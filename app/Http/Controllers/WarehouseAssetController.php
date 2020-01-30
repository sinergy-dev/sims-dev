<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\WarehouseAsset;
use App\Inventory;
use App\WarehouseAssetTransaction;
use App\Detail_inventory;
use App\Inventory_msp;
use App\WarehouseAssetTransactionMSP;

class WarehouseAssetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function view_asset(request $request)
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

        $datas = DB::table('inventory_produk')
                ->select('nama','kategori','tipe','qty','note')
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

        return view('report/asset', compact('lead', 'total_ter','notif','notifOpen','notifsd','notiftp','datas','notifClaim'));
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

        if ($ter != null) {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();

            $notifc = count($notif);
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $notif = DB::table('sales_lead_register')
            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->select('sales_lead_register.opp_name','sales_solution_design.nik')
            ->where('result','')
            ->orderBy('sales_lead_register.created_at','desc')
            ->get();

            $notifc = count($notif);
        }else{
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();

            $notifc = count($notif);
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

        $assetsd    = DB::table('inventory_asset_transaction')
                    ->join('users','users.nik','=','inventory_asset_transaction.nik_peminjam')
                    ->join('inventory_produk','inventory_produk.id_barang','=','inventory_asset_transaction.id_barang')
                    ->select('inventory_asset_transaction.id_transaksi','inventory_asset_transaction.id_barang','inventory_produk.nama','inventory_asset_transaction.qty_pinjam','inventory_asset_transaction.keterangan','users.name','inventory_asset_transaction.qty_awal','inventory_asset_transaction.nik_peminjam','inventory_asset_transaction.status', 'tgl_peminjaman', 'tgl_pengembalian')
                    ->get();

        $pinjaman = DB::table('inventory_asset_transaction')
                    ->join('users','users.nik','=','inventory_asset_transaction.nik_peminjam')
                    ->join('inventory_produk','inventory_produk.id_barang','=','inventory_asset_transaction.id_barang')
                    ->select('inventory_asset_transaction.id_transaksi','inventory_asset_transaction.id_barang','inventory_produk.nama','inventory_asset_transaction.qty_pinjam','inventory_asset_transaction.keterangan','users.name','inventory_asset_transaction.qty_awal','inventory_asset_transaction.nik_peminjam','inventory_asset_transaction.status', 'tgl_peminjaman', 'tgl_pengembalian', 'inventory_asset_transaction.note')
                    ->where('nik_peminjam', Auth::User()->nik)
                    ->get();

        // $id_barang = $request['id_barang'];

        $asset = DB::table('inventory_produk')
                ->select('nama','inventory_produk.kategori', 'inventory_produk.tipe', 'qty','inventory_produk.note','inventory_produk.id_barang','inventory_produk.id_po')
                ->get();

        $peminjam = DB::table('users')
                    ->select('name', 'nik')
                    ->where('id_company', '1')
                    ->get();

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
        }elseif (Auth::User()->id_company == '2') {
            $notifem = DB::table('users')
            ->select('name','nik')
            ->where('status_delete','D')
            ->get();
        }elseif ($ter != null) {
            $notifem = DB::table('users')
            ->select('name','nik')
            ->where('status_delete','D')
            ->get();
        }elseif ($div == 'TECHNICAL' && $pos == 'MANAGER') {
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

        return view('gudang.asset.asset', compact('notifc','pinjaman','kembali','assets','assetsd','asset','lead','notif','notifOpen','notifsd','notiftp','notifc','notifem', 'peminjaman', 'peminjam', 'list_pinjaman'));
    }

    public function detail_asset($id_barang)
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

            $notifc = count($notif);
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $notif = DB::table('sales_lead_register')
            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->select('sales_lead_register.opp_name','sales_solution_design.nik')
            ->where('result','')
            ->orderBy('sales_lead_register.created_at','desc')
            ->get();

            $notifc = count($notif);
        }else{
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();

            $notifc = count($notif);
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
        }elseif (Auth::User()->id_company == '2') {
            $notifem = DB::table('users')
            ->select('name','nik')
            ->where('status_delete','D')
            ->get();
        }elseif ($ter != null) {
            $notifem = DB::table('users')
            ->select('name','nik')
            ->where('status_delete','D')
            ->get();
        }elseif ($div == 'TECHNICAL' && $pos == 'MANAGER') {
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

        $detail_asset = DB::table('inventory_asset_transaction')
                        ->join('users', 'users.nik', '=', 'inventory_asset_transaction.nik_peminjam')
                        ->join('inventory_produk', 'inventory_asset_transaction.id_barang', '=', 'inventory_produk.id_barang')
                        ->select('users.name', 'tgl_peminjaman', 'tgl_pengembalian', 'inventory_asset_transaction.note', 'inventory_produk.id_barang', 'inventory_asset_transaction.id_transaksi', 'inventory_asset_transaction.status', 'nama', 'tipe', 'kategori', 'qty_pinjam')
                        ->where('inventory_produk.id_barang', $id_barang)
                        ->get();

        return view('gudang.asset.detail_asset', compact('notifc','lead','notif','notifOpen','notifsd','notiftp','notifc','notifem','detail_asset'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /*$name = substr($request['nama_barang'], 0,2);
        $date = date('m') . date('d');

        $barang = $request['nama_barang'];
        $inc = DB::table('inventory_asset')
                    ->select('id_barang')
                    ->where('nama_barang', $barang)
                    ->get();
        $increment = count($inc);
        $nomor = $increment+1;
        if($nomor < 10){
           $nomor = '0' . $nomor;
        }

        $asset = $name . $date . $nomor;

        $tambah = new WarehouseAsset();
        $tambah->id_barang   = $asset;
        $tambah->nik         = Auth::User()->nik;
        $tambah->nama_barang = $request['nama_barang'];
        $tambah->qty         = $request['qty'];
        $tambah->description = $request['keterangan'];
        $tambah->save(); 

        return redirect()->back()->with('success', 'Succesfully!');*/
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
    public function edit(Request $request)
    {
        /*$id_barang = $request['id_barang_edit'];

        $update = WarehouseAsset::where('id_barang',$id_barang)->first();
        $update->qty         = $request['qty_edit'];
        $update->description = $request['keterangan_edit'];
        $update->update(); 

        return redirect()->back()->with('update','Update Barang Berhasil!');*/
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id_barang)
    {
        $hapus = WarehouseAsset::find($id_barang);
        $hapus->delete();

        return redirect()->back()->with('alert', 'Deleted!');
    }

    public function peminjaman(Request $request)
    {
        $id_barang = $request['id_barang'];

        $qty_pinjam = DB::table('inventory_produk')
                    ->select('qty')
                    ->where('id_barang',$id_barang)
                    ->first();

        $qtys       = $qty_pinjam->qty;

        $qtyd       = $request['quantity'];

        $update = Inventory::where('id_barang',$id_barang)->first();

        if ($qtys >= $request['quantity']) {
            $update->qty = $qtys - $qtyd;
        }else{
            return back()->with('warning','Kebutuhan melebihi Stock!');
        }
        $update->update();  

/*      $store = Tech_asset_transaction::firstOrNew(array('nik_peminjam' => $nik_peminjam));*/
        $store                   = new WarehouseAssetTransaction();
        $store->id_barang        = $id_barang; 
        // if(Auth::User()->id_division == 'WAREHOUSE'){
        //     $store->nik_peminjam = $request['peminjam'];
        // }else {
        $store->nik_peminjam     = Auth::User()->nik;
        // }
        $store->qty_pinjam       = $request['quantity'];
        $store->tgl_pengembalian = $request['tgl_kembali'];
        $store->tgl_peminjaman   = $request['tgl_peminjaman'];
        $store->qty_awal         = $qtys;
        $store->status           = 'PENDING';
        $store->keterangan       = $request['description'];
        $store->save();       

        return redirect()->back()->with('update', 'Peminjaman Akan di Proses!');
    }

    public function accept_pinjam(Request $request)
    {
        $id_transaction = $request['id_transaction_update'];

        $id_barang   = $request['id_barang_update'];                

        $update             = WarehouseAssetTransaction::where('id_transaksi',$id_transaction)->first();
        $update->status     = 'ACCEPT';
        $update->update();

        return redirect()->back()->with('success', 'Peminjaman Telah di verifikasi!');; 
    }

    public function reject(Request $request)
    {
        $id_transaction = $request['id_transaction_reject'];

        $id_barang   = $request['id_barang_reject'];

        $hmm   = DB::table('inventory_asset_transaction')
                    ->select('qty_pinjam')
                    ->where('id_transaksi',$id_transaction)
                    ->first();

        $qtys       = $hmm->qty_pinjam;

        $hum   = DB::table('inventory_produk')
                    ->select('qty')
                    ->where('id_barang',$id_barang)
                    ->first();

        $qtyd       = $hum->qty;

        /*$update_qty = Tech_asset_transaction::firstOrNew(array('id_barang' => $id_barang));
        $update_qty->qty_awal     = $qtyd - $qtys;
        $update_qty->save();
*/
        $update_asset       = Inventory::where('id_barang',$id_barang)->first();
        $update_asset->qty  = $qtyd + $qtys;
        $update_asset->update();
                

        $update         = WarehouseAssetTransaction::where('id_transaksi',$id_transaction)->first();
        $update->status = 'REJECT';
        $update->note   = $request['note'];
        $update->update();

        return redirect()->back()->with('danger', 'Peminjaman Telah di Reject!');; 
    }

    public function ambil(Request $request)
    {

        $id_barang = $request['id_transaction_ambil'];

        $id_transaction   = $request['id_barang_ambil'];

        $update_ambil             = WarehouseAssetTransaction::where('id_transaksi',$id_transaction)->first();
        $update_ambil->status     = 'AMBIL';
        $update_ambil->update();

        return redirect()->back()->with('update', 'Barang Telah di Ambil !');; 
    }

    public function kembali(Request $request)
    {
        $id_barang = $request['id_transaction_kembali'];

        $id_transaction   = $request['id_barang_kembali'];

        $hmm   = DB::table('inventory_asset_transaction')
                    ->select('qty_pinjam')
                    ->where('id_transaksi',$id_transaction)
                    ->first();

        $qtys       = $hmm->qty_pinjam;

        $hum   = DB::table('inventory_produk')
                    ->select('qty')
                    ->where('id_barang',$id_barang)
                    ->first();

        $qtyd       = $hum->qty;

        $update_asset       = Inventory::where('id_barang',$id_barang)->first();
        $update_asset->qty  = $qtyd + $qtys;
        $update_asset->update();
                

        $update         = WarehouseAssetTransaction::where('id_transaksi',$id_transaction)->first();
        $update->status = 'RETURN';
        $update->update();

        return redirect()->back()->with('success', 'Barang Telah di Kembalikan !');
    }


    public function index_msp(Request $request)
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

            $notifc = count($notif);
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $notif = DB::table('sales_lead_register')
            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->select('sales_lead_register.opp_name','sales_solution_design.nik')
            ->where('result','')
            ->orderBy('sales_lead_register.created_at','desc')
            ->get();

            $notifc = count($notif);
        }else{
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();

            $notifc = count($notif);
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

        $assetsd    = DB::table('inventory_asset_transaction_msp')
                    ->join('users','users.nik','=','inventory_asset_transaction_msp.nik_peminjam')
                    ->join('inventory_produk_msp','inventory_produk_msp.id_barang','=','inventory_asset_transaction_msp.id_barang')
                    ->select('inventory_asset_transaction_msp.id_transaksi','inventory_asset_transaction_msp.id_barang','inventory_produk_msp.nama','inventory_asset_transaction_msp.qty_pinjam','inventory_asset_transaction_msp.keterangan','users.name','inventory_asset_transaction_msp.qty_awal','inventory_asset_transaction_msp.nik_peminjam','inventory_asset_transaction_msp.status', 'tgl_peminjaman', 'tgl_pengembalian')
                    ->get();

        $pinjaman = DB::table('inventory_asset_transaction_msp')
                    ->join('users','users.nik','=','inventory_asset_transaction_msp.nik_peminjam')
                    ->join('inventory_produk_msp','inventory_produk_msp.id_barang','=','inventory_asset_transaction_msp.id_barang')
                    ->select('inventory_asset_transaction_msp.id_transaksi','inventory_asset_transaction_msp.id_barang','inventory_produk_msp.nama','inventory_asset_transaction_msp.qty_pinjam','inventory_asset_transaction_msp.keterangan','users.name','inventory_asset_transaction_msp.qty_awal','inventory_asset_transaction_msp.nik_peminjam','inventory_asset_transaction_msp.status', 'tgl_peminjaman', 'tgl_pengembalian', 'inventory_asset_transaction_msp.note')
                    ->where('nik_peminjam', Auth::User()->nik)
                    ->get();

        // $id_barang = $request['id_barang'];

        $asset = DB::table('inventory_produk_msp')
                ->select('nama','inventory_produk_msp.kategori', 'inventory_produk_msp.tipe', 'qty','inventory_produk_msp.note','inventory_produk_msp.id_barang','inventory_produk_msp.id_po', 'kode_barang', 'unit')
                ->get();

        $peminjam = DB::table('users')
                    ->select('name', 'nik')
                    ->where('id_company', '2')
                    ->get();

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
        }elseif (Auth::User()->id_company == '2') {
            $notifem = DB::table('users')
            ->select('name','nik')
            ->where('status_delete','D')
            ->get();
        }elseif ($ter != null) {
            $notifem = DB::table('users')
            ->select('name','nik')
            ->where('status_delete','D')
            ->get();
        }elseif ($div == 'TECHNICAL' && $pos == 'MANAGER') {
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

        return view('gudang.asset.asset_msp', compact('notifc','pinjaman','kembali','assets','assetsd','asset','lead','notif','notifOpen','notifsd','notiftp','notifc','notifem', 'peminjaman', 'peminjam', 'list_pinjaman'));
    }

    public function peminjaman_msp(Request $request)
    {
        $id_barang = $request['id_barang'];

        $qty_pinjam = DB::table('inventory_produk_msp')
                    ->select('qty')
                    ->where('id_barang',$id_barang)
                    ->first();

        $qtys       = $qty_pinjam->qty;

        $qtyd       = $request['quantity'];

        $update = Inventory_msp::where('id_barang',$id_barang)->first();

        if ($qtys >= $request['quantity']) {
            $update->qty = $qtys - $qtyd;
        }else{
            return back()->with('warning','Kebutuhan melebihi Stock!');
        }
        $update->update();  

/*      $store = Tech_asset_transaction::firstOrNew(array('nik_peminjam' => $nik_peminjam));*/
        $store_trans                   = new WarehouseAssetTransactionMSP();
        $store_trans->id_barang        = $id_barang; 
        // if(Auth::User()->id_division == 'WAREHOUSE'){
        //     $store->nik_peminjam = $request['peminjam'];
        // }else {
        $store_trans->nik_peminjam     = Auth::User()->nik;
        // }
        $store_trans->qty_pinjam       = $request['quantity'];
        $store_trans->tgl_pengembalian = $request['tgl_kembali'];
        $store_trans->tgl_peminjaman   = $request['tgl_peminjaman'];
        $store_trans->qty_awal         = $qtys;
        $store_trans->status           = 'PENDING';
        $store_trans->keterangan       = $request['description'];
        $store_trans->save();

        /*for ($i=0; $i < $asset; $i++) { 
            $update         = Detail_inventory::where('id_barang',$id_barang)->first();
            $update->status = 'ASSET';
            $update->update();
        } */       

        return redirect()->back()->with('update', 'Peminjaman Akan di Proses!');
    }

    public function detail_asset_msp($id_barang)
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

            $notifc = count($notif);
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $notif = DB::table('sales_lead_register')
            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->select('sales_lead_register.opp_name','sales_solution_design.nik')
            ->where('result','')
            ->orderBy('sales_lead_register.created_at','desc')
            ->get();

            $notifc = count($notif);
        }else{
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();

            $notifc = count($notif);
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
        }elseif (Auth::User()->id_company == '2') {
            $notifem = DB::table('users')
            ->select('name','nik')
            ->where('status_delete','D')
            ->get();
        }elseif ($ter != null) {
            $notifem = DB::table('users')
            ->select('name','nik')
            ->where('status_delete','D')
            ->get();
        }elseif ($div == 'TECHNICAL' && $pos == 'MANAGER') {
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

        $detail_asset = DB::table('inventory_asset_transaction_msp')
                        ->join('users', 'users.nik', '=', 'inventory_asset_transaction_msp.nik_peminjam')
                        ->join('inventory_produk_msp', 'inventory_asset_transaction_msp.id_barang', '=', 'inventory_produk_msp.id_barang')
                        ->select('users.name', 'tgl_peminjaman', 'tgl_pengembalian', 'inventory_asset_transaction_msp.keterangan', 'inventory_produk_msp.id_barang', 'inventory_asset_transaction_msp.id_transaksi', 'inventory_asset_transaction_msp.status', 'nama', 'tipe', 'kategori', 'qty_pinjam', 'inventory_asset_transaction_msp.note')
                        ->where('inventory_produk_msp.id_barang', $id_barang)
                        ->get();

        return view('gudang.asset.detail_asset_msp', compact('notifc','lead','notif','notifOpen','notifsd','notiftp','notifc','notifem','detail_asset'));
    }

    public function accept_pinjam_msp(Request $request)
    {
        $id_transaction = $request['id_transaction_update'];

        $id_barang   = $request['id_barang_update'];                

        $update             = WarehouseAssetTransactionMSP::where('id_transaksi',$id_transaction)->first();
        $update->status     = 'ACCEPT';
        $update->update();

        return redirect()->back()->with('success', 'Peminjaman Telah di verifikasi!');; 
    }

    public function reject_msp(Request $request)
    {
        $id_transaction = $request['id_transaction_reject'];

        $id_barang   = $request['id_barang_reject'];

        $hmm   = DB::table('inventory_asset_transaction_msp')
                    ->select('qty_pinjam')
                    ->where('id_transaksi',$id_transaction)
                    ->first();

        $qtys       = $hmm->qty_pinjam;

        $hum   = DB::table('inventory_produk_msp')
                    ->select('qty')
                    ->where('id_barang',$id_barang)
                    ->first();

        $qtyd       = $hum->qty;

        /*$update_qty = Tech_asset_transaction::firstOrNew(array('id_barang' => $id_barang));
        $update_qty->qty_awal     = $qtyd - $qtys;
        $update_qty->save();
*/
        $update_asset       = Inventory_msp::where('id_barang',$id_barang)->first();
        $update_asset->qty  = $qtyd + $qtys;
        $update_asset->update();
                

        $update         = WarehouseAssetTransactionMSP::where('id_transaksi',$id_transaction)->first();
        $update->status = 'REJECT';
        $update->note   = $request['note'];
        $update->update();

        return redirect()->back()->with('danger', 'Peminjaman Telah di Reject!');; 
    }

    public function kembali_msp(Request $request)
    {
        $id_barang = $request['id_transaction_kembali'];

        $id_transaction   = $request['id_barang_kembali'];

        $hmm   = DB::table('inventory_asset_transaction_msp')
                    ->select('qty_pinjam')
                    ->where('id_transaksi',$id_transaction)
                    ->first();

        $qtys       = $hmm->qty_pinjam;

        $hum   = DB::table('inventory_produk_msp')
                    ->select('qty')
                    ->where('id_barang',$id_barang)
                    ->first();

        $qtyd       = $hum->qty;

        $update_asset       = Inventory_msp::where('id_barang',$id_barang)->first();
        $update_asset->qty  = $qtyd + $qtys;
        $update_asset->update();
                

        $update         = WarehouseAssetTransactionMSP::where('id_transaksi',$id_transaction)->first();
        $update->status = 'RETURN';
        $update->update();

        return redirect()->back()->with('success', 'Barang Telah di Kembalikan !');
    }
}
