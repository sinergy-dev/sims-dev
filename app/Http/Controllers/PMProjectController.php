<?php

namespace App\Http\Controllers;
use DB;
use PDF;
use Auth;
use App\PMO_assign;
use App\User;
use App\PMO;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\PMOProjectCharter;
use App\PMOInternalStakeholder;
use App\PMOEksternalStakeholder;
use App\PMOTechnologyUsed;
use App\PMORisk;
use App\PMODocument;
use App\PMODocumentProject;
use App\PMODocumentProjectCharter;
use App\PMOActivity;
use App\GanttTaskPmo;
use App\GanttLink;
use App\PMOIssue;
use App\PMOProgressReport;
use App\PMOProgressDisti;
use App\PMOFinalReport;
use App\SLAProject;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

use Mail;
use App\Mail\MailPMProject;

use Google_Client;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

use Intervention\Image\ImageManagerStatic as Image;

use Illuminate\Http\Request;
use LDAP\Result;
class PMProjectController extends Controller
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

    public function pmoPmIndex(){
        return view('PMO/pm_index')->with([
            'initView' => $this->initMenuBase(),
            'feature_item'=>$this->RoleDynamic('pmo')
        ]);
    }

    public function pmoPmDetail($id_pmo){
        return view('PMO/pm_detail')->with([
            'initView' => $this->initMenuBase(),
            'feature_item'=>$this->RoleDynamic('pmo')
        ]);
    }

    public function pmoPMDashboard(){
        return view('PMO/pm_dashboard')->with([
            'initView' => $this->initMenuBase()
        ]);
    }

    public function mailPMO(Request $request){
        return view('mail/MailPMOProject');
    }

    public function deleteAssign(Request $request){

        $delete = PMO::where('id',$request->id_pmo)->first();
        $delete->delete();

        return 'deleted';
    }

    public function getListDataProject(Request $request){
        // $getListLeadRegister = DB::table('sales_lead_register')->join('tb_id_project', 'tb_id_project.lead_id', '=', 'sales_lead_register.lead_id')
  //                       ->select('opp_name as name_project','tb_id_project.id_project as project_id', 'sales_lead_register.nik');

  //       $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')
  //                       ->select('name', 'roles.group')->where('user_id', Auth::User()->nik)->first(); 

  //       $data =  PMO::LeftjoinSub($getListLeadRegister, 'project_id', function($join){
  //                   $join->on('tb_pmo.project_id', '=', 'project_id.project_id');
  //               })
        //      ->leftJoin('tb_pmo_project_charter','tb_pmo_project_charter.id_project','=','tb_pmo.id')
        //      ->select('name_project','tb_pmo.project_id','current_phase','project_type','tb_pmo.id','implementation_type');

  //       if ($cek_role->name == 'PMO Manager' || Auth::User()->name == 'PMO Staff' || $cek_role->name == 'BCD Manager' || $cek_role->name == 'Chief Operating Officer') {
  //        $data = $data->orderBy('tb_pmo.id','desc')->get()->makeHidden(['type_project_array','type_project_array']);
  //           // $data = PMO::get();
  //       } elseif ($cek_role->group == 'Sales' || $cek_role->group == 'bcd') {
  //        $data = $data->join('tb_pmo_assign', 'tb_pmo_assign.id_project', 'tb_pmo.id')
        //      ->where('project_id.nik', Auth::User()->nik)->orderBy('tb_pmo.id','asc')
        //      ->get()->makeHidden(['type_project_array','phase']);
  //           // $data = PMO::get();
  //       } else {
  //        $data = $data->join('tb_pmo_assign', 'tb_pmo_assign.id_project', 'tb_pmo.id')
        //      ->where('tb_pmo_assign.nik', Auth::User()->nik)->orderBy('tb_pmo.id','asc')
        //      ->get()->makeHidden(['type_project_array','phase']);

  //           // $data = PMO::where('assign_pm', Auth::User()->nik)->get();
  //       }

  //       // $project_indicator = DB::table('tb_pmo')->join('tb_pmo_progress_report','tb_pmo.id','tb_pmo_progress_report.id_project')->select('project_indicator')->orderBy('tb_pmo_progress_report.reporting_date','desc')->first();

  //       // $data = collect(["data"=>$data,"indicator_project"=>$project_indicator]);

  //       // return $data;
  //       return array("data" => $data);

        $getListLeadRegister = DB::table('sales_lead_register')->join('tb_id_project', 'tb_id_project.lead_id', '=', 'sales_lead_register.lead_id')
                        ->select('opp_name as name_project','tb_id_project.id_project as project_id', 'sales_lead_register.nik');

        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')
                        ->select('name', 'roles.group')->where('user_id', Auth::User()->nik)->first(); 

        $data =  PMO::select('tb_pmo.project_id','current_phase','project_type','tb_pmo.id','implementation_type');

        if ($cek_role->name == 'VP Program & Project Management' || $cek_role->name == 'Project Management Office Manager' || $cek_role->name == 'Chief Operating Officer' || $cek_role->name == 'VP Solutions & Partnership Management' || $cek_role->name == 'Project Transformation Officer') {
            $data = $data->orderBy('tb_pmo.id','desc');
        } elseif ($cek_role->group == 'Sales' || $cek_role->group == 'bcd') {
            $data = $data->LeftjoinSub($getListLeadRegister, 'project_id', function($join){
                        $join->on('tb_pmo.project_id', '=', 'project_id.project_id');
                    })
                    ->where('project_id.nik', Auth::User()->nik)->orderBy('tb_pmo.id','desc');
        } else {
            $data = $data->join('tb_pmo_assign', 'tb_pmo_assign.id_project', 'tb_pmo.id')
                ->where('tb_pmo_assign.nik', Auth::User()->nik)->orderBy('tb_pmo.id','desc');
        }

        $orderColumnIndex = $request->order[0]['column'] ?? '0';

        $orderByName = 'project_id';
        switch($orderColumnIndex){
            case '1':
                $orderByName = 'project_id';
                break;
            case '2':
                $orderByName = 'name_project';
                break;
            case '3':
                $orderByName = 'project_type';
                break;
            case '4':
                $orderByName = 'project_pm';
                break;
            case '5':
                $orderByName = 'project_pc';
                break;
            case '6':
                $orderByName = 'current_phase';
                break;
            default:
                $orderByName = 'id';
                break;
        }

        $searchFields = ['name_project', 'project_id', 'current_phase', 'project_type', 'tb_pmo.id', 'implementation_type','project_pc','project_pm'];

        if ($request->searchFor != "") { 
            $data = $data->get()->makeHidden('type_project_array');

            $filtered = $data->filter(function ($value, $key) use($request, $searchFields) { 
               return stripos($value["current_phase"], $request->searchFor) !== false || 
                    stripos($value["implementation_type"], $request->searchFor) !== false ||
                    stripos($value["indicator_project"], $request->searchFor) !== false ||
                    stripos($value["name_project"], $request->searchFor) !== false ||
                    stripos($value["owner"], $request->searchFor) !== false ||
                    stripos($value["project_id"], $request->searchFor) !== false ||
                    stripos($value["project_pc"], $request->searchFor) !== false ||
                    stripos($value["project_pm"], $request->searchFor) !== false ||
                    stripos($value["project_type"], $request->searchFor) !== false ||
                    stripos($value["status"], $request->searchFor) !== false ||
                    stripos($value["type_project"], $request->searchFor) !== false;
            });

            $data = $filtered;

            $totalRecords = $data->count();
            // Apply pagination
            $start = $request->input('start', 0);
            $pageLength = $request->input('length', $request->length); // Number of records per page

            $draw = $request->input('draw');

            $outputArray = [];
            foreach ($data as $item) {
                $outputArray[] = collect([
                    "current_phase"=>$item->current_phase,
                    "id"=>$item->id,
                    "implementation_type"=>$item->implementation_type,
                    "indicator_project"=>$item->indicator_project,
                    "name_project"=>$item->name_project,
                    "no_po_customer"=>$item->no_po_customer,
                    "owner"=>$item->owner,
                    "project_id"=>$item->project_id,
                    "project_pc"=>$item->project_pc,
                    "project_pm"=>$item->project_pm,
                    "project_type"=>$item->project_type,
                    "sign"=>$item->sign,
                    "status"=>$item->status,
                    "type_project"=>$item->type_project,
                ]);
            }

            $data = $outputArray;

            if ($request->order[0]['dir'] == 'asc') {
                $data = collect($data)->sortByDesc($orderByName)->values()->all();
            }else{
                $data = collect($data)->sortByDesc($orderByName)->values()->all();
            }

            if ($draw > 1) {
                $datas = collect($data)->skip($start)->take($pageLength);
                $data = [];
                $data = array_values($datas->toArray());

                // return $data;
            }else{
                $data = collect($data)->skip($start)->take($pageLength);
            }
        }else{
             // Get the total count before pagination
            // Apply pagination
            $totalRecords = $data->count();
            $start = $request->input('start', 0);
            $pageLength = $request->input('length', $request->length); // Number of records per page
            $draw = $request->input('draw');

            if ($request->order[0]['dir'] == 'asc') {
                $data = $data->get()->sortByDesc($orderByName)->values()->all();
            }else{
                $data = $data->get()->sortByDesc($orderByName)->values()->all();
            }

            if ($draw > 1) {
                $datas = collect($data)->skip($start)->take($pageLength);
                $data = [];
                $data = array_values($datas->toArray());

                // return $data;
            }else{
                $data = collect($data)->skip($start)->take($pageLength);
            }
        }

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'length' => $pageLength,
            'data' => $data,
        ]);
    }

    public function deleteDoc(Request $request)
    {
        $deleteDoc = PMODocument::where('id',$request->id_doc)->first();

        $deleteDocProjectCharter = PMODocumentProjectCharter::where('id_document',$request->id_doc)->delete();

        $deleteDocProject = PMODocumentProject::where('id_document',$request->id_doc)->delete();

        $store_activity = new PMOActivity();
        $store_activity->id_project = $request['id_pmo'];
        $store_activity->phase = 'Delete document';
        $store_activity->operator = Auth::User()->name;
        $store_activity->activity = 'Delete Document' . $deleteDoc->document_name;
        $store_activity->date_time = Carbon::now()->toDateTimeString();
        $store_activity->save();

        $deleteDoc->delete();

        return 'deleted';
    }

    public function getAllActivity(Request $request)
    {
        $data = PMOActivity::where('id_project',$request->id_pmo)->orderby('date_time','desc')->get();

        return array("data"=>$data);
    }

    public function getUser(Request $request)
    {
        $getUser = User::selectRaw('`users`.`nik` AS `id`,`users`.`name` AS `text`, `users`.`email`, `users`.`phone`')->where('status_karyawan', '!=', 'dummy')->where('id_company','1');

        if (isset($request->nik)) {
            $getUser->where('users.nik', $request->nik);
        }

        return array("data" => $getUser->get());
    }

    public function getListDataPid(Request $req){
        $fk_id_project = PMO::select('project_id');
        // ->makeHidden(['indicator_project', 'sign', 'type_project','name_project','owner','no_po','project_pm','project_pc','phase']);
        // $fk_id_project->setAppends([]);
        // $fk_id_project = DB::table('tb_pmo')->select('project_id')->get();

        $data = DB::table('tb_id_project')->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
            ->selectRaw('`tb_id_project`.`id_project` AS `id`,`tb_id_project`.`id_project` AS `text`,`sales_lead_register`.`opp_name`')->where('sales_lead_register.result','=','WIN')
            // ->where('sales_lead_register.year','=',date('Y'))
            ->where('users.id_company', '1')
            ->whereNotIn('tb_id_project.id_project',function($query) use ($fk_id_project) {
                $query->select('id_project')->whereIn('id_project',$fk_id_project)->from('tb_id_project');
            })
            ->orderBy('tb_id_project.date','desc');

        if (isset($req->pid)) {
            $data->where('tb_id_project.id_project','=',$req->pid);
        }

        return $data->get();
    }

    public function getListforProjectCharterById(Request $request){
        // $getListLeadRegister = DB::table('sales_lead_register')->join('tb_id_project', 'tb_id_project.lead_id', '=', 'sales_lead_register.lead_id')
        //              ->join('users','users.nik','=','sales_lead_register.nik')
  //                       ->select('opp_name as name_project','tb_id_project.id_project as id_project','name as owner','no_po_customer');

        // $data = DB::table("tb_pmo")->LeftjoinSub($getListLeadRegister, 'project_id', function($join){
  //                   $join->on('tb_pmo.project_id', '=', 'project_id.id_project');
  //               })->join('tb_pmo_assign','tb_pmo_assign.id_project','=','tb_pmo.id')
        //      ->join('users','users.nik','=','tb_pmo_assign.nik')
        //      ->select('name_project','project_id as id_project','current_phase','project_type','owner','no_po_customer',DB::raw('(CASE WHEN role = "Delivery Project Manager" THEN name END) AS project_pm'),DB::raw('(CASE WHEN role = "Delivery Project Coordinator" THEN name END) AS project_pc'));


        return $data = PMO::where('tb_pmo.id',$request->id_pmo)->get()->makeHidden(['indicator_project', 'sign','type_project','status']);

        // return $data->where('tb_pmo.id',$request->id_pmo)->get();
    }

    public function downloadProjectCharterPdf(Request $request){
        // $pdf = PDF::loadView('PMO.Pdf.projectCharter');
        $data = PMOProjectCharter::join('tb_pmo', 'tb_pmo.id', 'tb_pmo_project_charter.id_project')->where('tb_pmo_project_charter.id_project',$request->id_pmo)->first();
        $data = json_decode($data,true);

        // return $data;

        $pdf = PDF::loadView('PMO.Pdf.projectCharter', compact('data'));
        $fileName =  $data['project_id']['project_id']  . ' Project Charter.pdf';
        // $nameFileFix = str_replace(' ', '_', $fileName);

        // return $data;

        return $pdf->stream($fileName);
        // return $pdf->Output("D");

        // return view('PMO.Pdf.projectCharter',compact('data'));

        // return $pdf->download('project_charter.pdf');
    }

    public function downloadProgressMeetingPdf(Request $request){

        $data = PMOProgressReport::join('tb_pmo', 'tb_pmo.id','tb_pmo_progress_report.id_project')->where('tb_pmo_progress_report.id_project', $request->id_pmo)->orderby('tb_pmo_progress_report.id','desc')->first();
        $data = json_decode($data,true);

        // return $data;

        $pdf = PDF::loadView('PMO.Pdf.progressMeeting', compact('data'));
        $fileName = ' Project Progress Report.pdf';
        
        return $pdf->stream($fileName);
        // return $pdf->download('progress_meeting.pdf');
    }

    public function downloadFinalProjectPdf(Request $request){
        $data = PMOFinalReport::join('tb_pmo', 'tb_pmo.id','tb_pmo_final_report.id_project')->where('tb_pmo_final_report.id_project', $request->id_pmo)->first();
        $data = json_decode($data,true);

        // return $data;

        $pdf = PDF::loadView('PMO.Pdf.finalProject', compact('data'));
        $fileName = ' Project Charter.pdf';
        return $pdf->stream($fileName);
    }

    public function getPMStaff()
    {
        $getPMStaff = collect(User::select(DB::raw('`nik` AS `id`,`name` AS `text`'))->whereRaw("(`id_position` = 'PM' OR `id_position` = 'PM SPV')")->where('status_karyawan', '!=', 'dummy')->where('id_company','1')->get());
        $getPCStaff = collect(User::select(DB::raw('`nik` AS `id`,`name` AS `text`'))->whereRaw("(`id_position` = 'SERVICE PROJECT')")->where('status_karyawan', '!=', 'dummy')->where('id_company','1')->get());

        return array("data" => $getPMStaff->merge($getPCStaff));
    }

    public function getPCStaff()
    {
        $getPCStaff = collect(User::select(DB::raw('`nik` AS `id`,`name` AS `text`'))->where('id_position','SERVICE PROJECT')->where('status_karyawan', '!=', 'dummy')->where('id_company','1')->get());

        return array("data" => $getPCStaff);
    }

    public function getDefaultTask(Request $request)
    {
        $get_project_type = PMO::where('id', $request->id_pmo)->first();
        if (count($get_project_type->type_project_array) == 2) {
            if($get_project_type->project_type == $get_project_type->type_project_array[0]){
                $get_technology_used = DB::table('tb_pmo')->join('tb_pmo_project_charter','tb_pmo_project_charter.id_project','tb_pmo.id')->join('tb_pmo_technology_project_charter','tb_pmo_technology_project_charter.id_project_charter','tb_pmo_project_charter.id')->where('project_type','!=','supply_only')->where('tb_pmo.id',$request->id_pmo)->first();
            } else {
                $get_technology_used = DB::table('tb_pmo')->join('tb_pmo_project_charter','tb_pmo_project_charter.id_project','tb_pmo.id')->join('tb_pmo_technology_project_charter','tb_pmo_technology_project_charter.id_project_charter','tb_pmo_project_charter.id')->where('project_type','!=','supply_only')->where('tb_pmo.id',$request->id_pmo-1)->first();
            }
        } else {
            $get_technology_used = DB::table('tb_pmo')->join('tb_pmo_project_charter','tb_pmo_project_charter.id_project','tb_pmo.id')->join('tb_pmo_technology_project_charter','tb_pmo_technology_project_charter.id_project_charter','tb_pmo_project_charter.id')->where('project_type','!=','supply_only')->where('tb_pmo.id',$request->id_pmo)->first();
        }
        
        // return $get_technology_used;
        if ($get_project_type->project_type == 'implementation') {
            $implementation_type = json_decode($get_project_type->implementation_type);
            if ($implementation_type == '["hardware","service"]') {
                $implementation_type = substr($implementation_type,2,8);
            } elseif ($implementation_type == '["hardware","service","license"]') {
                $implementation_type = substr($implementation_type,2,8);
            } elseif ($implementation_type == '["service","license"]'){
                // return substr($implementation_type, 12,18);
                $implementation_type = 'license';
            } elseif($implementation_type == '["service"]'){
                $implementation_type = 'service';
            }
        }
        

        if ($get_project_type->project_type == 'implementation') {
            $dataInitiating = DB::table('tb_pmo_define_task')->where('project_type', $get_project_type->project_type)->where('implementation_type', $implementation_type)->where('task', 'Initiating')->get();
            $dataPlanning = DB::table('tb_pmo_define_task')->where('project_type', $get_project_type->project_type)->where('implementation_type',$implementation_type)->where('task', 'Planning')->get();
            $dataExecuting = DB::table('tb_pmo_define_task')->where('project_type', $get_project_type->project_type)->where('implementation_type', $implementation_type)->where('task', 'Executing')->get();
            $dataClosing = DB::table('tb_pmo_define_task')->where('project_type', $get_project_type->project_type)->where('implementation_type',$implementation_type)->where('task', 'Closing')->get();
        } else if($get_project_type->project_type == 'supply_only' || $get_project_type->project_type == 'maintenance' && $get_technology_used->technology_used != 'ATM/CRM'){
            $dataInitiating = DB::table('tb_pmo_define_task')->where('project_type', $get_project_type->project_type)->where('implementation_type', null)->where('task', 'Initiating')->get();
            $dataPlanning = DB::table('tb_pmo_define_task')->where('project_type', $get_project_type->project_type)->where('implementation_type', null)->where('task', 'Planning')->get();
            $dataExecuting = DB::table('tb_pmo_define_task')->where('project_type', $get_project_type->project_type)->where('implementation_type', null)->where('task', 'Executing')->get();
            $dataClosing = DB::table('tb_pmo_define_task')->where('project_type', $get_project_type->project_type)->where('implementation_type', null)->where('task', 'Closing')->get();
        } else if($get_technology_used->technology_used == 'ATM/CRM'){
            $dataInitiating = DB::table('tb_pmo_define_task')->where('project_type', $get_project_type->project_type)->where('implementation_type', 'ATM')->where('task', 'Initiating')->get();
            $dataPlanning = DB::table('tb_pmo_define_task')->where('project_type', $get_project_type->project_type)->where('implementation_type', 'ATM')->where('task', 'Planning')->get();
            $dataExecuting = DB::table('tb_pmo_define_task')->where('project_type', $get_project_type->project_type)->where('implementation_type', 'ATM')->where('task', 'Executing')->get();
            $dataClosing = DB::table('tb_pmo_define_task')->where('project_type', $get_project_type->project_type)->where('implementation_type', 'ATM')->where('task', 'Closing')->get();
        }
        

        // return $dataInitiating;
        if ($request->assign) {
            return collect([
                "Executing" => $dataExecuting,
            ]);
        }else{
            return collect([
                "Initiating" => $dataInitiating,
                "Planning" => $dataPlanning,
                "Executing" => $dataExecuting,
                "Closing" => $dataClosing
            ]);
        }
    }

    public function getPhase(Request $request)
    {
        $data = DB::table('tb_pmo_define_task')->select('task')->orderByRaw('FIELD(task, "Initiating", "Planning", "Executing", "Closing")')->groupBy('task')->get();

        // return collect([
        //     "Initiating" => "Initiating",
        //     "Planning" => "Planning",
        //     "Executing" => "Executing",
        //     "Closing" => "Closing"
        // ]);
        // return $dataInitiating;
        return collect([
            "Initiating" => array("Initiating"),
            "Planning" => array("Planning"),
            "Executing" => array("Executing"),
            "Closing" => array("Closing")
        ]);
    }

    public function assignProject(Request $request)
    {
        // return $request->cbImpelementType;
        $dataAll = json_decode($request->cbProjectType,true);
        foreach ($dataAll as $key => $data) {
            $store = new PMO();
            $store->project_id = $request['selectPIDAssign'];
            $store->project_type = $data;
            if ($data == 'implementation') {
                $store->implementation_type = json_encode($_POST['cbImpelementType']);
            }
            if (DB::table('tb_pmo')->where('project_id',$request['selectPIDAssign'])->exists()) {
                $store->current_phase = "Waiting";
            } else {
                $store->current_phase = 'New';
            }
            $store->date_time = Carbon::now()->toDateTimeString();
            $store->save();

            $id = PMO::where('project_id', $request->selectPIDAssign)->first();

            if (isset($request->selectPM) && isset($request->selectPC)) {
                $store_assign = new PMO_assign();
                $store_assign->id_project = $store->id;
                if (PMO_assign::join('tb_pmo','tb_pmo.id','tb_pmo_assign.id_project')->where('project_type','implementation')->where('role','Project Manager')->where('project_id',$request->selectPIDAssign)->exists()) {
                    $store_assign->role = 'Project Coordinator';
                    $store_assign->nik = $request['selectPC'];
                } else {
                    $store_assign->role = 'Project Manager';
                    $store_assign->nik = $request['selectPM'];
                }
                
                $store_assign->save();
            } else {
                $store_assign = new PMO_assign();
                $store_assign->id_project = $store->id;
                if ($request->selectPM != '') {
                    $store_assign->role = 'Project Manager';
                    $store_assign->nik = $request['selectPM'];
                } else {
                    $store_assign->role = 'Project Coordinator';
                    $store_assign->nik = $request['selectPC'];
                }
                $store_assign->save();
            }
        }

        if ($request->selectPM != '') {
            $name = DB::table('users')->where('nik',$request->selectPM)->first()->name;
            $email_user = DB::table('users')->where('nik',$request->selectPM)->first()->email;

            if ($dataAll == ["implementation","maintenance"]) {
                // return "oke";
                $type_project = 'Implementation + Maintenance & Managed Service';
                $user = $id->project_pm . "/" . $id->project_pc;
            } else if($dataAll == ["implementation"]) {
                $type_project =  'Implementation';
                $user = $id->project_pm;
            } else if($dataAll == ["supply_only"]) {
                $type_project =  'Supply Only';
                $user = $id->project_pm;
            }

            $mail = new MailPMProject(collect([
                    "image"         => 'assign.png',
                    "subject"       => 'You`re assigned to this project:',
                    "subject_email" => 'Assign Project',
                    "pid"           => $request['selectPIDAssign'],
                    "to"            => $name,
                    "name_project"  => DB::table('tb_id_project')->where('id_project',$request->selectPIDAssign)->first()->name_project,
                    "project_type"  => $type_project,
                    "sales_owner"   => DB::table('tb_id_project')->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')->join('users','users.nik','=','sales_lead_register.nik')->where('tb_id_project.id_project',$request->selectPIDAssign)->first()->name,
                    "project_pm"    => $user,
                    "note_reject"   => '',
                    'status'        =>'assignProject',
                    'id'            => '-',
                    'type_project'  => '-'
                ])
            );
            
            Mail::to($email_user)->send($mail);
        }

        if($request->selectPC != ''){
            $name = DB::table('users')->where('nik',$request->selectPC)->first()->name;
            $email_user = DB::table('users')->where('nik',$request->selectPC)->first()->email;
            if ($dataAll == ["implementation","maintenance"]) {
                $type_project = 'Implementation + Maintenance & Managed Service';
                $user = $id->project_pm . "/" . $id->project_pc;
            }else if($dataAll == ["maintenance"]) {
                $type_project =  'Maintenance';
                $user = $id->project_pc;
            }

            $mail = new MailPMProject(collect([
                    "image"         => 'assign.png',
                    "subject"       => 'You`re assigned to this project:',
                    "subject_email" => 'Assign Project',
                    "pid"           => $request['selectPIDAssign'],
                    "to"            => $name,
                    "name_project"  => DB::table('tb_id_project')->where('id_project',$request->selectPIDAssign)->first()->name_project,
                    "project_type"  => $type_project,
                    "sales_owner"   => DB::table('tb_id_project')->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')->join('users','users.nik','=','sales_lead_register.nik')->where('tb_id_project.id_project',$request->selectPIDAssign)->first()->name,
                    "project_pm"    => $user,
                    "note_reject"   => '',
                    'status'        =>'assignProject',
                    'id'            => '-',
                    'type_project'  => '-'
                ])
            );
            
            Mail::to($email_user)->send($mail);
        }

        return $mail;
    }

    public function storeProjectCharter(Request $request)
    {
        $store = new PMOProjectCharter();
        $store->id_project = $request['id_pmo'];
        $store->customer_name = $request['inputCustomer'];
        $store->customer_address = $request['textAreaAddress'];
        $store->customer_phone = $request['inputPhone'];
        $store->customer_cp = $request['inputContactPerson'];
        $store->customer_cp_title = $request['inputCpTitle'];
        $store->customer_email = $request['inputEmail'];
        $store->customer_cp_phone = $request['inputCpPhone'];
        $store->project_description = $request['textAreaProjectDesc'];
        $store->project_objectives = $request['textAreaProjectObj'];

        $start_date = strtotime($_POST['inputStartDate']); 
        $start_date = date("Y-m-d",$start_date);
        $store->estimated_start_date = $start_date;

        $end_date = strtotime($_POST['inputFinishDate']); 
        $end_date = date("Y-m-d",$end_date);
        $store->estimated_end_date = $end_date;

        $store->flexibility = $request['selectFlexibility'];
        $store->scope_of_work = $request['textAreaSOW'];
        $store->out_of_scope = $request['textAreaOutOfScope'];
        $store->customer_requirement = $request['textAreaCustomerRequirement'];
        $store->terms_of_payment = $request['textAreaTOP'];

        if($request->file('inputCompanyLogo') === null) {
            // $store->logo_company = $update->gambar;
        } else {
            $allowedfileExtension   = ['jpg','png', 'jpeg', 'JPG', 'PNG'];
            $file                   = $request->file('inputCompanyLogo');
            $fileName               = $file->getClientOriginalName();
            $extension              = $file->getClientOriginalExtension();
            $check                  = in_array($extension,$allowedfileExtension);

            if ($check) {
                Image::make($file->getRealPath())->resize(1024, 1024)->save('image/'.$fileName);

                $store->logo_company = $fileName;
            } else {
                return redirect()->back()->with('alert','Oops! Only jpg, png');
            }
        }
        $store->date_time = Carbon::now()->toDateTimeString();
        $store->status = 'New';
        $store->market_segment = $request['selectMarketSegment'];
        $store->save();

        $dataStakeholder = json_decode($request->arrInternalStakeHolder,true);
        foreach ($dataStakeholder as $key => $value) {
            $store_stakeholder = new PMOInternalStakeholder();
            $store_stakeholder->id_project_charter = $store->id;
            $store_stakeholder->id_project = $request['id_pmo'];
            $store_stakeholder->nik = $value['nik'];
            $store_stakeholder->role = $value['role'];
            $store_stakeholder->date_time = Carbon::now()->toDateTimeString();
            $store_stakeholder->save();
        }

        $dataTechUse = json_decode($request->arrCbTechUse,true);
        foreach ($dataTechUse as $key => $value) {
            $store_technology = new PMOTechnologyUsed();
            $store_technology->id_project_charter = $store->id;
            $store_technology->technology_used = $value;
            $store_technology->date_time = Carbon::now()->toDateTimeString();
            $store_technology->save();
        }

        $dataRisk = json_decode($request->arrIdentifiedRisk,true);
        foreach ($dataRisk as $key => $value) {
            $store_risk = new PMORisk();
            $store_risk->id_project =  $request->id_pmo;
            $store_risk->risk_description = $value['risk'];
            $store_risk->risk_owner = $value['owner'];
            $store_risk->impact = $value['impact'];
            $store_risk->risk_response = $value['response'];
            $store_risk->likelihood = $value['likelihood'];
            $store_risk->impact_description = $value['impactDescription'];

            $due_date = strtotime($value['due_date']); 
            $due_date = date("Y-m-d",$due_date);
            $store_risk->due_date = $due_date;

            $review_date = strtotime($value['review_date']); 
            $review_date = date("Y-m-d",$review_date);
            $store_risk->review_date = $review_date;
            $store_risk->status = $value['status'];
            $store_risk->date_time = Carbon::now()->toDateTimeString();
            $store_risk->save();
        }

        $get_id_pmo = PMO::where('id', $request->id_pmo)->first();
        $directory = "PMO/";

        if ($request->inputPO != '-') {
            $allowedfileExtension   = ['pdf', 'PDF'];
            $file                   = $request->file('inputPO');
            $fileName               = $file->getClientOriginalName();
            $strfileName            = explode('.', $fileName);
            $lastElement            = end($strfileName);
            $nameDoc                = $fileName;
            $extension              = $file->getClientOriginalExtension();
            $check                  = in_array($extension,$allowedfileExtension);

            $tambah_po = new PMODocument();
            if ($check) {
                $this->uploadToLocal($request->file('inputPO'),$directory,$nameDoc);
                $tambah_po->document_name             = "PO";
            } else {
                return redirect()->back()->with('alert','Oops! Only pdf');
            }

            if(isset($fileName)){
                $pdf_url = urldecode(url("PMO/" . $nameDoc));
                $pdf_name = $nameDoc;
            } else {
                $pdf_url = 'http://test-drive.sinergy.co.id:8000/Lampiran.pdf';
                $pdf_name = 'pdf_lampiran';
            }

            if ($get_id_pmo->parent_id_drive == null) {
                $parentID = $this->googleDriveMakeFolder($request->project_id);
            } else {
                $parentID = [];
                $parent_id = explode('"', $get_id_pmo->parent_id_drive)[1];
                array_push($parentID,$parent_id);
            }

            $tambah_po->document_location         = "PMO/" . $pdf_name;
            $tambah_po->link_drive = $this->googleDriveUploadCustom($pdf_name,$directory . $pdf_name,$parentID);
            $tambah_po->save();

            $tambah_po_pj = new PMODocumentProjectCharter();
            $tambah_po_pj->id_project_charter = $store->id;
            $tambah_po_pj->id_document = $tambah_po->id;
            $tambah_po_pj->date_time = Carbon::now()->toDateTimeString();
            $tambah_po_pj->save();


            $update_parent = PMO::where('id', $request['id_pmo'])->first();
            $update_parent->parent_id_drive = $parentID;
            $update_parent->save();
        }

        $get_parent_drive = PMO::where('id', $request->id_pmo)->first();

        if ($request->inputToR != '-') {
            $allowedfileExtension   = ['pdf', 'PDF'];
            $file                   = $request->file('inputToR');
            $fileName               = $file->getClientOriginalName();
            $strfileName            = explode('.', $fileName);
            $lastElement            = end($strfileName);
            $nameDoc                = $fileName;
            $extension              = $file->getClientOriginalExtension();
            $check                  = in_array($extension,$allowedfileExtension);

            $tambah_tor = new PMODocument();
            if ($check) {
                $this->uploadToLocal($request->file('inputToR'),$directory,$nameDoc);
                $tambah_tor->document_name             = "ToR";
            } else {
                return redirect()->back()->with('alert','Oops! Only pdf');
            }

            if(isset($fileName)){
                $pdf_url = urldecode(url("PMO/" . $nameDoc));
                $pdf_name = $nameDoc;
            } else {
                $pdf_url = 'http://test-drive.sinergy.co.id:8000/Lampiran.pdf';
                $pdf_name = 'pdf_lampiran';
            }

            if ($get_parent_drive->parent_id_drive == null) {
                $parentID = $this->googleDriveMakeFolder($request->project_id);
            } else {
                $parentID = [];
                $parent_id = explode('"', $get_parent_drive->parent_id_drive)[1];
                array_push($parentID,$parent_id);
            }

            $tambah_tor->document_location         = "PMO/" . $pdf_name;
            $tambah_tor->link_drive = $this->googleDriveUploadCustom($pdf_name,$directory . $pdf_name,$parentID);
            $tambah_tor->save();

            $tambah_tor_pj = new PMODocumentProjectCharter();
            $tambah_tor_pj->id_project_charter = $store->id;
            $tambah_tor_pj->id_document = $tambah_tor->id;
            $tambah_tor_pj->date_time = Carbon::now()->toDateTimeString();
            $tambah_tor_pj->save();


            $update_parent = PMO::where('id', $request['id_pmo'])->first();
            $update_parent->parent_id_drive = $parentID;
            $update_parent->save();
        }

        if ($request->inputSbe != '-') {
            $allowedfileExtension   = ['pdf', 'PDF'];
            $file                   = $request->file('inputSbe');
            $fileName               = $file->getClientOriginalName();
            $strfileName            = explode('.', $fileName);
            $lastElement            = end($strfileName);
            $nameDoc                = $fileName;
            $extension              = $file->getClientOriginalExtension();
            $check                  = in_array($extension,$allowedfileExtension);

            $tambah_sbe = new PMODocument();
            if ($check) {
                $this->uploadToLocal($request->file('inputSbe'),$directory,$nameDoc);
                $tambah_sbe->document_name             = "SBE";
            } else {
                return redirect()->back()->with('alert','Oops! Only pdf');
            }

            if(isset($fileName)){
                $pdf_url = urldecode(url("PMO/" . $nameDoc));
                $pdf_name = $nameDoc;
            } else {
                $pdf_url = 'http://test-drive.sinergy.co.id:8000/Lampiran.pdf';
                $pdf_name = 'pdf_lampiran';
            }

            if ($get_parent_drive->parent_id_drive == null) {
                $parentID = $this->googleDriveMakeFolder($request->project_id);
            } else {
                $parentID = [];
                $parent_id = explode('"', $get_parent_drive->parent_id_drive)[1];
                array_push($parentID,$parent_id);
            }

            $tambah_sbe->document_location         = "PMO/" . $pdf_name;
            $tambah_sbe->link_drive = $this->googleDriveUploadCustom($pdf_name,$directory . $pdf_name,$parentID);
            $tambah_sbe->save();

            $tambah_sbe_pj = new PMODocumentProjectCharter();
            $tambah_sbe_pj->id_project_charter = $store->id;
            $tambah_sbe_pj->id_document = $tambah_sbe->id;
            $tambah_sbe_pj->date_time = Carbon::now()->toDateTimeString();
            $tambah_sbe_pj->save();


            $update_parent = PMO::where('id', $request['id_pmo'])->first();
            $update_parent->parent_id_drive = $parentID;
            $update_parent->save();
        }

        // return gettype($request->arrInputDocPendukung);
        $dataAll = json_decode($request->arrInputDocPendukung,true);
        // return $dataAll;
        foreach ($dataAll as $key => $data) {
            // if (in_array("", $request->inputDocPendukung)) {
            if($request->inputDocPendukung[0] != '-'){
                // return "abc";
                $allowedfileExtension   = ['jpg','png', 'jpeg', 'JPG', 'PNG', 'pdf', 'PDF'];
                $file                   = $request->file('inputDocPendukung')[$key];
                $fileName               = $file->getClientOriginalName();
                $strfileName            = explode('.', $fileName);
                $lastElement            = end($strfileName);
                $nameDoc                = $fileName;
                $extension              = $file->getClientOriginalExtension();
                $check                  = in_array($extension,$allowedfileExtension);
                $tambah_dok             = new PMODocument();
                if ($check) {
                    $this->uploadToLocal($request->file('inputDocPendukung')[$key],$directory,$nameDoc);
                    $tambah_dok->document_name             = $data['nameDocPendukung'];

                } else {
                    return redirect()->back()->with('alert','Oops! Only pdf');
                }

                if(isset($fileName)){
                    $pdf_url = urldecode(url("PMO/" . $nameDoc));
                    $pdf_name = $nameDoc;
                } else {
                    $pdf_url = 'http://test-drive.sinergy.co.id:8000/Lampiran.pdf';
                    $pdf_name = 'pdf_lampiran';
                }

                $parentID = [];
                $parent_id = explode('"', $get_parent_drive->parent_id_drive)[1];
                array_push($parentID,$parent_id);


                $pdf_name = explode(".",$pdf_name)[0] . "." . explode(".",$pdf_name)[1];

                $tambah_dok->document_location         = "PMO/".$pdf_name;
                $tambah_dok->link_drive = $this->googleDriveUploadCustom($pdf_name,$directory . $pdf_name,$parentID);
                $tambah_dok->save();

                $tambah_dok_pj = new PMODocumentProjectCharter();
                $tambah_dok_pj->id_project_charter = $store->id;
                $tambah_dok_pj->id_document = $tambah_dok->id;
                $tambah_dok_pj->date_time = Carbon::now()->toDateTimeString();
                $tambah_dok_pj->save();
            }
        }

        //tambah activity
        $store_activity = new PMOActivity();
        $store_activity->id_project = $request['id_pmo'];
        $store_activity->phase = 'New Project Charter';
        $store_activity->operator = Auth::User()->name;
        $store_activity->activity = 'Create New Project Charter';
        $store_activity->date_time = Carbon::now()->toDateTimeString();
        $store_activity->save();
    }

    public function storeCustomerInfoProjectCharter(Request $request)
    {

        if (PMOProjectCharter::where('id_project',$request['id_pmo'])->exists()) {
            PMOProjectCharter::where('id_project',$request['id_pmo'])->delete();
        }

        $store = new PMOProjectCharter();
        $store->id_project = $request['id_pmo'];
        $store->customer_name = $request['inputCustomer'];
        $store->customer_address = $request['textAreaAddress'];
        $store->customer_phone = $request['inputPhone'];
        $store->customer_cp = $request['inputContactPerson'];
        $store->customer_cp_title = $request['inputCpTitle'];
        $store->customer_email = $request['inputEmail'];
        $store->customer_cp_phone = $request['inputCpPhone'];
        if($request->file('inputCompanyLogo') === null) {
            // $store->logo_company = $update->gambar;
        } else {
            $allowedfileExtension   = ['jpg','png', 'jpeg', 'JPG', 'PNG'];
            $file                   = $request->file('inputCompanyLogo');
            $fileName               = $file->getClientOriginalName();
            $imageName              = 'image/customer_logo/'.$fileName;
            $extension              = $file->getClientOriginalExtension();
            $check                  = in_array($extension,$allowedfileExtension);

            if ($check) {

                // Image::make($file->getRealPath())->resize(1024, 1024)->save('image/customer_logo'.$fileName);
                $request->file('inputCompanyLogo')->move("image/customer_logo", $imageName);

                $store->logo_company = $imageName;
            } else {
                return redirect()->back()->with('alert','Oops! Only jpg, png');
            }

        }
        $store->status = 'Draft';
        $store->save();
    }

    public function updateInternalStakholder(Request $request)
    {
        $update = PMOProjectCharter::where('id_project', $request->id_pmo)->first();
        
        // return $request->id_pmo;
        $update->scope_of_work = $request['textAreaSOW'];
        $update->out_of_scope = $request['textAreaOutOfScope'];
        $update->customer_requirement = $request['textAreaCustomerRequirement'];
        $update->terms_of_payment = $request['textAreaTOP'];
        $update->date_time = Carbon::now()->toDateTimeString();
        $update->save();

        $dataStakeholder = json_decode($request->arrInternalStakeHolder,true);
        $cek_role = PMOInternalStakeholder::where('id_project', $request->id_pmo)->get();
        $cek_eksternal = PMOEksternalStakeholder::where('id_project', $request->id_pmo)->get();
        if (isset($cek_role)) {
            // return $cek_role;
            foreach ($cek_role as $key => $value) {
                PMOInternalStakeholder::where('id_project',$value->id_project)->delete(); 
            }
        }

        if (isset($cek_eksternal)) {
            // return $cek_role;
            foreach ($cek_eksternal as $key => $value) {
                PMOEksternalStakeholder::where('id_project',$value->id_project)->delete(); 
            }
        }
        
        foreach ($dataStakeholder as $key => $value) {
            if (is_numeric($value['nik'])) {
                $store_stakeholder = new PMOInternalStakeholder();
                // $store_stakeholder->id_project_charter = ;
                $store_stakeholder->id_project = $request['id_pmo'];
                $store_stakeholder->nik = $value['nik'];
                $store_stakeholder->role = $value['role'];
                $store_stakeholder->date_time = Carbon::now()->toDateTimeString();
                $store_stakeholder->save();
            } else {
                $store_stakeholder = new PMOEksternalStakeholder();
                // $store_stakeholder->id_project_charter = ;
                $store_stakeholder->id_project = $request['id_pmo'];
                $store_stakeholder->name = $value['nik'];
                $store_stakeholder->email = $value['email'];
                $store_stakeholder->phone = substr(str_replace('-', '', $value['phone']),0);
                $store_stakeholder->role = $value['role'];
                $store_stakeholder->date_time = Carbon::now()->toDateTimeString();
                $store_stakeholder->save();
            }
        }
    }

    public function updateIdentifiedRisk(Request $request)
    {
        $dataRisk = json_decode($request->arrIdentifiedRisk,true);
        $cek_risk = PMORisk::where('id_project', $request->id_pmo)->get();
        if (isset($cek_risk)) {
            foreach ($cek_risk as $key => $value) {
                PMORisk::where('id_project',$value->id_project)->delete(); 
            }
        } 
        foreach ($dataRisk as $key => $value) {
            // return $value['risk'];
            $store_risk = new PMORisk();
            $store_risk->id_project =  $request->id_pmo;
            $store_risk->risk_description = $value['risk'];
            $store_risk->risk_owner = $value['owner'];
            $store_risk->impact = $value['impact'];
            $store_risk->risk_response = $value['response'];
            $store_risk->likelihood = $value['likelihood'];
            $store_risk->impact_rank = (int)$value['impact']*(int)$value['likelihood'];
            $store_risk->impact_description = $value['impactDescription'];

            $due_date = strtotime($value['due_date']); 
            $due_date = date("Y-m-d",$due_date);
            $store_risk->due_date = $due_date;

            $review_date = strtotime($value['review_date']); 
            $review_date = date("Y-m-d",$review_date);
            $store_risk->review_date = $review_date;
            $store_risk->status = $value['status'];
            $store_risk->date_time = Carbon::now()->toDateTimeString();
            $store_risk->save();
        }
    }

    public function updateCustomerInfoProjectCharter(Request $request)
    {
        $directory = 'PMO/';
        $update = PMOProjectCharter::where('id_project', $request->id_pmo)->first();
        $update->customer_name = $request['inputCustomer'];
        $update->customer_address = $request['textAreaAddress'];
        $update->customer_phone = $request['inputPhone'];
        $update->customer_cp = $request['inputContactPerson'];
        $update->customer_cp_title = $request['inputCpTitle'];
        $update->customer_email = $request['inputEmail'];
        $update->customer_cp_phone = $request['inputCpPhone'];
        // return $request->file('inputCompanyLogo');
        $allowedfileExtension   = ['jpg','png', 'jpeg', 'JPG', 'PNG'];
        $file                   = $request->file('inputCompanyLogo');
        $fileName               = $file->getClientOriginalName();
        // return $fileName;
        $imageName              = 'image/customer_logo/'.$fileName;
        $extension              = $file->getClientOriginalExtension();
        $check                  = in_array($extension,$allowedfileExtension);

        if($request->file('inputCompanyLogo') === null || $imageName === $update->logo_company) {
            // $store->logo_company = $update->gambar;
        } else {
            if ($check) {
                // Image::make($file->getRealPath())->resize(1024, 1024)->save('image/customer_logo'.$fileName);
                $request->file('inputCompanyLogo')->move("image/customer_logo", $imageName);

                $update->logo_company = $imageName;
            } else {
                return redirect()->back()->with('alert','Oops! Only jpg, png');
            }
            // return $file->getMimeType();

        }
        $update->save();
    }

    public function postEventCalendar($name_project,$end_date,$email){
        // $calenderId = "kfo8st45f546hr112s6ia4mgmo@group.calendar.google.com";

        // return $email;
        $calenderId = "primary";

        $client = new Client();
        $url = "https://www.googleapis.com/calendar/v3/calendars/".$calenderId."/events?key=".env('GCALENDAR_API_KEY')."&sendNotifications=true";
        $token = $this->getOauth2AccessToken();

        $response =  $client->request(
                    'POST', 
                    $url,        
                    [
                        // 'form_params' => [
                        //     'sendNotifications' => true,
                        // ],
                        'headers' => [
                            'Content-Type'=>'application/json',
                            'Authorization'=>$token
                        ],'json' => [
                                "summary" => $name_project,
                                "start" => array(
                                    'dateTime' => Carbon::parse($end_date . "08:00:00")->toISOString(),
                                ),
                                "end" => array(
                                    'dateTime' => Carbon::parse($end_date . "08:00:00")->toISOString(),
                                ),
                                "description" => $name_project,
                                'reminders' => array(
                                    'useDefault' => FALSE,
                                    'overrides' => array(
                                        array('method' => 'email', 'minutes' => 24 * 60),
                                        array('method' => 'popup', 'minutes' => 10),
                                    ),
                                ),
                                'attendees'=> array(
                                    array('email'=> $email[0]),
                                    array('email'=> $email[1]),
                                ),
                        ]
                    ]
        );

        return $response->getBody();
    }
    
    public function updateProjectInformationProjectCharter(Request $request)
    {
        // return User::where('id_division','BCD')->where('id_position','STAFF')->first()->email;
        $update = PMOProjectCharter::where('id_project', $request->id_pmo)->first();

        $start_date = strtotime($_POST['inputStartDate']); 
        $start_date = date("Y-m-d",$start_date);

        $end_date = strtotime($_POST['inputFinishDate']); 
        $end_date = date("Y-m-d",$end_date);
        // $end_date = '2023-07-27';
        $getNameProject = PMOProjectCharter::join('tb_pmo','tb_pmo.id','=','tb_pmo_project_charter.id_project')
                        ->join('tb_id_project','tb_id_project.id_project','=','tb_pmo.project_id')
                        ->where('tb_pmo.id',$request->id_pmo)
                        ->first();

        $name_project_calendar = "Estimated Finish Date ".$getNameProject->name_project;

        // return $end_date . $update->estimated_end_date;
        if ($end_date != $update->estimated_end_date) {
            $this->postEventCalendar($name_project_calendar,$end_date,array(Auth::User()->email,User::join('role_user','role_user.user_id', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name', 'Project Management Office Manager')->orwhere('roles.name','VP Program & Project Management')->first()->email));
        }
        // return $update->name_project;
        $update->project_description = $request['textAreaProjectDesc'];
        $update->project_objectives = $request['textAreaProjectObj'];       
        $update->estimated_start_date = $start_date;        
        $update->estimated_end_date = $end_date;
        // $this->postEventCalendar($name_project_calendar,$end_date,array(Auth::User()->email,Auth::User()->where('id_division','PMO')->where('id_position','MANAGER')->email));
       

        $update->flexibility = $request['selectFlexibility'];
        $update->market_segment = $request['selectMarketSegment'];
        $update->save();

        $dataTechUse = json_decode($request->arrCbTechUse,true);
        $cek_tech = PMOTechnologyUsed::where('id_project_charter', $update->id)->get();
        if (isset($cek_tech)) {
            foreach ($cek_tech as $key => $value) {
                PMOTechnologyUsed::where('id_project_charter',$value->id_project_charter)->delete(); 
            }
        } 
        foreach ($dataTechUse as $key => $value) {
            $store_technology = new PMOTechnologyUsed();
            $store_technology->id_project_charter = $update->id;
            $store_technology->technology_used = $value;
            $store_technology->date_time = Carbon::now()->toDateTimeString();
            $store_technology->save();
        }

    }

    public function getOauth2AccessToken(){
        $client = new Client();

        $response = $client->request(
                'POST',
                'https://oauth2.googleapis.com/token',
                [
                    'headers' => [
                        'Content-Type' => 'application/x-www-form-urlencoded',
                    ],
                    'form_params' => [
                        'grant_type' => 'refresh_token',
                        'client_id' => env('GCALENDER_CLIENT_ID'),
                        'client_secret' => env('GCALENDAR_CLIENT_SECRET'),
                        'refresh_token' => env('GCALENDAR_REFRESH_TOKEN')
                    ]
                ]
            );

        $response = json_decode($response->getBody());

        return "Bearer " . $response->access_token;
        // if(Cache::store('file')->has('webex_access_token')){
        //   Log::info('Webex Access Token still falid');
        //   return "Bearer " . Cache::store('file')->get('webex_access_token');
        // } else {
        //   Log::error('Webex Access Token not falid. Try to refresh token');
        //   $client = new Client();
        //   $response = $client->request(
        //     'POST',
        //     'https://webexapis.com/v1/access_token',
        //     [
        //       'headers' => [
        //         'Content-Type' => 'application/x-www-form-urlencoded',
        //       ],
        //       'form_params' => [
        //         'grant_type' => 'refresh_token',
        //         'client_id' => env('WEBEX_CLIENT_ID'),
        //         'client_secret' => env('WEBEX_CLIENT_SECRET'),
        //         'refresh_token' => env('WEBEX_REFRESH_TOKEN')
        //       ]
        //     ]
        //   );

        //   $response = json_decode($response->getBody());

        //   if(isset($response->access_token)){
        //     Log::info('Refresh Token success. Save token to cache file');
        //     Cache::store('file')->put('webex_access_token',$response->access_token,now()->addSeconds($response->expires_in));
        //     return "Bearer " . Cache::store('file')->get('webex_access_token');
        //   } else {
        //     Log::error('Refresh Token failed. Please to try change "refresh token"');
        //   }
        // }
    }

    public function updateDocProjectCharter(Request $request)
    {
        $update = PMOProjectCharter::where('id_project', $request->id_pmo)->first();
        $get_id_pmo = PMO::where('id', $request->id_pmo)->first();
        $directory = "PMO/";

        $get_dokumen = DB::table('tb_pmo_project_charter')->join('tb_pmo', 'tb_pmo.id', 'tb_pmo_project_charter.id_project')->join('tb_pmo_doc_project_charter', 'tb_pmo_doc_project_charter.id_project_charter', 'tb_pmo_project_charter.id')->join('tb_pmo_document', 'tb_pmo_document.id', 'tb_pmo_doc_project_charter.id_document')->select('document_name', 'document_location', 'link_drive', 'tb_pmo_document.id as id_document', 'tb_pmo_doc_project_charter.id as id_doc_project_charter', 'tb_pmo_doc_project_charter.id_project_charter')->where('tb_pmo.id', $request->id_pmo)->get();

        $count = PMODocument::join('tb_pmo_doc_project_charter', 'tb_pmo_doc_project_charter.id_document', 'tb_pmo_document.id')->join('tb_pmo_project_charter','tb_pmo_project_charter.id','tb_pmo_doc_project_charter.id_project_charter')->where('tb_pmo_project_charter.id_project', $request->id_pmo)->count();

        // if (isset($get_dokumen)) {
        //     foreach ($get_dokumen as $key => $value) {
        //         PMODocumentProjectCharter::where('id_project_charter',$value->id_project_charter)->delete(); 

        //         if ($values->document_location) {
        //             PMODocument::where('id', $value->id_document)->delete();
        //         }
        //     }
        // }
        if ($request->inputPO != '-') {
            $allowedfileExtension   = ['pdf', 'PDF'];
            $file                   = $request->file('inputPO');
            $fileName               = $file->getClientOriginalName();
            $strfileName            = explode('.', $fileName);
            $lastElement            = end($strfileName);
            $nameDoc                = $fileName;
            $extension              = $file->getClientOriginalExtension();
            $check                  = in_array($extension,$allowedfileExtension);

            if ($count == 0) {
                $tambah_po = new PMODocument();
                if ($check) {
                    $this->uploadToLocal($request->file('inputPO'),$directory,$nameDoc);
                    $tambah_po->document_name             = "PO";
                } else {
                    return redirect()->back()->with('alert','Oops! Only pdf');
                }

                if(isset($fileName)){
                    $pdf_url = urldecode(url("PMO/" . $nameDoc));
                    $pdf_name = $nameDoc;
                } else {
                    $pdf_url = 'http://test-drive.sinergy.co.id:8000/Lampiran.pdf';
                    $pdf_name = 'pdf_lampiran';
                }

                if ($get_id_pmo->parent_id_drive == null) {
                    $parentID = $this->googleDriveMakeFolder($request->project_id);
                } else {
                    $parentID = [];
                    $parent_id = explode('"', $get_id_pmo->parent_id_drive)[1];
                    array_push($parentID,$parent_id);
                }

                $tambah_po->document_location = "PMO/" . $pdf_name;
                $tambah_po->link_drive = $this->googleDriveUploadCustom($pdf_name,$directory . $pdf_name,$parentID);
                $tambah_po->save();

                $tambah_po_pj = new PMODocumentProjectCharter();
                $tambah_po_pj->id_project_charter = $update->id;
                $tambah_po_pj->id_document = $tambah_po->id;
                $tambah_po_pj->date_time = Carbon::now()->toDateTimeString();
                $tambah_po_pj->save();

                $update_parent = PMO::where('id', $request['id_pmo'])->first();
                $update_parent->parent_id_drive = $parentID;
                $update_parent->save();
            } else {
                if ($request->isChangePO == "true") {
                    $getId = PMODocument::join('tb_pmo_doc_project_charter', 'tb_pmo_doc_project_charter.id_document', 'tb_pmo_document.id')->join('tb_pmo_project_charter','tb_pmo_project_charter.id','tb_pmo_doc_project_charter.id_project_charter')->where('tb_pmo_project_charter.id_project',$request->id_pmo)->where('document_name', 'PO')->first();

                    $update_po = PMODocument::where('id', $getId->id_document)->first();

                    if ($check) {
                        $this->uploadToLocal($request->file('inputPO'),$directory,$nameDoc);
                        $update_po->document_name             = "PO";
                    } else {
                        return redirect()->back()->with('alert','Oops! Only pdf');
                    }

                    if(isset($fileName)){
                        $pdf_url = urldecode(url("draft_pr/" . $nameDoc));
                        $pdf_name = $nameDoc;
                    } else {
                        $pdf_url = 'http://test-drive.sinergy.co.id:8000/Lampiran.pdf';
                        $pdf_name = 'pdf_lampiran';
                    }

                    $parentID = [];
                    $parent_id = explode('"', $get_id_pmo->parent_id_drive)[1];
                    array_push($parentID,$parent_id);
                    $pdf_name = explode(".",$pdf_name)[0] . "." . explode(".",$pdf_name)[1];

                    $update_po->document_location = "PMO/" . $pdf_name;
                    $update_po->link_drive = $this->googleDriveUploadCustom($pdf_name,$directory . $pdf_name,$parentID);
                    $update_po->save();

                    $update_parent = PMO::where('id', $request['id_pmo'])->first();
                    $update_parent->parent_id_drive = $parentID;
                    $update_parent->save();
                }
            }
        }

        $get_parent_drive = PMO::where('id', $request->id_pmo)->first();

        if ($request->inputToR != '-') {
            $allowedfileExtension   = ['pdf', 'PDF'];
            $file                   = $request->file('inputToR');
            $fileName               = $file->getClientOriginalName();
            $strfileName            = explode('.', $fileName);
            $lastElement            = end($strfileName);
            $nameDoc                = $fileName;
            $extension              = $file->getClientOriginalExtension();
            $check                  = in_array($extension,$allowedfileExtension);

            if ($count == 0) {
                $tambah_tor = new PMODocument();
                if ($check) {
                    $this->uploadToLocal($request->file('inputToR'),$directory,$nameDoc);
                    $tambah_tor->document_name             = "ToR";
                } else {
                    return redirect()->back()->with('alert','Oops! Only pdf');
                }

                if(isset($fileName)){
                    $pdf_url = urldecode(url("PMO/" . $nameDoc));
                    $pdf_name = $nameDoc;
                } else {
                    $pdf_url = 'http://test-drive.sinergy.co.id:8000/Lampiran.pdf';
                    $pdf_name = 'pdf_lampiran';
                }

                if ($get_parent_drive->parent_id_drive == null) {
                    $parentID = $this->googleDriveMakeFolder($request->project_id);
                } else {
                    $parentID = [];
                    $parent_id = explode('"', $get_parent_drive->parent_id_drive)[1];
                    array_push($parentID,$parent_id);
                }

                $tambah_tor->document_location         = "PMO/" . $pdf_name;
                $tambah_tor->link_drive = $this->googleDriveUploadCustom($pdf_name,$directory . $pdf_name,$parentID);
                $tambah_tor->save();

                $tambah_tor_pj = new PMODocumentProjectCharter();
                $tambah_tor_pj->id_project_charter = $update->id;
                $tambah_tor_pj->id_document = $tambah_tor->id;
                $tambah_tor_pj->date_time = Carbon::now()->toDateTimeString();
                $tambah_tor_pj->save();

                $update_parent = PMO::where('id', $request['id_pmo'])->first();
                $update_parent->parent_id_drive = $parentID;
                $update_parent->save();
            } else {
                if ($request->isChangeToR == "true") {
                    $getId = PMODocument::join('tb_pmo_doc_project_charter', 'tb_pmo_doc_project_charter.id_document', 'tb_pmo_document.id')->join('tb_pmo_project_charter','tb_pmo_project_charter.id','tb_pmo_doc_project_charter.id_project_charter')->where('tb_pmo_project_charter.id_project', $request->id_pmo)->where('document_name', 'ToR')->first();

                    $update_tor = PMODocument::where('id', $getId->id_document)->first();

                    if ($check) {
                        $this->uploadToLocal($request->file('inputToR'),$directory,$nameDoc);
                        $update_po->document_name             = "ToR";
                    } else {
                        return redirect()->back()->with('alert','Oops! Only pdf');
                    }

                    if(isset($fileName)){
                        $pdf_url = urldecode(url("draft_pr/" . $nameDoc));
                        $pdf_name = $nameDoc;
                    } else {
                        $pdf_url = 'http://test-drive.sinergy.co.id:8000/Lampiran.pdf';
                        $pdf_name = 'pdf_lampiran';
                    }

                    $parentID = [];
                    $parent_id = explode('"', $get_id_pmo->parent_id_drive)[1];
                    array_push($parentID,$parent_id);
                    $pdf_name = explode(".",$pdf_name)[0] . "." . explode(".",$pdf_name)[1];

                    $update_tor->document_location = "PMO/" . $pdf_name;
                    $update_tor->link_drive = $this->googleDriveUploadCustom($pdf_name,$directory . $pdf_name,$parentID);
                    $update_tor->save();

                    $update_parent = PMO::where('id', $request['id_pmo'])->first();
                    $update_parent->parent_id_drive = $parentID;
                    $update_parent->save();
                }
            }            
        }

        if ($request->inputSbe != '-') {
            $allowedfileExtension   = ['pdf', 'PDF'];
            $file                   = $request->file('inputSbe');
            $fileName               = $file->getClientOriginalName();
            $strfileName            = explode('.', $fileName);
            $lastElement            = end($strfileName);
            $nameDoc                = $fileName;
            $extension              = $file->getClientOriginalExtension();
            $check                  = in_array($extension,$allowedfileExtension);

            if ($count == 0) {
                $tambah_sbe = new PMODocument();
                if ($check) {
                    $this->uploadToLocal($request->file('inputSbe'),$directory,$nameDoc);
                    $tambah_sbe->document_name             = "SBE";
                } else {
                    return redirect()->back()->with('alert','Oops! Only pdf');
                }

                if(isset($fileName)){
                    $pdf_url = urldecode(url("PMO/" . $nameDoc));
                    $pdf_name = $nameDoc;
                } else {
                    $pdf_url = 'http://test-drive.sinergy.co.id:8000/Lampiran.pdf';
                    $pdf_name = 'pdf_lampiran';
                }

                if ($get_parent_drive->parent_id_drive == null) {
                    $parentID = $this->googleDriveMakeFolder($request->project_id);
                } else {
                    $parentID = [];
                    $parent_id = explode('"', $get_parent_drive->parent_id_drive)[1];
                    array_push($parentID,$parent_id);
                }

                $tambah_sbe->document_location         = "PMO/" . $pdf_name;
                $tambah_sbe->link_drive = $this->googleDriveUploadCustom($pdf_name,$directory . $pdf_name,$parentID);
                $tambah_sbe->save();

                $tambah_sbe_pj = new PMODocumentProjectCharter();
                $tambah_sbe_pj->id_project_charter = $update->id;
                $tambah_sbe_pj->id_document = $tambah_sbe->id;
                $tambah_sbe_pj->date_time = Carbon::now()->toDateTimeString();
                $tambah_sbe_pj->save();

                $update_parent = PMO::where('id', $request['id_pmo'])->first();
                $update_parent->parent_id_drive = $parentID;
                $update_parent->save();
            } else {
                if ($request->isChangeSbe == "true") {
                    $getId = PMODocument::join('tb_pmo_doc_project_charter', 'tb_pmo_doc_project_charter.id_document', 'tb_pmo_document.id')->join('tb_pmo_project_charter','tb_pmo_project_charter.id','tb_pmo_doc_project_charter.id_project_charter')->where('tb_pmo_project_charter.id_project', $request->id_pmo)->where('document_name', 'SBE')->first();

                    $update_sbe = PMODocument::where('id', $getId->id_document)->first();

                    if ($check) {
                        $this->uploadToLocal($request->file('inputSbe'),$directory,$nameDoc);
                        $update_po->document_name             = "SBE";
                    } else {
                        return redirect()->back()->with('alert','Oops! Only pdf');
                    }

                    if(isset($fileName)){
                        $pdf_url = urldecode(url("draft_pr/" . $nameDoc));
                        $pdf_name = $nameDoc;
                    } else {
                        $pdf_url = 'http://test-drive.sinergy.co.id:8000/Lampiran.pdf';
                        $pdf_name = 'pdf_lampiran';
                    }

                    $parentID = [];
                    $parent_id = explode('"', $get_id_pmo->parent_id_drive)[1];
                    array_push($parentID,$parent_id);
                    $pdf_name = explode(".",$pdf_name)[0] . "." . explode(".",$pdf_name)[1];

                    $update_sbe->document_location = "PMO/" . $pdf_name;
                    $update_sbe->link_drive = $this->googleDriveUploadCustom($pdf_name,$directory . $pdf_name,$parentID);
                    $update_sbe->save();

                    $update_parent = PMO::where('id', $request['id_pmo'])->first();
                    $update_parent->parent_id_drive = $parentID;
                    $update_parent->save();
                }
            }
        }

        // return gettype($request->arrInputDocPendukung);
        $dataAll = json_decode($request->arrInputDocPendukung,true);
        // return $dataAll;
        foreach ($dataAll as $key => $data) {
            // if (in_array("", $request->inputDocPendukung)) {
            if($request->inputDoc[0] != '-'){
                // return "abc";
                $allowedfileExtension   = ['jpg','png', 'jpeg', 'JPG', 'PNG', 'pdf', 'PDF'];
                $file                   = $request->file('inputDoc')[$key];
                $fileName               = $file->getClientOriginalName();
                $strfileName            = explode('.', $fileName);
                $lastElement            = end($strfileName);
                $nameDoc                = $fileName;
                $extension              = $file->getClientOriginalExtension();
                $check                  = in_array($extension,$allowedfileExtension);
                $tambah_dok             = new PMODocument();
                if ($check) {
                    $this->uploadToLocal($request->file('inputDoc')[$key],$directory,$nameDoc);
                    $tambah_dok->document_name             = $data['nameDocPendukung'];

                } else {
                    return redirect()->back()->with('alert','Oops! Only pdf');
                }

                if(isset($fileName)){
                    $pdf_url = urldecode(url("PMO/" . $nameDoc));
                    $pdf_name = $nameDoc;
                } else {
                    $pdf_url = 'http://test-drive.sinergy.co.id:8000/Lampiran.pdf';
                    $pdf_name = 'pdf_lampiran';
                }

                $parentID = [];
                $parent_id = explode('"', $get_parent_drive->parent_id_drive)[1];
                array_push($parentID,$parent_id);


                $pdf_name = explode(".",$pdf_name)[0] . "." . explode(".",$pdf_name)[1];

                $tambah_dok->document_location         = "PMO/".$pdf_name;
                $tambah_dok->link_drive = $this->googleDriveUploadCustom($pdf_name,$directory . $pdf_name,$parentID);
                $tambah_dok->save();

                $tambah_dok_pj = new PMODocumentProjectCharter();
                $tambah_dok_pj->id_project_charter = $update->id;
                $tambah_dok_pj->id_document = $tambah_dok->id;
                $tambah_dok_pj->date_time = Carbon::now()->toDateTimeString();
                $tambah_dok_pj->save();
            }
        }

        // $this->uploadPdfPC($request->id_pmo,$approver);

    }

    public function updateSLAProject(Request $request)
    {
        $update = PMOProjectCharter::where('id_project', $request->id_pmo)->first();
        $update->status = 'New';
        $update->save();

        $cek_sign = PMOActivity::where('id_project', $request->id_pmo)->where('operator', Auth::User()->name)->whereRaw("(`phase` =  'Update Project Charter' OR `phase` = 'New Project Charter')")->first();

        if (PMOActivity::where('id_project', $request->id_pmo)->where('operator', Auth::User()->name)->whereRaw("(`phase` =  'Update Project Charter' OR `phase` = 'New Project Charter')")->exists()) {
            PMOActivity::where('id', $cek_sign->id)->delete(); 
        }

        $store_activity = new PMOActivity();
        $store_activity->id_project = $request['id_pmo'];
        
        $store_activity->operator = Auth::User()->name;
        if (PMOActivity::where('id_project', $request['id_pmo'])->exists()) {
            $store_activity->phase = 'Update Project Charter';
            $store_activity->activity = 'Update Project Charter';
        } else {
            $store_activity->phase = 'New Project Charter';
            $store_activity->activity = 'Create New Project Charter';
        }
        
        $store_activity->date_time = Carbon::now()->toDateTimeString();
        $store_activity->save();

        $datas = PMO::where('id',$request->id_pmo)->first();

        $store = new SLAProject();
        $store->pid = $datas->project_id;
        $cekSlaStandard = SLAProject::where('pid','Standard')->first();
        $store->date_add = Carbon::now()->toDateTimeString();

        if ($request->isSlaValue == 'No') {
            $store->sla_response = $cekSlaStandard->sla_response;
            $store->sla_resolution_critical = $cekSlaStandard->sla_resolution_critical;
            $store->sla_resolution_major = $cekSlaStandard->sla_resolution_major;
            $store->sla_resolution_moderate = $cekSlaStandard->sla_resolution_moderate;
            $store->sla_resolution_minor = $cekSlaStandard->sla_resolution_minor;
        } else {
            if (isset($request->slaResponseTime)) {
                $store->sla_response = str_replace(',', '.', $request->slaResponseTime);
            }else {
                $store->sla_response = $cekSlaStandard->sla_response;
            }
            if (isset($request->slaResolutionTimeCritical)) {
                $store->sla_resolution_critical = $request->slaResolutionTimeCritical;
            }else {
                $store->sla_resolution_critical = $cekSlaStandard->sla_resolution_critical;
            }
            if (isset($request->slaResolutionTimeMajor)) {
                $store->sla_resolution_major = $request->slaResolutionTimeMajor;
            }else {
                $store->sla_resolution_major = $cekSlaStandard->sla_resolution_major;
            }
            if (isset($request->slaResolutionTimeModerate)) {
                $store->sla_resolution_moderate = $request->slaResolutionTimeModerate;
            }else {
                $store->sla_resolution_moderate = $cekSlaStandard->sla_resolution_moderate;
            }
            if (isset($request->slaResolutionTimeMinor)) {
                $store->sla_resolution_minor = $request->slaResolutionTimeMinor;
            }else {
                $store->sla_resolution_minor = $cekSlaStandard->sla_resolution_minor;
            }
        }

        $store->save();

        if ($update->project_type == 'supply_only') {
            $project_type = 'Supply Only';
        }else if ($update->project_type == 'implementation') {
            $project_type = 'Implementation';
        }else{
            $project_type = 'Maintenance & Managed Service';
        }

        $user_pm = $update->project_pm;
        $user_pc = $update->project_pc;

        $email_user = User::join('role_user','role_user.user_id', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name', 'Project Management Office Manager')->first()->email;

        $mail = new MailPMProject(collect([
                "image"         => 'project_charter.png',
                "subject_email" => 'New Project Charter',
                "subject"       => 'There is new project charter,',
                "pid"           => $datas->project_id,
                "to"            => User::join('role_user','role_user.user_id', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name', 'Project Management Office Manager')->select('users.name as name')->first()->name,
                "name_project"  => DB::table('tb_id_project')->where('id_project',$datas->project_id)->first()->name_project,
                // "project_type"  => $project_type,
                "project_type"  => $datas->type_project,
                "sales_owner"   => DB::table('tb_id_project')->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')->join('users','users.nik','=','sales_lead_register.nik')->select('users.name')->where('id_project',$datas->project_id)->first()->name,
                "project_pm"    => $user_pm . "/" . $user_pc,
                "note_reject"   => '',
                "status"        => 'updateProjectCharter',
                'id'            => $request->id_pmo,
                'type_project'  => $datas->project_type

            ])
        );

        Mail::to($email_user)->send($mail);

        $approver = '';
    }

    public function getSignProjectCharter(Request $request)
    {
        // $get_dokumen = DB::table('tb_pmo_project_charter')->join('tb_pmo', 'tb_pmo.id', 'tb_pmo_project_charter.id_project')->join('tb_pmo_doc_project_charter', 'tb_pmo_doc_project_charter.id_project_charter', 'tb_pmo_project_charter.id')->join('tb_pmo_document', 'tb_pmo_document.id', 'tb_pmo_doc_project_charter.id_document')->select('document_name', 'document_location', 'link_drive', 'tb_pmo_document.id as id_document', 'tb_pmo_doc_project_charter.id as id_doc_project_charter', 'tb_pmo_doc_project_charter.id_project_charter')->where('tb_pmo.id', $request->id_pmo)->get();

        // if (isset($get_dokumen)) {
        //     return 'yes';
        // } else {
        //     return 'no';
        // }

        $data = PMO::where('id', $request->id_pmo)->first();

        $get_name_sales = DB::table('tb_id_project')->join('sales_lead_register', 'sales_lead_register.lead_id', 'tb_id_project.lead_id')->join('users', 'users.nik','sales_lead_register.nik')->select('users.name')->where('tb_id_project.id_project', $data->project_id)->first();

        $get_name_pm = PMO_assign::join('users', 'users.nik', 'tb_pmo_assign.nik')->join('tb_pmo', 'tb_pmo.id', 'tb_pmo_assign.id_project')->select('users.name')->where('id_project', $request->id_pmo)->first();

        if(PMOActivity::where('phase',"Update Project Charter")->where('id_project', $request->id_pmo)->exists()){
            $get_last_activity = DB::table('tb_pmo_activity')
                ->join('users', 'users.name', 'tb_pmo_activity.operator')
                ->select('id')
                ->where('id_project',$request['id_pmo'])
                ->where('tb_pmo_activity.phase', 'Update Project Charter')
                ->orderBy('date_time', 'desc')->take(1)->first();

            $unapproved = DB::table('tb_pmo_activity')
                ->where('id_project',$request['id_pmo'])
                ->where(function($query){
                    $query->where('tb_pmo_activity.phase', 'Reject Project Charter')->orWhere('phase', 'Update Project Charter');
                })
                ->where('tb_pmo_activity.id','!=',$get_last_activity->id)
                ->orderBy('id', 'desc')->get();
        } else {
            $unapproved = DB::table('tb_pmo_activity')
                ->where('id_project',$request['id_pmo'])
                ->where(function($query){
                    $query->where('tb_pmo_activity.phase', 'Reject Project Charter')->orWhere('phase', 'Update Project Charter');
                })
                ->orderBy('id', 'desc')->get();
        }
        
        $activity = DB::table('tb_pmo_activity')->where('id_project', $request['id_pmo']);

        if(count($unapproved) != 0){
            $activity->where('tb_pmo_activity.id','>',$unapproved->first()->id);
        }
            
        $activity->where(function($query){
            $query->where('tb_pmo_activity.phase', 'Approve Project Charter')
            ->orWhere('tb_pmo_activity.phase', 'Update Project Charter')
            ->orWhere('tb_pmo_activity.phase', 'New Project Charter');
        });

        $sign = User::join('role_user', 'role_user.user_id', '=', 'users.nik')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->select(
                    'users.name', 
                    'roles.name as position', 
                    'roles.group as group',
                    'users.ttd as ttd_digital',
                    'users.email',
                    'users.avatar',
                    DB::raw("IFNULL(SUBSTR(`temp_tb_pmo_activity`.`date_time`,1,10),'-') AS `date_sign`"),
                    DB::raw('IF(ISNULL(`temp_tb_pmo_activity`.`date_time`),"false","true") AS `signed`')
                )
            ->leftJoinSub($activity,'temp_tb_pmo_activity',function($join){
                // $join->on("temp_tb_pmo_activity.operator","=","users.name");
                $join->on("users.name","LIKE",DB::raw("CONCAT('%', temp_tb_pmo_activity.operator, '%')"));
            })
            ->where('users.id_company', '1')
            ->where('users.status_karyawan', '!=', 'dummy');

        if ($data->project_type == 'maintenance') {
            foreach ($sign->get() as $key => $value) {
                if ($value->name == 'Agustinus Angger Muryanto' && $value->signed == 'true') {
                    $sign->whereRaw("(`users`.`name` = '" . $get_name_pm->name . "' OR `roles`.`name` = 'VP Program & Project Management' OR `users`.`name` = '" . $get_name_sales->name . "')")
                    ->orderByRaw('FIELD(position, "Delivery Project Coordinator","VP Program & Project Management","Account Executive","VP Sales","BCD Manager","Chief Operating Officer")');
                } else{
                    $sign->whereRaw("(`users`.`name` = '" . $get_name_pm->name . "' OR `roles`.`name` = 'Project Management Office Manager' OR `users`.`name` = '" . $get_name_sales->name . "')")
                    ->orderByRaw('FIELD(position, "Delivery Project Coordinator","Project Management Office Manager","Account Executive","VP Sales","BCD Manager","Chief Operating Officer")');
                }
            }

        } else if ($data->project_type == 'implementation'){
            foreach ($sign->get() as $key => $value) {
                if ($value->name == 'Agustinus Angger Muryanto' && $value->signed == 'true') {
                    $sign->whereRaw("(`users`.`name` = '" . $get_name_pm->name . "' OR `roles`.`name` = 'VP Program & Project Management' OR `users`.`name` = '" . $get_name_sales->name . "')")
                    ->orderByRaw('FIELD(position, "Delivery Project Manager","VP Program & Project Management","Account Executive","VP Sales","Chief Operating Officer")');
                    return $sign->get();
                } else {
                    $sign->whereRaw("(`users`.`name` = '" . $get_name_pm->name . "' OR `roles`.`name` = 'Project Management Office Manager' OR `users`.`name` = '" . $get_name_sales->name . "')")
                    ->orderByRaw('FIELD(position, "Delivery Project Manager","Project Management Office Manager","Account Executive","VP Sales","BCD Manager","Chief Operating Officer")');
                }
            }
            
        }

        $status = 'all';

        if ($status == 'all') {
            return $sign->get();
        } else {
            return $sign->get()->where('signed','false')->first()->name;
        }
        
    }

    public function approveProjectCharter(Request $request)
    {
        $nik = Auth::User()->nik;
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')
                    ->select('name', 'group')->where('user_id', $nik)->first(); 

        $update = PMOProjectCharter::where('id_project', $request->id_pmo)->first();

        $data = PMO::join("tb_id_project","tb_id_project.id_project","=","tb_pmo.project_id")->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')->join('users','users.nik','=','sales_lead_register.nik')->where('tb_pmo.id',$request->id_pmo)->first();

        $store_activity = new PMOActivity();
        $store_activity->id_project = $request['id_pmo'];
        $store_activity->phase = 'Approve Project Charter';
        $store_activity->operator = Auth::User()->name;
        $store_activity->activity = 'Approve Project Charter';
        $store_activity->date_time = Carbon::now()->toDateTimeString();
        $store_activity->save();

        if ($cek_role->group == 'Sales' || $cek_role->name == 'Chief Operating Officer') {
            $update->status = 'Done';
            $subject_email = 'Approve Project Charter';
            $subject = 'Your project is available to run,';
            if ($update->project_pm != '-') {
                $user = $update->project_pm;
                $email_user = DB::table('users')->where('name',$update->project_pm)->first()->email;
            }else{
                $user = $update->project_pc;
                $email_user = DB::table('users')->where('name',$update->project_pc)->first()->email;
            }
                $user_pm = $update->project_pm;
                $user_pc = $update->project_pc;

            $this->uploadPdfPC($request->id_pmo);
        } else {
            $update->status = 'Approve';
            $subject_email = 'Approve Project Charter';
            $subject = 'You`re as project owner, please approve project charter to next regulation';            
            $email_user = $data->email;
            $user = $data->name;
            // if ($update->project_pm != '-') {
                $user_pm = $update->project_pm;
            // }else{
                $user_pc = $update->project_pc;
            // }
        }
        $update->save();

        // if ($data->project_type == 'supply_only') {
        //     $type_project = 'Supply Only';
        // }else if ($data->project_type == 'implementation') {
        //     $type_project = 'Implementation';
        // }else{
        //     $type_project = 'Maintenance & Managed Service';
        // }
        $datas = PMO::where('id',$request->id_pmo)->first();

        $mail = new MailPMProject(collect([
                "image"         => 'project_charter_approved.png',
                "subject_email" => $subject_email,
                "subject"       => $subject,
                "pid"           => $data->project_id,
                "to"            => $user,
                "name_project"  => DB::table('tb_id_project')->where('id_project',$datas->project_id)->first()->name_project,
                "project_type"  => $datas->type_project,
                "sales_owner"   => $data->name,
                "project_pm"    => $user_pm . "/" . $user_pc,
                "note_reject"   => '',
                "status"        => 'approveProjectCharter',
                "id"            => $request->id_pmo,
                'type_project'  => $datas->project_type
            ])
        );
        
        Mail::to($email_user)->send($mail);

        // if ($update->status == 'Done') {
        //     $this->uploadPdfPC($request->id_pmo);
        // }
    }

    public function rejectProjectCharter(Request $request)
    {
        $update_pc = PMOProjectCharter::where('id_project', $request->id_pmo)->first();
        $update_pc->status = 'Reject';
        $update_pc->save();

        // return $update_pc->project_pc;

        $store_activity = new PMOActivity();
        $store_activity->id_project = $request['id_pmo'];
        $store_activity->phase = 'Reject Project Charter';
        $store_activity->operator = Auth::User()->name;
        $store_activity->activity = 'Reject Project Charter for the following reasons:' . $request['reason'];
        $store_activity->date_time = Carbon::now()->toDateTimeString();
        $store_activity->save();

        $data = DB::table('tb_pmo')->join('tb_id_project', 'tb_id_project.id_project', 'tb_pmo.project_id')->join('sales_lead_register', 'sales_lead_register.lead_id', 'tb_id_project.lead_id')->join('users','users.nik', 'sales_lead_register.nik')->where('tb_pmo.id', $request->id_pmo)->first();

        if ($update_pc->project_pm != '-') {
            $user = $update_pc->project_pm;
            $email_user = DB::table('users')->where('name',$update_pc->project_pm)->first()->email;
        }else{
            $user = $update_pc->project_pc;
            $email_user = DB::table('users')->where('name',$update_pc->project_pc)->first()->email;

        }

        $user_pm = $update_pc->project_pm;
        $user_pc = $update_pc->project_pc;


        // if ($update_pc->project_type == 'supply_only') {
        //     $type_project = 'Supply Only';
        // }else if ($update_pc->project_type == 'implementation') {
        //     $type_project = 'Implementation';
        // }else{
        //     $type_project = 'Maintenance & Managed Service';
        // }

        $datas = PMO::where('id',$request->id_pmo)->first();

        $mail = new MailPMProject(collect([
                "image"         => 'sirkulasi_pr.png',
                "subject_email" => 'Reject Project Charter',
                "subject"       => 'Project charter has been rejected:',
                "pid"           => $data->project_id,
                "to"            => $user,
                "name_project"  => DB::table('tb_id_project')->where('id_project',$data->id_project)->first()->name_project,
                // "project_type"  => $project_type,
                "project_type"  => $datas->type_project,
                "sales_owner"   => $data->name,
                "project_pm"    => $user_pm . "/" . $user_pc,
                "note_reject"   => $update_pc->note_reject,
                "status"        => 'rejectProjectCharter',
                "id"            => $request->id_pmo,
                'type_project'  => $datas->project_type
            ])
        );
        
        Mail::to($email_user)->send($mail);
    }

    public function rejectFinalReport(Request $request)
    {
        $update = PMOFinalReport::where('id_project', $request->id_pmo)->first();
        $update->status = 'Reject';
        $update->save();

        $store_activity = new PMOActivity();
        $store_activity->id_project = $request['id_pmo'];
        $store_activity->phase = 'Reject Final Report';
        $store_activity->operator = Auth::User()->name;
        $store_activity->activity = 'Reject Final Report for the following reasons: ' . $request['reason'];
        $store_activity->date_time = Carbon::now()->toDateTimeString();
        $store_activity->save();

        $data = PMOFinalReport::where('id_project', $request->id_pmo)->first();

        if ($data->project_pm != '-') {
            $user = $data->project_pm;
            $email_user = DB::table('users')->where('name',$data->project_pm)->first()->email;

        }else{
            $user = $data->project_pc;
            $email_user = DB::table('users')->where('name',$data->project_pc)->first()->email;
        }

        $user_pm = $data->project_pm;
        $user_pc = $data->project_pc;

        // return [$user_pc,$user_pm];

        // if ($data->project_type == 'supply_only') {
        //     $type_project = 'Supply Only';
        // }else if ($data->project_type == 'implementation') {
        //     $type_project = 'Implementation';
        // }else{
        //     $type_project = 'Maintenance & Managed Service';
        // }

        $datas = PMO::where('id',$request->id_pmo)->first();

        $mail = new MailPMProject(collect([
                "image"         => 'sirkulasi_pr.png',
                "subject_email" => 'Reject Final Report',
                "subject"       => 'Final Report has been rejected,',
                "pid"           => $datas->project_id,
                "to"            => $user,
                "name_project"  => DB::table('tb_id_project')->where('id_project',$datas->project_id)->first()->name_project,
                "project_type"  => $datas->type_project,
                "sales_owner"   => $data->owner,
                "project_pm"    => $user_pm . "/" . $user_pc,
                "note_reject"   => $data->note_reject,
                "status"        => 'rejectFinalReport',
                "id"            => $request->id_pmo,
                'type_project'  => $datas->project_type
            ])
        );
        
        Mail::to($email_user)->send($mail);
    }

    public function showProjectCharter(Request $request)
    {
        $getListLeadRegister = DB::table('sales_lead_register')->join('tb_id_project', 'tb_id_project.lead_id', '=', 'sales_lead_register.lead_id')
                        ->join('users','users.nik','=','sales_lead_register.nik')
                        ->select('opp_name as name_project','tb_id_project.id_project as id_project','name as owner','no_po_customer','tb_id_project.amount_idr as amount','impact_description');

            // $data = DB::table('tb_pmo_project_charter')->join('tb_pmo', 'tb_pmo.id', 'tb_pmo_project_charter.id_project')->LeftjoinSub($getListLeadRegister, 'project_id', function($join){
          //                   $join->on('tb_pmo.project_id', '=', 'project_id.id_project');
          //               })->join('tb_pmo_assign','tb_pmo_assign.id_project','=','tb_pmo.id')
                //      ->join('users','users.nik','=','tb_pmo_assign.nik')
                //      // ->join('tb_pmo_project_charter','tb_pmo_project_charter.id_project','=','tb_pmo.id')
                //      ->select('name_project','project_id as id_project','current_phase','project_type','owner','no_po_customer',
                //          DB::raw('(CASE WHEN role = "Delivery Project Manager" THEN name END) AS project_pm'),
                //          DB::raw('(CASE WHEN role = "Delivery Project Coordinator" THEN name END) AS project_pc'),
                //          'project_description',
                //          'project_objectives',
                //          'estimated_start_date',
                //          'estimated_end_date',
                //          'flexibility',
                //          'scope_of_work',
                //          'out_of_scope',
                //          'customer_requirement',
                //          'terms_of_payment',
                //          'tb_pmo_project_charter.date_time', 
                //          'customer_name',    
                //          'customer_phone',   
                //          'customer_cp',  
                //          'customer_email',   
                //          'customer_cp_phone',    
                //          'customer_cp_title',
                //          'market_segment', 'customer_address')
          //               ->where('tb_pmo_project_charter.id_project',$request->id_pmo)->get();

        $getPid = DB::table('tb_pmo')->select('project_id')->where('id',$request->id_pmo)->first()->project_id;
        $countPid = DB::table('tb_pmo')->where('project_id',$getPid)->count();

        if ($countPid == 2 && DB::table('tb_pmo')->select('project_type')->where('id',$request->id_pmo)->first()->project_type == 'maintenance') {
            $id_pmo = $request->id_pmo-1;
        } else {
            $id_pmo = $request->id_pmo;
        }

        $data = PMOProjectCharter::join('tb_pmo', 'tb_pmo.id', 'tb_pmo_project_charter.id_project')
            ->leftjoin('tb_sla_project', 'tb_pmo.project_id', 'tb_sla_project.pid')
            ->where('tb_pmo_project_charter.id_project',$id_pmo)->get();

        return $data;
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
        $file->setParents([env('GOOGLE_DRIVE_PARENT_ID_PMO')]);

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

    public function getIssue(Request $request){
        return array("data"=>DB::table('tb_pmo_issue')->where('id_project',$request->id_pmo)->get());
    }

    public function getRisk(Request $request){
        return array("data"=>DB::table('tb_pmo_identified_risk')
                ->select('id','risk_description','risk_owner','risk_response','impact','likelihood','impact_rank','due_date','review_date','status',DB::raw("(CASE WHEN (impact_description is null) THEN '-' ELSE impact_description  END) as impact_description"))->where('id_project',$request->id_pmo)->get());
    }

    public function getDetailIssue(Request $request){
        return DB::table('tb_pmo_issue')->where('id',$request->id)->get();
    }

    public function getDetailRisk(Request $request){
        return DB::table('tb_pmo_identified_risk')->where('id',$request->id)->get();
    }

    public function getMilestone(Request $request){
        $dataInitiating = DB::table('gantt_tasks_pmo')->where('id_pmo',$request->id_pmo)->where('parent',0)->where('text','Initiating')->first();
        $dataPlanning = DB::table('gantt_tasks_pmo')->where('id_pmo',$request->id_pmo)->where('parent',0)->where('text','Planning')->first();
        $dataExecuting = DB::table('gantt_tasks_pmo')->where('id_pmo',$request->id_pmo)->where('parent',0)->where('text','Executing')->first();
        $dataClosing = DB::table('gantt_tasks_pmo')->where('id_pmo',$request->id_pmo)->where('parent',0)->where('text','Closing')->first();
        $pid = PMO::where('id',$request->id_pmo)->first()->project_id;

        $milestone = 'false';
        if(DB::table('gantt_tasks_pmo')->where('id_pmo',$request->id_pmo)->exists()){
            $milestone = 'true';
        }

        $kickoff = 'false';
        if(DB::table('gantt_tasks_pmo')->where('id_pmo',$request->id_pmo)->where('text', 'Kick Off Meeting')->where('status','Done')->exists()){
            $kickoff = 'true';
        }

        $finalreport = 'false';
        $approveFinalReport = '';

        if(DB::table('tb_pmo_final_report')->where('id_project',$request->id_pmo)->where('status', 'New')->orderby('id','desc')->limit(1)->exists()){
            $finalreport = 'true';
            $approveFinalReport = '';
        }else if (DB::table('tb_pmo_final_report')->where('id_project',$request->id_pmo)->where('status', 'Reject')->orderby('id','desc')->limit(1)->exists()) {
            $finalreport = 'false';
            $approveFinalReport = 'false';
        }else if (DB::table('tb_pmo_final_report')->where('id_project',$request->id_pmo)->where('status', 'Approve')->orderby('id','desc')->limit(1)->exists()) {
            $approveFinalReport = 'true';
            $finalreport = 'true';   
        }

        $sendCss = 'false';
        if (DB::table('tb_pmo_activity')->where('id_project',$request->id_pmo)->where('activity', 'CSS has been sent')->orderby('id','desc')->limit(1)->exists()) {
            $sendCss = 'true';
        }

        $isProgressReport = 'false';
        if (DB::table('tb_pmo_progress_report')->where('id_project',$request->id_pmo)->exists()) {
            $isProgressReport = 'true';
        }

        $isIssueRiskClear = 'true';
        if (DB::table('tb_pmo_identified_risk')->where('id_project',$request->id_pmo)->whereRaw("(`status` = 'Active')")->exists() || DB::table('tb_pmo_issue')->where('id_project',$request->id_pmo)->where('status','Open')->exists()) {
            $isIssueRiskClear = 'false';
        }

        $ganttStatus = DB::table('tb_pmo')->select('gantt_status')->where('id',$request->id_pmo)->first()->gantt_status;


        if (!empty($dataInitiating)) {
            $getLatestSubtaskInitiating = DB::table('gantt_tasks_pmo')->select(DB::raw('(CASE WHEN status = "Done" THEN end_date ELSE null END) AS end_date'))->where('parent',$dataInitiating->id)->orderBy('id','desc')->limit(1)->first();
            $getLatestSubtaskPlanning = DB::table('gantt_tasks_pmo')->select(DB::raw('(CASE WHEN status = "Done" THEN end_date ELSE null END) AS end_date'))->where('parent',$dataPlanning->id)->orderBy('id','desc')->limit(1)->first();
            $getLatestSubtaskExecuting = DB::table('gantt_tasks_pmo')->select(DB::raw('(CASE WHEN status = "Done" THEN end_date ELSE null END) AS end_date'))->where('parent',$dataExecuting->id)->orderBy('id','desc')->limit(1)->first();
            $getLatestSubtaskClosing = DB::table('gantt_tasks_pmo')->select(DB::raw('(CASE WHEN status = "Done" THEN end_date ELSE null END) AS end_date'))->where('parent',$dataClosing->id)->orderBy('id','desc')->limit(1)->first();

            $getLatestActivityInitiating = DB::table('gantt_tasks_pmo')->where('parent',$dataInitiating->id)->orderBy('id','desc')->limit(1)->where('status','Done')->first();

            $getLatestActivityPlanning = DB::table('gantt_tasks_pmo')->where('parent',$dataPlanning->id)->orderBy('id','desc')->limit(1)->where('status','Done')->first();

            $getLatestActivityExecuting = DB::table('gantt_tasks_pmo')->where('parent',$dataExecuting->id)->orderBy('id','desc')->limit(1)->where('status','Done')->first();

            $getLatestActivityClosing = DB::table('gantt_tasks_pmo')->where('parent',$dataClosing->id)->orderBy('id','desc')->limit(1)->where('status','Done')->first();

            $dataInitiatingFinal    = collect($dataInitiating)->put('end_date_final',empty($getLatestSubtaskInitiating)?NUll:$getLatestSubtaskInitiating->end_date)->put('last_end_date',empty($getLatestActivityInitiating)?NUll:$getLatestActivityInitiating->end_date)->put('last_sub_task',empty($getLatestActivityInitiating)?"-":$getLatestActivityInitiating->text);
            $dataPlanningFinal      = collect($dataPlanning)->put('end_date_final',empty($getLatestSubtaskPlanning)?NUll:$getLatestSubtaskPlanning->end_date)->put('last_sub_task',empty($getLatestActivityPlanning)?"-":$getLatestActivityPlanning->text)->put('last_end_date',empty($getLatestActivityPlanning)?NUll:$getLatestActivityPlanning->end_date);
            $dataExecutingFinal     = collect($dataExecuting)->put('end_date_final',empty($getLatestSubtaskExecuting)?NUll:$getLatestSubtaskExecuting->end_date)->put('last_end_date',empty($getLatestActivityExecuting)?NUll:$getLatestActivityExecuting->end_date)->put('last_sub_task',empty($getLatestActivityExecuting)?"-":$getLatestActivityExecuting->text);
            $dataClosingFinal       = collect($dataClosing)->put('end_date_final',empty($getLatestSubtaskClosing)?NUll:$getLatestSubtaskClosing->end_date)->put('last_end_date',empty($getLatestActivityClosing)?NUll:$getLatestActivityClosing->end_date)->put('last_sub_task',empty($getLatestActivityClosing)?"-":$getLatestActivityClosing->text);

            $data = array("data"=>collect(["Initiating"=>$dataInitiatingFinal->all(),"Planning"=>$dataPlanningFinal->all(),"Executing"=>$dataExecutingFinal->all(),"Closing"=>$dataClosingFinal->all(), "milestone"=>$milestone, "kickoff"=>$kickoff, "finalreport"=>$finalreport, "sendCss"=>$sendCss,"approveFinalReport"=>$approveFinalReport,"pid"=>$pid,"isProgressReport"=>$isProgressReport,"isIssueRiskClear"=>$isIssueRiskClear,"ganttStatus"=>$ganttStatus]));

        }else{
            $data = array("data"=>collect(["Initiating"=>null,"Planning"=>null,"Executing"=>null,"Closing"=>null, "milestone"=>$milestone, "kickoff"=>$kickoff, "finalreport"=>$finalreport,"approveFinalReport"=>$approveFinalReport,"pid"=>$pid,"isProgressReport"=>$isProgressReport,"isIssueRiskClear"=>$isIssueRiskClear,"ganttStatus"=>$ganttStatus]));
        }        

        return $data;
    }

    public function getStageWeekly(Request $request){
          // return (String)Carbon::now()->endOfWeek()->format("Y-m-d");
        $dateFormat = DB::table('gantt_tasks_pmo')->select(DB::raw("DATE_FORMAT(baseline_start, '%d %M %Y') as start_date_format"),DB::raw("DATE_FORMAT(baseline_end, '%d %M %Y') as end_date_format"),'gantt_tasks_pmo.id as id_gantt')->where('parent','!=',0)->where('duration','!=',0); 

        $dataParent = DB::table('gantt_tasks_pmo')->select('id as id_parent','text as text_parent')->where('parent','==',0)->where('duration','!=',0);  
        // return $dataParent->get();

        $startOfWeek = Carbon::now()->startOfWeek()->format('Y-m-d');
        $endOfWeek = Carbon::now()->endOfWeek()->format('Y-m-d');

        // return $endOfWeek;

        // return gettype(date($startOfWeek));

        // $startOfWeek = strtotime($startOfWeek);
        // $startOfWeek = date('Y-m-d',$startOfWeek);
        // $first_day_this_month = date('Y-m-01 H:i:s');
        // $yesterday = date('Y-m-d H:i:s', strtotime("-1 day"));

        $start_date = "2023-02-02";
        $end_date   = "2023-02-14";

        $dataMilestone = GanttTaskPmo::leftJoinSub($dateFormat, 'format_date_task',function($join){
                        $join->on("gantt_tasks_pmo.id", '=', 'format_date_task.id_gantt');
                    })->leftJoinSub($dataParent, 'parent_text',function($join){
                        $join->on("gantt_tasks_pmo.parent", '=', 'parent_text.id_parent');
                    })
                    // ->select(DB::raw("CONCAT(format_date_task.`start_date_format`,' - ',format_date_task.`end_date_format`) AS periode"),'text as milestone','format_date_task.id_gantt','parent_text.text_parent')->where('id_pmo',$request->id_pmo)->where('status','!=','Done')->whereBetween('start_date', [Carbon::now()->startOfWeek()->format("Y-m-d"),Carbon::now()->endOfWeek()->format("Y-m-d")])->get();
                    ->select(DB::raw("CONCAT(format_date_task.`start_date_format`,' - ',format_date_task.`end_date_format`) AS periode"),'text as milestone','format_date_task.id_gantt','parent_text.text_parent', 'deliverable_document')
                    ->where('id_pmo',$request->id_pmo)
                    ->where('status','!=','Done')
                    ->where('duration','!=',0)
                    // ->where(function($query) use ($startOfWeek, $endOfWeek){
                    //   $query->whereBetween('baseline_start', [$startOfWeek,$endOfWeek])
                    //         ->orWhereBetween('baseline_end', [$startOfWeek,$endOfWeek]);
                    // })
                    ->get();

         // $dataMilestone = GanttTaskPmo::where('id_pmo',$request->id_pmo)->get();
                // ->whereBetween('start_date', [Carbon::subWeek()->format("Y-m-d H:i:s"), Carbon::now()])

        // $data = DB::table('gantt_tasks_pmo')->select(DB::raw("CONCAT(`start_date`,' - ',`end_date`) AS periode"),'text as milestone')->where('parent',74)->get();

        return array("data"=> $dataMilestone);
    }

    public function getGantt(Request $request){
        $tasks = new GanttTaskPmo();
        $links = new GanttLink();
 
        return response()->json([
            "data" => $tasks->where('id_pmo', $request->id_pmo)->get(),
            "links" => $links->all(),
        ]);
    }

    public function getMilestoneById(Request $request){
        $type = $request->type ;
        $getParent = GanttTaskPmo::where('id_pmo',$request->id_pmo)->where('parent',0)->orderByRaw('FIELD(text, "Initiating", "Planning", "Executing", "Closing")')->get();
        // return $getParent;
        // $dataGantt = GanttTaskPmo::where('id_pmo', $request->id_pmo)->where('parent','!=',0)->groupBy('parent')->get();
        foreach($getParent as $dataParent){
            $modifiedData = $dataParent->text;
            if ($dataParent->text == 'Executing') {
                // return $dataParent->id;
                if (explode("&",$type)[0] == 'implementation') {
                    $getParentKey = GanttTaskPmo::where('id_pmo',$request->id_pmo)->where('parent',$dataParent->id)->get();

                    foreach($getParentKey as $dataParentkey){
                        // return $key;
                        if ($dataParentkey->duration == 0) {
                            $milestoneArray[$modifiedData][$dataParentkey->text] = GanttTaskPmo::where('id_pmo', $request->id_pmo)->where('parent', $dataParentkey->id)->get(); 
                        }

                        if ($dataParentkey->duration != 0) {
                            $milestoneArray[$modifiedData] = GanttTaskPmo::where('id_pmo', $request->id_pmo)->where('parent', $dataParent->id)->get();
                        }                     
                    }
                }else{
                    $milestoneArray[$modifiedData] = GanttTaskPmo::where('id_pmo', $request->id_pmo)->where('parent', $dataParent->id)->get();
                }
                // if (!empty($getParentKey)) {
                    
                // }else{
                //     $milestoneArray[$modifiedData] = GanttTaskPmo::where('id_pmo', $request->id_pmo)->where('parent', $dataParent->id)->get()->orderBy("id")->get()->groupBy('text');
                // }                    
            }else{
                $milestoneArray[$modifiedData] = GanttTaskPmo::where('id_pmo', $request->id_pmo)->where('parent', $dataParent->id)->get();
            }  
            // $milestoneArray[$modifiedData] = GanttTaskPmo::where('id_pmo', $request->id_pmo)->where('parent', $dataParent->id)->orderBy("id")->get()->groupBy('text');
            // $milestoneArray = $getParentKey;
        }

        $ganttStatus = DB::table('tb_pmo')->select('gantt_status')->where('id',$request->id_pmo)->first()->gantt_status;

        // return [$milestoneArray,$ganttStatus];
        return $milestoneArray;

        // return $dataParent->text;
    }

    public function postIssueProblems(Request $request){
        $get_id = DB::table('tb_pmo_issue')->where('id', $request->id)->first();
        if (isset($get_id)) {
            $store = PMOIssue::where('id', $request->id)->first();
        } else {
            $store = new PMOIssue; 
        } 
        $store->id_project = $request->id_pmo;
        $store->issue_description = $request->textareaDescIssue;
        $store->solution_plan = $request->textareaSolutionPlan;
        $store->owner = $request->inputOwnerIssue;
        $store->rating_severity = $request->inputRatingIssue;
        $expected_date = strtotime($_POST['expected_date']); 
        $expected_date = date("Y-m-d",$expected_date);
        $store->expected_date = $expected_date;
        $actual_date = strtotime($_POST['actual_date']); 
        $actual_date = date("Y-m-d",$actual_date);
        if (isset($request->actual_date)) {
            $store->actual_date = $actual_date;
        }
        
        $store->status = $request->selectStatusIssue;
        $store->save();

        $activity = new PMOActivity();
        $activity->id_project = $request->id_pmo;
        $activity->phase = 'Add Issue';
        $activity->operator = Auth::User()->name;
        $activity->activity = 'Add Issue';
        $activity->date_time = Carbon::now()->toDateTimeString();
        $activity->save();
    }

    public function postRisk(Request $request){
        $get_id = DB::table('tb_pmo_identified_risk')->where('id', $request->id)->first();
        if (isset($get_id)) {
            $store = PMORisk::where('id', $request->id)->first();
        } else {
            $store = new PMORisk;
        }
        
        $store->id_project = $request->id_pmo;
        $store->risk_description = $request->textAreaRisk;
        $store->risk_owner = $request->inputOwner;
        $store->impact = $request->inputImpact;
        $store->likelihood = $request->inputLikelihood;
        if (isset($request->inputRank)) {
            $store->impact_rank = $request->inputRank;
        } else{
            $store->impact_rank = (int)$request->inputImpact*(int)$request->inputLikelihood;
        }
        $store->impact_description = $request->textareaDescription;
        $store->risk_response = $request->textareaResponse;
        $due_date = strtotime($_POST['due_date']); 
        $due_date = date("Y-m-d",$due_date);
        $store->due_date = $due_date;
        $review_date = strtotime($_POST['review_date']); 
        $review_date = date("Y-m-d",$review_date);
        $store->review_date = $review_date;
        $store->status = $request->selectStatusRisk;
        $store->save();

        $activity = new PMOActivity();
        $activity->id_project = $request->id_pmo;
        $activity->phase = 'Add Risk';
        $activity->operator = Auth::User()->name;
        $activity->activity = 'Add Risk';
        $activity->date_time = Carbon::now()->toDateTimeString();
        $activity->save();
    }

    public function getShowDocument(Request $request){

        $getpid = DB::table('tb_pmo')->where('id',$request->id_pmo)->first()->project_id;

        $docProj = DB::table('tb_pmo_doc_project')->join('tb_pmo_document','tb_pmo_document.id','=','tb_pmo_doc_project.id_document')->leftJoin('gantt_tasks_pmo','gantt_tasks_pmo.id',"=","tb_pmo_doc_project.sub_task")->join('tb_pmo','tb_pmo.id','tb_pmo_doc_project.id_project')->where("project_id",$getpid)->orderBy('tb_pmo_doc_project.date_time','desc')->get();

        $docProjCharter = DB::table('tb_pmo_document')->join('tb_pmo_doc_project_charter', 'tb_pmo_doc_project_charter.id_document', 'tb_pmo_document.id')->join('tb_pmo_project_charter','tb_pmo_project_charter.id','tb_pmo_doc_project_charter.id_project_charter')->join('tb_pmo','tb_pmo.id','tb_pmo_project_charter.id_project')->where('tb_pmo.project_id',$getpid)->orderBy('tb_pmo_doc_project_charter.date_time','desc')->get();

        return array("data"=>$docProj->merge($docProjCharter));

        // return array("data"=>$docProj );
    }

    public function getDeliverableDocument(Request $request)
    {
        // return $getDeliverableDocument = DB::table('tb_pmo')->join('gantt_tasks_pmo', 'gantt_tasks_pmo.id_pmo', 'tb_pmo.id')->join('tb_pmo_define_task', 'tb_pmo_define_task.sub_task', 'gantt_tasks_pmo.text')->select('tb_pmo_define_task.deliverable_document', 'text')->where('tb_pmo.id', $request->id_pmo)->get();
        // $get_project_type = DB::table('tb_pmo')->where('tb_pmo.id', $request->id_pmo)->first()->project_type;

        // return $getDeliverableDocument = DB::table('tb_pmo_define_task')->join('gantt_tasks_pmo', 'tb_pmo_define_task.sub_task', 'gantt_tasks_pmo.text')->select('tb_pmo_define_task.deliverable_document', 'text')->where('id_pmo', $request->id_pmo)->where('tb_pmo_define_task.project_type',$get_project_type)->where('tb_pmo_define_task.sub_task', 'PO / SPK from customer')->first()->deliverable_document;

        return array("data"=>GanttTaskPmo::select(DB::raw('`id` AS `id`,`text` AS `text`'))->where("id_pmo",$request->id_pmo)->whereRaw("(`deliverable_document` =  'true' OR `deliverable_document` = 'tentative' OR `deliverable_document` =  'Done')")->whereRaw("(`status` =  'On-Going' OR `status` = 'Done')")->get());
    }

    public function storeMilestone(Request $request){
        DB::beginTransaction();

        try {
            $activity = new PMOActivity();
            $activity->id_project = $request->id_pmo;
            $activity->phase = 'Add Milestone';
            $activity->operator = Auth::User()->name;
            $activity->activity = 'Add Milestone';
            $activity->date_time = Carbon::now()->toDateTimeString();
            $activity->save();

            if (GanttTaskPmo::where('id_pmo', $request->id_pmo)->exists()) {
                if ($request->current_save == "form_Initiating") {
                    $parent = GanttTaskPmo::where('id_pmo', $request->id_pmo)->where('text','Initiating')->first()->id;
                    GanttTaskPmo::where('parent', $parent)->delete();

                    $arrInitiatingMilestone = json_decode($request->arrInitiating,true);
                    foreach($arrInitiatingMilestone as $dataInitiating){
                        // $get_project_type = DB::table('tb_pmo')->where('tb_pmo.id', $request->id_pmo)->first();

                        // $getDeliverableDocument = DB::table('tb_pmo_define_task')->select('tb_pmo_define_task.deliverable_document', 'sub_task')->where('tb_pmo_define_task.project_type',$get_project_type->project_type)->where('sub_task', $dataInitiating["labelTask"])->first();

                        $storeInitiating = new GanttTaskPmo;

                        $storeInitiating->parent      = DB::table('gantt_tasks_pmo')->where('id_pmo',$request->id_pmo)->where('text','Initiating')->first()->id; 
                        $storeInitiating->text        = $dataInitiating["labelTask"];
                        if ($dataInitiating["startDateMilestone"] != "Invalid date" && $dataInitiating["finishDateMilestone"] != "Invalid date") {
                            $storeInitiating->start_date  = $dataInitiating["startDateMilestone"];
                            $storeInitiating->end_date    = $dataInitiating["finishDateMilestone"];
                            $storeInitiating->duration    = round((strtotime($dataInitiating["finishDateMilestone"]) - strtotime($dataInitiating["startDateMilestone"])) / (60 * 60 * 24)) + 1;
                        }
                        $storeInitiating->bobot       = $dataInitiating["weightMilestone"];
                        $storeInitiating->id_pmo      = $request["id_pmo"];
                        if (DB::table('tb_pmo')->where('id', $request->id_pmo)->first()->project_type == 'supply_only') {
                            $storeInitiating->status      = 'On-Going';
                        } else {
                            $storeInitiating->status      = 'Done';
                        }
                        $storeInitiating->deliverable_document = $dataInitiating["deliverableDoc"];
                        $storeInitiating->save();

                        $getId = DB::table('gantt_tasks_pmo')->where('id_pmo',$request->id_pmo)->where('text','Initiating')->first()->id;
                        $update = GanttTaskPmo::where('text','Initiating')->where('id_pmo',$request->id_pmo)->first();
                        $update->start_date = DB::table('gantt_tasks_pmo')->where('parent',$getId)->orderby('id','asc')->limit('1')->first()->start_date;
                        $update->update();
                    }                
                }else if ($request->current_save == "form_Planning") {
                    $parent = GanttTaskPmo::where('id_pmo', $request->id_pmo)->where('text','Planning')->first()->id;
                    GanttTaskPmo::where('parent', $parent)->delete();
                    
                    $arrPlanningMilestone = json_decode($request->arrPlanning,true);
                    foreach($arrPlanningMilestone as $dataPlanning){
                        $storePlanning = new GanttTaskPmo;

                        $storePlanning->parent          = DB::table('gantt_tasks_pmo')->where('id_pmo',$request->id_pmo)->where('text','Planning')->first()->id; 
                        $storePlanning->text            = $dataPlanning["labelTask"];
                        if ($dataPlanning["startDateMilestone"] != "Invalid date" && $dataPlanning["startDateMilestone"] != "Invalid date") {
                            $storePlanning->start_date      = $dataPlanning["startDateMilestone"];
                            $storePlanning->end_date        = $dataPlanning["finishDateMilestone"];
                            $storePlanning->duration        = round((strtotime($dataPlanning["finishDateMilestone"]) - strtotime($dataPlanning["startDateMilestone"])) / (60 * 60 * 24)) + 1;
                        }
                        $storePlanning->bobot           = $dataPlanning["weightMilestone"];
                        $storePlanning->id_pmo          = $request["id_pmo"];
                        $storePlanning->status          = 'On-Going';
                        $storePlanning->deliverable_document = $dataPlanning["deliverableDoc"];
                        $storePlanning->save();

                        $getId = DB::table('gantt_tasks_pmo')->where('id_pmo',$request->id_pmo)->where('text','Planning')->first()->id;
                        $update = GanttTaskPmo::where('text','Planning')->where('id_pmo',$request->id_pmo)->first();
                        $update->start_date = DB::table('gantt_tasks_pmo')->where('parent',$getId)->orderby('id','asc')->limit('1')->first()->start_date;
                        $update->update();
                    }
                }else if ($request->current_save == "form_Executing") {
                    $parent = GanttTaskPmo::where('id_pmo', $request->id_pmo)->where('text','Executing')->first()->id;
                    GanttTaskPmo::where('parent', $parent)->delete();

                    $arrExecutingMilestone = json_decode($request->arrExecuting,true);
                    foreach($arrExecutingMilestone as $key => $dataExecuting){
                        // return $key;
                        $cek = var_dump($key, is_numeric($key));
                        if (is_numeric($key)) {
                           // if (is_array($dataExecuting)) {
                                $storeExecuting = new GanttTaskPmo;               
                                $storeExecuting->parent         = DB::table('gantt_tasks_pmo')->where('id_pmo',$request->id_pmo)->where('text','Executing')->first()->id;
                                $storeExecuting->text           = $dataExecuting["labelTask"];
                                if ($dataExecuting["startDateMilestone"] != "Invalid date" && $dataExecuting["finishDateMilestone"] != "Invalid date") {
                                    $storeExecuting->start_date     = $dataExecuting["startDateMilestone"];
                                    $storeExecuting->end_date       = $dataExecuting["finishDateMilestone"];
                                    $storeExecuting->duration       = round((strtotime($dataExecuting["finishDateMilestone"]) - strtotime($dataExecuting["startDateMilestone"])) / (60 * 60 * 24)) + 1;
                                }
                                $storeExecuting->bobot          = $dataExecuting["weightMilestone"];
                                $storeExecuting->id_pmo         = $request["id_pmo"];
                                $storeExecuting->status         = 'On-Going';
                                $storeExecuting->deliverable_document = $dataExecuting["deliverableDoc"];
                                $storeExecuting->save();
                            // }                                         

                            $getId = DB::table('gantt_tasks_pmo')->where('id_pmo',$request->id_pmo)->where('text','Executing')->first()->id;
                            $update = GanttTaskPmo::where('text','Executing')->where('id_pmo',$request->id_pmo)->first();
                            $update->start_date = DB::table('gantt_tasks_pmo')->where('parent',$getId)->orderby('id','asc')->limit('1')->first()->start_date;
                            $update->update();
                            
                        }else{
                            $storeKey = new GanttTaskPmo;
                            $storeKey->text        = $key;
                            $storeKey->id_pmo      = $request->id_pmo;
                            $storeKey->parent      = DB::table('gantt_tasks_pmo')->where('id_pmo',$request->id_pmo)->where('text','Executing')->first()->id;
                            $storeKey->duration    = 0;
                            $storeKey->status      = 'On-Going';
                            $storeKey->save();

                            foreach($arrExecutingMilestone[$key] as $dataExecutingKey){
                                $storeExecuting = new GanttTaskPmo;               
                                $storeExecuting->parent         = $storeKey->id; 
                                if ($dataExecutingKey["labelSolution"] != "") {
                                    $storeExecuting->text           = "[ ".$dataExecutingKey["labelSolution"]." ] - " . $dataExecutingKey["labelTask"];
                                }else{
                                    $storeExecuting->text           = $dataExecutingKey["labelTask"];
                                }

                                if ($dataExecutingKey["startDateMilestone"] != "Invalid date" && $dataExecutingKey["finishDateMilestone"] != "Invalid date") {
                                    $storeExecuting->start_date     = $dataExecutingKey["startDateMilestone"];
                                    $storeExecuting->end_date       = $dataExecutingKey["finishDateMilestone"];
                                    $storeExecuting->duration       = round((strtotime($dataExecutingKey["finishDateMilestone"]) - strtotime($dataExecutingKey["startDateMilestone"])) / (60 * 60 * 24)) + 1;
                                }
                                $storeExecuting->bobot          = $dataExecutingKey["weightMilestone"];
                                $storeExecuting->id_pmo         = $request["id_pmo"];
                                $storeExecuting->status         = 'On-Going';
                                $storeExecuting->deliverable_document = $dataExecutingKey["deliverableDoc"];
                                $storeExecuting->save();
                            }                

                            $getId = DB::table('gantt_tasks_pmo')->where('id_pmo',$request->id_pmo)->where('text','Executing')->first()->id;
                            $update = GanttTaskPmo::where('text','Executing')->where('id_pmo',$request->id_pmo)->first();
                            $update->start_date = DB::table('gantt_tasks_pmo')->where('parent',$getId)->orderby('id','asc')->limit('1')->first()->start_date;
                            $update->update();
                        }
                    }
                }else if ($request->current_save == "form_Closing") {
                    $parent = GanttTaskPmo::where('id_pmo', $request->id_pmo)->where('text','Closing')->first()->id;
                    GanttTaskPmo::where('parent', $parent)->delete();

                    $arrClosingMilestone = json_decode($request->arrClosing,true);
                        foreach($arrClosingMilestone as $dataClosing){
                            $storeClosing = new GanttTaskPmo;

                            $storeClosing->parent                   = DB::table('gantt_tasks_pmo')->where('id_pmo',$request->id_pmo)->where('text','Closing')->first()->id;  
                            $storeClosing->text                     = $dataClosing["labelTask"];
                            if ($dataClosing["startDateMilestone"] != "Invalid date" && $dataClosing["finishDateMilestone"] != "Invalid date") {
                                $storeClosing->start_date               = $dataClosing["startDateMilestone"];
                                $storeClosing->end_date                 = $dataClosing["finishDateMilestone"];
                                $storeClosing->duration                 = round((strtotime($dataClosing["finishDateMilestone"]) - strtotime($dataClosing["startDateMilestone"])) / (60 * 60 * 24)) + 1;
                            }
                            $storeClosing->bobot                    = $dataClosing["weightMilestone"];
                            $storeClosing->id_pmo                   = $request["id_pmo"];
                            $storeClosing->status                   = 'On-Going';
                            $storeClosing->deliverable_document     = $dataClosing["deliverableDoc"];
                            $storeClosing->save();

                            $getId = DB::table('gantt_tasks_pmo')->where('id_pmo',$request->id_pmo)->where('text','Closing')->first()->id;
                            $update = GanttTaskPmo::where('text','Closing')->where('id_pmo',$request->id_pmo)->first();
                            $update->start_date = DB::table('gantt_tasks_pmo')->where('parent',$getId)->orderby('id','asc')->limit('1')->first()->start_date;
                            $update->update();
                        }
                }
            }else{
                $arrMainMilestone = json_decode($request->arrMainMilestone,true);
                foreach($arrMainMilestone as $data){
                    $store = new GanttTaskPmo;
                    // return $data;
                    $store->text        = $data;
                    $store->id_pmo      = $request->id_pmo;
                    $store->parent      = 0;
                    $store->duration    = 0;
                    $store->status      = 'Done';
                    $store->save();

                }

                if (count(json_decode($request->arrInitiating)) > 0) {
                    $arrInitiatingMilestone = json_decode($request->arrInitiating,true);
                    foreach($arrInitiatingMilestone as $dataInitiating){
                        // $get_project_type = DB::table('tb_pmo')->where('tb_pmo.id', $request->id_pmo)->first();

                        // $getDeliverableDocument = DB::table('tb_pmo_define_task')->select('tb_pmo_define_task.deliverable_document', 'sub_task')->where('tb_pmo_define_task.project_type',$get_project_type->project_type)->where('sub_task', $dataInitiating["labelTask"])->first();

                        $storeInitiating = new GanttTaskPmo;

                        $storeInitiating->parent      = DB::table('gantt_tasks_pmo')->where('id_pmo',$request->id_pmo)->where('text','Initiating')->first()->id; 
                        $storeInitiating->text        = $dataInitiating["labelTask"];
                        if ($dataInitiating["startDateMilestone"] != "Invalid date" && $dataInitiating["finishDateMilestone"] != "Invalid date") {
                            $storeInitiating->start_date  = $dataInitiating["startDateMilestone"];
                            $storeInitiating->end_date    = $dataInitiating["finishDateMilestone"];
                            $storeInitiating->duration    = round((strtotime($dataInitiating["finishDateMilestone"]) - strtotime($dataInitiating["startDateMilestone"])) / (60 * 60 * 24)) + 1;
                        }
                        $storeInitiating->bobot       = $dataInitiating["weightMilestone"];
                        $storeInitiating->id_pmo      = $request["id_pmo"];
                        if (DB::table('tb_pmo')->where('id', $request->id_pmo)->first()->project_type == 'supply_only') {
                            $storeInitiating->status      = 'On-Going';
                        } else {
                            $storeInitiating->status      = 'Done';
                        }
                        $storeInitiating->deliverable_document = $dataInitiating["deliverableDoc"];
                        $storeInitiating->save();

                        $getId = DB::table('gantt_tasks_pmo')->where('id_pmo',$request->id_pmo)->where('text','Initiating')->first()->id;
                        $update = GanttTaskPmo::where('text','Initiating')->where('id_pmo',$request->id_pmo)->first();
                        $update->start_date = DB::table('gantt_tasks_pmo')->where('parent',$getId)->orderby('id','asc')->limit('1')->first()->start_date;
                        $update->update();
                    }
                }
            
                if (count(json_decode($request->arrPlanning)) > 0) {
                    $arrPlanningMilestone = json_decode($request->arrPlanning,true);
                    foreach($arrPlanningMilestone as $dataPlanning){

                        // $get_project_type = DB::table('tb_pmo')->where('tb_pmo.id', $request->id_pmo)->first()->project_type;

                        // $getDeliverableDocument = DB::table('tb_pmo_define_task')->select('tb_pmo_define_task.deliverable_document', 'sub_task')->where('tb_pmo_define_task.project_type',$get_project_type)->where('sub_task', $dataPlanning["labelTask"])->first();

                        $storePlanning = new GanttTaskPmo;

                        $storePlanning->parent          = DB::table('gantt_tasks_pmo')->where('id_pmo',$request->id_pmo)->where('text','Planning')->first()->id; 
                        $storePlanning->text            = $dataPlanning["labelTask"];
                        if ($dataPlanning["startDateMilestone"] != "Invalid date" && $dataPlanning["startDateMilestone"] != "Invalid date") {
                            $storePlanning->start_date      = $dataPlanning["startDateMilestone"];
                            $storePlanning->end_date        = $dataPlanning["finishDateMilestone"];
                            $storePlanning->duration        = round((strtotime($dataPlanning["finishDateMilestone"]) - strtotime($dataPlanning["startDateMilestone"])) / (60 * 60 * 24)) + 1;
                        }
                        $storePlanning->bobot           = $dataPlanning["weightMilestone"];
                        $storePlanning->id_pmo          = $request["id_pmo"];
                        $storePlanning->status          = 'On-Going';
                        $storePlanning->deliverable_document = $dataPlanning["deliverableDoc"];
                        $storePlanning->save();

                        $getId = DB::table('gantt_tasks_pmo')->where('id_pmo',$request->id_pmo)->where('text','Planning')->first()->id;
                        $update = GanttTaskPmo::where('text','Planning')->where('id_pmo',$request->id_pmo)->first();
                        $update->start_date = DB::table('gantt_tasks_pmo')->where('parent',$getId)->orderby('id','asc')->limit('1')->first()->start_date;
                        $update->update();
                    }
                }

                if (json_decode($request->arrExecuting) !== null) {
                    $arrExecutingMilestone = json_decode($request->arrExecuting,true);
                    // return $arrExecutingMilestone;
                    // if (count($arrExecutingMilestone) > 1) {
                    //     return "lebih dari satu";
                    // }else{
                    //     return $arrExecutin
                    // foreach($arrExecutingMilestone as $data){
                    //     return $data["labelTask"];
                    // }
                    // return $arrExecutingMilestone
                    foreach($arrExecutingMilestone as $key => $dataExecuting){
                        // return $key;
                        $cek = var_dump($key, is_numeric($key));
                        if (is_numeric($key)) {
                           // if (is_array($dataExecuting)) {
                                $storeExecuting = new GanttTaskPmo;               
                                $storeExecuting->parent         = DB::table('gantt_tasks_pmo')->where('id_pmo',$request->id_pmo)->where('text','Executing')->first()->id;
                                $storeExecuting->text           = $dataExecuting["labelTask"];
                                if ($dataExecuting["startDateMilestone"] != "Invalid date" && $dataExecuting["finishDateMilestone"] != "Invalid date") {
                                    $storeExecuting->start_date     = $dataExecuting["startDateMilestone"];
                                    $storeExecuting->end_date       = $dataExecuting["finishDateMilestone"];
                                    $storeExecuting->duration       = round((strtotime($dataExecuting["finishDateMilestone"]) - strtotime($dataExecuting["startDateMilestone"])) / (60 * 60 * 24)) + 1;
                                }
                                $storeExecuting->bobot          = $dataExecuting["weightMilestone"];
                                $storeExecuting->id_pmo         = $request["id_pmo"];
                                $storeExecuting->status         = 'On-Going';
                                $storeExecuting->deliverable_document = $dataExecuting["deliverableDoc"];
                                $storeExecuting->save();
                            // }                                         

                            $getId = DB::table('gantt_tasks_pmo')->where('id_pmo',$request->id_pmo)->where('text','Executing')->first()->id;
                            $update = GanttTaskPmo::where('text','Executing')->where('id_pmo',$request->id_pmo)->first();
                            $update->start_date = DB::table('gantt_tasks_pmo')->where('parent',$getId)->orderby('id','asc')->limit('1')->first()->start_date;
                            $update->update();
                            
                        }else{
                            $storeKey = new GanttTaskPmo;
                            $storeKey->text        = $key;
                            $storeKey->id_pmo      = $request->id_pmo;
                            $storeKey->parent      = DB::table('gantt_tasks_pmo')->where('id_pmo',$request->id_pmo)->where('text','Executing')->first()->id;
                            $storeKey->duration    = 0;
                            $storeKey->status      = 'On-Going';
                            $storeKey->save();

                            foreach($arrExecutingMilestone[$key] as $dataExecutingKey){
                                $storeExecuting = new GanttTaskPmo;               
                                $storeExecuting->parent         = $storeKey->id; 
                                if ($dataExecutingKey["labelSolution"] != "") {
                                    $storeExecuting->text           = "[ ".$dataExecutingKey["labelSolution"]." ] - " . $dataExecutingKey["labelTask"];
                                }else{
                                    $storeExecuting->text           = $dataExecutingKey["labelTask"];
                                }

                                if ($dataExecutingKey["startDateMilestone"] != "Invalid date" && $dataExecutingKey["finishDateMilestone"] != "Invalid date") {
                                    $storeExecuting->start_date     = $dataExecutingKey["startDateMilestone"];
                                    $storeExecuting->end_date       = $dataExecutingKey["finishDateMilestone"];
                                    $storeExecuting->duration       = round((strtotime($dataExecutingKey["finishDateMilestone"]) - strtotime($dataExecutingKey["startDateMilestone"])) / (60 * 60 * 24)) + 1;
                                }
                                $storeExecuting->bobot          = $dataExecutingKey["weightMilestone"];
                                $storeExecuting->id_pmo         = $request["id_pmo"];
                                $storeExecuting->status         = 'On-Going';
                                $storeExecuting->deliverable_document = $dataExecutingKey["deliverableDoc"];
                                $storeExecuting->save();
                            }                

                            $getId = DB::table('gantt_tasks_pmo')->where('id_pmo',$request->id_pmo)->where('text','Executing')->first()->id;
                            $update = GanttTaskPmo::where('text','Executing')->where('id_pmo',$request->id_pmo)->first();
                            $update->start_date = DB::table('gantt_tasks_pmo')->where('parent',$getId)->orderby('id','asc')->limit('1')->first()->start_date;
                            $update->update();
                            // $getIdParentKey = DB::table('gantt_tasks_pmo')->where('id_pmo',$request->id_pmo)->where('parent',$storeKey->id)->orderBy('id')->first()->id;
                            // $updateParentKey = DB::table('gantt_tasks_pmo')->where('id_pmo',$request->id_pmo)->where('id',$storeKey->id)->orderBy('id')->first();
                            // $updateParentKey->start_date = DB::table('gantt_tasks_pmo')->where('parent',$getIdParentKey)->orderby('id','asc')->limit('1')->first()->start_date;
                            // $updateParentKey->end_date = DB::table('gantt_tasks_pmo')->where('parent',$getIdParentKey)->orderby('id','desc')->limit('1')->first()->end_date;
                            // $updateParentKey->update();
                        }
                        // $get_project_type = DB::table('tb_pmo')->where('tb_pmo.id', $request->id_pmo)->first()->project_type;

                        // $getDeliverableDocument = DB::table('tb_pmo_define_task')->select('tb_pmo_define_task.deliverable_document', 'sub_task')->where('tb_pmo_define_task.project_type',$get_project_type)->where('sub_task', $dataExecuting["labelTask"])->first();
                    }
                }

                if (count(json_decode($request->arrClosing)) > 0) {
                    $arrClosingMilestone = json_decode($request->arrClosing,true);
                    foreach($arrClosingMilestone as $dataClosing){

                        // $get_project_type = DB::table('tb_pmo')->where('tb_pmo.id', $request->id_pmo)->first()->project_type;

                        // $getDeliverableDocument = DB::table('tb_pmo_define_task')->select('tb_pmo_define_task.deliverable_document', 'sub_task')->where('tb_pmo_define_task.project_type',$get_project_type)->where('sub_task', $dataClosing["labelTask"])->first();

                        $storeClosing = new GanttTaskPmo;

                        $storeClosing->parent                   = DB::table('gantt_tasks_pmo')->where('id_pmo',$request->id_pmo)->where('text','Closing')->first()->id;  
                        $storeClosing->text                     = $dataClosing["labelTask"];
                        if ($dataClosing["startDateMilestone"] != "Invalid date" && $dataClosing["finishDateMilestone"] != "Invalid date") {
                            $storeClosing->start_date               = $dataClosing["startDateMilestone"];
                            $storeClosing->end_date                 = $dataClosing["finishDateMilestone"];
                            $storeClosing->duration                 = round((strtotime($dataClosing["finishDateMilestone"]) - strtotime($dataClosing["startDateMilestone"])) / (60 * 60 * 24)) + 1;
                        }
                        $storeClosing->bobot                    = $dataClosing["weightMilestone"];
                        $storeClosing->id_pmo                   = $request["id_pmo"];
                        $storeClosing->status                   = 'On-Going';
                        $storeClosing->deliverable_document     = $dataClosing["deliverableDoc"];
                        $storeClosing->save();

                        $getId = DB::table('gantt_tasks_pmo')->where('id_pmo',$request->id_pmo)->where('text','Closing')->first()->id;
                        $update = GanttTaskPmo::where('text','Closing')->where('id_pmo',$request->id_pmo)->first();
                        $update->start_date = DB::table('gantt_tasks_pmo')->where('parent',$getId)->orderby('id','asc')->limit('1')->first()->start_date;
                        $update->update();
                    }
                }
            }

            // if (count(json_decode($request->arrInitiating)) > 0) {
            //     GanttTaskPmo::where('id_pmo', $request->id_pmo)->delete();

            //     $arrInitiatingMilestone = json_decode($request->arrInitiating,true);
            //     foreach($arrInitiatingMilestone as $dataInitiating){
            //         // $get_project_type = DB::table('tb_pmo')->where('tb_pmo.id', $request->id_pmo)->first();

            //         // $getDeliverableDocument = DB::table('tb_pmo_define_task')->select('tb_pmo_define_task.deliverable_document', 'sub_task')->where('tb_pmo_define_task.project_type',$get_project_type->project_type)->where('sub_task', $dataInitiating["labelTask"])->first();

            //         $storeInitiating = new GanttTaskPmo;

            //         $storeInitiating->parent      = DB::table('gantt_tasks_pmo')->where('id_pmo',$request->id_pmo)->where('text','Initiating')->first()->id; 
            //         $storeInitiating->text        = $dataInitiating["labelTask"];
            //         if ($dataInitiating["startDateMilestone"] != "Invalid date" && $dataInitiating["finishDateMilestone"] != "Invalid date") {
            //             $storeInitiating->start_date  = $dataInitiating["startDateMilestone"];
            //             $storeInitiating->end_date    = $dataInitiating["finishDateMilestone"];
            //             $storeInitiating->duration    = round((strtotime($dataInitiating["finishDateMilestone"]) - strtotime($dataInitiating["startDateMilestone"])) / (60 * 60 * 24)) + 1;
            //         }
            //         $storeInitiating->bobot       = $dataInitiating["weightMilestone"];
            //         $storeInitiating->id_pmo      = $request["id_pmo"];
            //         if (DB::table('tb_pmo')->where('id', $request->id_pmo)->first()->project_type == 'supply_only') {
            //             $storeInitiating->status      = 'On-Going';
            //         } else {
            //             $storeInitiating->status      = 'Done';
            //         }
            //         $storeInitiating->deliverable_document = $dataInitiating["deliverableDoc"];
            //         $storeInitiating->save();

            //         $getId = DB::table('gantt_tasks_pmo')->where('id_pmo',$request->id_pmo)->where('text','Initiating')->first()->id;
            //         $update = GanttTaskPmo::where('text','Initiating')->where('id_pmo',$request->id_pmo)->first();
            //         $update->start_date = DB::table('gantt_tasks_pmo')->where('parent',$getId)->orderby('id','asc')->limit('1')->first()->start_date;
            //         $update->update();
            //     }
            // }
            
            // if (count(json_decode($request->arrPlanning)) > 0) {
            //     $arrPlanningMilestone = json_decode($request->arrPlanning,true);
            //     foreach($arrPlanningMilestone as $dataPlanning){

            //         // $get_project_type = DB::table('tb_pmo')->where('tb_pmo.id', $request->id_pmo)->first()->project_type;

            //         // $getDeliverableDocument = DB::table('tb_pmo_define_task')->select('tb_pmo_define_task.deliverable_document', 'sub_task')->where('tb_pmo_define_task.project_type',$get_project_type)->where('sub_task', $dataPlanning["labelTask"])->first();

            //         $storePlanning = new GanttTaskPmo;

            //         $storePlanning->parent          = DB::table('gantt_tasks_pmo')->where('id_pmo',$request->id_pmo)->where('text','Planning')->first()->id; 
            //         $storePlanning->text            = $dataPlanning["labelTask"];
            //         if ($dataPlanning["startDateMilestone"] != "Invalid date" && $dataPlanning["startDateMilestone"] != "Invalid date") {
            //             $storePlanning->start_date      = $dataPlanning["startDateMilestone"];
            //             $storePlanning->end_date        = $dataPlanning["finishDateMilestone"];
            //             $storePlanning->duration        = round((strtotime($dataPlanning["finishDateMilestone"]) - strtotime($dataPlanning["startDateMilestone"])) / (60 * 60 * 24)) + 1;
            //         }
            //         $storePlanning->bobot           = $dataPlanning["weightMilestone"];
            //         $storePlanning->id_pmo          = $request["id_pmo"];
            //         $storePlanning->status          = 'On-Going';
            //         $storePlanning->deliverable_document = $dataPlanning["deliverableDoc"];
            //         $storePlanning->save();

            //         $getId = DB::table('gantt_tasks_pmo')->where('id_pmo',$request->id_pmo)->where('text','Planning')->first()->id;
            //         $update = GanttTaskPmo::where('text','Planning')->where('id_pmo',$request->id_pmo)->first();
            //         $update->start_date = DB::table('gantt_tasks_pmo')->where('parent',$getId)->orderby('id','asc')->limit('1')->first()->start_date;
            //         $update->update();
            //     }
            // }

            // if (json_decode($request->arrExecuting) !== null) {
            //     $arrExecutingMilestone = json_decode($request->arrExecuting,true);
            //     // return $arrExecutingMilestone;
            //     // if (count($arrExecutingMilestone) > 1) {
            //     //     return "lebih dari satu";
            //     // }else{
            //     //     return $arrExecutin
            //     // foreach($arrExecutingMilestone as $data){
            //     //     return $data["labelTask"];
            //     // }
            //     // return $arrExecutingMilestone
            //     foreach($arrExecutingMilestone as $key => $dataExecuting){
            //         // return $key;
            //         $cek = var_dump($key, is_numeric($key));
            //         if (is_numeric($key)) {
            //            // if (is_array($dataExecuting)) {
            //                 $storeExecuting = new GanttTaskPmo;               
            //                 $storeExecuting->parent         = DB::table('gantt_tasks_pmo')->where('id_pmo',$request->id_pmo)->where('text','Executing')->first()->id;
            //                 $storeExecuting->text           = $dataExecuting["labelTask"];
            //                 if ($dataExecuting["startDateMilestone"] != "Invalid date" && $dataExecuting["finishDateMilestone"] != "Invalid date") {
            //                     $storeExecuting->start_date     = $dataExecuting["startDateMilestone"];
            //                     $storeExecuting->end_date       = $dataExecuting["finishDateMilestone"];
            //                     $storeExecuting->duration       = round((strtotime($dataExecuting["finishDateMilestone"]) - strtotime($dataExecuting["startDateMilestone"])) / (60 * 60 * 24)) + 1;
            //                 }
            //                 $storeExecuting->bobot          = $dataExecuting["weightMilestone"];
            //                 $storeExecuting->id_pmo         = $request["id_pmo"];
            //                 $storeExecuting->status         = 'On-Going';
            //                 $storeExecuting->deliverable_document = $dataExecuting["deliverableDoc"];
            //                 $storeExecuting->save();
            //             // }                                         

            //             $getId = DB::table('gantt_tasks_pmo')->where('id_pmo',$request->id_pmo)->where('text','Executing')->first()->id;
            //             $update = GanttTaskPmo::where('text','Executing')->where('id_pmo',$request->id_pmo)->first();
            //             $update->start_date = DB::table('gantt_tasks_pmo')->where('parent',$getId)->orderby('id','asc')->limit('1')->first()->start_date;
            //             $update->update();
                        
            //         }else{
            //             $storeKey = new GanttTaskPmo;
            //             $storeKey->text        = $key;
            //             $storeKey->id_pmo      = $request->id_pmo;
            //             $storeKey->parent      = DB::table('gantt_tasks_pmo')->where('id_pmo',$request->id_pmo)->where('text','Executing')->first()->id;
            //             $storeKey->duration    = 0;
            //             $storeKey->status      = 'On-Going';
            //             $storeKey->save();

            //             foreach($arrExecutingMilestone[$key] as $dataExecutingKey){
            //                 $storeExecuting = new GanttTaskPmo;               
            //                 $storeExecuting->parent         = $storeKey->id; 
            //                 if ($dataExecutingKey["labelSolution"] != "") {
            //                     $storeExecuting->text           = "[ ".$dataExecutingKey["labelSolution"]." ] - " . $dataExecutingKey["labelTask"];
            //                 }else{
            //                     $storeExecuting->text           = $dataExecutingKey["labelTask"];
            //                 }

            //                 if ($dataExecutingKey["startDateMilestone"] != "Invalid date" && $dataExecutingKey["finishDateMilestone"] != "Invalid date") {
            //                     $storeExecuting->start_date     = $dataExecutingKey["startDateMilestone"];
            //                     $storeExecuting->end_date       = $dataExecutingKey["finishDateMilestone"];
            //                     $storeExecuting->duration       = round((strtotime($dataExecutingKey["finishDateMilestone"]) - strtotime($dataExecutingKey["startDateMilestone"])) / (60 * 60 * 24)) + 1;
            //                 }
            //                 $storeExecuting->bobot          = $dataExecutingKey["weightMilestone"];
            //                 $storeExecuting->id_pmo         = $request["id_pmo"];
            //                 $storeExecuting->status         = 'On-Going';
            //                 $storeExecuting->deliverable_document = $dataExecutingKey["deliverableDoc"];
            //                 $storeExecuting->save();
            //             }                

            //             $getId = DB::table('gantt_tasks_pmo')->where('id_pmo',$request->id_pmo)->where('text','Executing')->first()->id;
            //             $update = GanttTaskPmo::where('text','Executing')->where('id_pmo',$request->id_pmo)->first();
            //             $update->start_date = DB::table('gantt_tasks_pmo')->where('parent',$getId)->orderby('id','asc')->limit('1')->first()->start_date;
            //             $update->update();
            //             // $getIdParentKey = DB::table('gantt_tasks_pmo')->where('id_pmo',$request->id_pmo)->where('parent',$storeKey->id)->orderBy('id')->first()->id;
            //             // $updateParentKey = DB::table('gantt_tasks_pmo')->where('id_pmo',$request->id_pmo)->where('id',$storeKey->id)->orderBy('id')->first();
            //             // $updateParentKey->start_date = DB::table('gantt_tasks_pmo')->where('parent',$getIdParentKey)->orderby('id','asc')->limit('1')->first()->start_date;
            //             // $updateParentKey->end_date = DB::table('gantt_tasks_pmo')->where('parent',$getIdParentKey)->orderby('id','desc')->limit('1')->first()->end_date;
            //             // $updateParentKey->update();
            //         }

            //         // $get_project_type = DB::table('tb_pmo')->where('tb_pmo.id', $request->id_pmo)->first()->project_type;

            //         // $getDeliverableDocument = DB::table('tb_pmo_define_task')->select('tb_pmo_define_task.deliverable_document', 'sub_task')->where('tb_pmo_define_task.project_type',$get_project_type)->where('sub_task', $dataExecuting["labelTask"])->first();

                    
            //     }
            // }

            // if (count(json_decode($request->arrClosing)) > 0) {
            //     $arrClosingMilestone = json_decode($request->arrClosing,true);
            //     foreach($arrClosingMilestone as $dataClosing){

            //         // $get_project_type = DB::table('tb_pmo')->where('tb_pmo.id', $request->id_pmo)->first()->project_type;

            //         // $getDeliverableDocument = DB::table('tb_pmo_define_task')->select('tb_pmo_define_task.deliverable_document', 'sub_task')->where('tb_pmo_define_task.project_type',$get_project_type)->where('sub_task', $dataClosing["labelTask"])->first();

            //         $storeClosing = new GanttTaskPmo;

            //         $storeClosing->parent                   = DB::table('gantt_tasks_pmo')->where('id_pmo',$request->id_pmo)->where('text','Closing')->first()->id;  
            //         $storeClosing->text                     = $dataClosing["labelTask"];
            //         if ($dataClosing["startDateMilestone"] != "Invalid date" && $dataClosing["finishDateMilestone"] != "Invalid date") {
            //             $storeClosing->start_date               = $dataClosing["startDateMilestone"];
            //             $storeClosing->end_date                 = $dataClosing["finishDateMilestone"];
            //             $storeClosing->duration                 = round((strtotime($dataClosing["finishDateMilestone"]) - strtotime($dataClosing["startDateMilestone"])) / (60 * 60 * 24)) + 1;
            //         }
            //         $storeClosing->bobot                    = $dataClosing["weightMilestone"];
            //         $storeClosing->id_pmo                   = $request["id_pmo"];
            //         $storeClosing->status                   = 'On-Going';
            //         $storeClosing->deliverable_document     = $dataClosing["deliverableDoc"];
            //         $storeClosing->save();

            //         $getId = DB::table('gantt_tasks_pmo')->where('id_pmo',$request->id_pmo)->where('text','Closing')->first()->id;
            //         $update = GanttTaskPmo::where('text','Closing')->where('id_pmo',$request->id_pmo)->first();
            //         $update->start_date = DB::table('gantt_tasks_pmo')->where('parent',$getId)->orderby('id','asc')->limit('1')->first()->start_date;
            //         $update->update();
            //     }
            // }
            
            $update_phase = PMO::where('id', $request->id_pmo)->first();
            // $update_phase->current_phase = 'Planning';
            if (DB::table('tb_pmo')->where('id', $request->id_pmo)->first()->project_type == 'supply_only') {
                $update_phase->current_phase = 'Initiating';
            } else {
                $update_phase->current_phase = 'Planning';
            }
            $update_phase->gantt_status = 'defined';
            $update_phase->save();

            // Commit the transaction
            DB::commit();
        }catch (QueryException $e) {
            // Rollback transaction if there is a database query error
            DB::rollBack();

            // Log the error or handle it
            Log::error("Database Query Error: " . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Data could not be saved due to a database error.',
            ], 500);

        } catch (Exception $e) {
            // Rollback transaction if there is a general error
            DB::rollBack();

            // Log the error or handle it
            Log::error("General Error: " . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'An unexpected error occurred. Please try again.',
            ], 500);
        }
    }

    public function storeCustomMilestone(Request $request){
        // return $request->arrInitiating;
        // return $request->arrMainMilestone;

        if (GanttTaskPmo::where('id_pmo', $request->id_pmo)->exists()) {
            GanttTaskPmo::where('id_pmo', $request->id_pmo)->delete();
            // $data = GanttTaskPmo::where('id_pmo', $request->id_pmo)->get();
            // foreach ($data as $key => $value) {
            //     $data = 
            // }
        }


        $arrMainMilestone = json_decode($request->arrMainMilestone,true);
        foreach($arrMainMilestone as $data){
            $store = new GanttTaskPmo;
            // return $data;
            $store->text        = $data;
            $store->id_pmo      = $request->id_pmo;
            $store->parent      = 0;
            $store->duration    = 0;
            $store->status      = 'Done';
            $store->save();

        }

        $arrInitiatingMilestone = json_decode($request->arrInitiating,true);
        foreach($arrInitiatingMilestone as $dataInitiating){

            $storeInitiating = new GanttTaskPmo;

            $storeInitiating->parent      = DB::table('gantt_tasks_pmo')->where('id_pmo',$request->id_pmo)->where('text','Initiating')->first()->id; 
            $storeInitiating->text        = $dataInitiating["inputTaskMilestone"];

            if ($dataInitiating["startDateMilestone"] != "Invalid date" && $dataInitiating["finishDateMilestone"] != "Invalid date") {
                $storeInitiating->start_date  = $dataInitiating["startDateMilestone"];
                $storeInitiating->end_date    = $dataInitiating["finishDateMilestone"];
                $storeInitiating->duration    = round((strtotime($dataInitiating["finishDateMilestone"]) - strtotime($dataInitiating["startDateMilestone"])) / (60 * 60 * 24)) + 1;
            }
            
            $storeInitiating->bobot       = $dataInitiating["weightMilestone"];
            $storeInitiating->id_pmo      = $request["id_pmo"];
            if (DB::table('tb_pmo')->where('id', $request->id_pmo)->first()->project_type == 'supply_only') {
                $storeInitiating->status      = 'On-Going';
            } else {
                $storeInitiating->status      = 'Done';
            }
            $storeInitiating->deliverable_document = $dataInitiating["deliverableDoc"];
            $storeInitiating->save();

            $getId = DB::table('gantt_tasks_pmo')->where('id_pmo',$request->id_pmo)->where('text','Initiating')->first()->id;
            $update = GanttTaskPmo::where('text','Initiating')->where('id_pmo',$request->id_pmo)->first();
            $update->start_date = DB::table('gantt_tasks_pmo')->where('parent',$getId)->orderby('id','asc')->limit('1')->first()->start_date;
            $update->update();

        }


        $arrPlanningMilestone = json_decode($request->arrPlanning,true);
        foreach($arrPlanningMilestone as $dataPlanning){

            $storePlanning = new GanttTaskPmo;

            $storePlanning->parent          = DB::table('gantt_tasks_pmo')->where('id_pmo',$request->id_pmo)->where('text','Planning')->first()->id; 
            $storePlanning->text            = $dataPlanning["inputTaskMilestone"];
            if ($dataPlanning["startDateMilestone"] != "Invalid date" && $dataPlanning["finishDateMilestone"] != "Invalid date") {
                $storePlanning->start_date      = $dataPlanning["startDateMilestone"];
                $storePlanning->end_date        = $dataPlanning["finishDateMilestone"];
                $storePlanning->duration        = round((strtotime($dataPlanning["finishDateMilestone"]) - strtotime($dataPlanning["startDateMilestone"])) / (60 * 60 * 24)) + 1;
            }
            
            $storePlanning->bobot           = $dataPlanning["weightMilestone"];
            $storePlanning->id_pmo          = $request["id_pmo"];
            $storePlanning->status          = 'On-Going';
            $storePlanning->deliverable_document = $dataPlanning["deliverableDoc"];
            $storePlanning->save();

            $getId = DB::table('gantt_tasks_pmo')->where('id_pmo',$request->id_pmo)->where('text','Planning')->first()->id;
            $update = GanttTaskPmo::where('text','Planning')->where('id_pmo',$request->id_pmo)->first();
            $update->start_date = DB::table('gantt_tasks_pmo')->where('parent',$getId)->orderby('id','asc')->limit('1')->first()->start_date;
            $update->update();
        }

        $arrExecutingMilestone = json_decode($request->arrExecuting,true);
        var_dump($arrExecutingMilestone);

        foreach($arrExecutingMilestone as $key => $dataExecuting){
            // return $key;
            $cek = var_dump($key, is_numeric($key));
            // return $cek;
            if (is_numeric($key)) {
               // if (is_array($dataExecuting)) {
                    $storeExecuting = new GanttTaskPmo;               
                    $storeExecuting->parent         = DB::table('gantt_tasks_pmo')->where('id_pmo',$request->id_pmo)->where('text','Executing')->first()->id;
                    $storeExecuting->text           = $dataExecuting["inputTaskMilestone"];
                    if ($dataExecuting["startDateMilestone"] != "Invalid date" && $dataExecuting["finishDateMilestone"] != "Invalid date") {
                        $storeExecuting->start_date     = $dataExecuting["startDateMilestone"];
                        $storeExecuting->end_date       = $dataExecuting["finishDateMilestone"];
                        $storeExecuting->duration       = round((strtotime($dataExecuting["finishDateMilestone"]) - strtotime($dataExecuting["startDateMilestone"])) / (60 * 60 * 24)) + 1;
                    }
                    
                    $storeExecuting->bobot          = $dataExecuting["weightMilestone"];
                    $storeExecuting->id_pmo         = $request["id_pmo"];
                    $storeExecuting->status         = 'On-Going';
                    $storeExecuting->deliverable_document = $dataExecuting["deliverableDoc"];
                    $storeExecuting->save();
                // }                                         

                $getId = DB::table('gantt_tasks_pmo')->where('id_pmo',$request->id_pmo)->where('text','Executing')->first()->id;
                $update = GanttTaskPmo::where('text','Executing')->where('id_pmo',$request->id_pmo)->first();
                $update->start_date = DB::table('gantt_tasks_pmo')->where('parent',$getId)->orderby('id','asc')->limit('1')->first()->start_date;
                $update->update();
                
            }else{
                $storeKey = new GanttTaskPmo;
                $storeKey->text        = $key;
                $storeKey->id_pmo      = $request->id_pmo;
                $storeKey->parent      = DB::table('gantt_tasks_pmo')->where('id_pmo',$request->id_pmo)->where('text','Executing')->first()->id;
                $storeKey->duration    = 0;
                $storeKey->status      = 'On-Going';
                $storeKey->save();

                foreach($dataExecuting as $dataExecutingKey){
                    $storeExecuting = new GanttTaskPmo;               
                    $storeExecuting->parent         = $storeKey->id; 
                    $storeExecuting->text           = $dataExecutingKey["inputTaskMilestone"];
                    if ($dataExecutingKey["startDateMilestone"] != "Invalid date" && $dataExecutingKey["finishDateMilestone"] != "Invalid date") {
                        $storeExecuting->start_date     = $dataExecutingKey["startDateMilestone"];
                        $storeExecuting->end_date       = $dataExecutingKey["finishDateMilestone"];
                        $storeExecuting->duration       = round((strtotime($dataExecutingKey["finishDateMilestone"]) - strtotime($dataExecutingKey["startDateMilestone"])) / (60 * 60 * 24)) + 1;
                    }
                    
                    $storeExecuting->bobot          = $dataExecutingKey["weightMilestone"];
                    $storeExecuting->id_pmo         = $request["id_pmo"];
                    $storeExecuting->status         = 'On-Going';
                    $storeExecuting->deliverable_document = $dataExecutingKey["deliverableDoc"];
                    $storeExecuting->save();
                }                

                $getId = DB::table('gantt_tasks_pmo')->where('id_pmo',$request->id_pmo)->where('text','Executing')->first()->id;
                $update = GanttTaskPmo::where('text','Executing')->where('id_pmo',$request->id_pmo)->first();
                $update->start_date = DB::table('gantt_tasks_pmo')->where('parent',$getId)->orderby('id','asc')->limit('1')->first()->start_date;
                $update->update();
                
            }
            
        }

        $arrClosingMilestone = json_decode($request->arrClosing,true);
        foreach($arrClosingMilestone as $dataClosing){

            $storeClosing = new GanttTaskPmo;

            $storeClosing->parent                   = DB::table('gantt_tasks_pmo')->where('id_pmo',$request->id_pmo)->where('text','Closing')->first()->id;  
            $storeClosing->text                     = $dataClosing["inputTaskMilestone"];
            if ($dataClosing["startDateMilestone"] != "Invalid date" && $dataClosing["finishDateMilestone"] != "Invalid date") {
                $storeClosing->start_date               = $dataClosing["startDateMilestone"];
                $storeClosing->end_date                 = $dataClosing["finishDateMilestone"];
                $storeClosing->duration                 = round((strtotime($dataClosing["finishDateMilestone"]) - strtotime($dataClosing["startDateMilestone"])) / (60 * 60 * 24)) + 1;
            }
            
            $storeClosing->bobot                    = $dataClosing["weightMilestone"];
            $storeClosing->id_pmo                   = $request["id_pmo"];
            $storeClosing->status                   = 'On-Going';
            $storeClosing->deliverable_document     = $dataClosing["deliverableDoc"];
            $storeClosing->save();

            $getId = DB::table('gantt_tasks_pmo')->where('id_pmo',$request->id_pmo)->where('text','Closing')->first()->id;
            $update = GanttTaskPmo::where('text','Closing')->where('id_pmo',$request->id_pmo)->first();
            $update->start_date = DB::table('gantt_tasks_pmo')->where('parent',$getId)->orderby('id','asc')->limit('1')->first()->start_date;
            $update->update();
        }

        $update_phase = PMO::where('id', $request->id_pmo)->first();
        // $update_phase->current_phase = 'Planning';
        if (DB::table('tb_pmo')->where('id', $request->id_pmo)->first()->project_type == 'supply_only') {
            $update_phase->current_phase = 'Initiating';
        } else {
            $update_phase->current_phase = 'Planning';
        }
        $update_phase->gantt_status = 'custom';
        $update_phase->save();

        $activity = new PMOActivity();
        $activity->id_project = $request->id_pmo;
        $activity->phase = 'Add Milestone';
        $activity->operator = Auth::User()->name;
        $activity->activity = 'Add Milestone';
        $activity->date_time = Carbon::now()->toDateTimeString();
        $activity->save();
    }

    public function storeWeeklyReport(Request $request){
        $store = new PMOProgressReport;
        $store->reporting_date      = $request->date_report_date;
        $store->overall_progress    = $request->overall_progress;
        $store->project_summary     = mb_convert_encoding($request->textareaStatusSummary, "UTF-8", "ISO-8859-1");
        if ($request->textareaNoteSummaryHealth == "undefined" || $request->textareaNoteSummaryHealth == '') {
            $store->note                = "-";
        }else{
            $store->note                = $request->textareaNoteSummaryHealth;
        }
        $store->project_indicator   = $request->cbProjectIndicator;
        $store->project_health_project      = $request->cbProject;
        $store->project_health_schedule     = $request->cbSchedule;
        $store->project_health_technical    = $request->cbTechnical;
        $store->project_health_scope        = $request->cbScope;
        $store->project_health_resource     = $request->cbResource;
        $store->project_health_partner      = $request->cbPartner;
        $store->id_project                  = $request->id_pmo;
        $store->date_time                   = Carbon::now()->toDateTimeString();
        $store->save();

        $arrDisti = json_decode($request->arrDisti,true);
        foreach($arrDisti as $arrDisti){
            $storeDisti = new PMOProgressDisti;

            $storeDisti->id_report      = $store->id;  
            $storeDisti->recipient_name = $arrDisti["recipient"];
            $storeDisti->company_name   = $arrDisti["company"];
            $storeDisti->title          = $arrDisti["title"];
            $storeDisti->email          = $arrDisti["email"];
            $storeDisti->date_time      = Carbon::now()->toDateTimeString();
            $storeDisti->save();
        }

        // $arrWeeklyRisk = json_decode($request->arrWeeklyRisk,true);
        // foreach($arrWeeklyRisk as $arrWeeklyRisk){
        //     $storeRisk = new PMORisk;

        //     $storeRisk->risk_description    = $arrWeeklyRisk["textareaDescriptionRisk"];
        //     $storeRisk->response_plan       = $arrWeeklyRisk["textareaResponseRisk"];
        //     $storeRisk->risk_owner          = $arrWeeklyRisk["inputOwnerRisk"];
        //     $storeRisk->impact              = $arrWeeklyRisk["inputRatingRisk"];
        //     $storeRisk->due_date            = $arrWeeklyRisk["due_date_risk"];
        //     $storeRisk->status              = $arrWeeklyRisk["status_risk"];

        //     $storeRisk->save();
        // }

        // $arrWeeklyIssue = json_decode($request->arrWeeklyIssue,true);
        // foreach($arrWeeklyIssue as $arrWeeklyIssue){
        //     $storeIssue = new PMOIssue;

        //     $storeIssue->id_project                  = $request->id_pmo;
        //     $storeIssue->issue_description           = $arrWeeklyIssue["textAreaIssueDesc"];
        //     $storeIssue->solution_plan               = $arrWeeklyIssue["textareaSolutionPlanIssue"];
        //     $storeIssue->owner                       = $arrWeeklyIssue["inputOwnerIssue"];
        //     $storeIssue->rating_severity             = $arrWeeklyIssue["inputRatingIssue"];
        //     $storeIssue->expected_date               = $arrWeeklyIssue["expected_date_issue"];
        //     $storeIssue->actual_date                 = $arrWeeklyIssue["actual_date_issue"];
        //     $storeIssue->status                      = $arrWeeklyIssue["status_issue"];


        //     $storeIssue->save();
        // }

        $activity = new PMOActivity();
        $activity->id_project = $request->id_pmo;
        $activity->phase = 'Create Weekly Report';
        $activity->operator = Auth::User()->name;
        $activity->activity = 'Create Weekly Report';
        $activity->date_time = Carbon::now()->toDateTimeString();
        $activity->save();

        $this->uploadPdfWeekly($request->id_pmo);
    }

    public function storeScheduleRemarksFinalReport(Request $request)
    {
        // if (PMOFinalReport::where('id_project', $request->id_pmo)->exists()) {
        //     $data = PMOFinalReport::where('id_project', $request->id_pmo)->get();
        //     foreach ($data as $key => $value) {
        //         $data = PMOFinalReport::where('id', $value->id)->delete();
        //     }
        // }
        // $get_id = PMOFinalReport::where('id_project', $request->id_pmo)->first()->id_project;

        // $update = PMOFinalReport::where('id_project',$request->id_pmo)->first();
        if (PMOFinalReport::where('id_project', $request->id_pmo)->exists()) {
            $update = PMOFinalReport::where('id_project',$request->id_pmo)->first();
            $update->schedule_summary        = $request->selectScheduleSummaryFinal;
            $update->schedule_remarks        = $request->textareaScheduleRemarks;
            $update->save();
        } else {
            $update = new PMOFinalReport();
            $update->schedule_summary        = $request->selectScheduleSummaryFinal;
            $update->schedule_remarks        = $request->textareaScheduleRemarks;
            $update->save();
        }
    }

    public function storeFinalReport(Request $request){
        // $get_id = PMOFinalReport::where('id_project', $request->id_pmo)->first()->id_project;
        if (PMOFinalReport::where('id_project', $request->id_pmo)->exists()) {
            if ($request->status == 'draft') {
                $update = PMOFinalReport::where('id_project', $request->id_pmo)->first();
                $update->schedule_summary        = $request->selectScheduleSummaryFinal;
                $update->schedule_remarks        = $request->textareaScheduleRemarks;
                $update->css                     = $request->link_feedback;
                $update->lesson_learn            = $request->textareaLessonLearn;
                $update->additional_notes        = $request->textareaAdditionalNote;
                $update->recommendation_future   = $request->textareaRecomendation;
                $update->term_payment            = $request->arrToP;
                $update->payment_date            = $request->arrPayDate;
                // $update->status                  = 'New';
                $update->update();
            }else if ($request->status == 'final') {
                $update = PMOFinalReport::where('id_project', $request->id_pmo)->first();
                $update->schedule_summary        = $request->selectScheduleSummaryFinal;
                $update->schedule_remarks        = $request->textareaScheduleRemarks;
                $update->css                     = $request->link_feedback;
                $update->lesson_learn            = $request->textareaLessonLearn;
                $update->additional_notes        = $request->textareaAdditionalNote;
                $update->recommendation_future   = $request->textareaRecomendation;
                $update->term_payment            = $request->arrToP;
                $update->payment_date            = $request->arrPayDate;
                $update->status                  = 'New';
                $update->update();

                $activity = new PMOActivity();
                $activity->id_project = $request->id_pmo;
                $activity->phase = 'Update Final Report';
                $activity->operator = Auth::User()->name;
                $activity->activity = 'Update Final Report';
                $activity->date_time = Carbon::now()->toDateTimeString();
                $activity->save();

                $data = PMO::join("tb_id_project","tb_id_project.id_project","=","tb_pmo.project_id")->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')->join('users','users.nik','=','sales_lead_register.nik')->where('tb_pmo.id',$request->id_pmo)->first();

                $dataspm = PMOProjectCharter::where('tb_pmo_project_charter.id_project',$request->id_pmo)->first();

                // if ($data->project_type == 'supply_only') {
                //     $type_project = 'Supply Only';
                // }else if ($data->project_type == 'implementation') {
                //     $type_project = 'Implementation';
                // }else{
                //     $type_project = 'Maintenance & Managed Service';
                // }

                // if ($dataspm->project_pm != null) {
                    $user_pm = $dataspm->project_pm;

                // }else{
                    $user_pc = $dataspm->project_pc;

                // }

                $datas = PMO::where('id',$request->id_pmo)->first();


                $email_user = User::join('role_user','role_user.user_id', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name', 'Project Management Office Manager')->first()->email;

                $mail = new MailPMProject(collect([
                        "image"         => 'sirkulasi_pr.png',
                        "subject_email" => 'New Final Report',
                        "subject"       => 'There is new final report,',
                        "pid"           => $data->project_id,
                        "to"            => User::join('role_user','role_user.user_id', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name', 'Project Management Office Manager')->select('users.name as name')->first()->name,
                        "name_project"  => DB::table('tb_id_project')->where('id_project',$data->project_id)->first()->name_project,
                        "project_type"  => $datas->type_project,
                        "sales_owner"   => $data->name,
                        "project_pm"    => $user_pm . "/" . $user_pc,
                        "note_reject"   => '',
                        "status"        => 'storeFinalReport',
                        "id"            => $request->id_pmo,
                        'type_project'  => $datas->project_type

                    ])
                );

                Mail::to($email_user)->send($mail);
            }
            
            // foreach ($data as $key => $value) {
            //     $data = PMOFinalReport::where('id', $value->id)->delete();
            // }
            // $store = PMOFinalReport::where('id_project',$get_id)->first();
        } else {
            $store = new PMOFinalReport();
            $store->id_project              = $request->id_pmo;
            $store->schedule_summary        = $request->selectScheduleSummaryFinal;
            $store->schedule_remarks        = $request->textareaScheduleRemarks;
            $store->css                     = $request->link_feedback;
            $store->lesson_learn            = $request->textareaLessonLearn;
            $store->additional_notes        = $request->textareaAdditionalNote;
            $store->recommendation_future   = $request->textareaRecomendation;
            $store->term_payment            = $request->arrToP;
            $store->payment_date            = $request->arrPayDate;
            $store->status                  = 'New';
            $store->save();

            $activity = new PMOActivity();
            $activity->id_project = $request->id_pmo;
            $activity->phase = 'Create Final Report';
            $activity->operator = Auth::User()->name;
            $activity->activity = 'Create Final Report';
            $activity->date_time = Carbon::now()->toDateTimeString();
            $activity->save();

            $data = PMO::join("tb_id_project","tb_id_project.id_project","=","tb_pmo.project_id")->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')->join('users','users.nik','=','sales_lead_register.nik')->where('tb_pmo.id',$request->id_pmo)->first();

            $dataspm = PMOProjectCharter::where('tb_pmo_project_charter.id_project',$request->id_pmo)->first();

            if ($data->project_type == 'supply_only') {
                $type_project = 'Supply Only';
            }else if ($data->project_type == 'implementation') {
                $project_type = 'Implementation';
            }else{
                $project_type = 'Maintenance & Managed Service';
            }

            // if ($dataspm->project_pm != null) {
                $user_pm = $dataspm->project_pm;

            // }else{
                $user_pc = $dataspm->project_pc;

            // }

            $datas = PMO::where('id',$request->id_pmo)->first();

            $email_user = User::join('role_user','role_user.user_id', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name', 'Project Management Office Manager')->first()->email;

            $mail = new MailPMProject(collect([
                    "image"         => 'sirkulasi_pr.png',
                    "subject_email" => 'New Final Report',
                    "subject"       => 'There is new final report,',
                    "pid"           => $data->project_id,
                    "to"            => User::join('role_user','role_user.user_id', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')->where('roles.name', 'Project Management Office Manager')->select('users.name as name')->first()->name,
                    "name_project"  => DB::table('tb_id_project')->where('id_project',$data->project_id)->first()->name_project,
                    "project_type"  => $datas->type_project,
                    "sales_owner"   => $data->name,
                    "project_pm"    => $user_pm . "/" . $user_pc,
                    "note_reject"   => '',
                    "status"        => 'storeFinalReport',
                    "id"            => $request->id_pmo,
                    'type_project'  => $datas->project_type

                ])
            );

            Mail::to($email_user)->send($mail);
        }


        // $activity = new PMOActivity();
        // $activity->id_project = $request->id_pmo;
        // $activity->phase = 'Create Final Report';
        // $activity->operator = Auth::User()->name;
        // $activity->activity = 'Create Final Report';
        // $activity->date_time = Carbon::now()->toDateTimeString();
        // $activity->save();

        
        
        // $request->arrMilestoneFinal;
        // $request->arrChecklist;
    }

    public function storeApproveFinalReport(Request $request){
        $update = PMOFinalReport::where('id_project',$request->id_pmo)->first();
        $update->status = 'Approve';
        $update->update();

        $activity = new PMOActivity();
        $activity->id_project = $request->id_pmo;
        $activity->phase = 'Approve Final Report';
        $activity->operator = Auth::User()->name;
        $activity->activity = 'Approve Final Report';
        $activity->date_time = Carbon::now()->toDateTimeString();
        $activity->save();

        $data = PMOFinalReport::where('id_project',$request->id_pmo)->first();

        if ($data->project_type == 'supply_only') {
            $project_type = 'Supply Only';
        }else if ($data->project_type == 'implementation') {
            $project_type = 'Implementation';
        }else{
            $project_type = 'Maintenance & Managed Service';
        }

        if ($data->project_pm != '-') {
            $user = $data->project_pm;
            $email_user = DB::table('users')->where('name',$data->project_pm)->first()->email;
        }else{
            $user = $data->project_pc;
            $email_user = DB::table('users')->where('name',$data->project_pc)->first()->email;
        }

        $user_pm = $data->project_pm;
        $user_pc = $data->project_pc;

        $datas = PMO::where('id',$request->id_pmo)->first();

        $mail = new MailPMProject(collect([
                "image"         => 'final_report_approved.png',
                "subject_email" => 'Approve Final Report',
                "subject"       => 'Final Report has been approved,',
                "pid"           => $datas->project_id,
                "to"            => $user,
                "name_project"  => DB::table('tb_id_project')->where('id_project',$datas->project_id)->first()->name_project,
                "project_type"  => $datas->type_project,
                "sales_owner"   => $data->owner,
                "project_pm"    => $user_pm . "/" . $user_pc,
                "note_reject"   => '',
                "status"        => 'storeApproveFinalReport',
                "id"            => $request->id_pmo,
                'type_project'  => $datas->project_type
            ])
        );
        
        Mail::to($email_user)->send($mail);
        $this->uploadPdfFinalReport($request->id_pmo);
    }

    public function getFinalReportById(Request $request){
        return PMOFinalReport::where('id_project',$request->id_pmo)->get();
    }

    public function updateStatusTask(Request $request)
    {   
        $get_id = GanttTaskPmo::where('id', $request->id_task)->first()->parent;
        $get_parent = GanttTaskPmo::where('id',$get_id)->first()->text;

        $update = GanttTaskPmo::where('id', $request->id_task)->first();
        $update->status = 'Done';
        // $update->end_date = date('Y-m-d');
        // $update->end_date = $update->end_date;
        $update->save();

        // $isKickOff = GanttTaskPmo::where('id', $request->id_task)->first()->text;
        // if (str_contains($isKickOff, 'Kick of meeting')) {
            $activity = new PMOActivity();
            $activity->id_project = $request->id_pmo;
            $activity->phase = $update->text;
            $activity->operator = Auth::User()->name;
            $activity->activity =  'Completed Task '.$update->text;
            $activity->date_time = Carbon::now()->toDateTimeString();
            $activity->save();
        // }

        $update_phase = PMO::where('id', $request->id_pmo)->first();
        if(GanttTaskPmo::where('id_pmo', $request->id_pmo)->where('status','On-Going')->exists()){
            $update_phase->current_phase = $get_parent;
        } else {
            $update_phase->current_phase = 'Done';
        }
        $update_phase->save();

        if ($update->text == 'Submit Final Project Closing Report' && $update_phase->project_type == 'implementation') {
            if (DB::table('tb_pmo')->select('current_phase')->where('project_id',$update_phase->project_id)->where('project_type','maintenance')->exists()) {
                $id = DB::table('tb_pmo')->select('id')->where('project_id',$update_phase->project_id)->where('project_type','maintenance')->first()->id;
                $update_phase = PMO::where('id', $id)->first();
                $update_phase->current_phase = 'New';
                $update_phase->save();
            }
        }
    }

    public function getProgressBar(Request $request)
    {
        $data = GanttTaskPmo::where('id_pmo', $request->id_pmo)->where('status', 'Done')->sum('bobot');
        if (GanttTaskPmo::where('id_pmo', $request->id_pmo)->where('status', 'Done')->where('parent','!=',0)->limit('1')->orderBy('id','desc')->first()  !== null) {
            $dataLatestMilestone = GanttTaskPmo::where('id_pmo', $request->id_pmo)->where('status', 'Done')->limit('1')->orderBy('id','desc')->first()->parent;
            $isUpdateCurrentMilestone = GanttTaskPmo::where('parent', $dataLatestMilestone)->where('status', 'Done')->count() > 0 ? "True" : "False";

            if (isset($dataLatestMilestone)) {
                $dataMainLatestMilestone = GanttTaskPmo::where('id', $dataLatestMilestone)->first()->text;
                if ($dataMainLatestMilestone == "Initiating") {
                    $dataMainLatestMilestone = $dataMainLatestMilestone;
                }else if ($dataMainLatestMilestone == "Planning") {
                    $dataMainLatestMilestone = $dataMainLatestMilestone;
                }else if ($dataMainLatestMilestone == "Executing") {
                    $dataMainLatestMilestone = $dataMainLatestMilestone;
                }else if ($dataMainLatestMilestone == 'Closing') {
                    $dataMainLatestMilestone = $dataMainLatestMilestone;
                }else{
                    $dataMainLatestMilestone = "Executing";
                }
            } else {
                $dataMainLatestMilestone = [];
            }
        }else{
            $isUpdateCurrentMilestone = 'False';
            $dataMainLatestMilestone = [];
        }

        return array("progress"=>collect(["bobot"=>$data,"lates_progress_milestone"=>$dataMainLatestMilestone,"is_update_current_milestone"=>$isUpdateCurrentMilestone]));
    }

    public function storeDocument(Request $request)
    {
        $directory = "PMO/";
        $get_parent_drive = PMO::where('id', $request->id_pmo)->first();
        // if (DB::table('tb_pmo')->select('parent_id_drive')->where('project_id',$get_parent_drive->project_id)->where('project_type','implementation')->exists()) {
        //     $parent = DB::table('tb_pmo')->select('parent_id_drive')->where('project_id',$get_parent_drive->project_id)->where('project_type','implementation')->first()->parent_id_drive;
        //     $parentID = [];
        //     $parent_id = explode('"', $parent)[1];
        //     return array_push($parentID,$parent_id);
        // } else {
        //     return 'false';
        // }

        $dataAll = json_decode($request->arrInputDoc,true);
        // return $dataAll;
        foreach ($dataAll as $key => $data) {
            // return $data;

            $allowedfileExtension   = ['pdf', 'PDF'];
            $file                   = $request->file('inputDoc')[$key];
            $fileName               = $file->getClientOriginalName();
            $strfileName            = explode('.', $fileName);
            $lastElement            = end($strfileName);
            $nameDoc                = $fileName;
            $extension              = $file->getClientOriginalExtension();
            $check                  = in_array($extension,$allowedfileExtension);

            $tambah = new PMODocument();
            if ($check) {
                $this->uploadToLocal($request->file('inputDoc')[$key],$directory,$nameDoc);
                $tambah->document_name             = $data['nameDoc'];
            } else {
                return redirect()->back()->with('alert','Oops! Only pdf');
            }

            if(isset($fileName)){
                $pdf_url = urldecode(url("PMO/" . $nameDoc));
                $pdf_name = $nameDoc;
            } else {
                $pdf_url = 'http://test-drive.sinergy.co.id:8000/Lampiran.pdf';
                $pdf_name = 'pdf_lampiran';
            }

            if ($get_parent_drive->project_type == 'supply_only') {
                $parentID = $this->googleDriveMakeFolder($get_parent_drive->project_id);
                $update_parent = PMO::where('id', $request->id_pmo)->first();
                $update_parent->parent_id_drive = $parentID;
                $update_parent->save();
            } elseif (DB::table('tb_pmo')->select('parent_id_drive')->where('project_id',$get_parent_drive->project_id)->where('project_type','implementation')->exists()) {
                $update_parent = PMO::where('id', $request->id_pmo)->first();
                $parent = DB::table('tb_pmo')->select('parent_id_drive')->where('project_id',$get_parent_drive->project_id)->where('project_type','implementation')->first()->parent_id_drive;
                $parentID = [];
                $parent_id = explode('"', $parent)[1];
                array_push($parentID,$parent_id);
                $update_parent->parent_id_drive = $parentID;
                $update_parent->save();
            } else {
                $parentID = [];
                $parent_id = explode('"', $get_parent_drive->parent_id_drive)[1];
                array_push($parentID,$parent_id);
            }

            $tambah->document_location         = "PMO/" . $pdf_name;
            $tambah->link_drive = $this->googleDriveUploadCustom($pdf_name,$directory . $pdf_name,$parentID);
            $tambah->save();

            $tambah_doc = new PMODocumentProject();
            $tambah_doc->id_project = $request->id_pmo;
            $tambah_doc->id_document = $tambah->id;
            $tambah_doc->sub_task = $request->sub_task;
            $tambah_doc->date_time = Carbon::now()->toDateTimeString();
            $tambah_doc->save();

            $update = GanttTaskPmo::where('id', $request->sub_task)->first();
            $update->deliverable_document = 'Done';
            $update->update();
        }

    }

    public function getProjectDocument(Request $request)
    {
        $doc = GanttTaskPmo::where('id_pmo', $request->id_pmo)->select('text', 'deliverable_document')->where('deliverable_document', '!=', 'false')->get();
        return array("data"=>$doc);
    }

    public function sendMailCss(Request $request)
    {
        $this->sendEmail($request->bodyOpenMail,$request->emailOpenSubject,$request->emailOpenTo,$request->emailOpenCc);

        $store_activity = new PMOActivity();
        $store_activity->id_project = $request['id_pmo'];
        $store_activity->phase = 'Send CSS';
        $store_activity->operator = Auth::User()->name;
        $store_activity->activity = 'CSS has been sent';
        $store_activity->date_time = Carbon::now()->toDateTimeString();
        $store_activity->save();
    }

    public function sendEmail($body, $subject, $to, $cc){
        Mail::html($body, function ($message) use ($to, $cc, $subject) {
            $message
                ->to(explode(";", $to))
                ->subject($subject);

            if($cc != ""){
                $message->cc(explode(";", $cc));
            }
        });
    }

    public function uploadPdfPC($id_pmo)
    {
        $client = $this->getClient();
        $service = new Google_Service_Drive($client);
        $directory = '';

        $data = DB::table('tb_pmo')->join('tb_pmo_project_charter', 'tb_pmo_project_charter.id_project', '=', 'tb_pmo.id')->select('parent_id_drive','project_id')->where('tb_pmo_project_charter.id_project', $id_pmo)->first();

        $parent_id = explode('"', $data->parent_id_drive)[1];

        // return $parent_id . '-' . $data->parent_id_drive;
        // if ($approver == '') {
        //     $fileName =  'Project Charter' .$approver. '.pdf';
        // } else {
        //     $fileName =  'Project Charter ' .$approver. '.pdf';    
        // }

        $fileName =  'Project Charter.pdf';
        
        $nameFileFix = str_replace(' ', '_', $fileName);

        if(isset($fileName)){
            $pdf_url = urldecode(url("/PMO/downloadProjectCharterPdf?id_pmo=" . $id_pmo));
            $pdf_name = $nameFileFix;
        } else {
            $pdf_url = 'http://test-drive.sinergy.co.id:8000/Lampiran.pdf';
            $pdf_name = 'pdf_lampiran';
        }

        $parent = [];
        array_push($parent,$parent_id);

        $file = new Google_Service_Drive_DriveFile();
        $file->setName($pdf_name);
        $file->setParents($parent);

        $result = $service->files->create(
            $file, 
            array(
                'data' => file_get_contents($pdf_url, false, stream_context_create(["ssl" => ["verify_peer"=>false, "verify_peer_name"=>false],"http" => ['protocol_version'=>'1.1']])),
                'mimeType' => 'application/octet-stream',
                'uploadType' => 'multipart',
                'supportsAllDrives' => true
            )
        );

        $optParams = array(
            'fields' => 'files(webViewLink)',
            'q' => 'mimeType="application/pdf" and "' . explode('"',$data->parent_id_drive)[1] . '" in parents',
            'supportsAllDrives' => true,
            'includeItemsFromAllDrives' => true
        );

        $link = $service->files->listFiles($optParams)->getFiles()[0]->getWebViewLink();

        $tambah = new PMODocument();
        $tambah->document_location         = "PMO/" . $pdf_name;
        $tambah->document_name = 'Project Charter';
        $tambah->link_drive = $link;
        $tambah->save();

        $tambah_doc = new PMODocumentProject();
        $tambah_doc->id_project = $id_pmo;
        $tambah_doc->id_document = $tambah->id;
        $tambah_doc->sub_task = '';
        $tambah_doc->date_time = Carbon::now()->toDateTimeString();
        $tambah_doc->save();
    }

    public function uploadPdfWeekly($id_pmo)
    {
        $client = $this->getClient();
        $service = new Google_Service_Drive($client);
        $directory = '';

        $data = DB::table('tb_pmo')->join('tb_pmo_progress_report','tb_pmo_progress_report.id_project','tb_pmo.id')->select('parent_id_drive','project_id')->where('tb_pmo.id', $id_pmo)->orderby('tb_pmo_progress_report.id','desc')->first();
        $count_periode = DB::table('tb_pmo_progress_report')->where('tb_pmo_progress_report.id_project',$id_pmo)->count();
        // return $count_periode;

        $projectType = DB::table('tb_pmo')->where('id', $id_pmo)->value('project_type');
        if (($projectType == 'Implementation + Maintenance & Managed Service' && $data->project_type == 'maintenance') || $data->parent_id_drive == null) {
            $parentDriveID = $this->googleDriveMakeFolder($data->project_id);

            DB::table('tb_pmo')
                ->where('id', $id_pmo)
                ->update(['parent_id_drive' => $parentDriveID]);

            $data = DB::table('tb_pmo')->join('tb_pmo_progress_report','tb_pmo_progress_report.id_project','tb_pmo.id')->select('parent_id_drive','project_id')->where('tb_pmo.id', $id_pmo)->orderby('tb_pmo_progress_report.id','desc')->first();
        }

        $parent_id = explode('"', $data->parent_id_drive)[1];
        // if (!empty($data)) {
            // return $fileName = 'Project Progress Report Periode 1.pdf';
        // } else {
            // return $fileName =  'Project Progress Report Periode ' . $count_periode . '.pdf';
        $fileName =  'Project Progress Report Periode ' . $count_periode . '.pdf';
        // }
        
        $nameFileFix = str_replace(' ', '_', $fileName);

        if(isset($fileName)){
            $pdf_url = urldecode(url("/PMO/downloadProgressMeetingPdf?id_pmo=" . $id_pmo));
            $pdf_name = $nameFileFix;
        } else {
            $pdf_url = 'http://test-drive.sinergy.co.id:8000/Lampiran.pdf';
            $pdf_name = 'pdf_lampiran';
        }

        $parent = [];
        array_push($parent,$parent_id);

        $file = new Google_Service_Drive_DriveFile();
        $file->setName($pdf_name);
        $file->setParents($parent);

        $result = $service->files->create(
            $file, 
            array(
                'data' => file_get_contents($pdf_url, false, stream_context_create(["ssl" => ["verify_peer"=>false, "verify_peer_name"=>false],"http" => ['protocol_version'=>'1.1']])),
                'mimeType' => 'application/octet-stream',
                'uploadType' => 'multipart',
                'supportsAllDrives' => true
            )
        );

        $optParams = array(
            'fields' => 'files(webViewLink)',
            'q' => 'mimeType="application/pdf" and "' . explode('"',$data->parent_id_drive)[1] . '" in parents',
            'supportsAllDrives' => true,
            'includeItemsFromAllDrives' => true
        );

        $link = $service->files->listFiles($optParams)->getFiles()[0]->getWebViewLink();

        $tambah = new PMODocument();
        $tambah->document_location         = "PMO/" . $pdf_name;
        $tambah->document_name = 'Project Progress Report Periode ' . $count_periode;
        $tambah->link_drive = $link;
        $tambah->save();

         $get_current_task = GanttTaskPmo::select('text','id')->where('id_pmo',$id_pmo)->where('status', 'On-Going')->orderBy('baseline_start','asc')->first();
        // return $get_current_task->id;

        $tambah_doc = new PMODocumentProject();
        $tambah_doc->id_project = $id_pmo;
        $tambah_doc->id_document = $tambah->id;
        $tambah_doc->sub_task = $get_current_task->id;
        $tambah_doc->date_time = Carbon::now()->toDateTimeString();
        $tambah_doc->save();
    }

    public function uploadPdfFinalReport($id_pmo)
    {
        $client = $this->getClient();
        $service = new Google_Service_Drive($client);
        $directory = '';

        $data = DB::table('tb_pmo')->join('tb_pmo_project_charter', 'tb_pmo_project_charter.id_project', '=', 'tb_pmo.id')->select('parent_id_drive','project_id')->where('tb_pmo_project_charter.id_project', $id_pmo)->first();

        $parent_id = explode('"', $data->parent_id_drive)[1];
        // if ($approver == '') {
        //     $fileName =  'Final Project Report' .$approver. '.pdf';
        // } else {
        //     $fileName =  'Final Project Report ' .$approver. '.pdf';    
        // }
        $fileName =  'Final Project Report.pdf';
        
        $nameFileFix = str_replace(' ', '_', $fileName);

        if(isset($fileName)){
            $pdf_url = urldecode(url("/PMO/downloadFinalProjectPdf?id_pmo=" . $id_pmo));
            $pdf_name = $nameFileFix;
        } else {
            $pdf_url = 'http://test-drive.sinergy.co.id:8000/Lampiran.pdf';
            $pdf_name = 'pdf_lampiran';
        }

        $parent = [];
        array_push($parent,$parent_id);

        $file = new Google_Service_Drive_DriveFile();
        $file->setName($pdf_name);
        $file->setParents($parent);

        $result = $service->files->create(
            $file, 
            array(
                'data' => file_get_contents($pdf_url, false, stream_context_create(["ssl" => ["verify_peer"=>false, "verify_peer_name"=>false],"http" => ['protocol_version'=>'1.1']])),
                'mimeType' => 'application/octet-stream',
                'uploadType' => 'multipart',
                'supportsAllDrives' => true
            )
        );

        $optParams = array(
            'fields' => 'files(webViewLink)',
            'q' => 'mimeType="application/pdf" and "' . explode('"',$data->parent_id_drive)[1] . '" in parents',
            'supportsAllDrives' => true,
            'includeItemsFromAllDrives' => true
        );

        $link = $service->files->listFiles($optParams)->getFiles()[0]->getWebViewLink();

        $tambah = new PMODocument();
        $tambah->document_location         = "PMO/" . $pdf_name;
        $tambah->document_name = 'Final Report';
        $tambah->link_drive = $link;
        $tambah->save();

        $get_current_task = GanttTaskPmo::select('text','id')->where('id_pmo',$id_pmo)->where('status', 'On-Going')->orderBy('baseline_start','desc')->first();
        // return $get_current_task->id;

        $tambah_doc = new PMODocumentProject();
        $tambah_doc->id_project = $id_pmo;
        $tambah_doc->id_document = $tambah->id;
        $tambah_doc->sub_task = $get_current_task->id;
        $tambah_doc->date_time = Carbon::now()->toDateTimeString();
        $tambah_doc->save();
    }

    public function getCountDashboard(Request $request)
    {
        $countInitiating = DB::table('tb_pmo')->whereRaw("(`current_phase` =  'New' OR `current_phase` = 'Waiting')")->where('project_id','like','%'.$request->year)->count();
        $countPlanning = DB::table('tb_pmo')->where('current_phase','Planning')->where('project_id','like','%'.$request->year)->count();
        $countExecuting = DB::table('tb_pmo')->where('current_phase','Executing')->where('project_id','like','%'.$request->year)->count();
        $getParent = GanttTaskPmo::select('id')->where('text','Closing')->groupBy('id_pmo');

        $countClosing = GanttTaskPmo::join('tb_pmo','tb_pmo.id','gantt_tasks_pmo.id_pmo')->whereIn('parent',$getParent)->where('project_id','like','%'.$request->year)->get()->where('status','On-Going')->groupBy('id_pmo')->count();
        $countClosing = DB::table('tb_pmo')->where('current_phase','Closing')->where('project_id','like','%'.$request->year)->count();


        $queryDone =  GanttTaskPmo::join('tb_pmo','tb_pmo.id','gantt_tasks_pmo.id_pmo')->select('id_pmo',DB::raw('(CASE WHEN status = "Done" THEN 1 ELSE 0 END) AS counts'))->whereIn('parent',$getParent)->orderby('gantt_tasks_pmo.id','desc')->where('project_id','like','%'.$request->year)->get()->groupBy('id_pmo');

        $countDone = 0;
        $countDones = 0;
        $countDonesDone = 0;
        foreach($queryDone as $key => $data){
            // return $key;
            foreach($queryDone[$key] as $datas){
                // var_dump($datas);
                if ($datas['counts'] == 0) {
                    $countDones = $datas['counts'];
                    // var_dump($countDones);
                    $queryDone[$key]["total"] = 0;
                }else{
                    // return "sini";
                    $countDonesDone = $datas['counts'];
                    // var_dump($countDonesDone);
                    $queryDone[$key]["total"] = 1;
                }
            } 
        } 

        // $sum = 0;
        // foreach($queryDone as $key => $data){
        //    $sum += $queryDone[$key]["total"]; 
        // }   

        $countDone = DB::table('tb_pmo')->where('current_phase','Done')->where('project_id','like','%'.$request->year)->count();     

        $countOnGoing = DB::table('tb_pmo')->whereRaw("(`current_phase` =  'New' OR `current_phase` = 'Waiting' OR `current_phase` = 'Planning' OR `current_phase` = 'Executing' OR `current_phase` = 'Closing')")->where('project_id','like','%'.$request->year)->count();

        return collect([
            "countInitiating" => $countInitiating,
            "countPlanning" => $countPlanning,
            "countExecuting" => $countExecuting,
            "countClosing" => $countClosing,
            "countDone" => $countDone,
            "countOnGoing" => $countOnGoing
        ]);
    }

    public function getTotalProjectType(Request $request)
    {
//        $data = DB::table('tb_pmo')->select(
//                DB::raw('COUNT(project_type) as count'),
//                // 'project_type',
//                DB::raw('(CASE WHEN project_type = "implementation" THEN "Implementation" WHEN project_type = "maintenance" THEN "Maintenance & Managed Service" WHEN project_type = "supply_only" THEN "Supply Only" ELSE project_type END) AS project_type'),
//            )
//            ->where('project_id','like','%'.$request->year)
//            ->groupBy('project_type')->get();

        $subquery = DB::table('tb_pmo')
            ->selectRaw('DISTINCT project_id')
            ->where('project_id', 'like', '%' . $request->year);

        $processedProjects = DB::table('tb_pmo AS pmo')
            ->joinSub($subquery, 'subquery', function ($join) {
                $join->on('subquery.project_id', '=', 'pmo.project_id');
            })
            ->select(
                'pmo.project_id',
                DB::raw("
            CASE 
                WHEN GROUP_CONCAT(DISTINCT pmo.project_type ORDER BY pmo.project_type SEPARATOR ' + ') = 'implementation + maintenance' 
                THEN 'Implementation + Maintenance & Managed Service'
                WHEN GROUP_CONCAT(DISTINCT pmo.project_type ORDER BY pmo.project_type) = 'implementation' 
                THEN 'Implementation'
                WHEN GROUP_CONCAT(DISTINCT pmo.project_type ORDER BY pmo.project_type) = 'maintenance' 
                THEN 'Maintenance & Managed Service'
                WHEN GROUP_CONCAT(DISTINCT pmo.project_type ORDER BY pmo.project_type) = 'supply_only' 
                THEN 'Supply Only'
                ELSE GROUP_CONCAT(DISTINCT pmo.project_type ORDER BY pmo.project_type SEPARATOR ' + ')
            END AS project_type
        ")
            )
            ->groupBy('pmo.project_id');

        $countedProjects = DB::table(DB::raw("({$processedProjects->toSql()}) as processed_projects"))
            ->mergeBindings($processedProjects)
            ->select(
                'processed_projects.project_type',
                DB::raw('COUNT(DISTINCT processed_projects.project_id) AS total_projects')
            )
            ->groupBy('processed_projects.project_type');

        $categories = collect([
            ['project_type' => 'Implementation'],
            ['project_type' => 'Maintenance & Managed Service'],
            ['project_type' => 'Supply Only'],
            ['project_type' => 'Implementation + Maintenance & Managed Service'],
        ]);

        $data = DB::table(DB::raw("(SELECT * FROM (SELECT 'Implementation' AS project_type UNION ALL
            SELECT 'Maintenance & Managed Service' UNION ALL
            SELECT 'Supply Only' UNION ALL
            SELECT 'Implementation + Maintenance & Managed Service') AS categories) AS categories"))
            ->leftJoinSub($countedProjects, 'counted_projects', function ($join) {
                $join->on('categories.project_type', '=', 'counted_projects.project_type');
            })
            ->select(
                'categories.project_type',
                DB::raw('COALESCE(counted_projects.total_projects, 0) AS count')
            )
            ->get();

        return array("data" => $data);
    }

    public function getMarketSegment(Request $request)
    {
        $data = DB::table('tb_pmo_project_charter')->join('tb_pmo','tb_pmo.id','tb_pmo_project_charter.id_project')->select(
                DB::raw('COUNT(market_segment) as count'),
                'market_segment'
            )
            ->where('market_segment', '!=' ,NULL)
            ->where('project_id','like','%'.$request->year)
            ->groupBy('market_segment');

        return array("data" => $data->get());
    }

    public function getNominalByPeople(Request $request)
    {
        $data = DB::table('tb_pmo')->join('tb_id_project', 'tb_id_project.id_project', 'tb_pmo.project_id')->join('tb_pmo_assign','tb_pmo_assign.id_project','tb_pmo.id')->join('users','users.nik', 'tb_pmo_assign.nik')
                ->select(
                    DB::raw('SUM(amount_idr) as amount'),
                    'users.name'
                )
                ->where('project_id','like','%'.$request->year)
                ->groupBy('name');

        return array("data" => $data->get());
    }

    public function getProjectValue(Request $request)
    {

        // return $data = DB::table('tb_pmo')->join('tb_id_project','tb_pmo.project_id','tb_id_project.id_project')->select('id_project', 'amount_idr')->get();

//        $data = DB::table('tb_pmo')->join('tb_id_project','tb_pmo.project_id','tb_id_project.id_project')->select(
//                // DB::raw('SUM(amount_idr) as amount_idr'),
//                DB::raw('COUNT(IF(`tb_id_project`.`amount_idr` <= "1000000000",1,NULL)) AS "dibawah_1M"'),
//                DB::raw('COUNT(IF(`tb_id_project`.`amount_idr` > "1000000000" && `tb_id_project`.`amount_idr` <= 5000000000,1,NULL)) AS "one_until_five_M"'),
//                DB::raw('COUNT(IF(`tb_id_project`.`amount_idr` > "5000000000" && `tb_id_project`.`amount_idr` <= 10000000000,1,NULL)) AS "five_until_ten_M"'),
//                DB::raw('COUNT(IF(`tb_id_project`.`amount_idr` > "10000000000",1,NULL)) AS "diatas_10M"')
//            )
//            ->where('project_id','like','%'.$request->year)
//            ->first();

        $data = DB::table('tb_pmo')
            ->join('tb_id_project', 'tb_pmo.project_id', '=', 'tb_id_project.id_project')
            ->select(
                DB::raw('COUNT(DISTINCT CASE 
            WHEN tb_id_project.amount_idr <= 1000000000 THEN tb_pmo.project_id 
        END) AS dibawah_1M'),

                DB::raw('COUNT(DISTINCT CASE 
            WHEN tb_id_project.amount_idr > 1000000000 
            AND tb_id_project.amount_idr <= 5000000000 THEN tb_pmo.project_id 
        END) AS one_until_five_M'),

                DB::raw('COUNT(DISTINCT CASE 
            WHEN tb_id_project.amount_idr > 5000000000 
            AND tb_id_project.amount_idr <= 10000000000 THEN tb_pmo.project_id 
        END) AS five_until_ten_M'),

                DB::raw('COUNT(DISTINCT CASE 
            WHEN tb_id_project.amount_idr > 10000000000 THEN tb_pmo.project_id 
        END) AS diatas_10M')
            )
            ->where('tb_pmo.project_id', 'like', '%' . $request->year)
            ->first();

            // ->whereYear('project_id', date('Y'))
            // ->groupBy('amount_idr');

        // return array("data" => $data);
        return array("data"=>collect([["count"=>$data->dibawah_1M,"label"=>"Dibawah 1M"],["count"=>$data->one_until_five_M,"label"=>"1-5M"],["count"=>$data->five_until_ten_M,"label"=>"5-10M"],["count"=>$data->diatas_10M,"label"=>"Diatas 10M"]]));
    }

    public function getProjectStatus(Request $request)
    {
        $dataOnGoing = DB::table('tb_pmo')->select(
                DB::raw('COUNT(IF(`tb_pmo`.`current_phase` = "New" OR `tb_pmo`.`current_phase` = "Initiating" OR `tb_pmo`.`current_phase` = "Planning" OR `tb_pmo`.`current_phase` = "Executing",1,NULL)) AS "on_going"'),
                // DB::raw('COUNT(IF(`tb_pmo`.`current_phase` = "Closing",1,NULL)) AS "finished"')
            )
            ->where('project_id','like','%'.$request->year)
            ->first();

            // ->whereYear('project_id', date('Y'))
            // ->groupBy('amount_idr');
        $getParent = GanttTaskPmo::select('id')->where('text','Closing')->groupBy('id_pmo');
        $queryDone =  GanttTaskPmo::join('tb_pmo','tb_pmo.id','gantt_tasks_pmo.id_pmo')->select('id_pmo',DB::raw('(CASE WHEN status = "Done" THEN 1 ELSE 0 END) AS counts'))->whereIn('parent',$getParent)->orderby('gantt_tasks_pmo.id','desc')->where('project_id','like','%'.$request->year)->get()->groupBy('id_pmo');

        $countDone = 0;
        $countDones = 0;
        $countDonesDone = 0;
        foreach($queryDone as $key => $data){
            // return $key;
            foreach($queryDone[$key] as $datas){
                // var_dump($datas);
                if ($datas['counts'] == 0) {
                    $countDones = $datas['counts'];
                    // var_dump($countDones);
                    $queryDone[$key]["total"] = 0;
                }else{
                    // return "sini";
                    $countDonesDone = $datas['counts'];
                    // var_dump($countDonesDone);
                    $queryDone[$key]["total"] = 1;
                }
            } 
        } 

        $sum = 0;
        foreach($queryDone as $key => $data){
           $sum += $queryDone[$key]["total"]; 
        }        

        // return array("data" => $data->get());
        return array("data"=>collect([["count"=>$dataOnGoing->on_going,"label"=>"On-Going"],["count"=>$sum,"label"=>"Finished"]]));
    }

    public function getProjectPhase(Request $request)
    {
        $dataAll = DB::table('tb_pmo')->select(
                DB::raw('COUNT(IF(`tb_pmo`.`current_phase` = "Initiating",1,NULL)) AS "initiating"'),
                DB::raw('COUNT(IF(`tb_pmo`.`current_phase` = "Planning" ,1,NULL)) AS "planning"'),
                DB::raw('COUNT(IF(`tb_pmo`.`current_phase` = "Executing",1,NULL)) AS "executing"')
                // DB::raw('COUNT(IF(`tb_pmo`.`current_phase` = "Closing",1,NULL)) AS "closing"')
            )->where('project_id','like','%'.$request->year)->first();
            // ->whereYear('project_id', date('Y'))
            // ->groupBy('amount_idr');

        $getParent = GanttTaskPmo::select('id')->where('text','Closing')->groupBy('id_pmo');

        $countClosing = GanttTaskPmo::join('tb_pmo','tb_pmo.id','gantt_tasks_pmo.id_pmo')->whereIn('parent',$getParent)->where('project_id','like','%'.$request->year)->get()->where('status','On-Going')->groupBy('id_pmo')->count();

        $queryDone =  GanttTaskPmo::join('tb_pmo','tb_pmo.id','gantt_tasks_pmo.id_pmo')->select('id_pmo',DB::raw('(CASE WHEN status = "Done" THEN 1 ELSE 0 END) AS counts'))->whereIn('parent',$getParent)->orderby('gantt_tasks_pmo.id','desc')->where('project_id','like','%'.$request->year)->get()->groupBy('id_pmo');

        $countDone = 0;
        $countDones = 0;
        $countDonesDone = 0;
        foreach($queryDone as $key => $data){
            // return $key;
            foreach($queryDone[$key] as $datas){
                // var_dump($datas);
                if ($datas['counts'] == 0) {
                    $countDones = $datas['counts'];
                    // var_dump($countDones);
                    $queryDone[$key]["total"] = 0;
                }else{
                    // return "sini";
                    $countDonesDone = $datas['counts'];
                    // var_dump($countDonesDone);
                    $queryDone[$key]["total"] = 1;
                }
            } 
        } 

        $sum = 0;
        foreach($queryDone as $key => $data){
           $sum += $queryDone[$key]["total"]; 
        }

        // return array("data" => $data->get());
        return array("data"=>collect([["count"=>$dataAll->initiating,"label"=>"Initiating"],["count"=>$dataAll->planning,"label"=>"Planning"],["count"=>$dataAll->executing,"label"=>"Executing"],["count"=>$countClosing,"label"=>"Closing"],["count"=>$sum,"label"=>"Done"]]));
    }

    public function getHandoverProject(Request $request)
    {
        $data = DB::table('gantt_tasks_pmo')
            ->join('tb_pmo','tb_pmo.id','gantt_tasks_pmo.id_pmo')
            ->select(
                DB::raw('COUNT(IF(`text` = "Internal handover project" AND `status` = "Done",1,NULL)) AS "done"'),
                DB::raw('COUNT(IF(`text` = "Internal handover project" AND `status` = "On-Going",1,NULL)) AS "no"')
            )
            ->where('project_id','like','%'.$request->year)
            ->first();
            // ->whereYear('project_id', date('Y'))
            // ->groupBy('amount_idr');

        // return array("data" => $data->get());
        return array("data"=>collect([["count"=>$data->done,"label"=>"Done"],["count"=>$data->no,"label"=>"No"]]));
    }

    public function getProjectHealth(Request $request)
    {

        $getSupplyOnly = DB::table('gantt_tasks_pmo')->join('tb_pmo','tb_pmo.id','gantt_tasks_pmo.id_pmo')->where('project_type','supply_only')->where('parent','!=',0)->select('gantt_tasks_pmo.id as id_task','text','status','tb_pmo.id as id_pmo')->where('project_id','like','%'.$request->year);
        $get_id_min = DB::table($getSupplyOnly,'temp')->groupBy('id_pmo')->selectRaw('MIN(`temp`.`id_task`) as `id_task`')->where('status','On-Going');
        $getAllSupplyOnly = DB::table($get_id_min,'temp2')->join('gantt_tasks_pmo','gantt_tasks_pmo.id','temp2.id_task')->select('temp2.id_task','text','baseline_end','gantt_tasks_pmo.id_pmo');
        $getCountSupplyOnly = DB::table($getAllSupplyOnly,'temp4')->join('gantt_tasks_pmo','gantt_tasks_pmo.id','temp4.id_task')->select(
            DB::raw('COUNT(IF(`temp4`.`baseline_end` < "'.date('Y-m-d').'",1,NULL)) as "delayed"'),
            DB::raw('COUNT(IF(`temp4`.`baseline_end` > "'.date('Y-m-d').'",1,NULL)) as "onTrack"'),
            DB::raw('COUNT(IF(`temp4`.`baseline_end` = "'.date('Y-m-d').'",1,NULL)) as "mightDelay"')
        )->first();

        $data = DB::table('tb_pmo_progress_report')->join('tb_pmo','tb_pmo.id','tb_pmo_progress_report.id_project')->select('project_indicator','tb_pmo.id as id_pmo','tb_pmo_progress_report.id','id_project')->where('project_id','like','%'.$request->year);

        $get_id_max = DB::table($data,'temp')->groupBy('id_project')->selectRaw('MAX(`temp`.`id`) as `id`');
        $getAll = DB::table($get_id_max,'temp2')->join('tb_pmo_progress_report','tb_pmo_progress_report.id','temp2.id')->select('temp2.id','project_indicator','id_project');
        $getCount = DB::table($getAll,'temp3')->join('tb_pmo_progress_report','tb_pmo_progress_report.id','temp3.id')->select(
            DB::raw('COUNT(IF(`temp3`.`project_indicator` = "onTrack",1,NULL)) AS "onTrack"'),
            DB::raw('COUNT(IF(`temp3`.`project_indicator` = "mightDelay",1,NULL)) AS "mightDelay"'),
            DB::raw('COUNT(IF(`temp3`.`project_indicator` = "delayed",1,NULL)) AS "delayed"')
        )->first();


        // return $getCountSupplyOnly->delayed; 
        // return $getAllSupplyOnly->get(); 

        return array("data"=>collect([["count"=>$getCount->onTrack+$getCountSupplyOnly->onTrack,"label"=>"On Schedule"],["count"=>$getCount->mightDelay+$getCountSupplyOnly->mightDelay,"label"=>"Potential Delay"],["count"=>$getCount->delayed+$getCountSupplyOnly->delayed,"label"=>"Delay"]]));
    }

    public function getTotalProject(Request $request)
    {
        $getParent = GanttTaskPmo::select('id')->where('text','Closing')->groupBy('id_pmo');
        $data = GanttTaskPmo::join('tb_pmo','tb_pmo.id','gantt_tasks_pmo.id_pmo')->whereIn('parent',$getParent)->select('text','status','parent','id_pmo','gantt_tasks_pmo.id')->where('project_id','like','%'.$request->year);

        $get_id_max = DB::table($data,'temp')->groupBy('id_pmo')->selectRaw('MAX(`temp`.`id`) as `id_task`');
        $getAll = DB::table($get_id_max,'temp2')->join('gantt_tasks_pmo','gantt_tasks_pmo.id','temp2.id_task')->select('temp2.id_task','text','id_pmo','status as status_done')->where('status','Done');
        // return $getAll->get();

        return $dataOnGoing = DB::table($getAll, 'temp3')->rightJoin('tb_pmo','tb_pmo.id','temp3.id_pmo')->join('tb_pmo_assign','tb_pmo.id','tb_pmo_assign.id_project')->join('users','users.nik','tb_pmo_assign.nik')->select(
            'users.name','users.nik','id_position',
            DB::raw('COUNT(IF(`temp3`.`status_done` = "Done",1,NULL)) AS "finished"'),
            DB::raw('COUNT(IF(`tb_pmo`.`current_phase` = "New" OR `tb_pmo`.`current_phase` = "Initiating" OR `tb_pmo`.`current_phase` = "Planning" OR `tb_pmo`.`current_phase` = "Executing",1,NULL)) AS "on_going"')
        )->where('project_id','like','%'.$request->year)->groupBy('users.nik')->orderByRaw('FIELD(id_position, "PM SPV", "PM", "SERVICE PROJECT")')->get();
    }

    public function exportRiskExcel(Request $request)
    {
        $getIdProject = PMO::where('id',$request->id_pmo)->first()->project_id;

        // return $getIdProject;

        $spreadsheet = new Spreadsheet();

        $prSheet = new Worksheet($spreadsheet,'Risk Management');
        $spreadsheet->addSheet($prSheet);
        $spreadsheet->removeSheetByIndex(0);
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->mergeCells('A1:K1');
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

        $sheet->getStyle('A1:K1')->applyFromArray($titleStyle);
        $sheet->setCellValue('A1','Project Risk Management - Id Project [ '. $getIdProject.' ]');

        $headerStyle = $normalStyle;
        $headerStyle['font']['bold'] = true;
        $headerStyle['alignment'] = ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER];
        $sheet->getStyle('A2:K2')->applyFromArray($headerStyle);

        $headerContent = ["No", "Risk Description", "Risk Owner", "Impact", "Likelihood",  "Impact Rank", "Risk Response", "Due Date", "Review Date", "Status"];
        $sheet->fromArray($headerContent,NULL,'A2');

        $risks = PMORisk::select('risk_description','risk_owner','impact','likelihood','impact_rank','impact_description','risk_response','due_date','review_date','status')->where('id_project',$request->id_pmo)->get();

        foreach ($risks as $key => $data) {
            $sheet->fromArray(array_merge([$key + 1],array_values($data->toArray())),NULL,'A' . ($key + 3));
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
        $sheet->getColumnDimension('J')->setAutoSize(true);
        $sheet->getColumnDimension('K')->setAutoSize(true);



        $fileName = 'Project Risk Management - Id Project [ '. $getIdProject. ' ] '. '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        
        $writer = new Xlsx($spreadsheet);
        ob_end_clean();
        return $writer->save("php://output");

    }

    public function exportIssueExcel(Request $request)
    {
        $getIdProject = PMO::where('id',$request->id_pmo)->first()->project_id;

        // return $getIdProject;

        $spreadsheet = new Spreadsheet();

        $prSheet = new Worksheet($spreadsheet,'Issue Management');
        $spreadsheet->addSheet($prSheet);
        $spreadsheet->removeSheetByIndex(0);
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->mergeCells('A1:H1');
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

        $sheet->getStyle('A1:H1')->applyFromArray($titleStyle);
        $sheet->setCellValue('A1','Project Issue Management - Id Project [ '. $getIdProject.' ]');

        $headerStyle = $normalStyle;
        $headerStyle['font']['bold'] = true;
        $headerStyle['alignment'] = ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER];
        $sheet->getStyle('A2:J2')->applyFromArray($headerStyle);

        $headerContent = ["No", "Issue Description", "Solution Plan", "Owner", "Rating Severity",  "Expected Date", "Actual Date", "Status"];
        $sheet->fromArray($headerContent,NULL,'A2');

        $risks = PMOIssue::select('issue_description','solution_plan','owner','rating_severity','expected_date','actual_date','status')->where('id_project',$request->id_pmo)->get();

        foreach ($risks as $key => $data) {
            $sheet->fromArray(array_merge([$key + 1],array_values($data->toArray())),NULL,'A' . ($key + 3));
        }

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);



        $fileName = 'Project Issue Management - Id Project [ '. $getIdProject. ' ] '. '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        
        $writer = new Xlsx($spreadsheet);
        ob_end_clean();
        return $writer->save("php://output");

    }

    public function getYearFilter()
    {
        $data = DB::table('tb_pmo')->select( 
                    DB::raw("SUBSTRING_INDEX(GROUP_CONCAT(DISTINCT `project_id`), '/',-1) AS id"),
                    DB::raw("SUBSTRING_INDEX(GROUP_CONCAT(DISTINCT `project_id`), '/',-1) AS text"))
                ->groupBy('project_id')->distinct()->get();
        return $data;
        
    }

    public function getCountDashboardByYear(Request $request)
    {
        $countInitiating = DB::table('tb_pmo')->whereRaw("(`current_phase` =  'New' OR `current_phase` = 'Waiting')")->where('project_id','like','%'.$request->year)->count();
        $countPlanning = DB::table('tb_pmo')->where('current_phase','Planning')->where('project_id','like','%'.$request->year)->count();
        $countExecuting = DB::table('tb_pmo')->where('current_phase','Executing')->where('project_id','like','%'.$request->year)->count();
        $getParent = GanttTaskPmo::select('id')->where('text','Closing')->groupBy('id_pmo');

        $countClosing = GanttTaskPmo::join('tb_pmo','tb_pmo.id','gantt_tasks_pmo.id_pmo')->whereIn('parent',$getParent)->where('project_id','like','%'.$request->year)->get()->where('status','On-Going')->groupBy('id_pmo')->count();

        $queryDone =  GanttTaskPmo::join('tb_pmo','tb_pmo.id','gantt_tasks_pmo.id_pmo')->select('id_pmo',DB::raw('(CASE WHEN status = "Done" THEN 1 ELSE 0 END) AS counts'))->whereIn('parent',$getParent)->orderby('gantt_tasks_pmo.id','desc')->where('project_id','like','%'.$request->year)->get()->groupBy('id_pmo');

        $countDone = 0;
        $countDones = 0;
        $countDonesDone = 0;
        foreach($queryDone as $key => $data){
            // return $key;
            foreach($queryDone[$key] as $datas){
                // var_dump($datas);
                if ($datas['counts'] == 0) {
                    $countDones = $datas['counts'];
                    // var_dump($countDones);
                    $queryDone[$key]["total"] = 0;
                }else{
                    // return "sini";
                    $countDonesDone = $datas['counts'];
                    // var_dump($countDonesDone);
                    $queryDone[$key]["total"] = 1;
                }
            } 
        } 

        $sum = 0;
        foreach($queryDone as $key => $data){
           $sum += $queryDone[$key]["total"]; 
        }        

        $countOnGoing = DB::table('tb_pmo')->whereRaw("(`current_phase` =  'New' OR `current_phase` = 'Waiting' OR `current_phase` = 'Planning' OR `current_phase` = 'Executing' OR `current_phase` = 'Closing')")->where('project_id','like','%'.$request->year)->count();

        return collect([
            "countInitiating" => $countInitiating,
            "countPlanning" => $countPlanning,
            "countExecuting" => $countExecuting,
            "countClosing" => $countClosing,
            "countDone" => $sum,
            "countOnGoing" => $countOnGoing
        ]);
    }
}
