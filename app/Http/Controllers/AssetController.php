<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use DB;
use App\Tech_asset;
use App\Tech_asset_transaction;
// use App\SNAsset;
use App\Kategori_Asset;
use App\User;
use Notification;
use App\Notifications\Peminjaman;
use App\Notifications\AcceptPinjaman;
use App\Notifications\RejectPinjaman;
use App\Detail_Transaction_Tech_Asset;

class AssetController extends Controller
{
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

        $asset = DB::table('tb_asset')
                ->join('tb_kategori_asset', 'tb_kategori_asset.id_kat', '=', 'tb_asset.id_kat')
        		->select('tb_asset.id_barang','nama_barang','description','nik', 'serial_number', 'status', 'kategori', 'total_pinjam')
        		->get();

        $asset2 = DB::table('tb_asset')
        			->join('tb_kategori_asset', 'tb_kategori_asset.id_kat', '=', 'tb_asset.id_kat')
        			->join('tb_detail_asset_transaction', 'tb_detail_asset_transaction.id_barang', '=', 'tb_asset.id_barang')
        			->select(DB::raw('count(tb_detail_asset_transaction.id_barang) as qty_pinjam'), 'nama_barang', 'description', 'serial_number', 'tb_asset.status', 'kategori', 'tb_asset.id_barang', 'tb_kategori_asset.id_kat')
        			->groupBy('tb_detail_asset_transaction.id_barang')
        			->get();

        $asset3 = DB::table('tb_asset')
                ->join('tb_kategori_asset', 'tb_kategori_asset.id_kat', '=', 'tb_asset.id_kat')
        		->select('tb_asset.id_barang','nama_barang','description','nik', 'serial_number', 'status', 'kategori', 'total_pinjam', 'tb_kategori_asset.id_kat')
        		->where('status_pinjam', 'TIDAK PERNAH')
        		->get();


        $assets = DB::table('tb_asset')
        		->join('users','users.nik','=','tb_asset.nik')
        		->select('tb_asset.id_barang','tb_asset.nama_barang','tb_asset.description','users.name','tb_asset.nik')
        		->get();

        $assetsd	= DB::table('tb_asset_transaction')
                    ->join('users','users.nik','=','tb_asset_transaction.nik_peminjam')
        			->join('tb_kategori_asset','tb_kategori_asset.id_kat','=','tb_asset_transaction.id_kat')
        			->select('tb_asset_transaction.id_transaction','tb_asset_transaction.id_kat','tb_asset_transaction.qty_akhir','users.name','tb_asset_transaction.nik_peminjam','tb_asset_transaction.status', 'tb_asset_transaction.keterangan', 'tgl_pengembalian', 'tgl_peminjaman', 'tb_asset_transaction.note', 'tb_kategori_asset.kategori', 'keperluan', 'no_peminjaman', 'tb_asset_transaction.updated_at')
                    ->orderBy('tb_asset_transaction.created_at', 'desc')
        			->get();

        $pinjaman = DB::table('tb_asset_transaction')
                    ->join('users','users.nik','=','tb_asset_transaction.nik_peminjam')
                    ->join('tb_kategori_asset','tb_kategori_asset.id_kat','=','tb_asset_transaction.id_kat')
                    ->select('tb_asset_transaction.nik_peminjam','tb_asset_transaction.id_transaction','tb_asset_transaction.id_kat','users.name','tb_asset_transaction.qty_akhir','tb_asset_transaction.created_at','tb_asset_transaction.updated_at','tb_asset_transaction.status', 'tb_asset_transaction.keterangan', 'tgl_pengembalian', 'tgl_peminjaman', 'tb_asset_transaction.note', 'tb_kategori_asset.kategori', 'keperluan', 'no_peminjaman')
                    ->where('tb_asset_transaction.nik_peminjam',Auth::User()->nik)
                    ->orderBy('tb_asset_transaction.created_at', 'desc')
                    ->get();

        $nik_peminjam = DB::table('tb_asset_transaction')
                ->select('nik_peminjam')
                ->first();


        $kategori = DB::table('tb_kategori_asset')
                    ->select('qty', 'kategori', 'desc', 'id_kat')
                    ->get();

        $kategori2 = DB::table('tb_kategori_asset')
                    ->select('qty', 'kategori', 'desc', 'id_kat')
                    ->where('qty', '!=', '0')
                    ->get();

        $id_barang = $request['id_barang_update'];

        $id_transaction = $request['id_transaksi'];

        $serial_number = DB::table('tb_detail_asset_transaction')->join('tb_asset', 'tb_asset.id_barang', '=', 'tb_detail_asset_transaction.id_barang')->select('nama_barang', 'serial_number')->where('id_transaction', $id_transaction)->get();

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

        $status = DB::table('tb_asset_transaction')
        			->select('status')
        			->first();

		return view('TECH.asset.asset_peminjaman', compact('notifc','pinjaman','assets','assetsd','asset','lead','notif','notifOpen','notifsd','notiftp','notifc', 'count_qty', 'kategori', 'nik_peminjam', 'serial_number', 'asset2', 'asset3', 'status', 'notifClaim', 'kategori2'));
	}

    public function detail_asset_peminjaman($id_barang){
        $asset = DB::table('tb_asset_transaction')
                ->join('users','users.nik','=','tb_asset_transaction.nik_peminjam')
                ->join('tb_detail_asset_transaction', 'tb_detail_asset_transaction.id_transaction', '=', 'tb_asset_transaction.id_transaction')
                ->join('tb_asset','tb_asset.id_barang','=','tb_detail_asset_transaction.id_barang')
                ->select('tb_asset_transaction.nik_peminjam','tb_asset_transaction.id_transaction','users.name','tb_asset_transaction.qty_akhir','tb_asset_transaction.created_at','tb_asset_transaction.updated_at','tb_asset.nama_barang','tb_asset_transaction.status','tb_asset_transaction.qty_akhir', 'tgl_peminjaman', 'tgl_pengembalian', 'tb_asset_transaction.keterangan', 'tb_asset_transaction.note','tb_asset_transaction.status')
                ->where('tb_asset.id_barang',$id_barang)
                ->orderBy('tb_asset_transaction.created_at', 'desc')
                ->get();

        /*$asset = DB::table('tb_detail_asset_transaction')
        		->join('tb_asset_transaction', 'tb_asset_transaction.id_transaction', '=', 'tb_detail_asset_transaction.id_transaction')
        		->join('users', 'users.nik', '=', 'tb_asset_transaction.nik_peminjam')
        		->join('tb_asset', 'tb_asset.id_barang', '=', 'tb_detail_asset_transaction.id_barang')
        		->select('tb_asset_transaction.nik_peminjam', 'users.name','tb_asset_transaction.qty_akhir','tb_asset_transaction.created_at','tb_asset_transaction.updated_at','tb_asset.nama_barang','tb_asset_transaction.status','tb_asset_transaction.qty_akhir', 'tgl_peminjaman', 'tgl_pengembalian', 'tb_asset_transaction.keterangan', 'tb_asset_transaction.note','tb_asset_transaction.status')
        		->where('tb_detail_asset_transaction.id_barang')
        		->orderBy('tb_detail_asset_transaction.created_at','desc')
        		->get();*/

        $tampilkan = DB::table('tb_detail_asset_transaction')
        			->join('tb_asset_transaction', 'tb_asset_transaction.id_transaction', '=', 'tb_detail_asset_transaction.id_transaction')
        			->join('tb_asset', 'tb_asset.id_barang', '=', 'tb_detail_asset_transaction.id_barang')
        			->select('tb_asset_transaction.status')
        			->where('tb_detail_asset_transaction.id_barang', $id_barang)
        			->first();

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


        return view('TECH.asset.detail_asset_peminjaman',compact('asset','notif','notifOpen','notifsd','notiftp','notifc', 'tampilkan', 'notifClaim'));
    }

	public function store(Request $request){

        $count_qty = Kategori_Asset::select('qty')->where('id_kat', $request->kategori)->first();

		$tambah                   = new Tech_asset();
		$tambah->nik 		      = Auth::User()->nik;
		$tambah->nama_barang      = $request['nama_barang'];
		$tambah->serial_number    = $request['sn'];
        $tambah->id_kat           = $request['kategori'];
        $tambah->status           = 'AVAILABLE';
        $tambah->description      = $request['keterangan'];
        $tambah->total_pinjam 	  = '0';
        $tambah->status_pinjam 	  = 'TIDAK PERNAH';
		$tambah->save(); 

        $id_kat         = $request->kategori;
        $update         = Kategori_Asset::where('id_kat', $id_kat)->first();
        $update->qty    = $count_qty->qty + 1;
        $update->update();

		return redirect()->back()->with('success', 'Berhasil menambahkan asset!');
	}

    public function edit(Request $request){
        $id_barang = $request['id_barang_edit'];

        $update = Tech_asset::where('id_barang',$id_barang)->first();
        $update->nama_barang   = $request['edit_nama'];
        $update->serial_number = $request['serial_number_edit']; 
        $update->description   = $request['keterangan_edit'];
        $update->update(); 

        return redirect()->back()->with('update','Update Barang Berhasil!');;
    }

	public function update(Request $request){

        $count_qty = Kategori_Asset::select('qty')->where('id_kat', $request->kategori3)->first();

        $qty_pinjam = $request['quantity'];

        $inc = DB::table('tb_asset_transaction')->select('id_transaction')->get();
        $increment = count($inc);
        $nomor = $increment+1;
        if($nomor < 10){
            $nomor = '00' . $nomor;
        }

        $no_peminjaman = date('ymd').$nomor;

        $store                   = new Tech_asset_transaction();
        $store->no_peminjaman 	 = $no_peminjaman;
		$store->nik_peminjam	 = Auth::User()->nik;
		$store->qty_akhir 		 = $qty_pinjam;
        $store->qty_awal         = $count_qty->qty;
        $tgl_peminjaman          = strtotime($_POST['tgl_peminjaman']); 
        $tgl_peminjaman          = date("Y-m-d",$tgl_peminjaman);
        $tgl_pengembalian        = strtotime($_POST['tgl_kembali']); 
        $tgl_pengembalian        = date("Y-m-d",$tgl_pengembalian);
        $store->tgl_pengembalian = $tgl_pengembalian;
        $store->tgl_peminjaman   = $tgl_peminjaman;
        $store->keperluan        = $request['description'];
        $store->keterangan       = $request['keterangan'];
        $store->status           = 'PENDING';
        $store->id_kat           = $request->kategori3;
		$store->save();

        $update_kat      = Kategori_Asset::where('id_kat', $request->kategori3)->first();
        $update_kat->qty = $count_qty->qty - $qty_pinjam;
        $update_kat->update();

        $kirim = User::select('email')->where('id_position', 'INTERNAL IT')
        ->orWhere('email', 'brillyan@sinergy.co.id')
        ->orWhere('email', 'endraw@sinergy.co.id')
        ->get();

        $users = User::select('email')->where('email', 'faiqoh@sinergy.co.id')->get();
        Notification::send($kirim, new Peminjaman());

	    return redirect()->back()->with('update', 'Peminjaman Akan di Proses!');	
	}

    public function accept(Request $request){
        $id_transaction = $request['id_transaction_update'];
        $kategori 		= $request['id_kat_accept'];

        $id_barang     = $_POST['detail_product'];   
        $id_transac    = $_POST['id_transaction_update'];
        $id_kat_accept = $_POST['id_kat_accept'];
        $qty 		   = $request->qty_akhir;
        $qty_akhir     = (int)$qty;
        var_dump($qty_akhir);

        for ($i=0; $i < $qty_akhir ; $i++) { 
        	$data = array(
                'id_transaction'      => $id_transac,
                'id_barang'           => $id_barang[$i],
            );

            $insertData[] = $data;
        }

        Detail_Transaction_Tech_Asset::insert($insertData);

        $update             = Tech_asset_transaction::where('id_transaction',$id_transaction)->first();
        $update->status     = 'ACCEPT';
        $update->update();

    	/*$count_total_pinjam = Tech_asset::select('id_barang', 'total_pinjam')->where('id_barang', $id_barang)->get();

    	foreach ($count_total_pinjam as $qty_pinjam) {
    		$update_qty = Tech_asset::where('id_barang', $qty_pinjam->id_barang)->first();
    		$update_qty->status = 'UNAVAILABLE';
    		$update_qty->total_pinjam = $qty_pinjam->total_pinjam +1;
    		$update_qty->update();
    	}*/

        for ($i=0; $i < $qty_akhir ; $i++) {  
        	$data = array(
                'status'        => 'UNAVAILABLE',
                'status_pinjam' => 'PERNAH',
                // 'total_pinjam' => $count_total_pinjam->total_pinjam + 1,
            );
        Tech_asset::where('id_barang',$id_barang[$i])->update($data);
        }
        
        $nik_peminjam = $request['nik_peminjam_accept'];

        $users = User::select('email')->where('nik',$nik_peminjam)->first();
        Notification::send($users, new AcceptPinjaman());

        return redirect()->back()->with('success', 'Peminjaman Telah di verifikasi!');; 
    }

    public function reject(Request $request){

        $id_transaction = $request['id_transaction_reject'];

        $hmm   = DB::table('tb_asset_transaction')->select('qty_akhir')->where('id_transaction',$id_transaction)->first();

        $qtys  = $hmm->qty_akhir;

        $kat = DB::table('tb_kategori_asset')->select('qty', 'kategori', 'id_kat')->where('id_kat',$request['id_kat'])->first();

        $qty_kat = $kat->qty;

        // $id_barang = $_POST['id_barang_reject'];

        $qty 		   = $request->qty_total_reject;
        $qty_reject   = (int)$qty;
        var_dump($qty_reject);

        /*for ($i=0; $i < $qty_reject ; $i++) {  
        	$data = array(
                'status'      => 'AVAILABLE',
            );
        Tech_asset::where('id_barang',$id_barang[$i])->update($data);
        }*/

        $update         = Tech_asset_transaction::where('id_transaction',$id_transaction)->first();
        $update->status = 'REJECT';
        $update->note   = $request['reject_note'];
        $update->update();

        $update_kat = Kategori_Asset::where('id_kat', $kat->id_kat)->first();
        $update_kat->qty = $qtys + $qty_kat;
        $update_kat->update();

        $nik_peminjam = $request['nik_peminjam_reject'];

        $users = User::select('email')->where('nik',$nik_peminjam)->first();
        Notification::send($users, new RejectPinjaman());

        return redirect()->back()->with('danger', 'Peminjaman Telah di Reject!');; 
    }

    /*public function ambil(Request $request){

        $id_barang = $request['id_transaction_ambil'];

        $id_transaction   = $request['id_barang_ambil'];

        $update_ambil             = Tech_asset_transaction::where('id_transaction',$id_transaction)->first();
        $update_ambil->status     = 'AMBIL';
        $update_ambil->update();

        return redirect()->back()->with('warning', 'Barang Telah di Ambil !');; 
    }*/

    public function kembali(Request $request){

        $id_transaction   = $request['id_transaction_kembali'];

        $hmm   = DB::table('tb_asset_transaction')->select('qty_akhir')->where('id_transaction',$id_transaction)->first();

        $qtys       = $hmm->qty_akhir;

        $kat = DB::table('tb_kategori_asset')->select('qty', 'kategori', 'id_kat')->where('id_kat',$request['id_kat_kembali'])->first();

        $qty_kat = $kat->qty;

        $id_barang = $_POST['id_barang_kembali'];

        $qty 		   = $request->total_qty_kembali;
        $qty_kembali   = (int)$qty;
        var_dump($qty_kembali);

        for ($i=0; $i < $qty_kembali ; $i++) {  
        	$data = array(
                'status'       => 'AVAILABLE',
            );
        Tech_asset::where('id_barang',$id_barang[$i])->update($data);
        }
                

        $update                     = Tech_asset_transaction::where('id_transaction',$id_transaction)->first();
        $update->status             = 'RETURN';
        $update->updated_at         = date('Y-m-d H:i:s');
        $update->update();

        $update_kat         = Kategori_Asset::where('id_kat', $kat->id_kat)->first();
        $update_kat->qty    = $qty_kat + $qtys;
        $update_kat->update();

        return redirect()->back()->with('success', 'Barang Telah di Kembalikan !');; 
    }

    public function destroy(Request $request)
    {
        $id_barang = $request['id_barang_delete'];
        $id_kat = $request['id_kat_delete'];

        $hapus = Tech_asset::find($id_barang);
        $hapus->delete();

        $qty_kat = Kategori_Asset::select('qty')->where('id_kat', $id_kat)->first();

        $update = Kategori_Asset::where('id_kat', $id_kat)->first();
        $update->qty = $qty_kat->qty - 1;
        $update->update();

        return redirect()->back()->with('danger', 'Data berhasil dihapus!');
    }

   /* public function update_sn_asset(Request $request)
    {
        $id_barang = $request['sn_barang'];

        $arr = explode("\r\n", trim($_POST['serial_number']));

        for ($i = 0; $i < count($arr); $i++) {
                $line = $arr[$i];

                $datas = array(
                    'id_barang'     => $id_barang,
                    'serial_number' => $line,
                    );
                $insertDatas[] = $datas;
        }
        SNAsset::insert($insertDatas);

        return redirect()->back()->with('alert', 'Successfully!');
    }*/

    public function store_kategori(Request $request)
    {
        $tambah             = new Kategori_Asset();
        $tambah->kategori   = $request['kategori'];
        $tambah->qty        = '0';
        $tambah->desc       = $request['keterangan'];
        $tambah->save();

        return redirect()->back()->with('success', 'Successfully');
    }

    public function getdropdownkategori(Request $request)
    {
        $kategori = $request['kategori3'];

        return array(DB::table('tb_kategori_asset')
                ->select('qty')
                ->where('id_kat',$request->kategori)
                ->get(),$request->kategori);
    }

    public function getdropdownsn(Request $request)
    {
        $kategori = $request['id_kat_accept'];

        return array(DB::table('tb_asset')
                ->select('serial_number', 'id_barang', 'nama_barang')
                ->where('id_kat',$request->kategori)
                ->where('status', 'AVAILABLE')
                ->get(),$request->kategori);
    }

    public function getdropsn(Request $request)
    {
        $kategori = $request['id_kat_accept'];

        return array(DB::table('tb_asset')
            ->select('serial_number', 'nama_barang', 'id_barang')
            ->where('id_kat', $request->kategori)
            ->where('status', 'AVAILABLE')
            ->get(),$request->kategori);  
    }

    public function getid_barang(Request $request)
    {
    	$id_transaction = $request['id_transaction_kembali'];

        return array(DB::table('tb_detail_asset_transaction')
                ->select('id_barang')
                ->where('id_transaction',$request->id_transaction)
                ->get(),$request->id_transaction);
    }

    public function getid_barang_reject(Request $request)
    {
    	$id_transaction = $request['id_transaction_reject'];

        return array(DB::table('tb_detail_asset_transaction')
                ->select('id_barang')
                ->where('id_transaction',$request->id_transaction)
                ->get(),$request->id_transaction);
    }

    public function getsn(Request $request)
    {
    	$id_transaction = $request['btn_detail'];

        return array(DB::table('tb_detail_asset_transaction')
        		->join('tb_asset', 'tb_detail_asset_transaction.id_barang', '=', 'tb_asset.id_barang')
                ->select('nama_barang', 'serial_number')
                ->where('id_transaction',$request->id_transaction)
                ->get(),$request->id_transaction);
    }

    /*public function getid_barang_accept(Request $request)
    {
    	$kategori = $request['detail_product'];

        return array(DB::table('tb_asset')
                ->select('id_barang')
                ->where('id_barang',$request->kategori)
                ->get(),$request->kategori);
    }*/
}
