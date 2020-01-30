<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Auth;
use DB;

use App\User;
use App\Imp;
use App\Imp_Detail;
use App\Imp_Phase;
use App\Imp_Engineer_Assign;
use App\Imp_Problem;
use App\Imp_Change_Log;

use App\GanttTask;
use App\GanttLink;

class ImplementationController extends Controller
{

    private $imp_ids;

    public function __construct() {

        $this->middleware('auth');

    }

    public function index() {

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

        $count_d = Imp::where('current_phase', 'Design')
                        ->count();

        $count_s = Imp::where('current_phase', 'Staging')
                        ->count();

        $count_i = Imp::where('current_phase', 'Implementation')
                        ->count();

        $count_m = Imp::where('current_phase', 'Migration')
                        ->count();

        $count_t = Imp::where('current_phase', 'Testing')
                        ->count();

        $count_done = Imp::where('current_phase', 'Done')
                        ->count();

        $lead_win = DB::table('sales_lead_register')
                        ->select('lead_id', 'opp_name')
                        ->where('result', 'WIN')
                        ->get();

        $data = Imp::select('id', 'lead_id', 'title', 'current_phase')
                    ->orderBy('created_at', 'desc')
                    ->get();

        $engineer_staff = DB::table('users')
                            ->select('nik', 'name')
                            ->where('id_position', 'ENGINEER STAFF')
                            ->get();

        $engineer_manager = DB::table('users')
                            ->select('nik', 'name')
                            ->where('id_position', 'ENGINEER MANAGER')
                            ->first();
        
        return view('implementation.index', compact('notifClaim', 'notif', 'notifOpen', 'notifsd', 'notiftp', 'count_d', 'count_s', 'count_i', 'count_m', 'count_t', 'count_done', 'lead_win', 'data', 'engineer_staff', 'engineer_manager'));

    }

    public function store(Request $request) {

        // $dates = strtotime($request['event_date']);

        // Mengubah data ke string
        // $attendees = $request->input('attendee');
        // $jsonAttendees = json_encode($attendees);
        
        // $tambah                 = new EventManagement();
        // $tambah->created_by     = Auth::User()->nik;
        // $tambah->title          = $request['event_title'];
        // $tambah->date           = date("Y-m-d", $dates);
        // $tambah->start_time     = $request['start_time'];
        // $tambah->end_time       = $request['end_time'];
        // $tambah->venue          = $request['venue'];
        // $tambah->organizer      = $request['organizer'];
        // $tambah->category       = $request['option-radio-inline'];
        // $tambah->attendee       = $jsonAttendees;
        // $tambah->save();

        $dates_d = strtotime($request['design_date']);
        $dates_s = strtotime($request['staging_date']);
        $dates_i = strtotime($request['implementation_date']);
        $dates_m = strtotime($request['migration_date']);
        $dates_t = strtotime($request['testing_date']);

        // Add new project
        $tambah_imp                 = new Imp();
        $tambah_imp->lead_id        = $request['lead_id_win'];
        $tambah_imp->title          = $request['add_project_title'];
        $tambah_imp->current_phase  = 'Design';
        $tambah_imp->save();

        // Design Phase
        $substr_start_d = substr($request['design_date'], 0, 10);
        $format_start_d = strtotime($substr_start_d);
        $substr_end_d = substr($request['design_date'], 13, 10);
        $format_end_d = strtotime($substr_end_d);
        $tambah_phase               = new Imp_Phase();
        $tambah_phase->id_imp       = $tambah_imp->id;
        $tambah_phase->phase_status = 'Design';
        $tambah_phase->start_date   = date("Y-m-d", $format_start_d);
        $tambah_phase->end_date     = date("Y-m-d", $format_end_d);
        $tambah_phase->save();

        // Staging Phase
        $substr_start_s = substr($request['staging_date'], 0, 10);
        $format_start_s = strtotime($substr_start_s);
        $substr_end_s = substr($request['staging_date'], 13, 10);
        $format_end_s = strtotime($substr_end_s);
        $tambah_phase               = new Imp_Phase();
        $tambah_phase->id_imp       = $tambah_imp->id;
        $tambah_phase->phase_status = 'Staging';
        $tambah_phase->start_date   = date("Y-m-d", $format_start_s);
        $tambah_phase->end_date     = date("Y-m-d", $format_end_s);
        $tambah_phase->save();

        // Implementation Phase
        $substr_start_i = substr($request['implementation_date'], 0, 10);
        $format_start_i = strtotime($substr_start_i);
        $substr_end_i = substr($request['implementation_date'], 13, 10);
        $format_end_i = strtotime($substr_end_i);
        $tambah_phase               = new Imp_Phase();
        $tambah_phase->id_imp       = $tambah_imp->id;
        $tambah_phase->phase_status = 'Implementation';
        $tambah_phase->start_date   = date("Y-m-d", $format_start_i);
        $tambah_phase->end_date     = date("Y-m-d", $format_end_i);
        $tambah_phase->save();

        // Migration Phase
        $substr_start_m = substr($request['migration_date'], 0, 10);
        $format_start_m = strtotime($substr_start_m);
        $substr_end_m = substr($request['migration_date'], 13, 10);
        $format_end_m = strtotime($substr_end_m);
        $tambah_phase               = new Imp_Phase();
        $tambah_phase->id_imp       = $tambah_imp->id;
        $tambah_phase->phase_status = 'Migration';
        $tambah_phase->start_date   = date("Y-m-d", $format_start_m);
        $tambah_phase->end_date     = date("Y-m-d", $format_end_m);
        $tambah_phase->save();

        // Testing Phase
        $substr_start_t = substr($request['testing_date'], 0, 10);
        $format_start_t = strtotime($substr_start_t);
        $substr_end_t = substr($request['testing_date'], 13, 10);
        $format_end_t = strtotime($substr_end_t);
        $tambah_phase               = new Imp_Phase();
        $tambah_phase->id_imp       = $tambah_imp->id;
        $tambah_phase->phase_status = 'Testing';
        $tambah_phase->start_date   = date("Y-m-d", $format_start_t);
        $tambah_phase->end_date     = date("Y-m-d", $format_end_t);
        $tambah_phase->save();

        // Assign Project Leader
        $tambah_leader          = new Imp_Engineer_Assign();
        $tambah_leader->id_imp  = $tambah_imp->id;
        $tambah_leader->role    = 'Project Leader';
        $tambah_leader->nik     = $request['project_leader'];
        $tambah_leader->save();

        // Assign Member
        $get_member = $request['project_member'];
        foreach($get_member as $gets){
            $tambah_leader          = new Imp_Engineer_Assign();
            $tambah_leader->id_imp  = $tambah_imp->id;
            $tambah_leader->role    = 'Member';
            $tambah_leader->nik     = $gets;
            $tambah_leader->save();
        }

        // Add change log
        $detail_id_engineer = DB::table('tb_imp_engineer_assign')
                                    ->select('nik')
                                    ->where('id_imp', $tambah_imp->id)
                                    ->where('role', 'Project Leader')
                                    ->get();

        foreach($detail_id_engineer as $current_die) {
            $add_log                        = new Imp_Change_Log();
            $add_log->id_imp                = $tambah_imp->id;
            $add_log->id_engineer_assign    = 1;
            $add_log->status                = 'Create New Project - ['.$tambah_imp->lead_id.'] '.$tambah_imp->title;
            $add_log->save();
        }

        //Gantt Chart Store
        $phase = array('Design', 'Staging', 'Implementation', 'Migration', 'Testing');

        foreach($phase as $p) {
            $task = new GanttTask();
 
            $task->text = $p;
            $task->id_imp = $tambah_imp->id;
            if($p == 'Design') {
                $date1 = date("Y-m-d", $format_start_d);
                $date2 = date("Y-m-d", $format_end_d);
                
                $diff = abs(strtotime($date2) - strtotime($date1));

                $years = floor($diff / (365*60*60*24));
                $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
                $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24) / (60*60*24));
                
                $task->start_date = date("Y-m-d", $format_start_d);
                $task->duration = $days+1;
            } elseif($p == 'Staging') {
                $date1 = date("Y-m-d", $format_start_s);
                $date2 = date("Y-m-d", $format_end_s);
                
                $diff = abs(strtotime($date2) - strtotime($date1));

                $years = floor($diff / (365*60*60*24));
                $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
                $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24) / (60*60*24));
                
                $task->start_date = date("Y-m-d", $format_start_s);
                $task->duration = $days+1;
            } elseif($p == 'Implementation') {
                $date1 = date("Y-m-d", $format_start_i);
                $date2 = date("Y-m-d", $format_end_i);
                
                $diff = abs(strtotime($date2) - strtotime($date1));

                $years = floor($diff / (365*60*60*24));
                $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
                $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24) / (60*60*24));
                
                $task->start_date = date("Y-m-d", $format_start_i);
                $task->duration = $days+1;
            } elseif($p == 'Migration') {
                $date1 = date("Y-m-d", $format_start_m);
                $date2 = date("Y-m-d", $format_end_m);
                
                $diff = abs(strtotime($date2) - strtotime($date1));

                $years = floor($diff / (365*60*60*24));
                $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
                $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24) / (60*60*24));
                
                $task->start_date = date("Y-m-d", $format_start_m);
                $task->duration = $days+1;
            } elseif($p == 'Testing') {
                $date1 = date("Y-m-d", $format_start_t);
                $date2 = date("Y-m-d", $format_end_t);
                
                $diff = abs(strtotime($date2) - strtotime($date1));

                $years = floor($diff / (365*60*60*24));
                $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
                $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24) / (60*60*24));
                
                $task->start_date = date("Y-m-d", $format_start_t);
                $task->duration = $days+1;
            }
            $task->progress = '0.00';
            $task->parent = '0';
    
            $task->save();
        }

        return redirect()->back();

    }

    public function detail($id) {

        $imp_id = $id;

        $coba = DB::table('tb_imp')
        ->select('id', 'title', 'current_phase')
        ->where('id', $id)
        ->first();

        $this->imp_ids = $coba->id;

        // $tasks = new GanttTask();
        // $links = new GanttLink();
 
        // // return response()->json([
        // //     "data" => $tasks->where('id_imp', $imp_id)->get(),
        // //     "links" => $links->all()
        // // ]);

        // $jsonGantt = [
        //     "data" => $tasks->where('id_imp', $imp_id)->get(),
        //     "links" => $links->all()
        // ];

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

        // Untuk seleksi siapa saja yang absen dan yang belum
        $current_engineer = DB::table('tb_imp_engineer_assign')
                                ->select('nik', 'role')
                                ->where('id_imp', $id)
                                ->where('nik', Auth::User()->nik)
                                ->first();

        $detail = DB::table('tb_imp')
                        ->select('id', 'title', 'current_phase')
                        ->where('id', $id)
                        ->first();

        $detail_id_engineer = DB::table('tb_imp_engineer_assign')
                                    ->select('id', 'nik')
                                    ->where('id_imp', $id)
                                    ->get();

        $detail_id_phase = DB::table('tb_imp_phase')
                                ->select('id', 'phase_status', 'start_date', 'end_date', 'finish_date')
                                ->where('id_imp', $id)
                                ->get();

        $engineer_problem = DB::table('tb_imp_problem')
                                ->join('tb_imp', 'tb_imp.id', '=', 'tb_imp_problem.id_imp')
                                ->join('tb_imp_engineer_assign', 'tb_imp_engineer_assign.id', '=', 'tb_imp_problem.id_engineer_assign')
                                ->join('users', 'users.nik', '=', 'tb_imp_engineer_assign.nik')
                                ->join('tb_imp_phase', 'tb_imp_phase.id', '=', 'tb_imp_problem.id_phase')
                                ->select('tb_imp_problem.start_date', 'tb_imp_problem.end_date', 'tb_imp_problem.id', 'users.name', 'users.nik', 'tb_imp_phase.phase_status', 'tb_imp_problem.problem', 'tb_imp_problem.conture_measure', 'tb_imp_problem.root_cause', 'tb_imp.current_phase')
                                ->where('tb_imp_problem.id_imp', $id)
                                ->get();

        $engineer_progress = DB::table('tb_imp_detail')
                            ->join('tb_imp', 'tb_imp.id', '=', 'tb_imp_detail.id_imp')
                            ->join('tb_imp_engineer_assign', 'tb_imp_engineer_assign.id', '=', 'tb_imp_detail.id_engineer_assign')
                            ->join('users', 'users.nik', '=', 'tb_imp_engineer_assign.nik')
                            ->join('tb_imp_phase', 'tb_imp_phase.id', '=', 'tb_imp_detail.id_phase')
                            ->select('users.name', 'tb_imp_detail.id', 'tb_imp_detail.progress', 'tb_imp_phase.phase_status', 'tb_imp_detail.created_at', 'users.nik', 'tb_imp.current_phase')
                            ->where('tb_imp_detail.id_imp', $id)
                            ->get();

        $project_leader = DB::table('tb_imp_engineer_assign')
                                ->join('users', 'users.nik', '=', 'tb_imp_engineer_assign.nik')
                                ->select('users.name', 'users.nik')
                                ->where('tb_imp_engineer_assign.id_imp', $id)
                                ->where('role', 'Project Leader')
                                ->first();

        $member = DB::table('tb_imp_engineer_assign')
                                ->join('users', 'users.nik', '=', 'tb_imp_engineer_assign.nik')
                                ->select('users.name', 'users.nik', 'tb_imp_engineer_assign.id')
                                ->where('tb_imp_engineer_assign.id_imp', $id)
                                ->where('role', 'Member')
                                ->get();

        $engineer_staff = DB::table('users')
                            ->select('nik', 'name')
                            ->where('id_position', 'ENGINEER STAFF')
                            ->get();

        $engineer_manager = DB::table('users')
                                ->select('nik', 'name')
                                ->where('id_position', 'ENGINEER MANAGER')
                                ->first();

        $current_engineer_manager = DB::table('tb_imp_engineer_assign')
                                        ->select('nik')
                                        ->where('nik', $engineer_manager->nik)
                                        ->where('id_imp', $id)
                                        ->first();

        $phase = array('Design', 'Staging', 'Implementation', 'Migration', 'Testing');

        $detail_problem = DB::table('tb_imp_problem')
                                ->select('id', 'id_imp', 'problem', 'conture_measure', 'root_cause', 'start_date', 'end_date', 'created_at', 'updated_at')
                                ->where('id_imp', $id)
                                ->get();

        $change_log = DB::table('tb_imp_change_log')
                                ->join('tb_imp_engineer_assign', 'tb_imp_engineer_assign.id', '=', 'tb_imp_change_log.id_engineer_assign')
                                ->join('users', 'users.nik', '=', 'tb_imp_engineer_assign.nik')
                                ->select('users.name', 'tb_imp_change_log.status', 'tb_imp_change_log.created_at')
                                ->where('tb_imp_change_log.id_imp', $id)
                                ->orderBy('tb_imp_change_log.created_at', 'desc')
                                ->get();

        $list_engineer = User::select('nik', 'name')
                                ->where('id_division', 'TECHNICAL')
                                ->where('id_territory', 'DPG')
                                ->whereNotIn('nik',function($query) use ($id) {
                                    $query->select('nik')
                                            ->where('id_imp', $id)
                                            ->from('tb_imp_engineer_assign');
                                })->get();
                                
        $gantt = GanttTask::select('id')->where('id_imp', $imp_id)->get();

        return view('implementation.detail', compact('gantt', 'coba','list_engineer', 'notifClaim', 'notif', 'notifOpen', 'notifsd', 'notiftp', 'imp_id', 'current_engineer', 'detail', 'detail_id_engineer', 'detail_id_phase', 'engineer_problem', 'engineer_progress', 'project_leader', 'member', 'engineer_staff', 'engineer_manager', 'current_engineer_manager', 'phase', 'detail_problem', 'change_log'));

    }

    public function get($id){
        $tasks = new GanttTask();
        $links = new GanttLink();
 
        return response()->json([
            "data" => $tasks->where('id_imp', $id)->get(),
            "links" => $links->all()
        ]);
    }

    public function update_project(Request $request) {

        $imp_id = $request['id_project'];

        $lead_id = $request['edit_lead_id_win'];

        $update_project = Imp::where('id', $imp_id)->first();
        if($lead_id != null) {
            $update_project->lead_id = $lead_id;
        }
        $update_project->title = $request['edit_title'];
        $update_project->update();

        return redirect()->back();

    }

    public function edit_phase(Request $request) {
        
        $imp_id = $request['edit_phase_id_imp'];

        $phase = array('Design', 'Staging', 'Implementation', 'Migration', 'Testing');

        // foreach($phase as $p) {
        //     $id_gantt = $request[$p.'_id_gantt'];

        //     $substr_start = substr($request[$p.'_date_edit'], 0, 10);
        //     $format_start = strtotime($substr_start);
        //     $start        = date("Y-m-d", $format_start);

        //     $substr_end   = substr($request[$p.'_date_edit'], 13, 10);
        //     $format_end   = strtotime($substr_end);
        //     $end          = date("Y-m-d", $format_end);
            
        //     $diff = abs(strtotime($end) - strtotime($start));

        //     $years = floor($diff / (365*60*60*24));
        //     $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
        //     $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24) / (60*60*24));

        //     $update = GanttTask::where('id', $id_gantt)->first();
        //     $update->duration = $days;
        //     $update->start_date = $start;
        //     $update->update();
        // }

        $current_design = Imp_Phase::where('id_imp', $imp_id)
                                    ->where('phase_status', 'Design')
                                    ->select('start_date', 'end_date')
                                    ->first();
        
        $current_staging = Imp_Phase::where('id_imp', $imp_id)
                                    ->where('phase_status', 'Staging')
                                    ->select('start_date', 'end_date')
                                    ->first();

        $current_implementation = Imp_Phase::where('id_imp', $imp_id)
                                    ->where('phase_status', 'Implementation')
                                    ->select('start_date', 'end_date')
                                    ->first();

        $current_migration = Imp_Phase::where('id_imp', $imp_id)
                                    ->where('phase_status', 'Migration')
                                    ->select('start_date', 'end_date')
                                    ->first();

        $current_testing = Imp_Phase::where('id_imp', $imp_id)
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
            $add_log                        = new Imp_Change_Log();
            $add_log->id_imp                = $imp_id;
            if($request['edit_phase_id_engineer'] == null) {
                $add_log->id_engineer_assign    = 1;
            } else {
                $add_log->id_engineer_assign    = $request['edit_phase_id_engineer'];
            }
            $add_log->status                = 'Edit Phase Status - [Design] from "'.$current_design->start_date.' - '.$current_design->end_date.'" to "'.$start_d.' - '.$end_d.'"';
            $add_log->save();
        }
        $design_phase_update = Imp_Phase::where('id_imp', $imp_id)->where('phase_status', 'Design')->first();
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
            $add_log                        = new Imp_Change_Log();
            $add_log->id_imp                = $imp_id;
            if($request['edit_phase_id_engineer'] == null) {
                $add_log->id_engineer_assign    = 1;
            } else {
                $add_log->id_engineer_assign    = $request['edit_phase_id_engineer'];
            }
            $add_log->status                = 'Edit Phase Status - [Staging] from "'.$current_staging->start_date.' - '.$current_staging->end_date.'" to "'.$start_s.' - '.$end_s.'"';
            $add_log->save();
        }
        $staging_phase_update = Imp_Phase::where('id_imp', $imp_id)->where('phase_status', 'Staging')->first();
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
            $add_log                        = new Imp_Change_Log();
            $add_log->id_imp                = $imp_id;
            if($request['edit_phase_id_engineer'] == null) {
                $add_log->id_engineer_assign    = 1;
            } else {
                $add_log->id_engineer_assign    = $request['edit_phase_id_engineer'];
            }
            $add_log->status                = 'Edit Phase Status - [Implementation] from "'.$current_implementation->start_date.' - '.$current_implementation->end_date.'" to "'.$start_i.' - '.$end_i.'"';
            $add_log->save();
        }
        $implementation_phase_update = Imp_Phase::where('id_imp', $imp_id)->where('phase_status', 'Implementation')->first();
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
            $add_log                        = new Imp_Change_Log();
            $add_log->id_imp                = $imp_id;
            if($request['edit_phase_id_engineer'] == null) {
                $add_log->id_engineer_assign    = 1;
            } else {
                $add_log->id_engineer_assign    = $request['edit_phase_id_engineer'];
            }
            $add_log->status                = 'Edit Phase Status - [Migration] from "'.$current_migration->start_date.' - '.$current_migration->end_date.'" to "'.$start_m.' - '.$end_m.'"';
            $add_log->save();
        }
        $migration_phase_update = Imp_Phase::where('id_imp', $imp_id)->where('phase_status', 'Migration')->first();
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
            $add_log                        = new Imp_Change_Log();
            $add_log->id_imp                = $imp_id;
            if($request['edit_phase_id_engineer'] == null) {
                $add_log->id_engineer_assign    = 1;
            } else {
                $add_log->id_engineer_assign    = $request['edit_phase_id_engineer'];
            }
            $add_log->status                = 'Edit Phase Status - [Testing] from "'.$current_testing->start_date.' - '.$current_testing->end_date.'" to "'.$start_t.' - '.$end_t.'"';
            $add_log->save();
        }
        $testing_phase_update = Imp_Phase::where('id_imp', $imp_id)->where('phase_status', 'Testing')->first();
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

        $finish_date = Imp_Phase::where('id_imp', $imp_id)->where('phase_status', $current_phase)->first();
        $finish_date->finish_date = date('Y-m-d');
        $finish_date->update();

        $add_log                        = new Imp_Change_Log();
        $add_log->id_imp                = $imp_id;
        if($request['update_phase_id_engineer'] == null) {
            $add_log->id_engineer_assign    = 1;
        } else {
            $add_log->id_engineer_assign    = $request['update_phase_id_engineer'];
        }
        $add_log->status                = 'Update Phase Status - ['.$request['next_current_phase'].']';
        $add_log->save();

        $update = Imp::where('id', $imp_id)->first();
        $update->current_phase = $request['next_current_phase'];
        $update->update();

        return redirect()->back();

    }

    public function update_leader(Request $request) {

        $imp_id = $request['leader_update_id_imp'];
        
        $update_leader = Imp_Engineer_Assign::where('id_imp', $imp_id)->where('role', 'Project Leader')->first();
        $update_leader->role = 'Member';
        $update_leader->update();

        $selected_leader = $request['project_leader'];
        $on_db_leader = DB::table('tb_imp_engineer_assign')
                                ->select('nik')
                                ->where('nik', $selected_leader)
                                ->first();

        if($selected_leader == optional($on_db_leader)->nik) {
            $update_current_leader = Imp_Engineer_Assign::where('id_imp', $imp_id)->where('nik', $selected_leader)->first();
            $update_current_leader->role = 'Project Leader';
            $update_current_leader->update();
        } else {
            $tambah_leader          = new Imp_Engineer_Assign();
            $tambah_leader->id_imp  = $imp_id;
            $tambah_leader->role    = 'Project Leader';
            $tambah_leader->nik     = $request['project_leader'];
            $tambah_leader->save();
        }

        return redirect()->back();

    }

    public function update_engineer(Request $request) {

        $imp_id = $request['engineer_add_id_imp'];

        $get_member = $request['project_member'];
        foreach($get_member as $gets){
            $member_add          = new Imp_Engineer_Assign();
            $member_add->id_imp  = $imp_id;
            $member_add->role    = 'Member';
            $member_add->nik     = $gets;
            $member_add->save();
        }

        return redirect()->back();

    }

    public function engineer_problem(Request $request) {

        $substr_start_d = substr($request['problem_date'], 0, 10);
        $format_start_d = strtotime($substr_start_d);
        $substr_end_d = substr($request['problem_date'], 13, 10);
        $format_end_d = strtotime($substr_end_d);

        $tambah_problem                         = new Imp_Problem();
        $tambah_problem->id_imp                 = $request['problem_id_imp'];
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

    public function engineer_problem_edit(Request $request) {

        $problem_id = $request['id_problem'];

        $substr_start_d = substr($request['problem_date_edit'], 0, 10);
        $format_start_d = strtotime($substr_start_d);
        $substr_end_d = substr($request['problem_date_edit'], 13, 10);
        $format_end_d = strtotime($substr_end_d);

        $update_problem                         = Imp_Problem::where('id', $problem_id)->first();
        $update_problem->start_date             = date("Y-m-d", $format_start_d);
        $update_problem->end_date               = date("Y-m-d", $format_end_d);
        $update_problem->problem                = $request['problem_input_edit'];
        $update_problem->conture_measure        = $request['measure_input_edit'];
        $update_problem->root_cause             = $request['cause_input_edit'];
        $update_problem->update();

        return redirect()->back();

    }

    public function engineer_progress(Request $request) {

        $tambah_progress                        = new Imp_Detail();
        $tambah_progress->id_imp                = $request['progress_id_imp'];
        $tambah_progress->id_engineer_assign    = $request['progress_id_engineer'];
        $tambah_progress->id_phase              = $request['progress_id_phase'];
        $tambah_progress->progress              = $request['progress_input'];
        $tambah_progress->save();

        return redirect()->back();

    }

    public function engineer_progress_edit(Request $request) {

        $progress_id = $request['id_progress'];

        $update_progress                = Imp_Detail::where('id', $progress_id)->first();
        $update_progress->progress      = $request['edit_progress_input'];
        $update_progress->update();

        return redirect()->back();

    }

    public function project_delete($id) {
    	$delete = Imp::find($id);
    	$delete->delete();

        return redirect()->back()->with('alert', 'Deleted!');
    }

    public function progress_delete($id) {
    	$delete = Imp_Detail::find($id);
    	$delete->delete();

        return redirect()->back()->with('alert', 'Deleted!');
    }

    public function problem_delete($id) {
    	$delete = Imp_Problem::find($id);
    	$delete->delete();

        return redirect()->back()->with('alert', 'Deleted!');
    }

    public function engineer_delete($id) {
    	$delete = Imp_Engineer_Assign::find($id);
    	$delete->delete();

        return redirect()->back()->with('alert', 'Deleted!');
    }

    public function get_data_progress(Request $request) {

        return array(DB::table('tb_imp_detail')
                ->join('tb_imp_engineer_assign', 'tb_imp_engineer_assign.id', '=', 'tb_imp_detail.id_engineer_assign')
                ->join('users', 'users.nik', '=', 'tb_imp_engineer_assign.nik')
                ->join('tb_imp_phase', 'tb_imp_phase.id', '=', 'tb_imp_detail.id_phase')
                ->select('users.name', 'tb_imp_detail.id', 'tb_imp_detail.progress', 'tb_imp_phase.phase_status', 'users.nik', 'tb_imp_detail.created_at')
                ->where('tb_imp_detail.id', $request->id_pro)
                ->get(), $request->id_pro);
    }

    public function get_data_problem(Request $request) {

        return array(DB::table('tb_imp_problem')
        ->join('tb_imp_engineer_assign', 'tb_imp_engineer_assign.id', '=', 'tb_imp_problem.id_engineer_assign')
                ->join('users', 'users.nik', '=', 'tb_imp_engineer_assign.nik')
                ->join('tb_imp_phase', 'tb_imp_phase.id', '=', 'tb_imp_problem.id_phase')
                ->select('tb_imp_problem.start_date', 'tb_imp_problem.end_date', 'tb_imp_problem.id', 'users.name', 'users.nik', 'tb_imp_phase.phase_status', 'tb_imp_problem.problem', 'tb_imp_problem.conture_measure', 'tb_imp_problem.root_cause')
                ->where('tb_imp_problem.id', $request->id_prog)
                ->get(), $request->id_prog);
    }

}
