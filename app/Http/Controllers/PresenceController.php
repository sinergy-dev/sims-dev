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

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PresenceController extends Controller
{
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
            ->whereRaw('DATE(`presence_actual`) = "' . now()->toDateString() . '"');


        if($presenceStatus->count() == 0){
            $presenceStatus = "not-yet";
        } else if ($presenceStatus->count() == 1) {
            $presenceStatus = "done-checkin";
        } else {
            $presenceStatus = "done-checkout";
        }

        return view('presence.presence', compact('presenceStatus','notif','notifOpen','notifsd','notiftp', 'notifClaim'))->with(['initView'=>$this->initMenuBase()]);
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
        
        return view('presence.reporting', compact('notif','notifOpen','notifsd','notiftp', 'notifClaim'))->with(['initView'=>$this->initMenuBase()]);
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

    public function getPresenceReportData($typeData = "notAll", $typeCompany = "all"){
        $startDate = Carbon::now()->subMonths(1)->format("Y-m-16");
        $endDate = Carbon::now()->format("Y-m-16");

        $workDays = $this->getWorkDays($startDate,$endDate)["workdays"]->values();

        $parameterUser = PresenceHistory::select(DB::raw('presence__history.*'))
            ->whereRaw('`presence_actual` BETWEEN "' . $startDate . '" AND "' . $endDate . '"')
            ->join('users','users.nik','=','presence__history.nik');

        if($typeCompany != "all"){
            $parameterUser = $parameterUser->where('users.id_company','=',$typeCompany);
        }

        $parameterUser = $parameterUser->pluck('nik')->unique()->values();

        $presenceHistoryAll = collect();
        foreach ($parameterUser as $value) {
            $presenceHistoryTemp = PresenceHistory::select(
                DB::raw("*"),
                DB::raw("CAST(`presence_actual` AS DATE) AS `presence_actual_date`")
            )->whereRaw('`nik` = ' . $value)
            ->whereRaw('`presence_actual` BETWEEN "' . $startDate . '" AND "' . $endDate . '"');

            $presenceHistory = DB::table(DB::raw("(" . $presenceHistoryTemp->toSql() . ") AS `presence__history_temp`"))
                ->join('users','users.nik','=','presence__history_temp.nik')
                ->select('presence__history_temp.nik','users.name')
                ->selectRaw("CAST(MIN(`presence__history_temp`.`presence_actual`) AS DATE) AS `date`")
                ->selectRaw("MIN(`presence__history_temp`.`presence_schedule`) AS `schedule`")
                ->selectRaw("RIGHT(MIN(`presence__history_temp`.`presence_actual`),8) AS `checkin`")
                ->selectRaw("IF(MAX(`presence__history_temp`.`presence_actual`) = MIN(`presence__history_temp`.`presence_actual`), '-', RIGHT(MAX(`presence__history_temp`.`presence_actual`),8)) AS `checkout`")
                ->selectRaw("MAX(`presence__history_temp`.`presence_condition`) AS `condition`")
                // ->where('users.id_company','<>',2)
                ->groupBy('presence__history_temp.presence_actual_date');

            $presenceHistoryAll = $presenceHistoryAll->merge($presenceHistory->get());

            $presenceHistoryAbsent = $workDays->diff($presenceHistory->get()->pluck('date')->values())->values();
            $presenceHistoryAbsentTemp = collect();
            foreach ($presenceHistoryAbsent as $key => $absentDate) {
                // echo $value . "<br>";
                // $presenceHistoryAbsentTemp
                $presenceHistoryAll->push((object) [
                    "nik" => $value,
                    "name" => $presenceHistory->first()->name,
                    "date" => $absentDate,
                    "schedule" => "08:00:00",
                    "checkin" =>  "00:00:00",
                    "checkout" =>  "00:00:00",
                    "condition" => "Absent"
                ]);
            }

        }

        $presenceHistoryAllLate = $presenceHistoryAll->where('condition','Late');
        $presenceHistoryAllAbsent = $presenceHistoryAll->where('condition','Absent');
        $presenceHistoryAllUnCheckout = $presenceHistoryAll->where('checkout','=','-');
        $presenceHistoryAllUnCheckout->each(function ($item, $key) {
            if($item->condition != "Late" && $item->condition != "Absent"){
                $item->condition = "Uncheckout";
            }
        });

        if($typeData == "all"){
            return collect([
                "range" => $startDate . " to " . $endDate,
                "data" => $presenceHistoryAll->merge($presenceHistoryAllUnCheckout)->unique(),
                "holiday" => $this->getWorkDays($startDate,$endDate)["workdays"]
            ]);
        } else {
            return collect([
                "range" => $startDate . " to " . $endDate,
                "data" => $presenceHistoryAllLate->merge($presenceHistoryAllUnCheckout)->merge($presenceHistoryAllAbsent)->unique(),
                "holiday" => $this->getWorkDays($startDate,$endDate)["workdays"]
            ]);
        }
    }

    public function getWorkDays($startDate,$endDate){
        $client = new Client();
        $api_response = $client->get('https://www.googleapis.com/calendar/v3/calendars/en.indonesian%23holiday%40group.v.calendar.google.com/events?key='.env('GOOGLE_API_KEY'));
        $json = (string)$api_response->getBody();
        $holiday_indonesia = json_decode($json, true);

        $holiday_indonesia_final_detail = collect();
        $holiday_indonesia_final_date = collect();
        
        foreach ($holiday_indonesia["items"] as $value) {
            if(( ( $value["start"]["date"] >= $startDate ) && ( $value["start"]["date"] <= $endDate ) )){
                $holiday_indonesia_final_detail->push(["date" => $value["start"]["date"],"summary" => $value["summary"]]);
                $holiday_indonesia_final_date->push($value["start"]["date"]);
            }
        }

        $period = new DatePeriod(
             new DateTime($startDate),
             new DateInterval('P1D'),
             new DateTime($endDate)
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

        $startDate = Carbon::now()->subMonths()->format("Y-m-16");
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
                ->get()
                ->toArray();
        } else {
             $parameterUser = DB::table('users')
                ->join('presence__history', 'presence__history.nik', '=', 'users.nik')
                ->select('users.nik', 'users.name')
                ->where('presence_actual','>=',$startDate)
                ->where('presence_actual','<=',$endDate)
                ->get()
                ->toArray();
        }

        // return $startDate;

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
                ->where('presence_actual','>=',$startDate)
                ->where('presence_actual','<=',$endDate)
                ->get()
                ->toarray();
            $late = DB::table('presence__history')
                ->where('nik','=',$NikUser[$key])
                ->where('presence_actual','>=',$startDate)
                ->where('presence_actual','<=',$endDate)
                ->where('presence_condition','=',"Late")
                ->get()
                ->toarray();
            $ontime = DB::table('presence__history')
                ->where('nik','=',$NikUser[$key])
                ->where('presence_actual','>=',$startDate)
                ->where('presence_actual','<=',$endDate)
                ->where('presence_condition','=',"On-Time")
                ->get()
                ->toarray();
            $injury = DB::table('presence__history')
                ->where('nik','=',$NikUser[$key])
                ->where('presence_actual','>=',$startDate)
                ->where('presence_actual','<=',$endDate)
                ->where('presence_condition','=',"Injury-Time")
                ->get()
                ->toarray();
            $absen = DB::table('presence__history')
                ->where('nik','=',$NikUser[$key])
                ->where('presence_actual','>=',$startDate)
                ->where('presence_actual','<=',$endDate)
                ->where('presence_condition','<>',"Injury-Time")
                ->where('presence_condition','<>',"Late")
                ->where('presence_condition','<>',"On-Time")
                ->where('presence_condition','<>',"-")
                ->get()
                ->toarray();

            $where =  DB::table('presence__location_user')
                ->join('presence__location', 'presence__location_user.location_id', '=', 'presence__location.id')
                ->select('presence__location.location_name', 'user_id')
                ->get()
                ->toarray();


            $var[$stat]["all"] = sizeof($all);
            $var[$stat]["all"] = sizeof($late) + sizeof($ontime) + sizeof($injury);
            $var[$stat]["late"] = sizeof($late);
            $var[$stat]["ontime"] = sizeof($ontime);
            $var[$stat]["injury"] = sizeof($injury);
            $var[$stat]["absen"] = sizeof($absen);
            $var[$stat]["where"] = $where[0]->location_name;
            $var[$stat]["name"] = $status[$key];
        }

        return collect([
            "range" => $startDate . " to " . $endDate,
            "data" => $var,
        ]);
    }

    public function getDataReportPresence2(Request $req){

        // $startDate = Carbon::now()->subMonths(1)->format("Y-m-16");
        // $endDate = Carbon::now()->format("Y-m-16");

        $parameterUser = DB::table('users')
            ->join('presence__history', 'presence__history.nik', '=', 'users.nik')
            ->select('users.nik', 'users.name')
            ->where('presence_actual','>=',$req->start)
            ->where('presence_actual','<=',$req->end)
            ->get()
            ->toArray();
        

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
                ->toarray();
            $late = DB::table('presence__history')
                ->where('nik','=',$NikUser[$key])
                ->where('presence_actual','>=',$req->start)
                ->where('presence_actual','<=',$req->end)
                ->where('presence_condition','=',"Late")
                ->get()
                ->toarray();
            $ontime = DB::table('presence__history')
                ->where('nik','=',$NikUser[$key])
                ->where('presence_actual','>=',$req->start)
                ->where('presence_actual','<=',$req->end)
                ->where('presence_condition','=',"On-Time")
                ->get()
                ->toarray();
            $injury = DB::table('presence__history')
                ->where('nik','=',$NikUser[$key])
                ->where('presence_actual','>=',$req->start)
                ->where('presence_actual','<=',$req->end)
                ->where('presence_condition','=',"Injury-Time")
                ->get()
                ->toarray();
            $absen = DB::table('presence__history')
                ->where('nik','=',$NikUser[$key])
                ->where('presence_actual','>=',$req->start)
                ->where('presence_actual','<=',$req->end)
                ->where('presence_condition','<>',"Injury-Time")
                ->where('presence_condition','<>',"Late")
                ->where('presence_condition','<>',"On-Time")
                ->where('presence_condition','<>',"-")
                ->get()
                ->toarray();

            $where =  DB::table('presence__location_user')
                ->join('presence__location', 'presence__location_user.location_id', '=', 'presence__location.id')
                ->select('presence__location.location_name', 'user_id')
                ->get()
                ->toarray();


            $var[$stat]["all"] = sizeof($all);
            $var[$stat]["all"] = sizeof($late) + sizeof($ontime) + sizeof($injury);
            $var[$stat]["late"] = sizeof($late);
            $var[$stat]["ontime"] = sizeof($ontime);
            $var[$stat]["injury"] = sizeof($injury);
            $var[$stat]["absen"] = sizeof($absen);
            $var[$stat]["where"] = $where[0]->location_name;
            $var[$stat]["name"] = $status[$key];
        }

        return collect([
            "range" => $req->start . " to " . $req->end,
            "data" => $var,
        ]);
    }

    public function getExportReport(Request $req){

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

        $summarySheet->getStyle('A1:J1')->applyFromArray($titleStyle);
        $summarySheet->setCellValue('A1','All Presence');

        $headerContent = ["No", "Nik", "Name", "Date","Schedule","Check-In","Check-Out","Condition","Valid","Reason"];
        $summarySheet->getStyle('A2:J2')->applyFromArray($headerStyle);
        $summarySheet->fromArray($headerContent,NULL,'A2');

        if(isset($req->type)){
            $typeCompany = ($req->type == "SIP") ? "1" : "2";
            $dataPresence = $this->getPresenceReportData("all",$typeCompany)["data"]->sortBy('name');
            $exportName = 'Report Presence ' . $req->type . ' (reported at ' . date("Y-m-d") . ')';
        } else {
            $dataPresence = $this->getPresenceReportData("all")["data"]->sortBy('name');
            $exportName = 'Report Presence (reported at' . date("Y-m-d") . ')';
        }

        $dataPresence->map(function($item,$key) use ($summarySheet){
            $summarySheet->fromArray(array_merge([$key + 1],array_values(get_object_vars($item))),NULL,'A' . ($key + 3));
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

        $dataPresenceIndividual = $dataPresence->groupBy('name');

        $indexSheet = 0;
        foreach ($dataPresenceIndividual as $key => $item) {
            $spreadsheet->addSheet(new Worksheet($spreadsheet,$key));
            $detailSheet = $spreadsheet->setActiveSheetIndex($indexSheet + 1);

            $detailSheet->getStyle('A1:J1')->applyFromArray($titleStyle);
            $detailSheet->setCellValue('A1','Presence Report ' . $key);
            $detailSheet->mergeCells('A1:J1');

            $headerContent = ["No", "Nik", "Name", "Date","Schedule","Check-In","Check-Out","Condition","Valid","Reason"];
            $detailSheet->getStyle('A2:J2')->applyFromArray($headerStyle);
            $detailSheet->fromArray($headerContent,NULL,'A2');

            foreach ($item as $key => $eachPresence) {
                $detailSheet->fromArray(array_merge([$key + 1],array_values(get_object_vars($eachPresence))),NULL,'A' . ($key + 3));
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
            $indexSheet = $indexSheet + 1;
        }

        $spreadsheet->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $exportName . '.xlsx"');
        header('Cache-Control: max-age=0');
        
        $writer = new Xlsx($spreadsheet);
        return $writer->save("php://output");
    }
}
