<?php

namespace App\Http\Controllers;

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
use Charts;

use App\SalesChangeLog;

use Mail;
use App\Notifications\NewLead;
use App\Notifications\PresalesAssign;
use App\Notifications\PresalesReAssign;
use App\Notifications\RaiseToTender;
use App\Notifications\Result;
use Notification;

use App\EngineerSpent;
use App\ESMProgress;
use App\PR;
use App\PONumber;
use App\Letter;
use App\HRNumber;
use App\Cuti;
use App\Task;
use App\Barang;
use App\HRCrud;
use App\Partnership;
use App\ShoTransaction;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\input;
use App\http\Requests;
use Validator;
use Response;

class SalesDemoController extends Controller
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

        $year = date('Y');

        $cek_note = Sales::count('keterangan');

        $cek_initial = Sales::where('year',$year)->where('result','OPEN')->count('result');


        if($ter != null){
        	if($pos == 'ENGINEER MANAGER') {
                $lead = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho', 'sales_lead_register.status_handover','sales_lead_register.nik','sales_lead_register.status_engineer', 'sales_lead_register.closing_date', 'sales_lead_register.keterangan')
                    ->where('sales_lead_register.result','WIN')
                    ->where('sales_lead_register.status_sho','PMO')
                    ->where('users.id_company','1')
                    ->get();

                 $leads = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho', 'sales_lead_register.status_handover','sales_lead_register.nik','sales_lead_register.status_engineer', 'sales_lead_register.closing_date', 'sales_lead_register.keterangan')
                    ->where('sales_lead_register.result','WIN')
                    ->where('sales_lead_register.status_sho','PMO')
                    ->where('users.id_company','1')
                    ->where('year','2019')
                    ->get();
	        } elseif($pos == 'ENGINEER STAFF') {
	            $lead = DB::table('sales_lead_register')
	                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
	                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
	                ->join('tb_engineer','sales_lead_register.lead_id','=','tb_engineer.lead_id')
	                ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho', 'sales_lead_register.status_handover','sales_lead_register.nik','sales_lead_register.status_engineer', 'sales_lead_register.closing_date', 'sales_lead_register.keterangan')
	                ->where('sales_lead_register.result','WIN')
	                ->where('tb_engineer.nik',$nik)
                    ->where('users.id_company','1')
	                ->get();

                $leads = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->join('tb_engineer','sales_lead_register.lead_id','=','tb_engineer.lead_id')
                    ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho', 'sales_lead_register.status_handover','sales_lead_register.nik','sales_lead_register.status_engineer', 'sales_lead_register.closing_date', 'sales_lead_register.keterangan')
                    ->where('sales_lead_register.result','WIN')
                    ->where('tb_engineer.nik',$nik)
                    ->where('users.id_company','1')
                    ->where('year','2019')
                    ->get();
	        } elseif($div == 'FINANCE') {
                $lead = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.nik', 'sales_lead_register.closing_date', 'sales_lead_register.keterangan')
                    ->where('sales_lead_register.result','WIN')
                    ->where('id_company','1')
                    ->get();

                $leads = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.nik', 'sales_lead_register.closing_date', 'sales_lead_register.keterangan')
                    ->where('sales_lead_register.result','WIN')
                    ->where('year','2019')
                    ->where('id_company','1')
                    ->get();
                
	        }elseif ($div == 'MSM' && $pos == 'MANAGER') {
                $lead = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                        ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.keterangan', 'sales_lead_register.closing_date', 'sales_lead_register.keterangan')
                        ->where('id_company','1')
                        ->where('result','!=','hmm')
                        ->where('year','2018')
                        ->get();

                    $leads = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id', 'sales_lead_register.nik', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.keterangan','sales_lead_register.year','sales_lead_register.closing_date', 'sales_lead_register.keterangan')
                    ->where('year','2019')
                    ->where('id_company','1')
                    ->where('result','!=','hmm')
                    ->get();
            } elseif($div == '' && $pos == 'OPERATION DIRECTOR' && $ter == 'OPERATION') {
                $lead = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                        ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.keterangan', 'sales_lead_register.closing_date', 'sales_lead_register.keterangan')
                        ->where('year','2018')
                        ->get();

                $leads = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'sales_lead_register.nik', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.keterangan','sales_lead_register.year','sales_lead_register.closing_date', 'sales_lead_register.keterangan')
                ->where('year','2019')
                ->where('result','!=','hmm')
                ->get();
            } else {
	                $lead = DB::table('sales_lead_register')
	                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
	                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
	                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.keterangan', 'sales_lead_register.closing_date', 'sales_lead_register.keterangan')
	                    ->where('id_territory', $ter)
                        ->where('year','2018')
	                    ->get();

                    $leads = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id', 'sales_lead_register.nik', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.keterangan','sales_lead_register.year','sales_lead_register.closing_date', 'sales_lead_register.keterangan')
                    ->where('id_territory', $ter)
                    ->where('year','2019')
                    ->get();

                    $cek_note = DB::table('sales_lead_register')
                         ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                         ->where('id_territory',$ter)
                         ->count('keterangan');

                    $cek_initial = Sales::join('users', 'users.nik', '=', 'sales_lead_register.nik')
                                ->where('id_territory',$ter)->where('year',$year)->where('result','OPEN')->count('result');


	                $total_lead = count($leads);

                    $total_open = DB::table('sales_lead_register')
                                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                                ->where('sales_lead_register.result','')
                                ->where('users.id_territory',$ter)
                                ->where('year','2019')
                                ->count('lead_id');

                    $total_sd = DB::table('sales_lead_register')
                                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                                ->where('sales_lead_register.result','SD')
                                ->where('users.id_territory',$ter)
                                ->where('year','2019')
                                ->count('lead_id');

                    $total_tp = DB::table('sales_lead_register')
                                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                                ->where('sales_lead_register.result','TP')
                                ->where('users.id_territory',$ter)
                                ->where('year','2019')
                                ->count('lead_id');

                    $total_win = DB::table('sales_lead_register')
                                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                                ->where('sales_lead_register.result','WIN')
                                ->where('users.id_territory',$ter)
                                ->where('year','2019')
                                ->count('lead_id');

                    $total_lose = DB::table('sales_lead_register')
                                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                                ->where('sales_lead_register.result','LOSE')
                                ->where('users.id_territory',$ter)
                                ->where('year','2019')
                                ->count('lead_id');

                    $total_leads = count($lead);

                    $total_opens = DB::table('sales_lead_register')
                                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                                ->where('sales_lead_register.result','')
                                ->where('users.id_territory',$ter)
                                ->where('year','2018')
                                ->count('lead_id');

                    $total_sds = DB::table('sales_lead_register')
                                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                                ->where('sales_lead_register.result','SD')
                                ->where('users.id_territory',$ter)
                                ->where('year','2018')
                                ->count('lead_id');

                    $total_tps = DB::table('sales_lead_register')
                                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                                ->where('sales_lead_register.result','TP')
                                ->where('users.id_territory',$ter)
                                ->where('year','2018')
                                ->count('lead_id');

                    $total_wins = DB::table('sales_lead_register')
                                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                                ->where('sales_lead_register.result','WIN')
                                ->where('users.id_territory',$ter)
                                ->where('year','2018')
                                ->count('lead_id');

                    $total_loses = DB::table('sales_lead_register')
                                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                                ->where('sales_lead_register.result','LOSE')
                                ->where('users.id_territory',$ter)
                                ->where('year','2018')
                                ->count('lead_id');
	            }
                
        } elseif($div == 'TECHNICAL PRESALES' && $pos == 'STAFF' && $com == '1') {
                $lead = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                    ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name',
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_solution_design.nik', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.keterangan', 'sales_lead_register.closing_date', 'sales_lead_register.keterangan')
                    ->where('sales_solution_design.nik', $nik)
                    ->where('users.id_company','1')
                    ->get();

                $leads = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.nik','sales_lead_register.status_engineer','sales_lead_register.keterangan','sales_lead_register.year', 
                    'sales_lead_register.closing_date', 'sales_lead_register.keterangan')
                ->where('sales_solution_design.nik', $nik)
                ->where('users.id_company','1')
                ->where('year','2019')
                ->get();
                
                    $total_lead = count($leads);

                    $total_open = DB::table('sales_lead_register')
                                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                                ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                                ->where('sales_lead_register.result','')
                                ->where('sales_solution_design.nik', $nik)
                                ->where('sales_lead_register.year','2019')
                                ->count('sales_lead_register.lead_id');

                    $total_sd = DB::table('sales_lead_register')
                                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                                ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                                ->where('sales_lead_register.result','SD')
                                ->where('sales_solution_design.nik', $nik)
                                ->where('sales_lead_register.year','2019')
                                ->count('sales_lead_register.lead_id');

                    $total_tp = DB::table('sales_lead_register')
                                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                                ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                                ->where('sales_lead_register.result','TP')
                                ->where('sales_solution_design.nik', $nik)
                                ->where('sales_lead_register.year','2019')
                                ->count('sales_lead_register.lead_id');

                    $total_win = DB::table('sales_lead_register')
                                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                                ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                                ->where('sales_lead_register.result','WIN')
                                ->where('sales_solution_design.nik', $nik)
                                ->where('sales_lead_register.year','2019')
                                ->count('sales_lead_register.lead_id');

                    $total_lose = DB::table('sales_lead_register')
                                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                                ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                                ->where('sales_lead_register.result','LOSE')
                                ->where('sales_solution_design.nik', $nik)
                                ->where('sales_lead_register.year','2019')
                                ->count('sales_lead_register.lead_id');
                 
                    $total_leads = count($lead);

                    $total_opens = DB::table('sales_lead_register')
                                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                                ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                                ->where('sales_lead_register.result','')
                                ->where('sales_solution_design.nik', $nik)
                                ->where('sales_lead_register.year','2018')
                                ->count('sales_lead_register.lead_id');

                    $total_sds = DB::table('sales_lead_register')
                                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                                ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                                ->where('sales_lead_register.result','SD')
                                ->where('sales_solution_design.nik', $nik)
                                ->where('sales_lead_register.year','2018')
                                ->count('sales_lead_register.lead_id');

                    $total_tps = DB::table('sales_lead_register')
                                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                                ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                                ->where('sales_lead_register.result','TP')
                                ->where('sales_solution_design.nik', $nik)
                                ->where('sales_lead_register.year','2018')
                                ->count('sales_lead_register.lead_id');

                    $total_wins = DB::table('sales_lead_register')
                                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                                ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                                ->where('sales_lead_register.result','WIN')
                                ->where('sales_solution_design.nik', $nik)
                                ->where('sales_lead_register.year','2018')
                                ->count('sales_lead_register.lead_id');

                    $total_loses = DB::table('sales_lead_register')
                                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                                ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                                ->where('sales_lead_register.result','LOSE')
                                ->where('sales_solution_design.nik', $nik)
                                ->where('sales_lead_register.year','2018')
                                ->count('sales_lead_register.lead_id');

        } elseif($div == 'TECHNICAL PRESALES' && $pos == 'STAFF' && $com == '2') {
            $lead = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_solution_design.nik', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.keterangan', 'sales_lead_register.closing_date', 'sales_lead_register.keterangan')
                ->where('sales_solution_design.nik', $nik)
                ->where('users.id_company','2')
                ->get();

            $leads = DB::table('sales_lead_register')
            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
            ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name',
            'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.nik','sales_lead_register.status_engineer','sales_lead_register.keterangan','sales_lead_register.year', 
                'sales_lead_register.closing_date', 'sales_lead_register.keterangan')
            ->where('sales_solution_design.nik', $nik)
            ->where('users.id_company','2')
            ->where('year','2019')
            ->get();
            
                $total_lead = count($leads);

                $total_open = DB::table('sales_lead_register')
                            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                            ->where('sales_lead_register.result','')
                            ->where('sales_solution_design.nik', $nik)
                            ->where('sales_lead_register.year','2019')
                            ->where('users.id_company','2')
                            ->count('sales_lead_register.lead_id');

                $total_sd = DB::table('sales_lead_register')
                            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                            ->where('sales_lead_register.result','SD')
                            ->where('sales_solution_design.nik', $nik)
                            ->where('sales_lead_register.year','2019')
                            ->where('users.id_company','2')
                            ->count('sales_lead_register.lead_id');

                $total_tp = DB::table('sales_lead_register')
                            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                            ->where('sales_lead_register.result','TP')
                            ->where('sales_solution_design.nik', $nik)
                            ->where('sales_lead_register.year','2019')
                            ->where('users.id_company','2')
                            ->count('sales_lead_register.lead_id');

                $total_win = DB::table('sales_lead_register')
                            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                            ->where('sales_lead_register.result','WIN')
                            ->where('sales_solution_design.nik', $nik)
                            ->where('sales_lead_register.year','2019')
                            ->where('users.id_company','2')
                            ->count('sales_lead_register.lead_id');

                $total_lose = DB::table('sales_lead_register')
                            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                            ->where('sales_lead_register.result','LOSE')
                            ->where('sales_solution_design.nik', $nik)
                            ->where('sales_lead_register.year','2019')
                            ->where('users.id_company','2')
                            ->count('sales_lead_register.lead_id');
             
                $total_leads = count($lead);

                $total_opens = DB::table('sales_lead_register')
                            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                            ->where('sales_lead_register.result','')
                            ->where('sales_solution_design.nik', $nik)
                            ->where('sales_lead_register.year','2018')
                            ->where('users.id_company','2')
                            ->count('sales_lead_register.lead_id');

                $total_sds = DB::table('sales_lead_register')
                            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                            ->where('sales_lead_register.result','SD')
                            ->where('sales_solution_design.nik', $nik)
                            ->where('sales_lead_register.year','2018')
                            ->where('users.id_company','2')
                            ->count('sales_lead_register.lead_id');

                $total_tps = DB::table('sales_lead_register')
                            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                            ->where('sales_lead_register.result','TP')
                            ->where('sales_solution_design.nik', $nik)
                            ->where('sales_lead_register.year','2018')
                            ->where('users.id_company','2')
                            ->count('sales_lead_register.lead_id');

                $total_wins = DB::table('sales_lead_register')
                            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                            ->where('sales_lead_register.result','WIN')
                            ->where('sales_solution_design.nik', $nik)
                            ->where('sales_lead_register.year','2018')
                            ->where('users.id_company','2')
                            ->count('sales_lead_register.lead_id');

                $total_loses = DB::table('sales_lead_register')
                            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                            ->where('sales_lead_register.result','LOSE')
                            ->where('sales_solution_design.nik', $nik)
                            ->where('sales_lead_register.year','2018')
                            ->where('users.id_company','2')
                            ->count('sales_lead_register.lead_id');

        } elseif($div == 'PMO' && $pos == 'MANAGER') {
                $lead = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->join('tb_id_project', 'tb_id_project.lead_id', '=', 'sales_lead_register.lead_id')
                    ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name',
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','users.nik', 'tb_id_project.id_project', 'sales_lead_register.closing_date', 'sales_lead_register.keterangan')
                    ->where('sales_lead_register.result','WIN')
                    ->where('users.id_company','1')
                    ->get();

                $leads = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->join('tb_id_project', 'tb_id_project.lead_id', '=', 'sales_lead_register.lead_id')
                    ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name',
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','users.nik', 'tb_id_project.id_project', 'sales_lead_register.closing_date', 'sales_lead_register.keterangan')
                    ->where('sales_lead_register.result','WIN')
                    ->where('users.id_company','1')
                    ->where('year','2019')
                    ->get();

                $lead_id = $request['pmo_reassign'];

       			$contributes = DB::table('tb_pmo')
       				->join('sales_lead_register','sales_lead_register.lead_id','=','tb_pmo.lead_id')
                    ->select('tb_pmo.id_pmo','tb_pmo.pmo_nik','tb_pmo.lead_id')
                    ->get();

                $pmo_update = $request->input('pmo_nik_update');

                $users = DB::table('users')
                    ->select('nik','name','id_division')
                    ->where('id_division','PMO')
                    ->get();
                $users = $users->toArray();

        }elseif($div == 'PMO' && $pos == 'STAFF') {
                $lead = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->join('tb_id_project', 'tb_id_project.lead_id', '=', 'sales_lead_register.lead_id')
                    ->join('tb_pmo','sales_lead_register.lead_id','=','tb_pmo.lead_id')
                    ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name',
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','tb_pmo.pmo_nik', 'tb_id_project.id_project', 'sales_lead_register.closing_date', 'sales_lead_register.keterangan')
                    ->where('sales_lead_register.result','WIN')
                    ->where('tb_pmo.pmo_nik',$nik)
                    ->where('users.id_company','1')
                    ->get();

                $leads = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->join('tb_id_project', 'tb_id_project.lead_id', '=', 'sales_lead_register.lead_id')
                    ->join('tb_pmo','sales_lead_register.lead_id','=','tb_pmo.lead_id')
                    ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name',
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','tb_pmo.pmo_nik', 'tb_id_project.id_project', 'sales_lead_register.closing_date', 'sales_lead_register.keterangan')
                    ->where('sales_lead_register.result','WIN')
                    ->where('tb_pmo.pmo_nik',$nik)
                    ->where('users.id_company','1')
                    ->where('year','2019')
                    ->get();
        }elseif($div == 'PMO' && $pos == 'ADMIN') {
                $lead = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->join('tb_id_project', 'tb_id_project.lead_id', '=', 'sales_lead_register.lead_id')
                    ->join('tb_pmo','sales_lead_register.lead_id','=','tb_pmo.lead_id')
                    ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name',
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','tb_pmo.pmo_nik', 'tb_id_project.id_project', 'sales_lead_register.closing_date', 'sales_lead_register.keterangan')
                    ->where('sales_lead_register.result','WIN')
                    ->where('tb_pmo.pmo_nik',$nik)
                    ->where('users.id_company','1')
                    ->where('year','2018')
                    ->get();

                $leads = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->join('tb_id_project', 'tb_id_project.lead_id', '=', 'sales_lead_register.lead_id')
                    ->join('tb_pmo','sales_lead_register.lead_id','=','tb_pmo.lead_id')
                    ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name',
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','tb_pmo.pmo_nik', 'tb_id_project.id_project', 'sales_lead_register.closing_date', 'sales_lead_register.keterangan')
                    ->where('sales_lead_register.result','WIN')
                    ->where('tb_pmo.pmo_nik',$nik)
                    ->where('users.id_company','1')
                    ->where('year','2019')
                    ->get();
        }elseif ($div == 'SALES' && $pos == 'STAFF' && $ter == null) {
            $lead = DB::table('sales_lead_register')
            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
            ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
            ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name',
            'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.nik','sales_lead_register.status_engineer','sales_lead_register.keterangan', 'sales_lead_register.closing_date', 'sales_lead_register.keterangan')
            ->where('users.id_company','2')
            ->where('year','2018')
            ->where('sales_lead_register.nik',$nik)
            ->get();

            $leads = DB::table('sales_lead_register')
            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
            ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
            ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name',
            'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.nik','sales_lead_register.status_engineer','sales_lead_register.keterangan', 'sales_lead_register.closing_date', 'sales_lead_register.keterangan')
            ->where('users.id_company','2')
            ->where('sales_lead_register.nik',$nik)
            ->where('year','2019')
            ->get();  

            $total_lead = count($leads);

            $total_open = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->where('sales_lead_register.result','')
                        ->where('users.id_company','2')
                        ->where('year','2019')
                        ->where('sales_lead_register.nik',$nik)
                        ->count('lead_id');

            $total_sd = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->where('sales_lead_register.result','SD')
                        ->where('users.id_company','2')
                        ->where('year','2019')
                        ->where('sales_lead_register.nik',$nik)
                        ->count('lead_id');

            $total_tp = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->where('sales_lead_register.result','TP')
                        ->where('users.id_company','2')
                        ->where('year','2019')
                        ->where('sales_lead_register.nik',$nik)
                        ->count('lead_id');

            $total_win = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->where('sales_lead_register.result','WIN')
                        ->where('users.id_company','2')
                        ->where('year','2019')
                        ->where('sales_lead_register.nik',$nik)
                        ->count('lead_id');

            $total_lose = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->where('sales_lead_register.result','LOSE')
                        ->where('users.id_company','2')
                        ->where('year','2019')
                        ->where('sales_lead_register.nik',$nik)
                        ->count('lead_id');

            $total_leads = count($lead);

            $total_opens = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->where('sales_lead_register.result','')
                        ->where('users.id_company','2')
                        ->where('year','2018')
                        ->count('lead_id');

            $total_sds = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->where('sales_lead_register.result','SD')
                        ->where('users.id_company','2')
                        ->count('lead_id');

            $total_tps = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->where('sales_lead_register.result','TP')
                        ->where('users.id_company','2')
                        ->where('year','2018')
                        ->count('lead_id');

            $total_wins = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->where('sales_lead_register.result','WIN')
                        ->where('users.id_company','2')
                        ->where('year','2018')
                        ->count('lead_id');

            $total_loses = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->where('sales_lead_register.result','LOSE')
                        ->where('users.id_company','2')
                        ->where('year','2018')
                        ->count('lead_id');

        }elseif ($pos == 'DIRECTOR' && $com == '2' || $div == 'TECHNICAL' && $pos == 'MANAGER' && $com == '2' || 
        	$div == 'SALES' && $pos == 'MANAGER' && $ter == null) {

            $lead = DB::table('sales_lead_register')
            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
            ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
            ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name',
            'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.nik','sales_lead_register.status_engineer','sales_lead_register.keterangan', 'sales_lead_register.closing_date', 'sales_lead_register.keterangan')
            ->where('users.id_company','2')
            ->where('year','2018')
            ->get();

            $leads = DB::table('sales_lead_register')
            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
            ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
            ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name',
            'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.nik','sales_lead_register.status_engineer','sales_lead_register.keterangan', 'sales_lead_register.closing_date', 'sales_lead_register.keterangan')
            ->where('users.id_company','2')
            ->where('year','2019')
            ->get();  

            $total_lead = count($leads);

            $total_open = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->where('sales_lead_register.result','')
                        ->where('users.id_company','2')
                        ->where('year','2019')
                        ->count('lead_id');

            $total_sd = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->where('sales_lead_register.result','SD')
                        ->where('users.id_company','2')
                        ->where('year','2019')
                        ->count('lead_id');

            $total_tp = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->where('sales_lead_register.result','TP')
                        ->where('users.id_company','2')
                        ->where('year','2019')
                        ->count('lead_id');

            $total_win = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->where('sales_lead_register.result','WIN')
                        ->where('users.id_company','2')
                        ->where('year','2019')
                        ->count('lead_id');

            $total_lose = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->where('sales_lead_register.result','LOSE')
                        ->where('users.id_company','2')
                        ->where('year','2019')
                        ->count('lead_id');

            $total_leads = count($lead);

            $total_opens = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->where('sales_lead_register.result','')
                        ->where('users.id_company','2')
                        ->where('year','2018')
                        ->count('lead_id');

            $total_sds = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->where('sales_lead_register.result','SD')
                        ->where('users.id_company','2')
                        ->count('lead_id');

            $total_tps = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->where('sales_lead_register.result','TP')
                        ->where('users.id_company','2')
                        ->where('year','2018')
                        ->count('lead_id');

            $total_wins = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->where('sales_lead_register.result','WIN')
                        ->where('users.id_company','2')
                        ->where('year','2018')
                        ->count('lead_id');

            $total_loses = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->where('sales_lead_register.result','LOSE')
                        ->where('users.id_company','2')
                        ->where('year','2018')
                        ->count('lead_id');

        }else if ($div == 'TECHNICAL PRESALES' && $pos == 'MANAGER') {
            $lead = DB::table('sales_lead_register')
            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
            ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
            ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name',
            'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.nik','sales_lead_register.status_engineer','sales_lead_register.keterangan', 'sales_lead_register.closing_date', 'sales_lead_register.keterangan')
            ->where('users.id_company','1')
            ->where('year','2018')
            ->get();

            $leadspre = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_solution_design.nik', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.keterangan','sales_lead_register.closing_date')
                ->where('year','2019')
                ->where('users.id_company','1')
                ->get();

            $leads = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.nik','sales_lead_register.status_engineer','sales_lead_register.keterangan','sales_lead_register.year','sales_lead_register.closing_date', 'sales_lead_register.keterangan','users.id_company')
                ->where('year','2019')
                ->where('users.id_company','1')
                ->where('result','OPEN')
                ->get();

            $datas =DB::table('sales_solution_design')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','sales_solution_design.lead_id')
                    ->join('users', 'users.nik', '=', 'sales_solution_design.nik')
                    ->select('users.name','sales_solution_design.nik')
                    ->where('year','2019')
                    ->where('id_company','1')
                    ->get();

            $rk = user::select('nik')->where('email','rizkik@sinergy.co.id')->first();

            $gp = user::select('nik')->where('email','ganjar@sinergy.co.id')->first();

            $st = user::select('nik')->where('email','satria@sinergy.co.id')->first();

                    $total_lead = count($leads);

                    $total_open = DB::table('sales_lead_register')
                                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                                ->where('sales_lead_register.result','')
                                ->where('sales_lead_register.year','=','2019')
                                ->where('users.id_company','1')
                                ->count('lead_id');

                    $total_sd = DB::table('sales_lead_register')
                                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                                ->where('sales_lead_register.result','SD')
                                ->where('users.id_company','1')
                                ->where('sales_lead_register.year','=','2019')
                                ->count('lead_id');

                    $total_tp = DB::table('sales_lead_register')
                                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                                ->where('sales_lead_register.result','TP')
                                ->where('sales_lead_register.year','=','2019')
                                ->where('users.id_company','1')
                                ->count('lead_id');

                    $total_win = DB::table('sales_lead_register')
                                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                                ->where('sales_lead_register.result','WIN')
                                ->where('sales_lead_register.year','=','2019')
                                ->where('users.id_company','1')
                                ->count('lead_id');

                    $total_lose = DB::table('sales_lead_register')
                                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                                ->where('sales_lead_register.result','LOSE')
                                ->where('sales_lead_register.year','=','2019')
                                ->where('users.id_company','1')
                                ->count('lead_id');
     
                    $total_leads = count($lead);

                    $total_opens = DB::table('sales_lead_register')
                                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                                ->where('sales_lead_register.result','')
                                ->where('sales_lead_register.year','2018')
                                ->where('users.id_company','1')
                                ->count('lead_id');

                    $total_sds = DB::table('sales_lead_register')
                                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                                ->where('sales_lead_register.result','SD')
                                ->where('sales_lead_register.year','2018')
                                ->where('users.id_company','1')
                                ->count('lead_id');

                    $total_tps = DB::table('sales_lead_register')
                                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                                ->where('sales_lead_register.result','TP')
                                ->where('sales_lead_register.year','2018')
                                ->where('users.id_company','1')
                                ->count('lead_id');

                    $total_wins = DB::table('sales_lead_register')
                                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                                ->where('sales_lead_register.result','WIN')
                                ->where('sales_lead_register.year','2018')
                                ->where('users.id_company','1')
                                ->count('lead_id');

                    $total_loses = DB::table('sales_lead_register')
                                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                                ->where('sales_lead_register.result','LOSE')
                                ->where('sales_lead_register.year','2018')
                                ->where('users.id_company','1')
                                ->count('lead_id');
        }
        else {
            $lead = DB::table('sales_lead_register')
	            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
	            ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
	            ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name',
	            'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.nik','sales_lead_register.status_engineer','sales_lead_register.keterangan', 'sales_lead_register.closing_date', 'sales_lead_register.keterangan','users.id_company')
                ->where('result','!=','hmm')
	            ->where('year','2018')
	            ->get();

	        $leads = DB::table('sales_lead_register')
	            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
	            ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
	            ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name',
	            'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.nik','sales_lead_register.status_engineer','sales_lead_register.keterangan','sales_lead_register.year','sales_lead_register.closing_date', 'sales_lead_register.keterangan','users.id_company')
                ->where('result','!=','hmm')
	            ->where('year','2019')
	            ->get();

           /*     if (date('Y') == '2018') {
                    $total_lead = count($lead);
                }else{
                    $total_lead = count($leads);
                }*/
                

                    $total_lead = count($leads);

                    $total_open = DB::table('sales_lead_register')
                                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                                ->where('sales_lead_register.result','')
                                ->where('sales_lead_register.year','2019')
                                ->count('lead_id');

                    $total_sd = DB::table('sales_lead_register')
                                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                                ->where('sales_lead_register.result','SD')
                                ->where('sales_lead_register.year','2019')
                                ->count('lead_id');

                    $total_tp = DB::table('sales_lead_register')
                                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                                ->where('sales_lead_register.result','TP')
                                ->where('sales_lead_register.year','2019')
                                ->count('lead_id');

                    $total_win = DB::table('sales_lead_register')
                                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                                ->where('sales_lead_register.result','WIN')
                                ->where('sales_lead_register.year','2019')
                                ->count('lead_id');

                    $total_lose = DB::table('sales_lead_register')
                                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                                ->where('sales_lead_register.result','LOSE')
                                ->where('sales_lead_register.year','2019')
                                ->count('lead_id');
      
                    $total_leads = count($lead);

                    $total_opens = DB::table('sales_lead_register')
                                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                                ->where('sales_lead_register.result','')
                                ->where('sales_lead_register.year','2018')
                                ->count('lead_id');

                    $total_sds = DB::table('sales_lead_register')
                                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                                ->where('sales_lead_register.result','SD')
                                ->where('sales_lead_register.year','2018')
                                ->count('lead_id');

                    $total_tps = DB::table('sales_lead_register')
                                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                                ->where('sales_lead_register.result','TP')
                                ->where('sales_lead_register.year','2018')
                                ->count('lead_id');

                    $total_wins = DB::table('sales_lead_register')
                                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                                ->where('sales_lead_register.result','WIN')
                                ->where('sales_lead_register.year','2018')
                                ->count('lead_id');

                    $total_loses = DB::table('sales_lead_register')
                                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                                ->where('sales_lead_register.result','LOSE')
                                ->where('sales_lead_register.year','2018')
                                ->count('lead_id');
                
        }

        $lead_id = $request['lead_id_edit'];

        $owner_by_lead = DB::table('sales_lead_register')
                        ->select('nik')
                        ->where('lead_id',$lead_id)
                        ->first();

        /*  $presales = DB::table('sales_solution_design')
                    ->join('users','users.nik','=','sales_solution_design.nik')
                    ->select('sales_solution_design.lead_id','sales_solution_design.nik','sales_solution_design.assessment','sales_solution_design.pov','sales_solution_design.pd','sales_solution_design.pb','sales_solution_design.priority','sales_solution_design.project_size','users.name','sales_solution_design.status', 'sales_solution_design.assessment_date', 'sales_solution_design.pd_date', 'sales_solution_design.pov_date')*/
        if ($ter != null) {
            if($pos == 'ENGINEER MANAGER') {
                $total_ter = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho', 'sales_lead_register.status_handover','sales_lead_register.nik','sales_lead_register.status_engineer')
                    ->where('sales_lead_register.result','WIN')
                    ->where('sales_lead_register.status_sho','PMO')
                    ->where('year','2018')
                    ->sum('amount');

                $total_ters = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho', 'sales_lead_register.status_handover','sales_lead_register.nik','sales_lead_register.status_engineer')
                    ->where('sales_lead_register.result','WIN')
                    ->where('sales_lead_register.status_sho','PMO')
                    ->where('year','2019')
                    ->sum('amount');
            } elseif($pos == 'ENGINEER STAFF') {
                $total_ter = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->join('tb_engineer','sales_lead_register.lead_id','=','tb_engineer.lead_id')
                    ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho', 'sales_lead_register.status_handover','sales_lead_register.nik','sales_lead_register.status_engineer')
                    ->where('sales_lead_register.result','WIN')
                    ->where('tb_engineer.nik',$nik)
                    ->where('year','2018')
                    ->sum('amount');

                $total_ters = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->join('tb_engineer','sales_lead_register.lead_id','=','tb_engineer.lead_id')
                    ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho', 'sales_lead_register.status_handover','sales_lead_register.nik','sales_lead_register.status_engineer')
                    ->where('sales_lead_register.result','WIN')
                     ->where('tb_engineer.nik',$nik)
                     ->where('year','2019')
                    ->sum('amount');
            } elseif($div == 'FINANCE') {
                $total_ter = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho', 'sales_lead_register.status_handover','sales_lead_register.nik')
                    ->where('sales_lead_register.result','WIN')
                    ->where('year','2018')
                    ->sum('amount');

                $total_ters = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho', 'sales_lead_register.status_handover','sales_lead_register.nik')
                    ->where('sales_lead_register.result','WIN')
                    ->where('year','2019')
                    ->where('id_company','1')
                    ->sum('amount');
            } else {
                $total_ter = DB::table("sales_lead_register")
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->where('id_territory', $ter)
                        ->where('year','2018')
                        ->sum('amount');

                $total_ters = DB::table("sales_lead_register")
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->where('id_territory', $ter)
                        ->where('year',$year)
                        ->sum('amount');
            }
            
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $total_ter = DB::table("sales_lead_register")
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                        ->where('sales_solution_design.nik', $nik)
                        ->where('year','2018')
                        ->sum('amount');

            $total_ters = DB::table("sales_lead_register")
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                        ->where('sales_solution_design.nik', $nik)
                        ->where('year','2019')
                        ->sum('amount');
        }elseif ($div == 'SALES' && $pos == 'STAFF' && $ter == NULL && $com == 2) {
            $total_ter = DB::table("sales_lead_register")
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->where('id_company','2')
                        ->where('year','2018')
                        ->where('sales_lead_register.nik',$nik)
                        ->sum('amount');

            $total_ters = DB::table("sales_lead_register")
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->where('year','2019')
                        ->where('id_company','2')
                         ->where('sales_lead_register.nik',$nik)
                        ->sum('amount');
        }elseif ($com == 2 || $pos == 'DIRECTOR' && $com == '2' || $div == 'SALES' && $pos == 'MANAGER' && $ter == null) {
            $total_ter = DB::table("sales_lead_register")
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->where('id_company','2')
                        ->where('year','2018')
                        ->sum('amount');

            $total_ters = DB::table("sales_lead_register")
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->where('year','2019')
                        ->where('id_company','2')
                        ->sum('amount');
        }elseif ($div == 'PMO') {
            $total_ter = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_id_project', 'tb_id_project.lead_id', '=', 'sales_lead_register.lead_id')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name',
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','users.nik')
                    ->where('sales_lead_register.result','WIN')
                    ->where('year','2018')
                    ->SUM('amount');

            $total_ters = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_id_project', 'tb_id_project.lead_id', '=', 'sales_lead_register.lead_id')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name',
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','users.nik')
                    ->where('sales_lead_register.result','WIN')
                    ->where('year','2019')
                    ->SUM('amount');
        }elseif ($div == 'TECHNICAL' && $ter == 'DPG') {
            $total_ter = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name',
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','users.nik')
                    ->where('sales_lead_register.result','WIN')
                    ->where('year','2018')
                    ->SUM('amount');

            $total_ters = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name',
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','users.nik')
                    ->where('sales_lead_register.result','WIN')
                    ->where('year','2019')
                    ->SUM('amount');
        }else{
            $total_ter = DB::table("sales_lead_register")
                        ->where('year','2018')
                        ->sum('amount');

            $total_ters = DB::table("sales_lead_register")
                        ->where('year','2019')
                        ->sum('amount');
        }

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

        return view('DemoTemplate/demo_sales', compact('lead','leads', 'total_ter','total_ters','notif','notifOpen','notifsd','notiftp','id_pro','contributes','users','pmo_nik','owner_by_lead','total_lead','total_open','total_sd','total_tp','total_win','total_lose','total_leads','total_opens','total_sds','total_tps','total_wins','total_loses', 'notifClaim','cek_note','cek_initial','datas','rk','gp','st','leadspre'));
    }

    public function dashboard()
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

        // count id project
        if($div == 'FINANCE' && $pos == 'MANAGER'){
            $idp = DB::table('tb_id_project')
                ->get();
            $idps = count($idp);
        }

        //count lead
        if($div == 'SALES' && $pos != 'ADMIN' || $pos == 'STAFF' && $com == '2'){
            $count = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('id_territory', $ter)
                ->where('id_company', '1')
                ->where('year','2019')
                ->get();
            $counts = count($count);

            $count = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('id_territory', $ter)
                ->where('id_company', '2')
                ->where('year','2019')
                ->get();
            $countmsp = count($count);
        } elseif($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $count = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'sales_lead_register.opp_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('sales_solution_design.nik', $nik)
                ->where('id_company', '1')
                ->where('year','2019')
                ->get();
            $counts = count($count);
        } elseif ($pos == 'ADMIN') {
            $count = DB::table('dvg_esm')
                    ->join('users', 'users.nik', '=', 'dvg_esm.personnel')
                    ->select('no','date','users.name', 'type', 'description', 'amount', 'id_project', 'remarks', 'status')
                    ->where('status', 'ADMIN')
                    ->where('nik_admin', $nik)
                    ->where('id_company', '1')
                    ->get();
            $counts = count($count);

        } elseif ($div == 'FINANCE') {
            $count = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'sales_lead_register.opp_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result','win')
                ->where('id_company', '1')
                ->where('year','2019')
                ->get();
            $counts = count($count);
        }elseif($pos == 'ENGINEER MANAGER') {
            $count = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.nik','sales_lead_register.status_engineer')
                ->where('sales_lead_register.result','WIN')
                ->where('sales_lead_register.status_sho','PMO')
                ->where('id_company', '1')
                ->where('year','2019')
                ->get();
            $counts = count($count);
        }elseif($pos == 'ENGINEER STAFF') {
            $count = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('tb_engineer','sales_lead_register.lead_id','=','tb_engineer.lead_id')
                ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.nik','sales_lead_register.status_engineer')
                ->where('sales_lead_register.result','WIN')
                ->where('tb_engineer.nik',$nik)
                ->where('id_company', '1')
                ->where('year','2019')
                ->get();
            $counts = count($count);
        }elseif ($div == 'PMO' && $pos == 'STAFF') {
            $count = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('tb_pmo', 'tb_pmo.lead_id', '=', 'sales_lead_register.lead_id')
                ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'sales_lead_register.opp_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('tb_pmo.pmo_nik', $nik)
                ->where('id_company', '1')
                ->where('year','2019')
                ->get();
            $counts = count($count);
        } elseif ($div == 'PMO' && $pos == 'MANAGER') {
            $count = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
                ->where('sales_lead_register.status_sho','')
                ->where('id_company', '1')
                ->where('year','2019')
                ->get();
            $counts = count($count);
        }elseif ($com == '2' ) {
            $count = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
                ->where('sales_lead_register.status_sho','')
                ->where('id_company', '2')
                ->where('year','2019')
                ->get();
            $counts = count($count);

            $countss = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('id_company','2')
                ->where('year','2019')
                ->get();
            $countmsp = count($countss);
        }else {
            $count = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('year','2019')
                ->get();
            $counts = count($count);
        }
        
        // count status open
        if($div == 'SALES' && $pos != 'ADMIN'){
            $open = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result', '')
                ->where('id_territory', $ter)
                ->get();
            $opens = count($open);
        } elseif($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $open = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'sales_lead_register.opp_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result', '')
                ->where('sales_solution_design.nik', $nik)
                ->where('year','2019')
                ->get();
            $opens = count($open);
        } elseif ($div == 'FINANCE' && $pos == 'MANAGER') {
            $open = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('tb_id_project', 'tb_id_project.lead_id', '=', 'sales_lead_register.lead_id')
                ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'sales_lead_register.opp_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('sales_lead_register.status_sho','')
                ->where('year','2019')
                ->get();
            $opens = count($open);
        } elseif ($div == 'TECHNICAL' && $ter == 'DPG') {
            $open = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'sales_lead_register.opp_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result3','DONE')
                ->where('year','2019')
                ->get();
            $opens = count($open);
        }elseif ($pos == 'ADMIN') {
            $open = DB::table('tb_pr')
                ->select('no')
                ->get();
            $opens = count($open);
        } else {
            $open = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result','')
                ->where('year','2019')
                ->get();
            $opens = count($open);
        }

        // count status sd
        if($div == 'SALES' && $pos != 'ADMIN'){
            $sd = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result', 'SD')
                ->where('id_territory', $ter)
                ->where('year','2019')
                ->get();
            $sds = count($sd);
        } elseif($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $sd = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'sales_lead_register.opp_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result', 'SD')
                ->where('sales_solution_design.nik', $nik)
                ->where('year','2019')
                ->get();
            $sds = count($sd);
        } elseif ($div == 'FINANCE') {
            $sd = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->join('tb_id_project', 'tb_id_project.lead_id', '=', 'sales_lead_register.lead_id')
                    ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'sales_lead_register.opp_name',
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                    ->where('sales_lead_register.lead_id','tb_id_project.lead_id')
                    ->where('year','2019')
                    ->get();
            $sds = count($sd);
        } elseif ($div == 'TECHNICAL' && $ter == 'DPG') {
            $sd = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'sales_lead_register.opp_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result3','DONE')
                ->where('year','2019')
                ->get();
            $sds = count($sd);
        }elseif ($pos == 'ADMIN') {
            $sd = DB::table('tb_po')
                ->select('no')
                ->get();
            $sds = count($sd);
        } else {
            $sd = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result','SD')
                ->where('year','2019')
                ->get();
            $sds = count($sd);
        }

        // count status tp
        if($div == 'SALES' && $pos != 'ADMIN'){
            $tp = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result', 'TP')
                ->where('id_territory', $ter)
                ->where('year','2019')
                ->get();
            $tps = count($tp);
        } elseif($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $tp = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'sales_lead_register.opp_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result', 'TP')
                ->where('sales_solution_design.nik', $nik)
                ->where('year','2019')
                ->get();
            $tps = count($tp);
        }elseif ($div == 'FINANCE') {
            $tp = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->join('tb_id_project', 'tb_id_project.lead_id', '=', 'sales_lead_register.lead_id')
                    ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'sales_lead_register.opp_name',
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                    ->where('sales_lead_register.lead_id','tb_id_project.lead_id')
                    ->where('year','2019')
                    ->get();
            $tps = count($tp);
        } elseif ($div == 'TECHNICAL' && $ter == 'DPG') {
            $tp = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'sales_lead_register.opp_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result3','DONE')
                ->where('year','2019')
                ->get();
            $tps = count($tp);
        } else {
            $tp = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result','TP')
                ->where('year','2019')
                ->get();
            $tps = count($tp);
        }   

        // count status win
        if($div == 'SALES' && $pos != 'ADMIN'){
            $win = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result', 'WIN')
                ->where('id_territory', $ter)
                ->where('year','2019')
                ->get();
            $wins = count($win);

            $winss = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result','WIN')
                ->where('year','2019')
                ->get();
            $win2 = count($winss);
        } elseif($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $win = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'sales_lead_register.opp_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result', 'WIN')
                ->where('sales_solution_design.nik', $nik)
                ->where('year','2019')
                ->get();
            $wins = count($win);

            $winss = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result','WIN')
                ->where('year','2019')
                ->get();
            $win2 = count($winss);
        } elseif ($div == 'FINANCE') {
            $win = DB::table('dvg_esm')
                    ->join('users', 'users.nik', '=', 'dvg_esm.personnel')
                    ->select('no','date','users.name', 'type', 'description', 'amount', 'id_project', 'remarks', 'status')
                    ->where('status', 'FINANCE')
                    ->get();
            $wins = count($win);

            $winss = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result','WIN')
                ->where('year','2019')
                ->get();
            $win2 = count($winss);
        }elseif ($div == 'HR') {
            $win = DB::table('dvg_esm')
                    ->join('users', 'users.nik', '=', 'dvg_esm.personnel')
                    ->select('no','date','users.name', 'type', 'description', 'amount', 'id_project', 'remarks', 'status')
                    ->where('status', 'HRD')
                    ->get();
            $wins = count($win);

            $winss = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result','WIN')
                ->where('year','2019')
                ->get();
            $win2 = count($winss);
        } else {
            $win = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result','WIN')
                ->where('year','2019')
                ->get();
            $wins = count($win);

            $winss = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result','WIN')
                ->where('year','2019')
                ->get();
            $win2 = count($winss);
        }

        // count status lose
        if ($div == 'SALES' && $pos != 'ADMIN' || $pos == 'DIRECTOR' && $com == '2' || $pos == 'STAFF' && $com == '2') {
            $lose = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result', 'LOSE')
                ->where('id_territory', $ter)
                ->where('year','2019')
                ->get();
            $loses = count($lose);

            $lose = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result', 'LOSE')
                ->where('id_territory', $ter)
                ->where('id_company','2')
                ->where('year','2019')
                ->get();
            $losemsp = count($lose);

             $losess = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result','LOSE')
                ->where('year','2019')
                ->where('year','2019')
                ->get();
            $lose2 = count($losess);
        } elseif($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $lose = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'sales_lead_register.opp_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result', 'LOSE')
                ->where('sales_solution_design.nik', $nik)
                ->where('year','2019')
                ->get();
            $loses = count($lose);

            $losess = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result','LOSE')
                ->where('year','2019')
                ->where('year','2019')
                ->get();
            $lose2 = count($losess);
        } elseif ($div == 'FINANCE') {
            $lose = DB::table('dvg_esm')
                    ->join('users', 'users.nik', '=', 'dvg_esm.personnel')
                    ->select('no','date','users.name', 'type', 'description', 'amount', 'id_project', 'remarks', 'status')
                    ->where('status', 'TRANSFER')
                    ->get();
            $loses = count($lose);

            $losess = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result','LOSE')
                ->where('year','2019')
                ->where('year','2019')
                ->get();
            $lose2 = count($losess);
        }elseif ($div == 'HR') {
            $lose = DB::table('dvg_esm')
                    ->join('users', 'users.nik', '=', 'dvg_esm.personnel')
                    ->select('no','date','users.name', 'type', 'description', 'amount', 'id_project', 'remarks', 'status')
                    ->where('status', 'TRANSFER')
                    ->get();
            $loses = count($lose);

            $losess = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result','LOSE')
                ->where('year','2019')
                ->where('year','2019')
                ->get();
            $lose2 = count($losess);
        } elseif ($pos == 'ADMIN') {
            $lose = DB::table('tb_quote')
                        ->select('id_quote','quote_number','position','type_of_letter','date','to','attention','title','project')
                        ->get();
            $loses = count($lose);

            $losess = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result','LOSE')
                ->where('year','2019')
                ->where('year','2019')
                ->get();
            $lose2 = count($losess);
        } else {
            $lose = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result','LOSE')
                ->where('year','2019')
                ->get();
            $loses = count($lose);

            $losess = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result','LOSE')
                ->where('year','2019')
                ->where('year','2019')
                ->get();
            $lose2 = count($losess);

            $lose = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result', 'LOSE')
                ->where('id_territory', $ter)
                ->where('id_company','2')
                ->where('year','2019')
                ->get();
            $losemsp = count($lose);
        }

        if ($div == 'SALES' && $pos != 'ADMIN') {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
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

        return view('DemoTemplate/dashboard', compact('pos','div','results','idps', 'counts','opens', 'sds', 'tps', 'notiftp', 'notifsd', 'notifOpen', 'wins', 'loses', 'notif', 'notifClaim','countmsp','losemsp','win1','win2','lose1','lose2'));
    }

    public function profile()
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        if ($div == 'SALES' && $ter != ''  || $div == 'TECHNICAL' && $ter == 'DVG' || $ter == 'DPG') {
            $user_profile = DB::table('users')
                        ->join('tb_division','tb_division.id_division','=','users.id_division')
                        ->join('tb_position','tb_position.id_position','=','users.id_position')
                        ->join('tb_territory','tb_territory.id_territory','=','users.id_territory')
                        ->select('users.name','tb_division.name_division','tb_position.name_position','users.email','users.date_of_birth','users.nik','users.phone','users.gambar','tb_territory.name_territory','users.date_of_entry')
                        ->where('users.nik',$nik)
                        ->first();
        }else if($div == 'TECHNICAL PRESALES' && $pos == 'MANAGER'){
             $user_profile = DB::table('users')
                        ->join('tb_division','tb_division.id_division','=','users.id_division')
                        ->join('tb_position','tb_position.id_position','=','users.id_position')
                        ->select('users.name','tb_division.id_division','tb_position.name_position','users.email','users.date_of_birth','users.nik','users.phone','users.gambar','users.date_of_entry')
                        ->where('users.nik',$nik)
                        ->first();
        }else if($div == 'TECHNICAL' && $pos == 'MANAGER'){
             $user_profile = DB::table('users')
                        ->join('tb_division','tb_division.id_division','=','users.id_division')
                        ->join('tb_position','tb_position.id_position','=','users.id_position')
                        ->select('users.name','tb_division.name_division','tb_position.name_position','users.email','users.date_of_birth','users.nik','users.phone','users.gambar','users.date_of_entry')
                        ->where('users.nik',$nik)
                        ->first();
        }else if($div == 'FINANCE' && $pos == 'MANAGER'){
             $user_profile = DB::table('users')
                        ->join('tb_division','tb_division.id_division','=','users.id_division')
                        ->join('tb_position','tb_position.id_position','=','users.id_position')
                        ->select('users.name','tb_division.name_division','tb_position.name_position','users.email','users.date_of_birth','users.nik','users.phone','users.gambar','users.date_of_entry')
                        ->where('users.nik',$nik)
                        ->first();
        }else if($div == 'FINANCE' && $pos == 'STAFF'){
             $user_profile = DB::table('users')
                        ->join('tb_division','tb_division.id_division','=','users.id_division')
                        ->join('tb_position','tb_position.id_position','=','users.id_position')
                        ->select('users.name','tb_division.name_division','tb_position.name_position','users.email','users.date_of_birth','users.nik','users.phone','users.gambar','users.date_of_entry')
                        ->where('users.nik',$nik)
                        ->first();
        }else if($div == 'TECHNICAL PRESALES' && $pos == 'STAFF'){
             $user_profile = DB::table('users')
                        ->join('tb_division','tb_division.id_division','=','users.id_division')
                        ->join('tb_position','tb_position.id_position','=','users.id_position')
                        ->select('users.name','tb_division.id_division','tb_position.name_position','users.email','users.date_of_birth','users.nik','users.phone','users.gambar','users.date_of_entry')
                        ->where('users.nik',$nik)
                        ->first();
        }else if($div == 'PMO' && $pos == 'MANAGER'){
             $user_profile = DB::table('users')
                        ->join('tb_division','tb_division.id_division','=','users.id_division')
                        ->join('tb_position','tb_position.id_position','=','users.id_position')
                        ->select('users.name','tb_division.name_division','tb_position.name_position','users.email','users.date_of_birth','users.nik','users.phone','users.gambar','users.date_of_entry')
                        ->where('users.nik',$nik)
                        ->first();
        }else if($div == 'PMO' && $pos == 'STAFF'){
             $user_profile = DB::table('users')
                        ->join('tb_division','tb_division.id_division','=','users.id_division')
                        ->join('tb_position','tb_position.id_position','=','users.id_position')
                        ->select('users.name','tb_division.name_division','tb_position.name_position','users.email','users.date_of_birth','users.nik','users.phone','users.gambar','users.date_of_entry')
                        ->where('users.nik',$nik)
                        ->first();
        }else if($div == 'PMO' && $pos == 'ADMIN'){
             $user_profile = DB::table('users')
                        ->join('tb_division','tb_division.id_division','=','users.id_division')
                        ->join('tb_position','tb_position.id_position','=','users.id_position')
                        ->select('users.name','tb_division.name_division','tb_position.name_position','users.email','users.date_of_birth','users.nik','users.phone','users.gambar','users.date_of_entry')
                        ->where('users.nik',$nik)
                        ->first();
        }else if($div == 'TECHNICAL' && $pos == 'INTERNAL IT'){
             $user_profile = DB::table('users')
                        ->join('tb_division','tb_division.id_division','=','users.id_division')
                        ->join('tb_position','tb_position.id_position','=','users.id_position')
                        ->select('users.name','tb_division.name_division','tb_position.name_position','users.email','users.date_of_birth','users.nik','users.phone','users.gambar','users.date_of_entry')
                        ->where('users.nik',$nik)
                        ->first();
        }else if($div == 'SALES' && $ter == ''){
             $user_profile = DB::table('users')
                        ->join('tb_division','tb_division.id_division','=','users.id_division')
                        ->join('tb_position','tb_position.id_position','=','users.id_position')
                        ->select('users.name','tb_division.name_division','tb_position.name_position','users.email','users.date_of_birth','users.nik','users.phone','users.gambar','users.date_of_entry')
                        ->where('users.nik',$nik)
                        ->first();
        }
        else{
           $user_profile = DB::table('users')
                        ->join('tb_position','tb_position.id_position','=','users.id_position')
                        ->select('users.name','tb_position.name_position','users.email','users.date_of_birth','users.nik','users.phone','users.gambar','users.date_of_entry')
                        ->where('users.nik',$nik)
                        ->first();
        }
        

        if ($ter != null) {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
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
         
        return view('DemoTemplate/profile', compact('notif','notifOpen','notifsd','notiftp','ter','user_profile', 'notifClaim'));
    }

    public function pam()
    {

        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        if ($div == 'HR' && $pos == 'HR MANAGER' || $div == 'FINANCE' && $pos == 'STAFF') {
            $pam = DB::table('dvg_pam')
                ->join('users','users.nik','=','dvg_pam.personel')
                ->join('tb_pr','tb_pr.no','=','dvg_pam.no_pr')
                ->select('dvg_pam.id_pam','dvg_pam.date_handover','tb_pr.no_pr','dvg_pam.ket_pr','dvg_pam.note_pr','dvg_pam.to_agen','dvg_pam.status','users.name','dvg_pam.subject','dvg_pam.amount')
                ->get();
        } elseif ($pos == 'ADMIN') {
            $pam = DB::table('dvg_pam')
                ->join('users','users.nik','=','dvg_pam.personel')
                ->join('tb_pr','tb_pr.no','=','dvg_pam.no_pr')
                ->select('dvg_pam.id_pam','dvg_pam.date_handover','tb_pr.no_pr','dvg_pam.ket_pr','dvg_pam.note_pr','dvg_pam.to_agen','dvg_pam.status','users.name','dvg_pam.subject')
                ->where('nik_admin',$nik)
                ->get();
        } else {

        }

        $no_pr = DB::table('tb_pr')
                ->select('result','no_pr','no')
                ->where('result','T')
                ->get();

        $pams = DB::table('dvg_pam')
            ->select('id_pam')
            ->get();

        $produks = DB::table('dvg_pam')
            ->join('dvg_pr_product','dvg_pr_product.id_pam','=','dvg_pam.id_pam')
            ->select('dvg_pr_product.name_product','dvg_pr_product.qty','dvg_pr_product.id_pam','dvg_pr_product.nominal')
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

        $sum = DB::table('dvg_pam')
            ->select('id_pam')
            ->sum('id_pam');

        $count_product = DB::table('dvg_pr_product')
            ->select('id_product')
            ->sum('id_product');

        $total_amount = DB::table('dvg_pr_product')
                    ->select('nominal')
                    ->sum('nominal');

        return view('DemoTemplate/pam',compact('notif','notifOpen','notifsd','notiftp','pam','produks','pams','sum','id_pam','count_product','total_amount','no_pr', 'notifClaim'));
    }

    public function detail_pam($id_pam)
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

        $detail_pam = DB::table('dvg_pam_progress')
                    ->join('users', 'dvg_pam_progress.nik', '=', 'users.nik')
                    ->select('dvg_pam_progress.id_progress','dvg_pam_progress.created_at','dvg_pam_progress.keterangan','dvg_pam_progress.status','users.name')
                    ->where('dvg_pam_progress.id_pam',$id_pam)
                    ->get();

        $tampilkan = DB::table('dvg_pam')
                    ->join('users', 'users.nik', '=', 'dvg_pam.personel')
                    ->join('tb_pr','tb_pr.no_pr','=','dvg_pam.no_pr')
                    ->join('dvg_pr_product','dvg_pr_product.id_pam','=','dvg_pam.id_pam')
                    ->select('dvg_pam.id_pam','tb_pr.no_pr','dvg_pam.nik_admin','dvg_pam.date_handover','users.name', 'dvg_pam.to_agen', 'dvg_pr_product.nominal','dvg_pam.ket_pr','dvg_pam.status')
                    ->where('dvg_pam.id_pam',$id_pam)
                    ->first();

        $produks = DB::table('dvg_pam')
            ->join('dvg_pr_product','dvg_pr_product.id_pam','=','dvg_pam.id_pam')
            ->select('dvg_pr_product.name_product','dvg_pr_product.qty','dvg_pr_product.id_pam','dvg_pr_product.nominal','dvg_pr_product.total_nominal','dvg_pr_product.description')
            ->get();



        $total_amount = DB::table('dvg_pr_product')
                    ->select('total_nominal')
                    ->where('id_pam',$id_pam)
                    ->sum('total_nominal');

        return view('DemoTemplate/detail_pam',compact('detail_pam', 'notif', 'notifsd', 'notifOpen', 'notiftp', 'nomor', 'tampilkan','produks','total_amount', 'notifClaim'));
    }

    public function claim()
    {

        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position; 

        $owner2 = DB::table('users')
                    ->select('nik', 'id_company', 'id_position', 'id_division', 'id_territory', 'name', 'email', 'password', 'date_of_entry', 'date_of_birth', 'address', 'phone')
                    ->first();

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

        if ($div == 'HR' && $pos == 'HR MANAGER' || $div == 'FINANCE' && $pos == 'STAFF') {
            $datas_2018 = DB::table('dvg_esm')
                    ->join('users', 'users.nik', '=', 'dvg_esm.personnel')
                    ->select('no','date','users.name', 'type', 'description', 'amount', 'id_project', 'remarks', 'status','dvg_esm.created_at', 'dvg_esm.personnel', 'dvg_esm.year')
                    ->where('year','2018')
                    ->get();

            $datas_2019 = DB::table('dvg_esm')
                    ->join('users', 'users.nik', '=', 'dvg_esm.personnel')
                    ->select('no','date','users.name', 'type', 'description', 'amount', 'id_project', 'remarks', 'status','dvg_esm.created_at', 'dvg_esm.personnel', 'dvg_esm.year')
                    ->where('year','2019')
                    ->get();
        } elseif ($pos == 'ADMIN') {
            $datas_2018 = DB::table('dvg_esm')
                    ->join('users', 'users.nik', '=', 'dvg_esm.personnel')
                    ->select('no','date','nik_admin','users.name', 'type', 'description', 'amount', 'id_project', 'remarks', 'status','dvg_esm.created_at', 'dvg_esm.personnel', 'dvg_esm.year')
                    ->where('nik_admin',$nik)
                    ->where('year', '2018')
                    ->get();

            $datas_2019 = DB::table('dvg_esm')
                    ->join('users', 'users.nik', '=', 'dvg_esm.personnel')
                    ->select('no','date','nik_admin','users.name', 'type', 'description', 'amount', 'id_project', 'remarks', 'status','dvg_esm.created_at', 'dvg_esm.personnel', 'dvg_esm.year')
                    ->where('nik_admin',$nik)
                    ->where('year', '2019')
                    ->get();
        } else {
            $datas_2018 = DB::table('dvg_esm')
                    ->join('users', 'users.nik', '=', 'dvg_esm.personnel')
                    ->select('no','date','personnel','users.name', 'type', 'description', 'amount', 'id_project', 'remarks', 'status','dvg_esm.created_at', 'dvg_esm.personnel', 'dvg_esm.year')
                    ->where('personnel',$nik)
                    ->where('year', '2018')
                    ->get();

            $datas_2019 = DB::table('dvg_esm')
                    ->join('users', 'users.nik', '=', 'dvg_esm.personnel')
                    ->select('no','date','personnel','users.name', 'type', 'description', 'amount', 'id_project', 'remarks', 'status','dvg_esm.created_at', 'dvg_esm.personnel', 'dvg_esm.year')
                    ->where('personnel',$nik)
                    ->where('year', '2019')
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
        
       /* $nomor = DB::table('dvg_esm')
                    ->select('no')
                    ->where('no', $no)
                    ->first();*/

        return view('DemoTemplate/esm', compact('datas','notif','notifOpen','notifsd','notiftp','owner2','newDate', 'notifClaim','datas_2018', 'datas_2019'));
    }

    public function detail_claim($no)
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

        $nomor = DB::table('dvg_esm')
                    ->select('no')
                    ->where('no', $no)
                    ->first();

        $detail_esm = DB::table('dvg_esm_progress')
                    ->join('dvg_esm','dvg_esm_progress.no','=','dvg_esm.no')
                    ->join('users', 'dvg_esm_progress.nik', '=', 'users.nik')
                    ->select('dvg_esm_progress.id','dvg_esm_progress.created_at','dvg_esm_progress.keterangan','dvg_esm_progress.status','dvg_esm_progress.amount','users.name')
                    ->where('dvg_esm.no',$no)
                    ->get();

        $tampilkan = DB::table('dvg_esm')
                    ->join('users', 'users.nik', '=', 'dvg_esm.personnel')
                    ->select('no','date','users.name', 'type', 'description', 'amount', 'id_project', 'remarks', 'status','dvg_esm.created_at')
                    ->where('dvg_esm.no',$no)
                    ->first();

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

        return view('DemoTemplate/detail_claim',compact('detail_esm', 'notif', 'notifsd', 'notifOpen', 'notiftp', 'nomor', 'tampilkan','notifClaim'));
    }

    public function report_range()
    {

        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        // count semua lead
        if($ter != null){
            $lead = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name', 'sales_lead_register.closing_date')
                ->where('id_territory', $ter)
                ->get();
        } elseif($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $lead = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                ->select('sales_lead_register.lead_id','tb_contact.brand_name', 'sales_lead_register.opp_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.closing_date')
                ->where('sales_solution_design.nik', $nik)
                ->get();
        } else {
            $lead = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name', 'sales_lead_register.closing_date')
                ->get();
        }

        if ($ter != null) {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
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

        return view('DemoTemplate/report_range', compact('lead', 'notif', 'notifOpen', 'notifsd','notiftp'));
    }

    public function sho()
    {

        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        if($div == 'SALES'){
            $lead = DB::table('sales_sho')
                ->join('sales_lead_register','sales_lead_register.lead_id','=','sales_sho.lead_id')
                ->join('users','users.nik','=','sales_lead_register.nik')/*
                ->join('sales_sho_transaction','sales_sho_transaction.id_sho','=','sales_sho.id_sho')*/
                ->select('sales_lead_register.nik','sales_sho.id_sho','sales_sho.timeline','sales_sho.sow','sales_sho.top','sales_sho.service_budget','sales_sho.meeting_date','users.name','sales_sho.updated_at','sales_lead_register.status_sho', 'sales_sho.lead_id')
                ->where('id_territory', $ter)
                ->get();
        }elseif($div == 'TECHNICAL PRESALES' || $div == 'TECHNICAL' || $div == 'PMO'){
            $lead = DB::table('sales_sho')
                ->join('sales_lead_register','sales_lead_register.lead_id','=','sales_sho.lead_id')
                ->join('users','users.nik','=','sales_lead_register.nik')
                // ->join('sales_sho_transaction','sales_sho_transaction.id_sho','=','sales_sho.id_sho')
                ->select('sales_lead_register.nik','sales_sho.id_sho','sales_sho.timeline','sales_sho.sow','sales_sho.top','sales_sho.service_budget','sales_sho.meeting_date','users.name','sales_sho.updated_at','sales_lead_register.status_sho', 'sales_sho.lead_id')
                // ->where('sales_sho_transaction.nik', $nik)
                ->get();
        }else{
            $lead = DB::table('sales_sho')
                ->join('sales_lead_register','sales_lead_register.lead_id','=','sales_sho.lead_id')
                ->join('users','users.nik','=','sales_lead_register.nik')/*
                ->join('sales_sho_transaction','sales_sho_transaction.id_sho','=','sales_sho.id_sho')*/
                ->select('sales_lead_register.nik','sales_sho.id_sho','sales_sho.timeline','sales_sho.sow','sales_sho.top','sales_sho.service_budget','sales_sho.meeting_date','users.name','sales_sho.updated_at','sales_lead_register.status_sho', 'sales_sho.lead_id')
                ->get();
        }

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

        return view('DemoTemplate/sho',compact('lead','notif','notifOpen','notifsd','notiftp', 'notifClaim'));
    }

    public function detail_sho($id_sho)
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        $presales = DB::table('users')
                    ->select('name','nik')
                    ->where('id_division','TECHNICAL PRESALES')
                    ->get();

        $ter1 = DB::table('users')
                    ->select('name','nik')
                    ->where('id_territory','TERRITORY 1')
                    ->get();

        $ter2 = DB::table('users')
                    ->select('name','nik')
                    ->where('id_territory','TERRITORY 2')
                    ->get();

        $ter3 = DB::table('users')
                    ->select('name','nik')
                    ->where('id_territory','TERRITORY 3')
                    ->get();

        $ter4 = DB::table('users')
                    ->select('name','nik')
                    ->where('id_territory','TERRITORY 4')
                    ->get();

        $ter5 = DB::table('users')
                    ->select('name','nik')
                    ->where('id_territory','TERRITORY 5')
                    ->get();

        $ter6 = DB::table('users')
                    ->select('name','nik')
                    ->where('id_territory','TERRITORY 6')
                    ->get();

        $engineer = DB::table('users')
                    ->select('name','nik')
                    ->where('id_position','ENGINEER MANAGER')
                    ->orwhere('id_position','ENGINEER STAFF')
                    ->get();

        $pmo = DB::table('users')
                    ->select('name','nik')
                    ->where('id_division','PMO')
                    ->get();

        $tampilkan = SalesHandover::find($id_sho);

        $tampilkanb = DB::table('sales_sho')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','sales_sho.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->select('users.nik','sales_sho.id_sho','users.name')
                    ->where('id_sho',$id_sho)
                    ->first();

        $tampilkans = DB::table('sales_sho_transaction')
                    ->join('users','users.nik','=','sales_sho_transaction.nik')
                    ->join('sales_sho','sales_sho.id_sho','=','sales_sho_transaction.id_sho')
                    ->select('sales_sho_transaction.id_transaction','sales_sho.lead_id', 'users.name', 'sales_sho_transaction.keterangan', 'sales_sho_transaction.tanggal_hadir','sales_sho_transaction.updated_at','users.nik','sales_sho.id_sho','sales_sho_transaction.status','sales_sho.meeting_date','sales_sho_transaction.updated_at')
                    ->where('sales_sho_transaction.id_sho',$id_sho)
                    ->get();


        $tampilkanc = DB::table('sales_sho_transaction')
                    ->select('updated_at')
                    ->first();

        $tampilkanz = DB::table('sales_sho_transaction')
                    ->select('created_at')
                    ->where('id_sho',$id_sho)
                    ->first();

        $tampilkant = DB::table('sales_sho')
                    ->select(DB::raw("DATEDIFF(now(),created_at) AS DAYS"))
                    ->where('id_sho',$id_sho)
                    ->get();

        $tampilkanx = substr($tampilkant,9,-2);

        $now = 0;

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

        if ($div == 'SALES' && $pos == 'MANAGER') {
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

        return view('DemoTemplate/detail_sho',compact('tampilkan','tampilkans','notif','notifOpen','tampilkanc','tampilkant','tampilkanb','now','tampilkanx','tampilkanz','notifsd','presales','notiftp','ter1','ter2','ter3','ter4','ter5','ter6','engineer','pmo', 'notifClaim'));
        // return view('sales/detail_sho')->with('tampilkan',$tampilkan);
    }

    public function partnership()
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

        if ($ter != null) {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik','lead_id')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();

             $notifc = count($notif);
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $notif = DB::table('sales_lead_register')
            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->select('sales_lead_register.opp_name','sales_solution_design.nik','sales_lead_register.lead_id')
            ->where('result','OPEN')
            ->orderBy('sales_lead_register.created_at','desc')
            ->get();

             $notifc = count($notif);
        }else{
             $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik','lead_id')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();

            $notifc = count($notif);        
        }


        $datas = DB::table('tb_partnership')
                        ->select('id_partnership', 'partner' , 'level', 'renewal_date', 'annual_fee', 'sales_target', 'sales_certification', 'engineer_certification', 'type')
                        ->get();

        return view('DemoTemplate/partnership', compact('lead', 'total_ter','notif','notifOpen','notifsd','notiftp','id_pro', 'datas', 'notifClaim', 'notifc'));
    }

    public function idpro()
    {


        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        $pops = SalesProject::select('id_project')->orderBy('id_project','desc')->first();

        if ($div == 'SALES' && $pos != 'ADMIN') {
            $salessp = DB::table('tb_id_project')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
                    ->select('tb_contact.customer_legal_name','tb_id_project.id_project','tb_id_project.date','tb_id_project.no_po_customer','sales_lead_register.opp_name','users.name','tb_id_project.amount_idr','tb_id_project.amount_usd','sales_lead_register.lead_id','sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro')
                    ->where('sales_lead_register.nik',$nik)
                    ->where('id_company','1')
                    ->get();

            $salesmsp = DB::table('tb_id_project')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
                    ->select('tb_contact.customer_legal_name','tb_id_project.id_project','tb_id_project.date','tb_id_project.no_po_customer','sales_lead_register.opp_name','users.name','tb_id_project.amount_idr','tb_id_project.amount_usd','sales_lead_register.lead_id','sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro')
                    ->where('sales_lead_register.nik',$nik)
                    ->where('id_company','2')
                    ->get(); 
        }elseif ($div == 'TECHNICAL' && $pos == 'MANAGER' || $pos == 'DIRECTOR') {
            $salessp = DB::table('tb_id_project')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
                    ->select('tb_contact.customer_legal_name','tb_id_project.id_project','tb_id_project.date','tb_id_project.no_po_customer','sales_lead_register.opp_name','users.name','tb_id_project.amount_idr','tb_id_project.amount_usd','sales_lead_register.lead_id','sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro')
                    ->where('id_company','1')
                    ->get(); 

            $salesmsp = DB::table('tb_id_project')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
                    ->select('tb_contact.customer_legal_name','tb_id_project.id_project','tb_id_project.date','tb_id_project.no_po_customer','sales_lead_register.opp_name','users.name','tb_id_project.amount_idr','tb_id_project.amount_usd','sales_lead_register.lead_id','sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro')
                    ->where('id_company','2')
                    ->get();
        }elseif($div == 'FINANCE'){
            if ($pos == 'MANAGER') {
                $salessp = DB::table('tb_id_project')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
                    ->select('tb_contact.customer_legal_name','tb_id_project.id_project','tb_id_project.date','tb_id_project.no_po_customer','sales_lead_register.opp_name','users.name','tb_id_project.amount_idr','tb_id_project.amount_usd','sales_lead_register.lead_id','sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro','tb_id_project.invoice')
                    ->where('id_company','1')
                    ->get(); 

                $salesmsp = DB::table('tb_id_project')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
                    ->select('tb_contact.customer_legal_name','tb_id_project.id_project','tb_id_project.date','tb_id_project.no_po_customer','sales_lead_register.opp_name','users.name','tb_id_project.amount_idr','tb_id_project.amount_usd','sales_lead_register.lead_id','sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro','tb_id_project.invoice')
                    ->where('id_company','2')
                    ->get();
            }elseif ($pos == 'STAFF') {
                $salessp = DB::table('tb_id_project')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
                    ->select('tb_contact.customer_legal_name','tb_id_project.id_project','tb_id_project.date','tb_id_project.no_po_customer','sales_lead_register.opp_name','users.name','sales_lead_register.lead_id','sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro','tb_id_project.invoice')
                    ->where('id_company','1')
                    ->get();

                $salesmsp = DB::table('tb_id_project')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
                    ->select('tb_contact.customer_legal_name','tb_id_project.id_project','tb_id_project.date','tb_id_project.no_po_customer','sales_lead_register.opp_name','users.name','sales_lead_register.lead_id','sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro','tb_id_project.invoice')
                    ->where('id_company','2')
                    ->get();
            }
        }
        else{
            $salessp = DB::table('tb_id_project')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
                    ->select('tb_contact.customer_legal_name','tb_id_project.id_project','tb_id_project.date','tb_id_project.no_po_customer','sales_lead_register.opp_name','users.name','sales_lead_register.lead_id','sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro','tb_id_project.invoice')
                    ->where('id_company','1')
                    ->get();

            $salesmsp = DB::table('tb_id_project')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
                    ->select('tb_contact.customer_legal_name','tb_id_project.id_project','tb_id_project.date','tb_id_project.no_po_customer','sales_lead_register.opp_name','users.name','sales_lead_register.lead_id','sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro','tb_id_project.invoice')
                    ->where('id_company','2')
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
                    ->select('sales_lead_register.lead_id','opp_name','pid')
                    ->where('result','WIN')
                    ->where('year','2019')
                    ->where('id_company','1')
                    ->get();

        $lead_msp = DB::table('sales_lead_register')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->select('lead_id','opp_name','pid')
                    ->where('result','WIN')
                    ->where('year','2019')
                    ->where('id_company','2')
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

      return view('DemoTemplate/idpro',compact('salessp','salesmsp','lead_sp','lead_msp','notif','notifOpen','notifsd','notiftp', 'notifClaim','pops','datas'));
    }

    public function employees(Request $request)
    {

        // $hr = HRCrud::all();
        // return view('HR/human_resource')->with('hr', $hr);
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        $hr = DB::table('users')
                ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                ->select('users.nik', 'users.name', 'users.id_position', 'users.id_division', 'users.id_territory', 'tb_company.code_company','users.email','users.date_of_entry','users.date_of_birth','users.address','users.phone','users.password','users.id_company')
                ->where('users.nik','!=','100000000000')
                ->get();

        $code = $request['code_input'];


        // if ($ter != null) {
        //     $notif = DB::table('sales_lead_register')
        //     ->select('opp_name')
        //     ->orderBy('created_at','desc')->first();
        // }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
        //     $notif = DB::table('sales_lead_register')
        //     ->select('opp_name')
        //     ->orderBy('created_at','desc')->first();
        // }else{
        //     $notif = DB::table('sales_lead_register')
        //     ->select('opp_name')
        //     ->orderBy('created_at','desc')->first();
        // }

        if ($ter != null) {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','OPEN')
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
            ->select('sales_lead_register.opp_name','sales_solution_design.nik')
            ->where('result','')
            ->orderBy('sales_lead_register.created_at','desc')
            ->get();
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $notifOpen= DB::table('sales_lead_register')
            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->select('sales_lead_register.opp_name','sales_solution_design.nik')
            ->where('result','')
            ->orderBy('sales_lead_register.created_at','desc')
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

        return view('DemoTemplate/employees', compact('hr','notif','notifOpen','notifsd','notiftp','ter','code', 'notifClaim'));
    }

    public function leaving_permit(Request $request)
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
                ->join('tb_position','tb_position.id_position','=','users.id_position')
                ->join('tb_division','tb_division.id_division','=','users.id_division')
                ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason')
                ->where('id_territory', $ter)
                ->get();
            }elseif($div == 'TECHNICAL' && $pos == 'ENGINEER MANAGER' && $ter == 'DPG'){
                $cuti = DB::table('tb_cuti')
                    ->join('users','users.nik','=','tb_cuti.nik')
                    ->join('tb_position','tb_position.id_position','=','users.id_position')
                    ->join('tb_division','tb_division.id_division','=','users.id_division')
                    ->join('tb_territory','tb_territory.id_territory','=','users.id_territory')
                    ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason')
                    ->where('users.id_territory','DPG')
                    ->get();
            }else{
                $cuti = DB::table('tb_cuti')
                ->join('users','users.nik','=','tb_cuti.nik')
                ->join('tb_position','tb_position.id_position','=','users.id_position')
                ->join('tb_division','tb_division.id_division','=','users.id_division')
                ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason')
                ->where('users.nik',$nik)
                ->get();
            }
        }elseif($div == 'TECHNICAL DVG' && $pos == 'MANAGER'){
        $cuti = DB::table('tb_cuti')
            ->join('users','users.nik','=','tb_cuti.nik')
            ->join('tb_position','tb_position.id_position','=','users.id_position')
            ->join('tb_division','tb_division.id_division','=','users.id_division')
            ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason')
            ->where('users.id_division','TECHNICAL DVG')
            ->get();
        }elseif($div == 'TECHNICAL PRESALES' && $pos == 'MANAGER'){
        $cuti = DB::table('tb_cuti')
            ->join('users','users.nik','=','tb_cuti.nik')
            ->join('tb_position','tb_position.id_position','=','users.id_position')
            ->join('tb_division','tb_division.id_division','=','users.id_division')
            ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason')
            ->where('users.id_division','TECHNICAL PRESALES')
            ->get();
        }elseif($div == 'FINANCE' && $pos == 'MANAGER'){
        $cuti = DB::table('tb_cuti')
            ->join('users','users.nik','=','tb_cuti.nik')
            ->join('tb_position','tb_position.id_position','=','users.id_position')
            ->join('tb_division','tb_division.id_division','=','users.id_division')
            ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason')
            ->where('users.id_division','FINANCE')
            ->get();
        }elseif($div == 'PMO' && $pos == 'MANAGER'){
        $cuti = DB::table('tb_cuti')
            ->join('users','users.nik','=','tb_cuti.nik')
            ->join('tb_position','tb_position.id_position','=','users.id_position')
            ->join('tb_division','tb_division.id_division','=','users.id_division')
            ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason')
            ->where('users.id_division','PMO')
            ->get();
        }elseif($div == 'TECHNICAL DVG' && $pos == 'STAFF' || $div == 'TECHNICAL DPG' && $pos == 'ENGINEER STAFF' || $div == 'TECHNICAL PRESALES' && $pos == 'STAFF' || $div == 'FINANCE' && $pos == 'STAFF' || $div == 'PMO' && $pos == 'STAFF'){
        $cuti = DB::table('tb_cuti')
            ->join('users','users.nik','=','tb_cuti.nik')
            ->join('tb_position','tb_position.id_position','=','users.id_position')
            ->join('tb_division','tb_division.id_division','=','users.id_division')
            ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason')
            ->where('users.nik',$nik)
            ->get();
        }elseif($pos == 'ADMIN'){
        $cuti = DB::table('tb_cuti')
            ->join('users','users.nik','=','tb_cuti.nik')
            ->join('tb_position','tb_position.id_position','=','users.id_position')
            ->join('tb_division','tb_division.id_division','=','users.id_division')
            ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason')
            ->where('users.nik',$nik)
            ->get();
        }elseif($div == 'HR' && $pos == 'HR MANAGER'){
        $cuti = DB::table('tb_cuti')
            ->join('users','users.nik','=','tb_cuti.nik')
            ->join('tb_position','tb_position.id_position','=','users.id_position')
            ->join('tb_division','tb_division.id_division','=','users.id_division')
            ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason')
            ->where('status','v')
            ->get();
        }else{
        $cuti = DB::table('tb_cuti')
            ->join('users','users.nik','=','tb_cuti.nik')
            ->join('tb_position','tb_position.id_position','=','users.id_position')
            ->join('tb_division','tb_division.id_division','=','users.id_division')
            ->select('users.nik','users.name','tb_position.name_position','tb_division.name_division','tb_cuti.date_req','tb_cuti.reason_leave','tb_cuti.date_start','tb_cuti.date_end','tb_cuti.id_cuti','tb_cuti.status','tb_cuti.decline_reason')
            ->get();
        }

        if ($ter != null) {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','OPEN')
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
            ->select('sales_lead_register.opp_name','sales_solution_design.nik')
            ->where('result','')
            ->orderBy('sales_lead_register.created_at','desc')
            ->get();
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $notifOpen= DB::table('sales_lead_register')
            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->select('sales_lead_register.opp_name','sales_solution_design.nik')
            ->where('result','')
            ->orderBy('sales_lead_register.created_at','desc')
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

        return view('DemoTemplate/leaving', compact('notif','notifOpen','notifsd','notiftp','cuti', 'notifClaim'));
    }

    public function customer()
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

        return view('DemoTemplate/customer',compact('data', 'notif','notifOpen','notifsd','notiftp', 'notifClaim'));
    }

    public function admin_hr()
    {

        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position; 

        $pops = HRNumber::select('no_letter')->orderBy('no_letter','desc')->first();

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

        $datas = DB::table('tb_hr_number')
                        ->join('users', 'users.nik', '=', 'tb_hr_number.from')
                        ->select('no','no_letter', 'type_of_letter', 'divsion', 'pt', 'month', 'date', 'to', 'attention', 'title', 'project', 'description', 'from', 'division', 'project_id', 'name', 'note')
                        ->get();

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

        return view('DemoTemplate/demo_hr', compact('lead', 'total_ter','notif','notifOpen','notifsd','notiftp','id_pro', 'datas', 'notifClaim','pops'));
    }

    public function admin_pr()
    {

        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position; 

        $pops = PR::select('no_pr')->orderBy('no_pr','desc')->first();

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

        $datas = DB::table('tb_pr')
                        ->join('users', 'users.nik', '=', 'tb_pr.from')
                        ->select('no','no_pr', 'position', 'type_of_letter', 'month', 'date', 'to', 'attention', 'title', 'project', 'description', 'from', 'division', 'issuance', 'project_id', 'name', 'note', 'users.nik')
                        ->get();

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

        return view('DemoTemplate/demo_pr', compact('lead', 'total_ter','notif','notifOpen','notifsd','notiftp','id_pro', 'datas', 'notifClaim','pops'));
    }

    public function admin_po()
    {

        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position; 

        $pops = PONumber::select('no_po')->orderBy('no_po','desc')->first();

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

        $datas = DB::table('tb_po')
                        ->join('users','users.nik','=','tb_po.from')
                        ->select('tb_po.no','tb_po.no_po', 'tb_po.position', 'tb_po.type_of_letter', 'tb_po.month', 'tb_po.date', 'tb_po.to', 'tb_po.attention', 'tb_po.title', 'tb_po.project', 'tb_po.description', 'tb_po.from', 'tb_po.division', 'tb_po.issuance', 'tb_po.project_id', 'users.name', 'tb_po.note')
                        ->get();

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

        return view('DemoTemplate/demo_po', compact('lead', 'total_ter','notif','notifOpen','notifsd','notiftp','id_pro', 'datas', 'notifClaim','pops'));
    }

    public function admin_quote()
    {

        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        $pops = Quote::select('quote_number')->orderBy('quote_number','desc')->first();

        $datas = DB::table('tb_quote')
                        ->join('users', 'users.nik', '=', 'tb_quote.nik')
                        ->select('id_quote','quote_number','position','type_of_letter','date','to','attention','title','project','status', 'description', 'from', 'division', 'project_id','note', 'status_backdate', 'tb_quote.nik', 'name', 'month')
                        ->where('status_backdate', NULL)
                        ->orWhere('status_backdate', 'F')
                        ->get();

        $count = DB::table('tb_quote')
                    ->where('status_backdate', 'T')
                    ->get();

        $counts = count($count);

        // if ($ter != null) {
        //     $notif = DB::table('sales_lead_register')
        //     ->select('opp_name')
        //     ->orderBy('created_at','desc')->first();
        // }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
        //     $notif = DB::table('sales_lead_register')
        //     ->select('opp_name')
        //     ->orderBy('created_at','desc')->first();
        // }else{
        //     $notif = DB::table('sales_lead_register')
        //     ->select('opp_name')
        //     ->orderBy('created_at','desc')->first();
        // }

        if ($ter != null) {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','OPEN')
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

        return view('DemoTemplate/demo_quote',compact('notif','datas','notifOpen','notifsd','notiftp', 'notifClaim', 'counts', 'count','pops'));
    }

    public function admin_letter()
    {

        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position; 

        $pops = letter::select('no_letter')->orderBy('no_letter','desc')->first();

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

        $datas = DB::table('tb_letter')
                        ->join('users', 'users.nik', '=', 'tb_letter.nik')
                        ->select('no','no_letter', 'position', 'type_of_letter', 'month', 'date', 'to', 'attention', 'title', 'project', 'description', 'from', 'division', 'project_id', 'status', 'note', 'name', 'tb_letter.nik')
                        ->where('status', NULL)
                        ->orwhere('status', 'F')
                        ->get();

        $count = DB::table('tb_letter')
                    ->where('status', 'T')
                    ->get();

        $counts = count($count);

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


        return view('DemoTemplate/demo_letter', compact('lead', 'total_ter','notif','notifOpen','notifsd','notiftp','id_pro', 'datas', 'notifClaim','counts','pops'));
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
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.result2','sales_lead_register.result3','sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.status_engineer')
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

        $pmo_contribute = DB::table('tb_pmo')
                    ->join('users','users.nik','=','tb_pmo.pmo_nik')
                    ->select('users.name','tb_pmo.id_pmo')
                    ->where('lead_id',$lead_id)
                    ->get();

        $engineer_contribute = DB::table('tb_engineer')
                    ->join('users','users.nik','=','tb_engineer.nik')
                    ->select('tb_engineer.id_engineer','users.name')
                    ->where('lead_id',$lead_id)
                    ->get();

        $tampilkanc = DB::table('sales_tender_process')
        			->join('sales_lead_register', 'sales_tender_process.lead_id', '=', 'sales_lead_register.lead_id')
                    ->select('sales_tender_process.lead_id','auction_number','submit_price','win_prob','project_name','submit_date','quote_number','status','sales_lead_register.nik', 'sales_tender_process.assigned_by','quote_number2')
                    ->where('sales_tender_process.lead_id',$lead_id)
                    ->first();

        $get_quote_number = DB::table('tb_quote')
                            ->select('id_quote', 'quote_number')
                            ->where('status','T')
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

        $pmo_id = DB::table('tb_pmo')
                    ->join('users','tb_pmo.pmo_nik','=','users.nik')
                    ->select('tb_pmo.id_pmo','users.name')
                    ->where('lead_id',$lead_id)
                    ->first();

        $sd_id = DB::table('sales_solution_design')
                ->join('users','users.nik','=','sales_solution_design.nik')
                ->select('sales_solution_design.id_sd','users.name')
                ->where('lead_id',$lead_id)
                ->first();

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
                        ->select('sales_change_log.created_at', 'sales_lead_register.opp_name', 'sales_change_log.status', 'users.name', 'sales_change_log.submit_price')
                        ->where('sales_change_log.lead_id',$lead_id)
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

        return view('DemoTemplate/demo_detail_sales',compact('lead','tampilkan','tampilkans','tampilkan_com', 'tampilkana', 'tampilkanc','notif','notifOpen','notifsd','notiftp','tampilkan_progress','pmo_id','engineer_id','current_eng','tampilkan_progress_engineer','pmo_contribute','engineer_contribute','q_num','sd_id', 'get_quote_number', 'q_num2', 'change_log','notifClaim'));
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
        
      /*  $angka = $request['amount'];
        $format_rupiah = number_format($angka, '2', '.', ',');*/

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
            $tambah->result = 'OPEN';
            $tambah->closing_date = $request['closing_date'];
            $tambah->keterangan = $request['note'];
            $tambah->save();

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
            
            $users = User::select('email')->where('id_position', 'STAFF')->where('id_division', 'TECHNICAL')->where('id_territory', 'DVG')->get();
            Notification::send($kirim, new NewLead());

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
            $tambah->closing_date = $request['closing_date'];
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
        $update->closing_date = $request['closing_date_edit'];
        $update->keterangan = $request['note_edit'];
        $update->update();

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
        $tambah->nik = $request['add_contribute'];
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
        if (Auth::User()->id_company == '1') {
        
            $lead_id = $request['lead_id_result'];        

            $update = Sales::where('lead_id', $lead_id)->first();
            $update->result = $request['result'];
            $update->keterangan = $request['keterangan'];
            $update->update();

            if($request['result'] != 'HOLD'){
                $update = TenderProcess::where('lead_id', $lead_id)->first();
                $update->status = 'closed';
                $update->update();
            }

            $tambah = new SalesChangeLog();
            $tambah->lead_id = $request['lead_id_result'];
            $tambah->nik = Auth::User()->nik;
            if($request['result'] == 'WIN'){
                $tambah->status = 'Update WIN';
            } elseif($request['result'] == 'LOSE'){
                $tambah->status = 'Update LOSE';
            } elseif($request['result'] == 'HOLD'){
                $tambah->status = 'Update HOLD';
            } elseif($request['result'] == 'CANCEL'){
                $tambah->status = 'Update CANCEL';
            }
            $tambah->save();

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

            if(Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'SALES' && Auth::User()->id_territory == $nik_sales->id_territory){
                $kirim = User::select('email')
                                ->where('id_division', 'TECHNICAL PRESALES')
                                ->where('nik', $current_presales->nik)
                                ->orWhere('nik', $presales_manager->nik)
                                ->orWhere('email', 'nabil@sinergy.co.id')
                                ->orWhere('email', 'yuliane@sinergy.co.id')
                                ->get();
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

            $users = User::where('email','arkhab@sinergy.co.id')->first();
            Notification::send($kirim, new Result());

        }else{
            
            $lead_id = $request['lead_id_result'];        

            $update = Sales::where('lead_id', $lead_id)->first();
            $update->result = $request['result'];
            $update->keterangan = $request['keterangan'];
            $update->update();

            if($request['result'] != 'HOLD'){
                $update = TenderProcess::where('lead_id', $lead_id)->first();
                $update->status = 'closed';
                $update->update();
            }

            $tambah = new SalesChangeLog();
            $tambah->lead_id = $request['lead_id_result'];
            $tambah->nik = Auth::User()->nik;
            if($request['result'] == 'WIN'){
                $tambah->status = 'Update WIN';
            } elseif($request['result'] == 'LOSE'){
                $tambah->status = 'Update LOSE';
            } elseif($request['result'] == 'HOLD'){
                $tambah->status = 'Update HOLD';
            } elseif($request['result'] == 'CANCEL'){
                $tambah->status = 'Update CANCEL';
            }
            $tambah->save();

        }
       

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
          } else {
            $update->assessment = $request['assesment'];
            $update->assessment_date = $request['assesment_date_before'];
            $update->update();
          }

          if($request['propossed_design'] != $request['pd_before']){
            $update->pd = $request['propossed_design'];
            $update->pd_date = date('Y-m-d H:i:s');
            $update->update();
          } else {
            $update->pd = $request['propossed_design'];
            $update->pd_date = $request['pd_date_before'];
            $update->update();
          }

          if($request['pov'] != $request['pov_before']){
            $update->pov = $request['pov'];
            $update->pov_date = date('Y-m-d H:i:s');
            $update->update();
          } else {
            $update->pov = $request['pov'];
            $update->pov_date = $request['pov_date_before'];
            $update->update();
          }

          if(str_replace(',', '',$request['project_budget']) <= $request['amount_check']){
            if($request['project_budget'] != $request['project_budget_before']){
                $update->pb = str_replace(',', '',$request['project_budget']);
                $update->update();
            }
          } else {
            return back()->with('warning','Project Budget melebihi Amount!');
          }

          // if($request['project_budget'] != $request['project_budget_before']){
          //   $update->pb = $request['project_budget'];
          //   $update->update();
          // }

          $update->priority = $request['priority'];
          $update->project_size = $request['proyek_size'];
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

        $tambah = new SalesChangeLog();
        $tambah->lead_id = $request['lead_id'];
        $tambah->nik = Auth::User()->nik;
        $tambah->status = 'Update SD';
        $tambah->save();

        return redirect()->back();
    }

    public function update_tp(Request $request, $lead_id)
    {
        $update = TenderProcess::where('lead_id', $lead_id)->first();

        // $id_quote = $request['quote_before'];
        // $id_quote_true = $request['quote_number'];
        // $update_false = Quote::where('id_quote', $id_quote)->first();
        // $update_true = Quote::where('id_quote', $id_quote_true)->first();

        if($request['submit_price'] != $request['submit_price_before']){
 /*           $angka = $request['submit_price'];
            $format_rupiah = number_format($angka, '2', ',', '.'); */
           $update->submit_price = $request['submit_price'];
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
           $update->submit_price = $request['submit_price'];
           $update->update();
        }else if ($request['submit_price'] == TRUE) {
           $update->submit_price = $request['submit_price'];
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

        if (is_null($request['submit_date'])) {   
            $update->submit_date = $request['submit_date'];
            $update->update();
        }else if ( $request['submit_date'] == TRUE) {
           $update->submit_date = $request['submit_date'];
           $update->update();   
        }

        if($request['submit_price'] != $request['submit_price_before']){
            $update->submit_price = $request['submit_price'];
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
        $tambah->submit_price = $request['submit_price'];
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
        $tambah->office_building = $request['office_building'];
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

        return redirect('customer');
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

	public function sales_project_index(){

	    $nik = Auth::User()->nik;
	    $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
	    $ter = $territory->id_territory;
	    $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
	    $div = $division->id_division;
	    $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
	    $pos = $position->id_position;

        $pops = SalesProject::select('id_project')->orderBy('id_project','desc')->first();

        if ($div == 'SALES' && $pos != 'ADMIN') {
            $salessp = DB::table('tb_id_project')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
                    ->select('tb_contact.customer_legal_name','tb_id_project.id_project','tb_id_project.date','tb_id_project.no_po_customer','sales_lead_register.opp_name','users.name','tb_id_project.amount_idr','tb_id_project.amount_usd','sales_lead_register.lead_id','sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro')
                    ->where('sales_lead_register.nik',$nik)
                    ->where('id_company','1')
                    ->get();

            $salesmsp = DB::table('tb_id_project')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
                    ->select('tb_contact.customer_legal_name','tb_id_project.id_project','tb_id_project.date','tb_id_project.no_po_customer','sales_lead_register.opp_name','users.name','tb_id_project.amount_idr','tb_id_project.amount_usd','sales_lead_register.lead_id','sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro')
                    ->where('sales_lead_register.nik',$nik)
                    ->where('id_company','2')
                    ->get(); 
        }elseif ($div == 'TECHNICAL' && $pos == 'MANAGER' || $pos == 'DIRECTOR') {
            $salessp = DB::table('tb_id_project')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
                    ->select('tb_contact.customer_legal_name','tb_id_project.id_project','tb_id_project.date','tb_id_project.no_po_customer','sales_lead_register.opp_name','users.name','tb_id_project.amount_idr','tb_id_project.amount_usd','sales_lead_register.lead_id','sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro')
                    ->where('id_company','1')
                    ->get(); 

            $salesmsp = DB::table('tb_id_project')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
                    ->select('tb_contact.customer_legal_name','tb_id_project.id_project','tb_id_project.date','tb_id_project.no_po_customer','sales_lead_register.opp_name','users.name','tb_id_project.amount_idr','tb_id_project.amount_usd','sales_lead_register.lead_id','sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro')
                    ->where('id_company','2')
                    ->get();
        }elseif($div == 'FINANCE'){
            if ($pos == 'MANAGER') {
                $salessp = DB::table('tb_id_project')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
                    ->select('tb_contact.customer_legal_name','tb_id_project.id_project','tb_id_project.date','tb_id_project.no_po_customer','sales_lead_register.opp_name','users.name','tb_id_project.amount_idr','tb_id_project.amount_usd','sales_lead_register.lead_id','sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro','tb_id_project.invoice')
                    ->where('id_company','1')
                    ->get(); 

                $salesmsp = DB::table('tb_id_project')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
                    ->select('tb_contact.customer_legal_name','tb_id_project.id_project','tb_id_project.date','tb_id_project.no_po_customer','sales_lead_register.opp_name','users.name','tb_id_project.amount_idr','tb_id_project.amount_usd','sales_lead_register.lead_id','sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro','tb_id_project.invoice')
                    ->where('id_company','2')
                    ->get();
            }elseif ($pos == 'STAFF') {
                $salessp = DB::table('tb_id_project')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
                    ->select('tb_contact.customer_legal_name','tb_id_project.id_project','tb_id_project.date','tb_id_project.no_po_customer','sales_lead_register.opp_name','users.name','sales_lead_register.lead_id','sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro','tb_id_project.invoice')
                    ->where('id_company','1')
                    ->get();

                $salesmsp = DB::table('tb_id_project')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
                    ->select('tb_contact.customer_legal_name','tb_id_project.id_project','tb_id_project.date','tb_id_project.no_po_customer','sales_lead_register.opp_name','users.name','sales_lead_register.lead_id','sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro','tb_id_project.invoice')
                    ->where('id_company','2')
                    ->get();
            }
        }
        else{
            $salessp = DB::table('tb_id_project')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
                    ->select('tb_contact.customer_legal_name','tb_id_project.id_project','tb_id_project.date','tb_id_project.no_po_customer','sales_lead_register.opp_name','users.name','sales_lead_register.lead_id','sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro','tb_id_project.invoice')
                    ->where('id_company','1')
                    ->get();

            $salesmsp = DB::table('tb_id_project')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
                    ->select('tb_contact.customer_legal_name','tb_id_project.id_project','tb_id_project.date','tb_id_project.no_po_customer','sales_lead_register.opp_name','users.name','sales_lead_register.lead_id','sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro','tb_id_project.invoice')
                    ->where('id_company','2')
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
                    ->select('sales_lead_register.lead_id','opp_name','pid')
                    ->where('result','WIN')
                    ->where('year','2019')
                    ->where('id_company','1')
                    ->get();

        $lead_msp = DB::table('sales_lead_register')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->select('lead_id','opp_name','pid')
                    ->where('result','WIN')
                    ->where('year','2019')
                    ->where('id_company','2')
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

      return view('sales/sales_project',compact('salessp','salesmsp','lead_sp','lead_msp','notif','notifOpen','notifsd','notiftp', 'notifClaim','pops','datas'));
    }

    public function store_sales_project(Request $request){
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
        $hitung_sip = DB::table('tb_id_project')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->select('id_project','users.id_company')
                    ->orderBy('id_project','desc')
                    ->where('users.id_company','1')
                    ->first();

        $hitung_msp = DB::table('tb_id_project')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->select('id_project','users.id_company')
                    ->orderBy('id_project','desc')
                    ->where('users.id_company','2')
                    ->first();

        $cek_sip = Sales::join('users','users.nik','=','sales_lead_register.nik')
                ->select('id_company')
                ->where('lead_id',$request['customer_name'])
                ->first();

   /*     $inc_sip = DB::table('tb_id_project')
                    ->select('id_project')
                    ->where('id_company','1')
                    ->orderBy('id_project','desc')
                    ->first();

        $inc_msp = DB::table('tb_id_project')
                    ->select('id_project')
                    ->where('id_company','2')
                    ->orderBy('id_project','desc')
                    ->first();*/
        $cek_pid = Salesproject::select('lead_id')->where('lead_id',$contact)->count('lead_id');
        $counts = count($hitung_sip);

        $countss = count($hitung_msp);

        if ($cek_sip->id_company == '1') {
            if ($counts > 0) {
                $increment = round($hitung_sip->id_project);
            }else{
              $increment = round($hitung_sip);
            }
            $nomor = $increment+1;

            if($nomor <= 9){
                $nomor = '00' . $nomor;
            }elseif($nomor > 9 && $nomor < 99){
                $nomor = '0' . $nomor;
            }

            $project = $nomor.'/'.$name .'/'. $bln .'/'. date('Y');

            $lead_id = $request['customer_name'];

            $update = Sales::where('lead_id', $lead_id)->first();
            $update->status_sho = 'SHO';
            $update->pid = $cek_pid + 1;
            $update->update();

            $tambah = new SalesProject();
            $tambah->date = $request['date'];
            $tambah->id_project = $project;
            $tambah->nik = $request['sales'];
            $tambah->no_po_customer = $request['po_customer'];
            $tambah->lead_id = $request['customer_name'];
            $tambah->name_project = $request['name_project'];
            $tambah->amount_idr = str_replace(',', '', $request['amount']);
            $tambah->amount_usd = $request['kurs'];
            $tambah->note = $request['note'];
            $tambah->save();

            return redirect('salesproject')->with('success', 'Create PID Successfully!');

        }else if($cek_sip->id_company == '2'){
            if ($countss > 0) {
            $increment = round($hitung_msp->id_project);
            }else{
              $increment = round($hitung_msp);
            }
            $nomor = $increment+1;

            if($nomor <= 9){
                $nomor = '00' . $nomor;
            }elseif($nomor > 9 && $nomor < 99){
                $nomor = '0' . $nomor;
            }

            $project = $nomor.'/'.$name .'/'. $bln .'/'. date('Y');

            $lead_id = $request['customer_name'];

            $update = Sales::where('lead_id', $lead_id)->first();
            $update->status_sho = 'SHO';
            $update->pid = $cek_pid + 1;
            $update->update();

            $tambah = new SalesProject();
            $tambah->date = $request['date'];
            $tambah->id_project = $project;
            $tambah->nik = $request['sales'];
            $tambah->no_po_customer = $request['po_customer'];
            $tambah->lead_id = $request['customer_name'];
            $tambah->name_project = $request['name_project'];
            $tambah->amount_idr = str_replace(',', '', $request['amount']);
            $tambah->amount_usd = $request['kurs'];
            $tambah->note = $request['note'];
            $tambah->save();

            return redirect('salesproject')->with('success', 'Create PID Successfully!');
        }
        
    }

    public function getDatalead(Request $request)
    {
         return array(DB::table('sales_lead_register')
                ->select('lead_id,opp_name')
                ->where('result','WIN')
                ->get());
    }

    public function update_sp(Request $request)
    {
        $id_project = $request['id_project_edit'];

        $update = SalesProject::where('id_project', $id_project)->first();
        $update->no_po_customer = $request['po_customer_edit'];
        $update->name_project = $request['name_project_edit'];
        if (Auth::User()->id_position == 'MANAGER') {
            $update->amount_idr = str_replace(',', '', $request['amount_edit']);
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
            $row->setFontSize(11);
            $row->setAlignment('center');
            $row->setFontWeight('bold');
        });

        $sheet->row(1, array('Data ID Project SIP'));

        $sheet->row(2, function ($row) {
            $row->setFontFamily('Calibri');
            $row->setFontSize(11);
            $row->setFontWeight('bold');
        });

        $datas = Salesproject::join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
            ->join('users','users.nik','=','sales_lead_register.nik')
            ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
            ->select('date', 'id_project', 'no_po_customer',  'sales_lead_register.opp_name', 'amount_idr', 'users.name', 'tb_contact.customer_legal_name')
            ->where('year','2019')
            ->where('id_company','1')
            ->get();

       // $sheet->appendRow(array_keys($datas[0]));
            $sheet->row($sheet->getHighestRow(), function ($row) {
                $row->setFontWeight('bold');
            });

             $datasheet = array();
             $datasheet[0]  =   array("No", "Date", "ID Project", "No. PO customer", "Customer Name", "Project Name",  "Sales", "Amount IDR");
             $i=1;

            foreach ($datas as $data) {

               // $sheet->appendrow($data);
              $datasheet[$i] = array(
                            $i,
                            $data['date'],
                            $data['id_project'],
                            $data['no_po_customer'],
                            $data['customer_legal_name'],
                            $data['opp_name'],
                            $data['name'],
                            $data['amount_idr']
                        );
              
              $i++;
            }

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
            $row->setFontSize(11);
            $row->setAlignment('center');
            $row->setFontWeight('bold');
        });

        $sheet->row(1, array('Data ID Project MSP'));

        $sheet->row(2, function ($row) {
            $row->setFontFamily('Calibri');
            $row->setFontSize(11);
            $row->setFontWeight('bold');
        });

        $datas = Salesproject::join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
            ->join('users','users.nik','=','sales_lead_register.nik')
            ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
            ->select('date', 'id_project', 'no_po_customer',  'sales_lead_register.opp_name', 'amount_idr', 'users.name', 'tb_contact.customer_legal_name')
            ->where('year','2019')
            ->where('id_company','2')
            ->get();

       // $sheet->appendRow(array_keys($datas[0]));
            $sheet->row($sheet->getHighestRow(), function ($row) {
                $row->setFontWeight('bold');
            });

             $datasheet = array();
             $datasheet[0]  =   array("No", "Date", "ID Project", "No. PO customer", "Customer Name", "Project Name",  "Sales", "Amount IDR");
             $i=1;

            foreach ($datas as $data) {

               // $sheet->appendrow($data);
              $datasheet[$i] = array(
                            $i,
                            $data['date'],
                            $data['id_project'],
                            $data['no_po_customer'],
                            $data['customer_legal_name'],
                            $data['opp_name'],
                            $data['name'],
                            $data['amount_idr']
                        );
              
              $i++;
            }

            $sheet->fromArray($datasheet);
        });

        })->export('xls');
    }

    public function view_lead()
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        // count semua lead
        if($ter != null){
            $lead = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
                ->where('id_territory', $ter)
                ->get();
        }elseif ($ter == null && $div == 'SALES') {
            $lead = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
                ->where('id_territory', $ter)
                ->where('id_company','2')
                ->get();
        } elseif($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $lead = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                ->select('sales_lead_register.lead_id','tb_contact.brand_name', 'sales_lead_register.opp_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('sales_solution_design.nik', $nik)
                ->get();
        }elseif ($div == 'FINANCE') {
            $lead = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
                ->where('result','win')
                ->where('year','2019')
                ->get();
        }elseif ($ter == 'DPG' && $pos == 'ENGINEER MANAGER') {
            $lead = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id','tb_contact.brand_name', 'sales_lead_register.opp_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result','win')
                ->get();
        }elseif ($ter == 'DPG' && $pos == 'ENGINEER STAFF') {
            $lead = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('tb_engineer', 'tb_engineer.lead_id', '=', 'sales_lead_register.lead_id')
                ->select('sales_lead_register.lead_id','tb_contact.brand_name', 'sales_lead_register.opp_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('tb_engineer.nik', $nik)
                ->get();
        }elseif ($div == 'PMO' && $pos == 'STAFF') {
            $lead = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('tb_pmo', 'tb_pmo.lead_id', '=', 'sales_lead_register.lead_id')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
                ->where('tb_pmo.pmo_nik', $nik)
                ->get();
        }elseif ($div == 'PMO' && $pos == 'MANAGER') {
            $lead = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
                ->where('sales_lead_register.status_sho','')
                ->get();
        } else {
            $lead = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
                ->where('year','2019')
                ->get();
        }

        if ($ter != null) {
            $total_ter = DB::table("sales_lead_register")
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->where('id_territory', $ter)
                        ->where('year','2019')
                        ->sum('amount');
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $total_ter = DB::table("sales_lead_register")
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                        ->where('sales_solution_design.nik', $nik)
                        ->where('year','2019')
                        ->sum('amount');
        }else{
            $total_ter = DB::table("sales_lead_register")
                        ->where('year','2019')
                        ->sum('amount');
        }

        if ($ter != null) {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
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

        return view('DemoTemplate/demo_view_lead', compact('lead','leads','notif', 'total_ter', 'notifOpen', 'notifsd', 'notiftp', 'notifClaim'));
    }

    public function view_open()
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        if($ter != null){
            $open = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
                ->where('result', '')
                ->where('id_territory', $ter)
                ->where('year','2019')
                ->get();
        } elseif($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $open = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                ->select('sales_lead_register.lead_id','tb_contact.brand_name', 'sales_lead_register.opp_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('sales_solution_design.nik', $nik)
                ->where('result', '')
                ->where('year','2019')
                ->get();
        }elseif ($div == 'FINANCE') {
            $open = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('tb_id_project', 'tb_id_project.lead_id', '=', 'sales_lead_register.lead_id')
                ->select('sales_lead_register.lead_id','tb_contact.brand_name', 'sales_lead_register.opp_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('sales_lead_register.status_sho','')
                ->where('year','2019')
                ->get();
        } else {
            $open = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
                ->where('result', '')
                ->where('year','2019')
                ->get();
        }

        if($ter != null){
            $sd = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
                ->where('result', 'SD')
                ->where('id_territory', $ter)
                ->where('year','2019')
                ->get();
        } elseif($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $sd = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                ->select('sales_lead_register.lead_id','tb_contact.brand_name', 'sales_lead_register.opp_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('sales_solution_design.nik', $nik)
                ->where('year','2019')
                ->where('result', 'SD')
                ->get();
        }elseif ($div == 'FINANCE') {
            $sd = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('tb_id_project', 'tb_id_project.lead_id', '=', 'sales_lead_register.lead_id')
                ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'sales_lead_register.opp_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('sales_lead_register.lead_id','tb_id_project.lead_id')
                ->where('year','2019')
                ->get();
        } else {
            $sd = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
                ->where('result', 'SD')
                ->where('year','2019')
                ->get();
        }

        if($ter != null){
            $tp = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
                ->where('result', 'TP')
                ->where('id_territory', $ter)
                ->where('year','2019')
                ->get();
        } elseif($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $tp = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                ->select('sales_lead_register.lead_id','tb_contact.brand_name', 'sales_lead_register.opp_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('sales_solution_design.nik', $nik)
                ->where('result', 'TP')
                ->where('year','2019')
                ->get();
        }elseif ($div == 'FINANCE') {
            $tp = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('tb_id_project', 'tb_id_project.lead_id', '=', 'sales_lead_register.lead_id')
                ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'sales_lead_register.opp_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('sales_lead_register.lead_id','tb_id_project.lead_id')
                ->where('year','2019')
                ->get();
        } else {
            $tp = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
                ->where('result', 'TP')
                ->where('year','2019')
                ->get();
        }

        if ($ter != null) {
            $total_ter_open = DB::table("sales_lead_register")
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->where('id_territory', $ter)
                        ->where('result', '')
                        ->where('year','2019')
                        ->sum('amount');
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $total_ter_open = DB::table("sales_lead_register")
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                        ->where('sales_solution_design.nik', $nik)
                        ->where('result', '')
                        ->where('year','2019')
                        ->sum('amount');
        }elseif ($div == 'FINANCE') {
            $total_ter_open = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->join('tb_id_project', 'tb_id_project.lead_id', '=', 'sales_lead_register.lead_id')
                    ->where('sales_lead_register.lead_id','tb_id_project.lead_id')
                    ->where('year','2019')
                    ->sum('amount');
        }else{
            $total_ter_open = DB::table("sales_lead_register")
                        ->where('result', '')
                        ->where('year','2019')
                        ->sum('amount');
        }

        if ($ter != null) {
            $total_ter_sd = DB::table("sales_lead_register")
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->where('id_territory', $ter)
                        ->where('result', 'sd')
                        ->where('year','2019')
                        ->sum('amount');
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $total_ter_sd = DB::table("sales_lead_register")
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                        ->where('sales_solution_design.nik', $nik)
                        ->where('result', 'sd')
                        ->where('year','2019')
                        ->sum('amount');
        }elseif ($div == 'FINANCE') {
            $total_ter_sd = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->join('tb_id_project', 'tb_id_project.lead_id', '=', 'sales_lead_register.lead_id')
                    ->where('sales_lead_register.lead_id','tb_id_project.lead_id')
                    ->where('year','2019')
                    ->sum('amount');
        }else{
            $total_ter_sd = DB::table("sales_lead_register")
                        ->where('result', 'sd')
                        ->where('year','2019')
                        ->sum('amount');
        }

        if ($ter != null) {
            $total_ter_tp = DB::table("sales_lead_register")
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->where('id_territory', $ter)
                        ->where('result', 'tp')
                        ->where('year','2019')
                        ->sum('amount');
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $total_ter_tp = DB::table("sales_lead_register")
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                        ->where('sales_solution_design.nik', $nik)
                        ->where('result', 'tp')
                        ->where('year','2019')
                        ->sum('amount');
        }elseif ($div == 'FINANCE') {
            $total_ter_tp = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->join('tb_id_project', 'tb_id_project.lead_id', '=', 'sales_lead_register.lead_id')
                    ->where('sales_lead_register.lead_id','tb_id_project.lead_id')
                    ->where('year','2019')
                    ->sum('amount');
        }else{
            $total_ter_tp = DB::table("sales_lead_register")
                        ->where('result', 'tp')
                        ->where('year','2019')
                        ->sum('amount');
        }

        if ($ter != null) {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
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

        return view('DemoTemplate/demo_view_open', compact('open','sd','tp','notif','total_ter_open', 'total_ter_sd', 'total_ter_tp', 'notifOpen', 'notifsd', 'notiftp','notifClaim'));
    }

    public function view_win()
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        if($ter != null){
            $win = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
                ->where('result', 'win')
                ->where('id_territory', $ter)
                ->where('year','2019')
                ->get();
        } elseif($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $win = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                ->select('sales_lead_register.lead_id','tb_contact.brand_name', 'sales_lead_register.opp_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('sales_solution_design.nik', $nik)
                ->where('result', 'WIN')
                ->where('year','2019')
                ->get();
        }elseif ($div == 'FINANCE') {
            $win = DB::table('dvg_esm')
                    ->join('users', 'users.nik', '=', 'dvg_esm.personnel')
                    ->select('no','date','users.name', 'type', 'description', 'amount', 'id_project', 'remarks', 'status')
                    ->where('status', 'FINANCE')
                    ->get();
        } else {
            $win = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
                ->where('result', 'win')
                ->where('year','2019')
                ->get();
        }

        if ($ter != null) {
            $total_ter = DB::table("sales_lead_register")
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->where('id_territory', $ter)
                        ->where('result', 'win')
                        ->where('year','2019')
                        ->sum('amount');
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $total_ter = DB::table("sales_lead_register")
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                        ->where('sales_solution_design.nik', $nik)
                        ->where('result', 'win')
                        ->where('year','2019')
                        ->sum('amount');
        }else{
            $total_ter = DB::table("sales_lead_register")
                        ->where('result', 'win')
                        ->where('year','2019')
                        ->sum('amount');
        }

        if ($ter != null) {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
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

        return view('DemoTemplate/demo_view_win', compact('win', 'notif', 'total_ter', 'notifOpen', 'notifsd', 'notiftp','notifClaim'));
    }

    public function view_lose()
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        if($ter != null){
            $lose = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
                ->where('result', 'lose')
                ->where('id_territory', $ter)
                ->where('year','2019')
                ->get();
        }elseif ($ter == null && $div == 'SALES') {
            $lose = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
                ->where('result', 'lose')
                ->where('id_territory', $ter)
                ->where('id_company','2')
                ->where('year','2019')
                ->get();
        } elseif($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $lose = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                ->select('sales_lead_register.lead_id','tb_contact.brand_name', 'sales_lead_register.opp_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('sales_solution_design.nik', $nik)
                ->where('result', 'LOSE')
                ->where('year','2019')
                ->get();
        }elseif ($div == 'FINANCE') {
            $lose = DB::table('dvg_esm')
                    ->join('users', 'users.nik', '=', 'dvg_esm.personnel')
                    ->select('no','date','users.name', 'type', 'description', 'amount', 'id_project', 'remarks', 'status')
                    ->where('status', 'TRANSFER')
                    ->get();
        } else {
            $lose = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
                ->where('result', 'lose')
                ->where('year','2019')
                ->get();
        }

        if ($ter != null) {
            $total_ter = DB::table("sales_lead_register")
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->where('id_territory', $ter)
                        ->where('result', 'lose')
                        ->where('year','2019')
                        ->sum('amount');
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $total_ter = DB::table("sales_lead_register")
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                        ->where('sales_solution_design.nik', $nik)
                        ->where('result', 'lose')
                        ->where('year','2019')
                        ->sum('amount');
        }else{
            $total_ter = DB::table("sales_lead_register")
                        ->where('result', 'lose')
                        ->where('year','2019')
                        ->sum('amount');
        }

        if ($ter != null) {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();
        }elseif ($div == 'SALES' && $pos == 'MANAGER') {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik','lead_id')
            ->where('result','')
            ->orderBy('created_at','desc')
            ->get();
        }elseif ($div == 'SALES' && $pos == 'STAFF') {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik','lead_id')
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

        return view('DemoTemplate/demo_view_lose', compact('lose', 'notif', 'total_ter', 'notifOpen', 'notifsd', 'notiftp','notifClaim'));
    }

    public function claim_pending(){
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        if ($div == 'FINANCE') {
            $datas = DB::table('dvg_esm')
                    ->join('users', 'users.nik', '=', 'dvg_esm.personnel')
                    ->select('no','date','users.name', 'type', 'description', 'amount', 'id_project', 'remarks', 'status')
                    ->where('status', 'FINANCE')
                    ->get();
        }elseif ($div == 'HR') {
            $datas = DB::table('dvg_esm')
                    ->join('users', 'users.nik', '=', 'dvg_esm.personnel')
                    ->select('no','date','users.name', 'type', 'description', 'amount', 'id_project', 'remarks', 'status')
                    ->where('status', 'HRD')
                    ->get();
        }

        if ($ter != null) {
            $total_ter = DB::table("sales_lead_register")
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->where('id_territory', $ter)
                        ->where('result', 'win')
                        ->sum('amount');
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $total_ter = DB::table("sales_lead_register")
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                        ->where('sales_solution_design.nik', $nik)
                        ->where('result', 'win')
                        ->sum('amount');
        }else{
            $total_ter = DB::table("sales_lead_register")
                        ->where('result', 'win')
                        ->sum('amount');
        }

        if ($ter != null) {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
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

        return view('DemoTemplate/claim_finance', compact('datas','notif','notifOpen','notifsd','notiftp', 'notifClaim'));
    }

    public function claim_transfer(){
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

       if ($div == 'FINANCE') {
            $datas = DB::table('dvg_esm')
                    ->join('users', 'users.nik', '=', 'dvg_esm.personnel')
                    ->select('no','date','users.name', 'type', 'description', 'amount', 'id_project', 'remarks', 'status')
                    ->where('status', 'TRANSFER')
                    ->get();
        } 

        if ($ter != null) {
            $total_ter = DB::table("sales_lead_register")
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->where('id_territory', $ter)
                        ->where('result', 'win')
                        ->sum('amount');
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $total_ter = DB::table("sales_lead_register")
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                        ->where('sales_solution_design.nik', $nik)
                        ->where('result', 'win')
                        ->sum('amount');
        }else{
            $total_ter = DB::table("sales_lead_register")
                        ->where('result', 'win')
                        ->sum('amount');
        }

        if ($ter != null) {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
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

        return view('DemoTemplate/claim_finance', compact('datas','notif','notifOpen','notifsd','notiftp', 'notifClaim'));
    }

    public function claim_admin(){
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

       if ($pos == 'ADMIN') {
            $datas = DB::table('dvg_esm')
                    ->join('users', 'users.nik', '=', 'dvg_esm.personnel')
                    ->select('no','date','users.name', 'type', 'description', 'amount', 'id_project', 'remarks', 'status', 'nik_admin')
                    ->where('status', 'ADMIN')
                    ->where('nik_admin', $nik)
                    ->get();
        } 

        if ($ter != null) {
            $total_ter = DB::table("sales_lead_register")
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->where('id_territory', $ter)
                        ->where('result', 'win')
                        ->sum('amount');
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $total_ter = DB::table("sales_lead_register")
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                        ->where('sales_solution_design.nik', $nik)
                        ->where('result', 'win')
                        ->sum('amount');
        }else{
            $total_ter = DB::table("sales_lead_register")
                        ->where('result', 'win')
                        ->sum('amount');
        }

        if ($ter != null) {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
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

        return view('DemoTemplate/claim_finance', compact('datas','notif','notifOpen','notifsd','notiftp', 'notifClaim'));
    }

}