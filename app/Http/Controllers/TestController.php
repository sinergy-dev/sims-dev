<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use Mail;
use App\Mail\EmailRemainderWeekly;
use App\Mail\MailResult;
use App\Mail\mailPID;
use App\Mail\RequestNewAssetHr;
use App\Notifications\Testing;
use Notification;
use App\Notifications\NewLead;
use App\PID;
use Auth;
use DB;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

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

  public function testEmailPeminjaman(){

      $to = User::select('email')->where('id_division','WAREHOUSE')->where('id_position','WAREHOUSE')->get();

      $users = User::select('name')->where('id_division','WAREHOUSE')->where('id_position','WAREHOUSE')->first();


      Mail::to($to)->send(new RequestNewAssetHr($users,'[SIMS-APP] Permohonan untuk Peminjaman Asset'));

  }

  public function testRemainderEmail(){
    $parameterEmail = collect([
      // "to" => DB::table('users')->where('nik',1150991080)->first()->name,
      "to" => DB::table('users')->where('name','Rama Agastya')->first()->name,
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
    Mail::to('agastya@sinergy.co.id')->send($return);

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

    $users = User::select('name')->where('name','Rama Agastya')->first();



    return new MailResult($users,$pid_info);
  }

  public function postEventCalendar(Request $request){
    // $calenderId = "kfo8st45f546hr112s6ia4mgmo@group.calendar.google.com";
    $calenderId = $request->group;

    $client = new Client();
    $url = "https://www.googleapis.com/calendar/v3/calendars/".$calenderId."/events?key=".env('APPSINERGY_GOOGLE_API_KEY')."&sendNotifications=true";
    $token = $this->getOauth2AccessToken();

    $response =  $client->request(
          'POST', 
          $url,        
          [
            // 'form_params' => [
            //     'sendNotifications' => true,
            // ],
            'headers' => [
              'Content-Type'=>'application/json',
              'Authorization'=>$token
            ],'json' => [
                "summary" => $request->summary,
                "start" => array(
                  'dateTime' => $request->startDateTime,
                ),
                "end" => array(
                  'dateTime' => $request->endDateTime,
                ),
                "description" => $request->description,
                'reminders' => array(
                  'useDefault' => FALSE,
                  'overrides' => array(
                    array('method' => 'email', 'minutes' => 24 * 60),
                    array('method' => 'popup', 'minutes' => 10),
                  ),
                ),
                'attendees'=> array(
                  array('email'=> $request->email),
                ),
            ]
          ]
    );

    return $response->getBody();
  }

  public function getOauth2AccessToken(){
    $client = new Client();

    $response = $client->request(
        'POST',
        'https://oauth2.googleapis.com/token',
        [
          'headers' => [
            'Content-Type' => 'application/x-www-form-urlencoded',
          ],
          'form_params' => [
            'grant_type' => 'refresh_token',
            'client_id' => env('OAUTH2_CLIENT_ID'),
            'client_secret' => env('OAUTH2_CLIENT_SECRET'),
            'refresh_token' => env('OAUTH2_REFRESH_TOKEN')
          ]
        ]
      );

    $response = json_decode($response->getBody());

    return "Bearer " . $response->access_token;
    // if(Cache::store('file')->has('webex_access_token')){
    //   Log::info('Webex Access Token still falid');
    //   return "Bearer " . Cache::store('file')->get('webex_access_token');
    // } else {
    //   Log::error('Webex Access Token not falid. Try to refresh token');
    //   $client = new Client();
    //   $response = $client->request(
    //     'POST',
    //     'https://webexapis.com/v1/access_token',
    //     [
    //       'headers' => [
    //         'Content-Type' => 'application/x-www-form-urlencoded',
    //       ],
    //       'form_params' => [
    //         'grant_type' => 'refresh_token',
    //         'client_id' => env('WEBEX_CLIENT_ID'),
    //         'client_secret' => env('WEBEX_CLIENT_SECRET'),
    //         'refresh_token' => env('WEBEX_REFRESH_TOKEN')
    //       ]
    //     ]
    //   );

    //   $response = json_decode($response->getBody());

    //   if(isset($response->access_token)){
    //     Log::info('Refresh Token success. Save token to cache file');
    //     Cache::store('file')->put('webex_access_token',$response->access_token,now()->addSeconds($response->expires_in));
    //     return "Bearer " . Cache::store('file')->get('webex_access_token');
    //   } else {
    //     Log::error('Refresh Token failed. Please to try change "refresh token"');
    //   }
    // }
  }

  public function getListEvent(){
    $client = new Client();
    $url = "https://www.googleapis.com/calendar/v3/calendars/primary/events?key=".env('APPSINERGY_GOOGLE_API_KEY');
    $token = $this->getOauth2AccessToken();

    $response = $client->request(
      'GET',
      $url,
      [
        'headers' => [
          'Accept'=>'application/json',
          'Content-Type'=>'application/json',
          'Authorization'=>$token
        ]
      ]
    );

    return $response->getBody();
  }

  public function getCalendarList(){
    $client = new Client();
    $url = "https://www.googleapis.com/calendar/v3/users/me/calendarList?key=".env('APPSINERGY_GOOGLE_API_KEY');
    $token = $this->getOauth2AccessToken();

    $response = $client->request(
      'GET',
      $url,
      [
        'headers' => [
          'Accept'=>'application/json',
          'Content-Type'=>'application/json',
          'Authorization'=>$token
        ]
      ]
    );

    $json = (string)$response->getBody();
    $responses = json_decode($json,true);

    return $responses;
  }


}
