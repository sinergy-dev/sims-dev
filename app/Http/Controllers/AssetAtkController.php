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
use Carbon\Carbon;

use Validator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

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
                    ->join('tb_asset_atk','tb_asset_atk.id_barang','=','tb_asset_atk_request.id_barang')
                    ->select('nama', 'tb_asset_atk_request.status', 'tb_asset_atk_request.qty', 'keterangan', 'tb_asset_atk_request.created_at', 'name', 'link', 'tb_asset_atk_request.id_barang','tb_asset_atk_request.nik','tb_asset_atk.qty as qty_asset')
                    ->orderBy('tb_asset_atk_request.created_at','desc')
                    ->where('tb_asset_atk_request.status', 'PROCESS')
                    ->orWhere('tb_asset_atk_request.status', 'REQUEST')
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

        $month = AssetAtkChangelog::selectRaw('LEFT(`created_at`, 7) AS `month`')->groupBy('month')->limit(10)->orderBy('month', 'desc')->get()->pluck('month');
        // return $month;

        $month_formatted = [];

        foreach ($month as $data) {
            array_push($month_formatted, Carbon::parse($data . "-01")->format("F Y"));
        }

        $unit_assets = AssetAtk::select('unit')->where('unit', '<>', null)->groupBy('unit')->get();

        $sidebar_collapse = true;

        $cek = AssetAtk::join('tb_asset_atk_transaction', 'tb_asset_atk_transaction.id_barang', '=', 'tb_asset_atk.id_barang', 'left')->select('tb_asset_atk_transaction.id_barang')->get();

    	return view('HR/asset_atk',compact('notif', 'notifc', 'notifsd', 'notiftp', 'notifOpen', 'notifClaim', 'asset', 'assetsd', 'pinjaman', 'atk', 'cek', 'pr_request', 'pr_request2', 'unit_assets', 'request', 'request2', 'month', 'month_formatted','sidebar_collapse'))->with(['initView'=> $this->initMenuBase(),'feature_item'=>$this->RoleDynamic('asset_atk')]);
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

        $asset = DB::table('tb_asset_atk_transaction')
                    ->join('users','users.nik','=','tb_asset_atk_transaction.nik_peminjam')
                    ->join('tb_asset_atk','tb_asset_atk.id_barang','=','tb_asset_atk_transaction.id_barang')
                    ->select('tb_asset_atk.description','tb_asset_atk_transaction.nik_peminjam','tb_asset_atk_transaction.id_transaction','tb_asset_atk_transaction.id_barang','users.name','tb_asset_atk_transaction.qty_akhir','tb_asset_atk_transaction.created_at','tb_asset_atk_transaction.updated_at','tb_asset_atk.nama_barang','tb_asset_atk_transaction.status', 'no_transac', 'tb_asset_atk_transaction.keterangan', 'tb_asset_atk_transaction.note')
                    ->where('tb_asset_atk.id_barang',$id_barang)
                    ->orderBy('tb_asset_atk_transaction.created_at', 'desc')
                    ->get();

        $last_update = AssetAtkChangelog::join('users', 'users.nik', '=', 'tb_asset_atk_changelog.nik')->select('status','tb_asset_atk_changelog.created_at', 'name')->where('id_barang',$id_barang)->orderBy('tb_asset_atk_changelog.id','desc')->first();

        $data = AssetAtk::select('qty','unit','nama_barang')->where('id_barang',$id_barang)->first();

        return view('HR/detail_asset_atk',compact('notif', 'notifc', 'notifsd', 'notiftp', 'notifOpen', 'notifClaim', 'asset', 'data', 'last_update'))->with(['initView'=> $this->initMenuBase()]);
    }

    public function getSaldoAtk(Request $request)
    {
        $detail = AssetAtkChangelog::join('users', 'users.nik', '=', 'tb_asset_atk_changelog.nik')
                    ->join('tb_asset_atk', 'tb_asset_atk.id_barang', '=', 'tb_asset_atk_changelog.id_barang')
                    ->select('users.name', 'tb_asset_atk_changelog.created_at', 'tb_asset_atk_changelog.status', 'tb_asset_atk_changelog.qty', 'nama_barang', 'unit')
                    ->where('tb_asset_atk.id_barang', $request->id_barang)
                    ->orderBy('tb_asset_atk_changelog.id', 'desc')
                    ->get();

        return array("data"=>$detail);
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

    public function getSummaryAtk(Request $request)
    {
        $summary = AssetAtkChangelog::selectRaw('SUM(CASE WHEN `status` = "In" THEN 1 ELSE 0 END) AS `sum_in`')
            ->selectRaw('SUM(CASE WHEN `status` = "Out" THEN 1 ELSE 0 END) AS `sum_out`')
            ->selectRaw('LEFT(`created_at`, 7) AS `month`')
            ->where('id_barang', $request->id_barang)
            ->groupBy('month')
            ->whereYear('created_at', date('Y'))
            ->get();        

        return array("data"=>$summary);
    }

    public function getSummaryQty(Request $request)
    {
        $summary = AssetAtkChangelog::selectRaw('SUM(CASE WHEN `status` = "In" THEN qty ELSE 0 END) AS `sum_in`')
            ->selectRaw('SUM(CASE WHEN `status` = "Out" THEN qty ELSE 0 END) AS `sum_out`')
            ->selectRaw('LEFT(`created_at`, 7) AS `month`')
            ->where('id_barang', $request->id_barang)
            ->groupBy('month')
            ->whereYear('created_at', date('Y'))
            ->get();        

        return array("data"=>$summary);
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

    public function getMostRequest(Request $request)
    {
        $getData = AssetAtkTransaction::join('users', 'users.nik', '=', 'tb_asset_atk_transaction.nik_peminjam')->select(DB::raw('SUM(qty_akhir) as qty'), 'name')->where('tb_asset_atk_transaction.id_barang', $request->id_barang)->where('tb_asset_atk_transaction.status', 'ACCEPT')->whereMonth('tb_asset_atk_transaction.updated_at', date('m'))->whereYear('tb_asset_atk_transaction.updated_at', date('Y'))->groupBy('nik_peminjam')->orderBy('qty', 'asc')->get();

        return array("data"=>$getData);
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

        $get_divisi_hr = User::select('email','id_position', 'id_division')->where('id_position', 'HR MANAGER')->first();

        $receiver = $get_email_user->concat([$get_divisi_hr]);

        // $receiver_final = $receiver->all();
        $receiver_final = $get_email_user;

        $req_atk = AssetAtkRequest::join('users', 'tb_asset_atk_request.nik', '=', 'users.nik')
                    ->select('nama', 'qty', 'name', 'keterangan', 'status', 'link', 'tb_asset_atk_request.created_at')
                    ->where('tb_asset_atk_request.id_barang', $request->id_barang)
                    ->first();
        // return $req_atk;

        // foreach ($receiver_final as $final) {
            Mail::to($kirim)->send(new RequestATK('[SIMS-App] Request ATK sedang diproses', $req_atk,$kirim->id_position,$kirim->id_division,$get_email_user, 'PROCESS','ATK'));
        // }

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

        $get_divisi_hr = User::select('email','id_position', 'id_division')->where('id_position', 'HR MANAGER')->first();

        $receiver = $get_email_user->concat([$get_divisi_hr]);

        // $receiver_final = $receiver->all();

        $receiver_final = $get_email_user;

        $req_atk = AssetAtkRequest::join('users', 'tb_asset_atk_request.nik', '=', 'users.nik')
                    ->select('nama', 'qty', 'name', 'keterangan', 'status', 'link', 'note_reject', 'tb_asset_atk_request.created_at')
                    ->where('tb_asset_atk_request.id_barang', $request['id_barang_done2'])
                    ->first();

        // foreach ($receiver_final as $final) {
            Mail::to($kirim)->send(new RequestATK('[SIMS-App] Request ATK Sudah Datang', $req_atk,$kirim->id_position,$kirim->id_division,$get_email_user, 'DONE','ATK'));
        // }

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
    	$inc = DB::table('tb_asset_atk_transaction')->select('id_transaction')->get();
        $increment = count($inc);
        $nomor = $increment+1;
        if($nomor < 10){
            $nomor = '00' . $nomor;
        }

        $no_peminjaman = date('ymd').$nomor;

        $data = collect();
        foreach ($request->data as $value) {

            $nama_barang = $value['nama_barang'];

            $tambah = new AssetAtkTransaction();
            $tambah->id_barang = $value['id_barang'];
            $tambah->no_transac = $value['ket'];
            $tambah->qty_akhir = $value['qty_request'];
            $tambah->qty_awal = $value['qty_awal'];
            $tambah->keterangan = $value['ket'];
            $tambah->nik_peminjam = Auth::User()->nik;
            $tambah->status = 'PENDING';
            $tambah->save();

            $datas = [
                'id_barang'     => $tambah->id_barang,
                'qty_akhir'     => $tambah->qty_akhir,
                'keterangan'    => $tambah->keterangan,
                'nama_barang'   => $nama_barang
            ];

            $data->push($datas);
        }

        $get_id_transac = AssetAtkTransaction::select('id_transaction')->where('id_barang', $request->id_barang)->orderBy('created_at','desc')->first();

        // $get_divisi_hr = User::select('email','id_position', 'name', 'id_division')->where('email', 'yudhi@sinergy.co.id')->first();

        // $get_email_manager = collect([$get_divisi_hr]);

        // $get_divisi_hr2 = User::select('email','id_position', 'name')->where('id_position', 'WAREHOUSE')->where('id_company', '1')->first();

        // $receiver = $get_email_manager->concat([$get_divisi_hr2]);

        // $receiver_final = $receiver->all();

        // $receiver_final = $get_email_manager;
        $get_divisi_hr = User::select('email','id_position', 'name', 'id_division')->where('email', 'andi@sinergy.co.id')->first();
        // $get_email_cc = User::select('email','id_position', 'name', 'id_division')->where('email', 'yudhi@sinergy.co.id')->first();

        $req_atk = collect(['variable'=>$data,'nama_peminjam'=>Auth::User()->name,'request_date'=>date("Y-m-d h:i:s"),'status'=>'REQUEST']);

        // foreach ($receiver_final as $final) {
            Mail::to($get_divisi_hr)->send(new RequestATK('[SIMS-App] Request ATK', $req_atk,$get_divisi_hr->id_position,$get_divisi_hr->id_division,$get_divisi_hr,'PENDING','ATK'));
        // }

		return redirect()->back()->with('update', 'Request ATK akan diproses!');
    }

    public function store_request_atk(Request $request)
    {
        $data = collect();
        foreach ($request->data as $value) {
            $tambah = new AssetAtkRequest();
            $tambah->nama = $value['name'];
            $tambah->qty = $value['qty'];
            $tambah->keterangan = $value['ket'];
            $tambah->nik = Auth::User()->nik;
            $tambah->status = 'REQUEST';
            $tambah->link = $value['link'];
            $tambah->save();

            $datas = [
                'nama'          => $tambah->nama,
                'qty'           => $tambah->qty,
                'keterangan'    => $tambah->keterangan,
                'link'          => $tambah->link
            ];
            $data->push($datas);
        }

        $get_id_transac = AssetAtkRequest::select('id_barang')->orderBy('created_at','desc')->first();

        // $get_divisi_hr = User::select('email','id_position', 'id_division', 'name')->where('email', 'yudhi@sinergy.co.id')->first();

        // $get_email_manager = collect([$get_divisi_hr]);

        // $get_divisi_hr2 = User::select('email','id_position', 'name')->where('id_position', 'WAREHOUSE')->where('id_company', '1')->first();

        // $receiver = $get_email_manager->concat([$get_divisi_hr2]);

        // $receiver_final = $receiver->all();

        // $receiver_final = $get_email_manager;

        $get_divisi_hr = User::select('email','id_position', 'name', 'id_division')->where('email', 'andi@sinergy.co.id')->first();
        // $get_email_cc = User::select('email','id_position', 'name', 'id_division')->where('email', 'yudhi@sinergy.co.id')->first();

        $req_atk = collect(['variable'=>$data,'nama_peminjam'=>Auth::User()->name,'request_date'=>date("Y-m-d h:i:s"),'status'=>'REQUEST']);

        // foreach ($receiver_final as $final) {
            Mail::to($get_divisi_hr)->send(new RequestATK('[SIMS-App] Request ATK Baru', $req_atk,$get_divisi_hr->id_position,$get_divisi_hr->id_division,$get_divisi_hr,'REQUEST','ATK'));
        // }

        return redirect()->back()->with('update', 'Request ATK akan diproses!');
    }

    public function accept_request(Request $request)
    {
        $cek_status = AssetAtkTransaction::select('id_barang', 'id_transaction')->where('id_barang', $request->id_barang)->where('status', 'PENDING')->where('id_transaction', '!=', $request->id_transaction)->first();
        $count_status = AssetAtkTransaction::select('id_barang', 'id_transaction')->where('id_barang', $request->id_barang)->where('status', 'PENDING')->where('id_transaction', '!=', $request->id_transaction)->count();

        $cek_qty = AssetAtkTransaction::select('qty_awal', 'qty_akhir')->where('id_transaction', $request->id_transaction)->where('id_barang', $request->id_barang)->first();
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


        $kirim = AssetAtkTransaction::join('users', 'users.nik', '=', 'tb_asset_atk_transaction.nik_peminjam')->select('users.email')->where('id_transaction',$request->id_transaction)->first();

        $get_email_user = collect([$kirim]);

        $get_divisi_hr = User::select('email','id_position', 'id_division')->where('id_position', 'HR MANAGER')->first();

        $receiver = $get_email_user->concat([$get_divisi_hr]);

        // $receiver_final = $receiver->all();

        $receiver_final = $get_email_user;

        $req_atk = AssetAtk::join('tb_asset_atk_transaction', 'tb_asset_atk.id_barang','=', 'tb_asset_atk_transaction.id_barang')
                    ->join('users', 'tb_asset_atk_transaction.nik_peminjam', '=', 'users.nik')
                    ->select('nama_barang', 'qty_akhir', 'qty_request', 'tb_asset_atk_transaction.status', 'name', 'keterangan', 'tb_asset_atk_transaction.created_at')
                    ->where('tb_asset_atk_transaction.id_transaction', $request->id_transaction)
                    ->first();
        // return $req_atk;

        // foreach ($receiver_final as $final) {
            Mail::to($kirim)->send(new RequestATK('[SIMS-App] Request ATK Diterima', $req_atk,$kirim->id_position,$kirim->id_division,$get_email_user, 'ACCEPT','ATK'));
        // }

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

        $update             = AssetAtkTransaction::where('id_transaction',$request->id_transaction)->first();
        $update->status     = 'REJECT';
        $update->note       = $request->reason;
        $update->update();

        $kirim = AssetAtkTransaction::join('users', 'users.nik', '=', 'tb_asset_atk_transaction.nik_peminjam')->select('users.email')->where('id_transaction',$request->id_transaction)->first();

        $get_email_user = collect([$kirim]);

        // $get_divisi_hr = User::select('email','id_position', 'id_division')->where('email', 'yudhi@sinergy.co.id')->first();

        // $receiver = $get_email_user->concat([$get_divisi_hr]);

        // $receiver_final = $receiver->all();

        $receiver_final = $get_email_user;

        $req_atk = AssetAtk::join('tb_asset_atk_transaction', 'tb_asset_atk.id_barang','=', 'tb_asset_atk_transaction.id_barang')
                    ->join('users', 'tb_asset_atk_transaction.nik_peminjam', '=', 'users.nik')
                    ->select('nama_barang', 'qty_akhir', 'qty_request', 'tb_asset_atk_transaction.status', 'name', 'keterangan', 'tb_asset_atk_transaction.created_at','note')
                    ->where('tb_asset_atk_transaction.id_transaction', $request->id_transaction)
                    ->first();

        // foreach ($receiver_final as $final) {
            Mail::to($kirim)->send(new RequestATK('[SIMS-App] Request ATK Ditolak', $req_atk,$kirim->id_position,$kirim->id_division,$get_email_user, 'REJECT','ATK'));
        // }

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

        $get_divisi_hr = User::select('email','id_position', 'id_division')->where('id_position', 'HR MANAGER')->first();

        $receiver = $get_email_user->concat([$get_divisi_hr]);

        $receiver_final = $receiver->all();

        $req_atk = AssetAtkRequest::join('users', 'tb_asset_atk_request.nik', '=', 'users.nik')
                    ->select('nama', 'qty', 'name', 'keterangan', 'status', 'link', 'note_reject', 'tb_asset_atk_request.created_at')
                    ->where('tb_asset_atk_request.id_barang', $request->id_barang)
                    ->first();
        // return $req_atk;

        $receiver_final = $get_email_user;

        // foreach ($receiver_final as $final) {
            Mail::to($kirim)->send(new RequestATK('[SIMS-App] Request ATK ditolak', $req_atk,$kirim->id_position,$kirim->id_division,$get_email_user,'REJECTED','ATK'));
        // }

        return redirect()->back()->with('update', 'Successfully!');   
    }

    public function reportExcel(Request $request)
    {
        $spreadsheet = new Spreadsheet();

        $prSheet = new Worksheet($spreadsheet,'LAPORAN PENGGUNAAN ATK');
        $spreadsheet->addSheet($prSheet);
        $spreadsheet->removeSheetByIndex(0);
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->mergeCells('A1:I1');
        $normalStyle = [
            'font' => [
                'name' => 'Calibri',
                'size' => 11
            ],
        ];

        $titleStyle = $normalStyle;
        $titleStyle['alignment'] = ['horizontal' => Alignment::HORIZONTAL_CENTER];
        $titleStyle['borders'] = ['outline' => ['borderStyle' => Border::BORDER_THIN]];
        $titleStyle['fill'] = ['fillType' => Fill::FILL_SOLID];
        $titleStyle['font']['bold'] = true;

        $dateReport = Carbon::parse($request->month  . "/01/" . $request->year);
        $sheet->getStyle('A1:I1')->applyFromArray($titleStyle);
        $sheet->setCellValue('A1','LAPORAN PENGGUNAAN ALAT TULIS KANTOR');
        $sheet->setCellValue('B2','Bulan ' . $dateReport->format("F"));
        $sheet->setCellValue('B3','Tahun ' . $request->year);
        $sheet->setCellValue('B4','Report Pada ' . date('Y-m-d'));

        $headerStyle = $normalStyle;
        $headerStyle['font']['bold'] = true;
        $headerStyle['alignment'] = ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER];
        $sheet->getStyle('A6:I7')->applyFromArray($headerStyle);

        $headerContent = ["No", "Jenis Barang", "In", "", "Out",  "", "Tanggal Terakhir Request", "Jumlah Terakhir", ""];
        $sheet->fromArray($headerContent,NULL,'A6');

        $headerContent = ["", "", "Jumlah", "Unit", "Jumlah",  "Unit", "", "Jumlah", "Unit"];
        $sheet->fromArray($headerContent,NULL,'A7');
        $sheet->mergeCells("A6:A7");
        $sheet->mergeCells("B6:B7");
        $sheet->mergeCells("C6:D6");
        $sheet->mergeCells("E6:F6");
        $sheet->mergeCells("G6:G7");
        $sheet->mergeCells("H6:I6");

        $latestrequest = AssetAtkChangelog::selectRaw('`id_barang`, LEFT(MAX(`tb_asset_atk_changelog`.`created_at`),10) AS `latest_date_request`')->where('status', 'Out')->groupby('id_barang');

        $change_log = AssetAtkChangelog::selectRaw('`id_barang`, SUM(CASE WHEN `tb_asset_atk_changelog`.`status` = "In" THEN `tb_asset_atk_changelog`.`qty` ELSE 0 END) AS `sum_in` ')
            ->selectRaw('SUM(CASE WHEN `tb_asset_atk_changelog`.`status` = "Out" THEN `tb_asset_atk_changelog`.`qty` ELSE 0 END) AS `sum_out`')
            ->whereMonth('tb_asset_atk_changelog.created_at', $request->month)
            ->whereYear('tb_asset_atk_changelog.created_at', $request->year)
            ->groupBy('tb_asset_atk_changelog.id_barang');

        $change_logAll = AssetAtk::leftJoinSub($change_log, 'change_log',function($join){
                    $join->on("change_log.id_barang", '=', 'tb_asset_atk.id_barang');
                })
                ->leftJoinSub($latestrequest, 'latestrequest',function($join){
                    $join->on("latestrequest.id_barang", '=', 'tb_asset_atk.id_barang');
                })
                ->selectRaw('`nama_barang`, IFNULL(`sum_in`,"0") AS `sum_in`, `unit` as `unit_in`, IFNULL(`sum_out`,"0") AS `sum_out`, `unit`  as `unit_out`, IFNULL(`latest_date_request`,"-") AS `latest_date_request`, IFNULL(`qty`,"0") AS `qty_akhir`, `unit`')
                ->get();

        foreach ($change_logAll as $key => $data) {
            $sheet->fromArray(array_merge([$key + 1],array_values($data->toArray())),NULL,'A' . ($key + 8));
        }

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);


        $fileName = 'LAPORAN PENGGUNAAN ATK ' . date('Y-m-d') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        
        $writer = new Xlsx($spreadsheet);
        ob_end_clean();
        return $writer->save("php://output");

    }
}
