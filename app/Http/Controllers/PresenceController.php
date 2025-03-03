<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use DateTime;
use App\User;
use Carbon\Carbon;
use DatePeriod;
use DateInterval;
use GuzzleHttp\Client;
use Excel;

use App\PresenceHistory;
use App\PresenceLocationUser;
use App\PresenceShifting;
use App\PresenceShiftingProject;
use App\PresenceShiftingOption;
use App\PresenceShiftingUser;
use App\PresenceLocation;
use App\PresenceSetting;

use Jenssegers\Agent\Agent;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PresenceController extends Controller
{
    ////////
    public function __construct() {
        $this->middleware('auth', ['except' => ['getPresenceParameter','checkIn','checkOut','personalHistoryMsp']]);
    }

    public function notification_legacy(){
        $user = Auth::User(); 
        $nik = $user->nik;
        $ter = $user->id_territory;
        $div = $user->id_division;
        $pos = $user->id_position; 
        $com = $user->id_company;
        
        if ($ter != null) {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();
        } elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $notif = DB::table('sales_lead_register')
            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->select('sales_lead_register.opp_name','sales_solution_design.nik')
            ->where('result','OPEN')
            ->orderBy('sales_lead_register.created_at','desc')
            ->get();
        } else {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();
        }

        if ($div == 'TECHNICAL PRESALES' && $pos == 'MANAGER') {
            $notifOpen= DB::table('sales_lead_register')
            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->select('sales_lead_register.opp_name','sales_solution_design.nik','sales_solution_design.lead_id')
            ->where('result','')
            ->orderBy('sales_lead_register.created_at','desc')
            ->get();
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $notifOpen= DB::table('sales_lead_register')
            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->select('sales_lead_register.opp_name','sales_solution_design.nik','sales_solution_design.lead_id')
            ->where('result','')
            ->orderBy('sales_lead_register.created_at','desc')
            ->get();
        }elseif ($div == 'SALES' && $pos == 'MANAGER') {
            $notifOpen= DB::table('sales_lead_register')
            ->select('opp_name','nik','lead_id')
            ->where('result','')
            ->orderBy('created_at','desc')
            ->get();
        }elseif ($div == 'SALES' && $pos == 'STAFF') {
            $notifOpen= DB::table('sales_lead_register')
            ->select('opp_name','nik','lead_id')
            ->where('result','')
            ->orderBy('created_at','desc')
            ->get();
        }else{
            $notifOpen= DB::table('sales_lead_register')
            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->select('sales_lead_register.opp_name','sales_solution_design.nik','sales_solution_design.lead_id')
            ->where('result','')
            ->orderBy('sales_lead_register.created_at','desc')
            ->get();
        }

        if ($div == 'TECHNICAL PRESALES' && $pos == 'MANAGER') {
            $notifsd= DB::table('sales_lead_register')
            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->select('sales_lead_register.opp_name','sales_solution_design.nik','sales_solution_design.lead_id')
            ->where('result','SD')
            ->orderBy('sales_lead_register.created_at','desc')
            ->get();
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $notifsd= DB::table('sales_lead_register')
            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->select('sales_lead_register.opp_name','sales_solution_design.nik','sales_solution_design.lead_id')
            ->where('result','SD')
            ->orderBy('sales_lead_register.created_at','desc')
            ->get();
        }elseif ($div == 'SALES' && $pos == 'MANAGER') {
            $notifsd= DB::table('sales_lead_register')
            ->select('opp_name','nik','lead_id')
            ->where('result','SD')
            ->orderBy('created_at','desc')
            ->get();
        }elseif ($div == 'SALES' && $pos == 'STAFF') {
            $notifsd= DB::table('sales_lead_register')
            ->select('opp_name','nik','lead_id')
            ->where('result','SD')
            ->orderBy('created_at','desc')
            ->get();
        }else{
            $notifsd= DB::table('sales_lead_register')
            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->select('sales_lead_register.opp_name','sales_solution_design.nik','sales_solution_design.lead_id')
            ->where('result','SD')
            ->orderBy('sales_lead_register.created_at','desc')
            ->get();
        }

        if ($div == 'TECHNICAL PRESALES' && $pos == 'MANAGER') {
            $notiftp= DB::table('sales_lead_register')
            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->select('sales_lead_register.opp_name','sales_solution_design.nik','sales_solution_design.lead_id')
            ->where('result','TP')
            ->orderBy('sales_lead_register.created_at','desc')
            ->get();
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $notiftp= DB::table('sales_lead_register')
            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->select('sales_lead_register.opp_name','sales_solution_design.nik','sales_solution_design.lead_id')
            ->where('result','TP')
            ->orderBy('sales_lead_register.created_at','desc')
            ->get();
        }elseif ($div == 'SALES' && $pos == 'MANAGER') {
            $notiftp= DB::table('sales_lead_register')
            ->select('opp_name','nik','lead_id')
            ->where('result','TP')
            ->orderBy('created_at','desc')
            ->get();
        }elseif ($div == 'SALES' && $pos == 'STAFF') {
            $notiftp= DB::table('sales_lead_register')
            ->select('opp_name','nik','lead_id')
            ->where('result','TP')
            ->orderBy('created_at','desc')
            ->get();
        }else{
            $notiftp= DB::table('sales_lead_register')
            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->select('sales_lead_register.opp_name','sales_solution_design.nik','sales_solution_design.lead_id')
            ->where('result','TP')
            ->orderBy('sales_lead_register.created_at','desc')
            ->get();
        }

        if (Auth::User()->id_position == 'ADMIN') {
            $notifClaim = DB::table('dvg_esm')
                            ->select('nik_admin', 'personnel', 'type')
                            ->where('status', 'ADMIN')
                            ->get();
        } elseif (Auth::User()->id_position == 'HR MANAGER') {
            $notifClaim = DB::table('dvg_esm')
                            ->select('nik_admin', 'personnel', 'type')
                            ->where('status', 'HRD')
                            ->get();
        } elseif (Auth::User()->id_division == 'FINANCE') {
            $notifClaim = DB::table('dvg_esm')
                            ->select('nik_admin', 'personnel', 'type')
                            ->where('status', 'FINANCE')
                            ->get();
        } else {
            $notifClaim = false;
        }

        return collect([
            "notif" => $notif,
            "notifOpen" => $notifOpen,
            "notifsd" => $notifsd,
            "notiftp" => $notiftp,
            "notifClaim" => $notifClaim
        ]);
    }

    public function index() {

        $notifAll = $this->notification_legacy();
        
        $notif = $notifAll["notif"];
        $notifOpen = $notifAll["notifOpen"];
        $notifsd = $notifAll["notifsd"];
        $notiftp = $notifAll["notiftp"];
        $notifClaim = $notifAll["notifClaim"];

        $presenceStatus = PresenceHistory::where('nik',Auth::User()->nik)
            ->orderBy('presence_actual','DESC');

        // return $presenceStatus->first();

        $presenceStatusDetail = "";
        $usersShifting = PresenceShiftingUser::where('nik',Auth::User()->nik)->exists();
        $shiftingScheduleLibur = PresenceShifting::where('nik',Auth::User()->nik)
            ->where('tanggal_shift',date('Y-m-d'))
            ->where('className',"Libur")
            ->exists();

        // if($usersShifting && $shiftingScheduleLibur){
        //     $presenceStatus = "libur";
        // } else {
            // code...

        $userPresence = User::join('presence__shifting_user','presence__shifting_user.nik','=','users.nik','left')->select('name',
                DB::raw("(CASE WHEN (`presence__shifting_user`.`nik` is null) THEN 'non-shifting' ELSE 'shifting' END) as status"))
                ->where('name',Auth::User()->name)
                ->first();


        if($presenceStatus->count() == 0){
            $presenceStatus = "not-yet";
        } else {
            if($presenceStatus->first()->presence_type == "Check-Out"){
                $todayPresence = PresenceHistory::where('nik',Auth::User()->nik)
                        ->whereRaw('DATE(`presence_actual`) = "' . now()->toDateString() . '"')
                        ->where('presence_type','Check-In')
                        ->orderBy('presence_actual','DESC')
                        ->count();
                if ($userPresence->status == 'shifting') {

                    if($todayPresence >= 2){
                        $presenceStatus = "done-checkout";
                    } else {
                        $presenceStatus = "not-yet";
                    }
                } else {
                    if($todayPresence >= 1){
                        $presenceStatus = "done-checkout";
                    } else {
                        $presenceStatus = "not-yet";
                    }
                }
                

                // return $todayPresence;


            } else if ($presenceStatus->first()->presence_type == "Check-In") {
                $presenceStatusDetail = $presenceStatus->first()->presence_condition;
                $presenceStatus = "done-checkin";
            }
            
        }
            
        // }

        return view('presence.presence', compact('presenceStatus','presenceStatusDetail','notif','notifOpen','notifsd','notiftp', 'notifClaim'))->with(['initView'=>$this->initMenuBase()]);
    }

    public function personalHistory() {
        $notifAll = $this->notification_legacy();
        
        $notif = $notifAll["notif"];
        $notifOpen = $notifAll["notifOpen"];
        $notifsd = $notifAll["notifsd"];
        $notiftp = $notifAll["notiftp"];
        $notifClaim = $notifAll["notifClaim"];

        $presenceHistoryTemp = PresenceHistory::select(
            DB::raw("*"),
            DB::raw("CAST(`presence_actual` AS DATE) AS `presence_actual_date`")
        )->whereRaw('`nik` = ' . Auth::User()->nik);

        $presenceHistory = DB::table(DB::raw("(" . $presenceHistoryTemp->toSql() . ") AS `presence__history_temp`"))
            ->select('presence__history_temp.nik')
            ->selectRaw("CAST(MIN(`presence__history_temp`.`presence_actual`) AS DATE) AS `date`")
            ->selectRaw("MIN(`presence__history_temp`.`presence_schedule`) AS `schedule`")
            ->selectRaw("MIN(`presence__history_temp`.`presence_actual`) AS `checkin`")
            ->selectRaw("MAX(`presence__history_temp`.`presence_actual`) AS `checkout`")
            ->selectRaw("MAX(`presence__history_temp`.`presence_condition`) AS `condition`")
            ->groupBy('presence__history_temp.presence_actual_date');

        $presenceHistoryDetail = $presenceHistory->get();
        $presenceHistoryCounted = $presenceHistory->get()->groupBy('condition');

        $presenceHistoryCounted = $presenceHistoryCounted->map(function ($item, $key) {
            if($key == "On-Time"){
                return ['count' => collect($item)->count(),'color' => "#00a65a"];
            }else if($key == "Injury-Time"){
                return ['count' => collect($item)->count(),'color' => "#f39c12"];
            } else {
                return ['count' => collect($item)->count(),'color' => "#dd4b39"];
            }
        })->sortBy('count');

        // return $presenceHistoryCounted->sortBy('count');


        return view('presence.personal_history', compact('presenceHistoryDetail','presenceHistoryCounted','notif','notifOpen','notifsd','notiftp', 'notifClaim'))->with(['initView'=>$this->initMenuBase()]);
    }

    public function personalHistoryMsp(Request $req){

        if (isset(Auth::User()->nik)) {
            $presenceHistoryTemp = PresenceHistory::select(
                DB::raw("*"),
                DB::raw("CAST(`presence_actual` AS DATE) AS `presence_actual_date`")
            )->whereRaw('`nik` = ' . Auth::User()->nik);
        }else{
            $presenceHistoryTemp = PresenceHistory::select(
                DB::raw("*"),
                DB::raw("CAST(`presence_actual` AS DATE) AS `presence_actual_date`")
            )->whereRaw('`nik` = ' . $req->nik);
        }        

        $presenceHistory = DB::table(DB::raw("(" . $presenceHistoryTemp->toSql() . ") AS `presence__history_temp`"))
            ->select('presence__history_temp.nik')
            ->selectRaw("CAST(MIN(`presence__history_temp`.`presence_actual`) AS DATE) AS `date`")
            ->selectRaw("MIN(`presence__history_temp`.`presence_schedule`) AS `schedule`")
            ->selectRaw("MIN(`presence__history_temp`.`presence_actual`) AS `checkin`")
            ->selectRaw("MAX(`presence__history_temp`.`presence_actual`) AS `checkout`")
            ->selectRaw("MAX(`presence__history_temp`.`presence_condition`) AS `condition`")
            // ->orderBy("date","DESC")
            ->groupBy('presence__history_temp.presence_actual_date');

        $presenceHistoryDetail = $presenceHistory->get();
        $presenceHistoryCounted = $presenceHistory->get()->groupBy('condition');

        $presenceHistoryCounted = $presenceHistoryCounted->map(function ($item, $key) {
            if($key == "On-Time"){
                return ['count' => collect($item)->count(),'color' => "#00a65a"];
            }else if($key == "Injury-Time"){
                return ['count' => collect($item)->count(),'color' => "#f39c12"];
            } else {
                return ['count' => collect($item)->count(),'color' => "#dd4b39"];
            }
        })->sortBy('count');

        return ["data"=>$presenceHistoryDetail,"datas"=>$presenceHistoryCounted,"datas2"=>$presenceHistoryCounted->keys(),"datas3"=>$presenceHistoryCounted->pluck('count'),"datas4"=>$presenceHistoryCounted->pluck('color')];

    }

    public function teamHistory() {
        $notifAll = $this->notification_legacy();
        
        $notif = $notifAll["notif"];
        $notifOpen = $notifAll["notifOpen"];
        $notifsd = $notifAll["notifsd"];
        $notiftp = $notifAll["notiftp"];
        $notifClaim = $notifAll["notifClaim"];

        return view('presence.team_history', compact('notif','notifOpen','notifsd','notiftp', 'notifClaim'));
    }

    public function presenceReport() {
        $notifAll = $this->notification_legacy();
        
        $notif = $notifAll["notif"];
        $notifOpen = $notifAll["notifOpen"];
        $notifsd = $notifAll["notifsd"];
        $notiftp = $notifAll["notiftp"];
        $notifClaim = $notifAll["notifClaim"];

        $sidebar_collapse = true;
        
        return view('presence.reporting', compact('notif','notifOpen','notifsd','notiftp', 'notifClaim', 'sidebar_collapse'))->with(['initView'=>$this->initMenuBase()]);
    }

    public function getAllUser()
    {
        $getUserSip = User::join('presence__history', 'presence__history.nik', '=', 'users.nik')->selectRaw('`users`.`nik`  AS `nik`,`users`.`name` AS `text`')->where('id_company','1')->where('status_karyawan','!=','dummy')->orderBy('users.name','asc')->groupBy('users.nik')->get();

        $getUserMsp = User::join('presence__history', 'presence__history.nik', '=', 'users.nik')->selectRaw('`users`.`nik` AS `nik`,`users`.`name` AS `text`')->where('id_company','2')->where('status_karyawan','!=','dummy')->orderBy('users.name','asc')->groupBy('users.nik')->get();

        $getUserSipSims = User::join('role_user','role_user.user_id','users.nik')->join('roles','role_user.role_id','roles.id')->join('presence__history', 'presence__history.nik', '=', 'users.nik')->selectRaw('`users`.`nik` AS `nik`,`users`.`name` AS `text`')->where('id_company','1')->where('group', 'Synergy System Management')->where('status_karyawan','!=','dummy')->orderBy('users.name','asc')->groupBy('users.nik')->get();

        $getUserSipPmds = User::join('role_user','role_user.user_id','users.nik')->join('roles','role_user.role_id','roles.id')->join('presence__history', 'presence__history.nik', '=', 'users.nik')->selectRaw('`users`.`nik` AS `nik`,`users`.`name` AS `text`')->where('id_company','1')->where('group', 'Solutions & Partnership Management')->where('status_karyawan','!=','dummy')->orderBy('users.name','asc')->groupBy('users.nik')->get();

        $getUserSipFin = User::join('presence__history', 'presence__history.nik', '=', 'users.nik')->selectRaw('`users`.`nik` AS `nik`,`users`.`name` AS `text`')->where('id_company','1')->where('id_division', 'FINANCE')->where('status_karyawan','!=','dummy')->orderBy('users.name','asc')->groupBy('users.nik')->get();

        $getUserSipHc = User::join('role_user','role_user.user_id','users.nik')->join('roles','role_user.role_id','roles.id')->join('presence__history', 'presence__history.nik', '=', 'users.nik')
                ->selectRaw('`users`.`nik` AS `nik`,`users`.`name` AS `text`')
                ->where('group','Human Capital Management')
                ->orderBy('users.name','asc')->groupBy('users.nik')->get();

        $getUserSipPmo = User::join('role_user','role_user.user_id','users.nik')->join('roles','role_user.role_id','roles.id')->join('presence__history', 'presence__history.nik', '=', 'users.nik')->selectRaw('`users`.`nik` AS `nik`,`users`.`name` AS `text`')->where('id_company','1')->where('group', 'Program & Project Management')->where('status_karyawan','!=','dummy')->orderBy('users.name','asc')->groupBy('users.nik')->get();

        $getUserShifting = User::join('presence__history', 'presence__history.nik', '=', 'users.nik')->join('presence__shifting_user', 'presence__shifting_user.nik', '=', 'users.nik')->selectRaw('`users`.`nik` AS `nik`,`users`.`name` AS `text`')->where('id_company','1')->where('status_karyawan','!=','dummy')->orderBy('users.name','asc')->groupBy('users.nik')->get();
        
        $getUserSipSccam = User::join('role_user','role_user.user_id','users.nik')->join('roles','role_user.role_id','roles.id')->join('presence__history', 'presence__history.nik', '=', 'users.nik')->selectRaw('`users`.`nik` AS `nik`,`users`.`name` AS `text`')->where('id_company','1')->where('group', 'Internal Chain Management')->where('status_karyawan','!=','dummy')->orderBy('users.name','asc')->groupBy('users.nik')->get();

        return array(
            collect(["text"=>'SIP',"children"=>$getUserSip]),
            collect(["text"=>'SIMS',"children"=>$getUserSipSims]),
            collect(["text"=>'PMDS',"children"=>$getUserSipPmds]),
            collect(["text"=>'FIN',"children"=>$getUserSipFin]),
            collect(["text"=>'HC',"children"=>$getUserSipHc]),
            collect(["text"=>'PMO',"children"=>$getUserSipPmo]),
            collect(["text"=>'SCCAM', "children"=>$getUserSipSccam]),
            collect(["text"=>'MSP',"children"=>$getUserMsp]),
            collect(["text"=>'Shifting', "children"=>$getUserShifting])
        );
    }

    public function presenceSetting() {
        $notifAll = $this->notification_legacy();
        
        $notif = $notifAll["notif"];
        $notifOpen = $notifAll["notifOpen"];
        $notifsd = $notifAll["notifsd"];
        $notiftp = $notifAll["notiftp"];
        $notifClaim = $notifAll["notifClaim"];

        return view('presence.setting', compact('notif','notifOpen','notifsd','notiftp', 'notifClaim'))->with(['initView'=>$this->initMenuBase()]);
    }

    public function presenceSettingGetListUser(){

        $getLocationUser = PresenceLocationUser::join('presence__location', 'presence__location.id', '=', 'presence__location_user.location_id')
                            ->select('user_id', )
                            ->selectRaw('GROUP_CONCAT(`location_name` ORDER BY `location_name` ASC SEPARATOR ", ") as `location_name`')
                            ->groupBy('user_id');

        return User::where('id_company','=',1)
            ->join('role_user', 'role_user.user_id', '=', 'users.nik')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->leftJoinSub($getLocationUser, 'location', function($join){
                $join->on('users.nik', '=', 'location.user_id');
            })
            ->leftJoin('presence__setting', 'presence__setting.id', '=', 'users.id_presence_setting')
            ->select('users.nik','users.name', 'roles.name as role', DB::raw('IFNULL(`location_name`,"-") AS `location_name`'), 'setting_on_time', 'setting_check_out')
            ->where('status_karyawan','<>','dummy')
            ->get(); 
    }

    public function presenceSettingShowSchedule(Request $request)
    {
        $getSchedule = User::where('id_company','=',1)
            ->leftJoin('presence__setting', 'presence__setting.id', '=', 'users.id_presence_setting')
            ->select('users.nik','users.name', 'setting_on_time', 'setting_check_out')
            ->where('nik', $request->nik)
            ->first(); 

        return array("data"=>$getSchedule);
    }

    public function presenceSettingShowLocationExisting(Request $request)
    {
        $getLocation = User::where('id_company','=',1)
            ->leftJoin('presence__location_user', 'presence__location_user.user_id', '=', 'users.nik')
            ->leftJoin('presence__location', 'presence__location.id', '=', 'presence__location_user.location_id')
            ->select('nik',  DB::raw('GROUP_CONCAT(`location_name`) as `location_name`'), 'users.name', DB::raw('GROUP_CONCAT(`location_id`) as `location_id`'))
            ->where('nik', $request->nik)
            ->groupBy('nik')
            ->get(); 

        return array("data"=>$getLocation);
    }

    public function presenceSettingShowLocationUser(Request $request)
    {
        $location = PresenceLocationUser::where('user_id', $request->nik)->pluck('location_id');

        $getLocation = PresenceLocation::select(DB::raw('`presence__location`.`id` AS `id`,`location_name` AS `text`'))->whereNotIn('id', $location)->get();

        return array("data" => $getLocation);
    }

    public function presenceSettingShowAllLocation()
    {
        $getLocation = PresenceLocation::select(DB::raw('`presence__location`.`id` AS `id`,`location_name` AS `text`'))
            ->where('location_status','ACTIVE')
            ->get();

        return array("data" => $getLocation);
    }

    public function presenceSettingSetSchedule(Request $request)
    {
        $data = DB::table('presence__setting')
            ->where('setting_on_time', $request->work_in)
            ->Where('setting_check_out', $request->work_out);

        if ($data->exists()) {
            $update_setting = User::where('nik', $request->nik)->first();
            $update_setting->id_presence_setting = $data->first()->id;
            $update_setting->update();            

        } else {

            $tambah_setting = new PresenceSetting();
            $tambah_setting->setting_on_time = $request->work_in;
            $tambah_setting->setting_injury_time = date('H:i:s',strtotime('+30 minutes +00 seconds',strtotime($request->work_in)));
            $tambah_setting->setting_late = date('H:i:s',strtotime('+30 minutes +01 seconds',strtotime($request->work_in)));
            $tambah_setting->setting_check_out = $request->work_out;
            $tambah_setting->date_add = date('Y-m-d h:i:s');
            $tambah_setting->date_update = date('Y-m-d h:i:s');
            $tambah_setting->save();

            $update_setting = User::where('nik', $request->nik)->first();
            $update_setting->id_presence_setting = $tambah_setting->id;
            $update_setting->update();

        }

        return redirect()->back();
    }

    public function presenceSettingSetLocation(Request $request)
    {
        // $array  = explode(',', $request->location_before_id);

        // $array2 = $request->location_after_id;

        // if (isset($request->location_before_id)) {
        //     foreach ($array as $before) {
        //         $delete       = PresenceLocationUser::where('user_id',$request->nik)->where('location_id',$before)->first();
        //         $delete->delete();
        //     }
        // }

        // foreach ($array2 as $after) {
        //     $add                = new PresenceLocationUser();
        //     $add->user_id       = $request->nik;
        //     $add->location_id   = $after;
        //     $add->date_add      = date("Y-m-d h:i:s");
        //     $add->save();  
        // }

        $location = $request->location_update;

        $delete = PresenceLocationUser::where('user_id',$request->nik)->delete();

        foreach ($location as $location) {
            $add                = new PresenceLocationUser();
            $add->user_id       = $request->nik;
            $add->location_id   = $location;
            $add->date_add      = date("Y-m-d h:i:s");
            $add->save();  
        }

        return redirect()->back();
    }

    public function presenceSettingAddLocation(Request $request)
    {
        $add = new PresenceLocation();
        $add->location_name = $request->location_name;
        $add->location_lat = $request->location_lat;
        $add->location_lng = $request->location_lng;
        $add->location_radius = '500';
        $add->location_status = 'ACTIVE';
        $add->date_add = date("Y-m-d h:i:s");
        $add->date_update = date("Y-m-d h:i:s");
        $add->save();

        return redirect()->back();
    }

    public function getLocationNameFromLatLng(Request $request) {
        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng={$latitude},{$longitude}&key=".env('GOOGLE_API_KEY_GLOBAL');

        // Membuat request ke Google Maps Geocoding API
        $response = file_get_contents($url);

        // Mengecek apakah request berhasil
        if ($response === false) {
            return "Error fetching data.";
        }

        // Parsing hasil JSON
        $data = json_decode($response, true);

        // Mengecek apakah hasil valid
        if ($data['status'] == 'OK') {
            // Mengambil alamat format terbaca manusia (formatted_address) dari hasil pertama
            return $data['results'][0]['formatted_address'];
        }

        return "Location not found.";
    }

    public function getLocationByUser(Request $req)
    {
        $getAllLocationUser = PresenceLocationUser::join('presence__location', 'presence__location.id', '=', 'presence__location_user.location_id')
                            ->join('users','users.nik','presence__location_user.user_id')
                            ->select('user_id', 'location_name','location_radius','location_lat','location_lng')
                            ->where('user_id',Auth::User()->nik)->get();

        return $getCurrentLocation = PresenceLocation::join('presence__location_user', 'presence__location.id', '=', 'presence__location_user.location_id')->select('location_name')->where('user_id',Auth::User()->nik)->where('presence__location.id',$req->id_location)->first()->location_name;
    }

    function getNtpTime($server = "pool.ntp.org", $port = 123, $timezone = 'Asia/Jakarta')
    {
        $sock = fsockopen("udp://$server", $port, $errNo, $errStr, 2);
        if (!$sock) return false;

        $data = "\010" . str_repeat("\0", 47);
        fwrite($sock, $data);
        $response = fread($sock, 48);
        fclose($sock);

        if (strlen($response) != 48) return false;

        $unpack = unpack('N12', $response);
        $timestamp = sprintf('%u', $unpack[9]) - 2208988800; // Convert NTP time

        // Convert UTC to your local timezone
        return \Carbon\Carbon::createFromTimestamp($timestamp, 'UTC')->setTimezone($timezone);
    }

    public function checkIn(Request $req) {
        $history = new PresenceHistory();
        $agent = new Agent();
        if (isset(Auth::User()->nik)) {
            $req->nik = Auth::User()->nik;
        }

        $setting_schedule = User::with('presence_setting')
            ->where('nik',$req->nik)
            ->first()
            ->presence_setting;

        if (PresenceShiftingUser::join('presence__shifting','presence__shifting.nik','presence__shifting_user.nik')->where('presence__shifting_user.nik',$req->nik)->where('tanggal_shift',date('Y-m-d'))->exists()){
            $setting_schedule = $this->makeShiftingSchedule($req->nik,"15");
        }

        $ntpTime = $this->getNtpTime();

        if (!$ntpTime) {
            return response()->json(['error' => 'Failed to fetch NTP time'], 500);
        }

        $history->nik = $req->nik;
        $history->presence_setting = $setting_schedule->id;
        $history->presence_schedule = $setting_schedule->setting_on_time;
        // $history->presence_actual = $req->presence_actual;
        $history->presence_actual = $ntpTime->format('Y-m-d H:i:s');
        $history->presence_location = $req->id_location;
        $history->presence_condition = $this->checkPresenceCondition($req->presence_actual,$setting_schedule);
        $history->presence_type = "Check-In";
        $history->ip_address = $req->getClientIp();
        $deviceType = $agent->device();
        $platform = $agent->platform();
        $platformVersion = $agent->version($platform);
        $browser = $agent->browser();
        $browserVersion = $agent->version($browser);
        $history->device = "Device: " . $deviceType . " Platform: " . $platform . ' ' . $platformVersion . ' Browser : ' . $browser . ' ' . $browserVersion;

        // return $history;
        // return $setting_schedule;

        $history->save();
    }

    public function checkOut(Request $req) {
        $agent = new Agent();
        $history = new PresenceHistory();
        if (isset(Auth::User()->nik)) {
            $req->nik = Auth::User()->nik;
        }

        $setting_schedule = User::with('presence_setting')
            ->where('nik',$req->nik)
            ->first()
            ->presence_setting;

        $getCheckout = PresenceHistory::where('nik',$req->nik)->orderby('id','desc')->first();

        if (PresenceShiftingUser::join('presence__shifting','presence__shifting.nik','presence__shifting_user.nik')->where('presence__shifting_user.nik',$req->nik)->where('tanggal_shift',date('Y-m-d'))->exists()){
            $setting_schedule = $this->makeShiftingSchedule($req->nik,"15");
        }

        $history->nik = $req->nik;
        $history->presence_setting = $setting_schedule->id;
        $history->presence_schedule = $setting_schedule->setting_check_out;
        $history->presence_actual = $req->presence_actual;
        $history->presence_location = $getCheckout->presence_location;
        $history->presence_condition = "-";
        $history->ip_address = $req->getClientIp();
        $history->presence_type = "Check-Out";
        $deviceType = $agent->device();
        $platform = $agent->platform();
        $platformVersion = $agent->version($platform);
        $browser = $agent->browser();
        $browserVersion = $agent->version($browser);
        $history->device = "Device: " . $deviceType . " Platform: " . $platform . ' ' . $platformVersion . ' Browser : ' . $browser . ' ' . $browserVersion;

        $history->save();
    }

    public function checkPresenceCondition($presenceActual,$settingSchedule){
        $actual = new DateTime($presenceActual);
        if ($actual->diff(new DateTime($settingSchedule->setting_on_time))->format('%R') == '+') {
            return "On-Time";
        } else if ($actual->diff(new DateTime($settingSchedule->setting_on_time))->format('%R') == '-' && $actual->diff(new DateTime($settingSchedule->setting_injury_time))->format('%R') == '+') {
            return "Injury-Time";
        } else {
            return "Late";
        }
    }

    public function makeShiftingSchedule($nik,$span)
    {
        $getData = 'true';
        if (PresenceHistory::where('nik',$nik)->orderBy('presence_actual','desc')->exists()) {
            $getDate = PresenceHistory::select(DB::raw("CAST(`presence_actual` AS DATE) AS `presence_actual_date`"),'presence_type')->where('nik',$nik)->orderBy('presence_actual','desc')->first()->presence_type;
        } else {
            $getDate = 'false';
        }

        if ($getDate == 'Check-In') {
            $getDate = 'true';
        } else {
            $getDate = 'false';
        }

        if($getDate == 'true'){
            $getDate = PresenceHistory::select(DB::raw("CAST(`presence_actual` AS DATE) AS `presence_actual_date`"))->where('nik',$nik)->orderBy('presence_actual','desc')->first()->presence_actual_date;

            $shiftingSchedule = PresenceShifting::where('nik',$nik)
                ->where('tanggal_shift',$getDate)
                ->whereNotExists(function($query)
                {
                    $query->select(DB::raw(1))
                          ->from('presence__history')
                          ->whereRaw('presence__history.presence_setting = presence__shifting.id')
                          ->where('presence_type','Check-Out');
                })
                ->first();
        } else {
            $shiftingSchedule = PresenceShifting::where('nik',$nik)
                ->where('tanggal_shift',date('Y-m-d'))
                ->whereNotExists(function($query)
                {
                    $query->select(DB::raw(1))
                          ->from('presence__history')
                          ->whereRaw('presence__history.presence_setting = presence__shifting.id')
                          ->where('presence_type','Check-Out');
                })
                ->first();
        }


        // return $shiftingSchedule;

        // $shiftingSchedule = PresenceShifting::where('nik',$nik)
        //     ->where('tanggal_shift',date('Y-m-d'))
        //     ->first();

        $start = substr($shiftingSchedule->start, 11,8);
        $shiftingSchedule->setting_on_time = $start;
        $shiftingSchedule->setting_injury_time = date("H:i:s",strtotime('+' . $span . ' minutes',strtotime($start)));
        $shiftingSchedule->setting_late = date("H:i:s",strtotime('+' . $span . ' minutes +1 seconds',strtotime($start)));
        $shiftingSchedule->setting_check_out = substr($shiftingSchedule->end, 11,8);

        return $shiftingSchedule;
    }

    public function presenceShifting() {
        $notifAll = $this->notification_legacy();
        
        $notif = $notifAll["notif"];
        $notifOpen = $notifAll["notifOpen"];
        $notifsd = $notifAll["notifsd"];
        $notiftp = $notifAll["notiftp"];
        $notifClaim = $notifAll["notifClaim"];

        // return $this->getOptionGrouped();
        
        return view('presence.shifting', compact('notif','notifOpen','notifsd','notiftp', 'notifClaim'))
            ->with([
                'initView'=>$this->initMenuBase(),
                'sidebar_collapse' => 'True',
                'shiftingOptions' => $this->getOptionGrouped(),
                'feature_item'=>$this->RoleDynamic('presence_shifting')
            ]);
    }

    public function shiftingGetProject(){
        return PresenceShiftingProject::orderBy('id','DESC')->where('project_name','!=','PASPAMPRES')->get();
    }

    public function shiftingGetOption(){
        return PresenceShiftingOption::orderBy('id','DESC')
            ->where('status','ACTIVE')
            ->get();
    }

    public function shiftingGetUsers(){
        return User::select('nik','name')
            ->where('status_karyawan','<>','dummy')
            ->where('id_company','=','1')
            ->orderBy('nik')
            ->get();
    }

    public function getSchedule(Request $req){
        $PresenceShifting = PresenceShifting::orderBy('start','DESC');

        if(isset($req->idUser)){
            $PresenceShifting->where('nik','=',$req->idUser);
        }

        if(isset($req->project)){
            $PresenceShifting->where('presence__shifting_user.shifting_project','=',$req->project);
        }


        if(isset($req->start)){
            $PresenceShifting->whereBetween('tanggal_shift',[$req->start,$req->end]);
        } else {
            $PresenceShifting->whereBetween('tanggal_shift',[date('Y-m') . "-01",date_format(date_add(date_create(date('Y-m') . "-12"),date_interval_create_from_date_string("1 month")),"Y-m-d")]);
        }

        return $PresenceShifting;
    }

    public function getScheduleThisMonth(Request $req){
        return $this->getSchedule($req)
            ->join('users', 'users.nik', '=', 'presence__shifting.nik')
            ->where('status_karyawan', '!=', 'dummy')
            ->orderBy('start','DESC')
            ->get()
            ->map(function($data){
                if(!in_array($data->className,["Libur","Pagi","Sore","Malam","HO","Helpdesk","On-Site","Off-Site"])){
                    $data->className = "Custom";
                }
                return $data;
            })
            ->toArray();
    }

    public function getSummaryThisMonth(Request $req){
        $count_shift = $this->getSchedule($req);
        $count_shift->getQuery()->orders = null;
        $count_shift = $count_shift ->join('users', 'users.nik', '=', 'presence__shifting.nik')
            ->selectRaw('`users`.`nik`,`className`, COUNT(*) AS `count`')
            ->where('status_karyawan', '!=', 'dummy')
            ->groupBy('className')
            ->groupBy('presence__shifting.nik');

        // return $count_shift->get();

        $shifting_option = PresenceShiftingOption::select(
                DB::raw('DISTINCT presence__shifting_option.name_option'),
                'presence__shifting_option.class_shifting',
            );

        $count_shift = DB::table($count_shift,'count_shift')
            ->leftJoinSub($shifting_option,'shifting_option',function($join){
                $join->on('count_shift.className','=','shifting_option.name_option');
            })->get()->groupBy('nik');

        // return $count_shift;

        $nicknameM = DB::table('users')
                   ->select('name as nickname','nik')
                   ->where('status_karyawan', '!=', 'dummy')
                   ->where('name','RLIKE',"^M\\b")
                   ->orWhere('name','RLIKE','^Muhammad\\b')
                   ->orWhere('name','RLIKE','^Mochammad\\b')
                   ->orWhere('name','RLIKE','^Muhammad\\b');

        $nicknameAll = DB::table('users')
                    ->select('name as nickname_all','nik')
                    ->whereNotIn('name',function($query){
                        $query->select('name')
                        ->where('status_karyawan', '!=', 'dummy')
                        ->where('name','RLIKE','^M\\b')
                       ->orWhere('name','RLIKE','^Muhammad\\b')
                       ->orWhere('name','RLIKE','^Mochammad\\b')
                       ->orWhere('name','RLIKE','^Muhammad\\b')
                        ->from('users');
                    });

        $shifting_summary = DB::table('presence__shifting_user')
            ->select(
                DB::raw('`users`.`nik` AS `id`'),
                'users.name',
                // DB::raw('(CASE WHEN substring_index(`users`.`name`, " ", 1) = "M" THEN substring_index(`users`.`name`, " ", 2) ELSE substring_index(`users`.`name`, " ", 1) END) AS `nickname`'),
                // DB::raw('`users`.`name` AS `nickname_all`'),
                DB::raw('presence__shifting_project.id AS `project_id`'),
                'presence__shifting_project.project_name',
                'nickname',
                'nickname_all'
            )
            ->LeftjoinSub($nicknameM, 'nickname__nik', function ($join) {
                $join->on('presence__shifting_user.nik', '=', 'nickname__nik.nik');
            })
            ->LeftjoinSub($nicknameAll, 'nicknameAll__nik', function ($join) {
                $join->on('presence__shifting_user.nik', '=', 'nicknameAll__nik.nik');
            })
            ->join('users','users.nik','=','presence__shifting_user.nik')
            ->join('presence__shifting_project','presence__shifting_project.id','=','presence__shifting_user.shifting_project')
            ->where('status_karyawan', '!=', 'dummy')
            ->get();

        $shifting_summary = $shifting_summary->map(function ($item, $key) use ($count_shift) {
            if(!empty($count_shift[$item->id])) {
                $item->shifting_summary = $count_shift[$item->id];
            }
            return $item;
        });

        return $shifting_summary;
    }

    public function getScheduleThisProject(Request $req){
        return $this->getSchedule($req)
            // ->select('presence__shifting.id','presence__shifting.nik','presence__shifting.title','presence__shifting.start','presence__shifting.end','presence__shifting.className','presence__shifting.hadir','presence__shifting.tanggal_shift','presence__shifting.nik','presence__shifting.id_project','presence__shifting.created_at')
            ->join('presence__shifting_user','presence__shifting_user.nik','=','presence__shifting.nik')
            ->join('users', 'users.nik', '=', 'presence__shifting_user.nik')
            ->where('status_karyawan', '!=', 'dummy')
            ->orderBy('start','DESC')
            ->get()
            ->toArray();
    }

    public function getScheduleThisUser(Request $req){
        return $this->getSchedule($req)
            ->orderBy('start','DESC')
            ->get()
            ->map(function($data){
                if(!in_array($data->className,["Libur","Pagi","Sore","Malam","HO","Helpdesk","On-Site","Off-Site"])){
                    $data->className = "Custom";
                }
                return $data;
            })
            ->toArray();
    }

    public function createSchedule(Request $req){
        $user = DB::table('users')->where("nik",$req->nik)->first();

        DB::table('presence__shifting')
            ->insert(
                [
                    'id' => NULL,
                    'nik' => $user->nik,
                    'title' => $req->title,
                    'start' => $req->start,
                    'end' => $req->end,
                    'className' => $req->shift,
                    'hadir' => "00:00:00",
                    'tanggal_shift' => substr($req->start,0,10),
                    'id_project' => $req->id_project,
                    'created_at' => date('Y-m-d h:i:s'),
                    
                ]
            );

        DB::table('presence__shifting_log')
            ->insert(
                    [
                        'nik_user' => Auth::user()->nik,
                        'title' => $req->title,
                        'start_before' => $req->start_before,
                        'end_before' => $req->end_before,
                        'className_before' => $req->shift,  
                        'created_at' => date('Y-m-d h:i:s'),
                        'status' => 'create',                   
                    ]
                );

        return DB::table('presence__shifting')->orderBy('id','DESC')->first()->id;
    }

    public function deleteSchedule (Request $req) {
        $shifting = DB::table('presence__shifting')->select('start','end','title','className')->where('id','=',$req->id)->first();

        DB::table('presence__shifting_log')
            ->insert(
                    [
                        'nik_user' => Auth::user()->nik,
                        'title' => $shifting->title,
                        'start_before' => date('Y-m-d h:i:s', strtotime($shifting->start)),
                        'end_before' => date('Y-m-d h:i:s', strtotime($shifting->end)),
                        'className_before' => $shifting->className, 
                        'created_at' => date('Y-m-d h:i:s'),
                        'status' => 'delete',                   
                    ]
                );

        DB::table('presence__shifting')
            ->where('id','=',$req->id)
            ->delete();

        return "success";
    }

    public function modifyUserShifting(Request $request){
        $date = date('Y-m-d h:i:s', time());

        if($request->on_project == "0"){
            DB::table('presence__shifting_user')
                ->where('nik','=',$request->id_user)
                ->delete();
            return redirect('presence/shifting')->with('message', "Delete User " . " success.");
        } else {
            if (DB::table('presence__shifting_user')->where('nik',$request->id_user)->where('shifting_project',$request->on_project)->get() == NULL){
                DB::table('presence__shifting_user')
                ->insert([
                        'nik' => $request->id_user,
                        'shifting_project' => $request->on_project,
                    ]);
            } else {
                DB::table('presence__shifting_user')
                    ->where('nik','=',$request->id_user)
                    ->delete();

                DB::table('presence__shifting_user')
                    ->insert([
                        'nik' => $request->id_user,
                        'shifting_project' => $request->on_project,
                    ]);
            }
            return redirect('presence/shifting')->with('message', "Add User " . " success.");
        }
    }

    public function getReportShifting(Request $req)
    {
        $beforeData = PresenceShifting::join('users','users.nik','=','presence__shifting.nik')
            ->join('presence__shifting_project', 'presence__shifting_project.id', '=', 'presence__shifting.id_project')
            ->selectRaw('`presence__shifting`.`id`,`users`.`name`, `project_name`, `className`,DATE_FORMAT(`start`, "%H:%i") as `start`, DATE_FORMAT(`end`, "%H:%i") as `end`, DATE_FORMAT(`presence__shifting`.`tanggal_shift`, "%d-%m-%Y") as `tanggal_shift`, DATE_FORMAT(`presence__shifting`.`created_at`, "%d-%m-%Y") as `created_at`')
            ->orderBy('presence__shifting.created_at','asc')
            ->whereMonth('tanggal_shift', $req->month)
            ->whereYear('tanggal_shift', $req->year)
            ->where('presence__shifting.created_at', '<', $req->start)
            ->get();

        $afterData = PresenceShifting::join('users','users.nik','=','presence__shifting.nik')
            ->join('presence__shifting_project', 'presence__shifting_project.id', '=', 'presence__shifting.id_project')
            ->selectRaw('`presence__shifting`.`id`,`users`.`name`, `project_name`, `className`,DATE_FORMAT(`start`, "%H:%i") as `start`, DATE_FORMAT(`end`, "%H:%i") as `end`, DATE_FORMAT(`presence__shifting`.`tanggal_shift`, "%d-%m-%Y") as `tanggal_shift`, DATE_FORMAT(`presence__shifting`.`created_at`, "%d-%m-%Y") as `created_at`')
            ->orderBy('presence__shifting.created_at','asc')
            ->whereMonth('tanggal_shift', $req->month)
            ->whereYear('tanggal_shift', $req->year)
            ->where('presence__shifting.created_at', '<', $req->end)
            ->get();

        $beforeDate = Carbon::parse($req->start)->format("d M Y");
        $afterDate = Carbon::parse($req->end)->format("d M Y");

        $spreadsheet = new Spreadsheet();

        $spreadsheet->removeSheetByIndex(0);
        $spreadsheet->addSheet(new Worksheet($spreadsheet,'Diff'));
        $spreadsheet->addSheet(new Worksheet($spreadsheet,'Before'));
        $spreadsheet->addSheet(new Worksheet($spreadsheet,'After'));
        $diffSheet = $spreadsheet->setActiveSheetIndex(0);
        $beforeSheet = $spreadsheet->setActiveSheetIndex(1);
        $afterSheet = $spreadsheet->setActiveSheetIndex(2);

        $beforeSheet->mergeCells('A3:H3');
        $afterSheet->mergeCells('A3:H3');
        $diffSheet->mergeCells('A2:D2');
        $diffSheet->mergeCells('E2:H2');
        $normalStyle = [
            'font' => [
                'name' => 'Calibri',
                'size' => 11
            ],
        ];

        $titleStyle = $normalStyle;
        $titleStyle['alignment'] = ['horizontal' => Alignment::HORIZONTAL_CENTER];
        $titleStyle['borders'] = ['outline' => ['borderStyle' => Border::BORDER_THIN]];
        $titleStyle['fill'] = ['fillType' => Fill::FILL_SOLID, 'startColor' => ["argb" => "FFFCD703"]];
        $titleStyle['font']['bold'] = true;

        $titleStyle2 = $normalStyle;
        $titleStyle2['alignment'] = ['horizontal' => Alignment::HORIZONTAL_CENTER];
        $titleStyle2['borders'] = ['outline' => ['borderStyle' => Border::BORDER_THIN]];
        $titleStyle2['fill'] = ['fillType' => Fill::FILL_SOLID, 'startColor' => ["argb" => "fffa7952"]];
        $titleStyle2['font']['bold'] = true;

        $headerStyle = $normalStyle;
        $headerStyle['font']['bold'] = true;

        $diffSheet->getStyle('A2:D2')->applyFromArray($titleStyle);
        $diffSheet->getStyle('E2:H2')->applyFromArray($titleStyle2);
        $diffSheet->setCellValue('A2','Before');
        $diffSheet->setCellValue('E2','After');

        $diffbeforeData = PresenceShifting::join('users','users.nik','=','presence__shifting.nik')
            ->join('presence__shifting_project', 'presence__shifting_project.id', '=', 'presence__shifting.id_project')
            ->selectRaw('`users`.`name`, `className`, DATE_FORMAT(`presence__shifting`.`tanggal_shift`, "%d-%m-%Y") as `tanggal_shift`,DATE_FORMAT(`presence__shifting`.`created_at`, "%d-%m-%Y") as `created_at`')
            ->orderBy('presence__shifting.created_at','asc')
            ->whereIn('presence__shifting.id',array_values(array_diff($beforeData->pluck('id')->toArray(), $afterData->pluck('id')->toArray())))
            ->get();

        $diffafterData = PresenceShifting::join('users','users.nik','=','presence__shifting.nik')
            ->join('presence__shifting_project', 'presence__shifting_project.id', '=', 'presence__shifting.id_project')
            ->selectRaw('`users`.`name`, `className`, DATE_FORMAT(`presence__shifting`.`tanggal_shift`, "%d-%m-%Y") as `tanggal_shift`,DATE_FORMAT(`presence__shifting`.`created_at`, "%d-%m-%Y") as `created_at`')
            ->orderBy('presence__shifting.created_at','asc')
            ->whereIn('presence__shifting.id',array_values(array_diff($afterData->pluck('id')->toArray(), $beforeData->pluck('id')->toArray())))
            ->get();

        $headerContent = ["Name", "Shift", "Shifting Date","Created Date", "Name", "Shift", "Shifting Date", "Created Date"];
        $diffSheet->getStyle('A3:H3')->applyFromArray($headerStyle);
        $diffSheet->fromArray($headerContent,NULL,'A3');

        $diffbeforeData->map(function($item,$key) use ($diffSheet){
            $diffSheet->fromArray(array_values($item->toArray()),NULL,'A' . ($key + 4));
        });
        $diffafterData->map(function($item,$key) use ($diffSheet){
            $diffSheet->fromArray(array_values($item->toArray()),NULL,'E' . ($key + 4));
        });

        $diffSheet->getColumnDimension('A')->setAutoSize(true);
        $diffSheet->getColumnDimension('B')->setAutoSize(true);
        $diffSheet->getColumnDimension('C')->setAutoSize(true);
        $diffSheet->getColumnDimension('D')->setAutoSize(true);
        $diffSheet->getColumnDimension('E')->setAutoSize(true);
        $diffSheet->getColumnDimension('F')->setAutoSize(true);
        $diffSheet->getColumnDimension('G')->setAutoSize(true);
        $diffSheet->getColumnDimension('H')->setAutoSize(true);

        //before sheet

        $beforeSheet->getStyle('A3:H3')->applyFromArray($titleStyle);
        $beforeSheet->setCellValue('A1','Last Update: ');
        $beforeSheet->setCellValue('B1',$beforeDate);
        $beforeSheet->setCellValue('A3','Report Shifting Before');

        $headerContent = ["No", "Name", "Project", "Shift(Title)", "Start Shift", "End Shift ", "Shifting Date", "Created Shifting"];
        $beforeSheet->getStyle('A4:H4')->applyFromArray($headerStyle);
        $beforeSheet->fromArray($headerContent,NULL,'A4');

        $beforeData->map(function($item,$key) use ($beforeSheet){
            $data = array_values($item->toArray());
            array_shift($data);
            $beforeSheet->fromArray(array_merge([$key + 1],$data),NULL,'A' . ($key + 5));
        });

        $beforeSheet->getColumnDimension('A')->setAutoSize(true);
        $beforeSheet->getColumnDimension('B')->setAutoSize(true);
        $beforeSheet->getColumnDimension('C')->setAutoSize(true);
        $beforeSheet->getColumnDimension('D')->setAutoSize(true);
        $beforeSheet->getColumnDimension('E')->setAutoSize(true);
        $beforeSheet->getColumnDimension('F')->setAutoSize(true);
        $beforeSheet->getColumnDimension('G')->setAutoSize(true);
        $beforeSheet->getColumnDimension('H')->setAutoSize(true);

        //after sheet

        $afterSheet->getStyle('A3:H3')->applyFromArray($titleStyle);
        $afterSheet->setCellValue('A1','Last Update: ');
        $afterSheet->setCellValue('B1',$afterDate);
        $afterSheet->setCellValue('A3','Report Shifting After');

        $headerContent = ["No", "Name", "Project", "Shift(Title)", "Start Shift", "End Shift ", "Shifting Date", "Created Shifting"];
        $afterSheet->getStyle('A4:H4')->applyFromArray($headerStyle);
        $afterSheet->fromArray($headerContent,NULL,'A4');

        $afterData->map(function($item,$key) use ($afterSheet){
            $data = array_values($item->toArray());
            array_shift($data);
            $afterSheet->fromArray(array_merge([$key + 1],$data),NULL,'A' . ($key + 5));
        });

        $afterSheet->getColumnDimension('A')->setAutoSize(true);
        $afterSheet->getColumnDimension('B')->setAutoSize(true);
        $afterSheet->getColumnDimension('C')->setAutoSize(true);
        $afterSheet->getColumnDimension('D')->setAutoSize(true);
        $afterSheet->getColumnDimension('E')->setAutoSize(true);
        $afterSheet->getColumnDimension('F')->setAutoSize(true);
        $afterSheet->getColumnDimension('G')->setAutoSize(true);
        $afterSheet->getColumnDimension('H')->setAutoSize(true);

        $spreadsheet->setActiveSheetIndex(0);

        $fileName = 'Report Log Shifting '. $req->year .' '. $req->month . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        
        $writer = new Xlsx($spreadsheet);
        ob_end_clean();
        return $writer->save("php://output");
    }

    public function modifyOptionShifting(Request $req){

        if($req->new_label != ""){
            $option = new PresenceShiftingOption();
            $option->name_option = $req->new_label;
            $option->start_shifting = $req->new_checkin;
            $option->end_shifting = $req->new_checkout;
            $option->id_project = $req->new_id_project;
            $option->class_shifting = $req->new_class_shifting;
            $option->status = $req->new_value;
            $option->save();
        }

        foreach($req->option_id as $option_key => $option_id){
            $option = PresenceShiftingOption::find($option_id);
            $option->name_option = $req->option_label[$option_key];
            $option->start_shifting = $req->checkin_value[$option_key];
            $option->end_shifting = $req->checkout_value[$option_key];
            $option->status = $req->status_value[$option_key];
            $option->save();
        }
    }

    public function getOptionGrouped(){
        return PresenceShiftingOption::orderBy('presence__shifting_project.id','DESC')
            ->select(
                'presence__shifting_option.id',
                'presence__shifting_option.name_option',
                'presence__shifting_option.start_shifting',
                'presence__shifting_option.end_shifting',
                'presence__shifting_option.class_shifting',
                'presence__shifting_option.id_project',
                'presence__shifting_option.status',
                'presence__shifting_project.project_name'

            )
            ->orderBy('presence__shifting_option.id','ASC')
            ->join('presence__shifting_project','presence__shifting_option.id_project','=','presence__shifting_project.id')
            ->get()
            ->groupBy('project_name')
            ->sort()
            ->toArray();
    }

    public function getPresenceParameter(Request $req){
        // return PresenceLocationUser::with('location')->where('user_id',$req->nik)->get();
        return PresenceLocationUser::selectRaw('presence__location.*,presence__location_user.*')
            ->rightJoin('presence__location','presence__location_user.location_id','presence__location.id')
            ->where('user_id',$req->nik)->get();
    }

    public function getPresenceReportData($typeData = "notAll",$nik = "", $typeCompany = "all",$date){
        // $startDate = Carbon::now()->subMonths(1)->format("Y-m-16");
        // $endDate = Carbon::now()->format("Y-m-16");
        $startDate = $date["startDate"];
        $endDate = $date["endDate"];

        $workDays = $this->getWorkDays($startDate,$endDate)["workdays"]->values();
        $shiftingUserList = PresenceShiftingUser::pluck('nik')->toArray();
        // return gettype($shiftingUserList);
        // return in_array(1211094060, $shiftingUserList) ? "yes" : "no";
        // return $shiftingUserList;

        $parameterUser = PresenceHistory::select(DB::raw('presence__history.*'))
            ->whereRaw('`presence_actual` BETWEEN "' . $startDate . ' 00:00:00" AND "' . $endDate . ' 23:59:59"')
            ->join('users','users.nik','=','presence__history.nik');
            // ->orderBy('nik','DESC');

        if($nik == ""){
            $parameterUser = $parameterUser;
        } else {
            $parameterUser = $parameterUser->whereIn('users.nik',$nik);
        }

        // $parameterUser = $parameterUser->limit(1)->pluck('nik')->unique()->values();
        $parameterUser = User::whereIn('nik',$parameterUser->pluck('nik')->unique()->values())->get();
        // $parameterUser = User::whereIn('nik',['1220599090'])->get();
        $presenceHistoryAll = collect();
        foreach ($parameterUser as $value) {
            // echo $value->nik . "<br>";
            if (in_array($value->nik, $shiftingUserList)) {

                // $getLoc = PresenceHistory::select('presence__location.location_name','presence__location.id')->join('presence__location','presence__location.id','=','presence__history.presence_location')->where('presence__history.presence_type','Check-In')
                //     ->whereRaw('`presence_actual` BETWEEN "' . $startDate . ' 00:00:00" AND "' . $endDate . ' 23:59:59"')->whereRaw('`presence__history`.`nik` = ' . $value->nik);

                $getLastTimeCheckOut = PresenceShifting::where('nik',$value->nik)->whereBetween('tanggal_shift',[$startDate,$endDate])->get();

                $presenceHistoryTemp = PresenceHistory::select(
                        DB::raw("presence__history.nik"),
                        DB::raw("MIN(presence__history.presence_schedule) AS presence_schedule"),
                        DB::raw("SUBSTRING_INDEX(GROUP_CONCAT(DISTINCT `presence__history`.`presence_condition`), ',',1) AS presence_condition"),
                        DB::raw("MIN(CAST(`presence_actual` AS DATE)) AS `presence_actual_date`"),
                        // DB::raw("MIN(presence__location.location_name) AS location_name"),
                        DB::raw("MIN(`presence_actual`) AS `presence_actual_start_date`"),
                        DB::raw("MAX(`presence_actual`) AS `presence_actual_end_date`"),
                        DB::raw("GROUP_CONCAT(
                            DISTINCT `presence__shifting`.`className` SEPARATOR ', '
                        ) AS shifting",
                        ),
                        DB::raw("SUBSTRING_INDEX(GROUP_CONCAT(
                            DISTINCT `presence__location`.`location_name`), ',', 1)  AS location_name",
                        ),
                    )->whereRaw('`presence__history`.`nik` = ' . $value->nik)
                    ->whereRaw('`presence_actual` BETWEEN "' . $startDate . ' 00:00:00" AND "' . $endDate . ' 23:59:59"')
                    ->where('className','<>','Libur')
                    ->join('presence__shifting','presence__shifting.id','=','presence__history.presence_setting')
                    ->join('presence__location','presence__location.id','=','presence__history.presence_location')
                    ->groupBy(['presence__history.nik','tanggal_shift'])->orderBy('presence_actual_date','ASC');

                // $presenceHistoryTemp = $presenceHistoryTemp->where('presence_condition','!=','-');

                // return $presenceHistoryTemp->get();
            } else {
                $presenceHistoryTemp = PresenceHistory::select(
                    DB::raw("presence__history.*"),
                    DB::raw("CAST(`presence_actual` AS DATE) AS `presence_actual_date`"),
                    DB::raw('`presence__location`.`location_name`'),
                    DB::raw('`presence__shifting`.`title`')
                )->whereRaw('`presence__history`.`nik` = ' . $value->nik)
                // )->whereRaw('`presence__history`.`nik` = 1210398080')
                ->whereRaw('`presence_actual` BETWEEN "' . $startDate . ' 00:00:00" AND "' . $endDate . ' 23:59:59"')
                ->leftJoin('presence__location','presence__location.id','=','presence__history.presence_location')
                ->leftJoin('presence__shifting','presence__shifting.id','=','presence__history.presence_setting')
                ->orderBy('presence_actual_date','ASC')
                // ->orderBy('presence_actual','ASC')
                ->orderBy('presence_type','ASC');
            }

            // dd($presenceHistoryTemp->get());
            // return $presenceHistoryTemp->get();
            // return $presenceHistoryTemp->where('presence_type','Check-In')->count() * 2;
            // return $presenceHistoryTemp->pluck('presence_type');
            // return $presenceHistoryTemp->get();

            $presenceHistory = collect();
            
            if(in_array($value->nik, $shiftingUserList)){
                // return $presenceHistoryTemp->get();
                foreach($presenceHistoryTemp->get() as $key => $eachPresenceHistory){
                    // return $eachPresenceHistory;
                    if($eachPresenceHistory->presence_type == "Check-Out"){
                        // return $eachPresenceHistory;
                        if($presenceHistory->isNotEmpty()){
                            $presenceHistory->last()->checkout = $eachPresenceHistory->presence_actual;
                        }
                    } 
                    else {
                        $presenceHistory->push((object) [
                            "nik" => $eachPresenceHistory->nik,
                            "name" => $value->name,
                            "date" => $eachPresenceHistory->presence_actual_date,
                            "location" => $eachPresenceHistory->location_name,
                            "schedule" => $eachPresenceHistory->presence_schedule,
                            "checkin" =>  $eachPresenceHistory->presence_actual_start_date,
                            "checkout" =>  $eachPresenceHistory->presence_actual_end_date,
                            "condition" => $eachPresenceHistory->presence_condition,
                            "shifting" => $eachPresenceHistory->shifting
                            // "shifting" => $eachPresenceHistory->title
                        ]);
                    }
                }
                // return $presenceHistory;
            } else {
                foreach($presenceHistoryTemp->get() as $key => $eachPresenceHistory){
                    if($eachPresenceHistory->presence_type == "Check-Out"){
                        if($presenceHistory->isNotEmpty()){
                            $presenceHistory->last()->checkout = $eachPresenceHistory->presence_actual;
                        }
                    } else {
                        if($presenceHistory->isNotEmpty() && $presenceHistory->last()->checkout == "Uncheckout"){
                            $presenceHistory->last()->checkout = "-";
                            $presenceHistory->last()->condition = "Uncheckout";
                        }
                        $presenceHistory->push((object) [
                            "nik" => $eachPresenceHistory->nik,
                            "name" => $value->name,
                            "date" => $eachPresenceHistory->presence_actual_date,
                            "location" => $eachPresenceHistory->location_name,
                            "schedule" => $eachPresenceHistory->presence_schedule,
                            "checkin" =>  $eachPresenceHistory->presence_actual,
                            "checkout" =>  "Uncheckout",
                            "condition" => $eachPresenceHistory->presence_condition,
                            "shifting" => $eachPresenceHistory->shifting
                            // "shifting" => $eachPresenceHistory->title
                        ]);
                    }
                }
            }
            
            // return $presenceHistory;

            // if($presenceHistory->last()->isNotEmpty()){
            //     if($presenceHistory->last()->checkout == "Uncheckout"){
            //         $presenceHistory->last()->checkout = "-";
            //         $presenceHistory->last()->condition = "Uncheckout";
            //     }
            // }

            $presenceHistoryAll = $presenceHistoryAll->merge($presenceHistory);
            // return $presenceHistoryAll;
            // echo $value->nik . "<br>";
            // return $value;
            // return in_array($value, $shiftingUserList) ? "yes" : "no";
            if(in_array($value->nik, $shiftingUserList)){
                $workDays = PresenceShifting::where('nik','=',$value->nik)
                    ->where('className','<>','Libur')
                    ->whereBetween('tanggal_shift',[$startDate . ' 00:00:00',$endDate . ' 23:59:59'])
                    // ->select('tanggal_shift','className');
                    ->orderBy('tanggal_shift','ASC');
                // return $workDays;

                $presenceHistoryAbsent = $workDays->pluck('tanggal_shift')->diff($presenceHistory->pluck('date')->values())->values();

                $presenceHistoryAbsentTemp = collect();
                foreach ($presenceHistoryAbsent as $key => $absentDate) {
                    // echo $value . "<br>";
                    // $presenceHistoryAbsentTemp
                    $presenceHistoryAll->push((object) [
                        "nik" => $value->nik,
                        "name" => $value->name,
                        "date" => $absentDate,
                        "location" => "-",
                        "schedule" => "08:00:00",
                        "checkin" =>  "00:00:00",
                        "checkout" =>  "00:00:00",
                        "condition" => "Absent",
                        "shifting" => $value->shifting
                    ]);
                }
            } else {
                $workDays = $this->getWorkDays($startDate,$endDate)["workdays"]->values();
                $presenceHistory = $presenceHistory->pluck('date')->values();
                $presenceHistoryAbsent =  $workDays->diff($presenceHistory)->values()->toArray();
                // print_r($workDays);
                // $presenceHistoryAbsent = $workDays->diff($presenceHistory->pluck('date')->values())->values();

                $presenceHistoryAbsentTemp = collect();
                foreach ($presenceHistoryAbsent as $key => $absentDate) {
                    // echo $value . "<br>";
                    // $presenceHistoryAbsentTemp
                    $presenceHistoryAll->push((object) [
                        "nik" => $value->nik,
                        "name" => $value->name,
                        "date" => $absentDate,
                        "location" => "-",
                        "schedule" => "08:00:00",
                        "checkin" =>  "00:00:00",
                        "checkout" =>  "00:00:00",
                        "condition" => "Absent",
                        "shifting" => "-"
                    ]);
                }
            }

            // return $workDays->get()->where('tanggal_shift','2022-05-01')->first();
            // return $presenceHistory->get()->pluck('date')->values();
            // return $presenceHistoryAbsent;

            

            // dd($presenceHistoryAll->sortBy('date')->values());
        }

        // return $presenceHistoryAll;

        if($typeData == "all"){
            return collect([
                "range" => $startDate . " 00:00:00 to " . $endDate . " 23:59:59",
                // "data" => $presenceHistoryAll->merge($presenceHistoryAllUnCheckout)->unique(),
                "data" => $presenceHistoryAll->sortBy('date')->values(),
                "holiday" => $this->getWorkDays($startDate,$endDate)["workdays"]
            ]);
        } else {
            return collect([
                "range" => $startDate . " 00:00:00 to " . $endDate . " 23:59:59",
                // "data" => $presenceHistoryAllLate->merge($presenceHistoryAllUnCheckout)->merge($presenceHistoryAllAbsent)->unique(),
                "data" => $presenceHistoryAll->sortBy('date')->values(),
                "holiday" => $this->getWorkDays($startDate,$endDate)["workdays"]
            ]);
        }
    }

    public function getWorkDaysRoute(Request $req){
        return $this->getWorkDays($req->start,$req->end);
    }

    public function getWorkDays($startDate,$endDate){
        $formattedStartDate = Carbon::parse($startDate)->toISOString();
        $formattedEndDate   = Carbon::parse($endDate)->toISOString();
        
        $client = new Client();
        $api_response = $client->get('https://www.googleapis.com/calendar/v3/calendars/en.indonesian%23holiday%40group.v.calendar.google.com/events?timeMin='. $formattedStartDate .'&timeMax='. $formattedEndDate .'&key='.env('GCALENDAR_API_KEY'));
        // $api_response = $client->get('https://aws-cron.sifoma.id/holiday.php?key=AIzaSyBNVCp8lA_LCRxr1rCYhvFIUNSmDsbcGno');
        $json = (string)$api_response->getBody();
        $holiday_indonesia = json_decode($json, true);

        $holiday_indonesia_final_detail = collect();
        $holiday_indonesia_final_date = collect();
        
        foreach ($holiday_indonesia["items"] as $value) {
            if(( ( $value["start"]["date"] >= $startDate ) && ( $value["start"]["date"] <= $endDate ) && ($value["description"] == 'Public holiday')  && (!strstr($value['summary'], "Joint")  && ($value["summary"] != 'Boxing Day') ))){
                $holiday_indonesia_final_detail->push(["date" => $value["start"]["date"],"summary" => $value["summary"]]);
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

        $workDaysMinHoliday = $workDays->diff($holiday_indonesia_final_date->unique());
        $workDaysMinHolidayKeyed = $workDaysMinHoliday->map(function ($item, $key) {
            // return ["date" => $item];
            // return (object) array('date' => $item);
            return $item;
        });

        return collect(["holiday" => $holiday_indonesia_final_detail, "workdays" => $workDaysMinHolidayKeyed]);
    }

    public function getDataReportPresence($typeCompany = "all"){
        // return $typeCompany;

        $startDate = Carbon::now()->subMonths()->format("Y-m-16");
        // return $startDate;
        // $startDate = Carbon::now()->subMonth(1);
        $endDate = Carbon::now()->format("Y-m-15");
        // $endDate = "2021-06-15";

        if ($typeCompany != "all") {
             $parameterUser = DB::table('users')
                ->join('presence__history', 'presence__history.nik', '=', 'users.nik')
                ->select('users.nik', 'users.name')
                ->where('users.id_company', $typeCompany)
                ->where('presence_actual','>=',$startDate)
                ->where('presence_actual','<=',$endDate)
                ->where('status_karyawan', '!=', 'dummy')
                ->groupBy('users.nik')
                ->get()
                ->toArray();
        } else {
             $parameterUser = DB::table('users')
                ->join('presence__history', 'presence__history.nik', '=', 'users.nik')
                ->select('users.nik', 'users.name')
                ->where('presence_actual','>=',$startDate)
                ->where('presence_actual','<=',$endDate)
                ->where('status_karyawan', '!=', 'dummy')
                ->groupBy('users.nik')
                ->get()
                ->toArray();
        }

        // return $parameterUser;

        if (!empty(($parameterUser))) {
            foreach($parameterUser as $user){
                $status[] = $user->name;
            }

            foreach($parameterUser as $user){
                $NikUser[] = $user->nik;
            }

            foreach ($status as $key => $stat) {
                $all = DB::table('presence__history')
                    ->where('nik','=',$NikUser[$key])
                    ->where('presence_actual','>=',$startDate)
                    ->where('presence_actual','<=',$endDate)
                    ->get()
                    ->count('presence_type');
                // return $all;
                $late = DB::table('presence__history')
                    ->where('nik','=',$NikUser[$key])
                    ->where('presence_actual','>=',$startDate)
                    ->where('presence_actual','<=',$endDate)
                    ->where('presence_condition','=',"Late")
                    ->get()
                    ->count('presence_type');
                $ontime = DB::table('presence__history')
                    ->where('nik','=',$NikUser[$key])
                    ->where('presence_actual','>=',$startDate)
                    ->where('presence_actual','<=',$endDate)
                    ->where('presence_condition','=',"On-Time")
                    ->get()
                    ->count('presence_type');
                $injury = DB::table('presence__history')
                    ->where('nik','=',$NikUser[$key])
                    ->where('presence_actual','>=',$startDate)
                    ->where('presence_actual','<=',$endDate)
                    ->where('presence_condition','=',"Injury-Time")
                    ->get()
                    ->count('presence_type');
                $absen = DB::table('presence__history')
                    ->where('nik','=',$NikUser[$key])
                    ->where('presence_actual','>=',$startDate)
                    ->where('presence_actual','<=',$endDate)
                    ->where('presence_condition','<>',"Injury-Time")
                    ->where('presence_condition','<>',"Late")
                    ->where('presence_condition','<>',"On-Time")
                    ->where('presence_condition','<>',"-")
                    ->get()
                    ->count('presence_type');

                $where =  DB::table('presence__location_user')
                    ->join('presence__location', 'presence__location_user.location_id', '=', 'presence__location.id')
                    ->select('presence__location.location_name', 'user_id')
                    ->get()
                    ->toarray();


                // $var[$stat]["all"] = sizeof($all);
                $var[$stat]["all"] = $all;
                // $var[$stat]["all"] = sizeof($late) + sizeof($ontime) + sizeof($injury);
                $var[$stat]["all"] = $late + $ontime + $injury;
                // $var[$stat]["late"] = sizeof($late);
                $var[$stat]["late"] = $late;
                // $var[$stat]["ontime"] = sizeof($ontime);
                $var[$stat]["ontime"] = $ontime;
                // $var[$stat]["injury"] = sizeof($injury);
                $var[$stat]["injury"] = $injury;
                // $var[$stat]["absen"] = sizeof($absen);
                $var[$stat]["absen"] = $absen;
                $var[$stat]["where"] = $where[0]->location_name;
                $var[$stat]["name"] = $status[$key];
            }

            return collect([
                "range" => $startDate . " to " . $endDate,
                "data" => $var,
            ])->sortBy("data");
        }else{
            return collect([
                "range" => $startDate . " to " . $endDate,
                "data" => [],
            ]);
        }
        
        
    }

    public function getFilterReport(Request $req){

        // $startDate = Carbon::now()->subMonths(1)->format("Y-m-16");
        // $endDate = Carbon::now()->format("Y-m-16");
        if (isset($req->nik)) {
            $parameterUser = DB::table('users')
                ->join('presence__history', 'presence__history.nik', '=', 'users.nik')
                ->select('users.nik', 'users.name')
                ->whereDate('presence_actual','>=',$req->start)
                ->whereDate('presence_actual','<=',$req->end)
                ->whereIn('presence__history.nik',$req->nik)
                ->groupBy('users.nik')
                ->get()->toArray();  
        } else {
            $parameterUser = DB::table('users')
                ->join('presence__history', 'presence__history.nik', '=', 'users.nik')
                ->select('users.nik', 'users.name')
                ->whereDate('presence_actual','>=',$req->start)
                ->whereDate('presence_actual','<=',$req->end)
                ->groupBy('users.nik')
                ->get()->toArray();  
        }
                
        if (!empty(($parameterUser))) {
            $status;

            foreach($parameterUser as $user){
                $status[] = $user->name;
            }

            foreach($parameterUser as $user){
                $NikUser[] = $user->nik;
            }

            foreach ($status as $key => $stat) {
                $all = DB::table('presence__history')
                    ->where('nik','=',$NikUser[$key])
                    ->where('presence_actual','>=',$req->start)
                    ->where('presence_actual','<=',$req->end)
                    ->get()
                    ->count('presence_type');

                $late = DB::table('presence__history')
                    ->where('nik','=',$NikUser[$key])
                    ->where('presence_actual','>=',$req->start)
                    ->where('presence_actual','<=',$req->end)
                    ->where('presence_condition','=',"Late")
                    ->get()
                    ->count('presence_type');
                $ontime = DB::table('presence__history')
                    ->where('nik','=',$NikUser[$key])
                    ->where('presence_actual','>=',$req->start)
                    ->where('presence_actual','<=',$req->end)
                    ->where('presence_condition','=',"On-Time")
                    ->get()
                    ->count('presence_type');
                $injury = DB::table('presence__history')
                    ->where('nik','=',$NikUser[$key])
                    ->where('presence_actual','>=',$req->start)
                    ->where('presence_actual','<=',$req->end)
                    ->where('presence_condition','=',"Injury-Time")
                    ->get()
                    ->count('presence_type');
                $absen = DB::table('presence__history')
                    ->where('nik','=',$NikUser[$key])
                    ->where('presence_actual','>=',$req->start)
                    ->where('presence_actual','<=',$req->end)
                    ->where('presence_condition','<>',"Injury-Time")
                    ->where('presence_condition','<>',"Late")
                    ->where('presence_condition','<>',"On-Time")
                    ->where('presence_condition','<>',"-")
                    ->get()
                    ->count('presence_type');

                $where =  DB::table('presence__location_user')
                    ->join('presence__location', 'presence__location_user.location_id', '=', 'presence__location.id')
                    ->select('presence__location.location_name', 'user_id')
                    ->get()
                    ->toarray();


                // $var[$stat]["all"] = sizeof($all);
                $var[$stat]["all"] = $all;
                // $var[$stat]["all"] = sizeof($late) + sizeof($ontime) + sizeof($injury);
                $var[$stat]["all"] = $late + $ontime + $injury;
                // $var[$stat]["late"] = sizeof($late);
                $var[$stat]["late"] = $late;
                // $var[$stat]["ontime"] = sizeof($ontime);
                $var[$stat]["ontime"] = $ontime;
                // $var[$stat]["injury"] = sizeof($injury);
                $var[$stat]["injury"] = $injury;
                // $var[$stat]["absen"] = sizeof($absen);
                $var[$stat]["where"] = $where[0]->location_name;
                $var[$stat]["name"] = $status[$key];
            }

            return collect([
                "range" => $req->start . " to " . $req->end,
                "data" => $var,
            ]);
        }else{
            return collect([
                "range" => $req->start . " to " . $req->end,
                "data" => [],
            ]);
        }
        
    }

    function getLogActivityShifting(Request $req){
        return array("data"=>DB::table('presence__shifting_log')
            ->join('users','users.nik','=','presence__shifting_log.nik_user')
            ->select('title','presence__shifting_log.created_at','users.name','start_before','end_before','className_before','status')
            ->orderBy('presence__shifting_log.created_at','desc')
            ->get());
    }


    public function getExportReport(Request $req){

        if(isset($req->startDate) && isset($req->endDate)){
            // return "Start " . $req->startDate . " End " . $req->endDate
            $date = [
                "startDate" => $req->startDate,
                "endDate" => $req->endDate
            ];
        } else {
            $date = [
                "startDate" => Carbon::now()->subMonths(1)->format("Y-m-16"),
                "endDate" => Carbon::now()->format("Y-m-16")
            ];
        }
        // return $this->getPresenceReportData("all","1",$date);

        $spreadsheet = new Spreadsheet();

        $spreadsheet->removeSheetByIndex(0);
        $spreadsheet->addSheet(new Worksheet($spreadsheet,'All Presence'));
        $summarySheet = $spreadsheet->setActiveSheetIndex(0);

        $summarySheet->mergeCells('A1:J1');
        $normalStyle = [
            'font' => [
                'name' => 'Calibri',
                'size' => 11
            ],
        ];

        $titleStyle = $normalStyle;
        $titleStyle['alignment'] = ['horizontal' => Alignment::HORIZONTAL_CENTER];
        $titleStyle['borders'] = ['outline' => ['borderStyle' => Border::BORDER_THIN]];
        $titleStyle['fill'] = ['fillType' => Fill::FILL_SOLID, 'startColor' => ["argb" => "FFFCD703"]];
        $titleStyle['font']['bold'] = true;

        $headerStyle = $normalStyle;
        $headerStyle['font']['bold'] = true;

        $conditionOnTime = $headerStyle;$conditionOnTime['fill'] = ['fillType' => Fill::FILL_SOLID, 'startColor' => ["argb" => "FF92cf51"]];
        $conditionInjury = $headerStyle;$conditionInjury['fill'] = ['fillType' => Fill::FILL_SOLID, 'startColor' => ["argb" => "FFffbe02"]];
        $conditionLate = $headerStyle;$conditionLate['fill'] = ['fillType' => Fill::FILL_SOLID, 'startColor' => ["argb" => "FFda9694"]];
        $conditionUnCheckout = $headerStyle;$conditionUnCheckout['fill'] = ['fillType' => Fill::FILL_SOLID, 'startColor' => ["argb" => "FFbebfbf"]];
        $conditionAbsent = $headerStyle;$conditionAbsent['fill'] = ['fillType' => Fill::FILL_SOLID, 'startColor' => ["argb" => "FF8cb4e2"]];

        $summarySheet->getStyle('A1:J1')->applyFromArray($titleStyle);
        $summarySheet->setCellValue('A1','All Presence');

        $headerContent = ["No", "Nik", "Name", "Date","Location","Schedule","Check-In","Check-Out","Condition","Shifting"];
        $summarySheet->getStyle('A2:J2')->applyFromArray($headerStyle);
        $summarySheet->fromArray($headerContent,NULL,'A2');

        $summarySheet2 = $spreadsheet->setActiveSheetIndex(0);

        // $summarySheet2->mergeCells('M2:Q2');

        $headerContent2 = ["Nama", "Total Hari Kerja", "Kegiatan", "Hari Kerja Non-Shift","Kelebihan Hari Kerja"];
        $summarySheet2->getStyle('M2:Q2')->applyFromArray($headerStyle);
        $summarySheet2->fromArray($headerContent2,NULL,'M2');

        // if(isset($req->type)){
        //     if($req->type == "SIP"){
        //         $typeCompany = "1";
        //     } elseif ($req->type == "MSM") {
        //         $typeCompany = "2";
        //     }elseif ($req->type == "SIM") {
        //         $typeCompany = "3";
        //     }elseif ($req->type == "FIN") {
        //         $typeCompany = "4";
        //     }elseif ($req->type == "HR") {
        //         $typeCompany = "5";
        //     }elseif ($req->type == "PMO") {
        //         $typeCompany = "6";
        //     } else {
        //         $typeCompany = "7";
        //     }
        // // return $this->getPresenceReportData("all",$typeCompany,$date);
            
        //     // return $req->nik;
        //     // return $typeCompany;
        //     // $typeCompany = ($req->type == "SIP") ? "1" : "2";
        //     $typeCompany = ($req->type == "SIP") ? "1" : ($req->type == "MSM") ? "2" : ($req->type == "SIM") ? "3" : ($req->type == "FIN") ? "4" : ($req->type == "HR") ? "5" : (($req->type == "PMO") ? "6" : "7");
        //     $dataPresence = $this->getPresenceReportData("all",array($req->nik),$typeCompany,$date);
        //     $exportName = 'Report Presence ' . $req->type . ' (reported at ' . date("Y-m-d") . ')';
        // } else {
        //     $dataPresence = $this->getPresenceReportData("all",array($req->nik),"all",$date);
        //     $exportName = 'Report Presence (reported at' . date("Y-m-d") . ')';
        // }
            // return $req->nik;

        if(isset($req->nik)){
            $dataPresence = $this->getPresenceReportData("all",$req->nik,'all',$date);
            $exportName = 'Report Presence ' . $req->type . '(reported at ' . date("Y-m-d") . ')';

            $getHariKerjaShifting = PresenceShifting::join('users','users.nik','presence__shifting.nik')
                            ->select('presence__shifting.nik', 'name',
                                DB::raw('COUNT(className) as className'),
                                DB::raw('COUNT(IF(`presence__shifting`.`className` = "Kegiatan",1,NULL)) AS "classNameKegiatan"'),
                                DB::raw('COUNT(IF(`presence__shifting`.`className` = "HO/Aktivasi",1,NULL)) AS "classNameHo"'),
                                DB::raw('COUNT(IF(`presence__shifting`.`className` = "Preventive_Maintenance",1,NULL)) AS "classNamePM"')
                            )
                            ->where('className', '!=', 'Libur')
                            ->whereIn('presence__shifting.nik',$req->nik)
                            ->whereRaw('`tanggal_shift` BETWEEN "' . $req->startDate . ' 00:00:00" AND "' . $req->endDate . ' 23:59:59"')->groupBy('nik')->get();
        } else {
            $dataPresence = $this->getPresenceReportData("all",'','all',$date);
            $exportName = 'Report Presence ' . $req->type . '(reported at ' . date("Y-m-d") . ')';


            $getHariKerjaShifting = PresenceShifting::join('users','users.nik','presence__shifting.nik')
                            ->select('presence__shifting.nik', 'name',
                                DB::raw('COUNT(className) as className'),
                                DB::raw('COUNT(IF(`presence__shifting`.`className` = "Kegiatan",1,NULL)) AS "classNameKegiatan"'),
                                DB::raw('COUNT(IF(`presence__shifting`.`className` = "HO/Aktivasi",1,NULL)) AS "classNameHo"'),
                                DB::raw('COUNT(IF(`presence__shifting`.`className` = "Preventive_Maintenance",1,NULL)) AS "classNamePM"')
                            )
                            ->where('className', '!=', 'Libur')
                            // ->whereIn('presence__shifting.nik',$req->nik)
                            ->whereRaw('`tanggal_shift` BETWEEN "' . $req->startDate . ' 00:00:00" AND "' . $req->endDate . ' 23:59:59"')->groupBy('nik')->get();
        }

        $workDays = $this->getWorkDays($req->startDate,$req->endDate)["workdays"]->values()->count();

        $getAll = collect();

        foreach ($getHariKerjaShifting as $key => $value) {
            $kegiatan = $value['classNameKegiatan']+$value['classNameHo']+$value['classNamePM'];
            $lebih_kerja = $value['className']-$workDays;
            $className = $value['className']-$kegiatan;
            $getAll->push([
                "name"              =>$value['name'],
                "className"         => (string)$className,
                "classNameKegiatan" => (string)$kegiatan,
                "workDays"          =>$workDays,
                "lebih_kerja"       => (string)$lebih_kerja
            ]);
        }

        foreach ($getAll as $key => $value) {
            $summarySheet2->fromArray(array_merge(array_values($value)),NULL,'M' . ($key + 3));
        }

        // return $dataPresence;
        $dataPresence["data"]->sortBy('name')->sortBy('date')->map(function($item,$key) use ($summarySheet,$conditionOnTime,$conditionInjury,$conditionLate,$conditionUnCheckout,$conditionAbsent){
            $summarySheet->fromArray(array_merge([$key + 1],array_values(get_object_vars($item))),NULL,'A' . ($key + 3));
            if($item->condition == "On-Time"){
                $summarySheet->getStyle('I' . ($key + 3))->applyFromArray($conditionOnTime);
            } else if ($item->condition == "Injury-Time"){
                $summarySheet->getStyle('I' . ($key + 3))->applyFromArray($conditionInjury);
            } else if ($item->condition == "Late"){
                $summarySheet->getStyle('I' . ($key + 3))->applyFromArray($conditionLate);
            } else if ($item->condition == "Uncheckout"){
                $summarySheet->getStyle('I' . ($key + 3))->applyFromArray($conditionUnCheckout);
            } else if ($item->condition == "Absent"){
                $summarySheet->getStyle('I' . ($key + 3))->applyFromArray($conditionAbsent);
            }
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

        $summarySheet2->getColumnDimension('M')->setAutoSize(true);
        $summarySheet2->getColumnDimension('N')->setAutoSize(true);
        $summarySheet2->getColumnDimension('O')->setAutoSize(true);
        $summarySheet2->getColumnDimension('P')->setAutoSize(true);
        $summarySheet2->getColumnDimension('Q')->setAutoSize(true);

        $dataPresenceIndividual = $dataPresence["data"]->sortBy('name')->groupBy('name');

        $indexSheet = 0;
        foreach ($dataPresenceIndividual as $key => $item) {
            $name = substr($key,0,30);

            $spreadsheet->addSheet(new Worksheet($spreadsheet,$name));
            // $spreadsheet->addSheet(new Worksheet($spreadsheet,$key));
            $detailSheet = $spreadsheet->setActiveSheetIndex($indexSheet + 1);

            $detailSheet->getStyle('A1:K1')->applyFromArray($titleStyle);
            $detailSheet->setCellValue('A1','Presence Report ' . $key);
            $detailSheet->mergeCells('A1:K1');

            $headerContent = ["No", "Nik", "Name", "Date","Location","Schedule","Check-In","Check-Out","Condition","Valid"];
            $detailSheet->getStyle('A2:K2')->applyFromArray($headerStyle);
            $detailSheet->fromArray($headerContent,NULL,'A2');

            if (DB::table('presence__shifting_user')->where('nik',$item[0]->nik)->exists()) {
                $detailSheet2 = $spreadsheet->setActiveSheetIndex($indexSheet + 1);

                $detailSheet2->getStyle('N1:P1')->applyFromArray($titleStyle);
                $detailSheet2->setCellValue('N1','Jumlah Hari Kerja ' . $key);
                $detailSheet2->mergeCells('N1:P1');

                $headerContent2 = ["Total Kerja", "Hari Kerja Non-Shift", "Kelebihan Hari Kerja"];
                $detailSheet2->getStyle('N2:P2')->applyFromArray($headerStyle);
                $detailSheet2->fromArray($headerContent2,NULL,'N2');

                $countShift = collect();

                $getShifting = PresenceShifting::join('presence__history','presence__history.presence_setting','presence__shifting.id')
                            ->select(DB::raw('CONCAT(`className`) as `title`'), 'tanggal_shift', 'presence__shifting.nik')
                            // ->whereRaw('`nik` = ' . $value->nik)
                            ->whereRaw('`presence__shifting`.`nik` = ' . $item[0]->nik)
                            ->where('className', '!=', 'Libur')
                            ->where('presence_type','Check-In')
                            ->whereRaw('`tanggal_shift` BETWEEN "' . $req->startDate . ' 00:00:00" AND "' . $req->endDate . ' 23:59:59"')->get()->count();

                $workDays = $this->getWorkDays($req->startDate,$req->endDate)["workdays"]->values()->count();

                $countShift->push(["shift"=>$getShifting,
                    "hari_kerja"       =>$workDays,
                    "lebih_kerja"    =>$getShifting-$workDays,
                ]);

                foreach ($countShift as $key => $value) {
                    $detailSheet2->fromArray(array_merge(array_values($value)),NULL,'N' . ($key + 3));
                }

                $detailSheet2->getColumnDimension('N')->setAutoSize(true);
                $detailSheet2->getColumnDimension('O')->setAutoSize(true);
                $detailSheet2->getColumnDimension('P')->setAutoSize(true);
            }

            foreach ($item->sortBy('date')->values() as $key => $eachPresence) {
                // return $item->sortBy('date')->values();
                $detailSheet->fromArray(array_merge([$key + 1],array_values(get_object_vars($eachPresence))),NULL,'A' . ($key + 3));
                if($eachPresence->condition == "On-Time"){
                    $detailSheet->getStyle('I' . ($key + 3))->applyFromArray($conditionOnTime);
                } else if ($eachPresence->condition == "Injury-Time"){
                    $detailSheet->getStyle('I' . ($key + 3))->applyFromArray($conditionInjury);
                } else if ($eachPresence->condition == "Late"){
                    $detailSheet->getStyle('I' . ($key + 3))->applyFromArray($conditionLate);
                } else if ($eachPresence->condition == "Uncheckout"){
                    $detailSheet->getStyle('I' . ($key + 3))->applyFromArray($conditionUnCheckout);
                } else if ($eachPresence->condition == "Absent"){
                    $detailSheet->getStyle('I' . ($key + 3))->applyFromArray($conditionAbsent);
                }
                
            }

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
            $indexSheet = $indexSheet + 1;

            // $indexSheet = $indexSheet + 1;
        }

        $spreadsheet->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $exportName . '.xlsx"');
        header('Cache-Control: max-age=0');
        
        $writer = new Xlsx($spreadsheet);
        ob_end_clean();
        return $writer->save("php://output");
    }

    public function getReportPresenceDummy(Request $request, $nik = "")
    {
        $startDate = $request["startDate"];
        $endDate = $request["endDate"];

        $workDays = $this->getWorkDays($startDate,$endDate)["workdays"]->values();
        $shiftingUserList = PresenceShiftingUser::pluck('nik')->toArray();
        // return $shiftingUserList;

        $parameterUser = PresenceHistory::select(DB::raw('presence__history.*'))
            ->whereRaw('`presence_actual` BETWEEN "' . $startDate . ' 00:00:00" AND "' . $endDate . ' 23:59:59"')
            ->join('users','users.nik','=','presence__history.nik');
            // ->orderBy('nik','DESC');

        // if($nik == ""){
        //     $parameterUser = $parameterUser;
        // } else {
            $parameterUser = $parameterUser->whereIn('users.nik',$shiftingUserList);
        // }

        // $parameterUser = $parameterUser->limit(1)->pluck('nik')->unique()->values();
        $parameterUser = User::whereIn('nik',$parameterUser->pluck('nik')->unique()->values())->get();
        // $parameterUser = User::whereIn('nik',['1220599090'])->get();
        // return $parameterUser;

        $presenceHistoryAll = collect();
        foreach ($parameterUser as $value) {
            // echo $value->nik . "<br>";
            $presenceHistoryTemp = PresenceHistory::select(
                DB::raw("presence__history.*"),
                DB::raw("CAST(`presence_actual` AS DATE) AS `presence_actual_date`"),
                DB::raw('`presence__location`.`location_name`'),
                DB::raw('`presence__shifting`.`title`')
            )->whereRaw('`presence__history`.`nik` = ' . '1210398080')
            ->whereRaw('`presence_actual` BETWEEN "' . $startDate . ' 00:00:00" AND "' . $endDate . ' 23:59:59"')
            ->leftJoin('presence__location','presence__location.id','=','presence__history.presence_location')
            ->leftJoin('presence__shifting','presence__shifting.id','=','presence__history.presence_setting')
            ->orderBy('presence_actual_date','ASC')
            ->orderBy('presence_type','ASC');

            // return $presenceHistoryTemp->get();

            $presenceHistory = collect();
            
            foreach($presenceHistoryTemp->get() as $key => $eachPresenceHistory){
                if($eachPresenceHistory->presence_type == "Check-Out"){
                    if($presenceHistory->isNotEmpty()){
                        $presenceHistory->last()->checkout = $eachPresenceHistory->presence_actual;
                    }
                } else {
                    if($presenceHistory->isNotEmpty() && $presenceHistory->last()->checkout == "Uncheckout"){
                        $presenceHistory->last()->checkout = "-";
                        $presenceHistory->last()->condition = "Uncheckout";
                    }
                    $presenceHistory->push((object) [
                        "nik" => $eachPresenceHistory->nik,
                        "name" => $value->name,
                        "date" => $eachPresenceHistory->presence_actual_date,
                        "location" => $eachPresenceHistory->location_name,
                        "schedule" => $eachPresenceHistory->presence_schedule,
                        "checkin" =>  $eachPresenceHistory->presence_actual,
                        "checkout" =>  "Uncheckout",
                        "condition" => $eachPresenceHistory->presence_condition,
                        "shifting" => explode(" - ",$eachPresenceHistory->title)[0]
                    ]);
                }
            }
            // return $presenceHistory;

            // if($presenceHistory->last()->isNotEmpty()){
            //     if($presenceHistory->last()->checkout == "Uncheckout"){
            //         $presenceHistory->last()->checkout = "-";
            //         $presenceHistory->last()->condition = "Uncheckout";
            //     }
            // }

            $presenceHistoryAll = $presenceHistoryAll->merge($presenceHistory);
            // return $presenceHistoryAll;
            // echo $value->nik . "<br>";
            // return $value;
            // return in_array($value->nik, $shiftingUserList) ? "yes" : "no";
            if(in_array('1210398080', $shiftingUserList)){
                // return $value->nik;
                $workDays = PresenceShifting::where('nik','=','1210398080')
                    ->where('className','<>','Libur')
                    ->whereBetween('tanggal_shift',[$startDate . ' 00:00:00',$endDate . ' 23:59:59'])
                    // ->select('tanggal_shift','className');
                    ->orderBy('tanggal_shift','ASC');
                // return $workDays;

                $presenceHistoryAbsent = $workDays->pluck('tanggal_shift')->diff($presenceHistory->pluck('date')->values())->values();
                // return $presenceHistoryAbsent;
                // return $presenceHistoryAbsent = $workDays->pluck('tanggal_shift');
                

                $presenceHistoryAbsentTemp = collect();
                
                foreach ($presenceHistoryAbsent as $key => $absentDate) {
                    // echo $value . "<br>";
                    // $presenceHistoryAbsentTemp
                    $presenceHistoryAll->push((object) [
                        "nik" => $value->nik,
                        "name" => $value->name,
                        "date" => $absentDate,
                        "location" => "-",
                        "schedule" => "08:00:00",
                        "checkin" =>  "00:00:00",
                        "checkout" =>  "00:00:00",
                        "condition" => "Absent",
                        "shifting" => explode(" - ",$workDays->get()->where('tanggal_shift',$absentDate)->first()->title)[0]
                    ]);

                    return $presenceHistoryAll;
                }
                return $presenceHistoryAll;
            } 
        }
    }

    public function addProject(Request $request)
    {
        $store = new PresenceShiftingProject();
        $store->project_name = $request->name;
        $store->project_location = $request->location;
        $store->save();

        $add_option = new PresenceShiftingOption();
        $add_option->name_option = 'Pagi';
        $add_option->start_shifting = '07:00';
        $add_option->end_shifting = '15:00';
        $add_option->class_shifting = 'green';
        $add_option->id_project = $store->id;
        $add_option->status = 'NON-ACTIVE';
        $add_option->save();


        $add_option2 = new PresenceShiftingOption();
        $add_option2->name_option = 'Sore';
        $add_option2->start_shifting = '14:00';
        $add_option2->end_shifting = '22:00';
        $add_option2->class_shifting = 'orange';
        $add_option2->id_project = $store->id;
        $add_option2->status = 'NON-ACTIVE';
        $add_option2->save();


        $add_option3 = new PresenceShiftingOption();
        $add_option3->name_option = 'Libur';
        $add_option3->start_shifting = '00:00';
        $add_option3->end_shifting = '23:59';
        $add_option3->class_shifting = 'red';
        $add_option3->id_project = $store->id;
        $add_option3->status = 'NON-ACTIVE';
        $add_option3->save();

    }
}
