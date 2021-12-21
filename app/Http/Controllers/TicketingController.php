<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

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

use Auth;
use Mail;
use Blade;

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

class TicketingController extends Controller
{
	public function index() {

		$clients = DB::table('ticketing__client')
			->select('id','client_name','client_acronym','open_dear','close_dear')
			->selectRaw("REPLACE(`open_to`,';','<br>') AS `open_to`")
			->selectRaw("REPLACE(`open_cc`,';','<br>') AS `open_cc`")
			->selectRaw("REPLACE(`close_to`,';','<br>') AS `close_to`")
			->selectRaw("REPLACE(`close_cc`,';','<br>') AS `close_cc`")
			// ->where('situation','=',1)
			->get();

		return view('ticketing.index')
			->with([
				'clients' => $clients,
				'initView' => $this->initMenuBase(),
				'sidebar_collapse' => 'True'
			]);
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

	public function getDashboard() {
		// $start = microtime(true);

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
				        GROUP BY
				            `id_ticket`
				    )
				GROUP BY
		    `activity`) AS `ticketing_activity`"),'ticketing_activity.activity','=','ticketing__condition.name','left')
		    ->get()->keyBy('name');

		// return $result2;

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
		// return $result2;

		$get_client = DB::table('ticketing__client')
			->select('id','client_name','client_acronym')
			->where('situation','=','1')
			->get();
		// return $get_client;

		$count_ticket_by_client = DB::table('ticketing__id')
			->selectRaw('`ticketing__client`.`client_acronym`, COUNT(*) AS ticket_count')
			->groupBy('ticketing__id.id_client')
			->join('ticketing__client','ticketing__client.id','=','ticketing__id.id_client')
			->where('ticketing__client.situation','=','1')
			->orderBy('ticket_count','DESC')
			->limit(10)
			->get();
		// return $count_ticket_by_client;

		$needed = DB::table('ticketing__activity')
			->select('ticketing__detail.id', 'ticketing__detail.id_ticket', 'ticketing__detail.id_atm', 'ticketing__detail.location', 'ticketing__activity.operator', 'ticketing__activity.date','ticketing__detail.severity')
			->join('ticketing__detail','ticketing__detail.id_ticket','=','ticketing__activity.id_ticket')
			->whereIn('ticketing__activity.id', function ($query){
					$query->selectRaw("MAX(`ticketing__activity`.`id`) AS `activity`")
						->from('ticketing__activity')
						->groupBy('id_ticket');
				})
			->where('activity','<>','CLOSE')
			->where('activity','<>','CANCEL')
			->orderBy('ticketing__detail.severity','ASC')
			->limit(10)
			->get();
		// return $needed;

		$severity_count = DB::table('ticketing__detail')
			->select('ticketing__severity.name',DB::raw('COUNT(*) as count'))
			->join('ticketing__severity','ticketing__severity.id','=','ticketing__detail.severity')
			->where('ticketing__detail.severity','<>',0)
			->groupBy('ticketing__detail.severity')
			->get()
			->keyBy('name');

		$severity_count = $severity_count->map(function($item, $key){
			return $item->count;
		});

		$severity_label = TicketingSeverity::select('id','name')->orderBy('id','DESC')->get();

		// $time_elapsed_secs = microtime(true) - $start;
		// return $time_elapsed_secs;

		return collect([
			"counter_condition" => $result2,
			"counter_severity" => $severity_count,
			"occurring_ticket" => $needed,
			"customer_list" => $get_client,
			"chart_data" => [
				"label" => $count_ticket_by_client->pluck('client_acronym'),
				"data" => $count_ticket_by_client->pluck('ticket_count')
			],
			"severity_label" => $severity_label
		]);
	}

	public function getCreateParameter(){
		$client = TicketingClient::where('situation','=',1)
			->select('id','client_name','client_acronym')
			->orderBy('client_acronym')
			->get();

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
		$newTicketId->id = $req->id;
		$newTicketId->id_ticket = $req->id_ticket;
		$client = TicketingClient::find($req->id_client);
		$newTicketId->id_client = $client->id;
		$newTicketId->operator = Auth::user()->name;

		$newTicketId->save();
		return collect([
			"banking" => $client->value('banking'),
			"wincor" => $client->value('wincor')
		]);
	}

	public function putReserveIdTicket(Request $req){

		$updateTicketId = Ticketing::where('id_ticket',$req->id_ticket_before)->first();
		$updateTicketId->id_ticket = $req->id_ticket_after;
		$client = TicketingClient::find($req->id_client);
		$updateTicketId->id_client = $client->id;

		$updateTicketId->save();
		return collect([
			"banking" => $client->banking,
			"wincor" => $client->wincor
		]);
	}

	public function getAtmId(Request $request){
		if($request->acronym == "BDIY"){
			$request->client_id = 19;
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

	public function getEmailData(Request $req){
		if(isset($req->client)){
			return TicketingClient::where('id',$req->client)
				->first();
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


			$ticket_reciver = Ticketing::where('id',$idTicket)
				->first()
				->client_ticket;

			if(isset($ticket_data->id_atm)){
				if($ticket_reciver->client_acronym == "BDIYUPS"){
					$ticket_data->atm_detail = TicketingATMPeripheral::where('id_atm',TicketingATM::where('atm_id',$ticket_data->id_atm)->first()->id)->where('type','UPS')->first();
				}
			} else {
				$ticket_data->atm_detail = null;
			}
			return collect([
				"ticket_data" => $ticket_data,
				"ticket_reciver" => $ticket_reciver
			]);
		}
	}

	public function getEmailTemplate(Request $req){
		$return = TicketingEmail::where('type','=',$req->email_type)
			->where('activity','=',$req->email_activity)
			->where('name','=',$req->email_name)
			->first()
			->body;
		return view(["template" => $return]);
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

		$detailTicketOpen = new TicketingDetail();
		$detailTicketOpen->id_ticket = $request->id_ticket;
		
		if($request->absen != "-"){
			$detailTicketOpen->id_atm = $request->absen;
		} else if($request->switchLocation != "-"){
			$detailTicketOpen->id_atm = $request->switchLocation;
		} else {
			if($request->id_atm != null){
				$atm = TicketingATM::find($request->id_atm);
				$detailTicketOpen->id_atm = $atm->atm_id;
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

		if($request->engineer != ""){
			$detailTicketOpen->engineer = $request->engineer;
		}

		$detailTicketOpen->save();

		$this->sendEmail($request->to,$request->cc,$request->subject,$request->body);

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
		return $activityTicketOpen;
	}

	public function sendEmail($to, $cc, $subject, $body){
		Mail::html($body, function ($message) use ($to, $cc, $subject) {
			$message
				->from('helpdesk@sinergy.co.id','Helpdesk Sinergy')
				->to(explode(";", $to))
				->subject($subject);

			if($cc != ""){
				$message->cc(explode(";", $cc));
			}
		});
	}

	public function getPerformanceAll(){
		// sleep(5);
		$occurring_ticket = DB::table('ticketing__activity')
			->select('id_ticket','activity')
			->whereIn('id',function ($query) {
				$query->select(DB::raw("MAX(id) AS activity"))
					->from('ticketing__activity')
					->groupBy('id_ticket');
				})
			->where('activity','<>','CANCEL')
			->where('activity','<>','CLOSE')
			->get()
			->pluck('id_ticket');

		$occurring_ticket_result = TicketingDetail::with([
				'first_activity_ticket:id_ticket,date,operator',
				'lastest_activity_ticket',
				'id_detail:id_ticket,id',
			])
			->whereIn('id_ticket',$occurring_ticket)
			->orderBy('id','DESC')
			->get();

		$limit = $occurring_ticket->count() > 100 ? 100 : 100 - $occurring_ticket->count();

		$residual_ticket_result = TicketingDetail::with([
				'first_activity_ticket:id_ticket,date,operator',
				'lastest_activity_ticket',
				'id_detail:id_ticket,id',
			])
			->whereNotIn('id_ticket',$occurring_ticket)
			->limit($limit)
			->orderBy('id','DESC')
			->get();

		$result = $occurring_ticket_result->merge($residual_ticket_result);

		return array("data" => $result);

	}

	public function getPerformanceByClient(Request $request){
		$start = microtime(true);
		$client_acronym = $request->client;
		$client_id = $request->client;

		$occurring_ticket = DB::table('ticketing__activity')
			->select('ticketing__activity.id_ticket','ticketing__activity.activity','ticketing__id.id_client')
			->whereIn('ticketing__activity.id',function ($query) {
				$query->select(DB::raw("MAX(id) AS activity"))
					->from('ticketing__activity')
					->groupBy('id_ticket');
				})
			->join('ticketing__id','ticketing__id.id_ticket','=','ticketing__activity.id_ticket')
			->where('ticketing__activity.activity','<>','CANCEL')
			->where('ticketing__activity.activity','<>','CLOSE')
			// ->where('ticketing__id.id_client','=',2)
			->get();

		$occurring_ticket_result = TicketingDetail::with([
				'first_activity_ticket:id_ticket,date,operator',
				'lastest_activity_ticket',
				'id_detail:id_ticket,id',
			])
			->whereIn('id_ticket',
				$occurring_ticket->where('id_client','=',DB::table('ticketing__client')
					->where('client_acronym','=',$client_acronym)
					->value('id')
				)
				->pluck('id_ticket')
			)
			->orderBy('id','DESC')
			->get();

		$finish_ticket = DB::table('ticketing__activity')
			->select('ticketing__activity.id_ticket','ticketing__activity.activity')
			->whereIn('ticketing__activity.id',function ($query) {
				$query->select(DB::raw("MAX(`id`) AS `activity`"))
					->from('ticketing__activity')
					->groupBy('id_ticket');
				})
			->whereRaw('`ticketing__activity`.`id_ticket` LIKE "%/' . $client_acronym . '/%"')
			->whereRaw('(`ticketing__activity`.`activity` = "CANCEL" OR `ticketing__activity`.`activity` = "CLOSE")')
			->orderBy('ticketing__activity.id','DESC')
			->take(100 - $occurring_ticket_result->count())
			->get()
			->pluck('id_ticket');

		$finish_ticket_result = TicketingDetail::with([
				'first_activity_ticket:id_ticket,date,operator',
				'lastest_activity_ticket',
				'id_detail:id_ticket,id',
			])
			->whereIn('id_ticket',$finish_ticket)
			->orderBy('id','DESC')
			->get();

		$result = $occurring_ticket_result->merge($finish_ticket_result);

		// $time_elapsed_secs = microtime(true) - $start;
		// return $time_elapsed_secs;

		return array("data" => $result);
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
				'id_detail:id_ticket,id,id_client'
			])
			->first();

		if(Ticketing::where('id',$idTicket)->first()->id_client == "29"){
			$result->machine_absen = TicketingAbsen::find($result->id_atm);
			return $result;
		} else {
			return $result;
		}

	}

	public function getPerformanceBySeverity(Request $req){
		$occurring_ticket = DB::table('ticketing__activity')
			->select('ticketing__activity.id_ticket','ticketing__activity.activity')
			->whereIn('ticketing__activity.id',function ($query) {
				$query->select(DB::raw("MAX(id) AS activity"))
					->from('ticketing__activity')
					->groupBy('id_ticket');
				})
			->where('ticketing__activity.activity','<>','CANCEL')
			->where('ticketing__activity.activity','<>','CLOSE')
			->get();

		$occurring_ticket_result = TicketingDetail::with([
				'first_activity_ticket:id_ticket,date,operator',
				'lastest_activity_ticket',
				'id_detail:id_ticket,id',
			])
			->whereIn('id_ticket',$occurring_ticket->pluck('id_ticket'))
			->where('severity',$req->severity)
			->orderBy('id','DESC')
			->get();

		$finish_ticket = DB::table('ticketing__activity')
			->select('ticketing__activity.id_ticket','ticketing__activity.activity')
			->whereIn('ticketing__activity.id',function ($query) {
				$query->select(DB::raw("MAX(`id`) AS `activity`"))
					->from('ticketing__activity')
					->groupBy('id_ticket');
				})
			->whereRaw('(`ticketing__activity`.`activity` = "CANCEL" OR `ticketing__activity`.`activity` = "CLOSE")')
			->orderBy('ticketing__activity.id','DESC')
			->get()
			->pluck('id_ticket');

		$finish_ticket_result = TicketingDetail::with([
				'first_activity_ticket:id_ticket,date,operator',
				'lastest_activity_ticket',
				'id_detail:id_ticket,id',
			])
			->whereIn('id_ticket',$finish_ticket)
			->where('severity',$req->severity)
			->take(100 - $occurring_ticket_result->count())
			->orderBy('id','DESC')
			->get();

		$result = $occurring_ticket_result->merge($finish_ticket_result);

		return array('data' => $result);
	}	

	public function setUpdateTicket(Request $req){

		if(isset($req->email)){
			$this->sendEmail($req->to,$req->cc,$req->subject,$req->body);
		}

		$detailTicketUpdate = TicketingDetail::where('id_ticket',$req->id_ticket)
			->first();

		$detailTicketUpdate->engineer = $req->engineer;
		$detailTicketUpdate->ticket_number_3party = $req->ticket_number_3party;

		$detailTicketUpdate->save();

		$this->checkPendingReminder($req->id_ticket);

		$activityTicketUpdate = new TicketingActivity();
		$activityTicketUpdate->id_ticket = $req->id_ticket;
		// $activityTicketUpdate->date = date("Y-m-d H:i:s.000000");
		$activityTicketUpdate->date = $req->timeOnProgress;
		$activityTicketUpdate->activity = "ON PROGRESS";
		$activityTicketUpdate->operator = Auth::user()->name;
		$activityTicketUpdate->note = $req->note;

		$activityTicketUpdate->save();

		$clientAcronymFilter = Ticketing::with('client_ticket')
			->where('id_ticket',$req->id_ticket)
			->first()
			->client_ticket
			->client_acronym;

		$activityTicketUpdate->client_acronym_filter = $clientAcronymFilter;
		
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

		$clientAcronymFilter = Ticketing::with('client_ticket')
			->where('id_ticket',$request->id_ticket)
			->first()
			->client_ticket
			->client_acronym;

		$activityTicketUpdate->client_acronym_filter = $clientAcronymFilter;
		
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

		return Ticketing::with('client_ticket')
			->where('id_ticket',$request->id_ticket)
			->first()
			->client_ticket
			->client_acronym;
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

		$clientAcronymFilter = Ticketing::with('client_ticket')
			->where('id_ticket',$request->id_ticket)
			->first()
			->client_ticket
			->client_acronym;

		$activityTicketUpdate->client_acronym_filter = $clientAcronymFilter;
		
		return $activityTicketUpdate;
	}

	public function getCloseMailTemplate(){
		return view('ticketing.mail.CloseTicket');
	}

	public function sendEmailClose(Request $request){
		$this->sendEmail($request->to,$request->cc,$request->subject,$request->body);

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

		$clientAcronymFilter = Ticketing::with('client_ticket')
			->where('id_ticket',$request->id_ticket)
			->first()
			->client_ticket
			->client_acronym;

		$activityTicketUpdate->client_acronym_filter = $clientAcronymFilter;
		
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
				"client_name" => $req->client_name,
				"client_acronym" => $req->client_acronym,
				"open_dear" => $req->open_dear,
				"open_to" => $req->open_to,
				"open_cc" => $req->open_cc,
				"close_dear" => $req->close_dear,
				"close_to" => $req->close_to,
				"close_cc" => $req->close_cc,
			]);
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
		    'atmOwner.required' => 'You must select ATM Owner!',
		    'atmLocation.required' => 'You must set ATM Location!',
		    'atmAddress.required' => 'You must select ATM Address!',
		    'atmActivation.required' => 'You must set ATM Activation date!',
		];

    	$validator = Validator::make($request->all(), [
			'atmID' => 'unique:ticketing__atm,atm_id',
			'atmSerial' => 'unique:ticketing__atm,serial_number',
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
				"activation" =>  Carbon::createFromFormat('d/m/Y',$request->atmActivation)->formatLocalized('%Y-%m-%d'),
				"note" => $request->atmNote,
				"machine_type" => $request->atmType,
			]);

		$newAtm->save();

	}

	public function newAtmPeripheral(Request $request){
		if(TicketingClient::find($request->atmOwner)->client_acronym == "BDIYCCTV"){
			$request->peripheralType = "CCTV";
		} else if (TicketingClient::find($request->atmOwner)->client_acronym == "BDIYUPS"){
			$request->peripheralType = "UPS";
		}
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
		$bulan = Carbon::createFromDate($req->year, $req->month + 1, 1)->format('M');

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
		
		$value1 = $this->getPerformanceByFinishTicket($client,$bulan . "/" . $req->year);
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

		$value1 = $this->getPerformance5($client,$bulan . "/" . $req->year);
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
			$writer->save($location);
			return $name;
		}
	}

	public function getPerformanceByFinishTicket($acronym_client,$period){
		$occurring_ticket = DB::table('ticketing__activity')
			->select('id_ticket','activity')
			->whereIn('id',function ($query) {
				$query->select(DB::raw("MAX(id) AS activity"))
					->from('ticketing__activity')
					->groupBy('id_ticket');
				})
			->where('activity','<>','CANCEL')
			->where('activity','<>','CLOSE')
			->whereRaw('`id_ticket` LIKE "%' . $acronym_client . '%"')
			->get()
			->pluck('id_ticket');

		$residual_ticket_result = TicketingDetail::with([
				'first_activity_ticket:id_ticket,date,operator',
				'lastest_activity_ticket',
				'id_detail:id_ticket,id',
				'resolve',
				'absen_machine'
			])
			->whereNotIn('id_ticket',$occurring_ticket)
			->whereRaw("`id_ticket` LIKE '%/" . $acronym_client . "/" . $period . "'")
			->orderBy('id','ASC')
			->get();

		return $residual_ticket_result;
	}

	public function getPerformance5($acronym_client,$period){
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
		$limitQuery = 500;

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
            ->joinSub($ticketing_activity_filtered,'ticketing_activity_filtered',function($join){
            	$join->on('ticketing_activity_filtered.id_ticket','=','ticketing__activity.id_ticket');
            })
            ->where('ticketing__activity.date','<',$request->end)
            ->groupBy('id_ticket');

        $latest_activity_filtered = $open_activity_filtered;

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
		    ->selectRaw("CONCAT('[',`ticketing__detail`.`location`,'] ',`ticketing__detail`.`problem`) AS `location_problem`")
		    ->selectRaw("DATE_FORMAT(`ticketing__detail`.`reporting_time`,'%c/%e/%Y %k:%i') AS `open_reporting_date`")
		    ->selectRaw("DATE_FORMAT(`open_activity_detail`.`open_date`,'%c/%e/%Y %k:%i') AS `open_date`")
		    ->selectRaw("DATE_FORMAT(`latest_activity_detail`.`latest_date`,'%c/%e/%Y %k:%i') AS `latest_date`")
		    ->selectRaw("`ticketing__severity`.`name`")
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
		    ->selectRaw("IF(IFNULL(`ticketing__resolve`.`root_couse`, '-') = '-',IF(`latest_activity_detail`.`latest_activity` = 'CANCEL',TIMEDIFF(`latest_activity_detail`.`latest_date`,`open_activity_detail`.`open_date`),'-'),TIMEDIFF(`latest_activity_detail`.`latest_date`,`open_activity_detail`.`open_date`)) AS `resolution_time`")
			->joinSub($latest_activity_detail,'latest_activity_detail',function($join){
				$join->on('open_activity_detail.id_ticket','=','latest_activity_detail.id_ticket');
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
			->orderBy('open_activity_detail.id_ticket','ASC')
			->get();

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

	    $summarySheet->getStyle('A1:R1')->applyFromArray($titleStyle);
	    $summarySheet->getStyle('A2:R2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
	    $summarySheet->getStyle('A2:R2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
	    $summarySheet->getStyle('C2:R2')->getAlignment()->setWrapText(true);
	    $summarySheet->getStyle('C2:R2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
	    $summarySheet->setCellValue('B1','Report Bayu');
	    $summarySheet->setCellValue('D1','Grab per ' . Carbon::now()->format("d M Y"));

	    $headerContent = [
			"id_ticket",
			"ticket_number_3party",
			"location_problem",
			"open_reporting_date",
			"open_date",
			"latest_date",
			"name",
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
			"resolution_time"
		];
	    $summarySheet->getStyle('A2:R2')->applyFromArray($headerStyle);
	    
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

	    $spreadsheet->setActiveSheetIndex(0);

	   
	    $name = 'Report_Bayu_-_[' . $request->start . '_to_' . $request->end . ']_(' . date("Y-m-d") . ')_' . Auth::user()->name . '.xlsx';
		$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
		$location = public_path() . '/report/bayu/' . $name;
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

		$ticketing_activity_occurring_all = DB::table('ticketing__activity')
			->joinSub($ticketing_activity_occurring,'ticketing_activity_occurring',function($join){
				$join->on('ticketing_activity_occurring.id_ticket','=','ticketing__activity.id_ticket');
			});

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
  //   	$latest_activity_table = DB::table(function ($query) use ($request){
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
		$writer->save($location);
		return $name;

		return $latest_activity_table->get();
	}

}
