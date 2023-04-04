<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PONumber;
use DB;
use Auth;
// use Excel;
use App\PR;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PONumberController extends Controller
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

        $pops = PONumber::select('no_po')->orderBy('created_at','desc')->first();

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

        $datas = DB::table('tb_po')
                        ->join('users','users.nik','=','tb_po.from')
                        ->join('tb_pr', 'tb_pr.no', '=', 'tb_po.no_pr', 'left')
                        ->select('tb_po.no','tb_po.no_po', 'tb_po.position', 'tb_po.type_of_letter', 'tb_po.month', 'tb_po.date', 'tb_po.to', 'tb_po.attention', 'tb_po.title', 'tb_po.project', 'tb_po.description', 'tb_po.from', 'tb_po.division', 'tb_po.issuance', 'tb_po.project_id', 'tb_po.note', 'users.name as from_name', 'tb_pr.no_pr')
                        ->where('tb_po.date','like',$tahun."%")
                        ->get();

        $no_pr = DB::table('tb_pr')->select('no_pr', 'to', 'no')->where('date','like',$tahun."%")->where('result', '!=', 'used')->orderBy('created_at', 'desc')->get();

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

        $year_before = PONumber::select(DB::raw('YEAR(created_at) year'))->orderBy('year','desc')->groupBy('year')->get();

        return view('admin/po', compact('notif','notifOpen','notifsd','notiftp', 'datas', 'notifClaim','pops', 'sidebar_collapse', 'no_pr','tahun','year_before'))->with(['initView'=> $this->initMenuBase(),'feature_item'=>$this->RoleDynamic('po')]);
    }

    public function getPRNumber(){
        $tahun = Date('Y');

        return array(DB::table('tb_pr')->select('no_pr', 'to', 'no')->where('date','like',$tahun."%")->where('result', '!=', 'used')->orderBy('created_at', 'desc')->get());
        // return array(DB::table('tb_pr')->select('no_pr', 'to', 'no')->where('date','like','2020'."%")->orWhere('date','like',$tahun."%")->where('result', '!=', 'used')->orderBy('created_at', 'desc')->get());
    }

    public function getdatapr(Request $request)
    {
        $cek_pro  = PR::select('project_id')->where('no',$request->data)->first();

        return array(DB::table('tb_pr')
            ->join('users', 'users.nik', '=', 'tb_pr.from')
            ->select('no','no_pr', 'position', 'type_of_letter', 'month', 'date', 'to', 'attention', 'title', 'project', 'description', 'name', 'users.name as issuance_name', 'project_id', 'division')
            ->where('no',$request->data)
            ->get(),$request->data);
        
    }

    public function getdatapo(Request $request)
    {
        $tahun = date("Y"); 

        return array("data" => PONumber::join('users','users.nik','=','tb_po.from')
                        ->join('tb_pr', 'tb_pr.no', '=', 'tb_po.no_pr', 'left')
                        ->select('tb_po.no','tb_po.no_po', 'tb_po.position', 'tb_po.type_of_letter', 'tb_po.month', 'tb_po.date', 'tb_po.to', 'tb_po.attention', 'tb_po.title', 'tb_po.project', 'tb_po.description', 'tb_po.from', 'tb_po.division', 'tb_po.issuance', 'tb_po.project_id', 'tb_po.note', 'users.name as from_name', 'tb_pr.no_pr')
                        ->where('tb_po.date','like',$tahun."%")
                        ->get());
    }

    public function getfilteryear(Request $request)
    {
        $tahun = date("Y"); 

        return array("data" => PONumber::join('users','users.nik','=','tb_po.from')
                        ->join('tb_pr', 'tb_pr.no', '=', 'tb_po.no_pr', 'left')
                        ->select('tb_po.no','tb_po.no_po', 'tb_po.position', 'tb_po.type_of_letter', 'tb_po.month', 'tb_po.date', 'tb_po.to', 'tb_po.attention', 'tb_po.title', 'tb_po.project', 'tb_po.description', 'tb_po.from', 'tb_po.division', 'tb_po.issuance', 'tb_po.project_id', 'tb_po.note', 'users.name as from_name', 'tb_pr.no_pr')
                        ->whereYear('tb_po.created_at', $request->data)
                        ->get());
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

    	$tahun = date("Y");
    	$cek = DB::table('tb_po')
                ->where('date','like',$tahun."%")
                ->count('no');

        if ($cek > 0) {
        	$type = 'PO';
	        
            $edate = strtotime($_POST['date']); 
            $edate = date("Y-m-d",$edate);

            $month_po = substr($edate,5,2);
            $year_po = substr($edate,0,4);

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
	        $bln = $array_bln[$month_po];

	        $getnumber = PONumber::orderBy('no', 'desc')->where('date','like',$tahun."%")->count();

	        $getnumbers = PONumber::orderBy('no', 'desc')->first();

	        if($getnumber < 1){
	            $getlastnumber = 1;
	            $lastnumber = $getlastnumber;
	        } else{
	            $lastnumber = $getnumber+1;
	        }

	        if($lastnumber < 10){
	           $akhirnomor = '00' . $lastnumber;
	        }elseif($lastnumber > 9 && $lastnumber < 100){
	           $akhirnomor = '0' . $lastnumber;
	        }elseif($lastnumber >= 100){
	           $akhirnomor = $lastnumber;
	        }

	        $no = $akhirnomor.'/'.'FA'.'/'. $type.'/' . $bln .'/'. $year_po;
	        $nom = PONumber::select('no')->orderBy('created_at','desc')->first();


	        $tambah = new PONumber();
	        $tambah->no = $nom->no + 1;
	        $tambah->no_po = $no;
	        $tambah->position = 'FA';
	        $tambah->type_of_letter = $type;
	        $tambah->month = $bln;
	        $tambah->date = $edate;
	        $tambah->to = $request['to'];
	        $tambah->attention = $request['attention'];
	        $tambah->title = $request['title'];
	        $tambah->project = $request['project'];
	        $tambah->description = $request['description'];
	        $tambah->from = Auth::User()->nik;
	        $tambah->division = $request['division'];
	        $tambah->issuance = $request['issuance'];
	        $tambah->project_id = $request['project_id'];
            $tambah->no_pr = $request['no_pr'];
            $tambah->status = 'N';
	        $tambah->save();

            $update_no_pr = PR::where('no', $request->no_pr)->first();
            $update_no_pr->result = 'used';
            $update_no_pr->update();

	        return redirect('po')->with('success', 'Created Purchase Order Successfully!');
        } else {
        	$type = 'PO';
	        $edate = strtotime($_POST['date']); 
            $edate = date("Y-m-d",$edate);

            $month_po = substr($edate,5,2);
            $year_po = substr($edate,0,4);

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
	        $bln = $array_bln[$month_po];

	        $getnumber = PONumber::orderBy('no', 'desc')->where('date','like',$tahun."%")->count();

	        $getnumbers = PONumber::orderBy('no', 'desc')->first();

	        if($getnumber == NULL){
	            $getlastnumber = 1;
	            $lastnumber = $getlastnumber;
	        } else{
	            $lastnumber = $getnumber+1;
	        }

	        if($lastnumber < 10){
	           $akhirnomor = '00' . $lastnumber;
	        }elseif($lastnumber > 9 && $lastnumber < 100){
	           $akhirnomor = '0' . $lastnumber;
	        }elseif($lastnumber >= 100){
	           $akhirnomor = $lastnumber;
	        }

	        $no = $akhirnomor.'/'.'FA'.'/'. $type.'/' . $bln .'/'. $year_po;

	        $tambah = new PONumber();
	        $tambah->no = $getnumbers->no+1;
	        $tambah->no_po = $no;
	        $tambah->position = 'FA';
	        $tambah->type_of_letter = $type;
	        $tambah->month = $bln;
	        $tambah->date = $edate;
	        $tambah->to = $request['to'];
	        $tambah->attention = $request['attention'];
	        $tambah->title = $request['title'];
	        $tambah->project = $request['project'];
	        $tambah->description = $request['description'];
	        $tambah->from = Auth::User()->nik;
	        $tambah->division = $request['division'];
	        $tambah->issuance = $request['issuance'];
	        $tambah->project_id = $request['project_id'];
            $tambah->no_pr = $request['no_pr'];
            $tambah->status = 'N';
	        $tambah->save();

            $update_no_pr = PR::where('no', $request->no_pr)->first();
            $update_no_pr->result = 'used';
            $update_no_pr->update();

	        return redirect('po')->with('success', 'Created Purchase Order Successfully!');
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $no = $request['edit_no_po'];

        $update = PONumber::where('no',$no)->first();
        $update->to = $request['edit_to'];
        $update->attention = $request['edit_attention'];
        $update->title = $request['edit_title'];
        $update->project = $request['edit_project'];
        $update->description = $request['edit_description'];
        $update->issuance = $request['edit_issuance'];
        $update->project_id = $request['edit_project_id'];
        $update->division = $request['edit_division'];
        $update->note = $request['edit_note'];

        $update->update();

        return redirect('po')->with('update', 'Updated Purchase Order Data Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($no)
    {
        $hapus = PONumber::find($no);
        $hapus->delete();

        return redirect('po')->with('alert', 'Deleted!');
    }

    public function downloadExcelPO(Request $request) {
        $spreadsheet = new Spreadsheet();

        $prSheet = new Worksheet($spreadsheet,'Purchase Order');
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
        $sheet->setCellValue('A1','REKAP PURCHASE ORDER');

        $headerStyle = $normalStyle;
        $headerStyle['font']['bold'] = true;
        $sheet->getStyle('A2:O2')->applyFromArray($headerStyle);;

        $headerContent = ["No", "No Letter", "Position", "Type of Letter", "Month",  "Date", "To", "Attention", "Title", "Project", "Description", "From", "Division", "Issuance", "Project ID"];
        $sheet->fromArray($headerContent,NULL,'A2');

        $dataPO = PONumber::join('users', 'users.nik', '=', 'tb_po.from')
            ->select('no_po','position','type_of_letter','month','date','to','attention','title','project','description','name','division','issuance','project_id')
            ->whereYear('tb_po.created_at', $request->year)
            ->get();

        $dataPO->map(function($item,$key) use ($sheet){
            $sheet->fromArray(array_merge([$key + 1],array_values($item->toArray())),NULL,'A' . ($key + 3));
        });

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setWidth(25);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getColumnDimension('J')->setWidth(25);
        $sheet->getColumnDimension('K')->setWidth(25);
        $sheet->getColumnDimension('L')->setAutoSize(true);
        $sheet->getColumnDimension('M')->setAutoSize(true);
        $sheet->getColumnDimension('N')->setWidth(25);
        $sheet->getColumnDimension('O')->setWidth(25);

        $fileName = 'Daftar Buku Admin (PO) ' . date('Y') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        
        $writer = new Xlsx($spreadsheet);
        ob_end_clean();
        return $writer->save("php://output");
    }
}
