<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use DB;
use App\AssetHR;
use App\DetailAssetHR;
use App\User;


class AssetHRController extends Controller
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

        $asset = DB::table('tb_asset_hr')->join('users', 'users.nik', '=', 'tb_asset_hr.nik')
                ->select('nama_barang', 'id_barang', 'name', 'qty', 'description')
                ->get();

        $assetsd    = DB::table('tb_asset_hr_transaction')
                    ->join('users','users.nik','=','tb_asset_hr_transaction.nik_peminjam')
                    ->join('tb_asset_hr','tb_asset_hr.id_barang','=','tb_asset_hr_transaction.id_barang')
                    ->select('tb_asset_hr_transaction.id_transaction','tb_asset_hr_transaction.id_barang','tb_asset_hr.nama_barang','tb_asset_hr_transaction.qty_akhir','tb_asset_hr.description','users.name','tb_asset_hr.qty','tb_asset_hr_transaction.nik_peminjam','tb_asset_hr_transaction.status', 'tb_asset_hr_transaction.keterangan', 'tgl_pengembalian', 'tgl_peminjaman', 'no_transac')
                    ->get();

        $pinjaman = DB::table('tb_asset_hr_transaction')
                    ->join('users','users.nik','=','tb_asset_hr_transaction.nik_peminjam')
                    ->join('tb_asset_hr','tb_asset_hr.id_barang','=','tb_asset_hr_transaction.id_barang')
                    ->select('tb_asset_hr.description','tb_asset_hr_transaction.nik_peminjam','tb_asset_hr_transaction.id_transaction','tb_asset_hr_transaction.id_barang','users.name','tb_asset_hr_transaction.qty_akhir','tb_asset_hr_transaction.created_at','tb_asset_hr_transaction.updated_at','tb_asset_hr.nama_barang','tb_asset_hr_transaction.status', 'no_transac')
                    ->where('tb_asset_hr_transaction.nik_peminjam',Auth::User()->nik)
                    ->get();

        $users = User::select('name','nik')->get();

    	return view('HR/asset_hr',compact('notif', 'notifc', 'notifsd', 'notiftp', 'notifOpen', 'notifClaim', 'asset', 'assetsd', 'pinjaman','users'));
    }

    public function getdetail(Request $request)
    {
    	$id_transaction = $request['btn_accept'];

        return array(DB::table('tb_asset_hr_transaction')
        		->join('tb_asset_hr', 'tb_asset_hr_transaction.id_barang', '=', 'tb_asset_hr.id_barang')
                ->select('nama_barang', 'qty_akhir')
                ->where('id_transaction',$request->id_transaction)
                ->get(),$request->id_transaction);
    }

    public function getdetail2(Request $request)
    {
    	$id_transaction = $request['btn_reject'];

        return array(DB::table('tb_asset_hr_transaction')
        		->join('tb_asset_hr', 'tb_asset_hr_transaction.id_barang', '=', 'tb_asset_hr.id_barang')
                ->select('nama_barang', 'qty_akhir')
                ->where('id_transaction',$request->id_transaction)
                ->get(),$request->id_transaction);
    }

    public function store(Request $request)
    {
    	$tambah                 = new AssetHR();
        $tambah->nik            = Auth::User()->nik;
        $tambah->nama_barang    = $request['nama_barang'];
        $tambah->qty            = $request['qty'];
        $tambah->description    = $request['keterangan'];
        $tambah->save();

        return redirect()->back();
    }

    public function detail_asset($id_barang)
    {
        $asset = DB::table('tb_asset_hr_transaction')
                ->join('users','users.nik','=','tb_asset_hr_transaction.nik_peminjam')
                ->join('tb_asset_hr','tb_asset_hr.id_barang','=','tb_asset_hr_transaction.id_barang')
                ->select('tb_asset_hr_transaction.nik_peminjam','tb_asset_hr_transaction.id_transaction','tb_asset_hr_transaction.id_barang','users.name','tb_asset_hr_transaction.qty_akhir','tb_asset_hr.nama_barang','tb_asset_hr_transaction.status', 'tb_asset_hr_transaction.tgl_peminjaman', 'tb_asset_hr_transaction.tgl_pengembalian', 'tb_asset_hr_transaction.keterangan', 'tb_asset_hr_transaction.note', 'no_transac')
                ->where('tb_asset_hr_transaction.id_barang',$id_barang)
                ->get();

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

        return view('HR.detail_asset_peminjaman',compact('asset','notif','notifOpen','notifsd','notiftp','notifc', 'notifClaim'));
    }

    public function peminjaman(Request $request)
    {
        $id_barang = $request['id_barang'];

        $qty_pinjam = DB::table('tb_asset_hr')
                    ->select('qty')
                    ->where('id_barang',$id_barang)
                    ->first();

        $qtys       = $qty_pinjam->qty;

        $qtyd       = $request['quantity'];

        $update = AssetHR::where('id_barang',$id_barang)->first();

        if ($qtys >= $request['quantity']) {
            $update->qty = $qtys - $qtyd;
        }else{
            return back()->with('warning','Kebutuhan melebihi Stock!');
        }
        $update->update();  

        $inc = DB::table('tb_asset_hr_transaction')->select('id_transaction')->get();
        $increment = count($inc);
        $nomor = $increment+1;
        if($nomor < 10){
            $nomor = '00' . $nomor;
        }

        $no_peminjaman = date('ymd').$nomor;

        $store                  = new DetailAssetHR();
        $store->id_barang       = $id_barang; 
        $store->nik_peminjam    = $request['users'];
        $store->qty_akhir       = $request['quantity'];
        $store->qty_awal        = $qtys;
        $store->status          = 'PENDING';
        $store->keterangan      = $request['keperluan'];
        $store->tgl_peminjaman  = date('Y-m-d');
        $store->no_transac		= $no_peminjaman;
        $store->save();

        return redirect()->back()->with('alert', 'Peminjaman Akan di Proses!');
    }

    public function accept_pinjam(Request $request)
    {
        $id_transaction = $request['id_transaction_update'];

        $id_barang   = $request['id_barang_update'];

        $update             = DetailAssetHR::where('id_transaction',$id_transaction)->first();
        $update->status     = 'ACCEPT';
        $update->update();

        return redirect()->back()->with('success', 'Peminjaman Telah di verifikasi!');; 
    }

    public function reject_pinjam(Request $request)
    {
        $id_transaction = $request['id_transaction_reject'];

        $id_barang   = $request['id_barang_reject'];

        $hmm   = DetailAssetHR::select('qty_akhir')->where('id_transaction',$id_transaction)->first();

        $qtys       = $hmm->qty_akhir;

        $hum   = AssetHR::select('qty')->where('id_barang',$id_barang)->first();

        $qtyd       = $hum->qty;

        $update_asset       = AssetHR::where('id_barang',$id_barang)->first();
        $update_asset->qty  = $qtyd + $qtys;
        $update_asset->update();
                

        $update         = DetailAssetHR::where('id_transaction',$id_transaction)->first();
        $update->status = 'REJECT';
        $update->update();

        return redirect()->back()->with('danger', 'Peminjaman Telah di Reject!');
    }

    public function kembali(Request $request)
    {
        $id_barang = $request['id_transaction_kembali'];

        $id_transaction   = $request['id_barang_kembali'];

        $hmm   = DetailAssetHR::select('qty_akhir')->where('id_transaction',$id_transaction)->first();

        $qtys       = $hmm->qty_akhir;

        $hum   = AssetHR::select('qty')->where('id_barang',$id_barang)->first();

        $qtyd       = $hum->qty;

        $update_asset       = AssetHR::where('id_barang',$id_barang)->first();
        $update_asset->qty  = $qtyd + $qtys;
        $update_asset->update();
                

        $update         = DetailAssetHR::where('id_transaction',$id_transaction)->first();
        $update->status = 'RETURN';
        $update->tgl_pengembalian = date('Y-m-d');
        $update->update();

        return redirect()->back()->with('success', 'Barang Telah di Kembalikan !');
    }

}
