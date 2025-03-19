<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Str;
use DateTime;


use App\Ticketing;
use App\TicketingDetail;
use App\TicketingActivity;
use App\TicketingResolve;
use App\TicketingClient;
use App\TicketingATM;
use App\TicketingATMPeripheral;
use App\TicketingSeverity;
use App\TicketingAbsen;
use App\TicketingSwitch;
use App\TicketingPendingReminder;
use App\TicketingEscalateEngineer;
use App\TicketingEmail;
use App\User;
use App\SalesProject;
use App\TicketingUser;
use App\AssetMgmt;
use App\AssetMgmtDetail;
use App\TicketingEmailSetting;
use App\TicketingEmailSLM;
use App\AssetManagement;
use App\AssetManagementAssignEngineer;
use App\TB_Contact;
use App\SLAProject;

use App\Service\TelegramService;

use Auth;
use Mail;
use Blade;
use App\Mail\EmailRemainderWeekly;
use App\Mail\EmailReOpenTicket;
use App\Mail\ApprovePendingTicket;
use App\Mail\RejectPendingTicket;

use Carbon\Carbon;
use Validator;
use Illuminate\Validation\Rule;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Log;

use App\RequestChange;

class TicketingController extends Controller
{

	public function __construct()
    {
        $this->telegramService = new TelegramService;
    }

	public function index() {

		$clients = DB::table('ticketing__client')
			->select('id','client_name','client_acronym','open_dear','close_dear')
			->selectRaw("REPLACE(`open_to`,';','<br>') AS `open_to`")
			->selectRaw("REPLACE(`open_cc`,';','<br>') AS `open_cc`")
			->selectRaw("REPLACE(`close_to`,';','<br>') AS `close_to`")
			->selectRaw("REPLACE(`close_cc`,';','<br>') AS `close_cc`")
			// ->where('situation','=',1)
			->get();

		return view('ticketing.index_copy')
			->with([
				'clients' => $clients,
				'initView' => $this->initMenuBase(),
				'sidebar_collapse' => 'true',
				'feature_item'=>$this->RoleDynamic('userSetting')
			]);
	}

	public function getClientByPid(Request $request)
	{
		if ($request->pid == "INTERNAL") {
			$getData = collect([
				["id"=>"INTERNAL"]
			]);
		}else{
			$getData = DB::table('tb_id_project')->join('sales_lead_register','sales_lead_register.lead_id','tb_id_project.lead_id')->join('tb_contact','tb_contact.id_customer','sales_lead_register.id_customer')->select(DB::raw("CONCAT(`tb_contact`.`code`, ' - ', `customer_legal_name`) AS `id`"),DB::raw("CONCAT(`tb_contact`.`code`, ' - ', `customer_legal_name`) AS `text`"))->where('id_project',$request->pid)->get();
		}

		return $getData;
	}

	public function storeAddMail(Request $request)
	{
		$store = new TicketingClient();
		$store->client_name = $request->client_name;
		$store->client_acronym = $request->client_acronym;
		$store->open_dear = $request->open_dear;
		$store->close_dear = $request->close_dear;
		$store->situation = '1';
		$store->banking = $request->banking;
		$store->wincor = $request->wincor;
		$store->open_to = $request->open_to;
		$store->open_cc = $request->open_cc;
		$store->close_to = $request->close_to;
		$store->close_cc = $request->close_cc;
		$store->save();
	}

	public function storeAddMailSetting(Request $request)
	{
		if (TicketingEmailSetting::where('pid',$request->pid)->exists()) {
			return response()->json(['data' => 'Data already exist!'], 500);
		}else{
			$store = new TicketingEmailSetting();
			$store->pid = $request->pid;
			$store->client = $request->client_acronym;
			$store->dear = $request->dear;
			$store->to = $request->to;
			$store->cc = $request->cc;
			$store->date_add = Carbon::now()->toDateTimeString();
			$store->operator = Auth::User()->name;
			$store->save();
		}
	}

	public function storeAddMailSLM(Request $request)
	{
		if (TicketingEmailSLM::where('second_level_support',$request->secondLevelSupport)->exists()) {
			return response()->json(['data' => 'Data already exist!'], 500);
		}else{
			$store = new TicketingEmailSLM();
			$store->second_level_support = $request->secondLevelSupport;
			$store->dear = $request->dear;
			$store->to = $request->to;
			$store->cc = $request->cc;
			$store->date_add = Carbon::now()->toDateTimeString();
			$store->operator = Auth::User()->name;
			$store->save();
		}
	}

	public function getSettingEmail()
	{
		$clients = TicketingClient::select('id', 'client_acronym', 'client_name', 'open_dear', 'close_dear')
			->selectRaw("REPLACE(`open_to`,';','<br>') AS `open_to`")
			->selectRaw("REPLACE(`open_cc`,';','<br>') AS `open_cc`")
			->selectRaw("REPLACE(`close_to`,';','<br>') AS `close_to`")
			->selectRaw("REPLACE(`close_cc`,';','<br>') AS `close_cc`")
			->orderBy('id', 'desc')
			->get();

		return array("data" => $clients);
	}

	public function getSettingEmailbyPID()
	{
		$clients = TicketingEmailSetting::select('id', 'client', 'pid', 'dear')
			->selectRaw("REPLACE(`to`,';','<br>') AS `to`")
			->selectRaw("REPLACE(`cc`,';','<br>') AS `cc`")
			->orderBy('id', 'desc')
			->get();

		return array("data" => $clients);
	}

	public function getSettingEmailSLM()
	{
		$clients = TicketingEmailSLM::select('id','second_level_support', 'dear')
			->selectRaw("REPLACE(`to`,';','<br>') AS `to`")
			->selectRaw("REPLACE(`cc`,';','<br>') AS `cc`")
			->orderBy('id', 'desc')
			->get();

		return array("data" => $clients);
	}

	public function getDashboardNeedAttention(Request $request)
	{
		$startDate = $request->start . ' 00:00:01';
		$endDate = $request->end . ' 23:59:59';
        if ($request->start != '' && $request->end != '') {
            $date = Carbon::create($request->start);
            $year = $date->year;
            // or
            $year = $date->format('Y');
        }else{
            $year = date('Y');
        }
		$nik = Auth::User()->nik;
        $cek_role = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role','group','mini_group')->where('user_id',$nik)->first();
        $nikEoS = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role','group','mini_group','nik')->where('roles.name','Engineer Onsite Enterprise')->orwhere('roles.name','Engineer Onsite SOC')->orwhere('roles.name','Engineer Onsite ATM')->orwhere('roles.name','Delivery Project Coordinator')->get()->pluck('nik');
        $nikCC = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role','group','mini_group','nik')->where('roles.name','Customer Support Center')->get()->pluck('nik');

        $getPid = DB::table('ticketing__user')->where('nik',Auth::User()->nik)->get()->pluck('pid');
        $getPidEoS = DB::table('ticketing__user')->whereIn('nik',$nikEoS)->get()->pluck('pid');
        $getPidCC = DB::table('ticketing__user')->whereIn('nik',$nikCC)->get()->pluck('pid');

        $occurring_ticket = DB::table('ticketing__activity')
			->select('id_ticket','activity')
			->whereIn('id',function ($query) use ($startDate,$endDate) {
				$query->select(DB::raw("MAX(id) AS activity"))
					->from('ticketing__activity')
					// ->where('id_ticket','like','%'.date('Y'))
					// ->whereRaw('ticketing__activity.date BETWEEN "' . $startDate . '" AND "' . $endDate . '"')
					// ->where('activity','<>','CLOSE')
		            // ->where('activity','<>','CANCEL')
		            // ->where('activity','<>','PENDING')
					->groupBy('id_ticket');
				})
            ->whereRaw('ticketing__activity.date BETWEEN "' . $startDate . '" AND "' . $endDate . '"')
			->where('activity','<>','CLOSE')
            ->where('activity','<>','CANCEL')
            // ->where('activity','<>','PENDING')
            // ->whereNotIn('activity', ['CLOSE', 'CANCEL', 'PENDING'])
            // ->where('id_ticket','like','%'.date('Y'))
			->get()
			->pluck('id_ticket');

		$needed = TicketingDetail::select('ticketing__detail.*')
		    ->addSelect([
		        'first_activity_date' => function ($query) {
		            $query->select('date')
		                ->from('ticketing__activity')
		                ->whereColumn('ticketing__activity.id_ticket', 'ticketing__detail.id_ticket')
		                ->orderBy('date', 'asc')
		                ->limit(1);
		        },
		        'lastest_activity_date' => function ($query) {
		            $query->select('date')
		                ->from('ticketing__activity')
		                ->whereColumn('ticketing__activity.id_ticket', 'ticketing__detail.id_ticket')
		                ->orderBy('date', 'desc')
		                ->limit(1);
		        },
		        'lastest_activity_operator' => function ($query) {
		            $query->select('operator')
		                ->from('ticketing__activity')
		                ->whereColumn('ticketing__activity.id_ticket', 'ticketing__detail.id_ticket')
		                ->orderBy('date', 'desc')
		                ->limit(1);
		        },
		        'lastest_activity_activity' => function ($query) {
		            $query->select('activity')
		                ->from('ticketing__activity')
		                ->whereColumn('ticketing__activity.id_ticket', 'ticketing__detail.id_ticket')
		                ->orderBy('date', 'desc')
		                ->limit(1);
		        },
			    ])
			    ->whereIn('id_ticket', $occurring_ticket)
			    ->orderByRaw('FIELD(ticketing__detail.severity, "1", "2", "3", "4") ASC');

		if (isset($request->pid)) {
		    $needed = $needed->where('pid', $request->pid);
		} else if (isset($request->client)) {
		    $needed = $needed->where('pid','like','%'. $request->client.'%');
		} else {
		    // if ($cek_role->name_role == 'Engineer Onsite Enterprise' || $cek_role->name_role == 'Engineer Onsite SOC' || $cek_role->name_role == 'Engineer Onsite ATM' || $cek_role->name_role == 'Delivery Project Coordinator'  || $cek_role->name_role == 'Customer Support Center') {
		    //     $needed = $needed->whereIn('pid', $getPid)->where('ticketing__detail.id_ticket', 'like', '%' . date('Y'));
		    // } elseif ($cek_role->name_role == 'Synergy System & Services Manager') {
		    //     $needed = $needed->whereIn('pid', $getPidEoS)->where('ticketing__detail.id_ticket', 'like', '%' . date('Y'));
		    // } elseif ($cek_role->name_role == 'Customer Relation Manager') {
		    //     $needed = $needed->whereIn('pid', $getPidCC)->where('ticketing__detail.id_ticket', 'like', '%' . date('Y'));
		    // }

		    $pidMap = [
		        'Engineer on Site' => $getPid,
		        'Customer Care' => $getPid,
		        'Managed Service Manager' => $getPidEoS,
		        'Customer Relation Manager' => $getPidCC,
		    ];
		    if (isset($pidMap[$cek_role->name_role])) {
		        $needed = $needed->whereIn('pid', $pidMap[$cek_role->name_role])
		                         ->where('ticketing__detail.id_ticket', 'like', '%' . $year);
		    }
		}

		if (isset($request->start) && isset($request->end)) {
		    $needed = $needed->whereRaw('ticketing__detail.reporting_time BETWEEN "' . $startDate . '" AND "' . $endDate . '"');
		} else {
		    $needed = $needed->where('ticketing__detail.id_ticket', 'like', '%' . date('Y'));
		}

		$needed = $needed->limit(10)->get();

		$now = new DateTime();

		$needed = $needed->map(function ($ticket) use ($now) {
		    $first_activity_date = new DateTime($ticket->first_activity_date);
		    $lastest_activity_date = new DateTime($ticket->lastest_activity_date);

		    $open_duration_interval = $lastest_activity_date->diff($now);
		    $open_duration = $open_duration_interval->format('%a days, %h hours, %i minutes');
		    $total_minutes = ($open_duration_interval->days * 24 * 60) + ($open_duration_interval->h * 60) + $open_duration_interval->i;

		    return [
		        "id" => $ticket->id,
		        "id_ticket" => $ticket->id_ticket,
		        "id_atm" => $ticket->id_atm,
		        "location" => $ticket->location,
		        "operator" => $ticket->lastest_activity_operator,
		        "date" => $ticket->lastest_activity_date,
		        "severity" => $ticket->severity,
		        "activity" => $ticket->lastest_activity_activity,
		        "first_activity_ticket" => $ticket->first_activity_date,
		        "open_duration" => $open_duration,
		        "total_minutes" => $total_minutes,
		    ];
		})->sort(function ($a, $b) {
		    return $a['severity'] <=> $b['severity'] ?: $b['total_minutes'] <=> $a['total_minutes'];
		})
		->values();

		return ["occurring_ticket" => $needed->all()];

		// $needed = $needed->map(function ($ticket) {
		//     $first_activity_date = new DateTime($ticket->first_activity_date);
		//     $lastest_activity_date = new DateTime($ticket->lastest_activity_date);
		//     $now = new DateTime();
		//     $open_duration_interval = $lastest_activity_date->diff($now);
		//     $open_duration = $open_duration_interval->format('%a days, %h hours, %i minutes');
		//     $total_minutes = ($open_duration_interval->days * 24 * 60) + ($open_duration_interval->h * 60) + $open_duration_interval->i;

		//     return [
		//         "id" => $ticket->id,
		//         "id_ticket" => $ticket->id_ticket,
		//         "id_atm" => $ticket->id_atm,
		//         "location" => $ticket->location,
		//         "operator" => $ticket->lastest_activity_operator,
		//         "date" => $ticket->lastest_activity_date,
		//         "severity" => $ticket->severity,
		//         "activity" => $ticket->lastest_activity_activity,
		//         "first_activity_ticket" => $ticket->first_activity_date,
		//         "open_duration" => $open_duration,
		//         "total_minutes" => $total_minutes,
		//     ];
		// });

		// $needed = $needed->sort(function ($a, $b) {
		//     if ($a['severity'] != $b['severity']) {
		//         return $a['severity'] - $b['severity'];
		//     }
		//     return $b['total_minutes'] - $a['total_minutes'];
		// });

		// return ["occurring_ticket" => $needed->values()->all()];
	}

	public function getDashboardByActivity(Request $request)
	{
		$startDate = $request->start . ' 00:00:01';
		$endDate = $request->end . ' 23:59:59';

		if ($request->start != '' && $request->end != '') {
			$date = Carbon::create($request->start);
			$year = $date->year; // Using the 'year' property
			// or
			$year = $date->format('Y'); // Using the format method
		}else{
			$year = date('Y');
		}

		$nik = Auth::User()->nik;
        $cek_role = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role','group','mini_group')->where('user_id',$nik)->first();

        $nikEoS = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role','group','mini_group','nik')->where('roles.name','Engineer Onsite Enterprise')->orwhere('roles.name','Engineer Onsite SOC')->orwhere('roles.name','Engineer Onsite ATM')->orwhere('roles.name','Delivery Project Coordinator')->get()->pluck('nik');
        $nikCC = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role','group','mini_group','nik')->where('roles.name','Customer Support Center')->get()->pluck('nik');

        $getPid = DB::table('ticketing__user')->where('nik',Auth::User()->nik)->get()->pluck('pid');
        $getPidEoS = DB::table('ticketing__user')->whereIn('nik',$nikEoS)->get()->pluck('pid');
        $getPidCC = DB::table('ticketing__user')->whereIn('nik',$nikCC)->get()->pluck('pid');

        $detail = DB::table('ticketing__detail')->select('id_ticket');

        if (isset($request->pid)) {
	        $detail = $detail->where('pid',$request->pid);
        }else if (isset($request->client)) {
	        $detail = $detail->where('pid','like','%'.$request->client.'%');
        }else {
        	if ($cek_role->name_role == 'Engineer Onsite Enterprise' || $cek_role->name_role == 'Engineer Onsite SOC' || $cek_role->name_role == 'Engineer Onsite ATM' || $cek_role->name_role == 'Delivery Project Coordinator'  || $cek_role->name_role == 'Customer Support Center') {
        		$detail = $detail->whereIn('pid',$getPid)->where('id_ticket','like','%'.$year);
        	} elseif($cek_role->name_role == 'Synergy System & Services Manager' || Auth::User()->nik = '1181195100'){
        		$detail = $detail->whereIn('pid',$getPidEoS)->where('id_ticket','like','%'.$year);
        	} elseif($cek_role->name_role == 'Customer Relation Manager'){
        		$detail = $detail->whereIn('pid',$getPidCC)->where('id_ticket','like','%'.$year);
        	} else {
        		$detail = $detail->where('id_ticket','like','%'.$year);
        	}
        } 

        if (isset($request->start) && isset($request->end)) {
        	$detail = $detail->whereRaw('`ticketing__detail`.`reporting_time` BETWEEN "' . $startDate . '" AND "' . $endDate . '"');
        }else{
        	$detail = $detail->where('id_ticket','like','%'.$year);
        }

        $detail = $detail->get()->pluck('id_ticket')->toArray();

        $detailNonImplode = $detail;

        $detail = implode("','", array_map('addslashes', $detail));
        $detail = "'$detail'";

        $result2 = DB::table('ticketing__condition')
			->select('name',DB::raw("IFNULL(`ticketing_activity`.`count`,0) AS `count`"))
			->join(DB::raw("(SELECT
				        `activity`,
				        COUNT(*) AS `count`
				    FROM
				        `ticketing__activity`
				    WHERE
				        `id` IN(
				        SELECT
				            MAX(`id`) AS `activity`
				        FROM
				            `ticketing__activity`
				        WHERE
		                `ticketing__activity`.`id_ticket` LIKE '%" . $year . "%'
		                 AND `ticketing__activity`.`id_ticket` IN ($detail)
				        GROUP BY
				            `id_ticket`
				    )
				GROUP BY
		    `activity`) AS `ticketing_activity`"),'ticketing_activity.activity','=','ticketing__condition.name','left')
		    ->get()->keyBy('name');

        $all = 0;
        foreach ($result2 as $key => $value) {
            $all = $all + $value->count;
        }

        $result2 = $result2->map(function($item, $key){
            return $item->count;
        });

        $result2->put("ALL",$all);
        $result2->put("PROGRESS",$result2["ON PROGRESS"]);
        $result2->forget("ON PROGRESS");

        return collect(['counter_condition'=>$result2]);
	}

	public function getDashboardResponse(Request $request)
	{
		$startDate = $request->start . ' 00:00:01';
		$endDate = $request->end . ' 23:59:59';
		$nik = Auth::User()->nik;
        $cek_role = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role','group','mini_group')->where('user_id',$nik)->first();

        $nikEoS = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role','group','mini_group','nik')->where('roles.name','Engineer Onsite Enterprise')->orwhere('roles.name','Engineer Onsite SOC')->orwhere('roles.name','Engineer Onsite ATM')->orwhere('roles.name','Delivery Project Coordinator')->get()->pluck('nik');
        $nikCC = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role','group','mini_group','nik')->where('roles.name','Customer Support Center')->get()->pluck('nik');

        $getPid = DB::table('ticketing__user')->where('nik',Auth::User()->nik)->get()->pluck('pid');
        $getPidEoS = DB::table('ticketing__user')->whereIn('nik',$nikEoS)->get()->pluck('pid');
        $getPidCC = DB::table('ticketing__user')->whereIn('nik',$nikCC)->get()->pluck('pid');

        $detail = DB::table('ticketing__detail')->select('id_ticket');

        if (isset($request->pid)) {
	        $detail = $detail->where('pid',$request->pid);
        }else if (isset($request->client)) {
	        $detail = $detail->where('pid','like','%'.$request->client.'%');
        }else {
        	if ($cek_role->name_role == 'Engineer Onsite Enterprise' || $cek_role->name_role == 'Engineer Onsite SOC' || $cek_role->name_role == 'Engineer Onsite ATM' || $cek_role->name_role == 'Delivery Project Coordinator'  || $cek_role->name_role == 'Customer Support Center') {
        		$detail = $detail->whereIn('pid',$getPid)->where('id_ticket','like','%'.date('Y'));
        	} elseif($cek_role->name_role == 'Synergy System & Services Manager'  || Auth::User()->nik = '1181195100'){
        		$detail = $detail->whereIn('pid',$getPidEoS)->where('id_ticket','like','%'.date('Y'));
        	} elseif($cek_role->name_role == 'Customer Relation Manager'){
        		$detail = $detail->whereIn('pid',$getPidCC)->where('id_ticket','like','%'.date('Y'));
        	} else {
        		$detail = $detail->where('id_ticket','like','%'.date('Y'));
        	}
        } 

        if (isset($request->start) && isset($request->end)) {
        	$detail = $detail->whereRaw('`ticketing__detail`.`reporting_time` BETWEEN "' . $startDate . '" AND "' . $endDate . '"');
        }else{
        	$detail = $detail->where('id_ticket','like','%'.date('Y'));
        }

        $detail = $detail->get()->pluck('id_ticket')->toArray();

        $occurring_ticket = DB::table('ticketing__activity as ta')
		    ->join(DB::raw('(SELECT MAX(id) as max_id FROM ticketing__activity GROUP BY id_ticket) as max_activity'), 
		           'ta.id', '=', 'max_activity.max_id')
		    ->where('ta.activity', 'CLOSE')
		    ->whereIn('ta.id_ticket', $detail)
		    ->where('ta.id_ticket', 'like', '%'.date('Y'))
		    ->pluck('ta.id_ticket');


        $tickets = TicketingDetail::with([
            'first_activity_ticket:id_ticket,date,operator',
            'lastest_activity_ticket',
            'id_detail:id_ticket,id' 
        ])->whereIn('ticketing__detail.id_ticket', $occurring_ticket)
        ->orderBy('ticketing__detail.id', 'DESC')->where('pid','!=',null)->get();

        // $statusSequence = DB::table('ticketing__activity')
        //     ->select('id_ticket', 'activity', 'date')
        //     ->selectRaw('ROW_NUMBER() OVER (PARTITION BY id_ticket ORDER BY date) as seq_num')
        //     ->whereRaw('`ticketing__activity`.`date` BETWEEN "' . $startDate . '" AND "' . $endDate . '"');

        // $pairedProgressPending = DB::table(DB::raw("({$statusSequence->toSql()}) as ppp"))
        // ->select('ppp.id_ticket', 'ppp.date as progress_time', DB::raw('MIN(n.date) as pending_time'))
        // ->leftJoin(DB::raw("({$statusSequence->toSql()}) as n"), function ($join) use ($startDate,$endDate){
        //     $join->on('ppp.id_ticket', '=', 'n.id_ticket')
        //          ->where('n.activity', 'PENDING')
        //          ->whereRaw('`n`.`date` BETWEEN "' . $startDate . '" AND "' . $endDate . '"')
        //          ->whereColumn('n.seq_num', '>', 'ppp.seq_num');
        // })
        // ->whereRaw('`ppp`.`date` BETWEEN "' . $startDate . '" AND "' . $endDate . '"')
        // ->where('ppp.activity', 'ON PROGRESS')

        // ->groupBy('ppp.id_ticket', 'ppp.date');

        // $progressToClose = DB::table('ticketing__activity as ptc')
        //     ->select('id_ticket', DB::raw('MIN(date) as close_time'))
        //     ->where('activity', 'CLOSE')
        // 	->whereRaw('`ptc`.`date` BETWEEN "' . $startDate . '" AND "' . $endDate . '"')
        //     ->groupBy('id_ticket');

        // $openToProgress = DB::table('ticketing__activity as opt')
        //     ->select(
        //         'id_ticket',
        //         DB::raw('MIN(CASE WHEN activity = "ON PROGRESS" THEN date END) as first_on_progress_time'),
        //         DB::raw('MIN(CASE WHEN activity = "OPEN" THEN date END) as open_time')
        //     )
        // 	->whereRaw('`opt`.`date` BETWEEN "' . $startDate . '" AND "' . $endDate . '"')
        //     ->groupBy('id_ticket');

        // $resolution_time = DB::table('ticketing__activity as pppp')
        //     ->select(
		//         // 'pppp.id_ticket',
		//         // DB::raw('SUM(TIMESTAMPDIFF(SECOND, progress_time, pending_time)) as total_progress_to_pending_seconds'),
		//         // DB::raw('SUM(CASE WHEN pending_time IS NOT NULL THEN 0 ELSE TIMESTAMPDIFF(SECOND, progress_time, close_time) END) as total_progress_to_close_seconds'),
		//         // DB::raw('MAX(TIMESTAMPDIFF(SECOND, opt.open_time, opt.first_on_progress_time)) as last_open_to_progress_seconds')
		//         'pppp.id_ticket',
        //         DB::raw('TIMESTAMPDIFF(SECOND, progress_time, pending_time) as progress_to_pending_seconds'),
        //         DB::raw('CASE WHEN pending_time IS NOT NULL THEN NULL ELSE TIMESTAMPDIFF(SECOND, progress_time, close_time) END as progress_to_close_seconds'),
        //         DB::raw('TIMESTAMPDIFF(SECOND, opt.open_time, opt.first_on_progress_time) as open_to_progress_seconds')
		//     )
        //     ->joinSub($pairedProgressPending, 'ppp', 'pppp.id_ticket', '=', 'ppp.id_ticket')
        //     ->joinSub($progressToClose, 'ptc', 'pppp.id_ticket', '=', 'ptc.id_ticket')
        //     ->joinSub($openToProgress,'opt', 'pppp.id_ticket', '=', 'opt.id_ticket')
        //     ->orderBy('pppp.id_ticket')
        //     ->whereIn('pppp.id_ticket',$tickets)
        //     // ->whereRaw('`pppp`.`date` BETWEEN "' . $startDate . '" AND "' . $endDate . '"')
        //     ->groupBy('pppp.id_ticket','progress_to_close_seconds','progress_to_pending_seconds','open_to_progress_seconds');

        // $resolution_time_summary = DB::table($resolution_time, 'resolution')
        //     ->select(
        //         'resolution.id_ticket',
        //         DB::raw('SUM(CASE WHEN resolution.progress_to_pending_seconds IS NOT NULL THEN resolution.progress_to_pending_seconds ELSE 0 END) as total_progress_to_pending_seconds'),
        //         DB::raw('SUM(CASE WHEN resolution.progress_to_close_seconds IS NOT NULL THEN resolution.progress_to_close_seconds ELSE 0 END) as total_progress_to_close_seconds'),
        //         DB::raw('MAX(resolution.open_to_progress_seconds) as last_open_to_progress_seconds')
        //     )
        //     ->groupBy('resolution.id_ticket')
        //     ->orderBy('resolution.id_ticket')
        //     ->get();

        // $occurring_ticket = DB::table('ticketing__activity as ta')
		//     ->join(DB::raw('(SELECT MAX(id) as max_id FROM ticketing__activity GROUP BY id_ticket) as max_activity'), 
		//            'ta.id', '=', 'max_activity.max_id')
		//     ->where('ta.activity', 'CLOSE')
		//     ->whereIn('ta.id_ticket', $detailNonImplode)
		//     ->where('ta.id_ticket', 'like', '%2024')
		//     ->pluck('ta.id_ticket');

        // $tickets = TicketingDetail::joinSub($resolution_time_summary, 'rts', function($join) {
	    //     $join->on('ticketing__detail.id_ticket', '=', 'rts.id_ticket');
	    // })
	    // ->select(
	    // 	'ticketing__detail.*',
	    //     'rts.total_progress_to_pending_seconds',
	    //     'rts.total_progress_to_close_seconds',
	    //     'rts.last_open_to_progress_seconds'
	    // )
        // ->whereIn('ticketing__detail.id_ticket', $occurring_ticket)
        // ->orderBy('ticketing__detail.id', 'DESC')->get();

        // $tickets->each(function ($ticket) use ($resolution_time_summary) {
        //     $resolutionData = $resolution_time_summary->firstWhere('id_ticket', $ticket->id_ticket);

        //     if ($resolutionData) {
        //         $ticket->progress_to_pending_seconds = $resolutionData->total_progress_to_pending_seconds;
        //         $ticket->progress_to_close_seconds = $resolutionData->total_progress_to_close_seconds;
        //         $ticket->open_to_progress_seconds = $resolutionData->last_open_to_progress_seconds;
        //     } else {
        //         $ticket->progress_to_pending_seconds = null;
        //         $ticket->progress_to_close_seconds = null;
        //         $ticket->open_to_progress_seconds = null;
        //     }
        // });

        // return $tickets;

        $SLAProjects = SLAProject::whereIn('pid', $tickets->pluck('pid')->unique()->toArray())
		    ->orWhere('pid', 'Standard')
		    ->get()
		    ->keyBy('pid');

		foreach ($tickets as $ticket) {
			$sla = $SLAProjects->get($ticket->pid) ?: $SLAProjects->get('Standard');

			if ($ticket->severity == '1') {
		        $ticket->sla_response = $sla->sla_response;
		    } elseif ($ticket->severity == '2') {
		        $ticket->sla_response = $sla->sla_response;
		    } elseif ($ticket->severity == '3') {
		        $ticket->sla_response = $sla->sla_response;
		    } else {
		        $ticket->sla_response = $sla->sla_response;
		    }

		    $firstActivity = $ticket->first_activity_ticket;
		    $lastActivity = $ticket->lastest_activity_ticket;

		    if ($firstActivity) {
		        $responseTimeInSeconds = strtotime($firstActivity->date) - strtotime($ticket->reporting_time);
		        $responseTimeInHours = $responseTimeInSeconds / 3600;

		        $ticket->highlight_sla_response = $responseTimeInHours <= $ticket->sla_response ? 'Comply' : 'Not-Comply';
		    } else {
		        $ticket->highlight_sla_response = 'Not-Comply';
		    }
		}

        $pids = $tickets->groupBy('pid');

        $resultCountResponse = $pids->map(function ($tickets, $pid) {
		    $complyCount = $tickets->where('highlight_sla_response', 'Comply')->count();
		    $notComplyCount = $tickets->where('highlight_sla_response', 'Not-Comply')->count();

		    return [
		        'pid' => $pid,
		        'comply' => $complyCount,
		        'not_comply' => $notComplyCount,
		        'total' => $complyCount + $notComplyCount
		    ];
		})->sortByDesc('not_comply')->values()->all();

        return array("data"=>$resultCountResponse);
	}

	public function getDashboardResolution(Request $request)
	{
		$startDate = $request->start . ' 00:00:01';
		$endDate = $request->end . ' 23:59:59';
		$nik = Auth::User()->nik;
        $cek_role = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role','group','mini_group')->where('user_id',$nik)->first();

        $nikEoS = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role','group','mini_group','nik')->where('roles.name','Engineer Onsite Enterprise')->orwhere('roles.name','Engineer Onsite SOC')->orwhere('roles.name','Engineer Onsite ATM')->orwhere('roles.name','Delivery Project Coordinator')->get()->pluck('nik');
        $nikCC = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role','group','mini_group','nik')->where('roles.name','Customer Support Center')->get()->pluck('nik');

        $getPid = DB::table('ticketing__user')->where('nik',Auth::User()->nik)->get()->pluck('pid');
        $getPidEoS = DB::table('ticketing__user')->whereIn('nik',$nikEoS)->get()->pluck('pid');
        $getPidCC = DB::table('ticketing__user')->whereIn('nik',$nikCC)->get()->pluck('pid');

        $detail = DB::table('ticketing__detail')->select('id_ticket');

        if (isset($request->pid)) {
	        $detail = $detail->where('pid',$request->pid);
        }else if(isset($request->client)){
	        $detail = $detail->where('pid','like','%'.$request->pid.'%');
        }else {
        	if ($cek_role->name_role == 'Engineer Onsite Enterprise' || $cek_role->name_role == 'Engineer Onsite SOC' || $cek_role->name_role == 'Engineer Onsite ATM' || $cek_role->name_role == 'Delivery Project Coordinator'  || $cek_role->name_role == 'Customer Support Center') {
        		$detail = $detail->whereIn('pid',$getPid)->where('id_ticket','like','%'.date('Y'));
        	} elseif($cek_role->name_role == 'Synergy System & Services Manager' || Auth::User()->nik = '1181195100'){
        		$detail = $detail->whereIn('pid',$getPidEoS)->where('id_ticket','like','%'.date('Y'));
        	} elseif($cek_role->name_role == 'Customer Relation Manager'){
        		$detail = $detail->whereIn('pid',$getPidCC)->where('id_ticket','like','%'.date('Y'));
        	} else {
        		$detail = $detail->where('id_ticket','like','%'.date('Y'));
        	}
        } 

        if (isset($request->start) && isset($request->end)) {
        	$detail = $detail->whereRaw('`ticketing__detail`.`reporting_time` BETWEEN "' . $startDate . '" AND "' . $endDate . '"');
        }else{
        	$detail = $detail->where('id_ticket','like','%'.date('Y'));
        }

        $detail = $detail->get()->pluck('id_ticket')->toArray();

		$occurring_ticket = DB::table('ticketing__activity as ta')
		    ->join(DB::raw('(SELECT MAX(id) as max_id FROM ticketing__activity GROUP BY id_ticket) as max_activity'), 
		           'ta.id', '=', 'max_activity.max_id')
		    ->where('ta.activity', 'CLOSE')
		    ->whereIn('ta.id_ticket', $detail)
		    ->where('ta.id_ticket', 'like', '%'.date('Y'))
		    ->pluck('ta.id_ticket');

        $tickets = TicketingDetail::with([
            'first_activity_ticket:id_ticket,date,operator',
            'lastest_activity_ticket',
            'id_detail:id_ticket,id' 
        ])->whereIn('ticketing__detail.id_ticket', $occurring_ticket)
        ->orderBy('ticketing__detail.id', 'DESC')->where('pid','!=',null);

        $statusSequence = DB::table('ticketing__activity')
		    ->select('id_ticket', 'activity', 'date')
		    ->selectRaw('ROW_NUMBER() OVER (PARTITION BY id_ticket ORDER BY date) AS seq_num')
		    ->whereRaw('`ticketing__activity`.`date` BETWEEN "' . $startDate . '" AND "' . $endDate . '"');

		$pairedProgressPending = DB::table($statusSequence, 'ppp')
		    ->select('ppp.id_ticket', 'ppp.date AS progress_time', DB::raw('MIN(n.date) AS pending_time'))
		    ->leftJoinSub($statusSequence,'n', function ($join) use ($startDate,$endDate) {
		        $join->on('ppp.id_ticket', '=', 'n.id_ticket')
		            ->where('n.activity', 'PENDING')
		            ->whereRaw('`n`.`date` BETWEEN "' . $startDate . '" AND "' . $endDate . '"')
		            ->whereColumn('n.seq_num', '>', 'ppp.seq_num');
		    })
		    ->where('ppp.activity', 'ON PROGRESS')
		    ->whereRaw('`ppp`.`date` BETWEEN "' . $startDate . '" AND "' . $endDate . '"')
		    ->groupBy('ppp.id_ticket', 'ppp.date');        

        $progressToClose = DB::table('ticketing__activity as ptc')
            ->select('id_ticket', DB::raw('MIN(date) as close_time'))
            ->where('activity', 'CLOSE')
            ->whereRaw('`ptc`.`date` BETWEEN "' . $startDate . '" AND "' . $endDate . '"')
            ->groupBy('id_ticket');        

        $openToProgress = DB::table('ticketing__activity as opt')
            ->select(
                'id_ticket',
                DB::raw('MIN(CASE WHEN activity = "ON PROGRESS" THEN date END) as first_on_progress_time'),
                DB::raw('MIN(CASE WHEN activity = "OPEN" THEN date END) as open_time')
            )
            ->whereRaw('`opt`.`date` BETWEEN "' . $startDate . '" AND "' . $endDate . '"')
            ->groupBy('id_ticket');            

        $resolution_time = DB::table('ticketing__activity as pppp')
            ->select(
                'pppp.id_ticket',
                DB::raw('TIMESTAMPDIFF(SECOND, progress_time, pending_time) as progress_to_pending_seconds'),
                DB::raw('CASE WHEN pending_time IS NOT NULL THEN NULL ELSE TIMESTAMPDIFF(SECOND, progress_time, close_time) END as progress_to_close_seconds'),
                DB::raw('TIMESTAMPDIFF(SECOND, opt.open_time, opt.first_on_progress_time) as open_to_progress_seconds')
            )
            ->leftJoinSub($pairedProgressPending, 'ppp', 'pppp.id_ticket', '=', 'ppp.id_ticket')
            ->leftJoinSub($progressToClose, 'ptc', 'pppp.id_ticket', '=', 'ptc.id_ticket')
            ->leftJoinSub($openToProgress,'opt', 'pppp.id_ticket', '=', 'opt.id_ticket')
            ->orderBy('pppp.id_ticket')
            // ->where('pppp.id_ticket','like','%'.date('Y'))
            ->whereIn('pppp.id_ticket', $tickets->pluck('id_ticket'))
            ->groupBy('pppp.id_ticket','progress_to_pending_seconds','progress_to_close_seconds','open_to_progress_seconds');

        $resolution_time_summary = DB::table($resolution_time, 'resolution')
            ->select(
                'resolution.id_ticket',
                DB::raw('SUM(CASE WHEN resolution.progress_to_pending_seconds IS NOT NULL THEN resolution.progress_to_pending_seconds ELSE 0 END) as total_progress_to_pending_seconds'),
                // DB::raw('SUM(CASE WHEN resolution.progress_to_close_seconds IS NOT NULL THEN resolution.progress_to_close_seconds ELSE 0 END) as total_progress_to_close_seconds'),
				DB::raw('MAX(resolution.progress_to_close_seconds) as total_progress_to_close_seconds'),
                DB::raw('MAX(resolution.open_to_progress_seconds) as last_open_to_progress_seconds')
            )
            ->groupBy('resolution.id_ticket')
            ->orderBy('resolution.id_ticket');

        $ticketsWithResolutionTime = $tickets->leftJoinSub($resolution_time_summary, 'resolution_summary', function ($join) {
		    $join->on('ticketing__detail.id_ticket', '=', 'resolution_summary.id_ticket');
		})
		->select('ticketing__detail.*', 
		         'resolution_summary.total_progress_to_pending_seconds', 
		         'resolution_summary.total_progress_to_close_seconds', 
		         'resolution_summary.last_open_to_progress_seconds')
		->get();

		foreach ($ticketsWithResolutionTime as $ticket) {
			if ($ticket->pid == '') {
				$cekSla = SLAProject::where('pid','Standard');
			} elseif(isset($ticket->pid)){
				$cekSla = SLAProject::where('pid',$ticket->pid);
			} 

			if ($cekSla->first() !== null) {
				if ($ticket->severity == '1') {
					$cekSla = $cekSla->select('sla_response','sla_resolution_critical as sla_resolution')->first();
				} elseif ($ticket->severity == '2') {
					$cekSla = $cekSla->select('sla_response','sla_resolution_major as sla_resolution')->first();
				} elseif ($ticket->severity == '3') {
					$cekSla = $cekSla->select('sla_response','sla_resolution_moderate as sla_resolution')->first();
				} elseif ($ticket->severity == '4') {
					$cekSla = $cekSla->select('sla_response','sla_resolution_minor as sla_resolution')->first();
				} elseif ($ticket->severity == '0'){
					$cekSla = $cekSla->select('sla_response','sla_resolution_minor as sla_resolution')->first();
				}
			}else{
				$cekSla = collect(["sla_response"=>0,"sla_resolution"=>0]);
			}
			

		    $firstActivity = $ticket->first_activity_ticket;
		    $lastActivity = $ticket->lastest_activity_ticket;

		    if ($firstActivity && $lastActivity) {
                $resolutionTimeInHours = ((float)$ticket->open_to_progress_seconds + (float)$ticket->progress_to_pending_seconds + $ticket->progress_to_close_seconds) / 3600;
                $formattedTime         = $this->formatResolutionTime($resolutionTimeInHours);

                $ticket->sla_resolution_percentage = $formattedTime;

                if ($resolutionTimeInHours <= $cekSla['sla_resolution']) {
                	if ($resolutionTimeInHours == 0) {
                		$ticket->highlight_sla_resolution = 'Not-Comply';
                	}else{
                    	$ticket->highlight_sla_resolution = 'Comply';
                	}
                } else {
                    $ticket->highlight_sla_resolution = 'Not-Comply';
                }
		    } else {
		        $ticket->sla_resolution_percentage = '-'; 
		        $ticket->highlight_sla_resolution = 'Not-Comply';
		    }
		}

        $resultCountResolution = [];
        $pids = $ticketsWithResolutionTime->groupBy('pid');

        foreach ($pids as $pid => $tickets) {
            $severityCount = [
                'Critical' => ['comply' => 0, 'not_comply' => 0],
                'Major' => ['comply' => 0, 'not_comply' => 0],
                'Moderate' => ['comply' => 0, 'not_comply' => 0],
                'Minor' => ['comply' => 0, 'not_comply' => 0]
            ];

            foreach ($tickets as $ticket) {
                $severity = '';

                if ($ticket->severity == '1') {
                    $severity = 'Critical';
                } elseif ($ticket->severity == '2') {
                    $severity = 'Major';
                } elseif ($ticket->severity == '3') {
                    $severity = 'Moderate';
                } elseif ($ticket->severity == '4' || $ticket->severity == '0') {
                    $severity = 'Minor';
                }

                if ($ticket->highlight_sla_resolution == 'Comply') {
                    $severityCount[$severity]['comply']++;
                } elseif ($ticket->highlight_sla_resolution == 'Not-Comply') {
                    $severityCount[$severity]['not_comply']++;
                }
            }

            $totalComply = $severityCount['Critical']['comply'] + 
                   $severityCount['Major']['comply'] + 
                   $severityCount['Moderate']['comply'] + 
                   $severityCount['Minor']['comply'];

            $totalNotComply = $severityCount['Critical']['not_comply'] + 
                              $severityCount['Major']['not_comply'] + 
                              $severityCount['Moderate']['not_comply'] + 
                              $severityCount['Minor']['not_comply'];

            $resultCountResolution[] = [
                'pid' => $pid,
                'critical' => $severityCount['Critical'],
                'major' => $severityCount['Major'],
                'moderate' => $severityCount['Moderate'],
                'minor' => $severityCount['Minor'],
                'total' => [
                    'comply' => $totalComply,
                    'not_comply' => $totalNotComply
                ]
            ];
        }

        usort($resultCountResolution, function ($a, $b) {
			return $b['total']['not_comply'] <=> $a['total']['not_comply'];
		});

        // $limitResultCountResolution = array_slice($resultCountResolution, 0, 10);


        return array("data"=>$resultCountResolution);
	}

	public function getDashboard() {

		$nik = Auth::User()->nik;
        $cek_role = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role','group','mini_group')->where('user_id',$nik)->first();

        $nikEoS = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role','group','mini_group','nik')->where('roles.name','Engineer Onsite Enterprise')->orwhere('roles.name','Engineer Onsite SOC')->orwhere('roles.name','Engineer Onsite ATM')->orwhere('roles.name','Delivery Project Coordinator')->get()->pluck('nik');
        $nikCC = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role','group','mini_group','nik')->where('roles.name','Customer Support Center')->get()->pluck('nik');

        if ($cek_role->name_role == 'Engineer Onsite Enterprise' || $cek_role->name_role == 'Engineer Onsite SOC' || $cek_role->name_role == 'Engineer Onsite ATM' || $cek_role->name_role == 'Delivery Project Coordinator' || $cek_role->name_role == 'Customer Support Center') {
        	$getPid = DB::table('ticketing__user')->where('nik',Auth::User()->nik)->get()->pluck('pid');
        } else {
        	$getPid = [];
        }
        $getPidEoS = DB::table('ticketing__user')->whereIn('nik',$nikEoS)->get()->pluck('pid');
        $getPidCC = DB::table('ticketing__user')->whereIn('nik',$nikCC)->get()->pluck('pid');

        $severity_count = DB::table('ticketing__detail')
            ->select('ticketing__severity.name',DB::raw('COUNT(*) as count'))
            ->join('ticketing__severity','ticketing__severity.id','=','ticketing__detail.severity')
            ->where('ticketing__detail.severity','<>',0)
            ->groupBy('ticketing__detail.severity')
            ->where('id_ticket','like','%'.date('Y'));

        if ($cek_role->name_role == 'Synergy System & Services Manager' || Auth::User()->nik = '1181195100') {
            $severity_count = $severity_count->whereIn('pid',$getPidEoS)->get()
            ->keyBy('name');
        } elseif($cek_role->name_role == 'Engineer Onsite Enterprise' || $cek_role->name_role == 'Engineer Onsite SOC' || $cek_role->name_role == 'Engineer Onsite ATM' || $cek_role->name_role == 'Delivery Project Coordinator' || $cek_role->name_role == 'Customer Support Center'){
            $severity_count = $severity_count->whereIn('pid',$getPid)->get()
            ->keyBy('name');
        } elseif ($cek_role->name_role == 'Customer Relation Manager') {
            $severity_count = $severity_count->whereIn('pid',$getPidCC)->get()
            ->keyBy('name');
        } else {
            $severity_count = $severity_count->get()
            ->keyBy('name');
        }

        if($cek_role->name_role == 'Engineer Onsite Enterprise' || $cek_role->name_role == 'Engineer Onsite SOC' || $cek_role->name_role == 'Engineer Onsite ATM' || $cek_role->name_role == 'Delivery Project Coordinator' ){
			$get_client = DB::table('tb_id_project')->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
            ->join('ticketing__user','ticketing__user.pid','=','tb_id_project.id_project')
            ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
            ->select('tb_contact.id_customer as id','code as client_acronym','brand_name as client_name')
            ->orderBy('code')
            ->groupBy('tb_contact.id_customer','code','brand_name')
			->where('ticketing__user.nik',$nik)->get();
		}else if ($cek_role->name_role == "Customer Support Center" || $cek_role->name_role = 'Chief Operating Officer' || $cek_role->name_role == 'VP Solutions & Partnership Management' || $cek_role->name_role == 'Customer Relation Manager') {
			$get_client = DB::table('tb_id_project')->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
            ->join('ticketing__user','ticketing__user.pid','=','tb_id_project.id_project')
            ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
            ->select('tb_contact.id_customer as id','code as client_acronym','brand_name as client_name')
            ->orderBy('code')
            ->groupBy('tb_contact.id_customer','code','brand_name')->get();

        	$get_client = $get_client->prepend((object)(['id' => 'INTERNAL','client_acronym' => 'INTERNAL','client_name' => 'INTERNAL']))->prepend((object)(['id' => '13','client_acronym' => 'ADMF','client_name' => 'PT. Adira Finance, Tbk']));
		} elseif ($cek_role->name_role == 'Synergy System & Services Manager' || Auth::User()->nik = '1181195100') {
			$get_client = DB::table('tb_id_project')->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
            ->join('ticketing__user','ticketing__user.pid','=','tb_id_project.id_project')
            ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
            ->select('tb_contact.id_customer as id','code as client_acronym','brand_name as client_name')
            ->orderBy('code')
            ->groupBy('tb_contact.id_customer','code','brand_name')
			->whereIn('ticketing__user.nik',$getPidEoS)->get();
		}else{
			$get_client = DB::table('tb_id_project')->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
            ->join('ticketing__user','ticketing__user.pid','=','tb_id_project.id_project')
            ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
            ->select('tb_contact.id_customer as id','code as client_acronym','brand_name as client_name')
            ->orderBy('code')
            ->groupBy('tb_contact.id_customer','code','brand_name')
			->where('ticketing__user.nik',Auth::User()->nik)->get();
		}

        // $count_ticket_by_client = DB::table('ticketing__id')
        //     ->leftJoin('ticketing__client', 'ticketing__client.id', '=', 'ticketing__id.id_client')
        //     ->leftJoin('ticketing__email_setting', 'ticketing__email_setting.id', '=', 'ticketing__id.id_client_pid')
        //     ->leftJoin('ticketing__detail','ticketing__detail.id_ticket','ticketing__id.id_ticket')
        //     ->select(DB::raw("
        //         CASE 
        //             WHEN ticketing__client.client_acronym IN ('BJBR', 'BBJB', 'BANK JABAR') THEN 'BBJB'
        //             WHEN ticketing__client.client_acronym IN ('BKES','BPJS') THEN 'BKES'
        //             WHEN ticketing__client.client_acronym IN ('PBLG', 'BULG') THEN 'BULG'
        //             WHEN ticketing__client.client_acronym IN ('BGDN', 'PGAN') THEN 'PGAN'
        //             WHEN ticketing__client.client_acronym IN ('ADRF', 'ADMF') THEN 'ADMF'
        //             WHEN ticketing__client.client_acronym IN ('BTNI', 'BBTN') THEN 'BBTN'
        //             ELSE COALESCE(ticketing__client.client_acronym, SUBSTRING_INDEX(SUBSTRING_INDEX(ticketing__email_setting.client, '/', -2), ' ', 1))
        //         END AS client_acronym
        //     "), DB::raw('COUNT(ticketing__id.id) as ticket_count'))
        //     ->where('ticketing__id.id_ticket', 'like', '%' . date('Y') . '%')
        //     ->groupBy(DB::raw("
        //         CASE 
        //             WHEN ticketing__client.client_acronym IN ('BJBR','BBJB', 'BANK JABAR') THEN 'BBJB'
        //             WHEN ticketing__client.client_acronym IN ('BKES','BPJS') THEN 'BKES'
        //             WHEN ticketing__client.client_acronym IN ('PBLG', 'BULG') THEN 'BULG'
        //             WHEN ticketing__client.client_acronym IN ('BGDN', 'PGAN') THEN 'PGAN'
        //             WHEN ticketing__client.client_acronym IN ('ADRF', 'ADMF') THEN 'ADMF'
        //             WHEN ticketing__client.client_acronym IN ('BTNI', 'BBTN') THEN 'BBTN'
        //             ELSE COALESCE(ticketing__client.client_acronym, SUBSTRING_INDEX(SUBSTRING_INDEX(ticketing__email_setting.client, '/', -2), ' ', 1))
        //         END
        //     "))
        //     ->orderBy('ticket_count', 'DESC');

        // if ($cek_role->name_role == 'Engineer Onsite Enterprise' || $cek_role->name_role == 'Engineer Onsite SOC' || $cek_role->name_role == 'Engineer Onsite ATM' || $cek_role->name_role == 'Delivery Project Coordinator'  || $cek_role->name_role == 'Customer Support Center') {
        //     $count_ticket_by_client = $count_ticket_by_client->whereIn('ticketing__detail.pid',$getPid)->limit(10)
        //     ->get();
        // } elseif($cek_role->name_role == 'Synergy System & Services Manager'){
        //     $count_ticket_by_client = $count_ticket_by_client->whereIn('ticketing__detail.pid',$getPidEoS)->limit(10)
        //     ->get();
        // } elseif ($cek_role->name_role == 'Customer Relation Manager') {
        //     $count_ticket_by_client = $count_ticket_by_client->whereIn('ticketing__detail.pid',$getPidCC)->limit(10)
        //     ->get();
        // } else {
        //     $count_ticket_by_client = $count_ticket_by_client->limit(10)->get();
        // }

        $severity_count = $severity_count->map(function($item, $key){
            return $item->count;
        });

        $severity_label = TicketingSeverity::select('id','name')->orderBy('id','DESC')->get();

        return collect([
            "counter_severity" => $severity_count,
            "customer_list" => $get_client,
            "severity_label" => $severity_label
        ]);
	}

	public function getDashboardByFilter(Request $request) {
        $startDate = $request->start . ' 00:00:01';
        $endDate = $request->end . ' 23:59:59';

        $nik = Auth::User()->nik;
        $cek_role = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role','group','mini_group')->where('user_id',$nik)->first();

        $nikEoS = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role','group','mini_group','nik')->where('roles.name','Engineer Onsite Enterprise')->orwhere('roles.name','Engineer Onsite SOC')->orwhere('roles.name','Engineer Onsite ATM')->orwhere('roles.name','Delivery Project Coordinator')->get()->pluck('nik');
        $nikCC = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role','group','mini_group','nik')->where('roles.name','Customer Support Center')->get()->pluck('nik');

        $getPid = DB::table('ticketing__user')->where('nik',Auth::User()->nik)->get()->pluck('pid');
        $getPidEoS = DB::table('ticketing__user')->whereIn('nik',$nikEoS)->get()->pluck('pid');
        $getPidCC = DB::table('ticketing__user')->whereIn('nik',$nikCC)->get()->pluck('pid');

        $severity_count = DB::table('ticketing__detail')
            ->select('ticketing__severity.name',DB::raw('COUNT(*) as count'))
            ->join('ticketing__severity','ticketing__severity.id','=','ticketing__detail.severity')
            ->where('ticketing__detail.severity','<>',0)
            ->groupBy('ticketing__detail.severity');

        if (isset($request->pid)) {
            $severity_count = $severity_count->where('pid',$request->pid);
        }else if(isset($request->client)){
            $severity_count = $severity_count->where('pid','like','%'.$request->client.'%');
        }else{
            if ($cek_role->name_role == 'Engineer Onsite Enterprise' || $cek_role->name_role == 'Engineer Onsite SOC' || $cek_role->name_role == 'Engineer Onsite ATM' || $cek_role->name_role == 'Delivery Project Coordinator' || $cek_role->name_role == 'Customer Support Center') {
                $severity_count = $severity_count->whereIn('pid',$getPid);
            } elseif($cek_role->name_role == 'Synergy System & Services Manager' || Auth::User()->nik = '1181195100'){
                $severity_count = $severity_count->whereIn('pid',$getPidEoS);
            } elseif ($cek_role->name_role == 'Customer Relation Manager') {
                $severity_count = $severity_count->whereIn('pid',$getPidCC);
            } else {
                $severity_count = $severity_count;
            }
        }

        if (isset($request->start) && isset($request->end)) {
            $severity_count = $severity_count->whereRaw('`ticketing__detail`.`reporting_time` BETWEEN "' . $startDate . '" AND "' . $endDate . '"');
        }

        $severity_count = $severity_count->get()->keyBy('name');

        $get_client = DB::table('tb_id_project')->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
            ->join('ticketing__user','ticketing__user.pid','=','tb_id_project.id_project')
            ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
            ->select('tb_contact.id_customer as id','code as client_acronym','brand_name as client_name')
            ->orderBy('code')
            ->groupBy('tb_contact.id_customer','code','brand_name');

        if ($cek_role->name_role == "Customer Support Center" || $cek_role->name_role = 'Chief Operating Officer' || $cek_role->name_role == 'VP Solutions & Partnership Management' || $cek_role->name_role == 'Customer Relation Manager') {
            $get_client = DB::table('tb_id_project')->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
            ->join('ticketing__user','ticketing__user.pid','=','tb_id_project.id_project')
            ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
            ->select('tb_contact.id_customer as id','code as client_acronym','brand_name as client_name')
            ->orderBy('code')
            ->groupBy('tb_contact.id_customer','code','brand_name')->get();

            $get_client = $get_client->prepend((object)(['id' => 'INTERNAL','client_acronym' => 'INTERNAL','client_name' => 'INTERNAL']))->prepend((object)(['id' => '13','client_acronym' => 'ADMF','client_name' => 'PT. Adira Finance, Tbk']));
        } elseif ($cek_role->name_role == 'Synergy System & Services Manager' || Auth::User()->nik = '1181195100') {
            $get_client = DB::table('tb_id_project')->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
            ->join('ticketing__user','ticketing__user.pid','=','tb_id_project.id_project')
            ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
            ->select('tb_contact.id_customer as id','code as client_acronym','brand_name as client_name')
            ->orderBy('code')
            ->groupBy('tb_contact.id_customer','code','brand_name')
            ->whereIn('ticketing__user.nik',$getPidEoS)->get();
        } else{
            $get_client = DB::table('tb_id_project')->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
            ->join('ticketing__user','ticketing__user.pid','=','tb_id_project.id_project')
            ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
            ->select('tb_contact.id_customer as id','code as client_acronym','brand_name as client_name')
            ->orderBy('code')
            ->groupBy('tb_contact.id_customer','code','brand_name')
            ->where('ticketing__user.nik',Auth::User()->nik)->get();
        }

        // $get_client = DB::table('tb_id_project')->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
        //     ->join('ticketing__user','ticketing__user.pid','=','tb_id_project.id_project')
        //     ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
        //     ->select('tb_contact.id_customer as id','code as client_acronym','brand_name as client_name')
        //     ->orderBy('code')
        //     ->groupBy('tb_contact.id_customer','code','brand_name')->get();

        // $get_client = $get_client->prepend((object)(['id' => 'INTERNAL','client_acronym' => 'INTERNAL','client_name' => 'INTERNAL']))->prepend((object)(['id' => '13','client_acronym' => 'ADMF','client_name' => 'PT. Adira Finance, Tbk']));

        // $count_ticket_by_client = DB::table('ticketing__id')
        //     ->leftJoin('ticketing__client', 'ticketing__client.id', '=', 'ticketing__id.id_client')
        //     ->leftJoin('ticketing__email_setting', 'ticketing__email_setting.id', '=', 'ticketing__id.id_client_pid')
        //     ->leftJoin('ticketing__detail','ticketing__detail.id_ticket','ticketing__id.id_ticket')
        //     ->select(DB::raw("
        //         CASE 
        //             WHEN ticketing__client.client_acronym IN ('BJBR', 'BBJB', 'BANK JABAR') THEN 'BBJB'
        //             WHEN ticketing__client.client_acronym IN ('BKES','BPJS') THEN 'BKES'
        //             WHEN ticketing__client.client_acronym IN ('PBLG', 'BULG') THEN 'BULG'
        //             WHEN ticketing__client.client_acronym IN ('BGDN', 'PGAN') THEN 'PGAN'
        //             WHEN ticketing__client.client_acronym IN ('ADRF', 'ADMF') THEN 'ADMF'
        //             WHEN ticketing__client.client_acronym IN ('BTNI', 'BBTN') THEN 'BBTN'
        //             ELSE COALESCE(ticketing__client.client_acronym, SUBSTRING_INDEX(SUBSTRING_INDEX(ticketing__email_setting.client, '/', -2), ' ', 1))
        //         END AS client_acronym
        //     "), DB::raw('COUNT(ticketing__id.id) as ticket_count'))
        //     ->groupBy(DB::raw("
        //         CASE 
        //             WHEN ticketing__client.client_acronym IN ('BJBR','BBJB', 'BANK JABAR') THEN 'BBJB'
        //             WHEN ticketing__client.client_acronym IN ('BKES','BPJS') THEN 'BKES'
        //             WHEN ticketing__client.client_acronym IN ('PBLG', 'BULG') THEN 'BULG'
        //             WHEN ticketing__client.client_acronym IN ('BGDN', 'PGAN') THEN 'PGAN'
        //             WHEN ticketing__client.client_acronym IN ('ADRF', 'ADMF') THEN 'ADMF'
        //             WHEN ticketing__client.client_acronym IN ('BTNI', 'BBTN') THEN 'BBTN'
        //             ELSE COALESCE(ticketing__client.client_acronym, SUBSTRING_INDEX(SUBSTRING_INDEX(ticketing__email_setting.client, '/', -2), ' ', 1))
        //         END
        //     "))
        //     ->orderBy('ticket_count', 'DESC');

        // if (isset($request->pid)) {
        //  $count_ticket_by_client = $count_ticket_by_client->where('ticketing__detail.pid',$request->pid);
        // }else {
        //  if ($cek_role->name_role == 'Engineer Onsite Enterprise' || $cek_role->name_role == 'Engineer Onsite SOC' || $cek_role->name_role == 'Engineer Onsite ATM' || $cek_role->name_role == 'Delivery Project Coordinator'  || $cek_role->name_role == 'Customer Support Center') {
        //         $count_ticket_by_client = $count_ticket_by_client->whereIn('ticketing__detail.pid',$getPid)->where('ticketing__id.id_ticket','like','%'.date('Y'));
        //     } elseif($cek_role->name_role == 'Synergy System & Services Manager'){
        //         $count_ticket_by_client = $count_ticket_by_client->whereIn('ticketing__detail.pid',$getPidEoS)->where('ticketing__id.id_ticket','like','%'.date('Y'));
        //     } elseif ($cek_role->name_role == 'Customer Relation Manager') {
        //         $count_ticket_by_client = $count_ticket_by_client->whereIn('ticketing__detail.pid',$getPidCC)->where('ticketing__id.id_ticket','like','%'.date('Y'));
        //     } else {
        //         $count_ticket_by_client = $count_ticket_by_client->where('ticketing__id.id_ticket','like','%'.date('Y'));
        //     }
        // }

        $severity_count = $severity_count->map(function($item, $key){
            return $item->count;
        });

        $severity_label = TicketingSeverity::select('id','name')->orderBy('id','DESC')->get();


        return collect([
            // "counter_condition" => $result2,
            "counter_severity" => $severity_count,
            // "occurring_ticket" => $needed,
            "customer_list" => $get_client,
            // "chart_data" => [
            //     "label" => $count_ticket_by_client->pluck('client_acronym'),
            //     "data" => $count_ticket_by_client->pluck('ticket_count')
            // ],
            "severity_label" => $severity_label
            // "response_time" => array("data" => $resultCountResponse),
            // "resolution_time" => array("data" => $resultCountResolution)
        ]);
    }

	public function calculateSlaResolutionPercentage($resolutionTimeInHours, $totalHoursInMonth) {
	    return (($resolutionTimeInHours / $totalHoursInMonth) * 100) - 100;
	}

	public function getCreateParameter(){
		// $client = TicketingClient::where('situation','=',1)
		// 	->select('id','client_name','client_acronym')
		// 	->orderBy('client_acronym')
		// 	->get();\
		$cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')
                    ->select('name')->where('user_id', Auth::User()->nik)->first();

		if ($cek_role->name == "Customer Support Center" || $cek_role->name == 'IT Internal') {
			$client = DB::table('tb_id_project')->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
			->join('ticketing__user','ticketing__user.pid','=','tb_id_project.id_project')
			->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
			->select('tb_contact.id_customer as id','code','customer_legal_name as brand_name')
			->orderBy('code')
			->groupBy('tb_contact.id_customer','code','brand_name')->get();

			$client = $client->prepend((object)(['id' => 'INTERNAL','code' => 'INTERNAL','brand_name' => 'INTERNAL']))->prepend((object)(['id' => '13','code' => 'ADMF','brand_name' => 'PT. Adira Finance, Tbk']));
		}else{
			$client = DB::table('tb_id_project')->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
			->join('ticketing__user','ticketing__user.pid','=','tb_id_project.id_project')
			->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
			->select('tb_contact.id_customer as id','code','customer_legal_name as brand_name')
			->orderBy('code')
			->groupBy('tb_contact.id_customer','code','brand_name')
			->where('ticketing__user.nik',Auth::User()->nik)->get();
		}

		$severity = TicketingSeverity::select('id','name','description')->get();

		$email_template = TicketingEmail::where('activity','Open')->get();
		
		return collect([
			"client" => $client,
			"severity" => $severity,
			"email_template" => $email_template
		]);
	}

	public function getReserveIdTicket(){
		return Ticketing::orderBy('id','DESC')->first()->id + 1;
	}

	public function setReserveIdTicket(Request $req){
		$newTicketId = new Ticketing();
		$newTicketId->id_ticket 	= $req->id_ticket;
		// $client 					= TicketingClient::find($req->id_client);
		// $newTicketId->id_client 	= $client->id;
		$newTicketId->operator 		= Auth::user()->name;
		$client = TicketingEmailSetting::where('pid',$req->pid)->first();
		$newTicketId->id_client_pid = $client->id;
		// if (isset($req->pid)) {
		// 	$client = TicketingEmailSetting::where('pid',$req->pid)->first();
		// 	$newTicketId->id_ticket_pid = $client->id;
		// }else{
		// 	$client 					= TicketingClient::find($req->id_client);
		// 	$newTicketId->id_client 	= $client->id;
		// }

		$newTicketId->save();

		// return $client;
		// return collect([
		// 	"banking" => $client->banking,
		// 	"wincor" => $client->wincor
		// ]);
	}

	public function putReserveIdTicket(Request $req){
		$updateTicketId = Ticketing::where('id_ticket',$req->id_ticket_before)->first();
		$updateTicketId->id_ticket = $req->id_ticket_after;
		$client = TicketingEmailSetting::where('pid',$req->pid)->first();
		$updateTicketId->id_client_pid = $client->id;
		// if (isset($req->pid)) {
		// 	$client = TicketingEmailSetting::where('pid',$req->pid)->first();
		// 	$updateTicketId->id_ticket_pid = $client->id;
		// }else{
		// 	$client 					= TicketingClient::find($req->id_client);
		// 	$updateTicketId->id_client 	= $client->id;
		// }
		$updateTicketId->save();
		// return collect([
		// 	"banking" => $client->banking,
		// 	"wincor" => $client->wincor
		// ]);
	}

	public function checkPidReserve(Request $req){
		if ($req->pid == 13) {
			$req->pid = 'ADMF';
		}
		$check = TicketingEmailSetting::where('pid',$req->pid)->first();

		if (isset($check)) {
			return response()->json(['data' => true], 200);
		}else{
			return response()->json(['data' => false], 200);
		}
	}

	public function getAssetByPid(Request $request)
	{
		$getId = AssetMgmt::leftjoin('tb_asset_management_detail','tb_asset_management_detail.id_asset','tb_asset_management.id')->select('tb_asset_management_detail.id_asset','detail_lokasi','tb_asset_management_detail.id');
        $getLastId = DB::table($getId,'temp')->groupBy('id_asset')->selectRaw('MAX(`temp`.`id`) as `id_last_asset`')->selectRaw('id_asset');

        // return $getLastId->id_last_asset;

        $data = DB::table($getLastId, 'temp2')->join('tb_asset_management','tb_asset_management.id','temp2.id_asset')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')
            ->select('tb_asset_management.id',
            	// DB::raw('CONCAT(`id_device_customer`," - ", `alamat_lokasi`," - ", `serial_number`) AS `text`'),
            	DB::raw("(CASE WHEN serial_number IS NULL THEN CONCAT(kota, ' - ', alamat_lokasi) WHEN serial_number = '' THEN CONCAT(kota, ' - ', alamat_lokasi) ELSE CONCAT(id_device_customer, ' - ', alamat_lokasi, ' - ', category, ' - ', vendor, ' - ', type_device, ' - ', serial_number) END) as text"))
            ->orderBy('tb_asset_management.created_at','desc')->where('pid',$request->pid)->where('category',$request->category)
            ->where(DB::raw("CONCAT(id_device_customer, ' - ', alamat_lokasi, ' - ', category, ' - ', vendor, ' - ' , type_device, ' - ', serial_number)"), 'like', '%' . request('q') . '%')
            ->get();

        return $data;
	}

	public function getAssetByClient(Request $request)
	{

		$getId = AssetMgmt::leftjoin('tb_asset_management_detail','tb_asset_management_detail.id_asset','tb_asset_management.id')->select('tb_asset_management_detail.id_asset','detail_lokasi','tb_asset_management_detail.id');
        $getLastId = DB::table($getId,'temp')->groupBy('id_asset')->selectRaw('MAX(`temp`.`id`) as `id_last_asset`')->selectRaw('id_asset');
        // return $getLastId->id_last_asset;
        if ($request->client == "INTERNAL") {

      //   	$data = DB::table('tb_asset_management as t_asset')
		    // ->join(DB::raw('(SELECT id_asset,MAX(tb_asset_management_detail.date_add) as max_date FROM tb_asset_management_detail GROUP BY id_asset) as t_asset_detail'), function ($join) {
		    //     $join->on('t_asset.id', '=', 't_asset_detail.id_asset');
		    // })
		    // ->join('users','users.nik','=','t_asset_detail.pic')
      //       ->join('role_user','role_user.user_id','=','users.nik')
      //       ->join('roles','roles.id','=','role_user.role_id')
		    // ->select('t_asset.id', DB::raw("(CASE WHEN serial_number IS NULL THEN CONCAT(CONCAT(users.name,' - ',roles.name), ' - ',category,' - ',type_device) 
      //           	WHEN serial_number = '' THEN CONCAT(CONCAT(users.name,' - ',roles.name), ' - ',category,' - ',type_device) 
      //           	ELSE CONCAT(CONCAT(users.name,' - ',roles.name), ' - ',category,' - ',type_device,' - ',serial_number) END) as text"))
      //       ->where(DB::raw("CONCAT(CONCAT(users.name,' - ',roles.name), ' - ',category,' - ',type_device,' - ',serial_number)"),'like','%'.request('q').'%')
      //       ->orderBy('t_asset.created_at','desc')
		    // ->get();

        	// $data = AssetMgmtDetail::join('tb_asset_management','tb_asset_management.id','=','tb_asset_management_detail.id_asset')
        	// ->join('users','users.nik','=','tb_asset_management_detail.pic')
            // ->join('role_user','role_user.user_id','=','users.nik')
            // ->join('roles','roles.id','=','role_user.role_id')
            // ->select(
            // 	"tb_asset_management.id",
            //     DB::raw("(CASE WHEN serial_number IS NULL THEN CONCAT(CONCAT(users.name,' - ',roles.name), ' - ',category,' - ',type_device) 
            //     	WHEN serial_number = '' THEN CONCAT(CONCAT(users.name,' - ',roles.name), ' - ',category,' - ',type_device) 
            //     	ELSE CONCAT(CONCAT(users.name,' - ',roles.name), ' - ',category,' - ',type_device,' - ',serial_number) END) as text"),
            // 	DB::raw("MAX(date_add) AS date_add")
            // )
            // ->groupBy('id','text')
            // ->orderBy('tb_asset_management.created_at','desc')
            // ->where('tb_asset_management_detail.pid',$request->client)
            // ->where('tb_asset_management_detail.pic','<>',null)
            // ->distinct()
            // ->get();

            $data = DB::table($getLastId, 'temp2')
            ->join('tb_asset_management','tb_asset_management.id','temp2.id_asset')
            ->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')
            ->leftjoin('users','users.nik','=','tb_asset_management_detail.pic')
            ->leftjoin('role_user','role_user.user_id','=','users.nik')
            ->leftjoin('roles','roles.id','=','role_user.role_id')
            ->select(
            	"tb_asset_management.id",
                DB::raw("(CASE WHEN serial_number IS NULL THEN CONCAT(CONCAT('(',users.name,' - ',roles.name,')'), ' - ',category,' - ',type_device) 
                	WHEN serial_number = '' THEN CONCAT(CONCAT('(',users.name,' - ',roles.name,')'), ' - ',category,' - ',type_device) 
                	ELSE CONCAT(CONCAT('(',users.name,' - ',roles.name,')'), ' - ',category,' - ',type_device,' - ',serial_number) END) as text")
            )
            ->where('tb_asset_management_detail.pid',$request->client)
            ->where('pic','!=',null)
            ->groupBy(
                'tb_asset_management.id',
                'text'
            )
            ->orderBy('tb_asset_management.created_at','desc')->get(); 
        }else{
        	$client = TB_Contact::where('id_customer',$request->client)->first()->customer_legal_name;
        	// DB::table($getLastId, 'temp2')
        	// ->join('tb_asset_management','tb_asset_management.id','temp2.id_asset')
        	// ->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')
        	$data = DB::table($getLastId, 'temp2')
        	->join('tb_asset_management','tb_asset_management.id','temp2.id_asset')
        	->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')
            ->select('tb_asset_management.id',
            	// DB::raw('CONCAT(`id_device_customer`," - ", `alamat_lokasi`," - ", `serial_number`) AS `text`'),
            	DB::raw("(CASE WHEN serial_number IS NULL THEN CONCAT(kota, ' - ', alamat_lokasi) WHEN serial_number = '' THEN CONCAT(kota, ' - ', alamat_lokasi) ELSE CONCAT(id_device_customer, ' - ', alamat_lokasi, ' - ', category, ' - ', vendor, ' - ', type_device, ' - ', serial_number) END) as text"))
            // ->orderBy('tb_asset_management.created_at','desc')
            // ->where('client','like','%'.$client.'%')
            ->orderBy('tb_asset_management.created_at','desc')->where('client','like','%'.$client.'%')
            ->where(DB::raw("CONCAT(id_device_customer, ' - ', alamat_lokasi, ' - ', category, ' - ', vendor, ' - ' , type_device, ' - ', serial_number)"),'like','%'.request('q').'%')
            ->get();
        }

        return $data;
	}

	public function getAtmId(Request $request){
		if($request->acronym == "BDIY"){
			$request->client_id = 19;
		}else if($request->acronym == "BNTT"){
			$request->client_id = 48;
		}

		// return TicketingATM::where('owner',TicketingClient::where('client_acronym',$client_acronym)->first()->id)
		// 	->select(
		// 		'id',
		// 		DB::raw('CONCAT(`atm_id`," - ", `location`) AS `text`')
		// 	)
		// 	->get()->all();

		// return $request->client_id;

		return TicketingATM::where('owner',$request->client_id)
			->select(
				'id',
				DB::raw('CONCAT(`atm_id`," - ", `location`) AS `text`')
			)
			->get()->all();
	}

	public function getAbsenId(Request $req){
		return TicketingAbsen::select(
				'id',
				DB::raw('CONCAT(`nama_cabang`," - ", `nama_kantor`) AS `text`')
			)
			->get()->all();
	}

	public function getSwitchId(Request $req){
		return TicketingSwitch::select(
				'id',
				DB::raw('CONCAT(`location`," - ",`cabang`," [",`serial_number`,"]") AS `text`')
			)
			->get()->all();
	}

	public function getDetailAsset(Request $request)
    {
    	// return $request->id_asset;
        $getId = AssetMgmt::join('tb_asset_management_detail','tb_asset_management_detail.id_asset','tb_asset_management.id')->select('tb_asset_management_detail.id_asset','detail_lokasi','tb_asset_management_detail.id','second_level_support');
        $getLastId = DB::table($getId,'temp')->groupBy('id_asset')->selectRaw('MAX(`temp`.`id`) as `id_last_asset`');
        // return $getLastId->id_last_asset;

        $getAll = DB::table($getLastId, 'temp2')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')->join('tb_asset_management','tb_asset_management_detail.id_asset','tb_asset_management.id')
        	// ->leftJoin('tb_asset_management_assign_engineer','tb_asset_management.id','tb_asset_management_assign_engineer.id_asset')
            ->select('tb_asset_management_detail.id_asset','id_device_customer','client','pid','kota','alamat_lokasi','detail_lokasi','ip_address','server','port','status_cust','second_level_support','operating_system','version_os','installed_date','license','license_end_date','license_start_date','maintenance_end','maintenance_start','notes','rma','spesifikasi','type_device','serial_number','vendor','category','category_peripheral','asset_owner','related_id_asset',DB::raw("(CASE WHEN (category_peripheral = '-') THEN 'asset' WHEN (category_peripheral != '-') THEN 'peripheral' END) as type"),'status',DB::raw("TIMESTAMPDIFF(HOUR, concat(maintenance_start,' 00:00:00'), concat(maintenance_end,' 00:00:00')) AS slaPlanned"))
            ->where('tb_asset_management_detail.id_asset',$request->id_asset)
            ->first();

        $getEngineer = DB::table('tb_asset_management_assign_engineer as ae')
            ->join('users', 'ae.engineer_atm', 'users.name')
            ->join('presence__history as p','users.nik','p.nik')
            ->select('engineer_atm','role')
            ->where('id_asset',$request->id_asset)
            ->whereDate('p.presence_actual', Carbon::today())
            ->get();

        if (isset($request->id_asset)) {
	        if (TicketingEmailSLM::where('second_level_support',$getId->where('tb_asset_management_detail.id_asset',$request->id_asset)->first()->second_level_support)->first() != "") {
				if ($getAll) {
				    $getAll->engineers = $getEngineer;
				}

				$getData = collect($getAll);
			}else{
				if ($request->client != "INTERNAL") {
					$getData = response()->json(['data' => 'Please add email setting for this SLM!'], 500);
				}else{
					$getData = collect($getAll);
				}
			}
        }else{
			$getData = collect($getAll);
        }
		

        return $getData;
    }

	public function getAtmDetail(Request $request){
		return TicketingATM::where('id',$request->id_atm)->first();
	}

	public function getAbsenDetail(Request $request){
		return TicketingAbsen::where('id',$request->id_absen)->first();
	}

	public function getSwitchDetail(Request $request){
		return TicketingSwitch::where('id',$request->id_switch)->first();
	}

	public function getAtmPeripheralDetail(Request $request){
		if($request->type == "CCTV"){
			$result = TicketingATMPeripheral::with('atm')
				->where('id_atm',TicketingATM::where('id',$request->id_atm)->first()->id)
				->where('type',$request->type)
				->first();
			$result->serial_number = "DVR : " . $result->cctv_dvr_sn . "<br>" . "CCTV Eksternal : " . $result->cctv_besar_sn . "<br>" . "CCTV Internal : " . $result->cctv_kecil_sn . "<br>";
			$result->machine_type = "DVR : " . $result->cctv_dvr_type . "<br>" . "CCTV Eksternal : " . $result->cctv_besar_type . "<br>" . "CCTV Internal : " . $result->cctv_kecil_type . "<br>";
			
			return $result;
		} else {
			return TicketingATMPeripheral::with('atm')
				->where('id_atm',TicketingATM::where('id',$request->id_atm)->first()->id)
				->where('type',$request->type)
				->first();
		}
	}

	public function getClient(Request $request)
    {
        $nik = Auth::User()->nik;
        $cek_role = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role','group','mini_group')->where('user_id',$nik)->first();

        $nikEoS = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role','group','mini_group','nik')->where('roles.name','Engineer Onsite Enterprise')->orwhere('roles.name','Engineer Onsite SOC')->orwhere('roles.name','Engineer Onsite ATM')->orwhere('roles.name','Delivery Project Coordinator')->get()->pluck('nik');
        $nikCC = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role','group','mini_group','nik')->where('roles.name','Customer Support Center')->get()->pluck('nik');

        // $getClient = DB::table('ticketing__id')
        // 			->join('ticketing__client','ticketing__client.id','=','ticketing__id.id_client')
        // 			->select('ticketing__client.id as id', 'ticketing__client.client_name as text')
        // 			->groupBy('ticketing__client.id')
        // 			->where('client_name','like','%'.request('q').'%')
        // 			->get();

        // $getClientPid = DB::table('ticketing__id')
        // 			->join('ticketing__email_setting','ticketing__email_setting.id','=','ticketing__id.id_client_pid')
        // 			->select('ticketing__email_setting.id as id', 'ticketing__email_setting.client as text')
        // 			->groupBy('ticketing__email_setting.client','ticketing__email_setting.id')
        // 			->where('client','like','%'.request('q').'%')
        // 			->get();

        $getClient = DB::table('ticketing__user')
        				->join('tb_id_project','tb_id_project.id_project','=','ticketing__user.pid')
        				->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
        				->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
        				// ->join('ticketing__email_setting','ticketing__email_setting.pid','=','ticketing__user.pid')
        				->select('code as id', 
        					DB::raw('CONCAT(code," - ",brand_name) as text'))
        				->where(DB::raw('CONCAT(code," - ",brand_name)'),'like','%'.request('q').'%');

        if ($cek_role->name_role == 'Engineer Onsite Enterprise' || $cek_role->name_role == 'Engineer Onsite SOC' || $cek_role->name_role == 'Engineer Onsite ATM' || $cek_role->name_role == 'Delivery Project Coordinator'  || $cek_role->name_role == 'Customer Support Center') {
            $getClient = $getClient->where('ticketing__user.nik',$nik)->distinct()->get();
        } elseif($cek_role->name_role == 'Synergy System & Services Manager'  || Auth::User()->nik = '1181195100'){
            $getClient = $getClient->whereIn('ticketing__user.nik',$nikEoS)->distinct()->get();
        } elseif ($cek_role->name_role == 'Customer Relation Manager') {
            $getClient = $getClient->whereIn('ticketing__user.nik',$nikCC)->distinct()->get();
        } else {
            $getClient = $getClient->distinct()->get();
        }

        return $getClient;
    }

	public function getPid(Request $request)
    {
        $nik = Auth::User()->nik;
        $cek_role = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role','group','mini_group')->where('user_id',$nik)->first();

        $nikEoS = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role','group','mini_group','nik')->where('roles.name','Engineer Onsite Enterprise')->orwhere('roles.name','Engineer Onsite SOC')->orwhere('roles.name','Engineer Onsite ATM')->orwhere('roles.name','Delivery Project Coordinator')->get()->pluck('nik');
        $nikCC = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role','group','mini_group','nik')->where('roles.name','Customer Support Center')->get()->pluck('nik');

        if (isset($request->code)) {
        	$getPid = DB::table('ticketing__user')->select('pid as id', 'pid as text')
        			->where('pid','like','%'.$request->code.'%')
        			->where('pid','like','%'.request('q').'%');
        }else{
        	$getPid = DB::table('ticketing__user')->select('pid as id', 'pid as text')->where('pid','like','%'.request('q').'%');
        }

        if ($cek_role->name_role == 'Engineer Onsite Enterprise' || $cek_role->name_role == 'Engineer Onsite SOC' || $cek_role->name_role == 'Engineer Onsite ATM' || $cek_role->name_role == 'Delivery Project Coordinator'  || $cek_role->name_role == 'Customer Support Center') {
            $getPid = $getPid->where('nik',$nik)->distinct()->get();
        } elseif($cek_role->name_role == 'Synergy System & Services Manager'  || Auth::User()->nik = '1181195100'){
            $getPid = $getPid->whereIn('nik',$nikEoS)->distinct()->get();
        } elseif ($cek_role->name_role == 'Customer Relation Manager') {
            $getPid = $getPid->whereIn('nik',$nikCC)->distinct()->get();
        } else {
            $getPid = $getPid->distinct()->get();
        }

        return $getPid;
    }

	public function getEmailData(Request $req){
		// return $req->client;

		if ($req->client == 13) {
			$req->client = 'ADMF';
		}
		if(isset($req->client)){
			if (isset($req->slm)) {
				$openCustomer = TicketingEmailSetting::select(DB::raw("CONCAT(`to`,';',`cc`) AS open_cc"))->where('pid',$req->client)->first()->open_cc;
				$openSLM = TicketingEmailSLM::select(
					'dear as open_dear',
					'to as open_to',
					'cc as open_cc')->where('second_level_support',$req->slm)->first();
				if ($openSLM && $openCustomer) {
				    $openSLM->open_cc = $openSLM->open_cc ? $openSLM->open_cc . ';' . $openCustomer : $openCustomer;
				}

				return $openSLM;
			}else{
				return TicketingEmailSetting::select('dear as open_dear',
					'to as open_to',
					'cc as open_cc',
					DB::raw("TRIM(SUBSTRING_INDEX(client, ' - ', -1)) as client_name"))
				->where('pid',$req->client)->first();
			}
		} else {
			$idTicket = $req->id_ticket;
            $ticket_data = TicketingDetail::whereHas('id_detail', function($query) use ($idTicket){
                    $query->where('id','=',$idTicket);
                })


                ->with([
                    'lastest_activity_ticket:id_ticket,date,activity,operator',
                    'resolve',
                    'first_activity_ticket',
                    'severity_detail:id,name'
                ])
                ->first();

            $cek_id_client_pid = Ticketing::where('id_ticket',$idTicket)->orderby('id','desc')->first();

            $cekIdAsset = DB::table('ticketing__detail')->select('serial_device')->where('id_ticket',$req->id_ticket)->first();

            // if(isset($cekIdAsset)){
                if ($cekIdAsset->serial_device != "-") {
                    $serial_number = $cekIdAsset->serial_device;

                    $cekId = DB::table('tb_asset_management')->select('id')->where('serial_number',$serial_number)->first();

                    $cekSlm = DB::table('tb_asset_management_detail')->select('second_level_support')->where('id_asset',$cekId->id)->first();
                }
            // }
			
			if (isset($req->id_atm)) {
				if (isset($cek_id_client_pid->id_client_pid)) {
					if (TicketingEmailSetting::where('id',$cek_id_client_pid->id_client_pid)->first()->pid == "INTERNAL") {
						$ticket_reciver = TicketingEmailSetting::select(
							'dear as close_dear',
							'to as close_to',
							'cc as close_cc',
						DB::raw("TRIM(SUBSTRING_INDEX(client, ' - ', -1)) as client_name")
						)->where('id',$cek_id_client_pid->id_client_pid)
						->first();
					}else{
						$closeCustomer = TicketingEmailSetting::select(DB::raw("CONCAT(`to`,';',`cc`) AS close_cc"))
							->where('id',$cek_id_client_pid->id_client_pid)
							->first()->close_cc;

						$closeSLM = TicketingEmailSLM::select(
							'dear as close_dear',
							'to as close_to',
							'cc as close_cc')->where('second_level_support',$cekSlm->second_level_support)->first();

						if ($closeSLM && $closeCustomer) {
						    $closeSLM->close_cc = $closeSLM->close_cc ? $closeSLM->open_cc . ';' . $closeCustomer : $closeCustomer;
						}

						$slm = AssetMgmtDetail::where('id_device_customer',$req->id_atm)->first()->second_level_support;
						$openCustomer = TicketingEmailSetting::select(DB::raw("CONCAT(`to`,';',`cc`) AS open_cc"))->where('id',$cek_id_client_pid->id_client_pid)->first()->open_cc;
						$ticket_reciver = TicketingEmailSLM::select(
							'dear as close_dear',
							'to as close_to',
							'cc as close_cc',
							// DB::raw("TRIM(SUBSTRING_INDEX(client, ' - ', -1)) as client_name")
							'second_level_support'
						)->where('second_level_support',$slm)
						->first();

						if ($ticket_reciver && $openCustomer) {
						    $ticket_reciver->close_cc = $ticket_reciver->close_cc ? $ticket_reciver->close_cc . ';' . $openCustomer : $openCustomer;
						}

						if ($ticket_reciver->close_cc == null) {
							$ticket_reciver->close_cc = $closeSLM->close_cc;
						}
					}
				}else{
					$ticket_reciver = Ticketing::where('id',$idTicket)
					->first()
					->client_ticket;
				}
			}else{
				if (isset($cek_id_client_pid->id_client_pid)) {
					$ticket_reciver = TicketingEmailSetting::select(
						'dear as close_dear',
						'to as close_to',
						'cc as close_cc',
					DB::raw("TRIM(SUBSTRING_INDEX(client, ' - ', -1)) as client_name"))->where('id',$cek_id_client_pid->id_client_pid)
					->first();
				}else{
					$ticket_reciver = Ticketing::where('id',$idTicket)
					->first()
					->client_ticket;
				}
			}
			

			if(isset($ticket_data->id_atm)){
				// return $ticket_data->atm_detail;
				$ticket_data->atm_detail = AssetMgmt::where('id',$ticket_data->id_atm)->first();

				// if(str_contains($ticket_reciver->client_name,"UPS")){
				// 	$ticket_data->atm_detail = AssetMgmt::where('id',$ticket_data->id_atm)->first();
				// }
			}else{
				$ticket_data = TicketingDetail::whereHas('id_detail', function($query) use ($idTicket){
					$query->where('id_ticket','=',$idTicket);
				})
				->with([
					'lastest_activity_ticket:id_ticket,date,activity,operator',
					'resolve',
					'first_activity_ticket',
					'severity_detail:id,name'
				])
				->first();
			}

			return collect([
				"ticket_data" => $ticket_data,
				"ticket_reciver" => $ticket_reciver
			]);
		}

		// $idTicket = $req->id_ticket;
		// $ticket_data = TicketingDetail::whereHas('id_detail', function($query) use ($idTicket){
		// 	$query->where('id','=',$idTicket);
		// })
		// ->with([
		// 	'lastest_activity_ticket:id_ticket,date,activity,operator',
		// 	'resolve',
		// 	'first_activity_ticket',
		// 	'severity_detail:id,name'
		// ])
		// ->first();

		// if (isset($req->slm)) {
		// 	// $ticket_reciver = Ticketing::where('id',$idTicket)
		// 	// ->first()
		// 	// ->client_ticket;
		// 	// $slm = AssetMgmtDetail::where('id_device_customer',$req->id_atm)->first()->second_level_support;
		// 	$ticket_reciver = TicketingEmailSLM::select('dear as close_dear','to as close_to','cc as close_cc')->where('second_level_support',$req->slm)
		// 	->first();
		// }else{
		// 	$ticket_reciver = Ticketing::where('id',$idTicket)
		// 	->first()
		// 	->client_ticket;
		// }
		

		// // if(isset($ticket_data->id_atm)){
		// // 	$ticket_data->atm_detail = AssetMgmtDetail::where('id_device_customer',$ticket_data->id_atm)->first();

		// // 	// if(str_contains($ticket_reciver->client_name,"UPS")){
		// // 	// 	$ticket_data->atm_detail = AssetMgmt::where('id',$ticket_data->id_atm)->first();
		// // 	// }
		// // } else {
		// // 	$ticket_data->atm_detail = null;
		// // }
		// return collect([
		// 	"ticket_data" => $ticket_data,
		// 	"ticket_reciver" => $ticket_reciver
		// ]);
	}

	public function getEmailTemplate(Request $req){
		$return = TicketingEmail::where('activity','=',$req->email_activity);

		if($req->email_name != "Wincor Template"){
			$return->where('type','=',$req->email_type);	
		}

		$return->where('name','=',$req->email_name);

		// dd();

		return view(["template" => $return->first()->body]);
		// return view('ticketing.mail.OpenTicketHitachi');
	}

	public function getOpenMailTemplate(Request $req){
		if($req->type == "normal") {
			return view('ticketing.mail.OpenTicket');
		} else if($req->type == "wincor") {
			return view('ticketing.mail.OpenTicketWincor');
		}
	}

	public function sendEmailOpen(Request $request){
		// return $request->id_ticket;
		try {
			DB::beginTransaction();
			$detailTicketOpen = new TicketingDetail();
			$detailTicketOpen->id_ticket = $request->id_ticket;
			
			if($request->absen != "-"){
				$detailTicketOpen->id_atm = $request->absen;
			} else if($request->switchLocation != "-"){
				$detailTicketOpen->id_atm = $request->switchLocation;
			} else {
				if($request->id_atm != null || $request->id_atm != ""){
	
					$atm = AssetMgmtDetail::where('id_asset',$request->id_atm)->orderby('id','desc')->first();
	
					$detailTicketOpen->id_atm = $atm->id_device_customer;
				} else {
					$detailTicketOpen->id_atm = $request->id_atm;
				}
			}
	
			$detailTicketOpen->refrence = $request->refrence;
			$detailTicketOpen->pic = $request->pic;
			$detailTicketOpen->contact_pic = $request->contact_pic;
			$detailTicketOpen->location = $request->location;
			$detailTicketOpen->problem = $request->problem;
			$detailTicketOpen->serial_device = $request->serial_device;
			$detailTicketOpen->note = $request->note;
			$detailTicketOpen->reporting_time = $request->report;
			$detailTicketOpen->severity = substr($request->severity,0,1);
			$detailTicketOpen->type_ticket = $request->type_ticket;
			$detailTicketOpen->pid = $request->pid;
	
			if($request->engineer != ""){
				$detailTicketOpen->engineer = $request->engineer;
			}
	
			$detailTicketOpen->save();
	
			//Start Notification Job SLM
	
			if($request->id_atm != null || $request->client != 'BBJB'){
				 $this->setNotif($detailTicketOpen->engineer, $request->id_ticket, $request->location);
			}
			//End Notification Job SLM
	
			$this->sendEmail($request->to,$request->cc,$request->subject,$request->body);
            $cekId = optional(Ticketing::where('id_ticket', $request->id_ticket)->first())->id_client_pid;
            if ($cekId) {
                $cekTeleId = TicketingEmailSetting::where('id', $cekId)->first();
                if ($cekTeleId) {
                    $bodyMassage = 'Dear Tim '.$cekTeleId->client . ', Terdapat open ticket, dengan ID <b>' . $request->id_ticket . '</b> yang berlokasi di <b>' . $request->location . '</b>, dengan problem <b>'. $request->problem . '</b>. Terima kasih.';

                    if (isset($cekTeleId->chat_id)) {
                        $this->telegramService->sendMessage($cekTeleId->chat_id, $bodyMassage);
                    }
                }
            }
	
			$activityTicketOpen = new TicketingActivity();
			$activityTicketOpen->id_ticket = $request->id_ticket;
			$activityTicketOpen->date = date("Y-m-d H:i:s.000000");
			$activityTicketOpen->activity = "OPEN";
			$activityTicketOpen->operator = Auth::user()->name;
			$activityTicketOpen->note = "Open Ticket";
	
			$activityTicketOpen->save();
			
			if($request->type_ticket == "PM"){
				$detailTicketOpen->reporting_time = date("Y-m-d H:i:s.000000");
				$detailTicketOpen->save();
			}
	
			$activityTicketOpen->client_id_filter = $request->clientID;
			DB::commit();
			return $activityTicketOpen;
		} catch (Exception $e) {
			DB::rollBack();
			return false;
		}
	}

	public function sendEmail($to, $cc, $subject, $body){
		$response = Mail::html($body, function ($message) use ($to, $cc, $subject) {
			$message
				->from('helpdesk@sinergy.co.id','Helpdesk Sinergy')
				->to(explode(";", $to))
				->subject($subject);

			if($cc != ""){
				$message->cc(explode(";", $cc));
			}
		});

	}

	public function sendEmailSlm($to, $cc, $subject, $body, $driveId = null)
	{
		$attachment = null;
		if ($driveId) {
			$attachment = $this->downloadGoogleDriveFile($driveId); 
		}

		$response = Mail::html($body, function ($message) use ($to, $cc, $subject, $attachment) {
			$message
				->from('helpdesk@sinergy.co.id', 'Helpdesk Sinergy')
				->to(explode(";", $to))
				->subject($subject);

			if ($cc != "") {
				$message->cc(explode(";", $cc));
			}

			if ($attachment) {
				$message->attach($attachment);
			}
		});

		if ($attachment) {
			unlink($attachment); 
		}
	}

	private function downloadGoogleDriveFile($fileId)
	{

		$downloadLink = "https://drive.google.com/uc?export=download&id=$fileId";

		$tempFilePath = storage_path('app/public/Service-Report.pdf');

		try {
			file_put_contents($tempFilePath, file_get_contents($downloadLink));
		} catch (Exception $e) {
			Log::error('Failed to download file: ' . $e->getMessage());
			return null;
		}

		return $tempFilePath;
	}

	public function getPerformanceAll(){
		// sleep(5);
		// dd(Auth::user());
		$cek_role = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role','group','mini_group')->where('user_id',Auth::User()->nik)->first();
        $nikEoS = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role','group','mini_group','nik')->where('roles.name','Engineer Onsite Enterprise')->orwhere('roles.name','Engineer Onsite SOC')->orwhere('roles.name','Engineer Onsite ATM')->orwhere('roles.name','Delivery Project Coordinator')->get()->pluck('nik');
        $nikCC = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role','group','mini_group','nik')->where('roles.name','Customer Support Center')->get()->pluck('nik');

        // $getPid = DB::table('ticketing__user')->where('nik',Auth::User()->nik)->get()->pluck('pid');
        // $getPidEoS = DB::table('ticketing__user')->whereIn('nik',$nikEoS)->get()->pluck('pid');
        // $getPidCC = DB::table('ticketing__user')->whereIn('nik',$nikCC)->get()->pluck('pid');

		$getPid = TicketingUser::select('pid');

		if ($cek_role->name_role == 'Synergy System & Services Manager') {
            $getPid = $getPid->whereIn('nik',$nikEoS)->get()->pluck('pid');
        } elseif(($cek_role->name_role == 'Engineer Onsite Enterprise' || $cek_role->name_role == 'Engineer Onsite SOC' || $cek_role->name_role == 'Engineer Onsite ATM' || $cek_role->name_role == 'Delivery Project Coordinator' ) && Auth::User()->nik != '1181195100' || $cek_role->name_role == 'Customer Support Center'){
            $getPid = $getPid->where('nik',Auth::User()->nik)->get()->pluck('pid');
        } elseif ($cek_role->name_role == 'Customer Relation Manager') {
            $getPid = $getPid->whereIn('nik',$nikCC)->get()->pluck('pid');
        } elseif ($cek_role->name_role == 'IT Internal') {
            $getPid = DB::table('ticketing__detail')->select('pid')->where('pid','INTERNAL')->distinct()->get()->pluck('pid');
        }else {
            $getPid = $getPid->get()->pluck('pid');
        }

		$occurring_ticket = DB::table('ticketing__activity')
			->select('id_ticket','activity')
			->whereIn('id',function ($query) {
				$query->select(DB::raw("MAX(id) AS activity"))
					->from('ticketing__activity')
					->groupBy('id_ticket');
				})
			->where('activity','<>','CANCEL')
			->where('activity','<>','CLOSE')
			// ->where('id_ticket','33964/BBJB/Sep/2024')
			->get()
			->pluck('id_ticket');

		// $occurring_ticket_result = TicketingDetail::with([
		// 		'first_activity_ticket:id_ticket,date,operator',
		// 		'lastest_activity_ticket',
		// 		'id_detail:id_ticket,id',
		// 	])
		// 	->where('pid','=',null)
		// 	->whereIn('id_ticket',$occurring_ticket)
		// 	// ->where('id_ticket','33546/BBJB/Aug/2024')
		// 	->orderBy('ticketing__detail.id','DESC')
		// 	->get();

		$occurring_ticket_result_pid = TicketingDetail::with([
				'first_activity_ticket:id_ticket,date,operator',
				'lastest_activity_ticket',
				'id_detail:id_ticket,id',
			])
			->whereIn('pid',$getPid)
			->whereIn('id_ticket',$occurring_ticket)
			->where('id_ticket','33964/BBJB/Sep/2024')
			->orderBy('ticketing__detail.id','DESC')
			->get();

		$limit = $occurring_ticket->count() > 100 ? 100 : 100 - $occurring_ticket->count();

		// $residual_ticket_result = TicketingDetail::with([
		// 		'first_activity_ticket:id_ticket,date,operator',
		// 		'lastest_activity_ticket',
		// 		'id_detail:id_ticket,id',
		// 	])
		// 	->whereNotIn('id_ticket',$occurring_ticket)
		// 	->where('pid','=',null)
		// 	->limit($limit)
		// 	->orderBy('ticketing__detail.id','DESC')
		// 	->get();

		$residual_ticket_result_pid = TicketingDetail::with([
				'first_activity_ticket:id_ticket,date,operator',
				'lastest_activity_ticket',
				'id_detail:id_ticket,id',
			])
			// ->join('ticketing__user','ticketing__user.pid','ticketing__detail.pid')
			->whereIn('pid',$getPid)
			->whereNotIn('id_ticket',$occurring_ticket)
			// ->where('id_ticket','33964/BBJB/Sep/2024')
			->limit($limit)
			->orderBy('ticketing__detail.id','DESC')
			->get();

        $result = $occurring_ticket_result_pid
            ->merge($residual_ticket_result_pid);

        $resolution_time_summary = $this->calculateResolutionTime(date('Y'));

        $result->each(function ($ticket) use ($resolution_time_summary) {
            $resolutionData = $resolution_time_summary->firstWhere('id_ticket', $ticket->id_ticket);

            if ($resolutionData) {
                $ticket->progress_to_pending_seconds = $resolutionData->total_progress_to_pending_seconds;
                $ticket->progress_to_close_seconds = $resolutionData->total_progress_to_close_seconds;
                $ticket->open_to_progress_seconds = $resolutionData->last_open_to_progress_seconds;
            } else {
                $ticket->progress_to_pending_seconds = null;
                $ticket->progress_to_close_seconds = null;
                $ticket->open_to_progress_seconds = null;
            }
        });
		// $pid = TicketingUser::join('users','users.nik','ticketing__user.nik')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id');

		// $result = $occurring_ticket_result_pid
		//     ->merge($residual_ticket_result_pid);

  //       $keyToCheck = 'severity';

		// $someObjectsMissingProperty = count(array_filter(get_object_vars($result), function($item) use ($keyToCheck) {
		//     return !property_exists($item, $keyToCheck) || is_null($item->$keyToCheck);
		// })) > 0;

		// return $someObjectsMissingProperty ? 'true' : 'false'; // Output: true

		foreach ($result as $ticket) {
			if ($ticket->pid == '') {
				$cekSla = SLAProject::where('pid','Standard');
			} else {
				$cekSla = SLAProject::where('pid',$ticket->pid);
			}

			if ($cekSla->first() !== null) {
				switch ($ticket->severity) {
			        case '1':
			            $cekSla = $cekSla->select('sla_response', DB::raw("(CASE WHEN (sla_resolution_critical is null) THEN 4 WHEN (sla_resolution_critical = '') THEN 4 ELSE sla_resolution_critical END) as sla_resolution"))->first();
			            break;
			        case '2':
			            $cekSla = $cekSla->select('sla_response', DB::raw("(CASE WHEN (sla_resolution_major is null) THEN 8 WHEN (sla_resolution_major = '') THEN 8 ELSE sla_resolution_major END) as sla_resolution"))->first();
			            break;
			        case '3':
			            $cekSla = $cekSla->select('sla_response', DB::raw("(CASE WHEN (sla_resolution_moderate is null) THEN 12 WHEN (sla_resolution_moderate = '') THEN 12 ELSE sla_resolution_minor END) as sla_resolution"))->first();
			            break;
			        case '4':
			            $cekSla = $cekSla->select('sla_response', DB::raw("(CASE WHEN (sla_resolution_minor is null) THEN 24 WHEN (sla_resolution_minor = '') THEN 24 ELSE sla_resolution_minor END) as sla_resolution"))->first();
			            break;
			        case '0':
			        	$cekSla = $cekSla->select('sla_response', DB::raw("(CASE WHEN (sla_resolution_minor is null) THEN 24 WHEN (sla_resolution_minor = '') THEN 24 ELSE sla_resolution_minor END) as sla_resolution"))->first();
			            break;
			        default:
			        	$cekSla = $cekSla->select('sla_response', DB::raw("(CASE WHEN (sla_resolution_minor is null) THEN 24 WHEN (sla_resolution_minor = '') THEN 24 ELSE sla_resolution_minor END) as sla_resolution"))->first();
			        break;
			    }
			}else{
				$cekSla = collect(["sla_response"=>"0","sla_resolution"=>"0"]);
			}
					
		    $firstActivity = $ticket->first_activity_ticket;
		    $lastActivity = $ticket->lastest_activity_ticket;

		    $now = time();

		    $openTime = $closeTime = $durationFromLastActivity = null;

		    if ($firstActivity) {
		        $openTime = strtotime($firstActivity->date);
		    }

		    if ($firstActivity && $lastActivity) {
		        $openTime = strtotime($firstActivity->date);
		        $closeTime = strtotime($lastActivity->date);

                $resolutionTimeInHours = ((float)$ticket->open_to_progress_seconds + (float)$ticket->progress_to_pending_seconds + $ticket->progress_to_close_seconds) / 3600;
				$formattedTime         = $this->formatResolutionTime($resolutionTimeInHours);

		        $ticket->sla_resolution_percentage = $formattedTime;

		        // return $cekSla->sla_resolution;
		        if ($resolutionTimeInHours <= $cekSla['sla_resolution']) {
		        	if ($resolutionTimeInHours == 0) {
                		$ticket->highlight_sla_resolution = '-';
                	}else{
                    	$ticket->highlight_sla_resolution = 'Comply';
                	}
		        } else {
		            $ticket->highlight_sla_resolution = 'Not-Comply';
		        }
		    } else {
		        $ticket->sla_resolution_percentage = '-'; 
		        $ticket->highlight_sla_resolution = 'Not-Comply';
		    }

		    if ($firstActivity) {
		        $responseTimeInSeconds = $openTime - strtotime($ticket->reporting_time);
		        $responseTimeInMinutes = $responseTimeInSeconds / 60;
		        $responseTimeInHour = $responseTimeInMinutes / 60;

		        $formattedTime = $this->formatResponseTime($responseTimeInMinutes);

		        $ticket->response_time_percentage = $formattedTime;

		        if ($responseTimeInHour <= $cekSla['sla_response']) {
		            $ticket->highlight_sla_response = 'Comply';
		        } else {
		            $ticket->highlight_sla_response = 'Not-Comply';
		        }

		        if ($lastActivity->activity !== 'CLOSE') {
		            $durationFromLastActivity = $now - strtotime($lastActivity->date);
		            $ticket->duration_from_last_activity = $this->formatDuration($durationFromLastActivity);
		        } else {
		            $ticket->duration_from_last_activity = '-';
		        }
		    } else {
		        $ticket->response_time_percentage = '-';
		        $ticket->highlight_sla_response = 'Not-Comply';
		        $ticket->duration_from_last_activity = '-';
		    }
		}

		return array("data" => $result);
	}

	function calculateResolutionTimeFilter($year){
        $statusSequence = DB::table('ticketing__activity')
            ->select('id_ticket', 'activity', 'date')
            ->selectRaw('ROW_NUMBER() OVER (PARTITION BY id_ticket ORDER BY date) as seq_num');

        $pairedProgressPending = DB::table(DB::raw("({$statusSequence->toSql()}) as ppp"))
        ->select('ppp.id_ticket', 'ppp.date as progress_time', DB::raw('MIN(n.date) as pending_time'))
        ->leftJoin(DB::raw("({$statusSequence->toSql()}) as n"), function ($join) use ($year){
            $join->on('ppp.id_ticket', '=', 'n.id_ticket')
                ->where('n.activity', 'PENDING')
        		->where('n.id_ticket','like','%'.$year)
                ->whereColumn('n.seq_num', '>', 'ppp.seq_num');
        })
        ->where('ppp.activity', 'ON PROGRESS')
        ->where('ppp.id_ticket','like','%'.$year)
        ->groupBy('ppp.id_ticket', 'ppp.date');

        // return $statusSequence->get();

        $progressToClose = DB::table('ticketing__activity as ptc')
            ->select('id_ticket', DB::raw('MIN(date) as close_time'))
            ->where('activity', 'CLOSE')
            ->where('ptc.id_ticket','like','%'.$year)
            ->groupBy('id_ticket');

        $openToProgress = DB::table('ticketing__activity as opt')
            ->select(
                'id_ticket',
                DB::raw('MIN(CASE WHEN activity = "ON PROGRESS" THEN date END) as first_on_progress_time'),
                DB::raw('MIN(CASE WHEN activity = "OPEN" THEN date END) as open_time')
            )
            ->where('opt.id_ticket','like','%'.$year)
            ->groupBy('id_ticket');

        $resolution_time = DB::table('ticketing__activity as pppp')
            ->select(
                'pppp.id_ticket',
                DB::raw('TIMESTAMPDIFF(SECOND, progress_time, pending_time) as progress_to_pending_seconds'),
                DB::raw('CASE WHEN pending_time IS NOT NULL THEN NULL ELSE TIMESTAMPDIFF(SECOND, progress_time, close_time) END as progress_to_close_seconds'),
                DB::raw('TIMESTAMPDIFF(SECOND, opt.open_time, opt.first_on_progress_time) as open_to_progress_seconds')
            )
            ->leftJoinSub($pairedProgressPending, 'ppp', 'pppp.id_ticket', '=', 'ppp.id_ticket')
            ->leftJoinSub($progressToClose, 'ptc', 'pppp.id_ticket', '=', 'ptc.id_ticket')
            ->leftJoinSub($openToProgress,'opt', 'pppp.id_ticket', '=', 'opt.id_ticket')
            ->orderBy('pppp.id_ticket')
            ->where(function($query) use ($year) {
	            foreach ($year as $year) {
	                $query->orWhere('pppp.id_ticket', 'like', "%/$year");
	            }
	        })
            ->groupBy('pppp.id_ticket','progress_to_close_seconds','progress_to_pending_seconds','open_to_progress_seconds');

        $resolution_time_summary = DB::table($resolution_time, 'resolution')
            ->select(
                'resolution.id_ticket',
                DB::raw('SUM(CASE WHEN resolution.progress_to_pending_seconds IS NOT NULL THEN resolution.progress_to_pending_seconds ELSE 0 END) as total_progress_to_pending_seconds'),
                // DB::raw('SUM(CASE WHEN resolution.progress_to_close_seconds IS NOT NULL THEN resolution.progress_to_close_seconds ELSE 0 END) as total_progress_to_close_seconds'),
                DB::raw('MAX(resolution.progress_to_close_seconds) as total_progress_to_close_seconds'),
                DB::raw('MAX(resolution.open_to_progress_seconds) as last_open_to_progress_seconds')
            )
            ->groupBy('resolution.id_ticket')
            ->orderBy('resolution.id_ticket')
            ->get();

        return $resolution_time_summary;
    }

    function calculateResolutionTime($year){
    	// return $year;
        $statusSequence = DB::table('ticketing__activity')
            ->select('id_ticket', 'activity', 'date')
            ->selectRaw('ROW_NUMBER() OVER (PARTITION BY id_ticket ORDER BY date) as seq_num');

        $pairedProgressPending = DB::table($statusSequence, 'ppp')
        ->select('ppp.id_ticket', 'ppp.date as progress_time', DB::raw('MIN(n.date) as pending_time'))
        ->leftJoinSub($statusSequence,'n', function ($join) use ($year) {
            $join->on('ppp.id_ticket', '=', 'n.id_ticket')
                 ->where('n.activity', 'PENDING')
                 ->where('n.id_ticket','like','%'.$year)
                 ->whereColumn('n.seq_num', '>', 'ppp.seq_num');
        })
        ->where('ppp.activity', 'ON PROGRESS')
        ->where('ppp.id_ticket','like','%'.$year)
        ->groupBy('ppp.id_ticket', 'ppp.date');

        $progressToClose = DB::table('ticketing__activity as ptc')
            ->select('id_ticket', DB::raw('MIN(date) as close_time'))
            ->where('activity', 'CLOSE')
            ->where('id_ticket','like','%'.$year)
            ->groupBy('id_ticket');

        $openToProgress = DB::table('ticketing__activity as opt')
            ->select(
                'id_ticket',
                DB::raw('MIN(CASE WHEN activity = "ON PROGRESS" THEN date END) as first_on_progress_time'),
                DB::raw('MIN(CASE WHEN activity = "OPEN" THEN date END) as open_time')
            )
            ->where('id_ticket','like','%'.$year)
            ->groupBy('id_ticket');

        $resolution_time = DB::table('ticketing__activity as pppp')
            ->select(
                'pppp.id_ticket',
                DB::raw('TIMESTAMPDIFF(SECOND, progress_time, pending_time) as progress_to_pending_seconds'),
                DB::raw('CASE WHEN pending_time IS NOT NULL THEN NULL ELSE TIMESTAMPDIFF(SECOND, progress_time, close_time) END as progress_to_close_seconds'),
                DB::raw('TIMESTAMPDIFF(SECOND, opt.open_time, opt.first_on_progress_time) as open_to_progress_seconds')
            )
            ->leftJoinSub($pairedProgressPending, 'ppp', 'pppp.id_ticket', '=', 'ppp.id_ticket')
            ->leftJoinSub($progressToClose, 'ptc', 'pppp.id_ticket', '=', 'ptc.id_ticket')
            ->leftJoinSub($openToProgress,'opt', 'pppp.id_ticket', '=', 'opt.id_ticket')
            ->orderBy('pppp.id_ticket')
            ->where('pppp.id_ticket','like','%'.$year)
            ->groupBy('pppp.id_ticket','progress_to_close_seconds','progress_to_pending_seconds','open_to_progress_seconds');

        $resolution_time_summary = DB::table($resolution_time, 'resolution')
            ->select(
                'resolution.id_ticket',
                DB::raw('SUM(CASE WHEN resolution.progress_to_pending_seconds IS NOT NULL THEN resolution.progress_to_pending_seconds ELSE 0 END) as total_progress_to_pending_seconds'),
                // DB::raw('SUM(CASE WHEN resolution.progress_to_close_seconds IS NOT NULL THEN resolution.progress_to_close_seconds ELSE 0 END) as total_progress_to_close_seconds'),
                DB::raw('MAX(resolution.progress_to_close_seconds) as total_progress_to_close_seconds'),
                DB::raw('MAX(resolution.open_to_progress_seconds) as last_open_to_progress_seconds')
            )
            ->groupBy('resolution.id_ticket')
            ->orderBy('resolution.id_ticket')
            ->get();

        return $resolution_time_summary;
    }

	// function calculateResponseTimePercentage($responseTimeInMinutes) {
	//     if ($responseTimeInMinutes <= 60) {
	//         return 100;
	//     } elseif ($responseTimeInMinutes > 30 && $responseTimeInMinutes <= 45) {
	//         return 100 - round((50 * ($responseTimeInMinutes - 30) / 15), 2); // Linear decrease to 50%
	//     } elseif ($responseTimeInMinutes > 45) {
	//         return round(max(0, 50 - (50 * ($responseTimeInMinutes - 45) / 45)), 2); // Linear decrease to 0%
	//     } else {
	//         return 0; // Default to 0% for any unexpected values
	//     }
	// }

    function customRound($number) {
        if ($number - floor($number) > 0.5) {
            return ceil($number);
        } else {
            return floor($number);
        }
    }

	function formatResponseTime($responseTimeInMinutes) {
	    $formattedTime = '';

	    if ($responseTimeInMinutes > 1440) {
	    	$formattedTime = $this->customRound($responseTimeInMinutes / 1440) . ' Hari ' . $this->customRound(($responseTimeInMinutes % 1440) / 60) . ' Jam ' . $responseTimeInMinutes % 60 . ' Menit';
	    } elseif ($responseTimeInMinutes > 61) {
	    	$formattedTime = $this->customRound(($responseTimeInMinutes % 1440) / 60) . ' Jam ' . $responseTimeInMinutes % 60  . ' Menit';
	    } else {
	    	$formattedTime = number_format($responseTimeInMinutes,2). ' Menit';
	    }

	    return trim($formattedTime);
	}

	function formatResolutionTime($resolutionTimeInHours) {
		// Number of hours in a day
	    $hoursInDay = 24;
	    // Number of minutes in an hour
	    $minutesInHour = 60;

	    // Convert total hours to total minutes
	    $totalMinutes = $resolutionTimeInHours * $minutesInHour;

	    // Calculate days
	    $days = intdiv($totalMinutes, $hoursInDay * $minutesInHour);
	    // Calculate remaining minutes after extracting days
	    $remainingMinutes = $totalMinutes % ($hoursInDay * $minutesInHour);

	    // Calculate hours from remaining minutes
	    $remainingHours = intdiv($remainingMinutes, $minutesInHour);
	    // Calculate remaining minutes after extracting hours
	    $minutes = $remainingMinutes % $minutesInHour;

	    $formattedTime = '';

	    // Format days
	    if ($days > 0) {
	        $formattedTime .= $days . ' Hari';
	    }

	    // Format hours
	    if ($remainingHours > 0) {
	        if (!empty($formattedTime)) $formattedTime .= ' ';
	        $formattedTime .= $remainingHours . ' Jam';
	    }

	    // Format minutes
	    if ($minutes > 0 || empty($formattedTime)) {
	        if (!empty($formattedTime)) $formattedTime .= ' ';
	        $formattedTime .= $minutes . ' Menit';
	    }

	    return $formattedTime;
	}

	function getDaysInMonth($month, $year) {
	    return cal_days_in_month(CAL_GREGORIAN, $month, $year);
	}

	public function getPerformanceByFilter(Request $request){
		$start = microtime(true);

		$limitAll = 500;

		$nik = Auth::User()->nik;
        $cek_role = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role','group','mini_group')->where('user_id',$nik)->first();

        $nikEoS = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role','group','mini_group','nik')->where('roles.name','Engineer Onsite Enterprise')->orwhere('roles.name','Engineer Onsite SOC')->orwhere('roles.name','Engineer Onsite ATM')->orwhere('roles.name','Delivery Project Coordinator')->get()->pluck('nik');
        $nikCC = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role','group','mini_group','nik')->where('roles.name','Customer Support Center')->get()->pluck('nik');

        $getPid = DB::table('ticketing__user')->where('nik',Auth::User()->nik)->distinct()->get()->pluck('pid');
        $getPidEoS = DB::table('ticketing__user')->whereIn('nik',$nikEoS)->distinct()->get()->pluck('pid');
        $getPidCC = DB::table('ticketing__user')->whereIn('nik',$nikCC)->distinct()->get()->pluck('pid');

		$occurring_ticket = DB::table('ticketing__activity')
			->select('ticketing__activity.id_ticket','ticketing__activity.activity')
			->whereIn('ticketing__activity.id',function ($query) {
				$query->select(DB::raw("MAX(id) AS activity"))
					->from('ticketing__activity')
					->groupBy('id_ticket');
				});

		if (isset($request->activity)) {
			$occurring_ticket->where('ticketing__activity.activity','<>',$request->activity);
		}else{
			if ($request->attention == true) {
				$occurring_ticket
					->where('ticketing__activity.activity','<>','CANCEL')
					->where('ticketing__activity.activity','<>','CLOSE')
					->where('ticketing__activity.activity','<>','PENDING');
			}
		}

		if(isset($request->client)){
			if ($request->client != '') {
				$id_client_pid = new TicketingEmailSetting();
				if ($request->client[0] == "INTERNAL") {
					$id_client_pid = TicketingEmailSetting::where('client','like','%INTERNAL%')->get()->pluck('id');
					$id_client = TicketingClient::select('id')->where('client_acronym','INTR')->get()->pluck('id');
				}elseif ($request->client[0] == "13") {
					$id_client_pid = TicketingEmailSetting::where('client','like','%Adira%')->get()->pluck('id');
					$id_client = TicketingClient::select('id')->where('client_acronym','ADRF')->get()->pluck('id');
				}else{
					$cek_code = TB_Contact::select(DB::raw("(CASE 
						WHEN `code` = 'BBJB' THEN 'BJBR'
						WHEN `code` = 'BKES' THEN 'BPJS'  
						WHEN `code` = 'PBLG' THEN 'BULG' 
						WHEN `code` = 'BGDN' THEN 'PGAN' 
						WHEN `code` = 'ADRF' THEN 'ADMF' 
						WHEN `code` = 'BTNI' THEN 'BBTN' 
						ELSE `code` END) as code"),'customer_legal_name')->where('id_customer',$request->client)->first();
					$id_client = TicketingClient::select('id')->where('client_acronym',$cek_code->code)->get()->pluck('id');
					$id_client_pid = TicketingEmailSetting::where('client','like','%'.$cek_code->customer_legal_name.'%')->get()->pluck('id');

					if ($id_client_pid != "") {
						$id_client_pid = $id_client_pid;
					}else{
						$id_client_pid = [''];
					}
				}

				$occurring_ticket->whereIn('ticketing__activity.id_ticket',function($query) use ($request,$id_client_pid,$id_client){
					$query->select('ticketing__id.id_ticket')
						->from('ticketing__id')
						->whereIn('ticketing__id.id_client_pid',$id_client_pid)
						->orWhereIn('ticketing__id.id_client',$id_client);
				});
			}
		}
		
		if(isset($request->severity)){
			$occurring_ticket->whereIn('ticketing__activity.id_ticket',function($query) use ($request){
				$query->select('ticketing__detail.id_ticket')
					->from('ticketing__detail')
					->whereIn('ticketing__detail.severity',$request->severity);
			});
		}
		if(isset($request->type)){
			$occurring_ticket->whereIn('ticketing__activity.id_ticket',function($query) use ($request){
				$query->select('ticketing__detail.id_ticket')
					->from('ticketing__detail')
					->whereIn('ticketing__detail.type_ticket',$request->type);
			});
		}

		if(isset($request->startDate) && isset($request->endDate)){
			$occurring_ticket->whereBetween('ticketing__activity.date', [$request->startDate . " 00:00:00", $request->endDate . " 23:59:59"]);
		}

		// return $occurring_ticket = $occurring_ticket->get();

		$occurring_ticket_result = TicketingDetail::with([
				'first_activity_ticket:id_ticket,date,operator',
				'lastest_activity_ticket',
				'id_detail:id_ticket,id,id_client',
			])
			->whereIn('id_ticket',$occurring_ticket->pluck('id_ticket'))
			->orderBy('id','DESC');

		if ($request->startDate != '' && $request->endDate != '') {
			$date = Carbon::create($request->startDate);
			$year = $date->year; // Using the 'year' property
			// or
			$year = $date->format('Y'); // Using the format method
		}else{
			$year = date('Y');
		}

		if ($cek_role->name_role == 'Engineer Onsite Enterprise' || $cek_role->name_role == 'Engineer Onsite SOC' || $cek_role->name_role == 'Engineer Onsite ATM' || $cek_role->name_role == 'Delivery Project Coordinator'  || $cek_role->name_role == 'Customer Support Center' || $cek_role->name_role == 'IT Internal') {
			if($cek_role->name_role == 'Customer Support Center'){
				$occurring_ticket_result = $occurring_ticket_result->whereIn('pid',$getPid)->orWhere('pid','13')->orWhere('pid','ADMF')->orWhere('pid','INTERNAL')->where('id_ticket','like','%'.$year);
			} else if($cek_role->name_role == 'IT Internal'){
				$occurring_ticket_result = $occurring_ticket_result->where('pid','INTERNAL')->where('id_ticket','like','%'.$year);
			} else {
				$occurring_ticket_result = $occurring_ticket_result->whereIn('pid',$getPid)->where('id_ticket','like','%'.$year);
			}
    	} elseif($cek_role->name_role == 'Synergy System & Services Manager' || Auth::User()->nik = '1181195100'){
    		$occurring_ticket_result = $occurring_ticket_result->whereIn('pid',$getPidEoS)->where('id_ticket','like','%'.$year);
    	} elseif($cek_role->name_role == 'Customer Relation Manager'){
    		$occurring_ticket_result = $occurring_ticket_result->whereIn('pid',$getPidCC)->where('id_ticket','like','%'.$year);
    	} else {
    		$occurring_ticket_result = $occurring_ticket_result->where('id_ticket','like','%'.$year);
    	}

    	$occurring_ticket_result = $occurring_ticket_result->get();

		$finish_ticket = DB::table('ticketing__activity')
			->select('ticketing__activity.id_ticket','ticketing__activity.activity')
			->whereIn('ticketing__activity.id',function ($query) {
				$query->select(DB::raw("MAX(`id`) AS `activity`"))
					->from('ticketing__activity')
					->groupBy('id_ticket');
			});

		if(isset($request->client)){
			if ($request->client != '') {
				$id_client_pid = new TicketingEmailSetting();
				if ($request->client[0] == "INTERNAL") {
					$id_client_pid = TicketingEmailSetting::where('client','like','%INTERNAL%')->get()->pluck('id');
					$id_client = TicketingClient::select('id')->where('client_acronym','INTR')->get()->pluck('id');
				}elseif ($request->client[0] == "13") {
					$id_client_pid = TicketingEmailSetting::where('client','like','%Adira%')->get()->pluck('id');
					$id_client = TicketingClient::select('id')->where('client_acronym','ADRF')->get()->pluck('id');
				}else{
					$cek_code = TB_Contact::select(DB::raw("(CASE 
						WHEN `code` = 'BBJB' THEN 'BJBR'
						WHEN `code` = 'BKES' THEN 'BPJS'  
						WHEN `code` = 'PBLG' THEN 'BULG' 
						WHEN `code` = 'BGDN' THEN 'PGAN' 
						WHEN `code` = 'ADRF' THEN 'ADMF' 
						WHEN `code` = 'BTNI' THEN 'BBTN' 
						ELSE `code` END) as code"),'customer_legal_name')->where('id_customer',$request->client)->first();
					$id_client = TicketingClient::select('id')->where('client_acronym',$cek_code->code)->get()->pluck('id');
					$id_client_pid = TicketingEmailSetting::where('client','like','%'.$cek_code->customer_legal_name.'%')->get()->pluck('id');

					if ($id_client_pid != "") {
						$id_client_pid = $id_client_pid;
					}else{
						$id_client_pid = [''];
					}
				}

				$finish_ticket->whereIn('ticketing__activity.id_ticket',function($query) use ($request,$id_client_pid,$id_client){
					$query->select('ticketing__id.id_ticket')
						->from('ticketing__id')
						->whereIn('ticketing__id.id_client_pid',$id_client_pid)
						->orWhereIn('ticketing__id.id_client',$id_client);
				});
			}
		}

		if(isset($request->severity)){
			$finish_ticket->whereIn('ticketing__activity.id_ticket',function($query) use ($request){
				$query->select('ticketing__detail.id_ticket')
					->from('ticketing__detail')
					->whereIn('ticketing__detail.severity',$request->severity);
			});
		}

		if(isset($request->type)){
			$finish_ticket->whereIn('ticketing__activity.id_ticket',function($query) use ($request){
				$query->select('ticketing__detail.id_ticket')
					->from('ticketing__detail')
					->whereIn('ticketing__detail.type_ticket',$request->type);
			});
		}

		if(isset($request->startDate) && isset($request->endDate)){
			$finish_ticket->whereBetween('ticketing__activity.date', [$request->startDate . " 00:00:00", $request->endDate . " 23:59:59"]);
		}else{
			$finish_ticket->whereYear('ticketing__activity.date',date('Y'));
		}

		$limit = $occurring_ticket_result->count() > $limitAll ? $limitAll : $limitAll - $occurring_ticket_result->count();

		if ($request->attention == true) {
			$finish_ticket
			->where('ticketing__activity.activity','<>','CLOSE')
            ->where('ticketing__activity.activity','<>','CANCEL')
            ->where('ticketing__activity.activity','<>','PENDING')
			// ->whereRaw('(`ticketing__activity`.`activity` = "CANCEL" OR `ticketing__activity`.`activity` = "CLOSE" OR `ticketing__activity`.`activity` = "PENDING")')
			->orderBy('ticketing__activity.id','DESC')
			->get();
		}else{
			$finish_ticket
			// ->where('ticketing__activity.activity','<>','CLOSE')
            // ->where('ticketing__activity.activity','<>','CANCEL')
            ->whereRaw('(`ticketing__activity`.`activity` = "CANCEL" OR `ticketing__activity`.`activity` = "CLOSE")')
			->orderBy('ticketing__activity.id','DESC')
			->get();
		}

		$finish_ticket_result = TicketingDetail::with([
				'first_activity_ticket:id_ticket,date,operator',
				'lastest_activity_ticket',
				'id_detail:id_ticket,id,id_client',
			])
			->whereIn('id_ticket',$finish_ticket->pluck('id_ticket'))
			->take($limit)
			->orderBy('id','DESC');

		if ($cek_role->name_role == 'Engineer Onsite Enterprise' || $cek_role->name_role == 'Engineer Onsite SOC' || $cek_role->name_role == 'Engineer Onsite ATM' || $cek_role->name_role == 'Delivery Project Coordinator' || $cek_role->name_role == 'Customer Support Center') {
			if($cek_role->name_role == 'Customer Support Center'){
				// $finish_ticket_result = $finish_ticket_result->whereIn('pid',$getPid)->orWhere('pid','13')->where('id_ticket','like','%'.date('Y'));
				$finish_ticket_result = $finish_ticket_result->whereIn('pid',$getPid)->orWhere('pid','13')->orWhere('pid','ADMF')->orWhere('pid','INTERNAL')->where('id_ticket','like','%'.$year);

			} else {
				$finish_ticket_result = $finish_ticket_result->whereIn('pid',$getPid)->where('id_ticket','like','%'.$year);
			}
    		// $finish_ticket_result = $finish_ticket_result->whereIn('pid',$getPid)->where('id_ticket','like','%'.$year);
    	} elseif($cek_role->name_role == 'Synergy System & Services Manager' || Auth::User()->nik = '1181195100'){
    		$finish_ticket_result = $finish_ticket_result->whereIn('pid',$getPidEoS)->where('id_ticket','like','%'.$year);
    	} elseif($cek_role->name_role == 'Customer Relation Manager'){
    		$finish_ticket_result = $finish_ticket_result->whereIn('pid',$getPidCC)->where('id_ticket','like','%'.$year);
    	} else {
    		$finish_ticket_result = $finish_ticket_result->where('id_ticket','like','%'.$year);
    	}

		$finish_ticket_result = $finish_ticket_result->get();

        $result = $occurring_ticket_result->merge($finish_ticket_result)->take($limitAll);

        $id_ticket = $result->pluck('id_ticket');

        $year = Ticketing::select(DB::raw("SUBSTRING_INDEX(id_ticket, '/', -1) as year"))->where('id_ticket','!=',null)
    		->distinct()->get()->pluck('year');

        // return $resolution_time_summary = $this->calculateResolutionTimeFilter($year);

    	$statusSequence = DB::table('ticketing__activity')
		    ->select('id_ticket', 'activity', 'date')
		    ->selectRaw('ROW_NUMBER() OVER (PARTITION BY id_ticket ORDER BY date) AS seq_num')->whereIn('id_ticket',$id_ticket);

		// return $statusSequence->get();

		// Paired progress and pending time
		$pairedProgressPending = DB::table($statusSequence, 'ppp')
		    ->select('ppp.id_ticket', 'ppp.date AS progress_time', DB::raw('MIN(n.date) AS pending_time'))
		    ->leftJoinSub($statusSequence,'n', function ($join) use ($id_ticket) {
		        $join->on('ppp.id_ticket', '=', 'n.id_ticket')
		            ->where('n.activity', 'PENDING')
		            ->whereIn('n.id_ticket', $id_ticket)
		            ->whereColumn('n.seq_num', '>', 'ppp.seq_num');
		    })
		    ->where('ppp.activity', 'ON PROGRESS')
		    ->whereIn('ppp.id_ticket', $id_ticket)
		    ->groupBy('ppp.id_ticket', 'ppp.date');


        // return $pairedProgressPending->get();

        $progressToClose = DB::table('ticketing__activity as ptc')
            ->select('id_ticket', DB::raw('MIN(date) as close_time'))
            ->where('activity', 'CLOSE')
            // ->where('ptc.id_ticket','like','%'.$year)
		    ->whereIn('ptc.id_ticket', $id_ticket)
            ->groupBy('id_ticket');

		// return $progressToClose->get();

        $openToProgress = DB::table('ticketing__activity as opt')
            ->select(
                'id_ticket',
                DB::raw('MIN(CASE WHEN activity = "ON PROGRESS" THEN date END) as first_on_progress_time'),
                DB::raw('MIN(CASE WHEN activity = "OPEN" THEN date END) as open_time')
            )
            // ->where('opt.id_ticket','like','%'.$year)
		    ->whereIn('opt.id_ticket', $id_ticket)
            ->groupBy('id_ticket');

		// return $openToProgress->get();

        // $resolution_time = DB::table('ticketing__activity as pppp')
        //     ->select(
        //         'pppp.id_ticket',
        //         DB::raw('TIMESTAMPDIFF(SECOND, progress_time, pending_time) as progress_to_pending_seconds'),
        //         DB::raw('CASE WHEN pending_time IS NOT NULL THEN NULL ELSE TIMESTAMPDIFF(SECOND, progress_time, close_time) END as progress_to_close_seconds'),
        //         DB::raw('TIMESTAMPDIFF(SECOND, opt.open_time, opt.first_on_progress_time) as open_to_progress_seconds')
        //     )
        //     ->leftJoinSub($pairedProgressPending, 'ppp', 'pppp.id_ticket', '=', 'ppp.id_ticket')
        //     ->leftJoinSub($progressToClose, 'ptc', 'pppp.id_ticket', '=', 'ptc.id_ticket')
        //     ->leftJoinSub($openToProgress,'opt', 'pppp.id_ticket', '=', 'opt.id_ticket')
        //     ->orderBy('pppp.id_ticket')
        //     // ->where(function($query) use ($year) {
	    //     //     foreach ($year as $year) {
	    //     //         $query->orWhere('pppp.id_ticket', 'like', "%/$year");
	    //     //     }
	    //     // })
		//     ->whereIn('pppp.id_ticket', $id_ticket)
        //     ->groupBy('pppp.id_ticket','progress_to_close_seconds','progress_to_pending_seconds','open_to_progress_seconds');

		// return $resolution_time->get();

		$openToLatestActivity = DB::table('ticketing__activity')
		    ->select(
		        'id_ticket',
		        DB::raw('MIN(CASE WHEN activity = "OPEN" THEN date END) AS open_time'),
		        DB::raw('MAX(date) AS latest_activity_time'),
				DB::raw('MAX(activity) AS latest_activity'),
		        DB::raw('MAX(CASE WHEN activity = "CLOSE" THEN date END) AS close_time') 
		    )
		    ->whereIn('id_ticket', $id_ticket)
		    ->groupBy('id_ticket');

		$resolution_time = DB::table('ticketing__activity AS ppppp')
		    ->select(
		        'ppppp.id_ticket', 'ol.latest_activity',
		        DB::raw('TIMESTAMPDIFF(SECOND, ppp.progress_time, ppp.pending_time) AS progress_to_pending_seconds'),
		        DB::raw('CASE WHEN ppp.pending_time IS NOT NULL THEN NULL ELSE TIMESTAMPDIFF(SECOND, ppp.progress_time, ptc.close_time) END AS progress_to_close_seconds'),
		        DB::raw('TIMESTAMPDIFF(SECOND, opt.open_time, opt.first_on_progress_time) AS open_to_progress_seconds'),
		        DB::raw('TIMESTAMPDIFF(SECOND, ol.open_time, ol.latest_activity_time) AS open_to_latest_activity_seconds')
		    )
		    ->leftJoinSub($pairedProgressPending, 'ppp', 'ppppp.id_ticket', '=', 'ppp.id_ticket')
		    ->leftJoinSub($progressToClose, 'ptc', 'ppppp.id_ticket', '=', 'ptc.id_ticket')
		    ->leftJoinSub($openToProgress, 'opt', 'ppppp.id_ticket', '=', 'opt.id_ticket')
		    ->leftJoinSub($openToLatestActivity, 'ol', 'ppppp.id_ticket', '=', 'ol.id_ticket')
		    ->whereIn('ppppp.id_ticket', $id_ticket)
		    ->groupBy('ppppp.id_ticket', 'progress_to_close_seconds', 'progress_to_pending_seconds', 'open_to_progress_seconds')
		    ->orderBy('ppppp.id_ticket');

			// return $resolution_time->get();

    	$resolution_time_summary = DB::table($resolution_time, 'resolution')
            ->select(
                'resolution.id_ticket', 'resolution.latest_activity',
                // DB::raw('SUM(CASE WHEN resolution.progress_to_pending_seconds IS NOT NULL THEN resolution.progress_to_pending_seconds ELSE 0 END) as total_progress_to_pending_seconds'),
                DB::raw('MAX(resolution.progress_to_pending_seconds) as total_progress_to_pending_seconds'),
                // DB::raw('MAX(resolution.progress_to_close_seconds) as total_progress_to_close_seconds'),
                // DB::raw('SUM(CASE WHEN resolution.progress_to_close_seconds IS NOT NULL THEN resolution.progress_to_close_seconds ELSE 0 END) as total_progress_to_close_seconds'),
                DB::raw('MAX(resolution.progress_to_close_seconds) as total_progress_to_close_seconds'),
                DB::raw('MAX(resolution.open_to_progress_seconds) as last_open_to_progress_seconds'),
                DB::raw('MAX(resolution.open_to_latest_activity_seconds) as last_open_to_latest_activity_seconds')
            )
            ->groupBy('resolution.id_ticket')
            ->orderBy('resolution.id_ticket')
            ->get();

		// return $resolution_time_summary;

        $result->each(function ($ticket) use ($resolution_time_summary) {
            $resolutionData = $resolution_time_summary->firstWhere('id_ticket', $ticket->id_ticket);

            if ($resolutionData) {
                $ticket->progress_to_pending_seconds = $resolutionData->total_progress_to_pending_seconds;
                $ticket->progress_to_close_seconds = $resolutionData->total_progress_to_close_seconds;
                $ticket->open_to_progress_seconds = $resolutionData->last_open_to_progress_seconds;
                // if ($resolutionData->latest_activity == 'CLOSE') {
		        //     $ticket->open_to_progress_seconds = $resolutionData->last_open_to_progress_seconds;
		        // } else {
				// 	$ticket->open_to_progress_seconds = $resolutionData->last_open_to_latest_activity_seconds;
		        // }
            } else {
                $ticket->progress_to_pending_seconds = null;
                $ticket->progress_to_close_seconds = null;
                $ticket->open_to_progress_seconds = null;
            }
        });

		$standardSLA = SLAProject::where('pid', 'Standard')->first();
		$customSLA = SLAProject::whereIn('pid', $result->pluck('pid')->filter())->get()->keyBy('pid');

		foreach ($result as $ticket) {
		    $sla = $ticket->pid ? ($customSLA[$ticket->pid] ?? $standardSLA) : $standardSLA;

		    switch ($ticket->severity) {
		        case '1':
		            $slaResponse = $sla->sla_response;
		            $slaResolution = $sla->sla_resolution_critical;
		            break;
		        case '2':
		            $slaResponse = $sla->sla_response;
		            $slaResolution = $sla->sla_resolution_major;
		            break;
		        case '3':
		            $slaResponse = $sla->sla_response;
		            $slaResolution = $sla->sla_resolution_moderate;
		            break;
		        case '4':
		        case '0':
		            $slaResponse = $sla->sla_response;
		            $slaResolution = $sla->sla_resolution_minor;
		            break;
		        default:
		            $slaResponse = $sla->sla_response;
		            $slaResolution = $sla->sla_resolution_minor;
		            break;
		    }

		    $firstActivity = $ticket->first_activity_ticket;
		    $lastActivity = $ticket->lastest_activity_ticket;

		    $now = time();
		    $openTime = $firstActivity ? strtotime($firstActivity->date) : null;
		    $closeTime = $lastActivity ? strtotime($lastActivity->date) : null;

		    if ($openTime && $closeTime) {
		        $resolutionTimeInHours = ($ticket->open_to_progress_seconds + $ticket->progress_to_pending_seconds + $ticket->progress_to_close_seconds) / 3600;
		        $resolutionTimeInMinutes = ($ticket->open_to_progress_seconds + $ticket->progress_to_pending_seconds + $ticket->progress_to_close_seconds) / 60;

		        if ($request->export == true) {
		        	$ticket->sla_resolution_percentage = number_format($resolutionTimeInMinutes,2);
		        } else {
		        	$ticket->sla_resolution_percentage = $this->formatResolutionTime($resolutionTimeInHours);
		        }
		        if ($resolutionTimeInHours == 0) {
		        	$ticket->highlight_sla_resolution = '-';
		        }else{
		        	$ticket->highlight_sla_resolution = $resolutionTimeInHours <= $slaResolution && $resolutionTimeInHours != 0 ? 'Comply' : 'Not-Comply';
		        }
		        
		    } else {
		        $ticket->sla_resolution_percentage = '-';
		        $ticket->highlight_sla_resolution = 'Not-Comply';
		    }

		    if ($openTime) {
		        $responseTimeInSeconds = $openTime - strtotime($ticket->reporting_time);
		        $responseTimeInHours = $responseTimeInSeconds / 3600;

		        if ($request->export == true) {
		        	$ticket->response_time_percentage = number_format(($responseTimeInSeconds / 60),2);
		        } else {
		        	$ticket->response_time_percentage = $this->formatResponseTime($responseTimeInSeconds / 60);
		        }

		        
		        $ticket->highlight_sla_response = $responseTimeInHours <= $slaResponse ? 'Comply' : 'Not-Comply';

		        if ($lastActivity->activity !== 'CLOSE') {
		            $durationFromLastActivity = $now - strtotime($lastActivity->date);
		            $ticket->duration_from_last_activity = $this->formatDuration($durationFromLastActivity);
		        } else {
		            $ticket->duration_from_last_activity = '-';
		        }
		    } else {
		        $ticket->response_time_percentage = '-';
		        $ticket->highlight_sla_response = 'Not-Comply';
		        $ticket->duration_from_last_activity = '-';
		    }
		}

		return array("data" => $result);
	}

	private function formatDuration($durationInSeconds)
	{
	    $days = floor($durationInSeconds / (60 * 60 * 24));
	    $hours = floor(($durationInSeconds % (60 * 60 * 24)) / (60 * 60));
	    $minutes = floor(($durationInSeconds % (60 * 60)) / 60);

	    $timeComponents = [];
	    if ($days > 0) $timeComponents[] = "{$days} days";
	    if ($hours > 0) $timeComponents[] = "{$hours} hours";
	    $timeComponents[] = "{$minutes} minutes";

	    return implode(', ', $timeComponents);
	}

	public function getPerformanceByTicket(Request $req){
		$idTicket = $req->idTicket;
		$result = TicketingDetail::whereHas('id_detail', function($query) use ($idTicket){
				$query->where('id','=',$idTicket);
			})
			->with([
				'lastest_activity_ticket:id_ticket,date,activity,operator',
				'resolve',
				'all_activity_ticket',
				'first_activity_ticket',
				'id_detail:id_ticket,id,id_client,id_client_pid'
			])
			->first();

		if(Ticketing::where('id',$idTicket)->first()->id_client == "29"){
			$result->machine_absen = TicketingAbsen::find($result->id_atm);
			return $result;
		} else {
			return $result;
		}
	}

	// New Function for SLM

    public function getDataAssignEngineer(Request $request)
    {
        $idAtm = $request->id_atm;
        $serialNumber = $request->serial_number;

        $engineer = DB::table('tb_asset_management_detail as a')->join('tb_asset_management as b', 'a.id_asset', 'b.id')
            ->join('tb_asset_management_assign_engineer as c', 'b.id','c.id_asset')
            ->join('users as d', 'c.engineer_atm', 'd.name')
            ->join('presence__history as e', 'd.nik', 'e.nik')
            ->whereDate('e.presence_actual', Carbon::today())
            ->where('a.id_device_customer', $idAtm)
            ->where('b.serial_number', $serialNumber)
            ->select('c.engineer_atm')
            ->get();

        return $engineer;
    }
	public function getRequestPending(Request $req){
		$idTicket = $req->idTicket;
		$result = TicketingDetail::whereHas('id_detail', function($query) use ($idTicket){
				$query->where('id','=',$idTicket);
			})
			->with([
				'lastest_activity_ticket:id_ticket,date,activity,operator',
				'id_detail:id_ticket,id,id_client,id_client_pid'
			])
			->first();

		$pending = DB::table('slm_job_pending')
			->where('id_ticket', $result->id_ticket)->orderByDesc('id')->first();

		$result->pending = $pending;
		return $result;
	}

	public function approvePending(Request $request)
	{
		$idTicket = $request->id_ticket;
		try {
			DB::beginTransaction();
			$ticketDetail = TicketingDetail::where('id_ticket', $idTicket)->first();

			$pendingDetail = DB::table('slm_job_pending')
				->where('id_ticket', $idTicket)
				->latest('id')->first();

			$pendingJob = DB::table('slm_job_pending')
			->where('id_ticket', $idTicket)  
			->latest('id')                   
			->limit(1)                      
			->update([
				'is_approved' => 'Y',
			]);			
			$activityTicketUpdate = new TicketingActivity();
			$activityTicketUpdate->id_ticket = $idTicket;
			$activityTicketUpdate->date = Carbon::now()->toDateTimeString();
			$activityTicketUpdate->activity = "PENDING";
			$activityTicketUpdate->operator = Auth::user()->name;
			$activityTicketUpdate->note = "Pending Request has approved by leader with reason : " . $request->reason;
			$activityTicketUpdate->save();

			$ticketDetail->update([
				'request_pending' => null,
			]);
				
			$cek_client_pid = Ticketing::where("id_ticket",$idTicket)->first();
			if ($cek_client_pid->id_client_pid) {
				$clientIdFilter = Ticketing::where('id_ticket',$idTicket)
					->first()->id_client_pid;

				$cek_code = TicketingEmailSetting::where('id',$clientIdFilter)->first()->client;

				if ($cek_code == "INTERNAL") {
					$clientIdFilter = 'INTERNAL';
				}else{
					$customer = explode("- ", $cek_code)[1];

					$id_client = TB_Contact::where('customer_legal_name', 'LIKE', '%'.$customer.'%')->first()->id_customer;
					$clientIdFilter = $id_client;
				}			
			}else{
				$clientIdFilter = Ticketing::with('client_ticket')
					->where('id_ticket',$idTicket)
					->first()
					->client_ticket
					->id;

				$cek_code = TicketingClient::where('id',$clientIdFilter)->first()->client_acronym;

				if ($cek_code == 'BPJS') {
					$cek_code = 'BKES';
				} elseif($cek_code == 'PBLG'){
					$cek_code = 'BULG';
				} elseif($cek_code == 'BGDN'){
					$cek_code = 'PGAN';
				} elseif($cek_code == 'BJBR'){
					$cek_code = 'BBJB';
				} elseif($cek_code == 'ADRF'){
					$cek_code = 'ADMF';
				} elseif($cek_code == 'BTNI'){
					$cek_code = 'BBTN';
				} else {
					$cek_code = $cek_code;
				}

				$id_client = TB_Contact::where('code',$cek_code)->first()->id_customer;
				$clientIdFilter = $id_client;
			}

			DB::commit();
			$engineer = $this->getEngineerData($ticketDetail->engineer);
			$chatIDGroup = env('TELEGRAM_GROUP_CHAT_ID');
			$message = 'Hai, '. $engineer->name . '. Request pending dengan ID Tiket: <b>'. $idTicket .'</b> sudah diapprove oleh leader.';
			if ($engineer->id_telegram != null){
                $this->telegramService->sendMessage($engineer->id_telegram,$message);
            }
			$this->telegramService->sendMessage($chatIDGroup,$message);
			$mail = new ApprovePendingTicket(collect([
					'id_ticket' => $idTicket,
					'estimated_pending' => $pendingDetail->estimated_pending,
					'name' => $engineer->name
				]));
				Mail::to($engineer->email)->send($mail);
			return response()->json([
				'status' => 'success',
				'message' => 'Success, ticket is pending',
				'id_client' => $clientIdFilter
			]);
		} catch (Exception $e) {
			DB::rollBack();
			return response()->json([
				'status' => 'error',
				'message' => $e->getMessage()
			]);
		}

	}

	public function rejectPending(Request $request)
	{
		$idTicket = $request->id_ticket;
		try {
			DB::beginTransaction();
			$ticketDetail = TicketingDetail::where('id_ticket', $idTicket)->first();

			$pendingJob = DB::table('slm_job_pending')
			->where('id_ticket', $idTicket)  
			->latest('id')                   
			->limit(1)                      
			->update([
				'is_approved' => 'N',
			]);	
			
			$ticketSLM = DB::table('ticketing__slm')->where('id_ticket', $idTicket)
			->update(['status' => 'ON PROGRESS']);

			$activityTicketUpdate = new TicketingActivity();
			$activityTicketUpdate->id_ticket = $idTicket;
			$activityTicketUpdate->date = Carbon::now()->toDateTimeString();
			$activityTicketUpdate->activity = "ON PROGRESS";
			$activityTicketUpdate->operator = Auth::user()->name;
			$activityTicketUpdate->note = "Pending Request has rejected by leader with reason : " . $request->reason;
			$activityTicketUpdate->save();

			$ticketDetail->update([
				'request_pending' => null,
			]);

				
			$cek_client_pid = Ticketing::where("id_ticket",$idTicket)->first();
			if ($cek_client_pid->id_client_pid) {
				$clientIdFilter = Ticketing::where('id_ticket',$idTicket)
					->first()->id_client_pid;

				$cek_code = TicketingEmailSetting::where('id',$clientIdFilter)->first()->client;

				if ($cek_code == "INTERNAL") {
					$clientIdFilter = 'INTERNAL';
				}else{
					$customer = explode("- ", $cek_code)[1];

					$id_client = TB_Contact::where('customer_legal_name', 'LIKE', '%'.$customer.'%')->first()->id_customer;
					$clientIdFilter = $id_client;
				}			
			}else{
				$clientIdFilter = Ticketing::with('client_ticket')
					->where('id_ticket',$idTicket)
					->first()
					->client_ticket
					->id;

				$cek_code = TicketingClient::where('id',$clientIdFilter)->first()->client_acronym;

				if ($cek_code == 'BPJS') {
					$cek_code = 'BKES';
				} elseif($cek_code == 'PBLG'){
					$cek_code = 'BULG';
				} elseif($cek_code == 'BGDN'){
					$cek_code = 'PGAN';
				} elseif($cek_code == 'BJBR'){
					$cek_code = 'BBJB';
				} elseif($cek_code == 'ADRF'){
					$cek_code = 'ADMF';
				} elseif($cek_code == 'BTNI'){
					$cek_code = 'BBTN';
				} else {
					$cek_code = $cek_code;
				}

				$id_client = TB_Contact::where('code',$cek_code)->first()->id_customer;
				$clientIdFilter = $id_client;
			}

            DB::commit();
            $engineer = $this->getEngineerData($ticketDetail->engineer);
            $chatIDGroup = env('TELEGRAM_GROUP_CHAT_ID');
            $message = 'Hai, ' .$engineer->name. '. Request pending dengan ID Tiket: <b>'. $idTicket .'</b> direject oleh leader. Kini status tiket menjadi On Progress.';
            if ($engineer->id_telegram != null){
                $this->telegramService->sendMessage($engineer->id_telegram,$message);
            }
            $this->telegramService->sendMessage($chatIDGroup,$message);
            $mail = new RejectPendingTicket(collect([
				'name' => $engineer->name,
				'id_ticket' => $idTicket,
				'reason' => $request->reason,
			]));
			Mail::to($engineer->email)->send($mail);
			return response()->json([
				'status' => 'success',
				'message' => 'Success, request pending is rejected',
				'id_client' => $clientIdFilter
			]);
		} catch (Exception $e) {
			DB::rollBack();
			return response()->json([
				'status' => 'error',
				'message' => $e->getMessage()
			]);
		}
	}

	public function getEngineerData($name)
	{
		$engineer = DB::table('users')
			->where('name', 'like', '%'.$name.'%')
			->select('telegram_id as id_telegram', 'nik', 'name', 'email')
			->first();

		return $engineer;
	}

	public function getTripRequestAll()
	{
		$data = DB::table('slm_money_request as a')
			->select('a.id', 'a.type', 'a.start_date', 'a.end_date', 'a.from', 'a.to', 'a.status',
			'a.nominal','a.nominal_settlement','a.id_ticket','a.request_by')
			->orderByDesc('a.id')->get();

		return array("data" => $data);
	}

	public function getDetailTripRequest(Request $request)
	{
		$idMonreq = $request->idMoneyRequest;

		$dataHeader = DB::table('slm_money_request')
			->where('id', $idMonreq)->first();

		$dataDetail = DB::table('slm_money_request_detail')->where('id_money_request', $idMonreq)->get();

		$dataLog = DB::table('slm_money_request_activity')->where('id_money_request', $idMonreq)->orderbyDesc('id')->get();

		$result = [
			'id' => $dataHeader->id,
			'type' => $dataHeader->type, 
			'start_date' => $dataHeader->start_date, 
			'end_date' => $dataHeader->end_date, 
			'from' => $dataHeader->from,
			'to' => $dataHeader->to,
			'details' => $dataDetail->map(function($detail) {
				return [
					'id' => $detail->id, 
					'request' => $detail->request,
					'nominal' => $detail->nominal,
					'file' => $detail->file,
					'file_2' => $detail->file_2,
					'date_time' => $detail->date_time
				];
			})->toArray(), 
			'log' => $dataLog->map(function($log) {
				return [
					'id' => $log->id, 
					'activity' => $log->activity,
					'time' => $log->time, 
					'status' => $log->status
				];
			})->toArray() 
		];
		
		

		return array('data' => $result);
	}

	// End new function for SLM

	public function getPerformanceBySeverity(Request $req){
		$startDate = $req->start . ' 00:00:01';
		$endDate = $req->end . ' 23:59:59';

		$nik = Auth::User()->nik;
        $cek_role = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role','group','mini_group')->where('user_id',$nik)->first();

        $nikEoS = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role','group','mini_group','nik')->where('roles.name','Engineer Onsite Enterprise')->orwhere('roles.name','Engineer Onsite SOC')->orwhere('roles.name','Engineer Onsite ATM')->orwhere('roles.name','Delivery Project Coordinator')->get()->pluck('nik');
        $nikCC = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role','group','mini_group','nik')->where('roles.name','Customer Support Center')->get()->pluck('nik');

        $getPid = DB::table('ticketing__user')->where('nik',Auth::User()->nik)->get()->pluck('pid');
        $getPidEoS = DB::table('ticketing__user')->whereIn('nik',$nikEoS)->get()->pluck('pid');
        $getPidCC = DB::table('ticketing__user')->whereIn('nik',$nikCC)->get()->pluck('pid');

		$occurring_ticket = DB::table('ticketing__activity')
			->select('ticketing__activity.id_ticket','ticketing__activity.activity')
			->whereIn('ticketing__activity.id',function ($query) use ($startDate,$endDate) {
				$query->select(DB::raw("MAX(id) AS activity"))
					->from('ticketing__activity')
					->whereRaw('`ticketing__activity`.`date` BETWEEN "' . $startDate . '" AND "' . $endDate . '"')
					->groupBy('id_ticket');
				})
			->where('ticketing__activity.activity','<>','CANCEL')
			->where('ticketing__activity.activity','<>','CLOSE')
			->whereRaw('`ticketing__activity`.`date` BETWEEN "' . $startDate . '" AND "' . $endDate . '"')
			->get();

		$occurring_ticket_result = TicketingDetail::with([
				'first_activity_ticket:id_ticket,date,operator',
				'lastest_activity_ticket',
				'id_detail:id_ticket,id',
			])
			->whereIn('id_ticket',$occurring_ticket->pluck('id_ticket'))
			->where('severity',$req->severity)
			->orderBy('id','DESC');

		if (isset($request->pid)) {
			$occurring_ticket_result = $occurring_ticket_result->where('pid',$req->pid);
		}

		if (isset($req->start) && isset($req->end)){
			// $occurring_ticket_result = $occurring_ticket_result->whereRaw('`ticketing__detail`.`reporting_time` BETWEEN "' . $startDate . '" AND "' . $endDate . '"');
			if ($cek_role->name_role == 'Engineer Onsite Enterprise' || $cek_role->name_role == 'Engineer Onsite SOC' || $cek_role->name_role == 'Engineer Onsite ATM' || $cek_role->name_role == 'Delivery Project Coordinator' || $cek_role->name_role == 'Customer Support Center') {
        		$occurring_ticket_result = $occurring_ticket_result->whereIn('pid',$getPid)->whereRaw('`ticketing__detail`.`reporting_time` BETWEEN "' . $startDate . '" AND "' . $endDate . '"');
        	} elseif($cek_role->name_role == 'Synergy System & Services Manager' || Auth::User()->nik = '1181195100'){
        		$occurring_ticket_result = $occurring_ticket_result->whereIn('pid',$getPidEoS)->whereRaw('`ticketing__detail`.`reporting_time` BETWEEN "' . $startDate . '" AND "' . $endDate . '"');
        	} elseif($cek_role->name_role == 'Customer Relation Manager'){
        		$occurring_ticket_result = $occurring_ticket_result->whereIn('pid',$getPidCC)->whereRaw('`ticketing__detail`.`reporting_time` BETWEEN "' . $startDate . '" AND "' . $endDate . '"');
        	} else {
        		$occurring_ticket_result = $occurring_ticket_result->whereRaw('`ticketing__detail`.`reporting_time` BETWEEN "' . $startDate . '" AND "' . $endDate . '"');
        	}
		} 

		$occurring_ticket_result = $occurring_ticket_result->get();

		$finish_ticket = DB::table('ticketing__activity')
			->select('ticketing__activity.id_ticket','ticketing__activity.activity')
			->whereIn('ticketing__activity.id',function ($query) use ($startDate,$endDate) {
				$query->select(DB::raw("MAX(`id`) AS `activity`"))
					->from('ticketing__activity')
					->whereRaw('`ticketing__activity`.`date` BETWEEN "' . $startDate . '" AND "' . $endDate . '"')
					->groupBy('id_ticket');
				})
			->whereRaw('(`ticketing__activity`.`activity` = "CANCEL" OR `ticketing__activity`.`activity` = "CLOSE")')
			->orderBy('ticketing__activity.id','DESC')
			->whereRaw('`ticketing__activity`.`date` BETWEEN "' . $startDate . '" AND "' . $endDate . '"')
			->get()
			->pluck('id_ticket');

		$limit = $occurring_ticket_result->count() > 100 ? 100 : 100 - $occurring_ticket_result->count();

		$finish_ticket_result = TicketingDetail::with([
				'first_activity_ticket:id_ticket,date,operator',
				'lastest_activity_ticket',
				'id_detail:id_ticket,id',
			])
			->whereIn('id_ticket',$finish_ticket)
			->where('severity',$req->severity)
			->take($limit)
			->orderBy('id','DESC');

		if (isset($req->pid)) {
			$finish_ticket_result = $finish_ticket_result->where('pid',$req->pid);
		} 
		if (isset($req->start) && isset($req->end)){
			// $finish_ticket_result = $finish_ticket_result->whereRaw('`ticketing__detail`.`reporting_time` BETWEEN "' . $startDate . '" AND "' . $endDate . '"');
			if ($cek_role->name_role == 'Engineer Onsite Enterprise' || $cek_role->name_role == 'Engineer Onsite SOC' || $cek_role->name_role == 'Engineer Onsite ATM' || $cek_role->name_role == 'Delivery Project Coordinator'  || $cek_role->name_role == 'Customer Support Center') {
        		$finish_ticket_result = $finish_ticket_result->whereIn('pid',$getPid)->whereRaw('`ticketing__detail`.`reporting_time` BETWEEN "' . $startDate . '" AND "' . $endDate . '"');
        	} elseif($cek_role->name_role == 'Synergy System & Services Manager' || Auth::User()->nik = '1181195100'){
        		$finish_ticket_result = $finish_ticket_result->whereIn('pid',$getPidEoS)->whereRaw('`ticketing__detail`.`reporting_time` BETWEEN "' . $startDate . '" AND "' . $endDate . '"');
        	} elseif($cek_role->name_role == 'Customer Relation Manager'){
        		$finish_ticket_result = $finish_ticket_result->whereIn('pid',$getPidCC)->whereRaw('`ticketing__detail`.`reporting_time` BETWEEN "' . $startDate . '" AND "' . $endDate . '"');
        	} else {
        		$finish_ticket_result = $finish_ticket_result->whereRaw('`ticketing__detail`.`reporting_time` BETWEEN "' . $startDate . '" AND "' . $endDate . '"');
        	}
		} 
		
		$finish_ticket_result = $finish_ticket_result->get();

		$result = $occurring_ticket_result->merge($finish_ticket_result);

        $statusSequence = DB::table('ticketing__activity')
		    ->select('id_ticket', 'activity', 'date')
		    ->selectRaw('ROW_NUMBER() OVER (PARTITION BY id_ticket ORDER BY date) AS seq_num')
		    ->whereRaw('`ticketing__activity`.`date` BETWEEN "' . $startDate . '" AND "' . $endDate . '"');

		$pairedProgressPending = DB::table($statusSequence, 'ppp')
		    ->select('ppp.id_ticket', 'ppp.date AS progress_time', DB::raw('MIN(n.date) AS pending_time'))
		    ->leftJoinSub($statusSequence,'n', function ($join) use ($startDate,$endDate) {
		        $join->on('ppp.id_ticket', '=', 'n.id_ticket')
		            ->where('n.activity', 'PENDING')
		            ->whereRaw('`n`.`date` BETWEEN "' . $startDate . '" AND "' . $endDate . '"')
		            ->whereColumn('n.seq_num', '>', 'ppp.seq_num');
		    })
		    ->where('ppp.activity', 'ON PROGRESS')
		    ->whereRaw('`ppp`.`date` BETWEEN "' . $startDate . '" AND "' . $endDate . '"')
		    ->groupBy('ppp.id_ticket', 'ppp.date');

        $progressToClose = DB::table('ticketing__activity as ptc')
            ->select('id_ticket', DB::raw('MIN(date) as close_time'))
            ->where('activity', 'CLOSE')
            ->whereRaw('`ptc`.`date` BETWEEN "' . $startDate . '" AND "' . $endDate . '"')
            ->groupBy('id_ticket');

        $openToProgress = DB::table('ticketing__activity as opt')
            ->select(
                'id_ticket',
                DB::raw('MIN(CASE WHEN activity = "ON PROGRESS" THEN date END) as first_on_progress_time'),
                DB::raw('MIN(CASE WHEN activity = "OPEN" THEN date END) as open_time')
            )
            ->whereRaw('`opt`.`date` BETWEEN "' . $startDate . '" AND "' . $endDate . '"')
            ->groupBy('id_ticket');

        $openToLatestActivity = DB::table('ticketing__activity')
		    ->select(
		        'id_ticket',
		        DB::raw('MIN(CASE WHEN activity = "OPEN" THEN date END) AS open_time'),
		        DB::raw('MAX(date) AS latest_activity_time'),
				DB::raw('MAX(activity) AS latest_activity'),
		        DB::raw('MAX(CASE WHEN activity = "CLOSE" THEN date END) AS close_time') 
		    )
		    ->whereIn('id_ticket', $result->pluck('id_ticket'))
		    ->groupBy('id_ticket');

        $resolution_time = DB::table('ticketing__activity AS ppppp')
		    ->select(
		        'ppppp.id_ticket', 'ol.latest_activity',
		        DB::raw('TIMESTAMPDIFF(SECOND, ppp.progress_time, ppp.pending_time) AS progress_to_pending_seconds'),
		        DB::raw('CASE WHEN ppp.pending_time IS NOT NULL THEN NULL ELSE TIMESTAMPDIFF(SECOND, ppp.progress_time, ptc.close_time) END AS progress_to_close_seconds'),
		        DB::raw('TIMESTAMPDIFF(SECOND, opt.open_time, opt.first_on_progress_time) AS open_to_progress_seconds'),
		        DB::raw('TIMESTAMPDIFF(SECOND, ol.open_time, ol.latest_activity_time) AS open_to_latest_activity_seconds')
		    )
		    ->leftJoinSub($pairedProgressPending, 'ppp', 'ppppp.id_ticket', '=', 'ppp.id_ticket')
		    ->leftJoinSub($progressToClose, 'ptc', 'ppppp.id_ticket', '=', 'ptc.id_ticket')
		    ->leftJoinSub($openToProgress, 'opt', 'ppppp.id_ticket', '=', 'opt.id_ticket')
		    ->leftJoinSub($openToLatestActivity, 'ol', 'ppppp.id_ticket', '=', 'ol.id_ticket')
		    ->whereIn('ppppp.id_ticket', $result->pluck('id_ticket'))
		    ->groupBy('ppppp.id_ticket', 'progress_to_close_seconds', 'progress_to_pending_seconds', 'open_to_progress_seconds')
		    ->orderBy('ppppp.id_ticket');

        $resolution_time_summary = DB::table($resolution_time, 'resolution')
            ->select(
                'resolution.id_ticket', 'resolution.latest_activity',
                // DB::raw('SUM(CASE WHEN resolution.progress_to_pending_seconds IS NOT NULL THEN resolution.progress_to_pending_seconds ELSE 0 END) as total_progress_to_pending_seconds'),
                DB::raw('MAX(resolution.progress_to_pending_seconds) as total_progress_to_pending_seconds'),
                // DB::raw('MAX(resolution.progress_to_close_seconds) as total_progress_to_close_seconds'),
                // DB::raw('SUM(CASE WHEN resolution.progress_to_close_seconds IS NOT NULL THEN resolution.progress_to_close_seconds ELSE 0 END) as total_progress_to_close_seconds'),
                DB::raw('MAX(resolution.progress_to_close_seconds) as total_progress_to_close_seconds'),
                DB::raw('MAX(resolution.open_to_progress_seconds) as last_open_to_progress_seconds'),
                DB::raw('MAX(resolution.open_to_latest_activity_seconds) as last_open_to_latest_activity_seconds')
            )
            ->groupBy('resolution.id_ticket')
            ->orderBy('resolution.id_ticket')
            ->get();

        // $ticketsWithResolutionTime = TicketingDetail::with([
		//     'first_activity_ticket:id_ticket,date,operator',
		//     'lastest_activity_ticket',
		//     'id_detail:id_ticket,id' 
		// ])->whereIn('ticketing__detail.id_ticket', $result->pluck('id_ticket'))->leftJoinSub($resolution_time_summary, 'resolution_summary', function ($join) {
		//     $join->on('ticketing__detail.id_ticket', '=', 'resolution_summary.id_ticket');
		// })
		// ->select('ticketing__detail.*', 
		//          'resolution_summary.total_progress_to_pending_seconds', 
		//          'resolution_summary.total_progress_to_close_seconds', 
		//          'resolution_summary.last_open_to_progress_seconds')
		// ->orderBy('id_ticket','desc')->get();


        // foreach ($ticketsWithResolutionTime as $ticket) {
		// 	if ($ticket->pid == '') {
		// 		$cekSla = SLAProject::where('pid','Standard');
		// 	} elseif(isset($ticket->pid)){
		// 		$cekSla = SLAProject::where('pid',$ticket->pid);
		// 	} 

		// 	if ($ticket->severity == '1') {
		// 		$cekSla = $cekSla->select('sla_response','sla_resolution_critical as sla_resolution')->first();
		// 	} elseif ($ticket->severity == '2') {
		// 		$cekSla = $cekSla->select('sla_response','sla_resolution_major as sla_resolution')->first();
		// 	} elseif ($ticket->severity == '3') {
		// 		$cekSla = $cekSla->select('sla_response','sla_resolution_moderate as sla_resolution')->first();
		// 	} elseif ($ticket->severity == '4') {
		// 		$cekSla = $cekSla->select('sla_response','sla_resolution_minor as sla_resolution')->first();
		// 	} elseif ($ticket->severity == '0'){
		// 		$cekSla = $cekSla->select('sla_response','sla_resolution_minor as sla_resolution')->first();
		// 	}

		//     $firstActivity = $ticket->first_activity_ticket;
		//     $lastActivity = $ticket->lastest_activity_ticket;

		//     $now = time();

		//     $openTime = $closeTime = $durationFromLastActivity = null;

		//     if ($firstActivity) {
		//         $openTime = strtotime($firstActivity->date);
		//     }

		//     if ($firstActivity && $lastActivity) {
		//         $openTime = strtotime($firstActivity->date);
		//         $closeTime = strtotime($lastActivity->date);

        //         $resolutionTimeInHours = ((float)$ticket->open_to_progress_seconds + (float)$ticket->progress_to_pending_seconds + $ticket->progress_to_close_seconds) / 3600;
		// 		$formattedTime         = $this->formatResolutionTime($resolutionTimeInHours);

		//         $ticket->sla_resolution_percentage = $formattedTime;

		//         if ($resolutionTimeInHours <= $cekSla->sla_resolution) {
		//         	if ($resolutionTimeInHours == 0) {
        //         		$ticket->highlight_sla_resolution = '-';
        //         	}else{
        //             	$ticket->highlight_sla_resolution = 'Comply';
        //         	}
		//         } else {
		//             $ticket->highlight_sla_resolution = 'Not-Comply';
		//         }
		//     } else {
		//         $ticket->sla_resolution_percentage = '-'; 
		//         $ticket->highlight_sla_resolution = 'Not-Comply';
		//     }

		//     if ($firstActivity) {
		//         $responseTimeInSeconds = $openTime - strtotime($ticket->reporting_time);
		//         $responseTimeInMinutes = $responseTimeInSeconds / 60;
		//         $responseTimeInHour = $responseTimeInMinutes / 60;

		//         $formattedTime = $this->formatResponseTime($responseTimeInMinutes);

		//         $ticket->response_time_percentage = $formattedTime;

		//         if ($responseTimeInHour <= $cekSla->sla_response) {
		//             $ticket->highlight_sla_response = 'Comply';
		//         } else {
		//             $ticket->highlight_sla_response = 'Not-Comply';
		//         }

		//         if ($lastActivity->activity != 'CLOSE') {
		//             $durationFromLastActivity = $now - strtotime($lastActivity->date);
		//         }

		//         if ($lastActivity->activity !== 'CLOSE') {
		//             $durationFromLastActivity = $now - strtotime($lastActivity->date);
		//             $ticket->duration_from_last_activity = $this->formatDuration($durationFromLastActivity);
		//         } else {
		//             $ticket->duration_from_last_activity = '-';
		//         }
		//     } else {
		//         $ticket->response_time_percentage = '-';
		//         $ticket->highlight_sla_response = 'Not-Comply';
		//         $ticket->duration_from_last_activity = '-';

		//     }
		// }

		// return array('data' => $ticketsWithResolutionTime);

        $result->each(function ($ticket) use ($resolution_time_summary) {
            $resolutionData = $resolution_time_summary->firstWhere('id_ticket', $ticket->id_ticket);

            if ($resolutionData) {
                $ticket->progress_to_pending_seconds = $resolutionData->total_progress_to_pending_seconds;
                $ticket->progress_to_close_seconds = $resolutionData->total_progress_to_close_seconds;
                $ticket->open_to_progress_seconds = $resolutionData->last_open_to_progress_seconds;
                // if ($resolutionData->latest_activity == 'CLOSE') {
		        //     $ticket->open_to_progress_seconds = $resolutionData->last_open_to_progress_seconds;
		        // } else {
				// 	$ticket->open_to_progress_seconds = $resolutionData->last_open_to_latest_activity_seconds;
		        // }
            } else {
                $ticket->progress_to_pending_seconds = null;
                $ticket->progress_to_close_seconds = null;
                $ticket->open_to_progress_seconds = null;
            }
        });

		$standardSLA = SLAProject::where('pid', 'Standard')->first();
		$customSLA = SLAProject::whereIn('pid', $result->pluck('pid')->filter())->get()->keyBy('pid');

		foreach ($result as $ticket) {
		    $sla = $ticket->pid ? ($customSLA[$ticket->pid] ?? $standardSLA) : $standardSLA;

		    switch ($ticket->severity) {
		        case '1':
		            $slaResponse = $sla->sla_response;
		            $slaResolution = $sla->sla_resolution_critical;
		            break;
		        case '2':
		            $slaResponse = $sla->sla_response;
		            $slaResolution = $sla->sla_resolution_major;
		            break;
		        case '3':
		            $slaResponse = $sla->sla_response;
		            $slaResolution = $sla->sla_resolution_moderate;
		            break;
		        case '4':
		        case '0':
		            $slaResponse = $sla->sla_response;
		            $slaResolution = $sla->sla_resolution_minor;
		            break;
		        default:
		            $slaResponse = $sla->sla_response;
		            $slaResolution = $sla->sla_resolution_minor;
		            break;
		    }

		    $firstActivity = $ticket->first_activity_ticket;
		    $lastActivity = $ticket->lastest_activity_ticket;

		    $now = time();
		    $openTime = $firstActivity ? strtotime($firstActivity->date) : null;
		    $closeTime = $lastActivity ? strtotime($lastActivity->date) : null;

		    if ($openTime && $closeTime) {
		        $resolutionTimeInHours = ($ticket->open_to_progress_seconds + $ticket->progress_to_pending_seconds + $ticket->progress_to_close_seconds) / 3600;
		        $resolutionTimeInMinutes = ($ticket->open_to_progress_seconds + $ticket->progress_to_pending_seconds + $ticket->progress_to_close_seconds) / 60;

		        if ($req->export == true) {
		        	$ticket->sla_resolution_percentage = number_format($resolutionTimeInMinutes,2);
		        } else {
		        	$ticket->sla_resolution_percentage = $this->formatResolutionTime($resolutionTimeInHours);
		        }
		        $ticket->highlight_sla_resolution = $resolutionTimeInHours <= $slaResolution && $resolutionTimeInHours != 0 ? 'Comply' : 'Not-Comply';
		    } else {
		        $ticket->sla_resolution_percentage = '-';
		        $ticket->highlight_sla_resolution = 'Not-Comply';
		    }

		    if ($openTime) {
		        $responseTimeInSeconds = $openTime - strtotime($ticket->reporting_time);
		        $responseTimeInHours = $responseTimeInSeconds / 3600;

		        if ($req->export == true) {
		        	$ticket->response_time_percentage = number_format(($responseTimeInSeconds / 60),2);
		        } else {
		        	$ticket->response_time_percentage = $this->formatResponseTime($responseTimeInSeconds / 60);
		        }

		        
		        $ticket->highlight_sla_response = $responseTimeInHours <= $slaResponse ? 'Comply' : 'Not-Comply';

		        if ($lastActivity->activity !== 'CLOSE') {
		            $durationFromLastActivity = $now - strtotime($lastActivity->date);
		            $ticket->duration_from_last_activity = $this->formatDuration($durationFromLastActivity);
		        } else {
		            $ticket->duration_from_last_activity = '-';
		        }
		    } else {
		        $ticket->response_time_percentage = '-';
		        $ticket->highlight_sla_response = 'Not-Comply';
		        $ticket->duration_from_last_activity = '-';
		    }
		}

		return array("data" => $result);
	}

	public function getPerformanceByNeedAttention(Request $req){
		$startDate = $req->start . ' 00:00:01';
		$endDate = $req->end . ' 23:59:59';

		$nik = Auth::User()->nik;
        $cek_role = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role','group','mini_group')->where('user_id',$nik)->first();

        $nikEoS = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role','group','mini_group','nik')->where('roles.name','Engineer Onsite Enterprise')->orwhere('roles.name','Engineer Onsite SOC')->orwhere('roles.name','Engineer Onsite ATM')->orwhere('roles.name','Delivery Project Coordinator')->get()->pluck('nik');
        $nikCC = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role','group','mini_group','nik')->where('roles.name','Customer Support Center')->get()->pluck('nik');

        $getPid = DB::table('ticketing__user')->where('nik',Auth::User()->nik)->get()->pluck('pid');
        $getPidEoS = DB::table('ticketing__user')->whereIn('nik',$nikEoS)->get()->pluck('pid');
        $getPidCC = DB::table('ticketing__user')->whereIn('nik',$nikCC)->get()->pluck('pid');

		$occurring_ticket = DB::table('ticketing__activity')
			->select('ticketing__activity.id_ticket','ticketing__activity.activity')
			->whereIn('ticketing__activity.id',function ($query) use ($startDate,$endDate){
				$query->select(DB::raw("MAX(id) AS activity"))
					->from('ticketing__activity')
					// ->whereRaw('`ticketing__activity`.`date` BETWEEN "' . $startDate . '" AND "' . $endDate . '"')
					->groupBy('id_ticket');
				})
            ->whereRaw('`ticketing__activity`.`date` BETWEEN "' . $startDate . '" AND "' . $endDate . '"')
			->where('ticketing__activity.activity','<>','CLOSE')
            ->where('ticketing__activity.activity','<>','CANCEL')
            // ->where('ticketing__activity.activity','<>','PENDING')
			->get();

		$occurring_ticket_result = TicketingDetail::with([
				'first_activity_ticket:id_ticket,date,operator',
				'lastest_activity_ticket',
				'id_detail:id_ticket,id',
			])
			->whereIn('ticketing__detail.id_ticket',$occurring_ticket->pluck('id_ticket'))
			// ->orderBy('id','DESC');
			->orderByRaw('FIELD(ticketing__detail.severity, "1", "2", "3", "4") ASC');

		if (isset($req->pid)) {
			$occurring_ticket_result = $occurring_ticket_result->where('pid',$req->pid);
		}else if (isset($req->client)) {
			$occurring_ticket_result = $occurring_ticket_result->where('pid','like','%'.$req->client.'%');
		}

		if (isset($req->start) && isset($req->end)){
			// $occurring_ticket_result = $occurring_ticket_result->whereRaw('`ticketing__detail`.`reporting_time` BETWEEN "' . $startDate . '" AND "' . $endDate . '"');
			if ($cek_role->name_role == 'Engineer Onsite Enterprise' || $cek_role->name_role == 'Engineer Onsite SOC' || $cek_role->name_role == 'Engineer Onsite ATM' || $cek_role->name_role == 'Delivery Project Coordinator'  || $cek_role->name_role == 'Customer Support Center') {
        		$occurring_ticket_result = $occurring_ticket_result->whereIn('pid',$getPid)->whereRaw('`ticketing__detail`.`reporting_time` BETWEEN "' . $startDate . '" AND "' . $endDate . '"');
        	} elseif($cek_role->name_role == 'Synergy System & Services Manager' || Auth::User()->nik = '1181195100'){
        		$occurring_ticket_result = $occurring_ticket_result->whereIn('pid',$getPidEoS)->whereRaw('`ticketing__detail`.`reporting_time` BETWEEN "' . $startDate . '" AND "' . $endDate . '"');
        	} elseif($cek_role->name_role == 'Customer Relation Manager'){
        		$occurring_ticket_result = $occurring_ticket_result->whereIn('pid',$getPidCC)->whereRaw('`ticketing__detail`.`reporting_time` BETWEEN "' . $startDate . '" AND "' . $endDate . '"');
        	} else {
        		$occurring_ticket_result = $occurring_ticket_result->whereRaw('`ticketing__detail`.`reporting_time` BETWEEN "' . $startDate . '" AND "' . $endDate . '"');
        	}
		} 

		$result = $occurring_ticket_result->get();

		$statusSequence = DB::table('ticketing__activity')
		    ->select('id_ticket', 'activity', 'date')
		    ->selectRaw('ROW_NUMBER() OVER (PARTITION BY id_ticket ORDER BY date) AS seq_num')
		    ->whereRaw('`ticketing__activity`.`date` BETWEEN "' . $startDate . '" AND "' . $endDate . '"');

		$pairedProgressPending = DB::table($statusSequence, 'ppp')
		    ->select('ppp.id_ticket', 'ppp.date AS progress_time', DB::raw('MIN(n.date) AS pending_time'))
		    ->leftJoinSub($statusSequence,'n', function ($join) use ($startDate,$endDate) {
		        $join->on('ppp.id_ticket', '=', 'n.id_ticket')
		            ->where('n.activity', 'PENDING')
		            ->whereRaw('`n`.`date` BETWEEN "' . $startDate . '" AND "' . $endDate . '"')
		            ->whereColumn('n.seq_num', '>', 'ppp.seq_num');
		    })
		    ->where('ppp.activity', 'ON PROGRESS')
		    ->whereRaw('`ppp`.`date` BETWEEN "' . $startDate . '" AND "' . $endDate . '"')
		    ->groupBy('ppp.id_ticket', 'ppp.date');

        $progressToClose = DB::table('ticketing__activity as ptc')
            ->select('ptc.id_ticket', DB::raw('MIN(date) as close_time'))
            ->where('activity', 'CLOSE')
            ->whereRaw('`ptc`.`date` BETWEEN "' . $startDate . '" AND "' . $endDate . '"')
            ->groupBy('ptc.id_ticket');

        $openToProgress = DB::table('ticketing__activity as opt')
            ->select(
                'opt.id_ticket',
                DB::raw('MIN(CASE WHEN activity = "ON PROGRESS" THEN date END) as first_on_progress_time'),
                DB::raw('MIN(CASE WHEN activity = "OPEN" THEN date END) as open_time')
            )
            ->whereRaw('`opt`.`date` BETWEEN "' . $startDate . '" AND "' . $endDate . '"')
            ->groupBy('opt.id_ticket');

        $openToLatestActivity = DB::table('ticketing__activity')
		    ->select(
		        'id_ticket',
		        DB::raw('MIN(CASE WHEN activity = "OPEN" THEN date END) AS open_time'),
		        DB::raw('MAX(date) AS latest_activity_time'),
				DB::raw('MAX(activity) AS latest_activity'),
		        DB::raw('MAX(CASE WHEN activity = "CLOSE" THEN date END) AS close_time') 
		    )
		    ->whereRaw('`date` BETWEEN "' . $startDate . '" AND "' . $endDate . '"')
		    ->groupBy('id_ticket');

        // $resolution_time = DB::table('ticketing__activity as pppp')
        //     ->select(
        //         'pppp.id_ticket',
        //         DB::raw('TIMESTAMPDIFF(SECOND, progress_time, pending_time) as progress_to_pending_seconds'),
        //         DB::raw('CASE WHEN pending_time IS NOT NULL THEN NULL ELSE TIMESTAMPDIFF(SECOND, progress_time, ptc.close_time) END as progress_to_close_seconds'),
        //         DB::raw('TIMESTAMPDIFF(SECOND, opt.open_time, opt.first_on_progress_time) as open_to_progress_seconds'),
        //         DB::raw('TIMESTAMPDIFF(SECOND, ol.open_time, ol.latest_activity_time) AS open_to_latest_activity_seconds')
        //     )
        //     ->leftJoinSub($pairedProgressPending, 'ppp', 'pppp.id_ticket', '=', 'ppp.id_ticket')
        //     ->leftJoinSub($progressToClose, 'ptc', 'pppp.id_ticket', '=', 'ptc.id_ticket')
        //     ->leftJoinSub($openToProgress,'opt', 'pppp.id_ticket', '=', 'opt.id_ticket')
		//     ->leftJoinSub($openToLatestActivity, 'ol', 'pppp.id_ticket', '=', 'ol.id_ticket')
        //     ->whereIn('pppp.id_ticket',$occurring_ticket_result->pluck('id_ticket'))
        //     ->orderBy('pppp.id_ticket')
        //     ->groupBy('pppp.id_ticket','progress_to_close_seconds','progress_to_pending_seconds','open_to_progress_seconds');

		$resolution_time = DB::table('ticketing__activity AS ppppp')
		    ->select(
		        'ppppp.id_ticket', 'ol.latest_activity',
		        DB::raw('TIMESTAMPDIFF(SECOND, ppp.progress_time, ppp.pending_time) AS progress_to_pending_seconds'),
		        DB::raw('CASE WHEN ppp.pending_time IS NOT NULL THEN NULL ELSE TIMESTAMPDIFF(SECOND, ppp.progress_time, ptc.close_time) END AS progress_to_close_seconds'),
		        DB::raw('TIMESTAMPDIFF(SECOND, opt.open_time, opt.first_on_progress_time) AS open_to_progress_seconds'),
		        DB::raw('TIMESTAMPDIFF(SECOND, ol.open_time, ol.latest_activity_time) AS open_to_latest_activity_seconds')
		    )
		    ->leftJoinSub($pairedProgressPending, 'ppp', 'ppppp.id_ticket', '=', 'ppp.id_ticket')
		    ->leftJoinSub($progressToClose, 'ptc', 'ppppp.id_ticket', '=', 'ptc.id_ticket')
		    ->leftJoinSub($openToProgress, 'opt', 'ppppp.id_ticket', '=', 'opt.id_ticket')
		    ->leftJoinSub($openToLatestActivity, 'ol', 'ppppp.id_ticket', '=', 'ol.id_ticket')
		    ->whereIn('ppppp.id_ticket', $occurring_ticket_result->pluck('id_ticket'))
		    ->groupBy('ppppp.id_ticket', 'progress_to_close_seconds', 'progress_to_pending_seconds', 'open_to_progress_seconds')
		    ->orderBy('ppppp.id_ticket');

        $resolution_time_summary = DB::table($resolution_time, 'resolution')
            ->select(
                'resolution.id_ticket', 'resolution.latest_activity',
                // DB::raw('SUM(CASE WHEN resolution.progress_to_pending_seconds IS NOT NULL THEN resolution.progress_to_pending_seconds ELSE 0 END) as total_progress_to_pending_seconds'),
                DB::raw('MAX(resolution.progress_to_pending_seconds) as total_progress_to_pending_seconds'),
                // DB::raw('MAX(resolution.progress_to_close_seconds) as total_progress_to_close_seconds'),
                // DB::raw('SUM(CASE WHEN resolution.progress_to_close_seconds IS NOT NULL THEN resolution.progress_to_close_seconds ELSE 0 END) as total_progress_to_close_seconds'),
                DB::raw('MAX(resolution.progress_to_close_seconds) as total_progress_to_close_seconds'),
                DB::raw('MAX(resolution.open_to_progress_seconds) as last_open_to_progress_seconds'),
                DB::raw('MAX(resolution.open_to_latest_activity_seconds) as last_open_to_latest_activity_seconds')
            )
            ->groupBy('resolution.id_ticket')
            ->orderBy('resolution.id_ticket')->get();

        // $ticketsWithResolutionTime = $occurring_ticket_result->leftJoinSub($resolution_time_summary, 'resolution_summary', function ($join) {
		//     $join->on('ticketing__detail.id_ticket', '=', 'resolution_summary.id_ticket');
		// })
		// ->select('ticketing__detail.*', 
		//          'resolution_summary.total_progress_to_pending_seconds', 
		//          'resolution_summary.total_progress_to_close_seconds', 
		//          'resolution_summary.last_open_to_progress_seconds')
		// ->get();

		$result->each(function ($ticket) use ($resolution_time_summary) {
            $resolutionData = $resolution_time_summary->firstWhere('id_ticket', $ticket->id_ticket);

            if ($resolutionData) {
                $ticket->progress_to_pending_seconds = $resolutionData->total_progress_to_pending_seconds;
                $ticket->progress_to_close_seconds = $resolutionData->total_progress_to_close_seconds;
                $ticket->open_to_progress_seconds = $resolutionData->last_open_to_progress_seconds;
                // if ($resolutionData->latest_activity == 'CLOSE') {
		        //     $ticket->open_to_progress_seconds = $resolutionData->last_open_to_progress_seconds;
		        // } else {
				// 	$ticket->open_to_progress_seconds = $resolutionData->last_open_to_latest_activity_seconds;
		        // }
            } else {
                $ticket->progress_to_pending_seconds = null;
                $ticket->progress_to_close_seconds = null;
                $ticket->open_to_progress_seconds = null;
            }
        });

        $standardSLA = SLAProject::where('pid', 'Standard')->first();
		$customSLA = SLAProject::whereIn('pid', $result->pluck('pid')->filter())->get()->keyBy('pid');

        foreach ($result as $ticket) {
		    $sla = $ticket->pid ? ($customSLA[$ticket->pid] ?? $standardSLA) : $standardSLA;

		    switch ($ticket->severity) {
		        case '1':
		            $slaResponse = $sla->sla_response;
		            $slaResolution = $sla->sla_resolution_critical;
		            break;
		        case '2':
		            $slaResponse = $sla->sla_response;
		            $slaResolution = $sla->sla_resolution_major;
		            break;
		        case '3':
		            $slaResponse = $sla->sla_response;
		            $slaResolution = $sla->sla_resolution_moderate;
		            break;
		        case '4':
		        case '0':
		            $slaResponse = $sla->sla_response;
		            $slaResolution = $sla->sla_resolution_minor;
		            break;
		        default:
		            $slaResponse = $sla->sla_response;
		            $slaResolution = $sla->sla_resolution_minor;
		            break;
		    }

		    $firstActivity = $ticket->first_activity_ticket;
		    $lastActivity = $ticket->lastest_activity_ticket;

		    $now = time();
		    $openTime = $firstActivity ? strtotime($firstActivity->date) : null;
		    $closeTime = $lastActivity ? strtotime($lastActivity->date) : null;

		    if ($openTime && $closeTime) {
		        $resolutionTimeInHours = ($ticket->open_to_progress_seconds + $ticket->progress_to_pending_seconds + $ticket->progress_to_close_seconds) / 3600;
		        $resolutionTimeInMinutes = ($ticket->open_to_progress_seconds + $ticket->progress_to_pending_seconds + $ticket->progress_to_close_seconds) / 60;

		        if ($req->export == true) {
		        	$ticket->sla_resolution_percentage = number_format($resolutionTimeInMinutes,2);
		        } else {
		        	$ticket->sla_resolution_percentage = $this->formatResolutionTime($resolutionTimeInHours);
		        }
		        $ticket->highlight_sla_resolution = $resolutionTimeInHours <= $slaResolution && $resolutionTimeInHours != 0 ? 'Comply' : 'Not-Comply';
		    } else {
		        $ticket->sla_resolution_percentage = '-';
		        $ticket->highlight_sla_resolution = 'Not-Comply';
		    }

		    if ($openTime) {
		        $responseTimeInSeconds = $openTime - strtotime($ticket->reporting_time);
		        $responseTimeInHours = $responseTimeInSeconds / 3600;

		        if ($req->export == true) {
		        	$ticket->response_time_percentage = number_format(($responseTimeInSeconds / 60),2);
		        } else {
		        	$ticket->response_time_percentage = $this->formatResponseTime($responseTimeInSeconds / 60);
		        }

		        
		        $ticket->highlight_sla_response = $responseTimeInHours <= $slaResponse ? 'Comply' : 'Not-Comply';

		        if ($lastActivity->activity !== 'CLOSE') {
		            $durationFromLastActivity = $now - strtotime($lastActivity->date);
		            $ticket->duration_from_last_activity = $this->formatDuration($durationFromLastActivity);
		        } else {
		            $ticket->duration_from_last_activity = '-';
		        }
		    } else {
		        $ticket->response_time_percentage = '-';
		        $ticket->highlight_sla_response = 'Not-Comply';
		        $ticket->duration_from_last_activity = '-';
		    }
		}

		if (isset($req->client)) {
			$id_client = TB_Contact::where('code',$req->client)->first()->id_customer;
		}else{
			$id_client = '';
		}

		return array("data" => $result,"id_client" => $id_client);
	}

	public function getPerformanceByActivity(Request $req){
		$startDate = $req->start . ' 00:00:01';
		$endDate = $req->end . ' 23:59:59';

		$nik = Auth::User()->nik;
        $cek_role = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role','group','mini_group')->where('user_id',$nik)->first();

        $nikEoS = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role','group','mini_group','nik')->where('roles.name','Engineer Onsite Enterprise')->orwhere('roles.name','Engineer Onsite SOC')->orwhere('roles.name','Engineer Onsite ATM')->orwhere('roles.name','Delivery Project Coordinator')->get()->pluck('nik');
        $nikCC = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role','group','mini_group','nik')->where('roles.name','Customer Support Center')->get()->pluck('nik');

       	$getPid = DB::table('ticketing__user')->where('nik',Auth::User()->nik)->get()->pluck('pid');
        $getPidEoS = DB::table('ticketing__user')->whereIn('nik',$nikEoS)->get()->pluck('pid');
        $getPidCC = DB::table('ticketing__user')->whereIn('nik',$nikCC)->get()->pluck('pid');

		$occurring_ticket = DB::table('ticketing__activity')
			->select('ticketing__activity.id_ticket','ticketing__activity.activity')
			->whereIn('ticketing__activity.id',function ($query) use ($startDate,$endDate){
				$query->select(DB::raw("MAX(id) AS activity"))
					->from('ticketing__activity')
					->whereRaw('`ticketing__activity`.`date` BETWEEN "' . $startDate . '" AND "' . $endDate . '"')
					->groupBy('id_ticket');
				})
			->where('ticketing__activity.activity',$req->activity)
			->whereRaw('`ticketing__activity`.`date` BETWEEN "' . $startDate . '" AND "' . $endDate . '"')
			->get();

		$occurring_ticket_result = TicketingDetail::with([
				'first_activity_ticket:id_ticket,date,operator',
				'lastest_activity_ticket',
				'id_detail:id_ticket,id',
			])
			->whereIn('ticketing__detail.id_ticket',$occurring_ticket->pluck('id_ticket'))
			->orderBy('id','DESC');

		if (isset($req->pid)) {
			$occurring_ticket_result = $occurring_ticket_result->where('pid',$req->pid);
		} 
		if (isset($req->start) && isset($req->end)){
			// $occurring_ticket_result = $occurring_ticket_result->whereRaw('`ticketing__detail`.`reporting_time` BETWEEN "' . $startDate . '" AND "' . $endDate . '"');
			if ($cek_role->name_role == 'Engineer Onsite Enterprise' || $cek_role->name_role == 'Engineer Onsite SOC' || $cek_role->name_role == 'Engineer Onsite ATM' || $cek_role->name_role == 'Delivery Project Coordinator' || $cek_role->name_role == 'Customer Support Center') {
        		$occurring_ticket_result = $occurring_ticket_result->whereIn('pid',$getPid)->where('ticketing__detail.id_ticket','like','%'.date('Y'));
        	} elseif($cek_role->name_role == 'Synergy System & Services Manager' || Auth::User()->nik = '1181195100'){
        		$occurring_ticket_result = $occurring_ticket_result->whereIn('pid',$getPidEoS)->where('ticketing__detail.id_ticket','like','%'.date('Y'));
        	} elseif($cek_role->name_role == 'Customer Relation Manager'){
        		$occurring_ticket_result = $occurring_ticket_result->whereIn('pid',$getPidCC)->where('ticketing__detail.id_ticket','like','%'.date('Y'));
        	} else {
        		$occurring_ticket_result = $occurring_ticket_result->where('ticketing__detail.id_ticket','like','%'.date('Y'));
        	}
		} 

		$result = $occurring_ticket_result->get();

		$statusSequence = DB::table('ticketing__activity')
		    ->select('id_ticket', 'activity', 'date')
		    ->selectRaw('ROW_NUMBER() OVER (PARTITION BY id_ticket ORDER BY date) AS seq_num')
		    ->whereRaw('`ticketing__activity`.`date` BETWEEN "' . $startDate . '" AND "' . $endDate . '"');

		$pairedProgressPending = DB::table($statusSequence, 'ppp')
		    ->select('ppp.id_ticket', 'ppp.date AS progress_time', DB::raw('MIN(n.date) AS pending_time'))
		    ->leftJoinSub($statusSequence,'n', function ($join) use ($startDate,$endDate,$occurring_ticket_result) {
		        $join->on('ppp.id_ticket', '=', 'n.id_ticket')
		            ->where('n.activity', 'PENDING')
		            ->whereRaw('`n`.`date` BETWEEN "' . $startDate . '" AND "' . $endDate . '"')
		            ->whereIn('n.id_ticket', $occurring_ticket_result->pluck('id_ticket'))
		            ->whereColumn('n.seq_num', '>', 'ppp.seq_num');
		    })
		    ->where('ppp.activity', 'ON PROGRESS')
		    ->whereIn('ppp.id_ticket', $occurring_ticket_result->pluck('id_ticket'))
		    ->whereRaw('`ppp`.`date` BETWEEN "' . $startDate . '" AND "' . $endDate . '"')
		    ->groupBy('ppp.id_ticket', 'ppp.date');        

        $progressToClose = DB::table('ticketing__activity as ptc')
            ->select('id_ticket', DB::raw('MIN(date) as close_time'))
            ->where('activity', 'CLOSE')
		    ->whereIn('ptc.id_ticket', $occurring_ticket_result->pluck('id_ticket'))
            ->whereRaw('`ptc`.`date` BETWEEN "' . $startDate . '" AND "' . $endDate . '"')
            ->groupBy('id_ticket');                

        $openToProgress = DB::table('ticketing__activity as opt')
            ->select(
                'id_ticket',
                DB::raw('MIN(CASE WHEN activity = "ON PROGRESS" THEN date END) as first_on_progress_time'),
                DB::raw('MIN(CASE WHEN activity = "OPEN" THEN date END) as open_time')
            )
		    ->whereIn('opt.id_ticket', $occurring_ticket_result->pluck('id_ticket'))
            ->whereRaw('`opt`.`date` BETWEEN "' . $startDate . '" AND "' . $endDate . '"')
            ->groupBy('id_ticket');

        $openToLatestActivity = DB::table('ticketing__activity')
		    ->select(
		        'id_ticket',
		        DB::raw('MIN(CASE WHEN activity = "OPEN" THEN date END) AS open_time'),
		        DB::raw('MAX(date) AS latest_activity_time'),
				DB::raw('MAX(activity) AS latest_activity'),
		        DB::raw('MAX(CASE WHEN activity = "CLOSE" THEN date END) AS close_time') 
		    )
		    ->whereIn('ticketing__activity.id_ticket', $occurring_ticket_result->pluck('id_ticket'))
		    ->whereRaw('`date` BETWEEN "' . $startDate . '" AND "' . $endDate . '"')
		    ->groupBy('id_ticket');

		$resolution_time = DB::table('ticketing__activity AS ppppp')
		    ->select(
		        'ppppp.id_ticket', 'ol.latest_activity',
		        DB::raw('TIMESTAMPDIFF(SECOND, ppp.progress_time, ppp.pending_time) AS progress_to_pending_seconds'),
		        DB::raw('CASE WHEN ppp.pending_time IS NOT NULL THEN NULL ELSE TIMESTAMPDIFF(SECOND, ppp.progress_time, ptc.close_time) END AS progress_to_close_seconds'),
		        DB::raw('TIMESTAMPDIFF(SECOND, opt.open_time, opt.first_on_progress_time) AS open_to_progress_seconds'),
		        DB::raw('TIMESTAMPDIFF(SECOND, ol.open_time, ol.latest_activity_time) AS open_to_latest_activity_seconds')
		    )
		    ->leftJoinSub($pairedProgressPending, 'ppp', 'ppppp.id_ticket', '=', 'ppp.id_ticket')
		    ->leftJoinSub($progressToClose, 'ptc', 'ppppp.id_ticket', '=', 'ptc.id_ticket')
		    ->leftJoinSub($openToProgress, 'opt', 'ppppp.id_ticket', '=', 'opt.id_ticket')
		    ->leftJoinSub($openToLatestActivity, 'ol', 'ppppp.id_ticket', '=', 'ol.id_ticket')
		    ->whereIn('ppppp.id_ticket', $occurring_ticket_result->pluck('id_ticket'))
		    ->groupBy('ppppp.id_ticket', 'progress_to_close_seconds', 'progress_to_pending_seconds', 'open_to_progress_seconds')
		    ->orderBy('ppppp.id_ticket');

        $resolution_time_summary = DB::table($resolution_time, 'resolution')
            ->select(
                'resolution.id_ticket', 'resolution.latest_activity',
                // DB::raw('SUM(CASE WHEN resolution.progress_to_pending_seconds IS NOT NULL THEN resolution.progress_to_pending_seconds ELSE 0 END) as total_progress_to_pending_seconds'),
                DB::raw('MAX(resolution.progress_to_pending_seconds) as total_progress_to_pending_seconds'),
                // DB::raw('MAX(resolution.progress_to_close_seconds) as total_progress_to_close_seconds'),
                // DB::raw('SUM(CASE WHEN resolution.progress_to_close_seconds IS NOT NULL THEN resolution.progress_to_close_seconds ELSE 0 END) as total_progress_to_close_seconds'),
                DB::raw('MAX(resolution.progress_to_close_seconds) as total_progress_to_close_seconds'),
                DB::raw('MAX(resolution.open_to_progress_seconds) as last_open_to_progress_seconds'),
                DB::raw('MAX(resolution.open_to_latest_activity_seconds) as last_open_to_latest_activity_seconds')
            )
            ->groupBy('resolution.id_ticket')
            ->orderBy('resolution.id_ticket')
            ->get();

		// return $resolution_time_summary;

        $result->each(function ($ticket) use ($resolution_time_summary) {
            $resolutionData = $resolution_time_summary->firstWhere('id_ticket', $ticket->id_ticket);

            if ($resolutionData) {
                $ticket->progress_to_pending_seconds = $resolutionData->total_progress_to_pending_seconds;
                $ticket->progress_to_close_seconds = $resolutionData->total_progress_to_close_seconds;
                $ticket->open_to_progress_seconds = $resolutionData->last_open_to_progress_seconds;
                // if ($resolutionData->latest_activity == 'CLOSE') {
		        //     $ticket->open_to_progress_seconds = $resolutionData->last_open_to_progress_seconds;
		        // } else {
				// 	$ticket->open_to_progress_seconds = $resolutionData->last_open_to_latest_activity_seconds;
		        // }
            } else {
                $ticket->progress_to_pending_seconds = null;
                $ticket->progress_to_close_seconds = null;
                $ticket->open_to_progress_seconds = null;
            }
        });

        $standardSLA = SLAProject::where('pid', 'Standard')->first();
		$customSLA = SLAProject::whereIn('pid', $result->pluck('pid')->filter())->get()->keyBy('pid');

		foreach ($result as $ticket) {
		    $sla = $ticket->pid ? ($customSLA[$ticket->pid] ?? $standardSLA) : $standardSLA;

		    switch ($ticket->severity) {
		        case '1':
		            $slaResponse = $sla->sla_response;
		            $slaResolution = $sla->sla_resolution_critical;
		            break;
		        case '2':
		            $slaResponse = $sla->sla_response;
		            $slaResolution = $sla->sla_resolution_major;
		            break;
		        case '3':
		            $slaResponse = $sla->sla_response;
		            $slaResolution = $sla->sla_resolution_moderate;
		            break;
		        case '4':
		        case '0':
		            $slaResponse = $sla->sla_response;
		            $slaResolution = $sla->sla_resolution_minor;
		            break;
		        default:
		            $slaResponse = $sla->sla_response;
		            $slaResolution = $sla->sla_resolution_minor;
		            break;
		    }

		    $firstActivity = $ticket->first_activity_ticket;
		    $lastActivity = $ticket->lastest_activity_ticket;

		    $now = time();
		    $openTime = $firstActivity ? strtotime($firstActivity->date) : null;
		    $closeTime = $lastActivity ? strtotime($lastActivity->date) : null;

		    if ($openTime && $closeTime) {
		        $resolutionTimeInHours = ($ticket->open_to_progress_seconds + $ticket->progress_to_pending_seconds + $ticket->progress_to_close_seconds) / 3600;
		        $resolutionTimeInMinutes = ($ticket->open_to_progress_seconds + $ticket->progress_to_pending_seconds + $ticket->progress_to_close_seconds) / 60;

		        if ($req->export == true) {
		        	$ticket->sla_resolution_percentage = number_format($resolutionTimeInMinutes,2);
		        } else {
		        	$ticket->sla_resolution_percentage = $this->formatResolutionTime($resolutionTimeInHours);
		        }
		        $ticket->highlight_sla_resolution = $resolutionTimeInHours <= $slaResolution && $resolutionTimeInHours != 0 ? 'Comply' : 'Not-Comply';
		    } else {
		        $ticket->sla_resolution_percentage = '-';
		        $ticket->highlight_sla_resolution = 'Not-Comply';
		    }

		    if ($openTime) {
		        $responseTimeInSeconds = $openTime - strtotime($ticket->reporting_time);
		        $responseTimeInHours = $responseTimeInSeconds / 3600;

		        if ($req->export == true) {
		        	$ticket->response_time_percentage = number_format(($responseTimeInSeconds / 60),2);
		        } else {
		        	$ticket->response_time_percentage = $this->formatResponseTime($responseTimeInSeconds / 60);
		        }

		        
		        $ticket->highlight_sla_response = $responseTimeInHours <= $slaResponse ? 'Comply' : 'Not-Comply';

		        if ($lastActivity->activity !== 'CLOSE') {
		            $durationFromLastActivity = $now - strtotime($lastActivity->date);
		            $ticket->duration_from_last_activity = $this->formatDuration($durationFromLastActivity);
		        } else {
		            $ticket->duration_from_last_activity = '-';
		        }
		    } else {
		        $ticket->response_time_percentage = '-';
		        $ticket->highlight_sla_response = 'Not-Comply';
		        $ticket->duration_from_last_activity = '-';
		    }
		}

		return array("data" => $result);
	}	

	public function updateTicketPendingBeforeClose(Request $request)
	{
		$activityTicketUpdate = new TicketingActivity();
		$activityTicketUpdate->id_ticket = $request->id_ticket;
		// $activityTicketUpdate->date = Carbon::parse($request->datePendingClose . ' ' . $request->startTimePendingClose)->format('Y-m-d H:i:s');
		$activityTicketUpdate->date = $request->startDateTimePendingClose;
		$activityTicketUpdate->activity = "ON PROGRESS";
		$activityTicketUpdate->operator = Auth::user()->name;
		$activityTicketUpdate->note = $request->saveNotePendingClose;
		$activityTicketUpdate->save();

		$activityTicketUpdate = new TicketingActivity();
		$activityTicketUpdate->id_ticket = $request->id_ticket;
		// $activityTicketUpdate->date = Carbon::parse($request->datePendingClose . ' ' . $request->endTimePendingClose)->format('Y-m-d H:i:s');
		$activityTicketUpdate->date = $request->endDateTimePendingClose;
		$activityTicketUpdate->activity = "ON PROGRESS";
		$activityTicketUpdate->operator = Auth::user()->name;
		$activityTicketUpdate->note = $request->saveNotePendingClose;
		$activityTicketUpdate->save();

		$updatePending = TicketingPendingReminder::where('id_ticket',$request->id_ticket)->orderby('id','desc')->first();
		$updatePending->remind_success = 'SKIPPED';
		$updatePending->save();

		return $request->endDateTimePendingClose;
	}

	public function setUpdateTicket(Request $req){
		
		if(isset($req->email)){
			$this->sendEmail($req->to,$req->cc,$req->subject,$req->body);
		}

		$clientAcronym = explode("/", $req->id_ticket);

		$detailTicketUpdate = TicketingDetail::where('id_ticket',$req->id_ticket)
			->first();

		$engineer = $detailTicketUpdate->engineer;

		if(isset($req->engineer)){
			if(isset($req->engineer) && $detailTicketUpdate->id_atm != null && $clientAcronym[1] == 'BBJB'){
				 $this->setNotif($req->engineer, $req->id_ticket, $detailTicketUpdate->location);
			}
		}

		$detailTicketUpdate->engineer = $req->engineer;
		$detailTicketUpdate->severity = $req->severity;
		$detailTicketUpdate->ticket_number_3party = $req->ticket_number_3party;

		$detailTicketUpdate->save();

		$checkLastActivity = TicketingActivity::where('id_ticket', $req->id_ticket)
		->orderbyDesc('id')->first();

		$this->checkPendingReminder($req->id_ticket);

		$activityTicketUpdate = new TicketingActivity();
		$activityTicketUpdate->id_ticket = $req->id_ticket;
//		 $activityTicketUpdate->date = date("Y-m-d H:i:s.000000");
		$activityTicketUpdate->date = $req->timeOnProgress;
		 if($checkLastActivity->activity == 'OPEN' && isset($req->engineer) && $engineer != $req->engineer && isset($detailTicketUpdate->id_atm) && $clientAcronym[1] == 'BBJB'){
		 	$activityTicketUpdate->activity = "OPEN";
		 }else{
			$activityTicketUpdate->activity = "ON PROGRESS";
		 }
		$activityTicketUpdate->operator = Auth::user()->name;
		$activityTicketUpdate->note = $req->note;

		$activityTicketUpdate->save();

		$cek_client_pid = Ticketing::where("id_ticket",$req->id_ticket)->first();
		if ($cek_client_pid->id_client_pid) {
			$clientIdFilter = Ticketing::where('id_ticket',$req->id_ticket)
				->first()->id_client_pid;

			$cek_code = TicketingEmailSetting::where('id',$clientIdFilter)->first()->client;

			if ($cek_code == "INTERNAL") {
				$clientIdFilter = 'INTERNAL';
			}else{
				$customer = explode("- ", $cek_code)[1];

				$id_client = TB_Contact::where('customer_legal_name', 'LIKE', '%'.$customer.'%')->first()->id_customer;
				$clientIdFilter = $id_client;
			}			
		}else{
			$clientIdFilter = Ticketing::with('client_ticket')
				->where('id_ticket',$req->id_ticket)
				->first()
				->client_ticket
				->id;

			$cek_code = TicketingClient::where('id',$clientIdFilter)->first()->client_acronym;

			if ($cek_code == 'BPJS') {
	    		$cek_code = 'BKES';
	    	} elseif($cek_code == 'PBLG'){
	    		$cek_code = 'BULG';
	    	} elseif($cek_code == 'BGDN'){
	    		$cek_code = 'PGAN';
	    	} elseif($cek_code == 'BJBR'){
	    		$cek_code = 'BBJB';
	    	} elseif($cek_code == 'ADRF'){
	    		$cek_code = 'ADMF';
	    	} elseif($cek_code == 'BTNI'){
	    		$cek_code = 'BBTN';
	    	} else {
	    		$cek_code = $cek_code;
	    	}

			$id_client = TB_Contact::where('code',$cek_code)->first()->id_customer;
			$clientIdFilter = $id_client;
		}

		$activityTicketUpdate->client_id_filter = $clientIdFilter;
		
		return $activityTicketUpdate;
	}

	public function getOnProgressMailTemplate(){
		return view('ticketing.mail.OnProgressTicket');
	}

	private function checkPendingReminder($id_ticket){
		$checkLatestActivity = TicketingActivity::where('id_ticket',$id_ticket)
			->orderBy('id','DESC')
			->first();

		if ($checkLatestActivity->activity == "PENDING"){
			$checkLatestReminderPending = TicketingPendingReminder::where('id_ticket',$id_ticket)
				->where('remind_success','FALSE');

			if($checkLatestReminderPending->count() > 0){
				foreach ($checkLatestReminderPending->get() as $key => $value) {
					$value->remind_success = 'SKIPPED';
					$value->save();
				}
			}

		}
	}

	public function getCancelMailTemplate(){
		return view('ticketing.mail.CancelTicket');
	}

	public function sendEmailCancel(Request $request){
		$this->sendEmail($request->to,$request->cc,$request->subject,$request->body);

		$this->checkPendingReminder($request->id_ticket);

		$activityTicketUpdate = new TicketingActivity();
		$activityTicketUpdate->id_ticket = $request->id_ticket;
		$activityTicketUpdate->date = date("Y-m-d H:i:s.000000");
		$activityTicketUpdate->activity = "CANCEL";
		$activityTicketUpdate->operator = Auth::user()->name;
		$activityTicketUpdate->note = "Cancel Ticket - " . $request->note_cancel;

		$activityTicketUpdate->save();

		$cek_client_pid = Ticketing::where("id_ticket",$request->id_ticket)->first();
		if ($cek_client_pid->id_client_pid) {
			$clientIdFilter = Ticketing::where('id_ticket',$request->id_ticket)
				->first()->id_client_pid;

			$cek_code = TicketingEmailSetting::where('id',$clientIdFilter)->first()->client;

			if ($cek_code == "INTERNAL") {
				$clientIdFilter = 'INTERNAL';
			}else{
				$customer = explode("- ", $cek_code)[1];

				$id_client = TB_Contact::where('customer_legal_name', 'LIKE', '%'.$customer.'%')->first()->id_customer;
				$clientIdFilter = $id_client;
			}			
		}else{
			$clientIdFilter = Ticketing::with('client_ticket')
				->where('id_ticket',$request->id_ticket)
				->first()
				->client_ticket
				->id;

			$cek_code = TicketingClient::where('id',$clientIdFilter)->first()->client_acronym;

			if ($cek_code == 'BPJS') {
	    		$cek_code = 'BKES';
	    	} elseif($cek_code == 'PBLG'){
	    		$cek_code = 'BULG';
	    	} elseif($cek_code == 'BGDN'){
	    		$cek_code = 'PGAN';
	    	} elseif($cek_code == 'BJBR'){
	    		$cek_code = 'BBJB';
	    	} elseif($cek_code == 'ADRF'){
	    		$cek_code = 'ADMF';
	    	} elseif($cek_code == 'BTNI'){
	    		$cek_code = 'BBTN';
	    	} else {
	    		$cek_code = $cek_code;
	    	}

			$id_client = TB_Contact::where('code',$cek_code)->first()->id_customer;
			$clientIdFilter = $id_client;
		}

		$activityTicketUpdate->client_id_filter = $clientIdFilter;
		
		return $activityTicketUpdate;
	}

	public function getPendingTicketData(Request $request){
		return TicketingPendingReminder::where('id_ticket',$request->id_ticket)
			->orderBy('id','DESC')
			->first();
	}

	public function getPendingMailTemplate(){
		return view('ticketing.mail.PendingTicket');
	}

	public function setUpdateTicketPending(Request $request){
		$activityTicketUpdate = new TicketingActivity();
		$activityTicketUpdate->id_ticket = $request->id_ticket;
		$activityTicketUpdate->date = date("Y-m-d H:i:s.000000");
		$activityTicketUpdate->activity = "PENDING";
		$activityTicketUpdate->operator = Auth::user()->name;
		$activityTicketUpdate->note = "Update Pending - " . $request->updatePending;

		$activityTicketUpdate->save();

		$checkLatestReminderPending = TicketingPendingReminder::where('id_ticket',$request->id_ticket)
				->where('remind_success','FALSE');

		if($checkLatestReminderPending->count() > 0){
			foreach ($checkLatestReminderPending->get() as $key => $value) {
				$value->remind_success = 'INVALID';
				$value->save();
			}
		}

		$remainder = new TicketingPendingReminder();
		$remainder->id_pending = $activityTicketUpdate->id;
		$remainder->id_ticket = $request->id_ticket;
		$remainder->remind_create = Carbon::now()->toDateTimeString();
		$remainder->remind_time = Carbon::parse($request->estimationPending)->toDateTimeString();
		$remainder->remind_success = "FALSE";

		$remainder->save();

		// $cek_client_pid = Ticketing::where("id_ticket",$request->id_ticket)->first();
		// if ($cek_client_pid->id_client_pid) {
		// 	$clientIdFilter = Ticketing::where('id_ticket',$request->id_ticket)
		// 		->first()->id_client_pid;
		// }else{
		// $clientIdFilter = Ticketing::with('client_ticket')
		// 	->where('id_ticket',$request->id_ticket)
		// 	->first()
		// 	->client_ticket
		// 	->id;
		// }

		$cek_client_pid = Ticketing::where("id_ticket",$request->id_ticket)->first();
		if ($cek_client_pid->id_client_pid) {
			$clientIdFilter = Ticketing::where('id_ticket',$request->id_ticket)
				->first()->id_client_pid;

			$cek_code = TicketingEmailSetting::where('id',$clientIdFilter)->first()->client;

			if ($cek_code == "INTERNAL") {
				$clientIdFilter = 'INTERNAL';
			}else{
				$customer = explode("- ", $cek_code)[1];

				$id_client = TB_Contact::where('customer_legal_name', 'LIKE', '%'.$customer.'%')->first()->id_customer;
				$clientIdFilter = $id_client;
			}			
		}else{
			$clientIdFilter = Ticketing::with('client_ticket')
				->where('id_ticket',$request->id_ticket)
				->first()
				->client_ticket
				->id;

			$cek_code = TicketingClient::where('id',$clientIdFilter)->first()->client_acronym;

			if ($cek_code == 'BPJS') {
	    		$cek_code = 'BKES';
	    	} elseif($cek_code == 'PBLG'){
	    		$cek_code = 'BULG';
	    	} elseif($cek_code == 'BGDN'){
	    		$cek_code = 'PGAN';
	    	} elseif($cek_code == 'BJBR'){
	    		$cek_code = 'BBJB';
	    	} elseif($cek_code == 'ADRF'){
	    		$cek_code = 'ADMF';
	    	} elseif($cek_code == 'BTNI'){
	    		$cek_code = 'BBTN';
	    	} else {
	    		$cek_code = $cek_code;
	    	}

			$id_client = TB_Contact::where('code',$cek_code)->first()->id_customer;
			$clientIdFilter = $id_client;
		}

		$activityTicketUpdate->client_id_filter = $clientIdFilter;

		return $activityTicketUpdate;
	}

	public function sendEmailPending(Request $request){

		$this->sendEmail($request->to,$request->cc,$request->subject,$request->body);

		$activityTicketUpdate = new TicketingActivity();
		$activityTicketUpdate->id_ticket = $request->id_ticket;
		$activityTicketUpdate->date = date("Y-m-d H:i:s.000000");
		$activityTicketUpdate->activity = "PENDING";
		$activityTicketUpdate->operator = Auth::user()->name;
		$activityTicketUpdate->note = "Pending Ticket - " . $request->note_pending;

		$activityTicketUpdate->save();

		$checkLatestReminderPending = TicketingPendingReminder::where('id_ticket',$request->id_ticket)
				->where('remind_success','FALSE');

		if($checkLatestReminderPending->count() > 0){
			foreach ($checkLatestReminderPending->get() as $key => $value) {
				$value->remind_success = 'INVALID';
				$value->save();
			}
		}

		$remainder = new TicketingPendingReminder();
		$remainder->id_pending = $activityTicketUpdate->id;
		$remainder->id_ticket = $request->id_ticket;
		$remainder->remind_create = Carbon::now()->toDateTimeString();
		$remainder->remind_time = Carbon::parse($request->estimationPending)->toDateTimeString();
		$remainder->remind_success = "FALSE";

		$remainder->save();

		$cek_client_pid = Ticketing::where("id_ticket",$request->id_ticket)->first();
		if ($cek_client_pid->id_client_pid) {
			$clientIdFilter = Ticketing::where('id_ticket',$request->id_ticket)
				->first()->id_client_pid;

			$cek_code = TicketingEmailSetting::where('id',$clientIdFilter)->first()->client;

			if ($cek_code == "INTERNAL") {
				$clientIdFilter = 'INTERNAL';
			}else{
				$customer = explode("- ", $cek_code)[1];

				$id_client = TB_Contact::where('customer_legal_name', 'LIKE', '%'.$customer.'%')->first()->id_customer;
				$clientIdFilter = $id_client;
			}			
		}else{
			$clientIdFilter = Ticketing::with('client_ticket')
				->where('id_ticket',$request->id_ticket)
				->first()
				->client_ticket
				->id;

			$cek_code = TicketingClient::where('id',$clientIdFilter)->first()->client_acronym;

			if ($cek_code == 'BPJS') {
	    		$cek_code = 'BKES';
	    	} elseif($cek_code == 'PBLG'){
	    		$cek_code = 'BULG';
	    	} elseif($cek_code == 'BGDN'){
	    		$cek_code = 'PGAN';
	    	} elseif($cek_code == 'BJBR'){
	    		$cek_code = 'BBJB';
	    	} elseif($cek_code == 'ADRF'){
	    		$cek_code = 'ADMF';
	    	} elseif($cek_code == 'BTNI'){
	    		$cek_code = 'BBTN';
	    	} else {
	    		$cek_code = $cek_code;
	    	}

			$id_client = TB_Contact::where('code',$cek_code)->first()->id_customer;
			$clientIdFilter = $id_client;
		}

		$activityTicketUpdate->client_id_filter = $clientIdFilter;
		
		return $activityTicketUpdate;
	}

	public function getCloseMailTemplate(){
		return view('ticketing.mail.CloseTicket');
	}

	public function sendEmailClose(Request $request){

		//SLM Changes
		$filePdf = DB::table('ticketing__slm')->where('id_ticket', $request->id_ticket)
			->where('status', 'CLOSE')
			->select('pdf_report')
			->first();

		if($filePdf){
			$this->sendEmailSlm($request->to,$request->cc,$request->subject,$request->body, $filePdf->pdf_report);
		}else{
			$this->sendEmail($request->to,$request->cc,$request->subject,$request->body);
		}
		//End changes
		$this->checkPendingReminder($request->id_ticket);

		$activityTicketUpdate = new TicketingActivity();
		$activityTicketUpdate->id_ticket = $request->id_ticket;
		$activityTicketUpdate->date = $request->finish;
		$activityTicketUpdate->activity = "CLOSE";
		$activityTicketUpdate->operator = Auth::user()->name;
		$activityTicketUpdate->note = "CLOSE";

		$activityTicketUpdate->save();

		$resolveTicket = new TicketingResolve();
		$resolveTicket->id_ticket = $request->id_ticket;
		$resolveTicket->root_couse = $request->root_cause;
		$resolveTicket->counter_measure = $request->couter_measure;
		$resolveTicket->finish = date("Y-m-d H:i:s.000000");

		$resolveTicket->save();

		$cek_client_pid = Ticketing::where("id_ticket",$request->id_ticket)->first();
		if ($cek_client_pid->id_client_pid) {
			$clientIdFilter = Ticketing::where('id_ticket',$request->id_ticket)
				->first()->id_client_pid;

			$cek_code = TicketingEmailSetting::where('id',$clientIdFilter)->first()->client;

			if ($cek_code == "INTERNAL") {
				$clientIdFilter = 'INTERNAL';
			}else{
				$customer = explode("- ", $cek_code)[1];

				$id_client = TB_Contact::where('customer_legal_name', 'LIKE', '%'.$customer.'%')->first()->id_customer;
				$clientIdFilter = $id_client;
			}			
		}else{
			$clientIdFilter = Ticketing::with('client_ticket')
				->where('id_ticket',$request->id_ticket)
				->first()
				->client_ticket
				->id;

			$cek_code = TicketingClient::where('id',$clientIdFilter)->first()->client_acronym;

			if ($cek_code == 'BPJS') {
	    		$cek_code = 'BKES';
	    	} elseif($cek_code == 'PBLG'){
	    		$cek_code = 'BULG';
	    	} elseif($cek_code == 'BGDN'){
	    		$cek_code = 'PGAN';
	    	} elseif($cek_code == 'BJBR'){
	    		$cek_code = 'BBJB';
	    	} elseif($cek_code == 'ADRF'){
	    		$cek_code = 'ADMF';
	    	} elseif($cek_code == 'BTNI'){
	    		$cek_code = 'BBTN';
	    	} else {
	    		$cek_code = $cek_code;
	    	}

			$id_client = TB_Contact::where('code',$cek_code)->first()->id_customer;
			$clientIdFilter = $id_client;
		}

		$activityTicketUpdate->client_id_filter = $clientIdFilter;
		$cekId = Ticketing::where('id_ticket',$request->id_ticket)->first()->id_client_pid;
		$cekTeleId = TicketingEmailSetting::where('id',$cekId)->first();

		$bodyMassage = 'Dear Tim '. $cekTeleId->client . ', Ticket dengan ID <b>' . $request->id_ticket . '</b> yang berlokasi di <b>' . $request->location . '</b>, dengan problem <b>'. $request->problem . '</b> telah close. Counter Measure ' .$request->counter_measure .'. Root Cause <b>'.$request->root_couse.'</b>. Terima kasih.';

		if (isset($cekTeleId->chat_id)) {
			$this->telegramService->sendMessage($cekTeleId->chat_id,$bodyMassage);
		}
		
		return $activityTicketUpdate;
	}

	public function getEscalateMailTemplate(){
		return view('ticketing.mail.EscalateTicket');
	}

	public function saveEscalate(Request $req){
		
		$escalateTicket = new TicketingEscalateEngineer();
		$escalateTicket->id_ticket = $req->id_ticket;
		$escalateTicket->engineer_name = $req->nameEngineer;
		$escalateTicket->engineer_contact = $req->contactEngineer;
		$escalateTicket->rca = $req->rca;
		$escalateTicket->date_add = Carbon::now()->toDateTimeString();
		$escalateTicket->status = "ON PROGRESS";
		$escalateTicket->save();

		$ticket = TicketingDetail::where('id_ticket','=',$req->id_ticket)->first();

		$this->checkPendingReminder($req->id_ticket);

		$activityTicketUpdate = new TicketingActivity();
		$activityTicketUpdate->id_ticket = $req->id_ticket;
		$activityTicketUpdate->date = Carbon::now()->toDateTimeString();
		$activityTicketUpdate->activity = "ON PROGRESS";
		$activityTicketUpdate->operator = Auth::user()->name;
		$activityTicketUpdate->note = "Engineer Escalation from " . $ticket->engineer . " to " . $req->nameEngineer . " (" . $req->contactEngineer . ")";


		$activityTicketUpdate->save();
		$ticket->engineer = $req->nameEngineer . " (" . $req->contactEngineer . ")";
		$ticket->save();
	}

	public function sendEmailEscalate(Request $req){
		$mail = $this->sendEmail($req->to,$req->cc,$req->subject,$req->body);
		$this->saveEscalate($req);
	}

	public function reOpenTicket(Request $req){
		$req->idTicket = $req->id_ticket;
		$ticket = $this->getPerformanceByTicket($req);

		$cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')->join('users','users.nik','role_user.user_id')
                    ->select('users.name', 'roles.group', 'roles.name as name_role','email'); 

		$requestChange = new RequestChange();
		$requestChange->type = "Re-Open Ticket";
		$requestChange->requester = Auth::user()->name;
		$requestChange->object_id = $req->id_ticket;
		$requestChange->parameter1_before = $req->reason;
		$requestChange->status = "On-Progress";
		$requestChange->save();

		if (Str::contains($req->idTicket, 'BJBR') || Str::contains($req->idTicket, 'BBJB') || Str::contains($req->idTicket, 'BPJS') || Str::contains($req->idTicket, 'BKES')) {
			$cek_role = $cek_role->where('roles.name','Synergy System & Services Manager')->first();
			$to = $cek_role->name;
			$kirim = $cek_role->email;
		} else {
			$cek_role = $cek_role->where('roles.name','Project Transformation Officer')->first();
			$to = $cek_role->name;
			$kirim = $cek_role->email;
		}

		// return $requestChange;

		$mail = new EmailReOpenTicket(collect([
                    "to" => $to,
                    "id_ticket" => $req->id_ticket,
                    "reopen_reason" => $req->reason,
                    "requestor" => Auth::user()->name,
                    "customer" => TicketingEmailSetting::find($ticket->id_detail->id_client_pid)->client,
					"problem" => $ticket->problem,
					"last_update" => $ticket->lastest_activity_ticket->date . " [" . $ticket->lastest_activity_ticket->operator . "] - " . $ticket->lastest_activity_ticket->activity,
					"url" =>  url("/requestChange?id_requestChange=" . $requestChange->id)
                ])
            );
		
		Mail::to($kirim)->send($mail);

		return $mail;
	}

	public function getSettingEmailClientById(Request $req){
		$result = DB::table('ticketing__email_setting')
			->where('id','=',$req->id)
			->get();

		return $result;
	}

	public function getSettingEmailSLMById(Request $req){
		$result = DB::table('ticketing__email_slm')
			->where('id','=',$req->id)
			->get();

		return $result;
	}

	public function getSettingClient(Request $req){
		$result = DB::table('ticketing__client')
			->where('id','=',$req->id)
			->get();

		return $result;
	}

	public function setSettingClient(Request $req){
		DB::table('ticketing__client')
			->where('id','=',$req->id)
			->update([
				"client_name" 		=> $req->client_name,
				"client_acronym"	=> $req->client_acronym,
				"open_dear" 		=> $req->open_dear,
				"open_to" 			=> $req->open_to,
				"open_cc" 			=> $req->open_cc,
				"close_dear" 		=> $req->close_dear,
				"close_to" 			=> $req->close_to,
				"close_cc" 			=> $req->close_cc,
				"situation" 		=> $req->situation,
				"banking"			=> $req->banking,
				"wincor"			=> $req->wincor,
			]);
	}

	public function updateEmailSetting(Request $req){
		// if (TicketingEmailSetting::where('pid',$req->pid)->exists()) {
		// 	return response()->json(['data' => 'Data already exist!'], 500);
		// }else{
		DB::table('ticketing__email_setting')
		->where('id','=',$req->id)
		->update([
			"pid" 		=> $req->pid,
			"client"	=> $req->client_acronym,
			"dear" 		=> $req->dear,
			"to" 		=> $req->to,
			"cc" 		=> $req->cc
		]);
		// }
	}

	public function updateEmailSLM(Request $req){
		// if (TicketingEmailSLM::where('second_level_support',$req->secondLevelSupport)->exists()) {
		// 	return response()->json(['data' => 'Data already exist!'], 500);
		// }else{
		DB::table('ticketing__email_slm')
		->where('id','=',$req->id)
		->update([
			"second_level_support" 	=> $req->secondLevelSupport,
			"dear" 			=> $req->dear,
			"to" 			=> $req->to,
			"cc" 			=> $req->cc
		]);
		// }
		
	}

	public function getAllAtmSetting(){
		return array('data' => TicketingATM::join('ticketing__client','ticketing__atm.owner','=','ticketing__client.id')
			->select(
				'ticketing__atm.id',
				DB::raw('`ticketing__client`.`client_acronym` AS `owner`'),
				'ticketing__atm.atm_id',
				'ticketing__atm.serial_number',
				'ticketing__atm.location',
				'ticketing__atm.activation'
			)
			->orderBy('ticketing__atm.id','DESC')
			->get());
	}

	public function getParameterAddAtm(){
		return TicketingClient::select('id','client_acronym','client_name')
			->where('banking','=',1)
			->get();
	}

	public function newAtm(Request $request){
		$newAtm = new TicketingATM();

        $messages = [
		    'atmID.unique' => 'The ATM ID has already been taken!',
		    'atmSerial.unique' => 'The Serial Number has already been taken!',
		    'atmSerial.required' => 'You must fill serial number of ATM',
		    'atmOwner.required' => 'You must select ATM Owner!',
		    'atmLocation.required' => 'You must set ATM Location!',
		    'atmAddress.required' => 'You must select ATM Address!',
		    'atmActivation.required' => 'You must set ATM Activation date!',
		];

    	$validator = Validator::make($request->all(), [
			'atmID' => 'unique:ticketing__atm,atm_id',
			'atmSerial' => 'unique:ticketing__atm,serial_number',
			'atmSerial' => 'required',
			'atmOwner' => 'required',
			'atmLocation' => 'required',
			'atmAddress' => 'required',
			'atmActivation' => 'required',
        ],$messages);

        if (!$validator->passes()) {
			return response()->json(['error'=>$validator->errors()->all()]);
        }

		$newAtm->fill([
				"owner" => $request->atmOwner,
				"atm_id" => $request->atmID, 
				"serial_number" => $request->atmSerial,
				"location" => $request->atmLocation,
				"address" => $request->atmAddress,
				"activation" => $request->atmActivation == "" ? "1970-01-01" : Carbon::createFromFormat('d/m/Y',$request->atmActivation)->formatLocalized('%Y-%m-%d'),
				"note" => $request->atmNote,
				"machine_type" => $request->atmType,
				"os_atm" => $request->atmOS,
				"versi_atm" => $request->atmVersion,
				"engineer_atm" => $request->atmEngineer
			]);

		$newAtm->save();

	}

	public function newAtmPeripheral(Request $request){
		if(strpos(TicketingClient::find($request->atmOwner)->client_name,"CCTV")){
			$request->peripheralType = "CCTV";
		} else if (strpos(TicketingClient::find($request->atmOwner)->client_name,"UPS")){
			$request->peripheralType = "UPS";
		}

		// return $request->peripheralType;
		$newAtmPeripheral = new TicketingATMPeripheral();
		$newAtmPeripheral->id_atm = TicketingATM::where('atm_id',$request->atmID)->first()->id;
		$newAtmPeripheral->id_peripheral = $request->peripheralID;
		$newAtmPeripheral->type = $request->peripheralType;
		$newAtmPeripheral->serial_number = (isset($request->peripheralSerial) ? $request->peripheralSerial : "-");
		$newAtmPeripheral->machine_type = (isset($request->peripheralMachineType) ? $request->peripheralMachineType : "-");

		$newAtmPeripheral->cctv_dvr_sn = (isset($request->peripheral_cctv_dvr_sn) ? $request->peripheral_cctv_dvr_sn : "-");
		$newAtmPeripheral->cctv_dvr_type = (isset($request->peripheral_cctv_dvr_type) ? $request->peripheral_cctv_dvr_type : "-");
		$newAtmPeripheral->cctv_besar_sn = (isset($request->peripheral_cctv_besar_sn) ? $request->peripheral_cctv_besar_sn : "-");
		$newAtmPeripheral->cctv_besar_type = (isset($request->peripheral_cctv_besar_type) ? $request->peripheral_cctv_besar_type : "-");
		$newAtmPeripheral->cctv_kecil_sn = (isset($request->peripheral_cctv_kecil_sn) ? $request->peripheral_cctv_kecil_sn : "-");
		$newAtmPeripheral->cctv_kecil_type = (isset($request->peripheral_cctv_kecil_type) ? $request->peripheral_cctv_kecil_type : "-");

		$newAtmPeripheral->save();
		return $newAtmPeripheral;
	}

	public function getDetailAtm(Request $request){
		$atm = TicketingATM::with('peripheral')->where('id',$request->id_atm)->first();

		$client = TicketingClient::select('id','client_acronym','client_name')
			->where('banking','=',1)
			->get();

		return array(
			'atm' => $atm,
			'client' => $client
		);
	}

	public function setAtm(Request $request){
		$setAtm = TicketingATM::where('id','=',$request->idAtm)->first();
		 $messages = [
		    'atmID.unique' => 'The ATM ID has already been taken!',
		    'atmSerial.unique' => 'The Serial Number has already been taken!',
		];

    	$validator = Validator::make($request->all(), [
			'atmID' => Rule::unique('ticketing__atm','atm_id')->ignore($setAtm->id),
			'atmSerial' => Rule::unique('ticketing__atm','serial_number')->ignore($setAtm->id),
        ],$messages);

        if (!$validator->passes()) {
			return response()->json(['error'=>$validator->errors()->all()]);
        }

		$setAtm->fill([
				"owner" => $request->atmOwner,
				"atm_id" => $request->atmID, 
				"serial_number" => $request->atmSerial,
				"location" => $request->atmLocation,
				"address" => $request->atmAddress,
				"activation" =>  Carbon::createFromFormat('d/m/Y',$request->atmActivation)->formatLocalized('%Y-%m-%d'),
				"note" => $request->atmNote,
				"machine_type" => $request->atmType,
				"os_atm" => $request->atmOS,
				"versi_atm" => $request->atmVersion,
				"engineer_atm" => $request->atmEngineer
			]);

		$setAtm->save();

	}

	public function deleteAtm(Request $request){
		TicketingATM::where('id','=',$request->idAtm)->first()->delete();
	}

	public function editAtmPeripheral(Request $request){
		$peripheral = TicketingATMPeripheral::find($request->id);

		if($peripheral->type == "CCTV"){
			if($request->type == "1"){
				$peripheral->cctv_dvr_type = $request->typeEdit;
				$peripheral->cctv_dvr_sn = $request->serialEdit;
				$peripheral->save();
			} else if ($request->type == "2") {
				$peripheral->cctv_besar_type = $request->typeEdit;
				$peripheral->cctv_besar_sn = $request->serialEdit;
				$peripheral->save();
			} else {
				$peripheral->cctv_kecil_type = $request->typeEdit;
				$peripheral->cctv_kecil_sn = $request->serialEdit;
				$peripheral->save();
			}
		} else {
			$peripheral->machine_type = $request->typeEdit;
			$peripheral->serial_number = $request->serialEdit;
			$peripheral->save();
		}
		return $peripheral;
	}

	public function deleteAtmPeripheral(Request $request){
		$peripheral = TicketingATMPeripheral::find($request->id);

		if($peripheral->type == "CCTV"){
			if($request->type == "1"){
				$peripheral->cctv_dvr_type = "";
				$peripheral->cctv_dvr_sn = "";
				$peripheral->save();
			} else if ($request->type == "2") {
				$peripheral->cctv_besar_type = "";
				$peripheral->cctv_besar_sn = "";
				$peripheral->save();
			} else {
				$peripheral->cctv_kecil_type = "";
				$peripheral->cctv_kecil_sn = "";
				$peripheral->save();
			}
			if($peripheral->cctv_dvr_type == "" && $peripheral->cctv_besar_type == "" && $peripheral->cctv_kecil_type == ""){
				$peripheral->delete();
			}
		} else {
			$peripheral->delete();
		}
		return $peripheral;
	}

	public function getAllAbsenSetting(){
		return array('data' => TicketingAbsen::get());
	}

	public function newAbsen(Request $request){
		$newAbsen = new TicketingAbsen();

		$newAbsen->nama_cabang = $request->absenAddNamaCabang;
		$newAbsen->nama_kantor = $request->absenAddNamaKantor;
		$newAbsen->type_machine = $request->absenAddMachineType;
		$newAbsen->ip_machine = $request->absenAddIPMachine;
		$newAbsen->ip_server = $request->absenAddIPServer;

        $newAbsen->save();

	}

	public function getDetailAbsen(Request $request){
		return array(
			'absen' => TicketingAbsen::where('id',$request->id_absen)->first()
		);
	}

	public function setAbsen(Request $request){
		$setAbsen = TicketingAbsen::where('id','=',$request->idAbsen)->first();

		$setAbsen->nama_cabang = $request->absenEditNamaCabang;
		$setAbsen->nama_kantor = $request->absenEditNamaKantor;
		$setAbsen->type_machine = $request->absenEditMachineType;
		$setAbsen->ip_machine = $request->absenEditIPMachine;
		$setAbsen->ip_server = $request->absenEditIPServer;

		$setAbsen->save();

	}

	public function deleteAbsen(Request $request){
		TicketingAbsen::where('id','=',$request->idAbsen)->first()->delete();
	}

	public function getAllSwitchSetting(){
		return array('data' => TicketingSwitch::get());
	}

	public function newSwitch(Request $request){
		$newSwitch = new TicketingSwitch();

		$newSwitch->type = $request->switchAddType;
		$newSwitch->port = $request->switchAddPort;
		$newSwitch->serial_number = $request->switchAddSerialNumber;
		$newSwitch->ip_management = $request->switchAddIPManagement;
		$newSwitch->location = $request->switchAddLocation;
		$newSwitch->cabang = $request->switchAddCabang;
		$newSwitch->note = $request->switchAddNote;

        $newSwitch->save();
	}

	public function getDetailSwitch(Request $request){
		return array(
			'switch' => TicketingSwitch::where('id',$request->id_switch)->first()
		);
	}

	public function setSwitch(Request $request){
		$setSwitch = TicketingSwitch::where('id','=',$request->idSwitch)->first();

		$setSwitch->type = $request->switchEditType;
		$setSwitch->port = $request->switchEditPort;
		$setSwitch->serial_number = $request->switchEditSerialNumber;;
		$setSwitch->ip_management = $request->switchEditIPManagement;;
		$setSwitch->location = $request->switchEditLocation;;
		$setSwitch->cabang = $request->switchEditCabang;;
		$setSwitch->note = $request->switchEditNote;

		$setSwitch->save();

	}

	public function deleteSwitch(Request $request){
		TicketingSwitch::where('id','=',$request->idSwitch)->first()->delete();
	}

	public function getReportParameter(){
		return array(
			'client_data' => TicketingClient::select('id','client_acronym','client_name')
				->where('situation','=','1')
				->get(),
			'ticket_year' => DB::table('ticketing__detail')
				->selectRaw("SUBSTRING_INDEX(`id_ticket`, '/', -1) AS `year`")
				->orderBy('year','DESC')
				->groupBy('year')
				->get()
			);
	}

	public function makeReportTicket(Request $req){
		// Create new Spreadsheet object
		$spreadsheet = new Spreadsheet();
		$client = TicketingClient::find($req->client)->client_acronym;
		if (isset($req->month)) {
			$bulan = Carbon::createFromDate($req->year, $req->month + 1, 1)->format('M');
		} else {
			$bulan = '';
		}
		
		// return $client . "/" . $bulan . "/" . $req->year;
		// return $bulan . "/" . $req->year;
		// $value1 = $this->getPerformance5($client,$bulan . "/" . $req->year);
		// return $value1;

		// Set document properties
		$title = 'Laporan Bulanan '. $client . ' '. $bulan . " " . $req->year;

		$spreadsheet->getProperties()->setCreator('SIP')
			->setLastModifiedBy('SIMS-APP')
			->setTitle($title);

		// Rename worksheet
		$spreadsheet->getActiveSheet()->setTitle('General');

		// Report Title
		$spreadsheet->getActiveSheet()->getRowDimension('2')->setRowHeight(35);
		$spreadsheet->getActiveSheet()->setCellValue('J2', 'LAPORAN REPORT ' . $client);
		$spreadsheet->getActiveSheet()->getStyle('J2')->getFont()->setName('Calibri');
		$spreadsheet->getActiveSheet()->getStyle('J2')->getFont()->setSize(24);
		$spreadsheet->getActiveSheet()->getStyle('J2')->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->getStyle('J2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$spreadsheet->getActiveSheet()->getStyle('J2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

		// Report Month
		$spreadsheet->getActiveSheet()->getRowDimension('2')->setRowHeight(35);
		$spreadsheet->getActiveSheet()->setCellValue('B2', Carbon::createFromDate(2018, $req->month + 1, 1)->format('F'));
		$spreadsheet->getActiveSheet()->getStyle('B2')->getFont()->setName('Calibri');
		$spreadsheet->getActiveSheet()->getStyle('B2')->getFont()->setSize(24);
		$spreadsheet->getActiveSheet()->getStyle('B2')->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$spreadsheet->getActiveSheet()->getStyle('B2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

		$Colom_Header = [
			'borders' => [
				'allBorders' => [
					'borderStyle' => Border::BORDER_THIN,
					'color' => ['argb' => 'FF000000'],
				],
			],
			'font' => [
				'name' => 'Calibri',
				'bold' => false,
				'size' => 11,
			],
			'alignment' => [
				'horizontal' => Alignment::HORIZONTAL_CENTER,
				'vertical' => Alignment::VERTICAL_CENTER,
			],
			'fill' => [
				'fillType' => Fill::FILL_SOLID,
				'color' => ['argb' => 'FF00B0F0'],
			],
		];

		$border = [
			'borders' => [
				'allBorders' => [
					'borderStyle' => Border::BORDER_THIN,
					'color' => ['argb' => 'FF000000'],
				],
			],
		];

		$cancel_row = [
			'fill' => [
				'fillType' => Fill::FILL_SOLID,
				'color' => ['argb' => 'FFFF0000'],
			],
		];

		$spreadsheet->getActiveSheet()->getStyle('A4:Q4')->applyFromArray($Colom_Header);
		$spreadsheet->getActiveSheet()->getRowDimension('4')->setRowHeight(25);
		
		$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(7);
		$spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(10);
		$spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(40);
		$spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(15);
		$spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(30);
		$spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(60);
		$spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(15);
		$spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(15);
		$spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(15);
		$spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(15);
		$spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(15);
		$spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(20);
		$spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(20);
		$spreadsheet->getActiveSheet()->getColumnDimension('O')->setWidth(80);
		$spreadsheet->getActiveSheet()->getColumnDimension('P')->setWidth(100);
		$spreadsheet->getActiveSheet()->getColumnDimension('Q')->setWidth(20);
		$spreadsheet->getActiveSheet()->getColumnDimension('R')->setWidth(20);

		if($client == "BTNI"){
			// Colom Header
			$spreadsheet->getActiveSheet(0)
				->setCellValue('A4','NO')
				->setCellValue('B4','ID tiket SIP')
				->setCellValue('C4','LOKASI')
				->setCellValue('D4','TYPE MACHINE')
				->setCellValue('E4','IP MACHINE')
				->setCellValue('F4','IP SERVER')
				->setCellValue('G4','PROBLEM')
				->setCellValue('H4','JAM OPEN')
				->setCellValue('I4','TGL. OPEN TIKET')
				->setCellValue('J4','TGL. SELESAI')
				->setCellValue('K4','SELESAI')
				->setCellValue('L4','PIC')
				->setCellValue('M4','NO TLP')
				->setCellValue('N4','ROOTCOSE')
				->setCellValue('O4','CONTERMASURE')
				->setCellValue('P4','ENGINEER')
				->setCellValue('Q4','OPEN BY');
		} else {
			$spreadsheet->getActiveSheet(0)
				->setCellValue('A4','NO')
				->setCellValue('B4','ID tiket SIP')
				->setCellValue('C4','ID ATM')
				->setCellValue('D4','LOKASI ')
				->setCellValue('E4','SN ATM')
				->setCellValue('F4','NUMBER TIKET')
				->setCellValue('G4','PROBLEM')
				->setCellValue('H4','TIKET WINCOR')
				->setCellValue('I4','JAM OPEN')
				->setCellValue('J4','TGL. OPEN TIKET')
				->setCellValue('K4','TGL. SELESAI')
				->setCellValue('L4','SELESAI')
				->setCellValue('M4','PIC')
				->setCellValue('N4','NO TLP')
				->setCellValue('O4','ROOTCOSE')
				->setCellValue('P4','CONTERMASURE')
				->setCellValue('Q4','ENGINEER')
				->setCellValue('R4','OPEN BY');
		}
		
		// $value1 = $this->getPerformanceByFinishTicket($client,$bulan . "/" . $req->year);

		if($client == 'BJBR' && $req->month > 6 && $req->year >= 2024){
			$client = 'BBJB';
		} else if($client == 'BJBR' && $req->year >= 2025){
            $client = 'BBJB';
        } else {
			$client = $client;
		}

		if (isset($req->month)) {
			$value1 = $this->getPerformanceByFinishTicket($client,$bulan . "/" . $req->year,$req->type);
		} else {
			$value1 = $this->getPerformanceByFinishTicket($client,$req->year,$req->type);
		}
		// return $value1;

		if($client == "BTNI"){
			foreach ($value1 as $key => $value) {
				$spreadsheet->getActiveSheet()->getStyle('A' . (5 + $key))->applyFromArray($Colom_Header);
				$spreadsheet->getActiveSheet()->getStyle('B' . (5 + $key) .  ':R' . (5 + $key))->applyFromArray($border);
				$spreadsheet->getActiveSheet()->setCellValue('A' . (5 + $key),$key + 1);
				$spreadsheet->getActiveSheet()->setCellValue('B' . (5 + $key),$value->id_ticket);
				$spreadsheet->getActiveSheet()->setCellValue('C' . (5 + $key),$value->location);
				if(isset($value->absen_machine)){
					$spreadsheet->getActiveSheet()->setCellValue('D' . (5 + $key),$value->absen_machine->type_machine);
					$spreadsheet->getActiveSheet()->setCellValue('E' . (5 + $key),$value->absen_machine->ip_machine);
					$spreadsheet->getActiveSheet()->setCellValue('F' . (5 + $key),$value->absen_machine->ip_server);
				}
				$spreadsheet->getActiveSheet()->setCellValue('G' . (5 + $key),$value->problem);
				$spreadsheet->getActiveSheet()->setCellValue('H' . (5 + $key),$value->ticket_number_3party);
				if($value->open == NULL){
					// $spreadsheet->getActiveSheet()->setCellValue('I' . (5 + $key),"NULL");
					// $spreadsheet->getActiveSheet()->setCellValue('J' . (5 + $key),"NULL");
					if($value->reporting_time != "Invalid date"){
						$spreadsheet->getActiveSheet()->setCellValue('I' . (5 + $key),date_format(date_create($value->reporting_time),'G:i:s'));
						$spreadsheet->getActiveSheet()->setCellValue('J' . (5 + $key),date_format(date_create($value->reporting_time),'d F Y'));
					}
				} else {
					$spreadsheet->getActiveSheet()->setCellValue('I' . (5 + $key),date_format(date_create($value->open),'G:i:s'));
					$spreadsheet->getActiveSheet()->setCellValue('J' . (5 + $key),date_format(date_create($value->open),'d F Y'));
				}
				if($value->lastest_activity_ticket->activity == "CANCEL"){
					$spreadsheet->getActiveSheet()->setCellValue('K' . (5 + $key),'-');
					$spreadsheet->getActiveSheet()->setCellValue('L' . (5 + $key),'-');
					$spreadsheet->getActiveSheet()->getStyle('B' . (5 + $key) .  ':Q' . (5 + $key))->applyFromArray($cancel_row);
				} else {
					$spreadsheet->getActiveSheet()->setCellValue('K' . (5 + $key),date_format(date_create($value->lastest_activity_ticket->date),'G:i:s'));
					$spreadsheet->getActiveSheet()->setCellValue('L' . (5 + $key),date_format(date_create($value->lastest_activity_ticket->date),'d F Y'));
					if(isset($value->resolve)){
						$spreadsheet->getActiveSheet()->setCellValue('O' . (5 + $key),$value->resolve->root_couse);
						$spreadsheet->getActiveSheet()->setCellValue('P' . (5 + $key),$value->resolve->counter_measure);
					}
				}
				$spreadsheet->getActiveSheet()->setCellValue('M' . (5 + $key),$value->pic);
				$spreadsheet->getActiveSheet()->setCellValue('N' . (5 + $key),$value->contact_pic);
				$spreadsheet->getActiveSheet()->setCellValue('Q' . (5 + $key),$value->engineer);
				$spreadsheet->getActiveSheet()->setCellValue('R' . (5 + $key),$value->first_activity_ticket->operator);
				// $spreadsheet->getActiveSheet()->setCellValue('R' . (5 + $key),$value->id_ticket);
			}
		} else {
			foreach ($value1 as $key => $value) {
				// echo $value->id_ticket . '\n';
				$spreadsheet->getActiveSheet()->getStyle('A' . (5 + $key))->applyFromArray($Colom_Header);
				$spreadsheet->getActiveSheet()->getStyle('B' . (5 + $key) .  ':R' . (5 + $key))->applyFromArray($border);
				$spreadsheet->getActiveSheet()->setCellValue('A' . (5 + $key),$key + 1);
				$spreadsheet->getActiveSheet()->setCellValue('B' . (5 + $key),$value->id_ticket);
				$spreadsheet->getActiveSheet()->setCellValue('C' . (5 + $key),$value->id_atm);
				$spreadsheet->getActiveSheet()->setCellValue('D' . (5 + $key),$value->location);
				$spreadsheet->getActiveSheet()->setCellValue('E' . (5 + $key),$value->serial_device);
				$spreadsheet->getActiveSheet()->setCellValue('F' . (5 + $key),$value->refrence);
				$spreadsheet->getActiveSheet()->setCellValue('G' . (5 + $key),$value->problem);
				$spreadsheet->getActiveSheet()->setCellValue('H' . (5 + $key),$value->ticket_number_3party);
				if($value->open == NULL){
					// $spreadsheet->getActiveSheet()->setCellValue('I' . (5 + $key),"NULL");
					// $spreadsheet->getActiveSheet()->setCellValue('J' . (5 + $key),"NULL");
					if($value->reporting_time != "Invalid date"){
						$spreadsheet->getActiveSheet()->setCellValue('I' . (5 + $key),date_format(date_create($value->reporting_time),'G:i:s'));
						$spreadsheet->getActiveSheet()->setCellValue('J' . (5 + $key),date_format(date_create($value->reporting_time),'d F Y'));
					}
				} else {
					$spreadsheet->getActiveSheet()->setCellValue('I' . (5 + $key),date_format(date_create($value->open),'G:i:s'));
					$spreadsheet->getActiveSheet()->setCellValue('J' . (5 + $key),date_format(date_create($value->open),'d F Y'));
				}
				if($value->lastest_activity_ticket->activity == "CANCEL"){
					$spreadsheet->getActiveSheet()->setCellValue('K' . (5 + $key),'-');
					$spreadsheet->getActiveSheet()->setCellValue('L' . (5 + $key),'-');
					$spreadsheet->getActiveSheet()->getStyle('B' . (5 + $key) .  ':Q' . (5 + $key))->applyFromArray($cancel_row);
				} else {
					$spreadsheet->getActiveSheet()->setCellValue('K' . (5 + $key),date_format(date_create($value->lastest_activity_ticket->date),'G:i:s'));
					$spreadsheet->getActiveSheet()->setCellValue('L' . (5 + $key),date_format(date_create($value->lastest_activity_ticket->date),'d F Y'));
					if(isset($value->resolve)){
						$spreadsheet->getActiveSheet()->setCellValue('O' . (5 + $key),$value->resolve->root_couse);
						$spreadsheet->getActiveSheet()->setCellValue('P' . (5 + $key),$value->resolve->counter_measure);
					}
				}
				$spreadsheet->getActiveSheet()->setCellValue('M' . (5 + $key),$value->pic);
				$spreadsheet->getActiveSheet()->setCellValue('N' . (5 + $key),$value->contact_pic);
				$spreadsheet->getActiveSheet()->setCellValue('Q' . (5 + $key),$value->engineer);
				$spreadsheet->getActiveSheet()->setCellValue('R' . (5 + $key),$value->first_activity_ticket->operator);
			}
		}

		$spreadsheet->createSheet(1)->setTitle('Summary');
		$spreadsheet->setActiveSheetIndex(1);

		$Colom_Header2 = [
			'borders' => [
				'allBorders' => [
					'borderStyle' => Border::BORDER_THIN,
					'color' => ['argb' => 'FF000000'],
				],
			],
			'font' => [
				'name' => 'Calibri',
				'bold' => TRUE,
				'size' => 11,
			],
			'alignment' => [
				'horizontal' => Alignment::HORIZONTAL_CENTER,
				'vertical' => Alignment::VERTICAL_CENTER,
			]
		];

		$spreadsheet->getActiveSheet()
			->setCellValue('B5','No')
			->setCellValue('C5','ID ATM')
			->setCellValue('D5','LOKASI ATM')
			->setCellValue('E5','PERIODE BULAN')
			->setCellValue('I5','TOTAL DATA CORECTIVE (JAM) ')
			->setCellValue('J5','JUMLAH OPERASIONAL')
			->setCellValue('K5','SLA')

			->setCellValue('E6','AWAL')
			->setCellValue('F6','PERIODE PROBLEM')
			->setCellValue('H6','AKHIR')
			;

		$spreadsheet->getActiveSheet()->getStyle("I5")->getAlignment()->setWrapText(true);
		$spreadsheet->getActiveSheet()->getStyle("J5")->getAlignment()->setWrapText(true);

		if($client == 'BJBR' && $req->month > 6 && $req->year >= 2024){
			$client = 'BBJB';
		} else if($client == 'BJBR' && $req->year >= 2025){
		    $client = 'BBJB';
        } else {
			$client = $client;
		}

		$value1 = $this->getPerformance5($client,$bulan . "/" . $req->year,$req->type);
		// return $value1;
		if($value1 == 0){
			return 0;
		} else {
			$middle = [
				'alignment' => [
					'horizontal' => Alignment::HORIZONTAL_LEFT,
					'vertical' => Alignment::VERTICAL_CENTER,
				]
			];

			$index = 0;

			$atm_id = "";
			$repeat = 0;
			foreach ($value1 as $key => $value) {
				if($value->last_status[0] == "CLOSE"){
					$spreadsheet->getActiveSheet()->getStyle('B' . (7 + $index))->getFill()->setFillType(Fill::FILL_SOLID);
					$spreadsheet->getActiveSheet()->getStyle('B' . (7 + $index))->getFill()->getStartColor()->setARGB('FF2E75B6');
					$spreadsheet->getActiveSheet()->getStyle('B' . (7 + $index) .  ':K' . (7 + $index))->applyFromArray($border);
					$spreadsheet->getActiveSheet()->setCellValue('B' . (7 + $index),$index + 1);
					$spreadsheet->getActiveSheet()->setCellValue('D' . (7 + $index),$value->location);
					$spreadsheet->getActiveSheet()->setCellValue('E' . (7 + $index),date_format(date_create($bulan),"01/m/Y"));
					$spreadsheet->getActiveSheet()->setCellValue('F' . (7 + $index),date_format(date_create($value->open),'d/m/Y h:i A'));
					$spreadsheet->getActiveSheet()->setCellValue('G' . (7 + $index),date_format(date_create($value->last_status[1]),'d/m/Y h:i A'));
					$spreadsheet->getActiveSheet()->setCellValue('H' . (7 + $index),date_format(date_create($bulan),"t/m/Y"));
					
					$close_ticket_time = (int)strtotime($value->last_status[1]);
					$open_ticket_time = (int)strtotime($value->open);
					if ($close_ticket_time > $open_ticket_time){
						if($open_ticket_time == NULL){
							$operasional = round(($close_ticket_time - (int)strtotime($value->reporting_time))/3600,2);
						} else {
							$operasional = round(($close_ticket_time - $open_ticket_time)/3600,2);
						}
					} else {
						if($open_ticket_time == NULL){
							$operasional = round(((int)strtotime($value->reporting_time) - $close_ticket_time)/3600,2);
						} else {
							$operasional = round(($open_ticket_time - $close_ticket_time)/3600,2);
						}
					}

					$spreadsheet->getActiveSheet()->setCellValue('I' . (7 + $index),$operasional);
					$spreadsheet->getActiveSheet()->setCellValue('J' . (7 + $index),(int)date_format(date_create($bulan),"t") * 24);
					$sla_result = 100 - round(($operasional / ((int)date_format(date_create($bulan),"t") * 24)) * 100 , 2);
					$spreadsheet->getActiveSheet()->setCellValue('K' . (7 + $index),($sla_result < 0 ? 0 : $sla_result));
					
					if($client != "TTNI"){
						$spreadsheet->getActiveSheet()->setCellValue('C' . (7 + $index),$value->id_atm);
						$spreadsheet->getActiveSheet()->setCellValue('C' . (7 + $index),$value->id_atm);
						if($atm_id == $value->id_atm){
							$atm_id = $atm_id;
							$repeat++;
						} else {
							// $spreadsheet->getActiveSheet()->getStyle('C' . (7 + $index))->getFill()->setFillType(Fill::FILL_SOLID);
							// $spreadsheet->getActiveSheet()->getStyle('C' . (7 + $index))->getFill()->getStartColor()->setARGB('FFFF0000');
							if($repeat != 0){
								// $spreadsheet->getActiveSheet()->getStyle('C' . ((6 + $index) - $repeat))->getFill()->setFillType(Fill::FILL_SOLID);
								// $spreadsheet->getActiveSheet()->getStyle('C' . ((6 + $index) - $repeat))->getFill()->getStartColor()->setARGB('FF00FF00');
								$spreadsheet->getActiveSheet()->mergeCells('C' . ((6 + $index) - $repeat) . ':C' . (((6 + $index) - $repeat) + $repeat));
								$spreadsheet->getActiveSheet()->mergeCells('D' . ((6 + $index) - $repeat) . ':D' . (((6 + $index) - $repeat) + $repeat));
								$spreadsheet->getActiveSheet()->getStyle('C' . ((6 + $index) - $repeat) . ':C' . (((6 + $index) - $repeat) + $repeat))->applyFromArray($middle);
								$spreadsheet->getActiveSheet()->getStyle('D' . ((6 + $index) - $repeat) . ':D' . (((6 + $index) - $repeat) + $repeat))->applyFromArray($middle);
							}

							$repeat = 0;
							$atm_id = $value->id_atm;
						}
					} else {
						$spreadsheet->getActiveSheet()->setCellValue('C' . (7 + $index),'-');
						$spreadsheet->getActiveSheet()->setCellValue('C' . (7 + $index),'-');
					}

					$index++;
				}
			}

			$bold = [
				'font' => [
					'name' => 'Calibri',
					'bold' => TRUE,
					'size' => 11,
				]
			];

			$spreadsheet->getActiveSheet()->getStyle('J' . (7 + $index) .  ':K' . (7 + $index))->applyFromArray($border);
			$spreadsheet->getActiveSheet()->getStyle('J' . (7 + $index) .  ':K' . (7 + $index))->applyFromArray($bold);
			$spreadsheet->getActiveSheet()->setCellValue('J' . (7 + $index),"TOTAL");
			$spreadsheet->getActiveSheet()->setCellValue('K' . (7 + $index),"=ROUND(AVERAGE(K7:K" . (6 + $index) . "),3)");

			$spreadsheet->getActiveSheet()->getStyle('E5')->getFill()->setFillType(Fill::FILL_SOLID);
			$spreadsheet->getActiveSheet()->getStyle('E5')->getFill()->getStartColor()->setARGB('FF2E75B6');

			$spreadsheet->getActiveSheet()->getStyle('E6')->getFill()->setFillType(Fill::FILL_SOLID);
			$spreadsheet->getActiveSheet()->getStyle('E6')->getFill()->getStartColor()->setARGB('FFFF0000');

			$spreadsheet->getActiveSheet()->getStyle('F6')->getFill()->setFillType(Fill::FILL_SOLID);
			$spreadsheet->getActiveSheet()->getStyle('F6')->getFill()->getStartColor()->setARGB('FFFFFF00');

			$spreadsheet->getActiveSheet()->getStyle('H6')->getFill()->setFillType(Fill::FILL_SOLID);
			$spreadsheet->getActiveSheet()->getStyle('H6')->getFill()->getStartColor()->setARGB('FF00B050');

			$spreadsheet->getActiveSheet()->getStyle('B5:K6')->applyFromArray($Colom_Header2);

			$spreadsheet->getActiveSheet()->mergeCells('B5:B6');
			$spreadsheet->getActiveSheet()->mergeCells('C5:C6');
			$spreadsheet->getActiveSheet()->mergeCells('D5:D6');
			$spreadsheet->getActiveSheet()->mergeCells('E5:H5');
			$spreadsheet->getActiveSheet()->mergeCells('I5:I6');
			$spreadsheet->getActiveSheet()->mergeCells('J5:J6');
			$spreadsheet->getActiveSheet()->mergeCells('K5:K6');
			$spreadsheet->getActiveSheet()->mergeCells('F6:G6');

			$spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(5);
			$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(10);
			$spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(40);
			$spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(15);
			$spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(20);
			$spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(20);
			$spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(15);
			$spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(20);
			$spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(15);
			$spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(15);

			$spreadsheet->getActiveSheet()->getRowDimension('5')->setRowHeight(20);
			$spreadsheet->getActiveSheet()->getRowDimension('6')->setRowHeight(20);

			$spreadsheet->setActiveSheetIndex(1);
			$spreadsheet->getActiveSheet()->getSheetView()->setZoomScale(98);

			$name = 'Report_' . $client . '_-_' . Carbon::createFromDate( $req->year , $req->month + 1, 1)->format('F-Y') . '_(' . date("Y-m-d") . ')_' . Auth::user()->name . '.xlsx';
			$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
			$location = public_path() . '/report/' . $name;
			ob_end_clean();
			$writer->save($location);
			return $name;
		}
	}

	public function getPerformanceByFinishTicket($acronym_client,$period,$type){
		$occurring_ticket = DB::table('ticketing__activity')
			->select('id_ticket','activity')
			->whereIn('id',function ($query) {
				$query->select(DB::raw("MAX(id) AS activity"))
					->from('ticketing__activity')
					->groupBy('id_ticket');
				})
			// ->where('activity','<>','CANCEL')
			// ->where('activity','<>','CLOSE')
			->whereRaw('`id_ticket` LIKE "%' . $acronym_client . '%"')
			->get()
			->pluck('id_ticket');

		if (preg_match("(/)", $period)) {
			$residual_ticket_result = TicketingDetail::with([
				'first_activity_ticket:id_ticket,date,operator',
				'lastest_activity_ticket',
				'id_detail:id_ticket,id',
				'resolve',
				'absen_machine'
			])
			// ->whereNotIn('id_ticket',$occurring_ticket)
			->whereIn('id_ticket',$occurring_ticket)
			->where('type_ticket',$type)
			->whereRaw("`id_ticket` LIKE '%/" . $acronym_client . "/" . $period . "'")
			->orderBy('id','ASC')
			->get();
		} else {
			$residual_ticket_result = TicketingDetail::with([
				'first_activity_ticket:id_ticket,date,operator',
				'lastest_activity_ticket',
				'id_detail:id_ticket,id',
				'resolve',
				'absen_machine'
			])
			// ->whereNotIn('id_ticket',$occurring_ticket)
			->whereIn('id_ticket',$occurring_ticket)
			->where('type_ticket',$type)
			// ->whereRaw("`id_ticket` LIKE '%/" . $acronym_client . "/%'")
			->where('id_ticket', 'like', '%' . $acronym_client . '%')
			->where('id_ticket', 'like', '%' . $period . '%')
			->orderBy('id','ASC')
			->get();
		}

		return $residual_ticket_result;
	}

	public function makeReportTicketPID(Request $req){
		// Create new Spreadsheet object
		$spreadsheet = new Spreadsheet();
		$client = TicketingClient::find($req->client)->client_acronym;
		if (isset($req->month)) {
			$bulan = Carbon::createFromDate($req->year, $req->month + 1, 1)->format('M');
		} else {
			$bulan = '';
		}
		

		// return $client . "/" . $bulan . "/" . $req->year;
		// return $bulan . "/" . $req->year;
		// $value1 = $this->getPerformance5($client,$bulan . "/" . $req->year);
		// return $value1;

		// Set document properties
		$title = 'Laporan Bulanan '. $client . ' '. $bulan . " " . $req->year;

		$spreadsheet->getProperties()->setCreator('SIP')
			->setLastModifiedBy('Rama Agastya')
			->setTitle($title);

		// Rename worksheet
		$spreadsheet->getActiveSheet()->setTitle('General');

		// Report Title
		$spreadsheet->getActiveSheet()->getRowDimension('2')->setRowHeight(35);
		$spreadsheet->getActiveSheet()->setCellValue('J2', 'LAPORAN REPORT ' . $client);
		$spreadsheet->getActiveSheet()->getStyle('J2')->getFont()->setName('Calibri');
		$spreadsheet->getActiveSheet()->getStyle('J2')->getFont()->setSize(24);
		$spreadsheet->getActiveSheet()->getStyle('J2')->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->getStyle('J2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$spreadsheet->getActiveSheet()->getStyle('J2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

		// Report Month
		$spreadsheet->getActiveSheet()->getRowDimension('2')->setRowHeight(35);
		$spreadsheet->getActiveSheet()->setCellValue('B2', Carbon::createFromDate(2018, $req->month + 1, 1)->format('F'));
		$spreadsheet->getActiveSheet()->getStyle('B2')->getFont()->setName('Calibri');
		$spreadsheet->getActiveSheet()->getStyle('B2')->getFont()->setSize(24);
		$spreadsheet->getActiveSheet()->getStyle('B2')->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$spreadsheet->getActiveSheet()->getStyle('B2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

		$Colom_Header = [
			'borders' => [
				'allBorders' => [
					'borderStyle' => Border::BORDER_THIN,
					'color' => ['argb' => 'FF000000'],
				],
			],
			'font' => [
				'name' => 'Calibri',
				'bold' => false,
				'size' => 11,
			],
			'alignment' => [
				'horizontal' => Alignment::HORIZONTAL_CENTER,
				'vertical' => Alignment::VERTICAL_CENTER,
			],
			'fill' => [
				'fillType' => Fill::FILL_SOLID,
				'color' => ['argb' => 'FF00B0F0'],
			],
		];

		$border = [
			'borders' => [
				'allBorders' => [
					'borderStyle' => Border::BORDER_THIN,
					'color' => ['argb' => 'FF000000'],
				],
			],
		];

		$cancel_row = [
			'fill' => [
				'fillType' => Fill::FILL_SOLID,
				'color' => ['argb' => 'FFFF0000'],
			],
		];

		$spreadsheet->getActiveSheet()->getStyle('A4:S4')->applyFromArray($Colom_Header);
		$spreadsheet->getActiveSheet()->getRowDimension('4')->setRowHeight(25);
		
		$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(7);
		$spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(40);
		$spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(10);
		$spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(40);
		$spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(15);
		$spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(30);
		$spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(60);
		$spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(15);
		$spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(15);
		$spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(15);
		$spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(15);
		$spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(15);
		$spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(20);
		$spreadsheet->getActiveSheet()->getColumnDimension('O')->setWidth(20);
		$spreadsheet->getActiveSheet()->getColumnDimension('P')->setWidth(80);
		$spreadsheet->getActiveSheet()->getColumnDimension('Q')->setWidth(100);
		$spreadsheet->getActiveSheet()->getColumnDimension('R')->setWidth(20);
		$spreadsheet->getActiveSheet()->getColumnDimension('S')->setWidth(20);

		if($client == "BTNI"){
			// Colom Header
			$spreadsheet->getActiveSheet(0)
				->setCellValue('A4','NO')
				->setCellValue('B4','ID tiket SIP')
				->setCellValue('C4','ID Project')
				->setCellValue('D4','LOKASI')
				->setCellValue('E4','TYPE MACHINE')
				->setCellValue('F4','IP MACHINE')
				->setCellValue('G4','IP SERVER')
				->setCellValue('H4','PROBLEM')
				->setCellValue('I4','JAM OPEN')
				->setCellValue('J4','TGL. OPEN TIKET')
				->setCellValue('K4','TGL. SELESAI')
				->setCellValue('L4','SELESAI')
				->setCellValue('M4','PIC')
				->setCellValue('N4','NO TLP')
				->setCellValue('O4','ROOTCOSE')
				->setCellValue('P4','CONTERMASURE')
				->setCellValue('Q4','ENGINEER')
				->setCellValue('R4','OPEN BY');
		} else {
			$spreadsheet->getActiveSheet(0)
				->setCellValue('A4','NO')
				->setCellValue('B4','ID tiket SIP')
				->setCellValue('C4','ID Project')
				->setCellValue('D4','ID ATM')
				->setCellValue('E4','LOKASI ')
				->setCellValue('F4','SN ATM')
				->setCellValue('G4','NUMBER TIKET')
				->setCellValue('H4','PROBLEM')
				->setCellValue('I4','TIKET WINCOR')
				->setCellValue('J4','JAM OPEN')
				->setCellValue('K4','TGL. OPEN TIKET')
				->setCellValue('L4','TGL. SELESAI')
				->setCellValue('M4','SELESAI')
				->setCellValue('N4','PIC')
				->setCellValue('O4','NO TLP')
				->setCellValue('P4','ROOTCOSE')
				->setCellValue('Q4','CONTERMASURE')
				->setCellValue('R4','ENGINEER')
				->setCellValue('S4','OPEN BY');
		}
		
		// $value1 = $this->getPerformanceByFinishTicket($client,$bulan . "/" . $req->year);

		if($client == 'BJBR' && $bulan >= 6 && $req->year >= 2024){
			$client = 'BBJB';
		} else {
			$client = $client;
		}

		if (isset($req->month)) {
			$value1 = $this->getPerformanceByFinishTicketPID($client,$bulan . "/" . $req->year,$req->pid,$req->type);
		} else {
			$value1 = $this->getPerformanceByFinishTicketPID($client,$req->year,$req->pid,$req->type);
		}
		// return $value1;

		if($client == "BTNI"){
			foreach ($value1 as $key => $value) {
				$spreadsheet->getActiveSheet()->getStyle('A' . (5 + $key))->applyFromArray($Colom_Header);
				$spreadsheet->getActiveSheet()->getStyle('B' . (5 + $key) .  ':R' . (5 + $key))->applyFromArray($border);
				$spreadsheet->getActiveSheet()->setCellValue('A' . (5 + $key),$key + 1);
				$spreadsheet->getActiveSheet()->setCellValue('B' . (5 + $key),$value->id_ticket);
				$spreadsheet->getActiveSheet()->setCellValue('C' . (5 + $key),$value->pid);
				$spreadsheet->getActiveSheet()->setCellValue('D' . (5 + $key),$value->location);
				if(isset($value->absen_machine)){
					$spreadsheet->getActiveSheet()->setCellValue('E' . (5 + $key),$value->absen_machine->type_machine);
					$spreadsheet->getActiveSheet()->setCellValue('F' . (5 + $key),$value->absen_machine->ip_machine);
					$spreadsheet->getActiveSheet()->setCellValue('G' . (5 + $key),$value->absen_machine->ip_server);
				}
				$spreadsheet->getActiveSheet()->setCellValue('H' . (5 + $key),$value->problem);
				$spreadsheet->getActiveSheet()->setCellValue('I' . (5 + $key),$value->ticket_number_3party);
				if($value->open == NULL){
					// $spreadsheet->getActiveSheet()->setCellValue('I' . (5 + $key),"NULL");
					// $spreadsheet->getActiveSheet()->setCellValue('J' . (5 + $key),"NULL");
					if($value->reporting_time != "Invalid date"){
						$spreadsheet->getActiveSheet()->setCellValue('J' . (5 + $key),date_format(date_create($value->reporting_time),'G:i:s'));
						$spreadsheet->getActiveSheet()->setCellValue('K' . (5 + $key),date_format(date_create($value->reporting_time),'d F Y'));
					}
				} else {
					$spreadsheet->getActiveSheet()->setCellValue('J' . (5 + $key),date_format(date_create($value->open),'G:i:s'));
					$spreadsheet->getActiveSheet()->setCellValue('K' . (5 + $key),date_format(date_create($value->open),'d F Y'));
				}
				if($value->lastest_activity_ticket->activity == "CANCEL"){
					$spreadsheet->getActiveSheet()->setCellValue('K' . (5 + $key),'-');
					$spreadsheet->getActiveSheet()->setCellValue('L' . (5 + $key),'-');
					$spreadsheet->getActiveSheet()->getStyle('B' . (5 + $key) .  ':Q' . (5 + $key))->applyFromArray($cancel_row);
				} else {
					$spreadsheet->getActiveSheet()->setCellValue('L' . (5 + $key),date_format(date_create($value->lastest_activity_ticket->date),'G:i:s'));
					$spreadsheet->getActiveSheet()->setCellValue('L' . (5 + $key),date_format(date_create($value->lastest_activity_ticket->date),'d F Y'));
					if(isset($value->resolve)){
						$spreadsheet->getActiveSheet()->setCellValue('O' . (5 + $key),$value->resolve->root_couse);
						$spreadsheet->getActiveSheet()->setCellValue('P' . (5 + $key),$value->resolve->counter_measure);
					}
				}
				$spreadsheet->getActiveSheet()->setCellValue('M' . (5 + $key),$value->pic);
				$spreadsheet->getActiveSheet()->setCellValue('N' . (5 + $key),$value->contact_pic);
				$spreadsheet->getActiveSheet()->setCellValue('Q' . (5 + $key),$value->engineer);
				$spreadsheet->getActiveSheet()->setCellValue('R' . (5 + $key),$value->first_activity_ticket->operator);
				// $spreadsheet->getActiveSheet()->setCellValue('R' . (5 + $key),$value->id_ticket);
			}
		} else {
			foreach ($value1 as $key => $value) {
				$spreadsheet->getActiveSheet()->getStyle('A' . (5 + $key))->applyFromArray($Colom_Header);
				$spreadsheet->getActiveSheet()->getStyle('B' . (5 + $key) .  ':R' . (5 + $key))->applyFromArray($border);
				$spreadsheet->getActiveSheet()->setCellValue('A' . (5 + $key),$key + 1);
				$spreadsheet->getActiveSheet()->setCellValue('B' . (5 + $key),$value->id_ticket);
				$spreadsheet->getActiveSheet()->setCellValue('C' . (5 + $key),$value->pid);
				$spreadsheet->getActiveSheet()->setCellValue('D' . (5 + $key),$value->id_atm);
				$spreadsheet->getActiveSheet()->setCellValue('E' . (5 + $key),$value->location);
				$spreadsheet->getActiveSheet()->setCellValue('F' . (5 + $key),$value->serial_device);
				$spreadsheet->getActiveSheet()->setCellValue('G' . (5 + $key),$value->refrence);
				$spreadsheet->getActiveSheet()->setCellValue('H' . (5 + $key),$value->problem);
				$spreadsheet->getActiveSheet()->setCellValue('I' . (5 + $key),$value->ticket_number_3party);
				if($value->open == NULL){
					// $spreadsheet->getActiveSheet()->setCellValue('I' . (5 + $key),"NULL");
					// $spreadsheet->getActiveSheet()->setCellValue('J' . (5 + $key),"NULL");
					if($value->reporting_time != "Invalid date"){
						$spreadsheet->getActiveSheet()->setCellValue('J' . (5 + $key),date_format(date_create($value->reporting_time),'G:i:s'));
						$spreadsheet->getActiveSheet()->setCellValue('K' . (5 + $key),date_format(date_create($value->reporting_time),'d F Y'));
					}
				} else {
					$spreadsheet->getActiveSheet()->setCellValue('J' . (5 + $key),date_format(date_create($value->open),'G:i:s'));
					$spreadsheet->getActiveSheet()->setCellValue('K' . (5 + $key),date_format(date_create($value->open),'d F Y'));
				}
				if($value->lastest_activity_ticket->activity == "CANCEL"){
					$spreadsheet->getActiveSheet()->setCellValue('L' . (5 + $key),'-');
					$spreadsheet->getActiveSheet()->setCellValue('M' . (5 + $key),'-');
					$spreadsheet->getActiveSheet()->getStyle('B' . (5 + $key) .  ':Q' . (5 + $key))->applyFromArray($cancel_row);
				} else {
					$spreadsheet->getActiveSheet()->setCellValue('L' . (5 + $key),date_format(date_create($value->lastest_activity_ticket->date),'G:i:s'));
					$spreadsheet->getActiveSheet()->setCellValue('M' . (5 + $key),date_format(date_create($value->lastest_activity_ticket->date),'d F Y'));
					if(isset($value->resolve)){
						$spreadsheet->getActiveSheet()->setCellValue('P' . (5 + $key),$value->resolve->root_couse);
						$spreadsheet->getActiveSheet()->setCellValue('Q' . (5 + $key),$value->resolve->counter_measure);
					}
				}
				$spreadsheet->getActiveSheet()->setCellValue('N' . (5 + $key),$value->pic);
				$spreadsheet->getActiveSheet()->setCellValue('O' . (5 + $key),$value->contact_pic);
				$spreadsheet->getActiveSheet()->setCellValue('R' . (5 + $key),$value->engineer);
				$spreadsheet->getActiveSheet()->setCellValue('S' . (5 + $key),$value->first_activity_ticket->operator);
			}
		}

		$spreadsheet->createSheet(1)->setTitle('Summary');
		$spreadsheet->setActiveSheetIndex(1);

		$Colom_Header2 = [
			'borders' => [
				'allBorders' => [
					'borderStyle' => Border::BORDER_THIN,
					'color' => ['argb' => 'FF000000'],
				],
			],
			'font' => [
				'name' => 'Calibri',
				'bold' => TRUE,
				'size' => 11,
			],
			'alignment' => [
				'horizontal' => Alignment::HORIZONTAL_CENTER,
				'vertical' => Alignment::VERTICAL_CENTER,
			]
		];

		$spreadsheet->getActiveSheet()
			->setCellValue('B5','No')
			->setCellValue('D5','ID ATM')
			->setCellValue('E5','LOKASI ATM')
			->setCellValue('F5','PERIODE BULAN')
			->setCellValue('J5','TOTAL DATA CORECTIVE (JAM) ')
			->setCellValue('K5','JUMLAH OPERASIONAL')
			->setCellValue('L5','SLA')

			->setCellValue('F6','AWAL')
			->setCellValue('G6','PERIODE PROBLEM')
			->setCellValue('I6','AKHIR')
			;

		$spreadsheet->getActiveSheet()->getStyle("I5")->getAlignment()->setWrapText(true);
		$spreadsheet->getActiveSheet()->getStyle("J5")->getAlignment()->setWrapText(true);

		$value1 = $this->getPerformance6($client,$bulan . "/" . $req->year,$req->pid,$req->type);
		// return $value1;
		if($value1 == 0){
			return 0;
		} else {
			$middle = [
				'alignment' => [
					'horizontal' => Alignment::HORIZONTAL_LEFT,
					'vertical' => Alignment::VERTICAL_CENTER,
				]
			];

			$index = 0;

			$atm_id = "";
			$repeat = 0;
			foreach ($value1 as $key => $value) {
				if($value->last_status[0] == "CLOSE"){
					// return 'disini';

					$spreadsheet->getActiveSheet()->getStyle('B' . (7 + $index))->getFill()->setFillType(Fill::FILL_SOLID);
					$spreadsheet->getActiveSheet()->getStyle('B' . (7 + $index))->getFill()->getStartColor()->setARGB('FF2E75B6');
					$spreadsheet->getActiveSheet()->getStyle('B' . (7 + $index) .  ':K' . (7 + $index))->applyFromArray($border);
					$spreadsheet->getActiveSheet()->setCellValue('B' . (7 + $index),$index + 1);
					$spreadsheet->getActiveSheet()->setCellValue('D' . (7 + $index),$value->location);
					$spreadsheet->getActiveSheet()->setCellValue('E' . (7 + $index),date_format(date_create($bulan),"01/m/Y"));
					$spreadsheet->getActiveSheet()->setCellValue('F' . (7 + $index),date_format(date_create($value->open),'d/m/Y h:i A'));
					$spreadsheet->getActiveSheet()->setCellValue('G' . (7 + $index),date_format(date_create($value->last_status[1]),'d/m/Y h:i A'));
					$spreadsheet->getActiveSheet()->setCellValue('H' . (7 + $index),date_format(date_create($bulan),"t/m/Y"));
					
					$close_ticket_time = (int)strtotime($value->last_status[1]);
					$open_ticket_time = (int)strtotime($value->open);
					if ($close_ticket_time > $open_ticket_time){
						if($open_ticket_time == NULL){
							$operasional = round(($close_ticket_time - (int)strtotime($value->reporting_time))/3600,2);
						} else {
							$operasional = round(($close_ticket_time - $open_ticket_time)/3600,2);
						}
					} else {
						if($open_ticket_time == NULL){
							$operasional = round(((int)strtotime($value->reporting_time) - $close_ticket_time)/3600,2);
						} else {
							$operasional = round(($open_ticket_time - $close_ticket_time)/3600,2);
						}
					}

					$spreadsheet->getActiveSheet()->setCellValue('I' . (7 + $index),$operasional);
					$spreadsheet->getActiveSheet()->setCellValue('J' . (7 + $index),(int)date_format(date_create($bulan),"t") * 24);
					$sla_result = 100 - round(($operasional / ((int)date_format(date_create($bulan),"t") * 24)) * 100 , 2);
					$spreadsheet->getActiveSheet()->setCellValue('K' . (7 + $index),($sla_result < 0 ? 0 : $sla_result));
					
					if($client != "TTNI"){
						$spreadsheet->getActiveSheet()->setCellValue('C' . (7 + $index),$value->id_atm);
						$spreadsheet->getActiveSheet()->setCellValue('C' . (7 + $index),$value->id_atm);
						if($atm_id == $value->id_atm){
							$atm_id = $atm_id;
							$repeat++;
						} else {
							// $spreadsheet->getActiveSheet()->getStyle('C' . (7 + $index))->getFill()->setFillType(Fill::FILL_SOLID);
							// $spreadsheet->getActiveSheet()->getStyle('C' . (7 + $index))->getFill()->getStartColor()->setARGB('FFFF0000');
							if($repeat != 0){
								// $spreadsheet->getActiveSheet()->getStyle('C' . ((6 + $index) - $repeat))->getFill()->setFillType(Fill::FILL_SOLID);
								// $spreadsheet->getActiveSheet()->getStyle('C' . ((6 + $index) - $repeat))->getFill()->getStartColor()->setARGB('FF00FF00');
								$spreadsheet->getActiveSheet()->mergeCells('C' . ((6 + $index) - $repeat) . ':C' . (((6 + $index) - $repeat) + $repeat));
								$spreadsheet->getActiveSheet()->mergeCells('D' . ((6 + $index) - $repeat) . ':D' . (((6 + $index) - $repeat) + $repeat));
								$spreadsheet->getActiveSheet()->getStyle('C' . ((6 + $index) - $repeat) . ':C' . (((6 + $index) - $repeat) + $repeat))->applyFromArray($middle);
								$spreadsheet->getActiveSheet()->getStyle('D' . ((6 + $index) - $repeat) . ':D' . (((6 + $index) - $repeat) + $repeat))->applyFromArray($middle);
							}

							$repeat = 0;
							$atm_id = $value->id_atm;
						}
					} else {
						$spreadsheet->getActiveSheet()->setCellValue('C' . (7 + $index),'-');
						$spreadsheet->getActiveSheet()->setCellValue('C' . (7 + $index),'-');
					}

					$index++;
				}
			}

			$bold = [
				'font' => [
					'name' => 'Calibri',
					'bold' => TRUE,
					'size' => 11,
				]
			];

			$spreadsheet->getActiveSheet()->getStyle('J' . (7 + $index) .  ':K' . (7 + $index))->applyFromArray($border);
			$spreadsheet->getActiveSheet()->getStyle('J' . (7 + $index) .  ':K' . (7 + $index))->applyFromArray($bold);
			$spreadsheet->getActiveSheet()->setCellValue('J' . (7 + $index),"TOTAL");
			$spreadsheet->getActiveSheet()->setCellValue('K' . (7 + $index),"=ROUND(AVERAGE(K7:K" . (6 + $index) . "),3)");

			$spreadsheet->getActiveSheet()->getStyle('E5')->getFill()->setFillType(Fill::FILL_SOLID);
			$spreadsheet->getActiveSheet()->getStyle('E5')->getFill()->getStartColor()->setARGB('FF2E75B6');

			$spreadsheet->getActiveSheet()->getStyle('E6')->getFill()->setFillType(Fill::FILL_SOLID);
			$spreadsheet->getActiveSheet()->getStyle('E6')->getFill()->getStartColor()->setARGB('FFFF0000');

			$spreadsheet->getActiveSheet()->getStyle('F6')->getFill()->setFillType(Fill::FILL_SOLID);
			$spreadsheet->getActiveSheet()->getStyle('F6')->getFill()->getStartColor()->setARGB('FFFFFF00');

			$spreadsheet->getActiveSheet()->getStyle('H6')->getFill()->setFillType(Fill::FILL_SOLID);
			$spreadsheet->getActiveSheet()->getStyle('H6')->getFill()->getStartColor()->setARGB('FF00B050');

			$spreadsheet->getActiveSheet()->getStyle('B5:K6')->applyFromArray($Colom_Header2);

			$spreadsheet->getActiveSheet()->mergeCells('B5:B6');
			$spreadsheet->getActiveSheet()->mergeCells('C5:C6');
			$spreadsheet->getActiveSheet()->mergeCells('D5:D6');
			$spreadsheet->getActiveSheet()->mergeCells('E5:H5');
			$spreadsheet->getActiveSheet()->mergeCells('I5:I6');
			$spreadsheet->getActiveSheet()->mergeCells('J5:J6');
			$spreadsheet->getActiveSheet()->mergeCells('K5:K6');
			$spreadsheet->getActiveSheet()->mergeCells('F6:G6');

			$spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(5);
			$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(10);
			$spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(40);
			$spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(15);
			$spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(20);
			$spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(20);
			$spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(15);
			$spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(20);
			$spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(15);
			$spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(15);

			$spreadsheet->getActiveSheet()->getRowDimension('5')->setRowHeight(20);
			$spreadsheet->getActiveSheet()->getRowDimension('6')->setRowHeight(20);

			$spreadsheet->setActiveSheetIndex(1);
			$spreadsheet->getActiveSheet()->getSheetView()->setZoomScale(98);

			$name = 'Report_' . $client . '_-_' . Carbon::createFromDate( $req->year , $req->month + 1, 1)->format('F-Y') . '_(' . date("Y-m-d") . ')_' . Auth::user()->name . '.xlsx';
			$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
			$location = public_path() . '/report/' . $name;
			ob_end_clean();
			$writer->save($location);
			return $name;
		}
	}

	public function getPerformanceByFinishTicketPID($acronym_client,$period,$pid,$type){
		// return $period;
		$occurring_ticket = DB::table('ticketing__activity')
			->select('id_ticket','activity')
			->whereIn('id',function ($query) {
				$query->select(DB::raw("MAX(id) AS activity"))
					->from('ticketing__activity')
					->groupBy('id_ticket');
				})
			// ->where('activity','<>','CANCEL')
			// ->where('activity','<>','CLOSE')
			->whereRaw('`id_ticket` LIKE "%' . $acronym_client . '%"')
			->get()
			->pluck('id_ticket');

		if (preg_match("(/)", $period)) {
			$residual_ticket_result = TicketingDetail::with([
				'first_activity_ticket:id_ticket,date,operator',
				'lastest_activity_ticket',
				'id_detail:id_ticket,id',
				'resolve',
				'absen_machine'
			])
			// ->whereNotIn('id_ticket',$occurring_ticket)
			->whereIn('id_ticket',$occurring_ticket)
			->where('ticketing__detail.pid',$pid)
			->where('ticketing__detail.type_ticket',$type)
			->whereRaw("`id_ticket` LIKE '%/" . $acronym_client . "/" . $period . "'")
			->orderBy('id','ASC')
			->get();
		} else {
			$residual_ticket_result = TicketingDetail::with([
				'first_activity_ticket:id_ticket,date,operator',
				'lastest_activity_ticket',
				'id_detail:id_ticket,id',
				'resolve',
				'absen_machine'
			])
			// ->whereNotIn('id_ticket',$occurring_ticket)
			->whereIn('id_ticket',$occurring_ticket)
			->where('ticketing__detail.pid',$pid)
			->where('ticketing__detail.type_ticket',$type)
			// ->whereRaw("`id_ticket` LIKE '%/" . $acronym_client . "/%'")
			->where('id_ticket', 'like', '%' . $acronym_client . '%')
			->where('id_ticket', 'like', '%' . $period . '%')
			->orderBy('id','ASC')
			->get();
		}

		

		return $residual_ticket_result;
	}

	public function getReportTicket(){
		$occurring_ticket = DB::table('ticketing__activity')
			->select('id_ticket','activity')
			->whereIn('id',function ($query) {
				$query->select(DB::raw("MAX(id) AS activity"))
					->from('ticketing__activity')
					->groupBy('id_ticket');
				})
			// ->where('activity','<>','CANCEL')
			->where('activity','<>','CLOSE')
			->whereRaw('`id_ticket` LIKE "%BTNI%"')
			->get()
			->pluck('id_ticket');

		// return $occurring_ticket;

		// return $occurring_ticket;

		// $result = TicketingDetail::whereHas('id_detail', function($query) use ($idTicket){
		// 		$query->where('id','=',$idTicket);
		// 	})
		// 	->with([
		// 		'lastest_activity_ticket:id_ticket,date,activity,operator',
		// 		'resolve',
		// 		'all_activity_ticket',
		// 		'first_activity_ticket',
		// 		'id_detail:id_ticket,id,id_client'
		// 	])
		// 	->first();

		$residual_ticket_result = TicketingDetail::with([
				'first_activity_ticket:id_ticket,date,operator',
				'lastest_activity_ticket',
				'id_detail:id_ticket,id',
				'resolve',
				'absen_machine'
			])
			// ->whereNotIn('id_ticket',$occurring_ticket)
			->whereIn('id_ticket',$occurring_ticket)
			->whereRaw("`id_ticket` LIKE '%/BTNI/NOV/2022'")
			->orderBy('id','ASC')
			->get();

		return $residual_ticket_result;
	}

	public function getPerformance5($acronym_client,$period,$type){
		if($acronym_client != "TTNI" && $acronym_client != "BTNI"){
			$result = DB::table('ticketing__id')
				->where('ticketing__detail.id_ticket','LIKE','%' . $period . '%')
				->where('ticketing__detail.id_ticket','LIKE','%' . $acronym_client . '%')
				->join('ticketing__detail','ticketing__detail.id_ticket','=','ticketing__id.id_ticket')
				->orderBy('ticketing__detail.id_atm','ASC');
		} else {
			$result = DB::table('ticketing__id')
				->where('ticketing__detail.id_ticket','LIKE','%' . $period . '%')
				->where('ticketing__detail.id_ticket','LIKE','%' . $acronym_client . '%')
				->join('ticketing__detail','ticketing__detail.id_ticket','=','ticketing__id.id_ticket')
				->orderBy('ticketing__detail.id_ticket','ASC');
		}

		if ($type != 'none') {
			$result = $result->where('type_ticket',$type);
		}

		$final = [];

		if($result->count() == 0){
			return 0;
		}else{
			$result = $result->get();
			$atm_before = $result[0]->id_atm; 

			foreach ($result as $key => $value) {

				$check = DB::table('ticketing__activity')
					->where('id_ticket','=',$value->id_ticket)
					->orderBy('id','DESC')
					->value('activity');

				$downtime = 0;

				if($check == "CLOSE" || $check == "CANCEL"){
					$value->open = DB::table('ticketing__activity')
						->where('id_ticket','=',$value->id_ticket)
						->where('activity','=','OPEN')
						->value('date');

					$value->id_open = DB::table('ticketing__id')
						->where('id_ticket','=',$value->id_ticket)
						->value('id');

					$value->last_status = array(
						$check,
						DB::table('ticketing__activity')
							->where('id_ticket','=',$value->id_ticket)
							->orderBy('id','DESC')
							->value('date')
						);

					if($value->id_atm == $atm_before){
						
					}

					if($check == "CLOSE"){
						$value->root_couse = DB::table('ticketing__resolve')
							->where('id_ticket','=',$value->id_ticket)
							->value('root_couse');

						$value->counter_measure = DB::table('ticketing__resolve')
							->where('id_ticket','=',$value->id_ticket)
							->value('counter_measure');

					} else {
						$value->root_couse = '-';
						$value->counter_measure = '-';
					}


					$value->operator = DB::table('ticketing__activity')
						->where('id_ticket','=',$value->id_ticket)
						->orderBy('id','DESC')
						->value('operator');

					$final[] = $value;

				}
			}
			return $final;
		}
	}

	public function getPerformance6($acronym_client,$period,$pid,$type){
		if($acronym_client != "TTNI" && $acronym_client != "BTNI"){
			$result = DB::table('ticketing__id')
				->where('ticketing__detail.id_ticket','LIKE','%' . $period . '%')
				->where('ticketing__detail.id_ticket','LIKE','%' . $acronym_client . '%')
				->where('ticketing__detail.pid',$pid)
				->where('ticketing__detail.type_ticket',$type)
				->join('ticketing__detail','ticketing__detail.id_ticket','=','ticketing__id.id_ticket')
				->orderBy('ticketing__detail.id_atm','ASC');
		} else {
			$result = DB::table('ticketing__id')
				->where('ticketing__detail.id_ticket','LIKE','%' . $period . '%')
				->where('ticketing__detail.id_ticket','LIKE','%' . $acronym_client . '%')
				->where('ticketing__detail.pid',$pid)
				->where('ticketing__detail.type_ticket',$type)
				->join('ticketing__detail','ticketing__detail.id_ticket','=','ticketing__id.id_ticket')
				->orderBy('ticketing__detail.id_ticket','ASC');
		}

		$final = [];

		if($result->count() == 0){
			return 0;
		}else{
			$result = $result->get();
			$atm_before = $result[0]->id_atm; 

			foreach ($result as $key => $value) {

				$check = DB::table('ticketing__activity')
					->where('id_ticket','=',$value->id_ticket)
					->orderBy('id','DESC')
					->value('activity');

				$downtime = 0;

				if($check == "CLOSE" || $check == "CANCEL"){
					$value->open = DB::table('ticketing__activity')
						->where('id_ticket','=',$value->id_ticket)
						->where('activity','=','OPEN')
						->value('date');

					$value->id_open = DB::table('ticketing__id')
						->where('id_ticket','=',$value->id_ticket)
						->value('id');

					$value->last_status = array(
						$check,
						DB::table('ticketing__activity')
							->where('id_ticket','=',$value->id_ticket)
							->orderBy('id','DESC')
							->value('date')
						);

					if($value->id_atm == $atm_before){
						
					}

					if($check == "CLOSE"){
						$value->root_couse = DB::table('ticketing__resolve')
							->where('id_ticket','=',$value->id_ticket)
							->value('root_couse');

						$value->counter_measure = DB::table('ticketing__resolve')
							->where('id_ticket','=',$value->id_ticket)
							->value('counter_measure');

					} else {
						$value->root_couse = '-';
						$value->counter_measure = '-';
					}


					$value->operator = DB::table('ticketing__activity')
						->where('id_ticket','=',$value->id_ticket)
						->orderBy('id','DESC')
						->value('operator');

					$final[] = $value;

				}
			}
			return $final;
		}
	}

	public function getReportNew(Request $request){

		$request->start = Carbon::parse($request->start . " 00:00:00")->toDateTimeString();
		$request->end = Carbon::parse($request->end . " 23:59:59")->toDateTimeString();
		$limitQuery = 600;

		$ticketing_activity_max = DB::table('ticketing__activity')
			->selectRaw("MAX(`id`) AS `id`")
			->groupBy('id_ticket');

		$ticketing_activity_occurring = DB::table('ticketing__activity')
			->joinSub($ticketing_activity_max,'ticketing_activity_max',function($join){
				$join->on('ticketing_activity_max.id','=','ticketing__activity.id');
			})
			->select('id_ticket')
			->where('activity','=',"OPEN")
			->orWhere('activity','=',"ON PROGRESS")
			->orWhere('activity','=',"PENDING");

		$ticketing_activity_filtered = DB::table('ticketing__activity')
			->select('id_ticket')
			->whereRaw('`ticketing__activity`.`date` BETWEEN "' . $request->start . '" AND "' . $request->end . '"')
			->groupBy('id_ticket');

		$ticketing_activity_filtered = DB::table(function ($query) use ($request,$ticketing_activity_occurring){
				$query->from('ticketing__activity')
					->select('id_ticket')
					->whereRaw('`ticketing__activity`.`date` BETWEEN "' . $request->start . '" AND "' . $request->end . '"')
					->groupBy('id_ticket');
			},'ticketing_activity_filtered')
			->joinSub($ticketing_activity_occurring,'ticketing_activity_occurring',function($join){
				$join->on('ticketing_activity_occurring.id_ticket','=','ticketing_activity_filtered.id_ticket');
			});

		$ticketing_activity_filtered = DB::table(function ($query) use ($request,$ticketing_activity_occurring){
				$query->from('ticketing__activity')
					->select('id_ticket')
					->whereRaw('`ticketing__activity`.`date` BETWEEN "' . $request->start . '" AND "' . $request->end . '"')
					->groupBy('id_ticket');
			},'ticketing_activity_filtered')
			->union($ticketing_activity_occurring);

		// return $ticketing_activity_occurring->pluck('ticketing_activity_filtered.id_ticket')->sortBy('id_ticket');
		// return $ticketing_activity_filtered->get();


		$open_activity_filtered = DB::table('ticketing__activity')
			->selectRaw('ticketing__activity.id_ticket')
            ->selectRaw("MAX(`id`) AS `latest_activity`")
            ->selectRaw("MIN(`id`) AS `open_activity`")
            ->selectRaw("MAX(`date`) AS `max_activity`")
            ->joinSub($ticketing_activity_filtered,'ticketing_activity_filtered',function($join){
            	$join->on('ticketing_activity_filtered.id_ticket','=','ticketing__activity.id_ticket');
            })
            ->where('ticketing__activity.date','<',$request->end)
            ->groupBy('id_ticket');

        $max_activity_filtered = DB::table('ticketing__activity')
			->selectRaw('`ticketing__activity`.`id_ticket`')
            ->selectRaw("MAX(`date`) AS `max_activity`")
            ->joinSub($ticketing_activity_filtered,'ticketing_activity_filtered',function($join){
            	$join->on('ticketing_activity_filtered.id_ticket','=','ticketing__activity.id_ticket');
            })
            ->where('ticketing__activity.date','<',$request->end)
            ->groupBy('id_ticket');

        $latest_activity_filtered = $open_activity_filtered;
        // $max_activity_filtered = $open_activity_filtered;

        $latest_activity_detail = DB::table('ticketing__activity')
        	->select('ticketing__activity.id_ticket')
        	->selectRaw("`ticketing__activity`.`date` AS `latest_date`")
        	->selectRaw("`ticketing__activity`.`operator` AS `latest_by`")
        	->selectRaw("`ticketing__activity`.`activity` AS `latest_activity`")
        	->selectRaw("`ticketing__activity`.`note` AS `latest_note`")
        	->joinSub($latest_activity_filtered,'latest_activity_filtered',function($join){
        		$join->on('ticketing__activity.id','=','latest_activity_filtered.latest_activity');
        	})
        	->orderBy('ticketing__activity.id_ticket','ASC')
        	->limit($limitQuery);

        $max_activity_detail = DB::table('ticketing__activity')
        	->select('ticketing__activity.id_ticket')
        	->selectRaw("`ticketing__activity`.`date` AS `max_date`")
        	->selectRaw("`ticketing__activity`.`operator` AS `max_by`")
        	->selectRaw("`ticketing__activity`.`activity` AS `max_activity`")
        	->selectRaw("`ticketing__activity`.`note` AS `max_note`")
        	->joinSub($max_activity_filtered,'max_activity_filtered',function($join){
        		$join->on('ticketing__activity.id_ticket','=','max_activity_filtered.id_ticket')
        			->on('ticketing__activity.date','=','max_activity_filtered.max_activity');
        	})
        	->orderBy('ticketing__activity.id_ticket','ASC')
        	->limit($limitQuery);

        // return $max_activity_detail->get();
        // return $max_activity_filtered->get();

        // return $latest_activity_detail->get();

        $ticket_filtered = $ticketing_activity_filtered;

        $ticket_handle = DB::table('ticketing__activity')
        	->select('ticketing__activity.id_ticket')
        	->selectRaw("GROUP_CONCAT(DISTINCT (`ticketing__activity`.`operator`) SEPARATOR ', ') AS `hendle_by`")
        	// ->whereRaw("`date` BETWEEN '" . $request->start . "' AND '" . $request->end . "'")
        	->joinSub($ticket_filtered,'ticket_filtered',function($join){
        		$join->on('ticket_filtered.id_ticket','ticketing__activity.id_ticket');
        	})
        	->where('ticketing__activity.operator','<>','System')
        	->groupBy('ticketing__activity.id_ticket');

        $ticket_escalate = DB::table('ticketing__escalate_engineer')
        	->select('id_ticket')
        	->selectRaw("GROUP_CONCAT(DISTINCT (CONCAT(`engineer_name`, ' (', `engineer_contact`, ')'))SEPARATOR ', ') AS `escalate_engineer`")
        	->whereRaw("`date_add` BETWEEN '" . $request->start . "' AND '" . $request->end . "'")
        	->groupBy('id_ticket');

		$data = DB::table(function($query) use ($open_activity_filtered,$limitQuery){
				$query->from('ticketing__activity')
					->select('ticketing__activity.id_ticket')
					->selectRaw("`ticketing__activity`.`date` AS `open_date`")
					->selectRaw("`ticketing__activity`.`operator` AS `open_by`")
					->joinSub($open_activity_filtered,'open_activity_filtered',function($join){
						$join->on('ticketing__activity.id','=','open_activity_filtered.open_activity');
					})
					->orderBy('ticketing__activity.id_ticket','ASC')
					->limit($limitQuery);
			},'open_activity_detail')
			->selectRaw("`open_activity_detail`.`id_ticket`")
		    ->selectRaw("IFNULL(`ticketing__detail`.`ticket_number_3party`,'-') AS `ticket_number_3party`")
		    ->selectRaw("IFNULL(`ticketing__detail`.`id_atm`,'-') AS `id_atm`")
		    ->selectRaw("CONCAT('[',`ticketing__detail`.`location`,'] ',`ticketing__detail`.`problem`) AS `location_problem`")
		    ->selectRaw("DATE_FORMAT(`ticketing__detail`.`reporting_time`,'%c/%e/%Y %k:%i') AS `open_reporting_date`")
		    ->selectRaw("DATE_FORMAT(`open_activity_detail`.`open_date`,'%c/%e/%Y %k:%i') AS `open_date`")
		    ->selectRaw("DATE_FORMAT(`latest_activity_detail`.`latest_date`,'%c/%e/%Y %k:%i') AS `latest_date`")
		    ->selectRaw("DATE_FORMAT(`max_activity_detail`.`max_date`,'%c/%e/%Y %k:%i') AS `max_date`")
		    ->selectRaw("`ticketing__severity`.`name` AS `severity_name`")
		    ->selectRaw("IF(`ticketing__detail`.`type_ticket` = 'TT','Trouble Ticket',IF(`ticketing__detail`.`type_ticket` = 'PM','Preventive Maintenance',IF(`ticketing__detail`.`type_ticket` = 'PL','Permintaan Layanan','-'))) AS `ticket_type`")
		    // ->selectRaw("`ticketing__detail`.`type_ticket` AS `ticket_type`")
		    ->selectRaw("IF(`latest_activity_detail`.`latest_activity` = 'CLOSE',`ticketing__resolve`.`root_couse`,'-') AS `root_couse`")
		    ->selectRaw("IF(`latest_activity_detail`.`latest_activity` = 'CLOSE',`ticketing__resolve`.`counter_measure`,`latest_activity_detail`.`latest_note`) AS `counter_measure/latest_note`")
		    ->selectRaw("`latest_activity_detail`.`latest_activity`")
		    ->selectRaw("IF(IFNULL(`ticketing__resolve`.`root_couse`, '-') = '-',IF(`latest_activity_detail`.`latest_activity` = 'CANCEL','Completed','Occurring'),'Completed') AS `actual_status`")
		    ->selectRaw("`open_activity_detail`.`open_by`")
		    ->selectRaw("IFNULL(`ticketing__detail`.`engineer`,'-') AS `engineer`")
		    ->selectRaw("`latest_activity_detail`.`latest_by`")
		    // ->selectRaw("IF(`latest_activity_detail`.`latest_activity` = 'CLOSE',`latest_activity_detail`.`latest_by`,'-') AS `close_by`")
		    ->selectRaw("`ticket_handle`.`hendle_by`")
		    ->selectRaw("IFNULL(`ticket_escalate`.`escalate_engineer`,'-') AS `escalate_engineer`")
		    ->selectRaw("REPLACE(TIMEDIFF(`open_activity_detail`.`open_date`,`ticketing__detail`.`reporting_time`),'00000','') AS `responds_time`")
		    ->selectRaw("IF(IFNULL(`ticketing__resolve`.`root_couse`, '-') = '-',IF(`latest_activity_detail`.`latest_activity` = 'CANCEL',TIMEDIFF(`latest_activity_detail`.`latest_date`,`open_activity_detail`.`open_date`),'-'),TIMEDIFF(`latest_activity_detail`.`latest_date`,`open_activity_detail`.`open_date`)) AS `resolution_time_by_close`")
		    ->selectRaw("IF(IFNULL(`ticketing__resolve`.`root_couse`, '-') = '-',IF(`latest_activity_detail`.`latest_activity` = 'CANCEL',TIMEDIFF(`max_activity_detail`.`max_date`,`open_activity_detail`.`open_date`),'-'),TIMEDIFF(`max_activity_detail`.`max_date`,`open_activity_detail`.`open_date`)) AS `resolution_time_by_latest_update`")
			->joinSub($latest_activity_detail,'latest_activity_detail',function($join){
				$join->on('open_activity_detail.id_ticket','=','latest_activity_detail.id_ticket');
			})
			->leftJoinSub($max_activity_detail,'max_activity_detail',function($join){
				$join->on('open_activity_detail.id_ticket','=','max_activity_detail.id_ticket');
			})
			->leftJoinSub($ticket_handle,'ticket_handle',function ($join){
				$join->on('open_activity_detail.id_ticket','=','ticket_handle.id_ticket');
			})
			->leftJoinSub($ticket_escalate,'ticket_escalate',function ($join){
				$join->on('open_activity_detail.id_ticket','=','ticket_escalate.id_ticket');
			})
			->leftJoin('ticketing__resolve','ticketing__resolve.id_ticket','=','open_activity_detail.id_ticket')
			->leftJoin('ticketing__detail','ticketing__detail.id_ticket','=','open_activity_detail.id_ticket')
			->leftJoin('ticketing__severity','ticketing__severity.id','=','ticketing__detail.severity')
			->orderBy('open_activity_detail.id_ticket','ASC');

		if ($request->type != 'none') {
			$data = $data->where('type_ticket',$request->type);
		}

		$data = $data->get();

		// return $data;

		
		$spreadsheet = new Spreadsheet();

	    $spreadsheet->removeSheetByIndex(0);
	    $spreadsheet->addSheet(new Worksheet($spreadsheet,'Summary'));
	    $summarySheet = $spreadsheet->setActiveSheetIndex(0);

	    $normalStyle = [
	      'font' => [
	        'name' => 'Calibri',
	        'size' => 8
	      ],
	    ];

	    $titleStyle = $normalStyle;
	    $titleStyle['alignment'] = ['horizontal' => Alignment::HORIZONTAL_CENTER];
	    $titleStyle['borders'] = ['outline' => ['borderStyle' => Border::BORDER_THIN]];
	    $titleStyle['font']['bold'] = true;

	    $headerStyle = $normalStyle;
	    $headerStyle['font']['bold'] = true;
	    $headerStyle['fill'] = ['fillType' => Fill::FILL_SOLID, 'startColor' => ["argb" => "FFC9C9C9"]];
	    $headerStyle['borders'] = ['allBorders' => ['borderStyle' => Border::BORDER_THIN]];

	    $summarySheet->getStyle('A1:V1')->applyFromArray($titleStyle);
	    $summarySheet->getStyle('A2:V2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
	    $summarySheet->getStyle('A2:V2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
	    $summarySheet->getStyle('C2:V2')->getAlignment()->setWrapText(true);
	    $summarySheet->getStyle('C2:V2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
	    $summarySheet->setCellValue('B1','Report Bayu');
	    $summarySheet->setCellValue('D1','Grab per ' . Carbon::now()->format("d M Y"));

	    $headerContent = [
			"id_ticket",
			"ticket_number_3party",
			"id_atm",
			"location_problem",
			"open_reporting_date",
			"open_date",
			"latest_date",
			"max_date",
			"severity_name",
			"ticket_type",
			"root_couse",
			"counter_measure/latest_note",
			"latest_activity",
			"actual_status",
			"open_by",
			"engineer",
			"last_update_by",
			// "close_by",
			"hendle_by",
			"escalate_engineer",
			"responds_time",
			"resolution_time_by_close",
			"resolution_time_by_latest_update",
		];
	    $summarySheet->getStyle('A2:U2')->applyFromArray($headerStyle);
	    
	    $summarySheet->fromArray($headerContent,NULL,'A2');

	    $itemStyle = $normalStyle;
	    $itemStyle['fill'] = ['fillType' => Fill::FILL_SOLID, 'startColor' => ["argb" => "FFFFFE9F"]];
	    $itemStyle['borders'] = ['allBorders' => ['borderStyle' => Border::BORDER_THIN]];
	    $data->map(function($item,$key) use ($summarySheet){
			$summarySheet->fromArray(
				array_values((array)$item),
				NULL,
				'A' . ($key + 3)
			);
	    });

	    $summarySheet->getColumnDimension('A')->setAutoSize(true);
	    $summarySheet->getColumnDimension('B')->setAutoSize(true);
	    $summarySheet->getColumnDimension('C')->setAutoSize(true);
	    $summarySheet->getColumnDimension('D')->setAutoSize(true);
	    $summarySheet->getColumnDimension('E')->setAutoSize(true);
	    $summarySheet->getColumnDimension('F')->setAutoSize(true);
	    $summarySheet->getColumnDimension('G')->setAutoSize(true);
	    $summarySheet->getColumnDimension('H')->setAutoSize(true);
	    $summarySheet->getColumnDimension('I')->setAutoSize(true);
	    $summarySheet->getColumnDimension('J')->setAutoSize(true);
	    $summarySheet->getColumnDimension('K')->setAutoSize(true);
	    $summarySheet->getColumnDimension('L')->setAutoSize(true);
	    $summarySheet->getColumnDimension('M')->setAutoSize(true);
	    $summarySheet->getColumnDimension('N')->setAutoSize(true);
	    $summarySheet->getColumnDimension('O')->setAutoSize(true);
	    $summarySheet->getColumnDimension('P')->setAutoSize(true);
	    $summarySheet->getColumnDimension('Q')->setAutoSize(true);
	    $summarySheet->getColumnDimension('R')->setAutoSize(true);
	    $summarySheet->getColumnDimension('S')->setAutoSize(true);
	    $summarySheet->getColumnDimension('T')->setAutoSize(true);
	    $summarySheet->getColumnDimension('U')->setAutoSize(true);
	    $summarySheet->getColumnDimension('V')->setAutoSize(true);

	    $spreadsheet->setActiveSheetIndex(0);

	   
	    $name = 'Report_Bayu_-_[' . $request->start . '_to_' . $request->end . ']_(' . date("Y-m-d") . ')_' . Auth::user()->name . '.xlsx';
		$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
		$location = public_path() . '/report/bayu/' . $name;
		ob_end_clean();
		$writer->save($location);
		return $name;
	}

	public function getReportNewDeny(Request $request){

		$ticketing_activity_max = DB::table('ticketing__activity')
			->selectRaw("MAX(`id`) AS `id`")
			->groupBy('id_ticket');

		$ticketing_activity_occurring = DB::table('ticketing__activity')
			->joinSub($ticketing_activity_max,'ticketing_activity_max',function($join){
				$join->on('ticketing_activity_max.id','=','ticketing__activity.id');
			})
			->select('id_ticket','activity')
			->where('activity','=',"OPEN")
			->orWhere('activity','=',"ON PROGRESS")
			->orWhere('activity','=',"PENDING");

		// return $ticketing_activity_occurring->get();

		// $ticketing_activity_occurring_all = DB::table('ticketing__activity')
		// 	->joinSub($ticketing_activity_occurring,'ticketing_activity_occurring',function($join){
		// 		$join->on('ticketing_activity_occurring.id_ticket','=','ticketing__activity.id_ticket');
		// 	});

		// return $ticketing_activity_occurring_all->get();

        $ticketing_id_filtered = DB::table('ticketing__activity')
        	// ->selectRaw("MAX(`id`) AS `latest_id`")
        	// ->selectRaw("MIN(`id`) AS `open_id`")
        	->selectRaw('ticketing__activity.id_ticket')
        	// ->selectRaw('COUNT(*)')
        	->whereRaw('`date` BETWEEN "' . $request->start . '" AND "' . $request->end  . '"')
        	// ->orWhereIn('id_ticket',$ticketing_activity_occurring->pluck('id_ticket'))
        	->groupBy('id_ticket');

        // return $ticketing_max_min->get();

		$ticketing_max_min = DB::table('ticketing__activity')
			->joinSub($ticketing_id_filtered,'ticketing__activity_filterd',function($join){
				$join->on('ticketing__activity_filterd.id_ticket','=','ticketing__activity.id_ticket');
			})
			->selectRaw("MAX(`id`) AS `latest_id`")
        	->selectRaw("MIN(`id`) AS `open_id`")
			->groupBy('ticketing__activity.id_ticket');


		// return $ticketing_max_min->pluck('id_ticket');
		// return $ticketing_max_min->get();
		// return $ticketing_activity_occurring->pluck('activity');
		// return $ticketing_activity_occurring_all->get();

        $open_activity_table = DB::table('ticketing__activity')
        	->select("ticketing__activity.id_ticket")
			->selectRaw("`ticketing__activity`.`activity` AS `open_activity`")
	        ->selectRaw("`ticketing__activity`.`date` AS `open_activity_date`")
	        ->selectRaw("`ticketing__activity`.`operator` AS `open_operator`")
	        ->joinSub($ticketing_max_min,'open_activity',function($join){
				$join->on('ticketing__activity.id','=','open_activity.open_id');
			});

		$latest_activity_table = DB::table('ticketing__activity')
        	->select("ticketing__activity.id_ticket")
			->selectRaw("`ticketing__activity`.`activity` AS `latest_activity`")
	        ->selectRaw("`ticketing__activity`.`date` AS `latest_activity_date`")
	        ->selectRaw("`ticketing__activity`.`operator` AS `latest_operator`")
	        ->joinSub($ticketing_max_min,'latest_activity',function($join){
				$join->on('ticketing__activity.id','=','latest_activity.latest_id');
			})
			->orderBy('latest_activity','ASC');

		$ticketing_activity = DB::table('ticketing__activity');

		$joined_activity_table = DB::table(function ($query) use ($ticketing_id_filtered){
			$query->from('ticketing__activity')
				->joinSub($ticketing_id_filtered,'ticketing__activity_filterd',function($join){
					$join->on('ticketing__activity_filterd.id_ticket','=','ticketing__activity.id_ticket');
				})
				->selectRaw('ticketing__activity.id_ticket')
				->selectRaw("MAX(`ticketing__activity`.`id`) AS `latest_id`")
	        	->selectRaw("MIN(`ticketing__activity`.`id`) AS `open_id`")

				->groupBy('ticketing__activity.id_ticket');
		},'ticketing__activity_limited')
		->joinSub($ticketing_activity,'open_activity_table',function($join){
			$join->on('open_activity_table.id','=','ticketing__activity_limited.open_id');
		})
		->joinSub($ticketing_activity,'latest_activity_table',function($join){
			$join->on('latest_activity_table.id','=','ticketing__activity_limited.latest_id');
		})
		->leftJoin('ticketing__resolve','ticketing__activity_limited.id_ticket','=','ticketing__resolve.id_ticket')
		->join('ticketing__detail','ticketing__activity_limited.id_ticket','=','ticketing__detail.id_ticket')
		
		->selectRaw("`open_activity_table`.`id_ticket`")
		->selectRaw("`open_activity_table`.`activity` AS `open_activity`")
		->selectRaw("SUBSTR(`open_activity_table`.`date`,1,19) AS `open_activity_date`")
		->selectRaw("SUBSTR(`ticketing__detail`.`reporting_time`,1,19) AS `open_reporting_date`")
		->selectRaw("`open_activity_table`.`operator` AS `open_operator`")
		->selectRaw("`latest_activity_table`.`activity` AS `latest_activity`")
		->selectRaw("SUBSTR(`latest_activity_table`.`date`,1,19) AS `latest_activity_date`")
		->selectRaw("`latest_activity_table`.`operator` AS `latest_operator`")
		->selectRaw("TIMEDIFF(`latest_activity_table`.`date`,`open_activity_table`.`date`) AS `resolution_time`")
		->selectRaw("`ticketing__detail`.`engineer`")
		->selectRaw("`ticketing__resolve`.`root_couse`")
		->selectRaw("`ticketing__detail`.`engineer`")
		->selectRaw("`ticketing__resolve`.`counter_measure`");



		// ->selectRaw("`ticketing__resolve`.`root_couse`")
		// ->selectRaw("`ticketing__detail`.`engineer`")
		// ->selectRaw("`open_activity_table`.`activity` AS `open_activity`")
		// ->selectRaw("`latest_activity_table`.`activity` AS `latest_activity`");

		// ->selectRaw('ticketing__activity_limited.id_ticket');

		// return $joined_activity_table->pluck('open_reporting_date');
		// return $latest_activity_table->get();
		// return $latest_activity_table->pluck('latest_activity');
		
		// $latest_activity_table = DB::table('ticketing__activitya')
		// $latest_activity_table = DB::table(function ($query) use ($request){
		// 	$query->from('ticketing__activity')
		// 		->selectRaw('ticketing__activity.id_ticket')
		// 		->whereRaw('`date` BETWEEN "' . $request->start . '" AND "' . $request->end  . '"')
		//         ->groupBy('id_ticket');
		// },'ticketing__activity_limited')
		// ->selectRaw("`open_activity_table`.`id_ticket`")
		// ->selectRaw("`open_activity_table`.`open_activity`")
		// ->selectRaw("`open_activity_table`.`open_activity_date`")
		// ->selectRaw("`ticketing__detail`.`reporting_time` AS `open_reporting_date`")
		// ->selectRaw("`open_activity_table`.`open_operator`")
		// ->selectRaw("`latest_activity_table`.`latest_activity`")
		// ->selectRaw("`latest_activity_table`.`latest_activity_date`")
		// ->selectRaw("`latest_activity_table`.`latest_operator`")
		// ->selectRaw("TIMEDIFF(`latest_activity_table`.`latest_activity_date`,`open_activity_table`.`open_activity_date`) AS `resolution_time`")
		// ->selectRaw("`ticketing__detail`.`engineer`")
		// ->selectRaw("`ticketing__resolve`.`root_couse`")
		// ->selectRaw("`ticketing__detail`.`engineer`")
		// ->selectRaw("`ticketing__resolve`.`counter_measure`")
		// ->joinSub($open_activity_table,'open_activity_table',function($join){
		// 	$join->on('ticketing__activity_limited.id_ticket','=','open_activity_table.id_ticket');
		// })
		// ->joinSub($latest_activity_table,'latest_activity_table',function($join){
		// 	$join->on('ticketing__activity_limited.id_ticket','=','latest_activity_table.id_ticket');
		// })
		
		// ->orderBy('ticketing__activity_limited1.id_ticket','ASC')
		// ->get();

		// return $latest_activity_table->pluck('latest_activity');
		$latest_activity_table = $joined_activity_table->get();

		$spreadsheet = new Spreadsheet();

	    $spreadsheet->removeSheetByIndex(0);
	    $spreadsheet->addSheet(new Worksheet($spreadsheet,'Summary'));
	    $summarySheet = $spreadsheet->setActiveSheetIndex(0);

	    $normalStyle = [
	      'font' => [
	        'name' => 'Calibri',
	        'size' => 8
	      ],
	    ];

	    $titleStyle = $normalStyle;
	    $titleStyle['alignment'] = ['horizontal' => Alignment::HORIZONTAL_CENTER];
	    $titleStyle['borders'] = ['outline' => ['borderStyle' => Border::BORDER_THIN]];
	    $titleStyle['font']['bold'] = true;

	    $headerStyle = $normalStyle;
	    $headerStyle['font']['bold'] = true;
	    $headerStyle['fill'] = ['fillType' => Fill::FILL_SOLID, 'startColor' => ["argb" => "FFC9C9C9"]];
	    $headerStyle['borders'] = ['allBorders' => ['borderStyle' => Border::BORDER_THIN]];

	    $summarySheet->getStyle('A1:O1')->applyFromArray($titleStyle);
	    $summarySheet->getStyle('A2:O2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
	    $summarySheet->getStyle('A2:O2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
	    $summarySheet->getStyle('C2:O2')->getAlignment()->setWrapText(true);
	    $summarySheet->getStyle('C2:O2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
	    $summarySheet->setCellValue('B1','Report Bulanan');
	    $summarySheet->setCellValue('D1','Grab per ' . Carbon::now()->format("d M Y"));

	    $headerContent = ["id_ticket","open_activity","open_activity_date","open_reporting_date","open_operator","latest_activity","latest_activity_date","latest_operator","resolution_time","engineer","root_couse","counter_measure",];
	    $summarySheet->getStyle('A2:L2')->applyFromArray($headerStyle);
	    
	    $summarySheet->fromArray($headerContent,NULL,'A2');

	    $itemStyle = $normalStyle;
	    $itemStyle['fill'] = ['fillType' => Fill::FILL_SOLID, 'startColor' => ["argb" => "FFFFFE9F"]];
	    $itemStyle['borders'] = ['allBorders' => ['borderStyle' => Border::BORDER_THIN]];
	    $latest_activity_table->map(function($item,$key) use ($summarySheet){
			$summarySheet->fromArray(
				array_values((array)$item),
				NULL,
				'A' . ($key + 3)
			);
	    });

	    $summarySheet->getColumnDimension('A')->setAutoSize(true);
	    $summarySheet->getColumnDimension('B')->setAutoSize(true);
	    $summarySheet->getColumnDimension('C')->setAutoSize(true);
	    $summarySheet->getColumnDimension('D')->setAutoSize(true);
	    $summarySheet->getColumnDimension('E')->setAutoSize(true);
	    $summarySheet->getColumnDimension('F')->setAutoSize(true);
	    $summarySheet->getColumnDimension('G')->setAutoSize(true);
	    $summarySheet->getColumnDimension('H')->setAutoSize(true);
	    $summarySheet->getColumnDimension('I')->setAutoSize(true);
	    $summarySheet->getColumnDimension('J')->setAutoSize(true);
	    $summarySheet->getColumnDimension('K')->setAutoSize(true);
	    $summarySheet->getColumnDimension('L')->setAutoSize(true);

	    $spreadsheet->setActiveSheetIndex(0);

	   
	    $name = 'Report_Denny_-_[' . $request->start . '_to_' . $request->end . ']_(' . date("Y-m-d") . ')_' . Auth::user()->name . '.xlsx';
		$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
		$location = public_path() . '/report/denny/' . $name;
		ob_end_clean();
		$writer->save($location);
		return $name;

		return $latest_activity_table->get();
	}

	public function getUser(Request $request)
	{
		$getUser = User::join('role_user', 'role_user.user_id', '=', 'users.nik')
		    ->join('roles', 'roles.id', '=', 'role_user.role_id')
		    ->select(DB::raw('`nik` AS `id`, `users`.`name` AS `text`'))
		    ->whereRaw("(`roles`.`name` = 'Engineer Onsite Enterprise' 
		                OR `roles`.`name` = 'Engineer Onsite SOC' 
		                OR `roles`.`name` = 'Engineer Onsite ATM' 
		                OR `roles`.`name` = 'Customer Support Center')")
		    ->get();        

		return ["data" => collect($getUser)];
	}

	public function getCustomer()
    {
        $getCustomer = collect(DB::table('tb_id_project')->join('sales_lead_register','sales_lead_register.lead_id','tb_id_project.lead_id')->join('users','users.nik','sales_lead_register.nik')->join('tb_contact','tb_contact.id_customer','sales_lead_register.id_customer')->select(DB::raw('`tb_contact`.`brand_name` AS `id`,`customer_legal_name` AS `text`'))->where('users.id_company', '1')->distinct()->get());

        return array("data" => $getCustomer);
    }

    public function getAllPid(Request $request)
    {
    	// $nik = json_decode($request->nik,true);
    	if ($request->assign == 'user') {
    		$getPid = DB::table('ticketing__user')->select('pid')->whereIn('nik',$request->nik);
	    	$getAllPid = SalesProject::join('sales_lead_register', 'sales_lead_register.lead_id', '=', 'tb_id_project.lead_id')
	    						->join('tb_contact','tb_contact.id_customer','sales_lead_register.id_customer')
		    					->join('users', 'users.nik', '=', 'sales_lead_register.nik')
		    					->leftJoinSub($getPid, 'tb_pid',function($join){
				                    $join->on("tb_pid.pid", '=', 'tb_id_project.id_project');
				                })
		    					->select('tb_pid.pid', 'id_project',DB::raw("(CASE WHEN `tb_pid`.`pid` is null THEN 'Non-Selected' ELSE 'Selected' END) as result_modif"),'name_project','brand_name','customer_legal_name')->where('id_company', '1');

	    	if (DB::table('ticketing__user')->where('nik',$request->nik)->exists()) {
		    	$getAllPid = $getAllPid->orderby('result_modif','desc')->orderBy('tb_id_project.created_at','desc')->get();
	    	} else {
	    		$getAllPid = $getAllPid->orderby('tb_id_project.created_at','desc')->get();
	    	}
	    } else {
	    	$getProjectName = DB::table('presence__shifting_user')->join('presence__shifting_project', 'presence__shifting_project.id', 'presence__shifting_user.shifting_project')->select('project_name')->whereIn('nik',$request->nik)->first();

    		$getPidShifting = DB::table('ticketing__user')->join('presence__shifting_user','presence__shifting_user.nik','ticketing__user.nik')->join('presence__shifting_project', 'presence__shifting_project.id', 'presence__shifting_user.shifting_project')->select('pid')->where('project_name',$getProjectName->project_name)->distinct();

    		$getAllPid = SalesProject::join('sales_lead_register', 'sales_lead_register.lead_id', '=', 'tb_id_project.lead_id')
	    						->join('tb_contact','tb_contact.id_customer','sales_lead_register.id_customer')
		    					->join('users', 'users.nik', '=', 'sales_lead_register.nik')
		    					->leftJoinSub($getPidShifting, 'getPidShifting',function($join){
				                    $join->on("getPidShifting.pid", '=', 'tb_id_project.id_project');
				                })
		    					->select('getPidShifting.pid', 'id_project',DB::raw("(CASE WHEN `getPidShifting`.`pid` is null THEN 'Non-Selected' ELSE 'Selected' END) as result_modif"),'name_project','brand_name','customer_legal_name')->where('id_company', '1');

    		// return $getPidShifting->get();
		   	$getAllPid = $getAllPid->orderby('result_modif','desc')->orderBy('tb_id_project.created_at','desc')->get();
    	}
    	

    	return array("data"=>$getAllPid);
    }

    public function getSiteShifting()
    {
    	return $data = DB::table('presence__shifting_project')->select('id','project_name')->get();
    }

    public function getUserShifting()
    {
    	$nik = Auth::User()->nik;
    	$cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')
                    ->select('name')->where('user_id', Auth::User()->nik)->first();

        if ($cek_role->name == 'Synergy System & Services Manager' || $nik == '1181195100') {
        	$data = DB::table('presence__shifting_user')->join('presence__shifting_project','presence__shifting_project.id','presence__shifting_user.shifting_project')->join('users','users.nik','presence__shifting_user.nik')->select('users.name','project_name','presence__shifting_user.nik','presence__shifting_project.id as id_location')->where('status_karyawan','!=','dummy')->orderBy('project_name','asc')->get();

	    	$dataNonShifting = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')
	    			->whereNotExists(function($query)
	                {
	                    $query->select(DB::raw(1))
	                          ->from('presence__shifting_user')
	                          ->whereRaw('presence__shifting_user.nik = users.nik');
	                })
	                ->whereRaw("(`roles`.`name` = 'Engineer Onsite Enterprise' OR `roles`.`name` = 'Engineer Onsite SOC' OR `roles`.`name` = 'Engineer Onsite ATM')")
	                ->select('users.name','users.nik',DB::raw("CONCAT('Not-Set') AS `project_name`"),DB::raw("CONCAT('-') AS `id_location`"))->orderBy('project_name','asc')->get();

	        $data = $data->merge($dataNonShifting);
        } elseif ($cek_role->name == 'Project Transformation Officer') {
        	$data = DB::table('presence__shifting_user')->join('presence__shifting_project','presence__shifting_project.id','presence__shifting_user.shifting_project')->join('users','users.nik','presence__shifting_user.nik')->select('users.name','project_name','presence__shifting_user.nik','presence__shifting_project.id as id_location')->where('status_karyawan','!=','dummy')->orderBy('project_name','asc')->get();

	    	$dataNonShifting = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')
	    			->whereNotExists(function($query)
	                {
	                    $query->select(DB::raw(1))
	                          ->from('presence__shifting_user')
	                          ->whereRaw('presence__shifting_user.nik = users.nik');
	                })
	                ->whereRaw("(`roles`.`name` = 'Customer Support Center')")
	                ->select('users.name','users.nik',DB::raw("CONCAT('Not-Set') AS `project_name`"),DB::raw("CONCAT('-') AS `id_location`"))->orderBy('project_name','asc')->get();

	        $data = $data->merge($dataNonShifting);
        } else {
        	$data = DB::table('presence__shifting_user')->join('presence__shifting_project','presence__shifting_project.id','presence__shifting_user.shifting_project')->join('users','users.nik','presence__shifting_user.nik')->select('users.name','project_name','presence__shifting_user.nik','presence__shifting_project.id as id_location')->where('status_karyawan','!=','dummy')->where('presence__shifting_user.nik',Auth::User()->nik)->orderBy('project_name','asc')->get();

	    	$dataNonShifting = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')
	    			->whereNotExists(function($query)
	                {
	                    $query->select(DB::raw(1))
	                          ->from('presence__shifting_user')
	                          ->whereRaw('presence__shifting_user.nik = users.nik');
	                })
	                ->whereRaw("(`roles`.`name` = 'Engineer Onsite Enterprise' OR `roles`.`name` = 'Engineer Onsite SOC' OR `roles`.`name` = 'Engineer Onsite ATM' OR `roles`.`name` = 'Customer Support Center')")
	                ->select('users.name','users.nik',DB::raw("CONCAT('Not-Set') AS `project_name`"),DB::raw("CONCAT('-') AS `id_location`"))->orderBy('project_name','asc')->where('users.nik',Auth::User()->nik)->get();

	        $data = $data->merge($dataNonShifting);
        }
    	

    	$dataAll = collect();

    	foreach ($data as $key => $value) {
    		$count = DB::table('ticketing__user')->where('nik',$value->nik)->count();
    		$dataAll->push([
    			"name" => $value->name,
    			"id_location" =>$value->id_location,
    			"nik" => $value->nik,
    			"project_name" => $value->project_name,
    			"count"	=> $count
    		]);
    	}

    	return $dataAll;
    }

    public function getFilterDataAll(Request $request)
    {
    	$cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')
                    ->select('name')->where('user_id', Auth::User()->nik)->first();

    	if ($request->assign == 'user') {

    		$getPid = TicketingUser::join('tb_id_project','tb_id_project.id_project','ticketing__user.pid')->join('sales_lead_register','sales_lead_register.lead_id','tb_id_project.lead_id')->join('tb_contact','tb_contact.id_customer','sales_lead_register.id_customer')->selectRaw('ticketing__user.nik')->selectRaw('GROUP_CONCAT(`ticketing__user`.`pid`, " - ", `name_project`) AS `pid`')->selectRaw('GROUP_CONCAT(`tb_contact`.`brand_name`) AS `brand_name`')->groupby('ticketing__user.nik');

    		$dataNonShifting = User::leftJoinSub($getPid, 'tb_pid',function($join){
                    $join->on("tb_pid.nik", '=', 'users.nik');
                })
    			->whereNotExists(function($query){
                    $query->select(DB::raw(1))
                          ->from('presence__shifting_user')
                          ->whereRaw('presence__shifting_user.nik = users.nik');
                })->whereRaw("(`id_company` = '1' AND `id_division` = 'MSM' AND `status_karyawan` != 'dummy' AND `id_position` != 'ADMIN' AND `id_position` != 'MANAGER')")
                ->select('users.name',DB::raw("CONCAT('Not-Set') AS `project_name`"),'users.nik',DB::raw("CONCAT('-') AS `id_location`"),'tb_pid.pid',DB::raw("CONCAT('-') AS `brand_name`"))->orderBy('project_name','asc');

            if ($cek_role->name == 'Synergy System & Services Manager' || $cek_role->name == 'Supply Chain & IT Support Manager') {
            	$dataNonShifting = $dataNonShifting;
            } else{
            	$dataNonShifting = $dataNonShifting->where('users.nik',Auth::User()->nik);
            }

            if (!in_array(null,$request->location)) {
    			foreach ($request->location as $key => $value) {
	                $dataNonShifting->havingRaw('FIND_IN_SET("'. $value.'", project_name)');
	            }
    		}

    		if (!in_array(null,$request->user)) {
    			$dataNonShifting->whereIn('users.nik',$request->user);
    		}

    		if (!in_array(null,$request->customer)) {
	            foreach ($request->customer as $key => $value) {
	                $dataNonShifting->havingRaw('FIND_IN_SET("'. $value.'", brand_name)');
	            }
	        }

    		$data = DB::table('presence__shifting_user')
    				->join('presence__shifting_project','presence__shifting_project.id','presence__shifting_user.shifting_project')
    				->join('users','users.nik','presence__shifting_user.nik')
    				->leftJoinSub($getPid, 'tb_pid',function($join){
	                    $join->on("tb_pid.nik", '=', 'presence__shifting_user.nik');
	                })
    				->select('users.name','project_name','presence__shifting_user.nik','presence__shifting_project.id as id_location','tb_pid.pid','brand_name')
    				->where('status_karyawan','!=','dummy')
    				->orderBy('project_name','asc')
    				->union($dataNonShifting);

    		if ($cek_role->name == 'Synergy System & Services Manager' || $cek_role->name == 'Supply Chain & IT Support Manager') {
            	$data = $data;
            } else{
            	$data = $data->where('users.nik',Auth::User()->nik);
            }

    		if (!in_array(null,$request->location)) {
    			$data->whereIn('project_name',$request->location);
    		}

    		if (!in_array(null,$request->user)) {
    			$data->whereIn('presence__shifting_user.nik',$request->user);
    		}

    		if (!in_array(null,$request->customer)) {
	            foreach ($request->customer as $key => $value) {
	                $data->havingRaw('FIND_IN_SET("'. $value.'", brand_name)');
	            }
	        }

    		$data = $data->get();

    		$dataAll = collect();
    		foreach ($data as $key => $value) {
	    		$count = DB::table('ticketing__user')->join('tb_id_project','tb_id_project.id_project','ticketing__user.pid')->join('sales_lead_register', 'sales_lead_register.lead_id', '=', 'tb_id_project.lead_id')->join('tb_contact','tb_contact.id_customer','sales_lead_register.id_customer')->select('id_project','name_project','brand_name')->where('ticketing__user.nik',$value->nik);

	    		if (!in_array(null,$request->customer)) {
		            foreach ($request->customer as $key => $valueCount) {
		                $count->havingRaw('FIND_IN_SET("'. $valueCount.'", brand_name)');
		            }
		        }

	    		$dataAll->push([
	    			"name" => $value->name,
	    			"id_location" =>$value->id_location,
	    			"nik" => $value->nik,
	    			"project_name" => $value->project_name,
	    			"count"	=> $count->get()->count()
	    		]);
	    	}
    	} else {

    		$data = DB::table('users')
    				->join('presence__shifting_user','users.nik','presence__shifting_user.nik')
	    			->join('presence__shifting_project', 'presence__shifting_project.id', 'presence__shifting_user.shifting_project')
	    			->leftJoin('ticketing__user','ticketing__user.nik','presence__shifting_user.nik')
	    			->leftJoin('tb_id_project','tb_id_project.id_project','ticketing__user.pid')
	    			->leftJoin('sales_lead_register','sales_lead_register.lead_id','tb_id_project.lead_id')
	    			->leftJoin('tb_contact','tb_contact.id_customer','sales_lead_register.id_customer')
	    			->selectRaw('GROUP_CONCAT(DISTINCT `users`.`name`) AS `name`, GROUP_CONCAT(DISTINCT `presence__shifting_user`.`nik`) AS `nik`')
	    			->selectRaw('GROUP_CONCAT( DISTINCT `ticketing__user`.`pid`, " - ", `tb_id_project`.`name_project`) AS `pid`')
	    			->selectRaw('GROUP_CONCAT(DISTINCT `tb_contact`.`brand_name`) AS `brand_name`')
	    			->selectRaw('presence__shifting_project.project_name')->where('status_karyawan','!=','dummy')->groupBy('presence__shifting_project.project_name');

	    	if ($cek_role->name == 'Synergy System & Services Manager' || $cek_role->name == 'Supply Chain & IT Support Manager') {
            	$data = $data;
            } else{
            	// $data = $data;
            	$data = $data->where('nik',Auth::User()->nik);
            }

	    	if (!in_array(null,$request->location)) {
    			$data->where('project_name',$request->location);
    		}

    		if (!in_array(null,$request->customer)) {
	            foreach ($request->customer as $key => $value) {
	                $data->havingRaw('FIND_IN_SET("'. $value.'", brand_name)');
	            }
	        }

	    	$data = $data->get();

	    	$dataAll = collect();

	    	foreach ($data as $key => $value) {
	    		$count = DB::table('ticketing__user')->join('presence__shifting_user','presence__shifting_user.nik','ticketing__user.nik')->join('presence__shifting_project', 'presence__shifting_project.id', 'presence__shifting_user.shifting_project')->select('pid')->where('project_name',$value->project_name)->distinct()->get();
	    		$dataAll->push([
	    			"name" => $value->name,
	    			"nik" => $value->nik,
	    			"id" => DB::table('presence__shifting_project')->where('project_name',$value->project_name)->first()->id,
	    			"project_name" => $value->project_name,
	    			"count" => $count->count('pid')
	    		]);
	    	}
	    }

    	return $dataAll;
    }

    public function storeAssign(Request $request)
    {
    	$nik = json_decode($request->nik,true);
    	$delete = TicketingUser::whereIn('nik',$nik)->delete();

    	foreach ($nik as $key => $value) {
    		foreach (json_decode($request->pid,true) as $key => $valuepid) {
    			$store = new TicketingUser();
	    		$store->nik = $value;
	    		$store->pid = $valuepid;
	    		$store->date_time = Carbon::now()->toDateTimeString();
	    		$store->save();
    		}
    		
    	}
    }

    public function getFilterPIDByCustomer(Request $request)
    {
    	// $nik = json_decode($request->nik,true);
    	if (DB::table('ticketing__user')->where('nik',$request->nik)->exists()) {
    		$getPid = DB::table('ticketing__user')->select('pid')->whereIn('nik',$request->nik);

	    	$getAllPid = SalesProject::join('sales_lead_register', 'sales_lead_register.lead_id', '=', 'tb_id_project.lead_id')
	    					->join('users', 'users.nik', '=', 'sales_lead_register.nik')
	    					->join('tb_contact','tb_contact.id_customer','sales_lead_register.id_customer')
	    					->leftJoinSub($getPid, 'tb_pid',function($join){
			                    $join->on("tb_pid.pid", '=', 'tb_id_project.id_project');
			                })
	    					->select('tb_pid.pid', 'id_project',DB::raw("(CASE WHEN `tb_pid`.`pid` is null THEN 'Non-Selected' ELSE 'Selected' END) as result_modif"),'name_project','brand_name',DB::raw("(CASE WHEN `tb_pid`.`pid` is null THEN `id_project` ELSE `id_project` END) as pid"),'customer_legal_name')->where('id_company', '1')
	    					->orderby('result_modif','desc')->orderBy('tb_id_project.created_at','desc');
    	} else {
    		$getPid = DB::table('ticketing__user')->select('pid')->whereIn('nik',$request->nik);

    		$getAllPid = SalesProject::join('sales_lead_register', 'sales_lead_register.lead_id', '=', 'tb_id_project.lead_id')
    					->join('users', 'users.nik', '=', 'sales_lead_register.nik')
    					->join('tb_contact','tb_contact.id_customer','sales_lead_register.id_customer')
    					->leftJoinSub($getPid, 'tb_pid',function($join){
		                    $join->on("tb_pid.pid", '=', 'tb_id_project.id_project');
		                })
    					->select('tb_pid.pid', 'id_project',DB::raw("(CASE WHEN `tb_pid`.`pid` is null THEN 'Non-Selected' ELSE 'Selected' END) as result_modif"),'name_project','brand_name',DB::raw("(CASE WHEN `tb_pid`.`pid` is null THEN `id_project` ELSE `id_project` END) as pid"),'customer_legal_name')->where('id_company', '1')
    					->orderby('tb_id_project.created_at','desc');
    	}

    	// return $getAllPid->get();

    	// if (isset($request->customer)) {
    	// 	$getAllPid->whereIn('sales_lead_register.id_customer',$request->customer);
    	// }

    	if (!in_array(null,$request->customer)) {
            foreach ($request->customer as $key => $value) {
                $getAllPid->havingRaw('FIND_IN_SET("'. $value.'", brand_name)');
            }
        }

    	return array("data"=>$getAllPid->get());
    }

    public function getSearchPID(Request $request)
    {
    	$getPid = DB::table('ticketing__user')->select('pid')->where('nik',$request->nik);

    	$getAllPid = SalesProject::join('sales_lead_register', 'sales_lead_register.lead_id', '=', 'tb_id_project.lead_id')
    					->join('users', 'users.nik', '=', 'sales_lead_register.nik')
    					->leftJoinSub($getPid, 'tb_pid',function($join){
		                    $join->on("tb_pid.pid", '=', 'tb_id_project.id_project');
		                })
    					->select('tb_pid.pid', 'id_project',DB::raw("(CASE WHEN `tb_pid`.`pid` is null THEN 'Non-Selected' ELSE 'Selected' END) as result_modif"),'name_project')->where('id_company', '1')
    					->orderby('result_modif','desc')->orderBy('tb_id_project.created_at','desc');

    	$searchFields = ['tb_pid.pid', 'id_project', 'name_project'];

        if($request->search != ""){
            $getAllPid->where(function($getAllPid) use($request, $searchFields){
                $searchWildCard = '%'. $request->search . '%';
                foreach ($searchFields as $data) {
                    $getAllPid->orWhere($data, 'LIKE', $searchWildCard);
                }
            });
        }

        if (isset($request->customer)) {
        	$getAllPid->whereIn('sales_lead_register.id_customer',$request->customer);
        }


        return array("data" => $getAllPid->get());
    }

    public function getSearchAllData(Request $request)
    {
    	$cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')
                    ->select('name')->where('user_id', Auth::User()->nik)->first();

    	if ($request->assign == 'user') {

    		$getPid = TicketingUser::join('tb_id_project','tb_id_project.id_project','ticketing__user.pid')->selectRaw('ticketing__user.nik')->selectRaw('GROUP_CONCAT(`pid`, " - ", `name_project`) AS `pid`')->groupby('ticketing__user.nik');

    		$dataNonShifting = User::leftJoinSub($getPid, 'tb_pid',function($join){
                    $join->on("tb_pid.nik", '=', 'users.nik');
                })
    			->whereNotExists(function($query){
                    $query->select(DB::raw(1))
                          ->from('presence__shifting_user')
                          ->whereRaw('presence__shifting_user.nik = users.nik');
                })->whereRaw("(`id_company` = '1' AND `id_division` = 'MSM' AND `status_karyawan` != 'dummy' AND `id_position` != 'ADMIN' AND `id_position` != 'MANAGER')")
                ->select('users.name','users.nik',DB::raw("CONCAT('Not-Set') AS `project_name`"),DB::raw("CONCAT('-') AS `id_location`"),'tb_pid.pid');

    		$dataAll = User::join('presence__shifting_user','users.nik','presence__shifting_user.nik')
    				->join('presence__shifting_project','presence__shifting_project.id','presence__shifting_user.shifting_project')
    				->leftJoinSub($getPid, 'tb_pid',function($join){
	                    $join->on("tb_pid.nik", '=', 'presence__shifting_user.nik');
	                })
    				->select('users.name','project_name','presence__shifting_user.nik','presence__shifting_project.id as id_location','tb_pid.pid')
    				->where('users.status_karyawan','!=','dummy')
    				->orderBy('project_name','asc');

    		if ($cek_role->name == 'Synergy System & Services Manager' || $cek_role->name == 'Supply Chain & IT Support Manager') {
            	$dataNonShifting = $dataNonShifting->get();
            	$dataAll = $dataAll->get();
            } else{
            	$dataNonShifting = $dataNonShifting->where('users.nik',Auth::User()->nik)->get();
            	$dataAll = $dataAll->where('presence__shifting_user.nik',Auth::User()->nik)->get();
            }

    		$dataAll = $dataAll->merge($dataNonShifting);

    		if ($request->searchAll != "") {
    			$filtered = $dataAll->filter(function ($value, $key) use($request) { 
    				return stripos($value["project_name"], $request->searchAll) !== false ||
	                    stripos($value["name"], $request->searchAll) !== false ||
	                    stripos($value["pid"], $request->searchAll) !== false;
    			});
    		} else {
    			$filtered = $dataAll;
    		}

    		$dataCollect = collect();
    		foreach ($filtered as $key => $value) {
	    		$count = DB::table('ticketing__user')->where('nik',$value->nik)->count();
	    		$dataCollect->push([
	    			"name" => $value->name,
	    			"id_location" =>$value->id_location,
	    			"nik" => $value->nik,
	    			"project_name" => $value->project_name,
	    			"count"	=> $count
	    		]);
	    	}
    	} else {

	    	$dataAll = DB::table('presence__shifting_user')
	    			->join('presence__shifting_project', 'presence__shifting_project.id', 'presence__shifting_user.shifting_project')
	    			->join('users','users.nik','presence__shifting_user.nik')
	    			->join('ticketing__user','ticketing__user.nik','presence__shifting_user.nik')
	    			->join('tb_id_project','tb_id_project.id_project','ticketing__user.pid')
	    			->selectRaw('GROUP_CONCAT(DISTINCT `users`.`name`) AS `name`, GROUP_CONCAT(DISTINCT `presence__shifting_user`.`nik`) AS `nik`')
	    			->selectRaw('GROUP_CONCAT( DISTINCT `pid`, " - ", `name_project`) AS `pid`')
	    			->selectRaw('presence__shifting_project.project_name')->where('status_karyawan','!=','dummy')->groupBy('presence__shifting_project.project_name');

	    	if ($cek_role->name == 'Synergy System & Services Manager' || $cek_role->name == 'Supply Chain & IT Support Manager') {
            	$dataAll = $dataAll;
            } else{
            	$dataAll = $dataAll->where('presence__shifting_user.nik',Auth::User()->nik);
            }

	    	$searchFields = ['presence__shifting_project.project_name', 'name', 'pid'];

	    	if($request->searchAll != ""){
	            $dataAll->where(function($dataAll) use($request, $searchFields){
	                $searchWildCard = '%'. $request->searchAll . '%';
	                foreach ($searchFields as $data) {
	                    $dataAll->orWhere($data, 'LIKE', $searchWildCard);
	                }
	            });
        	}

	    	$dataAll = $dataAll->get();

	    	$dataCollect = collect();

	    	foreach ($dataAll as $key => $value) {
	    		$count = DB::table('ticketing__user')->join('presence__shifting_user','presence__shifting_user.nik','ticketing__user.nik')->join('presence__shifting_project', 'presence__shifting_project.id', 'presence__shifting_user.shifting_project')->select('pid')->where('project_name',$value->project_name)->distinct()->get();


	    		$dataCollect->push([
	    			"name" => $value->name,
	    			"nik" => $value->nik,
	    			"id_location" => DB::table('presence__shifting_project')->where('project_name',$value->project_name)->first()->id,
	    			"project_name" => $value->project_name,
	    			"count" => $count->count('pid')
	    		]);
	    	}
	    }

	    return $dataCollect;
    }

   	public function getCategorybyClient(Request $request)
   	{
   		$getId = AssetMgmt::join('tb_asset_management_detail','tb_asset_management_detail.id_asset','tb_asset_management.id')->select('tb_asset_management_detail.id_asset','detail_lokasi','tb_asset_management_detail.id');
        $getLastId = DB::table($getId,'temp')->groupBy('id_asset')->selectRaw('MAX(`temp`.`id`) as `id_last_asset`')->selectRaw('id_asset');

        $data = DB::table($getLastId, 'temp2')->join('tb_asset_management','tb_asset_management.id','temp2.id_asset')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')
            ->select('category as id', 'category as text')->where('category','like','%'.request('q').'%');

   		if ($request->client == "INTERNAL") {
	        $data = $data->where('tb_asset_management_detail.pid',$request->client)->distinct()->get();
   		}elseif($request->client == 'ADMF'){
   			$cus_legal_name = TB_Contact::where('code',$request->client)->first()->customer_legal_name;

	        $data = $data->where('tb_asset_management_detail.client',$cus_legal_name)->distinct()->get();
   		}else{
   			$cus_legal_name = TB_Contact::where('id_customer',$request->client)->first()->customer_legal_name;

	        $data = $data->where('tb_asset_management_detail.client',$cus_legal_name)->distinct()->get();
   		}
   		
        return $data; 
   	}

   	public function getCategorybyAsset(Request $request)
   	{
   		$getId = AssetMgmt::join('tb_asset_management_detail','tb_asset_management_detail.id_asset','tb_asset_management.id')->select('tb_asset_management_detail.id_asset','detail_lokasi','tb_asset_management_detail.id');
        $getLastId = DB::table($getId,'temp')->groupBy('id_asset')->selectRaw('MAX(`temp`.`id`) as `id_last_asset`')->selectRaw('id_asset');

        $data = DB::table($getLastId, 'temp2')->join('tb_asset_management','tb_asset_management.id','temp2.id_asset')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')
            ->select('category as id', 'category as text')->where('category','like','%'.request('q').'%');

	    $data = $data->where('tb_asset_management_detail.id_asset',$request->id_asset)->distinct()->get();

   		// if ($request->client == "INTERNAL") {
	    //     $data = $data->where('tb_asset_management_detail.pid',$request->client)->distinct()->get();
   		// }elseif($request->client == 'ADMF'){
   		// 	$cus_legal_name = TB_Contact::where('code',$request->client)->first()->customer_legal_name;

	    //     $data = $data->where('tb_asset_management_detail.client',$cus_legal_name)->distinct()->get();
   		// }else{
   		// 	$cus_legal_name = TB_Contact::where('id_customer',$request->client)->first()->customer_legal_name;

	    //     $data = $data->where('tb_asset_management_detail.client',$cus_legal_name)->distinct()->get();
   		// }
   		
        return $data; 
   	}

    public function getPidByPic(Request $request)
    {
    	$client_acronym_long = '';
    	if ($request->client_acronym == 'BPJS') {
    		$client_acronym = 'BKES';
    		$client_acronym_long = $client_acronym;
    	} elseif($request->client_acronym == 'PBLG'){
    		$client_acronym = 'BULG';
    		$client_acronym_long = $client_acronym;
    	} elseif($request->client_acronym == 'BGDN'){
    		$client_acronym = 'PGAN';
    		$client_acronym_long = $client_acronym;
    	} elseif($request->client_acronym == 'BJBR'){
    		$client_acronym = 'BBJB';
    	} elseif($request->client_acronym == 'BBJB'){
    		$client_acronym_long = 'BANK JABAR';
    		$client_acronym = $request->client_acronym;
    	} elseif($request->client_acronym == 'ADRF'){
    		$client_acronym = 'ADMF';
    		$client_acronym_long = $client_acronym;
    	} elseif($request->client_acronym == 'BTNI'){
    		$client_acronym = 'BBTN';
    	} else {
    		$client_acronym = $request->client_acronym;
    		$client_acronym_long = $client_acronym;
    	}

    	$cek_role = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role','group','mini_group')->where('user_id',Auth::User()->nik)->first();

    	if($cek_role->name_role == 'Chief Operating Officer' || $cek_role->name_role == 'Synergy System & Services Manager'){
    		$data = SalesProject::join('ticketing__user','tb_id_project.id_project','ticketing__user.pid')
    			->select(DB::raw('`tb_id_project`.`id_project` AS `id`,CONCAT(`id_project`," - ", `name_project`) AS `text`'))
    			->where('pid', 'like', '%'.$client_acronym.'%')->distinct()->get();
    	} else {
    		if ($cek_role->name_role == "Customer Support Center" || $cek_role->name_role == 'IT Internal') {
    			if ($request->client_acronym == "INTR") {
    				$data = collect([
    					['id' => 'INTERNAL',   // Replace with your desired ID
			            'text' => 'INTERNAL']
			        ]);
    			}elseif ($request->client_acronym == "ADMF") {
    				$data = collect([
    					['id' => 'ADMF',   // Replace with your desired ID
			            'text' => 'ADMF']
			        ]);
    			}else{
    				$data = DB::table('tb_id_project')
    					->join('ticketing__user','tb_id_project.id_project','ticketing__user.pid')
    					->select(DB::raw('`tb_id_project`.`id_project` AS `id`,CONCAT(`id_project`," - ", `name_project`) AS `text`'))
    					->where('pid', 'like', '%'.$client_acronym.'%')
    					->groupBy('id_project','name_project')
    					->get();
    			}				
    		}else{
    			$data = DB::table('tb_id_project')
	    			->join('ticketing__user','tb_id_project.id_project','ticketing__user.pid')
	    			->select(DB::raw('`tb_id_project`.`id_project` AS `id`,CONCAT(`id_project`," - ", `name_project`) AS `text`'))
	    			->whereRaw(
                        "(`pid` LIKE ? AND `ticketing__user`.`nik` = ? OR `pid` LIKE ? AND `ticketing__user`.`nik` = ?)", 
                        ['%'.$client_acronym.'%', Auth::User()->nik, '%'.$client_acronym_long.'%', Auth::User()->nik]
                    )
    				->groupBy('id_project','name_project')
	    			->get();
    		}	
    		// $data = DB::table('ticketing__user')->select(DB::raw('`pid` AS `id`,`pid` AS `text`'))->where('pid', 'like', '%'.$client_acronym.'%')->distinct()->get();
    	} 		
    	
    	return $data;
    }

    public function getPidAssigned(Request $request)
    {
    	if ($request->client_acronym == 'BPJS') {
    		$client_acronym = 'BKES';
    	} elseif($request->client_acronym == 'PBLG'){
    		$client_acronym = 'BULG';
    	} elseif($request->client_acronym == 'BGDN'){
    		$client_acronym = 'PGAN';
    	} elseif($request->client_acronym == 'ADRF'){
    		$client_acronym = 'ADMF';
    	} elseif($request->client_acronym == 'BJBR'){
    		$client_acronym = 'BBJB';
    	} elseif($request->client_acronym == 'BTNI'){
    		$client_acronym = 'BBTN';
    	} else{
    		$client_acronym = $request->client_acronym;
    	}

    	$data = DB::table('ticketing__user')->select(DB::raw('`pid` AS `id`,`pid` AS `text`'))->where('pid', 'like', '%'.$client_acronym.'%')->distinct()->get();

    	return $data;
    }

    public function getIdAtm(Request $request)
    {
    	$data = TicketingATM::select(DB::raw('`id` AS `id`,`atm_id` AS `text`'))->where('atm_id','like','%'.request('q').'%')->where('engineer_atm',null)->get();
    	return response()->json($data);
    }

    public function getEngineer(Request $request)
    {
    	return $data = User::select(DB::raw('`name` AS `id`,`name` AS `text`'))->where('id_division','MSM')->where('status_karyawan','!=','dummy')->where('name','like','%'.request('q').'%')->get();
    }

    public function assignEngineer(Request $request)
    {
    	$data = json_decode($request->arrListEngineerAssign,true);

    	foreach ($data as $value) {
    		foreach ($value['atm_id'] as $values) {
    			$update = TicketingATM::where('id',$values)->first();
    			$update->engineer_atm = $value['engineer'];
    			$update->update();
    		}
    	}
    }

    public function setUpdateSeverity(Request $request){
    	$updateDetail = TicketingDetail::where('id_ticket',$request->id_ticket)->first();
    	$updateDetail->severity = $request->severity;

    	$activityTicketUpdate = new TicketingActivity();
		$activityTicketUpdate->id_ticket = $request->id_ticket;
		$activityTicketUpdate->date = date("Y-m-d H:i:s.000000");
		$activityTicketUpdate->activity = Ticketing::where('id_ticket',$request->id_ticket)->first()->lastest_activity_ticket->activity;
		$activityTicketUpdate->operator = Auth::user()->name;
		$activityTicketUpdate->note = "Update Severity " . TicketingSeverity::where('id',$updateDetail->severity)->first()->name . " to " . TicketingSeverity::where('id',$request->severity)->first()->name;
		$activityTicketUpdate->save();

		$cek_client_pid = Ticketing::where("id_ticket",$request->id_ticket)->first();
		if ($cek_client_pid->id_client_pid) {
			$clientIdFilter = Ticketing::where('id_ticket',$request->id_ticket)
				->first()->id_client_pid;

			$cek_code = TicketingEmailSetting::where('id',$clientIdFilter)->first()->client;

			if ($cek_code == "INTERNAL") {
				$clientIdFilter = 'INTERNAL';
			}else{
				$customer = explode("- ", $cek_code)[1];

				$id_client = TB_Contact::where('customer_legal_name', 'LIKE', '%'.$customer.'%')->first()->id_customer;
				$clientIdFilter = $id_client;
			}			
		}else{
			$clientIdFilter = Ticketing::with('client_ticket')
				->where('id_ticket',$request->id_ticket)
				->first()
				->client_ticket
				->id;

			$cek_code = TicketingClient::where('id',$clientIdFilter)->first()->client_acronym;

			if ($cek_code == 'BPJS') {
	    		$cek_code = 'BKES';
	    	} elseif($cek_code == 'PBLG'){
	    		$cek_code = 'BULG';
	    	} elseif($cek_code == 'BGDN'){
	    		$cek_code = 'PGAN';
	    	} elseif($cek_code == 'BJBR'){
	    		$cek_code = 'BBJB';
	    	} elseif($cek_code == 'ADRF'){
	    		$cek_code = 'ADMF';
	    	} elseif($cek_code == 'BTNI'){
	    		$cek_code = 'BBTN';
	    	} else {
	    		$cek_code = $cek_code;
	    	}

			$id_client = TB_Contact::where('code',$cek_code)->first()->id_customer;
			$clientIdFilter = $id_client;
		}

		$activityTicketUpdate->client_id_filter = $clientIdFilter;

    	$updateDetail->save();

    	return $activityTicketUpdate;
    }

    public function getSLAProject()
    {
    	$data = SLAProject::orderBy('id','asc')->get();

    	return array("data"=>$data);
    }

    public function makeExportTicketPerformance(Request $req){
		// Create new Spreadsheet object
		$spreadsheet = new Spreadsheet();

		// Set document properties
		$title = '';
		if (isset($req->start) && isset($req->end)) {
			$title = 'Report Ticket ' . '(' . Carbon::parse($req->start)->format('d F Y H:i') . ' - ' . Carbon::parse($req->end)->format('d F Y H:i') . ')'; 
		}else{
			$currentYear = Carbon::now()->year;
			$startOfYear = Carbon::createFromDate($currentYear)->startOfYear();
			$endOfYear = Carbon::createFromDate($currentYear)->endOfYear();
			$title = 'Report Ticket ' . '(' . $startOfYear->format('d F Y H:i') . ' - ' . $endOfYear->format('d F Y H:i') . ')';
		}

		$spreadsheet->getProperties()->setCreator('SIP')
			->setLastModifiedBy('SIMS-APP')
			->setTitle($title);

		$ticketSheet = new Worksheet($spreadsheet,'Ticket - Reporting');
        $spreadsheet->addSheet($ticketSheet);
        $spreadsheet->removeSheetByIndex(0);
        $sheet = $spreadsheet->getActiveSheet();

        $normalStyle = [
            'font' => [
                'name' => 'Calibri',
                'size' => 11
            ],
        ];

        $titleStyle = $normalStyle;
        $titleStyle['alignment'] = ['horizontal' => Alignment::HORIZONTAL_CENTER];
        $titleStyle['font']['bold'] = true;

        $sheet->getStyle('A1:O1')->applyFromArray($titleStyle);

        $headerStyle = $normalStyle;
        $headerStyle['font']['bold'] = true;
        $headerStyle['fill'] = ['fillType' => Fill::FILL_SOLID, 'startColor' => ["argb" => "FFFCD703"]];
        $head['borders'] = ['outline' => ['borderStyle' => Border::BORDER_THIN]];
        $sheet->getStyle('A1:O1')->applyFromArray($headerStyle);;

        $headerContent = ["ID Ticket",	
        				 "Asset", 
        				 "Serial Number",
        				 "Ticket Number", 
        				 "Open",
        				 "Location - Problem", 
        				 "PIC",
        				 "Severity",
        				 "Status",
        				 "Response Time (Minute)",
        				 "Status Response Time", 
        				 "Resolution Time (Minute)", 
        				 "Status Resolution Time", 
        				 "Operator",
        				 "Engineer"
        				];

        $sheet->fromArray($headerContent,NULL,'A1');  

        $dataPost = [];
        if (isset($req->client)) {
        	$dataPost['client'] = [$req->client];
        }

        if (isset($req->pid)) {
        	$dataPost['pid'] = $req->pid;
        }

        if (isset($req->start)) {
        	$dataPost['startDate'] = $req->start;
        }

        if (isset($req->end)) {
        	$dataPost['endDate'] = $req->end;
        }

        if (isset($req->severity)) {
        	$dataPost['severity'] = $req->severity;
        }

        if (isset($req->type)) {
        	$dataPost['type'] = [$req->type];
        }

        if (isset($req->activity)) {
        	$dataPost['activity'] = $req->activity;
        }

        if (isset($req->attention)) {
        	$dataPost['attention'] = true;
        }

        $dataPost['export'] = true;

        $request = Request::create('/getPerformanceByFilter', 'POST', $dataPost);

		$valueRequest = $this->getPerformanceByFilter($request);

        $valueRequest = $valueRequest['data']->map(function($item,$key) use ($sheet){
        	$open_time = Carbon::parse($item->first_activity_ticket->date)->format('d F Y H:i');
			$problem = $item->location . " -  " . $item->problem;

			if($item->type_ticket == "TT"){
				$item->type_ticket = "Trouble Ticket";
			} elseif ($item->type_ticket == "PM"){
				$item->type_ticket = "Preventive Maintenance";
			} elseif ($item->type_ticket == "PL"){
				$item->type_ticket = "Permintaan Layanan";
			} elseif ($item->type_ticket == "PP"){
				$item->type_ticket = "Permintaan Penawaran";
			}

			if($item->severity == 1){
				$item->severity_numerical = 1;
				$item->severity = $item->type_ticket . ' - Critical';
			} elseif($item->severity == 2) {
				$item->severity_numerical = 2;
				$item->severity = $item->type_ticket . ' - Major';
			} elseif($item->severity == 3) {
				$item->severity_numerical = 3;
				$item->severity = $item->type_ticket . ' - Moderate';
			} elseif($item->severity == 4) {
				$item->severity_numerical = 4;
				$item->severity = $item->type_ticket . ' - Minor';
			} elseif($item->severity == 0){
				$item->severity_numerical = 0;
				$item->severity = $item->type_ticket;
			}

			$sheet->setCellValueExplicit('A'.($key + 2),$item->id_ticket,DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('B'.($key + 2),$item->id_atm,DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('C'.($key + 2),$item->serial_device,DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('D'.($key + 2),(string)$item->ticket_number_3party,DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('E'.($key + 2),$open_time,DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('F'.($key + 2),$problem,DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('G'.($key + 2),$item->pic,DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('H'.($key + 2),$item->severity,DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('I'.($key + 2),$item->lastest_activity_ticket->activity,DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('J'.($key + 2),$item->response_time_percentage,DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('K'.($key + 2),$item->highlight_sla_response,DataType::TYPE_STRING);
			$sheet->setCellValueExplicit('L'.($key + 2),$item->sla_resolution_percentage,DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('M'.($key + 2),$item->highlight_sla_resolution,DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('N'.($key + 2),$item->lastest_activity_ticket->operator,DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('O'.($key + 2),$item->engineer,DataType::TYPE_STRING);
        });

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setWidth(50);
        $sheet->getColumnDimension('G')->setWidth(20);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getColumnDimension('J')->setAutoSize(true);
        $sheet->getColumnDimension('K')->setAutoSize(true);
        $sheet->getColumnDimension('L')->setAutoSize(true);
        $sheet->getColumnDimension('M')->setAutoSize(true);
        $sheet->getColumnDimension('N')->setAutoSize(true);
        $sheet->getColumnDimension('O')->setAutoSize(true);

        $name = $title . '.xlsx';        
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
		$location = public_path() . '/report/' . $name;
		ob_end_clean();
		$writer->save($location);
		return $name;
	}

	public function setNotif($engineerName, $idTicket, $location)
	{
		$leader = DB::table('roles as a')
						->leftjoin('role_user as b', 'a.id', 'b.role_id')
						->leftjoin('users as c', 'b.user_id', 'c.nik')
						->select('c.name as name', 'c.email as email','c.nik as nik', 'c.telegram_id as id_telegram')
						->where('a.name', 'Synergy System & Services Manager')
						->first();

        $oldJob = DB::table('slm_notification_job')->where('id_ticket', $idTicket)
            ->where('notification_type', 'Assign Engineer')->get();

        if(!empty($oldJob)){
            foreach($oldJob as $job){
                DB::table('slm_notification_job')->where('id', $job->id)->update(['status' => 'Cancel']);
            }
        }

        if ($engineerName != null || $engineerName != ''){
            $engineer = DB::table('users')
                ->where('name', 'like', '%'.$engineerName.'%')
                ->select('telegram_id as id_telegram', 'nik')
                ->first();


            DB::table('slm_notification_job')->insert([
                'id_ticket' => $idTicket,
                'id_telegram' => $leader->id_telegram,
                'message' => 'Ticket dengan ID: '. $idTicket .' belum di pick oleh engineer silahkan lakukan assign engineer ulang pada ticket tersebut.',
                'nik' => $leader->nik,
                'notification_type' => 'Assign Engineer',
                'push_at' => Carbon::now()->addMinutes(5),
                'status' => 'Belum Dikirim',
                'created_at' => Carbon::now()
            ]);

            $message = 'Hai, '.$engineerName.'. Anda memiliki ticket baru dengan ID: '. $idTicket .' Lokasi: '.$location.'. Segera accept tiket tersebut di SLM App.';

            $chatIDGroup = env('TELEGRAM_GROUP_CHAT_ID');
            // $this->telegramService->sendMessage($chatIDGroup, $message);
            if(!empty($chatIDGroup)){
                $this->telegramService->sendMessage($chatIDGroup, $message);
            }

            if(!empty($engineer) && $engineer->id_telegram){
                $this->telegramService->sendMessage($engineer->id_telegram, $message);
            }
        } else {
            $message = 'Hai, '.$leader->name.'. Ticket dengan ID: '. $idTicket .' Lokasi: '.$location.'. Tidak memiliki engineer / engineer belum di set. Segera update ticket pada SIMS App agar tiket dapat segera ditangani. Terima kasih.';

            $chatIDGroup = env('TELEGRAM_GROUP_CHAT_ID');
            if(!empty($chatIDGroup)){
                $this->telegramService->sendMessage($chatIDGroup, $message);
            }

            if($leader->id_telegram){
                $this->telegramService->sendMessage($leader->id_telegram, $message);
            }
        }

	}
}
