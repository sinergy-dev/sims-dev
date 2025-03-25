<?php

namespace App\Http\Controllers;

use App\IdeaHubPoint;
use App\Mail\MailIdeaHub;
use Illuminate\Http\Request;
use App\Sales;
use Auth;
use Charts;
use App\IdeaHub;
use App\IdeaHubActivity;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class DASHBOARDController extends Controller
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
        $pos = '';$div = '';$results = '';$idps = '';$counts = '';$opens = '';$sds = '';$tps = '';$notiftp = '';$notifsd = '';$notifOpen = '';$wins = '';$loses = '';$notif = '';$notifClaim = '';$win1 = '';$win2 = '';$lose1 = '';$lose2 = '';$ba = '';$co = '';$lead_win = '';$top_win_sip = '';$top_win_msp = '';$loop_year = '';$year_now = '';$countmsp = '';$losemsp = '';$top_win_sip_ter;
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;
        $company = DB::table('users')->select('id_company')->where('nik', $nik)->first();
        $com = $company->id_company;

        $loop_year = DB::table('sales_lead_register')
                    ->select('year')->groupBy('year')->orderBy('year','desc')->get();
        // TOP 5

        $year_now = DATE('Y');

        $top_win_sip = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                        ->select(DB::raw('COUNT(sales_lead_register.lead_id) as leads'), DB::raw('SUM(sales_lead_register.amount) as amounts'), DB::raw('SUM(sales_lead_register.deal_price) as deal_prices'), 'users.name', 'tb_company.code_company','users.nik','users.id_territory')
                        ->where('result', 'WIN')
                        ->whereYear('closing_date', $year_now)
                        ->where('users.id_company', '1')
                        ->where('users.status_karyawan', '!=', 'dummy')
                        ->groupBy('sales_lead_register.nik')
                        ->orderBy('deal_prices', 'desc')
                        ->take(5)
                        ->get();

        $top_win_msp = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                        ->select(DB::raw('COUNT(sales_lead_register.lead_id) as leads'), DB::raw('SUM(sales_lead_register.amount) as amounts'), DB::raw('SUM(sales_lead_register.deal_price) as deal_prices'), 'users.name', 'tb_company.code_company','users.nik')
                        ->where('result', 'WIN')
                        ->whereYear('closing_date', $year_now)
                        ->where('users.id_company', '2')
                        ->where('users.status_karyawan', '!=', 'dummy')
                        ->groupBy('sales_lead_register.nik')
                        ->orderBy('deal_prices', 'desc')
                        ->take(5)
                        ->get();

        if ($div == 'SALES') {
            $top_win_sip_ter = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_territory','tb_territory.id_territory','=','users.id_territory')
                        ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                        ->select(DB::raw('COUNT(sales_lead_register.lead_id) as leads'), DB::raw('SUM(sales_lead_register.amount) as amounts'), DB::raw('SUM(sales_lead_register.deal_price) as deal_prices'), 'users.name', 'tb_company.code_company','users.nik','users.id_territory')
                        ->where('result', 'WIN')
                        ->whereYear('closing_date', $year_now)
                        ->where('users.id_company', '1')
                        ->where('users.id_territory', $ter)
                        ->where('users.status_karyawan', '!=', 'dummy')
                        ->groupBy('users.id_territory','sales_lead_register.nik')
                        ->orderBy('deal_prices', 'desc')
                        ->take(5)
                        ->get()->toArray();
        }else{
            $sum_amounts_ter = DB::table('sales_lead_register')
                            ->join('users','users.nik','=','sales_lead_register.nik')
                            ->select(DB::raw('SUM(sales_lead_register.amount) as sum_amounts'),DB::raw('COUNT(sales_lead_register.lead_id) as leads_total'),'id_territory')
                            // ->where('users.id_territory','!=','OPERATION')
                            ->whereYear('closing_date', $year_now)
                            ->where('result', 'WIN')
                            ->where('users.status_karyawan', '!=', 'dummy')
                            ->where('users.id_company', '1')
                            ->groupBy('id_territory');

            $top_win_sip_ter_ter = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_territory','tb_territory.id_territory','=','users.id_territory')
                        ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                        ->joinSub($sum_amounts_ter,'sum_amounts_ter',function($join){
                            $join->on('sum_amounts_ter.id_territory','=','users.id_territory');
                        })
                        ->select(DB::raw('COUNT(sales_lead_register.lead_id) as leads'), DB::raw('SUM(sales_lead_register.amount) as amounts'), DB::raw('SUM(sales_lead_register.deal_price) as deal_prices'), 'users.name', 'tb_company.code_company','users.id_territory','users.nik')
                        ->selectRaw('`sum_amounts_ter`.`sum_amounts` AS `sum_total`')
                        ->selectRaw('`sum_amounts_ter`.`leads_total` AS `leads_total`')
                        ->where('result', 'WIN')
                        // ->where('users.id_territory','!=','OPERATION')
                        ->whereYear('closing_date', $year_now)
                        ->where('users.status_karyawan', '!=', 'dummy')
                        ->where('users.id_company', '1')
                        ->groupBy('users.nik')
                        ->orderBy('deal_prices', 'desc')
                        ->get();

            // return $sum_amounts_ter->get();

           $top_win_sip_ter_ter->push([
                "leads" => $sum_amounts_ter->get()->sum('leads_total'),
                "amounts" => (string)$sum_amounts_ter->get()->sum('sum_amounts'),
                "deal_prices" => (string)$sum_amounts_ter->get()->sum('sum_amounts'),
                "name" => "Total All Teritory",
                "code_company" => "SIP",
                "id_territory" => "TOTAL",
                "nik" => "-",
                "sum_total" => (string)$sum_amounts_ter->get()->sum('sum_amounts'),
                "leads_total" => $sum_amounts_ter->get()->sum('leads_total')
            ]);
            // return $top_win_sip_ter_ter->sum('sum_total');
            // return $deals = $top_win_sip_ter_ter->sum(function ($region) {
            //     return $region->submits->sum('deals');
            // });

            $groups = collect($top_win_sip_ter_ter)->sortBy('id_territory',SORT_NATURAL)->groupBy('id_territory');

            $groups2 = collect($sum_amounts_ter)->sortBy('id_territory',SORT_NATURAL)->groupBy('sum_amounts');

            $top_win_sip_ter = $groups->toArray();
        }

        // return $top_win_sip_ter;

        // count id project
        if($div == 'FINANCE' && $pos == 'MANAGER'){
            $idp = DB::table('tb_id_project')
                ->get();
            $idps = count($idp);
        }

        //count lead
        if($div == 'SALES' && $pos != 'ADMIN'){
            $count = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('id_territory', $ter)
                ->where('id_company', '1')
                ->whereYear('closing_date',$year_now)
                ->get();
            $counts = count($count);
        
        } elseif($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $count = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'sales_lead_register.opp_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('sales_solution_design.nik', $nik)
                ->where('id_company', '1')
                ->whereYear('closing_date',$year_now)
                ->get();
            $counts = count($count);
        
        } elseif($div == 'TECHNICAL PRESALES' && $pos == 'MANAGER') {
            $count = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'sales_lead_register.opp_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('id_company', '1')
                ->whereYear('closing_date',$year_now)
                ->get();
            $counts = count($count);
        
        } elseif ($pos == 'ADMIN') {
            $count = DB::table('dvg_esm')
                    ->join('users', 'users.nik', '=', 'dvg_esm.personnel')
                    ->select('no','date','users.name', 'type', 'description', 'amount', 'id_project', 'remarks', 'status')
                    ->where('status', 'ADMIN')
                    ->where('nik_admin', $nik)
                    ->where('id_company', '1')
                    ->whereYear('dvg_esm.created_at',$year_now)
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
                ->whereYear('closing_date',$year_now)
                ->get();
            $counts = count($count);
        
        } elseif($pos == 'ENGINEER MANAGER') {
            $count = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.nik','sales_lead_register.status_engineer')
                ->where('sales_lead_register.result','WIN')
                ->where('sales_lead_register.status_sho','PMO')
                ->where('id_company', '1')
                ->whereYear('closing_date',$year_now)
                ->get();
            $counts = count($count);
        
        } elseif($pos == 'ENGINEER STAFF') {
            $count = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('tb_engineer','sales_lead_register.lead_id','=','tb_engineer.lead_id')
                ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.opp_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho','sales_lead_register.nik','sales_lead_register.status_engineer')
                ->where('sales_lead_register.result','WIN')
                ->where('tb_engineer.nik',$nik)
                ->where('id_company', '1')
                ->whereYear('closing_date',$year_now)
                ->get();
            $counts = count($count);
        
        } elseif ($div == 'PMO' && $pos == 'STAFF') {
            $count = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('tb_pmo', 'tb_pmo.lead_id', '=', 'sales_lead_register.lead_id')
                ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'sales_lead_register.opp_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('tb_pmo.pmo_nik', $nik)
                ->where('id_company', '1')
                ->whereYear('closing_date',$year_now)
                ->get();
            $counts = count($count);
        
        } elseif ($div == 'PMO' && $pos == 'MANAGER') {
            $count = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->whereYear('closing_date',$year_now)
                ->where('id_company', '1')
                ->where('result','!=','hmm')
                ->get();
            $counts = count($count);
        
        } else {
            $count = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->whereYear('closing_date',$year_now)
                ->where('result','!=','hmm')
                ->get();
            $counts = count($count);

            //count WO
            if ($div == 'WAREHOUSE') {
                $counts = DB::table('tb_po')
                        ->count('no');

                $opens = DB::table('tb_do')
                        ->count('no');

                $ba = DB::table('detail_inventory_produk')
                        ->where('status','P')
                        ->count('id_detail');

                $co = DB::table('inventory_produk')
                        ->count();
            }
        
        }
        
        // count status open
        if($div == 'SALES' && $pos != 'ADMIN'){
            $open = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result', '')
                ->whereYear('closing_date',$year_now)
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
                ->whereYear('closing_date',$year_now)
                ->get();
            $opens = count($open);
        } elseif($div == 'TECHNICAL PRESALES' && $pos == 'MANAGER') {
            $open = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'sales_lead_register.opp_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result', '')
                ->whereYear('closing_date',$year_now)
                ->where('id_company','1')
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
                ->whereYear('closing_date',$year_now)
                ->get();
            $opens = count($open);
        } elseif ($div == 'TECHNICAL' && $ter == 'DPG') {
            $open = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'sales_lead_register.opp_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result3','DONE')
                ->whereYear('closing_date',$year_now)
                ->get();
            $opens = count($open);
        } elseif ($pos == 'ADMIN') {
            $open = DB::table('tb_pr')
                ->select('no')
                ->whereYear('created_at',$year_now)
                ->get();

            $opens = count($open);
        } elseif($div == 'PMO' && $pos == 'MANAGER') {
            $open = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result','')
                ->where('id_company', '1')
                ->whereYear('closing_date',$year_now)
                ->get();
            $opens = count($open);
        } else {
            $open = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result','')
                ->whereYear('closing_date',$year_now)
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
                ->whereYear('closing_date',$year_now)
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
                ->whereYear('closing_date',$year_now)
                ->get();
            $sds = count($sd);
        } elseif($div == 'TECHNICAL PRESALES' && $pos == 'MANAGER') {
            $sd = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'sales_lead_register.opp_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result', 'SD')
                ->where('id_company','1')
                ->whereYear('closing_date',$year_now)
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
                ->whereYear('closing_date',$year_now)

                    ->get();
            $sds = count($sd);
        } elseif ($div == 'TECHNICAL' && $ter == 'DPG') {
            $sd = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'sales_lead_register.opp_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result3','DONE')
                ->whereYear('closing_date',$year_now)
                ->get();
            $sds = count($sd);
        } elseif ($pos == 'ADMIN') {
            $sd = DB::table('tb_po')
                ->select('no')
                ->whereYear('created_at',$year_now)
                ->get();
            $sds = count($sd);
        } elseif($div == 'PMO' && $pos == 'MANAGER') {
            $sd = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result','SD')
                ->where('id_company', '1')
                ->whereYear('closing_date',$year_now)
                ->get();
            $sds = count($sd);
        } else {
            $sd = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result','SD')
                ->whereYear('closing_date',$year_now)
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
                ->whereYear('closing_date',$year_now)
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
                ->whereYear('closing_date',$year_now)
                ->get();
            $tps = count($tp);
        } elseif($div == 'TECHNICAL PRESALES' && $pos == 'MANAGER') {
            $tp = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'sales_lead_register.opp_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result', 'TP')
                ->where('id_company', '1')
                ->whereYear('closing_date',$year_now)
                ->get();
            $tps = count($tp);
        } elseif ($div == 'FINANCE') {
            $tp = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->join('tb_id_project', 'tb_id_project.lead_id', '=', 'sales_lead_register.lead_id')
                    ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'sales_lead_register.opp_name',
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                    ->where('sales_lead_register.lead_id','tb_id_project.lead_id')
                ->whereYear('closing_date',$year_now)

                    ->get();
            $tps = count($tp);
        } elseif ($div == 'TECHNICAL' && $ter == 'DPG') {
            $tp = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'sales_lead_register.opp_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result3','DONE')
                ->whereYear('closing_date',$year_now)
                ->get();
            $tps = count($tp);
        } elseif($div == 'PMO' && $pos == 'MANAGER') {
            $tp = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result','TP')
                ->where('id_company', '1')
                ->whereYear('closing_date',$year_now)
                ->get();
            $tps = count($tp);
        } else {
            $tp = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result','TP')
                ->whereYear('closing_date',$year_now)
                ->get();
            $tps = count($tp);
        }   

        // count status win
        if($div == 'SALES'){
            // $win = DB::table('sales_lead_register')
            //     ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
            //     ->select('sales_lead_register.lead_id','sales_lead_register.opp_name',
            //     'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
            //     ->where('result', 'WIN')
            //     // ->where('id_territory', $ter)
            //     ->whereYear('closing_date',date('Y'))
            //     ->get();
            // $wins = count($win);

            $wins = 11;

            // $winss = DB::table('sales_lead_register')
            //     ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
            //     ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
            //     ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name',
            //     'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
            //     ->where('result','WIN')
            //     ->whereYear('closing_date',$year_now)
            //     ->get();
            $win2 = 10;
        } elseif($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $win = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'sales_lead_register.opp_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result', 'WIN')
                ->where('sales_solution_design.nik', $nik)
                ->whereYear('closing_date',$year_now)
                ->get();
            $wins = count($win);

            $winss = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result','WIN')
                ->whereYear('closing_date',$year_now)
                ->get();
            $win2 = count($winss);
        } elseif($div == 'TECHNICAL PRESALES' && $pos == 'MANAGER') {
            $win = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'sales_lead_register.opp_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result', 'WIN')
                ->where('id_company','1')
                ->whereYear('closing_date',$year_now)
                ->get();
            $wins = count($win);

            $winss = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result','WIN')
                ->whereYear('closing_date',$year_now)
                ->where('id_company', '1')
                ->get();
            $win2 = count($winss);
        } elseif ($div == 'FINANCE') {
            $win = DB::table('dvg_esm')
                    ->join('users', 'users.nik', '=', 'dvg_esm.personnel')
                    ->select('no','date','users.name', 'type', 'description', 'amount', 'id_project', 'remarks', 'status')
                    ->where('status', 'FINANCE')
                    ->whereYear('dvg_esm.created_at',$year_now)
                    ->get();
            $wins = count($win);

            $winss = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result','WIN')
                ->whereYear('closing_date',$year_now)
                ->get();
            $win2 = count($winss);
        } elseif ($div == 'HR') {
            $win = DB::table('dvg_esm')
                    ->join('users', 'users.nik', '=', 'dvg_esm.personnel')
                    ->select('no','date','users.name', 'type', 'description', 'amount', 'id_project', 'remarks', 'status')
                    ->where('status', 'HRD')
                    ->whereYear('dvg_esm.created_at',$year_now)
                    ->get();

            $wins = count($win);

            $winss = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result','WIN')
                ->whereYear('closing_date',$year_now)
                ->get();
            $win2 = count($winss);
        } elseif($div == 'PMO' && $pos == 'MANAGER') {
            $win = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result','WIN')
                ->where('id_company', '1')
                ->whereYear('closing_date',$year_now)
                ->get();
            $wins = count($win);

            $winss = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result','WIN')
                ->where('id_company', '1')
                ->whereYear('closing_date',$year_now)
                ->get();
            $win2 = count($winss);
        } else {
            $win = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result','WIN')
                ->whereYear('closing_date',$year_now)
                ->get();
            $wins = count($win);

            $winss = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result','WIN')
                ->whereYear('closing_date',$year_now)
                ->get();
            $win2 = count($winss);
        }

        // count status lose
        if ($div == 'SALES' && $pos != 'ADMIN') {
            $lose = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result', 'LOSE')
                ->where('id_territory', $ter)
                ->whereYear('closing_date',$year_now)
                ->get();
            $loses = count($lose);

            $losess = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result','LOSE')
                ->whereYear('closing_date',$year_now)
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
                ->whereYear('closing_date',$year_now)
                ->get();
            $loses = count($lose);

            $losess = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result','LOSE')
                ->whereYear('closing_date',$year_now)
                ->get();
            $lose2 = count($losess);
        } elseif($div == 'TECHNICAL PRESALES' && $pos == 'MANAGER') {
            $lose = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'sales_lead_register.opp_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result', 'LOSE')
                ->where('id_company','1')
                ->whereYear('closing_date',$year_now)
                ->get();
            $loses = count($lose);

            $losess = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result','LOSE')
                ->where('id_company', '1')
                ->whereYear('closing_date',$year_now)
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
                ->whereYear('closing_date',$year_now)
                ->get();
            $lose2 = count($losess);
        } elseif ($div == 'HR') {
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
                ->whereYear('closing_date',$year_now)
                ->get();
            $lose2 = count($losess);
        } elseif ($pos == 'ADMIN') {
            $lose = DB::table('tb_quote')
                        ->select('id_quote','quote_number','position','type_of_letter','date','to','attention','title','project')
                        ->whereYear('created_at',$year_now)
                        ->get();

            $loses = count($lose);

            $losess = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result','LOSE')
                ->whereYear('closing_date',$year_now)
                ->get();
            $lose2 = count($losess);
        } elseif($div == 'PMO' && $pos == 'MANAGER') {
            $lose = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result','LOSE')
                ->where('id_company', '1')
                ->whereYear('closing_date',$year_now)
                ->get();
            $loses = count($lose);

            $losess = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result','LOSE')
                ->where('id_company', '1')
                ->whereYear('closing_date',$year_now)
                ->get();
            $lose2 = count($losess);
        } else {
            $lose = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result','LOSE')
                ->whereYear('closing_date',$year_now)
                ->get();
            $loses = count($lose);

            $losess = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('result','LOSE')
                ->whereYear('closing_date',$year_now)
                ->get();
            $lose2 = count($losess);
        }

        if ($div == 'SALES' && $pos != 'ADMIN') {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();
        } elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();
        } else{
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
        } elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $notifOpen= DB::table('sales_lead_register')
            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->select('sales_lead_register.opp_name','sales_solution_design.nik','sales_solution_design.lead_id')
            ->where('result','')
            ->orderBy('sales_lead_register.created_at','desc')
            ->get();
        } elseif ($div == 'SALES' && $pos == 'MANAGER') {
            $notifOpen= DB::table('sales_lead_register')
            ->select('opp_name','nik','lead_id')
            ->where('result','')
            ->orderBy('created_at','desc')
            ->get();
        } elseif ($div == 'SALES' && $pos == 'STAFF') {
            $notifOpen= DB::table('sales_lead_register')
            ->select('opp_name','nik','lead_id')
            ->where('result','')
            ->orderBy('created_at','desc')
            ->get();
        } else{
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
        } elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $notifsd= DB::table('sales_lead_register')
            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->select('sales_lead_register.opp_name','sales_solution_design.nik','sales_lead_register.lead_id')
            ->where('result','SD')
            ->orderBy('sales_lead_register.created_at','desc')
            ->get();
        } elseif ($div == 'SALES' && $pos == 'MANAGER') {
            $notifsd= DB::table('sales_lead_register')
            ->select('opp_name','nik','lead_id')
            ->where('result','SD')
            ->orderBy('created_at','desc')
            ->get();
        } elseif ($div == 'SALES' && $pos == 'STAFF') {
            $notifsd= DB::table('sales_lead_register')
            ->select('opp_name','nik','lead_id')
            ->where('result','SD')
            ->orderBy('created_at','desc')
            ->get();
        } else{
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
        } elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $notiftp= DB::table('sales_lead_register')
            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->select('sales_lead_register.opp_name','sales_solution_design.nik','sales_solution_design.lead_id')
            ->where('result','TP')
            ->orderBy('sales_lead_register.created_at','desc')
            ->get();
        } elseif ($div == 'SALES' && $pos == 'MANAGER') {
            $notiftp= DB::table('sales_lead_register')
            ->select('opp_name','nik','lead_id')
            ->where('result','TP')
            ->orderBy('created_at','desc')
            ->get();
        } elseif ($div == 'SALES' && $pos == 'STAFF') {
            $notiftp= DB::table('sales_lead_register')
            ->select('opp_name','nik','lead_id')
            ->where('result','TP')
            ->orderBy('created_at','desc')
            ->get();
        } else{
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

        // return $this->RoleDynamic('asset_atk');
        // return $top_win_sip_ter;

        return view('dashboard/dashboard', compact('pos','div','results','idps', 'counts','opens', 'sds', 'tps', 'notiftp', 'notifsd', 'notifOpen', 'wins', 'loses', 'notif', 'notifClaim','win1','win2','lose1','lose2','ba','co', 'lead_win', 'top_win_sip','top_win_sip_ter','top_win_msp','loop_year','year_now', 'countmsp', 'losemsp'))->with(['initView'=> $this->initMenuBase(),'feature_item'=>$this->RoleDynamic('Dashboard')]);

    }

    public function getDashboardBox(Request $request){
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;
        $company = DB::table('users')->select('id_company')->where('nik', $nik)->first();
        $com = $company->id_company;

        // $year = date('Y');
        $year = $request->year;

        $count_lead = DB::table('sales_lead_register')
                    ->join('users','sales_lead_register.nik','=','users.nik')
                    ->whereYear('sales_lead_register.closing_date',$year)
                    ->where('users.status_karyawan', '!=', 'dummy');

        $count_open = DB::table('sales_lead_register')
                    ->join('users','sales_lead_register.nik','=','users.nik')
                    ->whereRaw('(result = "" || result = "SD" || result = "TP")')
                    ->whereYear('sales_lead_register.closing_date',$year)
                    ->where('users.status_karyawan', '!=', 'dummy');

        $count_win = DB::table('sales_lead_register')
                    ->join('users','sales_lead_register.nik','=','users.nik')
                    ->where('result','WIN')
                    ->whereYear('sales_lead_register.closing_date',$year)
                    ->where('users.status_karyawan', '!=', 'dummy');

        $count_lose = DB::table('sales_lead_register')
                    ->join('users','sales_lead_register.nik','=','users.nik')
                    ->where('result','LOSE')
                    ->whereYear('sales_lead_register.closing_date',$year)
                    ->where('users.status_karyawan', '!=', 'dummy');

        if ($div == 'SALES' && $pos == 'STAFF') {
            $count_leads = $count_lead->where('id_territory', $ter)
                ->where('id_company', '1')
                ->where('users.nik', $nik)
                ->count();
            $count_opens = $count_open->where('id_territory', $ter)
                ->where('id_company', '1')
                ->where('users.nik', $nik)
                ->count();
            $count_wins = $count_win->where('id_territory', $ter)
                ->where('id_company', '1')
                ->where('users.nik', $nik)
                ->count();
            $count_loses = $count_lose->where('id_territory', $ter)
                ->where('id_company', '1')
                ->where('users.nik', $nik)
                ->count();
        }elseif($div == 'SALES' && $pos == 'MANAGER'){
            $count_leads = $count_lead->where('id_territory', $ter)
                ->where('id_company', '1')
                ->count();
            $count_opens = $count_open->where('id_territory', $ter)
                ->where('id_company', '1')
                ->count();
            $count_wins = $count_win->where('id_territory', $ter)
                ->where('id_company', '1')
                ->count();
            $count_loses = $count_lose->where('id_territory', $ter)
                ->where('id_company', '1')
                ->count();
        }else{
            $count_leads = $count_lead->where('id_company', '1')
                ->count();
            $count_opens = $count_open->where('id_company','1')
                ->count();
            $count_wins = $count_win->where('id_company', '1')
                ->count();
            $count_loses = $count_lose->where('id_company', '1')
                ->count();
        }       


        return collect([
            'lead'=>$count_leads,
            'open'=>$count_opens,
            'win'=>$count_wins,
            'lose'=>$count_loses
        ]);
    }


    public function getChart(Request $request)
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        $year = date('Y');

        if($div == 'SALES'){
            $chart = DB::table('sales_lead_register')
                    ->join('users', 'sales_lead_register.nik', '=', 'users.nik')
                    ->orderBy('month')
                    ->where('id_territory', $ter)
                    ->where('id_company',1)
                    ->where('year',$request->year)
                    ->get();
        }elseif($div == 'TECHNICAL PRESALES' && $pos == 'STAFF'){
            $chart = DB::table('sales_lead_register')
                    ->join('users', 'sales_lead_register.nik', '=', 'users.nik')
                    ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                    ->orderBy('month')
                    ->where('sales_solution_design.nik', $nik)
                    ->where('year',$request->year)
                    ->get();
        }else{
            $chart = DB::table('sales_lead_register')
                    ->where('year',$request->year)
                    ->orderBy('month')
                    ->get();
        }

        $hasil = [0,0,0,0,0,0,0,0,0,0,0,0];
        $bulan_angka = [1,2,3,4,5,6,7,8,9,10,11,12];

        if (count($chart) != 0) {
            $first = $chart[0]->month;

            $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

            foreach ($bulan_angka as $key => $value2) {
                foreach ($chart as $value) {
                    if ($value->month == $value2) {
                        $hasil[$key]++;
                    }
                }
            }
        }
        
        return $hasil;
    }

    public function getChartAdmin()
    {
        $year = date("Y");

        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        if($pos == 'ADMIN'){
            $chart = DB::table('dvg_esm')
                    ->orderBy('month')
                    ->where('year',$year)
                    ->get();
        }if($pos == 'HR MANAGER'){
            $chart = DB::table('dvg_esm')
                    ->orderBy('month')
                    ->where('year',$year)
                    ->get();
        }if($div == 'FINANCE'){
            $chart = DB::table('dvg_esm')
                    ->orderBy('month')
                    ->where('year',$year)
                    ->get();
        }

        $first = $chart[0]->month;
        $hasil = [0,0,0,0,0,0,0,0,0,0,0,0];

        $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $bulan_angka = [1,2,3,4,5,6,7,8,9,10,11,12];

        foreach ($bulan_angka as $key => $value2) {
            foreach ($chart as $value) {
                if ($value->month == $value2) {
                    $hasil[$key]++;
                }
            }
        }
        return $hasil;
    }

    public function getPieChart(Request $request)
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        $pie = 0;

        $year = date('Y');

        if($div == 'SALES'){
            $status = DB::table('sales_lead_register')
                    ->join('users', 'sales_lead_register.nik', '=', 'users.nik')
                    ->orderBy('result')
                    ->where('id_territory', $ter)
                    ->where('year',$request->year)
                    ->get();
        }elseif($div == 'TECHNICAL PRESALES' && $pos == 'STAFF'){
            $status = DB::table('sales_lead_register')
                    ->join('users', 'sales_lead_register.nik', '=', 'users.nik')
                    ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                    ->orderBy('result')
                    ->where('sales_solution_design.nik', $nik)
                    ->where('year',$request->year)
                    ->get();
        }else{
            $status = DB::table('sales_lead_register')
                    ->orderBy('result')
                    ->where('year',$request->year)
                    ->get();
        }

        $hasil = [0,0,0,0,0,0];
        $hasil2 = [0,0,0,0,0,0];

        if (count($status) != 0) {
            $first = $status[0]->result;
            $bulan_angka = ['OPEN', '', 'SD', 'TP', 'WIN', 'LOSE'];

            foreach ($bulan_angka as $key => $value2) {
                foreach ($status as $value) {
                        if ($value->result == $value2) {
                            $hasil[$key]++;
                            $pie++;
                        }
                    }
            }

            foreach ($hasil as $key => $value) {
                $hasil2[$key] = ($value/$pie)*100;
            }
        }

        return $hasil2;
    }

    public function getPieChartAFH()
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        $pie = 0;

        $year = date('Y');

        if($pos == 'ADMIN' || $div == 'FINANCE' || $pos == 'HR MANAGER'){
            $status = DB::table('dvg_esm')
                    ->join('users', 'dvg_esm.nik_admin', '=', 'users.nik')
                    ->orderBy('status')
                    ->where('year',$year)
                    ->get();
        }

        $first = $status[0]->status;
        $hasil = [0,0,0,0];
        $bulan_angka = ['ADMIN', 'HRD', 'FINANCE', 'TRANSFER'];

        foreach ($bulan_angka as $key => $value2) {
            foreach ($status as $value) {
                    if ($value->status == $value2) {
                        $hasil[$key]++;
                        $pie++;
                    }
                }
        }

        $hasil2 = [0,0,0,0];
        foreach ($hasil as $key => $value) {
            $hasil2[$key] = ($value/$pie)*100;
        }

        return $hasil2;
    }

    public function getAreaChart()
    {   
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        $year = date('Y');

        $years = $year - 1;

        if($div == 'SALES' ){
            $chart = DB::table('sales_lead_register')
                    ->join('users', 'sales_lead_register.nik', '=', 'users.nik')
                    ->orderBy('amount')
                    ->where('id_territory', $ter)
                    ->where('year',$years)
                    ->get();
        }elseif($div == 'TECHNICAL PRESALES' && $pos == 'STAFF'){
            $chart = DB::table('sales_lead_register')
                    ->join('users', 'sales_lead_register.nik', '=', 'users.nik')
                    ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                    ->orderBy('amount')
                    ->where('sales_solution_design.nik', $nik)
                    ->where('year',$years)
                    ->get();
        }else{
            $chart = DB::table('sales_lead_register')
                    ->orderBy('amount')
                    ->where('year',$years)
                    ->get();
        }

        $first = $chart[0]->month;
        $hasil = [0,0,0,0,0,0,0,0,0,0,0,0];

        $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $bulan_angka = [1,2,3,4,5,6,7,8,9,10,11,12];

        foreach ($bulan_angka as $key => $value2) {
           foreach ($chart as $value) {
               if ($value->month == $value2) {
                    $hasil[$key] = $hasil[$key]+$value->deal_price;
                }
            }
        }
        return $hasil;
    }

    public function getAreaChart2019(Request $request)
    {   
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        $year = date('Y');


        if($div == 'SALES' ){
            $chart = DB::table('sales_lead_register')
                    ->join('users', 'sales_lead_register.nik', '=', 'users.nik')
                    ->orderBy('amount')
                    ->where('id_territory', $ter)
                    ->where('year',$request->year)
                    ->get();
        }elseif($div == 'TECHNICAL PRESALES' && $pos == 'STAFF'){
            $chart = DB::table('sales_lead_register')
                    ->join('users', 'sales_lead_register.nik', '=', 'users.nik')
                    ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                    ->orderBy('amount')
                    ->where('sales_solution_design.nik', $nik)
                    ->where('year',$request->year)
                    ->get();
        }else{
            if (Auth::User()->email == 'tech@sinergy.co.id') {
                $chart = DB::table('sales_lead_register')
                    ->orderBy('deal_price')
                    ->where('year',$request->year)
                    ->get();
            }else{
                $chart = DB::table('sales_lead_register')
                    ->orderBy('amount')
                    ->where('year',$request->year)
                    ->get();
            }
        }

        $hasil = [0,0,0,0,0,0,0,0,0,0,0,0];
        $bulan_angka = [1,2,3,4,5,6,7,8,9,10,11,12];

        if ($chart) {
            // return $chart;
            $first = $chart[0]->month;

            $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

            foreach ($bulan_angka as $key => $value2) {
               foreach ($chart as $value) {
                   if ($value->month == $value2) {
                        $hasil[$key] = $hasil[$key]+$value->deal_price;
                    }
                }
            }
        }
       
        return $hasil;
    }

    public function getAreaChartClaim()
    {   
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        $year = date('Y');

        if($pos == 'ADMIN'){
            $chart = DB::table('dvg_esm')
                    ->orderBy('amount')
                    ->where('year',$year)
                    ->get();
        }elseif($pos == 'HR MANAGER'){
            $chart = DB::table('dvg_esm')
                    ->orderBy('amount')
                    ->where('year',$year)
                    ->get();
        }elseif($div == 'FINANCE'){
            $chart = DB::table('dvg_esm')
                    ->orderBy('amount')
                    ->where('year',$year)
                    ->get();
        }

        $first = $chart[0]->month;
        $hasil = [0,0,0,0,0,0,0,0,0,0,0,0];

        $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $bulan_angka = [1,2,3,4,5,6,7,8,9,10,11,12];

        foreach ($bulan_angka as $key => $value2) {
           foreach ($chart as $value) {
               if ($value->month == $value2) {
                    $hasil[$key] = $hasil[$key]+$value->amount;
                }
            }
        }
        return $hasil;
    }

    public function getDoughnutChart(Request $request)
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        $pie = 0;

        $year = date('Y');

        if($div == 'SALES'){
            $status = DB::table('sales_lead_register')
                    ->join('users', 'sales_lead_register.nik', '=', 'users.nik')
                    ->orderBy('result')
                    ->where('id_territory', $ter)
                    ->where('year',$request->year)
                    ->get();
        }elseif($div == 'TECHNICAL PRESALES' && $pos == 'STAFF'){
            $status = DB::table('sales_lead_register')
                    ->join('users', 'sales_lead_register.nik', '=', 'users.nik')
                    ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                    ->orderBy('result')
                    ->where('sales_solution_design.nik', $nik)
                    ->where('year',$request->year)
                    ->get();
        }else{
            $status = DB::table('sales_lead_register')
                    ->orderBy('result')
                    ->where('year',$request->year)
                    ->get();
        }

        $hasil = [0,0];
        $hasil2 = [0,0];

        if (count($status) != 0) {
            $first = $status[0]->result;
            $bulan_angka = ['WIN', 'LOSE'];

            foreach ($bulan_angka as $key => $value2) {
                foreach ($status as $value) {
                    if ($value->result == $value2) {
                        $hasil[$key]++;
                        $pie++;
                    }
                }
            }

            foreach ($hasil as $key => $value) {
                if ($value != 0) {
                    $hasil2[$key] = ($value/$pie)*100;
                }
            }

        }
        
        return $hasil2;
    }

    public function getDoughnutChartAFH()
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        $pie = 0;

        $year = date('Y');

        $status = DB::table('dvg_esm')
                ->orderBy('status')
                ->where('year',$year)
                ->get();


        // $status = DB::table('sales_lead_register')
        //             ->orderBy('result')
        //             ->where('year',$year)
        //             ->get();

        // $first = $data[0]->status;
        // $hasil = [0,0];
        // if($div == 'FINANCE'){
        //     $bulan_angka = ['FINANCE', 'TRANSFER'];
        // }elseif($pos == 'ADMIN'){
        //     $bulan_angka = ['ADMIN', 'TRANSFER'];
        // }elseif($pos == 'HR MANAGER'){
        //     $bulan_angka = ['HRD', 'TRANSFER'];
        // }

        // foreach ($bulan_angka as $key => $value2) {
        //     foreach ($data as $value) {
        //             if ($value->status == $value2) {
        //                 $hasil[$key]++;
        //                 $pie++;
        //             }
        //         }
        // }

        // $hasil2 = [0,0];
        // foreach ($hasil as $key => $value) {
        //     $hasil2[$key] = ($value/$pie)*100;
        // }

        // return $hasil2;

        $first = $status[0]->status;
        $hasil = [0,0];
        if($div == 'FINANCE'){
            $bulan_angka = ['FINANCE', 'TRANSFER'];
        }elseif($pos == 'ADMIN'){
            $bulan_angka = ['ADMIN', 'TRANSFER'];
        }elseif($pos == 'HR MANAGER'){
            $bulan_angka = ['HRD', 'TRANSFER'];
        }

        foreach ($bulan_angka as $key => $value2) {
            foreach ($status as $value) {
                    if ($value->status == $value2) {
                        $hasil[$key]++;
                        $pie++;
                    }
                }
        }

        $hasil2 = [0,0];
        foreach ($hasil as $key => $value) {
            $hasil2[$key] = ($value/$pie)*100;
        }

        return $hasil2;
    }

    public function getChartByStatus(Request $request)
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users' )->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;
        $company = DB::table('users')->select('id_company')->where('nik', $nik)->first();
        $com = $company->id_company;

        $data = Sales::join('users','sales_lead_register.nik','=','users.nik')
                    ->select(
                    DB::raw('COUNT(IF(`sales_lead_register`.`result` = "OPEN",1,NULL)) AS "INITIAL"'), 
                    DB::raw('COUNT(IF(`sales_lead_register`.`result` = "",1,NULL)) AS "OPEN"'), 
                    DB::raw('COUNT(IF(`sales_lead_register`.`result` = "SD",1,NULL)) AS "SD"'),
                    DB::raw('COUNT(IF(`sales_lead_register`.`result` = "TP",1,NULL)) AS "TP"'),
                    DB::raw('COUNT(IF(`sales_lead_register`.`result` = "WIN",1,NULL)) AS "WIN"'),
                    DB::raw('COUNT(IF(`sales_lead_register`.`result` = "LOSE",1,NULL)) AS "LOSE"'),
                    DB::raw('COUNT(*) AS `All`'),
                    DB::raw('SUM(IF(`sales_lead_register`.`result` = "OPEN",amount,NULL)) AS "amount_INITIAL"'),
                    DB::raw('SUM(IF(`sales_lead_register`.`result` = "",amount,NULL)) AS "amount_OPEN"'),
                    DB::raw('SUM(IF(`sales_lead_register`.`result` = "SD",amount,NULL)) AS "amount_SD"'),
                    DB::raw('SUM(IF(`sales_lead_register`.`result` = "TP",amount,NULL)) AS "amount_TP"'),
                    DB::raw('SUM(IF(`sales_lead_register`.`result` = "WIN",amount,NULL)) AS "amount_WIN"'),
                    DB::raw('SUM(IF(`sales_lead_register`.`result` = "LOSE",amount,NULL)) AS "amount_LOSE"'),
                    DB::raw('SUM(amount) AS `amount_All`'),
                    'month'
                )
                ->where('result','!=','HOLD')
                ->where('result','!=','SPECIAL')
                ->where('result','!=','CANCEL')
                ->where('id_company','1')
                ->where('sales_lead_register.result','!=','hmm')
                ->whereYear('sales_lead_register.created_at',$request->year)
                ->groupBy('month');

        if ($div == 'SALES') {          
            $datas = array("data" => $data->where('users.id_territory',$ter)->get());
        }else{
            $datas = array("data" => $data->get());
        }

        return $datas;
    }

    public function maintenance()
    {
        return view('maintenance');
    }

    public function notif_view_all(){
        return view('notif/view_all')->with(['initView'=> $this->initMenuBase()]);
    }

    public function top_win_ter_non_detail(Request $request)
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = (array)$territory->id_territory;

        $sum_amounts_ter = DB::table('sales_lead_register')
            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
            ->select(
                DB::raw('SUM(sales_lead_register.amount) as sum_amounts'),
                DB::raw('COUNT(sales_lead_register.lead_id) as leads_total'),
                'users.id_territory'
            )
            ->whereYear('closing_date', $request->year)
            ->where('result', 'WIN')
            ->where('users.status_karyawan', '!=', 'dummy')
            ->where('users.id_company', '1');

        $sum_amounts_ter = $sum_amounts_ter->whereNotIn('users.id_territory',$ter)->groupBy('users.id_territory')
        ->get()
        ->keyBy('id_territory');

        $merged_results = [];

        foreach ($sum_amounts_ter as $id_territory => $sum_amount) {
            $merged_results[$id_territory] = [
                [
                    'sum_amounts' => $sum_amount->sum_amounts,
                    'leads_total' => $sum_amount->leads_total,
                    'id_territory' => $id_territory,
                ]
            ];
        }
        
        ksort($merged_results);

        $merged_results = array_slice($merged_results, 0, 3, true) +
                          array_slice($merged_results, 3, null, true);

        $merged_results;

        return $top_win_sip_ter = $merged_results;
    }

    public function top_win_sip_ter(Request $request){
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;
        $company = DB::table('users')->select('id_company')->where('nik', $nik)->first();
        $com = $company->id_company;

        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')
                    ->select('name', 'roles.group')->where('user_id', $nik)->first(); 


        $sum_amounts_ter = DB::table('sales_lead_register')
            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
            ->select(
                DB::raw('SUM(sales_lead_register.amount) as sum_amounts'),
                DB::raw('COUNT(sales_lead_register.lead_id) as leads_total'),
                'users.id_territory'
            )
            ->whereYear('closing_date', $request->year)
            ->where('result', 'WIN')
            ->where('users.status_karyawan', '!=', 'dummy')
            ->where('users.id_company', '1');

        $top_win_sip_ter_ter = DB::table('sales_lead_register')
            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
            ->join('tb_territory', 'tb_territory.id_territory', '=', 'users.id_territory')
            ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
            ->select(
                DB::raw('COUNT(sales_lead_register.lead_id) as leads'),
                DB::raw('SUM(sales_lead_register.amount) as amounts'),
                DB::raw('SUM(sales_lead_register.deal_price) as deal_prices'),
                'users.name',
                'tb_company.code_company',
                'users.id_territory',
                'users.nik'
            )
            ->where('result', 'WIN')
            ->whereYear('closing_date', $request->year)
            ->where('users.status_karyawan', '!=', 'dummy')
            ->where('users.id_company', '1')
            ->groupBy('users.nik', 'users.name', 'tb_company.code_company', 'users.id_territory', 'users.nik')
            ->orderBy('deal_prices', 'desc');


        if ($cek_role->name == 'Account Executive') {
            $sum_amounts_ter = $sum_amounts_ter->where('users.nik',$nik)->groupBy('users.id_territory')
            ->get()
            ->keyBy('id_territory');

            $top_win_sip_ter_ter = $top_win_sip_ter_ter->where('users.nik',$nik)->get()
            ->groupBy('id_territory');

            $merged_results = [];

            foreach ($sum_amounts_ter as $id_territory => $sum_amount) {
                $merged_results[$id_territory] = [
                    [
                        'sum_amounts' => $sum_amount->sum_amounts,
                        'leads_total' => $sum_amount->leads_total,
                        'id_territory' => $id_territory,
                        'details' => $top_win_sip_ter_ter->get($id_territory, collect([]))
                    ]
                ];
            }

            ksort($merged_results);

            $merged_results = array_slice($merged_results, 0, 3, true) +
                              array_slice($merged_results, 3, null, true);

            $top_win_sip_ter = $merged_results;

        } elseif ($cek_role->name == 'VP Sales') {
            $sum_amounts_ter = $sum_amounts_ter->where('users.id_territory',$ter)->groupBy('users.id_territory')
            ->get()
            ->keyBy('id_territory');

            $top_win_sip_ter_ter = $top_win_sip_ter_ter->where('users.id_territory',$ter)->get()
            ->groupBy('id_territory');

            $merged_results = [];

            foreach ($sum_amounts_ter as $id_territory => $sum_amount) {
                $merged_results[$id_territory] = [
                    [
                        'sum_amounts' => $sum_amount->sum_amounts,
                        'leads_total' => $sum_amount->leads_total,
                        'id_territory' => $id_territory,
                        'details' => $top_win_sip_ter_ter->get($id_territory, collect([]))
                    ]
                ];
            }
            
            ksort($merged_results);

            $merged_results = array_slice($merged_results, 0, 3, true) +
                              array_slice($merged_results, 3, null, true);

            $merged_results;

            $top_win_sip_ter = $merged_results;

        }else{
           
           $sum_amounts_ter = $sum_amounts_ter->groupBy('users.id_territory')
            ->get()
            ->keyBy('id_territory');

            $top_win_sip_ter_ter = $top_win_sip_ter_ter->get()
            ->groupBy('id_territory');

            foreach ($sum_amounts_ter as $id_territory => $sum_amount) {
                $merged_results[$id_territory] = [
                    [
                        'sum_amounts' => $sum_amount->sum_amounts,
                        'leads_total' => $sum_amount->leads_total,
                        'id_territory' => $id_territory,
                        'details' => $top_win_sip_ter_ter->get($id_territory, collect([]))
                    ]
                ];
            }

            $total_amounts = $sum_amounts_ter->sum('sum_amounts');
            $total_leads = $sum_amounts_ter->sum('leads_total');

            $total_object = [
                [
                    'sum_amounts' => $total_amounts,
                    'leads_total' => $total_leads,
                    'id_territory' => 'TOTAL'
                ]
            ];

            ksort($merged_results);

            $merged_results['TOTAL'] = $total_object;
            $top_win_sip_ter = $merged_results;

        }
        return $top_win_sip_ter;

    }

    public function top_win_sip(Request $request){
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;
        $company = DB::table('users')->select('id_company')->where('nik', $nik)->first();
        $com = $company->id_company;

        $top_win_sip = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                        ->select(DB::raw('COUNT(sales_lead_register.lead_id) as leads'), DB::raw('SUM(sales_lead_register.amount) as amounts'), DB::raw('SUM(sales_lead_register.deal_price) as deal_prices'), 'users.name', 'tb_company.code_company','users.nik','users.id_territory')
                        ->where('result', 'WIN')
                        ->whereYear('closing_date', $request->year)
                        ->where('users.id_company', '1')
                        ->where('users.status_karyawan', '!=', 'dummy')
                        ->groupBy('sales_lead_register.nik')
                        ->orderBy('deal_prices', 'desc')
                        ->take(5)
                        ->get();

        return $top_win_sip;
    }

    public function top_win_msp(Request $request){
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;
        $company = DB::table('users')->select('id_company')->where('nik', $nik)->first();
        $com = $company->id_company;

        $top_win_msp = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                        ->select(DB::raw('COUNT(sales_lead_register.lead_id) as leads'), DB::raw('SUM(sales_lead_register.amount) as amounts'), DB::raw('SUM(sales_lead_register.deal_price) as deal_prices'), 'users.name', 'tb_company.code_company','users.nik')
                        ->where('result', 'WIN')
                        ->whereYear('closing_date', $request->year)
                        ->where('users.id_company', '2')
                        ->where('users.status_karyawan', '!=', 'dummy')
                        ->groupBy('sales_lead_register.nik')
                        ->orderBy('deal_prices', 'desc')
                        ->take(5)
                        ->get();

        return $top_win_msp;
    }

    public function loop_year(){
        $loop_year = DB::table('sales_lead_register')
                    ->select(DB::raw('`year` AS `id`,`year` AS `text`'))->groupBy('year')->orderBy('year','desc')->get();

        return $loop_year;
    }

    public function storeIdea(Request $request)
    {
        try{
            DB::beginTransaction();
            $idea = IdeaHub::create([
                'nik' => Auth::user()->nik,
                'ide' => $request->idea,
                'konsep_bisnis' => $request->konsep_bisnis,
                'referensi' => $request->referensi_bisnis,
                'posisi' => Auth::user()->roles()->first()->name,
                'divisi' => Auth::user()->roles()->first()->group
            ]);

            IdeaHubActivity::create([
                'id_idea_hub' => $idea->id,
                'operator' => Auth::user()->name,
                'activity' => 'Create new idea',
                'date_add' => Carbon::today()
            ]);

            $checkPoint = IdeaHubPoint::where('nik', Auth::user()->nik)->first();

            if (!empty($checkPoint)){
                $point = $checkPoint->point;
                $id = $checkPoint->id;
                $updatePoint = IdeaHubPoint::find($id);
                $updatePoint->update([
                    'point' => $point + 1
                ]);
            }else{
                IdeaHubPoint::create([
                    'nik' => $idea->nik,
                    'point' => 1,
                    // 'nama' => Auth::user()->nik
                ]);
            }

            $role = Auth::user()->roles()->first()->name;

            $userToSend = DB::table('roles as r')
                ->join('role_user as ru', 'r.id', 'ru.role_id')
                ->join('users as u', 'ru.user_id', 'u.nik')
                ->select('u.name', 'u.email');

            if ($idea->divisi == 'Solutions & Partnership Management'){
                if ($role != 'VP Solutions & Partnership Management'){
                    $userToSend = $userToSend->where('r.name', 'VP Solutions & Partnership Management');
                }else{
                    $userToSend = $userToSend->where('r.name', 'Chief Operating Officer');
                }
            }elseif ($idea->divisi == 'Program & Project Management'){
                if ($role != 'VP Program & Project Management'){
                    $userToSend = $userToSend->where('r.name', 'VP Program & Project Management');
                }else {
                    $userToSend = $userToSend->where('r.name', 'Chief Operating Officer');
                }
            }elseif ($idea->divisi == 'Synergy System Management'){
                if ($role != 'VP Synergy System Management'){
                    $userToSend = $userToSend->where('r.name', 'VP Synergy System Management');
                }else{
                    $userToSend = $userToSend->where('r.name', 'Chief Operating Officer');
                }
            }elseif ($idea->divisi == 'Internal Chain Management'){
                if ($role != 'VP Internal Chain Management'){
                    $userToSend = $userToSend->where('r.name', 'VP Internal Chain Management');
                }else{
                    $userToSend = $userToSend->where('r.name', 'Chief Operating Officer');
                }
            }elseif ($idea->divisi == 'Human Capital Management'){
                if ($role != 'VP Human Capital Management'){
                    $userToSend = $userToSend->where('r.name', 'VP Human Capital Management');
                }else{
                    $userToSend = $userToSend->where('r.name', 'Chief Operating Officer');
                }
            }elseif ($idea->divisi == 'Sales'){
                if ($role != 'VP Sales'){
                    $userToSend = $userToSend->where('r.name', 'VP Sales')->where('u.id_territory', Auth::user()->id_territory);
                }else{
                    $userToSend = $userToSend->where('r.name', 'Chief Operating Officer');
                }
            }elseif ($idea->divisi == 'Financial And Accounting'){
                if ($role != 'VP Financial & Accounting'){
                    $userToSend = $userToSend->where('r.name', 'VP Financial & Accounting');
                }else{
                    $userToSend = $userToSend->where('r.name', 'Chief Operating Officer');
                }
            }
            $userToSend = $userToSend->first();

            $sender = Auth::user()->name;

            Mail::to($userToSend->email)->send(new MailIdeaHub('[SIMS-App] Idea Hub - New Business Idea', $idea, $userToSend, $sender));
            DB::commit();
            return $idea->id;
        }catch (\Exception $exception){
            DB::rollBack();
            return $exception->getMessage();
        }

    }

}
