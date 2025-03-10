<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\AssetMgmt;
use App\AssetMgmtDetail;
use App\AssetMgmtLog;
use App\AssetMgmtDocument;
use App\AssetMgmtAssign;
use App\AssetMgmtCategory;
use App\AssetMgmtScheduling;
use App\SalesProject;
use App\AssetMgmtAssignEngineer;
use App\TicketingDetail;
use App\Mail\MailReminderMaintenanceEndAsset;
use App\Mail\MailGenerateBAST;
use App\AssetMgmtServicePoint;
use App\TB_Contact;
use App\User;
use Mail;
use Illuminate\Validation\Rule;
use Validator;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Google_Client;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;

use Carbon\Carbon;
use DB;
use Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

use PDF;

use setasign\Fpdf\Fpdf;
use setasign\Fpdi\Fpdi;
use setasign\FpdiProtection\FpdiProtection;
use setasign\Fpdi\PdfParser\StreamReader;

class AssetMgmtController extends Controller
{

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

    public function index()
    {
        return view('asset_management/asset')->with(['initView'=> $this->initMenuBase(),'feature_item'=>$this->RoleDynamic('asset_mgmt')]);
    }

    public function detail_asset()
    {
        return view('asset_management/detail_asset')->with(['initView'=> $this->initMenuBase(),'feature_item'=>$this->RoleDynamic('asset_mgmt')]);
    }

    public function dashboard()
    {
        $year = DB::table('tb_asset_management')->selectRaw('YEAR(created_at) as year')->distinct()->get();

        return view('asset_management/dashboard',compact('year'))->with(['initView'=> $this->initMenuBase(),'feature_item'=>$this->RoleDynamic('asset_mgmt')]);
    }

    public function asset_scheduling()
    {
        return view('asset_management/asset_scheduling')->with(['initView'=> $this->initMenuBase(),'feature_item'=>$this->RoleDynamic('asset_mgmt')]);
    }

    public function getSearchData(Request $request)
    {
        // $nik = Auth::User()->nik;
        // $cek_role = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role','group','mini_group')->where('user_id',$nik)->first();

        // $getPid = DB::table('ticketing__user')->where('nik',Auth::User()->nik)->get()->pluck('pid');
        // $getPidPm = DB::table('tb_pmo')->join('tb_pmo_assign','tb_pmo_assign.id_project','tb_pmo.id')->join('role_user','role_user.user_id','tb_pmo_assign.nik')->join('roles','roles.id','role_user.role_id')->where('nik',Auth::User()->nik)->where('name','!=','Asset Management')->get()->pluck('project_id');

        // $getId = AssetMgmt::join('tb_asset_management_detail','tb_asset_management_detail.id_asset','tb_asset_management.id_asset')->select('tb_asset_management_detail.id_asset','detail_lokasi','tb_asset_management_detail.id');
        // $getLastId = DB::table($getId,'temp')->groupBy('id_asset')->selectRaw('MAX(`temp`.`id`) as `id_last_asset`')->selectRaw('id_asset');

        // $data = DB::table($getLastId, 'temp2')->join('tb_asset_management','tb_asset_management.id_asset','temp2.id_asset')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')
        //     ->select('tb_asset_management_detail.pid','asset_owner','category','category_peripheral','tb_asset_management.id_asset','type_device','vendor','status','rma','spesifikasi','serial_number','notes')
        //     ->where('category_peripheral','-')
        //     ->orderBy('tb_asset_management.created_at','desc');  

        $nik = Auth::User()->nik;
        $cek_role = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role','group','mini_group')->where('user_id',$nik)->first();

        $getPid = DB::table('ticketing__user')->where('nik',Auth::User()->nik)->get()->pluck('pid');
        $getPidPm = DB::table('tb_pmo')->join('tb_pmo_assign','tb_pmo_assign.id_project','tb_pmo.id')->join('role_user','role_user.user_id','tb_pmo_assign.nik')->join('roles','roles.id','role_user.role_id')->where('nik',Auth::User()->nik)->where('name','!=','Asset Management')->get()->pluck('project_id');

        $getId = AssetMgmt::join('tb_asset_management_detail','tb_asset_management_detail.id_asset','tb_asset_management.id')->select('tb_asset_management_detail.id_asset','detail_lokasi','tb_asset_management_detail.id');
        $getLastId = DB::table($getId,'temp')->groupBy('id_asset')->selectRaw('MAX(`temp`.`id`) as `id_last_asset`')->selectRaw('id_asset');

        $data = DB::table($getLastId, 'temp2')
            ->join('tb_asset_management','tb_asset_management.id','temp2.id_asset')
            ->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')
            ->leftjoin('users','users.nik','=','tb_asset_management_detail.pic')
            ->leftjoin('role_user','role_user.user_id','=','users.nik')
            ->leftjoin('roles','roles.id','=','role_user.role_id')
            ->select('tb_asset_management_detail.pid',
                'asset_owner',
                'category',
                'category_peripheral',
                'tb_asset_management.id_asset',
                'type_device',
                'vendor',
                'status',
                'rma',
                'spesifikasi',
                'serial_number',
                'notes',
                'related_id_asset',
                'tb_asset_management.id',
                'id_device_customer',
                'client',
                DB::raw('CONCAT(users.name, " - ",roles.name) AS pic_name')
            )->groupBy(
                'tb_asset_management_detail.pid',
                'asset_owner',
                'category',
                'tb_asset_management.id_asset',
                'type_device',
                'vendor',
                'status',
                'rma',
                'spesifikasi',
                'serial_number',
                'notes',
                'tb_asset_management.id',
                'id_device_customer',
                'client',
                'pic_name'
            )
            ->orderBy('tb_asset_management.created_at','desc'); 

        $searchFields = ['asset_owner', 'tb_asset_management_detail.pid', 'serial_number', 'tb_asset_management.id_asset', 'type_device', 'vendor', 'rma', 'spesifikasi','notes','id_device_customer','client','pid','users.name','roles.name'];

        if ($cek_role->mini_group == 'Supply Chain & IT Support' || $cek_role->name_role == 'VP Internal Chain Management' || $cek_role->name_role == 'Chief Operating Officer') {
            $data = $data;
        } else if ($cek_role->name_role == 'Delivery Project Coordinator') {
            $data = $data->whereIn('pid',$getPid);
        } elseif ($cek_role->name_role == 'Delivery Project Manager') {
            $data = $data->whereIn('pid',$getPidPm);
        }

        $data->where(function($data) use($request, $searchFields){
            $searchWildCard = '%'. $request->search . '%';
            foreach ($searchFields as $datas) {
                $data->orWhere($datas, 'LIKE', $searchWildCard);
            }
        });

        return array("data"=>$data->get());
    }

    public function getAssetOwner()
    {
        // $order = ["PSIP"];

        $query = request('q');

        $data = TB_Contact::select('code as id', DB::raw('CONCAT(code, " - ", customer_legal_name) AS text'))
            ->when($query, function ($queryBuilder) use ($query) {
                $queryBuilder->where('code', 'like', '%' . $query . '%');
            })
            ->distinct()
            ->get();

        $data = $data->toArray();

        if (!$query || empty($data)) {
            $newObject = ['id' => 'PSIP', 'text' => 'PSIP - PT. Sinergy Informasi Pratama'];
            $newObject2 = ['id' => 'DIST', 'text' => 'DIST - Distributor'];
            $newObject3 = ['id' => 'PRIN', 'text' => 'PRIN - Principal'];

            array_unshift($data, $newObject3);
            array_unshift($data, $newObject2);
            array_unshift($data, $newObject);
        }

        // return response()->json($data);

        // $data->push($newObject)->push($newObject2)->push($newObject3);

        return response()->json($data);
    }

    public function getDataAsset()
    {
        $nik = Auth::User()->nik;
        $cek_role = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role','group','mini_group')->where('user_id',$nik)->first();

        $getPid = DB::table('ticketing__user')->where('nik',Auth::User()->nik)->get()->pluck('pid');
        $getPidPm = DB::table('tb_pmo')->join('tb_pmo_assign','tb_pmo_assign.id_project','tb_pmo.id')->join('role_user','role_user.user_id','tb_pmo_assign.nik')->join('roles','roles.id','role_user.role_id')->where('nik',Auth::User()->nik)->where('name','!=','Asset Management')->get()->pluck('project_id');

        // $getId = AssetMgmt::join('tb_asset_management_detail','tb_asset_management_detail.id_asset','tb_asset_management.id')
        //         ->select('tb_asset_management_detail.id_asset','detail_lokasi','tb_asset_management_detail.id');

        // $getLastId = DB::table($getId,'temp')
        //         ->selectRaw('MAX(`temp`.`id`) as `id_last_asset`')
        //         ->selectRaw('id_asset');

        $getId = AssetMgmt::join('tb_asset_management_detail','tb_asset_management_detail.id_asset','tb_asset_management.id')->select('tb_asset_management_detail.id_asset','detail_lokasi','tb_asset_management_detail.id');
        $getLastId = DB::table($getId,'temp')->groupBy('id_asset')->selectRaw('MAX(`temp`.`id`) as `id_last_asset`')->selectRaw('id_asset');

        $data = DB::table($getLastId, 'temp2')
            ->join('tb_asset_management','tb_asset_management.id','temp2.id_asset')
            ->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')
            ->leftjoin('users','users.nik','=','tb_asset_management_detail.pic')
            ->leftjoin('role_user','role_user.user_id','=','users.nik')
            ->leftjoin('roles','roles.id','=','role_user.role_id')
            ->select('tb_asset_management_detail.pid',
                'asset_owner',
                'category',
                'tb_asset_management.id_asset',
                'type_device',
                'vendor',
                'status',
                'rma',
                'spesifikasi',
                'serial_number',
                'notes',
                'tb_asset_management.id',
                'id_device_customer',
                'client',
                DB::raw('CONCAT(users.name, " - ",roles.name) AS pic_name')
            )->groupBy(
                'tb_asset_management_detail.pid',
                'asset_owner',
                'category',
                'tb_asset_management.id_asset',
                'type_device',
                'vendor',
                'status',
                'rma',
                'spesifikasi',
                'serial_number',
                'notes',
                'tb_asset_management.id',
                'id_device_customer',
                'client',
                'pic_name'
            )
            ->orderBy('tb_asset_management.created_at','desc'); 

        if ($cek_role->mini_group == 'Supply Chain & IT Support' || $cek_role->name_role == 'VP Internal Chain Management' || $cek_role->name_role == 'Chief Operating Officer') {
            $data = $data;
        } else if ($cek_role->name_role == 'Delivery Project Coordinator') {
            $data = $data->whereIn('pid',$getPid);
        } elseif ($cek_role->name_role == 'Delivery Project Manager') {
            $data = $data->whereIn('pid',$getPidPm);
        } elseif($cek_role->mini_group == 'Supply Chain & IT Support' || $cek_role->mini_group == 'Internal Operation Support'){
            $data = $data->where('pid','INTERNAL');
        } else if ($cek_role->name_role == 'Synergy System & Services Manager' ) {
            $data = $data->where('pid','!=','INTERNAL');
        } 

        return array("data"=>$data->get());
    }

    public function getVendor(Request $request)
    {   
        $searchTerm = request('q');

        $data = DB::table('tb_asset_management')->select('vendor as id','vendor as text')->where('vendor','!=',null)
            ->whereRaw('LOWER(vendor) LIKE ?', ['%' . strtolower($searchTerm) . '%'])
            // ->where('vendor','ilike','%'.request('q').'%')
            ->distinct()->get();

        return $data;
    }

    public function getTypeDevice(Request $request)
    {
        $data = DB::table('tb_asset_management')->select('type_device as id','type_device as text')->where('type_device','!=',null)->whereRaw('LOWER(type_device) LIKE ?', ['%' . strtolower(request('q')) . '%'])->distinct()->get();

        return $data;
    }

    public function getCategoryPeripheral(Request $request)
    {
        $data = DB::table('tb_asset_management')->select('category_peripheral as id','category_peripheral as text')->where('category_peripheral','!=',null)->where('category_peripheral','!=','-')->where('category_peripheral','like','%'.request('q').'%')->distinct()->get();

        return $data;
    }

    public function getLevelSupport(Request $request)
    {
        $searchTerm = request('q');
        
        $data = DB::table('tb_asset_management_detail')->select('second_level_support as id','second_level_support as text')->where('second_level_support','!=',null)->where('second_level_support','!=','null')
            // ->where('second_level_support','like','%'.request('q').'%')
            ->whereRaw('LOWER(second_level_support) LIKE ?', ['%' . strtolower($searchTerm) . '%'])
            ->distinct()->get();

        return $data;
    }


    public function getFilterData(Request $request)
    {
        $nik = Auth::User()->nik;
        $cek_role = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role','group','mini_group')->where('user_id',$nik)->first();

        $getPid = DB::table('ticketing__user')->where('nik',Auth::User()->nik)->get()->pluck('pid');
        $getPidPm = DB::table('tb_pmo')->join('tb_pmo_assign','tb_pmo_assign.id_project','tb_pmo.id')->join('role_user','role_user.user_id','tb_pmo_assign.nik')->join('roles','roles.id','role_user.role_id')->where('nik',Auth::User()->nik)->where('name','!=','Asset Management')->get()->pluck('project_id');

        $getId = AssetMgmt::join('tb_asset_management_detail','tb_asset_management_detail.id_asset','tb_asset_management.id')->select('tb_asset_management_detail.id_asset','detail_lokasi','tb_asset_management_detail.id');
        $getLastId = DB::table($getId,'temp')->groupBy('id_asset')->selectRaw('MAX(`temp`.`id`) as `id_last_asset`')->selectRaw('id_asset');

        $data = DB::table($getLastId, 'temp2')
            ->join('tb_asset_management','tb_asset_management.id','temp2.id_asset')
            ->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')
            ->join('tb_asset_management_category','tb_asset_management_category.name','tb_asset_management.category')
            ->leftjoin('users','users.nik','=','tb_asset_management_detail.pic')
            ->leftjoin('role_user','role_user.user_id','=','users.nik')
            ->leftjoin('roles','roles.id','=','role_user.role_id')
            ->select('tb_asset_management_detail.pid',
                'asset_owner',
                'category',
                'category_peripheral',
                'tb_asset_management.id_asset',
                'type_device',
                'vendor',
                'status',
                'rma',
                'spesifikasi',
                'serial_number',
                'notes',
                'related_id_asset',
                'tb_asset_management.id',
                'id_device_customer',
                'client',
                DB::raw('CONCAT(users.name, " - ",roles.name) AS pic_name')
            )->groupBy(
                'tb_asset_management_detail.pid',
                'asset_owner',
                'category',
                'tb_asset_management.id_asset',
                'type_device',
                'vendor',
                'status',
                'rma',
                'spesifikasi',
                'serial_number',
                'notes',
                'tb_asset_management.id',
                'id_device_customer',
                'client',
                'pic_name'
            )
            ->orderBy('tb_asset_management.created_at','desc'); 

        if (isset($request->pid)) {
            $data->where('pid',$request->pid);
        }

        if (isset($request->assetOwner)) {
            $data->where('asset_owner',$request->assetOwner);
        }

        if (isset($request->category)) {
            if ($request->category == 'Peripheral') {
                $data->where('category_peripheral','!=','-');
            } else {
                $data->where('tb_asset_management_category.id_category',$request->category);
            }
        } 


        if (isset($request->client)) {
            $data->where('client',$request->client);
        }    

        if ($cek_role->mini_group == 'Supply Chain & IT Support' || $cek_role->name_role == 'VP Internal Chain Management' || $cek_role->name_role == 'Chief Operating Officer') {
            $data = $data;
        } else if ($cek_role->name_role == 'Delivery Project Coordinator') {
            $data = $data->whereIn('pid',$getPid);
        } elseif ($cek_role->name_role == 'Delivery Project Manager') {
            $data = $data->whereIn('pid',$getPidPm);
        } else if ($cek_role->name_role == 'Synergy System & Services Manager' ) {
            $data = $data->where('pid','!=','INTERNAL');
        } elseif($cek_role->mini_group == 'Supply Chain & IT Support' || $cek_role->mini_group == 'Internal Operation Support'){
            $data = $data->where('pid','INTERNAL');
        } 

        return array("data"=>$data->get());
    }

    public function getClientAsset()
    {
        $nik = Auth::User()->nik;
        $cek_role = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role','group','mini_group')->where('user_id',$nik)->first();

        $getPid = DB::table('ticketing__user')->where('nik',Auth::User()->nik)->get()->pluck('pid');
        $getPidPm = DB::table('tb_pmo')->join('tb_pmo_assign','tb_pmo_assign.id_project','tb_pmo.id')->join('role_user','role_user.user_id','tb_pmo_assign.nik')->join('roles','roles.id','role_user.role_id')->where('nik',Auth::User()->nik)->where('name','!=','Asset Management')->get()->pluck('project_id');

        $getClient = DB::table('tb_asset_management_detail')->select('client as id','client as text')->where('client','like','%'.request('q').'%')->groupby('client');

        if ($cek_role->mini_group == 'Supply Chain & IT Support' || $cek_role->name_role == 'VP Internal Chain Management' || $cek_role->name_role == 'Chief Operating Officer') {
            $getClient = $getClient;
        } else if ($cek_role->name_role == 'Delivery Project Coordinator') {
            $getClient = $getClient->whereIn('pid',$getPid);
        } elseif ($cek_role->name_role == 'Delivery Project Manager') {
            $getClient = $getClient->whereIn('pid',$getPidPm);
        } else if ($cek_role->name_role == 'Synergy System & Services Manager' ) {
            $getClient = $getClient->where('pid','!=','INTERNAL');
        } 

        return response()->json($getClient->get());
    }

    public function storeAsset(Request $request)
    {   
        if ($request->typeAsset == 'peripheral') {
            $category = $request->category;
            $inc = AssetMgmt::select('category')
                        ->where('asset_owner', $request->assetOwner)
                        ->where('category_peripheral', $category)
                        ->get();
            $increment = count($inc);
            $nomor = $increment+1;
            if($nomor < 10){
                $nomor = '00000' . $nomor;
            } elseif ($nomor < 100) {
                $nomor = '0000' . $nomor;
            } elseif ($nomor < 1000) {
                $nomor = '000' . $nomor;
            } elseif ($nomor < 10000) {
                $nomor = '00' . $nomor;
            } else {
                $nomor = '0' . $nomor;
            }
            $cat = $category;
        } else {
            $category = $request->category;
            $inc = AssetMgmt::select('category')
                        ->where('asset_owner', $request->assetOwner)
                        ->where('category', $request->category_text)
                        ->get();

            $increment = count($inc);
            $nomor = $increment+1;
            if($nomor < 10){
                $nomor = '00000' . $nomor;
            } elseif ($nomor < 100) {
                $nomor = '0000' . $nomor;
            } elseif ($nomor < 1000) {
                $nomor = '000' . $nomor;
            } elseif ($nomor < 10000) {
                $nomor = '00' . $nomor;
            } else {
                $nomor = '0' . $nomor;
            }


            // if ($category == 'Network') {
            //     $cat = 'NTW';
            // } elseif($category == 'Security'){
            //     $cat = 'SCR';
            // } elseif($category == 'Computer'){
            //     $cat = 'COM';
            // } else {
            //     $cat = strtoupper(substr($category, 0, 3));
            // }
            $cat = $category;

        }

        $id =  $request->assetOwner . '-' . $cat . '-' . date('m') . date('y') . '-' . $nomor;

        $store = new AssetMgmt();
        $store->id_asset = $id;
        $store->asset_owner = $request->assetOwner;
        $store->category = $request->category_text;
        // if ($request->typeAsset == 'peripheral') {
        //     $store->category_peripheral = $request->categoryPeripheral;
        // } else {
        //     $store->category_peripheral = '-';
        // }
        if ($request->pid == 'INTERNAL') {
            if ($request->pic != null && $request->pic != '' && $request->pic != "null") {
                $store->status = 'Installed';
            }else{
                $store->status = $request->status;
            }
        }else{
            $store->status = $request->status;
        }

        $store->vendor = $request->vendor;
        $store->type_device = $request->typeDevice;
        $store->serial_number = $request->serialNumber;
        $store->spesifikasi = $request->spesifikasi;
        $store->rma = $request->rma;
        $store->notes = $request->notes;
        $store->nilai_buku = str_replace('.', '', $request['nilaiBuku']);
        $store->harga_beli = str_replace('.', '', $request['hargaBeli']);
        if ($request->tanggalBeli == 'Invalid date') {
            $store->tanggal_pembelian = null;
        } else {
            $store->tanggal_pembelian = $request->tanggalBeli;
        }
        $store->reason_status = $request->reason;
        $store->pr = $request->pr;
        $store->save();

        // if ($request->typeAsset == 'asset') {
        // } else if ($request->typeAsset == 'peripheral') {

        if (isset($request->assignTo)) {

            $getId = AssetMgmt::join('tb_asset_management_detail','tb_asset_management_detail.id_asset','tb_asset_management.id')->select('tb_asset_management_detail.id_asset','detail_lokasi','tb_asset_management_detail.id');
            $getLastId = DB::table($getId,'temp')->groupBy('id_asset')->selectRaw('MAX(`temp`.`id`) as `id_last_asset`')->selectRaw('id_asset');

            $data = DB::table($getLastId, 'temp2')->join('tb_asset_management','tb_asset_management.id','temp2.id_asset')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')
                ->select('tb_asset_management_detail.id_asset','id_device_customer','client','pid','kota','alamat_lokasi','detail_lokasi','ip_address','server','port','status_cust','second_level_support','operating_system','version_os','installed_date','license','license_end_date','license_start_date','maintenance_end','maintenance_start','latitude','longitude','service_point','tb_asset_management.pr')
                ->where('tb_asset_management_detail.id_asset',$request->assignTo)
                ->first();

            // return gettype($data->latitude) . $data->latitude;

            $storeDetail = new AssetMgmtDetail();
            $storeDetail->id_asset = $store->id;
            $storeDetail->client = $data->client;
            $storeDetail->pid = $data->pid;
            $storeDetail->kota = $data->kota;
            $storeDetail->alamat_lokasi = $data->alamat_lokasi;
            $storeDetail->detail_lokasi = $data->detail_lokasi;
            $storeDetail->ip_address = $data->ip_address;
            $storeDetail->server = $data->server;
            $storeDetail->port = $data->port;
            // $storeDetail->status_cust = $data->statusCust;
            $storeDetail->second_level_support = $data->second_level_support;
            $storeDetail->operating_system = $data->operating_system;
            $storeDetail->version_os = $data->version_os;
            $storeDetail->installed_date = $data->installed_date;
            $storeDetail->license = $data->license;
            $storeDetail->latitude = $data->latitude;
            $storeDetail->longitude = $data->longitude;
            $storeDetail->service_point = $data->service_point;
            $storeDetail->license_start_date = $data->license_start_date;
            $storeDetail->license_end_date = $data->license_end_date;
            $storeDetail->maintenance_start = $data->maintenance_start;
            $storeDetail->maintenance_end = $data->maintenance_end;
            $storeDetail->date_add = Carbon::now()->toDateTimeString();
            $storeDetail->pr = $data->pr;

            $storeAssign = new AssetMgmtAssign();
            $storeAssign->id_asset_induk = $request->assignTo;
            $storeAssign->id_asset_peripheral = $store->id;
            $storeAssign->date_add = Carbon::now()->toDateTimeString();
            $storeAssign->save();

            $updateAsset = AssetMgmt::where('id',$request->assignTo)->first();
            $updateAsset->status = 'Installed';
            $updateAsset->save();

            $storeDetail->related_id_asset = $request->assignTo;

            $storeDetail->save();

        } else {
            $storeDetail = new AssetMgmtDetail();
            $storeDetail->id_asset = $store->id;
            $storeDetail->id_device_customer = $request->idDeviceCustomer;
            $storeDetail->client = $request->client;
            $storeDetail->pid = $request->pid;
            $storeDetail->kota = $request->kota;
            $storeDetail->alamat_lokasi = $request->alamatLokasi;
            $storeDetail->detail_lokasi = $request->detailLokasi;
            $storeDetail->latitude = $request->latitude;
            $storeDetail->longitude = $request->longitude;
            $storeDetail->ip_address = $request->ipAddress;
            $storeDetail->server = $request->ipServer;
            $storeDetail->port = $request->port;
            $storeDetail->status_cust = $request->statusCust;
            $storeDetail->second_level_support = $request->secondLevelSupport;
            $storeDetail->operating_system = $request->operatingSystem;
            $storeDetail->version_os = $request->versionOs;
            $storeDetail->service_point = $request->servicePoint;
            $storeDetail->date_add = Carbon::now()->toDateTimeString();
            $storeDetail->pr = $request->pr;
            $storeDetail->pic = $request->pic;
            $storeDetail->accessoris = $request->accessoris;
            if ($request->installedDate == 'Invalid date') {
                $storeDetail->installed_date = null;
            } else {
                $storeDetail->installed_date = $request->installedDate;
            }
            $storeDetail->license = $request->license;

            if ($request->licenseStartDate == 'Invalid date') {
                $storeDetail->license_start_date = null;
            } else {
                $storeDetail->license_start_date = $request->licenseStartDate;
            }

            // $storeDetail->license_start_date = $request->licenseStartDate;

            if ($request->licenseStartDate == 'Invalid date') {
                $storeDetail->license_end_date = null;
            } else {
                $storeDetail->license_end_date = $request->licenseEndDate;
            }

            // $storeDetail->license_end_date = $request->licenseEndDate;
            // return $request->maintenanceStart;
            if ($request->maintenanceStart == 'Invalid date' || $request->maintenanceStart == '') {
                $storeDetail->maintenance_start = null;
            } else {
                $storeDetail->maintenance_start = $request->maintenanceStart;
            }

            if ($request->maintenanceEnd == 'Invalid date' || $request->maintenanceEnd == '') {
                $storeDetail->maintenance_end = null;
            } else {
                $storeDetail->maintenance_end = $request->maintenanceEnd;
            }

            // $storeDetail->maintenance_start = $request->maintenanceStart;
            // $storeDetail->maintenance_end = $request->maintenanceEnd;
            $storeDetail->save();

        }
            // else {
            //     $storeDetail = new AssetMgmtDetail();
            //     $storeDetail->id_asset = $store->id;
            //     $storeDetail->save();
            // }

        $storeLog = new AssetMgmtLog();
        $storeLog->id_asset = $store->id;
        $storeLog->operator = Auth::User()->name;
        $storeLog->date_add = Carbon::now()->toDateTimeString();
        $storeLog->activity = 'Add New Asset ' .$id. ' with Category ' . $request->category;
        $storeLog->save();

        if ($request->hasFile('inputDoc')) {
            $directory = "Asset Management/";
            $get_parent_drive = AssetMgmt::where('id', $store->id)->first();
            $allowedfileExtension   = ['jpg', 'JPG','png','PNG','jpeg'];
            $file                   = $request->file('inputDoc');
            $fileName               = $file->getClientOriginalName();
            $strfileName            = explode('.', $fileName);
            $lastElement            = end($strfileName);
            $nameDoc                = $fileName;
            $extension              = $file->getClientOriginalExtension();
            $check                  = in_array($extension,$allowedfileExtension);

            $updateDetail = AssetMgmtDetail::where('id',$storeDetail->id)->first();
            if ($check) {
                $this->uploadToLocal($request->file('inputDoc'),$directory,$nameDoc);
                $updateDetail->document_name             = 'Bukti Asset '.$id;
            } else {
                return redirect()->back()->with('alert','Oops! Only pdf');
            }

            if(isset($fileName)){
                $pdf_url = urldecode(url("Asset Management/" . $nameDoc));
                $pdf_name = $nameDoc;
            } else {
                $pdf_url = 'http://test-drive.sinergy.co.id:8000/Lampiran.pdf';
                $pdf_name = 'pdf_lampiran';
            }

            if ($get_parent_drive->parent_id_drive == null) {
                $parentID = $this->googleDriveMakeFolder($store->id_asset);
            } else {
                $parentID = [];
                $parent_id = explode('"', $get_parent_drive->parent_id_drive)[1];
                array_push($parentID,$parent_id);
            }

            $updateDetail->document_location         = "Asset/Bukti Asset " .$id;
            $updateDetail->link_drive = $this->googleDriveUploadCustom($pdf_name,$directory . $pdf_name,$parentID);
            $updateDetail->save();
        }
        
        if ($request->pic != 'undefined' && $request->pic != 'null') {
            $pdfPath = $this->getPdfBASTAsset($store->id,$storeDetail->id);
            $this->uploadPdfBAST($store->id,$pdfPath);

            $to = User::select('email','name')
                    ->join('role_user','role_user.user_id','=','users.nik')
                    ->where('nik',$request->pic)->first();

            $data = [
                [   
                    'name'              => $to->name,
                    'id_asset'          => $store->id_asset, 
                    'category'          => $store->category,
                    'type_device'       => $store->vendor . " - " . $store->type_device . " - " . $store->serial_number,
                    'spesifikasi'       => $store->spesifikasi,
                    'link_drive'        => AssetMgmtDocument::where('id_detail_asset',$storeDetail->id)->first()->link_drive,

                ]
            ];

            Mail::to($to->email)->send(new MailGenerateBAST($data,'[SIMS-APP] Generate BAST')); 
        } 
    }

    public function getAssetById(Request $request)
    {
        $getId = AssetMgmt::join('tb_asset_management_detail','tb_asset_management_detail.id_asset','tb_asset_management.id')->select('tb_asset_management_detail.id_asset','detail_lokasi','tb_asset_management_detail.id');
        $getLastId = DB::table($getId,'temp')->groupBy('id_asset')->selectRaw('MAX(`temp`.`id`) as `id_last_asset`');

        return $getAll = DB::table($getLastId, 'temp2')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')->join('tb_asset_management', 'tb_asset_management.id', '=', 'tb_asset_management_detail.id_asset')->select('tb_asset_management_detail.id_asset','id_device_customer','client','pid','kota','alamat_lokasi','detail_lokasi','ip_address','server','port','status_cust','second_level_support','operating_system','version_os','installed_date','license','license_end_date','license_start_date','maintenance_end','maintenance_start')->where('tb_asset_management_detail.id_asset',$request->id_asset)->get();
    }

    public function getClientByPid(Request $request)
    {
        $data = DB::table('tb_id_project')->select('customer_name')->where('id_project',$request->pid)->where('id_project','like','%'.request('q').'%')->first();

        return response()->json($data->customer_name);
    }

    public function getPrByYear(Request $request)
    {
        $currentYear = date('Y');       
        $lastYear    = $currentYear - 1;

        $data = DB::table('tb_pr')
            ->select('no_pr as id','no_pr as text')
            ->where(function ($query) use ($currentYear, $lastYear) {
                $query->whereYear('date', $currentYear)
                      ->orWhereYear('date', $lastYear);
            })
            ->where('status','Done')
            ->where('no_pr','like','%'.request('q').'%')
            ->orderby('created_at','desc')
            ->get();

        return $data;
    }

    public function getDateByPr(Request $request)
    {
        $getId = DB::table('tb_pr')->join('tb_pr_activity','tb_pr_activity.id_draft_pr','tb_pr.id_draft_pr')->select('tb_pr_activity.id_draft_pr','tb_pr_activity.status','tb_pr_activity.id');
        $getLastId = DB::table($getId,'temp')->groupBy('id_draft_pr')->selectRaw('MAX(`temp`.`id`) as `id_activity`')->selectRaw('id_draft_pr');

        $dataPr = DB::table($getLastId, 'temp2')->join('tb_pr','tb_pr.id_draft_pr','temp2.id_draft_pr')->join('tb_pr_activity','tb_pr_activity.id','temp2.id_activity')->select('tb_pr_activity.status','activity','tb_pr_activity.date_time')->where('no_pr',$request->no_pr)->get();

        $dataPr->transform(function ($item) {
            $date = \Carbon\Carbon::parse($item->date_time)->addDay();
            
            if ($date->isSaturday()) {
                $date->addDays(2);
            } elseif ($date->isSunday()) {
                $date->addDay();
            }

            $item->date_time = $date->format('Y-m-d');

            return $item;
        });

        return $dataPr->first()->date_time;
    }

    public function getPid(Request $request)
    {
        $nik = Auth::User()->nik;
        $cek_role = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role','group','mini_group')->where('user_id',$nik)->first();

        $getPid = DB::table('ticketing__user')->where('nik',Auth::User()->nik)->get()->pluck('pid');
        $getPidPm = DB::table('tb_pmo')->join('tb_pmo_assign','tb_pmo_assign.id_project','tb_pmo.id')->join('role_user','role_user.user_id','tb_pmo_assign.nik')->join('roles','roles.id','role_user.role_id')->where('nik',Auth::User()->nik)->where('name','!=','Asset Management')->get()->pluck('project_id');

        $getAllPid = SalesProject::join('sales_lead_register', 'sales_lead_register.lead_id', '=', 'tb_id_project.lead_id')->join('users', 'users.nik', '=', 'sales_lead_register.nik')->select('id_project as id',DB::raw("CONCAT(`id_project`,' - ',`name_project`) AS text"))->where('id_company', '1')->where('id_project','like','%'.request('q').'%')->orderBy('tb_id_project.created_at','desc');

        if ($cek_role->mini_group == 'Supply Chain & IT Support' || $cek_role->name_role == 'VP Internal Chain Management' || $cek_role->name_role == 'Chief Operating Officer' || $cek_role->name_role == 'Customer Support Center') {
            $getAllPid = $getAllPid->get();
            $getAllPid = $getAllPid->prepend((object)(['id' => 'INTERNAL','text' => 'INTERNAL']));
        } else if ($cek_role->name_role == 'Delivery Project Coordinator') {
            $getAllPid = $getAllPid->whereIn('id_project',$getPid)->get();
            $getAllPid = $getAllPid->prepend((object)(['id' => 'INTERNAL','text' => 'INTERNAL']));
        } elseif ($cek_role->name_role == 'Delivery Project Manager') {
            $getAllPid = $getAllPid->whereIn('id_project',$getPidPm)->get();
            $getAllPid = $getAllPid->prepend((object)(['id' => 'INTERNAL','text' => 'INTERNAL']));
        } elseif ($cek_role->name_role == 'Synergy System & Services Manager') {
            $getAllPid = $getAllPid->get();
            $getAllPid = $getAllPid->prepend((object)(['id' => 'INTERNAL','text' => 'INTERNAL']));
        }

        return response()->json($getAllPid);
    }

    public function updateAsset(Request $request)
    {
        $update = AssetMgmt::where('id',$request->id_asset)->first();
        $getPid = DB::table('tb_asset_management_detail')->where('id_asset',$request->id_asset)->orderBy('id','desc')->first()->pid;
        if ($update->asset_owner != $request->assetOwner) {

            $dateAsset = DB::table('tb_asset_management')
                ->select(DB::raw("SUBSTRING_INDEX(SUBSTRING_INDEX(id_asset, '-', 3), '-', -1) as id_extracted"))
                ->where('id', $request->id_asset)
                ->first()->id_extracted;

            if ($request->typeAsset == 'peripheral') {
                $category = $update->categoryPeripheral;
                $inc = AssetMgmt::select('category_peripheral')
                            ->where('asset_owner', $request->assetOwner)
                            ->where('category_peripheral', $category)
                            ->get();
                $increment = count($inc);
                $nomor = $increment+1;
                if($nomor < 10){
                    $nomor = '00000' . $nomor;
                } elseif ($nomor < 100) {
                    $nomor = '0000' . $nomor;
                } elseif ($nomor < 1000) {
                    $nomor = '000' . $nomor;
                } elseif ($nomor < 10000) {
                    $nomor = '00' . $nomor;
                } else {
                    $nomor = '0' . $nomor;
                }
                $cat = $category;
            } else {
                $category = $update->category;
                $inc = AssetMgmt::select('category')
                            ->where('asset_owner', $request->assetOwner)
                            ->where('category', $category)
                            ->get();
                $increment = count($inc);
                $nomor = $increment+1;
                if($nomor < 10){
                    $nomor = '00000' . $nomor;
                } elseif ($nomor < 100) {
                    $nomor = '0000' . $nomor;
                } elseif ($nomor < 1000) {
                    $nomor = '000' . $nomor;
                } elseif ($nomor < 10000) {
                    $nomor = '00' . $nomor;
                } else {
                    $nomor = '0' . $nomor;
                }


                if ($category == 'Network') {
                    $cat = 'NTW';
                } elseif($category == 'Security'){
                    $cat = 'SCR';
                } elseif($category == 'Computer'){
                    $cat = 'COM';
                } else {
                    $cat = strtoupper(substr($category, 0, 3));
                }

            }

            $id =  $request->assetOwner . '-' . $cat . '-' . $dateAsset . '-' . $nomor;

            $updateAsset = AssetMgmt::where('id',$request->id_asset)->first();
            $updateAsset->id_asset = $id;
            $updateAsset->save();


            $storeLog = new AssetMgmtLog();
            $storeLog->id_asset = $request->id_asset;
            $storeLog->operator = Auth::User()->name;
            $storeLog->date_add = Carbon::now()->toDateTimeString();
            $storeLog->activity = 'Update Asset with Asset Owner ' .$update->asset_owner. ' to ' . $request->assetOwner;
            $storeLog->save();
        }
        $update->asset_owner = $request->assetOwner;

        $oldStatus = $update->status;
        $update->status = $request->status;
        $update->notes = $request->notes;
        $update->save();

        if ($oldStatus !== $request->status) {
            $storeLog = new AssetMgmtLog();
            $storeLog->id_asset  = $request->id_asset;
            $storeLog->operator  = Auth::user()->name;
            $storeLog->date_add  = Carbon::now()->toDateTimeString();
            $storeLog->activity  = "Update Asset with status {$oldStatus} to {$request->status}";
            $storeLog->save();
        }

        if ($oldStatus !== $request->status && $request->status === 'Available') {
            $getIdDetailAsset = AssetMgmtDetail::where('id_asset', $request->id_asset)
                ->orderBy('id', 'desc')
                ->first();
        
            $storeDetail = $getIdDetailAsset->replicate();
            $storeDetail->installed_date = null;
            $storeDetail->pic = null;
            $storeDetail->save();            

            if ($getPid === 'INTERNAL') {
                $pdfPath = $this->getPdfBASTPengembalian($request->id_asset,$getIdDetailAsset->id);
                $this->uploadPdfBASTPengembalian($request->id_asset,$pdfPath);
            }
        }

        // if ($update->status != $request->status) {  
        //     if ($request->status === 'Available') {
        //         $getIdDetailAsset = AssetMgmtDetail::where('id_asset',$request->id_asset)->orderby('id','desc')->first();
        //         $storeDetail = $getIdDetailAsset->replicate();
        //         $storeDetail->installed_date = null;
        //         $storeDetail->pic = null;
        //         $storeDetail->save();
        //     }
        //     $storeLog = new AssetMgmtLog();
        //     $storeLog->id_asset = $request->id_asset;
        //     $storeLog->operator = Auth::User()->name;
        //     $storeLog->date_add = Carbon::now()->toDateTimeString();
        //     $storeLog->activity = 'Update Asset with status ' .$update->status. ' to ' . $request->status;
        //     $storeLog->save();
        // }
        // $update->status = $request->status;
        // $update->save;

        // if ($request->status === 'Available') {
        //     if ($getPid === 'INTERNAL') {
        //         $getIdDetailAsset = AssetMgmtDetail::where('id_asset',$request->id_asset)->orderby('id','desc')->first();

        //         $pdfPath = $this->getPdfBASTPengembalian($request->id_asset,$getIdDetailAsset->id);

        //         $this->uploadPdfBASTPengembalian($request->id_asset,$pdfPath);
        //     }        
        // }


        if ($update->vendor != $request->vendor) {
            $storeLog = new AssetMgmtLog();
            $storeLog->id_asset = $request->id_asset;
            $storeLog->operator = Auth::User()->name;
            $storeLog->date_add = Carbon::now()->toDateTimeString();
            $storeLog->activity = 'Update Asset with vendor ' .$update->vendor. ' to ' . $request->vendor;
            $storeLog->save();
        }
        $update->vendor = $request->vendor;


        if ($update->type_device != $request->typeDevice) {
            $storeLog = new AssetMgmtLog();
            $storeLog->id_asset = $request->id_asset;
            $storeLog->operator = Auth::User()->name;
            $storeLog->date_add = Carbon::now()->toDateTimeString();
            $storeLog->activity = 'Update Asset with Type Device ' .$update->type_device. ' to ' . $request->typeDevice;
            $storeLog->save();
        }
        $update->type_device = $request->typeDevice;

        if ($update->serial_number != $request->serialNumber) {
            $storeLog = new AssetMgmtLog();
            $storeLog->id_asset = $request->id_asset;
            $storeLog->operator = Auth::User()->name;
            $storeLog->date_add = Carbon::now()->toDateTimeString();
            $storeLog->activity = 'Update Asset with Serial Number ' .$update->serial_number. ' to ' . $request->serialNumber;
            $storeLog->save();
        }
        $update->serial_number = $request->serialNumber;

        if ($update->spesifikasi != $request->spesifikasi) {
            $lines = explode("\n", $request->spesifikasi);
            $osVersionValue = '';

            foreach ($lines as $line) {
                $line = trim($line);
                if (stripos($line, 'OS Version') === 0) {
                    $osVersionValue = trim(substr($line, strpos($line, ':') + 1));
                    break;
                }
            }

            if ($osVersionValue !== '') {
                $latestDetail = AssetMgmtDetail::where('id_asset', $request->id_asset)->orderby('id','desc')->first();
                $latestDetail->operating_system = $osVersionValue;
                $latestDetail->save();
            }

            $storeLog = new AssetMgmtLog();
            $storeLog->id_asset = $request->id_asset;
            $storeLog->operator = Auth::User()->name;
            $storeLog->date_add = Carbon::now()->toDateTimeString();
            $storeLog->activity = 'Update Asset with Spesifikasi ' .$update->spesifikasi. ' to ' . $request->spesifikasi;
            $storeLog->save();
        }
        $update->spesifikasi = $request->spesifikasi;

        if ($update->rma != $request->rma) {
            $storeLog = new AssetMgmtLog();
            $storeLog->id_asset = $request->id_asset;
            $storeLog->operator = Auth::User()->name;
            $storeLog->date_add = Carbon::now()->toDateTimeString();
            $storeLog->activity = 'Update Asset with RMA ' .$update->rma. ' to ' . $request->rma;
            $storeLog->save();
        }
        $update->rma = $request->rma;

        if ($update->notes != $request->notes) {
            $storeLog = new AssetMgmtLog();
            $storeLog->id_asset = $request->id_asset;
            $storeLog->operator = Auth::User()->name;
            $storeLog->date_add = Carbon::now()->toDateTimeString();
            $storeLog->activity = 'Update Asset with Notes ' .$update->notes. ' to ' . $request->notes;
            $storeLog->save();
        }
        $update->notes = $request->notes;

        if ($update->tanggal_pembelian != $request->tanggalBeli) {
            $storeLog = new AssetMgmtLog();
            $storeLog->id_asset = $request->id_asset;
            $storeLog->operator = Auth::User()->name;
            $storeLog->date_add = Carbon::now()->toDateTimeString();
            $storeLog->activity = 'Update Asset with Tanggal Pembelian ' .$update->tanggal_pembelian. ' to ' . $request->tanggalBeli;
            $storeLog->save();
        }
        $update->tanggal_pembelian = $request->tanggalBeli;

        if ($update->pr != $request->inputPr) {
            $getIdDetailAsset = AssetMgmtDetail::where('id_asset',$request->id_asset)->orderby('id','desc')->first();
                $storeDetail = $getIdDetailAsset->replicate();
                $storeDetail->pr = $request->inputPr;
                $storeDetail->save();

            $storeLog = new AssetMgmtLog();
            $storeLog->id_asset = $request->id_asset;
            $storeLog->operator = Auth::User()->name;
            $storeLog->date_add = Carbon::now()->toDateTimeString();
            $storeLog->activity = 'Update Asset with Number Purchase Request ' .$update->pr. ' to ' . $request->inputPr;
            $storeLog->save();
        }
        $update->pr = $request->inputPr;

        if ($update->nilai_buku != str_replace('.', '', $request['nilaiBuku'])) {
            $storeLog = new AssetMgmtLog();
            $storeLog->id_asset = $request->id_asset;
            $storeLog->operator = Auth::User()->name;
            $storeLog->date_add = Carbon::now()->toDateTimeString();
            $storeLog->activity = 'Update Asset with Nilai Buku ' .$update->nilai_buku. ' to ' . $request->nilaiBuku;
            $storeLog->save();
        }
        $update->nilai_buku = str_replace('.', '', $request['nilaiBuku']);

        if ($update->harga_beli != str_replace('.', '', $request['hargaBeli'])) {
            $storeLog = new AssetMgmtLog();
            $storeLog->id_asset = $request->id_asset;
            $storeLog->operator = Auth::User()->name;
            $storeLog->date_add = Carbon::now()->toDateTimeString();
            $storeLog->activity = 'Update Asset with Harga Beli ' .$update->harga_beli. ' to ' . $request->hargaBeli;
            $storeLog->save();
        }
        $update->harga_beli = str_replace('.', '', $request['hargaBeli']);

        if (isset($request->reason)) {
            if ($update->reason_status != $request->reason) {
                $storeLog = new AssetMgmtLog();
                $storeLog->id_asset = $request->id_asset;
                $storeLog->operator = Auth::User()->name;
                $storeLog->date_add = Carbon::now()->toDateTimeString();
                $storeLog->activity = 'Update Asset with reason status ' .$update->reason_status. ' to ' . $request->reason;
                $storeLog->save();
            }
            $update->reason_status = $request->reason;
        }

        // if (isset($request->inputPr)) {
        //     if ($update->pr != $request->inputPr) {
        //         $storeLog = new AssetMgmtLog();
        //         $storeLog->id_asset = $request->id_asset;
        //         $storeLog->operator = Auth::User()->name;
        //         $storeLog->date_add = Carbon::now()->toDateTimeString();
        //         $storeLog->activity = 'Update Asset with PR ' .$update->pr. ' to ' . $request->inputPr;
        //         $storeLog->save();
        //     }
        //     $update->pr = $request->inputPr;
        // }

        $update->save(); 

        if ($request->status != 'Installed') {
            $delete = AssetMgmtAssign::where('id_asset_peripheral',$request->id_asset)->delete();
        }

        if (isset($request->engineer['name'])) {
            $data = json_decode($request->engineer,true);

            $delete_engineer_assign = AssetMgmtAssignEngineer::where('id_asset',$request->id_asset)->delete();

            foreach ($data as $value) {
                $store = new AssetMgmtAssignEngineer();
                $store->id_asset     = $request->id_asset;
                $store->engineer_atm = $value['name'];
                $store->role         = $value['roles'];
                $store->date_add     = Carbon::now()->toDateTimeString();
                $store->save();

                $storeLog = new AssetMgmtLog();
                $storeLog->id_asset = $request->id_asset;
                $storeLog->operator = Auth::User()->name;
                $storeLog->date_add = Carbon::now()->toDateTimeString();
                $storeLog->activity = 'Assign Engineer ' .$value['name'] . ' as ' . $value['roles'] . ' Engineer to asset ' . AssetMgmt::where('id',$request->id_asset)->first()->id_asset;
                $storeLog->save();
            } 
        }

        // $storeLog = new AssetMgmtLog();
        // $storeLog->id_asset = $request->id_asset;
        // $storeLog->operator = Auth::User()->name;
        // $storeLog->date_add = Carbon::now()->toDateTimeString();
        // $storeLog->activity = 'Update Asset ' .$request->id_asset. ' with Category ' . $update->category;
        // $storeLog->save();
    }

    public function updateDetailAsset(Request $request)
    {
        $update = AssetMgmtDetail::where('id_asset',$request->id_asset)
                ->orderby('id','desc')
                ->first();
        $storeDetail = new AssetMgmtDetail();
        $storeDetail->id_asset = $request->id_asset;
        $updateAssetMgmt = AssetMgmt::where('id',$request->id_asset)->orderby('id','desc')->first();

        if (isset($request->inputPic)) {
            $updateAssetMgmt->status = 'Installed';
            $updateAssetMgmt->update();
        }else{
            if ($updateAssetMgmt->status != 'Rent' || $updateAssetMgmt != 'Unavailable') {
                $updateAssetMgmt->status = 'Available';
                $updateAssetMgmt->update();
            }
        }

        if ($update->id_device_customer != $request->idDeviceCustomer) {
            $storeLog = new AssetMgmtLog();
            $storeLog->id_asset = $request->id_asset;
            $storeLog->operator = Auth::User()->name;
            $storeLog->date_add = Carbon::now()->toDateTimeString();
            $storeLog->activity = 'Update Detail Asset with ID Device Customer ' .$update->id_device_customer. ' to ' . $request->idDeviceCustomer;
            $storeLog->save();
        }
        $storeDetail->id_device_customer = $request->idDeviceCustomer;

        if ($update->accessoris != $request->accessoris) {
            $storeLog = new AssetMgmtLog();
            $storeLog->id_asset = $request->id_asset;
            $storeLog->operator = Auth::User()->name;
            $storeLog->date_add = Carbon::now()->toDateTimeString();
            $storeLog->activity = 'Update accessoris ' .$update->accessoris. ' to ' . $request->accessoris;
            $storeLog->save();
        }
        $storeDetail->accessoris = $request->accessoris;

        if ($update->pid != $request->pid) {
            $storeLog = new AssetMgmtLog();
            $storeLog->id_asset = $request->id_asset;
            $storeLog->operator = Auth::User()->name;
            $storeLog->date_add = Carbon::now()->toDateTimeString();
            $storeLog->activity = 'Update Detail Asset with Project ID ' .$update->pid. ' to ' . $request->pid;
            $storeLog->save();
        }
        $storeDetail->pid = $request->pid;

        if ($update->kota != $request->kota) {
            $storeLog = new AssetMgmtLog();
            $storeLog->id_asset = $request->id_asset;
            $storeLog->operator = Auth::User()->name;
            $storeLog->date_add = Carbon::now()->toDateTimeString();
            $storeLog->activity = 'Update Detail Asset with Kota ' .$update->kota. ' to ' . $request->kota;
            $storeLog->save();
        }
        $storeDetail->kota = $request->kota;

        if ($update->alamat_lokasi != $request->alamatLokasi) {
            $storeLog = new AssetMgmtLog();
            $storeLog->id_asset = $request->id_asset;
            $storeLog->operator = Auth::User()->name;
            $storeLog->date_add = Carbon::now()->toDateTimeString();
            $storeLog->activity = 'Update Detail Asset with Alamat Lokasi ' .$update->alamat_lokasi. ' to ' . $request->alamatLokasi;
            $storeLog->save();
        }
        $storeDetail->alamat_lokasi = $request->alamatLokasi;

        if ($update->detail_lokasi != $request->detailLokasi) {
            $storeLog = new AssetMgmtLog();
            $storeLog->id_asset = $request->id_asset;
            $storeLog->operator = Auth::User()->name;
            $storeLog->date_add = Carbon::now()->toDateTimeString();
            $storeLog->activity = 'Update Detail Asset with Detail Lokasi ' .$update->detail_lokasi. ' to ' . $request->detailLokasi;
            $storeLog->save();
        }
        $storeDetail->detail_lokasi = $request->detailLokasi;

        if ($update->service_point != $request->servicePoint) {
            $storeLog = new AssetMgmtLog();
            $storeLog->id_asset = $request->id_asset;
            $storeLog->operator = Auth::User()->name;
            $storeLog->date_add = Carbon::now()->toDateTimeString();
            $storeLog->activity = 'Update Detail Asset with Service Point ' .$update->service_point. ' to ' . $request->servicePoint;
            $storeLog->save();
        }
        $storeDetail->service_point = $request->servicePoint;

        $storeDetail->latitude = $request->latitude;
        $storeDetail->longitude = $request->longitude;

        if ($update->ip_address != $request->ipAddress) {
            $storeLog = new AssetMgmtLog();
            $storeLog->id_asset = $request->id_asset;
            $storeLog->operator = Auth::User()->name;
            $storeLog->date_add = Carbon::now()->toDateTimeString();
            $storeLog->activity = 'Update Detail Asset with IP Address ' .$update->ip_address. ' to ' . $request->ipAddress;
            $storeLog->save();
        }
        $storeDetail->ip_address = $request->ipAddress;

        if ($update->server != $request->ipServer) {
            $storeLog = new AssetMgmtLog();
            $storeLog->id_asset = $request->id_asset;
            $storeLog->operator = Auth::User()->name;
            $storeLog->date_add = Carbon::now()->toDateTimeString();
            $storeLog->activity = 'Update Detail Asset with Server ' .$update->server. ' to ' . $request->ipServer;
            $storeLog->save();
        }
        $storeDetail->server = $request->ipServer;

        if ($update->port != $request->port) {
            $storeLog = new AssetMgmtLog();
            $storeLog->id_asset = $request->id_asset;
            $storeLog->operator = Auth::User()->name;
            $storeLog->date_add = Carbon::now()->toDateTimeString();
            $storeLog->activity = 'Update Detail Asset with Port ' .$update->port. ' to ' . $request->port;
            $storeLog->save();
        }
        $storeDetail->port = $request->port;

        if ($update->status_cust != $request->statusCust) {
            $storeLog = new AssetMgmtLog();
            $storeLog->id_asset = $request->id_asset;
            $storeLog->operator = Auth::User()->name;
            $storeLog->date_add = Carbon::now()->toDateTimeString();
            $storeLog->activity = 'Update Detail Asset with Status Customer ' .$update->status_cust. ' to ' . $request->statusCust;
            $storeLog->save();
        }
        $storeDetail->status_cust = $request->statusCust;

        if ($update->second_level_support != $request->secondLevelSupport) {
            $storeLog = new AssetMgmtLog();
            $storeLog->id_asset = $request->id_asset;
            $storeLog->operator = Auth::User()->name;
            $storeLog->date_add = Carbon::now()->toDateTimeString();
            $storeLog->activity = 'Update Detail Asset with Second Level Support ' .$update->second_level_support. ' to ' . $request->secondLevelSupport;
            $storeLog->save();
        }
        $storeDetail->second_level_support = $request->secondLevelSupport;

        //update Operating System disini ngubah di database 
        if ($update->operating_system != $request->operatingSystem) {
            $updateAssetMgmt = AssetMgmt::where('id',$request->id_asset)->first();
            //$storeDetail->operating_system = $request->operatingSystem;

            $lines = explode("\n", $updateAssetMgmt->spesifikasi);
            
            foreach($lines as $index=>$line){
                $line = trim($line);
                if (stripos($line, 'OS Version') === 0) {
                    $lines[$index] = 'OS Version : ' . $request->operatingSystem;
                    break;
                }
            }
            $updateAssetMgmt->spesifikasi = implode("\n", $lines);
            $updateAssetMgmt->save();

            $storeLog = new AssetMgmtLog();
            $storeLog->id_asset = $request->id_asset;
            $storeLog->operator = Auth::User()->name;
            $storeLog->date_add = Carbon::now()->toDateTimeString();
            $storeLog->activity = 'Update Detail Asset with Operating System ' .$update->operating_system. ' to ' . $request->operatingSystem;
            $storeLog->save();
        }
        $storeDetail->operating_system = $request->operatingSystem;    
        $storeDetail->save();
        

        if ($update->version_os != $request->versionOs) {
            $storeLog = new AssetMgmtLog();
            $storeLog->id_asset = $request->id_asset;
            $storeLog->operator = Auth::User()->name;
            $storeLog->date_add = Carbon::now()->toDateTimeString();
            $storeLog->activity = 'Update Detail Asset with Version OS ' .$update->version_os. ' to ' . $request->versionOs;
            $storeLog->save();
        }
        $storeDetail->version_os = $request->versionOs;

        if ($update->installed_date != $request->installedDate) {
            $storeLog = new AssetMgmtLog();
            $storeLog->id_asset = $request->id_asset;
            $storeLog->operator = Auth::User()->name;
            $storeLog->date_add = Carbon::now()->toDateTimeString();
            $storeLog->activity = 'Update Detail Asset with Installed Date ' .$update->installed_date. ' to ' . $request->installedDate;
            $storeLog->save();
        }
        $storeDetail->installed_date = $request->installedDate;

        if ($update->license != $request->license) {
            $storeLog = new AssetMgmtLog();
            $storeLog->id_asset = $request->id_asset;
            $storeLog->operator = Auth::User()->name;
            $storeLog->date_add = Carbon::now()->toDateTimeString();
            $storeLog->activity = 'Update Detail Asset with License ' .$update->license. ' to ' . $request->license;
            $storeLog->save();
        }
        $storeDetail->license = $request->license;

        if ($update->license_start_date != $request->licenseStartDate) {
            $storeLog = new AssetMgmtLog();
            $storeLog->id_asset = $request->id_asset;
            $storeLog->operator = Auth::User()->name;
            $storeLog->date_add = Carbon::now()->toDateTimeString();
            $storeLog->activity = 'Update Detail Asset with License Start Date ' .$update->license_start_date. ' to ' . $request->licenseStartDate;
            $storeLog->save();
        }
        $storeDetail->license_start_date = $request->licenseStartDate;

        if ($update->license_end_date != $request->licenseEndDate) {
            $storeLog = new AssetMgmtLog();
            $storeLog->id_asset = $request->id_asset;
            $storeLog->operator = Auth::User()->name;
            $storeLog->date_add = Carbon::now()->toDateTimeString();
            $storeLog->activity = 'Update Detail Asset with License End Date ' .$update->license_end_date. ' to ' . $request->licenseEndDate;
            $storeLog->save();
        }
        $storeDetail->license_end_date = $request->licenseEndDate;

        if ($update->maintenance_start != $request->maintenanceStart) {
            $storeLog = new AssetMgmtLog();
            $storeLog->id_asset = $request->id_asset;
            $storeLog->operator = Auth::User()->name;
            $storeLog->date_add = Carbon::now()->toDateTimeString();
            $storeLog->activity = 'Update Detail Asset with Maintenance Start ' .$update->maintenance_start. ' to ' . $request->maintenanceStart;
            $storeLog->save();
        }
        $storeDetail->maintenance_start = $request->maintenanceStart;

        if ($update->maintenance_end != $request->maintenanceEnd) {
            $storeLog = new AssetMgmtLog();
            $storeLog->id_asset = $request->id_asset;
            $storeLog->operator = Auth::User()->name;
            $storeLog->date_add = Carbon::now()->toDateTimeString();
            $storeLog->activity = 'Update Detail Asset with Maintenance End ' .$update->maintenance_end. ' to ' . $request->maintenanceEnd;
            $storeLog->save();
        }
        $storeDetail->maintenance_end = $request->maintenanceEnd;


        $updatePic = ($update->pic === null) ? "null" : $update->pic;
        $requestPic = ($request->inputPic === null) ? "null" : $request->inputPic;
        
        if ($updatePic != $requestPic && $update->pid == 'INTERNAL') {
            $checkBASTPengembalian = DB::table('tb_asset_management_dokumen')
                                    ->where('id_detail_asset',$update->id_asset)
                                    ->where('document_name', 'Berita Acara Pengembalian')
                                    ->orderBy('id','desc')->first();

            if ($checkBASTPengembalian == null && $update->pic !== "null") {
                // $storeDetailAvailable = $update->replicate();
                // $storeDetailAvailable->installed_date = null;
                // $storeDetailAvailable->pic = null;
                // $storeDetailAvailable->save();   

                $pdfPathPengembalian = $this->getPdfBASTPengembalian($update->id_asset, $update->id);
                $this->uploadPdfBASTPengembalian($update->id_asset, $pdfPathPengembalian);
            }


            $pic_old = User::select('users.name')->join('role_user','role_user.user_id','=','users.nik')
                            ->join('roles','roles.id','=','role_user.role_id')->where('users.nik',$update->pic);

            $pic_new = User::select('users.name')->join('role_user','role_user.user_id','=','users.nik')
                                ->join('roles','roles.id','=','role_user.role_id')->where('users.nik',$request->inputPic);


            $pic_old_data = $pic_old->first(); 
            $pic_new_data = $pic_new->first();

            $storeLog = new AssetMgmtLog();
            $storeLog->id_asset = $request->id_asset;
            $storeLog->operator = Auth::user()->name;
            $storeLog->date_add = Carbon::now()->toDateTimeString();

            if ($pic_old_data && $pic_new_data) {
                $storeLog->activity = 'Update PIC Asset from ' . $pic_old_data->name . ' to ' . $pic_new_data->name;
            } else if ($pic_new_data) {
                $storeLog->activity = 'Update PIC Asset to ' . $pic_new_data->name;
            } 
            $storeLog->save();

            // if($pic_old->first() !== null){
            //     $storeLog = new AssetMgmtLog();
            //     $storeLog->id_asset = $request->id_asset;
            //     $storeLog->operator = Auth::User()->name;
            //     $storeLog->date_add = Carbon::now()->toDateTimeString();
            //     $storeLog->activity = 'Update PIC Asset from ' .$pic_old->first()->name. ' to ' . $pic_new->first()->name;
            //     $storeLog->save();
            // }else{
            //     $storeLog = new AssetMgmtLog();
            //     $storeLog->id_asset = $request->id_asset;
            //     $storeLog->operator = Auth::User()->name;
            //     $storeLog->date_add = Carbon::now()->toDateTimeString();
            //     $storeLog->activity = 'Update PIC Asset to ' . $pic_new->first()->name;
            //     $storeLog->save();
            // }

            $storeDetail->pic = $request->inputPic; 
        }else{   
            $storeDetail->pic = $request->inputPic;
        }

        if ($update->client != $request->client) {
            $storeLog = new AssetMgmtLog();
            $storeLog->id_asset = $request->id_asset;
            $storeLog->operator = Auth::User()->name;
            $storeLog->date_add = Carbon::now()->toDateTimeString();
            $storeLog->activity = 'Update Detail Asset with Client ' .$update->client. ' to ' . $request->client;
            $storeLog->save();
        }

        $updateAssetMgmt = AssetMgmt::where('id',$request->id_asset)->first();

        if (isset($request->inputPic)) {
            $updateAssetMgmt->status = 'Installed';
            $updateAssetMgmt->update();
        }else{
            if ($updateAssetMgmt->status != 'Rent' || $updateAssetMgmt != 'Unavailable') {
                $updateAssetMgmt->status = 'Available';
                $updateAssetMgmt->update();
            }
        }

        $storeDetail->client = $request->client;
        $storeDetail->pr     = $update->pr;
        $storeDetail->date_add = Carbon::now()->toDateTimeString();
        $storeDetail->related_id_asset = $update->related_id_asset;

        $id = AssetMgmt::where('id',$request->id_asset)->first()->id_asset;

        if (isset($request->inputDoc)) {
            if ($request->inputDoc != '' && $request->inputDoc != "undefined") {
                $get_parent_drive = AssetMgmt::where('id', $request->id_asset)->first();

                $file                   = $request->file('inputDoc');
                $fileName               = 'Bukti Asset '.$id.'.pdf';
                $filePath               = $file->getRealPath();
                $extension              = $file->getClientOriginalExtension();

                if ($get_parent_drive->parent_id_drive == null) {
                    $parentID = $this->googleDriveMakeFolder($request->id_asset);
                } else {
                    $parentID = [];
                    $parent_id = explode('"', $get_parent_drive->parent_id_drive)[1];
                    array_push($parentID,$parent_id);
                }

                $storeDetail->document_location     = "Asset/Bukti Asset " . $id;
                $storeDetail->document_name         = 'Bukti Asset '.$id;
                $storeDetail->link_drive            = $this->googleDriveUploadCustom($fileName,$filePath,$parentID);
            }
        }

        $storeDetail->save();

        if ($updatePic != $requestPic) {
            $pdfPathBaru = $this->getPdfBASTAsset($request->id_asset,$storeDetail->id);
            $this->uploadPdfBAST($request->id_asset,$pdfPathBaru);

            $to = User::select('email','name')
                    ->join('role_user','role_user.user_id','=','users.nik')
                    ->where('nik',$request->inputPic)->first();

            $data = [
                [   
                    'name'              => $to->name,
                    'id_asset'          => $storeDetail->id_asset, 
                    'category'          => $storeDetail->category,
                    'type_device'       => $storeDetail->vendor . " - " . $storeDetail->type_device . " - " . $storeDetail->serial_number,
                    'spesifikasi'       => $storeDetail->spesifikasi,
                    'link_drive'        => AssetMgmtDocument::where('id_detail_asset',$storeDetail->id)->first()->link_drive,
                ]
            ];

            Mail::to($to->email)->send(new MailGenerateBAST($data,'[SIMS-APP] Generate BAST')); 
        } else {
            $getIdDetailAsset = AssetMgmtDocument::where('id_detail_asset',$update->id)->first(); 
            if ($getIdDetailAsset) {
                $storeDokumen = $getIdDetailAsset->replicate();
                $storeDokumen->id_detail_asset = $storeDetail->id;
                $storeDokumen->save();
            } 

        }

        // BAST that is undefined
        // if (isset($request->inputDocBA)) {
        //     if ($request->inputDocBA != '' && $request->inputDocBA != "undefined") {
        //         $get_parent_drive       = AssetMgmt::where('id', $request->id_asset)->first();
        //         $file                   = $request->file('inputDocBA');
        //         $fileName               = 'Berita Acara '.$id.'.pdf';
        //         $filePath               = $file->getRealPath();
        //         $extension              = $file->getClientOriginalExtension();

        //         $storeDoc = new AssetMgmtDocument();

        //         if ($get_parent_drive->parent_id_drive == null) {
        //             $parentID = $this->googleDriveMakeFolder($request->id_asset);
        //         } else {
        //             $parentID = [];
        //             $parent_id = explode('"', $get_parent_drive->parent_id_drive)[1];
        //             array_push($parentID,$parent_id);
        //         }

        //         $storeDoc->id_detail_asset      = $storeDetail->id;
        //         $storeDoc->document_name        = 'Berita Acara '.$id;
        //         $storeDoc->document_location    = "Asset/BAST " . $assetMgmt->id_asset;
        //         $storeDoc->link_drive           = $this->googleDriveUploadCustom($fileName,$filePath,$parentID);
        //         $storeDoc->save();
        //     }
        // }
    }

    public function getProvince(Request $request)
    {
        $client = new Client();
        $getData = $client->get('https://open-api.my.id/api/wilayah/provinces');
        // $getData = $client->get('https://api.binderbyte.com/wilayah/provinsi?api_key='.env('API_KEY_PROVINCE'));
        $json = (string)$getData->getBody();
        $getDataRegencies = json_decode($json, true);
        // return $getDataRegencies;

        $getDataRegenciesDetail = collect();

        foreach ($getDataRegencies as $key => $value) {
            // return $value;
            $data = (string)$client->get('https://open-api.my.id/api/wilayah/regencies/'.$value['id'])->getBody();
            // $data = (string)$client->get('https://api.binderbyte.com/wilayah/kabupaten?api_key='.env('API_KEY_PROVINCE').'&id_provinsi='.$value['id'])->getBody();
            $dataJson = json_decode($data,true);
            foreach ($dataJson as $key => $value) {
                // return $value;
                $getDataRegenciesDetail->push(["id"=>$value['name'],"text"=>$value['name']]);
            }
        }

        // $getDataRegenciesDetail = $client->get('https://alamat.thecloudalert.com/api/kabkota/get/');
        // $getDataRegenciesDetail->getBody();

        $getDataRegenciesDetail->where('id','like','%'.request('q').'%')->all();
        return response()->json($getDataRegenciesDetail);

        // return $getDataRegenciesDetail;
    }

    public function getDetailAsset(Request $request)
    {
        $updateAsset = AssetMgmt::where('id',$request->id_asset)->first();
        if ($updateAsset->tanggal_pembelian) {   

            $purchaseDate = new \Carbon\Carbon($updateAsset->tanggal_pembelian);
            $dayDifference = \Carbon\Carbon::now()->diffInDays($purchaseDate);

            $depreciationPercentage = 0;
            if ($dayDifference > 1460) {
                $depreciationPercentage = 100;
            } elseif ($dayDifference > 1095) {
                $depreciationPercentage = 75;
            } elseif ($dayDifference > 730) {
                $depreciationPercentage = 50;
            } elseif ($dayDifference > 365) {
                $depreciationPercentage = 25;
            }

            if ($depreciationPercentage > 0) {
                $computedNilaiBuku = ceil(floatval($updateAsset->harga_beli) * (1 - $depreciationPercentage / 100));
                if ($updateAsset->nilai_buku != $computedNilaiBuku) {
                    $updateAsset->nilai_buku = $computedNilaiBuku;
                    $updateAsset->save();
                }
            } 
            else {
                if ($updateAsset->nilai_buku != $updateAsset->harga_beli) {
                    $updateAsset->nilai_buku = $updateAsset->harga_beli;
                    $updateAsset->save();
                }
            }
        }

        $getId = AssetMgmt::join('tb_asset_management_detail','tb_asset_management_detail.id_asset','tb_asset_management.id')->select('tb_asset_management_detail.id_asset','detail_lokasi','tb_asset_management_detail.id');
        // return $getId->get();
        $getLastId = DB::table($getId,'temp')->groupBy('id_asset')->selectRaw('MAX(`temp`.`id`) as `id_last_asset`');
        // return $getLastId->get();

        // return $getDokumen = DB::table($getLastId, 'temp2')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')->leftJoin('tb_asset_management_dokumen','tb_asset_management_dokumen.id_detail_asset','=','tb_asset_management_detail.id')->select('tb_asset_management_dokumen.link_drive as link_drive_BA',
        //             'tb_asset_management_dokumen.document_name as document_name_BA',
        //             'tb_asset_management_dokumen.document_location as document_location_BA')->where('id_detail',$getLastId->id_last_asset->first())->get();
        $getAll = DB::table($getLastId, 'temp2')
            ->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')
            ->leftJoin('tb_asset_management_dokumen','tb_asset_management_dokumen.id_detail_asset','=','tb_asset_management_detail.id')
            ->join('tb_asset_management', 'tb_asset_management.id', '=', 'tb_asset_management_detail.id_asset')
            ->leftJoin('tb_asset_management_assign_engineer','tb_asset_management.id','tb_asset_management_assign_engineer.id_asset')
            ->leftJoin('tb_asset_management_category','tb_asset_management.category','tb_asset_management_category.name')
            ->leftJoin('users','users.nik','=','tb_asset_management_detail.pic')
            ->leftJoin('role_user','role_user.user_id','=','users.nik')
            ->leftJoin('roles','roles.id','=','role_user.role_id')
            ->select('tb_asset_management.id_asset',
                    'id_device_customer',
                    'tb_asset_management_detail.accessoris',
                    'client',
                    'pid',
                    'kota',
                    'alamat_lokasi',
                    'detail_lokasi',
                    'ip_address',
                    'server',
                    'port',
                    'status_cust',
                    'second_level_support',
                    'operating_system',
                    'version_os',
                    'installed_date',
                    'license',
                    'license_end_date',
                    'license_start_date',
                    'maintenance_end',
                    'maintenance_start',
                    'notes',
                    'rma',
                    'spesifikasi',
                    'type_device',
                    'serial_number',
                    'vendor',
                    'tb_asset_management_category.id_category as category_code',
                    'category as category_text',
                    'category_peripheral',
                    'asset_owner',
                    'related_id_asset',
                    DB::raw("(CASE WHEN (category_peripheral = '-') THEN 'asset' WHEN (category_peripheral != '-') THEN 'peripheral' END) as type"),
                    'status',
                    DB::raw("TIMESTAMPDIFF(HOUR, concat(maintenance_start,' 00:00:00'), concat(maintenance_end,' 00:00:00')) AS slaPlanned"),
                    'service_point',
                    'latitude',
                    'longitude',
                    'tanggal_pembelian',
                    'nilai_buku',
                    'harga_beli',
                    'tb_asset_management.id as id',
                    'tb_asset_management_detail.id as id_detail_asset',
                    'reason_status',
                    'tb_asset_management_detail.link_drive as link_drive_asset',
                    'tb_asset_management_detail.document_name as document_name_asset',
                    'tb_asset_management_detail.document_location as document_location_asset',
                    'tb_asset_management_dokumen.link_drive as link_drive_BA',
                    'tb_asset_management_dokumen.document_name as document_name_BA',
                    'tb_asset_management_dokumen.document_location as document_location_BA',
                    'tb_asset_management_detail.pr as pr',
                    'users.nik as pic', 'id_device_customer',
                    DB::raw('CONCAT(users.name," - ",(CASE WHEN roles.mini_group IS NULL THEN roles.group ELSE roles.mini_group END)) AS text_name'))
            ->where('tb_asset_management_detail.id_asset',$request->id_asset)
            ->first();
        $getIdTicket = DB::table('ticketing__detail')->where('serial_device',$getAll->serial_number)->select('id_ticket')->whereBetween('reporting_time', [$getAll->maintenance_start . " 00:00:00", $getAll->maintenance_end . " 23:59:59"])->get()->pluck('id_ticket');

        $timeTrouble = DB::table('ticketing__activity')->join('ticketing__activity as t2','t2.id_ticket','ticketing__activity.id_ticket')->join('ticketing__detail','ticketing__detail.id_ticket','ticketing__activity.id_ticket')
            ->select(DB::raw("TIME_TO_SEC(TIMEDIFF(t2.date, ticketing__activity.date)) AS time_diff_seconds"),
                'ticketing__activity.id_ticket')
            ->where('ticketing__activity.activity', 'OPEN')->where('t2.activity', 'CLOSE')->whereIn('ticketing__activity.id_ticket',$getIdTicket)->get()->SUM('time_diff_seconds');

        $countTicket = DB::table('ticketing__detail')->where('serial_device',$getAll->serial_number)->whereBetween('reporting_time', [$getAll->maintenance_start . " 00:00:00", $getAll->maintenance_end . " 23:59:59"])->count();

        $getEngineer = DB::table('tb_asset_management_assign_engineer')->select('engineer_atm','role','id')->where('id_asset',$request->id_asset)->get()->groupby('role');

        if ($getAll) {
            $getAll->engineers = $getEngineer;
        }

        $getData = collect($getAll);

        if ($getAll->category_peripheral == '-' || $getAll->category_peripheral == null) {
            if ($getAll->category_code == 'COM' || $getAll->category_code == 'FNT' || $getAll->category_code == 'ELC' || $getAll->category_code == 'VHC') {
                $sla = 0;
            }else{
                $sla = (100 - ((($timeTrouble/3600)/$getAll->slaPlanned)*100));
            }   

            $getServicePoint = AssetMgmtServicePoint::where('service_point',$getAll->service_point)->first();

            if ($getServicePoint) {
                $latitudeTo = $getServicePoint->latitude;
                $longitudeTo = $getServicePoint->longitude;

                $latitudeFrom = $getAll->latitude;
                $longitudeFrom = $getAll->longitude;

                $distance = $this->haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo);
                $getData = $getData->put('countTicket',$countTicket)->put('slaUptime',number_format($sla, 2, '.', ''))->put('distance',$distance);
            }else{
                $getData = $getData->put('countTicket',$countTicket)->put('slaUptime',number_format($sla, 2, '.', ''));
            }           
        } else {
            $sla = 0;
            $getData = $getData->put('countTicket',$countTicket)->put('slaUptime',number_format($sla, 2, '.', ''));
        }

        
        $getData = collect($getAll);
        return $getData;
    }

    public function getLog(Request $request)
    {
        if ($request->year != '') {
            $year = $request->year;
        }else{
            $year = date('Y');
        }

        $data = AssetMgmtLog::whereIn(DB::raw('YEAR(date_add)'), $year)->orderBy('date_add','desc');

        return array("data"=>$data->get());
    }

    public function getLogById(Request $request)
    {
        // $getLog = DB::table('tb_asset_management_log')
        //     ->where('id_asset', $request->id_asset)->orderby('date_add','desc');
        // return $request->id_asset;
        $getId = AssetMgmtLog::select('id','operator','id_asset')->where('id_asset', $request->id_asset);
        $getLog = DB::table($getId,'temp')->groupBy('id_asset')->selectRaw('MAX(`temp`.`id`) as `id_log`')->selectRaw('id_asset');
        // return $getLog->get();

        // $peripheral = AssetMgmt::select('category_peripheral')->where('id',$request->id_asset)->first();
        if ($request->type == 'asset') {
            $data = DB::table('tb_asset_management_detail')->joinSub($getLog, 'temp', function($join) {
                $join->on('tb_asset_management_detail.id_asset', '=', 'temp.id_asset');
            })
            ->join('tb_asset_management_log','tb_asset_management_log.id','temp.id_log')
            ->select('tb_asset_management_detail.id','client','pid',
                DB::raw("CONCAT(`detail_lokasi`, ' - ', `alamat_lokasi`, ' - ', `kota`) AS `lokasi`"),
                DB::raw("CONCAT(`maintenance_start`, ' - ', `maintenance_end`) AS `periode`"),
                DB::raw("(CASE WHEN (related_id_asset is null) THEN '-' ELSE related_id_asset END) as related_id_asset"),
                'operator'
            )
            ->where('tb_asset_management_detail.id_asset',$request->id_asset)
            ->orderby('tb_asset_management_detail.id','desc')
            ->get();
        }else{
            $data = AssetMgmtDetail::joinSub($getLog, 'temp', function($join) {
                $join->on('tb_asset_management_detail.id_asset', '=', 'temp.id_asset');
            })
            ->join('users','users.nik','=','tb_asset_management_detail.pic')
            ->join('role_user','role_user.user_id','=','tb_asset_management_detail.pic')
            ->join('roles','roles.id','=','role_user.role_id')
            ->join('tb_asset_management_log','tb_asset_management_log.id','temp.id_log')
            ->select('tb_asset_management_detail.id','client','pid',
                DB::raw("CONCAT(`detail_lokasi`, ' - ', `alamat_lokasi`, ' - ', `kota`) AS `lokasi`"),
                DB::raw("CONCAT(`maintenance_start`, ' - ', `maintenance_end`) AS `periode`"),
                DB::raw("(CASE WHEN (related_id_asset is null) THEN '-' ELSE related_id_asset END) as related_id_asset"),
                'operator',
                DB::raw("CONCAT(`users`.`name`,' - ',`roles`.`name`) AS `pic_name`"), 
                DB::raw("CASE 
                    WHEN LEAD(tb_asset_management_detail.date_add) OVER (ORDER BY tb_asset_management_detail.id) IS NULL 
                    THEN CONCAT(DATE_FORMAT(tb_asset_management_detail.installed_date, '%Y-%m-%d'), ' until Now')
                    
                    ELSE CONCAT(DATE_FORMAT(tb_asset_management_detail.installed_date, '%Y-%m-%d'), ' until ', 
                                COALESCE(DATE_FORMAT(DATE_SUB(LEAD(tb_asset_management_detail.installed_date) OVER (ORDER BY tb_asset_management_detail.id), INTERVAL 1 DAY), '%Y-%m-%d'), ''))
                    END AS periode_asset_internal
                    ")
            )
            ->where('tb_asset_management_detail.id_asset',$request->id_asset)
            // ->where('document_name','!=',null)
            ->orderby('tb_asset_management_detail.id','desc')
            ->get()->filter(function ($item) {
                return $item->document_name !== null; // Filter where document_name is not null
            })
            ->values();

            // $data->transform(function ($item) {
            //     if (isset($item->document_name)) {
            //         unset($item->document_name);
            //     }
            //     return $item;
            // });
        }

        return array("data"=>$data);
    }

    public function getPeripheral(Request $request)
    {
        $data = AssetMgmtAssign::where('tb_asset_management_assign.id_asset_induk',$request->id_asset)->get();


        $collectData = collect();

        foreach ($data as $key => $value) {
            $getData = AssetMgmt::select('tb_asset_management.category_peripheral',DB::raw("CONCAT(`vendor`, ' - ', `category`, ' - ', `type_device`, ' - ', `serial_number`) AS `text`"),'id_asset','id')->where('id',$value->id_asset_peripheral)->first();
            // $collectData->push(['category_peripheral'=>$getData->category_peripheral,'text'=>$getData->text,'id_asset'=>$getData->id_asset,'id'=>$getData->id]);
            $collectData->push(['text'=>$getData->text,'id_asset'=>$getData->id_asset,'id'=>$getData->id]);

            // $collectData[] = $collectData;
        }

        return $collectData;
    }

    public function getAssetToAssign()
    {
        $getId = AssetMgmt::join('tb_asset_management_detail','tb_asset_management_detail.id_asset','tb_asset_management.id')->select('tb_asset_management_detail.id_asset','detail_lokasi','tb_asset_management_detail.id');
        $getLastId = DB::table($getId,'temp')->groupBy('id_asset')->selectRaw('MAX(`temp`.`id`) as `id_last_asset`');

        $getAll = DB::table($getLastId, 'temp2')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')->join('tb_asset_management', 'tb_asset_management.id', '=', 'tb_asset_management_detail.id_asset')->select(DB::raw('`tb_asset_management`.`id` AS `id`'),DB::raw("CONCAT(`tb_asset_management`.`id_asset`, ' - ', `alamat_lokasi`, ' - ', `serial_number`) AS `text`"))
        // ->where('category_peripheral','-')
        // ->where('status','Available')
        ->where('tb_asset_management.id','like','%'.request('q').'%')
        ->orWhere(DB::raw("CONCAT(`tb_asset_management`.`id_asset`, ' - ', `alamat_lokasi`, ' - ', `serial_number`)"),'like','%'.request('q').'%')->get();

        return response()->json($getAll);
        // return $getAll;
    }

    public function storeAssign(Request $request)
    {
        $store = new AssetMgmtAssign();
        $store->id_asset_induk = $request->id_asset_induk;
        $store->id_asset_peripheral = $request->id_asset;
        $store->date_add = Carbon::now()->toDateTimeString();
        $store->save();

        $update = AssetMgmt::where('id',$request->id_asset)->first();
        $update->status = 'Installed';
        $update->save();

        $updateInduk = AssetMgmt::where('id',$request->id_asset_induk)->first();
        $updateInduk->status = 'Installed';
        $updateInduk->save();

        $updateDetail = AssetMgmtDetail::where('id_asset',$request->id_asset_induk)->first();
        $updateDetail->related_id_asset = $request->id_asset;
        $updateDetail->save();        

        $storeDetail = new AssetMgmtDetail();
        $storeDetail->id_asset = $request->id_asset;
        $storeDetail->id_device_customer = $updateDetail->id_device_customer;
        $storeDetail->client = $updateDetail->client;
        $storeDetail->pid = $updateDetail->pid;
        $storeDetail->kota = $updateDetail->kota;
        $storeDetail->alamat_lokasi = $updateDetail->alamat_lokasi;
        $storeDetail->detail_lokasi = $updateDetail->detail_lokasi;
        $storeDetail->ip_address = $updateDetail->ip_address;
        $storeDetail->latitude = $updateDetail->latitude;
        $storeDetail->longitude = $updateDetail->longitude;
        $storeDetail->service_point = $updateDetail->servicePoint;
        $storeDetail->server = $updateDetail->server;
        $storeDetail->port = $updateDetail->port;
        $storeDetail->status_cust = $updateDetail->status_cust;
        $storeDetail->second_level_support = $updateDetail->second_level_support;
        $storeDetail->operating_system = $updateDetail->operating_system;
        $storeDetail->version_os = $updateDetail->version_os;
        $storeDetail->installed_date = $updateDetail->installed_date;
        $storeDetail->license = $updateDetail->license;
        $storeDetail->license_start_date = $updateDetail->license_start_date;
        $storeDetail->license_end_date = $updateDetail->license_end_date;
        $storeDetail->maintenance_start = $updateDetail->maintenance_start;
        $storeDetail->maintenance_end = $updateDetail->maintenance_end;
        $storeDetail->related_id_asset = $request->id_asset_induk;
        $storeDetail->date_add = Carbon::now()->toDateTimeString();
        $storeDetail->save();

        $id_asset = AssetMgmt::where('id',$request->id_asset)->first()->id_asset;
        $id_asset_induk = AssetMgmt::where('id',$request->id_asset_induk)->first()->id_asset;

        $storeLog = new AssetMgmtLog();
        $storeLog->id_asset = $request->id_asset;
        $storeLog->operator = Auth::User()->name;
        $storeLog->date_add = Carbon::now()->toDateTimeString();
        $storeLog->activity = 'Assign asset ' . $id_asset . ' to ' . $id_asset_induk;
        $storeLog->save();
    }

    public function deleteAssignedAsset(Request $request)
    {
        $delete = AssetMgmtAssign::where('id_asset_peripheral',$request->id_asset)->delete();
        $data = AssetMgmtDetail::where('id_asset',$request->id_asset)->orderby('id','desc')->first();

        $updateAssetDetail = AssetMgmtDetail::whereIn('id_asset',[$request->id_asset,$data->related_id_asset])->update(['related_id_asset' => NULL]);

        $updateStatus = AssetMgmt::whereIn('id',[$request->id_asset,$data->related_id_asset])->update(['status' => 'Available']);

        $storeLog = new AssetMgmtLog();
        $storeLog->id_asset = $request->id_asset;
        $storeLog->operator = Auth::User()->name;
        $storeLog->date_add = Carbon::now()->toDateTimeString();
        $storeLog->activity = 'Remove Asset ' . AssetMgmt::where('id',$request->id_asset)->first()->id_asset . ' from Asset ' . $data->related_id_asset;
        $storeLog->save();
    }

    public function getPidByClient(Request $request)
    {
        $code_name = DB::table('tb_contact')->where('customer_legal_name',$request->client)->first()->code;

        $getPid = DB::table('tb_asset_management_detail')
            ->select('pid as id','pid as text')
            ->where('pid','like','%'.$code_name.'%')
            ->groupby('pid')->get();

        return $getPid;

    }

    public function getPidForFilter(Request $request)
    {
        $nik = Auth::User()->nik;
        $cek_role = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role','group','mini_group')->where('user_id',$nik)->first();

        $getPidEoS = DB::table('ticketing__user')->where('nik',Auth::User()->nik)->pluck('pid');
        $getPidPm = DB::table('tb_pmo')->join('tb_pmo_assign','tb_pmo_assign.id_project','tb_pmo.id')->join('role_user','role_user.user_id','tb_pmo_assign.nik')->join('roles','roles.id','role_user.role_id')->where('nik',Auth::User()->nik)->where('name','!=','Asset Management')->pluck('project_id');

        $getPid = DB::table('tb_asset_management_detail')
            ->select('pid as id', 'pid as text')
            ->where('pid', 'like', '%' . request('q') . '%')
            ->orderByRaw("
                CASE 
                    WHEN pid = 'INTERNAL' THEN 1
                    ELSE 2
                END
            ")->orderBy('pid', 'ASC')
            ->groupBy('pid');

        if ($cek_role->mini_group == 'Supply Chain & IT Support' || $cek_role->name_role == 'VP Internal Chain Management' || $cek_role->name_role == 'Chief Operating Officer') {
            $getPid = $getPid;
        } else if ($cek_role->name_role == 'Delivery Project Coordinator') {
            $getPid = $getPid->whereIn('pid',$getPidEoS);
        } elseif ($cek_role->name_role == 'Delivery Project Manager') {
            $getPid = $getPid->whereIn('pid',$getPidPm);
        } else if ($cek_role->name_role == 'Synergy System & Services Manager' ) {
            $getPid = $getPid->where('pid','!=','INTERNAL');
        } 

        $result = $getPid->get();


        return response()->json($result);
    }

    public function getChartAssetOwner(Request $request)
    {
        if ($request->year != "") {
            $year = $request->year;
        }else{
            $year = date('Y');
        }

        $data = AssetMgmt::join('tb_asset_management_detail','tb_asset_management.id','=','tb_asset_management_detail.id_asset')
            ->whereIn(DB::raw('YEAR(tb_asset_management.created_at)'), $year);

        $desc = AssetMgmt::whereIn(DB::raw('YEAR(tb_asset_management.created_at)'), $year)->select('asset_owner');

        if (isset($request->assetOwner)) {
            $data = $data->where('tb_asset_management.asset_owner',$request->assetOwner);
        }

        if (isset($request->category)) {
            $data = $data->where('tb_asset_management.category',$request->category);
        }

        if (isset($request->client)) {
            $data = $data->where('tb_asset_management_detail.client',$request->client);
        }

        if (isset($request->pid)) {
            $data = $data->where('tb_asset_management_detail.pid',$request->pid);
        }

        $desc = $desc->whereNotNull('asset_owner')->groupBy('asset_owner')->get()->pluck('asset_owner');
        $data = $data->whereNotNull('asset_owner')->get();

        // return count($data);


        $length = $desc->count();
        $allData = [];

        if (count($data) == 0) {
            // $hasil2 = [0,0,0];
            $allData[] = [
                'label' => '-',
                'value' => 0,
                'countValue' => 0
            ];
        }else{
            $hasil = array_fill(0, $length, 0);
            $pie = 0;

            foreach ($desc as $key => $value2) {
                foreach ($data as $value) {
                    if ($value->asset_owner == $value2) {
                        $hasil[$key]++;
                        $pie++;
                    }
                }
            }

            $collection = collect($hasil);
            $isAllZeros = $collection->every(function ($value) {
                return $value === 0;
            });

            $hasil2 = array_fill(0, $length, 0);
            $countValue = array_fill(0, $length, 0);
            $sortedDesc = $desc->toArray();

            if (!$isAllZeros) {
                foreach ($hasil as $key => $value) {
                    $hasil2[$key] = round(($value / $pie) * 100, 2);
                    $countValue[$key] = $value;
                }

                $combined = [];
                foreach ($hasil2 as $key => $value) {
                    if ($desc[$key] != 'null') {
                        $combined[] = [
                            'label' => $desc[$key],
                            'value' => $value
                        ];
                    }
                }

                // usort($combined, function ($a, $b) {
                //     return $b['value'] <=> $a['value'];
                // });

                $hasil2 = array_column($combined, 'value');
                $sortedDesc = array_column($combined, 'label');
            }

            foreach ($sortedDesc as $key => $label) {
                $allData[] = [
                    'label' => $label,
                    'value' => $hasil2[$key],
                    'countValue' => $countValue[$key]
                ];
            }

            $allData = $allData;
        }

        // return collect(["allData"=>$allData,"limitData"=>$allData]);

        return $allData;
    }

    public function getChartCategory(Request $request)
    {
        if ($request->year != "") {
            $year = $request->year;
        }else{
            $year = date('Y');
        }

        $data = AssetMgmt::join('tb_asset_management_detail','tb_asset_management.id','=','tb_asset_management_detail.id_asset')
            ->whereIn(DB::raw('YEAR(created_at)'), $year);
        $desc = AssetMgmt::whereIn(DB::raw('YEAR(created_at)'), $year)->select('category')->where('category', '!=', null)->groupBy('category')->get()->pluck('category');
        $length = $desc->count();

        if (isset($request->assetOwner)) {
            $data = $data->where('tb_asset_management.asset_owner',$request->assetOwner);
        }

        if (isset($request->category)) {
            $data = $data->where('tb_asset_management.category',$request->category);
        }

        if (isset($request->client)) {
            $data = $data->where('tb_asset_management_detail.client',$request->client);
        }

        if (isset($request->pid)) {
            $data = $data->where('tb_asset_management_detail.pid',$request->pid);
        }

        $data = $data->whereNotNull('asset_owner')->get();

        $allData = [];

        if (count($data) == 0) {
            $allData[] = [
                'label' => '-',
                'value' => 0,
                'countValue' => 0
            ];
        }else{
            $hasil = array_fill(0, $length, 0);
            $pie = 0;

            foreach ($desc as $key => $value2) {
                foreach ($data as $value) {
                    if ($value->category == $value2) {
                        $hasil[$key]++;
                        $pie++;
                    }
                }
            }

            $collection = collect($hasil);
            $isAllZeros = $collection->every(function ($value) {
                return $value === 0;
            });

            $hasil2 = array_fill(0, $length, 0);
            $countValue = array_fill(0, $length, 0);
            $sortedDesc = $desc->toArray();

            if (!$isAllZeros) {
                foreach ($hasil as $key => $value) {
                    $hasil2[$key] = round(($value / $pie) * 100, 2);
                    $countValue[$key] = $value;
                }

                $combined = [];
                foreach ($hasil2 as $key => $value) {
                    $combined[] = [
                        'label' => $desc[$key],
                        'value' => $value
                    ];
                }

                // usort($combined, function ($a, $b) {
                //     return $b['value'] <=> $a['value'];
                // });

                $hasil2 = array_column($combined, 'value');
                $sortedDesc = array_column($combined, 'label');
            }

            foreach ($sortedDesc as $key => $label) {
                $allData[] = [
                    'label' => $label,
                    'value' => $hasil2[$key],
                    'countValue'=> $countValue[$key]
                ];
            }
        }

        // return collect(["allData"=>$allData,"limitData"=>$allData]);

        return $allData;
    }

    public function getChartVendor(Request $request)
    {
        if ($request->year != "") {
            $year = $request->year;
        }else{
            $year = date('Y');
        }

        $data = AssetMgmt::join('tb_asset_management_detail','tb_asset_management.id','=','tb_asset_management_detail.id_asset')
                ->whereIn(DB::raw('YEAR(created_at)'), $year);
                // ->whereRaw("YEAR(created_at) = ?", [$year]);
        $desc = AssetMgmt::whereIn(DB::raw('YEAR(created_at)'), $year)->select('vendor')->where('vendor', '!=', null)->groupBy('vendor')->get()->pluck('vendor');

        if (isset($request->assetOwner)) {
            $data = $data->where('tb_asset_management.asset_owner',$request->assetOwner);
        }

        if (isset($request->category)) {
            $data = $data->where('tb_asset_management.category',$request->category);
        }

        if (isset($request->client)) {
            $data = $data->where('tb_asset_management_detail.client',$request->client);
        }

        if (isset($request->pid)) {
            $data = $data->where('tb_asset_management_detail.pid',$request->pid);
        }

        $data = $data->whereNotNull('asset_owner')->get();

        $length = $desc->count();

        $hasilChart = collect();

        $allData = [];

        if (count($data) == 0) {
            $allData[] = [
                'label' => '-',
                'value' => 0,
                'countValue' => 0
            ];
        } else {
            $hasil = array_fill(0, $length, 0);
            $pie = 0;

            foreach ($desc as $key => $value2) {
                foreach ($data as $value) {
                    if ($value->vendor == $value2) {
                        $hasil[$key]++;
                        $pie++;
                    }
                }
            }

            $collection = collect($hasil);
            $isAllZeros = $collection->every(function ($value) {
                return $value === 0;
            });

            $hasil2 = array_fill(0, $length, 0);
            $countValue = array_fill(0, $length, 0);

            $sortedDesc = $desc->toArray();

            if (!$isAllZeros) {
                foreach ($hasil as $key => $value) {
                    $hasil2[$key] = round(($value / $pie) * 100, 2);
                    $countValue[$key] = $value;
                }

                $combined = [];
                foreach ($hasil2 as $key => $value) {
                    $combined[] = [
                        'label' => $desc[$key],
                        'value' => $value
                    ];
                }

                // usort($combined, function ($a, $b) {
                //     return $b['value'] <=> $a['value'];
                // });

                $hasil2 = array_column($combined, 'value');
                $sortedDesc = array_column($combined, 'label');
            }

            foreach ($sortedDesc as $key => $label) {
                $allData[] = [
                    'label' => $label,
                    'value' => $hasil2[$key],
                    'countValue' => $countValue[$key]
                ];
            }
        }
        // return collect(["allData"=>$allData,"limitData"=>$allData]);

        return $allData;
    }

    public function getChartClient(Request $request)
    {
        if ($request->year != "") {
            $year = $request->year;
        }else{
            $year = date('Y');
        }

        $getId = AssetMgmt::join('tb_asset_management_detail','tb_asset_management_detail.id_asset','tb_asset_management.id')->select('tb_asset_management_detail.id_asset','detail_lokasi','tb_asset_management_detail.id')
            ->whereIn(DB::raw('YEAR(tb_asset_management.created_at)'), $year);
        $getLastId = DB::table($getId,'temp')->groupBy('id_asset')->selectRaw('MAX(`temp`.`id`) as `id_last_asset`')->selectRaw('id_asset');

        $data = DB::table($getLastId, 'temp2')->join('tb_asset_management','tb_asset_management.id','temp2.id_asset')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')->join('tb_contact','tb_contact.customer_legal_name','tb_asset_management_detail.client')->select('asset_owner','category','category_peripheral','tb_asset_management.id_asset','type_device','vendor','tb_asset_management.status','rma','spesifikasi','serial_number','notes','client','code');
            // ->where('category_peripheral','-')

        $desc = DB::table($getLastId, 'temp2')->join('tb_asset_management','tb_asset_management.id','temp2.id_asset')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')->join('tb_contact','tb_contact.customer_legal_name','tb_asset_management_detail.client')
            ->select('tb_contact.code')
            // ->where('category_peripheral','-')
            ->groupBy('code')
            ->get()->pluck('code');


        $length = $desc->count(); 

        if (isset($request->assetOwner)) {
            $data = $data->where('tb_asset_management.asset_owner',$request->assetOwner);
        }

        if (isset($request->category)) {
            $data = $data->where('tb_asset_management.category',$request->category);
        }

        if (isset($request->client)) {
            $data = $data->where('tb_asset_management_detail.client',$request->client);
        }

        if (isset($request->pid)) {
            $data = $data->where('tb_asset_management_detail.pid',$request->pid);
        }

        $data = $data->get();        

        $hasilChart = collect(); 

        $allData = [];

        if (count($data) == 0) {
            $allData[] = [
                'label' => '-',
                'value' => 0,
                'countValue' => 0
            ];
        }else{
            $hasil = array_fill(0, $length, 0);

            $pie = 0;

            foreach ($desc as $key => $value2) {
                foreach ($data as $value) {
                    if ($value->code == $value2) {
                        $hasil[$key]++;
                        $pie++;
                    }
                }
            }

            $collection = collect($hasil);
            $isAllZeros = $collection->every(function ($value) {
                return $value === 0;
            });

            $hasil2 = array_fill(0, $length, 0);
            $countValue = array_fill(0, $length, 0);

            $sortedDesc = $desc->toArray();

            if (!$isAllZeros) {
                foreach ($hasil as $key => $value) {
                    $hasil2[$key] = round(($value / $pie) * 100, 2);
                    $countValue[$key] = $value;
                }

                // Combine $hasil2 and $desc into an associative array
                $combined = [];
                foreach ($hasil2 as $key => $value) {
                    $combined[] = [
                        'percentage' => $value,
                        'name' => $desc[$key]
                    ];
                }

                // Sort the combined array by percentage in descending order
                // usort($combined, function ($a, $b) {
                //     return $b['percentage'] <=> $a['percentage'];
                // });

                // Extract the sorted values back into separate arrays
                $hasil2 = array_column($combined, 'percentage');
                $sortedDesc = array_column($combined, 'name');
            }

            foreach ($sortedDesc as $key => $label) {
                $allData[] = [
                    'label' => $label,
                    'value' => $hasil2[$key],
                    'countValue' => $countValue[$key]
                ];
            }
            
        }

        return $allData;
    }

    public function getCountDashboard(Request $request)
    {
        if ($request->year != "") {
            $year = [$request->year];
        }else{
            $year = date('Y');
        }

        $nik = Auth::User()->nik;
        $cek_role = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role','group','mini_group')->where('user_id',$nik)->first();

        $getPid = DB::table('ticketing__user')->where('nik',Auth::User()->nik)->get()->pluck('pid');
        $getPidPm = DB::table('tb_pmo')->join('tb_pmo_assign','tb_pmo_assign.id_project','tb_pmo.id')->join('role_user','role_user.user_id','tb_pmo_assign.nik')->join('roles','roles.id','role_user.role_id')->where('nik',Auth::User()->nik)->where('name','!=','Asset Management')->get()->pluck('project_id');

        $getId = AssetMgmt::join('tb_asset_management_detail','tb_asset_management_detail.id_asset','tb_asset_management.id')->select('tb_asset_management_detail.id_asset','detail_lokasi','tb_asset_management_detail.id')
            ->whereIn(DB::raw('YEAR(tb_asset_management.created_at)'), $year);

        if (isset($request->assetOwner)) {
            $getId = $getId->where('tb_asset_management.asset_owner',$request->assetOwner);
        }

        if (isset($request->category)) {
            $getId = $getId->where('tb_asset_management.category',$request->category);
        }

        if (isset($request->client)) {
            $getId = $getId->where('tb_asset_management_detail.client',$request->client);
        }

        if (isset($request->pid)) {
            $getId = $getId->where('tb_asset_management_detail.pid',$request->pid);
        }

        $getLastId = DB::table($getId,'temp')->groupBy('id_asset')->selectRaw('MAX(`temp`.`id`) as `id_last_asset`')->selectRaw('id_asset');

        $data = DB::table($getLastId, 'temp2')->join('tb_asset_management','tb_asset_management.id','temp2.id_asset')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')
            ->select('tb_asset_management_detail.pid','asset_owner','category','category_peripheral','tb_asset_management.id_asset','type_device','vendor','status','rma','spesifikasi','serial_number','notes','tb_asset_management.id','id_device_customer')
            ->orderBy('tb_asset_management.created_at','desc'); 

        $dataInstalled = DB::table($getLastId, 'temp2')->join('tb_asset_management','tb_asset_management.id','temp2.id_asset')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')
            ->select('tb_asset_management_detail.pid','asset_owner','category','category_peripheral','tb_asset_management.id_asset','type_device','vendor','status','rma','spesifikasi','serial_number','notes','tb_asset_management.id','id_device_customer')
            ->orderBy('tb_asset_management.created_at','desc')->where('status','Installed'); 

        $dataAvailable = DB::table($getLastId, 'temp2')->join('tb_asset_management','tb_asset_management.id','temp2.id_asset')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')
            ->select('tb_asset_management_detail.pid','asset_owner','category','category_peripheral','tb_asset_management.id_asset','type_device','vendor','status','rma','spesifikasi','serial_number','notes','tb_asset_management.id','id_device_customer')
            ->orderBy('tb_asset_management.created_at','desc')->where('status','Available'); 


        $dataRent = DB::table($getLastId, 'temp2')->join('tb_asset_management','tb_asset_management.id','temp2.id_asset')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')
            ->select('tb_asset_management_detail.pid','asset_owner','category','category_peripheral','tb_asset_management.id_asset','type_device','vendor','status','rma','spesifikasi','serial_number','notes','tb_asset_management.id','id_device_customer')
            ->orderBy('tb_asset_management.created_at','desc')->where('status','Rent'); 

        if ($cek_role->mini_group == 'Supply Chain & IT Support' || $cek_role->name_role == 'VP Internal Chain Management' || $cek_role->name_role == 'Chief Operating Officer') {
            $countAll = $data->count();
            $countInstalled = $dataInstalled->count();
            $countAvailable = $dataAvailable->count();
            $countRent = $dataRent->count();
        } else if ($cek_role->name_role == 'Delivery Project Coordinator' || $cek_role->name_role == 'Customer Support Center') {
            $countAll = $data->whereIn('pid',$getPid)->count();
            $countInstalled = $dataInstalled->whereIn('pid',$getPid)->count();
            $countAvailable = $dataInstalled->whereIn('pid',$getPid)->count();
            $countRent = $dataRent->whereIn('pid',$getPid)->count();

            // $data = $data->whereIn('pid',$getPid);
        } elseif ($cek_role->name_role == 'Delivery Project Manager') {
            $countAll = $data->whereIn('pid',$getPidPm)->count();
            $countInstalled = $dataInstalled->whereIn('pid',$getPidPm)->count();
            $countAvailable = $dataInstalled->whereIn('pid',$getPidPm)->count();
            $countRent = $dataRent->whereIn('pid',$getPidPm)->count();

            // $data = $data->whereIn('pid',$getPidPm);
        } else if ($cek_role->name_role == 'Synergy System & Services Manager' ) {
            $countAll = $data->where('pid','!=','INTERNAL')->count();
            $countInstalled = $dataInstalled->where('pid','!=','INTERNAL')->count();
            $countAvailable = $dataInstalled->where('pid','!=','INTERNAL')->count();
            $countRent = $dataRent->where('pid','!=','INTERNAL')->count();
        }



        // $countAll = AssetMgmt::count();
        // $countInstalled = AssetMgmt::where('status','Installed')->count();
        // $countAvailable = AssetMgmt::where('status','Available')->count();
        // $countRent = AssetMgmt::where('status','Temporary')->count();

        return collect(["countAll"=>$countAll,"countInstalled"=>$countInstalled,"countAvailable"=>$countAvailable,"countRent"=>$countRent]);
    }

    public function getFilterCount(Request $request)
    {
        if ($request->year != "") {
            $year = $request->year;
        }else{
            $year = date('Y');
        }

        $nik = Auth::User()->nik;
        $cek_role = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role','group','mini_group')->where('user_id',$nik)->first();

        $getPid = DB::table('ticketing__user')->where('nik',Auth::User()->nik)->get()->pluck('pid');
        $getPidPm = DB::table('tb_pmo')->join('tb_pmo_assign','tb_pmo_assign.id_project','tb_pmo.id')->join('role_user','role_user.user_id','tb_pmo_assign.nik')->join('roles','roles.id','role_user.role_id')->where('nik',Auth::User()->nik)->where('name','!=','Asset Management')->get()->pluck('project_id');

        $getId = AssetMgmt::join('tb_asset_management_detail','tb_asset_management_detail.id_asset','tb_asset_management.id')->select('tb_asset_management_detail.id_asset','detail_lokasi','tb_asset_management_detail.id')->whereYear('tb_asset_management.created_at',$year);
        $getLastId = DB::table($getId,'temp')->groupBy('id_asset')->selectRaw('MAX(`temp`.`id`) as `id_last_asset`')->selectRaw('id_asset');

        $countAll = DB::table($getLastId, 'temp2')->join('tb_asset_management','tb_asset_management.id','temp2.id_asset')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')
            ->select('tb_asset_management_detail.pid','asset_owner','category','category_peripheral','tb_asset_management.id_asset','type_device','vendor','status','rma','spesifikasi','serial_number','notes');

        $countInstalled = DB::table($getLastId, 'temp2')->join('tb_asset_management','tb_asset_management.id','temp2.id_asset')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')
            ->select('tb_asset_management_detail.pid','asset_owner','category','category_peripheral','tb_asset_management.id_asset','type_device','vendor','status','rma','spesifikasi','serial_number','notes')->where('status','Installed');

        $countAvailable = DB::table($getLastId, 'temp2')->join('tb_asset_management','tb_asset_management.id','temp2.id_asset')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')
            ->select('tb_asset_management_detail.pid','asset_owner','category','category_peripheral','tb_asset_management.id_asset','type_device','vendor','status','rma','spesifikasi','serial_number','notes')->where('status','Available');

        $countRent = DB::table($getLastId, 'temp2')->join('tb_asset_management','tb_asset_management.id','temp2.id_asset')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')
            ->select('tb_asset_management_detail.pid','asset_owner','category','category_peripheral','tb_asset_management.id_asset','type_device','vendor','status','rma','spesifikasi','serial_number','notes')->where('status','Temporary');

        if (isset($request->pid)) {
            $countAll->where('pid',$request->pid);
            $countInstalled->where('pid',$request->pid);
            $countAvailable->where('pid',$request->pid);
            $countRent->where('pid',$request->pid);
        }

        if (isset($request->assetOwner)) {
            $countAll->where('asset_owner',$request->assetOwner);
            $countInstalled->where('asset_owner',$request->assetOwner);
            $countAvailable->where('asset_owner',$request->assetOwner);
            $countRent->where('asset_owner',$request->assetOwner);
        }

        if (isset($request->category)) {
            $countAll->where('category',$request->category);
            $countInstalled->where('category',$request->category);
            $countAvailable->where('category',$request->category);
            $countRent->where('category',$request->category);
        } 

        if (isset($request->category)) {
            $countAll->where('category',$request->category);
            $countInstalled->where('category',$request->category);
            $countAvailable->where('category',$request->category);
            $countRent->where('category',$request->category);
        }
        
        if (isset($request->client)) {
            $countAll->where('client',$request->client);
            $countInstalled->where('client',$request->client);
            $countAvailable->where('client',$request->client);
            $countRent->where('client',$request->client);
        } 

        if ($cek_role->mini_group == 'Supply Chain & IT Support' || $cek_role->name_role == 'VP Internal Chain Management' || $cek_role->name_role == 'Chief Operating Officer') {
            $countAll = $countAll;
            $countInstalled = $countInstalled;
            $countAvailable = $countAvailable;
            $countRent = $countRent;
        } else if ($cek_role->name_role == 'Delivery Project Coordinator') {
            $countAll = $countAll->whereIn('pid',$getPid);
            $countInstalled = $countInstalled->whereIn('pid',$getPid);
            $countAvailable = $countAvailable->whereIn('pid',$getPid);
            $countRent = $countRent->whereIn('pid',$getPid);

            // $data = $data->whereIn('pid',$getPid);
        } elseif ($cek_role->name_role == 'Delivery Project Manager') {
            $countAll = $countAll->whereIn('pid',$getPidPm);
            $countInstalled = $countInstalled->whereIn('pid',$getPidPm);
            $countAvailable = $countAvailable->whereIn('pid',$getPidPm);
            $countRent = $countRent->whereIn('pid',$getPidPm);

            // $data = $data->whereIn('pid',$getPidPm);
        } else if ($cek_role->name_role == 'Synergy System & Services Manager' ) {
            $countAll = $countAll->where('pid','!=','INTERNAL');
            $countInstalled = $countInstalled->where('pid','!=','INTERNAL');
            $countAvailable = $countAvailable->where('pid','!=','INTERNAL');
            $countRent = $countRent->where('pid','!=','INTERNAL');
        }

        return collect(["countAll"=>$countAll->count('tb_asset_management.id_asset'),"countInstalled"=>$countInstalled->count('tb_asset_management.id_asset'),"countAvailable"=>$countAvailable->count('tb_asset_management.id_asset'),"countRent"=>$countRent->count('tb_asset_management.id_asset')]);
    }

    public function getColor(Request $request)
    {
        $client = new Client();
        $getData = $client->get('https://csscolorsapi.com/api/colors/theme/dark/');
        $json = (string)$getData->getBody();
        return $getColor = json_decode($json, true);
    }

    public function assignEngineer(Request $request)
    {
        $data = json_decode($request->arrListEngineerAssign,true);

        if ($request->type == 'pid') {
            foreach ($data as $value) {
                $getId = AssetMgmt::join('tb_asset_management_detail','tb_asset_management_detail.id_asset','tb_asset_management.id')->select('tb_asset_management_detail.id_asset','detail_lokasi','tb_asset_management_detail.id');
                $getLastId = DB::table($getId,'temp')->groupBy('id_asset')->selectRaw('MAX(`temp`.`id`) as `id_last_asset`')->selectRaw('id_asset');

                $dataAsset = DB::table($getLastId, 'temp2')->join('tb_asset_management','tb_asset_management.id','temp2.id_asset')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')
                    ->select('tb_asset_management_detail.pid','tb_asset_management_detail.id_asset')->where('pid',$value['pid'])->get();

                foreach ($dataAsset as $values) {
                    $id = AssetMgmt::where('id_asset',$values->id_asset)->first();
                    if (AssetMgmtAssignEngineer::where('id_asset',$values->id_asset)->where('role','Primary')->exists()) {
                        $store = AssetMgmtAssignEngineer::where('id_asset',$values->id_asset)->where('role','Primary')->first();
                        $store->engineer_atm = $value['engineer'];
                        $store->id_asset = $values->id_asset;
                        $store->date_add = Carbon::now()->toDateTimeString();
                    } else {
                        $store = new AssetMgmtAssignEngineer();
                        $store->engineer_atm = $value['engineer'];
                        $store->id_asset = $values->id_asset;
                        $store->role = $value['role'];
                        $store->date_add = Carbon::now()->toDateTimeString();
                    }
                    // $store = new AssetMgmtAssignEngineer();
                    $store->save();
                }
            }
        } else {
           foreach ($data as $value) {
                foreach ($value['id_asset'] as $values) {
                    $id = AssetMgmt::where('id_asset',$values)->first();
                    if (AssetMgmtAssignEngineer::where('id_asset',$values)->where('role','Primary')->exists()) {
                        $store = AssetMgmtAssignEngineer::where('id_asset',$values)->where('role','Primary')->first();
                        $store->engineer_atm = $value['engineer'];
                        $store->id_asset = $values;
                        $store->date_add = Carbon::now()->toDateTimeString();
                    } else {
                        $store = new AssetMgmtAssignEngineer();
                        $store->engineer_atm = $value['engineer'];
                        $store->id_asset = $values;
                        $store->role = $value['role'];
                        $store->date_add = Carbon::now()->toDateTimeString();
                    }
                    // $store = new AssetMgmtAssignEngineer();
                    $store->save();
                }
            } 
        }
        
    }

    public function getPidAsset()
    {
        $getId = AssetMgmt::join('tb_asset_management_detail','tb_asset_management_detail.id_asset','tb_asset_management.id')->select('tb_asset_management_detail.id_asset','detail_lokasi','tb_asset_management_detail.id');
        $getLastId = DB::table($getId,'temp')->groupBy('id_asset')->selectRaw('MAX(`temp`.`id`) as `id_last_asset`')->selectRaw('id_asset');

        $data = DB::table($getLastId, 'temp2')->join('tb_asset_management','tb_asset_management.id','temp2.id_asset')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')
            ->select('tb_asset_management_detail.pid as id','tb_asset_management_detail.pid as text')->where('pid','!=',null)->where('pid','like','%'.request('q').'%')->distinct()->get();

        return $data;
    }

    public function getIdAtm(Request $request)
    {

        $getId = AssetMgmt::join('tb_asset_management_detail','tb_asset_management_detail.id_asset','tb_asset_management.id')->select('tb_asset_management_detail.id_asset','detail_lokasi','tb_asset_management_detail.id');
        $getLastId = DB::table($getId,'temp')->groupBy('id_asset')->selectRaw('MAX(`temp`.`id`) as `id_last_asset`')->selectRaw('id_asset');

        $getLastId = DB::table($getLastId,'temp2')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')->select('tb_asset_management_detail.id_asset','pid','id_last_asset');

        $data = DB::table($getLastId, 'temp3')->join('tb_asset_management','tb_asset_management.id','temp3.id_asset')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp3.id_last_asset')
            ->select('tb_asset_management.id as id',DB::raw("CONCAT(`tb_asset_management`.`id_asset`, ' - ', `id_device_customer`, ' - ', `alamat_lokasi`, ' - ', `type_device`, ' - ', `serial_number`) AS `text`"));  

        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')
                    ->select('name', 'roles.group')->where('user_id', Auth::User()->nik)->first(); 

        if ($cek_role->name == 'Synergy System & Services Manager') {
            $data = $data->where('temp3.pid','!=',null)->get(); 
        } else {
            $data = $data->whereRaw("(`category` = 'ATM' OR `category` = 'CRM')")
            ->get(); 
        }


        // $data = DB::table('tb_asset_management')->join('')->select(DB::raw('`id_asset` AS `id`,`id_asset` AS `text`'))->where('id_asset','like','%'.request('q').'%')->whereRaw("(`category` = 'ATM' OR `category` = 'CRM')")->get();
        return response()->json($data);
    }

    public function getEngineer(Request $request)
    {
        return $data = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select(DB::raw('`users`.`name` AS `id`,`users`.`name` AS `text`'))->where('roles.name','Engineer on Site')->where('status_karyawan','!=','dummy')->where('users.name','like','%'.request('q').'%')->get();
    }

    public function getEngineerById(Request $request)
    {   
        $ids = $request->pid ? 'pid' : 'id_asset';

        if ($ids == 'pid') {
            $id_asset = AssetMgmtDetail::select('id_asset')->where("pid",$request->pid)->orderby('pid','DESC')->limit(1)->first();

            $enginAssign = AssetMgmtAssignEngineer::select('engineer_atm')->where('id_asset',$id_asset->id_asset)->where('role','Primary')->get();
        }else{
            $enginAssign = AssetMgmtAssignEngineer::select('engineer_atm')->whereIn('id_asset',array_map('intval', explode(',', $request->id_asset)))->where('role','Primary')->get();
        }

        $data = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select(DB::raw('`users`.`name` AS `id`,`users`.`name` AS `text`'))->where('roles.name','Engineer on Site')->where('status_karyawan','!=','dummy')->where('users.name','like','%'.request('q').'%')->whereNotIn('users.name',$enginAssign)->get();

        return $data;
    }

    public function getRolesById(Request $request)
    {
        $ids = $request->pid ? 'pid' : 'id_asset';

        if ($ids == 'pid') {
            $id_asset = AssetMgmtDetail::select('id_asset')->where("pid",$request->pid)->orderby('pid','DESC')->limit(1)->first();

            $enginAssign = AssetMgmtAssignEngineer::select(DB::raw('(CASE WHEN role = "Primary" THEN true ELSE false END) AS status'))->where('id_asset',$id_asset->id_asset)->where('engineer_atm',$request->engineer)->first();
        }else{
            $enginAssign = AssetMgmtAssignEngineer::select(DB::raw('(CASE WHEN role = "Primary" THEN true ELSE false END) AS status'))->whereIn('id_asset',array_map('intval', explode(',', $request->id_asset)))->where('engineer_atm',$request->engineer)->first();
        }

        if (isset($enginAssign->status)) {
            $data = collect([
                ['id' => 'Primary', 'text' => 'Primary'],
                ['id' => 'Secondary', 'text' => 'Secondary'],
            ]);
        }else{
            $data = collect([
                ['id'=>'Secondary','text'=>'Secondary']
            ]);
            // $data = collect([
            //     ['id' => 'Primary', 'text' => 'Primary'],
            //     ['id' => 'Secondary', 'text' => 'Secondary'],
            // ]);
        }

        return $data;
    }

    public function getLocationNameFromLatLng(Request $request) {
        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng={$latitude},{$longitude}&key=".env('GOOGLE_API_KEY_GLOBAL');

        // Membuat request ke Google Maps Geocoding API
        $response = file_get_contents($url);

        // Mengecek apakah request berhasil
        if ($response === false) {
            return "Error fetching data.";
        }

        // Parsing hasil JSON
        $data = json_decode($response, true);

        // Mengecek apakah hasil valid
        if ($data['status'] == 'OK') {
            // Mengambil alamat format terbaca manusia (formatted_address) dari hasil pertama
            return $data['results'][0]['formatted_address'];
        }

        return "Location not found.";
    }

    public function getTicketId(Request $request)
    {
        $getId = AssetMgmt::join('tb_asset_management_detail','tb_asset_management_detail.id_asset','tb_asset_management.id')->select('tb_asset_management_detail.id_asset','detail_lokasi','tb_asset_management_detail.id');
        $getLastId = DB::table($getId,'temp')->groupBy('id_asset')->selectRaw('MAX(`temp`.`id`) as `id_last_asset`');

        $getAll = DB::table($getLastId, 'temp2')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')->join('tb_asset_management', 'tb_asset_management.id', '=', 'tb_asset_management_detail.id_asset')->leftJoin('tb_asset_management_assign_engineer','tb_asset_management.id_asset','tb_asset_management_assign_engineer.id_asset')
            ->select('serial_number','maintenance_end','maintenance_start')
            ->where('tb_asset_management_detail.id_asset',$request->id_asset)
            ->first();

        // return $getAll->serial_number;

        // $getIdTicket = DB::table('ticketing__detail')->select('id_ticket',DB::raw("CONCAT(`location`,' - ',`problem`) AS problem"),'pic',DB::raw("(CASE WHEN (type_ticket = 'TT') THEN 'Trouble Ticket' WHEN (type_ticket = 'PM') THEN 'Preventive Maintenance' WHEN (type_ticket = 'PL') THEN 'Permintaan Layanan'  END) as type_ticket"))->where('serial_device',$getAll->serial_number)->whereBetween('reporting_time', [$getAll->maintenance_start . " 00:00:00", $getAll->maintenance_end . " 23:59:59"])->get();

        $occurring_ticket = DB::table('ticketing__activity')
            ->select('id_ticket','activity')
            ->whereIn('id',function ($query) {
                $query->select(DB::raw("MAX(id) AS activity"))
                    ->from('ticketing__activity')
                    ->groupBy('id_ticket');
                })
            ->where('activity','CLOSE')
            ->get()
            ->pluck('id_ticket');

        $occurring_ticket_result = TicketingDetail::with([
                'first_activity_ticket:id_ticket,date,operator',
                'lastest_activity_ticket',
                'id_detail:id_ticket,id'
            ])
            ->whereIn('id_ticket',$occurring_ticket)
            ->where('serial_device',$getAll->serial_number)
            ->where('serial_device','!=',null)
            // ->whereBetween('reporting_time', [$getAll->maintenance_start . " 00:00:00", $getAll->maintenance_end . " 23:59:59"])
            ->orderBy('ticketing__detail.id','DESC')
            ->get();

        foreach ($occurring_ticket_result as $ticket) {
            $concat_problem_ticket = DB::table('ticketing__detail')
                ->select(DB::raw("CONCAT(`location`,' - ',`problem`) AS problem"))
                ->where('id_ticket', $ticket->id_ticket)
                ->first()->problem;
            
            $concat_type_ticket = DB::table('ticketing__detail')
                ->select(DB::raw("(CASE WHEN `type_ticket` = 'PL' THEN 'Permintaan Layanan Ticket' 
                                        WHEN `type_ticket` = 'TT' THEN 'Trouble Ticket' 
                                        WHEN `type_ticket` = 'PM' THEN 'Preventive Maintenance Ticket' 
                                        ELSE '-' END) as type"))
                ->where('id_ticket', $ticket->id_ticket)
                ->first()->type;
            
            $ticket->concatenate_problem_ticket = $concat_problem_ticket;
            $ticket->concatenate_type_ticket = $concat_type_ticket;
        }

        $result = $occurring_ticket_result;

        return array("data" => $result);
    }

    public function getChangeLog(Request $request)
    {
        $data = AssetMgmtLog::where('id_asset',$request->id_asset)->orderby('id','desc')->get();

        return array("data"=>$data);
    }

    public function getServicePoint(Request $request)
    {
        $data = DB::table('tb_asset_management_service_point')->select('service_point as id','service_point as text')->where('service_point','like','%'.request('q').'%')->distinct()->get();

        return $data;
    }

    public function getCategory(Request $request)
    {
        $data = DB::table('tb_asset_management_category')->select('id_category as id','name as text')->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower(request('q')) . '%'])->distinct()->get();

        return $data;
    }

    public function getSpesifikasi(Request $request)
    {
        $data = DB::table('tb_asset_management_spesifikasi')->select('id','name','satuan')->where('id_category', $request->id)->where('name', 'like', '%'.request('q').'%')->distinct()->get();

        return $data;
    }

    public function getSpesifikasiDetail(Request $request)
    {
        $data = DB::table('tb_asset_management_spesifikasi_detail')->select('id','name')->where('id_spesifikasi', $request->id)->where('name', 'like', '%'.request('q').'%')->distinct()->get();

        return $data;
    }

    public function storeSpesifikasiDetail(Request $request)
    {
        $idSpesifikasi = $request->input('id_spesifikasi');  
        $name          = $request->input('name');

        $existing = DB::table('tb_asset_management_spesifikasi_detail')
                  ->where('id_spesifikasi', $idSpesifikasi)
                  ->where('name', $name)
                  ->first();
    
        if ($existing) {
            return response()->json([
                'id'   => $existing->id,
                'id_spesifikasi' => $idSpesifikasi,
                'name' => $name
            ]);
        }

        $id = DB::table('tb_asset_management_spesifikasi_detail')->insertGetId([
            'id_spesifikasi' => $idSpesifikasi,
            'name'           => $name
        ]);

        return response()->json([
            'id'   => $id,
            'id_spesifikasi' => $idSpesifikasi,
            'name' => $name
        ]);
    }

    public function getEmployeeNames()
    {
        $data = DB::table('users')
            ->join('role_user','role_user.user_id','=','users.nik')
            ->join('roles','roles.id','=','role_user.role_id')
            ->select('users.nik as id', DB::raw("MIN(CONCAT(users.name, ' - ', (CASE WHEN roles.mini_group IS NULL THEN roles.group ELSE roles.mini_group END))) AS text"))
            ->where('id_company', 1)
            ->where('status_delete', '!=', 'D')
            ->where('status_karyawan','!=','dummy')
            // ->where(DB::raw("CONCAT(users.name, ' - ', roles.mini_group)"),'<>','NULL')
            ->where('users.name','like', '%'.request('q').'%')
            ->orderBy('users.name')
            ->groupBy('users.nik')
            // ->where('users.name','like', '%'.DB::raw("CONCAT(users.name, ' - ', roles.mini_group)").'%')
            ->get();

        return $data;
    }

    public function getLocationAddress()
    {
        $data = DB::table('tb_asset_management_service_point')->select('service_point as name', 'detail_lokasi as lokasi','latitude as lat','longitude as long')->distinct()->get();

        return $data;
    }

    public function haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371)
    {
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $angle * $earthRadius;
    }

    public function storeServicePoint(Request $request)
    {
        $store = new AssetMgmtServicePoint();
        $store->service_point = $request->servicePoint;
        $store->latitude = $request->latitude;
        $store->longitude = $request->longitude;
        $store->detail_lokasi = $request->detailLokasi;
        $store->date_add = Carbon::now()->toDateTimeString();
        $store->save();
    }

    public function storeCategory(Request $request)
    {
        $store = new AssetMgmtCategory();

        $messages = [
            'id_category.unique' => collect(["inputCatCode"=>'The Category ID has already been taken!']),
            'name.unique' => collect(["inputCatName"=>'The Category Name has already been taken!']),
        ];

        $validator = Validator::make($request->all(), [
            'id_category' => 'unique:tb_asset_management_category,id_category',
            'name' => 'unique:tb_asset_management_category,name',
        ],$messages);

        if (!$validator->passes()) {
            return response()->json(['errors' => $validator->errors()->all()], 500);
        }

        $store->fill([
            "id_category" => $request->id_category,
            "name" => $request->name, 
            "date_add" => Carbon::now()->toDateTimeString()
        ]);

        $store->save();
    }

    public function getReminderAsset(Request $request)
    {
        $getId = AssetMgmt::join('tb_asset_management_detail','tb_asset_management_detail.id_asset','tb_asset_management.id')->select('tb_asset_management_detail.id_asset','detail_lokasi','tb_asset_management_detail.id');
        $getLastId = DB::table($getId,'temp')->groupBy('id_asset')->selectRaw('MAX(`temp`.`id`) as `id_last_asset`')->selectRaw('id_asset');

        $getAll = DB::table($getLastId, 'temp2')->join('tb_asset_management','tb_asset_management.id','temp2.id_asset')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')
            ->select('tb_asset_management_detail.pid','asset_owner','category','category_peripheral','tb_asset_management.id_asset','status','serial_number','maintenance_end')
            // ->where('category_peripheral','-')
            ->orderBy('tb_asset_management.created_at','desc')->where(DB::raw("DATEDIFF(now(), maintenance_end)"), '=', '-90')->get()->pluck('pid'); 

        $getIdPmo = DB::table('tb_pmo')->whereIn('project_id',$getAll)->get()->pluck('id');

        $getPidPm = DB::table('tb_pmo')->join('tb_pmo_assign','tb_pmo_assign.id_project','tb_pmo.id')->join('users','users.nik','tb_pmo_assign.nik')->whereIn('id_project',$getIdPmo)->select('name','email')->where('role','Delivery Project Coordinator');

        $dataAll = DB::table('users')
                    ->whereIn('nik',function($query) use ($getAll){
                        $query->select('users.nik')
                            ->from('tb_id_project')
                            ->join('sales_lead_register','sales_lead_register.lead_id','tb_id_project.lead_id')
                            ->join('users','users.nik','sales_lead_register.nik')
                            ->whereIn('id_project',$getAll)
                            ->groupBy('users.nik');
                    })
                    ->orWhereIn('nik',function($query) use ($getIdPmo){
                        $query->select('users.nik')
                            ->from('tb_pmo')
                            ->join('tb_pmo_assign','tb_pmo_assign.id_project','tb_pmo.id')
                            ->join('users','users.nik','tb_pmo_assign.nik')
                            ->whereIn('id_project',$getIdPmo)
                            ->where('role','Delivery Project Coordinator')
                            ->groupBy('users.nik');
                    })
                    ->select('nik','users.email','users.name')
                    ->get();

        // return $dataAll;

        foreach ($dataAll as $key => $data) {
            $getId = AssetMgmt::join('tb_asset_management_detail','tb_asset_management_detail.id_asset','tb_asset_management.id')->select('tb_asset_management_detail.id_asset','detail_lokasi','tb_asset_management_detail.id');
            $getLastId = DB::table($getId,'temp')->groupBy('id_asset')->selectRaw('MAX(`temp`.`id`) as `id_last_asset`')->selectRaw('id_asset');
            Mail::to($data->email)->send(new MailReminderMaintenanceEndAsset(collect([
                "to" => $data->name,
                "data" => DB::table($getLastId, 'temp2')->join('tb_asset_management','tb_asset_management.id','temp2.id_asset')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')
                    ->select('tb_asset_management_detail.pid','asset_owner','category','category_peripheral','tb_asset_management.id_asset','status','serial_number','client','pid',DB::raw("CONCAT(`detail_lokasi`, ' - ', `alamat_lokasi`, ' - ', `kota`) AS `lokasi`"),DB::raw("CONCAT(`maintenance_start`, ' s/d ', `maintenance_end`) AS `periode`"))
                    ->where('category_peripheral','-')->where(DB::raw("DATEDIFF(now(), maintenance_end)"), '=', '-90')->get(),
                ])
            ));
        }
    }

    public function uploadToLocal($file,$directory,$nameFile){
        $file->move($directory,$nameFile);
    }

    public function googleDriveMakeFolder($nameFolder){
        $client_folder = $this->getClient();
        $service_folder = new Google_Service_Drive($client_folder);

        $file = new Google_Service_Drive_DriveFile();
        $file->setName($nameFolder);
        $file->setMimeType('application/vnd.google-apps.folder');
        $file->setDriveId(env('GOOGLE_DRIVE_DRIVE_ID'));
        $file->setParents([env('GOOGLE_DRIVE_PARENT_ID_Asset')]);

        $result = $service_folder->files->create(
            $file, 
            array(
                'mimeType' => 'application/octet-stream',
                'uploadType' => 'multipart',
                'supportsAllDrives' => true
            )
        );

        return array($result->id);
    }

    public function googleDriveUploadCustom($fileName,$locationFile,$parentID){
        $client = $this->getClient();
        $service = new Google_Service_Drive($client);

        $file = new Google_Service_Drive_DriveFile();
        $file->setName($fileName);
        $file->setParents($parentID);

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

    public function getIdAsset(Request $request)
    {
        $id = AssetMgmtScheduling::where('status','PENDING')->pluck('id_asset');

        $getId = AssetMgmt::join('tb_asset_management_detail','tb_asset_management_detail.id_asset','tb_asset_management.id')->select('tb_asset_management_detail.id_asset','detail_lokasi','tb_asset_management_detail.id');
        $getLastId = DB::table($getId,'temp')->groupBy('id_asset')->selectRaw('MAX(`temp`.`id`) as `id_last_asset`');

        // $getAll = DB::table($getLastId, 'temp2')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')->join('tb_asset_management', 'tb_asset_management.id', '=', 'tb_asset_management_detail.id_asset')->select(DB::raw('`tb_asset_management`.`id` AS `id`'),DB::raw("CONCAT(`tb_asset_management`.`id_asset`, ' - ', `alamat_lokasi`, ' - ', `serial_number`) AS `text`"))

        $data = DB::table($getLastId, 'temp2')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')->join('tb_asset_management', 'tb_asset_management.id', '=', 'tb_asset_management_detail.id_asset')->select('tb_asset_management.id as id', DB::raw("CONCAT(`tb_asset_management`.`id_asset`, ' - ', `id_device_customer`, ' - ', `alamat_lokasi`, ' - ', `type_device`, ' - ', `serial_number`) AS `text`"))
            ->where('tb_asset_management.id_asset', 'like', '%' . request('q') . '%')
            ->whereNotIn('tb_asset_management.id',$id)
            ->distinct()
            ->get();

        return $data;
    }

    public function getPidScheduling(Request $request)
    {
        $getId = AssetMgmt::join('tb_asset_management_detail','tb_asset_management_detail.id_asset','tb_asset_management.id')->select('tb_asset_management_detail.id_asset','detail_lokasi','tb_asset_management_detail.id');
        $getLastId = DB::table($getId,'temp')->groupBy('id_asset')->selectRaw('MAX(`temp`.`id`) as `id_last_asset`')->selectRaw('id_asset');

        if (isset($request->id_asset)) {
            $pid = DB::table($getLastId, 'temp2')->join('tb_asset_management','tb_asset_management.id','temp2.id_asset')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')
            ->select('tb_asset_management_detail.pid')->where('tb_asset_management_detail.id_asset',$request->id_asset)->pluck('pid');

            $getClient = DB::table('tb_id_project')->whereIn('id_project',$pid)->select('customer_name')->pluck('customer_name');
            $getDate = DB::table('tb_id_project')->whereIn('id_project',$pid)->select('date')->first()->date;

            $data = DB::table('tb_id_project')->select('id_project as id', 'id_project as text')
                ->where('id_project', 'like', '%' . request('q') . '%')
                ->whereNotIn('id_project',$pid)
                ->whereIn('customer_name',$getClient)
                ->where('date', '>', $getDate)
                ->distinct()
                ->get();

        } else {
            $pid = DB::table($getLastId, 'temp2')->join('tb_asset_management','tb_asset_management.id','temp2.id_asset')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')
            ->select('tb_asset_management_detail.pid')->where('tb_asset_management_detail.pid',$request->pid)->pluck('pid');

            $getClient = DB::table('tb_id_project')->whereIn('id_project',$pid)->select('customer_name')->pluck('customer_name');
            $getDate = DB::table('tb_id_project')->whereIn('id_project',$pid)->select('date')->first()->date;

            $data = DB::table('tb_id_project')->select('id_project as id', 'id_project as text')
                ->where('id_project', 'like', '%' . request('q') . '%')
                ->whereNotIn('id_project',$pid)
                ->whereIn('customer_name',$getClient)
                ->where('date', '>', $getDate)
                ->distinct()
                ->get();
        }

        

        return $data;
    }

    public function storeScheduling(Request $request)
    {
        $data = json_decode($request->arrListAsset,true);

        if ($request->type == 'pid') {
            foreach ($data as $value) {
                $getId = AssetMgmt::join('tb_asset_management_detail','tb_asset_management_detail.id_asset','tb_asset_management.id')->select('tb_asset_management_detail.id_asset','detail_lokasi','tb_asset_management_detail.id');
                $getLastId = DB::table($getId,'temp')->groupBy('id_asset')->selectRaw('MAX(`temp`.`id`) as `id_last_asset`');

                $getAll = DB::table($getLastId, 'temp2')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')
                    ->join('tb_asset_management', 'tb_asset_management.id', '=', 'tb_asset_management_detail.id_asset')
                    ->select('tb_asset_management_detail.id_asset','pid','maintenance_start','maintenance_end')
                    ->where('tb_asset_management_detail.pid',$value['pid_before'])
                    ->get();

                foreach ($getAll as $data) {

                    $start_before = $data->maintenance_end;
                    $start_after = Carbon::parse($start_before)
                                    ->addDay(1)->format('Y-m-d');

                    $start_after_carbon = Carbon::parse($start_after);
                    $end_after = $start_after_carbon->copy()->addMonth($value['periode']);

                    if ($end_after->day != $start_after_carbon->day) {
                        $end_after = $end_after->subDays($end_after->day)->endOfMonth();
                    }

                    $end_after_formatted = $end_after->format('Y-m-d');

                    $store = new AssetMgmtScheduling();
                    $store->id_asset = $data->id_asset;
                    $store->pid = $value['pid_after'];
                    $store->maintenance_start = $start_after;
                    $store->maintenance_end = $end_after_formatted;
                    $store->status = 'PENDING';
                    $store->date_add = Carbon::now()->toDateTimeString();
                    $store->save();

                    $id_asset = AssetMgmt::where('id',$store->id_asset)->first()->id_asset;
                    $storeLog = new AssetMgmtLog();
                    $storeLog->id_asset = $store->id_asset;
                    $storeLog->operator = Auth::User()->name;
                    $storeLog->date_add = Carbon::now()->toDateTimeString();
                    $storeLog->activity = 'Add Schedulling ' . $id_asset;
                    $storeLog->save();
                }
            }
        } else {
            foreach ($data as $value) {
                $store = new AssetMgmtScheduling();
                $store->id_asset = $value['id_asset'];
                $store->pid = $value['pid'];
                $store->maintenance_end = $value['date_end'];
                $store->maintenance_start = $value['date_start'];
                $store->status = 'PENDING';
                $store->date_add = Carbon::now()->toDateTimeString();
                $store->save();

                $id_asset = AssetMgmt::where('id',$store->id_asset)->first()->id_asset;
                $storeLog = new AssetMgmtLog();
                $storeLog->id_asset = $value['id_asset'];
                $storeLog->operator = Auth::User()->name;
                $storeLog->date_add = Carbon::now()->toDateTimeString();
                $storeLog->activity = 'Add Schedulling ' . $id_asset;
                $storeLog->save();
            }
        }
    }

    public function getDataScheduling()
    {
        $data = AssetMgmtScheduling::join('tb_asset_management','tb_asset_management.id','tb_asset_management_scheduling.id_asset')->select('tb_asset_management.id_asset','pid','maintenance_start','maintenance_end','tb_asset_management_scheduling.status','tb_asset_management_scheduling.id')->where('tb_asset_management_scheduling.status','PENDING');

        return array("data" => $data->get());
    }

    public function testScheduling(Request $request)
    {
        $cek = DB::table('tb_asset_management_scheduling')->where('status','PENDING')->where('maintenance_start',date("Y-m-d"))->get();

        foreach ($cek as $value) {
            $updateScheduling = AssetMgmtScheduling::where('id_asset',$value->id_asset)->first();
            $updateScheduling->status = 'DONE';
            $updateScheduling->save();

            $getDetail = AssetMgmtDetail::where('id_asset',$value->id_asset)->orderby('id','desc')->first();
            $addDetail = new AssetMgmtDetail();
            $addDetail->id_asset = $getDetail->id_asset;
            $addDetail->pid = $value->pid;
            $addDetail->maintenance_start = $value->maintenance_start;
            $addDetail->maintenance_end = $value->maintenance_end;
            $addDetail->id_device_customer = $getDetail->id_device_customer;
            $addDetail->client = $getDetail->client;
            $addDetail->kota = $getDetail->kota;
            $addDetail->alamat_lokasi = $getDetail->alamat_lokasi;
            $addDetail->detail_lokasi = $getDetail->detail_lokasi;
            $addDetail->latitude = $getDetail->latitude;
            $addDetail->longitude = $getDetail->longitude;
            $addDetail->service_point = $getDetail->service_point;
            $addDetail->port = $getDetail->port;
            $addDetail->ip_address = $getDetail->ip_address;
            $addDetail->server = $getDetail->server;
            $addDetail->status_cust = $getDetail->status_cust;
            $addDetail->second_level_support = $getDetail->second_level_support;
            $addDetail->operating_system = $getDetail->operating_system;
            if (!empty($getDetail->installed_date)) {
                $addDetail->installed_date = $getDetail->installed_date;
            } else {
                $addDetail->installed_date = null;
            }

            $addDetail->license = $getDetail->license;

            if (!empty($getDetail->license_start_date)) {
                $addDetail->license_start_date = $getDetail->license_start_date;
            } else {
                $addDetail->license_start_date = null;
            }

            if (!empty($getDetail->license_end_date)) {
                $addDetail->license_end_date = $getDetail->license_end_date;
            } else {
                $addDetail->license_end_date = null;
            }
            
            $addDetail->save();
        }

        return $cek;
    }

    public function deleteScheduling(Request $request)
    {
        $delete = AssetMgmtScheduling::where('id',$request->id)->delete();
    }
    // public function getAssignedEngineer(Request $request)
    // {
    //     $data = AssetMgmtAssignEngineer::join('tb_asset_management','tb_asset_management.id_asset','tb_asset_management_assign_engineer.id_asset')->select(DB::raw('`name` AS `id`,`users`.`name` AS `text`'))->get();
    //     return $data;
    // }

    public function getPdfBASTAsset($id_asset, $id_detail_asset) // Request $request
    {   
        $pihak_pertama = User::select('users.name','users.nik','roles.name as departement','phone','ttd','date_of_entry as entry_date')
                        ->join('role_user','role_user.user_id','=','users.nik')
                        ->join('roles','roles.id','=','role_user.role_id')
                        ->where('nik',Auth::User()->nik)
                        ->first(); 

        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')
                    ->select('name', 'roles.group','roles.mini_group')
                    ->where('user_id', Auth::User()->nik)
                    ->first(); 

        if (stripos($cek_role->name, 'Manager') !== false) {
            $atasan_pp = User::select('users.name','users.nik','roles.name as departement','phone','ttd')
                        ->join('role_user','role_user.user_id','=','users.nik')
                        ->join('roles','roles.id','=','role_user.role_id')
                        ->where('roles.group','Supply Chain, CPS & Asset Management')
                        ->where('roles.name','like','VP%')
                        ->first();
        }else{
            $atasan_pp = User::select('users.name','users.nik','roles.name as departement','phone','ttd')
                        ->join('role_user','role_user.user_id','=','users.nik')
                        ->join('roles','roles.id','=','role_user.role_id')
                        ->where('roles.mini_group','Supply Chain & IT Support')
                        ->where('roles.name','like','%Manager%')
                        ->first(); 
        }

        $pihak_pertama->atasan = $atasan_pp->name;

        $getId = AssetMgmt::join('tb_asset_management_detail','tb_asset_management_detail.id_asset','tb_asset_management.id')
                ->select('tb_asset_management_detail.id_asset','detail_lokasi','tb_asset_management_detail.id');
        
        $getLastId = DB::table($getId,'temp')
                    ->groupBy('id_asset')
                    ->selectRaw('MAX(`temp`.`id`) as `id_last_asset`');

        $list_asset_request = DB::table($getLastId, 'temp2')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')
                    ->join('tb_asset_management', 'tb_asset_management.id', '=', 'tb_asset_management_detail.id_asset')
                    ->select('tb_asset_management.id','category','serial_number',DB::raw('CONCAT(vendor, " - ",type_device) AS merk'),'notes', DB::raw("(CASE WHEN (accessoris is null or accessoris = 'undefined') THEN '-' ELSE accessoris END) as accessoris"), 'spesifikasi')
                    ->where('tb_asset_management.id',$id_asset) //request->
                    ->get();
            
        // $list_asset_request = DB::table('tb_asset_management')
        //                 ->select('tb_asset_management.id','category','type_device','serial_number','vendor as merk','notes',DB::raw("(CASE WHEN (accessoris is null) THEN '-' ELSE accessoris END) as accessoris"))
        //                 ->join('tb_asset_management_detail','tb_asset_management_detail.id_asset','=','tb_asset_management.id')
        //                 ->where('tb_asset_management.id',$id_asset)
        //                 ->groupBy('tb_asset_management.id','accessoris')
        //                 ->get();
                    
        // return $id_asset;
        $cek_role_pk = DB::table('tb_asset_management_detail')
                        ->join('users','users.nik','=','tb_asset_management_detail.pic')
                        ->join('role_user','role_user.user_id','=','users.nik')
                        ->join('roles','roles.id','=','role_user.role_id')
                        // ->join('tb_asset_management_detail','tb_asset_management_detail.id_asset','=','tb_asset_management.id')
                        ->select('tb_asset_management_detail.id','tb_asset_management_detail.id_asset','roles.name','roles.group','users.name as name_pk','roles.mini_group','ttd','users.nik','users.date_of_entry as entry_date','users.phone','installed_date', 'users.id_territory')
                        ->where('tb_asset_management_detail.id_asset',$id_asset) //request->
                        ->where('tb_asset_management_detail.id',$id_detail_asset); //request->
                        // ->where('tb_asset_management_detail.id_asset',$id_asset);

        $pihak_kedua = $cek_role_pk->first();
        $roleName = $pihak_kedua->name;
        $ppGroup = $pihak_kedua->group;
        $territory_sales = $pihak_kedua->id_territory;

        if (strpos($roleName, 'VP Sales') !== false ) {
            $atasan_pk = User::select('users.name','users.nik','roles.name as departement','phone')
                ->join('role_user','role_user.user_id','=','users.nik')
                ->join('roles','roles.id','=','role_user.role_id')
                ->where('roles.name','like', '%Chief Executive Officer%')
                ->whereNotNull('user_id')
                ->where('status_karyawan','!=','dummy')
                ->first();
        }
        else if (strpos($roleName, 'Account Executive') !== false ) {
            $atasan_pk = User::select('users.name','users.nik','roles.name as departement','phone')
                ->join('role_user','role_user.user_id','=','users.nik')
                ->join('roles','roles.id','=','role_user.role_id')
                ->where('roles.name','like', '%VP Sales%')
                ->where('id_territory','like','%'.$territory_sales.'%')
                ->whereNotNull('user_id')
                ->where('status_karyawan','!=','dummy')
                ->first();
        }
        else if (strpos($roleName, 'VP Financial & Accounting') !== false ) {
            $atasan_pk = User::select('users.name','users.nik','roles.name as departement','phone')
                ->join('role_user','role_user.user_id','=','users.nik')
                ->join('roles','roles.id','=','role_user.role_id')
                ->where('roles.name','like', '%Chief Executive Officer%')
                ->whereNotNull('user_id')
                ->where('status_karyawan','!=','dummy')
                ->first();
        }
        else if (strpos($roleName, 'VP') === 0 ) {
            $atasan_pk = User::select('users.name','users.nik','roles.name as departement','phone')
                ->join('role_user','role_user.user_id','=','users.nik')
                ->join('roles','roles.id','=','role_user.role_id')
                ->where('roles.name','like', '%Chief Operating Officer%')
                ->whereNotNull('user_id')
                ->where('status_karyawan','!=','dummy')
                ->first();
        } 
        else if (strpos($roleName, 'Delivery Project Manager') !== false) {
            $atasan_pk = User::select('users.name','users.nik','roles.mini_group as departement','phone')
                            ->join('role_user','role_user.user_id','=','users.nik')
                            ->join('roles','roles.id','=','role_user.role_id')
                            ->where('group','like','%'.$ppGroup.'%')
                            ->where('roles.name','like','%Project Management Manager%')
                            ->first(); 
        }
        else if (strpos($roleName, 'Renumeration, Personalia & GS Manager') === 0) {
            $atasan_pk = User::select('users.name','users.nik','roles.mini_group as departement','phone')
                            ->join('role_user','role_user.user_id','=','users.nik')
                            ->join('roles','roles.id','=','role_user.role_id')
                            ->where('roles.name','like','VP Program & Project Management')
                            ->first(); 
        } 
        else if (substr($roleName, -7) === 'Manager') {
            $atasan_pk = User::select('users.name','users.nik','roles.mini_group as departement','phone')
                            ->join('role_user','role_user.user_id','=','users.nik')
                            ->join('roles','roles.id','=','role_user.role_id')
                            ->where('group','like','%'.$ppGroup.'%')
                            ->where('roles.name','like','VP%')
                            ->first(); 
        } 
        else if ($roleName === "Chief Executive Officer") {
            $atasan_pk = (object) [
                'name'       => '-',
                'nik'        => '',
                'departement'=> '',
                'phone'      => '',
            ];

        } else if (substr($roleName, -8) === 'Director') {
            $atasan_pk = User::select('users.name','users.nik','roles.mini_group as departement','phone')
                            ->join('role_user','role_user.user_id','=','users.nik')
                            ->join('roles','roles.id','=','role_user.role_id')
                            ->where('roles.name','like','Chief Executive Officer')
                            ->first(); 
        } 


        // if (($salesUser && $salesUser->id === $pihak_kedua->id) || ($financeUser && $financeUser->id === $pihak_kedua->id)) {
        //     $atasan_pk = User::select('users.name','users.nik','roles.name as departement','phone')
        //         ->join('role_user','role_user.user_id','=','users.nik')
        //         ->join('roles','roles.id','=','role_user.role_id')
        //         ->where('roles.name','like', '%Chief Executive Officer%')
        //         ->whereNotNull('user_id')
        //         ->where('status_karyawan','!=','dummy')
        //         ->first();
        // }        
        // else if ($cek_role_pk->where('roles.name','like','%Manager%')->first()) {
        //     $group = $cek_role_pk->first()->group;

        //     $atasan_pk = User::select('users.name','users.nik','roles.mini_group as departement','phone')
        //                 ->join('role_user','role_user.user_id','=','users.nik')
        //                 ->join('roles','roles.id','=','role_user.role_id')
        //                 ->where('group','like','%'.$group.'%')
        //                 ->where('roles.name','like','VP%')
        //                 ->first(); 
        // } 
        // else if ($vpUser && $vpUser->id === $pihak_kedua->id) {
        //     $atasan_pk = User::select('users.name','users.nik','roles.name as departement','phone')
        //         ->join('role_user','role_user.user_id','=','users.nik')
        //         ->join('roles','roles.id','=','role_user.role_id')
        //         ->where('roles.name','like', '%Chief Operating Officer%')
        //         ->whereNotNull('user_id')
        //         ->where('status_karyawan','!=','dummy')
        //         ->first();
        // }
        //else if (($cek_role_pk->where('roles.name','like','VP%')->first()) == $pihak_kedua) {
        //     $atasan_pk = User::select('users.name','users.nik','roles.name as departement','phone')
        //                 ->join('role_user','role_user.user_id','=','users.nik')
        //                 ->join('roles','roles.id','=','role_user.role_id')
        //                 ->where('roles.name','like', '%Chief Operating Officer%')
        //                 ->first();
        //     dd($atasan_pk);
        // }
        else {
            $cek_role_pk = DB::table('tb_asset_management')
                        ->join('tb_asset_management_detail', 'tb_asset_management_detail.id_asset', '=', 'tb_asset_management.id')
                        ->select('tb_asset_management.id', 'roles.name', 'roles.group', 'users.name as name_pk', 'roles.mini_group', 'ttd')
                        ->join('users', 'users.nik', '=', 'tb_asset_management_detail.pic')
                        ->join('role_user', 'role_user.user_id', '=', 'users.nik')
                        ->join('roles', 'roles.id', '=', 'role_user.role_id')
                        ->where('tb_asset_management_detail.id_asset',$id_asset) //request->
                        ->where('tb_asset_management_detail.id',$id_detail_asset) //request->
                        ->first();
            
            $atasan_pk = User::select('users.name','users.nik','roles.mini_group as departement','phone')
                        ->join('role_user','role_user.user_id','=','users.nik')
                        ->join('roles','roles.id','=','role_user.role_id');

            $mini_group = $cek_role_pk->mini_group;
            if ($mini_group === 'Human Capital') {
                $atasan_pk = $atasan_pk
                        ->where('roles.name','like','VP Program & Project Management')
                        ->first();
            }
            else if ($mini_group == "" or $mini_group == null) {
                $group = $cek_role_pk->group;
                $atasan_pk =  $atasan_pk
                        ->where('roles.name','like','%Manager%')
                        ->where('roles.name','<>','Delivery Project Manager')
                        ->where('group','like','%'. $group .'%')
                        ->first();
            } 
            else {
                $mini_group = $cek_role_pk->mini_group;
                $isManagerOnMiniGroup = User::select('users.name','users.nik','roles.mini_group as departement','phone')
                                ->join('role_user','role_user.user_id','=','users.nik')
                                ->join('roles','roles.id','=','role_user.role_id')
                                ->where('roles.name','like','%Manager%')
                                ->where('roles.name','<>','Delivery Project Manager')
                                ->where('roles.mini_group','like','%'.$mini_group.'%')
                                ->first();
        
                if ($isManagerOnMiniGroup) {
                    $atasan_pk = $atasan_pk
                            ->where('roles.name','like','%Manager%')
                            ->where('roles.name','<>','Delivery Project Manager')
                            ->where('roles.mini_group','like','%'.$mini_group.'%')
                            ->first();
                } else{
                    $group = $cek_role_pk->group;
                    $atasan_pk =  $atasan_pk
                            ->where('roles.name','like','VP%')
                            ->where('group','like','%'. $group .'%')
                            ->first();
                } 
            }
            
            // $isManagerOnMiniGroup = User::select('users.name','users.nik','roles.mini_group as departement','phone')
            //                     ->join('role_user','role_user.user_id','=','users.nik')
            //                     ->join('roles','roles.id','=','role_user.role_id')
            //                     ->where('roles.name','like','%Manager%')
            //                     ->where('roles.name','<>','Delivery Project Manager')
            //                     ->where(function($query) use ($mini_group) {
            //                         $query->where('roles.mini_group','like','%'.$mini_group.'%')
            //                               ->orWhere('roles.group','like','%'.$mini_group.'%');
            //                     })
            //                     ->first();
            
            // $atasan_pk = $atasan_pk
            //                     ->where('roles.name','like','%Manager%')
            //                     ->where('roles.name','<>','Delivery Project Manager')
            //                     ->where(function($query) use ($mini_group) {
            //                         $query->where('roles.mini_group','like','%'.$mini_group.'%')
            //                             ->orWhere('roles.group','like','%'.$mini_group.'%');
            //                     })
            //                     ->first();

            // if ($isManagerOnMiniGroup) {
                // $atasan_pk = $atasan_pk
                //         ->where('roles.name','like','%Manager%')
                //         ->where('roles.name','<>','Delivery Project Manager')
                //         ->where(function($query) use ($mini_group) {
                //             $query->where('roles.group','like','%'.$mini_group.'%')
                //                 ->orWhere('roles.mini_group','like','%'.$mini_group.'%');
                //         })
                //         ->first();
                        // ->where('roles.name','like','%Manager%')
                        // ->where('mini_group','like','%'. $mini_group .'%')
                        // ->first();
            // } else{
            //     $group = $cek_role_pk->group;
            //     $atasan_pk =  $atasan_pk
            //             ->where('roles.name','like','VP%')
            //             ->where('group','like','%'. $group .'%')
            //             ->first();
            // } 
        }
        $installed_date = DB::table('tb_asset_management_detail')
                        ->select('installed_date')
                        ->where('tb_asset_management_detail.id_asset',$id_asset) //request->
                        ->where('tb_asset_management_detail.id',$id_detail_asset)->first()->installed_date; //request->
        
        $pdf = PDF::loadView('asset_management.berita_acara_pdf',compact('pihak_pertama','pihak_kedua','atasan_pk','atasan_pp','list_asset_request','installed_date'));
        $fileName = 'bast.pdf';
        $nameFileFix = str_replace(' ', '_', $fileName);

        // return $pdf->stream($nameFileFix); 
        return $pdf->output(); 

    }

    public function uploadPdfBAST($id_asset,$filePath)
    {
        $client = $this->getClient();
        $service = new Google_Service_Drive($client);

        $data = DB::table('tb_asset_management')
                ->join('tb_asset_management_detail','tb_asset_management_detail.id_asset','=','tb_asset_management.id')
                ->select('users.name as name_pk','tb_asset_management.category','parent_id_drive','tb_asset_management_detail.id','tb_asset_management.id_asset')
                ->join('users','users.nik','=','tb_asset_management_detail.pic')
                ->join('role_user','role_user.user_id','=','users.nik')
                ->join('roles','roles.id','=','role_user.role_id')
                ->where('tb_asset_management.id',$id_asset)
                ->orderBy('tb_asset_management_detail.id','desc')
                ->groupBy('tb_asset_management_detail.id',
                    'tb_asset_management.id_asset',
                    'category',
                    'name_pk',
                    'parent_id_drive')->first(); 

        $fileName  = 'BAST_'. $data->category . '_' . $data->name_pk . '.pdf';

        if ($data->parent_id_drive == null) {
            $parentID = $this->googleDriveMakeFolder($id_asset);
        } else {
            $parentID = [];
            $parent_id = explode('"', $data->parent_id_drive)[1];
            array_push($parentID,$parent_id);
        }

        $update_parent = AssetMgmt::where('id', $id_asset)->first();
        $update_parent->parent_id_drive = $parentID;
        $update_parent->save(); 

        $file = new Google_Service_Drive_DriveFile();
        $file->setName($fileName);
        $file->setParents($parentID);

        $result = $service->files->create(
            $file, 
            array(
                'data' => $filePath,
                'mimeType' => 'application/pdf',
                'uploadType' => 'multipart',
                'supportsAllDrives' => true
            )
        );
        $fileId = $result->id;

        $storeDoc                       = new AssetMgmtDocument();
        $storeDoc->id_detail_asset      = $data->id;
        $storeDoc->document_name        = "Berita Acara ".$data->id_asset;
        $storeDoc->document_location    = "Asset Management/".$data->id_asset.'/';
        $storeDoc->link_drive           = "https://drive.google.com/file/d/{$fileId}/view?usp=drivesdk";
        // $storeDoc->link_drive           = $this->googleDriveUploadCustom($fileName,$filePath,$parentID);
        $storeDoc->save();
    }
    
    public function getPdfBASTPengembalian($id_asset, $id_detail_asset)
    {
        $getIdAsset = DB::table('tb_asset_management_detail')
                    ->where('id_asset',$id_asset) // request->
                    ->where('pic','!=',null)
                    ->orderBy('id','desc')
                    ->first();
        
        $pihak_pertama = User::select('users.name','users.nik','roles.name as departement','phone','ttd','date_of_entry as entry_date')
                        ->join('role_user','role_user.user_id','=','users.nik')
                        ->join('roles','roles.id','=','role_user.role_id')

                        ->where('nik',Auth::User()->nik)
                        ->first(); 

        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')
                    ->select('name', 'roles.group','roles.mini_group')
                    ->where('user_id', Auth::User()->nik)
                    ->first(); 

        if (stripos($cek_role->name, 'Manager') !== false) {
            $atasan_pp = User::select('users.name','users.nik','roles.name as departement','phone','ttd')
                        ->join('role_user','role_user.user_id','=','users.nik')
                        ->join('roles','roles.id','=','role_user.role_id')
                        ->where('roles.group','Supply Chain, CPS & Asset Management')
                        ->where('roles.name','like','VP%')
                        ->first();
        }else{
            $atasan_pp = User::select('users.name','users.nik','roles.name as departement','phone','ttd')
                        ->join('role_user','role_user.user_id','=','users.nik')
                        ->join('roles','roles.id','=','role_user.role_id')
                        ->where('roles.mini_group','Supply Chain & IT Support')
                        ->where('roles.name','like','%Manager%')
                        ->first(); 
        }

        $pihak_pertama->atasan = $atasan_pp->name;

        $getId = AssetMgmt::join('tb_asset_management_detail','tb_asset_management_detail.id_asset','tb_asset_management.id')
                ->select('tb_asset_management_detail.id_asset','detail_lokasi','tb_asset_management_detail.id');
        
        $getLastId = DB::query()
                    ->fromSub($getId, 'temp')
                    ->groupBy('id_asset')
                    ->selectRaw('MAX(`temp`.`id`) as `id_last_asset`');

        $list_asset_request = DB::table($getLastId, 'temp2')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')
                    ->join('tb_asset_management', 'tb_asset_management.id', '=', 'tb_asset_management_detail.id_asset')
                    ->select('tb_asset_management.id','category','serial_number',DB::raw('CONCAT(vendor, " - ",type_device) AS merk'), 'notes', DB::raw("(CASE WHEN (accessoris is null or accessoris = 'undefined') THEN '-' ELSE accessoris END) as accessoris"), 'spesifikasi')
                    ->where('tb_asset_management.id',$id_asset) //request->
                    ->get();

        $cek_role_pk = DB::table('tb_asset_management_detail')
                        ->join('users','users.nik','=','tb_asset_management_detail.pic')
                        ->join('role_user','role_user.user_id','=','users.nik')
                        ->join('roles','roles.id','=','role_user.role_id')
                        ->select('tb_asset_management_detail.id','tb_asset_management_detail.id_asset','roles.name','roles.group','users.name as name_pk','roles.group','ttd','users.nik','users.date_of_entry as entry_date','users.phone','installed_date', 'users.id_territory')
                        ->where('tb_asset_management_detail.id_asset',$id_asset) //request->
                        ->where('tb_asset_management_detail.id',$getIdAsset->id); 
        
        $pihak_kedua = $cek_role_pk->first();
        $roleName = $pihak_kedua->name;
        $ppGroup = $pihak_kedua->group;
        $territory_sales = $pihak_kedua->id_territory;

        // if ($cek_role_pk->where('roles.name','like','%Manager%')->first()) {
        //     $group = $cek_role_pk->first()->group;

        //     $atasan_pk = User::select('users.name','users.nik','roles.mini_group as departement','phone')
        //                 ->join('role_user','role_user.user_id','=','users.nik')
        //                 ->join('roles','roles.id','=','role_user.role_id')
        //                 ->where('group','like','%'.$group.'%')
        //                 ->where('roles.name','like','VP%')
        //                 ->first(); 

        // }else if ($cek_role_pk->where('roles.name','like','VP%')->first()) {
        //     $atasan_pk = User::select('users.name','users.nik','roles.mini_group as departement','phone')
        //                 ->join('role_user','role_user.user_id','=','users.nik')
        //                 ->join('roles','roles.id','=','role_user.role_id')
        //                 ->where('roles.name','like','%Chief Operating Officer%')
        //                 ->first();
        // }
        if (strpos($roleName, 'VP Sales') !== false ) {
            $atasan_pk = User::select('users.name','users.nik','roles.name as departement','phone')
                ->join('role_user','role_user.user_id','=','users.nik')
                ->join('roles','roles.id','=','role_user.role_id')
                ->where('roles.name','like', '%Chief Executive Officer%')
                ->whereNotNull('user_id')
                ->where('status_karyawan','!=','dummy')
                ->first();
        }
        else if (strpos($roleName, 'Account Executive') !== false ) {
            $atasan_pk = User::select('users.name','users.nik','roles.name as departement','phone')
                ->join('role_user','role_user.user_id','=','users.nik')
                ->join('roles','roles.id','=','role_user.role_id')
                ->where('roles.name','like', '%VP Sales%')
                ->where('id_territory','like','%'.$territory_sales.'%')
                ->whereNotNull('user_id')
                ->where('status_karyawan','!=','dummy')
                ->first();
        }
        else if (strpos($roleName, 'VP Financial & Accounting') !== false ) {
            $atasan_pk = User::select('users.name','users.nik','roles.name as departement','phone')
                ->join('role_user','role_user.user_id','=','users.nik')
                ->join('roles','roles.id','=','role_user.role_id')
                ->where('roles.name','like', '%Chief Executive Officer%')
                ->whereNotNull('user_id')
                ->where('status_karyawan','!=','dummy')
                ->first();
        }
        else if (strpos($roleName, 'VP') === 0 ) {
            $atasan_pk = User::select('users.name','users.nik','roles.name as departement','phone')
                ->join('role_user','role_user.user_id','=','users.nik')
                ->join('roles','roles.id','=','role_user.role_id')
                ->where('roles.name','like', '%Chief Operating Officer%')
                ->whereNotNull('user_id')
                ->where('status_karyawan','!=','dummy')
                ->first();

        }
        else if (strpos($roleName, 'Delivery Project Manager') !== false) {
            $atasan_pk = User::select('users.name','users.nik','roles.mini_group as departement','phone')
                            ->join('role_user','role_user.user_id','=','users.nik')
                            ->join('roles','roles.id','=','role_user.role_id')
                            ->where('group','like','%'.$ppGroup.'%')
                            ->where('roles.name','like','%Project Management Manager%')
                            ->first(); 
        }
        else if (strpos($roleName, 'Renumeration, Personalia & GS Manager') === 0) {
            $atasan_pk = User::select('users.name','users.nik','roles.mini_group as departement','phone')
                            ->join('role_user','role_user.user_id','=','users.nik')
                            ->join('roles','roles.id','=','role_user.role_id')
                            ->where('roles.name','like','VP Program & Project Management')
                            ->first(); 
        }  
        else if (substr($roleName, -7) === 'Manager') {
            $atasan_pk = User::select('users.name','users.nik','roles.mini_group as departement','phone')
                            ->join('role_user','role_user.user_id','=','users.nik')
                            ->join('roles','roles.id','=','role_user.role_id')
                            ->where('group','like','%'.$ppGroup.'%')
                            ->where('roles.name','like','VP%')
                            ->first(); 
        } 
        else if ($roleName === "Chief Executive Officer") {
            $atasan_pk = (object) [
                'name'       => '-',
                'nik'        => '',
                'departement'=> '',
                'phone'      => '',
            ];

        } 
        else if (substr($roleName, -8) === 'Director') {
            $atasan_pk = User::select('users.name','users.nik','roles.mini_group as departement','phone')
                            ->join('role_user','role_user.user_id','=','users.nik')
                            ->join('roles','roles.id','=','role_user.role_id')
                            ->where('roles.name','like','Chief Executive Officer')
                            ->first(); 
        } 
        else {

            $cek_role_pk = DB::table('tb_asset_management')
                        ->join('tb_asset_management_detail', 'tb_asset_management_detail.id_asset', '=', 'tb_asset_management.id')
                        ->select('tb_asset_management.id', 'roles.name', 'roles.group', 'users.name as name_pk', 'roles.mini_group', 'ttd')
                        ->join('users', 'users.nik', '=', 'tb_asset_management_detail.pic')
                        ->join('role_user', 'role_user.user_id', '=', 'users.nik')
                        ->join('roles', 'roles.id', '=', 'role_user.role_id')
                        ->where('tb_asset_management_detail.id_asset',$id_asset) //request->
                        ->where('tb_asset_management_detail.id',$getIdAsset->id) 
                        ->first();

            $atasan_pk = User::select('users.name','users.nik','roles.mini_group as departement','phone')
                        ->join('role_user','role_user.user_id','=','users.nik')

                        ->join('roles','roles.id','=','role_user.role_id');
            
            $mini_group = $cek_role_pk->mini_group;
            if ($mini_group === 'Human Capital') {

                $atasan_pk = $atasan_pk
                        ->where('roles.name','like','VP Program & Project Management')
                        ->first();
            }
            else if ($mini_group == "" or $mini_group = null) {
                $group = $cek_role_pk->group;
                $atasan_pk =  $atasan_pk
                        ->where('roles.name','like','%Manager%')
                        ->where('roles.name','<>','Delivery Project Manager')
                        ->where('group','like','%'. $group .'%')
                        ->first();

                // $mini_group = $cek_role_pk->group;
                // $isManagerOnMiniGroup = User::select('users.name','users.nik','roles.mini_group as departement','phone')
                //                 ->join('role_user','role_user.user_id','=','users.nik')
                //                 ->join('roles','roles.id','=','role_user.role_id')
                //                 ->where('roles.name','like','%Manager%')
                //                 ->where('roles.name','<>','Delivery Project Manager')
                //                 ->where('roles.group','like','%'.$mini_group.'%')
                //                 ->first();

                // if ($isManagerOnMiniGroup) {
                //     $atasan_pk = $atasan_pk
                //             ->where('roles.name','like','%Manager%')
                //             ->where('roles.name','<>','Delivery Project Manager')
                //             ->where('roles.mini_group','like','%'.$mini_group.'%')
                //             ->first();
                // } else{
                //     $group = $cek_role_pk->group;
                //     $atasan_pk =  $atasan_pk
                //             ->where('roles.name','like','VP%')
                //             ->where('group','like','%'. $group .'%')
                //             ->first();
                // } 
            } 
            else {
                $mini_group = $cek_role_pk->mini_group;
                $isManagerOnMiniGroup = User::select('users.name','users.nik','roles.mini_group as departement','phone')
                                ->join('role_user','role_user.user_id','=','users.nik')
                                ->join('roles','roles.id','=','role_user.role_id')
                                ->where('roles.name','like','%Manager%')
                                ->where('roles.name','<>','Delivery Project Manager')
                                ->where('roles.mini_group','like','%'.$mini_group.'%')
                                ->first();
        
                if ($isManagerOnMiniGroup) {
                    $atasan_pk = $atasan_pk
                            ->where('roles.name','like','%Manager%')
                            ->where('roles.name','<>','Delivery Project Manager')
                            ->where('roles.mini_group','like','%'.$mini_group.'%')
                            ->first();
                } else{
                    $group = $cek_role_pk->group;
                    $atasan_pk =  $atasan_pk
                            ->where('roles.name','like','VP%')
                            ->where('group','like','%'. $group .'%')
                            ->first();
                } 
            }
            // else if (empty($mini_group)) {
            //     $mini_group = $cek_role_pk->group;
            // } 
            
            // $isManagerOnMiniGroup = User::select('users.name','users.nik','roles.mini_group as departement','phone')
            //                     ->join('role_user','role_user.user_id','=','users.nik')
            //                     ->join('roles','roles.id','=','role_user.role_id')
            //                     ->where('roles.name','like','%Manager%')
            //                     ->where('roles.name','<>','Delivery Project Manager')
            //                     ->where(function($query) use ($mini_group) {
            //                         $query->where('roles.group','like','%'.$mini_group.'%')
            //                               ->orWhere('roles.mini_group','like','%'.$mini_group.'%');
            //                     })
            //                     ->first();
            
            // $atasan_pk = $atasan_pk
            //                     ->where('roles.name','like','%Manager%')
            //                     ->where('roles.name','<>','Delivery Project Manager')
            //                     ->where(function($query) use ($mini_group) {
            //                         $query->where('roles.group','like','%'.$mini_group.'%')
            //                             ->orWhere('roles.mini_group','like','%'.$mini_group.'%');
            //                     })
            //                     ->first();


            // $mini_group = $cek_role_pk->mini_group;
            // if ($mini_group === 'Human Capital') {
            //     $mini_group = 'Renumeration, Personalia & GS';
            // }

            // $isManagerOnMiniGroup = User::select('users.name','users.nik','roles.mini_group as departement','phone')
            //                         ->join('role_user','role_user.user_id','=','users.nik')
            //                         ->join('roles','roles.id','=','role_user.role_id')
            //                         ->where('roles.name','like','%Manager%')
            //                         ->where('roles.name','<>','Delivery Project Manager')
            //                         ->where('roles.mini_group','like','%'.$mini_group.'%')
            //                         ->first();

            // $atasan_pk = User::select('users.name','users.nik','roles.mini_group as departement','phone')
            //             ->join('role_user','role_user.user_id','=','users.nik')
            //             ->join('roles','roles.id','=','role_user.role_id');

            // if ($isManagerOnMiniGroup) {
            //     $mini_group = $cek_role_pk->mini_group;
            //     $atasan_pk = $atasan_pk
            //             ->where('roles.name','like','%Manager%')
            //             ->where('mini_group','like','%'. $mini_group .'%')
            //             ->first();
            // }else{
            //     $group = $cek_role_pk->group;
            //     $atasan_pk =  $atasan_pk
            //             ->where('roles.name','like','VP%')
            //             ->where('group','like','%'. $group .'%')
            //             ->first();
            // }
        }

        $installed_date = DB::table('tb_asset_management_detail')
                        ->select('installed_date')
                        ->where('tb_asset_management_detail.id_asset',$id_asset) //request->
                        ->where('tb_asset_management_detail.id',$getIdAsset->id)->first()->installed_date; 

        //Original data
        $original_pp = clone $pihak_pertama; 
        $original_pp_atasan = clone $atasan_pp; 
        $original_pk = clone $pihak_kedua;
        $original_pk_atasan = clone $atasan_pk;

        //Swap pihak_kedua as pihak_pertama
        $pihak_pertama->name = $original_pk->name_pk;
        $pihak_pertama->nik = $original_pk->nik;
        $pihak_pertama->departement = $original_pk->name;
        $pihak_pertama->phone = $original_pk->phone;
        $pihak_pertama->entry_date = $original_pk->entry_date;
        $pihak_pertama->ttd = $original_pk->ttd;
        $atasan_pp->name = $original_pk_atasan->name;

        //pihak_pertama as pihak_kedua
        $pihak_kedua->name_pk = $original_pp->name; 
        $pihak_kedua->nik = $original_pp->nik;
        $pihak_kedua->name = $original_pp->departement;
        $pihak_kedua->phone = $original_pp->phone;
        $pihak_kedua->ttd = $original_pp->ttd;             
        $atasan_pk->name = $original_pp_atasan->name;

        $pdf = PDF::loadView('asset_management.berita_pengembalian_pdf',compact('pihak_pertama','pihak_kedua','atasan_pk','atasan_pp','list_asset_request','installed_date'));
        $fileName = 'bastpengembalian.pdf';
        $nameFileFix = str_replace(' ', '_', $fileName);

        return $pdf->output();
        //return $pdf->stream($nameFileFix);
    }

    public function uploadPdfBASTPengembalian($id_asset,$filePath)
    {
        $client = $this->getClient();
        $service = new Google_Service_Drive($client);

        $data = DB::table('tb_asset_management')
                ->join('tb_asset_management_detail','tb_asset_management_detail.id_asset','=','tb_asset_management.id')
                ->select('users.name as name_pk','tb_asset_management.category','parent_id_drive','tb_asset_management_detail.id','tb_asset_management.id_asset')
                ->join('users','users.nik','=','tb_asset_management_detail.pic')
                ->join('role_user','role_user.user_id','=','users.nik')
                ->join('roles','roles.id','=','role_user.role_id')
                ->where('tb_asset_management.id',$id_asset)
                ->orderBy('tb_asset_management_detail.id','desc')
                ->groupBy('tb_asset_management_detail.id',
                    'tb_asset_management.id_asset',
                    'category',
                    'name_pk',
                    'parent_id_drive')->first(); 

        $fileName  = 'BAST_Pengembalian_'. $data->category . '_' . $data->name_pk . '.pdf';

        if ($data->parent_id_drive == null) {
            $parentID = $this->googleDriveMakeFolder($id_asset);
        } else {
            $parentID = [];
            $parent_id = explode('"', $data->parent_id_drive)[1];
            array_push($parentID,$parent_id);
        }

        $update_parent = AssetMgmt::where('id', $id_asset)->first();
        $update_parent->parent_id_drive = $parentID;
        $update_parent->save(); 

        $file = new Google_Service_Drive_DriveFile();
        $file->setName($fileName);
        $file->setParents($parentID);

        $result = $service->files->create(
            $file, 
            array(
                'data' => $filePath,
                'mimeType' => 'application/pdf',
                'uploadType' => 'multipart',
                'supportsAllDrives' => true
            )
        );
        $fileId = $result->id;

        $storeDoc                       = new AssetMgmtDocument();
        $storeDoc->id_detail_asset      = $data->id;
        $storeDoc->document_name        = "Berita Acara Pengembalian ".$data->id_asset;
        $storeDoc->document_location    = "Asset Management/".$data->id_asset.'/';
        $storeDoc->link_drive           = "https://drive.google.com/file/d/{$fileId}/view?usp=drivesdk";
        $storeDoc->save();
    }

    public function generatePdf(Request $request)
    {
        // Fetch multiple products or any other items (use any collection of data)
        $products = AssetMgmt::join('tb_asset_management_detail','tb_asset_management.id','=','tb_asset_management_detail.id_asset')
                ->select('tb_asset_management.id','type_device','tb_asset_management.id_asset')
                ->whereYear('tb_asset_management.created_at',date('Y')); // Fetch all products or you can limit the number based on your needs

        if (isset($request->assetOwner)) {
            $products = $products->where('tb_asset_management.asset_owner',$request->assetOwner);
        }

        if (isset($request->category)) {
            $products = $products->where('tb_asset_management.category',$request->category);
        }

        if (isset($request->client)) {
            $products = $products->where('tb_asset_management_detail.client',$request->client);
        }

        if (isset($request->pid)) {
            $products = $products->where('tb_asset_management_detail.pid',$request->pid);
        }

        $products = $products->get();

        $qrCodes = collect();

        foreach ($products as $product) {
            // Generate the QR code as an image
            $qrCodeImage = QrCode::size(75)->generate(url('asset/detail') . '?id_asset=' . $product->id);
            $qrCodes->push(['product' => $product, 'qrCode' => $qrCodeImage]);
        }

        // Pass the QR codes array to the PDF view
        $pdf = PDF::loadView('asset_management.qr-codes', ['qrCodes' => $qrCodes]);

        // Return the generated PDF as a download
        return $pdf->download('qr-codes.pdf');
    }
}
