<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Barang;
use Auth;
use DB;
use App\Cuti;
use App\Task;
use App\User;
use App\CutiDetil;
// use App\Notifications\CutiKaryawan;
use App\Mail\CutiKaryawan;
use Mail;
// use Notification;
use Excel;
use GuzzleHttp\Client;
use App\Messenger;
use App\DetailMessenger;
use PDF;
use Log;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class HRGAController extends Controller
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
    
    public function index()
    {
    	$nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position; 


        if($ter != null){
            $lead = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho')
                ->where('id_territory', $ter)
                ->get();
        } elseif($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $lead = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_solution_design.nik', 'sales_lead_register.status_sho')
                ->where('sales_solution_design.nik', $nik)
                ->get();
        }elseif($div == 'PMO' && $pos == 'MANAGER') {
            $lead = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho')
                ->where('sales_lead_register.result','WIN')
                ->get();
        }elseif($div == 'PMO' && $pos == 'STAFF') {
            $lead = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('tb_pmo','sales_lead_register.lead_id','=','tb_pmo.lead_id')
                ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','tb_pmo.pmo_nik')
                ->where('sales_lead_register.result','WIN')
                ->where('tb_pmo.pmo_nik',$nik)
                ->get();
        }
        elseif($div == 'FINANCE' && $pos == 'MANAGER') {
            $lead = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.nik')
                ->where('sales_lead_register.result','WIN')
                ->get();
        }
        elseif($div == 'FINANCE' && $pos == 'STAFF') {
            $lead = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.nik')
                ->where('sales_lead_register.result','WIN')
                ->get();
        }
        elseif($pos == 'ENGINEER MANAGER') {
            $lead = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.nik','sales_lead_register.status_engineer')
                ->where('sales_lead_register.result','WIN')
                ->where('sales_lead_register.status_sho','PMO')
                ->get();
        }
        elseif($pos == 'ENGINEER STAFF') {
            $lead = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('tb_engineer','sales_lead_register.lead_id','=','tb_engineer.lead_id')
                ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.nik','sales_lead_register.status_engineer')
                ->where('sales_lead_register.result','WIN')
                 ->where('tb_engineer.nik',$nik)
                ->get();
        }
        else {
              $lead = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.nik')
                ->get();
        }

        /*  $presales = DB::table('sales_solution_design')
                    ->join('users','users.nik','=','sales_solution_design.nik')
                    ->select('sales_solution_design.lead_id','sales_solution_design.nik','sales_solution_design.assessment','sales_solution_design.pov','sales_solution_design.pd','sales_solution_design.pb','sales_solution_design.priority','sales_solution_design.project_size','users.name','sales_solution_design.status', 'sales_solution_design.assessment_date', 'sales_solution_design.pd_date', 'sales_solution_design.pov_date')*/
        if ($ter != null) {
            $total_ter = DB::table("sales_lead_register")
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->where('id_territory', $ter)
                        ->sum('amount');
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $total_ter = DB::table("sales_lead_register")
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                        ->where('sales_solution_design.nik', $nik)
                        ->sum('amount');
        }else{
            $total_ter = DB::table("sales_lead_register")
                        ->sum('amount');
        }

        if ($ter != null) {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik','lead_id')
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

        $datas = Barang::orderBy('id_item', 'DESC')->paginate(5);

        $tasks = DB::table('tb_task')
                ->select('id_task','task_name','description','task_date')
                ->first();

        return view('HRGA/hrga', compact('lead', 'total_ter','notif','notifOpen','notifsd','notiftp','id_pro','datas','tasks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_delivery_person()
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position; 

         if ($ter != null) {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik','lead_id')
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

        $data = DB::table('tb_messenger')
                ->join('users','users.nik','=','tb_messenger.nik')
                ->select('book_date','activity','status','pic_name','pic_contact','note','book_time','lokasi','item','users.nik','users.name')
                ->get();

        return view('delivery/delivery_person',compact('notif','notifOpen','notifsd','notiftp','notifClaim','data'));
    }

    public function getDataMessenger(Request $request){

        if ($request->id == 'done') {
             return array("data" => DB::table('tb_messenger')
                ->join('users as u1','u1.nik','=','tb_messenger.nik')
                ->join('users as u2','u2.nik','=','tb_messenger.nik_request')
                ->select('book_date','activity','status','pic_name','pic_contact','note','book_time','lokasi','item','u1.nik','nik_request','u1.name as name1','id_messenger','u2.name as name2','u2.id_division')
                ->where('status','done')
                ->get());
        }else if ($request->id == 'requested') {
             return array("data" => DB::table('tb_messenger')
                ->join('users as u1','u1.nik','=','tb_messenger.nik')
                ->join('users as u2','u2.nik','=','tb_messenger.nik_request')
                ->select('book_date','activity','status','pic_name','pic_contact','note','book_time','lokasi','item','u1.nik','nik_request','u1.name as name1','id_messenger','u2.name as name2','u2.id_division')
                ->where('book_date','!=',date("Y-m-d"))
                ->get());
        }else if ($request->id == 'today') {
             return array("data" => DB::table('tb_messenger')
                ->join('users as u1','u1.nik','=','tb_messenger.nik')
                ->join('users as u2','u2.nik','=','tb_messenger.nik_request')
                ->select('book_date','activity','status','pic_name','pic_contact','note','book_time','lokasi','item','u1.nik','nik_request','u1.name as name1','id_messenger','u2.name as name2','u2.id_division')
                ->where('book_date',date('Y-m-d'))
                ->where('tb_messenger.status','!=','done')
                ->get());
        }

        return array("data" => DB::table('tb_messenger')
                ->join('users as u1','u1.nik','=','tb_messenger.nik')
                ->join('users as u2','u2.nik','=','tb_messenger.nik_request')
                ->select('book_date','activity','status','pic_name','pic_contact','note','book_time','lokasi','item','u1.nik','nik_request','u1.name as name1','id_messenger','u2.name as name2','u2.id_division')
                ->where('book_date',date('Y-m-d'))
                ->where('tb_messenger.status','!=','done')
                ->get());
        
    }

    public function detail_delivery_person($id_messenger)
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position; 

         if ($ter != null) {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik','lead_id')
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

        $datas = DB::table('tb_messenger')
                ->join('users as u1','u1.nik','=','tb_messenger.nik')
                ->join('users as u2','u2.nik','=','tb_messenger.nik_request')
                ->join('tb_detail_messenger','tb_detail_messenger.id_messenger','=','tb_messenger.id_messenger')
                ->select('book_date','activity','pic_name','pic_contact','tb_messenger.note','book_time','lokasi','item','u1.nik','u1.name as name1','u2.name as name2','finish_time','tb_detail_messenger.note as notes','tb_messenger.status as statusm','tb_detail_messenger.status as statusd','tb_messenger.id_messenger')
                ->where('tb_messenger.id_messenger',$id_messenger)
                ->orderBy('tb_detail_messenger.created_at','desc')
                ->first();

        $data = DB::table('tb_messenger')
                ->join('users as u1','u1.nik','=','tb_messenger.nik')
                ->join('users as u2','u2.nik','=','tb_messenger.nik_request')
                ->join('tb_detail_messenger','tb_detail_messenger.id_messenger','=','tb_messenger.id_messenger')
                ->select('book_date','activity','pic_name','pic_contact','tb_messenger.note','book_time','lokasi','item','u1.nik','u1.name as name1','u2.name as name2','finish_time','tb_detail_messenger.note as notes','tb_messenger.status as statusm','tb_detail_messenger.status as statusd','tb_messenger.id_messenger')
                ->where('tb_messenger.id_messenger',$id_messenger)
                ->get();

        return view('delivery/detail_delivery_person',compact('notif','notifOpen','notifsd','notiftp','notifClaim','data','datas'));
    }

    public function getDateMessenger(){
        $getAllCutiDate = DB::table('tb_cuti_detail')
            ->select('date_off')
            ->whereIn('id_cuti',function($query){
                $query->select('id_cuti')
                    ->from('tb_cuti')
                    ->where('nik','=','1171290010')
                    ->orwhere('nik','=','1180400040');
            })
            ->pluck('date_off');

        return collect(["allCutiDate" => $getAllCutiDate]);
    }

    public function getMessenger(Request $request){
        $cek = DB::table('users')
            ->join("tb_messenger","tb_messenger.nik","=","users.nik")
            ->select('name','book_date',DB::raw('COUNT(`tb_messenger`.`book_date`) AS "book_date"'),'users.nik')
            ->whereIn('users.nik',function($query){
                $query->select('users.nik')
                    ->from('tb_messenger');
            })
            ->groupby("users.nik")
            ->groupby("book_date")
            ->where("book_date",$request->tanggal)
            ->get();

        $cek2 = array(DB::table('users')
            ->select('name','nik')
            ->where('id_position','courier')
            ->whereNotIn('nik',function($query){
                $query->select('nik')
                    ->from('tb_messenger');
            })
            ->get());

        $getAllCutiDate = DB::table('tb_cuti_detail')
            ->select('date_off')
            ->whereIn('id_cuti',function($query){
                $query->select('id_cuti')
                    ->from('tb_cuti')
                    ->where('nik','=','1171290010')
                    ->orwhere('nik','=','1180400040');
            })
            ->pluck('date_off');   

        $cutiAll = collect(["allCutiDate" => $getAllCutiDate]);     

        $getOldData = DB::table('tb_messenger')
                ->join('users','users.nik','=','tb_messenger.nik')
                ->select('book_date','activity','status','pic_name','pic_contact','note','book_time','lokasi','item','users.nik','users.name','id_messenger')
                ->where('id_messenger',$request->id_messenger)
                ->get();


        $messenger = DB::table('users')
            ->select('name','nik')
            ->where('id_position','courier')
            ->get();

        if ($cek->isEmpty()) {
            return array($messenger,"courier",$cutiAll,$getOldData);
        }else{
            return array($cek,$cek2,$cutiAll,$getOldData);
        }
       
    }

    public function store_messenger(Request $request){

        $store = new Messenger();
        $format_start_s     = strtotime($request['book_date']);
        $store->book_date   = date("Y-m-d",$format_start_s);
        $store->activity    = $request['activity'];
        $store->status      = 'new';
        $store->pic_name    = $request['pic_name'];
        $store->pic_contact = $request['pic_contact'];
        $store->note        = $request['note'];
        $store->book_time   = $request['book_time'];
        $store->lokasi      = $request['lokasi'];
        $store->item        = $request['items'];
        $store->nik         = $request['messenger_name'];
        $store->nik_request = Auth::User()->nik;
        $store->save();

        $store_detail = new DetailMessenger();
        $store_detail->id_messenger = $store->id_messenger;
        $store_detail->finish_time  = $request['book_time'];
        $store_detail->note         = 'Permintaan plan schedule messenger berhasil di buat';
        $store_detail->status       = 'new';
        $store_detail->save();

        return redirect()->back();

    }

    public function update_messenger(Request $request){

        $update = Messenger::where('id_messenger',$request->id_messenger)->first();
        $format_start_s      = strtotime($request['book_date_edit']);
        $update->book_date   = date("Y-m-d",$format_start_s);
        $update->activity    = $request['activity_edit'];
        $update->status      = 'edited';
        $update->pic_name    = $request['pic_name_edit'];
        $update->pic_contact = $request['pic_contact_edit'];
        $update->note        = $request['note_edit'];
        $update->book_time   = $request['book_time_edit'];
        $update->lokasi      = $request['lokasi_edit'];
        $update->item        = $request['items_edit'];
        $update->nik         = $request['messenger_name_edit'];
        $update->update();

        $update_detail = new DetailMessenger();
        $update_detail->id_messenger = $update->id_messenger;
        $update_detail->status       = "edited";
        $update_detail->note         = 'Terjadi perubahan plan schedule messenger';
        $update_detail->finish_time  = $request['book_time_edit'];
        $update_detail->save();

        return redirect()->back();
    }

    public function update_progress(Request $request){
        if ($request['btn-submit'] == 'done') {
            $update_detail = new DetailMessenger();
            $update_detail->id_messenger = $request->id_messenger;
            $update_detail->status       = "done";
            $update_detail->note         = $request['note_edit'];
            $update_detail->finish_time  = date("H:i:s");
            $update_detail->save();

            $update = Messenger::where('id_messenger',$request->id_messenger)->first();
            $update->status      = 'done';
            $update->update();
        }else{
            $update_detail = new DetailMessenger();
            $update_detail->id_messenger = $request->id_messenger;
            $update_detail->status       = "progress";
            $update_detail->note         = $request['note_edit'];
            $update_detail->finish_time  = date("H:i:s");
            $update_detail->save();

            $update = Messenger::where('id_messenger',$request->id_messenger)->first();
            $update->status      = 'onroad';
            $update->update();
        }
        

        return redirect()->back();
    }

    public function delete_messenger($id_messenger){
        $hapus = Messenger::find($id_messenger);
        $hapus->delete();

        return redirect()->back();
    }

    public function show_cuti(Request $request)
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        $cek = User::join('tb_cuti','tb_cuti.nik','=','users.nik','left')
                ->select('users.nik','cuti','cuti2','status_karyawan','status')->where('users.nik',$nik)->first();

        if ($cek->status == null) {
            $cek_cuti = User::select('users.nik','status_karyawan')->where('users.nik',$nik)->first();
        }else{
            $cek_cuti = User::join('tb_cuti','tb_cuti.nik','=','users.nik','left')
                ->select('users.nik','cuti','cuti2','status_karyawan','status')->where('users.nik',$nik)->orderBy('tb_cuti.id_cuti','desc')->first();
        }

        $total_cuti = $cek->cuti + $cek->cuti2;

        $year = date('Y');

        $cuti_index = '';
        $cuti_list = '';
        $detail_cuti = '';

        if ($ter != NULL) {
            if($div == 'SALES' && $pos == 'MANAGER'){
                $cuti = DB::table('tb_cuti')
                ->join('users','users.nik','=','tb_cuti.nik')
                ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                ->join('tb_position','tb_position.id_position','=','users.id_position')
                ->join('tb_division','tb_division.id_division','=','users.id_division')
                ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.id_position','users.id_territory','tb_cuti.pic','tb_cuti.updated_at')
                ->orderBy('tb_cuti.date_req','DESC')
                ->where('id_territory', $ter)
                ->groupby('id_cuti')
                ->get();


                $cuti2 = DB::table('tb_cuti')
                    ->join('users','users.nik','=','tb_cuti.nik')
                    ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                    ->join('tb_position','tb_position.id_position','=','users.id_position')
                    ->join('tb_division','tb_division.id_division','=','users.id_division')
                    ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_division.id_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.cuti',DB::raw('COUNT(tb_cuti.id_cuti) as niks'),DB::raw('group_concat(date_off) as dates'),'users.id_position','users.email','users.id_territory')
                    ->orderBy('date_req','DESC')
                    ->groupby('tb_cuti.id_cuti')
                    ->where('id_territory', $ter)
                    
                    ->groupby('nik')
                    ->get();
            } elseif($div == 'TECHNICAL' && $pos == 'ENGINEER MANAGER' && $ter == 'DPG'){
                $cuti = DB::table('tb_cuti')
                    ->join('users','users.nik','=','tb_cuti.nik')
                    ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                    ->join('tb_position','tb_position.id_position','=','users.id_position')
                    ->join('tb_division','tb_division.id_division','=','users.id_division')
                    ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.id_position','users.id_territory','tb_cuti.pic','tb_cuti.updated_at')
                    ->groupby('id_cuti')
                    ->orderBy('tb_cuti.date_req','DESC')
                    ->where('users.id_division','TECHNICAL')
                    ->where('users.id_territory','DPG')
                    ->get();

                $cuti2 = DB::table('tb_cuti')
                    ->join('users','users.nik','=','tb_cuti.nik')
                    ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                    ->join('tb_position','tb_position.id_position','=','users.id_position')
                    ->join('tb_division','tb_division.id_division','=','users.id_division')
                    ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_division.id_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.cuti',DB::raw('COUNT(tb_cuti.id_cuti) as niks'),DB::raw('group_concat(date_off) as dates'),'users.id_position','users.email','users.id_territory')
                    ->orderBy('date_req','DESC')
                    ->groupby('tb_cuti.id_cuti')
                    ->where('users.id_division','TECHNICAL')
                    ->where('users.id_territory','DPG')
                    
                    ->groupby('nik')
                    ->get();
            } elseif($div == 'TECHNICAL' && $ter == 'DVG' && $pos == 'MANAGER'){
                $cuti = DB::table('tb_cuti')
                    ->join('users','users.nik','=','tb_cuti.nik')
                    ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                    ->join('tb_position','tb_position.id_position','=','users.id_position')
                    ->join('tb_division','tb_division.id_division','=','users.id_division')
                    ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.id_position','users.id_territory','tb_cuti.pic','tb_cuti.updated_at') 
                    ->where('users.id_division','TECHNICAL')
                    ->where('users.id_territory','DVG')
                    ->orderBy('tb_cuti.date_req','DESC')
                    ->groupby('id_cuti')
                    ->orderBy('id_cuti','desc')
                    ->get();

                $cuti2 = DB::table('tb_cuti')
                    ->join('users','users.nik','=','tb_cuti.nik')
                    ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                    ->join('tb_position','tb_position.id_position','=','users.id_position')
                    ->join('tb_division','tb_division.id_division','=','users.id_division')
                    ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_division.id_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.cuti',DB::raw('COUNT(tb_cuti.id_cuti) as niks'),DB::raw('group_concat(date_off) as dates'),'users.id_position','users.email','users.id_territory')
                    ->orderBy('date_req','DESC')
                    ->groupby('tb_cuti.id_cuti')
                    ->where('users.id_division','TECHNICAL')
                    ->where('users.id_territory','DVG')
                    
                    ->groupby('nik')
                    ->get();

                $detail_cuti = DB::table('tb_cuti')
                            ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                            ->select('date_req','tb_cuti_detail.date_off','tb_cuti_detail.id_cuti')
                            ->where('tb_cuti_detail.id_cuti')
                            ->get();
            } elseif ($div == 'PMO' && $ter == 'OPERATION' && $pos == 'MANAGER') {
                $cuti = DB::table('tb_cuti')
                    ->join('users','users.nik','=','tb_cuti.nik')
                    ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                    ->join('tb_position','tb_position.id_position','=','users.id_position')
                    ->join('tb_division','tb_division.id_division','=','users.id_division')
                    ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.id_position','users.id_territory','tb_cuti.pic','tb_cuti.updated_at') 
                    ->where('users.id_division','PMO')
                    ->orderBy('tb_cuti.date_req','DESC')
                    ->groupby('id_cuti')
                    ->orderBy('id_cuti','desc')
                    ->get();


                $cuti2 = DB::table('tb_cuti')
                    ->join('users','users.nik','=','tb_cuti.nik')
                    ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                    ->join('tb_position','tb_position.id_position','=','users.id_position')
                    ->join('tb_division','tb_division.id_division','=','users.id_division')
                    ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_division.id_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.cuti',DB::raw('COUNT(tb_cuti.id_cuti) as niks'),DB::raw('group_concat(date_off) as dates'),'users.id_position','users.email','users.id_territory')
                    ->orderBy('date_req','DESC')
                    ->groupby('tb_cuti.id_cuti')
                    ->where('users.id_division','PMO')
                    
                    ->groupby('nik')
                    ->get();
            } elseif ($div == 'MSM' && $ter == 'OPERATION' && $pos == 'MANAGER') {
                $cuti = DB::table('tb_cuti')
                    ->join('users','users.nik','=','tb_cuti.nik')
                    ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                    ->join('tb_position','tb_position.id_position','=','users.id_position')
                    ->join('tb_division','tb_division.id_division','=','users.id_division')
                    ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.id_position','users.id_territory','tb_cuti.pic','tb_cuti.updated_at') 
                    ->where('users.id_division','MSM')
                    ->orderBy('tb_cuti.date_req','DESC')
                    ->groupby('id_cuti')
                    ->orderBy('id_cuti','desc')
                    ->get();


                $cuti2 = DB::table('tb_cuti')
                    ->join('users','users.nik','=','tb_cuti.nik')
                    ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                    ->join('tb_position','tb_position.id_position','=','users.id_position')
                    ->join('tb_division','tb_division.id_division','=','users.id_division')
                    ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_division.id_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.cuti',DB::raw('COUNT(tb_cuti.id_cuti) as niks'),DB::raw('group_concat(date_off) as dates'),'users.id_position','users.email','users.id_territory')
                    ->orderBy('date_req','DESC')
                    ->groupby('tb_cuti.id_cuti')
                    ->where('users.id_division','MSM')
                    
                    ->groupby('nik')
                    ->get();
            } elseif ($pos == 'OPERATION DIRECTOR') {
                $cuti = DB::table('tb_cuti')
                    ->join('users','users.nik','=','tb_cuti.nik')
                    ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                    ->join('tb_position','tb_position.id_position','=','users.id_position')
                    ->join('tb_division','tb_division.id_division','=','users.id_division')
                    ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.id_position','users.id_territory','tb_cuti.pic','tb_cuti.updated_at') 
                    ->where('users.id_position','MANAGER')
                    ->where('users.id_territory','OPERATION')
                    ->orwhere('users.id_position','OPERATION DIRECTOR')
                    ->orwhere('users.id_division','WAREHOUSE')
                    ->orderBy('tb_cuti.date_req','DESC')
                    ->groupby('id_cuti')
                    ->orderBy('id_cuti','desc')
                    ->get();


                $cuti2 = DB::table('tb_cuti')
                    ->join('users','users.nik','=','tb_cuti.nik')
                    ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                    ->join('tb_position','tb_position.id_position','=','users.id_position')
                    ->join('tb_division','tb_division.id_division','=','users.id_division')
                    ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_division.id_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.cuti',DB::raw('COUNT(tb_cuti.id_cuti) as niks'),DB::raw('group_concat(date_off) as dates'),'users.id_position','users.email','users.id_territory')
                    ->orderBy('date_req','DESC')
                    ->groupby('tb_cuti.id_cuti')
                    ->where('users.id_position','MANAGER')
                    ->where('users.id_territory','OPERATION')
                    ->orwhere('users.id_position','OPERATION DIRECTOR')
                    ->orwhere('users.id_division','WAREHOUSE')
                    ->groupby('nik')
                    ->get();
            } elseif($div == 'TECHNICAL PRESALES' && $pos == 'MANAGER'){
                $cuti = DB::table('tb_cuti')
                ->join('users','users.nik','=','tb_cuti.nik')
                ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                ->join('tb_position','tb_position.id_position','=','users.id_position')
                ->join('tb_division','tb_division.id_division','=','users.id_division')
                ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.id_position','tb_cuti.pic','tb_cuti.updated_at')
                ->where('users.id_territory','PRESALES')
                ->groupby('id_cuti')
                ->orderBy('id_cuti','desc')
                ->get();


                $cuti2 = DB::table('tb_cuti')
                    ->join('users','users.nik','=','tb_cuti.nik')
                    ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                    ->join('tb_position','tb_position.id_position','=','users.id_position')
                    ->join('tb_division','tb_division.id_division','=','users.id_division')
                    ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_division.id_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.cuti',DB::raw('COUNT(tb_cuti.id_cuti) as niks'),DB::raw('group_concat(date_off) as dates'),'users.id_position','users.email','users.id_territory')
                    ->orderBy('date_req','DESC')
                    ->groupby('tb_cuti.id_cuti')
                    
                    ->where('users.id_territory','PRESALES')
                    ->groupby('nik')
                    ->get();
            } elseif($div == 'FINANCE' && $pos == 'MANAGER'){
                $cuti = DB::table('tb_cuti')
                ->join('users','users.nik','=','tb_cuti.nik')
                ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                ->join('tb_position','tb_position.id_position','=','users.id_position')
                ->join('tb_division','tb_division.id_division','=','users.id_division')
                ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.id_position','users.id_territory','tb_cuti.pic','tb_cuti.updated_at')
                ->where('users.id_division','FINANCE')
                ->groupby('id_cuti')
                ->orderBy('id_cuti','desc')
                ->get();


                $cuti2 = DB::table('tb_cuti')
                    ->join('users','users.nik','=','tb_cuti.nik')
                    ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                    ->join('tb_position','tb_position.id_position','=','users.id_position')
                    ->join('tb_division','tb_division.id_division','=','users.id_division')
                    ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_division.id_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.cuti',DB::raw('COUNT(tb_cuti.id_cuti) as niks'),DB::raw('group_concat(date_off) as dates'),'users.id_position','users.email','users.id_territory')
                    ->orderBy('date_req','DESC')
                    ->groupby('tb_cuti.id_cuti')
                    ->where('users.id_division','FINANCE')
                    ->groupby('nik')
                    
                    ->get();
            }else{
                $cuti = DB::table('tb_cuti')
                ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                ->join('users','users.nik','=','tb_cuti.nik')
                ->join('tb_position','tb_position.id_position','=','users.id_position')
                ->join('tb_division','tb_division.id_division','=','users.id_division')
                ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.id_position','users.id_territory','tb_cuti.pic','tb_cuti.updated_at') 
                ->groupby('id_cuti')
                ->orderBy('tb_cuti.date_req','DESC')
                ->where('users.nik',$nik)
                ->orderBy('id_cuti','desc')
                ->get();

                $cuti2 = DB::table('tb_cuti')
                    ->join('users','users.nik','=','tb_cuti.nik')
                    ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                    ->join('tb_position','tb_position.id_position','=','users.id_position')
                    ->join('tb_division','tb_division.id_division','=','users.id_division')
                    ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_division.id_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.cuti',DB::raw('COUNT(tb_cuti.id_cuti) as niks'),DB::raw('group_concat(date_off) as dates'),'users.id_position','users.email','users.id_territory')
                    ->orderBy('date_req','DESC')
                    ->groupby('tb_cuti.id_cuti')
                    
                    ->where('users.nik',$nik)
                    ->groupby('nik')
                    ->get();

                $detail_cuti = DB::table('tb_cuti')
                            ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                            ->select('date_req','tb_cuti_detail.date_off','tb_cuti_detail.id_cuti')
                            ->where('tb_cuti_detail.id_cuti')
                            ->get();
            }
        }elseif($div == 'TECHNICAL' && $pos == 'MANAGER'){
            $cuti = DB::table('tb_cuti')
            ->join('users','users.nik','=','tb_cuti.nik')
            ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
            ->join('tb_position','tb_position.id_position','=','users.id_position')
            ->join('tb_division','tb_division.id_division','=','users.id_division')
            ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.id_position','users.id_territory','tb_cuti.pic','tb_cuti.updated_at')
            ->where('users.id_division','TECHNICAL')
            ->where('users.id_position','MANAGER')
            ->orwhere('users.id_position','ENGINEER MANAGER')
            ->groupby('id_cuti')
            ->orderBy('id_cuti','desc')
            ->get();


            $cuti2 = DB::table('tb_cuti')
                ->join('users','users.nik','=','tb_cuti.nik')
                ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                ->join('tb_position','tb_position.id_position','=','users.id_position')
                ->join('tb_division','tb_division.id_division','=','users.id_division')
                ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_division.id_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.cuti',DB::raw('COUNT(tb_cuti.id_cuti) as niks'),DB::raw('group_concat(date_off) as dates'),'users.id_position','users.email','users.id_territory')
                ->where('users.id_division','TECHNICAL')
                ->where('users.id_position','MANAGER')
                ->orwhere('users.id_position','ENGINEER MANAGER')
                ->orwhere('users.id_position','MANAGER')
                ->orwhere('users.id_division','WAREHOUSE')
                ->orderBy('date_req','DESC')
                ->groupby('tb_cuti.id_cuti')
                ->where('tb_cuti.status','n')
                ->orWhere('tb_cuti.status','R')
                ->groupby('nik')
                ->get();
        }elseif($div == 'TECHNICAL DVG' && $pos == 'STAFF' || $div == 'TECHNICAL DPG' && $pos == 'ENGINEER STAFF' || $div == 'TECHNICAL PRESALES' && $pos == 'STAFF' || $div == 'FINANCE' && $pos == 'STAFF' || $div == 'PMO' && $pos == 'STAFF' || $pos == 'ADMIN' || $div == 'HR' && $pos == 'STAFF GA' || $div == 'HR' && $pos == 'STAFF HR'){
        	$cuti = DB::table('tb_cuti')
                ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                ->join('users','users.nik','=','tb_cuti.nik')
                ->join('tb_position','tb_position.id_position','=','users.id_position')
                ->join('tb_division','tb_division.id_division','=','users.id_division')
                ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.id_position','users.id_territory','tb_cuti.pic','tb_cuti.updated_at') 
                ->groupby('id_cuti')
                ->orderBy('tb_cuti.date_req','DESC')
                ->where('users.nik',$nik)
                ->orderBy('id_cuti','desc')
                ->get();

            $cuti2 = DB::table('tb_cuti')
                ->join('users','users.nik','=','tb_cuti.nik')
                ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                ->join('tb_position','tb_position.id_position','=','users.id_position')
                ->join('tb_division','tb_division.id_division','=','users.id_division')
                ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_division.id_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.cuti',DB::raw('COUNT(tb_cuti.id_cuti) as niks'),DB::raw('group_concat(date_off) as dates'),'users.id_position','users.email','users.id_territory')
                ->orderBy('date_req','DESC')
                ->groupby('tb_cuti.id_cuti')
                ->where('tb_cuti.status','n')
                ->orWhere('tb_cuti.status','R')
                ->where('users.nik',$nik)
                ->groupby('nik')
                ->get();
        }elseif($div == 'HR' && $pos == 'HR MANAGER'){
	        $cuti = DB::table('tb_cuti')
	            ->join('users','users.nik','=','tb_cuti.nik')
	            ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
	            ->join('tb_position','tb_position.id_position','=','users.id_position')
	            ->join('tb_division','tb_division.id_division','=','users.id_division')
	            ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_division.id_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.cuti',DB::raw('COUNT(tb_cuti.id_cuti) as niks'),DB::raw('group_concat(date_off) as dates'),'users.id_position','users.email','users.id_territory','tb_cuti.pic','tb_cuti.updated_at')
                ->orderBy('date_req','DESC')
	            ->where('status','v')
                ->orwhere('tb_position.id_position','not like', '%STAFF%')
	            ->groupby('tb_cuti.id_cuti')
	            ->groupby('nik')
	            ->get();

            $cuti2 = DB::table('tb_cuti')
                ->join('users','users.nik','=','tb_cuti.nik')
                ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                ->join('tb_position','tb_position.id_position','=','users.id_position')
                ->join('tb_division','tb_division.id_division','=','users.id_division')
                ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_division.id_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.cuti',DB::raw('COUNT(tb_cuti.id_cuti) as niks'),DB::raw('group_concat(date_off) as dates'),'users.id_position','users.email','users.id_territory')
                ->orderBy('date_req','DESC')
                ->groupby('tb_cuti.id_cuti')
                ->where('tb_cuti.status','n')
                ->orWhere('tb_cuti.status','R')
                ->groupby('nik')
                ->get();


	        $cuti_index = DB::table('users')
	            ->join('tb_cuti','tb_cuti.nik','=','users.nik')
                ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
	            ->join('tb_position','tb_position.id_position','=','users.id_position')
	            ->join('tb_division','tb_division.id_division','=','users.id_division')
	            ->select('users.nik','users.date_of_entry','users.name','tb_position.name_position','tb_division.name_division','tb_division.id_division','users.cuti',DB::raw('COUNT(tb_cuti_detail.id_cuti) as niks'),DB::raw('DATEDIFF(NOW(),date_of_entry) AS date_of_entrys'),'users.email','users.cuti2','users.status_karyawan')
	            ->groupby('tb_cuti.nik')
                ->where('status_karyawan','!=','dummy')
	            ->get();

	        $cuti_list = DB::table('users')
	            ->join('tb_position','tb_position.id_position','=','users.id_position')
	            ->join('tb_division','tb_division.id_division','=','users.id_division')
	            ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_division.id_division','users.cuti','users.date_of_entry',DB::raw('DATEDIFF(NOW(),date_of_entry) AS date_of_entrys'),'users.email','users.cuti2','users.status_karyawan')
                ->where('status_karyawan','!=','dummy')
	            ->whereNotIn('nik',function($query) { 
	            	$query->select('nik')->from('tb_cuti');
	            })
	            ->get();

        }elseif ($pos == 'DIRECTOR') {
            $cuti = DB::table('tb_cuti')
                    ->join('users','users.nik','=','tb_cuti.nik')
                    ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                    ->join('tb_position','tb_position.id_position','=','users.id_position')
                    ->join('tb_division','tb_division.id_division','=','users.id_division')
                    ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.id_position','users.id_territory','tb_cuti.pic','tb_cuti.updated_at')
                    ->groupby('id_cuti')
                    ->orderBy('id_cuti','desc')
                    ->where('users.id_position','MANAGER')
                    ->where('users.id_division','!=','MSM')
                    ->orwhere('users.id_position','=','OPERATION DIRECTOR')
                    ->orwhere('users.id_position','=','HR MANAGER')
                    ->get();

            $cuti2 = DB::table('tb_cuti')
                    ->join('users','users.nik','=','tb_cuti.nik')
                    ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                    ->join('tb_position','tb_position.id_position','=','users.id_position')
                    ->join('tb_division','tb_division.id_division','=','users.id_division')
                    ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_division.id_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.cuti',DB::raw('COUNT(tb_cuti.id_cuti) as niks'),DB::raw('group_concat(date_off) as dates'),'users.id_position','users.email','users.id_territory')
                    ->orderBy('date_req','DESC')
                    ->groupby('tb_cuti.id_cuti')
                    ->where('users.id_position','MANAGER')
                    ->where('users.id_division','!=','MSM')
                    ->orwhere('users.id_position','=','OPERATION DIRECTOR')
                    ->orwhere('users.id_position','=','HR MANAGER')
                    ->groupby('nik')
                    ->get();
        }else{
        	$cuti = DB::table('tb_cuti')
                    ->join('users','users.nik','=','tb_cuti.nik')
                    ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                    ->join('tb_position','tb_position.id_position','=','users.id_position')
                    ->join('tb_division','tb_division.id_division','=','users.id_division')
                    ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.id_position','users.id_territory','tb_cuti.pic','tb_cuti.updated_at')
                    ->groupby('id_cuti')
                    ->orderBy('id_cuti','desc')
                    ->get();

            $cuti2 = DB::table('tb_cuti')
                    ->join('users','users.nik','=','tb_cuti.nik')
                    ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                    ->join('tb_position','tb_position.id_position','=','users.id_position')
                    ->join('tb_division','tb_division.id_division','=','users.id_division')
                    ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_division.id_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.cuti',DB::raw('COUNT(tb_cuti.id_cuti) as niks'),DB::raw('group_concat(date_off) as dates'),'users.id_position','users.email','users.id_territory')
                    ->orderBy('date_req','DESC')
                    ->groupby('tb_cuti.id_cuti')
                    
                    ->groupby('nik')
                    ->get();
        }

        $client = new Client();
        $api_response = $client->get('https://www.googleapis.com/calendar/v3/calendars/en.indonesian%23holiday%40group.v.calendar.google.com/events?key='.env('GOOGLE_API_YEY'));
        $json = (string)$api_response->getBody();
        $datas_nasional = json_decode($json, true);  
        // return $datas_nasional;
        // $datas_nasional = [];   

        $bulan = date('F');
        $tahun_ini = date('Y');
        $tahun_lalu = date('Y') - 1;

        if ($ter != null) {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik','lead_id')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik','lead_id')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();
        }else{
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik','lead_id')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();
        }

        if ($div == 'TECHNICAL PRESALES' && $pos == 'MANAGER') {
            $notifOpen= DB::table('sales_lead_register')
            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->select('sales_lead_register.opp_name','sales_solution_design.nik','sales_lead_register.lead_id')
            ->where('result','')
            ->orderBy('sales_lead_register.created_at','desc')
            ->get();
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $notifOpen= DB::table('sales_lead_register')
            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->select('sales_lead_register.opp_name','sales_solution_design.nik','sales_lead_register.lead_id')
            ->where('result','')
            ->orderBy('sales_lead_register.created_at','desc')
            ->get();
        }else{
            $notifOpen= DB::table('sales_lead_register')
            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->select('sales_lead_register.opp_name','sales_solution_design.nik','sales_lead_register.lead_id')
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
        } else {
            $notifClaim = DB::table('dvg_esm')
                            ->select('nik_admin', 'personnel', 'type')
                            ->where('status', 'FINANCE')
                            ->get();
        }

        return view('HR/cuti', compact('notif','notifOpen','notifsd','notiftp','cuti','cuti_index','cuti_list','detail_cuti','notifClaim','cek_cuti','total_cuti','year','datas_nasional','bulan','tahun_ini','tahun_lalu','cuti2','cek'))->with(['initView'=> $this->initMenuBase(),'feature_item'=>$this->RoleDynamic('cuti')]);
    }

    public function detil_cuti(Request $request)
    {
        $cuti = $request->cuti;

        if ($request->pilih == 'date') {
            return array(DB::table('tb_cuti_detail')
                ->join('tb_cuti','tb_cuti.id_cuti','=','tb_cuti_detail.id_cuti')
                ->join('users','users.nik','=','tb_cuti.nik')
                ->select('date_off','reason_leave','date_req','tb_cuti_detail.id_cuti','users.nik')
                ->where('tb_cuti_detail.id_cuti',$cuti)
                ->whereBetween('date_off',array($request->date_start,$request->date_end))
                ->get(),(int)$request->$cuti);
        }else{
            return array(DB::table('tb_cuti_detail')
                ->join('tb_cuti','tb_cuti.id_cuti','=','tb_cuti_detail.id_cuti')
                ->join('users','users.nik','=','tb_cuti.nik')
                ->select('date_off','reason_leave','date_req','tb_cuti_detail.id_cuti','users.nik','decline_reason','status')
                ->where('tb_cuti_detail.id_cuti',$cuti)
                ->get(),(int)$request->$cuti);
        }
        
    }

    public function store_cuti(Request $request)
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position; 
        $company = DB::table('users')->select('id_company')->where('nik',$nik)->first();
        $com = $company->id_company;

        $nik = Auth::User()->nik;
        $date_now = date('Y-m-d');
        
        $array =  explode(',', $_POST['date_start']);

        $hitung = sizeof($array);

    	$tambah = new Cuti();
        $tambah->nik = $nik;
        $tambah->date_req = $date_now;
        $tambah->reason_leave = $request['reason'];
        $tambah->jenis_cuti = $request['jenis_cuti'];
        $tambah->status = 'n';
        $tambah->save();

        foreach ($array as $dates) {
            $store = new CutiDetil();
            $store->id_cuti = $tambah->id_cuti;
            $format_start_s = strtotime($dates);
            $store->date_off = date("Y-m-d",$format_start_s);
            $store->save();
        }

        $id_cuti    = $tambah->id_cuti;
        $getStatus  = Cuti::select('status')->where('id_cuti',$id_cuti)->first();
        $status     = $getStatus->status;

        if ($ter != NULL) {
            if ($pos == 'MANAGER' || $pos == 'ENGINEER MANAGER' || $pos == 'OPERATION DIRECTOR') {
                if ($div == 'PMO' || $div == 'MSM') {
                    $nik_kirim = DB::table('users')->select('users.email')->where('email','nabil@sinergy.co.id')->where('id_company','1')->first();
                } else if ($div == 'FINANCE' || $div == 'SALES' || $div == 'OPERATION') {
                    $nik_kirim = DB::table('users')->select('users.email')->where('email','rony@sinergy.co.id')->where('id_company','1')->first();
                }else{
                    $nik_kirim = DB::table('users')->select('users.email')->where('email','nabil@sinergy.co.id')->where('id_company','1')->first();
                }
            }else if ($ter == 'DPG') {
                $nik_kirim = DB::table('users')->select('users.email')->where('id_position','ENGINEER MANAGER')->where('id_company','1')->first();
            }else if ($div == 'WAREHOUSE'){
                $nik_kirim = DB::table('users')->select('users.email')->where('email','brillyan@sinergy.co.id')->where('id_company','1')->first();
            }else{
                $nik_kirim = DB::table('users')->select('users.email')->where('id_territory',Auth::User()->id_territory)->where('id_position','MANAGER')->where('id_division',Auth::User()->id_division)->where('id_company','1')->first();
            }
        	

            if ($pos == "MANAGER" && $div == "MSM"){
                $kirim = [User::where('email', $nik_kirim->email)->first()->email,'rony@sinergy.co.id'];
            } else {
                $kirim = User::where('email', $nik_kirim->email)->first()->email;
            }
            // $kirim = User::where('email', 'ladinar@sinergy.co.id')->first()->email;

            $name_cuti = DB::table('tb_cuti')
                ->join('users','users.nik','=','tb_cuti.nik')
                ->select('users.name')
                ->where('id_cuti', $id_cuti)->first();

            $hari = DB::table('tb_cuti')
                    ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                    ->select(db::raw('count(tb_cuti_detail.id_cuti) as days'),'tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.status',DB::raw('group_concat(date_off) as dates'))
                    ->groupby('tb_cuti_detail.id_cuti')
                    ->where('tb_cuti.id_cuti', $id_cuti)
                    ->first();

            $ardetil = explode(',',$hari->dates);

            $ardetil_after = "";

            Mail::to($kirim)->send(new CutiKaryawan($name_cuti,$hari,$ardetil,$ardetil_after,'[SIMS-App] Permohonan Cuti'));
            
            
        }else{
            if ($div == 'HR') {
                if($pos == 'HR MANAGER'){
                    $nik_kirim = DB::table('users')->select('users.email')->where('email','rony@sinergy.co.id')->where('id_company','1')->first();
                }else{
                    $nik_kirim = DB::table('users')->select('users.email')->where('id_position','HR MANAGER')->where('id_division',Auth::User()->id_division)->where('id_company','1')->first();
                }
            }else if($pos == 'MANAGER'){
                $nik_kirim = DB::table('users')->select('users.email')->where('email','rony@sinergy.co.id')->where('id_company','1')->first();
            }else{
                $nik_kirim = DB::table('users')->select('users.email')->where('id_position','MANAGER')->where('id_division',Auth::User()->id_division)->where('id_company','1')->first();
            }
        	
    		// $kirim = User::where('email', 'ladinar@sinergy.co.id')->get();
            //

            $name_cuti = DB::table('tb_cuti')
                ->join('users','users.nik','=','tb_cuti.nik')
                ->select('users.name')
                ->where('id_cuti', $id_cuti)->first();

            $hari = DB::table('tb_cuti')
                    ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                    ->select(db::raw('count(tb_cuti_detail.id_cuti) as days'),'tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.status',DB::raw('group_concat(date_off) as dates'))
                    ->groupby('tb_cuti_detail.id_cuti')
                    ->where('tb_cuti.id_cuti', $id_cuti)
                    ->first();

            $ardetil = explode(',',$hari->dates);

            $ardetil_after = "";

            Mail::to($nik_kirim)->send(new CutiKaryawan($name_cuti,$hari,$ardetil,$ardetil_after,'[SIMS-App] Permohonan Cuti'));


        	
        }

        return redirect()->back();
    
    }

    public function approve_cuti(Request $request)
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position; 
        $company = DB::table('users')->select('id_company')->where('nik',$nik)->first();
        $com = $company->id_company;
        
        $id_cuti = $request['id_cuti_detil'];
        $nik = $request['nik_cuti'];

        $update = Cuti::where('id_cuti',$id_cuti)->first();
        $update->decline_reason = $request['reason_reject'];
        $update->pic            = Auth::User()->name;
        $update->updated_at     = date('Y-m-d');
        $update->status = 'v';
        $update->update();

        $array =  explode(',', $_POST['cuti_fix']);

        $delete = CutiDetil::where('id_cuti',$id_cuti)->delete();

        foreach ($array as $dates) {
            $update = new CutiDetil();
            $update->id_cuti = $id_cuti;
            $format_start_s = strtotime($dates);
            $update->date_off = date("Y-m-d",$format_start_s);
            $update->save();
        }

        $hitung = sizeof($array);

        $update_cuti = User::where('nik',$nik)->first();
        
        Log::debug("$hitung = " . $hitung);
        Log::debug("$update_cuti->cuti = " . $update_cuti->cuti);
        if ($hitung >= $update_cuti->cuti) {
            Log::debug("$hitung >= $update_cuti->cuti");

            $ambil2020 = $hitung - $update_cuti->cuti;
            Log::debug("$ambil2020 = " . $ambil2020);            

            $hasilsisa = $update_cuti->cuti2 - $ambil2020;
            Log::debug("$hasilsisa = " . $hasilsisa); 

            if ($ambil2020 == 0) {
                $update_cuti->cuti = $update_cuti->cuti - $hitung;
            }else{
                $update_cuti->cuti = 0;
                $update_cuti->cuti2 = $hasilsisa;
            }

        }else{
            $update_cuti->cuti = $update_cuti->cuti - $hitung;
        }
        
        $update_cuti->update();

        $getStatus  = Cuti::select('status')->where('id_cuti',$id_cuti)->first();
        $status     = $getStatus->status;

        $nik_kirim = DB::table('tb_cuti')->join('users','users.nik','=','tb_cuti.nik')->select('users.email')->where('id_cuti',$id_cuti)->first();
        $kirim = User::where('email',$nik_kirim->email)
                        ->get();

        $name_cuti = DB::table('tb_cuti')
                ->join('users','users.nik','=','tb_cuti.nik')
                ->select('users.name')
                ->where('id_cuti', $id_cuti)->first();

        $hari = DB::table('tb_cuti')
                ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                ->select(db::raw('count(tb_cuti_detail.id_cuti) as days'),'tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.status',DB::raw('group_concat(date_off) as dates'),"decline_reason")
                ->groupby('tb_cuti_detail.id_cuti')
                ->where('tb_cuti.id_cuti', $id_cuti)
                ->first();

        $ardetil = explode(',', $hari->dates); 

        $ardetil_after = "";

        Mail::to($kirim)->cc('elfi@sinergy.co.id')->send(new CutiKaryawan($name_cuti,$hari,$ardetil,$ardetil_after,'[SIMS-App] Approve - Permohonan Cuti'));        

        // Notification::send($kirim, new CutiKaryawan($id_cuti,$status));

        return redirect()->back();
    
    }

    public function decline_cuti(Request $request)
    {
        $id_cuti = $request['id_cuti'];
        $decline_reason = $request['decline_reason'];

        $update = Cuti::where('id_cuti',$id_cuti)->first();
        $update->decline_reason = $decline_reason;
        $update->status         = 'd';
        $update->pic            = Auth::User()->name;
        $update->updated_at     = date('Y-m-d');
        $update->update();

        // $kirim = User::where('email', 'ladinar@sinergy.co.id')->get();
        $nik_kirim = DB::table('tb_cuti')->join('users','users.nik','=','tb_cuti.nik')->select('users.email')->where('id_cuti',$id_cuti)->first();
            //
        $kirim = User::where('email', $nik_kirim->email)->first()->email;

        $name_cuti = DB::table('tb_cuti')
            ->join('users','users.nik','=','tb_cuti.nik')
            ->select('users.name')
            ->where('id_cuti', $id_cuti)->first();

        $hari = DB::table('tb_cuti')
                ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                ->select(db::raw('count(tb_cuti_detail.id_cuti) as days'),'tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.status','tb_cuti.decline_reason',DB::raw('group_concat(date_off) as dates'))
                ->groupby('tb_cuti_detail.id_cuti')
                ->where('tb_cuti.id_cuti', $id_cuti)
                ->first();

        $ardetil = explode(',', $hari->dates); 

        $ardetil_after = "";

        Mail::to($kirim)->send(new CutiKaryawan($name_cuti,$hari,$ardetil,$ardetil_after,'[SIMS-App] Decline - Permohonan Cuti'));

        return redirect()->back();
    
    }

    public function update_cuti(Request $request)
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position; 
        $company = DB::table('users')->select('id_company')->where('nik',$nik)->first();
        $com = $company->id_company;

        $id_cuti = $request['id_cuti'];

        $dates_after = $request['dates_after'];

        $dates_before = $request['dates_before'];

        if ($dates_after == 'kosong') {
            $update = Cuti::where('id_cuti',$id_cuti)->first();
            $update->reason_leave = $request['reason_edit'];
            // $update->status = $request['status_update'];
            $update->update();
        
        }else{
            $array =  explode(',', $dates_after);

            $array2 =  explode(',', $dates_before);

            foreach ($array2 as $dates2) {
                $delete = CutiDetil::where('date_off',$dates2)->where('id_cuti',$id_cuti)->delete();
            }

            foreach ($array as $dates) {
                $add = new CutiDetil();
                $add->id_cuti = $id_cuti;
                $format_start_s = strtotime($dates);
                $add->date_off = date("Y-m-d",$format_start_s);
                $add->save();
            }

            $update = Cuti::where('id_cuti',$id_cuti)->first();
            $update->reason_leave = $request['reason_edit'];
            $update->status = $request['status_update'];
            $update->update();

            if ($ter != NULL) {
                if ($pos == 'MANAGER' || $pos == 'ENGINEER MANAGER' || $pos == 'OPERATION DIRECTOR') {
                    if ($div == 'PMO' || $div == 'MSM') {
                        $nik_kirim = DB::table('users')->select('users.email')->where('email','firman@sinergy.co.id')->where('id_company','1')->first();
                    }else if ($div == 'FINANCE' || $div == 'SALES' || $div == 'OPERATION') {
                        $nik_kirim = DB::table('users')->select('users.email')->where('email','rony@sinergy.co.id')->where('id_company','1')->first();
                    }else{
                        $nik_kirim = DB::table('users')->select('users.email')->where('email','nabil@sinergy.co.id')->where('id_company','1')->first();
                    }
                }else if ($ter == 'DPG') {
                    $nik_kirim = DB::table('users')->select('users.email')->where('id_position','ENGINEER MANAGER')->where('id_company','1')->first();
                }else if ($div == 'WAREHOUSE'){
                    $nik_kirim = DB::table('users')->select('users.email')->where('email','firman@sinergy.co.id')->where('id_company','1')->first();
                }else{
                    $nik_kirim = DB::table('users')->select('users.email')->where('id_territory',Auth::User()->id_territory)->where('id_position','MANAGER')->where('id_division',Auth::User()->id_division)->where('id_company','1')->first();
                }
                
                $kirim = User::where('email', $nik_kirim->email)->first()->email;

                $name_cuti = DB::table('tb_cuti')
                    ->join('users','users.nik','=','tb_cuti.nik')
                    ->select('users.name','status')
                    ->where('id_cuti', $id_cuti)->first();

                $hari = DB::table('tb_cuti')
                        ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                        ->select(db::raw('count(tb_cuti_detail.id_cuti) as days'),'tb_cuti.date_req','tb_cuti.reason_leave',DB::raw("(CASE WHEN (status = 'n') THEN 'R' ELSE status END) as status"),DB::raw('group_concat(date_off) as dates'))
                        ->groupby('tb_cuti_detail.id_cuti')
                        ->where('tb_cuti.id_cuti', $id_cuti)
                        ->first();

                $hari_before = $_POST['dates_before'];

                $ardetil = explode(',',$hari_before);

                $hari_after = $_POST['dates_after'];

                $ardetil_after = explode(',',$hari_after);

                Mail::to($kirim)->send(new CutiKaryawan($name_cuti,$hari,$ardetil,$ardetil_after,'[SIMS-App] Reschedule - Permohonan Cuti'));            
            
            }else{
                if ($div == 'HR') {
                    if($pos == 'HR MANAGER'){
                        $nik_kirim = DB::table('users')->select('users.email')->where('email','rony@sinergy.co.id')->where('id_company','1')->first();
                    }else{
                        $nik_kirim = DB::table('users')->select('users.email')->where('id_position','HR MANAGER')->where('id_division',Auth::User()->id_division)->where('id_company','1')->first();
                    }
                }else if($pos == 'MANAGER'){
                    $nik_kirim = DB::table('users')->select('users.email')->where('email','rony@sinergy.co.id')->where('id_company','1')->first();
                }else{
                    $nik_kirim = DB::table('users')->select('users.email')->where('id_position','MANAGER')->where('id_division',Auth::User()->id_division)->where('id_company','1')->first();
                }
                
                //
                $kirim = User::where('email', $nik_kirim->email)->first()->email;

                $name_cuti = DB::table('tb_cuti')
                    ->join('users','users.nik','=','tb_cuti.nik')
                    ->select('users.name','status')
                    ->where('id_cuti', $id_cuti)->first();

                $hari = DB::table('tb_cuti')
                        ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                        ->select(db::raw('count(tb_cuti_detail.id_cuti) as days'),'tb_cuti.date_req','tb_cuti.reason_leave',DB::raw("(CASE WHEN (status = 'n') THEN 'R' ELSE status END) as status"),DB::raw('group_concat(date_off) as dates'))
                        ->groupby('tb_cuti_detail.id_cuti')
                        ->where('tb_cuti.id_cuti', $id_cuti)
                        ->first();

                $hari_before = $_POST['dates_before'];

                $ardetil = explode(',',$hari_before);

                $hari_after = $_POST['dates_after'];

                $ardetil_after = explode(',',$hari_after);
                
                Mail::to($kirim)->send(new CutiKaryawan($name_cuti,$hari,$ardetil,$ardetil_after,'[SIMS-App] Reschedule - Permohonan Cuti'));
            
            }
        
        }
        return 'success';

        // return redirect()->back();
    }

    public function delete_cuti($id_cuti)
    {
        $hapus = Cuti::find($id_cuti);
        $hapus->delete();

        return redirect()->back();
    }

    public function follow_up($id_cuti)
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position; 
        $company = DB::table('users')->select('id_company')->where('nik',$nik)->first();
        $com = $company->id_company;

        if ($ter != NULL) {
            if ($pos == 'MANAGER' || $pos == 'ENGINEER MANAGER' || $pos == 'OPERATION DIRECTOR') {
                if ($div == 'PMO') {
                    $nik_kirim = DB::table('users')->select('users.email')->where('email','firman@sinergy.co.id')->where('id_company','1')->first();
                }else if ($div == 'FINANCE' || $div == 'SALES' || $div == 'OPERATION') {
                    $nik_kirim = DB::table('users')->select('users.email')->where('email','rony@sinergy.co.id')->where('id_company','1')->first();
                }else{
                    $nik_kirim = DB::table('users')->select('users.email')->where('email','nabil@sinergy.co.id')->where('id_company','1')->first();
                }
            }else if ($ter == 'DPG') {
                $nik_kirim = DB::table('users')->select('users.email')->where('id_position','ENGINEER MANAGER')->where('id_company','1')->first();
            }else if ($div == 'WAREHOUSE'){
                $nik_kirim = DB::table('users')->select('users.email')->where('email','firman@sinergy.co.id')->where('id_company','1')->first();
            }else{
                $nik_kirim = DB::table('users')->select('users.email')->where('id_territory',Auth::User()->id_territory)->where('id_position','MANAGER')->where('id_division',Auth::User()->id_division)->where('id_company','1')->first();
            }
            
            $kirim = User::where('email', $nik_kirim->email)->first()->email;
            // $kirim = User::where('email', 'ladinar@sinergy.co.id')->first()->email;

            $name_cuti = DB::table('tb_cuti')
                ->join('users','users.nik','=','tb_cuti.nik')
                ->select('users.name')
                ->where('id_cuti', $id_cuti)->first();

            $hari = DB::table('tb_cuti')
                    ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                    ->select(db::raw('count(tb_cuti_detail.id_cuti) as days'),'tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.status',DB::raw('group_concat(date_off) as dates'))
                    ->groupby('tb_cuti_detail.id_cuti')
                    ->where('tb_cuti.id_cuti', $id_cuti)
                    ->first();

            $ardetil = explode(',',$hari->dates);

            $ardetil_after = "";

            Mail::to($kirim)->send(new CutiKaryawan($name_cuti,$hari,$ardetil,$ardetil_after,'[SIMS-App] Permohonan Cuti (Follow Up)'));
            
            
        }else{
            if ($div == 'HR') {
                if($pos == 'HR MANAGER'){
                    $nik_kirim = DB::table('users')->select('users.email')->where('email','rony@sinergy.co.id')->where('id_company','1')->first();
                }else{
                    $nik_kirim = DB::table('users')->select('users.email')->where('id_position','HR MANAGER')->where('id_division',Auth::User()->id_division)->where('id_company','1')->first();
                }
            }else if($div == 'MANAGER'){
                $nik_kirim = DB::table('users')->select('users.email')->where('email','rony@sinergy.co.id')->where('id_company','1')->first();
            }else{
                $nik_kirim = DB::table('users')->select('users.email')->where('id_position','MANAGER')->where('id_division',Auth::User()->id_division)->where('id_company','1')->first();
            }
            
            // $kirim = User::where('email', 'ladinar@sinergy.co.id')->get();
            //
            $kirim = User::where('email', $nik_kirim->email)->first()->email;

            $name_cuti = DB::table('tb_cuti')
                ->join('users','users.nik','=','tb_cuti.nik')
                ->select('users.name')
                ->where('id_cuti', $id_cuti)->first();

            $hari = DB::table('tb_cuti')
                    ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                    ->select(db::raw('count(tb_cuti_detail.id_cuti) as days'),'tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.status',DB::raw('group_concat(date_off) as dates'))
                    ->groupby('tb_cuti_detail.id_cuti')
                    ->where('tb_cuti.id_cuti', $id_cuti)
                    ->first();

            $ardetil = explode(',',$hari->dates);

            $ardetil_after = "";

            Mail::to($kirim)->send(new CutiKaryawan($name_cuti,$hari,$ardetil,$ardetil_after,'[SIMS-App] Approve - Permohonan Cuti (Follow Up)'));
        }

        return redirect()->back()->with('success','Cuti Kamu udah di follow up ke Bos! Thanks.');
    }

    public function setting_total_cuti(Request $request)
    {

        if ($request->users == 'all_emp') {
            $nik = User::select('nik','status_karyawan',DB::raw('DATEDIFF(NOW(),date_of_entry) AS date_of_entrys'))->get();

            foreach ($nik as $data) {
                if ($request['pengurangan_cuti'] == NULL) {

                    $update = User::where('nik',$data->nik)->first();
                    if ($data->status_karyawan == 'cuti') {
                        if ($data->date_of_entrys < 365) {
                            $update->cuti = NULL;
                        }else{
                            $update->cuti = $request['set_cuti'];
                        } 
                    }else{
                        $update->cuti = NULL;
                    }
                    $update->update();
                }else{
                    $update = User::where('nik',$data->nik)->first();
                    if ($request['set_cuti'] == NULL) {
                        $update->cuti = $nik->cuti - $request['pengurangan_cuti'];
                    }else{
                        if ($data->status_karyawan == 'cuti') {
                            $update->cuti = $request['set_cuti'] - $request['pengurangan_cuti'];
                        }else{
                            $update->cuti = NULL;
                        }
                    }
                    $update->update();
                }
            }
        }else{
            if ($request['pengurangan_cuti'] == NULL) {
                $update = User::where('nik',$request->users)->first();
                $update->cuti = $request['set_cuti'];
                $update->update();
            }else{
                $update = User::where('nik',$request->users)->first();
                if ($request['set_cuti'] == NULL) {

                    $update->cuti = $nik->cuti - $request['pengurangan_cuti'];
                }else{
                    
                    $update->cuti = $request['set_cuti'] - $request['pengurangan_cuti'];
                }
                
                $update->update();
            }
            
        }

        return redirect()->back();
    }

    public function set_total_cuti(Request $request)
    {

        $nik = User::select('nik','cuti','status_karyawan',DB::raw('DATEDIFF(NOW(),date_of_entry) AS date_of_entrys'))->get();

        foreach ($nik as $data) {
        	$update = User::where('nik',$data->nik)->first();
        	if ($data->status_karyawan == 'cuti') {
		        $update->cuti = $data->cuti - $request['pengurangan_cuti'];
        	}else{
        		$update->cuti = NULL;
        	}
        	$update->update();
        }

        return redirect()->back();
    }

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
        $tambah = new Barang();
        $tambah->id_item = $request['id_item'];
        $tambah->item_name = $request['nama_item'];
        $tambah->quantity = $request['quantity'];
        $tambah->info = $request['info'];
        $tambah->save();

        return view('HRGA/hrga');
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
    public function destroy($id)
    {
        $hapus = Barang::find($id);
                $hapus->delete();

                return redirect()->to('/hrga');
    }

    public function getIdTask()
    {
        return array(DB::table('tb_task')
            ->select('id_task','task_name','description','task_date')
            ->get());
        
    }

    public function getCutiUsers(Request $request){

        $getcuti = User::select(
            'nik',
            DB::raw('DATEDIFF(NOW(),date_of_entry) AS date_of_entrys'),
            DB::raw('(CASE WHEN (cuti IS NULL) THEN 0 ELSE cuti END) as cuti'),
            DB::raw('(CASE WHEN (cuti2 IS NULL) THEN 0 ELSE cuti2 END) as cuti2'),
            DB::raw('sum(cuti + cuti2) AS total_cuti'),
            'date_of_entry'
        )->where('nik',$request->nik)
        ->groupby('users.nik')
        ->get();


        $getAllCutiDate = DB::table('tb_cuti_detail')
            ->select('date_off')
            ->whereIn('id_cuti',function($query){
                $query->select('id_cuti')
                    ->from('tb_cuti')
                    ->where('status', '<>', 'd')
                    ->where('nik','=',Auth::user()->nik);
            })
            ->pluck('date_off');

        return collect(["parameterCuti" => $getcuti[0],"allCutiDate" => $getAllCutiDate]);
    }

    public function getCutiAuth(Request $request){

        $getcuti = User::select('nik','name',DB::raw('DATEDIFF(NOW(),date_of_entry) AS date_of_entrys'),DB::raw('(CASE WHEN (cuti IS NULL) THEN 0 ELSE cuti END) as cuti'),DB::raw('(CASE WHEN (cuti2 IS NULL) THEN 0 ELSE cuti2 END) as cuti2'),DB::raw('sum(cuti + cuti2) AS total_cuti'),'date_of_entry','gambar')->where('nik',Auth::User()->nik)->groupby('users.nik')->get();

        return $getcuti;
    }

    public function CutiExcel(Request $request) {
        $spreadsheet = new Spreadsheet();

        $spreadsheet->removeSheetByIndex(0);
        $spreadsheet->addSheet(new Worksheet($spreadsheet,'Sinergy informasi Pratama'));
        $spreadsheet->addSheet(new Worksheet($spreadsheet,'Multi Solusindo Perkasa'));
        $spreadsheet->addSheet(new Worksheet($spreadsheet,'Detail Report Cuti SIP & MSP'));
        $sipSheet = $spreadsheet->setActiveSheetIndex(0);
        $mspSheet = $spreadsheet->setActiveSheetIndex(1);
        $detailSheet = $spreadsheet->setActiveSheetIndex(2);

        $sipSheet->mergeCells('A1:H1');
        $mspSheet->mergeCells('A1:H1');
        $detailSheet->mergeCells('A1:H1');
        $normalStyle = [
            'font' => [
                'name' => 'Calibri',
                'size' => 11
            ],
        ];

        $titleStyle = $normalStyle;
        $titleStyle['alignment'] = ['horizontal' => Alignment::HORIZONTAL_CENTER];
        $titleStyle['borders'] = ['outline' => ['borderStyle' => Border::BORDER_THIN]];
        $titleStyle['fill'] = ['fillType' => Fill::FILL_SOLID, 'startColor' => ["argb" => "FFFCD703"]];
        $titleStyle['font']['bold'] = true;

        $headerStyle = $normalStyle;
        $headerStyle['font']['bold'] = true;

        $client = new Client();
        $client = $client->get('https://www.googleapis.com/calendar/v3/calendars/en.indonesian%23holiday%40group.v.calendar.google.com/events?key=' . env('GOOGLE_API_KEY'));
        $variable = json_decode($client->getBody())->items;

        $hitung_cuti_bersama = 0;
        foreach ($variable as $key => $value) {
          if(strpos($value->summary,'Cuti Bersama') === 0){
            // echo $value->start->date . "<br>";
            if(strpos($value->start->date ,date('Y')) === 0){
              // echo $value->start->date . " " . strpos($value->summary,'Cuti Bersama') . ' - ' . $value->summary . "<br>";
              $hitung_cuti_bersama++;
            }
          }
        }


        $sipSheet->getStyle('A1:H1')->applyFromArray($titleStyle);
        $sipSheet->setCellValue('A1','Report Cuti SIP');

        $sipCutiData = User::orderBy('users.id_division')
            ->select(
                'users.name',
                DB::raw('CONCAT(12, " Hari") AS `hak_cuti`'),
                DB::raw('CONCAT(' . $hitung_cuti_bersama . ', " Hari") AS `cuti_bersama_' . date('Y') . '`'),
                DB::raw('IF(`tb_cuti_counted`.`cuti_diambil` > 0,CONCAT(`tb_cuti_counted`.`cuti_diambil`, " Hari"),"-") AS `cuti_diambil_' . date('Y') . '`'),
                DB::raw('IF(`users`.`cuti` > 0,CONCAT(`users`.`cuti`, " Hari"),"-") AS `sisah_cuti_' . (date('Y')-1) . '`'),
                DB::raw('IF(`users`.`cuti2` > 0,CONCAT(`users`.`cuti2`, " Hari"),"-") AS `sisah_cuti_' . date('Y') . '`'),
                DB::raw('IF(`users`.`status_karyawan` = "belum_cuti","Belum 1 tahun",IF((`users`.`cuti` + `users`.`cuti2`) = 0,"Habis","-")) AS `status`')
            )
            ->leftJoinSub(DB::table('tb_cuti')
                ->select('nik',DB::raw('COUNT(*) as `cuti_diambil`'))
                ->join('tb_cuti_detail','tb_cuti.id_cuti', '=', 'tb_cuti_detail.id_cuti')
                ->where('status','=','v')
                ->where('date_req','>','2021-03-16')
                ->where('date_req','<','2021-04-15')
                // ->whereYear('date_req',date('Y'))
                ->groupBy('nik')
                ,'tb_cuti_counted','users.nik','=','tb_cuti_counted.nik')
            ->where('users.status_karyawan','!=','dummy')
            ->where('users.id_company','1')
            ->get();

        $headerContent = ["No","Nama Karyawan", "Hak Cuti Tahunan","Cuti Bersama", "Cuti Sudah diambil", "Sisa Cuti ", "Sisa Cuti " . date('Y'),"Status Hak Cuti Karyawan"];
        $sipSheet->getStyle('A2:H2')->applyFromArray($headerStyle);
        $sipSheet->fromArray($headerContent,NULL,'A2');

        $sipCutiData->map(function($item,$key) use ($sipSheet){
            $sipSheet->fromArray(array_merge([$key + 1],array_values($item->toArray())),NULL,'A' . ($key + 3));
        });

        $sipSheet->getColumnDimension('A')->setAutoSize(true);
        $sipSheet->getColumnDimension('B')->setAutoSize(true);
        $sipSheet->getColumnDimension('C')->setAutoSize(true);
        $sipSheet->getColumnDimension('D')->setAutoSize(true);
        $sipSheet->getColumnDimension('E')->setAutoSize(true);
        $sipSheet->getColumnDimension('F')->setAutoSize(true);
        $sipSheet->getColumnDimension('G')->setAutoSize(true);
        $sipSheet->getColumnDimension('H')->setAutoSize(true);

        $mspSheet->getStyle('A1:H1')->applyFromArray($titleStyle);
        $mspSheet->setCellValue('A1','Report Cuti MSP');

        $mspCutiData = User::orderBy('users.id_division')
            ->select(
                'users.name',
                DB::raw('CONCAT(12, " Hari") AS `hak_cuti`'),
                DB::raw('CONCAT(' . $hitung_cuti_bersama . ', " Hari") AS `cuti_bersama_' . date('Y') . '`'),
                DB::raw('IF(`tb_cuti_counted`.`cuti_diambil` > 0,CONCAT(`tb_cuti_counted`.`cuti_diambil`, " Hari"),"-") AS `cuti_diambil_' . date('Y') . '`'),
                DB::raw('IF(`users`.`cuti` > 0,CONCAT(`users`.`cuti`, " Hari"),"-") AS `sisah_cuti_' . (date('Y')-1) . '`'),
                DB::raw('IF(`users`.`cuti2` > 0,CONCAT(`users`.`cuti2`, " Hari"),"-") AS `sisah_cuti_' . date('Y') . '`'),
                DB::raw('IF(`users`.`status_karyawan` = "belum_cuti","Belum 1 tahun",IF((`users`.`cuti` + `users`.`cuti2`) = 0,"Habis","-")) AS `status`')
            )
            ->leftJoinSub(DB::table('tb_cuti')
                ->select('nik',DB::raw('COUNT(*) as `cuti_diambil`'))
                ->join('tb_cuti_detail','tb_cuti.id_cuti', '=', 'tb_cuti_detail.id_cuti')
                ->where('status','=','v')
                ->where('date_req','>','2021-03-16')
                ->where('date_req','<','2021-04-15')
                // ->whereYear('date_req',date('Y'))
                ->groupBy('nik')
                ,'tb_cuti_counted','users.nik','=','tb_cuti_counted.nik')
            ->where('users.status_karyawan','!=','dummy')
            ->where('users.id_company','2')
            ->get();

        $headerContent = ["No","Nama Karyawan", "Hak Cuti Tahunan","Cuti Bersama", "Cuti Sudah diambil", "Sisa Cuti ", "Sisa Cuti " . date('Y'),"Status Hak Cuti Karyawan"];
        $mspSheet->getStyle('A2:H2')->applyFromArray($headerStyle);
        $mspSheet->fromArray($headerContent,NULL,'A2');

        $mspCutiData->map(function($item,$key) use ($mspSheet){
            $mspSheet->fromArray(array_merge([$key + 1],array_values($item->toArray())),NULL,'A' . ($key + 3));
        });

        $mspSheet->getColumnDimension('A')->setAutoSize(true);
        $mspSheet->getColumnDimension('B')->setAutoSize(true);
        $mspSheet->getColumnDimension('C')->setAutoSize(true);
        $mspSheet->getColumnDimension('D')->setAutoSize(true);
        $mspSheet->getColumnDimension('E')->setAutoSize(true);
        $mspSheet->getColumnDimension('F')->setAutoSize(true);
        $mspSheet->getColumnDimension('G')->setAutoSize(true);
        $mspSheet->getColumnDimension('H')->setAutoSize(true);

        $detailSheet->getStyle('A1:H1')->applyFromArray($titleStyle);
        $detailSheet->setCellValue('A1','Detail Report Bulanan Cuti');

        $detailCutiData = Cuti::join('users','users.nik','=','tb_cuti.nik')
            ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
            ->join('tb_position','tb_position.id_position','=','users.id_position')
            ->join('tb_division','tb_division.id_division','=','users.id_division')
            ->join('tb_company','tb_company.id_company','=','users.id_company')
            ->select(
                'users.name',
                'code_company',
                'tb_division.name_division',
                DB::raw('CONCAT(COUNT(`tb_cuti_detail`.`id_cuti`), " Hari") AS `days`'),
                DB::raw('REPLACE(GROUP_CONCAT(`date_off`),"-","/") AS `date_off`'),
                'tb_cuti.date_req',
                DB::raw('CONCAT("[ ",`jenis_cuti`," ]/[ ",`reason_leave`," ]") AS `detail`')
            )
            ->where('status','v')
            // ->whereBetween('date_off',array($request->date_start,$request->date_end))
            ->where('date_off','>','2021-03-16')
            ->where('date_off','<','2021-04-15')
            ->groupby('tb_cuti.id_cuti')
            ->get();

        $headerContent = ["No", "Nama Karyawan","Company", "Division", "Request Cuti", "Date Off", "Tanggal Request", "[Jenis Cuti]/[keterangan]"];
        $detailSheet->getStyle('A2:H2')->applyFromArray($headerStyle);
        $detailSheet->fromArray($headerContent,NULL,'A2');

        $detailCutiData->map(function($item,$key) use ($detailSheet){
            $detailSheet->fromArray(array_merge([$key + 1],array_values($item->toArray())),NULL,'A' . ($key + 3));
        });

        $detailSheet->getColumnDimension('A')->setAutoSize(true);
        $detailSheet->getColumnDimension('B')->setAutoSize(true);
        $detailSheet->getColumnDimension('C')->setAutoSize(true);
        $detailSheet->getColumnDimension('D')->setAutoSize(true);
        $detailSheet->getColumnDimension('E')->setAutoSize(true);
        $detailSheet->getColumnDimension('F')->setAutoSize(true);
        $detailSheet->getColumnDimension('G')->setAutoSize(true);
        $detailSheet->getColumnDimension('H')->setAutoSize(true);

        $fileName = 'Report Cuti SIP & MSP '. date('F') .' '. date('Y') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        
        $writer = new Xlsx($spreadsheet);
        return $writer->save("php://output");
    }

    public function getFilterCom(Request $request)
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        if ($request->id == 'all_lis') {
            $cuti_index = DB::table('users')
                ->join('tb_cuti','tb_cuti.nik','=','users.nik')
                ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                ->join('tb_position','tb_position.id_position','=','users.id_position')
                ->join('tb_division','tb_division.id_division','=','users.id_division')
                ->select('users.nik','users.date_of_entry','users.name','tb_position.name_position','tb_division.name_division','tb_division.id_division','users.cuti',DB::raw('COUNT(tb_cuti_detail.id_cuti) as niks'),DB::raw('DATEDIFF(NOW(),date_of_entry) AS date_of_entrys'),'users.email','users.cuti2','users.status_karyawan')
                ->groupby('tb_cuti.nik')
                ->where('status_karyawan','!=','dummy');

            $cuti_list = DB::table('users')
                ->join('tb_position','tb_position.id_position','=','users.id_position')
                ->join('tb_division','tb_division.id_division','=','users.id_division')
                ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_division.id_division','users.cuti','users.date_of_entry',DB::raw('DATEDIFF(NOW(),date_of_entry) AS date_of_entrys'),'users.email','users.cuti2','users.status_karyawan')
                ->where('status_karyawan','!=','dummy')
                ->whereNotIn('nik',function($query) { 
                    $query->select('nik')->from('tb_cuti');
                });

            if ($request->filter_com == 'all') {
                $cuti_index = $cuti_index->get();
                $cuti_list = $cuti_list->get();
            } else {
                $cuti_index = $cuti_index
                    ->where('users.id_company',$request->filter_com)->get();
                $cuti_list = $cuti_list
                    ->where('users.id_company',$request->filter_com)->get();
            }

            return array("data"=>$cuti_index->merge($cuti_list));
        } elseif ($request->id == 'request') {

            $cuti = Cuti::join('users','users.nik','=','tb_cuti.nik')
                    ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                    ->join('tb_position','tb_position.id_position','=','users.id_position')
                    ->join('tb_division','tb_division.id_division','=','users.id_division')
                    ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_division.id_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.cuti',DB::raw('COUNT(tb_cuti.id_cuti) as niks'),DB::raw('group_concat(date_off) as dates'),'users.id_position','users.email','users.id_territory','tb_cuti.pic','tb_cuti.updated_at')
                    ->orderBy('date_req','DESC')
                    ->groupby('tb_cuti.id_cuti')
                    ->whereMonth('date_req',date('m'))
                    ->whereYear('date_req',date('Y'))
                    ->groupby('nik');

            if ($request->filter_com == 'all') {
                $cuti = $cuti
                        ->where('tb_cuti.status','n')
                        ->get();
            } else {
                if ($ter != NULL) {
                    if($div == 'SALES' && $pos == 'MANAGER'){
                        $cuti = $cuti
                            ->where('users.id_company',$request->filter_com)
                            ->where('id_territory', $ter)
                            ->where('tb_cuti.status','n')
                            ->get();
                    } elseif ($div == 'TECHNICAL' && $pos == 'ENGINEER MANAGER' && $ter == 'DPG') {
                        $cuti = $cuti
                            ->where('users.id_company',$request->filter_com)
                            ->where('users.id_division','TECHNICAL')
                            ->where('users.id_territory','DPG')
                            ->where('tb_cuti.status','n')
                            ->get();
                    } elseif ($div == 'TECHNICAL' && $ter == 'DVG' && $pos == 'MANAGER') {
                        $cuti = $cuti
                            ->where('users.id_company',$request->filter_com)
                            ->whereRaw("(`users`.`id_division` = 'TECHNICAL' AND `users`.`id_territory` = 'DVG'  AND `users`.`id_company` = '1' AND `tb_cuti`.`status` = 'n')")->orwhereRaw("(`users`.`id_position` = 'WAREHOUSE'  AND `users`.`id_company` = '1' AND `tb_cuti`.`status` = 'n')")
                            ->where('tb_cuti.status','n')
                            ->get();
                    } elseif ($div == 'MSM' && $ter == 'OPERATION' && $pos == 'MANAGER') {
                        $cuti = $cuti
                            ->where('users.id_company',$request->filter_com)
                            ->where('users.id_division','MSM')
                            ->where('tb_cuti.status','n')
                            ->get();
                    } elseif ($pos == 'OPERATION DIRECTOR') {
                        $cuti = $cuti
                            ->where('users.id_company',$request->filter_com)
                            ->where('users.id_position','MANAGER')
                            ->where('users.id_territory','OPERATION')
                            ->orwhere('users.id_position','OPERATION DIRECTOR')
                            ->orwhere('users.id_division','WAREHOUSE')
                            ->where('tb_cuti.status','n')
                            ->get();
                    } elseif($div == 'TECHNICAL PRESALES' && $pos == 'MANAGER'){
                        $cuti = $cuti
                            ->where('users.id_company',$request->filter_com)
                            ->where('users.id_territory','PRESALES')
                            ->where('tb_cuti.status','n')
                            ->get();
                    } elseif($div == 'FINANCE' && $pos == 'MANAGER'){
                        $cuti = $cuti
                            ->where('users.id_company',$request->filter_com)
                            ->where('users.id_division','FINANCE')
                            ->where('tb_cuti.status','n')
                            ->get();
                    } else{
                        $cuti = $cuti
                            ->where('users.id_company',$request->filter_com)
                            ->where('users.nik',$nik)
                            ->where('tb_cuti.status','n')
                            ->get();
                    }
                // } elseif ($pos == 'HR MANAGER') {
                //     $cuti = $cuti
                //         ->where('users.id_company',$request->filter_com)
                //         ->where('tb_cuti.status','n')
                //         ->get();
                } elseif ($pos == 'DIRECTOR') {
                    $cuti = $cuti
                        ->where('users.id_company',$request->filter_com)
                        ->whereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'SALES' AND `tb_cuti`.`status` = 'n')")->orwhereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'TECHNICAL' AND `users`.`id_territory` is null AND `tb_cuti`.`status` = 'n')")
                        ->get();
                } elseif($div == 'TECHNICAL DVG' && $pos == 'STAFF' || $div == 'TECHNICAL DPG' && $pos == 'ENGINEER STAFF' || $div == 'TECHNICAL PRESALES' && $pos == 'STAFF' || $div == 'FINANCE' && $pos == 'STAFF' || $div == 'PMO' && $pos == 'STAFF' || $pos == 'ADMIN' || $div == 'HR' && $pos == 'STAFF GA' || $div == 'HR' && $pos == 'STAFF HR'){
                        $cuti = $cuti
                            ->where('users.id_company',$request->filter_com)
                            ->where('users.nik',$nik)
                            ->where('tb_cuti.status','n')
                            ->get();
                } elseif ($div == 'TECHNICAL' && $pos == 'MANAGER') {  
                    $cuti = $cuti
                            ->whereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` != 'SALES' AND `tb_cuti`.`status` = 'n')")->WhereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` != 'FINANCE' AND `tb_cuti`.`status` = 'n') ")->whereRaw('users.id_company',$request->filter_com)                            
                            ->get();
                } else {
                    $cuti = $cuti
                        ->where('users.id_company',$request->filter_com)
                        ->where('tb_cuti.status','n')
                        ->get();
                }
            }

            return array("data"=>$cuti);
        } else if ($request->id == 'report_') {
            $cuti = DB::table('tb_cuti')
                    ->join('users','users.nik','=','tb_cuti.nik')
                    ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                    ->join('tb_position','tb_position.id_position','=','users.id_position')
                    ->join('tb_division','tb_division.id_division','=','users.id_division')
                    ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_division.id_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.cuti','tb_cuti.pic','tb_cuti.updated_at')
                    ->where('date_off', '>=', $request->start)
                    ->where('date_off', '<=', $request->end)
                    ->groupby('tb_cuti.id_cuti');
            if ($request->filter_com != 'all') {
                $cuti = $cuti->where('users.id_company',$request->filter_com);
            } 
            if ($request->division != "alldeh") {
                $cuti = $cuti->where('users.id_division',$request->division);
            }

            return $cuti->get();
        } else {
            $cuti = Cuti::join('users','users.nik','=','tb_cuti.nik')
                    ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                    ->join('tb_position','tb_position.id_position','=','users.id_position')
                    ->join('tb_division','tb_division.id_division','=','users.id_division')
                    ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.id_position','users.id_territory','tb_cuti.pic','tb_cuti.updated_at', 'tb_division.id_division')
                    ->orderBy('tb_cuti.date_req','DESC')
                    ->groupby('id_cuti');

            if ($ter != NULL) {
                if($div == 'SALES' && $pos == 'MANAGER'){
                    $cuti = $cuti
                        ->where('users.id_company',$request->filter_com)
                        ->where('id_territory', $ter)
                        ->get();
                } elseif ($div == 'TECHNICAL' && $pos == 'ENGINEER MANAGER' && $ter == 'DPG') {
                    $cuti = $cuti
                        ->where('users.id_company',$request->filter_com)
                        ->where('users.id_division','TECHNICAL')
                        ->where('users.id_territory','DPG')
                        ->get();
                } elseif ($div == 'TECHNICAL' && $ter == 'DVG' && $pos == 'MANAGER') {
                    $cuti = $cuti
                        ->where('users.id_company',$request->filter_com)
                        ->whereRaw("(`users`.`id_division` = 'TECHNICAL' AND `users`.`id_territory` = 'DVG'  AND `users`.`id_company` = '1')")->orwhereRaw("(`users`.`id_position` = 'WAREHOUSE'  AND `users`.`id_company` = '1')")
                        ->get();
                } elseif ($div == 'MSM' && $ter == 'OPERATION' && $pos == 'MANAGER') {
                    $cuti = $cuti
                        ->where('users.id_company',$request->filter_com)
                        ->where('users.id_division','MSM')
                        ->get();
                } elseif ($pos == 'OPERATION DIRECTOR') {
                    $cuti = $cuti
                        ->where('users.id_company',$request->filter_com)
                        ->where('users.id_position','MANAGER')
                        ->where('users.id_territory','OPERATION')
                        ->orwhere('users.id_position','OPERATION DIRECTOR')
                        ->orwhere('users.id_division','WAREHOUSE')
                        ->get();
                } elseif($div == 'TECHNICAL PRESALES' && $pos == 'MANAGER'){
                    $cuti = $cuti
                        ->where('users.id_company',$request->filter_com)
                        ->where('users.id_territory','PRESALES')
                        ->get();
                } elseif($div == 'FINANCE' && $pos == 'MANAGER'){
                    $cuti = $cuti
                        ->where('users.id_company',$request->filter_com)
                        ->where('users.id_division','FINANCE')
                        ->get();
                }elseif($div == 'PMO' && $pos == 'MANAGER'){
                    $cuti = $cuti
                        ->where('users.id_company',$request->filter_com)
                        ->where('users.id_division','PMO')
                        ->get();
                } else{
                    $cuti = $cuti
                        ->where('users.id_company',$request->filter_com)
                        ->where('users.nik',$nik)
                        ->get();
                }
            } elseif ($div == 'HR' && $pos == 'HR MANAGER' ) {
                $cuti = $cuti
                    ->where('users.id_company',$request->filter_com)
                    ->get();
            } elseif ($pos == 'DIRECTOR') {
                $cuti = $cuti
                    ->where('users.id_company',$request->filter_com)
                    // ->whereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'SALES')")->orWhereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'TECHNICAL' AND `users`.`id_territory` is null) ")
                    ->get();
            } elseif($div == 'TECHNICAL DVG' && $pos == 'STAFF' || $div == 'TECHNICAL DPG' && $pos == 'ENGINEER STAFF' || $div == 'TECHNICAL PRESALES' && $pos == 'STAFF' || $div == 'FINANCE' && $pos == 'STAFF' || $div == 'PMO' && $pos == 'STAFF' || $pos == 'ADMIN' || $div == 'HR' && $pos == 'STAFF GA' || $div == 'HR' && $pos == 'STAFF HR'){
                    $cuti = $cuti
                        ->where('users.id_company',$request->filter_com)
                        ->where('users.nik',$nik)
                        ->get();
            } elseif ($div == 'TECHNICAL' && $pos == 'MANAGER') {  
                $cuti = $cuti
                        ->where('users.id_company',$request->filter_com)
                        ->get();
            } else {
                $cuti = $cuti
                    ->where('users.id_company',$request->filter_com)
                    ->get();
            }

            return array("data"=>$cuti);
        }
        
    }

    public function get_history_cuti(Request $request)
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        $cuti = Cuti::join('users','users.nik','=','tb_cuti.nik')
                    ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                    ->join('tb_position','tb_position.id_position','=','users.id_position')
                    ->join('tb_division','tb_division.id_division','=','users.id_division')
                    ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.id_position','users.id_territory','tb_cuti.pic','tb_cuti.updated_at', 'tb_division.id_division')
                    ->orderBy('tb_cuti.date_req','DESC')
                    ->groupby('id_cuti');

        // return "hahahahaha";


        // return array("data" => Cuti::join('users','users.nik','=','tb_cuti.nik')
        //         ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
        //         ->join('tb_position','tb_position.id_position','=','users.id_position')
        //         ->join('tb_division','tb_division.id_division','=','users.id_division')
        //         ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_division.id_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.cuti',DB::raw('COUNT(tb_cuti.id_cuti) as niks'),DB::raw('group_concat(date_off) as dates'),'users.id_position','users.email','users.id_territory','tb_cuti.pic','tb_cuti.updated_at')
        //         ->where('users.id_division','WAREHOUSE')
        //         ->where('users.id_position','ENGINEER MANAGER')
        //         ->orwhere('users.id_position','MANAGER')
        //         ->orderBy('date_req','DESC')
        //         ->groupby('tb_cuti.id_cuti')
        //         ->where('tb_cuti.status','n')
        //         ->where('users.id_company', '1')
        //         ->groupby('nik')
        //         ->get());

        if ($ter != NULL) {
            if($div == 'SALES' && $pos == 'MANAGER'){
                $cuti = $cuti
                    ->where('id_territory', $ter)
                    ->get();
            } elseif ($div == 'TECHNICAL' && $pos == 'ENGINEER MANAGER' && $ter == 'DPG') {
                $cuti = $cuti
                    ->where('users.id_division','TECHNICAL')
                    ->where('users.id_territory','DPG')
                    ->get();
            } elseif ($div == 'TECHNICAL' && $ter == 'DVG' && $pos == 'MANAGER') {
                $cuti = $cuti
                    ->whereRaw("(`users`.`id_division` = 'TECHNICAL' AND `users`.`id_territory` = 'DVG' AND `users`.`id_company` = '1')")->orwhereRaw("(`users`.`id_position` = 'WAREHOUSE'  AND `users`.`id_company` = '1')")
                    ->get();
            } elseif ($div == 'MSM' && $ter == 'OPERATION' && $pos == 'MANAGER') {
                $cuti = $cuti
                    ->where('users.id_division','MSM')
                    ->get();
            } elseif ($pos == 'OPERATION DIRECTOR') {
                $cuti = $cuti
                    ->where('users.id_position','MANAGER')
                    ->where('users.id_territory','OPERATION')
                    ->orwhere('users.id_position','OPERATION DIRECTOR')
                    ->orwhere('users.id_division','WAREHOUSE')
                    ->get();
            } elseif($div == 'TECHNICAL PRESALES' && $pos == 'MANAGER'){
                $cuti = $cuti
                    ->where('users.id_territory','PRESALES')
                    ->get();
            } elseif($div == 'FINANCE' && $pos == 'MANAGER'){
                $cuti = $cuti
                    ->where('users.id_division','FINANCE')
                    ->get();
            } elseif($div == 'PMO' && $pos == 'MANAGER'){
                $cuti = $cuti
                    ->where('users.id_division','PMO')
                    ->get();
            } else{
                $cuti = $cuti
                    ->where('users.nik',$nik)
                    ->get();
            }
        } elseif ($div == 'HR' && $pos == 'HR MANAGER' ) {
            $cuti = $cuti
                ->get();
        } elseif ($pos == 'DIRECTOR') {
            $cuti = $cuti
                ->whereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'SALES')")->orWhereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'TECHNICAL' AND `users`.`id_territory` is null) ")
                ->get();
        } elseif($div == 'TECHNICAL DVG' && $pos == 'STAFF' || $div == 'TECHNICAL DPG' && $pos == 'ENGINEER STAFF' || $div == 'TECHNICAL PRESALES' && $pos == 'STAFF' || $div == 'FINANCE' && $pos == 'STAFF' || $div == 'PMO' && $pos == 'STAFF' || $pos == 'ADMIN' || $div == 'HR' && $pos == 'STAFF GA' || $div == 'HR' && $pos == 'STAFF HR'){
                $cuti = $cuti
                    ->where('users.nik',$nik)
                    ->get();
        } elseif ($div == 'TECHNICAL' && $pos == 'MANAGER') {  
            $cuti = $cuti
                    ->where('users.id_division','TECHNICAL')
                    ->where('users.id_position','MANAGER')
                    ->orwhere('users.id_position','ENGINEER MANAGER')
                    ->orwhere('users.id_position','MANAGER')
                    ->orwhere('users.id_division','WAREHOUSE')
                    ->get();
        } else {
            $cuti = $cuti
                ->get();
        }

        return array("data"=>$cuti);
    }

    public function get_list_cuti(Request $request)
    {

        $cuti_index = DB::table('users')
            ->join('tb_cuti','tb_cuti.nik','=','users.nik')
            ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
            ->join('tb_position','tb_position.id_position','=','users.id_position')
            ->join('tb_division','tb_division.id_division','=','users.id_division')
            ->select('users.nik','users.date_of_entry','users.name','tb_position.name_position','tb_division.name_division','tb_division.id_division','users.cuti',DB::raw('COUNT(tb_cuti_detail.id_cuti) as niks'),DB::raw('DATEDIFF(NOW(),date_of_entry) AS date_of_entrys'),'users.email','users.cuti2','users.status_karyawan')
            ->groupby('tb_cuti.nik')
            ->where('status_karyawan','!=','dummy')
            ->get();

        $cuti_list = DB::table('users')
            ->join('tb_position','tb_position.id_position','=','users.id_position')
            ->join('tb_division','tb_division.id_division','=','users.id_division')
            ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_division.id_division','users.cuti','users.date_of_entry',DB::raw('DATEDIFF(NOW(),date_of_entry) AS date_of_entrys'),'users.email','users.cuti2','users.status_karyawan')
            ->where('status_karyawan','!=','dummy')
            ->whereNotIn('nik',function($query) { 
                $query->select('nik')->from('tb_cuti');
            })
            ->get();

        return array("data"=>$cuti_index->merge($cuti_list));
    }

    public function get_request_cuti_byMonth(Request $request)
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        if ($ter != NULL) {
            if($div == 'SALES' && $pos == 'MANAGER'){
                return array("data"=>Cuti::join('users','users.nik','=','tb_cuti.nik')
                    ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                    ->join('tb_position','tb_position.id_position','=','users.id_position')
                    ->join('tb_division','tb_division.id_division','=','users.id_division')
                    ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_division.id_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.cuti',DB::raw('COUNT(tb_cuti.id_cuti) as niks'),DB::raw('group_concat(date_off) as dates'),'users.id_position','users.email','users.id_territory','tb_cuti.pic','tb_cuti.updated_at')
                    ->orderBy('date_req','DESC')
                    ->groupby('tb_cuti.id_cuti')
                    ->where('id_territory', $ter)
                    ->where('tb_cuti.status','n')
                    ->groupby('nik')
                    ->get());
            } elseif ($div == 'TECHNICAL' && $pos == 'ENGINEER MANAGER' && $ter == 'DPG') {
                return array("data"=>Cuti::join('users','users.nik','=','tb_cuti.nik')
                    ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                    ->join('tb_position','tb_position.id_position','=','users.id_position')
                    ->join('tb_division','tb_division.id_division','=','users.id_division')
                    ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_division.id_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.cuti',DB::raw('COUNT(tb_cuti.id_cuti) as niks'),DB::raw('group_concat(date_off) as dates'),'users.id_position','users.email','users.id_territory','tb_cuti.pic','tb_cuti.updated_at')
                    ->orderBy('date_req','DESC')
                    ->groupby('tb_cuti.id_cuti')
                    ->where('users.id_division','TECHNICAL')
                    ->where('users.id_territory','DPG')
                    ->where('tb_cuti.status','n')
                    ->groupby('nik')
                    ->get());
            } elseif ($div == 'TECHNICAL' && $ter == 'DVG' && $pos == 'MANAGER') {
                return array("data"=>Cuti::join('users','users.nik','=','tb_cuti.nik')
                    ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                    ->join('tb_position','tb_position.id_position','=','users.id_position')
                    ->join('tb_division','tb_division.id_division','=','users.id_division')
                    ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_division.id_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.cuti',DB::raw('COUNT(tb_cuti.id_cuti) as niks'),DB::raw('group_concat(date_off) as dates'),'users.id_position','users.email','users.id_territory','tb_cuti.pic','tb_cuti.updated_at')
                    ->orderBy('date_req','DESC')
                    ->groupby('tb_cuti.id_cuti')
                    ->whereRaw("(`users`.`id_division` = 'TECHNICAL' AND `users`.`id_territory` = 'DVG'  AND `users`.`id_company` = '1' AND `tb_cuti`.`status` = 'n')")->orwhereRaw("(`users`.`id_position` = 'WAREHOUSE'  AND `users`.`id_company` = '1' AND `tb_cuti`.`status` = 'n')")
                    ->groupby('nik')
                    ->get());
            }elseif ($div == 'PMO' && $ter == 'OPERATION' && $pos == 'MANAGER') {
                return array("data" => Cuti::join('users','users.nik','=','tb_cuti.nik')
                    ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                    ->join('tb_position','tb_position.id_position','=','users.id_position')
                    ->join('tb_division','tb_division.id_division','=','users.id_division')
                    ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_division.id_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.cuti',DB::raw('COUNT(tb_cuti.id_cuti) as niks'),DB::raw('group_concat(date_off) as dates'),'users.id_position','users.email','users.id_territory','tb_cuti.pic','tb_cuti.updated_at')
                    ->orderBy('date_req','DESC')
                    ->groupby('tb_cuti.id_cuti')
                    ->where('users.id_division','PMO')
                    ->where('tb_cuti.status','n')
                    ->groupby('nik')
                    ->get());
            } elseif ($div == 'MSM' && $ter == 'OPERATION' && $pos == 'MANAGER') {
                return array("data" => Cuti::join('users','users.nik','=','tb_cuti.nik')
                    ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                    ->join('tb_position','tb_position.id_position','=','users.id_position')
                    ->join('tb_division','tb_division.id_division','=','users.id_division')
                    ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_division.id_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.cuti',DB::raw('COUNT(tb_cuti.id_cuti) as niks'),DB::raw('group_concat(date_off) as dates'),'users.id_position','users.email','users.id_territory','tb_cuti.pic','tb_cuti.updated_at')
                    ->orderBy('date_req','DESC')
                    ->groupby('tb_cuti.id_cuti')
                    ->where('users.id_division','MSM')
                    ->where('tb_cuti.status','n')
                    ->groupby('nik')
                    ->get());
            } elseif ($pos == 'OPERATION DIRECTOR') {
                return array("data" => Cuti::join('users','users.nik','=','tb_cuti.nik')
                    ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                    ->join('tb_position','tb_position.id_position','=','users.id_position')
                    ->join('tb_division','tb_division.id_division','=','users.id_division')
                    ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_division.id_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.cuti',DB::raw('COUNT(tb_cuti.id_cuti) as niks'),DB::raw('group_concat(date_off) as dates'),'users.id_position','users.email','users.id_territory','tb_cuti.pic','tb_cuti.updated_at')
                    ->orderBy('date_req','DESC')
                    ->groupby('tb_cuti.id_cuti')
                    ->where('users.id_position','MANAGER')
                    ->where('users.id_territory','OPERATION')
                    ->orwhere('users.id_position','OPERATION DIRECTOR')
                    ->orwhere('users.id_division','WAREHOUSE')
                    ->where('tb_cuti.status','n')
                    ->groupby('nik')
                    ->get());
            } elseif($div == 'TECHNICAL PRESALES' && $pos == 'MANAGER'){
                return array("data"=>Cuti::join('users','users.nik','=','tb_cuti.nik')
                    ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                    ->join('tb_position','tb_position.id_position','=','users.id_position')
                    ->join('tb_division','tb_division.id_division','=','users.id_division')
                    ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_division.id_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.cuti',DB::raw('COUNT(tb_cuti.id_cuti) as niks'),DB::raw('group_concat(date_off) as dates'),'users.id_position','users.email','users.id_territory','tb_cuti.pic','tb_cuti.updated_at')
                    ->orderBy('date_req','DESC')
                    ->groupby('tb_cuti.id_cuti')
                    ->where('tb_cuti.status','n')
                    ->where('users.id_territory','PRESALES')
                    ->groupby('nik')
                    ->get());
            } elseif($div == 'FINANCE' && $pos == 'MANAGER'){
                return array("data"=>Cuti::join('users','users.nik','=','tb_cuti.nik')
                    ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                    ->join('tb_position','tb_position.id_position','=','users.id_position')
                    ->join('tb_division','tb_division.id_division','=','users.id_division')
                    ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_division.id_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.cuti',DB::raw('COUNT(tb_cuti.id_cuti) as niks'),DB::raw('group_concat(date_off) as dates'),'users.id_position','users.email','users.id_territory','tb_cuti.pic','tb_cuti.updated_at')
                    ->orderBy('date_req','DESC')
                    ->groupby('tb_cuti.id_cuti')
                    ->where('users.id_division','FINANCE')
                    ->where('tb_cuti.status','n')
                    ->groupby('nik')
                    ->get());
            }else{
                return array("data" => Cuti::join('users','users.nik','=','tb_cuti.nik')
                    ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                    ->join('tb_position','tb_position.id_position','=','users.id_position')
                    ->join('tb_division','tb_division.id_division','=','users.id_division')
                    ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_division.id_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.cuti',DB::raw('COUNT(tb_cuti.id_cuti) as niks'),DB::raw('group_concat(date_off) as dates'),'users.id_position','users.email','users.id_territory','tb_cuti.pic','tb_cuti.updated_at')
                    ->orderBy('date_req','DESC')
                    ->groupby('tb_cuti.id_cuti')
                    ->where('tb_cuti.status','n')
                    ->where('users.nik',$nik)
                    ->groupby('nik')
                    ->get());
            }
        } elseif ($div == 'HR' && $pos == 'HR MANAGER' ) {
            return array("data" => Cuti::join('users','users.nik','=','tb_cuti.nik')
                ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                ->join('tb_position','tb_position.id_position','=','users.id_position')
                ->join('tb_division','tb_division.id_division','=','users.id_division')
                ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_division.id_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.cuti',DB::raw('COUNT(tb_cuti.id_cuti) as niks'),DB::raw('group_concat(date_off) as dates'),'users.id_position','users.email','users.id_territory','tb_cuti.pic','tb_cuti.updated_at')
                ->orderBy('date_req','DESC')
                ->groupby('tb_cuti.id_cuti')
                ->where('tb_cuti.status','n')
                ->groupby('nik')
                ->get());
        } elseif ($pos == 'DIRECTOR') {

            return array("data"=>Cuti::join('users','users.nik','=','tb_cuti.nik')
                    ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                    ->join('tb_position','tb_position.id_position','=','users.id_position')
                    ->join('tb_division','tb_division.id_division','=','users.id_division')
                    ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_division.id_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.cuti',DB::raw('COUNT(tb_cuti.id_cuti) as niks'),DB::raw('group_concat(date_off) as dates'),'users.id_position','users.email','users.id_territory','tb_cuti.pic','tb_cuti.updated_at')
                    ->orderBy('date_req','DESC')
                    ->groupby('tb_cuti.id_cuti')
                    ->whereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'SALES' AND `tb_cuti`.`status` = 'n')")->orwhereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'TECHNICAL' AND `users`.`id_territory` is null AND `tb_cuti`.`status` = 'n')")
                    ->groupby('nik')
                    ->get());
            
        } elseif($div == 'TECHNICAL DVG' && $pos == 'STAFF' || $div == 'TECHNICAL DPG' && $pos == 'ENGINEER STAFF' || $div == 'TECHNICAL PRESALES' && $pos == 'STAFF' || $div == 'FINANCE' && $pos == 'STAFF' || $div == 'PMO' && $pos == 'STAFF' || $pos == 'ADMIN' || $div == 'HR' && $pos == 'STAFF GA' || $div == 'HR' && $pos == 'STAFF HR'){
            return array("data" => Cuti::join('users','users.nik','=','tb_cuti.nik')
                ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                ->join('tb_position','tb_position.id_position','=','users.id_position')
                ->join('tb_division','tb_division.id_division','=','users.id_division')
                ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_division.id_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.cuti',DB::raw('COUNT(tb_cuti.id_cuti) as niks'),DB::raw('group_concat(date_off) as dates'),'users.id_position','users.email','users.id_territory','tb_cuti.pic','tb_cuti.updated_at')
                ->orderBy('date_req','DESC')
                ->groupby('tb_cuti.id_cuti')
                ->where('tb_cuti.status','n')
                ->where('users.nik',$nik)
                ->groupby('nik')
                ->get());
        } elseif ($div == 'TECHNICAL' && $pos == 'MANAGER') {
            return array("data" => Cuti::join('users','users.nik','=','tb_cuti.nik')
                ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                ->join('tb_position','tb_position.id_position','=','users.id_position')
                ->join('tb_division','tb_division.id_division','=','users.id_division')
                ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_division.id_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.cuti',DB::raw('COUNT(tb_cuti.id_cuti) as niks'),DB::raw('group_concat(date_off) as dates'),'users.id_position','users.email','users.id_territory','tb_cuti.pic','tb_cuti.updated_at')
                ->where('users.id_division','WAREHOUSE')
                ->where('users.id_position','ENGINEER MANAGER')
                ->orwhere('users.id_position','MANAGER')
                ->orderBy('date_req','DESC')
                ->groupby('tb_cuti.id_cuti')
                ->where('tb_cuti.status','n')
                ->where('users.id_company', '1')
                ->groupby('nik')
                ->get());
        } else {
            return array("data" => Cuti::join('users','users.nik','=','tb_cuti.nik')
                    ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                    ->join('tb_position','tb_position.id_position','=','users.id_position')
                    ->join('tb_division','tb_division.id_division','=','users.id_division')
                    ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_division.id_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.cuti',DB::raw('COUNT(tb_cuti.id_cuti) as niks'),DB::raw('group_concat(date_off) as dates'),'users.id_position','users.email','users.id_territory','tb_cuti.pic','tb_cuti.updated_at')
                    ->orderBy('date_req','DESC')
                    ->groupby('tb_cuti.id_cuti')
                    ->where('tb_cuti.status','n')
                    ->groupby('nik')
                    ->get());
        }
        
    }

    public function filterByDate(Request $request)
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        if ($ter != NULL) {
            if($div == 'SALES' && $pos == 'MANAGER'){
                $cuti = DB::table('tb_cuti')
                ->join('users','users.nik','=','tb_cuti.nik')
                ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                ->join('tb_position','tb_position.id_position','=','users.id_position')
                ->join('tb_division','tb_division.id_division','=','users.id_division')
                ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_division.id_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.id_position','users.id_territory','tb_cuti.pic','tb_cuti.updated_at')
                ->orderBy('tb_cuti.date_req','DESC')
                ->where('id_territory', $ter)
                ->where('date_off', '>=', $request->start)
                ->where('date_off', '<=', $request->end)
                ->groupby('tb_cuti.id_cuti')
                ->get();
            } elseif ($div == 'TECHNICAL' && $pos == 'ENGINEER MANAGER' && $ter == 'DPG'){
                $cuti = DB::table('tb_cuti')
                    ->join('users','users.nik','=','tb_cuti.nik')
                    ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                    ->join('tb_position','tb_position.id_position','=','users.id_position')
                    ->join('tb_division','tb_division.id_division','=','users.id_division')
                    ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_division.id_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.id_position','users.id_territory','tb_cuti.pic','tb_cuti.updated_at')
                    ->orderBy('tb_cuti.date_req','DESC')
                    ->where('users.id_division','TECHNICAL')
                    ->where('users.id_territory','DPG')
                    ->where('date_off', '>=', $request->start)
                    ->where('date_off', '<=', $request->end)
                    ->groupby('tb_cuti.id_cuti')
                    ->get();
            } elseif($div == 'TECHNICAL' && $ter == 'DVG' && $pos == 'MANAGER'){
                $cuti = DB::table('tb_cuti')
                    ->join('users','users.nik','=','tb_cuti.nik')
                    ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                    ->join('tb_position','tb_position.id_position','=','users.id_position')
                    ->join('tb_division','tb_division.id_division','=','users.id_division')
                    ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_division.id_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.id_position','users.id_territory','tb_cuti.pic','tb_cuti.updated_at') 
                    ->where('users.id_division','TECHNICAL')
                    ->where('users.id_territory','DVG')
                    ->orderBy('tb_cuti.date_req','DESC')
                    ->where('date_off', '>=', $request->start)
                    ->where('date_off', '<=', $request->end)
                    ->groupby('tb_cuti.id_cuti')
                    ->orderBy('id_cuti','desc')
                    ->get();
            } elseif ($div == 'PMO' && $ter == 'OPERATION' && $pos == 'MANAGER') {
                $cuti = DB::table('tb_cuti')
                    ->join('users','users.nik','=','tb_cuti.nik')
                    ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                    ->join('tb_position','tb_position.id_position','=','users.id_position')
                    ->join('tb_division','tb_division.id_division','=','users.id_division')
                    ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_division.id_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.id_position','users.id_territory','tb_cuti.pic','tb_cuti.updated_at') 
                    ->where('users.id_division','PMO')
                    ->orderBy('tb_cuti.date_req','DESC')
                    ->where('date_off', '>=', $request->start)
                    ->where('date_off', '<=', $request->end)
                    ->groupby('tb_cuti.id_cuti')
                    ->orderBy('id_cuti','desc')
                    ->get();
            } elseif ($div == 'MSM' && $ter == 'OPERATION' && $pos == 'MANAGER') {
                $cuti = DB::table('tb_cuti')
                    ->join('users','users.nik','=','tb_cuti.nik')
                    ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                    ->join('tb_position','tb_position.id_position','=','users.id_position')
                    ->join('tb_division','tb_division.id_division','=','users.id_division')
                    ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_division.id_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.id_position','users.id_territory','tb_cuti.pic','tb_cuti.updated_at') 
                    ->where('users.id_division','MSM')
                    ->orderBy('tb_cuti.date_req','DESC')
                    ->where('date_off', '>=', $request->start)
                    ->where('date_off', '<=', $request->end)
                    ->groupby('tb_cuti.id_cuti')
                    ->orderBy('id_cuti','desc')
                    ->get();
            } elseif ($pos == 'OPERATION DIRECTOR') {
                $cuti = DB::table('tb_cuti')
                    ->join('users','users.nik','=','tb_cuti.nik')
                    ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                    ->join('tb_position','tb_position.id_position','=','users.id_position')
                    ->join('tb_division','tb_division.id_division','=','users.id_division')
                    ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_division.id_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.id_position','users.id_territory','tb_cuti.pic','tb_cuti.updated_at') 
                    ->where('users.id_position','MANAGER')
                    ->where('users.id_territory','OPERATION')
                    ->orwhere('users.id_position','OPERATION DIRECTOR')
                    ->orwhere('users.id_division','WAREHOUSE')
                    ->where('date_off', '>=', $request->start)
                    ->where('date_off', '<=', $request->end)
                    ->orderBy('tb_cuti.date_req','DESC')
                    ->groupby('tb_cuti.id_cuti')
                    ->orderBy('id_cuti','desc')
                    ->get();
            } elseif($div == 'TECHNICAL PRESALES' && $pos == 'MANAGER'){
                $cuti = DB::table('tb_cuti')
                ->join('users','users.nik','=','tb_cuti.nik')
                ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                ->join('tb_position','tb_position.id_position','=','users.id_position')
                ->join('tb_division','tb_division.id_division','=','users.id_division')
                ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_division.id_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.id_position','tb_cuti.pic','tb_cuti.updated_at')
                ->where('users.id_territory','PRESALES')
                ->where('date_off', '>=', $request->start)
                ->where('date_off', '<=', $request->end)
                ->groupby('tb_cuti.id_cuti')
                ->orderBy('id_cuti','desc')
                ->get();
            } elseif($div == 'FINANCE' && $pos == 'MANAGER'){
                $cuti = DB::table('tb_cuti')
                ->join('users','users.nik','=','tb_cuti.nik')
                ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                ->join('tb_position','tb_position.id_position','=','users.id_position')
                ->join('tb_division','tb_division.id_division','=','users.id_division')
                ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_division.id_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.id_position','users.id_territory','tb_cuti.pic','tb_cuti.updated_at')
                ->where('users.id_division','FINANCE')
                ->where('date_off', '>=', $request->start)
                ->where('date_off', '<=', $request->end)
                ->groupby('tb_cuti.id_cuti')
                ->orderBy('id_cuti','desc')
                ->get();
            } else{
                $cuti = DB::table('tb_cuti')
                ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                ->join('users','users.nik','=','tb_cuti.nik')
                ->join('tb_position','tb_position.id_position','=','users.id_position')
                ->join('tb_division','tb_division.id_division','=','users.id_division')
                ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_division.id_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.id_position','users.id_territory','tb_cuti.pic','tb_cuti.updated_at') 
                ->groupby('tb_cuti.id_cuti')
                ->orderBy('tb_cuti.date_req','DESC')
                ->where('users.nik',$nik)
                ->where('date_off', '>=', $request->start)
                ->where('date_off', '<=', $request->end)
                ->orderBy('id_cuti','desc')
                ->get();
            }
        } elseif($div == 'TECHNICAL' && $pos == 'MANAGER'){
            $cuti = DB::table('tb_cuti')
            ->join('users','users.nik','=','tb_cuti.nik')
            ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
            ->join('tb_position','tb_position.id_position','=','users.id_position')
            ->join('tb_division','tb_division.id_division','=','users.id_division')
            ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_division.id_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.id_position','users.id_territory','tb_cuti.pic','tb_cuti.updated_at')
            ->where('users.id_division','TECHNICAL')
            ->where('users.id_position','MANAGER')
            ->orwhere('users.id_position','ENGINEER MANAGER')
            ->where('date_off', '>=', $request->start)
            ->where('date_off', '<=', $request->end)
            ->groupby('tb_cuti.id_cuti')
            ->orderBy('id_cuti','desc')
            ->get();
        } elseif($div == 'TECHNICAL DVG' && $pos == 'STAFF' || $div == 'TECHNICAL DPG' && $pos == 'ENGINEER STAFF' || $div == 'TECHNICAL PRESALES' && $pos == 'STAFF' || $div == 'FINANCE' && $pos == 'STAFF' || $div == 'PMO' && $pos == 'STAFF' || $pos == 'ADMIN' || $div == 'HR' && $pos == 'STAFF GA' || $div == 'HR' && $pos == 'STAFF HR'){
                $cuti = DB::table('tb_cuti')
                    ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                    ->join('users','users.nik','=','tb_cuti.nik')
                    ->join('tb_position','tb_position.id_position','=','users.id_position')
                    ->join('tb_division','tb_division.id_division','=','users.id_division')
                    ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_division.id_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.id_position','users.id_territory','tb_cuti.pic','tb_cuti.updated_at') 
                    ->groupby('tb_cuti.id_cuti')
                    ->orderBy('tb_cuti.date_req','DESC')
                    ->where('users.nik',$nik)
                    ->where('date_off', '>=', $request->start)
                    ->where('date_off', '<=', $request->end)
                    ->orderBy('id_cuti','desc')
                    ->get();
        } elseif ($div == 'HR' && $pos == 'HR MANAGER') {
            $cuti = DB::table('tb_cuti')
                ->join('users','users.nik','=','tb_cuti.nik')
                ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                ->join('tb_position','tb_position.id_position','=','users.id_position')
                ->join('tb_division','tb_division.id_division','=','users.id_division')
                ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_division.id_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.cuti','tb_cuti.pic','tb_cuti.updated_at')
                // ->where('status','v')
                ->where('date_off', '>=', $request->start)
                ->where('date_off', '<=', $request->end)
                ->groupby('tb_cuti.id_cuti')
                ->get();
        } elseif ($pos == 'DIRECTOR') {
            $cuti = DB::table('tb_cuti')
                    ->join('users','users.nik','=','tb_cuti.nik')
                    ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                    ->join('tb_position','tb_position.id_position','=','users.id_position')
                    ->join('tb_division','tb_division.id_division','=','users.id_division')
                    ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_division.id_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.id_position','users.id_territory','tb_cuti.pic','tb_cuti.updated_at')
                    ->groupby('tb_cuti.id_cuti')
                    ->orderBy('id_cuti','desc')
                    ->where('users.id_position','MANAGER')
                    ->where('users.id_division','!=','MSM')
                    ->orwhere('users.id_position','=','OPERATION DIRECTOR')
                    ->orwhere('users.id_position','=','HR MANAGER')
                    ->where('date_off', '>=', $request->start)
                    ->where('date_off', '<=', $request->end)
                    ->get();
        } else {
            $cuti = DB::table('tb_cuti')
                    ->join('users','users.nik','=','tb_cuti.nik')
                    ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                    ->join('tb_position','tb_position.id_position','=','users.id_position')
                    ->join('tb_division','tb_division.id_division','=','users.id_division')
                    ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_division.id_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.id_position','users.id_territory','tb_cuti.pic','tb_cuti.updated_at')
                    ->groupby('tb_cuti.id_cuti')
                    ->where('date_off', '>=', $request->start)
                    ->where('date_off', '<=', $request->end)
                    ->orderBy('id_cuti','desc')
                    ->get();
        }

	    return $cuti;
    }

    public function filterByDateDiv(Request $request)
    {
    	if ($request->division == 'alldeh'){
    		$cuti = DB::table('tb_cuti')
	            ->join('users','users.nik','=','tb_cuti.nik')
	            ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
	            ->join('tb_position','tb_position.id_position','=','users.id_position')
	            ->join('tb_division','tb_division.id_division','=','users.id_division')
	            ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_division.id_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.cuti','tb_cuti.pic','tb_cuti.updated_at')
	            // ->where('status','v')
	            ->where('date_off', '>=', $request->start)
                ->where('date_off', '<=', $request->end)
	            ->groupby('tb_cuti.id_cuti')
	            ->get();
    	}else{
    		$cuti = DB::table('tb_cuti')
	            ->join('users','users.nik','=','tb_cuti.nik')
	            ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
	            ->join('tb_position','tb_position.id_position','=','users.id_position')
	            ->join('tb_division','tb_division.id_division','=','users.id_division')
	            ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_division.id_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.cuti','tb_cuti.pic','tb_cuti.updated_at')
	            // ->where('status','v')
	            ->where('tb_division.id_division',$request->division)
	            ->where('date_off', '>=', $request->start)
                ->where('date_off', '<=', $request->end)
	            ->groupby('tb_cuti.id_cuti')
	            ->get();
    	}
    	

	    return $cuti;
    }

    public function filterByDiv(Request $request)
    {

    	if ($request->division == 'alldeh') {
    		$cuti = DB::table('tb_cuti')
	            ->join('users','users.nik','=','tb_cuti.nik')
	            ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
	            ->join('tb_position','tb_position.id_position','=','users.id_position')
	            ->join('tb_division','tb_division.id_division','=','users.id_division')
	            ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_division.id_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.cuti')
	            // ->where('status','v')
	            ->whereYear('date_req',date('Y'))
	            ->groupby('tb_cuti.id_cuti')
	            ->get();
    	}else{
    		$cuti = DB::table('tb_cuti')
	            ->join('users','users.nik','=','tb_cuti.nik')
	            ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
	            ->join('tb_position','tb_position.id_position','=','users.id_position')
	            ->join('tb_division','tb_division.id_division','=','users.id_division')
	            ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_division.id_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason',DB::raw('COUNT(tb_cuti_detail.id_cuti) as days'),'users.cuti')
	            // ->where('status','v')
	            ->whereYear('date_req',date('Y'))
	            ->where('tb_division.id_division',$request->division)
	            ->groupby('tb_cuti.id_cuti')
	            ->get();
    	}
    	

	    return $cuti;
    }

    public function cutipdf(Request $request)
    {
         $year = date('Y');

        $cuti_index = DB::table('users')
                ->join('tb_cuti','tb_cuti.nik','=','users.nik')
                ->join('tb_cuti_detail','tb_cuti_detail.id_cuti','=','tb_cuti.id_cuti')
                ->join('tb_position','tb_position.id_position','=','users.id_position')
                ->join('tb_division','tb_division.id_division','=','users.id_division')
                ->select('users.nik','users.date_of_entry','users.name','tb_position.name_position','tb_division.name_division','tb_division.id_division','users.cuti',DB::raw('COUNT(tb_cuti_detail.id_cuti) as niks'),DB::raw('DATEDIFF(NOW(),date_of_entry) AS date_of_entrys'))
                ->groupby('tb_cuti.nik')
                ->get();

        $cuti_list = DB::table('users')
            ->join('tb_position','tb_position.id_position','=','users.id_position')
            ->join('tb_division','tb_division.id_division','=','users.id_division')
            ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_division.id_division','users.cuti','users.date_of_entry',DB::raw('DATEDIFF(NOW(),date_of_entry) AS date_of_entrys'))
            ->whereNotIn('nik',function($query) { 
                $query->select('nik')->from('tb_cuti');
            })
            ->get();

        /*$pdf = PDF::loadView('HR.cuti_pdf', compact('cuti_index', 'cuti_list', 'year'));
        return $pdf->download('report_cuti-'.date("d-m-Y").'.pdf');*/
        return view('HR.cuti_pdf', compact('cuti_index', 'cuti_list', 'year'));
    }

}
