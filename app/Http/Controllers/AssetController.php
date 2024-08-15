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
use App\Notifications\PinjamanBaru;
use App\Notifications\AcceptPinjaman;
use App\Notifications\RejectPinjaman;
use App\Detail_Transaction_Tech_Asset;
use Mail;
use App\Mail\PeminjamanAssetMSM;
use App\Mail\AcceptPinjamanAssetMSM;
use Excel;
use App\SalesProject;
use App\LogAssetTech;

//phpOfficeExcel
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


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
                ->select('tb_asset.id_barang','nama_barang','tb_kategori_asset.description','nik', 'serial_number', 'status', 'kategori', 'total_pinjam')
                ->get();

        $asset2 = DB::table('tb_asset')
                    ->join('tb_kategori_asset', 'tb_kategori_asset.id_kat', '=', 'tb_asset.id_kat')
                    ->join('tb_detail_asset_transaction', 'tb_detail_asset_transaction.id_barang', '=', 'tb_asset.id_barang')
                    ->select(DB::raw('count(tb_detail_asset_transaction.id_barang) as qty_pinjam'), 'nama_barang', 'tb_kategori_asset.description', 'serial_number', 'tb_asset.status', 'kategori', 'tb_asset.id_barang', 'tb_kategori_asset.id_kat')
                    ->groupBy('tb_detail_asset_transaction.id_barang')
                    ->get();

        $asset3 = DB::table('tb_asset')
                ->join('tb_kategori_asset', 'tb_kategori_asset.id_kat', '=', 'tb_asset.id_kat')
                ->select('tb_asset.id_barang','nama_barang','tb_kategori_asset.description','nik', 'serial_number', 'status', 'kategori', 'total_pinjam', 'tb_kategori_asset.id_kat')
                ->where('status_pinjam', 'TIDAK PERNAH')
                ->get();


        $assets = DB::table('tb_asset')
                ->join('users','users.nik','=','tb_asset.nik')
                ->select('tb_asset.id_barang','tb_asset.nama_barang','tb_asset.description','users.name','tb_asset.nik')
                ->get();

        $assetsd    = DB::table('tb_asset_transaction')
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
                    ->select('qty', 'kategori', 'description', 'id_kat')
                    ->get();

        $kategori2 = DB::table('tb_kategori_asset')
                    ->Rightjoin('tb_asset','tb_asset.id_kat','=','tb_kategori_asset.id_kat')
                    ->select('tb_kategori_asset.qty', 'tb_kategori_asset.kategori', 'tb_kategori_asset.description', 'tb_kategori_asset.id_kat')
                    ->where('tb_kategori_asset.qty', '!=', '0')
                    ->groupBy('id_kat')
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

        return view('TECH.asset.asset_peminjaman', compact('notifc','pinjaman','assets','assetsd','asset','notif','notifOpen','notifsd','notiftp','notifc', 'kategori', 'nik_peminjam', 'serial_number', 'asset2', 'asset3', 'status', 'notifClaim', 'kategori2'))->with(['initView'=> $this->initMenuBase(),'feature_item'=>$this->RoleDynamic('asset_peminjaman')]);
    }

    public function detail_asset_peminjaman($id_barang){
        $asset = DB::table('tb_asset_transaction')
                ->join('users','users.nik','=','tb_asset_transaction.nik_peminjam')
                ->join('tb_detail_asset_transaction', 'tb_detail_asset_transaction.id_transaction', '=', 'tb_asset_transaction.id_transaction')
                ->join('tb_asset','tb_asset.id_barang','=','tb_detail_asset_transaction.id_barang')
                ->select('tb_asset_transaction.nik_peminjam','tb_asset_transaction.id_transaction','users.name','tb_asset_transaction.qty_akhir','tb_asset_transaction.created_at','tb_asset_transaction.updated_at','tb_asset.nama_barang','tb_asset_transaction.status','tb_asset_transaction.qty_akhir', 'tgl_peminjaman', 'tgl_pengembalian', 'tb_asset_transaction.keterangan', 'tb_asset_transaction.note','tb_asset_transaction.status','keperluan')
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

        $notifClaim = '';

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


        return view('TECH.asset.detail_asset_peminjaman',compact('asset','notif','notifOpen','notifsd','notiftp','notifc', 'tampilkan', 'notifClaim'))->with(['initView'=> $this->initMenuBase()]);
    }

    public function store(Request $request){

        $count_qty = Kategori_Asset::select('qty')->where('id_kat', $request->kategori)->first();

        $acronym = Kategori_Asset::select('acronym')
                    ->where('id_kat', $request->kategori)
                    ->first();

        $getnumber = Tech_asset::orderBy('id_barang', 'desc')->where('created_at','like',date('Y')."%")->count();

        if($getnumber == NULL){
            $getlastnumber = 1;
            $lastnumber = $getlastnumber;
        } else{
            $lastnumber = $getnumber+1;
        }

        if($lastnumber < 10){
           $akhirnomor = '00' . $lastnumber;
        }elseif($lastnumber > 9 && $lastnumber < 100){
           $akhirnomor = '0' . $lastnumber;
        }elseif($lastnumber >= 100){
           $akhirnomor = $lastnumber;
        }

        $month = date('m');

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
        $bln = $array_bln[$month];

        $code = $akhirnomor . '/' . $acronym->acronym . '/' . $bln . '/' . date('Y');

        $tambah                   = new Tech_asset();
        $tambah->nik              = Auth::User()->nik;
        $tambah->code_asset       = $code;
        $tambah->nama_barang      = $request['nama_barang'];
        $tambah->serial_number    = $request['sn'];
        $tambah->id_kat           = $request['kategori'];
        $tambah->status           = 'AVAILABLE';
        $tambah->description      = $request['keterangan'];
        $tambah->total_pinjam     = '0';
        $tambah->status_pinjam    = 'TIDAK PERNAH';
        $tambah->location         = $request['letak_barang'];
        $tambah->save(); 

        $id_kat         = $request->kategori;
        $update         = Kategori_Asset::where('id_kat', $id_kat)->first();
        $update->qty    = $count_qty->qty + 1;
        $update->update();

        $tambah_log = new LogAssetTech();
        $tambah_log->nik = Auth::User()->nik;
        $tambah_log->keterangan     = "Menambahkan Aset " . $request['nama_barang'];
        $tambah_log->save();

        return redirect()->back()->with('success', 'Berhasil menambahkan asset!');
    }

    public function edit(Request $request){
        $id_barang = $request['id_barang_edit'];

        $update = Tech_asset::where('id_barang',$id_barang)->first();

        if ($request['status_asset'] != $update->status) {
            $tambah_log = new LogAssetTech();
            $tambah_log->nik = Auth::User()->nik;
            $tambah_log->keterangan = "Mengubah status Aset ". $update->nama_barang . " menjadi ". $request['status_asset'];
            $tambah_log->save();
        }

        if ($request['edit_nama'] != $update->nama_barang) {
            $tambah_log = new LogAssetTech();
            $tambah_log->nik = Auth::User()->nik;
            $tambah_log->keterangan     = "Mengubah Nama Asset ". $update->nama_barang . " menjadi ". $request['edit_nama'];
            $tambah_log->save();
        }

        if ($request['serial_number_edit'] != $update->serial_number) {
            $tambah_log = new LogAssetTech();
            $tambah_log->nik = Auth::User()->nik;
            $tambah_log->keterangan     = "Mengubah Serial Number Asset ". $update->nama_barang . " menjadi ". $request['serial_number_edit'];
            $tambah_log->save();
        }
        
        if ($request['keterangan_edit'] != "") {
            $tambah_log = new LogAssetTech();
            $tambah_log->nik = Auth::User()->nik;
            if ($update->description == NULL) {
                $tambah_log->keterangan     = "Mengubah keterangan Aset ". $update->nama_barang .  " menjadi ". $request['keterangan_edit'];
                $tambah_log->save();
            }else if ($request['keterangan_edit'] != $update->description) {
                $tambah_log->keterangan     = "Mengubah keterangan Aset ". $update->nama_barang . " menjadi ". $request['keterangan_edit'];
               $tambah_log->save();
            }
            
            
        }  

        if ($request['lokasi_edit'] != "") {
            $tambah_log = new LogAssetTech();
            $tambah_log->nik = Auth::User()->nik;
            $tambah_log->keterangan     = "Mengubah Lokasi Aset ". $update->nama_barang . " menjadi ". $request['lokasi_edit'];
            $tambah_log->save();  
        } 

        $update->nama_barang   = $request['edit_nama'];
        $update->serial_number = $request['serial_number_edit']; 
        $update->description   = $request['keterangan_edit'];
        $update->location      = $request['lokasi_edit'];

        if ($request['status_asset'] != "") {
            $update->status        = $request['status_asset']; 
        }
        $update->update(); 


        $update_kategori = Kategori_Asset::where('id_kat',$update->id_kat)->first();
        if ($request['status_asset'] == 'UNAVAILABLE') {
            $update_kategori->qty = $update_kategori->qty - 1;
        }else{
            $update_kategori->qty = $update_kategori->qty + 1;
        }
        $update_kategori->update();    

        


        return redirect()->back()->with('update','Update Barang Berhasil!');;
    }

    public function updateKategori(Request $request)
    {
        $update = Kategori_Asset::where('id_kat',$request->id_kat)->first();
        $update->acronym = $request['acronym'];
        $update->description = $request['description'];
        $update->update();
    }

    public function getKategoriById(Request $request)
    {
        $getKategori = Kategori_Asset::select('kategori', 'id_kat', 'qty', 'description', 'acronym')->where('id_kat', $request->id_kat)->first();

        return array("data" => $getKategori);
    }

    public function update(Request $request){
        // $admin = DB::table('users')->select('id_position','email')->where('id_position','ADMIN')->where('id_division','MSM')->first();

        // return $admin->email;

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
        $store->no_peminjaman    = $no_peminjaman;
        $store->nik_peminjam     = Auth::User()->nik;
        $store->qty_akhir        = $qty_pinjam;
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
        // $update_kat->qty = $count_qty->qty - $qty_pinjam;
        $update_kat->update();        

        // $kirim = User::select('email')->where('id_position', 'INTERNAL IT')
        // ->orWhere('email', 'brillyan@sinergy.co.id')
        // ->orWhere('email', 'endraw@sinergy.co.id')
        // ->get();

        $kirim = Auth::User()->email;

        // $user = DB::table('users')->select('email')->where('id_position','ADMIN')->where('id_division','MSM')->get();

        $admin = User::select('email','name')->where('id_division','WAREHOUSE')->where('id_position','WAREHOUSE')->get();

        $peminjaman = DB::table('tb_asset_transaction')
                        ->join('users', 'users.nik', '=', 'tb_asset_transaction.nik_peminjam')
                        ->join('tb_kategori_asset','tb_kategori_asset.id_kat','=','tb_asset_transaction.id_kat')
                        ->select('tgl_peminjaman', 'nik_peminjam', 'keterangan', 'name', 'keperluan','tgl_pengembalian','kategori','no_peminjaman')
                        ->where('id_transaction',$store->id_transaction)
                        ->first();

        $tambah_log = new LogAssetTech();
        $tambah_log->nik = Auth::User()->nik;
        $tambah_log->keterangan     = "Requesting Asset [ Transaksi Peminjaman - ". $peminjaman->no_peminjaman . " ]";
        $tambah_log->save();

        // $users = User::select('email')->where('email', 'faiqoh@sinergy.co.id')->get();
        foreach ($admin as $admin) {
            Mail::to($admin)->cc($kirim)->send(new PeminjamanAssetMSM($peminjaman,$admin,'[SIMS-App] Asking Approvement - Peminjaman Barang'));     
        }
        // Notification::send($kirim, new PinjamanBaru());

        return redirect()->back()->with('update', 'Peminjaman Akan di Proses!');    
    }

    public function accept(Request $request){
        $id_transaction = $request['id_transaction_update'];
        $kategori       = $request['id_kat_accept'];

        $id_barang     = $_POST['detail_product'];   
        $id_transac    = $_POST['id_transaction_update'];
        $id_kat_accept = $_POST['id_kat_accept'];
        $qty           = $request->qty_akhir;
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

        $count_qty = Kategori_Asset::select('qty')->where('id_kat', $request->id_kat_accept)->first();

        $update_kat      = Kategori_Asset::where('id_kat', $request->id_kat_accept)->first();
        $update_kat->qty = $count_qty->qty - $qty_akhir;
        $update_kat->update(); 

        $count_total_pinjam = Tech_asset::select('id_barang', 'total_pinjam')->where('id_barang', $id_barang)->get();

        foreach ($count_total_pinjam as $total_pinjam) {
            $update_qty = Tech_asset::where('id_barang', $total_pinjam->id_barang)->first();
            $update_qty->status = 'UNAVAILABLE';
            $update_qty->total_pinjam = $total_pinjam->total_pinjam +1;
            $update_qty->update();
        }
        $nik_peminjam = $request['nik_peminjam_accept'];

        $users = User::select('email','name')->where('nik',$nik_peminjam)->first();

        for ($i=0; $i < $qty_akhir ; $i++) {  
            $data = array(
                'status'        => 'UNAVAILABLE',
                'status_pinjam' => 'PERNAH',
                'location'      => $users->name . '[ ' . $request['location_update'] . ' ]'
                // 'total_pinjam' => $count_total_pinjam->total_pinjam + 1,
            );
        Tech_asset::where('id_barang',$id_barang[$i])->update($data);
        }

        $barang = DB::table('tb_detail_asset_transaction')
                   ->join('tb_asset','tb_asset.id_barang','tb_detail_asset_transaction.id_barang')
                   ->select('nama_barang','serial_number')
                   ->where('id_transaction',$id_transaction)
                   ->get(); 

        $total_barang = DB::table('tb_detail_asset_transaction')->where('id_transaction',$id_transaction)->count('id_transaction');

        $peminjaman = DB::table('tb_asset_transaction')
                        ->join('users', 'users.nik', '=', 'tb_asset_transaction.nik_peminjam')
                        ->join('tb_kategori_asset','tb_kategori_asset.id_kat','=','tb_asset_transaction.id_kat')
                        ->select('tgl_peminjaman', 'nik_peminjam', 'keterangan', 'name', 'keperluan','tgl_pengembalian','kategori','no_peminjaman','tb_asset_transaction.status')
                        ->where('id_transaction',$id_transaction)
                        ->first();

        

        $tambah_log = new LogAssetTech();
        $tambah_log->nik            = Auth::User()->nik;
        $tambah_log->keterangan     = "Accepting Transaksi Peminjaman [ ". $peminjaman->no_peminjaman . " ]";
        $tambah_log->save();

        $users = User::select('email','name')->where('nik',$nik_peminjam)->first();
        // Notification::send($users, new AcceptPinjaman());
        Mail::to($users)->cc(Auth::User()->email)->send(new AcceptPinjamanAssetMSM($peminjaman,$users,$barang,$total_barang,'[SIMS-App] Accepting Request - Peminjaman Barang'));   

        return redirect()->back()->with('success', 'Peminjaman Telah di verifikasi!');
    }

    public function reject(Request $request){

        $id_transaction = $request['id_transaction_reject'];

        $hmm   = DB::table('tb_asset_transaction')->select('qty_akhir')->where('id_transaction',$id_transaction)->first();

        $qtys  = $hmm->qty_akhir;

        $kat = DB::table('tb_kategori_asset')->select('qty', 'kategori', 'id_kat')->where('id_kat',$request['id_kat'])->first();

        $qty_kat = $kat->qty;

        // $id_barang = $_POST['id_barang_reject'];

        $qty           = $request->qty_total_reject;
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

        // $users = User::select('email')->where('nik',$nik_peminjam)->first();
        // Notification::send($users, new RejectPinjaman());

        $barang = DB::table('tb_detail_asset_transaction')
               ->join('tb_asset','tb_asset.id_barang','tb_detail_asset_transaction.id_barang')
               ->select('nama_barang')
               ->where('id_transaction',$id_transaction)
               ->get(); 

        $total_barang = DB::table('tb_detail_asset_transaction')->where('id_transaction',$id_transaction)->count('id_transaction');

        $peminjaman = DB::table('tb_asset_transaction')
                        ->join('users', 'users.nik', '=', 'tb_asset_transaction.nik_peminjam')
                        ->join('tb_kategori_asset','tb_kategori_asset.id_kat','=','tb_asset_transaction.id_kat')
                        ->select('tgl_peminjaman', 'nik_peminjam', 'keterangan', 'name', 'keperluan','tgl_pengembalian','kategori','no_peminjaman','tb_asset_transaction.status','note','no_peminjaman')
                        ->where('id_transaction',$id_transaction)
                        ->first();

        $tambah_log = new LogAssetTech();
        $tambah_log->nik            = Auth::User()->nik;
        $tambah_log->keterangan     = "Rejecting Transaksi peminjaman [ ". $peminjaman->no_peminjaman . " ]";
        $tambah_log->save();

        $users = User::select('email','name')->where('nik',$nik_peminjam)->first();

        Mail::to($users)->cc(Auth::User()->email)->send(new AcceptPinjamanAssetMSM($peminjaman,$users,$barang,$total_barang,'[SIMS-App] Rejecting Request - Peminjaman Barang'));   

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

        $qty           = $request->total_qty_kembali;
        $qty_kembali   = (int)$qty;
        var_dump($qty_kembali);

        for ($i=0; $i < $qty_kembali ; $i++) {  
            $data = array(
                'status'       => 'AVAILABLE',
                'location'     => $request['location_return'],
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

        $nik_peminjam = Tech_asset_transaction::select('nik_peminjam')->where('id_transaction',$id_transaction)->first()->nik_peminjam;

        $barang = DB::table('tb_detail_asset_transaction')
               ->join('tb_asset','tb_asset.id_barang','tb_detail_asset_transaction.id_barang')
               ->select('nama_barang','serial_number')
               ->where('id_transaction',$id_transaction)
               ->get(); 

        $pinjam = DB::table('tb_asset_transaction')
                        ->join('users', 'users.nik', '=', 'tb_asset_transaction.nik_peminjam')
                        ->join('tb_kategori_asset','tb_kategori_asset.id_kat','=','tb_asset_transaction.id_kat')
                        ->select('tgl_peminjaman', 'nik_peminjam', 'keterangan', 'name', 'keperluan','tgl_pengembalian','kategori','no_peminjaman','tb_asset_transaction.status','note','tb_asset_transaction.updated_at','no_peminjaman')
                        ->where('id_transaction',$id_transaction)
                        ->first();

        $tambah_log = new LogAssetTech();
        $tambah_log->nik            = Auth::User()->nik;
        $tambah_log->keterangan     = "Accept Returning Asset [ Transaksi peminjaman - ". $pinjam->no_peminjaman . " ]";
        $tambah_log->save();

        $pinjam->location_return = $request['location_return'];
        $peminjaman = $pinjam;

        $total_barang = DB::table('tb_detail_asset_transaction')->where('id_transaction',$id_transaction)->count('id_transaction');

        $users = User::select('email','name')->where('nik',$nik_peminjam)->first();

        Mail::to($users)->cc(Auth::User()->email)->send(new AcceptPinjamanAssetMSM($peminjaman,$users,$barang,$total_barang,'[SIMS-App] Returning of goods - Peminjaman Barang'));  

        return redirect()->back()->with('success', 'Barang Telah di Kembalikan !');; 
    }

    public function destroy(Request $request)
    {
        $id_barang = $request['id_barang_delete'];
        $id_kat = $request['id_kat_delete'];

        $hapus = Tech_asset::find($id_barang);
        
        $tambah_log = new LogAssetTech();
        $tambah_log->nik = Auth::User()->nik;
        $tambah_log->keterangan     = "Menghapus Aset " . $hapus['nama_barang'];
        $tambah_log->save();

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
        $tambah                 = new Kategori_Asset();
        $tambah->kategori       = $request['kategori'];
        $tambah->acronym        = $request['code_kat'];
        $tambah->qty            = '0';
        $tambah->description    = $request['keterangan'];
        $tambah->save();

        return redirect()->back()->with('success', 'Successfully');
    }

    public function getKategori(Request $request)
    {
        return array("data" => DB::table('tb_kategori_asset')
                    ->select('qty', 'kategori', 'description', 'id_kat','acronym')
                    ->get());
    }

    public function getKategori2(Request $request)
    {
        return $kategori2 = DB::table('tb_kategori_asset')
                    ->Rightjoin('tb_asset','tb_asset.id_kat','=','tb_kategori_asset.id_kat')
                    ->select('tb_kategori_asset.qty', 'tb_kategori_asset.kategori', 'tb_kategori_asset.description', 'tb_kategori_asset.id_kat')
                    ->where('tb_kategori_asset.qty', '!=', '0')
                    ->where('status','AVAILABLE')
                    ->groupBy('id_kat')
                    ->get();
    }

    public function getAssetTech(Request $request)
    {
        $asset2 = DB::table('tb_asset')
                    ->join('tb_kategori_asset', 'tb_kategori_asset.id_kat', '=', 'tb_asset.id_kat')
                    ->join('tb_detail_asset_transaction', 'tb_detail_asset_transaction.id_barang', '=', 'tb_asset.id_barang')
                    ->select(DB::raw('count(tb_detail_asset_transaction.id_barang) as qty_pinjam'), 'nama_barang', 'tb_asset.description', 'serial_number', 'tb_asset.status', 'kategori', 'tb_asset.id_barang', 'tb_kategori_asset.id_kat','status_pinjam','tb_kategori_asset.qty as qty_kategori','location', 'acronym', 'code_asset')
                    ->groupBy('tb_detail_asset_transaction.id_barang')
                    ->get();


        $asset3 = DB::table('tb_asset')
                ->join('tb_kategori_asset', 'tb_kategori_asset.id_kat', '=', 'tb_asset.id_kat')
                ->select('tb_asset.id_barang','nama_barang','tb_asset.description','nik', 'serial_number', 'status', 'kategori', 'total_pinjam', 'tb_kategori_asset.id_kat','status_pinjam','tb_kategori_asset.qty as qty_kategori','location', 'acronym', 'code_asset')
                ->where('status_pinjam', 'TIDAK PERNAH')
                ->get();

        return array("data"=>array_merge($asset2->toArray(),$asset3->toArray()));
    }

    public function getAssetTransactionModerator(Request $request)
    {   
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')
                    ->select('name', 'roles.group')->where('user_id', $nik)->first();

        $assetsd = DB::table('tb_asset_transaction')
                    ->join('users','users.nik','=','tb_asset_transaction.nik_peminjam')
                    ->join('tb_kategori_asset','tb_kategori_asset.id_kat','=','tb_asset_transaction.id_kat')
                    ->select('tb_asset_transaction.id_transaction','tb_asset_transaction.id_kat','tb_asset_transaction.qty_akhir','users.name','tb_asset_transaction.nik_peminjam','tb_asset_transaction.status', 'tb_asset_transaction.keterangan', 'tgl_pengembalian', 'tgl_peminjaman', 'tb_asset_transaction.note', 'tb_kategori_asset.kategori', 'keperluan', 'no_peminjaman', 'tb_asset_transaction.updated_at')
                    ->orderBy('tb_asset_transaction.created_at', 'desc');

        if ($div == 'BCD' && $pos == 'MANAGER'|| $cek_role->name == 'Inventory, Logistic & Delivery') {
            $assetsd = $assetsd;
        }else{
            $assetsd = $assetsd->where('nik_peminjam',$nik);
        }
        

        if (isset($request->status)) {
            return array("data"=> $assetsd->whereIn('status',$request->status)->get());
        }else{
            return array("data"=> $assetsd->get());
        }
    }

    public function getAssetTransaction(Request $request)
    {
        return array("data"=> $pinjaman = DB::table('tb_asset_transaction')
                    ->join('users','users.nik','=','tb_asset_transaction.nik_peminjam')
                    ->join('tb_kategori_asset','tb_kategori_asset.id_kat','=','tb_asset_transaction.id_kat')
                    ->select('tb_asset_transaction.nik_peminjam','tb_asset_transaction.id_transaction','tb_asset_transaction.id_kat','users.name','tb_asset_transaction.qty_akhir','tb_asset_transaction.created_at','tb_asset_transaction.updated_at','tb_asset_transaction.status', 'tb_asset_transaction.keterangan', 'tgl_pengembalian', 'tgl_peminjaman', 'tb_asset_transaction.note', 'tb_kategori_asset.kategori', 'keperluan', 'no_peminjaman')
                    ->where('tb_asset_transaction.nik_peminjam',Auth::User()->nik)
                    ->orderBy('tb_asset_transaction.created_at', 'desc')
                    ->get());
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
        $kategori = DB::table('tb_asset_transaction')->select('id_kat')->where('id_transaction',$request->id_transaction)->first()->id_kat;
        // $kategori = $request['id_kat_accept'];

        return array(DB::table('tb_asset')
                ->select('id_barang', 'serial_number', 'nama_barang')
                ->where('id_kat',$kategori)
                // ->where('status', 'AVAILABLE')
                ->get());
    }

    public function getAsset(Request $request)
    {
        // $kategori = $request['id_kat_accept'];

        return array(DB::table('tb_asset')
            ->select('id_barang', 'serial_number', 'nama_barang','description','id_kat','status','location')
            ->where('id_barang', $request->id_barang)
            ->get(),$request->id_barang);
    }

    public function getid_barang(Request $request)
    {
        $id_transaction = $request['id_transaction_kembali'];

        return array(DB::table('tb_detail_asset_transaction')
                ->select('id_barang')
                ->where('id_transaction',$request->id_transaction)
                ->get(),$request->id_transaction);
    }

    // public function getid_barang_reject(Request $request)
    // {
    //  $id_transaction = $request['id_transaction_reject'];

    //     return array(DB::table('tb_detail_asset_transaction')
    //             ->select('id_barang')
    //             ->where('id_transaction',$request->id_transaction)
    //             ->get(),$request->id_transaction);
    // }

    public function getdetailAssetPeminjaman(Request $request)
    {
        // $id_transaction = $request['btn_detail'];

        return array(DB::table('tb_asset_transaction')
                ->join('users','users.nik','=','tb_asset_transaction.nik_peminjam')
                ->select('status','no_peminjaman', 'name','tgl_peminjaman','tgl_pengembalian','id_kat','nik_peminjam','qty_akhir','keterangan','tb_asset_transaction.updated_at','note')
                ->where('tb_asset_transaction.no_peminjaman',$request->id_transaction)
                ->get(),$request->id_transaction);
    }

    public function getdetailAsset(Request $request)
    {
        // $id_transaction = $request['btn_detail'];

        return array(DB::table('tb_asset_transaction')
                ->join('users','users.nik','=','tb_asset_transaction.nik_peminjam')
                ->select('no_peminjaman', 'name','tgl_peminjaman','tgl_pengembalian','id_kat','nik_peminjam','qty_akhir','keterangan')
                ->where('tb_asset_transaction.id_transaction',$request->id_transaction)
                ->get(),$request->id_transaction);
    }

    public function getsn(Request $request)
    {
        // $id_transaction = $request['btn_detail'];

        return array(DB::table('tb_detail_asset_transaction')
                ->join('tb_asset_transaction','tb_asset_transaction.id_transaction','=','tb_detail_asset_transaction.id_transaction')
                ->join('tb_asset', 'tb_detail_asset_transaction.id_barang', '=', 'tb_asset.id_barang')
                ->select('nama_barang', 'serial_number','code_asset')
                ->where('no_peminjaman',$request->id_transaction)
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

    public function getLogAssetTech(Request $request)
    {
        return array("data"=>LogAssetTech::Leftjoin('users','users.nik','=','tb_log_asset_tech.nik')
                            ->select('users.name','tb_log_asset_tech.nik','tb_log_asset_tech.created_at','tb_log_asset_tech.keterangan')
                            ->orderBy('created_at','desc')
                            ->get());
    }

    public function exportExcelTech(Request $request)
    {
        $spreadsheet = new Spreadsheet();

        $prSheet = new Worksheet($spreadsheet,'Report List Asset');
        $spreadsheet->addSheet($prSheet);
        $spreadsheet->removeSheetByIndex(0);
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->mergeCells('A1:F1');
        $normalStyle = [
            'font' => [
                'name' => 'Calibri',
                'size' => 11
            ],
        ];

        $titleStyle = $normalStyle;
        $titleStyle['alignment'] = ['horizontal' => Alignment::HORIZONTAL_CENTER];
        $titleStyle['font']['bold'] = true;

        $sheet->getStyle('A1:G1')->applyFromArray($titleStyle);
        $sheet->setCellValue('A1','Report List Asset');

        $headerStyle = $normalStyle;
        $headerStyle['font']['bold'] = true;
        $sheet->getStyle('A2:G2')->applyFromArray($headerStyle);;

        $headerContent = ["NO", "NAMA BARANG", "DESCRYPTION/TYPE", "SERIAL NUMBER","KETERANGAN", "DATE","STATUS"];
        $sheet->fromArray($headerContent,NULL,'A2');
        
        $datas = Tech_asset::select('nama_barang','description','serial_number','location','updated_at','status')->orderBy('updated_at','DESC')->get();

        foreach ($datas as $key => $eachLead) {
            $sheet->fromArray(array_merge([$key + 1],array_values($eachLead->toArray())),NULL,'A' . ($key + 3));
        }

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);


        $fileName = 'Report List Asset ' . date('Y') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        
        $writer = new Xlsx($spreadsheet);
        ob_end_clean();
        return $writer->save("php://output");

        $tambah_log = new LogAssetTech();
        $tambah_log->nik = Auth::User()->nik;
        $tambah_log->keterangan     = "Exporting List Asset";
        $tambah_log->save();

        // return redirect()->back();
    }
}
