<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\TimesheetConfig;
use App\Timesheet;
use App\TimesheetLockDuration;
use App\TimesheetPermit;
use App\TimesheetPhase;
use App\TimesheetTask;
use App\TimesheetPid;
use App\SalesProject;
use App\TimesheetByDate;
use App\User;
use App\Cuti;
use App\CutiDetil;
use App\Sbe;
use App\SbeConfig;
use App\PublicHolidayAdjustment;
use App\Feelings;

use DatePeriod;
use DateInterval;
use DateTime;

use DB;
use Session;
use Auth;
use PDF;
use Carbon\Carbon;
use Log;

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

        $startDate = Carbon::parse($request->dateStart)->format('Y-m-d\TH:i:s\Z');
        $endDate = Carbon::parse($request->dateEnd)->format('Y-m-d\TH:i:s\Z');
        
        // if (isset($request->dateStart) || isset($request->dateEnd)) {
        //     $startDate = Carbon::parse($request->dateStart)->format('Y-m-d\TH:i:s\Z');
        //     $endDate = Carbon::parse($request->dateEnd)->format('Y-m-d\TH:i:s\Z');
        // }else{
        //     $currentDateTime    = Carbon::now();
        //     $formatMonthToYear   = $currentDateTime->year(date('Y'));

        //     $startDate       = $formatMonthToYear->startOfMonth()->format('Y-m-d\TH:i:s\Z');
        //     $endDate         = $formatMonthToYear->endOfMonth()->format('Y-m-d\TH:i:s\Z');
        // }

        try {
            // Create a new Guzzle HTTP client
            $client = new Client();
            // Make the API request
            // $url = "https://www.googleapis.com/calendar/v3/calendars/". $calenderId ."/events" . "?timeMin=" . $startDate  . "&timeMax=" . $endDate;
            $url = "https://www.googleapis.com/calendar/v3/calendars/". $calenderId ."/events" . "?timeMin=" . $startDate  . "&timeMax=" . $endDate;
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
                    //     'maxResults': 2500,
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

    public function deleteAllActivityByDate(Request $request)
    {
        $delete = Timesheet::where('start_date',$request->startDate)->where('nik',$request->nik)->delete();
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
        $hr = ['id' => 'hr', 'text' => 'HFM'];

        return $getListOperation->push($sol)->push($sid)->push($hr);

        // return $getListOperation;
    }

    public function deleteActivity(Request $request)
    {
        $delete = Timesheet::where('id',$request->id)->delete();
    }

    public function addTimesheet(Request $request)
    {
        $startDate = Carbon::now()->startOfMonth()->format("Y-m-d");
        $endDate = Carbon::now()->endOfMonth()->format("Y-m-d");

        $startDateInput = $request->startDate . '00:01:02';
        $endDateInput = $request->endDate . '23:59:59';

        Carbon::setTestNow();

        if ($request->isGCal == 'true') {
            $toDate = Carbon::createFromFormat('Y-m-d', $request->endDate, 'Asia/Jakarta');
            $fromDate = Carbon::createFromFormat('Y-m-d', $request->startDate, 'Asia/Jakarta');
      
            $days = $toDate->diffInDays($fromDate);
            if ($request->selectSchedule == 'Planned') {
                if ($days > 0) {
                    for ($i=0; $i <= $days; $i++) { 
                        if (isset($request->id_activity)) {
                            $addTimesheet = Timesheet::where('id',$request->id_activity)->first();
                        } else {
                            $addTimesheet = new Timesheet();
                            $addTimesheet->date_add = Carbon::now()->toDateTimeString();
                        }
                        $addTimesheet->nik = Auth::User()->nik;
                        $addTimesheet->schedule = $request->selectSchedule;
                        $startDatePlanned = Carbon::createFromFormat('Y-m-d', $request->startDate, 'Asia/Jakarta')->addDays($i);
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
                    if ($request->selectStatus == '') {
                        $addTimesheet->status = NULL;
                    } else {
                        $addTimesheet->status = $request->selectStatus;
                    }
                    $addTimesheet->duration = $request->selectDuration;
                    $addTimesheet->type = $request->selectType;
                    $getPoint = (int)$request->selectDuration/480;
                    $addTimesheet->point_mandays = number_format($getPoint, 2, '.', '');
                    // $addTimesheet->month = date("n");
                    $workdays = $this->getWorkDays($startDate,$endDate)["workdays"]->values();
                    // $addTimesheet->workdays = count($workdays);
                    $addTimesheet->save();
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
                if ($request->selectStatus == '') {
                    $addTimesheet->status = NULL;
                } else {
                    $addTimesheet->status = $request->selectStatus;
                }
                $addTimesheet->duration = $request->selectDuration;
                $addTimesheet->type = $request->selectType;
                $getPoint = (int)$request->selectDuration/480;
                $addTimesheet->point_mandays = number_format($getPoint, 2, '.', '');
                // $addTimesheet->month = date("n");
                $workdays = $this->getWorkDays($startDate,$endDate)["workdays"]->values();
                // $addTimesheet->workdays = count($workdays);
                $addTimesheet->save();
            }
        } else{
            $arrTimesheet = json_decode($request->arrTimesheet,true);
            foreach ($arrTimesheet as $value) {
                $startDateActivity = Carbon::createFromFormat('Y-m-d', $value['startDate'], 'Asia/Jakarta');
                $endDateActivity = Carbon::createFromFormat('Y-m-d', $value['endDate'], 'Asia/Jakarta');

                if (isset($value['id_activity'])) {
                    $addTimesheet = Timesheet::where('id',$value['id_activity'])->first();
                    $addTimesheet->start_date = $startDateActivity;
                    $addTimesheet->end_date = $endDateActivity;
                    $addTimesheet->nik = Auth::User()->nik;
                    $addTimesheet->schedule = $value['selectSchedule'];
                    $addTimesheet->pid = $value['selectLead'];
                    $addTimesheet->task = $value['selectTask'];
                    $addTimesheet->phase = $value['selectPhase'];
                    $addTimesheet->level = $value['selectLevel'];
                    $addTimesheet->activity = $value['textareaActivity'];
                    $addTimesheet->status = $value['selectStatus'];
                    if ($value['selectStatus'] == '') {
                        $addTimesheet->status = NULL;
                    } else {
                        $addTimesheet->status = $value['selectStatus'];
                    }
                    $addTimesheet->duration = $value['selectDuration'];
                    $addTimesheet->type = $value['selectType'];
                    $getPoint = (int)$value['selectDuration']/480;
                    $addTimesheet->point_mandays = number_format($getPoint, 2, '.', '');
                    $addTimesheet->save();
                } else {
                    $days = $endDateActivity->diffInDays($startDateActivity) + 1;

                    if ($days > 1) {
                        // Generate date range between start and end dates
                        $dateRange = $startDateActivity->range($endDateActivity)->toArray();

                        foreach ($dateRange as $date) {
                            $addTimesheet = new Timesheet();
                            $addTimesheet->date_add = Carbon::now()->toDateTimeString();
                            $addTimesheet->start_date = $date->toDateString();
                            $addTimesheet->end_date = $date->toDateString();
                            $addTimesheet->nik = Auth::User()->nik;
                            $addTimesheet->schedule = $value['selectSchedule'];
                            $addTimesheet->pid = $value['selectLead'];
                            $addTimesheet->task = $value['selectTask'];
                            $addTimesheet->phase = $value['selectPhase'];
                            $addTimesheet->level = $value['selectLevel'];
                            $addTimesheet->activity = $value['textareaActivity'];
                            $addTimesheet->status = $value['selectStatus'];
                            if ($value['selectStatus'] == '') {
                                $addTimesheet->status = NULL;
                            } else {
                                $addTimesheet->status = $value['selectStatus'];
                            }
                            $addTimesheet->duration = $value['selectDuration'];
                            $addTimesheet->type = $value['selectType'];
                            $getPoint = (int)$value['selectDuration']/480;
                            $addTimesheet->point_mandays = number_format($getPoint, 2, '.', '');
                            $addTimesheet->save();
                        }  
                    }else{
                        $addTimesheet = new Timesheet();
                        $addTimesheet->date_add = Carbon::now()->toDateTimeString();
                        $addTimesheet->start_date = $startDateActivity;
                        $addTimesheet->end_date = $endDateActivity;
                        $addTimesheet->nik = Auth::User()->nik;
                        $addTimesheet->schedule = $value['selectSchedule'];
                        $addTimesheet->pid = $value['selectLead'];
                        $addTimesheet->task = $value['selectTask'];
                        $addTimesheet->phase = $value['selectPhase'];
                        $addTimesheet->level = $value['selectLevel'];
                        $addTimesheet->activity = $value['textareaActivity'];
                        $addTimesheet->status = $value['selectStatus'];
                        if ($value['selectStatus'] == '') {
                            $addTimesheet->status = NULL;
                        } else {
                            $addTimesheet->status = $value['selectStatus'];
                        }
                        $addTimesheet->duration = $value['selectDuration'];
                        $addTimesheet->type = $value['selectType'];
                        $getPoint = (int)$value['selectDuration']/480;
                        $addTimesheet->point_mandays = number_format($getPoint, 2, '.', '');
                        $addTimesheet->save();
                    }

                }
            }
        } 
    }

    public function getActivitybyDate(Request $request)
    {
        if (isset($request->id)) {
            return $data = DB::table('tb_timesheet')->leftJoin('tb_id_project','tb_id_project.id_project','tb_timesheet.pid')->select('tb_timesheet.nik','schedule','start_date','type','pid','task','phase','level','activity','duration','tb_timesheet.status','date_add','end_date','point_mandays','tb_id_project.id_project',DB::raw("(CASE WHEN (id_project is null) THEN 'false' ELSE 'true' END) as status_pid"),'tb_timesheet.id')->where('tb_timesheet.id',$request->id)->get();
        } else {
            return $data = DB::table('tb_timesheet')->leftJoin('tb_id_project','tb_id_project.id_project','tb_timesheet.pid')->select('tb_timesheet.nik','schedule','start_date','type','pid','task','phase','level','activity','duration','tb_timesheet.status','date_add','end_date','point_mandays','tb_id_project.id_project',DB::raw("(CASE WHEN (id_project is null) THEN 'false' ELSE 'true' END) as status_pid"),'tb_timesheet.id')->where('tb_timesheet.start_date',$request->start_date)->where('tb_timesheet.nik',$request->nik)->orderby('id','asc')->get();
        }
    }

    public function uploadCSV(Request $request){
        $directory = "timesheet/";
        $nameFile = "template_upload_timesheet_csv.csv";
        $folderName = 'Test Timesheet';

        $this->uploadToLocal($request->file('csv_file'),$directory,$nameFile);

        $result = $this->readCSV($directory . "/" . $nameFile);

        if ($result == 'Format tidak sesuai' ) {
            return collect([
                "text" => 'Format tidak sesuai',
                "status" => 'Error',
            ]);
        } else if ($result == 'Tidak ada activity') {
            return collect([
                "text" => 'Tidak ada activity',
                "status" => 'Error',
            ]);
        } else {
            if(count($result) >= 1){
                $nik = Auth::User()->nik;
                $roles = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('name', 'roles.group','roles.id')->where('user_id', $nik)->first(); 

                if ($roles->group == 'presales') {
                    $rolesAlias = 'SOL';
                }else if ($roles->group == 'DPG') {
                    $rolesAlias = 'SID';
                }else if ($roles->group == 'Solutions & Partnership Management') {
                    $rolesAlias = $roles->name;
                }else{
                    $rolesAlias = $roles->group;
                }

                foreach ($result as $key => $value) {
                    $dateStartString = $value[2];
                    $dateEndString   = $value[9];

                    try {
                        // Attempt to parse the input date using the 'mm/dd/yyyy' format
                        $carbonStartDate = Carbon::createFromFormat('m/d/Y', $dateStartString);
                        $carbonEndDate = Carbon::createFromFormat('m/d/Y', $dateEndString);

                        // Check if the parsed date matches the original input date
                        if ($carbonStartDate->format('m/d/Y') == $dateStartString && $carbonEndDate->format('m/d/Y') == $dateEndString) {
                            $task = DB::table('tb_timesheet_task')->select('id')
                            ->where(DB::raw("REPLACE(task, ' ', '')"), 'LIKE', '%'.$value[10].'%')
                            ->orWhere(DB::raw("REPLACE(task, 'ing', '')"), 'LIKE', '%'.$value[10].'%')
                            ->orWhere('task','LIKE','%'.$value[10].'%')
                            ->first();

                            if (isset($task)) {
                                $task = $task->id;

                                $taskRoles = TimesheetConfig::where('roles',$roles->id)->first();
                                if (isset($taskRoles)) {
                                    if ($taskRoles->task != null) {
                                        if (!in_array((int)$task,json_decode($taskRoles->task))) {
                                            $arraytask = json_decode($taskRoles->task);
                                            $arraytask[] = (int)$task;

                                            $updatetask = TimesheetConfig::where('roles',$roles->id)->first();
                                            $updatetask->task = $arraytask;
                                            $updatetask->update();
                                        }
                                    }else{
                                        $arraytask[] = $task;

                                        $updatetask = TimesheetConfig::where('roles',$roles->id)->first();                                
                                        $updatetask->task = json_encode($arraytask,JSON_NUMERIC_CHECK);
                                        $updatetask->update();
                                    }
                                }else{
                                    $arraytask[] = $task;

                                    $addConfig           = new TimesheetConfig();
                                    $addConfig->roles    = $roles->id;
                                    $addConfig->task     = json_encode($arraytask,JSON_NUMERIC_CHECK);
                                    $addConfig->date_add = Carbon::now()->toDateTimeString();
                                    $addConfig->division = Auth::User()->id_division;
                                    $addConfig->save();
                                }
                            }else{
                                if ($value[10] == '-' || $value[10] == '') {
                                    $task = null;
                                }else{
                                    $store          = new TimesheetTask();
                                    $store->task    = $value[10];
                                    $store->save();

                                    $task = $store->id;

                                    $taskRoles = TimesheetConfig::where('roles',$roles->id)->first();
                                    if (isset($taskRoles)) {
                                        if ($taskRoles->task != null) {
                                            if (!in_array((int)$task,json_decode($taskRoles->task))) {
                                                $arraytask = json_decode($taskRoles->task);
                                                $arraytask[] = (int)$task;

                                                $updatetask = TimesheetConfig::where('roles',$roles->id)->first();
                                                $updatetask->task = $arraytask;
                                                $updatetask->update();
                                            }
                                        }else{
                                            $arraytask[] = $task;

                                            $updatetask = TimesheetConfig::where('roles',$roles->id)->first();                                
                                            $updatetask->task = json_encode($arraytask,JSON_NUMERIC_CHECK);
                                            $updatetask->update();
                                        }
                                    }else{
                                        $arraytask[] = $task;

                                        $addConfig           = new TimesheetConfig();
                                        $addConfig->roles    = $roles->id;
                                        $addConfig->task     = json_encode($arraytask,JSON_NUMERIC_CHECK);
                                        $addConfig->date_add = Carbon::now()->toDateTimeString();
                                        $addConfig->division = Auth::User()->id_division;
                                        $addConfig->save();
                                    }
                                }
                                
                            }

                            $phase = DB::table('tb_timesheet_phase')->select('id')
                                ->where(DB::raw("REPLACE(phase, ' ', '')"), 'LIKE', '%'.$value[11].'%')
                                ->orWhere(DB::raw("REPLACE(phase, 'ing', '')"), 'LIKE', '%'.$value[11].'%')
                                ->orwhere('phase','LIKE','%['.$rolesAlias.']%')
                                ->where('phase','LIKE','%'.$value[11].'%')->first();

                            if (isset($phase)) {
                                $phase = $phase->id;

                                $phaseRoles = TimesheetConfig::where('roles',$roles->id)->first();
                                if (isset($phaseRoles)) {
                                    if ($phaseRoles->phase != null) {
                                        if (!in_array((int)$phase,json_decode($phaseRoles->phase))) {
                                            $arrayphase = json_decode($phaseRoles->phase);
                                            $arrayphase[] = (int)$phase;

                                            $updatephase = TimesheetConfig::where('roles',$roles->id)->first();
                                            $updatephase->phase = $arrayphase;
                                            $updatephase->update();
                                        }
                                    }else{
                                        $arrayphase[] = $phase;

                                        $updatephase = TimesheetConfig::where('roles',$roles->id)->first();                                
                                        $updatephase->phase = json_encode($arrayphase,JSON_NUMERIC_CHECK);
                                        $updatephase->update();
                                    }
                                }else{
                                    $arrayphase[] = $phase;

                                    $addConfig           = new TimesheetConfig();
                                    $addConfig->roles    = $roles->id;
                                    $addConfig->phase    = json_encode($arrayphase,JSON_NUMERIC_CHECK);
                                    $addConfig->date_add = Carbon::now()->toDateTimeString();
                                    $addConfig->division = Auth::User()->id_division;
                                    $addConfig->save();
                                }
                            }else{
                                if ($value[11] == '-' || $value[11] == '') {
                                    $phase = null;
                                }else{
                                    $store        = new TimesheetPhase();
                                    $store->phase = "[". strtoupper($rolesAlias) ."] ". $value[11];
                                    $store->save();

                                    $phase = $store->id;

                                    $phaseRoles = TimesheetConfig::where('roles',$roles->id)->first();
                                    if (isset($phaseRoles)) {
                                        if ($phaseRoles->phase != null) {
                                            if (!in_array((int)$phase, json_decode($phaseRoles->phase))) {
                                                $arrayphase = json_decode($phaseRoles->phase);
                                                $arrayphase[] = (int)$phase;

                                                $updatephase = TimesheetConfig::where('roles',$roles->id)->first();
                                                $updatephase->phase = $arrayphase;
                                                $updatephase->update();
                                            }
                                        }else{
                                            $arrayphase[] = $phase;

                                            $updatephase = TimesheetConfig::where('roles',$roles->id)->first();
                                            $updatephase->phase = json_encode($arrayphase,JSON_NUMERIC_CHECK);
                                            $updatephase->update();
                                        }
                                    }else{
                                        $arrayphase[] = $phase;

                                        $addConfig           = new TimesheetConfig();
                                        $addConfig->roles    = $roles->id;
                                        $addConfig->phase    = json_encode($arrayphase,JSON_NUMERIC_CHECK);
                                        $addConfig->date_add = Carbon::now()->toDateTimeString();
                                        $addConfig->division = Auth::User()->id_division;
                                        $addConfig->save();
                                    }
                                }
                            }

                            $startDate = strtotime($value[2]);
                            $startformatDate =  Carbon::createFromTimestamp($startDate)->format('Y-m-d');

                            $endDate = strtotime($value[9]);
                            $endformatDate =  Carbon::createFromTimestamp($endDate)->format('Y-m-d');

                            // $start_date = Carbon::createFromFormat('d/m/Y', $value[2])->format('Y-m-d');
                            // $end_date = Carbon::createFromFormat('d/m/Y', $value[9])->format('Y-m-d');
                            $getPoint = (int)$value['7']/480;
                            $point_mandays = number_format($getPoint, 2, '.', '');
                            $insertTimesheet[] = [
                                'nik' => Auth::User()->nik, 
                                'schedule' => "Unplanned", 
                                'start_date' => $startformatDate, 
                                'type' => $value[3], 
                                'pid' => $value[4], 
                                'level' => $value[5], 
                                'activity' => mb_convert_encoding($value[6], "UTF-8", "ISO-8859-1"), 
                                'duration' => $value[7], 
                                'status' => $value[8], 
                                'task' => $task, 
                                'phase' => $phase, 
                                'end_date' => $endformatDate, 
                                'point_mandays' => $point_mandays, 
                                'date_add' => Carbon::now()->toDateTimeString()
                            ];
                            // var_dump($endformatDate);
                        } else {
                            return collect([
                                "text" => 'Format tanggal tidak sesuai, Inputkan format "MM/DD/YYYY" e.g. "10/31/2023"',
                                "status" => 'Error',
                            ]);
                        }
                    } catch (\Exception $e) {
                        return collect([
                            "text" => 'Format tanggal tidak sesuai, Inputkan format "MM/DD/YYYY" e.g. "10/31/2023"',
                            "status" => 'Error',
                        ]);
                    }
                }

                if(!empty($insertTimesheet)){
                    Timesheet::insert($insertTimesheet);
                    return collect([
                        "text" => 'Successfully',
                        "status" => 'Success',
                    ]);
                }
            } else {
                return 'Tidak ada activity';
            }
        }
        

        return $result;
    }

    // public function checkSimilarTask($stringCompare)
    // {
    //     $checkTask = Task::get();

    //     foreach($checkTask as $data){
    //         if ($this->similar_strings($data->task, $stringCompare)) {
    //             return true;
    //             break;
    //         } else {
    //             return false;
    //         }
    //     }   
    // }

    // public function similar_strings($str1, $str2, $threshold = 10) {
    //     // Calculate the Levenshtein distance between the two strings
    //     $distance = levenshtein($str1, $str2);
        
    //     // If the distance is less than or equal to the threshold, consider them similar
    //     if ($distance <= $threshold) {
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }

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
            "end_date",
            "task",
            "phase"
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
        //  $lock = TimesheetLockDuration::where('division',Auth::User()->id_division)->first();
        // } else {
        //  $lock = new TimesheetLockDuration();
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
        if ($request->nik == "") {
            $getAllPid = SalesProject::join('sales_lead_register', 'sales_lead_register.lead_id', '=', 'tb_id_project.lead_id')->join('users', 'users.nik', '=', 'sales_lead_register.nik')->select('id_project as id',DB::raw("CONCAT(`id_project`,' - ',`name_project`) AS text"))->where('id_company', '1')->orderby('tb_id_project.id_pro','desc')->get();
        }else{
            $cekPid = TimesheetPid::select('pid')->where('nik',$request->nik)->distinct()->pluck('pid');

            // return $cekPid;

            $getAllPid = SalesProject::join('sales_lead_register', 'sales_lead_register.lead_id', '=', 'tb_id_project.lead_id')->join('users', 'users.nik', '=', 'sales_lead_register.nik')->select('id_project as id',DB::raw("CONCAT(`id_project`,' - ',`name_project`) AS text"))->where('id_company', '1');
            
            if ($cekPid->pluck('pid')->isNotEmpty()) {
                $getAllPid = $getAllPid->whereNotIn('id_project',$cekPid)->get();
            } else {
                // Handle the case where $excludedIds is empty
                $getAllPid = $getAllPid->get(); // Or any other appropriate action
            }
          
        }
        

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
                if ($cek_role->name == 'Delivery Project Coordinator' || $cek_role->name == 'Delivery Project Manager') {
                    $getPidByPic = DB::table('tb_pmo')->join('tb_pmo_assign','tb_pmo_assign.id_project','tb_pmo.id')->join('tb_id_project','tb_id_project.id_project','tb_pmo.project_id')
                    ->join('sales_lead_register','sales_lead_register.lead_id','tb_id_project.lead_id')
                    ->select('tb_pmo.project_id as id',DB::raw("CONCAT(`tb_pmo`.`project_id`,' - ',`opp_name`) AS text"))
                    ->where('tb_pmo_assign.nik',Auth::User()->nik)
                    ->orderby('id','desc')
                    ->get();
                } else {
                    $getPidByPic = DB::table('tb_timesheet_pid')
                        ->join('tb_id_project','tb_timesheet_pid.pid','tb_id_project.id_project')
                        ->join('sales_lead_register','sales_lead_register.lead_id','tb_id_project.lead_id')
                        ->groupBy('tb_timesheet_pid.pid','opp_name')
                        ->select('tb_timesheet_pid.pid as id',DB::raw("CONCAT(`tb_timesheet_pid`.`pid`,' - ',`opp_name`) AS text"))
                        ->where('tb_timesheet_pid.nik',Auth::User()->nik)
                        ->orderby('id','desc')
                        ->get();
                }

                return $getPidByPic;
            }
        }else{

            $getPidByPic = DB::table('tb_timesheet_pid')->join('tb_id_project','tb_timesheet_pid.pid','tb_id_project.id_project')->join('sales_lead_register','sales_lead_register.lead_id','tb_id_project.lead_id')->select('tb_timesheet_pid.pid as id',DB::raw("CONCAT(`tb_timesheet_pid`.`pid`,' - ',`name`) AS text"))->where('tb_timesheet_pid.nik',Auth::User()->nik)->orderby('id','desc')->get();

            if (count($getPidByPic) == 0) {
                $getPidByPic = ['Alert','Please Ask your Manager/Spv to assign pid!'];
                return $getPidByPic;
            }else{
                return $getPidByPic;
            }
        }
        // if ($cek_role->group == 'hr' || $cek_role->group == 'Program & Project Management') {
            
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

        if ($cek_role->group == 'Solutions & Partnership Management') {
            if ($cek_role->name == 'VP Solutions & Partnership Management') {
                $getGroupRoles = DB::table('role_user')->join('roles','roles.id','role_user.role_id')->select('group')->where('user_id',Auth::User()->nik)->first()->group;

                $data = DB::table('tb_timesheet_config')->join('roles','roles.id','tb_timesheet_config.roles')->join('tb_timesheet_task', function ($join) {
                    $join->on(DB::raw('JSON_CONTAINS(tb_timesheet_config.task, CAST(tb_timesheet_task.id AS JSON), "$")'), '=', DB::raw('1'));
                })
                ->select('tb_timesheet_task.id as id', 'tb_timesheet_task.task as text')->where('group',$getGroupRoles)->distinct()->get();
            }else if ($cek_role->name == 'Application Development Specialist Manager' || $cek_role->name == 'Application Development Specialist') {
                $data = DB::table('tb_timesheet_config')->join('roles','roles.id','tb_timesheet_config.roles')->join('tb_timesheet_task', function ($join) {
                    $join->on(DB::raw('JSON_CONTAINS(tb_timesheet_config.task, CAST(tb_timesheet_task.id AS JSON), "$")'), '=', DB::raw('1'));
                })
                ->select('tb_timesheet_task.id as id', 'tb_timesheet_task.task as text')->where('name','Application Development Specialist')->distinct()->get();
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
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('name', 'roles.group','mini_group')->where('user_id', $nik)->first(); 

        if ($cek_role->mini_group == 'Application Development Specialist') {
            if ($cek_role->name == 'VP Solutions & Partnership Management') {
                $getGroupRoles = DB::table('role_user')->join('roles','roles.id','role_user.role_id')->select('group')->where('user_id',Auth::User()->nik)->first()->group;

                $data = DB::table('tb_timesheet_config')->join('roles','roles.id','tb_timesheet_config.roles')->join('tb_timesheet_phase',function ($join) {
                        $join->on('tb_timesheet_config.phase', 'LIKE', DB::raw("CONCAT('%', tb_timesheet_phase.id, '%')"));
                    })
                ->select('tb_timesheet_phase.id as id', 'tb_timesheet_phase.phase as text')->where('group',$getGroupRoles)->distinct()->get();
            }else if ($cek_role->name == 'Application Development Specialist Manager' || $cek_role->name == 'Application Development Specialist') {
                $data = DB::table('tb_timesheet_config')->join('roles','roles.id','tb_timesheet_config.roles')->join('tb_timesheet_phase',function ($join) {
                    $join->on('tb_timesheet_config.phase', 'LIKE', DB::raw("CONCAT('%', tb_timesheet_phase.id, '%')"));
                })
                ->select('tb_timesheet_phase.id as id', 'tb_timesheet_phase.phase as text')->where('name','Application Development Specialist')->get();
            } else {
                $getGroupRoles = DB::table('role_user')->join('roles','roles.id','role_user.role_id')->select('name')->where('user_id',Auth::User()->nik)->first()->name;

                $data = DB::table('tb_timesheet_config')->join('roles','roles.id','tb_timesheet_config.roles')->join('tb_timesheet_phase',function ($join) {
                    $join->on('tb_timesheet_config.phase', 'LIKE', DB::raw("CONCAT('%', tb_timesheet_phase.id, '%')"));
                })
                ->select('tb_timesheet_phase.id as id', 'tb_timesheet_phase.phase as text')->where('name',$getGroupRoles)->distinct()->get();
            }
            
        } else {
            $getGroupRoles = DB::table('role_user')->join('roles','roles.id','role_user.role_id')->select('group')->where('user_id',Auth::User()->nik)->first()->group;

            $data = DB::table('tb_timesheet_config')->join('roles','roles.id','tb_timesheet_config.roles')->join('tb_timesheet_phase',function ($join) {
                    $join->on('tb_timesheet_config.phase', 'LIKE', DB::raw("CONCAT('%', tb_timesheet_phase.id, '%')"));
                })
            ->select('tb_timesheet_phase.id as id', 'tb_timesheet_phase.phase as text')->where('group',$getGroupRoles)->distinct()->get();
        }

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
        } else if($getGroupRoles == 'Program & Project Management'){
            $getRoles = DB::table('roles')->selectRaw('`id` AS `id`,CONCAT("Program & Project Management") AS `text`')->where("name","not like","%SPV%")->where("name","not like","%Manager%")->where("name","not like","%Director%")->where("name","not like","%MSP%")->where("name","not like","%Admin%")->where('group',$getGroupRoles)->distinct()->get()->take(1);
        } else if($getGroupRoles == 'DPG'){
            $getRoles = DB::table('roles')->selectRaw('`id` AS `id`,CONCAT("SID") AS `text`')->where("name","not like","%SPV%")->where("name","not like","%Manager%")->where("name","not like","%Director%")->where("name","not like","%MSP%")->where("name","not like","%Admin%")->where('group',$getGroupRoles)->distinct()->get()->take(1);
        } else if($getGroupRoles == 'Synergy System Management'){
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
        $startDate = Carbon::parse($request->dateStart)->format('Y-m-d');
        $endDate = Carbon::parse($request->dateEnd)->format('Y-m-d');

        // if (isset($request->date)) {
        //     $startDate = Carbon::parse($request->date)->startOfMonth()->format('Y-m-d');
        //     $endDate = Carbon::parse($request->date)->endOfMonth()->format('Y-m-d');
        // }else{
        //     $currentDateTime    = Carbon::now();
        //     $formatMonthToYear   = $currentDateTime->year('2024');

        //     $startDate       = $formatMonthToYear->startOfMonth()->format('Y-m-d');
        //     $endDate         = $formatMonthToYear->endOfMonth()->format('Y-m-d');
        // }
        
        $hidden = ['planned','threshold'];

        $plannedDone = DB::table('tb_timesheet')
                   ->select('id','nik',DB::raw("(CASE WHEN (point_mandays is null) THEN 0 WHEN (point_mandays = '') THEN 0 ELSE point_mandays END) as planned"))
                   ->where('schedule', 'Planned')
                   ->where('status', 'Done');

        $unPlannedDone = DB::table('tb_timesheet')
                   ->select('id','nik',DB::raw("(CASE WHEN (point_mandays is null) THEN 0 WHEN (point_mandays = '') THEN 0 ELSE point_mandays END) as unplanned"))
                   ->where('schedule', 'Unplanned')
                   ->where('status', 'Done');

        $emoji = DB::table('tb_feelings')
                   ->select('code_feeling','date_add')->where('nik',$request->nik)->get();

        $data = DB::table('tb_timesheet')->leftJoin('tb_id_project','tb_id_project.id_project','tb_timesheet.pid')
            ->LeftjoinSub($unPlannedDone, 'unplanned_done', function ($join) {
                $join->on('tb_timesheet.id', '=', 'unplanned_done.id');
            })
            ->LeftjoinSub($plannedDone, 'planned_done', function ($join) {
                $join->on('tb_timesheet.id', '=', 'planned_done.id');
            })->select('tb_timesheet.nik','schedule','start_date','type','pid','task','phase','level','activity','duration','tb_timesheet.status','date_add','end_date','tb_id_project.id_project',DB::raw("(CASE WHEN (id_project is null) THEN 'false' ELSE 'true' END) as status_pid"),'tb_timesheet.id',DB::raw("(CASE WHEN (planned is null) THEN 0 ELSE planned END) as planned"),DB::raw("(CASE WHEN (unplanned is null) THEN 0 ELSE unplanned END) as unplanned"))
            ->where('tb_timesheet.nik',$request->nik)
            ->whereBetween('tb_timesheet.start_date', [$startDate, $endDate])
            ->orderby('id','asc');

        // if (isset($request->phase[1])) {
        //     $data = $data->whereIn('phase',$request->phase);
        // }else{
        //     $data = $data;
        // }

        if (isset($request->status)) {
            $data = $data->whereIn('tb_timesheet.status',$request->status);
        }else{
            $data = $data;
        }

        $getLock = TimesheetLockDuration::where('division',Auth::User()->id_division)->first();

        $getLeavingPermit = Cuti::join('tb_cuti_detail','tb_cuti_detail.id_cuti','tb_cuti.id_cuti')->select('date_off as start_date',DB::raw('CASE WHEN reason_leave IS NULL THEN "-" ELSE reason_leave END AS activity'))->where('nik',$request->nik)->where('tb_cuti.status','v')->whereBetween('date_off', [$startDate, $endDate])->orderby('start_date','desc')->get();

        $holiday = $this->getWorkDays($startDate,$endDate)["holiday"]->values();

        $getPermit = TimesheetPermit::select('start_date','end_date','status as remarks','activity','id')->where('nik',$request->nik)->get();

        $array = array_merge($data->get()->toArray(),$getLeavingPermit->toArray(),$holiday->toArray(),$getPermit->toArray());

        return collect(["data"=>$array,
            "lock_duration"=>empty($getLock->lock_duration)?(empty(DB::table('tb_timesheet_lock_duration')->where('division',Auth::User()->id_division)->first()->lock_duration) ? "1" : DB::table('tb_timesheet_lock_duration')->where('division',Auth::User()->id_division)->first()->lock_duration):$getLock->lock_duration,"emoji"=>$emoji]);
    }

    public function getWorkDays($startDate,$endDate){
        $formattedStartDate = Carbon::parse($startDate)->toISOString();
        $formattedEndDate   = Carbon::parse($endDate)->toISOString();

        $client = new Client();
        $api_response = $client->get('https://www.googleapis.com/calendar/v3/calendars/en.indonesian%23holiday%40group.v.calendar.google.com/events?timeMin='. $formattedStartDate .'&timeMax='. $formattedEndDate .'&key='.env('GCALENDAR_API_KEY'));
        // $api_response = $client->get('https://aws-cron.sifoma.id/holiday.php?key=AIzaSyBNVCp8lA_LCRxr1rCYhvFIUNSmDsbcGno');
        $json = (string)$api_response->getBody();
        $holiday_indonesia = json_decode($json, true);

        // return $holiday_indonesia;

        $holiday_indonesia_final_detail = collect();
        $holiday_indonesia_final_details = collect();
        $holiday_indonesia_final_date = collect();
        $holiday_indonesia_final_dates = collect();
        // return $holiday_indonesia;
        
        foreach ($holiday_indonesia["items"] as $value) {
            if(( (( $value["start"]["date"] >= $startDate ) && ( $value["start"]["date"] <= $endDate )) && (($value["description"] == 'Public holiday')) && (!strstr($value['summary'], "Joint")  && ($value["summary"] != 'Boxing Day')) )){
                $holiday_indonesia_final_detail->push(["start_date" => $value["start"]["date"],"activity" => $value["summary"],"remarks" => "Cuti Bersama"]);
                $holiday_indonesia_final_date->push($value["start"]["date"]);
            }
            if(( (( $value["start"]["date"] >= $startDate ) && ( $value["start"]["date"] <= $endDate )) && (($value['summary'] == 'Idul Fitri Joint Holiday') || ($value['summary'] == 'Boxing Day')) )){
                $holiday_indonesia_final_details->push(["start_date" => $value["start"]["date"],"activity" => $value["summary"],"remarks" => "Cuti Bersama"]);
                $holiday_indonesia_final_dates->push($value["start"]["date"]);
            }
        }

        $holiday_indonesia_final_detail = $holiday_indonesia_final_detail->merge($holiday_indonesia_final_details);

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


        if (isset($request->roles)) {
            if ($request->roles == 'DPG') {
                //     $data = TimesheetPid::join('users','users.nik','tb_timesheet_pid.nik')
                //     ->select(
                //         'users.name',
                //         'pid','role','tb_timesheet_pid.id'
                //     )->where('id_division','TECHNICAL');

                $data = TimesheetPid::join('users','users.nik','tb_timesheet_pid.nik')
                ->select('users.name',
                    DB::raw("max(tb_timesheet_pid.id) as id"),
                    DB::raw("max(tb_timesheet_pid.pid) as pid"),
                    DB::raw("max(role) as role")
                )->where('id_division','TECHNICAL')
                ->groupBy('tb_timesheet_pid.nik','tb_timesheet_pid.pid')
                ->get();

                // $get_id_max = DB::table($data, 'temp')->groupBy('pid')->selectRaw('MAX(`temp`.`id`) as `id`');
                // $getAll = DB::table($get_id_max, 'temp2')->join('tb_timesheet_pid', 'tb_timesheet_pid.id', '=', 'temp2.id')->join('users','users.nik','tb_timesheet_pid.nik')->select('name', 'pid', 'temp2.id','role')->get();

                // $data = TimesheetPid::join('users','users.nik','tb_timesheet_pid.nik')->select('users.name','tb_timesheet_pid.pid','role')->where('id_division','TECHNICAL')->get();
            }else if ($request->roles == 'Synergy System Management') {
                // $data = TimesheetPid::join('users','users.nik','tb_timesheet_pid.nik')
                // ->select(
                //     'users.name',
                //     'pid','role','tb_timesheet_pid.id'
                // )->where('id_division','TECHNICAL PRESALES');

                $data = TimesheetPid::join('users','users.nik','tb_timesheet_pid.nik')
                ->select('users.name',
                    DB::raw("max(tb_timesheet_pid.id) as id"),
                    DB::raw("max(tb_timesheet_pid.pid) as pid"),
                    DB::raw("max(role) as role")
                )->where('id_division','TECHNICAL PRESALES')
                ->groupBy('tb_timesheet_pid.nik','tb_timesheet_pid.pid')
                ->get();

                // $get_id_max = DB::table($data, 'temp')->groupBy('pid')->selectRaw('MAX(`temp`.`id`) as `id`');
                // $getAll = DB::table($get_id_max, 'temp2')->join('tb_timesheet_pid', 'tb_timesheet_pid.id', '=', 'temp2.id')->join('users','users.nik','tb_timesheet_pid.nik')->select('name', 'pid', 'temp2.id','role')->get();

                // $data = TimesheetPid::join('users','users.nik','tb_timesheet_pid.nik')->select('users.name','tb_timesheet_pid.pid','role')->where('id_division','TECHNICAL PRESALES')->get();
            }else {
                $data = TimesheetPid::join('users','users.nik','tb_timesheet_pid.nik')
                ->select('users.name',
                    DB::raw("max(tb_timesheet_pid.id) as id"),
                    DB::raw("max(tb_timesheet_pid.pid) as pid"),
                    DB::raw("max(role) as role")
                )->where('id_division',$request->roles)
                ->groupBy('tb_timesheet_pid.nik','tb_timesheet_pid.pid')
                ->get();
                // $data = TimesheetPid::join('users','users.nik','tb_timesheet_pid.nik')
                // ->select(
                //     'users.name','tb_timesheet_pid.id','role','pid'
                // )->where('id_division',$request->roles);

                // $get_id_max = DB::table($data, 'temp')->groupBy('pid')->selectRaw('MAX(`temp`.`id`) as `id`');
                // $getAll = DB::table($get_id_max, 'temp2')->join('tb_timesheet_pid', 'tb_timesheet_pid.id', '=', 'temp2.id')->join('users','users.nik','tb_timesheet_pid.nik')->select('name', 'pid', 'temp2.id','role')->get();

                // $data = TimesheetPid::join('users','users.nik','tb_timesheet_pid.nik')->select('users.name','tb_timesheet_pid.pid','role')->where('id_division',$request->roles)->get();
            }
        }else{
            if ($cek_role->name == 'Chief Operating Officer') {
                $data = [];
            }else{
                $data = TimesheetPid::join('users','users.nik','tb_timesheet_pid.nik')
                ->select('users.name',
                    DB::raw("max(tb_timesheet_pid.id) as id"),
                    DB::raw("max(tb_timesheet_pid.pid) as pid"),
                    DB::raw("max(role) as role")
                )->where('id_division',Auth::User()->id_division)
                ->groupBy('tb_timesheet_pid.nik','tb_timesheet_pid.pid')
                ->get();
            }

            // if ($cek_role->name == 'VP Solutions & Partnership Management' || $cek_role->name == 'Application Development Specialist Manager') {
            //     $data = TimesheetPid::join('users','users.nik','tb_timesheet_pid.nik')
            //     ->select('users.name',
            //         DB::raw("max(tb_timesheet_pid.id) as id"),
            //         DB::raw("max(tb_timesheet_pid.pid) as pid"),
            //         DB::raw("max(role) as role")
            //     )->where('id_division',Auth::User()->id_division)
            //     ->groupBy('tb_timesheet_pid.nik','tb_timesheet_pid.pid')
            //     ->get();

            //     // $get_id_max = DB::table($data, 'temp')->groupBy('pid')->selectRaw('MAX(`temp`.`id`) as `id`');
            //     // $getAll = DB::table($get_id_max, 'temp2')->join('tb_timesheet_pid', 'tb_timesheet_pid.id', '=', 'temp2.id')->join('users','users.nik','tb_timesheet_pid.nik')->select('name', 'pid', 'temp2.id','role')->get();
            // } elseif ($cek_role->name == 'SOL Manager') {
            //     $data = TimesheetPid::join('users','users.nik','tb_timesheet_pid.nik')
            //     ->select('users.name',
            //         DB::raw("max(tb_timesheet_pid.id) as id"),
            //         DB::raw("max(tb_timesheet_pid.pid) as pid"),
            //         DB::raw("max(role) as role")
            //     )->where('id_division',Auth::User()->id_division)
            //     ->groupBy('tb_timesheet_pid.nik','tb_timesheet_pid.pid')
            //     ->get();

            //     // $get_id_max = DB::table($data, 'temp')->groupBy('pid')->selectRaw('MAX(`temp`.`id`) as `id`');
            //     // $getAll = DB::table($get_id_max, 'temp2')->join('tb_timesheet_pid', 'tb_timesheet_pid.id', '=', 'temp2.id')->join('users','users.nik','tb_timesheet_pid.nik')->select('name', 'pid', 'temp2.id','role')->get();
            // } elseif ($cek_role->name == 'SID Manager' || $cek_role->name == 'SID SPV') {
            //     $data = TimesheetPid::join('users','users.nik','tb_timesheet_pid.nik')
            //     ->select('users.name',
            //         DB::raw("max(tb_timesheet_pid.id) as id"),
            //         DB::raw("max(tb_timesheet_pid.pid) as pid"),
            //         DB::raw("max(role) as role")
            //     )->where('id_division',Auth::User()->id_division)
            //     ->groupBy('tb_timesheet_pid.nik','tb_timesheet_pid.pid')
            //     ->get();

            //     // $get_id_max = DB::table($data, 'temp')->groupBy('pid')->selectRaw('MAX(`temp`.`id`) as `id`');
            //     // $getAll = DB::table($get_id_max, 'temp2')->join('tb_timesheet_pid', 'tb_timesheet_pid.id', '=', 'temp2.id')->join('users','users.nik','tb_timesheet_pid.nik')->select('name', 'pid', 'temp2.id','role')->get();
            // } elseif ($cek_role->name == 'MSM Manager' || $cek_role->name == 'MSM TS SPV') {
            //     // $data = TimesheetPid::join('users','users.nik','tb_timesheet_pid.nik')
            //     // ->select(
            //     //     'users.name',
            //     //     'role',
            //     //     'pid',
            //     //     'tb_timesheet_pid.id'
            //     // )->where('id_division',Auth::User()->id_division);

            //     // $getPidByNik = TimesheetPid::join('users','users.nik','tb_timesheet_pid.nik')->select('pid')->where('tb_timesheet_pid.nik',Auth::User()->nik)->get();
            //     // $getPid = TimesheetPid::whereIn('pid',$getPidByNik)->get();
            //     $data = TimesheetPid::join('users','users.nik','tb_timesheet_pid.nik')
            //     ->select('users.name',
            //         DB::raw("max(tb_timesheet_pid.id) as id"),
            //         DB::raw("max(tb_timesheet_pid.pid) as pid"),
            //         DB::raw("max(role) as role")
            //     )->where('id_division',Auth::User()->id_division)
            //     ->groupBy('tb_timesheet_pid.nik','tb_timesheet_pid.pid')
            //     ->get();

            //     // $get_id_max = DB::table($data, 'temp')->groupBy('pid')->selectRaw('MAX(`temp`.`id`) as `id`');
            //     // $getAll = DB::table($get_id_max, 'temp2')->join('tb_timesheet_pid', 'tb_timesheet_pid.id', '=', 'temp2.id')->join('users','users.nik','tb_timesheet_pid.nik')->select('name', 'pid', 'temp2.id','role')->get();
            // } elseif ($cek_role->name == 'PMO SPV' || $cek_role->name == 'PMO Manager') {
            //     $data = TimesheetPid::join('users','users.nik','tb_timesheet_pid.nik')
            //     ->select('users.name',
            //         DB::raw("max(tb_timesheet_pid.id) as id"),
            //         DB::raw("max(tb_timesheet_pid.pid) as pid"),
            //         DB::raw("max(role) as role")
            //     )->where('id_division',Auth::User()->id_division)
            //     ->groupBy('tb_timesheet_pid.nik','tb_timesheet_pid.pid')
            //     ->get();

            //     // $get_id_max = DB::table($data, 'temp')->groupBy('pid')->selectRaw('MAX(`temp`.`id`) as `id`');
            //     // $getAll = DB::table($get_id_max, 'temp2')->join('tb_timesheet_pid', 'tb_timesheet_pid.id', '=', 'temp2.id')->join('users','users.nik','tb_timesheet_pid.nik')->select('name', 'pid', 'temp2.id','role')->get();
            // } elseif ($cek_role->name == 'HR Manager') {
            //     $data = TimesheetPid::join('users','users.nik','tb_timesheet_pid.nik')
            //     ->select('users.name',
            //         DB::raw("max(tb_timesheet_pid.id) as id"),
            //         DB::raw("max(tb_timesheet_pid.pid) as pid"),
            //         DB::raw("max(role) as role")
            //     )->where('id_division',Auth::User()->id_division)
            //     ->groupBy('tb_timesheet_pid.nik','tb_timesheet_pid.pid')
            //     ->get();
            //     // $get_id_max = DB::table($data, 'temp')->groupBy('pid')->selectRaw('MAX(`temp`.`id`) as `id`');
            //     // $getAll = DB::table($get_id_max, 'temp2')->join('tb_timesheet_pid', 'tb_timesheet_pid.id', '=', 'temp2.id')->join('users','users.nik','tb_timesheet_pid.nik')->select('name', 'pid', 'temp2.id','role')->get();
            // } else {
            //     // $getPidByNik = TimesheetPid::join('users','users.nik','tb_timesheet_pid.nik')->select('pid')->where('tb_timesheet_pid.nik',Auth::User()->nik)->get();
            //     // $getPid = TimesheetPid::whereIn('pid',$getPidByNik)->get();
            //     // $data = TimesheetPid::join('users','users.nik','tb_timesheet_pid.nik')->select('users.name',
            //     //     'role',
            //     //     'pid',
            //     //     'tb_timesheet_pid.id'
            //     // )->whereIn('pid',$getPidByNik);

            //     $data = TimesheetPid::join('users','users.nik','tb_timesheet_pid.nik')
            //         ->select('users.name',
            //         DB::raw("max(tb_timesheet_pid.id) as id"),
            //         DB::raw("max(tb_timesheet_pid.pid) as pid"),
            //         DB::raw("max(role) as role")
            //     )
            //     ->groupBy('tb_timesheet_pid.nik','tb_timesheet_pid.pid')
            //     ->get();

            //     // $get_id_max = DB::table($data, 'temp')->groupBy('pid')->selectRaw('MAX(`temp`.`id`) as `id`');
            //     // $getAll = DB::table($get_id_max, 'temp2')->join('tb_timesheet_pid', 'tb_timesheet_pid.id', '=', 'temp2.id')->join('users','users.nik','tb_timesheet_pid.nik')->select('name', 'pid', 'temp2.id','role')->get();
            // }
        }
        
        return array("data"=>$data);
    }

    public function getTaskPhaseByDivisionForTable(Request $request)
    {
        $nik = Auth::User()->nik;
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('name', 'roles.group')->where('user_id', $nik)->first(); 

        $getGroupRoles = DB::table('role_user')->join('roles','roles.id','role_user.role_id')->select('group')->where('user_id',Auth::User()->nik)->first()->group;


        if ($cek_role->group == 'Solutions & Partnership Management') {
            if ($cek_role->name == 'Application Development Specialist Manager' || $cek_role->name == 'Application Development Specialist') {
                // $getGroupRoles = DB::table('role_user')->join('roles','roles.id','role_user.role_id')->select('name')->where('user_id',Auth::User()->nik)->first()->name;

                $data = DB::table('tb_timesheet_config')->join('roles','roles.id','tb_timesheet_config.roles')->join('tb_timesheet_task', function ($join) {
                    $join->on(DB::raw('JSON_CONTAINS(tb_timesheet_config.task, CAST(tb_timesheet_task.id AS JSON), "$")'), '=', DB::raw('1'));
                })
                ->select('tb_timesheet_task.id', 'tb_timesheet_task.task as title','tb_timesheet_task.description')->where('name','Application Development Specialist')->distinct()->get();
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


        if ($cek_role->group == 'Solutions & Partnership Management') {
            if ($cek_role->name == 'Application Development Specialist Manager' || $cek_role->name == 'Application Development Specialist') {
                // $getGroupRoles = DB::table('role_user')->join('roles','roles.id','role_user.role_id')->select('name')->where('user_id',Auth::User()->nik)->first()->name;

                // $dataPhase = DB::table('tb_timesheet_config')->join('roles','roles.id','tb_timesheet_config.roles')->join('tb_timesheet_phase', function ($join) {
                //     $join->on(DB::raw('JSON_CONTAINS(tb_timesheet_config.task, CAST(tb_timesheet_phase.id AS JSON), "$")'), '=', DB::raw('1'));
                // })
                $dataPhase = DB::table('tb_timesheet_config')->join('roles','roles.id','tb_timesheet_config.roles')->join('tb_timesheet_phase',function ($join) {
                    $join->on('tb_timesheet_config.phase', 'LIKE', DB::raw("CONCAT('%', tb_timesheet_phase.id, '%')"));
                })
                ->select('tb_timesheet_phase.id', 'tb_timesheet_phase.phase as title','tb_timesheet_phase.description')->where('name','Application Development Specialist')->get();
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
  //    $getGroupRoles = DB::table('role_user')->join('roles','roles.id','role_user.role_id')->select('group')->where('user_id',Auth::User()->nik)->first()->group;

  //    // $getAll = TimesheetConfig::join('roles','roles.id','tb_timesheet_config.roles')->select('task')->where('group',$getGroupRoles)->get();

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

        $getPublicHolidayAdjustment = PublicHolidayAdjustment::select('date')->whereYear('date',date('Y'))->count();

        if ($cek_role->group == 'Program & Project Management') {
            if ($cek_role->name == 'VP Program & Project Management') {
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Program & Project Management')->orWhere('roles.group','Human Capital Management')->pluck('nik');

                $getLeavingPermit   = $getLeavingPermit->whereIn('tb_cuti.nik',$listGroup)->get();
                $getPermit          = $getPermit->whereIn('tb_timesheet_permit.nik',$listGroup)->get();
                $sumMandays         = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->select('point_mandays','users.name','tb_timesheet.nik','task')->selectRaw('MONTH(start_date) AS month_number')->whereIn('tb_timesheet.nik',$listGroup)->where('status_karyawan','!=','dummy')->where('status_karyawan','!=','dummy')->where('status','Done')->whereYear('start_date',date('Y'))->get();

                $getUserByGroup     = User::join('role_user', 'role_user.user_id', '=', 'users.nik')
                                        ->join('roles', 'roles.id', '=', 'role_user.role_id')
                                        ->select('users.name','users.nik')
                                        ->where('roles.group','Program & Project Management')
                                        ->where('roles.name','not like','%Manager')
                                        ->where('users.status_delete','-')
                                        ->whereNotIn('nik', $sumMandays->pluck('nik'))
                                        ->get();
                $isStaff = false;

            } else if ($cek_role->name == 'Project Management Office Manager') {
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Program & Project Management')->pluck('nik');

                $getLeavingPermit   = $getLeavingPermit->whereIn('tb_cuti.nik',$listGroup)->get();
                $getPermit          = $getPermit->whereIn('tb_timesheet_permit.nik',$listGroup)->get();
                $sumMandays         = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->select('point_mandays','users.name','tb_timesheet.nik','task')->selectRaw('MONTH(start_date) AS month_number')->whereIn('tb_timesheet.nik',$listGroup)->where('status_karyawan','!=','dummy')->where('status_karyawan','!=','dummy')->where('status','Done')->whereYear('start_date',date('Y'))->get();

                $getUserByGroup     = User::join('role_user', 'role_user.user_id', '=', 'users.nik')
                                        ->join('roles', 'roles.id', '=', 'role_user.role_id')
                                        ->select('users.name','users.nik')
                                        ->where('roles.group','Program & Project Management')
                                        ->where('roles.name','not like','%Manager')
                                        ->where('users.status_delete','-')
                                        ->whereNotIn('nik', $sumMandays->pluck('nik'))
                                        ->get();
                $isStaff = false;

            } else {
                $getLeavingPermit   = $getLeavingPermit->where('tb_cuti.nik',$nik)->get();
                $getPermit          = $getPermit->where('tb_timesheet_permit.nik',$nik)->get();
                $sumMandays         = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->select('point_mandays','users.name','tb_timesheet.nik','task')->selectRaw('MONTH(start_date) AS month_number')->where('tb_timesheet.nik',$nik)->where('status','Done')->whereYear('start_date',date('Y'))->get();
                $isStaff = true;

            }
        }elseif ($cek_role->group == 'Synergy System Management') {
            if ($cek_role->name == 'VP Synergy System Management' || $cek_role->name == 'Synergy System & Services Manager' || $cek_role->name == 'Synergy System Delivery Manager' || $cek_role->name == 'Synergy System Architecture Manager') {
                if ($cek_role->name == 'Synergy System & Services Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.mini_group','Synergy System & Services')->pluck('nik');
                } elseif ($cek_role->name == 'Synergy System Delivery Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.mini_group','Synergy System Delivery')->pluck('nik');
                } elseif ($cek_role->name == 'Synergy System Architecture Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.mini_group','Synergy System Architecture')->pluck('nik');
                } else {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Synergy System Management')->pluck('nik');
                }

                $getLeavingPermit   = $getLeavingPermit->whereIn('tb_cuti.nik',$listGroup)->get();
                $getPermit          = $getPermit->whereIn('tb_timesheet_permit.nik',$listGroup)->get();
                $sumMandays         = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->select('point_mandays','users.name','tb_timesheet.nik','task')->selectRaw('MONTH(start_date) AS month_number')->whereIn('tb_timesheet.nik',$listGroup)->where('status_karyawan','!=','dummy')->where('status','Done')->whereYear('start_date',date('Y'))->get();

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
                $sumMandays         = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->select('point_mandays','users.name','tb_timesheet.nik','task')->selectRaw('MONTH(start_date) AS month_number')->where('tb_timesheet.nik',$nik)->where('status','Done')->whereYear('start_date',date('Y'))->get();

                $isStaff = true;
            }
        }elseif ($cek_role->group == 'Solutions & Partnership Management') {
            if ($cek_role->name == 'VP Solutions & Partnership Management' || $cek_role->name == 'Application Development Specialist Manager' || $cek_role->name == 'Customer Relation Manager') {
                if ($cek_role->name == 'Application Development Specialist Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.mini_group','Application Development Specialist')->pluck('nik');
                } else if ($cek_role->name == 'Customer Relation Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.mini_group','Customer Relationship Management')->pluck('nik');
                } else {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Solutions & Partnership Management')->pluck('nik');
                }

                $getLeavingPermit   = $getLeavingPermit->whereIn('tb_cuti.nik',$listGroup)->get();
                $getPermit          = $getPermit->whereIn('tb_timesheet_permit.nik',$listGroup)->get();
                $sumMandays         = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->select('point_mandays','users.name','tb_timesheet.nik','task')->selectRaw('MONTH(start_date) AS month_number')->whereIn('tb_timesheet.nik',$listGroup)->where('status_karyawan','!=','dummy')->where('status','Done')->whereYear('start_date',date('Y'))->get();

                $getUserByGroup     = User::join('role_user', 'role_user.user_id', '=', 'users.nik')
                                        ->join('roles', 'roles.id', '=', 'role_user.role_id')
                                        ->select('users.name','users.nik')
                                        ->where('roles.group','Solutions & Partnership Management')
                                        ->where('roles.name','not like','%Manager')
                                        ->where('users.status_delete','-')
                                        ->whereNotIn('nik', $sumMandays->pluck('nik'))
                                        ->get();
                $isStaff = false;

            } else {
                $getLeavingPermit   = $getLeavingPermit->where('tb_cuti.nik',$nik)->get();
                $getPermit          = $getPermit->where('tb_timesheet_permit.nik',$nik)->get();
                $sumMandays         = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->select('point_mandays','users.name','tb_timesheet.nik','task')->selectRaw('MONTH(start_date) AS month_number')->where('tb_timesheet.nik',$nik)->where('status','Done')->whereYear('start_date',date('Y'))->get();

                $isStaff = true;

            }
        }elseif ($cek_role->group == 'Human Capital Management') {
            if ($cek_role->name == 'People Operations & Services Manager' || $cek_role->name == 'VP Human Capital Management' || $cek_role->name == 'Human Capital Manager') {

                if ($cek_role->name == 'People Operations & Services Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.mini_group','People Operations & Services')->orWhere('roles.mini_group','Human Capital Management')->pluck('nik');
                } elseif ($cek_role->name == 'Human Capital Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.mini_group','Human Capital Management')->pluck('nik');
                } else {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Human Capital Management')->pluck('nik');
                }
                $getLeavingPermit   = $getLeavingPermit->whereIn('tb_cuti.nik',$listGroup)->get();
                $getPermit          = $getPermit->whereIn('tb_timesheet_permit.nik',$listGroup)->get();
                $sumMandays         = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->select('point_mandays','users.name','tb_timesheet.nik','task')->selectRaw('MONTH(start_date) AS month_number')->whereIn('tb_timesheet.nik',$listGroup)->where('status_karyawan','!=','dummy')->where('status','Done')->whereYear('start_date',date('Y'))->get();

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
                $sumMandays         = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->select('point_mandays','users.name','tb_timesheet.nik','task')->selectRaw('MONTH(start_date) AS month_number')->where('tb_timesheet.nik',$nik)->where('status','Done')->whereYear('start_date',date('Y'))->get();
                $isStaff = true;

            }
        }elseif ($cek_role->group == 'Internal Chain Management') {
            if ($cek_role->name == 'VP Internal Chain Management' || $cek_role->name == 'Supply Chain & IT Support Manager' || $cek_role->name == 'Internal Operation Support Manager' || $cek_role->name == 'Legal Compliance & Contract Doc Management') {

                if ($cek_role->name == 'Supply Chain & IT Support Manager' || $cek_role->name == 'Asset Management') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')
                    // ->where('roles.name','not like','%Manager')
                    ->where('roles.mini_group','Supply Chain & IT Support')
                    ->pluck('users.nik');
                } else if ($cek_role->name == 'Internal Operation Support Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')
                    // ->where('roles.name','not like','%Manager')
                    ->where('roles.mini_group','Internal Operation Support')
                    ->pluck('users.nik');
                } else if ($cek_role->name == 'Supply Chain Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')
                    // ->where('roles.name','not like','%Manager')
                    ->where('roles.mini_group','Supply Chain Management')
                    ->pluck('users.nik');
                } else {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')
                    // ->where('roles.name','not like','%Manager')
                    ->where('roles.group','Internal Chain Management')
                    ->pluck('users.nik');
                }

                $getLeavingPermit   = $getLeavingPermit->whereIn('tb_cuti.nik',$listGroup)->get();
                $getPermit          = $getPermit->whereIn('tb_timesheet_permit.nik',$listGroup)->get();
                $sumMandays         = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->select('point_mandays','users.name','tb_timesheet.nik','task')->selectRaw('MONTH(start_date) AS month_number')->whereIn('tb_timesheet.nik',$listGroup)->where('status_karyawan','!=','dummy')->where('status','Done')->whereYear('start_date',date('Y'))->get();

                $getUserByGroup     = User::join('role_user', 'role_user.user_id', '=', 'users.nik')
                                        ->join('roles', 'roles.id', '=', 'role_user.role_id')
                                        ->select('users.name','users.nik')
                                        ->where('roles.group','Internal Chain Management')
                                        ->where('roles.name','not like','%Manager')
                                        ->where('roles.name','not like','VP%')
                                        // ->where('roles.name','not like','%MSM Helpdesk%')
                                        // ->where('roles.name','not like','%MSM Lead Helpdesk%')
                                        // ->where('roles.name','not like','%MSM Intern%')
                                        ->where('users.status_delete','-')
                                        ->whereNotIn('nik', $sumMandays->pluck('nik'))
                                        ->get();

                $isStaff = false;

            } else {
                $getLeavingPermit   = $getLeavingPermit->where('tb_cuti.nik',$nik)->get();
                $getPermit          = $getPermit->where('tb_timesheet_permit.nik',$nik)->get();
                $sumMandays         = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->select('point_mandays','users.name','tb_timesheet.nik','task')->selectRaw('MONTH(start_date) AS month_number')->where('tb_timesheet.nik',$nik)->where('status','Done')->whereYear('start_date',date('Y'))->get();
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
                        "planned"   =>$workdays - $getPublicHolidayAdjustment,
                        "threshold" =>"-",
                        "billable"  =>"-",
                        "percentage_billable" =>"-",
                        "deviation" =>"-",
                        "total_task"=>"-",
                        "status"    =>"Go Ahead"
                    ]);
                }
            } else {
                $arrSumPoint->push(["name"=>Auth::User()->name,
                    "nik"       =>Auth::User()->nik,
                    "actual"    =>"-",
                    "planned"   =>$workdays - $getPublicHolidayAdjustment,
                    "threshold" =>"-",
                    "billable"  =>"-",
                    "percentage_billable" =>"-",
                    "deviation" =>"-",
                    "total_task"=>"-",
                    "status"    =>"Go Ahead"
                ]);
            }
        }else{
            $startDate       = Carbon::now()->startOfYear()->format("Y-m-d");
            $endDate         = Carbon::now()->endOfYear()->format("Y-m-d");
            $workdays        = $this->getWorkDays($startDate,$endDate,"workdays");
            $workdays        = count($workdays["workdays"]);

            $workdaysThisMonth = $this->getWorkDays(Carbon::now()->startOfMonth()->format("Y-m-d"),Carbon::now()->endOfMonth()->format("Y-m-d"),"workdays");
            $countThisMonth    = count($workdaysThisMonth["workdays"]); 

            $countPointByUser = $sumMandays->groupBy('name')->map(function ($group) {
                return round($group->count('point_mandays'),2);
            });

            $countPointThisMonth = $sumMandays->where('month_number',date("n"))->groupBy('name')->map(function ($group) {
                return round($group->sum('point_mandays'),2);
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

            $status = '';
            $sumPointMandays = collect();
            foreach($sumPointByUser as $key_point => $valueSumPoint){
                $billable = isset($sumArrayPermitByName[$key_point])?$sumArrayPermitByName[$key_point]:0;
                // $billable = isset($getPermitByName[$key_point])?$getPermitByName[$key_point]:0;
                $sumPointMandays->push([
                    "name"=>$key_point,
                    "nik"=>collect($sumMandays)->where('name',$key_point)->first()->nik,
                    "actual"=>$valueSumPoint,
                    // "planned"=>collect($sumMandays)->first()->planned,
                    "planned"=>$workdays - $getPublicHolidayAdjustment,
                    // "threshold"=>collect($sumMandays)->first()->threshold,
                    "threshold"=>number_format((float)$workdays*80/100,1,'.',''),
                    "billable"=>number_format($valueSumPoint - $billable,2,'.',''),
                    // "percentage_billable"=>number_format(($valueSumPoint - $billable)/collect($sumMandays)->first()->planned*100,  2, '.', ''),
                    "percentage_billable"=>number_format(($valueSumPoint - $billable)/$workdays*100,  2, '.', ''),
                    "deviation"=>number_format(($workdays - $valueSumPoint), 2, '.', ''),
                ]); 
            }  

            $collection = collect($sumPointMandays);        
            $uniqueCollection = $collection->groupBy('name')->map->first();

            foreach($uniqueCollection->all() as $key_uniq => $data_uniq){
                if ($data_uniq['name'] == $key_uniq) {
                    $countByName = isset($countPointThisMonth[$key_uniq])?$countPointThisMonth[$key_uniq]:0;
                    if (($countThisMonth - $getPublicHolidayAdjustment) >= $countByName) {
                        $status = "Go Ahead";
                    }else if(($countThisMonth - $getPublicHolidayAdjustment) < $countByName){
                        $status = "Overtime";
                    }

                    $arrSumPoint->push([
                        "name"      =>$data_uniq['name'],
                        "nik"       =>$data_uniq['nik'],
                        "actual"    =>$data_uniq['actual'],
                        "planned"   =>$data_uniq['planned'],
                        "threshold" =>number_format((float)$data_uniq['threshold'],1,'.',''),
                        "billable"  =>$data_uniq['billable'],
                        "percentage_billable" =>$data_uniq['percentage_billable'] . "%",
                        "deviation" =>number_format($data_uniq['deviation'], 2, '.', ''),
                        "total_task"=>$countPointByUser[$key_uniq],
                        "status"    =>$status,
                        "this_month"=>$countThisMonth,
                        "countByName"=>$countByName
                    ]);
                }
            }

            if ($isStaff == false) {
                foreach($getUserByGroup as $value_group){
                    $arrSumPoint->push(["name"=>$value_group->name,
                        "nik"       =>$value_group->nik,
                        "actual"    =>"-",
                        "planned"   =>$workdays - $getPublicHolidayAdjustment,
                        "threshold" =>"-",
                        "billable"  =>"-",
                        "percentage_billable" =>"-",
                        "deviation" =>"-",
                        "total_task"=>"-",
                        "status"    =>$status
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
                ->leftJoin('tb_pmo','tb_pmo.project_id','tb_id_project.id_project')
                ->leftJoin('tb_pmo_project_charter','tb_pmo.id','tb_pmo_project_charter.id_project')
                ->select('id_sbe','tb_id_project.id_project','tb_id_project.name_project','tb_sbe_config.id as id_sbe_config','tb_sbe_detail_config.qty','tb_sbe_detail_config.item','tb_sbe_config.project_type','manpower','tb_pmo_project_charter.estimated_end_date')
                ->where('tb_sbe_detail_item.detail_item','=','Mandays')
                ->where('tb_sbe.status','Fixed')
                ->where('tb_sbe_config.status','Choosed')
                ->get();

        $appendedAttributesToHide = ['link_document','detail_config','get_function','detail_all_config_choosed'];

        $getSbe->makeHidden($appendedAttributesToHide);

        $groupByProject = $getSbe->groupBy('id_project');    

        // return $getSbe->groupBy('id_project');
        $getSumPointByProject = collect();

        foreach($groupByProject as $key_pid => $value){
            $getSumPointByProject->push(['pid' => $key_pid, 'estimated_end_date'=> $value[0]['estimated_end_date']]);
            // return $key_pid;
            // return $value['project_type']; 
        }
        $sumPointByProject = $getSumPointByProject->groupby('pid');

        $sumPointMandays = collect();

        if (isset($request->roles)) {
            if ($request->roles == 'Program & Project Management' || $request->roles == 'Synergy System Management') {
                foreach($groupByProject as $key_pid => $value){
                    foreach($sumPointByProject as $key_group => $value_group){
                        if ($key_group == $key_pid) {
                            foreach($value as $value_pid){
                                if ($value_pid['project_type'] == 'Implementation') {
                                    if (strstr($value_pid['item'], "PM")) {
                                        if (isset($getSumPointByProject[$key_group]['Project Management'])) {
                                            // return "okee";
                                            $sumPointByProject[$key_group]["Project Management"]["sumMandays"] = $sumPointByProject[$key_group]["Project Management"]["sumMandays"] + (int)$value_pid['qty']; 
                                        }else{
                                            $sumPointByProject[$key_group]->put("Project Management",collect(["sumMandays"=>(int)$value_pid['qty'],$this->sumPointMandaysSbe("Project Management",$key_group,$request->roles),"name_project"=>$value_pid['name_project']]));
                                        }
                                    }else{
                                        if (isset($sumPointByProject[$key_group]['Synergy System Management'])) {
                                            $sumPointByProject[$key_group]["Synergy System Management"]["sumMandays"] = $sumPointByProject[$key_group]["Synergy System Management"]["sumMandays"] + (int)$value_pid['qty'];
                                        }else{
                                            $sumPointByProject[$key_group]->put("Synergy System Management",collect(["sumMandays"=>(int)$value_pid['qty'],$this->sumPointMandaysSbe("Synergy System Management",$key_group,$request->roles),"name_project"=>$value_pid['name_project']]));
                                        }
                                    }
                                }else if($value_pid['project_type'] == 'Supply Only'){
                                    // return $sumPointByProject["PMO"]["sumMandays"] + $value_pid['qty']; 
                                    if (isset($sumPointByProject[$key_group]['Project Management'])) {
                                        // return "okee 5";

                                        $sumPointByProject[$key_group]["Project Management"]["sumMandays"] = $sumPointByProject[$key_group]["Project Management"]["sumMandays"] + (int)$value_pid['qty']; 
                                    }else{
                                        $sumPointByProject[$key_group]->put("Project Management",collect(["sumMandays"=>(int)$value_pid['qty']]));
                                    }
                                }else if($value_pid['project_type'] == 'Maintenance'){
                                    if (strstr($value_pid['item'], "PM") === TRUE) {
                                        if (isset($sumPointByProject[$key_group]['Project Management'])) {
                                            $sumPointByProject[$key_group]["Project Management"]["sumMandays"]  = $sumPointByProject[$key_group]["Project Management"]["sumMandays"] + (int)$value_pid['qty']; 
                                        }else{
                                            $sumPointByProject[$key_group]->put("Project Management",collect(["sumMandays"=>(int)$value_pid['qty'],$this->sumPointMandaysSbe("Project Management",$key_group,$request->roles),"name_project"=>$value_pid['name_project']]));
                                        }
                                    }else{
                                        if (isset($sumPointByProject[$key_group]['Synergy System Management'])) {
                                            $sumPointByProject[$key_group]["Synergy System Management"]["sumMandays"] = $sumPointByProject[$key_group]["Synergy System Management"]["sumMandays"] + (int)$value_pid['qty']; 
                                        }else{
                                            $sumPointByProject[$key_group]->put("Synergy System Management",collect(["sumMandays"=>(int)$value_pid['qty'],$this->sumPointMandaysSbe("Synergy System Management",$key_group,$request->roles),"name_project"=>$value_pid['name_project']]));
                                        }
                                    }
                                }   
                            }
                        }
                    }
                }

                $upper_role_name = strtoupper($request->roles);

                foreach($sumPointByProject as $key_pid => $value_project){
                    if (isset($value_project[$upper_role_name][0])) {
                        foreach($value_project[$upper_role_name][0] as $data){
                            $sumPointMandays->push([
                                "name"                  =>$data['name'],
                                "nik"                   =>$data['nik'],
                                "planned"               =>$value_project[$upper_role_name]['sumMandays'],
                                "actual"                =>$data['actual'],
                                "project_id"            =>$value_project[0]['pid'],
                                "pid"                   =>$value_project[0]['pid'] . " - " . $value_project[$upper_role_name]['name_project'],
                                "estimated_end_date"    =>$value_project[0]['estimated_end_date'],
                                "remaining"             =>$value_project[$upper_role_name]['sumMandays']-$data['actual']
                            ]);
                        }
                    }else{
                        $sumPointMandays = $sumPointMandays;
                    } 
                }
            }else{
                $sumPointMandays = $sumPointMandays;
            }
        }else{
            foreach($groupByProject as $key_pid => $value){
                foreach($sumPointByProject as $key_group => $value_group){
                    if ($key_group == $key_pid) {
                        foreach($value as $value_pid){
                            if ($value_pid['project_type'] == 'Implementation') {
                                if (strstr($value_pid['item'], "PM")) {
                                    if (isset($getSumPointByProject[$key_group]['Project Management'])) {
                                        // return "okee";
                                        $sumPointByProject[$key_group]["Project Management"]["sumMandays"] = $sumPointByProject[$key_group]["Project Management"]["sumMandays"] + (int)$value_pid['qty']; 
                                    }else{

                                        $sumPointByProject[$key_group]->put("Project Management",collect(["sumMandays"=>(int)$value_pid['qty'],$this->sumPointMandaysSbe("Project Management",$key_group,""),"name_project"=>$value_pid['name_project']]));
                                    }
                                }else{
                                    if (isset($sumPointByProject[$key_group]['Synergy System Management'])) {
                                        $sumPointByProject[$key_group]["Synergy System Management"]["sumMandays"] = $sumPointByProject[$key_group]["Synergy System Management"]["sumMandays"] + (int)$value_pid['qty'];
                                    }else{
                                        $sumPointByProject[$key_group]->put("Synergy System Management",collect(["sumMandays"=>(int)$value_pid['qty'],$this->sumPointMandaysSbe("Synergy System Management",$key_group,""),"name_project"=>$value_pid['name_project']]));
                                    }
                                }
                            }else if($value_pid['project_type'] == 'Supply Only'){
                                // return $sumPointByProject["Project Management"]["sumMandays"] + $value_pid['qty']; 
                                if (isset($sumPointByProject[$key_group]['Project Management'])) {
                                    // return "okee 5";
                                    $sumPointByProject[$key_group]["Project Management"]["sumMandays"] = $sumPointByProject[$key_group]["Project Management"]["sumMandays"] + (int)$value_pid['qty']; 
                                }else{
                                    $sumPointByProject[$key_group]->put("Project Management",collect(["sumMandays"=>(int)$value_pid['qty']]));
                                }
                            }else if($value_pid['project_type'] == 'Maintenance'){
                                if (strstr($value_pid['item'], "PM")) {
                                    if (isset($sumPointByProject[$key_group]['Project Management'])) {
                                        $sumPointByProject[$key_group]["Project Management"]["sumMandays"]  = $sumPointByProject[$key_group]["Project Management"]["sumMandays"] + (int)$value_pid['qty']; 
                                    }else{
                                        $sumPointByProject[$key_group]->put("Project Management",collect(["sumMandays"=>(int)$value_pid['qty'],$this->sumPointMandaysSbe("Project Management",$key_group,""),"name_project"=>$value_pid['name_project']]));
                                    }
                                }else{
                                    if (isset($sumPointByProject[$key_group]['Synergy System Management'])) {
                                        $sumPointByProject[$key_group]["Synergy System Management"]["sumMandays"] = $sumPointByProject[$key_group]["Synergy System Management"]["sumMandays"] + (int)$value_pid['qty']; 
                                    }else{
                                        $sumPointByProject[$key_group]->put("Synergy System Management",collect(["sumMandays"=>(int)$value_pid['qty'],$this->sumPointMandaysSbe("Synergy System Management",$key_group,""),"name_project"=>$value_pid['name_project']]));
                                    }
                                }
                            }   
                        }
                    }
                }
            }

            $nik = Auth::User()->nik;
            $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('name', 'roles.group')->where('user_id', $nik)->first();

            $upper_role_name = strtoupper($cek_role->group);

            foreach($sumPointByProject as $key_pid => $value_project){
                if (isset($value_project[$upper_role_name][0])) {
                    foreach($value_project[$upper_role_name][0] as $data){
                        $sum = 0;
                        foreach($value_project[$upper_role_name][0] as $key=>$value){
                            if(isset($value['actual']))
                                $sum += $value['actual'];
                        }
                        $sumPointMandays->push([
                            "name"                  =>$data['name'],
                            "nik"                   =>$data['nik'],
                            "planned"               =>$value_project[$upper_role_name]['sumMandays'],
                            "actual"                =>$data['actual'],
                            "project_id"            =>$value_project[0]['pid'],
                            "pid"                   =>$value_project[0]['pid'] . " - " . $value_project[$upper_role_name]['name_project'],
                            "estimated_end_date"    =>$value_project[0]['estimated_end_date'],
                            "remaining"             =>$value_project[$upper_role_name]['sumMandays']-$sum
                        ]);
                    }
                }else{
                    $sumPointMandays = $sumPointMandays;
                } 
            }
        }
        
        return array("data"=>$sumPointMandays);
    }

    public function sumPointMandaysSbe($role,$pid,$cek_role_name)
    {
        $nik = Auth::User()->nik;
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('name', 'roles.group')->where('user_id', $nik)->first(); 

        // return $role;
        if ($role == 'Program & Project Management') {
            if ($cek_role->name == 'VP Program & Project Management' || $cek_role->name == 'Project Management Office Manager') {
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Program & Project Management')->pluck('nik');

                $sumMandays = Timesheet::join('users','users.nik','tb_timesheet.nik')->select('point_mandays','users.name','users.nik')->selectRaw('MONTH(start_date) AS month_number')->whereIn('tb_timesheet.nik',$listGroup)->where('status','Done')->where('status_karyawan','!=','dummy')->where('pid',$pid)->where('type','project')->get();
            } else {
                $sumMandays  = Timesheet::join('users','users.nik','tb_timesheet.nik')->select('point_mandays','users.name','users.nik')->selectRaw('MONTH(start_date) AS month_number')->where('tb_timesheet.nik',$nik)->where('status','Done')->where('pid',$pid)->where('type','project')->get();
            }
        }elseif ($role == 'Synergy System Management') {
            if ($cek_role->name == 'VP Synergy System Management' || $cek_role->name == 'Synergy System & Services Manager' || $cek_role->name == 'Synergy System Delivery Manager' || $cek_role->name == 'Synergy System Architecture Manager') {
                if ($cek_role->name == 'Synergy System & Services Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.mini_group','Synergy System & Services')->pluck('nik');
                } elseif ($cek_role->name == 'Synergy System Delivery Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.mini_group','Synergy System Delivery')->pluck('nik');
                } elseif ($cek_role->name == 'Synergy System Architecture Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.mini_group','Synergy System Architecture')->pluck('nik');
                } else {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Synergy System Management')->pluck('nik');
                }

                $sumMandays         = Timesheet::join('users','users.nik','tb_timesheet.nik')->select('point_mandays','users.name','users.nik')->selectRaw('MONTH(start_date) AS month_number')->whereIn('tb_timesheet.nik',$listGroup)->where('status','Done')->where('status_karyawan','!=','dummy')->where('pid',$pid)->where('type','project')->get();
            } else {
                $sumMandays         = Timesheet::join('users','users.nik','tb_timesheet.nik')->select('point_mandays','users.name','users.nik')->selectRaw('MONTH(start_date) AS month_number')->where('tb_timesheet.nik',$nik)->where('status','Done')->where('pid',$pid)->where('type','project')->get();
            }
        }

        $sumPointByUser = $sumMandays->groupBy('name')->map(function ($group) {
            return round($group->sum('point_mandays'),2);
        });

        $sumPointMandays = collect();
        foreach($sumPointByUser as $key_point => $valueSumPoint){
            foreach($sumMandays as $dataMandays){
                if ($dataMandays->name == $key_point) {
                    $sumPointMandays->push(["name"=>$key_point,"nik"=>$dataMandays->nik,"actual"=>$valueSumPoint]); 
                }
            }
        }

        $collection = collect($sumPointMandays);        
        $uniqueCollection = $collection->groupBy('name')->map->first();

        $arrSumPoint = collect();
        foreach($uniqueCollection->all() as $key_uniq => $data_uniq){
            if ($data_uniq['name'] == $key_uniq) {
                $arrSumPoint->push([
                    "name"      =>$data_uniq['name'],
                    "nik"       =>$data_uniq['nik'],
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

        if (isset($request->date)) {
            $startDate = Carbon::parse($request->date)->startOfMonth()->format('Y-m-d');
            $endDate = Carbon::parse($request->date)->endOfMonth()->format('Y-m-d');
        }else{
            $currentDateTime    = Carbon::now();
            $formatMonthToYear   = $currentDateTime->year(date('Y'));

            $startDate       = $formatMonthToYear->startOfMonth()->format('Y-m-d');
            $endDate         = $formatMonthToYear->endOfMonth()->format('Y-m-d');
        }

        $sumMandays = Timesheet::join('users','users.nik','tb_timesheet.nik')->selectRaw('tb_timesheet.nik')->selectRaw('users.name')->selectRaw('SUM(point_mandays) AS `point_mandays`')->where('tb_timesheet.nik',$nik)->where('status','Done')
        ->whereBetween('tb_timesheet.start_date', [$startDate, $endDate])
        ->groupby('tb_timesheet.nik')->get()->makeHidden(['planned','threshold','plannedMonth']);

        $actualPlanned = DB::table('tb_timesheet')->select(DB::raw('SUM(point_mandays) as point_mandays'))
                ->where('tb_timesheet.nik',$nik)
                ->where('schedule','Planned')
                ->where('status','Done')
                ->where('start_date',$request->date)->first();

        $actualUnplanned = DB::table('tb_timesheet')->select(DB::raw('SUM(point_mandays) as point_mandays'))
                ->where('tb_timesheet.nik',$nik)
                ->where('schedule','Unplanned')
                ->where('status','Done')
                ->where('start_date',$request->date)->first();

        if (count($sumMandays) == 1) {
            $planned = $sumMandays->map(function ($item, $key) {
                $planned = $item['plannedMonth'];
                $actual = $item['point_mandays'];
                $name = $item['name'];
                return [$planned,$actual,$name];
            });

            $percentage = number_format($planned[0][1]/$planned[0][0]*100,  2, '.', '');

            return collect(['percentage'=>$percentage,'name'=>Auth::User()->name,'plannedToday'=>number_format($actualPlanned->point_mandays,2, '.', ''),'unplannedToday'=>number_format($actualUnplanned->point_mandays,2, '.', '')]);
        } else {
            return collect(['percentage'=>'0','name'=>Auth::User()->name,'plannedToday'=>'0','unplannedToday'=>'0']);
        } 
    }

    public function getPointMandaysbyNik(Request $request)
    {
        $nik = Auth::User()->nik;

        $actualPlanned = DB::table('tb_timesheet')->select(DB::raw('SUM(point_mandays) as point_mandays'))
                ->where('tb_timesheet.nik',$nik)
                ->where('schedule','Planned')
                ->where('status','Done')
                ->where('start_date',$request->start_date)->first();

        $actualUnplanned = DB::table('tb_timesheet')->select(DB::raw('SUM(point_mandays) as point_mandays'))
                ->where('tb_timesheet.nik',$nik)
                ->where('schedule','Unplanned')
                ->where('status','Done')
                ->where('start_date',$request->start_date)->first();

        return collect(['plannedToday'=>round($actualPlanned->point_mandays,2),'unplannedToday'=>round($actualUnplanned->point_mandays,2)]);
    }

    public function getLevelChart(Request $request)
    {
        $nik = Auth::User()->nik;
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('name', 'roles.group')->where('user_id', $nik)->first(); 

        if ($cek_role->group == 'Program & Project Management') {
            if ($cek_role->name == 'VP Program & Project Management') {
                $level = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Program & Project Management')->orWhere('roles.group','Human Capital Management')->where('status_karyawan','!=','dummy')
                // ->whereMonth('start_date',date('m'))->get();
                ->whereYear('start_date',date('Y'))
                        ->get();
            } elseif ($cek_role->name == 'Project Management Office Manager') {
                $level = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Program & Project Management')->where('status_karyawan','!=','dummy')
                // ->whereMonth('start_date',date('m'))->get();
                ->whereYear('start_date',date('Y'))
                        ->get();
            } else {
                $level = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('tb_timesheet.nik',Auth::User()->nik)
                // ->whereMonth('start_date',date('m'))->get();
                ->whereYear('start_date',date('Y'))
                        ->get();
            }
        }elseif ($cek_role->group == 'Synergy System Management') {
            if ($cek_role->name == 'VP Synergy System Management' || $cek_role->name == 'Synergy System Delivery Manager' || $cek_role->name == 'Synergy System Architecture Manager' || $cek_role->name == 'Synergy System & Services Manager') {
                $level = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('status_karyawan','!=','dummy')->whereYear('start_date',date('Y'));
                if ($cek_role->name == 'Synergy System & Services Manager') {
                    $level->where('roles.mini_group','Synergy System & Services');
                } elseif ($cek_role->name == 'Synergy System Delivery Manager') {
                    $level->where('roles.mini_group','Synergy System Delivery');
                } elseif ($cek_role->name == 'Synergy System Architecture Manager') {
                    $level->where('roles.mini_group','Synergy System Architecture');
                } else {
                    $level->where('roles.group','Synergy System Management');
                }

                // $schedule = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','DPG')->where('status_karyawan','!=','dummy')
                // // ->whereMonth('start_date',date('m'))->get();
                // ->whereYear('start_date',date('Y'))
                //         ->get();
                $level = $level->get();
            } else {
                $level = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('tb_timesheet.nik',Auth::User()->nik)
                // ->whereMonth('start_date',date('m'))->get();
                ->whereYear('start_date',date('Y'))
                        ->get();
            }
        }elseif ($cek_role->group == 'Solutions & Partnership Management') {
            if ($cek_role->name == 'VP Solutions & Partnership Management' || $cek_role->name == 'Application Development Specialist Manager' || $cek_role->name == 'Customer Relation Manager' || $cek_role->name == 'Product Development Specialist Manager') {
                $level = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('status_karyawan','!=','dummy')->whereYear('start_date',date('Y'));

                if ($cek_role->name == 'Application Development Specialist Manager') {
                    $level->where('roles.mini_group','Application Development Specialist ');
                } elseif ($cek_role->name == 'Product Development Specialist Manager') {
                    $level->where('roles.mini_group','Product Development Specialist');
                } elseif ($cek_role->name == 'Customer Relation Manager') {
                    $level->where('roles.mini_group','Customer Relationship Management');
                } else {
                    $level->where('roles.group','Solutions & Partnership Management');
                }

                // $schedule = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Synergy System Management')->where('status_karyawan','!=','dummy')
                // // ->whereMonth('start_date',date('m'))->get();
                // ->whereYear('start_date',date('Y'))
                //         ->get();
                $level = $level->get();
            } else {
                $level = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('tb_timesheet.nik',Auth::User()->nik)
                // ->whereMonth('start_date',date('m'))->get();
                ->whereYear('start_date',date('Y'))
                        ->get();
            }
        }elseif ($cek_role->group == 'Human Capital Management') {
            if ($cek_role->name == 'VP Human Capital Management' || $cek_role->name == 'Human Capital Manager' || $cek_role->name == 'People Operations & Services Manager') {
                $level = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('status_karyawan','!=','dummy')->whereYear('start_date',date('Y'));

                if ($cek_role->name == 'Human Capital Manager') {
                    $level->where('roles.mini_group','Human Capital Management');
                } elseif ($cek_role->name == 'People Operations & Services Manager') {
                    $level->where('roles.mini_group','People Operations & Services ');
                }  else {
                    $level->where('roles.group','Human Capital Management');
                }

                $level = $level->get();

            } else {
                $level = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('tb_timesheet.nik',Auth::User()->nik)
                // ->whereMonth('start_date',date('m'))->get();
                ->whereYear('start_date',date('Y'))
                        ->get();
            }
        }elseif ($cek_role->group == 'Internal Chain Management') {
            if ($cek_role->name == 'VP Internal Chain Management' || $cek_role->name == 'Supply Chain & IT Support Manager' || $cek_role->name == 'Internal Operation Support Manager' || $cek_role->name == 'Supply Chain Manager') {
                $level = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('status_karyawan','!=','dummy')->whereYear('start_date',date('Y'));

                if ($cek_role->name == 'Supply Chain & IT Support Manager') {
                    $level->where('roles.mini_group','Supply Chain & IT Support');
                } elseif ($cek_role->name == 'Supply Chain Manager') {
                    $level->where('roles.mini_group','Supply Chain Management');
                } elseif ($cek_role->name == 'Internal Operation Support Manager') {
                    $level->where('roles.mini_group','Internal Operation Support');
                }  else {
                    $level->where('roles.group','Human Capital Management');
                }

                $level = $level->get();;
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

            // Convert the array to a Laravel Collection
            $collection = collect($hasil);

            // Use the every method to check if all values are zeros
            $isAllZeros = $collection->every(function ($value) {
                return $value === 0;
            });

            $hasil2 = [0,0,0,0,0];

            if ($isAllZeros) {
                return $hasil2;
            } else {
                foreach ($hasil as $key => $value) {
                    $hasil2[$key] = round(($value/$pie)*100,2);
                }

                return $hasil2;
            }
            
        }
        
    }

    public function getTaskChart(Request $request)
    {
        $nik = Auth::User()->nik;
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('name', 'roles.group')->where('user_id', $nik)->first(); 

        $task = DB::table('tb_timesheet')
                    ->select('tb_timesheet_task.task as task_name')
                    ->selectRaw('COUNT(tb_timesheet_task.task) as total_aktivitas')
                    ->join('tb_timesheet_task','tb_timesheet_task.id','=','tb_timesheet.task')
                    ->join('users','users.nik','tb_timesheet.nik')
                    ->join('role_user', 'role_user.user_id', '=', 'users.nik')
                    ->join('roles', 'roles.id', '=', 'role_user.role_id')
                    ->whereYear('start_date',date('Y'))
                    ->groupBy('tb_timesheet.task');

        if ($cek_role->group == 'Program & Project Management') {
            if ($cek_role->name == 'VP Program & Project Management') {
                $task = $task->where('roles.group','Program & Project Management')->orWhere('roles.group','Human Capital Management')
                    ->get();

                $count_task = $task->sum('total_aktivitas');
            } elseif ($cek_role->name == 'Project Management Office Manager') {
                $task = $task->where('roles.group','Program & Project Management')
                    ->get();

                $count_task = $task->sum('total_aktivitas');
            } else {
                $task = $task->where('tb_timesheet.nik',Auth::User()->nik)
                    ->get();

                $count_task = $task->sum('total_aktivitas');
            }
        }elseif ($cek_role->group == 'Synergy System Management') {
            if ($cek_role->name == 'VP Synergy System Management' || $cek_role->name == 'Synergy System Delivery Manager' || $cek_role->name == 'Synergy System Architecture Manager' || $cek_role->name == 'Synergy System & Services Manager') {
                if ($cek_role->name == 'Synergy System & Services Manager') {
                    $task = $task->where('roles.mini_group','Synergy System & Services');
                } elseif ($cek_role->name == 'Synergy System Delivery Manager') {
                    $task = $task->where('roles.mini_group','Synergy System Delivery');
                } elseif ($cek_role->name == 'Synergy System Architecture Manager') {
                    $task = $task->where('roles.mini_group','Synergy System Architecture');
                } else {
                    $task = $task->where('roles.group','Synergy System Management');
                }

                $task = $task->get();

                $count_task = $task->sum('total_aktivitas');
            } else {
                $task = $task->where('tb_timesheet.nik',Auth::User()->nik)
                    ->get();

                $count_task = $task->sum('total_aktivitas');
            }
        }elseif ($cek_role->group == 'Solutions & Partnership Management') {
            if ($cek_role->name == 'VP Solutions & Partnership Management' || $cek_role->name == 'Application Development Specialist Manager' || $cek_role->name == 'Customer Relation Manager' || $cek_role->name == 'Product Development Specialist Manager') {

                if ($cek_role->name == 'Application Development Specialist Manager') {
                    $task = $task->where('roles.mini_group','Application Development Specialist ');
                } elseif ($cek_role->name == 'Product Development Specialist Manager') {
                    $task = $task->where('roles.mini_group','Product Development Specialist');
                } elseif ($cek_role->name == 'Customer Relation Manager') {
                    $task = $task->where('roles.mini_group','Customer Relationship Management');
                } else {
                    $task = $task->where('roles.group','Solutions & Partnership Management');
                }

                $task = $task->get();

                $count_task = $task->sum('total_aktivitas');
            } else {
                $task = $task->where('tb_timesheet.nik',Auth::User()->nik)
                    ->get();

                $count_task = $task->sum('total_aktivitas');
            }
        }elseif ($cek_role->group == 'Human Capital Management') {
            if ($cek_role->name == 'VP Human Capital Management' || $cek_role->name == 'Human Capital Manager' || $cek_role->name == 'People Operations & Services Manager') {

                if ($cek_role->name == 'Human Capital Manager') {
                    $task = $task->where('roles.mini_group','Human Capital Management');
                } elseif ($cek_role->name == 'People Operations & Services Manager') {
                    $task = $task->where('roles.mini_group','People Operations & Services ');
                }  else {
                    $task = $task->where('roles.group','Human Capital Management');
                }

                $task = $task->get();

                $count_task = $task->sum('total_aktivitas');
            } else {
                $task = $task->where('tb_timesheet.nik',Auth::User()->nik)
                    ->get();

                $count_task = $task->sum('total_aktivitas');
            }
        }elseif ($cek_role->group == 'Internal Chain Management') {
            if ($cek_role->name == 'VP Internal Chain Management' || $cek_role->name == 'Supply Chain & IT Support Manager' || $cek_role->name == 'Internal Operation Support Manager' || $cek_role->name == 'Supply Chain Manager') {

                if ($cek_role->name == 'Supply Chain & IT Support Manager') {
                    $task = $task->where('roles.mini_group','Supply Chain & IT Support');
                } elseif ($cek_role->name == 'Supply Chain Manager') {
                    $task = $task->where('roles.mini_group','Supply Chain Management');
                } elseif ($cek_role->name == 'Internal Operation Support Manager') {
                    $task = $task->where('roles.mini_group','Internal Operation Support');
                }  else {
                    $task = $task->where('roles.group','Human Capital Management');
                }

                $task = $task->get();


                $count_task = $task->sum('total_aktivitas');
            } else {
                $task = $task->where('tb_timesheet.nik',Auth::User()->nik)
                    ->get();

                $count_task = $task->sum('total_aktivitas');
            }
        }

        $hasil = collect();
        $label = collect();

        if (count($task) == 0) {
            $hasil->push(0);
        }else{
            foreach ($task as $value) {
                $label->push($value->task_name);
                $hasil->push(round(($value->total_aktivitas/$count_task)*100,2));   
            }
        }
    
        return collect(["label"=>$label,"data"=>$hasil]);
    }

    public function getPhaseChart(Request $request)
    {
        $nik = Auth::User()->nik;
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('name', 'roles.group')->where('user_id', $nik)->first(); 

        $phase = DB::table('tb_timesheet')
                    ->select('tb_timesheet_phase.phase as phase_name')
                    ->selectRaw('COUNT(tb_timesheet_phase.phase) as total_aktivitas')
                    ->join('tb_timesheet_phase','tb_timesheet_phase.id','=','tb_timesheet.phase')
                    ->join('users','users.nik','tb_timesheet.nik')
                    ->join('role_user', 'role_user.user_id', '=', 'users.nik')
                    ->join('roles', 'roles.id', '=', 'role_user.role_id')
                    ->where('status_karyawan','!=','dummy')
                    ->groupBy('tb_timesheet.phase')
                    ->whereYear('start_date',date('Y'));

        if ($cek_role->group == 'Program & Project Management') {
            if ($cek_role->name == 'VP Program & Project Management') {
                $phase = $phase->where('roles.group','Program & Project Management')->orWhere('roles.group','Human Capital Management')
                    ->get();

                $count_phase = $phase->sum('total_aktivitas');
            } elseif ($cek_role->name == 'Project Management Office Manager') {
                $phase = $phase->where('roles.group','Program & Project Management')
                    ->get();

                $count_phase = $phase->sum('total_aktivitas');
            } else {
                $phase = $phase->where('tb_timesheet.nik',Auth::User()->nik)
                    ->get();

                $count_phase = $phase->sum('total_aktivitas');
            }
        }elseif ($cek_role->group == 'Synergy System Management') {
            if ($cek_role->name == 'VP Synergy System Management' || $cek_role->name == 'Synergy System Delivery Manager' || $cek_role->name == 'Synergy System Architecture Manager' || $cek_role->name == 'Synergy System & Services Manager') {
                if ($cek_role->name == 'Synergy System & Services Manager') {
                    $phase = $phase->where('roles.mini_group','Synergy System & Services');
                } elseif ($cek_role->name == 'Synergy System Delivery Manager') {
                    $phase = $phase->where('roles.mini_group','Synergy System Delivery');
                } elseif ($cek_role->name == 'Synergy System Architecture Manager') {
                    $phase = $phase->where('roles.mini_group','Synergy System Architecture');
                } else {
                    $phase = $phase->where('roles.group','Synergy System Management');
                }

                $phase = $phase->get();

                $count_phase = $phase->sum('total_aktivitas');
            } else {
                $phase = $phase->where('tb_timesheet.nik',Auth::User()->nik)
                    ->get();

                $count_phase = $phase->sum('total_aktivitas');
            }
        }elseif ($cek_role->group == 'Solutions & Partnership Management') {
            if ($cek_role->name == 'VP Solutions & Partnership Management' || $cek_role->name == 'Application Development Specialist Manager' || $cek_role->name == 'Customer Relation Manager' || $cek_role->name == 'Product Development Specialist Manager') {

                if ($cek_role->name == 'Application Development Specialist Manager') {
                    $phase = $phase->where('roles.mini_group','Application Development Specialist ');
                } elseif ($cek_role->name == 'Product Development Specialist Manager') {
                    $phase = $phase->where('roles.mini_group','Product Development Specialist');
                } elseif ($cek_role->name == 'Customer Relation Manager') {
                    $phase = $phase->where('roles.mini_group','Customer Relationship Management');
                } else {
                    $phase = $phase->where('roles.group','Solutions & Partnership Management');
                }

                $phase = $phase->get();

                $count_phase = $phase->sum('total_aktivitas');
            } else {
                $phase = $phase->where('tb_timesheet.nik',Auth::User()->nik)
                    ->get();

                $count_phase = $phase->sum('total_aktivitas');
            }
        }elseif ($cek_role->group == 'Human Capital Management') {
            if ($cek_role->name == 'VP Human Capital Management' || $cek_role->name == 'Human Capital Manager' || $cek_role->name == 'People Operations & Services Manager') {

                if ($cek_role->name == 'Human Capital Manager') {
                    $phase = $phase->where('roles.mini_group','Human Capital Management');
                } elseif ($cek_role->name == 'People Operations & Services Manager') {
                    $phase = $phase->where('roles.mini_group','People Operations & Services ');
                }  else {
                    $phase = $phase->where('roles.group','Human Capital Management');
                }

                $phase = $phase->get();

                $count_phase = $phase->sum('total_aktivitas');
            } else {
                $phase = $phase->where('tb_timesheet.nik',Auth::User()->nik)
                    ->get();

                $count_phase = $phase->sum('total_aktivitas');
            }
        }elseif ($cek_role->group == 'Internal Chain Management') {
            if ($cek_role->name == 'VP Internal Chain Management' || $cek_role->name == 'Supply Chain & IT Support Manager' || $cek_role->name == 'Internal Operation Support Manager' || $cek_role->name == 'Supply Chain Manager') {

                if ($cek_role->name == 'Supply Chain & IT Support Manager') {
                    $phase = $phase->where('roles.mini_group','Supply Chain & IT Support');
                } elseif ($cek_role->name == 'Supply Chain Manager') {
                    $phase = $phase->where('roles.mini_group','Supply Chain Management');
                } elseif ($cek_role->name == 'Internal Operation Support Manager') {
                    $phase = $phase->where('roles.mini_group','Internal Operation Support');
                }  else {
                    $phase = $phase->where('roles.group','Human Capital Management');
                }

                $phase = $phase->get();

                $count_phase = $phase->sum('total_aktivitas');
            } else {
                $phase = $phase->where('tb_timesheet.nik',Auth::User()->nik)
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
                $hasil->push(round(($value->total_aktivitas/$count_phase)*100,2));   
            }
        }

        return collect(["label"=>$label,"data"=>$hasil]);
    
    }

    public function getStatusChart(Request $request)
    {
        $nik = Auth::User()->nik;
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('name', 'roles.group')->where('user_id', $nik)->first(); 

        if ($cek_role->group == 'Program & Project Management') {
            if ($cek_role->name == 'VP Program & Project Management') {
                $status = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Program & Project Management')->orWhere('roles.group','Human Capital Management')->where('status_karyawan','!=','dummy')
                // ->whereMonth('start_date',date('m'))->get();
                ->whereYear('start_date',date('Y'))
                        ->get();
            } elseif ($cek_role->name == 'Project Management Office Manager') {
                $status = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Program & Project Management')->where('status_karyawan','!=','dummy')
                // ->whereMonth('start_date',date('m'))->get();
                ->whereYear('start_date',date('Y'))
                        ->get();
            } else {
                $status = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('tb_timesheet.nik',Auth::User()->nik)
                        // ->whereMonth('start_date',date('m'))
                        ->whereYear('start_date',date('Y'))
                        ->get();
            }
        }elseif ($cek_role->group == 'Synergy System Management') {
            if ($cek_role->name == 'VP Synergy System Management' || $cek_role->name == 'Synergy System Delivery Manager' || $cek_role->name == 'Synergy System Architecture Manager' || $cek_role->name == 'Synergy System & Services Manager') {
                $status = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('status_karyawan','!=','dummy')->whereYear('start_date',date('Y'));
                if ($cek_role->name == 'Synergy System & Services Manager') {
                    $status->where('roles.mini_group','Synergy System & Services');
                } elseif ($cek_role->name == 'Synergy System Delivery Manager') {
                    $status->where('roles.mini_group','Synergy System Delivery');
                } elseif ($cek_role->name == 'Synergy System Architecture Manager') {
                    $status->where('roles.mini_group','Synergy System Architecture');
                } else {
                    $status->where('roles.group','Synergy System Management');
                }

                // $schedule = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','DPG')->where('status_karyawan','!=','dummy')
                // // ->whereMonth('start_date',date('m'))->get();
                // ->whereYear('start_date',date('Y'))
                //         ->get();
                $status = $status->get();
            } else {
                $status = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('tb_timesheet.nik',Auth::User()->nik)
                // ->whereMonth('start_date',date('m'))->get();
                ->whereYear('start_date',date('Y'))
                        ->get();
            }
        }elseif ($cek_role->group == 'Solutions & Partnership Management') {
            if ($cek_role->name == 'VP Solutions & Partnership Management' || $cek_role->name == 'Application Development Specialist Manager' || $cek_role->name == 'Customer Relation Manager' || $cek_role->name == 'Product Development Specialist Manager') {
                $status = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('status_karyawan','!=','dummy')->whereYear('start_date',date('Y'));

                if ($cek_role->name == 'Application Development Specialist Manager') {
                    $status->where('roles.mini_group','Application Development Specialist ');
                } elseif ($cek_role->name == 'Product Development Specialist Manager') {
                    $status->where('roles.mini_group','Product Development Specialist');
                } elseif ($cek_role->name == 'Customer Relation Manager') {
                    $status->where('roles.mini_group','Customer Relationship Management');
                } else {
                    $status->where('roles.group','Solutions & Partnership Management');
                }

                // $schedule = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Synergy System Management')->where('status_karyawan','!=','dummy')
                // // ->whereMonth('start_date',date('m'))->get();
                // ->whereYear('start_date',date('Y'))
                //         ->get();
                $status = $status->get();
            } else {
                $status = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('tb_timesheet.nik',Auth::User()->nik)
                // ->whereMonth('start_date',date('m'))->get();
                ->whereYear('start_date',date('Y'))
                        ->get();
            }
        }elseif ($cek_role->group == 'Human Capital Management') {
            if ($cek_role->name == 'VP Human Capital Management' || $cek_role->name == 'Human Capital Manager' || $cek_role->name == 'People Operations & Services Manager') {
                $status = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('status_karyawan','!=','dummy')->whereYear('start_date',date('Y'));

                if ($cek_role->name == 'Human Capital Manager') {
                    $status->where('roles.mini_group','Human Capital Management');
                } elseif ($cek_role->name == 'People Operations & Services Manager') {
                    $status->where('roles.mini_group','People Operations & Services ');
                }  else {
                    $status->where('roles.group','Human Capital Management');
                }

                $status = $status->get();

            } else {
                $status = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('tb_timesheet.nik',Auth::User()->nik)
                // ->whereMonth('start_date',date('m'))->get();
                ->whereYear('start_date',date('Y'))
                        ->get();
            }
        }elseif ($cek_role->group == 'Internal Chain Management') {
            if ($cek_role->name == 'VP Internal Chain Management' || $cek_role->name == 'Supply Chain & IT Support Manager' || $cek_role->name == 'Internal Operation Support Manager' || $cek_role->name == 'Supply Chain Manager') {
                $status = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('status_karyawan','!=','dummy')->whereYear('start_date',date('Y'));

                if ($cek_role->name == 'Supply Chain & IT Support Manager') {
                    $status->where('roles.mini_group','Supply Chain & IT Support');
                } elseif ($cek_role->name == 'Supply Chain Manager') {
                    $status->where('roles.mini_group','Supply Chain Management');
                } elseif ($cek_role->name == 'Internal Operation Support Manager') {
                    $status->where('roles.mini_group','Internal Operation Support');
                }  else {
                    $status->where('roles.group','Human Capital Management');
                }

                $status = $status->get();;
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

            // Convert the array to a Laravel Collection
            $collection = collect($hasil);

            // Use the every method to check if all values are zeros
            $isAllZeros = $collection->every(function ($value) {
                return $value === 0;
            });

            $hasil2 = [0,0,0,0,0];

            if ($isAllZeros) {
                $hasil2;
            } else {
                foreach ($hasil as $key => $value) {
                    $hasil2[$key] = round(($value/$pie)*100,2);
                }
            }
            
        }

        return $hasil2;
    }

    public function getScheduleChart(Request $request)
    {
        $nik = Auth::User()->nik;
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('name', 'roles.group')->where('user_id', $nik)->first(); 

        if ($cek_role->group == 'Program & Project Management') {
            if ($cek_role->name == 'VP Program & Project Management') {
                $schedule = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Program & Project Management')->orWhere('roles.group','Human Capital Management')->where('status_karyawan','!=','dummy')
                // ->whereMonth('start_date',date('m'))->get();
                ->whereYear('start_date',date('Y'))
                        ->get();
            } elseif ($cek_role->name == 'Project Management Office Manager') {
                $schedule = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Program & Project Management')->where('status_karyawan','!=','dummy')
                // ->whereMonth('start_date',date('m'))->get();
                ->whereYear('start_date',date('Y'))
                        ->get();
            } else {
                $schedule = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('tb_timesheet.nik',Auth::User()->nik)->where('status_karyawan','!=','dummy')
                // ->whereMonth('start_date',date('m'))->get();
                ->whereYear('start_date',date('Y'))
                        ->get();
            }
        }elseif ($cek_role->group == 'Synergy System Management') {
            if ($cek_role->name == 'VP Synergy System Management' || $cek_role->name == 'Synergy System Delivery Manager' || $cek_role->name == 'Synergy System Architecture Manager' || $cek_role->name == 'Synergy System & Services Manager') {
                $schedule = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('status_karyawan','!=','dummy')->whereYear('start_date',date('Y'));
                if ($cek_role->name == 'Synergy System & Services Manager') {
                    $schedule->where('roles.mini_group','Synergy System & Services');
                } elseif ($cek_role->name == 'Synergy System Delivery Manager') {
                    $schedule->where('roles.mini_group','Synergy System Delivery');
                } elseif ($cek_role->name == 'Synergy System Architecture Manager') {
                    $schedule->where('roles.mini_group','Synergy System Architecture');
                } else {
                    $schedule->where('roles.group','Synergy System Management');
                }

                // $schedule = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','DPG')->where('status_karyawan','!=','dummy')
                // // ->whereMonth('start_date',date('m'))->get();
                // ->whereYear('start_date',date('Y'))
                //         ->get();
                $schedule = $schedule->get();
            } else {
                $schedule = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('tb_timesheet.nik',Auth::User()->nik)
                // ->whereMonth('start_date',date('m'))->get();
                ->whereYear('start_date',date('Y'))
                        ->get();
            }
        }elseif ($cek_role->group == 'Solutions & Partnership Management') {
            if ($cek_role->name == 'VP Solutions & Partnership Management' || $cek_role->name == 'Application Development Specialist Manager' || $cek_role->name == 'Customer Relation Manager' || $cek_role->name == 'Product Development Specialist Manager') {
                $schedule = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('status_karyawan','!=','dummy')->whereYear('start_date',date('Y'));

                if ($cek_role->name == 'Application Development Specialist Manager') {
                    $schedule->where('roles.mini_group','Application Development Specialist ');
                } elseif ($cek_role->name == 'Product Development Specialist Manager') {
                    $schedule->where('roles.mini_group','Product Development Specialist');
                } elseif ($cek_role->name == 'Customer Relation Manager') {
                    $schedule->where('roles.mini_group','Customer Relationship Management');
                } else {
                    $schedule->where('roles.group','Solutions & Partnership Management');
                }

                // $schedule = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Synergy System Management')->where('status_karyawan','!=','dummy')
                // // ->whereMonth('start_date',date('m'))->get();
                // ->whereYear('start_date',date('Y'))
                //         ->get();
                $schedule = $schedule->get();
            } else {
                $schedule = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('tb_timesheet.nik',Auth::User()->nik)
                // ->whereMonth('start_date',date('m'))->get();
                ->whereYear('start_date',date('Y'))
                        ->get();
            }
        }elseif ($cek_role->group == 'Human Capital Management') {
            if ($cek_role->name == 'VP Human Capital Management' || $cek_role->name == 'Human Capital Manager' || $cek_role->name == 'People Operations & Services Manager') {
                $schedule = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('status_karyawan','!=','dummy')->whereYear('start_date',date('Y'));

                if ($cek_role->name == 'Human Capital Manager') {
                    $schedule->where('roles.mini_group','Human Capital Management');
                } elseif ($cek_role->name == 'People Operations & Services Manager') {
                    $schedule->where('roles.mini_group','People Operations & Services ');
                }  else {
                    $schedule->where('roles.group','Human Capital Management');
                }

                $schedule = $schedule->get();

            } else {
                $schedule = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('tb_timesheet.nik',Auth::User()->nik)
                // ->whereMonth('start_date',date('m'))->get();
                ->whereYear('start_date',date('Y'))
                        ->get();
            }
        }elseif ($cek_role->group == 'Internal Chain Management') {
            if ($cek_role->name == 'VP Internal Chain Management' || $cek_role->name == 'Supply Chain & IT Support Manager' || $cek_role->name == 'Internal Operation Support Manager' || $cek_role->name == 'Supply Chain Manager') {
                $schedule = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('status_karyawan','!=','dummy')->whereYear('start_date',date('Y'));

                if ($cek_role->name == 'Supply Chain & IT Support Manager') {
                    $schedule->where('roles.mini_group','Supply Chain & IT Support');
                } elseif ($cek_role->name == 'Supply Chain Manager') {
                    $schedule->where('roles.mini_group','Supply Chain Management');
                } elseif ($cek_role->name == 'Internal Operation Support Manager') {
                    $schedule->where('roles.mini_group','Internal Operation Support');
                }  else {
                    $schedule->where('roles.group','Human Capital Management');
                }

                $schedule = $schedule->get();;
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
                $hasil2[$key] = round(($value/$pie)*100,2);
            }
        }

        return $hasil2;

        // return array($statusSchedule=>$hasil2);
    }

    public function getRemainingChart(Request $request)
    {
        $nik = Auth::User()->nik;
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('name', 'roles.group', 'mini_group')->where('user_id', $nik)->first();

        $data = DB::table('tb_timesheet')
                ->join('users','tb_timesheet.nik','users.nik')->select('name','point_mandays','end_date','status','users.nik')
                    ->where('status_karyawan','!=','dummy')
                    ->where('status_delete','!=','D')
                    ->selectRaw('MONTH(start_date) AS month_number')
                    ->whereYear('start_date',date('Y'))
                    ->whereMonth('start_date',Carbon::now()->month);

        $getUserByGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')
                        ->join('roles', 'roles.id', '=', 'role_user.role_id')
                        ->select('users.name');

        if ($request->roles != "") {
            if(Str::contains($cek_role->name, 'VP')){
                $getUserByGroup = $getUserByGroup->where('roles.group',$request->roles)
                            ->where('roles.name','not like','%Manager%')
                            ->where('roles.name','not like','%VP%')
                            ->where('users.status_karyawan','!=','dummy')
                            ->whereNotIn('nik', $data->get()->pluck('nik'))
                            ->get(); 
            } else {
                $getUserByGroup = $getUserByGroup->where('roles.mini_group',$cek_role->mini_group)
                            ->where('roles.name','not like','%Manager%')
                            ->where('roles.name','not like','%VP%')
                            ->where('users.status_karyawan','!=','dummy')
                            ->whereNotIn('nik', $data->get()->pluck('nik'))
                            ->get(); 
            }
        } else {
            if(Str::contains($cek_role->name, 'VP')){
                $getUserByGroup = $getUserByGroup->where('roles.group',$cek_role->group)
                            ->where('roles.name','not like','%Manager%')
                            ->where('roles.name','not like','%VP%')
                            ->where('users.status_karyawan','!=','dummy')
                            ->whereNotIn('nik', $data->get()->pluck('nik'))
                            ->get(); 
            } else {
                $getUserByGroup = $getUserByGroup->where('roles.mini_group',$cek_role->mini_group)
                            ->where('roles.name','not like','%Manager%')
                            ->where('roles.name','not like','%VP%')
                            ->where('users.status_karyawan','!=','dummy')
                            ->whereNotIn('nik', $data->get()->pluck('nik'))
                            ->get(); 
            }
        }

        $month_number = collect();

        $data = $data->whereMonth('start_date',Carbon::now()->month);

        $month_number = $month_number->push(Carbon::now()->month);

        $startDate = Carbon::now();
        $startDate->month;

        $endDate = Carbon::now();
        $endDate->month;

        $startDateFinal = $startDate->startOfMonth()->format("Y-m-d");
        $endDateFinal   = $endDate->endOfMonth()->format("Y-m-d");

        $workdays = $this->getWorkDays($startDateFinal,$endDateFinal)["workdays"]->values()->count();
                
        if ($cek_role->group == 'Program & Project Management') {
            if ($cek_role->name == 'VP Program & Project Management' || $cek_role->name == 'Project Management Office Manager') {
                if ($cek_role->name == 'VP Program & Project Management') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Program & Project Management')->orWhere('roles.group','Human Capital Management')->pluck('nik');
                } elseif ($cek_role->name == 'Project Management Office Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Program & Project Management')->pluck('nik');
                }
                
                $data = $data->whereIn('tb_timesheet.nik',$listGroup)->where('status','Done')->get();

                $data = $data->groupBy('month_number');

                $arrMonth = [1,2,3,4,5,6,7,8,9,10,11,12];

                $arrMonthMandays = [0,0,0,0,0,0,0,0,0,0,0,0];

                foreach($arrMonthMandays as $key_mandays => $valueMandays){
                    foreach($month_number as $key_month => $value_ArrMonth)
                    if ($key_month == $key_mandays) {
                        $arrMonthMandays[$value_ArrMonth-1] = $arrMonthMandays[$value_ArrMonth-1]+$workdays;
                    }else{
                        $arrMonthMandays[$value_ArrMonth-1] = $arrMonthMandays[$value_ArrMonth-1]; 
                    }
                }

                foreach($data as $key => $value){
                    $hasil_prosentase  = array($key => 0);
                    $hasil_remaining   = array($key => 0);
                    $hasil_over        = array($key => 0);

                    $arrName = collect();
                    $arrProsentaseByUser = collect();
                    $arrRemainingByUser = collect();
                    $arrOverByUser = collect();
                    $arrFinalProsentaseByUser = collect();
                    $arrFinalRemainingByUser = collect();
                    $arrFinalOverByUser = collect();

                    foreach($value as $datas){
                        $arrName->push($datas->name);
                    }

                    foreach($getUserByGroup as $value_group){
                        $arrName->push($value_group->name);
                    }

                    foreach($arrName->unique() as $key_name => $value_name){
                        $arrProsentaseByUser->put($value_name,0); 
                        $arrRemainingByUser->put($value_name,0);
                        $arrOverByUser->put($value_name,0);
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
                        if (round((float)$value_byUsers / $arrMonthMandays[$key-1] * 100,2) >= 100) {
                            $arrFinalProsentaseByUser->push(100);
                        } else {
                            $arrFinalProsentaseByUser->push(round((float)$value_byUsers / $arrMonthMandays[$key-1] * 100,2));
                        }

                        if (round((float)$value_byUsers / $arrMonthMandays[$key-1] * 100,2) >= 100) {
                            $arrFinalRemainingByUser->push(0);
                        } else {
                            $arrFinalRemainingByUser->push(100 - (round(($value_byUsers / $arrMonthMandays[$key-1]) * 100,2)));
                        }

                        if (round((float)$value_byUsers / $arrMonthMandays[$key-1] * 100,2) <= 100) {
                            $arrFinalOverByUser->push(0);
                        } else {
                            $arrFinalOverByUser->push(round((float)$value_byUsers / $arrMonthMandays[$key-1] * 100,2) - 100);
                        }
                    }

                    foreach($value as $datas){
                        foreach($hasil_prosentase as $key_prosentase => $value_prosentase){
                            $hasil_prosentase[$key_prosentase] = $arrFinalProsentaseByUser;
                        }

                        foreach($hasil_remaining as $key_remaining => $value_remaining){
                            $hasil_remaining[$key_remaining] = $arrFinalRemainingByUser;
                        }

                        foreach ($hasil_over as $key_over => $value_over) {
                            $hasil_over[$key_over] = $arrFinalOverByUser;
                        }
                    }

                    foreach($arrMonth as $keys => $month){
                        if ($month == $key) {
                            $arrMonth[$keys] = array($key=>collect([
                                "arrName"=>array($arrName->unique()),
                                "label"=>array(
                                    "Prosentase"=>$hasil_prosentase[$key_prosentase],
                                    "Remaining"=>$hasil_remaining[$key_remaining],
                                    "Over"=>$hasil_over[$key_over]
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

                            if (round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2) >= 100) {
                                $prosentase = 100;
                            } else {
                                $prosentase = round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2);
                            }

                            if (round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2) >= 100) {
                                $remaining = 0;
                            } else {
                                $remaining = 100 - (round((($hasil_remaining[$key] / $EffectiveMandaysMonthly) * 100),2));
                            }

                            if (round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2) <= 100) {
                                $over = 0;
                            } else {
                                $over = (round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2) - 100);
                            }

                            $arrMonth[$keys] = array($key=>collect([
                                "arrName"=>array($arrName),
                                "label"=>array(
                                    "Prosentase"=>array($prosentase),
                                    "Remaining"=>array($remaining),
                                    "Over"=>array($over)
                                )
                            ])); 
                        }
                    }
                }
            }
        }elseif ($cek_role->group == 'Synergy System Management') {
            if ($cek_role->name == 'VP Synergy System Management' || $cek_role->name == 'Synergy System & Services Manager' || $cek_role->name == 'Synergy System Delivery Manager' || $cek_role->name == 'Synergy System Architecture Manager') {
                if ($cek_role->name == 'Synergy System & Services Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.mini_group','Synergy System & Services')->pluck('nik');
                } elseif ($cek_role->name == 'Synergy System Delivery Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.mini_group','Synergy System Delivery')->where('status_karyawan','!=','dummy')->pluck('nik');
                } elseif ($cek_role->name == 'Synergy System Architecture Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.mini_group','Synergy System Architecture')->pluck('nik');
                } else {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Synergy System Management')->pluck('nik');
                }
                
                
                $data = $data->whereIn('tb_timesheet.nik',$listGroup)->where('status','Done')->get();

                $data = $data->groupBy('month_number');

                $arrMonth = [1,2,3,4,5,6,7,8,9,10,11,12];

                $arrMonthMandays = [0,0,0,0,0,0,0,0,0,0,0,0];

                foreach($arrMonthMandays as $key_mandays => $valueMandays){
                    foreach($month_number as $key_month => $value_ArrMonth)
                    if ($key_month == $key_mandays) {
                        $arrMonthMandays[$value_ArrMonth-1] = $arrMonthMandays[$value_ArrMonth-1]+$workdays;
                    }else{
                        $arrMonthMandays[$value_ArrMonth-1] = $arrMonthMandays[$value_ArrMonth-1]; 
                    }
                }

                foreach($data as $key => $value){
                    $hasil_prosentase  = array($key => 0);
                    $hasil_remaining   = array($key => 0);
                    $hasil_over        = array($key => 0);

                    $arrName = collect();
                    $arrProsentaseByUser = collect();
                    $arrRemainingByUser = collect();
                    $arrOverByUser = collect();
                    $arrFinalProsentaseByUser = collect();
                    $arrFinalRemainingByUser = collect();
                    $arrFinalOverByUser = collect();

                    foreach($value as $datas){
                        $arrName->push($datas->name);
                    }

                    foreach($getUserByGroup as $value_group){
                        $arrName->push($value_group->name);
                    }

                    foreach($arrName->unique() as $key_name => $value_name){
                        $arrProsentaseByUser->put($value_name,0); 
                        $arrRemainingByUser->put($value_name,0);
                        $arrOverByUser->put($value_name,0);
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
                        if (round((float)$value_byUsers / $arrMonthMandays[$key-1] * 100,2) >= 100) {
                            $arrFinalProsentaseByUser->push(100);
                        } else {
                            $arrFinalProsentaseByUser->push(round((float)$value_byUsers / $arrMonthMandays[$key-1] * 100,2));
                        }

                        if (round((float)$value_byUsers / $arrMonthMandays[$key-1] * 100,2) >= 100) {
                            $arrFinalRemainingByUser->push(0);
                        } else {
                            $arrFinalRemainingByUser->push(100 - (round(($value_byUsers / $arrMonthMandays[$key-1]) * 100,2)));
                        }

                        if (round((float)$value_byUsers / $arrMonthMandays[$key-1] * 100,2) <= 100) {
                            $arrFinalOverByUser->push(0);
                        } else {
                            $arrFinalOverByUser->push(round((float)$value_byUsers / $arrMonthMandays[$key-1] * 100,2) - 100);
                        }
                    }

                    foreach($value as $datas){
                        foreach($hasil_prosentase as $key_prosentase => $value_prosentase){
                            $hasil_prosentase[$key_prosentase] = $arrFinalProsentaseByUser;
                        }

                        foreach($hasil_remaining as $key_remaining => $value_remaining){
                            $hasil_remaining[$key_remaining] = $arrFinalRemainingByUser;
                        }

                        foreach ($hasil_over as $key_over => $value_over) {
                            $hasil_over[$key_over] = $arrFinalOverByUser;
                        }
                    }

                    foreach($arrMonth as $keys => $month){
                        if ($month == $key) {
                            $arrMonth[$keys] = array($key=>collect([
                                "arrName"=>array($arrName->unique()),
                                "label"=>array(
                                    "Prosentase"=>$hasil_prosentase[$key_prosentase],
                                    "Remaining"=>$hasil_remaining[$key_remaining],
                                    "Over"=>$hasil_over[$key_over]
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

                            if (round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2) >= 100) {
                                $prosentase = 100;
                            } else {
                                $prosentase = round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2);
                            }

                            if (round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2) >= 100) {
                                $remaining = 0;
                            } else {
                                $remaining = 100 - (round((($hasil_remaining[$key] / $EffectiveMandaysMonthly) * 100),2));
                            }

                            if (round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2) <= 100) {
                                $over = 0;
                            } else {
                                $over = (round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2) - 100);
                            }

                            $arrMonth[$keys] = array($key=>collect([
                                "arrName"=>array($arrName),
                                "label"=>array(
                                    "Prosentase"=>array($prosentase),
                                    "Remaining"=>array($remaining),
                                    "Over"=>array($over)
                                )
                            ])); 
                        }
                    }
                }
            }
        }elseif ($cek_role->group == 'Solutions & Partnership Management') {
            if ($cek_role->name == 'VP Solutions & Partnership Management' || $cek_role->name == 'Application Development Specialist Manager' || $cek_role->name == 'Customer Relation Manager') {
                if ($cek_role->name == 'Application Development Specialist Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.mini_group','Application Development Specialist')->pluck('nik');
                } else if ($cek_role->name == 'Customer Relation Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.mini_group','Customer Relationship Management')->pluck('nik');
                } else {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Solutions & Partnership Management')->pluck('nik');
                }
                
                
                $data = $data->whereIn('tb_timesheet.nik',$listGroup)->where('status','Done')->get();

                $data = $data->groupBy('month_number');

                $arrMonth = [1,2,3,4,5,6,7,8,9,10,11,12];

                $arrMonthMandays = [0,0,0,0,0,0,0,0,0,0,0,0];

                foreach($arrMonthMandays as $key_mandays => $valueMandays){
                    foreach($month_number as $key_month => $value_ArrMonth)
                    if ($key_month == $key_mandays) {
                        $arrMonthMandays[$value_ArrMonth-1] = $arrMonthMandays[$value_ArrMonth-1]+$workdays;
                    }else{
                        $arrMonthMandays[$value_ArrMonth-1] = $arrMonthMandays[$value_ArrMonth-1]; 
                    }
                }

                foreach($data as $key => $value){
                    $hasil_prosentase  = array($key => 0);
                    $hasil_remaining   = array($key => 0);
                    $hasil_over        = array($key => 0);

                    $arrName = collect();
                    $arrProsentaseByUser = collect();
                    $arrRemainingByUser = collect();
                    $arrOverByUser = collect();
                    $arrFinalProsentaseByUser = collect();
                    $arrFinalRemainingByUser = collect();
                    $arrFinalOverByUser = collect();

                    foreach($value as $datas){
                        $arrName->push($datas->name);
                    }

                    foreach($getUserByGroup as $value_group){
                        $arrName->push($value_group->name);
                    }

                    foreach($arrName->unique() as $key_name => $value_name){
                        $arrProsentaseByUser->put($value_name,0); 
                        $arrRemainingByUser->put($value_name,0);
                        $arrOverByUser->put($value_name,0);
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
                        if (round((float)$value_byUsers / $arrMonthMandays[$key-1] * 100,2) >= 100) {
                            $arrFinalProsentaseByUser->push(100);
                        } else {
                            $arrFinalProsentaseByUser->push(round((float)$value_byUsers / $arrMonthMandays[$key-1] * 100,2));
                        }

                        if (round((float)$value_byUsers / $arrMonthMandays[$key-1] * 100,2) >= 100) {
                            $arrFinalRemainingByUser->push(0);
                        } else {
                            $arrFinalRemainingByUser->push(100 - (round(($value_byUsers / $arrMonthMandays[$key-1]) * 100,2)));
                        }

                        if (round((float)$value_byUsers / $arrMonthMandays[$key-1] * 100,2) <= 100) {
                            $arrFinalOverByUser->push(0);
                        } else {
                            $arrFinalOverByUser->push(round((float)$value_byUsers / $arrMonthMandays[$key-1] * 100,2) - 100);
                        }
                    }

                    foreach($value as $datas){
                        foreach($hasil_prosentase as $key_prosentase => $value_prosentase){
                            $hasil_prosentase[$key_prosentase] = $arrFinalProsentaseByUser;
                        }

                        foreach($hasil_remaining as $key_remaining => $value_remaining){
                            $hasil_remaining[$key_remaining] = $arrFinalRemainingByUser;
                        }

                        foreach ($hasil_over as $key_over => $value_over) {
                            $hasil_over[$key_over] = $arrFinalOverByUser;
                        }
                    }

                    foreach($arrMonth as $keys => $month){
                        if ($month == $key) {
                            $arrMonth[$keys] = array($key=>collect([
                                "arrName"=>array($arrName->unique()),
                                "label"=>array(
                                    "Prosentase"=>$hasil_prosentase[$key_prosentase],
                                    "Remaining"=>$hasil_remaining[$key_remaining],
                                    "Over"=>$hasil_over[$key_over]
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

                            if (round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2) >= 100) {
                                $prosentase = 100;
                            } else {
                                $prosentase = round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2);
                            }

                            if (round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2) >= 100) {
                                $remaining = 0;
                            } else {
                                $remaining = 100 - (round((($hasil_remaining[$key] / $EffectiveMandaysMonthly) * 100),2));
                            }

                            if (round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2) <= 100) {
                                $over = 0;
                            } else {
                                $over = (round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2) - 100);
                            }

                            $arrMonth[$keys] = array($key=>collect([
                                "arrName"=>array($arrName),
                                "label"=>array(
                                    "Prosentase"=>array($prosentase),
                                    "Remaining"=>array($remaining),
                                    "Over"=>array($over)
                                )
                            ])); 
                        }
                    }
                }
            }
        }elseif ($cek_role->group == 'Human Capital Management') {
            if ($cek_role->name == 'People Operations & Services Manager' || $cek_role->name == 'VP Human Capital Management' || $cek_role->name == 'Human Capital Manager') {

                if ($cek_role->name == 'People Operations & Services Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.mini_group','People Operations & Services')->orWhere('roles.mini_group','Human Capital Management')->pluck('nik');
                } elseif ($cek_role->name == 'Human Capital Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.mini_group','Human Capital Management')->pluck('nik');
                } else {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Human Capital Management')->pluck('nik');
                }
                
                $data = $data->whereIn('tb_timesheet.nik',$listGroup)->where('status','Done')->get();

                $data = $data->groupBy('month_number');

                $arrMonth = [1,2,3,4,5,6,7,8,9,10,11,12];

                $arrMonthMandays = [0,0,0,0,0,0,0,0,0,0,0,0];

                foreach($arrMonthMandays as $key_mandays => $valueMandays){
                    foreach($month_number as $key_month => $value_ArrMonth)
                    if ($key_month == $key_mandays) {
                        $arrMonthMandays[$value_ArrMonth-1] = $arrMonthMandays[$value_ArrMonth-1]+$workdays;
                    }else{
                        $arrMonthMandays[$value_ArrMonth-1] = $arrMonthMandays[$value_ArrMonth-1]; 
                    }
                }

                foreach($data as $key => $value){
                    $hasil_prosentase  = array($key => 0);
                    $hasil_remaining   = array($key => 0);
                    $hasil_over        = array($key => 0);

                    $arrName = collect();
                    $arrProsentaseByUser = collect();
                    $arrRemainingByUser = collect();
                    $arrOverByUser = collect();
                    $arrFinalProsentaseByUser = collect();
                    $arrFinalRemainingByUser = collect();
                    $arrFinalOverByUser = collect();

                    foreach($value as $datas){
                        $arrName->push($datas->name);
                    }

                    foreach($getUserByGroup as $value_group){
                        $arrName->push($value_group->name);
                    }

                    foreach($arrName->unique() as $key_name => $value_name){
                        $arrProsentaseByUser->put($value_name,0); 
                        $arrRemainingByUser->put($value_name,0);
                        $arrOverByUser->put($value_name,0);
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
                        if (round((float)$value_byUsers / $arrMonthMandays[$key-1] * 100,2) >= 100) {
                            $arrFinalProsentaseByUser->push(100);
                        } else {
                            $arrFinalProsentaseByUser->push(round((float)$value_byUsers / $arrMonthMandays[$key-1] * 100,2));
                        }

                        if (round((float)$value_byUsers / $arrMonthMandays[$key-1] * 100,2) >= 100) {
                            $arrFinalRemainingByUser->push(0);
                        } else {
                            $arrFinalRemainingByUser->push(100 - (round(($value_byUsers / $arrMonthMandays[$key-1]) * 100,2)));
                        }

                        if (round((float)$value_byUsers / $arrMonthMandays[$key-1] * 100,2) <= 100) {
                            $arrFinalOverByUser->push(0);
                        } else {
                            $arrFinalOverByUser->push(round((float)$value_byUsers / $arrMonthMandays[$key-1] * 100,2) - 100);
                        }
                    }

                    foreach($value as $datas){
                        foreach($hasil_prosentase as $key_prosentase => $value_prosentase){
                            $hasil_prosentase[$key_prosentase] = $arrFinalProsentaseByUser;
                        }

                        foreach($hasil_remaining as $key_remaining => $value_remaining){
                            $hasil_remaining[$key_remaining] = $arrFinalRemainingByUser;
                        }

                        foreach ($hasil_over as $key_over => $value_over) {
                            $hasil_over[$key_over] = $arrFinalOverByUser;
                        }
                    }

                    foreach($arrMonth as $keys => $month){
                        if ($month == $key) {
                            $arrMonth[$keys] = array($key=>collect([
                                "arrName"=>array($arrName->unique()),
                                "label"=>array(
                                    "Prosentase"=>$hasil_prosentase[$key_prosentase],
                                    "Remaining"=>$hasil_remaining[$key_remaining],
                                    "Over"=>$hasil_over[$key_over]
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

                            if (round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2) >= 100) {
                                $prosentase = 100;
                            } else {
                                $prosentase = round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2);
                            }

                            if (round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2) >= 100) {
                                $remaining = 0;
                            } else {
                                $remaining = 100 - (round((($hasil_remaining[$key] / $EffectiveMandaysMonthly) * 100),2));
                            }

                            if (round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2) <= 100) {
                                $over = 0;
                            } else {
                                $over = (round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2) - 100);
                            }

                            $arrMonth[$keys] = array($key=>collect([
                                "arrName"=>array($arrName),
                                "label"=>array(
                                    "Prosentase"=>array($prosentase),
                                    "Remaining"=>array($remaining),
                                    "Over"=>array($over)
                                )
                            ])); 
                        }
                    }
                }
            }
        }elseif ($cek_role->group == 'Internal Chain Management') {
            if ($cek_role->name == 'VP Internal Chain Management' || $cek_role->name == 'Supply Chain & IT Support Manager' || $cek_role->name == 'Internal Operation Support Manager' || $cek_role->name == 'Legal Compliance & Contract Doc Management') {

                if ($cek_role->name == 'Supply Chain & IT Support Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')
                    // ->where('roles.name','not like','%Manager')
                    ->where('roles.mini_group','Supply Chain & IT Support')
                    ->pluck('users.nik');
                } else if ($cek_role->name == 'Internal Operation Support Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')
                    // ->where('roles.name','not like','%Manager')
                    ->where('roles.mini_group','Internal Operation Support')
                    ->pluck('users.nik');
                } else if ($cek_role->name == 'Supply Chain Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')
                    // ->where('roles.name','not like','%Manager')
                    ->where('roles.mini_group','Supply Chain Management')
                    ->pluck('users.nik');
                } else {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')
                    // ->where('roles.name','not like','%Manager')
                    ->where('roles.group','Internal Chain Management')
                    ->pluck('users.nik');
                }

                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')
                    // ->where('roles.name','not like','%Manager')
                    ->where('roles.group','Internal Chain Management')
                    ->pluck('users.nik');

                $data = $data->whereIn('tb_timesheet.nik',$listGroup)->where('status','Done')->get();

                $data = $data->groupBy('month_number');

                $arrMonth = [1,2,3,4,5,6,7,8,9,10,11,12];

                $arrMonthMandays = [0,0,0,0,0,0,0,0,0,0,0,0];

                foreach($arrMonthMandays as $key_mandays => $valueMandays){
                    foreach($month_number as $key_month => $value_ArrMonth)
                    if ($key_month == $key_mandays) {
                        $arrMonthMandays[$value_ArrMonth-1] = $arrMonthMandays[$value_ArrMonth-1]+$workdays;
                    }else{
                        $arrMonthMandays[$value_ArrMonth-1] = $arrMonthMandays[$value_ArrMonth-1]; 
                    }
                }

                foreach($data as $key => $value){
                    $hasil_prosentase  = array($key => 0);
                    $hasil_remaining   = array($key => 0);
                    $hasil_over        = array($key => 0);

                    $arrName = collect();
                    $arrProsentaseByUser = collect();
                    $arrRemainingByUser = collect();
                    $arrOverByUser = collect();
                    $arrFinalProsentaseByUser = collect();
                    $arrFinalRemainingByUser = collect();
                    $arrFinalOverByUser = collect();

                    foreach($value as $datas){
                        $arrName->push($datas->name);
                    }

                    foreach($getUserByGroup as $value_group){
                        $arrName->push($value_group->name);
                    }

                    foreach($arrName->unique() as $key_name => $value_name){
                        $arrProsentaseByUser->put($value_name,0); 
                        $arrRemainingByUser->put($value_name,0);
                        $arrOverByUser->put($value_name,0);
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
                        if (round((float)$value_byUsers / $arrMonthMandays[$key-1] * 100,2) >= 100) {
                            $arrFinalProsentaseByUser->push(100);
                        } else {
                            $arrFinalProsentaseByUser->push(round((float)$value_byUsers / $arrMonthMandays[$key-1] * 100,2));
                        }

                        if (round((float)$value_byUsers / $arrMonthMandays[$key-1] * 100,2) >= 100) {
                            $arrFinalRemainingByUser->push(0);
                        } else {
                            $arrFinalRemainingByUser->push(100 - (round(($value_byUsers / $arrMonthMandays[$key-1]) * 100,2)));
                        }

                        if (round((float)$value_byUsers / $arrMonthMandays[$key-1] * 100,2) <= 100) {
                            $arrFinalOverByUser->push(0);
                        } else {
                            $arrFinalOverByUser->push(round((float)$value_byUsers / $arrMonthMandays[$key-1] * 100,2) - 100);
                        }
                    }

                    foreach($value as $datas){
                        foreach($hasil_prosentase as $key_prosentase => $value_prosentase){
                            $hasil_prosentase[$key_prosentase] = $arrFinalProsentaseByUser;
                        }

                        foreach($hasil_remaining as $key_remaining => $value_remaining){
                            $hasil_remaining[$key_remaining] = $arrFinalRemainingByUser;
                        }

                        foreach ($hasil_over as $key_over => $value_over) {
                            $hasil_over[$key_over] = $arrFinalOverByUser;
                        }
                    }

                    foreach($arrMonth as $keys => $month){
                        if ($month == $key) {
                            $arrMonth[$keys] = array($key=>collect([
                                "arrName"=>array($arrName->unique()),
                                "label"=>array(
                                    "Prosentase"=>$hasil_prosentase[$key_prosentase],
                                    "Remaining"=>$hasil_remaining[$key_remaining],
                                    "Over"=>$hasil_over[$key_over]
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

                            if (round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2) >= 100) {
                                $prosentase = 100;
                            } else {
                                $prosentase = round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2);
                            }

                            if (round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2) >= 100) {
                                $remaining = 0;
                            } else {
                                $remaining = 100 - (round((($hasil_remaining[$key] / $EffectiveMandaysMonthly) * 100),2));
                            }

                            if (round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2) <= 100) {
                                $over = 0;
                            } else {
                                $over = (round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2) - 100);
                            }

                            $arrMonth[$keys] = array($key=>collect([
                                "arrName"=>array($arrName),
                                "label"=>array(
                                    "Prosentase"=>array($prosentase),
                                    "Remaining"=>array($remaining),
                                    "Over"=>array($over)
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
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('name', 'roles.group','mini_group')->where('user_id', $nik)->first(); 

        // $hasil = [0,0,0,0,0,0,0,0,0,0,0,0];

        $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $bulan_angka = [1,2,3,4,5,6,7,8,9,10,11,12];

        $data = DB::table('tb_timesheet')
                ->join('users','tb_timesheet.nik','users.nik')->select('name','point_mandays','end_date','status','users.nik')->where('status_karyawan','!=','dummy')->selectRaw('MONTH(start_date) AS month_number')->whereYear('start_date',date('Y'));

        $startDate = Carbon::now()->startOfMonth()->format("Y-m-d");
        $endDate = Carbon::now()->endOfMonth()->format("Y-m-d");
        $workdays = $this->getWorkDays($startDate,$endDate,"workdays");
        $workdays = count($workdays['workdays']);

        $getUserByGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')
                        ->join('roles', 'roles.id', '=', 'role_user.role_id')
                        ->select('users.name');

        if(Str::contains($cek_role->name, 'VP')){
            $getUserByGroup = $getUserByGroup->where('roles.group',$cek_role->group)
                        ->where('roles.name','not like','%Manager%')
                        ->where('roles.name','not like','%VP%')
                        ->where('users.status_karyawan','!=','dummy')
                        ->whereNotIn('nik', $data->get()->pluck('nik'))
                        ->get(); 
        } else {
            $getUserByGroup = $getUserByGroup->where('roles.mini_group',$cek_role->mini_group)
                        ->where('roles.name','not like','%Manager%')
                        ->where('roles.name','not like','%VP%')
                        ->where('users.status_karyawan','!=','dummy')
                        ->whereNotIn('nik', $data->get()->pluck('nik'))
                        ->get(); 
        }


        $arrCummulativeMandays = collect();
        if ($cek_role->group == 'Program & Project Management') {
            if ($cek_role->name == 'VP Program & Project Management' || $cek_role->name == 'Project Management Office Manager') {
                if ($cek_role->name == 'VP Program & Project Management') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Program & Project Management')->orWhere('roles.group','Human Capital Management')->pluck('nik');
                } elseif ($cek_role->name == 'Project Management Office Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Program & Project Management')->pluck('nik');
                }

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
        }elseif ($cek_role->group == 'Synergy System Management') {
            if ($cek_role->name == 'VP Synergy System Management' || $cek_role->name == 'Synergy System & Services Manager' || $cek_role->name == 'Synergy System Delivery Manager' || $cek_role->name == 'Synergy System Architecture Manager') {
                if ($cek_role->name == 'Synergy System & Services Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.mini_group','Synergy System & Services')->pluck('nik');
                } elseif ($cek_role->name == 'Synergy System Delivery Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.mini_group','Synergy System Delivery')->pluck('nik');
                } elseif ($cek_role->name == 'Synergy System Architecture Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.mini_group','Synergy System Architecture')->pluck('nik');
                } else {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Synergy System Management')->pluck('nik');
                }

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
        }elseif ($cek_role->group == 'Solutions & Partnership Management') {
            if ($cek_role->name == 'VP Solutions & Partnership Management' || $cek_role->name == 'Application Development Specialist Manager' || $cek_role->name == 'Customer Relation Manager') {
                if ($cek_role->name == 'Application Development Specialist Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.mini_group','Application Development Specialist')->pluck('nik');
                } else if ($cek_role->name == 'Customer Relation Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.mini_group','Customer Relationship Management')->pluck('nik');
                } else {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Solutions & Partnership Management')->pluck('nik');
                }
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
        }elseif ($cek_role->group == 'Human Capital Management') {
            if ($cek_role->name == 'People Operations & Services Manager' || $cek_role->name == 'VP Human Capital Management' || $cek_role->name == 'Human Capital Manager') {

                if ($cek_role->name == 'People Operations & Services Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.mini_group','People Operations & Services')->orWhere('roles.mini_group','Human Capital Management')->pluck('nik');
                } elseif ($cek_role->name == 'Human Capital Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.mini_group','Human Capital Management')->pluck('nik');
                } else {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Human Capital Management')->pluck('nik');
                }

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
        }elseif ($cek_role->group == 'Internal Chain Management') {
            if ($cek_role->name == 'VP Internal Chain Management' || $cek_role->name == 'Supply Chain & IT Support Manager' || $cek_role->name == 'Internal Operation Support Manager' || $cek_role->name == 'Legal Compliance & Contract Doc Management') {

                if ($cek_role->name == 'Supply Chain & IT Support Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')
                    // ->where('roles.name','not like','%Manager')
                    ->where('roles.mini_group','Supply Chain & IT Support')
                    ->pluck('users.nik');
                } else if ($cek_role->name == 'Internal Operation Support Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')
                    // ->where('roles.name','not like','%Manager')
                    ->where('roles.mini_group','Internal Operation Support')
                    ->pluck('users.nik');
                } else if ($cek_role->name == 'Supply Chain Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')
                    // ->where('roles.name','not like','%Manager')
                    ->where('roles.mini_group','Supply Chain Management')
                    ->pluck('users.nik');
                } else {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')
                    // ->where('roles.name','not like','%Manager')
                    ->where('roles.group','Internal Chain Management')
                    ->pluck('users.nik');
                }

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
        $count = (new Timesheet)->getPlannedAttribute($request->month,$request->year);
        $countThreshold = (new Timesheet)->getThresholdAttribute($request->month,$request->year);

        // Return the filtered products or perform any other logic
        $countData = response()->json($count);
        $data = $countData->getData();
        $countPlanned = (float)$data;

        $countDataThreshold = response()->json($countThreshold);
        $dataThreshold = $countDataThreshold->getData();
        $countThresholdFinal = (float)$dataThreshold;

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
        $cek_role = DB::table('role_user')->Rightjoin('roles', 'roles.id', '=', 'role_user.role_id')->select('name', 'roles.group','mini_group')->where('user_id', $nik)->first();

        $getLeavingPermit = Cuti::join('tb_cuti_detail','tb_cuti_detail.id_cuti','tb_cuti.id_cuti')
                            ->join('users','users.nik','=','tb_cuti.nik')
                            ->select('date_off as date','users.name')
                            ->where('tb_cuti.status','v')
                            ->whereIn(\DB::raw('MONTH(date_off)'),$arrayMonth)
                            ->whereYear('date_off',date('Y'))
                            ->orderby('date','desc');

        $getPublicHolidayAdjustment = PublicHolidayAdjustment::select('date')->whereIn(\DB::raw('MONTH(date)'),$arrayMonth)->whereYear('date',date('Y'))->count();

        $getPermit = TimesheetPermit::select('tb_timesheet_permit.start_date','tb_timesheet_permit.nik','users.name')->join('users','users.nik','=','tb_timesheet_permit.nik')->whereIn(\DB::raw('MONTH(start_date)'),$arrayMonth);

        $getUserByGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->select('users.name');

        if(Str::contains($cek_role->name, 'VP')){
            $getUserByGroup = $getUserByGroup->where('roles.group',$cek_role->group)
                        ->where('roles.name','not like','%Manager')
                        ->where('roles.name','not like','%VP')
                        ->where('users.status_karyawan','!=','dummy'); 
        } else {
            $getUserByGroup = $getUserByGroup->where('roles.mini_group',$cek_role->mini_group)
                        ->where('roles.name','not like','%Manager')
                        ->where('roles.name','not like','%VP')
                        ->where('users.status_karyawan','!=','dummy'); 
        }

        if ($cek_role->group == 'Program & Project Management') {
            if ($cek_role->name == 'VP Program & Project Management' || $cek_role->name == 'Project Management Office Manager') {
                if ($cek_role->name == 'VP Program & Project Management') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Program & Project Management')->orWhere('roles.group','Human Capital Management')->pluck('nik');
                } elseif ($cek_role->name == 'Project Management Office Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Program & Project Management')->pluck('nik');
                }

                $sumMandays  = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->select(DB::raw('CASE WHEN point_mandays IS NULL THEN 0 ELSE point_mandays END AS point_mandays'),'users.name','tb_timesheet.nik','task')->selectRaw('MONTH(start_date) AS month_number')->where('status_karyawan','!=','dummy')->where(function ($query) use ($arrayMonth) {
                    foreach ($arrayMonth as $month) {
                        $query->orWhereRaw("MONTH(start_date) = $month");
                    }
                });

                $sumMandays = $sumMandays->whereIn('tb_timesheet.nik',$listGroup);
                $sumMandays = $sumMandays->where('status','Done');

                if (is_null($sumMandays)) {
                    $isNeedOtherUser = true;
                }else{
                    $isNeedOtherUser = false;
                }

                if (is_null($sumMandays)) {
                    if (isset($request->pic[0])) {
                        $sumMandays         = $sumMandays->whereIn('tb_timesheet.nik',$request->pic);
                        $getLeavingPermit   = $getLeavingPermit->whereIn('tb_cuti.nik',$request->pic)->get();
                        $getPermit          = $getPermit->whereIn('tb_timesheet_permit.nik',$request->pic)->get();

                        $getUserByGroup = $getUserByGroup
                                            ->whereIn('nik',$request->pic)    
                                            ->get();
                    }else{
                        $sumMandays = $sumMandays->whereIn('tb_timesheet.nik',$listGroup);
                        $getLeavingPermit   = $getLeavingPermit->whereIn('tb_cuti.nik',$listGroup)->get();
                        $getPermit          = $getPermit->whereIn('tb_timesheet_permit.nik',$listGroup)->get();

                        $getUserByGroup = $getUserByGroup->get();
                    }
                }else{
                    if (isset($request->pic[0])) {
                        $sumMandays         = $sumMandays->whereIn('tb_timesheet.nik',$request->pic);
                        $getLeavingPermit   = $getLeavingPermit->whereIn('tb_cuti.nik',$request->pic)->get();
                        $getPermit          = $getPermit->whereIn('tb_timesheet_permit.nik',$request->pic)->get();

                        $getUserByGroup = $getUserByGroup
                                            ->whereNotIn('nik', $sumMandays->get()->pluck('nik'))
                                            ->whereIn('nik',$request->pic)    
                                            ->get();
                    }else{
                        $sumMandays = $sumMandays->whereIn('tb_timesheet.nik',$listGroup);
                        $getLeavingPermit   = $getLeavingPermit->whereIn('tb_cuti.nik',$listGroup)->get();
                        $getPermit          = $getPermit->whereIn('tb_timesheet_permit.nik',$listGroup)->get();

                        $getUserByGroup = $getUserByGroup->whereNotIn('nik', $sumMandays->get()->pluck('nik'))
                                            ->get();
                    }
                }
            } else {
                $getLeavingPermit   = $getLeavingPermit->where('tb_cuti.nik',$nik)->get();
                $getPermit          = $getPermit->where('tb_timesheet_permit.nik',$nik)->get();
                $sumMandays         = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->select('point_mandays','users.name','tb_timesheet.nik','task')->selectRaw('MONTH(start_date) AS month_number')->where('tb_timesheet.nik',$nik)->where(function ($query) use ($arrayMonth) {
                                            foreach ($arrayMonth as $month) {
                                                $query->orWhereRaw("MONTH(start_date) = $month");
                                            }
                                        });
                
                if (count($sumMandays->get()) == 0) {
                    $isNeedOtherUser = true;
                }else{
                    $isNeedOtherUser = false;
                }
            }
        }elseif ($cek_role->group == 'Synergy System Management') {
            if ($cek_role->name == 'VP Synergy System Management' || $cek_role->name == 'Synergy System & Services Manager' || $cek_role->name == 'Synergy System Delivery Manager' || $cek_role->name == 'Synergy System Architecture Manager') {
                if ($cek_role->name == 'Synergy System & Services Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.mini_group','Synergy System & Services')->pluck('nik');
                } elseif ($cek_role->name == 'Synergy System Delivery Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.mini_group','Synergy System Delivery')->pluck('nik');
                } elseif ($cek_role->name == 'Synergy System Architecture Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.mini_group','Synergy System Architecture')->pluck('nik');
                } else {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Synergy System Management')->pluck('nik');
                }

                $sumMandays         = Timesheet::join('users','users.nik','tb_timesheet.nik')->select(DB::raw('CASE WHEN point_mandays IS NULL THEN 0 ELSE point_mandays END AS point_mandays'),'users.name','tb_timesheet.nik','task')->selectRaw('MONTH(start_date) AS month_number')->where('status_karyawan','!=','dummy')->where(function ($query) use ($arrayMonth) {
                        foreach ($arrayMonth as $month) {
                            $query->orWhereRaw("MONTH(start_date) = $month");
                        }
                    });

                $sumMandays = $sumMandays->whereIn('tb_timesheet.nik',$listGroup);
                $sumMandays = $sumMandays->where('status','Done');

                if (is_null($sumMandays)) {
                    $isNeedOtherUser = true;
                }else{
                    $isNeedOtherUser = false;
                }

                if (is_null($sumMandays)) {
                    if (isset($request->pic[0])) {
                        $sumMandays         = $sumMandays->whereIn('tb_timesheet.nik',$request->pic);
                        $getLeavingPermit   = $getLeavingPermit->whereIn('tb_cuti.nik',$request->pic)->get();
                        $getPermit          = $getPermit->whereIn('tb_timesheet_permit.nik',$request->pic)->get();

                        $getUserByGroup = $getUserByGroup
                                            ->whereIn('nik',$request->pic)    
                                            ->get();
                    }else{
                        $sumMandays = $sumMandays->whereIn('tb_timesheet.nik',$listGroup);
                        $getLeavingPermit   = $getLeavingPermit->whereIn('tb_cuti.nik',$listGroup)->get();
                        $getPermit          = $getPermit->whereIn('tb_timesheet_permit.nik',$listGroup)->get();

                        $getUserByGroup = $getUserByGroup->get();
                    }
                }else{
                    if (isset($request->pic[0])) {
                        $sumMandays         = $sumMandays->whereIn('tb_timesheet.nik',$request->pic);
                        $getLeavingPermit   = $getLeavingPermit->whereIn('tb_cuti.nik',$request->pic)->get();
                        $getPermit          = $getPermit->whereIn('tb_timesheet_permit.nik',$request->pic)->get();

                        $getUserByGroup = $getUserByGroup
                                            ->whereNotIn('nik', $sumMandays->get()->pluck('nik'))
                                            ->whereIn('nik',$request->pic)    
                                            ->get();
                    }else{
                        $sumMandays = $sumMandays->whereIn('tb_timesheet.nik',$listGroup);
                        $getLeavingPermit   = $getLeavingPermit->whereIn('tb_cuti.nik',$listGroup)->get();
                        $getPermit          = $getPermit->whereIn('tb_timesheet_permit.nik',$listGroup)->get();

                        $getUserByGroup = $getUserByGroup->whereNotIn('nik', $sumMandays->get()->pluck('nik'))
                                            ->get();
                    }
                }
            } else {
                $getLeavingPermit   = $getLeavingPermit->where('tb_cuti.nik',$nik)->get();
                $getPermit          = $getPermit->where('tb_timesheet_permit.nik',$nik)->get();
                $sumMandays         = Timesheet::join('users','users.nik','tb_timesheet.nik')->select('point_mandays','users.name','tb_timesheet.nik','task')->selectRaw('MONTH(start_date) AS month_number')->where('tb_timesheet.nik',$nik)->where(function ($query) use ($arrayMonth) {
                                            foreach ($arrayMonth as $month) {
                                                $query->orWhereRaw("MONTH(start_date) = $month");
                                            }
                                        });

                if (count($sumMandays->get()) == 0) {
                    $isNeedOtherUser = true;
                }else{
                    $isNeedOtherUser = false;
                }
            }
        }elseif ($cek_role->group == 'Solutions & Partnership Management') {
            if ($cek_role->name == 'VP Solutions & Partnership Management' || $cek_role->name == 'Application Development Specialist Manager' || $cek_role->name == 'Customer Relation Manager') {
                if ($cek_role->name == 'Application Development Specialist Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.mini_group','Application Development Specialist')->pluck('nik');
                } else if ($cek_role->name == 'Customer Relation Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.mini_group','Customer Relationship Management')->pluck('nik');
                } else {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Solutions & Partnership Management')->pluck('nik');
                }
                
                $sumMandays         = Timesheet::join('users','users.nik','tb_timesheet.nik')->select(DB::raw('CASE WHEN point_mandays IS NULL THEN 0 ELSE point_mandays END AS point_mandays'),'users.name','tb_timesheet.nik','task')->selectRaw('MONTH(start_date) AS month_number')->where('status_karyawan','!=','dummy')->where(function ($query) use ($arrayMonth) {
                                            foreach ($arrayMonth as $month) {
                                                $query->orWhereRaw("MONTH(start_date) = $month");
                                            }
                                        });

                $sumMandays = $sumMandays->whereIn('tb_timesheet.nik',$listGroup);
                $sumMandays = $sumMandays->where('status','Done');

                if (is_null($sumMandays)) {
                    $isNeedOtherUser = true;
                }else{
                    $isNeedOtherUser = false;
                }

                if (is_null($sumMandays)) {
                    if (isset($request->pic[0])) {
                        $sumMandays         = $sumMandays->whereIn('tb_timesheet.nik',$request->pic);
                        $getLeavingPermit   = $getLeavingPermit->whereIn('tb_cuti.nik',$request->pic)->get();
                        $getPermit          = $getPermit->whereIn('tb_timesheet_permit.nik',$request->pic)->get();

                        $getUserByGroup = $getUserByGroup
                                            ->whereIn('nik',$request->pic)    
                                            ->get();
                    }else{
                        $sumMandays = $sumMandays->whereIn('tb_timesheet.nik',$listGroup);
                        $getLeavingPermit   = $getLeavingPermit->whereIn('tb_cuti.nik',$listGroup)->get();
                        $getPermit          = $getPermit->whereIn('tb_timesheet_permit.nik',$listGroup)->get();

                        $getUserByGroup = $getUserByGroup->get();
                    }
                }else{
                    if (isset($request->pic[0])) {
                        $sumMandays         = $sumMandays->whereIn('tb_timesheet.nik',$request->pic);
                        $getLeavingPermit   = $getLeavingPermit->whereIn('tb_cuti.nik',$request->pic)->get();
                        $getPermit          = $getPermit->whereIn('tb_timesheet_permit.nik',$request->pic)->get();

                        $getUserByGroup = $getUserByGroup
                                            ->whereNotIn('nik', $sumMandays->get()->pluck('nik'))
                                            ->whereIn('nik',$request->pic)    
                                            ->get();
                    }else{
                        $sumMandays = $sumMandays->whereIn('tb_timesheet.nik',$listGroup);
                        $getLeavingPermit   = $getLeavingPermit->whereIn('tb_cuti.nik',$listGroup)->get();
                        $getPermit          = $getPermit->whereIn('tb_timesheet_permit.nik',$listGroup)->get();

                        $getUserByGroup = $getUserByGroup->whereNotIn('nik', $sumMandays->get()->pluck('nik'))
                                            ->get();
                    }
                }
            } else {
                $getLeavingPermit   = $getLeavingPermit->where('tb_cuti.nik',$nik)->get();
                $getPermit          = $getPermit->where('tb_timesheet_permit.nik',$nik)->get();
                $sumMandays         = Timesheet::join('users','users.nik','tb_timesheet.nik')->select('point_mandays','users.name','tb_timesheet.nik','task')->selectRaw('MONTH(start_date) AS month_number')->where('tb_timesheet.nik',$nik)->where(function ($query) use ($arrayMonth) {
                                            foreach ($arrayMonth as $month) {
                                                $query->orWhereRaw("MONTH(start_date) = $month");
                                            }
                                        });

                if (count($sumMandays->get()) == 0) {
                    $isNeedOtherUser = true;
                }else{
                    $isNeedOtherUser = false;
                }
            }
        }elseif ($cek_role->group == 'Human Capital Management') {
            if ($cek_role->name == 'People Operations & Services Manager' || $cek_role->name == 'VP Human Capital Management' || $cek_role->name == 'Human Capital Manager') {

                if ($cek_role->name == 'People Operations & Services Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.mini_group','People Operations & Services')->orWhere('roles.mini_group','Human Capital')->pluck('nik');
                } elseif ($cek_role->name == 'Human Capital Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.mini_group','Human Capital')->pluck('nik');
                } else {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Human Capital Management')->pluck('nik');
                }
                
                $sumMandays         = Timesheet::join('users','users.nik','tb_timesheet.nik')->select(DB::raw('CASE WHEN point_mandays IS NULL THEN 0 ELSE point_mandays END AS point_mandays'),'users.name','tb_timesheet.nik','task')->selectRaw('MONTH(start_date) AS month_number')->where('status_karyawan','!=','dummy')->where(function ($query) use ($arrayMonth) {
                                            foreach ($arrayMonth as $month) {
                                                $query->orWhereRaw("MONTH(start_date) = $month");
                                            }
                                        });

                $sumMandays = $sumMandays->whereIn('tb_timesheet.nik',$listGroup);
                $sumMandays = $sumMandays->where('status','Done');

                if (is_null($sumMandays)) {
                    $isNeedOtherUser = true;
                }else{
                    $isNeedOtherUser = false;
                }

                if (is_null($sumMandays)) {
                    if (isset($request->pic[0])) {
                        $sumMandays         = $sumMandays->whereIn('tb_timesheet.nik',$request->pic);
                        $getLeavingPermit   = $getLeavingPermit->whereIn('tb_cuti.nik',$request->pic)->get();
                        $getPermit          = $getPermit->whereIn('tb_timesheet_permit.nik',$request->pic)->get();

                        $getUserByGroup = $getUserByGroup
                                            ->whereIn('nik',$request->pic)    
                                            ->get();
                    }else{
                        $sumMandays = $sumMandays->whereIn('tb_timesheet.nik',$listGroup);
                        $getLeavingPermit   = $getLeavingPermit->whereIn('tb_cuti.nik',$listGroup)->get();
                        $getPermit          = $getPermit->whereIn('tb_timesheet_permit.nik',$listGroup)->get();

                        $getUserByGroup = $getUserByGroup->get();
                    }
                }else{
                    if (isset($request->pic[0])) {
                        $sumMandays         = $sumMandays->whereIn('tb_timesheet.nik',$request->pic);
                        $getLeavingPermit   = $getLeavingPermit->whereIn('tb_cuti.nik',$request->pic)->get();
                        $getPermit          = $getPermit->whereIn('tb_timesheet_permit.nik',$request->pic)->get();

                        $getUserByGroup = $getUserByGroup
                                            ->whereNotIn('nik', $sumMandays->get()->pluck('nik'))
                                            ->whereIn('nik',$request->pic)    
                                            ->get();
                    }else{
                        $sumMandays = $sumMandays->whereIn('tb_timesheet.nik',$listGroup);
                        $getLeavingPermit   = $getLeavingPermit->whereIn('tb_cuti.nik',$listGroup)->get();
                        $getPermit          = $getPermit->whereIn('tb_timesheet_permit.nik',$listGroup)->get();

                        $getUserByGroup = $getUserByGroup->whereNotIn('nik', $sumMandays->get()->pluck('nik'))
                                            ->get();
                    }
                }
            } else {
                $getLeavingPermit   = $getLeavingPermit->where('tb_cuti.nik',$nik)->get();
                $getPermit          = $getPermit->where('tb_timesheet_permit.nik',$nik)->get();
                $sumMandays         = Timesheet::join('users','users.nik','tb_timesheet.nik')->select('point_mandays','users.name','tb_timesheet.nik','task')->selectRaw('MONTH(start_date) AS month_number')->where('tb_timesheet.nik',$nik)->where(function ($query) use ($arrayMonth) {
                                            foreach ($arrayMonth as $month) {
                                                $query->orWhereRaw("MONTH(start_date) = $month");
                                            }
                                        });

                if (count($sumMandays->get()) == 0) {
                    $isNeedOtherUser = true;
                }else{
                    $isNeedOtherUser = false;
                }

            }
        }elseif ($cek_role->group == 'Internal Chain Management') {
            if ($cek_role->name == 'VP Internal Chain Management' || $cek_role->name == 'Supply Chain & IT Support Manager' || $cek_role->name == 'Internal Operation Support Manager' || $cek_role->name == 'Legal Compliance & Contract Doc Management') {

                if ($cek_role->name == 'Supply Chain & IT Support Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')
                    // ->where('roles.name','not like','%Manager')
                    ->where('roles.mini_group','Supply Chain & IT Support')
                    ->pluck('users.nik');
                } else if ($cek_role->name == 'Internal Operation Support Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')
                    // ->where('roles.name','not like','%Manager')
                    ->where('roles.mini_group','Internal Operation Support')
                    ->pluck('users.nik');
                } else if ($cek_role->name == 'Supply Chain Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')
                    // ->where('roles.name','not like','%Manager')
                    ->where('roles.mini_group','Supply Chain Management')
                    ->pluck('users.nik');
                } else {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')
                    // ->where('roles.name','not like','%Manager')
                    ->where('roles.group','Internal Chain Management')
                    ->pluck('users.nik');
                }
                
                $sumMandays         = Timesheet::join('users','users.nik','tb_timesheet.nik')->select(DB::raw('CASE WHEN point_mandays IS NULL THEN 0 ELSE point_mandays END AS point_mandays'),'users.name','tb_timesheet.nik','task')->selectRaw('MONTH(start_date) AS month_number')->where('status_karyawan','!=','dummy')->where(function ($query) use ($arrayMonth) {
                                            foreach ($arrayMonth as $month) {
                                                $query->orWhereRaw("MONTH(start_date) = $month");
                                            }
                                        });

                $sumMandays = $sumMandays->whereIn('tb_timesheet.nik',$listGroup);
                $sumMandays = $sumMandays->where('status','Done');

                if (is_null($sumMandays)) {
                    $isNeedOtherUser = true;
                }else{
                    $isNeedOtherUser = false;
                }

                if (is_null($sumMandays)) {
                    if (isset($request->pic[0])) {
                        $sumMandays         = $sumMandays->whereIn('tb_timesheet.nik',$request->pic);
                        $getLeavingPermit   = $getLeavingPermit->whereIn('tb_cuti.nik',$request->pic)->get();
                        $getPermit          = $getPermit->whereIn('tb_timesheet_permit.nik',$request->pic)->get();

                        $getUserByGroup = $getUserByGroup
                                            ->whereIn('nik',$request->pic)    
                                            ->get();
                    }else{
                        $sumMandays = $sumMandays->whereIn('tb_timesheet.nik',$listGroup);
                        $getLeavingPermit   = $getLeavingPermit->whereIn('tb_cuti.nik',$listGroup)->get();
                        $getPermit          = $getPermit->whereIn('tb_timesheet_permit.nik',$listGroup)->get();

                        $getUserByGroup = $getUserByGroup->get();
                    }
                }else{
                    if (isset($request->pic[0])) {
                        $sumMandays         = $sumMandays->whereIn('tb_timesheet.nik',$request->pic);
                        $getLeavingPermit   = $getLeavingPermit->whereIn('tb_cuti.nik',$request->pic)->get();
                        $getPermit          = $getPermit->whereIn('tb_timesheet_permit.nik',$request->pic)->get();

                        $getUserByGroup = $getUserByGroup
                                            ->whereNotIn('nik', $sumMandays->get()->pluck('nik'))
                                            ->whereIn('nik',$request->pic)    
                                            ->get();
                    }else{
                        $sumMandays = $sumMandays->whereIn('tb_timesheet.nik',$listGroup);
                        $getLeavingPermit   = $getLeavingPermit->whereIn('tb_cuti.nik',$listGroup)->get();
                        $getPermit          = $getPermit->whereIn('tb_timesheet_permit.nik',$listGroup)->get();

                        $getUserByGroup = $getUserByGroup->whereNotIn('nik', $sumMandays->get()->pluck('nik'))
                                            ->get();
                    }
                }
            } else {
                $getLeavingPermit   = $getLeavingPermit->where('tb_cuti.nik',$nik)->get();
                $getPermit          = $getPermit->where('tb_timesheet_permit.nik',$nik)->get();
                $sumMandays         = Timesheet::join('users','users.nik','tb_timesheet.nik')->select('point_mandays','users.name','tb_timesheet.nik','task')->selectRaw('MONTH(start_date) AS month_number')->where('tb_timesheet.nik',$nik)->where(function ($query) use ($arrayMonth) {
                                            foreach ($arrayMonth as $month) {
                                                $query->orWhereRaw("MONTH(start_date) = $month");
                                            }
                                        });
                
                if (count($sumMandays->get()) == 0) {
                    $isNeedOtherUser = true;
                }else{
                    $isNeedOtherUser = false;
                }

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

        $sumMandays = $sumMandays->get();

        $getLeavingPermitByName = collect($getLeavingPermit)->groupBy('name');
        $getPermitByName        = collect($getPermit)->groupBy('name');
        $getTaskAvailableByName = collect($sumMandays->where('task',36)->groupBy('name'));

        if (count($sumMandays) === 0) {            
            $startDate       = Carbon::now()->startOfYear()->format("Y-m-d");
            $endDate         = Carbon::now()->endOfYear()->format("Y-m-d");
            $workdays        = $this->getWorkDays($startDate,$endDate,"workdays");
            $workdays        = count($workdays["workdays"]);

            if ($isNeedOtherUser == false) {
                foreach($getUserByGroup as $value_group){
                    $arrSumPoint->push(["name"=>$value_group->name,
                        "nik"       =>$value_group->nik,
                        "actual"    =>"-",
                        "planned"   =>$countPlanned,
                        "threshold" =>"-",
                        "billable"  =>"-",
                        "percentage_billable" =>"-",
                        "deviation" =>"-",
                        "total_task"=>"-"
                    ]);
                }
            }else{
                $arrSumPoint->push(["name"=>Auth::User()->name,
                    "nik"       =>$nik,
                    "actual"    =>"-",
                    "planned"   =>$countPlanned,
                    "threshold" =>"-",
                    "billable"  =>"-",
                    "percentage_billable" =>"-",
                    "deviation" =>"-",
                    "total_task"=>"-"
                ]);                
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
            
            $status = '';
            foreach($sumPointByUser as $key_point => $valueSumPoint){
                $countByName = isset($valueSumPoint[$key_point])?$valueSumPoint[$key_point]:0;
                if (($countPlanned - $getPublicHolidayAdjustment) >= $countByName) {
                    $status = "Go Ahead";
                }else if(($countPlanned - $getPublicHolidayAdjustment) < $countByName){
                    $status = "Overtime";
                }

                $billable = isset($sumArrayPermitByName[$key_point])?$sumArrayPermitByName[$key_point]:0;
                $sumPointMandays->push([
                    "name"=>$key_point,
                    "nik"=>collect($sumMandays)->where('name',$key_point)->first()->nik,
                    "actual"=>$valueSumPoint,
                    "planned"=>$countPlanned - $getPublicHolidayAdjustment,
                    "threshold"=>number_format((float)$countThresholdFinal,1,'.',''),
                    "billable"=>number_format($valueSumPoint - $billable,2,'.',''),
                    "percentage_billable"=>number_format(($valueSumPoint - $billable)/$countPlanned*100,  2, '.', ''),
                    "deviation"=>number_format($countPlanned - $valueSumPoint, 2, '.', ''),
                    "status" => $status
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
                        "threshold" =>number_format((float)$data_uniq['threshold'],1,'.',''),
                        "billable"  =>$data_uniq['billable'],
                        "percentage_billable" =>$data_uniq['percentage_billable'] . "%",
                        "deviation" =>number_format($data_uniq['deviation'], 2, '.', ''),
                        "total_task"=>$countPointByUser[$key_uniq],
                        "status"    =>$data_uniq['status']

                    ]);
                }
            }

            if ($isNeedOtherUser == false) {
                foreach($getUserByGroup as $value_group){
                    $arrSumPoint->push(["name"=>$value_group->name,
                        "nik"       =>$value_group->nik,
                        "actual"    =>"-",
                        "planned"   =>$countPlanned - $getPublicHolidayAdjustment,
                        "threshold" =>$countThresholdFinal,
                        "billable"  =>"-",
                        "percentage_billable" =>"-",
                        "deviation" =>"-",
                        "total_task"=>"-",
                        "status"    =>"Go Ahead"
                    ]);
                }
            }
        }


        return array("data"=>$arrSumPoint);
    }

    public function getFilterCummulativeMandaysChart(Request $request)
    {
        $nik = Auth::User()->nik;
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('name', 'roles.group','mini_group')->where('user_id', $nik)->first(); 

        $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $bulan_angka = [1,2,3,4,5,6,7,8,9,10,11,12];

        $arrayMonth = collect();
        foreach($request->month as $month){
            $date = Carbon::parse($month);
            // Get the numeric representation of the month (1 to 12)
            $numericMonth = $date->month;
            // return $numericMonth;
            $arrayMonth->push($numericMonth);
        }      

        $data = DB::table('tb_timesheet')
                ->join('users','tb_timesheet.nik','users.nik')->select(DB::raw('CASE WHEN point_mandays IS NULL THEN 0 ELSE point_mandays END AS point_mandays'),'name','end_date','status','users.nik')->selectRaw('MONTH(start_date) AS month_number')->where('status_karyawan','!=','dummy')->where(function ($query) use ($arrayMonth) {
                    foreach ($arrayMonth as $month) {
                        $query->orWhereRaw("MONTH(start_date) = $month");
                    }
                });

        $startDate = Carbon::now()->startOfMonth()->format("Y-m-d");
        $endDate = Carbon::now()->endOfMonth()->format("Y-m-d");
        $workdays = $this->getWorkDays($startDate,$endDate,"workdays");
        $workdays = count($workdays['workdays']);
        $arrCummulativeMandays = collect();

        $getUserByGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')
                        ->join('roles', 'roles.id', '=', 'role_user.role_id')
                        ->select('users.name');

        if ($request->roles != "") {
            if(Str::contains($cek_role->name, 'VP')){
                $getUserByGroup = $getUserByGroup->where('roles.group',$request->roles)
                            ->where('roles.name','not like','%Manager%')
                            ->where('roles.name','not like','%VP%')
                            ->where('users.status_karyawan','!=','dummy')
                            ->whereNotIn('nik', $data->get()->pluck('nik'));
            } else {
                $getUserByGroup = $getUserByGroup->where('roles.mini_group',$cek_role->mini_group)
                            ->where('roles.name','not like','%Manager%')
                            ->where('roles.name','not like','%VP%')
                            ->where('users.status_karyawan','!=','dummy')
                            ->whereNotIn('nik', $data->get()->pluck('nik'));
            }
        } else {
            if(Str::contains($cek_role->name, 'VP')){
                $getUserByGroup = $getUserByGroup->where('roles.group',$cek_role->group)
                            ->where('roles.name','not like','%Manager%')
                            ->where('roles.name','not like','%VP%')
                            ->where('users.status_karyawan','!=','dummy')
                            ->whereNotIn('nik', $data->get()->pluck('nik'));
            } else {
                $getUserByGroup = $getUserByGroup->where('roles.mini_group',$cek_role->mini_group)
                            ->where('roles.name','not like','%Manager%')
                            ->where('roles.name','not like','%VP%')
                            ->where('users.status_karyawan','!=','dummy')
                            ->whereNotIn('nik', $data->get()->pluck('nik'));
            }
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

        if ($cek_role->group == 'Program & Project Management') {
            if ($cek_role->name == 'VP Program & Project Management' || $cek_role->name == 'Project Management Office Manager') {
                if ($cek_role->name == 'VP Program & Project Management') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Program & Project Management')->orWhere('roles.group','Human Capital Management')->pluck('nik');
                } elseif ($cek_role->name == 'Project Management Office Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Program & Project Management')->pluck('nik');
                }
                
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
        }elseif ($cek_role->group == 'Synergy System Management') {
            if ($cek_role->name == 'VP Synergy System Management' || $cek_role->name == 'Synergy System & Services Manager' || $cek_role->name == 'Synergy System Delivery Manager' || $cek_role->name == 'Synergy System Architecture Manager') {
                if ($cek_role->name == 'Synergy System & Services Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.mini_group','Synergy System & Services')->pluck('nik');
                } elseif ($cek_role->name == 'Synergy System Delivery Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.mini_group','Synergy System Delivery')->pluck('nik');
                } elseif ($cek_role->name == 'Synergy System Architecture Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.mini_group','Synergy System Architecture')->pluck('nik');
                } else {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Synergy System Management')->pluck('nik');
                }

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
        }elseif ($cek_role->group == 'Solutions & Partnership Management') {
            if ($cek_role->name == 'VP Solutions & Partnership Management' || $cek_role->name == 'Application Development Specialist Manager' || $cek_role->name == 'Customer Relation Manager') {
                if ($cek_role->name == 'Application Development Specialist Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.mini_group','Application Development Specialist')->pluck('nik');
                } else if ($cek_role->name == 'Customer Relation Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.mini_group','Customer Relationship Management')->pluck('nik');
                } else {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Solutions & Partnership Management')->pluck('nik');
                }
                
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
        }elseif ($cek_role->group == 'Human Capital Management') {
            if ($cek_role->name == 'People Operations & Services Manager' || $cek_role->name == 'VP Human Capital Management' || $cek_role->name == 'Human Capital Manager') {

                if ($cek_role->name == 'People Operations & Services Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.mini_group','People Operations & Services')->orWhere('roles.mini_group','Human Capital')->pluck('nik');
                } elseif ($cek_role->name == 'Human Capital Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.mini_group','Human Capital')->pluck('nik');
                } else {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Human Capital Management')->pluck('nik');
                }

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
        }elseif ($cek_role->group == 'Internal Chain Management') {
            if ($cek_role->name == 'VP Internal Chain Management' || $cek_role->name == 'Supply Chain & IT Support Manager' || $cek_role->name == 'Internal Operation Support Manager' || $cek_role->name == 'Legal Compliance & Contract Doc Management') {

                if ($cek_role->name == 'Supply Chain & IT Support Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')
                    // ->where('roles.name','not like','%Manager')
                    ->where('roles.mini_group','Supply Chain & IT Support')
                    ->pluck('users.nik');
                } else if ($cek_role->name == 'Internal Operation Support Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')
                    // ->where('roles.name','not like','%Manager')
                    ->where('roles.mini_group','Internal Operation Support')
                    ->pluck('users.nik');
                } else if ($cek_role->name == 'Supply Chain Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')
                    // ->where('roles.name','not like','%Manager')
                    ->where('roles.mini_group','Supply Chain Management')
                    ->pluck('users.nik');
                } else {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')
                    // ->where('roles.name','not like','%Manager')
                    ->where('roles.group','Internal Chain Management')
                    ->pluck('users.nik');
                }

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
                foreach(User::select('name')->whereIn('nik',$listGroup)->get() as $name_pic){
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
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('name', 'roles.group','mini_group')->where('user_id', $nik)->first(); 

        $data = DB::table('tb_timesheet')
                ->join('users','tb_timesheet.nik','users.nik')->select('name','point_mandays','end_date','status','users.nik')->where('status_karyawan','!=','dummy')->selectRaw('MONTH(start_date) AS month_number');

        if (is_null($request->year)) {
            $data = $data->whereYear('start_date',date('Y'));
        }else{
            $data = $data->whereYear('start_date',$request->year); 
        }

        if (isset($request->month_select)) {
            $month_number = collect();

            $data = $data->whereMonth('start_date',$request->month_select);

            $month_number = $month_number->push($request->month_select);

            $startDate = Carbon::createFromDate($request->year, 1, 1);
            $startDate->month($request->month_select);

            $endDate = Carbon::createFromDate($request->year, 1, 1);
            $endDate->month($request->month_select);

            $startDateFinal = $startDate->startOfMonth()->format("Y-m-d");
            $endDateFinal   = $endDate->endOfMonth()->format("Y-m-d");
        }else{
            // $data = $data->whereMonth('start_date',Carbon::now()->month);
            $month_number = [1,2,3,4,5,6,7,8,9,10,11,12];

            $month_awal = $request->month[0];
            $month_formatted = Carbon::parse($month_awal)->month;

            $startDate = Carbon::createFromDate($request->year, 1, 1);
            $startDate->month($month_formatted);

            $endDate = Carbon::createFromDate($request->year, 1, 1);
            $endDate->month($month_formatted);

            $startDateFinal = $startDate->startOfMonth()->format("Y-m-d");
            $endDateFinal   = $endDate->endOfMonth()->format("Y-m-d");
        }

        $workdays = $this->getWorkDays($startDateFinal,$endDateFinal)["workdays"]->values()->count();

        $getUserByGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')
                        ->join('roles', 'roles.id', '=', 'role_user.role_id')
                        ->select('users.name');

        if ($request->roles != "") {
            if(Str::contains($cek_role->name, 'VP')){
                $getUserByGroup = $getUserByGroup->where('roles.group',$request->roles)
                            ->where('roles.name','not like','%Manager%')
                            ->where('roles.name','not like','%VP%')
                            ->where('users.status_karyawan','!=','dummy')
                            ->whereNotIn('nik', $data->get()->pluck('nik'))
                            ->get(); 
            } else {
                $getUserByGroup = $getUserByGroup->where('roles.mini_group',$cek_role->mini_group)
                            ->where('roles.name','not like','%Manager%')
                            ->where('roles.name','not like','%VP%')
                            ->where('users.status_karyawan','!=','dummy')
                            ->whereNotIn('nik', $data->get()->pluck('nik'))
                            ->get(); 
            }

            return $getUserByGroup;
        } else {
            if(Str::contains($cek_role->name, 'VP')){
                $getUserByGroup = $getUserByGroup->where('roles.group',$cek_role->group)
                            ->where('roles.name','not like','%Manager%')
                            ->where('roles.name','not like','%VP%')
                            ->where('users.status_karyawan','!=','dummy')
                            ->whereNotIn('nik', $data->get()->pluck('nik'))
                            ->get(); 
            } else {
                $getUserByGroup = $getUserByGroup->where('roles.mini_group',$cek_role->mini_group)
                            ->where('roles.name','not like','%Manager%')
                            ->where('roles.name','not like','%VP%')
                            ->where('users.status_karyawan','!=','dummy')
                            ->whereNotIn('nik', $data->get()->pluck('nik'))
                            ->get(); 
            }
        }
        

        // if ($request->roles != "") {
        //     $getUserByGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')
        //             ->join('roles', 'roles.id', '=', 'role_user.role_id')
        //             ->select('users.name')
        //             ->where('roles.group',$request->roles)
        //             ->where('roles.name','not like','%Manager%')
        //             ->where('roles.name','not like','%MSM Helpdesk%')
        //             ->where('roles.name','not like','%MSM Lead Helpdesk%')
        //             ->where('users.status_delete','-')
        //             ->get();
        // }else{
        //     $getUserByGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')
        //             ->join('roles', 'roles.id', '=', 'role_user.role_id')
        //             ->select('users.name')
        //             ->where('roles.group',$cek_role->group)
        //             ->where('roles.name','not like','%Manager%')
        //             ->where('roles.name','not like','%MSM Helpdesk%')
        //             ->where('roles.name','not like','%MSM Lead Helpdesk%')
        //             ->where('users.status_delete','-')
        //             ->get();
        // }     

        if ($cek_role->group == 'Program & Project Management') {
            if ($cek_role->name == 'VP Program & Project Management' || $cek_role->name == 'Project Management Office Manager') {
                if ($cek_role->name == 'VP Program & Project Management') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Program & Project Management')->orWhere('roles.group','Human Capital Management')->pluck('nik');
                } elseif ($cek_role->name == 'Project Management Office Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Program & Project Management')->pluck('nik');
                }
                
                $data = $data->whereIn('tb_timesheet.nik',$listGroup)->where('status','Done')->get();

                $data = $data->groupBy('month_number');

                $arrMonth = [1,2,3,4,5,6,7,8,9,10,11,12];

                $arrMonthMandays = [0,0,0,0,0,0,0,0,0,0,0,0];

                foreach($arrMonthMandays as $key_mandays => $valueMandays){
                    foreach($month_number as $key_month => $value_ArrMonth)
                    if ($key_month == $key_mandays) {
                        $arrMonthMandays[$value_ArrMonth-1] = $arrMonthMandays[$value_ArrMonth-1]+$workdays;
                    }else{
                        $arrMonthMandays[$value_ArrMonth-1] = $arrMonthMandays[$value_ArrMonth-1]; 
                    }
                }

                foreach($data as $key => $value){
                    $hasil_prosentase  = array($key => 0);
                    $hasil_remaining   = array($key => 0);
                    $hasil_over        = array($key => 0);

                    $arrName = collect();
                    $arrProsentaseByUser = collect();
                    $arrRemainingByUser = collect();
                    $arrOverByUser = collect();
                    $arrFinalProsentaseByUser = collect();
                    $arrFinalRemainingByUser = collect();
                    $arrFinalOverByUser = collect();

                    foreach($value as $datas){
                        $arrName->push($datas->name);
                    }

                    foreach($getUserByGroup as $value_group){
                        $arrName->push($value_group->name);
                    }

                    foreach($arrName->unique() as $key_name => $value_name){
                        $arrProsentaseByUser->put($value_name,0); 
                        $arrRemainingByUser->put($value_name,0);
                        $arrOverByUser->put($value_name,0);
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
                        if (round((float)$value_byUsers / $arrMonthMandays[$key-1] * 100,2) >= 100) {
                            $arrFinalProsentaseByUser->push(100);
                        } else {
                            $arrFinalProsentaseByUser->push(round((float)$value_byUsers / $arrMonthMandays[$key-1] * 100,2));
                        }

                        if (round((float)$value_byUsers / $arrMonthMandays[$key-1] * 100,2) >= 100) {
                            $arrFinalRemainingByUser->push(0);
                        } else {
                            $arrFinalRemainingByUser->push(100 - (round(($value_byUsers / $arrMonthMandays[$key-1]) * 100,2)));
                        }

                        if (round((float)$value_byUsers / $arrMonthMandays[$key-1] * 100,2) <= 100) {
                            $arrFinalOverByUser->push(0);
                        } else {
                            $arrFinalOverByUser->push(round((float)$value_byUsers / $arrMonthMandays[$key-1] * 100,2) - 100);
                        }
                    }

                    foreach($value as $datas){
                        foreach($hasil_prosentase as $key_prosentase => $value_prosentase){
                            $hasil_prosentase[$key_prosentase] = $arrFinalProsentaseByUser;
                        }

                        foreach($hasil_remaining as $key_remaining => $value_remaining){
                            $hasil_remaining[$key_remaining] = $arrFinalRemainingByUser;
                        }

                        foreach ($hasil_over as $key_over => $value_over) {
                            $hasil_over[$key_over] = $arrFinalOverByUser;
                        }
                    }

                    foreach($arrMonth as $keys => $month){
                        if ($month == $key) {
                            $arrMonth[$keys] = array($key=>collect([
                                "arrName"=>array($arrName->unique()),
                                "label"=>array(
                                    "Prosentase"=>$hasil_prosentase[$key_prosentase],
                                    "Remaining"=>$hasil_remaining[$key_remaining],
                                    "Over"=>$hasil_over[$key_over]
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

                            if (round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2) >= 100) {
                                $prosentase = 100;
                            } else {
                                $prosentase = round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2);
                            }

                            if (round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2) >= 100) {
                                $remaining = 0;
                            } else {
                                $remaining = 100 - (round((($hasil_remaining[$key] / $EffectiveMandaysMonthly) * 100),2));
                            }

                            if (round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2) <= 100) {
                                $over = 0;
                            } else {
                                $over = (round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2) - 100);
                            }

                            $arrMonth[$keys] = array($key=>collect([
                                "arrName"=>array($arrName),
                                "label"=>array(
                                    "Prosentase"=>array($prosentase),
                                    "Remaining"=>array($remaining),
                                    "Over"=>array($over)
                                )
                            ])); 
                        }
                    }
                }
            }
        }elseif ($cek_role->group == 'Synergy System Management') {
            if ($cek_role->name == 'VP Synergy System Management' || $cek_role->name == 'Synergy System & Services Manager' || $cek_role->name == 'Synergy System Delivery Manager' || $cek_role->name == 'Synergy System Architecture Manager') {
                if ($cek_role->name == 'Synergy System & Services Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.mini_group','Synergy System & Services')->pluck('nik');
                } elseif ($cek_role->name == 'Synergy System Delivery Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.mini_group','Synergy System Delivery')->pluck('nik');
                } elseif ($cek_role->name == 'Synergy System Architecture Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.mini_group','Synergy System Architecture')->pluck('nik');
                } else {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Synergy System Management')->pluck('nik');
                }
                
                $data = $data->whereIn('tb_timesheet.nik',$listGroup)->where('status','Done')->get();

                $data = $data->groupBy('month_number');

                $arrMonth = [1,2,3,4,5,6,7,8,9,10,11,12];

                $arrMonthMandays = [0,0,0,0,0,0,0,0,0,0,0,0];

                foreach($arrMonthMandays as $key_mandays => $valueMandays){
                    foreach($month_number as $key_month => $value_ArrMonth)
                    if ($key_month == $key_mandays) {
                        $arrMonthMandays[$value_ArrMonth-1] = $arrMonthMandays[$value_ArrMonth-1]+$workdays;
                    }else{
                        $arrMonthMandays[$value_ArrMonth-1] = $arrMonthMandays[$value_ArrMonth-1]; 
                    }
                }

                foreach($data as $key => $value){
                    $hasil_prosentase  = array($key => 0);
                    $hasil_remaining   = array($key => 0);
                    $hasil_over        = array($key => 0);

                    $arrName = collect();
                    $arrProsentaseByUser = collect();
                    $arrRemainingByUser = collect();
                    $arrOverByUser = collect();
                    $arrFinalProsentaseByUser = collect();
                    $arrFinalRemainingByUser = collect();
                    $arrFinalOverByUser = collect();

                    foreach($value as $datas){
                        $arrName->push($datas->name);
                    }

                    foreach($getUserByGroup as $value_group){
                        $arrName->push($value_group->name);
                    }

                    foreach($arrName->unique() as $key_name => $value_name){
                        $arrProsentaseByUser->put($value_name,0); 
                        $arrRemainingByUser->put($value_name,0);
                        $arrOverByUser->put($value_name,0);
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
                        if (round((float)$value_byUsers / $arrMonthMandays[$key-1] * 100,2) >= 100) {
                            $arrFinalProsentaseByUser->push(100);
                        } else {
                            $arrFinalProsentaseByUser->push(round((float)$value_byUsers / $arrMonthMandays[$key-1] * 100,2));
                        }

                        if (round((float)$value_byUsers / $arrMonthMandays[$key-1] * 100,2) >= 100) {
                            $arrFinalRemainingByUser->push(0);
                        } else {
                            $arrFinalRemainingByUser->push(100 - (round(($value_byUsers / $arrMonthMandays[$key-1]) * 100,2)));
                        }

                        if (round((float)$value_byUsers / $arrMonthMandays[$key-1] * 100,2) <= 100) {
                            $arrFinalOverByUser->push(0);
                        } else {
                            $arrFinalOverByUser->push(round((float)$value_byUsers / $arrMonthMandays[$key-1] * 100,2) - 100);
                        }
                    }

                    foreach($value as $datas){
                        foreach($hasil_prosentase as $key_prosentase => $value_prosentase){
                            $hasil_prosentase[$key_prosentase] = $arrFinalProsentaseByUser;
                        }

                        foreach($hasil_remaining as $key_remaining => $value_remaining){
                            $hasil_remaining[$key_remaining] = $arrFinalRemainingByUser;
                        }

                        foreach ($hasil_over as $key_over => $value_over) {
                            $hasil_over[$key_over] = $arrFinalOverByUser;
                        }
                    }

                    foreach($arrMonth as $keys => $month){
                        if ($month == $key) {
                            $arrMonth[$keys] = array($key=>collect([
                                "arrName"=>array($arrName->unique()),
                                "label"=>array(
                                    "Prosentase"=>$hasil_prosentase[$key_prosentase],
                                    "Remaining"=>$hasil_remaining[$key_remaining],
                                    "Over"=>$hasil_over[$key_over]
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

                            if (round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2) >= 100) {
                                $prosentase = 100;
                            } else {
                                $prosentase = round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2);
                            }

                            if (round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2) >= 100) {
                                $remaining = 0;
                            } else {
                                $remaining = 100 - (round((($hasil_remaining[$key] / $EffectiveMandaysMonthly) * 100),2));
                            }

                            if (round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2) <= 100) {
                                $over = 0;
                            } else {
                                $over = (round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2) - 100);
                            }

                            $arrMonth[$keys] = array($key=>collect([
                                "arrName"=>array($arrName),
                                "label"=>array(
                                    "Prosentase"=>array($prosentase),
                                    "Remaining"=>array($remaining),
                                    "Over"=>array($over)
                                )
                            ])); 
                        }
                    }
                }
            }
        }elseif ($cek_role->group == 'Solutions & Partnership Management') {
            if ($cek_role->name == 'VP Solutions & Partnership Management' || $cek_role->name == 'Application Development Specialist Manager' || $cek_role->name == 'Customer Relation Manager') {
                if ($cek_role->name == 'Application Development Specialist Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.mini_group','Application Development Specialist')->pluck('nik');
                } else if ($cek_role->name == 'Customer Relation Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.mini_group','Customer Relationship Management')->pluck('nik');
                } else {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Solutions & Partnership Management')->pluck('nik');
                }

                $data = $data->whereIn('tb_timesheet.nik',$listGroup)->where('status','Done')->get();

                $data = $data->groupBy('month_number');

                $arrMonth = [1,2,3,4,5,6,7,8,9,10,11,12];

                $arrMonthMandays = [0,0,0,0,0,0,0,0,0,0,0,0];

                foreach($arrMonthMandays as $key_mandays => $valueMandays){
                    foreach($month_number as $key_month => $value_ArrMonth)
                    if ($key_month == $key_mandays) {
                        $arrMonthMandays[$key_mandays] = $arrMonthMandays[$key_mandays]+$workdays;
                    }else{
                        $arrMonthMandays[$value_ArrMonth-1] = $arrMonthMandays[$value_ArrMonth-1]; 
                    }
                }

                foreach($data as $key => $value){
                    $hasil_prosentase  = array($key => 0);
                    $hasil_remaining   = array($key => 0);
                    $hasil_over        = array($key => 0);

                    $arrName = collect();
                    $arrProsentaseByUser = collect();
                    $arrRemainingByUser = collect();
                    $arrOverByUser = collect();
                    $arrFinalProsentaseByUser = collect();
                    $arrFinalRemainingByUser = collect();
                    $arrFinalOverByUser = collect();

                    foreach($value as $datas){
                        $arrName->push($datas->name);
                    }

                    foreach($getUserByGroup as $value_group){
                        $arrName->push($value_group->name);
                    }

                    foreach($arrName->unique() as $key_name => $value_name){
                        $arrProsentaseByUser->put($value_name,0); 
                        $arrRemainingByUser->put($value_name,0);
                        $arrOverByUser->put($value_name,0);
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
                        if (round((float)$value_byUsers / $arrMonthMandays[$key-1] * 100,2) >= 100) {
                            $arrFinalProsentaseByUser->push(100);
                        } else {
                            $arrFinalProsentaseByUser->push(round((float)$value_byUsers / $arrMonthMandays[$key-1] * 100,2));
                        }

                        if (round((float)$value_byUsers / $arrMonthMandays[$key-1] * 100,2) >= 100) {
                            $arrFinalRemainingByUser->push(0);
                        } else {
                            $arrFinalRemainingByUser->push(100 - (round(($value_byUsers / $arrMonthMandays[$key-1]) * 100,2)));
                        }

                        if (round((float)$value_byUsers / $arrMonthMandays[$key-1] * 100,2) <= 100) {
                            $arrFinalOverByUser->push(0);
                        } else {
                            $arrFinalOverByUser->push(round((float)$value_byUsers / $arrMonthMandays[$key-1] * 100,2) - 100);
                        }
                    }

                    foreach($value as $datas){
                        foreach($hasil_prosentase as $key_prosentase => $value_prosentase){
                            $hasil_prosentase[$key_prosentase] = $arrFinalProsentaseByUser;
                        }

                        foreach($hasil_remaining as $key_remaining => $value_remaining){
                            $hasil_remaining[$key_remaining] = $arrFinalRemainingByUser;
                        }

                        foreach ($hasil_over as $key_over => $value_over) {
                            $hasil_over[$key_over] = $arrFinalOverByUser;
                        }
                    }

                    foreach($arrMonth as $keys => $month){
                        if ($month == $key) {
                            $arrMonth[$keys] = array($key=>collect([
                                "arrName"=>array($arrName->unique()),
                                "label"=>array(
                                    "Prosentase"=>$hasil_prosentase[$key_prosentase],
                                    "Remaining"=>$hasil_remaining[$key_remaining],
                                    "Over"=>$hasil_over[$key_over]
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

                            if (round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2) >= 100) {
                                $prosentase = 100;
                            } else {
                                $prosentase = round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2);
                            }

                            if (round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2) >= 100) {
                                $remaining = 0;
                            } else {
                                $remaining = 100 - (round((($hasil_remaining[$key] / $EffectiveMandaysMonthly) * 100),2));
                            }

                            if (round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2) <= 100) {
                                $over = 0;
                            } else {
                                $over = (round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2) - 100);
                            }

                            $arrMonth[$keys] = array($key=>collect([
                                "arrName"=>array($arrName),
                                "label"=>array(
                                    "Prosentase"=>array($prosentase),
                                    "Remaining"=>array($remaining),
                                    "Over"=>array($over)
                                )
                            ])); 
                        }
                    }
                }
            }
        }elseif ($cek_role->group == 'Human Capital Management') {
            if ($cek_role->name == 'People Operations & Services Manager' || $cek_role->name == 'VP Human Capital Management' || $cek_role->name == 'Human Capital Manager') {

                if ($cek_role->name == 'People Operations & Services Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.mini_group','People Operations & Services')->orWhere('roles.mini_group','Human Capital')->pluck('nik');
                } elseif ($cek_role->name == 'Human Capital Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.mini_group','Human Capital')->pluck('nik');
                } else {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Human Capital Management')->pluck('nik');
                }
                
                $data = $data->whereIn('tb_timesheet.nik',$listGroup)->where('status','Done')->get();

                $data = $data->groupBy('month_number');

                $arrMonth = [1,2,3,4,5,6,7,8,9,10,11,12];

                $arrMonthMandays = [0,0,0,0,0,0,0,0,0,0,0,0];

                foreach($arrMonthMandays as $key_mandays => $valueMandays){
                    foreach($month_number as $key_month => $value_ArrMonth)
                    if ($key_month == $key_mandays) {
                        $arrMonthMandays[$value_ArrMonth-1] = $arrMonthMandays[$value_ArrMonth-1]+$workdays;
                    }else{
                        $arrMonthMandays[$value_ArrMonth-1] = $arrMonthMandays[$value_ArrMonth-1]; 
                    }
                }

                foreach($data as $key => $value){
                    $hasil_prosentase  = array($key => 0);
                    $hasil_remaining   = array($key => 0);
                    $hasil_over        = array($key => 0);

                    $arrName = collect();
                    $arrProsentaseByUser = collect();
                    $arrRemainingByUser = collect();
                    $arrOverByUser = collect();
                    $arrFinalProsentaseByUser = collect();
                    $arrFinalRemainingByUser = collect();
                    $arrFinalOverByUser = collect();

                    foreach($value as $datas){
                        $arrName->push($datas->name);
                    }

                    foreach($getUserByGroup as $value_group){
                        $arrName->push($value_group->name);
                    }

                    foreach($arrName->unique() as $key_name => $value_name){
                        $arrProsentaseByUser->put($value_name,0); 
                        $arrRemainingByUser->put($value_name,0);
                        $arrOverByUser->put($value_name,0);
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
                        if (round((float)$value_byUsers / $arrMonthMandays[$key-1] * 100,2) >= 100) {
                            $arrFinalProsentaseByUser->push(100);
                        } else {
                            $arrFinalProsentaseByUser->push(round((float)$value_byUsers / $arrMonthMandays[$key-1] * 100,2));
                        }

                        if (round((float)$value_byUsers / $arrMonthMandays[$key-1] * 100,2) >= 100) {
                            $arrFinalRemainingByUser->push(0);
                        } else {
                            $arrFinalRemainingByUser->push(100 - (round(($value_byUsers / $arrMonthMandays[$key-1]) * 100,2)));
                        }

                        if (round((float)$value_byUsers / $arrMonthMandays[$key-1] * 100,2) <= 100) {
                            $arrFinalOverByUser->push(0);
                        } else {
                            $arrFinalOverByUser->push(round((float)$value_byUsers / $arrMonthMandays[$key-1] * 100,2) - 100);
                        }
                    }

                    foreach($value as $datas){
                        foreach($hasil_prosentase as $key_prosentase => $value_prosentase){
                            $hasil_prosentase[$key_prosentase] = $arrFinalProsentaseByUser;
                        }

                        foreach($hasil_remaining as $key_remaining => $value_remaining){
                            $hasil_remaining[$key_remaining] = $arrFinalRemainingByUser;
                        }

                        foreach ($hasil_over as $key_over => $value_over) {
                            $hasil_over[$key_over] = $arrFinalOverByUser;
                        }
                    }

                    foreach($arrMonth as $keys => $month){
                        if ($month == $key) {
                            $arrMonth[$keys] = array($key=>collect([
                                "arrName"=>array($arrName->unique()),
                                "label"=>array(
                                    "Prosentase"=>$hasil_prosentase[$key_prosentase],
                                    "Remaining"=>$hasil_remaining[$key_remaining],
                                    "Over"=>$hasil_over[$key_over]
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

                            if (round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2) >= 100) {
                                $prosentase = 100;
                            } else {
                                $prosentase = round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2);
                            }

                            if (round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2) >= 100) {
                                $remaining = 0;
                            } else {
                                $remaining = 100 - (round((($hasil_remaining[$key] / $EffectiveMandaysMonthly) * 100),2));
                            }

                            if (round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2) <= 100) {
                                $over = 0;
                            } else {
                                $over = (round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2) - 100);
                            }

                            $arrMonth[$keys] = array($key=>collect([
                                "arrName"=>array($arrName),
                                "label"=>array(
                                    "Prosentase"=>array($prosentase),
                                    "Remaining"=>array($remaining),
                                    "Over"=>array($over)
                                )
                            ])); 
                        }
                    }
                }
            }
        }elseif ($cek_role->group == 'Internal Chain Management') {
            if ($cek_role->name == 'VP Internal Chain Management' || $cek_role->name == 'Supply Chain & IT Support Manager' || $cek_role->name == 'Internal Operation Support Manager' || $cek_role->name == 'Legal Compliance & Contract Doc Management') {

                if ($cek_role->name == 'Supply Chain & IT Support Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')
                    // ->where('roles.name','not like','%Manager')
                    ->where('roles.mini_group','Supply Chain & IT Support')
                    ->pluck('users.nik');
                } else if ($cek_role->name == 'Internal Operation Support Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')
                    // ->where('roles.name','not like','%Manager')
                    ->where('roles.mini_group','Internal Operation Support')
                    ->pluck('users.nik');
                } else if ($cek_role->name == 'Supply Chain Manager') {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')
                    // ->where('roles.name','not like','%Manager')
                    ->where('roles.mini_group','Supply Chain Management')
                    ->pluck('users.nik');
                } else {
                    $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')
                    // ->where('roles.name','not like','%Manager')
                    ->where('roles.group','Internal Chain Management')
                    ->pluck('users.nik');
                }

                $data = $data->whereIn('tb_timesheet.nik',$listGroup)->where('status','Done')->get()->groupBy('month_number');

                $arrMonth = [1,2,3,4,5,6,7,8,9,10,11,12];

                $arrMonthMandays = [0,0,0,0,0,0,0,0,0,0,0,0];

                foreach($arrMonthMandays as $key_mandays => $valueMandays){
                    foreach($month_number as $key_month => $value_ArrMonth)
                    if ($key_month == $key_mandays) {
                        $arrMonthMandays[$value_ArrMonth-1] = $arrMonthMandays[$value_ArrMonth-1]+$workdays;
                    }else{
                        $arrMonthMandays[$value_ArrMonth-1] = $arrMonthMandays[$value_ArrMonth-1]; 
                    }
                }

                foreach($data as $key => $value){
                    $hasil_prosentase  = array($key => 0);
                    $hasil_remaining   = array($key => 0);
                    $hasil_over        = array($key => 0);

                    $arrName = collect();
                    $arrProsentaseByUser = collect();
                    $arrRemainingByUser = collect();
                    $arrOverByUser = collect();
                    $arrFinalProsentaseByUser = collect();
                    $arrFinalRemainingByUser = collect();
                    $arrFinalOverByUser = collect();

                    foreach($value as $datas){
                        $arrName->push($datas->name);
                    }

                    foreach($getUserByGroup as $value_group){
                        $arrName->push($value_group->name);
                    }

                    foreach($arrName->unique() as $key_name => $value_name){
                        $arrProsentaseByUser->put($value_name,0); 
                        $arrRemainingByUser->put($value_name,0);
                        $arrOverByUser->put($value_name,0);
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
                        if (round((float)$value_byUsers / $arrMonthMandays[$key-1] * 100,2) >= 100) {
                            $arrFinalProsentaseByUser->push(100);
                        } else {
                            $arrFinalProsentaseByUser->push(round((float)$value_byUsers / $arrMonthMandays[$key-1] * 100,2));
                        }

                        if (round((float)$value_byUsers / $arrMonthMandays[$key-1] * 100,2) >= 100) {
                            $arrFinalRemainingByUser->push(0);
                        } else {
                            $arrFinalRemainingByUser->push(100 - (round(($value_byUsers / $arrMonthMandays[$key-1]) * 100,2)));
                        }

                        if (round((float)$value_byUsers / $arrMonthMandays[$key-1] * 100,2) <= 100) {
                            $arrFinalOverByUser->push(0);
                        } else {
                            $arrFinalOverByUser->push(round((float)$value_byUsers / $arrMonthMandays[$key-1] * 100,2) - 100);
                        }
                    }

                    foreach($value as $datas){
                        foreach($hasil_prosentase as $key_prosentase => $value_prosentase){
                            $hasil_prosentase[$key_prosentase] = $arrFinalProsentaseByUser;
                        }

                        foreach($hasil_remaining as $key_remaining => $value_remaining){
                            $hasil_remaining[$key_remaining] = $arrFinalRemainingByUser;
                        }

                        foreach ($hasil_over as $key_over => $value_over) {
                            $hasil_over[$key_over] = $arrFinalOverByUser;
                        }
                    }

                    foreach($arrMonth as $keys => $month){
                        if ($month == $key) {
                            $arrMonth[$keys] = array($key=>collect([
                                "arrName"=>array($arrName->unique()),
                                "label"=>array(
                                    "Prosentase"=>$hasil_prosentase[$key_prosentase],
                                    "Remaining"=>$hasil_remaining[$key_remaining],
                                    "Over"=>$hasil_over[$key_over]
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

                            if (round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2) >= 100) {
                                $prosentase = 100;
                            } else {
                                $prosentase = round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2);
                            }

                            if (round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2) >= 100) {
                                $remaining = 0;
                            } else {
                                $remaining = 100 - (round((($hasil_remaining[$key] / $EffectiveMandaysMonthly) * 100),2));
                            }

                            if (round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2) <= 100) {
                                $over = 0;
                            } else {
                                $over = (round((float)($hasil_prosentase[$key] / $EffectiveMandaysMonthly) * 100,2) - 100);
                            }

                            $arrMonth[$keys] = array($key=>collect([
                                "arrName"=>array($arrName),
                                "label"=>array(
                                    "Prosentase"=>array($prosentase),
                                    "Remaining"=>array($remaining),
                                    "Over"=>array($over)
                                )
                            ])); 
                        }
                    }
                }
            }
        }else {
            $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group',$request->roles)->pluck('nik');

            $data = $data->whereIn('tb_timesheet.nik',$listGroup)->where('status','Done')->get();

            $data = $data->groupBy('month_number');

            $arrMonth = [1,2,3,4,5,6,7,8,9,10,11,12];

            $arrMonthMandays = [0,0,0,0,0,0,0,0,0,0,0,0];

            foreach($arrMonthMandays as $key_mandays => $valueMandays){
                foreach($month_number as $key_month => $value_ArrMonth)
                if ($key_month == $key_mandays) {
                    $arrMonthMandays[$value_ArrMonth-1] = $arrMonthMandays[$value_ArrMonth-1]+$workdays;
                }else{
                    $arrMonthMandays[$value_ArrMonth-1] = $arrMonthMandays[$value_ArrMonth-1]; 
                }
            }

            foreach($data as $key => $value){
                $hasil_prosentase  = array($key => 0);
                $hasil_remaining   = array($key => 0);
                $hasil_over        = array($key => 0);

                $arrName = collect();
                $arrProsentaseByUser = collect();
                $arrRemainingByUser = collect();
                $arrOverByUser = collect();
                $arrFinalProsentaseByUser = collect();
                $arrFinalRemainingByUser = collect();
                $arrFinalOverByUser = collect();

                foreach($value as $datas){
                    $arrName->push($datas->name);
                }

                foreach($getUserByGroup as $value_group){
                    $arrName->push($value_group->name);
                }

                foreach($arrName->unique() as $key_name => $value_name){
                    $arrProsentaseByUser->put($value_name,0); 
                    $arrRemainingByUser->put($value_name,0);
                    $arrOverByUser->put($value_name,0);
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
                    if (round((float)$value_byUsers / $arrMonthMandays[$key-1] * 100,2) >= 100) {
                        $arrFinalProsentaseByUser->push(100);
                    } else {
                        $arrFinalProsentaseByUser->push(round((float)$value_byUsers / $arrMonthMandays[$key-1] * 100,2));
                    }

                    if (round((float)$value_byUsers / $arrMonthMandays[$key-1] * 100,2) >= 100) {
                        $arrFinalRemainingByUser->push(0);
                    } else {
                        $arrFinalRemainingByUser->push(100 - (round(($value_byUsers / $arrMonthMandays[$key-1]) * 100,2)));
                    }

                    if (round((float)$value_byUsers / $arrMonthMandays[$key-1] * 100,2) <= 100) {
                        $arrFinalOverByUser->push(0);
                    } else {
                        $arrFinalOverByUser->push(round((float)$value_byUsers / $arrMonthMandays[$key-1] * 100,2) - 100);
                    }
                }

                foreach($value as $datas){
                    foreach($hasil_prosentase as $key_prosentase => $value_prosentase){
                        $hasil_prosentase[$key_prosentase] = $arrFinalProsentaseByUser;
                    }

                    foreach($hasil_remaining as $key_remaining => $value_remaining){
                        $hasil_remaining[$key_remaining] = $arrFinalRemainingByUser;
                    }

                    foreach ($hasil_over as $key_over => $value_over) {
                        $hasil_over[$key_over] = $arrFinalOverByUser;
                    }
                }

                foreach($arrMonth as $keys => $month){
                    if ($month == $key) {
                        $arrMonth[$keys] = array($key=>collect([
                            "arrName"=>array($arrName->unique()),
                            "label"=>array(
                                "Prosentase"=>$hasil_prosentase[$key_prosentase],
                                "Remaining"=>$hasil_remaining[$key_remaining],
                                "Over"=>$hasil_over[$key_over]
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
                ->where('status_karyawan','!=','dummy')
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
            $data = $data;
        }else{
            $data = $data->whereIn('status',$request->status);                    
        }

        if ($request->schedule[0] === null) {
            $data = $data;
        }else{
            $data = $data->whereIn('schedule',$request->schedule);                    
        }

        if ($cek_role->group == 'Program & Project Management') {
            if ($cek_role->name == 'VP Program & Project Management' || $cek_role->name == 'Project Management Office Manager') {
                if ($request->pic[0] === null) {
                    if ($cek_role->name == 'VP Program & Project Management') {
                        $data = $data->where('roles.group',$cek_role->group)->orWhere('roles.group','Human Capital Management');
                    } else {
                        $data = $data->where('roles.group',$cek_role->group);
                    }
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else {
                $data = $data->where('tb_timesheet.nik',Auth::User()->nik);
            }
        }elseif ($cek_role->group == 'Synergy System Management') {
            if ($cek_role->name == 'VP Synergy System Management') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.group',$cek_role->group);
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } elseif ($cek_role->name == 'Synergy System & Services Manager') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.mini_group','Synergy System & Services');
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } elseif ($cek_role->name == 'Synergy System Delivery Manager') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.mini_group','Synergy System Delivery');
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } elseif ($cek_role->name == 'Synergy System Architecture Manager') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.mini_group','Synergy System Architecture');
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else {
                $data = $data->where('tb_timesheet.nik',Auth::User()->nik);
            }
        }elseif ($cek_role->group == 'Solutions & Partnership Management') {
            if ($cek_role->name == 'VP Solutions & Partnership Management') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.group',$cek_role->group);
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else if ($cek_role->name == 'Application Development Specialist Manager') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.mini_group','Application Development Specialist');
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else if ($cek_role->name == 'Customer Relation Manager') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.mini_group','Customer Relationship Management');
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else if ($cek_role->name == 'Product Development Specialist Manager') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.mini_group','Product Development Specialist');
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else {
               $data = $data->where('tb_timesheet.nik',Auth::User()->nik);
            }
        }elseif ($cek_role->group == 'Human Capital Management') {
            if ($cek_role->name == 'VP Human Capital Management') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.group',$cek_role->group);
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else if ($cek_role->name == 'People Operations & Services Manager') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.mini_group','People Operations & Services')->orWhere('roles.mini_group','Human Capital');
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else if ($cek_role->name == 'Human Capital Manager') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.mini_group','Human Capital');
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else {
               $data = $data->where('tb_timesheet.nik',Auth::User()->nik);
            }
        }elseif($cek_role->group == 'Internal Chain Management') {
            if ($cek_role->name == 'VP Internal Chain Management') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.group',$cek_role->group);
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else if ($cek_role->name == 'Internal Operation Support Manager') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.group','Internal Operation Support');
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else if ($cek_role->name == 'Supply Chain & IT Support Manager') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.group','Supply Chain & IT Support');
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else if ($cek_role->name == 'Supply Chain Manager') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.group','Supply Chain Management');
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
                $hasil2[$key] = round(($value/$pie)*100,2);
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
                ->where('status_karyawan','!=','dummy')
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

        if ($cek_role->group == 'Program & Project Management') {
            if ($cek_role->name == 'VP Program & Project Management' || $cek_role->name == 'Project Management Office Manager') {
                if ($request->pic[0] === null) {
                    if ($cek_role->name == 'VP Program & Project Management') {
                        $data = $data->where('roles.group',$cek_role->group)->orWhere('roles.group','Human Capital Management');
                    } else {
                        $data = $data->where('roles.group',$cek_role->group);
                    }
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else {
                $data = $data->where('tb_timesheet.nik',Auth::User()->nik);
            }
        }elseif ($cek_role->group == 'Synergy System Management') {
            if ($cek_role->name == 'VP Synergy System Management') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.group',$cek_role->group);
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } elseif ($cek_role->name == 'Synergy System & Services Manager') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.mini_group','Synergy System & Services');
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } elseif ($cek_role->name == 'Synergy System Delivery Manager') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.mini_group','Synergy System Delivery');
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } elseif ($cek_role->name == 'Synergy System Architecture Manager') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.mini_group','Synergy System Architecture');
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else {
                $data = $data->where('tb_timesheet.nik',Auth::User()->nik);
            }
        }elseif ($cek_role->group == 'Solutions & Partnership Management') {
            if ($cek_role->name == 'VP Solutions & Partnership Management') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.group',$cek_role->group);
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else if ($cek_role->name == 'Application Development Specialist Manager') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.mini_group','Application Development Specialist');
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else if ($cek_role->name == 'Customer Relation Manager') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.mini_group','Customer Relationship Management');
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else if ($cek_role->name == 'Product Development Specialist Manager') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.mini_group','Product Development Specialist');
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else {
               $data = $data->where('tb_timesheet.nik',Auth::User()->nik);
            }
        }elseif ($cek_role->group == 'Human Capital Management') {
            if ($cek_role->name == 'VP Human Capital Management') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.group',$cek_role->group);
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else if ($cek_role->name == 'People Operations & Services Manager') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.mini_group','People Operations & Services')->orWhere('roles.mini_group','Human Capital');
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else if ($cek_role->name == 'Human Capital Manager') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.mini_group','Human Capital');
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else {
               $data = $data->where('tb_timesheet.nik',Auth::User()->nik);
            }
        }elseif($cek_role->group == 'Internal Chain Management') {
            if ($cek_role->name == 'VP Internal Chain Management') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.group',$cek_role->group);
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else if ($cek_role->name == 'Internal Operation Support Manager') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.group','Internal Operation Support');
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else if ($cek_role->name == 'Supply Chain & IT Support Manager') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.group','Supply Chain & IT Support');
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else if ($cek_role->name == 'Supply Chain Manager') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.group','Supply Chain Management');
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
                    $hasil2->push(round(($value/$pie)*100,2));
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
                ->where('status_karyawan','!=','dummy')
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

        if ($cek_role->group == 'Program & Project Management') {
            if ($cek_role->name == 'VP Program & Project Management' || $cek_role->name == 'Project Management Office Manager') {
                if ($request->pic[0] === null) {
                    if ($cek_role->name == 'VP Program & Project Management') {
                        $data = $data->where('roles.group',$cek_role->group)->orWhere('roles.group','Human Capital Management');
                    } else {
                        $data = $data->where('roles.group',$cek_role->group);
                    }
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else {
                $data = $data->where('tb_timesheet.nik',Auth::User()->nik);
            }
        }elseif ($cek_role->group == 'Synergy System Management') {
            if ($cek_role->name == 'VP Synergy System Management') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.group',$cek_role->group);
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } elseif ($cek_role->name == 'Synergy System & Services Manager') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.mini_group','Synergy System & Services');
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } elseif ($cek_role->name == 'Synergy System Delivery Manager') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.mini_group','Synergy System Delivery');
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } elseif ($cek_role->name == 'Synergy System Architecture Manager') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.mini_group','Synergy System Architecture');
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else {
                $data = $data->where('tb_timesheet.nik',Auth::User()->nik);
            }
        }elseif ($cek_role->group == 'Solutions & Partnership Management') {
            if ($cek_role->name == 'VP Solutions & Partnership Management') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.group',$cek_role->group);
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else if ($cek_role->name == 'Application Development Specialist Manager') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.mini_group','Application Development Specialist');
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else if ($cek_role->name == 'Customer Relation Manager') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.mini_group','Customer Relationship Management');
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else if ($cek_role->name == 'Product Development Specialist Manager') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.mini_group','Product Development Specialist');
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else {
               $data = $data->where('tb_timesheet.nik',Auth::User()->nik);
            }
        }elseif ($cek_role->group == 'Human Capital Management') {
            if ($cek_role->name == 'VP Human Capital Management') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.group',$cek_role->group);
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else if ($cek_role->name == 'People Operations & Services Manager') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.mini_group','People Operations & Services')->orWhere('roles.mini_group','Human Capital');
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else if ($cek_role->name == 'Human Capital Manager') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.mini_group','Human Capital');
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else {
               $data = $data->where('tb_timesheet.nik',Auth::User()->nik);
            }
        }elseif($cek_role->group == 'Internal Chain Management') {
            if ($cek_role->name == 'VP Internal Chain Management') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.group',$cek_role->group);
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else if ($cek_role->name == 'Internal Operation Support Manager') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.group','Internal Operation Support');
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else if ($cek_role->name == 'Supply Chain & IT Support Manager') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.group','Supply Chain & IT Support');
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else if ($cek_role->name == 'Supply Chain Manager') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.group','Supply Chain Management');
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
                $hasil2[$key] = round(($value/$pie)*100,2);
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
                ->where('status_karyawan','!=','dummy')
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

        if ($cek_role->group == 'Program & Project Management') {
            if ($cek_role->name == 'VP Program & Project Management' || $cek_role->name == 'Project Management Office Manager') {
                if ($request->pic[0] === null) {
                    if ($cek_role->name == 'VP Program & Project Management') {
                        $data = $data->where('roles.group',$cek_role->group)->orWhere('roles.group','Human Capital Management');
                    } else {
                        $data = $data->where('roles.group',$cek_role->group);
                    }
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else {
                $data = $data->where('tb_timesheet.nik',Auth::User()->nik);
            }
        }elseif ($cek_role->group == 'Synergy System Management') {
            if ($cek_role->name == 'VP Synergy System Management') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.group',$cek_role->group);
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } elseif ($cek_role->name == 'Synergy System & Services Manager') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.mini_group','Synergy System & Services');
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } elseif ($cek_role->name == 'Synergy System Delivery Manager') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.mini_group','Synergy System Delivery');
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } elseif ($cek_role->name == 'Synergy System Architecture Manager') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.mini_group','Synergy System Architecture');
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else {
                $data = $data->where('tb_timesheet.nik',Auth::User()->nik);
            }
        }elseif ($cek_role->group == 'Solutions & Partnership Management') {
            if ($cek_role->name == 'VP Solutions & Partnership Management') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.group',$cek_role->group);
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else if ($cek_role->name == 'Application Development Specialist Manager') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.mini_group','Application Development Specialist');
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else if ($cek_role->name == 'Customer Relation Manager') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.mini_group','Customer Relationship Management');
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else if ($cek_role->name == 'Product Development Specialist Manager') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.mini_group','Product Development Specialist');
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else {
               $data = $data->where('tb_timesheet.nik',Auth::User()->nik);
            }
        }elseif ($cek_role->group == 'Human Capital Management') {
            if ($cek_role->name == 'VP Human Capital Management') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.group',$cek_role->group);
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else if ($cek_role->name == 'People Operations & Services Manager') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.mini_group','People Operations & Services')->orWhere('roles.mini_group','Human Capital');
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else if ($cek_role->name == 'Human Capital Manager') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.mini_group','Human Capital');
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else {
               $data = $data->where('tb_timesheet.nik',Auth::User()->nik);
            }
        }elseif($cek_role->group == 'Internal Chain Management') {
            if ($cek_role->name == 'VP Internal Chain Management') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.group',$cek_role->group);
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else if ($cek_role->name == 'Internal Operation Support Manager') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.group','Internal Operation Support');
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else if ($cek_role->name == 'Supply Chain & IT Support Manager') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.group','Supply Chain & IT Support');
                }else{
                    $data = $data->whereIn('tb_timesheet.nik',$request->pic);
                }
            } else if ($cek_role->name == 'Supply Chain Manager') {
                if ($request->pic[0] === null) {
                    $data = $data->where('roles.group','Supply Chain Management');
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
                $hasil->push(round(($value->total_aktivitas/$count_task)*100,2));   
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

        if (isset($request->task[1])) {
            $data = $data->whereIn('tb_timesheet.task',$request->task);                        
        }else{
            $data = $data;
        }

        if (isset($request->phase[1])) {
            $data = $data->whereIn('tb_timesheet.phase',$request->phase);                                    
        }else{
            $data = $data;                                      
        }

        if (isset($request->status[1])) {
            $data = $data->whereIn('status',$request->status);                        
        }else{
            $data = $data->where('status','Done');
        }

        if (isset($request->schedule[1])) {
            $data = $data->whereIn('schedule',$request->schedule);                    
        }else{
            $data = $data;
        }

        $cek_role_manager = str_contains($cek_role->name, 'Manager');
        $cek_role_spv = str_contains($cek_role->name, 'VP');

        if ($cek_role_manager || $cek_role_spv) {
            if (isset($request->pic[0])) {
                $data = $data->whereIn('tb_timesheet.nik',$request->pic);
            }else{
                $data = $data->where('roles.group',$cek_role->group);
            }
        }else{
            $data = $data->whereIn('tb_timesheet.nik',$request->pic);
        }

        // if ($cek_role->group == 'Program & Project Management') {
        //     if ($cek_role->name == 'PMO Manager' || $cek_role->name == 'PMO SPV') {
        //         if (isset($request->pic[1]))) {
        //             $data = $data->where('roles.group',$cek_role->group);
        //         }else{
        //             $data = $data->whereIn('tb_timesheet.nik',$request->pic);
        //         }
        //     } else {
        //         $data = $data->where('tb_timesheet.nik',Auth::User()->nik);
        //     }
        // }elseif ($cek_role->group == 'DPG') {
        //     if ($cek_role->name == 'SID Manager' || $cek_role->name == 'SID SPV') {
        //         if (isset($request->pic[1]))) {
        //             $data = $data->where('roles.group',$cek_role->group);
        //         }else{
        //             $data = $data->whereIn('tb_timesheet.nik',$request->pic);
        //         }
        //     } else {
        //         $data = $data->where('tb_timesheet.nik',Auth::User()->nik);
        //     }
        // }elseif ($cek_role->group == 'Synergy System Management') {
        //     if ($cek_role->name == 'SOL Manager') {
        //         if ($request->pic[0]) {
        //             $data = $data->where('roles.group',$cek_role->group);
        //         }else{
        //             $data = $data->whereIn('tb_timesheet.nik',$request->pic);
        //         }
        //     } else {
        //        $data = $data->where('tb_timesheet.nik',Auth::User()->nik);
        //     }
        // }elseif ($cek_role->group == 'Solutions & Partnership Management') {
        //     if ($cek_role->name == 'VP Solutions & Partnership Management' || $cek_role->name == 'Application Development Specialist Manager') {
        //         if ($request->pic[0]) {
        //             $data = $data->where('roles.group',$cek_role->group);
        //         }else{
        //             $data = $data->whereIn('tb_timesheet.nik',$request->pic);
        //         }
        //     } else {
        //        $data = $data->where('tb_timesheet.nik',Auth::User()->nik);
        //     }
        // }elseif ($cek_role->group == 'hr') {
        //     if ($cek_role->name == 'HR Manager') {
        //         if ($request->pic[0]) {
        //             $data = $data->where('roles.group',$cek_role->group);
        //         }else{
        //             $data = $data->whereIn('tb_timesheet.nik',$request->pic);
        //         }
        //     } else {
        //        $data = $data->where('tb_timesheet.nik',Auth::User()->nik);
        //     }
        // }elseif($cek_role->group == 'msm') {
        //     if ($cek_role->name == 'MSM Manager' || $cek_role->name == 'MSM TS SPV') {
        //         if ($request->pic[0]) {
        //             $data = $data->where('roles.group',$cek_role->group);
        //         }else{
        //             $data = $data->whereIn('tb_timesheet.nik',$request->pic);
        //         }
        //     } else {
        //        $data = $data->where('tb_timesheet.nik',Auth::User()->nik);
        //     }
        // }

        $phase = $data->groupBy('tb_timesheet.phase')->get();

        $count_phase = $phase->sum('total_aktivitas');

        $hasil = collect();
        $label = collect();

        if (count($phase) == 0) {
            $hasil->push(0);
        }else{
            foreach ($phase as $value) {
                $label->push($value->phase_name);
                $hasil->push(round(($value->total_aktivitas/$count_phase)*100,2));   
            }
        }

        return collect(["label"=>$label,"data"=>$hasil]);
    }

    public function exportExcel(Request $request)
    {
        $nik = Auth::User()->nik;
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('name', 'roles.group')->where('user_id', $nik)->first(); 

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

        $dataTimesheet = DB::table('tb_timesheet')->join('users','users.nik','=','tb_timesheet.nik')
            ->Leftjoin('tb_timesheet_phase','tb_timesheet_phase.id','=','tb_timesheet.phase')
            ->Leftjoin('tb_timesheet_task','tb_timesheet_task.id','=','tb_timesheet.task')
            ->select('users.nik','users.name','tb_timesheet.start_date','tb_timesheet.date_add','tb_timesheet.level','tb_timesheet_task.task','tb_timesheet_phase.phase','tb_timesheet.schedule','tb_timesheet.point_mandays','tb_timesheet.status','tb_timesheet.activity','tb_timesheet.end_date','tb_timesheet.type', DB::raw("(CASE WHEN (pid = 'null') THEN '-' WHEN (pid is null) THEN '-' ELSE pid END) as pid"))
            ->selectRaw("DATE_FORMAT(tb_timesheet.date_add, '%Y-%m-%d') AS formatted_date_add")
            ->where('status_karyawan','!=','dummy')
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

        $dataTimesheet = $dataTimesheet->get();

        // return $dataTimesheet;

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
                "date_add"         =>$data->formatted_date_add,
                "start_date"       =>$data->start_date,
                "end_date"         =>$data->end_date,
                "schedule"         =>$data->schedule,
            ]);
        }

        // return $collectByName;
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
            $collectByName = $collectByName->sortBy('name')->sortBy('date_add');

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
            $detailSheet->getColumnDimension('M')->setAutoSize(true);
            $detailSheet->getColumnDimension('N')->setAutoSize(true);

            $detailSheet->setAutoFilter(
                $spreadsheet->getActiveSheet()
                    ->calculateWorksheetDimension()
            );

            $indexSheet = $indexSheet + 1;
        }else{
            $collectByName = $collectByName->sortBy('date_add')->sortKeys()->groupBy('name');

            $collectByName = $collectByName->sortKeys();

            foreach ($collectByName as $key => $item) {
                $name = substr($key,0,30);

                $spreadsheet->addSheet(new Worksheet($spreadsheet,$name));
                // $spreadsheet->addSheet(new Worksheet($spreadsheet,$key));
                $detailSheet = $spreadsheet->setActiveSheetIndex($indexSheet + 1);

                // $detailSheet->getStyle('A1:J1')->applyFromArray($titleStyle);
                // $detailSheet->setCellValue('A1','Timesheet ' . $key);
                // $detailSheet->mergeCells('A1:J1');

                $headerContent = ["No", "Name","Activity", "Type", "PID/Lead ID", "Level", "Phase","Task","Duration","Status","Date Add","Start Date","End Date","Schedule"];

                $detailSheet->getStyle('C')->getAlignment()->setWrapText(true);
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
                $detailSheet->getColumnDimension('M')->setAutoSize(true);
                $detailSheet->getColumnDimension('N')->setAutoSize(true);

                $detailSheet->setAutoFilter(
                    $spreadsheet->getActiveSheet()
                        ->calculateWorksheetDimension()
                );

                $indexSheet = $indexSheet + 1;
            }
        }
        
        if ($indexSheet == '0') {
            return 'Staff belum ada yang input timesheet';
        } else {

            $firstWorksheet = $spreadsheet->getSheet(0);

            // Remove the first worksheet from the workbook
            $spreadsheet->removeSheetByIndex(0);
            $spreadsheet->setActiveSheetIndex(0);

            $year = date('Y');

            if (isset($request->year)) {
                $year = $request->year;
            }else{
                $year = $year;
            }

            $fileName = 'Timesheet ' . $cek_role->group . ' '. $year . '.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $fileName . '"');
            header('Cache-Control: max-age=0');

            $writer = new Xlsx($spreadsheet);
            ob_end_clean();
            return $writer->save("php://output");
        }
    }

    public function isFillFeeling(){

        $checkFeeling = Feelings::whereDate('date_add', Carbon::today())->where('nik',Auth::User()->nik)->first();

        // return $checkFeeling;
        if ($checkFeeling == null) {
            return collect(["false"]);
        } else {
            return $checkFeeling->code_feeling;
        }
    }

    public function storeFeeling(Request $request){
        $store               = new Feelings();
        $store->nik          = Auth::User()->nik;
        $store->code_feeling = $request->code_feeling;
        $store->date_add     = date("Y-m-d h:i:s");
        $store->save();
    }

    public function updateDateEvent(Request $request){
        $update               = Timesheet::where('id',$request->id)->first()->makeHidden('planned');
        $update->nik          = Auth::User()->nik;
        $update->start_date   = $request->dates;
        $update->end_date     = $request->dates;
        $update->update();
    }

    public function detailActivitybyPid(Request $request)
    {
        $data = TimesheetByDate::join('users','users.nik','tb_timesheet.nik')->leftJoin('tb_timesheet_phase','tb_timesheet_phase.id','tb_timesheet.phase')
            ->leftJoin('tb_timesheet_task','tb_timesheet_task.id','tb_timesheet.task')
            ->select('tb_timesheet.nik','pid','start_date','users.name')
            ->where('tb_timesheet.nik',$request->nik)
            ->where('pid',$request->pid)
            ->orderby('start_date','asc')->distinct()->get();

        return array("data"=>$data);
    }

    public function deleteRolePID(Request $request)
    {
        $delete = TimesheetPid::where('id',$request->id);
        $delete->delete();
    }

    public function updateRolePID(Request $request)
    {
        $update = TimesheetPid::where('id',$request->id)->first();
        $update->role = $request->role;
        $update->save();
    }

    public function getRolePID(Request $request)
    {
        return $data = TimesheetPid::join('users','users.nik','tb_timesheet_pid.nik')->select('name','pid','role','tb_timesheet_pid.id')->where('id',$request->id)->first();

    }
}