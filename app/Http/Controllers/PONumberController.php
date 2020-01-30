<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PONumber;
use DB;
use Auth;
use Excel;

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
                        ->select('tb_po.no','tb_po.no_po', 'tb_po.position', 'tb_po.type_of_letter', 'tb_po.month', 'tb_po.date', 'tb_po.to', 'tb_po.attention', 'tb_po.title', 'tb_po.project', 'tb_po.description', 'tb_po.from', 'tb_po.division', 'tb_po.issuance', 'tb_po.project_id', 'tb_po.note', 'users.name', 'no_pr')
                        ->where('date','like',$tahun."%")
                        ->get();

        $no_pr = DB::table('tb_pr')->select('no_pr')->where('date','like',$tahun."%")->get();

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

        $sidebar_collapse = true;

        return view('admin/po', compact('lead', 'total_ter','notif','notifOpen','notifsd','notiftp','id_pro', 'datas', 'notifClaim','pops', 'sidebar_collapse', 'no_pr'));
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
	        $month_pr = substr($request['date'],5,2);
	        $year_pr = substr($request['date'],0,4);

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

	        $no = $akhirnomor.'/'.'FA'.'/'. $type.'/' . $bln .'/'. $year_pr;
	        $nom = PONumber::select('no')->orderBy('created_at','desc')->first();


	        $tambah = new PONumber();
	        $tambah->no = $nom->no + 1;
	        $tambah->no_po = $no;
	        $tambah->position = 'FA';
	        $tambah->type_of_letter = $type;
	        $tambah->month = $bln;
	        $tambah->date = $request['date'];
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
	        $tambah->save();

	        return redirect('po')->with('success', 'Created Purchase Order Successfully!');
        } else {
        	$type = 'PO';
	        $month_pr = substr($request['date'],5,2);
	        $year_pr = substr($request['date'],0,4);

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

	        $no = $akhirnomor.'/'.'FA'.'/'. $type.'/' . $bln .'/'. $year_pr;

	        $tambah = new PONumber();
	        $tambah->no = $getnumbers->no+1;
	        $tambah->no_po = $no;
	        $tambah->position = 'FA';
	        $tambah->type_of_letter = $type;
	        $tambah->month = $bln;
	        $tambah->date = $request['date'];
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
	        $tambah->save();

	        return redirect('po')->with('success', 'Created Purchase Order Successfully!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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

    public function downloadExcelPO(Request $request)
    {
        $nama = 'Daftar Buku Admin (PO) '.date('Y');
        Excel::create($nama, function ($excel) use ($request) {
        $excel->sheet('Purchase Order', function ($sheet) use ($request) {
        
        $sheet->mergeCells('A1:O1');

       // $sheet->setAllBorders('thin');
        $sheet->row(1, function ($row) {
            $row->setFontFamily('Calibri');
            $row->setFontSize(11);
            $row->setAlignment('center');
            $row->setFontWeight('bold');
        });

        $sheet->row(1, array('REKAP PURCHASE ORDER'));

        $sheet->row(2, function ($row) {
            $row->setFontFamily('Calibri');
            $row->setFontSize(11);
            $row->setFontWeight('bold');
        });

        $datas = PONumber::join('users', 'users.nik', '=', 'tb_po.from')
                    ->select('no','no_po', 'position', 'type_of_letter', 'month', 'date', 'to', 'attention', 'title', 'project', 'description', 'division', 'issuance', 'project_id','name')
                    ->get();

       // $sheet->appendRow(array_keys($datas[0]));
            $sheet->row($sheet->getHighestRow(), function ($row) {
                $row->setFontWeight('bold');
            });

             $datasheet = array();
             $datasheet[0]  =   array("No", "No Letter", "Position", "Type of Letter", "Month",  "Date", "To", "Attention", "Title", "Project", "Description", "From", "Division", "Issuance", "Project ID");
             $i=1;

            foreach ($datas as $data) {

               // $sheet->appendrow($data);
              $datasheet[$i] = array(
                            $i,
                            $data['no_po'],
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
                            $data['issuance'],
                            $data['project_id']
                        );
              
              $i++;
            }

            $sheet->fromArray($datasheet);
        });

        })->export('xls');
    }
}
