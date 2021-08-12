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
use App\TicketingPendingReminder;
use App\TicketingEscalateEngineer;

use Auth;
use Mail;

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
			->where('situation','=',1)
			->get();

		return view('ticketing.index')
			->with([
				'clients' => $clients,
				'initView' => $this->initMenuBase(),
				'sidebar_collapse' => 'True'
			]);
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
			->pluck('client_acronym');
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
			]
		]);
	}

	public function getCreateParameter(){
		$client = TicketingClient::where('situation','=',1)->orderBy('client_acronym')->get();
		$severity = TicketingSeverity::all();
		
		return array(
			$client->pluck('client_acronym'),
			$severity->pluck('id'),
			$severity->pluck('name'),
			$severity->pluck('description'),
		);
	}

	public function getReserveIdTicket(){
		return Ticketing::orderBy('id','DESC')->first()->id + 1;
	}

	public function setReserveIdTicket(Request $req){

		$newTicketId = new Ticketing();
		$newTicketId->id = $req->id;
		$newTicketId->id_ticket = $req->id_ticket;
		$client = TicketingClient::where('client_acronym',$req->acronym_client);
		$newTicketId->id_client = $client->value('id');
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
		$client = TicketingClient::where('client_acronym',$req->acronym_client);
		$updateTicketId->id_client = $client->value('id');

		$updateTicketId->save();
		return collect([
			"banking" => $client->value('banking'),
			"wincor" => $client->value('wincor')
		]);
	}

	public function getAtmId(Request $request){
		$client_acronym = $request->acronym;
		if($request->acronym == "BDIYCCTV" || $request->acronym == "BDIYUPS"){
			$client_acronym = "BDIY";
		}
		return TicketingATM::where('owner',TicketingClient::where('client_acronym',$client_acronym)->first()->id)
			->select(
				'id',
				DB::raw('CONCAT(`atm_id`," - ", `location`) AS `text`')
			)
			->get()->all();
	}

	public function getAtmDetail(Request $request){
		return TicketingATM::where('id',$request->id_atm)->first();
	}

	public function getAbsenDetail(Request $request){
		return TicketingAbsen::where('id',$request->id_absen)->first();
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
			return $result = TicketingClient::where('client_acronym',$req->client)
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

	public function getOpenMailTemplate(Request $req){
		if($req->type == "normal") {
			return view('ticketing.mail.OpenTicket');
		} else if($req->type == "wincor") {
			return view('ticketing.mail.OpenTicketWincor');
		}
	}

	public function sendEmailOpen(Request $request){
		$this->sendEmail($request->to,$request->cc,$request->subject,$request->body);

		$detailTicketOpen = new TicketingDetail();
		$detailTicketOpen->id_ticket = $request->id_ticket;
		if($request->absen == "-"){
			$detailTicketOpen->id_atm = $request->id_atm;
		} else {
			$detailTicketOpen->id_atm = $request->absen;
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

		$detailTicketOpen->save();

		$activityTicketOpen = new TicketingActivity();
		$activityTicketOpen->id_ticket = $request->id_ticket;
		$activityTicketOpen->date = date("Y-m-d H:i:s.000000");
		$activityTicketOpen->activity = "OPEN";
		$activityTicketOpen->operator = Auth::user()->name;
		$activityTicketOpen->note = "Open Ticket";

		$activityTicketOpen->save();

		$clientAcronymFilter = Ticketing::with('client_ticket')
			->where('id_ticket',$request->id_ticket)
			->first()
			->client_ticket
			->client_acronym;
		$activityTicketOpen->client_acronym_filter = $clientAcronymFilter;
		// return $activityTicketOpen;
	}

	public function sendEmail($to, $cc, $subject, $body){
		Mail::html($body, function ($message) use ($to, $cc, $subject) {
			$message
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

		$residual_ticket_result = TicketingDetail::with([
				'first_activity_ticket:id_ticket,date,operator',
				'lastest_activity_ticket',
				'id_detail:id_ticket,id',
			])
			->whereNotIn('id_ticket',$occurring_ticket)
			->limit((100 - $occurring_ticket->count()))
			->orderBy('id','DESC')
			->get();

		$result = $occurring_ticket_result->merge($residual_ticket_result);

		return array("data" => $result);

	}

	public function getPerformanceByClient(Request $request){
		$start = microtime(true);
		$client_acronym = $request->client;

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
}
