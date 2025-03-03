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
use App\solution_design;

use Excel;

use Validator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder;

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

        $spreadsheet = new Spreadsheet();

        $prSheet = new Worksheet($spreadsheet,'Report Lead Register');
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
        $sheet->setCellValue('A1','Report Lead Register');

        $headerStyle = $normalStyle;
        $headerStyle['font']['bold'] = true;
        $sheet->getStyle('A2:H2')->applyFromArray($headerStyle);;

        $headerContent = ["NO", "LEAD ID", "CUSTOMER LEGAL NAME", "OPTY NAME", "CREATE DATE", "OWNER", "AMOUNT", "STATUS"];
        $sheet->fromArray($headerContent,NULL,'A2');

        $year = date('Y');

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
                    ->select(
                        'sales_lead_register.lead_id', 
                        'tb_contact.customer_legal_name', 
                        'sales_lead_register.opp_name',
                        DB::raw('SUBSTRING(`sales_lead_register`.`created_at`,1,10) AS `created_at_formated`'),
                        'users.name', 
                        'sales_lead_register.amount',
                        'sales_lead_register.result'
                    )
                    ->where('year',$year)
                    ->orderBy('sales_lead_register.created_at','DESC')
                    ->get();
        }else if ($div == 'SALES' && Auth::User()->id_company == '1') {
            $datas = Sales2::join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id', 'tb_contact.customer_legal_name', 'sales_lead_register.opp_name',DB::raw('SUBSTRING(`sales_lead_register`.`created_at`,1,10) AS `created_at_formated`'), 'users.name', 'sales_lead_register.amount',
                        'sales_lead_register.result')
                    ->where('id_territory', $ter)
                    ->where('year',$year)
                    ->orderBy('sales_lead_register.created_at','DESC')
                    ->get();
        }else{
            $datas = Sales2::join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id', 'tb_contact.customer_legal_name', 'sales_lead_register.opp_name',DB::raw('SUBSTRING(`sales_lead_register`.`created_at`,1,10) AS `created_at_formated`'), 'users.name', 'sales_lead_register.amount',
                        'sales_lead_register.result')
                    ->where('id_territory', $ter)
                    ->where('year',$year)
                    ->orderBy('sales_lead_register.created_at','DESC')
                    ->get();
        }

        foreach ($datas as $key => $eachLead) {
            $eachLead->amount = number_format($eachLead->amount,2,",",".");
            $eachLead->result = ($eachLead->result == "" ? "OPEN" : $eachLead->result);
            $sheet->fromArray(array_merge([$key + 1],array_values($eachLead->toArray())),NULL,'A' . ($key + 3));
        }

        // $datas =  $datas->map(function($item,$key) use ($sheet){
        //     $sheet->fromArray(array_merge([$key + 1],array_values($item->toArray())),NULL,'A' . ($key + 3));
        // });

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setWidth(25);

        $fileName = 'Report Lead ' . date('Y') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        
        $writer = new Xlsx($spreadsheet);
        ob_end_clean();
        return $writer->save("php://output");
    }

    public function exportExcelOpen(Request $request)
    {
        $spreadsheet = new Spreadsheet();

        $prSheet = new Worksheet($spreadsheet,'Report Lead Register SD');
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
        $sheet->setCellValue('A1','Report Lead Register SD');

        $headerStyle = $normalStyle;
        $headerStyle['font']['bold'] = true;
        $sheet->getStyle('A2:H2')->applyFromArray($headerStyle);;

        $headerContent = ["NO", "LEAD ID", "CUSTOMER LEGAL NAME", "OPTY NAME", "CREATE DATE", "OWNER", "AMOUNT", "STATUS"];
        $sheet->fromArray($headerContent,NULL,'A2');

        $year = date('Y');

        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        $datas_open = Sales2::join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select(
                        'sales_lead_register.lead_id', 
                        'tb_contact.customer_legal_name', 
                        'sales_lead_register.opp_name',
                        DB::raw('SUBSTRING(`sales_lead_register`.`created_at`,1,10) AS `created_at_formated`'),
                        'users.name', 
                        'sales_lead_register.amount',
                        'sales_lead_register.result'
                    )
                    ->where('result','')
                    ->where('year',$year)
                    ->orderBy('sales_lead_register.created_at','DESC');


        if ($pos == 'DIRECTOR' || $div == 'TECHNICAL' && $pos == 'MANAGER') {
                $datas_open = $datas_open
                                ->get();
        }else if ($div == 'SALES' && Auth::User()->id_company == '1') {
            $datas_open = $datas_open
                            ->where('id_territory', $ter)
                            ->get();
        }else{
            $datas_open = $datas_open
                            ->where('id_territory', $ter)
                            ->get();
        }


        $datas_sd = Sales2::join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select(
                        'sales_lead_register.lead_id', 
                        'tb_contact.customer_legal_name', 
                        'sales_lead_register.opp_name',
                        DB::raw('SUBSTRING(`sales_lead_register`.`created_at`,1,10) AS `created_at_formated`'),
                        'users.name', 
                        'sales_lead_register.amount',
                        'sales_lead_register.result'
                    )
                    ->where('result', 'SD')
                    ->where('year',$year)
                    ->orderBy('sales_lead_register.created_at','DESC');


        if ($pos == 'DIRECTOR' || $div == 'TECHNICAL' && $pos == 'MANAGER') {
                $datas_sd = $datas_sd
                    ->get();
        }else if ($div == 'SALES' && Auth::User()->id_company == '1') {
            $datas_sd = $datas_sd
                    ->where('id_territory', $ter)
                    ->get();
        }else{
            $datas_sd = $datas_sd
                    ->where('id_territory', $ter)
                    ->get();
        }

        $datas_tp = Sales2::join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select(
                        'sales_lead_register.lead_id', 
                        'tb_contact.customer_legal_name', 
                        'sales_lead_register.opp_name',
                        DB::raw('SUBSTRING(`sales_lead_register`.`created_at`,1,10) AS `created_at_formated`'),
                        'users.name', 
                        'sales_lead_register.amount',
                        'sales_lead_register.result'
                    )
                    ->where('result', 'TP')
                    ->where('year',$year)
                    ->orderBy('sales_lead_register.created_at','DESC');


        if ($pos == 'DIRECTOR' || $div == 'TECHNICAL' && $pos == 'MANAGER') {
            $datas_tp =$datas_tp
                    ->get();
        }else if ($div == 'SALES' && Auth::User()->id_company == '1') {
            $datas_tp = $datas_tp
                    ->where('id_territory', $ter)
                    ->get();
        }else{
            $datas_tp = $datas_tp
                    ->where('id_territory', $ter)
                    ->get();
        }

        $result = $datas_open->concat($datas_sd)->concat($datas_tp);
        foreach ($result->all() as $key => $eachLead) {
            $eachLead->amount = number_format($eachLead->amount,2,",",".");
            $eachLead->result = ($eachLead->result == "" ? "OPEN" : $eachLead->result);
            $sheet->fromArray(array_merge([$key + 1],array_values($eachLead->toArray())),NULL,'A' . ($key + 3));
        }

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setWidth(25);

        $fileName = 'Report Lead Register Open ' . date('Y') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        
        $writer = new Xlsx($spreadsheet);
        ob_end_clean();
        return $writer->save("php://output");
    }

    public function exportExcelWin(Request $request)
    {
        $spreadsheet = new Spreadsheet();

        $prSheet = new Worksheet($spreadsheet,'Report Lead Register WIN');
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
        $sheet->setCellValue('A1','Report Lead Register WIN');

        $headerStyle = $normalStyle;
        $headerStyle['font']['bold'] = true;
        $sheet->getStyle('A2:H2')->applyFromArray($headerStyle);;

        $headerContent = ["NO", "LEAD ID", "CUSTOMER LEGAL NAME", "OPTY NAME", "CREATE DATE", "OWNER", "AMOUNT", "STATUS"];
        $sheet->fromArray($headerContent,NULL,'A2');


        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        $datas = Sales2::join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select(
                        'sales_lead_register.lead_id', 
                        'tb_contact.customer_legal_name', 
                        'sales_lead_register.opp_name',
                        DB::raw('SUBSTRING(`sales_lead_register`.`created_at`,1,10) AS `created_at_formated`'),
                        'users.name', 
                        'sales_lead_register.amount',
                        'sales_lead_register.result'
                    )
                    ->where('result', 'WIN')
                    ->where('year',date('Y'))
                    ->orderBy('sales_lead_register.created_at','DESC');


        if ($pos == 'DIRECTOR' || $div == 'TECHNICAL' && $pos == 'MANAGER') {
            $datas = $datas->get();
        }else{
            $datas = $datas->where('id_territory', $ter)
                    ->get();
        }

        foreach ($datas as $key => $eachLead) {
            $eachLead->amount = number_format($eachLead->amount,2,",",".");
            $sheet->fromArray(array_merge([$key + 1],array_values($eachLead->toArray())),NULL,'A' . ($key + 3));
        }

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setWidth(25);

        $fileName = 'Report Lead Register WIN ' . date('Y') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        
        $writer = new Xlsx($spreadsheet);
        ob_end_clean();
        return $writer->save("php://output");

    }

    public function exportExcelLose(Request $request)
    {
       $spreadsheet = new Spreadsheet();

        $prSheet = new Worksheet($spreadsheet,'Report Lead Register LOSE');
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
        $sheet->setCellValue('A1','Report Lead Register LOSE');

        $headerStyle = $normalStyle;
        $headerStyle['font']['bold'] = true;
        $sheet->getStyle('A2:H2')->applyFromArray($headerStyle);;

        $headerContent = ["NO", "LEAD ID", "CUSTOMER LEGAL NAME", "OPTY NAME", "CREATE DATE", "OWNER", "AMOUNT", "STATUS"];
        $sheet->fromArray($headerContent,NULL,'A2');


        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        $datas = Sales2::join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select(
                        'sales_lead_register.lead_id', 
                        'tb_contact.customer_legal_name', 
                        'sales_lead_register.opp_name',
                        DB::raw('SUBSTRING(`sales_lead_register`.`created_at`,1,10) AS `created_at_formated`'),
                        'users.name', 
                        'sales_lead_register.amount',
                        'sales_lead_register.result'
                    )
                    ->where('result', 'LOSE')
                    ->where('year',date('Y'))
                    ->orderBy('sales_lead_register.created_at','DESC');

        if ($pos == 'DIRECTOR' || $div == 'TECHNICAL' && $pos == 'MANAGER') {
            $datas = $datas->get();
        }else{
            $datas = $datas->where('id_territory', $ter)
                    ->get();
        }

        foreach ($datas as $key => $eachLead) {
            $eachLead->amount = number_format($eachLead->amount,2,",",".");
            $sheet->fromArray(array_merge([$key + 1],array_values($eachLead->toArray())),NULL,'A' . ($key + 3));
        }

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setWidth(25);

        $fileName = 'Report Lead Register LOSE ' . date('Y') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        
        $writer = new Xlsx($spreadsheet);
        ob_end_clean();
        return $writer->save("php://output");
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

        $notifClaim = null;


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
                    ->orderBy('sales_lead_register.created_at','DESC')                    
                    ->get();
            }elseif ($div == 'SALES' && $pos != 'ADMIN'){
                $lead = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'sales_lead_register.deal_price', 'users.name')
                    ->whereYear('closing_date',date('Y'))
                    ->where('id_territory', $ter)
                    ->where('users.nik', $nik)
                    ->orderBy('sales_lead_register.created_at','DESC')                    
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
                    ->orderBy('sales_lead_register.created_at','DESC')                    
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
                    ->orderBy('sales_lead_register.created_at','DESC')                    
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
                    ->orderBy('sales_lead_register.created_at','DESC')                   
                    ->get();
            } else {
                $lead = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'sales_lead_register.deal_price', 'users.name')
                    ->where('id_territory', $ter)
                    ->where('year',$year)
                    ->orderBy('sales_lead_register.created_at','DESC')                    
                    ->get();
            }
        } elseif ($ter == 'DPG' && $pos == 'ENGINEER MANAGER') {
            $lead = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id','tb_contact.brand_name', 'sales_lead_register.opp_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name', 'sales_lead_register.result', 'sales_lead_register.deal_price')
                ->where('result','win')
                ->where('year',$year)
                ->orderBy('sales_lead_register.created_at','DESC')                   
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
                ->orderBy('sales_lead_register.created_at','DESC')                   
                ->get();
        }  else {
            $lead = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'sales_lead_register.deal_price', 'users.name')
                ->where('year',$year)
                ->where('result','!=','hmm')
                ->orderBy('sales_lead_register.created_at','DESC')                   
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

        return view('report/lead', compact('lead','notif', 'notifOpen', 'notifsd', 'notiftp', 'notifClaim'));
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
                    ->orderBy('sales_lead_register.created_at','DESC')                   
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
                    ->orderBy('sales_lead_register.created_at','DESC')                   
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
                    ->orderBy('sales_lead_register.created_at','DESC')                   
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
                    ->orderBy('sales_lead_register.created_at','DESC')                   
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
                ->orderBy('sales_lead_register.created_at','DESC')                   
                ->get();
        } else {
            $open = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name', 'sales_lead_register.deal_price')
                ->where('result', '')
                ->where('year',$year)
                ->orderBy('sales_lead_register.created_at','DESC')                   
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
                    ->orderBy('sales_lead_register.created_at','DESC')                   
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
                    ->orderBy('sales_lead_register.created_at','DESC')                   
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
                    ->orderBy('sales_lead_register.created_at','DESC')                   
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
                    ->orderBy('sales_lead_register.created_at','DESC')                   
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
                ->orderBy('sales_lead_register.created_at','DESC')                                   
                ->get();
        } else {
            $sd = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name', 'sales_lead_register.deal_price')
                ->where('result', 'SD')
                ->where('year',$year)
                ->orderBy('sales_lead_register.created_at','DESC') 
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
                    ->orderBy('sales_lead_register.created_at','DESC')   
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
                    ->orderBy('sales_lead_register.created_at','DESC')   
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
                    ->orderBy('sales_lead_register.created_at','DESC')
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
                    ->orderBy('sales_lead_register.created_at','DESC')
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
                ->orderBy('sales_lead_register.created_at','DESC')
                ->get();
        } else {
            $tp = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name', 'sales_lead_register.deal_price')
                ->where('result', 'TP')
                ->where('year',$year)
                ->orderBy('sales_lead_register.created_at','DESC')
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
        } else {
            $notifClaim = NULL;
        }

        return view('report/open_status', compact('open','sd','tp','notif', 'notifOpen', 'notifsd', 'notiftp','notifClaim'));
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
                    ->orderBy('sales_lead_register.created_at','DESC')                   

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
                    ->orderBy('sales_lead_register.created_at','DESC')                   

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
                    ->orderBy('sales_lead_register.created_at','DESC')                   

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
                    ->orderBy('sales_lead_register.created_at','DESC')                   

                    ->get();
            }
        }elseif ($div == 'FINANCE') {
            $win = DB::table('dvg_esm')
                    ->join('users', 'users.nik', '=', 'dvg_esm.personnel')
                    ->select('no','date','users.name', 'type', 'description', 'amount', 'id_project', 'remarks', 'status', 'sales_lead_register.deal_price')
                    ->where('status', 'FINANCE')
                    ->orderBy('sales_lead_register.created_at','DESC')                   

                    ->get();
        } else {
            $win = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name', 'sales_lead_register.deal_price')
                ->where('result', 'win')
                ->where('year',$year)
                ->orderBy('sales_lead_register.created_at','DESC')                   

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
        } else {
            $notifClaim = NULL;
        }

        return view('report/win_status', compact('win', 'notif', 'notifOpen', 'notifsd', 'notiftp','notifClaim'));
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
                    ->orderBy('sales_lead_register.created_at','DESC')                   

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
                    ->orderBy('sales_lead_register.created_at','DESC')                   

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
                    ->orderBy('sales_lead_register.created_at','DESC')                   

                    ->get();
            }  else {
                $lose = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                    ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name', 'sales_lead_register.deal_price')
                    ->where('result', 'lose')
                    ->where('id_territory', $ter)
                    ->where('year',$year)
                    ->orderBy('sales_lead_register.created_at','DESC')
                    ->get();
            }
        }elseif ($ter == null) {
            if ($div == 'SALES') {
                $lose = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name', 'sales_lead_register.deal_price')
                ->where('result', 'lose')
                ->where('id_territory', $ter)
                ->where('id_company','2')
                ->where('year',$year)
                ->orderBy('sales_lead_register.created_at','DESC') 
                ->get();
            }else{
                $lose = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->select('sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.brand_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'sales_lead_register.result', 'users.name', 'sales_lead_register.deal_price')
                ->where('result', 'lose')
                ->where('year',$year)
                ->orderBy('sales_lead_register.created_at','DESC') 
                ->get();
            }
            
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
                ->orderBy('sales_lead_register.created_at','DESC')                   
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
        } else {
            $notifClaim = null;
        }

        return view('report/lose_status', compact('lose', 'notif', 'notifOpen', 'notifsd', 'notiftp','notifClaim'));
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

    public function report_range(Request $request)
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
                ->orderBy('year','desc')
                ->get();

        $currentYear = Date('Y');

        $presales = '';    

        // $getPresales = DB::table('sales_solution_design')->join('users', 'users.nik', '=','sales_solution_design.nik')->selectRaw('`users`.`name` AS `name_presales`, GROUP_CONCAT(`sales_solution_design`.`nik`) AS `nik_presales`,GROUP_CONCAT(`sales_solution_design`.`priority`) AS `priority`')->selectRaw('lead_id')->where('status','closed')->groupBy('lead_id','name_presales');

        // $leadsnow = DB::table('sales_lead_register')
        //         ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
        //         ->leftJoinSub($getPresales, 'tb_presales',function($join){
        //             $join->on("sales_lead_register.lead_id", '=', 'tb_presales.lead_id');
        //         })
        //         ->join('users as u_sales', 'u_sales.nik', '=', 'sales_lead_register.nik')
        //         ->join('tb_company', 'tb_company.id_company', '=', 'u_sales.id_company')
        //         ->join('tb_territory','tb_territory.id_territory','=','u_sales.id_territory')
        //         ->Leftjoin('tb_pid', 'tb_pid.lead_id', '=', 'sales_lead_register.lead_id')
        //         ->leftjoin('sales_tender_process','sales_tender_process.lead_id','=','sales_lead_register.lead_id')
        //         ->select('sales_tender_process.win_prob','sales_lead_register.lead_id', 'tb_contact.id_customer', 'tb_contact.code', 'sales_lead_register.opp_name','tb_contact.customer_legal_name', 'tb_contact.brand_name', 'sales_lead_register.created_at', 'sales_lead_register.amount', 'u_sales.name as name','sales_lead_register.nik','sales_lead_register.keterangan','sales_lead_register.year', 'sales_lead_register.closing_date', 'sales_lead_register.deal_price','u_sales.id_territory', 'tb_pid.status','tb_presales.name_presales','tb_presales.priority','sales_lead_register.year','tb_territory.name_territory',DB::raw("(CASE WHEN (result = 'OPEN') THEN 'INITIAL' WHEN (result = '') THEN 'OPEN' WHEN (result = 'SD') THEN 'SD' WHEN (result = 'TP') THEN 'TP' WHEN (result = 'WIN') THEN 'WIN' WHEN( result = 'LOSE') THEN 'LOSE' WHEN( result = 'HOLD') THEN 'HOLD' WHEN( result = 'SPECIAL') THEN 'SPECIAL' WHEN(result = 'CANCEL') THEN 'CANCEL' END) as result_modif"), 'code_company', 'u_sales.id_company')
        //         ->orderByRaw('FIELD(result, "OPEN", "", "SD", "TP", "WIN", "LOSE", "CANCEL", "HOLD")')
        //         ->where('result','!=','hmm')
        //         ->orderBy('created_at', 'desc'); 

        $getPresales = DB::table('sales_solution_design')->join('users', 'users.nik', '=','sales_solution_design.nik')->selectRaw('GROUP_CONCAT(`users`.`name`) AS `name_presales`, GROUP_CONCAT(`sales_solution_design`.`nik`) AS `nik_presales`, GROUP_CONCAT(`sales_solution_design`.`priority`) AS `priority`')->selectRaw('lead_id')->groupBy('lead_id');

        $leadsnow = DB::table('sales_lead_register')
                ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->leftJoinSub($getPresales, 'tb_presales',function($join){
                    $join->on("sales_lead_register.lead_id", '=', 'tb_presales.lead_id');
                })
                ->join('users as u_sales', 'u_sales.nik', '=', 'sales_lead_register.nik')
                ->join('tb_territory','tb_territory.id_territory','=','u_sales.id_territory')
                ->join('tb_company', 'tb_company.id_company', '=', 'u_sales.id_company')
                ->Leftjoin('tb_pid', 'tb_pid.lead_id', '=', 'sales_lead_register.lead_id')
                ->leftjoin('sales_tender_process','sales_tender_process.lead_id','=','sales_lead_register.lead_id')
                ->select('sales_tender_process.win_prob',
                    'sales_lead_register.lead_id', 
                    'tb_contact.id_customer', 
                    'tb_contact.code', 
                    'sales_lead_register.opp_name',
                    'tb_contact.customer_legal_name', 
                    'tb_contact.brand_name', 
                    'sales_lead_register.created_at', 
                    'sales_lead_register.amount',
                     'u_sales.name as name','sales_lead_register.nik',
                     'sales_lead_register.keterangan','sales_lead_register.year', 
                     'sales_lead_register.closing_date', 
                     'sales_lead_register.deal_price',
                     'u_sales.id_territory', 
                     'tb_pid.status',
                     'tb_presales.name_presales', 
                     'code_company',
                      'name_territory',
                     'tb_presales.priority', 
                     DB::raw("(CASE WHEN (result = 'OPEN') THEN 'INITIAL' WHEN (result = '') THEN 'OPEN' WHEN (result = 'SD') THEN 'SD' WHEN (result = 'TP') THEN 'TP' WHEN (result = 'WIN') THEN 'WIN' WHEN( result = 'LOSE') THEN 'LOSE' WHEN( result = 'HOLD') THEN 'HOLD' WHEN( result = 'SPECIAL') THEN 'SPECIAL' WHEN(result = 'CANCEL') THEN 'CANCEL' END) as result_modif"))
                ->orderByRaw('FIELD(result, "OPEN", "", "SD", "TP", "WIN", "LOSE", "CANCEL", "HOLD")')
                ->where('result','!=','hmm')
                ->where('status_karyawan','!=','dummy')
                // ->whereIn('year',$year)
                // ->where('year', $year)
                ->orderBy('created_at', 'desc'); 

        // $total_deal_price = Sales::join('users as u_sales', 'u_sales.nik', '=', 'sales_lead_register.nik')
        //                         ->join('tb_company', 'tb_company.id_company', '=', 'u_sales.id_company')
        //                         ->select(DB::raw('SUM(sales_lead_register.deal_price) as deal_prices'))
        //                         ->where('code_company', 'MSP')
        //                         ->where('result','!=','hmm'); 

        $total_deal_price = DB::table('sales_lead_register')
                ->join('users as u_sales', 'u_sales.nik', '=', 'sales_lead_register.nik')
                ->join('tb_company', 'tb_company.id_company', '=', 'u_sales.id_company')
                ->leftJoinSub($getPresales, 'tb_presales',function($join){
                    $join->on("sales_lead_register.lead_id", '=', 'tb_presales.lead_id');
                })
                ->leftjoin('sales_tender_process','sales_tender_process.lead_id','=','sales_lead_register.lead_id')
                ->where('result', '!=', 'hmm');

        if($ter != null){
            $leads = $leadsnow->where('u_sales.id_company', '1')->get();

            $total_deal_price = $total_deal_price->where('u_sales.id_company', '1')->first();
            if ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
                $leads = $leadsnow->where('nik_presales', $nik)->get();

                // $total_deal_price = $total_deal_price->where('nik_presales', $nik)->first();

            } else if ($div == 'SALES') {
                $leads = $leadsnow->where('u_sales.id_territory', $ter)->get();
                // $total_deal_price = $total_deal_price->where('u_sales.id_territory', $ter)->first();
            }        
        }else{
            $leads = $leadsnow->get();
            
            $total_deal_price = $total_deal_price->sum('deal_price');
        }  

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
                ->where('status_karyawan','!=','dummy')
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
                ->where('status_karyawan','!=','dummy')
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
                ->where('status_karyawan','!=','dummy')
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

        return view('report/report_range', compact('leads','lead', 'notif', 'notifOpen', 'notifsd','notiftp','presales','rk','gp','st','rz','nt', 'total_deal_price','total_lead','total_open','total_sd','total_tp','total_win','total_lose','years','currentYear'))->with(['initView'=> $this->initMenuBase()]);
    }

    public function downloadExcelReportRange(Request $request) {
        $spreadsheet = new Spreadsheet();

        $prSheet = new Worksheet($spreadsheet,'Lead Register');
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
        $titleStyle['borders'] = ['outline' => ['borderStyle' => Border::BORDER_THIN]];
        $titleStyle['fill'] = ['fillType' => Fill::FILL_SOLID, 'startColor' => ["argb" => "FFFCD703"]];
        $titleStyle['font']['bold'] = true;

        $sheet->getStyle('A1:H1')->applyFromArray($titleStyle);
        $sheet->setCellValue('A1','Lead Register');

        $headerStyle = $normalStyle;
        $headerStyle['font']['bold'] = true;
        $sheet->getStyle('A2:H2')->applyFromArray($headerStyle);;

        $headerContent = ["LEAD ID", "OWNER", "OPP NAME", "CUSTOMER", "CREATEDATE",  "CLOSING DATE", "AMOUNT", "STATUS"];
        $sheet->fromArray($headerContent,NULL,'A2');

        $getPresales = solution_design::join('users', 'users.nik', '=','sales_solution_design.nik')->selectRaw('`users`.`name` AS `name_presales`, GROUP_CONCAT(`sales_solution_design`.`nik`) AS `nik_presales`, GROUP_CONCAT(`sales_solution_design`.`priority`) AS `priority`')->selectRaw('lead_id')->selectRaw('name')->where('status','closed')->groupBy('lead_id','name_presales');

        $leadsnow = DB::table('sales_lead_register')->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
                ->leftJoinSub($getPresales, 'tb_presales',function($join){
                    $join->on("sales_lead_register.lead_id", '=', 'tb_presales.lead_id');
                })
                ->join('users as u_sales', 'u_sales.nik', '=', 'sales_lead_register.nik')
                ->join('tb_company', 'tb_company.id_company', '=', 'u_sales.id_company')
                ->join('tb_territory','tb_territory.id_territory','=','u_sales.id_territory')
                ->leftjoin('sales_tender_process','sales_tender_process.lead_id','=','sales_lead_register.lead_id')
                ->select('sales_lead_register.lead_id', 'u_sales.name', 'sales_lead_register.opp_name', 'tb_contact.brand_name', 'sales_lead_register.created_at', 'sales_lead_register.closing_date', DB::raw("IFNULL(`sales_lead_register`.`deal_price`,0) AS `deal_price`"), DB::raw("(CASE WHEN (result = 'OPEN') THEN 'INITIAL' WHEN (result = '') THEN 'OPEN' WHEN (result = 'SD') THEN 'SD' WHEN (result = 'TP') THEN 'TP' WHEN (result = 'WIN') THEN 'WIN' WHEN( result = 'LOSE') THEN 'LOSE' WHEN( result = 'HOLD') THEN 'HOLD' WHEN( result = 'SPECIAL') THEN 'SPECIAL' WHEN(result = 'CANCEL') THEN 'CANCEL' END) as result_modif"))
                ->orderByRaw('FIELD(result, "OPEN", "", "SD", "TP", "WIN", "LOSE", "CANCEL", "HOLD")')
                ->where('result','!=','hmm')
                ->orderBy('created_at', 'desc'); 

        if (isset($request->year)) {
            $leadsnow->where('year', $request->year);   
        }

        if (isset($request->comp)) {
            $leadsnow->where('code_company', $request->comp);
        }

        if (isset($request->ter)) {
            $leadsnow->where('u_sales.id_territory', $request->ter);
        }

        if (isset($request->sales)) {
            $leadsnow->where('u_sales.name', $request->sales);
        }

        if (isset($request->presales)) {
            $leadsnow->where('tb_presales.name', $request->presales);
        }
        
        if (isset($request->winprob)) {
            $leadsnow->where('win_prob', $request->winProb);
        }

        if (isset($request->priority)) {
            $leadsnow->where('priority', $request->priority);
        }

        if (isset($request->status)) {
            $leadsnow->where('result', $request->status);
        } 

        if (isset($request->closing_date)) {
            $leadsnow->whereYear('closing_date', $request->closing_date);
        } 

        if (isset($request->date_start)) {
            $leadsnow->where('sales_lead_register.created_at', '>=', $request->date_start)
                 ->where('sales_lead_register.created_at', '<=', $request->date_end);   
        }

        $datas = json_decode(json_encode($leadsnow->get()), true);

        foreach ($datas as $key => $data) {
            $data['deal_price'] = number_format((int)$data['deal_price'],2,",",".");
            $sheet->fromArray($data,NULL,'A' . ($key + 3));
        }

        // $sheet->fromArray(["BBRI190901","Liliany","Pengadaan Server dan Sosama Teknologi Bank BRI","Bank BRI","2019-09-03 18:58:10","2019-09-30","0","SPECIAL"],NULL,'A' . 3);


        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setWidth(35);


        $fileName = 'Lead Register ' . date('Y-m-d') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        
        $writer = new Xlsx($spreadsheet);
        ob_end_clean();
        return $writer->save("php://output");
    }

    public function filter_sales_report(Request $req){
        return user::join('tb_company', 'tb_company.id_company', '=', 'users.id_company')->join('sales_lead_register', 'sales_lead_register.nik', '=', 'users.nik')->select('name', 'code_company', 'result', 'year')->where('users.nik',$req->nik)->where('result', 'WIN')->where('year', date('Y'))->first();
    }

    public function total_deal_price(Request $request){
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        $getPresales = DB::table('sales_solution_design')->join('users', 'users.nik', '=','sales_solution_design.nik')->selectRaw('`users`.`name` AS `name_presales`, GROUP_CONCAT(`sales_solution_design`.`nik`) AS `nik_presales`,GROUP_CONCAT(`sales_solution_design`.`priority`) AS `priority`')->selectRaw('lead_id')->selectRaw('name')->where('status','closed')->groupBy('lead_id','name_presales');

        $data = DB::table('sales_lead_register')
                ->join('users as u_sales', 'u_sales.nik', '=', 'sales_lead_register.nik')
                ->join('tb_company', 'tb_company.id_company', '=', 'u_sales.id_company')
                ->leftJoinSub($getPresales, 'tb_presales',function($join){
                    $join->on("sales_lead_register.lead_id", '=', 'tb_presales.lead_id');
                })
                ->leftjoin('sales_tender_process','sales_tender_process.lead_id','=','sales_lead_register.lead_id')
                ->where('result', '!=', 'hmm');

        // if (isset($request->nik)) {
        //     if (isset($request->year)) {
        //         $data->whereYear('closing_date', $request->year);   
        //     }
        // }else{
        //      if (isset($request->year)) {
        //         $data->where('year', $request->year);   
        //     }
        // }

        if (isset($request->year)) {
            $data->whereYear('closing_date', $request->year);   
        }

        if (isset($request->date_start)) {
            $data->where('sales_lead_register.closing_date', '>=', $request->date_start)
            ->where('sales_lead_register.closing_date', '<=', $request->date_end);         
        }

        if (isset($request->comp)) {
            $data->where('code_company', $request->comp);
        } else {
            $data->where('code_company', "SIP");     
        }

        if (isset($request->ter)) {
            $data->where('u_sales.id_territory', $request->ter);
        }

        if (isset($request->sales)) {
            $data->where('u_sales.name', $request->sales);
        }

        if (isset($request->presales)) {
            $data->where('tb_presales.name', $request->presales);
        }
        
        if (isset($request->winprob)) {
            if ($request->winprob != undefined) {
                $data->where('win_prob', $request->winProb);

            }
        }

        if (isset($request->priority)) {
            $data->where('priority', $request->priority);
        }

        if (!isset($request->status)) {
            $data->whereIn('result', $request->status);
        }

        // return $data->pluck('sales_lead_register.lead_id');
        
        return array($data->sum('deal_price'));
    	
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

        return view('report/report_range2', compact('lead', 'notif', 'notifOpen', 'notifsd','notiftp','rk','gp','st','rz','nt', 'total_deal_price','total_lead','total_open','total_sd','total_tp','total_win','total_lose', 'year_now', 'year', 'leads_now'))->with(['initView'=>$this->initMenuBase()]);
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

        return array("data" => $filter_sd);

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

        return array("data" => $filter_tp);

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

        return array("data" => $filter_win);

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

        return array("data" => $filter_lose);

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

        if ($request->type == 'ALL') {
            $top_win_sip = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                        ->join('sales_tender_process', 'sales_tender_process.lead_id', '=', 'sales_lead_register.lead_id')
                        ->select(DB::raw('COUNT(sales_lead_register.lead_id) as leads'), DB::raw('SUM(sales_lead_register.deal_price) as amounts'), 'users.name', 'tb_company.code_company')
                        ->where('result', 'WIN')
                        // ->where('sales_tender_process.win_prob', $request->data)
                        ->whereYear('closing_date', $request->tahun)
                        ->where('users.status_karyawan', 'cuti')
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
                        ->whereYear('closing_date', $request->tahun)
                        ->where('users.status_karyawan', 'cuti')
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

    public function get_top_win_sip(){
        $top_win_sip =DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                        ->select(DB::raw('COUNT(sales_lead_register.lead_id) as leads'), DB::raw('SUM(sales_lead_register.amount) as amounts'), DB::raw('SUM(sales_lead_register.deal_price) as deal_prices'), 'users.name', 'tb_company.code_company','users.nik','users.id_territory')
                        ->where('result', 'WIN')
                        ->whereYear('closing_date', date('Y'))
                        ->where('users.id_company', '1')
                        ->where('users.status_karyawan', '!=', 'dummy')
                        ->groupBy('sales_lead_register.nik')
                        ->orderBy('deal_prices', 'desc')
                        ->take(5)
                        ->get();

        return array("data" => $top_win_sip);
    }

    public function get_top_win_msp()
    {
        $top_win_msp = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                        ->select(DB::raw('COUNT(sales_lead_register.lead_id) as leads'), DB::raw('SUM(sales_lead_register.amount) as amounts'), DB::raw('SUM(sales_lead_register.deal_price) as deal_prices'), 'users.name', 'tb_company.code_company','users.nik')
                        ->where('result', 'WIN')
                        ->whereYear('closing_date', date('Y'))
                        ->where('users.id_company', '2')
                        ->where('users.status_karyawan', '!=', 'dummy')
                        ->groupBy('sales_lead_register.nik')
                        ->orderBy('deal_prices', 'desc')
                        ->take(5)
                        ->get();

        return array("data" => $top_win_msp);
    }

    public function get_filter_top_win_sip(Request $request)
    {
        $top_win_sip = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                        ->select(DB::raw('COUNT(sales_lead_register.lead_id) as leads'), DB::raw('SUM(sales_lead_register.amount) as amounts'), DB::raw('SUM(sales_lead_register.deal_price) as deal_prices'), 'users.name', 'tb_company.code_company')
                        ->where('result', 'WIN')
                        ->where('sales_lead_register.closing_date', '>=', $request->start)
                        ->where('sales_lead_register.closing_date', '<=', $request->end)
                        ->where('users.id_company', '1')
                        ->groupBy('sales_lead_register.nik')
                        ->orderBy('deal_prices', 'desc')
                        ->take(5)
                        ->get();

        return array("data" => $top_win_sip);
    }

    public function get_filter_top_win_msp(Request $request)
    {
        $top_win_msp = DB::table('sales_lead_register')
                        ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                        ->join('tb_company', 'tb_company.id_company', '=', 'users.id_company')
                        ->select(DB::raw('COUNT(sales_lead_register.lead_id) as leads'), DB::raw('SUM(sales_lead_register.amount) as amounts'), DB::raw('SUM(sales_lead_register.deal_price) as deal_prices'), 'users.name', 'tb_company.code_company')
                        ->where('result', 'WIN')
                        ->where('sales_lead_register.closing_date', '>=', $request->start)
                        ->where('sales_lead_register.closing_date', '<=', $request->end)
                        ->where('users.id_company', '2')
                        ->groupBy('sales_lead_register.nik')
                        ->orderBy('deal_prices', 'desc')
                        ->take(5)
                        ->get();

        return array("data" => $top_win_msp);
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

        return view('report/report_sales', compact('lead', 'notif', 'notifOpen', 'notifsd', 'notiftp', 'total_ter', 'lead_sales','cek_sales', 'lead_sd', 'lead_tp', 'lead_win', 'lead_lose', 'lead_summary', 'top_win', 'top_win_sip', 'top_win_msp', 'years'))->with(['initView'=>$this->initMenuBase(),'feature_item'=>$this->RoleDynamic('report_sales')]);

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
                        ->whereYear('closing_date', date('Y'))
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
                            ->where('status_karyawan','!=','dummy')
        					->get();

        // return $top_win_presales;

        $users = User::join('role_user','users.nik','=','role_user.user_id')
                ->join('roles','role_user.role_id','=','roles.id')
                ->select('users.name', 'users.nik')
                ->where('status_karyawan','!=','dummy')
                ->where('roles.group', 'Synergy System Management')
                ->get();

        // return $users;

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

        return view('report/report_presales', compact('lead', 'notif', 'notifOpen', 'notifsd', 'notiftp', 'total_ter', 'lead_sales','cek_sales', 'lead_sd', 'lead_tp', 'lead_win', 'lead_lose', 'lead_summary', 'top_win', 'top_win_presales', 'years', 'users'))->with(['initView'=>$this->initMenuBase()]);

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
            ->join('users','users.id_territory','tb_territory.id_territory')
            ->select("tb_territory.id_territory","code_ter")
            ->where('status_karyawan','!=','dummy')
            ->where('tb_territory.id_territory','like','TERRITORY%')->groupby('tb_territory.id_territory')
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
        return view('report/report_territory', compact('notif', 'notifOpen', 'notifsd', 'notiftp', 'notifClaim' ,'territory_loop'))->with(['initView'=> $this->initMenuBase(),'feature_item'=>$this->RoleDynamic('report_territory')]);
    
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

    public function getCustomerPerTerritory(Request $request){
        $data = Sales2::join('users','users.nik','=','sales_lead_register.nik')
            ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
            ->select('tb_contact.brand_name','users.id_territory')
            ->where('result','!=','CANCEL')
            ->where('result','!=','HOLD')
            ->where('result','!=','SPECIAL')
            ->where('sales_lead_register.result','!=','hmm')
            ->where('users.status_karyawan','!=','dummy')
            ->whereRaw("(`id_territory` != 'SALES MSP' AND `id_territory` != 'SPECIALIST' AND `id_territory` != 'PRESALES')")
            ->where('id_territory',$request->id_territory)
            ->groupBy('id_territory')
            ->groupBy('sales_lead_register.id_customer');

        // return array("data" => $data->get()->groupBy('id_territory'));
        // return $data;
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
        return view('report/report_product_technology', compact('notif', 'notifOpen', 'notifsd', 'notiftp', 'notifClaim'))->with(['initView'=> $this->initMenuBase()]);
    
    }

    public function report_product_technology_sip_msp(){
        return array("data" => Sales2::join('users','users.nik','=','sales_lead_register.nik')
                ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
                ->select('users.name','sales_lead_register.opp_name','sales_lead_register.amount','sales_lead_register.deal_price','tb_contact.brand_name','sales_lead_register.lead_id')
                ->get());
    
    }

    public function getFilterTags(Request $request){
        $query_product = DB::table('sales_lead_register')
                    ->joinSub(DB::table('tb_product_tag_relation'), 'tb_product_tag_relation_alias', function ($join) {
                        $join->on('sales_lead_register.lead_id', '=', 'tb_product_tag_relation_alias.lead_id');
                    })
                    ->joinSub(DB::table('tb_product_tag'), 'tb_product_tag_alias', function ($join) {
                        $join->on('tb_product_tag_alias.id', '=', 'tb_product_tag_relation_alias.id_product_tag');
                    })
                    ->joinSub(DB::table('tb_technology_tag'), 'tb_technology_tag_alias', function ($join) {
                        $join->on('tb_technology_tag_alias.id', '=', 'tb_product_tag_relation_alias.id_technology_tag');
                    })
                    ->Leftjoin('users as sales','sales.nik','=','sales_lead_register.nik')
                    ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                    ->Leftjoin('users as presales','presales.nik','=','sales_lead_register.nik')                    
                    ->select("sales_lead_register.lead_id")   
                    ->where('result','WIN') 
                    ->where('sales_lead_register.created_at','>=',$request->start_date)
                    ->where('sales_lead_register.created_at','<=',$request->end_date);


        if (isset($request->TagsProduct)) {
             $query_product->whereIn('tb_product_tag_relation_alias.id_product_tag',$request->TagsProduct);
        }

        if (isset($request->Tagstechno)) {
            $query_product->whereIn('tb_product_tag_relation_alias.id_technology_tag',$request->Tagstechno);
        }

        if (isset($request->TagsPersona)) {
            // $query_product->where(function ($query_main) use ($request){
            //     $query_main->where(function ($query) use ($request){
            //         foreach (explode(",", $request->TagsPersona[0]) as $key => $value) {
            //             $query->orWhere('sales.nik',$value);
            //         }
            //     });

            //     $query_main->orWhere(function ($query) use ($request){
            //         foreach (explode(",", $request->TagsPersona[0]) as $key => $value) {
            //             $query->orWhere('presales.nik',$value);
            //         }
            //     });
            // });

            $query_product->where(function ($query) use ($request){
                foreach (explode(",", $request->TagsPersona[0]) as $key => $value) {
                    if(str_contains($value, "-s")) {

                        $query->orWhere('sales.nik',trim($value,'-s'));
                    }
                }
            });

            $query_product->Where(function ($query) use ($request){
                foreach (explode(",", $request->TagsPersona[0]) as $key => $value) {
                    if (str_contains($value, "-p")) {
                        $query->orWhere('presales.nik',trim($value,'-p'));

                    }
                }
            });
        }



        $query_all = $query_product->get()->sortBy('lead_id');

        $sorted = $query_all->unique("lead_id")->values();

        $data = DB::table('sales_lead_register')                   
                    ->joinSub(DB::table('tb_contact'), 'tb_contact_alias', function ($join) {
                        $join->on('tb_contact_alias.id_customer', '=', 'sales_lead_register.id_customer');
                    })
                    ->Leftjoin('users as sales','sales.nik','=','sales_lead_register.nik')
                    ->select("sales_lead_register.lead_id","sales.name as name_sales","opp_name","amount","brand_name")
                    ->whereIn("sales_lead_register.lead_id",$sorted->pluck("lead_id"))->get();

        foreach($data as $sort){


            $tag_all = DB::table('tb_product_tag_relation')
                ->joinSub(DB::table('tb_product_tag'), 'tb_product_tag_alias', function ($join) {
                    $join->on('tb_product_tag_alias.id', '=', 'tb_product_tag_relation.id_product_tag');
                })
                ->joinSub(DB::table('tb_technology_tag'), 'tb_technology_tag_alias', function ($join) {
                    $join->on('tb_technology_tag_alias.id', '=', 'tb_product_tag_relation.id_technology_tag');
                })
                ->select('name_tech','name_product',DB::raw('(CASE WHEN (price is null) THEN 0 ELSE price END) AS price'))
                ->where('lead_id',$sort->lead_id)
                ->get();

            $sort->tag_tech = $tag_all->pluck('name_tech');
            $sort->tag_product = $tag_all->pluck('name_product');
            $sort->tag_price = $tag_all->pluck('price');

            $presales = DB::table('sales_solution_design')->where("lead_id",$sort->lead_id)->get()->pluck("nik");
            $sort->name_presales = DB::table("users")->whereIn("nik",$presales)
                            // ->selectRaw("GROUP_CONCAT(`name`)")
                            ->get()->pluck('name');

        }
        
        return array("data"=>$data);             
    
    }

    public function reportExcelTag(Request $request) {

        $spreadsheet = new Spreadsheet();

        $prSheet = new Worksheet($spreadsheet,'Report Tag');
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
        $titleStyle['borders'] = ['outline' => ['borderStyle' => Border::BORDER_THIN]];
        $titleStyle['fill'] = ['fillType' => Fill::FILL_SOLID, 'startColor' => ["argb" => "FFFCD703"]];
        $titleStyle['font']['bold'] = true;

        $sheet->getStyle('A1:I1')->applyFromArray($titleStyle);
        $sheet->setCellValue('A1','Report Tag');

        $headerStyle = $normalStyle;
        $headerStyle['font']['bold'] = true;
        $sheet->getStyle('A2:I2')->applyFromArray($headerStyle);;

        $headerContent = ["Lead Id", "Customer", "Opp Name", "Sales",  "Deal Price", "Technology", "Product" , "Price", "Presales"];
        $sheet->fromArray($headerContent,NULL,'A2');

        $query_product = DB::table('sales_lead_register')
                    ->joinSub(DB::table('tb_product_tag_relation'), 'tb_product_tag_relation_alias', function ($join) {
                        $join->on('sales_lead_register.lead_id', '=', 'tb_product_tag_relation_alias.lead_id');
                    })
                    ->joinSub(DB::table('tb_product_tag'), 'tb_product_tag_alias', function ($join) {
                        $join->on('tb_product_tag_alias.id', '=', 'tb_product_tag_relation_alias.id_product_tag');
                    })
                    ->joinSub(DB::table('tb_technology_tag'), 'tb_technology_tag_alias', function ($join) {
                        $join->on('tb_technology_tag_alias.id', '=', 'tb_product_tag_relation_alias.id_technology_tag');
                    })
                    ->Leftjoin('users as sales','sales.nik','=','sales_lead_register.nik')
                    ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                    ->Leftjoin('users as presales','presales.nik','=','sales_lead_register.nik')                    
                    ->select("sales_lead_register.lead_id") 
                    ->where('result','WIN')    
                    ->where('sales_lead_register.created_at','>=',$request->start_date)
                    ->where('sales_lead_register.created_at','<=',$request->end_date);


        if (isset($request->TagsProduct)) {
             $query_product->whereIn('tb_product_tag_relation_alias.id_product_tag',$request->TagsProduct);
        }

        if (isset($request->Tagstechno)) {
            $query_product->orwhereIn('tb_product_tag_relation_alias.id_technology_tag',$request->Tagstechno);
        }

        // if (isset($request->TagsPersona)) {
        //     $query_product->whereIn('sales.nik',$request->TagsPersona)
        //     ->orWhereIn('presales.nik',$request->TagsPersona)->get();
        // }

        if (isset($request->TagsPersona)) {
            // $query_product->where(function ($query_main) use ($request){
            //     $query_main->where(function ($query) use ($request){
            //         foreach (explode(",", $request->TagsPersona[0]) as $key => $value) {
            //             $query->orWhere('sales.nik',$value);
            //         }
            //     });

            //     $query_main->orWhere(function ($query) use ($request){
            //         foreach (explode(",", $request->TagsPersona[0]) as $key => $value) {
            //             $query->orWhere('presales.nik',$value);
            //         }
            //     });
            // });

            $query_product->where(function ($query) use ($request){
                foreach (explode(",", $request->TagsPersona[0]) as $key => $value) {
                    if(str_contains($value, "-s")) {

                        $query->orWhere('sales.nik',trim($value,'-s'));
                    }
                }
            });

            $query_product->Where(function ($query) use ($request){
                foreach (explode(",", $request->TagsPersona[0]) as $key => $value) {
                    if (str_contains($value, "-p")) {
                        $query->orWhere('presales.nik',trim($value,'-p'));

                    }
                }
            });
        }

        $query_all = $query_product->get()->sortBy('lead_id');

        $sorted = $query_all->unique("lead_id")->values();

        $data = DB::table('sales_lead_register')                   
                    ->joinSub(DB::table('tb_contact'), 'tb_contact_alias', function ($join) {
                        $join->on('tb_contact_alias.id_customer', '=', 'sales_lead_register.id_customer');
                    })
                    ->Leftjoin('users as sales','sales.nik','=','sales_lead_register.nik')
                    ->select("sales_lead_register.lead_id","brand_name","opp_name","sales.name as name_sales","amount")
                    ->whereIn("sales_lead_register.lead_id",$sorted->pluck("lead_id"))->get();

        foreach($data as $sort){
            $tag_all = DB::table('tb_product_tag_relation')
                ->joinSub(DB::table('tb_product_tag'), 'tb_product_tag_alias', function ($join) {
                    $join->on('tb_product_tag_alias.id', '=', 'tb_product_tag_relation.id_product_tag');
                })
                ->joinSub(DB::table('tb_technology_tag'), 'tb_technology_tag_alias', function ($join) {
                    $join->on('tb_technology_tag_alias.id', '=', 'tb_product_tag_relation.id_technology_tag');
                })
                ->select('name_tech','name_product',DB::raw('(CASE WHEN (price is null) THEN 0 ELSE price END) AS price'))
                ->where('lead_id',$sort->lead_id)
                ->get();

            $sort->tag_tech = implode("\n", $tag_all->pluck('name_tech')->toArray());
            $sort->tag_product = implode("\n", $tag_all->pluck('name_product')->toArray());
            // $sort->tag_price = implode("\n", $tag_all->pluck('price')->toArray());
            $sort->tag_price = $tag_all->pluck('price')->toArray();

            $presales = DB::table('sales_solution_design')->where("lead_id",$sort->lead_id)->get()->pluck("nik");
            $sort->name_presales = implode("\n",DB::table("users")->whereIn("nik",$presales)
                            ->get()->pluck('name')->toArray());
        }

        Cell::setValueBinder(new AdvancedValueBinder());

        foreach ($data as $key => $dataku) {
            $dataku->amount = number_format((int)$dataku->amount,2,",",".");
            $tag_price = "";
            $count_price = (sizeof($dataku->tag_price) == 1? true : false); 
            foreach ($dataku->tag_price as $key2 => $price) {
                if ($count_price ) {
                    $tag_price = $tag_price . number_format((int)$price,2,",",".");
                } else {
                    if((sizeof($dataku->tag_price) -1) == $key2){
                        $tag_price = $tag_price . number_format((int)$price,2,",",".");                    
                    } else {
                        $tag_price = $tag_price . number_format((int)$price,2,",",".") . "\n";                    
                    }
                }
            }

            $dataku->tag_price = $tag_price;
            $sheet->fromArray(array_values((array)$dataku),NULL,'A' . ($key + 3));

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

        $fileName = 'Report Lead Product Technology ' . date('Y') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        
        $writer = new Xlsx($spreadsheet);
        ob_end_clean();
        return $writer->save("php://output");
    }

    public function reportPdfTag(Request $request) {

        $query_product = DB::table('sales_lead_register')
                    ->joinSub(DB::table('tb_product_tag_relation'), 'tb_product_tag_relation_alias', function ($join) {
                        $join->on('sales_lead_register.lead_id', '=', 'tb_product_tag_relation_alias.lead_id');
                    })
                    ->joinSub(DB::table('tb_product_tag'), 'tb_product_tag_alias', function ($join) {
                        $join->on('tb_product_tag_alias.id', '=', 'tb_product_tag_relation_alias.id_product_tag');
                    })
                    ->joinSub(DB::table('tb_technology_tag'), 'tb_technology_tag_alias', function ($join) {
                        $join->on('tb_technology_tag_alias.id', '=', 'tb_product_tag_relation_alias.id_technology_tag');
                    })
                    ->Leftjoin('users as sales','sales.nik','=','sales_lead_register.nik')
                    ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
                    ->Leftjoin('users as presales','presales.nik','=','sales_lead_register.nik')                    
                    ->select("sales_lead_register.lead_id")    
                    ->where('sales_lead_register.created_at','>=',$request->start_date)
                    ->where('sales_lead_register.created_at','<=',$request->end_date);


        if (isset($request->TagsProduct)) {
             $query_product->whereIn('tb_product_tag_relation_alias.id_product_tag',$request->TagsProduct);
        }

        if (isset($request->Tagstechno)) {
            $query_product->orwhereIn('tb_product_tag_relation_alias.id_technology_tag',$request->Tagstechno);
        }

        if (isset($request->TagsPersona)) {
            $query_product->whereIn('sales.nik',$request->TagsPersona)
            ->orWhereIn('presales.nik',$request->TagsPersona)->get();
        }

        $query_all = $query_product->get()->sortBy('lead_id');

        $sorted = $query_all->unique("lead_id")->values();


        $data = DB::table('sales_lead_register')                   
                    ->joinSub(DB::table('tb_contact'), 'tb_contact_alias', function ($join) {
                        $join->on('tb_contact_alias.id_customer', '=', 'sales_lead_register.id_customer');
                    })
                    ->Leftjoin('users as sales','sales.nik','=','sales_lead_register.nik')
                    ->select("sales_lead_register.lead_id","sales.name as name_sales","opp_name","amount","brand_name")
                    ->whereIn("sales_lead_register.lead_id",$sorted->pluck("lead_id"))->get();

        foreach($data as $sort){
            $tag_all = DB::table('tb_product_tag_relation')
                ->joinSub(DB::table('tb_product_tag'), 'tb_product_tag_alias', function ($join) {
                    $join->on('tb_product_tag_alias.id', '=', 'tb_product_tag_relation.id_product_tag');
                })
                ->joinSub(DB::table('tb_technology_tag'), 'tb_technology_tag_alias', function ($join) {
                    $join->on('tb_technology_tag_alias.id', '=', 'tb_product_tag_relation.id_technology_tag');
                })
                ->select('name_tech','name_product',DB::raw('(CASE WHEN (price is null) THEN 0 ELSE price END) AS price'))
                ->where('lead_id',$sort->lead_id)
                ->get();

            $sort->tag_tech = $tag_all->pluck('name_tech');
            $sort->tag_product = $tag_all->pluck('name_product');
            $sort->tag_price = $tag_all->pluck('price');

            $presales = DB::table('sales_solution_design')->where("lead_id",$sort->lead_id)->get()->pluck("nik");
            $sort->name_presales = implode("\n",DB::table("users")->whereIn("nik",$presales)
                            ->get()->pluck('name')->toArray());
        }

        $pdf = PDF::loadView('report.report_tag_pdf');
        return $pdf->download('report tagging'.date("d-m-Y").'.pdf');
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
            // if($request->id_territory == "OPERATION"){
            //     // $data->where('users.id_territory',$request->id_territory);
            //     $data->where('users.nik','=','100000000003');
            // } else {
                $data->where('users.id_territory',$request->id_territory);
            // }
        } else {
            $data->where('users.id_territory',Auth::User()->id_territory);
        }

        if (isset($request->id_customer)) {
            if ($request->id_customer != -1) {
                $data->where('sales_lead_register.id_customer', $request->id_customer);
            }
        }

        if (isset($request->nik_sales)) {
            if ($request->nik_sales != -1) {
                $data->where('sales_lead_register.nik','like','%'.$request->nik_sales.'%');
            }
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

        // if($request->id_territory == "OPERATION"){
        //     // $data->where('users.id_territory',$request->id_territory);

        // } else {
        //     $data->where('users.id_territory',$request->id_territory);
        // }
        
        if(isset($request->start_date) && isset($request->end_date)){
            $data->where('sales_lead_register.created_at', '>=', $request->start_date);
            $data->where('sales_lead_register.created_at', '<=', $request->end_date);
        } else {
            $data->whereYear('sales_lead_register.created_at',date("Y"));
        }

        if($request->id_territory == "OPERATION"){
            // $data->where('users.id_territory',$request->id_territory);
            // $data->where('users.nik','=','1100492050');
            // $data = $data->get()->map(function ($arr) {
            //     $arr['id_territory'] = "OPERATION";
            //     return $arr;
            // });
            $data->where('users.id_territory',$request->id_territory);
            return array("data" => $data->get());

        } else {
            $data->where('users.id_territory',$request->id_territory);
        return array("data" => $data->get());

        }

    
    }

    public function download_excel_presales_win(Request $request) {
        $spreadsheet = new Spreadsheet();

        $spreadsheet->removeSheetByIndex(0);
        $spreadsheet->addSheet(new Worksheet($spreadsheet,'Summary Lead Presales'));
        $summarySheet = $spreadsheet->setActiveSheetIndex(0);

        $summarySheet->mergeCells('A1:L1');
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

        $summarySheet->getStyle('A1:L1')->applyFromArray($titleStyle);
        $summarySheet->setCellValue('A1','Total Lead Register');

        $headerContent = ["No","Presales Name", "Total Initial", "Total Open", "Total Sd","Total Tp","Total Win","Total Lose", "Total Hold", "Total Cancel", "Total Spesial", "Total Lead"];
        $summarySheet->getStyle('A2:L2')->applyFromArray($headerStyle);
        $summarySheet->fromArray($headerContent,NULL,'A2');

        $summaryPresales = Sales2::join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
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
            ->where('sales_lead_register.year', $request->year)
            ->groupBy('sales_solution_design.nik')
            ->get();

        $summaryPresales->map(function($item,$key) use ($summarySheet){
            $summarySheet->fromArray(array_merge([$key + 1],array_values($item->toArray())),NULL,'A' . ($key + 3));
        });

        $summarySheet->getColumnDimension('A')->setAutoSize(true);
        $summarySheet->getColumnDimension('B')->setAutoSize(true);
        $summarySheet->getColumnDimension('C')->setAutoSize(true);
        $summarySheet->getColumnDimension('D')->setAutoSize(true);
        $summarySheet->getColumnDimension('E')->setAutoSize(true);
        $summarySheet->getColumnDimension('F')->setAutoSize(true);
        $summarySheet->getColumnDimension('G')->setAutoSize(true);
        $summarySheet->getColumnDimension('H')->setAutoSize(true);
        $summarySheet->getColumnDimension('I')->setAutoSize(true);
        $summarySheet->getColumnDimension('J')->setAutoSize(true);
        $summarySheet->getColumnDimension('K')->setAutoSize(true);
        $summarySheet->getColumnDimension('L')->setAutoSize(true);

        $detailPresales = Sales2::join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
            ->join('users as presales', 'presales.nik', '=', 'sales_solution_design.nik')
            ->join('users as sales', 'sales.nik', '=', 'sales_lead_register.nik')
            ->select(
                'sales_solution_design.lead_id', 
                'opp_name',
                'tb_contact.brand_name', 
                'sales.name',
                'sales_lead_register.amount',
                'result',
                DB::raw('`presales`.`name` AS `presales_name`'))
            ->where('presales.id_company', '1')
            ->where('sales_lead_register.year', $request->year)
            ->orderBy('result')
            ->get()->sortBy('presales_name')->groupBy('presales_name');

        // return $detailPresales;

        $indexSheet = 0;
        foreach ($detailPresales as $key => $item) {
            $spreadsheet->addSheet(new Worksheet($spreadsheet,$key));
            $detailSheet = $spreadsheet->setActiveSheetIndex($indexSheet + 1);

            $detailSheet->getStyle('A1:G1')->applyFromArray($titleStyle);
            $detailSheet->setCellValue('A1','Lead Register');
            $detailSheet->mergeCells('A1:G1');

            $headerContent = ["No", "Lead Id", "Customer", "Opp Name", "Owner", "Amount", "Status"];
            $detailSheet->getStyle('A2:G2')->applyFromArray($headerStyle);
            $detailSheet->fromArray($headerContent,NULL,'A2');

            foreach ($item as $key => $eachLead) {
                $eachLead->amount = number_format($eachLead->amount,2,",",".");
                $eachLead->result = ($eachLead->result == "" ? "OPEN" : $eachLead->result);
                $detailSheet->fromArray(array_merge([$key + 1],array_values($eachLead->toArray())),NULL,'A' . ($key + 3));
            }
            $detailSheet->getColumnDimension('H')->setVisible(false);
            $detailSheet->getColumnDimension('A')->setAutoSize(true);
            $detailSheet->getColumnDimension('B')->setAutoSize(true);
            $detailSheet->getColumnDimension('C')->setAutoSize(true);
            $detailSheet->getColumnDimension('D')->setAutoSize(true);
            $detailSheet->getColumnDimension('E')->setAutoSize(true);
            $detailSheet->getColumnDimension('F')->setAutoSize(true);
            $detailSheet->getColumnDimension('G')->setAutoSize(true);
            $indexSheet = $indexSheet + 1;
        }

        $fileName = 'Report Presales ' . date("d-m-Y") . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        
        $writer = new Xlsx($spreadsheet);
        ob_end_clean();
        return $writer->save("php://output");
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
            ->select('sales_solution_design.lead_id', 'opp_name', 'deal_price', DB::raw("(CASE WHEN (result = 'OPEN') THEN 'INITIAL' WHEN (result = '') THEN 'OPEN' WHEN (result = 'SD') THEN 'SD' WHEN (result = 'TP') THEN 'TP' WHEN (result = 'WIN') THEN 'WIN' WHEN( result = 'LOSE') THEN 'LOSE' WHEN( result = 'HOLD') THEN 'HOLD' WHEN( result = 'SPECIAL') THEN 'SPECIAL'END) as results"), 'sales_lead_register.amount', 'tb_contact.brand_name',  'sales.name')
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

        return view('report/record_authentication', compact('notif', 'notifOpen', 'notifsd', 'notiftp', 'notifClaim'))->with(['initView'=> $this->initMenuBase(),'feature_item'=>$this->RoleDynamic('record_log_history')]);
    
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
            $getlogin = HistoryAuth::join('users','users.nik','=','tb_history_auth.nik')
                    ->select('name','email','datetime','ip_address')
                    ->where('information','Log In')
                    ->orderBy('datetime','DESC');
        }else{
            $getlogin = HistoryAuth::join('users','users.nik','=','tb_history_auth.nik')
                    ->select('name','email','datetime','ip_address')
                    ->where('information','Log In')
                    ->where('tb_history_auth.nik',Auth::User()->nik)
                    ->orderBy('datetime','DESC');
        }
        $totalRecords = $getlogin->count();
        // Apply pagination
        $start = $request->input('start', 0);
        $length = $request->input('length', $request->length); // Number of records per page

        $data = $getlogin->skip($start)->take($length);

        return response()->json([
            'draw' => $request->input('draw'),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $data->get(),
            'length' => $length,
        ]);
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
            ->orWhere('id_territory','=','OPERATION')
            // ->orWhere('id_territory','=','OPERATION')
            ->get();

        return view('report/report_product', compact('territory_loop'))->with(['initView'=>$this->initMenuBase()]);
    }

    public function getTerritory(Request $request)
    {
         $territory_loop = DB::table("tb_territory")
            ->select(DB::raw('`code_ter` AS `id`,`id_territory` AS `text`'))
            ->where('id_territory','like','TERRITORY%')
            ->where('id_territory','!=','TERRITORY 6')
            ->get();

        return array("data"=>$territory_loop);
    }

    public function getreportproduct(Request $request)
    {
        $reportproduct = array("data" => ProductTagRelation::join('tb_product_tag','tb_product_tag.id','=','tb_product_tag_relation.id_product_tag')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_product_tag_relation.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->join('tb_territory','tb_territory.id_territory','=','users.id_territory')
                    ->select('tb_product_tag.name_product',
                        DB::raw("SUM(CASE WHEN (price is null) THEN 0 ELSE price END) as total_price"),
                        DB::raw("COUNT(tb_product_tag_relation.lead_id) as total_lead"),
                        DB::raw("count(case when `users`.`id_territory` = 'TERRITORY 1' AND price is not null then 0 end) as countTer1"),
                        DB::raw("count(case when `users`.`id_territory` = 'TERRITORY 2' AND price is not null then 0 end) as countTer2"),
                        DB::raw("count(case when `users`.`id_territory` = 'TERRITORY 3' AND price is not null then 0 end) as countTer3"),
                        DB::raw("count(case when `users`.`id_territory` = 'TERRITORY 4' AND price is not null then 0 end) as countTer4"),
                        DB::raw("count(case when `users`.`id_territory` = 'TERRITORY 5' AND price is not null then 0 end) as countTer5"),
                        DB::raw("count(case when `users`.`id_territory` = 'OPERATION' AND price is not null then 0 end) as countTerOp"),
                        DB::raw("SUM(IF(`users`.`id_territory`='TERRITORY 1',CASE WHEN (price is null) THEN 0 ELSE price END,0)) AS ter1_price"),
                        DB::raw("SUM(IF(`users`.`id_territory`='TERRITORY 2',CASE WHEN (price is null) THEN 0 ELSE price END,0)) AS ter2_price"),
                        DB::raw("SUM(IF(`users`.`id_territory`='TERRITORY 3',CASE WHEN (price is null) THEN 0 ELSE price END,0)) AS ter3_price"),
                        DB::raw("SUM(IF(`users`.`id_territory`='TERRITORY 4',CASE WHEN (price is null) THEN 0 ELSE price END,0)) AS ter4_price"),
                        DB::raw("SUM(IF(`users`.`id_territory`='TERRITORY 5',CASE WHEN (price is null) THEN 0 ELSE price END,0)) AS ter5_price"),
                        DB::raw("SUM(IF(`users`.`id_territory`='OPERATION',CASE WHEN (price is null) THEN 0 ELSE price END,0)) AS operation_price")

                    )
                    // ->groupBy('tb_product_tag.name_product')
                    ->where('sales_lead_register.created_at', '>=', Carbon::now()->startOfYear()->toDateTimeString())
                    ->where('sales_lead_register.created_at', '<=', Carbon::now()->endOfYear()->toDateTimeString())
                    ->groupBy('tb_product_tag.name_product')
                    ->orderBy('total_lead', 'desc')
                    ->orderBy('total_price', 'desc')
                    ->where('status_karyawan','!=','dummy')
                    // ->groupBy('tb_product_tag_relation.id_product_tag')
                    ->get());

        return $reportproduct;
    }

    public function getFilterProduct(Request $request)
    {
        $reportproduct = ProductTagRelation::join('tb_product_tag','tb_product_tag.id','=','tb_product_tag_relation.id_product_tag')
                    ->join('sales_lead_register','sales_lead_register.lead_id','=','tb_product_tag_relation.lead_id')
                    ->join('users','users.nik','=','sales_lead_register.nik')
                    ->join('tb_territory','tb_territory.id_territory','=','users.id_territory')
                    ->select('tb_product_tag.name_product',
                        DB::raw("SUM(price) as total_price"),
                        DB::raw("COUNT(tb_product_tag_relation.lead_id) as total_lead"),
                        DB::raw("count(case when `users`.`id_territory` = 'OPERATION' then 0 end) as countTerOp"),
                        DB::raw("count(case when `users`.`id_territory` = 'TERRITORY 1' then 0 end) as countTer1"),
                        DB::raw("count(case when `users`.`id_territory` = 'TERRITORY 2' then 0 end) as countTer2"),
                        DB::raw("count(case when `users`.`id_territory` = 'TERRITORY 3' then 0 end) as countTer3"),
                        DB::raw("count(case when `users`.`id_territory` = 'TERRITORY 4' then 0 end) as countTer4"),
                        DB::raw("count(case when `users`.`id_territory` = 'TERRITORY 5' then 0 end) as countTer5"),
                        DB::raw("SUM(IF(`users`.`id_territory`='OPERATION',price,0)) AS operation_price"),
                        DB::raw("SUM(IF(`users`.`id_territory`='TERRITORY 1',price,0)) AS ter1_price"),
                        DB::raw("SUM(IF(`users`.`id_territory`='TERRITORY 2',price,0)) AS ter2_price"),
                        DB::raw("SUM(IF(`users`.`id_territory`='TERRITORY 3',price,0)) AS ter3_price"),
                        DB::raw("SUM(IF(`users`.`id_territory`='TERRITORY 4',price,0)) AS ter4_price"),
                        DB::raw("SUM(IF(`users`.`id_territory`='TERRITORY 5',price,0)) AS ter5_price")
                    )
                    ->where('sales_lead_register.created_at', '>=', $request->start_date)
                    ->where('sales_lead_register.created_at', '<=', $request->end_date)
                    ->orderBy('total_lead', 'desc')
                    ->orderBy('total_price', 'desc')
                    ->where('status_karyawan','!=','dummy');
                    // ->orderBy('total_lead', 'desc');

        if (isset($request->name_product)) {
            $reportproduct->where('tb_product_tag.id',$request->name_product)->groupBy('tb_product_tag.name_product');
        }else{
            $reportproduct->groupBy('tb_product_tag.name_product');
        } 
        
        return array("data" => $reportproduct->get());
    }
}