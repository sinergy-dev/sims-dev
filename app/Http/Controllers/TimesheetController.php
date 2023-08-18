<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\TimesheetConfig;
use App\Timesheet;
use App\TimesheetLockDuration;
use App\TimesheetPermit;
use App\TimesheetPhase;
use App\TimesheetTask;
use App\TimesheetPid;
use App\SalesProject;
use App\User;
use App\Cuti;
use App\CutiDetil;
use App\Sbe;
use App\SbeConfig;

use DatePeriod;
use DateInterval;
use DateTime;

use DB;
use Session;
use Auth;
use PDF;
use Carbon\Carbon;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use Mail;


class TimesheetController extends Controller
{
	public function timesheet()
    {
        // return "lalalal";
        return view('timesheet/timesheet')->with(['initView'=> $this->initMenuBase(),'feature_item'=>$this->RoleDynamic('timesheet')]);
    }

    public function timesheet_dashboard()
    {
        return view('timesheet/dashboard')->with(['initView'=> $this->initMenuBase(),'feature_item'=>$this->RoleDynamic('timesheet')]);
    }

    public function timesheet_config()
    {
        return view('timesheet/config_timesheet')->with(['initView'=> $this->initMenuBase(),'feature_item'=>$this->RoleDynamic('timesheet')]);
    }

    public function getListCalendarEvent(Request $request){
        // $url = "https://www.googleapis.com/calendar/v3/users/me/calendarList";
        // $client = new Client();
        // $token = $this->getOauth2AccessToken();

        // $response =  $client->request(
        //     'GET', 
        //     $url,        
        //     [
        //         'headers' => [
        //             'Content-Type'=>'application/json',
        //             'Authorization'=>$token
        //         ],
        //         // 'form_params' => [
        //         //     'sendNotifications' => true,
        //         // ],
        //     ]
        // );

        $calenderId = User::where('nik',$request->nik)->first()->email;
        // Auth::User()->email;
        //calendar id nya apa aja?
        //ladinar@sinergy.co.id
        

        // $client = new Client();
        // $url = "https://www.googleapis.com/calendar/v3/calendars/". $calenderId ."/events";
        try {
            // Create a new Guzzle HTTP client
            $client = new Client();
            // Make the API request
            $url = "https://www.googleapis.com/calendar/v3/calendars/". $calenderId ."/events";
            $token = $this->getOauth2AccessToken();

            $response = $client->request(
                'GET', 
                $url,        
                [
                    'headers' => [
                        'Content-Type'=>'application/json',
                        'Authorization'=>$token
                    ],
                    // 'form_params' => [
                    //     'sendNotifications' => true,
                    // ],
                ]
            );
            // Check for successful response (status code in the range of 200-299)
            if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) { 
                return json_decode($response->getBody(), true);
                // Use the response data as needed
                // ...
            } else {
                // Handle non-successful response
                $errorCode = $response->getStatusCode();
                throw new Exception('Error: API returned status code ' . $errorCode);
            }

        } catch (RequestException $e) {
            // Handle Guzzle-specific HTTP request exception
            // This may include network issues or connectivity problems
            return $response = [];
        } catch (Exception $e) {
            // Handle other exceptions
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }

        // $token = $this->getOauth2AccessToken();

        // $response = $client->request(
        //     'GET', 
        //     $url,        
        //     [
        //         'headers' => [
        //             'Content-Type'=>'application/json',
        //             'Authorization'=>$token
        //         ],
        //         // 'form_params' => [
        //         //     'sendNotifications' => true,
        //         // ],
        //     ]
        // );
        // return json_decode($response->getBody(),true);
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
                        'client_secret' => env('GCALENDAR_CLIENT_SECRET'),
                        'refresh_token' => env('GCALENDAR_REFRESH_TOKEN')
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

    public function storePhaseConfig(Request $request)
    {
        if (isset($request->id)) {
            $update = TimesheetPhase::where('id',$request->id)->first();
            $update->phase = $request->inputPhase;
            $update->description = $request->inputPhaseDesc;
            $update->update();
        }else{
            $store = new TimesheetPhase();
            $store->phase = $request->inputPhase;
            $store->description = $request->inputPhaseDesc;
            $store->date_add = Carbon::now()->toDateTimeString();
            $store->save();
        }
    }

    public function deleteTaskPhase(Request $request)
    {
        if ($request->type == "phase") {
            $delete = TimesheetPhase::where('id',$request->id);
            $delete->delete();
        }else{
            $delete = TimesheetTask::where('id',$request->id);
            $delete->delete();
        }
    }

    public function storeTaskConfig(Request $request)
    {
        if (isset($request->id)) {
            $update = TimesheetTask::where('id',$request->id)->first();
            $update->task = $request->inputTask;
            $update->description = $request->inputTaskDesc;
            $update->update();
        }else{
            $store = new TimesheetTask();
            $store->task = $request->inputTask;
            $store->description = $request->inputTaskDesc;
            $store->date_add = Carbon::now()->toDateTimeString();
            $store->save();
        }
    }

    public function assignPidConfig(Request $request)
    {
        if ($request->selectAssignFor == "Pid") {
           foreach (json_decode($request->selectPIDAssign,true) as $key => $value) {
                $assign = new TimesheetPid();
                $assign->nik = $request->selectPICAssign;
                $assign->pid = $value;
                $assign->role = $request->selectRoleAssign;
                $assign->date_add = Carbon::now()->toDateTimeString();
                $assign->save();
            }

            $getDivision = User::select('id_division')->distinct()->where('nik',$request->selectPICAssign)->first();

            $update = TimesheetConfig::where('division',$getDivision->id_division)->first();
            if (isset($update)) {
                $update->status_assign_pid = 'Pid';
                $update->update(); 
            }       
        }else{
            $update = TimesheetConfig::where('division',Auth::User()->id_division)->get();
            if (count($update) != 0) {
                foreach($update as $updates){
                    $updates = TimesheetConfig::where('division',$updates->division)->first();
                    $updates->status_assign_pid = "All";
                    $updates->update();
                }
            }else{
                $assign = new TimesheetConfig();
                $assign->roles = null;
                $assign->phase = null;
                $assign->task = null;
                $assign->date_add = Carbon::now()->toDateTimeString();
                $assign->division = Auth::User()->id_division;
                $assign->status_assign_pid = "All";

                $assign->save();
            }
        }
    }

    public function getListOperation()
    {
        $getListOperation = User::select(DB::raw('LOWER(id_division) as id'), 'id_division as text')->where('id_company', '1')->where('id_territory','OPERATION')->where('id_division','!=','WAREHOUSE')->where('id_division','!=','TECHNICAL')->distinct()->get();

        $sol = ['id' => 'presales', 'text' => 'SOL'];
        $sid = ['id' => 'DPG', 'text' => 'SID'];
        $hr = ['id' => 'hr', 'text' => 'HR'];

        return $getListOperation->push($sol)->push($sid)->push($hr);

        // return $getListOperation;
    }

    public function addTimesheet(Request $request)
    {
        // return $request->id_activity;
        $startDate = Carbon::now()->startOfMonth()->format("Y-m-d");
        $endDate = Carbon::now()->endOfMonth()->format("Y-m-d");

        $startDateInput = $request->startDate . '00:01:02';
        $endDateInput = $request->endDate . '23:59:59';

        // $toDate = Carbon::parse($startDateInput)->addDays(1);
        // $fromDate = Carbon::parse($request->endDate)->addDays(1);
        Carbon::setTestNow();

        $toDate = Carbon::createFromFormat('Y-m-d', $request->endDate, 'Asia/Jakarta');
        $fromDate = Carbon::createFromFormat('Y-m-d', $request->startDate, 'Asia/Jakarta');
  
        $days = $toDate->diffInDays($fromDate);
        // $allDaysPlanned = $days+1;

        if ($request->selectSchedule == 'Planned') {
            if ($days > 0) {
                for ($i=0; $i <= $days; $i++) { 

                    if (isset($request->id_activity)) {
                        $addTimesheet = Timesheet::where('id',$request->id_activity)->first();
                    } else {
                        $addTimesheet = new Timesheet();
                        $addTimesheet->date_add = Carbon::now()->toDateTimeString();
                    }

                    // $addTimesheet = new Timesheet();
                    $addTimesheet->nik = Auth::User()->nik;
                    $addTimesheet->schedule = $request->selectSchedule;
                    $startDatePlanned = Carbon::createFromFormat('Y-m-d', $request->startDate, 'Asia/Jakarta')->addDays($i);

                    // return Carbon::parse($startDate);
                    $addTimesheet->start_date = $startDatePlanned;
                    $addTimesheet->end_date = $startDatePlanned;
                    $addTimesheet->pid = $request->selectLead;
                    $addTimesheet->task = $request->selectTask;
                    $addTimesheet->phase = $request->selectPhase;
                    $addTimesheet->level = $request->selectLevel;
                    $addTimesheet->activity = $request->textareaActivity;
                    $addTimesheet->status = $request->selectStatus;
                    $addTimesheet->duration = $request->selectDuration;
                    $addTimesheet->type = $request->selectType;
                    $getPoint = (int)$request->selectDuration/480;
                    $addTimesheet->point_mandays = number_format($getPoint, 2, '.', '');
                    $addTimesheet->month = date("n");
                    $workdays = $this->getWorkDays($startDate,$endDate)["workdays"]->values();
                    // $addTimesheet->workdays = count($workdays);
                    $addTimesheet->save();

                    $storeAll[] = $addTimesheet;
                }
            }else{
                if (isset($request->id_activity)) {
                    $addTimesheet = Timesheet::where('id',$request->id_activity)->first();
                } else {
                    $addTimesheet = new Timesheet();
                    $addTimesheet->date_add = Carbon::now()->toDateTimeString();
                }
                $addTimesheet->nik = Auth::User()->nik;
                $addTimesheet->schedule = $request->selectSchedule;
                $startDatePlanned = Carbon::createFromFormat('Y-m-d', $request->startDate, 'Asia/Jakarta');
                // return Carbon::parse($startDate);
                $addTimesheet->start_date = $startDatePlanned;
                $addTimesheet->end_date = $startDatePlanned;
                $addTimesheet->pid = $request->selectLead;
                $addTimesheet->task = $request->selectTask;
                $addTimesheet->phase = $request->selectPhase;
                $addTimesheet->level = $request->selectLevel;
                $addTimesheet->activity = $request->textareaActivity;
                $addTimesheet->status = $request->selectStatus;
                $addTimesheet->duration = $request->selectDuration;
                $addTimesheet->type = $request->selectType;
                $getPoint = (int)$request->selectDuration/480;
                $addTimesheet->point_mandays = number_format($getPoint, 2, '.', '');
                $addTimesheet->month = date("n");
                $workdays = $this->getWorkDays($startDate,$endDate)["workdays"]->values();
                // $addTimesheet->workdays = count($workdays);
                $addTimesheet->save();

                $storeAll[] = $addTimesheet;
            }
        } else {
            if (isset($request->id_activity)) {
                $addTimesheet = Timesheet::where('id',$request->id_activity)->first();
            } else {
                $addTimesheet = new Timesheet();
                $addTimesheet->date_add = Carbon::now()->toDateTimeString();
            }
            $addTimesheet->nik = Auth::User()->nik;
            $addTimesheet->schedule = $request->selectSchedule;
            $addTimesheet->start_date = $request->startDate;
            $addTimesheet->end_date = $request->endDate;
            $addTimesheet->pid = $request->selectLead;
            $addTimesheet->task = $request->selectTask;
            $addTimesheet->phase = $request->selectPhase;
            $addTimesheet->level = $request->selectLevel;
            $addTimesheet->activity = $request->textareaActivity;
            $addTimesheet->status = $request->selectStatus;
            $addTimesheet->duration = $request->selectDuration;
            $addTimesheet->type = $request->selectType;
            $getPoint = (int)$request->selectDuration/480;
            $addTimesheet->point_mandays = number_format($getPoint, 2, '.', '');
            $addTimesheet->month = date("n");
            $workdays = $this->getWorkDays($startDate,$endDate)["workdays"]->values();
            // $addTimesheet->workdays = count($workdays);
            $addTimesheet->save();

            $storeAll [] = $addTimesheet;
        }

        return $storeAll;
    }

    public function uploadCSV(Request $request){
        $directory = "timesheet/";
        $nameFile = "template_timesheet.csv";
        $folderName = 'Test Timesheet';

        $this->uploadToLocal($request->file('csv_file'),$directory,$nameFile);

        $result = $this->readCSV($directory . "/" . $nameFile);

        if ($result == 'Format tidak sesuai' ) {
            return 'Format tidak sesuai';
            return collect([
                "text" => 'Format tidak sesuai',
                "status" => 'Error',
            ]);
        } else if ($result == 'Tidak ada produk') {
            return collect([
                "text" => 'Format tidak sesuai',
                "status" => 'Error',
            ]);
        } else {
            if(count($result) >= 1){
                foreach ($result as $key => $value) {
                    $getPoint = (int)$value['7'];
                    $point_mandays = number_format($getPoint, 2, '.', '');
                    $insertTimesheet[] = ['nik' => Auth::User()->nik, 'schedule' => $value[1], 'start_date' => $value[2], 'type' => $value[3], 'pid' => $value[4], 'level' => $value[5], 'activity' => $value[6], 'duration' => $value[7], 'status' => $value[8], 'end_date' => $value[9], 'point_mandays' => $point_mandays  
                    ];
                }
     
                if(!empty($insertTimesheet)){
                    Timesheet::insert($insertTimesheet);
                }

            } else {
                return 'Tidak ada activity';
            }
        }
        

        return $result;
    }

    public function readCSV($locationFile){

        $format = array(
            "schedule",
            "start_date",
            "type",
            "pid",
            "level",
            "activity",
            "duration(menit)",
            "status",
            "end_date"
        );

        if (($open = fopen($locationFile, "r")) !== FALSE) {

            $i = 0;
            $array = [];
            while (($data = fgetcsv($open, 1000, ";")) !== FALSE) {
                if($i != 0){
                    $array[] = $data;
                } else {
                    array_shift($data);
                    if (empty(!array_diff($format, $data))) {
                        return 'Format tidak sesuai';
                    }

                    
                }
                $i++;     
            }
            if ($i == 1) {
                return 'Tidak ada activity';
            }
            fclose($open);
        }

        return $array;
        // return array_shift($array);
    }

    public function uploadToLocal($file,$directory,$nameFile){
        $file->move($directory,$nameFile);
    }

    public function storeLockDuration(Request $request)
    {
        if (DB::table('tb_timesheet_lock_duration')->where('division',Auth::User()->id_division)->exists()) {
            // if (DB::table('tb_timesheet_lock_duration')->where('division',Auth::User()->id_division)->first()->division == Auth::User()->id_division) {
                $lock = TimesheetLockDuration::where('division',Auth::User()->id_division)->first();
            // } 
        } else {
            $lock = new TimesheetLockDuration();
        }
    	// if (DB::table('tb_timesheet_lock_duration')->where('division',Auth::User()->id_division)->first()->division == Auth::User()->id_division) {
    	// 	$lock = TimesheetLockDuration::where('division',Auth::User()->id_division)->first();
    	// } else {
    	// 	$lock = new TimesheetLockDuration();
    	// }
    	
    	$lock->lock_duration = $request->selectLock;
    	$lock->division = Auth::User()->id_division;
    	$lock->date_add = Carbon::now()->toDateTimeString();
    	$lock->save();
    }

    public function addConfig(Request $request)
    {
    	// return $request->arrConfig;
        // $delete = TimesheetConfig::where('division',Auth::User()->id_division);
        // $delete->delete();
        $delete = TimesheetConfig::where('division',Auth::User()->id_division)->whereNotIn('roles',json_decode($request->roles));
        if(isset($delete)){
            $delete->delete();
        }

    	foreach (json_decode($request->arrConfig,true) as $key => $value) {
    		// return gettype($value['phase']);
	    	// foreach ($value['phase'] as $key => $phase) {
	    		// foreach ($value['task'] as $key => $task) {
    				if (DB::table('tb_timesheet_config')->select('roles')->where('roles',$value['unit'])->exists()) {
                        $addConfig = TimesheetConfig::where('roles',$value['unit'])->first();
    				} else {
                        $addConfig = new TimesheetConfig();
    				}

    				$arrPhase            = array($value['phase']);
		    		$arrTask             = array($value['task']);
		    		$addConfig->roles    = $value['unit'];
		    		$addConfig->phase    = json_encode($value['phase'],JSON_NUMERIC_CHECK);
		    		$addConfig->task     = json_encode($value['task'],JSON_NUMERIC_CHECK);
		    		$addConfig->date_add = Carbon::now()->toDateTimeString();
			    	$addConfig->division = Auth::User()->id_division;
			    	$addConfig->save();
		    		
			    // }
	    	// }
    	}
    }

    public function getAllPid(Request $request)
    {
    	$getAllPid = SalesProject::join('sales_lead_register', 'sales_lead_register.lead_id', '=', 'tb_id_project.lead_id')->join('users', 'users.nik', '=', 'sales_lead_register.nik')->select('id_project as id','id_project as text')->where('id_company', '1')->orderby('tb_id_project.id_pro','desc')->get();

    	return $getAllPid;
    }

    public function getPidByPic(Request $request)
    {        
        $nik = Auth::User()->nik;
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('name', 'roles.group','role_user.role_id')->where('user_id', $nik)->first(); 

        $cekPidStatus = TimesheetConfig::where('division',Auth::User()->id_division)->first();

        if (isset($cekPidStatus)) {
            if ($cekPidStatus->status_assign_pid == 'All') {
                $getPidByPic = DB::table('tb_id_project')->join('sales_lead_register','sales_lead_register.lead_id','tb_id_project.lead_id')->join('users','users.nik','sales_lead_register.nik')->select('id_project as id',DB::raw("CONCAT(`id_project`,' - ',`opp_name`) AS text"))->where('id_company','1')->orderby('tb_id_project.id_pro','desc')->get();

                return $getPidByPic;
            }else if ($cekPidStatus->status_assign_pid == 'Pid') {
                $getPidByPic = DB::table('tb_timesheet_pid')->join('tb_id_project','tb_timesheet_pid.pid','tb_id_project.id_project')->join('sales_lead_register','sales_lead_register.lead_id','tb_id_project.lead_id')->select('tb_timesheet_pid.pid as id',DB::raw("CONCAT(`tb_timesheet_pid`.`pid`,' - ',`opp_name`) AS text"))->where('tb_timesheet_pid.nik',Auth::User()->nik)->orderby('id','desc')->get();

                return $getPidByPic;
            }
        }else{

            $getPidByPic = DB::table('tb_timesheet_pid')->join('tb_id_project','tb_timesheet_pid.pid','tb_id_project.id_project')->join('sales_lead_register','sales_lead_register.lead_id','tb_id_project.lead_id')->select('tb_timesheet_pid.pid as id',DB::raw("CONCAT(`tb_timesheet_pid`.`pid`,' - ',`opp_name`) AS text"))->where('tb_timesheet_pid.nik',Auth::User()->nik)->orderby('id','desc')->get();

            if (count($getPidByPic) == 0) {
                $getPidByPic = ['Alert','Please Ask your Manager/Spv to assign pid!'];
                return $getPidByPic;
            }else{
                return $getPidByPic;
            }
        }
        // if ($cek_role->group == 'hr' || $cek_role->group == 'pmo') {
            
        // } else {
            
        // }
    }

    public function getLeadId(Request $request)
    {
    	return $getLeadId = DB::table('sales_lead_register')->join('sales_solution_design','sales_solution_design.lead_id','sales_lead_register.lead_id')->select('sales_solution_design.lead_id as id',DB::raw("CONCAT(`sales_solution_design`.`lead_id`,' - ',`opp_name`) AS text"))->where('sales_solution_design.nik',Auth::User()->nik)->get();
    }


    public function getAllTask(Request $request)
    {
        if (isset($request->id)) {
            return $getAll = TimesheetTask::select('id as id','task as text','description')->where('id',$request->id)->get();
        }else{
            return $getAll = TimesheetTask::select('id as id','task as text')->get();
        }
    }

    public function getTaskByDivision(Request $request)
    {
        $nik = Auth::User()->nik;
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('name', 'roles.group')->where('user_id', $nik)->first(); 

        if ($cek_role->group == 'bcd') {
            if ($cek_role->name == 'BCD Development SPV') {
                // $getGroupRoles = DB::table('role_user')->join('roles','roles.id','role_user.role_id')->select('name')->where('user_id',Auth::User()->nik)->first()->name;

                $data = DB::table('tb_timesheet_config')->join('roles','roles.id','tb_timesheet_config.roles')->join('tb_timesheet_task', function ($join) {
                    $join->on(DB::raw('JSON_CONTAINS(tb_timesheet_config.task, CAST(tb_timesheet_task.id AS JSON), "$")'), '=', DB::raw('1'));
                })
                ->select('tb_timesheet_task.id as id', 'tb_timesheet_task.task as text')->where('name','BCD Development')->distinct()->get();
            } else {
                $getGroupRoles = DB::table('role_user')->join('roles','roles.id','role_user.role_id')->select('name')->where('user_id',Auth::User()->nik)->first()->name;

                $data = DB::table('tb_timesheet_config')->join('roles','roles.id','tb_timesheet_config.roles')->join('tb_timesheet_task', function ($join) {
                    $join->on(DB::raw('JSON_CONTAINS(tb_timesheet_config.task, CAST(tb_timesheet_task.id AS JSON), "$")'), '=', DB::raw('1'));
                })
                ->select('tb_timesheet_task.id as id', 'tb_timesheet_task.task as text')->where('name',$getGroupRoles)->distinct()->get();
            }
            
        } else {
            $getGroupRoles = DB::table('role_user')->join('roles','roles.id','role_user.role_id')->select('group')->where('user_id',Auth::User()->nik)->first()->group;

            $data = DB::table('tb_timesheet_config')->join('roles','roles.id','tb_timesheet_config.roles')->join('tb_timesheet_task', function ($join) {
                $join->on(DB::raw('JSON_CONTAINS(tb_timesheet_config.task, CAST(tb_timesheet_task.id AS JSON), "$")'), '=', DB::raw('1'));
            })
            ->select('tb_timesheet_task.id as id', 'tb_timesheet_task.task as text')->where('group',$getGroupRoles)->distinct()->get();
        }
    	
    	// $getAll = TimesheetConfig::join('roles','roles.id','tb_timesheet_config.roles')->select('task')->where('group',$getGroupRoles)->get();

		return $data;
    	
    }

    public function getPhaseByDivision(Request $request)
    {
        $nik = Auth::User()->nik;
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('name', 'roles.group')->where('user_id', $nik)->first(); 

        if ($cek_role->group == 'bcd') {
            if ($cek_role->name == 'BCD Development SPV' || $cek_role->name == 'BCD Development') {
                // $getGroupRoles = DB::table('role_user')->join('roles','roles.id','role_user.role_id')->select('name')->where('user_id',Auth::User()->nik)->first()->name;

                // $data = DB::table('tb_timesheet_config')->join('roles','roles.id','tb_timesheet_config.roles')->join('tb_timesheet_phase', function ($join) {
                //     $join->on(DB::raw('JSON_CONTAINS(tb_timesheet_config.task, CAST(tb_timesheet_phase.id AS JSON), "$")'), '=', DB::raw('1'));
                // })
                // ->select('tb_timesheet_phase.id as id', 'tb_timesheet_phase.phase as text')->where('name','BCD Development')->distinct()->get();

                $data = DB::table('tb_timesheet_config')->join('roles','roles.id','tb_timesheet_config.roles')->join('tb_timesheet_phase',function ($join) {
                    $join->on('tb_timesheet_config.phase', 'LIKE', DB::raw("CONCAT('%', tb_timesheet_phase.id, '%')"));
                })
                ->select('tb_timesheet_phase.id as id', 'tb_timesheet_phase.phase as text')->where('name','BCD Development')->get();
            } else {
                $getGroupRoles = DB::table('role_user')->join('roles','roles.id','role_user.role_id')->select('name')->where('user_id',Auth::User()->nik)->first()->name;

                // $data = DB::table('tb_timesheet_config')->join('roles','roles.id','tb_timesheet_config.roles')->join('tb_timesheet_phase', function ($join) {
                //     $join->on(DB::raw('JSON_CONTAINS(tb_timesheet_config.task, CAST(tb_timesheet_phase.id AS JSON), "$")'), '=', DB::raw('1'));
                // })
                $data = DB::table('tb_timesheet_config')->join('roles','roles.id','tb_timesheet_config.roles')->join('tb_timesheet_phase',function ($join) {
                    $join->on('tb_timesheet_config.phase', 'LIKE', DB::raw("CONCAT('%', tb_timesheet_phase.id, '%')"));
                })
                ->select('tb_timesheet_phase.id as id', 'tb_timesheet_phase.phase as text')->where('name',$getGroupRoles)->distinct()->get();
            }
            
        } else {
            $getGroupRoles = DB::table('role_user')->join('roles','roles.id','role_user.role_id')->select('group')->where('user_id',Auth::User()->nik)->first()->group;

            // $data = DB::table('tb_timesheet_config')->join('roles','roles.id','tb_timesheet_config.roles')->join('tb_timesheet_phase', function ($join) {
            //     $join->on(DB::raw('JSON_CONTAINS(tb_timesheet_config.task, CAST(tb_timesheet_phase.id AS JSON), "$")'), '=', DB::raw('1'));
            // })
            $data = DB::table('tb_timesheet_config')->join('roles','roles.id','tb_timesheet_config.roles')->join('tb_timesheet_phase',function ($join) {
                    $join->on('tb_timesheet_config.phase', 'LIKE', DB::raw("CONCAT('%', tb_timesheet_phase.id, '%')"));
                })
            ->select('tb_timesheet_phase.id as id', 'tb_timesheet_phase.phase as text')->where('group',$getGroupRoles)->distinct()->get();
        }

    	// $getAll = TimesheetConfig::join('roles','roles.id','tb_timesheet_config.roles')->select('task')->where('group',$getGroupRoles)->get();

		return $data;
    	
    }

    public function getAllPhase(Request $request)
    {
        if (isset($request->id)) {
            return $getPhase = TimesheetPhase::select('id as id','phase as text','description')->where('id',$request->id)->get();
        }else{
            return $getPhase = TimesheetPhase::select('id as id','phase as text')->get();
        }
    }

    public function getLockDurationByDivision(Request $request)
    {
    	return $getLockDuration = TimesheetLockDuration::select('lock_duration','division')->where('division',Auth::User()->id_division)->get();
    }

    public function getRoles()
    {
    	$getGroupRoles = DB::table('role_user')->join('roles','roles.id','role_user.role_id')->select('group')->where('user_id',Auth::User()->nik)->first()->group;

        if($getGroupRoles == 'msm'){
            $getRoles = DB::table('roles')->selectRaw('`id` AS `id`,CONCAT("MSM") AS `text`')->where("name","not like","%SPV%")->where("name","not like","%Manager%")->where("name","not like","%Director%")->where("name","not like","%MSP%")->where("name","not like","%Admin%")->where('group',$getGroupRoles)->distinct()->get()->take(1);
        } else if($getGroupRoles == 'pmo'){
            $getRoles = DB::table('roles')->selectRaw('`id` AS `id`,CONCAT("PMO") AS `text`')->where("name","not like","%SPV%")->where("name","not like","%Manager%")->where("name","not like","%Director%")->where("name","not like","%MSP%")->where("name","not like","%Admin%")->where('group',$getGroupRoles)->distinct()->get()->take(1);
        } else if($getGroupRoles == 'DPG'){
            $getRoles = DB::table('roles')->selectRaw('`id` AS `id`,CONCAT("SID") AS `text`')->where("name","not like","%SPV%")->where("name","not like","%Manager%")->where("name","not like","%Director%")->where("name","not like","%MSP%")->where("name","not like","%Admin%")->where('group',$getGroupRoles)->distinct()->get()->take(1);
        } else if($getGroupRoles == 'presales'){
            $getRoles = DB::table('roles')->selectRaw('`id` AS `id`,CONCAT("SOL") AS `text`')->where("name","not like","%SPV%")->where("name","not like","%Manager%")->where("name","not like","%Director%")->where("name","not like","%MSP%")->where("name","not like","%Admin%")->where('group',$getGroupRoles)->distinct()->get()->take(1);
        }else if($getGroupRoles == 'hr'){
            $getRoles = DB::table('roles')->selectRaw('`id` AS `id`,`name` AS `text`')->where("name","not like","%SPV%")->where("name","not like","%Manager%")->where("name","not like","%Director%")->where("name","not like","%MSP%")->where('group',$getGroupRoles)->distinct()->get();
        }  else {
            $getRoles = DB::table('roles')->select('id as id','name as text')->where("name","not like","%SPV%")->where("name","not like","%Manager%")->where("name","not like","%Director%")->where("name","not like","%MSP%")->where("name","not like","%Admin%")->where('group',$getGroupRoles)->distinct()->get();
        }

    	return $getRoles;
    }

    public function getAllUser()
    {
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('name', 'roles.group')->where('user_id', Auth::User()->nik)->first()->group; 

        return $getUser = User::select('users.nik as id', 'users.name as text')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group',$cek_role)->where('roles.name',"not like","%Manager%")->where('roles.name','not like','%MSM Helpdesk%')->where('status_karyawan','!=','dummy')->get();

    	// return $getUser = User::select('nik as id', 'name as text')->where('id_division',Auth::User()->id_division)->get();
    }

    public function getConfigByDivision()
    {
    	// return $getConfig = TimesheetConfig::join('tb_timesheet_phase','tb_timesheet_phase.id','tb_timesheet_config.phase')->join('tb_timesheet_task','tb_timesheet_task.id','tb_timesheet_config.task')->join('roles','roles.id','tb_timesheet_config.roles')->select('roles.name','tb_timesheet_task.task','tb_timesheet_phase.phase','division','tb_timesheet_config.date_add')->where('division',Auth::User()->id_division)->get();
    	return $getConfig = TimesheetConfig::where('division',Auth::User()->id_division)->get();

    	// return unserialize($getConfig->phase);
    }

    public function getAllPhaseTask(Request $request)
    {
    	$dataTask = TimesheetTask::select('task as name','description','id')->get();
    	$dataPhase = TimesheetPhase::select('phase as name','description','id')->get();

        if (isset($request->type)) {
            if ($request->type == 'Task') {
                $array = $dataTask;
            }else if ($request->type == 'Phase') {
                $array = $dataPhase;
            }
        }else{
            $array = array_merge($dataTask->toArray(),$dataPhase->toArray());
        }

		return array("data"=>$array);
    }

    public function getAllActivityByUser(Request $request)
    {
    	$startDate = Carbon::now()->startOfYear()->format("Y-m-d");
        $endDate = Carbon::now()->endOfYear()->format("Y-m-d");

        $hidden = ['planned','threshold'];

    	$data = DB::table('tb_timesheet')->where('nik',$request->nik)->orderby('id','asc')->get();

    	$getLock = TimesheetLockDuration::where('division',Auth::User()->id_division)->first();

    	$getLeavingPermit = Cuti::join('tb_cuti_detail','tb_cuti_detail.id_cuti','tb_cuti.id_cuti')->select('date_off as start_date','reason_leave as activity')->where('nik',$request->nik)->where('tb_cuti.status','v')->orderby('start_date','desc')->get();

    	$holiday = $this->getWorkDays($startDate,$endDate)["holiday"]->values();

    	$getPermit = TimesheetPermit::select('start_date','end_date','status as remarks','activity','id')->where('nik',$request->nik)->get();

    	$array = array_merge($data->toArray(),$getLeavingPermit->toArray(),$holiday->toArray(),$getPermit->toArray());

    	return collect(["data"=>$array,
    		"lock_duration"=>empty($getLock->lock_duration)?(empty(DB::table('tb_timesheet_lock_duration')->where('division',Auth::User()->id_division)->first()->lock_duration) ? "1" : DB::table('tb_timesheet_lock_duration')->where('division',Auth::User()->id_division)->first()->lock_duration):$getLock->lock_duration]);
    }

    public function getWorkDays($startDate,$endDate){
        $client = new Client();
        $api_response = $client->get('https://www.googleapis.com/calendar/v3/calendars/en.indonesian%23holiday%40group.v.calendar.google.com/events?key='.env('GOOGLE_API_KEY'));
        // $api_response = $client->get('https://aws-cron.sifoma.id/holiday.php?key='.env('GOOGLE_API_KEY'));
        // $api_response = $client->get('https://aws-cron.sifoma.id/holiday.php?key=AIzaSyBNVCp8lA_LCRxr1rCYhvFIUNSmDsbcGno');
        $json = (string)$api_response->getBody();
        $holiday_indonesia = json_decode($json, true);

        $holiday_indonesia_final_detail = collect();
        $holiday_indonesia_final_date = collect();
        
        foreach ($holiday_indonesia["items"] as $value) {
            if(( ( $value["start"]["date"] >= $startDate ) && ( $value["start"]["date"] <= $endDate ) )){
                $holiday_indonesia_final_detail->push(["start_date" => $value["start"]["date"],"activity" => $value["summary"],"remarks" => "Cuti Bersama"]);
                $holiday_indonesia_final_date->push($value["start"]["date"]);
            }
        }

        $period = new DatePeriod(
             new DateTime($startDate),
             new DateInterval('P1D'),
             new DateTime($endDate . '23:59:59')
        );

        $workDays = collect();
        foreach($period as $date){
            if(!($date->format("N") == 6 || $date->format("N") == 7)){
                $workDays->push($date->format("Y-m-d"));
            }
        }

        // return $period;

        $workDaysMinHoliday = $workDays->diff($holiday_indonesia_final_date->unique());
        $workDaysMinHolidayKeyed = $workDaysMinHoliday->map(function ($item, $key) {
            // return ["date" => $item];
            // return (object) array('date' => $item);
            return $item;
        });

        return collect(["holiday" => $holiday_indonesia_final_detail, "workdays" => $workDaysMinHolidayKeyed]);
        
    }

    public function getAllAssignPidByDivision(Request $request)
    {
        $nik = Auth::User()->nik;
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('name', 'roles.group')->where('user_id', $nik)->first(); 

        if ($cek_role->name == 'BCD Manager' || $cek_role->name == 'BCD Development SPV') {
            $data = TimesheetPid::join('users','users.nik','tb_timesheet_pid.nik')->select('users.name','tb_timesheet_pid.pid','role')->where('id_division',Auth::User()->id_division)->get();
        } elseif ($cek_role->name == 'SOL Manager') {
            $data = TimesheetPid::join('users','users.nik','tb_timesheet_pid.nik')->select('users.name','tb_timesheet_pid.pid','role')->where('id_division',Auth::User()->id_division)->get();
        } elseif ($cek_role->name == 'SID Manager' || $cek_role->name == 'SID SPV') {
            $data = TimesheetPid::join('users','users.nik','tb_timesheet_pid.nik')->select('users.name','tb_timesheet_pid.pid','role')->where('id_division',Auth::User()->id_division)->get();
        } elseif ($cek_role->name == 'MSM Manager' || $cek_role->name == 'MSM TS SPV') {
            $data = TimesheetPid::join('users','users.nik','tb_timesheet_pid.nik')->select('users.name','tb_timesheet_pid.pid','role')->where('id_division',Auth::User()->id_division)->get();
        } elseif ($cek_role->name == 'PMO SPV' || $cek_role->name == 'PMO Manager') {
            $data = TimesheetPid::join('users','users.nik','tb_timesheet_pid.nik')->select('users.name','tb_timesheet_pid.pid','role')->where('id_division',Auth::User()->id_division)->get();
        } elseif ($cek_role->name == 'HR Manager') {
            $data = TimesheetPid::join('users','users.nik','tb_timesheet_pid.nik')->select('users.name','tb_timesheet_pid.pid','role')->where('id_division',Auth::User()->id_division)->get();
        } else {
            $getPidByNik = TimesheetPid::join('users','users.nik','tb_timesheet_pid.nik')->select('pid')->where('tb_timesheet_pid.nik',Auth::User()->nik)->get();
            $getPid = TimesheetPid::whereIn('pid',$getPidByNik)->get();
            $data = TimesheetPid::join('users','users.nik','tb_timesheet_pid.nik')->select('pid','users.name','role')->whereIn('pid',$getPidByNik)->get();
        }
    	
    	return array("data"=>$data);
    }

    public function getTaskPhaseByDivisionForTable(Request $request)
    {
        $nik = Auth::User()->nik;
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('name', 'roles.group')->where('user_id', $nik)->first(); 

    	$getGroupRoles = DB::table('role_user')->join('roles','roles.id','role_user.role_id')->select('group')->where('user_id',Auth::User()->nik)->first()->group;


        if ($cek_role->group == 'bcd') {
            if ($cek_role->name == 'BCD Development SPV' || $cek_role->name == 'BCD Development') {
                // $getGroupRoles = DB::table('role_user')->join('roles','roles.id','role_user.role_id')->select('name')->where('user_id',Auth::User()->nik)->first()->name;

                $data = DB::table('tb_timesheet_config')->join('roles','roles.id','tb_timesheet_config.roles')->join('tb_timesheet_task', function ($join) {
                    $join->on(DB::raw('JSON_CONTAINS(tb_timesheet_config.task, CAST(tb_timesheet_task.id AS JSON), "$")'), '=', DB::raw('1'));
                })
                ->select('tb_timesheet_task.id', 'tb_timesheet_task.task as title','tb_timesheet_task.description')->where('name','BCD Development')->distinct()->get();
            } else {
                $getGroupRoles = DB::table('role_user')->join('roles','roles.id','role_user.role_id')->select('name')->where('user_id',Auth::User()->nik)->first()->name;

                $data = DB::table('tb_timesheet_config')->join('roles','roles.id','tb_timesheet_config.roles')->join('tb_timesheet_task', function ($join) {
                    $join->on(DB::raw('JSON_CONTAINS(tb_timesheet_config.task, CAST(tb_timesheet_task.id AS JSON), "$")'), '=', DB::raw('1'));
                })
                ->select('tb_timesheet_task.id', 'tb_timesheet_task.task as title','tb_timesheet_task.description')->where('name',$getGroupRoles)->distinct()->get();
            }
            
        } else {
            $getGroupRoles = DB::table('role_user')->join('roles','roles.id','role_user.role_id')->select('group')->where('user_id',Auth::User()->nik)->first()->group;

            $data = DB::table('tb_timesheet_config')->join('roles','roles.id','tb_timesheet_config.roles')->join('tb_timesheet_task', function ($join) {
                $join->on(DB::raw('JSON_CONTAINS(tb_timesheet_config.task, CAST(tb_timesheet_task.id AS JSON), "$")'), '=', DB::raw('1'));
            })
            ->select('tb_timesheet_task.id', 'tb_timesheet_task.task as title','tb_timesheet_task.description')->where('group',$getGroupRoles)->distinct()->get();
        }

		// $data = DB::table('tb_timesheet_config')->join('roles','roles.id','tb_timesheet_config.roles')->join('tb_timesheet_task', function ($join) {
	 //        $join->on(DB::raw('JSON_CONTAINS(tb_timesheet_config.task, CAST(tb_timesheet_task.id AS JSON), "$")'), '=', DB::raw('1'));
	 //    })
	 //    ->select('tb_timesheet_task.id', 'tb_timesheet_task.task as title','tb_timesheet_task.description')->where('group',$getGroupRoles)->distinct()->get();


        if ($cek_role->group == 'bcd') {
            if ($cek_role->name == 'BCD Development SPV' || $cek_role->name == 'BCD Development') {
                // $getGroupRoles = DB::table('role_user')->join('roles','roles.id','role_user.role_id')->select('name')->where('user_id',Auth::User()->nik)->first()->name;

                // $dataPhase = DB::table('tb_timesheet_config')->join('roles','roles.id','tb_timesheet_config.roles')->join('tb_timesheet_phase', function ($join) {
                //     $join->on(DB::raw('JSON_CONTAINS(tb_timesheet_config.task, CAST(tb_timesheet_phase.id AS JSON), "$")'), '=', DB::raw('1'));
                // })
                $dataPhase = DB::table('tb_timesheet_config')->join('roles','roles.id','tb_timesheet_config.roles')->join('tb_timesheet_phase',function ($join) {
                    $join->on('tb_timesheet_config.phase', 'LIKE', DB::raw("CONCAT('%', tb_timesheet_phase.id, '%')"));
                })
                ->select('tb_timesheet_phase.id', 'tb_timesheet_phase.phase as title','tb_timesheet_phase.description')->where('name','BCD Development')->get();
            } else {
                $getGroupRoles = DB::table('role_user')->join('roles','roles.id','role_user.role_id')->select('name')->where('user_id',Auth::User()->nik)->first()->name;

                $dataPhase = DB::table('tb_timesheet_config')->join('roles','roles.id','tb_timesheet_config.roles')->join('tb_timesheet_phase',function ($join) {
                    $join->on('tb_timesheet_config.phase', 'LIKE', DB::raw("CONCAT('%', tb_timesheet_phase.id, '%')"));
                })
                ->select('tb_timesheet_phase.id', 'tb_timesheet_phase.phase as title','tb_timesheet_phase.description')->where('name',$getGroupRoles)->distinct()->get();
                // $dataPhase = DB::table('tb_timesheet_config')->join('roles','roles.id','tb_timesheet_config.roles')->join('tb_timesheet_phase', function ($join) {
                //     $join->on(DB::raw('JSON_CONTAINS(tb_timesheet_config.task, CAST(tb_timesheet_phase.id AS JSON), "$")'), '=', DB::raw('1'));
                // })
                
            }
            
        } else {
            $getGroupRoles = DB::table('role_user')->join('roles','roles.id','role_user.role_id')->select('group')->where('user_id',Auth::User()->nik)->first()->group;

            // $dataPhase = DB::table('tb_timesheet_config')->join('roles','roles.id','tb_timesheet_config.roles')->join('tb_timesheet_phase', function ($join) {
            //     $join->on(DB::raw('JSON_CONTAINS(tb_timesheet_config.task, CAST(tb_timesheet_phase.id AS JSON), "$")'), '=', DB::raw('1'));
            // })

            $dataPhase = DB::table('tb_timesheet_config')->join('roles','roles.id','tb_timesheet_config.roles')->join('tb_timesheet_phase',function ($join) {
                    $join->on('tb_timesheet_config.phase', 'LIKE', DB::raw("CONCAT('%', tb_timesheet_phase.id, '%')"));
                })

            ->select('tb_timesheet_phase.id', 'tb_timesheet_phase.phase as title','tb_timesheet_phase.description')->where('group',$getGroupRoles)->distinct()->get();
        }



		// $dataPhase = DB::table('tb_timesheet_config')->join('roles','roles.id','tb_timesheet_config.roles')->join('tb_timesheet_phase', function ($join) {
	 //        $join->on(DB::raw('JSON_CONTAINS(tb_timesheet_config.task, CAST(tb_timesheet_phase.id AS JSON), "$")'), '=', DB::raw('1'));
	 //    })
	 //    ->select('tb_timesheet_phase.id', 'tb_timesheet_phase.phase as title','tb_timesheet_phase.description')->where('group',$getGroupRoles)->distinct()->get();

		$array = array_merge($data->toArray(),$dataPhase->toArray());
		return collect(["Task"=>$data,"Phase"=>$dataPhase]);
    	
    }

  //   public function getPhaseByDivisionForTable(Request $request)
  //   {
  //   	$getGroupRoles = DB::table('role_user')->join('roles','roles.id','role_user.role_id')->select('group')->where('user_id',Auth::User()->nik)->first()->group;

  //   	// $getAll = TimesheetConfig::join('roles','roles.id','tb_timesheet_config.roles')->select('task')->where('group',$getGroupRoles)->get();

		// $data = DB::table('tb_timesheet_config')->join('roles','roles.id','tb_timesheet_config.roles')->join('tb_timesheet_phase', function ($join) {
	 //        $join->on(DB::raw('JSON_CONTAINS(tb_timesheet_config.task, CAST(tb_timesheet_phase.id AS JSON), "$")'), '=', DB::raw('1'));
	 //    })
	 //    ->select('tb_timesheet_phase.id', 'tb_timesheet_phase.phase','tb_timesheet_phase.description')->where('group',$getGroupRoles)->distinct()->get();

		// return array("data"=>$data);
    	
  //   }

    public function storePermit(Request $request)
    {
        $date = json_decode($request->inputDatePermit,true);
        foreach ($date as $value) {
            $store = new TimesheetPermit();
            $format_date     = strtotime($value);
            $store->start_date = date("Y-m-d",$format_date);
            $store->end_date = date("Y-m-d",$format_date);
            $store->activity = $request->textareaActivityPermit;
            $store->status = $request->selectPermit;
            $store->date_add = Carbon::now()->toDateTimeString();
            $store->nik = $request->nik;
            $store->save();
            $storeAll[] = $store;
        }

        return $storeAll;
    }

    public function deletePermit(Request $request)
    {
        $delete = TimesheetPermit::where('id',$request->id)->first();
        $delete->delete();
    }

    public function getNameByNik(Request $request)
    {
        return $getName = User::select('name','email')->where('nik',$request->nik)->first();
    }

    public function sumPointMandays(Request $request)
    {
        $nik = Auth::User()->nik;
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('name', 'roles.group')->where('user_id', $nik)->first();
        $getLeavingPermit = Cuti::join('tb_cuti_detail','tb_cuti_detail.id_cuti','tb_cuti.id_cuti')
                            ->join('users','users.nik','=','tb_cuti.nik')
                            ->select('date_off as date','users.name')
                            ->where('tb_cuti.status','v')
                            ->whereYear('date_off',date('Y'))
                            ->orderby('date','desc');

        $getPermit = TimesheetPermit::select('tb_timesheet_permit.start_date','tb_timesheet_permit.nik','users.name')->join('users','users.nik','=','tb_timesheet_permit.nik');

        if ($cek_role->group == 'pmo') {
            if ($cek_role->name == 'PMO Manager' || $cek_role->name == 'PMO SPV') {
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','pmo')->pluck('nik');

                $getLeavingPermit   = $getLeavingPermit->whereIn('tb_cuti.nik',$listGroup)->get();
                $getPermit          = $getPermit->whereIn('tb_timesheet_permit.nik',$listGroup)->get();
                $sumMandays         = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->select('point_mandays','users.name','tb_timesheet.nik','task')->selectRaw('MONTH(start_date) AS month_number')->whereIn('tb_timesheet.nik',$listGroup)->where('status','Done')->get();

                $getUserByGroup     = User::join('role_user', 'role_user.user_id', '=', 'users.nik')
                                        ->join('roles', 'roles.id', '=', 'role_user.role_id')
                                        ->select('users.name','users.nik')
                                        ->where('roles.group','pmo')
                                        ->where('roles.name','not like','%Manager')
                                        ->where('users.status_delete','-')
                                        ->whereNotIn('nik', $sumMandays->pluck('nik'))
                                        ->get();
                $isStaff = false;

            } else {
                $getLeavingPermit   = $getLeavingPermit->where('tb_cuti.nik',$nik)->get();
                $getPermit          = $getPermit->where('tb_timesheet_permit.nik',$nik)->get();
                $sumMandays         = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->select('point_mandays','users.name','tb_timesheet.nik','task')->selectRaw('MONTH(start_date) AS month_number')->where('tb_timesheet.nik',$nik)->where('status','Done')->get();
                $isStaff = true;

            }
        }elseif ($cek_role->group == 'DPG') {
            if ($cek_role->name == 'SID Manager' || $cek_role->name == 'SID SPV') {
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','DPG')->pluck('nik');

                $getLeavingPermit   = $getLeavingPermit->whereIn('tb_cuti.nik',$listGroup)->get();
                $getPermit          = $getPermit->whereIn('tb_timesheet_permit.nik',$listGroup)->get();
                $sumMandays         = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->select('point_mandays','users.name','tb_timesheet.nik','task')->selectRaw('MONTH(start_date) AS month_number')->whereIn('tb_timesheet.nik',$listGroup)->where('status','Done')->get();

                $getUserByGroup     = User::join('role_user', 'role_user.user_id', '=', 'users.nik')
                                        ->join('roles', 'roles.id', '=', 'role_user.role_id')
                                        ->select('users.name','users.nik')
                                        ->where('roles.group','DPG')
                                        ->where('roles.name','not like','%Manager')
                                        ->where('users.status_delete','-')
                                        ->whereNotIn('nik', $sumMandays->pluck('nik'))
                                        ->get();
                $isStaff = false;
                // return $getUserByGroup;
            } else {
                $getLeavingPermit   = $getLeavingPermit->where('tb_cuti.nik',$nik)->get();
                $getPermit          = $getPermit->where('tb_timesheet_permit.nik',$nik)->get();
                $sumMandays         = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->select('point_mandays','users.name','tb_timesheet.nik','task')->selectRaw('MONTH(start_date) AS month_number')->where('tb_timesheet.nik',$nik)->where('status','Done')->get();

                $isStaff = true;
            }
        }elseif ($cek_role->group == 'presales') {
            if ($cek_role->name == 'SOL Manager') {
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','presales')->pluck('nik');
                $getLeavingPermit   = $getLeavingPermit->whereIn('tb_cuti.nik',$listGroup)->get();
                $getPermit          = $getPermit->whereIn('tb_timesheet_permit.nik',$listGroup)->get();
                $sumMandays         = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->select('point_mandays','users.name','tb_timesheet.nik','task')->selectRaw('MONTH(start_date) AS month_number')->whereIn('tb_timesheet.nik',$listGroup)->where('status','Done')->get();

                $getUserByGroup     = User::join('role_user', 'role_user.user_id', '=', 'users.nik')
                                        ->join('roles', 'roles.id', '=', 'role_user.role_id')
                                        ->select('users.name','users.nik')
                                        ->where('roles.group','presales')
                                        ->where('roles.name','not like','%Manager')
                                        ->where('users.status_delete','-')
                                        ->whereNotIn('nik', $sumMandays->pluck('nik'))
                                        ->get();
                $isStaff = false;

            } else {
                $getLeavingPermit   = $getLeavingPermit->where('tb_cuti.nik',$nik)->get();
                $getPermit          = $getPermit->where('tb_timesheet_permit.nik',$nik)->get();
                $sumMandays         = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->select('point_mandays','users.name','tb_timesheet.nik','task')->selectRaw('MONTH(start_date) AS month_number')->where('tb_timesheet.nik',$nik)->where('status','Done')->get();

                $isStaff = true;

            }
        }elseif ($cek_role->group == 'bcd') {
            if ($cek_role->name == 'BCD Manager' || $cek_role->name == 'BCD Development SPV') {
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','bcd')->pluck('nik');

                $getLeavingPermit   = $getLeavingPermit->whereIn('tb_cuti.nik',$listGroup)->get();
                $getPermit          = $getPermit->whereIn('tb_timesheet_permit.nik',$listGroup)->get();
                $sumMandays         = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->select('point_mandays','users.name','tb_timesheet.nik','task')->selectRaw('MONTH(start_date) AS month_number')->whereIn('tb_timesheet.nik',$listGroup)->where('status','Done')->get();

                $getUserByGroup     = User::Rightjoin('role_user', 'role_user.user_id', '=', 'users.nik')
                                        ->Rightjoin('roles', 'roles.id', '=', 'role_user.role_id')
                                        ->select('users.name','users.nik')
                                        ->where('roles.group','bcd')
                                        ->where('roles.name','not like','%Manager')
                                        ->where('users.status_delete','-')
                                        ->whereNotIn('nik', $sumMandays->pluck('nik'))
                                        ->get();
                $isStaff = false;
            } else {
                $getLeavingPermit   = $getLeavingPermit->where('tb_cuti.nik',$nik)->get();
                $getPermit          = $getPermit->where('tb_timesheet_permit.nik',$nik)->get();
                $sumMandays         = DB::table('tb_timesheet')->Leftjoin('users','users.nik','tb_timesheet.nik')->select('point_mandays','users.name','tb_timesheet.nik','task')->selectRaw('MONTH(start_date) AS month_number')->where('tb_timesheet.nik',$nik)->where('status','Done')->get();
                $isStaff = true;
            }
        }elseif ($cek_role->group == 'hr') {
            if ($cek_role->name == 'HR Manager') {
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','hr')->pluck('nik');
                $getLeavingPermit   = $getLeavingPermit->whereIn('tb_cuti.nik',$listGroup)->get();
                $getPermit          = $getPermit->whereIn('tb_timesheet_permit.nik',$listGroup)->get();
                $sumMandays         = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->select('point_mandays','users.name','tb_timesheet.nik','task')->selectRaw('MONTH(start_date) AS month_number')->whereIn('tb_timesheet.nik',$listGroup)->where('status','Done')->get();

                $getUserByGroup     = User::join('role_user', 'role_user.user_id', '=', 'users.nik')
                                        ->join('roles', 'roles.id', '=', 'role_user.role_id')
                                        ->select('users.name','users.nik')
                                        ->where('roles.group','hr')
                                        ->where('roles.name','not like','%Manager')
                                        ->where('users.status_delete','-')
                                        ->whereNotIn('nik', $sumMandays->pluck('nik'))
                                        ->get();
                $isStaff = false;

            } else {
                $getLeavingPermit   = $getLeavingPermit->where('tb_cuti.nik',$nik)->get();
                $getPermit          = $getPermit->where('tb_timesheet_permit.nik',$nik)->get();
                $sumMandays         = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->select('point_mandays','users.name','tb_timesheet.nik','task')->selectRaw('MONTH(start_date) AS month_number')->where('tb_timesheet.nik',$nik)->where('status','Done')->get();
                $isStaff = true;

            }
        }elseif ($cek_role->group == 'msm') {
            if ($cek_role->name == 'MSM Manager' || $cek_role->name == 'MSM TS SPV') {
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','msm')->pluck('nik');

                $getLeavingPermit   = $getLeavingPermit->whereIn('tb_cuti.nik',$listGroup)->get();
                $getPermit          = $getPermit->whereIn('tb_timesheet_permit.nik',$listGroup)->get();
                $sumMandays         = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->select('point_mandays','users.name','tb_timesheet.nik','task')->selectRaw('MONTH(start_date) AS month_number')->whereIn('tb_timesheet.nik',$listGroup)->where('status','Done')->get();

                $getUserByGroup     = User::join('role_user', 'role_user.user_id', '=', 'users.nik')
                                        ->join('roles', 'roles.id', '=', 'role_user.role_id')
                                        ->select('users.name','users.nik')
                                        ->where('roles.group','msm')
                                        ->where('roles.name','not like','%Manager')
                                        ->where('roles.name','not like','%MSM Helpdesk%')
                                        ->where('users.status_delete','-')
                                        ->whereNotIn('nik', $sumMandays->pluck('nik'))
                                        ->get();

                $isStaff = false;

            } else {
                $getLeavingPermit   = $getLeavingPermit->where('tb_cuti.nik',$nik)->get();
                $getPermit          = $getPermit->where('tb_timesheet_permit.nik',$nik)->get();
                $sumMandays         = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->select('point_mandays','users.name','tb_timesheet.nik','task')->selectRaw('MONTH(start_date) AS month_number')->where('tb_timesheet.nik',$nik)->where('status','Done')->get();
                $isStaff = true;

            }
        }

        $getLeavingPermitByName = collect($getLeavingPermit)->groupBy('name');
        $getPermitByName        = collect($getPermit)->groupBy('name');
        $getTaskAvailableByName = collect($sumMandays->where('task',36)->groupBy('name'));

        $arrSumPoint = collect();

        if (count($sumMandays) === 0) {
            $startDate       = Carbon::now()->startOfYear()->format("Y-m-d");
            $endDate         = Carbon::now()->endOfYear()->format("Y-m-d");
            $workdays        = $this->getWorkDays($startDate,$endDate,"workdays");
            $workdays        = count($workdays["workdays"]);

            if ($isStaff == false) {
                foreach($getUserByGroup as $value_group){
                    $arrSumPoint->push(["name"=>$value_group->name,
                        "nik"       =>$value_group->nik,
                        "actual"    =>"-",
                        "planned"   =>$workdays,
                        "threshold" =>"-",
                        "billable"  =>"-",
                        "percentage_billable" =>"-",
                        "deviation" =>"-",
                        "total_task"=>"-"
                    ]);
                }
            }
        }else{
            $startDate       = Carbon::now()->startOfYear()->format("Y-m-d");
            $endDate         = Carbon::now()->endOfYear()->format("Y-m-d");
            $workdays        = $this->getWorkDays($startDate,$endDate,"workdays");
            $workdays        = count($workdays["workdays"]);

            $countPointByUser = $sumMandays->groupBy('name')->map(function ($group) {
                return round($group->count('point_mandays'),2);
            });

            $sumPointByUser = $sumMandays->groupBy('name')->map(function ($group) {
                return round($group->sum('point_mandays'),2);
            });

            $getPermitByName = $getPermitByName->map(function ($group) {
                return $group->count('start_date');
            });

            $getLeavingPermitByName = $getLeavingPermitByName->map(function ($group){
                return $group->count('date');
            });

            $getTaskAvailableByName = $getTaskAvailableByName->map(function ($group){
                return round($group->sum('point_mandays'),2);
            });

            $sumArrayPermitByName = array();
            // Merge the arrays and sum the values
            $mergedKeys = array_merge(array_keys(json_decode(json_encode($getPermitByName), true)), array_keys(json_decode(json_encode($getTaskAvailableByName), true)));
            $mergedKeys = array_unique($mergedKeys); // Remove duplicates

            foreach ($mergedKeys as $key) {
                $sumArrayPermitByName[$key] = (isset($getPermitByName[$key]) ? $getPermitByName[$key] : 0) + (isset($getTaskAvailableByName[$key]) ? $getTaskAvailableByName[$key] : 0);
            }

            $sumPointMandays = collect();
            foreach($sumPointByUser as $key_point => $valueSumPoint){
                $billable = isset($sumArrayPermitByName[$key_point])?$sumArrayPermitByName[$key_point]:0;
                // $billable = isset($getPermitByName[$key_point])?$getPermitByName[$key_point]:0;
                $sumPointMandays->push([
                    "name"=>$key_point,
                    "nik"=>collect($sumMandays)->where('name',$key_point)->first()->nik,
                    "actual"=>$valueSumPoint,
                    // "planned"=>collect($sumMandays)->first()->planned,
                    "planned"=>$workdays,
                    // "threshold"=>collect($sumMandays)->first()->threshold,
                    "threshold"=>$workdays*80/100,
                    "billable"=>number_format($valueSumPoint - $billable,2,'.',''),
                    // "percentage_billable"=>number_format(($valueSumPoint - $billable)/collect($sumMandays)->first()->planned*100,  2, '.', ''),
                    "percentage_billable"=>number_format(($valueSumPoint - $billable)/$workdays*100,  2, '.', ''),
                    "deviation"=>$workdays - $valueSumPoint,
                    // "deviation"=>collect($sumMandays)->first()->planned - $valueSumPoint,
                    // "total_task"=>'-'
                ]); 
            }  

            $collection = collect($sumPointMandays);        
            $uniqueCollection = $collection->groupBy('name')->map->first();

            foreach($uniqueCollection->all() as $key_uniq => $data_uniq){
                if ($data_uniq['name'] == $key_uniq) {
                    $arrSumPoint->push([
                        "name"      =>$data_uniq['name'],
                        "nik"       =>$data_uniq['nik'],
                        "actual"    =>$data_uniq['actual'],
                        "planned"   =>$data_uniq['planned'],
                        "threshold" =>$data_uniq['threshold'],
                        "billable"  =>$data_uniq['billable'],
                        "percentage_billable" =>$data_uniq['percentage_billable'] . "%",
                        "deviation" =>$data_uniq['deviation'],
                        "total_task"=>$countPointByUser[$key_uniq]
                    ]);
                }
            }

            if ($isStaff == false) {
                foreach($getUserByGroup as $value_group){
                    $arrSumPoint->push(["name"=>$value_group->name,
                        "nik"       =>$value_group->nik,
                        "actual"    =>"-",
                        "planned"   =>$workdays,
                        "threshold" =>"-",
                        "billable"  =>"-",
                        "percentage_billable" =>"-",
                        "deviation" =>"-",
                        "total_task"=>"-"
                    ]);
                }
            }
        }

        return array("data"=>$arrSumPoint);
    }

    public function sumPointSbe(Request $request)
    {
        $getSbe = SbeConfig::join('tb_sbe','tb_sbe.id','=','tb_sbe_config.id_sbe')
                ->join('tb_sbe_detail_config','tb_sbe_detail_config.id_config_sbe','=','tb_sbe_config.id')
                ->join('tb_sbe_detail_item','tb_sbe_detail_item.id','=','tb_sbe_detail_config.detail_item')
                ->join('tb_id_project','tb_id_project.lead_id','=','tb_sbe.lead_id')
                ->select('id_sbe','id_project','tb_id_project.name_project','tb_sbe_config.id as id_sbe_config','tb_sbe_detail_config.qty','tb_sbe_detail_config.item','project_type','manpower')
                ->where('tb_sbe_detail_item.detail_item','=','Mandays')
                ->where('tb_sbe.status','Fixed')
                ->where('tb_sbe_config.status','Choosed')
                ->get();
        
        $appendedAttributesToHide = ['link_document','detail_config','get_function','detail_all_config_choosed'];

        $getSbe->makeHidden($appendedAttributesToHide);

        $groupByProject = $getSbe->groupBy('id_project');

        // return $groupByProject;

        // return $getSbe->groupBy('id_project');
        $getSumPointByProject = collect();
        foreach($groupByProject as $key_pid => $value){
            $getSumPointByProject->push(['pid' => $key_pid]);
            // return $key_pid;
            // return $value['project_type']; 
        }
        $sumPointByProject = $getSumPointByProject->groupby('pid');

        foreach($groupByProject as $key_pid => $value){
            foreach($sumPointByProject as $key_group => $value_group){
                if ($key_group == $key_pid) {
                    foreach($value as $value_pid){
                        if ($value_pid['project_type'] == 'Implementation') {
                            // return "oke";
                            if ($value_pid['item'] == 'PM Maintenance') {
                                if (isset($getSumPointByProject[$key_group]['PMO'])) {
                                    // return "okee";
                                    $sumPointByProject[$key_group]["PMO"]["sumMandays"] = $sumPointByProject[$key_group]["PMO"]["sumMandays"] + (int)$value_pid['qty']; 
                                }else{
                                    $sumPointByProject[$key_group]->put("PMO",collect(["sumMandays"=>(int)$value_pid['qty'],$this->sumPointMandaysSbe("PMO",$key_group),"name_project"=>$value_pid['name_project']]));
                                }
                            }else{
                                if (isset($sumPointByProject[$key_group]['DPG'])) {
                                    $sumPointByProject[$key_group]["DPG"]["sumMandays"] = $sumPointByProject[$key_group]["DPG"]["sumMandays"] + (int)$value_pid['qty'];
                                }else{
                                    $sumPointByProject[$key_group]->put("DPG",collect(["sumMandays"=>(int)$value_pid['qty'],$this->sumPointMandaysSbe("DPG",$key_group),"name_project"=>$value_pid['name_project']]));
                                }
                            }
                        }else if($value_pid['project_type'] == 'Supply Only'){
                            // return $sumPointByProject["PMO"]["sumMandays"] + $value_pid['qty']; 
                            if (isset($sumPointByProject[$key_group]['PMO'])) {
                                // return "okee 5";

                                $sumPointByProject[$key_group]["PMO"]["sumMandays"] = $sumPointByProject[$key_group]["PMO"]["sumMandays"] + (int)$value_pid['qty']; 
                            }else{
                                $sumPointByProject[$key_group]->put("PMO",collect(["sumMandays"=>(int)$value_pid['qty']]));
                            }
                        }else if($value_pid['project_type'] == 'Maintenance'){
                            if ($value_pid['item'] == 'PM Maintenance') {
                                if (isset($sumPointByProject[$key_group]['PMO'])) {
                                    $sumPointByProject[$key_group]["PMO"]["sumMandays"]  = $sumPointByProject[$key_group]["PMO"]["sumMandays"] + (int)$value_pid['qty']; 
                                }else{
                                    $sumPointByProject[$key_group]->put("PMO",collect(["sumMandays"=>(int)$value_pid['qty'],$this->sumPointMandaysSbe("PMO",$key_group),"name_project"=>$value_pid['name_project']]));
                                }
                            }else{
                                if (isset($sumPointByProject[$key_group]['MSM'])) {
                                    $sumPointByProject[$key_group]["MSM"]["sumMandays"] = $sumPointByProject[$key_group]["MSM"]["sumMandays"] + (int)$value_pid['qty']; 
                                }else{
                                    $sumPointByProject[$key_group]->put("MSM",collect(["sumMandays"=>(int)$value_pid['qty'],$this->sumPointMandaysSbe("MSM",$key_group),"name_project"=>$value_pid['name_project']]));
                                }
                            }
                        }   
                    }
                }
            }
        }



        // $sumPointSbeFinal = collect();
        // foreach($sumPointByProject as $value_final){
        //     $sumPointSbeFinal->push(["pid"=>$value_final[0]["pid"],collect(["PMO"=>])=>$]);
        // }
        // $cobaMSM = $this->sumPointMandaysSbe("DPG","006/RTAA/SIP/I/2022");
        // return $cobaMSM;
        // return array("data"=>$sumPointByProject);
        $nik = Auth::User()->nik;
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('name', 'roles.group')->where('user_id', $nik)->first();

        $sumPointMandays = collect();

        foreach($sumPointByProject as $key_pid => $value_project){
            if (isset($value_project[$cek_role->group][0])) {
                foreach($value_project[$cek_role->group][0] as $data){
                    $sumPointMandays->push([
                        "name"          =>$data['name'],
                        "nik"           =>$data['nik'],
                        "planned"       =>$value_project[$cek_role->group]['sumMandays'],
                        "actual"        =>$data['actual'],
                        "pid"           =>$value_project[0]['pid'] . " - " . $value_project[$cek_role->group]['name_project']
                    ]);
                }
            }else{
                $sumPointMandays = $sumPointMandays;
            } 
        }

        return array("data"=>$sumPointMandays);
        // return $getAll->unique();
        // return $getSbe->makeHidden($appendedAttributesToHide)->groupBy('id_project');
    }

    public function sumPointMandaysSbe($role,$pid)
    {
        $nik = Auth::User()->nik;
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('name', 'roles.group')->where('user_id', $nik)->first(); 

        // return $role;
        if ($role == 'PMO') {
            if ($cek_role->name == 'PMO Manager' || $cek_role->name == 'PMO SPV') {
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','pmo')->pluck('nik');
                $sumMandays         = Timesheet::join('users','users.nik','tb_timesheet.nik')->select('point_mandays','users.name','tb_timesheet.nik')->selectRaw('MONTH(start_date) AS month_number')->whereIn('tb_timesheet.nik',$listGroup)->where('status','Done')->where('pid',$pid)->where('type','project')->get();
            } else {
                $sumMandays         = Timesheet::join('users','users.nik','tb_timesheet.nik')->select('point_mandays','users.name','tb_timesheet.nik')->selectRaw('MONTH(start_date) AS month_number')->where('tb_timesheet.nik',$nik)->where('status','Done')->where('pid',$pid)->where('type','project')->get();
            }
        }elseif ($role == 'DPG') {
            if ($cek_role->name == 'SID Manager' || $cek_role->name == 'SID SPV') {
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','DPG')->pluck('nik');

                $sumMandays         = Timesheet::join('users','users.nik','tb_timesheet.nik')->select('point_mandays','users.name','tb_timesheet.nik')->selectRaw('MONTH(start_date) AS month_number')->whereIn('tb_timesheet.nik',$listGroup)->where('status','Done')->where('pid',$pid)->where('type','project')->get();
            } else {
                $sumMandays         = Timesheet::join('users','users.nik','tb_timesheet.nik')->select('point_mandays','users.name','tb_timesheet.nik')->selectRaw('MONTH(start_date) AS month_number')->where('tb_timesheet.nik',$nik)->where('status','Done')->where('pid',$pid)->where('type','project')->get();
            }
        }elseif ($role == 'MSM') {
            if ($cek_role->name == 'SOL Manager') {
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','msm')->pluck('nik');
                $sumMandays         = Timesheet::join('users','users.nik','tb_timesheet.nik')->select('point_mandays','users.name','tb_timesheet.nik')->selectRaw('MONTH(start_date) AS month_number')->whereIn('tb_timesheet.nik',$listGroup)->where('status','Done')->where('pid',$pid)->where('type','project')->get();
            } else {
                $sumMandays         = Timesheet::join('users','users.nik','tb_timesheet.nik')->select('point_mandays','users.name','tb_timesheet.nik')->selectRaw('MONTH(start_date) AS month_number')->where('tb_timesheet.nik',$nik)->where('status','Done')->where('pid',$pid)->where('type','project')->get();
            }
        }

        $sumPointByUser = $sumMandays->groupBy('name')->map(function ($group) {
            return round($group->sum('point_mandays'),2);
        });


        $sumPointMandays = collect();
        foreach($sumPointByUser as $key_point => $valueSumPoint){
            $sumPointMandays->push(["name"=>$key_point,"nik"=>collect($sumMandays)->first()->nik,"actual"=>$valueSumPoint]); 
        }

        $collection = collect($sumPointMandays);        
        $uniqueCollection = $collection->groupBy('name')->map->first();

        $arrSumPoint = collect();
        foreach($uniqueCollection->all() as $key_uniq => $data_uniq){
            if ($data_uniq['name'] == $key_uniq) {
                $arrSumPoint->push([
                    "name"      =>$data_uniq['name'],
                    "nik"       =>collect($sumMandays)->first()->nik,
                    "actual"    =>$data_uniq['actual']
                ]);
            }
        }

        return $arrSumPoint;
    }

    public function getPercentage(Request $request)
    {
        $nik = Auth::User()->nik;
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('name', 'roles.group')->where('user_id', $nik)->first(); 
        $startDate = Carbon::now()->startOfMonth()->format("Y-m-d");
        $endDate = Carbon::now()->endOfMonth()->format("Y-m-d");
        $now = date('Y-m-d');

        $workdays = $this->getWorkDays($startDate,$endDate)["workdays"]->values();
        $getLeavingPermit = Cuti::join('tb_cuti_detail','tb_cuti_detail.id_cuti','tb_cuti.id_cuti')->select('date_off as date')->where('tb_cuti.status','v')->whereYear('date_off',date('Y'))->orderby('date','desc');

        $getPermit = TimesheetPermit::select('start_date');
        $workdays = count($workdays);

        $getData = User::select('nik','name')->where('nik',$nik)->get();
        $getLeavingPermit = $getLeavingPermit->where('nik',$nik)->get();
        $getPermit = $getPermit->where('nik',$nik)->get();
        $sumMandays = Timesheet::join('users','users.nik','tb_timesheet.nik')->selectRaw('tb_timesheet.nik')->selectRaw('users.name')->selectRaw('SUM(point_mandays) AS `point_mandays`')->where('tb_timesheet.nik',$nik)->where('status','Done')->whereMonth('start_date',date('m'))->groupby('tb_timesheet.nik')->get();

        // return count($sumMandays);

        $allWorkdays = $this->getWorkDays($startDate,$endDate)["workdays"]->values();
        $allWorkdays = $allWorkdays->toArray();

        $getAllPermit = collect();
        $getPermit = json_decode($getPermit, true);

        $getAllLeavingPermit = collect();
        $getLeavingPermit = json_decode($getLeavingPermit, true);

        foreach ($getPermit as $value) {
            $getAllPermit->push($value['start_date']);
        }

        foreach ($getLeavingPermit as $value) {
           $getAllLeavingPermit->push($value['date']);
        }

        $actualPlanned = DB::table('tb_timesheet')->select(DB::raw('SUM(point_mandays) as point_mandays'))
                ->where('tb_timesheet.nik',$nik)
                ->where('schedule','Planned')
                ->where('status','Done')
                ->where('start_date',date("Y-m-d"))->first();

        $actualUnplanned = DB::table('tb_timesheet')->select(DB::raw('SUM(point_mandays) as point_mandays'))
                ->where('tb_timesheet.nik',$nik)
                ->where('schedule','Unplanned')
                ->where('status','Done')
                ->where('start_date',date("Y-m-d"))->first();

        $getAllLeavingPermit = $getAllLeavingPermit->toArray();

        $all = array_merge($allWorkdays);

        $differenceArray = array_diff($all, $getAllPermit->toArray());
        $differenceArrayMerged = array_merge($differenceArray);
        // $differenceArray2 = array_diff($differenceArrayMerged, $getAllLeavingPermit);
        // $billable = count($differenceArray2);

        $isEndMonth = 'false';
        if ($now == $endDate) {
            $isEndMonth = 'true';
        }

        if (count($sumMandays) == 1) {
            $planned = $sumMandays->map(function ($item, $key) {
                $planned = $item['plannedMonth'];
                $actual = $item['point_mandays'];
                $name = $item['name'];
                return [$planned,$actual,$name];
            });

            // return $planned;

            $percentage = number_format($planned[0][1]/$planned[0][0]*100,  2, '.', '');

            return collect(['percentage'=>$percentage,'name'=>Auth::User()->name,'isEndMonth'=>$isEndMonth,'plannedToday'=>$actualPlanned->point_mandays,'unplannedToday'=>$actualUnplanned->point_mandays]);
        } else {
            return collect(['percentage'=>'0','name'=>Auth::User()->name,'isEndMonth'=>$isEndMonth,'plannedToday'=>'0','unplannedToday'=>'0']);
        } 
    }

    public function getLevelChart(Request $request)
    {
        $nik = Auth::User()->nik;
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('name', 'roles.group')->where('user_id', $nik)->first(); 

        if ($cek_role->group == 'pmo') {
            if ($cek_role->name == 'PMO Manager' || $cek_role->name == 'PMO SPV') {
                $level = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','pmo')
                // ->whereMonth('start_date',date('m'))->get();
                ->whereYear('start_date',date('Y'))
                        ->get();
            } else {
                $level = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('tb_timesheet.nik',Auth::User()->nik)
                // ->whereMonth('start_date',date('m'))->get();
                ->whereYear('start_date',date('Y'))
                        ->get();
            }
        }elseif ($cek_role->group == 'DPG') {
            if ($cek_role->name == 'SID Manager' || $cek_role->name == 'SID SPV') {
                $level = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','DPG')
                // ->whereMonth('start_date',date('m'))->get();
                ->whereYear('start_date',date('Y'))
                        ->get();
            } else {
                $level = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('tb_timesheet.nik',Auth::User()->nik)
                // ->whereMonth('start_date',date('m'))->get();
                ->whereYear('start_date',date('Y'))
                        ->get();
            }
        }elseif ($cek_role->group == 'presales') {
            if ($cek_role->name == 'SOL Manager') {
                $level = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','presales')
                // ->whereMonth('start_date',date('m'))->get();
                ->whereYear('start_date',date('Y'))
                        ->get();
            } else {
                $level = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('tb_timesheet.nik',Auth::User()->nik)
                // ->whereMonth('start_date',date('m'))->get();
                ->whereYear('start_date',date('Y'))
                        ->get();
            }
        }elseif ($cek_role->group == 'bcd') {
            if ($cek_role->name == 'BCD Manager' || $cek_role->name == 'BCD Development SPV') {
                $level = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','bcd')
                // ->whereMonth('start_date',date('m'))->get();
                ->whereYear('start_date',date('Y'))
                        ->get();
            } else {
                $level = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('tb_timesheet.nik',Auth::User()->nik)
                // ->whereMonth('start_date',date('m'))->get();
                ->whereYear('start_date',date('Y'))
                        ->get();
            }
        }elseif ($cek_role->group == 'hr') {
            if ($cek_role->name == 'HR Manager') {
                $level = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','hr')
                // ->whereMonth('start_date',date('m'))->get();
                ->whereYear('start_date',date('Y'))
                        ->get();
            } else {
                $level = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('tb_timesheet.nik',Auth::User()->nik)
                // ->whereMonth('start_date',date('m'))->get();
                ->whereYear('start_date',date('Y'))
                        ->get();
            }
        }elseif($cek_role->group == 'msm') {
            if ($cek_role->name == 'MSM Manager' || $cek_role->name == 'MSM TS SPV') {
                $level = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','msm')
                // ->whereMonth('start_date',date('m'))->get();
                ->whereYear('start_date',date('Y'))
                        ->get();
            } else {
                $level = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('tb_timesheet.nik',Auth::User()->nik)
                // ->whereMonth('start_date',date('m'))->get();
                ->whereYear('start_date',date('Y'))
                        ->get();
            }
        }

        if (count($level) == 0) {
            $hasil2 = [0,0,0,0];
        }else{
            $first = $level[0]->level;

            $hasil = [0,0,0,0,0];
            $bulan_angka = ['A', 'B', 'C', 'D', 'E'];
            $pie = 0;

            foreach ($bulan_angka as $key => $value2) {
                foreach ($level as $value) {
                        if ($value->level == $value2) {
                            $hasil[$key]++;
                            $pie++;
                        }
                    }
            }

            $hasil2 = [0,0,0,0,0];
            foreach ($hasil as $key => $value) {
                $hasil2[$key] = ($value/$pie)*100;
            }
        }
        
        return $hasil2;
    }

    public function getTaskChart(Request $request)
    {
        $nik = Auth::User()->nik;
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('name', 'roles.group')->where('user_id', $nik)->first(); 

        if ($cek_role->group == 'pmo') {
            if ($cek_role->name == 'PMO Manager' || $cek_role->name == 'PMO SPV') {
                $task = DB::table('tb_timesheet')
                    ->select('tb_timesheet_task.task as task_name')
                    ->selectRaw('COUNT(tb_timesheet_task.task) as total_aktivitas')
                    ->join('tb_timesheet_task','tb_timesheet_task.id','=','tb_timesheet.task')
                    ->join('users','users.nik','tb_timesheet.nik')
                    ->join('role_user', 'role_user.user_id', '=', 'users.nik')
                    ->join('roles', 'roles.id', '=', 'role_user.role_id')
                    ->where('roles.group','pmo')
                    ->groupBy('tb_timesheet.task')

                // ->whereMonth('start_date',date('m'))->get();
                    ->whereYear('start_date',date('Y'))
                    ->get();

                $count_task = $task->sum('total_aktivitas');
            } else {
                $task = DB::table('tb_timesheet')
                    ->select('tb_timesheet_task.task as task_name')
                    ->selectRaw('COUNT(tb_timesheet_task.task) as total_aktivitas')
                    ->join('tb_timesheet_task','tb_timesheet_task.id','=','tb_timesheet.task')
                    ->groupBy('tb_timesheet.task')
                    ->where('tb_timesheet.nik',Auth::User()->nik)
                // ->whereMonth('start_date',date('m'))->get();
                    ->whereYear('start_date',date('Y'))
                    ->get();

                $count_task = $task->sum('total_aktivitas');

                // $task = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('tb_timesheet.nik',Auth::User()->nik)
                // // ->whereMonth('start_date',date('m'))->get();
                // ->whereYear('start_date',date('Y'))
                //         ->get();
            }
        }elseif ($cek_role->group == 'DPG') {
            if ($cek_role->name == 'SID Manager' || $cek_role->name == 'SID SPV') {
                $task = DB::table('tb_timesheet')
                    ->select('tb_timesheet_task.task as task_name')
                    ->selectRaw('COUNT(tb_timesheet_task.task) as total_aktivitas')
                    ->join('tb_timesheet_task','tb_timesheet_task.id','=','tb_timesheet.task')
                    ->join('users','users.nik','tb_timesheet.nik')
                    ->join('role_user', 'role_user.user_id', '=', 'users.nik')
                    ->join('roles', 'roles.id', '=', 'role_user.role_id')
                    ->where('roles.group','DPG')
                    ->groupBy('tb_timesheet.task')

                // ->whereMonth('start_date',date('m'))->get();
                    ->whereYear('start_date',date('Y'))
                    ->get();

                $count_task = $task->sum('total_aktivitas');
            } else {
                $task = DB::table('tb_timesheet')
                    ->select('tb_timesheet_task.task as task_name')
                    ->selectRaw('COUNT(tb_timesheet_task.task) as total_aktivitas')
                    ->join('tb_timesheet_task','tb_timesheet_task.id','=','tb_timesheet.task')
                    ->groupBy('tb_timesheet.task')
                    ->where('tb_timesheet.nik',Auth::User()->nik)
                // ->whereMonth('start_date',date('m'))->get();
                    ->whereYear('start_date',date('Y'))
                    ->get();

                $count_task = $task->sum('total_aktivitas');

                // $task = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('tb_timesheet.nik',Auth::User()->nik)
                // // ->whereMonth('start_date',date('m'))->get();
                // ->whereYear('start_date',date('Y'))
                //         ->get();
            }
        }elseif ($cek_role->group == 'presales') {
            if ($cek_role->name == 'SOL Manager') {
                $task = DB::table('tb_timesheet')
                    ->select('tb_timesheet_task.task as task_name')
                    ->selectRaw('COUNT(tb_timesheet_task.task) as total_aktivitas')
                    ->join('tb_timesheet_task','tb_timesheet_task.id','=','tb_timesheet.task')
                    ->join('users','users.nik','tb_timesheet.nik')
                    ->join('role_user', 'role_user.user_id', '=', 'users.nik')
                    ->join('roles', 'roles.id', '=', 'role_user.role_id')
                    ->where('roles.group','presales')
                    ->groupBy('tb_timesheet.task')

                // ->whereMonth('start_date',date('m'))->get();
                    ->whereYear('start_date',date('Y'))
                    ->get();

                $count_task = $task->sum('total_aktivitas');
            } else {
                $task = DB::table('tb_timesheet')
                    ->select('tb_timesheet_task.task as task_name')
                    ->selectRaw('COUNT(tb_timesheet_task.task) as total_aktivitas')
                    ->join('tb_timesheet_task','tb_timesheet_task.id','=','tb_timesheet.task')
                    ->groupBy('tb_timesheet.task')
                    ->where('tb_timesheet.nik',Auth::User()->nik)
                // ->whereMonth('start_date',date('m'))->get();
                    ->whereYear('start_date',date('Y'))
                    ->get();

                $count_task = $task->sum('total_aktivitas');

                // $task = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('tb_timesheet.nik',Auth::User()->nik)
                // // ->whereMonth('start_date',date('m'))->get();
                // ->whereYear('start_date',date('Y'))
                //         ->get();
            }
        }elseif ($cek_role->group == 'bcd') {
            if ($cek_role->name == 'BCD Manager' || $cek_role->name == 'BCD Development SPV') {
                $task = DB::table('tb_timesheet')
                    ->select('tb_timesheet_task.task as task_name')
                    ->selectRaw('COUNT(tb_timesheet_task.task) as total_aktivitas')
                    ->join('tb_timesheet_task','tb_timesheet_task.id','=','tb_timesheet.task')
                    ->join('users','users.nik','tb_timesheet.nik')
                    ->join('role_user', 'role_user.user_id', '=', 'users.nik')
                    ->join('roles', 'roles.id', '=', 'role_user.role_id')
                    ->where('roles.group','bcd')
                    ->groupBy('tb_timesheet.task')

                // ->whereMonth('start_date',date('m'))->get();
                    ->whereYear('start_date',date('Y'))
                    ->get();

                $count_task = $task->sum('total_aktivitas');
            } else {
                $task = DB::table('tb_timesheet')
                    ->select('tb_timesheet_task.task as task_name')
                    ->selectRaw('COUNT(tb_timesheet_task.task) as total_aktivitas')
                    ->join('tb_timesheet_task','tb_timesheet_task.id','=','tb_timesheet.task')
                    ->groupBy('tb_timesheet.task')
                    ->where('tb_timesheet.nik',Auth::User()->nik)
                // ->whereMonth('start_date',date('m'))->get();
                    ->whereYear('start_date',date('Y'))
                    ->get();

                $count_task = $task->sum('total_aktivitas');

                // $task = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('tb_timesheet.nik',Auth::User()->nik)
                // // ->whereMonth('start_date',date('m'))->get();
                // ->whereYear('start_date',date('Y'))
                //         ->get();
            }
        }elseif ($cek_role->group == 'hr') {
            if ($cek_role->name == 'HR Manager') {
                $task = DB::table('tb_timesheet')
                    ->select('tb_timesheet_task.task as task_name')
                    ->selectRaw('COUNT(tb_timesheet_task.task) as total_aktivitas')
                    ->join('tb_timesheet_task','tb_timesheet_task.id','=','tb_timesheet.task')
                    ->join('users','users.nik','tb_timesheet.nik')
                    ->join('role_user', 'role_user.user_id', '=', 'users.nik')
                    ->join('roles', 'roles.id', '=', 'role_user.role_id')
                    ->where('roles.group','hr')
                    ->groupBy('tb_timesheet.task')

                // ->whereMonth('start_date',date('m'))->get();
                    ->whereYear('start_date',date('Y'))
                    ->get();

                $count_task = $task->sum('total_aktivitas');
            } else {
                $task = DB::table('tb_timesheet')
                    ->select('tb_timesheet_task.task as task_name')
                    ->selectRaw('COUNT(tb_timesheet_task.task) as total_aktivitas')
                    ->join('tb_timesheet_task','tb_timesheet_task.id','=','tb_timesheet.task')
                    ->groupBy('tb_timesheet.task')
                    ->where('tb_timesheet.nik',Auth::User()->nik)
                // ->whereMonth('start_date',date('m'))->get();
                    ->whereYear('start_date',date('Y'))
                    ->get();

                $count_task = $task->sum('total_aktivitas');

                // $task = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('tb_timesheet.nik',Auth::User()->nik)
                // // ->whereMonth('start_date',date('m'))->get();
                // ->whereYear('start_date',date('Y'))
                //         ->get();
            }
        }elseif($cek_role->group == 'msm') {
            if ($cek_role->name == 'MSM Manager' || $cek_role->name == 'MSM TS SPV') {
                $task = DB::table('tb_timesheet')
                    ->select('tb_timesheet_task.task as task_name')
                    ->selectRaw('COUNT(tb_timesheet_task.task) as total_aktivitas')
                    ->join('tb_timesheet_task','tb_timesheet_task.id','=','tb_timesheet.task')
                    ->join('users','users.nik','tb_timesheet.nik')
                    ->join('role_user', 'role_user.user_id', '=', 'users.nik')
                    ->join('roles', 'roles.id', '=', 'role_user.role_id')
                    ->where('roles.group','msm')
                    ->groupBy('tb_timesheet.task')

                // ->whereMonth('start_date',date('m'))->get();
                    ->whereYear('start_date',date('Y'))
                    ->get();

                $count_task = $task->sum('total_aktivitas');
            } else {
                $task = DB::table('tb_timesheet')
                    ->select('tb_timesheet_task.task as task_name')
                    ->selectRaw('COUNT(tb_timesheet_task.task) as total_aktivitas')
                    ->join('tb_timesheet_task','tb_timesheet_task.id','=','tb_timesheet.task')
                    ->groupBy('tb_timesheet.task')
                    ->where('tb_timesheet.nik',Auth::User()->nik)
                // ->whereMonth('start_date',date('m'))->get();
                    ->whereYear('start_date',date('Y'))
                    ->get();

                $count_task = $task->sum('total_aktivitas');

                // $task = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('tb_timesheet.nik',Auth::User()->nik)
                // // ->whereMonth('start_date',date('m'))->get();
                // ->whereYear('start_date',date('Y'))
                //         ->get();
            }
        }

        $hasil = collect();
        $label = collect();

        if (count($task) == 0) {
            $hasil->push(0);
        }else{
            foreach ($task as $value) {
                $label->push($value->task_name);
                $hasil->push(($value->total_aktivitas/$count_task)*100);   
            }
        }
    
        return collect(["label"=>$label,"data"=>$hasil]);
    }

    public function getPhaseChart(Request $request)
    {
        $nik = Auth::User()->nik;
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('name', 'roles.group')->where('user_id', $nik)->first(); 

        if ($cek_role->group == 'pmo') {
            if ($cek_role->name == 'PMO Manager' || $cek_role->name == 'PMO SPV') {
                $phase = DB::table('tb_timesheet')
                    ->select('tb_timesheet_phase.phase as phase_name')
                    ->selectRaw('COUNT(tb_timesheet_phase.phase) as total_aktivitas')
                    ->join('tb_timesheet_phase','tb_timesheet_phase.id','=','tb_timesheet.phase')
                    ->join('users','users.nik','tb_timesheet.nik')
                    ->join('role_user', 'role_user.user_id', '=', 'users.nik')
                    ->join('roles', 'roles.id', '=', 'role_user.role_id')
                    ->where('roles.group','pmo')
                    ->groupBy('tb_timesheet.phase')

                // ->whereMonth('start_date',date('m'))->get();
                    ->whereYear('start_date',date('Y'))
                    ->get();

                $count_phase = $phase->sum('total_aktivitas');
            } else {
                $phase = DB::table('tb_timesheet')
                    ->select('tb_timesheet_phase.phase as phase_name')
                    ->selectRaw('COUNT(tb_timesheet_phase.phase) as total_aktivitas')
                    ->join('tb_timesheet_phase','tb_timesheet_phase.id','=','tb_timesheet.phase')
                    ->groupBy('tb_timesheet.phase')
                    ->where('tb_timesheet.nik',Auth::User()->nik)
                // ->whereMonth('start_date',date('m'))->get();
                    ->whereYear('start_date',date('Y'))
                    ->get();

                $count_phase = $phase->sum('total_aktivitas');
            }
        }elseif ($cek_role->group == 'DPG') {
            if ($cek_role->name == 'SID Manager' || $cek_role->name == 'SID SPV') {
                $phase = DB::table('tb_timesheet')
                    ->select('tb_timesheet_phase.phase as phase_name')
                    ->selectRaw('COUNT(tb_timesheet_phase.phase) as total_aktivitas')
                    ->join('tb_timesheet_phase','tb_timesheet_phase.id','=','tb_timesheet.phase')
                    ->join('users','users.nik','tb_timesheet.nik')
                    ->join('role_user', 'role_user.user_id', '=', 'users.nik')
                    ->join('roles', 'roles.id', '=', 'role_user.role_id')
                    ->where('roles.group','DPG')
                    ->groupBy('tb_timesheet.phase')

                // ->whereMonth('start_date',date('m'))->get();
                    ->whereYear('start_date',date('Y'))
                    ->get();

                $count_phase = $phase->sum('total_aktivitas');
            } else {
                $phase = DB::table('tb_timesheet')
                    ->select('tb_timesheet_phase.phase as phase_name')
                    ->selectRaw('COUNT(tb_timesheet_phase.phase) as total_aktivitas')
                    ->join('tb_timesheet_phase','tb_timesheet_phase.id','=','tb_timesheet.phase')
                    ->groupBy('tb_timesheet.phase')
                    ->where('tb_timesheet.nik',Auth::User()->nik)
                // ->whereMonth('start_date',date('m'))->get();
                    ->whereYear('start_date',date('Y'))
                    ->get();

                $count_phase = $phase->sum('total_aktivitas');
            }
        }elseif ($cek_role->group == 'presales') {
            if ($cek_role->name == 'SOL Manager') {
                $phase = DB::table('tb_timesheet')
                    ->select('tb_timesheet_phase.phase as phase_name')
                    ->selectRaw('COUNT(tb_timesheet_phase.phase) as total_aktivitas')
                    ->join('tb_timesheet_phase','tb_timesheet_phase.id','=','tb_timesheet.phase')
                    ->join('users','users.nik','tb_timesheet.nik')
                    ->join('role_user', 'role_user.user_id', '=', 'users.nik')
                    ->join('roles', 'roles.id', '=', 'role_user.role_id')
                    ->where('roles.group','presales')
                    ->groupBy('tb_timesheet.phase')

                // ->whereMonth('start_date',date('m'))->get();
                    ->whereYear('start_date',date('Y'))
                    ->get();

                $count_phase = $phase->sum('total_aktivitas');
            } else {
                $phase = DB::table('tb_timesheet')
                    ->select('tb_timesheet_phase.phase as phase_name')
                    ->selectRaw('COUNT(tb_timesheet_phase.phase) as total_aktivitas')
                    ->join('tb_timesheet_phase','tb_timesheet_phase.id','=','tb_timesheet.phase')
                    ->groupBy('tb_timesheet.phase')
                    ->where('tb_timesheet.nik',Auth::User()->nik)
                // ->whereMonth('start_date',date('m'))->get();
                    ->whereYear('start_date',date('Y'))
                    ->get();

                $count_phase = $phase->sum('total_aktivitas');
            }
        }elseif ($cek_role->group == 'bcd') {
            if ($cek_role->name == 'BCD Manager' || $cek_role->name == 'BCD Development SPV') {
                $phase = DB::table('tb_timesheet')
                    ->select('tb_timesheet_phase.phase as phase_name')
                    ->selectRaw('COUNT(tb_timesheet_phase.phase) as total_aktivitas')
                    ->join('tb_timesheet_phase','tb_timesheet_phase.id','=','tb_timesheet.phase')
                    ->join('users','users.nik','tb_timesheet.nik')
                    ->join('role_user', 'role_user.user_id', '=', 'users.nik')
                    ->join('roles', 'roles.id', '=', 'role_user.role_id')
                    ->where('roles.group','bcd')
                    ->groupBy('tb_timesheet.phase')

                // ->whereMonth('start_date',date('m'))->get();
                    ->whereYear('start_date',date('Y'))
                    ->get();

                $count_phase = $phase->sum('total_aktivitas');
            } else {
                $phase = DB::table('tb_timesheet')
                    ->select('tb_timesheet_phase.phase as phase_name')
                    ->selectRaw('COUNT(tb_timesheet_phase.phase) as total_aktivitas')
                    ->join('tb_timesheet_phase','tb_timesheet_phase.id','=','tb_timesheet.phase')
                    ->groupBy('tb_timesheet.phase')
                    ->where('tb_timesheet.nik',Auth::User()->nik)
                // ->whereMonth('start_date',date('m'))->get();
                    ->whereYear('start_date',date('Y'))
                    ->get();

                $count_phase = $phase->sum('total_aktivitas');
            }
        }elseif ($cek_role->group == 'hr') {
            if ($cek_role->name == 'HR Manager') {
                $phase = DB::table('tb_timesheet')
                    ->select('tb_timesheet_phase.phase as phase_name')
                    ->selectRaw('COUNT(tb_timesheet_phase.phase) as total_aktivitas')
                    ->join('tb_timesheet_phase','tb_timesheet_phase.id','=','tb_timesheet.phase')
                    ->join('users','users.nik','tb_timesheet.nik')
                    ->join('role_user', 'role_user.user_id', '=', 'users.nik')
                    ->join('roles', 'roles.id', '=', 'role_user.role_id')
                    ->where('roles.group','hr')
                    ->groupBy('tb_timesheet.phase')

                // ->whereMonth('start_date',date('m'))->get();
                    ->whereYear('start_date',date('Y'))
                    ->get();

                $count_phase = $phase->sum('total_aktivitas');
            } else {
                $phase = DB::table('tb_timesheet')
                    ->select('tb_timesheet_phase.phase as phase_name')
                    ->selectRaw('COUNT(tb_timesheet_phase.phase) as total_aktivitas')
                    ->join('tb_timesheet_phase','tb_timesheet_phase.id','=','tb_timesheet.phase')
                    ->groupBy('tb_timesheet.phase')
                    ->where('tb_timesheet.nik',Auth::User()->nik)
                // ->whereMonth('start_date',date('m'))->get();
                    ->whereYear('start_date',date('Y'))
                    ->get();

                $count_phase = $phase->sum('total_aktivitas');
            }
        }elseif($cek_role->group == 'msm') {
            if ($cek_role->name == 'MSM Manager' || $cek_role->name == 'MSM TS SPV') {
                $phase = DB::table('tb_timesheet')
                    ->select('tb_timesheet_phase.phase as phase_name')
                    ->selectRaw('COUNT(tb_timesheet_phase.phase) as total_aktivitas')
                    ->join('tb_timesheet_phase','tb_timesheet_phase.id','=','tb_timesheet.phase')
                    ->join('users','users.nik','tb_timesheet.nik')
                    ->join('role_user', 'role_user.user_id', '=', 'users.nik')
                    ->join('roles', 'roles.id', '=', 'role_user.role_id')
                    ->where('roles.group','msm')
                    ->groupBy('tb_timesheet.phase')

                // ->whereMonth('start_date',date('m'))->get();
                    ->whereYear('start_date',date('Y'))
                    ->get();

                $count_phase = $phase->sum('total_aktivitas');
            } else {
                $phase = DB::table('tb_timesheet')
                    ->select('tb_timesheet_phase.phase as phase_name')
                    ->selectRaw('COUNT(tb_timesheet_phase.phase) as total_aktivitas')
                    ->join('tb_timesheet_phase','tb_timesheet_phase.id','=','tb_timesheet.phase')
                    ->groupBy('tb_timesheet.phase')
                    ->where('tb_timesheet.nik',Auth::User()->nik)
                // ->whereMonth('start_date',date('m'))->get();
                    ->whereYear('start_date',date('Y'))
                    ->get();

                $count_phase = $phase->sum('total_aktivitas');

                // $task = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('tb_timesheet.nik',Auth::User()->nik)
                // // ->whereMonth('start_date',date('m'))->get();
                // ->whereYear('start_date',date('Y'))
                //         ->get();
            }
        }

        $hasil = collect();
        $label = collect();

        if (count($phase) == 0) {
            $hasil->push(0);
        }else{
            foreach ($phase as $value) {
                $label->push($value->phase_name);
                $hasil->push(($value->total_aktivitas/$count_phase)*100);   
            }
        }

        return collect(["label"=>$label,"data"=>$hasil]);
    
    }

    public function getStatusChart(Request $request)
    {
        $nik = Auth::User()->nik;
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('name', 'roles.group')->where('user_id', $nik)->first(); 

        if ($cek_role->group == 'pmo') {
            if ($cek_role->name == 'PMO Manager' || $cek_role->name == 'PMO SPV') {
                $status = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','pmo')
                        // ->whereMonth('start_date',date('m'))
                        ->whereYear('start_date',date('Y'))
                        ->get();
            } else {
                $status = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('tb_timesheet.nik',Auth::User()->nik)
                        // ->whereMonth('start_date',date('m'))
                        ->whereYear('start_date',date('Y'))
                        ->get();
            }
        }elseif ($cek_role->group == 'DPG') {
            if ($cek_role->name == 'SID Manager' || $cek_role->name == 'SID SPV') {
                $status = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','DPG')
                // ->whereMonth('start_date',date('m'))->get();
                ->whereYear('start_date',date('Y'))
                        ->get();
            } else {
                $status = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('tb_timesheet.nik',Auth::User()->nik)
                // ->whereMonth('start_date',date('m'))->get();
                ->whereYear('start_date',date('Y'))
                        ->get();
            }
        }elseif ($cek_role->group == 'presales') {
            if ($cek_role->name == 'SOL Manager') {
                $status = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','presales')
                // ->whereMonth('start_date',date('m'))->get();
                ->whereYear('start_date',date('Y'))
                        ->get();
            } else {
                $status = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('tb_timesheet.nik',Auth::User()->nik)
                // ->whereMonth('start_date',date('m'))->get();
                ->whereYear('start_date',date('Y'))
                        ->get();
            }
        }elseif ($cek_role->group == 'bcd') {
            if ($cek_role->name == 'BCD Manager' || $cek_role->name == 'BCD Development SPV') {
                $status = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','bcd')
                // ->whereMonth('start_date',date('m'))->get();
                ->whereYear('start_date',date('Y'))
                        ->get();
            } else {
                $status = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('tb_timesheet.nik',Auth::User()->nik)
                // ->whereMonth('start_date',date('m'))->get();
                ->whereYear('start_date',date('Y'))
                        ->get();
            }
        }elseif ($cek_role->group == 'hr') {
            if ($cek_role->name == 'HR Manager') {
                $status = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','hr')
                // ->whereMonth('start_date',date('m'))->get();
                ->whereYear('start_date',date('Y'))
                        ->get();
            } else {
                $status = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('tb_timesheet.nik',Auth::User()->nik)
                // ->whereMonth('start_date',date('m'))->get();
                ->whereYear('start_date',date('Y'))
                        ->get();
            }
        }elseif($cek_role->group == 'msm') {
            if ($cek_role->name == 'MSM Manager' || $cek_role->name == 'MSM TS SPV') {
                $status = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','msm')
                // ->whereMonth('start_date',date('m'))->get();
                ->whereYear('start_date',date('Y'))
                        ->get();
            } else {
                $status = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('tb_timesheet.nik',Auth::User()->nik)
                // ->whereMonth('start_date',date('m'))->get();
                ->whereYear('start_date',date('Y'))
                        ->get();
            }
        }

        // return $status[0]->status;

        // if (count($status) != 0) {
        //     $first = $status[0]->status;
        // }else{

        // }

        if (count($status) == 0) {
            $hasil2 = [0,0,0,0];
        }else{
            $hasil = [0,0,0,0];
            $bulan_angka = ['Done','NotDone','Cancel','Reschedule'];
            $pie = 0;

            foreach ($bulan_angka as $key => $value2) {
                foreach ($status as $value) {
                    if ($value->status == $value2) {
                        $hasil[$key]++;
                        $pie++;
                    }
                }
            }

            $hasil2 = [0,0,0,0];
            foreach ($hasil as $key => $value) {
                $hasil2[$key] = ($value/$pie)*100;
            }
        }

        return $hasil2;
    }

    public function getScheduleChart(Request $request)
    {
        $nik = Auth::User()->nik;
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('name', 'roles.group')->where('user_id', $nik)->first(); 

        if ($cek_role->group == 'pmo') {
            if ($cek_role->name == 'PMO Manager' || $cek_role->name == 'PMO SPV') {
                $schedule = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','pmo')
                // ->whereMonth('start_date',date('m'))->get();
                ->whereYear('start_date',date('Y'))
                        ->get();
            } else {
                $schedule = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('tb_timesheet.nik',Auth::User()->nik)
                // ->whereMonth('start_date',date('m'))->get();
                ->whereYear('start_date',date('Y'))
                        ->get();
            }
        }elseif ($cek_role->group == 'DPG') {
            if ($cek_role->name == 'SID Manager' || $cek_role->name == 'SID SPV') {
                $schedule = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','DPG')
                // ->whereMonth('start_date',date('m'))->get();
                ->whereYear('start_date',date('Y'))
                        ->get();
            } else {
                $schedule = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('tb_timesheet.nik',Auth::User()->nik)
                // ->whereMonth('start_date',date('m'))->get();
                ->whereYear('start_date',date('Y'))
                        ->get();
            }
        }elseif ($cek_role->group == 'presales') {
            if ($cek_role->name == 'SOL Manager') {
                $schedule = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','presales')
                // ->whereMonth('start_date',date('m'))->get();
                ->whereYear('start_date',date('Y'))
                        ->get();
            } else {
                $schedule = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('tb_timesheet.nik',Auth::User()->nik)
                // ->whereMonth('start_date',date('m'))->get();
                ->whereYear('start_date',date('Y'))
                        ->get();
            }
        }elseif ($cek_role->group == 'bcd') {
            if ($cek_role->name == 'BCD Manager' || $cek_role->name == 'BCD Development SPV') {
                $schedule = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','bcd')
                // ->whereMonth('start_date',date('m'))->get();
                ->whereYear('start_date',date('Y'))
                        ->get();
            } else {
                $schedule = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('tb_timesheet.nik',Auth::User()->nik)
                // ->whereMonth('start_date',date('m'))->get();
                ->whereYear('start_date',date('Y'))
                        ->get();
            }
        }elseif ($cek_role->group == 'hr') {
            if ($cek_role->name == 'HR Manager') {
                $schedule = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','hr')
                // ->whereMonth('start_date',date('m'))->get();
                ->whereYear('start_date',date('Y'))
                        ->get();
            } else {
                $schedule = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('tb_timesheet.nik',Auth::User()->nik)
                // ->whereMonth('start_date',date('m'))->get();
                ->whereYear('start_date',date('Y'))
                        ->get();
            }
        }elseif ($cek_role->group == 'msm') {
            if ($cek_role->name == 'MSM Manager' || $cek_role->name == 'MSM TS SPV') {
                $schedule = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','msm')
                // ->whereMonth('start_date',date('m'))->get();
                ->whereYear('start_date',date('Y'))
                        ->get();
            } else {
                $schedule = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('tb_timesheet.nik',Auth::User()->nik)
                // ->whereMonth('start_date',date('m'))->get();
                ->whereYear('start_date',date('Y'))
                        ->get();
            }
        }

        if (count($schedule) == 0) {
            $hasil2 = [0,0];
        }else{
            $first = $schedule[0]->schedule;

            $hasil = [0,0];
            $statusSchedule = ['Planned','Unplanned'];
            $pie = 0;

            foreach ($statusSchedule as $key => $value2) {
                foreach ($schedule as $value) {
                    if ($value->schedule == $value2) {
                        $hasil[$key]++;
                        $pie++;
                        // return $hasil;
                    }
                }
            }

            $hasil2 = [0,0];
            foreach ($hasil as $key => $value) {
                $hasil2[$key] = ($value/$pie)*100;
            }
        }

        return $hasil2;

        // return array($statusSchedule=>$hasil2);
    }

    public function getRemainingChart(Request $request)
    {
        $nik = Auth::User()->nik;
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('name', 'roles.group')->where('user_id', $nik)->first(); 

        $data = DB::table('tb_timesheet')
                ->join('users','tb_timesheet.nik','users.nik')->select('name','point_mandays','end_date','status','users.nik')->selectRaw('MONTH(start_date) AS month_number');

        $getUserByGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')
                        ->join('roles', 'roles.id', '=', 'role_user.role_id')
                        ->select('users.name')
                        ->where('roles.group',$cek_role->group)
                        ->where('roles.name','not like','%Manager')
                        ->where('users.status_delete','-')
                        ->get();

        if ($cek_role->group == 'pmo') {
            if ($cek_role->name == 'PMO Manager' || $cek_role->name == 'PMO SPV') {
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','pmo')->pluck('nik');
                
                $data = $data->whereIn('tb_timesheet.nik',$listGroup)->where('status','Done')->get();

                $data = $data->groupBy('month_number');

                $arrMonth = [1,2,3,4,5,6,7,8,9,10,11,12];

                $arrMonthMandays = [0,0,0,0,0,0,0,0,0,0,0,0];

                foreach($arrMonth as $key_months => $valueMonth){
                    $startDate = Carbon::now();
                    $startDate->month($valueMonth);

                    $endDate = Carbon::now();
                    $endDate->month($valueMonth);

                    $startDateFinal = $startDate->startOfMonth()->format("Y-m-d");
                    $endDateFinal = $endDate->endOfMonth()->format("Y-m-d");

                    foreach($arrMonthMandays as $key_mandays => $valueMandays){
                        if ($key_months == $key_mandays) {
                            $arrMonthMandays[$key_mandays]  = $arrMonthMandays[$key_mandays]+count($this->getWorkDays($startDateFinal,$endDateFinal)["workdays"]->values());
                        }
                    }
                }

                foreach($data as $key => $value){
                    $hasil_prosentase  = array($key => 0);
                    $hasil_remaining   = array($key => 0);

                    $arrName = collect();
                    $arrProsentaseByUser = collect();
                    $arrRemainingByUser = collect();
                    $arrFinalProsentaseByUser = collect();
                    $arrFinalRemainingByUser = collect();

                    foreach($value as $datas){
                        $arrName->push($datas->name);
                    }

                    foreach($getUserByGroup as $value_group){
                        $arrName->push($value_group->name);
                    }

                    foreach($arrName->unique() as $key_name => $value_name){
                        $arrProsentaseByUser->put($value_name,0); 
                        $arrRemainingByUser->put($value_name,0);
                    }

                    foreach($value as $datas){
                        foreach($hasil_prosentase as $key_prosentase => $value_prosentase){
                            foreach($arrProsentaseByUser as $key_byUser => $value_byUser){
                                    if ($key_byUser == $datas->name) {
                                    $arrProsentaseByUser[$key_byUser] = $value_byUser+$datas->point_mandays;
                                }
                            }
                            $hasil_prosentase[$key_prosentase] = $arrFinalProsentaseByUser;
                        }
                    }

                    foreach($arrProsentaseByUser as $key_byUsers => $value_byUsers){
                        $arrFinalProsentaseByUser->push(round((float)$value_byUsers / $arrMonthMandays[$key] * 100,2));
                        $arrFinalRemainingByUser->push(100 - (round(($value_byUsers / $arrMonthMandays[$key]) * 100,2)));

                    }

                    foreach($value as $datas){
                        foreach($hasil_prosentase as $key_prosentase => $value_prosentase){
                            $hasil_prosentase[$key_prosentase] = $arrFinalProsentaseByUser;
                        }

                        foreach($hasil_remaining as $key_remaining => $value_remaining){
                            $hasil_remaining[$key_remaining] = $arrFinalRemainingByUser;
                        }
                    }

                    foreach($arrMonth as $keys => $month){
                        if ($month == $key) {
                            $arrMonth[$keys] = array($key=>collect([
                                "arrName"=>array($arrName->unique()),
                                "label"=>array(
                                    "Prosentase"=>$hasil_prosentase[$key_prosentase],
                                    "Remaining"=>$hasil_remaining[$key_remaining]
                                )
                            ])); 
                        }
                    }
                }
            } else {
                $startDate = Carbon::now()->startOfMonth()->format("Y-m-d");
                $endDate = Carbon::now()->endOfMonth()->format("Y-m-d");

                $data = $data->where('tb_timesheet.nik',$nik)->where('status','Done')->get()->groupBy('month_number');

                $EffectiveMandaysMonthly = count($this->getWorkDays($startDate,$endDate)["workdays"]->values());
                $arrMonth = [1,2,3,4,5,6,7,8,9,10,11,12];

                foreach($data as $key => $value){
                    $hasil_prosentase  = array($key => 0);
                    $hasil_remaining   = array($key => 0);

                    foreach($value as $datas){
                        $arrName = collect([$datas->name]);
                        foreach($hasil_prosentase as $key_prosentase => $value_prosentase){
                            $hasil_prosentase[$key_prosentase] = $value_prosentase+$datas->point_mandays;
                        }

                        foreach($hasil_remaining as $key_remaining => $value_remaining){
                            $hasil_remaining[$key_remaining] = $value_remaining+$datas->point_mandays;
                        }
                    }

                    foreach($arrMonth as $keys => $month){
                        if ($month == $key) {
                            $arrMonth[$keys] = array($key=>collect([
                                "arrName"=>array($arrName),
                                "label"=>array(
                                    "Prosentase"=>array(round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2)),
                                    "Remaining"=>array((100 - round((($hasil_remaining[$key] / $EffectiveMandaysMonthly) * 100),2)))
                                )
                            ])); 
                        }
                    }
                }
            }
        }elseif ($cek_role->group == 'DPG') {
            if ($cek_role->name == 'SID Manager' || $cek_role->name == 'SID SPV') {
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','DPG')->pluck('nik');
                
                $data = $data->whereIn('tb_timesheet.nik',$listGroup)->where('status','Done')->get();

                $data = $data->groupBy('month_number');

                $arrMonth = [1,2,3,4,5,6,7,8,9,10,11,12];

                $arrMonthMandays = [0,0,0,0,0,0,0,0,0,0,0,0];

                foreach($arrMonth as $key_months => $valueMonth){
                    $startDate = Carbon::now();
                    $startDate->month($valueMonth);

                    $endDate = Carbon::now();
                    $endDate->month($valueMonth);

                    $startDateFinal = $startDate->startOfMonth()->format("Y-m-d");
                    $endDateFinal = $endDate->endOfMonth()->format("Y-m-d");

                    foreach($arrMonthMandays as $key_mandays => $valueMandays){
                        if ($key_months == $key_mandays) {
                            $arrMonthMandays[$key_mandays]  = $arrMonthMandays[$key_mandays]+count($this->getWorkDays($startDateFinal,$endDateFinal)["workdays"]->values());
                        }
                    }
                }

                foreach($data as $key => $value){
                    $hasil_prosentase  = array($key => 0);
                    $hasil_remaining   = array($key => 0);

                    $arrName = collect();
                    $arrProsentaseByUser = collect();
                    $arrRemainingByUser = collect();
                    $arrFinalProsentaseByUser = collect();
                    $arrFinalRemainingByUser = collect();

                    foreach($value as $datas){
                        $arrName->push($datas->name);
                    }

                    foreach($getUserByGroup as $value_group){
                        $arrName->push($value_group->name);
                    }

                    foreach($arrName->unique() as $key_name => $value_name){
                        $arrProsentaseByUser->put($value_name,0); 
                        $arrRemainingByUser->put($value_name,0);
                    }

                    foreach($value as $datas){
                        foreach($hasil_prosentase as $key_prosentase => $value_prosentase){
                            foreach($arrProsentaseByUser as $key_byUser => $value_byUser){
                                if ($key_byUser == $datas->name) {
                                    $arrProsentaseByUser[$key_byUser] = $value_byUser+$datas->point_mandays;
                                }
                            }
                            $hasil_prosentase[$key_prosentase] = $arrFinalProsentaseByUser;
                        }
                    }

                    foreach($arrProsentaseByUser as $key_byUsers => $value_byUsers){
                        $arrFinalProsentaseByUser->push(round((float)$value_byUsers / $arrMonthMandays[$key] * 100,2));
                        $arrFinalRemainingByUser->push(100 - (round(($value_byUsers / $arrMonthMandays[$key]) * 100,2)));

                    }

                    foreach($value as $datas){
                        foreach($hasil_prosentase as $key_prosentase => $value_prosentase){
                            $hasil_prosentase[$key_prosentase] = $arrFinalProsentaseByUser;
                        }

                        foreach($hasil_remaining as $key_remaining => $value_remaining){
                            $hasil_remaining[$key_remaining] = $arrFinalRemainingByUser;
                        }
                    }

                    foreach($arrMonth as $keys => $month){
                        if ($month == $key) {
                            $arrMonth[$keys] = array($key=>collect([
                                "arrName"=>array($arrName->unique()),
                                "label"=>array(
                                    "Prosentase"=>$hasil_prosentase[$key_prosentase],
                                    "Remaining"=>$hasil_remaining[$key_remaining]
                                )
                            ])); 
                        }
                    }
                }
            } else {
                $startDate = Carbon::now()->startOfMonth()->format("Y-m-d");
                $endDate = Carbon::now()->endOfMonth()->format("Y-m-d");

                $data = $data->where('tb_timesheet.nik',$nik)->where('status','Done')->get()->groupBy('month_number');

                $EffectiveMandaysMonthly = count($this->getWorkDays($startDate,$endDate)["workdays"]->values());
                $arrMonth = [1,2,3,4,5,6,7,8,9,10,11,12];

                foreach($data as $key => $value){
                    $hasil_prosentase  = array($key => 0);
                    $hasil_remaining   = array($key => 0);

                    foreach($value as $datas){
                        $arrName = collect([$datas->name]);
                        foreach($hasil_prosentase as $key_prosentase => $value_prosentase){
                            $hasil_prosentase[$key_prosentase] = $value_prosentase+$datas->point_mandays;
                        }

                        foreach($hasil_remaining as $key_remaining => $value_remaining){
                            $hasil_remaining[$key_remaining] = $value_remaining+$datas->point_mandays;
                        }
                    }

                    foreach($arrMonth as $keys => $month){
                        if ($month == $key) {
                            $arrMonth[$keys] = array($key=>collect([
                                "arrName"=>array($arrName),
                                "label"=>array(
                                    "Prosentase"=>array(round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2)),
                                    "Remaining"=>array((100 - round((($hasil_remaining[$key] / $EffectiveMandaysMonthly) * 100),2)))
                                )
                            ])); 
                        }
                    }
                }
            }
        }elseif ($cek_role->group == 'presales') {
            if ($cek_role->name == 'SOL Manager') {
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','presales')->pluck('nik');
                
                $data = $data->whereIn('tb_timesheet.nik',$listGroup)->where('status','Done')->get();

                $data = $data->groupBy('month_number');

                $arrMonth = [1,2,3,4,5,6,7,8,9,10,11,12];

                $arrMonthMandays = [0,0,0,0,0,0,0,0,0,0,0,0];

                foreach($arrMonth as $key_months => $valueMonth){
                    $startDate = Carbon::now();
                    $startDate->month($valueMonth);

                    $endDate = Carbon::now();
                    $endDate->month($valueMonth);

                    $startDateFinal = $startDate->startOfMonth()->format("Y-m-d");
                    $endDateFinal = $endDate->endOfMonth()->format("Y-m-d");

                    foreach($arrMonthMandays as $key_mandays => $valueMandays){
                        if ($key_months == $key_mandays) {
                            $arrMonthMandays[$key_mandays]  = $arrMonthMandays[$key_mandays]+count($this->getWorkDays($startDateFinal,$endDateFinal)["workdays"]->values());
                        }
                    }
                }

                foreach($data as $key => $value){
                    $hasil_prosentase  = array($key => 0);
                    $hasil_remaining   = array($key => 0);

                    $arrName = collect();
                    $arrProsentaseByUser = collect();
                    $arrRemainingByUser = collect();
                    $arrFinalProsentaseByUser = collect();
                    $arrFinalRemainingByUser = collect();

                    foreach($value as $datas){
                        $arrName->push($datas->name);
                    }

                    foreach($getUserByGroup as $value_group){
                        $arrName->push($value_group->name);
                    }

                    foreach($arrName->unique() as $key_name => $value_name){
                        $arrProsentaseByUser->put($value_name,0); 
                        $arrRemainingByUser->put($value_name,0);
                    }

                    foreach($value as $datas){
                        foreach($hasil_prosentase as $key_prosentase => $value_prosentase){
                            foreach($arrProsentaseByUser as $key_byUser => $value_byUser){
                                    if ($key_byUser == $datas->name) {
                                    $arrProsentaseByUser[$key_byUser] = $value_byUser+$datas->point_mandays;
                                }
                            }
                            $hasil_prosentase[$key_prosentase] = $arrFinalProsentaseByUser;
                        }
                    }

                    foreach($arrProsentaseByUser as $key_byUsers => $value_byUsers){
                        $arrFinalProsentaseByUser->push(round((float)$value_byUsers / $arrMonthMandays[$key] * 100,2));
                        $arrFinalRemainingByUser->push(100 - (round(($value_byUsers / $arrMonthMandays[$key]) * 100,2)));

                    }

                    foreach($value as $datas){
                        foreach($hasil_prosentase as $key_prosentase => $value_prosentase){
                            $hasil_prosentase[$key_prosentase] = $arrFinalProsentaseByUser;
                        }

                        foreach($hasil_remaining as $key_remaining => $value_remaining){
                            $hasil_remaining[$key_remaining] = $arrFinalRemainingByUser;
                        }
                    }

                    foreach($arrMonth as $keys => $month){
                        if ($month == $key) {
                            $arrMonth[$keys] = array($key=>collect([
                                "arrName"=>array($arrName->unique()),
                                "label"=>array(
                                    "Prosentase"=>$hasil_prosentase[$key_prosentase],
                                    "Remaining"=>$hasil_remaining[$key_remaining]
                                )
                            ])); 
                        }
                    }
                }
            } else {
                $startDate = Carbon::now()->startOfMonth()->format("Y-m-d");
                $endDate = Carbon::now()->endOfMonth()->format("Y-m-d");

                $data = $data->where('tb_timesheet.nik',$nik)->where('status','Done')->get()->groupBy('month_number');

                $EffectiveMandaysMonthly = count($this->getWorkDays($startDate,$endDate)["workdays"]->values());
                $arrMonth = [1,2,3,4,5,6,7,8,9,10,11,12];

                foreach($data as $key => $value){
                    $hasil_prosentase  = array($key => 0);
                    $hasil_remaining   = array($key => 0);

                    foreach($value as $datas){
                        $arrName = collect([$datas->name]);
                        foreach($hasil_prosentase as $key_prosentase => $value_prosentase){
                            $hasil_prosentase[$key_prosentase] = $value_prosentase+$datas->point_mandays;
                        }

                        foreach($hasil_remaining as $key_remaining => $value_remaining){
                            $hasil_remaining[$key_remaining] = $value_remaining+$datas->point_mandays;
                        }
                    }

                    foreach($arrMonth as $keys => $month){
                        if ($month == $key) {
                            $arrMonth[$keys] = array($key=>collect([
                                "arrName"=>array($arrName),
                                "label"=>array(
                                    "Prosentase"=>array(round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2)),
                                    "Remaining"=>array((100 - round((($hasil_remaining[$key] / $EffectiveMandaysMonthly) * 100),2)))
                                )
                            ])); 
                        }
                    }
                }
            }
        }elseif ($cek_role->group == 'bcd') {
            if ($cek_role->name == 'BCD Manager' || $cek_role->name == 'BCD Development SPV') {
                $listGroup = User::Rightjoin('role_user', 'role_user.user_id', '=', 'users.nik')->Rightjoin('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','bcd')->pluck('nik');

                $data = $data->whereIn('tb_timesheet.nik',$listGroup)->where('status','Done')->get();

                $data = $data->groupBy('month_number');

                $arrMonth = [1,2,3,4,5,6,7,8,9,10,11,12];

                $arrMonthMandays = [0,0,0,0,0,0,0,0,0,0,0,0];

                foreach($arrMonth as $key_months => $valueMonth){
                    $startDate = Carbon::now();
                    $startDate->month($valueMonth);

                    $endDate = Carbon::now();
                    $endDate->month($valueMonth);

                    $startDateFinal = $startDate->startOfMonth()->format("Y-m-d");
                    $endDateFinal = $endDate->endOfMonth()->format("Y-m-d");

                    foreach($arrMonthMandays as $key_mandays => $valueMandays){
                        if ($key_months == $key_mandays) {
                            $arrMonthMandays[$key_mandays]  = $arrMonthMandays[$key_mandays]+count($this->getWorkDays($startDateFinal,$endDateFinal)["workdays"]->values());
                        }
                    }
                }

                foreach($data as $key => $value){
                    $hasil_prosentase  = array($key => 0);
                    $hasil_remaining   = array($key => 0);

                    $arrName = collect();
                    $arrProsentaseByUser = collect();
                    $arrRemainingByUser = collect();
                    $arrFinalProsentaseByUser = collect();
                    $arrFinalRemainingByUser = collect();

                    foreach($value as $datas){
                        $arrName->push($datas->name);
                    }

                    foreach($getUserByGroup as $value_group){
                        $arrName->push($value_group->name);
                    }

                    foreach($arrName->unique() as $key_name => $value_name){
                        $arrProsentaseByUser->put($value_name,0); 
                        $arrRemainingByUser->put($value_name,0);
                    }

                    foreach($value as $datas){
                        foreach($hasil_prosentase as $key_prosentase => $value_prosentase){
                            foreach($arrProsentaseByUser as $key_byUser => $value_byUser){
                                if ($key_byUser == $datas->name) {
                                    $arrProsentaseByUser[$key_byUser] = $value_byUser+$datas->point_mandays;
                                }
                            }
                            $hasil_prosentase[$key_prosentase] = $arrFinalProsentaseByUser;
                        }
                    }

                    foreach($arrProsentaseByUser as $key_byUsers => $value_byUsers){
                        $arrFinalProsentaseByUser->push(round((float)$value_byUsers / $arrMonthMandays[$key] * 100,2));
                        $arrFinalRemainingByUser->push(100 - (round(($value_byUsers / $arrMonthMandays[$key]) * 100,2)));

                    }

                    foreach($value as $datas){
                        foreach($hasil_prosentase as $key_prosentase => $value_prosentase){
                            $hasil_prosentase[$key_prosentase] = $arrFinalProsentaseByUser;
                        }

                        foreach($hasil_remaining as $key_remaining => $value_remaining){
                            $hasil_remaining[$key_remaining] = $arrFinalRemainingByUser;
                        }
                    }

                    foreach($arrMonth as $keys => $month){
                        if ($month == $key) {
                            $arrMonth[$keys] = array($key=>collect([
                                "arrName"=>array($arrName->unique()),
                                "label"=>array(
                                    "Prosentase"=>$hasil_prosentase[$key_prosentase],
                                    "Remaining"=>$hasil_remaining[$key_remaining]
                                )
                            ])); 
                        }
                    }
                }
            } else {
                $startDate = Carbon::now()->startOfMonth()->format("Y-m-d");
                $endDate = Carbon::now()->endOfMonth()->format("Y-m-d");

                $data = $data->where('tb_timesheet.nik',$nik)->where('status','Done')->get()->groupBy('month_number');

                $EffectiveMandaysMonthly = count($this->getWorkDays($startDate,$endDate)["workdays"]->values());
                $arrMonth = [1,2,3,4,5,6,7,8,9,10,11,12];

                foreach($data as $key => $value){
                    $hasil_prosentase  = array($key => 0);
                    $hasil_remaining   = array($key => 0);

                    foreach($value as $datas){
                        $arrName = collect([$datas->name]);
                        foreach($hasil_prosentase as $key_prosentase => $value_prosentase){
                            $hasil_prosentase[$key_prosentase] = $value_prosentase+$datas->point_mandays;
                        }

                        foreach($hasil_remaining as $key_remaining => $value_remaining){
                            $hasil_remaining[$key_remaining] = $value_remaining+$datas->point_mandays;
                        }
                    }

                    foreach($arrMonth as $keys => $month){
                        if ($month == $key) {
                            $arrMonth[$keys] = array($key=>collect([
                                "arrName"=>array($arrName),
                                "label"=>array(
                                    "Prosentase"=>array(round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2)),
                                    "Remaining"=>array((100 - round((($hasil_remaining[$key] / $EffectiveMandaysMonthly) * 100),2)))
                                )
                            ])); 
                        }
                    }
                }

            }
        }elseif ($cek_role->group == 'hr') {
            if ($cek_role->name == 'HR Manager') {
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','hr')->pluck('nik');
                
                $data = $data->whereIn('tb_timesheet.nik',$listGroup)->where('status','Done')->get();

                $data = $data->groupBy('month_number');

                $arrMonth = [1,2,3,4,5,6,7,8,9,10,11,12];

                $arrMonthMandays = [0,0,0,0,0,0,0,0,0,0,0,0];

                foreach($arrMonth as $key_months => $valueMonth){
                    $startDate = Carbon::now();
                    $startDate->month($valueMonth);

                    $endDate = Carbon::now();
                    $endDate->month($valueMonth);

                    $startDateFinal = $startDate->startOfMonth()->format("Y-m-d");
                    $endDateFinal = $endDate->endOfMonth()->format("Y-m-d");

                    foreach($arrMonthMandays as $key_mandays => $valueMandays){
                        if ($key_months == $key_mandays) {
                            $arrMonthMandays[$key_mandays]  = $arrMonthMandays[$key_mandays]+count($this->getWorkDays($startDateFinal,$endDateFinal)["workdays"]->values());
                        }
                    }
                }

                foreach($data as $key => $value){
                    $hasil_prosentase  = array($key => 0);
                    $hasil_remaining   = array($key => 0);

                    $arrName = collect();
                    $arrProsentaseByUser = collect();
                    $arrRemainingByUser = collect();
                    $arrFinalProsentaseByUser = collect();
                    $arrFinalRemainingByUser = collect();

                    foreach($value as $datas){
                        $arrName->push($datas->name);
                    }

                    foreach($getUserByGroup as $value_group){
                        $arrName->push($value_group->name);
                    }

                    foreach($arrName->unique() as $key_name => $value_name){
                        $arrProsentaseByUser->put($value_name,0); 
                        $arrRemainingByUser->put($value_name,0);
                    }

                    foreach($value as $datas){
                        foreach($hasil_prosentase as $key_prosentase => $value_prosentase){
                            foreach($arrProsentaseByUser as $key_byUser => $value_byUser){
                                    if ($key_byUser == $datas->name) {
                                    $arrProsentaseByUser[$key_byUser] = $value_byUser+$datas->point_mandays;
                                }
                            }
                            $hasil_prosentase[$key_prosentase] = $arrFinalProsentaseByUser;
                        }
                    }

                    foreach($arrProsentaseByUser as $key_byUsers => $value_byUsers){
                        $arrFinalProsentaseByUser->push(round((float)$value_byUsers / $arrMonthMandays[$key] * 100,2));
                        $arrFinalRemainingByUser->push(100 - (round(($value_byUsers / $arrMonthMandays[$key]) * 100,2)));

                    }

                    foreach($value as $datas){
                        foreach($hasil_prosentase as $key_prosentase => $value_prosentase){
                            $hasil_prosentase[$key_prosentase] = $arrFinalProsentaseByUser;
                        }

                        foreach($hasil_remaining as $key_remaining => $value_remaining){
                            $hasil_remaining[$key_remaining] = $arrFinalRemainingByUser;
                        }
                    }

                    foreach($arrMonth as $keys => $month){
                        if ($month == $key) {
                            $arrMonth[$keys] = array($key=>collect([
                                "arrName"=>array($arrName->unique()),
                                "label"=>array(
                                    "Prosentase"=>$hasil_prosentase[$key_prosentase],
                                    "Remaining"=>$hasil_remaining[$key_remaining]
                                )
                            ])); 
                        }
                    }
                }
            } else {
                $startDate = Carbon::now()->startOfMonth()->format("Y-m-d");
                $endDate = Carbon::now()->endOfMonth()->format("Y-m-d");

                $data = $data->where('tb_timesheet.nik',$nik)->where('status','Done')->get()->groupBy('month_number');

                $EffectiveMandaysMonthly = count($this->getWorkDays($startDate,$endDate)["workdays"]->values());
                $arrMonth = [1,2,3,4,5,6,7,8,9,10,11,12];

                foreach($data as $key => $value){
                    $hasil_prosentase  = array($key => 0);
                    $hasil_remaining   = array($key => 0);

                    foreach($value as $datas){
                        $arrName = collect([$datas->name]);
                        foreach($hasil_prosentase as $key_prosentase => $value_prosentase){
                            $hasil_prosentase[$key_prosentase] = $value_prosentase+$datas->point_mandays;
                        }

                        foreach($hasil_remaining as $key_remaining => $value_remaining){
                            $hasil_remaining[$key_remaining] = $value_remaining+$datas->point_mandays;
                        }
                    }

                    foreach($arrMonth as $keys => $month){
                        if ($month == $key) {
                            $arrMonth[$keys] = array($key=>collect([
                                "arrName"=>array($arrName),
                                "label"=>array(
                                    "Prosentase"=>array(round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2)),
                                    "Remaining"=>array((100 - round((($hasil_remaining[$key] / $EffectiveMandaysMonthly) * 100),2)))
                                )
                            ])); 
                        }
                    }
                }
            }
        }elseif ($cek_role->group == 'msm') {
            if ($cek_role->name == 'MSM Manager' || $cek_role->name == 'MSM TS SPV') {
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','msm')->pluck('nik');

                $data = $data->whereIn('tb_timesheet.nik',$listGroup)->where('status','Done')->get();

                $data = $data->groupBy('month_number');

                $arrMonth = [1,2,3,4,5,6,7,8,9,10,11,12];

                $arrMonthMandays = [0,0,0,0,0,0,0,0,0,0,0,0];

                $startDate = Carbon::now();
                $endDate = Carbon::now();
                foreach($arrMonth as $key_months => $valueMonth){
                    $startDate->month($valueMonth);
                    $endDate->month($valueMonth);

                    $startDateFinal = $startDate->startOfMonth()->format("Y-m-d");
                    $endDateFinal = $endDate->endOfMonth()->format("Y-m-d");

                    foreach($arrMonthMandays as $key_mandays => $valueMandays){
                        if ($key_months == $key_mandays) {
                            $arrMonthMandays[$key_mandays]  = $arrMonthMandays[$key_mandays]+count($this->getWorkDays($startDateFinal,$endDateFinal)["workdays"]->values());
                        }
                    }
                }

                foreach($data as $key => $value){
                    $hasil_prosentase  = array($key => 0);
                    $hasil_remaining   = array($key => 0);

                    $arrName = collect();
                    $arrProsentaseByUser = collect();
                    $arrRemainingByUser = collect();
                    $arrFinalProsentaseByUser = collect();
                    $arrFinalRemainingByUser = collect();

                    foreach($value as $datas){
                        $arrName->push($datas->name);
                    }

                    foreach($getUserByGroup as $value_group){
                        $arrName->push($value_group->name);
                    }

                    foreach($arrName->unique() as $key_name => $value_name){
                        $arrProsentaseByUser->put($value_name,0); 
                        $arrRemainingByUser->put($value_name,0);
                    }

                    foreach($value as $datas){
                        foreach($hasil_prosentase as $key_prosentase => $value_prosentase){
                            foreach($arrProsentaseByUser as $key_byUser => $value_byUser){
                                    if ($key_byUser == $datas->name) {
                                    $arrProsentaseByUser[$key_byUser] = $value_byUser+$datas->point_mandays;
                                }
                            }
                            $hasil_prosentase[$key_prosentase] = $arrFinalProsentaseByUser;
                        }
                    }

                    foreach($arrProsentaseByUser as $key_byUsers => $value_byUsers){
                        $arrFinalProsentaseByUser->push(round((float)$value_byUsers / $arrMonthMandays[$key] * 100,2));
                        $arrFinalRemainingByUser->push(100 - (round(($value_byUsers / $arrMonthMandays[$key]) * 100,2)));

                    }

                    foreach($value as $datas){
                        foreach($hasil_prosentase as $key_prosentase => $value_prosentase){
                            $hasil_prosentase[$key_prosentase] = $arrFinalProsentaseByUser;
                        }

                        foreach($hasil_remaining as $key_remaining => $value_remaining){
                            $hasil_remaining[$key_remaining] = $arrFinalRemainingByUser;
                        }
                    }

                    foreach($arrMonth as $keys => $month){
                        if ($month == $key) {
                            $arrMonth[$keys] = array($key=>collect([
                                "arrName"=>array($arrName->unique()),
                                "label"=>array(
                                    "Prosentase"=>$hasil_prosentase[$key_prosentase],
                                    "Remaining"=>$hasil_remaining[$key_remaining]
                                )
                            ])); 
                        }
                    }
                }
            }else{
                $startDate = Carbon::now()->startOfMonth()->format("Y-m-d");
                $endDate = Carbon::now()->endOfMonth()->format("Y-m-d");

                $data = $data->where('tb_timesheet.nik',$nik)->where('status','Done')->get()->groupBy('month_number');

                $EffectiveMandaysMonthly = count($this->getWorkDays($startDate,$endDate)["workdays"]->values());
                $arrMonth = [1,2,3,4,5,6,7,8,9,10,11,12];

                foreach($data as $key => $value){
                    $hasil_prosentase  = array($key => 0);
                    $hasil_remaining   = array($key => 0);

                    foreach($value as $datas){
                        $arrName = collect([$datas->name]);
                        foreach($hasil_prosentase as $key_prosentase => $value_prosentase){
                            $hasil_prosentase[$key_prosentase] = $value_prosentase+$datas->point_mandays;
                        }

                        foreach($hasil_remaining as $key_remaining => $value_remaining){
                            $hasil_remaining[$key_remaining] = $value_remaining+$datas->point_mandays;
                        }
                    }

                    foreach($arrMonth as $keys => $month){
                        if ($month == $key) {
                            $arrMonth[$keys] = array($key=>collect([
                                "arrName"=>array($arrName),
                                "label"=>array(
                                    "Prosentase"=>array(round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2)),
                                    "Remaining"=>array((100 - round((($hasil_remaining[$key] / $EffectiveMandaysMonthly) * 100),2)))
                                )
                            ])); 
                        }
                    }
                }
            }
        }

        return $arrMonth;
    }

    public function getHoliday(Request $request)
    {
        $startDate = Carbon::now()->startOfYear()->format("Y-m-d");
        $endDate = Carbon::now()->endOfYear()->format("Y-m-d");
        return $workdays = $this->getWorkDays($startDate,$endDate)["holiday"]->values();
    }

    public function getCummulativeMandaysChart()
    {
        $nik = Auth::User()->nik;
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('name', 'roles.group')->where('user_id', $nik)->first(); 

        // $hasil = [0,0,0,0,0,0,0,0,0,0,0,0];

        $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $bulan_angka = [1,2,3,4,5,6,7,8,9,10,11,12];

        $data = DB::table('tb_timesheet')
                ->join('users','tb_timesheet.nik','users.nik')->select('name','point_mandays','end_date','status','users.nik')->selectRaw('MONTH(start_date) AS month_number');

        $startDate = Carbon::now()->startOfMonth()->format("Y-m-d");
        $endDate = Carbon::now()->endOfMonth()->format("Y-m-d");
        $workdays = $this->getWorkDays($startDate,$endDate,"workdays");
        $workdays = count($workdays['workdays']);

        $getUserByGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')
                        ->join('roles', 'roles.id', '=', 'role_user.role_id')
                        ->select('users.name')
                        ->where('roles.group',$cek_role->group)
                        ->where('roles.name','not like','%Manager')
                        ->where('roles.name','not like','%MSM Helpdesk')
                        ->where('users.status_delete','-')
                        ->whereNotIn('nik', $data->get()->pluck('nik'))
                        ->get();

        $arrCummulativeMandays = collect();
        if ($cek_role->group == 'pmo') {
            if ($cek_role->name == 'PMO Manager' || $cek_role->name == 'PMO SPV') {
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','pmo')->pluck('nik');

                $data = $data->whereIn('tb_timesheet.nik',$listGroup)->where('status','Done')->get()->groupBy('name');
                // return $data;
                if (count($data) == 0) {
                    foreach($getUserByGroup as $name_pic){
                        $arrayName = array($name_pic->name => [0,0,0,0,0,0,0,0,0,0,0,0]);
                        $arrCummulativeMandays->push(['name'=>$name_pic->name,'month_array'=>$arrayName]);
                    }
                }else{
                    foreach ($data as $key_value => $value) {
                        $arrayName = array($key_value => [0,0,0,0,0,0,0,0,0,0,0,0]);

                        foreach($data[$key_value] as $data_value){
                            if ($key_value === $data_value->name) {
                                foreach($arrayName as $key_month_name => $month_value){
                                    if ($key_month_name == $data_value->name) {
                                        foreach ($bulan_angka as $key_month => $value2) {
                                            if ($data_value->month_number == $value2) {
                                                $arrayName[$key_month_name][$key_month] = $month_value[$key_month]+$data_value->point_mandays;
                                            }
                                        }
                                    }
                                }
                            }
                        }

                       $arrCummulativeMandays->push(['name'=>$key_value,'month_array'=>$arrayName]);
                    }

                    if(isset($getUserByGroup)){
                        foreach($getUserByGroup as $key => $value_group){
                            $arrayName = array($value_group->name => [0,0,0,0,0,0,0,0,0,0,0,0]);

                            $arrCummulativeMandays->push(['name'=>$value_group->name,'month_array'=>$arrayName]);
                        }
                    }
                    
                }                
            } else {
                $data = $data->where('tb_timesheet.nik',$nik)->where('status','Done')->get();
                $arrayName = array(Auth::User()->name => [0,0,0,0,0,0,0,0,0,0,0,0]);
                if (count($data) == 0) {
                    $arrCummulativeMandays->push(['name'=>Auth::User()->name,'month_array'=>$arrayName]);
                }else{
                    foreach($arrayName as $key_month_name => $month_value){
                        if ($key_month_name == Auth::User()->name) {
                            foreach ($bulan_angka as $key => $value2) {
                               foreach ($data as $value) {
                                   if ($value->month_number == $value2) {
                                        $arrayName[$key_month_name][$key] = $arrayName[$key_month_name][$key]+$value->point_mandays;
                                    }
                                }
                            }
                        }
                    }

                    $arrCummulativeMandays->push(['name'=>$value->name,'month_array'=>$arrayName]);
                }
            }          
        }elseif ($cek_role->group == 'DPG') {
            if ($cek_role->name == 'SID Manager' || $cek_role->name == 'SID SPV') {
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','DPG')->pluck('nik');

                $data = $data->whereIn('tb_timesheet.nik',$listGroup)->where('status','Done')->get()->groupBy('name');
                // return $data;
                if (count($data) == 0) {
                    foreach($getUserByGroup as $name_pic){
                        $arrayName = array($name_pic->name => [0,0,0,0,0,0,0,0,0,0,0,0]);
                        $arrCummulativeMandays->push(['name'=>$name_pic->name,'month_array'=>$arrayName]);
                    }
                }else{
                    foreach ($data as $key_value => $value) {
                        $arrayName = array($key_value => [0,0,0,0,0,0,0,0,0,0,0,0]);

                        foreach($data[$key_value] as $data_value){
                            if ($key_value === $data_value->name) {
                                foreach($arrayName as $key_month_name => $month_value){
                                    if ($key_month_name == $data_value->name) {
                                        foreach ($bulan_angka as $key_month => $value2) {
                                            if ($data_value->month_number == $value2) {
                                                $arrayName[$key_month_name][$key_month] = $month_value[$key_month]+$data_value->point_mandays;
                                            }
                                        }
                                    }
                                }
                            }
                        }

                       $arrCummulativeMandays->push(['name'=>$key_value,'month_array'=>$arrayName]);
                    }

                    if(isset($getUserByGroup)){
                        foreach($getUserByGroup as $key => $value_group){
                            $arrayName = array($value_group->name => [0,0,0,0,0,0,0,0,0,0,0,0]);

                            $arrCummulativeMandays->push(['name'=>$value_group->name,'month_array'=>$arrayName]);
                        }
                    }
                    
                }                
            } else {
                $data = $data->where('tb_timesheet.nik',$nik)->where('status','Done')->get();
                $arrayName = array(Auth::User()->name => [0,0,0,0,0,0,0,0,0,0,0,0]);
                if (count($data) == 0) {
                    $arrCummulativeMandays->push(['name'=>Auth::User()->name,'month_array'=>$arrayName]);
                }else{
                    foreach($arrayName as $key_month_name => $month_value){
                        if ($key_month_name == Auth::User()->name) {
                            foreach ($bulan_angka as $key => $value2) {
                               foreach ($data as $value) {
                                   if ($value->month_number == $value2) {
                                        $arrayName[$key_month_name][$key] = $arrayName[$key_month_name][$key]+$value->point_mandays;
                                    }
                                }
                            }
                        }
                    }

                    $arrCummulativeMandays->push(['name'=>$value->name,'month_array'=>$arrayName]);
                }
            }          
        }elseif ($cek_role->group == 'presales') {
            if ($cek_role->name == 'SOL Manager') {
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','presales')->pluck('nik');
                $data = $data->whereIn('tb_timesheet.nik',$listGroup)->where('status','Done')->get()->groupBy('name');
                // return $data;
                if (count($data) == 0) {
                    foreach($getUserByGroup as $name_pic){
                        $arrayName = array($name_pic->name => [0,0,0,0,0,0,0,0,0,0,0,0]);
                        $arrCummulativeMandays->push(['name'=>$name_pic->name,'month_array'=>$arrayName]);
                    }
                }else{
                    foreach ($data as $key_value => $value) {
                        $arrayName = array($key_value => [0,0,0,0,0,0,0,0,0,0,0,0]);

                        foreach($data[$key_value] as $data_value){
                            if ($key_value === $data_value->name) {
                                foreach($arrayName as $key_month_name => $month_value){
                                    if ($key_month_name == $data_value->name) {
                                        foreach ($bulan_angka as $key_month => $value2) {
                                            if ($data_value->month_number == $value2) {
                                                $arrayName[$key_month_name][$key_month] = $month_value[$key_month]+$data_value->point_mandays;
                                            }
                                        }
                                    }
                                }
                            }
                        }

                       $arrCummulativeMandays->push(['name'=>$key_value,'month_array'=>$arrayName]);
                    }

                    if(isset($getUserByGroup)){
                        foreach($getUserByGroup as $key => $value_group){
                            $arrayName = array($value_group->name => [0,0,0,0,0,0,0,0,0,0,0,0]);

                            $arrCummulativeMandays->push(['name'=>$value_group->name,'month_array'=>$arrayName]);
                        }
                    }
                    
                }                
            } else {
                $data = $data->where('tb_timesheet.nik',$nik)->where('status','Done')->get();
                $arrayName = array(Auth::User()->name => [0,0,0,0,0,0,0,0,0,0,0,0]);
                if (count($data) == 0) {
                    $arrCummulativeMandays->push(['name'=>Auth::User()->name,'month_array'=>$arrayName]);
                }else{
                    foreach($arrayName as $key_month_name => $month_value){
                        if ($key_month_name == Auth::User()->name) {
                            foreach ($bulan_angka as $key => $value2) {
                               foreach ($data as $value) {
                                   if ($value->month_number == $value2) {
                                        $arrayName[$key_month_name][$key] = $arrayName[$key_month_name][$key]+$value->point_mandays;
                                    }
                                }
                            }
                        }
                    }

                    $arrCummulativeMandays->push(['name'=>$value->name,'month_array'=>$arrayName]);
                }
            }
        }elseif ($cek_role->group == 'bcd') {
            if ($cek_role->name == 'BCD Manager' || $cek_role->name == 'BCD Development SPV') {
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','bcd')->pluck('nik');
                
                $data = $data->whereIn('tb_timesheet.nik',$listGroup)->where('status','Done')->get()->groupBy('name');
                // return $data;
                if (count($data) == 0) {
                    foreach($getUserByGroup as $name_pic){
                        $arrayName = array($name_pic->name => [0,0,0,0,0,0,0,0,0,0,0,0]);
                        $arrCummulativeMandays->push(['name'=>$name_pic->name,'month_array'=>$arrayName]);
                    }
                }else{
                    foreach ($data as $key_value => $value) {
                        $arrayName = array($key_value => [0,0,0,0,0,0,0,0,0,0,0,0]);

                        foreach($data[$key_value] as $data_value){
                            if ($key_value === $data_value->name) {
                                foreach($arrayName as $key_month_name => $month_value){
                                    if ($key_month_name == $data_value->name) {
                                        foreach ($bulan_angka as $key_month => $value2) {
                                            if ($data_value->month_number == $value2) {
                                                $arrayName[$key_month_name][$key_month] = $month_value[$key_month]+$data_value->point_mandays;
                                            }
                                        }
                                    }
                                }
                            }
                        }

                       $arrCummulativeMandays->push(['name'=>$key_value,'month_array'=>$arrayName]);
                    }

                    if(isset($getUserByGroup)){
                        foreach($getUserByGroup as $key => $value_group){
                            $arrayName = array($value_group->name => [0,0,0,0,0,0,0,0,0,0,0,0]);

                            $arrCummulativeMandays->push(['name'=>$value_group->name,'month_array'=>$arrayName]);
                        }
                    }
                    
                }                
            } else {
                $data = $data->where('tb_timesheet.nik',$nik)->where('status','Done')->get();
                $arrayName = array(Auth::User()->name => [0,0,0,0,0,0,0,0,0,0,0,0]);
                if (count($data) == 0) {
                    $arrCummulativeMandays->push(['name'=>Auth::User()->name,'month_array'=>$arrayName]);
                }else{
                    foreach($arrayName as $key_month_name => $month_value){
                        if ($key_month_name == Auth::User()->name) {
                            foreach ($bulan_angka as $key => $value2) {
                               foreach ($data as $value) {
                                   if ($value->month_number == $value2) {
                                        $arrayName[$key_month_name][$key] = $arrayName[$key_month_name][$key]+$value->point_mandays;
                                    }
                                }
                            }
                        }
                    }

                    $arrCummulativeMandays->push(['name'=>$value->name,'month_array'=>$arrayName]);
                }
            }
        }elseif ($cek_role->group == 'hr') {
            if ($cek_role->name == 'HR Manager') {
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','hr')->pluck('nik');

                $data = $data->whereIn('tb_timesheet.nik',$listGroup)->where('status','Done')->get()->groupBy('name');
                // return $data;
                if (count($data) == 0) {
                    foreach($getUserByGroup as $name_pic){
                        $arrayName = array($name_pic->name => [0,0,0,0,0,0,0,0,0,0,0,0]);
                        $arrCummulativeMandays->push(['name'=>$name_pic->name,'month_array'=>$arrayName]);
                    }
                }else{
                    foreach ($data as $key_value => $value) {
                        $arrayName = array($key_value => [0,0,0,0,0,0,0,0,0,0,0,0]);

                        foreach($data[$key_value] as $data_value){
                            if ($key_value === $data_value->name) {
                                foreach($arrayName as $key_month_name => $month_value){
                                    if ($key_month_name == $data_value->name) {
                                        foreach ($bulan_angka as $key_month => $value2) {
                                            if ($data_value->month_number == $value2) {
                                                $arrayName[$key_month_name][$key_month] = $month_value[$key_month]+$data_value->point_mandays;
                                            }
                                        }
                                    }
                                }
                            }
                        }

                       $arrCummulativeMandays->push(['name'=>$key_value,'month_array'=>$arrayName]);
                    }

                    if(isset($getUserByGroup)){
                        foreach($getUserByGroup as $key => $value_group){
                            $arrayName = array($value_group->name => [0,0,0,0,0,0,0,0,0,0,0,0]);

                            $arrCummulativeMandays->push(['name'=>$value_group->name,'month_array'=>$arrayName]);
                        }
                    }
                    
                }                
            } else {
                $data = $data->where('tb_timesheet.nik',$nik)->where('status','Done')->get();
                $arrayName = array(Auth::User()->name => [0,0,0,0,0,0,0,0,0,0,0,0]);
                if (count($data) == 0) {
                    $arrCummulativeMandays->push(['name'=>Auth::User()->name,'month_array'=>$arrayName]);
                }else{
                    foreach($arrayName as $key_month_name => $month_value){
                        if ($key_month_name == Auth::User()->name) {
                            foreach ($bulan_angka as $key => $value2) {
                               foreach ($data as $value) {
                                   if ($value->month_number == $value2) {
                                        $arrayName[$key_month_name][$key] = $arrayName[$key_month_name][$key]+$value->point_mandays;
                                    }
                                }
                            }
                        }
                    }

                    $arrCummulativeMandays->push(['name'=>$value->name,'month_array'=>$arrayName]);
                }
            }
        }elseif ($cek_role->group == 'msm') {
            if ($cek_role->name == 'MSM Manager' || $cek_role->name == 'MSM TS SPV') {
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','msm')->pluck('nik');

                $data = $data->whereIn('tb_timesheet.nik',$listGroup)->where('status','Done')->get()->groupBy('name');
                // return $data;
                if (count($data) == 0) {
                    foreach($getUserByGroup as $name_pic){
                        $arrayName = array($name_pic->name => [0,0,0,0,0,0,0,0,0,0,0,0]);
                        $arrCummulativeMandays->push(['name'=>$name_pic->name,'month_array'=>$arrayName]);
                    }
                }else{
                    foreach ($data as $key_value => $value) {
                        $arrayName = array($key_value => [0,0,0,0,0,0,0,0,0,0,0,0]);

                        foreach($data[$key_value] as $data_value){
                            if ($key_value === $data_value->name) {
                                foreach($arrayName as $key_month_name => $month_value){
                                    if ($key_month_name == $data_value->name) {
                                        foreach ($bulan_angka as $key_month => $value2) {
                                            if ($data_value->month_number == $value2) {
                                                $arrayName[$key_month_name][$key_month] = $month_value[$key_month]+$data_value->point_mandays;
                                            }
                                        }
                                    }
                                }
                            }
                        }

                       $arrCummulativeMandays->push(['name'=>$key_value,'month_array'=>$arrayName]);
                    }

                    if(isset($getUserByGroup)){
                        foreach($getUserByGroup as $key => $value_group){
                            $arrayName = array($value_group->name => [0,0,0,0,0,0,0,0,0,0,0,0]);

                            $arrCummulativeMandays->push(['name'=>$value_group->name,'month_array'=>$arrayName]);
                        }
                    }
                    
                }                
            } else {
                $data = $data->where('tb_timesheet.nik',$nik)->where('status','Done')->get();
                $arrayName = array(Auth::User()->name => [0,0,0,0,0,0,0,0,0,0,0,0]);
                if (count($data) == 0) {
                    $arrCummulativeMandays->push(['name'=>Auth::User()->name,'month_array'=>$arrayName]);
                }else{
                    foreach($arrayName as $key_month_name => $month_value){
                        if ($key_month_name == Auth::User()->name) {
                            foreach ($bulan_angka as $key => $value2) {
                               foreach ($data as $value) {
                                   if ($value->month_number == $value2) {
                                        $arrayName[$key_month_name][$key] = $arrayName[$key_month_name][$key]+$value->point_mandays;
                                    }
                                }
                            }
                        }
                    }

                    $arrCummulativeMandays->push(['name'=>$value->name,'month_array'=>$arrayName]);
                }
            }
        }

        $arrCummulativeMandays->push(["workdays"=>$workdays]);
        return $arrCummulativeMandays;
    }

    public function getFilterSumPointMandays(Request $request)
    {
        // $countMonth = Timesheet::getPlannedAttribute($request->month)->get();
        $count = (new Timesheet)->getPlannedAttribute($request->month);
        $countThreshold = (new Timesheet)->getThresholdAttribute($request->month);

        // Return the filtered products or perform any other logic
        $countData = response()->json($count);
        $data = $countData->getData();
        $countPlanned = (int)$data;

        $countDataThreshold = response()->json($countThreshold);
        $dataThreshold = $countDataThreshold->getData();
        $countThresholdFinal = (int)$dataThreshold;

        $arrayMonth = collect();
        $arrSumPoint = collect();
        foreach($request->month as $month){
            $date = Carbon::parse($month);
            // Get the numeric representation of the month (1 to 12)
            $numericMonth = $date->month;
            // return $numericMonth;
            $arrayMonth->push($numericMonth);
        }      

        $nik = Auth::User()->nik;
        $cek_role = DB::table('role_user')->Rightjoin('roles', 'roles.id', '=', 'role_user.role_id')->select('name', 'roles.group')->where('user_id', $nik)->first(); 

        $getLeavingPermit = Cuti::join('tb_cuti_detail','tb_cuti_detail.id_cuti','tb_cuti.id_cuti')
                            ->join('users','users.nik','=','tb_cuti.nik')
                            ->select('date_off as date','users.name')
                            ->where('tb_cuti.status','v')
                            ->whereIn(\DB::raw('MONTH(date_off)'),$arrayMonth)
                            ->whereYear('date_off',date('Y'))
                            ->orderby('date','desc');


        $getPermit = TimesheetPermit::select('tb_timesheet_permit.start_date','tb_timesheet_permit.nik','users.name')->join('users','users.nik','=','tb_timesheet_permit.nik')->whereIn(\DB::raw('MONTH(start_date)'),$arrayMonth);

        $getUserByGroup     = User::join('role_user', 'role_user.user_id', '=', 'users.nik')
                                        ->join('roles', 'roles.id', '=', 'role_user.role_id')
                                        ->select('users.name','users.nik')
                                        ->where('roles.group',$cek_role->group)
                                        ->where('roles.name','not like','%Manager')
                                        ->where('users.status_delete','-');

        if ($cek_role->group == 'pmo') {
            if ($cek_role->name == 'PMO Manager' || $cek_role->name == 'PMO SPV') {
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','pmo')->pluck('nik');

                $sumMandays         = Timesheet::join('users','users.nik','tb_timesheet.nik')->select(DB::raw('CASE WHEN point_mandays IS NULL THEN 0 ELSE point_mandays END AS point_mandays'),'users.name','tb_timesheet.nik','task')->selectRaw('MONTH(start_date) AS month_number')->where(function ($query) use ($arrayMonth) {
                                            foreach ($arrayMonth as $month) {
                                                $query->orWhereRaw("MONTH(start_date) = $month");
                                            }
                                        });

                if ($request->pic[0] === null) {
                    $sumMandays = $sumMandays->whereIn('tb_timesheet.nik',$listGroup);
                    $getLeavingPermit   = $getLeavingPermit->whereIn('tb_cuti.nik',$listGroup)->get();
                    $getPermit          = $getPermit->whereIn('tb_timesheet_permit.nik',$listGroup)->get();

                    $getUserByGroup = $getUserByGroup->whereNotIn('nik', $sumMandays->get()->pluck('nik'))
                                        ->get();
                }else{
                    $sumMandays = $sumMandays->whereIn('tb_timesheet.nik',$request->pic);
                    $getLeavingPermit   = $getLeavingPermit->whereIn('tb_cuti.nik',$request->pic)->get();
                    $getPermit          = $getPermit->whereIn('tb_timesheet_permit.nik',$request->pic)->get();

                    $getUserByGroup = $getUserByGroup
                                        ->whereNotIn('nik', $sumMandays->get()->pluck('nik'))
                                        ->whereIn('nik',$request->pic)    
                                        ->get();
                }
                if (is_null($sumMandays)) {
                    $isNeedOtherUser = true;
                }else{
                    $isNeedOtherUser = false;
                }
            } else {
                $getLeavingPermit   = $getLeavingPermit->where('tb_cuti.nik',$nik)->get();
                $getPermit          = $getPermit->where('tb_timesheet_permit.nik',$nik)->get();
                $sumMandays         = Timesheet::join('users','users.nik','tb_timesheet.nik')->select('point_mandays','users.name','tb_timesheet.nik','task')->selectRaw('MONTH(start_date) AS month_number')->where('tb_timesheet.nik',$nik)->where(function ($query) use ($arrayMonth) {
                                            foreach ($arrayMonth as $month) {
                                                $query->orWhereRaw("MONTH(start_date) = $month");
                                            }
                                        });
                
                $isNeedOtherUser = false;
            }
        }elseif ($cek_role->group == 'DPG') {
            if ($cek_role->name == 'SID Manager' || $cek_role->name == 'SID SPV') {
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','DPG')->pluck('nik');

                $sumMandays         = Timesheet::join('users','users.nik','tb_timesheet.nik')->select(DB::raw('CASE WHEN point_mandays IS NULL THEN 0 ELSE point_mandays END AS point_mandays'),'users.name','tb_timesheet.nik','task')->selectRaw('MONTH(start_date) AS month_number')->where(function ($query) use ($arrayMonth) {
                        foreach ($arrayMonth as $month) {
                            $query->orWhereRaw("MONTH(start_date) = $month");
                        }
                    });

                if ($request->pic[0] === null) {
                    $sumMandays = $sumMandays->whereIn('tb_timesheet.nik',$listGroup);
                    $getLeavingPermit   = $getLeavingPermit->whereIn('tb_cuti.nik',$listGroup)->get();
                    $getPermit          = $getPermit->whereIn('tb_timesheet_permit.nik',$listGroup)->get();

                    $getUserByGroup = $getUserByGroup->whereNotIn('nik', $sumMandays->get()->pluck('nik'))
                                        ->get();
                }else{
                    $sumMandays = $sumMandays->whereIn('tb_timesheet.nik',$request->pic);
                    $getLeavingPermit   = $getLeavingPermit->whereIn('tb_cuti.nik',$request->pic)->get();
                    $getPermit          = $getPermit->whereIn('tb_timesheet_permit.nik',$request->pic)->get();

                    $getUserByGroup = $getUserByGroup
                                        ->whereNotIn('nik', $sumMandays->get()->pluck('nik'))
                                        ->whereIn('nik',$request->pic)    
                                        ->get();
                }

                if (is_null($sumMandays)) {
                    $isNeedOtherUser = true;
                }else{
                    $isNeedOtherUser = false;
                }
            } else {
                $getLeavingPermit   = $getLeavingPermit->where('tb_cuti.nik',$nik)->get();
                $getPermit          = $getPermit->where('tb_timesheet_permit.nik',$nik)->get();
                $sumMandays         = Timesheet::join('users','users.nik','tb_timesheet.nik')->select('point_mandays','users.name','tb_timesheet.nik','task')->selectRaw('MONTH(start_date) AS month_number')->where('tb_timesheet.nik',$nik)->where(function ($query) use ($arrayMonth) {
                                            foreach ($arrayMonth as $month) {
                                                $query->orWhereRaw("MONTH(start_date) = $month");
                                            }
                                        });

                $isNeedOtherUser = false;
            }
        }elseif ($cek_role->group == 'presales') {
            if ($cek_role->name == 'SOL Manager') {
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','presales')->pluck('nik');
                
                $sumMandays         = Timesheet::join('users','users.nik','tb_timesheet.nik')->select(DB::raw('CASE WHEN point_mandays IS NULL THEN 0 ELSE point_mandays END AS point_mandays'),'users.name','tb_timesheet.nik','task')->selectRaw('MONTH(start_date) AS month_number')->where(function ($query) use ($arrayMonth) {
                                            foreach ($arrayMonth as $month) {
                                                $query->orWhereRaw("MONTH(start_date) = $month");
                                            }
                                        });

                if ($request->pic[0] === null) {
                    $sumMandays = $sumMandays->whereIn('tb_timesheet.nik',$listGroup);
                    $getLeavingPermit   = $getLeavingPermit->whereIn('tb_cuti.nik',$listGroup)->get();
                    $getPermit          = $getPermit->whereIn('tb_timesheet_permit.nik',$listGroup)->get();

                    $getUserByGroup = $getUserByGroup->whereNotIn('nik', $sumMandays->get()->pluck('nik'))
                                        ->get();
                }else{
                    $sumMandays = $sumMandays->whereIn('tb_timesheet.nik',$request->pic);
                    $getLeavingPermit   = $getLeavingPermit->whereIn('tb_cuti.nik',$request->pic)->get();
                    $getPermit          = $getPermit->whereIn('tb_timesheet_permit.nik',$request->pic)->get();

                    $getUserByGroup = $getUserByGroup
                                        ->whereNotIn('nik', $sumMandays->get()->pluck('nik'))
                                        ->whereIn('nik',$request->pic)    
                                        ->get();
                }

                if (is_null($sumMandays)) {
                    $isNeedOtherUser = true;
                }else{
                    $isNeedOtherUser = false;
                }
            } else {
                $getLeavingPermit   = $getLeavingPermit->where('tb_cuti.nik',$nik)->get();
                $getPermit          = $getPermit->where('tb_timesheet_permit.nik',$nik)->get();
                $sumMandays         = Timesheet::join('users','users.nik','tb_timesheet.nik')->select('point_mandays','users.name','tb_timesheet.nik','task')->selectRaw('MONTH(start_date) AS month_number')->where('tb_timesheet.nik',$nik)->where(function ($query) use ($arrayMonth) {
                                            foreach ($arrayMonth as $month) {
                                                $query->orWhereRaw("MONTH(start_date) = $month");
                                            }
                                        });

                $isNeedOtherUser = false;
            }
        }elseif ($cek_role->group == 'bcd') {
            $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','bcd')->pluck('nik');

            if ($cek_role->name == 'BCD Manager' || $cek_role->name == 'BCD Development SPV') {
                    $sumMandays = Timesheet::join('users','users.nik','tb_timesheet.nik')->select(DB::raw('CASE WHEN point_mandays IS NULL THEN 0 ELSE point_mandays END AS point_mandays'),'users.name','tb_timesheet.nik','task')
                        ->selectRaw('MONTH(start_date) AS month_number')->where(function ($query) use ($arrayMonth) {
                            foreach ($arrayMonth as $month) {
                                $query->orWhereRaw("MONTH(start_date) = $month");
                            }
                        });

                    if ($request->pic[0] === null) {
                        $sumMandays = $sumMandays->whereIn('tb_timesheet.nik',$listGroup);
                        $getLeavingPermit   = $getLeavingPermit->whereIn('tb_cuti.nik',$listGroup)->get();
                        $getPermit          = $getPermit->whereIn('tb_timesheet_permit.nik',$listGroup)->get();

                        $getUserByGroup = $getUserByGroup->whereNotIn('nik', $sumMandays->get()->pluck('nik'))
                                            ->get();
                    }else{
                        $sumMandays = $sumMandays->whereIn('tb_timesheet.nik',$request->pic);
                        $getLeavingPermit   = $getLeavingPermit->whereIn('tb_cuti.nik',$request->pic)->get();
                        $getPermit          = $getPermit->whereIn('tb_timesheet_permit.nik',$request->pic)->get();

                        $getUserByGroup = $getUserByGroup
                                            ->whereNotIn('nik', $sumMandays->get()->pluck('nik'))
                                            ->whereIn('nik',$request->pic)    
                                            ->get();
                    }

                    if (is_null($sumMandays)) {
                        $isNeedOtherUser = true;
                    }else{
                        $isNeedOtherUser = false;
                    }
            } else {
                $getLeavingPermit   = $getLeavingPermit->where('tb_cuti.nik',$nik)->get();
                $getPermit          = $getPermit->where('tb_timesheet_permit.nik',$nik)->get();
                $sumMandays         = Timesheet::join('users','users.nik','tb_timesheet.nik')->select('point_mandays','users.name','tb_timesheet.nik','task')->selectRaw('MONTH(start_date) AS month_number')->where('tb_timesheet.nik',$nik)->where(function ($query) use ($arrayMonth) {
                        foreach ($arrayMonth as $month) {
                            $query->orWhereRaw("MONTH(start_date) = $month");
                        }
                    });
                
                $isNeedOtherUser = false;
            }
        }elseif ($cek_role->group == 'hr') {
            if ($cek_role->name == 'HR Manager') {
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','hr')->pluck('nik');
                
                $sumMandays         = Timesheet::join('users','users.nik','tb_timesheet.nik')->select(DB::raw('CASE WHEN point_mandays IS NULL THEN 0 ELSE point_mandays END AS point_mandays'),'users.name','tb_timesheet.nik','task')->selectRaw('MONTH(start_date) AS month_number')->where(function ($query) use ($arrayMonth) {
                                            foreach ($arrayMonth as $month) {
                                                $query->orWhereRaw("MONTH(start_date) = $month");
                                            }
                                        });

                if ($request->pic[0] === null) {
                    $sumMandays = $sumMandays->whereIn('tb_timesheet.nik',$listGroup);
                    $getLeavingPermit   = $getLeavingPermit->whereIn('tb_cuti.nik',$listGroup)->get();
                    $getPermit          = $getPermit->whereIn('tb_timesheet_permit.nik',$listGroup)->get();

                    $getUserByGroup = $getUserByGroup->whereNotIn('nik', $sumMandays->get()->pluck('nik'))
                                        ->get();
                }else{
                    $sumMandays = $sumMandays->whereIn('tb_timesheet.nik',$request->pic);
                    $getLeavingPermit   = $getLeavingPermit->whereIn('tb_cuti.nik',$request->pic)->get();
                    $getPermit          = $getPermit->whereIn('tb_timesheet_permit.nik',$request->pic)->get();

                    $getUserByGroup = $getUserByGroup
                                        ->whereNotIn('nik', $sumMandays->get()->pluck('nik'))
                                        ->whereIn('nik',$request->pic)    
                                        ->get();
                }

                if (isset($sumMandays)) {
                    $isNeedOtherUser = true;
                }else{
                    $isNeedOtherUser = false;
                }
            } else {
                $getLeavingPermit   = $getLeavingPermit->where('tb_cuti.nik',$nik)->get();
                $getPermit          = $getPermit->where('tb_timesheet_permit.nik',$nik)->get();
                $sumMandays         = Timesheet::join('users','users.nik','tb_timesheet.nik')->select('point_mandays','users.name','tb_timesheet.nik','task')->selectRaw('MONTH(start_date) AS month_number')->where('tb_timesheet.nik',$nik)->where(function ($query) use ($arrayMonth) {
                                            foreach ($arrayMonth as $month) {
                                                $query->orWhereRaw("MONTH(start_date) = $month");
                                            }
                                        });
                $isNeedOtherUser = false;

            }
        }elseif ($cek_role->group == 'msm') {
            if ($cek_role->name == 'MSM Manager' || $cek_role->name == 'MSM TS SPV') {
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','msm')->pluck('nik');
                
                $sumMandays         = Timesheet::join('users','users.nik','tb_timesheet.nik')->select(DB::raw('CASE WHEN point_mandays IS NULL THEN 0 ELSE point_mandays END AS point_mandays'),'users.name','tb_timesheet.nik','task')->selectRaw('MONTH(start_date) AS month_number')->where(function ($query) use ($arrayMonth) {
                                            foreach ($arrayMonth as $month) {
                                                $query->orWhereRaw("MONTH(start_date) = $month");
                                            }
                                        });

                if ($request->pic[0] === null) {
                    $sumMandays = $sumMandays->whereIn('tb_timesheet.nik',$listGroup);
                    $getLeavingPermit   = $getLeavingPermit->whereIn('tb_cuti.nik',$listGroup)->get();
                    $getPermit          = $getPermit->whereIn('tb_timesheet_permit.nik',$listGroup)->get();

                    $getUserByGroup     = $getUserByGroup
                                        ->where('roles.name','not like','%MSM Helpdesk%')
                                        ->whereNotIn('nik', $sumMandays->get()->pluck('nik'))
                                        ->get();
                }else{
                    $sumMandays = $sumMandays->whereIn('tb_timesheet.nik',$request->pic);
                    $getLeavingPermit   = $getLeavingPermit->whereIn('tb_cuti.nik',$request->pic)->get();
                    $getPermit          = $getPermit->whereIn('tb_timesheet_permit.nik',$request->pic)->get();

                    $getUserByGroup = $getUserByGroup
                                        ->whereNotIn('nik', $sumMandays->get()->pluck('nik'))
                                        ->whereIn('nik',$request->pic)    
                                        ->get();
                }

                if (is_null($sumMandays)) {
                    $isNeedOtherUser = true;
                }else{
                    $isNeedOtherUser = false;
                }
            } else {
                $getLeavingPermit   = $getLeavingPermit->where('tb_cuti.nik',$nik)->get();
                $getPermit          = $getPermit->where('tb_timesheet_permit.nik',$nik)->get();
                $sumMandays         = Timesheet::join('users','users.nik','tb_timesheet.nik')->select('point_mandays','users.name','tb_timesheet.nik','task')->selectRaw('MONTH(start_date) AS month_number')->where('tb_timesheet.nik',$nik)->where(function ($query) use ($arrayMonth) {
                                            foreach ($arrayMonth as $month) {
                                                $query->orWhereRaw("MONTH(start_date) = $month");
                                            }
                                        });
                $isNeedOtherUser = false;

            }
        }

        if ($request->task[0] === null) {
            $sumMandays = $sumMandays;
        }else{
            $sumMandays = $sumMandays->whereIn('task',$request->task);                    
        }

        if ($request->phase[0] === null) {
            $sumMandays = $sumMandays;
        }else{
            $sumMandays = $sumMandays->whereIn('phase',$request->phase);                    
        }

        if ($request->status[0] === null) {
            $sumMandays = $sumMandays->where('status','Done');
        }else{
            $sumMandays = $sumMandays->whereIn('status',$request->status);                    
        }

        if (is_null($request->year)) {
            $sumMandays = $sumMandays->whereYear('start_date',date('Y'));
        }else{
            $sumMandays = $sumMandays->whereYear('start_date',$request->year);                    
        }

        if ($request->schedule[0] === null) {
            $sumMandays = $sumMandays;
        }else{
            $sumMandays = $sumMandays->whereIn('schedule',$request->schedule);                    
        }

        $sumMandays = $sumMandays->get()->makeHidden(['planned','threshold']);

        $getLeavingPermitByName = collect($getLeavingPermit)->groupBy('name');
        $getPermitByName        = collect($getPermit)->groupBy('name');
        $getTaskAvailableByName = collect($sumMandays->where('task',36)->groupBy('name'));

        if (count($sumMandays) === 0) {
            $startDate       = Carbon::now()->startOfYear()->format("Y-m-d");
            $endDate         = Carbon::now()->endOfYear()->format("Y-m-d");
            $workdays        = $this->getWorkDays($startDate,$endDate,"workdays");
            $workdays  = count($workdays["workdays"]);

            if ($isNeedOtherUser == false) {
                foreach($getUserByGroup as $value_group){
                    $arrSumPoint->push(["name"=>$value_group->name,
                        "nik"       =>$value_group->nik,
                        "actual"    =>"-",
                        "planned"   =>$workdays,
                        "threshold" =>"-",
                        "billable"  =>"-",
                        "percentage_billable" =>"-",
                        "deviation" =>"-",
                        "total_task"=>"-"
                    ]);
                }
            }
        }else{
            $startDate       = Carbon::now()->startOfYear()->format("Y-m-d");
            $endDate         = Carbon::now()->endOfYear()->format("Y-m-d");
            $workdays        = $this->getWorkDays($startDate,$endDate,"workdays");
            $workdays        = count($workdays["workdays"]);

            $countPointByUser = $sumMandays->groupBy('name')->map(function ($group) {
                return round($group->count('point_mandays'),2);
            });

            $sumPointByUser = $sumMandays->groupBy('name')->map(function ($group) {
                return round($group->sum('point_mandays'),2);
            });

            $getPermitByName = $getPermitByName->map(function ($group) {
                return $group->count('start_date');
            });

            $getLeavingPermitByName = $getLeavingPermitByName->map(function ($group){
                return $group->count('date');
            });

            $getTaskAvailableByName = $getTaskAvailableByName->map(function ($group){
                return round($group->sum('point_mandays'),2);
            });

            $sumArrayPermitByName = array();
            // Merge the arrays and sum the values
            $mergedKeys = array_merge(array_keys(json_decode(json_encode($getPermitByName), true)), array_keys(json_decode(json_encode($getTaskAvailableByName), true)));
            $mergedKeys = array_unique($mergedKeys); // Remove duplicates

            foreach ($mergedKeys as $key) {
                $sumArrayPermitByName[$key] = (isset($getPermitByName[$key]) ? $getPermitByName[$key] : 0) + (isset($getTaskAvailableByName[$key]) ? $getTaskAvailableByName[$key] : 0);
            }

            $sumPointMandays = collect();
            foreach($sumPointByUser as $key_point => $valueSumPoint){
                $billable = isset($sumArrayPermitByName[$key_point])?$sumArrayPermitByName[$key_point]:0;
                $sumPointMandays->push([
                    "name"=>$key_point,
                    "nik"=>collect($sumMandays)->where('name',$key_point)->first()->nik,
                    "actual"=>$valueSumPoint,
                    "planned"=>$countPlanned,
                    "threshold"=>$countThresholdFinal,
                    "billable"=>number_format($valueSumPoint - $billable,2,'.',''),
                    "percentage_billable"=>number_format(($valueSumPoint - $billable)/collect($sumMandays)->first()->planned*100,  2, '.', ''),
                    "deviation"=>collect($sumMandays)->first()->planned - $valueSumPoint
                ]); 
            }  
            
            $collection = collect($sumPointMandays);        
            $uniqueCollection = $collection->groupBy('name')->map->first();

            foreach($uniqueCollection->all() as $key_uniq => $data_uniq){
                if ($data_uniq['name'] == $key_uniq) {
                    $arrSumPoint->push([
                        "name"      =>$data_uniq['name'],
                        "nik"       =>$data_uniq['nik'],
                        "actual"    =>$data_uniq['actual'],
                        "planned"   =>$data_uniq['planned'],
                        "threshold" =>$data_uniq['threshold'],
                        "billable"  =>$data_uniq['billable'],
                        "percentage_billable" =>$data_uniq['percentage_billable'] . "%",
                        "deviation" =>$data_uniq['deviation'],
                        "total_task"=>$countPointByUser[$key_uniq]
                    ]);
                }
            }

            if ($isNeedOtherUser == false) {
                foreach($getUserByGroup as $value_group){
                    $arrSumPoint->push(["name"=>$value_group->name,
                        "nik"       =>$value_group->nik,
                        "actual"    =>"-",
                        "planned"   =>$countPlanned,
                        "threshold" =>$countThresholdFinal,
                        "billable"  =>"-",
                        "percentage_billable" =>"-",
                        "deviation" =>"-",
                        "total_task"=>"-"
                    ]);
                }
            }
        }

        return array("data"=>$arrSumPoint);
    }

    public function getFilterCummulativeMandaysChart(Request $request)
    {
        $nik = Auth::User()->nik;
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('name', 'roles.group')->where('user_id', $nik)->first(); 

        $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $bulan_angka = [1,2,3,4,5,6,7,8,9,10,11,12];

        $data = DB::table('tb_timesheet')
                ->join('users','tb_timesheet.nik','users.nik')->select(DB::raw('CASE WHEN point_mandays IS NULL THEN 0 ELSE point_mandays END AS point_mandays'),'name','end_date','status','users.nik')->selectRaw('MONTH(start_date) AS month_number');

        $startDate = Carbon::now()->startOfMonth()->format("Y-m-d");
        $endDate = Carbon::now()->endOfMonth()->format("Y-m-d");
        $workdays = $this->getWorkDays($startDate,$endDate,"workdays");
        $workdays = count($workdays['workdays']);
        $arrCummulativeMandays = collect();

        $getUserByGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')
                        ->join('roles', 'roles.id', '=', 'role_user.role_id')
                        ->select('users.name')
                        ->where('roles.group',$cek_role->group)
                        ->where('roles.name','not like','%Manager')
                        ->where('users.status_delete','-');

        if ($request->task[0] === null) {
            $data = $data;
        }else{
            $data = $data->whereIn('task',$request->task);                    
        }

        if ($request->phase[0] === null) {
            $data = $data;
        }else{
            $data = $data->whereIn('phase',$request->phase);                    
        }

        if ($request->status[0] === null) {
            $data = $data->where('status','Done');
        }else{
            $data = $data->whereIn('status',$request->status);                    
        }

        if (is_null($request->year)) {
            $data = $data->whereYear('start_date',date('Y'));
        }else{
            $data = $data->whereYear('start_date',$request->year);                    
        }

        if ($request->schedule[0] === null) {
            $data = $data;
        }else{
            $data = $data->whereIn('schedule',$request->schedule);                    
        }

        if ($cek_role->group == 'pmo') {
            if ($cek_role->name == 'PMO Manager' || $cek_role->name == 'PMO SPV') {
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','pmo')->pluck('nik');
                
                if ($request->pic[0] === null) {
                    $data = $data->whereIn('tb_timesheet.nik',$listGroup);
                    $getUserByGroup = $getUserByGroup->whereNotIn('nik', $data->get()->pluck('nik'))
                                        ->get();
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                    $getUserByGroup = $getUserByGroup
                                ->whereNotIn('nik', $data->get()->pluck('nik'))
                                ->whereIn('nik',$request->pic)    
                                ->get();
                }

                $data = $data->get()->groupBy('name');

                if (count($data) == 0) {
                    foreach(User::select('name')->whereIn('nik',$request->pic)->get() as $name_pic){
                        $arrayName = array($name_pic->name => [0,0,0,0,0,0,0,0,0,0,0,0]);
                        $arrCummulativeMandays->push(['name'=>$name_pic->name,'month_array'=>$arrayName]);
                    }
                }else{
                    foreach ($data as $key_value => $value) {
                        $arrayName = array($key_value => [0,0,0,0,0,0,0,0,0,0,0,0]);

                        foreach($data[$key_value] as $data_value){
                            if ($key_value === $data_value->name) {
                                foreach($arrayName as $key_month_name => $month_value){
                                    if ($key_month_name == $data_value->name) {
                                        foreach ($bulan_angka as $key_month => $value2) {
                                            if ($data_value->month_number == $value2) {
                                                $arrayName[$key_month_name][$key_month] = $month_value[$key_month]+$data_value->point_mandays;
                                            }
                                        }
                                    }
                                }
                            }
                        }

                       $arrCummulativeMandays->push(['name'=>$key_value,'month_array'=>$arrayName]);
                    }

                    if(isset($getUserByGroup)){
                        foreach($getUserByGroup as $key => $value_group){
                            $arrayName = array($value_group->name => [0,0,0,0,0,0,0,0,0,0,0,0]);

                            $arrCummulativeMandays->push(['name'=>$value_group->name,'month_array'=>$arrayName]);
                        }
                    }
                    
                }
            } else {
                $data = $data->where('tb_timesheet.nik',$nik)->where('status','Done')->get();

                $arrayName = array(Auth::User()->name => [0,0,0,0,0,0,0,0,0,0,0,0]);
                if (count($data) == 0) {
                    $arrCummulativeMandays->push(['name'=>Auth::User()->name,'month_array'=>$arrayName]);
                }else{
                    foreach ($bulan_angka as $key => $value2) {
                       foreach ($data as $value) {
                           if ($value->month_number == $value2) {
                                $arrayName[$key] = $arrayName[$key]+$value->point_mandays;
                            }
                        }
                    }

                    $arrCummulativeMandays->push(['name'=>$value->name,'month_array'=>$arrayName]);
                }
            }            
        }elseif ($cek_role->group == 'DPG') {
            if ($cek_role->name == 'SID Manager' || $cek_role->name == 'SID SPV') {
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','DPG')->pluck('nik');

                if ($request->pic[0] === null) {
                    $data = $data->whereIn('tb_timesheet.nik',$listGroup);
                    $getUserByGroup = $getUserByGroup->whereNotIn('nik', $data->get()->pluck('nik'))
                                        ->get();
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                    $getUserByGroup = $getUserByGroup
                                ->whereNotIn('nik', $data->get()->pluck('nik'))
                                ->whereIn('nik',$request->pic)    
                                ->get();
                }

                // if ($request->task[0] === null) {
                //     $data = $data;
                // }else{
                //     $data = $data->whereIn('task',$request->task);                    
                // }

                // if ($request->status[0] === null) {
                //     $data = $data->where('status','Done');
                // }else{
                //     $data = $data->whereIn('status',$request->status);                    
                // }

                // if (is_null($request->year)) {
                //     $data = $data->whereYear('start_date',date('Y'));
                // }else{
                //     $data = $data->whereYear('start_date',$request->year);                    
                // }

                // if ($request->schedule[0] === null) {
                //     $data = $data;
                // }else{
                //     $data = $data->whereIn('schedule',$request->schedule);                    
                // }

                $data = $data->get()->groupBy('name');

                if (count($data) == 0) {
                    foreach(User::select('name')->whereIn('nik',$request->pic)->get() as $name_pic){
                        $arrayName = array($name_pic->name => [0,0,0,0,0,0,0,0,0,0,0,0]);
                        $arrCummulativeMandays->push(['name'=>$name_pic->name,'month_array'=>$arrayName]);
                    }
                }else{
                    foreach ($data as $key_value => $value) {
                        $arrayName = array($key_value => [0,0,0,0,0,0,0,0,0,0,0,0]);

                        foreach($data[$key_value] as $data_value){
                            if ($key_value === $data_value->name) {
                                foreach($arrayName as $key_month_name => $month_value){
                                    if ($key_month_name == $data_value->name) {
                                        foreach ($bulan_angka as $key_month => $value2) {
                                            if ($data_value->month_number == $value2) {
                                                $arrayName[$key_month_name][$key_month] = $month_value[$key_month]+$data_value->point_mandays;
                                            }
                                        }
                                    }
                                }
                            }
                        }

                       $arrCummulativeMandays->push(['name'=>$key_value,'month_array'=>$arrayName]);
                    }

                    if(isset($getUserByGroup)){
                        foreach($getUserByGroup as $key => $value_group){
                            $arrayName = array($value_group->name => [0,0,0,0,0,0,0,0,0,0,0,0]);

                            $arrCummulativeMandays->push(['name'=>$value_group->name,'month_array'=>$arrayName]);
                        }
                    }
                    
                }
            } else {
                $data = $data->where('tb_timesheet.nik',$nik)->where('status','Done')->get();

                $arrayName = array(Auth::User()->name => [0,0,0,0,0,0,0,0,0,0,0,0]);
                if (count($data) == 0) {
                    $arrCummulativeMandays->push(['name'=>Auth::User()->name,'month_array'=>$arrayName]);
                }else{
                    foreach ($bulan_angka as $key => $value2) {
                       foreach ($data as $value) {
                           if ($value->month_number == $value2) {
                                $arrayName[$key] = $arrayName[$key]+$value->point_mandays;
                            }
                        }
                    }

                    $arrCummulativeMandays->push(['name'=>$value->name,'month_array'=>$arrayName]);
                }
            }            
        }elseif ($cek_role->group == 'presales') {
            if ($cek_role->name == 'SOL Manager') {
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','presales')->pluck('nik');
                
                if ($request->pic[0] === null) {
                    $data = $data->whereIn('tb_timesheet.nik',$listGroup);
                    $getUserByGroup = $getUserByGroup->whereNotIn('nik', $data->get()->pluck('nik'))
                                        ->get();
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                    $getUserByGroup = $getUserByGroup
                                ->whereNotIn('nik', $data->get()->pluck('nik'))
                                ->whereIn('nik',$request->pic)    
                                ->get();
                } 

                // if ($request->task[0] === null) {
                //     $data = $data;
                // }else{
                //     $data = $data->whereIn('task',$request->task);                    
                // }

                // if ($request->status[0] === null) {
                //     $data = $data->where('status','Done');
                // }else{
                //     $data = $data->whereIn('status',$request->status);                    
                // }

                // if (is_null($request->year)) {
                //     $data = $data->whereYear('start_date',date('Y'));
                // }else{
                //     $data = $data->whereYear('start_date',$request->year);                    
                // }

                // if ($request->schedule[0] === null) {
                //     $data = $data;
                // }else{
                //     $data = $data->whereIn('schedule',$request->schedule);                    
                // }

                $data = $data->get()->groupBy('name');

                if (count($data) == 0) {
                    foreach(User::select('name')->whereIn('nik',$request->pic)->get() as $name_pic){
                        $arrayName = array($name_pic->name => [0,0,0,0,0,0,0,0,0,0,0,0]);
                        $arrCummulativeMandays->push(['name'=>$name_pic->name,'month_array'=>$arrayName]);
                    }
                }else{
                    foreach ($data as $key_value => $value) {
                        $arrayName = array($key_value => [0,0,0,0,0,0,0,0,0,0,0,0]);

                        foreach($data[$key_value] as $data_value){
                            if ($key_value === $data_value->name) {
                                foreach($arrayName as $key_month_name => $month_value){
                                    if ($key_month_name == $data_value->name) {
                                        foreach ($bulan_angka as $key_month => $value2) {
                                            if ($data_value->month_number == $value2) {
                                                $arrayName[$key_month_name][$key_month] = $month_value[$key_month]+$data_value->point_mandays;
                                            }
                                        }
                                    }
                                }
                            }
                        }

                       $arrCummulativeMandays->push(['name'=>$key_value,'month_array'=>$arrayName]);
                    }

                    if(isset($getUserByGroup)){
                        foreach($getUserByGroup as $key => $value_group){
                            $arrayName = array($value_group->name => [0,0,0,0,0,0,0,0,0,0,0,0]);

                            $arrCummulativeMandays->push(['name'=>$value_group->name,'month_array'=>$arrayName]);
                        }
                    }
                    
                }
            } else {
                $data = $data->where('tb_timesheet.nik',$nik)->where('status','Done')->get();

                $arrayName = array(Auth::User()->name => [0,0,0,0,0,0,0,0,0,0,0,0]);
                if (count($data) == 0) {
                    $arrCummulativeMandays->push(['name'=>Auth::User()->name,'month_array'=>$arrayName]);
                }else{
                    foreach ($bulan_angka as $key => $value2) {
                       foreach ($data as $value) {
                           if ($value->month_number == $value2) {
                                $arrayName[$key] = $arrayName[$key]+$value->point_mandays;
                            }
                        }
                    }

                    $arrCummulativeMandays->push(['name'=>$value->name,'month_array'=>$arrayName]);
                }
            }
        }elseif ($cek_role->group == 'bcd') {
            if ($cek_role->name == 'BCD Manager' || $cek_role->name == 'BCD Development SPV') {
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','bcd')->pluck('nik');
                
                if ($request->pic[0] === null) {
                    $data = $data->whereIn('tb_timesheet.nik',$listGroup);
                    $getUserByGroup = $getUserByGroup->whereNotIn('nik', $data->get()->pluck('nik'))
                                        ->get();
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                    $getUserByGroup = $getUserByGroup
                                ->whereNotIn('nik', $data->get()->pluck('nik'))
                                ->whereIn('nik',$request->pic)    
                                ->get();
                } 

                // if ($request->task[0] === null) {
                //     $data = $data;
                // }else{
                //     $data = $data->whereIn('task',$request->task);                    
                // }

                // if ($request->status[0] === null) {
                //     $data = $data->where('status','Done');
                // }else{
                //     $data = $data->whereIn('status',$request->status);                    
                // }

                // if (is_null($request->year)) {
                //     $data = $data->whereYear('start_date',date('Y'));
                // }else{
                //     $data = $data->whereYear('start_date',$request->year);                    
                // }

                // if ($request->schedule[0] === null) {
                //     $data = $data;
                // }else{
                //     $data = $data->whereIn('schedule',$request->schedule );                    
                // }

                $data = $data->get()->groupBy('name');

                if (count($data) == 0) {
                    foreach(User::select('name')->whereIn('nik',$request->pic)->get() as $name_pic){
                        $arrayName = array($name_pic->name => [0,0,0,0,0,0,0,0,0,0,0,0]);
                        $arrCummulativeMandays->push(['name'=>$name_pic->name,'month_array'=>$arrayName]);
                    }
                }else{
                    foreach ($data as $key_value => $value) {
                        $arrayName = array($key_value => [0,0,0,0,0,0,0,0,0,0,0,0]);

                        foreach($data[$key_value] as $data_value){
                            if ($key_value === $data_value->name) {
                                foreach($arrayName as $key_month_name => $month_value){
                                    if ($key_month_name == $data_value->name) {
                                        foreach ($bulan_angka as $key_month => $value2) {
                                            if ($data_value->month_number == $value2) {
                                                $arrayName[$key_month_name][$key_month] = $month_value[$key_month]+$data_value->point_mandays;
                                            }
                                        }
                                    }
                                }
                            }
                        }

                       $arrCummulativeMandays->push(['name'=>$key_value,'month_array'=>$arrayName]);
                    }

                    if(isset($getUserByGroup)){
                        foreach($getUserByGroup as $key => $value_group){
                            $arrayName = array($value_group->name => [0,0,0,0,0,0,0,0,0,0,0,0]);

                            $arrCummulativeMandays->push(['name'=>$value_group->name,'month_array'=>$arrayName]);
                        }
                    }
                    
                }                
            } else {
                $data = $data->where('tb_timesheet.nik',$nik)->where('status','Done')->get();

                $arrayName = array(Auth::User()->name => [0,0,0,0,0,0,0,0,0,0,0,0]);
                if (count($data) == 0) {
                    $arrCummulativeMandays->push(['name'=>Auth::User()->name,'month_array'=>$arrayName]);
                }else{
                    foreach ($bulan_angka as $key => $value2) {
                       foreach ($data as $value) {
                           if ($value->month_number == $value2) {
                                $arrayName[$value->name][$key] = $arrayName[$value->name][$key]+$value->point_mandays;
                            }
                        }
                    }

                    $arrCummulativeMandays->push(['name'=>$value->name,'month_array'=>$arrayName]);
                }
            }
        }elseif ($cek_role->group == 'hr') {
            if ($cek_role->name == 'HR Manager') {
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','hr')->pluck('nik');

                if ($request->pic[0] === null) {
                    $data = $data->whereIn('tb_timesheet.nik',$listGroup);
                    $getUserByGroup = $getUserByGroup->whereNotIn('nik', $data->get()->pluck('nik'))
                                        ->get();
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                    $getUserByGroup = $getUserByGroup
                                ->whereNotIn('nik', $data->get()->pluck('nik'))
                                ->whereIn('nik',$request->pic)    
                                ->get();
                } 

                // if ($request->task[0] === null) {
                //     $data = $data;
                // }else{
                //     $data = $data->whereIn('task',$request->task);                    
                // }

                // if ($request->status[0] === null) {
                //     $data = $data->where('status','Done');
                // }else{
                //     $data = $data->whereIn('status',$request->status);                    
                // }

                // if (is_null($request->year)) {
                //     $data = $data->whereYear('start_date',date('Y'));
                // }else{
                //     $data = $data->whereYear('start_date',$request->year);                    
                // }

                // if ($request->schedule[0] === null) {
                //     $data = $data;
                // }else{
                //     $data = $data->whereIn('schedule',$request->schedule);                    
                // }

                $data = $data->get()->groupBy('name');

                if (count($data) == 0) {
                    foreach(User::select('name')->whereIn('nik',$request->pic)->get() as $name_pic){
                        $arrayName = array($name_pic->name => [0,0,0,0,0,0,0,0,0,0,0,0]);
                        $arrCummulativeMandays->push(['name'=>$name_pic->name,'month_array'=>$arrayName]);
                    }
                }else{
                    foreach ($data as $key_value => $value) {
                        $arrayName = array($key_value => [0,0,0,0,0,0,0,0,0,0,0,0]);

                        foreach($data[$key_value] as $data_value){
                            if ($key_value === $data_value->name) {
                                foreach($arrayName as $key_month_name => $month_value){
                                    if ($key_month_name == $data_value->name) {
                                        foreach ($bulan_angka as $key_month => $value2) {
                                            if ($data_value->month_number == $value2) {
                                                $arrayName[$key_month_name][$key_month] = $month_value[$key_month]+$data_value->point_mandays;
                                            }
                                        }
                                    }
                                }
                            }
                        }

                       $arrCummulativeMandays->push(['name'=>$key_value,'month_array'=>$arrayName]);
                    }

                    if(isset($getUserByGroup)){
                        foreach($getUserByGroup as $key => $value_group){
                            $arrayName = array($value_group->name => [0,0,0,0,0,0,0,0,0,0,0,0]);

                            $arrCummulativeMandays->push(['name'=>$value_group->name,'month_array'=>$arrayName]);
                        }
                    }
                    
                }
            } else {
                $data = $data->where('tb_timesheet.nik',$nik)->where('status','Done')->get();

                $arrayName = array(Auth::User()->name => [0,0,0,0,0,0,0,0,0,0,0,0]);
                if (count($data) == 0) {
                    $arrCummulativeMandays->push(['name'=>Auth::User()->name,'month_array'=>$arrayName]);
                }else{
                    foreach ($bulan_angka as $key => $value2) {
                       foreach ($data as $value) {
                           if ($value->month_number == $value2) {
                                $arrayName[$key] = $arrayName[$key]+$value->point_mandays;
                            }
                        }
                    }

                    $arrCummulativeMandays->push(['name'=>$value->name,'month_array'=>$arrayName]);
                }
            }
        }elseif ($cek_role->group == 'msm') {
            if ($cek_role->name == 'MSM Manager' || $cek_role->name == 'MSM TS SPV') {
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','msm')->pluck('nik');

                if ($request->pic[0] === null) {
                    $data = $data->whereIn('tb_timesheet.nik',$listGroup);
                    $getUserByGroup = $getUserByGroup->whereNotIn('nik', $data->get()->pluck('nik'))
                                        ->get();
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                    $getUserByGroup = $getUserByGroup
                                ->whereNotIn('nik', $data->get()->pluck('nik'))
                                ->whereIn('nik',$request->pic)    
                                ->get();
                } 

                // if ($request->task[0] === null) {
                //     $data = $data;
                // }else{
                //     $data = $data->whereIn('task',$request->task);                    
                // }

                // if ($request->status[0] === null) {
                //     $data = $data->where('status','Done');
                // }else{
                //     $data = $data->whereIn('status',$request->status);                    
                // }

                // if (is_null($request->year)) {
                //     $data = $data->whereYear('start_date',date('Y'));
                // }else{
                //     $data = $data->whereYear('start_date',$request->year);                    
                // }

                // if ($request->schedule[0] === null) {
                //     $data = $data;
                // }else{
                //     $data = $data->whereIn('schedule',$request->schedule);                    
                // }

                $data = $data->get()->groupBy('name');

                if (count($data) == 0) {
                    foreach(User::select('name')->whereIn('nik',$request->pic)->get() as $name_pic){
                        $arrayName = array($name_pic->name => [0,0,0,0,0,0,0,0,0,0,0,0]);
                        $arrCummulativeMandays->push(['name'=>$name_pic->name,'month_array'=>$arrayName]);
                    }
                }else{
                    foreach ($data as $key_value => $value) {
                        $arrayName = array($key_value => [0,0,0,0,0,0,0,0,0,0,0,0]);

                        foreach($data[$key_value] as $data_value){
                            if ($key_value === $data_value->name) {
                                foreach($arrayName as $key_month_name => $month_value){
                                    if ($key_month_name == $data_value->name) {
                                        foreach ($bulan_angka as $key_month => $value2) {
                                            if ($data_value->month_number == $value2) {
                                                $arrayName[$key_month_name][$key_month] = $month_value[$key_month]+$data_value->point_mandays;
                                            }
                                        }
                                    }
                                }
                            }
                        }

                       $arrCummulativeMandays->push(['name'=>$key_value,'month_array'=>$arrayName]);
                    }

                    if(isset($getUserByGroup)){
                        foreach($getUserByGroup as $key => $value_group){
                            $arrayName = array($value_group->name => [0,0,0,0,0,0,0,0,0,0,0,0]);

                            $arrCummulativeMandays->push(['name'=>$value_group->name,'month_array'=>$arrayName]);
                        }
                    }
                    
                }
            } else {
                $data = $data->where('tb_timesheet.nik',$nik)->where('status','Done')->get();

                $arrayName = array(Auth::User()->name => [0,0,0,0,0,0,0,0,0,0,0,0]);
                if (count($data) == 0) {
                    $arrCummulativeMandays->push(['name'=>Auth::User()->name,'month_array'=>$arrayName]);
                }else{
                    foreach ($bulan_angka as $key => $value2) {
                       foreach ($data as $value) {
                           if ($value->month_number == $value2) {
                                $arrayName[$key] = $arrayName[$key]+$value->point_mandays;
                            }
                        }
                    }

                    $arrCummulativeMandays->push(['name'=>$value->name,'month_array'=>$arrayName]);
                }
            }
        }else{
            $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group',$request->roles)->pluck('nik');

            $getUserByGroupOD = User::join('role_user', 'role_user.user_id', '=', 'users.nik')
                        ->join('roles', 'roles.id', '=', 'role_user.role_id')
                        ->select('users.name')
                        ->where('roles.group',$request->roles)
                        ->where('roles.name','not like','%Manager')
                        ->where('users.status_delete','-');

            if ($request->pic[0] === null) {
                $data = $data->whereIn('tb_timesheet.nik',$listGroup);
                $getUserByGroupOD = $getUserByGroupOD->whereNotIn('nik', $data->get()->pluck('nik'))
                                    ->get();
            }else{
                $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                $getUserByGroupOD = $getUserByGroupOD
                            ->whereNotIn('nik', $data->get()->pluck('nik'))
                            ->whereIn('nik',$request->pic)    
                            ->get();
            } 

            // if ($request->task[0] === null) {
            //     $data = $data;
            // }else{
            //     $data = $data->whereIn('task',$request->task);                    
            // }

            // if ($request->status[0] === null) {
            //     $data = $data->where('status','Done');
            // }else{
            //     $data = $data->whereIn('status',$request->status);                    
            // }

            // if (is_null($request->year)) {
            //     $data = $data->whereYear('start_date',date('Y'));
            // }else{
            //     $data = $data->whereYear('start_date',$request->year);                    
            // }

            // if ($request->schedule[0] === null) {
            //     $data = $data;
            // }else{
            //     $data = $data->whereIn('schedule',$request->schedule);                    
            // }

            $data = $data->get()->groupBy('name');

            if (count($data) == 0) {
                foreach(User::select('name')->whereIn('nik',$request->pic)->get() as $name_pic){
                    $arrayName = array($name_pic->name => [0,0,0,0,0,0,0,0,0,0,0,0]);
                    $arrCummulativeMandays->push(['name'=>$name_pic->name,'month_array'=>$arrayName]);
                }
            }else{
                foreach ($data as $key_value => $value) {
                    $arrayName = array($key_value => [0,0,0,0,0,0,0,0,0,0,0,0]);

                    foreach($data[$key_value] as $data_value){
                        if ($key_value === $data_value->name) {
                            foreach($arrayName as $key_month_name => $month_value){
                                if ($key_month_name == $data_value->name) {
                                    foreach ($bulan_angka as $key_month => $value2) {
                                        if ($data_value->month_number == $value2) {
                                            $arrayName[$key_month_name][$key_month] = $month_value[$key_month]+$data_value->point_mandays;
                                        }
                                    }
                                }
                            }
                        }
                    }

                   $arrCummulativeMandays->push(['name'=>$key_value,'month_array'=>$arrayName]);
                }

                if(isset($getUserByGroupOD)){
                    foreach($getUserByGroupOD as $key => $value_group){
                        $arrayName = array($value_group->name => [0,0,0,0,0,0,0,0,0,0,0,0]);

                        $arrCummulativeMandays->push(['name'=>$value_group->name,'month_array'=>$arrayName]);
                    }
                }
                
            }
        }

        $arrCummulativeMandays->push(["workdays"=>$workdays]);
        return $arrCummulativeMandays;
    }

    public function getFilterRemainingChart(Request $request)
    {
        $nik = Auth::User()->nik;
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('name', 'roles.group')->where('user_id', $nik)->first(); 

        $data = DB::table('tb_timesheet')
                ->join('users','tb_timesheet.nik','users.nik')->select('name','point_mandays','end_date','status','users.nik')->selectRaw('MONTH(start_date) AS month_number');

        if (is_null($request->year)) {
            $data = $data->whereYear('start_date',date('Y'));
        }else{
            $data = $data->whereYear('start_date',$request->year);                    
        }

        $getUserByGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')
                    ->join('roles', 'roles.id', '=', 'role_user.role_id')
                    ->select('users.name')
                    ->where('roles.group',$cek_role->group)
                    ->where('roles.name','not like','%Manager')
                    ->where('users.status_delete','-')
                    ->get();

        if ($cek_role->group == 'pmo') {
            if ($cek_role->name == 'PMO Manager' || $cek_role->name == 'PMO SPV') {
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','pmo')->pluck('nik');
                
                $data = $data->whereIn('tb_timesheet.nik',$listGroup)->where('status','Done')->get();

                $getUserByGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')
                        ->join('roles', 'roles.id', '=', 'role_user.role_id')
                        ->select('users.name')
                        ->where('roles.group',$cek_role->group)
                        ->where('roles.name','not like','%Manager')
                        ->where('users.status_delete','-')
                        ->get();

                $data = $data->groupBy('month_number');

                $arrMonth = [1,2,3,4,5,6,7,8,9,10,11,12];

                $arrMonthMandays = [0,0,0,0,0,0,0,0,0,0,0,0];

                foreach($arrMonth as $key_months => $valueMonth){
                    $startDate = Carbon::now();
                    $startDate->month($valueMonth);

                    $endDate = Carbon::now();
                    $endDate->month($valueMonth);

                    $startDateFinal = $startDate->startOfMonth()->format("Y-m-d");
                    $endDateFinal = $endDate->endOfMonth()->format("Y-m-d");

                    foreach($arrMonthMandays as $key_mandays => $valueMandays){
                        if ($key_months == $key_mandays) {
                            $arrMonthMandays[$key_mandays]  = $arrMonthMandays[$key_mandays]+count($this->getWorkDays($startDateFinal,$endDateFinal)["workdays"]->values());
                        }
                    }
                }

                foreach($data as $key => $value){
                    $hasil_prosentase  = array($key => 0);
                    $hasil_remaining   = array($key => 0);

                    $arrName = collect();
                    $arrProsentaseByUser = collect();
                    $arrRemainingByUser = collect();
                    $arrFinalProsentaseByUser = collect();
                    $arrFinalRemainingByUser = collect();

                    foreach($value as $datas){
                        $arrName->push($datas->name);
                    }

                    foreach($getUserByGroup as $value_group){
                        $arrName->push($value_group->name);
                    }

                    foreach($arrName->unique() as $key_name => $value_name){
                        $arrProsentaseByUser->put($value_name,0); 
                        $arrRemainingByUser->put($value_name,0);
                    }

                    foreach($value as $datas){
                        foreach($hasil_prosentase as $key_prosentase => $value_prosentase){
                            foreach($arrProsentaseByUser as $key_byUser => $value_byUser){
                                    if ($key_byUser == $datas->name) {
                                    $arrProsentaseByUser[$key_byUser] = $value_byUser+$datas->point_mandays;
                                }
                            }
                            $hasil_prosentase[$key_prosentase] = $arrFinalProsentaseByUser;
                        }
                    }

                    foreach($arrProsentaseByUser as $key_byUsers => $value_byUsers){
                        $arrFinalProsentaseByUser->push(round((float)$value_byUsers / $arrMonthMandays[$key] * 100,2));
                        $arrFinalRemainingByUser->push(100 - (round(($value_byUsers / $arrMonthMandays[$key]) * 100,2)));

                    }

                    foreach($value as $datas){
                        foreach($hasil_prosentase as $key_prosentase => $value_prosentase){
                            $hasil_prosentase[$key_prosentase] = $arrFinalProsentaseByUser;
                        }

                        foreach($hasil_remaining as $key_remaining => $value_remaining){
                            $hasil_remaining[$key_remaining] = $arrFinalRemainingByUser;
                        }
                    }

                    foreach($arrMonth as $keys => $month){
                        if ($month == $key) {
                            $arrMonth[$keys] = array($key=>collect([
                                "arrName"=>array($arrName->unique()),
                                "label"=>array(
                                    "Prosentase"=>$hasil_prosentase[$key_prosentase],
                                    "Remaining"=>$hasil_remaining[$key_remaining]
                                )
                            ])); 
                        }
                    }
                }
            } else {
                $startDate = Carbon::now()->startOfMonth()->format("Y-m-d");
                $endDate = Carbon::now()->endOfMonth()->format("Y-m-d");

                $data = $data->where('tb_timesheet.nik',$nik)->where('status','Done')->get()->groupBy('month_number');

                $EffectiveMandaysMonthly = count($this->getWorkDays($startDate,$endDate)["workdays"]->values());
                $arrMonth = [1,2,3,4,5,6,7,8,9,10,11,12];

                foreach($data as $key => $value){
                    $hasil_prosentase  = array($key => 0);
                    $hasil_remaining   = array($key => 0);

                    foreach($value as $datas){
                        $arrName = collect([$datas->name]);
                        foreach($hasil_prosentase as $key_prosentase => $value_prosentase){
                            $hasil_prosentase[$key_prosentase] = $value_prosentase+$datas->point_mandays;
                        }

                        foreach($hasil_remaining as $key_remaining => $value_remaining){
                            $hasil_remaining[$key_remaining] = $value_remaining+$datas->point_mandays;
                        }
                    }

                    foreach($arrMonth as $keys => $month){
                        if ($month == $key) {
                            $arrMonth[$keys] = array($key=>collect([
                                "arrName"=>array($arrName),
                                "label"=>array(
                                    "Prosentase"=>array(round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2)),
                                    "Remaining"=>array((100 - round((($hasil_remaining[$key] / $EffectiveMandaysMonthly) * 100),2)))
                                )
                            ])); 
                        }
                    }
                }
            }
        }elseif ($cek_role->group == 'DPG') {
            if ($cek_role->name == 'SID Manager' || $cek_role->name == 'SID SPV') {
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','DPG')->pluck('nik');
                
                $data = $data->whereIn('tb_timesheet.nik',$listGroup)->where('status','Done')->get();

                $getUserByGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')
                        ->join('roles', 'roles.id', '=', 'role_user.role_id')
                        ->select('users.name')
                        ->where('roles.group',$cek_role->group)
                        ->where('roles.name','not like','%Manager')
                        ->where('users.status_delete','-')
                        ->get();

                $data = $data->groupBy('month_number');

                $arrMonth = [1,2,3,4,5,6,7,8,9,10,11,12];

                $arrMonthMandays = [0,0,0,0,0,0,0,0,0,0,0,0];

                foreach($arrMonth as $key_months => $valueMonth){
                    $startDate = Carbon::now();
                    if (is_null($request->year)) {
                        $startDate->setYear(date('Y'));
                    }else{
                        $startDate->setYear($request->year);
                    }
                    $startDate->month($valueMonth);
                    

                    $endDate = Carbon::now();
                    if (is_null($request->year)) {
                        $endDate->setYear(date('Y'));
                    }else{
                        $endDate->setYear($request->year);
                    }
                    $endDate->month($valueMonth);

                    $startDateFinal = $startDate->startOfMonth()->format("Y-m-d");

                    $endDateFinal = $endDate->endOfMonth()->format("Y-m-d");

                    foreach($arrMonthMandays as $key_mandays => $valueMandays){
                        if ($key_months == $key_mandays) {
                            $arrMonthMandays[$key_mandays]  = $arrMonthMandays[$key_mandays]+count($this->getWorkDays($startDateFinal,$endDateFinal)["workdays"]->values());
                        }
                    }
                }

                foreach($data as $key => $value){
                    $hasil_prosentase  = array($key => 0);
                    $hasil_remaining   = array($key => 0);

                    $arrName = collect();
                    $arrProsentaseByUser = collect();
                    $arrRemainingByUser = collect();
                    $arrFinalProsentaseByUser = collect();
                    $arrFinalRemainingByUser = collect();

                    foreach($value as $datas){
                        $arrName->push($datas->name);
                    }

                    foreach($getUserByGroup as $value_group){
                        $arrName->push($value_group->name);
                    }

                    foreach($arrName->unique() as $key_name => $value_name){
                        $arrProsentaseByUser->put($value_name,0); 
                        $arrRemainingByUser->put($value_name,0);
                    }

                    foreach($value as $datas){
                        foreach($hasil_prosentase as $key_prosentase => $value_prosentase){
                            foreach($arrProsentaseByUser as $key_byUser => $value_byUser){
                                if ($key_byUser == $datas->name) {
                                    $arrProsentaseByUser[$key_byUser] = $value_byUser+$datas->point_mandays;
                                }
                            }
                            $hasil_prosentase[$key_prosentase] = $arrFinalProsentaseByUser;
                        }
                    }

                    foreach($arrProsentaseByUser as $key_byUsers => $value_byUsers){
                        $arrFinalProsentaseByUser->push(round((float)$value_byUsers / $arrMonthMandays[$key] * 100,2));
                        $arrFinalRemainingByUser->push(100 - (round(($value_byUsers / $arrMonthMandays[$key]) * 100,2)));

                    }

                    foreach($value as $datas){
                        foreach($hasil_prosentase as $key_prosentase => $value_prosentase){
                            $hasil_prosentase[$key_prosentase] = $arrFinalProsentaseByUser;
                        }

                        foreach($hasil_remaining as $key_remaining => $value_remaining){
                            $hasil_remaining[$key_remaining] = $arrFinalRemainingByUser;
                        }
                    }

                    foreach($arrMonth as $keys => $month){
                        if ($month == $key) {
                            $arrMonth[$keys] = array($key=>collect([
                                "arrName"=>array($arrName->unique()),
                                "label"=>array(
                                    "Prosentase"=>$hasil_prosentase[$key_prosentase],
                                    "Remaining"=>$hasil_remaining[$key_remaining]
                                )
                            ])); 
                        }
                    }
                }
            } else {
                $startDate = Carbon::now()->startOfMonth()->format("Y-m-d");
                $endDate = Carbon::now()->endOfMonth()->format("Y-m-d");

                $data = $data->where('tb_timesheet.nik',$nik)->where('status','Done')->get()->groupBy('month_number');

                $EffectiveMandaysMonthly = count($this->getWorkDays($startDate,$endDate)["workdays"]->values());
                $arrMonth = [1,2,3,4,5,6,7,8,9,10,11,12];

                foreach($data as $key => $value){
                    $hasil_prosentase  = array($key => 0);
                    $hasil_remaining   = array($key => 0);

                    foreach($value as $datas){
                        $arrName = collect([$datas->name]);
                        foreach($hasil_prosentase as $key_prosentase => $value_prosentase){
                            $hasil_prosentase[$key_prosentase] = $value_prosentase+$datas->point_mandays;
                        }

                        foreach($hasil_remaining as $key_remaining => $value_remaining){
                            $hasil_remaining[$key_remaining] = $value_remaining+$datas->point_mandays;
                        }
                    }

                    foreach($arrMonth as $keys => $month){
                        if ($month == $key) {
                            $arrMonth[$keys] = array($key=>collect([
                                "arrName"=>array($arrName),
                                "label"=>array(
                                    "Prosentase"=>array(round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2)),
                                    "Remaining"=>array((100 - round((($hasil_remaining[$key] / $EffectiveMandaysMonthly) * 100),2)))
                                )
                            ])); 
                        }
                    }
                }
            }
        }elseif ($cek_role->group == 'presales') {
            if ($cek_role->name == 'SOL Manager') {
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','presales')->pluck('nik');
                
                $data = $data->whereIn('tb_timesheet.nik',$listGroup)->where('status','Done')->get();

                $getUserByGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')
                        ->join('roles', 'roles.id', '=', 'role_user.role_id')
                        ->select('users.name')
                        ->where('roles.group',$cek_role->group)
                        ->where('roles.name','not like','%Manager')
                        ->where('users.status_delete','-')
                        ->get();

                $data = $data->groupBy('month_number');

                $arrMonth = [1,2,3,4,5,6,7,8,9,10,11,12];

                $arrMonthMandays = [0,0,0,0,0,0,0,0,0,0,0,0];

                foreach($arrMonth as $key_months => $valueMonth){
                    $startDate = Carbon::now();
                    $startDate->month($valueMonth);

                    $endDate = Carbon::now();
                    $endDate->month($valueMonth);

                    $startDateFinal = $startDate->startOfMonth()->format("Y-m-d");
                    $endDateFinal = $endDate->endOfMonth()->format("Y-m-d");

                    foreach($arrMonthMandays as $key_mandays => $valueMandays){
                        if ($key_months == $key_mandays) {
                            $arrMonthMandays[$key_mandays]  = $arrMonthMandays[$key_mandays]+count($this->getWorkDays($startDateFinal,$endDateFinal)["workdays"]->values());
                        }
                    }
                }

                foreach($data as $key => $value){
                    $hasil_prosentase  = array($key => 0);
                    $hasil_remaining   = array($key => 0);

                    $arrName = collect();
                    $arrProsentaseByUser = collect();
                    $arrRemainingByUser = collect();
                    $arrFinalProsentaseByUser = collect();
                    $arrFinalRemainingByUser = collect();

                    foreach($value as $datas){
                        $arrName->push($datas->name);
                    }

                    foreach($getUserByGroup as $value_group){
                        $arrName->push($value_group->name);
                    }

                    foreach($arrName->unique() as $key_name => $value_name){
                        $arrProsentaseByUser->put($value_name,0); 
                        $arrRemainingByUser->put($value_name,0);
                    }

                    foreach($value as $datas){
                        foreach($hasil_prosentase as $key_prosentase => $value_prosentase){
                            foreach($arrProsentaseByUser as $key_byUser => $value_byUser){
                                    if ($key_byUser == $datas->name) {
                                    $arrProsentaseByUser[$key_byUser] = $value_byUser+$datas->point_mandays;
                                }
                            }
                            $hasil_prosentase[$key_prosentase] = $arrFinalProsentaseByUser;
                        }
                    }

                    foreach($arrProsentaseByUser as $key_byUsers => $value_byUsers){
                        $arrFinalProsentaseByUser->push(round((float)$value_byUsers / $arrMonthMandays[$key] * 100,2));
                        $arrFinalRemainingByUser->push(100 - (round(($value_byUsers / $arrMonthMandays[$key]) * 100,2)));

                    }

                    foreach($value as $datas){
                        foreach($hasil_prosentase as $key_prosentase => $value_prosentase){
                            $hasil_prosentase[$key_prosentase] = $arrFinalProsentaseByUser;
                        }

                        foreach($hasil_remaining as $key_remaining => $value_remaining){
                            $hasil_remaining[$key_remaining] = $arrFinalRemainingByUser;
                        }
                    }

                    foreach($arrMonth as $keys => $month){
                        if ($month == $key) {
                            $arrMonth[$keys] = array($key=>collect([
                                "arrName"=>array($arrName->unique()),
                                "label"=>array(
                                    "Prosentase"=>$hasil_prosentase[$key_prosentase],
                                    "Remaining"=>$hasil_remaining[$key_remaining]
                                )
                            ])); 
                        }
                    }
                }
            } else {
                $startDate = Carbon::now()->startOfMonth()->format("Y-m-d");
                $endDate = Carbon::now()->endOfMonth()->format("Y-m-d");

                $data = $data->where('tb_timesheet.nik',$nik)->where('status','Done')->get()->groupBy('month_number');

                $EffectiveMandaysMonthly = count($this->getWorkDays($startDate,$endDate)["workdays"]->values());
                $arrMonth = [1,2,3,4,5,6,7,8,9,10,11,12];

                foreach($data as $key => $value){
                    $hasil_prosentase  = array($key => 0);
                    $hasil_remaining   = array($key => 0);

                    foreach($value as $datas){
                        $arrName = collect([$datas->name]);
                        foreach($hasil_prosentase as $key_prosentase => $value_prosentase){
                            $hasil_prosentase[$key_prosentase] = $value_prosentase+$datas->point_mandays;
                        }

                        foreach($hasil_remaining as $key_remaining => $value_remaining){
                            $hasil_remaining[$key_remaining] = $value_remaining+$datas->point_mandays;
                        }
                    }

                    foreach($arrMonth as $keys => $month){
                        if ($month == $key) {
                            $arrMonth[$keys] = array($key=>collect([
                                "arrName"=>array($arrName),
                                "label"=>array(
                                    "Prosentase"=>array(round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2)),
                                    "Remaining"=>array((100 - round((($hasil_remaining[$key] / $EffectiveMandaysMonthly) * 100),2)))
                                )
                            ])); 
                        }
                    }
                }
            }
        }elseif ($cek_role->group == 'bcd') {
            if ($cek_role->name == 'BCD Manager' || $cek_role->name == 'BCD Development SPV') {
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','bcd')->pluck('nik');

                $data = $data->whereIn('tb_timesheet.nik',$listGroup)->where('status','Done')->get();

                $data = $data->groupBy('month_number');

                $arrMonth = [1,2,3,4,5,6,7,8,9,10,11,12];

                $arrMonthMandays = [0,0,0,0,0,0,0,0,0,0,0,0];

                foreach($arrMonth as $key_months => $valueMonth){
                    $startDate = Carbon::now();
                    $startDate->month($valueMonth);

                    $endDate = Carbon::now();
                    $endDate->month($valueMonth);

                    $startDateFinal = $startDate->startOfMonth()->format("Y-m-d");
                    $endDateFinal = $endDate->endOfMonth()->format("Y-m-d");

                    foreach($arrMonthMandays as $key_mandays => $valueMandays){
                        if ($key_months == $key_mandays) {
                            $arrMonthMandays[$key_mandays]  = $arrMonthMandays[$key_mandays]+count($this->getWorkDays($startDateFinal,$endDateFinal)["workdays"]->values());
                        }
                    }
                }

                foreach($data as $key => $value){
                    $hasil_prosentase  = array($key => 0);
                    $hasil_remaining   = array($key => 0);

                    $arrName = collect();
                    $arrProsentaseByUser = collect();
                    $arrRemainingByUser = collect();
                    $arrFinalProsentaseByUser = collect();
                    $arrFinalRemainingByUser = collect();

                    foreach($value as $datas){
                        $arrName->push($datas->name);
                    }

                    foreach($getUserByGroup as $value_group){
                        $arrName->push($value_group->name);
                    }

                    foreach($arrName->unique() as $key_name => $value_name){
                        $arrProsentaseByUser->put($value_name,0); 
                        $arrRemainingByUser->put($value_name,0);
                    }

                    foreach($value as $datas){
                        foreach($hasil_prosentase as $key_prosentase => $value_prosentase){
                            foreach($arrProsentaseByUser as $key_byUser => $value_byUser){
                                    if ($key_byUser == $datas->name) {
                                    $arrProsentaseByUser[$key_byUser] = $value_byUser+$datas->point_mandays;
                                }
                            }
                            $hasil_prosentase[$key_prosentase] = $arrFinalProsentaseByUser;
                        }
                    }

                    foreach($arrProsentaseByUser as $key_byUsers => $value_byUsers){
                        $arrFinalProsentaseByUser->push(round((float)$value_byUsers / $arrMonthMandays[$key] * 100,2));
                        $arrFinalRemainingByUser->push(100 - (round(($value_byUsers / $arrMonthMandays[$key]) * 100,2)));

                    }

                    foreach($value as $datas){
                        foreach($hasil_prosentase as $key_prosentase => $value_prosentase){
                            $hasil_prosentase[$key_prosentase] = $arrFinalProsentaseByUser;
                        }

                        foreach($hasil_remaining as $key_remaining => $value_remaining){
                            $hasil_remaining[$key_remaining] = $arrFinalRemainingByUser;
                        }
                    }

                    foreach($arrMonth as $keys => $month){
                        if ($month == $key) {
                            $arrMonth[$keys] = array($key=>collect([
                                "arrName"=>array($arrName->unique()),
                                "label"=>array(
                                    "Prosentase"=>$hasil_prosentase[$key_prosentase],
                                    "Remaining"=>$hasil_remaining[$key_remaining]
                                )
                            ])); 
                        }
                    }
                }
            } else {
                $startDate = Carbon::now()->startOfMonth()->format("Y-m-d");
                $endDate = Carbon::now()->endOfMonth()->format("Y-m-d");

                $data = $data->where('tb_timesheet.nik',$nik)->where('status','Done')->get()->groupBy('month_number');

                $EffectiveMandaysMonthly = count($this->getWorkDays($startDate,$endDate)["workdays"]->values());
                $arrMonth = [1,2,3,4,5,6,7,8,9,10,11,12];

                foreach($data as $key => $value){
                    $hasil_prosentase  = array($key => 0);
                    $hasil_remaining   = array($key => 0);

                    foreach($value as $datas){
                        $arrName = collect([$datas->name]);
                        foreach($hasil_prosentase as $key_prosentase => $value_prosentase){
                            $hasil_prosentase[$key_prosentase] = $value_prosentase+$datas->point_mandays;
                        }

                        foreach($hasil_remaining as $key_remaining => $value_remaining){
                            $hasil_remaining[$key_remaining] = $value_remaining+$datas->point_mandays;
                        }
                    }

                    foreach($arrMonth as $keys => $month){
                        if ($month == $key) {
                            $arrMonth[$keys] = array($key=>collect([
                                "arrName"=>array($arrName),
                                "label"=>array(
                                    "Prosentase"=>array(round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2)),
                                    "Remaining"=>array((100 - round((($hasil_remaining[$key] / $EffectiveMandaysMonthly) * 100),2)))
                                )
                            ])); 
                        }
                    }
                }

            }
        }elseif ($cek_role->group == 'hr') {
            if ($cek_role->name == 'HR Manager') {
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','hr')->pluck('nik');
                
                $data = $data->whereIn('tb_timesheet.nik',$listGroup)->where('status','Done')->get();

                $getUserByGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')
                        ->join('roles', 'roles.id', '=', 'role_user.role_id')
                        ->select('users.name')
                        ->where('roles.group',$cek_role->group)
                        ->where('roles.name','not like','%Manager')
                        ->where('users.status_delete','-')
                        ->get();

                $data = $data->groupBy('month_number');

                $arrMonth = [1,2,3,4,5,6,7,8,9,10,11,12];

                $arrMonthMandays = [0,0,0,0,0,0,0,0,0,0,0,0];

                foreach($arrMonth as $key_months => $valueMonth){
                    $startDate = Carbon::now();
                    $startDate->month($valueMonth);

                    $endDate = Carbon::now();
                    $endDate->month($valueMonth);

                    $startDateFinal = $startDate->startOfMonth()->format("Y-m-d");
                    $endDateFinal = $endDate->endOfMonth()->format("Y-m-d");

                    foreach($arrMonthMandays as $key_mandays => $valueMandays){
                        if ($key_months == $key_mandays) {
                            $arrMonthMandays[$key_mandays]  = $arrMonthMandays[$key_mandays]+count($this->getWorkDays($startDateFinal,$endDateFinal)["workdays"]->values());
                        }
                    }
                }

                foreach($data as $key => $value){
                    $hasil_prosentase  = array($key => 0);
                    $hasil_remaining   = array($key => 0);

                    $arrName = collect();
                    $arrProsentaseByUser = collect();
                    $arrRemainingByUser = collect();
                    $arrFinalProsentaseByUser = collect();
                    $arrFinalRemainingByUser = collect();

                    foreach($value as $datas){
                        $arrName->push($datas->name);
                    }

                    foreach($getUserByGroup as $value_group){
                        $arrName->push($value_group->name);
                    }

                    foreach($arrName->unique() as $key_name => $value_name){
                        $arrProsentaseByUser->put($value_name,0); 
                        $arrRemainingByUser->put($value_name,0);
                    }

                    foreach($value as $datas){
                        foreach($hasil_prosentase as $key_prosentase => $value_prosentase){
                            foreach($arrProsentaseByUser as $key_byUser => $value_byUser){
                                    if ($key_byUser == $datas->name) {
                                    $arrProsentaseByUser[$key_byUser] = $value_byUser+$datas->point_mandays;
                                }
                            }
                            $hasil_prosentase[$key_prosentase] = $arrFinalProsentaseByUser;
                        }
                    }

                    foreach($arrProsentaseByUser as $key_byUsers => $value_byUsers){
                        $arrFinalProsentaseByUser->push(round((float)$value_byUsers / $arrMonthMandays[$key] * 100,2));
                        $arrFinalRemainingByUser->push(100 - (round(($value_byUsers / $arrMonthMandays[$key]) * 100,2)));

                    }

                    foreach($value as $datas){
                        foreach($hasil_prosentase as $key_prosentase => $value_prosentase){
                            $hasil_prosentase[$key_prosentase] = $arrFinalProsentaseByUser;
                        }

                        foreach($hasil_remaining as $key_remaining => $value_remaining){
                            $hasil_remaining[$key_remaining] = $arrFinalRemainingByUser;
                        }
                    }

                    foreach($arrMonth as $keys => $month){
                        if ($month == $key) {
                            $arrMonth[$keys] = array($key=>collect([
                                "arrName"=>array($arrName->unique()),
                                "label"=>array(
                                    "Prosentase"=>$hasil_prosentase[$key_prosentase],
                                    "Remaining"=>$hasil_remaining[$key_remaining]
                                )
                            ])); 
                        }
                    }
                }
            } else {
                $startDate = Carbon::now()->startOfMonth()->format("Y-m-d");
                $endDate = Carbon::now()->endOfMonth()->format("Y-m-d");

                $data = $data->where('tb_timesheet.nik',$nik)->where('status','Done')->get()->groupBy('month_number');

                $EffectiveMandaysMonthly = count($this->getWorkDays($startDate,$endDate)["workdays"]->values());
                $arrMonth = [1,2,3,4,5,6,7,8,9,10,11,12];

                foreach($data as $key => $value){
                    $hasil_prosentase  = array($key => 0);
                    $hasil_remaining   = array($key => 0);

                    foreach($value as $datas){
                        $arrName = collect([$datas->name]);
                        foreach($hasil_prosentase as $key_prosentase => $value_prosentase){
                            $hasil_prosentase[$key_prosentase] = $value_prosentase+$datas->point_mandays;
                        }

                        foreach($hasil_remaining as $key_remaining => $value_remaining){
                            $hasil_remaining[$key_remaining] = $value_remaining+$datas->point_mandays;
                        }
                    }

                    foreach($arrMonth as $keys => $month){
                        if ($month == $key) {
                            $arrMonth[$keys] = array($key=>collect([
                                "arrName"=>array($arrName),
                                "label"=>array(
                                    "Prosentase"=>array(round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2)),
                                    "Remaining"=>array((100 - round((($hasil_remaining[$key] / $EffectiveMandaysMonthly) * 100),2)))
                                )
                            ])); 
                        }
                    }
                }
            }
        }elseif ($cek_role->group == 'msm') {
            if ($cek_role->name == 'MSM Manager' || $cek_role->name == 'MSM TS SPV') {
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','hr')->pluck('nik');

                $data = $data->whereIn('tb_timesheet.nik',$listGroup)->where('status','Done')->get();

                $getUserByGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')
                        ->join('roles', 'roles.id', '=', 'role_user.role_id')
                        ->select('users.name')
                        ->where('roles.group',$cek_role->group)
                        ->where('roles.name','not like','%Manager')
                        ->where('users.status_delete','-')
                        ->get();

                $data = $data->groupBy('month_number');

                $arrMonth = [1,2,3,4,5,6,7,8,9,10,11,12];

                $arrMonthMandays = [0,0,0,0,0,0,0,0,0,0,0,0];

                foreach($arrMonth as $key_months => $valueMonth){
                    $startDate = Carbon::now();
                    $startDate->month($valueMonth);

                    $endDate = Carbon::now();
                    $endDate->month($valueMonth);

                    $startDateFinal = $startDate->startOfMonth()->format("Y-m-d");
                    $endDateFinal = $endDate->endOfMonth()->format("Y-m-d");

                    foreach($arrMonthMandays as $key_mandays => $valueMandays){
                        if ($key_months == $key_mandays) {
                            $arrMonthMandays[$key_mandays]  = $arrMonthMandays[$key_mandays]+count($this->getWorkDays($startDateFinal,$endDateFinal)["workdays"]->values());
                        }
                    }
                }

                foreach($data as $key => $value){
                    $hasil_prosentase  = array($key => 0);
                    $hasil_remaining   = array($key => 0);

                    $arrName = collect();
                    $arrProsentaseByUser = collect();
                    $arrRemainingByUser = collect();
                    $arrFinalProsentaseByUser = collect();
                    $arrFinalRemainingByUser = collect();

                    foreach($value as $datas){
                        $arrName->push($datas->name);
                    }

                    foreach($getUserByGroup as $value_group){
                        $arrName->push($value_group->name);
                    }

                    foreach($arrName->unique() as $key_name => $value_name){
                        $arrProsentaseByUser->put($value_name,0); 
                        $arrRemainingByUser->put($value_name,0);
                    }

                    foreach($value as $datas){
                        foreach($hasil_prosentase as $key_prosentase => $value_prosentase){
                            foreach($arrProsentaseByUser as $key_byUser => $value_byUser){
                                    if ($key_byUser == $datas->name) {
                                    $arrProsentaseByUser[$key_byUser] = $value_byUser+$datas->point_mandays;
                                }
                            }
                            $hasil_prosentase[$key_prosentase] = $arrFinalProsentaseByUser;
                        }
                    }

                    foreach($arrProsentaseByUser as $key_byUsers => $value_byUsers){
                        $arrFinalProsentaseByUser->push(round((float)$value_byUsers / $arrMonthMandays[$key] * 100,2));
                        $arrFinalRemainingByUser->push(100 - (round(($value_byUsers / $arrMonthMandays[$key]) * 100,2)));

                    }

                    foreach($value as $datas){
                        foreach($hasil_prosentase as $key_prosentase => $value_prosentase){
                            $hasil_prosentase[$key_prosentase] = $arrFinalProsentaseByUser;
                        }

                        foreach($hasil_remaining as $key_remaining => $value_remaining){
                            $hasil_remaining[$key_remaining] = $arrFinalRemainingByUser;
                        }
                    }

                    foreach($arrMonth as $keys => $month){
                        if ($month == $key) {
                            $arrMonth[$keys] = array($key=>collect([
                                "arrName"=>array($arrName->unique()),
                                "label"=>array(
                                    "Prosentase"=>$hasil_prosentase[$key_prosentase],
                                    "Remaining"=>$hasil_remaining[$key_remaining]
                                )
                            ])); 
                        }
                    }
                }
            }else{
                $startDate = Carbon::now()->startOfMonth()->format("Y-m-d");
                $endDate = Carbon::now()->endOfMonth()->format("Y-m-d");

                $data = $data->where('tb_timesheet.nik',$nik)->where('status','Done')->get()->groupBy('month_number');

                $EffectiveMandaysMonthly = count($this->getWorkDays($startDate,$endDate)["workdays"]->values());
                $arrMonth = [1,2,3,4,5,6,7,8,9,10,11,12];

                foreach($data as $key => $value){
                    $hasil_prosentase  = array($key => 0);
                    $hasil_remaining   = array($key => 0);

                    foreach($value as $datas){
                        $arrName = collect([$datas->name]);
                        foreach($hasil_prosentase as $key_prosentase => $value_prosentase){
                            $hasil_prosentase[$key_prosentase] = $value_prosentase+$datas->point_mandays;
                        }

                        foreach($hasil_remaining as $key_remaining => $value_remaining){
                            $hasil_remaining[$key_remaining] = $value_remaining+$datas->point_mandays;
                        }
                    }

                    foreach($arrMonth as $keys => $month){
                        if ($month == $key) {
                            $arrMonth[$keys] = array($key=>collect([
                                "arrName"=>array($arrName),
                                "label"=>array(
                                    "Prosentase"=>array(round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2)),
                                    "Remaining"=>array((100 - round((($hasil_remaining[$key] / $EffectiveMandaysMonthly) * 100),2)))
                                )
                            ])); 
                        }
                    }
                }
            }
        } else {
            $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group',$request->roles)->pluck('nik');

            $data = $data->whereIn('tb_timesheet.nik',$listGroup)->where('status','Done')->get();

            $getUserByGroupOD = User::join('role_user', 'role_user.user_id', '=', 'users.nik')
                    ->join('roles', 'roles.id', '=', 'role_user.role_id')
                    ->select('users.name')
                    ->where('roles.group',$request->roles)
                    ->where('roles.name','not like','%Manager')
                    ->where('users.status_delete','-')
                    ->get();

            $data = $data->groupBy('month_number');

            $arrMonth = [1,2,3,4,5,6,7,8,9,10,11,12];

            $arrMonthMandays = [0,0,0,0,0,0,0,0,0,0,0,0];

            foreach($arrMonth as $key_months => $valueMonth){
                $startDate = Carbon::now();
                $startDate->month($valueMonth);

                $endDate = Carbon::now();
                $endDate->month($valueMonth);

                $startDateFinal = $startDate->startOfMonth()->format("Y-m-d");
                $endDateFinal = $endDate->endOfMonth()->format("Y-m-d");

                foreach($arrMonthMandays as $key_mandays => $valueMandays){
                    if ($key_months == $key_mandays) {
                        $arrMonthMandays[$key_mandays]  = $arrMonthMandays[$key_mandays]+count($this->getWorkDays($startDateFinal,$endDateFinal)["workdays"]->values());
                    }
                }
            }

            foreach($data as $key => $value){
                $hasil_prosentase  = array($key => 0);
                $hasil_remaining   = array($key => 0);

                $arrName = collect();
                $arrProsentaseByUser = collect();
                $arrRemainingByUser = collect();
                $arrFinalProsentaseByUser = collect();
                $arrFinalRemainingByUser = collect();

                foreach($value as $datas){
                    $arrName->push($datas->name);
                }

                foreach($getUserByGroupOD as $value_group){
                    $arrName->push($value_group->name);
                }

                foreach($arrName->unique() as $key_name => $value_name){
                    $arrProsentaseByUser->put($value_name,0); 
                    $arrRemainingByUser->put($value_name,0);
                }

                foreach($value as $datas){
                    foreach($hasil_prosentase as $key_prosentase => $value_prosentase){
                        foreach($arrProsentaseByUser as $key_byUser => $value_byUser){
                                if ($key_byUser == $datas->name) {
                                $arrProsentaseByUser[$key_byUser] = $value_byUser+$datas->point_mandays;
                            }
                        }
                        $hasil_prosentase[$key_prosentase] = $arrFinalProsentaseByUser;
                    }
                }

                foreach($arrProsentaseByUser as $key_byUsers => $value_byUsers){
                    $arrFinalProsentaseByUser->push(round((float)$value_byUsers / $arrMonthMandays[$key] * 100,2));
                    $arrFinalRemainingByUser->push(100 - (round(($value_byUsers / $arrMonthMandays[$key]) * 100,2)));

                }

                foreach($value as $datas){
                    foreach($hasil_prosentase as $key_prosentase => $value_prosentase){
                        $hasil_prosentase[$key_prosentase] = $arrFinalProsentaseByUser;
                    }

                    foreach($hasil_remaining as $key_remaining => $value_remaining){
                        $hasil_remaining[$key_remaining] = $arrFinalRemainingByUser;
                    }
                }

                foreach($arrMonth as $keys => $month){
                    if ($month == $key) {
                        $arrMonth[$keys] = array($key=>collect([
                            "arrName"=>array($arrName->unique()),
                            "label"=>array(
                                "Prosentase"=>$hasil_prosentase[$key_prosentase],
                                "Remaining"=>$hasil_remaining[$key_remaining]
                            )
                        ])); 
                    }
                }
            }
        }

        return $arrMonth;
    }

    public function getFilterStatusChart(Request $request)
    {
        $nik = Auth::User()->nik;
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('name', 'roles.group')->where('user_id', $nik)->first(); 

        $arrayMonth = collect();
        foreach($request->month as $month){
            $date = Carbon::parse($month);
            // Get the numeric representation of the month (1 to 12)
            $numericMonth = $date->month;
            // return $numericMonth;
            $arrayMonth->push($numericMonth);
        } 

        $data = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')
                ->join('role_user', 'role_user.user_id', '=', 'users.nik')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->where(function ($query) use ($arrayMonth) {
                    foreach ($arrayMonth as $month) {
                        $query->orWhereRaw("MONTH(start_date) = $month");
                    }
                });

        if (is_null($request->year)) {
            $data = $data->whereYear('start_date',date('Y'));
        }else{
            $data = $data->whereYear('start_date',$request->year);                    
        }

        if ($request->task[0] === null) {
            $data = $data;
        }else{
            $data = $data->whereIn('task',$request->task);                    
        }

        if ($request->phase[0] === null) {
            $data = $data;
        }else{
            $data = $data->whereIn('phase',$request->phase);                    
        }

        if ($request->status[0] === null) {
            $data = $data->where('status','Done');
        }else{
            $data = $data->whereIn('status',$request->status);                    
        }

        if ($request->schedule[0] === null) {
            $data = $data;
        }else{
            $data = $data->whereIn('schedule',$request->schedule);                    
        }

        if ($cek_role->group == 'pmo') {
            if ($cek_role->name == 'PMO Manager' || $cek_role->name == 'PMO SPV') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.group',$cek_role->group);
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else {
                $data = $data->where('tb_timesheet.nik',Auth::User()->nik);
            }
        }elseif ($cek_role->group == 'DPG') {
            if ($cek_role->name == 'SID Manager' || $cek_role->name == 'SID SPV') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.group',$cek_role->group);
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else {
                $data = $data->where('tb_timesheet.nik',Auth::User()->nik);
            }
        }elseif ($cek_role->group == 'presales') {
            if ($cek_role->name == 'SOL Manager') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.group',$cek_role->group);
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else {
               $data = $data->where('tb_timesheet.nik',Auth::User()->nik);
            }
        }elseif ($cek_role->group == 'bcd') {
            if ($cek_role->name == 'BCD Manager' || $cek_role->name == 'BCD Development SPV') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.group',$cek_role->group);
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else {
               $data = $data->where('tb_timesheet.nik',Auth::User()->nik);
            }
        }elseif ($cek_role->group == 'hr') {
            if ($cek_role->name == 'HR Manager') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.group',$cek_role->group);
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else {
               $data = $data->where('tb_timesheet.nik',Auth::User()->nik);
            }
        }elseif($cek_role->group == 'msm') {
            if ($cek_role->name == 'MSM Manager' || $cek_role->name == 'MSM TS SPV') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.group',$cek_role->group);
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else {
               $data = $data->where('tb_timesheet.nik',Auth::User()->nik);
            }
        }

        $status = $data->get();

        if (count($status) == 0) {
            $hasil2 = [0,0,0,0];
        }else{
            $hasil = [0,0,0,0];
            $bulan_angka = ['Done','Undone','Cancel','Reschedule'];
            $pie = 0;

            foreach ($bulan_angka as $key => $value2) {
                foreach ($status as $value) {
                    if ($value->status == $value2) {
                        $hasil[$key]++;
                        $pie++;
                    }
                }
            }

            $hasil2 = [0,0,0,0];
            foreach ($hasil as $key => $value) {
                $hasil2[$key] = ($value/$pie)*100;
            }
        }

        return $hasil2;
    }

    public function getFilterLevelChart(Request $request)
    {
        $nik = Auth::User()->nik;
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('name', 'roles.group')->where('user_id', $nik)->first(); 

        $arrayMonth = collect();
        foreach($request->month as $month){
            $date = Carbon::parse($month);
            // Get the numeric representation of the month (1 to 12)
            $numericMonth = $date->month;
            // return $numericMonth;
            $arrayMonth->push($numericMonth);
        } 

        $data = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')
                ->join('role_user', 'role_user.user_id', '=', 'users.nik')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->where(function ($query) use ($arrayMonth) {
                    foreach ($arrayMonth as $month) {
                        $query->orWhereRaw("MONTH(start_date) = $month");
                    }
                });

        if (is_null($request->year)) {
            $data = $data->whereYear('start_date',date('Y'));
        }else{
            $data = $data->whereYear('start_date',$request->year);                    
        }

        if ($request->task[0] === null) {
            $data = $data;
        }else{
            $data = $data->whereIn('task',$request->task);                    
        }

        if ($request->phase[0] === null) {
            $data = $data;
        }else{
            $data = $data->whereIn('phase',$request->phase);                    
        }

        if ($request->status[0] === null) {
            $data = $data->where('status','Done');
        }else{
            $data = $data->whereIn('status',$request->status);                    
        }

        if ($request->schedule[0] === null) {
            $data = $data;
        }else{
            $data = $data->whereIn('schedule',$request->schedule);                    
        }

        if ($cek_role->group == 'pmo') {
            if ($cek_role->name == 'PMO Manager' || $cek_role->name == 'PMO SPV') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.group',$cek_role->group);
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else {
                $data = $data->where('tb_timesheet.nik',Auth::User()->nik);
            }
        }elseif ($cek_role->group == 'DPG') {
            if ($cek_role->name == 'SID Manager' || $cek_role->name == 'SID SPV') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.group',$cek_role->group);
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else {
                $data = $data->where('tb_timesheet.nik',Auth::User()->nik);
            }
        }elseif ($cek_role->group == 'presales') {
            if ($cek_role->name == 'SOL Manager') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.group',$cek_role->group);
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else {
               $data = $data->where('tb_timesheet.nik',Auth::User()->nik);
            }
        }elseif ($cek_role->group == 'bcd') {
            if ($cek_role->name == 'BCD Manager' || $cek_role->name == 'BDC Development SPV') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.group',$cek_role->group);
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else {
               $data = $data->where('tb_timesheet.nik',Auth::User()->nik);
            }
        }elseif ($cek_role->group == 'hr') {
            if ($cek_role->name == 'HR Manager') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.group',$cek_role->group);
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else {
               $data = $data->where('tb_timesheet.nik',Auth::User()->nik);
            }
        }elseif($cek_role->group == 'msm') {
            if ($cek_role->name == 'MSM Manager' || $cek_role->name == 'MSM TS SPV') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.group',$cek_role->group);
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else {
               $data = $data->where('tb_timesheet.nik',Auth::User()->nik);
            }
        }

        $level = $data->get();

        // return $level;

        if (count($level) == 0) {
            $hasil2 = [0,0,0,0,0];
        }else{
            // $first = $level[0]->level;
            $hasil = [0,0,0,0,0];
            $bulan_angka = ['A', 'B', 'C', 'D', 'E'];
            $pie = 0;

            foreach ($bulan_angka as $key => $value2) {
                foreach ($level as $value) {
                    if ($value->level == $value2) {
                        $hasil[$key]++;
                        $pie++;
                    }
                }
            }

            $hasil2 = collect();
            foreach ($hasil as $key => $value) {
                // return $hasil2[$key];
                if ($value !== 0) {
                    $hasil2->push(($value/$pie)*100);
                }else{
                    $hasil2->push(0);   
                }
            }
        }
        
        return $hasil2;
    }

    public function getFilterScheduleChart(Request $request)
    {
        $nik = Auth::User()->nik;
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('name', 'roles.group')->where('user_id', $nik)->first(); 

        $arrayMonth = collect();
        $arrayMonth = collect();
        foreach($request->month as $month){
            $date = Carbon::parse($month);
            // Get the numeric representation of the month (1 to 12)
            $numericMonth = $date->month;
            // return $numericMonth;
            $arrayMonth->push($numericMonth);
        } 

        $data = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')
                ->join('role_user', 'role_user.user_id', '=', 'users.nik')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->where(function ($query) use ($arrayMonth) {
                    foreach ($arrayMonth as $month) {
                        $query->orWhereRaw("MONTH(start_date) = $month");
                    }
                });

        if (is_null($request->year)) {
            $data = $data->whereYear('start_date',date('Y'));
        }else{
            $data = $data->whereYear('start_date',$request->year);                    
        }

        if ($request->task[0] === null) {
            $data = $data;
        }else{
            $data = $data->whereIn('task',$request->task);                    
        }

        if ($request->phase[0] === null) {
            $data = $data;
        }else{
            $data = $data->whereIn('phase',$request->phase);                    
        }

        if ($request->status[0] === null) {
            $data = $data->where('status','Done');
        }else{
            $data = $data->whereIn('status',$request->status);                    
        }

        if ($request->schedule[0] === null) {
            $data = $data;
        }else{
            $data = $data->whereIn('schedule',$request->schedule);                    
        }

        if ($cek_role->group == 'pmo') {
            if ($cek_role->name == 'PMO Manager' || $cek_role->name == 'PMO SPV') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.group',$cek_role->group);
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else {
                $data = $data->where('tb_timesheet.nik',Auth::User()->nik);
            }
        }elseif ($cek_role->group == 'DPG') {
            if ($cek_role->name == 'SID Manager' || $cek_role->name == 'SID SPV') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.group',$cek_role->group);
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else {
                $data = $data->where('tb_timesheet.nik',Auth::User()->nik);
            }
        }elseif ($cek_role->group == 'presales') {
            if ($cek_role->name == 'SOL Manager') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.group',$cek_role->group);
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else {
               $data = $data->where('tb_timesheet.nik',Auth::User()->nik);
            }
        }elseif ($cek_role->group == 'bcd') {
            if ($cek_role->name == 'BCD Manager' || $cek_role->name == 'BCD Development SPV') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.group',$cek_role->group);
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else {
               $data = $data->where('tb_timesheet.nik',Auth::User()->nik);
            }
        }elseif ($cek_role->group == 'hr') {
            if ($cek_role->name == 'HR Manager') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.group',$cek_role->group);
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else {
               $data = $data->where('tb_timesheet.nik',Auth::User()->nik);
            }
        }elseif($cek_role->group == 'msm') {
            if ($cek_role->name == 'MSM Manager' || $cek_role->name == 'MSM TS SPV') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.group',$cek_role->group);
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else {
               $data = $data->where('tb_timesheet.nik',Auth::User()->nik);
            }
        }

        $schedule = $data->get();

        if (count($schedule) == 0) {
            $hasil2 = [0,0];
        }else{
            $first = $schedule[0]->schedule;

            $hasil = [0,0];
            $statusSchedule = ['Planned','Unplanned'];
            $pie = 0;

            foreach ($statusSchedule as $key => $value2) {
                foreach ($schedule as $value) {
                    if ($value->schedule == $value2) {
                        $hasil[$key]++;
                        $pie++;
                        // return $hasil;
                    }
                }
            }

            $hasil2 = [0,0];
            foreach ($hasil as $key => $value) {
                $hasil2[$key] = ($value/$pie)*100;
            }
        }

        return $hasil2;
    }

    public function getFilterTaskChart(Request $request)
    {
        $nik = Auth::User()->nik;
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('name', 'roles.group')->where('user_id', $nik)->first(); 

        $arrayMonth = collect();
        foreach($request->month as $month){
            $date = Carbon::parse($month);
            // Get the numeric representation of the month (1 to 12)
            $numericMonth = $date->month;
            // return $numericMonth;
            $arrayMonth->push($numericMonth);
        } 


        $data = DB::table('tb_timesheet')
                ->select('tb_timesheet_task.task as task_name')
                ->selectRaw('COUNT(tb_timesheet_task.task) as total_aktivitas')
                ->join('tb_timesheet_task','tb_timesheet_task.id','=','tb_timesheet.task')
                ->join('users','users.nik','tb_timesheet.nik')
                ->join('role_user', 'role_user.user_id', '=', 'users.nik')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->where(function ($query) use ($arrayMonth) {
                    foreach ($arrayMonth as $month) {
                        $query->orWhereRaw("MONTH(start_date) = $month");
                    }
                });

        if (is_null($request->year)) {
            $data = $data->whereYear('start_date',date('Y'));
        }else{
            $data = $data->whereYear('start_date',$request->year);                    
        }

        if ($request->task[0] === null) {
            $data = $data;
        }else{
            $data = $data->whereIn('tb_timesheet.task',$request->task);                    
        }

        if ($request->phase[0] === null) {
            $data = $data;
        }else{
            $data = $data->whereIn('tb_timesheet.phase',$request->phase);                    
        }

        if ($request->status[0] === null) {
            $data = $data->where('status','Done');
        }else{
            $data = $data->whereIn('status',$request->status);                    
        }

        if ($request->schedule[0] === null) {
            $data = $data;
        }else{
            $data = $data->whereIn('schedule',$request->schedule);                    
        }

        if ($cek_role->group == 'pmo') {
            if ($cek_role->name == 'PMO Manager' || $cek_role->name == 'PMO SPV') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.group',$cek_role->group);
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else {
                $data = $data->where('tb_timesheet.nik',Auth::User()->nik);
            }
        }elseif ($cek_role->group == 'DPG') {
            if ($cek_role->name == 'SID Manager' || $cek_role->name == 'SID SPV') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.group',$cek_role->group);
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else {
                $data = $data->where('tb_timesheet.nik',Auth::User()->nik);
            }
        }elseif ($cek_role->group == 'presales') {
            if ($cek_role->name == 'SOL Manager') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.group',$cek_role->group);
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else {
               $data = $data->where('tb_timesheet.nik',Auth::User()->nik);
            }
        }elseif ($cek_role->group == 'bcd') {
            if ($cek_role->name == 'BCD Manager' || $cek_role->name == 'BCD Development SPV') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.group',$cek_role->group);
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else {
               $data = $data->where('tb_timesheet.nik',Auth::User()->nik);
            }
        }elseif ($cek_role->group == 'hr') {
            if ($cek_role->name == 'HR Manager') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.group',$cek_role->group);
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else {
               $data = $data->where('tb_timesheet.nik',Auth::User()->nik);
            }
        }elseif($cek_role->group == 'msm') {
            if ($cek_role->name == 'MSM Manager' || $cek_role->name == 'MSM TS SPV') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.group',$cek_role->group);
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else {
               $data = $data->where('tb_timesheet.nik',Auth::User()->nik);
            }
        }

        $task = $data->groupBy('tb_timesheet.task')->get();

        $count_task = $task->sum('total_aktivitas');

        $hasil = collect();
        $label = collect();

        if (count($task) == 0) {
            $hasil->push(0);
        }else{
            foreach ($task as $value) {
                $label->push($value->task_name);
                $hasil->push(($value->total_aktivitas/$count_task)*100);   
            }
        }

        return collect(["label"=>$label,"data"=>$hasil]);
    }

    public function getFilterPhaseChart(Request $request)
    {
        $nik = Auth::User()->nik;
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('name', 'roles.group')->where('user_id', $nik)->first(); 

        $arrayMonth = collect();
        foreach($request->month as $month){
            $date = Carbon::parse($month);
            // Get the numeric representation of the month (1 to 12)
            $numericMonth = $date->month;
            // return $numericMonth;
            $arrayMonth->push($numericMonth);
        } 


        $data = DB::table('tb_timesheet')
                ->select('tb_timesheet_phase.phase as phase_name')
                ->selectRaw('COUNT(tb_timesheet_phase.phase) as total_aktivitas')
                ->join('tb_timesheet_phase','tb_timesheet_phase.id','=','tb_timesheet.phase')
                ->join('users','users.nik','tb_timesheet.nik')
                ->join('role_user', 'role_user.user_id', '=', 'users.nik')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->where(function ($query) use ($arrayMonth) {
                    foreach ($arrayMonth as $month) {
                        $query->orWhereRaw("MONTH(start_date) = $month");
                    }
                });

        if (is_null($request->year)) {
            $data = $data->whereYear('start_date',date('Y'));
        }else{
            $data = $data->whereYear('start_date',$request->year);                    
        }

        if ($request->task[0] === null) {
            $data = $data;
        }else{
            $data = $data->whereIn('tb_timesheet.task',$request->task);                    
        }

        if ($request->phase[0] === null) {
            $data = $data;
        }else{
            $data = $data->whereIn('tb_timesheet.phase',$request->phase);                    
        }

        if ($request->status[0] === null) {
            $data = $data->where('status','Done');
        }else{
            $data = $data->whereIn('status',$request->status);                    
        }

        if ($request->schedule[0] === null) {
            $data = $data;
        }else{
            $data = $data->whereIn('schedule',$request->schedule);                    
        }

        if ($cek_role->group == 'pmo') {
            if ($cek_role->name == 'PMO Manager' || $cek_role->name == 'PMO SPV') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.group',$cek_role->group);
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else {
                $data = $data->where('tb_timesheet.nik',Auth::User()->nik);
            }
        }elseif ($cek_role->group == 'DPG') {
            if ($cek_role->name == 'SID Manager' || $cek_role->name == 'SID SPV') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.group',$cek_role->group);
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else {
                $data = $data->where('tb_timesheet.nik',Auth::User()->nik);
            }
        }elseif ($cek_role->group == 'presales') {
            if ($cek_role->name == 'SOL Manager') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.group',$cek_role->group);
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else {
               $data = $data->where('tb_timesheet.nik',Auth::User()->nik);
            }
        }elseif ($cek_role->group == 'bcd') {
            if ($cek_role->name == 'BCD Manager' || $cek_role->name == 'BCD Development SPV') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.group',$cek_role->group);
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else {
               $data = $data->where('tb_timesheet.nik',Auth::User()->nik);
            }
        }elseif ($cek_role->group == 'hr') {
            if ($cek_role->name == 'HR Manager') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.group',$cek_role->group);
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else {
               $data = $data->where('tb_timesheet.nik',Auth::User()->nik);
            }
        }elseif($cek_role->group == 'msm') {
            if ($cek_role->name == 'MSM Manager' || $cek_role->name == 'MSM TS SPV') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.group',$cek_role->group);
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else {
               $data = $data->where('tb_timesheet.nik',Auth::User()->nik);
            }
        }

        $phase = $data->groupBy('tb_timesheet.phase')->get();

        $count_phase = $phase->sum('total_aktivitas');

        $hasil = collect();
        $label = collect();

        if (count($phase) == 0) {
            $hasil->push(0);
        }else{
            foreach ($phase as $value) {
                $label->push($value->phase_name);
                $hasil->push(($value->total_aktivitas/$count_phase)*100);   
            }
        }

        return collect(["label"=>$label,"data"=>$hasil]);
    }

    public function exportExcel(Request $request)
    {
        $nik = Auth::User()->nik;
        $cek_role = DB::table('role_user')->Rightjoin('roles', 'roles.id', '=', 'role_user.role_id')->select('name', 'roles.group')->where('user_id', $nik)->first(); 

        $appendedAttributesToHide = ['planned','threshold'];

        $arrayMonth = collect();
        $arrSumPoint = collect();
        foreach($request->month as $month){
            $date = Carbon::parse($month);
            // Get the numeric representation of the month (1 to 12)
            $numericMonth = $date->month;
            // return $numericMonth;
            $arrayMonth->push($numericMonth);
        }

        $dataTimesheet = Timesheet::Rightjoin('users','users.nik','=','tb_timesheet.nik')
            ->Leftjoin('tb_timesheet_phase','tb_timesheet_phase.id','=','tb_timesheet.phase')
            ->Leftjoin('tb_timesheet_task','tb_timesheet_task.id','=','tb_timesheet.task')
            ->select('users.nik','users.name','tb_timesheet.start_date','tb_timesheet.date_add','tb_timesheet.level','tb_timesheet_task.task','tb_timesheet_phase.phase','tb_timesheet.schedule','tb_timesheet.point_mandays','tb_timesheet.status','tb_timesheet.activity','tb_timesheet.end_date','tb_timesheet.type', DB::raw("(CASE WHEN (pid = 'null') THEN '-' WHEN (pid is null) THEN '-' ELSE pid END) as pid"))
            ->where(function ($query) use ($arrayMonth) {
                foreach ($arrayMonth as $month) {
                    $query->orWhereRaw("MONTH(start_date) = $month");
                }
            });

        $getUserByGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('users.name','users.nik')
            ->where('roles.group',$cek_role->group)
            ->where('roles.name','not like','%Manager')
            ->where('users.status_delete','-');

        $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group',$cek_role->group)->pluck('nik');

        if ($request->pic[0] === null) {
            $dataTimesheet = $dataTimesheet->whereIn('tb_timesheet.nik',$listGroup);

            $getUserByGroup = $getUserByGroup->get();

        }else{
            $dataTimesheet = $dataTimesheet->whereIn('tb_timesheet.nik',$request->pic);

            $getUserByGroup = $getUserByGroup
                                ->whereIn('nik',$request->pic)    
                                ->get();
        }

        if ($request->task[0] === null) {
            $dataTimesheet = $dataTimesheet;
        }else{
            $dataTimesheet = $dataTimesheet->whereIn('tb_timesheet_task.task',$request->task);                    
        }

        if ($request->status[0] === null) {
            $dataTimesheet = $dataTimesheet->where('status','Done');
        }else{
            $dataTimesheet = $dataTimesheet->whereIn('status',$request->status);                    
        }

        if (is_null($request->year)) {
            $dataTimesheet = $dataTimesheet->whereYear('start_date',date('Y'));
        }else{
            $dataTimesheet = $dataTimesheet->whereYear('start_date',$request->year);                    
        }

        if ($request->schedule[0] === null) {
            $dataTimesheet = $dataTimesheet;
        }else{
            $dataTimesheet = $dataTimesheet->whereIn('schedule',$request->schedule);                    
        }

        $dataTimesheet = $dataTimesheet->get()->makeHidden($appendedAttributesToHide);

        $collectByName = collect();

        foreach($dataTimesheet as $data){
            $collectByName->push([
                "name"             =>$data->name,
                "activity"         =>$data->activity,
                "type"             =>$data->type,
                "pid"              =>$data->pid,
                "level"            =>isset($data->level)?$data->level:"-",
                "phase"            =>isset($data->phase)?$data->phase:"-",
                "task"             =>isset($data->task)?$data->task:"-",
                "point_mandays"    =>isset($data->point_mandays)?$data->point_mandays:0,
                "status"           =>isset($data->status)?$data->status:"New",
                "date_add"         =>$data->date_add,
                "start_date"       =>$data->start_date,
                "end_date"         =>$data->end_date,
                "schedule"         =>$data->schedule,
            ]);
        }

        // foreach($getUserByGroup as $user){
        //     $collectByName->push([
        //         "name"             =>$user->name,
        //         "activity"         =>"-",
        //         "level"            =>"-",
        //         "phase"            =>"-",
        //         "task"             =>"-",
        //         "point_mandays"    =>"-",
        //         "status"           =>"-",
        //         "date_add"         =>"-",
        //         "start_date"       =>"-",
        //         "end_date"         =>"-",
        //         "schedule"         =>"-"
        //     ]);
        // }
        $indexSheet = 0;

        // $timesheetSheet = new Worksheet($spreadsheet,'Export Timesheet');
        // $spreadsheet->addSheet($timesheetSheet);
        // $spreadsheet->removeSheetByIndex(0);
        // $sheet = $spreadsheet->getActiveSheet(0);

        // $sheet->mergeCells('A1:H1');
        $spreadsheet = new Spreadsheet();
        $normalStyle = [
            'font' => [
                'name' => 'Calibri',
                'size' => 11
            ],
        ];

        $titleStyle = $normalStyle;
        $titleStyle['alignment'] = ['horizontal' => Alignment::HORIZONTAL_CENTER];
        $titleStyle['font']['bold'] = true;

        // $sheet->getStyle('A1:I1')->applyFromArray($titleStyle);
        // $sheet->setCellValue('A1','Report Timesheet');

        // $headerContent = ["No", "Activity", "Level", "Phase","Task","Duration",'Status','Date Add',"Schedule"];

        $headerStyle = $normalStyle;
        $headerStyle['font']['bold'] = true;

        if (Auth::User()->id_division == 'MSM') {
            $collectByName = $collectByName->sortBy('name');

            $spreadsheet->addSheet(new Worksheet($spreadsheet,"Timesheet MSM"));
            // $spreadsheet->addSheet(new Worksheet($spreadsheet,$key));
            $detailSheet = $spreadsheet->setActiveSheetIndex($indexSheet + 1);

            // $detailSheet->getStyle('A1:J1')->applyFromArray($titleStyle);
            // $detailSheet->setCellValue('A1','Timesheet ' . $key);
            // $detailSheet->mergeCells('A1:J1');

            $headerContent = ["No", "Name","Activity", "Type", "PID/Lead ID", "Level", "Phase","Task","Duration","Status","Date Add","Start Date","End Date","Schedule"];
            $detailSheet->getStyle('A1:N1')->applyFromArray($headerStyle);
            $detailSheet->fromArray($headerContent,NULL,'A1');

            $collectByName->map(function($item,$key) use ($detailSheet){
                // $item->date = date_format(date_create($item->date),'d-M-Y');
                $detailSheet->fromArray(array_merge([$key + 1],array_values($item)),NULL,'A' . ($key + 2));
            });

            $detailSheet->getColumnDimension('A')->setAutoSize(true);
            $detailSheet->getColumnDimension('B')->setAutoSize(true);
            $detailSheet->getColumnDimension('C')->setAutoSize(true);
            $detailSheet->getColumnDimension('D')->setAutoSize(true);
            $detailSheet->getColumnDimension('E')->setAutoSize(true);
            $detailSheet->getColumnDimension('F')->setAutoSize(true);
            $detailSheet->getColumnDimension('G')->setAutoSize(true);
            $detailSheet->getColumnDimension('H')->setAutoSize(true);
            $detailSheet->getColumnDimension('I')->setAutoSize(true);
            $detailSheet->getColumnDimension('J')->setAutoSize(true);
            $detailSheet->getColumnDimension('K')->setAutoSize(true);
            $detailSheet->getColumnDimension('L')->setAutoSize(true);

            $indexSheet = $indexSheet + 1;
        }else{
            $collectByName = $collectByName->sortBy('name')->groupby('name');

            foreach ($collectByName as $key => $item) {
                $name = substr($key,0,30);

                $spreadsheet->addSheet(new Worksheet($spreadsheet,$name));
                // $spreadsheet->addSheet(new Worksheet($spreadsheet,$key));
                $detailSheet = $spreadsheet->setActiveSheetIndex($indexSheet + 1);

                // $detailSheet->getStyle('A1:J1')->applyFromArray($titleStyle);
                // $detailSheet->setCellValue('A1','Timesheet ' . $key);
                // $detailSheet->mergeCells('A1:J1');

                $headerContent = ["No", "Name","Activity", "Type", "PID/Lead ID", "Level", "Phase","Task","Duration","Status","Date Add","Start Date","End Date","Schedule"];
                $detailSheet->getStyle('A1:N1')->applyFromArray($headerStyle);
                $detailSheet->fromArray($headerContent,NULL,'A1');

                $collectByName[$key]->map(function($item,$key) use ($detailSheet){
                    // $item->date = date_format(date_create($item->date),'d-M-Y');

                    $detailSheet->fromArray(array_merge([$key + 1],array_values($item)),NULL,'A' . ($key + 2));
                });

                $detailSheet->getColumnDimension('A')->setAutoSize(true);
                $detailSheet->getColumnDimension('B')->setAutoSize(true);
                $detailSheet->getColumnDimension('C')->setAutoSize(true);
                $detailSheet->getColumnDimension('D')->setAutoSize(true);
                $detailSheet->getColumnDimension('E')->setAutoSize(true);
                $detailSheet->getColumnDimension('F')->setAutoSize(true);
                $detailSheet->getColumnDimension('G')->setAutoSize(true);
                $detailSheet->getColumnDimension('H')->setAutoSize(true);
                $detailSheet->getColumnDimension('I')->setAutoSize(true);
                $detailSheet->getColumnDimension('J')->setAutoSize(true);
                $detailSheet->getColumnDimension('K')->setAutoSize(true);
                $detailSheet->getColumnDimension('L')->setAutoSize(true);

                $indexSheet = $indexSheet + 1;
            }
        }
        

        // return $indexSheet;
        
        
        if ($indexSheet == '0') {
            return 'Staff belum ada yang input timesheet';
        } else {

            $firstWorksheet = $spreadsheet->getSheet(0);

            // Remove the first worksheet from the workbook
            $spreadsheet->removeSheetByIndex(0);
            $spreadsheet->setActiveSheetIndex(0);

            $fileName = 'Timesheet ' . $cek_role->group . ' '. date('Y') . '.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $fileName . '"');
            header('Cache-Control: max-age=0');

            $writer = new Xlsx($spreadsheet);
            ob_end_clean();
            return $writer->save("php://output");
        }
        
    }
}