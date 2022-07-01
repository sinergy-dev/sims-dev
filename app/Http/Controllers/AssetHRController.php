<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use DB;
use App\AssetHR;
use App\AssetHrRequest;
use App\DetailAssetHR;
use App\User;
use App\Mail\RequestAssetHr;
use Mail;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Carbon\Carbon;

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

        $notifClaim = '';

        $user_pinjam = '';

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

        $listAsset = DB::table('tb_asset_hr')
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
                ->select('nama_barang', 'tb_asset_hr.id_barang','status','description','code_name', 'serial_number','name','lokasi','kategori')
                // ->where('availability',1)
                ->get();

        $asset = $listAsset->groupBy('kategori');

        // return $asset;

        $assetsd    = DB::table('tb_asset_hr_transaction')
                    ->join('users','users.nik','=','tb_asset_hr_transaction.nik_peminjam')
                    ->join('tb_asset_hr','tb_asset_hr.id_barang','=','tb_asset_hr_transaction.id_barang')
                    ->select('tb_asset_hr_transaction.id_transaction','tb_asset_hr_transaction.id_barang','tb_asset_hr.nama_barang','tb_asset_hr.description','users.name','tb_asset_hr_transaction.nik_peminjam','tb_asset_hr.status', 'tb_asset_hr_transaction.keterangan', 'tgl_pengembalian', 'tgl_peminjaman', 'no_transac')
                    // ->where('tb_asset_hr.availability',1)
                    ->get();

        $pinjaman = DB::table('tb_asset_hr_transaction')
                    ->join('users','users.nik','=','tb_asset_hr_transaction.nik_peminjam')
                    ->join('tb_asset_hr','tb_asset_hr.id_barang','=','tb_asset_hr_transaction.id_barang')
                    ->select('tb_asset_hr.description','tb_asset_hr_transaction.nik_peminjam','tb_asset_hr_transaction.id_transaction','tb_asset_hr_transaction.id_barang','users.name','tb_asset_hr_transaction.created_at','tb_asset_hr_transaction.updated_at','tb_asset_hr.nama_barang','tb_asset_hr.status', 'no_transac')
                    ->where('tb_asset_hr_transaction.nik_peminjam',Auth::User()->nik)
                    // ->where('tb_asset_hr.availability',1)
                    ->get();

        $kategori_asset = DB::table('tb_kategori_asset_hr')
        				->Leftjoin(DB::raw("(
                        SELECT
                            COUNT(`kategori`) AS `count_kategori`, `kategori`
                        FROM
                            `tb_asset_hr`
                        WHERE 'availability' = 1
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

        $pinjam_request = DB::table('tb_asset_hr_transaction')
                        ->select('keterangan','note','no_transac','id_transaction')
                        ->where('nik_peminjam',Auth::User()->nik)
                        ->where('tb_asset_hr_transaction.status','PENDING')
                        ->where('tgl_pengembalian',NULL)
                        ->orderBy('tb_asset_hr_transaction.created_at','asc')
                        ->get();

        if (Auth::User()->id_division == 'HR' || Auth::User()->id_division == 'WAREHOUSE' && Auth::User()->id_position == 'WAREHOUSE' && Auth::User()->id_territory == 'OPERATION') {
            $current_request = DB::table('tb_asset_hr_request')
                           ->join('users','users.nik','=','tb_asset_hr_request.nik')
                           ->join('tb_kategori_asset_hr','tb_kategori_asset_hr.id','=','tb_asset_hr_request.kategori_request') 
                           ->select('nama','tb_kategori_asset_hr.kategori','tb_kategori_asset_hr.code_kat','merk','link','id_request','users.name','tb_asset_hr_request.nik','tb_asset_hr_request.status','tb_asset_hr_request.created_at')
                           ->where('tb_asset_hr_request.status','<>','ACCEPT')
                           ->where('tb_asset_hr_request.status','<>','REJECT')
                           ->where('tb_asset_hr_request.status','<>','CANCEL')
                           ->get();

            $current_borrowed = DB::table('tb_asset_hr_transaction')
                        ->join('tb_asset_hr','tb_asset_hr.id_barang','=','tb_asset_hr_transaction.id_barang')
                        ->select('tb_asset_hr.description','serial_number','tgl_peminjaman','tgl_pengembalian','tb_asset_hr.note','no_transac','nama_barang','code_name','id_transaction')
                        ->where('tb_asset_hr_transaction.status','ACCEPT')
                        ->where('tgl_pengembalian',NULL)
                        ->get();

            $historyCancel  = DB::table('tb_asset_hr_request')
               ->join('tb_kategori_asset_hr','tb_kategori_asset_hr.id','=','tb_asset_hr_request.kategori_request') 
               ->select('nama','tb_kategori_asset_hr.kategori','tb_kategori_asset_hr.code_kat','merk','link','id_request','tb_asset_hr_request.status')
               ->where('tb_asset_hr_request.status','=','CANCEL')
               ->orwhere('tb_asset_hr_request.status','=','REJECT')
               ->get();
        }else{
            $current_request = DB::table('tb_asset_hr_request')
                           ->join('users','users.nik','=','tb_asset_hr_request.nik')
                           ->join('tb_kategori_asset_hr','tb_kategori_asset_hr.id','=','tb_asset_hr_request.kategori_request') 
                           ->select('nama','tb_kategori_asset_hr.kategori','tb_kategori_asset_hr.code_kat','merk','link','id_request','tb_asset_hr_request.status','users.name', 'tb_asset_hr_request.nik','tb_asset_hr_request.created_at')
                           ->where('tb_asset_hr_request.nik',Auth::User()->nik)
                           ->where('tb_asset_hr_request.status','<>','ACCEPT')
                           ->where('tb_asset_hr_request.status','<>','REJECT')
                           ->where('tb_asset_hr_request.status','<>','CANCEL')
                           ->get();

            $historyCancel  = DB::table('tb_asset_hr_request')
                           ->join('tb_kategori_asset_hr','tb_kategori_asset_hr.id','=','tb_asset_hr_request.kategori_request') 
                           ->select('nama','tb_kategori_asset_hr.kategori','tb_kategori_asset_hr.code_kat','merk','link','id_request','tb_asset_hr_request.status')
                           ->where('nik',Auth::User()->nik)
                           ->where('tb_asset_hr_request.status','<>','REQUEST')
                           ->where('tb_asset_hr_request.status','<>','PENDING')
                           ->get();
        }

        
        

        // $request_asset = DB::table('tb_asset_hr_transaction')
        //                 ->join('tb_asset_hr','tb_asset_hr.id_barang','=','tb_asset_hr_transaction.id_barang')
        //                 ->join('users','users.nik','=','tb_asset_hr_transaction.nik_peminjam')
        //                 ->select('name','nama_barang','description','no_transac','tb_asset_hr.note','nik_peminjam','tb_asset_hr_transaction.id_barang','id_transaction')
        //                 ->where('tb_asset_hr_transaction.status','PENDING')
        //                 ->orderBy('tb_asset_hr_transaction.created_at','asc')
        //                 ->get();

        $request_asset = DB::table('tb_asset_hr_transaction')
                        ->join('users','users.nik','=','tb_asset_hr_transaction.nik_peminjam')
                        ->select('tb_asset_hr_transaction.keterangan','tb_asset_hr_transaction.nik_peminjam','tb_asset_hr_transaction.id_transaction','users.name','tb_asset_hr_transaction.created_at','tb_asset_hr_transaction.updated_at','no_transac','note','tgl_peminjaman')
                        ->where('tb_asset_hr_transaction.status','PENDING')
                        ->orderBy('tb_asset_hr_transaction.created_at','asc')
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

        $sidebar_collapse = true;

    	return view('HR/asset_hr',compact('notif', 'notifc', 'notifsd', 'notiftp', 'notifOpen', 'notifClaim', 'asset', 'assetsd', 'pinjaman','users','nomor','user_pinjam','kategori_asset','current_borrowed','request_asset','current_request','pinjam_request','historyCancel','sidebar_collapse'))->with(['initView'=> $this->initMenuBase(),'feature_item'=>$this->RoleDynamic('asset_hr')]);
    }

    public function getRequestAssetBy(Request $request){
    	if ($request->status == 'pinjam') {
    		return $current_request = DB::table('tb_asset_hr_transaction')
                           ->select('note','keterangan','id_transaction','tgl_peminjaman')
                           ->where('id_transaction',$request->id)
                           ->get();
    	}else{
    		return $current_request = DB::table('tb_asset_hr_request')
                           ->join('users','users.nik','=','tb_asset_hr_request.nik')
                           ->join('tb_kategori_asset_hr','tb_kategori_asset_hr.id','=','tb_asset_hr_request.kategori_request') 
                           ->select('nama','tb_kategori_asset_hr.kategori','tb_kategori_asset_hr.code_kat','tb_kategori_asset_hr.id','merk','link','id_request','users.name','tb_asset_hr_request.nik','tb_asset_hr_request.status','users.id_company','tb_asset_hr_request.qty','tb_asset_hr_request.status','tb_asset_hr_request.created_at')
                           ->where('id_request',$request->id)
                           ->get();
    	}
       
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

    public function getListAsset(Request $request){
    	$category = DB::table('tb_kategori_asset_hr')->where('kategori',$request->category)->first()->id;

        return array("results" => DB::table('tb_asset_hr')->select(
            DB::raw("CASE WHEN (serial_number is null) THEN CONCAT(`nama_barang`,' - ',`merk`) ELSE CONCAT(`nama_barang`,' - ',`merk`,'(',`serial_number`,')') END AS `text`"),
            DB::raw("`id_barang` AS `id`"))
            ->where('status','AVAILABLE')
            ->where('kategori',$category)->get()); 
    }

    public function getCategoryPinjam(Request $request){
        return array("results" => DB::table('tb_kategori_asset_hr')->Leftjoin(DB::raw("(
            SELECT
                `kategori`,`status`
            FROM
                `tb_asset_hr`
            WHERE
                `tb_asset_hr`.`status` = 'AVAILABLE'
            GROUP BY
                `kategori`
            ) as tb_asset_hr"),function($join){
                $join->on("tb_kategori_asset_hr.id","=","tb_asset_hr.kategori");
            })
        ->select(DB::raw("`tb_kategori_asset_hr`.`id` AS `no`,`tb_kategori_asset_hr`.`code_kat` AS `id`,`tb_kategori_asset_hr`.`kategori` AS `text`"))
        ->where('tb_asset_hr.status','AVAILABLE')
        ->get());
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
        if ($request['purchase_price'] == NULL) {
        	$harga_beli	= $request['purchase_price'];
        }else{
        	$harga_beli = str_replace(',', '', $request['purchase_price']);
        }
        $tambah->harga_beli     = $harga_beli;
        $tambah->type     		= $request['type_asset'];        
        $tambah->status         = "AVAILABLE";
        $edate          = strtotime($_POST['asset_date']); 
        $edate          = date("Y-m-d",$edate);
        $tambah->tgl_tambah     = $edate;
        $tambah->serial_number  = $request['asset_sn'];
        $tambah->description    = $request['keterangan'];
        $tambah->note    		= $request['note'];
        $tambah->lokasi         = $request['lokasi'];
        $tambah->merk           = $request['merk_barang'];
        $tambah->save();

        return redirect()->back()->with('success', 'Create New Asset Successfully!');
    }

    public function storeRequestAsset(Request $request){
        $count_kategori = count($request['cat_req_id']);   

        $text = $request['link_barang_request'];
        $link_barang = $text;

        $inc = DB::table('tb_asset_hr_request')->get();
        $increment = count($inc);
        $no_req = date('ymd');

        for ($i=0; $i < $count_kategori ; $i++) { 
            if ($request->merk_barang_request[$i] == '') {
                $merk_barang = ' - ';
            }else{
                $merk_barang = $request['merk_barang_request'][$i];
            }  

            $nomor = $increment+$i;
            if($nomor < 10){
                $nomor = '00' . $nomor;
            }
            $data = array(
                'id_request'        => $no_req . $nomor,
                'nik'               => Auth::User()->nik,
                'kategori_request'  => $request['cat_req_id'][$i],
                'nama'              => $request['nama_barang_request'][$i],
                'qty'               => $request['qty_barang_request'][$i],
                'status'            => "REQUEST",
                'link'              => $link_barang[$i],
                'merk'              => $merk_barang,
                'created_at'        => date("Y-m-d h:i:s"),
                'updated_at'        => date("Y-m-d h:i:s"),
            );
            $insertData[] = $data;
        }
        AssetHrRequest::insert($insertData);  

        $req_asset = collect(['insertdata'=>$insertData,'nama_peminjam'=>Auth::User()->name,'request_date'=>date("Y-m-d h:i:s"),'status'=>'REQUEST']);

        $to = User::select('email')
                ->join('role_user','role_user.user_id','=','users.nik')
                ->where('id_division','HR')
                ->where('id_position','HR MANAGER')
                ->where('role_id',11)->get();

        $users = User::select('name')
                ->join('role_user','role_user.user_id','=','users.nik')
                ->where('id_division','HR')
                ->where('id_position','HR MANAGER')
                ->where('role_id',11)->first();

        Mail::to($to)->send(new RequestAssetHr('new',$users,$req_asset,'[SIMS-APP] Request New Asset'));     

        return redirect()->back()->with('success', 'Create New Request Asset Successfully!')->withInput(['tab'=>'request_list']);
    }

    public function batalkanReq(Request $request){
    	if ($request->status == 'pinjam') {
    		$update = DetailAssetHR::where('id_transaction',$request->id_request)->first();
	        $update->status = 'CANCEL';
	        $update->update();

	        $req_asset = DetailAssetHR::join('users','users.nik','=','tb_asset_hr_transaction.nik_peminjam')
	                    ->select('users.name','tgl_peminjaman','status','note','keterangan')
	                    ->where('id_transaction',$request->id_request)
	                    ->first();

	        $sendFor = 'cancelPinjam';
    	}else{
    		$update = AssetHrRequest::where('id_request',$request->id_request)->first();
	        $update->status = 'CANCEL';
	        $update->update();

	        $req_asset = AssetHrRequest::join('users','users.nik','=','tb_asset_hr_request.nik')
	                    ->select('nama','qty','link','merk','users.name','tb_asset_hr_request.updated_at','tb_asset_hr_request.status')
	                    ->where('id_request',$request->id_request)
	                    ->first();

	        $sendFor = 'batalkan';
    	}
        


        $to = User::select('email')
                ->join('role_user','role_user.user_id','=','users.nik')
                ->where('id_division','HR')
                ->where('id_position','HR MANAGER')
                ->where('role_id',11)->get();

        $users = User::select('name')
                ->join('role_user','role_user.user_id','=','users.nik')
                ->where('id_division','HR')
                ->where('id_position','HR MANAGER')
                ->where('role_id',11)->first();

        Mail::to($to)->send(new RequestAssetHr($sendFor,$users,$req_asset,'[SIMS-APP] Request Asset dibatalkan'));  
    }

    public function AddNoteReq(Request $request){
    	if ($request->status == 'pinjam') {
    		$asset = DetailAssetHR::join('users','users.nik','=','tb_asset_hr_transaction.nik_peminjam')
	                    ->select('users.name','tgl_peminjaman','status','note','keterangan')
	                    ->where('id_transaction',$request->id_request)
	                    ->first();

	        $sendFor = 'addNotePinjam';
    	}else{
    		$asset = AssetHrRequest::join('users','users.nik','=','tb_asset_hr_request.nik')
                    ->select('nama','qty','link','merk','users.name','tb_asset_hr_request.updated_at','tb_asset_hr_request.status')
                    ->where('id_request',$request->id_request)
                    ->first();
            $sendFor = 'addNote';
    	}
        

        $req_asset = collect(['asset'=>$asset,'notes'=>$request->notes]);

        $to = User::select('email')
                ->join('role_user','role_user.user_id','=','users.nik')
                ->where('id_division','HR')
                ->where('id_position','HR MANAGER')
                ->where('role_id',11)->get();

        $users = User::select('name')
                ->join('role_user','role_user.user_id','=','users.nik')
                ->where('id_division','HR')
                ->where('id_position','HR MANAGER')
                ->where('role_id',11)->first();

        Mail::to($to)->send(new RequestAssetHr($sendFor,$users,$req_asset,'[SIMS-APP] Request New Asset (Update)'));  
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
                ->orderBy('tb_asset_hr_transaction.updated_at','desc')
                ->get();

        $detailAsset = DB::table('tb_asset_hr')
                ->select('nama_barang', 'tb_asset_hr.id_barang', 'description','code_name','status','serial_number',DB::raw('DATEDIFF(tgl_tambah,NOW()) AS umur_asset'),'lokasi','tgl_tambah','merk','description','note','harga_beli')
                ->where('tb_asset_hr.id_barang',$id_barang)                
                ->first();

        $total_pinjam = DB::table('tb_asset_hr_transaction')->groupBy('id_barang')->where('id_barang',$id_barang)->count();

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

        $sidebar_collapse = true;

        return view('HR.detail_asset_peminjaman',compact('asset','detailAsset','total_pinjam','notif','notifOpen','notifsd','notiftp','notifc', 'notifClaim','sidebar_collapse'))->with(['initView'=>$this->initMenuBase()]);
    }

    public function peminjaman(Request $request)
    {
        $id_barang = $request['id_barang'];

        $update = AssetHR::where('id_barang',$id_barang)->first();
        if (Auth::User()->id_division == 'HR' || Auth::User()->id_position == 'WAREHOUSE' && Auth::User()->id_company == '1') {
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
        if (Auth::User()->id_division == 'HR' || Auth::User()->id_position == 'WAREHOUSE' && Auth::User()->id_company == '1') {
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

        if (Auth::User()->id_division == 'HR' || Auth::User()->id_position == 'WAREHOUSE' && Auth::User()->id_company == '1') {
            $asset = AssetHR::join('tb_asset_hr_transaction','tb_asset_hr_transaction.id_barang','=','tb_asset_hr.id_barang')
                    ->join('users','users.nik','=','tb_asset_hr_transaction.nik_peminjam')
                    ->select('nama_barang','description','name','tb_asset_hr_transaction.created_at','keterangan','tb_asset_hr_transaction.status')
                    ->where('tb_asset_hr_transaction.id_transaction',$store->id_transaction)
                    ->first();

            $req_asset = collect(['asset'=>$asset]);

            // return $req_asset;

            $to = User::select('email')->where('nik',$store->nik_peminjam)->get();

            $users = User::select('name')->where('nik',$store->nik_peminjam)->first();

            Mail::to($to)->send(new RequestAssetHr('peminjaman',$users,$req_asset,'[SIMS-APP] Accepting Peminjaman Asset'));
        }else{
            $req_asset = AssetHR::join('tb_asset_hr_transaction','tb_asset_hr_transaction.id_barang','=','tb_asset_hr.id_barang')
                    ->join('users','users.nik','=','tb_asset_hr_transaction.nik_peminjam')
                    ->select('nama_barang','description','name','tb_asset_hr_transaction.created_at','keterangan','tb_asset_hr_transaction.status')
                    ->where('tb_asset_hr_transaction.id_transaction',$store->id_transaction)
                    ->first();

            $to = User::select('email')->where('id_division','WAREHOUSE')->where('id_position','WAREHOUSE')->get();

            $users = User::select('name')->where('id_division','WAREHOUSE')->where('id_position','WAREHOUSE')->first();

            Mail::to($to)->send(new RequestAssetHr('reqPinjam',$users,$req_asset,'[SIMS-APP] Permohonan untuk Peminjaman Asset'));
        }      

        return redirect()->back()->with('alert', $line)->withInput(['tab'=>'request_list']);
    }

    public function requestPeminjaman(Request $request)
    {
        $inc = DB::table('tb_asset_hr_transaction')->select('id_transaction')->get();
        $increment = count($inc);
        $nomor = $increment+1;
        if($nomor < 10){
            $nomor = '00' . $nomor;
        }

        $no_peminjaman = date('ymd').$nomor;

        $store                  = new DetailAssetHR();
        $store->nik_peminjam    = Auth::User()->nik;
        $store->status          = 'PENDING';

        $line = 'Peminjaman Barang Akan diproses!';

        $store->keterangan      = 'Specification - '.$request['description'].'<br>'.'Using for - '.$request['keperluan'];
        $store->tgl_peminjaman  = date('Y-m-d');
        $store->no_transac      = $no_peminjaman;   
        $store->note            = $request['cat_pinjam_id'];  
        $store->save();    

        $req_asset = DetailAssetHR::join('users','users.nik','=','tb_asset_hr_transaction.nik_peminjam')
                ->select('name','tb_asset_hr_transaction.created_at','note','tb_asset_hr_transaction.status','keterangan')
                ->where('tb_asset_hr_transaction.id_transaction',$store->id_transaction)
                ->first();

        $to = User::select('email')->where('id_division','WAREHOUSE')->where('id_position','WAREHOUSE')->get();

        $users = User::select('name')->where('id_division','WAREHOUSE')->where('id_position','WAREHOUSE')->first();

        Mail::to($to)->send(new RequestAssetHr('reqPinjam',$users,$req_asset,'[SIMS-APP] Permohonan untuk Peminjaman Asset'));      
        

        return redirect()->back()->with(['alert'=> $line,'id'=>$req_asset->id_transaction]);
    }

    public function acceptPeminjaman(Request $request){   
        $update_accept =  DetailAssetHR::where('id_transaction',$request->id_transaction)
                ->first();        
        $update = AssetHR::where('id_barang',$request->id_barang)->first();
        if ($request->status == 'ACCEPT') {
            $update->status = 'UNAVAILABLE';
            $update_accept->id_barang = $request->id_barang; 
            $update->update(); 
        }

        if ($request->status == 'ACCEPT') {
            $update_accept->status = "ACCEPT";
            $emailSubject = '[SIMS-APP] Accepting Peminjaman Asset';

            $asset = AssetHR::join('tb_asset_hr_transaction','tb_asset_hr_transaction.id_barang','=','tb_asset_hr.id_barang')
                    ->join('users','users.nik','=','tb_asset_hr_transaction.nik_peminjam')
                    ->select('nama_barang','description','name','tb_asset_hr_transaction.created_at','keterangan','tb_asset_hr_transaction.status')
                    ->where('tb_asset_hr_transaction.id_transaction',$request->id_transaction)
                    ->first();
            $update_accept->save();

            $req_asset = collect(['asset'=>$asset,'reason'=>$request->reason]);

            $to = User::select('email')->where('nik',$update_accept->nik_peminjam)->get();

            $users = User::select('name')->where('nik',$update_accept->nik_peminjam)->first();

            Mail::to($to)->send(new RequestAssetHr('peminjaman',$users,$req_asset,$emailSubject));
        }else{
            $update_accept->status = "REJECT";
            $emailSubject = '[SIMS-APP] Rejecting Peminjaman Asset';

            $asset = DetailAssetHR::join('users','users.nik','=','tb_asset_hr_transaction.nik_peminjam')
	                ->select('name','tb_asset_hr_transaction.created_at','note','tb_asset_hr_transaction.status','keterangan')
	                ->where('tb_asset_hr_transaction.id_transaction',$request->id_transaction)
	                ->first();
            $update_accept->save();

            $req_asset = collect(['asset'=>$asset,'reason'=>$request->reason]);

            $to = User::select('email')->where('nik',$update_accept->nik_peminjam)->get();

            $users = User::select('name')->where('nik',$update_accept->nik_peminjam)->first();

            Mail::to($to)->send(new RequestAssetHr('peminjaman',$users,$req_asset,$emailSubject));
        }


        // $update_rejects = DetailAssetHR::where('id_barang',$request->id_barang)
        //     ->where('nik_peminjam','<>',$request->nik_peminjam)
        //     ->where('status','PENDING')
        //     ->get();

        // foreach ($update_rejects as $update_reject) {
        //     $update_reject->status = "REJECT";
        //     $update_reject->save();
        // }

        // $asset = AssetHR::join('tb_asset_hr_transaction','tb_asset_hr_transaction.id_barang','=','tb_asset_hr.id_barang')
        //             ->join('users','users.nik','=','tb_asset_hr_transaction.nik_peminjam')
        //             ->select('nama_barang','description','name','tb_asset_hr_transaction.created_at','keterangan','tb_asset_hr_transaction.status')
        //             ->where('tb_asset_hr_transaction.id_transaction',$request->id_transaction)
        //             ->first();

        // $asset = DetailAssetHR::join('users','users.nik','=','tb_asset_hr_transaction.nik_peminjam')
        // 		->join('tb_asset_hr','tb_asset_hr.id_barang','=','tb_asset_hr_transaction.id_barang')
        //         ->select('name','tb_asset_hr_transaction.created_at','note','tb_asset_hr_transaction.status','keterangan','nama_barang')
        //         ->where('tb_asset_hr_transaction.id_transaction',$request->id_transaction)
        //         ->first();


        return redirect()->back()->with('alert','Asset Request Accepted!');
        
    }

    public function acceptNewAsset(Request $request){

        $update = AssetHrRequest::where('id_request',$request->id_request)->first();
        if ($request->status == 'ACCEPT') {
            $update->status     = 'PENDING';
            $update->updated_at = date('Y-m-d h:i:s');
            $alerts = 'New Asset Request Accepted!';

            $emailSubject = '[SIMS-APP] Request Asset ( ' . $update->nama . ' ) sedang diproses';
        }else{
            $update->status = 'REJECT';
            $update->updated_at = date('Y-m-d h:i:s');
            $alerts = 'New Asset Request Rejected!';

            $emailSubject = '[SIMS-APP] Rejecting Request New Asset';
        }
        $update->update();  

        $asset = AssetHrRequest::join('users','users.nik','=','tb_asset_hr_request.nik')
                ->select('nama','qty','link','merk','users.name','tb_asset_hr_request.updated_at','tb_asset_hr_request.status')
                ->where('id_request',$request->id_request)
                ->first(); 

        $req_asset = collect(['asset'=>$asset,'reason'=>$request->reason]);

        $to = User::select('email')->where('nik',$update->nik)->get();

        $users = User::select('name')->where('nik',$update->nik)->first();

        Mail::to($to)->send(new RequestAssetHr('proses',$users,$req_asset,$emailSubject));  

        // $update_rejects = DetailAssetHR::where('id_barang',$request->id_barang)
        //     ->where('nik_peminjam','<>',$request->nik_peminjam)
        //     ->where('status','PENDING')
        //     ->get();

        // foreach ($update_rejects as $update_reject) {
        //     $update_reject->status = "REJECT";
        //     $update_reject->save();
        // }

        return redirect()->back()->with('alert',$alerts);
    }

    public function createNewAsset(Request $request){
        $tambah                 = new AssetHR();
        $tambah->nik            = Auth::User()->nik;
        $tambah->code_name      = $request['asset_code'];
        if ($request->category_id == '') {
            $tambah->kategori       = $request['category_id_req'];
        }else{
            $tambah->kategori       = $request['category_id'];
        }        
        $tambah->nama_barang    = $request['nama_barang'];
        $tambah->status         = "UNAVAILABLE";
        $edate          = strtotime($_POST['asset_date']); 
        $edate          = date("Y-m-d",$edate);
        $tambah->tgl_tambah     = $edate;
        $tambah->serial_number  = $request['asset_sn'];
        $tambah->description    = $request['keterangan'];
        $tambah->note           = $request['note'];
        $tambah->lokasi         = $request['lokasi'];
        $tambah->save();

        $inc = DB::table('tb_asset_hr_transaction')->select('id_transaction')->get();
        $increment = count($inc);
        $nomor = $increment+1;
        if($nomor < 10){
            $nomor = '00' . $nomor;
        }

        $no_peminjaman = date('ymd').$nomor;

        $store                  = new DetailAssetHR();
        $store->id_barang       = $tambah->id_barang; 
        $store->nik_peminjam    = $request->requestNik;     
        $store->keterangan      = $request['keperluan'];
        $store->tgl_peminjaman  = date('Y-m-d');
        $store->status          = 'ACCEPT';
        $store->no_transac      = $no_peminjaman;        
        $store->save();

        $update             = AssetHrRequest::where('id_request',$request->id_requestNewAsset)->first();
        $update->status     = 'ACCEPT';
        $update->updated_at = date('Y-m-d h:i:s');
        $update->update(); 

        $asset = AssetHrRequest::join('users','users.nik','=','tb_asset_hr_request.nik')
                    ->select('nama','qty','link','merk','users.name','tb_asset_hr_request.updated_at','tb_asset_hr_request.status')
                    ->where('id_request',$request->id_requestNewAsset)
                    ->first();

        $req_asset = collect(['asset'=>$asset]);

        $to = User::select('email')->where('nik',$update->nik)->get();

        $users = User::select('name')->where('nik',$update->nik)->first();

        Mail::to($to)->send(new RequestAssetHr('proses',$users,$req_asset,'[SIMS-APP] Request Asset ( ' . $request['nama_barang'] . ' ) Sudah Datang'));  

        return redirect()->back()->with('alert', 'Create New Asset Successfully!');

    }

    public function penghapusan(Request $request)
    {
        $id_barang = $request['id_barang'];

        $hapus = AssetHR::where('id_barang',$id_barang)->delete();

        // $update = AssetHR::where('id_barang',$id_barang)->first();
        // $update->availability = 0;
        // $update->update();  

        return redirect()->back()->with('alert', 'Hapus Asset Berhasil!');
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

        $update_asset             = AssetHR::where('id_barang',$id_barang)->first();
        $update_asset->status     = 'AVAILABLE';
        $update_asset->lokasi     = $request->lokasi_kembali;
        $update_asset->update();

        $update                     = DetailAssetHR::where('id_transaction',$id_transaction)->first();
        $update->tgl_pengembalian   = $request['tanggal_kembali'];
        $update->update();

        // $asset = DB::table('tb_asset_hr')
        //         ->join('tb_asset_hr_transaction','tb_asset_hr_transaction.id_barang','=','tb_asset_hr.id_barang','inner')
        //         ->join('users', 'users.nik', '=', 'tb_asset_hr_transaction.nik_peminjam')
        //         ->select('nama_barang', 'tb_asset_hr.id_barang', 'description','code_name','tb_asset_hr_transaction.id_transaction','name','tb_asset_hr.status','lokasi')
        //         ->where('tb_asset_hr_transaction.id_barang',$request->id_barang)
        //         ->orderBy('tb_asset_hr_transaction.id_transaction','desc')
        //         ->first();

        $req_asset = AssetHR::join('tb_asset_hr_transaction','tb_asset_hr_transaction.id_barang','=','tb_asset_hr.id_barang')
                    ->join('users','users.nik','=','tb_asset_hr.nik')
                    ->select('nama_barang','description','name','tb_asset_hr_transaction.created_at','keterangan','tb_asset_hr.status','lokasi','tgl_peminjaman','tgl_pengembalian')
                    ->where('tb_asset_hr_transaction.id_transaction',$id_transaction)
                    ->first();

        $to = User::select('email')->where('nik',$update->nik_peminjam)->get();

        $users = User::select('name')->where('nik',$update->nik_peminjam)->first();

        Mail::to($to)->send(new RequestAssetHr('reqPinjam',$users,$req_asset,'[SIMS-APP] Pengembalian Asset'));

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
        $spreadsheet = new Spreadsheet();

        $prSheet = new Worksheet($spreadsheet,'LAPORAN PENGGUNAAN ASET GA');
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

        $dateReport = Carbon::parse($request->month . $request->year);
        $sheet->getStyle('A1:I1')->applyFromArray($titleStyle);
        $sheet->setCellValue('A1','LAPORAN PENGGUNAAN ASET KANTOR');
        $sheet->setCellValue('B2','Bulan ' . $dateReport->format("F"));
        $sheet->setCellValue('B3','Tahun ' . $request->year);
        $sheet->setCellValue('B4','Report Pada ' . date('Y-m-d'));

        $headerStyle = $normalStyle;
        $headerStyle['font']['bold'] = true;
        $headerStyle['alignment'] = ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER];
        $sheet->getStyle('A6:I7')->applyFromArray($headerStyle);

        $headerContent = ["No","Kode Asset", "Nama Barang", "Deskripsi", "Latest Peminjam", "Note" , "Status", "Serial Number","Lokasi"];
        $sheet->fromArray($headerContent,NULL,'A7');

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
        ->select('code_name','nama_barang','description', 'name', 'note','status', 'serial_number','lokasi','lokasi')
        ->where('availability',1)
        ->get();

        foreach ($datas as $key => $data) {
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


        $fileName = 'LAPORAN PENGGUNAAN ASET GA ' . date('Y-m-d') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        
        $writer = new Xlsx($spreadsheet);
        return $writer->save("php://output");
    }
}
