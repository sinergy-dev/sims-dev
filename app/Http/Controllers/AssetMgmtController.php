<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AssetMgmt;
use App\AssetMgmtDetail;
use App\AssetMgmtLog;
use App\AssetMgmtAssign;
use App\SalesProject;
use App\AssetMgmtAssignEngineer;
use App\TicketingDetail;
use App\Mail\MailReminderMaintenanceEndAsset;
use App\AssetMgmtServicePoint;
use App\TB_Contact;
use App\AssetMgmtCategory;
use App\AssetMgmtScheduling;
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
        return view('asset_management/dashboard')->with(['initView'=> $this->initMenuBase(),'feature_item'=>$this->RoleDynamic('asset_mgmt')]);
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

        $data = DB::table($getLastId, 'temp2')->join('tb_asset_management','tb_asset_management.id','temp2.id_asset')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')
            ->select('tb_asset_management_detail.pid','asset_owner','category','category_peripheral','tb_asset_management.id_asset','type_device','vendor','status','rma','spesifikasi','serial_number','notes','tb_asset_management.id','id_device_customer')
            // ->where('category_peripheral','-')
            ->orderBy('tb_asset_management.created_at','desc'); 

        $searchFields = ['asset_owner', 'tb_asset_management_detail.pid', 'serial_number', 'tb_asset_management.id_asset', 'type_device', 'vendor', 'rma', 'spesifikasi','notes','id_device_customer'];

        if ($cek_role->mini_group == 'Center Point & Asset Management SVC ' || $cek_role->name_role == 'VP Supply Chain, CPS & Asset Management' || $cek_role->name_role == 'Operations Director') {
            $data = $data;
        } else if ($cek_role->name_role == 'Engineer on Site' ) {
            $data = $data->whereIn('pid',$getPid);
        } elseif ($cek_role->name_role == 'Project Manager' || $cek_role->name_role == 'Project Coordinator') {
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
        $order = ["PSIP"];

        $data = TB_Contact::select('code as id', 'code as text')
            ->where('code', 'like', '%' . request('q') . '%')
            ->distinct()
            ->get();

        $data = $data->sortBy(function ($item) use ($order) {
            return array_search($item->id, $order) === false ? 1 : 0;
        })->values();

        // return $data;


        // $newObject = (object) ['id' => 'SIP', 'text' => 'SIP'];
        $data = $data->toArray();
        $newObject2 = (object) ['id' => 'DIST', 'text' => 'DIST'];
        $newObject3 = (object) ['id'=>'PRIN','text'=>'PRIN'];

        array_unshift($data, $newObject3);
        array_unshift($data, $newObject2);
        // array_unshift($data, $newObject);

        // $data->push($newObject)->push($newObject2)->push($newObject3);

        return response()->json($data);
    }

    public function getDataAsset()
    {
        $nik = Auth::User()->nik;
        $cek_role = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role','group','mini_group')->where('user_id',$nik)->first();

        $getPid = DB::table('ticketing__user')->where('nik',Auth::User()->nik)->get()->pluck('pid');
        $getPidPm = DB::table('tb_pmo')->join('tb_pmo_assign','tb_pmo_assign.id_project','tb_pmo.id')->join('role_user','role_user.user_id','tb_pmo_assign.nik')->join('roles','roles.id','role_user.role_id')->where('nik',Auth::User()->nik)->where('name','!=','Asset Management')->get()->pluck('project_id');

        $getId = AssetMgmt::join('tb_asset_management_detail','tb_asset_management_detail.id_asset','tb_asset_management.id')->select('tb_asset_management_detail.id_asset','detail_lokasi','tb_asset_management_detail.id');
        $getLastId = DB::table($getId,'temp')->groupBy('id_asset')->selectRaw('MAX(`temp`.`id`) as `id_last_asset`')->selectRaw('id_asset');

        $data = DB::table($getLastId, 'temp2')->join('tb_asset_management','tb_asset_management.id','temp2.id_asset')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')
            ->select('tb_asset_management_detail.pid','asset_owner','category','category_peripheral','tb_asset_management.id_asset','type_device','vendor','status','rma','spesifikasi','serial_number','notes','tb_asset_management.id','id_device_customer')
            // ->where('category_peripheral','-')
            ->orderBy('tb_asset_management.created_at','desc'); 

        if ($cek_role->mini_group == 'Center Point & Asset Management SVC ' || $cek_role->name_role == 'VP Supply Chain, CPS & Asset Management' || $cek_role->name_role == 'Operations Director') {
            $data = $data;
        } else if ($cek_role->name_role == 'Engineer on Site' ) {
            $data = $data->whereIn('pid',$getPid);
        } elseif ($cek_role->name_role == 'Project Manager' || $cek_role->name_role == 'Project Coordinator') {
            $data = $data->whereIn('pid',$getPidPm);
        }

        return array("data"=>$data->get());
    }

    public function getVendor(Request $request)
    {
        $data = DB::table('tb_asset_management')->select('vendor as id','vendor as text')->where('vendor','!=',null)->where('vendor','like','%'.request('q').'%')->distinct()->get();

        return $data;
    }

    public function getTypeDevice(Request $request)
    {
        $data = DB::table('tb_asset_management')->select('type_device as id','type_device as text')->where('type_device','!=',null)->where('type_device','like','%'.request('q').'%')->distinct()->get();

        return $data;
    }

    public function getCategoryPeripheral(Request $request)
    {
        $data = DB::table('tb_asset_management')->select('category_peripheral as id','category_peripheral as text')->where('category_peripheral','!=',null)->where('category_peripheral','!=','-')->where('category_peripheral','like','%'.request('q').'%')->distinct()->get();

        return $data;
    }

    public function getLevelSupport(Request $request)
    {
        $data = DB::table('tb_asset_management_detail')->select('second_level_support as id','second_level_support as text')->where('second_level_support','!=',null)->where('second_level_support','like','%'.request('q').'%')->distinct()->get();

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

        $data = DB::table($getLastId, 'temp2')->join('tb_asset_management','tb_asset_management.id','temp2.id_asset')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')->join('tb_asset_management_category','tb_asset_management_category.name','tb_asset_management.category')
            ->select('tb_asset_management_detail.pid','asset_owner','category','category_peripheral','tb_asset_management.id_asset','type_device','vendor','status','rma','spesifikasi','serial_number','notes','related_id_asset','tb_asset_management.id','id_device_customer')->orderBy('tb_asset_management.created_at','desc'); 

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

        if ($cek_role->mini_group == 'Center Point & Asset Management SVC ' || $cek_role->name_role == 'VP Supply Chain, CPS & Asset Management' || $cek_role->name_role == 'Operations Director') {
            $data = $data;
        } else if ($cek_role->name_role == 'Engineer on Site' ) {
            $data = $data->whereIn('pid',$getPid);
        } elseif ($cek_role->name_role == 'Project Manager' || $cek_role->name_role == 'Project Coordinator') {
            $data = $data->whereIn('pid',$getPidPm);
        } else if ($cek_role->name_role == 'Managed Service Manager' ) {
            $data = $data->where('pid','!=','INTERNAL');
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

        if ($cek_role->mini_group == 'Center Point & Asset Management SVC ' || $cek_role->name_role == 'VP Supply Chain, CPS & Asset Management' || $cek_role->name_role == 'Operations Director') {
            $getClient = $getClient;
        } else if ($cek_role->name_role == 'Engineer on Site' ) {
            $getClient = $getClient->whereIn('pid',$getPid);
        } elseif ($cek_role->name_role == 'Project Manager' || $cek_role->name_role == 'Project Coordinator') {
            $getClient = $getClient->whereIn('pid',$getPidPm);
        } else if ($cek_role->name_role == 'Managed Service Manager' ) {
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
        $store->status = $request->status;
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
        $store->save();

        // if ($request->typeAsset == 'asset') {
        // } else if ($request->typeAsset == 'peripheral') {

        if (isset($request->assignTo)) {

            $getId = AssetMgmt::join('tb_asset_management_detail','tb_asset_management_detail.id_asset','tb_asset_management.id')->select('tb_asset_management_detail.id_asset','detail_lokasi','tb_asset_management_detail.id');
            $getLastId = DB::table($getId,'temp')->groupBy('id_asset')->selectRaw('MAX(`temp`.`id`) as `id_last_asset`')->selectRaw('id_asset');

            $data = DB::table($getLastId, 'temp2')->join('tb_asset_management','tb_asset_management.id','temp2.id_asset')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')
                ->select('tb_asset_management_detail.id_asset','id_device_customer','client','pid','kota','alamat_lokasi','detail_lokasi','ip_address','server','port','status_cust','second_level_support','operating_system','version_os','installed_date','license','license_end_date','license_start_date','maintenance_end','maintenance_start','latitude','longitude','service_point')
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

            if ($request->licenseStartDate == 'Invalid date') {
                $storeDetail->maintenance_start = null;
            } else {
                $storeDetail->maintenance_start = $request->maintenanceStart;
            }

            if ($request->licenseStartDate == 'Invalid date') {
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

        if (isset($request->inputDoc)) {
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

    public function getPid(Request $request)
    {
        $nik = Auth::User()->nik;
        $cek_role = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role','group','mini_group')->where('user_id',$nik)->first();

        $getPid = DB::table('ticketing__user')->where('nik',Auth::User()->nik)->get()->pluck('pid');
        $getPidPm = DB::table('tb_pmo')->join('tb_pmo_assign','tb_pmo_assign.id_project','tb_pmo.id')->join('role_user','role_user.user_id','tb_pmo_assign.nik')->join('roles','roles.id','role_user.role_id')->where('nik',Auth::User()->nik)->where('name','!=','Asset Management')->get()->pluck('project_id');

        $getAllPid = SalesProject::join('sales_lead_register', 'sales_lead_register.lead_id', '=', 'tb_id_project.lead_id')->join('users', 'users.nik', '=', 'sales_lead_register.nik')->select('id_project as id',DB::raw("CONCAT(`id_project`,' - ',`name_project`) AS text"))->where('id_company', '1')->where('id_project','like','%'.request('q').'%')->orderBy('tb_id_project.created_at','desc');

        if ($cek_role->mini_group == 'Center Point & Asset Management SVC ' || $cek_role->name_role == 'VP Supply Chain, CPS & Asset Management' || $cek_role->name_role == 'Operations Director') {
            $getAllPid = $getAllPid->get();
            $getAllPid = $getAllPid->push((object)(['id' => 'INTERNAL','text' => 'INTERNAL']));
        } else if ($cek_role->name_role == 'Engineer on Site' ) {
            $getAllPid = $getAllPid->whereIn('id_project',$getPid)->get();
        } elseif ($cek_role->name_role == 'Project Manager' || $cek_role->name_role == 'Project Coordinator') {
            $getAllPid = $getAllPid->whereIn('id_project',$getPidPm)->get();
        } elseif ($cek_role->name_role == 'Managed Service Manager') {
            $getAllPid = $getAllPid->get();
        }

        return response()->json($getAllPid);
    }

    public function updateAsset(Request $request)
    {
        $update = AssetMgmt::where('id',$request->id_asset)->first();
        if ($update->asset_owner != $request->assetOwner) {

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

            $id =  $request->assetOwner . '-' . $cat . '-' . date('m') . date('y') . '-' . $nomor;

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

        if ($update->status != $request->status) {
            $storeLog = new AssetMgmtLog();
            $storeLog->id_asset = $request->id_asset;
            $storeLog->operator = Auth::User()->name;
            $storeLog->date_add = Carbon::now()->toDateTimeString();
            $storeLog->activity = 'Update Asset with status ' .$update->status. ' to ' . $request->status;
            $storeLog->save();
        }
        $update->status = $request->status;


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

        $update->save();

        if ($request->status != 'Installed') {
            $delete = AssetMgmtAssign::where('id_asset_peripheral',$request->id_asset)->delete();
        }

        if (isset($request->engineer)) {
            $store = new AssetMgmtAssignEngineer();
            $store->id_asset = $request->id_asset;
            $store->engineer_atm = $request->engineer;
            $store->date_add = Carbon::now()->toDateTimeString();
            $store->save();

            $storeLog = new AssetMgmtLog();
            $storeLog->id_asset = $request->id_asset;
            $storeLog->operator = Auth::User()->name;
            $storeLog->date_add = Carbon::now()->toDateTimeString();
            $storeLog->activity = 'Assign Engineer ' .$request->engineer. ' to asset ' . AssetMgmt::where('id',$request->id_asset)->first()->id_asset;
            $storeLog->save();
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
        $update = AssetMgmtDetail::where('id_asset',$request->id_asset)->orderby('id','desc')->first();
        $storeDetail = new AssetMgmtDetail();
        $storeDetail->id_asset = $request->id_asset;

        if ($update->id_device_customer != $request->idDeviceCustomer) {
            $storeLog = new AssetMgmtLog();
            $storeLog->id_asset = $request->id_asset;
            $storeLog->operator = Auth::User()->name;
            $storeLog->date_add = Carbon::now()->toDateTimeString();
            $storeLog->activity = 'Update Detail Asset with ID Device Customer ' .$update->id_device_customer. ' to ' . $request->idDeviceCustomer;
            $storeLog->save();
        }
        $storeDetail->id_device_customer = $request->idDeviceCustomer;

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

        if ($update->operating_system != $request->operatingSystem) {
            $storeLog = new AssetMgmtLog();
            $storeLog->id_asset = $request->id_asset;
            $storeLog->operator = Auth::User()->name;
            $storeLog->date_add = Carbon::now()->toDateTimeString();
            $storeLog->activity = 'Update Detail Asset with Operating System ' .$update->operating_system. ' to ' . $request->operatingSystem;
            $storeLog->save();
        }
        $storeDetail->operating_system = $request->operatingSystem;

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

        if ($update->client != $request->client) {
            $storeLog = new AssetMgmtLog();
            $storeLog->id_asset = $request->id_asset;
            $storeLog->operator = Auth::User()->name;
            $storeLog->date_add = Carbon::now()->toDateTimeString();
            $storeLog->activity = 'Update Detail Asset with Client ' .$update->client. ' to ' . $request->client;
            $storeLog->save();
        }
        $storeDetail->client = $request->client;
        $storeDetail->date_add = Carbon::now()->toDateTimeString();
        $storeDetail->related_id_asset = $update->related_id_asset;
        $storeDetail->save();

        $id = AssetMgmt::where('id',$storeDetail->id_asset)->first()->id_asset;

        if (isset($request->inputDoc)) {
            $directory = "Asset Management/";
            $get_parent_drive = AssetMgmt::where('id', $storeDetail->id_asset)->first();
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
                $updateDetail->document_name             = 'Bukti Asset '. $id;
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
                $parentID = $this->googleDriveMakeFolder($storeDetail->id_asset);
            } else {
                $parentID = [];
                $parent_id = explode('"', $get_parent_drive->parent_id_drive)[1];
                array_push($parentID,$parent_id);
            }

            $updateDetail->document_location         = "Asset/Bukti Asset " . $id;
            // $updateDetail->document_location         = "PMO/" . $pdf_name;
            $updateDetail->link_drive = $this->googleDriveUploadCustom($pdf_name,$directory . $pdf_name,$parentID);
            $updateDetail->save();
        }
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
        $getId = AssetMgmt::join('tb_asset_management_detail','tb_asset_management_detail.id_asset','tb_asset_management.id')->select('tb_asset_management_detail.id_asset','detail_lokasi','tb_asset_management_detail.id');
        $getLastId = DB::table($getId,'temp')->groupBy('id_asset')->selectRaw('MAX(`temp`.`id`) as `id_last_asset`');

        $getAll = DB::table($getLastId, 'temp2')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')->join('tb_asset_management', 'tb_asset_management.id', '=', 'tb_asset_management_detail.id_asset')->leftJoin('tb_asset_management_assign_engineer','tb_asset_management.id','tb_asset_management_assign_engineer.id_asset')
            ->leftjoin('tb_asset_management_category','tb_asset_management.category','tb_asset_management_category.name')
            ->select('tb_asset_management.id_asset','id_device_customer','client','pid','kota','alamat_lokasi','detail_lokasi','ip_address','server','port','status_cust','second_level_support','operating_system','version_os','installed_date','license','license_end_date','license_start_date','maintenance_end','maintenance_start','notes','rma','spesifikasi','type_device','serial_number','vendor','tb_asset_management_category.id_category as category_code','category as category_text','category_peripheral','asset_owner','related_id_asset',DB::raw("(CASE WHEN (category_peripheral = '-') THEN 'asset' WHEN (category_peripheral != '-') THEN 'peripheral' END) as type"),'status',DB::raw("TIMESTAMPDIFF(HOUR, concat(maintenance_start,' 00:00:00'), concat(maintenance_end,' 00:00:00')) AS slaPlanned"),'engineer_atm','service_point','latitude','longitude','tanggal_pembelian','nilai_buku','harga_beli','tb_asset_management.id','reason_status','link_drive','document_name','document_location')
            ->where('tb_asset_management_detail.id_asset',$request->id_asset)
            ->first();

        $getIdTicket = DB::table('ticketing__detail')->where('serial_device',$getAll->serial_number)->select('id_ticket')->whereBetween('reporting_time', [$getAll->maintenance_start . " 00:00:00", $getAll->maintenance_end . " 23:59:59"])->get()->pluck('id_ticket');

        $timeTrouble = DB::table('ticketing__activity')->join('ticketing__activity as t2','t2.id_ticket','ticketing__activity.id_ticket')->join('ticketing__detail','ticketing__detail.id_ticket','ticketing__activity.id_ticket')
            ->select(DB::raw("TIME_TO_SEC(TIMEDIFF(t2.date, ticketing__activity.date)) AS time_diff_seconds"),
                'ticketing__activity.id_ticket')
            ->where('ticketing__activity.activity', 'OPEN')->where('t2.activity', 'CLOSE')->whereIn('ticketing__activity.id_ticket',$getIdTicket)->get()->SUM('time_diff_seconds');

        $countTicket = DB::table('ticketing__detail')->where('serial_device',$getAll->serial_number)->whereBetween('reporting_time', [$getAll->maintenance_start . " 00:00:00", $getAll->maintenance_end . " 23:59:59"])->count();

        $getData = collect($getAll);

        if ($getAll->category_peripheral == '-' || $getAll->category_peripheral == null) {
            if ($getAll->category_code == 'COM') {
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

        return $getData;
    }

    public function getLog()
    {
        $data = AssetMgmtLog::orderBy('date_add','desc')->get();
        return $data;
    }

    public function getLogById(Request $request)
    {
        // $getLog = DB::table('tb_asset_management_log')
        //     ->where('id_asset', $request->id_asset)->orderby('date_add','desc');

        $getId = AssetMgmtLog::select('id','operator','id_asset')->where('id_asset', $request->id_asset);
        $getLog = DB::table($getId,'temp')->groupBy('id_asset')->selectRaw('MAX(`temp`.`id`) as `id_log`')->selectRaw('id_asset');

        // return $getLog->get();

        // $peripheral = AssetMgmt::select('category_peripheral')->where('id',$request->id_asset)->first();

        $data = DB::table($getLog,'temp')->join('tb_asset_management_detail','tb_asset_management_detail.id_asset','temp.id_asset')->join('tb_asset_management_log','tb_asset_management_log.id','temp.id_log')->leftjoin('tb_asset_management','tb_asset_management.id','tb_asset_management_detail.related_id_asset')
            ->select('client','pid',DB::raw("CONCAT(`detail_lokasi`, ' - ', `alamat_lokasi`, ' - ', `kota`) AS `lokasi`"),DB::raw("CONCAT(`maintenance_start`, ' - ', `maintenance_end`) AS `periode`"),DB::raw("(CASE WHEN (related_id_asset is null) THEN '-' ELSE `tb_asset_management`.`id_asset`  END) as related_id_asset"),'operator')->where('tb_asset_management_detail.id_asset',$request->id_asset)->orderby('tb_asset_management_detail.id','desc')->get();

        // $data = AssetMgmtDetail::leftJoinSub($getLog,'getLog',function($join){
        //             $join->on("tb_asset_management_detail.id_asset","getLog.id_asset");
        //         })->joinSub($getLog,'getLog',function($join){
        //             $join->on("tb_asset_management_detail.id_asset","getLog.id_asset");
        //         })
        //         ->select('client','pid',DB::raw("CONCAT(`detail_lokasi`, ' - ', `alamat_lokasi`, ' - ', `kota`) AS `lokasi`"),DB::raw("CONCAT(`maintenance_start`, ' - ', `maintenance_end`) AS `periode`"),DB::raw("(CASE WHEN (related_id_asset is null) THEN '-' ELSE related_id_asset  END) as related_id_asset"),'operator')->where('tb_asset_management_detail.id_asset',$request->id_asset)->orderby('tb_asset_management_detail.id','desc')->get();

        // $data = AssetMgmtDetail::select('client','pid',DB::raw("CONCAT(`detail_lokasi`, ' - ', `alamat_lokasi`, ' - ', `kota`) AS `lokasi`"),DB::raw("CONCAT(`maintenance_start`, ' - ', `maintenance_end`) AS `periode`"),DB::raw("(CASE WHEN (related_id_asset is null) THEN '-' ELSE related_id_asset  END) as related_id_asset"),DB::raw("(CASE WHEN (related_id_asset is null) THEN '-' END) as operator"))->where('tb_asset_management_detail.id_asset',$request->id_asset)->orderby('tb_asset_management_detail.id','desc')->get();


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

        $getPid = DB::table('tb_asset_management_detail')->select('pid as id','pid as text')->where('pid','like','%'.$code_name.'%')->groupby('pid')->get();

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
            ->groupBy('pid');

        if ($cek_role->mini_group == 'Center Point & Asset Management SVC ' || $cek_role->name_role == 'VP Supply Chain, CPS & Asset Management' || $cek_role->name_role == 'Operations Director') {
            $getPid = $getPid;
        } else if ($cek_role->name_role == 'Engineer on Site' ) {
            $getPid = $getPid->whereIn('pid',$getPidEoS);
        } elseif ($cek_role->name_role == 'Project Manager' || $cek_role->name_role == 'Project Coordinator') {
            $getPid = $getPid->whereIn('pid',$getPidPm);
        } else if ($cek_role->name_role == 'Managed Service Manager' ) {
            $getPid = $getPid->where('pid','!=','INTERNAL');
        } 

        $result = $getPid->get();


        return response()->json($result);
    }

    public function getChartAssetOwner(Request $request)
    {
        $data = AssetMgmt::get();
        $desc = AssetMgmt::select('asset_owner')->where('asset_owner', '!=', null)->groupBy('asset_owner')->get()->pluck('asset_owner');
        $length = $desc->count();

        if (count($data) == 0) {
            $hasil2 = [0,0,0];
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
            $sortedDesc = $desc->toArray();

            if (!$isAllZeros) {
                foreach ($hasil as $key => $value) {
                    $hasil2[$key] = round(($value / $pie) * 100, 2);
                }

                $combined = [];
                foreach ($hasil2 as $key => $value) {
                    $combined[] = [
                        'label' => $desc[$key],
                        'value' => $value
                    ];
                }

                usort($combined, function ($a, $b) {
                    return $b['value'] <=> $a['value'];
                });

                $hasil2 = array_column($combined, 'value');
                $sortedDesc = array_column($combined, 'label');
            }
        }

        $allData = [];
        foreach ($sortedDesc as $key => $label) {
            $allData[] = [
                'label' => $label,
                'value' => $hasil2[$key]
            ];
        }

        // return collect(["allData"=>$allData,"limitData"=>$allData]);

        return $allData;
    }

    public function getChartCategory(Request $request)
    {
        $data = AssetMgmt::get();
        $desc = AssetMgmt::select('category')->where('category', '!=', null)->groupBy('category')->get()->pluck('category');
        $length = $desc->count();

        if (count($data) == 0) {
            $hasil2 = [0,0,0];
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
            $sortedDesc = $desc->toArray();

            if (!$isAllZeros) {
                foreach ($hasil as $key => $value) {
                    $hasil2[$key] = round(($value / $pie) * 100, 2);
                }

                $combined = [];
                foreach ($hasil2 as $key => $value) {
                    $combined[] = [
                        'label' => $desc[$key],
                        'value' => $value
                    ];
                }

                usort($combined, function ($a, $b) {
                    return $b['value'] <=> $a['value'];
                });

                $hasil2 = array_column($combined, 'value');
                $sortedDesc = array_column($combined, 'label');
            }
        }

        $allData = [];
        foreach ($sortedDesc as $key => $label) {
            $allData[] = [
                'label' => $label,
                'value' => $hasil2[$key]
            ];
        }

        // return collect(["allData"=>$allData,"limitData"=>$allData]);

        return $allData;
    }

    public function getChartVendor(Request $request)
    {
        $data = AssetMgmt::get();
        $desc = AssetMgmt::select('vendor')->where('vendor', '!=', null)->groupBy('vendor')->get()->pluck('vendor');
        $length = $desc->count();

        $hasilChart = collect();

        if (count($data) == 0) {
            $hasil2 = array_fill(0, $length, 0);
            $sortedDesc = $desc->toArray();
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
            $sortedDesc = $desc->toArray();

            if (!$isAllZeros) {
                foreach ($hasil as $key => $value) {
                    $hasil2[$key] = round(($value / $pie) * 100, 2);
                }

                $combined = [];
                foreach ($hasil2 as $key => $value) {
                    $combined[] = [
                        'label' => $desc[$key],
                        'value' => $value
                    ];
                }

                usort($combined, function ($a, $b) {
                    return $b['value'] <=> $a['value'];
                });

                $hasil2 = array_column($combined, 'value');
                $sortedDesc = array_column($combined, 'label');
            }
        }

        $allData = [];
        foreach ($sortedDesc as $key => $label) {
            $allData[] = [
                'label' => $label,
                'value' => $hasil2[$key]
            ];
        }

        // return collect(["allData"=>$allData,"limitData"=>$allData]);

        return $allData;
    }

    public function getChartClient(Request $request)
    {
        $getId = AssetMgmt::join('tb_asset_management_detail','tb_asset_management_detail.id_asset','tb_asset_management.id')->select('tb_asset_management_detail.id_asset','detail_lokasi','tb_asset_management_detail.id');
        $getLastId = DB::table($getId,'temp')->groupBy('id_asset')->selectRaw('MAX(`temp`.`id`) as `id_last_asset`')->selectRaw('id_asset');

        $data = DB::table($getLastId, 'temp2')->join('tb_asset_management','tb_asset_management.id','temp2.id_asset')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')->join('tb_contact','tb_contact.customer_legal_name','tb_asset_management_detail.client')->select('asset_owner','category','category_peripheral','tb_asset_management.id_asset','type_device','vendor','tb_asset_management.status','rma','spesifikasi','serial_number','notes','client','code')
            // ->where('category_peripheral','-')
            ->get();

        $desc = DB::table($getLastId, 'temp2')->join('tb_asset_management','tb_asset_management.id','temp2.id_asset')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')->join('tb_contact','tb_contact.customer_legal_name','tb_asset_management_detail.client')
            ->select('tb_contact.code')
            // ->where('category_peripheral','-')
            ->groupBy('code')
            ->get()->pluck('code');


        $length = $desc->count(); 

        $hasilChart = collect(); 

        if (count($data) == 0) {
            $hasil2 = array_fill(0, $length, 0);
            $sortedDesc = $desc->toArray();
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
            $sortedDesc = $desc->toArray();

            if (!$isAllZeros) {
                foreach ($hasil as $key => $value) {
                    $hasil2[$key] = round(($value / $pie) * 100, 2);
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
                usort($combined, function ($a, $b) {
                    return $b['percentage'] <=> $a['percentage'];
                });

                // Extract the sorted values back into separate arrays
                $hasil2 = array_column($combined, 'percentage');
                $sortedDesc = array_column($combined, 'name');
            }
            
        }

        $allData = [];
        foreach ($sortedDesc as $key => $label) {
            $allData[] = [
                'label' => $label,
                'value' => $hasil2[$key]
            ];
        }

        return $allData;
    }

    public function getCountDashboard(Request $request)
    {
        $nik = Auth::User()->nik;
        $cek_role = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role','group','mini_group')->where('user_id',$nik)->first();

        $getPid = DB::table('ticketing__user')->where('nik',Auth::User()->nik)->get()->pluck('pid');
        $getPidPm = DB::table('tb_pmo')->join('tb_pmo_assign','tb_pmo_assign.id_project','tb_pmo.id')->join('role_user','role_user.user_id','tb_pmo_assign.nik')->join('roles','roles.id','role_user.role_id')->where('nik',Auth::User()->nik)->where('name','!=','Asset Management')->get()->pluck('project_id');

        $getId = AssetMgmt::join('tb_asset_management_detail','tb_asset_management_detail.id_asset','tb_asset_management.id')->select('tb_asset_management_detail.id_asset','detail_lokasi','tb_asset_management_detail.id');
        $getLastId = DB::table($getId,'temp')->groupBy('id_asset')->selectRaw('MAX(`temp`.`id`) as `id_last_asset`')->selectRaw('id_asset');

        $data = DB::table($getLastId, 'temp2')->join('tb_asset_management','tb_asset_management.id','temp2.id_asset')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')
            ->select('tb_asset_management_detail.pid','asset_owner','category','category_peripheral','tb_asset_management.id_asset','type_device','vendor','status','rma','spesifikasi','serial_number','notes','tb_asset_management.id','id_device_customer')
            // ->where('category_peripheral','-')
            ->orderBy('tb_asset_management.created_at','desc'); 

        if ($cek_role->mini_group == 'Center Point & Asset Management SVC ' || $cek_role->name_role == 'VP Supply Chain, CPS & Asset Management' || $cek_role->name_role == 'Operations Director') {
            $countAll = $data->count();
            $countInstalled = $data->where('status','Installed')->count();
            $countAvailable = $data->where('status','Available')->count();
            $countTemporary = $data->where('status','Temporary')->count();
        } else if ($cek_role->name_role == 'Engineer on Site' ) {
            $countAll = $data->whereIn('pid',$getPid)->count();
            $countInstalled = $data->where('status','Installed')->whereIn('pid',$getPid)->count();
            $countAvailable = $data->where('status','Available')->whereIn('pid',$getPid)->count();
            $countTemporary = $data->where('status','Temporary')->whereIn('pid',$getPid)->count();

            // $data = $data->whereIn('pid',$getPid);
        } elseif ($cek_role->name_role == 'Project Manager' || $cek_role->name_role == 'Project Coordinator') {
            $countAll = $data->whereIn('pid',$getPidPm)->count();
            $countInstalled = $data->where('status','Installed')->whereIn('pid',$getPidPm)->count();
            $countAvailable = $data->where('status','Available')->whereIn('pid',$getPidPm)->count();
            $countTemporary = $data->where('status','Temporary')->whereIn('pid',$getPidPm)->count();

            // $data = $data->whereIn('pid',$getPidPm);
        } else if ($cek_role->name_role == 'Managed Service Manager' ) {
            $countAll = $data->where('pid','!=','INTERNAL')->count();
            $countInstalled = $data->where('status','Installed')->where('pid','!=','INTERNAL')->count();
            $countAvailable = $data->where('status','Available')->where('pid','!=','INTERNAL')->count();
            $countTemporary = $data->where('status','Temporary')->where('pid','!=','INTERNAL')->count();
        }



        // $countAll = AssetMgmt::count();
        // $countInstalled = AssetMgmt::where('status','Installed')->count();
        // $countAvailable = AssetMgmt::where('status','Available')->count();
        // $countTemporary = AssetMgmt::where('status','Temporary')->count();

        return collect(["countAll"=>$countAll,"countInstalled"=>$countInstalled,"countAvailable"=>$countAvailable,"countTemporary"=>$countTemporary]);
    }

    public function getFilterCount(Request $request)
    {
        $nik = Auth::User()->nik;
        $cek_role = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role','group','mini_group')->where('user_id',$nik)->first();

        $getPid = DB::table('ticketing__user')->where('nik',Auth::User()->nik)->get()->pluck('pid');
        $getPidPm = DB::table('tb_pmo')->join('tb_pmo_assign','tb_pmo_assign.id_project','tb_pmo.id')->join('role_user','role_user.user_id','tb_pmo_assign.nik')->join('roles','roles.id','role_user.role_id')->where('nik',Auth::User()->nik)->where('name','!=','Asset Management')->get()->pluck('project_id');

        $getId = AssetMgmt::join('tb_asset_management_detail','tb_asset_management_detail.id_asset','tb_asset_management.id')->select('tb_asset_management_detail.id_asset','detail_lokasi','tb_asset_management_detail.id');
        $getLastId = DB::table($getId,'temp')->groupBy('id_asset')->selectRaw('MAX(`temp`.`id`) as `id_last_asset`')->selectRaw('id_asset');

        $countAll = DB::table($getLastId, 'temp2')->join('tb_asset_management','tb_asset_management.id','temp2.id_asset')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')
            ->select('tb_asset_management_detail.pid','asset_owner','category','category_peripheral','tb_asset_management.id_asset','type_device','vendor','status','rma','spesifikasi','serial_number','notes');

        $countInstalled = DB::table($getLastId, 'temp2')->join('tb_asset_management','tb_asset_management.id','temp2.id_asset')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')
            ->select('tb_asset_management_detail.pid','asset_owner','category','category_peripheral','tb_asset_management.id_asset','type_device','vendor','status','rma','spesifikasi','serial_number','notes')->where('status','Installed');

        $countAvailable = DB::table($getLastId, 'temp2')->join('tb_asset_management','tb_asset_management.id','temp2.id_asset')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')
            ->select('tb_asset_management_detail.pid','asset_owner','category','category_peripheral','tb_asset_management.id_asset','type_device','vendor','status','rma','spesifikasi','serial_number','notes')->where('status','Available');

        $countTemporary = DB::table($getLastId, 'temp2')->join('tb_asset_management','tb_asset_management.id','temp2.id_asset')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')
            ->select('tb_asset_management_detail.pid','asset_owner','category','category_peripheral','tb_asset_management.id_asset','type_device','vendor','status','rma','spesifikasi','serial_number','notes')->where('status','Temporary');

        if (isset($request->pid)) {
            $countAll->where('pid',$request->pid);
            $countInstalled->where('pid',$request->pid);
            $countAvailable->where('pid',$request->pid);
            $countTemporary->where('pid',$request->pid);
        }

        if (isset($request->assetOwner)) {
            $countAll->where('asset_owner',$request->assetOwner);
            $countInstalled->where('asset_owner',$request->assetOwner);
            $countAvailable->where('asset_owner',$request->assetOwner);
            $countTemporary->where('asset_owner',$request->assetOwner);
        }

        if (isset($request->category)) {
            $countAll->where('category',$request->category);
            $countInstalled->where('category',$request->category);
            $countAvailable->where('category',$request->category);
            $countTemporary->where('category',$request->category);
        } 

        if (isset($request->category)) {
            $countAll->where('category',$request->category);
            $countInstalled->where('category',$request->category);
            $countAvailable->where('category',$request->category);
            $countTemporary->where('category',$request->category);
        }
        
        if (isset($request->client)) {
            $countAll->where('client',$request->client);
            $countInstalled->where('client',$request->client);
            $countAvailable->where('client',$request->client);
            $countTemporary->where('client',$request->client);
        } 

        if ($cek_role->mini_group == 'Center Point & Asset Management SVC ' || $cek_role->name_role == 'VP Supply Chain, CPS & Asset Management' || $cek_role->name_role == 'Operations Director') {
            $countAll = $countAll;
            $countInstalled = $countInstalled;
            $countAvailable = $countAvailable;
            $countTemporary = $countTemporary;
        } else if ($cek_role->name_role == 'Engineer on Site' ) {
            $countAll = $countAll->whereIn('pid',$getPid);
            $countInstalled = $countInstalled->whereIn('pid',$getPid);
            $countAvailable = $countAvailable->whereIn('pid',$getPid);
            $countTemporary = $countTemporary->whereIn('pid',$getPid);

            // $data = $data->whereIn('pid',$getPid);
        } elseif ($cek_role->name_role == 'Project Manager' || $cek_role->name_role == 'Project Coordinator') {
            $countAll = $countAll->whereIn('pid',$getPidPm);
            $countInstalled = $countInstalled->whereIn('pid',$getPidPm);
            $countAvailable = $countAvailable->whereIn('pid',$getPidPm);
            $countTemporary = $countTemporary->whereIn('pid',$getPidPm);

            // $data = $data->whereIn('pid',$getPidPm);
        } else if ($cek_role->name_role == 'Managed Service Manager' ) {
            $countAll = $countAll->where('pid','!=','INTERNAL');
            $countInstalled = $countInstalled->where('pid','!=','INTERNAL');
            $countAvailable = $countAvailable->where('pid','!=','INTERNAL');
            $countTemporary = $countTemporary->where('pid','!=','INTERNAL');
        }

        return collect(["countAll"=>$countAll->count('tb_asset_management.id_asset'),"countInstalled"=>$countInstalled->count('tb_asset_management.id_asset'),"countAvailable"=>$countAvailable->count('tb_asset_management.id_asset'),"countTemporary"=>$countTemporary->count('tb_asset_management.id_asset')]);
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

        foreach ($data as $value) {
            foreach ($value['id_asset'] as $values) {
                $id = AssetMgmt::where('id_asset',$values)->first();
                $store = new AssetMgmtAssignEngineer();
                $store->engineer_atm = $value['engineer'];
                $store->id_asset = $id->id;
                $store->save();
            }
        }
    }

    public function getIdAtm(Request $request)
    {

        $getId = AssetMgmt::join('tb_asset_management_detail','tb_asset_management_detail.id_asset','tb_asset_management.id')->select('tb_asset_management_detail.id_asset','detail_lokasi','tb_asset_management_detail.id');
        $getLastId = DB::table($getId,'temp')->groupBy('id_asset')->selectRaw('MAX(`temp`.`id`) as `id_last_asset`')->selectRaw('id_asset');

        $getLastId = DB::table($getLastId,'temp2')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')->select('tb_asset_management_detail.id_asset','pid','id_last_asset');

        $data = DB::table($getLastId, 'temp3')->join('tb_asset_management','tb_asset_management.id','temp3.id_asset')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp3.id_last_asset')
            ->select(DB::raw('`tb_asset_management`.`id_asset` AS `id`,`tb_asset_management_detail`.`id_device_customer` AS `text`'));

        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')
                    ->select('name', 'roles.group')->where('user_id', Auth::User()->nik)->first(); 

        if ($cek_role->name == 'Managed Service Manager') {
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
            ->whereBetween('reporting_time', [$getAll->maintenance_start . " 00:00:00", $getAll->maintenance_end . " 23:59:59"])
            ->orderBy('ticketing__detail.id','DESC')
            ->get();

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
        $data = DB::table('tb_asset_management_category')->select('id_category as id','name as text')->where('name','like','%'.request('q').'%')->distinct()->get();

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

        $getPidPm = DB::table('tb_pmo')->join('tb_pmo_assign','tb_pmo_assign.id_project','tb_pmo.id')->join('users','users.nik','tb_pmo_assign.nik')->whereIn('id_project',$getIdPmo)->select('name','email')->where('role','Project Coordinator');

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
                            ->where('role','Project Coordinator')
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

        $data = AssetMgmt::select('id as id', 'id_asset as text')
            ->where('id_asset', 'like', '%' . request('q') . '%')
            ->whereNotIn('id',$id)
            ->distinct()
            ->get();

        return $data;
    }

    public function getPidScheduling(Request $request)
    {
        $getId = AssetMgmt::join('tb_asset_management_detail','tb_asset_management_detail.id_asset','tb_asset_management.id')->select('tb_asset_management_detail.id_asset','detail_lokasi','tb_asset_management_detail.id');
        $getLastId = DB::table($getId,'temp')->groupBy('id_asset')->selectRaw('MAX(`temp`.`id`) as `id_last_asset`')->selectRaw('id_asset');

        $pid = DB::table($getLastId, 'temp2')->join('tb_asset_management','tb_asset_management.id','temp2.id_asset')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')
            ->select('tb_asset_management_detail.pid')->where('tb_asset_management_detail.id_asset',$request->id_asset)->pluck('pid'); 

        $getClient = DB::table('tb_id_project')->whereIn('id_project',$pid)->select('customer_name')->pluck('customer_name');

        $data = DB::table('tb_id_project')->select('id_project as id', 'id_project as text')
            ->where('id_project', 'like', '%' . request('q') . '%')
            ->whereNotIn('id_project',$pid)
            ->whereIn('customer_name',$getClient)
            ->distinct()
            ->get();

        return $data;
    }

    public function storeScheduling(Request $request)
    {
        $data = json_decode($request->arrListAsset,true);

        foreach ($data as $value) {
            $store = new AssetMgmtScheduling();
            $store->id_asset = $value['id_asset'];
            $store->pid = $value['pid'];
            $store->maintenance_end = $value['date_end'];
            $store->maintenance_start = $value['date_start'];
            $store->status = 'PENDING';
            $store->save();
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
}
