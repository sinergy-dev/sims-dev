<?php

namespace App\Http\Controllers;

use App\Mail\MailQuotation;
use App\Quote;
use App\QuoteActivity;
use App\QuoteConfig;
use App\QuoteConfigProduct;
use App\QuoteProduct;
use Google_Client;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;
use PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;
use Excel;
use App\TB_Contact;
use App\SalesProject;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class QuoteController extends Controller
{
//	public function __construct()
//    {
//        $this->middleware('auth');
//    }
    
	public function index()
	{
        $nik = Auth::User()->nik;
        $role = $this->cekRole($nik);
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        $pops = Quote::select('quote_number')->orderBy('created_at','desc')->first();

        $pops2 = Quote::select('quote_number')->where('status_backdate', 'F')->orderBy('updated_at', 'desc')->first();

        $tahun = date("Y");

		$datas = DB::table('tb_quote')
                        ->leftJoin('users', 'users.nik', '=', 'tb_quote.nik')
                        ->join('tb_contact', 'tb_contact.id_customer', '=', 'tb_quote.id_customer', 'left')
                        ->select('id_quote','quote_number','position','type_of_letter','date','to','attention','title','project','tb_quote.status', 'description', 'from', 'division', 'project_id','note', 'status_backdate', 'tb_quote.nik', 'name', 'month', 'project_type', 'tb_contact.id_customer', 'customer_legal_name')
                        ->orderBy('tb_quote.created_at', 'desc')
                        ->get();

        $backdate_num = Quote::select('quote_number','id_quote')->where('status_backdate', 'T')->whereYear('created_at', $tahun)->orderBy('created_at','asc')->get();

        $count = DB::table('tb_quote')
                    ->where('status_backdate', 'T')
                    ->get();

        $counts = count($count);

        $customer = TB_Contact::select('customer_legal_name', 'id_customer')->where('tb_contact.status', 'Accept')->get();

        $status_quote = Quote::select('status_backdate')->where('status_backdate', '!=', 'T')->groupBy('status_backdate')->get();

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
        } else {
            $notifClaim = DB::table('dvg_esm')
                            ->select('nik_admin', 'personnel', 'type')
                            ->where('status', 'FINANCE')
                            ->get();
        }

        $lead = DB::table('sales_lead_register')->whereRaw("(`result` = 'OPEN' OR `result` = 'SD' OR `result` = 'TP')");

        if ($role->name == 'VP Solutions & Partnership Management'){
            $leadId = $lead->where('nik', '1061184050');
        } else if($role->name == 'Technology Alliance Solutions' || $role->name == 'Product Development Specialist Manager'){
            $leadId = $lead->where('nik', '1110492070');
        }else {
            $leadId = $lead->where('nik', Auth::user()->nik);
        }
        $leadId = $leadId->select('lead_id')->get();

        $sidebar_collapse = true;

        $year_before = Quote::select(DB::raw('YEAR(created_at) year'))->orderBy('year','desc')->groupBy('year')->get();

        $pid = SalesProject::join('sales_lead_register', 'sales_lead_register.lead_id', '=', 'tb_id_project.lead_id')->join('users', 'users.nik', '=', 'sales_lead_register.nik')->select('id_project')->where('id_company', '1')->get();

        return view('quote/quote',compact('notif','datas','notifOpen','notifsd','notiftp', 'notifClaim', 'counts', 'count','pops', 'pops2', 'backdate_num', 'sidebar_collapse', 'customer', 'status_quote','tahun','year_before', 'pid','leadId'))->with(['initView'=> $this->initMenuBase()]);
	}

	public function quoteList()
    {
        $nik = Auth::User()->nik;
        $role = $this->cekRole($nik);
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        $pops = Quote::select('quote_number')->orderBy('created_at','desc')->first();

        $pops2 = Quote::select('quote_number')->where('status_backdate', 'F')->orderBy('updated_at', 'desc')->first();

        $tahun = date("Y");

        $datas = DB::table('tb_quote')
            ->leftJoin('users', 'users.nik', '=', 'tb_quote.nik')
            ->join('tb_contact', 'tb_contact.id_customer', '=', 'tb_quote.id_customer', 'left')
            ->select('id_quote','quote_number','position','type_of_letter','date','to','attention','title','project','tb_quote.status', 'description', 'from', 'division', 'project_id','note', 'status_backdate', 'tb_quote.nik', 'name', 'month', 'project_type', 'tb_contact.id_customer', 'customer_legal_name')
            ->orderBy('tb_quote.created_at', 'desc')
            ->get();

        $backdate_num = Quote::select('quote_number','id_quote')->where('status_backdate', 'T')->whereYear('created_at', $tahun)->orderBy('created_at','asc')->get();

        $count = DB::table('tb_quote')
            ->where('status_backdate', 'T')
            ->get();

        $counts = count($count);

        $customer = TB_Contact::select('customer_legal_name', 'id_customer')->where('tb_contact.status', 'Accept')->get();

        $status_quote = Quote::select('status_backdate')->where('status_backdate', '!=', 'T')->groupBy('status_backdate')->get();

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
        } else {
            $notifClaim = DB::table('dvg_esm')
                ->select('nik_admin', 'personnel', 'type')
                ->where('status', 'FINANCE')
                ->get();
        }

        $lead = DB::table('sales_lead_register')->whereRaw("(`result` = 'INITIAL' OR `result` = '' OR  `result` = 'SD' OR `result` = 'TP')");

        if ($role->name == 'VP Solutions & Partnership Management'){
            $leadId = $lead->where('nik', '1061184050');
        } elseif($role->name == 'Technology Alliance Solutions' || $role->name == 'Product Development Specialist Manager'){
            $leadId = $lead->where('nik', '1110492070');
        }else {
            $leadId = $lead->where('nik', Auth::user()->nik);
        }
        $leadId = $leadId->select('lead_id','opp_name')->get();

        $sidebar_collapse = true;

        $quoteStatus = Quote::pluck('status')->unique()->values();

        $year_before = Quote::select(DB::raw('YEAR(created_at) year'))->orderBy('year','desc')->groupBy('year')->get();

        $pid = SalesProject::join('sales_lead_register', 'sales_lead_register.lead_id', '=', 'tb_id_project.lead_id')->join('users', 'users.nik', '=', 'sales_lead_register.nik')->select('id_project')->where('id_company', '1')->get();

        return view('sales/quote_list',compact('notif','datas','notifOpen','notifsd','notiftp', 'notifClaim', 'counts', 'count','pops', 'pops2', 'backdate_num', 'sidebar_collapse', 'customer', 'status_quote','tahun','year_before', 'pid','leadId', 'role', 'quoteStatus'))->with(['initView'=> $this->initMenuBase(),'feature_item'=>$this->RoleDynamic('quotation_list')]);
    }

	public function create()
	{

	}

	public function get_backdate_num(Request $request)
    {
        if (isset($request->tanggal)) {
            $backdate_num = Quote::selectRaw('`quote_number` as `text`')->selectRaw('`id_quote` as `id`')->where('status_backdate', 'T')->whereYear('created_at',substr($request->tanggal, 6,4))->orderBy('created_at','desc')->get();
            return array('results'=>$backdate_num);
            // return substr($request->tanggal, 6,4);
        } else {
            $backdate_num = Quote::selectRaw('`quote_number` as `text`')->selectRaw('`id_quote` as `id`')->where('status_backdate', 'T')->orderBy('created_at','desc')->get();
            return array('results'=>$backdate_num);
            // return substr($request->tanggal, 6,4);

        }
        
    }

    public function getCustomer(Request $req)
    {
        $data = collect(TB_Contact::select(DB::raw('`id_customer` AS `id`,`customer_legal_name` AS `text`'))->get());

        return array("data" => $data);
    }

    public function getdataquote(Request $request)
    {
        $tahun = date("Y");
        $user = Auth::user()->nik;
        $role = $this->cekRole($user);
        $query = Quote::join('users', 'users.nik', '=', 'tb_quote.nik')
            ->join('tb_contact', 'tb_contact.id_customer', '=', 'tb_quote.id_customer', 'left')
            ->leftjoin('tb_quote_config as c', 'tb_quote.id_quote', 'c.id_quote')
            ->where(function($query) {
                $query->where('status_backdate', '!=', 'T')
                    ->orWhereNull('status_backdate');
            });
//            ->where('c.status', 'Choosed');

        if($role->name == 'VP Sales'){
            $roleSalesByTerritory = DB::table('roles as r')
                ->join('role_user as ru', 'r.id', 'ru.role_id')
                ->join('users as u', 'ru.user_id', 'u.nik')
                ->whereIn('r.name', ['Account Executive', 'VP Sales'])
                ->select('ru.user_id as nik')->pluck('nik')->toArray();
            $query = $query->whereIn('tb_quote.nik', $roleSalesByTerritory);
        }else if($role->name == 'Account Executive' || $role->name == 'Technology Alliance Solutions' || $role->name == 'Product Development Specialist Manager'){
            $query = $query->where('tb_quote.nik', $user);
        }
//            ->where('date','like',$tahun."%")
            $query = $query->select('tb_quote.id_quote','quote_number','position','type_of_letter','date','to','tb_quote.attention','title','project',
                'tb_quote.status', 'description', 'from', 'division', 'project_id','note', 'status_backdate',
                'tb_quote.nik', 'name', 'month', 'tb_quote.project_type', 'tb_contact.id_customer', 'customer_legal_name','c.nominal')->whereYear('date',date('Y'))->get();

        return array("data" => $query);
    }


    public function getDataQuoteFilter(Request $request)
    {
        $status = $request->status ?: [];
        $tahun = date("Y");
        $user = Auth::user()->nik;
        $role = $this->cekRole($user);
        $query1 = Quote::join('users', 'users.nik', '=', 'tb_quote.nik')
            ->join('tb_contact', 'tb_contact.id_customer', '=', 'tb_quote.id_customer', 'left')
            ->join('role_user as ru', 'users.nik', 'ru.user_id')
            ->join('roles as r', 'ru.role_id', 'r.id')
            ->leftjoin('tb_quote_config as c', 'tb_quote.id_quote', 'c.id_quote')
            ->where(function($query) {
                $query->where('status_backdate', '!=', 'T')
                    ->orWhereNull('status_backdate');
            })
            ->where('c.status', 'Choosed');

        $query2 = Quote::join('users', 'users.nik', '=', 'tb_quote.nik')
            ->join('tb_contact', 'tb_contact.id_customer', '=', 'tb_quote.id_customer', 'left')
            ->join('role_user as ru', 'users.nik', 'ru.user_id')
            ->join('roles as r', 'ru.role_id', 'r.id')
            ->leftjoin('tb_quote_config as c', 'tb_quote.id_quote', 'c.id_quote');

        if($role->name == 'VP Sales'){
            $roleSalesByTerritory = DB::table('roles as r')
                ->join('role_user as ru', 'r.id', 'ru.role_id')
                ->join('users as u', 'ru.user_id', 'u.nik')
                ->whereIn('r.name', ['Account Executive', 'VP Sales'])
                ->where('u.id_territory', Auth::user()->id_territory)
                ->select('ru.user_id as nik')->pluck('nik')->toArray();
            $query1 = $query1->whereIn('tb_quote.nik', $roleSalesByTerritory);
            $query2 = $query2->whereIn('tb_quote.nik', $roleSalesByTerritory)->where('tb_quote.status', 'SAVED');
        }else if ($role->name == 'VP Solutions & Partnership Management'){
            $query2 = $query2->where('tb_quote.status', 'SAVED');
        }else if($role->name == 'Account Executive' || $role->name == 'Technology Alliance Solutions' || $role->name == 'Product Development Specialist Manager'){
            $query1 = $query1->where('tb_quote.nik', $user);
            $query2 = $query2->where('tb_quote.nik', $user)->where('tb_quote.status', 'SAVED');
        }else if($role->name == 'Chief Executive Officer' || $role->name == 'Chief Operating Officer'){
            $query1 = $query1->where('tb_quote.status', 'APPROVED');
//            $query2 = $query2->where('tb_quote.status', 'APPROVED');
        }

        if ($status != [] && $status != [null]){
            $query1 = $query1->whereIn('tb_quote.status', $status);
            $query2 = $query2->whereIn('tb_quote.status', $status);
        }

        if($request->startDate != '' && $request->endDate != '' ){
            $query1 = $query1->whereBetween('tb_quote.date', [$request->startDate, $request->endDate]);
            $query2 = $query2->whereBetween('tb_quote.date', [$request->startDate, $request->endDate]);
        }

        if ($role->name == 'Chief Executive Officer' || $role->name == 'Chief Operating Officer'){
            $query1 = $query1->select('tb_quote.id_quote','quote_number','position','type_of_letter','date','to','tb_quote.attention','title','project',
                'tb_quote.status', 'tb_quote.description', 'from', 'division', 'project_id','note', 'status_backdate','r.name as role_name',
                'tb_quote.nik', 'users.name', 'month', 'c.project_type', 'tb_contact.id_customer', 'customer_legal_name','c.nominal');

            $finalQuery = $query1->orderByDesc('date')->get();
        }else{
            $query1 = $query1->select('tb_quote.id_quote','quote_number','position','type_of_letter','date','to','tb_quote.attention','title','project',
                'tb_quote.status', 'tb_quote.description', 'from', 'division', 'project_id','note', 'status_backdate','r.name as role_name',
                'tb_quote.nik', 'users.name', 'month', 'c.project_type', 'tb_contact.id_customer', 'customer_legal_name','c.nominal');
            $query2 = $query2->select('tb_quote.id_quote','quote_number','position','type_of_letter','date','to','tb_quote.attention','title','project',
                'tb_quote.status', 'tb_quote.description', 'from', 'division', 'project_id','note', 'status_backdate', 'r.name as role_name',
                'tb_quote.nik', 'users.name', 'month', 'c.project_type', 'tb_contact.id_customer', 'customer_legal_name','c.nominal');

//            $finalQuery = DB::table(DB::raw("({$query1->toBase()->union($query2->toBase())->toSql()}) as combined"))
//                ->mergeBindings($query1->toBase()->union($query2->toBase()))
//                ->orderByRaw("FIELD(combined.status, 'ON GOING', 'REJECTED', 'APPROVED', 'SAVED')")
//                ->orderByDesc('date')
//                ->distinct()
//                ->get();

            $query1Sql = $query1->toBase()->toSql();
            $query2Sql = $query2->toBase()->toSql();

            $finalQuery = DB::table(DB::raw("({$query1Sql} UNION {$query2Sql}) as combined"))
                ->mergeBindings($query1->toBase())
                ->mergeBindings($query2->toBase())
                ->orderByRaw("FIELD(combined.status, 'ON GOING', 'REJECTED', 'APPROVED', 'SAVED')")
                ->orderByDesc('date')
                ->distinct()
                ->get();
        }

        return array("data" => $finalQuery, 'role' => $role->name);
    }

    public function getDropdownFilterQuote()
    {
        $quoteStatus = Quote::pluck('status')->unique()->values()->map(function ($item, $key){
            return array("id" => $item, "text" => $item);
        });

        return collect([
           'dataStatus' => $quoteStatus
        ]);
    }

    public function getDetailLead(Request $request)
    {
        $customer = DB::table('sales_lead_register as a')->where('lead_id', $request->lead_id)
            ->join('tb_contact as b', 'a.id_customer', 'b.id_customer')
            ->select('customer_legal_name as customer', 'office_building', 'street_address', 'city', 'phone', 'opp_name as subject', 'a.id_customer', 'postal')
            ->first();

        return response()->json($customer);
    }

    public function storeQuotation(Request $request)
    {
        $user = Auth::user();
        $role = $this->cekRole($user->nik);

        try {
         DB::beginTransaction();
            $address = '';
            if (!empty($request['building'])) {
                $address .= $request['building'] . '<br>';
            }
            $address .= $request['street'].'<br>'.$request['city'];

            $quote = new Quote();
            $quote->quote_number = '-';
            $quote->to = $request['customer'];
            $quote->attention = $request['attention'];
            $quote->title = $request['subject'];
            $quote->from = $user->name;
            $quote->nik = $user->nik;
            $quote->email = $request['email'];
            $quote->no_telp = $request['telp'];
            $quote->id_customer = $request['id_customer'];
            $quote->project_type = $request['quote_type'];
            $quote->building = $request['building'];
            $quote->street = $request['street'];
            $quote->city = $request['city'];
            $quote->address = $address;
            $quote->lead_id = $request['lead_id'];
            $quote->date = $request['date'];
            $quote->status = 'SAVED';
            if ($role->name == 'VP Solutions & Partnership Management' || $role->name == 'Technology Alliance Solutions' || $role->name == 'Product Development Specialist Manager'){
                $quote->position = 'SPM';
            }else if($role->name = 'Account Executive' || $role->name == 'VP Sales'){
                $quote->position = $request['position'];
            }else if($role->name == 'Chief Operating Officer'){
                $quote->position == 'DIR';
            }
            $quote->save();

            DB::commit();
            return $quote->id_quote;
        }catch (Exception $e){
            DB::rollback();

        }
    }

    public function updateQuotation(Request $request)
    {
        $user = Auth::user();
        $role = $this->cekRole($user->nik);

        try {
         DB::beginTransaction();
            $address = '';
            if (!empty($request['building'])) {
                $address .= $request['building'] . '<br>';
            }else{
                $address .= $request['street'].'<br>'.$request['city'];
            }
            $quote = Quote::find($request['id_quote']);
            $quote->quote_number = '-';
            $quote->to = $request['customer'];
            $quote->attention = $request['attention'];
            $quote->title = $request['subject'];
            $quote->from = $user->name;
            $quote->nik = $user->nik;
            $quote->email = $request['email'];
            $quote->no_telp = $request['telp'];
            $quote->id_customer = $request['id_customer'];
            $quote->project_type = $request['quote_type'];
            $quote->building = $request['building'];
            $quote->street = $request['street'];
            $quote->city = $request['city'];
            $quote->address = $address;
            $quote->lead_id = $request['lead_id'];
            $quote->date = $request['date'];
            $quote->status = 'SAVED';
            if ($role->name == 'VP Solutions & Partnership Management' || $role->name == 'Technology Alliance Solutions' || $role->name == 'Product Development Specialist Manager'){
                $quote->position = 'SPM';
            }else if($role->name = 'Account Executive' || $role->name == 'VP Sales'){
                $quote->position = $request['position'];
            }else if($role->name == 'Chief Operating Officer'){
                $quote->position == 'DIR';
            }
            $quote->save();

            DB::commit();
            return $quote->id_quote;
        }catch (Exception $e){
            DB::rollback();

        }
    }

    public function updateQuotationNewVersion(Request $request)
    {
        $user = Auth::user();
        $role = $this->cekRole($user->nik);

        $version = $request['version'];
        try {
            DB::beginTransaction();
            $quote = Quote::find($request['id_quote']);
            $countConfig = QuoteConfig::where('id_quote', $request['id_quote'])->where('status', '!=', null)->count();
            if ($version == 'new'){
                $config  = new QuoteConfig();
                $config->version = $countConfig + 1;
                $config->id_quote = $request['id_quote'];
                $config->project_type = $request['quote_type'];
                $config->email = $request['email'];
                $config->attention = $request['attention'];
                $config->save();

                $getLatestConfig = QuoteConfig::where('id_quote', $request['id_quote'])->where('version', $countConfig)->first();
                $getProduct = QuoteConfigProduct::join('tb_quote_product as p', 'tb_quote_config_product.id_product', 'p.id')
                    ->where('id_config', $getLatestConfig->id)
                    ->select('p.name', 'p.qty', 'p.nominal', 'p.grand_total', 'p.description', 'p.unit','p.price_list', 'p.total_price_list')
                    ->get();
                foreach ($getProduct as $product){
                    $newProduct = new QuoteProduct();
                    $newProduct->id_quote = $request['id_quote'];
                    $newProduct->name = $product->name;
                    $newProduct->description = $product->description;
                    $newProduct->qty = $product->qty;
                    $newProduct->unit = $product->unit;
                    $newProduct->nominal = $product->nominal;
                    $newProduct->grand_total = $product->grand_total;
                    $newProduct->price_list = $product->price_list;
                    $newProduct->total_price_list = $product->total_price_list;
                    $newProduct->save();

                    $newConfigProduct = new QuoteConfigProduct();
                    $newConfigProduct->id_config = $config->id;
                    $newConfigProduct->id_product = $newProduct->id;
                    $newConfigProduct->save();
                }
            }else{
                $config = QuoteConfig::find($request['id_config']);
                $config->project_type = $request['quote_type'];
                $config->email = $request['email'];
                $config->attention = $request['attention'];
                $config->save();
            }
            $address = '';
            if (!empty($request['building'])) {
                $address .= $request['building'] . '<br>';
            }
            $address .= $request['street'].'<br>'.$request['city'];
            $quote->email = $request['email'];
            $quote->attention = $request['attention'];
            $quote->building = $request['building'];
            $quote->street = $request['street'];
            $quote->city = $request['city'];
            $quote->address = $address;
             $quote->save();
            DB::commit();
            return $config->id;
        }catch (Exception $e){
            DB::rollback();

        }
    }

    public function getProductQuote(Request $request)
    {
        $quoteProduct = QuoteProduct::where('id_quote', $request['id_quote'])->orderBy('id', 'asc')->get();

        return array('data' => $quoteProduct);
    }

    public function getProductQuoteNewVersion(Request $request)
    {
        $configProduct = QuoteConfigProduct::where('id_config', $request['id_config'])
            ->join('tb_quote_product as b', 'tb_quote_config_product.id_product', 'b.id')
            ->orderBy('b.id', 'asc')
            ->get();
        $quoteProduct = QuoteProduct::where('id_quote', $request['id_quote'])->orderBy('id', 'asc')->get();

        return array('data' => $configProduct);
    }

    public function storeProductQuotation(Request $request)
    {
        $idConfig = $request['id_config'] ?? null;
        $quoteProduct = new QuoteProduct();
        $quoteProduct->id_quote = $request['id_quote'];
        $quoteProduct->name = $request['nameProduct'];
        $quoteProduct->qty = $request['qtyProduct'];
        $quoteProduct->description = $request['descProduct'];
        $quoteProduct->nominal = $request['priceProduct'];
        if(!empty($request['priceList'])){
            $quoteProduct->price_list = $request['priceList'];
            $quoteProduct->total_price_list = $request['priceList'] * $request['qtyProduct'];
        }
        $quoteProduct->unit = $request['typeProduct'];
        $quoteProduct->grand_total = $request['totalPrice'];
        $quoteProduct->save();

        if ($idConfig != null){
            $configProduct = new QuoteConfigProduct();
            $configProduct->id_config = $idConfig;
            $configProduct->id_product = $quoteProduct->id;
            $configProduct->save();
        }

    }

    public function getTax(Request $request)
    {
        $tax = QuoteConfig::find($request['id_tax']);

        return collect([
            'data' => $tax
        ]);
    }

    public function storeTax(Request $request)
    {
        $quote = Quote::find($request['id_quote']);
        $version = QuoteConfig::where('id_quote', $request['id_quote'])->count();
        $quoteConfig = new QuoteConfig();
        $quoteConfig->id_quote = $request['id_quote'];
        $quoteConfig->nominal = str_replace('.', '', $request['grand_total']);
        $quoteConfig->project_type = $quote->project_type;
        $quoteConfig->discount = $request['discount'];
        $quoteConfig->tax_vat = $request['status_tax'];
        $quoteConfig->date_add = Carbon::today();
        $quoteConfig->version = $version + 1;
        $quoteConfig->status = 'Choosed';
        $quoteConfig->save();

        return $quoteConfig->id;
    }

    public function updateTax(Request $request)
    {
        $quote = Quote::find($request['id_quote']);
        $quoteConfig = QuoteConfig::find($request['id']);
        $quoteConfig->id_quote = $request['id_quote'];
        $quoteConfig->nominal = str_replace('.', '', $request['grand_total']);
        $quoteConfig->discount = $request['discount'];
        $quoteConfig->tax_vat = $request['status_tax'];
        $quoteConfig->date_add = Carbon::today();
        $quoteConfig->status = 'Choosed';
        $quoteConfig->save();

        return $quoteConfig->id;
    }
    public function updateTaxNewVersion(Request $request)
    {
        $quote = Quote::find($request['id_quote']);

        $quoteConfig = QuoteConfig::find($request['id']);
        $quoteConfig->id_quote = $request['id_quote'];
        $quoteConfig->nominal = str_replace('.', '', $request['grand_total']);
        $quoteConfig->discount = $request['discount'];
        $quoteConfig->tax_vat = $request['status_tax'];
        $quoteConfig->date_add = Carbon::today();
        $quoteConfig->save();

        return $quoteConfig->id;
    }

    public function updateProductQuotation(Request $request)
    {
        $quoteProduct = QuoteProduct::find($request['id']);
        $quoteProduct->id_quote = $request['id_quote'];
        $quoteProduct->name = $request['nameProduct'];
        $quoteProduct->qty = $request['qtyProduct'];
        $quoteProduct->description = $request['descProduct'];
        $quoteProduct->nominal = $request['priceProduct'];
        if(!empty($request['priceList'])){
            $quoteProduct->price_list = $request['priceList'];
            $quoteProduct->total_price_list = $request['priceList'] * $request['qtyProduct'];
        }
        $quoteProduct->unit = $request['typeProduct'];
        $quoteProduct->grand_total = $request['totalPrice'];
        $quoteProduct->save();
    }

    public function storeTermPayment(Request $request)
    {
        $quote = Quote::find($request['id_quote']);

        $quote->term_payment = str_replace('•', '-', $request['term_payment']);
        $quote->save();
    }

    public function getPreview(Request $request)
    {
        $id_quote = $request['id_quote'];
        $quote = Quote::find($id_quote);
        if ($request['id_config']){
            $quoteProduct = QuoteConfigProduct::where('id_config', $request['id_config'])
                ->rightjoin('tb_quote_product as a', 'tb_quote_config_product.id_product', 'a.id')
                ->get();
            $quoteConfig = QuoteConfig::find($request['id_config']);
        }else{
            $quoteProduct = QuoteProduct::where('id_quote', $id_quote)->get();
            $quoteConfig = QuoteConfig::where('id_quote', $id_quote)->first();
        }

        return collect([
           'quote'  => $quote,
            'product' => $quoteProduct,
            'config' => $quoteConfig
        ]);
    }

    public function getVersionConfig(Request $request)
    {
        $config = QuoteConfig::where('id_quote', $request['id_quote'])
                ->where('status','!=', null)
                ->get();

        return collect(['data' => $config]);
    }

    public function getVersionDetail(Request $request)
    {
	    $config = QuoteConfig::join('tb_quote as q', 'tb_quote_config.id_quote', 'q.id_quote')
            ->select('tb_quote_config.email', 'tb_quote_config.attention', 'q.title')
            ->where('tb_quote_config.id', $request->id)
            ->first();

	    return response()->json(['data' => $config]);
    }

    public function getDataEmail(Request $request)
    {
        $config = QuoteConfig::join('tb_quote as a', 'tb_quote_config.id_quote', 'a.id_quote')
            ->select('a.from', 'a.to', 'a.no_telp', 'a.quote_number', 'a.title', 'a.term_payment', 'a.address', 'a.building', 'a.street','a.city',
            'tb_quote_config.email','tb_quote_config.project_type', 'tb_quote_config.attention', 'tb_quote_config.nominal', 'tb_quote_config.tax_vat','a.nik')
            ->where('tb_quote_config.id', $request->id)
            ->where('tb_quote_config.status', 'Choosed')
            ->first();
        $product = QuoteConfigProduct::join('tb_quote_product as a', 'tb_quote_config_product.id_product', 'a.id')
            ->where('id_config', $request->id)
            ->select('a.*')
            ->get();
        $role = $this->cekRole($config->nik);
        $getTerritory = DB::table('users')->where('nik', $config->nik)->first()->id_territory;
        if ($getTerritory == 'TERRITORY 1'){
            $territory = 'Territory 1';
        }else if($getTerritory == 'TERRITORY 2'){
            $territory = 'Territory 2';
        }else if($getTerritory == 'TERRITORY 3'){
            $territory = 'Territory 3';
        }

        return view('quotation_pdf', compact('config', 'product','role', 'territory'));
    }

    public function deleteProduct(Request $request)
    {
        $quoteProduct = QuoteProduct::find($request->id);

        $quoteProduct->delete();
    }

    public function getProductById(Request $request)
    {
        $quoteProduct = QuoteProduct::find($request->id);

        return array('data' => $quoteProduct);
    }

    public function uploadCSV(Request $request){
        $directory = "quote/";
        $nameFile = "test_csv_upload.xlsx";

        $newVersion = $request['new_version'] ?? null;

        $this->uploadToLocal($request->file('csv_file'),$directory,$nameFile);

        $result = $this->readCSV($directory . "/" . $nameFile);

        if ($result == 'Format tidak sesuai' ) {
            return collect([
                "text" => 'Format tidak sesuai',
                "status" => 'Error',
            ]);
        } else if ($result == 'Tidak ada produk') {
            return collect([
                "text" => 'Tidak ada produk',
                "status" => 'Error',
            ]);
        } else {
            try{
                if(count($result) >= 1){
                    foreach ($result as $key => $value) {
                        if (is_numeric($value[3]) && is_numeric($value[5])) {
                            if ($value[6] != null || $value[6] != ''){
                                $insertProduct[] = ['id_quote' => $request->id_quote,'name' => $value[1], 'description' => (string)$value[2], 'qty' => $value[3], 'unit' => $value[4], 'nominal' => $value[5], 'grand_total' => $value[3]*$value[5], 'price_list' => $value[6], 'total_price_list' => $value[3]*$value[6]];
                            }else{
                                $insertProduct[] = ['id_quote' => $request->id_quote,'name' => $value[1], 'description' => (string)$value[2], 'qty' => $value[3], 'unit' => $value[4], 'nominal' => $value[5], 'grand_total' => $value[3]*$value[5], 'price_list' => 0, 'total_price_list' => 0];
                            }
                        }else{
                            if ($value[6] != null || $value[6] != ""){
                                $insertProduct[] = ['id_quote' => $request->id_quote,'name' => $value[1], 'description' => (string)$value[2], 'qty' => $value[3], 'unit' => $value[4], 'nominal_product' => preg_replace("/[^0-9]/", "", substr($value[5], 0, strpos($value[5], ","))),
                                    'grand_total' => $value[3] * preg_replace(" /[^0-9]/", "", substr($value[5], 0, strpos($value[5], ","))), 'price_list' => preg_replace("/[^0-9]/", "", substr($value[6], 0, strpos($value[6], ","))), 'total_price_list' => $value[3] * preg_replace("/[^0-9]/", "", substr($value[6], 0, strpos($value[6], ",")))
                                ];
                            }else{
                                $insertProduct[] = ['id_quote' => $request->id_quote,'name' => $value[1], 'description' => (string)$value[2], 'qty' => $value[3], 'unit' => $value[4], 'nominal_product' => preg_replace("/[^0-9]/", "", substr($value[5], 0, strpos($value[5], ","))),
                                    'grand_total' => $value[3] * preg_replace("/[^0-9]/", "", substr($value[5], 0, strpos($value[5], ","))), 'price_list' => 0, 'total_price_list' => 0];
                            }
                        }
                    }

                    if(!empty($insertProduct)){
                        foreach ($insertProduct as $product) {
                            $product = QuoteProduct::create($product);
                            if ($newVersion != null){
                                $configProduct = new QuoteConfigProduct();
                                $configProduct->id_product = $product->id;
                                $configProduct->id_config = $request['id_config'];
                                $configProduct->save();
                            }
                        }
//                        $product = QuoteProduct::insert($insertProduct);
                    }

                } else {
                    return 'Tidak ada produk';
                }
            }catch(\Exception $e){
                return collect([
                   'text' => 'Something went wrong!',
                   'status' => 'Error'
                ]);
            }

        }

        return $result;
    }

    public function readCSV($locationFile){

        $format = array(
            "product",
            "description",
            "qty",
            "type(Pcs,Unit,Lot,Pack,Node)",
            "price(non-ppn)",
            "pricelist(non-ppn)"
        );

        if (($open = fopen($locationFile, "r")) !== FALSE) {

            $i = 0;
            $array = [];
            while (($data = fgetcsv($open, 1000, ";")) !== FALSE) {
                if($i != 0){
                    $array[] = $data;
                } else {
                    array_shift($data);
                    if (empty(!array_diff($format, $data))) {
                        return 'Format tidak sesuai';
                    }
                }
                $i++;
            }
            if ($i == 1) {
                return 'Tidak ada produk';
            }
            fclose($open);
        }

        return $array;
    }

    public function uploadToLocal($file,$directory,$nameFile){
        $file->move($directory,$nameFile);
    }

    /*public function getdatabackdate(Request $request)
    {
        // $tahun = date("Y"); 

        return array("data" => Quote::join('users', 'users.nik', '=', 'tb_quote.nik')
                        ->join('tb_contact', 'tb_contact.id_customer', '=', 'tb_quote.id_customer', 'left')
                        ->select('id_quote','quote_number','position','type_of_letter','date','to','attention','title','project','status', 'description', 'from', 'division', 'project_id','note', 'status_backdate', 'tb_quote.nik', 'name', 'month', 'project_type', 'tb_contact.id_customer', 'customer_legal_name')
                        ->where('status_backdate', $request->status)
                        ->where('date','like',$request->year."%")
                        ->get());
    }*/


    public function getfilteryear(Request $request)
    {
        // $tahun = date("Y"); 

        return array("data" => Quote::join('users', 'users.nik', '=', 'tb_quote.nik')
                        ->join('tb_contact', 'tb_contact.id_customer', '=', 'tb_quote.id_customer', 'left')
                        ->select('id_quote','quote_number','position','type_of_letter','date','to','attention','title','project','tb_quote.status', 'description', 'from', 'division', 'project_id','note', 'status_backdate', 'tb_quote.nik', 'name', 'month', 'project_type', 'tb_contact.id_customer', 'customer_legal_name')
                        ->where('status_backdate', $request->status)
                        ->where('date','like',$request->year."%")
                        ->get());
    }

    public function storeLastStepQuote(Request $request)
    {
        $countQuote = Quote::where('quote_number', '!=', '-')
//            ->whereYear('quote_number_update', date('Y'))
            ->where('quote_number', 'like', '%'.date('Y'))
            ->count();
        $type = 'QO';
        $quotation = Quote::find($request['id_quote']);
        $config = QuoteConfig::where('id_quote', $request['id_quote'])->first();
        $position = $quotation->position;
        $monthInRomawi = array('01' => "I",
            '02' => "II",
            '03' => "III",
            '04' => "IV",
            '05' => "V",
            '06' => "VI",
            '07' => "VII",
            '08' => "VIII",
            '09' => "IX",
            '10' => "X",
            '11' => "XI",
            '12' => "XII");
        $monthQuote = Carbon::parse($quotation->date)->format('m');
        $yearQuote = Carbon::parse($quotation->date)->format('Y');
        $month = $monthInRomawi[$monthQuote];
        $role = $this->cekRole($quotation->nik);
        try {
            DB::beginTransaction();
            $quotationDate = Carbon::parse($quotation->date);

            if ($quotationDate < Carbon::today()){
                $checkBackDateNumber = Quote::where('status_backdate','T')
                    ->whereYear('created_at',date('Y'))
                    ->orderBy('quote_number','asc')
                    ->first();
                if ($checkBackDateNumber != null){
                    $no   = $checkBackDateNumber->quote_number.'/'.$position .'/'. $type.'/' . $month .'/'. $yearQuote;
                }else{
                    $countBackDate = Quote::where('status_backdate', 'F')->where('quote_number', 'like', '%'.date('Y'));
//                        ->whereYear('quote_number_update', date('Y'));
                    if ($countBackDate->count() > 0){
                        $lastBackDate = $countBackDate->orderBy('date', 'desc')->first()->quote_number;
                        $getLastNumberBackDate = explode("/",$lastBackDate)[0];
                        $newBackDateNumber = $getLastNumberBackDate + 10;
                        if($newBackDateNumber < 10){
                            $finalNumber  = '000' . $newBackDateNumber;
                        }elseif($newBackDateNumber > 9 && $newBackDateNumber < 100){
                            $finalNumber = '00' . $newBackDateNumber;
                        }elseif($newBackDateNumber >= 100){
                            $finalNumber = '0' . $newBackDateNumber;
                        }
                        $no   = $finalNumber.'/'.$position .'/'. $type.'/' . $month .'/'. $yearQuote;
                    }else{
                        $no   = '0005'.'/'.$position .'/'. $type.'/' . $month .'/'. $yearQuote;
                    }
                }
                $quotation->quote_number = $no;
                $quotation->status_backdate = 'F';
                $quotation->type_of_letter = $type;
                $quotation->month = $month;
                $quotation->status = 'ON GOING';
                $quotation->quote_number_update = Carbon::today();
                $quotation->save();
            }else{
                if ($countQuote > 0){
                    $quote = Quote::where('status_backdate','A')
//                        ->whereYear('quote_number_update',date('Y'))
                        ->where('quote_number', 'like', '%'.date('Y'))
                        ->where('quote_number', '!=', '-')
                        ->orderBy('date','desc')
                        ->first()->quote_number;

                    $getLastNumberQuote =  explode("/",$quote)[0];

                    $quoteNumber = $getLastNumberQuote+1;

                    $quoteNumber9 = $getLastNumberQuote+2;

                    if($quoteNumber < 10){
                        $finalNumber  = '000' . $quoteNumber;
                        $finalNumber9 = '000' . $quoteNumber9;
                    }elseif($quoteNumber > 9 && $quoteNumber < 100){
                        $finalNumber = '00' . $quoteNumber;
                        $finalNumber9 = '00' . $quoteNumber9;
                    }elseif($quoteNumber >= 100){
                        $finalNumber = '0' . $quoteNumber;
                        $finalNumber9 = '0' . $quoteNumber9;
                    }

                    if (substr($getLastNumberQuote, -1) == '4') {
                        $no   = $finalNumber9.'/'.$position .'/'. $type.'/' . $month .'/'. $yearQuote;

                        $no9  = $finalNumber;
                        $checkQuoteBackDate = Quote::where('quote_number', '=', $no9)->exists();

//                    if (!$checkQuoteBackDate) {
//                        $backDateQuote = new Quote();
//                        $backDateQuote->quote_number = $no9;
//                        $backDateQuote->status_backdate = 'T';
//                        $backDateQuote->save();
//                    }

                        $quotation->quote_number = $no;
                        $quotation->status_backdate = 'A';
                        $quotation->type_of_letter = $type;
                        $quotation->month = $month;
                        $quotation->status = 'ON GOING';
                        $quotation->quote_number_update = Carbon::today();
                        $quotation->save();

                    }else {
                        $no   = $finalNumber.'/'.$position .'/'. $type.'/' . $month .'/'. $yearQuote;

                        $quotation->quote_number = $no;
                        $quotation->status_backdate = 'A';
                        $quotation->type_of_letter = $type;
                        $quotation->month = $month;
                        $quotation->status = 'ON GOING';
                        $quotation->quote_number_update = Carbon::today();
                        $quotation->save();
                    }
                } else {
                    $getlastnumber = 1;

                    if($getlastnumber < 10){
                        $finalNumber = '000' . $getlastnumber;
                    }elseif($getlastnumber > 9 && $getlastnumber < 100){
                        $finalNumber = '00' . $getlastnumber;
                    }elseif($getlastnumber >= 100){
                        $finalNumber = '0' . $getlastnumber;
                    }

                    $no   = $finalNumber.'/'.$position .'/'. $type.'/' . $month .'/'. $yearQuote;

                    $quotation->quote_number = $no;
                    $quotation->status_backdate = 'A';
                    $quotation->type_of_letter = $type;
                    $quotation->month = $month;
                    $quotation->status = 'ON GOING';
                    $quotation->quote_number_update = Carbon::today();
                    $quotation->save();
                }
            }
            $config->update(['email' => $quotation->email, 'attention' => $quotation->attention]);
            $quoteProduct = QuoteProduct::where('id_quote', $quotation->id_quote)->get();
            foreach ($quoteProduct as $product){
                $configProduct = new QuoteConfigProduct();
                $configProduct->id_config = $config->id;
                $configProduct->id_product = $product->id;
                $configProduct->save();
            }
            $nominal = str_replace(',', '.', $config->nominal); // Pastikan menggunakan . sebagai pemisah desimal
            $nominal = floatval($nominal);
            $quoteActivity = new QuoteActivity();
            $quoteActivity->id_quote = $quotation->id_quote;
            $quoteActivity->operator = Auth::user()->name;
            $quoteActivity->activity = 'Create Quotation version 1 with ammount '. number_format($nominal, 2, ',', '.');
            $quoteActivity->status = 'ON GOING';
            $quoteActivity->date_add = Carbon::now();
            $quoteActivity->save();
            $this->sendEmail($quotation->status, $role->name, $quotation->nik, 'Store New', $quotation, '[SIMS APP] New Quotation');
            DB::commit();
        }catch (Exception $e){
            DB::rollBack();
            Log::error('message : '.$e->getMessage());
            Log::error($e->getTraceAsString());
        }

    }

    public function storeLastStepQuoteNewVersion(Request $request)
    {
        $decimalToRomawi = array(
            '01' => "I",
            '02' => "II",
            '03' => "III",
            '04' => "IV",
            '05' => "V",
            '06' => "VI",
            '07' => "VII",
            '08' => "VIII",
            '09' => "IX",
            '10' => "X",
            '11' => "XI",
            '12' => "XII");

        $romawiToDecimal = array(
            "I" => '01',
            "II" => '02',
            "III" => '03',
            "IV" => '04',
            "V" => '05',
            "VI" => '06',
            "VII" => '07',
            "VIII" => '08',
            "IX" => '09',
            "X" => '10',
            "XI" => '11',
            "XII" => '12');

        try{
            DB::beginTransaction();
            $getOldVersion = QuoteConfig::where('id_quote', $request['id_quote'])->get();
            foreach ($getOldVersion as $old){
                if ($old->status == null && $old->id != $request['id_config']){
                    $old->delete();
                }else{
                    $old->update(['status' => 'New', 'reason' => null]);
                }
            }

            $quote = Quote::find($request['id_quote']);

            $quoteNumber = explode("/", $quote->quote_number)[0];
            $quoteNumberDiv = explode("/", $quote->quote_number)[1];
            $quoteNumberMonth = explode("/",$quote->quote_number)[3];
            $quoteNumberYear = explode("/",$quote->quote_number)[4];
            $month = $romawiToDecimal[$quoteNumberMonth];

            if ($quoteNumberYear != date('Y')){
                if ($quote->status_backdate == 'F'){
                    $countBackDate = Quote::where('status_backdate', 'F')->where('quote_number', 'like', '%'.date('Y'));
//                        ->whereYear('quote_number_update', date('Y'));
                    if ($countBackDate > 0){
                        $quoteNumberBackdate = Quote::where('status_backdate','F')
//                            ->whereYear('quote_number_update',date('Y'))
                            ->where('quote_number', 'like', '%'.date('Y'))
                            ->where('quote_number', '!=', '-')
                            ->orderBy('date','desc')
                            ->first()->quote_number;
                        $nmr = $quoteNumberBackdate + 10;
                        if($nmr < 10){
                            $no = '000' . $nmr;
                        }else if ($nmr > 9 && $nmr < 100){
                            $no = '00' . $nmr;
                        }else if ($nmr >= 100){
                            $no = '0' . $nmr;
                        }
                    }else{
                        $no = '0005';
                    }

                }else if ($quote->status_backdate == 'A'){
                    $countQuote = Quote::where('quote_number', '!=', '-')
                        ->where('status_backdate', '!=', 'F')
//                        ->whereYear('quote_number_update', date('Y'))
                        ->where('quote_number', 'like', '%'.date('Y'))
                        ->count();
                    if ($countQuote > 0){
                        $quoteNumbers = Quote::where('status_backdate','A')
//                            ->whereYear('quote_number_update',date('Y'))
                            ->where('quote_number', 'like', '%'.date('Y'))
                            ->where('quote_number', '!=', '-')
                            ->orderBy('date','desc')
                            ->first()->quote_number;
                        $lastNumber = substr($quoteNumbers, -1);
                        if ($lastNumber == '4'){
                            $nmr = $quoteNumbers + 2;
                        } else{
                            $nmr = $quoteNumbers + 1;
                        }
                        if($nmr < 10){
                            $no = '000' . $nmr;
                        }else if ($nmr > 9 && $nmr < 100){
                            $no = '00' . $nmr;
                        }else if ($nmr >= 100){
                            $no = '0' . $nmr;
                        }
                    }else{
                        $no = '0001';
                    }
                }
                $monthRomawi = $decimalToRomawi[date('m')];
                $newQuoteNumber = $no . '/' . $quoteNumberDiv . '/QO/'. $monthRomawi . '/' . date('Y');
                $quote->quote_number = $newQuoteNumber;
                $quote->quote_number_update = Carbon::today();
            }else if ($month != date('m')){
                $monthRomawi = $decimalToRomawi[date('m')];
                $no = $quoteNumber . '/' . $quoteNumberDiv . '/QO/'. $monthRomawi . '/' . $quoteNumberYear;
                $quote->quote_number = $no;
                $quote->quote_number_update = Carbon::today();
            }

            $quote->status = 'ON GOING';
            $quote->is_uploaded = 0;
            $quote->save();
            $role = $this->cekRole($quote->nik);

            $config = QuoteConfig::find($request['id_config']);
            $config->status = 'Choosed';
            $config->save();

            $nominal = str_replace(',', '.', $config->nominal);
            $nominal = floatval($nominal);

            $quoteActivity = new QuoteActivity();
            $quoteActivity->id_quote = $request['id_quote'];
            $quoteActivity->operator = Auth::user()->name;
            $quoteActivity->activity = 'Create Quotation version '. $config->version .' with ammount '. number_format($nominal, 2, ',', '.');
            $quoteActivity->status = 'ON GOING';
            $quoteActivity->date_add = Carbon::now();
            $quoteActivity->save();
//            $this->sendEmail($quote->status, $role->name, $quote->nik, 'Edit', $quote, '[SIMS APP] Update Quotation');
            DB::commit();
            return response(['message' => 'success']);
        }catch (\Exception $exception){
            DB::rollBack();
            Log::error('message : '.$exception->getMessage());
            Log::error($exception->getTraceAsString());
            return response(['message' => 'error', 'trace' => $exception->getTraceAsString()]);
        }
    }

    public function detailQuotation($id)
    {
        $version = QuoteConfig::where('id_quote', $id)->where('status', 'Choosed')->first();
        $quote = DB::table('tb_quote')->where('id_quote',$id)->first();
        $nik = $quote->nik;
        $status = $quote->status;
        $sign = $quote->sign;
        $sended = $quote->is_sended;
        $idVersion = $version->id;
        $roleData = $this->cekRole($nik);
        $roleUser = $this->cekRole(Auth::user()->nik);
        $idTerritory = DB::table('users')->where('nik', $nik)->first()->id_territory;
        $canApproveReject = false;
        if(($roleData->name == 'Technology Alliance Solutions' || $roleData->name == 'Product Development Specialist Manager') && $roleUser->name == 'VP Solutions & Partnership Management'){
            $canApproveReject = true;
        }else if($roleData->name == 'VP Solutions & Partnership Management' && $roleUser->name == 'Chief Operating Officer'){
            $canApproveReject = true;
        }else if($roleData->name == 'VP Sales' && $roleUser->name == 'Chief Executive Officer'){
            $canApproveReject = true;
        }else if($roleData->name == 'Account Executive' && $roleUser->name == 'VP Sales'){
            if($idTerritory == Auth::user()->id_territory){
                $canApproveReject = true;
            }
        }
        if ($idTerritory == Auth::user()->id_territory){
            $territory = true;
        }else {
            $territory = false;
        }
        return view('/sales/quote_detail', compact('version','nik', 'status', 'canApproveReject','id','sign','idVersion','sended'))->with(['initView' => $this->initMenuBase(), 'sidebar_collapse' => 'true']);
    }

    public function approveQuotation(Request $request)
    {
        $quotation = Quote::find($request['id_quote']);
        $version = QuoteConfig::where('status', '!=', null)->where('id_quote', $request['id_quote'])->get();
        $role = $this->cekRole($quotation->nik);
        try {
            DB::beginTransaction();
            $quotation->update(['status' => 'APPROVED']);
            $projectType = null;
            $nominal = null;
            foreach ($version as $versi){
                if ($versi->id == $request['id_config']){
                    $versi->status = 'Choosed';
                    $projectType = $versi->project_type;
                    $nominal = $versi->nominal;
                }else{
                    $versi->status = 'New';
                }
                $versi->save();
            }
            $nominalFinal = str_replace(',', '.', $nominal); // Pastikan menggunakan . sebagai pemisah desimal
            $nominalFinal = floatval($nominalFinal);

            $quoteActivity = new QuoteActivity();
            $quoteActivity->id_quote = $quotation->id_quote;
            $quoteActivity->operator = Auth::user()->name;
            $quoteActivity->activity = 'Quotation version '. $request['version'] .' ( '.$projectType.', '.number_format($nominalFinal, 2, ',', '.').' ) Approved by  '. Auth::user()->name;
            $quoteActivity->status = 'APPROVED';
            $quoteActivity->date_add = Carbon::now();
            $quoteActivity->save();
            $this->sendEmailAction($quotation->status,  $quotation->nik, $quotation, '[SIMS APP] Approved Quotation');
            DB::commit();
            return response(['message' => 'success']);
        }catch (\Exception $e){
            DB::rollBack();
            Log::error('message : '.$e->getMessage());
            Log::error($e->getTraceAsString());
            return response([
                'message' => 'error',
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    public function rejectQuotation(Request $request)
    {
        $quotation = Quote::find($request['id_quote']);
        $version = QuoteConfig::where('status', '!=', null)->where('id_quote', $request['id_quote'])->get();
        $role = $this->cekRole($quotation->nik);
        try {
            DB::beginTransaction();
            $quotation->update(['status' => 'REJECTED']);
            $projectType = null;
            $nominal = null;
            foreach ($version as $versi){
                if ($versi->id == $request['id_config']){
                    $versi->status = 'Choosed';
                    $versi->reason = $request['reason'];
                    $projectType = $versi->project_type;
                    $nominal = $versi->nominal;
                }else{
                    $versi->status = 'New';
                }
                $versi->save();
            }
            $nominalFinal = str_replace(',', '.', $nominal); // Pastikan menggunakan . sebagai pemisah desimal
            $nominalFinal = floatval($nominalFinal);

            $quoteActivity = new QuoteActivity();
            $quoteActivity->id_quote = $quotation->id_quote;
            $quoteActivity->operator = Auth::user()->name;
            $quoteActivity->activity = 'Quotation version '. $request['version'] .' ( '.$projectType.', '.number_format($nominalFinal, 2, ',', '.').' ) Rejected by  '. Auth::user()->name;
            $quoteActivity->status = 'REJECTED';
            $quoteActivity->date_add = Carbon::now();
            $quoteActivity->save();
            $this->sendEmailAction($quotation->status, $quotation->nik, $quotation, '[SIMS APP] Rejected Quotation');
            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();
            Log::error('message : '.$e->getMessage());
            Log::error($e->getTraceAsString());
        }
    }

    public function getDetailQuote(Request $request)
    {
        $id_quote = $request['id_quote'];
        $quote = Quote::find($id_quote);
        $quoteConfig = QuoteConfig::where('id_quote', $id_quote)->where('version', $request['versi'])->first();
        $quoteProduct = QuoteConfigProduct::where('id_config', $quoteConfig->id)
            ->join('tb_quote_product as a', 'tb_quote_config_product.id_product', 'a.id')
            ->get();

        return collect([
            'quote'  => $quote,
            'product' => $quoteProduct,
            'config' => $quoteConfig
        ]);
    }

    public function getActivity(Request $request)
    {
        $data = QuoteActivity::where('id_quote', $request['id_quote'])->orderByDesc('id')->get();

        return collect(['data' => $data]);
    }

    public function generatePDF(Request $request)
    {
        $config = QuoteConfig::join('tb_quote as a', 'tb_quote_config.id_quote', 'a.id_quote')
            ->select('a.from', 'a.to', 'a.no_telp', 'a.quote_number', 'a.title', 'a.term_payment', 'a.address', 'a.building', 'a.street','a.city',
                'tb_quote_config.email','tb_quote_config.project_type', 'tb_quote_config.attention', 'tb_quote_config.nominal', 'tb_quote_config.tax_vat',
                'a.nik', 'tb_quote_config.id','a.date', 'a.sign','a.parent_drive_id','is_uploaded','a.id_quote')
            ->where('tb_quote_config.id_quote', $request['id_quote'])
            ->where('tb_quote_config.status', 'Choosed')
            ->first();
        $quotation = Quote::find($config->id_quote);
        $product = QuoteConfigProduct::join('tb_quote_product as a', 'tb_quote_config_product.id_product', 'a.id')
            ->where('id_config', $config->id)
            ->select('a.*')
            ->get();
        $role = $this->cekRole($config->nik);
        $getTerritory = DB::table('users')->where('nik', $config->nik)->first()->id_territory;
        if ($getTerritory == 'TERRITORY 1'){
            $territory = 'Territory 1';
        }else if($getTerritory == 'TERRITORY 2'){
            $territory = 'Territory 2';
        }else if($getTerritory == 'TERRITORY 3'){
            $territory = 'Territory 3';
        }

        $data = [
            'config' => $config,
            'product' => $product,
            'role' => $role,
            'territory' => $territory ?? null
        ];

        if ($request['lang'] == 'id'){
            $pdf =  PDF::loadView('sales.quotation_pdf', $data);
        }else if($request['lang'] == 'en'){
            $pdf =  PDF::loadView('sales.quotation_pdf_english', $data);
        }

        if ($config->sign != null){
            if ($config->is_uploaded == null || $config->is_uploaded == '0'){
                if ($config->parent_drive_id == null){
                    $parentId = $this->googleDriveMakeFolder($config->quote_number);
                    $quotation->update(['parent_drive_id' => $parentId]);
                }else{
                    $parentId = $config->parent_drive_id;
                }
                $uploadpdf = $this->googleDriveUploadPdf('Quotation.pdf', $pdf->output(),$parentId);
                if ($uploadpdf){
                    $quotation->update(['pdf_url' => $uploadpdf, 'is_uploaded' => 1]);
                }
            }
        }

        return $pdf->stream('Quotation.pdf');
    }

    public function store(Request $request)
    {

        $tahun = date("Y");
        $cek = DB::table('tb_quote')
                // ->where('date','like',$tahun."%")
                ->whereYear('created_at', $tahun)
                ->count('id_quote');

        $type = 'QO';
        $posti = $request['position'];

        $edate = strtotime($_POST['date']); 
        $edate = date("Y-m-d",$edate);
        $month_quote = substr($edate,5,2);
        $year_quote = substr($edate,0,4);

        $array_bln = array('01' => "I",
                            '02' => "II",
                            '03' => "III",
                            '04' => "IV",
                            '05' => "V",
                            '06' => "VI",
                            '07' => "VII",
                            '08' => "VIII",
                            '09' => "IX",
                            '10' => "X",
                            '11' => "XI",
                            '12' => "XII");
        $bln = $array_bln[$month_quote];

        if ($cek > 0) {
            
            $quote = Quote::where('status_backdate','A')->orderBy('id_quote','desc')->whereYear('created_at',$tahun)->first()->quote_number;

            $getnumber =  explode("/",$quote)[0];

            // $nom = Quote::select('id_quote')->orderBy('created_at','desc')->whereYear('created_at', $tahun)->first()->id_quote;

            // $skipNum = Quote::select('quote_number')->orderBy('created_at','desc')->first();

            $lastnumber = $getnumber+1;

            $lastnumber9 = $getnumber+2;

            if($lastnumber < 10){
               $akhirnomor  = '000' . $lastnumber;
               $akhirnomor9 = '000' . $lastnumber9;
            }elseif($lastnumber > 9 && $lastnumber < 100){
               $akhirnomor = '00' . $lastnumber;
               $akhirnomor9 = '00' . $lastnumber9;
            }elseif($lastnumber >= 100){
               $akhirnomor = '0' . $lastnumber;
               $akhirnomor9 = '0' . $lastnumber9;
            }         

            // return substr($getnumber, -1);   

            if (substr($getnumber, -1) == '4') {
                $no   = $akhirnomor9.'/'.$posti .'/'. $type.'/' . $bln .'/'. $year_quote;

                $no9  = $akhirnomor;

                if (Quote::where('quote_number', '=', $no9)->exists()) {
                    $tambah = new Quote();
                        
                    $tambah->quote_number = $no;
                    $tambah->status_backdate = 'A';
                    $tambah->position = $posti;
                    $tambah->type_of_letter = $type;
                    $tambah->month = $bln;
                    $tambah->date = $edate;
                    $tambah->id_customer = $request['customer_quote'];
                    $tambah->attention = $request['attention'];
                    $tambah->title = $request['title'];
                    $tambah->project = $request['project'];
                    $tambah->description = $request['description'];
                    $tambah->nik = Auth::User()->nik;
                    $tambah->division = $request['division'];
                    $tambah->project_id = $request['project_id'];
                    $tambah->project_type = $request['project_type'];

                    $tambah->save();
                }else{
                    for ($i=0; $i < 2 ; $i++) { 
                        $tambah = new Quote();
                        
                        if ($i == 0) {
                            // $tambah->no = $nom+1;
                            $tambah->quote_number = $no9;
                            $tambah->status_backdate = 'T';
                        }else{
                            // $tambah->no = $nom+2;
                            $tambah->quote_number = $no;
                            $tambah->status_backdate = 'A';
                        }
                        $tambah->position = $posti;
                        $tambah->type_of_letter = $type;
                        $tambah->month = $bln;
                        $tambah->date = $edate;
                        $tambah->id_customer = $request['customer_quote'];
                        $tambah->attention = $request['attention'];
                        $tambah->title = $request['title'];
                        $tambah->project = $request['project'];
                        $tambah->description = $request['description'];
                        $tambah->nik = Auth::User()->nik;
                        $tambah->division = $request['division'];
                        $tambah->project_id = $request['project_id'];
                        $tambah->project_type = $request['project_type'];

                        $tambah->save();
                    }
                }

            }else {
                $no   = $akhirnomor.'/'.$posti .'/'. $type.'/' . $bln .'/'. $year_quote;

                $tambah = new Quote();
                $tambah->quote_number = $no;
                $tambah->position = $posti;
                $tambah->type_of_letter = $type;
                $tambah->month = $bln;
                $tambah->date = $edate;
                $tambah->id_customer = $request['customer_quote'];
                $tambah->attention = $request['attention'];
                $tambah->title = $request['title'];
                $tambah->project = $request['project'];
                $tambah->description = $request['description'];
                $tambah->nik = Auth::User()->nik;
                $tambah->status_backdate = 'A';
                $tambah->division = $request['division'];
                $tambah->project_id = $request['project_id'];
                $tambah->project_type = $request['project_type'];
                $tambah->save();  
            }

            
        } else{

            $getlastnumber = 1;
            $lastnumber = $getlastnumber;

            if($lastnumber < 10){
               $akhirnomor = '000' . $lastnumber;
            }elseif($lastnumber > 9 && $lastnumber < 100){
               $akhirnomor = '00' . $lastnumber;
            }elseif($lastnumber >= 100){
               $akhirnomor = '0' . $lastnumber;
            }

            $noReset = $akhirnomor.'/'.$posti .'/'. $type.'/' . $bln .'/'. $year_quote;

            $tambah = new Quote();
            $tambah->quote_number = $noReset;
            $tambah->position = $posti;
            $tambah->type_of_letter = $type;
            $tambah->month = $bln;
            $tambah->date = $edate;
            $tambah->id_customer = $request['customer_quote'];
            $tambah->attention = $request['attention'];
            $tambah->title = $request['title'];
            $tambah->project = $request['project'];
            $tambah->description = $request['description'];
            $tambah->nik = Auth::User()->nik;
            $tambah->status_backdate = 'A';
            $tambah->division = $request['division'];
            $tambah->project_id = $request['project_id'];
            $tambah->project_type = $request['project_type'];
            $tambah->save();          

            
        }

        return redirect('quoteIndex')->with('success', 'Create Quote Number Successfully!');
    }

    public function addBackdateNum(Request $request)
    {
        $lastnumber = Quote::whereYear('created_at',substr($request->date_backdate, 6,4))->where('status_backdate', 'F')->orderBy('id_quote', 'desc')->first()->quote_number;

        $getquote =  explode("/",$lastnumber)[0];

        $getnumber = $getquote + 10;

        if($getnumber < 10){
           $akhirnomor  = '000' . $getnumber;
        }elseif($getnumber > 9 && $getnumber < 100){
           $akhirnomor = '00' . $getnumber;
        }elseif($getnumber >= 100){
           $akhirnomor = '0' . $getnumber;
        }     

        $edate = strtotime($_POST['date_backdate']); 
        $edate = date("Y-m-d",$edate);

        $tambah = new Quote();
        $tambah->quote_number = $akhirnomor;
        $tambah->status_backdate = 'T';
        $tambah->position = 'DIR';
        $tambah->type_of_letter = 'QO';
        $tambah->month = 'II';
        $tambah->date = $edate;
        $tambah->created_at = $edate . '00:00:00';
        $tambah->project_id = $request['project_id_backdate'];
        $tambah->nik = Auth::User()->nik;
        $tambah->id_customer = '2';
        $tambah->save();
    }

    public function store_backdate(Request $request)
    {
        $type = 'QO';
        $posti = $request['position'];
        
        $edate = strtotime($_POST['date']); 
        $edate = date("Y-m-d",$edate);

        $month_quote = substr($edate,5,2);
        $year_quote = substr($edate,0,4);

        $array_bln = array('01' => "I",
                            '02' => "II",
                            '03' => "III",
                            '04' => "IV",
                            '05' => "V",
                            '06' => "VI",
                            '07' => "VII",
                            '08' => "VIII",
                            '09' => "IX",
                            '10' => "X",
                            '11' => "XI",
                            '12' => "XII");
        $bln = $array_bln[$month_quote];

        /*$query = Quote::select('id_quote')
                        ->where('status_backdate','T')
                        ->orderBy('id_quote','asc')
                        ->first();
        
        $lastnumber = $query->id_quote;

        if($lastnumber < 10){
           $akhirnomor = '00' . $lastnumber;
        }elseif($lastnumber > 9 && $lastnumber < 100){
           $akhirnomor = '0' . $lastnumber;
        }elseif($lastnumber >= 100){
           $akhirnomor = $lastnumber;
        }*/

        // $akhirnomor = $request['backdate_num'];

        // $no = $akhirnomor.'/'.$posti .'/'. $type.'/' . $bln .'/'. $year_quote;

        // $angka7 = Quote::select('id_quote')
        //         ->where('status_backdate','T')
        //         ->orderBy('id_quote','asc')
        //         ->first();

        // $angka = $angka7->id_quote;

        // $update = Quote::where('id_quote',$akhirnomor)->first();
        $update = Quote::where('id_quote',$request['backdate_num'])->first();
        $no = $update->quote_number .'/'.$posti .'/'. $type.'/' . $bln .'/'. $year_quote;
        $update->quote_number = $no;
        $update->position = $posti;
        $update->type_of_letter = $type;
        $update->month = $bln;
        $update->date = $edate;
        // $update->to = $request['to'];
        $update->id_customer = $request['customer_quote_backdate'];
        $update->attention = $request['attention'];
        $update->title = $request['title'];
        $update->project = $request['project'];
        $update->description = $request['description'];
        $update->nik = Auth::User()->nik;
        $update->division = $request['division'];
        $update->project_id = $request['project_id_backdate'];
        $update->status_backdate = 'F';
        $update->project_type = $request['project_type'];
        $update->update();

        return redirect('quote')->with('sukses', 'Create Quote Number Successfully!');
    }

	public function update(Request $request)
	{
        $quote_number = $request['quote_number'];
        $posti = $request['edit_position'];

        $edate = strtotime($_POST['edit_date']); 
        $edate = date("Y-m-d",$edate);

        $month_quote = substr($edate,5,2);
        $year_quote = substr($edate,0,4);

        $array_bln = array('01' => "I",
                    '02' => "II",
                    '03' => "III",
                    '04' => "IV",
                    '05' => "V",
                    '06' => "VI",
                    '07' => "VII",
                    '08' => "VIII",
                    '09' => "IX",
                    '10' => "X",
                    '11' => "XI",
                    '12' => "XII");
        $bln = $array_bln[$month_quote];

        $getno = Quote::where('quote_number', $quote_number)->first()->quote_number;
        $getnumberQuote =  explode("/",$getno)[0];

        // return $getnumberQuote;

        $no_update = $getnumberQuote.'/'.$posti .'/QO/' . $bln .'/'. $year_quote;

        $update = Quote::where('quote_number', $quote_number)->first();
        $update->quote_number = $no_update;
        $update->position = $posti;
        $update->month = $bln;
        $update->date = $edate;
        $update->id_customer = $request['edit_to'];
        $update->attention = $request['edit_attention'];
        $update->title = $request['edit_title'];
        $update->project = $request['edit_project'];
        $update->description = $request['edit_description'];
        $update->project_id = $request['edit_project_id'];
        $update->note = $request['edit_note'];
        $update->update();

        return redirect('quote')->with('update', 'Update Quote Number Successfully!');
	}

    public function destroy_quote(Request $request)
    {
        $hapus = Quote::find($request->id_quote);
        $hapus->delete();

        return redirect()->back();
    }

    public function report_quote()
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        $datas = DB::table('tb_quote')
                        ->select('id_quote','quote_number','position','type_of_letter','date','to','attention','title','project')
                        ->get();

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
        
        return view('report/quote',compact('notif','datas','notifOpen','notifsd','notiftp', 'notifClaim'));
    }

    public function donwloadExcelQuote(Request $request)
    {
    	$nama = 'Daftar Buku Admin (Quo) '.date('Y');
        Excel::create($nama, function ($excel) use ($request) {
        $excel->sheet('Quote Number', function ($sheet) use ($request) {
        
        $sheet->mergeCells('A1:O1');

        $sheet->row(1, function ($row) {
            $row->setFontFamily('Calibri');
            $row->setFontSize(11);
            $row->setAlignment('center');
            $row->setFontWeight('bold');
        });

        $sheet->row(1, array('Quote Number'));

        $sheet->row(2, function ($row) {
            $row->setFontFamily('Calibri');
            $row->setFontSize(11);
            $row->setFontWeight('bold');
        });

        $datas = Quote::join('users', 'users.nik', '=', 'tb_quote.from')
                    ->select('quote_number','position','type_of_letter', 'month', 'date', 'to', 'attention', 'title','project','description','name','division','project_id')
                    ->get();

       // $sheet->appendRow(array_keys($datas[0]));
            $sheet->row($sheet->getHighestRow(), function ($row) {
                $row->setFontWeight('bold');
            });

             $datasheet = array();
             $datasheet[0]  =   array("No", "No Quote", "Position", "Type of Letter", "Month",  "Date", "To" , "Attention", "Title", "Project", "Description", "From", "Division","Id Project");
             $i=1;

            foreach ($datas as $data) {

               // $sheet->appendrow($data);
              $datasheet[$i] = array(
                            $i,
                            $data['quote_number'],
                            $data['position'],
                            $data['type_of_letter'],
                            $data['month'],
                            $data['date'],
                            $data['to'],
                            $data['attention'],
                            $data['title'],
                            $data['project'],
                            $data['description'],
                            $data['name'],
                            $data['division'],
                            $data['project_id'],
                        );
              
              $i++;
            }

            $sheet->fromArray($datasheet);
        });

        })->export('xls');
    }

    public function saveSignature(Request $request)
    {
        $quote = Quote::find($request['id_quote']);
        $quote->sign = $request['signature'];
        $quote->save();
    }

    public function cekRole($nik)
    {
        $role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('name', 'roles.group')->where('user_id', $nik)->first();

        return $role;
    }

    public function sendEmail($status, $role, $nik, $action, $detail, $subject)
    {
        $sender = DB::table('users')->where('nik', $nik)->first();
        $userToSend = DB::table('roles as r')->join('role_user as ru', 'r.id', 'ru.role_id')
            ->join('users as u', 'ru.user_id', 'u.nik');

        if($role == 'Account Executive'){
            $userToSend = $userToSend->where('r.name', 'VP Sales')->where('u.id_territory', $sender->id_territory);
        }else if($role == 'VP Sales'){
            $userToSend = $userToSend->where('r.name', 'Chief Executive Officer');
        }else if($role == 'Technology Alliance Solutions'){
            $userToSend = $userToSend->where('r.name', 'VP Solutions & Partnership Management');
        }else if($role == 'VP Solutions & Partnership Management'){
            $userToSend = $userToSend->where('r.name', 'Chief Operating Officer');
        }

        if ($status == 'ON GOING' && $action == 'Store New'){
            $status = 'NEW';
        } else if($status == 'ON GOING' && $action == 'Edit'){
            $status = 'EDIT';
        }

        $config = QuoteConfig::where('id_quote', $detail->id_quote)->where('status', 'Choosed')->first();

        $userToSend = $userToSend->select('u.name', 'u.nik', 'u.email')->first();

        Mail::to($userToSend->email)->send(new MailQuotation($subject, $detail, $userToSend, $status, null, $config));
    }

    public function sendEmailAction($status, $nik, $detail, $subject)
    {
        $userToSend = DB::table('users')->where('nik', $nik)->first();

        $config = QuoteConfig::where('id_quote', $detail->id_quote)->where('status', 'Choosed')->first();
        $notes = null;
        if ($status == 'REJECTED'){
            $notes = $config->reason;
        }
//        $userToSend = $userToSend->select('u.email', 'u.nik', 'u.name')->first();

        Mail::to($userToSend->email)->send(new MailQuotation($subject, $detail, $userToSend, $status, $notes, $config));
    }

    public function getCount()
    {
        $nik = Auth::user()->nik;
        $role = $this->cekRole($nik);
        if($role->name == 'VP Solutions & Partnership Management'){
            $roleTA = DB::table('roles as r')
                ->join('role_user as ru', 'r.id', 'ru.role_id')
                ->whereIn('r.name', ['Technology Alliance Solutions', 'VP Solutions & Partnership Management'])
                ->select('ru.user_id as nik')
                ->get();
            $nikList = $roleTA->pluck('nik')->toArray();

            $countAll = Quote::whereIn('nik', $nikList);
            $countDone = Quote::whereIn('nik', $nikList)->where('status', 'APPROVED');
            $countNeedAttention = Quote::whereIn('nik', $nikList)->where('status', 'ON GOING');
            $countOngoing = Quote::whereIn('nik', $nikList)->where('status', 'REJECTED');
        }else if($role->name == 'VP Sales'){
            $roleSalesByTerritory = DB::table('roles as r')
                ->join('role_user as ru', 'r.id', 'ru.role_id')
                ->join('users as u', 'ru.user_id', 'u.nik')
                ->where('u.id_territory', Auth::user()->id_territory)
                ->whereIn('r.name', ['Account Executive', 'VP Sales'])
                ->select('ru.user_id as nik')->get();
            $nikList = $roleSalesByTerritory->pluck('nik')->toArray();
            $countAll = Quote::whereIn('nik', $nikList);
            $countDone = Quote::whereIn('nik', $nikList)->where('status', 'APPROVED');
            $countNeedAttention = Quote::whereIn('nik', $nikList)->where('status', 'ON GOING');
            $countOngoing = Quote::whereIn('nik', $nikList)->where('status', 'REJECTED');
        }else if($role->name == 'Chief Operating Officer'){

            $countAll = Quote::all();
            $countDone = Quote::where('status', 'APPROVED');
            $countNeedAttention = Quote::where('status', 'ON GOING');
            $countOngoing = Quote::where('status', 'ON GOING');
        }else if($role->name == 'Chief Executive Officer'){
            $countAll = Quote::all();
            $countDone = Quote::where('status', 'APPROVED');
            $countNeedAttention = Quote::where('status', 'ON GOING');
            $countOngoing = Quote::where('status', 'ON GOING');
        }else{
            $countAll = Quote::where('nik', $nik);
            $countDone = Quote::where('nik', $nik)->where('status', 'APPROVED');
            $countNeedAttention = Quote::where('nik', $nik)->where('status', 'REJECTED');
            $countOngoing = Quote::where('nik', $nik)->whereIn('status', ['ON GOING', 'SAVED']);
        }

        return collect([
           'count_all' => $countAll->count(),
           'count_done' => $countDone->count(),
           'count_need_attention'  => $countNeedAttention->count(),
            'count_ongoing' => $countOngoing->count()
        ]);

    }
    public function getCountFilter()
    {
        $nik = Auth::user()->nik;
//        $role = $this->cekRole($nik);

        $countAll = Quote::where('nik', $nik);
        $countDone = Quote::where('nik', $nik)->where('status', 'Done');
        $countNeedAttention = Quote::where('nik', $nik)->where('status', 'Need Attention');
        $countOngoing = Quote::where('nik', $nik)->where('status', 'On Going');

        return collect([
           'count_all' => $countAll->count(),
           'count_done' => $countDone->count(),
           'count_need_attention'  => $countNeedAttention->count(),
            'count_ongoing' => $countOngoing->count()
        ]);

    }

    public function getPDF($id)
    {
        $config = QuoteConfig::join('tb_quote as a', 'tb_quote_config.id_quote', 'a.id_quote')
            ->where('tb_quote_config.id', $id)->where('tb_quote_config.status', 'Choosed')->first();
        $product = QuoteConfigProduct::join('tb_quote_product as a', 'tb_quote_config_product.id_product', 'a.id')
            ->where('id_config', $config->id)
            ->select('a.*')
            ->get();
        $role = $this->cekRole($config->nik);
        $getTerritory = DB::table('users')->where('nik', $config->nik)->first()->id_territory;
        if ($getTerritory == 'TERRITORY 1'){
            $territory = 'Territory 1';
        }else if($getTerritory == 'TERRITORY 2'){
            $territory = 'Territory 2';
        }else if($getTerritory == 'TERRITORY 3'){
            $territory = 'Territory 3';
        }

        $data = [
            'config' => $config,
            'product' => $product,
            'role' => $role,
            'territory' => $territory ?? null
        ];

        $pdf =  PDF::loadView('sales.quotation_pdf', $data);

        return $pdf->output();
    }

    public function sendMailtoCustomer(Request $request)
    {
        $body = $request->body;
        $to = $request->to;
        $cc = $request->cc;
        $subject = $request->subject;
        $pdf = $this->getPDF($request->id);
        $config = QuoteConfig::find($request->id);
        $quote = Quote::find($config->id_quote);
        $mail = Mail::html($body, function ($message) use ($to, $cc, $subject, $pdf) {
            $message
                ->to(explode(";", $to))
                ->subject($subject);

            if($cc != ""){
                $message->cc(explode(";", $cc));
            }
            $message->attachData($pdf, "Quotation.pdf", [
                'mime' => 'application/pdf',
            ]);
        });
        if ($mail){
            $quote->update(['is_sended' => 1]);

        }


    }

    public function getClient()
    {
        $client = new Google_Client();
        $client->setApplicationName('Google Drive API PHP Quickstart');
        $client->setAuthConfig(env('AUTH_CONFIG'));
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');
        // $client->setScopes("https://www.googleapis.com/auth/drive");
        $client->setScopes(Google_Service_Drive::DRIVE_READONLY);

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

    public function googleDriveMakeFolder($nameFolder){
        $client_folder = $this->getClient();
        $service_folder = new Google_Service_Drive($client_folder);

        $file = new Google_Service_Drive_DriveFile();
        $file->setName($nameFolder);
        $file->setMimeType('application/vnd.google-apps.folder');
        $file->setDriveId(env('GOOGLE_DRIVE_DRIVE_ID'));
        $file->setParents([env('GOOGLE_DRIVE_PARENT_ID_Quotation')]);

        $result = $service_folder->files->create(
            $file,
            array(
                'mimeType' => 'application/octet-stream',
                'uploadType' => 'multipart',
                'supportsAllDrives' => true
            )
        );

        return $result->id;
    }

    public function googleDriveUploadPdf($fileName,$pdf,$parentID){
        try {
            $client = $this->getClient();
            $service = new Google_Service_Drive($client);

            $file = new Google_Service_Drive_DriveFile();
            $file->setName($fileName);
            $file->setParents([$parentID]);

            $result = $service->files->create(
                $file,
                [
                    'data' => $pdf,
                    'mimeType' => 'application/pdf',
                    'uploadType' => 'multipart',
                    'supportsAllDrives' => true
                ]
            );

            $fileId = $result->id;

            return $fileId;

        } catch (\Exception $e) {
            Log::error('Google Drive upload error: ' . $e->getMessage());
            return false;
        }
    }
}
