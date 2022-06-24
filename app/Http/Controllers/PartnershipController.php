<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\Partnership;
use PDF;
use Excel;
use File;
use App\User;
use App\PartnershipCertification;
use App\PartnershipImageCertificate;

use Intervention\Image\ImageManagerStatic as Image;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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

        $notifClaim = '';

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

        return view('DVG.partnership', compact('notif','notifOpen','notifsd','notiftp', 'datas', 'notifClaim', 'notifc'))->with(['initView'=> $this->initMenuBase(),'feature_item'=>$this->RoleDynamic('partnership')]);
    }

    public function detail($id)
    {
        $data = Partnership::select('id_partnership', 'partner', 'level', 'levelling', 'type', 'renewal_date', 'annual_fee', 'cam_name', 'cam_email', 'cam_phone', 'email_support', 'id_mitra', 'logo')->where('id_partnership', $id)->first();

        $sidebar_collapse = true;

        return view('DVG.partnership_detail', compact('data', 'sidebar_collapse'))->with(['initView'=> $this->initMenuBase(),'feature_item'=>$this->RoleDynamic('partnership')]);;
    }

    public function getUser()
    {
        $getUser = collect(User::join('role_user','role_user.user_id','=','users.nik')
            ->join('roles','role_user.role_id','=','roles.id')
            ->select(DB::raw('`users`.`nik` AS `id`,`users`.`name` AS `text`'))
            ->whereRaw("(`roles`.`group` = 'msm' OR `roles`.`group` = 'pmo' OR `roles`.`group` = 'sales' OR `roles`.`group` = 'presales' OR `roles`.`group` = 'DVG' OR `roles`.`group` = 'DPG')")
            ->where('status_karyawan', '!=', 'dummy')
            ->get());

        return array("data"=>$getUser);
    }

    public function store(Request $request)
    {
    	$tambah 						= new Partnership();
    	$tambah->type 					= $request['type'];
    	$tambah->partner 				= $request['partner'];
        $tambah->level                  = $request['level'];
    	$tambah->renewal_date 			= $request['renewal_date'];
    	$tambah->annual_fee 			= str_replace('.', '', $request['annual_fee']);;
    	$tambah->sales_target 			= $request['sales_target'];
    	$tambah->sales_certification 	= $request['sales_certification'];
    	$tambah->engineer_certification = $request['engineer_certification'];
        $tambah->levelling              = $request['levelling'];
        $tambah->cam_name               = $request['cam'];
        $tambah->cam_email              = $request['cam_email'];
        $tambah->cam_phone              = $request['cam_phone'];
        $tambah->email_support          = $request['email_support'];
        $tambah->id_mitra               = $request['id_mitra'];
    	$tambah->save();

        $lastid = Partnership::select('id_partnership')->orderBy('created_at', 'desc')->first();

        $count = count($request['cert_name']);

        for($i = 0; $i < $count; $i++){
            $data = array(
                'id_partnership'       => $lastid->id_partnership,
                'nik'                  => $request['cert_person'][$i],
                'level_certification'  => $request['cert_type'][$i],
                'name_certification'   => $request['cert_name'][$i],
            );
            $insertData[] = $data;
        }
        PartnershipCertification::insert($insertData);

        return redirect('/partnership')->with('success', 'Created Partnership Successfully!');
    }

    public function getDetailPartnership(Request $request)
    {
        $data = Partnership::select('partner', 'level', 'levelling', 'type', 'renewal_date', 'annual_fee', 'cam_name', 'cam_email', 'cam_phone', 'email_support', 'id_mitra')->where('id_partnership', $request->id)->first();

        return array("data" => $data);
    }

    public function addCertList(Request $request)
    {
        $count = count($request['cert_name']);

        for($i = 0; $i < $count; $i++){
            $data = array(
                'id_partnership'       => $request['id_partnership'],
                'nik'                  => $request['cert_person'][$i],
                'level_certification'  => $request['cert_type'][$i],
                'name_certification'   => $request['cert_name'][$i],
            );
            $insertData[] = $data;
        }
        PartnershipCertification::insert($insertData);

        return redirect()->back();
    }

    public function addCert(Request $request)
    {
        $id = Partnership::where('id_partnership', $request['id_partnership'])->first();

        $tambah = new PartnershipImageCertificate();
        $tambah->id_partnership = $id;
        $tambah->nik = Auth::User()->nik;

        $allowedfileExtension   = ['jpg','png', 'jpeg', 'JPG', 'PNG'];
        $file                   = $request->file('cert');
        $fileName               = $file->getClientOriginalName();
        $imageName              = $id.'_'.$fileName;
        $extension              = $file->getClientOriginalExtension();
        $check                  = in_array($extension,$allowedfileExtension);

        if ($check) {
            Image::make($file->getRealPath())->save('image/partnerCertificate/'.$imageName);

            $tambah->certificate = $fileName;
        } else {
            return redirect()->back()->with('alert','Oops! Only jpg, png');
        }

        $tambah->save();
        return redirect()->back();
    }

    public function updateCertPerson(Request $request)
    {
        $update = PartnershipCertification::where('id', $request->id_cert_edit)->first();
        $update->nik = $request['cert_user_edit'];
        $update->name_certification = $request['cert_name_edit'];
        $update->update();

        return redirect()->back();
    }

    public function deleteCertPerson(Request $request)
    {
        $delete = PartnershipCertification::where('id', $request->id);
        $delete->delete();

        return redirect()->back()->with('alert', 'Deleted!');
    }

    public function getListCert(Request $request)
    {
        $cert = Partnership::where('id_partnership', $request->id)->first();

        return array("data"=>$cert);
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
        // return $request['id_edit'];
    	$update                    = Partnership::where('id_partnership', $request['id_edit'])->first();
    	$update->type              = $request['type_edit'];
    	$update->partner           = $request['partner_edit'];
        $update->levelling         = $request['levelling_edit'];
        $update->level             = $request['level_edit'];
    	$update->renewal_date      = $request['renewal_edit'];
    	$update->annual_fee        = $request['annual_edit'];
        $update->cam_name          = $request['cam_edit'];
        $update->cam_phone         = $request['phone_edit'];
        $update->cam_email         = $request['email_edit'];
        $update->email_support     = $request['support_edit'];
    	$update->id_mitra          = $request['mitra_edit'];

        // return $request->file('imageUpload') != null ? 'true' : 'false';

        if ($request->file('fileupload') === null) {
            // $update->logo = $update->logo;  
        }else{

            $allowedfileExtension   = ['jpg','png', 'jpeg', 'JPG', 'PNG'];
            $file                   = $request->file('fileupload');
            $fileName               = $file->getClientOriginalName();
            $extension              = $file->getClientOriginalExtension();
            $check                  = in_array($extension,$allowedfileExtension);

            if ($check) {
                // Image::make($file->getRealPath())->save('image/logo_partnership/'.$fileName);
                $request->file('fileupload')->move("image/logo_partnership/", $fileName);
                $update->logo = $fileName;
            } else {
                return redirect()->back()->with('alert','Oops! Only jpg, png');
            }
        }

    	$update->update();

        // return redirect('/partnership')->with('success', 'Update Successfully!');
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

    public function downloadExcel(Request $request) {
        $spreadsheet = new Spreadsheet();

        $spreadsheet->removeSheetByIndex(0);
        $spreadsheet->addSheet(new Worksheet($spreadsheet,'SIP Partnership Summary'));
        $summarySheet = $spreadsheet->setActiveSheetIndex(0);

        $summarySheet->mergeCells('A1:I1');
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

        $summarySheet->getStyle('A1:I1')->applyFromArray($titleStyle);
        $summarySheet->setCellValue('A1','SIP Partnership Summary');

        $headerContent = ["No", "Type", "Partner", "Level", "Renewal Date", "Annual Fee",  "Sales Target", "Sales Certification", "Engineer Certification"];
        $summarySheet->getStyle('A2:I2')->applyFromArray($headerStyle);
        $summarySheet->fromArray($headerContent,NULL,'A2');

        $dataPartnership = Partnership::select('type', 'partner', 'level',  'renewal_date', 'annual_fee', 'sales_target', 'sales_certification', 'engineer_certification')
            ->get();

        $dataPartnership->map(function($item,$key) use ($summarySheet){
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

        $fileName = 'SIP Partnership Summary ' . date("Y") . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        
        $writer = new Xlsx($spreadsheet);
        return $writer->save("php://output");
    }
}
