<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use App\Sales;
use App\TechnologyTag;
use App\ProductTag;
use App\User;
use App\TB_Contact;
use App\ProductTagRelation;
use App\TechnologyTagRelation;
use App\SalesChangeLog;
use App\solution_design;
use App\TenderProcess;
use App\Quote;
use App\PID;
use App\ServiceTagRelation;
use App\SbeRelation;
use App\RequestChange;

use Mail;
use App\Mail\MailResult;
use App\Mail\CreateLeadRegister;
use App\Mail\AssignPresales;
use App\Mail\RaiseTender;
use App\Mail\AddContribute;
use App\Mail\EmailChangeCustomer;

use Carbon\Carbon;
use Google\Auth\CredentialsLoader;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;


class SalesLeadController extends Controller
{
    public function getCountLead(Request $request)
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        $cek_role = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role')->where('user_id',$nik)->first();

        // return $request->year;

        $total_lead = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->where('sales_lead_register.result','!=','hmm')
                    ->whereIn('year',$request->year);

        $total_open = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->where('sales_lead_register.result','')
                    ->whereIn('year',$request->year);

        $total_sd = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->where('sales_lead_register.result','SD')
                    ->whereIn('year',$request->year);

        $total_tp = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->where('sales_lead_register.result','TP')
                    ->whereIn('year',$request->year);

        $total_win = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->where('sales_lead_register.result','WIN')
                    ->whereIn('year',$request->year);

        $total_lose = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->where('sales_lead_register.result','LOSE')
                    ->whereIn('year',$request->year);

        $total_cancel = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->where('sales_lead_register.result','CANCEL')
                    ->whereIn('year',$request->year);

        $total_initial = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->where('sales_lead_register.result','OPEN')
                    ->whereIn('year',$request->year);

        $presales = false;

        if($ter != null){
            if ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
                $count_lead = $total_lead->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                            ->where('sales_solution_design.nik', $nik)
                            ->count('sales_lead_register.lead_id');

                $count_open = $total_open->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                            ->where('sales_solution_design.nik', $nik)
                            ->count('sales_lead_register.lead_id');

                $count_sd = $total_sd->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                            ->where('sales_solution_design.nik', $nik)
                            ->count('sales_lead_register.lead_id');

                $count_tp = $total_tp->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                            ->where('sales_solution_design.nik', $nik)
                            ->count('sales_lead_register.lead_id');

                $count_win = $total_win->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                            ->where('sales_solution_design.nik', $nik)
                            ->count('sales_lead_register.lead_id');

                $count_lose = $total_lose->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                            ->where('sales_solution_design.nik', $nik)
                            ->count('sales_lead_register.lead_id');

                $count_cancel = $total_cancel->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                            ->where('sales_solution_design.nik', $nik)
                            ->count('sales_lead_register.lead_id');

                $count_initial = $total_initial->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                            ->where('sales_solution_design.nik', $nik)
                            ->count('sales_lead_register.lead_id');

                $sum_amount_lead = $total_lead->select(DB::raw('case WHEN SUM(amount) IS NOT NULL THEN SUM(amount) ELSE 0 END as amount_lead'))->first();

                $sum_amount_open = $total_open->select(DB::raw('SUM(amount) as amount_open'))->first();

                $sum_amount_sd = $total_sd->select(DB::raw('SUM(amount) as amount_sd'))->first();

                $sum_amount_tp = $total_tp->select(DB::raw('SUM(amount) as amount_tp'))->first();

                $sum_amount_win = $total_win->select(DB::raw('SUM(amount) as amount_win'))->first();

                $sum_amount_lose = $total_lose->select(DB::raw('SUM(amount) as amount_lose'))->first();

            } else if ($div == 'SALES' && $pos == 'MANAGER') {
                $count_lead = $total_lead->where('users.id_territory',$ter)
                        ->where('id_company','1')
                        ->count('lead_id');

                $count_open = $total_open->where('users.id_territory',$ter)
                        ->where('id_company','1')
                        ->count('lead_id');

                $count_sd = $total_sd->where('users.id_territory',$ter)
                        ->where('id_company','1')
                        ->count('lead_id');

                $count_tp = $total_tp->where('users.id_territory',$ter)
                        ->where('id_company','1')
                        ->count('lead_id');

                $count_win = $total_win->where('users.id_territory',$ter)
                        ->where('id_company','1')
                        ->count('lead_id');

                $count_lose = $total_lose->where('users.id_territory',$ter)
                        ->where('id_company','1')
                        ->count('lead_id');

                $count_cancel = $total_cancel->where('users.id_territory',$ter)
                        ->where('id_company','1')
                        ->count('lead_id');

                $count_initial = $total_initial->where('users.id_territory',$ter)
                        ->where('id_company','1')
                        ->count('lead_id');

                $sum_amount_lead = $total_lead->select(DB::raw('case WHEN SUM(amount) IS NOT NULL THEN SUM(amount) ELSE 0 END as amount_lead'))->first();

                $sum_amount_open = $total_open->select(DB::raw('SUM(amount) as amount_open'))->first();

                $sum_amount_sd = $total_sd->select(DB::raw('SUM(amount) as amount_sd'))->first();

                $sum_amount_tp = $total_tp->select(DB::raw('SUM(amount) as amount_tp'))->first();

                $sum_amount_win = $total_win->select(DB::raw('SUM(amount) as amount_win'))->first();

                $sum_amount_lose = $total_lose->select(DB::raw('SUM(amount) as amount_lose'))->first();

            } else if ($div == 'SALES' && $pos == 'STAFF') {
                $count_lead = $total_lead->where('users.nik',$nik)
                        ->where('id_company','1')
                        ->count('lead_id');

                $count_open = $total_open->where('users.nik',$nik)
                        ->where('id_company','1')
                        ->count('lead_id');

                $count_sd = $total_sd->where('users.nik',$nik)
                        ->where('id_company','1')
                        ->count('lead_id');

                $count_tp = $total_tp->where('users.nik',$nik)
                        ->where('id_company','1')
                        ->count('lead_id');

                $count_win = $total_win->where('users.nik',$nik)
                        ->where('id_company','1')
                        ->count('lead_id');

                $count_lose = $total_lose->where('users.nik',$nik)
                        ->where('id_company','1')
                        ->count('lead_id');

                $count_cancel = $total_cancel->where('users.nik',$nik)
                        ->where('id_company','1')
                        ->count('lead_id');

                $count_initial = $total_initial->where('users.nik',$nik)
                        ->where('id_company','1')
                        ->count('lead_id');

                $sum_amount_lead = $total_lead->select(DB::raw('case WHEN SUM(amount) IS NOT NULL THEN SUM(amount) ELSE 0 END as amount_lead'))->first();

                $sum_amount_open = $total_open->select(DB::raw('SUM(amount) as amount_open'))->first();

                $sum_amount_sd = $total_sd->select(DB::raw('SUM(amount) as amount_sd'))->first();

                $sum_amount_tp = $total_tp->select(DB::raw('SUM(amount) as amount_tp'))->first();

                $sum_amount_win = $total_win->select(DB::raw('SUM(amount) as amount_win'))->first();

                $sum_amount_lose = $total_lose->select(DB::raw('SUM(amount) as amount_lose'))->first();

            } else if ($div == 'TECHNICAL PRESALES' && $pos == 'MANAGER' || $div == 'BCD' && $pos == 'ADMIN') {
                $count_lead = $total_lead->where('users.id_company','1')
                            ->count('lead_id');

                $count_open = $total_open->where('users.id_company','1')
                            ->count('lead_id');

                $count_sd = $total_sd->where('users.id_company','1')
                            ->count('lead_id');

                $count_tp = $total_tp->where('users.id_company','1')
                            ->count('lead_id');

                $count_win = $total_win->where('users.id_company','1')
                            ->count('lead_id');

                $count_lose = $total_lose->where('users.id_company','1')
                            ->count('lead_id');

                $count_cancel = $total_cancel->where('users.id_company','1')
                            ->count('lead_id');

                $count_initial = $total_initial->where('users.id_company','1')
                            ->count('lead_id');

                if ($div == 'TECHNICAL PRESALES' && $pos == 'MANAGER') {
                    $sum_amount_lead = $total_initial->select(DB::raw('case WHEN SUM(amount) IS NOT NULL THEN SUM(amount) ELSE 0 END as amount_lead'))->first();
                } else {
                    $sum_amount_lead = $total_lead->select(DB::raw('case WHEN SUM(amount) IS NOT NULL THEN SUM(amount) ELSE 0 END as amount_lead'))->where('users.id_company','1')->first();
                }

                $sum_amount_open = $total_open->select(DB::raw('SUM(amount) as amount_open'))->first();

                $sum_amount_sd = $total_sd->select(DB::raw('SUM(amount) as amount_sd'))->first();

                $sum_amount_tp = $total_tp->select(DB::raw('SUM(amount) as amount_tp'))->first();

                $sum_amount_win = $total_win->select(DB::raw('SUM(amount) as amount_win'))->first();

                $sum_amount_lose = $total_lose->select(DB::raw('SUM(amount) as amount_lose'))->first();

                if ($div == 'TECHNICAL PRESALES' && $pos == 'MANAGER') {
                    $presales = true;
                } else {
                    $presales = false;
                }
                
            } else if ($div == 'BCD' && $pos == 'MANAGER'){

                $count_lead = $total_lead->count('lead_id');

                $count_open = $total_open->count('lead_id');

                $count_sd = $total_sd->count('lead_id');

                $count_tp = $total_tp->count('lead_id');

                $count_win = $total_win->count('lead_id');

                $count_lose = $total_lose->count('lead_id');
                
                $count_cancel = $total_cancel->count('lead_id');
                
                $count_initial = $total_initial->count('lead_id');

                $sum_amount_lead = $total_lead->select(DB::raw('case WHEN SUM(amount) IS NOT NULL THEN SUM(amount) ELSE 0 END as amount_lead'))->first();

                $sum_amount_open = $total_open->select(DB::raw('SUM(amount) as amount_open'))->first();

                $sum_amount_sd = $total_sd->select(DB::raw('SUM(amount) as amount_sd'))->first();

                $sum_amount_tp = $total_tp->select(DB::raw('SUM(amount) as amount_tp'))->first();

                $sum_amount_win = $total_win->select(DB::raw('SUM(amount) as amount_win'))->first();

                $sum_amount_lose = $total_lose->select(DB::raw('SUM(amount) as amount_lose'))->first();

            } else if ($cek_role->name_role == 'Operations Director' ){
                $count_lead = $total_lead->count('lead_id');

                $count_open = $total_open->count('lead_id');

                $count_sd = $total_sd->count('lead_id');

                $count_tp = $total_tp->count('lead_id');

                $count_win = $total_win->count('lead_id');

                $count_lose = $total_lose->count('lead_id');
                
                $count_cancel = $total_cancel->count('lead_id');
                
                $count_initial = $total_initial->count('lead_id');

                $sum_amount_lead = $total_lead->select(DB::raw('case WHEN SUM(amount) IS NOT NULL THEN SUM(amount) ELSE 0 END as amount_lead'))->first();

                $sum_amount_open = $total_open->select(DB::raw('SUM(amount) as amount_open'))->first();

                $sum_amount_sd = $total_sd->select(DB::raw('SUM(amount) as amount_sd'))->first();

                $sum_amount_tp = $total_tp->select(DB::raw('SUM(amount) as amount_tp'))->first();

                $sum_amount_win = $total_win->select(DB::raw('SUM(amount) as amount_win'))->first();

                $sum_amount_lose = $total_lose->select(DB::raw('SUM(amount) as amount_lose'))->first();
            }else {
                $count_lead = $total_lead->where('users.id_territory',$ter)
                            ->where('id_company','1')
                            ->count('lead_id');

                $count_open = $total_open->where('users.id_territory',$ter)
                            ->where('id_company','1')
                            ->count('lead_id');

                $count_sd = $total_sd->where('users.id_territory',$ter)
                            ->where('id_company','1')
                            ->count('lead_id');

                $count_tp = $total_tp->where('users.id_territory',$ter)
                            ->where('id_company','1')
                            ->count('lead_id');

                $count_win = $total_win->where('users.id_territory',$ter)
                            ->where('id_company','1')
                            ->count('lead_id');

                $count_lose = $total_lose->where('users.id_territory',$ter)
                            ->where('id_company','1')
                            ->count('lead_id');

                $count_cancel = $total_cancel->where('users.id_territory',$ter)
                            ->where('id_company','1')
                            ->count('lead_id');

                $count_initial = $total_initial->where('users.id_territory',$ter)
                            ->where('id_company','1')
                            ->count('lead_id');

                $sum_amount_lead = $total_lead->select(DB::raw('case WHEN SUM(amount) IS NOT NULL THEN SUM(amount) ELSE 0 END as amount_lead'))->first();

                $sum_amount_open = $total_open->select(DB::raw('SUM(amount) as amount_open'))->first();

                $sum_amount_sd = $total_sd->select(DB::raw('SUM(amount) as amount_sd'))->first();

                $sum_amount_tp = $total_tp->select(DB::raw('SUM(amount) as amount_tp'))->first();

                $sum_amount_win = $total_win->select(DB::raw('SUM(amount) as amount_win'))->first();

                $sum_amount_lose = $total_lose->select(DB::raw('SUM(amount) as amount_lose'))->first();
                
            }             
        } else {

            $count_lead = $total_lead->count('lead_id');

            $count_open = $total_open->count('lead_id');

            $count_sd = $total_sd->count('lead_id');

            $count_tp = $total_tp->count('lead_id');

            $count_win = $total_win->count('lead_id');

            $count_lose = $total_lose->count('lead_id');
            
            $count_cancel = $total_cancel->count('lead_id');
            
            $count_initial = $total_initial->count('lead_id');

            $sum_amount_lead = $total_lead->select(DB::raw('case WHEN SUM(amount) IS NOT NULL THEN SUM(amount) ELSE 0 END as amount_lead'))->first();

            $sum_amount_open = $total_open->select(DB::raw('SUM(amount) as amount_open'))->first();

            $sum_amount_sd = $total_sd->select(DB::raw('SUM(amount) as amount_sd'))->first();

            $sum_amount_tp = $total_tp->select(DB::raw('SUM(amount) as amount_tp'))->first();

            $sum_amount_win = $total_win->select(DB::raw('SUM(amount) as amount_win'))->first();

            $sum_amount_lose = $total_lose->select(DB::raw('SUM(amount) as amount_lose'))->first();

        }

        return collect([
            'lead'=>$count_lead,
            'initial'=>$count_initial,
            'open'=>$count_open,
            'sd'=>$count_sd,
            'tp'=>$count_tp,
            'win'=>$count_win,
            'lose'=>$count_lose,
            'cancel'=>$count_cancel,
            'initial_unfiltered'=>$count_initial,
            'open_unfiltered'=>$count_open,
            'sd_unfiltered'=>$count_sd,
            'tp_unfiltered'=>$count_tp,
            'win_unfiltered'=>$count_win,
            'lose_unfiltered'=>$count_lose,
            'cancel_unfiltered'=>$count_cancel,
            'amount_lead'=>$sum_amount_lead->amount_lead,
            'amount_open'=>$sum_amount_open->amount_open,
            'amount_sd'=>$sum_amount_sd->amount_sd,
            'amount_tp'=>$sum_amount_tp->amount_tp,
            'amount_win'=>$sum_amount_win->amount_win,
            'amount_lose'=>$sum_amount_lose->amount_lose,
            'presales'=>$presales
        ]);
    }

    public function index(){
        $year = DB::table('sales_lead_register')->select('year')->where('year','!=',NULL)->groupBy('year')->get();

        $year_now = date('Y');

        $sidebar_collapse = true;

        return view('sales/project_sales',compact('year','year_now', 'sidebar_collapse'))->with(['initView'=> $this->initMenuBase(),'feature_item'=>$this->RoleDynamic('Sales')]);
    }

    public function detailSales($lead_id)
    {
        $year = DB::table('sales_lead_register')->select('year')->where('year','!=',NULL)->groupBy('year')->get();

        $year_now = date('Y');

        $sidebar_collapse = true;

        return view('sales/project_detail_sales',compact('year','year_now','sidebar_collapse'))->with(['initView'=> $this->initMenuBase(),'feature_item'=>$this->RoleDynamic('DetailSales')]);
    }

    public function getPresales()
    {
        $getPresales = collect(User::select(DB::raw('`nik` AS `id`,`name` AS `text`'))->where('id_division','TECHNICAL PRESALES')->where('status_karyawan', '!=', 'dummy')->where('id_company','1')->get());

        return array("data" => $getPresales);
    }

    public function getSales(Request $request)
    {
        $getSales = User::select(DB::raw('`nik` AS `id`,`name` AS `text`'))->whereRaw("(`id_company` = '1' AND `id_division` = 'SALES' AND `status_karyawan` != 'dummy' AND `id_position` != 'ADMIN')")->orWhereRaw("(`id_position` = 'MANAGER' AND `id_division` = 'TECHNICAL' AND `id_company` = '1'  AND `id_territory` = 'OPERATION' AND `status_karyawan` != 'dummy')");

        return array("data" => collect($getSales->get()));
    }

    public function getSalesByTerritory(Request $request)
    {
        $getSales = User::select(DB::raw('`nik` AS `id`,`name` AS `text`'))->whereRaw("(`id_company` = '1' AND `id_division` = 'SALES' AND `status_karyawan` != 'dummy' AND `id_position` != 'ADMIN')")->orWhereRaw("(`id_position` = 'MANAGER' AND `id_division` = 'BCD' AND `id_company` = '1')")->orWhereRaw("(`id_position` = 'MANAGER' AND `id_division` = 'TECHNICAL' AND `id_company` = '1'  AND `id_territory` = 'OPERATION')")->where('status_karyawan','!=','dummy');

        if (isset($request->territory)) {
            $getSales->whereIn('id_territory', $request->territory);
        }

        return array("results" => collect($getSales->get()));
    }

    public function showEditLead(Request $request)
    {
        $getListProductLead = DB::table('tb_product_tag_relation')->join('tb_product_tag', 'tb_product_tag_relation.id_product_tag', '=', 'tb_product_tag.id')
                        ->join('tb_technology_tag', 'tb_technology_tag.id', '=', 'tb_product_tag_relation.id_technology_tag')
                        ->select('lead_id', DB::raw('GROUP_CONCAT(`tb_product_tag`.`id`) as `id_product_tag`'), DB::raw('GROUP_CONCAT(`tb_technology_tag`.`id`) AS `id_tech`'))
                        ->groupBy('lead_id');

        // $getListTechTag = DB::table('tb_technology_tag')->join('tb_technology_tag_relation', 'tb_technology_tag_relation.id_tech_tag', '=', 'tb_technology_tag.id')
        //                 ->select('lead_id', DB::raw('GROUP_CONCAT(`tb_technology_tag`.`id`) AS `id_tech`'))
        //                 ->groupBy('lead_id');

        $lead = DB::table('sales_lead_register')
                ->leftJoinSub($getListProductLead, 'product_lead', function($join){
                    $join->on('sales_lead_register.lead_id', '=', 'product_lead.lead_id');
                })
                ->select('sales_lead_register.lead_id', 'sales_lead_register.opp_name', 'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result','sales_lead_register.keterangan', 'sales_lead_register.closing_date', 'sales_lead_register.deal_price', 'id_product_tag', 'id_tech')
                ->where('sales_lead_register.lead_id',$request->lead_id)
                ->get();

        return array("data"=>$lead);
    }

    public function getCustomer()
    {
        $getCustomer = collect(TB_Contact::select(DB::raw('`id_customer` AS `id`,`brand_name` AS `text`'))->where('status', 'Accept')->get());

        return array("data" => $getCustomer);
    }

    public function getCustomerByLead(Request $request)
    {
        $getCustomer = TB_Contact::join('sales_lead_register', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')->select(DB::raw('`tb_contact`.`id_customer` AS `id`,`brand_name` AS `text`'))->where('tb_contact.status', 'Accept')->groupby('tb_contact.id_customer');

        return array("data" => collect($getCustomer->get()));
    }

    public function getPresalesAssign(Request $request)
    {
        $getPresalesAssign = collect(User::join('sales_solution_design', 'sales_solution_design.nik', '=', 'users.nik')->select(DB::raw('`users`.`nik` AS `id`,`name` AS `text`'))->where('lead_id', $request->lead_id)->get());

        return array("data" => $getPresalesAssign);
    }

    public function getTerritory(Request $request)
    {
        $getTerritory = DB::table('users')
            ->select('id_territory')
            ->whereRaw("(`id_division` = 'SALES' OR `id_division` = 'BCD' )")
            ->where('status_karyawan', '!=', 'dummy')
            ->where('id_company', '1')
            ->groupBy('id_territory');

        return $getTerritory->get();
    }

    public function getCompany()
    {
        $getCompany = DB::table('tb_company')->select('id_company', DB::raw("(CASE WHEN (code_company = 'SIP') THEN 'Sinergy Informasi Pratama' WHEN (code_company = 'MSP') THEN 'Multi Solusindo Perkasa' END) as company"))->get();

        return $getCompany;
    }

    public function getResult()
    {
        $getResult = Sales::select(DB::raw("(CASE WHEN (result = 'OPEN') THEN 'INITIAL' WHEN (result = '') THEN 'OPEN' WHEN (result = 'SD') THEN 'SD' WHEN (result = 'TP') THEN 'TP' WHEN (result = 'WIN') THEN 'WIN' WHEN( result = 'LOSE') THEN 'LOSE' WHEN( result = 'HOLD') THEN 'HOLD' WHEN( result = 'SPECIAL') THEN 'SPECIAL' WHEN(result = 'CANCEL') THEN 'CANCEL' END) as result_modif"), DB::raw("(CASE WHEN (result = 'OPEN') THEN 'OPEN' WHEN (result = '') THEN null WHEN (result = 'SD') THEN 'SD' WHEN (result = 'TP') THEN 'TP' WHEN (result = 'WIN') THEN 'WIN' WHEN( result = 'LOSE') THEN 'LOSE' WHEN( result = 'HOLD') THEN 'HOLD' WHEN( result = 'SPECIAL') THEN 'SPECIAL' WHEN(result = 'CANCEL') THEN 'CANCEL' END) as result_value"))
            ->whereRaw("(`result` = 'OPEN' OR `result` = '' OR `result` = 'SD' OR `result` = 'TP' OR `result` = 'WIN' OR `result` = 'LOSE' OR `result` = 'CANCEL' OR `result` = 'HOLD' OR `result` = 'SPECIAL')")
            ->orderByRaw('FIELD(result, "OPEN", "", "SD", "TP", "WIN", "LOSE", "CANCEL", "HOLD", "SPECIAL")')
            ->groupBy('result');

        return $getResult->get();
    }

    function getUserByTerritory(Request $request)
    {
        $getUser = collect(User::select(DB::raw('`users`.`nik` AS `id`,`name` AS `text`'))->where('id_division', 'SALES')->where('status_karyawan', '!=', 'dummy')->where('id_territory', $request->territory)->get());

        return array("data"=>$getUser);
    }

    public function getDataLead(Request $request)
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        $cek_role = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role')->where('user_id',$nik)->first();

        // $year = date('Y');
        $year = [date('Y'), date('Y')-1];

        $getPresales = DB::table('sales_solution_design')->join('users', 'users.nik', '=','sales_solution_design.nik')->selectRaw('GROUP_CONCAT(`users`.`name`) AS `name_presales`, GROUP_CONCAT(`sales_solution_design`.`nik`) AS `nik_presales`')->selectRaw('lead_id')->groupBy('lead_id');

        $leadsnow = DB::table('sales_lead_register')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->leftJoinSub($getPresales, 'tb_presales',function($join){
                    $join->on("sales_lead_register.lead_id", '=', 'tb_presales.lead_id');
                })
                ->join('users as u_sales', 'u_sales.nik', '=', 'sales_lead_register.nik')
                ->Leftjoin('tb_pid', 'tb_pid.lead_id', '=', 'sales_lead_register.lead_id')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.created_at', 'sales_lead_register.amount', 'u_sales.name as name','sales_lead_register.nik','sales_lead_register.keterangan','sales_lead_register.year', 'sales_lead_register.closing_date', 'sales_lead_register.deal_price','u_sales.id_territory', 'tb_pid.status','tb_presales.name_presales', DB::raw("(CASE WHEN (result = 'OPEN') THEN 'INITIAL' WHEN (result = '') THEN 'OPEN' WHEN (result = 'SD') THEN 'SD' WHEN (result = 'TP') THEN 'TP' WHEN (result = 'WIN') THEN 'WIN' WHEN( result = 'LOSE') THEN 'LOSE' WHEN( result = 'HOLD') THEN 'HOLD' WHEN( result = 'SPECIAL') THEN 'SPECIAL' WHEN(result = 'CANCEL') THEN 'CANCEL' END) as result_modif"))
                ->orderByRaw('FIELD(result, "OPEN", "", "SD", "TP", "WIN", "LOSE", "CANCEL", "HOLD")')
                ->where('result','!=','hmm')
                ->whereIn('year',$year)
                // ->where('year', $year)
                ->orderBy('created_at', 'desc');

        if ($div == 'BCD') {
            if ($div == 'BCD' && $pos == 'ADMIN') {
                $leadsnow->where('u_sales.id_company', '1');
            }
        }
         
        if($ter != null && $div != 'BCD' || $cek_role->name_role == 'Operations Director'){
            $leadsnow->where('u_sales.id_company', '1');
            if ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
                $leadsnow->where('nik_presales', $nik);
            } else if ($div == 'SALES' && $pos == 'MANAGER') {
                $leadsnow->where('u_sales.id_territory', $ter);
            } else if ($div == 'SALES' && $pos == 'STAFF') {
                $leadsnow->where('u_sales.nik', $nik);
            } 
        }  

        return array("data"=>$leadsnow->get());
    }

    public function getFilterLead(Request $request)
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;

        $getPresales = DB::table('sales_solution_design')->join('users', 'users.nik', '=','sales_solution_design.nik')->selectRaw('GROUP_CONCAT(`users`.`name`) AS `name_presales`, GROUP_CONCAT(`sales_solution_design`.`nik`) AS `nik_presales`')->selectRaw('lead_id')->groupBy('lead_id');

        $getListProductLead = DB::table('tb_product_tag')->join('tb_product_tag_relation', 'tb_product_tag_relation.id_product_tag', '=', 'tb_product_tag.id')
                        ->select('lead_id', DB::raw('GROUP_CONCAT(`tb_product_tag`.`id`) as `id_product_tag`'))
                        ->groupBy('lead_id');

        $getListTechTag = DB::table('tb_technology_tag')->join('tb_technology_tag_relation', 'tb_technology_tag_relation.id_tech_tag', '=', 'tb_technology_tag.id')
                        ->select('lead_id', DB::raw('GROUP_CONCAT(`tb_technology_tag`.`id`) AS `id_tech`'))
                        ->groupBy('lead_id');
             
        $leadsnow = DB::table('sales_lead_register')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('users as u_sales', 'u_sales.nik', '=', 'sales_lead_register.nik')
                ->leftJoinSub($getPresales, 'tb_presales',function($join){
                    $join->on("sales_lead_register.lead_id", '=', 'tb_presales.lead_id');
                })
                ->leftJoinSub($getListProductLead, 'product_lead', function($join){
                    $join->on('sales_lead_register.lead_id', '=', 'product_lead.lead_id');
                })
                ->leftJoinSub($getListTechTag, 'tech_tag', function($join){
                    $join->on('sales_lead_register.lead_id', '=', 'tech_tag.lead_id');
                })
                ->Leftjoin('tb_pid', 'tb_pid.lead_id', '=', 'sales_lead_register.lead_id')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.created_at', 'sales_lead_register.amount', 'u_sales.name as name','sales_lead_register.nik','sales_lead_register.keterangan','sales_lead_register.year', 'sales_lead_register.closing_date', 'sales_lead_register.deal_price','u_sales.id_territory', 'name_presales', 'nik_presales', DB::raw("(CASE WHEN (result = 'OPEN') THEN 'INITIAL' WHEN (result = '') THEN 'OPEN' WHEN (result = 'SD') THEN 'SD' WHEN (result = 'TP') THEN 'TP' WHEN (result = 'WIN') THEN 'WIN' WHEN( result = 'LOSE') THEN 'LOSE' WHEN( result = 'HOLD') THEN 'HOLD' WHEN( result = 'SPECIAL') THEN 'SPECIAL' WHEN(result = 'CANCEL') THEN 'CANCEL' END) as result_modif"), 'id_product_tag', 'id_tech', 'tb_pid.status')
                ->orderByRaw('FIELD(result, "OPEN", "", "SD", "TP", "WIN", "LOSE", "CANCEL", "HOLD")')
                ->orderBy('created_at', 'desc');

        $leads = $leadsnow->where('result','!=', 'hmm');

        if ($div == 'SALES') {
            $leads->where('id_territory', $ter);
        } 

        if(isset($request->year)){
            $leads->whereIn('year',$request->year)
                   ->orWhereIn('DB::raw("YEAR(closing_date)")', '=', ['2021','2020']) ;
        }

        if(isset($request->territory)){
            $leads->whereIn('u_sales.id_territory',$request->territory);
        }

        if(isset($request->company)){
            $leads->whereIn('u_sales.id_company',$request->company);
        }

        if(isset($request->result)){
            if(in_array("null", $request->result)){
                $leads->whereIn('sales_lead_register.result',array_merge($request->result,['']));
            } else {
                $leads->whereIn('sales_lead_register.result',$request->result);
            }
        }

        if (isset($request->sales_name)) {
            $leads->whereIn('u_sales.nik',$request->sales_name);
            
        }

        if (isset($request->presales_name)) {
            $leads->whereIn('nik_presales',$request->presales_name);   
        }

        if (isset($request->product_tag)) {
            $leads->whereIn('id_product_tag',$request->product_tag);
        }

        if (isset($request->tech_tag)) {
            $leads->whereIn('id_tech',$request->tech_tag);
        }
        
        if (isset($request->customer)) {
            $leads->whereIn('tb_contact.id_customer',$request->customer);
        }

        return array("data"=>$leads->get());

    }

     public function filterCountLead(Request $request)
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        $getPresales = DB::table('sales_solution_design')->join('users', 'users.nik', '=','sales_solution_design.nik')->selectRaw('GROUP_CONCAT(`users`.`name`) AS `name_presales`, GROUP_CONCAT(`sales_solution_design`.`nik`) AS `nik_presales`')->selectRaw('lead_id')->groupBy('lead_id');
        // return $getPresales->get();

        $getListProductLead = DB::table('tb_product_tag')->join('tb_product_tag_relation', 'tb_product_tag_relation.id_product_tag', '=', 'tb_product_tag.id')
                        ->select('lead_id', DB::raw('GROUP_CONCAT(`tb_product_tag`.`id`) as `id_product_tag`'))
                        ->groupBy('lead_id');

        $getListTechTag = DB::table('tb_technology_tag')->join('tb_technology_tag_relation', 'tb_technology_tag_relation.id_tech_tag', '=', 'tb_technology_tag.id')
                        ->select('lead_id', DB::raw('GROUP_CONCAT(`tb_technology_tag`.`id`) AS `id_tech`'))
                        ->groupBy('lead_id');

        $total_lead = DB::table('sales_lead_register')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->leftJoinSub($getPresales, 'tb_presales',function($join){
                    $join->on("sales_lead_register.lead_id", '=', 'tb_presales.lead_id');
                })
                ->leftJoinSub($getListProductLead, 'product_lead', function($join){
                    $join->on('sales_lead_register.lead_id', '=', 'product_lead.lead_id');
                })
                ->leftJoinSub($getListTechTag, 'tech_tag', function($join){
                    $join->on('sales_lead_register.lead_id', '=', 'tech_tag.lead_id');
                })
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik');
                // ->where('sales_lead_register.result','!=','hmm');

        $total_initial = DB::table('sales_lead_register')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->leftJoinSub($getPresales, 'tb_presales',function($join){
                        $join->on("sales_lead_register.lead_id", '=', 'tb_presales.lead_id');
                    })
                    ->leftJoinSub($getListProductLead, 'product_lead', function($join){
                        $join->on('sales_lead_register.lead_id', '=', 'product_lead.lead_id');
                    })
                    ->leftJoinSub($getListTechTag, 'tech_tag', function($join){
                        $join->on('sales_lead_register.lead_id', '=', 'tech_tag.lead_id');
                    })
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik');
                    // ->where('sales_lead_register.result','OPEN');

        $total_open = DB::table('sales_lead_register')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->leftJoinSub($getPresales, 'tb_presales',function($join){
                        $join->on("sales_lead_register.lead_id", '=', 'tb_presales.lead_id');
                    })
                    ->leftJoinSub($getListProductLead, 'product_lead', function($join){
                        $join->on('sales_lead_register.lead_id', '=', 'product_lead.lead_id');
                    })
                    ->leftJoinSub($getListTechTag, 'tech_tag', function($join){
                        $join->on('sales_lead_register.lead_id', '=', 'tech_tag.lead_id');
                    })
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik');
                    // ->where('sales_lead_register.result','');

        $total_sd = DB::table('sales_lead_register')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->leftJoinSub($getPresales, 'tb_presales',function($join){
                        $join->on("sales_lead_register.lead_id", '=', 'tb_presales.lead_id');
                    })
                    ->leftJoinSub($getListProductLead, 'product_lead', function($join){
                        $join->on('sales_lead_register.lead_id', '=', 'product_lead.lead_id');
                    })
                    ->leftJoinSub($getListTechTag, 'tech_tag', function($join){
                        $join->on('sales_lead_register.lead_id', '=', 'tech_tag.lead_id');
                    })
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik');
                    // ->where('sales_lead_register.result','SD');

        $total_tp = DB::table('sales_lead_register')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->leftJoinSub($getPresales, 'tb_presales',function($join){
                        $join->on("sales_lead_register.lead_id", '=', 'tb_presales.lead_id');
                    })
                    ->leftJoinSub($getListProductLead, 'product_lead', function($join){
                        $join->on('sales_lead_register.lead_id', '=', 'product_lead.lead_id');
                    })
                    ->leftJoinSub($getListTechTag, 'tech_tag', function($join){
                        $join->on('sales_lead_register.lead_id', '=', 'tech_tag.lead_id');
                    })
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik');
                    // ->where('sales_lead_register.result','TP');

        $total_win = DB::table('sales_lead_register')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->leftJoinSub($getPresales, 'tb_presales',function($join){
                        $join->on("sales_lead_register.lead_id", '=', 'tb_presales.lead_id');
                    })
                    ->leftJoinSub($getListProductLead, 'product_lead', function($join){
                        $join->on('sales_lead_register.lead_id', '=', 'product_lead.lead_id');
                    })
                    ->leftJoinSub($getListTechTag, 'tech_tag', function($join){
                        $join->on('sales_lead_register.lead_id', '=', 'tech_tag.lead_id');
                    })
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik');
                    // ->where('sales_lead_register.result','WIN');

        $total_lose = DB::table('sales_lead_register')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->leftJoinSub($getPresales, 'tb_presales',function($join){
                        $join->on("sales_lead_register.lead_id", '=', 'tb_presales.lead_id');
                    })
                    ->leftJoinSub($getListProductLead, 'product_lead', function($join){
                        $join->on('sales_lead_register.lead_id', '=', 'product_lead.lead_id');
                    })
                    ->leftJoinSub($getListTechTag, 'tech_tag', function($join){
                        $join->on('sales_lead_register.lead_id', '=', 'tech_tag.lead_id');
                    })
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik');
                    // ->where('sales_lead_register.result','LOSE');

        $total_cancel = DB::table('sales_lead_register')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->leftJoinSub($getPresales, 'tb_presales',function($join){
                        $join->on("sales_lead_register.lead_id", '=', 'tb_presales.lead_id');
                    })
                    ->leftJoinSub($getListProductLead, 'product_lead', function($join){
                        $join->on('sales_lead_register.lead_id', '=', 'product_lead.lead_id');
                    })
                    ->leftJoinSub($getListTechTag, 'tech_tag', function($join){
                        $join->on('sales_lead_register.lead_id', '=', 'tech_tag.lead_id');
                    })
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik');
                    // ->where('sales_lead_register.result','CANCEL');

        $presales = false;

        if ($div == 'SALES' && $pos == 'STAFF') {
            $total_lead->where('sales_lead_register.result',"!=","hmm")->where('sales_lead_register.nik',$nik);
            $total_open->where('sales_lead_register.result',"")->where('sales_lead_register.nik',$nik);
            $total_initial->where('sales_lead_register.result',"OPEN")->where('sales_lead_register.nik',$nik);
            $total_sd->where('sales_lead_register.result',"SD")->where('sales_lead_register.nik',$nik);
            $total_tp->where('sales_lead_register.result',"TP")->where('sales_lead_register.nik',$nik);
            $total_win->where('sales_lead_register.result',"WIN")->where('sales_lead_register.nik',$nik);
            $total_lose->where('sales_lead_register.result',"LOSE")->where('sales_lead_register.nik',$nik);
            $total_cancel->where('sales_lead_register.result',"CANCEL")->where('sales_lead_register.nik',$nik);
        } elseif ($div == 'TECHNICAL PRESALES' && $pos == 'MANAGER') {
            $total_lead->where('sales_lead_register.result',"OPEN")->where('users.id_company','1');
            $total_open->where('sales_lead_register.result',"")->where('users.id_company','1');
            $total_initial->where('sales_lead_register.result',"OPEN")->where('users.id_company','1');
            $total_sd->where('sales_lead_register.result',"SD")->where('users.id_company','1');
            $total_tp->where('sales_lead_register.result',"TP")->where('users.id_company','1');
            $total_win->where('sales_lead_register.result',"WIN")->where('users.id_company','1');
            $total_lose->where('sales_lead_register.result',"LOSE")->where('users.id_company','1');
            $total_cancel->where('sales_lead_register.result',"CANCEL")->where('users.id_company','1');
            if ($div == 'TECHNICAL PRESALES' && $pos == 'MANAGER') {
                $presales = true;
            } else {
                $presales = false;
            }
        } elseif ($div == 'SALES' && $pos == 'MANAGER') {
            $total_lead->where('sales_lead_register.result',"!=","hmm")->where('users.id_territory',$ter);
            $total_open->where('sales_lead_register.result',"")->where('users.id_territory',$ter);
            $total_initial->where('sales_lead_register.result',"OPEN")->where('users.id_territory',$ter);
            $total_sd->where('sales_lead_register.result',"SD")->where('users.id_territory',$ter);
            $total_tp->where('sales_lead_register.result',"TP")->where('users.id_territory',$ter);
            $total_win->where('sales_lead_register.result',"WIN")->where('users.id_territory',$ter);
            $total_lose->where('sales_lead_register.result',"LOSE")->where('users.id_territory',$ter);
            $total_cancel->where('sales_lead_register.result',"CANCEL")->where('users.id_territory',$ter);
        } elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $total_lead->where('sales_lead_register.result',"!=","hmm")->where('nik_presales',$nik);
            $total_open->where('sales_lead_register.result',"")->where('nik_presales',$nik);
            $total_initial->where('sales_lead_register.result',"OPEN")->where('nik_presales',$nik);
            $total_sd->where('sales_lead_register.result',"SD")->where('nik_presales',$nik);
            $total_tp->where('sales_lead_register.result',"TP")->where('nik_presales',$nik);
            $total_win->where('sales_lead_register.result',"WIN")->where('nik_presales',$nik);
            $total_lose->where('sales_lead_register.result',"LOSE")->where('nik_presales',$nik);
            $total_cancel->where('sales_lead_register.result',"CANCEL")->where('nik_presales',$nik);
        } else {
            $total_lead->where('sales_lead_register.result',"!=","hmm");
            $total_open->where('sales_lead_register.result',"");
            $total_initial->where('sales_lead_register.result',"OPEN");
            $total_sd->where('sales_lead_register.result',"SD");
            $total_tp->where('sales_lead_register.result',"TP");
            $total_win->where('sales_lead_register.result',"WIN");
            $total_lose->where('sales_lead_register.result',"LOSE");
            $total_cancel->where('sales_lead_register.result',"CANCEL");
        }        

        // Year
        if(!in_array(null,$request->year)){
            $total_lead->whereIn('year',$request->year);
            $total_open->whereIn('year',$request->year);
            $total_sd->whereIn('year',$request->year);
            $total_tp->whereIn('year',$request->year);
            $total_win->whereIn('year',$request->year);
            $total_lose->whereIn('year',$request->year);
            $total_initial->whereIn('year',$request->year);
            $total_cancel->whereIn('year',$request->year);
        }

        // Territory
        if(!in_array(null,$request->territory)){
            $total_lead->whereIn('id_territory',$request->territory);
            $total_open->whereIn('id_territory',$request->territory);
            $total_sd->whereIn('id_territory',$request->territory);
            $total_tp->whereIn('id_territory',$request->territory);
            $total_win->whereIn('id_territory',$request->territory);
            $total_lose->whereIn('id_territory',$request->territory);
            $total_initial->whereIn('id_territory',$request->territory);
            $total_cancel->whereIn('id_territory',$request->territory);
        }


        // Company
        if(!in_array(null,$request->company)){
            $total_lead->whereIn('id_company',$request->company);
            $total_open->whereIn('id_company',$request->company);
            $total_sd->whereIn('id_company',$request->company);
            $total_tp->whereIn('id_company',$request->company);
            $total_win->whereIn('id_company',$request->company);
            $total_lose->whereIn('id_company',$request->company);
            $total_initial->whereIn('id_company',$request->company);
            $total_cancel->whereIn('id_company',$request->company);
        }

        if (!in_array(null,$request->sales_name)) {
            $total_lead->whereIn('sales_lead_register.nik',$request->sales_name);
            $total_open->whereIn('sales_lead_register.nik',$request->sales_name);
            $total_sd->whereIn('sales_lead_register.nik',$request->sales_name);
            $total_tp->whereIn('sales_lead_register.nik',$request->sales_name);
            $total_win->whereIn('sales_lead_register.nik',$request->sales_name);
            $total_lose->whereIn('sales_lead_register.nik',$request->sales_name);
            $total_initial->whereIn('sales_lead_register.nik',$request->sales_name);
            $total_cancel->whereIn('sales_lead_register.nik',$request->sales_name);
            
        }

        if (!in_array(null,$request->presales_name)) {
            $total_lead->whereIn('nik_presales',$request->presales_name);
            $total_open->whereIn('nik_presales',$request->presales_name);
            $total_sd->whereIn('nik_presales',$request->presales_name);
            $total_tp->whereIn('nik_presales',$request->presales_name);
            $total_win->whereIn('nik_presales',$request->presales_name);
            $total_lose->whereIn('nik_presales',$request->presales_name);  
            $total_initial->whereIn('nik_presales',$request->presales_name);  
            $total_cancel->whereIn('nik_presales',$request->presales_name);  
        }

        if (!in_array(null,$request->product_tag)) {
            $total_lead->whereIn('id_product_tag',$request->product_tag);
            $total_open->whereIn('id_product_tag',$request->product_tag);
            $total_sd->whereIn('id_product_tag',$request->product_tag);
            $total_tp->whereIn('id_product_tag',$request->product_tag);
            $total_win->whereIn('id_product_tag',$request->product_tag);
            $total_lose->whereIn('id_product_tag',$request->product_tag); 
            $total_initial->whereIn('id_product_tag',$request->product_tag); 
            $total_cancel->whereIn('id_product_tag',$request->product_tag); 
        }

        if (!in_array(null,$request->tech_tag)) {
            $total_lead->whereIn('id_tech',$request->tech_tag);
            $total_open->whereIn('id_tech',$request->tech_tag);
            $total_sd->whereIn('id_tech',$request->tech_tag);
            $total_tp->whereIn('id_tech',$request->tech_tag);
            $total_win->whereIn('id_tech',$request->tech_tag);
            $total_lose->whereIn('id_tech',$request->tech_tag);
            $total_initial->whereIn('id_tech',$request->tech_tag);
            $total_cancel->whereIn('id_tech',$request->tech_tag);
        }
        
        if (!in_array(null,$request->customer)) {
            $total_lead->whereIn('tb_contact.id_customer',$request->customer);
            $total_open->whereIn('tb_contact.id_customer',$request->customer);
            $total_sd->whereIn('tb_contact.id_customer',$request->customer);
            $total_tp->whereIn('tb_contact.id_customer',$request->customer);
            $total_win->whereIn('tb_contact.id_customer',$request->customer);
            $total_lose->whereIn('tb_contact.id_customer',$request->customer);
            $total_initial->whereIn('tb_contact.id_customer',$request->customer);
            $total_cancel->whereIn('tb_contact.id_customer',$request->customer);
        }

        $total_initial_unfiltered = $total_initial->where('sales_lead_register.result',"OPEN")->count();
        $total_open_unfiltered = $total_open->where('sales_lead_register.result',"")->count();
        $total_sd_unfiltered = $total_sd->where('sales_lead_register.result',"SD")->count();
        $total_tp_unfiltered = $total_tp->where('sales_lead_register.result',"TP")->count();
        $total_win_unfiltered = $total_win->where('sales_lead_register.result',"WIN")->count();
        $total_lose_unfiltered = $total_lose->where('sales_lead_register.result',"LOSE")->count();
        $total_cancel_unfiltered = $total_cancel->where('sales_lead_register.result',"CANCEL")->count();

        if(!in_array(null,$request->result)){
            if (in_array("null", $request->result)) {
                $total_lead->whereNull('sales_lead_register.result');
            }
            $total_lead->whereIn('sales_lead_register.result',$request->result);

            $total_lead->where(function ($query) use ($request,$total_initial,$total_open,$total_sd,$total_tp,$total_win,$total_lose,$ter,$div,$pos){
                // Init
                if (!in_array("OPEN", $request->result)) {
                    $total_initial->where('sales_lead_register.result',"hmm");
                } else {
                    $query->orWhere('sales_lead_register.result',"OPEN");
                }

                if (!in_array("null", $request->result)) {
                    $total_open->where('sales_lead_register.result',"hmm");
                } else {
                    $query->where('sales_lead_register.result',"");
                }

                if (!in_array("SD", $request->result)) {
                    $total_sd->where('sales_lead_register.result',"hmm");
                } else {
                    $query->orWhere('sales_lead_register.result',"SD");
                }

                if (!in_array("TP", $request->result)) {
                    $total_tp->where('sales_lead_register.result',"hmm");
                } else {
                    $query->orWhere('sales_lead_register.result',"TP");
                }

                if (!in_array("WIN", $request->result)) {
                    $total_win->where('sales_lead_register.result',"hmm");
                } else {
                    $query->orWhere('sales_lead_register.result',"WIN");
                }

                if (!in_array("LOSE", $request->result)) {
                    $total_lose->where('sales_lead_register.result',"hmm");

                } else {
                    $query->orWhere('sales_lead_register.result',"LOSE");
                }

            });
        }

        // if($ter != null){
        //     if ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
        //     } else if ($div == 'SALES') {

        //     } else if ($div == 'TECHNICAL PRESALES' && $pos == 'MANAGER') {
               
        //     } else {

        //     }             
        // } else {
        //     $count_lead = $total_lead->count('sales_lead_register.lead_id');

        //     $count_open = $total_open->where('result','')->count('sales_lead_register.lead_id');

        //     $count_sd = $total_sd->count('sales_lead_register.lead_id');

        //     $count_tp = $total_tp->where('result','TP')->count('sales_lead_register.lead_id');

        //     $count_win = $total_win->where('result','WIN')->count('sales_lead_register.lead_id');

        //     $count_lose = $total_lose->where('result','LOSE')->count('sales_lead_register.lead_id');

        //     $sum_amount_lead = $total_lead->select(DB::raw('(CASE WHEN(SUM(amount) is null) THEN "0" ELSE SUM(amount) END) AS amount_lead'))->first();

        //     $sum_amount_open = $total_open->select(DB::raw('(CASE WHEN(SUM(amount) is null) THEN "0" ELSE SUM(amount) END) as amount_open'))->first();

        //     $sum_amount_sd = $total_sd->select(DB::raw('(CASE WHEN(SUM(amount) is null) THEN "0" ELSE SUM(amount) END) as amount_sd'))->first();

        //     $sum_amount_tp = $total_tp->select(DB::raw('(CASE WHEN(SUM(amount) is null) THEN "0" ELSE SUM(amount) END) as amount_tp'))->first();

        //     $sum_amount_win = $total_win->select(DB::raw('(CASE WHEN(SUM(amount) is null) THEN "0" ELSE SUM(amount) END) as amount_win'))->first();

        //     $sum_amount_lose = $total_lose->select(DB::raw('(CASE WHEN(SUM(amount) is null) THEN "0" ELSE SUM(amount) END) as amount_lose'))->first();

        // }
        $count_lead = $total_lead->count('sales_lead_register.lead_id');

        $count_open = $total_open->where('result','')->count('sales_lead_register.lead_id');

        $count_sd = $total_sd->count('sales_lead_register.lead_id');

        $count_tp = $total_tp->where('result','TP')->count('sales_lead_register.lead_id');

        $count_win = $total_win->where('result','WIN')->count('sales_lead_register.lead_id');

        $count_lose = $total_lose->where('result','LOSE')->count('sales_lead_register.lead_id');
        
        $count_initial = $total_initial->where('result','OPEN')->count('sales_lead_register.lead_id');
        
        $count_cancel = $total_cancel->where('result','CANCEL')->count('sales_lead_register.lead_id');

        $sum_amount_lead = $total_lead->select(DB::raw('(CASE WHEN(SUM(amount) is null) THEN "0" ELSE SUM(amount) END) AS amount_lead'))->first();

        $sum_amount_open = $total_open->select(DB::raw('(CASE WHEN(SUM(amount) is null) THEN "0" ELSE SUM(amount) END) as amount_open'))->first();

        $sum_amount_sd = $total_sd->select(DB::raw('(CASE WHEN(SUM(amount) is null) THEN "0" ELSE SUM(amount) END) as amount_sd'))->first();

        $sum_amount_tp = $total_tp->select(DB::raw('(CASE WHEN(SUM(amount) is null) THEN "0" ELSE SUM(amount) END) as amount_tp'))->first();

        $sum_amount_win = $total_win->select(DB::raw('(CASE WHEN(SUM(amount) is null) THEN "0" ELSE SUM(amount) END) as amount_win'))->first();

        $sum_amount_lose = $total_lose->select(DB::raw('(CASE WHEN(SUM(amount) is null) THEN "0" ELSE SUM(amount) END) as amount_lose'))->first();

        // return $count_tp;

        return collect([
            'lead'=>$count_lead,
            'initial'=>$count_initial,
            'open'=>$count_open,
            'sd'=>$count_sd,
            'tp'=>$count_tp,
            'win'=>$count_win,
            'lose'=>$count_lose,
            'cancel'=>$count_cancel,
            'initial_unfiltered'=>$total_initial_unfiltered,
            'open_unfiltered'=>$total_open_unfiltered,
            'sd_unfiltered'=>$total_sd_unfiltered,
            'tp_unfiltered'=>$total_tp_unfiltered,
            'win_unfiltered'=>$total_win_unfiltered,
            'lose_unfiltered'=>$total_lose_unfiltered,
            'cancel_unfiltered'=>$total_cancel_unfiltered,
            'amount_lead'=>$sum_amount_lead->amount_lead,
            'amount_open'=>$sum_amount_open->amount_open,
            'amount_sd'=>$sum_amount_sd->amount_sd,
            'amount_tp'=>$sum_amount_tp->amount_tp,
            'amount_win'=>$sum_amount_win->amount_win,
            'amount_lose'=>$sum_amount_lose->amount_lose,
            'presales'=>$presales
        ]);
    }

    public function update_lead_register(Request $request)
    {
        $lead_id = $request['lead_id_edit'];

        $update = Sales::where('lead_id',$lead_id)->first();
        $update->opp_name   = $request['opp_name_edit'];
        if ($request['amount_edit'] != NULL) {
            $update->amount = str_replace('.', '', $request['amount_edit']);
        }else{
            $update->amount = $request['amount_edit'];
        }
        // $update->created_at = $request['create_date_edit'];
        $edate_edit = strtotime($_POST['closing_date_edit']); 
        $edate_edit = date("Y-m-d",$edate_edit);

        $update->closing_date = $edate_edit;
        $update->keterangan = $request['note_edit'];
        $update->update();

        $amount = str_replace('.', '', $request['amount_edit']);

        $tambah_log = new SalesChangeLog();
        $tambah_log->lead_id = $lead_id;
        $tambah_log->nik = Auth::User()->nik;
        $tambah_log->status = 'Update Lead with Amount ';
        $tambah_log->submit_price  = $amount;
        $tambah_log->save();

        if (isset($request->id)) {
            $name_tagging = ProductTagRelation::join('tb_product_tag', 'tb_product_tag_relation.id_product_tag', '=', 'tb_product_tag.id')
                    ->join('tb_technology_tag', 'tb_product_tag_relation.id_technology_tag', '=', 'tb_technology_tag.id')
                    ->select('name_product', 'name_tech', 'price', 'tb_product_tag_relation.id')
                    ->whereIn('tb_product_tag_relation.id', $request->id)->get();

            if(isset($name_tagging)){
                foreach($name_tagging as $data){
                    $add_changelog = new SalesChangeLog();
                    $add_changelog->lead_id = $lead_id;
                    $add_changelog->nik = Auth::User()->nik;
                    $add_changelog->status = 'Delete Tagging Product ' .  $data['name_product'] . ', Technology ' .  $data['name_tech'] . ', with Price ' . str_replace('.', '', $data['price']);
                    $add_changelog->save();
                }    
            }

            if (isset($name_tagging)) {
                foreach ($name_tagging as $key => $value) {
                    ProductTagRelation::where('id',$value->id)->delete(); 
                }
            } 
        }

        if(isset($request->tagProduct)){
            foreach ($request->tagProduct as $key => $value) {
                $store = new ProductTagRelation;
                $store->lead_id = $lead_id;
                $store->id_product_tag = $value['productTag'];
                $store->id_technology_tag = $value['technologyTag'];
                $store->price = $value['price'];
                $store->save(); 

                $add_changelog = new SalesChangeLog();
                $add_changelog->lead_id = $lead_id;
                $add_changelog->nik = Auth::User()->nik;
                $add_changelog->status = 'Add Tagging Product ' .  $value['productTagText'] . ', Technology ' .  $value['technologyTagText'] . ', with Price ' . str_replace('.', '', $value['price']);
                $add_changelog->save();
            }
        }

        return redirect()->back()->with('update','Lead Register Has Been Updated!'); 
    }

    public function getDetailLead(Request $request)
    {
        $getListProductLead = DB::table('tb_product_tag')->join('tb_product_tag_relation', 'tb_product_tag_relation.id_product_tag', '=', 'tb_product_tag.id')
                        ->join('tb_technology_tag','tb_technology_tag.id','=','tb_product_tag_relation.id_technology_tag')
                        ->select('lead_id', DB::raw('GROUP_CONCAT(`tb_product_tag`.`name_product`) as `name_product_tag`'), DB::raw('GROUP_CONCAT(`tb_technology_tag`.`name_tech`) AS `name_tech`'))
                        ->groupBy('lead_id');

        // $getListTechTag = DB::table('tb_technology_tag')->join('tb_technology_tag_relation', 'tb_technology_tag_relation.id_technology_tag', '=', 'tb_technology_tag.id')
        //                 ->select('lead_id', DB::raw('GROUP_CONCAT(`tb_technology_tag`.`name_tech`) AS `name_tech`'))
        //                 ->groupBy('lead_id');

        $getPresales = DB::table('sales_solution_design')->join('users', 'users.nik', '=','sales_solution_design.nik')->selectRaw('GROUP_CONCAT(`users`.`name`) AS `name_presales`')->selectRaw('lead_id')->groupBy('lead_id');

        $lead = DB::table('sales_lead_register')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->joinSub($getPresales, 'tb_presales',function($join){
                    $join->on("sales_lead_register.lead_id", '=', 'tb_presales.lead_id');
                })
                ->leftJoinSub($getListProductLead, 'product_lead', function($join){
                    $join->on('sales_lead_register.lead_id', '=', 'product_lead.lead_id');
                })
                ->select('sales_lead_register.lead_id', 'sales_lead_register.opp_name', 'sales_lead_register.amount', 'sales_lead_register.closing_date', 'sales_lead_register.deal_price','name_presales', 'name', 'customer_legal_name','sales_lead_register.result', DB::raw("(CASE WHEN (name_product_tag is null) THEN '' ELSE name_product_tag END) as name_product_tag"), DB::raw("(CASE WHEN (name_tech is null) THEN '' ELSE name_tech END) as name_tech"), DB::raw("(CASE WHEN (keterangan is null) THEN '' ELSE keterangan END) as keterangan"), 'sales_lead_register.id_customer')
                ->where('sales_lead_register.lead_id',$request->lead_id)
                ->get();

        return array("data"=>$lead);
    }

    public function changeNominal(Request $request)
    {
        $getData = Sales::join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('amount', 'deal_price', 'sales_lead_register.created_at', 'opp_name', 'customer_legal_name')->where('lead_id', $request->lead_id)->first();

        $requestChange = new RequestChange();
        $requestChange->type = "Change Nominal";
        $requestChange->requester = Auth::user()->name;
        $requestChange->object_id = $request->lead_id;
        $requestChange->parameter1_before = $getData->deal_price;
        $requestChange->parameter1_after = str_replace('.', '', $request['input_amount']);     
        $requestChange->status = "On-Progress";
        $requestChange->save();

        $mail = new EmailChangeCustomer(collect([
                    "type" => "Change Nominal",
                    "to" => "Rony Cahyadi",
                    "lead_id" => $request->lead_id,
                    "reason" => $request->input_reason,
                    "requestor" => Auth::user()->name,
                    "nominal_after" => $request->input_amount,
                    "nominal_before" => $getData->deal_price,
                    "customer" => $getData->customer_legal_name,
                    "project" => $getData->opp_name,
                    "created_at" => $getData->created_at,
                    "url" =>  url("/requestChange?id_requestChange=" . $requestChange->id)
                ])
            );
        
        Mail::to("rony@sinergy.co.id")->send($mail);

        return $mail;
    }

    public function changeCustomer(Request $request)
    {
        $cus = Sales::join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('opp_name', 'customer_legal_name', 'sales_lead_register.created_at', 'sales_lead_register.id_customer')->where('lead_id', $request->lead_id)->first();

        $getCus = TB_Contact::select('customer_legal_name')->where('id_customer', $request->input_cus)->first();

        $requestChange = new RequestChange();
        $requestChange->type = "Change Customer";
        $requestChange->requester = Auth::user()->name;
        $requestChange->object_id = $request->lead_id;
        $requestChange->parameter1_before = $cus->id_customer;
        $requestChange->parameter1_after = $request->input_cus; 
        $requestChange->parameter2_before = $cus->customer_legal_name;
        $requestChange->parameter2_after = $getCus->customer_legal_name;      
        $requestChange->status = "On-Progress";
        $requestChange->save();

        $mail = new EmailChangeCustomer(collect([
                    "type" => "Change Customer",
                    "to" => "Rony Cahyadi",
                    "lead_id" => $request->lead_id,
                    "reason" => $request->input_reason,
                    "requestor" => Auth::user()->name,
                    "customer_after" => $getCus->customer_legal_name,
                    "customer_before" => $cus->customer_legal_name,
                    "project" => $cus->opp_name,
                    "created_at" => $cus->created_at,
                    "url" =>  url("/requestChange?id_requestChange=" . $requestChange->id)
                ])
            );
        
        Mail::to("rony@sinergy.co.id")->send($mail);

        return $mail;
    }

    public function getChangeLog(Request $request)
    {
        $change_log = SalesChangeLog::join('sales_lead_register', 'sales_change_log.lead_id', '=', 'sales_lead_register.lead_id')
                        ->join('users', 'sales_change_log.nik', '=', 'users.nik')
                        ->select('sales_change_log.created_at', 'sales_lead_register.opp_name', 'sales_change_log.status', 'users.name', 'sales_change_log.submit_price', 'sales_change_log.deal_price', 'sales_change_log.progress_date')
                        ->where('sales_change_log.lead_id',$request->lead_id)
                        ->orderBy('created_at', 'desc')
                        ->get();

        return array("data" => $change_log);
    }

    public function getProductTag(Request $request)
    {
        $getListProductLead = collect(ProductTag::select(DB::raw('`id`,`name_product` AS `text`'))->orderBy('name_product','asc')->get());

        return array("results" => $getListProductLead);
    }

    public function getTechTag(Request $request)
    {
        $getListTechTag = collect(TechnologyTag::select(DB::raw('`id`,`name_tech` AS `text`'))->orderBy('name_tech','asc')->get());

        return array("results" => $getListTechTag);
    }

    public function getProductTechTag(Request $request)
    {

        $getListProductLead = DB::table('tb_product_tag')->selectRaw('CONCAT("p",`id`) AS `id`,`name_product` AS `text`')
                // ->selectRaw('`id` AS `id`,`name_product` AS `text`')
                ->get(); 

        $getListTechTag = DB::table('tb_technology_tag')->selectRaw('CONCAT("t",`id`) AS `id`,`name_tech` AS `text`')
                // ->selectRaw('`id` AS `id`,`name_tech` AS `text`')
                ->get();

        return array(
            collect(["id"=>0,"text"=>'Product',"children"=>$getListProductLead]),
            collect(["id"=>1,"text"=>'Technology',"children"=>$getListTechTag])
        ); 

        // return array("product_tag"=>$getListProductLead,"technology_tag"=>$getListTechTag);
    }

    public function getLeadTp(Request $request)
    {
        $lead = TenderProcess::join('sales_lead_register', 'sales_tender_process.lead_id', '=', 'sales_lead_register.lead_id')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_tender_process.lead_id', 
                        'sales_lead_register.opp_name',
                        DB::raw("(CASE WHEN (auction_number is null) THEN '' ELSE auction_number END) as auction_number"), 
                        DB::raw("(CASE WHEN (submit_price is null) THEN '' ELSE submit_price END) as submit_price"), 
                        DB::raw("(CASE WHEN (win_prob is null) THEN '' ELSE win_prob END) as win_prob"), 
                        DB::raw("(CASE WHEN (project_name is null) THEN '' ELSE project_name END) as project_name"), 
                        DB::raw("(CASE WHEN (submit_date is null) THEN '' ELSE submit_date END) as submit_date"), 
                        DB::raw("(CASE WHEN (quote_number is null) THEN '' ELSE quote_number END) as quote_number"),  
                        DB::raw("(CASE WHEN (assigned_by is null) THEN '' ELSE assigned_by END) as assigned_by"), 
                        DB::raw("(CASE WHEN (quote_number2 is null) THEN '' ELSE quote_number2 END) as quote_number2"), 
                        'sales_lead_register.amount', 'sales_lead_register.id_customer', 'sales_tender_process.status', 'result', 'sales_lead_register.nik',
                        DB::raw("(CASE WHEN (deal_price is null) THEN '' ELSE deal_price END) as deal_price"), 
                        DB::raw("(CASE WHEN (deal_price_total is null) THEN '' ELSE deal_price_total END) as deal_price_total"), 
                        DB::raw("(CASE WHEN (jumlah_tahun is null) THEN '' ELSE jumlah_tahun END) as jumlah_tahun"), 
                        DB::raw("(CASE WHEN (project_class is null) THEN '' ELSE project_class END) as project_class"),'id_tp')
                    ->where('sales_tender_process.lead_id',$request->lead_id)
                    ->first();

        return array("data" => $lead);
    }

    public function getLeadSd(Request $request)
    {
        $lead = solution_design::join('users','users.nik','=','sales_solution_design.nik')
                    ->join('sales_lead_register', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                    ->select('sales_solution_design.lead_id','sales_solution_design.nik', 
                        DB::raw("(CASE WHEN (assessment is null) THEN '' ELSE assessment END) as assessment"), 
                        DB::raw("(CASE WHEN (pov is null) THEN '' ELSE pov END) as pov"), 
                        DB::raw("(CASE WHEN (pd is null) THEN '' ELSE pd END) as pd"), 
                        DB::raw("(CASE WHEN (pb is null) THEN '' ELSE pb END) as pb"), 
                        DB::raw("(CASE WHEN (priority is null) THEN '' ELSE priority END) as priority"), 
                        DB::raw("(CASE WHEN (project_size is null) THEN '' ELSE project_size END) as project_size"), 'users.name', 
                        DB::raw("(CASE WHEN (sales_solution_design.status is null) THEN '' ELSE sales_solution_design.status END) as status"), 
                        DB::raw("(CASE WHEN (assessment_date is null) THEN '-' ELSE assessment_date END) as assessment_date"),
                        DB::raw("(CASE WHEN (pd_date is null) THEN '-' ELSE pd_date END) as pd_date"),
                        DB::raw("(CASE WHEN (pov_date is null) THEN '-' ELSE pov_date END) as pov_date"), 'sales_lead_register.amount', 'sales_lead_register.deal_price', 'checked','sales_lead_register.result')
                    ->where('sales_solution_design.lead_id',$request->lead_id)
                    ->first();

        return array("data" => $lead);
    }

    public function getSearchDataLead(Request $request)
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        $cek_role = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role')->where('user_id',$nik)->first();

        $getPresales = DB::table('sales_solution_design')->join('users', 'users.nik', '=','sales_solution_design.nik')->selectRaw('GROUP_CONCAT(`users`.`name`) AS `name_presales`, GROUP_CONCAT(`sales_solution_design`.`nik`) AS `nik_presales`')->selectRaw('lead_id')->groupBy('lead_id');

        $getListProductLead = DB::table('tb_product_tag')->join('tb_product_tag_relation', 'tb_product_tag_relation.id_product_tag', '=', 'tb_product_tag.id')
                        ->select('lead_id', DB::raw('GROUP_CONCAT(`tb_product_tag`.`id`) as `id_product_tag`'))
                        ->groupBy('lead_id');

        // return $getListProductLead->get();

        $getListTechTag = DB::table('tb_technology_tag')->join('tb_product_tag_relation', 'tb_product_tag_relation.id_technology_tag', '=', 'tb_technology_tag.id')
                        ->select('lead_id', DB::raw('GROUP_CONCAT(`tb_technology_tag`.`id`) AS `id_tech_tag`'))
                        ->groupBy('lead_id');

        $leads = DB::table('sales_lead_register')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->leftJoinSub($getPresales, 'tb_presales',function($join){
                    $join->on("sales_lead_register.lead_id", '=', 'tb_presales.lead_id');
                })
                ->leftJoinSub($getListProductLead, 'product_lead', function($join){
                    $join->on('sales_lead_register.lead_id', '=', 'product_lead.lead_id');
                })
                ->leftJoinSub($getListTechTag, 'tech_tag', function($join){
                    $join->on('sales_lead_register.lead_id', '=', 'tech_tag.lead_id');
                })
                ->join('users as u_sales', 'u_sales.nik', '=', 'sales_lead_register.nik')
                ->leftJoin('tb_pid', 'tb_pid.lead_id', '=', 'sales_lead_register.lead_id')
                ->select(
                    'sales_lead_register.lead_id', 
                    'sales_lead_register.opp_name',
                    'tb_contact.brand_name', 
                    'sales_lead_register.created_at', 
                    'sales_lead_register.amount', 
                    'u_sales.name as name',
                    'sales_lead_register.nik',
                    'sales_lead_register.keterangan',
                    'sales_lead_register.year', 
                    'sales_lead_register.closing_date', 
                    'sales_lead_register.deal_price',
                    'u_sales.id_territory', 
                    'tb_pid.status',
                    'tb_presales.name_presales', 
                    DB::raw("(CASE WHEN (result = 'OPEN') THEN 'INITIAL' WHEN (result = '') THEN 'OPEN' WHEN (result = 'SD') THEN 'SD' WHEN (result = 'TP') THEN 'TP' WHEN (result = 'WIN') THEN 'WIN' WHEN( result = 'LOSE') THEN 'LOSE' WHEN( result = 'HOLD') THEN 'HOLD' WHEN( result = 'SPECIAL') THEN 'SPECIAL' WHEN(result = 'CANCEL') THEN 'CANCEL' END) as result_modif", 'id_product_tag', 'id_tech')
                    // DB::raw("`product_lead`.`id_product_tag` AS `id_product_tag_concat`"),
                    // DB::raw("`tech_tag`.`id_tech_tag` AS `id_tech_tag_concat`")
                )
                ->orderByRaw('FIELD(result, "OPEN", "", "SD", "TP", "WIN", "LOSE", "CANCEL", "HOLD")')
                ->where('result', '!=', 'hmm')
                ->orderBy('created_at', 'desc');

        $searchFields = ['sales_lead_register.lead_id', 'opp_name', 'brand_name', 'name', 'id_territory', 'deal_price', 'amount', 'name_presales'];

        if($ter != null || $cek_role->name_role != 'Operations Director'){
            $leads->where('u_sales.id_company','1');
            if ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
                $leads = $leads->where('nik_presales', $nik);
            } else if ($div == 'SALES' && $pos == 'MANAGER') {
                $leads = $leads->where('u_sales.id_territory', $ter);
            } else if ($div == 'SALES' && $pos == 'STAFF') {
                $leads = $leads->where('u_sales.nik', $nik);
            }       
        } 

        if ($div == 'SALES') {
            $leads->where('u_sales.id_territory', $ter);
        }

        if(!in_array(null,$request->year)){
            if($request->search != ""){
                $leads->where(function($leads) use($request, $searchFields){
                    $searchWildCard = '%'. $request->search . '%';
                    foreach ($searchFields as $data) {
                        $leads->orWhere($data, 'LIKE', $searchWildCard);
                    }
                });
            }
        }

        if(!in_array(null,$request->year)){
            $leads->whereIn('year',$request->year);
        }

        if(!in_array(null,$request->territory)){
            $leads->whereIn('u_sales.id_territory',$request->territory);
        }

        if(!in_array(null,$request->company)){
            $leads->whereIn('u_sales.id_company',$request->company);
        }

        if(!in_array(null,$request->result)){
            if(in_array("null", $request->result)){
                $leads->whereIn('sales_lead_register.result',array_merge($request->result,['']));
            } else {
                $leads->whereIn('sales_lead_register.result',$request->result);
            }
        }

        if (!in_array(null,$request->sales_name)) {
            $leads->whereIn('u_sales.nik',$request->sales_name);
            
        }

        if (!in_array(null,$request->presales_name)) {
            $leads->whereIn('nik_presales',$request->presales_name);   
        }

        if (!in_array(null,$request->product_tag)) {
            foreach ($request->product_tag as $key => $value) {
                $leads->selectRaw("FIND_IN_SET('" . $value . "', `product_lead`.`id_product_tag`) AS `found_product" . $key . "`");
                $leads->havingRaw("`found_product" . $key . "` <> 0");
            }
        }

        if (!in_array(null,$request->tech_tag)) {
            foreach ($request->tech_tag as $key => $value) {
                $leads->selectRaw("FIND_IN_SET('" . $value . "', `tech_tag`.`id_tech_tag`) AS `found_tech" . $key . "`");
                $leads->havingRaw("`found_tech" . $key . "` <> 0");
            }
        }

        if (!in_array(null,$request->customer)) {
            $leads->whereIn('tb_contact.id_customer',$request->customer);
        }

        // return $leads->get();

        return array("data" => $leads->get());

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

    public function changelogTp(Request $request)
    {
        $tambah = new SalesChangeLog();
        $tambah->lead_id = $request->lead_id;
        $tambah->nik = Auth::User()->nik;
        $tambah->status = 'Update TP '. "(" .$request->status.")";
        if ($request['deal_price'] != '') {
            $tambah->deal_price = str_replace('.', '', $request['deal_price']); 
        } else {
            $tambah->deal_price = $request['deal_price'];
        }
        $tambah->submit_price = str_replace('.', '', $request['submit_price']);
        $tambah->save();
    }

    public function update_tp(Request $request)
    {
        $compare_win_tp = TenderProcess::select('status')->where('lead_id', $request->lead_id)->first();

        $update = TenderProcess::where('lead_id', $request->lead_id)->first();
        $update->status = 'ready';
        $update->submit_price = str_replace('.', '', $request['submit_price']);
        $update->auction_number = $request['lelang'];
        $update->win_prob = $request['win_prob'];
        $update->project_name = $request['project_name'];
        $edate = strtotime($_POST['submit_date']); 
        $edate = date("Y-m-d",$edate);
        $update->submit_date = $edate;
        $update->quote_number2 = $request['quote_number'];
        $update->update();
 
        $compare_win_lead = Sales::select('result')->where('lead_id', $request->lead_id)->first();
        $update_lead = Sales::where('lead_id', $request->lead_id)->first();

        if($compare_win_lead->result != 'WIN') {
            $update_lead->result = 'TP';
        }

        if ($request['deal_price'] == '') {
           $update_lead->deal_price = $request['deal_price'];
        }else{
           $update_lead->deal_price = str_replace('.', '', $request['deal_price']); 
        }

        if ($request['deal_price'] == '') {
           $update_lead->amount = $request['amount_cek_tp'];
        }else{
           $update_lead->amount = str_replace('.', '', $request['deal_price']); 
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
                        $update_lead->deal_price_total = str_replace('.', '', $request['deal_price_total']); 
                    }
                } else {
                    $update_lead->jumlah_tahun = NULL;
                    $update_lead->deal_price_total = NULL;
                }
        }
        $update_lead->update();

        return redirect()->back();
    }

    public function checkProductTech(Request $request)
    {
        // foreach ($request->lead_id as $value) {
            $update = solution_design::where('lead_id', $request->lead_id)->first();
            $update->checked = 'checked';
            $update->update();
        // }
    }

    public function update_sd(Request $request)
    {
        // return $request->id_sbe;

        $id = explode(',', $request->id);

        $name_tagging = ProductTagRelation::join('tb_product_tag', 'tb_product_tag_relation.id_product_tag', '=', 'tb_product_tag.id')
                    ->join('tb_technology_tag', 'tb_product_tag_relation.id_technology_tag', '=', 'tb_technology_tag.id')
                    ->select('name_product', 'name_tech', 'price', 'tb_product_tag_relation.id')
                    ->whereIn('tb_product_tag_relation.id', $id)->get();

        if(isset($name_tagging)){
            foreach($name_tagging as $data){
                $add_changelog = new SalesChangeLog();
                $add_changelog->lead_id = $request->lead_id;
                $add_changelog->nik = Auth::User()->nik;
                $add_changelog->status = 'Delete Tagging Product ' .  $data['name_product'] . ', Technology ' .  $data['name_tech'] . ', with Price ' . str_replace('.', '', $data['price']);
                $add_changelog->save();
            }    
        }

        if (isset($name_tagging)) {
            foreach ($name_tagging as $key => $value) {
                ProductTagRelation::where('id',$value->id)->delete(); 
            }
        } 

        if(!isset($request->id)){
            if(isset($request->tagData["tagProduct"])){
                foreach ($request->tagData["tagProduct"] as $key => $value) {
                    $store = new ProductTagRelation;
                    $store->lead_id = $request->lead_id;
                    $store->id_product_tag = $value['tag_product']['productTag'];
                    $store->id_technology_tag = $value['tag_product']['techTag'];
                    $store->price = $value['tag_price'];
                    $store->save(); 

                    $add_changelog = new SalesChangeLog();
                    $add_changelog->lead_id = $request->lead_id;
                    $add_changelog->nik = Auth::User()->nik;
                    $add_changelog->status = 'Add Tagging Product ' .  $value['tag_product']['productTagText'] . ', Technology ' .  $value['tag_product']['techTagText'] . ', with Price ' . str_replace('.', '', $value['tag_price']);
                    $add_changelog->save();
                }
            }
        }

        if (isset($request->tagData["tagSBE"])) {
            foreach ($request->tagData["tagSBE"] as $key => $value) {
                $store = new SbeRelation;
                $store->lead_id = $request->lead_id;
                $store->tag_sbe = $value['tag_sbe'];
                $store->price_sbe = $value['price_sbe'];
                $store->save();

                $add_changelog = new SalesChangeLog();
                $add_changelog->lead_id = $request->lead_id;
                $add_changelog->nik = Auth::User()->nik;
                $add_changelog->status = 'Add Tagging SBE ' .  $value['sbeText'] . ', with Price ' . str_replace('.', '', $value['price_sbe']);
                $add_changelog->save();
            }
        }

        if(isset($request->id_sbe_delete)){
            $id_sbe = explode(',', $request->id_sbe_delete);
            $name = explode(',', $request->name_sbe_delete);
            $price = explode(',', $request->price_sbe_delete);

            for($i=0; $i < count($id_sbe) ; $i++) {
                $add_changelog_sbe = new SalesChangeLog();
                $add_changelog_sbe->lead_id = $request->lead_id;
                $add_changelog_sbe->nik = Auth::User()->nik;
                $add_changelog_sbe->status = 'Delete Tagging SBE ' .  $name[$i] . ', with Price ' . str_replace('.', '', $price[$i]);
                $add_changelog_sbe->save();
            }
        }

        $id_sbe = explode(',', $request->id_sbe_delete);

        $get_id = SbeRelation::whereIn('id', $id_sbe)->get();

        if (isset($get_id)){
            foreach ($get_id as $value) {
                SbeRelation::where('id',$value->id)->delete(); 
            }
        }

        $update = solution_design::where('lead_id', $request->lead_id)->first();
        $update->assessment = $request['assessment'];
        if ($request['assessment_date'] != '') {
            $update->assessment_date = $request['assessment_date'];
        }
        $update->pd = $request['propossed_design'];
        if ($request['pd_date'] != '') {
            $update->pd_date = $request['pd_date'];
        }
        $update->pov = $request['pov'];
        if ($request['pov_date'] != '') {
            $update->pov_date = $request['pov_date'];
        }
        $update->pb = str_replace('.', '',$request['project_budget']);
        $update->priority = $request['priority'];
        $update->project_size = $request['proyek_size'];
        $update->update();

        $update = Sales::where('lead_id', $request->lead_id)->first();
        $update->result = 'SD';
        $update->update();        

        return redirect()->back();
    }

    public function updateProductTag(Request $request)
    {
        $update = ProductTagRelation::where('id', $request->id_exist)->first();
        $update->lead_id = $request->lead_id;
        $update->id_product_tag = $request->id_product;
        $update->id_technology_tag = $request->id_techno;
        $update->price = str_replace('.', '', $request->price);
        $update->update(); 

        $get_name = ProductTagRelation::joinSub(DB::table('tb_product_tag'), 'tb_product_tag_alias', function ($join) {
                    $join->on('tb_product_tag_alias.id', '=', 'tb_product_tag_relation.id_product_tag');
                })
                ->joinSub(DB::table('tb_technology_tag'), 'tb_technology_tag_alias', function ($join) {
                    $join->on('tb_technology_tag_alias.id', '=', 'tb_product_tag_relation.id_technology_tag');
                })
                ->select('name_tech','name_product','id_technology_tag','id_product_tag','price','tb_product_tag_relation.id')
                ->where('tb_product_tag_relation.id',$request->id_exist)
                ->first();

        $add_changelog = new SalesChangeLog();
        $add_changelog->lead_id = $request->lead_id;
        $add_changelog->nik = Auth::User()->nik;
        $add_changelog->status = 'Update Tagging Product ' .  $get_name->name_product . ', Technology ' .  $get_name->name_tech . ', with Price ' . str_replace('.', '', $request->price);
        $add_changelog->save();

        return redirect()->back();
    }

    public function updateSbeTag(Request $request)
    {
        $update_sbe = SbeRelation::where('id', $request->id_exist)->first();
        $update_sbe->lead_id = $request->lead_id;
        $update_sbe->tag_sbe = $request->id_sbe;
        $update_sbe->price_sbe = str_replace('.', '', $request->price);
        $update_sbe->update();

        $add_changelog_sbe = new SalesChangeLog();
        $add_changelog_sbe->lead_id = $request->lead_id;
        $add_changelog_sbe->nik = Auth::User()->nik;
        $add_changelog_sbe->status = 'Update Tagging SBE ' .  $request->name_sbe . ', with Price ' . str_replace('.', '', $request->price);
        $add_changelog_sbe->save();
    }

    public function showTagging(Request $request)
    {
        return ProductTagRelation::joinSub(DB::table('tb_product_tag'), 'tb_product_tag_alias', function ($join) {
                    $join->on('tb_product_tag_alias.id', '=', 'tb_product_tag_relation.id_product_tag');
                })
                ->LeftjoinSub(DB::table('tb_technology_tag'), 'tb_technology_tag_alias', function ($join) {
                    $join->on('tb_technology_tag_alias.id', '=', 'tb_product_tag_relation.id_technology_tag');
                })
                ->select('name_tech','name_product','id_technology_tag','id_product_tag','price','tb_product_tag_relation.id')
                ->where('lead_id',$request->lead_id)
                ->get();
    }

    public function showSbe(Request $request)
    {
        return SbeRelation::select('id', 'lead_id', 'price_sbe', 'tag_sbe')->where('lead_id', $request->lead_id)->orderBy('created_at','desc')->get();
    }

    public function changelog_sd(Request $request)
    {
        $tambah = new SalesChangeLog();
        $tambah->lead_id = $request->lead_id;
        $tambah->nik = Auth::User()->nik;
        $tambah->status = 'Update SD '. "(" .$request->status.")";
        $tambah->save();
    }

    public function getDetailLeadResult(Request $request)
    {
        $getLead = Sales::select('lead_id', 'opp_name', 'id_customer')->where('lead_id', $request->lead_id)->get();

        return array("data"=>$getLead);
    }

    public function getQuoteNumber(Request $request)
    {
        $get_quote_number = Quote::join('tb_contact', 'tb_contact.id_customer', '=', 'tb_quote.id_customer')
                    ->select(
                        DB::raw("CONCAT(`quote_number`, ' - ', `customer_legal_name`) AS `text`"), 
                        DB::raw('`id_quote` AS `id`')
                    )
                    ->where('tb_quote.status', null)
                    ->where('tb_quote.id_customer', $request->id_customer)
                    ->orderBy('tb_quote.created_at', 'desc')
                    ->get();

        return array("data"=>$get_quote_number);
    }

    public function addContribute(Request $request)
    {      
        foreach ($request->nik_cont as $value) {
            $tambah = new solution_design();
            $tambah->lead_id = $request['lead_cont'];
            $tambah->nik     = $value;
            $tambah->status  = 'cont';
            $tambah->save();
        }

        $tambah_log = new SalesChangeLog();
        $tambah_log->lead_id = $request['lead_cont'];
        $tambah_log->nik = Auth::User()->nik;
        $tambah_log->status = 'Add new contribute '. "(" .$request->concat_name.")";
        $tambah_log->save();

        $kirim = User::select('email')->where('nik', $request->nik_cont)->first();

        $data = DB::table('sales_lead_register')
                    ->join('sales_solution_design','sales_solution_design.lead_id','sales_lead_register.lead_id')
                    ->join('users as sales', 'sales.nik', '=', 'sales_lead_register.nik')
                    ->join('users as presales','presales.nik','=','sales_solution_design.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'sales_lead_register.opp_name','sales_lead_register.amount', 'sales.name as sales_name','presales.name as presales_name')
                    ->where('sales_solution_design.status', 'cont')
                    ->where('sales_lead_register.lead_id',$tambah->lead_id)
                    ->first();

        $status = 'contribute';
        Mail::to($kirim)->send(new AddContribute($data,$status));

        return redirect()->back();
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

    public function updateResult(Request $request)
    {
        // return $request->tagData['arr_sbe'];

        $update = Sales::where('lead_id', $request->lead_id_result)->first();
        $update->result = $request['result'];
        $update->keterangan = $request['keterangan'];
        $update->closing_date = date("Y-m-d");
        $update->result4    = $request['project_type'];
        $update->update();

        if($request['result'] != 'HOLD' || $request['result'] != 'SPECIAL'){
            $update = TenderProcess::where('lead_id', $request->lead_id_result)->first();
            $update->status = 'closed';
            $update->update();
        }

        $tambah = new SalesChangeLog();
        $tambah->lead_id = $request['lead_id_result'];
        $tambah->nik = Auth::User()->nik;

        $data = Sales::join('users','sales_lead_register.nik','=','users.nik')->where('lead_id',$request->lead_id_result)->first();

        if($request['result'] == 'WIN'){

            if (isset($request->tagData['id'])) {
                $id = $request->tagData['id'];

                $name_tagging = ProductTagRelation::join('tb_product_tag', 'tb_product_tag_relation.id_product_tag', '=', 'tb_product_tag.id')
                            ->join('tb_technology_tag', 'tb_product_tag_relation.id_technology_tag', '=', 'tb_technology_tag.id')
                            ->select('name_product', 'name_tech', 'price', 'tb_product_tag_relation.id')
                            ->whereIn('tb_product_tag_relation.id', $id)->get();

                if(isset($name_tagging)){
                    foreach($name_tagging as $data){
                        $add_changelog = new SalesChangeLog();
                        $add_changelog->lead_id = $request->lead_id_result;
                        $add_changelog->nik = Auth::User()->nik;
                        $add_changelog->status = 'Delete Tagging Product ' .  $data['name_product'] . ', Technology ' .  $data['name_tech'] . ', with Price ' . str_replace('.', '', $data['price']);
                        $add_changelog->save();
                    }    
                }

                if (isset($name_tagging)) {
                    foreach ($name_tagging as $key => $value) {
                        ProductTagRelation::where('id',$value->id)->delete(); 
                    }
                }   
            } else {
                if(isset($request->tagData)){
                    if(!empty($request->tagData["tagProduct"])){
                        foreach ($request->tagData["tagProduct"] as $key => $value) {
                            $store = new ProductTagRelation;
                            $store->lead_id = $request->lead_id_result;
                            $store->id_product_tag = $value['tag_product']['productTag'];
                            $store->id_technology_tag = $value['tag_product']['techTag'];
                            $store->price = $value['tag_price'];
                            $store->save(); 

                            $add_changelog = new SalesChangeLog();
                            $add_changelog->lead_id = $request->lead_id_result;
                            $add_changelog->nik = Auth::User()->nik;
                            $add_changelog->status = 'Add Tagging Product ' .  $value['tag_product']['productTagText'] . ', Technology ' .  $value['tag_product']['techTagText'] . ', with Price ' . str_replace('.', '', $value['tag_price']);
                            $add_changelog->save();
                        }
                    }

                    if(!empty($request->tagData["tagService"])){
                        foreach ($request->tagData["tagService"] as $key => $value) {
                            $store = new ServiceTagRelation;
                            $store->lead_id = $request->lead_id_result;
                            $store->id_service_tag = $value['tag_service'];
                            $store->price = $value['tag_price'];
                            $store->save(); 
                        }
                    }
                }
            }

            if (isset($request->tagData["tagSbe"])) {
                foreach ($request->tagData["tagSbe"] as $key => $value) {
                    $store = new SbeRelation;
                    $store->lead_id = $request->lead_id_result;
                    $store->tag_sbe = $value['tag_sbe_id'];
                    $store->price_sbe = str_replace('.', '', $value['tag_price']);
                    $store->save();

                    $add_changelog = new SalesChangeLog();
                    $add_changelog->lead_id = $request->lead_id_result;
                    $add_changelog->nik = Auth::User()->nik;
                    $add_changelog->status = 'Add Tagging SBE ' .  $value['tag_sbe_text'] . ', with Price ' . str_replace('.', '', $value['tag_price']);
                    $add_changelog->save();
                }
            }

            if(isset($request->tagData['arr_sbe'])){  

                foreach($request->tagData['arr_sbe'] as $key => $value){
                    $add_changelog = new SalesChangeLog();
                    $add_changelog->lead_id = $request->lead_id_result;
                    $add_changelog->nik = Auth::User()->nik;
                    $add_changelog->status = 'Delete Tagging SBE ' .  $value['name'] . ', with Price ' . str_replace('.', '', $value['price']);
                    $add_changelog->save();

                    $delete = SbeRelation::where('id',$value['id'])->delete();
                }

            }

            $tambah->status = 'Update WIN';

            if ($request['quote_number_final'] != 'Choose Quote') {
                $amount_quo = Quote::where('quote_number', $request['quote_number_final'])->first()->amount;
            }

            $tambahpid = new PID();
            $tambahpid->lead_id     = $request['lead_id_result'];
            $tambahpid->no_po       = $request['no_po'];
            if ($request['amount_pid'] != NULL) {
                $tambahpid->amount_pid  = str_replace('.', '',$request['amount_pid']);
            }else{
                $tambahpid->amount_pid  = $amount_quo;
            }
            
            if ($request['date_po'] != NULL) {
                $edate                  = strtotime($_POST['date_po']); 
                $edate                  = date("Y-m-d",$edate);
                $tambahpid->date_po     = $edate;
            }  
            if ($request['request_id'] == "true") {
                $tambahpid->status = 'requested';

            }else{
                $tambahpid->status = 'pending';
            }

            $tambahpid->save();

            $update_quo = TenderProcess::where('lead_id', $request->lead_id_result)->first();
            $update_quo->quote_number_final = $request['quote_number_final'];
            $update_quo->update();

            if ($request['quote_number_final'] != 'Choose Quote') {
                $update_status_quo = Quote::where('quote_number', $request['quote_number_final'])->first();
                $update_status_quo->status = 'choosed';
                $update_status_quo->update();
            }
            

            $cekstatus = PID::select('status')->where('lead_id', $tambahpid->lead_id)->first();

            if ($cekstatus->status == 'requested') {
                $pid_info = DB::table('sales_lead_register')
                    ->join('sales_tender_process','sales_tender_process.lead_id','=','sales_lead_register.lead_id')
                    ->join('tb_quote', 'tb_quote.id_quote', '=', 'sales_tender_process.quote_number_final','left')
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
                        'tb_quote.quote_number',
                        'quote_number_final'
                    )->first();

                if($pid_info->lead_id == "MSPQUO"){
                    $pid_info->url_create = "/salesproject";
                }else {
                    $pid_info->url_create = "/salesproject#acceptProjectID?" . $pid_info->id_pid;
                }

                $users = User::select('name', 'email')->where('id_division','FINANCE')->where('id_position','MANAGER')->first();
       
                Mail::to('hellosinergy@gmail.com')->send(new MailResult($users,$pid_info));
                Mail::to($users->email)->send(new MailResult($users,$pid_info));

                $jsonInsert = array(
                    "company"=> $pid_info->id_company,
                    "heximal" => "#246d18",
                    "lead_id" => $request->lead_id_result,
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
                $this->getNotifBadgeCountPID($jsonCount);

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

        $total = TenderProcess::join('sales_lead_register','sales_tender_process.lead_id','=','sales_lead_register.lead_id')
                ->where('sales_lead_register.nik', $data->nik)
                ->where('sales_lead_register.result','TP')
                ->whereYear('sales_tender_process.created_at',date('Y'))
                ->count('sales_tender_process.lead_id');

        $jsonCount = array(
            "to" => $data->email,
            "total"=> $total
        );

        $this->getNotifCountLead($jsonCount);  
        return "success";
    }

    public function getNotifBadgeCountPID($json){
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
    }

    public function getPid(Request $request)
    {
        $getPid = DB::table('sales_lead_register')->join('tb_pid','tb_pid.lead_id','=','sales_lead_register.lead_id')
                ->select('sales_lead_register.lead_id','amount_pid','no_po','date_po','opp_name')
                ->where('sales_lead_register.lead_id', $request->lead_id)
                ->get();

        return array("data"=>$getPid);
    }

    public function updateResultRequestPid(Request $request)
    {
        $update = PID::where('lead_id', $request->lead_id)->first();
        $update->status = 'requested';
        $update->update();

        $pid_info = DB::table('sales_lead_register')
            ->join('sales_tender_process','sales_tender_process.lead_id','=','sales_lead_register.lead_id')
            ->join('tb_quote', 'tb_quote.id_quote', '=', 'sales_tender_process.quote_number_final', 'left')
            ->join('tb_pid','tb_pid.lead_id','=','sales_lead_register.lead_id')
            ->join('users','users.nik','=','sales_lead_register.nik')
            ->where('sales_lead_register.lead_id',$request->lead_id)
            ->select(
                'sales_lead_register.lead_id',
                'sales_lead_register.opp_name',
                'users.name',
                'tb_pid.amount_pid',
                'tb_pid.id_pid',
                'tb_pid.no_po',
                'sales_lead_register.result',
                'users.email',
                'quote_number_final',
                'users.id_company',
                'tb_quote.quote_number'
            )->first();

        $pid_info->url_create = "/salesproject#acceptProjectID?" . $pid_info->id_pid;


        $users = User::select('name','email')->where('id_division','FINANCE')->where('id_position','MANAGER')->first();
        
        Mail::to($users->email)->send(new MailResult($users,$pid_info));

        $total = PID::where('status','requested')->count('id_pid');

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
        $this->getNotifBadgeCountPID($jsonCount);

        return redirect()->to('/project')->with('success', 'Create PID Successfully!');
    }

    public function storeLead(Request $request)
    {
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
        if((Auth::User()->id_division == 'SALES') || (Auth::User()->id_division == 'BCD' && Auth::User()->id_position == 'MANAGER') || Auth::User()->name == "Operations Team"){
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
           $tambah->amount = str_replace('.', '', $request['amount']);
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

        // if ($request->product != "") {
        //     $productTag = $request->product;
        //     $count = count($productTag);
        //     $techTag = $request->technology;
        //     if ($count == '1') {
        //         foreach ($productTag as $product) {
        //             foreach ($techTag as $data) {
        //                 $productRelation = new ProductTagRelation();
        //                 $productRelation->lead_id = $lead_id;
        //                 $productRelation->id_product_tag = $product;
        //                 $productRelation->id_technology_tag = $data;
        //                 $productRelation->price = '0';
        //                 $productRelation->save();
        //             }
        //         }
        //     } 
        //     else {
        //         foreach ($productTag as $product) {
        //             $productRelation = new ProductTagRelation();
        //             $productRelation->lead_id = $lead_id;
        //             $productRelation->id_product_tag = $product;
        //             $productRelation->price = '0';
        //             $productRelation->save();
        //         } 
        //         $get_id = ProductTagRelation::select('id')->orderBy('updated_at', 'desc')->take($count)->get();
        //         foreach ($techTag as $data) {
        //             $update = ProductTagRelation::whereIn('id', $get_id)
        //                     ->update([
        //                         'id_technology_tag' => $data,
        //                     ]);
        //         }
        //     }
        // }

       

        // if ($request->product != "") {
        //     $productTag = $request->product;
        //     foreach ($productTag as $data) {
        //         $productRelation = new ProductTagRelation();
        //         $productRelation->lead_id = $lead;
        //         $productRelation->id_product_tag = $data;
        //         $productRelation->save();
        //     }
        // }
        
        // if ($request->technology != "") {
        //     $techTag = $request->technology;
        //     foreach ($techTag as $data) {
        //         $productRelation = new TechnologyTagRelation();
        //         $productRelation->lead_id = $lead;
        //         $productRelation->id_tech_tag = $data;
        //         $productRelation->save();
        //     }
        // }
        

        $lead_change_log = $name->code . date('y') . date('m') . $nomor;
        $amount = str_replace('.', '', $request['amount']);
        $tambah_log = new SalesChangeLog();
        $tambah_log->lead_id = $lead_change_log;
        if(Auth::User()->id_division == 'SALES' || Auth::User()->id_division == 'BCD' && Auth::User()->id_position == 'MANAGER'){
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
        } elseif(Auth::User()->id_division == 'SALES' || Auth::User()->id_division == 'BCD' && Auth::User()->id_position == 'MANAGER'){
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
        $user_to = User::select('email')
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
        $this->getNotifBadgeInsert($jsonInsert);
        
        

        if (Auth::User()->id_division === 'TECHNICAL PRESALES' && Auth::User()->id_position === 'STAFF') {
            return redirect('project')->with('success', 'Wait for Presales Manager Assign Lead Register!');
        }else{
            return redirect('project')->with('success', 'Create Lead Register Successfully!');
        }
    }

    public function assignPresales(Request $request)
    {
        $tambah = new solution_design();
        $tambah->lead_id = $request['lead_id'];
        $tambah->nik = $request['nik_presales'];
        $tambah->save();

        $tambahtp = new TenderProcess();
        $tambahtp->lead_id = $request['lead_id'];
        $tambahtp->save();

        $update = Sales::where('lead_id', $request['lead_id'])->first();
        $update->result = '';
        $update->update();

        $tambah = new SalesChangeLog();
        $tambah->lead_id = $request['lead_id'];
        $tambah->nik = Auth::User()->nik;
        $tambah->status = 'Assign Presales to '. $request->name_presales;
        $tambah->save();

        $kirim = User::select('email')->where('nik', $request['nik_presales'])->first();

        $data = DB::table('sales_lead_register')
                    ->join('sales_solution_design','sales_solution_design.lead_id','sales_lead_register.lead_id')
                    ->join('users as sales', 'sales.nik', '=', 'sales_lead_register.nik')
                    ->join('users as presales','presales.nik','=','sales_solution_design.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'sales_lead_register.opp_name','sales_lead_register.amount', 'sales.name as sales_name','presales.name as presales_name')
                    ->where('sales_lead_register.lead_id',$tambah->lead_id)
                    ->first();

        $status = 'assign';
        Mail::to($kirim)->send(new AssignPresales($data,$status));

        $user_to = User::select('email','nik')
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
                ->where('sales_sd_filtered.nik','=',$request->nik_presales);

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

        $this->getNotifBadgeInsert($jsonInsert);

        return redirect('project');
    }

    public function reassignPresales(Request $request)
    {
        $update = solution_design::where('lead_id', $request['lead_id'])->first();
        $update->nik = $request['nik_presales'];
        $update->update();

        $tambah = new SalesChangeLog();
        $tambah->lead_id    = $request['lead_id'];
        $tambah->nik        = Auth::User()->nik;
        $tambah->status     = 'Re-Assign Presales to '. $request->name_presales;
        $tambah->save();

        $kirim = User::select('email')->where('nik', $request['nik_presales'])->first();

        $data = DB::table('sales_lead_register')
                    ->join('sales_solution_design','sales_solution_design.lead_id','sales_lead_register.lead_id')
                    ->join('users as sales', 'sales.nik', '=', 'sales_lead_register.nik')
                    ->join('users as presales','presales.nik','=','sales_solution_design.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'sales_lead_register.opp_name','sales_lead_register.amount', 'sales.name as sales_name','presales.name as presales_name')
                    ->where('sales_lead_register.lead_id',$request->lead_id)
                    ->first();
        $status = 'reAssign';
        Mail::to($kirim)->send(new AssignPresales($data,$status));

        return redirect('project');
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
        $total_sales = TenderProcess::join('sales_lead_register','sales_tender_process.lead_id','=','sales_lead_register.lead_id')
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

        $this->getNotifBadgeInsert($jsonInsert);


        return redirect()->back();
    }

    public function destroy(Request $request)
    {
        $hapus = Sales::find($request->lead_id);
        $hapus->delete();

        return redirect()->back();
    }

}