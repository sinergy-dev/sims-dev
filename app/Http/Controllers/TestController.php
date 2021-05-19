<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Sales;
use Mail;
use App\Mail\EmailRemainderWeekly;
use App\Mail\MailResult;
use App\Mail\mailPID;
use App\Mail\CreateLeadRegister;
use App\Mail\AssignPresales;
use App\Mail\RaiseTender;

use App\Mail\RequestNewAssetHr;
use App\Notifications\Testing;
use Notification;
use App\Notifications\NewLead;
use App\PID;
use Auth;
use DB;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Google_Client;
use Google_Service_Drive;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;

use HttpOz\Roles\Models\Role;

// use App\Notifications\Result;


class TestController extends Controller
{
	public function send_mail(){
		// $email = 'faiqoh@sinergy.co.id';
  //       Notification::route('mail', $email)->notify(new NewLead($email));  

  			$to = User::select('email','name')->where('id_position', 'STAFF')->where('id_division', 'TECHNICAL')->where('id_territory', 'DVG')->get();

  			$users = User::select('name')->where('id_division','FINANCE')->where('id_position','MANAGER')->first();

  			$pid_info = Sales::join('sales_tender_process','sales_tender_process.lead_id','=','sales_lead_register.lead_id')
                  ->join('tb_pid','tb_pid.lead_id','=','sales_lead_register.lead_id')
                  ->select('sales_lead_register.lead_id','no_po','amount_pid','quote_number2')
                  ->first(); 
            // Mail::to($users, new Result());

        // foreach ($to as $data) {
        // 	Mail::to($data->email)->send(new MailResult($users,$pid_info));
        // }
                  $pid_info->url_create = "hahahaha";
        return new MailResult($users,$pid_info);

        // return Mail::to('tito@sinergy.co.id')->send(new MailResult($users,$pid_info));
	}

  public function testNewLead(){
    // $data = DB::table('sales_lead_register')
    //                 ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
    //                 ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
    //                 ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'sales_lead_register.opp_name','sales_lead_register.amount', 'users.name')
    //                 ->where('lead_id','AEON200201')
    //                 ->first();
    $data = DB::table('sales_lead_register')
      ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
      ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
      ->select(
          'sales_lead_register.lead_id',
          'tb_contact.customer_legal_name', 
          'sales_lead_register.opp_name',
          'sales_lead_register.amount', 
          'users.name')
      ->where('lead_id',"BMRI210403")
      ->first();
    return new CreateLeadRegister($data); 

    // return Mail::to('tito@sinergy.co.id')->send(new CreateLeadRegister($data));

  }

  public function testAssignPresales(){
    $data = DB::table('sales_lead_register')
                    ->join('sales_solution_design','sales_solution_design.lead_id','sales_lead_register.lead_id')
                    ->join('users as sales', 'sales.nik', '=', 'sales_lead_register.nik')
                    ->join('users as presales','presales.nik','=','sales_solution_design.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'sales_lead_register.opp_name','sales_lead_register.amount', 'sales.name as sales_name','presales.name as presales_name')
                    ->where('sales_lead_register.lead_id','AEON200201')
                    ->first();
    $status = 'reAssign';


    return new AssignPresales($data,$status);

    // return Mail::to('hisyam@sinergy.co.id')->send(new AssignPresales($data,$status));

  }

  public function testRaiseToTender(){
    $data = DB::table('sales_lead_register')
                    ->join('sales_solution_design','sales_solution_design.lead_id','sales_lead_register.lead_id')
                    ->join('users as sales', 'sales.nik', '=', 'sales_lead_register.nik')
                    ->join('users as presales','presales.nik','=','sales_solution_design.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'sales_lead_register.opp_name','sales_lead_register.amount', 'sales.name as sales_name','presales.name as presales_name')
                    ->where('sales_lead_register.lead_id','AEON200201')
                    ->first();

    // return Mail::to('tito@sinergy.co.id')->send(new RaiseTender($data));

    return new RaiseTender($data);      

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

    // return Mail::to('tito@sinergy.co.id')->send(new EmailRemainderWeekly($parameterEmail));

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
            'client_id' => env('GCALENDER_CLIENT_ID'),
            'client_secret' => env('GCALENDER_CLIENT_SECRET'),
            'refresh_token' => env('GCALENDER_REFRESH_TOKEN')
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

  public function storeEvents(Request $request){
    $client = $this->getClient();   
    $service  = new Google_Service_Calendar($client);

    $calendarId = $request->group;
    $event    = new Google_Service_Calendar_Event(array(
        'summary' => $request->summary,
        // 'location' => 'Gelora Bung Karno',
        'description' => $request->description,
        "start" => array(
          'dateTime' => $request->startDateTime,
        ),
        "end" => array(
          'dateTime' => $request->endDateTime,
        ),
        'attendees' => array(
          array('email' => $request->email),
        ),
        'reminders' => array(
          'useDefault' => FALSE,
          'overrides' => array(
            array('method' => 'email', 'minutes' => 24 * 60),
            array('method' => 'popup', 'minutes' => 10),
          ),
        ),
    ));

    $optParams = Array(
      'sendNotifications' => true,
    );

    $event = $service->events->insert($calendarId, $event, $optParams);
    printf('Event created: %s\n', $event->htmlLink); 
  }

  public function getClient(){
    $client = new Google_Client();
    $client->setScopes(Google_Service_Calendar::CALENDAR);
    // $client->setAuthConfig('/home/dinar/sims-dev/app/Http/Controllers/client_secrets.json');
    $tokenPath = '/home/dinar/sims-dev/app/Http/Controllers/token.json';
    echo "string";

    if (file_exists($tokenPath)) {
        $accessToken = json_decode(file_get_contents($tokenPath), true);
        $client->setAccessToken($accessToken);
    }

    if (!$client->isAccessTokenExpired()) {
        // echo "string";
        return $client;
        // $service = new Google_Service_Calendar($client);

        // Print the next 10 events on the user's calendar.
        // $optParams = array(
        //   'maxResults' => 10,
        //   'orderBy' => 'startTime',
        //   'singleEvents' => true,
        //   'timeMin' => date('c'),
        // );
        // $results = $service->events->listEvents($calendarId, $optParams);
        // $events = $results->getItems();

        // if (empty($events)) {
        //     print "No upcoming events found.\n";
        // } else {
        //     print "Upcoming events:\n";
        //     foreach ($events as $event) {
        //         $start = $event->start->dateTime;
        //         if (empty($start)) {
        //             $start = $event->start->date;
        //         }
        //         printf("%s (%s)\n", $event->getSummary(), $start);
        //     }
        // }

        
    } else {
      $redirect_uri = 'https://' . $_SERVER['HTTP_HOST'] . '/oauth2callback';
      return redirect()->away($redirect_uri);
    }
                            
  }

  public function testJson(){
    $client = $this->getClient();   
    $service  = new Google_Service_Calendar($client);

      $calendarId = "gbivof9sl2pmd7vok9bopi03oc@group.calendar.google.com";
      $event    = new Google_Service_Calendar_Event(array(
          'summary' => 'Hari Kartono',
          'location' => 'Gelora Bung Karno',
          'description' => 'A chance to hear more about Google\'s developer products.',
          'start' => array(
            'dateTime' => '2021-04-22T09:00:00+07:00',
            'timeZone' => 'Asia/Jakarta',
          ),
          'end' => array(
            'dateTime' => '2021-04-22T17:00:00+07:00',
            'timeZone' => 'Asia/Jakarta',
          ),
          'recurrence' => array(
            'RRULE:FREQ=DAILY;COUNT=2'
          ),
          'attendees' => array(
            array('email' => 'faiqoh11.fa@gmail.com'),
            array('email' => 'ladinarnanda@gmail.com'),
          ),
          'reminders' => array(
            'useDefault' => FALSE,
            'overrides' => array(
              array('method' => 'email', 'minutes' => 24 * 60),
              array('method' => 'popup', 'minutes' => 10),
            ),
          ),
      ));

      $optParams = Array(
        'sendNotifications' => true,
      );

      $event = $service->events->insert($calendarId, $event, $optParams);
      printf('Event created: %s\n', $event->htmlLink); 
  }

  public function oauth2callback(Request $request){
    $client = new Google_Client();
    $client->setAuthConfigFile('/home/dinar/sims-dev/app/Http/Controllers/client_secrets.json');
    $client->setRedirectUri('https://' . $_SERVER['HTTP_HOST'] . '/oauth2callback');

    $client->setScopes(Google_Service_Calendar::CALENDAR);

    if (! isset($_GET['code'])) {
      $auth_url = $client->createAuthUrl();
      return redirect()->away($auth_url);
      echo "stringss";
    } else {
      $client->authenticate($_GET['code']);
      echo "string";
      $request->session()->put('access_token',$client->getAccessToken());
      $tokenPath = '/home/dinar/sims-dev/app/Http/Controllers/token.json';
      if (!file_exists(dirname($tokenPath))) {
          mkdir(dirname($tokenPath), 0700, true);
      }
      file_put_contents($tokenPath, json_encode($client->getAccessToken()));
      $redirect_uri = 'https://' . $_SERVER['HTTP_HOST'] . '/';
      return redirect()->away($redirect_uri);
    }
  }

  public function testPermission(){
    return view('testPermission')->with(['initView'=> $this->initMenuBase()]);
  }

  public function testPermissionConfig(){ 
    return view('testPermissionConfig')->with(['initView'=> $this->initMenuBase()]);
  }

  public function getUserList(){
    return DB::table('role_user')
      ->select(
          'role_user.*',
          DB::raw('`roles`.`group` AS `name_group`'),
          DB::raw('`roles`.`name` AS `name_roles`'),
          'users.name',
          'roles_feature_each.each_feature'
        )
      ->join('users','role_user.user_id','=','users.nik')
      ->join('roles','role_user.role_id','=','roles.id')
      ->joinSub(DB::table('roles_feature')
        ->select(
          'roles_feature.role_id',
          DB::raw('GROUP_CONCAT(`features`.`name`) AS `each_feature`')
        )
        ->join('features','features.id','=','roles_feature.feature_id')
        ->groupBy('roles_feature.role_id'), 'roles_feature_each', function ($join) {
        $join->on('roles.id', '=', 'roles_feature_each.role_id');
      })
      // ->take(1)
      ->get();
  }

  public function getParameter(){
    $name = DB::table('users')
      ->select(
          DB::raw('`nik` AS `id`'),
          DB::raw('`name` AS `text`'),
        )
      ->where('id_company','=','1')
      ->where('status_karyawan','=','cuti')
      ->get();

    $roles = DB::table('roles')
      ->select(
        DB::raw('`id` AS `id`'),
        DB::raw('`name` AS `text`'),
        DB::raw('`group` AS `group`'),
      )->get();

      $groups = [];
      foreach($roles->pluck('group')->unique()->values() as $group){
        array_push($groups, ["text" => strtoupper($group),"children" => $roles->where('group',$group)->values()]);
      }

      // return collect(["result" => ["text" => "Sales","children" => [["id"=>1],["id"=>2]]]]);
      // return $groups;
    return collect(['name' => $name,'roles' => $groups]);
  }

  public function getParameterRoles(){
      $roles = DB::table('roles')
      ->select(
        DB::raw('`id` AS `id`'),
        DB::raw('`name` AS `text`'),
        DB::raw('`group` AS `group`'),
      )->get();

      $groups = [];
      foreach($roles->pluck('group')->unique()->values() as $group){
        array_push($groups, ["text" => strtoupper($group),"children" => $roles->where('group',$group)->values()]);
      }
      // return collect(["result" => ["text" => "Sales","children" => [["id"=>1],["id"=>2]]]]);
      // return $groups;
    return collect(['roles' => $groups]);
  }

  public function getParameterFeature(Request $req){
      $features = DB::table('features')
      ->select(
        DB::raw('`features`.`id` AS `id`'),
        DB::raw('`name` AS `text`'),
        DB::raw('`group` AS `group`'),
      )->whereNotIn('id', function($query) use ($req){
        $query->select('feature_id')
              ->from('roles_feature')->where('role_id',$req->roles_id);
      })
      ->get();

      $groupsFeature = [];
      foreach($features->pluck('group')->unique()->values() as $group){
        array_push($groupsFeature, ["text" => strtoupper($group),"children" => $features->where('group',$group)->values()]);
      }

      // return collect(["result" => ["text" => "Sales","children" => [["id"=>1],["id"=>2]]]]);
      // return $groups;
    return collect(['features' => $groupsFeature]);
  }


  public function getFeatureRole(){
    return DB::table('roles')
      ->select(
          DB::raw('`roles`.`group` AS `name_group`'),
          DB::raw('`roles`.`name` AS `name_roles`'),
          DB::raw('`roles_feature_each`.`each_feature` AS `feature_name`')
        )
      // ->join('features','features.id','=','roles_feature.feature_id')
      ->joinSub(DB::table('roles_feature')
        ->select(
          'roles_feature.role_id',
          DB::raw('GROUP_CONCAT(`features`.`name`) AS `each_feature`')
        )
        ->join('features','features.id','=','roles_feature.feature_id')
        ->groupBy('roles_feature.role_id'), 'roles_feature_each', function ($join) {
        $join->on('roles.id', '=', 'roles_feature_each.role_id');
      })
      ->get();
    // return DB::table('roles')->get();
  }

  public function setRoles(Request $req){
    foreach ($req->id_role as $id_role) {
      User::find($req->id_user)->attachRole(Role::find($id_role));
    }
    // return $req->id_role;
    return "Success";
  }

  public function setRolesFeature(Request $req){
    foreach ($req->id_feature as $id_feature) {
      DB::table('roles_feature')->insert([
        'role_id' => Role::find($req->id_role)->id,
        'feature_id' => $id_feature
      ]);
      // Role::find($req->id_role)->attachRole(DB::table('features')->find($id_feature));
    }
    // return $req->id_role;
    return "Success";
  }

  public function addConfigRoles(Request $req){
    DB::table('roles')->insert([
        'name' => $req->name,
        'slug' => $req->slug,
        'description' => $req->description,
        'group' => $req->group
    ]);

    return "Success";
  }

  public function addConfigFeature(Request $req){
    $index_of = DB::table('features')->latest('index_of')->first()->index_of + 1;

    DB::table('features')->insert([
        'name' => $req->name,
        'description' => $req->description,
        'group' => $req->group,
        'index_of'=> $index_of,
        'url'=>$req->url
    ]);

    return "Success";
  }

  public function getRoles(Request $req){
    return DB::table('roles')->get();
  }

  public function getFeature(Request $req){
    return DB::table('features')->get();
  }

  public function getRoleDetail(Request $req){
    return collect([
      'role' => DB::table('roles')->where('id','=',$req->id)->get()->first(),
      'holder' => DB::table('role_user')
        ->select('users.name')
        ->join('users','role_user.user_id','=','users.nik')
        ->where('role_id','=',$req->id)
        ->get()
    ]);
  }

  public function getFeatureItem(Request $req){
    $column = DB::table('roles')->selectRaw('`id`,`name` AS `title`, REPLACE(`slug`,".","_") AS `name`,CONCAT("condition_",REPLACE(`slug`,".","_")) AS `data`');
    if($req->group == "all") {
      $column2 = $column->get();
    } else {
      $column2 = $column->where('group','=',$req->group)
        ->get();
    }
    

    $column = $column2->map(function ($item, $key) {
      $item->class = "text-center";
      return $item;
    });

    // return $column;

    $return = DB::table('feature_item')
      ->select("feature_item.*");
      
    foreach ($column as $key => $value) {
      $return = $return->addSelect($value->name . ".condition_" . $value->name);
      $leftJoin = DB::table('roles_feature_item')
        ->where('roles_id','=',$value->id);

      $join = DB::table('feature_item')
        ->selectRaw('`feature_item`.`id`, CONCAT("' . "<label class='switch'><input class='featureItemCheck' type='checkbox' id='" . $value->id . "-" . '",' . '`feature_item`.`id`' . ',"\' ",' . "IF(`roles_feature_item_filtered`.`id` > 0,'checked','')" . ',"' . "><span class='slider round'></span></label>" . '") AS `condition_' . $value->name . '`')
        ->leftJoinSub($leftJoin,'roles_feature_item_filtered',function($join){
          $join->on('roles_feature_item_filtered.feature_item_id','=','feature_item.id');
        });
      $return = $return->joinSub($join,$value->name,function($join) use ($value){
        $join->on($value->name . '.id','=','feature_item.id');
      });

      // $return = $return->selectRaw('CONCAT("' . "<label class='switch'><input type='checkbox' id='checkbox1'><span class='slider round'></span></label>" . '") AS `' . $value->name . '`');
      
      // $return = $return->selectRaw('CONCAT("' . "<label class='switch'><input type='checkbox' id='checkbox1'><span class='slider round'></span></label>" . '") AS `' . $value->name . '`');
    }

    $column->prepend(['title' => "Item",'data' => "item_id"]);
    $column->prepend(['title' => "Feature",'data' => "group"]);

    return collect(['data' => $return->get(),'column' => $column]);

    // return DB::table('feature_item')
    //   ->selectRaw("*")
    //   ->selectRaw('CONCAT("' . "<label class='switch'><input type='checkbox' id='checkbox1'><span class='slider round'></span></label>" . '") AS `director`')
    //   ->selectRaw('CONCAT("' . "<label class='switch'><input type='checkbox' id='checkbox1'><span class='slider round'></span></label>" . '") AS `staff`')
    //   ->selectRaw('CONCAT("' . "<label class='switch'><input type='checkbox' id='checkbox1'><span class='slider round'></span></label>" . '") AS `admin`')
    //   ->selectRaw('CONCAT("' . "<label class='switch'><input type='checkbox' id='checkbox1'><span class='slider round'></span></label>" . '") AS `hrstaff`')
    //   ->selectRaw('CONCAT("' . "<label class='switch'><input type='checkbox' id='checkbox1'><span class='slider round'></span></label>" . '") AS `hrga`')
    //   ->selectRaw('CONCAT("' . "<label class='switch'><input type='checkbox' id='checkbox1'><span class='slider round'></span></label>" . '") AS `pmostaff`')
    //   ->get();
  }

  public function getFeatureItemParameter(){
    return DB::table('roles')->select('group')->groupBy('group')->pluck('group');
  }

  public function changeFeatureItem(Request $req){
    $roleFeatureItem = DB::table('roles_feature_item')
      ->where('roles_id','=',$req->role)
      ->where('feature_item_id','=',$req->feature);

    if($roleFeatureItem->exists()){
      $roleFeatureItem->delete();
      return "deleted";
    } else {
      DB::table('roles_feature_item')
        ->insert([
          'roles_id' => $req->role,
          'feature_item_id' => $req->feature
        ]);
      return "added";
    }
  }

}
