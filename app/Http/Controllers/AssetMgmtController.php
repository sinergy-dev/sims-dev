<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AssetMgmt;
use App\AssetMgmtDetail;
use App\AssetMgmtLog;
use App\AssetMgmtAssign;
use App\SalesProject;
use App\AssetMgmtAssignEngineer;
use GuzzleHttp\Client;
use Carbon\Carbon;
use DB;
use Auth;


class AssetMgmtController extends Controller
{
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

    public function getSearchData(Request $request)
    {
        $nik = Auth::User()->nik;
        $cek_role = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role','group','mini_group')->where('user_id',$nik)->first();

        $getPid = DB::table('ticketing__user')->where('nik',Auth::User()->nik)->get()->pluck('pid');
        $getPidPm = DB::table('tb_pmo')->join('tb_pmo_assign','tb_pmo_assign.id_project','tb_pmo.id')->join('role_user','role_user.user_id','tb_pmo_assign.nik')->join('roles','roles.id','role_user.role_id')->where('nik',Auth::User()->nik)->where('name','!=','Asset Management')->get()->pluck('project_id');

        $getId = AssetMgmt::join('tb_asset_management_detail','tb_asset_management_detail.id_asset','tb_asset_management.id_asset')->select('tb_asset_management_detail.id_asset','detail_lokasi','tb_asset_management_detail.id');
        $getLastId = DB::table($getId,'temp')->groupBy('id_asset')->selectRaw('MAX(`temp`.`id`) as `id_last_asset`')->selectRaw('id_asset');

        $data = DB::table($getLastId, 'temp2')->join('tb_asset_management','tb_asset_management.id_asset','temp2.id_asset')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')
            ->select('tb_asset_management_detail.pid','asset_owner','category','category_peripheral','tb_asset_management.id_asset','type_device','vendor','status','rma','spesifikasi','serial_number','notes')
            ->where('category_peripheral','-')
            ->orderBy('tb_asset_management.created_at','desc');  

        $searchFields = ['asset_owner', 'tb_asset_management_detail.pid', 'serial_number', 'tb_asset_management.id_asset', 'type_device', 'vendor', 'rma', 'spesifikasi','notes'];

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

    public function getDataAsset()
    {
        $nik = Auth::User()->nik;
        $cek_role = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role','group','mini_group')->where('user_id',$nik)->first();

        $getPid = DB::table('ticketing__user')->where('nik',Auth::User()->nik)->get()->pluck('pid');
        $getPidPm = DB::table('tb_pmo')->join('tb_pmo_assign','tb_pmo_assign.id_project','tb_pmo.id')->join('role_user','role_user.user_id','tb_pmo_assign.nik')->join('roles','roles.id','role_user.role_id')->where('nik',Auth::User()->nik)->where('name','!=','Asset Management')->get()->pluck('project_id');

        $getId = AssetMgmt::join('tb_asset_management_detail','tb_asset_management_detail.id_asset','tb_asset_management.id_asset')->select('tb_asset_management_detail.id_asset','detail_lokasi','tb_asset_management_detail.id');
        $getLastId = DB::table($getId,'temp')->groupBy('id_asset')->selectRaw('MAX(`temp`.`id`) as `id_last_asset`')->selectRaw('id_asset');

        $data = DB::table($getLastId, 'temp2')->join('tb_asset_management','tb_asset_management.id_asset','temp2.id_asset')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')
            ->select('tb_asset_management_detail.pid','asset_owner','category','category_peripheral','tb_asset_management.id_asset','type_device','vendor','status','rma','spesifikasi','serial_number','notes')
            ->where('category_peripheral','-')
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
        $getId = AssetMgmt::leftjoin('tb_asset_management_detail','tb_asset_management_detail.id_asset','tb_asset_management.id_asset')->select('tb_asset_management_detail.id_asset','detail_lokasi','tb_asset_management_detail.id');
        $getLastId = DB::table($getId,'temp')->groupBy('id_asset')->selectRaw('MAX(`temp`.`id`) as `id_last_asset`')->selectRaw('id_asset');

        // return $getLastId->id_last_asset;

        $data = DB::table($getLastId, 'temp2')->join('tb_asset_management','tb_asset_management.id_asset','temp2.id_asset')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')
            ->select('tb_asset_management_detail.pid','asset_owner','category','category_peripheral','tb_asset_management.id_asset','type_device','vendor','status','rma','spesifikasi','serial_number','notes','related_id_asset')->orderBy('tb_asset_management.created_at','desc');  

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
                $data->where('category',$request->category);
            }
        } 

        if (isset($request->client)) {
            $data->where('client',$request->client);
        }      

        return array("data"=>$data->get());
    }

    public function getClient()
    {
        // $getAllPid = SalesProject::join('sales_lead_register', 'sales_lead_register.lead_id', '=', 'tb_id_project.lead_id')->join('users', 'users.nik', '=', 'sales_lead_register.nik')->select('customer_name as id','customer_name as text')->where('id_company', '1')->where('id_project','like','%'.request('q').'%')->orderBy('tb_id_project.created_at','desc')->get()->groupBy('customer_name');

        $getClient = DB::table('tb_asset_management_detail')->select('client as id','client as text')->where('client','like','%'.request('q').'%')->groupby('client')->get();

        return response()->json($getClient);
    }

    public function storeAsset(Request $request)
    {
        // return $request->typeAsset;
        if ($request->typeAsset == 'peripheral') {
            $category = $request->categoryPeripheral;
            $inc = AssetMgmt::select('category_peripheral')
                        ->where('category_peripheral', $category)
                        ->get();
            $increment = count($inc);
            $nomor = $increment+1;
            if($nomor < 10){
                $nomor = '00' . $nomor;
            } else {
                $nomor = '0' . $nomor;
            }
            $cat = $category;
        } else {
            $category = $request->category;
            $inc = AssetMgmt::select('category')
                        ->where('category', $category)
                        ->get();
            $increment = count($inc);
            $nomor = $increment+1;
            if($nomor < 10){
                $nomor = '00' . $nomor;
            } else {
                $nomor = '0' . $nomor;
            }

            if ($category == 'Network') {
                $cat = 'NTW';
            } elseif($category == 'Security'){
                $cat = 'SCR';
            } else {
                $cat = $category;
            }

        }

        $id =  $nomor .'-'. $cat;

        $store = new AssetMgmt();
        $store->id_asset = $id;
        $store->asset_owner = $request->assetOwner;
        $store->category = $request->category;
        if ($request->typeAsset == 'peripheral') {
            $store->category_peripheral = $request->categoryPeripheral;
        } else {
            $store->category_peripheral = '-';
        }
        $store->status = $request->status;
        $store->vendor = $request->vendor;
        $store->type_device = $request->typeDevice;
        $store->serial_number = $request->serialNumber;
        $store->spesifikasi = $request->spesifikasi;
        $store->rma = $request->rma;
        $store->notes = $request->notes;
        $store->save();

        if ($request->typeAsset == 'asset') {
            $storeDetail = new AssetMgmtDetail();
            $storeDetail->id_asset = $id;
            $storeDetail->id_device_customer = $request->idDeviceCustomer;
            $storeDetail->client = $request->client;
            $storeDetail->pid = $request->pid;
            $storeDetail->kota = $request->kota;
            $storeDetail->alamat_lokasi = $request->alamatLokasi;
            $storeDetail->detail_lokasi = $request->detailLokasi;
            $storeDetail->ip_address = $request->ipAddress;
            $storeDetail->server = $request->ipServer;
            $storeDetail->port = $request->port;
            $storeDetail->status_cust = $request->statusCust;
            $storeDetail->second_level_support = $request->secondLevelSupport;
            $storeDetail->operating_system = $request->operatingSystem;
            $storeDetail->version_os = $request->versionOs;
            $storeDetail->installed_date = $request->installedDate;
            $storeDetail->license = $request->license;
            $storeDetail->license_start_date = $request->licenseStartDate;
            $storeDetail->license_end_date = $request->licenseEndDate;
            $storeDetail->maintenance_start = $request->maintenanceStart;
            $storeDetail->maintenance_end = $request->maintenanceEnd;
            $storeDetail->save();
        } else if ($request->typeAsset == 'peripheral') {
            $getId = AssetMgmt::join('tb_asset_management_detail','tb_asset_management_detail.id_asset','tb_asset_management.id_asset')->select('tb_asset_management_detail.id_asset','detail_lokasi','tb_asset_management_detail.id');
            $getLastId = DB::table($getId,'temp')->groupBy('id_asset')->selectRaw('MAX(`temp`.`id`) as `id_last_asset`')->selectRaw('id_asset');

            $data = DB::table($getLastId, 'temp2')->join('tb_asset_management','tb_asset_management.id_asset','temp2.id_asset')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')
                ->select('tb_asset_management_detail.id_asset','id_device_customer','client','pid','kota','alamat_lokasi','detail_lokasi','ip_address','server','port','status_cust','second_level_support','operating_system','version_os','installed_date','license','license_end_date','license_start_date','maintenance_end','maintenance_start')
                ->where('tb_asset_management_detail.id_asset',$request->assignTo)
                ->first();

            if (isset($request->assignTo)) {

                $storeDetail = new AssetMgmtDetail();
                $storeDetail->id_asset = $id;
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
                $storeDetail->license_start_date = $data->license_start_date;
                $storeDetail->license_end_date = $data->license_end_date;
                $storeDetail->maintenance_start = $data->maintenance_start;
                $storeDetail->maintenance_end = $data->maintenance_end;
                $storeDetail->date_add = Carbon::now()->toDateTimeString();

                $storeAssign = new AssetMgmtAssign();
                $storeAssign->id_asset_induk = $request->assignTo;
                $storeAssign->id_asset_peripheral = $id;
                $storeAssign->date_add = Carbon::now()->toDateTimeString();
                $storeAssign->save();

                $updateAsset = AssetMgmt::where('id_asset',$request->assignTo)->first();
                $updateAsset->status = 'Installed';
                $updateAsset->save();

                $storeDetail->related_id_asset = $request->assignTo;

                $storeDetail->save();

            } else {
                $storeDetail = new AssetMgmtDetail();
                $storeDetail->id_asset = $id;
                $storeDetail->save();
            }

        }

        $storeLog = new AssetMgmtLog();
        $storeLog->id_asset = $id;
        $storeLog->operator = Auth::User()->name;
        $storeLog->date_add = Carbon::now()->toDateTimeString();
        $storeLog->activity = 'Add New Asset ' .$id. ' with Category ' . $request->category;
        $storeLog->save();

    }

    public function getAssetById(Request $request)
    {
        $getId = AssetMgmt::join('tb_asset_management_detail','tb_asset_management_detail.id_asset','tb_asset_management.id_asset')->select('tb_asset_management_detail.id_asset','detail_lokasi','tb_asset_management_detail.id');
        $getLastId = DB::table($getId,'temp')->groupBy('id_asset')->selectRaw('MAX(`temp`.`id`) as `id_last_asset`');

        return $getAll = DB::table($getLastId, 'temp2')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')->join('tb_asset_management', 'tb_asset_management.id_asset', '=', 'tb_asset_management_detail.id_asset')->select('tb_asset_management_detail.id_asset','id_device_customer','client','pid','kota','alamat_lokasi','detail_lokasi','ip_address','server','port','status_cust','second_level_support','operating_system','version_os','installed_date','license','license_end_date','license_start_date','maintenance_end','maintenance_start')->where('tb_asset_management_detail.id_asset',$request->id_asset)->get();
    }

    public function getClientByPid(Request $request)
    {
        $data = DB::table('tb_id_project')->select('customer_name')->where('id_project',$request->pid)->where('id_project','like','%'.request('q').'%')->first();

        return response()->json($data->customer_name);
    }

    public function getPid()
    {
        $getAllPid = SalesProject::join('sales_lead_register', 'sales_lead_register.lead_id', '=', 'tb_id_project.lead_id')->join('users', 'users.nik', '=', 'sales_lead_register.nik')->select('id_project as id',DB::raw("CONCAT(`id_project`,' - ',`name_project`) AS text"))->where('id_company', '1')->where('id_project','like','%'.request('q').'%')->orderBy('tb_id_project.created_at','desc')->get();

        $getAllPid = $getAllPid->push((object)(['id' => 'INTERNAL','text' => 'INTERNAL']));

        return response()->json($getAllPid);
    }

    public function updateAsset(Request $request)
    {

        $update = AssetMgmt::where('id_asset',$request->id_asset)->first();
        $update->asset_owner = $request->assetOwner;
        $update->status = $request->status;
        $update->vendor = $request->vendor;
        $update->type_device = $request->typeDevice;
        $update->serial_number = $request->serialNumber;
        $update->spesifikasi = $request->spesifikasi;
        $update->rma = $request->rma;
        $update->notes = $request->notes;
        $update->save();

        if ($request->status != 'Installed') {
            $delete = AssetMgmtAssign::where('id_asset_peripheral',$request->id_asset)->delete();
        }

        $storeDetail = new AssetMgmtDetail();
        $storeDetail->id_asset = $request->id_asset;
        $storeDetail->id_device_customer = $request->idDeviceCustomer;
        $storeDetail->client = $request->client;
        $storeDetail->pid = $request->pid;
        $storeDetail->kota = $request->kota;
        $storeDetail->alamat_lokasi = $request->alamatLokasi;
        $storeDetail->detail_lokasi = $request->detailLokasi;
        $storeDetail->ip_address = $request->ipAddress;
        $storeDetail->server = $request->ipServer;
        $storeDetail->port = $request->port;
        $storeDetail->status_cust = $request->statusCust;
        $storeDetail->second_level_support = $request->secondLevelSupport;
        $storeDetail->operating_system = $request->operatingSystem;
        $storeDetail->version_os = $request->versionOs;
        $storeDetail->installed_date = $request->installedDate;
        $storeDetail->license = $request->license;
        $storeDetail->license_start_date = $request->licenseStartDate;
        $storeDetail->license_end_date = $request->licenseEndDate;
        $storeDetail->maintenance_start = $request->maintenanceStart;
        $storeDetail->maintenance_end = $request->maintenanceEnd;
        $storeDetail->date_add = Carbon::now()->toDateTimeString();
        $storeDetail->save();

        if (isset($request->engineer)) {
            $store = new AssetMgmtAssignEngineer();
            $store->id_asset = $request->id_asset;
            $store->engineer_atm = $request->engineer;
            $store->save();
        }

        $storeLog = new AssetMgmtLog();
        $storeLog->id_asset = $request->id_asset;
        $storeLog->operator = Auth::User()->name;
        $storeLog->date_add = Carbon::now()->toDateTimeString();
        $storeLog->activity = 'Update Asset ' .$request->id_asset. ' with Category ' . $update->category;
        $storeLog->save();
    }

    public function getProvince(Request $request)
    {
        $client = new Client();
        // $getData = $client->get('https://open-api.my.id/api/wilayah/provinces');
        $getData = $client->get('https://api.binderbyte.com/wilayah/provinsi?api_key='.env('API_KEY_PROVINCE'));
        $json = (string)$getData->getBody();
        $getDataRegencies = json_decode($json, true);

        $getDataRegenciesDetail = collect();

        foreach ($getDataRegencies["value"] as $key => $value) {
            // return $value;
            // $data = (string)$client->get('https://open-api.my.id/api/wilayah/regencies/'.$value['id'])->getBody();
            $data = (string)$client->get('https://api.binderbyte.com/wilayah/kabupaten?api_key='.env('API_KEY_PROVINCE').'&id_provinsi='.$value['id'])->getBody();
            $dataJson = json_decode($data,true);
            foreach ($dataJson["value"] as $key => $value) {
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
        $getId = AssetMgmt::join('tb_asset_management_detail','tb_asset_management_detail.id_asset','tb_asset_management.id_asset')->select('tb_asset_management_detail.id_asset','detail_lokasi','tb_asset_management_detail.id');
        $getLastId = DB::table($getId,'temp')->groupBy('id_asset')->selectRaw('MAX(`temp`.`id`) as `id_last_asset`');

        $getAll = DB::table($getLastId, 'temp2')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')->join('tb_asset_management', 'tb_asset_management.id_asset', '=', 'tb_asset_management_detail.id_asset')->leftJoin('tb_asset_management_assign_engineer','tb_asset_management.id_asset','tb_asset_management_assign_engineer.id_asset')
            ->select('tb_asset_management_detail.id_asset','id_device_customer','client','pid','kota','alamat_lokasi','detail_lokasi','ip_address','server','port','status_cust','second_level_support','operating_system','version_os','installed_date','license','license_end_date','license_start_date','maintenance_end','maintenance_start','notes','rma','spesifikasi','type_device','serial_number','vendor','category','category_peripheral','asset_owner','related_id_asset',DB::raw("(CASE WHEN (category_peripheral = '-') THEN 'asset' WHEN (category_peripheral != '-') THEN 'peripheral' END) as type"),'status',DB::raw("TIMESTAMPDIFF(HOUR, concat(maintenance_start,' 00:00:00'), concat(maintenance_end,' 00:00:00')) AS slaPlanned"),'engineer_atm')
            ->where('tb_asset_management_detail.id_asset',$request->id_asset)
            ->first();

        $getIdTicket = DB::table('ticketing__detail')->where('id_atm',$getAll->id_device_customer)->select('id_ticket')->whereBetween('reporting_time', [$getAll->maintenance_start . " 00:00:00", $getAll->maintenance_end . " 23:59:59"])->get()->pluck('id_ticket');

        $timeTrouble = DB::table('ticketing__activity')->join('ticketing__activity as t2','t2.id_ticket','ticketing__activity.id_ticket')->join('ticketing__detail','ticketing__detail.id_ticket','ticketing__activity.id_ticket')
            ->select(DB::raw("TIME_TO_SEC(TIMEDIFF(t2.date, ticketing__activity.date)) AS time_diff_seconds"),
                'ticketing__activity.id_ticket')
            ->where('ticketing__activity.activity', 'OPEN')->where('t2.activity', 'CLOSE')->whereIn('ticketing__activity.id_ticket',$getIdTicket)->get()->SUM('time_diff_seconds');

        $countTicket = DB::table('ticketing__detail')->where('id_atm',$getAll->id_device_customer)->whereBetween('reporting_time', [$getAll->maintenance_start . " 00:00:00", $getAll->maintenance_end . " 23:59:59"])->count();

        if ($getAll->category_peripheral == '-') {
            $sla = (100 - ((($timeTrouble/3600)/$getAll->slaPlanned)*100));
        } else {
            $sla = 0;
        }

        $getData = collect($getAll);
        $getData = $getData->put('countTicket',$countTicket)->put('slaUptime',number_format($sla, 2, '.', ''));

        return $getData;
    }

    public function getLog()
    {
        $data = AssetMgmtLog::orderBy('date_add','desc')->get();
        return $data;
    }

    public function getLogById(Request $request)
    {
        $peripheral = AssetMgmt::select('category_peripheral')->where('id_asset',$request->id_asset)->first();
        $data = AssetMgmtDetail::select('client','pid',DB::raw("CONCAT(`detail_lokasi`, ' - ', `alamat_lokasi`, ' - ', `kota`) AS `lokasi`"),DB::raw("CONCAT(`maintenance_start`, ' - ', `maintenance_end`) AS `periode`"),'related_id_asset')->where('id_asset',$request->id_asset)->orderby('tb_asset_management_detail.id','desc')->get();

        return array("data"=>$data);
    }

    public function getPeripheral(Request $request)
    {
        $data = AssetMgmtAssign::where('tb_asset_management_assign.id_asset_induk',$request->id_asset)->get();

        $collectData = collect();

        foreach ($data as $key => $value) {
            $getData = AssetMgmt::select('tb_asset_management.category_peripheral',DB::raw("CONCAT(`type_device`, ' - ', `serial_number`) AS `text`"),'id_asset')->where('id_asset',$value->id_asset_peripheral)->first();
            $collectData->push(['category_peripheral'=>$getData->category_peripheral,'text'=>$getData->text,'id_asset'=>$getData->id_asset]);
            // $collectData[] = $collectData;
        }

        return $collectData;
    }

    public function getAssetToAssign()
    {
        $getId = AssetMgmt::join('tb_asset_management_detail','tb_asset_management_detail.id_asset','tb_asset_management.id_asset')->select('tb_asset_management_detail.id_asset','detail_lokasi','tb_asset_management_detail.id');
        $getLastId = DB::table($getId,'temp')->groupBy('id_asset')->selectRaw('MAX(`temp`.`id`) as `id_last_asset`');

        $getAll = DB::table($getLastId, 'temp2')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')->join('tb_asset_management', 'tb_asset_management.id_asset', '=', 'tb_asset_management_detail.id_asset')->select(DB::raw('`tb_asset_management`.`id_asset` AS `id`'),DB::raw("CONCAT(`tb_asset_management`.`id_asset`, ' - ', `detail_lokasi`, ' - ', `serial_number`) AS `text`"))->where('category_peripheral','-')
        // ->where('status','Available')
        ->where('tb_asset_management.id','like','%'.request('q').'%')->get();

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

        $update = AssetMgmt::where('id_asset',$request->id_asset)->first();
        $update->status = 'Installed';
        $update->save();

        $updateDetail = AssetMgmtDetail::where('id_asset',$request->id_asset_induk)->first();
        // $updateDetail->related_id_asset = $request->id_asset_induk;
        // $updateDetail->save();

        $storeDetail = new AssetMgmtDetail();
        $storeDetail->id_asset = $request->id_asset;
        $storeDetail->id_device_customer = $updateDetail->id_device_customer;
        $storeDetail->client = $updateDetail->client;
        $storeDetail->pid = $updateDetail->pid;
        $storeDetail->kota = $updateDetail->kota;
        $storeDetail->alamat_lokasi = $updateDetail->alamat_lokasi;
        $storeDetail->detail_lokasi = $updateDetail->detail_lokasi;
        $storeDetail->ip_address = $updateDetail->ip_address;
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
        $storeDetail->related_id_asset = $updateDetail->id_asset_induk;
        $storeDetail->date_add = Carbon::now()->toDateTimeString();
        $storeDetail->save();

        $storeLog = new AssetMgmtLog();
        $storeLog->id_asset = $request->id_asset;
        $storeLog->operator = Auth::User()->name;
        $storeLog->date_add = Carbon::now()->toDateTimeString();
        $storeLog->activity = 'Assign peripheral ' . $request->id_asset . ' to ' . $request->id_asset_induk;
        $storeLog->save();
    }

    public function deleteAssignedAsset(Request $request)
    {
        $delete = AssetMgmtAssign::where('id_asset_peripheral',$request->id_asset)->delete();
        $data = AssetMgmtDetail::where('id_asset',$request->id_asset)->orderby('id','desc')->first();

        $updateStatus = AssetMgmt::where('id_asset',$request->id_asset)->first();
        $updateStatus->status = 'Available';
        $updateStatus->save();

        $storeLog = new AssetMgmtLog();
        $storeLog->id_asset = $request->id_asset;
        $storeLog->operator = Auth::User()->name;
        $storeLog->date_add = Carbon::now()->toDateTimeString();
        $storeLog->activity = 'Remove Asset ' .$request->id_asset. ' from Asset ' . $data->related_id_asset;
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
        $getPid = DB::table('tb_asset_management_detail')->select('pid as id','pid as text')->where('pid','like','%'.request('q').'%')->groupby('pid')->get();

        return response()->json($getPid);
    }

    public function getChartAssetOwner(Request $request)
    {
        $data = AssetMgmt::get();
        $hasilChart = collect();

        if (count($data) == 0) {
            $hasil2 = [0,0,0];
        }else{
            $hasil = [0,0,0];
            $desc = ['SIP','Principal','Distributor'];
            $pie = 0;

            foreach ($desc as $key => $value2) {
                foreach ($data as $value) {
                    if ($value->asset_owner == $value2) {
                        $hasil[$key]++;
                        $pie++;
                    }
                }
            }

            // Convert the array to a Laravel Collection
            $collection = collect($hasil);

            // Use the every method to check if all values are zeros
            $isAllZeros = $collection->every(function ($value) {
                return $value === 0;
            });

            $hasil2 = [0,0,0];

            if ($isAllZeros) {
                $hasil2;
            } else {
                foreach ($hasil as $key => $value) {
                    $hasil2[$key] = round(($value/$pie)*100,2);
                }
            }
            
        }
        return $hasilChart->push(['chart'=>$hasil2,'name'=>$desc]);
    }

    public function getChartCategory(Request $request)
    {
        $data = AssetMgmt::get();
        $hasilChart = collect();


        if (count($data) == 0) {
            $hasil2 = [0,0,0,0];
        }else{
            $hasil = [0,0,0,0];
            $desc = ['ATM','CRM','Network','Security'];
            $pie = 0;

            foreach ($desc as $key => $value2) {
                foreach ($data as $value) {
                    if ($value->category == $value2) {
                        $hasil[$key]++;
                        $pie++;
                    }
                }
            }

            // Convert the array to a Laravel Collection
            $collection = collect($hasil);

            // Use the every method to check if all values are zeros
            $isAllZeros = $collection->every(function ($value) {
                return $value === 0;
            });

            $hasil2 = [0,0,0,0];

            if ($isAllZeros) {
                $hasil2;
            } else {
                foreach ($hasil as $key => $value) {
                    $hasil2[$key] = round(($value/$pie)*100,2);
                }
            }
            
        }

        return $hasilChart->push(['chart'=>$hasil2,'name'=>$desc]);
    }

    public function getChartVendor(Request $request)
    {
        $data = AssetMgmt::get();
        $desc = AssetMgmt::select('vendor')->where('vendor','!=',null)->groupby('vendor')->get()->pluck('vendor');
        $length = $desc->count();

        $hasilChart = collect();

        if (count($data) == 0) {
            $hasil2 = array_fill(0, $length, 0);
        }else{
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

            // Convert the array to a Laravel Collection
            $collection = collect($hasil);

            // Use the every method to check if all values are zeros
            $isAllZeros = $collection->every(function ($value) {
                return $value === 0;
            });

            $hasil2 = array_fill(0, $length, 0);

            if ($isAllZeros) {
                $hasil2;
            } else {
                foreach ($hasil as $key => $value) {
                    $hasil2[$key] = round(($value/$pie)*100,2);
                }
            }
            
        }

        return $hasilChart->push(['chart'=>$hasil2,'name'=>$desc]);
    }

    public function getChartClient(Request $request)
    {
        $getId = AssetMgmt::join('tb_asset_management_detail','tb_asset_management_detail.id_asset','tb_asset_management.id_asset')->select('tb_asset_management_detail.id_asset','detail_lokasi','tb_asset_management_detail.id');
        $getLastId = DB::table($getId,'temp')->groupBy('id_asset')->selectRaw('MAX(`temp`.`id`) as `id_last_asset`')->selectRaw('id_asset');

        $data = DB::table($getLastId, 'temp2')->join('tb_asset_management','tb_asset_management.id_asset','temp2.id_asset')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')->join('tb_contact','tb_contact.customer_legal_name','tb_asset_management_detail.client')
            ->select('tb_contact.code','asset_owner','category','category_peripheral','tb_asset_management.id_asset','type_device','vendor','tb_asset_management.status','rma','spesifikasi','serial_number','notes')
            ->where('category_peripheral','-')
            ->get();

        $desc = DB::table($getLastId, 'temp2')->join('tb_asset_management','tb_asset_management.id_asset','temp2.id_asset')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')->join('tb_contact','tb_contact.customer_legal_name','tb_asset_management_detail.client')
            ->select('tb_contact.code')
            ->where('category_peripheral','-')
            ->groupBy('code')
            ->get()->pluck('code');

        $length = $desc->count(); 

        $hasilChart = collect(); 

        if (count($data) == 0) {
            $hasil2 = array_fill(0, $length, 0);
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

            // Convert the array to a Laravel Collection
            $collection = collect($hasil);

            // Use the every method to check if all values are zeros
            $isAllZeros = $collection->every(function ($value) {
                return $value === 0;
            });

            $hasil2 = array_fill(0, $length, 0);

            if ($isAllZeros) {
                $hasil2;
            } else {
                foreach ($hasil as $key => $value) {
                    $hasil2[$key] = round(($value/$pie)*100,2);
                }
            }
            
        }

        return $hasilChart->push(['chart'=>$hasil2,'name'=>$desc]);
    }

    public function getCountDashboard(Request $request)
    {
        $countAll = AssetMgmt::count();
        $countInstalled = AssetMgmt::where('status','Installed')->count();
        $countAvailable = AssetMgmt::where('status','Available')->count();
        $countRma = AssetMgmt::where('status','RMA')->count();

        return collect(["countAll"=>$countAll,"countInstalled"=>$countInstalled,"countAvailable"=>$countAvailable,"countRma"=>$countRma]);
    }

    public function getFilterCount(Request $request)
    {
        $getId = AssetMgmt::join('tb_asset_management_detail','tb_asset_management_detail.id_asset','tb_asset_management.id_asset')->select('tb_asset_management_detail.id_asset','detail_lokasi','tb_asset_management_detail.id');
        $getLastId = DB::table($getId,'temp')->groupBy('id_asset')->selectRaw('MAX(`temp`.`id`) as `id_last_asset`')->selectRaw('id_asset');

        $countAll = DB::table($getLastId, 'temp2')->join('tb_asset_management','tb_asset_management.id_asset','temp2.id_asset')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')
            ->select('tb_asset_management_detail.pid','asset_owner','category','category_peripheral','tb_asset_management.id_asset','type_device','vendor','status','rma','spesifikasi','serial_number','notes'); 

        $countInstalled = DB::table($getLastId, 'temp2')->join('tb_asset_management','tb_asset_management.id_asset','temp2.id_asset')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')
            ->select('tb_asset_management_detail.pid','asset_owner','category','category_peripheral','tb_asset_management.id_asset','type_device','vendor','status','rma','spesifikasi','serial_number','notes')->where('status','Installed');  

        $countAvailable = DB::table($getLastId, 'temp2')->join('tb_asset_management','tb_asset_management.id_asset','temp2.id_asset')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')
            ->select('tb_asset_management_detail.pid','asset_owner','category','category_peripheral','tb_asset_management.id_asset','type_device','vendor','status','rma','spesifikasi','serial_number','notes')->where('status','Available');  

        $countRma = DB::table($getLastId, 'temp2')->join('tb_asset_management','tb_asset_management.id_asset','temp2.id_asset')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')
            ->select('tb_asset_management_detail.pid','asset_owner','category','category_peripheral','tb_asset_management.id_asset','type_device','vendor','status','rma','spesifikasi','serial_number','notes')->where('status','RMA');  

        if (isset($request->pid)) {
            $countAll->where('pid',$request->pid);
            $countInstalled->where('pid',$request->pid);
            $countAvailable->where('pid',$request->pid);
            $countRma->where('pid',$request->pid);
        }

        if (isset($request->assetOwner)) {
            $countAll->where('asset_owner',$request->assetOwner);
            $countInstalled->where('asset_owner',$request->assetOwner);
            $countAvailable->where('asset_owner',$request->assetOwner);
            $countRma->where('asset_owner',$request->assetOwner);
        }

        if (isset($request->category)) {
            $countAll->where('category',$request->category);
            $countInstalled->where('category',$request->category);
            $countAvailable->where('category',$request->category);
            $countRma->where('category',$request->category);
        } 

        if (isset($request->category)) {
            if ($request->category == 'Peripheral') {
                $countAll->where('category_peripheral','!=','-');
                $countInstalled->where('category_peripheral','!=','-');
                $countAvailable->where('category_peripheral','!=','-');
                $countRma->where('category_peripheral','!=','-');
            } else {
                $countAll->where('category',$request->category);
                $countInstalled->where('category',$request->category);
                $countAvailable->where('category',$request->category);
                $countRma->where('category',$request->category);
            }
        } 

        if (isset($request->client)) {
            $countAll->where('client',$request->client);
            $countInstalled->where('client',$request->client);
            $countAvailable->where('client',$request->client);
            $countRma->where('client',$request->client);
        } 

        return collect(["countAll"=>$countAll->count('tb_asset_management.id_asset'),"countInstalled"=>$countInstalled->count('tb_asset_management.id_asset'),"countAvailable"=>$countAvailable->count('tb_asset_management.id_asset'),"countRma"=>$countRma->count('tb_asset_management.id_asset')]);
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
                $store = new AssetMgmtAssignEngineer();
                $store->engineer_atm = $value['engineer'];
                $store->id_asset = $values;
                $store->save();
            }
        }
    }

    public function getIdAtm(Request $request)
    {

        $getId = AssetMgmt::join('tb_asset_management_detail','tb_asset_management_detail.id_asset','tb_asset_management.id_asset')->select('tb_asset_management_detail.id_asset','detail_lokasi','tb_asset_management_detail.id');
        $getLastId = DB::table($getId,'temp')->groupBy('id_asset')->selectRaw('MAX(`temp`.`id`) as `id_last_asset`')->selectRaw('id_asset');

        $data = DB::table($getLastId, 'temp2')->join('tb_asset_management','tb_asset_management.id_asset','temp2.id_asset')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')
            ->select(DB::raw('`tb_asset_management`.`id_asset` AS `id`,`tb_asset_management_detail`.`id_device_customer` AS `text`'))
            ->whereRaw("(`category` = 'ATM' OR `category` = 'CRM')")
            ->get(); 

        // $data = DB::table('tb_asset_management')->join('')->select(DB::raw('`id_asset` AS `id`,`id_asset` AS `text`'))->where('id_asset','like','%'.request('q').'%')->whereRaw("(`category` = 'ATM' OR `category` = 'CRM')")->get();
        return response()->json($data);
    }

    public function getEngineer(Request $request)
    {
        return $data = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select(DB::raw('`users`.`name` AS `id`,`users`.`name` AS `text`'))->where('roles.name','Engineer on Site')->where('status_karyawan','!=','dummy')->where('users.name','like','%'.request('q').'%')->get();
    }

    // public function getAssignedEngineer(Request $request)
    // {
    //     $data = AssetMgmtAssignEngineer::join('tb_asset_management','tb_asset_management.id_asset','tb_asset_management_assign_engineer.id_asset')->select(DB::raw('`name` AS `id`,`users`.`name` AS `text`'))->get();
    //     return $data;
    // }
}
