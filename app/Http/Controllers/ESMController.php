<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EngineerSpent;
use Auth;
use DB;
use PDF; 
use Excel;
use App\User;
use App\ESMProgress;

class ESMController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position; 

        $owner2 = DB::table('users')
                    ->select('nik', 'id_company', 'id_position', 'id_division', 'id_territory', 'name', 'email', 'password', 'date_of_entry', 'date_of_birth', 'address', 'phone')
                    ->first();

        $year = DB::table("dvg_esm")->select("year")->groupby('year')->get();

        $datas = '';

        $datas_2018 = '';

        $datas_2019 = '';

        $notifClaim = '';

        if ($ter != null) {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $notif = DB::table('sales_lead_register')
            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->select('sales_lead_register.opp_name','sales_solution_design.nik')
            ->where('result','OPEN')
            ->orderBy('sales_lead_register.created_at','desc')
            ->get();
        }else{
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

        if ($div == 'HR' && $pos == 'HR MANAGER' || $div == 'FINANCE' && $pos == 'STAFF') {
            $datas_2018 = DB::table('dvg_esm')
                    ->join('users', 'users.nik', '=', 'dvg_esm.personnel')
                    ->select('no','date','users.name', 'type', 'description', 'amount', 'id_project', 'remarks', 'dvg_esm.status','dvg_esm.created_at', 'dvg_esm.personnel', 'dvg_esm.year')
                    ->where('year','2018')
                    ->get();

            $datas_2019 = DB::table('dvg_esm')
                    ->join('users', 'users.nik', '=', 'dvg_esm.personnel')
                    ->select('no','date','users.name', 'type', 'description', 'amount', 'id_project', 'remarks', 'dvg_esm.status','dvg_esm.created_at', 'dvg_esm.personnel', 'dvg_esm.year')
                    ->where('year','2019')
                    ->get();
        } elseif ($pos == 'ADMIN') {
            $datas_2018 = DB::table('dvg_esm')
                    ->join('users', 'users.nik', '=', 'dvg_esm.personnel')
                    ->select('no','date','nik_admin','users.name', 'type', 'description', 'amount', 'id_project', 'remarks', 'dvg_esm.status','dvg_esm.created_at', 'dvg_esm.personnel', 'dvg_esm.year')
                    ->where('nik_admin',$nik)
                    ->where('year', '2018')
                    ->get();

            $datas = DB::table('dvg_esm')
                    ->join('users', 'users.nik', '=', 'dvg_esm.personnel')
                    ->select('no','date','nik_admin','users.name', 'type', 'description', 'amount', 'id_project', 'remarks', 'dvg_esm.status','dvg_esm.created_at', 'dvg_esm.personnel', 'dvg_esm.year','dvg_esm.id_ems')
                    ->orderBy("no","desc")
                    ->where('year','2020')
                    ->where('personnel','1190500060')
                    ->get();
        } else {
            $datas_2018 = DB::table('dvg_esm')
                    ->join('users', 'users.nik', '=', 'dvg_esm.personnel')
                    ->select('no','date','personnel','users.name', 'type', 'description', 'amount', 'id_project', 'remarks', 'dvg_esm.status','dvg_esm.created_at', 'dvg_esm.personnel', 'dvg_esm.year')
                    ->where('personnel',$nik)
                    ->where('year', '2018')
                    ->get();

            $datas_2019 = DB::table('dvg_esm')
                    ->join('users', 'users.nik', '=', 'dvg_esm.personnel')
                    ->select('no','date','personnel','users.name', 'type', 'description', 'amount', 'id_project', 'remarks', 'dvg_esm.status','dvg_esm.created_at', 'dvg_esm.personnel', 'dvg_esm.year')
                    ->where('personnel',$nik)
                    ->where('year', '2019')
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
        }
        
       /* $nomor = DB::table('dvg_esm')
                    ->select('no')
                    ->where('no', $no)
                    ->first();*/

        return view('DVG/esm/esm', compact('datas','notif','notifOpen','notifsd','notiftp','owner2', 'notifClaim','datas_2018', 'datas_2019','year'))->with(['initView'=> $this->initMenuBase(),'feature_item'=>$this->RoleDynamic('claim')]);
    }

    public function getESM(){
        $nik = Auth::User()->nik;

        if (Auth::User()->id_position == 'ADMIN') {
            return array("data" => EngineerSpent::join('users', 'users.nik', '=', 'dvg_esm.personnel')
                    ->select('no','date','nik_admin','users.name', 'type', 'description', 'amount', 'id_project', 'remarks', 'dvg_esm.status','dvg_esm.created_at', 'dvg_esm.personnel', 'dvg_esm.year','dvg_esm.id_ems')
                    ->where("status","ADMIN")
                    ->where('year','2020')
                    ->where('nik_admin',$nik)
                    ->get());
        }elseif (Auth::User()->id_division == 'HR') {
            return array("data" => EngineerSpent::join('users', 'users.nik', '=', 'dvg_esm.personnel')
                    ->select('no','date','nik_admin','users.name', 'type', 'description', 'amount', 'id_project', 'remarks', 'dvg_esm.status','dvg_esm.created_at', 'dvg_esm.personnel', 'dvg_esm.year','dvg_esm.id_ems')
                    ->where('status','HRD')
                    ->orwhere('status','FINANCE')
                    ->orwhere('status','TRANSFER')
                    ->where('year','2020')
                    ->orderBy("dvg_esm.updated_at","desc")
                    ->get());
        }elseif (Auth::User()->id_division == 'FINANCE') {
            return array("data" => EngineerSpent::join('users', 'users.nik', '=', 'dvg_esm.personnel')
                    ->select('no','date','nik_admin','users.name', 'type', 'description', 'amount', 'id_project', 'remarks', 'dvg_esm.status','dvg_esm.created_at', 'dvg_esm.personnel', 'dvg_esm.year','dvg_esm.id_ems')
                    ->where('status','FINANCE')
                    ->orwhere('status','TRANSFER')
                    ->where('year','2020')
                    ->orderBy("dvg_esm.updated_at","asc")
                    ->get());
        }else{
            return array("data" => EngineerSpent::join('users', 'users.nik', '=', 'dvg_esm.personnel')
                    ->select('no','date','nik_admin','users.name', 'type', 'description', 'amount', 'id_project', 'remarks', 'dvg_esm.status','dvg_esm.created_at', 'dvg_esm.personnel', 'dvg_esm.year','dvg_esm.id_ems')
                    ->orderBy("no","desc")
                    ->where('year','2020')
                    ->where('personnel',$nik)
                    ->get());
        }
    }

    public function getFilterESMbyYear(Request $request){
        $nik = Auth::User()->nik;

        if (Auth::User()->id_position == 'ADMIN') {
            return array("data" => EngineerSpent::join('users', 'users.nik', '=', 'dvg_esm.personnel')
                    ->select('no','date','nik_admin','users.name', 'type', 'description', 'amount', 'id_project', 'remarks', 'dvg_esm.status','dvg_esm.created_at', 'dvg_esm.personnel', 'dvg_esm.year','dvg_esm.id_ems')
                    ->orderBy("no","desc")
                    ->where('nik_admin',$nik)
                    ->where('year',$request->year)
                    ->get());
        }elseif (Auth::User()->id_division == 'HR') {
            return array("data" => EngineerSpent::join('users', 'users.nik', '=', 'dvg_esm.personnel')
                    ->select('no','date','nik_admin','users.name', 'type', 'description', 'amount', 'id_project', 'remarks', 'dvg_esm.status','dvg_esm.created_at', 'dvg_esm.personnel', 'dvg_esm.year','dvg_esm.id_ems')
                    ->where('status','HRD')
                    ->orwhere('status','FINANCE')
                    ->orwhere('status','TRANSFER')
                    ->where('year',$request->year)
                    ->orderBy("dvg_esm.updated_at","desc")
                    ->get());
        }elseif (Auth::User()->id_division == 'FINANCE') {
            return array("data" => EngineerSpent::join('users', 'users.nik', '=', 'dvg_esm.personnel')
                    ->select('no','date','nik_admin','users.name', 'type', 'description', 'amount', 'id_project', 'remarks', 'dvg_esm.status','dvg_esm.created_at', 'dvg_esm.personnel', 'dvg_esm.year','dvg_esm.id_ems')
                    ->where('status','FINANCE')
                    ->orwhere('status','TRANSFER')
                    ->where('year',$request->year)
                    ->orderBy("dvg_esm.updated_at","asc")
                    ->get());
        }else{
            return array("data" => EngineerSpent::join('users', 'users.nik', '=', 'dvg_esm.personnel')
                    ->select('no','date','nik_admin','users.name', 'type', 'description', 'amount', 'id_project', 'remarks', 'dvg_esm.status','dvg_esm.created_at', 'dvg_esm.personnel', 'dvg_esm.year','dvg_esm.id_ems')
                    ->orderBy("no","desc")
                    ->where('year',$request->year)
                    ->where('personnel',$nik)
                    ->get());
        }
    }

    public function getFilterESMbyStatus(Request $request){
        $nik = Auth::User()->nik;

        if (Auth::User()->id_position == 'ADMIN') {
            return array("data" => EngineerSpent::join('users', 'users.nik', '=', 'dvg_esm.personnel')
                    ->select('no','date','nik_admin','users.name', 'type', 'description', 'amount', 'id_project', 'remarks', 'dvg_esm.status','dvg_esm.created_at', 'dvg_esm.personnel', 'dvg_esm.year','dvg_esm.id_ems')
                    ->orderBy("no","desc")
                    ->where('nik_admin',$nik)
                    ->where('year',$request->year)
                    ->where('status',$request->status)
                    ->get());
        }elseif (Auth::User()->id_division == 'HR') {
            return array("data" => EngineerSpent::join('users', 'users.nik', '=', 'dvg_esm.personnel')
                    ->select('no','date','nik_admin','users.name', 'type', 'description', 'amount', 'id_project', 'remarks', 'dvg_esm.status','dvg_esm.created_at', 'dvg_esm.personnel', 'dvg_esm.year','dvg_esm.id_ems')
                    ->where('status','HRD')
                    ->where('year',$request->year)
                    ->where('status',$request->status)
                    ->orderBy("dvg_esm.updated_at","desc")
                    ->get());
        }elseif (Auth::User()->id_division == 'FINANCE') {
            return array("data" => EngineerSpent::join('users', 'users.nik', '=', 'dvg_esm.personnel')
                    ->select('no','date','nik_admin','users.name', 'type', 'description', 'amount', 'id_project', 'remarks', 'dvg_esm.status','dvg_esm.created_at', 'dvg_esm.personnel', 'dvg_esm.year','dvg_esm.id_ems')
                    ->where('year',$request->year)
                    ->where('status',$request->status)
                    ->orderBy("dvg_esm.updated_at","asc")
                    ->get());
        }else{
            return array("data" => EngineerSpent::join('users', 'users.nik', '=', 'dvg_esm.personnel')
                    ->select('no','date','nik_admin','users.name', 'type', 'description', 'amount', 'id_project', 'remarks', 'dvg_esm.status','dvg_esm.created_at', 'dvg_esm.personnel', 'dvg_esm.year','dvg_esm.id_ems')
                    ->orderBy("no","desc")
                    ->where('year',$request->year)
                    ->where('status',$request->status)
                    ->where('personnel',$nik)
                    ->get());
        }
    }

    public function getEditEsm(Request $request){

        return EngineerSpent::join('users', 'users.nik', '=', 'dvg_esm.personnel')
                    ->select('no','date','nik_admin','users.name', 'type', 'description', 'amount', 'id_project', 'remarks', 'dvg_esm.status','dvg_esm.created_at', 'dvg_esm.personnel', 'dvg_esm.year','dvg_esm.id_ems')
                    ->orderBy("no","desc")
                    ->where('id_ems',$request->id_ems)
                    ->get();
    }

    public function import_claim(Request $request)
    {
        $path = $request->file('file')->getRealPath();
        $data = Excel::load($path)->get();
 
        if($data->count()){
            foreach ($data as $key => $value) {
                $arr[] = ['id_ems' => $value->id_ems, 'no' => $value->no, 'date' => $value->date, 'month' => $value->month, 'nik_admin' => $value->nik_admin, 'personnel' => $value->personnel, 'type' => $value->type, 'description' => $value->description, 'amount' => $value->amount, 'id_project' => $value->id_project, 'remarks' => $value->remarks, 'status' => $value->status, 'year' => $value->year];
            }
 
            if(!empty($arr)){
                EngineerSpent::insert($arr);
            }
        }

        return back()->with('success', 'Insert Record Successfully');
    }

    public function import_claim_progress(Request $request)
    {
        $path = $request->file('file')->getRealPath();
        $data = Excel::load($path)->get();
 
        if($data->count()){
            foreach ($data as $key => $value) {
                $arr[] = ['id' => $value->id, 'id_ems' => $value->id_ems, 'no' => $value->no, 'nik' => $value->nik, 'keterangan' => $value->keterangan, 'status' => $value->status, 'amount' => $value->amount];
            }
 
            if(!empty($arr)){
                ESMProgress::insert($arr);
            }
        }

        return back()->with('success', 'Insert Record Successfully');
    }

    public function detail_esm($no)
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        $notifClaim = '';

        if ($ter != null) {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $notif = DB::table('sales_lead_register')
            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->select('sales_lead_register.opp_name','sales_solution_design.nik')
            ->where('result','')
            ->orderBy('sales_lead_register.created_at','desc')
            ->get();
        }else{
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();
        }


        if ($div == 'TECHNICAL PRESALES' && $pos == 'MANAGER' ) {
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
            ->select('sales_lead_register.opp_name','sales_solution_design.nik')
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

        $nomor = DB::table('dvg_esm')
                    ->select('no')
                    ->where('no', $no)
                    ->first();

        $detail_esm = DB::table('dvg_esm_progress')
                    ->join('dvg_esm','dvg_esm_progress.no','=','dvg_esm.no')
                    ->join('users', 'dvg_esm_progress.nik', '=', 'users.nik')
                    ->select('dvg_esm_progress.id','dvg_esm_progress.created_at','dvg_esm_progress.keterangan','dvg_esm_progress.status','dvg_esm_progress.amount','users.name')
                    ->where('dvg_esm.no',$no)
                    ->get();

        $tampilkan = DB::table('dvg_esm')
                    ->join('users', 'users.nik', '=', 'dvg_esm.personnel')
                    ->select('no','date','users.name', 'type', 'description', 'amount', 'id_project', 'remarks', 'dvg_esm.status','dvg_esm.created_at')
                    ->where('dvg_esm.no',$no)
                    ->first();

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

        return view('DVG/esm/detail_esm',compact('detail_esm', 'notif', 'notifsd', 'notifOpen', 'notiftp', 'nomor', 'tampilkan','notifClaim'))->with(['initView'=>$this->initMenuBase()]);
    }

    public function tambah_return_hr(Request $request)
    {
        $no = $request['no_return_hr'];
        $tambah = new ESMProgress();
        $tambah->no = $no;
        $tambah->nik = Auth::User()->nik;
        $tambah->keterangan = $request['keterangan'];
        $tambah->status = 'ADMIN';
        $tambah->save();

        $update = EngineerSpent::where('no', $no)->first();
        $update->status = 'ADMIN';
        $update->update();

        return redirect()->back();
    }

    public function tambah_return_fnc(Request $request)
    {
        $no = $request['no_return_fnc'];
        $tambah = new ESMProgress();
        $tambah->no = $no;
        $tambah->nik = Auth::User()->nik;
        $tambah->keterangan = $request['keterangan'];
        $tambah->status = 'HRD';
        $tambah->save();

        $update = EngineerSpent::where('no', $no)->first();
        $update->status = 'HRD';
        $update->update();

        return redirect()->back();
    }

    public function assign_to_hrd(Request $request)
    {
        $number = $request['assign_to_hrd_edit'];

        $tambah = new ESMProgress();
        $tambah->id_ems = $number;
        $tambah->nik = Auth::User()->nik;
        $tambah->keterangan = $request['keterangan'];
        $tambah->status = 'HRD';
        $tambah->save();


        $update = EngineerSpent::where('id_ems', $number)->first();
        $update->status = 'HRD';
        $update->update();

        return redirect('/esm');
    }

    public function assign_to_fnc(Request $request)
    {
        $number = $request['assign_to_fnc_edit'];
        // $no = $request['no_return_fnc'];

        $tambah = new ESMProgress();
        $tambah->id_ems = $number;
        $tambah->nik = Auth::User()->nik;
        $tambah->keterangan = $request['keterangan'];
        $tambah->status = 'FINANCE';
        $tambah->amount = str_replace(',', '', $request['amount']);
        $tambah->save();

        $update = EngineerSpent::where('id_ems', $number)->first();
        $update->status = 'FINANCE';
        $update->update();

        return redirect('/esm');
    }

    public function assign_to_adm(Request $request)
    {
        $number = $request['assign_to_adm_edit'];

        $tambah = new ESMProgress();
        $tambah->id_ems = $number;
        $tambah->nik = Auth::User()->nik;
        $tambah->keterangan = $request['keterangan'];
        $tambah->amount = str_replace(',', '', $request['amount']);
        $tambah->status = 'TRANSFER';
        $tambah->save();

        $update = EngineerSpent::where('id_ems', $number)->first();
        $update->status = 'TRANSFER';
        $update->update();

        return redirect('/esm');
    }

    public function store(Request $request)
    {
        
        /*$month = substr($request['date'],5,2);
        $date = substr($request['date'],8,2);

        $inc = DB::table('dvg_esm')
                    ->select('no')
                    ->get();
        $increment = count($inc);
        $nomor = $increment+1;
        if($nomor < 10){
            $nomor = '000' . $nomor;
        }elseif($nomor > 9 && $nomor < 99){
            $nomor = '00' . $nomor;
        }

        $no = $month . $date . $nomor;*/

        $tambah = new EngineerSpent();
        $tambah->no = $request['no'];
        $tambah->date = $request['date'];
        $tambah->nik_admin = Auth::User()->nik;
        $tambah->personnel = $request['personnel'];
        $tambah->type = $request['type'];
        $tambah->description = $request['description'];
        $tambah->amount = str_replace(',', '', $request['amount']);
        $tambah->id_project = $request['id_project'];
        $tambah->remarks = $request['remarks'];
        $tambah->status = 'ADMIN';
        $tambah->month = date("n");
        $tambah->year = date("Y");
        $tambah->save();

        $get_id = EngineerSpent::select('id_ems')->orderBy('created_at','desc')->first();

        $tambahprogress = new ESMProgress();
        $tambahprogress->id_ems = $get_id->id_ems;
        $tambahprogress->no = $request['no'];
        $tambahprogress->nik = Auth::User()->nik;
        $tambahprogress->keterangan = $request['description'];
        $tambahprogress->amount = str_replace(',', '', $request['amount']);
        $tambahprogress->status = 'ADMIN';
        $tambahprogress->save();


        return redirect('/esm')->with('success', 'Created Engineer Spent Successfully!');
    }

    public function edit(Request $request)
    {
        $no = $request['edit_no'];

        $update = EngineerSpent::where('no', $no)->first();
        $update->no = $request['edit_no'];
        $update->type = $request['edit_type'];
        $update->description = $request['edit_description'];
        $update->amount = $request['edit_amountclaim'];
        $update->id_project = $request['edit_id_project'];
        $update->remarks = $request['edit_remarks'];
        $update->update();

        return redirect('/esm')->with('success', 'Update Successfully!');
    }

    public function destroy($id_ems)
    {
        $hapus = EngineerSpent::where('id_ems',$id_ems);
        $hapus->delete();

        return redirect()->back()->with('alert', 'Deleted!');
    }

    public function downloadpdf()
    {
        $datas = DB::table('dvg_esm')
                    ->join('users', 'users.nik', '=', 'dvg_esm.personnel')
                    ->select('no','date','users.name', 'type', 'description', 'amount', 'id_project', 'remarks')
                    ->get();

        $pdf = PDF::loadView('DVG.esm.esm_pdf', compact('datas'));
        return $pdf->download('Rekap Claim Management'.date("d-m-Y").'.pdf');
    }

    public function downloadExcel(Request $request)
    {
        $nama = 'Engineer Spent Management '.date('Y-m-d');
        Excel::create($nama, function ($excel) use ($request) {
        $excel->sheet('Rekap Engineer Spent Management', function ($sheet) use ($request) {
        
        $sheet->mergeCells('A1:H1');

       // $sheet->setAllBorders('thin');
        $sheet->row(1, function ($row) {
            $row->setFontFamily('Calibri');
            $row->setFontSize(11);
            $row->setAlignment('center');
            $row->setFontWeight('bold');
        });

        $sheet->row(1, array('REKAP ENGINEER SPENT MANAGEMENT'));

        $sheet->row(2, function ($row) {
            $row->setFontFamily('Calibri');
            $row->setFontSize(11);
            $row->setFontWeight('bold');
        });

        $datas = EngineerSpent::join('users', 'users.nik', '=', 'dvg_esm.personnel')
                    ->select('no','date','users.name', 'type', 'description', 'amount', 'id_project', 'remarks')
                    ->get();

       // $sheet->appendRow(array_keys($datas[0]));
            $sheet->row($sheet->getHighestRow(), function ($row) {
                $row->setFontWeight('bold');
            });

             $datasheet = array();
             $datasheet[0]  =   array("NO", "DATE", "NAME", "TYPE", "DESCRIPTION",  "AMOUNT", "ID PROJECT", "REMARKS");
             $i=1;

            foreach ($datas as $data) {

               // $sheet->appendrow($data);
              $datasheet[$i] = array(
                            $data['no'],
                            $data['date'],
                            $data['name'],
                            $data['type'],
                            $data['description'],
                            $data['amount'],
                            $data['id_project'],
                            $data['remarks']
                        );
              
              $i++;
            }

            $sheet->fromArray($datasheet);
        });

        })->export('xls');
    }

    public function claim_pending(){
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        if ($div == 'FINANCE') {
            $datas = DB::table('dvg_esm')
                    ->join('users', 'users.nik', '=', 'dvg_esm.personnel')
                    ->select('no','date','users.name', 'type', 'description', 'amount', 'id_project', 'remarks', 'dvg_esm.status')
                    ->where('dvg_esm.status', 'FINANCE')
                    ->get();
        }elseif ($div == 'HR') {
            $datas = DB::table('dvg_esm')
                    ->join('users', 'users.nik', '=', 'dvg_esm.personnel')
                    ->select('no','date','users.name', 'type', 'description', 'amount', 'id_project', 'remarks', 'dvg_esm.status')
                    ->where('dvg_esm.status', 'HRD')
                    ->get();
        }

        if ($ter != null) {
            $total_ter = DB::table("sales_lead_register")
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->where('id_territory', $ter)
                        ->where('result', 'win')
                        ->sum('amount');
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $total_ter = DB::table("sales_lead_register")
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                        ->where('sales_solution_design.nik', $nik)
                        ->where('result', 'win')
                        ->sum('amount');
        }else{
            $total_ter = DB::table("sales_lead_register")
                        ->where('result', 'win')
                        ->sum('amount');
        }

        if ($ter != null) {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();
        }else{
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();
        }

        if ($div == 'TECHNICAL PRESALES' && $pos == 'MANAGER' ) {
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
        }

        return view('report/claim_finance', compact('datas','notif','notifOpen','notifsd','notiftp', 'notifClaim'));
    }

     public function claim_transfer(){
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

       if ($div == 'FINANCE') {
            $datas = DB::table('dvg_esm')
                    ->join('users', 'users.nik', '=', 'dvg_esm.personnel')
                    ->select('no','date','users.name', 'type', 'description', 'amount', 'id_project', 'remarks', 'dvg_esm.status')
                    ->where('dvg_esm.status', 'TRANSFER')
                    ->get();
        } 

        if ($ter != null) {
            $total_ter = DB::table("sales_lead_register")
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->where('id_territory', $ter)
                        ->where('result', 'win')
                        ->sum('amount');
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $total_ter = DB::table("sales_lead_register")
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                        ->where('sales_solution_design.nik', $nik)
                        ->where('result', 'win')
                        ->sum('amount');
        }else{
            $total_ter = DB::table("sales_lead_register")
                        ->where('result', 'win')
                        ->sum('amount');
        }

        if ($ter != null) {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();
        }else{
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();
        }

        if ($div == 'TECHNICAL PRESALES' && $pos == 'MANAGER' ) {
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
        }

        return view('report/claim_finance', compact('datas','notif','notifOpen','notifsd','notiftp', 'notifClaim'));
    }

    public function claim_admin(){
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

       if ($pos == 'ADMIN') {
            $datas = DB::table('dvg_esm')
                    ->join('users', 'users.nik', '=', 'dvg_esm.personnel')
                    ->select('no','date','users.name', 'type', 'description', 'amount', 'id_project', 'remarks', 'dvg_esm.status', 'nik_admin')
                    ->where('dvg_esm.status', 'ADMIN')
                    ->where('nik_admin', $nik)
                    ->get();
        } 

        if ($ter != null) {
            $total_ter = DB::table("sales_lead_register")
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->where('id_territory', $ter)
                        ->where('result', 'win')
                        ->sum('amount');
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $total_ter = DB::table("sales_lead_register")
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                        ->where('sales_solution_design.nik', $nik)
                        ->where('result', 'win')
                        ->sum('amount');
        }else{
            $total_ter = DB::table("sales_lead_register")
                        ->where('result', 'win')
                        ->sum('amount');
        }

        if ($ter != null) {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();
        }else{
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();
        }

        if ($div == 'TECHNICAL PRESALES' && $pos == 'MANAGER' ) {
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
        }

        return view('report/claim_finance', compact('datas','notif','notifOpen','notifsd','notiftp', 'notifClaim'));
    }

}
