<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use App\PR;
use App\SalesProject;
use App\User;
use Illuminate\Support\Facades\Route;
// use Excel;
use Validator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PrController extends Controller
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

        $pops = PR::select('no_pr')->orderBy('created_at','desc')->first();

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

        $tahun = date("Y");

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
                            ->where('status', 'ADMIN')
                            ->get();
        }

        $sidebar_collapse = true;

        $year_before = PR::select(DB::raw('YEAR(created_at) year'))->orderBy('year','desc')->groupBy('year')->get();

        $pid = SalesProject::select('id_project')->get();

        $user = User::select('name', 'nik')->where('id_company', '1')->where('status_karyawan', '!=', 'dummy')->orderBy('name','asc')->get();

        return view('admin/pr', compact('notif','notifOpen','notifsd','notiftp' ,'pops', 'sidebar_collapse','year_before','tahun','pid', 'notifClaim', 'user'))->with(['initView'=> $this->initMenuBase()]);
    }

    public function getCountPr(Request $request)
    {
        $total_pr = PR::select('no_pr', 'amount')->whereYear('date', $request->year);

        $count_all = $total_pr->count('no_pr');
        $count_ipr = $total_pr->where('type_of_letter', 'IPR')->count('no_pr');
        $count_epr = PR::select('no_pr', 'amount')->whereYear('date', $request->year)->where('type_of_letter', 'EPR')->count('no_pr');
        $amount_all = PR::select('no_pr', 'amount')->whereYear('date', $request->year)->sum('amount');
        $amount_ipr = $total_pr->where('type_of_letter', 'IPR')->sum('amount');
        $amount_epr = PR::select('no_pr', 'amount')->whereYear('date', $request->year)->where('type_of_letter', 'EPR')->sum('amount');

        return collect([
            'all'=>[$count_all,strpos((string)$amount_all,".",0) ? (string)$amount_all : (string)$amount_all . ".00"],
            'ipr'=>[$count_ipr,strpos((string)$amount_ipr,".",0) ? (string)$amount_ipr : (string)$amount_ipr . ".00"],
            'epr'=>[$count_epr,strpos((string)$amount_epr,".",0) ? (string)$amount_epr : (string)$amount_epr . ".00"]
        ]);
    }

    
    public function create()
    {
        //
    }

    public function store_pr(Request $request)
    {
        $tahun = date("Y");
        $cek = DB::table('tb_pr')
                ->where('date','like',$tahun."%")
                ->count('no');

        $type = $request['type'];
        $posti = $request['position'];

        $edate = strtotime($_POST['date']); 
        $edate = date("Y-m-d",$edate);

        $month_pr = substr($edate,5,2);
        $year_pr = substr($edate,0,4);

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
        $bln = $array_bln[$month_pr];

        $getnumber = PR::orderBy('no', 'desc')->where('date','like',$tahun."%")->count();

        if($getnumber == NULL){
            $getlastnumber = 1;
            $lastnumber = $getlastnumber;
        } else{
            $lastnumber = $getnumber+1;
        }

        if($lastnumber < 10){
           $akhirnomor = '000' . $lastnumber;
        }elseif($lastnumber > 9 && $lastnumber < 100){
           $akhirnomor = '00' . $lastnumber;
        }elseif($lastnumber >= 100){
           $akhirnomor = '0' . $lastnumber;
        }

        $no = $akhirnomor.'/'.$posti .'/'. $type.'/' . $bln .'/'. $year_pr;
        $nom = PR::select('no')->orderBy('created_at','desc')->first();

        $tambah = new PR();
        $tambah->no = $nom->no+1;
        $tambah->no_pr = $no;
        $tambah->position = $posti;
        $tambah->type_of_letter = $type;
        $tambah->month = $bln;
        $tambah->date = $edate;
        $tambah->to = $request['to'];
        $tambah->attention = $request['attention'];
        $tambah->title = $request['title'];
        $tambah->project = $request['project'];
        $tambah->description = $request['description'];
        $tambah->from = $request['from_user'];
        // $tambah->division = $request['division'];
        $tambah->division = 'PMO';
        $tambah->issuance = Auth::User()->nik;
        $tambah->amount = str_replace(',', '', $request['amount']);
        if ($request['project_id'] == null) {
            $tambah->project_id = $request['project_idInputNew'];
        }else{
            $tambah->project_id = $request['project_id'];
        }
        $tambah->category = $request['category'];
        $tambah->result = 'T';
        $tambah->status = 'On Progress';
        $tambah->save();

        return redirect('pr')->with('success', 'Created Purchase Request Successfully!');       
    }

    public function reportPr()
    {
        $year = date("Y");
        $sidebar_collapse = true;

        return view('admin/report_pr', compact('year', 'sidebar_collapse'))->with(['initView'=> $this->initMenuBase()]);
    }

    public function getTotalPr()
    {
        $year = date('Y');
        $pie = 0;
        $total = PR::orderby('type_of_letter')->whereYear('date',$year)->get();

        $first = $total[0]->type_of_letter;
        $hasil = [0,0];
        $type_pr = ['IPR', 'EPR'];

        foreach ($type_pr as $key => $value2) {
            foreach ($total as $value) {
                    if ($value->type_of_letter == $value2) {
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

    public function getAmountByCategory()
    {
        $year = date('Y');

        $sum_all = PR::selectRaw('SUM(`amount`) as `sum_all`')
            ->whereYear('date', date('Y'))
            ->first();

        $sum_cat = PR::select('category')
            // ->selectRaw('SUM(`amount`) as `sum`')
            ->selectRaw('SUM(`amount`)/' . $sum_all->sum_all . '*100 as `precentage`')
            ->orderBy('precentage','DESC')
            ->whereYear('date', date('Y'))
            ->groupBy('category')->get();

        return array("label"=>$sum_cat->pluck('category'), "precentage"=>$sum_cat->pluck('precentage'));
    }

    public function getTotalPrByMonth()
    {
        $data = PR::select(
                DB::raw('COUNT(IF(`tb_pr`.`type_of_letter` = "IPR",1,NULL)) AS "IPR"'),
                DB::raw('COUNT(IF(`tb_pr`.`type_of_letter` = "EPR",1,NULL)) AS "EPR"'), 'month'
            )
            ->whereYear('date', date('Y'))
            ->groupBy('month');

        return array("data" => $data->get());
    }

    public function getTotalAmountByType()
    {
        $data = PR::select(
                DB::raw('SUM(IF(`tb_pr`.`type_of_letter` = "IPR",amount,"")) AS "amount_IPR"'),
                DB::raw('SUM(IF(`tb_pr`.`type_of_letter` = "EPR",amount,"")) AS "amount_EPR"'), 'month'
            )
            ->whereYear('date', date('Y'))
            ->groupBy('month');

        return array("data" => $data->get());
    }

    public function getTotalNominalByCat()
    {
        $data = PR::select(
                DB::raw('COUNT(no_pr) as total'),
                DB::raw('SUM(amount) as nominal'),
                'category'
            )
            ->whereYear('date', date('Y'))
            ->orderBy('nominal', 'desc')
            ->groupBy('category');

        return array("data" => $data->get());
    }

    public function getTotalNominalByPid()
    {
        $data = PR::select(
                DB::raw('COUNT(no_pr) as total'),
                DB::raw('SUM(amount) as nominal'),
                'project_id'
            )
            ->whereRaw("(`project_id` != 'internal' AND `project_id` != '-')")
            ->whereYear('date', date('Y'))
            ->groupBy('project_id');

        return array("data" => $data->get());
    }

    public function getTotalNominalByCatIpr()
    {
        $data = PR::select(
                DB::raw('COUNT(no_pr) as total'),
                DB::raw('SUM(amount) as nominal'),
                'category'
            )
            ->whereYear('date', date('Y'))
            ->where('type_of_letter', 'IPR')
            ->orderBy('nominal', 'desc')
            ->groupBy('category');

        return array("data" => $data->get());
    }

    public function getTotalNominalByCatEpr()
    {
        $data = PR::select(
                DB::raw('COUNT(no_pr) as total'),
                DB::raw('SUM(amount) as nominal'),
                'category'
            )
            ->whereYear('date', date('Y'))
            ->where('type_of_letter', 'EPR')
            ->orderBy('nominal', 'desc')
            ->groupBy('category');

        return array("data" => $data->get());
    }
    
    public function update_pr(Request $request)
    {
        $no = $request['edit_no_pr'];

        $update = PR::where('no',$no)->first();
        $update->to = $request['edit_to'];
        $update->attention = $request['edit_attention'];
        $update->title = $request['edit_title'];
        $update->description = $request['edit_description'];
        $update->project_id = $request['edit_project_id'];
        $update->note = $request['edit_note'];
        $amount = str_replace(',', '', $request['edit_amount']);
        $update->status = $request['edit_status'];
        $update->amount = $amount;


        $update->update();

        return redirect('pr')->with('update', 'Updated Purchase Request Data Successfully!');
    }

    public function getfilteryear(Request $request)
    {
        $filter_pr = DB::table('tb_pr')
                        ->join('users as user_from', 'user_from.nik', '=', 'tb_pr.from')
                        ->join('users as issuance', 'issuance.nik', '=', 'tb_pr.issuance')
                        ->select('no','no_pr', 'position', 'type_of_letter', 'month', 'date', 'to', 'attention', 'title', 'description', 'division', 'project_id', 'user_from.name as user_from', 'note', 'issuance.name as issuance', 'category', 'status')
                        ->where('result', '!=', 'R')
                        ->whereYear('tb_pr.created_at', $request->data)
                        ->get();

        return array("data" => $filter_pr);
    }

    public function getdatapr(Request $request)
    {
        $tahun = date("Y"); 

        return array("data" => PR::join('users as user_from', 'user_from.nik', '=', 'tb_pr.from')
                                ->join('users as issuance', 'issuance.nik', '=', 'tb_pr.issuance')
                                ->select('no','no_pr', 'position', 'type_of_letter', 'month', 'date', 'to', 'attention', 'title', 'description', 'division', 'project_id', 'user_from.name as user_from', 'note', 'issuance.name as issuance', 'category', 'issuance as issuance_nik', 'amount', 'status')
                                ->where('result', '!=', 'R')
                                ->where('date','like',$tahun."%")
                                ->get());
    }

    public function destroy_pr($no)
    {
        $hapus = PR::find($no);
        $hapus->delete();

        return redirect('pr')->with('alert', 'Deleted!');
    }

    public function PrAdmin()
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

        $datas = DB::table('tb_pr')
                        ->join('users','users.nik','=','tb_pr.from')
                        ->select('tb_pr.no','tb_pr.no_pr', 'tb_pr.position', 'tb_pr.type_of_letter', 'tb_pr.month', 'tb_pr.date', 'tb_pr.to', 'tb_pr.attention', 'tb_pr.title', 'tb_pr.project', 'tb_pr.description', 'tb_pr.from', 'tb_pr.division', 'tb_pr.issuance', 'tb_pr.project_id', 'users.name')
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

        return view('report/pr', compact('notif','notifOpen','notifsd','notiftp','id_pro', 'datas', 'notifClaim'));
    }

    public function downloadExcelPr(Request $request) {

        $spreadsheet = new Spreadsheet();

        $prSheet = new Worksheet($spreadsheet,'Purchase Request');
        $spreadsheet->addSheet($prSheet);
        $spreadsheet->removeSheetByIndex(0);
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->mergeCells('A1:O1');
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

        $sheet->getStyle('A1:O1')->applyFromArray($titleStyle);
        $sheet->setCellValue('A1','Purchase Request');

        $headerStyle = $normalStyle;
        $headerStyle['font']['bold'] = true;
        $sheet->getStyle('A2:O2')->applyFromArray($headerStyle);;

        $headerContent = ["No", "NO PR", "POSITION", "TYPE OF LETTER", "MONTH",  "DATE", "TO" , "ATTENTION", "TITLE", "PROJECT", "DESCRIPTION", "FROM", "ISSUANCE" ,"ID PROJECT", "AMOUNT"];
        $sheet->fromArray($headerContent,NULL,'A2');

        $dataPR = PR::join('users as user_from', 'user_from.nik', '=', 'tb_pr.from')
            ->Leftjoin('users as issuance', 'issuance.nik', '=', 'tb_pr.issuance')
            ->select('no_pr','position','type_of_letter', 'month', 'date', 'to', 'attention', 'title','project','description','user_from.name as user_from','issuance.name as issuance','project_id','amount', 'status')
            ->whereYear('tb_pr.date', $request->year)
            ->get();

        foreach ($dataPR as $key => $data) {
            $data->amount = number_format($data->amount,2,",",".");
            $sheet->fromArray(array_merge([$key + 1],array_values($data->toArray())),NULL,'A' . ($key + 3));
        }

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setWidth(35);
        $sheet->getColumnDimension('H')->setWidth(25);
        $sheet->getColumnDimension('I')->setWidth(50);
        $sheet->getColumnDimension('J')->setWidth(25);
        $sheet->getColumnDimension('K')->setWidth(25);
        $sheet->getColumnDimension('L')->setAutoSize(true);
        $sheet->getColumnDimension('M')->setWidth(25);
        $sheet->getColumnDimension('N')->setWidth(45);
        $sheet->getColumnDimension('O')->setWidth(25);


        $fileName = 'Daftar Buku Admin (PR) ' . date('Y') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        
        $writer = new Xlsx($spreadsheet);
        return $writer->save("php://output");
    }
}
