<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use Mail;
use App\Mail\EmailRemainderWeekly;
use App\Mail\MailResult;
use App\Mail\mailPID;
use App\Notifications\Testing;
use Notification;
use App\Notifications\NewLead;
use App\PID;
use Auth;
use DB;
// use App\Notifications\Result;


class TestController extends Controller
{
	public function send_mail(){
		// $email = 'faiqoh@sinergy.co.id';
  //       Notification::route('mail', $email)->notify(new NewLead($email));  

  			$to = User::select('email','name')->where('id_position', 'STAFF')->where('id_division', 'TECHNICAL')->where('id_territory', 'DVG')->get();

  			$users = User::select('name')->where('id_division','FINANCE')->where('id_position','MANAGER')->first();

  			$pid_info = Sales::join('sales_tender_process','sales_tender_process.lead_id','=','sales_lead_register.lead_id')
                  ->join('tb_pid','tb_pid.lead_id','=','sales_lead_register.lead_id')->select('lead_id','no_po','amount_pid','quote_number2')->first(); 
            // Mail::to($users, new Result());

        foreach ($to as $data) {
        	Mail::to($data->email)->send(new MailResult($users,$pid_info));
        }
	}

  public function testRemainderEmail(){
    $parameterEmail = collect([
      "to" => DB::table('users')->where('nik',1150991080)->first()->name,
      "proses_count" => DB::table('sales_lead_register')->where('nik',1150991080)->whereRaw('(`result` = "SD" OR `result` = "TP")')->count(),
      "tp_count" => DB::table('sales_lead_register')->where('nik',1150991080)->where('result' ,'TP')->count(),
      "tp_detail" => DB::table('sales_lead_register')
        ->select('sales_lead_register.lead_id','tb_contact.brand_name','sales_lead_register.opp_name')
        ->join('tb_contact','sales_lead_register.id_customer','=','tb_contact.id_customer')
        ->where('nik',1150991080)
        ->where('result' ,'TP')
        ->get(),
      "sd_count" => DB::table('sales_lead_register')->where('nik',1150991080)->where('result' ,'SD')->count(),
      "sd_detail" => DB::table('sales_lead_register')
        ->select('sales_lead_register.lead_id','tb_contact.brand_name','sales_lead_register.opp_name')
        ->join('tb_contact','sales_lead_register.id_customer','=','tb_contact.id_customer')
        ->where('nik',1150991080)
        ->where('result' ,'SD')
        ->get(),
    ]);

    $return =  new EmailRemainderWeekly($parameterEmail);
    return $return;
  }

  public function authentication($id)
  {
    Auth::loginUsingId($id);
    return redirect('salesproject');
  }

  public function view_mail_to_sales(){
    // $pid_info = DB::table('tb_id_project')
    //         ->where('id_pro',111)
    //         ->select(
    //             'lead_id',
    //             'name_project',
    //             'no_po_customer',
    //             'sales_name',
    //             'no_po_customer',
    //             'tb_id_project.id_project'
    //         )->first();

    // if($pid_info->lead_id == "MSPQUO"){
    //   $pid_info->no_quote = $pid_info->no_po_customer;
    //   $pid_info->no_po_customer = "-";
    // }else {
    //   $pid_info->no_quote = "-";
    // }

    // return new mailPID($pid_info);

    return redirect('/salesproject');
    
  }

  public function view_mail_to_finance(){
  
    $pid_info = DB::table('sales_lead_register')
      ->join('sales_tender_process','sales_tender_process.lead_id','=','sales_lead_register.lead_id')
      ->join('tb_pid','tb_pid.lead_id','=','sales_lead_register.lead_id')
      ->join('users','users.nik','=','sales_lead_register.nik')
      ->where('sales_lead_register.lead_id','BBJB190401')
      ->select(
          'sales_lead_register.lead_id',
          'sales_lead_register.opp_name',
          'users.name',
          'tb_pid.amount_pid',
          'tb_pid.id_pid',
          'tb_pid.no_po',
          'sales_tender_process.quote_number2'
      )->first();

    if($pid_info->lead_id == "MSPQUO"){
      $pid_info->url_create = "/salesproject";
    }else {
      $pid_info->url_create = "/salesproject#acceptProjectID?" . $pid_info->id_pid;
    }

    $users = User::select('name')->where('id_division','FINANCE')->where('id_position','MANAGER')->first();

    return new MailResult($users,$pid_info);
  }

}
