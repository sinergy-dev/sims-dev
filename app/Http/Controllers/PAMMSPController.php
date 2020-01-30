<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use App\pam_msp;
use App\pam_produk_msp;
use App\pam_progress_msp;
use PDF;
use Excel;
use App\PR_MSP;
use App\PONumberMSP;
use App\POAssetMSP;

class PAMMSPController extends Controller
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

    public function index(Request $request)
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        if ($div == 'FINANCE' && $pos == 'STAFF') {
            $pam = DB::table('tb_pam_msp')
                ->join('users','users.nik','=','tb_pam_msp.personel')
                ->join('tb_pr_msp','tb_pr_msp.no','=','tb_pam_msp.no_pr')
                ->select('tb_pam_msp.id_pam','tb_pr_msp.date','tb_pr_msp.no_pr','tb_pam_msp.ket_pr','tb_pam_msp.note_pr','tb_pam_msp.to_agen','tb_pam_msp.status','users.name','tb_pam_msp.subject','tb_pam_msp.amount', 'ppn', 'terms')
                ->get();
        } elseif ($pos == 'ADMIN') {
            $pam = DB::table('tb_pam_msp')
                ->join('users','users.nik','=','tb_pam_msp.personel')
                ->join('tb_po_asset_msp', 'tb_po_asset_msp.id_pr_asset', '=', 'tb_pam_msp.id_pam')
                ->join('tb_pr_msp','tb_pr_msp.no','=','tb_pam_msp.no_pr')
                ->select('tb_pam_msp.id_pam','tb_pam_msp.date_handover','tb_pr_msp.no_pr','tb_pam_msp.ket_pr','tb_pam_msp.note_pr','tb_pam_msp.to_agen','tb_pam_msp.status','users.name','tb_pam_msp.subject', 'tb_pr_msp.no', 'tb_pr_msp.date', 'tb_pam_msp.attention', 'tb_pam_msp.project', 'tb_pam_msp.project_id', 'ppn', 'terms', 'tb_po_asset_msp.id_po_asset', 'tb_po_asset_msp.id_pr_asset')
                ->where('tb_pam_msp.nik_admin',$nik)
                ->get();

        }

        $no_pr = DB::table('tb_pr_msp')
                ->select('result','no_pr','no')
                ->where('result','T')
                ->get();

        $pams = DB::table('tb_pam_msp')
            ->select('id_pam')
            ->get();

        $produks = DB::table('tb_pam_msp')
            ->join('tb_pr_product_msp','tb_pr_product_msp.id_pam','=','tb_pam_msp.id_pam')
            ->select('tb_pr_product_msp.name_product','tb_pr_product_msp.qty','tb_pr_product_msp.id_pam','tb_pr_product_msp.nominal')
            ->get();

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

        $sum = DB::table('tb_pam_msp')
            ->select('id_pam')
            ->sum('id_pam');

        $count_product = DB::table('tb_pr_product_msp')
            ->select('id_product')
            ->sum('id_product');

        $total_amount = DB::table('tb_pr_product_msp')
                    ->select('nominal')
                    ->sum('nominal');

        $from = DB::table('users')
                ->select('nik', 'name')
                ->where('id_company', '2')
                ->get();

        $project_id = DB::table('tb_id_project')
                        ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                        ->join('users','users.nik','=','sales_lead_register.nik')
                        ->select('id_project')
                        ->where('id_company', '2')
                        ->get();

        return view('admin_msp/pam',compact('notif','notifOpen','notifsd','notiftp','notifClaim','pam','produks','pams','sum','id_pam','count_product','total_amount','no_pr','$total_amount','from', 'project_id'));
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

    public function detail_pam($id_pam)
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

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

        $detail_pam = DB::table('tb_pam_progress_msp')
                    ->join('users', 'tb_pam_progress_msp.nik', '=', 'users.nik')
                    ->select('tb_pam_progress_msp.id_progress','tb_pam_progress_msp.created_at','tb_pam_progress_msp.keterangan','tb_pam_progress_msp.status','users.name')
                    ->where('tb_pam_progress_msp.id_pam',$id_pam)
                    ->get();

        $tampilkan = DB::table('tb_pam_msp')
                    ->join('users', 'users.nik', '=', 'tb_pam_msp.personel')
                    ->join('tb_pr_msp','tb_pr_msp.no_pr','=','tb_pam_msp.no_pr')
                    ->join('tb_pr_product_msp','tb_pr_product_msp.id_pam','=','tb_pam_msp.id_pam')
                    ->select('tb_pam_msp.id_pam','tb_pr_msp.no_pr','tb_pam_msp.nik_admin','tb_pam_msp.date_handover','users.name', 'tb_pam_msp.to_agen', 'tb_pr_product_msp.nominal','tb_pam_msp.ket_pr','tb_pam_msp.status', 'tb_pr_msp.date')
                    ->where('tb_pam_msp.id_pam',$id_pam)
                    ->first();

        $produks = DB::table('tb_pam_msp')
            ->join('tb_pr_product_msp','tb_pr_product_msp.id_pam','=','tb_pam_msp.id_pam')
            ->select('tb_pr_product_msp.id_product','tb_pr_product_msp.name_product','tb_pr_product_msp.qty','tb_pr_product_msp.id_pam','tb_pr_product_msp.nominal','tb_pr_product_msp.total_nominal','tb_pr_product_msp.description', 'tb_pr_product_msp.msp_code')
            ->where('tb_pam_msp.id_pam',$id_pam)
            ->get();

        $total_produk = DB::table('tb_pam_msp')
            ->join('tb_pr_product_msp','tb_pr_product_msp.id_pam','=','tb_pam_msp.id_pam')
            ->select('tb_pr_product_msp.id_product','tb_pr_product_msp.name_product','tb_pr_product_msp.qty','tb_pr_product_msp.id_pam','tb_pr_product_msp.nominal','tb_pr_product_msp.total_nominal','tb_pr_product_msp.description')
            ->where('tb_pam_msp.id_pam',$id_pam)
            ->count('name_product');


        $total_amount = DB::table('tb_pr_product_msp')
                    ->select('total_nominal')
                    ->where('id_pam',$id_pam)
                    ->sum('total_nominal');

        $count_pam = DB::table('tb_pr_product_msp')
                    ->where('id_pam',$id_pam)
                    ->count('name_product');

        $project_id = DB::table('tb_id_project')
                        ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                        ->join('users','users.nik','=','sales_lead_register.nik')
                        ->select('id_project')
                        ->where('id_company', '2')
                        ->get();


        return view('admin_msp/detail_pam',compact('count_pam','total_produk','detail_pam', 'notif', 'notifsd', 'notifOpen', 'notiftp','notifClaim', 'nomor', 'tampilkan','produks','total_amount', 'project_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
  
    public function tambah(Request $request)
    {
        /*$month_pr = substr($request['date'],5,2);
        $year_pr = substr($request['date'],0,4);*/

        $month_pr = date("m");
        $year_pr = date("Y");

        $array_bln = array('01' => "I",
                            '02' => "II",
                            '03' => "III",
                            '04' => "IV",
                            '05' => "V",
                            '06' => "VI",
                            '07' => "VII",
                            '08' => "VIII",
                            '09' => "IX",
                            '10' => "X",
                            '11' => "XI",
                            '12' => "XII");
        $bln = $array_bln[$month_pr];

        $getnumber = PR_MSP::orderBy('no', 'desc')->first();
        $getnumber_po = PONumberMSP::orderBy('no', 'desc')->first();


//Penomoran PO
        if($getnumber_po == NULL){
            $getlastnumber_po = 1;
            $lastnumber_po = $getlastnumber_po;
        } else{
            $lastnumber_po = $getnumber_po->no+1;
        }

        if($lastnumber_po < 10){
           $akhirnomor_po = '000' . $lastnumber_po;
        }elseif($lastnumber_po > 9 && $lastnumber_po < 100){
           $akhirnomor_po = '00' . $lastnumber_po;
        }elseif($lastnumber_po >= 100){
           $akhirnomor_po = '0' . $lastnumber_po;
        }

        $no_po = $akhirnomor_po.'/'. 'FA' . '/' . 'PO' .'/'. $bln . '/' . $year_pr;

        $tambah_nopo = new PONumberMSP();
        $tambah_nopo->no = $lastnumber_po;
        $tambah_nopo->no_po = $no_po;
        $tambah_nopo->month = $bln;
        $tambah_nopo->position = 'FA';
        $tambah_nopo->type_of_letter = 'PO';
        $tambah_nopo->date = date("Y-m-d");
        $tambah_nopo->to = $request['to_agen'];
        $tambah_nopo->attention = $request['attention'];
        $tambah_nopo->project = $request['project'];
        $tambah_nopo->from = Auth::User()->nik;
        $tambah_nopo->project_id = $request['project_id'];
        $tambah_nopo->save();


// Penomoran PR
        if($getnumber == NULL){
            $getlastnumber = 1;
            $lastnumber = $getlastnumber;
        } else{
            $lastnumber = $getnumber->no+1;
        }

        if($lastnumber < 10){
           $akhirnomor = '000' . $lastnumber;
        }elseif($lastnumber > 9 && $lastnumber < 100){
           $akhirnomor = '00' . $lastnumber;
        }elseif($lastnumber >= 100){
           $akhirnomor = '0' . $lastnumber;
        }

        $no_pr = $akhirnomor.'/'. 'MSP' . '/' . $bln .'/'. $year_pr;

        $lastnopo = PONumberMSP::select('no')->orderby('created_at','desc')->first();

        $tambah = new PR_MSP();
        $tambah->no = $lastnumber;
        $tambah->no_pr = $no_pr;
        $tambah->no_po = $lastnopo->no;
        $tambah->month = $bln;
        $tambah->date = date("Y-m-d");
        $tambah->to = $request['to_agen'];
        $tambah->attention = $request['attention'];
        $tambah->project = $request['project'];
        $tambah->from = Auth::User()->nik;
        $tambah->project_id = $request['project_id'];
        $tambah->result = 'T';
        $tambah->save();


//PR Asset MSP
        $terms = $request['term'];

        $lastnopr = PR_MSP::select('no')->orderby('created_at','desc')->first();
        $tambah_pam = new pam_msp();
        $tambah_pam->nik_admin     = Auth::User()->nik;
        // $tambah->date_handover = $request['date_handover'];
        $tambah_pam->no_pr         = $lastnopr->no;
        $tambah_pam->to_agen       = $request['to_agen'];
        $tambah_pam->ket_pr        = $request['ket'];
        $tambah_pam->personel      = $request['owner_pr'];
        $tambah_pam->subject       = $request['subject'];
        $tambah_pam->status        = 'NEW';
        $tambah_pam->address       = $request['address'];
        $tambah_pam->telp          = $request['telp'];
        $tambah_pam->fax           = $request['fax'];
        $tambah_pam->email         = $request['email'];
        $tambah_pam->attention     = $request['attention'];
        // $tambah_pam->project       = $request['project'];
        $tambah_pam->project_id    = $request['project_id'];
        $tambah_pam->ppn           = $request['radiobutton'];
        $tambah_pam->pph           = $request['pph'];
        $tambah_pam->terms         = nl2br($request['term']);
        $tambah_pam->save();

//PO Asset MSP
        $last_id_pam = pam_msp::select('id_pam')->orderby('created_at','desc')->first();
        $tambah_poasset = new POAssetMSP();
        $tambah_poasset->nik_admin     = Auth::User()->nik;
        // $tambah->date_handover         = $request['date_handover'];
        $tambah_poasset->no_pr         = $lastnopr->no;
        $tambah_poasset->no_po         = $lastnopo->no;
        $tambah_poasset->id_pr_asset   = $last_id_pam->id_pam;
        $tambah_poasset->to_agen       = $request['to_agen'];
        $tambah_poasset->subject       = $request['subject'];
        $tambah_poasset->status_po     = 'NEW';
        $tambah_poasset->address       = $request['address'];
        $tambah_poasset->telp          = $request['telp'];
        $tambah_poasset->fax           = $request['fax'];
        $tambah_poasset->email         = $request['email'];
        $tambah_poasset->attention     = $request['attention'];
        // $tambah_pam->project       = $request['project'];
        $tambah_poasset->project_id    = $request['project_id'];
        $tambah_poasset->save();


//Progress PR Asset
        $lastInsertedId = $tambah_pam->id_pam;
        $tambahprogress = new pam_progress_msp();
        $tambahprogress->id_pam = $lastInsertedId;
        $tambahprogress->nik = Auth::User()->nik;
        $tambahprogress->keterangan = $request['ket'];
        $tambahprogress->status = 'ADMIN';
        $tambahprogress->save();

        /*$no = $no_pr;
        $update = PR_MSP::where('no', $no)->first();
        $update->result = 'F';
        $update->update();*/

        return redirect('pr_asset_msp')->with('success', 'Create PR Asset Successfully!');
    }

    public function store_produk(Request $request)
    {
        $id_pam = $request['id_pam_set'];
        
        $produk     = $request->name_product;
        $msp_code   = $request->msp_code;
        $qty        = $request->qty;
        $unit       = $request->unit;
        $nominal    = $request->nominal;
        $ket        = $request->ket;

        if(count($produk) > count($qty))
            $count = count($qty);
        else $count = count($produk);

        for($i = 0; $i < $count; $i++){
            $data = array(
                'id_pam'  => $id_pam,
                'name_product' => $produk[$i],
                'msp_code' => $msp_code[$i],
                'qty' => $qty[$i],
                'unit' => $unit[$i],
                'nominal'   => str_replace(',', '', $nominal[$i]),
                'total_nominal' => $qty[$i] * str_replace(',', '', $nominal[$i]),
                'description'   => $ket[$i],
            );

            $insertData[] = $data;
        }
        pam_produk_msp::insert($insertData);

        $update = pam_msp::where('id_pam',$id_pam)->first();
        $update->status     = 'ADMIN';
        $update->update();

        return redirect('pr_asset_msp')->with('success', 'Add Product Successfully!');
    }

    public function update_produk(Request $request, $id_product){

        $msp_code   = $request->msp_code;
        $produk     = $request->name_product;
        $qty        = $request->qty;
        $nominal    = $request->nominal;

        if(count($produk) > count($qty))
            $count = count($qty);
        else $count = count($produk);

        for($i = 0; $i < $count; $i++){
            $data = array(
                'msp_code'  => $msp_code[$i],
                'name_product' => $produk[$i],
                'qty' => $qty[$i],
                'nominal'   => str_replace(',', '', $nominal[$i]),
            );

            $insertData[] = $data;
        }

        DB::table('tb_pr_product_msp')->whereIn('id_product', $id_product)->update($insertData[]);

        return redirect()->back();

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     
    public function show($id)
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
    public function update(Request $request)
    {
        $id_pam = $request['id_pam_edit'];

        $update = pam_msp::where('id_pam', $id_pam)->first();
        $update->to_agen       = $request['to_agen_edit'];
        $update->ket_pr        = $request['ket_edit'];
        $update->note_pr       = $request['note_edit'];
        $update->attention     = $request['attention_edit'];
        $update->subject       = $request['subject_edit'];
        $update->project       = $request['project_edit'];
        $update->project_id    = $request['project_id_edit'];
        $update->terms         = $request['term_edit'];
        $update->update();

        return redirect('pr_asset_msp')->with('update', 'Successfully!');
        //
    }

    /*public function assign_to_hrd(Request $request){
        $id_pam = $request['assign_to_hrd_edit'];

        $total_amount = pam_produk_msp::select('total_nominal')
                ->where('id_pam',$id_pam)
                ->sum('total_nominal');

        $update = pam_msp::where('id_pam',$id_pam)->first();
        $update->status    = 'HRD';
        $update->amount    = $total_amount;
        $update->update();

        $tambah = new pam_progress_msp();
        $tambah->id_pam = $id_pam;
        $tambah->nik = Auth::User()->nik;
        $tambah->keterangan = $request['keterangan'];
        $tambah->status = 'HRD';
        $tambah->save();

        return redirect()->back();
    }*/

    public function assign_to_fnc(Request $request)
    {
        $id_pam = $request['assign_to_fnc_edit'];
        $id_po_asset = $request['id_po_asset_msp_edit'];
        // $no = $request['no_return_fnc'];
        
        $update = pam_msp::where('id_pam',$id_pam)->first();
        $update->status = 'FINANCE';
        $update->update();

        $tambah = new pam_progress_msp();
        $tambah->id_pam = $id_pam;
        $tambah->nik = Auth::User()->nik;
        $tambah->keterangan = $request['keterangan'];
        $tambah->status = 'FINANCE';
        $tambah->amount = $request['amount'];
        $tambah->save();

        $update_po = POAssetMSP::where('id_po_asset', $id_po_asset)->first();
        $update_po->status_po = 'FINANCE';
        $update_po->update();

        return redirect('pr_asset_msp')->with('success', 'Successfully!');
    }

     public function assign_to_adm(Request $request)
    {
        $id_pam = $request['assign_to_adm_edit'];

        $tambah = new pam_progress_msp();
        $tambah->id_pam = $id_pam;
        $tambah->nik = Auth::User()->nik;
        $tambah->keterangan = $request['keterangan'];
        $tambah->status = 'TRANSFER';
        $tambah->amount = $request['amount'];
        $tambah->save();

        $update = pam_msp::where('id_pam', $id_pam)->first();
        $update->status = 'TRANSFER';
        $update->update();

        return redirect('pr_asset_msp')->with('success', 'Successfully!');
    }

    public function tambah_return_hr(Request $request)
    {
        $id_pam = $request['no_return_hr'];

        $update = pam_msp::where('id_pam', $id_pam)->first();
        $update->status = 'ADMIN';
        $update->update();

        $tambah = new pam_progress_msp();
        $tambah->id_pam = $id_pam;
        $tambah->nik = Auth::User()->nik;
        $tambah->keterangan = $request['keterangan'];
        $tambah->status = 'ADMIN';
        $tambah->save();

        return redirect('pr_asset_msp')->with('success', 'Successfully!');
    }

    public function tambah_return_fnc(Request $request)
    {
        $id_pam = $request['no_return_fnc'];
        $tambah = new pam_progress_msp();
        $tambah->id_pam = $id_pam;
        $tambah->nik = Auth::User()->nik;
        $tambah->keterangan = $request['keterangan'];
        $tambah->status = 'ADMIN';
        $tambah->save();

        $update = pam_msp::where('id_pam', $id_pam)->first();
        $update->status = 'AMDIN';
        $update->update();

        return redirect('pr_asset_msp')->with('success', 'Successfully!');
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

    public function downloadPDF()
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        $datas = DB::table('tb_pam_msp')
            ->join('users','users.nik','=','tb_pam_msp.personel')
            ->join('tb_pr_msp','tb_pr_msp.no','=','tb_pam_msp.no_pr')
            ->select('tb_pam_msp.id_pam','tb_pr_msp.date','tb_pr_msp.no_pr','tb_pam_msp.ket_pr','tb_pam_msp.note_pr','tb_pam_msp.to_agen','tb_pam_msp.status','users.name','tb_pam_msp.subject','users.name','tb_pam_msp.subject', 'tb_pr_msp.no', 'tb_pr_msp.date', 'tb_pam_msp.attention', 'tb_pam_msp.project', 'tb_pam_msp.project_id', 'address', 'telp', 'fax', 'email')
            ->where('nik_admin', $nik)
            ->get();

        $produks = DB::table('tb_pam_msp')
            ->join('tb_pr_product_msp','tb_pr_product_msp.id_pam','=','tb_pam_msp.id_pam')
            ->select('tb_pr_product_msp.name_product','tb_pr_product_msp.qty','tb_pr_product_msp.id_pam','tb_pr_product_msp.nominal','tb_pr_product_msp.total_nominal', 'tb_pr_product_msp.unit', 'tb_pr_product_msp.msp_code')
            ->get();

        $total_amounts = DB::table('tb_pr_product_msp')
                    ->select('nominal')
                    ->sum('nominal');

        $total_amount = "Rp " . number_format($total_amounts,2,',','.');

        $ppns = $total_amounts * (10/100);

        $ppn   = "Rp " . number_format($ppns,2,',','.');

        $grand_total = $total_amounts + $ppns;

        $grand_total2 =  "Rp " . number_format($grand_total,2,',','.');

        $pdf = PDF::loadView('DVG.pam.pr_asset_pdf', compact('datas','produks','total_amount', 'ppn', 'grand_total2'));
        return $pdf->download('Purchase Request Asset Management'.date("d-m-Y").'.pdf');
    }

    public function downloadPDF2($id_pam)
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        $datas = DB::table('tb_pam_msp')
            ->join('users','users.nik','=','tb_pam_msp.personel')
            ->join('tb_pr_msp','tb_pr_msp.no','=','tb_pam_msp.no_pr')
            ->select('tb_pam_msp.id_pam','tb_pam_msp.date_handover','tb_pr_msp.no_pr','tb_pam_msp.ket_pr','tb_pam_msp.note_pr','tb_pam_msp.to_agen','tb_pam_msp.status','users.name','tb_pam_msp.subject', 'users.id_division', 'users.id_position','tb_pr_msp.date', 'tb_pam_msp.attention', 'tb_pam_msp.project', 'tb_pam_msp.project_id', 'tb_pam_msp.address', 'tb_pam_msp.telp', 'tb_pam_msp.fax', 'tb_pam_msp.email', 'tb_pam_msp.ppn', 'tb_pam_msp.pph', 'terms')
            ->where('nik_admin', $nik)
            ->where('tb_pam_msp.id_pam', $id_pam)
            ->first();

        $produks = DB::table('tb_pam_msp')
            ->join('tb_pr_product_msp','tb_pr_product_msp.id_pam','=','tb_pam_msp.id_pam')
            ->select('tb_pr_product_msp.name_product','tb_pr_product_msp.qty','tb_pr_product_msp.id_pam','tb_pr_product_msp.nominal','tb_pr_product_msp.total_nominal', 'tb_pr_product_msp.description', 'tb_pr_product_msp.unit', 'tb_pr_product_msp.msp_code')
            ->where('tb_pam_msp.id_pam',$id_pam)
            ->get();

        // $nominals = DB::table('tb_pr_product_msp')
        //             ->select('nominal')
        //             ->where('id_product', $id_product)
        //             ->first();

        // $nominal = "Rp " . number_format($nominals,0,'','.');

        $total_amounts = DB::table('tb_pr_product_msp')
                    ->select('total_nominal')
                    ->where('id_pam',$id_pam)
                    ->sum('total_nominal');

        $total_amount = "Rp " . number_format($total_amounts,0,'','.');

        $ppns = $total_amounts * (10/100);

        $ppn   = "Rp " . number_format($ppns,0,'','.');

        $grand_total = $total_amounts + $ppns;

        $grand_total2 =  "Rp " . number_format($grand_total,0,'','.');

        /*$pph = DB::table('tb_pam_msp')
                ->select('pph')
                ->where('id_pam', $id_pam)
                ->first();

        $pph2 = $total_amounts * $pph / (100);

        $pph3 = "Rp " . number_format($pph2,2,',','.');*/

        return view('admin_msp.pr_pdf', compact('datas','produks','total_amount', 'nominal', 'ppn', 'grand_total2'));
        // return $pdf->download('Purchase Request '.$datas->no_pr.' '.'.pdf');
    }

    public function delete_produk(Request $request){
        $hapus = pam_produk_msp::find($request->id_product);
        $hapus->delete();

        return redirect()->back();
    }

    /*public function exportExcel(Request $request)
    {
        $nama = 'Purchase Request Asset'.date('Y-m-d');
        Excel::create($nama, function ($excel) use ($request) {
        $excel->sheet('Status Pembayaran PR Intern DVG', function ($sheet) use ($request) {
        $sheet->mergeCells('A1:I1');
        $sheet->mergeCells('A3:I3');

       // $sheet->setAllBorders('thin');
        $sheet->row(1, function ($row) {
            $row->setFontFamily('Calibri');
            $row->setFontSize(11);
            $row->setAlignment('center');
            $row->setFontWeight('bold');
            $row->setValignment('center');
        });

        $sheet->row(1, array('Status Pembayaran PR Intern DVG'));

        $datas = pam_msp::join('tb_pr_msp','tb_pr_msp.no','=','tb_pam_msp.no_pr')
                    ->select('tb_pam_msp.id_pam','tb_pam_msp.date_handover','tb_pr_msp.no_pr','tb_pam_msp.to_agen','tb_pam_msp.personel','amount')
                    ->get();

        $produks = pam_produk_msp::select('name_product','qty','nominal','description')
                    ->get();


   
            $sheet->row($sheet->getHighestRow(), function ($row) {
                $row->setFontWeight('bold');
            });

             $datasheet = array();
             $datasheet[0]  =   array("No","Created Date", "No. PR", "To", "Personel", "Subject","Qty","Description",  "Amount");
             $i=1;

            foreach ($datas as $data) {
                       // $sheet->appendrow($data);
                foreach ($produks as $produk) {
                     $datasheet[$i] = array($i,
                        $data['date_handover'],
                        $data['no_pr'],
                        $data['to_agen'],
                        $data['personel'],
                        $produk['name_product'],
                        $produk['qty'],
                        $produk['description'],                        
                        $produk['nominal'],
                    );
                  $i++;
                }        
            }

            $sheet->fromArray($datasheet);
        });
        
        })->export('xls');
    }*/
}
