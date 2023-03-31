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

    public function sbe_detail()
    {
        return view('solution.sbe_detail')->with(['initView'=> $this->initMenuBase(),'feature_item'=>$this->RoleDynamic('Solution')]);
    }

    public function getLead(Request $request)
    {
    	$nik = Auth::User()->nik;
    	$cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')
                    ->select('name', 'roles.group')->where('user_id', $nik)->first(); 

        $data = DB::table('sales_lead_register')->join('sales_solution_design','sales_solution_design.lead_id','sales_lead_register.lead_id')->join('users','users.nik','sales_lead_register.nik')->select(DB::raw('`sales_lead_register`.`lead_id` AS `id`,CONCAT(`sales_lead_register`.`lead_id`," - ",`opp_name`) AS `text`'))->where('id_company','1')->orderBy('year','desc');

        if ($cek_role->name == 'SOL Staff' || $cek_role->name == 'SOL Manager') {
        	$data->where('sales_solution_design.nik',$nik)->get();
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

    		$createConfig = new SbeConfig();
            $createConfig->id_sbe = $create->id;
	    	$createConfig->project_location = $request->textareaLoc;
    		$createConfig->project_type = 'Supply Only';
	    	$createConfig->duration = $request->inputDuration;
	    	$createConfig->estimated_running = $request->inputEstimatedRun;
            $createConfig->date_add = Carbon::now()->toDateTimeString();
            $createConfig->status = 'Choosed';
            $createConfig->sow = $request->textareaSOW;
            $createConfig->oos = $request->textareaScope;
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
            $updateConfig = SbeConfig::where('id',$createConfig->id)->first();
            $updateConfig->nominal = DB::table('tb_sbe_detail_config')->where('id_config_sbe',$createConfig->id)->groupby('id_config_sbe')->sum('total_nominal');
            $updateConfig->save();

            $storeActivity = new SbeActivity();
            $storeActivity->id_sbe = $create->id;
            $storeActivity->operator = Auth::User()->nik;
            $storeActivity->activity = 'Create config ' . $createConfig->project_type . ' with amount ' . $updateConfig->nominal;
            $storeActivity->date_add = Carbon::now()->toDateTimeString();
            $storeActivity->save();

            $storeRelation = new SbeRelation();
            $storeRelation->lead_id = $request->inputLead;
            $storeRelation->tag_sbe = '1';
            $storeRelation->price_sbe = $updateConfig->nominal;
            $storeRelation->save();
    	}

    	if (count($dataImp) != 0 ) {
            $countVersion = DB::table('tb_sbe_config')->where('project_type','Implementation')->where('id_sbe', $create->id)->count()+1;

    		$createConfig = new SbeConfig();
	    	$createConfig->id_sbe = $create->id;
	    	$createConfig->project_location = $request->textareaLoc;
    		$createConfig->project_type = 'Implementation';
	    	$createConfig->duration = $request->inputDuration;
	    	$createConfig->estimated_running = $request->inputEstimatedRun;
            $createConfig->date_add = Carbon::now()->toDateTimeString();
            $createConfig->status = 'Choosed';
            $createConfig->sow = $request->textareaSOW;
            $createConfig->oos = $request->textareaScope;
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
            $updateConfig = SbeConfig::where('id',$createConfig->id)->first();
            $updateConfig->nominal = DB::table('tb_sbe_detail_config')->where('id_config_sbe',$createConfig->id)->groupby('id_config_sbe')->sum('total_nominal');
            $updateConfig->save();

            $storeActivity = new SbeActivity();
            $storeActivity->id_sbe = $create->id;
            $storeActivity->operator = Auth::User()->nik;
            $storeActivity->activity = 'Create config ' . $createConfig->project_type . ' with amount ' . $updateConfig->nominal;
            $storeActivity->date_add = Carbon::now()->toDateTimeString();
            $storeActivity->save();

            $storeRelation = new SbeRelation();
            $storeRelation->lead_id = $request->inputLead;
            $storeRelation->tag_sbe = '4';
            $storeRelation->price_sbe = $updateConfig->nominal;
            $storeRelation->save();
    	}

    	if (count($dataMnS) != 0 ) {
            $countVersion = DB::table('tb_sbe_config')->where('project_type','Maintenance')->where('id_sbe', $create->id)->count()+1;

    		$createConfig = new SbeConfig();
	    	$createConfig->id_sbe = $create->id;
	    	$createConfig->project_location = $request->textareaLoc;
    		$createConfig->project_type = 'Maintenance';
	    	$createConfig->duration = $request->inputDuration;
	    	$createConfig->estimated_running = $request->inputEstimatedRun;
            $createConfig->date_add = Carbon::now()->toDateTimeString();
            $createConfig->status = 'Choosed';
            $createConfig->sow = $request->textareaSOW;
            $createConfig->oos = $request->textareaScope;
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
            $updateConfig = SbeConfig::where('id',$createConfig->id)->first();
            $updateConfig->nominal = DB::table('tb_sbe_detail_config')->where('id_config_sbe',$createConfig->id)->groupby('id_config_sbe')->sum('total_nominal');
            $updateConfig->save();

            $storeActivity = new SbeActivity();
            $storeActivity->id_sbe = $create->id;
            $storeActivity->operator = Auth::User()->nik;
            $storeActivity->activity = 'Create config ' . $createConfig->project_type . ' with amount ' . $updateConfig->nominal;
            $storeActivity->date_add = Carbon::now()->toDateTimeString();
            $storeActivity->save();

            $storeRelation = new SbeRelation();
            $storeRelation->lead_id = $request->inputLead;
            $storeRelation->tag_sbe = '2';
            $storeRelation->price_sbe = $updateConfig->nominal;
            $storeRelation->save();
    	}

        $updateNominalSbe = Sbe::where('id',$create->id)->first();
        $updateNominalSbe->nominal = DB::table('tb_sbe_config')->where('id_sbe',$create->id)->groupby('id_sbe')->sum('nominal');
        $updateNominalSbe->save();

        $email_user = User::join('role_user','role_user.user_id', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name', 'SOL Manager')->first()->email;

        $mail = new MailReviewConfigSBE(collect([
                "subject_email" => 'Please Review this Temporary SBE',
                "to"            => User::join('role_user','role_user.user_id', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name', 'SOL Manager')->select('users.name as name')->first()->name,
                "data"          => SbeConfig::where('status','Choosed')->where('id_sbe', $create->id)->orderByRaw('FIELD(project_type, "Supply Only", "Implementation", "Maintenance")')->get()->makeHidden(['detail_config','detail_all_config_choosed'])->groupby('project_type'),
                "status"        => 'Review SBE',
                "id"            => $create->id,
                "lead_id"       => $request->inputLead
            ])
        );

        $nik = Auth::User()->nik;
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')
                    ->select('name', 'roles.group')->where('user_id', $nik)->first(); 
        if ($cek_role->name != 'SOL Manager') {
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
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')
                    ->select('name', 'roles.group')->where('user_id', $nik)->first(); 

        $data = Sbe::join('sales_solution_design','sales_solution_design.lead_id','tb_sbe.lead_id')->join('sales_lead_register','sales_lead_register.lead_id','tb_sbe.lead_id')->join('users','users.nik','sales_solution_design.nik')->select('tb_sbe.lead_id','tb_sbe.status','opp_name','users.name as presales','tb_sbe.nominal','tb_sbe.id');

        if ($cek_role->name == 'SOL Staff') {
            $data->where('sales_solution_design.nik',$nik)->get();
        } else {
            $data->get();
        }

        return array("data"=>$data->get());
    }

    public function getSoWbyLeadID(Request $request)
    {
        return $data = SbeSow::where('lead_id',$request->lead_id)->first();
    }

    public function getActivity(Request $request)
    {
        $data = SbeActivity::join('users','users.nik','tb_sbe_activity.operator')->join('role_user','users.nik','role_user.user_id')->join('roles','roles.id','role_user.role_id')->select('users.name','activity','date_add','roles.name as role')->where('id_sbe',$request->id_sbe)->orderBy('date_add','desc')->get();

        if (SbeActivity::where('id_sbe',$request->id_sbe)->where('activity','like','Choose config version%')->orderBy('date_add','desc')->take(1)->exists()) {
            $getNotes = array();
        } else {
            $getNotes = SbeNotes::where('id_sbe',$request->id_sbe)->orderBy('date_add','desc')->take(1)->get();
        }

        $status = DB::table('tb_sbe')->select('status')->where('id',$request->id_sbe)->get();

        // return array("data"=>$data);
        return collect([
            "data" => $data,
            "getNotes" => $getNotes,
            "status" => $status
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
        $getProjectType = DB::table('tb_sbe_config')->select('id_sbe','project_type')->where('id',$request->id_config_sbe)->first();

        $countVersion = DB::table('tb_sbe_config')->where('project_type',$getProjectType->project_type)->where('id_sbe', $request->id_sbe)->count()+1;

        $createConfig = new SbeConfig();
        $createConfig->id_sbe = $request->id_sbe;
        $createConfig->project_location = $request->textareaLocUpdate;
        $createConfig->project_type = $getProjectType->project_type;
        $createConfig->duration = $request->inputDurationUpdate;
        $createConfig->estimated_running = $request->inputEstimatedRunUpdate;
        $createConfig->date_add = Carbon::now()->toDateTimeString();
        $createConfig->status = 'New';
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
        $updateConfig = SbeConfig::where('id',$createConfig->id)->first();
        $updateConfig->nominal = DB::table('tb_sbe_detail_config')->where('id_config_sbe',$createConfig->id)->groupby('id_config_sbe')->sum('total_nominal');
        $updateConfig->save();

        $storeActivity = new SbeActivity();
        $storeActivity->id_sbe = $request->id_sbe;
        $storeActivity->operator = Auth::User()->nik;
        $storeActivity->activity = 'Create config ' . $createConfig->project_type . ' with amount ' . $updateConfig->nominal;
        $storeActivity->date_add = Carbon::now()->toDateTimeString();
        $storeActivity->save();
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

            $storeRelation = new SbeRelation();
            $storeRelation->lead_id = $getId->lead_id;
            $storeRelation->price_sbe = str_replace('.', '', $updateVersion->nominal);
            if ($updateVersion->project_type == 'Supply Only') {
                $storeRelation->tag_sbe = '1';
            } elseif ($updateVersion->project_type == 'Implementation') {
                $storeRelation->tag_sbe = '4';
            } elseif ($updateVersion->project_type == 'Maintenance') {
                $storeRelation->tag_sbe = '2';
            }
            
            $storeRelation->save();
        }

        $getAllId = SbeConfig::whereNotIn('id',$dataVersion)->get();
        foreach ($getAllId as $key => $value) {
            $updateVersion = SbeConfig::where('id',$value->id)->first();
            $updateVersion->status = 'New';
            $updateVersion->save();
        }

        $update = Sbe::where('id',$getId->id_sbe)->first();
        $update->nominal = DB::table('tb_sbe_config')->where('id_sbe',$getId->id_sbe)->where('status','Choosed')->groupby('id_sbe')->sum('nominal');
        $update->save();


        $email_user = User::join('role_user','role_user.user_id', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name', 'SOL Manager')->first()->email;

        $mail = new MailReviewConfigSBE(collect([
                "subject_email" => 'Please Review this Temporary SBE',
                "to"            => User::join('role_user','role_user.user_id', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name', 'SOL Manager')->select('users.name as name')->first()->name,
                "data"          => SbeConfig::where('status','Choosed')->where('id_sbe',$getId->id_sbe)->orderByRaw('FIELD(project_type, "Supply Only", "Implementation", "Maintenance")')->get()->makeHidden(['detail_config','detail_all_config_choosed'])->groupby('project_type'),
                "status"        => 'Review SBE',
                "id"            => $getId->id_sbe,
                "lead_id"       => $getId->lead_id

            ])
        );

        $nik = Auth::User()->nik;
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')
                    ->select('name', 'roles.group')->where('user_id', $nik)->first(); 
        if ($cek_role->name != 'SOL Manager') {
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

    public function getGenerateConfig(Request $request)
    {
        $getFunction = SbeConfig::where('status','Choosed')->where('id_sbe',$request->id_sbe)->orderByRaw('FIELD(project_type, "Supply Only", "Implementation", "Maintenance")')->get()->makeHidden(['detail_config','detail_all_config_choosed'])->groupby('project_type');

        $getPresales = DB::table('tb_sbe')->join('sales_solution_design','sales_solution_design.lead_id','tb_sbe.lead_id')->where('id',$request->id_sbe)->first();

        $getAll = DB::table('tb_sbe_config')->join('tb_sbe','tb_sbe.id','tb_sbe_config.id_sbe')->join('sales_lead_register','sales_lead_register.lead_id','tb_sbe.lead_id')->join('users','users.nik','sales_lead_register.nik')->select('tb_sbe.lead_id','users.name as owner','project_location','estimated_running','duration','opp_name','tb_sbe.nominal as grand_total')->where('id_sbe',$request->id_sbe)->first();

        $getIdConfigSbe = DB::table('tb_sbe_config')->join('tb_sbe_detail_config','tb_sbe_detail_config.id_config_sbe','tb_sbe_config.id')->select('id_sbe','id_config_sbe')->where('tb_sbe_config.status','Choosed')->where('id_sbe',$request->id_sbe)->get();

        $getConfig = SbeConfig::where('status','Choosed')->where('id_sbe',$request->id_sbe)->orderByRaw('FIELD(project_type, "Supply Only", "Implementation", "Maintenance")')->get()->makeHidden(['detail_config'])->groupby('project_type');

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
                    ->where('users.status_karyawan', '!=', 'dummy')
                    ->whereRaw("(`users`.`nik` = '" . $getPresales->nik . "' OR `roles`.`name` = 'SOL Manager')")
                    ->orderByRaw('FIELD(position, "SOL Staff","SOL Manager")')
                    ->get();

        collect(["data"=>$getAll,"function"=>$getFunction,"config"=>$getConfig,"sign"=>$getSign]);

        $pdf = PDF::loadView('solution.PDF.sbePdf', compact('getAll','getFunction','getConfig','getSign'));
        $fileName = 'SBE ' . $getAll->lead_id . '.pdf';

        return $pdf->stream($fileName);
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

        $fileName =  'SBE ' . $data->lead_id . '.pdf';

        if(isset($fileName)){
            $pdf_url = urldecode(url("/sbe/getGenerateConfig?id_sbe=" . $request->id_sbe));
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
                'data' => file_get_contents($pdf_url, false, stream_context_create(["ssl" => ["verify_peer"=>false, "verify_peer_name"=>false]])),
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
                'data' => file_get_contents($locationFile, false, stream_context_create(["ssl" => ["verify_peer"=>false, "verify_peer_name"=>false]])),
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
}