<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use DateTime;
use App\User;

use App\PresenceHistory;
use App\PresenceLocationUser;

class PresenceController extends Controller
{
    public function __construct() {
        $this->middleware('auth', ['except' => ['getPresenceParameter','checkIn','checkOut']]);
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
            ->whereRaw('DATE(`presence_actual`) = "' . now()->toDateString() . '"');


        if($presenceStatus->count() == 0){
            $presenceStatus = "not-yet";
        } else if ($presenceStatus->count() == 1) {
            $presenceStatus = "done-checkin";
        } else {
            $presenceStatus = "done-checkout";
        }

        return view('presence.presence', compact('presenceStatus','notif','notifOpen','notifsd','notiftp', 'notifClaim'));
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


        return view('presence.personal_history', compact('presenceHistoryDetail','presenceHistoryCounted','notif','notifOpen','notifsd','notiftp', 'notifClaim'));
    }

    public function teamHistory() {
        $notifAll = $this->notification_legacy();
        
        $notif = $notifAll["notif"];
        $notifOpen = $notifAll["notifOpen"];
        $notifsd = $notifAll["notifsd"];
        $notiftp = $notifAll["notiftp"];
        $notifClaim = $notifAll["notifClaim"];

        return view('presence.team_history', compact('lead','notif','notifOpen','notifsd','notiftp', 'notifClaim'));
    }

    public function presenceReport() {
        $notifAll = $this->notification_legacy();
        
        $notif = $notifAll["notif"];
        $notifOpen = $notifAll["notifOpen"];
        $notifsd = $notifAll["notifsd"];
        $notiftp = $notifAll["notiftp"];
        $notifClaim = $notifAll["notifClaim"];
        
        return view('presence.reporting', compact('notif','notifOpen','notifsd','notiftp', 'notifClaim'));
    }

    public function presenceSetting() {
        $notifAll = $this->notification_legacy();
        
        $notif = $notifAll["notif"];
        $notifOpen = $notifAll["notifOpen"];
        $notifsd = $notifAll["notifsd"];
        $notiftp = $notifAll["notiftp"];
        $notifClaim = $notifAll["notifClaim"];

        return view('presence.setting', compact('notif','notifOpen','notifsd','notiftp', 'notifClaim'));
    }

    public function presenceShifting() {
        $notifAll = $this->notification_legacy();
        
        $notif = $notifAll["notif"];
        $notifOpen = $notifAll["notifOpen"];
        $notifsd = $notifAll["notifsd"];
        $notiftp = $notifAll["notiftp"];
        $notifClaim = $notifAll["notifClaim"];
        
        return view('presence.shifting', compact('notif','notifOpen','notifsd','notiftp', 'notifClaim'));
    }

    public function getPresenceParameter(Request $req){
        return PresenceLocationUser::with('location')->where('user_id',$req->nik)->get();
    }

    public function checkIn(Request $req) {
        $history = new PresenceHistory();
        if (isset(Auth::User()->nik)) {
            $setting_schedule = Auth::User()->presence_setting;
            $history->nik = Auth::User()->nik;
        }else{
            $history->nik = $req->nik;
            $setting_schedule = User::with('presence_setting')->where('nik',$history->nik)->first()->presence_setting;
        }     
        $history->presence_setting = $setting_schedule->id;
        $history->presence_schedule = $setting_schedule->setting_on_time;
        $history->presence_actual = $req->presence_actual;
        $history->presence_location = 1;
        $history->presence_condition = $this->checkPresenceCondition($req->presence_actual,$setting_schedule);
        $history->presence_type = "Check-In";

        $history->save();
    }

    public function checkOut(Request $req) {
        $history = new PresenceHistory();
        if (isset(Auth::User()->nik)) {
            $setting_schedule = Auth::User()->presence_setting;
            $history->nik = Auth::User()->nik;
        }else{
            $history->nik = $req->nik;
            $setting_schedule = User::with('presence_setting')->where('nik',$history->nik)->first()->presence_setting;
        }
        $history->presence_setting = $setting_schedule->id;
        $history->presence_schedule = $setting_schedule->setting_check_out;
        $history->presence_actual = $req->presence_actual;
        $history->presence_location = 1;
        $history->presence_condition = "-";
        $history->presence_type = "Check-Out";

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
}
