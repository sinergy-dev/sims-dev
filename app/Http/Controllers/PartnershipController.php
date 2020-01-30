<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\Partnership;
use PDF;
use Excel;
use File;

class PartnershipController extends Controller
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


        $datas = DB::table('tb_partnership')
                        ->select('id_partnership', 'partner' , 'level', 'renewal_date', 'annual_fee', 'sales_target', 'sales_certification', 'engineer_certification', 'type', 'doc')
                        ->get();

        return view('DVG.partnership', compact('lead', 'total_ter','notif','notifOpen','notifsd','notiftp','id_pro', 'datas', 'notifClaim', 'notifc'));
    }

    public function store(Request $request)
    {
    	$tambah 						= new Partnership();
    	$tambah->type 					= $request['type'];
    	$tambah->partner 				= $request['partner'];
        $tambah->level                  = $request['level'];
    	$tambah->renewal_date 			= $request['renewal_date'];
    	$tambah->annual_fee 			= $request['annual_fee'];
    	$tambah->sales_target 			= $request['sales_target'];
    	$tambah->sales_certification 	= $request['sales_certification'];
    	$tambah->engineer_certification = $request['engineer_certification'];
    	$tambah->save();

        return redirect('/partnership')->with('success', 'Created Partnership Successfully!');
    }

    public function proses_upload(Request $request) {

        $id_partnership = $request['upload_id_partnership'];
        $doc = $request['upload_doc'];
        $doc_lama = public_path('public/pdfpart/').$doc;

        if($doc != NULL) {
            File::delete("public/pdfpart/", $doc_lama);
        }

        $update = Partnership::where('id_partnership', $id_partnership)->first();
                                $file               = $request['file'];
                                $fileName           = $file->getClientOriginalName();
                                $nameDoc            = $id_partnership.'_'.$fileName;
                                $request->file('file')->move("public/pdfpart/", $nameDoc);
                                $update->doc   = $nameDoc;
                                $update->update();
        
        return redirect()->back();

    }

    public function download_partnership($id) {
        
        $doc = DB::table('tb_partnership')
                    ->select('doc')
                    ->where('id_partnership', $id)
                    ->first();

        $file = public_path()."/public/pdfpart/".$doc->doc;

        return response()->file($file);
        // return response()->download($file, $doc->doc);

    }

    public function update(Request $request)
    {
    	$id = $request['edit_id'];

    	$update = Partnership::where('id_partnership', $id)->first();
    	$update->type = $request['edit_type'];
    	$update->partner = $request['edit_partner'];
        $update->level = $request['edit_level'];
    	$update->renewal_date = $request['edit_renewal_date'];
    	$update->annual_fee = $request['edit_annual_fee'];
    	$update->sales_target = $request['edit_sales_target'];
    	$update->sales_certification = $request['edit_sales_certification'];
    	$update->engineer_certification = $request['edit_engineer_certification'];
    	$update->update();

        return redirect('/partnership')->with('success', 'Update Successfully!');
    }

    public function destroy($id)
    {
    	$delete = Partnership::find($id);
    	$delete->delete();

        return redirect()->back()->with('alert', 'Deleted!');
    }

    public function downloadpdf()
    {
        $datas = DB::table('tb_partnership')
                    ->select('id_partnership', 'partner', 'renewal_date','level', 'annual_fee', 'sales_target', 'sales_certification', 'engineer_certification', 'type')
                    ->get();


        $pdf = PDF::loadView('DVG.partnership_pdf', compact('datas'));
        return $pdf->download('SIP Partnership Summary '.date("Y").'.pdf');
    }

    public function downloadExcel(Request $request)
    {
        $nama = 'SIP Partnership Summary '.date('Y');
        Excel::create($nama, function ($excel) use ($request) {
        $excel->sheet('SIP Partnership Summary', function ($sheet) use ($request) {
        
        $sheet->mergeCells('A1:I1');

       // $sheet->setAllBorders('thin');
        $sheet->row(1, function ($row) {
            $row->setFontFamily('Calibri');
            $row->setFontSize(11);
            $row->setAlignment('center');
            $row->setFontWeight('bold');
        });

        $sheet->row(1, array('SIP Partnership Summary'));

        $sheet->row(2, function ($row) {
            $row->setFontFamily('Calibri');
            $row->setFontSize(11);
            $row->setFontWeight('bold');
        });

        $datas = Partnership::select('id_partnership', 'partner', 'level',  'renewal_date', 'annual_fee', 'sales_target', 'sales_certification', 'engineer_certification', 'type')
                    ->get();

       // $sheet->appendRow(array_keys($datas[0]));
            $sheet->row($sheet->getHighestRow(), function ($row) {
                $row->setFontWeight('bold');
            });

             $datasheet = array();
             $datasheet[0]  =   array("No", "Type", "Partner", "Level", "Renewal Date", "Annual Fee",  "Sales Target", "Sales Certification", "Engineer Certification");
             $i=1;

            foreach ($datas as $data) {

               // $sheet->appendrow($data);
              $datasheet[$i] = array(
                            $i,
                            $data['type'],
                            $data['partner'],
                            $data['level'],
                            $data['renewal_date'],
                            $data['annual_fee'],
                            $data['sales_target'],
                            $data['sales_certification'],
                            $data['engineer_certification']
                        );
              
              $i++;
            }

            $sheet->fromArray($datasheet);
        });

        })->export('xls');
    }
}
