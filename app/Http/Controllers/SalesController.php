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
use Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Carbon\Carbon;
use App\solution_design;
use App\TB_Contact;
use App\Quote;
use App\SalesProject;
use App\PMO;
use App\PMOProgress;
use App\SalesHandover;
use App\ProductTag;
use App\TechnologyTag;
use App\ProductTagRelation;
use App\ServiceTagRelation;
use App\TechnologyTagRelation;
use App\PID;

use App\SalesChangeLog;
use App\Detail_IdProject;
use App\POCustomer;
use App\Mail\MailResult;
use App\Mail\mailPID;
use App\Mail\CreateLeadRegister;
use App\Mail\AssignPresales;
use App\Mail\RaiseTender;
use App\Mail\RequestCustomer;

use Mail;
use App\Notifications\NewLead;
use App\Notifications\PresalesAssign;
use App\Notifications\PresalesReAssign;
use App\Notifications\RaiseToTender;
use App\Notifications\Result;
use Notification;

use App\PIDRequest;
use App\QuoteMSP;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use Google\Auth\CredentialsLoader;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class SalesController extends Controller{
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

        $notifClaim = '';

        $datas = '';

        $rk = '';

        $gp = '';

        $st = '';

        $rz = '';

        $jh = '';

        $leadspre = '';

        $leadsprenow = '';

        $leadsnow = '';

        $leadnow = '';

        $year_dif = '';

        $total_lead = null;

        $total_open = null;

        $total_sd = null;

        $total_tp = null;

        $total_win = null;

        $total_lose = null;



        $users = DB::table('users')
                    ->select('nik','name','id_division')
                    ->where('id_division','PMO')
                    ->get();
        $users = $users->toArray();

        $cek_note = Sales::count('keterangan');

        $dates = Date('Y');
        
        $year = DB::table('sales_lead_register')->select('year')->where('year','!=',NULL)->groupBy('year')->get();

        $lead_id = $request['lead_id_edit'];

        $owner_by_lead = DB::table('sales_lead_register')
                        ->select('nik')
                        ->where('lead_id',$lead_id)
                        ->first();

        $tag_product = ProductTag::get();

        $tag_technology = TechnologyTag::get();

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
                ->where('year',date('Y'))
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
                    ->leftJoin('tb_pid', 'tb_pid.lead_id', '=', 'sales_lead_register.lead_id')
                    // ->leftJoin('tb_product_tag_relation','sales_lead_register.lead_id', '=', 'tb_product_tag_relation.lead_id')
                    // ->leftJoin('tb_product_tag','tb_product_tag_relation.id_product_tag','=','tb_product_tag.id')
                    ->LeftJoin(DB::raw("(
                        SELECT
                            `lead_id`,
                            GROUP_CONCAT(`tb_product_tag`.`name_product`) AS `name_product`
                        FROM
                            `tb_product_tag`
                        INNER JOIN `tb_product_tag_relation` ON `tb_product_tag_relation`.`id_product_tag` = `tb_product_tag`.`id`
                        GROUP BY
                            `lead_id`
                      ) as tb_product_custom"),function($join){
                        $join->on("sales_lead_register.lead_id","=","tb_product_custom.lead_id");
                    })
                    ->LeftJoin(DB::raw("(
                        SELECT
                            `lead_id`,
                            GROUP_CONCAT(`tb_technology_tag`.`name_tech`) AS `name_tech`
                        FROM
                            `tb_technology_tag`
                        INNER JOIN `tb_technology_tag_relation` ON `tb_technology_tag_relation`.`id_tech_tag` = `tb_technology_tag`.`id`
                        GROUP BY
                            `lead_id`
                      ) as tb_technology_custom"),function($join){
                        $join->on("sales_lead_register.lead_id","=","tb_technology_custom.lead_id");
                    })
                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.keterangan', 'sales_lead_register.closing_date', 'sales_lead_register.keterangan','sales_lead_register.deal_price','sales_lead_register.year','users.nik', 'tb_pid.status',DB::raw('GROUP_CONCAT(tb_technology_custom.name_tech) as result_concat'),DB::raw('GROUP_CONCAT(tb_product_custom.name_product) as result_concat_2'))  
                    ->where('result','!=','hmm')
                    ->where('users.id_company',1)
                    ->orderBy('created_at','desc')
                    ->where('users.id_territory',$ter)
                    ->groupBy('sales_lead_register.lead_id')
                    ->groupBy('tb_pid.status')
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

                    // $leads = DB::table('sales_lead_register')
                    //     ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    //     ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    //     ->join('tb_pid', 'tb_pid.lead_id', '=', 'sales_lead_register.lead_id','left')
                    //     ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.keterangan', 'sales_lead_register.closing_date', 'sales_lead_register.keterangan','sales_lead_register.deal_price','sales_lead_register.year','users.nik', 'tb_pid.status')
                    //     ->where('result','!=','hmm')
                    //     ->where('id_territory', $ter)
                    //     ->where('id_company','1')
                    //     ->orderBy('created_at','desc')
                    //     ->orwhere('year',$dates)
                    //     ->whereYear('sales_lead_register.created_at', '=', '2019')
                    //     ->get();

                    $leads = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->leftJoin('tb_pid', 'tb_pid.lead_id', '=', 'sales_lead_register.lead_id')
                    // ->leftJoin('tb_product_tag_relation','sales_lead_register.lead_id', '=', 'tb_product_tag_relation.lead_id')
                    // ->leftJoin('tb_product_tag','tb_product_tag_relation.id_product_tag','=','tb_product_tag.id')
                    ->LeftJoin(DB::raw("(
                        SELECT
                            `lead_id`,
                            GROUP_CONCAT(`tb_product_tag`.`name_product`) AS `name_product`
                        FROM
                            `tb_product_tag`
                        INNER JOIN `tb_product_tag_relation` ON `tb_product_tag_relation`.`id_product_tag` = `tb_product_tag`.`id`
                        GROUP BY
                            `lead_id`
                      ) as tb_product_custom"),function($join){
                        $join->on("sales_lead_register.lead_id","=","tb_product_custom.lead_id");
                    })
                    ->LeftJoin(DB::raw("(
                        SELECT
                            `lead_id`,
                            GROUP_CONCAT(`tb_technology_tag`.`name_tech`) AS `name_tech`
                        FROM
                            `tb_technology_tag`
                        INNER JOIN `tb_technology_tag_relation` ON `tb_technology_tag_relation`.`id_tech_tag` = `tb_technology_tag`.`id`
                        GROUP BY
                            `lead_id`
                      ) as tb_technology_custom"),function($join){
                        $join->on("sales_lead_register.lead_id","=","tb_technology_custom.lead_id");
                    })
                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.keterangan', 'sales_lead_register.closing_date', 'sales_lead_register.keterangan','sales_lead_register.deal_price','sales_lead_register.year','users.nik', 'tb_pid.status',DB::raw('GROUP_CONCAT(tb_technology_custom.name_tech) as result_concat'),DB::raw('GROUP_CONCAT(tb_product_custom.name_product) as result_concat_2'))  
                    ->where('result','!=','hmm')
                    ->where('users.id_company',1)
                    ->orderBy('created_at','desc')
                    ->where('users.id_territory',$ter)
                    ->groupBy('sales_lead_register.lead_id')
                    ->groupBy('tb_pid.status')
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

                $leadsprenow = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                    ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name',
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_solution_design.nik', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.keterangan','sales_lead_register.closing_date','sales_lead_register.deal_price','sales_lead_register.year','tb_contact.id_customer','users.id_territory')
                    ->where('id_company','1')
                    ->where('sales_lead_register.result','!=','hmm')
                    ->get();

                $leadspre = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                    ->join('users as u_presales','u_presales.nik','=','sales_solution_design.nik')
                    ->LeftJoin(DB::raw("(
                        SELECT
                            `lead_id`,
                            GROUP_CONCAT(`tb_product_tag`.`name_product`) AS `name_product`
                        FROM
                            `tb_product_tag`
                        INNER JOIN `tb_product_tag_relation` ON `tb_product_tag_relation`.`id_product_tag` = `tb_product_tag`.`id`
                        GROUP BY
                            `lead_id`
                      ) as tb_product_custom"),function($join){
                        $join->on("sales_solution_design.lead_id","=","tb_product_custom.lead_id");
                    })
                    ->LeftJoin(DB::raw("(
                        SELECT
                            `lead_id`,
                            GROUP_CONCAT(`tb_technology_tag`.`name_tech`) AS `name_tech`
                        FROM
                            `tb_technology_tag`
                        INNER JOIN `tb_technology_tag_relation` ON `tb_technology_tag_relation`.`id_tech_tag` = `tb_technology_tag`.`id`
                        GROUP BY
                            `lead_id`
                      ) as tb_technology_custom"),function($join){
                        $join->on("sales_solution_design.lead_id","=","tb_technology_custom.lead_id");
                    })
                    ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name',
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name','u_presales.name as name_presales', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.keterangan','sales_lead_register.closing_date','sales_lead_register.deal_price','sales_lead_register.year','tb_contact.id_customer','users.id_territory','sales_solution_design.status','users.nik','tb_product_custom.name_product as name_product','tb_technology_custom.name_tech as name_tech')
                    ->whereYear('sales_lead_register.created_at',date('Y'))
                    ->where('sales_lead_register.result','!=','hmm')
                    ->where('users.id_company','1')
                    ->get();

                $leads = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->LeftJoin(DB::raw("(
                        SELECT
                            `lead_id`,
                            GROUP_CONCAT(`tb_product_tag`.`name_product`) AS `name_product`
                        FROM
                            `tb_product_tag`
                        INNER JOIN `tb_product_tag_relation` ON `tb_product_tag_relation`.`id_product_tag` = `tb_product_tag`.`id`
                        GROUP BY
                            `lead_id`
                      ) as tb_product_custom"),function($join){
                        $join->on("sales_lead_register.lead_id","=","tb_product_custom.lead_id");
                    })
                    ->LeftJoin(DB::raw("(
                        SELECT
                            `lead_id`,
                            GROUP_CONCAT(`tb_technology_tag`.`name_tech`) AS `name_tech`
                        FROM
                            `tb_technology_tag`
                        INNER JOIN `tb_technology_tag_relation` ON `tb_technology_tag_relation`.`id_tech_tag` = `tb_technology_tag`.`id`
                        GROUP BY
                            `lead_id`
                      ) as tb_technology_custom"),function($join){
                        $join->on("sales_lead_register.lead_id","=","tb_technology_custom.lead_id");
                    })
                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name',
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.nik','sales_lead_register.status_engineer','sales_lead_register.keterangan','sales_lead_register.year','sales_lead_register.closing_date', 'sales_lead_register.keterangan','users.id_company','sales_lead_register.deal_price','sales_lead_register.year','users.id_territory',DB::raw('GROUP_CONCAT(tb_product_custom.name_product) as result_concat'),DB::raw('GROUP_CONCAT(tb_technology_custom.name_tech) as result_concat_2'))
                    ->orwhere('year',$dates)
                    ->where('users.id_company','1')
                    ->where('result','OPEN')
                    ->groupBy('sales_lead_register.lead_id')
                    ->get();

                $leadsnow = DB::table('sales_lead_register')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->join('sales_solution_design','sales_solution_design.lead_id','=','sales_lead_register.lead_id')
                    ->Leftjoin('users as u_presales', 'u_presales.nik', '=', 'sales_solution_design.nik')
                    ->join('users as u_sales', 'u_sales.nik', '=', 'sales_lead_register.nik')
                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'u_sales.name as name','u_presales.name as name_presales', 'sales_lead_register.result','sales_solution_design.status','sales_lead_register.keterangan','sales_lead_register.year','sales_lead_register.closing_date', 'sales_lead_register.keterangan','u_sales.id_company','sales_lead_register.deal_price','sales_lead_register.year','u_sales.id_territory')
                    ->where('u_sales.id_company','1')
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

                // $total_leads = count($lead);
            
            } else if ($ter == 'DVG' && $pos == 'MANAGER'){
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
                    ->LeftJoin(DB::raw("(
                        SELECT
                            `lead_id`,
                            GROUP_CONCAT(`tb_product_tag`.`name_product`) AS `name_product`
                        FROM
                            `tb_product_tag`
                        INNER JOIN `tb_product_tag_relation` ON `tb_product_tag_relation`.`id_product_tag` = `tb_product_tag`.`id`
                        GROUP BY
                            `lead_id`
                      ) as tb_product_custom"),function($join){
                        $join->on("sales_lead_register.lead_id","=","tb_product_custom.lead_id");
                    })
                    ->LeftJoin(DB::raw("(
                        SELECT
                            `lead_id`,
                            GROUP_CONCAT(`tb_technology_tag`.`name_tech`) AS `name_tech`
                        FROM
                            `tb_technology_tag`
                        INNER JOIN `tb_technology_tag_relation` ON `tb_technology_tag_relation`.`id_tech_tag` = `tb_technology_tag`.`id`
                        GROUP BY
                            `lead_id`
                      ) as tb_technology_custom"),function($join){
                        $join->on("sales_lead_register.lead_id","=","tb_technology_custom.lead_id");
                    })
                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name',
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.nik','sales_lead_register.status_engineer','sales_lead_register.keterangan','sales_lead_register.year','sales_lead_register.closing_date', 'sales_lead_register.keterangan','tb_company.code_company','tb_company.id_company','sales_lead_register.deal_price', 'tb_pid.status','users.id_territory',DB::raw('GROUP_CONCAT(tb_product_custom.name_product) as result_concat'),DB::raw('GROUP_CONCAT(tb_technology_custom.name_tech) as result_concat_2'))
                    ->where('result','!=','hmm')
                    ->whereYear('sales_lead_register.created_at', '=', $dates-1)
                    ->orwhere('year',$dates)
                    ->orderBy('created_at','desc')
                    ->groupBy('sales_lead_register.lead_id')
                    ->groupBy('tb_pid.status')
                    ->get();

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

            }else {
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
                            ->LeftJoin(DB::raw("(
                                SELECT
                                    `lead_id`,
                                    GROUP_CONCAT(`tb_product_tag`.`name_product`) AS `name_product`
                                FROM
                                    `tb_product_tag`
                                INNER JOIN `tb_product_tag_relation` ON `tb_product_tag_relation`.`id_product_tag` = `tb_product_tag`.`id`
                                GROUP BY
                                    `lead_id`
                              ) as tb_product_custom"),function($join){
                                $join->on("sales_lead_register.lead_id","=","tb_product_custom.lead_id");
                            })
                            ->LeftJoin(DB::raw("(
                                SELECT
                                    `lead_id`,
                                    GROUP_CONCAT(`tb_technology_tag`.`name_tech`) AS `name_tech`
                                FROM
                                    `tb_technology_tag`
                                INNER JOIN `tb_technology_tag_relation` ON `tb_technology_tag_relation`.`id_tech_tag` = `tb_technology_tag`.`id`
                                GROUP BY
                                    `lead_id`
                              ) as tb_technology_custom"),function($join){
                                $join->on("sales_lead_register.lead_id","=","tb_technology_custom.lead_id");
                            })
                            ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name',
                            'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.nik','sales_lead_register.status_engineer','sales_lead_register.keterangan','sales_lead_register.year','sales_lead_register.closing_date', 'sales_lead_register.keterangan','tb_company.code_company','tb_company.id_company','sales_lead_register.deal_price',DB::raw('GROUP_CONCAT("tb_product_custom.name_product") as result_concat'),DB::raw('GROUP_CONCAT("tb_technology_custom.name_tech") as result_concat_2'))
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
            ->LeftJoin(DB::raw("(
                SELECT
                    `lead_id`,
                    GROUP_CONCAT(`tb_product_tag`.`name_product`) AS `name_product`
                FROM
                    `tb_product_tag`
                INNER JOIN `tb_product_tag_relation` ON `tb_product_tag_relation`.`id_product_tag` = `tb_product_tag`.`id`
                GROUP BY
                    `lead_id`
              ) as tb_product_custom"),function($join){
                $join->on("sales_lead_register.lead_id","=","tb_product_custom.lead_id");
            })
            ->LeftJoin(DB::raw("(
                SELECT
                    `lead_id`,
                    GROUP_CONCAT(`tb_technology_tag`.`name_tech`) AS `name_tech`
                FROM
                    `tb_technology_tag`
                INNER JOIN `tb_technology_tag_relation` ON `tb_technology_tag_relation`.`id_tech_tag` = `tb_technology_tag`.`id`
                GROUP BY
                    `lead_id`
              ) as tb_technology_custom"),function($join){
                $join->on("sales_lead_register.lead_id","=","tb_technology_custom.lead_id");
            })
            ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name',
            'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.nik','sales_lead_register.status_engineer','sales_lead_register.keterangan','sales_lead_register.year','sales_lead_register.closing_date', 'sales_lead_register.keterangan','tb_company.code_company','tb_company.id_company','sales_lead_register.deal_price', 'tb_pid.status','users.id_territory',DB::raw('GROUP_CONCAT(tb_product_custom.name_product) as result_concat'),DB::raw('GROUP_CONCAT(tb_technology_custom.name_tech) as result_concat_2'))
            ->where('result','!=','hmm')
            ->whereYear('sales_lead_register.created_at', '=', $dates-1)
            ->orwhere('year',$dates)
            ->orderBy('created_at','desc')
            ->groupBy('sales_lead_register.lead_id')
            ->groupBy('tb_pid.status')
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
        // return $leadsnow;
        
        if (Auth::User()->id_division == 'FINANCE') {            
            return view('sales/lead_id_project', compact('lead','leads','notif','notifOpen','notifsd','notiftp','notifClaim'))->with(['initView'=> $this->initMenuBase()]);
        }else{
            return view('sales/sales', compact('lead','leads','notif','notifOpen','notifsd','notiftp','users','owner_by_lead','total_lead','total_open','total_sd','total_tp','total_win','total_lose', 'notifClaim','cek_note','datas','rk','gp','st','rz','jh','leadspre','year','year_now','year_dif','leadsprenow','leadsnow','leadnow','tag_product','tag_technology'))->with(['initView'=> $this->initMenuBase()]);
        }
    }

    public function getBtnFilter(Request $request)
    {
        if($request->id_assign=='company'){
            return array(DB::table('tb_company')
                ->select('code_company')
                ->get(),$request->id_assign);
        } else if ($request->id_assign == 'territory') {
            return array(DB::table('tb_territory')
                ->select('name_territory')
                ->where('name_territory', 'like', 'TERRITORY%')
                ->get(),$request->id_assign);
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

    public function getListProductLead(Request $request)
    {
        $getListProductLead = collect(ProductTagRelation::join('tb_product_tag','tb_product_tag.id','=','tb_product_tag_relation.id_product_tag')->select(DB::raw('`tb_product_tag`.`id`,`tb_product_tag`.`name_product` AS `text`'))->orderBy('name_product','asc')->where('tb_product_tag_relation.lead_id',$request->lead_id)->get());

        return array("results" => $getListProductLead);
    }

    public function getProductTag(Request $request)
    {
        $getListProductLead = collect(ProductTag::select(DB::raw('`id`,`name_product` AS `text`'))->orderBy('name_product','asc')->get());

        return array("results" => $getListProductLead);
    }

    public function getPersonaTags(Request $request)
    {
        $getSales = DB::table('users')->selectRaw('CONCAT(`nik`, "-s") AS `id`,`name` AS `text`')->where('id_division','sales')->where('id_position','!=','admin')->where('status_karyawan','!=','dummy')->orderBy('name','asc')->get();
        $getPresales = DB::table('users')->selectRaw('CONCAT(`nik`, "-p") AS `id`,`name` AS `text`')->where('id_division','TECHNICAL PRESALES')->where('id_company',1)->orderBy('name','asc')->get(); 

        return array(
            collect(["id"=>0,"text"=>'Sales',"children"=>$getSales]),
            // collect(["id"=>1,"text"=>'Presales',"children"=>$getPresales])
        );
    
    }

    public function getAllEmployee(Request $request)
    {
        $getAllEmployee = DB::table('users')->selectRaw('`nik` AS `id`,`name` AS `text`')->where('status_karyawan','!=','dummy')->orderBy('name','asc')->get();
        
        return $getAllEmployee;
    }

    public function getProductTechTag(Request $request)
    {
        // $getListProductLead = collect(ProductTag::select(DB::raw("CONCAT('p',`id`) AS `id`,`name_product` AS 'text'"))->orderBy('name_product','asc')->get());
        // $getListProductLead = collect(ProductTag::select(DB::raw("`id`,`name_product` AS 'text'"))->orderBy('name_product','asc')->get());

        // return ProductTag::select(DB::raw("`id`,`name_product` AS 'text'"))->orderBy('name_product','asc')->get(); 
        $getListProductLead = DB::table('tb_product_tag')->selectRaw('CONCAT("p",`id`) AS `id`,`name_product` AS `text`')->get(); 
        $getListTechTag = DB::table('tb_technology_tag')->selectRaw('CONCAT("t",`id`) AS `id`,`name_tech` AS `text`')->get(); 

        // $getListTechTag = collect(TechnologyTag::select(DB::raw('CONCAT("t",`id`) AS id,`name_tech` AS `text`'))->orderBy('name_tech','asc')->get());

        // return collect(["data" => ["id"=>0,"text"=>'Product',"Product"=>$getListProductLead]]);
        return array(
            collect(["id"=>0,"text"=>'Product',"children"=>$getListProductLead]),
            collect(["id"=>1,"text"=>'Technology',"children"=>$getListTechTag])
        );
    }

    public function getProductTechTagDetail(Request $request){
        $getListProductLead = DB::table('tb_product_tag')->whereNotIn('id',function($query) use ($request) {
            $query->select('id_product_tag')->where('lead_id',$request->lead_id)->from('tb_product_tag_relation');
        })->selectRaw('CONCAT("p",`id`) AS `id`,`name_product` AS `text`')->get(); 

        $getListTechTag = DB::table('tb_technology_tag')->whereNotIn('id',function($query) use ($request) {
            $query->select('id_tech_tag')->where('lead_id',$request->lead_id)->from('tb_technology_tag_relation');
        })->selectRaw('CONCAT("t",`id`) AS `id`,`name_tech` AS `text`')->get(); 

        return array("product_tag"=>$getListProductLead,"technology_tag"=>$getListTechTag);
    }

    public function getListTechTag(Request $request)
    {
        $getListTechTag = collect(TechnologyTagRelation::join('tb_technology_tag','tb_technology_tag.id','=','tb_technology_tag_relation.id_tech_tag')->select(DB::raw('`tb_technology_tag`.`id`,`tb_technology_tag`.`name_tech` AS `text`'))->orderBy('name_tech','asc')->where('tb_technology_tag_relation.lead_id',$request->lead_id)->get());

        return array("results" => $getListTechTag);
    }

    public function getTechTag(Request $request)
    {
        $getListTechTag = collect(TechnologyTag::select(DB::raw('`id`,`name_tech` AS `text`'))->orderBy('name_tech','asc')->get());

        return array("results" => $getListTechTag);
    }

    public function getLoseReason(Request $request){
        return Sales::select('keterangan')->where('lead_id',$request->lead_id)->first();
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

        $pre_cont = '';

        $notifClaim = '';

        if ($div == 'SALES') {
            try {
                $lead_id = Crypt::decrypt($lead_id);
            } catch (DecryptException $e) {
               $lead_id = $lead_id;
            }
        }        

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
        } elseif($div == 'PMO' && $pos == 'MANAGER') {
            $lead = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover')
                ->where('sales_lead_register.result','WIN')
                ->get();
        } elseif($div == 'PMO' && $pos == 'STAFF') {
            $lead = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('tb_pmo','sales_lead_register.lead_id','=','tb_pmo.lead_id')
                ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','tb_pmo.pmo_nik')
                ->where('sales_lead_register.result','WIN')
                ->where('tb_pmo.pmo_nik',$nik)
                ->get();
        } elseif($div == 'FINANCE' && $pos == 'MANAGER') {
            $lead = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.nik')
                ->where('sales_lead_register.result','WIN')
                ->get();
        } elseif($pos == 'ENGINEER MANAGER') {
            $lead = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.nik','sales_lead_register.status_engineer')
                ->where('sales_lead_register.result','WIN')
                ->where('sales_lead_register.status_sho','PMO')
                ->get();
        } elseif($pos == 'ENGINEER STAFF') {
            $lead = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('tb_engineer','sales_lead_register.lead_id','=','tb_engineer.lead_id')
                ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.nik','sales_lead_register.status_engineer')
                ->where('sales_lead_register.result','WIN')
                 ->where('tb_engineer.nik',$nik)
                ->get();
        } else {
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
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.result2','sales_lead_register.result3','sales_lead_register.status_sho','sales_lead_register.status_handover','sales_lead_register.status_engineer', 'sales_lead_register.id_customer','sales_lead_register.closing_date', 'sales_lead_register.deal_price')
                    ->where('lead_id',$lead_id)
                    ->first();

        if ($div == 'SALES' && $ter == null) {

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

        // SELECT
        //     DISTINCT `tb_product_tag`.`name_product`
        // FROM
        //     `tb_product_tag_relation`
        // INNER JOIN `tb_product_tag` ON `tb_product_tag`.`id` = `tb_product_tag_relation`.`id_product_tag`
        // WHERE
        //     `lead_id` = "BULG201003"

        $productTag = DB::table('tb_product_tag_relation')
            ->join('tb_product_tag','tb_product_tag.id','=','tb_product_tag_relation.id_product_tag')
            ->selectRaw('DISTINCT `tb_product_tag`.`name_product`')
            ->where('tb_product_tag_relation.lead_id',$lead_id)
            ->get();

        $technologyTag = DB::table('tb_product_tag_relation')
            ->join('tb_technology_tag','tb_technology_tag.id','=','tb_product_tag_relation.id_technology_tag')
            ->selectRaw('DISTINCT `tb_technology_tag`.`name_tech`')
            ->where('tb_product_tag_relation.lead_id',$lead_id)
            ->get();



        $productTech = collect(["product"=>$productTag,"technology"=>$technologyTag]);

        // $productTech = Sales::Leftjoin('tb_product_tag_relation','tb_product_tag_relation.lead_id','=','sales_lead_register.lead_id')
        //         ->Leftjoin('tb_product_tag','tb_product_tag.id','=','tb_product_tag_relation.id_product_tag')
        //         ->Leftjoin('tb_technology_tag_relation','tb_technology_tag_relation.lead_id','=','sales_lead_register.lead_id')
        //         ->Leftjoin('tb_technology_tag','tb_technology_tag.id','tb_technology_tag_relation.id_tech_tag')
        //         ->select('name_product','name_tech','tb_technology_tag_relation.price as price_tech','tb_product_tag_relation.price as price_product')
        //         ->where('sales_lead_register.lead_id',$lead_id)
        //         ->get();

        // return $productTech;
       

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

        return view('sales/detail_sales',compact('pre_cont','lead','tampilkan','tampilkans','tampilkan_com', 'tampilkana', 'tampilkanc','notif','notifOpen','notifsd','notiftp','tampilkan_progress','engineer_id','current_eng','tampilkan_progress_engineer','engineer_contribute','q_num','sd_id', 'get_quote_number', 'q_num2', 'change_log','notifClaim','tampilkan_po','productTag','technologyTag','productTech'))->with(['initView'=> $this->initMenuBase()]);
    
    }

    public function getProductTechByLead(Request $request){
        $productTag = DB::table('tb_product_tag_relation')->join('tb_product_tag','tb_product_tag.id','=','tb_product_tag_relation.id_product_tag')->selectRaw('CONCAT("p",`tb_product_tag_relation`.`id`) AS `id`,`name_product`,`price`')->where('tb_product_tag_relation.lead_id',$request->lead_id)->orderBy('tb_product_tag_relation.created_at','desc')->get();

        // $technologyTag = DB::table('tb_technology_tag_relation')->join('tb_technology_tag','tb_technology_tag.id','=','tb_technology_tag_relation.id_tech_tag')->selectRaw('CONCAT("t",`tb_technology_tag_relation`.`id`) AS `id`,`name_tech`,`price`')->where('tb_technology_tag_relation.lead_id',$request->lead_id)->orderBy('tb_technology_tag_relation.created_at','desc')->get();

        return $productTech = collect($productTag->toArray());
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
            if(Auth::User()->id_division == 'SALES' || Auth::User()->id_division == 'MSM' && Auth::User()->id_position == 'MANAGER' || Auth::User()->name == "Operations Team"){

//                return Auth::User()->id_division;
                $tambah->nik = Auth::User()->nik;
            } else {
//                return Auth::User()->id_division;
                
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

            if ($request->product != "") {
                $productTag = $request->product;
                foreach ($productTag as $data) {
                    $productRelation = new ProductTagRelation();
                    $productRelation->lead_id = $lead;
                    $productRelation->id_product_tag = $data;
                    $productRelation->save();
                }
            }
            
            if ($request->technology != "") {
                $techTag = $request->technology;
                foreach ($techTag as $data) {
                    $productRelation = new TechnologyTagRelation();
                    $productRelation->lead_id = $lead;
                    $productRelation->id_tech_tag = $data;
                    $productRelation->save();
                }
            }
            

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
                $data = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'sales_lead_register.opp_name','sales_lead_register.amount', 'users.name')
                    ->where('lead_id',$lead)
                    ->first();


                Mail::to($kirim)->send(new CreateLeadRegister($data));

            }

            /*$user_to = User::select('email')
                            ->where('id_position', 'MANAGER')
                            ->where('id_division', 'TECHNICAL PRESALES')->first()->email;

            $sales_sd_filtered = DB::table('sales_solution_design');

            $total = Sales::join('users','users.nik','=','sales_lead_register.nik')
                    ->leftJoinSub($sales_sd_filtered, 'sales_sd_filtered', function ($join) {
                        $join->on('sales_sd_filtered.lead_id','=','sales_lead_register.lead_id');
                    })
                    ->selectRaw("COUNT(IF(`sales_lead_register`.`result` = 'SD',1,IF(`sales_lead_register`.`result` = 'OPEN',1,IF(`sales_lead_register`.`result` = '',1,NULL)))) AS `progress_counted`")
                    ->where('year',date('Y'))
                    ->where('id_company','1')
                    ->where('sales_sd_filtered.nik','=',$user_to)
                    ->orWhereRaw('`sales_sd_filtered`.`nik` IS NULL');          

            $jsonCount = array(
                "to"=>$user_to,
                "total"=> $total->first()->progress_counted
            );

            

            $jsonInsert = array(
                "heximal" => "#7735a3",
                "lead_id" => $lead,
                "opty_name" => $tambah->opp_name,
                "result"=> 'INITIAL',
                "showed"=>"true",
                "status"=>"unread",
                "to"=> $user_to,
                "date_time"=>Carbon::now()->timestamp
            );

            $this->getNotifCountLead($jsonCount);
            $this->getNotifBadgeInsert($jsonInsert);*/

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

    public function update_lead_register(Request $request)
    {
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

            if ($request->product_edit != "") {
                $leadProduct = ProductTagRelation::where('lead_id',$lead_id)->get();
                foreach ($leadProduct as $data) {
                   $delete_product = ProductTagRelation::where('lead_id',$lead_id)->delete();
                }
            
                $productTag = $request->product_edit;
                foreach ($productTag as $data) {
                    $productRelation = new ProductTagRelation();
                    $productRelation->lead_id = $lead_id;
                    $productRelation->id_product_tag = $data;
                    $productRelation->save();
                }
            }else{
                $leadProduct = ProductTagRelation::where('lead_id',$lead_id)->get();
                foreach ($leadProduct as $data) {
                   $delete_product = ProductTagRelation::where('lead_id',$lead_id)->delete();
                }

                // $leadtech = TechnologyTagRelation::where('lead_id',$lead_id)->get();
                // foreach ($leadtech as $data) {
                //    $delete_product = TechnologyTagRelation::where('lead_id',$lead_id)->delete();
                // }
            }

            if ($request->technology_edit != "") {
                $leadtech = TechnologyTagRelation::where('lead_id',$lead_id)->get();
                   foreach ($leadtech as $data) {
                        if ($data != NULL) {
                            $delete_product = TechnologyTagRelation::where('lead_id',$lead_id)->delete();
                        }
                    }

                $techTag = $request->technology_edit;
                foreach ($techTag as $data) {
                    $productRelation = new TechnologyTagRelation();
                    $productRelation->lead_id = $lead_id;
                    $productRelation->id_tech_tag = $data;
                    $productRelation->save();
                }
            }else{
                // $leadProduct = ProductTagRelation::where('lead_id',$lead_id)->get();
                // foreach ($leadProduct as $data) {
                //    $delete_product = ProductTagRelation::where('lead_id',$lead_id)->delete();
                // }

                $leadtech = TechnologyTagRelation::where('lead_id',$lead_id)->get();
                foreach ($leadtech as $data) {
                   $delete_product = TechnologyTagRelation::where('lead_id',$lead_id)->delete();
                }
            }

            return redirect()->back()->with('update','Lead Register Has Been Updated!'); 
    }

    public function assign_to_presales(Request $request)
    {
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

        $data = DB::table('sales_lead_register')
                    ->join('sales_solution_design','sales_solution_design.lead_id','sales_lead_register.lead_id')
                    ->join('users as sales', 'sales.nik', '=', 'sales_lead_register.nik')
                    ->join('users as presales','presales.nik','=','sales_solution_design.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'sales_lead_register.opp_name','sales_lead_register.amount', 'sales.name as sales_name','presales.name as presales_name')
                    ->where('sales_lead_register.lead_id',$tambah->lead_id)
                    ->first();

        $status = 'assign';
        // Notification::send($kirim, new PresalesAssign());
        Mail::to($kirim)->send(new AssignPresales($data,$status));

        //Disabled push notif
        /*$user_to = User::select('email','nik')
                            ->where('id_position', 'MANAGER')
                            ->where('id_division', 'TECHNICAL PRESALES')->first();

        $sales_sd_filtered = DB::table('sales_solution_design');

        if ($kirim->email != $user_to->email) {
            $total_manager = Sales::join('users','users.nik','=','sales_lead_register.nik')
                    ->leftJoinSub($sales_sd_filtered, 'sales_sd_filtered', function ($join) {
                        $join->on('sales_sd_filtered.lead_id','=','sales_lead_register.lead_id');
                    })
                    ->selectRaw("COUNT(IF(`sales_lead_register`.`result` = 'SD',1,IF(`sales_lead_register`.`result` = 'OPEN',1,IF(`sales_lead_register`.`result` = '',1,NULL)))) AS `progress_counted`")
                    ->where('year',date('Y'))
                    ->where('id_company','1')
                    ->where('sales_sd_filtered.nik','=',$user_to->nik)
                    ->orWhereRaw('`sales_sd_filtered`.`nik` IS NULL');  

            $total_staff = Sales::join('users','users.nik','=','sales_lead_register.nik')
                    ->leftJoinSub($sales_sd_filtered, 'sales_sd_filtered', function ($join) {
                        $join->on('sales_sd_filtered.lead_id','=','sales_lead_register.lead_id');
                })
                ->selectRaw("COUNT(IF(`sales_lead_register`.`result` = 'SD',1,IF(`sales_lead_register`.`result` = '',1,NULL))) AS `progress_counted`")
                ->where('year',date('Y'))
                ->where('id_company','1')
                ->where('sales_sd_filtered.nik','=',$nik_assign);

                $i = 0;
                do {
                    if ($i == 0) {
                        $jsonCount = array(
                            "to"=>$kirim->email,
                            "total"=>$total_staff->first()->progress_counted
                        );
                    }

                    if ($i == 1) {
                        $jsonCount = array(
                            "to"=>$user_to->email,
                            "total"=>$total_manager->first()->progress_counted
                        );
                    }
                    $i++;

                    $this->getNotifCountLead($jsonCount);

                } while ($i < 2);
        }else{
            $total_manager = Sales::join('users','users.nik','=','sales_lead_register.nik')
                    ->leftJoinSub($sales_sd_filtered, 'sales_sd_filtered', function ($join) {
                        $join->on('sales_sd_filtered.lead_id','=','sales_lead_register.lead_id');
                    })
                    ->selectRaw("COUNT(IF(`sales_lead_register`.`result` = 'SD',1,IF(`sales_lead_register`.`result` = 'OPEN',1,IF(`sales_lead_register`.`result` = '',1,NULL)))) AS `progress_counted`")
                    ->where('year',date('Y'))
                    ->where('id_company','1')
                    ->where('sales_sd_filtered.nik','=',$user_to->nik)
                    ->orWhereRaw('`sales_sd_filtered`.`nik` IS NULL');          


            $jsonCount = array(
                "to"=>$kirim->email,
                "total"=>$total_manager->first()->progress_counted
            );

            $this->getNotifCountLead($jsonCount);

        }



        $jsonInsert = array(
            "heximal" => "#f2562b",
            "lead_id" => $data->lead_id,
            "opty_name" => "(You've been assigned) " . $data->opp_name,
            "result"=> "OPEN",
            "showed"=>"true",
            "status"=>"unread",
            "to"=> $kirim->email,
            "date_time"=>Carbon::now()->timestamp

        );

        $this->getNotifBadgeInsert($jsonInsert);*/

        return redirect('project');
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

        $data = DB::table('sales_lead_register')
                    ->join('sales_solution_design','sales_solution_design.lead_id','sales_lead_register.lead_id')
                    ->join('users as sales', 'sales.nik', '=', 'sales_lead_register.nik')
                    ->join('users as presales','presales.nik','=','sales_solution_design.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'sales_lead_register.opp_name','sales_lead_register.amount', 'sales.name as sales_name','presales.name as presales_name')
                    ->where('sales_lead_register.lead_id',$lead_id)
                    ->first();
        $status = 'reAssign';
        // Notification::send($kirim, new PresalesReAssign());
        Mail::to($kirim)->send(new AssignPresales($data,$status));

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

    public function raise_to_tender(Request $request)
    {
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
        // Notification::send($kirim, new RaiseToTender());
        $data = DB::table('sales_lead_register')
                    ->join('sales_solution_design','sales_solution_design.lead_id','sales_lead_register.lead_id')
                    ->join('users as sales', 'sales.nik', '=', 'sales_lead_register.nik')
                    ->join('users as presales','presales.nik','=','sales_solution_design.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'sales_lead_register.opp_name','sales_lead_register.amount', 'sales.name as sales_name','presales.name as presales_name','sales.id_territory','sales.email as sales_email','sales.nik','presales.nik as presales_nik','result','presales.email as presales_email')
                    ->where('sales_lead_register.lead_id',$lead_id)
                    ->first();

        
        Mail::to($kirim)->send(new RaiseTender($data));


        //Disabled push notif
        /*$total_sales = TenderProcess::join('sales_lead_register','sales_tender_process.lead_id','=','sales_lead_register.lead_id')
                    ->where('sales_lead_register.nik', $data->nik)
                    ->where('sales_lead_register.result','TP')
                    ->whereYear('sales_tender_process.created_at',date('Y'))
                    ->count('sales_tender_process.lead_id');


        $user_to = User::select('email')
                            ->where('id_position', 'MANAGER')
                            ->where('id_division', 'TECHNICAL PRESALES')->first()->email;

        $sales_sd_filtered = DB::table('sales_solution_design');

  
        $total_manager = Sales::join('users','users.nik','=','sales_lead_register.nik')
                ->leftJoinSub($sales_sd_filtered, 'sales_sd_filtered', function ($join) {
                    $join->on('sales_sd_filtered.lead_id','=','sales_lead_register.lead_id');
                })
                ->selectRaw("COUNT(IF(`sales_lead_register`.`result` = 'SD',1,IF(`sales_lead_register`.`result` = 'OPEN',1,IF(`sales_lead_register`.`result` = '',1,NULL)))) AS `progress_counted`")
                ->where('year',date('Y'))
                ->where('id_company','1')
                ->where('sales_sd_filtered.nik','=',$user_to)
                ->orWhereRaw('`sales_sd_filtered`.`nik` IS NULL');  

        $total_staff = Sales::join('users','users.nik','=','sales_lead_register.nik')
                ->leftJoinSub($sales_sd_filtered, 'sales_sd_filtered', function ($join) {
                    $join->on('sales_sd_filtered.lead_id','=','sales_lead_register.lead_id');
            })
            ->selectRaw("COUNT(IF(`sales_lead_register`.`result` = 'SD',1,IF(`sales_lead_register`.`result` = '',1,NULL))) AS `progress_counted`")
            ->where('year',date('Y'))
            ->where('id_company','1')
            ->where('sales_sd_filtered.nik','=',$data->presales_nik);


        $i = 0;

        if ($data->presales_email != $user_to) {
            do {
                if ($i == 0) {
                    $jsonCount = array(
                        "to"=>$data->presales_email,
                        "total"=>$total_staff->first()->progress_counted
                    );
                }

                if ($i == 1) {
                    $jsonCount = array(
                        "to"    => $data->sales_email,
                        "total" => $total_sales
                    );
                }
                $i++;

                $this->getNotifCountLead($jsonCount);

            } while ($i < 2);
        }else{
            do {
                if ($i == 0) {
                    $jsonCount = array(
                        "to"=>$user_to,
                        "total"=>$total_manager->first()->progress_counted
                    );
                }

                if ($i == 1) {
                    $jsonCount = array(
                        "to"    => $data->sales_email,
                        "total" => $total_sales
                    );
                }
                $i++;


                $this->getNotifCountLead($jsonCount);

            } while ($i < 2);
        }

        $jsonInsert = array(
            "heximal" => "#f7e127",
            "lead_id" => $lead_id,
            "opty_name" => $data->opp_name,
            "result"=> $data->result,
            "showed"=>"true",
            "status"=>"unread",
            "to"=> $data->sales_email,
            "date_time"=>Carbon::now()->timestamp
        );

        $this->getNotifBadgeInsert($jsonInsert);*/


        return redirect()->back();
    }

    public function update_result(Request $request)
    {
        
        $lead_id = $request['lead_id_result'];
        

        if ($request['quote_number_final'] != NULL) {
            $id_quotes = Quote::where('quote_number', $request['quote_number_final'])->first()->id_quote;

            $amount_quo = Quote::where('quote_number', $request['quote_number_final'])->first()->amount;

        }

        // return $id_quotes;

        if ($request['result'] == 'WIN' && $request['deal_price_result'] == null) {
            return back()->with('submit-price','Deal Price Wajib Diisi!');
        } else{

            $update = Sales::where('lead_id', $lead_id)->first();
            $update->result = $request['result'];
            $update->keterangan = $request['keterangan'];
            $update->closing_date = date("Y-m-d");
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

            $data = Sales::join('users','sales_lead_register.nik','=','users.nik')->where('lead_id',$lead_id)->first();

            if($request['result'] == 'WIN'){

                if(isset($request->tagData)){
                    if(!empty($request->tagData["tagProduct"])){
                        foreach ($request->tagData["tagProduct"] as $key => $value) {
                            $store = new ProductTagRelation;
                            $store->lead_id = $lead_id;
                            $store->id_product_tag = $value['tag_product']['productTag'];
                            $store->id_technology_tag = $value['tag_product']['techTag'];
                            $store->price = $value['tag_price'];
                            $store->save(); 
                        }
                    }

                    if(!empty($request->tagData["tagService"])){
                        foreach ($request->tagData["tagService"] as $key => $value) {
                            $store = new ServiceTagRelation;
                            $store->lead_id = $lead_id;
                            $store->id_service_tag = $value['tag_service'];
                            $store->price = $value['tag_price'];
                            $store->save(); 
                        }
                    }
                }


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
                if ($request['request_id'] == "true") {
                    $tambahpid->status = 'requested';

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
                            'users.id_company',
                            'sales_tender_process.quote_number2'
                        )->first();

                    if($pid_info->lead_id == "MSPQUO"){
                        $pid_info->url_create = "/salesproject";
                    }else {
                        $pid_info->url_create = "/salesproject#acceptProjectID?" . $pid_info->id_pid;
                    }

                    $users = User::select('name', 'email')->where('id_division','FINANCE')->where('id_position','MANAGER')->first();
           
                    Mail::to('hellosinergy@gmail.com')->send(new MailResult($users,$pid_info));
                    Mail::to($users->email)->send(new MailResult($users,$pid_info));

                    //Disabled push notif
                    /*$jsonInsert = array(
                        "company"=> $pid_info->id_company,
                        "heximal" => "#246d18",
                        "lead_id" => $lead_id,
                        "opty_name" => $data->opp_name,
                        "result"=> $data->result,
                        "showed"=>"true",
                        "status"=>"unread",
                        "to"=> $users->email,
                        "id_pid"=>$tambahpid->id_pid,
                        "date_time"=>Carbon::now()->timestamp

                    );

                    $jsonCount = array(
                        "manager"=>[
                            "to" => $users->email,
                            "total" => PID::where('status','requested')->count('id_pid'),
                        
                    ]);

                    $this->getNotifBadgeInsert($jsonInsert);
                    $this->getNotifBadgeCountPID($jsonCount);*/

                }

	            

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

            //Disabled push notif
            /*$total = TenderProcess::join('sales_lead_register','sales_tender_process.lead_id','=','sales_lead_register.lead_id')
                    ->where('sales_lead_register.nik', $data->nik)
                    ->where('sales_lead_register.result','TP')
                    ->whereYear('sales_tender_process.created_at',date('Y'))
                    ->count('sales_tender_process.lead_id');

            $jsonCount = array(
                "to" => $data->email,
                "total"=> $total
            );

            $this->getNotifCountLead($jsonCount); */           

        }    
        return "success";
    }

    public function update_next_status(Request $request)
    {
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

            if($request['submit_price'] != $request['submit_price_before']){
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

            if ($request['deal_price'] == '') {
               $update_lead->amount = $request['amount_cek_tp'];
            }else{
               $update_lead->amount = str_replace(',', '', $request['deal_price']); 
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

    public function add_changelog_progress(Request $request) 
    {

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

    public function add_product_technology(Request $request)
    {
        if ($request->paramId == 'p') {
            $store = new ProductTagRelation;
            $store->lead_id         = $request->lead_id;
            $store->id_product_tag  = $request->id;
            $store->price           = $request->price;
            $store->save();

            $product = ProductTag::where('id',$request->id)->first();

            $tambah             = new SalesChangeLog();
            $tambah->lead_id    = $request->lead_id; 
            $tambah->nik        = Auth::User()->nik;
            $tambah->status     = "Added Product - Price ( " . $product->name_product ." - ". number_format($request->price) . " )";
            $tambah->save(); 
        }else{
            $store = new TechnologyTagRelation;
            $store->lead_id         = $request->lead_id;
            $store->id_tech_tag     = $request->id;
            $store->price           = $request->price;
            $store->save();

            $product = TechnologyTag::where('id',$request->id)->first();                

            $tambah             = new SalesChangeLog();
            $tambah->lead_id    = $request->lead_id; 
            $tambah->nik        = Auth::User()->nik; 
            $tambah->status     = "Added Technology ( " . $product->name_tech . " )";
            $tambah->save(); 
        }
        
    }

    public function update_product_technology(Request $request)
    {
        if ($request->paramId == 'p') {
            $update = ProductTagRelation::where('id',$request->id)->first();
            $update->price = $request->price;
            $update->update();

            $product = ProductTagRelation::join('tb_product_tag','tb_product_tag.id','=','tb_product_tag_relation.id_product_tag')
                ->where('tb_product_tag_relation.id',$request->id)->first();

            $tambah             = new SalesChangeLog();
            $tambah->lead_id    = $product->lead_id; 
            $tambah->nik        = Auth::User()->nik;
            $tambah->status     = "Updated Product - Price ( " . $product->name_product ." - ". number_format($product->price) . " )";
            $tambah->save(); 
        }else{
            $update = TechnologyTagRelation::where('id',$request->id)->first();
            $update->price = $request->price;
            $update->update();

            $product = TechnologyTagRelation::join('tb_technology_tag','tb_technology_tag.id','=','tb_technology_tag_relation.id_tech_tag')
                ->where('tb_technology_tag_relation.id',$request->id)->first();

            $tambah             = new SalesChangeLog();
            $tambah->lead_id    = $product->lead_id; 
            $tambah->nik        = Auth::User()->nik; 
            $tambah->status     = "Updated Technology ( " . $product->name_tech . " )";
            $tambah->save(); 
        }
        
    }

    public function delete_product_technology(Request $request)
    {

        if ($request->paramId == 'p') {
            $product = ProductTagRelation::join('tb_product_tag','tb_product_tag.id','=','tb_product_tag_relation.id_product_tag')
                ->where('tb_product_tag_relation.id',$request->id)->first();

            $tambah             = new SalesChangeLog();
            $tambah->lead_id    = $product->lead_id; 
            $tambah->nik        = Auth::User()->nik;
            $tambah->status     = "Deleted Product - Price ( " . $product->name_product ." - ". number_format($product->price) . " )";
            $tambah->save(); 

            $delete = ProductTagRelation::where('id',$request->id)->delete();
        }else{
            $product = TechnologyTagRelation::join('tb_technology_tag','tb_technology_tag.id','=','tb_technology_tag_relation.id_tech_tag')
                ->where('tb_technology_tag_relation.id',$request->id)->first();

            $tambah             = new SalesChangeLog();
            $tambah->lead_id    = $product->lead_id; 
            $tambah->nik        = Auth::User()->nik; 
            $tambah->status     = "Deleted Technology ( " . $product->name_tech . " )";
            $tambah->save(); 

            $delete = TechnologyTagRelation::where('id',$request->id)->delete();
        }

        
        
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

        $notifClaim = null;

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

        $data = TB_Contact::select('code')->get()->pluck('code');  

        if(Auth::User()->id_division == 'SALES'){
            $count_request = TB_Contact::where('status', 'New')->where('nik_request', Auth::User()->nik)->count('id_customer');
        } else {
            $count_request = TB_Contact::where('status', 'New')->count('id_customer');            
        }

        $roles = DB::table('role_user')->join('roles','role_user.role_id','=','roles.id')
                ->select('users.name as name','users.email as email','users.phone as phone')
                ->join('users','role_user.user_id','=','users.nik')
                ->where('status_karyawan', '!=', 'dummy')
                ->where('roles.id',42)
                ->first();

        return view('sales/customer',compact('data', 'notif','notifOpen','notifsd','notiftp','notifClaim', 'count_request','roles'))->with(['initView'=> $this->initMenuBase(),'feature_item'=>$this->RoleDynamic('customer')]);
    }

    public function getCustomerData()
    {
        $getCustomer = TB_Contact::select('code', 'customer_legal_name', 'brand_name', 'id_customer')->where('status', 'Accept')->get();

        return array("data"=>$getCustomer);
    }

    public function getCustomerDataRequest()
    {
        if (Auth::User()->id_division == 'SALES') {
            $getCustomer = TB_Contact::select('code', 'customer_legal_name', 'brand_name', 'status', 'id_customer')->where('status', 'New')->where('nik_request', Auth::User()->nik)->get();
        } else {
            $getCustomer = TB_Contact::join('users', 'users.nik', '=', 'tb_contact.nik_request')->select('code', 'customer_legal_name', 'brand_name', 'status', 'id_customer', 'users.name')->where('status', 'New')->get();
        }

        return array("data"=>$getCustomer);
    }

    public function showCustomerRequest(Request $request)
    {
        $getCustomer = TB_Contact::select('id_customer', 'customer_legal_name', 'code', 'brand_name', 'office_building', 'street_address', 'province', 'postal', 'phone', 'city')
                        ->where('id_customer', $request->id_customer)
                        ->get();

        return array("data"=>$getCustomer);
    }

    public function customer_store(Request $request)
    {
        // $request->validate([
        //     'code_name' => 'required|unique:tb_contact,code',
        // ]);

        $tambah = new TB_Contact();
        $tambah->customer_legal_name = $request['name_contact'];
        $tambah->brand_name = $request['brand_name'];
        $tambah->office_building = nl2br($request['office_building']);
        $tambah->street_address = $request['street_address'];
        $tambah->city = $request['city'];
        $tambah->province = $request['province'];
        $tambah->postal = $request['postal'];
        $tambah->phone = $request['phone'];
        $tambah->status = 'New';
        $tambah->nik_request = Auth::User()->nik;
        $tambah->save();

        $kirim_cc = User::join('role_user','users.nik','=','role_user.user_id')
                ->join('roles','role_user.role_id','=','roles.id')->select('email')->where('roles.name', 'Procurement & Vendor Management')->where('status_karyawan', '!=', 'dummy')->first();

        $kirim = User::join('role_user','users.nik','=','role_user.user_id')
            ->join('roles','role_user.role_id','=','roles.id')->select('email')->where('roles.name', 'Legal & Compliance Management')->where('status_karyawan', '!=', 'dummy')->first();

        $data = TB_Contact::join('users', 'users.nik', '=', 'tb_contact.nik_request')
                    ->select('id_customer', 'customer_legal_name', 'code', 'brand_name', 'office_building', 'street_address', 'province', 'postal', 'tb_contact.phone', 'city', 'tb_contact.created_at', 'name', 'tb_contact.status')
                    ->where('id_customer',$tambah->id_customer)
                    ->first();

        Mail::to($kirim)->cc($kirim_cc)->send(new RequestCustomer('[SIMS-App] Request Customer Data',$data));

        return redirect('customer')->with('success', 'Please Waiting for Accept this Request!');
    }

    public function acceptRequest(Request $request)
    {
        $update = TB_Contact::where('id_customer', $request->id_customer)->first();
        $update->code = $request->code_name;
        $update->status = 'Accept';
        $update->update();

        $data = TB_Contact::join('users', 'users.nik', '=', 'tb_contact.nik_request')
                    ->select('id_customer', 'customer_legal_name', 'code', 'brand_name', 'office_building', 'street_address', 'province', 'postal', 'tb_contact.phone', 'city', 'tb_contact.created_at', 'name', 'tb_contact.status', 'nik_request')
                    ->where('id_customer',$request->id_customer)
                    ->first();

        $kirim = User::select('email')->where('nik', $data->nik_request)->first();

        Mail::to($kirim)->send(new RequestCustomer('[SIMS-App] Request Customer Data Diterima',$data));

        return redirect('customer')->with('success', 'Successfully!');
    }

    public function rejectRequest(Request $request)
    {
        $update = TB_Contact::where('id_customer', $request->id_customer)->first();
        $update->status = 'Reject';
        $update->update();

        $data = TB_Contact::join('users', 'users.nik', '=', 'tb_contact.nik_request')
                    ->select('id_customer', 'customer_legal_name', 'code', 'brand_name', 'office_building', 'street_address', 'province', 'postal', 'tb_contact.phone', 'city', 'tb_contact.created_at', 'name', 'tb_contact.status', 'nik_request')
                    ->where('id_customer',$request->id_customer)
                    ->first();

        $kirim = User::select('email')->where('nik', $data->nik_request)->first();

        Mail::to($kirim)->send(new RequestCustomer('[SIMS-App] Request Customer Data Ditolak',$data));

        return redirect('customer')->with('success', 'Successfully!');
    }

    public function update_customer(Request $request)
    {
        $update = TB_Contact::where('id_customer', $request->id_customer)->first();
        $update->code = $request['code_name'];
        $update->customer_legal_name = $request['name_contact'];
        $update->brand_name = $request['brand_name'];
        $update->office_building = $request['office_building'];
        $update->street_address = $request['street_address'];
        $update->city = $request['city'];
        $update->province = $request['province'];
        $update->postal = $request['postal'];
        $update->phone = $request['phone'];
        $update->update();

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

        $notifClaim = '';

        if ($div == 'SALES' && $pos != 'ADMIN') {
            $salessp = DB::table('tb_id_project')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                    ->join('sales_tender_process','sales_tender_process.lead_id','=','sales_lead_register.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
                    ->select('tb_id_project.customer_name','tb_id_project.id_project','tb_id_project.date','tb_id_project.no_po_customer','sales_lead_register.opp_name','users.name','tb_id_project.amount_idr',
                        DB::raw('IF(tb_id_project.date >= "2022-04-01", (tb_id_project.amount_idr*100)/111, (tb_id_project.amount_idr*10)/11) as amount_idr_before_tax'),
                        'tb_id_project.amount_usd','sales_lead_register.lead_id','sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro','tb_id_project.invoice','progres','name_project','tb_id_project.created_at','customer_legal_name','sales_name','sales_tender_process.quote_number_final','tb_id_project.status')
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
                    ->select('tb_id_project.customer_name','tb_id_project.id_project','tb_id_project.date','tb_id_project.no_po_customer','sales_lead_register.opp_name','users.name','tb_id_project.amount_idr',
                        DB::raw('IF(tb_id_project.date >= "2022-04-01", (tb_id_project.amount_idr*100)/111, (tb_id_project.amount_idr*10)/11) as amount_idr_before_tax'),
                        'tb_id_project.amount_usd','sales_lead_register.lead_id','sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro','tb_id_project.invoice','tb_id_project.status','name_project','tb_id_project.created_at','sales_name','customer_legal_name','users.id_company','tb_quote_msp.quote_number','tb_pid.no_po')
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
                    ->select('tb_id_project.customer_name','tb_id_project.id_project','tb_id_project.date','tb_id_project.no_po_customer','sales_lead_register.opp_name','users.name','tb_id_project.amount_idr',
                        DB::raw('IF(tb_id_project.date >= "2022-04-01", (tb_id_project.amount_idr*100)/111, (tb_id_project.amount_idr*10)/11) as amount_idr_before_tax'),
                        'tb_id_project.amount_usd','sales_lead_register.lead_id','sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro','tb_id_project.invoice','progres','name_project','tb_id_project.created_at','customer_legal_name','sales_name','sales_tender_process.quote_number_final','tb_id_project.status')
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
                    ->select('tb_id_project.customer_name','tb_id_project.id_project','tb_id_project.date','tb_id_project.no_po_customer','sales_lead_register.opp_name','users.name','tb_id_project.amount_idr',
                        DB::raw('IF(tb_id_project.date >= "2022-04-01", (tb_id_project.amount_idr*100)/111, (tb_id_project.amount_idr*10)/11) as amount_idr_before_tax'),
                        'tb_id_project.amount_usd','sales_lead_register.lead_id','sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro','tb_id_project.invoice','tb_id_project.status','name_project','tb_id_project.created_at','sales_name','customer_legal_name','users.id_company','tb_quote_msp.quote_number','tb_pid.no_po')
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
                    ->select('tb_id_project.customer_name','tb_id_project.id_project','tb_id_project.date','tb_id_project.no_po_customer','users.name','tb_id_project.amount_idr','tb_id_project.amount_usd',
                        DB::raw('IF(tb_id_project.date >= "2022-04-01", (tb_id_project.amount_idr*100)/111, (tb_id_project.amount_idr*10)/11) as amount_idr_before_tax'),
                        'sales_lead_register.lead_id','sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro','tb_id_project.invoice','sales_name','progres','name_project','tb_id_project.created_at','customer_legal_name','sales_tender_process.quote_number_final','tb_id_project.status')
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
                    ->select('tb_id_project.customer_name','tb_id_project.id_project','tb_id_project.date','tb_id_project.no_po_customer','sales_lead_register.opp_name','users.name','tb_id_project.amount_idr',
                        DB::raw('IF(tb_id_project.date >= "2022-04-01", (tb_id_project.amount_idr*100)/111, (tb_id_project.amount_idr*10)/11) as amount_idr_before_tax'),
                        'tb_id_project.amount_usd','sales_lead_register.lead_id','sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro','tb_id_project.invoice','tb_id_project.status','name_project','tb_id_project.created_at','sales_name','customer_legal_name','users.id_company','tb_quote_msp.quote_number','tb_pid.no_po')
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
                    ->select('tb_id_project.customer_name','tb_id_project.id_project','tb_id_project.date','tb_id_project.no_po_customer','sales_lead_register.opp_name','users.name','sales_lead_register.lead_id',
                        DB::raw('IF(tb_id_project.date >= "2022-04-01", (tb_id_project.amount_idr*100)/111, (tb_id_project.amount_idr*10)/11) as amount_idr_before_tax'),
                        'sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro','tb_id_project.invoice','sales_name','progres','name_project','tb_id_project.created_at','customer_legal_name','sales_tender_process.quote_number_final','tb_id_project.status')
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
                    ->select('tb_id_project.customer_name','tb_id_project.id_project','tb_id_project.date','tb_id_project.no_po_customer','sales_lead_register.opp_name','users.name','tb_id_project.amount_idr',
                        DB::raw('IF(tb_id_project.date >= "2022-04-01", (tb_id_project.amount_idr*100)/111, (tb_id_project.amount_idr*10)/11) as amount_idr_before_tax'),
                        'tb_id_project.amount_usd','sales_lead_register.lead_id','sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro','tb_id_project.invoice','tb_id_project.status','name_project','tb_id_project.created_at','sales_name','customer_legal_name','users.id_company','tb_quote_msp.quote_number','tb_pid.no_po')
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
                    ->select('tb_id_project.customer_name','tb_id_project.id_project','tb_id_project.date','tb_id_project.no_po_customer','sales_lead_register.opp_name','users.name','sales_lead_register.lead_id',
                        DB::raw('IF(tb_id_project.date >= "2022-04-01", (tb_id_project.amount_idr*100)/111, (tb_id_project.amount_idr*10)/11) as amount_idr_before_tax'),
                        'sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro','tb_id_project.invoice','tb_id_project.status','progres','name_project','tb_id_project.created_at','customer_legal_name','sales_name','sales_tender_process.quote_number_final')
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
                    ->select('tb_id_project.customer_name','tb_id_project.id_project','tb_id_project.date','tb_id_project.no_po_customer','sales_lead_register.opp_name','users.name','tb_id_project.amount_idr',
                        DB::raw('IF(tb_id_project.date >= "2022-04-01", (tb_id_project.amount_idr*100)/111, (tb_id_project.amount_idr*10)/11) as amount_idr_before_tax'),
                        'tb_id_project.amount_usd','sales_lead_register.lead_id','sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro','tb_id_project.invoice','tb_id_project.status','name_project','tb_id_project.created_at','sales_name','customer_legal_name','users.id_company','tb_quote_msp.quote_number','tb_pid.no_po')
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

        $pid_request_lead = PID::join('tb_quote_msp','tb_quote_msp.id_quote','=','tb_pid.no_quo','left')
                        ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_pid.lead_id')
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

        $year_before = SalesProject::select(DB::raw('YEAR(created_at) year'))->groupBy('year')->orderBy('year','DESC')->get();

        $year_now = date('Y');

      return view('sales/sales_project',compact('hitung_msp','salessp','salesmsp','lead_sp','lead_msp','notif','notifOpen','notifsd','notiftp', 'notifClaim','pops','pid_request','pid_request_done','pid_request_lead','pid_request_lead_done','year_now','year_before'))->with(['initView'=> $this->initMenuBase(),'feature_item'=>$this->RoleDynamic('idProject')]);
    }

    public function getPIDIndex(Request $request){
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;
        $company = DB::table('users')->select('id_company')->where('nik', $nik)->first();
        $com = $company->id_company;

        $pops = SalesProject::select('id_project')->orderBy('created_at','desc')->first();

        if ($div == 'SALES' && $pos != 'ADMIN') {
            if ($com == 1) {

                $pid = DB::table('tb_id_project')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                    ->join('sales_tender_process','sales_tender_process.lead_id','=','sales_lead_register.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    // ->join('tb_pmo','tb_pmo.project_id','tb_id_project.id_project')
                    ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
                    ->select('tb_id_project.customer_name','tb_id_project.id_project','tb_id_project.date','tb_id_project.no_po_customer',
                        'sales_lead_register.opp_name','users.name','tb_id_project.amount_idr',
                        DB::raw('IF(tb_id_project.date >= "2022-04-01", (tb_id_project.amount_idr*100)/111, (tb_id_project.amount_idr*10)/11) as amount_idr_before_tax'),
                        'tb_id_project.amount_usd','sales_lead_register.lead_id','sales_lead_register.opp_name','tb_id_project.note',
                        'tb_id_project.id_pro','tb_id_project.invoice','progres','name_project','tb_id_project.created_at','customer_legal_name',
                        'sales_name','sales_tender_process.quote_number_final','tb_id_project.status','users.id_company','kontrak_customer','kontrak_vendor','invoice_customer','invoice_vendor','notes_kontrak_customer','notes_kontrak_vendor','notes_invoice_vendor','notes_invoice_customer')
                    // ->where('sales_lead_register.nik',$nik)
                    ->where('id_territory', $ter)
                    // ->orWhere('tb_id_project.sales_name',Auth::User()->name)
                    ->where('id_company','1')
                    // ->whereYear('tb_id_project.date',date('Y'))
                    ->whereYear('tb_id_project.date',$request->year_filter)
                    ->get();

                $id_project = $pid->pluck('id_project');

                foreach ($id_project as $key => $value) {
                   $projectDetails = DB::table('tb_pmo')
                        ->select('project_type', 'current_phase')
                        ->where('project_id', $value)
                        ->get();

                    $projectTypes = $projectDetails->pluck('project_type')->toArray(); // Convert to array for easier handling
                    $currentPhase = $projectDetails->pluck('current_phase')->first(); // Get the first current_phase

                    if (in_array("implementation", $projectTypes) && in_array("maintenance", $projectTypes)) {
                        $projectType = 'Implementation + Maintenance & Managed Service';
                    } elseif (in_array("implementation", $projectTypes)) {
                        $projectType = 'Implementation';
                    } elseif (in_array("maintenance", $projectTypes)) {
                        $projectType = 'Maintenance & Managed Service';
                    } elseif (in_array("supply_only", $projectTypes)) {
                        $projectType = 'Supply Only';
                    } else {
                        $projectType = 'Unknown'; // Default value if no known project type matches
                    }

                    $pid[$key]->project_type = $projectType;
                    if($projectType == 'Unknown'){
                        $pid[$key]->current_phase = 'Unknown';
                    } else {
                        $pid[$key]->current_phase = $currentPhase;
                    }
                    
                }
                
            }else{

                $pid = DB::table('tb_id_project')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->join('tb_pid','tb_pid.lead_id','=','tb_id_project.lead_id','left')
                    ->join('tb_quote_msp','tb_quote_msp.id_quote','=','tb_pid.no_quo','left')
                    ->join('tb_company','tb_company.id_company','=','users.id_company')
                    ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
                    ->select('tb_id_project.customer_name','tb_id_project.id_project','tb_id_project.date','tb_id_project.no_po_customer','sales_lead_register.opp_name','users.name','tb_id_project.amount_idr',
                        DB::raw('IF(tb_id_project.date >= "2022-04-01", (tb_id_project.amount_idr*100)/111, (tb_id_project.amount_idr*10)/11) as amount_idr_before_tax'),
                        'tb_id_project.amount_usd','sales_lead_register.lead_id','sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro','tb_id_project.invoice','tb_id_project.status','name_project','tb_id_project.created_at','sales_name','customer_legal_name','users.id_company','tb_quote_msp.quote_number','tb_pid.no_po','users.id_company','kontrak_customer','kontrak_vendor','invoice_customer','invoice_vendor','notes_kontrak_customer','notes_kontrak_vendor','notes_invoice_vendor','notes_invoice_customer')
                    ->where('users.id_company','2')
                    ->whereYear('tb_id_project.date',date('Y'))
                    ->where('tb_id_project.status','!=','WO')
                    ->get();
            }

            return array("data" => $pid);  
        
        }
        // elseif ($div == 'TECHNICAL' && $pos == 'MANAGER' && $ter == 'OPERATION') {
        //     if ($request->id == "SIP") {
        //         $pid = DB::table('tb_id_project')
        //             ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
        //             ->join('sales_tender_process','sales_tender_process.lead_id','=','sales_lead_register.lead_id')
        //             ->join('users','users.nik','=','sales_lead_register.nik')
        //             ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
        //             ->select('tb_id_project.customer_name','tb_id_project.id_project','tb_id_project.date','tb_id_project.no_po_customer','users.name','tb_id_project.amount_idr','tb_id_project.amount_usd',DB::raw('IF(`tb_id_project`.`date` >= "2022-04-01", (`tb_id_project`.`amount_idr`*100)/111, (`tb_id_project`.`amount_idr`*10)/11) as `amount_idr_before_tax` '),'sales_lead_register.lead_id','sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro','tb_id_project.invoice','sales_name','progres','name_project','tb_id_project.created_at','customer_legal_name','sales_tender_process.quote_number_final','tb_id_project.status','users.id_company','invoice')
        //             ->where('id_company','1')
        //             ->whereYear('tb_id_project.date',$request->year_filter)
        //             ->get(); 

        //     }else if ($request->id == "MSP") {
                
        //         $pid = DB::table('tb_id_project')
        //             ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
        //             ->join('users','users.nik','=','sales_lead_register.nik')
        //             ->join('tb_pid','tb_pid.lead_id','=','tb_id_project.lead_id','left')
        //             ->join('tb_quote_msp','tb_quote_msp.id_quote','=','tb_pid.no_quo','left')
        //             ->join('tb_company','tb_company.id_company','=','users.id_company')
        //             ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
        //             ->select(
        //                 'tb_id_project.customer_name',
        //                 'tb_id_project.id_project',
        //                 'tb_id_project.date',
        //                 'tb_id_project.no_po_customer',
        //                 'sales_lead_register.opp_name',
        //                 'users.name',
        //                 'tb_id_project.amount_idr',
        //                 DB::raw('IF(`tb_id_project`.`date` >= "2022-04-01", (`tb_id_project`.`amount_idr`*100)/111, (`tb_id_project`.`amount_idr`*10)/11) as `amount_idr_before_tax` '),'tb_id_project.amount_usd','sales_lead_register.lead_id','sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro','tb_id_project.invoice','tb_id_project.status','name_project','tb_id_project.created_at','sales_name','customer_legal_name','users.id_company','tb_quote_msp.quote_number','tb_pid.no_po','users.id_company')
        //             ->where('users.id_company','2')
        //             ->whereYear('tb_id_project.date',$request->year_filter)
        //             ->where('tb_id_project.status','!=','WO')
        //             ->get();
        //     }else{
                
        //         $pid = DB::table('tb_id_project')
        //             ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
        //             ->join('sales_tender_process','sales_tender_process.lead_id','=','sales_lead_register.lead_id')
        //             ->join('users','users.nik','=','sales_lead_register.nik')
        //             ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
        //             ->select('tb_id_project.customer_name','tb_id_project.id_project','tb_id_project.date','tb_id_project.no_po_customer','users.name','tb_id_project.amount_idr','tb_id_project.amount_usd',DB::raw('IF(`tb_id_project`.`date` >= "2022-04-01", (`tb_id_project`.`amount_idr`*100)/111, (`tb_id_project`.`amount_idr`*10)/11) as `amount_idr_before_tax` '),'sales_lead_register.lead_id','sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro','tb_id_project.invoice','sales_name','progres','name_project','tb_id_project.created_at','customer_legal_name','sales_tender_process.quote_number_final','tb_id_project.status','users.id_company')
        //             ->where('id_company','1')
        //             ->whereYear('tb_id_project.date',date('Y'))
        //             ->get();
        //     }  

        //     return array("data" => $pid);  
        
        // }
        elseif ($div == 'FINANCE' || $pos == 'DIRECTOR' || $div == 'TECHNICAL' && $pos == 'MANAGER' && $ter == 'OPERATION' || $div == 'BCD' && $pos == 'MANAGER'){

            if ($request->id == "SIP") {
                
                $pid = DB::table('tb_id_project')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                    ->join('sales_tender_process','sales_tender_process.lead_id','=','sales_lead_register.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    // ->join('tb_pmo','tb_pmo.project_id','tb_id_project.id_project')
                    ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
                    ->select('tb_id_project.customer_name','tb_id_project.id_project','tb_id_project.date','tb_id_project.no_po_customer','users.name','tb_id_project.amount_idr','tb_id_project.amount_usd',
                        DB::raw('IF(tb_id_project.date >= "2022-04-01", (tb_id_project.amount_idr*100)/111, (tb_id_project.amount_idr*10)/11) as amount_idr_before_tax'),
                        'sales_lead_register.lead_id','sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro','tb_id_project.invoice','sales_name','progres','name_project','tb_id_project.created_at','customer_legal_name','sales_tender_process.quote_number_final','tb_id_project.status','users.id_company','invoice','kontrak_customer','kontrak_vendor','invoice_customer','invoice_vendor','notes_kontrak_customer','notes_kontrak_vendor','notes_invoice_vendor','notes_invoice_customer')
                    ->where('id_company','1')
                    // ->whereYear('tb_id_project.date',$request->year_filter)
                    ->whereYear('tb_id_project.date',$request->year_filter)
                    ->get(); 

                $id_project = $pid->pluck('id_project');

                foreach ($id_project as $key => $value) {
                   $projectDetails = DB::table('tb_pmo')
                        ->select('project_type', 'current_phase')
                        ->where('project_id', $value)
                        ->get();

                    $projectTypes = $projectDetails->pluck('project_type')->toArray(); // Convert to array for easier handling
                    $currentPhase = $projectDetails->pluck('current_phase')->first(); // Get the first current_phase

                    if (in_array("implementation", $projectTypes) && in_array("maintenance", $projectTypes)) {
                        $projectType = 'Implementation + Maintenance & Managed Service';
                    } elseif (in_array("implementation", $projectTypes)) {
                        $projectType = 'Implementation';
                    } elseif (in_array("maintenance", $projectTypes)) {
                        $projectType = 'Maintenance & Managed Service';
                    } elseif (in_array("supply_only", $projectTypes)) {
                        $projectType = 'Supply Only';
                    } else {
                        $projectType = 'Unknown'; // Default value if no known project type matches
                    }

                    $pid[$key]->project_type = $projectType;
                    if($projectType == 'Unknown'){
                        $pid[$key]->current_phase = 'Unknown';
                    } else {
                        $pid[$key]->current_phase = $currentPhase;
                    }

                }

            }else if ($request->id == "MSP") {
                $pid = DB::table('tb_id_project')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->join('tb_pid','tb_pid.lead_id','=','tb_id_project.lead_id','left')
                    ->join('tb_quote_msp','tb_quote_msp.id_quote','=','tb_pid.no_quo','left')
                    ->join('tb_company','tb_company.id_company','=','users.id_company')
                    ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
                    ->select(
                        'tb_id_project.customer_name',
                        'tb_id_project.id_project',
                        'tb_id_project.date',
                        'tb_id_project.no_po_customer',
                        'sales_lead_register.opp_name',
                        'users.name',
                        'tb_id_project.amount_idr',
                        DB::raw('IF(tb_id_project.date >= "2022-04-01", (tb_id_project.amount_idr*100)/111, (tb_id_project.amount_idr*10)/11) as amount_idr_before_tax'),
                        'tb_id_project.amount_usd','sales_lead_register.lead_id','sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro','tb_id_project.invoice','tb_id_project.status','name_project','tb_id_project.created_at','sales_name','customer_legal_name','users.id_company','tb_quote_msp.quote_number','tb_pid.no_po','users.id_company')
                    ->where('users.id_company','2')
                    ->whereYear('tb_id_project.date',$request->year_filter)
                    ->where('tb_id_project.status','!=','WO')
                    ->get();
            }else{

                $pid = DB::table('tb_id_project')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                    ->join('sales_tender_process','sales_tender_process.lead_id','=','sales_lead_register.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    // ->join('tb_pmo','tb_pmo.project_id','tb_id_project.id_project')
                    ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
                    ->select('tb_id_project.customer_name','tb_id_project.id_project','tb_id_project.date','tb_id_project.no_po_customer','users.name','tb_id_project.amount_idr','tb_id_project.amount_usd',
                        DB::raw('IF(tb_id_project.date >= "2022-04-01", (tb_id_project.amount_idr*100)/111, (tb_id_project.amount_idr*10)/11) as amount_idr_before_tax'),
                        'sales_lead_register.lead_id','sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro','tb_id_project.invoice','sales_name','progres','name_project','tb_id_project.created_at','customer_legal_name','sales_tender_process.quote_number_final','tb_id_project.status','users.id_company','kontrak_customer','kontrak_vendor','invoice_customer','invoice_vendor','notes_kontrak_customer','notes_kontrak_vendor','notes_invoice_vendor','notes_invoice_customer')
                    ->where('id_company','1')
                    // ->whereYear('tb_id_project.date',date('Y'))
                    ->whereYear('tb_id_project.date',$request->year_filter)
                    ->get();

                $id_project = $pid->pluck('id_project');

                foreach ($id_project as $key => $value) {
                   $projectDetails = DB::table('tb_pmo')
                        ->select('project_type', 'current_phase')
                        ->where('project_id', $value)
                        ->get();

                    $projectTypes = $projectDetails->pluck('project_type')->toArray(); // Convert to array for easier handling
                    $currentPhase = $projectDetails->pluck('current_phase')->first(); // Get the first current_phase

                    if (in_array("implementation", $projectTypes) && in_array("maintenance", $projectTypes)) {
                        $projectType = 'Implementation + Maintenance & Managed Service';
                    } elseif (in_array("implementation", $projectTypes)) {
                        $projectType = 'Implementation';
                    } elseif (in_array("maintenance", $projectTypes)) {
                        $projectType = 'Maintenance & Managed Service';
                    } elseif (in_array("supply_only", $projectTypes)) {
                        $projectType = 'Supply Only';
                    } else {
                        $projectType = 'Unknown'; // Default value if no known project type matches
                    }

                    $pid[$key]->project_type = $projectType;
                    if($projectType == 'Unknown'){
                        $pid[$key]->current_phase = 'Unknown';
                    } else {
                        $pid[$key]->current_phase = $currentPhase;
                    }

                }

                // return $pid->distinct();
            }  

            return array("data" => $pid);   
        
        }else{

            if ($com == 1) {

                $pid = DB::table('tb_id_project')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                    ->join('sales_tender_process','sales_tender_process.lead_id','=','sales_lead_register.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
                    ->select('tb_id_project.customer_name','tb_id_project.id_project','tb_id_project.date','tb_id_project.no_po_customer','sales_lead_register.opp_name','users.name','sales_lead_register.lead_id',
                        DB::raw('IF(tb_id_project.date >= "2022-04-01", (tb_id_project.amount_idr*100)/111, (tb_id_project.amount_idr*10)/11) as amount_idr_before_tax'),
                        'sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro','tb_id_project.invoice','tb_id_project.status','progres','name_project','tb_id_project.created_at','customer_legal_name','sales_name','sales_tender_process.quote_number_final','users.id_company','tb_id_project.amount_idr','tb_id_project.sales_name','kontrak_customer','kontrak_vendor','invoice_customer','invoice_vendor','notes_kontrak_customer','notes_kontrak_vendor','notes_invoice_vendor','notes_invoice_customer')
                    ->where('id_company','1')
                    // ->whereYear('tb_id_project.date',date('Y'))
                    ->whereYear('tb_id_project.date',$request->year_filter)
                    ->get();

                $id_project = $pid->pluck('id_project');

                foreach ($id_project as $key => $value) {
                   $projectDetails = DB::table('tb_pmo')
                        ->select('project_type', 'current_phase')
                        ->where('project_id', $value)
                        ->get();

                    $projectTypes = $projectDetails->pluck('project_type')->toArray(); // Convert to array for easier handling
                    $currentPhase = $projectDetails->pluck('current_phase')->first(); // Get the first current_phase

                    if (in_array("implementation", $projectTypes) && in_array("maintenance", $projectTypes)) {
                        $projectType = 'Implementation + Maintenance & Managed Service';
                    } elseif (in_array("implementation", $projectTypes)) {
                        $projectType = 'Implementation';
                    } elseif (in_array("maintenance", $projectTypes)) {
                        $projectType = 'Maintenance & Managed Service';
                    } elseif (in_array("supply_only", $projectTypes)) {
                        $projectType = 'Supply Only';
                    } else {
                        $projectType = 'Unknown'; // Default value if no known project type matches
                    }

                    $pid[$key]->project_type = $projectType;
                    if($projectType == 'Unknown'){
                        $pid[$key]->current_phase = 'Unknown';
                    } else {
                        $pid[$key]->current_phase = $currentPhase;
                    }

                }

            }else{

                $pid = DB::table('tb_id_project')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->join('tb_pid','tb_pid.lead_id','=','tb_id_project.lead_id','left')
                    ->join('tb_quote_msp','tb_quote_msp.id_quote','=','tb_pid.no_quo','left')
                    ->join('tb_company','tb_company.id_company','=','users.id_company')
                    ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
                    ->select('tb_id_project.customer_name','tb_id_project.id_project','tb_id_project.date','tb_id_project.no_po_customer','sales_lead_register.opp_name','users.name','tb_id_project.amount_idr',
                        DB::raw('IF(tb_id_project.date >= "2022-04-01", (tb_id_project.amount_idr*100)/111, (tb_id_project.amount_idr*10)/11) as amount_idr_before_tax'),
                        'tb_id_project.amount_usd','sales_lead_register.lead_id','sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro','tb_id_project.invoice','tb_id_project.status','name_project','tb_id_project.created_at','sales_name','customer_legal_name','users.id_company','tb_quote_msp.quote_number','tb_pid.no_po','users.id_company','kontrak_customer','kontrak_vendor','invoice_customer','invoice_vendor','notes_kontrak_customer','notes_kontrak_vendor','notes_invoice_vendor','notes_invoice_customer')
                    ->where('users.id_company','2')
                    ->whereYear('tb_id_project.date',date('Y'))
                    ->where('tb_id_project.status','!=','WO')
                    ->get();

            }
            
            return array("data" => $pid);  
        
        }
    
    }

    public function getFilterYearPID(Request $req){
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;
        $company = DB::table('users')->select('id_company')->where('nik', $nik)->first();
        $com = $company->id_company;

        $pops = SalesProject::select('id_project')->orderBy('created_at','desc')->first();

        if ($div == 'SALES' && $pos != 'ADMIN') {
            if ($com == 1) {

                $pid = DB::table('tb_id_project')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                    ->join('sales_tender_process','sales_tender_process.lead_id','=','sales_lead_register.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
                    ->select('tb_id_project.customer_name','tb_id_project.id_project','tb_id_project.date','tb_id_project.no_po_customer','sales_lead_register.opp_name','users.name','tb_id_project.amount_idr',
                        DB::raw('IF(tb_id_project.date >= "2022-04-01", (tb_id_project.amount_idr*100)/111, (tb_id_project.amount_idr*10)/11) as amount_idr_before_tax'),
                        'tb_id_project.amount_usd','sales_lead_register.lead_id','sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro','tb_id_project.invoice','progres','name_project','tb_id_project.created_at','customer_legal_name','sales_name','sales_tender_process.quote_number_final','tb_id_project.status','users.id_company')
                    // ->where('sales_lead_register.nik',$nik)
                    ->where('id_territory', $ter)
                    // ->orWhere('tb_id_project.sales_name',Auth::User()->name)
                    ->where('users.id_company',$req->id)
                    ->whereYear('tb_id_project.date',$req->filterYear)
                    ->get();

                $id_project = $pid->pluck('id_project');

                foreach ($id_project as $key => $value) {
                   $projectDetails = DB::table('tb_pmo')
                        ->select('project_type', 'current_phase')
                        ->where('project_id', $value)
                        ->get();

                    $projectTypes = $projectDetails->pluck('project_type')->toArray(); // Convert to array for easier handling
                    $currentPhase = $projectDetails->pluck('current_phase')->first(); // Get the first current_phase

                    if (in_array("implementation", $projectTypes) && in_array("maintenance", $projectTypes)) {
                        $projectType = 'Implementation + Maintenance & Managed Service';
                    } elseif (in_array("implementation", $projectTypes)) {
                        $projectType = 'Implementation';
                    } elseif (in_array("maintenance", $projectTypes)) {
                        $projectType = 'Maintenance & Managed Service';
                    } elseif (in_array("supply_only", $projectTypes)) {
                        $projectType = 'Supply Only';
                    } else {
                        $projectType = 'Unknown'; // Default value if no known project type matches
                    }

                    $pid[$key]->project_type = $projectType;
                    if($projectType == 'Unknown'){
                        $pid[$key]->current_phase = 'Unknown';
                    } else {
                        $pid[$key]->current_phase = $currentPhase;
                    }

                }
                
            }else{

                $pid = DB::table('tb_id_project')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->join('tb_pid','tb_pid.lead_id','=','tb_id_project.lead_id','left')
                    ->join('tb_quote_msp','tb_quote_msp.id_quote','=','tb_pid.no_quo','left')
                    ->join('tb_company','tb_company.id_company','=','users.id_company')
                    ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
                    ->select('tb_id_project.customer_name','tb_id_project.id_project','tb_id_project.date','tb_id_project.no_po_customer','sales_lead_register.opp_name','users.name','tb_id_project.amount_idr',
                        DB::raw('IF(tb_id_project.date >= "2022-04-01", (tb_id_project.amount_idr*100)/111, (tb_id_project.amount_idr*10)/11) as amount_idr_before_tax'),
                        'tb_id_project.amount_usd','sales_lead_register.lead_id','sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro','tb_id_project.invoice','tb_id_project.status','name_project','tb_id_project.created_at','sales_name','customer_legal_name','users.id_company','tb_quote_msp.quote_number','tb_pid.no_po','users.id_company')
                    ->where('tb_company.id_company',$req->id)
                    ->whereYear('tb_id_project.date',$req->filterYear)
                    ->where('tb_id_project.status','!=','WO')
                    ->get();
            }

            return array("data" => $pid);  
        
        }elseif ($div == 'TECHNICAL' && $pos == 'MANAGER' && $ter == 'OPERATION' || $pos == 'DIRECTOR') {
            if ($com == 1) {

                 $pid = DB::table('tb_id_project')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                    ->join('sales_tender_process','sales_tender_process.lead_id','=','sales_lead_register.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    // ->join('tb_pmo','tb_pmo.project_id','tb_id_project.id_project')
                    ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
                    ->select('tb_id_project.customer_name','tb_id_project.id_project','tb_id_project.date','tb_id_project.no_po_customer','sales_lead_register.opp_name','users.name','tb_id_project.amount_idr',
                        DB::raw('IF(tb_id_project.date >= "2022-04-01", (tb_id_project.amount_idr*100)/111, (tb_id_project.amount_idr*10)/11) as amount_idr_before_tax'),
                        'tb_id_project.amount_usd','sales_lead_register.lead_id','sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro','tb_id_project.invoice','progres','name_project','tb_id_project.created_at','customer_legal_name','sales_name','sales_tender_process.quote_number_final','tb_id_project.status','users.id_company')
                    ->where('users.id_company',$req->id)
                    ->whereYear('tb_id_project.date',$req->filterYear)
                    ->get(); 

                $id_project = $pid->pluck('id_project');

                foreach ($id_project as $key => $value) {
                   $projectDetails = DB::table('tb_pmo')
                        ->select('project_type', 'current_phase')
                        ->where('project_id', $value)
                        ->get();

                    $projectTypes = $projectDetails->pluck('project_type')->toArray(); // Convert to array for easier handling
                    $currentPhase = $projectDetails->pluck('current_phase')->first(); // Get the first current_phase

                    if (in_array("implementation", $projectTypes) && in_array("maintenance", $projectTypes)) {
                        $projectType = 'Implementation + Maintenance & Managed Service';
                    } elseif (in_array("implementation", $projectTypes)) {
                        $projectType = 'Implementation';
                    } elseif (in_array("maintenance", $projectTypes)) {
                        $projectType = 'Maintenance & Managed Service';
                    } elseif (in_array("supply_only", $projectTypes)) {
                        $projectType = 'Supply Only';
                    } else {
                        $projectType = 'Unknown'; // Default value if no known project type matches
                    }

                    $pid[$key]->project_type = $projectType;
                    if($projectType == 'Unknown'){
                        $pid[$key]->current_phase = 'Unknown';
                    } else {
                        $pid[$key]->current_phase = $currentPhase;
                    }

                }

            }else{

                $pid = DB::table('tb_id_project')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->join('tb_pid','tb_pid.lead_id','=','tb_id_project.lead_id','left')
                    ->join('tb_quote_msp','tb_quote_msp.id_quote','=','tb_pid.no_quo','left')
                    ->join('tb_company','tb_company.id_company','=','users.id_company')
                    ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
                    ->select('tb_id_project.customer_name','tb_id_project.id_project','tb_id_project.date','tb_id_project.no_po_customer','sales_lead_register.opp_name','users.name','tb_id_project.amount_idr',
                        DB::raw('IF(tb_id_project.date >= "2022-04-01", (tb_id_project.amount_idr*100)/111, (tb_id_project.amount_idr*10)/11) as amount_idr_before_tax'),
                        'tb_id_project.amount_usd','sales_lead_register.lead_id','sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro','tb_id_project.invoice','tb_id_project.status','name_project','tb_id_project.created_at','sales_name','customer_legal_name','users.id_company','tb_quote_msp.quote_number','tb_pid.no_po','users.id_company')
                    ->where('tb_company.id_company',$req->id)
                    ->whereYear('tb_id_project.date',$req->filterYear)
                    ->where('tb_id_project.status','!=','WO')
                    ->get();

            }

            return array("data" => $pid);  
        
        }elseif ($div == 'FINANCE'){
            if ($req->id == 1) {
                $pid = DB::table('tb_id_project')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                    ->join('sales_tender_process','sales_tender_process.lead_id','=','sales_lead_register.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    // ->join('tb_pmo','tb_pmo.project_id','tb_id_project.id_project')
                    ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
                    ->select('tb_id_project.customer_name','tb_id_project.id_project','tb_id_project.date','tb_id_project.no_po_customer','users.name','tb_id_project.amount_idr','tb_id_project.amount_usd',
                        DB::raw('IF(tb_id_project.date >= "2022-04-01", (tb_id_project.amount_idr*100)/111, (tb_id_project.amount_idr*10)/11) as amount_idr_before_tax'),
                        'sales_lead_register.lead_id','sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro','tb_id_project.invoice','sales_name','progres','name_project','tb_id_project.created_at','customer_legal_name','sales_tender_process.quote_number_final','tb_id_project.status','users.id_company','invoice')
                    ->where('users.id_company',$req->id)
                    ->whereYear('tb_id_project.date',$req->filterYear)
                    ->get(); 

                $id_project = $pid->pluck('id_project');

                foreach ($id_project as $key => $value) {
                   $projectDetails = DB::table('tb_pmo')
                        ->select('project_type', 'current_phase')
                        ->where('project_id', $value)
                        ->get();

                    $projectTypes = $projectDetails->pluck('project_type')->toArray(); // Convert to array for easier handling
                    $currentPhase = $projectDetails->pluck('current_phase')->first(); // Get the first current_phase

                    if (in_array("implementation", $projectTypes) && in_array("maintenance", $projectTypes)) {
                        $projectType = 'Implementation + Maintenance & Managed Service';
                    } elseif (in_array("implementation", $projectTypes)) {
                        $projectType = 'Implementation';
                    } elseif (in_array("maintenance", $projectTypes)) {
                        $projectType = 'Maintenance & Managed Service';
                    } elseif (in_array("supply_only", $projectTypes)) {
                        $projectType = 'Supply Only';
                    } else {
                        $projectType = 'Unknown'; // Default value if no known project type matches
                    }

                    $pid[$key]->project_type = $projectType;
                    if($projectType == 'Unknown'){
                        $pid[$key]->current_phase = 'Unknown';
                    } else {
                        $pid[$key]->current_phase = $currentPhase;
                    }

                }

            }else if ($req->id == 2) {
                $pid = DB::table('tb_id_project')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->join('tb_pid','tb_pid.lead_id','=','tb_id_project.lead_id','left')
                    ->join('tb_quote_msp','tb_quote_msp.id_quote','=','tb_pid.no_quo','left')
                    ->join('tb_company','tb_company.id_company','=','users.id_company')
                    ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
                    ->select(
                        'tb_id_project.customer_name',
                        'tb_id_project.id_project',
                        'tb_id_project.date',
                        'tb_id_project.no_po_customer',
                        'sales_lead_register.opp_name',
                        'users.name',
                        'tb_id_project.amount_idr',
                        DB::raw('IF(tb_id_project.date >= "2022-04-01", (tb_id_project.amount_idr*100)/111, (tb_id_project.amount_idr*10)/11) as amount_idr_before_tax'),'tb_id_project.amount_usd','sales_lead_register.lead_id','sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro','tb_id_project.invoice','tb_id_project.status','name_project','tb_id_project.created_at','sales_name','customer_legal_name','users.id_company','tb_quote_msp.quote_number','tb_pid.no_po','users.id_company')
                    ->where('tb_company.id_company',$req->id)
                    ->whereYear('tb_id_project.date',$req->filterYear)
                    ->where('tb_id_project.status','!=','WO')
                    ->get();
            }else{
                $pid = DB::table('tb_id_project')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                    ->join('sales_tender_process','sales_tender_process.lead_id','=','sales_lead_register.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->join('tb_pmo','tb_pmo.project_id','tb_id_project.id_project')
                    ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
                    ->select('tb_id_project.customer_name','tb_id_project.id_project','tb_id_project.date','tb_id_project.no_po_customer','users.name','tb_id_project.amount_idr','tb_id_project.amount_usd',
                        DB::raw('IF(tb_id_project.date >= "2022-04-01", (tb_id_project.amount_idr*100)/111, (tb_id_project.amount_idr*10)/11) as amount_idr_before_tax'),
                        'sales_lead_register.lead_id','sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro','tb_id_project.invoice','sales_name','progres','name_project','tb_id_project.created_at','customer_legal_name','sales_tender_process.quote_number_final','tb_id_project.status','users.id_company','current_phase')
                    ->where('users.id_company','1')
                    ->whereYear('tb_id_project.date',$req->filterYear)
                    ->get();
            }  

            return array("data" => $pid);   
        
        }else{
            if ($com == 1) {
                $pid = DB::table('tb_id_project')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                    ->join('sales_tender_process','sales_tender_process.lead_id','=','sales_lead_register.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    // ->join('tb_pmo','tb_pmo.project_id','tb_id_project.id_project')
                    ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
                    ->select('tb_id_project.customer_name','tb_id_project.id_project','tb_id_project.date','tb_id_project.no_po_customer','sales_lead_register.opp_name','users.name','sales_lead_register.lead_id',
                        DB::raw('IF(tb_id_project.date >= "2022-04-01", (tb_id_project.amount_idr*100)/111, (tb_id_project.amount_idr*10)/11) as amount_idr_before_tax'),
                        'sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro','tb_id_project.invoice','tb_id_project.status','progres','name_project','tb_id_project.created_at','customer_legal_name','sales_name','sales_tender_process.quote_number_final','users.id_company','tb_id_project.amount_idr','tb_id_project.sales_name')
                    ->where('users.id_company',$req->id)
                    ->whereYear('tb_id_project.date',$req->filterYear)
                    ->get();

                $id_project = $pid->pluck('id_project');

                foreach ($id_project as $key => $value) {
                   $projectDetails = DB::table('tb_pmo')
                        ->select('project_type', 'current_phase')
                        ->where('project_id', $value)
                        ->get();

                    $projectTypes = $projectDetails->pluck('project_type')->toArray(); // Convert to array for easier handling
                    $currentPhase = $projectDetails->pluck('current_phase')->first(); // Get the first current_phase

                    if (in_array("implementation", $projectTypes) && in_array("maintenance", $projectTypes)) {
                        $projectType = 'Implementation + Maintenance & Managed Service';
                    } elseif (in_array("implementation", $projectTypes)) {
                        $projectType = 'Implementation';
                    } elseif (in_array("maintenance", $projectTypes)) {
                        $projectType = 'Maintenance & Managed Service';
                    } elseif (in_array("supply_only", $projectTypes)) {
                        $projectType = 'Supply Only';
                    } else {
                        $projectType = 'Unknown'; // Default value if no known project type matches
                    }

                    $pid[$key]->project_type = $projectType;
                    if($projectType == 'Unknown'){
                        $pid[$key]->current_phase = 'Unknown';
                    } else {
                        $pid[$key]->current_phase = $currentPhase;
                    }

                }
            }else{

                $pid = DB::table('tb_id_project')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->join('tb_pid','tb_pid.lead_id','=','tb_id_project.lead_id','left')
                    ->join('tb_quote_msp','tb_quote_msp.id_quote','=','tb_pid.no_quo','left')
                    ->join('tb_company','tb_company.id_company','=','users.id_company')
                    ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
                    ->select('tb_id_project.customer_name','tb_id_project.id_project','tb_id_project.date','tb_id_project.no_po_customer','sales_lead_register.opp_name','users.name','tb_id_project.amount_idr',
                        DB::raw('IF(tb_id_project.date >= "2022-04-01", (tb_id_project.amount_idr*100)/111, (tb_id_project.amount_idr*10)/11) as amount_idr_before_tax'),
                        'tb_id_project.amount_usd','sales_lead_register.lead_id','sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro','tb_id_project.invoice','tb_id_project.status','name_project','tb_id_project.created_at','sales_name','customer_legal_name','users.id_company','tb_quote_msp.quote_number','tb_pid.no_po','users.id_company')
                    ->where('tb_company.id_company',$req->id)
                    ->whereYear('tb_id_project.date',$req->filterYear)
                    ->where('tb_id_project.status','!=','WO')
                    ->get();

            }
            
            return array("data" => $pid);  
        
        }
    }

    public function getShowPIDReq(Request $request){
        if($request->id == "request"){

                $pid = PID::join('tb_quote_msp','tb_quote_msp.id_quote','=','tb_pid.no_quo','left')->join('sales_lead_register','sales_lead_register.lead_id','=','tb_pid.lead_id')
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
                        'tb_pid.id_pid',
                        'tb_pid.status')
                        ->where('tb_pid.status','requested')
                        ->get(); 

        }else if($request->id == "history"){

                $pid = PID::join('tb_quote_msp','tb_quote_msp.id_quote','=','tb_pid.no_quo','left')->join('sales_lead_register','sales_lead_register.lead_id','=','tb_pid.lead_id')
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
                        'tb_pid.id_pid',
                        'tb_pid.status')
                        ->where('tb_pid.status','done')
                        ->get();  

        }else{

                $pid = PID::join('tb_quote_msp','tb_quote_msp.id_quote','=','tb_pid.no_quo','left')->join('sales_lead_register','sales_lead_register.lead_id','=','tb_pid.lead_id')
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
                        'tb_pid.id_pid',
                        'tb_pid.status')
                        ->where('tb_pid.status','requested')
                        ->get(); 
        }

        return array("data" => $pid);
    }

    public function getEditPID(Request $request){

        $pid = DB::table('tb_id_project')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                    ->join('sales_tender_process','sales_tender_process.lead_id','=','sales_lead_register.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
                    ->select('tb_id_project.customer_name','tb_id_project.id_project','tb_id_project.date','tb_id_project.no_po_customer','sales_lead_register.opp_name','users.name','sales_lead_register.lead_id',
                        DB::raw('IF(tb_id_project.date >= "2022-04-01", (tb_id_project.amount_idr*100)/111, (tb_id_project.amount_idr*10)/11) as amount_idr_before_tax'),
                        'sales_lead_register.opp_name','tb_id_project.note','tb_id_project.id_pro','tb_id_project.invoice','tb_id_project.status','progres','name_project','tb_id_project.created_at','customer_legal_name','sales_name','sales_tender_process.quote_number_final','users.id_company','tb_id_project.amount_idr','tb_id_project.sales_name','kontrak_customer','kontrak_vendor','invoice_customer','invoice_vendor','notes_kontrak_customer','notes_kontrak_vendor','notes_invoice_vendor','notes_invoice_customer')
                    ->where('tb_id_project.id_pro',$request->id_pro)
                    ->get();

        return array($pid);

    }


    public function getAcceptProjectID(Request $request){
        $po_number = PID::join('tb_quote_msp','tb_quote_msp.id_quote','=','tb_pid.no_quo','left')->join('sales_lead_register', 'sales_lead_register.lead_id', '=', 'tb_pid.lead_id')->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')->join('users', 'users.nik', '=', 'sales_lead_register.nik')->join('sales_tender_process', 'sales_lead_register.lead_id', '=', 'sales_tender_process.lead_id')->select('sales_lead_register.opp_name', 'name', 'no_po', 'tb_pid.date_po', 'amount_pid', 'quote_number_final', 'sales_lead_register.lead_id', 'tb_contact.code','tb_quote_msp.quote_number','tb_quote_msp.date','tb_quote_msp.amount')->where('id_pid',$request->id)
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
        $edate = strtotime($request['date']); 
        $edate = date("Y-m-d",$edate);
        $month = date("n",strtotime($request['date']));

        $array_bln = array(1 => "I" ,"II","III","IV","V","VI","VII","VIII","IX","X","XI","XII");
        $bln = $array_bln[$month];
        // return $bln;

        $sales = $request['sales'];
        $contact = $request['customer_name'];
        $name = substr($contact, 0,4);
        $company = DB::table('tb_company')
                    ->join('users','users.id_company','=','tb_company.id_company')
                    ->select('code_company')
                    ->where('nik', $sales)
                    ->first();

        // if (substr($request['date'], 0,4) != $year) {
        //     return redirect()->back()->with('gagal', 'Tanggal Yang Kamu Input Tidak Valid!');
        // }

        $hitung_sip = DB::table('tb_id_project')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->select('id_project','users.id_company')
                    ->orderBy('id_project','desc')
                    ->whereYear('tb_id_project.date',substr($edate, 0,4))
                    ->where('users.id_company','1')
                    ->get();

        $hitung_msp = DB::table('tb_id_project')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->select('id_project','users.id_company')
                    ->orderBy('id_project','desc')
                    ->whereYear('tb_id_project.date',substr($edate, 0,4))
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

        if ($cek_sip->id_company == '1') {
            if ($counts > 0) {
              $increment = $counts;
            }else{
              $increment = 0;
            }

            $nomor = $increment+1;

            if($nomor <= 9){
                $nomor = '00' . $nomor;
            }elseif($nomor > 9 && $nomor <= 99){
                $nomor = '0' . $nomor;
            }

            $project = $nomor.'/'.$name .'/'. 'SIP/' . $bln .'/'. substr($edate, 0,4);

            $lead_id = $request['customer_name'];

            $cek_result = Sales::select('result','pid','id_customer')->where('lead_id',$contact)->first();

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
            } else {
              $pid_info->no_quote = "-";
            }

            $users = User::join('sales_lead_register', 'sales_lead_register.nik', '=', 'users.nik')
                ->join('tb_id_project', 'tb_id_project.lead_id', '=', 'sales_lead_register.lead_id')
                ->select('users.email', 'users.name', 'tb_id_project.lead_id')
                ->where('tb_id_project.id_pro',$tambah->id_pro)
                ->first();

            $getPmManager = User::join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('email','users.name')->where('roles.name','Project Management Office Manager')->first();

            Mail::to($users->email)->send(new mailPID($pid_info,$users,'getPmManager','sales'));
            Mail::to($getPmManager->email)->send(new mailPID($pid_info,$users,$getPmManager,'pm'));
            // Mail::to("hellosinergy@gmail.com")->send(new mailPID($pid_info,$users));

        }else if($cek_sip->id_company == '2'){

            if ($countss > 0) {
              $increment = $countss;
            }else{
              $increment = 0;
            }
            $nomor = $increment+1;

            if($nomor <= 9){
                $nomor = '00' . $nomor;
            }elseif($nomor > 9 && $nomor <= 99){
                $nomor = '0' . $nomor;
            }

            $name_msp = $request['id_cus'];

            $project = $nomor.'/'. $name_msp .'/'. 'MSP/' .$bln .'/'. substr($edate, 0,4);

            $lead_id = $request['customer_name'];

            $cek_result = Sales::select('result','pid','id_customer')->where('lead_id',$contact)->first();

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
            $update->no_po  = $request['p_order'];
            $update->status = 'done';
            $update->save();

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

            $users = User::join('sales_lead_register', 'sales_lead_register.nik', '=', 'users.nik')
                ->join('tb_id_project', 'tb_id_project.lead_id', '=', 'sales_lead_register.lead_id')
                ->select('users.email', 'users.name', 'tb_id_project.lead_id')
                ->where('tb_id_project.id_pro',$tambah->id_pro)
                ->first();

            Mail::to($users->email)->send(new mailPID($pid_info,$users,'getPmManager','sales'));
            Mail::to("hellosinergy@gmail.com")->send(new mailPID($pid_info,$users,'getPmManager','sales'));
        
        }

        //Disabled push notif
        /*$finance = User::where('id_division','FINANCE')->where('id_position','MANAGER')->first();

        $jsonCount = array(
            "manager" => [
                "to" => $finance->email,
                "total" => PID::where('status','requested')->count('id_pid')
        ]);
            
        $this->getNotifBadgeCountPID($jsonCount);*/

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
                'sales_lead_register.result',
                'users.email',
                'sales_tender_process.quote_number2',
                'users.id_company'
            )->first();

        if($pid_info->lead_id == "MSPQUO"){
            $pid_info->url_create = "/salesproject";
        }else {
            $pid_info->url_create = "/salesproject#acceptProjectID?" . $pid_info->id_pid;
        }

        $users = User::select('name','email')->where('id_division','FINANCE')->where('id_position','MANAGER')->first();
        
        // Mail::to('faiqoh@sinergy.co.id')->send(new MailResult($users,$pid_info));
        // Mail::to('agastya@sinergy.co.id')->send(new MailResult($users,$pid_info));
        // return $users
        Mail::to($users->email)->send(new MailResult($users,$pid_info));

        //Disabled push notif
        /*$total = PID::where('status','requested')->count('id_pid');

        $jsonCount = array(
            "manager"=>[
                "to"=> $users->email,
                "total"=>$total
            ]
        );

        $jsonInsert = array(
            "company"=> $pid_info->id_company,
            "heximal" => "#246d18",
            "lead_id" => $pid_info->lead_id,
            "opty_name" => $pid_info->opp_name,
            "result"=> $pid_info->result,
            "showed"=>"true",
            "status"=>"unread",
            "to"=>  $users->email,
            "id_pid" => $pid_info->id_pid,
            "date_time"=>Carbon::now()->timestamp

        );

        $this->getNotifBadgeInsert($jsonInsert);
        $this->getNotifBadgeCountPID($jsonCount);*/

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

        $roles = DB::table('role_user')->join('roles','role_user.role_id','=','roles.id')
                ->select('roles.name')
                // ->join('users','role_user.user_id','=','users.nik')
                ->where('role_user.user_id',Auth::User()->nik)
                ->first();

        $edate = strtotime($request['date_edit']); 
        $edate = date("Y-m-d",$edate);
        $month = date("n",strtotime($request['date_edit']));
        $year = substr($edate,0,4);

        $array_bln = array(1 => "I" ,"II","III","IV","V","VI","VII","VIII","IX","X","XI","XII");
        $bln = $array_bln[$month];

        $id = SalesProject::where('id_project',$id_project)->first()->id_project;

        $getnumber =  explode("/",$id)[0];
        $getcus =  explode("/",$id)[1];
        $getcom =  explode("/",$id)[2];

        $cek_lead_before = Salesproject::select('lead_id')->where('id_project',$id_project)->first()->lead_id;

        $cek_com = Sales::join('users','users.nik','=','sales_lead_register.nik')
                    ->select('id_company')
                    ->where('lead_id',$cek_lead_before)
                    ->first()->id_company;

        $id_year_before = SalesProject::join('sales_lead_register', 'sales_lead_register.lead_id', '=', 'tb_id_project.lead_id')
                            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                            ->select('id_project')
                            ->whereYear('date', $year)->orderBy('tb_id_project.id_pro', 'desc')->where('users.id_company', $cek_com)->first()->id_project;

        $get_number_before = explode("/",$id_year_before)[0] + 1;

        $update = SalesProject::where('id_project', $id_project)->first();
        if ($roles->name == 'Legal & Compliance Management') {
            $update->kontrak_vendor = $request['statusKontrakVendor'];
            $update->notes_kontrak_vendor = nl2br($request['notesKontrakVendor']);
            $update->kontrak_customer = $request['statusKontrakCustomer'];
            $update->notes_kontrak_customer = nl2br($request['notesKontrakCustomer']);
            // $update->invoice_customer = $request['statusInvoiceCustomer'];
            // $update->notes_invoice_customer = nl2br($request['notesInvoiceCustomer']);
            // $update->invoice_vendor = $request['statusInvoiceVendor'];
            // $update->notes_invoice_vendor = nl2br($request['notesInvoiceVendor']);
        } else {
            if ($year == date('Y')) {
               $update->id_project = $getnumber . '/' . $getcus . '/' . $getcom . '/' . $bln . '/' . $year;
            } else {
                if ($cek_com = '1') {
                    $update->id_project = $get_number_before . '/' . $getcus . '/SIP/' . $bln . '/' . $year;
                } else {
                    $update->id_project = $get_number_before . '/' . $getcus . '/MSP/' . $bln . '/' . $year;
                }
            }
            $update->no_po_customer = $request['po_customer_edit'];
            $update->name_project = $request['name_project_edit'];
            if (Auth::User()->id_position == 'MANAGER') {
                $amunt = str_replace(',', '', $request['amount_edit']);
                // $update->amount_idr = $amunt.(int)"00";
                $update->amount_idr = $amunt;
                $update->amount_usd = $request['kurs_edit'];
            }else{

            }
            $update->note = $request['note_edit'];
            $update->invoice = $request['invoice'];
            $update->date = $edate;
            $update->kontrak_vendor = $request['statusKontrakVendor'];
            $update->notes_kontrak_vendor = $request['notesKontrakVendor'];
            $update->kontrak_customer = $request['statusKontrakCustomer'];
            $update->notes_kontrak_customer = $request['notesKontrakCustomer'];
            // $update->invoice_customer = $request['statusInvoiceCustomer'];
            // $update->notes_invoice_customer = $request['notesInvoiceCustomer'];
            // $update->invoice_vendor = $request['statusInvoiceVendor'];
            // $update->notes_invoice_vendor = $request['notesInvoiceVendor'];
        }
        
        
        $update->update();//

        $lead_id = Salesproject::select('lead_id')->where('id_project',$update->id_project)->first()->lead_id;

        $cek_company = Salesproject::join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')->join('users','users.nik','=','sales_lead_register.nik')->join('tb_company','tb_company.id_company','=','users.id_company')->where('id_project',$update->id_project)->first()->id_company;

        if ($lead_id != 'MSPQUO' && $lead_id != 'MSPPO' && $lead_id != 'SIPPO' && $lead_id != 'SIPQUO') {
            // $update2 = PID::where('lead_id',$lead_id)->first();
            // $update2->no_po = $request['po_customer_edit'];
            // $update2->update();

            if ($cek_company == '1') {
                return redirect('salesproject#tab_1');
            }else{
                return redirect('salesproject#tab_2');
            }
            
        }else{
            return redirect('salesproject');
        }
    }

    // public function destroy_sp(Request $request)
    // {
    //     $lead_id = $request['id_pro'];
    //     $id_pro = $request['lead_id'];

    //     $cek_pid = Salesproject::select('lead_id')->where('lead_id',$lead_id)->count('lead_id');

    //     $update = Sales::where('lead_id', $lead_id)->first();
    //     $update->pid = $cek_pid - 1;
    //     $update->update();

    //     $hapus = Salesproject::find($id_pro);
    //     $hapus->delete();

    //     return redirect()->back()->with('error', 'Deleted PID Successfully!');
    // }

    public function destroy_sp($id_pro)
    {
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

    public function export(Request $request) {
        $spreadsheet = new Spreadsheet();

        $prSheet = new Worksheet($spreadsheet,'Data ID Project');
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
        $titleStyle['font']['bold'] = true;

        $sheet->getStyle('A1:H1')->applyFromArray($titleStyle);
        $sheet->setCellValue('A1','Data ID Project SIP');

        $headerStyle = $normalStyle;
        $headerStyle['font']['bold'] = true;
        $sheet->getStyle('A2:H2')->applyFromArray($headerStyle);

        $dataPID = Salesproject::join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
            ->join('users','users.nik','=','sales_lead_register.nik')
            ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
            ->whereYear('tb_id_project.date',$request->year)
            ->orderBy('tb_id_project.id_project','asc')
            ->where('id_company','1');
            

        if (Auth::User()->id_division == 'SALES') {
            $dataPID = $dataPID->where('users.id_territory',Auth::User()->id_territory);
        }

        if (Auth::User()->id_division == 'FINANCE' && Auth::User()->id_position == 'MANAGER' || Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_division == 'SALES' && Auth::User()->id_position != 'ADMIN') {
            $headerContent = ["No", "Date", "ID Project", "No. PO customer", "Customer Name", "Project Name",  "Amount IDR", "Sales"];
            $dataPID = $dataPID->select(
                'tb_id_project.date',
                'tb_id_project.id_project',
                'tb_id_project.no_po_customer',
                'customer_legal_name',
                'name_project',
                'tb_id_project.amount_idr',
                'users.name'
            )->get();
            
        } else {
            $headerContent = ["No", "Date", "ID Project", "No. PO customer", "Customer Name", "Project Name", "Sales"];
            $dataPID = $dataPID->select(
                'tb_id_project.date',
                'tb_id_project.id_project',
                'tb_id_project.no_po_customer',
                'customer_legal_name',
                'name_project',
                'users.name'
            )->get();
        }

        $sheet->fromArray($headerContent,NULL,'A2');

        $dataPID->map(function($item,$key) use ($sheet){
            $item->date = date_format(date_create($item->date),'d-M-Y');
            $sheet->fromArray(array_merge([$key + 1],array_values($item->toArray())),NULL,'A' . ($key + 3));
        });

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);

        $fileName = 'ID PROJECT SIP ' . date('Y') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        
        $writer = new Xlsx($spreadsheet);
        ob_end_clean();
        return $writer->save("php://output");
    }

    public function export_msp(Request $request)
    {

        $spreadsheet = new Spreadsheet();

        $prSheet = new Worksheet($spreadsheet,'ID PROJECT MSP');
        $spreadsheet->addSheet($prSheet);
        $spreadsheet->removeSheetByIndex(0);
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->mergeCells('A1:I1');
        $normalStyle = [
            'font' => [
                'name' => 'Calibri',
                'size' => 11
            ],
        ];

        $titleStyle = $normalStyle;
        $titleStyle['alignment'] = ['horizontal' => Alignment::HORIZONTAL_CENTER];
        $titleStyle['font']['bold'] = true;

        $sheet->getStyle('A1:I1')->applyFromArray($titleStyle);
        $sheet->setCellValue('A1','ID PROJECT MSP');

        $headerStyle = $normalStyle;
        $headerStyle['font']['bold'] = true;
        $sheet->getStyle('A2:I2')->applyFromArray($headerStyle);;

        $headerContent = ["No", "Date", "ID Project", "No. PO customer","No. Quote","Customer Name", "Project Name", "Amount IDR", "Sales"];
        $sheet->fromArray($headerContent,NULL,'A2');

        $year = date('Y');


        $datas = SalesProject::join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')
                ->join('users','users.nik','=','sales_lead_register.nik')
                ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
                ->LeftJoin('tb_pid','tb_pid.lead_id','=','tb_id_project.lead_id')
                ->LeftJoin('tb_quote_msp','tb_quote_msp.id_quote','=','tb_pid.no_quo')
                ->select(
                    'tb_id_project.date',
                    'tb_id_project.id_project',
                    'tb_id_project.no_po_customer',
                    'tb_quote_msp.quote_number',
                    'tb_id_project.customer_name',
                    'tb_id_project.name_project',
                    'tb_id_project.amount_idr',
                    'sales_name',
                    'users.name',
                    'tb_pid.no_po',
                    'tb_contact.customer_legal_name',
                    'sales_lead_register.opp_name',
                    'sales_lead_register.lead_id'
                )
                ->where('id_company','2')
                ->whereYear('tb_id_project.date',$request->year)
                ->where('tb_id_project.status','!=','WO')
                ->orderBy('tb_id_project.id_project','asc')
                ->get();

        foreach ($datas as $key => $eachLead) {
            $eachLead->amount_idr = number_format($eachLead->amount_idr,2,",",".");
            if($eachLead->lead_id == 'MSPQUO' || $eachLead->lead_id == 'MSPPO'){
                // $eachLead->no_po_customer = $eachLead->no_po; 
                $eachLead->quote_number = "-";
                // $eachLead->customer_name = "-";

            } else {
                $eachLead->no_po_customer = $eachLead->no_po;
                $eachLead->customer_name = $eachLead->customer_legal_name;
                $eachLead->name_project = $eachLead->opp_name;
                $eachLead->sales_name = $eachLead->name;

                $eachLead->no_po = "";
                $eachLead->customer_legal_name = "";
                $eachLead->opp_name = "";
                $eachLead->name = "";
                $eachLead->lead_id = "";
                
                // $eachLead->no_po_customer = $eachLead->no_po;

            }
            $eachLead->date = date("d-m-Y",strtotime($eachLead->date));
            // $eachLead->result = ($eachLead->result == "" ? "OPEN" : $eachLead->result);
            $sheet->fromArray(array_merge([$key + 1],array_values($eachLead->toArray())),NULL,'A' . ($key + 3));
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

        $fileName = 'ID PROJECT MSP ' . date('Y-m-d') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        
        $writer = new Xlsx($spreadsheet);
        ob_end_clean();
        return $writer->save("php://output");


            // foreach ($datas as $data) {
            //   if ($data->lead_id == 'MSPQUO' || $data->lead_id == 'MSPPO') {
            //       $datasheet[$i] = array(
            //         $i,
            //         date_format(date_create($data['date']),'d-M-Y'),
            //         $data['id_project'],
            //         $data['no_po_customer'],
            //         ' - ',
            //         $data['customer_name'],
            //         $data['name_project'],
            //         $data['amount_idr'],
            //         $data['sales_name']
            //         );
                  
            //       $i++;
            //     }else{
            //         $datasheet[$i] = array(
            //             $i,
            //             date_format(date_create($data['date']),'d-M-Y'),
            //             $data['id_project'],
            //             $data['no_po'],
            //             $data['quote_number'],
            //             $data['customer_legal_name'],
            //             $data['opp_name'],
            //             $data['amount_idr'],
            //             $data['name']
                    
            //         );             
            //     $i++;
            //     }
            // }
    }

    //Disabled push notif
/*    public function getNotifBadgeCountPID($json){
        $url = env('FIREBASE_DATABASEURL')."/notif/ID_Project.json?auth=".env('REALTIME_FIREBASE_AUTH');
        try {
            $client = new Client();
            $client->request('PATCH', $url, [
                'headers' => [
                    'Content-Type' => 'application/json'
                ],
                'json' => $json
            ]);
        } catch (RequestException $e){
            $error['error'] = $e->getMessage();
        }
    }

    public function getNotifCountLead($json){
        $url = env('FIREBASE_DATABASEURL')."/notif/Lead_Register.json?auth=".env('REALTIME_FIREBASE_AUTH');
        try {
            $client = new Client();
            $client->request('POST', $url, [
                'headers' => [
                    'Content-Type' => 'application/json'
                ],
                'json' => $json
            ]);
        } catch (RequestException $e){
            $error['error'] = $e->getMessage();
        }
    }

    public function getNotifBadgeInsert($json){
        $url = env('FIREBASE_DATABASEURL')."/notif/web-notif.json?auth=".env('REALTIME_FIREBASE_AUTH');
        try {
            $client = new Client();
            $client->request('POST', $url, [
                'headers' => [
                    'Content-Type' => 'application/json'
                ],
                'json' => $json
            ]);
        } catch (RequestException $e){
            $error['error'] = $e->getMessage();
        }
    }*/
}