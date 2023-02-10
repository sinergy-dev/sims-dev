<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PMO;
use App\PMOProgress;
use App\Sales;
use App\Sales2;
use Session;
use Auth;
use DB;
use PDF;
use App\User;
use App\PMO_phase;
use App\PMO_assign;
use App\PMO_detail;
use App\PMO_changelog;
use App\PMO_problem;
use App\GanttTaskPmo;
use App\GanttLink;

use Excel;

class PMOController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position; 
        $company = DB::table('users')->select('id_company')->where('nik',$nik)->first();
        $com = $company->id_company;

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
            ->where('result','OPEN')
            ->orderBy('sales_lead_register.created_at','desc')
            ->get();
        }else{
             $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
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
            ->select('sales_lead_register.opp_name','sales_solution_design.nik','sales_solution_design.lead_id')
            ->where('result','SD')
            ->orderBy('sales_lead_register.created_at','desc')
            ->get();
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $notifsd= DB::table('sales_lead_register')
            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->select('sales_lead_register.opp_name','sales_solution_design.nik','sales_solution_design.lead_id')
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
            ->select('sales_lead_register.opp_name','sales_solution_design.nik','sales_solution_design.lead_id')
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

        $notifClaim = '';

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

        $count_d = PMO::where('current_phase', 'Design')
                        ->count();

        $count_s = PMO::where('current_phase', 'Staging')
                        ->count();

        $count_i = PMO::where('current_phase', 'Implementation')
                        ->count();

        $count_m = PMO::where('current_phase', 'Migration')
                        ->count();

        $count_t = PMO::where('current_phase', 'Testing')
                        ->count();

        $count_done = PMO::where('current_phase', 'Done')
                        ->count();

        $lead_win = DB::table('sales_lead_register')
                        ->select('sales_lead_register.lead_id', 'opp_name')
                        ->whereNotIn('sales_lead_register.lead_id',function($query) {

                         $query->select('tb_pmo.lead_id')->from('tb_pmo');})
                        ->where('result', 'WIN')
                        ->get();

        $data = PMO::select('id_pmo', 'lead_id', 'title', 'current_phase')
                    ->orderBy('id_pmo', 'desc')
                    ->get();

        $engineer_staff = DB::table('users')
                            ->select('nik', 'name')
                            ->where('id_position', 'PM')
                            ->where('id_division','PMO')
                            ->where('id_company',1)
                            ->get();

        $engineer_manager = DB::table('users')
                            ->select('nik', 'name')
                            ->where('id_position', 'MANAGER')
                            ->where('id_division','PMO')
                            ->where('id_company',1)
                            ->first();
        
        return view('PMO.index', compact('notifClaim', 'notif', 'notifOpen', 'notifsd', 'notiftp', 'count_d', 'count_s', 'count_i', 'count_m', 'count_t', 'count_done', 'lead_win', 'data', 'engineer_staff', 'engineer_manager'))->with(['initView'=> $this->initMenuBase(),'feature_item'=>$this->RoleDynamic('pmo_index')]);
    }

    public function detail($lead_id) {

        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position; 
        $company = DB::table('users')->select('id_company')->where('nik',$nik)->first();
        $com = $company->id_company;

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
            ->where('result','OPEN')
            ->orderBy('sales_lead_register.created_at','desc')
            ->get();
        }else{
             $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
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
            ->select('sales_lead_register.opp_name','sales_solution_design.nik','sales_solution_design.lead_id')
            ->where('result','SD')
            ->orderBy('sales_lead_register.created_at','desc')
            ->get();
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $notifsd= DB::table('sales_lead_register')
            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->select('sales_lead_register.opp_name','sales_solution_design.nik','sales_solution_design.lead_id')
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
            ->select('sales_lead_register.opp_name','sales_solution_design.nik','sales_solution_design.lead_id')
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

        $imp_id = $lead_id;
        $id = DB::table('tb_pmo')->select('id_pmo')->where('lead_id',$lead_id)->orderBy('id_pmo','desc')->first();

        $engineer_staff = DB::table('users')
                                ->select('nik', 'name')
                                ->where('id_position', 'PM')
                                ->where('id_company',1)
                                ->get();

        $engineer_manager = DB::table('users')
                                ->select('nik', 'name')
                                ->where('id_position', 'MANAGER')
                                ->where('id_division','PMO')
                                ->where('id_company',1)
                                ->first();
        if ($id != NULL) {
            $id_pmo = $id->id_pmo;

            $current_engineer = DB::table('tb_pmo_assign')
                                ->select('nik', 'role')
                                ->where('id_pmo', $id_pmo)
                                ->where('nik', Auth::User()->nik)
                                ->first();

            $detail = DB::table('tb_pmo')
                            ->select('id_pmo','title', 'current_phase')
                            ->where('id_pmo', $id_pmo)
                            ->first();

            $detail_id_engineer = DB::table('tb_pmo_assign')
                                        ->select('id', 'nik')
                                        ->where('id_pmo', $id_pmo)
                                        ->get();

            $detail_id_phase = DB::table('tb_pmo_phase')
                                    ->select('id', 'phase_status', 'start_date', 'end_date', 'finish_date')
                                    ->where('id_pmo', $id_pmo)
                                    ->get();

            $engineer_problem = DB::table('tb_pmo_problem')
                                    ->join('tb_pmo_assign', 'tb_pmo_assign.id', '=', 'tb_pmo_problem.id_engineer_assign')
                                    ->join('users', 'users.nik', '=', 'tb_pmo_assign.nik')
                                    ->join('tb_pmo_phase', 'tb_pmo_phase.id', '=', 'tb_pmo_problem.id_phase')
                                    ->select('tb_pmo_problem.start_date', 'tb_pmo_problem.end_date', 'tb_pmo_problem.id', 'users.name', 'users.nik', 'tb_pmo_phase.phase_status', 'tb_pmo_problem.problem', 'tb_pmo_problem.conture_measure', 'tb_pmo_problem.root_cause')
                                    ->where('tb_pmo_problem.id_pmo', $id_pmo)
                                    ->get();

            $engineer_progress = DB::table('tb_pmo_detail')
                                ->join('tb_pmo_assign', 'tb_pmo_assign.id', '=', 'tb_pmo_detail.id_engineer_assign')
                                ->join('users', 'users.nik', '=', 'tb_pmo_assign.nik')
                                ->join('tb_pmo_phase', 'tb_pmo_phase.id', '=', 'tb_pmo_detail.id_phase')
                                ->select('users.name', 'tb_pmo_detail.id', 'tb_pmo_detail.progress', 'tb_pmo_phase.phase_status', 'users.nik', 'tb_pmo_detail.date')
                                ->where('tb_pmo_detail.id_pmo', $id_pmo)
                                ->get();

            $project_leader = DB::table('tb_pmo_assign')
                                    ->join('users', 'users.nik', '=', 'tb_pmo_assign.nik')
                                    ->select('users.name', 'users.nik')
                                    ->where('tb_pmo_assign.id_pmo', $id_pmo)
                                    ->where('role', 'Project Leader')
                                    ->first();

            $member = DB::table('tb_pmo_assign')
                                    ->join('users', 'users.nik', '=', 'tb_pmo_assign.nik')
                                    ->select('users.name', 'users.nik', 'tb_pmo_assign.id')
                                    ->where('tb_pmo_assign.id_pmo', $id_pmo)
                                    ->where('role', 'Member')
                                    ->get();


            $current_engineer_manager = DB::table('tb_pmo_assign')
                                            ->select('nik')
                                            ->where('nik', $engineer_manager->nik)
                                            ->where('id_pmo', $id_pmo)
                                            ->first();

            $phase = array('Design', 'Staging', 'Implementation', 'Migration', 'Testing');

            $detail_problem = DB::table('tb_pmo_problem')
                                    ->select('id', 'id_pmo', 'problem', 'conture_measure', 'root_cause', 'start_date', 'end_date')
                                    ->where('id_pmo', $id_pmo)
                                    ->get();

            $change_log = DB::table('tb_pmo_changelog')
                                    ->join('tb_pmo_assign', 'tb_pmo_assign.id', '=', 'tb_pmo_changelog.id_engineer_assign')
                                    ->join('users', 'users.nik', '=', 'tb_pmo_assign.nik')
                                    ->select('users.name', 'tb_pmo_changelog.status', 'tb_pmo_changelog.date')
                                    ->where('tb_pmo_changelog.id_pmo', $id_pmo)
                                    ->orderBy('tb_pmo_changelog.id', 'desc')
                                    ->get();

            $list_engineer = User::select('nik', 'name')
                                    ->where('id_division', 'TECHNICAL')
                                    ->where('id_territory', 'DPG')
                                    ->get();
                                    // ->whereNotIn('nik',function($query) use ($id) {
                                    //     $query->select('nik')
                                                // ->where('id_imp', $id)
                                    //             ->from('tb_imp_engineer_assign');
                                    // })->get();

            return view('PMO/detail', compact('list_engineer', 'notifClaim', 'notif', 'notifOpen', 'notifsd', 'notiftp', 'imp_id', 'current_engineer', 'detail', 'detail_id_engineer', 'detail_id_phase', 'engineer_problem', 'engineer_progress', 'project_leader', 'member', 'engineer_staff', 'engineer_manager', 'current_engineer_manager', 'phase', 'detail_problem', 'change_log','id'))->with(['initView'=>$this->initMenuBase()]);
        }
        
        return view('PMO/detail', compact('id','imp_id','notifClaim', 'notif', 'notifOpen', 'notifsd', 'notiftp','engineer_staff', 'engineer_manager'))->with(['initView'=>$this->initMenuBase()]);

        // Untuk seleksi siapa saja yang absen dan yang belum
        // $current_engineer = DB::table('tb_imp_engineer_assign')
        //                         ->select('nik', 'role')
        //                         ->where('id_imp', $id)
        //                         ->where('nik', Auth::User()->nik)
        //                         ->first();

    }

    public function store_stage(Request $request){
        $dates_d = strtotime($request['design_date']);
        $dates_s = strtotime($request['staging_date']);
        $dates_i = strtotime($request['implementation_date']);
        $dates_m = strtotime($request['migration_date']);
        $dates_t = strtotime($request['testing_date']);

        // Add new project
        $tambah_pmo                 = new PMO();
        $tambah_pmo->lead_id        = $request['pmo_lead_add'];
        $tambah_pmo->title          = $request['add_project_title'];
        $tambah_pmo->current_phase  = 'Design';
        $tambah_pmo->save();

        // Design Phase
        $substr_start_d = substr($request['design_date'], 0, 10);
        $format_start_d = strtotime($substr_start_d);
        $substr_end_d = substr($request['design_date'], 13, 10);
        $format_end_d = strtotime($substr_end_d);
        $tambah_phase               = new PMO_phase();
        $tambah_phase->id_pmo       = $tambah_pmo->id_pmo;
        $tambah_phase->phase_status = 'Design';
        $tambah_phase->start_date   = date("Y-m-d", $format_start_d);
        $tambah_phase->end_date     = date("Y-m-d", $format_end_d);
        $tambah_phase->save();

        // Staging Phase
        $substr_start_s = substr($request['staging_date'], 0, 10);
        $format_start_s = strtotime($substr_start_s);
        $substr_end_s = substr($request['staging_date'], 13, 10);
        $format_end_s = strtotime($substr_end_s);
        $tambah_phase               = new PMO_phase();
        $tambah_phase->id_pmo       = $tambah_pmo->id_pmo;
        $tambah_phase->phase_status = 'Staging';
        $tambah_phase->start_date   = date("Y-m-d", $format_start_s);
        $tambah_phase->end_date     = date("Y-m-d", $format_end_s);
        $tambah_phase->save();

        // Implementation Phase
        $substr_start_i = substr($request['implementation_date'], 0, 10);
        $format_start_i = strtotime($substr_start_i);
        $substr_end_i = substr($request['implementation_date'], 13, 10);
        $format_end_i = strtotime($substr_end_i);
        $tambah_phase               = new PMO_phase();
        $tambah_phase->id_pmo       = $tambah_pmo->id_pmo;
        $tambah_phase->phase_status = 'Implementation';
        $tambah_phase->start_date   = date("Y-m-d", $format_start_i);
        $tambah_phase->end_date     = date("Y-m-d", $format_end_i);
        $tambah_phase->save();

        // Migration Phase
        $substr_start_m = substr($request['migration_date'], 0, 10);
        $format_start_m = strtotime($substr_start_m);
        $substr_end_m = substr($request['migration_date'], 13, 10);
        $format_end_m = strtotime($substr_end_m);
        $tambah_phase               = new PMO_phase();
        $tambah_phase->id_pmo       = $tambah_pmo->id_pmo;
        $tambah_phase->phase_status = 'Migration';
        $tambah_phase->start_date   = date("Y-m-d", $format_start_m);
        $tambah_phase->end_date     = date("Y-m-d", $format_end_m);
        $tambah_phase->save();

        // Testing Phase
        $substr_start_t = substr($request['testing_date'], 0, 10);
        $format_start_t = strtotime($substr_start_t);
        $substr_end_t = substr($request['testing_date'], 13, 10);
        $format_end_t = strtotime($substr_end_t);
        $tambah_phase               = new PMO_phase();
        $tambah_phase->id_pmo       = $tambah_pmo->id_pmo;
        $tambah_phase->phase_status = 'Testing';
        $tambah_phase->start_date   = date("Y-m-d", $format_start_t);
        $tambah_phase->end_date     = date("Y-m-d", $format_end_t);
        $tambah_phase->save();

        // Assign Project Leader
        $tambah_leader          = new PMO_assign();
        $tambah_leader->id_pmo  = $tambah_pmo->id_pmo;
        $tambah_leader->role    = 'Project Leader';
        $tambah_leader->nik     = $request['project_leader'];
        $tambah_leader->save();

        // Assign Member
        $get_member = $request['project_member'];
        foreach($get_member as $gets){
            $tambah_leader          = new PMO_assign();
            $tambah_leader->id_pmo  = $tambah_pmo->id_pmo;
            $tambah_leader->role    = 'Member';
            $tambah_leader->nik     = $gets;
            $tambah_leader->save();
        }

        // Add change log
        $detail_id_engineer = DB::table('tb_pmo_assign')
                                    ->select('id', 'nik')
                                    ->where('id_pmo', $tambah_pmo->id_pmo)
                                    ->get();

        // foreach($detail_id_engineer as $current_die) {
        //     if($current_die->nik == Auth::User()->nik) {
        //         $add_log                        = new Imp_Change_Log();
        //         $add_log->id_imp                = $tambah_pmo->id;
        //         $add_log->id_engineer_assign    = $current_die->id;
        //         $add_log->status                = 'Create New Project - ['.$tambah_pmo->lead_id.'] '.$tambah_pmo->title;
        //         $add_log->save();
        //     }
        // }

        return redirect()->back();
    
    }

    public function edit_phase(Request $request) {

        $imp_id = $request['edit_phase_id_imp'];

        $current_design = PMO_phase::where('id_pmo', $imp_id)
                                    ->where('phase_status', 'Design')
                                    ->select('start_date', 'end_date')
                                    ->first();
        
        $current_staging = PMO_phase::where('id_pmo', $imp_id)
                                    ->where('phase_status', 'Staging')
                                    ->select('start_date', 'end_date')
                                    ->first();

        $current_implementation = PMO_phase::where('id_pmo', $imp_id)
                                    ->where('phase_status', 'Implementation')
                                    ->select('start_date', 'end_date')
                                    ->first();

        $current_migration = PMO_phase::where('id_pmo', $imp_id)
                                    ->where('phase_status', 'Migration')
                                    ->select('start_date', 'end_date')
                                    ->first();

        $current_testing = PMO_phase::where('id_pmo', $imp_id)
                                    ->where('phase_status', 'Testing')
                                    ->select('start_date', 'end_date')
                                    ->first();

        // ----------------------------------------------------------------------------------- Design Phase
        $substr_start_d = substr($request['Design_date_edit'], 0, 10);
        $format_start_d = strtotime($substr_start_d);
        $start_d        = date("Y-m-d", $format_start_d);
        $substr_end_d   = substr($request['Design_date_edit'], 13, 10);
        $format_end_d   = strtotime($substr_end_d);
        $end_d          = date("Y-m-d", $format_end_d);
        if($current_design->start_date != $start_d || $current_design->end_date != $end_d) {
            $add_log                        = new PMO_changelog();
            $add_log->id_pmo                = $imp_id;
            $add_log->id_engineer_assign    = $request['edit_phase_id_engineer'];
            $add_log->status                = 'Edit Phase Status - [Design] from "'.$current_design->start_date.' - '.$current_design->end_date.'" to "'.$start_d.' - '.$end_d.'"';
            $add_log->date                  = date('Y-m-d');
            $add_log->save();
        }
        $design_phase_update = PMO_phase::where('id_pmo', $imp_id)->where('phase_status', 'Design')->first();
        if($current_design->start_date != $start_d){
            $design_phase_update->start_date = $start_d;
        }
        if($current_design->end_date != $end_d){
            $design_phase_update->end_date = $end_d;
        }
        $design_phase_update->update();

        // ----------------------------------------------------------------------------------- Staging Phase
        $substr_start_s = substr($request['Staging_date_edit'], 0, 10);
        $format_start_s = strtotime($substr_start_s);
        $start_s        = date("Y-m-d", $format_start_s);
        $substr_end_s   = substr($request['Staging_date_edit'], 13, 10);
        $format_end_s   = strtotime($substr_end_s);
        $end_s          = date("Y-m-d", $format_end_s);
        if($current_staging->start_date != $start_s || $current_staging->end_date != $end_s) {
            $add_log                        = new PMO_changelog();
            $add_log->id_pmo              = $imp_id;
            $add_log->id_engineer_assign    = $request['edit_phase_id_engineer'];
            $add_log->status                = 'Edit Phase Status - [Staging] from "'.$current_staging->start_date.' - '.$current_staging->end_date.'" to "'.$start_s.' - '.$end_s.'"';
            $add_log->date                  = date('Y-m-d');
            $add_log->save();
        }
        $staging_phase_update = PMO_phase::where('id_pmo', $imp_id)->where('phase_status', 'Staging')->first();
        if($current_staging->start_date != $start_s){
            $staging_phase_update->start_date = $start_s;
        }
        if($current_staging->end_date != $end_s){
            $staging_phase_update->end_date = $end_s;
        }
        $staging_phase_update->update();

        // ----------------------------------------------------------------------------------- Implementation Phase
        $substr_start_i = substr($request['Implementation_date_edit'], 0, 10);
        $format_start_i = strtotime($substr_start_i);
        $start_i        = date("Y-m-d", $format_start_i);
        $substr_end_i   = substr($request['Implementation_date_edit'], 13, 10);
        $format_end_i   = strtotime($substr_end_i);
        $end_i          = date("Y-m-d", $format_end_i);
        if($current_implementation->start_date != $start_i || $current_implementation->end_date != $end_i) {
            $add_log                        = new PMO_changelog();
            $add_log->id_pmo              = $imp_id;
            $add_log->id_engineer_assign    = $request['edit_phase_id_engineer'];
            $add_log->status                = 'Edit Phase Status - [Implementation] from "'.$current_implementation->start_date.' - '.$current_implementation->end_date.'" to "'.$start_i.' - '.$end_i.'"';
            $add_log->date                  = date('Y-m-d');
            $add_log->save();
        }
        $implementation_phase_update = PMO_phase::where('id_pmo', $imp_id)->where('phase_status', 'Implementation')->first();
        if($current_implementation->start_date != $start_i){
            $implementation_phase_update->start_date = $start_i;
        }
        if($current_implementation->end_date != $end_i){
            $implementation_phase_update->end_date = $end_i;
        }
        $implementation_phase_update->update();

        // ----------------------------------------------------------------------------------- Migration Phase
        $substr_start_m = substr($request['Migration_date_edit'], 0, 10);
        $format_start_m = strtotime($substr_start_m);
        $start_m        = date("Y-m-d", $format_start_m);
        $substr_end_m   = substr($request['Migration_date_edit'], 13, 10);
        $format_end_m   = strtotime($substr_end_m);
        $end_m          = date("Y-m-d", $format_end_m);
        if($current_migration->start_date != $start_m || $current_migration->end_date != $end_m) {
            $add_log                        = new PMO_changelog();
            $add_log->id_pmo              = $imp_id;
            $add_log->id_engineer_assign    = $request['edit_phase_id_engineer'];
            $add_log->status                = 'Edit Phase Status - [Migration] from "'.$current_migration->start_date.' - '.$current_migration->end_date.'" to "'.$start_m.' - '.$end_m.'"';
            $add_log->date                  = date('Y-m-d');
            $add_log->save();
        }
        $migration_phase_update = PMO_phase::where('id_pmo', $imp_id)->where('phase_status', 'Migration')->first();
        if($current_migration->start_date != $start_m){
            $migration_phase_update->start_date = $start_m;
        }
        if($current_migration->end_date != $end_m){
            $migration_phase_update->end_date = $end_m;
        }
        $migration_phase_update->update();

        // ----------------------------------------------------------------------------------- Testing Phase
        $substr_start_t = substr($request['Testing_date_edit'], 0, 10);
        $format_start_t = strtotime($substr_start_t);
        $start_t        = date("Y-m-d", $format_start_t);
        $substr_end_t   = substr($request['Testing_date_edit'], 13, 10);
        $format_end_t   = strtotime($substr_end_t);
        $end_t          = date("Y-m-d", $format_end_t);
        if($current_testing->start_date != $start_t || $current_testing->end_date != $end_t) {
            $add_log                        = new PMO_changelog();
            $add_log->id_pmo              = $imp_id;
            $add_log->id_engineer_assign    = $request['edit_phase_id_engineer'];
            $add_log->status                = 'Edit Phase Status - [Testing] from "'.$current_testing->start_date.' - '.$current_testing->end_date.'" to "'.$start_t.' - '.$end_t.'"';
            $add_log->date                  = date('Y-m-d');
            $add_log->save();
        }
        $testing_phase_update = PMO_phase::where('id_pmo', $imp_id)->where('phase_status', 'Testing')->first();
        if($current_testing->start_date != $start_t){
            $testing_phase_update->start_date = $start_t;
        }
        if($current_testing->end_date != $end_t){
            $testing_phase_update->end_date = $end_t;
        }
        $testing_phase_update->update();

        return redirect()->back();

    }

    public function update_phase(Request $request) {

        $imp_id = $request['next_phase_id_imp'];
        $current_phase = $request['phase_now'];

        $finish_date = PMO_phase::where('id_pmo', $imp_id)->where('phase_status', $current_phase)->first();
        $finish_date->finish_date = date('Y-m-d');
        $finish_date->update();

        $add_log                        = new PMO_changelog();
        $add_log->id_pmo                = $imp_id;
        $add_log->id_engineer_assign    = $request['update_phase_id_engineer'];
        $add_log->status                = 'Update Phase Status - ['.$request['next_current_phase'].']';
        $add_log->date                  = date('Y-m-d');
        $add_log->save();

        $update = PMO::where('id_pmo', $imp_id)->first();
        $update->current_phase = $request['next_current_phase'];
        $update->update();

        return redirect()->back();

    }

    public function add_progress(Request $request) {

        $tambah_progress                        = new PMO_detail();
        $tambah_progress->id_pmo                = $request['progress_id_imp'];
        $tambah_progress->id_engineer_assign    = $request['progress_id_engineer'];
        $tambah_progress->id_phase              = $request['progress_id_phase'];
        $tambah_progress->progress              = $request['progress_input'];
        $tambah_progress->date                  = date('Y-m-d');
        $tambah_progress->save();

        return redirect()->back();

    }

    public function add_problem(Request $request) {

        $substr_start_d = substr($request['problem_date'], 0, 10);
        $format_start_d = strtotime($substr_start_d);
        $substr_end_d = substr($request['problem_date'], 13, 10);
        $format_end_d = strtotime($substr_end_d);

        $tambah_problem                         = new PMO_problem();
        $tambah_problem->id_pmo                 = $request['problem_id_imp'];
        $tambah_problem->id_engineer_assign     = $request['problem_id_engineer'];
        $tambah_problem->id_phase               = $request['problem_id_phase'];
        $tambah_problem->start_date             = date("Y-m-d", $format_start_d);
        $tambah_problem->end_date               = date("Y-m-d", $format_end_d);
        $tambah_problem->problem                = $request['problem_input'];
        $tambah_problem->conture_measure        = $request['measure_input'];
        $tambah_problem->root_cause             = $request['cause_input'];
        $tambah_problem->save();

        return redirect()->back();

    }

    public function progress_edit(Request $request) {

        $progress_id = $request['progress_id_imp'];

        $update_progress                = PMO_detail::where('id_pmo', $progress_id)->first();
        $update_progress->progress      = $request['edit_progress_input'];
        $update_progress->update();

        return redirect()->back();

    }

    public function update_leader(Request $request) {

        $imp_id = $request['leader_update_id_imp'];
        
        $update_leader = PMO_assign::where('id_pmo', $imp_id)->where('role', 'Project Leader')->first();
        $update_leader->role = 'Member';
        $update_leader->update();

        $selected_leader = $request['project_leader'];
        $on_db_leader = DB::table('tb_pmo_assign')
                                ->select('nik')
                                ->where('nik', $selected_leader)
                                ->first();

        if($selected_leader == optional($on_db_leader)->nik) {
            $update_current_leader = PMO_assign::where('id_pmo', $imp_id)->where('nik', $selected_leader)->first();
            $update_current_leader->role = 'Project Leader';
            $update_current_leader->update();
        } else {
            $tambah_leader          = new PMO_assign();
            $tambah_leader->id_pmo  = $imp_id;
            $tambah_leader->role    = 'Project Leader';
            $tambah_leader->nik     = $request['project_leader'];
            $tambah_leader->save();
        }

        return redirect()->back();

    }

    public function geteditprogress(Request $request){

        return array(DB::table('tb_pmo_detail')
                ->join('tb_pmo_assign', 'tb_pmo_assign.id', '=', 'tb_pmo_detail.id_engineer_assign')
                ->join('users', 'users.nik', '=', 'tb_pmo_assign.nik')
                ->join('tb_pmo_phase', 'tb_pmo_phase.id', '=', 'tb_pmo_detail.id_phase')
                ->select('users.name', 'tb_pmo_detail.id', 'tb_pmo_detail.progress', 'tb_pmo_phase.phase_status', 'users.nik', 'tb_pmo_detail.date')
                ->where('tb_pmo_detail.id',$request->id_progress)
                ->get(),$request->id_progress);

    }

    public function getGantt(Request $request){
        $tasks = new GanttTaskPmo();
        $links = new GanttLink();
 
        return response()->json([
            "data" => $tasks->where('id_pmo', $request->id_pmo)->get(),
            "links" => $links->all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tambah = new PMO();
        $tambah->pmo_nik = $request['pmo_nik'];
        $tambah->lead_id = $request['coba_lead_pmo'];
        $tambah->save();

        $lead_id = $request['coba_lead_pmo'];

        $update = Sales::where('lead_id', $lead_id)->first();
        $update->status_sho = 'PMO';
        $update->update();

        return redirect()->back();
    }

    public function update_pmo(Request $request)
    {
        $lead_pmo = $request['pmo_reassign'];

        $update = PMO::where('lead_id',$lead_pmo)->first();
        $update->pmo_nik = $request['upadte_pmo_nik'];
        $update->update();

        return redirect()->back();
    }

    public function progress_store(Request $request)
    {
        $tambah = new PMOProgress();
        $tambah->id_pmo = $request['id_pmo'];
        $tambah->tanggal = $request['tanggal'];
        $tambah->ket = $request['keterangan'];
        $tambah->save();

        return redirect()->back();
    }

    public function add_contribute(Request $request)
    {
        $tambah = new PMO();
        $tambah->lead_id = $request['coba_lead_contribute_pmo'];
        $tambah->pmo_nik = $request['add_contribute_pmo'];
        $tambah->save();

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id_pmo)
    {
        $hapus = PMO::find($id_pmo);
        $hapus->delete();

        return redirect()->back();
    }

    public function exportExcel(Request $request)
    {
        $nama = 'Lead Register '.date('Y-m-d');
        Excel::create($nama, function ($excel) use ($request) {
        $excel->sheet('Lead Register', function ($sheet) use ($request) {
        
        $sheet->mergeCells('A1:H1');

       // $sheet->setAllBorders('thin');
        $sheet->row(1, function ($row) {
            $row->setFontFamily('Calibri');
            $row->setFontSize(11);
            $row->setAlignment('center');
            $row->setFontWeight('bold');
        });

        $sheet->row(1, array('LEAD REGISTER'));

        $sheet->row(2, function ($row) {
            $row->setFontFamily('Calibri');
            $row->setFontSize(11);
            $row->setFontWeight('bold');
        });

        $datas = Sales2::join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name',
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','users.nik','tb_contact.code')
                    ->where('sales_lead_register.result','WIN')
                    ->get();

       // $sheet->appendRow(array_keys($datas[0]));
            $sheet->row($sheet->getHighestRow(), function ($row) {
                $row->setFontWeight('bold');
            });

             $datasheet = array();
             $datasheet[0]  =   array("NO", "LEAD ID", "CUSTOMER", "OPTY NAME", "CREATE DATE",  "OWNER", "AMOUNT", "STATUS");
             $i=1;

            foreach ($datas as $data) {

               // $sheet->appendrow($data);
              $datasheet[$i] = array($i,
                            $data['lead_id'],
                            $data['code'],
                            $data['opp_name'],
                            $data['created_at'],
                            $data['name'],
                            $data['amount'],
                            $data['result']
                        );
              
              $i++;
            }

            $sheet->fromArray($datasheet);
        });

        })->export('xls');
    }


    // public function downloadPDF()
    // {
    //     $nik = Auth::User()->nik;
    //     $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
    //     $ter = $territory->id_territory;
    //     $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
    //     $div = $division->id_division;
    //     $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
    //     $pos = $position->id_position;

    //     if($div == 'PMO'){
    //         $win = DB::table('sales_lead_register')
    //             ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
    //             ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
    //             ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
    //             'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
    //             ->where('result', 'win')
    //             ->get();
    //     }
    //     $pdf = PDF::loadView('report.win_pdf', compact('win'));
    //     return $pdf->download('exportpdfPMO-'.date("d-m-Y").'.pdf');
    // }

}
