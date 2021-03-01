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

    public function getPresenceReportData($typeData = "notAll"){
        


        $startDate = Carbon::now()->subMonths(1)->format("Y-m-15");
        $endDate = Carbon::now()->format("Y-m-15");

        // $startDate = "2020-12-15";
        // $endDate = "2021-01-15";
        
        // return $this->getWorkDays($startDate,$endDate);

        $workDays = $this->getWorkDays($startDate,$endDate)["workdays"]->values();

        // return $workDays;

        $parameterUser = PresenceHistory::groupBy('nik')
            ->whereRaw('`presence_actual` BETWEEN "' . $startDate . '" AND "' . $endDate . '"')
            ->pluck('nik');
        // $parameterUserMsp = Users::where('id_company',1)->get()->pluck('nik');
        // $parameterUser = ['1170498100'];

        // echo "<pre>";

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
        // print_r($presenceHistoryAll);
        // echo "</pre>";

        // dd($presenceHistoryAll);
        // dd($presenceHistoryAll->pluck('date')->values());
        // return $this->getWorkDays($startDate,$endDate)["workdays"];
        // dd($this->getWorkDays($startDate,$endDate)["workdays"]->values());
        // return $presenceHistoryAll->diffAssoc($this->getWorkDays($startDate,$endDate)["workdays"]);
        
        //       return $this->getWorkDays($startDate,$endDate)["workdays"]->values()->diff($presenceHistoryAll->pluck('date')->values())->values();
        
        // return $presenceHistoryAll->pluck('date')->diff($this->getWorkDays($startDate,$endDate)["workdays"]);
        
        // dd($this->getWorkDays($startDate,$endDate)["workdays"])->diff($presenceHistoryAll->pluck('date'));
        // dd($presenceHistoryAll->diffAssoc($this->getWorkDays($startDate,$endDate)["workdays"]));
        // return ;

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

    private function getWorkDays($startDate,$endDate){
        $client = new Client();
        $api_response = $client->get('https://www.googleapis.com/calendar/v3/calendars/en.indonesian%23holiday%40group.v.calendar.google.com/events?key='.env('GOOGLE_API_YEY'));
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

    public function getExportRerport(){

        // return $this->getPresenceReportData("all")["data"];

        $header = ["No", "Nik", "Name", "-","Schedule","Check-In","Check-Out","Condition","Valid","Reason"];
        // $sources = $this->getPresenceReportData("all")["data"]->sortBy('name')->values()->groupBy('nik');
        
        // echo "<pre>";
        // // print_r(expression)
        // foreach ($sources as $key => $source){
        //     $source = $source->keyBy('name');
        //     foreach ($source as $key => $value) {
        //         echo $key . "<br>";
        //     }
        // }
        // echo "</pre>";

        // dd($this->getPresenceReportData()["data"]->sortBy('name')->sortBy('date')->values());
        // dd((array)$this->getPresenceReportData()["data"]->first());
        // dd(array_merge(["no" => 1],(array)$this->getPresenceReportData()["data"]->first()));
        // return  $this->getPresenceReportData("all")["data"]->sortBy('name')->values();
        return Excel::create('Report Presence ' . date("Y-m-d"), function($excel) use ($header) {

            $excel->sheet('All Presence', function($sheet) use ($header){
                    $sheet->row(1,$header);
                    $row = 2;
                    $data = $this->getPresenceReportData("all")["data"]->sortBy('name')->values();
                    foreach ($data as $value) {
                        // $datasheet = array_push($datasheet, (array)$value);
                        $sheet->row($row,array_merge(["no" => $row - 1],(array)$value));
                        $row++;
                    }

                    // $sheet->fromArray($datasheet);
                });

            $sources = $this->getPresenceReportData("all")["data"]->sortBy('name')->groupBy('name');
            // $sources = $this->getPresenceReportData("all")["data"]->sortBy('name')->values()->groupBy('nik');

            foreach ($sources as $key => $source){
                // $source = $source->keyBy('name');
                // $source->sortBy('checkin');
                $excel->sheet($key, function($sheet) use ($header,$source){
                    $sheet->row(1,$header);
                    $row = 2;
                    $source = $source->sortBy('date');
                    // $data = $this->getPresenceReportData()["data"]->sortBy('name')->values();
                    foreach ($source as $value) {
                        // $datasheet = array_push($datasheet, (array)$value);
                        $sheet->row($row,array_merge(["no" => $row - 1],(array)$value));
                        $row++;
                    }

                    // $sheet->fromArray($datasheet);
                });
            }

                // $excel->sheet('Sheet 1', function($sheet) use ($header){
                //     $sheet->row(1,$header);
                //     $row = 2;
                //     $data = $this->getPresenceReportData()["data"]->sortBy('name')->values();
                //     foreach ($data as $value) {
                //         // $datasheet = array_push($datasheet, (array)$value);
                //         $sheet->row($row,array_merge(["no" => $row - 1],(array)$value));
                //         $row++;
                //     }

                //     // $sheet->fromArray($datasheet);
                // });
            })->download('xlsx');
        // return "asdfadfa";
    }
}
