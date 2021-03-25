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
use App\AssetAtkChangelog;
use App\AssetAtkRequest;

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

        $notifClaim = '';

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
                ->select('nama_barang', 'tb_asset_atk.id_barang', 'name', 'qty', 'description','status', 'unit', 'merk')
                ->get();

        $assetsd    = DB::table('tb_asset_atk_transaction')
                    ->join('users','users.nik','=','tb_asset_atk_transaction.nik_peminjam')
                    ->join('tb_asset_atk','tb_asset_atk.id_barang','=','tb_asset_atk_transaction.id_barang')
                    ->select('tb_asset_atk_transaction.id_transaction','tb_asset_atk_transaction.id_barang','tb_asset_atk.nama_barang','tb_asset_atk_transaction.qty_akhir','tb_asset_atk.description','users.name','tb_asset_atk.qty','tb_asset_atk_transaction.nik_peminjam','tb_asset_atk_transaction.status', 'tb_asset_atk_transaction.keterangan','no_transac', 'tb_asset_atk_transaction.created_at', 'tb_asset_atk_transaction.note', 'qty_request')
                    ->where('tb_asset_atk_transaction.status', 'PENDING')
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
                    ->orderBy('created_at', 'desc')
                    ->get();

        $request = DB::table('tb_asset_atk_request')
                    ->join('users', 'users.nik', '=', 'tb_asset_atk_request.nik')
                    ->select('nama', 'status', 'qty', 'keterangan', 'tb_asset_atk_request.created_at', 'name', 'link', 'id_barang','tb_asset_atk_request.nik')
                    ->orderBy('tb_asset_atk_request.created_at','desc')
                    ->where('status', 'PROCESS')
                    ->orWhere('status', 'REQUEST')
                    ->get();

        $request2 = DB::table('tb_asset_atk_request')
                    ->join('users', 'users.nik', '=', 'tb_asset_atk_request.nik')
                    ->select('nama', 'status', 'qty', 'keterangan', 'tb_asset_atk_request.created_at', 'name', 'link', 'id_barang','tb_asset_atk_request.nik')
                    ->orderBy('tb_asset_atk_request.created_at','desc')
                    ->where('tb_asset_atk_request.nik', Auth::User()->nik)
                    ->get();

        $pr_request2 = DB::table('tb_asset_atk_transaction')
                    ->join('users','users.nik','=','tb_asset_atk_transaction.nik_peminjam')
                    ->join('tb_asset_atk','tb_asset_atk.id_barang','=','tb_asset_atk_transaction.id_barang')
                    ->select('tb_asset_atk.description','tb_asset_atk_transaction.nik_peminjam','tb_asset_atk_transaction.id_transaction','tb_asset_atk_transaction.id_barang','users.name','tb_asset_atk_transaction.qty_akhir','tb_asset_atk_transaction.created_at','tb_asset_atk_transaction.updated_at','tb_asset_atk.nama_barang','tb_asset_atk_transaction.status', 'no_transac', 'tb_asset_atk_transaction.keterangan', 'tb_asset_atk_transaction.note', 'qty_request')
                    ->where('tb_asset_atk_transaction.nik_peminjam',Auth::User()->nik)
                    ->get();

        $atk = AssetAtk::select('id_barang','nama_barang')->get();

        $unit_assets = AssetAtk::select('unit')->where('unit', '<>', null)->groupBy('unit')->get();
        // return $unit;

        $cek = AssetAtk::join('tb_asset_atk_transaction', 'tb_asset_atk_transaction.id_barang', '=', 'tb_asset_atk.id_barang', 'left')->select('tb_asset_atk_transaction.id_barang')->get();
        // return $cek;

    	return view('HR/asset_atk',compact('notif', 'notifc', 'notifsd', 'notiftp', 'notifOpen', 'notifClaim', 'asset', 'assetsd', 'pinjaman', 'atk', 'cek', 'pr_request', 'pr_request2', 'unit_assets', 'request', 'request2'));
    }

    public function getAtk(Request $request){

        return array("results" => DB::table('tb_asset_atk')->select(DB::raw("`id_barang` AS `id`,`nama_barang` AS `text`"))->get());
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

        $detail = AssetAtkChangelog::join('users', 'users.nik', '=', 'tb_asset_atk_changelog.nik')
                    ->join('tb_asset_atk', 'tb_asset_atk.id_barang', '=', 'tb_asset_atk_changelog.id_barang')
                    ->select('users.name', 'tb_asset_atk_changelog.created_at', 'tb_asset_atk_changelog.status', 'tb_asset_atk_changelog.qty', 'nama_barang', 'unit')
                    ->where('tb_asset_atk.id_barang', $id_barang)
                    ->orderBy('tb_asset_atk_changelog.id', 'desc')
                    ->get();

        $last_update = AssetAtkChangelog::join('users', 'users.nik', '=', 'tb_asset_atk_changelog.nik')->select('status','tb_asset_atk_changelog.created_at', 'name')->where('id_barang',$id_barang)->orderBy('tb_asset_atk_changelog.id','desc')->first();

        $data = AssetAtk::select('qty','unit','nama_barang')->where('id_barang',$id_barang)->first();

        $summary = AssetAtkChangelog::selectRaw('SUM(CASE WHEN `status` = "In" THEN 1 ELSE 0 END) AS `sum_in`')
            ->selectRaw('SUM(CASE WHEN `status` = "Out" THEN 1 ELSE 0 END) AS `sum_out`')
            ->selectRaw('LEFT(`created_at`, 7) AS `month`')
            ->where('id_barang', $id_barang)
            ->groupBy('month')
            ->get();

         // return $summary;   

        return view('HR/detail_asset_atk',compact('notif', 'notifc', 'notifsd', 'notiftp', 'notifOpen', 'notifClaim', 'asset', 'detail', 'data', 'last_update', 'summary'));
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
        $tambah->merk 			= $request['merk'];
        $tambah->save();

        $cek_id = AssetAtk::orderBy('id_barang','desc')->first();

        $tambah_changelog   = new AssetAtkChangelog();
        $tambah_changelog->nik = Auth::User()->nik;
        $tambah_changelog->id_barang = $cek_id->id_barang;
        $tambah_changelog->qty = $request['qty'];
        $tambah_changelog->status = 'In';
        $tambah_changelog->save();

        return redirect()->back()->with('update', 'Successfully!');
    }

    public function detail_produk_request(Request $request)
    {
        $id_barang = $request->id_barang;
        return array(DB::table('tb_asset_atk_request')
                ->join('users', 'users.nik', '=', 'tb_asset_atk_request.nik')
                ->select('nama', 'status', 'qty', 'keterangan', 'tb_asset_atk_request.created_at', 'name', 'link', 'id_barang','tb_asset_atk_request.nik')
                ->where('tb_asset_atk_request.id_barang',$id_barang)
                ->first());
    }


    public function update_stok(Request $request)
    {
        $id_barang = $request['id_barang_restok'];
        $qty_awal = AssetAtk::select('qty')->where('id_barang', $id_barang)->first();

        $update = AssetAtk::where('id_barang', $id_barang)->first();
        $update->qty = $qty_awal->qty + $request['qty_masuk_restok'];
        $update->update();

        $tambah_changelog   = new AssetAtkChangelog();
        $tambah_changelog->nik = Auth::User()->nik;
        $tambah_changelog->id_barang = $id_barang;
        $tambah_changelog->qty = $request['qty_masuk_restok'];
        $tambah_changelog->status = 'In';
        $tambah_changelog->save();

        return redirect()->back()->with('update', 'Successfully!');
    }

    public function accept_request_atk(Request $request)
    {
        $update = AssetAtkRequest::where('id_barang', $request->id_barang)->first();
        $update->status = 'PROCESS';
        $update->update();

        $kirim = AssetAtkRequest::join('users', 'users.nik', '=', 'tb_asset_atk_request.nik')->select('users.email', 'id_position')->where('id_barang',$request->id_barang)->first();

        $get_email_user = collect([$kirim]);

        $get_divisi_hr = User::select('email','id_position')->where('id_position', 'HR MANAGER')->first();

        $receiver = $get_email_user->concat([$get_divisi_hr]);

        $receiver_final = $receiver->all();

        $req_atk = AssetAtkRequest::join('users', 'tb_asset_atk_request.nik', '=', 'users.nik')
                    ->select('nama', 'qty', 'name', 'keterangan', 'status', 'link', 'tb_asset_atk_request.created_at')
                    ->where('tb_asset_atk_request.id_barang', $request->id_barang)
                    ->first();
        // return $req_atk;

        foreach ($receiver_final as $final) {
            Mail::to($final)->send(new RequestATK('[SIMS-App] Request ATK sedang diproses', $req_atk,$final->id_position,$get_email_user, 'PROCESS'));
        }

        return redirect()->back()->with('update', 'Successfully!');
    }

    public function request_done(Request $request)
    {
        $update = AssetAtkRequest::where('id_barang', $request['id_barang_done2'])->first();
        $update->status = 'DONE';
        $update->update();

        $qty_restock = $request['qty_restock_atk'];
        $qty_request = $request['qty_request_done2'];

        $tambah = new AssetAtk();
        $tambah->nik            = Auth::User()->nik;
        $tambah->nama_barang    = $request['nama_barang_done'];
        $tambah->qty            = $request['qty_restock_atk'];
        $tambah->description    = $request['keterangan_request'];
        $tambah->status         = 'NEW';
        $tambah->unit           = $request['unit_request'];
        $tambah->merk           = $request['merk_request'];
        $tambah->save();

        // $cek_id = AssetAtk::orderBy('id_barang','desc')->first();

        $tambah_changelog   = new AssetAtkChangelog();
        $tambah_changelog->nik = Auth::User()->nik;
        $tambah_changelog->id_barang = $tambah->id_barang;
        $tambah_changelog->qty = $request['qty_restock_atk'];
        $tambah_changelog->status = 'In';
        $tambah_changelog->save();

        $inc = DB::table('tb_asset_atk_transaction')->select('id_transaction')->get();
        $increment = count($inc);
        $nomor = $increment+1;
        if($nomor < 10){
            $nomor = '00' . $nomor;
        }

        $no_peminjaman = date('ymd').$nomor;

        $tambah_transac                   = new AssetAtkTransaction();
        $tambah_transac->no_transac       = $no_peminjaman;
        $tambah_transac->id_barang        = $tambah->id_barang;
        $tambah_transac->nik_peminjam     = $request['nik_request2'];
        $tambah_transac->qty_akhir        = $qty_request;
        $tambah_transac->qty_awal         = '0';
        $tambah_transac->keterangan       = $request['ket_request'];
        $tambah_transac->status           = 'ACCEPT';
        $tambah_transac->save();

        $tambah_changelog2   = new AssetAtkChangelog();
        $tambah_changelog2->nik = $request['nik_request2'];
        $tambah_changelog2->id_barang = $tambah->id_barang;
        $tambah_changelog2->qty = $qty_request;
        $tambah_changelog2->status = 'Out';
        $tambah_changelog2->save();

        $update_qty = AssetAtk::where('id_barang', $tambah->id_barang)->first();
        $update_qty->qty = $qty_restock - $qty_request;
        $update_qty->update();

        $kirim = AssetAtkRequest::join('users', 'users.nik', '=', 'tb_asset_atk_request.nik')->select('users.email', 'id_position')->where('id_barang',$request['id_barang_done2'])->first();

        $get_email_user = collect([$kirim]);

        $get_divisi_hr = User::select('email','id_position')->where('id_position', 'HR MANAGER')->first();

        $receiver = $get_email_user->concat([$get_divisi_hr]);

        $receiver_final = $receiver->all();

        $req_atk = AssetAtkRequest::join('users', 'tb_asset_atk_request.nik', '=', 'users.nik')
                    ->select('nama', 'qty', 'name', 'keterangan', 'status', 'link', 'note_reject', 'tb_asset_atk_request.created_at')
                    ->where('tb_asset_atk_request.id_barang', $request['id_barang_done2'])
                    ->first();

        foreach ($receiver_final as $final) {
            Mail::to($final)->send(new RequestATK('[SIMS-App] Request ATK Sudah Datang', $req_atk,$final->id_position,$get_email_user, 'DONE'));
        }

        return redirect()->back()->with('update', 'Successfully!');
    }

    public function getqtyatk(Request $request)
    {
    	$atk = $request['atk'];

        return array(DB::table('tb_asset_atk')
            ->select('qty', 'id_barang', 'nama_barang', 'unit')
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


            $get_id_transac = AssetAtkTransaction::select('id_transaction','nik_peminjam')->where('id_barang', $request['atk'])->orderBy('created_at','desc')->first();

            $get_email = AssetAtkTransaction::join('users', 'users.nik', '=', 'tb_asset_atk_transaction.nik_peminjam')->select('users.email','users.id_position')->where('nik', $get_id_transac->nik_peminjam)->first();

            $collect_email = collect([$get_email]);

            $get_divisi_hr = User::select('email','id_position')->where('id_position', 'HR MANAGER')->first();

            $get_divisi_hr2 = User::select('email','id_position')->where('id_position', 'STAFF GA')->first();

            $receiver = $collect_email->concat([$get_divisi_hr])->concat([$get_divisi_hr2]);

            

            $receiver_final = $receiver->all();
            // return $receiver_final;

            $req_atk = AssetAtk::join('tb_asset_atk_transaction', 'tb_asset_atk.id_barang','=', 'tb_asset_atk_transaction.id_barang')
                        ->join('users', 'tb_asset_atk_transaction.nik_peminjam', '=', 'users.nik')
                        ->select('nama_barang', 'qty_akhir', 'qty_request', 'tb_asset_atk_transaction.status', 'name', 'keterangan', 'tb_asset_atk_transaction.created_at')
                        ->where('tb_asset_atk_transaction.id_transaction', $get_id_transac->id_transaction)
                        ->first();

            foreach ($receiver_final as $final) {
                Mail::to($final)->send(new RequestATK('[SIMS-App] Request ATK', $req_atk,$final->id_position,$get_divisi_hr2 ));
            }
        } else {
            // $store                   = new AssetAtkTransaction();
            // $store->no_transac       = $no_peminjaman;
            // $store->id_barang        = $request['atk'];
            // $store->nik_peminjam     = Auth::User()->nik;
            // $store->qty_akhir        = $qty_akhir;
            // $store->qty_awal         = $count_qty->qty;
            // $store->keterangan       = $request['keterangan'];
            // $store->status           = 'PENDING';
            // $store->save();

            $id_barang = $request->atk;
            $ket = $request->keterangan;
            $qty_awal = $request->stock;
            $qty_request = $request->qty;
            $nama_barang = $request->id_barangs;

            if(count($id_barang) > count($qty_request))
                $count = count($qty_request);
            else $count = count($id_barang);

            for($i = 0; $i < $count; $i++){
                $data = array(
                    'id_barang'     => $id_barang[$i],
                    'no_transac'    => $no_peminjaman,
                    'qty_akhir'     => $qty_request[$i],
                    'qty_awal'      => $qty_awal[$i],
                    'keterangan'    => $ket[$i],
                    'nik_peminjam'  => Auth::User()->nik,
                    'status'        => 'PENDING',
                );

                $insertData[] = $data;
            }
            AssetAtkTransaction::insert($insertData);

            $variable = collect($insertData);
            // $variable = collect(["insert_data" => $insertData, "nama_barang" => $nama_barang]);

            // foreach ($variable["list_insert_data"] as $key => $value) {
            //     $value["nama_barang"]
            // }

            $variable = $variable->map(function($item,$key) use ($nama_barang) {
                $item["nama_barang"] = $nama_barang[$key];
                return $item;
            });

            $get_id_transac = AssetAtkTransaction::select('id_transaction')->where('id_barang', $request['atk'])->orderBy('created_at','desc')->first();

            // $get_email = User::select('email')->where('id_division', 'HR')->get();

            $get_divisi_hr = User::select('email','id_position')->where('id_position', 'HR MANAGER')->first();

            $get_email_manager = collect([$get_divisi_hr]);

            $get_divisi_hr2 = User::select('email','id_position', 'name')->where('id_position', 'WAREHOUSE')->where('id_company', '1')->first();

            $receiver = $get_email_manager->concat([$get_divisi_hr2]);

            $receiver_final = $receiver->all();

            // $req_atk = AssetAtk::join('tb_asset_atk_transaction', 'tb_asset_atk.id_barang','=', 'tb_asset_atk_transaction.id_barang')
            //             ->join('users', 'tb_asset_atk_transaction.nik_peminjam', '=', 'users.nik')
            //             ->select('nama_barang', 'qty_akhir', 'qty_request', 'tb_asset_atk_transaction.status', 'name', 'keterangan', 'tb_asset_atk_transaction.created_at')
            //             ->where('tb_asset_atk_transaction.id_transaction', $get_id_transac->id_transaction)
            //             ->first();

            $req_atk = collect(['variable'=>$variable,'nama_peminjam'=>Auth::User()->name,'request_date'=>date("Y-m-d h:i:s"),'status'=>'REQUEST']);

            // return $variable;

            foreach ($receiver_final as $final) {
                Mail::to($final)->send(new RequestATK('[SIMS-App] Request ATK', $req_atk,$final->id_position,$get_divisi_hr2,'PENDING'));
            }
        }

        // if ($count_qty->status == 'NEW') {
        //     $update         = AssetAtk::where('id_barang', $request->atk)->first();
        //     $update->qty    = $count_qty->qty - $qty_akhir;
        //     $update->status = 'NN';
        //     $update->update();
        // } elseif ($count_qty->qty < $qty_akhir) {
        //     $update         = AssetAtk::where('id_barang', $request->atk)->first();
        //     $update->qty    = $count_qty->qty;
        //     $update->update();
        // } else {
        //     $update         = AssetAtk::where('id_barang', $request->atk)->first();
        //     $update->qty    = $count_qty->qty - $qty_akhir;
        //     // $update->status = 'NN';
        //     $update->update();
        // }

        
        

		return redirect()->back()->with('update', 'Request ATK akan diproses!');
    }

    public function store_request_atk(Request $request)
    {
        $nama_barang = $request->atk;
        $qty = $request->qty;
        $ket = $request->keterangan;
        $link = $request->link;

        if(count($nama_barang) > count($qty))
            $count = count($qty);
        else $count = count($nama_barang);

        for($i = 0; $i < $count; $i++){
            $data = array(
                'nama'          => $nama_barang[$i],
                'qty'           => $qty[$i],
                'keterangan'    => $ket[$i],
                'nik'           => Auth::User()->nik,
                'status'        => 'REQUEST',
                'link'          => $link[$i],
            );

            $insertData[] = $data;
        }
        AssetAtkRequest::insert($insertData);

        $get_id_transac = AssetAtkRequest::select('id_barang')->orderBy('created_at','desc')->first();

        $get_divisi_hr = User::select('email','id_position')->where('id_position', 'HR MANAGER')->first();

        $get_email_manager = collect([$get_divisi_hr]);

        $get_divisi_hr2 = User::select('email','id_position', 'name')->where('id_position', 'WAREHOUSE')->where('id_company', '1')->first();

        $receiver = $get_email_manager->concat([$get_divisi_hr2]);

        $receiver_final = $receiver->all();

        // $req_atk = AssetAtkRequest::join('users', 'tb_asset_atk_request.nik', '=', 'users.nik')
        //             ->select('nama', 'qty', 'name', 'keterangan', 'status', 'link', 'tb_asset_atk_request.created_at')
        //             ->where('tb_asset_atk_request.id_barang', $get_id_transac->id_barang)
        //             ->first();

        $req_atk = collect(['variable'=>$insertData,'nama_peminjam'=>Auth::User()->name,'request_date'=>date("Y-m-d h:i:s"),'status'=>'REQUEST']);

        // return $req_atk;

        foreach ($receiver_final as $final) {
            Mail::to($final)->send(new RequestATK('[SIMS-App] Request ATK Baru', $req_atk,$final->id_position,$get_divisi_hr2,'REQUEST'));
        }

        return redirect()->back()->with('update', 'Request ATK akan diproses!');
    }

    public function accept_request(Request $request)
    {
    	// $id_barang = $request['id_barang_update'];
    	// $id_transaction = $request['id_transaction_update'];
    	// $qty = $request['qty_awal_accept'];
    	// $qty_akhir = $request['qty_akhir_accept'];

        $cek_status = AssetAtkTransaction::select('id_barang', 'id_transaction')->where('id_barang', $request->id_barang)->where('status', 'PENDING')->where('id_transaction', '!=', $request->id_transaction)->first();
        $count_status = AssetAtkTransaction::select('id_barang', 'id_transaction')->where('id_barang', $request->id_barang)->where('status', 'PENDING')->where('id_transaction', '!=', $request->id_transaction)->count();

        $cek_qty = AssetAtkTransaction::select('qty_awal', 'qty_akhir')->where('id_transaction', $request->id_transaction)->where('id_barang', $request->id_barang)->first();
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
            $update             = AssetAtkTransaction::where('id_transaction',$request->id_transaction)->first();
            $update->status     = 'ACCEPT';
            $update->update();

            $update_qty = AssetAtk::where('id_barang', $request->id_barang)->first();
            $update_qty->qty = $request->qty - $request->qty_akhir;
            $update_qty->update();

            $tambah_changelog   = new AssetAtkChangelog();
            $tambah_changelog->nik = $request->nik_peminjam;
            $tambah_changelog->id_barang = $request->id_barang;
            $tambah_changelog->qty = $request->qty_akhir;
            $tambah_changelog->status = 'Out';
            $tambah_changelog->save();

            /*$update_qty = AssetAtk::where('id_barang', $id_barang)->first();
            $update_qty->qty = $qty - $qty_akhir;
            $update_qty->update();
        }*/

        $kirim = AssetAtkTransaction::join('users', 'users.nik', '=', 'tb_asset_atk_transaction.nik_peminjam')->select('users.email')->where('id_transaction',$request->id_transaction)->first();

        $get_email_user = collect([$kirim]);

        $get_divisi_hr = User::select('email','id_position')->where('id_position', 'HR MANAGER')->first();

        $receiver = $get_email_user->concat([$get_divisi_hr]);

        $receiver_final = $receiver->all();

        $req_atk = AssetAtk::join('tb_asset_atk_transaction', 'tb_asset_atk.id_barang','=', 'tb_asset_atk_transaction.id_barang')
                    ->join('users', 'tb_asset_atk_transaction.nik_peminjam', '=', 'users.nik')
                    ->select('nama_barang', 'qty_akhir', 'qty_request', 'tb_asset_atk_transaction.status', 'name', 'keterangan', 'tb_asset_atk_transaction.created_at')
                    ->where('tb_asset_atk_transaction.id_transaction', $request->id_transaction)
                    ->first();
        // return $req_atk;

        foreach ($receiver_final as $final) {
            Mail::to($final)->send(new RequestATK('[SIMS-App] Request ATK Diterima', $req_atk,$final->id_position,$get_email_user, 'ACCEPT'));
        }

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
        // $id_barang = $request['id_barang_reject'];
        // $id_transaction = $request['id_transaction_reject'];
        // $qty = $request['qty_awal_reject'];
        // $qty_akhir = $request['qty_akhir_reject'];

        $update             = AssetAtkTransaction::where('id_transaction',$request->id_transaction)->first();
        $update->status     = 'REJECT';
        $update->note       = $request->reason;
        $update->update();

        /*$update_qty         = AssetAtk::where('id_barang', $id_barang)->first();
        $update_qty->qty    = $qty_akhir + $qty;
        $update_qty->update();*/

        $kirim = AssetAtkTransaction::join('users', 'users.nik', '=', 'tb_asset_atk_transaction.nik_peminjam')->select('users.email')->where('id_transaction',$request->id_transaction)->first();

        $get_email_user = collect([$kirim]);

        $get_divisi_hr = User::select('email','id_position')->where('id_position', 'HR MANAGER')->first();

        $receiver = $get_email_user->concat([$get_divisi_hr]);

        $receiver_final = $receiver->all();

        $req_atk = AssetAtk::join('tb_asset_atk_transaction', 'tb_asset_atk.id_barang','=', 'tb_asset_atk_transaction.id_barang')
                    ->join('users', 'tb_asset_atk_transaction.nik_peminjam', '=', 'users.nik')
                    ->select('nama_barang', 'qty_akhir', 'qty_request', 'tb_asset_atk_transaction.status', 'name', 'keterangan', 'tb_asset_atk_transaction.created_at','note')
                    ->where('tb_asset_atk_transaction.id_transaction', $request->id_transaction)
                    ->first();
        // return $req_atk;

        foreach ($receiver_final as $final) {
            Mail::to($final)->send(new RequestATK('[SIMS-App] Request ATK Ditolak', $req_atk,$final->id_position,$get_email_user, 'REJECT'));
        }

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
            $update_qty->qty = $qty_restock + $qty - $qty_request;
            $update_qty->update();

        // }

        
        return redirect()->back()->with('update', 'Successfully!');
    }

    public function reject_request_atk(Request $request)
    {
        $update = AssetAtkRequest::where('id_barang', $request->id_barang)->first();
        $update->status = 'REJECTED';
        $update->note_reject = $request->reason;
        $update->update();

        $kirim = AssetAtkRequest::join('users', 'users.nik', '=', 'tb_asset_atk_request.nik')->select('users.email', 'id_position')->where('id_barang',$request->id_barang)->first();

        $get_email_user = collect([$kirim]);

        $get_divisi_hr = User::select('email','id_position')->where('id_position', 'HR MANAGER')->first();

        $receiver = $get_email_user->concat([$get_divisi_hr]);

        $receiver_final = $receiver->all();

        $req_atk = AssetAtkRequest::join('users', 'tb_asset_atk_request.nik', '=', 'users.nik')
                    ->select('nama', 'qty', 'name', 'keterangan', 'status', 'link', 'note_reject', 'tb_asset_atk_request.created_at')
                    ->where('tb_asset_atk_request.id_barang', $request->id_barang)
                    ->first();
        // return $req_atk;

        foreach ($receiver_final as $final) {
            Mail::to($final)->send(new RequestATK('[SIMS-App] Request ATK ditolak', $req_atk,$final->id_position,$get_email_user,'REJECTED'));
        }

        return redirect()->back()->with('update', 'Successfully!');   
    }
}
