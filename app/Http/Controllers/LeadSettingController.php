<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use App\SalesChangeLog;
use Carbon\Carbon;
use Mail;
use App\Mail\ChangeLeadOwnerMail;


class LeadSettingController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function notification_legacy(){
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position; 
        $company = DB::table('users')->select('id_company')->where('nik',$nik)->first();
        $com = $company->id_company;
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
        // return $notif;

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
        // return $notifOpen;

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
        // return $notifsd;

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
        // return $notiftp;

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
        }
        return collect([
            "notif" => $notif,
            "notifOpen" => $notifOpen,
            "notifsd" => $notifsd,
            "notiftp" => $notiftp
        ]);
    }

    public function index(){
    	
    	$notifAll = $this->notification_legacy();
    	
    	$notif = $notifAll["notif"];
    	$notifOpen = $notifAll["notifOpen"];
    	$notifsd = $notifAll["notifsd"];
    	$notiftp = $notifAll["notiftp"];
    	
    	return view('sales/setting/index',compact('notif','notifOpen','notifsd','notiftp'))->with(['initView'=> $this->initMenuBase()]);
    }

    private function getData(){
        return DB::table('sales_lead_register')
            ->select(
                'users.name',
                'sales_lead_register.lead_id',
                'sales_lead_register.opp_name',
                'tb_contact.brand_name'
            )
            ->where('users.id_company','=','1')
            //->where('sales_lead_register.result','<>','LOSE')
            //->where('sales_lead_register.result','<>','WIN')
            ->whereRaw('`sales_lead_register`.`opp_name` IS NOT NULL')
            ->join('tb_contact','sales_lead_register.id_customer','=','tb_contact.id_customer')
            ->join('users','users.nik','=','sales_lead_register.nik');
    }

    public function getDataLead(){
        return collect(["data" => $this->getData()->get()]);
    }

    public function getDataLeadPerSales(Request $req){
        return collect(["data" => $this->getData()->where('users.name','=',$req->salesName)->get()]);
    }

    public function getDataListSales(Request $req){
        return DB::table('sales_lead_register')
            ->select('users.name')
            ->selectRaw('COUNT(*) AS `ALL`')
            ->selectRaw('COUNT(IF(`sales_lead_register`.`result` = "WIN", 1, NULL)) AS `WIN`')
            ->selectRaw('COUNT(IF(`sales_lead_register`.`result` = "LOSE", 1, NULL)) AS `LOSE`')
            ->selectRaw('COUNT(IF(`sales_lead_register`.`result` = "OPEN", 1, NULL) OR IF(`sales_lead_register`.`result` = "TP", 1, NULL) OR IF(`sales_lead_register`.`result` = "SD", 1, NULL)) AS `ACTIVE`')
            ->groupBy('sales_lead_register.nik')
            ->join('users','sales_lead_register.nik','=','users.nik')
            ->where('users.id_company','=','1')
            ->orderBy('all','ASC')
            ->get();
    }

    public function postUpdateSales(Request $req){
        $data = collect();
        foreach ($req->data as $key => $value) {
            $lead_id = substr($value['id_lead'],1, strpos($value['id_lead'], "]") - 1);
            $name_sales =  substr($value['to_sales'],11, strlen($value['to_sales']) - 11);

            $nik = DB::table('users')
                ->where('name','LIKE',"%" . $name_sales . "%")
                ->value('nik');

            $lead = DB::table('sales_lead_register')
                ->where('lead_id',$lead_id);
            $name_sales_before = DB::table('users')->where('nik',$lead->value('nik'))->value('name');
            $update = $lead->update(['nik' => $nik]);

            $tambah_log = new SalesChangeLog();
            $tambah_log->lead_id = $lead_id;
            $tambah_log->nik = Auth::User()->nik;
            $tambah_log->status = 'Change Lead Owner From ' . $name_sales_before . ' to ' . $name_sales;
            $tambah_log->save();

            $temp = [
                'before_sales' => $name_sales_before,
                'after_sales' => $name_sales,
                'changer' => Auth::User()->name,
                'date_change' => Carbon::now()->toFormattedDateString(),
                'lead_id' => $lead_id
            ];
            $data->push($temp);
        }
        Mail::to('agastya@sinergy.co.id')->send(new ChangeLeadOwnerMail($data));
    }

    public function getTestMailable(){
        $data = collect([[
                'before_sales' => "Rama Agastya",
                'after_sales' => "Rheza Pangalela ",
                'changer' => "Rheza Pangalela",
                'date_change' => Carbon::now()->toFormattedDateString(),
                'lead_id' => "ABC1231021"
            ],[
                'before_sales' => "Rama Agastya",
                'after_sales' => "Rheza Pangalela ",
                'changer' => "Rheza Pangalela",
                'date_change' => Carbon::now()->toFormattedDateString(),
                'lead_id' => "ABC1231021"
            ],[
                'before_sales' => "Rama Agastya",
                'after_sales' => "Rheza Pangalela ",
                'changer' => "Rheza Pangalela",
                'date_change' => Carbon::now()->toFormattedDateString(),
                'lead_id' => "ABC1231021"
            ],[
                'before_sales' => "Rama Agastya",
                'after_sales' => "Rheza Pangalela ",
                'changer' => "Rheza Pangalela",
                'date_change' => Carbon::now()->toFormattedDateString(),
                'lead_id' => "ABC1231021"
            ]]);

        return new ChangeLeadOwnerMail($data);
    }
}
