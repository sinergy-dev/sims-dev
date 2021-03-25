<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sales;
use App\Sales2;
use App\HistoryAuth;
use Carbon\Carbon;
use Session;
use Illuminate\Support\Facades\Redirect;
use Auth;
use DB;
use PDF;
use App\user;
use App\ProductTagRelation;
use App\ProductTag;

use Excel;

class ReportController extends Controller
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

    public function exportExcelLead(Request $request)
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

        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        if ($pos == 'DIRECTOR' || $div == 'TECHNICAL' && $pos == 'MANAGER') {
            $datas = Sales2::join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho')
                    ->where('year','2019')
                    ->get();
        }else if ($div == 'SALES' && Auth::User()->id_company == '1') {
            $datas = Sales2::join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho')
                    ->where('id_territory', $ter)
                    ->where('year','2019')
                    ->get();
        }else{
            $datas = Sales2::join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho')
                    ->where('id_territory', $ter)
                    ->where('year','2019')
                    ->get();
        }
        
       // $sheet->appendRow(array_keys($datas[0]));
            $sheet->row($sheet->getHighestRow(), function ($row) {
                $row->setFontWeight('bold');
            });

             $datasheet = array();
             $datasheet[0]  =   array("NO", "LEAD ID", "CUSTOMER LEGAL NAME", "OPTY NAME", "CREATE DATE", "OWNER", "AMOUNT", "STATUS");
             $i=1;

            foreach ($datas as $data) {

                if($data->result == 'OPEN') {
                    $datasheet[$i] = array($i,
                                $data['lead_id'],
                                $data['customer_legal_name'],
                                $data['opp_name'],
                                $data['created_at'],
                                $data['name'],
                                $data['amount'],
                                'INITIAL'
                            );
                    $i++;   
                } elseif($data->result == '') {
                    $datasheet[$i] = array($i,
                                $data['lead_id'],
                                $data['customer_legal_name'],
                                $data['opp_name'],
                                $data['created_at'],
                                $data['name'],
                                $data['amount'],
                                'OPEN'
                            );
                    $i++;   
                } else {
                    $datasheet[$i] = array($i,
                            $data['lead_id'],
                            $data['customer_legal_name'],
                            $data['opp_name'],
                            $data['created_at'],
                            $data['name'],
                            $data['amount'],
                            $data['result']
                        );
                    $i++;
                }
            }

            $sheet->fromArray($datasheet);
        });

        })->export('xls');
    }

    public function exportExcelOpen(Request $request)
    {
        $nama = 'Lead Register Open '.date('Y-m-d');
        Excel::create($nama, function ($excel) use ($request) {
        $excel->sheet('Lead Register (Open)', function ($sheet) use ($request) {
        
        $sheet->mergeCells('A1:H1');

       // $sheet->setAllBorders('thin');
        $sheet->row(1, function ($row) {
            $row->setFontFamily('Calibri');
            $row->setFontSize(11);
            $row->setAlignment('center');
            $row->setFontWeight('bold');
        });

        $sheet->row(1, array('LEAD REGISTER OPEN'));

        $sheet->row(2, function ($row) {
            $row->setFontFamily('Calibri');
            $row->setFontSize(11);
            $row->setFontWeight('bold');
        });

        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        if ($pos == 'DIRECTOR' || $div == 'TECHNICAL' && $pos == 'MANAGER') {
            $datas = Sales2::join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho')
                    ->where('year','2019')
                    ->get();
        }else if ($div == 'SALES' && Auth::User()->id_company == '1') {
            $datas = Sales2::join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho')
                    ->where('id_territory', $ter)
                    ->where('year','2019')
                    ->get();
        }else{
            $datas = Sales2::join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho')
                    ->where('id_territory', $ter)
                    ->where('year','2019')
                    ->get();
        }

       // $sheet->appendRow(array_keys($datas[0]));
            $sheet->row($sheet->getHighestRow(), function ($row) {
                $row->setFontWeight('bold');
            });

             $datasheet = array();
             $datasheet[0]  =   array("NO", "LEAD ID", "CUSTOMER", "OPTY NAME", "CREATE DATE", "OWNER", "AMOUNT", "STATUS");
             $i=1;


            foreach ($datas as $data) {

                if($data->result == '') {
                    $datasheet[$i] = array($i,
                                $data['lead_id'],
                                $data['customer_legal_name'],
                                $data['opp_name'],
                                $data['created_at'],
                                $data['name'],
                                $data['amount'],
                                'OPEN'
                            );
                    $i++;   
                } elseif($data->result == 'SD') {
                    $datasheet[$i] = array($i,
                                $data['lead_id'],
                                $data['customer_legal_name'],
                                $data['opp_name'],
                                $data['created_at'],
                                $data['name'],
                                $data['amount'],
                                'SD'
                            );
                    $i++;   
                } elseif($data->result == 'TP') {
                    $datasheet[$i] = array($i,
                                $data['lead_id'],
                                $data['customer_legal_name'],
                                $data['opp_name'],
                                $data['created_at'],
                                $data['name'],
                                $data['amount'],
                                'TP'
                            );
                    $i++;   
                }
            }

            $sheet->fromArray($datasheet);
        });

        })->export('xls');
    }

    public function exportExcelWin(Request $request)
    {
        $nama = 'Lead Register Win '.date('Y-m-d');
        Excel::create($nama, function ($excel) use ($request) {
        $excel->sheet('Lead Register (Win)', function ($sheet) use ($request) {
        
        $sheet->mergeCells('A1:H1');

       // $sheet->setAllBorders('thin');
        $sheet->row(1, function ($row) {
            $row->setFontFamily('Calibri');
            $row->setFontSize(11);
            $row->setAlignment('center');
            $row->setFontWeight('bold');
        });

        $sheet->row(1, array('LEAD REGISTER WIN'));

        $sheet->row(2, function ($row) {
            $row->setFontFamily('Calibri');
            $row->setFontSize(11);
            $row->setFontWeight('bold');
        });

        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        if ($pos == 'DIRECTOR' || $div == 'TECHNICAL' && $pos == 'MANAGER') {
            $datas = Sales2::join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho')
                    ->where('year','2019')
                    ->get();
        }else if ($div == 'SALES' && Auth::User()->id_company == '1') {
            $datas = Sales2::join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho')
                    ->where('id_territory', $ter)
                    ->where('year','2019')
                    ->get();
        }else{
            $datas = Sales2::join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho')
                    ->where('id_territory', $ter)
                    ->where('year','2019')
                    ->get();
        }

       // $sheet->appendRow(array_keys($datas[0]));
            $sheet->row($sheet->getHighestRow(), function ($row) {
                $row->setFontWeight('bold');
            });

             $datasheet = array();
             $datasheet[0]  =   array("NO", "LEAD ID", "CUSTOMER", "OPTY NAME", "CREATE DATE", "OWNER", "AMOUNT", "STATUS");
             $i=1;


            foreach ($datas as $data) {

                if($data->result == 'WIN') {
                    $datasheet[$i] = array($i,
                                $data['lead_id'],
                                $data['customer_legal_name'],
                                $data['opp_name'],
                                $data['created_at'],
                                $data['name'],
                                $data['amount'],
                                'WIN'
                            );
                    $i++;   
                }
            }

            $sheet->fromArray($datasheet);
        });

        })->export('xls');
    }

    public function exportExcelLose(Request $request)
    {
        $nama = 'Lead Register Lose '.date('Y-m-d');
        Excel::create($nama, function ($excel) use ($request) {
        $excel->sheet('Lead Register (Lose)', function ($sheet) use ($request) {
        
        $sheet->mergeCells('A1:H1');

       // $sheet->setAllBorders('thin');
        $sheet->row(1, function ($row) {
            $row->setFontFamily('Calibri');
            $row->setFontSize(11);
            $row->setAlignment('center');
            $row->setFontWeight('bold');
        });

        $sheet->row(1, array('LEAD REGISTER LOSE'));

        $sheet->row(2, function ($row) {
            $row->setFontFamily('Calibri');
            $row->setFontSize(11);
            $row->setFontWeight('bold');
        });

        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        if ($pos == 'DIRECTOR' || $div == 'TECHNICAL' && $pos == 'MANAGER') {
            $datas = Sales2::join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho')
                    ->where('year','2019')
                    ->get();
        }else if ($div == 'SALES' && Auth::User()->id_company == '1') {
            $datas = Sales2::join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho')
                    ->where('id_territory', $ter)
                    ->where('year','2019')
                    ->get();
        }else{
            $datas = Sales2::join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name','sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.status_sho')
                    ->where('id_territory', $ter)
                    ->where('year','2019')
                    ->get();
        }

       // $sheet->appendRow(array_keys($datas[0]));
            $sheet->row($sheet->getHighestRow(), function ($row) {
                $row->setFontWeight('bold');
            });

             $datasheet = array();
             $datasheet[0]  =   array("NO", "LEAD ID", "CUSTOMER", "OPTY NAME", "CREATE DATE", "OWNER", "AMOUNT", "STATUS");
             $i=1;


            foreach ($datas as $data) {

                if($data->result == 'LOSE') {
                    $datasheet[$i] = array($i,
                                $data['lead_id'],
                                $data['customer_legal_name'],
                                $data['opp_name'],
                                $data['created_at'],
                                $data['name'],
                                $data['amount'],
                                'LOSE'
                            );
                    $i++;   
                }
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

        $year = date('Y');

        // count semua lead
        if($ter != null){
            if ($div == 'FINANCE') {
                $lead = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'sales_lead_register.deal_price', 'users.name')
                    ->where('result','win')
                    ->where('year',$year)
                    ->get();
            } elseif ($div == 'PMO' && $pos == 'MANAGER') {
                $lead = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'sales_lead_register.deal_price', 'users.name')
                    ->where('year',$year)
                    ->where('id_company', '1')
                    ->where('result','!=','hmm')
                ->get();
            } elseif ($div == 'TECHNICAL PRESALES' && $pos == 'MANAGER') {
                $lead = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'sales_lead_register.deal_price', 'users.name')
                    ->where('year',$year)
                    ->where('id_company', '1')
                    ->where('result','!=','hmm')
                    ->get();
            } elseif($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
                $lead = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                    ->select('sales_lead_register.lead_id','tb_contact.brand_name', 'sales_lead_register.opp_name',
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.deal_price')
                    ->where('sales_solution_design.nik', $nik)
                    ->where('result','!=','hmm')
                    ->where('id_company', '1')
                    ->where('year',$year)
                    ->get();
            } else {
                $lead = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'sales_lead_register.deal_price', 'users.name')
                    ->where('id_territory', $ter)
                    ->where('year',$year)
                    ->get();
            }
        }elseif ($ter == null && $div == 'SALES') {
            $lead = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'sales_lead_register.deal_price', 'users.name')
                ->where('id_territory', $ter)
                ->where('year',$year)
                ->where('id_company','2')
                ->get();
        } elseif ($ter == 'DPG' && $pos == 'ENGINEER MANAGER') {
            $lead = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id','tb_contact.brand_name', 'sales_lead_register.opp_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.deal_price')
                ->where('result','win')
                ->where('year',$year)
                ->get();
        } elseif ($ter == 'DPG' && $pos == 'ENGINEER STAFF') {
            $lead = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('tb_engineer', 'tb_engineer.lead_id', '=', 'sales_lead_register.lead_id')
                ->select('sales_lead_register.lead_id','tb_contact.brand_name', 'sales_lead_register.opp_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.deal_price')
                ->where('tb_engineer.nik', $nik)
                ->where('year',$year)
                ->get();
        }  else {
            $lead = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'sales_lead_register.deal_price', 'users.name')
                ->where('year',$year)
                ->where('result','!=','hmm')
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

        return view('report/lead', compact('lead','leads','notif', 'total_ter', 'notifOpen', 'notifsd', 'notiftp', 'notifClaim'));
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

        $year = date('Y');

        if($ter != null){
            if($div == 'PMO' && $pos == 'MANAGER') {
                $open = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name', 'sales_lead_register.deal_price')
                    ->where('result', '')
                    ->where('id_company', '1')
                    ->where('year',$year)
                    ->get();
            } elseif($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
                $open = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                    ->select('sales_lead_register.lead_id','tb_contact.brand_name', 'sales_lead_register.opp_name',
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.deal_price')
                    ->where('sales_solution_design.nik', $nik)
                    ->where('result', '')
                    ->where('id_company', '1')
                    ->where('year',$year)
                    ->get();
            } elseif($div == 'TECHNICAL PRESALES' && $pos == 'MANAGER') {
                $open = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                    ->select('sales_lead_register.lead_id','tb_contact.brand_name', 'sales_lead_register.opp_name',
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.deal_price')
                    ->where('result', '')
                    ->where('id_company', '1')
                    ->where('year',$year)
                    ->get();
            } else {
                $open = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name', 'sales_lead_register.deal_price')
                    ->where('result', '')
                    ->where('id_territory', $ter)
                    ->where('year',$year)
                    ->get();
            }
        } elseif ($div == 'FINANCE') {
            $open = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('tb_id_project', 'tb_id_project.lead_id', '=', 'sales_lead_register.lead_id')
                ->select('sales_lead_register.lead_id','tb_contact.brand_name', 'sales_lead_register.opp_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.deal_price')
                ->where('sales_lead_register.status_sho','')
                ->where('year',$year)
                ->get();
        } else {
            $open = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name', 'sales_lead_register.deal_price')
                ->where('result', '')
                ->where('year',$year)
                ->get();
        }

        if($ter != null){
            if($div == 'PMO' && $pos == 'MANAGER') {
                $sd = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name', 'sales_lead_register.deal_price')
                    ->where('result', 'SD')
                    ->where('id_company', '1')
                    ->where('year',$year)
                    ->get();
            } elseif($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
                $sd = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                    ->select('sales_lead_register.lead_id','tb_contact.brand_name', 'sales_lead_register.opp_name',
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.deal_price')
                    ->where('sales_solution_design.nik', $nik)
                    ->where('year',$year)
                    ->where('result', 'SD')
                    ->get();
            }elseif($div == 'TECHNICAL PRESALES' && $pos == 'MANAGER') {
                $sd = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                    ->select('sales_lead_register.lead_id','tb_contact.brand_name', 'sales_lead_register.opp_name',
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.deal_price')
                    ->where('year',$year)
                    ->where('id_company', '1')
                    ->where('result', 'SD')
                    ->get();
            } else {
                $sd = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name', 'sales_lead_register.deal_price')
                    ->where('result', 'SD')
                    ->where('id_territory', $ter)
                    ->where('year',$year)
                    ->get();
            }
        }elseif ($div == 'FINANCE') {
            $sd = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('tb_id_project', 'tb_id_project.lead_id', '=', 'sales_lead_register.lead_id')
                ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'sales_lead_register.opp_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.deal_price')
                ->where('sales_lead_register.lead_id','tb_id_project.lead_id')
                ->where('year',$year)
                ->get();
        } else {
            $sd = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name', 'sales_lead_register.deal_price')
                ->where('result', 'SD')
                ->where('year',$year)
                ->get();
        }

        if($ter != null){
            if($div == 'PMO' && $pos == 'MANAGER') {
                $tp = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name', 'sales_lead_register.deal_price')
                    ->where('result', 'TP')
                    ->where('id_company', '1')
                    ->where('year',$year)
                    ->get();
            } elseif($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
                $tp = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                    ->select('sales_lead_register.lead_id','tb_contact.brand_name', 'sales_lead_register.opp_name',
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.deal_price')
                    ->where('sales_solution_design.nik', $nik)
                    ->where('result', 'TP')
                    ->where('year',$year)
                    ->get();
            }  elseif($div == 'TECHNICAL PRESALES' && $pos == 'MANAGER') {
                $tp = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                    ->select('sales_lead_register.lead_id','tb_contact.brand_name', 'sales_lead_register.opp_name',
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.deal_price')
                    ->where('id_company', '1')
                    ->where('result', 'TP')
                    ->where('year',$year)
                    ->get();
            } else {
                $tp = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name', 'sales_lead_register.deal_price')
                    ->where('result', 'TP')
                    ->where('id_territory', $ter)
                    ->where('year',$year)
                    ->get();
            }
        }elseif ($div == 'FINANCE') {
            $tp = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('tb_id_project', 'tb_id_project.lead_id', '=', 'sales_lead_register.lead_id')
                ->select('sales_lead_register.lead_id','tb_contact.customer_legal_name', 'sales_lead_register.opp_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.deal_price')
                ->where('sales_lead_register.lead_id','tb_id_project.lead_id')
                ->where('year',$year)
                ->get();
        } else {
            $tp = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name', 'sales_lead_register.deal_price')
                ->where('result', 'TP')
                ->where('year',$year)
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

        return view('report/open_status', compact('open','sd','tp','notif','total_ter_open', 'total_ter_sd', 'total_ter_tp', 'notifOpen', 'notifsd', 'notiftp','notifClaim'));
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

        $year = date('Y');

        if($ter != null){
            if($div == 'PMO' && $pos == 'MANAGER') {
                $win = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name', 'sales_lead_register.deal_price')
                    ->where('result', 'win')
                    ->where('id_company', '1')
                    ->where('year', $year)
                    ->get();
            } elseif($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
                $win = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                    ->select('sales_lead_register.lead_id','tb_contact.brand_name', 'sales_lead_register.opp_name',
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.deal_price')
                    ->where('sales_solution_design.nik', $nik)
                    ->where('result', 'WIN')
                    ->where('id_company', '1')
                    ->where('year',$year)
                    ->get();
            } elseif($div == 'TECHNICAL PRESALES' && $pos == 'MANAGER') {
                $win = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                    ->select('sales_lead_register.lead_id','tb_contact.brand_name', 'sales_lead_register.opp_name',
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.deal_price')
                    ->where('result', 'WIN')
                    ->where('id_company', '1')
                    ->where('year',$year)
                    ->get();
            } else {
                $win = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name', 'sales_lead_register.deal_price')
                    ->where('result', 'win')
                    ->where('id_territory', $ter)
                    ->where('year',$year)
                    ->get();
            }
        }elseif ($div == 'FINANCE') {
            $win = DB::table('dvg_esm')
                    ->join('users', 'users.nik', '=', 'dvg_esm.personnel')
                    ->select('no','date','users.name', 'type', 'description', 'amount', 'id_project', 'remarks', 'status', 'sales_lead_register.deal_price')
                    ->where('status', 'FINANCE')
                    ->get();
        } else {
            $win = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name', 'sales_lead_register.deal_price')
                ->where('result', 'win')
                ->where('year',$year)
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

        return view('report/win_status', compact('win', 'notif', 'total_ter', 'notifOpen', 'notifsd', 'notiftp','notifClaim'));
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

        $year = date('Y');

        if($ter != null){
            if($div == 'PMO' && $pos == 'MANAGER') {
                $lose = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name', 'sales_lead_register.deal_price')
                    ->where('result', 'lose')
                    ->where('id_company', '1')
                    ->where('year', $year)
                    ->get();
            }  elseif($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
                $lose = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                    ->select('sales_lead_register.lead_id','tb_contact.brand_name', 'sales_lead_register.opp_name',
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.deal_price')
                    ->where('sales_solution_design.nik', $nik)
                    ->where('result', 'LOSE')
                    ->where('id_company', '1')
                    ->where('year',$year)
                    ->get();
            }  elseif($div == 'TECHNICAL PRESALES' && $pos == 'MANAGER') {
                $lose = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                    ->select('sales_lead_register.lead_id','tb_contact.brand_name', 'sales_lead_register.opp_name',
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.deal_price')
                    ->where('id_company', '1')
                    ->where('result', 'LOSE')
                    ->where('year',$year)
                    ->get();
            } else {
                $lose = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name', 'sales_lead_register.deal_price')
                    ->where('result', 'lose')
                    ->where('id_territory', $ter)
                    ->where('year',$year)
                    ->get();
            }
        }elseif ($ter == null && $div == 'SALES') {
            $lose = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name', 'sales_lead_register.deal_price')
                ->where('result', 'lose')
                ->where('id_territory', $ter)
                ->where('id_company','2')
                ->where('year',$year)
                ->get();
        }elseif ($div == 'FINANCE') {
            $lose = DB::table('dvg_esm')
                    ->join('users', 'users.nik', '=', 'dvg_esm.personnel')
                    ->select('no','date','users.name', 'type', 'description', 'amount', 'id_project', 'remarks', 'status', 'sales_lead_register.deal_price')
                    ->where('status', 'TRANSFER')
                    ->get();
        } else {
            $lose = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name', 'sales_lead_register.deal_price')
                ->where('result', 'lose')
                ->where('year',$year)
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

        return view('report/lose_status', compact('lose', 'notif', 'total_ter', 'notifOpen', 'notifsd', 'notiftp','notifClaim'));
    }

    public function downloadPdflead()
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        $year = date('Y');

        
        if($ter != null){
            $lead = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
                ->where('id_territory', $ter)
                ->where('year',$year)
                ->get();
        } elseif($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $lead = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                ->select('sales_lead_register.lead_id','tb_contact.brand_name', 'sales_lead_register.opp_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('sales_solution_design.nik', $nik)
                ->where('year',$year)
                ->get();
        }elseif($div == 'PMO') {
            $lead = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
                ->where('sales_lead_register.result','WIN')
                ->where('year',$year)
                ->get();
        } else {
            $lead = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
                ->where('year',$year)
                ->get();
        }

        $pdf = PDF::loadView('report.ter_pdf', compact('lead'));
        return $pdf->download('report_lead-'.date("d-m-Y").'.pdf');
    }


    public function downloadPdfopen()
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        $year = date('Y');

        if($ter != null){
            $open = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
                ->where('result', '')
                ->where('id_territory', $ter)
                ->where('year',$year)
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
                ->where('year',$year)
                ->get();
        } else {
            $open = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
                ->where('result', '')
                ->where('year',$year)
                ->get();
        }

        if($ter != null){
            $sd = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
                ->where('result', 'sd')
                ->where('id_territory', $ter)
                ->where('year',$year)
                ->get();
        } elseif($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $sd = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                ->select('sales_lead_register.lead_id','tb_contact.brand_name', 'sales_lead_register.opp_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('sales_solution_design.nik', $nik)
                ->where('result', 'sd')
                ->where('year',$year)
                ->get();
        } else {
            $sd = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
                ->where('result', 'sd')
                ->where('year',$year)
                ->get();
        }

        if($ter != null){
            $tp = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
                ->where('result', 'tp')
                ->where('id_territory', $ter)
                ->where('year',$year)
                ->get();
        } elseif($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $tp = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                ->select('sales_lead_register.lead_id','tb_contact.brand_name', 'sales_lead_register.opp_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('sales_solution_design.nik', $nik)
                ->where('result', 'tp')
                ->where('year',$year)
                ->get();
        } else {
            $tp = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
                ->where('result', 'tp')
                ->where('year',$year)
                ->get();
        }

        $pdf = PDF::loadView('report.open_pdf', compact('open', 'sd', 'tp'));
        return $pdf->download('report_open.pdf');
    }

    public function downloadPdfwin()
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        $year = date('Y');

        if($ter != null){
            $win = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
                ->where('result', 'win')
                ->where('id_territory', $ter)
                ->where('year',$year)
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
                ->where('year',$year)
                ->get();
        } elseif($div == 'PMO' && $pos == 'MANAGER') {
            $win = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
                ->where('result', 'win')
                ->where('year',$year)
                ->get();
        } elseif($div == 'PMO' && $pos == 'STAFF') {
            $win = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('tb_pmo','sales_lead_register.lead_id','=','tb_pmo.lead_id')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
                ->where('result', 'win')
                ->where('tb_pmo.pmo_nik',$nik)
                ->where('year',$year)
                ->get();
        } else {
            $win = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
                ->where('result', 'win')
                ->where('year',$year)
                ->get();
        }
        $pdf = PDF::loadView('report.win_pdf', compact('win'));
        return $pdf->download('report_win'.date("d-m-Y").'.pdf');
    }

    public function downloadPdflose()
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        $year = date('Y');

        if($ter != null){
            $lose = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
                ->where('result', 'lose')
                ->where('id_territory', $ter)
                ->where('year',$year)
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
                ->where('year',$year)
                ->get();
        } else {
            $lose = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
                ->where('result', 'lose')
                ->where('year',$year)
                ->get();
        }
        $pdf = PDF::loadView('report.lose_pdf', compact('lose'));
        return $pdf->download('report_lose.pdf');
    }

    public function report()
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        $year = date('Y');

        if($ter != null){
            $lead = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
                ->where('id_territory', $ter)
                ->where('year',$year)
                ->where('result','!=','hmm')
                ->get();
        } elseif($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $lead = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                ->select('sales_lead_register.lead_id','tb_contact.brand_name', 'sales_lead_register.opp_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result')
                ->where('sales_solution_design.nik', $nik)
                ->where('year',$year)
                ->where('result','!=','hmm')
                ->get();
        } else {
            $lead = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
                ->where('year',$year)
                ->where('result','!=','hmm')
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

        return view('report/report', compact('lead', 'notif', 'notifOpen', 'notifsd','notiftp'));
    }

    public function getDropdown(Request $request)
    {
        if($request->id_client=='customer'){
            return array(DB::table('tb_contact')
                ->select('brand_name')
                ->get(),$request->id_client);
        } else if ($request->id_client == 'sales') {
            if (Auth::User()->id_position == 'DIRECTOR') {
                return array(DB::table('users')
                ->select('name')
                ->where('id_position','!=','ADMIN')
                ->where('id_division', 'SALES')
                ->get(),$request->id_client);
            }else if (Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL') {
                return array(DB::table('users')
                ->select('name')
                ->where('id_division', 'SALES')
                ->where('id_position','!=','ADMIN')
                ->where('id_company', '1')
                ->get(),$request->id_client);
            }else if (Auth::User()->id_division == 'SALES') {
                if (Auth::User()->id_company == '1') {
                    return array(DB::table('users')
                    ->select('name')
                    ->where('id_division', 'SALES')
                    ->where('id_position','!=','ADMIN')
                    ->where('id_company', '1')
                    ->get(),$request->id_client);

                }else if (Auth::User()->id_company == '2') {
                    return array(DB::table('users')
                    ->select('name')
                    ->where('id_division', 'SALES')
                    ->where('id_position','!=','ADMIN')
                    ->where('id_company', '2')
                    ->get(),$request->id_client);
                }
                
            } elseif (Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL PRESALES') {
                return array(DB::table('users')
                ->select('name')
                ->where('id_division', 'SALES')
                ->where('id_position','!=','ADMIN')
                ->where('id_company', '1')
                ->get(),$request->id_client);
            }
            
        } else if ($request->id_client == 'territory') {
            return array(DB::table('tb_territory')
                ->select('id_territory')
                ->get(),$request->id_client);
        } else if ($request->id_client == 'status') {
            return array(DB::table('sales_lead_register')
            ->select('result')
            ->get(),$request->id_client);
        } else if ($request->id_client == 'presales') {
            if (Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL PRESALES') {
                return array(DB::table('users')
                ->select('name')
                ->where('id_division', 'TECHNICAL PRESALES')
                ->where('id_company', '1')
                ->get(),$request->id_client);
            }
        } else if ($request->id_client == 'priority') {
            return array(DB::table('sales_solution_design')
                ->select('priority')
                ->get(),$request->id_client);
        } else if ($request->id_client == 'win') {
            return array(DB::table('sales_tender_process')
                ->select('win_prob')
                ->get(),$request->id_client);
        } else if ($request->id_client == 'DIR') {
            return array(DB::table('tb_quote')
                ->select('quote_number')
                ->where('position','DIR')
                ->get(),$request->id_client);
        } else if ($request->id_client == 'AM') {
            return array(DB::table('tb_quote')
                ->select('quote_number')
                ->where('position','AM')
                ->get(),$request->id_client);
        }
    }

    public function getCustomer(Request $request)
    {
            if ($request->type == 'customer') {
                $id_customer = DB::table('tb_contact')
                            ->where('brand_name',$request->customer)
                            ->value('id_customer');
                $customer = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
                    ->where('sales_lead_register.id_customer', $id_customer)
                    ->get();

                return $customer;
            } elseif ($request->type == 'sales') {
                $nik = DB::table('users')
                    ->where('name',$request->customer)
                    ->value('nik');
                $sales = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
                    ->where('sales_lead_register.nik', $nik)
                    ->get();
                
                return $sales;
            } elseif ($request->type == 'territory') {
                $ter = DB::table('tb_territory')
                    ->where('name_territory',$request->customer)
                    ->value('id_territory');
                $territory = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
                    ->where('users.id_territory', $ter)
                    ->get();
                
                return $territory;
            } elseif ($request->type == 'status') {
                $res = DB::table('sales_lead_register')
                    ->where('result',$request->customer)
                    ->value('result');

                    if ($res == 'OPEN') {
                        $status = DB::table('sales_lead_register')
                            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                            ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                            ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                            'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
                            ->where('result', '')
                            ->get();

                        return $status;
                    } elseif($res == 'SD') {
                    	$status = DB::table('sales_lead_register')
		                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
		                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
		                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
		                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
		                    ->where('result', 'SD')
		                    ->get();

                        return $status;
                    } elseif($res == 'TP') {
                        $status = DB::table('sales_lead_register')
                            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                            ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                            ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                            'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
                            ->where('result', 'TP')
                            ->get();

                        return $status;
                    } elseif($res == 'WIN') {
                        $status = DB::table('sales_lead_register')
                            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                            ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                            ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                            'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
                            ->where('result', 'WIN')
                            ->get();

                        return $status;
                    } elseif($res == 'LOSE') {
                        $status = DB::table('sales_lead_register')
                            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                            ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                            ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                            'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
                            ->where('result', 'LOSE')
                            ->get();

                        return $status;
                    }
            } elseif ($request->type == 'presales') {
                $pre = DB::table('users')
                    ->where('name',$request->customer)
                    ->value('nik');

                $presales = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->join('sales_solution_design', 'sales_lead_register.lead_id', '=', 'sales_solution_design.lead_id')
                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
                    ->where('sales_solution_design.nik', $pre)
                    ->get();
                return $presales;
            } elseif ($request->type == 'priority') {
                $prio = DB::table('sales_solution_design')
                    ->where('priority',$request->customer)
                    ->value('priority');

                if ($prio != NULL) {
                    $priority = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                        ->join('sales_solution_design', 'sales_lead_register.lead_id', '=', 'sales_solution_design.lead_id')
                        ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                        'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
                        ->where('sales_solution_design.priority', $prio)
                        ->get();
                }
                return $priority;
            } elseif ($request->type == 'win') {
                $win = DB::table('sales_tender_process')
                    ->where('win_prob',$request->customer)
                    ->value('win_prob');

                if ($win != NULL) {
                    $win_prob = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                        ->join('sales_tender_process', 'sales_lead_register.lead_id', '=', 'sales_tender_process.lead_id')
                        ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                        'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
                        ->where('sales_tender_process.win_prob', $win)
                        ->get();
                }
                return $win_prob;
           }
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

        $years = DB::table('sales_lead_register')
        		->select('year')
        		->where('year','!=',NULL)
        		->groupBy('year')
                ->get();

        $presales = '';        

        // count semua lead
        if($ter != null){
            $lead = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('tb_territory','tb_territory.id_territory','=','users.id_territory')
                ->join('sales_solution_design','sales_solution_design.lead_id','=','sales_lead_register.lead_id')
                ->join('sales_tender_process','sales_tender_process.lead_id','=','sales_lead_register.lead_id')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name', 'sales_lead_register.closing_date','tb_territory.name_territory','sales_solution_design.nik as nik_presales','sales_solution_design.priority','sales_tender_process.win_prob', 'sales_lead_register.deal_price', 'sales_lead_register.year')
                ->where('users.id_territory', $ter)
                ->where('result','!=','hmm')
                ->where('sales_solution_design.status',NULL)
                ->orWhere('sales_solution_design.status','closed')
                ->orderBy('created_at','desc')
                ->get();

        } elseif($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $lead = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                ->join('tb_territory','tb_territory.id_territory','=','users.id_territory')
                ->join('sales_solution_design','sales_solution_design.lead_id','=','sales_lead_register.lead_id')
                ->join('sales_tender_process','sales_tender_process.lead_id','=','sales_lead_register.lead_id')
                ->select('sales_lead_register.lead_id','tb_contact.brand_name', 'sales_lead_register.opp_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.closing_date','tb_territory.name_territory','sales_solution_design.nik as nik_presales','sales_solution_design.priority','sales_tender_process.win_prob', 'sales_lead_register.deal_price', 'sales_lead_register.year')
                ->where('sales_solution_design.nik', $nik)
                ->where('result','!=','hmm')
                ->where('sales_solution_design.status',NULL)
                ->orWhere('sales_solution_design.status','closed')
                ->orderBy('created_at','desc')
                ->get();

        } else {
            $lead = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('tb_territory','tb_territory.id_territory','=','users.id_territory')
                ->join('sales_solution_design','sales_solution_design.lead_id','=','sales_lead_register.lead_id')
                ->join('sales_tender_process','sales_tender_process.lead_id','=','sales_lead_register.lead_id')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name', 'sales_lead_register.closing_date','tb_territory.name_territory','sales_solution_design.nik as nik_presales','sales_solution_design.priority','sales_tender_process.win_prob', 'sales_lead_register.deal_price', 'sales_lead_register.year')
                ->where('result','!=','hmm')
                ->where('sales_solution_design.status',NULL)
                ->orWhere('sales_solution_design.status','closed')
                ->orderBy('created_at','desc')
                ->get();

        }

        $total_lead = DB::table('sales_lead_register')
                        ->where('result','!=','hmm')
                        ->where('year',date('Y'))
                        ->count('lead_id');

        $total_open = DB::table('sales_lead_register')
                        ->where('result','')
                        ->where('year',date('Y'))
                        ->count('lead_id');

        $total_sd = DB::table('sales_lead_register')
                        ->where('result','SD')
                        ->where('year',date('Y'))
                        ->count('lead_id');

        $total_tp = DB::table('sales_lead_register')
                        ->where('result','TP')
                        ->where('year',date('Y'))
                        ->count('lead_id');

        $total_win = DB::table('sales_lead_register')
                        ->where('result','WIN')
                        ->where('year',date('Y'))
                        ->count('lead_id');

        $total_lose = DB::table('sales_lead_register')
                        ->where('result','LOSE')
                        ->where('year',date('Y'))
                        ->count('lead_id');

        $rk = user::select('nik')->where('email','rizkik@sinergy.co.id')->first();

        $gp = user::select('nik')->where('email','ganjar@sinergy.co.id')->first();

        $st = user::select('nik')->where('email','satria@sinergy.co.id')->first();

        $rz = user::select('nik')->where('email','rizaldo@sinergy.co.id')->first();

        $nt = user::select('nik')->where('email','aura@sinergy.co.id')->first();

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

        $total_deal_price = DB::table('sales_lead_register')
                                ->select(DB::raw('SUM(sales_lead_register.deal_price) as deal_prices'))
                                ->where('result','!=','hmm')
                                ->first();

        return view('report/report_range', compact('lead', 'notif', 'notifOpen', 'notifsd','notiftp','presales','rk','gp','st','rz','nt', 'total_deal_price','total_lead','total_open','total_sd','total_tp','total_win','total_lose','years'));
    }

    public function total_deal_price(Request $request){
    	return array(DB::table('sales_lead_register')
                ->select('deal_price')
                ->where('year', $request->year)
                ->sum('deal_price'),$request->year);
    }

    public function report_deal_price()
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;


        $year_now = date("Y");

        $year = DB::table('sales_lead_register')->select('year')->where('year','!=',NULL)->groupBy('year')->get();

        // count semua lead
        /*if($ter != null){
            $lead = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('tb_territory','tb_territory.id_territory','=','users.id_territory')
                ->join('sales_solution_design','sales_solution_design.lead_id','=','sales_lead_register.lead_id')
                ->join('sales_tender_process','sales_tender_process.lead_id','=','sales_lead_register.lead_id')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name', 'sales_lead_register.closing_date','tb_territory.name_territory','sales_solution_design.nik as nik_presales','sales_solution_design.priority','sales_tender_process.win_prob','sales_lead_register.deal_price')
                ->where('users.id_territory', $ter)
                ->where('result','!=','hmm')
                ->where('sales_solution_design.status',NULL)
                ->orWhere('sales_solution_design.status','closed')
                ->orderBy('created_at','desc')
                ->get();

        } elseif($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $lead = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                ->join('tb_territory','tb_territory.id_territory','=','users.id_territory')
                ->join('sales_solution_design','sales_solution_design.lead_id','=','sales_lead_register.lead_id')
                ->join('sales_tender_process','sales_tender_process.lead_id','=','sales_lead_register.lead_id')
                ->select('sales_lead_register.lead_id','tb_contact.brand_name', 'sales_lead_register.opp_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.closing_date','tb_territory.name_territory','sales_solution_design.nik as nik_presales','sales_solution_design.priority','sales_tender_process.win_prob','sales_lead_register.deal_price')
                ->where('sales_solution_design.nik', $nik)
                ->where('result','!=','hmm')
                ->where('sales_solution_design.status',NULL)
                ->orWhere('sales_solution_design.status','closed')
                ->orderBy('created_at','desc')
                ->get();

        } else {*/
            $lead = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('tb_territory','tb_territory.id_territory','=','users.id_territory')
                ->join('sales_solution_design','sales_solution_design.lead_id','=','sales_lead_register.lead_id')
                ->join('sales_tender_process','sales_tender_process.lead_id','=','sales_lead_register.lead_id')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name', 'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name', 'sales_lead_register.closing_date','tb_territory.name_territory','sales_solution_design.nik as nik_presales','sales_solution_design.priority','sales_tender_process.win_prob','sales_lead_register.deal_price')
                ->where('result','!=','hmm')
                ->where('sales_solution_design.status',NULL)
                ->orWhere('sales_solution_design.status','closed')
                ->orderBy('created_at','desc')
                ->get();


            $leads_now = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->join('tb_territory','tb_territory.id_territory','=','users.id_territory')
                ->join('sales_solution_design','sales_solution_design.lead_id','=','sales_lead_register.lead_id')
                ->join('sales_tender_process','sales_tender_process.lead_id','=','sales_lead_register.lead_id')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name', 'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name', 'sales_lead_register.closing_date','tb_territory.name_territory','sales_solution_design.nik as nik_presales','sales_solution_design.priority','sales_tender_process.win_prob','sales_lead_register.deal_price')
                ->where('result','!=','hmm')
                ->where('sales_solution_design.status',NULL)
                ->orWhere('sales_solution_design.status','closed')
                ->whereYear('sales_lead_register.created_at', '=', $year_now-1)
                ->orwhere('year',$year_now)
                ->orderBy('created_at','desc')
                ->get();

        // }

        $total_lead = DB::table('sales_lead_register')
        				->where('result','!=','hmm')
                        ->count('lead_id');

        $total_open = DB::table('sales_lead_register')
                        ->where('result','')
                        ->count('lead_id');

        $total_sd = DB::table('sales_lead_register')
                        ->where('result','SD')
                        ->count('lead_id');

        $total_tp = DB::table('sales_lead_register')
                        ->where('result','TP')
                        ->count('lead_id');

        $total_win = DB::table('sales_lead_register')
                        ->where('result','WIN')
                        ->count('lead_id');

        $total_lose = DB::table('sales_lead_register')
                        ->where('result','LOSE')
                        ->count('lead_id');

        $rk = user::select('nik')->where('email','rizkik@sinergy.co.id')->first();

        $gp = user::select('nik')->where('email','ganjar@sinergy.co.id')->first();

        $st = user::select('nik')->where('email','satria@sinergy.co.id')->first();

        $rz = user::select('nik')->where('email','rizaldo@sinergy.co.id')->first();

        $nt = user::select('nik')->where('email','aura@sinergy.co.id')->first();

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

        $total_deal_price = DB::table('sales_lead_register')
                                ->select(DB::raw('SUM(sales_lead_register.deal_price) as deal_prices'))
                                ->where('result','!=','hmm')
                                ->first();

        return view('report/report_range2', compact('lead', 'notif', 'notifOpen', 'notifsd','notiftp','rk','gp','st','rz','nt', 'total_deal_price','total_lead','total_open','total_sd','total_tp','total_win','total_lose', 'year_now', 'year', 'leads_now'));
    
    }

    public function getfiltersd(Request $request) {

        $filter_sd = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                        ->select(DB::raw('COUNT(sales_lead_register.lead_id) as leads'), DB::raw('SUM(sales_lead_register.amount) as amounts'), 'users.name', 'tb_company.code_company')
                        ->where('result', 'SD')
                        ->where('sales_lead_register.created_at', '>=', $request->start)
                        ->where('sales_lead_register.created_at', '<=', $request->end)
                        ->groupBy('sales_lead_register.nik')
                        ->orderBy('amounts', 'desc')
                        ->get();

        return $filter_sd;

    }

    public function getfiltertp(Request $request) {

        $filter_tp = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                        ->select(DB::raw('COUNT(sales_lead_register.lead_id) as leads'), DB::raw('SUM(sales_lead_register.amount) as amounts'), 'users.name', 'tb_company.code_company')
                        ->where('result', 'TP')
                        ->where('sales_lead_register.created_at', '>=', $request->start)
                        ->where('sales_lead_register.created_at', '<=', $request->end)
                        ->groupBy('sales_lead_register.nik')
                        ->orderBy('amounts', 'desc')
                        ->get();

        return $filter_tp;

    }

    public function getfilterwin(Request $request) {

        $filter_win = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                        ->select(DB::raw('COUNT(sales_lead_register.lead_id) as leads'), DB::raw('SUM(sales_lead_register.amount) as amounts'), 'users.name', 'tb_company.code_company')
                        ->where('result', 'WIN')
                        ->where('sales_lead_register.created_at', '>=', $request->start)
                        ->where('sales_lead_register.created_at', '<=', $request->end)
                        ->groupBy('sales_lead_register.nik')
                        ->orderBy('amounts', 'desc')
                        ->get();

        return $filter_win;

    }

    public function getfilterlose(Request $request) {

        $filter_lose = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                        ->select(DB::raw('COUNT(sales_lead_register.lead_id) as leads'), DB::raw('SUM(sales_lead_register.amount) as amounts'), 'users.name', 'tb_company.code_company')
                        ->where('result', 'LOSE')
                        ->where('sales_lead_register.created_at', '>=', $request->start)
                        ->where('sales_lead_register.created_at', '<=', $request->end)
                        ->groupBy('sales_lead_register.nik')
                        ->orderBy('amounts', 'desc')
                        ->get();

        return $filter_lose;

    }

    public function getfiltersdyear(Request $request) {

        $filter_sd = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                        ->select(DB::raw('COUNT(sales_lead_register.lead_id) as leads'), DB::raw('SUM(sales_lead_register.amount) as amounts'), 'users.name', 'tb_company.code_company')
                        ->where('result', 'SD')
                        ->where('year', $request->data)
                        ->groupBy('sales_lead_register.nik')
                        ->orderBy('amounts', 'desc')
                        ->get();

        // return $filter_sd;
        return array("data" => $filter_sd);

    }

    public function getfiltertpyear(Request $request) {

        $filter_tp = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                        ->select(DB::raw('COUNT(sales_lead_register.lead_id) as leads'), DB::raw('SUM(sales_lead_register.amount) as amounts'), 'users.name', 'tb_company.code_company')
                        ->where('result', 'TP')
                        ->where('year', $request->data)
                        ->groupBy('sales_lead_register.nik')
                        ->orderBy('amounts', 'desc')
                        ->get();

        // return $filter_tp;
        return array("data" => $filter_tp);

    }

    public function getfilterwinyear(Request $request) {

        $filter_win = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                        ->select(DB::raw('COUNT(sales_lead_register.lead_id) as leads'), DB::raw('SUM(sales_lead_register.deal_price) as amounts'), 'users.name', 'tb_company.code_company')
                        ->where('result', 'WIN')
                        ->where('year', $request->data)
                        ->groupBy('sales_lead_register.nik')
                        ->orderBy('amounts', 'desc')
                        ->get();

        // return $filter_win;
        return array("data" => $filter_win);

    }

    public function getfilterloseyear(Request $request) {

        $filter_lose = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                        ->select(DB::raw('COUNT(sales_lead_register.lead_id) as leads'), DB::raw('SUM(sales_lead_register.amount) as amounts'), 'users.name', 'tb_company.code_company')
                        ->where('result', 'LOSE')
                        ->where('year', $request->data)
                        ->groupBy('sales_lead_register.nik')
                        ->orderBy('amounts', 'desc')
                        ->get();

        // return $filter_lose;
        return array("data" => $filter_lose);

    }

    public function getfiltertop(Request $request) {

        $year_now = DATE('Y');

        if ($request->data == 'ALL') {
            $top_win_sip = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                        ->join('sales_tender_process', 'sales_tender_process.lead_id', '=', 'sales_lead_register.lead_id')
                        ->select(DB::raw('COUNT(sales_lead_register.lead_id) as leads'), DB::raw('SUM(sales_lead_register.deal_price) as amounts'), 'users.name', 'tb_company.code_company')
                        ->where('result', 'WIN')
                        // ->where('sales_tender_process.win_prob', $request->data)
                        ->where('year', $request->tahun)
                        ->where('users.id_company', '1')
                        ->groupBy('sales_lead_register.nik')
                        ->orderBy('amounts', 'desc')
                        ->take(5)
                        ->get();
        }else{
            $top_win_sip = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                        ->join('sales_tender_process', 'sales_tender_process.lead_id', '=', 'sales_lead_register.lead_id')
                        ->select(DB::raw('COUNT(sales_lead_register.lead_id) as leads'), DB::raw('SUM(sales_lead_register.deal_price) as amounts'), 'users.name', 'tb_company.code_company')
                        ->where('result', 'WIN')
                        ->where('sales_tender_process.win_prob', $request->data)
                        ->where('year', $request->tahun)
                        ->where('users.id_company', '1')
                        ->groupBy('sales_lead_register.nik')
                        ->orderBy('amounts', 'desc')
                        ->take(5)
                        ->get();

        }

        return $top_win_sip;

    }

    public function getfiltertopmsp(Request $request) {

        $year_now = DATE('Y');

        if ($request->data == 'ALL') {
            $top_win_msp = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                        ->join('sales_tender_process', 'sales_tender_process.lead_id', '=', 'sales_lead_register.lead_id')
                        ->select(DB::raw('COUNT(sales_lead_register.lead_id) as leads'), DB::raw('SUM(sales_lead_register.deal_price) as amounts'), 'users.name', 'tb_company.code_company')
                        ->where('result', 'WIN')
                        // ->where('sales_tender_process.win_prob', $request->data)
                        ->where('year', $request->tahun)
                        ->where('users.id_company', '2')
                        ->groupBy('sales_lead_register.nik')
                        ->orderBy('amounts', 'desc')
                        ->take(5)
                        ->get();
        }else{
            $top_win_msp = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                        ->join('sales_tender_process', 'sales_tender_process.lead_id', '=', 'sales_lead_register.lead_id')
                        ->select(DB::raw('COUNT(sales_lead_register.lead_id) as leads'), DB::raw('SUM(sales_lead_register.deal_price) as amounts'), 'users.name', 'tb_company.code_company')
                        ->where('result', 'WIN')
                        ->where('sales_tender_process.win_prob', $request->data)
                        ->where('year', $request->tahun)
                        ->where('users.id_company', '2')
                        ->groupBy('sales_lead_register.nik')
                        ->orderBy('amounts', 'desc')
                        ->take(5)
                        ->get();
        }

        

        return $top_win_msp;

    }

    public function report_sales() {

        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        $years = DB::table('sales_lead_register')
                ->select('year')
                ->where('year','!=',NULL)
                ->orderBy('year','desc')
                ->groupBy('year')
                ->get();

        // TOP 5 Filter
        $year_now = DATE('Y');

        $top_win_sip = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                        ->select(DB::raw('COUNT(sales_lead_register.lead_id) as leads'), DB::raw('SUM(sales_lead_register.amount) as amounts'), DB::raw('SUM(sales_lead_register.deal_price) as deal_prices'), 'users.name', 'tb_company.code_company')
                        ->where('result', 'WIN')
                        ->where('year', $year_now)
                        ->where('users.id_company', '1')
                        ->groupBy('sales_lead_register.nik')
                        ->orderBy('deal_prices', 'desc')
                        ->take(5)
                        ->get();

        $top_win_msp = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                        ->select(DB::raw('COUNT(sales_lead_register.lead_id) as leads'), DB::raw('SUM(sales_lead_register.amount) as amounts'), DB::raw('SUM(sales_lead_register.deal_price) as deal_prices'), 'users.name', 'tb_company.code_company')
                        ->where('result', 'WIN')
                        ->where('year', $year_now)
                        ->where('users.id_company', '2')
                        ->groupBy('sales_lead_register.nik')
                        ->orderBy('deal_prices', 'desc')
                        ->take(5)
                        ->get();

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
        
        $lead_summary = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                        ->select(
                            DB::raw('COUNT(sales_lead_register.lead_id) as leads')
                            , DB::raw('SUM(sales_lead_register.amount) as amounts')
                            , 'users.name', 'tb_company.code_company')
                        ->where('year', '2019')
                        ->groupBy('sales_lead_register.nik')
                        ->orderBy('amounts', 'desc')
                        ->get();

        // TOP 5
        $year_now = DATE('Y');

        $top_win = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                        ->select(DB::raw('COUNT(sales_lead_register.lead_id) as leads'), DB::raw('SUM(sales_lead_register.amount) as amounts'), 'users.name', 'tb_company.code_company')
                        ->where('result', 'WIN')
                        ->where('year', $year_now)
                        ->groupBy('sales_lead_register.nik')
                        ->orderBy('amounts', 'desc')
                        ->take(5)
                        ->get();

        $lead_sales = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                        ->select(DB::raw('COUNT(sales_lead_register.lead_id) as leads'), DB::raw('SUM(sales_lead_register.amount) as amounts'), 'users.name', 'tb_company.code_company')
                        ->where('year', $year_now)
                        ->where('result', '!=', '')
                        ->where('result', '!=', 'OPEN')
                        ->where('result', '!=', 'CANCEL')
                        ->where('result', '!=', 'HOLD')
                        ->groupBy('sales_lead_register.nik')
                        ->orderBy('amounts', 'desc')
                        ->get();

        $lead_sd = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                        ->select(DB::raw('COUNT(sales_lead_register.lead_id) as leads'), DB::raw('SUM(sales_lead_register.amount) as amounts'), 'users.name', 'tb_company.code_company')
                        ->where('result', 'SD')
                        ->where('year', $year_now)
                        ->groupBy('sales_lead_register.nik')
                        ->orderBy('amounts', 'desc')
                        ->get();

        $lead_tp = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                        ->select(DB::raw('COUNT(sales_lead_register.lead_id) as leads'), DB::raw('SUM(sales_lead_register.amount) as amounts'), 'users.name', 'tb_company.code_company')
                        ->where('result', 'TP')
                        ->where('year', $year_now)
                        ->groupBy('sales_lead_register.nik')
                        ->orderBy('amounts', 'desc')
                        ->get();

        $lead_win = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                        ->select(DB::raw('COUNT(sales_lead_register.lead_id) as leads'), DB::raw('SUM(sales_lead_register.amount) as amounts'), 'users.name', 'tb_company.code_company')
                        ->where('result', 'WIN')
                        ->where('year', $year_now)
                        ->groupBy('sales_lead_register.nik')
                        ->orderBy('amounts', 'desc')
                        ->get();

        $lead_lose = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                        ->select(DB::raw('COUNT(sales_lead_register.lead_id) as leads'), DB::raw('SUM(sales_lead_register.amount) as amounts'), 'users.name', 'tb_company.code_company')
                        ->where('result', 'LOSE')
                        ->where('year', $year_now)
                        ->groupBy('sales_lead_register.nik')
                        ->orderBy('amounts', 'desc')
                        ->get();
        
        $cek_sales = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                        ->select(DB::raw('COUNT(sales_lead_register.lead_id) as leads'),'result')
                        ->groupBy('result')
                        ->get();


        $total_ter = DB::table("sales_lead_register")
                        ->where('year', $year_now)
                        ->sum('amount');

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

        return view('report/report_sales', compact('lead', 'notif', 'notifOpen', 'notifsd', 'notiftp', 'total_ter', 'lead_sales','cek_sales', 'lead_sd', 'lead_tp', 'lead_win', 'lead_lose', 'lead_summary', 'top_win', 'top_win_sip', 'top_win_msp', 'years'));

    }

    public function get_data_sd_report_sales(Request $request)
    {
        $lead_sd = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                        ->select(DB::raw('COUNT(sales_lead_register.lead_id) as leads'), DB::raw('SUM(sales_lead_register.amount) as amounts'), 'users.name', 'tb_company.code_company')
                        ->where('result', 'SD')
                        ->where('year', date('Y'))
                        ->groupBy('sales_lead_register.nik')
                        ->orderBy('amounts', 'desc')
                        ->get();

        return array("data" => $lead_sd);
    }

    public function get_data_tp_report_sales(Request $request)
    {
        $lead_tp = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                        ->select(DB::raw('COUNT(sales_lead_register.lead_id) as leads'), DB::raw('SUM(sales_lead_register.amount) as amounts'), 'users.name', 'tb_company.code_company')
                        ->where('result', 'TP')
                        ->where('year', date('Y'))
                        ->groupBy('sales_lead_register.nik')
                        ->orderBy('amounts', 'desc')
                        ->get();

        return array("data" => $lead_tp);
    }

    public function get_data_win_report_sales(Request $request)
    {
        $lead_win = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                        ->select(DB::raw('COUNT(sales_lead_register.lead_id) as leads'), DB::raw('SUM(sales_lead_register.amount) as amounts'), 'users.name', 'tb_company.code_company')
                        ->where('result', 'WIN')
                        ->where('year', date('Y'))
                        ->groupBy('sales_lead_register.nik')
                        ->orderBy('amounts', 'desc')
                        ->get();

        return array("data" => $lead_win);
    }

    public function get_data_lose_report_sales(Request $request)
    {
        $lead_lose = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                        ->select(DB::raw('COUNT(sales_lead_register.lead_id) as leads'), DB::raw('SUM(sales_lead_register.amount) as amounts'), 'users.name', 'tb_company.code_company')
                        ->where('result', 'LOSE')
                        ->where('year', date('Y'))
                        ->groupBy('sales_lead_register.nik')
                        ->orderBy('amounts', 'desc')
                        ->get();

        return array("data" => $lead_lose);
    }

    public function report_presales() {

        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        $years = DB::table('sales_lead_register')
                ->select('year')
                ->where('year','!=',NULL)
                ->orderBy('year', 'desc')
                ->groupBy('year')
                ->get();

        // TOP 5 Filter
        $year_now = DATE('Y');


        $top_win_presales = DB::table('sales_lead_register')->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
        					->join('users as presales', 'presales.nik', '=', 'sales_solution_design.nik')
        					->select('presales.name', 
        						DB::raw('COUNT(IF(`sales_lead_register`.`result` = "OPEN",1,NULL)) AS "INITIAL"'), 
        						DB::raw('COUNT(IF(`sales_lead_register`.`result` = "",1,NULL)) AS "OPEN"'), 
        						DB::raw('COUNT(IF(`sales_lead_register`.`result` = "SD",1,NULL)) AS "SD"'),
        						DB::raw('COUNT(IF(`sales_lead_register`.`result` = "TP",1,NULL)) AS "TP"'),
        						DB::raw('COUNT(IF(`sales_lead_register`.`result` = "WIN",1,NULL)) AS "WIN"'),
        						DB::raw('COUNT(IF(`sales_lead_register`.`result` = "LOSE",1,NULL)) AS "LOSE"'),
        						DB::raw('COUNT(IF(`sales_lead_register`.`result` = "HOLD",1,NULL)) AS "HOLD"'),
        						DB::raw('COUNT(IF(`sales_lead_register`.`result` = "CANCEL",1,NULL)) AS "CANCEL"'),
        						DB::raw('COUNT(IF(`sales_lead_register`.`result` = "SPESIAL",1,NULL)) AS "SPESIAL"'),
        						DB::raw('COUNT(*) AS `All`')
        					)
        					->where('id_company','1')
        					->groupBy('sales_solution_design.nik')
        					->get();

        // return $top_win_presales;

        $users = User::select('name','nik')->where('id_territory', 'PRESALES')->where('name', '!=', 'PRESALES')->get();


        foreach ($users as $user) {
	        $user->lead_register = Sales2::join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
	                		->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
	                        ->join('users as presales', 'presales.nik', '=', 'sales_solution_design.nik')
	                        ->join('users as sales', 'sales.nik', '=', 'sales_lead_register.nik')
	                        ->select('sales_solution_design.lead_id', 'opp_name', 'deal_price', 'result', 'sales_lead_register.amount', 'tb_contact.brand_name',  'sales.name')
	                        ->where('sales_solution_design.nik',$user->nik)
	                        ->where('presales.id_company', '1')
	                        ->whereYear('sales_solution_design.created_at', '2019')
	                        ->orderBy('result','desc')
	                        ->get();
        }

        // return $users;

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
        
        $lead_summary = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                        ->select(
                            DB::raw('COUNT(sales_lead_register.lead_id) as leads')
                            , DB::raw('SUM(sales_lead_register.amount) as amounts')
                            , 'users.name', 'tb_company.code_company')
                        ->where('year', '2019')
                        ->groupBy('sales_lead_register.nik')
                        ->orderBy('amounts', 'desc')
                        ->get();

        // TOP 5
        $year_now = DATE('Y');

        $top_win = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                        ->select(DB::raw('COUNT(sales_lead_register.lead_id) as leads'), DB::raw('SUM(sales_lead_register.amount) as amounts'), 'users.name', 'tb_company.code_company')
                        ->where('result', 'WIN')
                        ->where('year', $year_now)
                        ->groupBy('sales_lead_register.nik')
                        ->orderBy('amounts', 'desc')
                        ->take(5)
                        ->get();

        $lead_sales = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                        ->select(DB::raw('COUNT(sales_lead_register.lead_id) as leads'), DB::raw('SUM(sales_lead_register.amount) as amounts'), 'users.name', 'tb_company.code_company')
                        ->where('year', $year_now)
                        ->where('result', '!=', '')
                        ->where('result', '!=', 'OPEN')
                        ->where('result', '!=', 'CANCEL')
                        ->where('result', '!=', 'HOLD')
                        ->groupBy('sales_lead_register.nik')
                        ->orderBy('amounts', 'desc')
                        ->get();

        $lead_sd = DB::table('sales_lead_register')
        				->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                        ->join('users', 'users.nik', '=', 'sales_solution_design.nik')
                        ->select(DB::raw('COUNT(sales_lead_register.lead_id) as leads'), DB::raw('SUM(sales_lead_register.amount) as amounts'), 'users.name')
                        ->where('result', 'SD')
                        ->where('year', date("Y"))
                        ->where('users.id_company', '1')
                        ->where('sales_solution_design.nik', '!=', '1100492050')
                        ->groupBy('sales_solution_design.nik')
                        ->orderBy('amounts', 'desc')
                        ->get();

        $lead_tp = DB::table('sales_lead_register')
        				->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                        ->join('users', 'users.nik', '=', 'sales_solution_design.nik')
                        ->select(DB::raw('COUNT(sales_lead_register.lead_id) as leads'), DB::raw('SUM(sales_lead_register.amount) as amounts'), 'users.name')
                        ->where('result', 'TP')
                        ->where('year', date("Y"))
                        ->where('users.id_company', '1')
                        ->where('sales_solution_design.nik', '!=', '1100492050')
                        ->groupBy('sales_solution_design.nik')
                        ->orderBy('amounts', 'desc')
                        ->get();

        $lead_win = DB::table('sales_lead_register')
        				->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                        ->join('users', 'users.nik', '=', 'sales_solution_design.nik')
                        ->select(DB::raw('COUNT(sales_lead_register.lead_id) as leads'), DB::raw('SUM(sales_lead_register.amount) as amounts'), DB::raw('SUM(sales_lead_register.deal_price) as deal_prices'), 'users.name')
                        ->where('result', 'WIN')
                        ->where('year', date("Y"))
                        ->where('users.id_company', '1')
                        ->where('sales_solution_design.nik', '!=', '1100492050')
                        ->groupBy('sales_solution_design.nik')
                        ->orderBy('deal_prices', 'desc')
                        ->get();

        $lead_lose = DB::table('sales_lead_register')
        				->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                        ->join('users', 'users.nik', '=', 'sales_solution_design.nik')
                        ->select(DB::raw('COUNT(sales_lead_register.lead_id) as leads'), DB::raw('SUM(sales_lead_register.amount) as amounts'), 'users.name')
                        ->where('result', 'LOSE')
                        ->where('year', date("Y"))
                        ->where('users.id_company', '1')
                        ->where('sales_solution_design.nik', '!=', '1100492050')
                        ->groupBy('sales_solution_design.nik')
                        ->orderBy('amounts', 'desc')
                        ->get();
        
        $cek_sales = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                        ->select(DB::raw('COUNT(sales_lead_register.lead_id) as leads'),'result')
                        ->groupBy('result')
                        ->get();


        $total_ter = DB::table("sales_lead_register")
                        ->where('year', $year_now)
                        ->sum('amount');

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

        return view('report/report_presales', compact('lead', 'notif', 'notifOpen', 'notifsd', 'notiftp', 'total_ter', 'lead_sales','cek_sales', 'lead_sd', 'lead_tp', 'lead_win', 'lead_lose', 'lead_summary', 'top_win', 'top_win_presales', 'years', 'users'));

    }

    public function report_customer(){
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        $territory_loop = DB::table("tb_territory")
            ->select("id_territory","code_ter")
            ->where('id_territory','like','TERRITORY%')
            // ->orWhere('id_territory','=','OPERATION')
            ->get();

        $notifClaim = '';


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
        return view('report/report_territory', compact('notif', 'notifOpen', 'notifsd', 'notiftp', 'notifClaim' ,'territory_loop'));
    
    }

    public function getreportterritory(Request $request){
        $data = Sales2::join('users','users.nik','=','sales_lead_register.nik')
            ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
            ->select('users.name','users.id_territory','tb_contact.brand_name','users.id_territory',
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
                DB::raw('SUM(amount) AS `amount_All`')
            )
            ->where('result','!=','CANCEL')
            ->where('result','!=','HOLD')
            ->where('result','!=','SPECIAL')
            ->where('sales_lead_register.result','!=','hmm')
            ->groupBy('sales_lead_register.nik')
            ->groupBy('sales_lead_register.id_customer');

        
        if(isset($request->start_date) && isset($request->end_date)){
            $data->where('sales_lead_register.created_at', '>=', $request->start_date);
            $data->where('sales_lead_register.created_at', '<=', $request->end_date);
        } else {
            $data->whereYear('sales_lead_register.created_at',date("Y"));
        }

        if(Auth::User()->id_division == 'SALES') {
            $data->where('id_territory',Auth::User()->id_territory);
        } else{
            $data->where('id_territory',$request->id_territory);
        }
        

        return array("data" => $data->get());
    }

    public function report_product_technology(){
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        $notifClaim = '';

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
        return view('report/report_product_technology', compact('notif', 'notifOpen', 'notifsd', 'notiftp', 'notifClaim'));
    
    }

    public function report_product_technology_sip_msp(){
        return array("data" => Sales2::join('users','users.nik','=','sales_lead_register.nik')
                ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
                ->select('users.name','sales_lead_register.opp_name','sales_lead_register.amount','sales_lead_register.deal_price','tb_contact.brand_name','sales_lead_register.lead_id')
                ->get());
    
    }

    public function getFilterTags(Request $request){

        // return $request->TagsProduct;
        // return '"'.implode('"'.",".'"',$request->TagsProduct).'"';

        // return DB::table('tb_product_tag_relation')
        //     ->selectRaw('
        //         `lead_id`,
        //         GROUP_CONCAT(`id_product_tag`) AS `result`
        //     ')
        //     ->groupBy('lead_id')
        //     // ->WhereIn('result',$request->TagsProduct)
        //     ->get();

         $query_product = DB::table('tb_product_tag_relation')
                    ->join('tb_product_tag','tb_product_tag.id','=','tb_product_tag_relation.id_product_tag')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_product_tag_relation.lead_id')
                    ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
                    ->LeftJoin('sales_solution_design','sales_solution_design.lead_id','=','sales_lead_register.lead_id')
                    ->join('users as sales','sales.nik','=','sales_lead_register.nik')
                    ->Leftjoin('users as presales','presales.nik','=','sales_solution_design.nik')
                    ->select("sales_lead_register.lead_id","sales.name as name_sales","presales.name as name_presales","opp_name","amount","brand_name","name_product","price");

        $query_tech = DB::table('tb_technology_tag_relation')
            ->join('tb_technology_tag','tb_technology_tag.id','=','tb_technology_tag_relation.id_tech_tag')
            ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_technology_tag_relation.lead_id')
            ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
            ->LeftJoin('sales_solution_design','sales_solution_design.lead_id','=','tb_technology_tag_relation.lead_id')
            ->join('users as sales','sales.nik','=','sales_lead_register.nik')
            ->Leftjoin('users as presales','presales.nik','=','sales_solution_design.nik')
            ->select("sales_lead_register.lead_id","sales.name as name_sales","presales.name as name_presales","opp_name","amount","price","name_tech","brand_name");

        if ($request->TagsPersona == ["2"]) {
                if ($request->TagsProduct == ["-1"]) {
                    $query_tech_result = $query_tech->whereIn('tb_technology_tag_relation.id_tech_tag',$request->Tagstechno)
                    ->orderBy('lead_id','DESC')
                    ->where('sales_lead_register.created_at','>=',$request->start_date)
                    ->where('sales_lead_register.created_at','<=',$request->end_date)
                    ->get();

                    $query_product_result = $query_product->orderBy('lead_id','DESC')
                    ->whereIn('tb_product_tag_relation.lead_id',$query_tech_result->pluck("lead_id"))
                    ->where('sales_lead_register.created_at','>=',$request->start_date)
                    ->where('sales_lead_register.created_at','<=',$request->end_date)
                    ->get();
                    
                }else if ($request->Tagstechno == ["-1"]) {
                    $query_product_result = $query_product->whereIn('tb_product_tag_relation.id_product_tag',$request->TagsProduct)
                    ->orderBy('lead_id','DESC')
                    ->where('sales_lead_register.created_at','>=',$request->start_date)
                    ->where('sales_lead_register.created_at','<=',$request->end_date)
                    ->get();

                    $query_tech_result = $query_tech->orderBy('lead_id','DESC')
                    ->whereIn('tb_technology_tag_relation.lead_id',$query_product_result->pluck("lead_id"))
                    ->where('sales_lead_register.created_at','>=',$request->start_date)
                    ->where('sales_lead_register.created_at','<=',$request->end_date)
                    ->get();
                    //  $query_tech = DB::table('tb_technology_tag_relation')
                    // ->join('tb_technology_tag','tb_technology_tag.id','=','tb_technology_tag_relation.id_tech_tag')
                    // ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_technology_tag_relation.lead_id')
                    // ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
                    // ->LeftJoin('sales_solution_design','sales_solution_design.lead_id','=','tb_technology_tag_relation.lead_id')
                    // ->join('users as sales','sales.nik','=','sales_lead_register.nik')
                    // ->Leftjoin('users as presales','presales.nik','=','sales_solution_design.nik')
                    // ->select("sales_lead_register.lead_id","sales.name as name_sales","presales.name as name_presales","opp_name","amount","price","name_tech","brand_name")
                    // // ->whereIn('tb_technology_tag_relation.id_tech_tag',$)
                    // ->orderBy('lead_id','DESC')
                    // ->get();

                    // return $query_tech;
                    // $query_all = collect(array_merge($query_product->toArray()))->sortBy('lead_id');
                
                }else{
                   $query_product_result = $query_product->whereIn('tb_product_tag_relation.id_product_tag',$request->TagsProduct)
                    ->orderBy('lead_id','DESC')
                    ->where('sales_lead_register.created_at','>=',$request->start_date)
                    ->where('sales_lead_register.created_at','<=',$request->end_date)
                    ->get();

                    $query_tech_result = $query_tech->whereIn('tb_technology_tag_relation.id_tech_tag',$request->Tagstechno)
                    ->orderBy('lead_id','DESC')
                    ->where('sales_lead_register.created_at','>=',$request->start_date)
                    ->where('sales_lead_register.created_at','<=',$request->end_date)
                    ->get();
                
                }
        }else{
                if ($request->TagsProduct == ["-1"]) {
                    $query_tech_result = $query_tech->whereIn('tb_technology_tag_relation.id_tech_tag',$request->Tagstechno)
                    ->orderBy('lead_id','DESC')
                    ->where('sales_lead_register.created_at','>=',$request->start_date)
                    ->where('sales_lead_register.created_at','<=',$request->end_date)
                    ->whereIn('sales.nik',$request->TagsPersona)
                    ->orWhereIn('presales.nik',$request->TagsPersona)
                    ->get();

                    $query_product_result = $query_product->orderBy('lead_id','DESC')
                    ->whereIn('tb_product_tag_relation.lead_id',$query_tech_result->pluck("lead_id"))
                    ->where('sales_lead_register.created_at','>=',$request->start_date)
                    ->where('sales_lead_register.created_at','<=',$request->end_date)
                    ->whereIn('sales.nik',$request->TagsPersona)
                    ->orWhereIn('presales.nik',$request->TagsPersona)
                    ->get();
   
                }else if ($request->Tagstechno == ["-1"]) {
                    $query_product_result = $query_product->whereIn('tb_product_tag_relation.id_product_tag',$request->TagsProduct)
                    ->orderBy('lead_id','DESC')
                    ->where('sales_lead_register.created_at','>=',$request->start_date)
                    ->where('sales_lead_register.created_at','<=',$request->end_date)
                    ->whereIn('sales.nik',$request->TagsPersona)
                    ->orWhereIn('presales.nik',$request->TagsPersona)
                    ->get();

                    $query_tech_result = $query_tech->orderBy('lead_id','DESC')
                    ->whereIn('tb_technology_tag_relation.lead_id',$query_product_result->pluck("lead_id"))
                    ->where('sales_lead_register.created_at','>=',$request->start_date)
                    ->where('sales_lead_register.created_at','<=',$request->end_date)
                    ->whereIn('sales.nik',$request->TagsPersona)
                    ->orWhereIn('presales.nik',$request->TagsPersona)
                    ->get();
                
                }else{
                   $query_product_result = $query_product->whereIn('tb_product_tag_relation.id_product_tag',$request->TagsProduct)
                    ->orderBy('lead_id','DESC')
                    ->where('sales_lead_register.created_at','>=',$request->start_date)
                    ->where('sales_lead_register.created_at','<=',$request->end_date)
                    ->whereIn('sales.nik',$request->TagsPersona)
                    ->orWhereIn('presales.nik',$request->TagsPersona)
                    ->get();

                    $query_tech_result = $query_tech->whereIn('tb_technology_tag_relation.id_tech_tag',$request->Tagstechno)
                    ->orderBy('lead_id','DESC')
                    ->where('sales_lead_register.created_at','>=',$request->start_date)
                    ->where('sales_lead_register.created_at','<=',$request->end_date)
                    ->whereIn('sales.nik',$request->TagsPersona)
                    ->orWhereIn('presales.nik',$request->TagsPersona)
                    ->get();
                
                }

        }

        $query_all = collect(array_merge($query_product_result->toArray(),$query_tech_result->toArray()))->sortBy('lead_id');

        $sorted = $query_all->values()->all();
        
        return array("data"=>$sorted);        
    
    }

    public function getreportcustomermsp(){
        return array("data" => Sales2::join('users','users.nik','=','sales_lead_register.nik')
                ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
                ->select('users.name','users.id_territory','tb_contact.brand_name','users.id_territory',
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
                    DB::raw('SUM(amount) AS `amount_All`')
                )
                ->where('result','!=','HOLD')
                ->where('result','!=','SPECIAL')
                ->where('result','!=','CANCEL')
                ->whereYear('sales_lead_register.created_at',date("Y"))
                ->where('id_company',2)
                ->where('sales_lead_register.result','!=','hmm')
                ->groupBy('sales_lead_register.nik')
                ->groupBy('sales_lead_register.id_customer')
                ->get());
    
    }

    public function getFilterDateTerritory(Request $request){
        $data = Sales2::join('users','users.nik','=','sales_lead_register.nik')
            ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
            ->select('users.name','users.id_territory','tb_contact.brand_name','users.id_territory',
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
                DB::raw('SUM(amount) AS `amount_All`')
            )
            ->where('result','!=','CANCEL')
            ->where('result','!=','HOLD')
            ->where('result','!=','SPECIAL')
            ->where('sales_lead_register.result','!=','hmm')
            ->where('sales_lead_register.created_at', '>=', $request->start_date)
            ->where('sales_lead_register.created_at', '<=', $request->end_date)
            ->groupBy('sales_lead_register.nik')
            ->groupBy('sales_lead_register.id_customer');
        
        if(isset($request->id_territory)){
            if($request->id_territory == "OPERATION"){
                // $data->where('users.id_territory',$request->id_territory);
                $data->where('users.nik','=','100000000003');
            } else {
                $data->where('users.id_territory',$request->id_territory);
            }
        } else {
            $data->where('users.id_territory',Auth::User()->id_territory);
        }

        return array("data" => $data->get());
    
    }

    public function getfiltercustomermsp(Request $request){
        return array("data" => Sales2::join('users','users.nik','=','sales_lead_register.nik')
                ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
                ->select('users.name','users.id_territory','tb_contact.brand_name','users.id_territory',
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
                    DB::raw('SUM(amount) AS `amount_All`')
                )
                ->where('result','!=','CANCEL')
                ->where('result','!=','HOLD')
                ->where('result','!=','SPECIAL')
                ->where('sales_lead_register.created_at', '>=', $request->start_date)
                ->where('sales_lead_register.created_at', '<=', $request->end_date)
                ->where('id_company',2)
                ->where('sales_lead_register.result','!=','hmm')
                ->groupBy('sales_lead_register.nik')
                ->groupBy('sales_lead_register.id_customer')
                ->get());
    
    }

    public function getFilterTerritoryTabs(Request $request){
        $data = Sales2::join('users','users.nik','=','sales_lead_register.nik')
            ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
            ->select('users.name','users.id_territory','tb_contact.brand_name','users.id_territory',
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
                DB::raw('SUM(amount) AS `amount_All`')
            )
            ->where('result','!=','CANCEL')
            ->where('result','!=','HOLD')
            ->where('result','!=','SPECIAL')
            ->where('sales_lead_register.result','!=','hmm')
            ->groupBy('sales_lead_register.nik')
            ->groupBy('sales_lead_register.id_customer');

        if($request->id_territory == "OPERATION"){
            // $data->where('users.id_territory',$request->id_territory);
            $data->where('users.nik','=','100000000003');
        } else {
            $data->where('users.id_territory',$request->id_territory);
        }
        
        if(isset($request->start_date) && isset($request->end_date)){
            $data->where('sales_lead_register.created_at', '>=', $request->start_date);
            $data->where('sales_lead_register.created_at', '<=', $request->end_date);
        } else {
            $data->whereYear('sales_lead_register.created_at',date("Y"));
        }

        if($request->id_territory == "OPERATION"){
            // $data->where('users.id_territory',$request->id_territory);
            $data->where('users.nik','=','100000000003');
            $data = $data->get()->map(function ($arr) {
                $arr['id_territory'] = "OPERATION";
                return $arr;
            });
            return array("data" => $data);

        } else {
            $data->where('users.id_territory',$request->id_territory);
        return array("data" => $data->get());

        }

    
    }

    public function download_excel_presales_win(Request $request)
    {
    	$nama = 'Report Presales '.date("d-m-Y");
        Excel::create($nama, function ($excel) use ($request) {

        	$excel->sheet("Total Lead", function ($sheet) use ($request) {
        
                $sheet->mergeCells('A1:L1');

                $sheet->row(1, function ($row) {
                    $row->setFontFamily('Calibri');
                    $row->setFontSize(12);
                    $row->setAlignment('center');
                    $row->setFontWeight('bold');
                });

                $sheet->row(1, array('Total Lead Register'));

                $sheet->row(2, function ($row) {
                    $row->setFontFamily('Calibri');
                    $row->setFontSize(11);
                    $row->setFontWeight('bold');
                });

                $top_win_presales = Sales2::join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
        					->join('users as presales', 'presales.nik', '=', 'sales_solution_design.nik')
        					->select('presales.name', 
        						DB::raw('IFNULL(COUNT(IF(`sales_lead_register`.`result` = "OPEN",1,NULL)), "-") AS "INITIAL"'), 
        						DB::raw('IFNULL(COUNT(IF(`sales_lead_register`.`result` = "",1,NULL)), "-") AS "OPEN"'), 
        						DB::raw('IFNULL(COUNT(IF(`sales_lead_register`.`result` = "SD",1,NULL)), "-") AS "SD"'),
        						DB::raw('IFNULL(COUNT(IF(`sales_lead_register`.`result` = "TP",1,NULL)), "-") AS "TP"'),
        						DB::raw('IFNULL(COUNT(IF(`sales_lead_register`.`result` = "WIN",1,NULL)), "-") AS "WIN"'),
        						DB::raw('IFNULL(COUNT(IF(`sales_lead_register`.`result` = "LOSE",1,NULL)), "-") AS "LOSE"'),
        						DB::raw('IFNULL(COUNT(IF(`sales_lead_register`.`result` = "HOLD",1,NULL)), "-") AS "HOLD"'),
        						DB::raw('IFNULL(COUNT(IF(`sales_lead_register`.`result` = "CANCEL",1,NULL)), "-") AS "CANCEL"'),
        						DB::raw('IFNULL(COUNT(IF(`sales_lead_register`.`result` = "SPESIAL",1,NULL)), "-") AS "SPESIAL"'),
        						DB::raw('COUNT(*) AS `All`')
        					)
        					->where('id_company','1')
        					->where('sales_lead_register.year', $request->type)
        					->groupBy('sales_solution_design.nik')
        					->get();

                $datasheetpo = array();
                $datasheetpo[0] = array("No","Presales Name", "Total Initial", "Total Open", "Total Sd","Total Tp","Total Win","Total Lose", "Total Hold", "Total Cancel", "Total Spesial", "Total Lead");
                $i=1;

                foreach ($top_win_presales as $data) {
                    $datasheetpo[$i] = array($i,
                            $data['name'],
                            $data['INITIAL'],
                            $data['OPEN'],
                            $data['SD'],
                            $data['TP'],
                            $data['WIN'],
                            $data['LOSE'],
                            $data['HOLD'],
                            $data['CANCEL'],
                            $data['SPESIAL'],
                            $data['All'],
                        );
                	$i++;  
                }
                $sheet->fromArray($datasheetpo);
                
        	});

        	$users = User::select('name', 'nik')->where('id_territory', 'PRESALES')->where('name', '!=', 'PRESALES')->get();

        	foreach ($users as $user) {
        		$excel->sheet($user->name, function ($sheet) use ($request,$user) {
        
	                $sheet->mergeCells('A1:G1');

	                $sheet->row(1, function ($row) {
	                    $row->setFontFamily('Calibri');
	                    $row->setFontSize(12);
	                    $row->setAlignment('center');
	                    $row->setFontWeight('bold');
	                });

	                $sheet->row(1, array('Lead Register'));

	                $sheet->row(2, function ($row) {
	                    $row->setFontFamily('Calibri');
	                    $row->setFontSize(11);
	                    $row->setFontWeight('bold');
	                });

	                $win_presales = Sales2::join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
	                		->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
	                        ->join('users as presales', 'presales.nik', '=', 'sales_solution_design.nik')
	                        ->join('users as sales', 'sales.nik', '=', 'sales_lead_register.nik')
	                        ->select('sales_solution_design.lead_id', 'opp_name', 'deal_price', 'result', 'sales_lead_register.amount', 'tb_contact.brand_name',  'sales.name')
	                        ->where('sales_solution_design.nik',$user->nik)
	                        ->where('presales.id_company', '1')
	                        ->where('result', 'WIN')
	                        ->where('sales_lead_register.year', $request->type)
	                        ->get();

	                $sd_presales = Sales2::join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
	                		->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
	                        ->join('users as presales', 'presales.nik', '=', 'sales_solution_design.nik')
	                        ->join('users as sales', 'sales.nik', '=', 'sales_lead_register.nik')
	                        ->select('sales_solution_design.lead_id', 'opp_name', 'deal_price', 'result', 'sales_lead_register.amount', 'tb_contact.brand_name',  'sales.name')
	                        ->where('sales_solution_design.nik',$user->nik)
	                        ->where('presales.id_company', '1')
	                        ->where('result', 'SD')
	                        ->where('sales_lead_register.year', $request->type)
	                        ->get();


	                $tp_presales = Sales2::join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
	                		->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
	                        ->join('users as presales', 'presales.nik', '=', 'sales_solution_design.nik')
	                        ->join('users as sales', 'sales.nik', '=', 'sales_lead_register.nik')
	                        ->select('sales_solution_design.lead_id', 'opp_name', 'deal_price', 'result', 'sales_lead_register.amount', 'tb_contact.brand_name',  'sales.name')
	                        ->where('sales_solution_design.nik',$user->nik)
	                        ->where('presales.id_company', '1')
	                        ->where('result', 'TP')
	                        ->where('sales_lead_register.year', $request->type)
	                        ->get();


	                $open_presales = Sales2::join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
	                		->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
	                        ->join('users as presales', 'presales.nik', '=', 'sales_solution_design.nik')
	                        ->join('users as sales', 'sales.nik', '=', 'sales_lead_register.nik')
	                        ->select('sales_solution_design.lead_id', 'opp_name', 'deal_price', DB::raw("(CASE WHEN (result = 'OPEN') THEN 'INITIAL' WHEN (result = '') THEN 'OPEN' END) as results"), 'sales_lead_register.amount', 'tb_contact.brand_name',  'sales.name')
	                        ->where('sales_solution_design.nik',$user->nik)
	                        ->where('presales.id_company', '1')
	                        ->where('result', '')
	                        ->where('sales_lead_register.year', $request->type)
	                        ->get();


	                $initial_presales = Sales2::join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
	                		->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
	                        ->join('users as presales', 'presales.nik', '=', 'sales_solution_design.nik')
	                        ->join('users as sales', 'sales.nik', '=', 'sales_lead_register.nik')
	                        ->select('sales_solution_design.lead_id', 'opp_name', 'deal_price', DB::raw("(CASE WHEN (result = 'OPEN') THEN 'INITIAL' WHEN (result = '') THEN 'OPEN' END) as results"), 'sales_lead_register.amount', 'tb_contact.brand_name',  'sales.name')
	                        ->where('sales_solution_design.nik',$user->nik)
	                        ->where('presales.id_company', '1')
	                        ->where('result', 'OPEN')
	                        ->where('sales_lead_register.year', $request->type)
	                        ->get();


	                $lose_presales = Sales2::join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
	                		->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
	                        ->join('users as presales', 'presales.nik', '=', 'sales_solution_design.nik')
	                        ->join('users as sales', 'sales.nik', '=', 'sales_lead_register.nik')
	                        ->select('sales_solution_design.lead_id', 'opp_name', 'deal_price', 'result', 'sales_lead_register.amount', 'tb_contact.brand_name',  'sales.name')
	                        ->where('sales_solution_design.nik',$user->nik)
	                        ->where('presales.id_company', '1')
	                        ->where('result', 'LOSE')
	                        ->where('sales_lead_register.year', $request->type)
	                        ->get();

	                $hold_presales = Sales2::join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
	                		->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
	                        ->join('users as presales', 'presales.nik', '=', 'sales_solution_design.nik')
	                        ->join('users as sales', 'sales.nik', '=', 'sales_lead_register.nik')
	                        ->select('sales_solution_design.lead_id', 'opp_name', 'deal_price', 'result', 'sales_lead_register.amount', 'tb_contact.brand_name',  'sales.name')
	                        ->where('sales_solution_design.nik',$user->nik)
	                        ->where('presales.id_company', '1')
	                        ->where('result', 'HOLD')
	                        ->where('sales_lead_register.year', $request->type)
	                        ->get();

	                $cancel_presales = Sales2::join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
	                		->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
	                        ->join('users as presales', 'presales.nik', '=', 'sales_solution_design.nik')
	                        ->join('users as sales', 'sales.nik', '=', 'sales_lead_register.nik')
	                        ->select('sales_solution_design.lead_id', 'opp_name', 'deal_price', 'result', 'sales_lead_register.amount', 'tb_contact.brand_name',  'sales.name')
	                        ->where('sales_solution_design.nik',$user->nik)
	                        ->where('presales.id_company', '1')
	                        ->where('result', 'CANCEL')
	                        ->where('sales_lead_register.year', $request->type)
	                        ->get();

	                $spesial_presales = Sales2::join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
	                		->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
	                        ->join('users as presales', 'presales.nik', '=', 'sales_solution_design.nik')
	                        ->join('users as sales', 'sales.nik', '=', 'sales_lead_register.nik')
	                        ->select('sales_solution_design.lead_id', 'opp_name', 'deal_price', 'result', 'sales_lead_register.amount', 'tb_contact.brand_name',  'sales.name')
	                        ->where('sales_solution_design.nik',$user->nik)
	                        ->where('presales.id_company', '1')
	                        ->where('result', 'SPESIAL')
	                        ->where('sales_lead_register.year', $request->type)
	                        ->get();


	                    $datasheetpo = array();
	                    $datasheetpo[0] = array("No", "Lead Id", "Customer", "Opp Name", "Owner", "Amount", "Status");
	                    $i=1;

	                    foreach ($initial_presales as $data) {
		                    $datasheetpo[$i] = array($i,
                                    $data['lead_id'],
                                    $data['brand_name'],
                                    $data['opp_name'],
                                    $data['name'],
                                    number_format($data['amount'],2,",","."),
                                    $data['results'],
                                );
                        	$i++;  
	                    }


	                    foreach ($open_presales as $data) {
		                    $datasheetpo[$i] = array($i,
                                    $data['lead_id'],
                                    $data['brand_name'],
                                    $data['opp_name'],
                                    $data['name'],
                                    number_format($data['amount'],2,",","."),
                                    $data['results'],
                                );
                        	$i++;  
	                    }


	                    foreach ($sd_presales as $data) {
		                    $datasheetpo[$i] = array($i,
                                    $data['lead_id'],
                                    $data['brand_name'],
                                    $data['opp_name'],
                                    $data['name'],
                                    number_format($data['amount'],2,",","."),
                                    $data['result'],
                                );
                        	$i++;  
	                    }


	                    foreach ($tp_presales as $data) {
		                    $datasheetpo[$i] = array($i,
                                    $data['lead_id'],
                                    $data['brand_name'],
                                    $data['opp_name'],
                                    $data['name'],
                                    number_format($data['amount'],2,",","."),
                                    $data['result'],
                                );
                        	$i++;  
	                    }
	                    

	                    foreach ($win_presales as $data) {
		                    $datasheetpo[$i] = array($i,
                                    $data['lead_id'],
                                    $data['brand_name'],
                                    $data['opp_name'],
                                    $data['name'],
                                    number_format($data['deal_price'],2,",","."),
                                    $data['result'],
                                );
                        	$i++;  
	                    }

	                    foreach ($lose_presales as $data) {
	                    	$datasheetpo[$i] = array($i,
                                    $data['lead_id'],
                                    $data['brand_name'],
                                    $data['opp_name'],
                                    $data['name'],
                                    number_format($data['deal_price'],2,",","."),
                                    $data['result'],
                                );
                        	$i++; 
	                    }

	                    foreach ($hold_presales as $data) {
		                    $datasheetpo[$i] = array($i,
                                    $data['lead_id'],
                                    $data['brand_name'],
                                    $data['opp_name'],
                                    $data['name'],
                                    number_format($data['amount'],2,",","."),
                                    $data['result'],
                                );
                        	$i++;  
	                    }
	                    

	                    foreach ($cancel_presales as $data) {
		                    $datasheetpo[$i] = array($i,
                                    $data['lead_id'],
                                    $data['brand_name'],
                                    $data['opp_name'],
                                    $data['name'],
                                    number_format($data['deal_price'],2,",","."),
                                    $data['result'],
                                );
                        	$i++;  
	                    }

	                    foreach ($spesial_presales as $data) {
	                    	$datasheetpo[$i] = array($i,
                                    $data['lead_id'],
                                    $data['brand_name'],
                                    $data['opp_name'],
                                    $data['name'],
                                    number_format($data['deal_price'],2,",","."),
                                    $data['result'],
                                );
                        	$i++; 
	                    }

	                    $sheet->fromArray($datasheetpo);
                    
            	});
        	}

        })->export('xls');
    }

    public function filter_presales_each_year(Request $req)
    {
        return array("data" => Sales2::join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
            ->join('users as presales', 'presales.nik', '=', 'sales_solution_design.nik')
            ->join('users as sales', 'sales.nik', '=', 'sales_lead_register.nik')
            ->select('sales_solution_design.lead_id', 'opp_name', 'deal_price',DB::raw("(CASE WHEN (result = 'OPEN') THEN 'INITIAL' WHEN (result = '') THEN 'OPEN' WHEN (result = 'SD') THEN 'SD' WHEN (result = 'TP') THEN 'TP' WHEN (result = 'WIN') THEN 'WIN' WHEN( result = 'LOSE') THEN 'LOSE' WHEN( result = 'HOLD') THEN 'HOLD' WHEN( result = 'SPECIAL') THEN 'SPECIAL'END) as results"), 'sales_lead_register.amount', 'tb_contact.brand_name',  'sales.name')
            ->where('sales_solution_design.nik',$req->nik)
            ->where('presales.id_company', '1')
            ->where('sales_lead_register.year', $req->year)
            ->orderBy('result','desc')
            ->get()); 
    }

    public function getdatainitleadpresales(Request $request)
    {
       return array("data" => Sales2::join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
            ->join('users as presales', 'presales.nik', '=', 'sales_solution_design.nik')
            ->join('users as sales', 'sales.nik', '=', 'sales_lead_register.nik')
            ->select('sales_solution_design.lead_id', 'opp_name', 'deal_price', DB::raw('`result` AS `results`'), 'sales_lead_register.amount', 'tb_contact.brand_name',  'sales.name')
            ->where('sales_solution_design.nik',"1110492070")
            ->where('presales.id_company', '1')
            ->where('sales_lead_register.year', date("Y"))
            ->orderBy('result','desc')
            ->get());
    }

    public function getdatalead(Request $request)
    {

        return array("data" =>     
            Sales2::join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                            ->join('users as presales', 'presales.nik', '=', 'sales_solution_design.nik')
                            ->select('presales.name', 
                                DB::raw('COUNT(IF(`sales_lead_register`.`result` = "OPEN",1,NULL)) AS "INITIAL"'), 
                                DB::raw('COUNT(IF(`sales_lead_register`.`result` = "",1,NULL)) AS "OPEN"'), 
                                DB::raw('COUNT(IF(`sales_lead_register`.`result` = "SD",1,NULL)) AS "SD"'),
                                DB::raw('COUNT(IF(`sales_lead_register`.`result` = "TP",1,NULL)) AS "TP"'),
                                DB::raw('COUNT(IF(`sales_lead_register`.`result` = "WIN",1,NULL)) AS "WIN"'),
                                DB::raw('COUNT(IF(`sales_lead_register`.`result` = "LOSE",1,NULL)) AS "LOSE"'),
                                DB::raw('COUNT(IF(`sales_lead_register`.`result` = "HOLD",1,NULL)) AS "HOLD"'),
                                DB::raw('COUNT(IF(`sales_lead_register`.`result` = "CANCEL",1,NULL)) AS "CANCEL"'),
                                DB::raw('COUNT(IF(`sales_lead_register`.`result` = "SPESIAL",1,NULL)) AS "SPESIAL"'),
                                DB::raw('COUNT(*) AS `All`')
                            )
                            ->where('id_company','1')
                            ->where('year', date("Y"))
                            ->where('presales.nik', '!=', '1100492050')
                            ->groupBy('sales_solution_design.nik')
                            ->get());
    }

    public function getfiltersdpresales(Request $request) {

        $filter_sd = DB::table('sales_lead_register')
        				->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                        ->join('users', 'users.nik', '=', 'sales_solution_design.nik')
                        ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                        ->select(DB::raw('COUNT(sales_lead_register.lead_id) as leads'), DB::raw('SUM(sales_lead_register.amount) as amounts'), 'users.name', 'tb_company.code_company')
                        ->where('result', 'SD')
                        ->where('sales_lead_register.created_at', '>=', $request->start)
                        ->where('sales_lead_register.created_at', '<=', $request->end)
                        ->where('users.id_company', '1')

                        ->groupBy('sales_solution_design.nik')
                        ->orderBy('amounts', 'desc')
                        ->get();

        return $filter_sd;

    }

    public function getfiltertppresales(Request $request) {

        $filter_tp = DB::table('sales_lead_register')
        				->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                        ->join('users', 'users.nik', '=', 'sales_solution_design.nik')
                        ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                        ->select(DB::raw('COUNT(sales_lead_register.lead_id) as leads'), DB::raw('SUM(sales_lead_register.amount) as amounts'), 'users.name', 'tb_company.code_company')
                        ->where('result', 'TP')
                        ->where('sales_lead_register.created_at', '>=', $request->start)
                        ->where('sales_lead_register.created_at', '<=', $request->end)
                        ->where('users.id_company', '1')
                        ->where('sales_solution_design.nik', '!=', '1100492050')
                        ->groupBy('sales_solution_design.nik')
                        ->orderBy('amounts', 'desc')
                        ->get();

        return $filter_tp;

    }

    public function getfilterwinpresales(Request $request) {

        $filter_win = DB::table('sales_lead_register')
        				->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                        ->join('users', 'users.nik', '=', 'sales_solution_design.nik')
                        ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                        ->select(DB::raw('COUNT(sales_lead_register.lead_id) as leads'), DB::raw('SUM(sales_lead_register.amount) as amounts'), 'users.name', 'tb_company.code_company')
                        ->where('result', 'WIN')
                        ->where('sales_lead_register.created_at', '>=', $request->start)
                        ->where('sales_lead_register.created_at', '<=', $request->end)
                        ->where('users.id_company', '1')
                        ->where('sales_solution_design.nik', '!=', '1100492050')
                        ->groupBy('sales_solution_design.nik')
                        ->orderBy('amounts', 'desc')
                        ->get();

        return $filter_win;

    }

    public function getfilterlosepresales(Request $request) {

        $filter_lose = DB::table('sales_lead_register')
        				->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                        ->join('users', 'users.nik', '=', 'sales_solution_design.nik')
                        ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                        ->select(DB::raw('COUNT(sales_lead_register.lead_id) as leads'), DB::raw('SUM(sales_lead_register.amount) as amounts'), 'users.name', 'tb_company.code_company')
                        ->where('result', 'LOSE')
                        ->where('sales_lead_register.created_at', '>=', $request->start)
                        ->where('sales_lead_register.created_at', '<=', $request->end)
                        ->where('users.id_company', '1')
                        ->where('sales_solution_design.nik', '!=', '1100492050')
                        ->groupBy('sales_solution_design.nik')
                        ->orderBy('amounts', 'desc')
                        ->get();

        return $filter_lose;

    }

    public function getfiltersdyearpresales(Request $request) {

        $filter_sd = DB::table('sales_lead_register')
        				->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                        ->join('users', 'users.nik', '=', 'sales_solution_design.nik')
                        ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                        ->select(DB::raw('COUNT(sales_lead_register.lead_id) as leads'), DB::raw('SUM(sales_lead_register.amount) as amounts'), 'users.name', 'tb_company.code_company')
                        ->where('result', 'SD')
                        ->where('year', $request->data)
                        ->where('users.id_company', '1')
                        ->where('sales_solution_design.nik', '!=', '1100492050')
                        ->groupBy('sales_solution_design.nik')
                        ->orderBy('amounts', 'desc')
                        ->get();

        return $filter_sd;

    }

    public function getfiltertpyearpresales(Request $request) {

        $filter_tp = DB::table('sales_lead_register')
        				->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                        ->join('users', 'users.nik', '=', 'sales_solution_design.nik')
                        ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                        ->select(DB::raw('COUNT(sales_lead_register.lead_id) as leads'), DB::raw('SUM(sales_lead_register.amount) as amounts'), 'users.name', 'tb_company.code_company')
                        ->where('result', 'TP')
                        ->where('year', $request->data)
                        ->where('users.id_company', '1')
                        ->where('sales_solution_design.nik', '!=', '1100492050')
                        ->groupBy('sales_solution_design.nik')
                        ->orderBy('amounts', 'desc')
                        ->get();

        return $filter_tp;

    }

    public function getfilterwinyearpresales(Request $request) {

        $filter_win = DB::table('sales_lead_register')
        				->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                        ->join('users', 'users.nik', '=', 'sales_solution_design.nik')
                        ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                        ->select(DB::raw('COUNT(sales_lead_register.lead_id) as leads'), DB::raw('SUM(sales_lead_register.amount) as amounts'), 'users.name', 'tb_company.code_company')
                        ->where('result', 'WIN')
                        ->where('year', $request->data)
                        ->where('users.id_company', '1')
                        ->where('sales_solution_design.nik', '!=', '1100492050')
                        ->groupBy('sales_solution_design.nik')
                        ->orderBy('amounts', 'desc')
                        ->get();

        return $filter_win;

    }

    public function getfilterloseyearpresales(Request $request) {

        $filter_lose = DB::table('sales_lead_register')
        				->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                        ->join('users', 'users.nik', '=', 'sales_solution_design.nik')
                        ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                        ->select(DB::raw('COUNT(sales_lead_register.lead_id) as leads'), DB::raw('SUM(sales_lead_register.amount) as amounts'), 'users.name', 'tb_company.code_company')
                        ->where('result', 'LOSE')
                        ->where('year', $request->data)
                        ->where('users.id_company', '1')
                        ->where('sales_solution_design.nik', '!=', '1100492050')
                        ->groupBy('sales_solution_design.nik')
                        ->orderBy('amounts', 'desc')
                        ->get();

        return $filter_lose;

    }

    public function getfilteryearpresales(Request $req){
        Sales2::join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
            ->join('users as presales', 'presales.nik', '=', 'sales_solution_design.nik')
            ->join('users as sales', 'sales.nik', '=', 'sales_lead_register.nik')
            ->select('sales_solution_design.lead_id', 'opp_name', 'deal_price', 'result', 'sales_lead_register.amount', 'tb_contact.brand_name',  'sales.name')
            ->where('sales_solution_design.nik',$req->nik)
            ->where('presales.id_company', '1')
            ->where('sales_solution_design.nik', '!=', '1100492050')
            ->whereYear('sales_solution_design.created_at', $req->year)
            ->orderBy('result','desc')
            ->get();
    }

    public function filter_lead_presales(Request $request)
    {
    	$lead_presales = DB::table('sales_lead_register')->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
        					->join('users as presales', 'presales.nik', '=', 'sales_solution_design.nik')
        					->select('presales.name', 
        						DB::raw('COUNT(IF(`sales_lead_register`.`result` = "OPEN",1,NULL)) AS "INITIAL"'), 
        						DB::raw('COUNT(IF(`sales_lead_register`.`result` = "",1,NULL)) AS "OPEN"'), 
        						DB::raw('COUNT(IF(`sales_lead_register`.`result` = "SD",1,NULL)) AS "SD"'),
        						DB::raw('COUNT(IF(`sales_lead_register`.`result` = "TP",1,NULL)) AS "TP"'),
        						DB::raw('COUNT(IF(`sales_lead_register`.`result` = "WIN",1,NULL)) AS "WIN"'),
        						DB::raw('COUNT(IF(`sales_lead_register`.`result` = "LOSE",1,NULL)) AS "LOSE"'),
        						DB::raw('COUNT(IF(`sales_lead_register`.`result` = "HOLD",1,NULL)) AS "HOLD"'),
        						DB::raw('COUNT(IF(`sales_lead_register`.`result` = "CANCEL",1,NULL)) AS "CANCEL"'),
        						DB::raw('COUNT(IF(`sales_lead_register`.`result` = "SPESIAL",1,NULL)) AS "SPESIAL"'),
        						DB::raw('COUNT(*) AS `All`')
        					)
        					->where('id_company','1')
        					->where('sales_solution_design.nik', '!=', '1100492050')
        					->where('year', $request->data)
        					->groupBy('sales_solution_design.nik')
        					->get();

      	return $lead_presales;
    }


    public function getCustomerbyDate(Request $request)
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;

        if ($request->type == 'customer') {
                $id_customer = DB::table('tb_contact')
                            ->where('brand_name',$request->customer)
                            ->value('id_customer');

                if(Auth::User()->id_division == 'SALES'){
                $customer = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name', 'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name', 'sales_lead_register.closing_date')
                    ->where('sales_lead_register.id_customer', $id_customer)
                    ->where('sales_lead_register.created_at', '>=', $request->start)
                    ->where('sales_lead_register.created_at', '<=', $request->end)
                    ->where('sales_lead_register.nik', $nik)
                    ->get();
                return $customer;
                } elseif(Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL PRESALES') {
                $customer = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name', 'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name', 'sales_lead_register.closing_date')
                    ->where('sales_lead_register.id_customer', $id_customer)
                    ->where('sales_lead_register.created_at', '>=', $request->start)
                    ->where('sales_lead_register.created_at', '<=', $request->end)
                    ->get();
                return $customer;
                } 
            } 

        if ($request->type == 'sales') {
                $niks = DB::table('users')
                    ->where('name',$request->customer)
                    ->value('nik');

                 if (Auth::User()->id_division == 'SALES') {
                 	$sales = DB::table('sales_lead_register')
	                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
	                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
	                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name', 'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name', 'sales_lead_register.closing_date')
	                    ->where('sales_lead_register.nik', $niks)
	                    ->where('sales_lead_register.created_at', '>=', $request->start)
	                    ->where('sales_lead_register.created_at', '<=', $request->end)
	                    ->get();
	                return $sales;
                 } elseif (Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL PRESALES') {
	                $sales = DB::table('sales_lead_register')
	                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
	                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
	                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name', 'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name', 'sales_lead_register.closing_date')
	                    ->where('sales_lead_register.nik', $niks)
	                    ->where('sales_lead_register.created_at', '>=', $request->start)
	                    ->where('sales_lead_register.created_at', '<=', $request->end)
	                    ->get();
	                return $sales;
                 }
            } 

        if ($request->type == 'territory') {
                $terr = DB::table('tb_territory')
                    ->where('name_territory',$request->customer)
                    ->value('id_territory');
                
                if(Auth::User()->id_division == 'SALES' && Auth::User()->id_territory == $ter){
                $territory = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name', 'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name', 'sales_lead_register.closing_date')
                    ->where('users.id_territory', $terr)
                    ->where('users.id_company','!=','2')
                    ->where('sales_lead_register.created_at', '>=', $request->start)
                    ->where('sales_lead_register.created_at', '<=', $request->end)
                    ->get();
                return $territory;
                } elseif(Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL PRESALES') {
                $territory = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name', 'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name', 'sales_lead_register.closing_date')
                    ->where('users.id_territory', $terr)
                    ->where('users.id_company','!=','2')
                    ->where('sales_lead_register.created_at', '>=', $request->start)
                    ->where('sales_lead_register.created_at', '<=', $request->end)
                    ->get();
                return $territory;
                }
            }

        if ($request->type == 'status') {
                $res = DB::table('sales_lead_register')
                    ->where('result',$request->customer)
                    ->value('result');

                if(Auth::User()->id_division == 'SALES'){
                    if ($res == 'OPEN') {
                        $status = DB::table('sales_lead_register')
                            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                            ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                            ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                            'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name', 'sales_lead_register.closing_date')
                            ->where('result', '')
                            ->where('sales_lead_register.created_at', '>=', $request->start)
                            ->where('sales_lead_register.created_at', '<=', $request->end)
                            ->where('sales_lead_register.nik', $nik)
                            ->get();
                    } else {
                        $status = DB::table('sales_lead_register')
                            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                            ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                            ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                            'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name', 'sales_lead_register.closing_date')
                            ->where('result', $res)
                            ->where('sales_lead_register.created_at', '>=', $request->start)
                            ->where('sales_lead_register.created_at', '<=', $request->end)
                            ->where('sales_lead_register.nik', $nik)
                            ->get();
                    }
                return $status;
                } elseif(Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL PRESALES') {
                    if ($res == 'OPEN') {
                        $status = DB::table('sales_lead_register')
                            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                            ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                            ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                            'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name', 'sales_lead_register.closing_date')
                            ->where('result', '')
                            ->where('sales_lead_register.created_at', '>=', $request->start)
                            ->where('sales_lead_register.created_at', '<=', $request->end)
                            ->get();
                    } else {
                        $status = DB::table('sales_lead_register')
                            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                            ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                            ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                            'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name', 'sales_lead_register.closing_date')
                            ->where('result', $res)
                            ->where('sales_lead_register.created_at', '>=', $request->start)
                            ->where('sales_lead_register.created_at', '<=', $request->end)
                            ->get();
                    }
                return $status;
                }
            } 

        if ($request->type == 'presales') {
                $pre = DB::table('users')
                    ->where('name',$request->customer)
                    ->value('nik');

                if(Auth::User()->id_division == 'SALES'){
                $presales = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->join('sales_solution_design', 'sales_lead_register.lead_id', '=', 'sales_solution_design.lead_id')
                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name', 'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name', 'sales_lead_register.closing_date')
                    ->where('sales_solution_design.nik', $pre)
                    ->where('sales_lead_register.created_at', '>=', $request->start)
                    ->where('sales_lead_register.created_at', '<=', $request->end)
                    ->where('sales_lead_register.nik', $nik)
                    ->get();
                return $presales;
                } elseif(Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL PRESALES') {
                $presales = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->join('sales_solution_design', 'sales_lead_register.lead_id', '=', 'sales_solution_design.lead_id')
                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name', 'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name', 'sales_lead_register.closing_date')
                    ->where('sales_solution_design.nik', $pre)
                    ->where('sales_lead_register.created_at', '>=', $request->start)
                    ->where('sales_lead_register.created_at', '<=', $request->end)
                    ->get();
                return $presales;
                }
            }

        if ($request->type == 'priority') {
                $prio = DB::table('sales_solution_design')
                    ->where('priority',$request->customer)
                    ->value('priority');

                if(Auth::User()->id_division == 'SALES'){
                $priority = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->join('sales_solution_design', 'sales_lead_register.lead_id', '=', 'sales_solution_design.lead_id')
                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name', 'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name', 'sales_lead_register.closing_date')
                    ->where('sales_solution_design.priority', $prio)
                    ->where('sales_lead_register.created_at', '>=', $request->start)
                    ->where('sales_lead_register.created_at', '<=', $request->end)
                    ->where('sales_lead_register.nik', $nik)
                    ->get();
                return $priority;
                } elseif(Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL PRESALES') {
                $priority = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->join('sales_solution_design', 'sales_lead_register.lead_id', '=', 'sales_solution_design.lead_id')
                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name', 'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name', 'sales_lead_register.closing_date')
                    ->where('sales_solution_design.priority', $prio)
                    ->where('sales_lead_register.created_at', '>=', $request->start)
                    ->where('sales_lead_register.created_at', '<=', $request->end)
                    ->get();
                return $priority;
                }
            }

        if ($request->type == 'win') {
                if ($request->type == 'win') {
                    $win = DB::table('sales_tender_process')
                        ->where('win_prob',$request->customer)
                        ->value('win_prob');

                if(Auth::User()->id_division == 'SALES'){
                    if($win == 'LOW'){
                        $win_prob = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                        ->join('sales_tender_process', 'sales_lead_register.lead_id', '=', 'sales_tender_process.lead_id')
                        ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name', 'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name', 'sales_lead_register.closing_date')
                        ->where('sales_tender_process.win_prob', 'LOW')
                        ->where('sales_lead_register.created_at', '>=', $request->start)
                        ->where('sales_lead_register.created_at', '<=', $request->end)
                        ->where('sales_lead_register.nik', $nik)
                        ->get();
                        
                        return $win_prob;

                    }elseif($win == 'MEDIUM'){
                        $win_prob = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                        ->join('sales_tender_process', 'sales_lead_register.lead_id', '=', 'sales_tender_process.lead_id')
                        ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name', 'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name', 'sales_lead_register.closing_date')
                        ->where('sales_tender_process.win_prob', 'MEDIUM')
                        ->where('sales_lead_register.created_at', '>=', $request->start)
                        ->where('sales_lead_register.created_at', '<=', $request->end)
                        ->where('sales_lead_register.nik', $nik)
                        ->get();

                        return $win_prob;

                    }elseif($win == 'HIGH'){
                        $win_prob = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                        ->join('sales_tender_process', 'sales_lead_register.lead_id', '=', 'sales_tender_process.lead_id')
                        ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name', 'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name', 'sales_lead_register.closing_date')
                        ->where('sales_tender_process.win_prob', 'HIGH')
                        ->where('sales_lead_register.created_at', '>=', $request->start)
                        ->where('sales_lead_register.created_at', '<=', $request->end)
                        ->where('sales_lead_register.nik', $nik)
                        ->get();
                        
                        return $win_prob;

                    }
                } elseif(Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL PRESALES') {
                    if($win == 'LOW'){
                        $win_prob = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                        ->join('sales_tender_process', 'sales_lead_register.lead_id', '=', 'sales_tender_process.lead_id')
                        ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name', 'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name', 'sales_lead_register.closing_date')
                        ->where('sales_tender_process.win_prob', 'LOW')
                        ->where('sales_lead_register.created_at', '>=', $request->start)
                        ->where('sales_lead_register.created_at', '<=', $request->end)
                        ->get();
                        
                        return $win_prob;

                    }elseif($win == 'MEDIUM'){
                        $win_prob = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                        ->join('sales_tender_process', 'sales_lead_register.lead_id', '=', 'sales_tender_process.lead_id')
                        ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name', 'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name', 'sales_lead_register.closing_date')
                        ->where('sales_tender_process.win_prob', 'MEDIUM')
                        ->where('sales_lead_register.created_at', '>=', $request->start)
                        ->where('sales_lead_register.created_at', '<=', $request->end)
                        ->get();

                        return $win_prob;

                    }elseif($win == 'HIGH'){
                        $win_prob = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                        ->join('sales_tender_process', 'sales_lead_register.lead_id', '=', 'sales_tender_process.lead_id')
                        ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name', 'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name', 'sales_lead_register.closing_date')
                        ->where('sales_tender_process.win_prob', 'HIGH')
                        ->where('sales_lead_register.created_at', '>=', $request->start)
                        ->where('sales_lead_register.created_at', '<=', $request->end)
                        ->get();
                        
                        return $win_prob;

                    }
                }

               }
           }
    }

    public function getCustomerbyDate2(Request $request)
    {
        $nik = Auth::User()->nik;

        if ($request->type == 'customer') {
                $id_customer = DB::table('tb_contact')
                            ->where('brand_name',$request->customer)
                            ->value('id_customer');

                if(Auth::User()->id_division == 'SALES'){
	                $report = DB::table('sales_lead_register')
	                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
	                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
	                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name', 'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
	                    ->where('sales_lead_register.id_customer', $id_customer)
	                    ->where('sales_lead_register.created_at', '>=', $request->start)
	                    ->where('sales_lead_register.created_at', '<=', $request->end)
	                    ->where('sales_lead_register.nik', $nik)
	                    ->get();
                } elseif(Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL PRESALES') {
	                $report = DB::table('sales_lead_register')
	                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
	                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
	                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name', 'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
	                    ->where('sales_lead_register.id_customer', $id_customer)
	                    ->where('sales_lead_register.created_at', '>=', $request->start)
	                    ->where('sales_lead_register.created_at', '<=', $request->end)
	                    ->get();
                } 
            } 

        if ($request->type == 'sales') {
                $niks = DB::table('users')
                    ->where('name',$request->customer)
                    ->value('nik');

                 if (Auth::User()->id_division == 'SALES') {
                 	$report = DB::table('sales_lead_register')
	                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
	                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
	                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name', 'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
	                    ->where('sales_lead_register.nik', $niks)
	                    ->where('sales_lead_register.created_at', '>=', $request->start)
	                    ->where('sales_lead_register.created_at', '<=', $request->end)
                        // ->where('sales_lead_register.nik', $nik)
	                    ->get();
                 } elseif (Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL PRESALES') {
	                $report = DB::table('sales_lead_register')
	                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
	                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
	                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name', 'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
	                    ->where('sales_lead_register.nik', $niks)
	                    ->where('sales_lead_register.created_at', '>=', $request->start)
	                    ->where('sales_lead_register.created_at', '<=', $request->end)
                        // ->where('sales_lead_register.nik', $nik)
	                    ->get();
                 }
            } 

        if ($request->type == 'territory') {
                $ter = DB::table('tb_territory')
                    ->where('name_territory',$request->customer)
                    ->value('id_territory');
                
                if(Auth::User()->id_division == 'SALES'){
	                $report = DB::table('sales_lead_register')
	                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
	                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
	                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name', 'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
	                    ->where('users.id_territory', $ter)
	                    ->where('sales_lead_register.created_at', '>=', $request->start)
	                    ->where('sales_lead_register.created_at', '<=', $request->end)
	                    ->where('sales_lead_register.nik', $nik)
	                    ->get();
                } elseif(Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL PRESALES') {
	                $report = DB::table('sales_lead_register')
	                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
	                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
	                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name', 'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
	                    ->where('users.id_territory', $ter)
	                    ->where('sales_lead_register.created_at', '>=', $request->start)
	                    ->where('sales_lead_register.created_at', '<=', $request->end)
	                    ->get();
                }
            }

        if ($request->type == 'status') {
                $res = DB::table('sales_lead_register')
                    ->where('result',$request->customer)
                    ->value('result');

                if(Auth::User()->id_division == 'SALES'){
                    if ($res == 'OPEN') {
                        $report = DB::table('sales_lead_register')
                            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                            ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                            ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                            'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
                            ->where('result', '')
                            ->where('sales_lead_register.created_at', '>=', $request->start)
                            ->where('sales_lead_register.created_at', '<=', $request->end)
                            ->where('sales_lead_register.nik', $nik)
                            ->get();
                    } else {
                        $report = DB::table('sales_lead_register')
                            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                            ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                            ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                            'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
                            ->where('result', $res)
                            ->where('sales_lead_register.created_at', '>=', $request->start)
                            ->where('sales_lead_register.created_at', '<=', $request->end)
                            ->where('sales_lead_register.nik', $nik)
                            ->get();
                    }
                } elseif(Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL PRESALES') {
                    if ($res == 'OPEN') {
                        $report = DB::table('sales_lead_register')
                            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                            ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                            ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                            'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
                            ->where('result', '')
                            ->where('sales_lead_register.created_at', '>=', $request->start)
                            ->where('sales_lead_register.created_at', '<=', $request->end)
                            ->get();
                    } else {
                        $report = DB::table('sales_lead_register')
                            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                            ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                            ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                            'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
                            ->where('result', $res)
                            ->where('sales_lead_register.created_at', '>=', $request->start)
                            ->where('sales_lead_register.created_at', '<=', $request->end)
                            ->get();
                    }
                }
            } 

        if ($request->type == 'presales') {
                $pre = DB::table('users')
                    ->where('name',$request->customer)
                    ->value('nik');

                if(Auth::User()->id_division == 'SALES'){
	                $report = DB::table('sales_lead_register')
	                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
	                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
	                    ->join('sales_solution_design', 'sales_lead_register.lead_id', '=', 'sales_solution_design.lead_id')
	                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name', 'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
	                    ->where('sales_solution_design.nik', $pre)
	                    ->where('sales_lead_register.created_at', '>=', $request->start)
	                    ->where('sales_lead_register.created_at', '<=', $request->end)
	                    ->where('sales_lead_register.nik', $nik)
	                    ->get();
                } elseif(Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL PRESALES') {
	                $report = DB::table('sales_lead_register')
	                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
	                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
	                    ->join('sales_solution_design', 'sales_lead_register.lead_id', '=', 'sales_solution_design.lead_id')
	                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name', 'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
	                    ->where('sales_solution_design.nik', $pre)
	                    ->where('sales_lead_register.created_at', '>=', $request->start)
	                    ->where('sales_lead_register.created_at', '<=', $request->end)
	                    ->get();
                }
            }

        if ($request->type == 'priority') {
                $prio = DB::table('sales_solution_design')
                    ->where('priority',$request->customer)
                    ->value('priority');

                if(Auth::User()->id_division == 'SALES'){
	                $report = DB::table('sales_lead_register')
	                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
	                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
	                    ->join('sales_solution_design', 'sales_lead_register.lead_id', '=', 'sales_solution_design.lead_id')
	                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name', 'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
	                    ->where('sales_solution_design.priority', $prio)
	                    ->where('sales_lead_register.created_at', '>=', $request->start)
	                    ->where('sales_lead_register.created_at', '<=', $request->end)
	                    ->where('sales_lead_register.nik', $nik)
	                    ->get();
                } elseif(Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL PRESALES') {
	                $report = DB::table('sales_lead_register')
	                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
	                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
	                    ->join('sales_solution_design', 'sales_lead_register.lead_id', '=', 'sales_solution_design.lead_id')
	                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name', 'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
	                    ->where('sales_solution_design.priority', $prio)
	                    ->where('sales_lead_register.created_at', '>=', $request->start)
	                    ->where('sales_lead_register.created_at', '<=', $request->end)
	                    ->get();
                }
            }

        if ($request->type == 'win') {
                if ($request->type == 'win') {
                    $win = DB::table('sales_tender_process')
                        ->where('win_prob',$request->customer)
                        ->value('win_prob');

                if(Auth::User()->id_division == 'SALES'){
                    if($win == 'LOW'){
                        $report = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                        ->join('sales_tender_process', 'sales_lead_register.lead_id', '=', 'sales_tender_process.lead_id')
                        ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name', 'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
                        ->where('sales_tender_process.win_prob', 'LOW')
                        ->where('sales_lead_register.created_at', '>=', $request->start)
                        ->where('sales_lead_register.created_at', '<=', $request->end)
                        ->where('sales_lead_register.nik', $nik)
                        ->get();
                    }elseif($win == 'MEDIUM'){
                        $report = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                        ->join('sales_tender_process', 'sales_lead_register.lead_id', '=', 'sales_tender_process.lead_id')
                        ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name', 'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
                        ->where('sales_tender_process.win_prob', 'MEDIUM')
                        ->where('sales_lead_register.created_at', '>=', $request->start)
                        ->where('sales_lead_register.created_at', '<=', $request->end)
                        ->where('sales_lead_register.nik', $nik)
                        ->get();
                    }elseif($win == 'HIGH'){
                        $report = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                        ->join('sales_tender_process', 'sales_lead_register.lead_id', '=', 'sales_tender_process.lead_id')
                        ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name', 'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
                        ->where('sales_tender_process.win_prob', 'HIGH')
                        ->where('sales_lead_register.created_at', '>=', $request->start)
                        ->where('sales_lead_register.created_at', '<=', $request->end)
                        ->where('sales_lead_register.nik', $nik)
                        ->get();
                    }
                } elseif(Auth::User()->id_position == 'DIRECTOR' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL PRESALES') {
                    if($win == 'LOW'){
                        $report = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                        ->join('sales_tender_process', 'sales_lead_register.lead_id', '=', 'sales_tender_process.lead_id')
                        ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name', 'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
                        ->where('sales_tender_process.win_prob', 'LOW')
                        ->where('sales_lead_register.created_at', '>=', $request->start)
                        ->where('sales_lead_register.created_at', '<=', $request->end)
                        ->get();
                    }elseif($win == 'MEDIUM'){
                        $report = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                        ->join('sales_tender_process', 'sales_lead_register.lead_id', '=', 'sales_tender_process.lead_id')
                        ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name', 'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
                        ->where('sales_tender_process.win_prob', 'MEDIUM')
                        ->where('sales_lead_register.created_at', '>=', $request->start)
                        ->where('sales_lead_register.created_at', '<=', $request->end)
                        ->get();
                    }elseif($win == 'HIGH'){
                        $report = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                        ->join('sales_tender_process', 'sales_lead_register.lead_id', '=', 'sales_tender_process.lead_id')
                        ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name', 'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name')
                        ->where('sales_tender_process.win_prob', 'HIGH')
                        ->where('sales_lead_register.created_at', '>=', $request->start)
                        ->where('sales_lead_register.created_at', '<=', $request->end)
                        ->get();
                    }
                }

               }
           }

        $pdf = PDF::loadView('report.report_range_pdf', compact('report'));
        return $pdf->download('report'.date("d-m-Y").'.pdf');
    }

    public function report_record_auth(Request $request){
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        $notifClaim = '';
        
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

        return view('report/record_authentication', compact('notif', 'notifOpen', 'notifsd', 'notiftp', 'notifClaim'));
    
    }

    public function get_auth_login(Request $request){

        $getlogin = array("data" => HistoryAuth::join('users','users.nik','=','tb_history_auth.nik')
                    ->select('name','email','datetime','ip_address')
                    ->where('information','Log In')
                    ->whereDate('datetime', Carbon::today())
                    ->orderBy('datetime','DESC')->get());

        return $getlogin;

    }

    public function get_auth_login_users(Request $request){

        if (Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' || Auth::User()->id_division == 'HR' && Auth::User()->id_position == 'HR MANAGER' || Auth::User()->id_position == 'DIRECTOR')
        {
            $getlogin = array("data" => HistoryAuth::join('users','users.nik','=','tb_history_auth.nik')
                    ->select('name','email','datetime','ip_address')
                    ->where('information','Log In')
                    ->orderBy('datetime','DESC')->get());
        }else{
            $getlogin = array("data" => HistoryAuth::join('users','users.nik','=','tb_history_auth.nik')
                    ->select('name','email','datetime','ip_address')
                    ->where('information','Log In')
                    ->where('tb_history_auth.nik',Auth::User()->nik)
                    ->orderBy('datetime','DESC')->get());
        }

        

        return $getlogin;

    }

    public function get_auth_logout(Request $request){

        $getlogout = array("data" => HistoryAuth::join('users','users.nik','=','tb_history_auth.nik')
                    ->select('name','email','datetime','ip_address')
                    ->where('information','Log Out')
                    ->whereDate('datetime', Carbon::today())
                    ->orderBy('datetime','DESC')->get());

        return $getlogout;

    }

    public function getFilterRecordAuth(Request $request){

        if ($request->TagsPersona == "") {
            $getlogin = array("data" => HistoryAuth::join('users','users.nik','=','tb_history_auth.nik')
                    ->select('name','email','datetime','ip_address')
                    ->where('information','Log In')
                    ->where('datetime', '>=', $request->start_date)
                    ->where('datetime', '<=', $request->end_date)
                    ->where('tb_history_auth.nik',Auth::User()->nik)
                    ->orderBy('datetime','DESC')->get());

        }else{
            if ($request->TagsPersona == ["2"]) {
                $getlogin = array("data" => HistoryAuth::join('users','users.nik','=','tb_history_auth.nik')
                    ->select('name','email','datetime','ip_address')
                    ->where('information','Log In')
                    ->where('datetime', '>=', $request->start_date)
                    ->where('datetime', '<=', $request->end_date)
                    ->orderBy('datetime','DESC')->get());
            }else{
                $getlogin = array("data" => HistoryAuth::join('users','users.nik','=','tb_history_auth.nik')
                    ->select('name','email','datetime','ip_address')
                    ->where('information','Log In')
                    ->whereIn('tb_history_auth.nik',$request->TagsPersona)
                    ->where('datetime', '>=', $request->start_date)
                    ->where('datetime', '<=', $request->end_date)
                    ->orderBy('datetime','DESC')->get());
            }
            
        }

        return $getlogin;

    }

    public function report_product_index()
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        $territory_loop = DB::table("tb_territory")
            ->select("id_territory","code_ter")
            ->where('id_territory','like','TERRITORY%')
            ->where('id_territory','!=','TERRITORY 6')
            // ->orWhere('id_territory','=','OPERATION')
            ->get();

        if ($ter != null) {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();

            $notifc = count($notif);
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $notif = DB::table('sales_lead_register')
            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->select('sales_lead_register.opp_name','sales_solution_design.nik')
            ->where('result','')
            ->orderBy('sales_lead_register.created_at','desc')
            ->get();

            $notifc = count($notif);
        }else{
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();

            $notifc = count($notif);
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

        return view('report/report_product', compact('notifc','notif','notifOpen','notifsd','notiftp','notifc','territory_loop'));
    
    }

    public function getreportproduct(Request $request)
    {
        $reportproduct = array("data" => ProductTagRelation::join('tb_product_tag','tb_product_tag.id','=','tb_product_tag_relation.id_product_tag')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_product_tag_relation.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->join('tb_territory','tb_territory.id_territory','=','users.id_territory')
                    ->select('tb_product_tag.name_product',
                        DB::raw("SUM(price) as total_price"),
                        DB::raw("COUNT(tb_product_tag_relation.lead_id) as total_lead"),
                        DB::raw("count(case when `users`.`id_territory` = 'TERRITORY 1' then 0 end) as countTer1"),
                        DB::raw("count(case when `users`.`id_territory` = 'TERRITORY 2' then 0 end) as countTer2"),
                        DB::raw("count(case when `users`.`id_territory` = 'TERRITORY 3' then 0 end) as countTer3"),
                        DB::raw("count(case when `users`.`id_territory` = 'TERRITORY 4' then 0 end) as countTer4"),
                        DB::raw("count(case when `users`.`id_territory` = 'TERRITORY 5' then 0 end) as countTer5"),
                        DB::raw("SUM(IF(`users`.`id_territory`='TERRITORY 1',price,0)) AS ter1_price"),
                        DB::raw("SUM(IF(`users`.`id_territory`='TERRITORY 2',price,0)) AS ter2_price"),
                        DB::raw("SUM(IF(`users`.`id_territory`='TERRITORY 3',price,0)) AS ter3_price"),
                        DB::raw("SUM(IF(`users`.`id_territory`='TERRITORY 4',price,0)) AS ter4_price"),
                        DB::raw("SUM(IF(`users`.`id_territory`='TERRITORY 5',price,0)) AS ter5_price")
                    )
                    ->groupBy('tb_product_tag.name_product')
                    // ->groupBy('tb_product_tag_relation.id_product_tag')
                    ->get());

        return $reportproduct;
    }
}