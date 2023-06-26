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

use Mail;


class TimesheetController extends Controller
{
	public function timesheet()
    {
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

    public function storePhaseConfig(Request $request)
    {
    	$store = new TimesheetPhase();
    	$store->phase = $request->inputPhase;
    	$store->description = $request->inputPhaseDesc;
    	$store->date_add = Carbon::now()->toDateTimeString();
    	$store->save();
    }

    public function storeTaskConfig(Request $request)
    {
    	$store = new TimesheetTask();
    	$store->task = $request->inputTask;
    	$store->description = $request->inputTaskDesc;
    	$store->date_add = Carbon::now()->toDateTimeString();
    	$store->save();
    }

    public function assignPidConfig(Request $request)
    {
    	foreach (json_decode($request->selectPIDAssign,true) as $key => $value) {
    		$assign = new TimesheetPid();
	    	$assign->nik = $request->selectPICAssign;
	    	$assign->pid = $value;
	    	$assign->role = $request->selectRoleAssign;
	    	$assign->date_add = Carbon::now()->toDateTimeString();
	    	$assign->save();
    	}
    	
    }

    public function addTimesheet(Request $request)
    {
        // return $request->id_activity;
        $startDate = Carbon::now()->startOfMonth()->format("Y-m-d");
        $endDate = Carbon::now()->endOfMonth()->format("Y-m-d");

        if (isset($request->id_activity)) {
            $addTimesheet = Timesheet::where('id',$request->id_activity)->first();
        } else {
            $addTimesheet = new Timesheet();
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
    	$addTimesheet->date_add = Carbon::now()->toDateTimeString();
        $getPoint = (int)$request->selectDuration/480;
        $addTimesheet->point_mandays = number_format($getPoint, 2, '.', '');
        $addTimesheet->month = date("n");
        $workdays = $this->getWorkDays($startDate,$endDate)["workdays"]->values();
        $addTimesheet->workdays = count($workdays);
    	$addTimesheet->save();

        return $addTimesheet;
    }

    public function storeLockDuration(Request $request)
    {
    	if (DB::table('tb_timesheet_lock_duration')->where('division',Auth::User()->id_division)->first()->division == Auth::User()->id_division) {
    		$lock = TimesheetLockDuration::where('division',Auth::User()->id_division)->first();
    	} else {
    		$lock = new TimesheetLockDuration();
    	}
    	
    	$lock->lock_duration = $request->selectLock;
    	$lock->division = Auth::User()->id_division;
    	$lock->date_add = Carbon::now()->toDateTimeString();
    	$lock->save();
    }

    public function addConfig(Request $request)
    {
    	// return $request->arrConfig;

    	foreach (json_decode($request->arrConfig,true) as $key => $value) {
    		// return gettype($value['phase']);
	    	// foreach ($value['phase'] as $key => $phase) {
	    		// foreach ($value['task'] as $key => $task) {

    				if (DB::table('tb_timesheet_config')->select('roles')->where('roles',$value['unit'])->exists()) {
    					$addConfig = TimesheetConfig::where('roles',$value['unit'])->first();
    				} else {
    					$addConfig = new TimesheetConfig();
    				}

    				$arrPhase = array($value['phase']);
		    		$arrTask = array($value['task']);
		    		$addConfig->roles = $value['unit'];
		    		$addConfig->phase = json_encode($value['phase'],JSON_NUMERIC_CHECK);
		    		$addConfig->task = json_encode($value['task'],JSON_NUMERIC_CHECK);
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
    	$getPidByPic = DB::table('tb_timesheet_pid')->select('pid as id','pid as text')->where('nik',Auth::User()->nik)->orderby('id','desc')->get();

    	return $getPidByPic;
    }

    public function getLeadId(Request $request)
    {
    	return $getLeadId = DB::table('sales_lead_register')->join('sales_solution_design','sales_solution_design.lead_id','sales_lead_register.lead_id')->select('sales_solution_design.lead_id as id','sales_solution_design.lead_id as text')->where('sales_solution_design.nik',Auth::User()->nik)->get();
    }


    public function getAllTask(Request $request)
    {
    	return $getAll = TimesheetTask::select('id as id','task as text')->get();
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
            if ($cek_role->name == 'BCD Development SPV') {
                // $getGroupRoles = DB::table('role_user')->join('roles','roles.id','role_user.role_id')->select('name')->where('user_id',Auth::User()->nik)->first()->name;

                $data = DB::table('tb_timesheet_config')->join('roles','roles.id','tb_timesheet_config.roles')->join('tb_timesheet_phase', function ($join) {
                    $join->on(DB::raw('JSON_CONTAINS(tb_timesheet_config.task, CAST(tb_timesheet_phase.id AS JSON), "$")'), '=', DB::raw('1'));
                })
                ->select('tb_timesheet_phase.id as id', 'tb_timesheet_phase.phase as text')->where('name','BCD Development')->distinct()->get();
            } else {
                $getGroupRoles = DB::table('role_user')->join('roles','roles.id','role_user.role_id')->select('name')->where('user_id',Auth::User()->nik)->first()->name;

                $data = DB::table('tb_timesheet_config')->join('roles','roles.id','tb_timesheet_config.roles')->join('tb_timesheet_phase', function ($join) {
                    $join->on(DB::raw('JSON_CONTAINS(tb_timesheet_config.task, CAST(tb_timesheet_phase.id AS JSON), "$")'), '=', DB::raw('1'));
                })
                ->select('tb_timesheet_phase.id as id', 'tb_timesheet_phase.phase as text')->where('name',$getGroupRoles)->distinct()->get();
            }
            
        } else {
            $getGroupRoles = DB::table('role_user')->join('roles','roles.id','role_user.role_id')->select('group')->where('user_id',Auth::User()->nik)->first()->group;

            $data = DB::table('tb_timesheet_config')->join('roles','roles.id','tb_timesheet_config.roles')->join('tb_timesheet_phase', function ($join) {
                $join->on(DB::raw('JSON_CONTAINS(tb_timesheet_config.task, CAST(tb_timesheet_phase.id AS JSON), "$")'), '=', DB::raw('1'));
            })
            ->select('tb_timesheet_phase.id as id', 'tb_timesheet_phase.phase as text')->where('group',$getGroupRoles)->distinct()->get();
        }

    	// $getAll = TimesheetConfig::join('roles','roles.id','tb_timesheet_config.roles')->select('task')->where('group',$getGroupRoles)->get();

		return $data;
    	
    }

    public function getAllPhase(Request $request)
    {
    	return $getPhase = TimesheetPhase::select('id as id','phase as text')->get();
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
        } else if($getGroupRoles == 'hr'){
            $getRoles = DB::table('roles')->selectRaw('`id` AS `id`,CONCAT("HR") AS `text`')->where("name","not like","%SPV%")->where("name","not like","%Manager%")->where("name","not like","%Director%")->where("name","not like","%MSP%")->where("name","not like","%Admin%")->where('group',$getGroupRoles)->distinct()->get()->take(1);
        } else {
            $getRoles = DB::table('roles')->select('id as id','name as text')->where("name","not like","%SPV%")->where("name","not like","%Manager%")->where("name","not like","%Director%")->where("name","not like","%MSP%")->where("name","not like","%Admin%")->where('group',$getGroupRoles)->distinct()->get();
        }

    	return $getRoles;
    }

    public function getAllUser()
    {
    	return $getUser = User::select('nik as id', 'name as text')->where('id_division',Auth::User()->id_division)->get();
    }

    public function getConfigByDivision()
    {
    	// return $getConfig = TimesheetConfig::join('tb_timesheet_phase','tb_timesheet_phase.id','tb_timesheet_config.phase')->join('tb_timesheet_task','tb_timesheet_task.id','tb_timesheet_config.task')->join('roles','roles.id','tb_timesheet_config.roles')->select('roles.name','tb_timesheet_task.task','tb_timesheet_phase.phase','division','tb_timesheet_config.date_add')->where('division',Auth::User()->id_division)->get();
    	return $getConfig = TimesheetConfig::where('division',Auth::User()->id_division)->get();

    	// return unserialize($getConfig->phase);
    }

    public function getAllPhaseTask(Request $request)
    {
    	$dataTask = TimesheetTask::select('task as name','description')->get();
    	$dataPhase = TimesheetPhase::select('phase as name','description')->get();

    	$array = array_merge($dataTask->toArray(),$dataPhase->toArray());
		return array("data"=>$array);
    }

    public function getAllActivityByUser(Request $request)
    {
    	$startDate = Carbon::now()->startOfYear()->format("Y-m-d");
        $endDate = Carbon::now()->endOfYear()->format("Y-m-d");

    	$data = Timesheet::where('nik',$request->nik)->orderby('id','asc')->get()->makeHidden(['planned']);;

    	$getLock = TimesheetLockDuration::where('division',Auth::User()->id_division)->first();

    	$getLeavingPermit = Cuti::join('tb_cuti_detail','tb_cuti_detail.id_cuti','tb_cuti.id_cuti')->select('date_off as start_date','reason_leave as activity')->where('nik',$request->nik)->where('tb_cuti.status','v')->orderby('start_date','desc')->get();

    	$holiday = $this->getWorkDays($startDate,$endDate)["holiday"]->values();

    	$getPermit = TimesheetPermit::select('start_date','end_date','status as remarks','activity')->where('nik',$request->nik)->get();

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
    	$getGroupRoles = DB::table('role_user')->join('roles','roles.id','role_user.role_id')->select('group')->where('user_id',Auth::User()->nik)->first()->group;

		$data = DB::table('tb_timesheet_config')->join('roles','roles.id','tb_timesheet_config.roles')->join('tb_timesheet_task', function ($join) {
	        $join->on(DB::raw('JSON_CONTAINS(tb_timesheet_config.task, CAST(tb_timesheet_task.id AS JSON), "$")'), '=', DB::raw('1'));
	    })
	    ->select('tb_timesheet_task.id', 'tb_timesheet_task.task as title','tb_timesheet_task.description')->where('group',$getGroupRoles)->distinct()->get();

		$dataPhase = DB::table('tb_timesheet_config')->join('roles','roles.id','tb_timesheet_config.roles')->join('tb_timesheet_phase', function ($join) {
	        $join->on(DB::raw('JSON_CONTAINS(tb_timesheet_config.task, CAST(tb_timesheet_phase.id AS JSON), "$")'), '=', DB::raw('1'));
	    })
	    ->select('tb_timesheet_phase.id', 'tb_timesheet_phase.phase as title','tb_timesheet_phase.description')->where('group',$getGroupRoles)->distinct()->get();

		// $array = array_merge($data->toArray(),$dataPhase->toArray());
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
        // return $request->inputDatePermit;
        
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

    public function getNameByNik(Request $request)
    {
        return $getName = User::select('name','email')->where('nik',$request->nik)->first();
    }

    public function sumPointMandays(Request $request)
    {
        $nik = Auth::User()->nik;
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('name', 'roles.group')->where('user_id', $nik)->first(); 
        $startDate = Carbon::now()->startOfMonth()->format("Y-m-d");
        $endDate = Carbon::now()->endOfMonth()->format("Y-m-d");

        $workdays = $this->getWorkDays($startDate,$endDate)["workdays"]->values();
        $getLeavingPermit = Cuti::join('tb_cuti_detail','tb_cuti_detail.id_cuti','tb_cuti.id_cuti')->select('date_off as date')->where('tb_cuti.status','v')->whereYear('date_off',date('Y'))->orderby('date','desc');

        $getPermit = TimesheetPermit::select('start_date');
        $workdays = count($workdays);

        if ($cek_role->group == 'pmo') {
            if ($cek_role->name == 'PMO Manager') {
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','pmo')->pluck('nik');
                $getData = User::select('nik','name')->whereIn('nik',$listGroup)->get();
                $getLeavingPermit = $getLeavingPermit->whereIn('nik',$listGroup)->get();
                $getPermit = $getPermit->whereIn('nik',$listGroup)->get();
                $sumMandays = Timesheet::select('point_mandays')->whereIn('nik',$listGroup)->where('status','Done')->whereMonth('start_date',date('m'))->sum('point_mandays');
            } else {
                $getData = User::select('nik','name')->where('nik',$nik)->get();
                $getLeavingPermit = $getLeavingPermit->where('nik',$nik)->get();
                $getPermit = $getPermit->where('nik',$nik)->get();
                $sumMandays = Timesheet::select('point_mandays')->where('nik',$nik)->where('status','Done')->whereMonth('start_date',date('m'))->sum('point_mandays');
            }
        }elseif ($cek_role->group == 'DPG') {
            if ($cek_role->name == 'SID Manager') {
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','DPG')->pluck('nik');
                $getData = User::select('nik','name')->whereIn('nik',$listGroup)->get();
                $getLeavingPermit = $getLeavingPermit->whereIn('nik',$listGroup)->get();
                $getPermit = $getPermit->whereIn('nik',$listGroup)->get();
                $sumMandays = Timesheet::select('point_mandays')->whereIn('nik',$listGroup)->where('status','Done')->whereMonth('start_date',date('m'))->sum('point_mandays');
            } else {
                $getData = User::select('nik','name')->where('nik',$nik)->get();
                $getLeavingPermit = $getLeavingPermit->where('nik',$nik)->get();
                $getPermit = $getPermit->where('nik',$nik)->get();
                $sumMandays = Timesheet::select('point_mandays')->where('nik',$nik)->where('status','Done')->whereMonth('start_date',date('m'))->sum('point_mandays');
            }
        }elseif ($cek_role->group == 'presales') {
            if ($cek_role->name == 'SOL Manager') {
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','presales')->pluck('nik');
                $getData = User::select('nik','name')->whereIn('nik',$listGroup)->get();
                $getLeavingPermit = $getLeavingPermit->whereIn('nik',$listGroup)->get();
                $getPermit = $getPermit->whereIn('nik',$listGroup)->get();
                $sumMandays = Timesheet::select('point_mandays')->whereIn('nik',$listGroup)->where('status','Done')->whereMonth('start_date',date('m'))->sum('point_mandays');
            } else {
                $getData = User::select('nik','name')->where('nik',$nik)->get();
                $getLeavingPermit = $getLeavingPermit->where('nik',$nik)->get();
                $getPermit = $getPermit->where('nik',$nik)->get();
                $sumMandays = Timesheet::select('point_mandays')->where('nik',$nik)->where('status','Done')->whereMonth('start_date',date('m'))->sum('point_mandays');
            }
        }elseif ($cek_role->group == 'bcd') {
            if ($cek_role->name == 'BCD Manager') {
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','bcd')->pluck('nik');

                // return $listGroup;
                $getData = User::select('nik','name')->whereIn('nik',$listGroup)->get();
                $getLeavingPermit = $getLeavingPermit->whereIn('nik',$listGroup)->get();
                $getPermit = $getPermit->whereMonth('start_date',date('m'))->whereIn('nik',$listGroup)->get();
                $sumMandays = Timesheet::join('users','users.nik','tb_timesheet.nik')->select('point_mandays','users.name','tb_timesheet.nik')->whereIn('tb_timesheet.nik',$listGroup)->where('status','Done')->whereMonth('start_date',date('m'))->groupby('nik')->get();
                return $sumMandays;
            } else {
                $getData = User::select('nik','name')->where('nik',$nik)->get();
                $getLeavingPermit = $getLeavingPermit->where('nik',$nik)->get();
                $getPermit = $getPermit->whereMonth('start_date',date('m'))->where('nik',$nik)->get();
                $sumMandays = Timesheet::join('users','users.nik','tb_timesheet.nik')->selectRaw('tb_timesheet.nik')->selectRaw('users.name')->selectRaw('SUM(point_mandays) AS `point_mandays`')->where('tb_timesheet.nik',$nik)->where('status','Done')->whereMonth('start_date',date('m'))->groupby('tb_timesheet.nik')->get();
            }
        }elseif ($cek_role->group == 'hr') {
            if ($cek_role->name == 'HR Manager') {
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','hr')->pluck('nik');
                $getData = User::select('nik','name')->whereIn('nik',$listGroup)->get();
                $getLeavingPermit = $getLeavingPermit->whereIn('nik',$listGroup)->get();
                $getPermit = $getPermit->whereIn('nik',$listGroup)->get();
                $sumMandays = Timesheet::select('point_mandays')->whereIn('nik',$listGroup)->where('status','Done')->whereMonth('start_date',date('m'))->sum('point_mandays');
            } else {
                $getData = User::select('nik','name')->where('nik',$nik)->get();
                $getLeavingPermit = $getLeavingPermit->where('nik',$nik)->get();
                $getPermit = $getPermit->where('nik',$nik)->get();
                $sumMandays = Timesheet::select('point_mandays')->where('nik',$nik)->where('status','Done')->whereMonth('start_date',date('m'))->sum('point_mandays');
            }
        }

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

        $getAllLeavingPermit = $getAllLeavingPermit->toArray();

        $all = array_merge($allWorkdays);

        $differenceArray = array_diff($all, $getAllPermit->toArray());
        $differenceArrayMerged = array_merge($differenceArray);
        $differenceArray2 = array_diff($differenceArrayMerged, $getAllLeavingPermit);

        $billable = count($differenceArray2);

        $planned = $sumMandays->map(function ($item, $key) {
            $planned = $item['planned'];
            $actual = $item['point_mandays'];
            return [$planned,$actual];
        });

        $percentage = number_format($billable/$planned[0][0]*100,  2, '.', '');

        // return array("data"=>$sumMandays,"billable"=>$billable,"deviation"=>$planned[0][0]-$planned[0][1],"percentage"=>$billable/$planned[0][0]*100);
        // return array("data"=>$sumMandays,"data"=>$percentage);
        $sumMandays = $sumMandays[0];
        $sumMandays->percentage = $percentage;
        $sumMandays->deviation = $planned[0][0]-$planned[0][1];
        $sumMandays->billable = $billable;
        // return $sumMandays;
        return array("data"=>$sumMandays);

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

        $getAllLeavingPermit = $getAllLeavingPermit->toArray();

        $all = array_merge($allWorkdays);

        $differenceArray = array_diff($all, $getAllPermit->toArray());
        $differenceArrayMerged = array_merge($differenceArray);
        $differenceArray2 = array_diff($differenceArrayMerged, $getAllLeavingPermit);

        $billable = count($differenceArray2);

        $planned = $sumMandays->map(function ($item, $key) {
            $planned = $item['planned'];
            $actual = $item['point_mandays'];
            $name = $item['name'];
            return [$planned,$actual,$name];
        });

        // return $planned[0];

        $percentage = number_format($planned[0][1]/$planned[0][0]*100,  2, '.', '');

        $isEndMonth = 'false';
        if ($now == $endDate) {
            $isEndMonth = 'true';
        }

        return collect(['percentage'=>$percentage,'name'=>$planned[0][2],'isEndMonth'=>$isEndMonth]);
    }

    public function getLevelChart(Request $request)
    {
        $nik = Auth::User()->nik;
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('name', 'roles.group')->where('user_id', $nik)->first(); 

        if ($cek_role->group == 'pmo') {
            if ($cek_role->name == 'PMO Manager') {
                $level = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','pmo')->whereMonth('start_date',date('m'))->get();
            } else {
                $level = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('tb_timesheet.nik',Auth::User()->nik)->whereMonth('start_date',date('m'))->get();
            }
        }elseif ($cek_role->group == 'DPG') {
            if ($cek_role->name == 'SID Manager') {
                $level = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','DPG')->whereMonth('start_date',date('m'))->get();
            } else {
                $level = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('tb_timesheet.nik',Auth::User()->nik)->whereMonth('start_date',date('m'))->get();
            }
        }elseif ($cek_role->group == 'presales') {
            if ($cek_role->name == 'SOL Manager') {
                $level = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','presales')->whereMonth('start_date',date('m'))->get();
            } else {
                $level = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('tb_timesheet.nik',Auth::User()->nik)->whereMonth('start_date',date('m'))->get();
            }
        }elseif ($cek_role->group == 'bcd') {
            if ($cek_role->name == 'BCD Manager') {
                $level = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','bcd')->whereMonth('start_date',date('m'))->get();
            } else {
                $level = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('tb_timesheet.nik',Auth::User()->nik)->whereMonth('start_date',date('m'))->get();
            }
        }elseif ($cek_role->group == 'hr') {
            if ($cek_role->name == 'HR Manager') {
                $level = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','hr')->whereMonth('start_date',date('m'))->get();
            } else {
                $level = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('tb_timesheet.nik',Auth::User()->nik)->whereMonth('start_date',date('m'))->get();
            }
        }

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

        return $hasil2;
    }

    public function getStatusChart(Request $request)
    {
        $nik = Auth::User()->nik;
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('name', 'roles.group')->where('user_id', $nik)->first(); 

        if ($cek_role->group == 'pmo') {
            if ($cek_role->name == 'PMO Manager') {
                $status = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','pmo')->whereMonth('start_date',date('m'))->get();
            } else {
                $status = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('tb_timesheet.nik',Auth::User()->nik)->whereMonth('start_date',date('m'))->get();
            }
        }elseif ($cek_role->group == 'DPG') {
            if ($cek_role->name == 'SID Manager') {
                $status = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','DPG')->whereMonth('start_date',date('m'))->get();
            } else {
                $status = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('tb_timesheet.nik',Auth::User()->nik)->whereMonth('start_date',date('m'))->get();
            }
        }elseif ($cek_role->group == 'presales') {
            if ($cek_role->name == 'SOL Manager') {
                $status = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','presales')->whereMonth('start_date',date('m'))->get();
            } else {
                $status = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('tb_timesheet.nik',Auth::User()->nik)->whereMonth('start_date',date('m'))->get();
            }
        }elseif ($cek_role->group == 'bcd') {
            if ($cek_role->name == 'BCD Manager') {
                $status = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','bcd')->whereMonth('start_date',date('m'))->get();
            } else {
                $status = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('tb_timesheet.nik',Auth::User()->nik)->whereMonth('start_date',date('m'))->get();
            }
        }elseif ($cek_role->group == 'hr') {
            if ($cek_role->name == 'HR Manager') {
                $status = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','hr')->whereMonth('start_date',date('m'))->get();
            } else {
                $status = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('tb_timesheet.nik',Auth::User()->nik)->whereMonth('start_date',date('m'))->get();
            }
        }

        $first = $status[0]->status;
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

        return $hasil2;
    }

    public function getScheduleChart(Request $request)
    {
        $nik = Auth::User()->nik;
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('name', 'roles.group')->where('user_id', $nik)->first(); 

        if ($cek_role->group == 'pmo') {
            if ($cek_role->name == 'PMO Manager') {
                $schedule = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','pmo')->whereMonth('start_date',date('m'))->get();
            } else {
                $schedule = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('tb_timesheet.nik',Auth::User()->nik)->whereMonth('start_date',date('m'))->get();
            }
        }elseif ($cek_role->group == 'DPG') {
            if ($cek_role->name == 'SID Manager') {
                $schedule = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','DPG')->whereMonth('start_date',date('m'))->get();
            } else {
                $schedule = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('tb_timesheet.nik',Auth::User()->nik)->whereMonth('start_date',date('m'))->get();
            }
        }elseif ($cek_role->group == 'presales') {
            if ($cek_role->name == 'SOL Manager') {
                $schedule = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','presales')->whereMonth('start_date',date('m'))->get();
            } else {
                $schedule = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('tb_timesheet.nik',Auth::User()->nik)->whereMonth('start_date',date('m'))->get();
            }
        }elseif ($cek_role->group == 'bcd') {
            if ($cek_role->name == 'BCD Manager') {
                $schedule = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','bcd')->whereMonth('start_date',date('m'))->get();
            } else {
                $schedule = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('tb_timesheet.nik',Auth::User()->nik)->whereMonth('start_date',date('m'))->get();
            }
        }elseif ($cek_role->group == 'hr') {
            if ($cek_role->name == 'HR Manager') {
                $schedule = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','hr')->whereMonth('start_date',date('m'))->get();
            } else {
                $schedule = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('tb_timesheet.nik',Auth::User()->nik)->whereMonth('start_date',date('m'))->get();
            }
        }

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

        return $hasil2;

        // return array($statusSchedule=>$hasil2);
    }

    public function getRemainingChart(Request $request)
    {
        $nik = Auth::User()->nik;
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('name', 'roles.group')->where('user_id', $nik)->first(); 
        $startDate = Carbon::now()->startOfMonth()->format("Y-m-d");
        $endDate = Carbon::now()->endOfMonth()->format("Y-m-d");

        // $workdays = $this->getWorkDays($startDate,$endDate)["workdays"]->values();
        // $getLeavingPermit = Cuti::join('tb_cuti_detail','tb_cuti_detail.id_cuti','tb_cuti.id_cuti')->select('date_off as date')->where('tb_cuti.status','v')->whereYear('date_off',date('Y'))->orderby('date','desc');

        // $getPermit = TimesheetPermit::select('start_date');
        // $workdays = count($workdays);

        if ($cek_role->group == 'pmo') {
            if ($cek_role->name == 'PMO Manager') {
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','pmo')->pluck('nik');
                $sumMandays = Timesheet::join('users','users.nik','tb_timesheet.nik')
                ->selectRaw('tb_timesheet.nik')
                ->selectRaw('name')
                ->selectRaw('SUM(point_mandays) AS `point_mandays`')
                ->whereIn('tb_timesheet.nik',$listGroup)
                ->where('status','Done')->whereMonth('start_date',date('m'))
                ->groupby('tb_timesheet.nik');
            } else {
                $sumMandays = Timesheet::join('users','users.nik','tb_timesheet.nik')->selectRaw('tb_timesheet.nik')->selectRaw('users.name')->selectRaw('SUM(point_mandays) AS `point_mandays`')->where('tb_timesheet.nik',$nik)->where('status','Done')->whereMonth('start_date',date('m'))->groupby('tb_timesheet.nik');
            }
        }elseif ($cek_role->group == 'DPG') {
            if ($cek_role->name == 'SID Manager') {
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','DPG')->pluck('nik');
                $sumMandays = Timesheet::join('users','users.nik','tb_timesheet.nik')
                ->selectRaw('tb_timesheet.nik')
                ->selectRaw('name')
                ->selectRaw('SUM(point_mandays) AS `point_mandays`')
                ->whereIn('tb_timesheet.nik',$listGroup)
                ->where('status','Done')->whereMonth('start_date',date('m'))
                ->groupby('tb_timesheet.nik');
            } else {
                $sumMandays = Timesheet::join('users','users.nik','tb_timesheet.nik')->selectRaw('tb_timesheet.nik')->selectRaw('users.name')->selectRaw('SUM(point_mandays) AS `point_mandays`')->where('tb_timesheet.nik',$nik)->where('status','Done')->whereMonth('start_date',date('m'))->groupby('tb_timesheet.nik');
            }
        }elseif ($cek_role->group == 'presales') {
            if ($cek_role->name == 'SOL Manager') {
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','presales')->pluck('nik');
                $sumMandays = Timesheet::join('users','users.nik','tb_timesheet.nik')
                ->selectRaw('tb_timesheet.nik')
                ->selectRaw('name')
                ->selectRaw('SUM(point_mandays) AS `point_mandays`')
                ->whereIn('tb_timesheet.nik',$listGroup)
                ->where('status','Done')->whereMonth('start_date',date('m'))
                ->groupby('tb_timesheet.nik');
            } else {
                $sumMandays = Timesheet::join('users','users.nik','tb_timesheet.nik')->selectRaw('tb_timesheet.nik')->selectRaw('users.name')->selectRaw('SUM(point_mandays) AS `point_mandays`')->where('tb_timesheet.nik',$nik)->where('status','Done')->whereMonth('start_date',date('m'))->groupby('tb_timesheet.nik');
            }
        }elseif ($cek_role->group == 'bcd') {
            if ($cek_role->name == 'BCD Manager') {
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','bcd')->pluck('nik');

                $sumMandays = Timesheet::join('users','users.nik','tb_timesheet.nik')
                ->selectRaw('tb_timesheet.nik')
                ->selectRaw('name')
                ->selectRaw('workdays')
                ->selectRaw('SUM(point_mandays) AS `point_mandays`')
                ->whereIn('tb_timesheet.nik',$listGroup)
                ->where('status','Done')->whereMonth('start_date',date('m'))
                ->groupby('tb_timesheet.nik')->get();
            } else {
                // return date('n');
                $getWorkDays = DB::table('tb_timesheet')->join('tb_timesheet_workdays','tb_timesheet_workdays.month','tb_timesheet.month')->select('nik','tb_timesheet.workdays')->where('tb_timesheet.month',date('n'))->where('tb_timesheet.nik',$nik)->take(1);

                // return $getWorkDays->get();

                $sumMandays = DB::table($getWorkDays,'temp')->join('tb_timesheet','tb_timesheet.nik','temp.nik')->join('users','users.nik','tb_timesheet.nik')
                    ->select(
                        'temp.nik','users.name','temp.workdays',
                        DB::raw('SUM(point_mandays) as point_mandays'),
                        // DB::raw('(`temp`.`point_mandays`)/(`temp.planned`)*100 as `percentage` ')
                    )
                    // ->selectRaw('tb_timesheet.nik')
                    // ->selectRaw('users.name')
                    // ->selectRaw('SUM(point_mandays) AS `point_mandays`')
                    ->where('status','Done')
                    ->where('temp.nik',$nik)
                    ->whereMonth('start_date',date('m'));
            }
        }elseif ($cek_role->group == 'hr') {
            if ($cek_role->name == 'HR Manager') {
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','hr')->pluck('nik');
                $sumMandays = Timesheet::join('users','users.nik','tb_timesheet.nik')->join('tb_timesheet_workdays','tb_timesheet_workdays.month','tb_timesheet.month')
                ->selectRaw('tb_timesheet.nik')
                ->selectRaw('workdays')
                ->selectRaw('name')
                ->selectRaw('SUM(point_mandays) AS `point_mandays`')
                ->whereIn('tb_timesheet.nik',$listGroup)
                ->where('status','Done')->whereMonth('start_date',date('m'))
                ->groupby('tb_timesheet.nik');
            } else {
                $sumMandays = Timesheet::join('users','users.nik','tb_timesheet.nik')->selectRaw('tb_timesheet.nik')->selectRaw('users.name')->selectRaw('SUM(point_mandays) AS `point_mandays`')->where('tb_timesheet.nik',$nik)->where('status','Done')->whereMonth('start_date',date('m'))->groupby('tb_timesheet.nik');
            }
        }

        return $sumMandays->get();

        // $sumMandaysCollect = collect();
        // $sumMandays = json_decode($sumMandays, true);

        // $sumMandays = $sumMandays->map(function ($item, $key){
        //     $item->planned = $item->planned;
        //     // $item->document = $item->document_detail;
        //     return $item;
        // });

        // return $sumMandays->get();

        $data = DB::table($sumMandays,'temp')->join('tb_timesheet','tb_timesheet.nik','temp.nik')->join('tb_timesheet_workdays','tb_timesheet_workdays.month','tb_timesheet.month')->select(
                'temp.nik','temp.point_mandays','name'
                // DB::raw('SUM(temp.planned) as point_mandays'),
                // DB::raw('(`temp`.`point_mandays`)/(`temp.planned`)*100 as `percentage` ')
            )
            ->groupBy('nik');

        return array("data" => $data->get());
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

        $hasil = [0,0,0,0,0,0,0,0,0,0,0,0];

        $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $bulan_angka = [1,2,3,4,5,6,7,8,9,10,11,12];

        $data = DB::table('tb_timesheet')->join('users','tb_timesheet.nik','users.nik');

        if ($cek_role->group == 'pmo') {
            if ($cek_role->name == 'PMO Manager') {
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','pmo')->pluck('nik');
                $data = $data->whereIn('tb_timesheet.nik',$listGroup)
                ->groupBy('tb_timesheet.nik');
            } else {
                $sumMandays = Timesheet::join('users','users.nik','tb_timesheet.nik')->selectRaw('tb_timesheet.nik')->selectRaw('users.name')->selectRaw('SUM(point_mandays) AS `point_mandays`')->where('tb_timesheet.nik',$nik)->where('status','Done')->whereMonth('start_date',date('m'))->groupby('tb_timesheet.nik');
            }
        }elseif ($cek_role->group == 'DPG') {
            if ($cek_role->name == 'SID Manager') {
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','DPG')->pluck('nik');
                $data = $data->whereIn('tb_timesheet.nik',$listGroup)
                ->groupBy('tb_timesheet.nik');
            } else {
                $sumMandays = Timesheet::join('users','users.nik','tb_timesheet.nik')->selectRaw('tb_timesheet.nik')->selectRaw('users.name')->selectRaw('SUM(point_mandays) AS `point_mandays`')->where('tb_timesheet.nik',$nik)->where('status','Done')->whereMonth('start_date',date('m'))->groupby('tb_timesheet.nik');
            }
        }elseif ($cek_role->group == 'presales') {
            if ($cek_role->name == 'SOL Manager') {
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','presales')->pluck('nik');
                $data = $data->whereIn('tb_timesheet.nik',$listGroup)
                ->groupBy('tb_timesheet.nik');
            } else {
                $sumMandays = Timesheet::join('users','users.nik','tb_timesheet.nik')->selectRaw('tb_timesheet.nik')->selectRaw('users.name')->selectRaw('SUM(point_mandays) AS `point_mandays`')->where('tb_timesheet.nik',$nik)->where('status','Done')->whereMonth('start_date',date('m'))->groupby('tb_timesheet.nik');
            }
        }elseif ($cek_role->group == 'bcd') {
            if ($cek_role->name == 'BCD Manager') {
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','bcd')->pluck('nik');
                // $data = $data->select(DB::raw('SUM(point_mandays) AS `point_mandays`'),'name')->whereIn('tb_timesheet.nik',$listGroup)->where('status','Done')->groupby('name')->get();
                $data = $data->whereIn('tb_timesheet.nik',$listGroup)->where('status','Done')->get()->groupby('name');

                // return $data;
                $data = $data->toArray();
                // return array_column($data, 'point_mandays');
                // foreach ($bulan_angka as $key => $value2) {
                //    foreach ($data as $value) {
                //         foreach ($value as $key => $values) {
                //             // return $values->point_mandays;
                //             if ($values->month == $value2) {
                //                 $hasil[$key] = $hasil[$key]+$values->point_mandays;
                //             }
                //         }
                        
                //     }
                // }

                // foreach ($bulan_angka as $key => $value2) {
                //    foreach ($data as $value) {
                //        if ($value->month == $value2) {
                //             $hasil[$key] = $hasil[$key]+$value->point_mandays;
                //         }
                //     }
                // }

                // foreach ($data as $keys => $value) {
                //     // return $value;
                //     foreach ($value as $key => $values) {

                //         // $totalByName = $values->point_mandays;
                //         $totalByName = collect([$keys]);
                //         // $totalByName[$keys] = $totalByName[$keys]+$value->point_mandays;
                //         // $totalByName = $totalByName[$keys]+$values->point_mandays;
                //         // var_dump($totalByName);
                //         // foreach ($bulan_angka as $key => $value2) {
                //             // if ($values->month == $value2) {
                //                 // $hasil[$key] = $hasil[$key]+$values->point_mandays;
                //             // }
                //         // }
                //     }
                //     // $hasil = $hasil+$value->point_mandays;
                //     // foreach ($value as $key => $values) {
                //     //     foreach ($bulan_angka as $key => $value2) {
                //     //         if ($values->month == $value2) {
                //     //             $hasil[$key] = $hasil[$key]+$values->point_mandays;
                //     //         }
                //     //     }
                //     // }
                // }
            } else {
                $data = $data->where('status','Done')->where('tb_timesheet.nik',$nik)->get();

                foreach ($bulan_angka as $key => $value2) {
                   foreach ($data as $value) {
                       if ($value->month == $value2) {
                            $hasil[$key] = $hasil[$key]+$value->point_mandays;
                        }
                    }
                }
            }
        }elseif ($cek_role->group == 'hr') {
            if ($cek_role->name == 'HR Manager') {
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','hr')->pluck('nik');
                $data = $data->whereIn('tb_timesheet.nik',$listGroup)
                ->groupBy('tb_timesheet.nik');
            } else {
                $sumMandays = Timesheet::join('users','users.nik','tb_timesheet.nik')->selectRaw('tb_timesheet.nik')->selectRaw('users.name')->selectRaw('SUM(point_mandays) AS `point_mandays`')->where('tb_timesheet.nik',$nik)->where('status','Done')->whereMonth('start_date',date('m'))->groupby('tb_timesheet.nik');
            }
        }

        // return $data;
        return $hasil;

        // return array("data" => $data);
    }
}