<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SalesHandover;
use DB;
use App\ShoTransaction;
use App\Sales;
use Auth;

class SHOController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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

        $notifClaim = '';

        if($div == 'SALES'){
            $lead = DB::table('sales_sho')
                ->join('sales_lead_register','sales_lead_register.lead_id','=','sales_sho.lead_id')
                ->join('users','users.nik','=','sales_lead_register.nik')/*
                ->join('sales_sho_transaction','sales_sho_transaction.id_sho','=','sales_sho.id_sho')*/
                ->select('sales_lead_register.nik','sales_sho.id_sho','sales_sho.timeline','sales_sho.sow','sales_sho.top','sales_sho.service_budget','sales_sho.meeting_date','users.name','sales_sho.updated_at','sales_lead_register.status_sho', 'sales_sho.lead_id')
                ->where('id_territory', $ter)
                ->get();
        }elseif($div == 'TECHNICAL PRESALES' || $div == 'TECHNICAL' || $div == 'PMO'){
            $lead = DB::table('sales_sho')
                ->join('sales_lead_register','sales_lead_register.lead_id','=','sales_sho.lead_id')
                ->join('users','users.nik','=','sales_lead_register.nik')
                // ->join('sales_sho_transaction','sales_sho_transaction.id_sho','=','sales_sho.id_sho')
                ->select('sales_lead_register.nik','sales_sho.id_sho','sales_sho.timeline','sales_sho.sow','sales_sho.top','sales_sho.service_budget','sales_sho.meeting_date','users.name','sales_sho.updated_at','sales_lead_register.status_sho', 'sales_sho.lead_id')
                // ->where('sales_sho_transaction.nik', $nik)
                ->get();
        }else{
            $lead = DB::table('sales_sho')
                ->join('sales_lead_register','sales_lead_register.lead_id','=','sales_sho.lead_id')
                ->join('users','users.nik','=','sales_lead_register.nik')/*
                ->join('sales_sho_transaction','sales_sho_transaction.id_sho','=','sales_sho.id_sho')*/
                ->select('sales_lead_register.nik','sales_sho.id_sho','sales_sho.timeline','sales_sho.sow','sales_sho.top','sales_sho.service_budget','sales_sho.meeting_date','users.name','sales_sho.updated_at','sales_lead_register.status_sho', 'sales_sho.lead_id')
                ->get();
        }

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
            ->select('sales_lead_register.opp_name','sales_solution_design.nik','sales_lead_register.lead_id')
            ->where('result','SD')
            ->orderBy('sales_lead_register.created_at','desc')
            ->get();
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $notifsd= DB::table('sales_lead_register')
            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->select('sales_lead_register.opp_name','sales_solution_design.nik','sales_lead_register.lead_id')
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
            ->select('sales_lead_register.opp_name','sales_solution_design.nik','sales_lead_register.lead_id')
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

        return view('sales/sho',compact('lead','notif','notifOpen','notifsd','notiftp', 'notifClaim'))->with(['initView'=> $this->initMenuBase(),'feature_item'=>$this->RoleDynamic('salesHandover')]);
    }

    public function detail_sho($id_sho)
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        $notifClaim = "";

        $presales = DB::table('users')
                    ->select('name','nik')
                    ->where('id_division','TECHNICAL PRESALES')
                    ->get();

        $ter1 = DB::table('users')
                    ->select('name','nik')
                    ->where('id_territory','TERRITORY 1')
                    ->get();

        $ter2 = DB::table('users')
                    ->select('name','nik')
                    ->where('id_territory','TERRITORY 2')
                    ->get();

        $ter3 = DB::table('users')
                    ->select('name','nik')
                    ->where('id_territory','TERRITORY 3')
                    ->get();

        $ter4 = DB::table('users')
                    ->select('name','nik')
                    ->where('id_territory','TERRITORY 4')
                    ->get();

        $ter5 = DB::table('users')
                    ->select('name','nik')
                    ->where('id_territory','TERRITORY 5')
                    ->get();

        $ter6 = DB::table('users')
                    ->select('name','nik')
                    ->where('id_territory','TERRITORY 6')
                    ->get();

        $engineer = DB::table('users')
                    ->select('name','nik')
                    ->where('id_position','ENGINEER MANAGER')
                    ->orwhere('id_position','ENGINEER STAFF')
                    ->get();

        $pmo = DB::table('users')
                    ->select('name','nik')
                    ->where('id_division','PMO')
                    ->get();

        $tampilkan = SalesHandover::find($id_sho);

        $tampilkanb =DB::table('sales_sho')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','sales_sho.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->select('users.nik','sales_sho.id_sho','users.name')
                    ->where('id_sho',$id_sho)
                    ->first();

        $tampilkans = DB::table('sales_sho_transaction')
                    ->join('users','users.nik','=','sales_sho_transaction.nik')
                    ->join('sales_sho','sales_sho.id_sho','=','sales_sho_transaction.id_sho')
                    ->select('sales_sho_transaction.id_transaction','sales_sho.lead_id', 'users.name', 'sales_sho_transaction.keterangan', 'sales_sho_transaction.tanggal_hadir','sales_sho_transaction.updated_at','users.nik','sales_sho.id_sho','sales_sho_transaction.status','sales_sho.meeting_date','sales_sho_transaction.updated_at')
                    ->where('sales_sho_transaction.id_sho',$id_sho)
                    ->get();


        $tampilkanc = DB::table('sales_sho_transaction')
                    ->select('updated_at')
                    ->first();

        $tampilkanz = DB::table('sales_sho_transaction')
                    ->select('created_at')
                    ->where('id_sho',$id_sho)
                    ->first();

        $tampilkant = DB::table('sales_sho')
                    ->select(DB::raw("DATEDIFF(now(),created_at) AS DAYS"))
                    ->where('id_sho',$id_sho)
                    ->get();

        $tampilkanx = substr($tampilkant,9,-2);

        $now = 0;

        // if ($ter != null) {
        //     $notif = DB::table('sales_lead_register')
        //     ->select('opp_name')
        //     ->orderBy('created_at','desc')->first();
        // }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
        //     $notif = DB::table('sales_lead_register')
        //     ->select('opp_name')
        //     ->orderBy('created_at','desc')->first();
        // }else{
        //     $notif = DB::table('sales_lead_register')
        //     ->select('opp_name')
        //     ->orderBy('created_at','desc')->first();
        // }

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
            ->select('sales_lead_register.opp_name','sales_solution_design.nik','sales_lead_register.lead_id')
            ->where('result','SD')
            ->orderBy('sales_lead_register.created_at','desc')
            ->get();
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $notifsd= DB::table('sales_lead_register')
            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->select('sales_lead_register.opp_name','sales_solution_design.nik','sales_lead_register.lead_id')
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
            ->select('sales_lead_register.opp_name','sales_solution_design.nik','sales_lead_register.lead_id')
            ->where('result','SD')
            ->orderBy('sales_lead_register.created_at','desc')
            ->get();
        }

        if ($div == 'SALES' && $pos == 'MANAGER') {
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

        return view('sales/detail_sho',compact('tampilkan','tampilkans','notif','notifOpen','tampilkanc','tampilkant','tampilkanb','now','tampilkanx','tampilkanz','notifsd','presales','notiftp','ter1','ter2','ter3','ter4','ter5','ter6','engineer','pmo', 'notifClaim'))->with(['initView'=>$this->initMenuBase(),'feature_item'=>$this->RoleDynamic('salesHandover')]);
        // return view('sales/detail_sho')->with('tampilkan',$tampilkan);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /*$angka = $request['pro_budget'];*/
        /*$format_rupiah = number_format($angka, '2', ',', '.');*/
      /*  $leads = DB::table('sales_lead_register')
        		->select('lead_id')
        		->get();

        $leadx = (string)$leads;

        $id_pro = DB::table('tb_id_project')
        		->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
        		->select('tb_id_project.id_project')
        		->where('tb_id_project.lead_id',$leadx)
        		->first();*/

        $lead_id = $request['lead_to_sho'];
        
        $update = Sales::where('lead_id', $lead_id)->first();
        $update->status_handover = 'handover';
        $update->update();

        $tambah_sho = new SalesHandover();
        $tambah_sho->lead_id = $request['lead_to_sho'];
        $tambah_sho->sow = $request['sow'];
        $tambah_sho->timeline = $request['timeline'];
        $tambah_sho->top = $request['top'];
        // $tambah_sho->service_budget = $request['pro_budget'];
        $tambah_sho->service_budget = str_replace(',', '', $request['pro_budget']);
        $edate = strtotime($_POST['meeting']); 
        $edate = date("Y-m-d",$edate);
        $tambah_sho->meeting_date = $edate;
        $tambah_sho->save();

        return redirect('sho');
        //
    }

    public function store_sho_transac(Request $request){
        /*$nik = Auth::User()->nik;*/
        $tambah = new ShoTransaction();
        $tambah->id_sho = $request['id_sho'];/*
        $tambah->nik = $nik;*/
        $tambah->nik = $request['nik_transaction'];
        $tambah->tanggal_hadir = date('Y-m-d H:i:s');
        $tambah->keterangan = $request['keterangan'];
        $tambah->status = 'done';
        $tambah->save();


        return redirect()->back();
    }

    public function update_sho_transac(Request $request){
        $id_transaction = $request['id_sho_transac'];

        $update = ShoTransaction::where('id_transaction', $id_transaction)->first();
        $update->keterangan = $request['keterangan'];
        $update->status = '';
        $update->update();


        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show_($id)
    {
       
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update_sho(Request $request)
    {
        $id_sho = $request['id_sho'];

        $update = SalesHandover::where('id_sho', $id_sho)->first();
        $update->sow = $request['sow'];
        $update->timeline = $request['timeline'];
        $update->top = $request['top'];
        $update->service_budget = str_replace(',' , '', $request['pro_budget']);
        $update->meeting_date = $request['meeting_date'];
        $update->update();//

        return redirect('sho');
        // return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function update_detail_sho(Request $request)
    {
      
        //
    }

    public function destroy_detail($id_transaction)
    {
      $hapus = ShoTransaction::find($id_transaction);
      $hapus->delete();

      return redirect()->back();
    }

}
