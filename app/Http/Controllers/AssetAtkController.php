<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\AssetAtk;
use App\AssetAtkTransaction;
use App\User;

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
                ->select('nama_barang', 'id_barang', 'name', 'qty', 'description')
                ->get();

        $assetsd    = DB::table('tb_asset_atk_transaction')
                    ->join('users','users.nik','=','tb_asset_atk_transaction.nik_peminjam')
                    ->join('tb_asset_atk','tb_asset_atk.id_barang','=','tb_asset_atk_transaction.id_barang')
                    ->select('tb_asset_atk_transaction.id_transaction','tb_asset_atk_transaction.id_barang','tb_asset_atk.nama_barang','tb_asset_atk_transaction.qty_akhir','tb_asset_atk.description','users.name','tb_asset_atk.qty','tb_asset_atk_transaction.nik_peminjam','tb_asset_atk_transaction.status', 'tb_asset_atk_transaction.keterangan','no_transac', 'tb_asset_atk_transaction.created_at', 'tb_asset_atk_transaction.note')
                    ->get();

        $pinjaman = DB::table('tb_asset_atk_transaction')
                    ->join('users','users.nik','=','tb_asset_atk_transaction.nik_peminjam')
                    ->join('tb_asset_atk','tb_asset_atk.id_barang','=','tb_asset_atk_transaction.id_barang')
                    ->select('tb_asset_atk.description','tb_asset_atk_transaction.nik_peminjam','tb_asset_atk_transaction.id_transaction','tb_asset_atk_transaction.id_barang','users.name','tb_asset_atk_transaction.qty_akhir','tb_asset_atk_transaction.created_at','tb_asset_atk_transaction.updated_at','tb_asset_atk.nama_barang','tb_asset_atk_transaction.status', 'no_transac', 'tb_asset_atk_transaction.keterangan', 'tb_asset_atk_transaction.note')
                    ->where('tb_asset_atk_transaction.nik_peminjam',Auth::User()->nik)
                    ->get();

        $atk = AssetAtk::select('id_barang','nama_barang')->get();

    	return view('HR/asset_atk',compact('notif', 'notifc', 'notifsd', 'notiftp', 'notifOpen', 'notifClaim', 'asset', 'assetsd', 'pinjaman','atk'));
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
        $tambah->save();

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

    	$count_qty = AssetAtk::select('qty')->where('id_barang', $request->atk)->first();
        $qty_akhir = $request['quantity'];

    	$inc = DB::table('tb_asset_atk_transaction')->select('id_transaction')->get();
        $increment = count($inc);
        $nomor = $increment+1;
        if($nomor < 10){
            $nomor = '00' . $nomor;
        }

        $no_peminjaman = date('ymd').$nomor;

        $store                   = new AssetAtkTransaction();
        $store->no_transac	 	 = $no_peminjaman;
        $store->id_barang		 = $request['atk'];
		$store->nik_peminjam	 = Auth::User()->nik;
		$store->qty_akhir 		 = $qty_akhir;
        $store->qty_awal         = $count_qty->qty;
        $store->keterangan       = $request['keterangan'];
        $store->status           = 'PENDING';
		$store->save();


        $update         = AssetAtk::where('id_barang', $request->atk)->first();
        $update->qty    = $count_qty->qty - $qty_akhir;
        $update->update();

		return redirect()->back()->with('update', 'Request ATK akan diproses!');
    }

    public function accept_request(Request $request)
    {
    	$id_barang = $request['id_barang_update'];
    	$id_transaction = $request['id_transaction_update'];
    	$qty = $request['qty_awal_accept'];
    	$qty_akhir = $request['qty_akhir_accept'];

    	$update             = AssetAtkTransaction::where('id_transaction',$id_transaction)->first();
        $update->status     = 'ACCEPT';
        $update->update();

        /*$qty_awal = AssetAtk::select('qty')->where('id_barang', $id_barang)->first();
        $qty_pinjam = AssetAtkTransaction::select('qty_akhir')->where('id_transaction', $id_transaction)->first();

       	$update_qty = AssetAtk::where('id_barang', $id_barang)->first();
       	$update_qty->qty = $qty - $qty_akhir;
       	$update_qty->update();*/

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

        $update_qty         = AssetAtk::where('id_barang', $id_barang)->first();
        $update_qty->qty    = $qty_akhir + $qty;
        $update_qty->update();

        return redirect()->back()->with('update', 'Successfully!');
    }
}
