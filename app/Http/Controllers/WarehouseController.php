<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Warehouse;
use Auth;
// use App\Category_in;
// use App\Type_in;
use App\User;
use App\Inventory;
use App\Detail_inventory;
use App\PONumber;
use App\PONumberMSP;
use App\ChangelogInventory;
use App\Inventory_msp;
use App\pam_produk_msp;
use App\pam_msp;
use App\POAssetMSP;
use App\POAsset;
use App\Inventory_msp_changelog;
use App\WarehouseDetailProduk;
use App\pamProduk;
use DB;

class WarehouseController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function view_inventory(request $request)
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

        $datam = DB::table('detail_inventory_produk')
                ->join('inventory_produk','inventory_produk.id_barang','=','detail_inventory_produk.id_barang')
                        ->select('inventory_produk.nama','detail_inventory_produk.serial_number','detail_inventory_produk.tgl_masuk','detail_inventory_produk.note')
                        ->where('detail_inventory_produk.status','P')
                        ->get();

        $datas = DB::table('detail_inventory_produk')
                ->join('inventory_produk','inventory_produk.id_barang','=','detail_inventory_produk.id_barang')
                ->join('detail_inventory_project_transaction','detail_inventory_project_transaction.id_detail_barang','=','detail_inventory_produk.id_detail')
                        ->select('inventory_produk.nama','detail_inventory_produk.serial_number','detail_inventory_project_transaction.created_at','detail_inventory_produk.note')
                        ->where('detail_inventory_produk.status','PROJECT')
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

        return view('report/inventory', compact('lead', 'total_ter','notif','notifOpen','notifsd','notiftp','datas','datam', 'notifClaim'));
    }
    
    public function category_index()
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position; 

        $data = DB::table('tb_warehouse')
                        ->select('item_code','name_item', 'quantity', 'information')
                        ->get();

        // $category = category_in::select('id_category','category')->get();

        // $type = type_in::select('id_type','type')->get();

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
            $notifem = DB::table('users')
            ->select('name','nik')
            ->where('status_delete','D')
            ->get();
        }elseif ($div == 'TECHNICAL PRESALES') {
            $notifem = DB::table('users')
            ->select('name','nik')
            ->where('status_delete','D')
            ->get();
        }elseif (Auth::User()->id_division == '2') {
            $notifem = DB::table('users')
            ->select('name','nik')
            ->where('status_delete','D')
            ->get();
        }elseif ($ter != null) {
            $notifem = DB::table('users')
            ->select('name','nik')
            ->where('status_delete','D')
            ->get();
        }else{
            $notifem = DB::table('users')
            ->select('name','nik')
            ->where('status_delete','D')
            ->get();
        }

        return view('gudang/kategori', compact('notif','notifOpen','notifsd','notiftp','data','notifc','notifem','category','type'));
    }

    public function inventory_index(Request $request)
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position; 

        $data = DB::table('inventory_produk')
                ->join('tb_po','tb_po.no','=','inventory_produk.id_po')
                ->select('nama','kategori', 'tipe', 'qty','qty_status','inventory_produk.note','inventory_produk.id_barang','inventory_produk.id_product','status','inventory_produk.id_po','tb_po.no_po')
                ->get();

        // $category = category_in::select('id_category','category')->where('id_category','!=','5')->get();

        $po = POAsset::join('tb_pr','tb_pr.no','=','tb_po_asset.no_pr')
            ->join('tb_po','tb_po.no','=','tb_po_asset.no_po')
            ->join('dvg_pam','dvg_pam.no_pr','=','tb_pr.no')
            ->select('tb_po.no','tb_po.no_po','dvg_pam.id_pam')
            ->select('tb_po.no','tb_po.no_po','dvg_pam.id_pam')
            ->where('tb_po_asset.status_po','PENDING')
            ->orWhere('tb_po_asset.status_po','FINANCE')->get();

        // $type = type_in::select('id_type','type')->where('id_type','!=','8')->get();

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
            $notifem = DB::table('users')
            ->select('name','nik')
            ->where('status_delete','D')
            ->get();
        }elseif ($div == 'TECHNICAL PRESALES') {
            $notifem = DB::table('users')
            ->select('name','nik')
            ->where('status_delete','D')
            ->get();
        }elseif (Auth::User()->id_division == '2') {
            $notifem = DB::table('users')
            ->select('name','nik')
            ->where('status_delete','D')
            ->get();
        }elseif ($ter != null) {
            $notifem = DB::table('users')
            ->select('name','nik')
            ->where('status_delete','D')
            ->get();
        }else{
            $notifem = DB::table('users')
            ->select('name','nik')
            ->where('status_delete','D')
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

        return view('gudang/gudang', compact('notifClaim','notif','notifOpen','notifsd','notiftp','data','notifc','notifem','category','type','po','datas'));
    }

    public function inventory_msp()
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position; 

        $data = DB::table('inventory_produk_msp')
                ->select('nama', 'qty','inventory_produk_msp.note','inventory_produk_msp.id_product','inventory_produk_msp.id_barang','status','inventory_produk_msp.id_po','inventory_produk_msp.kode_barang','status2')
                ->get();

        $datas = POAssetMSP::join('tb_pr_msp','tb_pr_msp.no','=','tb_po_asset_msp.no_pr')
                ->join('tb_po_msp','tb_po_msp.no','=','tb_po_asset_msp.no_po')
                ->join('tb_pam_msp','tb_pam_msp.no_pr','=','tb_pr_msp.no')
                ->select('tb_po_msp.no','tb_po_msp.no_po','tb_pam_msp.id_pam')
                ->where('tb_po_asset_msp.status_po','PENDING')
                ->orWhere('tb_po_asset_msp.status_po','FINANCE')
                ->get();

        // $category = category_in::select('id_category','category')->where('id_category','!=','5')->get();

        $po = PONumber::select('no','no_po')->get();

        // $type = type_in::select('id_type','type')->where('id_type','!=','8')->get();

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

        // if ($pos == 'DIRECTOR') {
        //     $notifem = DB::table('users')
        //     ->select('name','nik')
        //     ->where('status_delete','D')
        //     ->get();
        // }elseif ($div == 'TECHNICAL PRESALES') {
        //     $notifem = DB::table('users')
        //     ->select('name','nik')
        //     ->where('status_delete','D')
        //     ->get();
        // }elseif (Auth::User()->id_division == '2') {
        //     $notifem = DB::table('users')
        //     ->select('name','nik')
        //     ->where('status_delete','D')
        //     ->get();
        // }elseif ($ter != null) {
        //     $notifem = DB::table('users')
        //     ->select('name','nik')
        //     ->where('status_delete','D')
        //     ->get();
        // }else{
        //     $notifem = DB::table('users')
        //     ->select('name','nik')
        //     ->where('status_delete','D')
        //     ->get();
        // }

        return view('gudang/gudang_msp', compact('notif','notifOpen','notifsd','notiftp','data','notifc','notifem','category','type','po','datas'));
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
            $notifem = DB::table('users')
            ->select('name','nik')
            ->where('status_delete','D')
            ->get();
        }elseif ($div == 'TECHNICAL PRESALES') {
            $notifem = DB::table('users')
            ->select('name','nik')
            ->where('status_delete','D')
            ->get();
        }elseif (Auth::User()->id_division == '2') {
            $notifem = DB::table('users')
            ->select('name','nik')
            ->where('status_delete','D')
            ->get();
        }elseif ($ter != null) {
            $notifem = DB::table('users')
            ->select('name','nik')
            ->where('status_delete','D')
            ->get();
        }else{
            $notifem = DB::table('users')
            ->select('name','nik')
            ->where('status_delete','D')
            ->get();
        }

        $cek_sn = Detail_inventory::select('serial_number')->where('id_barang',$id_barang)->where('serial_number','!=','')->count('serial_number');

        $qty_now = Inventory::select('qty','qty_status')->where('id_barang',$id_barang)->first();

        $qty_changelog = DB::table('inventory_produk')->join('inventory_change_log','inventory_change_log.id_product','=','inventory_produk.id_product')
            ->select('inventory_change_log.qty','inventory_produk.id_barang','inventory_produk.id_product')
            ->orderBy('inventory_change_log.created_at','desc')
            ->where('inventory_change_log.id_detail_barang',$id_barang)
            ->first();

        $qty_total = $qty_now->qty - $cek_sn;

        if ($cek_sn > 0) {
            $id_product = DB::table('inventory_produk')
            ->join('detail_inventory_produk','detail_inventory_produk.id_barang','=','inventory_produk.id_barang')
            ->select('inventory_produk.id_product','inventory_produk.id_barang')
            ->where('inventory_produk.id_barang',$id_barang)
            ->first();
        }else{
            $id_product = DB::table('inventory_produk')
            ->select('inventory_produk.id_product','inventory_produk.id_barang')
            ->where('inventory_produk.id_barang',$id_barang)
            ->first();
        }

        $detail = DB::table('inventory_produk')
            ->join('detail_inventory_produk','detail_inventory_produk.id_barang','=','inventory_produk.id_barang')
            ->select('detail_inventory_produk.id_detail','inventory_produk.nama','inventory_produk.qty','detail_inventory_produk.id_barang','inventory_produk.kategori','inventory_produk.tipe','inventory_produk.qty','detail_inventory_produk.note','detail_inventory_produk.serial_number','detail_inventory_produk.status','detail_inventory_produk.note','inventory_produk.id_product')
            ->where('inventory_produk.id_barang',$id_barang)
            ->get();

        /*$id_product = DB::table('inventory_produk')
            ->join('detail_inventory_produk','detail_inventory_produk.id_barang','=','inventory_produk.id_barang')
            ->select('inventory_produk.id_product','inventory_produk.id_barang')
            ->where('inventory_produk.id_barang',$id_barang)
            ->first();*/

        


        return view('gudang/detail_gudang', compact('detail','notif','notifOpen','notifsd','notiftp','notifc','notifem','cek_sn','qty_now','id_product','qty_total','qty_changelog'));
    }

    public function Detail_inventory_msp($id_product)
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


        $detail = DB::table('inventory_produk_msp')
            ->join('inventory_changelog_msp','inventory_changelog_msp.id_product','=','inventory_produk_msp.id_product')
            ->select('inventory_changelog_msp.id_changelog','inventory_produk_msp.nama','inventory_produk_msp.qty','inventory_changelog_msp.id_product','inventory_changelog_msp.qty','inventory_changelog_msp.status','inventory_changelog_msp.note','inventory_changelog_msp.status','inventory_produk_msp.kode_barang','inventory_changelog_msp.created_at')
            ->where('inventory_changelog_msp.id_product',$id_product)
            ->get();

        $datak = DB::table('inventory_produk_msp')
            ->select('nama','qty')
            ->where('id_product',$id_product)
            ->first();

        $keg = DB::table('inventory_changelog_msp')
            ->select('status','note','created_at')->where('id_product',$id_product)->orderBy('created_at','desc')->first();

        $notes = substr($keg->note,5,2);

        $dating = $keg->created_at;

        $sn = DB::table('detail_inventory_produk_msp')
            ->join('inventory_produk_msp','inventory_produk_msp.id_barang','=','detail_inventory_produk_msp.id_barang')
            ->select('serial_number','inventory_produk_msp.nama')
            ->where('detail_inventory_produk_msp.id_product',$id_product)
            ->get();

        $cek = Inventory_msp::select('status2')->where('id_product',$id_product)->first();


        return view('gudang/detail_gudang_msp', compact('cek','sn','detail','notif','notifOpen','notifsd','notiftp','notifc','notifem','datak','keg','notes','dating'));
    }    

    public function do_sup_index()
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        $pam = DB::table('tb_po_asset_msp')
                ->select('tb_po_asset_msp.date_handover','tb_po_asset_msp.to_agen','tb_po_asset_msp.status_do_sup','tb_po_asset_msp.subject', 'tb_po_asset_msp.attention', 'tb_po_asset_msp.project', 'tb_po_asset_msp.project_id', 'term', 'tb_po_asset_msp.id_po_asset', 'tb_po_asset_msp.id_pr_asset','tb_po_asset_msp.no_do_sup')
                ->get();

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

        $msp_code = DB::table('inventory_produk_msp')
                    ->select('kode_barang')
                    ->get();

        return view('gudang/gudang2',compact('notif','notifOpen','notifsd','notiftp','notifClaim','pam','produks','pams','sum','id_pam','count_product','total_amount','no_pr','$total_amount','from', 'project_id', 'msp_code'));
    }

   
    public function approve_finance_do(Request $request)
    {
        $id_po_asset = $request['id_po_asset'];

        $update = POAssetMSP::where('id_po_asset',$id_po_asset)->first();
        $update->status_do_sup = 'FINANCE';
        $update->update();  

        return redirect()->back()->with('success', 'Yes Approved!'); 
    }


    public function getDropdownPO(Request $request)
    {   
        /*return array(DB::table('tb_pr_product_msp')
                ->join('tb_pam_msp','tb_pam_msp.id_pam','=','tb_pr_product_msp.id_pam')
                ->select('name_product','msp_code','unit','qty','description')
                ->where('tb_pr_product_msp.id_pam',$request->product)
                ->get(),$request->product);*/

        return array(DB::table('tb_pam_msp')
            ->join('tb_pr_product_msp','tb_pr_product_msp.id_pam','=','tb_pam_msp.id_pam')
            ->join('tb_po_asset_msp', 'tb_po_asset_msp.id_pr_asset', '=', 'tb_pam_msp.id_pam')
            ->join('tb_pr_msp', 'tb_pr_msp.no', '=', 'tb_pam_msp.no_pr')
            ->join('tb_po_msp', 'tb_po_msp.no', '=', 'tb_pr_msp.no_po')
            ->join('inventory_produk_msp','inventory_produk_msp.id_product','=','tb_pr_product_msp.id_barang')
            ->select('tb_pr_product_msp.name_product','tb_pr_product_msp.qty','tb_pr_product_msp.id_pam','tb_pr_product_msp.nominal','tb_pr_product_msp.total_nominal', 'tb_pr_product_msp.description', 'tb_pr_product_msp.unit', 'tb_pr_product_msp.msp_code','tb_po_msp.no_po','tb_pr_product_msp.id_product','tb_po_asset_msp.id_po_asset','tb_po_asset_msp.status_po','tb_pr_product_msp.status','tb_pr_product_msp.id_barang','inventory_produk_msp.qty as qty_katalog')
            ->where('tb_pr_product_msp.id_pam',$request->product)
            ->where('tb_pr_product_msp.qty','!=',0)
            ->get(),$request->product);
    }

    public function getDropdownPoSip(Request $request)
    {   
        $product = $request['po_number']; 

        $cek_po  = POAsset::select('status_po')->where('id_pr_asset',$request->product)->first();

        if ($cek_po->status_po == 'FINANCE') {
            return array(DB::table('dvg_pam')
            ->join('dvg_pr_product','dvg_pr_product.id_pam','=','dvg_pam.id_pam')
            ->join('tb_po_asset', 'tb_po_asset.id_pr_asset', '=', 'dvg_pam.id_pam')
            ->join('tb_pr', 'tb_pr.no', '=', 'dvg_pam.no_pr')
            ->join('tb_po', 'tb_po.no', '=', 'tb_pr.no_po')/*
            ->join('inventory_produk','inventory_produk.id_product','=','dvg_pr_product.id_product')*/
            ->select('dvg_pr_product.name_product','dvg_pr_product.qty','dvg_pr_product.id_pam','dvg_pr_product.total_nominal','dvg_pr_product.nominal','dvg_pr_product.total_nominal', 'dvg_pr_product.description','tb_po.no_po','dvg_pr_product.id_product','tb_po_asset.id_po_asset','tb_po_asset.status_po')
            ->where('dvg_pr_product.id_pam',$request->product)
            ->where('dvg_pr_product.qty','!=',0)
            ->get(),$request->product);
        }else if ($cek_po->status_po == 'PENDING'){
            return array(DB::table('dvg_pam')
            ->join('dvg_pr_product','dvg_pr_product.id_pam','=','dvg_pam.id_pam')
            ->join('tb_po_asset', 'tb_po_asset.id_pr_asset', '=', 'dvg_pam.id_pam')
            ->join('tb_pr', 'tb_pr.no', '=', 'dvg_pam.no_pr')
            ->join('tb_po', 'tb_po.no', '=', 'tb_pr.no_po')
            ->join('inventory_produk','inventory_produk.id_product','=','dvg_pr_product.id_product')
            ->select('dvg_pr_product.name_product','dvg_pr_product.qty','dvg_pr_product.id_pam','dvg_pr_product.total_nominal','dvg_pr_product.nominal','dvg_pr_product.total_nominal', 'dvg_pr_product.description','tb_po.no_po','dvg_pr_product.id_product','tb_po_asset.id_po_asset','tb_po_asset.status_po','inventory_produk.kategori','inventory_produk.tipe','inventory_produk.qty as qty_katalog')
            ->where('dvg_pr_product.id_pam',$request->product)
            ->where('dvg_pr_product.qty','!=',0)
            ->get(),$request->product);
        }

        

    }

    public function getbtnSN(Request $request)
    {
        $product = $request['btn_sn']; 

        return array(DB::table('inventory_produk')
            ->join('inventory_change_log','inventory_change_log.id_product','=','inventory_produk.id_product')
            ->select('inventory_change_log.qty','inventory_produk.id_barang','inventory_produk.id_product')
            ->orderBy('inventory_change_log.created_at','desc')
            ->where('inventory_change_log.id_product',$request->product)
            ->first(),$request->product);
    }

    public function getDropdownSubmitPO(Request $request){
        return array(DB::table('tb_po_asset_msp')->select('status_po')->where('tb_po_asset_msp.id_pr_asset',$request->product)->get(),$request->product);
    }

    public function getDropdownSubmitPoSIP(Request $request){
        return array(DB::table('tb_po_asset')->select('status_po')->where('tb_po_asset.id_pr_asset',$request->product)->get(),$request->product);
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
        $tambah = new Warehouse();
        $tambah->item_code = $request['item_code'];
        $tambah->name_item = $request['name'];
        $tambah->quantity = $request['quantity'];
        $tambah->information = $request['information'];
        $tambah->save();

        return redirect('/warehouse')->with('success', 'Created Product Successfully!');
    }

    public function inventory_store(Request $request)
    {
       
        $qty        = $request['qty_terima'];
        $nama       = $request['name_product_edit'];
        $desc       = $request['desc_edit'];
        $po         = $request['no_po_edit'];
        $id_product = $request['id_product_edit'];
        $id_pam     = $request['id_pam'];
        $kategori   = $request['kategori'];
        $tipe       = $request['tipe'];

        if(count($nama) > count($qty))
            $count = count($qty);
        else $count = count($nama);

        for($i = 0; $i < $count; $i++){
            $data = array(
                'nama'              => $nama[$i],
                'qty'               => $qty[$i],
                'note'              => $desc[$i],
                'id_po'             => $po[$i],
                'kategori'          => $kategori[$i],
                'tipe'              => $tipe[$i],
                'id_product'        => $id_product[$i],
                'status'            => 'P',
            );
            $insertData[] = $data;

            $datas = array(
                'qty'               => $qty[$i],
                'id_product'        => $id_product[$i],
                'note'              => $po[$i],
                'status'            => 'P',
                );
            $insertDatas[] = $datas;
        };
        Inventory::insert($insertData);
        ChangelogInventory::insert($insertDatas);


        foreach ($id_product as $produk) {
            $qty_awal   = pamProduk::select('qty')->where('id_product',$produk)->get();
            $qty_akhir  = ChangelogInventory::select('qty')->where('id_product',$produk)->orderBy('created_at','asc')->get();

            foreach ($qty_awal as $qty_awal) {
                foreach ($qty_akhir as $qty_akhir) {
                    $update_qty = pamProduk::where('id_product',$produk)->first();
                    $update_qty->qty = $qty_awal->qty - $qty_akhir->qty;    
                    $update_qty->update(); 
                }               
            }  

            if ($request['qty_awal'] == $request['qty_terima']) {
                $update3 = POAsset::where('id_po_asset',$id_pam)->first();
                $update3->status_po = 'DONE';
                $update3->update();
            }elseif ($request['qty_awal'] != $request['qty_terima']) {
                $update3 = POAsset::where('id_po_asset',$id_pam)->first();
                $update3->status_po = 'PENDING';
                $update3->update();
            }

        }

        return redirect('/inventory')->with('success', 'Created Product Successfully!');
    }

    public function terima_store_msp(Request $request)
    {
       
        $nama       = $request['name'];
        $kategori   = $request['caty'];
        $tipe       = $request['type'];
        $qty        = $request['quantity'];
        $po         = $request['po'];
        $note       = $request['information'];
        $id         = $request['id_barang'];

        if(count($nama) > count($qty))
            $count = count($qty);
        else $count = count($nama);

        for($i = 0; $i < $count; $i++){
            $data = array(
                'kode_barang' => $id[$i],
                'nama'      => $nama[$i],
                'kategori'  => $kategori[$i],
                'tipe'      => $tipe[$i],
                'qty'       => $qty[$i],
                'note'      => $note[$i],
                'id_po'     => $po[$i],
            );
            $insertData[] = $data;
        };
        Inventory_msp::insert($insertData);

        return redirect('/inventory/msp')->with('success', 'Created Product Successfully!');
    }

    public function inventory_store_msp(Request $request)
    {
       
        $qty        = $request['qty_terima'];
        $nama       = $request['name_product_edit'];
        $code       = $request['msp_code_edit'];
        $desc       = $request['desc_edit'];
        $unit       = $request['unit_edit'];
        $po         = $request['no_po_edit'];
        $id_product = $request['id_product_edit'];
        $id_pam     = $request['id_pam'];/*
        $sn         = $request['sn_edit'];*/

        if(count($nama) > count($qty))
            $count = count($qty);
        else $count = count($nama);

        for($i = 0; $i < $count; $i++){
            $data = array(
                'kode_barang'   => $code[$i],
                'nama'          => $nama[$i],
                'unit'          => $unit[$i],
                'qty'           => $qty[$i],
                'note'          => $desc[$i],
                'id_po'         => $po[$i],
                'id_product'    => $id_product[$i],/* 
                'status2'       => $sn[$i],*/
            );
            $insertData[] = $data;

            $datas = array(
                'qty'           => $qty[$i],
                'id_product'    => $id_product[$i],
                'note'          => $po[$i],
                'status'        => 'P',
                );
            $insertDatas[] = $datas;
        };
        Inventory_msp::insert($insertData);
        Inventory_msp_changelog::insert($insertDatas);


        foreach ($id_product as $produk) {
            $qty_awal   = pam_produk_msp::select('qty')->where('id_product',$produk)->get();
            $qty_akhir  = Inventory_msp_changelog::select('qty')->where('id_product',$produk)->orderBy('created_at','asc')->get();/*
            $sn_edit    = Inventory_msp::select('status2')->where('id_product',$produk)->orderBy('created_at','asc')->get();*/
            /*$qty_awal       = $_POST['qty_awal'];
            $qty_terima     = $_POST['qty_terima'];*/

            foreach ($qty_awal as $qty_awal) {
                foreach ($qty_akhir as $qty_akhir) {
                    $update_qty = pam_produk_msp::where('id_product',$produk)->first();
                    $update_qty->qty = $qty_awal->qty - $qty_akhir->qty;    
                    $update_qty->update(); 
                }               
            }  

            /*foreach ($sn as $sn_edit) {
                $update_status = pam_produk_msp::where('id_product',$produk)->first();
                $update_status->status = $sn_edit;  
                $update_status->update();
            }
            */

            if ($request['qty_awal'] == $request['qty_terima']) {
                $update3 = POAssetMSP::where('id_po_asset',$id_pam)->first();
                $update3->status_po = 'DONE';
                $update3->update();
            }elseif ($request['qty_awal'] != $request['qty_terima']) {
                $update3 = POAssetMSP::where('id_po_asset',$id_pam)->first();
                $update3->status_po = 'PENDING';
                $update3->update();
            }

        }

        return redirect('/inventory/msp')->with('success', 'Created Product Successfully!');
    }

    public function inventory_detail_store_msp(Request $request)
    {        
        $id_product = $request['id_product_detil'];
        $id_barang = $request['id_barang_detail'];

        $qty_produk = Inventory_msp::select('qty')->where('id_product',$id_product)->first();
        $qty_pr = pam_produk_msp::select('qty')->where('id_product',$id_product)->first();

        $data = DB::table('inventory_produk_msp')->select('status2')->where('id_product',$id_product)->first();

        $qty_now = $qty_produk->qty + $qty_pr->qty;

        for ($i=0; $i < $qty_now ; $i++) { 
        $tambah = new WarehouseDetailProduk();
        $tambah->id_barang      = $id_barang;
        $tambah->id_product     = $id_product;
        $tambah->serial_number  = null;
        $tambah->save();  
        }

        $update = Inventory_msp::where('id_product',$id_product)->first();
        $update->status = 'v';
        $update->update();

        return redirect('/inventory/msp');
    }

    public function inventory_detail_produk(Request $request)
    {
        $qty        = $request['qty']; 

        $id_barang  = $request['id_barang_detail']; 

        $date = Inventory::select('created_at')
                ->where('id_barang',$id_barang)
                ->first();

        $dates = $date->created_at;    

        for ($i=0; $i < $qty ; $i++) { 
            $store = new Detail_inventory();
            $store->id_barang = $id_barang;
            $store->serial_number = $request['sn'];
            $store->tgl_masuk = $dates;
            $store->save();  
        }

        $update = Inventory::where('id_barang',$id_barang)->first();
        $update->status = 'P';
        $update->update(); 

        return redirect('/inventory');
    }


    public function store_category(Request $request)
    {
        $tambah = new Category_in();
        $tambah->category = $request['category'];
        $tambah->save();

        return redirect('/category')->with('success', 'Created Category Successfully!');
    }

    public function store_type(Request $request)
    {
        $tambah = new Type_in();
        $tambah->type = $request['type'];
        $tambah->save();

        return redirect('/category')->with('success', 'Created Type Successfully!');
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

    public function inventory_update(Request $request)
    {
        $id             = $_POST['id_product_edit'];
        $id_pam         = $_POST['id_pam'];
        $qty            = $_POST['qty_terima'];
        $id_po          = $_POST['no_po_edit'];
        $qty_awal       = $_POST['qty_awal'];
        $qty_terima     = $_POST['qty_terima'];
        $qty_katalog    = $_POST['qty_katalog'];

        $kategori       = $_POST['kategori'];
        $tipe           = $_POST['tipe'];

        $id_barang = Inventory::select('id_barang')->where('id_product',$id)->first();

        $date = Inventory::select('created_at')
                ->where('id_product',$id)
                ->first();

        $dates = $date->created_at;    


        if(count($id) > count($qty))
            $count = count($qty);
        else $count = count($id);

      /*  for ($i=0; $i < $count ; $i++) { 
            $store = new Detail_inventory();
            $store->id_barang = $id_barang->id_barang;
            $store->serial_number = $request['sn'];
            $store->tgl_masuk = $dates;
            $store->save();  
        }*/

        
        for($i = 0; $i < $count; $i++){
            $datas = array(
                'qty'           => $qty[$i],
                'id_product'    => $id[$i],
                'note'          => $id_po[$i],
                'status'        => 'P',
                );
            $insertDatas[] = $datas;

            $datam = array(
                'qty'           => $qty_terima[$i] + $qty_katalog[$i],
                'kategori'      => $kategori[$i],
                'tipe'          => $tipe[$i],
                'qty_status'    => 'Y',
            );
            Inventory::where('id_product',$id[$i])->update($datam);
        }
        ChangelogInventory::insert($insertDatas);
/*
        foreach ($id as $produk) {
            $qty_awal  = Inventory::select('qty')->where('id_product',$produk)->get();
            $qty_akhir = ChangelogInventory::select('qty')->where('id_product',$produk)->orderBy('created_at','asc')->get();
            $update    = Inventory::where('id_product',$produk)->first();
            foreach ($qty_awal as $qty_awal) {
                foreach ($qty_akhir as $qty_last ) {
                    $update->qty        = $qty_awal->qty + $qty_last->qty;
                    $update->kategori   = 
                    $update->qty_status = 'F'; 
                    $update->update(); 
                }                
            }
        }

*/
        foreach ($id as $produk) {
            $qty_awal = pamProduk::select('qty')->where('id_product',$produk)->get();
            $qty_akhir = ChangelogInventory::select('qty')->where('id_product',$produk)->orderBy('created_at','asc')->get();
            $update2 = pamProduk::where('id_product', $produk)->first();
            foreach ($qty_awal as $qty_awal) {
                foreach ($qty_akhir as $qty_akhir ) {
                    $update2->qty = $qty_awal->qty - $qty_akhir->qty; 
                        if ($request['qty_awal'] != $request['qty_terima']) {
                            $update3 = POAsset::where('id_po_asset',$id_pam)->first();
                            $update3->status_po = 'PENDING';
                            $update3->update();
                        }elseif ($request['qty_awal'] == $request['qty_terima']) {
                            $update3 = POAsset::where('id_po_asset',$id_pam)->first();
                            $update3->status_po = 'DONE';
                            $update3->update();
                        }
                    
                }
                            
            }
            $update2->update();
        }

        /*$id = $request['edit_id_barang'];

        $id_po = $request['po_detail_edit'];

        if ($request['edit_quantity'] != null) {
            $qty_lama   = Inventory::select('qty')->where('id_barang',$id)->first();
            $qty_old    = $qty_lama->qty;
            $qty_new    = $request['edit_quantity'];

            $counts = $qty_new;

            $qty_last = $qty_old + $qty_new;

            for ($i=0; $i < $counts ; $i++) { 
                $store = new Detail_inventory();
                $store->id_barang = $id;
                $store->id_po = $id_po;
                $store->save();  
            }
        }

        $update = Inventory::where('id_barang',$id)->first();
        $update->nama = $request['edit_name'];
        if ($request['edit_quantity'] != null) {
            $update->qty = $qty_last;
        } 
        $update->note = $request['edit_information'];
        $update->update();*/ 

        return redirect('/inventory')->with('update', 'Updated Product Successfully!');
    }

    public function inventory_msp_update(Request $request)
    {
        /*$id         = $_POST['id_product_edit'];
        $id_pam     = $_POST['id_pam'];
        $qty        = $_POST['qty_terima'];
        $id_po      = $_POST['no_po_edit'];
        $qty_awal   = $_POST['qty_awal'];
        $qty_terima = $_POST['qty_terima'];

        if(count($id) > count($qty))
            $count = count($qty);
        else $count = count($id);

        
        for($i = 0; $i < $count; $i++){
            $datas = array(
                'qty'           => $qty[$i],
                'id_product'    => $id[$i],
                'note'          => $id_po[$i],
                'status'        => 'P',
                );
            $insertDatas[] = $datas;
        }
        Inventory_msp_changelog::insert($insertDatas);

        foreach ($id as $produk) {
            $qty_awal  = Inventory_msp::select('qty')->where('id_product',$produk)->get();
            $qty_akhir = Inventory_msp_changelog::select('qty')->where('id_product',$produk)->orderBy('created_at','asc')->get();
            $update = Inventory_msp::where('id_product',$produk)->first();
            foreach ($qty_awal as $qty_awal) {
                foreach ($qty_akhir as $qty_last ) {
                    $update->qty = $qty_awal->qty + $qty_last->qty; 
                    $update->update(); 
                }                
            }
        }

        foreach ($id as $produk) {
            $qty_awal = pam_produk_msp::select('qty')->where('id_product',$produk)->get();
            $qty_akhir = Inventory_msp_changelog::select('qty')->where('id_product',$produk)->orderBy('created_at','asc')->get();
            $update2 = pam_produk_msp::where('id_product', $produk)->first();
            foreach ($qty_awal as $qty_awal) {
                foreach ($qty_akhir as $qty_akhir ) {
                    $update2->qty = $qty_awal->qty - $qty_akhir->qty; 
                        if ($request['qty_awal'] != $request['qty_terima']) {
                            $update3 = POAssetMSP::where('id_po_asset',$id_pam)->first();
                            $update3->status_po = 'PENDING';
                            $update3->update();
                        }elseif ($request['qty_awal'] == $request['qty_terima']) {
                            $update3 = POAssetMSP::where('id_po_asset',$id_pam)->first();
                            $update3->status_po = 'DONE';
                            $update3->update();
                        }
                    
                }
                            
            }
            $update2->update();
        }*/

        $id         = $_POST['id_product_edit'];
        $id_pam     = $_POST['id_pam'];
        $qty        = $_POST['qty_terima'];
        $id_po      = $_POST['no_po_edit'];
        $qty_awal   = $_POST['qty_awal'];
        $qty_katalog= $_POST['qty_katalog'];
        $qty_terima = $_POST['qty_terima'];
        $unit       = $_POST['unit_edit'];
        $id_product = $_POST['id_product_pam'];
/*
        $ids = @explode(',', $id);*/

        if(count($id_product) > count($qty))
            $count = count($qty);
        else $count = count($id_product);

        
        for($i = 0; $i < $count; $i++){
            $datas = array(
            'qty'           => $qty[$i],
            'id_product'    => $id[$i],
            'note'          => $id_po[$i],
            'status'        => 'P',
            );
            $insertDatas[] = $datas;

            $datam = array(
                'qty'  => $qty_terima[$i] + $qty_katalog[$i],
                'unit' => $unit[$i],
                'status' => 'Y',
            );
            Inventory_msp::where('id_product',$id[$i])->update($datam);

            $datak = array(
                'qty' => $qty_awal[$i] - $qty_terima[$i],
            );
            pam_produk_msp::where('id_product',$id_product[$i])->update($datak);

            if ($request['qty_awal'] != $request['qty_terima']) {
            $update3 = POAssetMSP::where('id_pr_asset',$id_pam)->first();
            $update3->status_po = 'PENDING';
            $update3->update();
            }elseif ($request['qty_awal'] == $request['qty_terima']) {
                $update3 = POAssetMSP::where('id_pr_asset',$id_pam)->first();
                $update3->status_po = 'DONE';
                $update3->update();
            }
        }
        Inventory_msp_changelog::insert($insertDatas);

        return redirect('/inventory/msp')->with('update', 'Updated Product Successfully!');
    }

    public function terima_msp_update(Request $request)
    {
        $id = $request['edit_id_barang'];

        $id_po = $request['po_detail_edit'];

        if ($request['edit_quantity'] != null) {
            $qty_lama   = Inventory_msp::select('qty')->where('id_barang',$id)->first();
            $qty_old    = $qty_lama->qty;
            $qty_new    = $request['edit_quantity'];

            $counts = $qty_new;

            $qty_last = $qty_old + $counts;

            $store = new Inventory_msp_changelog();
            $store->qty       = $counts;
            $store->id_barang = $id;
            $store->note      = $id_po;
            $store->status    = 'P';
            $store->save();  
            
        }

        $update = Inventory_msp::where('id_barang',$id)->first();
        $update->nama = $request['edit_name'];
        if ($request['edit_quantity'] != null) {   
            $update->qty = $qty_last;
        } 
        $update->note = $request['edit_information'];
        $update->update(); 

        return redirect('/inventory/msp')->with('update', 'Updated Product Successfully!');
    }

    public function update_serial_number(Request $request)
    {
        /*$id = $request['id_detail_edit'];
    
        $update = Detail_inventory::where('id_detail',$id)->first();
        $update->serial_number = $request['edit_serial_number'];
        
        $update->note = $request['note_edit'];

        $update->update();*/

        $id = $request['sn_barang'];

        $datas      = Inventory::select('qty','id_po','created_at','id_barang')->where('id_barang',$id)->first();
        $qty        = $datas->qty;
        $id_po      = $datas->id_po;
        $id_barang  = $datas->id_barang;
        $date       = $datas->created_at;

        if(count($id) > count($qty))
            $count = count($qty);
        else $count = count($id);

        $arr = explode("\r\n", trim($_POST['serial_number']));

        for ($i = 0; $i < count($arr); $i++) {
                $line = $arr[$i];

                $datas = array(
                    'id_barang'     => $id,
                    'serial_number' => $line,
                    'note'          => $id_po,
                    'status'        => 'P',
                    'tgl_masuk'     => $date,
                    );
                $insertDatas[] = $datas;
        }
        Detail_inventory::insert($insertDatas);

        /*$arr = explode("\r\n", trim($_POST['serial_number']));

        for ($i = 0; $i < count($arr); $i++) {
                $line = $arr[$i];

                $datas = array(
                    'id_barang'     => $id,
                    'serial_number' => $line,
                    'note'          => $id_po,
                    'status'        => 'P',
                    'tgl_masuk'     => $date,
                    );
                $insertDatas[] = $datas;
        }
        Detail_inventory::where('id_barang',$id)->update($datas);*/
/*
        $textAr = explode("\n", trim($_POST['serial_number']));

        foreach ($textAr as $line) {
            $data = [
                'id_barang'     => $id,
                'serial_number' => $line,
                'note'          => $id_po,
                'status'        => 'P',
                'tgl_masuk'     => $date,
            ];
        }
        Detail_inventory::where('id_barang',$id)->update($data); */

        $update = Inventory::where('id_barang',$id)->first();
        $update->qty_status = 'F';
        $update->status     = 'P';
        $update->update();

        return redirect()->back()->with('alert', 'Successfully!');
        
    }


    public function update_serial_number_msp(Request $request)
    {
        $id_product     = $request['id_product_edit'];
        $id_detail      = WarehouseDetailProduk::select('id_product')->where('id_product',$id_product)->where('serial_number',null)->get();
        $serial_number  = $_POST['serial_number'];
    
        $data = [];
        foreach ($id_detail as $id_detail) {
            foreach ($serial_number as $sn) {
                $textAr = explode("\n", $sn); // remove any extra \r chars

                foreach ($textAr as $line) {
                    $data[] = [
                        'serial_number'      => $line, 
                    ];
                }
            }
        }
        DB::table('detail_inventory_produk_msp')->where('id_product',$id_detail->id_product)->update($data);  
        

        return redirect()->back();
    }

    public function update_category(Request $request)
    {
        $id = $request['id_category_edit'];

        $update = Category_in::where('id_category',$id)->first();
        $update->category = $request['category_edit'];

        $update->update();

        return redirect()->back();
    }

    public function update_tipe(Request $request)
    {
        $id = $request['id_type_edit'];

        $update = Type_in::where('id_type',$id)->first();
        $update->type = $request['type_edit'];

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

    public function destroy_produk($id_barang)
    {
        $hapus = inventory::find($id_barang);
        $hapus->delete();

        return redirect()->back()->with('alert', 'Deleted!');
    }

    public function destroy_detail_produk(Request $request)
    {
        $id_detail = $request['id_detail_hapus'];
        $id_barang = $request['id_barang_hapus'];

        $qty_barang = inventory::select('qty')
                    ->where('id_barang',$id_barang)
                    ->first();

        $hapus = Detail_inventory::find($id_detail);
        $hapus->delete();

        $store = new ChangelogInventory();
        $store->id_detail_barang = $id_detail;
        $store->note             = $request['note_hapus'];
        $store->hapus();

        $update = inventory::where('id_barang',$id_barang);
        $update->qty = $qty_barang->qty - 1;
        $update->update(); 

        return redirect()->back()->with('alert', 'Deleted!');
    }

    public function destroy_category($id_category)
    {
        $hapus = Warehouse::find($id_category);
        $hapus->delete();

        return redirect()->back()->with('alert', 'Deleted!');
    }

    public function destroy_type($id_type)
    {
        $hapus = Warehouse::find($id_type);
        $hapus->delete();

        return redirect()->back()->with('alert', 'Deleted!');
    }
}
