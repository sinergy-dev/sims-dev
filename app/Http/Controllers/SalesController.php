<?php

namespace App\Http\Controllers;

use Illuminate\Console\Scheduling\Schedule;

use Illuminate\Http\Request;
use App\Sales;
use App\User;
use DB;
use App\TenderProcess;
use Illuminate\Support\Collection;
use Auth;
use Month;
use PDF;
use Excel;
use App\solution_design;
use App\TB_Contact;
use App\Quote;
use App\SalesProject;
use App\PMO;
use App\PMOProgress;
use App\SalesHandover;
use App\PID;

use App\SalesChangeLog;
use App\Detail_IdProject;
use App\POCustomer;
use App\Mail\MailResult;
use App\Mail\mailPID;


use Mail;
use App\Notifications\NewLead;
use App\Notifications\PresalesAssign;
use App\Notifications\PresalesReAssign;
use App\Notifications\RaiseToTender;
use App\Notifications\Result;
use Notification;

use App\PIDRequest;
use App\QuoteMSP;



class SALESController extends Controller
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
    
    public function index(Request $request)
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

        $users = DB::table('users')
                    ->select('nik','name','id_division')
                    ->where('id_division','PMO')
                    ->get();
        $users = $users->toArray();

        $cek_note = Sales::count('keterangan');

        $dates = Date('Y');

        // $cek_initial = Sales::where('year',$dates)->where('result','OPEN')->count('result');
        
        $year = DB::table('sales_lead_register')->select('year')->where('year','!=',NULL)->groupBy('year')->get();

        $lead_id = $request['lead_id_edit'];

        $owner_by_lead = DB::table('sales_lead_register')
                        ->select('nik')
                        ->where('lead_id',$lead_id)
                        ->first();

        if($ter != null){
        	if ($pos == 'ENGINEER MANAGER') {
                $lead = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho', 'sales_lead_register.status_handover','sales_lead_register.nik','sales_lead_register.status_engineer', 'sales_lead_register.closing_date', 'sales_lead_register.keterangan','sales_lead_register.deal_price')
                    ->where('sales_lead_register.result','WIN')
                    ->where('sales_lead_register.status_sho','PMO')
                    ->where('users.id_company','1')
                    ->get();

                 $leads = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho', 'sales_lead_register.status_handover','sales_lead_register.nik','sales_lead_register.status_engineer', 'sales_lead_register.closing_date', 'sales_lead_register.keterangan','sales_lead_register.deal_price')
                    ->where('sales_lead_register.result','WIN')
                    ->where('sales_lead_register.status_sho','PMO')
                    ->where('users.id_company','1')
                    ->where('year',$dates)
                    ->get();
	        
            } elseif ($pos == 'ENGINEER STAFF') {
	            $lead = DB::table('sales_lead_register')
	                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
	                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
	                ->join('tb_engineer','sales_lead_register.lead_id','=','tb_engineer.lead_id')
	                ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho', 'sales_lead_register.status_handover','sales_lead_register.nik','sales_lead_register.status_engineer', 'sales_lead_register.closing_date', 'sales_lead_register.keterangan','sales_lead_register.deal_price')
	                ->where('sales_lead_register.result','WIN')
	                ->where('tb_engineer.nik',$nik)
                    ->where('users.id_company','1')
	                ->get();

                $leads = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->join('tb_engineer','sales_lead_register.lead_id','=','tb_engineer.lead_id')
                    ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho', 'sales_lead_register.status_handover','sales_lead_register.nik','sales_lead_register.status_engineer', 'sales_lead_register.closing_date', 'sales_lead_register.keterangan','sales_lead_register.deal_price')
                    ->where('sales_lead_register.result','WIN')
                    ->where('tb_engineer.nik',$nik)
                    ->where('users.id_company','1')
                    ->where('year',$dates)
                    ->get();

	        } elseif ($div == 'FINANCE') {
                $lead = DB::table('tb_pid')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_pid.lead_id')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('tb_pid.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.nik', 'sales_lead_register.closing_date','sales_lead_register.keterangan','sales_lead_register.deal_price','id_company','tb_pid.no_po')
                    ->where('year',$dates)
                    ->whereIn('tb_pid.lead_id',function($query) { 
                        $query->select('tb_pid.lead_id')->from('sales_lead_register');
                    })
                    ->get();

                $leads = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.nik', 'sales_lead_register.closing_date', 'sales_lead_register.keterangan','sales_lead_register.deal_price','sales_lead_register.year','id_company')
                    ->where('sales_lead_register.result','WIN')
                    ->where('year',$dates)
                    ->whereNotIn('lead_id',function($query) { 
                        $query->select('lead_id')->from('tb_pid');
                    })
                    ->get();
               
	        } elseif ($pos == 'OPERATION DIRECTOR' || $div == 'MSM' && $pos == 'MANAGER') {
                $lead = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                        ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.keterangan', 'sales_lead_register.closing_date', 'sales_lead_register.keterangan','sales_lead_register.deal_price','sales_lead_register.year')
                        ->where('result','!=','hmm')
                        ->where('id_company','1')
                        ->get();

                $leads = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'sales_lead_register.nik', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.keterangan','sales_lead_register.year','sales_lead_register.closing_date', 'sales_lead_register.keterangan','sales_lead_register.deal_price','sales_lead_register.year')
                ->whereYear('sales_lead_register.created_at', '=', $dates-1)
                ->orwhere('year',$dates)
                ->where('id_company','1')
                ->where('result','!=','hmm')
                ->get();

                $leadsnow = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.nik', 'sales_lead_register.closing_date', 'sales_lead_register.keterangan','sales_lead_register.deal_price','sales_lead_register.year')
                    ->where('sales_lead_register.result','WIN')
                    ->whereYear('sales_lead_register.created_at', '=', $dates-1)
                    ->orwhere('year',$dates)
                    ->where('id_company','1')
                    ->get();

                $total_lead = count($leadsnow);

                $total_open = DB::table('sales_lead_register')
                            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                            ->where('sales_lead_register.result','')
                            ->where('year',$dates)
                            ->where('id_company','1')
                            ->count('lead_id');

                $total_sd = DB::table('sales_lead_register')
                            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                            ->where('sales_lead_register.result','SD')
                            ->where('year',$dates)
                            ->where('id_company','1')
                            ->count('lead_id');

                $total_tp = DB::table('sales_lead_register')
                            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                            ->where('sales_lead_register.result','TP')
                            ->where('year',$dates)
                            ->where('id_company','1')
                            ->count('lead_id');

                $total_win = DB::table('sales_lead_register')
                            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                            ->where('sales_lead_register.result','WIN')
                            ->where('year',$dates)
                            ->where('id_company','1')
                            ->count('lead_id');

                $total_lose = DB::table('sales_lead_register')
                            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                            ->where('sales_lead_register.result','LOSE')
                            ->where('year',$dates)
                            ->where('id_company','1')
                            ->count('lead_id');

            } elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
                
                $lead = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                    ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name',
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_solution_design.nik', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.keterangan', 'sales_lead_register.closing_date', 'sales_lead_register.keterangan','sales_lead_register.deal_price')
                    ->where('sales_solution_design.nik', $nik)
                    ->where('users.id_company','1')
                    ->get();

                $leads = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.nik','sales_lead_register.status_engineer','sales_lead_register.keterangan','sales_lead_register.year', 
                    'sales_lead_register.closing_date', 'sales_lead_register.keterangan','sales_lead_register.deal_price')
                ->where('sales_solution_design.nik', $nik)
                ->where('users.id_company','1')
                ->where('result','!=','hmm')
                ->get();

                $leadsnow = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.nik','sales_lead_register.status_engineer','sales_lead_register.keterangan','sales_lead_register.year', 
                    'sales_lead_register.closing_date', 'sales_lead_register.keterangan','sales_lead_register.deal_price','sales_lead_register.year')
                ->where('sales_solution_design.nik', $nik)
                ->where('users.id_company','1')
                ->whereYear('sales_lead_register.created_at', '=', $dates-1)
                ->orwhere('year',$dates)
                ->where('result','!=','hmm')
                ->get();

                $total_lead = count($leads);

                $total_open = DB::table('sales_lead_register')
                            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                            ->where('sales_lead_register.result','')
                            ->where('sales_solution_design.nik', $nik)
                            ->where('sales_lead_register.year',$dates)
                            ->count('sales_lead_register.lead_id');

                $total_sd = DB::table('sales_lead_register')
                            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                            ->where('sales_lead_register.result','SD')
                            ->where('sales_solution_design.nik', $nik)
                            ->where('sales_lead_register.year',$dates)
                            ->count('sales_lead_register.lead_id');

                $total_tp = DB::table('sales_lead_register')
                            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                            ->where('sales_lead_register.result','TP')
                            ->where('sales_solution_design.nik', $nik)
                            ->where('sales_lead_register.year',$dates)
                            ->count('sales_lead_register.lead_id');

                $total_win = DB::table('sales_lead_register')
                            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                            ->where('sales_lead_register.result','WIN')
                            ->where('sales_solution_design.nik', $nik)
                            ->where('sales_lead_register.year',$dates)
                            ->count('sales_lead_register.lead_id');

                $total_lose = DB::table('sales_lead_register')
                            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                            ->where('sales_lead_register.result','LOSE')
                            ->where('sales_solution_design.nik', $nik)
                            ->where('sales_lead_register.year',$dates)
                            ->count('sales_lead_register.lead_id');
             
            } elseif ($ter == 'OPERATION') {
                $lead = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.keterangan', 'sales_lead_register.closing_date', 'sales_lead_register.keterangan','sales_lead_register.deal_price','sales_lead_register.year')
                    ->where('result','!=','hmm')
                    ->where('id_company', '1')
                    ->orderBy('created_at','desc')
                    ->get();

               $leads = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.keterangan', 'sales_lead_register.closing_date', 'sales_lead_register.keterangan','sales_lead_register.deal_price','sales_lead_register.year')
                    ->where('result','!=','hmm')
                    ->where('id_company', '1')
                    ->orderBy('created_at','desc')
                    ->whereYear('sales_lead_register.created_at', '=', $dates-1)
                    ->orwhere('year',$dates)
                    ->get();

                $total_lead = count($leads);

                $total_open = DB::table('sales_lead_register')
                            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                            ->where('sales_lead_register.result','')
                            ->where('users.id_territory',$ter)
                            ->where('year',$dates)
                            ->count('lead_id');

                $total_sd = DB::table('sales_lead_register')
                            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                            ->where('sales_lead_register.result','SD')
                            ->where('users.id_territory',$ter)
                            ->where('year',$dates)
                            ->count('lead_id');

                $total_tp = DB::table('sales_lead_register')
                            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                            ->where('sales_lead_register.result','TP')
                            ->where('users.id_territory',$ter)
                            ->where('year',$dates)
                            ->count('lead_id');

                $total_win = DB::table('sales_lead_register')
                            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                            ->where('sales_lead_register.result','WIN')
                            ->where('users.id_territory',$ter)
                            ->where('year',$dates)
                            ->count('lead_id');

                $total_lose = DB::table('sales_lead_register')
                            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                            ->where('sales_lead_register.result','LOSE')
                            ->where('users.id_territory',$ter)
                            ->where('year',$dates)
                            ->count('lead_id');

            } else if ($div == 'SALES') {
                if ($pos == 'MANAGER') {

                    $lead = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->join('tb_pid', 'tb_pid.lead_id', '=', 'sales_lead_register.lead_id','left')
                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.keterangan', 'sales_lead_register.closing_date', 'sales_lead_register.keterangan','sales_lead_register.deal_price','sales_lead_register.year','users.nik', 'tb_pid.status')
                    ->where('result','!=','hmm')
                    ->where('id_territory', $ter)
                    ->where('id_company','1')
                    ->orderBy('created_at','desc')
                    ->get();
                    

                    $leads = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                        ->join('tb_pid', 'tb_pid.lead_id', '=', 'sales_lead_register.lead_id','left')
                        ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.keterangan', 'sales_lead_register.closing_date', 'sales_lead_register.keterangan','sales_lead_register.deal_price','sales_lead_register.year','users.nik', 'tb_pid.status')
                        ->where('result','!=','hmm')
                        ->where('users.id_company',1)
                        ->orderBy('created_at','desc')
                        ->where('users.id_territory',$ter)
                        ->get();
                    
                } else{

                    $lead = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->join('tb_pid', 'tb_pid.lead_id', '=', 'sales_lead_register.lead_id','left')
                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.keterangan', 'sales_lead_register.closing_date', 'sales_lead_register.keterangan','sales_lead_register.deal_price','sales_lead_register.year','users.nik', 'tb_pid.status')
                    ->where('result','!=','hmm')
                    ->where('id_territory', $ter)
                    ->where('id_company','1')
                    ->orderBy('created_at','desc')
                    ->get();

                    $leads = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                        ->join('tb_pid', 'tb_pid.lead_id', '=', 'sales_lead_register.lead_id','left')
                        ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.keterangan', 'sales_lead_register.closing_date', 'sales_lead_register.keterangan','sales_lead_register.deal_price','sales_lead_register.year','users.nik', 'tb_pid.status')
                        ->where('result','!=','hmm')
                        ->where('id_territory', $ter)
                        ->where('id_company','1')
                        ->orderBy('created_at','desc')
                        ->orwhere('year',$dates)
                        ->whereYear('sales_lead_register.created_at', '=', '2019')
                        ->get();

                }
                    

                    $total_lead = DB::table('sales_lead_register')
                                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                                ->where('users.id_territory',$ter)
                                ->where('year',$dates)
                                ->count('lead_id');

                    $total_open = DB::table('sales_lead_register')
                                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                                ->where('sales_lead_register.result','')
                                ->where('users.id_territory',$ter)
                                ->where('year',$dates)
                                ->count('lead_id');

                    $total_sd = DB::table('sales_lead_register')
                                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                                ->where('sales_lead_register.result','SD')
                                ->where('users.id_territory',$ter)
                                ->where('year',$dates)
                                ->count('lead_id');

                    $total_tp = DB::table('sales_lead_register')
                                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                                ->where('sales_lead_register.result','TP')
                                ->where('users.id_territory',$ter)
                                ->where('year',$dates)
                                ->count('lead_id');

                    $total_win = DB::table('sales_lead_register')
                                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                                ->where('sales_lead_register.result','WIN')
                                ->where('users.id_territory',$ter)
                                ->where('year',$dates)
                                ->count('lead_id');

                    $total_lose = DB::table('sales_lead_register')
                                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                                ->where('sales_lead_register.result','LOSE')
                                ->where('users.id_territory',$ter)
                                ->where('year',$dates)
                                ->count('lead_id');
            
            } elseif ($div == 'TECHNICAL PRESALES' && $pos == 'MANAGER') {
                $lead = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name',
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.nik','sales_lead_register.status_engineer','sales_lead_register.keterangan', 'sales_lead_register.closing_date', 'sales_lead_register.keterangan','sales_lead_register.deal_price','users.id_territory')
                    ->where('users.id_company','1')
                    ->where('year','2018')
                    ->get();

                $leadspre = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                    ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name',
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_solution_design.nik', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.keterangan','sales_lead_register.closing_date','sales_lead_register.deal_price','sales_lead_register.year','tb_contact.id_customer','sales_solution_design.status','users.id_territory')
                    ->whereYear('sales_lead_register.created_at',date('Y'))
                    ->where('sales_lead_register.result','!=','hmm')
                    ->where('users.id_company','1')
                    ->get();

                $leads = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name',
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.nik','sales_lead_register.status_engineer','sales_lead_register.keterangan','sales_lead_register.year','sales_lead_register.closing_date', 'sales_lead_register.keterangan','users.id_company','sales_lead_register.deal_price','sales_lead_register.year','users.id_territory')
                    ->orwhere('year',$dates)
                    ->where('users.id_company','1')
                    ->where('result','OPEN')
                    ->get();

                $leadsprenow = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                    ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name',
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_solution_design.nik', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.keterangan','sales_lead_register.closing_date','sales_lead_register.deal_price','sales_lead_register.year','tb_contact.id_customer','users.id_territory')
                    ->where('id_company','1')
                    ->where('sales_lead_register.result','!=','hmm')
                    ->get();

                $leadsnow = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name',
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.nik','sales_lead_register.status_engineer','sales_lead_register.keterangan','sales_lead_register.year','sales_lead_register.closing_date', 'sales_lead_register.keterangan','users.id_company','sales_lead_register.deal_price','sales_lead_register.year','users.id_territory')
                    ->where('users.id_company','1')
                    ->where('result','OPEN')
                    ->get();

                $datas =DB::table('sales_solution_design')
                        ->join('sales_lead_register','sales_lead_register.lead_id','=','sales_solution_design.lead_id')
                        ->join('users', 'users.nik', '=', 'sales_solution_design.nik')
                        ->select('users.name','sales_solution_design.nik')
                        ->whereYear('sales_lead_register.created_at', '=', $dates-1)
                        ->orwhere('year',$dates)
                        ->where('id_company','1')
                        ->get();

                $rk = user::select('nik')->where('email','rizkik@sinergy.co.id')->first();

                $gp = user::select('nik')->where('email','ganjar@sinergy.co.id')->first();

                $st = user::select('nik')->where('email','satria@sinergy.co.id')->first();

                $rz = user::select('nik')->where('email','rizaldo@sinergy.co.id')->first();

                $nt = user::select('nik')->where('email','aura@sinergy.co.id')->first();

                $jh = user::select('nik')->where('email','johan@sinergy.co.id')->first();



                $total_lead = count($leads);

                $total_open = DB::table('sales_lead_register')
                            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                            ->where('sales_lead_register.result','')
                            ->where('sales_lead_register.year','=',$dates)
                            ->where('users.id_company','1')
                            ->count('lead_id');

                $total_sd = DB::table('sales_lead_register')
                            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                            ->where('sales_lead_register.result','SD')
                            ->where('users.id_company','1')
                            ->where('sales_lead_register.year','=',$dates)
                            ->count('lead_id');

                $total_tp = DB::table('sales_lead_register')
                            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                            ->where('sales_lead_register.result','TP')
                            ->where('sales_lead_register.year','=',$dates)
                            ->where('users.id_company','1')
                            ->count('lead_id');

                $total_win = DB::table('sales_lead_register')
                            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                            ->where('sales_lead_register.result','WIN')
                            ->where('sales_lead_register.year','=',$dates)
                            ->where('users.id_company','1')
                            ->count('lead_id');

                $total_lose = DB::table('sales_lead_register')
                            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                            ->where('sales_lead_register.result','LOSE')
                            ->where('sales_lead_register.year','=',$dates)
                            ->where('users.id_company','1')
                            ->count('lead_id');

                $total_leads = count($lead);
            
            } else {
                if ($pos == 'ADMIN') {
                    $lead = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.keterangan', 'sales_lead_register.closing_date', 'sales_lead_register.keterangan','sales_lead_register.deal_price','sales_lead_register.year')
                    ->where('result','!=','hmm')
                    ->where('id_company','1')
                    ->orderBy('created_at','desc')
                    ->get();

                   $leads = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                        ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.keterangan', 'sales_lead_register.closing_date', 'sales_lead_register.keterangan','sales_lead_register.deal_price','sales_lead_register.year')
                        ->where('result','!=','hmm')
                        ->where('id_company','1')
                        ->orderBy('created_at','desc')
                        ->whereYear('sales_lead_register.created_at', '=', $dates-1)
                        ->orwhere('year',$dates)
                        ->get();
                
                }else{
                        $leads = DB::table('sales_lead_register')
                            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                            ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                            ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                            ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name',
                            'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.nik','sales_lead_register.status_engineer','sales_lead_register.keterangan','sales_lead_register.year','sales_lead_register.closing_date', 'sales_lead_register.keterangan','tb_company.code_company','tb_company.id_company','sales_lead_register.deal_price')
                            ->where('result','!=','hmm')
                            ->orderBy('created_at','desc')
                            ->get();

                        $dates = Date('Y');

                        $lead = DB::table('sales_lead_register')
                            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                            ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                            ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                            ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name',
                            'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.nik','sales_lead_register.status_engineer','sales_lead_register.keterangan','sales_lead_register.year','sales_lead_register.closing_date', 'sales_lead_register.keterangan','tb_company.code_company','tb_company.id_company','sales_lead_register.deal_price')
                            ->where('result','!=','hmm')
                            ->whereYear('sales_lead_register.created_at', '=', $dates-1)
                            ->orwhere('year',$dates)
                            ->orderBy('created_at','desc')
                            ->get();
                
                }
                

                $total_lead = DB::table('sales_lead_register')
                            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                            ->where('users.id_territory',$ter)
                            ->where('year',$dates)
                            ->count('lead_id');

                $total_open = DB::table('sales_lead_register')
                            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                            ->where('sales_lead_register.result','')
                            ->where('users.id_territory',$ter)
                            ->where('year',$dates)
                            ->count('lead_id');

                $total_sd = DB::table('sales_lead_register')
                            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                            ->where('sales_lead_register.result','SD')
                            ->where('users.id_territory',$ter)
                            ->where('year',$dates)
                            ->count('lead_id');

                $total_tp = DB::table('sales_lead_register')
                            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                            ->where('sales_lead_register.result','TP')
                            ->where('users.id_territory',$ter)
                            ->where('year',$dates)
                            ->count('lead_id');

                $total_win = DB::table('sales_lead_register')
                            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                            ->where('sales_lead_register.result','WIN')
                            ->where('users.id_territory',$ter)
                            ->where('year',$dates)
                            ->count('lead_id');

                $total_lose = DB::table('sales_lead_register')
                            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                            ->where('sales_lead_register.result','LOSE')
                            ->where('users.id_territory',$ter)
                            ->where('year',$dates)
                            ->count('lead_id');
            
            }             
        } else {
            $leads = DB::table('sales_lead_register')
            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
            ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
            ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
            ->join('tb_pid', 'tb_pid.lead_id', '=', 'sales_lead_register.lead_id','left')
            ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name',
            'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.nik','sales_lead_register.status_engineer','sales_lead_register.keterangan','sales_lead_register.year','sales_lead_register.closing_date', 'sales_lead_register.keterangan','tb_company.code_company','tb_company.id_company','sales_lead_register.deal_price', 'tb_pid.status','users.id_territory')
            ->where('result','!=','hmm')
            ->orderBy('created_at','desc')
            ->get();

            $dates = Date('Y');

            $lead = DB::table('sales_lead_register')
            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
            ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
            ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
            ->join('tb_pid', 'tb_pid.lead_id', '=', 'sales_lead_register.lead_id','left')
            ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name',
            'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.nik','sales_lead_register.status_engineer','sales_lead_register.keterangan','sales_lead_register.year','sales_lead_register.closing_date', 'sales_lead_register.keterangan','tb_company.code_company','tb_company.id_company','sales_lead_register.deal_price', 'tb_pid.status','users.id_territory')
            ->where('result','!=','hmm')
            ->whereYear('sales_lead_register.created_at', '=', $dates-1)
            ->orwhere('year',$dates)
            ->orderBy('created_at','desc')
            ->get();

            $year_dif = (int)$request['year_dif'];

            if (Auth::User()->email == 'tech@sinergy.co.id') {
                $coba = DB::table('sales_lead_register')
                ->select('year')
                ->where('result','!=','hmm')
                ->where('year',$year_dif)
                ->orderBy('created_at','desc')
                ->get();
            }
            
            $total_lead = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->where('sales_lead_register.year',$dates)
                        ->count('lead_id');

            $total_open = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->where('sales_lead_register.result','')
                        ->where('sales_lead_register.year',$dates)
                        ->count('lead_id');

            $total_sd = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->where('sales_lead_register.result','SD')
                        ->where('sales_lead_register.year',$dates)
                        ->count('lead_id');

            $total_tp = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->where('sales_lead_register.result','TP')
                        ->where('sales_lead_register.year',$dates)
                        ->count('lead_id');

            $total_win = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->where('sales_lead_register.result','WIN')
                        ->where('sales_lead_register.year',$dates)
                        ->count('lead_id');

            $total_lose = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->where('sales_lead_register.result','LOSE')
                        ->where('sales_lead_register.year',$dates)
                        ->count('lead_id');

        }

        if ($ter != null) {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();
        } elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $notif = DB::table('sales_lead_register')
            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->select('sales_lead_register.opp_name','sales_solution_design.nik')
            ->where('result','OPEN')
            ->orderBy('sales_lead_register.created_at','desc')
            ->get();
        } else {
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

        $year_now = date('Y');

        if (Auth::User()->id_division == 'FINANCE') {
            
            return view('sales/lead_id_project', compact('lead','leads','notif','notifOpen','notifsd','notiftp','notifClaim'));

        }else{

            return view('sales/sales', compact('lead','leads', 'total_ter','total_ters','notif','notifOpen','notifsd','notiftp','id_pro','contributes','users','pmo_nik','owner_by_lead','total_lead','total_open','total_sd','total_tp','total_win','total_lose','total_leads','total_opens','total_sds','total_tps','total_wins','total_loses', 'notifClaim','cek_note','cek_initial','datas','rk','gp','st','rz','nt','jh','leadspre','year','year_now','year_dif','coba','leadsprenow','leadsnow','leadnow'));

        }

        
    }

    public function getLeadByCompany(Request $request)
    {
        $leadsnow  = array("data" => DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.nik', 'sales_lead_register.closing_date', 'sales_lead_register.keterangan','sales_lead_register.deal_price','sales_lead_register.year','id_company')
                    ->where('sales_lead_register.result','WIN')
                    ->where('year',date('Y'))
                    ->get());

        return $leadsnow;
    }

    public function year_initial(Request $request)
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

        if ($div == 'TECHNICAL' && $pos == 'MANAGER' || $pos== 'DIRECTOR') {
            $a =   array(DB::table('sales_lead_register')
                ->join('users','users.nik','=','sales_lead_register.nik')
                ->select('year','result')
                ->where('result','!=','hmm')
                ->where('year',$request->product)
                ->get(),$request->product);

            $b = DB::table('sales_lead_register')
                ->join('users','users.nik','=','sales_lead_register.nik')
                ->select('year','result')
                ->where('result','!=','hmm')
                ->where('year',$request->product)
                ->count();

            if ($b > 0) {
                return $a;
            }else{
                return array(['null']);
            }
        }else if ($div == "SALES" && $pos != 'ADMIN') {
            $a =  array(DB::table('sales_lead_register')
                ->join('users','users.nik','=','sales_lead_register.nik')
                ->select('year','result')
                ->where('result','!=','hmm')
                ->where('id_territory', $ter)
                ->where('year',$request->product)
                ->get(),$request->product);

            $b = DB::table('sales_lead_register')
                ->join('users','users.nik','=','sales_lead_register.nik')
                ->select('year','result')
                ->where('result','!=','hmm')
                ->where('id_territory', $ter)
                ->where('year',$request->product)
                ->count();

            if ($b > 0) {
                return $a;
            }else{
                return array(['null']);
            }
        }else if($ter == 'OPERATION') {
            $a =  array(DB::table('sales_lead_register')
                ->join('users','users.nik','=','sales_lead_register.nik')
                ->select('year','result')
                ->where('result','!=','hmm')
                ->where('id_territory', $ter)
                ->where('id_company','1')
                ->where('year',$request->product)
                ->get(),$request->product);
            $b =  DB::table('sales_lead_register')
                ->join('users','users.nik','=','sales_lead_register.nik')
                ->select('year','result')
                ->where('result','!=','hmm')
                ->where('id_territory', $ter)
                ->where('id_company','1')
                ->where('year',$request->product)
                ->count();

            if ($b > 0) {
                return $a;
            }else{
                return array(['null']);
            }
        }else if ($div == "TECHNICAL PRESALES" && $pos == 'MANAGER') {
            $a =  array(DB::table('sales_lead_register')
                ->join('users','users.nik','=','sales_lead_register.nik')
                ->select('year','result')
                ->where('result','!=','hmm')
                ->where('id_company','1')
                ->where('year',$request->product)
                ->get(),$request->product);

            $b = DB::table('sales_lead_register')
                ->join('users','users.nik','=','sales_lead_register.nik')
                ->select('year','result')
                ->where('result','!=','hmm')
                ->where('id_company','1')
                ->where('year',$request->product)
                ->count();

            if ($b > 0) {
                return $a;
            }else{
                return array(['null']);
            }
        }else if ($div == "TECHNICAL PRESALES" && $pos == 'STAFF') {
            $a =  array(DB::table('sales_lead_register')
                ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                ->join('users','users.nik','=','sales_lead_register.nik')
                ->select('year','result')
                ->where('result','!=','hmm')
                ->where('id_company','1')
                ->where('sales_solution_design.nik',$nik)
                ->where('year',$request->product)
                ->get(),$request->product);

            $b =  DB::table('sales_lead_register')
                ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                ->join('users','users.nik','=','sales_lead_register.nik')
                ->select('year','result')
                ->where('result','!=','hmm')
                ->where('id_company','1')
                ->where('sales_solution_design.nik',$nik)
                ->where('year',$request->product)
                ->count();

            if ($b > 0) {
                return $a;
            }else{
                return array(['null']);
            }
        }
        
    }

    public function year_open(Request $request)
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

    	if ($div == 'TECHNICAL' && $pos == 'MANAGER' || $pos== 'DIRECTOR') {
	        $a = array(DB::table('sales_lead_register')
                    ->join('users','users.nik','=','sales_lead_register.nik')
	                ->select('year','result')
	                ->where('result','')
	                ->where('year',$request->product)
	                ->get(),$request->product);

            $b = DB::table('sales_lead_register')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->select('year','result')
                    ->where('result','')
                    ->where('year',$request->product)
                    ->count();

            if ($b > 0) {
                return $a;
            }else{
                return array(['null']);
            }

    	}else if ($div == "SALES" && $pos != 'ADMIN') {
            $a = array(DB::table('sales_lead_register')
            		->join('users','users.nik','=','sales_lead_register.nik')
	                ->select('year','result')
	                ->where('result','')
	                ->where('id_territory',$ter)
                    ->where('id_company','1')
	                ->where('year',$request->product)
	                ->get(),$request->product);

            $b = DB::table('sales_lead_register')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->select('year','result')
                    ->where('result','')
                    ->where('id_territory',$ter)
                    ->where('id_company','1')
                    ->where('year',$request->product)
                    ->count();
            
            if ($b > 0) {
                return $a;
            }else{
                return array(['null']);
            }
        }else if($ter == 'OPERATION') {
            $a = array(DB::table('sales_lead_register')
                    ->join('users','users.nik','=','sales_lead_register.nik')
	                ->select('year','result')
	                ->where('result','')
                    ->where('id_company','1')
	                ->where('year',$request->product)
	                ->get(),$request->product);

            $b = DB::table('sales_lead_register')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->select('year','result')
                    ->where('result','')
                    ->where('id_company','1')
                    ->where('year',$request->product)
                    ->count();

            if ($b > 0) {
                return $a;
            }else{
                return array(['null']);
            }
        }else if ($div == "TECHNICAL PRESALES" && $pos == 'MANAGER') {
            $a =  array(DB::table('sales_lead_register')
                    ->join('users','users.nik','=','sales_lead_register.nik')
	                ->select('year','result')
	                ->where('result','')
                    ->where('id_company','1')
	                ->where('year',$request->product)
	                ->get(),$request->product);

            $b =  DB::table('sales_lead_register')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->select('year','result')
                    ->where('result','')
                    ->where('id_company','1')
                    ->where('year',$request->product)
                    ->count();

            if ($b > 0) {
                return $a;
            }else{
                return array(['null']);
            }
        }else if ($div == "TECHNICAL PRESALES" && $pos == 'STAFF') {
            $a =  array(DB::table('sales_lead_register')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->join('sales_solution_design','sales_solution_design.nik','=','users.nik')
	                ->select('year','result')
	                ->where('result','')
                    ->where('sales_solution_design.nik',$nik)
                    ->where('id_company','1')
	                ->where('year',$request->product)
	                ->get(),$request->product);

            $b =  DB::table('sales_lead_register')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->join('sales_solution_design','sales_solution_design.nik','=','users.nik')
                    ->select('year','result')
                    ->where('result','')
                    ->where('sales_solution_design.nik',$nik)
                    ->where('id_company','1')
                    ->where('year',$request->product)
                    ->count();

            if ($b > 0) {
                return $a;
            }else{
                return array(['null']);
            }
        }
        
    }

    public function year_sd(Request $request)
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

    	if ($div == 'TECHNICAL' && $pos == 'MANAGER' || $pos== 'DIRECTOR') {
	        $a =  array(DB::table('sales_lead_register')
                ->join('users','users.nik','=','sales_lead_register.nik')
                ->select('year','result')
                ->where('result','SD')
                ->where('year',$request->product)
                ->get(),$request->product);

            $b =   DB::table('sales_lead_register')
                ->join('users','users.nik','=','sales_lead_register.nik')
                ->select('year','result')
                ->where('result','SD')
                ->where('year',$request->product)
                ->count();

             if ($b > 0) {
                return $a;
            }else{
                return array(['null']);
            }
    	}else if ($div == "SALES" && $pos != 'ADMIN') {
            $a =  array(DB::table('sales_lead_register')
            	->join('users','users.nik','=','sales_lead_register.nik')
                ->select('year','result')
                ->where('result','SD')
                ->where('id_company','1')
                ->where('year',$request->product)
                ->where('id_territory',$ter)
                ->get(),$request->product);

            $b = DB::table('sales_lead_register')
                ->join('users','users.nik','=','sales_lead_register.nik')
                ->select('year','result')
                ->where('result','SD')
                ->where('id_company','1')
                ->where('year',$request->product)
                ->where('id_territory',$ter)
                ->count();

             if ($b > 0) {
                return $a;
            }else{
                return array(['null']);
            }
        }else if($ter == 'OPERATION') {
            $a =  array(DB::table('sales_lead_register')
                ->join('users','users.nik','=','sales_lead_register.nik')
                ->select('year','result')
                ->where('result','SD')
                ->where('id_company','1')
                ->where('year',$request->product)
                ->get(),$request->product);

            $b = DB::table('sales_lead_register')
                ->join('users','users.nik','=','sales_lead_register.nik')
                ->select('year','result')
                ->where('result','SD')
                ->where('id_company','1')
                ->where('year',$request->product)
                ->count();

             if ($b > 0) {
                return $a;
            }else{
                return array(['null']);
            }
        }else if ($div == "TECHNICAL PRESALES" && $pos == 'MANAGER') {
            $a =  array(DB::table('sales_lead_register')
                ->join('users','users.nik','=','sales_lead_register.nik')
                ->select('year','result')
                ->where('result','SD')
                ->where('id_company','1')
                ->where('year',$request->product)
                ->get(),$request->product);

            $b = DB::table('sales_lead_register')
                ->join('users','users.nik','=','sales_lead_register.nik')
                ->select('year','result')
                ->where('result','SD')
                ->where('id_company','1')
                ->where('year',$request->product)
                ->count();

             if ($b > 0) {
                return $a;
            }else{
                return array(['null']);
            }
        }else if ($div == "TECHNICAL PRESALES" && $pos == 'STAFF') {
            $a =  array(DB::table('sales_lead_register')
                ->join('users','users.nik','=','sales_lead_register.nik')
                ->join('sales_solution_design','sales_solution_design.nik','=','users.nik')
                ->select('year','result')
                ->where('result','SD')
                ->where('id_company','1')
                ->where('sales_solution_design.nik',$nik)
                ->where('year',$request->product)
                ->get(),$request->product);

            $b = DB::table('sales_lead_register')
                ->join('users','users.nik','=','sales_lead_register.nik')
                ->join('sales_solution_design','sales_solution_design.nik','=','users.nik')
                ->select('year','result')
                ->where('result','SD')
                ->where('id_company','1')
                ->where('sales_solution_design.nik',$nik)
                ->where('year',$request->product)
                ->count();

             if ($b > 0) {
                return $a;
            }else{
                return array(['null']);
            }
        }
        
    }

    public function year_tp(Request $request)
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

        if ($div == 'TECHNICAL' && $pos == 'MANAGER' || $pos== 'DIRECTOR') {
	        $a =  array(DB::table('sales_lead_register')
                ->join('users','users.nik','=','sales_lead_register.nik')
                ->select('year','result')
                ->where('result','TP')
                ->where('year',$request->product)
                ->get(),$request->product);

            $b =  DB::table('sales_lead_register')
                ->join('users','users.nik','=','sales_lead_register.nik')
                ->select('year','result')
                ->where('result','TP')
                ->where('year',$request->product)
                ->count();

             if ($b > 0) {
                return $a;
            }else{
                return array(['null']);
            }
    	}else if ($div == "SALES" && $pos != 'ADMIN') {
            $a =  array(DB::table('sales_lead_register')
            	->join('users','users.nik','=','sales_lead_register.nik')
                ->select('year','result')
                ->where('result','TP')
                ->where('id_company','1')
                ->where('year',$request->product)
                ->where('id_territory',$ter)
                ->get(),$request->product);

            $b = DB::table('sales_lead_register')
                ->join('users','users.nik','=','sales_lead_register.nik')
                ->select('year','result')
                ->where('result','TP')
                ->where('id_company','1')
                ->where('year',$request->product)
                ->where('id_territory',$ter)
                ->count();

             if ($b > 0) {
                return $a;
            }else{
                return array(['null']);
            }
        }else if($ter == 'OPERATION') {
            $a =  array(DB::table('sales_lead_register')
                ->join('users','users.nik','=','sales_lead_register.nik')
                ->select('year','result')
                ->where('result','TP')
                ->where('id_company','1')
                ->where('year',$request->product)
                ->get(),$request->product);

            $b =  DB::table('sales_lead_register')
                ->join('users','users.nik','=','sales_lead_register.nik')
                ->select('year','result')
                ->where('result','TP')
                ->where('id_company','1')
                ->where('year',$request->product)
                ->count();

             if ($b > 0) {
                return $a;
            }else{
                return array(['null']);
            }
        }else if ($div == "TECHNICAL PRESALES" && $pos == 'MANAGER') {
            $a =  array(DB::table('sales_lead_register')
                ->join('users','users.nik','=','sales_lead_register.nik')
                ->select('year','result')
                ->where('result','TP')
                ->where('id_company','1')
                ->where('year',$request->product)
                ->get(),$request->product);

            $b =   DB::table('sales_lead_register')
                ->join('users','users.nik','=','sales_lead_register.nik')
                ->select('year','result')
                ->where('result','TP')
                ->where('id_company','1')
                ->where('year',$request->product)
                ->count();

             if ($b > 0) {
                return $a;
            }else{
                return array(['null']);
            }
        }else if ($div == "TECHNICAL PRESALES" && $pos == 'STAFF') {
            $a =   array(DB::table('sales_lead_register')
                ->join('users','users.nik','=','sales_lead_register.nik')
                ->join('sales_solution_design','sales_solution_design.nik','=','users.nik')
                ->select('year','result')
                ->where('result','TP')
                ->where('id_company','1')
                ->where('sales_solution_design.nik',$nik)
                ->where('year',$request->product)
                ->get(),$request->product);

            $b =  DB::table('sales_lead_register')
                ->join('users','users.nik','=','sales_lead_register.nik')
                ->join('sales_solution_design','sales_solution_design.nik','=','users.nik')
                ->select('year','result')
                ->where('result','TP')
                ->where('id_company','1')
                ->where('sales_solution_design.nik',$nik)
                ->where('year',$request->product)
                ->count();

             if ($b > 0) {
                return $a;
            }else{
                return array(['null']);
            }
        }

    }

    public function year_win(Request $request)
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

        if ($div == 'TECHNICAL' && $pos == 'MANAGER' || $pos== 'DIRECTOR') {
	        $a =  array(DB::table('sales_lead_register')
                ->join('users','users.nik','=','sales_lead_register.nik')
                ->where('result','WIN')
                ->where('year',$request->product)
                ->get(),$request->product);

            $b = DB::table('sales_lead_register')
                ->join('users','users.nik','=','sales_lead_register.nik')
                ->where('result','WIN')
                ->where('year',$request->product)
                ->count();

             if ($b > 0) {
                return $a;
            }else{
                return array(['null']);
            }
    	}else if ($div == "SALES" && $pos != 'ADMIN') {
            $a =   array(DB::table('sales_lead_register')
            	->join('users','users.nik','=','sales_lead_register.nik')
                ->where('result','WIN')
                ->where('id_company','1')
                ->where('year',$request->product)
                ->where('id_territory',$ter)
                ->get(),$request->product);

            $b =   DB::table('sales_lead_register')
                ->join('users','users.nik','=','sales_lead_register.nik')
                ->where('result','WIN')
                ->where('id_company','1')
                ->where('year',$request->product)
                ->where('id_territory',$ter)
                ->count();

            if ($b > 0) {
                return $a;
            }else{
                return array(['null']);
            }
        }else if($ter == 'OPERATION') {
            $a =   array(DB::table('sales_lead_register')
                ->join('users','users.nik','=','sales_lead_register.nik')
                ->where('result','WIN')
                ->where('id_company','1')
                ->where('year',$request->product)
                ->get(),$request->product);

            $b =   DB::table('sales_lead_register')
                ->join('users','users.nik','=','sales_lead_register.nik')
                ->where('result','WIN')
                ->where('id_company','1')
                ->where('year',$request->product)
                ->count();

             if ($b > 0) {
                return $a;
            }else{
                return array(['null']);
            }
        }else if ($div == "TECHNICAL PRESALES" && $pos == 'MANAGER') {
            $a =   array(DB::table('sales_lead_register')
                ->join('users','users.nik','=','sales_lead_register.nik')
                ->where('result','WIN')
                ->where('id_company','1')
                ->where('year',$request->product)
                ->get(),$request->product);

            $b =   DB::table('sales_lead_register')
                ->join('users','users.nik','=','sales_lead_register.nik')
                ->where('result','WIN')
                ->where('id_company','1')
                ->where('year',$request->product)
                ->count();

             if ($b > 0) {
                return $a;
            }else{
                return array(['null']);
            }
        }else if ($div == "TECHNICAL PRESALES" && $pos == 'STAFF') {
            $a =   array(DB::table('sales_lead_register')
                ->join('users','users.nik','=','sales_lead_register.nik')
                ->join('sales_solution_design','sales_solution_design.nik','=','users.nik')
                ->where('result','WIN')
                ->where('id_company','1')
                ->where('sales_solution_design.nik',$nik)
                ->where('year',$request->product)
                ->get(),$request->product);

            $b =   DB::table('sales_lead_register')
                ->join('users','users.nik','=','sales_lead_register.nik')
                ->join('sales_solution_design','sales_solution_design.nik','=','users.nik')
                ->where('result','WIN')
                ->where('id_company','1')
                ->where('sales_solution_design.nik',$nik)
                ->where('year',$request->product)
                ->count();

             if ($b > 0) {
                return $a;
            }else{
                return array(['null']);
            }
        }
    }

    public function year_lose(Request $request)
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

        if ($div == 'TECHNICAL' && $pos == 'MANAGER' || $pos== 'DIRECTOR') {
	        $a =  array(DB::table('sales_lead_register')
                ->join('users','users.nik','=','sales_lead_register.nik')
                ->select('year','result')
                ->where('result','LOSE')
                ->where('year',$request->product)
                ->get(),$request->product);

            $b = DB::table('sales_lead_register')
                ->join('users','users.nik','=','sales_lead_register.nik')
                ->select('year','result')
                ->where('result','LOSE')
                ->where('year',$request->product)
                ->count();

             if ($b > 0) {
                return $a;
            }else{
                return array(['null']);
            }
    	}else if ($div == "SALES" && $pos != 'ADMIN') {
            $a =  array(DB::table('sales_lead_register')
            	->join('users','users.nik','=','sales_lead_register.nik')
                ->select('year','result')
                ->where('result','LOSE')
                ->where('id_company','1')
                ->where('year',$request->product)
                ->where('id_territory',$ter)
                ->get(),$request->product);

            $b =  DB::table('sales_lead_register')
                ->join('users','users.nik','=','sales_lead_register.nik')
                ->select('year','result')
                ->where('result','LOSE')
                ->where('id_company','1')
                ->where('year',$request->product)
                ->where('id_territory',$ter)
                ->count();

             if ($b > 0) {
                return $a;
            }else{
                return array(['null']);
            }
        }else if($ter == 'OPERATION') {
            $a =  array(DB::table('sales_lead_register')
                ->join('users','users.nik','=','sales_lead_register.nik')
                ->select('year','result')
                ->where('result','LOSE')
                ->where('id_company','1')
                ->where('year',$request->product)
                ->get(),$request->product);

            $b = DB::table('sales_lead_register')
                ->join('users','users.nik','=','sales_lead_register.nik')
                ->select('year','result')
                ->where('result','LOSE')
                ->where('id_company','1')
                ->where('year',$request->product)
                ->count();

             if ($b > 0) {
                return $a;
            }else{
                return array(['null']);
            }
        }else if ($div == "TECHNICAL PRESALES" && $pos == 'MANAGER') {
            $a =  array(DB::table('sales_lead_register')
                ->join('users','users.nik','=','sales_lead_register.nik')
                ->select('year','result')
                ->where('result','LOSE')
                ->where('id_company','1')
                ->where('year',$request->product)
                ->get(),$request->product);

            $b =  DB::table('sales_lead_register')
                ->join('users','users.nik','=','sales_lead_register.nik')
                ->select('year','result')
                ->where('result','LOSE')
                ->where('id_company','1')
                ->where('year',$request->product)
                ->count();

             if ($b > 0) {
                return $a;
            }else{
                return array(['null']);
            }
        }else if ($div == "TECHNICAL PRESALES" && $pos == 'STAFF') {
            $a =   array(DB::table('sales_lead_register')
                ->join('users','users.nik','=','sales_lead_register.nik')
                ->join('sales_solution_design','sales_solution_design.nik','=','users.nik')
                ->select('year','result')
                ->where('result','LOSE')
                ->where('sales_solution_design.nik',$nik)
                ->where('id_company','1')
                ->where('year',$request->product)
                ->get(),$request->product);

            $b =  DB::table('sales_lead_register')
                ->join('users','users.nik','=','sales_lead_register.nik')
                ->join('sales_solution_design','sales_solution_design.nik','=','users.nik')
                ->select('year','result')
                ->where('result','LOSE')
                ->where('sales_solution_design.nik',$nik)
                ->where('id_company','1')
                ->where('year',$request->product)
                ->count();

             if ($b > 0) {
                return $a;
            }else{
                return array(['null']);
            }
        }
    }

    public function detail_sales($lead_id)
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;
        $company = DB::table('users')->select('id_company')->where('nik', $nik)->first();
        $com = $company->id_company;

        if($ter != null){
            $lead = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover')
                ->where('id_territory', $ter)
                ->get();
        } elseif($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $lead = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_solution_design.nik', 'sales_lead_register.status_sho','sales_lead_register.status_handover')
                ->where('sales_solution_design.nik', $nik)
                ->get();
        }elseif($div == 'PMO' && $pos == 'MANAGER') {
            $lead = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover')
                ->where('sales_lead_register.result','WIN')
                ->get();
        }elseif($div == 'PMO' && $pos == 'STAFF') {
            $lead = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('tb_pmo','sales_lead_register.lead_id','=','tb_pmo.lead_id')
                ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','tb_pmo.pmo_nik')
                ->where('sales_lead_register.result','WIN')
                ->where('tb_pmo.pmo_nik',$nik)
                ->get();
        }
        elseif($div == 'FINANCE' && $pos == 'MANAGER') {
            $lead = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.nik')
                ->where('sales_lead_register.result','WIN')
                ->get();
        }
        elseif($pos == 'ENGINEER MANAGER') {
            $lead = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.nik','sales_lead_register.status_engineer')
                ->where('sales_lead_register.result','WIN')
                ->where('sales_lead_register.status_sho','PMO')
                ->get();
        }
        elseif($pos == 'ENGINEER STAFF') {
            $lead = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('tb_engineer','sales_lead_register.lead_id','=','tb_engineer.lead_id')
                ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.nik','sales_lead_register.status_engineer')
                ->where('sales_lead_register.result','WIN')
                 ->where('tb_engineer.nik',$nik)
                ->get();
        }
        else {
              $lead = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.nik')
                ->get();
        }
        
        $tampilkan = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id','sales_lead_register.nik','tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name',
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.result2','sales_lead_register.result3','sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.status_engineer', 'sales_lead_register.id_customer','sales_lead_register.closing_date')
                    ->where('lead_id',$lead_id)
                    ->first();

        if ($div == 'SALES' && $ter == null) {
            /*$tampilkans = DB::table('sales_solution_design')
                    ->select('lead_id','assessment','pov','pd','pb','priority','project_size','status', 'assessment_date', 'pd_date', 'pov_date')
                    ->where('lead_id',$lead_id)
                    ->first();*/

            $tampilkans = DB::table('sales_solution_design')
                    ->join('users','users.nik','=','sales_solution_design.nik')
                    ->select('sales_solution_design.lead_id','sales_solution_design.nik','sales_solution_design.assessment','sales_solution_design.pov','sales_solution_design.pd','sales_solution_design.pb','sales_solution_design.priority','sales_solution_design.project_size','users.name','sales_solution_design.status', 'sales_solution_design.assessment_date', 'sales_solution_design.pd_date', 'sales_solution_design.pov_date')
                    ->where('lead_id',$lead_id)
                    ->first();

            $tampilkan_com = DB::table('sales_lead_register')
            		->join('users','users.nik','=','sales_lead_register.nik')
            		->select('users.id_company')
            		->where('lead_id',$lead_id)
            		->first();
        }else{
            $tampilkans = DB::table('sales_solution_design')
                    ->join('users','users.nik','=','sales_solution_design.nik')
                    ->select('sales_solution_design.lead_id','sales_solution_design.nik','sales_solution_design.assessment','sales_solution_design.pov','sales_solution_design.pd','sales_solution_design.pb','sales_solution_design.priority','sales_solution_design.project_size','users.name','sales_solution_design.status', 'sales_solution_design.assessment_date', 'sales_solution_design.pd_date', 'sales_solution_design.pov_date')
                    ->where('lead_id',$lead_id)
                    ->first();

            $tampilkan_com = DB::table('sales_lead_register')
            		->join('users','users.nik','=','sales_lead_register.nik')
            		->select('users.id_company')
            		->where('lead_id',$lead_id)
            		->first();
        }

        $tampilkana = DB::table('sales_solution_design')
                    ->join('users','users.nik','=','sales_solution_design.nik')
                    ->select('sales_solution_design.lead_id','sales_solution_design.nik','sales_solution_design.assessment','sales_solution_design.pov','sales_solution_design.pd','sales_solution_design.pb','sales_solution_design.priority','sales_solution_design.project_size','users.name','sales_solution_design.status', 'sales_solution_design.assessment_date', 'sales_solution_design.pd_date', 'sales_solution_design.pov_date','sales_solution_design.id_sd')
                    ->where('lead_id',$lead_id)
                    ->get();

        // $pmo_contribute = DB::table('tb_pmo')
        //             ->join('users','users.nik','=','tb_pmo.pmo_nik')
        //             ->select('users.name','tb_pmo.id_pmo')
        //             ->where('lead_id',$lead_id)
        //             ->get();

        $engineer_contribute = DB::table('tb_engineer')
                    ->join('users','users.nik','=','tb_engineer.nik')
                    ->select('tb_engineer.id_engineer','users.name')
                    ->where('lead_id',$lead_id)
                    ->get();

        $tampilkanc = DB::table('sales_tender_process')
        			->join('sales_lead_register', 'sales_tender_process.lead_id', '=', 'sales_lead_register.lead_id')
                    ->select('sales_tender_process.lead_id','auction_number','submit_price','win_prob','project_name','submit_date','quote_number','status','result','sales_lead_register.nik', 'sales_tender_process.assigned_by','quote_number2', 'sales_lead_register.amount', 'sales_lead_register.deal_price', 'sales_lead_register.deal_price_total', 'sales_lead_register.jumlah_tahun', 'sales_lead_register.project_class','id_tp')
                    ->where('sales_tender_process.lead_id',$lead_id)
                    ->first();

        $tampilkan_po = POCustomer::select('date','no_po','nominal','note','id_tb_po_cus')->where('lead_id',$lead_id)->get();

        $get_quote_number = DB::table('tb_quote')
                            ->join('tb_contact', 'tb_contact.id_customer', '=', 'tb_quote.id_customer')
                            ->select('id_quote', 'quote_number', 'customer_legal_name')
                            ->where('status', null)
                            ->where('tb_quote.id_customer', $tampilkan->id_customer)
                            ->orderBy('tb_quote.created_at', 'desc')
                            ->get();

        $tampilkan_progress = DB::table('tb_pmo_progress')
                    ->join('tb_pmo','tb_pmo_progress.id_pmo','=','tb_pmo.id_pmo')
                    ->select('tb_pmo.lead_id','tb_pmo_progress.ket','tb_pmo_progress.tanggal','tb_pmo.id_pmo','tb_pmo_progress.updated_at')
                    ->where('tb_pmo.lead_id',$lead_id)
                    ->get();

        $tampilkan_progress_engineer = DB::table('tb_engineer_progress')
                    ->join('tb_engineer','tb_engineer_progress.id_engineer','=','tb_engineer.id_engineer')
                    ->select('tb_engineer.lead_id','tb_engineer_progress.ket','tb_engineer.id_engineer')
                    ->where('tb_engineer.lead_id',$lead_id)
                    ->get();

        // $pmo_id = DB::table('tb_pmo')
        //             ->join('users','tb_pmo.pmo_nik','=','users.nik')
        //             ->select('tb_pmo.id_pmo','users.name')
        //             ->where('lead_id',$lead_id)
        //             ->first();

        $sd_id = DB::table('sales_solution_design')
                ->join('users','users.nik','=','sales_solution_design.nik')
                ->select('sales_solution_design.id_sd','users.name')
                ->where('lead_id',$lead_id)
                ->first();

        if ($com == 1 && $div == 'TECHNICAL PRESALES') {
            $pre_cont = DB::table('users')
                ->select('name')
                ->where('id_company','1')
                ->where('id_division','TECHNICAL PRESALES')
                ->get();
        }else if($com == 2 && $div == 'TECHNICAL PRESALES'){
            $pre_cont = DB::table('users')
                ->select('name')
                ->where('id_company','1')
                ->where('id_division','TECHNICAL PRESALES')
                ->get();
        }
        

        $engineer_id = DB::table('tb_engineer')
                    ->join('users','tb_engineer.nik','=','users.nik')
                    ->select('tb_engineer.id_engineer','users.name')
                    ->where('lead_id',$lead_id)
                    ->first();

        $current_eng = DB::table('tb_engineer')
                    ->join('users','tb_engineer.nik','=','users.nik')
                    ->select('tb_engineer.id_engineer','users.name')
                    ->where('lead_id',$lead_id)
                    ->first();

        $q_num = DB::table('sales_tender_process')
                ->select('quote_number')
                ->where('lead_id',$lead_id)
                ->first();

        $q_num2 = DB::table('sales_tender_process')
                ->join('tb_quote','sales_tender_process.quote_number','=','tb_quote.id_quote')
                ->select('tb_quote.quote_number')
                ->where('lead_id',$lead_id)
                ->first();

        $change_log = DB::table('sales_change_log')
                        ->join('sales_lead_register', 'sales_change_log.lead_id', '=', 'sales_lead_register.lead_id')
                        ->join('users', 'sales_change_log.nik', '=', 'users.nik')
                        ->select('sales_change_log.created_at', 'sales_lead_register.opp_name', 'sales_change_log.status', 'users.name', 'sales_change_log.submit_price', 'sales_change_log.deal_price', 'sales_change_log.progress_date')
                        ->where('sales_change_log.lead_id',$lead_id)
                        ->orderBy('created_at', 'desc')
                        ->get();

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


        if ($div == 'TECHNICAL PRESALES' && $pos == 'MANAGER' ) {
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
            ->select('sales_lead_register.opp_name','sales_solution_design.nik')
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

        return view('sales/detail_sales',compact('pre_cont','lead','tampilkan','tampilkans','tampilkan_com', 'tampilkana', 'tampilkanc','notif','notifOpen','notifsd','notiftp','tampilkan_progress','pmo_id','engineer_id','current_eng','tampilkan_progress_engineer','pmo_contribute','engineer_contribute','q_num','sd_id', 'get_quote_number', 'q_num2', 'change_log','notifClaim','tampilkan_po'));
    }

    public function getdatacustomer(Request $request)
    {
        $id_cus = $request['edit_cus'];

        return array(DB::table('tb_contact')
                ->select('id_customer','code','customer_legal_name','brand_name','office_building','street_address','city','province','postal','phone')
                ->where('id_customer',$request->id_cus)
                ->get(),$request->id_cus);
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
    

        if (Auth::User()->id_company == '1') {
            $contact = $request['contact'];
            $name = DB::table('tb_contact')
                        ->select('code')
                        ->where('id_customer', $contact)
                        ->first();
            $inc = DB::table('sales_lead_register')
                        ->select('lead_id')
                        ->where('id_customer', $contact)
                        ->where('month', date("n"))
                        ->where('year',date("Y"))
                        ->get();
            $increment = count($inc);
            $nomor = $increment+1;
            if($nomor < 10){
                $nomor = '0' . $nomor;
            }
            $lead = $name->code . date('y') . date('m') . $nomor;

            $tambah = new Sales();
            $tambah->lead_id = $lead;
            if(Auth::User()->id_division == 'SALES' || Auth::User()->id_division == 'MSM' && Auth::User()->id_position == 'MANAGER'){
                $tambah->nik = Auth::User()->nik;
            } else {
                $tambah->nik = $request['owner_sales'];
            }
            $tambah->id_customer = $request['contact'];
            $tambah->opp_name = $request['opp_name'];
            $tambah->month = date("n");
            $tambah->year = date("Y");
            // $tambah->amount = $request['amount'];
            if ($request['amount'] != NULL) {
               $tambah->amount = str_replace(',', '', $request['amount']);
            }else{
                $tambah->amount = $request['amount'];
            }
            if (is_null($request['po'])) {
                $tambah->result = 'OPEN';
            }else{
                $tambah->result = 'WIN';
            }
            
            $edate = strtotime($_POST['closing_date']); 
            $edate = date("Y-m-d",$edate);

            $tambah->closing_date = $edate;
            $tambah->keterangan = $request['note'];
            $tambah->save();

            /*$date_po = strtotime($_POST['date_po']); 
            $date_po = date("Y-m-d",$date_po);*/

            
           /* if (!empty($request['po'])) {
                $tambah_sd = new solution_design();
                $tambah_sd->lead_id = $lead;
                $tambah_sd->nik = Auth::User()->nik;
                $tambah_sd->save();

                $tambah_po = new PID();
                $tambah_po->lead_id = $lead;
                $tambah_po->amount_pid = str_replace(',', '', $request['amount_po']);
                $tambah_po->no_po = $request['no_po'];
                $tambah_po->date_po = $date_po;
                $tambah_po->status == 'requested';
                $tambah_po->save();


                $tambahtp = new TenderProcess();
                $tambahtp->lead_id = $lead;
                $tambahtp->save();
            }*/


            $lead_change_log = $name->code . date('y') . date('m') . $nomor;
            $amount = str_replace(',', '', $request['amount']);
            $tambah_log = new SalesChangeLog();
            $tambah_log->lead_id = $lead_change_log;
            if(Auth::User()->id_division == 'SALES' || Auth::User()->id_division == 'MSM' && Auth::User()->id_position == 'MANAGER'){
                $tambah_log->nik = Auth::User()->nik;
            } else {
                $tambah_log->nik = $request['owner_sales'];
            }
            $tambah_log->status = 'Create Lead with Amount ';
            $tambah_log->submit_price  = $amount;
            $tambah_log->save();



            $nik_sales = $request['owner_sales'];

            if(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL PRESALES'){
                $kirim = User::select('email')
                            ->where('nik', $nik_sales)
                            ->orWhere('email', 'nabil@sinergy.co.id')
                            ->get();
            } elseif(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL'){
                $kirim = User::select('email')
                            ->where('id_position', 'MANAGER')
                            ->where('id_division', 'TECHNICAL PRESALES')
                            ->orWhere('nik', $nik_sales)
                            ->get();
            } elseif(Auth::User()->id_division == 'SALES' || Auth::User()->id_division == 'MSM' && Auth::User()->id_position == 'MANAGER'){
                $kirim = User::select('email')
                            ->where('id_position', 'MANAGER')
                            ->where('id_division', 'TECHNICAL PRESALES')
                            ->orWhere('email', 'nabil@sinergy.co.id')
                            ->get();
            }

            if (is_null($request['po'])) {
                $users = User::select('email')->where('id_position', 'STAFF')->where('id_division', 'TECHNICAL')->where('id_territory', 'DVG')->get();
                Notification::send($kirim, new NewLead());
            }
            
            

            if (Auth::User()->id_division === 'TECHNICAL PRESALES' && Auth::User()->id_position === 'STAFF') {
                return redirect('project')->with('success', 'Wait for Presales Manager Assign Lead Register!');
            }else{
                return redirect('project')->with('success', 'Create Lead Register Successfully!');
            }

        }else if (Auth::User()->id_company == '2') {

            $contact = $request['contact'];
            $name = DB::table('tb_contact')
                        ->select('code')
                        ->where('id_customer', $contact)
                        ->first();
            $inc = DB::table('sales_lead_register')
                        ->select('lead_id')
                        ->where('id_customer', $contact)
                        ->where('month_msp', date("n"))
                        ->get();
            $increment = count($inc);
            $nomor = $increment+1;
            if($nomor < 10){
                $nomor = '0' . $nomor;
            }
            $lead = 'MSP' . $name->code . date('y') . date('m') . $nomor;

            $tambah = new Sales();
            $tambah->lead_id = $lead;
            if(Auth::User()->id_division == 'SALES'){
                $tambah->nik = Auth::User()->nik;
            } else {
                $tambah->nik = $request['owner_sales'];
            }
            $tambah->id_customer = $request['contact'];
            $tambah->opp_name = $request['opp_name'];
            $tambah->month_msp = date("n");
            $tambah->year = date("Y");
            // $tambah->amount = $request['amount'];
            if ($request['amount'] != NULL) {
               $tambah->amount = str_replace(',', '', $request['amount']);
            }else{
                $tambah->amount = $request['amount'];
            }
            $tambah->result = 'OPEN';
            $edate = strtotime($_POST['closing_date']); 
            $edate = date("Y-m-d",$edate);

            $tambah->closing_date = $edate;
            $tambah->save();

         /*   $tambah_sd = new solution_design();
            $tambah_sd->lead_id = $lead;
            $tambah_sd->nik = Auth::User()->nik;
            $tambah_sd->save();

            $tambahtp = new TenderProcess();
            $tambahtp->lead_id = $lead;
            $tambahtp->save();

            $tambahcl = new SalesChangeLog();
            $tambahcl->lead_id = $lead;
            $tambahcl->nik = Auth::User()->nik;
            $tambahcl->status = 'Create Lead';
            $tambahcl->save();

            $tambahscl = new SalesChangeLog();
            $tambahscl->lead_id = $lead;
            $tambahscl->status = 'Assign Presales';
            $tambahscl->save();*/

            return redirect('project')->with('success', 'Create Lead Register Successfully!');
        }
        
    }

    public function update_lead_register(Request $request){
        $lead_id = $request['lead_id_edit'];

        $date_edit_year = substr($request['create_date_edit'],2,2);
        $date_edit_month = substr($request['create_date_edit'],5,2);
        $leads = DB::table('sales_lead_register')
                ->select('lead_id')
                ->where('lead_id',$lead_id)
                ->first();
        $inc = substr($leads->lead_id,8);
        $name = $request['contact_edit'];
        $contact = DB::table('tb_contact')
                    ->select('code')
                    ->where('id_customer', $name)
                    ->first();
/*
        $lead_id_edit =  $name . $date_edit_year . $date_edit_month .$inc;*/

        $update = Sales::where('lead_id',$lead_id)->first();
        $update->opp_name   = $request['opp_name_edit'];
        // $update->id_customer = $request['contact_edit'];
        if ($request['amount_edit'] != NULL) {
            $update->amount = str_replace(',', '', $request['amount_edit']);
        }else{
            $update->amount = $request['amount_edit'];
        }
        // $update->created_at = $request['create_date_edit'];
        $edate_edit = strtotime($_POST['closing_date_edit']); 
        $edate_edit = date("Y-m-d",$edate_edit);

        $update->closing_date = $edate_edit;
        $update->keterangan = $request['note_edit'];
        $update->update();


        $amount = str_replace(',', '', $request['amount_edit']);

        if ($request['amount_edit'] != $request['amount_edit_before']) {
            $tambah_log = new SalesChangeLog();
            $tambah_log->lead_id = $lead_id;
            $tambah_log->nik = Auth::User()->nik;
            $tambah_log->status = 'Update Lead with Amount ';
            $tambah_log->submit_price  = $amount;
            $tambah_log->save();
        }
       



        return redirect()->back(); 
    }

    public function assign_to_presales(Request $request){

        $tambah = new solution_design();
        $tambah->lead_id = $request['coba_lead'];
        $tambah->nik = $request['owner'];
        $tambah->save();

        $tambahtp = new TenderProcess();
        $tambahtp->lead_id = $request['coba_lead'];
        $tambahtp->save();

        $lead_id = $request['coba_lead'];

        $update = Sales::where('lead_id', $lead_id)->first();
        $update->result = '';
        $update->update();

        $tambah = new SalesChangeLog();
        $tambah->lead_id = $request['coba_lead'];
        $tambah->nik = $request['cek_nik'];
        $tambah->status = 'Create Lead';
        $tambah->created_at = $request['cek_created_at'];
        $tambah->save();

        $tambah = new SalesChangeLog();
        $tambah->lead_id = $request['coba_lead'];
        $tambah->nik = Auth::User()->nik;
        $tambah->status = 'Assign Presales';
        $tambah->save();

        $nik_assign = $request['owner'];

        $kirim = User::select('email')->where('nik', $nik_assign)->first();

        $users = User::where('email','arkhab@sinergy.co.id')->first();
        Notification::send($kirim, new PresalesAssign());

        return redirect('project');

        // echo $request['coba_lead'];
    }

    public function reassign_to_presales(Request $request)
    {
        $lead_id = $request['coba_lead_reassign'];

        $update = solution_design::where('lead_id', $lead_id)->first();
        $update->nik = $request['owner_reassign'];
        $update->update();

        $tambah = new SalesChangeLog();
        $tambah->lead_id = $request['coba_lead_reassign'];
        $tambah->nik = Auth::User()->nik;
        $tambah->status = 'Re-Assign Presales';
        $tambah->save();

        $nik_assign = $request['owner_reassign'];

        $kirim = User::select('email')->where('nik', $nik_assign)->first();

        $users = User::where('email','arkhab@sinergy.co.id')->first();
        Notification::send($kirim, new PresalesReAssign());

        return redirect('project');
    }

    public function add_contribute(Request $request)
    {
        $tambah = new solution_design();
        $tambah->lead_id = $request['coba_lead_contribute'];
        $tambah->nik     = $request['add_contribute'];
        $tambah->status  = 'cont';
        $tambah->save();

        return redirect()->back();
    }

    public function delete_contribute_sd(Request $request)
    {
        $hapus = solution_design::find($request->id_sd);
        $hapus->delete();

        return redirect()->back();
    }

    public function raise_to_tender(Request $request){
        $lead_id = $request['lead_id'];

        $update = TenderProcess::where('lead_id', $lead_id)->first();
        $update->status = 'ready';
        $update->update();

        $update = solution_design::where('lead_id', $lead_id)->first();
        $update->status = 'closed';
        $update->update();

        $update = Sales::where('lead_id', $lead_id)->first();
        $update->result = 'TP';
        $update->update();

        $tambah = new SalesChangeLog();
        $tambah->lead_id = $request['lead_id'];
        $tambah->nik = Auth::User()->nik;
        $tambah->status = 'Raise To Tender';
        $tambah->save();

        $nik_sales = DB::table('sales_lead_register')
                    ->select('nik')
                    ->where('lead_id',$lead_id)
                    ->first();

        $kirim = User::select('email')
                        ->where('nik', $nik_sales->nik)
                        ->orWhere('email', 'nabil@sinergy.co.id')
                        ->get();

        $users = User::where('email','arkhab@sinergy.co.id')->first();
        Notification::send($kirim, new RaiseToTender());

        return redirect()->back();
    }

    public function update_result(Request $request){

        // return "asdfasdf";
        
            $lead_id = $request['lead_id_result'];

            if ($request['quote_number_final'] != NULL) {
                $id_quotes = Quote::where('quote_number', $request['quote_number_final'])->first()->id_quote;

                $amount_quo = Quote::where('quote_number', $request['quote_number_final'])->first()->amount;
            }

            if ($request['result'] == 'WIN' && $request['deal_price_result'] == null) {
                return back()->with('submit-price','Deal Price Wajib Diisi!');
            } else{
                // return "asdfafdasd";
                $edate = strtotime($_POST['update_closing_date']); 
                $edate = date("Y-m-d",$edate);

                $update = Sales::where('lead_id', $lead_id)->first();
                $update->result = $request['result'];
                $update->keterangan = $request['keterangan'];
                $update->closing_date = $edate;
                $update->result4    = $request['project_type'];
                $update->update();

                if($request['result'] != 'HOLD' || $request['result'] != 'SPECIAL'){
                    $update = TenderProcess::where('lead_id', $lead_id)->first();
                    $update->status = 'closed';
                    $update->update();
                }

                $tambah = new SalesChangeLog();
                $tambah->lead_id = $request['lead_id_result'];
                $tambah->nik = Auth::User()->nik;
                if($request['result'] == 'WIN'){
                    $tambah->status = 'Update WIN';

		            $tambahpid = new PID();
		            $tambahpid->lead_id     = $request['lead_id_result'];
		            $tambahpid->no_po       = $request['no_po'];
                    if ($request['amount_pid'] != NULL) {
                        $tambahpid->amount_pid  = str_replace(',', '',$request['amount_pid']);
                    }else{
                        $tambahpid->amount_pid  = $amount_quo;
                    }
                    
		            if ($request['date_po'] != NULL) {
                        $edate                  = strtotime($_POST['date_po']); 
                        $edate                  = date("Y-m-d",$edate);
                        $tambahpid->date_po     = $edate;
                    }  
                    // return $request['request_id'];
                    if (!empty($request['request_id'])) {
                        $tambahpid->status = 'requested';

                        /*$users = User::select('name')->where('id_division','FINANCE')->where('id_position','MANAGER')->first();

                        $pid_info = DB::table('sales_lead_register')
                            ->join('sales_tender_process','sales_tender_process.lead_id','=','sales_lead_register.lead_id')
                            ->join('tb_pid','tb_pid.lead_id','=','sales_lead_register.lead_id')
                            ->join('users','users.nik','=','sales_lead_register.nik')
                            ->where('sales_lead_register.lead_id','=',$request['lead_id_result'])
                            ->select(
                                'sales_lead_register.lead_id',
                                'sales_lead_register.opp_name',
                                'users.name',
                                'tb_pid.amount_pid',
                                'tb_pid.no_po',
                                'sales_tender_process.quote_number2'
                            )->first(); 
               
                            // return "$pid_info";

                        Mail::to('ladinar@sinergy.co.id')->send(new MailResult($users,$pid_info));
                        Mail::to('agastya@sinergy.co.id')->send(new MailResult($users,$pid_info));*/
                        // Mail::to($users->email)->send(new MailResult($users,$pid_info));
                    
                    }else{
                        $tambahpid->status = 'pending';
                    }

                    $tambahpid->save();

                    $update_quo = TenderProcess::where('lead_id', $lead_id)->first();
                    $update_quo->quote_number_final = $request['quote_number_final'];
                    $update_quo->update();

                    if ($request['quote_number_final'] != null) {
                        $update_status_quo = Quote::where('quote_number', $request['quote_number_final'])->first();
                        $update_status_quo->status = 'choosed';
                        $update_status_quo->update();
                    }
                    

                    $cekstatus = PID::select('status')->where('lead_id', $tambahpid->lead_id)->first();

                    if ($cekstatus->status == 'requested') {
                        $pid_info = DB::table('sales_lead_register')
                            ->join('sales_tender_process','sales_tender_process.lead_id','=','sales_lead_register.lead_id')
                            ->join('tb_pid','tb_pid.lead_id','=','sales_lead_register.lead_id')
                            ->join('users','users.nik','=','sales_lead_register.nik')
                            ->where('sales_lead_register.lead_id',$tambahpid->lead_id)
                            ->select(
                                'sales_lead_register.lead_id',
                                'sales_lead_register.opp_name',
                                'users.name',
                                'tb_pid.amount_pid',
                                'tb_pid.id_pid',
                                'tb_pid.no_po',
                                'sales_tender_process.quote_number2'
                            )->first();

                        if($pid_info->lead_id == "MSPQUO"){
                            $pid_info->url_create = "/salesproject";
                        }else {
                            $pid_info->url_create = "/salesproject#acceptProjectID?" . $pid_info->id_pid;
                        }

                        $users = User::select('name', 'email')->where('id_division','FINANCE')->where('id_position','MANAGER')->first();
               
                            // return "$pid_info";

                        // Mail::to('ladinar@sinergy.co.id')->send(new MailResult($users,$pid_info));
                        Mail::to('faiqoh@sinergy.co.id')->send(new MailResult($users,$pid_info));
                        Mail::to($users->email)->send(new MailResult($users,$pid_info));
                    }

                    
                    // return "asdfasfda";

		            // $tambahpid->status      = 'requested';
		            

                } elseif($request['result'] == 'LOSE'){
                    $tambah->status = 'Update LOSE';
                } elseif($request['result'] == 'HOLD'){
                    $tambah->status = 'Update HOLD';
                } elseif($request['result'] == 'CANCEL'){
                    $tambah->status = 'Update CANCEL';
                } elseif($request['result'] == 'SPECIAL'){
                    $tambah->status = 'Update SPECIAL';
                }
                $tambah->save();

            }

            


            $nik_sales = DB::table('sales_lead_register')
                            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                            ->select('sales_lead_register.nik', 'users.id_territory')
                            ->where('lead_id',$lead_id)
                            ->first();

            $current_presales = DB::table('sales_solution_design')
                                    ->join('users','users.nik','=','sales_solution_design.nik')
                                    ->select('sales_solution_design.nik')
                                    ->where('lead_id',$lead_id)
                                    ->first();

            $presales_manager = DB::table('users')
                                    ->select('nik')
                                    ->where('id_position', 'MANAGER')
                                    ->where('id_division', 'TECHNICAL PRESALES')
                                    ->first();

            if(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'SALES' && Auth::User()->id_territory == $nik_sales->id_territory || Auth::User()->email == 'presales@sinergy.co.id'){

                if (Auth::User()->email == 'presales@sinergy.co.id') {
                    $kirim = User::select('email')
                                ->where('id_division', 'TECHNICAL PRESALES')
                                ->where('email', 'ganjar@sinergy.co.id')
                                ->orWhere('email', 'nabil@sinergy.co.id')
                                ->orWhere('email', 'yuliane@sinergy.co.id')
                                ->get();
                }else{
                    $kirim = User::select('email')
                                ->where('id_division', 'TECHNICAL PRESALES')
                                ->where('nik', $current_presales->nik)
                                ->orWhere('nik', $presales_manager->nik)
                                ->orWhere('email', 'nabil@sinergy.co.id')
                                ->orWhere('email', 'yuliane@sinergy.co.id')
                                ->get();
                }
                
            } elseif(Auth::User()->id_position == 'STAFF' && Auth::User()->id_division == 'SALES' && Auth::User()->id_territory == $nik_sales->id_territory){
                $kirim = User::select('email')
                                ->where('id_position', 'MANAGER')
                                ->where('id_division', 'SALES')
                                ->where('id_territory', $nik_sales->id_territory)
                                ->orWhere('nik', $current_presales->nik)
                                ->orWhere('nik', $presales_manager->nik)
                                ->orWhere('email', 'nabil@sinergy.co.id')
                                ->orWhere('email', 'yuliane@sinergy.co.id')
                                ->get();
            } elseif(Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_position == 'MANAGER'){
                $kirim = User::select('email')
                                ->where('id_position', 'MANAGER')
                                ->where('id_division', 'SALES')
                                ->where('id_territory', $nik_sales->id_territory)
                                ->orWhere('nik', $current_presales->nik)
                                ->orWhere('nik', $presales_manager->nik)
                                ->orWhere('email', 'nabil@sinergy.co.id')
                                ->orWhere('email', 'yuliane@sinergy.co.id')
                                ->get();
            }

            /*$users = User::where('email','faiqoh@sinergy.co.id')->first();
            Notification::send($users, new Result());*/
           

        return redirect()->back();
    }

    public function update_next_status(Request $request){
        $lead_id = $request['lead_id_result2'];        

        $update = Sales::where('lead_id', $lead_id)->first();
        $update->result2 = $request['result2'];
        $update->update();

        return redirect()->back();
    }

    public function update_sd(Request $request, $lead_id)
    {

        $tampilkans = DB::table('sales_solution_design')
                    ->join('users','users.nik','=','sales_solution_design.nik')
                    ->select('sales_solution_design.lead_id','sales_solution_design.nik','sales_solution_design.assessment','sales_solution_design.pov','sales_solution_design.pd','sales_solution_design.pb','sales_solution_design.priority','sales_solution_design.project_size','users.name','sales_solution_design.status', 'sales_solution_design.assessment_date', 'sales_solution_design.pd_date', 'sales_solution_design.pov_date')
                    ->where('lead_id',$lead_id)
                    ->first();

        $update = solution_design::where('lead_id', $lead_id)->first();

        // if($request['project_budget'] != $request['project_budget_before']){
        //    $update->pb = $request['project_budget'];
        //    $update->update();
        //   }

        // if ($request['assesment'] == $request['assesment']) {
          if($request['assesment'] != $request['assesment_before']){
            $update->assessment = $request['assesment'];
            $update->assessment_date = date('Y-m-d H:i:s');
            $update->update();

            $tambah = new SalesChangeLog();
	        $tambah->lead_id = $request['lead_id'];
	        $tambah->nik = Auth::User()->nik;
	        $tambah->status = 'Update SD '. "(Assessment " .$request['assesment'].")";
	        $tambah->save();
          } else {
            $update->assessment = $request['assesment'];
            $update->assessment_date = $request['assesment_date_before'];
            $update->update();
          }

          if($request['propossed_design'] != $request['pd_before']){
            $update->pd = $request['propossed_design'];
            $update->pd_date = date('Y-m-d H:i:s');
            $update->update();

            $tambah = new SalesChangeLog();
	        $tambah->lead_id = $request['lead_id'];
	        $tambah->nik = Auth::User()->nik;
	        $tambah->status = 'Update SD '. "(Propossed design " . $request['propossed_design'].")";
	        $tambah->save();
          } else {
            $update->pd = $request['propossed_design'];
            $update->pd_date = $request['pd_date_before'];
            $update->update();
          }

          if($request['pov'] != $request['pov_before']){
            $update->pov = $request['pov'];
            $update->pov_date = date('Y-m-d H:i:s');
            $update->update();

            $tambah = new SalesChangeLog();
	        $tambah->lead_id = $request['lead_id'];
	        $tambah->nik = Auth::User()->nik;
	        $tambah->status = 'Update SD '. "(POV " .$request['pov'].")";
	        $tambah->save();
          } else {
            $update->pov = $request['pov'];
            $update->pov_date = $request['pov_date_before'];
            $update->update();
          }

          if(str_replace(',', '',$request['project_budget']) <= $request['amount_check']){
            if($request['project_budget'] != $request['project_budget_before']){
                $update->pb = str_replace(',', '',$request['project_budget']);
                $update->update();

                $tambah = new SalesChangeLog();
		        $tambah->lead_id = $request['lead_id'];
		        $tambah->nik = Auth::User()->nik;
		        $tambah->status = 'Update SD '. "(Project Budget " .$request['project_budget'].")";
		        $tambah->save();
            }
          } else {
            return back()->with('warning','Project Budget melebihi Amount!');
          }

          // if($request['project_budget'] != $request['project_budget_before']){
          //   $update->pb = $request['project_budget'];
          //   $update->update();
          // }
          if ($request['priority'] != $tampilkans->priority) {
              $update->priority = $request['priority'];

              $tambah = new SalesChangeLog();
              $tambah->lead_id = $request['lead_id'];
              $tambah->nik = Auth::User()->nik;
              $tambah->status = 'Update SD '. "(Priority " .$request['priority'].")";
              $tambah->save();
          }

          if ($request['proyek_size'] != $tampilkans->project_size) {
              $update->project_size = $request['proyek_size'];

              $tambah = new SalesChangeLog();
              $tambah->lead_id = $request['lead_id'];
              $tambah->nik = Auth::User()->nik;
              $tambah->status = 'Update SD '. "(Project Size " .$request['proyek_size'].")";
              $tambah->save();
          }
          
          
          $update->update();

        //   elseif($request['assesment'] == TRUE){
        //     $update->assessment = $request['assesment'];
        //     $update->assessment_date = date('Y-m-d H:i:s');
        //     $update->update();
        //   }
        //     $update->assessment = $request['assesment'];
        //     $update->update();
        // }else if ($request['assesment'] == TRUE) {
        //     $update->assessment = $request['assesment'];
        //     $update->assessment_date = date('Y-m-d H:i:s');
        //     $update->update();
        // }

        // if (is_null($request['propossed_design'])) {
        //    $update->pd = $request['propossed_design'];
        //    $update->update();
        // }else if ($request['propossed_design'] == TRUE) {
        //    $update->pd = $request['propossed_design'];
        //    $update->pd_date = date('Y-m-d H:i:s');
        //    $update->update(); 
        // }

        // if ( is_null($request['pov'])) {
        //   $update->pov = $request['pov'];
        //   $update->update();  
        // }else if ( $request['pov'] == TRUE) {
        //    $update->pov = $request['pov'];
        //    $update->pov_date = date('Y-m-d H:i:s');
        //    $update->update();   
        // }

        // if (is_null($request['project_budget'])) {   
        //     // $update->pb = $request['project_budget'];
        //     $update->pb = $format_rupiah;
        //     $update->update();
        // }else if ( $request['project_budget'] == TRUE) {
        //    // $update->pb = $request['project_budget'];
        //    $update->pb = $format_rupiah;
        //    $update->update();
        // }

        // if ( is_null($request['priority'])) {
        //   $update->priority = $request['priority'];
        //   $update->update();  
        // }else if ( $request['priority'] == TRUE) {
        //    $update->priority = $request['priority'];
        //    $update->update();   
        // }

        // if ($request['proyek_size'] == '') {   
        //     $update->project_size = $request['proyek_size'];
        //     $update->update();
        // }else if ( $request['proyek_size'] == TRUE) {
        //    $update->project_size = $request['proyek_size'];
        //    $update->update();   
        // }

        $lead_id = $request['lead_id'];        

        $update = Sales::where('lead_id', $lead_id)->first();
        $update->result = 'SD';
        $update->update();

        return redirect()->back();
    }

    public function update_tp(Request $request, $lead_id)
    {
        $compare_win_tp = TenderProcess::select('status')->where('lead_id', $lead_id)->first();
        $update = TenderProcess::where('lead_id', $lead_id)->first();
        
        if($compare_win_tp->status != 'closed') {
            $update->status = 'ready';
        }

        // $id_quote = $request['quote_before'];
        // $id_quote_true = $request['quote_number'];
        // $update_false = Quote::where('id_quote', $id_quote)->first();
        // $update_true = Quote::where('id_quote', $id_quote_true)->first();

        if($request['submit_price'] != $request['submit_price_before']){
 /*           $angka = $request['submit_price'];
            $format_rupiah = number_format($angka, '2', ',', '.'); */
           $update->submit_price = str_replace(',', '', $request['submit_price']);
           $update->update();
          }

        if (is_null( $request['lelang'])) {
           $update->auction_number = $request['lelang'];
           $update->update();
        }else if ($request['lelang'] == TRUE) {
            $update->auction_number = $request['lelang'];
            $update->update();
        }

        if (is_null($request['submit_price'])) {
           $update->submit_price = str_replace(',', '', $request['submit_price']);
           $update->update();
        }else if ($request['submit_price'] == TRUE) {
           $update->submit_price = str_replace(',', '', $request['submit_price']);
           $update->update(); 
        }

        if ( is_null($request['win_prob'])) {
          $update->win_prob = $request['win_prob'];
          $update->update();  
        }else if ( $request['win_prob'] == TRUE) {
           $update->win_prob = $request['win_prob'];
           $update->update();   
        }

        if (is_null($request['project_name'])) {   
            $update->project_name = $request['project_name'];
            $update->update();
        }else if ( $request['project_name'] == TRUE) {
           $update->project_name = $request['project_name'];
           $update->update();   
        }

        $edate = strtotime($_POST['submit_date']); 
        $edate = date("Y-m-d",$edate);

        if (is_null($request['submit_date'])) {   
            $update->submit_date = $edate;
            $update->update();
        }else if ( $request['submit_date'] == TRUE) {
           $update->submit_date = $edate;
           $update->update();   
        }

        if($request['submit_price'] != $request['submit_price_before']){
            $update->submit_price = str_replace(',', '', $request['submit_price']);
            $update->update();
          }

        if ( is_null($request['assigned_by'])) {
          $update->assigned_by = $request['assigned_by'];
          $update->update();  
        }else if ( $request['assigned_by'] == TRUE) {
           $update->assigned_by = $request['assigned_by'];
           $update->update();   
        }

//         if ($request['quote_number'] == TRUE) {
//             if($request['quote_number'] != $request['quote_before']) {
//                 $update->quote_number = $request['quote_number'];
//                 $update->update();

//                 if($update_false == TRUE){
//                     $update_false->status = 'F';
//                     $update_false->update();
//                 }

//                 if($update_true == TRUE){
//                     $update_true->status = 'T';
//                     $update_true->update();
//                 }
//             }
//         }else if($request['quote_number'] == NULL) {
//         	$q_num = $request['quote_before'];
//             $update->quote_number = $q_num;
//             $update->update();
// /*
//             if($update_false == TRUE){
//                 $update_false->status = 'F';
//                 $update_false->update();
//             }

//             if($update_true == TRUE){
//                 $update_true->status = 'T';
//                 $update_true->update();
//             }*/
//         }

        if (is_null($request['quote_number'])) {   
            $update->quote_number2 = $request['quote_number'];
            $update->update();
        }else if ( $request['quote_number'] == TRUE) {
           $update->quote_number2 = $request['quote_number'];
           $update->update();   
        }

        $tambah = new SalesChangeLog();
        $tambah->lead_id = $request['lead_id'];
        $tambah->nik = Auth::User()->nik;
        $tambah->status = 'Update TP';
        if ($request['deal_price'] == '') {
           $tambah->deal_price = $request['deal_price'];
        }else{
           $tambah->deal_price = str_replace(',', '', $request['deal_price']); 
        }
        
        // $tambah->submit_price = $request['submit_price'];
        $tambah->submit_price = str_replace(',', '', $request['submit_price']);
        $tambah->save();
 
        $compare_win_lead = Sales::select('result')->where('lead_id', $lead_id)->first();
        $update_lead = Sales::where('lead_id', $lead_id)->first();

        if($compare_win_lead->result != 'WIN') {
            $update_lead->result = 'TP';
        }

        if ($request['deal_price'] == '') {
           $update_lead->deal_price = $request['deal_price'];
        }else{
           $update_lead->deal_price = str_replace(',', '', $request['deal_price']); 
        }

        if ($request['submit_price'] != '') {
            $update_lead->amount = str_replace(',', '', $request['submit_price']);
        } elseif ($request['submit_price'] == '') {
            $update_lead->amount = $request['amount_before'];
        }

        if ( is_null($request['project_class'])) {
            $update_lead->project_class = $request['project_class'];
        }else if ( $request['project_class'] == TRUE) {
            $update_lead->project_class = $request['project_class'];
                if($request['project_class'] == 'multiyears' || $request['project_class'] == 'blanket') {

                    if ( is_null($request['jumlah_tahun'])) {
                        $update_lead->jumlah_tahun = $request['jumlah_tahun'];
                    }else if ( $request['jumlah_tahun'] == TRUE) {
                        $update_lead->jumlah_tahun = $request['jumlah_tahun'];
                    }

                    if ($request['deal_price_total'] == '') {
                        $update_lead->deal_price_total = $request['deal_price_total'];
                    }else{
                        $update_lead->deal_price_total = str_replace(',', '', $request['deal_price_total']); 
                    }
                } else {
                    $update_lead->jumlah_tahun = NULL;
                    $update_lead->deal_price_total = NULL;
                }
        }
        $update_lead->update();

        return redirect()->back();
    }

    public function add_changelog_progress(Request $request) {

        $tambah = new SalesChangeLog();
        $tambah->lead_id = $request['changelog_lead_id'];
        $tambah->nik = Auth::User()->nik;
        $tambah->status = $request['changelog_progress'];
        $tambah->progress_date = $request['changelog_date'];
        $tambah->deal_price = null;
        $tambah->submit_price = null;
        $tambah->save();

        return redirect()->back();

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($lead_id)
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
    public function destroy($lead_id)
    {
        $hapus = Sales::find($lead_id);
        $hapus->delete();

        return redirect()->back();
    }

    public function delete_update_status($lead_id) {
        $update = Sales::find($lead_id);
        $update->result = 'hmm';
        $update->update();

        return redirect()->back();
    }

    public function add_po(Request $request)
    {
        $edate = strtotime($_POST['date_po']); 
        $edate = date("Y-m-d",$edate);

        $tambah = new POCustomer;
        $tambah->lead_id = $request['lead_id_po'];
        $tambah->id_tp   = $request['id_tp_po'];
        $tambah->no_po   = $request['no_po'];
        $tambah->date    = $edate;
        $tambah->note    = $request['note_po'];
        $tambah->nominal = str_replace(',', '', $request['nominal_po']);
        $tambah->save();

        return redirect()->back();
    }

    public function update_po(Request $request)
    {
        $update = POCustomer::where('id_tb_po_cus',$request['id_po_customer_edit'])->first();
        $update->no_po   = $request['no_po_edit'];
        $update->date    = $request['date_po_edit'];
        $update->note    = $request['note_po_edit'];
        $update->nominal = str_replace(',', '', $request['nominal_po_edit']);
        $update->update();

        return redirect()->back();
    }

     public function delete_po($id_tb_po_cus)
    {
        $delete = POCustomer::find($id_tb_po_cus);
        $delete->delete();

        return redirect()->back();
    }

    public function customer_index()
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
            ->select('opp_name','nik')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','')
            ->orderBy('created_at','desc')
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
        }

        $data = TB_Contact::all();  

        return view('sales/customer',compact('data', 'notif','notifOpen','notifsd','notiftp', 'notifClaim'));
    }

    public function customer_store(Request $request)
    {
        $request->validate([
            'code_name' => 'required|unique:tb_contact,code',
        ]);

        $tambah = new TB_Contact();
        $tambah->code = $request['code_name'];
        $tambah->customer_legal_name = $request['name_contact'];
        $tambah->brand_name = $request['brand_name'];
        $tambah->office_building = nl2br($request['office_building']);
        $tambah->street_address = $request['street_address'];
        $tambah->city = $request['city'];
        $tambah->province = $request['province'];
        $tambah->postal = $request['postal'];
        $tambah->phone = $request['phone'];
        $tambah->save();

        return redirect('customer');
    }

    public function update_customer(Request $request)
    {   
        $id_contact = $request['id_contact'];

        $update = TB_Contact::where('id_customer', $id_contact)->first();
        $update->code = $request['code_name'];
        $update->customer_legal_name = $request['name_contact'];
        $update->brand_name = $request['brand_name'];
        $update->office_building = $request['office_building'];
        $update->street_address = $request['street_address'];
        $update->city = $request['city'];
        $update->province = $request['province'];
        $update->postal = $request['postal'];
        $update->phone = $request['phone'];
        $update->update();//

        return redirect('customer')->with('update', 'Update Contact Successfully!');;
    }

    /*public function total_amount()
    {
       $total_amount = DB::table('sales_lead_register')
                        ->sum('amount')
                        ->get();
        print_r($total_amount);
    }*/

    public function destroy_customer($id_customer)
    {
        $hapus = TB_Contact::find($id_customer);
        $hapus->delete();

        return redirect()->back();
    }

	public function sales_project_index()
    {

	    $nik = Auth::User()->nik;
	    $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
	    $ter = $territory->id_territory;
	    $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
	    $div = $division->id_division;
	    $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
	    $pos = $position->id_position;

        $pops = SalesProject::select('id_project')->orderBy('created_at','desc')->first();

        if ($div == 'SALES' && $pos != 'ADMIN') {
            $salessp = DB::table('tb_id_project')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                    ->join('sales_tender_process','sales_tender_process.lead_id','=','sales_lead_register.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
                    ->select('tb_id_project.customer_name','tb_id_project.id_project','tb_id_project.date','tb_id_project.no_po_customer','sales_lead_register.opp_name','users.name','tb_id_project.amount_idr',DB::raw('(`tb_id_project`.`amount_idr`*10)/11 as `amount_idr_before_tax` '),'tb_id_project.amount_usd','sales_lead_register.lead_id','sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro','tb_id_project.invoice','progres','name_project','tb_id_project.created_at','customer_legal_name','sales_name','sales_tender_process.quote_number_final')
                    ->where('sales_lead_register.nik',$nik)
                    ->orWhere('sales_name',Auth::User()->name)
                    ->where('id_company','1')
                    ->whereYear('tb_id_project.created_at',date('Y'))
                    ->get();

            $salesmsp = DB::table('tb_id_project')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->join('tb_pid','tb_pid.lead_id','=','tb_id_project.lead_id','left')
                    ->join('tb_quote_msp','tb_quote_msp.id_quote','=','tb_pid.no_quo','left')
                    ->join('tb_company','tb_company.id_company','=','users.id_company')
                    ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
                    ->select('tb_id_project.customer_name','tb_id_project.id_project','tb_id_project.date','tb_id_project.no_po_customer','sales_lead_register.opp_name','users.name','tb_id_project.amount_idr',DB::raw('(`tb_id_project`.`amount_idr`*10)/11 as `amount_idr_before_tax` '),'tb_id_project.amount_usd','sales_lead_register.lead_id','sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro','tb_id_project.invoice','tb_id_project.status','name_project','tb_id_project.created_at','sales_name','customer_legal_name','users.id_company','tb_quote_msp.quote_number','tb_pid.no_po')
                    ->where('users.id_company','2')
                    ->whereYear('tb_id_project.created_at',date('Y'))
                    ->where('tb_id_project.status','!=','WO')
                    ->get();
        }elseif ($div == 'TECHNICAL' && $pos == 'MANAGER' || $pos == 'DIRECTOR') {
            $salessp = DB::table('tb_id_project')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                    ->join('sales_tender_process','sales_tender_process.lead_id','=','sales_lead_register.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
                    ->select('tb_id_project.customer_name','tb_id_project.id_project','tb_id_project.date','tb_id_project.no_po_customer','sales_lead_register.opp_name','users.name','tb_id_project.amount_idr',DB::raw('(`tb_id_project`.`amount_idr`*10)/11 as `amount_idr_before_tax` '),'tb_id_project.amount_usd','sales_lead_register.lead_id','sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro','tb_id_project.invoice','progres','name_project','tb_id_project.created_at','customer_legal_name','sales_name','sales_tender_process.quote_number_final')
                    ->where('id_company','1')
                    ->whereYear('tb_id_project.created_at',date('Y'))
                    ->get(); 

            $salesmsp = DB::table('tb_id_project')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->join('tb_pid','tb_pid.lead_id','=','tb_id_project.lead_id','left')
                    ->join('tb_quote_msp','tb_quote_msp.id_quote','=','tb_pid.no_quo','left')
                    ->join('tb_company','tb_company.id_company','=','users.id_company')
                    ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
                    ->select('tb_id_project.customer_name','tb_id_project.id_project','tb_id_project.date','tb_id_project.no_po_customer','sales_lead_register.opp_name','users.name','tb_id_project.amount_idr',DB::raw('(`tb_id_project`.`amount_idr`*10)/11 as `amount_idr_before_tax` '),'tb_id_project.amount_usd','sales_lead_register.lead_id','sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro','tb_id_project.invoice','tb_id_project.status','name_project','tb_id_project.created_at','sales_name','customer_legal_name','users.id_company','tb_quote_msp.quote_number','tb_pid.no_po')
                    ->where('users.id_company','2')
                    ->whereYear('tb_id_project.created_at',date('Y'))
                    ->where('tb_id_project.status','!=','WO')
                    ->get();
        }elseif($div == 'FINANCE'){
            if ($pos == 'MANAGER') {
                $salessp = DB::table('tb_id_project')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                    ->join('sales_tender_process','sales_tender_process.lead_id','=','sales_lead_register.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
                    ->select('tb_id_project.customer_name','tb_id_project.id_project','tb_id_project.date','tb_id_project.no_po_customer','users.name','tb_id_project.amount_idr','tb_id_project.amount_usd',DB::raw('(`tb_id_project`.`amount_idr`*10)/11 as `amount_idr_before_tax` '),'sales_lead_register.lead_id','sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro','tb_id_project.invoice','sales_name','progres','name_project','tb_id_project.created_at','customer_legal_name','sales_tender_process.quote_number_final')
                    ->where('id_company','1')
                    ->whereYear('tb_id_project.created_at',date('Y'))
                    ->get(); 

                $salesmsp = DB::table('tb_id_project')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->join('tb_pid','tb_pid.lead_id','=','tb_id_project.lead_id','left')
                    ->join('tb_quote_msp','tb_quote_msp.id_quote','=','tb_pid.no_quo','left')
                    ->join('tb_company','tb_company.id_company','=','users.id_company')
                    ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
                    ->select('tb_id_project.customer_name','tb_id_project.id_project','tb_id_project.date','tb_id_project.no_po_customer','sales_lead_register.opp_name','users.name','tb_id_project.amount_idr',DB::raw('(`tb_id_project`.`amount_idr`*10)/11 as `amount_idr_before_tax` '),'tb_id_project.amount_usd','sales_lead_register.lead_id','sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro','tb_id_project.invoice','tb_id_project.status','name_project','tb_id_project.created_at','sales_name','customer_legal_name','users.id_company','tb_quote_msp.quote_number','tb_pid.no_po')
                    ->where('users.id_company','2')
                    ->whereYear('tb_id_project.created_at',date('Y'))
                    ->where('tb_id_project.status','!=','WO')
                    ->get();
            }elseif ($pos == 'STAFF') {
                $salessp = DB::table('tb_id_project')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                    ->join('sales_tender_process','sales_tender_process.lead_id','=','sales_lead_register.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
                    ->select('tb_id_project.customer_name','tb_id_project.id_project','tb_id_project.date','tb_id_project.no_po_customer','sales_lead_register.opp_name','users.name','sales_lead_register.lead_id',DB::raw('(`tb_id_project`.`amount_idr`*10)/11 as `amount_idr_before_tax` '),'sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro','tb_id_project.invoice','sales_name','progres','name_project','tb_id_project.created_at','customer_legal_name','sales_tender_process.quote_number_final')
                    ->where('id_company','1')
                    ->whereYear('tb_id_project.created_at',date('Y'))
                    ->get();

                $salesmsp = DB::table('tb_id_project')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->join('tb_pid','tb_pid.lead_id','=','tb_id_project.lead_id','left')
                    ->join('tb_quote_msp','tb_quote_msp.id_quote','=','tb_pid.no_quo','left')
                    ->join('tb_company','tb_company.id_company','=','users.id_company')
                    ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
                    ->select('tb_id_project.customer_name','tb_id_project.id_project','tb_id_project.date','tb_id_project.no_po_customer','sales_lead_register.opp_name','users.name','tb_id_project.amount_idr',DB::raw('(`tb_id_project`.`amount_idr`*10)/11 as `amount_idr_before_tax` '),'tb_id_project.amount_usd','sales_lead_register.lead_id','sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro','tb_id_project.invoice','tb_id_project.status','name_project','tb_id_project.created_at','sales_name','customer_legal_name','users.id_company','tb_quote_msp.quote_number','tb_pid.no_po')
                    ->where('users.id_company','2')
                    ->whereYear('tb_id_project.created_at',date('Y'))
                    ->where('tb_id_project.status','!=','WO')
                    ->get();
            }
        }
        else{
            $salessp = DB::table('tb_id_project')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                    ->join('sales_tender_process','sales_tender_process.lead_id','=','sales_lead_register.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
                    ->select('tb_id_project.customer_name','tb_id_project.id_project','tb_id_project.date','tb_id_project.no_po_customer','sales_lead_register.opp_name','users.name','sales_lead_register.lead_id',DB::raw('(`tb_id_project`.`amount_idr`*10)/11 as `amount_idr_before_tax` '),'sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro','tb_id_project.invoice','tb_id_project.status','progres','name_project','tb_id_project.created_at','customer_legal_name','sales_name','sales_tender_process.quote_number_final')
                    ->where('id_company','1')
                    ->whereYear('tb_id_project.created_at',date('Y'))
                    ->get();

            $salesmsp = DB::table('tb_id_project')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->join('tb_pid','tb_pid.lead_id','=','tb_id_project.lead_id','left')
                    ->join('tb_quote_msp','tb_quote_msp.id_quote','=','tb_pid.no_quo','left')
                    ->join('tb_company','tb_company.id_company','=','users.id_company')
                    ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
                    ->select('tb_id_project.customer_name','tb_id_project.id_project','tb_id_project.date','tb_id_project.no_po_customer','sales_lead_register.opp_name','users.name','tb_id_project.amount_idr',DB::raw('(`tb_id_project`.`amount_idr`*10)/11 as `amount_idr_before_tax` '),'tb_id_project.amount_usd','sales_lead_register.lead_id','sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro','tb_id_project.invoice','tb_id_project.status','name_project','tb_id_project.created_at','sales_name','customer_legal_name','users.id_company','tb_quote_msp.quote_number','tb_pid.no_po')
                    ->where('users.id_company','2')
                    ->whereYear('tb_id_project.created_at',date('Y'))
                    ->where('tb_id_project.status','!=','WO')
                    ->get();
        }

        //Buat yang sekali pakai

      /*  $lead_sp = DB::table('sales_lead_register')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->select('lead_id','opp_name')
                    ->where('result','WIN')
                    ->where('year','2019')
                    ->where('id_company','1')
                    ->where('status_sho',null)
                    ->get();

        $lead_msp = DB::table('sales_lead_register')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->select('lead_id','opp_name')
                    ->where('result','WIN')
                    ->where('year','2019')
                    ->where('id_company','2')
                    ->where('status_sho',null)
                    ->get();*/


        $lead_sp = DB::table('sales_lead_register')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->join('tb_pid','tb_pid.lead_id','=','sales_lead_register.lead_id')
                    ->select('sales_lead_register.lead_id','opp_name','pid','tb_pid.no_po')
                    ->where('result','WIN')
                    ->where('year',date('Y'))
                    ->where('sales_lead_register.status_sho',null)
                    ->where('users.id_company','1')
                    ->get();

        $lead_msp = DB::table('sales_lead_register')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->join('tb_pid','tb_pid.lead_id','=','sales_lead_register.lead_id')
                    ->select('sales_lead_register.lead_id','opp_name','pid','tb_pid.no_po')
                    ->where('result','WIN')
                    ->where('year',date('Y'))
                    ->where('id_company','2')
                    ->where('sales_lead_register.status_sho',null)
                    ->get();

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

        $hitung_msp = DB::table('tb_id_project')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->select('id_project','users.id_company')
                    ->orderBy('id_project','desc')
                    ->whereYear('tb_id_project.created_at',date('Y'))
                    ->where('users.id_company','2')
                    ->where('status','!=','WO')
                    ->get();

        $pid_request = PIDRequest::join('tb_quote_msp','tb_quote_msp.quote_number','=','tb_pid_request.no_quotation')
                        ->join('users','users.nik','=','tb_quote_msp.nik')
                        ->join('tb_company','tb_company.id_company','=','users.id_company')
                        ->select('tb_pid_request.created_at',
                        'tb_quote_msp.project',
                        'tb_quote_msp.quote_number',
                        'users.name',
                        'tb_quote_msp.amount',
                        'tb_pid_request.date_quotation',
                        'tb_pid_request.note',
                        'tb_pid_request.status',
                        'tb_pid_request.no_quotation',
                        'tb_company.code_company',
                        'tb_pid_request.id_pid_request')
                        ->where('tb_pid_request.status','requested')
                        ->get(); 

        $pid_request_lead = PID::join('tb_quote_msp','tb_quote_msp.id_quote','=','tb_pid.no_quo','left')->join('sales_lead_register','sales_lead_register.lead_id','=','tb_pid.lead_id')
                        ->join('users','users.nik','=','sales_lead_register.nik')
                        ->join('tb_company','tb_company.id_company','=','users.id_company')
                        ->select('tb_quote_msp.project',
                        'tb_quote_msp.quote_number',
                        'users.name',
                        'tb_quote_msp.amount',
                        'tb_company.code_company',
                        'tb_pid.no_po',
                        'tb_pid.created_at',
                        'sales_lead_register.opp_name',
                        'users.name','tb_pid.date_po',
                        'tb_quote_msp.date',
                        'tb_pid.amount_pid',
                        'tb_pid.id_pid')
                        ->where('tb_pid.status','requested')
                        ->get(); 

        $pid_request_done = PIDRequest::where('status','=','done')->get();   

        $pid_request_lead_done = PID::where('status','=','done')->get();     


      return view('sales/sales_project',compact('hitung_msp','salessp','salesmsp','lead_sp','lead_msp','notif','notifOpen','notifsd','notiftp', 'notifClaim','pops','datas','pid_request','pid_request_done','pid_request_lead','pid_request_lead_done'));
    }

    public function getAcceptProjectID(Request $request){
        $po_number = PID::join('tb_quote_msp','tb_quote_msp.id_quote','=','tb_pid.no_quo','left')->join('sales_lead_register', 'sales_lead_register.lead_id', '=', 'tb_pid.lead_id')->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')->join('users', 'users.nik', '=', 'sales_lead_register.nik')->join('sales_tender_process', 'sales_lead_register.lead_id', '=', 'sales_tender_process.lead_id')->select('sales_lead_register.opp_name', 'name', 'no_po', 'date_po', 'amount_pid', 'quote_number_final', 'sales_lead_register.lead_id', 'tb_contact.code','tb_quote_msp.quote_number','tb_quote_msp.date','tb_quote_msp.amount')->where('id_pid',$request->id)
            ->first();

        return $po_number;
    }

    public function getRequestProjectID(Request $request){
        $quote_number = PIDRequest::join('tb_quote_msp', 'tb_quote_msp.quote_number', '=', 'tb_pid_request.no_quotation')->join('tb_contact','tb_contact.id_customer','=','tb_quote_msp.customer_id')->join('users', 'users.nik', '=', 'tb_quote_msp.nik')
            ->select('project', 'name', 'no_quotation', 'tb_quote_msp.date', 'tb_quote_msp.amount','tb_contact.id_customer')->where('id_pid_request',$request->id)
            ->first();
        
        $quote_number->customer = QuoteMSP::where('quote_number',$quote_number->no_quotation)->first()->to;
        return $quote_number;
    }

    public function submitRequestID(Request $request){

    }

    public function store_sales_project(Request $request)
    {
        $year = date('Y');
        $array_bln = array(1 => "I" ,"II","III","IV","V","VI","VII","VIII","IX","X","XI","XII");
        $bln = $array_bln[date('n')];

        $sales = $request['sales'];
        $contact = $request['customer_name'];
        $name = substr($contact, 0,4);
        $company = DB::table('tb_company')
                    ->join('users','users.id_company','=','tb_company.id_company')
                    ->select('code_company')
                    ->where('nik', $sales)
                    ->first();

        if (substr($request['date'], 6,4) != $year) {
            return redirect()->back()->with('gagal', 'Tanggal Yang Kamu Input Tidak Valid!');
        }
                    
        $hitung_sip = DB::table('tb_id_project')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->select('id_project','users.id_company')
                    ->orderBy('id_project','desc')
                    ->whereYear('tb_id_project.created_at',$year)
                    ->where('users.id_company','1')
                    ->first();

        $hitung_msp = DB::table('tb_id_project')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->select('id_project','users.id_company')
                    ->orderBy('id_project','desc')
                    ->whereYear('tb_id_project.created_at',date('Y'))
                    ->where('users.id_company','2')
                    ->where('status','<>','WO')
                    ->get();

        // return $hitung_msp;

        $cek_sip = Sales::join('users','users.nik','=','sales_lead_register.nik')
                    ->select('id_company')
                    ->where('lead_id',$request['customer_name'])
                    ->first();

        $cek_pid = Salesproject::select('lead_id')->where('lead_id',$contact)->count('lead_id');

        $counts = count($hitung_sip);

        $countss = count($hitung_msp);

        $id_pro_payung = DB::table('tb_id_project')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->select('id_project','users.id_company')
                    ->where('tb_id_project.id_pro',$request['payung_id'])
                    ->first();

        $no_po = PID::select('no_po')->where('lead_id',$request['customer_name'])->first();

        $project_name = Sales::join('users','users.nik','=','sales_lead_register.nik')->select('opp_name','name')->where('lead_id',$request['customer_name'])->first();

        $edate = strtotime($request['date']); 
        $edate = date("Y-m-d",$edate);

        if ($cek_sip->id_company == '1') {
            if ($counts > 0) {
                $increment = round($hitung_sip->id_project);
            }else{
              $increment = 0;
            }
            $nomor = $increment+1;

            if($nomor <= 9){
                $nomor = '00' . $nomor;
            }elseif($nomor > 9 && $nomor < 99){
                $nomor = '0' . $nomor;
            }

            $project = $nomor.'/'.$name .'/'. 'SIP/' . $bln .'/'. substr($edate, 0,4);

            $lead_id = $request['customer_name'];

            $cek_result = Sales::select('result','pid','id_customer')->where('lead_id',$contact)->first();

            if ($cek_result->result == 'WIN') {

                if ($cek_result->pid == NULL) {
                    $tambah = new SalesProject();
                    $tambah->customer_name = TB_Contact::where('id_customer',$cek_result->id_customer)->first()->customer_legal_name;
                    $tambah->date = $edate;
                    $tambah->id_project = $project;
                    $tambah->nik = $request['sales'];
                    $tambah->no_po_customer = $no_po->no_po;
                    $tambah->lead_id = $request['customer_name'];
                    $tambah->name_project = $project_name->opp_name;
                    $tambah->sales_name = $project_name->name;
                    $tambah->amount_idr = str_replace(',', '', $request['amount']);
                    $tambah->amount_usd = $request['kurs'];
                    $tambah->note = $request['note'];
                    if (is_null($request['payungs'])) {
                        $tambah->status = 'NEW';
                    }else{
                        $tambah->status = $request['payungs'];
                    }
                    $tambah->save();

                    $update = Sales::where('lead_id', $lead_id)->first();
                    $update->status_sho = 'SHO';
                    $update->pid = $cek_pid + 1;
                    $update->update();

                    $update = PID::where('lead_id',$request['customer_name'])->first();
                    $update->status = 'done';
                    $update->save();
                
                }else{
                        $cek_id_project = Salesproject::select('status','id_pro')->where('id_pro',$request['payung_id'])->where('status','SP')->first();

                        if ($cek_id_project == NULL) {

                            $tambah = new SalesProject();

                            $tambah->customer_name = TB_Contact::where('id_customer',$cek_result->id_customer)->first()->customer_legal_name;
                            $edate = strtotime($request['date']); 
                            $edate = date("Y-m-d",$edate);
                            $tambah->date = $edate;
                            $tambah->id_project = $project;
                            $tambah->nik = $request['sales'];
                            $tambah->no_po_customer = $no_po->no_po;
                            $tambah->lead_id = $request['customer_name'];
                            $tambah->name_project = $project_name->opp_name;
                            $tambah->sales_name = $project_name->name;
                            $tambah->amount_idr = str_replace(',', '', $request['amount']);
                            $tambah->amount_usd = $request['kurs'];
                            $tambah->note = $request['note'];
                            if (is_null($request['payungs'])) {
                                $tambah->status = 'NEW';
                            }else{
                                $tambah->status = $request['payungs'];
                            }
                            $tambah->save();

                            $update = Sales::where('lead_id', $lead_id)->first();
                            $update->status_sho = 'SHO';
                            $update->pid = $cek_pid + 1;
                            $update->update();

                            $update = PID::where('lead_id',$request['customer_name'])->first();
                            $update->status = 'done';
                            $update->save();
                            # code...
                        
                        }else{

                            if ($cek_id_project->status == 'SP' && $cek_result->result == 'SPECIAL' || $cek_id_project->status == 'SP' && $cek_result->result == 'WIN') {

                                $hitung_detail_sip = DB::table('tb_detail_id_project')
                                    ->join('tb_id_project', 'tb_id_project.id_pro', '=', 'tb_detail_id_project.id_pro')
                                    ->select('tb_detail_id_project.id_project')
                                    ->orderBy('tb_detail_id_project.id_project','desc')
                                    ->where('tb_id_project.id_pro',$cek_id_project->id_pro)
                                    ->first();

                                $counts_detail = count($hitung_detail_sip);

                                if ($counts_detail > 0) {
                                    $increment_detail = round($hitung_detail_sip->id_project);
                                }else{
                                    $increment_detail = round($hitung_detail_sip);
                                }
                                $nomor_detail = $increment_detail+1;

                                if($nomor_detail <= 9){
                                    $nomor_detail = '00' . $nomor_detail;
                                }elseif($nomor_detail > 9 && $nomor_detail < 99){
                                    $nomor_detail = '0' . $nomor_detail;
                                }

                                $detail_project = $nomor_detail. '/' . $id_pro_payung->id_project . '/' . 'KP';
                                
                                $tambah_detail = new Detail_IdProject();
                                $tambah_detail->id_project = $detail_project;
                                $tambah_detail->id_pro = $cek_id_project->id_pro;
                                $tambah_detail->date = $edate;
                                $tambah_detail->no_po_customer = $no_po->no_po;
                                $tambah_detail->amount_idr = str_replace(',', '', $request['amount']);
                                $tambah_detail->save();

                                $count_detail_pro = Detail_IdProject::where('id_pro',$cek_id_project->id_pro)->count('id_project');

                                $update = Sales::where('lead_id', $contact)->first();
                                $update->pid = $count_detail_pro + 1;
                                $update->update();

                                $update = PID::where('lead_id',$request['customer_name'])->first();
                                $update->status = 'done';
                                $update->save();
                                
                                }else{

                                $tambah = new SalesProject();
                                $tambah->customer_name = TB_Contact::where('id_customer',$cek_result->id_customer)->first()->customer_legal_name;
                                $edate = strtotime($request['date']); 
                                $edate = date("Y-m-d",$edate);
                                $tambah->date = $edate;
                                $tambah->id_project = $project;
                                $tambah->nik = $request['sales'];
                                $tambah->no_po_customer = $no_po->no_po;
                                $tambah->lead_id = $request['customer_name'];
                                $tambah->name_project = $project_name->opp_name;
                                $tambah->sales_name = $project_name->name;
                                $tambah->amount_idr = str_replace(',', '', $request['amount']);
                                $tambah->amount_usd = $request['kurs'];
                                $tambah->note = $request['note'];
                                $tambah->status = 'SP';
                                $tambah->save();

                                $update = PID::where('lead_id',$request['customer_name'])->first();
                                $update->status = 'done';
                                $update->save();
                            }

                        }
                        
                }
                
            }else if ($cek_result->result == 'SPECIAL') {

                if ($cek_result->pid == NULL) {
                    $tambah = new SalesProject();
                    $tambah->customer_name = TB_Contact::where('id_customer',$cek_result->id_customer)->first()->customer_legal_name;
                    $edate = strtotime($request['date']); 
                    $edate = date("Y-m-d",$edate);
                    $tambah->date = $edate;
                    $tambah->id_project = $project;
                    $tambah->nik = $request['sales'];
                    $tambah->no_po_customer = $no_po->no_po;
                    $tambah->lead_id = $request['customer_name'];
                    $tambah->name_project = $project_name->opp_name;
                    $tambah->sales_name = $project_name->name;
                    $tambah->amount_idr = str_replace(',', '', $request['amount']);
                    $tambah->amount_usd = $request['kurs'];
                    $tambah->note = $request['note'];
                    if (is_null($request['payungs'])) {
                        $tambah->status = 'NEW';
                    }else{
                        $tambah->status = $request['payungs'];
                    }
                    $tambah->save();

                    $update = Sales::where('lead_id', $lead_id)->first();
                    $update->status_sho = 'SHO';
                    $update->pid = $cek_pid + 1;
                    $update->update();

                    $update = PID::where('lead_id',$request['customer_name'])->first();
                    $update->status = 'done';
                    $update->save();

                }else {
                    $cek_id_project = Salesproject::select('status','id_pro')->where('id_pro',$request['payung_id'])->where('status','SP')->first();
            

                    $hitung_detail_sip = DB::table('tb_detail_id_project')
                        ->join('tb_id_project', 'tb_id_project.id_pro', '=', 'tb_detail_id_project.id_pro')
                        ->select('tb_detail_id_project.id_project')
                        ->orderBy('tb_detail_id_project.id_project','desc')
                        ->where('tb_detail_id_project.id_pro',$cek_id_project->id_pro)
                        ->first();

                    $counts_detail = count($hitung_detail_sip);

                    if ($counts_detail > 0) {
                        $increment_detail = round($hitung_detail_sip->id_project);
                    }else{
                        $increment_detail = round($hitung_detail_sip);
                    }
                    $nomor_detail = $increment_detail+1;

                    if($nomor_detail <= 9){
                        $nomor_detail = '00' . $nomor_detail;
                    }elseif($nomor_detail > 9 && $nomor_detail < 99){
                        $nomor_detail = '0' . $nomor_detail;
                    }

                    $detail_project = $nomor_detail. '/' . $id_pro_payung->id_project . '/' . 'KP';
                    
                    $tambah_detail = new Detail_IdProject();
                    $tambah_detail->id_project = $detail_project;
                    $tambah_detail->id_pro = $cek_id_project->id_pro;
                    $tambah_detail->date = $edate;
                    $tambah_detail->no_po_customer = $no_po->no_po;
                    $tambah_detail->amount_idr = str_replace(',', '', $request['amount']);
                    $tambah_detail->save();

                    $count_detail_pro = Detail_IdProject::where('id_pro',1)->count('id_project');

                    $update = Sales::where('lead_id', $contact)->first();
                    $update->pid = $count_detail_pro + 1;
                    $update->update();

                    $update = PID::where('lead_id',$request['customer_name'])->first();
                    $update->status = 'done';
                    $update->save();
                
                }
            
            }

        }else if($cek_sip->id_company == '2'){

            if ($countss > 0) {
              $increment = $countss;
            }else{
              $increment = 0;
            }
            $nomor = $increment+1;

            if($nomor <= 9){
                $nomor = '00' . $nomor;
            }elseif($nomor > 9 && $nomor < 99){
                $nomor = '0' . $nomor;
            }

            $name_msp = $request['id_cus'];

            $project = $nomor.'/'. $name_msp .'/'. 'MSP/' .$bln .'/'. date('Y');

            $lead_id = $request['customer_name'];

            $cek_result = Sales::select('result','pid','id_customer')->where('lead_id',$contact)->first();

            if ($cek_result->result == 'WIN') {

                if ($cek_result->pid == NULL) {
                    $tambah = new SalesProject();
                    $tambah->customer_name = TB_Contact::where('id_customer',$cek_result->id_customer)->first()->customer_legal_name;
                    $edate = strtotime($request['date']); 
                    $edate = date("Y-m-d",$edate);
                    $tambah->date = $edate;
                    $tambah->id_project = $project;
                    $tambah->nik = $request['sales'];
                    if ($request['inputPO'] == '') {
                       $tambah->no_po_customer = $request['quote'];
                    }else{
                        $tambah->no_po_customer = $request['p_order'];
                    }
                    $tambah->lead_id = $request['customer_name'];
                    // $tambah->sales_name = DB::table('users')->where('nik',QuoteMSP::where('quote_number',$request['quote'])->first()->nik)->first()->name;
                    $tambah->name_project = $project_name->opp_name;
                    $tambah->amount_idr = str_replace(',', '', $request['amount']);
                    $tambah->amount_usd = $request['kurs'];
                    $tambah->note = $request['note'];
                    if (is_null($request['payungs'])) {
                        $tambah->status = 'NEW';
                    }else{
                        $tambah->status = $request['payungs'];
                    }
                    $tambah->save();

                    $update = PID::where('lead_id',$request['customer_name'])->first();
                    $update->status = 'done';
                    $update->save();

                    // $update = Sales::where('lead_id', $lead_id)->first();
                    // $update->status_sho = 'SHO';
                    // $update->pid = $cek_pid + 1;
                    // $update->update();

                }else{

                    $cek_id_project = Salesproject::select('status','id_pro')->where('id_pro',$request['payung_id'])->where('status','SP')->first();

                    if ($cek_id_project == NULL) {

                        $tambah = new SalesProject();
                        $tambah->customer_name = TB_Contact::where('id_customer',$cek_result->id_customer)->first()->customer_legal_name;
                        $edate = strtotime($request['date']); 
                        $edate = date("Y-m-d",$edate);
                        $tambah->date = $edate;
                        $tambah->id_project = $project;
                        $tambah->nik = $request['sales'];
                        if ($request['inputPO'] == '') {
                           $tambah->no_po_customer = $request['quote'];
                        }else{
                            $tambah->no_po_customer = $request['p_order'];
                        }
                        $tambah->lead_id = $request['customer_name'];
                        // $tambah->sales_name = DB::table('users')->where('nik',QuoteMSP::where('quote_number',$request['quote'])->first()->nik)->first()->name;
                        $tambah->name_project = $project_name->opp_name;
                        $tambah->amount_idr = str_replace(',', '', $request['amount']);
                        $tambah->amount_usd = $request['kurs'];
                        $tambah->note = $request['note'];
                        if (is_null($request['payungs'])) {
                            $tambah->status = 'NEW';
                        }else{
                            $tambah->status = $request['payungs'];
                        }
                        $tambah->save();

                        $update = PID::where('lead_id',$request['customer_name'])->first();
                        $update->status = 'done';
                        $update->save();


                    }else{

                        if ($cek_id_project->status == 'SP' && $cek_result->result == 'SPECIAL' || $cek_id_project->status == 'SP' && $cek_result->result == 'WIN') {

                            $hitung_detail_sip = DB::table('tb_detail_id_project')
                                ->join('tb_id_project', 'tb_id_project.id_pro', '=', 'tb_detail_id_project.id_pro')
                                ->select('tb_detail_id_project.id_project')
                                ->orderBy('tb_detail_id_project.id_project','desc')
                                ->where('tb_id_project.id_pro',$cek_id_project->id_pro)
                                ->first();

                            $counts_detail = count($hitung_detail_sip);

                            if ($counts_detail > 0) {
                                $increment_detail = round($hitung_detail_sip->id_project);
                            }else{
                                $increment_detail = round($hitung_detail_sip);
                            }
                            $nomor_detail = $increment_detail+1;

                            if($nomor_detail <= 9){
                                $nomor_detail = '00' . $nomor_detail;
                            }elseif($nomor_detail > 9 && $nomor_detail < 99){
                                $nomor_detail = '0' . $nomor_detail;
                            }

                            $detail_project = $nomor_detail. '/' . $id_pro_payung->id_project . '/' . 'KP';
                            
                            $tambah_detail = new Detail_IdProject();
                            $tambah_detail->id_project = $detail_project;
                            $tambah_detail->id_pro = $cek_id_project->id_pro;
                            $tambah_detail->date = $edate;
                            $tambah_detail->no_po_customer = $no_po->no_po;
                            $tambah_detail->amount_idr = str_replace(',', '', $request['amount']);
                            $tambah_detail->save();

                            $count_detail_pro = Detail_IdProject::where('id_pro',$cek_id_project->id_pro)->count('id_project');

                            $update = Sales::where('lead_id', $contact)->first();
                            $update->pid = $count_detail_pro + 1;
                            $update->update();

                            $update = PID::where('lead_id',$request['customer_name'])->first();
                            $update->status = 'done';
                            $update->save();
                        
                        }else{

                            // $tambah = new SalesProject();
                            // $tambah->customer_name = TB_Contact::where('id_customer',$cek_result->id_customer)->first()->customer_legal_name;
                            // $tambah->date = $edate;
                            // $tambah->id_project = $project;
                            // $tambah->nik = $request['sales'];
                            // $tambah->no_po_customer = $no_po->no_po;
                            // $tambah->lead_id = $request['customer_name'];
                            // $tambah->sales_name = DB::table('users')->where('nik',QuoteMSP::where('quote_number',$request['quote'])->first()->nik)->first()->name;
                            // $tambah->name_project = $project_name->opp_name;
                            // $tambah->amount_idr = str_replace(',', '', $request['amount']);
                            // $tambah->amount_usd = $request['kurs'];
                            // $tambah->note = $request['note'];
                            // $tambah->status = 'SP';
                            // $tambah->save();

                            // $update = PID::where('lead_id',$request['customer_name'])->first();
                            // $update->status = 'done';
                            // $update->save();
                        
                        }

                    }

                }

                
            }else if ($cek_result->result == 'SPECIAL') {

                if ($cek_result->pid == NULL) {
                    $tambah = new SalesProject();
                    $tambah->customer_name = TB_Contact::where('id_customer',$cek_result->id_customer)->first()->customer_legal_name;
                    $edate = strtotime($request['date']); 
                    $edate = date("Y-m-d",$edate);
                    $tambah->date = $edate;
                    $tambah->id_project = $project;
                    $tambah->nik = $request['sales'];
                    if ($request['inputPO'] == '') {
                       $tambah->no_po_customer = $request['quote'];
                    }else{
                        $tambah->no_po_customer = $request['p_order'];
                    }
                    $tambah->lead_id = $request['customer_name'];
                    // $tambah->sales_name = DB::table('users')->where('nik',QuoteMSP::where('quote_number',$request['quote'])->first()->nik)->first()->name;
                    $tambah->name_project = $project_name->opp_name;
                    $tambah->amount_idr = str_replace(',', '', $request['amount']);
                    $tambah->amount_usd = $request['kurs'];
                    $tambah->note = $request['note'];
                    if (is_null($request['payungs'])) {
                        $tambah->status = 'NEW';
                    }else{
                        $tambah->status = $request['payungs'];
                    }
                    $tambah->save();

                    $update = PID::where('lead_id',$request['customer_name'])->first();
                    $update->status = 'done';
                    $update->save();


                }else {
                    $cek_id_project = Salesproject::select('status','id_pro')->where('id_pro',$request['payung_id'])->where('status','SP')->first();
            

                    $hitung_detail_sip = DB::table('tb_detail_id_project')
                        ->join('tb_id_project', 'tb_id_project.id_pro', '=', 'tb_detail_id_project.id_pro')
                        ->select('tb_detail_id_project.id_project')
                        ->orderBy('tb_detail_id_project.id_project','desc')
                        ->where('tb_detail_id_project.id_pro',$cek_id_project->id_pro)
                        ->first();

                    $counts_detail = count($hitung_detail_sip);

                    if ($counts_detail > 0) {
                        $increment_detail = round($hitung_detail_sip->id_project);
                    }else{
                        $increment_detail = round($hitung_detail_sip);
                    }
                    $nomor_detail = $increment_detail+1;

                    if($nomor_detail <= 9){
                        $nomor_detail = '00' . $nomor_detail;
                    }elseif($nomor_detail > 9 && $nomor_detail < 99){
                        $nomor_detail = '0' . $nomor_detail;
                    }

                    $detail_project = $nomor_detail. '/' . $id_pro_payung->id_project . '/' . 'KP';
                    
                    $tambah_detail = new Detail_IdProject();
                    $tambah_detail->id_project = $detail_project;
                    $tambah_detail->id_pro = $cek_id_project->id_pro;
                    $tambah_detail->date = $edate;
                    $tambah_detail->no_po_customer = $no_po->no_po;
                    $tambah_detail->amount_idr = str_replace(',', '', $request['amount']);
                    $tambah_detail->save();

                    $count_detail_pro = Detail_IdProject::where('id_pro',1)->count('id_project');

                    $update = Sales::where('lead_id', $contact)->first();
                    $update->pid = $count_detail_pro + 1;
                    $update->update();

                    $update = PID::where('lead_id',$request['customer_name'])->first();
                    $update->status = 'done';
                    $update->save();
                
                }
            
            } else if ($cek_result->result == 'hmm'){

                $names = DB::table('tb_contact')->where('id_customer',$request['id_cus'])->first()->code;

                $cus_name = DB::table('tb_contact')->where('id_customer',$request['id_cus'])->first()->customer_legal_name;

                $projects = $nomor.'/'. $names .'/'. 'MSP/' .$bln .'/'. date('Y');

                // $projects = $nomor.'/'. 'TRAV' .'/'. 'MSP/' .$bln .'/'. date('Y');

                $tambah = new SalesProject();
                $tambah->customer_name = $cus_name;
                $edate = strtotime($request['date']); 
                $edate = date("Y-m-d",$edate);
                $tambah->date = $edate;
                $tambah->id_project = $projects;
                $tambah->no_po_customer = $request['quote'];
                $tambah->lead_id = $request['customer_name'];
                $tambah->sales_name = DB::table('users')->where('nik',QuoteMSP::where('quote_number',$request['quote'])->first()->nik)->first()->name;
                $tambah->name_project = QuoteMSP::where('quote_number',$request['quote'])->first()->project;
                $tambah->amount_idr = str_replace(',', '', $request['amount']);
                $tambah->note = $request['note'];
                if (is_null($request['payungs'])) {
                    $tambah->status = 'NEW';
                }else{
                    $tambah->status = $request['payungs'];
                }
                $tambah->save();

                $update = PIDRequest::where('no_quotation',$request['quote'])->first();
                $update->status = 'done';
                $update->save();

            }
        
        }

        $pid_info = DB::table('tb_id_project')
            ->where('id_pro',$tambah->id_pro)
            ->select(
                'lead_id',
                'name_project',
                'no_po_customer',
                'sales_name',
                'no_po_customer',
                'tb_id_project.id_project'
            )->first();

        if($pid_info->lead_id == "MSPQUO"){
          $pid_info->no_quote = $pid_info->no_po_customer;
          $pid_info->no_po_customer = "-";
        }else {
          $pid_info->no_quote = "-";
        }

        $users = User::join('sales_lead_register', 'sales_lead_register.nik', '=', 'users.nik')->join('tb_id_project', 'tb_id_project.lead_id', '=', 'sales_lead_register.lead_id')->select('users.email', 'users.name', 'tb_id_project.lead_id')->first();

        Mail::to($users->email)->send(new mailPID($users,$pid_info));
        /*Mail::to('faiqoh@sinergy.co.id')->send(new mailPID($pid_info));
        Mail::to('ladinar@sinergy.co.id')->send(new mailPID($pid_info));
        Mail::to('agastya@sinergy.co.id')->send(new mailPID($pid_info));*/

        return redirect()->to('/salesproject')->with('success', 'Create PID Successfully!');
        
    }

    public function update_result_request_id(Request $request)
    {
        $id = $request['lead_id'];

        $update = PID::where('lead_id', $id)->first();
        $update->status = 'requested';
        $update->update();

        // $users = User::select('name')->where('id_division','FINANCE')->where('id_position','MANAGER')->first();

        $pid_info = DB::table('sales_lead_register')
            ->join('sales_tender_process','sales_tender_process.lead_id','=','sales_lead_register.lead_id')
            ->join('tb_pid','tb_pid.lead_id','=','sales_lead_register.lead_id')
            ->join('users','users.nik','=','sales_lead_register.nik')
            ->where('sales_lead_register.lead_id',$id)
            ->select(
                'sales_lead_register.lead_id',
                'sales_lead_register.opp_name',
                'users.name',
                'tb_pid.amount_pid',
                'tb_pid.id_pid',
                'tb_pid.no_po',
                'sales_tender_process.quote_number2'
            )->first();

        if($pid_info->lead_id == "MSPQUO"){
            $pid_info->url_create = "/salesproject";
        }else {
            $pid_info->url_create = "/salesproject#acceptProjectID?" . $pid_info->id_pid;
        }

        $users = User::select('name')->where('id_division','FINANCE')->where('id_position','MANAGER')->first();
        
        Mail::to('faiqoh@sinergy.co.id')->send(new MailResult($users,$pid_info));
        // Mail::to('agastya@sinergy.co.id')->send(new MailResult($users,$pid_info));

        Mail::to($users->email)->send(new MailResult($users,$pid_info));


        return redirect()->to('/project')->with('success', 'Create PID Successfully!');
    }

    public function update_status_sales_project(Request $request)
    {
        $id_project = $request['id_pro'];

        $update = SalesProject::where('id_pro', $id_project)->first();
        $update->progres = $request['status'];

        $update->update();//

        return redirect('salesproject')->with('warning', 'Update Status Successfully!');;
    }

    public function detail_sales_project($id_pro)
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        $detail_salessp = DB::table('tb_id_project')
                            ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                            ->join('tb_detail_id_project','tb_detail_id_project.id_pro','=','tb_id_project.id_pro')
                            ->join('users','users.nik','=','sales_lead_register.nik')
                            ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
                            ->select('tb_detail_id_project.date','tb_detail_id_project.id_project','tb_detail_id_project.no_po_customer','sales_lead_register.lead_id','sales_lead_register.opp_name','tb_detail_id_project.amount_idr','users.name')
                            ->where('tb_id_project.id_pro',$id_pro)
                            ->get();

        $induk = DB::table('tb_id_project')
                ->select('id_project','id_pro','lead_id')
                ->where('id_pro',$id_pro)
                ->first();

        $pops = Detail_IdProject::select('id_project')->where('id_pro',$id_pro)->orderBy('id_detail_pro','desc')->first();

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


        if ($div == 'TECHNICAL PRESALES' && $pos == 'MANAGER' ) {
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
            ->select('sales_lead_register.opp_name','sales_solution_design.nik')
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

        return view('sales/detail_sales_project',compact('notif','notifOpen','notifsd','notiftp','notifClaim','detail_salessp','induk','pops'));
    }

    public function getDatalead(Request $request)
    {
        return array(DB::table('sales_lead_register')
                ->select('lead_id,opp_name')
                ->where('result','WIN')
                ->get());
    }

    public function getleadpid(Request $request)
    {
        return array(DB::table('sales_lead_register')
                ->join('users','users.nik','=','sales_lead_register.nik')
                ->join('tb_pid','tb_pid.lead_id','=','sales_lead_register.lead_id')
                ->where('sales_lead_register.lead_id',$request->lead_sp)
                ->select('amount_pid','no_po','date_po','opp_name','sales_lead_register.lead_id')
                ->get(),$request->lead_sp);
    }


    public function update_sp(Request $request)
    {
        $id_project = $request['id_project_edit'];

        $update = SalesProject::where('id_project', $id_project)->first();
        $update->no_po_customer = $request['po_customer_edit'];
        $update->name_project = $request['name_project_edit'];
        if (Auth::User()->id_position == 'MANAGER') {
            $amunt = str_replace(',', '', $request['amount_edit']);
            $update->amount_idr = $amunt.(int)"00";
            $update->amount_usd = $request['kurs_edit'];
        }else{

        }
        $update->note = $request['note_edit'];
        $update->invoice = $request['invoice'];
        $update->update();//

        return redirect('salesproject');
    }

    public function destroy_sp(Request $request)
    {
        $lead_id = $request['id_pro'];
        $id_pro = $request['lead_id'];

        $cek_pid = Salesproject::select('lead_id')->where('lead_id',$lead_id)->count('lead_id');

        $update = Sales::where('lead_id', $lead_id)->first();
        $update->pid = $cek_pid - 1;
        $update->update();

        $hapus = Salesproject::find($id_pro);
        $hapus->delete();

        return redirect()->back()->with('error', 'Deleted PID Successfully!');
    }

    public function getDropdown(Request $request)
    {
        if($request->id_assign=='DIR'){
            return array(DB::table('tb_quote')
                ->select('id_quote', 'quote_number')
                ->where('position','DIR')
                ->where('status','F')
                ->get(),$request->id_assign);
        } else if ($request->id_assign == 'AM') {
            return array(DB::table('tb_quote')
                ->select('id_quote', 'quote_number')
                ->where('position', 'AM')
                ->where('status','F')
                ->get(),$request->id_assign);
        }
    }

    public function export(Request $request)
    {
        $nama = 'ID PROJECT '.date('Y-m-d');
        Excel::create($nama, function ($excel) use ($request) {
        $excel->sheet('Data ID Project', function ($sheet) use ($request) {
        
        $sheet->mergeCells('A1:H1');

       // $sheet->setAllBorders('thin');
        $sheet->row(1, function ($row) {
            $row->setFontFamily('Calibri');
            $row->setFontSize(12);
            $row->setAlignment('center');
            $row->setFontWeight('bold');
        });

        $sheet->row(1, array('Data ID Project SIP'));

        $sheet->row(2, function ($row) {
            $row->setFontFamily('Calibri');
            $row->setFontSize(12);
            $row->setFontWeight('bold');
        });

        $datas = Salesproject::join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
            ->join('users','users.nik','=','sales_lead_register.nik')
            ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
            ->select('tb_id_project.customer_name','tb_id_project.id_project','tb_id_project.date','tb_id_project.no_po_customer','users.name','tb_id_project.amount_idr','tb_id_project.amount_usd',DB::raw('(`tb_id_project`.`amount_idr`*10)/11 as `amount_idr_before_tax` '),'sales_lead_register.lead_id','sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro','tb_id_project.invoice','status','sales_name','progres','name_project','tb_id_project.created_at','customer_legal_name')
            ->whereYear('tb_id_project.created_at',date('Y'))
            ->where('id_company','1')
            ->orderBy('tb_id_project.id_project','asc')
            ->get();

        // $datass = Salesproject::join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
        //     ->join('tb_detail_id_project','tb_detail_id_project.id_pro','=','tb_id_project.id_pro')
        //     ->join('users','users.nik','=','sales_lead_register.nik')
        //     ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
        //     ->select('tb_id_project.date', 'tb_detail_id_project.id_project as id_project', 'tb_id_project.no_po_customer',  'sales_lead_register.opp_name', 'tb_id_project.amount_idr', 'users.name', 'tb_contact.customer_legal_name','name_project')
        //     ->whereYear('tb_id_project.created_at',date('Y'))
        //     ->where('id_company','1')
        //     ->orderBy('tb_id_project.id_project','asc')
        //     ->get();

       // $sheet->appendRow(array_keys($datas[0]));
            $sheet->row($sheet->getHighestRow(), function ($row) {
                $row->setFontWeight('bold');
            });

             $datasheet = array();
             $datasheet[0]  =   array("No", "Date", "ID Project", "No. PO customer", "Customer Name", "Project Name",  "Amount IDR", "Sales");
             $i=1;

            foreach ($datas as $data) {
                if ($data->lead_id == 'SIPPO2020') {
                    $datasheet[$i] = array(
                            $i,
                            $data['date'],
                            $data['id_project'],
                            $data['no_po_customer'],
                            $data['customer_name'],
                            $data['name_project'],
                            $data['amount_idr'],
                            $data['sales_name']
                            
                        );
              
                    $i++;
                } else {
                    $datasheet[$i] = array(
                            $i,
                            $data['date'],
                            $data['id_project'],
                            $data['no_po_customer'],
                            $data['customer_legal_name'],
                            $data['opp_name'],
                            $data['amount_idr'],
                            $data['name']
                            
                        );
              
                    $i++;
                }
                
            }

            // foreach ($datass as $data) {
            //   $datasheet[$i] = array(
            //                 $i,
            //                 $data['date'],
            //                 $data['id_project'],
            //                 $data['no_po_customer'],
            //                 $data['customer_legal_name'],
            //                 $data['name_project'],
            //                 $data['amount_idr'],
            //                 $data['name']
                            
            //             );
              
            //   $i++;
            // }

            $sheet->fromArray($datasheet);
        });

        })->export('xls');
    }

    public function export_msp(Request $request)
    {
         $nama = 'ID PROJECT '.date('Y-m-d');
        Excel::create($nama, function ($excel) use ($request) {
        $excel->sheet('Data ID Project', function ($sheet) use ($request) {
        
        $sheet->mergeCells('A1:H1');

       // $sheet->setAllBorders('thin');
        $sheet->row(1, function ($row) {
            $row->setFontFamily('Calibri');
            $row->setFontSize(12);
            $row->setAlignment('center');
            $row->setFontWeight('bold');
        });

        $sheet->row(1, array('Data ID Project MSP'));

        $sheet->row(2, function ($row) {
            $row->setFontFamily('Calibri');
            $row->setFontSize(12);
            $row->setFontWeight('bold');
        });

        $datas = SalesProject::join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                ->join('users','users.nik','=','sales_lead_register.nik')
                ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
                ->select('tb_id_project.customer_name','tb_id_project.id_project','tb_id_project.date','tb_id_project.no_po_customer','sales_lead_register.opp_name','users.name','tb_id_project.amount_idr','tb_id_project.amount_usd','sales_lead_register.lead_id','sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro','tb_id_project.invoice','status','name_project','tb_id_project.created_at','sales_name','customer_legal_name')
                ->where('id_company','2')
                ->whereYear('tb_id_project.created_at',date('Y'))
                ->where('status','!=','WO')
                ->get();


       // $sheet->appendRow(array_keys($datas[0]));
            $sheet->row($sheet->getHighestRow(), function ($row) {
                $row->setFontWeight('bold');
            });

             $datasheet = array();
             $datasheet[0]  =   array("No", "Date", "ID Project", "No. PO customer", "Customer Name", "Project Name", "Amount IDR", "Sales");
             $i=1;


            foreach ($datas as $data) {
              if ($data->lead_id == 'MSPQUO' || $data->lead_id == 'MSPPO') {
                  $datasheet[$i] = array(
                    $i,
                    $data['date'],
                    $data['id_project'],
                    $data['no_po_customer'],
                    $data['customer_name'],
                    $data['name_project'],
                    $data['amount_idr'],
                    $data['sales_name']
                    
                    );
                  
                  $i++;
                }else{
                $datasheet[$i] = array(
                    $i,
                    $data['date'],
                    $data['id_project'],
                    $data['no_po_customer'],
                    $data['customer_legal_name'],
                    $data['opp_name'],
                    $data['amount_idr'],
                    $data['name']
                    
                );
              
                $i++;
                }
            }
              

            $sheet->fromArray($datasheet);
        });

        })->export('xls');
    }
}