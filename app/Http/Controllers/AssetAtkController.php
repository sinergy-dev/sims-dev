<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\AssetAtk;
use App\AssetAtkTransaction;
use App\User;
use App\Mail\RequestATK;
use Mail;

class AssetAtkController extends Controller
{
    public function index()
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

        $asset = DB::table('tb_asset_atk')->join('users', 'users.nik', '=', 'tb_asset_atk.nik')
                ->select('nama_barang', 'tb_asset_atk.id_barang', 'name', 'qty', 'description','status', 'unit')
                ->get();

        $assetsd    = DB::table('tb_asset_atk_transaction')
                    ->join('users','users.nik','=','tb_asset_atk_transaction.nik_peminjam')
                    ->join('tb_asset_atk','tb_asset_atk.id_barang','=','tb_asset_atk_transaction.id_barang')
                    ->select('tb_asset_atk_transaction.id_transaction','tb_asset_atk_transaction.id_barang','tb_asset_atk.nama_barang','tb_asset_atk_transaction.qty_akhir','tb_asset_atk.description','users.name','tb_asset_atk.qty','tb_asset_atk_transaction.nik_peminjam','tb_asset_atk_transaction.status', 'tb_asset_atk_transaction.keterangan','no_transac', 'tb_asset_atk_transaction.created_at', 'tb_asset_atk_transaction.note', 'qty_request')
                    ->where('tb_asset_atk_transaction.status', 'PENDING')
                    ->orWhere('tb_asset_atk_transaction.status', 'ACCEPT')
                    ->orWhere('tb_asset_atk_transaction.status', 'REJECT')
                    ->orderBy('created_at', 'desc')
                    ->get();

        $pr_request    = DB::table('tb_asset_atk_transaction')
                    ->join('users','users.nik','=','tb_asset_atk_transaction.nik_peminjam')
                    ->join('tb_asset_atk','tb_asset_atk.id_barang','=','tb_asset_atk_transaction.id_barang')
                    ->select('tb_asset_atk_transaction.id_transaction','tb_asset_atk_transaction.id_barang','tb_asset_atk.nama_barang','tb_asset_atk_transaction.qty_akhir','tb_asset_atk.description','users.name','tb_asset_atk.qty','tb_asset_atk_transaction.nik_peminjam','tb_asset_atk_transaction.status', 'tb_asset_atk_transaction.keterangan','no_transac', 'tb_asset_atk_transaction.created_at', 'tb_asset_atk_transaction.note', 'qty_request')
                    ->where('tb_asset_atk_transaction.status', 'PROSES')
                    ->orWhere('tb_asset_atk_transaction.status', 'DONE')
                    ->orderBy('created_at', 'desc')
                    ->get();

        $pinjaman = DB::table('tb_asset_atk_transaction')
                    ->join('users','users.nik','=','tb_asset_atk_transaction.nik_peminjam')
                    ->join('tb_asset_atk','tb_asset_atk.id_barang','=','tb_asset_atk_transaction.id_barang')
                    ->select('tb_asset_atk.description','tb_asset_atk_transaction.nik_peminjam','tb_asset_atk_transaction.id_transaction','tb_asset_atk_transaction.id_barang','users.name','tb_asset_atk_transaction.qty_akhir','tb_asset_atk_transaction.created_at','tb_asset_atk_transaction.updated_at','tb_asset_atk.nama_barang','tb_asset_atk_transaction.status', 'no_transac', 'tb_asset_atk_transaction.keterangan', 'tb_asset_atk_transaction.note', 'qty_request', 'qty_awal')
                    ->where('tb_asset_atk_transaction.nik_peminjam',Auth::User()->nik)
                    ->get();

        $pr_request2 = DB::table('tb_asset_atk_transaction')
                    ->join('users','users.nik','=','tb_asset_atk_transaction.nik_peminjam')
                    ->join('tb_asset_atk','tb_asset_atk.id_barang','=','tb_asset_atk_transaction.id_barang')
                    ->select('tb_asset_atk.description','tb_asset_atk_transaction.nik_peminjam','tb_asset_atk_transaction.id_transaction','tb_asset_atk_transaction.id_barang','users.name','tb_asset_atk_transaction.qty_akhir','tb_asset_atk_transaction.created_at','tb_asset_atk_transaction.updated_at','tb_asset_atk.nama_barang','tb_asset_atk_transaction.status', 'no_transac', 'tb_asset_atk_transaction.keterangan', 'tb_asset_atk_transaction.note', 'qty_request')
                    ->where('tb_asset_atk_transaction.nik_peminjam',Auth::User()->nik)
                    ->get();

        $atk = AssetAtk::select('id_barang','nama_barang')->get();

        $unit_assets = AssetAtk::select('unit')->groupBy('unit')->get();
        // return $unit;

        $cek = AssetAtk::join('tb_asset_atk_transaction', 'tb_asset_atk_transaction.id_barang', '=', 'tb_asset_atk.id_barang', 'left')->select('tb_asset_atk_transaction.id_barang')->get();
        // return $cek;

    	return view('HR/asset_atk',compact('notif', 'notifc', 'notifsd', 'notiftp', 'notifOpen', 'notifClaim', 'asset', 'assetsd', 'pinjaman', 'atk', 'cek', 'pr_request', 'pr_request2', 'unit_assets'));
    }

    public function detail($id_barang)
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

        $asset = DB::table('tb_asset_atk_transaction')
                    ->join('users','users.nik','=','tb_asset_atk_transaction.nik_peminjam')
                    ->join('tb_asset_atk','tb_asset_atk.id_barang','=','tb_asset_atk_transaction.id_barang')
                    ->select('tb_asset_atk.description','tb_asset_atk_transaction.nik_peminjam','tb_asset_atk_transaction.id_transaction','tb_asset_atk_transaction.id_barang','users.name','tb_asset_atk_transaction.qty_akhir','tb_asset_atk_transaction.created_at','tb_asset_atk_transaction.updated_at','tb_asset_atk.nama_barang','tb_asset_atk_transaction.status', 'no_transac', 'tb_asset_atk_transaction.keterangan', 'tb_asset_atk_transaction.note')
                    ->where('tb_asset_atk.id_barang',$id_barang)
                    ->orderBy('tb_asset_atk_transaction.created_at', 'desc')
                    ->get();

        return view('HR/detail_asset_atk',compact('notif', 'notifc', 'notifsd', 'notiftp', 'notifOpen', 'notifClaim', 'asset'));
    }

    public function store(Request $request)
    {
    	$tambah                 = new AssetAtk();
        $tambah->nik            = Auth::User()->nik;
        $tambah->nama_barang    = $request['nama_barang'];
        $tambah->qty            = $request['qty'];
        $tambah->description    = $request['keterangan'];
        $tambah->status         = 'NEW';
        $tambah->unit           = $request['unit'];
        $tambah->save();

        return redirect()->back();
    }

    public function update_stok(Request $request)
    {
        $id_barang = $request['id_barang_restok'];
        $qty_awal = AssetAtk::select('qty')->where('id_barang', $id_barang)->first();

        $update = AssetAtk::where('id_barang', $id_barang)->first();
        $update->qty = $qty_awal->qty + $request['qty_masuk_restok'];
        $update->update();

        return redirect()->back();
    }

    public function getqtyatk(Request $request)
    {
    	$atk = $request['atk'];

        return array(DB::table('tb_asset_atk')
            ->select('qty', 'id_barang')
            ->where('id_barang', $request->atk)
            ->get(),$request->atk);  
    }

    public function request_atk(Request $request)
    {

    	$count_qty = AssetAtk::select('qty','status')->where('id_barang', $request->atk)->first();
        $qty_akhir = $request['quantity'];
        // $id_barang = $request['id_barang_atk'];

    	$inc = DB::table('tb_asset_atk_transaction')->select('id_transaction')->get();
        $increment = count($inc);
        $nomor = $increment+1;
        if($nomor < 10){
            $nomor = '00' . $nomor;
        }

        $no_peminjaman = date('ymd').$nomor;

        /*$store                   = new AssetAtkTransaction();
        $store->no_transac	 	 = $no_peminjaman;
        $store->id_barang		 = $request['atk'];
		$store->nik_peminjam	 = Auth::User()->nik;
		$store->qty_akhir 		 = $qty_akhir;
        $store->qty_awal         = $count_qty->qty;
        $store->keterangan       = $request['keterangan'];
        $store->status           = 'PENDING';
		$store->save();*/


        if ($count_qty->qty == 0 ) {
            $store                   = new AssetAtkTransaction();
            $store->no_transac       = $no_peminjaman;
            $store->id_barang        = $request['atk'];
            $store->nik_peminjam     = Auth::User()->nik;
            $store->qty_request      = $qty_akhir;
            $store->qty_awal         = $count_qty->qty;
            $store->keterangan       = $request['keterangan'];
            $store->status           = 'PROSES';
            $store->save();
        } elseif ($count_qty->qty < $qty_akhir) {
            $store                   = new AssetAtkTransaction();
            $store->no_transac       = $no_peminjaman;
            $store->id_barang        = $request['atk'];
            $store->nik_peminjam     = Auth::User()->nik;
            $store->qty_awal         = $count_qty->qty;
            // $store->qty_akhir        = $count_qty->qty;
            $store->qty_request      = $qty_akhir;
            $store->keterangan       = $request['keterangan'];
            $store->status           = 'PROSES';
            $store->save();
        } else {
            $store                   = new AssetAtkTransaction();
            $store->no_transac       = $no_peminjaman;
            $store->id_barang        = $request['atk'];
            $store->nik_peminjam     = Auth::User()->nik;
            $store->qty_akhir        = $qty_akhir;
            $store->qty_awal         = $count_qty->qty;
            $store->keterangan       = $request['keterangan'];
            $store->status           = 'PENDING';
            $store->save();
        }

        if ($count_qty->status == 'NEW') {
            $update         = AssetAtk::where('id_barang', $request->atk)->first();
            $update->qty    = $count_qty->qty - $qty_akhir;
            $update->status = 'NN';
            $update->update();
        } elseif ($count_qty->qty == 0) {
            $update         = AssetAtk::where('id_barang', $request->atk)->first();
            $update->qty    = 0;
            $update->update();
        } else {
            $update         = AssetAtk::where('id_barang', $request->atk)->first();
            $update->qty    = $count_qty->qty - $qty_akhir;
            // $update->status = 'NN';
            $update->update();
        }

        $get_id_transac = AssetAtkTransaction::select('id_transaction')->where('id_barang', $request['atk'])->orderBy('created_at','desc')->first();

        $req_atk = AssetAtk::join('tb_asset_atk_transaction', 'tb_asset_atk.id_barang','=', 'tb_asset_atk_transaction.id_barang')
                    ->join('users', 'tb_asset_atk_transaction.nik_peminjam', '=', 'users.nik')
                    ->select('nama_barang', 'qty_akhir', 'qty_request', 'tb_asset_atk_transaction.status', 'name', 'keterangan', 'tb_asset_atk_transaction.created_at')
                    ->where('tb_asset_atk_transaction.id_transaction', $get_id_transac->id_transaction)
                    ->first();
        // return $req_atk;

        Mail::to('franki@sinergy.co.id')->cc('yudhi@sinergy.co.id')->send(new RequestATK('[SIMS-App] Request ATK', $req_atk));
        

		return redirect()->back()->with('update', 'Request ATK akan diproses!');
    }

    public function accept_request(Request $request)
    {
    	$id_barang = $request['id_barang_update'];
    	$id_transaction = $request['id_transaction_update'];
    	$qty = $request['qty_awal_accept'];
    	$qty_akhir = $request['qty_akhir_accept'];

        $cek_status = AssetAtkTransaction::select('id_barang', 'id_transaction')->where('id_barang', $id_barang)->where('status', 'PENDING')->where('id_transaction', '!=', $id_transaction)->first();
        $count_status = AssetAtkTransaction::select('id_barang', 'id_transaction')->where('id_barang', $id_barang)->where('status', 'PENDING')->where('id_transaction', '!=', $id_transaction)->count();

        $cek_qty = AssetAtkTransaction::select('qty_awal', 'qty_akhir')->where('id_transaction', $id_transaction)->where('id_barang', $id_barang)->first();
        // $cek_qty2 = AssetAtkTransaction::select('qty_awal', 'qty_akhir')->where('id_transaction', '!=' , $id_transaction)->where('id_barang', $id_barang)->first();

        /*if ($count_status > 0) {
            $update_qty_transaction = AssetAtkTransaction::where('id_transaction',$cek_status->id_transaction)->first();
            $update_qty_transaction->qty_awal = $qty - $qty_akhir;;
            $update_qty_transaction->update();
        }*/
        

        /*if ($cek_qty->qty_akhir > $cek_qty->qty_awal) {
            $update_status = AssetAtkTransaction::where('id_transaction', $id_transaction)->first();
            $update_status->status = 'PROSES';
            $update_status->qty_request = $cek_qty->qty_akhir - $cek_qty->qty_awal;
            $update_status->update();

            $update_qty_asset = AssetAtk::where('id_barang', $id_barang)->first();
            $update_qty_asset->qty = '0';
            $update_qty_asset->update();
        } else {*/
            $update             = AssetAtkTransaction::where('id_transaction',$id_transaction)->first();
            $update->status     = 'ACCEPT';
            $update->update();

            /*$update_qty = AssetAtk::where('id_barang', $id_barang)->first();
            $update_qty->qty = $qty - $qty_akhir;
            $update_qty->update();
        }*/

        $kirim = AssetAtkTransaction::join('users', 'users.nik', '=', 'tb_asset_atk_transaction.nik_peminjam')->select('users.email')->where('id_transaction',$id_transaction)->first();

        $req_atk = AssetAtk::join('tb_asset_atk_transaction', 'tb_asset_atk.id_barang','=', 'tb_asset_atk_transaction.id_barang')
                    ->join('users', 'tb_asset_atk_transaction.nik_peminjam', '=', 'users.nik')
                    ->select('nama_barang', 'qty_akhir', 'qty_request', 'tb_asset_atk_transaction.status', 'name', 'keterangan', 'tb_asset_atk_transaction.created_at')
                    ->where('tb_asset_atk_transaction.id_transaction', $id_transaction)
                    ->first();
        // return $req_atk;

        Mail::to($kirim)->cc('yudhi@sinergy.co.id')->send(new RequestATK('[SIMS-App] Approval Request ATK', $req_atk));

       	return redirect()->back()->with('update', 'Successfully!');
    }

    public function edit_atk(Request $request)
    {
    	$id_barang = $request['id_barang_edit'];

    	$update = AssetAtk::where('id_barang', $id_barang)->first();
    	$update->nama_barang = $request['nama_barang_edit'];
    	$update->description = $request['deskripsi_edit'];
    	$update->update();

    	return redirect()->back()->with('update', 'Successfully!');
    }

    public function reject_request(Request $request)
    {
        $id_barang = $request['id_barang_reject'];
        $id_transaction = $request['id_transaction_reject'];
        $qty = $request['qty_awal_reject'];
        $qty_akhir = $request['qty_akhir_reject'];

        $update             = AssetAtkTransaction::where('id_transaction',$id_transaction)->first();
        $update->status     = 'REJECT';
        $update->note       = $request['note_reject'];
        $update->update();

        /*$update_qty         = AssetAtk::where('id_barang', $id_barang)->first();
        $update_qty->qty    = $qty_akhir + $qty;
        $update_qty->update();*/

        $kirim = AssetAtkTransaction::join('users', 'users.nik', '=', 'tb_asset_atk_transaction.nik_peminjam')->select('users.email')->where('id_transaction',$id_transaction)->first();

        $req_atk = AssetAtk::join('tb_asset_atk_transaction', 'tb_asset_atk.id_barang','=', 'tb_asset_atk_transaction.id_barang')
                    ->join('users', 'tb_asset_atk_transaction.nik_peminjam', '=', 'users.nik')
                    ->select('nama_barang', 'qty_akhir', 'qty_request', 'tb_asset_atk_transaction.status', 'name', 'note', 'tb_asset_atk_transaction.created_at')
                    ->where('tb_asset_atk_transaction.id_transaction', $id_transaction)
                    ->first();
        // return $req_atk;

        Mail::to($kirim)->cc('yudhi@sinergy.co.id')->send(new RequestATK('[SIMS-App] Reject Request ATK', $req_atk));

        return redirect()->back()->with('update', 'Successfully!');
    }

    public function done_request_pr(Request $request)
    {
        $id_transaction = $request['id_transaction_done'];
        $id_barang = $request['id_barang_done'];
        $qty = $request['qty_done'];
        $qty_request = $request['qty_request_done'];
        $qty_restock = $request['qty_restock_pr'];

        /*if ($qty == 0) {
            return back()->with('qty-done','Quantity Habis');
        } else {*/
            $update = AssetAtkTransaction::where('id_transaction', $id_transaction)->first();
            $update->status = 'DONE';
            $update->update();

            $update_qty = AssetAtk::where('id_barang', $id_barang)->first();
            $update_qty->qty = $qty_restock - $qty_request;
            $update_qty->update();

        // }

        
        return redirect()->back()->with('update', 'Successfully!');
    }
}
