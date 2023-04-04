<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use App\HRNumber;
use Illuminate\Support\Facades\Route;
use Excel;
use Validator;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class HRNumberController extends Controller
{
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

        $pops = HRNumber::select('no_letter')->orderBy('created_at','desc')->first();

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
        } else {
            $notifClaim = DB::table('dvg_esm')
                            ->select('nik_admin', 'personnel', 'type')
                            ->where('status', 'FINANCE')
                            ->get();
        }

        $sidebar_collapse = true;

        $year = date("Y");

        $year_before = HRNumber::select(DB::raw('YEAR(created_at) year'))->orderBy('year','desc')->groupBy('year')->get();

        $tahun = HRNumber::select('created_at')->whereYear('created_at', $year)->groupBy('created_at')->get();

        return view('admin/hr_number', compact('notif','notifOpen','notifsd','notiftp', 'notifClaim','pops', 'sidebar_collapse', 'tahun','year','year_before'))->with(['initView'=> $this->initMenuBase()]);
    }


    public function getdata(Request $request)
    {
        $tahun = date("Y");

        return array("data" => HRNumber::join('users', 'users.nik', '=', 'tb_hr_number.from')
                        ->select('no','no_letter', 'type_of_letter', 'divsion', 'pt', 'month', 'date', 'to', 'attention', 'title', 'project', 'description', 'from', 'division', 'project_id', 'name', 'note')
                        ->where('date','like',$tahun."%")
                        ->get());
    }

    public function getfilteryear(Request $request)
    {
        return array("data" => HRNumber::join('users', 'users.nik', '=', 'tb_hr_number.from')
                        ->select('no','no_letter', 'type_of_letter', 'divsion', 'pt', 'month', 'date', 'to', 'attention', 'title', 'project', 'description', 'from', 'division', 'project_id', 'name', 'note')
                        // ->where('date','like',$request->year."%")
                        ->whereYear('tb_hr_number.created_at', $request->data)
                        ->get());
    }


    public function store(Request $request)
    {
        $tahun = date("Y");
        $cek = DB::table('tb_hr_number')
                ->where('date','like',$tahun."%")
                ->count('no');

        $type = $request['type'];
        $divisi = 'HR';
        
        $edate = strtotime($_POST['date']); 
        $edate = date("Y-m-d",$edate);

        $month_hr = substr($edate,5,2);
        $year_hr = substr($edate,0,4);

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
        $bln = $array_bln[$month_hr];

        $getnumber = HRNumber::orderBy('no', 'desc')->where('date','like',$tahun."%")->count();

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
        }elseif($lastnumber >= 100 && $lastnumber < 1000){
           $akhirnomor = '0' . $lastnumber;
        } elseif ($lastnumber >= 1000) {
            $akhirnomor = $lastnumber;
        }

        if ($type == 'Legal') {
            $no = $akhirnomor.'/SIP/'. $type.'/' . $bln .'/'. $year_hr;
        } else {
            $no = $akhirnomor.'/'.$divisi .'/'. $type.'/' . $bln .'/'. $year_hr;
        }

        $nom = HRNumber::select('no')->orderBy('no','desc')->first();

        $tambah = new HRNumber();
        $tambah->no = $nom->no+1;
        $tambah->no_letter = $no;
        $tambah->type_of_letter = $type;
        $tambah->divsion = $divisi;
        $tambah->pt = $request['pt'];
        $tambah->month = $bln;
        $tambah->date = $edate;
        $tambah->to = $request['to'];
        $tambah->attention = $request['attention'];
        $tambah->title = $request['title'];
        $tambah->project = $request['project'];
        $tambah->description = $request['description'];
        $tambah->from = Auth::User()->nik;
        $tambah->division = $request['division'];
        // $tambah->project_id = $request['project_id'];
        $tambah->save();

        return redirect('admin_hr')->with('success', 'Success!');    	
    }

    public function update(Request $request)
    {
    	$no = $request['edit_no_letter'];

        $edate = strtotime($_POST['edit_date']); 
        $edate = date("Y-m-d",$edate);
        $type = $request['edit_type'];

        $month_letter = substr($edate,5,2);
        $year_letter = substr($edate,0,4);

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
        $bln = $array_bln[$month_letter];

        $getno = HRNumber::where('no', $no)->first()->no_letter;
        $getnumberLetter =  explode("/",$getno)[0];

        // $no_update = $getnumberLetter.'/HR/' . $type . '/' . $bln .'/'. $year_letter;

        if ($type == 'Legal') {
            $no_update = $getnumberLetter.'/SIP/' . $type . '/' . $bln .'/'. $year_letter;
        } else {
            $no_update = $getnumberLetter.'/HR/' . $type . '/' . $bln .'/'. $year_letter;
        }

        $update = HRNumber::where('no',$no)->first();
        $update->to = $request['edit_to'];  
        $update->no_letter = $no_update;
        $update->pt = $request['edit_company'];
        $update->type_of_letter = $type;
        $update->month = $bln;
        $update->date = $edate;
        $update->attention = $request['edit_attention'];
        $update->title = $request['edit_title'];
        $update->project = $request['edit_project'];
        $update->description = $request['edit_description'];
        $update->note = $request['edit_note'];

        $update->update();

        return redirect('admin_hr')->with('update', 'Success!');
    }

    public function destroy($no)
    {
        $hapus = HRNumber::find($no);
        $hapus->delete();

        return redirect('admin_hr')->with('alert', 'Deleted!');
    }

    public function downloadExcelAdminHR(Request $request)
    {
        $spreadsheet = new Spreadsheet();

        $prSheet = new Worksheet($spreadsheet,'Penomoran HR');
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
        $titleStyle['font']['bold'] = true;

        $sheet->getStyle('A1:O1')->applyFromArray($titleStyle);
        $sheet->setCellValue('A1','Penomoran HR');

        $headerStyle = $normalStyle;
        $headerStyle['font']['bold'] = true;
        $sheet->getStyle('A2:O2')->applyFromArray($headerStyle);;

        $headerContent = ["No", "No Letter", "Type of Letter", "Division", "PT", "Month",  "Date", "To" , "Attention", "Title", "Project", "Description", "From", "Division", "Id Project"];
        $sheet->fromArray($headerContent,NULL,'A2');

        $dataHR = HRNumber::join('users', 'users.nik', '=', 'tb_hr_number.from')
            ->select('no_letter','type_of_letter','divsion','pt','month','date','to','attention','title','project','description','name','division','project_id')
            ->whereYear('tb_hr_number.created_at', $request->year)
            ->get();;

        $dataHR = $dataHR->map(function($item,$key) use ($sheet){
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
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getColumnDimension('J')->setAutoSize(true);
        $sheet->getColumnDimension('K')->setAutoSize(true);
        $sheet->getColumnDimension('L')->setAutoSize(true);
        $sheet->getColumnDimension('M')->setAutoSize(true);
        $sheet->getColumnDimension('N')->setAutoSize(true);
        $sheet->getColumnDimension('O')->setAutoSize(true);

        $fileName = 'Daftar Buku Admin (HR) ' . date('Y') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        
        $writer = new Xlsx($spreadsheet);
        ob_end_clean();
        return $writer->save("php://output");

    }

}
