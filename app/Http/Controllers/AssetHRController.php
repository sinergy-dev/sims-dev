<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Auth;
use DB;
use App\AssetHR;
use App\AssetHrRequest;
use App\DetailAssetHR;
use App\AssetNotesTransaction;
use App\User;
use App\RoleUser;
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
use PDF;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Google_Client;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;

use Maatwebsite\Excel\Facades\Excel;

use setasign\Fpdf\Fpdf;
use setasign\Fpdi\Fpdi;
use setasign\FpdiProtection\FpdiProtection;
use setasign\Fpdi\PdfParser\StreamReader;

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

        $user_pinjam = '';

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
                ->leftJoin('users', 'users.name', '=', 'tb_asset_hr_transaction.name')
                ->select('nama_barang', 'tb_asset_hr.id_barang','status','description','code_name', 'serial_number','users.name','lokasi','kategori', DB::raw('DATEDIFF(NOW(),date_of_entry) AS date_of_entrys'), 'users.status_kerja')
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
                        ->select('tb_asset_hr.description','serial_number','tgl_peminjaman','tgl_pengembalian','tb_asset_hr.note','no_transac','nama_barang','code_name','id_transaction', 'keterangan')
                        ->where('nik_peminjam',Auth::User()->nik)
                        ->where('tb_asset_hr_transaction.status','ACCEPT')
                        ->where('tgl_pengembalian',NULL)
                        ->get();

        $pinjam_request = DB::table('tb_asset_hr_transaction')
                        ->join('tb_asset_hr','tb_asset_hr.id_barang','tb_asset_hr_transaction.id_transaction')
                        ->select('keterangan','tb_asset_hr_transaction.note','no_transac','id_transaction','serial_number')
                        ->where('nik_peminjam',Auth::User()->nik)
                        ->where('tb_asset_hr_transaction.status','PENDING')
                        ->where('tgl_pengembalian',NULL)
                        ->orderBy('tb_asset_hr_transaction.created_at','asc')
                        ->get();

        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')
                    ->select('name', 'roles.group','roles.mini_group')
                    ->where('user_id', Auth::User()->nik)
                    ->first(); 

        if ($cek_role->mini_group == "Supply Chain & IT Support Manager" || $cek_role->name == 'Internal Operation Support Manager' || $cek_role->name == 'VP Internal Chain Management') {
            $current_request = DB::table('tb_asset_hr_request')
                           ->join('users','users.nik','=','tb_asset_hr_request.nik')
                           ->join('role_user','role_user.user_id','=','tb_asset_hr_request.accept_by')
                           ->join('roles','roles.id','=','role_user.role_id')
                           ->leftjoin('tb_kategori_asset_hr','tb_kategori_asset_hr.id','=','tb_asset_hr_request.kategori_request') 
                           ->select('nama',
                            'tb_kategori_asset_hr.kategori',
                            'tb_kategori_asset_hr.code_kat',
                            'merk',
                            'link',
                            'id_request',
                            'users.name as name_requestor',
                            'roles.name as roles_name',
                            'tb_asset_hr_request.nik',
                            'tb_asset_hr_request.status',
                            'tb_asset_hr_request.created_at',
                            'qty',
                            'keperluan as used_for',
                            'link_drive',
                            DB::raw('CASE WHEN (duration) = "Lifetime" THEN duration ELSE CONCAT(DATE_FORMAT(duration_start, "%d/%m/%Y")," - ",DATE_FORMAT(duration_end, "%d/%m/%Y")) END as duration'),
                            'reason',
                            'link_drive'
                            )
                           ->where('tb_asset_hr_request.status','<>','ACCEPT')
                           ->where('tb_asset_hr_request.status','<>','REJECT')
                           ->where('tb_asset_hr_request.status','<>','CANCEL')
                           ->get();

            $current_borrowed = DB::table('tb_asset_hr_transaction')
                        ->join('tb_asset_hr','tb_asset_hr.id_barang','=','tb_asset_hr_transaction.id_barang')
                        ->select('tb_asset_hr.description','serial_number','tgl_peminjaman','tgl_pengembalian','tb_asset_hr.note','no_transac','nama_barang','code_name','id_transaction', 'keterangan','serial_number')
                        ->where('tb_asset_hr_transaction.status','ACCEPT')
                        ->where('tgl_pengembalian',NULL)
                        ->get();

            $history_request = DB::table('tb_asset_hr_request')
                           ->join('users','users.nik','=','tb_asset_hr_request.nik')
                           ->leftjoin('tb_kategori_asset_hr','tb_kategori_asset_hr.id','=','tb_asset_hr_request.kategori_request') 
                           ->select('nama',
                                'tb_kategori_asset_hr.kategori',
                                'tb_kategori_asset_hr.code_kat',
                                'merk',
                                'link',
                                'id_request',
                                'tb_asset_hr_request.status',
                                'users.name as name_requestor', 
                                'tb_asset_hr_request.nik',
                                'tb_asset_hr_request.created_at',
                                'tb_asset_hr_request.updated_at',
                                'qty',
                                'keperluan as used_for',
                                'link_drive',
                                DB::raw('CASE WHEN (duration) = "Lifetime" THEN duration ELSE CONCAT(DATE_FORMAT(duration_start, "%d/%m/%Y")," - ",DATE_FORMAT(duration_end, "%d/%m/%Y")) END as duration'),
                                'reason',
                                'link_drive'
                            )
                           ->where('tb_asset_hr_request.status','<>','REQUEST')
                           ->where('tb_asset_hr_request.status','<>','ON PROGRESS')
                           ->get();

            $historyCancel  = DB::table('tb_asset_hr_request')
               ->join('tb_kategori_asset_hr','tb_kategori_asset_hr.id','=','tb_asset_hr_request.kategori_request') 
               ->select('nama','tb_kategori_asset_hr.kategori','tb_kategori_asset_hr.code_kat','merk','link','id_request','tb_asset_hr_request.status')
               ->where('tb_asset_hr_request.status','=','CANCEL')
               ->orwhere('tb_asset_hr_request.status','=','REJECT')
               ->get();
        }else{
            $current_request = DB::table('tb_asset_hr_request')
                           ->select('nama',
                                'tb_kategori_asset_hr.kategori',
                                'tb_kategori_asset_hr.code_kat',
                                'merk',
                                'link',
                                'id_request',
                                'tb_asset_hr_request.status',
                                'users.name as name_requestor', 
                                'tb_asset_hr_request.nik',
                                'tb_asset_hr_request.created_at',
                                'qty',
                                'keperluan as used_for',
                                'link_drive',
                                DB::raw('CASE WHEN (duration) = "Lifetime" THEN duration ELSE CONCAT(DATE_FORMAT(duration_start, "%d/%m/%Y")," - ",DATE_FORMAT(duration_end, "%d/%m/%Y")) END as duration'),
                                'reason',
                                'link_drive'
                            )->join('users','users.nik','=','tb_asset_hr_request.nik')
                           ->leftjoin('tb_kategori_asset_hr','tb_kategori_asset_hr.id','=','tb_asset_hr_request.kategori_request');

            $history_request = DB::table('tb_asset_hr_request')
                           ->select('nama',
                                'tb_kategori_asset_hr.kategori',
                                'tb_kategori_asset_hr.code_kat',
                                'merk',
                                'link',
                                'id_request',
                                'tb_asset_hr_request.status',
                                'users.name as name_requestor', 
                                'tb_asset_hr_request.nik',
                                'tb_asset_hr_request.created_at',
                                'tb_asset_hr_request.updated_at',
                                'qty',
                                'keperluan as used_for',
                                'link_drive',
                                DB::raw('CASE WHEN (duration) = "Lifetime" THEN duration ELSE CONCAT(DATE_FORMAT(duration_start, "%d/%m/%Y")," - ",DATE_FORMAT(duration_end, "%d/%m/%Y")) END as duration'),
                                'reason',
                                'link_drive'
                            )->join('users','users.nik','=','tb_asset_hr_request.nik')
                           ->leftjoin('tb_kategori_asset_hr','tb_kategori_asset_hr.id','=','tb_asset_hr_request.kategori_request') ;

            $historyCancel  = DB::table('tb_asset_hr_request')
                           ->select('nama','tb_kategori_asset_hr.kategori','tb_kategori_asset_hr.code_kat','merk','link','id_request','tb_asset_hr_request.status')
                           ->join('tb_kategori_asset_hr','tb_kategori_asset_hr.id','=','tb_asset_hr_request.kategori_request') ;

            if (stripos($cek_role->name, 'Manager') !== false) {
                //for manager
                $current_request = $current_request
                            ->join('role_user','role_user.user_id','=','tb_asset_hr_request.nik')
                            ->join('roles','roles.id','=','role_user.role_id')
                           ->where('roles.mini_group',$cek_role->mini_group)
                           ->where('tb_asset_hr_request.status','<>','ACCEPT')
                           ->where('tb_asset_hr_request.status','<>','REJECT')
                           ->where('tb_asset_hr_request.status','<>','CANCEL')
                           ->get();
                           
                $historyCancel = $historyCancel->join('role_user','role_user.user_id','=','tb_asset_hr_request.nik')
                            ->join('roles','roles.id','=','role_user.role_id')
                           ->where('roles.mini_group',$cek_role->mini_group)
                           ->where('tb_asset_hr_request.status','<>','REQUEST')
                           ->where('tb_asset_hr_request.status','<>','PENDING')
                           ->where('tb_asset_hr_request.status','<>','ON PROGRESS')
                           ->get();

                $history_request = $history_request->join('role_user','role_user.user_id','=','tb_asset_hr_request.nik')
                            ->join('roles','roles.id','=','role_user.role_id')
                           ->where('roles.mini_group',$cek_role->mini_group)
                           ->where('tb_asset_hr_request.status','<>','REQUEST')
                           ->where('tb_asset_hr_request.status','<>','ON PROGRESS')
                           ->get();
                
            }else if(stripos($cek_role->name, 'VP') !== false){
                //for VP
                $current_request = $current_request
                            ->join('role_user','role_user.user_id','=','tb_asset_hr_request.nik')
                            ->join('roles','roles.id','=','role_user.role_id')
                           // ->where('roles.name','like','%Manager%')
                           ->where('roles.group',$cek_role->group)
                           ->where('tb_asset_hr_request.status','<>','ACCEPT')
                           ->where('tb_asset_hr_request.status','<>','REJECT')
                           ->where('tb_asset_hr_request.status','<>','CANCEL')
                           ->get();
                           
                $historyCancel = $historyCancel->join('role_user','role_user.user_id','=','tb_asset_hr_request.nik')
                            ->join('roles','roles.id','=','role_user.role_id')
                            // ->where('roles.name','like','%Manager%')
                            ->where('roles.group',$cek_role->group)
                            ->where('tb_asset_hr_request.status','<>','REQUEST')
                            ->where('tb_asset_hr_request.status','<>','PENDING')
                            ->where('tb_asset_hr_request.status','<>','ON PROGRESS')
                            ->get();

                $history_request = $history_request->join('role_user','role_user.user_id','=','tb_asset_hr_request.nik')
                            ->join('roles','roles.id','=','role_user.role_id')
                            ->where('roles.name','like','%Manager%')
                            ->where('roles.group',$cek_role->group)
                            ->where('tb_asset_hr_request.status','<>','REQUEST')
                            ->where('tb_asset_hr_request.status','<>','ON PROGRESS')
                            ->get();
            }else if($cek_role->name == "Chief Operating Officer" || $cek_role->name == "Chief Executive Officer" || $cek_role->name == "Financial Director") {
                //for ops & direktur
                $current_request = $current_request
                            ->join('role_user','role_user.user_id','=','tb_asset_hr_request.nik')
                            ->join('roles','roles.id','=','role_user.role_id')
                            ->where('tb_asset_hr_request.status','<>','ACCEPT')
                            ->where('tb_asset_hr_request.status','<>','REJECT')
                            ->where('tb_asset_hr_request.status','<>','CANCEL')
                            ->get();
                           
                $historyCancel = $historyCancel->join('role_user','role_user.user_id','=','tb_asset_hr_request.nik')
                            ->join('roles','roles.id','=','role_user.role_id')
                            ->where('tb_asset_hr_request.status','<>','REQUEST')
                            ->where('tb_asset_hr_request.status','<>','PENDING')
                            ->where('tb_asset_hr_request.status','<>','ON PROGRESS')
                            ->get();

                $history_request = $history_request->join('role_user','role_user.user_id','=','tb_asset_hr_request.nik')
                            ->join('roles','roles.id','=','role_user.role_id')
                            ->where('tb_asset_hr_request.status','<>','REQUEST')
                            ->where('tb_asset_hr_request.status','<>','ON PROGRESS')
                            ->get();
            }else{
                //for staff
                $current_request = $current_request
                           ->where('tb_asset_hr_request.nik',Auth::User()->nik)
                           ->where('tb_asset_hr_request.status','<>','ACCEPT')
                           ->where('tb_asset_hr_request.status','<>','REJECT')
                           ->where('tb_asset_hr_request.status','<>','CANCEL')
                           ->get();

                $historyCancel = $historyCancel->where('nik',Auth::User()->nik)
                           ->where('tb_asset_hr_request.status','<>','REQUEST')
                           ->where('tb_asset_hr_request.status','<>','PENDING')
                           ->where('tb_asset_hr_request.status','<>','ON PROGRESS')
                           ->get();

                $history_request = $history_request
                           ->where('tb_asset_hr_request.nik',Auth::User()->nik)
                           ->where('tb_asset_hr_request.status','<>','REQUEST')
                           ->where('tb_asset_hr_request.status','<>','ON PROGRESS')
                           ->get();
            }
        }

        
        // $request_asset = DB::table('tb_asset_hr_transaction')
        //                 ->join('tb_asset_hr','tb_asset_hr.id_barang','=','tb_asset_hr_transaction.id_barang')
        //                 ->join('users','users.nik','=','tb_asset_hr_transaction.nik_peminjam')
        //                 ->select('name','nama_barang','description','no_transac','tb_asset_hr.note','nik_peminjam','tb_asset_hr_transaction.id_barang','id_transaction')
        //                 ->where('tb_asset_hr_transaction.status','PENDING')
        //                 ->orderBy('tb_asset_hr_transaction.created_at','asc')
        //                 ->get();

        // $request_asset = DB::table('tb_asset_hr_transaction')
        //                 ->join('users','users.nik','=','tb_asset_hr_transaction.nik_peminjam')
        //                 ->select('tb_asset_hr_transaction.keterangan','tb_asset_hr_transaction.nik_peminjam','tb_asset_hr_transaction.id_transaction','users.name','tb_asset_hr_transaction.created_at','tb_asset_hr_transaction.updated_at','no_transac','note','tgl_peminjaman')
        //                 ->where('tb_asset_hr_transaction.status','PENDING')
        //                 ->orderBy('tb_asset_hr_transaction.created_at','asc')
        //                 ->get();

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

    	return view('HR/asset_hr',compact('asset', 'assetsd', 'pinjaman','users','nomor','user_pinjam','kategori_asset','current_borrowed','current_request','pinjam_request','history_request','sidebar_collapse'))->with(['initView'=> $this->initMenuBase(),'feature_item'=>$this->RoleDynamic('asset_hr')]);
    }

    public function import(Request $request) 
    {
        // $path = $request->file('file')->getRealPath();
        // $data = Excel::import($path)->get();
        $directory = "draft_pr";
        $nameFile = "template_asset_hr.csv";
        // $nameFile = "template_asset_hr_peminjam.csv";
        $folderName = 'Test Draft PR 2';

        $this->uploadToLocal($request->file('file'),$directory,$nameFile);

        $result = $this->readCSV($directory . "/" . $nameFile);
        // return $result;
 
        if(count($result) >= 1){
            foreach ($result as $key => $value) {
                $arr[] = [
                    'id_barang' => $value[0], 
                    'nik' => '1220985100', 
                    'nama_barang' => $value[1], 
                    'code_name' => $value[2], 
                    'serial_number' => $value[3], 
                    'status' => $value[4], 
                    'merk' => $value[6], 
                    'type' => $value[7], 
                    'description' => $value[8],
                    'kategori' => $value[9],
                    'lokasi' => $value[10],
                    'tgl_tambah' => $value[5]
                ];
            }
 
            if(!empty($arr)){
                AssetHR::insert($arr);
            }
        }


        // if(count($result) >= 1){
        //     foreach ($result as $key => $value) {
        //         $arr[] = [
        //             'id_transaction' => $value[0], 
        //             'id_barang' => $value[1], 
        //             'nik_peminjam' => $value[2], 
        //             'status' => $value[3], 
        //             'keterangan' => $value[4], 
        //             'tgl_peminjaman' => $value[5],
        //             'no_transac' => $value[6]
        //         ];
        //     }
 
        //     if(!empty($arr)){
        //         DetailAssetHR::insert($arr);
        //     }
        // }
 
        return back()->with('success', 'Insert Record successfully.');
    }

    public function uploadToLocal($file,$directory,$nameFile){
        $file->move($directory,$nameFile);
    }

    public function readCSV($locationFile){

        if (($open = fopen($locationFile, "r")) !== FALSE) {

            $i = 0;
            $array = [];
            while (($data = fgetcsv($open, 1000, ";")) !== FALSE) {
                if($i != 0){
                    $array[] = $data;
                } else {
                    array_shift($data);                    
                }
                $i++;     
            }
            // if ($i == 1) {
            //     return 'Tidak ada produk';
            // }
            fclose($open);
        }

        return $array;
        // return array_shift($array);
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
                ->select('keterangan','serial_number','tgl_peminjaman','tgl_pengembalian','tb_asset_hr_transaction.note','no_transac','nama_barang','code_name','id_transaction', 'merk')->where('id_transaction',$request->id_transaction)->get();
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
                ->select('nama_barang', 'tb_asset_hr.id_barang', 'description','code_name','status','serial_number',DB::raw('DATEDIFF(tgl_tambah,created_at) AS umur_asset'),'lokasi')
                ->where('tb_asset_hr.id_barang',$request->id_barang)
                ->get();

        return $asset;

        //tambahhhhh
    }

    public function getCategory(Request $request){
    	// return array("results" => DB::table('tb_kategori_asset_hr')->select(DB::raw("`id` AS `no`,`code_kat` AS `id`,`kategori` AS `text`"))->get());
        $data = DB::table('tb_asset_management_category')->select('name as id','name as text')->where('name','like','%'.request('q').'%')->distinct()->get();

        return $data;
    }

    public function getListAsset(Request $request){
    	$category = DB::table('tb_kategori_asset_hr')->where('kategori',$request->category)->first()->id;

        return array("results" => DB::table('tb_asset_hr')->select(
            // DB::raw("CASE WHEN (serial_number is null) THEN CONCAT(`nama_barang`,' - ',`merk`) ELSE CONCAT(`nama_barang`,' - ',`merk`,'(',`serial_number`,')') END AS `text`"),
            // DB::raw("CONCAT(`nama_barang`,' - ',`merk`,'(',`serial_number`,')') AS `text`"),
            DB::raw("`nama_barang` AS `text`"),
            DB::raw("`id_barang` AS `id`"))
            ->where('status','AVAILABLE')
            ->where('kategori',$category)->orderBy('id','desc')->get()); 
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
        $items = $request->input('items'); // This will be an array of the form input

        $inc = DB::table('tb_asset_hr_request')->get();
        $increment = count($inc);
        $no_req = date('ymd');

        $i = 1;

        foreach ($items as $index => $item) {
            $i++;
            // Assuming you have a model `Item`
            $nomor = $increment+$i;
            if($nomor < 10){
                $nomor = '00' . $nomor;
            }

            $id_request = $no_req . $nomor;

            $data = AssetHrRequest::create([
                'id_request'        => $no_req . $nomor,
                'nik'               => Auth::User()->nik,
                'kategori'          => $item['category_asset_request'],
                'nama'              => $item['nama_barang_request'],
                'qty'               => '1',
                'status'            => "REQUEST",
                'link'              => $item['link_barang_request'],
                'merk'              => $item['merk_barang_request'],
                'keperluan'         => $item['keperluan_barang_request'],
                'duration'          => $item['duration_barang_request'],
            ]);

            // $data = array(
            //     // 'id_request'        => $no_req . $nomor,
            //     'nik'               => Auth::User()->nik,
            //     'kategori'          => $item['category_asset_request'],
            //     'nama'              => $item['nama_barang_request'],
            //     'qty'               => '1',
            //     'status'            => "REQUEST",
            //     'link'              => $item['link_barang_request'],
            //     'merk'              => $item['merk_barang_request'],
            //     'keperluan'         => $item['keperluan_barang_request'],
            //     'duration'          => $item['duration_barang_request'],
            //     'created_at'        => date("Y-m-d h:i:s"),
            //     'updated_at'        => date("Y-m-d h:i:s"),
            // );

            $update = AssetHrRequest::where('id_request',$id_request)->first();
            if ($item['duration_barang_request'] == 'Select Date') {
                $date_explode   = explode(' - ', $item['duration_date_range']);

                $date_start     = Carbon::parse($date_explode[0])->format('Y-m-d h:i:s');
                $date_end       = Carbon::parse($date_explode[1])->format('Y-m-d h:i:s');

                // $data['duration_start'] = $date_start;
                // $data['duration_end']   = $date_end;

                $update->duration_start = $date_start;
                $update->duration_end   = $date_end;
            }

            if ($request->hasFile("items.$index.file_barang_request")) {
                $directory = "Asset Request/";
                $allowedfileExtension   = ['jpg', 'JPG','png','PNG','jpeg','pdf'];
                $file                   = $request->file("items.$index.file_barang_request");
                $fileName               = $file->getClientOriginalName();
                $strfileName            = explode('.', $fileName);
                $lastElement            = end($strfileName);
                $nameDoc                = $fileName;
                $extension              = $file->getClientOriginalExtension();
                $check                  = in_array($extension,$allowedfileExtension);

                if ($check) {
                    $this->uploadToLocal($file,$directory,$nameDoc);
                } else {
                    return redirect()->back()->with('alert','Oops! Only pdf');
                }

                if(isset($fileName)){
                    $pdf_url    = urldecode(url("Asset Request/" . $nameDoc));
                    $pdf_name   = $nameDoc;
                } else {
                    $pdf_url    = 'http://test-drive.sinergy.co.id:8000/Lampiran.pdf';
                    $pdf_name   = 'pdf_lampiran';
                }

                $update->link_drive = $this->googleDriveUploadCustom($pdf_name,$directory . $pdf_name);
            } 
            $update->save();

            $insertData[] = $data;
            // insert($insertData);  
        }

        $req_asset = collect(['insertdata'=>$insertData,'requestor_name'=>Auth::User()->name,'request_date'=>date("Y-m-d h:i:s"),'status'=>'new']);

        $cc = User::select('email')
                ->join('role_user','role_user.user_id','=','users.nik')
                ->join('roles','roles.id','=','role_user.role_id')
                ->where('users.status_karyawan','<>','dummy')
                ->where('roles.name','Supply Chain & IT Support Manager')->orwhere('roles.name','Internal Operation Support Manager')->get();

        $cek_role = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('roles.name as name_role','group','mini_group')->where('user_id',Auth::User()->nik)->first();

        if (stripos($cek_role->name_role, 'Manager') !== false) {
            $to = User::select('email')
            ->join('role_user','role_user.user_id','=','users.nik')
            ->join('roles','roles.id','=','role_user.role_id')
            ->where('users.status_karyawan','<>','dummy')
            ->where('roles.name','like','VP%')
            ->where('roles.group','like','%'. $cek_role->group .'%')->first();

            $users = User::select('email')
                ->select('users.name')
                ->join('role_user','role_user.user_id','=','users.nik')
                ->join('roles','roles.id','=','role_user.role_id')
                ->where('roles.name','like','VP%')
            ->where('roles.group','like','%'. $cek_role->group .'%')->first();

        }else if(stripos($cek_role->name_role, 'VP') !== false) {
            $to = User::select('email')
            ->join('role_user','role_user.user_id','=','users.nik')
            ->join('roles','roles.id','=','role_user.role_id')
            ->where('users.status_karyawan','<>','dummy')
            ->where('roles.name','Chief Operating Officer')->first();

            $users = User::select('email')
                ->select('users.name')
                ->join('role_user','role_user.user_id','=','users.nik')
                ->join('roles','roles.id','=','role_user.role_id')
                ->where('users.status_karyawan','<>','dummy')
                ->where('roles.name','Chief Operating Officer')->first();
        }else{
            $to = User::select('email')
            ->join('role_user','role_user.user_id','=','users.nik')
            ->join('roles','roles.id','=','role_user.role_id')
            ->where('users.status_karyawan','<>','dummy')
            ->where('roles.name','<>','Delivery Project Manager')
            ->where('roles.name','like','%Manager%')
            ->where('roles.mini_group','like','%'. $cek_role->mini_group .'%')->first();

            $users = User::select('email')
                ->select('users.name')
                ->join('role_user','role_user.user_id','=','users.nik')
                ->join('roles','roles.id','=','role_user.role_id')
                ->where('users.status_karyawan','<>','dummy')
                ->where('roles.name','like','%Manager%')
                ->orwhere('roles.name','<>','Delivery Project Manager')
                ->where('roles.mini_group','like','%'. $cek_role->mini_group .'%')->first();
        }

        Mail::to($to->email)  // Main recipient
            ->cc($cc)    
            ->send(new RequestAssetHr('new',$users,$req_asset,'[SIMS-APP] Request New Asset'));

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
                ->where('id_position','HR MANAGER')->where('users.status_karyawan','!=','dummy')
                ->where('role_id',11)->get();

        $users = User::select('name')
                ->join('role_user','role_user.user_id','=','users.nik')
                ->where('id_division','HR')
                ->where('id_position','HR MANAGER')->where('users.status_karyawan','!=','dummy')
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
                ->where('id_position','HR MANAGER')->where('users.status_karyawan','!=','dummy')
                ->where('role_id',11)->get();

        $users = User::select('name')
                ->join('role_user','role_user.user_id','=','users.nik')
                ->where('id_division','HR')
                ->where('id_position','HR MANAGER')->where('users.status_karyawan','!=','dummy')
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
                ->select('nama_barang', 'tb_asset_hr.id_barang', 'description','code_name','status','serial_number',DB::raw('DATEDIFF(NOW(),tgl_tambah) AS umur_asset'),'lokasi','tgl_tambah','merk','description','note','harga_beli')
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

            $to = User::join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('email')->where('roles.name','HR GA')->get();

            $users = User::join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name')->where('roles.name','HR GA')->first();

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



        $to = User::join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('email')->where('roles.name','HR GA')->get();

        $users = User::join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name')->where('roles.name','HR GA')->first();

        Mail::to($to)->send(new RequestAssetHr('reqPinjam',$users,$req_asset,'[SIMS-APP] Permohonan untuk Peminjaman Asset'));      
        

        return redirect()->back()->with(['alert'=> $line,'id'=>$req_asset->id_transaction]);
    }

    public function acceptPeminjaman(Request $request){   
        // $update_accept =  DetailAssetHR::where('id_transaction',$request->id_transaction)
        //         ->first();        
        $update = AssetHrRequest::where('id_request',$request->id_request)->first();

        if ($request->status == 'ACCEPT') {
            $update->status = 'ACCEPT';
            $update->update(); 

            $emailSubject = '[SIMS-APP] Accepting Request Asset';

            $asset = AssetHR::join('tb_asset_hr_transaction','tb_asset_hr_transaction.id_barang','=','tb_asset_hr.id_barang')
                    ->join('users','users.nik','=','tb_asset_hr_transaction.nik_peminjam')
                    ->select('nama_barang','description','name','tb_asset_hr_transaction.created_at','keterangan','tb_asset_hr_transaction.status')
                    ->where('tb_asset_hr_transaction.id_transaction',$request->id_transaction)
                    ->first();

            $req_asset = collect(['asset'=>$asset,'reason'=>$request->reason]);

            $to = User::select('email')->where('nik',$update_accept->nik_peminjam)->get();

            $users = User::select('name')->where('nik',$update_accept->nik_peminjam)->first();

            Mail::to($to)->send(new RequestAssetHr('accept',$users,$req_asset,$emailSubject));
        }else if ($request->status == 'PROCESS') {
            $update->status = 'PROCESS';
            $update->update();

            $emailSubject = '[SIMS-APP] Request Asset Notes';

            $asset = DetailAssetHR::join('users','users.nik','=','tb_asset_hr_transaction.nik_peminjam')
                    ->select('name','tb_asset_hr_transaction.created_at','note','tb_asset_hr_transaction.status','keterangan')
                    ->where('tb_asset_hr_transaction.id_transaction',$request->id_transaction)
                    ->first();

            $req_asset = collect(['asset'=>$asset,'reason'=>$request->reason]);

            $to = User::select('email')->where('nik',$update_accept->nik_peminjam)->get();

            $users = User::select('name')->where('nik',$update_accept->nik_peminjam)->first();

            Mail::to($to)->send(new RequestAssetHr('process',$users,$req_asset,$emailSubject));
        }else{
            $emailSubject = '[SIMS-APP] Rejecting Request Asset';

            $asset = DetailAssetHR::join('users','users.nik','=','tb_asset_hr_transaction.nik_peminjam')
                    ->select('name','tb_asset_hr_transaction.created_at','note','tb_asset_hr_transaction.status','keterangan')
                    ->where('tb_asset_hr_transaction.id_transaction',$request->id_transaction)
                    ->first();

            $req_asset = collect(['asset'=>$asset,'reason'=>$request->reason]);

            $to = User::select('email')->where('nik',$update_accept->nik_peminjam)->get();

            $users = User::select('name')->where('nik',$update_accept->nik_peminjam)->first();

            Mail::to($to)->send(new RequestAssetHr('reject',$users,$req_asset,$emailSubject));
        }

        return redirect()->back()->with('alert','Asset Request Accepted!');
        
    }

    public function acceptNewAsset(Request $request){
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')
                    ->select('name', 'roles.group','roles.mini_group')
                    ->where('user_id', Auth::User()->nik)
                    ->first(); 

        $update = AssetHrRequest::where('id_request',$request->id_request)->first();

        if ($request->status == 'ACCEPT') {
            if ($request->notes != '') {
                $store_notes                = new AssetNotesTransaction();
                $store_notes->id_request    = $request->id_request;
                $store_notes->nik           = Auth::User()->nik;
                $store_notes->notes         = 'Approve';
                $store_notes->save();
            }

            if($request->status_notes == 'ACCEPT' || $update->status_notes == 'ACCEPT'){
                $status_email = 'accept';

                if ($cek_role->mini_group == 'Supply Chain & IT Support') {
                    $update->status         = 'ACCEPT';
                }else{
                    $update->status         = 'ON PROGRESS';
                }
                $update->status_notes   = 'ACCEPT';
                $update->updated_at     = date('Y-m-d h:i:s');
                $update->accept_by      = Auth::user()->nik;

                $alerts = 'New Asset Request Accepted!';

                $emailSubject = '[SIMS-APP] Request Asset ( ' . $update->nama . ' ) sudah disetujui';
            }else if($request->status_notes == 'REJECT' || $update->status_notes == 'REJECT'){
                $status_email = 'reject';

                $update->status = 'REJECT';
                $update->status_notes = 'REJECT';
                $update->updated_at = date('Y-m-d h:i:s');
                $update->accept_by      = Auth::user()->nik;

                $alerts = 'New Asset Request Rejected!';

                $emailSubject = '[SIMS-APP] Rejecting Request New Asset';
            }
        }else if($request->status == 'PENDING'){
            $store_notes = new AssetNotesTransaction();
            $store_notes->id_request = $request->id_request;
            $store_notes->nik        = Auth::User()->nik;
            $store_notes->notes        = 'Approve';
            $store_notes->save();

            $update->status         = 'PENDING';
            $update->status_notes   = $request->status_notes;
            $update->updated_at     = date('Y-m-d h:i:s');
            $update->accept_by      = Auth::user()->nik;

            $alerts = 'New Asset Request Accepted With Notes!';

            $emailSubject = '[SIMS-APP] Request Asset ( ' . $update->nama . ' ) sedang diproses';

            $status_email = 'pending';
        }else{
            if ($request->notes != '') {
                $store_notes                = new AssetNotesTransaction();
                $store_notes->id_request    = $request->id_request;
                $store_notes->nik           = Auth::User()->nik;
                $store_notes->notes         = $request->notes;
                $store_notes->save();
            }

            $update->status         = 'REJECT';
            $update->status_notes   = 'REJECT';
            $update->updated_at     = date('Y-m-d h:i:s');
            $update->accept_by      = Auth::user()->nik;

            $alerts = 'New Asset Request Rejected!';

            $emailSubject = '[SIMS-APP] Rejecting Request New Asset';

            $status_email = 'reject';
        }

        $update->update();  

        $asset = DB::table('tb_asset_hr_request')
                   ->join('users','users.nik','=','tb_asset_hr_request.nik')
                   ->leftjoin('tb_kategori_asset_hr','tb_kategori_asset_hr.id','=','tb_asset_hr_request.kategori_request') 
                   ->select('nama',
                    'tb_kategori_asset_hr.kategori',
                    'tb_kategori_asset_hr.code_kat',
                    'merk',
                    'link',
                    'id_request',
                    'users.name as name_requestor',
                    'tb_asset_hr_request.nik',
                    'tb_asset_hr_request.status',
                    'tb_asset_hr_request.created_at',
                    'qty',
                    'keperluan as used_for',
                    DB::raw('CASE WHEN (duration) = "Lifetime" THEN duration ELSE CONCAT(duration_start," s/d ",duration_end) END as duration'),
                    'reason',
                    'link_drive'
                    )
                   ->where('id_request',$request->id_request)
                   ->get();

        $req_asset = collect(['asset'=>$asset,'reason'=>$request->reason,'notes'=>$request->notes]);


        if ($update->status == "ON PROGRESS") {
            $to = User::join('role_user','role_user.user_id','=','users.nik')
                        ->join('roles', 'roles.id', '=', 'role_user.role_id')
                        ->select('users.name','email', 'roles.group')
                        ->where('roles.name', 'Asset, Facility & HSE Management')
                        ->where('users.status_karyawan','<>','dummy')
                        ->first(); 

            $cc = User::join('role_user','role_user.user_id','=','users.nik')
                    ->join('roles', 'roles.id', '=', 'role_user.role_id')
                    ->select('email')
                    ->where('nik',$update->nik)
                    ->orwhere('roles.name','Supply Chain & IT Support Manager')->orwhere('roles.name','Internal Operation Support Manager')->get();

            $users = User::join('role_user','role_user.user_id','=','users.nik')
                        ->join('roles', 'roles.id', '=', 'role_user.role_id')
                        ->where('users.status_karyawan','<>','dummy')
                        ->select('users.name', 'roles.group')->where('roles.name', 'Asset, Facility & HSE Management')->first();

            Mail::to($to->email)->cc($cc)->send(new RequestAssetHr($status_email,$users,$req_asset,$emailSubject));  
        }else{
            $to = User::select('email')->where('nik',$update->nik)
                ->first();

            $cek_role = DB::table('users')
                    ->join('role_user','role_user.user_id','users.nik')
                    ->join('roles','roles.id','role_user.role_id')
                    ->select('users.name','roles.name as name_role','group','mini_group')
                    ->where('user_id',$update->nik)->first();

            if (stripos($cek_role->name_role, 'Manager') !== false) {
                $cc = User::join('role_user','role_user.user_id','=','users.nik')
                        ->join('roles', 'roles.id', '=', 'role_user.role_id')
                        ->select('email')
                        ->where('users.status_karyawan','<>','dummy')
                        ->where('roles.name','like','VP%')
                        ->where('roles.group', 'like','%'. $cek_role->group .'%')
                        ->orwhere('roles.name','Supply Chain & IT Support Manager')
                        ->get(); 

            }else if (stripos($cek_role->name_role, 'VP') !== false) {
                $cc = User::join('role_user','role_user.user_id','=','users.nik')
                        ->join('roles', 'roles.id', '=', 'role_user.role_id')
                        ->select('email')
                        ->where('users.status_karyawan','<>','dummy')
                        ->where('roles.name','Chief Operating Officer')
                        ->orwhere('roles.name','Supply Chain & IT Support Manager')
                        ->get();

            }else{
                $cc = User::join('role_user','role_user.user_id','=','users.nik')
                        ->join('roles', 'roles.id', '=', 'role_user.role_id')
                        ->select('email')
                        ->where('users.status_karyawan','<>','dummy')
                        ->where('roles.name','like',"VP%")
                        ->where('roles.group', 'like','%'. $cek_role->group .'%')
                        ->orwhere('roles.name','<>','Delivery Project Manager')
                        ->where('roles.name','like','%Manager%')
                        ->where('roles.mini_group', 'like','%'. $cek_role->mini_group .'%')
                        ->orwhere('roles.name','Supply Chain & IT Support Manager')
                        ->get(); 
            }
            
            $users = User::select('name')->where('nik',$update->nik)->first();

            Mail::to($to)->cc($cc)->send(new RequestAssetHr($status_email,$users,$req_asset,$emailSubject));  
        }
        

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
        $sheet->setCellValue('B3','Tahun ' . date('Y'));
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
        ob_end_clean();
        return $writer->save("php://output");
    }

    public function getDetailAcceptRequest(Request $request)
    {
        $asset = AssetHrRequest::select('id_request',
            DB::raw('CONCAT(nama," - ",kategori) as category'),
            'keperluan as used_for',
            'reason')
            ->where('id_request',$request->id_request)
            ->get();

        return $asset;
    }

    public function storeNotesAssetTransaction(Request $request){
        $store_notes = new AssetNotesTransaction();
        $store_notes->id_request = $request->id_request;
        $store_notes->nik        = Auth::User()->nik;
        $store_notes->notes      = $request->notes;
        $store_notes->save();

        $asset = DB::table('tb_asset_hr_request')
                   ->join('users','users.nik','=','tb_asset_hr_request.nik')
                   ->leftjoin('tb_kategori_asset_hr','tb_kategori_asset_hr.id','=','tb_asset_hr_request.kategori_request') 
                   ->select('nama',
                    'tb_kategori_asset_hr.kategori',
                    'tb_kategori_asset_hr.code_kat',
                    'merk',
                    'link',
                    'id_request',
                    'users.name as name_requestor',
                    'tb_asset_hr_request.nik',
                    'tb_asset_hr_request.status',
                    'tb_asset_hr_request.created_at',
                    'qty',
                    'keperluan as used_for',
                    DB::raw('CASE WHEN (duration) = "Lifetime" THEN duration ELSE CONCAT(duration_start," s/d ",duration_end) END as duration'),
                    'reason',
                    'link_drive'
                    )
                   ->where('id_request',$request->id_request)
                   ->get();

        $req_asset = collect(['asset'=>$asset,'reason'=>$request->reason,'notes'=>$request->notes]);

        $emailSubject = '[SIMS-APP] New Notes Request Asset - ' . $request->id_request;

        $cekRoles = RoleUser::where('user_id',Auth::User()->nik)->join('roles','roles.id','=','role_user.role_id')->first();
        if ($cekRoles->mini_group == "Supply Chain & IT Support") {
            $getData    = AssetHrRequest::where('id_request',$request->id_request)->first();
            $to         = User::select('email')->where('nik',$getData->nik)->get();
            $users      = User::select('name')->where('nik',$getData->nik)->first();

            Mail::to($to)->send(new RequestAssetHr('pending',$users,$req_asset,$emailSubject)); 
        }else{
            $users = User::select('email')
                ->select('users.name')
                ->join('role_user','role_user.user_id','=','users.nik')
                ->join('roles','roles.id','=','role_user.role_id')
                ->where('users.status_karyawan','<>','dummy')
                ->where('roles.name','Asset, Facility & HSE Management')->first();

            $to = User::select('email')
                    ->join('role_user','role_user.user_id','=','users.nik')
                    ->join('roles','roles.id','=','role_user.role_id')
                    ->where('users.status_karyawan','<>','dummy')
                    ->where('roles.name','Asset, Facility & HSE Management')->get();

            $cc = User::select('email')
                    ->join('role_user','role_user.user_id','=','users.nik')
                    ->join('roles','roles.id','=','role_user.role_id')
                    ->where('users.status_karyawan','<>','dummy')
                    ->where('roles.name','Supply Chain & IT Support Manager')->get();

            Mail::to($to)->cc($cc)->send(new RequestAssetHr('pending',$users,$req_asset,$emailSubject));
        }
        
        return $store_notes;
    }

    public function googleDriveUploadCustom($fileName,$locationFile){
        $client = $this->getClient();
        $service = new Google_Service_Drive($client);

        $file = new Google_Service_Drive_DriveFile();
        $file->setName($fileName);
        // $file->setParents($parentID);

        // $file = new Google_Service_Drive_DriveFile();
        // $file->setName($nameFolder);
        // $file->setMimeType('application/vnd.google-apps.folder');
        $file->setDriveId(env('GOOGLE_DRIVE_DRIVE_ID'));
        $file->setParents([env('GOOGLE_DRIVE_PARENT_ID_Assset_Request')]);

        $result = $service->files->create(
            $file, 
            array(
                'data' => file_get_contents($locationFile, false, stream_context_create(["ssl" => ["verify_peer"=>false, "verify_peer_name"=>false],"http" => ['protocol_version'=>'1.1']])),
                'mimeType' => 'application/octet-stream',
                'uploadType' => 'multipart',
                'supportsAllDrives' => true
            )
        );

        $optParams = array(
          'fields' => 'files(webViewLink)',
          'q' => 'name="'.$fileName.'"',
          'supportsAllDrives' => true,
          'includeItemsFromAllDrives' => true
        );

        unlink($locationFile);
        $link = $service->files->listFiles($optParams)->getFiles()[0]->getWebViewLink();
        return $link;
    }

    public function getClient()
    {
        $client = new Google_Client();
        $client->setApplicationName('Google Drive API PHP Quickstart');
        $client->setAuthConfig(env('AUTH_CONFIG'));
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');
        $client->setScopes("https://www.googleapis.com/auth/drive");
        
        $tokenPath = env('TOKEN_PATH');
        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            if($accessToken != null){
                $client->setAccessToken($accessToken);
            }
        }

        if ($client->isAccessTokenExpired()) {
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                $authUrl = $client->createAuthUrl();

                if(isset($_GET['code'])){
                    $authCode = trim($_GET['code']);
                    $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
                    $client->setAccessToken($accessToken);

                    echo "Access Token = " . json_encode($client->getAccessToken());

                    if (array_key_exists('error', $accessToken)) {
                        throw new Exception(join(', ', $accessToken));
                    }
                } else {
                    echo "Open the following link in your browser :<br>";
                    echo "<a href='" . $authUrl . "'>google drive create token</a>";
                }

                
            }
            // if (!file_exists(dirname($tokenPath))) {
            //     mkdir(dirname($tokenPath), 0700, true);
            // }
            file_put_contents($tokenPath, json_encode($client->getAccessToken()));
        }
        return $client;
    }
}
