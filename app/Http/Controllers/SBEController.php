<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use PDF;
use App\SbeRelation;
use App\SbeActivity;
use App\SbeConfig;
use App\SbeDetailConfig;
use App\SbeDetailItem;
use App\SbeNotes;
use App\SbeSow;
use App\Sales;
use App\Sbe;
use App\SbeDocument;
use App\User;

use Carbon\Carbon;
use Google_Client;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

use Mail;
use App\Mail\MailReviewConfigSBE;

class SBEController extends Controller
{
	public function __construct()
    {
        set_time_limit(8000000);
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

    public function sbe_index(){
        return view('solution.sbe')->with(['initView'=> $this->initMenuBase(),'feature_item'=>$this->RoleDynamic('Solution')]);
    }

    public function setting_sbe(){
        return view('solution.setting')->with(['initView'=> $this->initMenuBase(),'feature_item'=>$this->RoleDynamic('Solution')]);
    }

    public function sbe_detail(Request $request)
    {
        $opp_name = DB::table('sales_lead_register')->select('lead_id','opp_name')->where('lead_id',$request->lead_id)->first();

        return view('solution.sbe_detail',compact('opp_name'))->with(['initView'=> $this->initMenuBase(),'feature_item'=>$this->RoleDynamic('Solution')]);
    }

    public function getLead(Request $request)
    {
    	$nik = Auth::User()->nik;
    	$cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')
                    ->select('name', 'roles.group','mini_group')->where('user_id', $nik)->first(); 

        $data = DB::table('sales_lead_register')->join('sales_solution_design','sales_solution_design.lead_id','sales_lead_register.lead_id')->join('users','users.nik','sales_lead_register.nik')->select(DB::raw('`sales_lead_register`.`lead_id` AS `id`,CONCAT(`sales_lead_register`.`lead_id`," - ",`opp_name`) AS `text`'))->where('id_company','1')
            // ->where('sales_lead_register.result','!=','WIN')
        ->whereRaw("(`sales_lead_register`.`result` = '' OR `sales_lead_register`.`result` = 'SD' OR `sales_lead_register`.`result` = 'TP')")
            ->orderBy('year','desc');

        if ($cek_role->name == 'VP Solutions & Partnership Management' || $cek_role->mini_group == 'Solution Architect' || $cek_role->name == 'Technology Alliance Solutions' || Auth::User()->nik == '1221199080' || Auth::User()->nik == '1230896110') {
            if ($cek_role->name == 'Technology Alliance Solutions') {
                $data->where('sales_solution_design.nik',$nik)->orwhere('sales_solution_design.nik_ta',$nik)->get();
            } else {
                $data->where('sales_solution_design.nik',$nik)->get();
            }
        	
        } else {
        	$data->get();
        }

        return $data->get();
    }

    public function createConfig(Request $request)
    {
        if (DB::table('tb_sbe')->where('lead_id',$request->inputLead)->exists()) {
            $create = DB::table('tb_sbe')->select('tb_sbe.id')->where('lead_id',$request->inputLead)->first();
        } else {
            $create = new Sbe();
            $create->lead_id = $request->inputLead;
            $create->status = 'Temporary';
            $create->save();
        }

        // return $create->id;

    	// $createSow = new SbeSow();
    	// $createSow->sow = $request->textareaSOW;
    	// $createSow->oos = $request->textareaScope;
     //    $createSow->id_sbe = $create->id;
     //    $createSow->lead_id = $request->inputLead;
    	// $createSow->date_add = Carbon::now()->toDateTimeString();
    	// $createSow->save();

        $dataSO = json_decode($request->arrItemSO,true);
        $dataImp = json_decode($request->arrItemImp,true);
        $dataMnS = json_decode($request->arrItemMnS,true);

    	if (count($dataSO) != 0 ) {
            $countVersion = DB::table('tb_sbe_config')->where('project_type','Supply Only')->where('id_sbe', $create->id)->count()+1;

            if (DB::table('tb_sbe')->where('lead_id',$request->inputLead)->exists()) {
                $getAllId = SbeConfig::where('id_sbe',$create->id)->where('project_type','Supply Only')->get();
                foreach ($getAllId as $key => $value) {
                    // return $value;
                    $updateVersion = SbeConfig::where('id',$value->id)->first();
                    $updateVersion->status = 'New';
                    $updateVersion->save();
                }
            }

    		$createConfig = new SbeConfig();
            $createConfig->id_sbe = $create->id;
	    	$createConfig->project_location = $request->textareaLoc;
    		$createConfig->project_type = 'Supply Only';
	    	$createConfig->duration = $request->durationSO;
	    	$createConfig->estimated_running = $request->inputEstimatedRun;
            $createConfig->date_add = Carbon::now()->toDateTimeString();
            $createConfig->status = 'Choosed';
            $createConfig->sow = $request->textareaSOWSo;
            $createConfig->oos = $request->textareaScopeSo;
            $createConfig->version = $countVersion;
            $createConfig->save();
	    	
    		foreach ($dataSO as $key => $value) {
	    		$createDetailConfig = new SbeDetailConfig();
	    		$createDetailConfig->id_config_sbe = $createConfig->id;
	    		$createDetailConfig->item = $value['items'];
	    		$createDetailConfig->detail_item = $value['detailItems'];
	    		$createDetailConfig->qty = $value['qtyItems'];
	    		$createDetailConfig->manpower = $value['manpower'];
	    		$createDetailConfig->price = str_replace('.', '', $value['priceItems']);
	    		$createDetailConfig->total_nominal = (int)str_replace('.', '', $value['priceItems'])*(int)$value['qtyItems']*(int)$value['manpower'];
                $createDetailConfig->date_add = Carbon::now()->toDateTimeString();
	    		$createDetailConfig->save();
	    	}

            $nominalConfig = DB::table('tb_sbe_config')->join('tb_sbe','tb_sbe.id','tb_sbe_config.id_sbe')->join('tb_sbe_detail_config','tb_sbe_detail_config.id_config_sbe','tb_sbe_config.id')->select('item','detail_item','total_nominal','qty','price','manpower')->where('tb_sbe_config.id',$createConfig->id)->where('tb_sbe_config.status','Choosed')->orderBy('item','asc')->distinct()->get();

            $totalNominalConfig = 0;
            foreach($nominalConfig as $key_point => $valueSumPoint){
                $totalNominalConfig += $valueSumPoint->total_nominal;
            }

            $updateConfig = SbeConfig::where('id',$createConfig->id)->first();
            $updateConfig->nominal = $totalNominalConfig;
            $updateConfig->save();

            $storeActivity = new SbeActivity();
            $storeActivity->id_sbe = $create->id;
            $storeActivity->operator = Auth::User()->nik;
            $storeActivity->activity = 'Create config ' . $createConfig->project_type . ' with amount ' . $updateConfig->nominal;
            $storeActivity->date_add = Carbon::now()->toDateTimeString();
            $storeActivity->save();


            $data = DB::table('tb_sbe')->join('tb_sbe_config','tb_sbe_config.id_sbe','tb_sbe.id')->join('tb_sbe_detail_config','tb_sbe_detail_config.id_config_sbe','tb_sbe_config.id')->select('item','detail_item','total_nominal','qty','price','manpower')->where('tb_sbe.id',$create->id)->where('tb_sbe_config.status','Choosed')->orderBy('item','asc')->distinct()->get();

            $total_nominal = 0;
            foreach($data as $key_point => $valueSumPoint){
                $total_nominal += $valueSumPoint->total_nominal;
            }

            $storeRelation = new SbeRelation();
            $storeRelation->lead_id = $request->inputLead;
            $storeRelation->tag_sbe = '1';
            $storeRelation->price_sbe = $total_nominal;
            $storeRelation->save();
    	}

    	if (count($dataImp) != 0 ) {
            $countVersion = DB::table('tb_sbe_config')->where('project_type','Implementation')->where('id_sbe', $create->id)->count()+1;

            if (DB::table('tb_sbe')->where('lead_id',$request->inputLead)->exists()) {
                $getAllId = SbeConfig::where('id_sbe',$create->id)->where('project_type','Implementation')->get();
                foreach ($getAllId as $key => $value) {
                    // return $value;
                    $updateVersion = SbeConfig::where('id',$value->id)->first();
                    $updateVersion->status = 'New';
                    $updateVersion->save();
                }
            }

    		$createConfig = new SbeConfig();
	    	$createConfig->id_sbe = $create->id;
	    	$createConfig->project_location = $request->textareaLoc;
    		$createConfig->project_type = 'Implementation';
	    	$createConfig->duration = $request->durationImp;
	    	$createConfig->estimated_running = $request->inputEstimatedRun;
            $createConfig->date_add = Carbon::now()->toDateTimeString();
            $createConfig->status = 'Choosed';
            $createConfig->sow = $request->textareaSOWImp;
            $createConfig->oos = $request->textareaScopeImp;
            $createConfig->version = $countVersion;
            $createConfig->save();

    		foreach ($dataImp as $key => $value) {
	    		$createDetailConfig = new SbeDetailConfig();
	    		$createDetailConfig->id_config_sbe = $createConfig->id;
	    		$createDetailConfig->item = $value['items'];
	    		$createDetailConfig->detail_item = $value['detailItems'];
	    		$createDetailConfig->qty = $value['qtyItems'];
	    		$createDetailConfig->manpower = $value['manpower'];
	    		$createDetailConfig->price = str_replace('.', '', $value['priceItems']);
	    		$createDetailConfig->total_nominal = (int)str_replace('.', '', $value['priceItems'])*(int)$value['qtyItems']*(int)$value['manpower'];
                $createDetailConfig->date_add = Carbon::now()->toDateTimeString();
	    		$createDetailConfig->save();
	    	}

            $nominalConfig = DB::table('tb_sbe_config')->join('tb_sbe','tb_sbe.id','tb_sbe_config.id_sbe')->join('tb_sbe_detail_config','tb_sbe_detail_config.id_config_sbe','tb_sbe_config.id')->select('item','detail_item','total_nominal','qty','price','manpower')->where('tb_sbe_config.id',$createConfig->id)->where('tb_sbe_config.status','Choosed')->orderBy('item','asc')->distinct()->get();

            $totalNominalConfig = 0;
            foreach($nominalConfig as $key_point => $valueSumPoint){
                $totalNominalConfig += $valueSumPoint->total_nominal;
            }

            $updateConfig = SbeConfig::where('id',$createConfig->id)->first();
            $updateConfig->nominal = $totalNominalConfig;
            $updateConfig->save();

            $storeActivity = new SbeActivity();
            $storeActivity->id_sbe = $create->id;
            $storeActivity->operator = Auth::User()->nik;
            $storeActivity->activity = 'Create config ' . $createConfig->project_type . ' with amount ' . $updateConfig->nominal;
            $storeActivity->date_add = Carbon::now()->toDateTimeString();
            $storeActivity->save();


            $data = DB::table('tb_sbe')->join('tb_sbe_config','tb_sbe_config.id_sbe','tb_sbe.id')->join('tb_sbe_detail_config','tb_sbe_detail_config.id_config_sbe','tb_sbe_config.id')->select('item','detail_item','total_nominal','qty','price','manpower')->where('tb_sbe.id',$create->id)->where('tb_sbe_config.status','Choosed')->orderBy('item','asc')->distinct()->get();

            $total_nominal = 0;
            foreach($data as $key_point => $valueSumPoint){
                $total_nominal += $valueSumPoint->total_nominal;
            }


            $storeRelation = new SbeRelation();
            $storeRelation->lead_id = $request->inputLead;
            $storeRelation->tag_sbe = '4';
            $storeRelation->price_sbe = $total_nominal;
            $storeRelation->save();
    	}

    	if (count($dataMnS) != 0 ) {
            $countVersion = DB::table('tb_sbe_config')->where('project_type','Maintenance')->where('id_sbe', $create->id)->count()+1;

            if (DB::table('tb_sbe')->where('lead_id',$request->inputLead)->exists()) {
                $getAllId = SbeConfig::where('id_sbe',$create->id)->where('project_type','Maintenance')->get();
                foreach ($getAllId as $key => $value) {
                    // return $value;
                    $updateVersion = SbeConfig::where('id',$value->id)->first();
                    $updateVersion->status = 'New';
                    $updateVersion->save();
                }
            }

    		$createConfig = new SbeConfig();
	    	$createConfig->id_sbe = $create->id;
	    	$createConfig->project_location = $request->textareaLoc;
    		$createConfig->project_type = 'Maintenance';
	    	$createConfig->duration = $request->durationMnS;
	    	$createConfig->estimated_running = $request->inputEstimatedRun;
            $createConfig->date_add = Carbon::now()->toDateTimeString();
            $createConfig->status = 'Choosed';
            $createConfig->sow = $request->textareaSOWMnS;
            $createConfig->oos = $request->textareaScopeMnS;
            $createConfig->version = $countVersion;
            $createConfig->save();

    		foreach ($dataMnS as $key => $value) {
	    		$createDetailConfig = new SbeDetailConfig();
	    		$createDetailConfig->id_config_sbe = $createConfig->id;
	    		$createDetailConfig->item = $value['items'];
	    		$createDetailConfig->detail_item = $value['detailItems'];
	    		$createDetailConfig->qty = $value['qtyItems'];
	    		$createDetailConfig->manpower = $value['manpower'];
	    		$createDetailConfig->price = str_replace('.', '', $value['priceItems']);
	    		$createDetailConfig->total_nominal = (int)str_replace('.', '', $value['priceItems'])*(int)$value['qtyItems']*(int)$value['manpower'];
                $createDetailConfig->date_add = Carbon::now()->toDateTimeString();
	    		$createDetailConfig->save();
	    	}
            $nominalConfig = DB::table('tb_sbe_config')->join('tb_sbe','tb_sbe.id','tb_sbe_config.id_sbe')->join('tb_sbe_detail_config','tb_sbe_detail_config.id_config_sbe','tb_sbe_config.id')->select('item','detail_item','total_nominal','qty','price','manpower')->where('tb_sbe_config.id',$createConfig->id)->where('tb_sbe_config.status','Choosed')->orderBy('item','asc')->distinct()->get();

            $totalNominalConfig = 0;
            foreach($nominalConfig as $key_point => $valueSumPoint){
                $totalNominalConfig += $valueSumPoint->total_nominal;
            }

            $updateConfig = SbeConfig::where('id',$createConfig->id)->first();
            $updateConfig->nominal = $totalNominalConfig;
            $updateConfig->save();

            $storeActivity = new SbeActivity();
            $storeActivity->id_sbe = $create->id;
            $storeActivity->operator = Auth::User()->nik;
            $storeActivity->activity = 'Create config ' . $createConfig->project_type . ' with amount ' . $updateConfig->nominal;
            $storeActivity->date_add = Carbon::now()->toDateTimeString();
            $storeActivity->save();


            $data = DB::table('tb_sbe')->join('tb_sbe_config','tb_sbe_config.id_sbe','tb_sbe.id')->join('tb_sbe_detail_config','tb_sbe_detail_config.id_config_sbe','tb_sbe_config.id')->select('item','detail_item','total_nominal','qty','price','manpower')->where('tb_sbe.id',$create->id)->where('tb_sbe_config.status','Choosed')->orderBy('item','asc')->distinct()->get();

            $total_nominal = 0;
            foreach($data as $key_point => $valueSumPoint){
                $total_nominal += $valueSumPoint->total_nominal;
            }


            $storeRelation = new SbeRelation();
            $storeRelation->lead_id = $request->inputLead;
            $storeRelation->tag_sbe = '2';
            $storeRelation->price_sbe = $total_nominal;
            $storeRelation->save();
    	}

        $updateNominalSbe = Sbe::where('id',$create->id)->first();
        $updateNominalSbe->nominal = DB::table('tb_sbe_config')->where('status','Choosed')->where('id_sbe',$create->id)->groupby('id_sbe')->sum('nominal');
        $updateNominalSbe->save();

        $email_user = User::join('role_user','role_user.user_id', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name', 'VP Solutions & Partnership Management')->first()->email;

        $mail = new MailReviewConfigSBE(collect([
                "subject_email" => 'Please Review this Temporary SBE',
                "to"            => User::join('role_user','role_user.user_id', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name', 'VP Solutions & Partnership Management')->select('users.name as name')->first()->name,
                "data"          => SbeConfig::where('status','Choosed')->where('id_sbe', $create->id)->orderByRaw('FIELD(project_type, "Supply Only", "Implementation", "Maintenance")')->get()->makeHidden(['detail_config','detail_all_config_choosed'])->groupby('project_type'),
                "status"        => 'Review SBE',
                "id"            => $create->id,
                "lead_id"       => $request->inputLead
            ])
        );

        

        $nik = Auth::User()->nik;
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')
                    ->select('name', 'roles.group')->where('user_id', $nik)->first(); 
        if ($cek_role->name != 'VP Solutions & Partnership Management') {
            Mail::to($email_user)->send($mail);
        }
    }

    public function storeDetailItem(Request $request)
    {
    	$store = new SbeDetailItem();
        $store->detail_item = $request->InputItem;
        $store->price = str_replace('.', '', $request['InputPrice']);
        $store->save();
    }

    public function updateDetailItem(Request $request)
    {
        $update = SbeDetailItem::where('id',$request->id)->first();
        $update->detail_item = $request->InputItem;
        $update->price = str_replace('.', '', $request['InputPrice']);
        $update->save();
    }

    public function getDetailItem(Request $request)
    {
        $data = SbeDetailItem::get();
        return array("data"=>$data);
    }

    public function getDropdownDetailItem(Request $request)
    {
        $data = DB::table('tb_sbe_detail_item')->select(DB::raw('`id` AS `id`,`detail_item` AS `text`,`price`'));

        if (isset($request->id)) {
            $data->where('id', $request->id);
        }

        return array("data" => $data->get());
    }

    public function getDataSbe(Request $request)
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')
                    ->select('name', 'roles.group')->where('user_id', $nik)->first(); 

        $getPresales = DB::table('sales_solution_design')->join('users', 'users.nik', '=','sales_solution_design.nik')->selectRaw('GROUP_CONCAT(`users`.`name`) AS `name_presales`, GROUP_CONCAT(`sales_solution_design`.`nik`) AS `nik_presales`')->selectRaw('lead_id')->groupBy('lead_id');

        $getTa = DB::table('sales_solution_design')->join('users', 'users.nik', '=','sales_solution_design.nik_ta')->selectRaw('`users`.`name` AS `name_ta`, `sales_solution_design`.`nik_ta` AS `nik_ta`')->selectRaw('lead_id')->distinct();

        $data = Sbe::join('sales_solution_design','sales_solution_design.lead_id','tb_sbe.lead_id')
        ->join('sales_lead_register','sales_lead_register.lead_id','tb_sbe.lead_id')
        // ->join('users','users.nik','sales_solution_design.nik')
        ->leftJoinSub($getPresales, 'tb_presales',function($join){
            $join->on("sales_lead_register.lead_id", '=', 'tb_presales.lead_id');
        })
        ->leftJoinSub($getTa, 'tb_ta',function($join){
            $join->on("sales_lead_register.lead_id", '=', 'tb_ta.lead_id');
        })
        ->join('users as u_sales', 'u_sales.nik', '=', 'sales_lead_register.nik')
        ->select('tb_sbe.lead_id','tb_sbe.status','opp_name','name_presales as presales','name_ta as ta','tb_sbe.nominal as detail_config_nominal','tb_sbe.id');

        if ($cek_role->name == 'Presales Support Architecture' || $cek_role->name == 'System Designer Architecture' || $cek_role->name == 'Technology Alliance Solutions') {
            if ($cek_role->name == 'Technology Alliance Solutions') {
                $data->where('tb_presales.nik_presales',$nik)->orwhere('tb_ta.nik_ta',$nik)->distinct()->get()->makeHidden('items_sbe');
            } else {
                $data->where('tb_presales.nik_presales',$nik)->distinct()->get()->makeHidden('items_sbe');
            }
            
        } elseif($cek_role->name == 'Account Executive'){
            $data->where('sales_lead_register.nik',$nik)->distinct()->get()->makeHidden('items_sbe');
        } else if($cek_role->name == 'VP Sales'){
            $data->where('u_sales.id_territory',$ter)->distinct()->get()->makeHidden('items_sbe');
        } else if($cek_role->name == 'PMO Officer'){
            $data->where('sales_lead_register.result','WIN')->distinct()->get()->makeHidden('items_sbe');
        }else {
            $data->distinct()->get()->makeHidden('items_sbe');
        }

        return array("data"=>$data->get()->makeHidden('items_sbe'));
    }

    public function getSoWbyLeadID(Request $request)
    {
        return $data = SbeSow::where('lead_id',$request->lead_id)->first();
    }

    public function getActivity(Request $request)
    {
        $data = SbeActivity::join('users','users.nik','tb_sbe_activity.operator')->join('role_user','users.nik','role_user.user_id')->join('roles','roles.id','role_user.role_id')->select('users.name','activity','date_add','roles.name as role')->where('id_sbe',$request->id_sbe)->orderBy('date_add','desc')->get();

        $activity = DB::table('tb_sbe_activity')->where('id_sbe',$request->id_sbe)->orderBy('date_add','desc')->first()->activity;

        if (substr($activity, 0,9) == 'Add Notes') {
            $getNotes = SbeNotes::where('id_sbe',$request->id_sbe)->orderBy('date_add','desc')->take(1)->get();
        } else{
            $getNotes = array();
        }

        $status = DB::table('tb_sbe')->select('status')->where('id',$request->id_sbe)->get();

        $presales = DB::table('tb_sbe')->join('sales_solution_design','sales_solution_design.lead_id','tb_sbe.lead_id')->select('sales_solution_design.nik')->where('id',$request->id_sbe)->get()->pluck('nik');

        $presales = $presales->toArray();

        $getLeadId = DB::table('tb_sbe')->select('lead_id')->where('id',$request->id_sbe)->first()->lead_id;

        $getResultLeadId = DB::table('sales_lead_register')->select('result')->where('lead_id',$getLeadId)->first()->result;

        return collect([
            "data" => $data,
            "getNotes" => $getNotes,
            "status" => $status,
            "presales" => $presales,
            "result" => $getResultLeadId
        ]);
    }

    public function storeNotes(Request $request)
    {
        $storeNotes = new SbeNotes();
        $storeNotes->id_sbe = $request->id_sbe;
        $storeNotes->notes = $request->inputNotes;
        $storeNotes->operator = Auth::User()->nik;
        $storeNotes->date_add = Carbon::now()->toDateTimeString();
        $storeNotes->save();

        $storeActivity = new SbeActivity();
        $storeActivity->id_sbe = $request->id_sbe;
        $storeActivity->operator = Auth::User()->nik;
        $storeActivity->activity = 'Add Notes ' . $request->inputNotes;
        $storeActivity->date_add = Carbon::now()->toDateTimeString();
        $storeActivity->save();

        $getLeadId = DB::table('tb_sbe')->where('id',$request->id_sbe)->first()->lead_id;

        $email_user = User::join('role_user','role_user.user_id', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->join('sales_solution_design','sales_solution_design.nik','users.nik')->where('lead_id',$getLeadId)->first()->email;

        $mail = new MailReviewConfigSBE(collect([
                "subject_email" => 'Add Notes on Lead ID ' . $getLeadId,
                "to"            => User::join('role_user','role_user.user_id', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->join('sales_solution_design','sales_solution_design.nik','users.nik')->select('users.name')->where('lead_id',$getLeadId)->first()->name,
                "data"          => SbeConfig::where('status','Choosed')->where('id_sbe',$request->id_sbe)->orderByRaw('FIELD(project_type, "Supply Only", "Implementation", "Maintenance")')->get()->makeHidden(['detail_config','detail_all_config_choosed'])->groupby('project_type'),
                "notes"          => $request->inputNotes,
                "status"        => 'Add Notes',
                "id"            => $request->id_sbe,
                "lead_id"       => $getLeadId
            ])
        );

        Mail::to($email_user)->send($mail);
    }

    public function getVersionConfig(Request $request)
    {
        // $data = SbeConfig::where('id_sbe',$request->id_sbe)->orderBy('date_add','asc')->orderByRaw('FIELD(project_type, "Supply Only", "Implementation", "Maintenance")')->get()->makeHidden(['detail_config','get_function'])->groupby('project_type');
        // return $data;

        return collect([
            "Supply Only" => SbeConfig::where('id_sbe',$request->id_sbe)->orderBy('date_add','asc')->where('project_type', 'Supply Only')->get()->makeHidden(['detail_config','get_function','detail_all_config_choosed']), 
            "Implementation" => SbeConfig::where('id_sbe',$request->id_sbe)->orderBy('date_add','asc')->where('project_type', 'Implementation')->get()->makeHidden(['detail_config','get_function','detail_all_config_choosed']),
            "Maintenance" => SbeConfig::where('id_sbe',$request->id_sbe)->orderBy('date_add','asc')->where('project_type', 'Maintenance')->get()->makeHidden(['detail_config','get_function','detail_all_config_choosed'])
        ]);
    }

    public function getDetailConfig(Request $request)
    {
        $data = SbeConfig::where('id',$request->id_config_sbe)->first()->makeHidden(['get_function','detail_all_config_choosed']);

        return $data;
    }

    public function updateConfig(Request $request)
    {
        $getProjectType = DB::table('tb_sbe_config')->join('tb_sbe','tb_sbe_config.id_sbe','tb_sbe.id')->select('id_sbe','project_type','lead_id')->where('tb_sbe_config.id',$request->id_config_sbe)->first();

        $countVersion = DB::table('tb_sbe_config')->where('project_type',$getProjectType->project_type)->where('id_sbe', $request->id_sbe)->count()+1;

        $getAllId = SbeConfig::where('id_sbe',$request->id_sbe)->where('project_type',$getProjectType->project_type)->get();
        foreach ($getAllId as $key => $value) {
            // return $value;
            $updateVersion = SbeConfig::where('id',$value->id)->first();
            $updateVersion->status = 'New';
            $updateVersion->save();
        }

        $createConfig = new SbeConfig();
        $createConfig->id_sbe = $request->id_sbe;
        $createConfig->project_location = $request->textareaLocUpdate;
        $createConfig->project_type = $getProjectType->project_type;
        $createConfig->duration = $request->inputDurationUpdate;
        $createConfig->estimated_running = $request->inputEstimatedRunUpdate;
        $createConfig->date_add = Carbon::now()->toDateTimeString();
        $createConfig->status = 'Choosed';
        $createConfig->sow = $request->textareaSOWUpdate;
        $createConfig->oos = $request->textareaScopeUpdate;
        $createConfig->version = $countVersion;
        $createConfig->save();

        $dataItem = json_decode($request->arrItemsUpdate,true);
        
        foreach ($dataItem as $key => $value) {
            $createDetailConfig = new SbeDetailConfig();
            $createDetailConfig->id_config_sbe = $createConfig->id;
            $createDetailConfig->item = $value['items'];
            $createDetailConfig->detail_item = $value['detailItems'];
            $createDetailConfig->qty = $value['qtyItems'];
            $createDetailConfig->manpower = $value['manpower'];
            $createDetailConfig->price = str_replace('.', '', $value['priceItems']);
            $createDetailConfig->total_nominal = (int)str_replace('.', '', $value['priceItems'])*(int)$value['qtyItems']*(int)$value['manpower'];
            $createDetailConfig->date_add = Carbon::now()->toDateTimeString();
            $createDetailConfig->save();
        }


        $nominalConfig = DB::table('tb_sbe_config')->join('tb_sbe_detail_config','tb_sbe_detail_config.id_config_sbe','tb_sbe_config.id')->select('item','detail_item','total_nominal','qty','price','manpower')->where('tb_sbe_config.id',$createConfig->id)->where('tb_sbe_config.status','Choosed')->orderBy('item','asc')->distinct()->get();

        $totalNominalConfig = 0;
        foreach($nominalConfig as $key_point => $valueSumPoint){
            $totalNominalConfig += $valueSumPoint->total_nominal;
        }

        $updateConfig = SbeConfig::where('id',$createConfig->id)->first();
        $updateConfig->nominal = $totalNominalConfig;
        $updateConfig->save();


        // $updateConfig = SbeConfig::where('id',$createConfig->id)->first();
        // $updateConfig->nominal = DB::table('tb_sbe_detail_config')->where('id_config_sbe',$createConfig->id)->groupby('id_config_sbe')->sum('total_nominal');
        // $updateConfig->save();

        $updateNominalSbe = Sbe::where('id',$request->id_sbe)->first();
        $updateNominalSbe->nominal = DB::table('tb_sbe_config')->where('id_sbe',$request->id_sbe)->where('status','Choosed')->groupby('id_sbe')->sum('nominal');
        $updateNominalSbe->save();

        $storeActivity = new SbeActivity();
        $storeActivity->id_sbe = $request->id_sbe;
        $storeActivity->operator = Auth::User()->nik;
        $storeActivity->activity = 'Create config ' . $createConfig->project_type . ' with amount ' . $updateConfig->nominal;
        $storeActivity->date_add = Carbon::now()->toDateTimeString();
        $storeActivity->save();

        $email_user = User::join('role_user','role_user.user_id', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name', 'VP Solutions & Partnership Management')->first()->email;

        $mail = new MailReviewConfigSBE(collect([
                "subject_email" => 'Please Review this Temporary SBE',
                "to"            => User::join('role_user','role_user.user_id', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name', 'VP Solutions & Partnership Management')->select('users.name as name')->first()->name,
                "data"          => SbeConfig::where('status','Choosed')->where('id_sbe',$request->id_sbe)->orderByRaw('FIELD(project_type, "Supply Only", "Implementation", "Maintenance")')->get()->makeHidden(['detail_config','detail_all_config_choosed'])->groupby('project_type'),
                "status"        => 'Review SBE',
                "id"            => $request->id_sbe,
                "lead_id"       => $getProjectType->lead_id

            ])
        );

        $nik = Auth::User()->nik;
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')
                    ->select('name', 'roles.group')->where('user_id', $nik)->first(); 
        if ($cek_role->name != 'VP Solutions & Partnership Management') {
            Mail::to($email_user)->send($mail);
        }
    }

    public function getConfigChoosed(Request $request)
    {
        $dataConfig = json_decode($request->arrChoosed,true);

        $data = SbeConfig::whereIn('id',$dataConfig)->orderByRaw('FIELD(project_type, "Supply Only", "Implementation", "Maintenance")')->get()->makeHidden(['detail_config','detail_all_config_choosed'])->groupby('project_type');

        return $data;
    }

    public function updateVersion(Request $request)
    {
        $dataVersion = json_decode($request->arrVersion,true);

        $getId = DB::table('tb_sbe_config')->join('tb_sbe','tb_sbe.id','tb_sbe_config.id_sbe')->where('tb_sbe_config.id',$dataVersion[0])->first();

        // return SbeConfig::where('status','Choosed')->where('id_sbe',$getId->id_sbe)->orderByRaw('FIELD(project_type, "Supply Only", "Implementation", "Maintenance")')->get()->makeHidden(['detail_config','detail_all_config_choosed'])->groupby('project_type');

        $getLead = DB::table('tb_sbe_relation')->where('lead_id',$getId->lead_id)->get();
        foreach ($getLead as $key => $value) {
            SbeRelation::where('id',$value->id)->delete(); 
        }

        foreach ($dataVersion as $key => $value) {
            $updateVersion = SbeConfig::where('id',$value)->first();
            $updateVersion->status = 'Choosed';
            $updateVersion->save();

            $storeActivity = new SbeActivity();
            $storeActivity->id_sbe = $getId->id_sbe;
            $storeActivity->operator = Auth::User()->nik;
            $storeActivity->activity = 'Choose config version ' . $updateVersion->version . ' project type ' . $updateVersion->project_type . ' with nominal ' . str_replace('.', '', $updateVersion->nominal);
            $storeActivity->date_add = Carbon::now()->toDateTimeString();
            $storeActivity->save();


            $data = DB::table('tb_sbe_config')->join('tb_sbe','tb_sbe.id','tb_sbe_config.id_sbe')->join('tb_sbe_detail_config','tb_sbe_detail_config.id_config_sbe','tb_sbe_config.id')->select('item','detail_item','total_nominal','qty','price','manpower')->where('tb_sbe_config.id',$value)->orderBy('item','asc')->distinct()->get();

            $total_nominal = 0;
            foreach($data as $key_point => $valueSumPoint){
                $total_nominal += $valueSumPoint->total_nominal;
            }

            // return $total_nominal;


            $storeRelation = new SbeRelation();
            $storeRelation->lead_id = $getId->lead_id;
            $storeRelation->price_sbe = str_replace('.', '', $total_nominal);
            if ($updateVersion->project_type == 'Supply Only') {
                $storeRelation->tag_sbe = '1';
            } elseif ($updateVersion->project_type == 'Implementation') {
                $storeRelation->tag_sbe = '4';
            } elseif ($updateVersion->project_type == 'Maintenance') {
                $storeRelation->tag_sbe = '2';
            }
            
            $storeRelation->save();
        }

        $getAllId = SbeConfig::where('id_sbe',$getId->id_sbe)->whereNotIn('id',$dataVersion)->get();
        foreach ($getAllId as $key => $value) {
            $updateVersion = SbeConfig::where('id',$value->id)->first();
            $updateVersion->status = 'New';
            $updateVersion->save();
        }

        $update = Sbe::where('id',$getId->id_sbe)->first();
        $update->nominal = DB::table('tb_sbe_config')->where('id_sbe',$getId->id_sbe)->where('status','Choosed')->groupby('id_sbe')->sum('nominal');
        $update->save();


        $email_user = User::join('role_user','role_user.user_id', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name', 'VP Solutions & Partnership Management')->first()->email;

        $mail = new MailReviewConfigSBE(collect([
                "subject_email" => 'Please Review this Temporary SBE',
                "to"            => User::join('role_user','role_user.user_id', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name', 'VP Solutions & Partnership Management')->select('users.name as name')->first()->name,
                "data"          => SbeConfig::where('status','Choosed')->where('id_sbe',$getId->id_sbe)->orderByRaw('FIELD(project_type, "Supply Only", "Implementation", "Maintenance")')->get()->makeHidden(['detail_config','detail_all_config_choosed'])->groupby('project_type'),
                "status"        => 'Review SBE',
                "id"            => $getId->id_sbe,
                "lead_id"       => $getId->lead_id

            ])
        );

        $nik = Auth::User()->nik;
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')
                    ->select('name', 'roles.group')->where('user_id', $nik)->first(); 
        if ($cek_role->name != 'VP Solutions & Partnership Management') {
            Mail::to($email_user)->send($mail);
        }
        
    }

    public function resetVersion(Request $request)
    {
        $getId = SbeConfig::select('id_sbe','tb_sbe_config.id')->where('id_sbe',$request->id_sbe)->get()->makeHidden(['detail_config','get_function','detail_all_config_choosed']);

        $getLeadId = DB::table('tb_sbe')->join('tb_sbe_config','tb_sbe_config.id_sbe','tb_sbe.id')->where('id_sbe',$request->id_sbe)->first();

        $getLead = DB::table('tb_sbe_relation')->where('lead_id',$getLeadId->lead_id)->get();

        foreach ($getLead as $key => $value) {
            SbeRelation::where('id',$value->id)->delete(); 
        }

        foreach ($getId as $key => $value) {
            $updateVersion = SbeConfig::where('id',$value->id)->first();
            $updateVersion->status = 'New';
            $updateVersion->save();
        }

        $updateNominalSbe = Sbe::where('id',$request->id_sbe)->first();
        $updateNominalSbe->nominal = '0';
        $updateNominalSbe->save();

        $storeActivity = new SbeActivity();
        $storeActivity->id_sbe = $request->id_sbe;
        $storeActivity->operator = Auth::User()->nik;
        $storeActivity->activity = 'Reset Chosen Version';
        $storeActivity->date_add = Carbon::now()->toDateTimeString();
        $storeActivity->save();
    }

    public function getConfigTemporary(Request $request)
    {
        $getId = DB::table('tb_sbe_config')->select('id')->where('id_sbe',$request->id_sbe)->first();

        return $data = SbeConfig::where('status','Choosed')->where('id_sbe',$request->id_sbe)->orderByRaw('FIELD(project_type, "Supply Only", "Implementation", "Maintenance")')->get()->makeHidden(['detail_config','detail_all_config_choosed'])->groupby('project_type');

        // return $data = SbeDetailConfig::join('tb_sbe_config','tb_sbe_config.id','tb_sbe_detail_config.id_config_sbe')
        //     ->select(DB::raw('`item` as function,SUM(total_nominal) as total_nominal,project_type,id_config_sbe'))
        //     ->where('id_config_sbe',$getId->id)
        //     ->where('status','Choosed')
        //     ->groupby('function')->get();
    }

    public function getGenerateConfig($id_sbe)
    {
        $getFunction = SbeConfig::where('status','Choosed')->where('id_sbe',$id_sbe)->orderByRaw('FIELD(project_type, "Supply Only", "Implementation", "Maintenance")')->get()->makeHidden(['detail_config','detail_all_config_choosed'])->groupby('project_type');

        $getPresales = DB::table('tb_sbe')->join('sales_solution_design','sales_solution_design.lead_id','tb_sbe.lead_id')->where('id',$id_sbe)->first();

        $getAll = DB::table('tb_sbe_config')->join('tb_sbe','tb_sbe.id','tb_sbe_config.id_sbe')->join('sales_lead_register','sales_lead_register.lead_id','tb_sbe.lead_id')->join('users','users.nik','sales_lead_register.nik')->join('tb_contact','tb_contact.id_customer','sales_lead_register.id_customer')->select('tb_sbe.lead_id','users.name as owner','project_location','estimated_running','duration','opp_name','tb_sbe.nominal as grand_total','customer_legal_name')->where('id_sbe',$id_sbe)->first();

        // $getNominal = SBE::where('id',$request->id_sbe)->first()->detail_config_nominal;

        $getNominalConfig = DB::table('tb_sbe')->join('tb_sbe_config','tb_sbe_config.id_sbe','tb_sbe.id')->join('tb_sbe_detail_config','tb_sbe_detail_config.id_config_sbe','tb_sbe_config.id')->select('item','detail_item','total_nominal','qty','price','manpower')->where('tb_sbe.id',$id_sbe)->where('tb_sbe_config.status','Choosed')->orderBy('item','asc')->distinct()->get();

        $getNominal = 0;
            foreach($getNominalConfig as $key_point => $valueSumPoint){
            $getNominal += $valueSumPoint->total_nominal;
        }

        $getIdConfigSbe = DB::table('tb_sbe_config')->join('tb_sbe_detail_config','tb_sbe_detail_config.id_config_sbe','tb_sbe_config.id')->select('id_sbe','id_config_sbe')->where('tb_sbe_config.status','Choosed')->where('id_sbe',$id_sbe)->get();

        $getConfig = SbeConfig::where('status','Choosed')->where('id_sbe',$id_sbe)->orderByRaw('FIELD(project_type, "Supply Only", "Implementation", "Maintenance")')->get()->makeHidden(['detail_config'])->groupby('project_type');



        $user = SbeActivity::where('id_sbe',$id_sbe)->first()->operator;

        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')
                    ->select('name', 'roles.group','user_id')->where('user_id', $user)->first();

        $getSign = User::join('role_user', 'role_user.user_id', '=', 'users.nik')
                    ->join('roles', 'roles.id', '=', 'role_user.role_id')
                    ->select(
                        'users.name', 
                        'roles.name as position', 
                        'roles.group as group',
                        'users.ttd as ttd_digital',
                        'users.email',
                        'users.avatar'
                    )
                    ->where('users.id_company', '1')
                    ->where('users.status_karyawan', '!=', 'dummy');

        if ($cek_role->name == 'Technology Alliance Solutions') {
            $getSign = $getSign->whereRaw("(`users`.`nik` = '" . $getPresales->nik_ta . "' OR `roles`.`name` = 'VP Solutions & Partnership Management')")
            ->orderByRaw('FIELD(position, "Technology Alliance Solutions","VP Solutions & Partnership Management","Presales")')->take(2)
            ->get();
        }else {
            $getSign = $getSign->whereRaw("(`users`.`nik` = '" . $getPresales->nik . "' OR `roles`.`name` = 'VP Solutions & Partnership Management')")
            ->orderByRaw('FIELD(position, "Presales","System Designer","VP Solutions & Partnership Management")')
            ->get();
        }  

        collect(["data"=>$getAll,"function"=>$getFunction,"config"=>$getConfig,"sign"=>$getSign,"grand_total" => $getNominal]);

        $pdf = PDF::loadView('solution.PDF.sbePdf', compact('getAll','getFunction','getConfig','getSign','getNominal'));
        $fileName = 'SBE ' . $getAll->lead_id . '.pdf';

        // return $pdf->stream($fileName);
        return $pdf->output();
    }

    public function uploadPdfConfigManual(Request $request)
    {
        $client = $this->getClient();
        $service = new Google_Service_Drive($client);

        $data = Sbe::where('id',$request->id_sbe)->first();


        if ($data->parent_id_drive == null) {
            $parentID = $this->googleDriveMakeFolder($request->project_id);
            $parent_id = $this->googleDriveMakeFolder($data->lead_id);
            $data->parent_id_drive = $parent_id;
            $data->save();


            $getParent = DB::table('tb_sbe')->where('id',$request->id_sbe)->first();
            $parent_id = explode('"', $getParent->parent_id_drive)[1];
            $parent = [];
            array_push($parent,$parent_id);
        } else {
            $getParent = DB::table('tb_sbe')->where('id',$request->id_sbe)->first();
            $parent_id = explode('"', $getParent->parent_id_drive)[1];
            $parent = [];
            array_push($parent,$parent_id);
        }

        $fileName =  'SBE_' . $data->lead_id . '.pdf';

        if(isset($fileName)){
            // $pdf_url = urldecode(url("/sbe/getGenerateConfig?id_sbe=" . $request->id_sbe));
            $pdf_url = $this->getGenerateConfig($request->id_sbe);
            $pdf_name = $fileName;
        } else {
            $pdf_url = 'http://test-drive.sinergy.co.id:8000/Lampiran.pdf';
            $pdf_name = 'pdf_lampiran';
        }

        $file = new Google_Service_Drive_DriveFile();
        $file->setName($pdf_name);
        $file->setParents($parent);

        $result = $service->files->create(
            $file, 
            array(
                // 'data' => file_get_contents($pdf_url, false, stream_context_create(["ssl" => ["verify_peer"=>false, "verify_peer_name"=>false],"http" => ['protocol_version'=>'1.1']])),
                'data' => $pdf_url,
                'mimeType' => 'application/octet-stream',
                'uploadType' => 'multipart',
                'supportsAllDrives' => true
            )
        );

        $optParams = array(
            'fields' => 'files(webViewLink)',
            'q' => 'mimeType="application/pdf" and "' . explode('"',$getParent->parent_id_drive)[1] . '" in parents',
            'supportsAllDrives' => true,
            'includeItemsFromAllDrives' => true
        );

        $link = $service->files->listFiles($optParams)->getFiles()[0]->getWebViewLink();

        $tambah = new SbeDocument();
        $tambah->document_location         = "SOL/" . $pdf_name;
        $tambah->document_name = 'SBE ' . $data->lead_id;
        $tambah->link_drive = $link;
        $tambah->id_sbe = $request->id_sbe;
        $tambah->save();

        $updateStatus = Sbe::where('id',$request->id_sbe)->first();
        $updateStatus->status = 'Fixed';
        $updateStatus->save();
    }

    public function uploadPdfConfig(Request $request)
    {
        $client = $this->getClient();
        $service = new Google_Service_Drive($client);

        $data = Sbe::where('id',$request->id_sbe)->first();


        if ($data->parent_id_drive == null) {
            $parentID = $this->googleDriveMakeFolder($request->project_id);
            $parent_id = $this->googleDriveMakeFolder($data->lead_id);
            $data->parent_id_drive = $parent_id;
            $data->save();


            $getParent = DB::table('tb_sbe')->where('id',$request->id_sbe)->first();
            $parent_id = explode('"', $getParent->parent_id_drive)[1];
            $parent = [];
            array_push($parent,$parent_id);
        } else {
            $getParent = DB::table('tb_sbe')->where('id',$request->id_sbe)->first();
            $parent_id = explode('"', $getParent->parent_id_drive)[1];
            $parent = [];
            array_push($parent,$parent_id);
        }

        $fileName =  'SBE_' . $data->lead_id . '.pdf';

        if(isset($fileName)){
            // $pdf_url = urldecode(url("/sbe/getGenerateConfig?id_sbe=" . $request->id_sbe));
            $pdf_url = $this->getGenerateConfig($request->id_sbe);
            $pdf_name = $fileName;
        } else {
            $pdf_url = 'http://test-drive.sinergy.co.id:8000/Lampiran.pdf';
            $pdf_name = 'pdf_lampiran';
        }

        $file = new Google_Service_Drive_DriveFile();
        $file->setName($pdf_name);
        $file->setParents($parent);

        $result = $service->files->create(
            $file, 
            array(
                // 'data' => file_get_contents($pdf_url, false, stream_context_create(["ssl" => ["verify_peer"=>false, "verify_peer_name"=>false],"http" => ['protocol_version'=>'1.1']])),
                'data' => $pdf_url,
                'mimeType' => 'application/octet-stream',
                'uploadType' => 'multipart',
                'supportsAllDrives' => true
            )
        );

        $optParams = array(
            'fields' => 'files(webViewLink)',
            'q' => 'mimeType="application/pdf" and "' . explode('"',$getParent->parent_id_drive)[1] . '" in parents',
            'supportsAllDrives' => true,
            'includeItemsFromAllDrives' => true
        );

        $link = $service->files->listFiles($optParams)->getFiles()[0]->getWebViewLink();

        $tambah = new SbeDocument();
        $tambah->document_location         = "SOL/" . $pdf_name;
        $tambah->document_name = 'SBE ' . $data->lead_id;
        $tambah->link_drive = $link;
        $tambah->id_sbe = $request->id_sbe;
        $tambah->save();

        $updateStatus = Sbe::where('id',$request->id_sbe)->first();
        $updateStatus->status = 'Fixed';
        $updateStatus->save();

        $storeActivity = new SbeActivity();
        $storeActivity->id_sbe = $request->id_sbe;
        $storeActivity->operator = Auth::User()->nik;
        $storeActivity->activity = 'Generate PDF';
        $storeActivity->date_add = Carbon::now()->toDateTimeString();
        $storeActivity->save();
    }

    public function deleteDetailItem(Request $request)
    {
        $delete = SbeDetailItem::where('id',$request->id)->first();
        $delete->delete();

        return 'deleted';
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
        $file->setParents([env('GOOGLE_DRIVE_PARENT_ID_SOL')]);

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

    public function dashboardSbe($value='')
    {
        $year = DB::table('tb_sbe')->select(DB::raw('YEAR(created_at) as year'))->distinct()->get();

        // Check if the current year exists in the collection
        $yearExists = $year->contains(function ($value) {
            return $value->year == date('Y');
        });

        if (!$yearExists) {
            $year->push((object) ['year' => date('Y')]);
        }

        return view('solution.sbe_dashboard',compact('year'))->with(['initView'=> $this->initMenuBase()]);;
    }

    public function getDataDashboardSbe(Request $request)
    {
        $chartCountSbeByStatus = DB::table('tb_sbe')
                        ->select(
                            'status',
                            DB::raw('COUNT(*) as total_status'))
                        ->groupBy('status')
                        ->whereYear('created_at',$request->year)
                        ->get();

        $chartCountSbeByType = DB::table(DB::raw('(
                        SELECT 
                            tb_sbe.id AS sbe_id, 
                            SUM(CASE 
                                WHEN tb_sbe_config.project_type = "Implementation" AND tb_sbe_config.status = "Choosed" 
                                THEN 1 ELSE 0 
                            END) AS has_implementation,
                            SUM(CASE 
                                WHEN tb_sbe_config.project_type = "Maintenance" AND tb_sbe_config.status = "Choosed" 
                                THEN 1 ELSE 0 
                            END) AS has_maintenance,
                            MAX(tb_sbe_config.project_type) AS MAX_PROJECT_TYPE
                        FROM tb_sbe
                        INNER JOIN tb_sbe_config ON tb_sbe.id = tb_sbe_config.id_sbe
                        WHERE YEAR(tb_sbe.created_at) = '. $request->year .'
                        AND tb_sbe_config.status = "Choosed"
                        GROUP BY tb_sbe.id
                    ) AS subquery'))
                    ->selectRaw('
                        CASE 
                            WHEN has_implementation > 0 AND has_maintenance > 0 
                            THEN "Implementation + Maintenance"
                            ELSE MAX_PROJECT_TYPE 
                        END AS project_type,
                        COUNT(*) AS count
                    ')
                    ->groupBy('project_type')
                    ->orderByRaw('FIELD(project_type, "Supply Only","Maintenance","Implementation","Implementation + Maintenance") ASC')
                    ->get();

        $chartSumSbeByStatus = DB::table('tb_sbe')
                        ->select(
                            'status',
                            DB::raw('SUM(nominal) as sum_nominal'))
                        ->groupBy('status')
                        ->whereYear('created_at',$request->year)
                        ->get();

        $top5SbeByStatus = 
                       DB::table(DB::raw('(
                        SELECT 
                            tb_sbe.id AS sbe_id, 
                            SUM(CASE 
                                WHEN tb_sbe_config.project_type = "Implementation" AND tb_sbe_config.status = "Choosed" 
                                THEN 1 ELSE 0 
                            END) AS has_implementation,
                            SUM(CASE 
                                WHEN tb_sbe_config.project_type = "Maintenance" AND tb_sbe_config.status = "Choosed" 
                                THEN 1 ELSE 0 
                            END) AS has_maintenance,
                            MAX(tb_sbe_config.project_type) AS MAX_PROJECT_TYPE
                        FROM tb_sbe
                        INNER JOIN tb_sbe_config ON tb_sbe.id = tb_sbe_config.id_sbe
                        WHERE YEAR(tb_sbe.created_at) = '. $request->year .'
                        AND tb_sbe_config.status = "Choosed"
                        GROUP BY tb_sbe.id
                    ) AS subquery'))
                    ->selectRaw('
                        CASE 
                            WHEN has_implementation > 0 AND has_maintenance > 0 
                            THEN "Implementation + Maintenance"
                            ELSE MAX_PROJECT_TYPE 
                        END AS project_type,
                        COUNT(*) AS count
                    ')
                    ->groupBy('project_type')
                    ->orderByRaw('FIELD(project_type, "Implementation + Maintenance", "Implementation", "Maintenance") DESC')
                    ->get();



        $top5SbeByStatus->transform(function ($row) use ($request) {
            // Add the top 5 nominal records for each project type
            $row->top_nominals = DB::table(DB::raw('(
                            SELECT 
                                sales_lead_register.opp_name,
                                users.name,
                                tb_sbe.id AS sbe_id, 
                                tb_sbe.nominal,
                                tb_sbe.created_at,
                                CASE 
                                    WHEN SUM(CASE WHEN tb_sbe_config.project_type = "Implementation" AND tb_sbe_config.status = "Choosed" THEN 1 ELSE 0 END) > 0 
                                    AND SUM(CASE WHEN tb_sbe_config.project_type = "Maintenance" AND tb_sbe_config.status = "Choosed" THEN 1 ELSE 0 END) > 0 
                                    THEN "Implementation + Maintenance"
                                    ELSE MAX(tb_sbe_config.project_type) 
                                END AS project_type
                            FROM tb_sbe
                            INNER JOIN tb_sbe_config ON tb_sbe.id = tb_sbe_config.id_sbe
                            JOIN sales_lead_register ON tb_sbe.lead_id = sales_lead_register.lead_id
                            JOIN sales_solution_design ON sales_lead_register.lead_id = sales_solution_design.lead_id
                            JOIN users ON sales_solution_design.nik_ta = users.nik
                            WHERE YEAR(tb_sbe.created_at) = '.$request->year.'
                            AND sales_solution_design.status = "closed"
                            AND tb_sbe.status = "Fixed"
                            AND tb_sbe_config.status = "Choosed"
                            GROUP BY tb_sbe.id, users.name, opp_name
                            ORDER BY nominal DESC
                        ) AS subquery'))
                        ->select(
                            'subquery.name',
                            'subquery.opp_name',
                            'subquery.nominal',
                            'subquery.sbe_id AS id',
                            'subquery.created_at',
                            'subquery.project_type'
                        )
                        ->where('subquery.project_type', '=', $row->project_type) // Filter based on project_type
                        ->orderBy(DB::raw('CAST(subquery.nominal AS UNSIGNED)'), 'DESC')
                        ->limit(5)
                        ->get();

            return $row;
        });

        $data = [
            'totalSbeByStatus'=>$chartCountSbeByStatus,
            'sumSbeByStatus'=>$chartSumSbeByStatus,
            'top5SbeByStatus'=>$top5SbeByStatus,
            'totalSbeByType'=>$chartCountSbeByType
        ];

        return $data;
    }
}