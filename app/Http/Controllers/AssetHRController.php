<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use DB;
use App\AssetHR;
use App\DetailAssetHR;
use App\User;
use Excel;

class AssetHRController extends Controller
{
    public function index()
    {
        //testestes

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

        $asset = DB::table('tb_asset_hr')
                ->Leftjoin(DB::raw("(
                    SELECT
                        `id_barang` AS `id_barang`, substring_index(group_concat(`name` ORDER BY `id_transaction` DESC SEPARATOR ','), ',', 1) AS `name`
                    FROM
                        `tb_asset_hr_transaction`
                    LEFT JOIN `users` ON `users`.`nik` = `tb_asset_hr_transaction`.`nik_peminjam`
                    WHERE `status` = 'ACCEPT'
                    GROUP BY
                        `id_barang`
                    ) as tb_asset_hr_transaction"),function($join){
                    $join->on("tb_asset_hr.id_barang","=","tb_asset_hr_transaction.id_barang");
                })
                ->select('nama_barang', 'tb_asset_hr.id_barang','status','description','code_name', 'serial_number','name','lokasi')
                ->where('availability',1)
                ->get();

        $assetsd    = DB::table('tb_asset_hr_transaction')
                    ->join('users','users.nik','=','tb_asset_hr_transaction.nik_peminjam')
                    ->join('tb_asset_hr','tb_asset_hr.id_barang','=','tb_asset_hr_transaction.id_barang')
                    ->select('tb_asset_hr_transaction.id_transaction','tb_asset_hr_transaction.id_barang','tb_asset_hr.nama_barang','tb_asset_hr.description','users.name','tb_asset_hr_transaction.nik_peminjam','tb_asset_hr.status', 'tb_asset_hr_transaction.keterangan', 'tgl_pengembalian', 'tgl_peminjaman', 'no_transac')
                    ->where('tb_asset_hr.availability',1)
                    ->get();

        $pinjaman = DB::table('tb_asset_hr_transaction')
                    ->join('users','users.nik','=','tb_asset_hr_transaction.nik_peminjam')
                    ->join('tb_asset_hr','tb_asset_hr.id_barang','=','tb_asset_hr_transaction.id_barang')
                    ->select('tb_asset_hr.description','tb_asset_hr_transaction.nik_peminjam','tb_asset_hr_transaction.id_transaction','tb_asset_hr_transaction.id_barang','users.name','tb_asset_hr_transaction.created_at','tb_asset_hr_transaction.updated_at','tb_asset_hr.nama_barang','tb_asset_hr.status', 'no_transac')
                    ->where('tb_asset_hr_transaction.nik_peminjam',Auth::User()->nik)
                    ->where('tb_asset_hr.availability',1)
                    ->get();

        $kategori_asset = DB::table('tb_kategori_asset_hr')
        				->Leftjoin(DB::raw("(
                        SELECT
                            COUNT(`kategori`) AS `count_kategori`, `kategori`
                        FROM
                            `tb_asset_hr`
                        GROUP BY
                            `kategori`
                      	) as tb_asset_hr"),function($join){
                        $join->on("tb_kategori_asset_hr.id","=","tb_asset_hr.kategori");
                    	})
                      	->select('tb_kategori_asset_hr.kategori','code_kat',DB::raw('tb_asset_hr.count_kategori as qty_kat'),'id')
        				->get();

        $current_borrowed = DB::table('tb_asset_hr_transaction')
                        ->join('tb_asset_hr','tb_asset_hr.id_barang','=','tb_asset_hr_transaction.id_barang')
                        ->select('tb_asset_hr.description','serial_number','tgl_peminjaman','tgl_pengembalian','tb_asset_hr.note','no_transac','nama_barang','code_name','id_transaction')
                        ->where('nik_peminjam',Auth::User()->nik)
                        ->where('tb_asset_hr_transaction.status','ACCEPT')
                        ->where('tgl_pengembalian',NULL)
                        ->get();

        $request_asset = DB::table('tb_asset_hr_transaction')
                        ->join('tb_asset_hr','tb_asset_hr.id_barang','=','tb_asset_hr_transaction.id_barang')
                        ->join('users','users.nik','=','tb_asset_hr_transaction.nik_peminjam')
                        ->select('name','nama_barang','description','no_transac','tb_asset_hr.note','nik_peminjam','tb_asset_hr_transaction.id_barang','id_transaction')
                        ->where('tb_asset_hr_transaction.status','PENDING')
                        ->get();

        $users = User::select('name','nik')->where('status_karyawan','!=','dummy')->get();

        $inc = DB::table('tb_asset_hr')->get();
        $increment = count($inc);

        $nomor = $increment+1;
        if($nomor < 10){
            $nomor = '00' . $nomor;
        }else if ($nomor > 10 && $nomor < 99) {
            $nomor = '0' . $nomor;
        }

    	return view('HR/asset_hr',compact('notif', 'notifc', 'notifsd', 'notiftp', 'notifOpen', 'notifClaim', 'asset', 'assetsd', 'pinjaman','users','nomor','user_pinjam','kategori_asset','current_borrowed','request_asset'));
    }

    public function getDetailBorrowed(Request $request){
        return DB::table('tb_asset_hr_transaction')
                ->join('tb_asset_hr','tb_asset_hr.id_barang','=','tb_asset_hr_transaction.id_barang')
                ->select('keterangan','serial_number','tgl_peminjaman','tgl_pengembalian','tb_asset_hr_transaction.note','no_transac','nama_barang','code_name','id_transaction')->where('id_transaction',$request->id_transaction)->get();
    }

    public function getPengembalian(Request $request){

        $asset = DB::table('tb_asset_hr')
                ->join('tb_asset_hr_transaction','tb_asset_hr_transaction.id_barang','=','tb_asset_hr.id_barang','inner')
                ->join('users', 'users.nik', '=', 'tb_asset_hr_transaction.nik_peminjam')
                ->select('nama_barang', 'tb_asset_hr.id_barang', 'description','code_name','tb_asset_hr_transaction.id_transaction','name')
                ->where('tb_asset_hr_transaction.id_barang',$request->id_barang)
                ->orderBy('tb_asset_hr_transaction.id_transaction','desc')
                ->get();

        return $asset;
    }

    public function getEditAsset(Request $request){
        $asset = DB::table('tb_asset_hr')
                ->select('nama_barang', 'tb_asset_hr.id_barang', 'description','code_name','status','serial_number',DB::raw('DATEDIFF(tgl_tambah,created_at) AS umur_asset'))
                ->where('tb_asset_hr.id_barang',$request->id_barang)
                ->get();

        return $asset;

        //tambahhhhh
    }

    public function getCategory(Request $request){

    	return array("results" => DB::table('tb_kategori_asset_hr')->select(DB::raw("`id` AS `no`,`code_kat` AS `id`,`kategori` AS `text`"))->get());
    }

    public function getdetail(Request $request)
    {
    	$id_transaction = $request['btn_accept'];

        return array(DB::table('tb_asset_hr_transaction')
        		->join('tb_asset_hr', 'tb_asset_hr_transaction.id_barang', '=', 'tb_asset_hr.id_barang')
                ->select('nama_barang')
                ->where('id_transaction',$request->id_transaction)
                ->get(),$request->id_transaction);
    }

    public function getdetail2(Request $request)
    {
    	$id_transaction = $request['btn_reject'];

        return array(DB::table('tb_asset_hr_transaction')
        		->join('tb_asset_hr', 'tb_asset_hr_transaction.id_barang', '=', 'tb_asset_hr.id_barang')
                ->select('nama_barang')
                ->where('id_transaction',$request->id_transaction)
                ->get(),$request->id_transaction);
    }

    public function store(Request $request)
    {
        $tambah                 = new AssetHR();
        $tambah->nik            = Auth::User()->nik;
        $tambah->code_name      = $request['asset_code'];
        $tambah->kategori    	= $request['category_id'];
        $tambah->nama_barang    = $request['nama_barang'];
        $tambah->status         = "AVAILABLE";
        $edate          = strtotime($_POST['asset_date']); 
        $edate          = date("Y-m-d",$edate);
        $tambah->tgl_tambah     = $edate;
        $tambah->serial_number  = $request['asset_sn'];
        $tambah->description    = $request['keterangan'];
        $tambah->note    		= $request['note'];
        $tambah->lokasi         = $request['lokasi'];
        $tambah->save();

        return redirect()->back()->with('success', 'Create New Asset Successfully!');
    }

    public function store_kategori(Request $request){
        if ($request['status_kategori'] == 'edit') {
            DB::table('tb_kategori_asset_hr')->where('id',$request['id_kategori'])->update(
                ['kategori' => $request['kategori'], 
                 'code_kat' => $request['kode_kategori'],
                ]
            );

            $line = 'Update Kategori Successfully!';
        }else{
            DB::table('tb_kategori_asset_hr')->insert(
                ['kategori' => $request['kategori'], 
                 'code_kat' => $request['kode_kategori'],
                ]
            );

            $line = 'Create New Kategori Successfully!';
        }
    	

		return redirect()->back()->with('success', $line);
    }

    public function detail_asset($id_barang)
    {
        $asset = DB::table('tb_asset_hr_transaction')
                ->join('users','users.nik','=','tb_asset_hr_transaction.nik_peminjam')
                ->join('tb_asset_hr','tb_asset_hr.id_barang','=','tb_asset_hr_transaction.id_barang')
                ->select('tb_asset_hr_transaction.nik_peminjam','tb_asset_hr_transaction.id_transaction','tb_asset_hr_transaction.id_barang','users.name','tb_asset_hr.nama_barang','tb_asset_hr.status', 'tb_asset_hr_transaction.tgl_peminjaman', 'tb_asset_hr_transaction.tgl_pengembalian', 'tb_asset_hr_transaction.keterangan', 'tb_asset_hr_transaction.note', 'no_transac')
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

        $update = AssetHR::where('id_barang',$id_barang)->first();
        if (Auth::User()->id_division == 'HR') {
            $update->status = 'UNAVAILABLE';
        }else{
            $update->status = 'PENDING';
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
        if (Auth::User()->id_division == 'HR') {
            $store->nik_peminjam    = $request['users'];
            $store->status          = 'ACCEPT';

            $line = 'Peminjaman Barang Berhasil!';
        }else{
            $store->nik_peminjam    = Auth::User()->nik;
            $store->status          = 'PENDING';

            $line = 'Peminjaman Barang Akan diproses!';
        }        
        $store->keterangan      = $request['keperluan'];
        $store->tgl_peminjaman  = date('Y-m-d');
        $store->no_transac		= $no_peminjaman;        
        $store->save();

        // $update_rejects = DetailAssetHR::where('id_barang',$request->id_barang)
        //     ->where('nik_peminjam','<>',$request['users'])
        //     ->where('status','PENDING')
        //     ->get();

        // foreach ($update_rejects as $update_reject) {
        //     $update_reject->status = "REJECT";
        //     $update_reject->update();
        // }

        return redirect()->back()->with('alert', $line);
    }

    public function acceptPeminjaman(Request $request){
        $update = AssetHR::where('id_barang',$request->id_barang)->first();
        if ($request->status == 'ACCEPT') {
            $update->status = 'UNAVAILABLE';
        }else{
            $update->status = 'AVAILABLE';
        }
        $update->update(); 

        // $update_accept =  DetailAssetHR::where('nik_peminjam',$request->nik_peminjam)
        //         ->where('id_barang',$request->id_barang)    
        //         ->first();

        $update_accept =  DetailAssetHR::where('id_transaction',$request->id_transaction)
                ->first();
        if ($request->status == 'ACCEPT') {
            $update_accept->status = "ACCEPT";
        }else{
            $update_accept->status = "REJECT";
        }
        $update_accept->save();

        // $update_rejects = DetailAssetHR::where('id_barang',$request->id_barang)
        //     ->where('nik_peminjam','<>',$request->nik_peminjam)
        //     ->where('status','PENDING')
        //     ->get();

        // foreach ($update_rejects as $update_reject) {
        //     $update_reject->status = "REJECT";
        //     $update_reject->save();
        // }

        return redirect()->back()->with('alert','Asset Request Accepted!');
        
    }

    public function penghapusan(Request $request)
    {
        $id_barang = $request['id_barang'];

        $update = AssetHR::where('id_barang',$id_barang)->first();
        $update->availability = 0;
        $update->update();  

        return redirect()->back()->with('alert', 'Penghapusan Barang Berhasil!');
    }

    public function accept_pinjam(Request $request)
    {
        $id_transaction = $request['id_transaction_update'];

        $id_barang   = $request['id_barang_update'];

        $update             = DetailAssetHR::where('id_transaction',$id_transaction)->first();
        $update->update();

        return redirect()->back()->with('success', 'Peminjaman Telah di verifikasi!');; 
    }

    public function reject_pinjam(Request $request)
    {
        $id_transaction = $request['id_transaction_reject'];

        $id_barang   = $request['id_barang_reject'];

        $update_asset       = AssetHR::where('id_barang',$id_barang)->first();
        $update_asset->qty  = 1;
        $update_asset->update();
                

        $update         = DetailAssetHR::where('id_transaction',$id_transaction)->first();
        $update->update();

        return redirect()->back()->with('danger', 'Peminjaman Telah di Reject!');
    }

    public function kembali(Request $request)
    {
        $id_barang = $request['id_barang_kembali'];

        $id_transaction   = $request['id_transaction_kembali'];

        $update_asset       = AssetHR::where('id_barang',$id_barang)->first();
        $update_asset->status     = 'AVAILABLE';
        $update_asset->update();

        $update         = DetailAssetHR::where('id_transaction',$id_transaction)->first();
        $update->tgl_pengembalian = $request['tanggal_kembali'];
        $update->update();

        $asset = DB::table('tb_asset_hr')
                ->join('tb_asset_hr_transaction','tb_asset_hr_transaction.id_barang','=','tb_asset_hr.id_barang','inner')
                ->join('users', 'users.nik', '=', 'tb_asset_hr_transaction.nik_peminjam')
                ->select('nama_barang', 'tb_asset_hr.id_barang', 'description','code_name','tb_asset_hr_transaction.id_transaction','name')
                ->where('tb_asset_hr_transaction.id_barang',$request->id_barang)
                ->orderBy('tb_asset_hr_transaction.id_transaction','desc')
                ->first();

        return redirect()->back()->with('success','Barang sudah dikembalikan!');
    }

    public function edit_asset(Request $request){

        $id_barang = $request['id_barang_asset_edit'];

        $update_asset                   = AssetHR::where('id_barang',$id_barang)->first();
        $update_asset->description      = $request['keterangan_edit'];
        $update_asset->serial_number    = $request['asset_sn_edit'];
        $update_asset->status           = $request['select-status'];
        $update_asset->lokasi           = $request['lokasi_edit'];
        $update_asset->update();

        return redirect()->back()->with('alert', 'Barang Telah di Update !');

    }

    public function export(Request $request)
    {
        $nama = 'List Asset '.date('Y-m-d');
        Excel::create($nama, function ($excel) use ($request) {
            // $excel->sheet('List Asset', function ($sheet) use ($request) {
            
            //     $sheet->mergeCells('A1:F1');

            //    // $sheet->setAllBorders('thin');
            //     $sheet->row(1, function ($row) {
            //         $row->setFontFamily('Calibri');
            //         $row->setFontSize(12);
            //         $row->setAlignment('center');
            //         $row->setFontWeight('bold');
            //     });

            //     $sheet->row(1, array('LIST ASSET'));

            //     $sheet->row(2, function ($row) {
            //         $row->setFontFamily('Calibri');
            //         $row->setFontSize(12);
            //         $row->setFontWeight('bold');
            //     });

            //     $asset = AssetHR::select('nama_barang', 'id_barang', 'description','code_name')
            //         ->where('tb_asset_hr.availability',1)
            //         ->get();
                

            //    // $sheet->appendRow(array_keys($datas[0]));
            //     $sheet->row($sheet->getHighestRow(), function ($row) {
            //         $row->setFontWeight('bold');
            //     });

            //     $datasheet = array();
            //     $datasheet[0]  =   array("No","Kode Asset","Nama Barang", "Quantity", "Deskripsi", "Status");
            //      $i=1;

            //     foreach ($asset as $data) {
            //         if ($data->qty == 0) {
            //           $datasheet[$i] = array(
            //             $i,
            //             $data['code_name'],
            //             $data['nama_barang'],
            //             $data['description'],
            //             'UnAvailable'
                        
            //             );
                      
            //           $i++;
            //         }else{
            //         $datasheet[$i] = array(
            //             $i,
            //             $data['code_name'],
            //             $data['nama_barang'],
            //             $data['description'],
            //             'Available'
                        
            //         );
                  
            //         $i++;
            //         }
                    
            //     }

            //     $sheet->fromArray($datasheet);
            // });

            $excel->sheet('List  Asset', function ($sheet) use ($request) {
            
                $sheet->mergeCells('A1:H1');

               // $sheet->setAllBorders('thin');
                $sheet->row(1, function ($row) {
                    $row->setFontFamily('Calibri');
                    $row->setFontSize(12);
                    $row->setAlignment('center');
                    $row->setFontWeight('bold');
                });

                $sheet->row(1, array('LIST ASSET'));

                $sheet->row(2, function ($row) {
                    $row->setFontFamily('Calibri');
                    $row->setFontSize(12);
                    $row->setFontWeight('bold');
                });

                // $datas    = DetailAssetHR::join('users','users.nik','=','tb_asset_hr_transaction.nik_peminjam')
                //             ->where('tb_asset_hr.availability',1)
                //             ->join('tb_asset_hr','tb_asset_hr.id_barang','=','tb_asset_hr_transaction.id_barang', 'left')
                //             ->select('tb_asset_hr_transaction.id_transaction','tb_asset_hr_transaction.id_barang','tb_asset_hr.nama_barang','tb_asset_hr.description','users.name','tb_asset_hr_transaction.nik_peminjam','tb_asset_hr.status', 'tb_asset_hr_transaction.keterangan', 'tgl_pengembalian', 'tgl_peminjaman', 'no_transac', 'code_name')
                //             ->get();

                $datas = AssetHR::Leftjoin(DB::raw("(
                        SELECT
                            `id_barang` AS `id_barang`, substring_index(group_concat(`name` ORDER BY `id_transaction` DESC SEPARATOR ','), ',', 1) AS `name`
                        FROM
                            `tb_asset_hr_transaction`
                        LEFT JOIN `users` ON `users`.`nik` = `tb_asset_hr_transaction`.`nik_peminjam`
                        GROUP BY
                            `id_barang`
                        ) as tb_asset_hr_transaction"),function($join){
                        $join->on("tb_asset_hr.id_barang","=","tb_asset_hr_transaction.id_barang");
                    })
                    ->select('nama_barang', 'tb_asset_hr.id_barang','status','description','code_name', 'serial_number','name','lokasi','note','lokasi')
                    ->where('availability',1)
                    ->get();
                    

               // $sheet->appendRow(array_keys($datas[0]));
                $sheet->row($sheet->getHighestRow(), function ($row) {
                    $row->setFontWeight('bold');
                });

                $datasheet = array();
                $datasheet[0]  =   array("No","Kode Asset", "Nama Barang", "Deskripsi", "Latest Peminjam", "Note" , "Status","Lokasi");
                 $i=1;

                foreach ($datas as $data) {
                    $datasheet[$i] = array($i,
                                $data['code_name'],
                                $data['nama_barang'],
                                $data['description'],
                                $data['name'],
                                $data['note'],
                                $data['status'],
                                $data['lokasi']
                            );
                    $i++;
                    
                }

                $sheet->fromArray($datasheet);
            });

        })->export('xls');
    }
}
