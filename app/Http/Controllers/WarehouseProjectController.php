<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Warehouse;
use Auth;
use App\Category_in;
use App\Type_in;
use App\User;
use App\Inventory_msp;
use App\Inventory;
use App\Detail_inventory;
use App\WarehouseProject;
use App\WarehouseProjectDetail;
use App\WarehouseProjectMSP;
use App\WarehouseProjectMSPDetail;
use App\Inventory_msp_changelog;
use App\DONumber;
use App\DOMSPNumber;
use App\projectInventory;
use DB;
use PDF;

class WarehouseProjectController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function view_do()
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

        $datas = DB::table('tb_do')
                        ->select('no','no_do','type_of_letter', 'month', 'date', 'to', 'attention', 'title', 'project', 'description','project_id')
                        ->get();

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

        return view('report/do', compact('lead', 'total_ter','notif','notifOpen','notifsd','notiftp','datas', 'notifClaim'));
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

        $data = projectInventory::join('tb_do','tb_do.no','=','inventory_project.ref')
                ->select('tb_do.no_do','inventory_project.to','inventory_project.from','inventory_project.subj','inventory_project.id_inventory_project','inventory_project.created_at')
                ->get();

        $barang = Inventory::select('id_barang','nama','note','qty')->get();

        $do_number = DONumber::select('no_do','no')->get();

        $category = category_in::select('id_category','category')->get();

        $type = type_in::select('id_type','type')->get();

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

        if ($ter != null) {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik','lead_id')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();

             $notifc = count($notif);
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $notif = DB::table('sales_lead_register')
            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->select('sales_lead_register.opp_name','sales_solution_design.nik','sales_lead_register.lead_id')
            ->where('result','OPEN')
            ->orderBy('sales_lead_register.created_at','desc')
            ->get();

             $notifc = count($notif);
        }else{
             $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik','lead_id')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();

            $notifc = count($notif);        
        }

        if ($pos == 'DIRECTOR') {
            $notifem= DB::table('users')
            ->select('name','nik')
            ->where('status_delete','D')
            ->get();
        }elseif ($div == 'TECHNICAL PRESALES') {
            $notifem= DB::table('users')
            ->select('name','nik')
            ->where('status_delete','D')
            ->get();
        }elseif (Auth::User()->id_division == '2') {
            $notifem= DB::table('users')
            ->select('name','nik')
            ->where('status_delete','D')
            ->get();
        }elseif ($ter != null) {
            $notifem= DB::table('users')
            ->select('name','nik')
            ->where('status_delete','D')
            ->get();
        }else{
            $notifem= DB::table('users')
            ->select('name','nik')
            ->where('status_delete','D')
            ->get();
        }

        return view('gudang/project/project', compact('notif','notifOpen','notifsd','notiftp','barang','notifc','notifem','category','type','do_number','data'));
    }

    public function add_project_sip(Request $request)
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        $barang = Inventory::select('inventory_produk.id_barang','id_product','inventory_produk.nama','inventory_produk.note','qty')->where('qty','!=',0)->get();

        $get_id = WarehouseProject::select('id_transaction')->orderBy('id_transaction','desc')->first();

        $cek_id    = WarehouseProject::select('id_transaction')->count('id_transaction');

        if ($cek_id > 0) {
            $getlastid = $get_id->id_transaction+1;
        }

        $do_number = DONumber::select('no_do','no')->get();

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

        if ($ter != null) {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik','lead_id')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();

             $notifc = count($notif);
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $notif = DB::table('sales_lead_register')
            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->select('sales_lead_register.opp_name','sales_solution_design.nik','sales_lead_register.lead_id')
            ->where('result','OPEN')
            ->orderBy('sales_lead_register.created_at','desc')
            ->get();

             $notifc = count($notif);
        }else{
             $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik','lead_id')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();

            $notifc = count($notif);        
        }

        if ($pos == 'DIRECTOR') {
            $notifem= DB::table('users')
            ->select('name','nik')
            ->where('status_delete','D')
            ->get();
        }elseif ($div == 'TECHNICAL PRESALES') {
            $notifem= DB::table('users')
            ->select('name','nik')
            ->where('status_delete','D')
            ->get();
        }elseif (Auth::User()->id_division == '2') {
            $notifem= DB::table('users')
            ->select('name','nik')
            ->where('status_delete','D')
            ->get();
        }elseif ($ter != null) {
            $notifem= DB::table('users')
            ->select('name','nik')
            ->where('status_delete','D')
            ->get();
        }else{
            $notifem= DB::table('users')
            ->select('name','nik')
            ->where('status_delete','D')
            ->get();
        }

        return view('gudang/project/add_project', compact('notif','notifOpen','notifsd','notiftp','barang','notifc','notifem','category','type',
            'do_number','datas','getlastid','cek_id'));
    }

    public function add_project_delivery()
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        $datas = WarehouseProjectMSP::join('tb_do_msp','tb_do_msp.no','=','inventory_delivery_msp.no_do')
                ->select('inventory_delivery_msp.to_agen','inventory_delivery_msp.from','inventory_delivery_msp.address','inventory_delivery_msp.id_transaction','inventory_delivery_msp.telp','inventory_delivery_msp.fax','inventory_delivery_msp.attn','inventory_delivery_msp.subj','inventory_delivery_msp.date','inventory_delivery_msp.id_transaction','tb_do_msp.no_do','inventory_delivery_msp.id_transaction','inventory_delivery_msp.status_kirim')
                ->get();

        $barang = Inventory_msp::select('id_barang','id_product','nama','note','qty')->where('qty','!=',0)->get();


        $do_number = DONumber::select('no_do','no')->get();

        $category = category_in::select('id_category','category')->get();

        $type = type_in::select('id_type','type')->get();

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

        if ($ter != null) {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik','lead_id')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();

             $notifc = count($notif);
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $notif = DB::table('sales_lead_register')
            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->select('sales_lead_register.opp_name','sales_solution_design.nik','sales_lead_register.lead_id')
            ->where('result','OPEN')
            ->orderBy('sales_lead_register.created_at','desc')
            ->get();

             $notifc = count($notif);
        }else{
             $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik','lead_id')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();

            $notifc = count($notif);        
        }

        if ($pos == 'DIRECTOR') {
            $notifem= DB::table('users')
            ->select('name','nik')
            ->where('status_delete','D')
            ->get();
        }elseif ($div == 'TECHNICAL PRESALES') {
            $notifem= DB::table('users')
            ->select('name','nik')
            ->where('status_delete','D')
            ->get();
        }elseif (Auth::User()->id_division == '2') {
            $notifem= DB::table('users')
            ->select('name','nik')
            ->where('status_delete','D')
            ->get();
        }elseif ($ter != null) {
            $notifem= DB::table('users')
            ->select('name','nik')
            ->where('status_delete','D')
            ->get();
        }else{
            $notifem= DB::table('users')
            ->select('name','nik')
            ->where('status_delete','D')
            ->get();
        }

        return view('gudang/project/add_project_msp', compact('notif','notifOpen','notifsd','notiftp','barang','notifc','notifem','category','type',
            'do_number','datas','barangs'));
    }

    public function inventory_index_msp(Request $request)
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position; 

        $datas = WarehouseProjectMSP::join('tb_do_msp','tb_do_msp.no','=','inventory_delivery_msp.no_do')
                ->select('inventory_delivery_msp.to_agen','inventory_delivery_msp.from','inventory_delivery_msp.address','inventory_delivery_msp.id_transaction','inventory_delivery_msp.telp','inventory_delivery_msp.fax','inventory_delivery_msp.attn','inventory_delivery_msp.subj','inventory_delivery_msp.date','inventory_delivery_msp.id_transaction','tb_do_msp.no_do','inventory_delivery_msp.id_transaction','inventory_delivery_msp.status_kirim')
                ->get();

        $barang = Inventory_msp::select('id_barang','id_product','nama','note','qty')->where('qty','!=',0)->get();

        $do_number = DONumber::select('no_do','no')->get();

        $category = category_in::select('id_category','category')->get();

        $type = type_in::select('id_type','type')->get();

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

        if ($ter != null) {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik','lead_id')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();

             $notifc = count($notif);
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $notif = DB::table('sales_lead_register')
            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->select('sales_lead_register.opp_name','sales_solution_design.nik','sales_lead_register.lead_id')
            ->where('result','OPEN')
            ->orderBy('sales_lead_register.created_at','desc')
            ->get();

             $notifc = count($notif);
        }else{
             $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik','lead_id')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();

            $notifc = count($notif);        
        }

        if ($pos == 'DIRECTOR') {
            $notifem= DB::table('users')
            ->select('name','nik')
            ->where('status_delete','D')
            ->get();
        }elseif ($div == 'TECHNICAL PRESALES') {
            $notifem= DB::table('users')
            ->select('name','nik')
            ->where('status_delete','D')
            ->get();
        }elseif (Auth::User()->id_division == '2') {
            $notifem= DB::table('users')
            ->select('name','nik')
            ->where('status_delete','D')
            ->get();
        }elseif ($ter != null) {
            $notifem= DB::table('users')
            ->select('name','nik')
            ->where('status_delete','D')
            ->get();
        }else{
            $notifem= DB::table('users')
            ->select('name','nik')
            ->where('status_delete','D')
            ->get();
        }

        return view('gudang/project/project_msp', compact('notif','notifOpen','notifsd','notiftp','barang','notifc','notifem','category','type','do_number','datas','barangs'));
    }

    public function getDropdown(Request $request)
    {
        $product = $request['product'];

        return array(DB::table('detail_inventory_produk')
                ->join('inventory_produk','inventory_produk.id_barang','=','detail_inventory_produk.id_barang')
                ->select('nama','serial_number','id_detail')
                ->where('detail_inventory_produk.status','P')
                ->where('detail_inventory_produk.id_barang',$request->product)
                ->get(),$request->product);
    }

    public function getDropdownSubmit(Request $request){
        return array(DB::table('inventory_delivery_msp_transaction')->join('inventory_produk_msp','inventory_produk_msp.id_product','=','inventory_delivery_msp_transaction.fk_id_product')->select('inventory_produk_msp.nama','inventory_produk_msp.qty')->where('inventory_delivery_msp_transaction.fk_id_product','!=',$request->product)->get(),$request->product);
    }

    public function getDetailProduk(Request $request)
    {            
        $product = $request['serial_number_produk'];

        return array(DB::table('detail_inventory_project_transaction')
                ->join('detail_inventory_produk','detail_inventory_produk.id_detail','=','detail_inventory_project_transaction.id_detail_barang')
                ->select('serial_number')
                ->where('detail_inventory_project_transaction.id_transaction',$product)
                ->get(),$request->product);
    }

    public function getQtyMsp(Request $request)
    {            
        $product = $request['product'];

        return array(DB::table('inventory_produk_msp')
                ->select('qty','unit','nama')
                ->where('id_product',$request->product)
                ->get(),$request->product);
    }


    public function Detail_inventory($id_barang)
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

        if ($ter != null) {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik','lead_id')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();

             $notifc = count($notif);
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $notif = DB::table('sales_lead_register')
            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->select('sales_lead_register.opp_name','sales_solution_design.nik','sales_lead_register.lead_id')
            ->where('result','OPEN')
            ->orderBy('sales_lead_register.created_at','desc')
            ->get();

             $notifc = count($notif);
        }else{
             $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik','lead_id')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();

            $notifc = count($notif);        
        }

        if ($pos == 'DIRECTOR') {
            $notifem= DB::table('users')
            ->select('name','nik')
            ->where('status_delete','D')
            ->get();
        }elseif ($div == 'TECHNICAL PRESALES') {
            $notifem= DB::table('users')
            ->select('name','nik')
            ->where('status_delete','D')
            ->get();
        }elseif (Auth::User()->id_division == '2') {
            $notifem= DB::table('users')
            ->select('name','nik')
            ->where('status_delete','D')
            ->get();
        }elseif ($ter != null) {
            $notifem= DB::table('users')
            ->select('name','nik')
            ->where('status_delete','D')
            ->get();
        }else{
            $notifem= DB::table('users')
            ->select('name','nik')
            ->where('status_delete','D')
            ->get();
        }

        $cek_sn = Detail_inventory::select('serial_number')->where('id_barang',$id_barang)->first();

        $cek = $cek_sn->serial_number;

        $detail = DB::table('inventory_produk')
            ->join('detail_inventory_produk','detail_inventory_produk.id_barang','=','inventory_produk.id_barang')
            ->join('inventory_category','inventory_category.id_category','=','inventory_produk.kategori')
            ->join('inventory_type','inventory_type.id_type','=','inventory_produk.tipe')
            ->select('detail_inventory_produk.id_detail','inventory_produk.nama','inventory_produk.qty','detail_inventory_produk.id_barang','inventory_category.category','inventory_type.type','inventory_produk.qty','detail_inventory_produk.note','detail_inventory_produk.serial_number')
            ->where('inventory_produk.id_barang',$id_barang)
            ->get();


        return view('gudang/detail_gudang', compact('detail','notif','notifOpen','notifsd','notiftp','notifc','notifem','cek'));
    }

    public function Detail_project(Request $request, $fk_id_inventory)
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

        if ($ter != null) {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik','lead_id')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();

             $notifc = count($notif);
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $notif = DB::table('sales_lead_register')
            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->select('sales_lead_register.opp_name','sales_solution_design.nik','sales_lead_register.lead_id')
            ->where('result','OPEN')
            ->orderBy('sales_lead_register.created_at','desc')
            ->get();

             $notifc = count($notif);
        }else{
             $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik','lead_id')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();

            $notifc = count($notif);        
        }

        if ($pos == 'DIRECTOR') {
            $notifem= DB::table('users')
            ->select('name','nik')
            ->where('status_delete','D')
            ->get();
        }elseif ($div == 'TECHNICAL PRESALES') {
            $notifem= DB::table('users')
            ->select('name','nik')
            ->where('status_delete','D')
            ->get();
        }elseif (Auth::User()->id_division == '2') {
            $notifem= DB::table('users')
            ->select('name','nik')
            ->where('status_delete','D')
            ->get();
        }elseif ($ter != null) {
            $notifem= DB::table('users')
            ->select('name','nik')
            ->where('status_delete','D')
            ->get();
        }else{
            $notifem= DB::table('users')
            ->select('name','nik')
            ->where('status_delete','D')
            ->get();
        }

        $detail  = DB::table('inventory_project_transaction')->join('inventory_produk','inventory_produk.id_barang','inventory_project_transaction.fk_id_barang')->join('inventory_project','inventory_project.id_inventory_project','=','inventory_project_transaction.fk_id_inventory')->select('inventory_project_transaction.ket','inventory_project_transaction.qty','inventory_project_transaction.vol','inventory_project_transaction.kg','inventory_produk.nama','inventory_project_transaction.ket','inventory_project_transaction.tgl_keluar','inventory_project_transaction.fk_id_barang','inventory_project_transaction.fk_id_inventory','inventory_project_transaction.id_transaction')->where('inventory_project_transaction.fk_id_inventory',$fk_id_inventory)->get(); 

        $fk_id_barang = $request['btn-details'];

        $details = WarehouseProjectDetail::join('inventory_project_transaction','inventory_project_transaction.id_transaction','=','detail_inventory_project_transaction.id_transaction')
                ->join('detail_inventory_produk','detail_inventory_produk.id_detail','=',
                'detail_inventory_project_transaction.id_detail_barang')
                ->join('inventory_produk','inventory_produk.id_barang','=','detail_inventory_produk.id_barang')
                ->select('inventory_produk.nama','detail_inventory_produk.serial_number','inventory_project_transaction.ket','inventory_project_transaction.qty','inventory_project_transaction.vol','inventory_project_transaction.kg','inventory_project_transaction.tgl_keluar')
                ->where('detail_inventory_project_transaction.fk_id_inventory',$fk_id_inventory)/*
                ->where('inventory_project_transaction.fk_id_barang',$fk_id_barang)*/
                ->get();

        return view('gudang/project/detail_project', compact('detail','details','notif','notifOpen','notifsd','notiftp','notifc','notifem','count_qty','sn'));
    }

    public function ShowDetailProduk(Request $request){
        $fk_id_barang = $request['btn-details'];

        return array(DB::table('detail_inventory_project_transaction')
            ->join('inventory_project_transaction','inventory_project_transaction.id_transaction','=','detail_inventory_project_transaction.id_transaction')
            ->join('detail_inventory_produk','detail_inventory_produk.id_detail','=',
            'detail_inventory_project_transaction.id_detail_barang')
            ->join('inventory_produk','inventory_produk.id_barang','=','detail_inventory_produk.id_barang')
            ->select('detail_inventory_produk.serial_number')
            ->where('detail_inventory_project_transaction.id_transaction',$request->product)
            ->get(),$request->product);
    }

    public function Detail_do_msp(Request $request, $id_transaction)
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

        if ($ter != null) {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik','lead_id')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();

             $notifc = count($notif);
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $notif = DB::table('sales_lead_register')
            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->select('sales_lead_register.opp_name','sales_solution_design.nik','sales_lead_register.lead_id')
            ->where('result','OPEN')
            ->orderBy('sales_lead_register.created_at','desc')
            ->get();

             $notifc = count($notif);
        }else{
             $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik','lead_id')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();

            $notifc = count($notif);        
        }

        if ($pos == 'DIRECTOR') {
            $notifem= DB::table('users')
            ->select('name','nik')
            ->where('status_delete','D')
            ->get();
        }elseif ($div == 'TECHNICAL PRESALES') {
            $notifem= DB::table('users')
            ->select('name','nik')
            ->where('status_delete','D')
            ->get();
        }elseif (Auth::User()->id_division == '2') {
            $notifem= DB::table('users')
            ->select('name','nik')
            ->where('status_delete','D')
            ->get();
        }elseif ($ter != null) {
            $notifem= DB::table('users')
            ->select('name','nik')
            ->where('status_delete','D')
            ->get();
        }else{
            $notifem= DB::table('users')
            ->select('name','nik')
            ->where('status_delete','D')
            ->get();
        }

        $to = WarehouseProjectMSP::select('to_agen','from')->where('id_transaction',$id_transaction)->first();

        $detail = WarehouseProjectMSPDetail::join('inventory_delivery_msp','inventory_delivery_msp.id_transaction','=',
                'inventory_delivery_msp_transaction.id_transaction')
                ->join('inventory_produk_msp','inventory_produk_msp.id_product','=','inventory_delivery_msp_transaction.fk_id_product')
                ->select('inventory_produk_msp.nama','inventory_produk_msp.kode_barang','inventory_delivery_msp_transaction.created_at','inventory_produk_msp.id_po','inventory_delivery_msp_transaction.note','inventory_delivery_msp_transaction.qty_transac','inventory_delivery_msp_transaction.unit','inventory_delivery_msp.to_agen','inventory_produk_msp.id_product','inventory_delivery_msp.to_agen','inventory_delivery_msp.id_transaction','inventory_delivery_msp_transaction.id_detail_do_msp','inventory_produk_msp.qty')
                ->where('inventory_delivery_msp_transaction.id_transaction',$id_transaction)
                ->get();

        $details = WarehouseProjectMSPDetail::join('inventory_delivery_msp','inventory_delivery_msp.id_transaction','=',
                'inventory_delivery_msp_transaction.id_transaction')
                ->join('inventory_produk_msp','inventory_produk_msp.id_product','=','inventory_delivery_msp_transaction.fk_id_product')
                ->join('tb_do_msp','tb_do_msp.no','=','inventory_delivery_msp.no_do')
                ->select('inventory_produk_msp.nama','inventory_produk_msp.kode_barang','inventory_delivery_msp_transaction.created_at','inventory_produk_msp.id_po','inventory_delivery_msp_transaction.note','inventory_delivery_msp_transaction.qty_transac','inventory_delivery_msp_transaction.unit','inventory_delivery_msp.to_agen','inventory_produk_msp.id_product','inventory_delivery_msp.to_agen','inventory_delivery_msp.id_transaction','inventory_delivery_msp_transaction.id_detail_do_msp','tb_do_msp.no_do')
                ->where('inventory_delivery_msp_transaction.id_transaction',$id_transaction)
                ->first();

        $cek_product = WarehouseProjectMSPDetail::where('id_transaction',$id_transaction)->count('id_transaction');

        $barang_transaction = WarehouseProjectMSPDetail::join('inventory_produk_msp','inventory_produk_msp.id_product','=','inventory_delivery_msp_transaction.fk_id_product')->select('fk_id_product')->where('id_transaction',$id_transaction)->get();

        $barang_transaction->toArray('fk_id_product');


       /* $ids = array_map(function($arr) {
                   return $arr['fk_id_product'];
                }, $barang_transaction);*/
/*
        $ids = array();
        foreach ($barang_transaction as $data) {
            
            $ids = $data->fk_id_product;

        }*/

        /*$my_arrays = [[1, 2],[3, 4]];
        foreach($my_arrays as $array) {
          foreach($array as $a) {
            $ids .= $a." " ;
          }

        }

        echo $ids;*/

        


        /*foreach ($barang_transaction as $key => $value) {
            $datas[] = $value->fk_id_product;
        }
*/
        $datak = $request['fk_id_product'];

    /*    $message = "kk,ll";
        $myArray = explode(',', $message);
        print_r ($myArray);
        foreach ($myArray as $value) {   
            echo "$value <br>";
            $array[] = $value;
        }
        print_r ($array);*/

      /*  $datam = $barang_transaction->fk_id_product;

        $datak = implode(", ", array_map('intval', $datam));*/

        $barang = Inventory_msp::select('id_product','id_barang','nama','note','qty')->where('qty','!=',0)->whereNotIn('id_product',function($query) use ($id_transaction) {
            $query->select('fk_id_product')->where('id_transaction',$id_transaction)->from('inventory_delivery_msp_transaction');
        })->get();

        return view('gudang/project/detail_project_msp', compact('array','cek_product','datas','datak','to','detail','details','barang','barang_transaction','notif','notifOpen','notifsd','notiftp','notifc','notifem','ids','result'));
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
    public function store_delivery_msp(Request $request)
    {    
    
        $type = 'SJ';
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

        $getnumber = DOMSPNumber::orderBy('no', 'desc')->first();

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

        $no = $akhirnomor .'/'. $type .'/' . $bln .'/'. $year_pr;
        
        $store = new DOMSPNumber();
        $store->no              = $lastnumber;
        $store->no_do           = $no;
        $store->type_of_letter  = $type;
        $store->month           = $bln;
        $store->date            = date("Y-m-d H:i:s");
        $store->to              = $request['to_agen'];
        $store->attention       = $request['att'];
        $store->project_id      = $request['id_project'];
        $store->save();

        $no_do = DOMSPNumber::select('no','no_do')->orderBy('created_at','desc')->first();

        $tambah = new WarehouseProjectMSP();
        $tambah->to_agen        = $request['to_agen'];
        $tambah->address        = $request['add'];
        $tambah->telp           = $request['telp'];
        $tambah->fax            = $request['fax'];
        $tambah->attn           = $request['att'];
        $tambah->from           = $request['from'];
        $tambah->subj           = $request['subj'];
        $tambah->date           = date("Y-m-d H:i:s");
        $tambah->no_do          = $no_do->no;
        $tambah->id_project     = $request['id_project'];
        $tambah->save();

        $lastInsertedId = WarehouseProjectMSP::select('id_transaction')->orderBy('created_at','desc')->first();
        $produk         = $_POST['product'];
        $qty            = $_POST['qty'];
        $unit           = $_POST['unit'];
        $kg             = $_POST['kg'];
        $vol            = $_POST['vol'];
        $note           = $_POST['information'];

        if(count($produk) > count($qty))
            $count = count($qty);
        else $count = count($produk);

        for($i = 0; $i < $count; $i++){
            $data = array(
                'id_transaction' => $lastInsertedId->id_transaction,
                'fk_id_product'  => $produk[$i],
                'qty_transac'    => $qty[$i],
                'unit'           => $unit[$i],
                'kg'             => $kg[$i],
                'vol'            => $vol[$i],
                'note'           => $note[$i],
            );
            $insertData[] = $data;

            $datas = array(
                'qty'           => $qty[$i],
                'id_product'    => $produk[$i],
                'note'          => $no_do->no_do,
                'status'        => 'D',
            );
            $insertDatas[] = $datas;
        };
        WarehouseProjectMSPDetail::insert($insertData);
        Inventory_msp_changelog::insert($insertDatas);

        foreach ($produk as $produk) {
            $qty_awal = inventory_msp::select('qty')->where('id_product',$produk)->get();
            $qty_akhir = WarehouseProjectMSPDetail::select('qty_transac')->where('fk_id_product',$produk)->orderBy('created_at','asc')->get();
            $update2 = Inventory_msp::where('id_product', $produk)->first();
            foreach ($qty_awal as $qty_awal) {
                foreach ($qty_akhir as $qty_akhir ) {
                    $update2->qty = $qty_awal->qty - $qty_akhir->qty; 
                }
                        
            }
            $update2->update();
        } 


      

        return redirect('/inventory/do/msp')->with('success', 'Created Delivery Order Successfully!');
    }

    public function store_product_do_msp(Request $request)
    {
        $no_do          = $request['no_do_edit'];
        $id_transac     = $request['id_transaction_product'];
        $produk         = $_POST['product'];
        $qty            = $_POST['qty'];
        $unit           = $_POST['unit'];
        $kg             = $_POST['kg'];
        $vol            = $_POST['vol'];
        $note           = $_POST['information'];


        if(count($produk) > count($qty))
            $count = count($qty);
        else $count = count($produk);

        for($i = 0; $i < $count; $i++){
            /*if($qty_compare->qty != NULL) {
                $datam = array(
                    'qty'            => $qty_compare->qty + $qty[$i],
                );
                $updateData[] = $datam;

                $datak = array(
                    'qty'           => $qty[$i],
                    'id_product'    => $produk[$i],
                    'note'          => $no_do,
                    'status'        => 'D',
                );
                $insertDatak[] = $datak;

                WarehouseProjectMSPDetail::where('fk_id_product',$produk)->update($datam);
                Inventory_msp_changelog::insert($insertDatak);

            } else {*/
                $data = array(
                    'id_transaction' => $id_transac,
                    'fk_id_product'  => $produk[$i],
                    'qty_transac'    => $qty[$i],
                    'unit'           => $unit[$i],
                    'kg'             => $kg[$i],
                    'vol'            => $vol[$i],
                    'note'           => $note[$i],
                );
                $insertData[] = $data;

                $datas = array(
                    'qty'           => $qty[$i],
                    'id_product'    => $produk[$i],
                    'note'          => $no_do,
                    'status'        => 'D',
                );
                $insertDatas[] = $datas;

                WarehouseProjectMSPDetail::insert($insertData);
                Inventory_msp_changelog::insert($insertDatas);
            
        };
        

        foreach ($produk as $produk) {
            $qty_awal = inventory_msp::select('qty')->where('id_product',$produk)->get();
            $qty_akhir = WarehouseProjectMSPDetail::select('qty_transac')->where('fk_id_product',$produk)->orderBy('created_at','asc')->get();
            $update2 = Inventory_msp::where('id_product', $produk)->first();
            foreach ($qty_awal as $qty_awal) {
                foreach ($qty_akhir as $qty_akhir ) {
                    $update2->qty = $qty_awal->qty - $qty_akhir->qty; 
                }
                        
            }
            $update2->update();
        } 

    return redirect()->back()->with('update', 'Updated Delivery Order Successfully!');

    }

    public function return_do_product_msp(Request $request)
    {
        $qtys           = $request['qty_before'];
        $id_product     = $request['id_product_edit'];
        $qtyd           = $request['qty_back'];
        $id_transac     = $request['id_transaction_edit'];
        $id_detail_do   = $request['id_detail_do_edit'];

        $qty_2 = Inventory_msp::select('qty')->where('id_product',$id_product)->first();

        $no_do = WarehouseProjectMSPDetail::join('inventory_delivery_msp','inventory_delivery_msp.id_transaction','=','inventory_delivery_msp_transaction.id_transaction')
                    ->join('tb_do_msp','tb_do_msp.no','=','inventory_delivery_msp.no_do')
                    ->select('tb_do_msp.no_do')
                    ->where('inventory_delivery_msp_transaction.id_transaction',$id_transac)
                    ->first();

          $update_qty2            = WarehouseProjectMSPDetail::where('id_detail_do_msp',$id_detail_do)->first();
          $update_qty2->qty_transac       = $qtys - $qtyd;
          $update_qty2->update();

          $update_qty            = Inventory_msp::where('id_product',$id_product)->first();
          $update_qty->qty       = $qty_2->qty + $qtyd;
          $update_qty->update();

          $tambah_log               = new Inventory_msp_changelog();
          $tambah_log->qty          = $qtyd;
          $tambah_log->id_product   = $id_product;
          $tambah_log->note         = $no_do->no_do;
          $tambah_log->status       = 'P';
          $tambah_log->save();

          return redirect()->back()->with('update', 'Return Product Delivery Order Successfully!');

    }

    public function edit_qty_do(Request $request)
    {
        $qtys           = $request['qty_produk'];
        $id_product     = $request['id_product_edit'];
        $qtyd           = $request['qty_edit_clone'];
        $id_transac     = $request['id_transaction_edit'];
        $id_detail_do   = $request['id_detail_do_edit'];

        $qty_2 = Inventory_msp::select('qty','id_po')->where('id_product',$id_product)->first();

        $qty_3 = WarehouseProjectMSPDetail::select('qty_transac')->where('id_detail_do_msp',$id_detail_do)->first();

        $update_qty2                 = WarehouseProjectMSPDetail::where('id_detail_do_msp',$id_detail_do)->first();
        $update_qty2->qty_transac    = $qty_3->qty_transac + $qtyd;
        $update_qty2->update();

        $update_qty            = Inventory_msp::where('id_product',$id_product)->first();
        $update_qty->qty       = $qty_2->qty - $qtyd;
        $update_qty->update();

        $tambah_log               = new Inventory_msp_changelog();
        $tambah_log->qty          = $qtyd;
        $tambah_log->id_product   = $id_product;
        $tambah_log->note         = $qty_2->id_po;
        $tambah_log->status       = 'D';
        $tambah_log->save();

        return redirect()->back()->with('update', 'Updated Delivery Order Successfully!');
    }

    public function store_delivery_sip(Request $request)
    {
        $type = 'SJ';
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

        $getnumber = DONumber::orderBy('no', 'desc')->first();

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

        $no = $akhirnomor .'/'. $type .'/' . $bln .'/'. $year_pr;
        
        $store                  = new DONumber();
        $store->no              = $lastnumber;
        $store->no_do           = $no;
        $store->type_of_letter  = $type;
        $store->month           = $bln;
        $store->date            = date("Y-m-d H:i:s");
        $store->to              = $request['to_agen'];
        $store->attention       = $request['att'];
        $store->project_id      = $request['id_project'];
        $store->title           = $request['title'];
        $store->project         = $request['project'];
        $store->description     = $request['description'];
        $store->save();

        $no_do = DONumber::select('no','no_do')->orderBy('created_at','desc')->first();

        $tambah                 = new projectInventory();
        $tambah->to             = $request['to_agen'];
        $tambah->address        = $request['add'];
        $tambah->telp           = $request['telp'];
        $tambah->fax            = $request['fax'];
        $tambah->att            = $request['att'];
        $tambah->from           = $request['from'];
        $tambah->subj           = $request['subj'];
        $tambah->date           = date("Y-m-d H:i:s");
        $tambah->ref            = $no_do->no;
        $tambah->id_project     = $request['id_project'];
        $tambah->save();

        $produb = $_POST['product'];
        $id_transac = $_POST['id_transac'];

        $produks = $_POST['detail_product'];

        $ket    = $request->information;
        $kg     = $request->kg;
        $vol    = $request->vol;

        $lastInsertedIdinv = $tambah->id_inventory_project;

        if(count($ket) > count($produb))
        $count = count($produb);
        else $count = count($ket);

        for($i = 0; $i < $count; $i++){
            $data = array(
                'id_transaction'      => $id_transac[$i],
                'fk_id_barang'        => $produb[$i],
                'fk_id_inventory'     => $lastInsertedIdinv,
                'tgl_keluar'          => date("Y-m-d H:i:s"),
                'vol'                 => $vol[$i],
                'kg'                  => $kg[$i],
                'ket'                 => $ket[$i],
                'qty'                 => count($produks[$i]), 
                'no_do'               => $no_do->no,
            );

            $insertData[] = $data;
        }
           /* $tambah_wp = new WarehouseProject();
            $tambah_wp->fk_id_barang         = $produs;
            $tambah_wp->fk_id_inventory      = $lastInsertedIdinv;
            $tambah_wp->tgl_keluar           = date("Y-m-d H:i:s");
            $tambah_wp->no_do                = $no_do->no;
            $tambah_wp->save(); */
        WarehouseProject::insert($insertData);

       /* $lastInsertedId = WarehouseProject::select('id_transaction')->where('fk_id_inventory',$lastInsertedIdinv)->orderBy('created_at','desc')->get();*/

        // $arr = @explode(",", trim($_POST['id_transac_b']));
        // $arr = $_POST['id_transac_b'];

        // $arr_length = count($produb); 
        // for($i=0; $i<$arr_length;$i++) 
        // {
        //     $arr2 = @explode(",",$arr[$i][0]);
        //     for ($j=0; $j <count($arr2) ; $j++) { 
        //         $datas = array(
        //             'id_transaction'      => $arr2[$j],
        //             'id_detail_barang'    => $produks[$i][$j],
        //             'fk_id_inventory'     => $lastInsertedIdinv,
        //         );

        //         $insertDatas[] = $datas;
        //     }
        // }

        // $arr_length = count($produb); 
        for($i=0; $i<count($produks);$i++) 
        {
            for ($j=0; $j <count($produks[$i]) ; $j++) { 
                $datas = array(
                    'id_transaction'      => $id_transac[$i],
                    'id_detail_barang'    => $produks[$i][$j],
                    'fk_id_inventory'     => $lastInsertedIdinv,
                );

                $insertDatas[] = $datas;
            }
        }
        DB::table('detail_inventory_project_transaction')->insert($insertDatas);

        $id_barang = $request['product'];

        $qty_awal = Inventory::select('qty')
                    ->where('id_barang',$id_barang)
                    ->first();
        $qty_transac = WarehouseProject::select('qty')
                    ->where('fk_id_barang',$id_barang)
                    ->first();
        
        $update = Inventory::where('id_barang',$id_barang)->first();
        $update->qty  = $qty_awal->qty - $qty_transac->qty;
        $update->update();

        foreach ($produks as $produk) {
            $update2 = Detail_inventory::where('id_detail', $produk)->first();
            $update2->status = 'PROJECT';
            $update2->note   = $no_do->no_do;
            $update2->update();
        }
        

        return redirect('/inventory/project')->with('success', 'Created Inventory Project Successfully!');
        
    }

    public function project_store(Request $request)
    {
        $type = 'SJ';
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

        $getnumber = DONumber::orderBy('no', 'desc')->first();

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

        $no = $akhirnomor .'/'. $type .'/' . $bln .'/'. $year_pr;
        
        $store = new DONumber();
        $store->no              = $lastnumber;
        $store->no_do           = $no;
        $store->type_of_letter  = $type;
        $store->month           = $bln;
        $store->date            = date("Y-m-d H:i:s");
        $store->to              = $request['to_agen'];
        $store->attention       = $request['att'];
        $store->project_id      = $request['id_project'];
        $store->title           = $request['title'];
        $store->project         = $request['project'];
        $store->description     = $request['description'];
        $store->save();

        $no_do = DONumber::select('no','no_do')->orderBy('created_at','desc')->first();

        $tambah = new projectInventory();
        $tambah->to        = $request['to_agen'];
        $tambah->address        = $request['add'];
        $tambah->telp           = $request['telp'];
        $tambah->fax            = $request['fax'];
        $tambah->att            = $request['att'];
        $tambah->from           = $request['from'];
        $tambah->subj           = $request['subj'];
        $tambah->date           = date("Y-m-d H:i:s");
        $tambah->ref            = $no_do->no;
        $tambah->id_project     = $request['id_project'];
        $tambah->save();

        $produks = $request['detail_product'];

        $inputs  = $request->all();

        $tambah_wp = new WarehouseProject();
        $tambah_wp->fk_id_barang         = $request['product'];
        $tambah_wp->tgl_keluar           = date("Y-m-d H:i:s");
        $tambah_wp->no_do                = $no_do;
        $tambah_wp->qty                  = count($produks);
        $tambah_wp->save();


       /* $lastInsertedId = $tambah->id_transaction;

        $data = [];
        foreach ($produks as $produk) {
            $data[] = [
                'id_transaction'      => $lastInsertedId, 
                'id_detail_barang'    => $produk,
            ];
        }

        DB::table('detail_inventory_project_transaction')->insert($data);       


        $id_barang = $request['product'];

        $qty_awal = Inventory::select('qty')
                    ->where('id_barang',$id_barang)
                    ->first();
        $qty_transac = WarehouseProject::select('qty_awal')
                    ->where('fk_id_barang',$id_barang)
                    ->first();
        
        $update = Inventory::where('id_barang',$id_barang)->first();
        $update->qty  = $qty_awal->qty - $qty_transac->qty_awal;
        $update->update();

        foreach ($produks as $produk) {
            $update2 = Detail_inventory::where('id_detail', $produk)->first();
            $update2->status = 'PROJECT';
            $update2->update();
        }*/

        return redirect('/inventory/project')->with('success', 'Created Inventory Project Successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
       
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update_warehouse(Request $request)
    {
        $item_code = $request['edit_item_code_before'];

        $update = Warehouse::where('item_code',$item_code)->first();
        $update->name_item = $request['edit_name'];
        $update->quantity  = $request['edit_quantity']; 
        $update->information = $request['edit_information'];

        $update->update();

        return redirect()->back();
    }

    public function update_serial_number(Request $request)
    {
        $id = $request['id_detail_edit'];
        $update = Detail_inventory::where('id_detail',$id)->first();
        $update->serial_number = $request['edit_serial_number'];
        
        $update->note = $request['note'];

        $update->update();

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($item_code)
    {
        $hapus = Warehouse::find($item_code);
        $hapus->delete();

        return redirect()->back()->with('alert', 'Deleted!');
    }

    public function downloadPdfDO($id_transaction)
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        $datas = WarehouseProjectMSP::join('tb_do_msp','tb_do_msp.no','=','inventory_delivery_msp.no_do')
                ->select('inventory_delivery_msp.to_agen','inventory_delivery_msp.from','inventory_delivery_msp.address','inventory_delivery_msp.id_transaction','inventory_delivery_msp.telp','inventory_delivery_msp.fax','inventory_delivery_msp.attn','inventory_delivery_msp.subj','inventory_delivery_msp.date','inventory_delivery_msp.id_transaction','tb_do_msp.no_do','inventory_delivery_msp.id_transaction')
                ->where('id_transaction', $id_transaction)
                ->first();

        $produks = WarehouseProjectMSPDetail::join('inventory_delivery_msp','inventory_delivery_msp.id_transaction','=',
                'inventory_delivery_msp_transaction.id_transaction')
                ->join('inventory_produk_msp','inventory_produk_msp.id_product','=','inventory_delivery_msp_transaction.fk_id_product')
                ->join('tb_pr_product_msp', 'tb_pr_product_msp.id_product', '=', 'inventory_delivery_msp_transaction.fk_id_product')
                ->select('inventory_produk_msp.nama','inventory_produk_msp.kode_barang','inventory_delivery_msp_transaction.created_at','inventory_produk_msp.id_po','inventory_delivery_msp_transaction.note','inventory_delivery_msp_transaction.qty_transac','inventory_delivery_msp_transaction.unit','inventory_delivery_msp.to_agen','inventory_delivery_msp_transaction.kg','inventory_delivery_msp_transaction.vol','inventory_produk_msp.id_product', 'inventory_delivery_msp.id_project', 'tb_pr_product_msp.description')
                ->where('inventory_delivery_msp_transaction.id_transaction',$id_transaction)
                ->get();

        $pdf = PDF::loadView('gudang.project.do_pdf', compact('datas','produks'));
        return $pdf->download('Delivery Order '.$datas->no_do.' '.'.pdf');
    }

    public function downloadPdfDOSIP($fk_id_inventory)
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        $datas = DB::table('inventory_project')->join('inventory_project_transaction','inventory_project_transaction.fk_id_inventory','=','inventory_project.id_inventory_project')
                ->join('tb_do','tb_do.no','=','inventory_project_transaction.no_do')
                ->select('inventory_project.to','inventory_project.from','inventory_project.address','inventory_project.telp','inventory_project.fax','inventory_project.att','inventory_project.id_project','inventory_project.subj','inventory_project.date','inventory_project_transaction.id_transaction','tb_do.no_do')
                ->where('id_inventory_project', $fk_id_inventory)
                ->first();

        $produk = WarehouseProjectDetail::join('inventory_project_transaction','inventory_project_transaction.id_transaction','=',
                'detail_inventory_project_transaction.id_transaction')
                ->join('detail_inventory_produk','detail_inventory_produk.id_detail','=',
                'detail_inventory_project_transaction.id_detail_barang')
                ->join('inventory_produk','inventory_produk.id_barang','=','detail_inventory_produk.id_barang')
                ->select('inventory_produk.nama','detail_inventory_produk.serial_number','inventory_project_transaction.tgl_keluar','detail_inventory_project_transaction.description','inventory_project_transaction.qty')
                ->where('detail_inventory_project_transaction.fk_id_inventory',$fk_id_inventory)
                ->get();

        $produks = WarehouseProjectDetail::join('inventory_project_transaction','inventory_project_transaction.id_transaction','=',
                'detail_inventory_project_transaction.id_transaction')
                ->join('detail_inventory_produk','detail_inventory_produk.id_detail','=',
                'detail_inventory_project_transaction.id_detail_barang')
                ->join('inventory_produk','inventory_produk.id_barang','=','detail_inventory_produk.id_barang')
                ->select('inventory_produk.nama','detail_inventory_produk.serial_number','inventory_project_transaction.tgl_keluar','detail_inventory_project_transaction.description','inventory_project_transaction.qty')
                ->where('detail_inventory_project_transaction.fk_id_inventory',$fk_id_inventory)
                ->first();

        return View('gudang.project.do_sip_pdf', compact('datas','produks','produk'));
        /*$pdf = PDF::loadView('gudang.project.do_sip_pdf', compact('datas','produks','produk'));
        return $pdf->download('Delivery Order '.$datas->no_do.' '.'.pdf');*/
    }
}
