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
use App\PartnershipTarget;
use App\PartnershipTechnology;
use App\PartnershipLog;

use Intervention\Image\ImageManagerStatic as Image;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

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

    public function getDataPartnership()
    {
        $data = Partnership::get();

        return array("data" => $data);
    }

    public function getSearchDataPartnership()
    {
        $data = Partnership::select('id_partnership', 'partner' , 'level', 'renewal_date', 'annual_fee', 'type', 'doc');

        $searchFields = ['id_partnership', 'partner' , 'level', 'renewal_date', 'annual_fee', 'type', 'doc'];
        if($request->search != ""){
            $data->where(function($data) use($request, $searchFields){
                $searchWildCard = '%'. $request->search . '%';
                foreach ($searchFields as $data) {
                    $data->orWhere($data, 'LIKE', $searchWildCard);
                }
            });
        }

        return array("data" => $data->get());
    }

    public function detail($id)
    {
        $getListTech = DB::table('tb_partnership_technology')->join('tb_technology_tag', 'tb_partnership_technology.technology', '=', 'tb_technology_tag.id')
                        ->select('id_partnership', DB::raw('GROUP_CONCAT(`tb_partnership_technology`.`technology`) AS `id_tech`'), DB::raw('GROUP_CONCAT(`tb_technology_tag`.`name_tech`) AS `name_tech`'))
                        ->groupBy('id_partnership');

        $data = Partnership::leftJoinSub($getListTech, 'tech_tag', function($join){
                    $join->on('tb_partnership.id_partnership', '=', 'tech_tag.id_partnership');
                })
                ->select('tb_partnership.id_partnership', 'partner', 'level', 'levelling', 'type', 'renewal_date', 'annual_fee', 'cam_name', 'cam_email', 'cam_phone', 'email_support', 'id_mitra', 'logo', 'id_tech', 'portal_partner', 'name_tech', 'badge')->where('tb_partnership.id_partnership', $id)->first();

        $sidebar_collapse = true;

        return view('DVG.partnership_detail', compact('data', 'sidebar_collapse'))->with(['initView'=> $this->initMenuBase(),'feature_item'=>$this->RoleDynamic('partnership')]);;
    }

    public function getUser()
    {
        $getUser = collect(User::join('role_user','role_user.user_id','=','users.nik')
            ->join('roles','role_user.role_id','=','roles.id')
            ->select(DB::raw('`users`.`name` AS `id`,`users`.`name` AS `text`'))
            ->whereRaw("(`roles`.`group` = 'msm' OR `roles`.`group` = 'pmo' OR `roles`.`group` = 'sales' OR `roles`.`group` = 'presales' OR `roles`.`group` = 'BCD' OR `roles`.`group` = 'DPG')")
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
        $tambah->portal_partner         = $request['portal_partner'];
    	$tambah->save();

        $lastid = Partnership::select('id_partnership')->orderBy('created_at', 'desc')->first();

        $tambah_log = new PartnershipLog;
        $tambah_log->nik = Auth::User()->nik;
        $tambah_log->id_partnership = $lastid->id_partnership;
        $tambah_log->description = 'Create New Partnership ' . $tambah->partner;
        $tambah_log->save();

        if(isset($request->tagData["tagCertList"])){
            foreach ($request->tagData["tagCertList"] as $key => $value) {
                $store = new PartnershipCertification;
                $store->id_partnership = $lastid->id_partnership;
                $store->name = $value['cert_person'];
                $store->level_certification = $value['cert_type'];
                $store->name_certification = $value['cert_name'];
                $store->save(); 

                $tambah_log = new PartnershipLog;
                $tambah_log->nik = Auth::User()->nik;
                $tambah_log->id_partnership = $lastid->id_partnership;
                $tambah_log->description = 'Create New Partnership with Certification '. $value['cert_type'] . ' ' . $value['cert_name'] . ' and Name ' . $value['cert_person'];
                $tambah_log->save();
            }
        }

        if(isset($request->tagData["tagSalesTarget"])){
            foreach ($request->tagData["tagSalesTarget"] as $key => $value) {
                $store = new PartnershipTarget;
                $store->id_partnership = $lastid->id_partnership;
                $store->target = $value['sales_target'];
                // $store->description = $value['description'];
                $store->countable = $value['countable'];
                $store->save(); 

                $tambah_log = new PartnershipLog;
                $tambah_log->nik = Auth::User()->nik;
                $tambah_log->id_partnership = $lastid->id_partnership;
                $tambah_log->description = 'Create New Partnership with Target ' . $value['sales_target'] . ' and ' . $value['countable'];
                $tambah_log->save();
            }
        }

        if(isset($request['id_technology'])){
            foreach ($request['id_technology'] as $key => $value) {
                $store = new PartnershipTechnology;
                $store->id_partnership = $lastid->id_partnership;
                $store->technology = $value;
                $store->save(); 
            }
        }

        $id = $lastid->id_partnership;
        return $id;
        // return redirect('/partnership')->with('success', 'Created Partnership Successfully!');
        // return redirect('/partnership')->with('success', $id);
    }


    public function getDataLog(Request $request)
    {
        $data = PartnershipLog::join('users', 'users.nik', '=', 'tb_partnership_log.nik')->select('description', 'tb_partnership_log.created_at', 'name')->where('id_partnership', $request->id_partnership)->orderby('id', 'desc')->get();
        return array("data" => $data);
    }

    public function getDetailPartnership(Request $request)
    {
        $getListTech = DB::table('tb_partnership_technology')->join('tb_technology_tag', 'tb_partnership_technology.technology', '=', 'tb_technology_tag.id')
                        ->select('id_partnership', DB::raw('GROUP_CONCAT(`tb_partnership_technology`.`technology`) AS `id_tech`'),  DB::raw('GROUP_CONCAT(`tb_technology_tag`.`name_tech`) AS `name_tech`'))
                        ->groupBy('id_partnership');

        $data = Partnership::leftJoinSub($getListTech, 'tech_tag', function($join){
                    $join->on('tb_partnership.id_partnership', '=', 'tech_tag.id_partnership');
                })
                ->select('partner', 'level', 'levelling', 'type', 'renewal_date', 'annual_fee', 'cam_name', 'cam_email', 'cam_phone', 'email_support', 'id_mitra', 'portal_partner', 'id_tech', 'name_tech')->where('tb_partnership.id_partnership', $request->id)->get();

        return array("data" => $data);
    }

    public function addCertList(Request $request)
    {
        $id_partnership = $request['id_partnership'];
        $count = count(json_decode($request->engData,true));
        if(isset($request->engData)){
            $dataAll = json_decode($request->engData,true);
            foreach ($dataAll as $data) {
                $store = new PartnershipCertification;
                $store->id_partnership      = $id_partnership;
                $store->name                = $data['cert_person'];
                $store->level_certification = $data['cert_type'];
                $store->name_certification  = $data['cert_name'];
                $store->expired_date        = $data['expired_date'];
                if ($request->file('imageData') === null) {
                }else{
                    $allowedfileExtension   = ['jpg','png', 'jpeg', 'JPG', 'PNG', 'pdf', 'PDF'];
                    $file                   = $request->file('imageData');
                    $fileName               = $file->getClientOriginalName();
                    $nameDoc                = 'certificate_engineer_' . $id_partnership . '_' . $fileName;
                    $extension              = $file->getClientOriginalExtension();
                    $check                  = in_array($extension,$allowedfileExtension);

                    if ($check) {
                        $request->file('imageData')->move("image/certificate_engineer/", $nameDoc);
                        $store->certificate         = $nameDoc;
                    } else {
                        return redirect()->back()->with('alert','Oops! Only jpg, png, pdf');
                    }
                }
                $store->save();
                
                $tambah_log = new PartnershipLog;
                $tambah_log->nik = Auth::User()->nik;
                $tambah_log->id_partnership = $id_partnership;
                $tambah_log->description = 'Create New Partnership with Certification '. $data['cert_type'] . ' ' . $data['cert_name'] . ' and Name ' . $data['cert_person'];
                $tambah_log->save();
            }
        }
        return redirect()->back();
    }

    public function addCert(Request $request)
    {

        $tambah = new PartnershipImageCertificate();
        $tambah->id_partnership = $request['idCertPartner'];
        $tambah->title = $request['inputTitleCert'];

        // return $request->file('imgCertPartner');

        if ($request->file('imgCertPartner') === null) {
            // $update->logo = $update->logo;  
        }else{

            $allowedfileExtension   = ['jpg','png', 'jpeg', 'JPG', 'PNG', 'pdf', 'PDF'];
            $file                   = $request->file('imgCertPartner');
            $fileName               = $file->getClientOriginalName();
            $extension              = $file->getClientOriginalExtension();
            $check                  = in_array($extension,$allowedfileExtension);

            if ($check) {
                // Image::make($file->getRealPath())->save('image/logo_partnership/'.$fileName);
                $request->file('imgCertPartner')->move("image/cert_partnership/", $fileName);
                $tambah->certificate = $fileName;
            } else {
                return redirect()->back()->with('alert','Oops! Only jpg, png');
            }
        }

        $tambah->save();

        $tambah_log = new PartnershipLog;
        $tambah_log->nik = Auth::User()->nik;
        $tambah_log->id_partnership = $request['idCertPartner'];
        $tambah_log->description = 'Add New Certificate with Title ' . $request['inputTitleCert'];
        $tambah_log->save();

        return redirect()->back();
    }

    public function getTargetPartnership(Request $request)
    {
        $data = PartnershipTarget::select('target', 'countable', 'description', 'status', 'id_partnership', 'id')->where('id_partnership', $request->id_partnership)->orderby('status', 'desc')->get();

        return array("data" => $data);
    }

    public function getTargetById(Request $request)
    {
        $data = PartnershipTarget::select('target', 'countable', 'description', 'status', 'id_partnership', 'id')->where('id', $request->id)->first();

        return array("data" => $data);   
    }

    public function getCertPartner(Request $request)
    {
        $data = PartnershipImageCertificate::select('title', 'certificate', 'id')->where('id_partnership', $request->id_partnership)->get();
        return array("data" => $data);
    }

    public function store_target(Request $request)
    {
        $store = new PartnershipTarget;
        $store->id_partnership = $request->id_partnership;
        $store->target = $request->target;
        $store->countable = $request->countable;
        $store->status = $request->status;
        $store->save();

        $tambah_log = new PartnershipLog;
        $tambah_log->nik = Auth::User()->nik;
        $tambah_log->id_partnership = $request->id_partnership;
        $tambah_log->description = 'Add new Target '  . $request['target'] . ' with ' . $request['countable'];
        $tambah_log->save();
    }

    public function updateTarget(Request $request)
    {
        $update = PartnershipTarget::where('id', $request->id)->first();
        $update->target = $request->target;
        $update->countable = $request->countable;
        $update->update();

        $select_id = PartnershipTarget::where('id', $request->id)->first();

        $tambah_log = new PartnershipLog;
        $tambah_log->nik = Auth::User()->nik;
        $tambah_log->id_partnership = $select_id->id_partnership;
        $tambah_log->description = 'Update Target '  . $request['target'] . ' with ' . $request['countable'];
        $tambah_log->save();
    }

    public function updateCertPerson(Request $request)
    {
        $update = PartnershipCertification::where('id', $request->id_cert_edit)->first();
        $update->name = $request['cert_user_edit'];
        $update->name_certification = $request['cert_name_edit'];
        $update->expired_date = $request['cert_exp_date'];
        if ($request->file('cert_eng_edit') === null) {
        }else{
            $allowedfileExtension   = ['jpg','png', 'jpeg', 'JPG', 'PNG', 'pdf', 'PDF'];
            $file                   = $request->file('cert_eng_edit');
            $fileName               = $file->getClientOriginalName();
            $nameDoc                = 'certificate_engineer_' . $request->id_cert_edit . '_' . $fileName;
            $extension              = $file->getClientOriginalExtension();
            $check                  = in_array($extension,$allowedfileExtension);

            if ($check) {
                $request->file('cert_eng_edit')->move("image/certificate_engineer/", $nameDoc);
                $update->certificate         = $nameDoc;
            } else {
                return redirect()->back()->with('alert','Oops! Only jpg, png, pdf');
            }
        }
        $update->update();

        $select_id = PartnershipCertification::where('id', $request->id_cert_edit)->first();

        $tambah_log = new PartnershipLog;
        $tambah_log->nik = Auth::User()->nik;
        $tambah_log->id_partnership = $select_id->id_partnership;
        $tambah_log->description = 'Update Certificate ' . $request['cert_name_edit'] . ' with Name ' . $request['cert_user_edit'] . ' & Exp Date ' . $request['cert_exp_date'];
        $tambah_log->save();
    }

    public function deleteCertPerson(Request $request)
    {
        $select_id = PartnershipCertification::where('id', $request->id)->first();

        $tambah_log = new PartnershipLog;
        $tambah_log->nik = Auth::User()->nik;
        $tambah_log->id_partnership = $select_id->id_partnership;
        $tambah_log->description = 'Delete Certificate ' . $select_id->name_certification . ' with Name ' . $select_id->name;
        $tambah_log->save();

        $delete = PartnershipCertification::where('id', $request->id);
        $delete->delete();
    }

    public function updateStatusTarget(Request $request)
    {
        $update = PartnershipTarget::where('id', $request->id)->first();
        $update->status = 'Done';
        $update->update();

        $select_id = PartnershipTarget::where('id', $request->id)->first();

        $tambah_log = new PartnershipLog;
        $tambah_log->nik = Auth::User()->nik;
        $tambah_log->id_partnership = $select_id->id_partnership;
        $tambah_log->description = 'Target ' . $select_id->target . ' with ' . $select_id->countable . ' Done';
        $tambah_log->save();
    }

    public function deleteTarget(Request $request)
    {
        $select_id = PartnershipTarget::where('id', $request->id)->first();

        $tambah_log = new PartnershipLog;
        $tambah_log->nik = Auth::User()->nik;
        $tambah_log->id_partnership = $select_id->id_partnership;
        $tambah_log->description = 'Delete Target ' . $select_id->target . ' with ' . $select_id->countable;
        $tambah_log->save();

        $delete = PartnershipTarget::where('id', $request->id);
        $delete->delete();
    }

    public function deleteCertPartner(Request $request)
    {
        $select_id = PartnershipImageCertificate::where('id', $request->id)->first();

        $tambah_log = new PartnershipLog;
        $tambah_log->nik = Auth::User()->nik;
        $tambah_log->id_partnership = $select_id->id_partnership;
        $tambah_log->description = 'Delete Image with Title ' . $select_id->title;
        $tambah_log->save();

        $delete = PartnershipImageCertificate::where('id', $request->id);
        $delete->delete();
    }

    public function updateTitleCert(Request $request)
    {
        $update = PartnershipImageCertificate::where('id', $request->id)->first();
        $update->title = $request->title;
        $update->update();

        $select_id = PartnershipImageCertificate::where('id', $request->id)->first();

        $tambah_log = new PartnershipLog;
        $tambah_log->nik = Auth::User()->nik;
        $tambah_log->id_partnership = $select_id->id_partnership;
        $tambah_log->description = 'Update Title Certificate ' . $request->title;
        $tambah_log->save();
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
        $update->portal_partner    = $request['partner_portal_edit'];

        // return $request->file('imageUpload') != null ? 'true' : 'false';

        if ($request->file('fileupload') === null) {
        }else if($request->file('fileupload') != null){
            $allowedfileExtension   = ['jpg','png', 'jpeg', 'JPG', 'PNG', 'pdf', 'PDF'];
            $file                   = $request->file('fileupload');
            $fileName               = $file->getClientOriginalName();
            $nameDoc                = 'logo_' . $request['id_edit'] . '_' . $fileName;
            $extension              = $file->getClientOriginalExtension();
            $check                  = in_array($extension,$allowedfileExtension);

            if ($check) {
                $request->file('fileupload')->move("image/logo_partnership/", $nameDoc);
                $update->logo = $nameDoc;
            } else {
                return redirect()->back()->with('alert','Oops! Only jpg, png, pdf');
            }
        }

        if ($request->file('badgeupload' === null)) {
        } else if($request->file('badgeupload') != null){
            $allowedfileExtension   = ['jpg','png', 'jpeg', 'JPG', 'PNG', 'pdf', 'PDF'];
            $file                   = $request->file('badgeupload');
            $fileName               = $file->getClientOriginalName();
            $nameDoc                = 'badge_' . $request['id_edit'] . '_' . $fileName;
            $extension              = $file->getClientOriginalExtension();
            $check                  = in_array($extension,$allowedfileExtension);

            if ($check) {
                $request->file('badgeupload')->move("image/badge_partnership/", $nameDoc);
                $update->badge = $nameDoc;
            } else {
                return redirect()->back()->with('alert','Oops! Only jpg, png, pdf');
            }
        } 

        if(isset($request['technologyTag_edit'])){

            $id_tech = PartnershipTechnology::where('id_partnership',$request['id_edit'])->get();
            foreach ($id_tech as $data) {
                if ($data != NULL) {
                    $delete_product = PartnershipTechnology::where('id_partnership',$request['id_edit'])->delete();
                }
            }

            foreach (json_decode($request['technologyTag_edit']) as $data) {
                $technology = new PartnershipTechnology();
                $technology->id_partnership = $request['id_edit'];
                $technology->technology = $data;
                $technology->save();
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

        $summarySheet->mergeCells('A1:J1');
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

        $summarySheet->getStyle('A1:J1')->applyFromArray($titleStyle);
        $summarySheet->setCellValue('A1','SIP Partnership Summary');

        $headerContent = ["Type", "Partner", "Level", "Levelling", "Renewal Date", "Number of Certification", "CAM Name", "CAM Email", "CAM Phone", "Email Support"];
        $summarySheet->getStyle('A2:J2')->applyFromArray($headerStyle);
        $summarySheet->fromArray($headerContent,NULL,'A2');

        // $dataPartnership = DB::table('tb_partnership')->select('type','partner','level','levelling','renewal_date','sales_target','cam_name','cam_email','cam_phone','email_support')->get();
        // $dataPartnership = DB::table('tb_partnership')->select('type','partner','level','levelling','renewal_date','renewal_date','cam_name','cam_email','cam_phone','email_support')->get();
        $dataPartnership = Partnership::get();

        $dataPartnership = $dataPartnership->map(function($item,$key){

            $total_cert = $item->total_cert;

            $return = collect([
                "type" => $item->type,
                "partner" => $item->partner,
                "level" => $item->level,
                "levelling" => $item->levelling,
                "renewal_date" => $item->renewal_date,
                "total_cert" => str_replace("<br>", "", implode("\n", $total_cert->pluck('combine')->toArray())),
                "cam_name" => $item->cam_name,
                "cam_email" => $item->cam_email,
                "cam_phone" => $item->cam_phone,
                "email_support" => $item->email_support
            ]);

            return $return;
        });
        // return $dataPartnership;

        foreach ($dataPartnership as $key => $data) {
            $summarySheet->fromArray(
                array_values((array)$data),
                NULL,
                'A' . ($key + 3)
            );
            $summarySheet->getStyle('A' . ($key + 3) . ':' . 'J' . ($key + 3))->getAlignment()->setWrapText(true);
        }

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

        $fileName = 'SIP Partnership Summary ' . date("Y") . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        
        // $writer = new Xlsx($spreadsheet);
        // return $writer->save("php://output");
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $location = public_path() . '/report/partnership/' . $fileName;
        $writer->save($location);
        return $fileName;
}
}
