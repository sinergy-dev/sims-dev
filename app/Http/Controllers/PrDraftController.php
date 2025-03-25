<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

use Auth;
use DB;
use App\PR;
use App\SalesProject;
use App\User;
use App\Sales2;
use App\Sales;
use App\PrProduct;
use App\PrDokumen;
use App\Quote;
use App\PRDraft;
use App\PRActivity;
use App\PRCompare;
use App\PRDocumentCompare;
use App\PRDocumentDraft;
use App\PRDraftVerify;
use App\PRProductCompare;
use App\PRProductDraft;
use App\PRNotes;
use App\PRNotesDetail;
use App\PRDraftComparison;
use Google_Client;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;
use Google_Service_Drive_Permission;
use PDF;
use Mail;
use App\Mail\DraftPR;
use Log;
use Exception;

use setasign\Fpdf\Fpdf;
use mPDF;
use setasign\Fpdi\Fpdi;
// use setasign\Fpdi\Tcpdf\Fpdi; // Correct namespace for FPDI for TCPDF
use setasign\FpdiProtection\FpdiProtection;
use setasign\Fpdi\PdfParser\StreamReader;
use setasign\Fpdi\PdfReader\Exception\PdfReaderException;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

use Carbon\Carbon;
use LDAP\Result;

class PrDraftController extends Controller
{
    public function __construct()
    {
        set_time_limit(8000000);
    }

    public function getCount(Request $request)
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first()->id_territory;
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('name', 'roles.group')->where('user_id', $nik)->first();

        if ($cek_role->name == 'VP Internal Chain Management' || $cek_role->name == 'Procurement & Vendor Management' || $cek_role->name == 'Supply Chain & IT Support Manager' || $cek_role->name == 'Chief Operating Officer' || $cek_role->name == 'Chief Executive Officer' || $cek_role->name == 'VP Program & Project Management' || $cek_role->name == 'VP Solutions & Partnership Management') {
            $count_all = PRDraft::count();
            $count_need_attention = PRDraft::whereRaw("(`status` = 'REJECT' OR `status` = 'UNAPPROVED')")->count();

            $count_ongoing = PRDraft::whereRaw("(`status` = 'VERIFIED' OR `status` = 'COMPARING' OR `status` = 'CIRCULAR' OR `status` = 'SAVED'  OR `status` = 'DRAFT')")->count();

            $count_done = PRDraft::whereRaw("(`status` = 'FINALIZED' OR `status` = 'SENDED')")->count();
        } else if ($cek_role->name == 'VP Sales'){
            $listTerritory = User::where('id_territory',$territory)->pluck('nik');

            $count_all = PRDraft::whereIn('issuance',$listTerritory)->count();

            $count_need_attention = PRDraft::whereRaw("(`status` = 'REJECT' OR `status` = 'UNAPPROVED')")->whereIn('issuance',$listTerritory)->count();

            $count_ongoing = PRDraft::whereRaw("(`status` = 'VERIFIED' OR `status` = 'COMPARING' OR `status` = 'CIRCULAR' OR `status` = 'SAVED'  OR `status` = 'DRAFT')")->whereIn('issuance',$listTerritory)->count();

            $count_done = PRDraft::whereRaw("(`status` = 'FINALIZED' OR `status` = 'SENDED')")->whereIn('issuance',$listTerritory)->count();
        } else if ($cek_role->name == 'VP Human Capital Management'){
            $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Human Capital Management')->pluck('nik');

            $count_all = PRDraft::whereIn('issuance',$listGroup)->count();

            $count_need_attention = PRDraft::whereRaw("(`status` = 'REJECT' OR `status` = 'UNAPPROVED')")->whereIn('issuance',$listGroup)->count();

            $count_ongoing = PRDraft::whereRaw("(`status` = 'VERIFIED' OR `status` = 'COMPARING' OR `status` = 'CIRCULAR' OR `status` = 'SAVED'  OR `status` = 'DRAFT')")->whereIn('issuance',$listGroup)->count();

            $count_done = PRDraft::whereRaw("(`status` = 'FINALIZED' OR `status` = 'SENDED')")->whereIn('issuance',$listGroup)->count();

        } else if ($cek_role->name == 'VP Synergy System Management'){
            $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Synergy System Management')->pluck('nik');

            $count_all = PRDraft::whereIn('issuance',$listGroup)->count();

            $count_need_attention = PRDraft::whereRaw("(`status` = 'REJECT' OR `status` = 'UNAPPROVED')")->whereIn('issuance',$listGroup)->count();

            $count_ongoing = PRDraft::whereRaw("(`status` = 'VERIFIED' OR `status` = 'COMPARING' OR `status` = 'CIRCULAR' OR `status` = 'SAVED'  OR `status` = 'DRAFT')")->whereIn('issuance',$listGroup)->count();

            $count_done = PRDraft::whereRaw("(`status` = 'FINALIZED' OR `status` = 'SENDED')")->whereIn('issuance',$listGroup)->count();
        } else {
            $count_all = PRDraft::where('issuance',$nik)->count();

            $count_need_attention = PRDraft::whereRaw("(`status` = 'REJECT' OR `status` = 'UNAPPROVED')")->where('issuance',$nik)->count();

            $count_ongoing = PRDraft::whereRaw("(`status` = 'VERIFIED' OR `status` = 'COMPARING' OR `status` = 'CIRCULAR' OR `status` = 'SAVED'  OR `status` = 'DRAFT')")->where('issuance',$nik)->count();

            $count_done = PRDraft::whereRaw("(`status` = 'FINALIZED' OR `status` = 'SENDED')")->where('issuance',$nik)->count();
        }

        return collect([
            "count_all" => $count_all,
            "count_need_attention" => $count_need_attention,
            "count_ongoing" => $count_ongoing,
            "count_done" => $count_done,
        ]);
    }

    public function getFilterDraft(Request $request)
    {
        $getData = PRDraft::leftJoin('tb_pr', 'tb_pr.id_draft_pr', '=', 'tb_pr_draft.id')->leftJoin('users', 'users.nik', '=', 'tb_pr_draft.issuance');
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first()->id_territory;
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('name', 'roles.group')->where('user_id', $nik)->first();

        if (in_array(null, $request->type_of_letter) && in_array(null, $request->status) && in_array(null, $request->user) && $request->startDate == "" && $request->endDate == "" && $request->searchFor == "") {
            // return 'askjkf';
            if ($cek_role->name == 'VP Internal Chain Management' || $cek_role->name == 'Procurement & Vendor Management' || $cek_role->name == 'Supply Chain & IT Support Manager' || $cek_role->name == 'Chief Operating Officer' || $cek_role->name == 'Chief Executive Officer' || $cek_role->name == 'VP Program & Project Management' || $cek_role->name == 'VP Solutions & Partnership Management') {
                $getDataEPR = PRDraft::where('type_of_letter', 'EPR');
                if ($cek_role->name == 'Procurement & Vendor Management') {
                    $getDataEPR = $getDataEPR->whereRaw("(`status` != 'CANCEL' && `status` != 'SENDED')");
                } else {
                    $getDataEPR = $getDataEPR->whereRaw("(`status` != 'SAVED' AND `status` != 'CANCEL' AND `status` != 'SENDED')")
                    ;
                }

            } else if ($cek_role->name == 'VP Sales'){
                $listTerritory = User::where('id_territory',$territory)->pluck('nik');
                $getDataEPR = PRDraft::where('type_of_letter', 'EPR')->whereIn('issuance',$listTerritory)->whereRaw("(`status` != 'CANCEL' && `status` != 'SENDED')");
            } else {
                $getDataEPR = PRDraft::where('type_of_letter', 'EPR')->where('issuance',$nik)->whereRaw("(`status` != 'CANCEL' && `status` != 'SENDED')");
            }

            if ($cek_role->name == 'VP Internal Chain Management' || $cek_role->name == 'Procurement & Vendor Management' || $cek_role->name == 'Supply Chain & IT Support Manager' || $cek_role->name == 'Chief Operating Officer' || $cek_role->name == 'Chief Executive Officer') {
                $getData = PRDraft::where('type_of_letter', 'IPR');
                if ($cek_role->name == 'Procurement & Vendor Management') {
                    $getData = $getData->whereRaw("(`status` != 'CANCEL' AND `status` != 'SENDED')")
                    ;
                } else {
                    $getData = $getData->whereRaw("(`status` != 'CANCEL' AND `status` != 'SENDED' AND `status` != 'SAVED' AND `status`)")
                    ;
                }

            } else if ($cek_role->name == 'VP Sales'){
                $listTerritory = User::where('id_territory',$territory)->pluck('nik');
                $getData = PRDraft::where('type_of_letter', 'IPR')->whereIn('issuance',$listTerritory)->whereRaw("(`status` != 'CANCEL' && `status` != 'SENDED')");
            } else if ($cek_role->name == 'VP Program & Project Management'){
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Program & Project Management')->pluck('nik');
                $getData = PRDraft::where('type_of_letter', 'IPR')->whereIn('issuance',$listGroup)->whereRaw("(`status` != 'CANCEL' && `status` != 'SENDED')");
            } else if ($cek_role->name == 'VP Human Capital Management'){
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Human Capital Management')->pluck('nik');
                $getData = PRDraft::where('type_of_letter', 'IPR')->whereIn('issuance',$listGroup)->whereRaw("(`status` != 'CANCEL' && `status` != 'SENDED')");
            } else if ($cek_role->name == 'VP Solutions & Partnership Management'){
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Solutions & Partnership Management')->pluck('nik');
                $getData = PRDraft::where('type_of_letter', 'IPR')->whereIn('issuance',$listGroup)->whereRaw("(`status` != 'CANCEL' && `status` != 'SENDED')");
            } else if ($cek_role->name == 'VP Synergy System Management'){
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Synergy System Management')->pluck('nik');
                $getData = PRDraft::where('type_of_letter', 'IPR')->whereIn('issuance',$listGroup)->whereRaw("(`status` != 'CANCEL' && `status` != 'SENDED')");
            } else {
                $getData = PRDraft::where('type_of_letter', 'IPR')->where('issuance',$nik)->whereRaw("(`status` != 'CANCEL' && `status` != 'SENDED')");
            }

            $getDataFinal = $getData->get();
            $getDataEPRFinal = $getDataEPR->get();

            $data = $getDataFinal->merge($getDataEPRFinal);

            $order = ["CIRCULAR", "UNAPPROVED", "REJECT", "COMPARING", "VERIFIED", "FINALIZED", "DRAFT", "SAVED", "SENDED", "CANCEL"];

            $data = $data->sort(function ($a, $b) use ($order) {
                $statusComparison = array_search($a->status, $order) <=> array_search($b->status, $order);

                if ($statusComparison === 0) {
                    return strtotime($a->date) <=> strtotime($b->date); // Gantilah 'date' dengan nama kolom tanggal yang sesuai
                }

                return $statusComparison;
            });

            if ($request->order[0]['dir'] == 'asc') {
                $data = $data->values()->all();
            }else{
                $data = $data->values()->all();
            }

            $totalRecords = collect($data)->count();
            // Apply pagination
            $start = $request->input('start', 0);
            $pageLength = $request->input('length', $request->input); // Number of records per page

            $draw = $request->input('draw');

            if ($draw > 1) {
                $datas = collect($data)->skip($start)->take($pageLength);
                $data = [];
                $data = array_values($datas->toArray());
            }else{
                $data = collect($data)->skip($start)->take($pageLength);
            }
        }else{
            if ($cek_role->name == 'VP Internal Chain Management' || $cek_role->name == 'Procurement & Vendor Management' || $cek_role->name == 'Supply Chain & IT Support Manager' || $cek_role->name == 'Chief Operating Officer' || $cek_role->name == 'Chief Executive Officer' || $cek_role->name == 'VP Solutions & Partnership Management' || $cek_role->name == 'VP Program & Project Management') {
                if (!in_array(null, $request->type_of_letter)) {
                    if(in_array("EPR", $request->type_of_letter)){
                        // return 'true';
                        $getData->orWhere('tb_pr_draft.type_of_letter', "EPR");
                    }

                    if (!in_array(null, $request->user)) {
                        $getData->whereIn('users.name', $request->user);
                    }

                    if(in_array("IPR", $request->type_of_letter)){
                        // return 'false';
                        if ($cek_role->name == 'VP Solutions & Partnership Management') {
                            $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Solutions & Partnership Management')->whereRaw("(`id_company` = '1')")->pluck('nik');
                            $getData->orWhere(function ($query) use ($listGroup){
                                $query->whereIn('tb_pr_draft.issuance',$listGroup)
                                    ->where('tb_pr_draft.type_of_letter', 'IPR');
                            });
                        } else if ($cek_role->name == 'VP Program & Project Management') {
                            $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Program & Project Management')->pluck('nik');
                            $getData->orWhere(function ($query) use ($listGroup){
                                $query->whereIn('tb_pr_draft.issuance',$listGroup)
                                    ->where('tb_pr_draft.type_of_letter', 'IPR');
                            });
                        } else {
                            $getData->orWhere('tb_pr_draft.type_of_letter', "IPR");
                        }
                    }
                } else {
                    // return 'disini';
                    if ($cek_role->name == 'VP Solutions & Partnership Management') {
                        $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Solutions & Partnership Management')->whereRaw("(`id_company` = '1')")->pluck('nik');
                        $getData->orWhere(function ($query) use ($listGroup){
                            $query->whereIn('tb_pr_draft.issuance',$listGroup)
                                ->where('tb_pr_draft.type_of_letter', 'IPR');
                        })->whereIn('tb_pr_draft.status', $request->status);
                        $getData->orWhere('tb_pr_draft.type_of_letter', "EPR");
                    } else if ($cek_role->name == 'VP Program & Project Management') {
                        $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Program & Project Management')->pluck('nik');
                        $getData->orWhere(function ($query) use ($listGroup){
                            $query->whereIn('tb_pr_draft.issuance',$listGroup)
                                ->where('tb_pr_draft.type_of_letter', 'IPR');
                        })->whereIn('tb_pr_draft.status', $request->status);
                        $getData->orWhere('tb_pr_draft.type_of_letter', "EPR");
                    }

                    if (!in_array(null, $request->user)) {
                        $getData->whereIn('users.name', $request->user);
                    }
                }
            } elseif($cek_role->name == 'VP Sales'){
                $listTerritoryName = User::select('name')->where('id_territory',$territory)->pluck('name');
                $listTerritoryNik = User::select('nik')->where('id_territory',$territory)->pluck('nik');

                if (!in_array(null, $request->type_of_letter)) {
                    $getData->whereIn('tb_pr_draft.type_of_letter', $request->type_of_letter)->whereIn('tb_pr_draft.issuance',$listTerritoryNik);
                }

                if (!in_array(null, $request->user)) {
                    $getData->whereIn('users.name', $request->user);
                } else {
                    $getData->whereIn('users.name', $listTerritoryName);
                }
            } else if ($cek_role->name == 'VP Human Capital Management'){
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Human Capital Management')->pluck('nik');
                $listGroupName = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Human Capital Management')->pluck('users.name');
                if (!in_array(null, $request->type_of_letter)) {
                    $getData->whereIn('tb_pr_draft.type_of_letter', $request->type_of_letter)->whereIn('tb_pr_draft.issuance',$listGroup);
                }

                if (!in_array(null, $request->user)) {
                    $getData->whereIn('users.name', $request->user);
                } else {
                    $getData->whereIn('users.name', $listGroupName);
                }
            } else if ($cek_role->name == 'VP Synergy System Management'){
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Synergy System Management')->pluck('nik');
                $listGroupName = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Synergy System Management')->pluck('users.name');
                if (!in_array(null, $request->type_of_letter)) {
                    $getData->whereIn('tb_pr_draft.type_of_letter', $request->type_of_letter)->whereIn('tb_pr_draft.issuance',$listGroup);
                }

                if (!in_array(null, $request->user)) {
                    $getData->whereIn('users.name', $request->user);
                } else {
                    $getData->whereIn('users.name', $listGroupName);
                }
            } else {
                if (!in_array(null, $request->type_of_letter)) {
                    $getData->whereIn('tb_pr_draft.type_of_letter', $request->type_of_letter)->where('users.name', Auth::User()->name);
                }
            }

            if (gettype($request->status) == 'array') {
                // return 'true';
                if (!in_array(null, $request->status)) {
                    if (in_array("NA", $request->status)) {
                        $getData->where(function($query){
                            $query->where('tb_pr_draft.status', 'REJECT')
                                ->orWhere('tb_pr_draft.status', 'UNAPPROVED');
                        });
                    } elseif (in_array("OG", $request->status)) {
                        $getData->where(function($query){
                            $query->where('tb_pr_draft.status', 'VERIFIED')
                                ->orWhere('tb_pr_draft.status', 'COMPARING')
                                ->orWhere('tb_pr_draft.status', 'CIRCULAR')
                                ->orWhere('tb_pr_draft.status', 'SAVED')
                                ->orWhere('tb_pr_draft.status', 'DRAFT');
                            // $query->whereRaw("(`tb_pr_draft`.`status` = 'VERIFIED' OR `tb_pr_draft`.`status` = 'COMPARING'  OR `tb_pr_draft`.`status` = 'CIRCULAR' OR `tb_pr_draft`.`status` = 'SAVED'  OR `tb_pr_draft`.`status` = 'DRAFT')");
                        });
                    } elseif (in_array("DO", $request->status)) {
                        $getData->where(function($query){
                            $query->where('tb_pr_draft.status', 'FINALIZED')
                                ->orWhere('tb_pr_draft.status', 'SENDED');
                        });
                    } elseif (in_array("ALL", $request->status)) {
                        $getData;
                    } else {
                        $getData->whereIn('tb_pr_draft.status', $request->status);
                    }
                }
            }

            if ($cek_role->name == 'VP Internal Chain Management' || $cek_role->name == 'Procurement & Vendor Management' || $cek_role->name == 'Supply Chain & IT Support Manager' || $cek_role->name == 'Chief Operating Officer' || $cek_role->name == 'Chief Executive Officer' || $cek_role->name == 'VP Solutions & Partnership Management' || $cek_role->name == 'VP Program & Project Management') {
                if (!in_array(null, $request->user)) {
                    $getData->whereIn('users.name', $request->user);
                }
            } elseif($cek_role->name == 'VP Sales'){
                $listTerritoryName = User::select('name')->where('id_territory',$territory)->pluck('name');
                $listTerritoryNik = User::select('nik')->where('id_territory',$territory)->pluck('nik');

                if (!in_array(null, $request->user)) {
                    $getData->whereIn('users.name', $request->user);
                } else {
                    $getData->whereIn('users.name', $listTerritoryName);
                }
            } else if ($cek_role->name == 'VP Internal Chain Management'){
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Internal Chain Management')->pluck('nik');
                $listGroupName = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Internal Chain Management')->pluck('users.name');

                if (!in_array(null, $request->user)) {
                    $getData->whereIn('users.name', $request->user);
                } else {
                    $getData->whereIn('users.name', $listGroupName);
                }
            } else if ($cek_role->name == 'VP Human Capital Management'){
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Human Capital Management')->pluck('nik');
                $listGroupName = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Human Capital Management')->pluck('users.name');

                if (!in_array(null, $request->user)) {
                    $getData->whereIn('users.name', $request->user);
                } else {
                    $getData->whereIn('users.name', $listGroupName);
                }
            } else if ($cek_role->name == 'VP Synergy System Management'){
                $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Synergy System Management')->pluck('nik');
                $listGroupName = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Synergy System Management')->pluck('users.name');

                if (!in_array(null, $request->user)) {
                    $getData->whereIn('users.name', $request->user);
                } else {
                    $getData->whereIn('users.name', $listGroupName);
                }
            } else {
                if (in_array(null, $request->user)) {
                    $getData->where('users.name', Auth::User()->name);
                }
            }

            if($request->startDate != "" && $request->endDate != ""){
                $getData->whereBetween('tb_pr_draft.created_at', [$request->startDate . " 00:00:00", $request->endDate . " 23:59:59"]);
            }

            $searchFields = ['tb_pr.no_pr', 'tb_pr_draft.status', 'tb_pr_draft.to', 'tb_pr.to', 'tb_pr_draft.title', 'tb_pr.title', 'name', 'tb_pr_draft.status'];
            if ($cek_role->name == 'Chief Operating Officer' || $cek_role->name == 'Chief Executive Officer' || $cek_role->name == 'VP Solutions & Partnership Management' || $cek_role->name == 'VP Program & Project Management'){
                if ($request->searchFor != "") {
                    $data = $getData->whereRaw("(`tb_pr_draft`.`status` != 'SAVED' AND `tb_pr_draft`.`status` != 'CANCEL' AND `tb_pr_draft`.`status` != 'SENDED' AND `tb_pr_draft`.`status` != 'DRAFT' AND `tb_pr_draft`.`status` != 'REJECT' AND `tb_pr_draft`.`status` != 'UNAPPROVED')");
                }
            }
            $data = $getData->get();


            if ($request->searchFor != "") {

                $filtered = $data->filter(function ($value, $key) use($request, $searchFields) {
                    return stripos($value["no_pr"], $request->searchFor) !== false ||
                        stripos($value["status"], $request->searchFor) !== false ||
                        stripos($value["to"], $request->searchFor) !== false ||
                        stripos($value["title"], $request->searchFor) !== false ||
                        stripos($value["name"], $request->searchFor) !== false ||
                        stripos($value["status"], $request->searchFor) !== false ||
                        stripos($value["circularby"], $request->searchFor) !== false;
                });

                $data = $filtered;

                $totalRecords = $data->count();
                // Apply pagination
                $start = $request->input('start', 0);
                $pageLength = $request->input('length', $request->length); // Number of records per page

                $draw = $request->input('draw');

                $order = ["CIRCULAR", "UNAPPROVED", "REJECT", "COMPARING", "VERIFIED", "FINALIZED", "DRAFT", "SAVED", "SENDED", "CANCEL"];

                $data = $data->sort(function ($a, $b) use ($order) {
                    // return array_search($item->status, $order);
                    $statusComparison = array_search($a->status, $order) <=> array_search($b->status, $order);

                    if ($statusComparison === 0) {
                        return strtotime($a->date) <=> strtotime($b->date); // Gantilah 'date' dengan nama kolom tanggal yang sesuai
                    }

                    return $statusComparison;
                });

                $outputArray = [];
                foreach ($data as $item) {
                    $outputArray[] = collect([
                        "no_pr"=>$item->no_pr,
                        "status"=>$item->status,
                        "status_pr"=>$item->status,
                        "to"=>$item->to,
                        "title"=>$item->title,
                        "name"=>$item->name,
                        "circularby"=>$item->circularby,
                        "id"=>$item->id,
                        "nominal"=>$item->nominal,
                        "type_of_letter"=>$item->type_of_letter,
                        "date"=>$item->date,
                        "created_at"=>$item->created_at,
                        "attention_notes"=>$item->attention_notes,
                        "isCircular"=>$item->isCircular,
                        "issuance"=>$item->issuance
                    ]);
                }

                $data = $outputArray;

                if ($request->order[0]['dir'] == 'asc') {
                    // $data = collect($data)->sortBy($orderByName)->values()->all();
                    $data = collect($data)->values()->all();
                }else{
                    // $data = collect($data)->sortByDesc($orderByName)->values()->all();
                    $data = collect($data)->values()->all();
                }

                if ($draw > 1) {
                    $datas = collect($data)->skip($start)->take($pageLength);
                    $data = [];
                    $data = array_values($datas->toArray());

                    // return $data;
                }else{
                    $data = collect($data)->skip($start)->take($pageLength);
                }
                $totalRecords = collect($data)->count();
            }else{
                // Get the total count before pagination
                // Apply pagination
                $totalRecords = $data->count();
                $start = $request->input('start', 0);
                $pageLength = $request->input('length', $request->length); // Number of records per page
                $draw = $request->input('draw');

                $order = ["CIRCULAR", "UNAPPROVED", "REJECT", "COMPARING", "VERIFIED", "FINALIZED", "DRAFT", "SAVED", "SENDED", "CANCEL"];

                $data = $data->sort(function ($a, $b) use ($order) {
                    // return array_search($item->status, $order);
                    $statusComparison = array_search($a->status, $order) <=> array_search($b->status, $order);

                    if ($statusComparison === 0) {
                        return strtotime($a->date) <=> strtotime($b->date); // Gantilah 'date' dengan nama kolom tanggal yang sesuai
                    }

                    return $statusComparison;
                });

                if ($request->order[0]['dir'] == 'asc') {
                    // $data = collect($data)->sortBy($orderByName)->values()->all();
                    $data = collect($data)->values()->all();
                }else{
                    // $data = collect($data)->sortByDesc($orderByName)->values()->all();
                    $data = collect($data)->values()->all();
                }

                if ($draw > 1) {
                    $datas = collect($data)->skip($start)->take($pageLength);
                    $data = [];
                    $data = array_values($datas->toArray());

                    // return $data;
                }else{
                    $data = collect($data)->skip($start)->take($pageLength);
                }
            }
            // $totalRecords = collect($data)->count();
        }

        return response()->json([
            'draw' => $request->input('draw'),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'length' => $pageLength,
            'data' => $data,
        ]);

    }

    public function getDropdownFilterPr(Request $request)
    {
        $getData = DB::table('tb_pr_draft')->leftJoin('users', 'users.nik', '=', 'tb_pr_draft.issuance');
        $date = $getData->pluck('tb_pr_draft.created_at')->toArray();
        sort($date);


        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first()->id_territory;
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('name', 'roles.group')->where('user_id', $nik)->first();

        if ($cek_role->name == 'VP Internal Chain Management' || $cek_role->name == 'Procurement & Vendor Management' || $cek_role->name == 'Supply Chain & IT Support Manager' || $cek_role->name == 'Chief Operating Officer' || $cek_role->name == 'Chief Executive Officer' || $cek_role->name == 'VP Solutions & Partnership Management' || $cek_role->name == 'VP Program & Project Management') {
            // if (!in_array(null, $request->type_of_letter)) {
            //     // if(in_array("EPR", $request->type_of_letter)){
            //     //     $getData->orWhere('tb_pr_draft.type_of_letter', "EPR");
            //     // }

            //     if (!in_array(null, $request->user)) {
            //         $getData->whereIn('users.name', $request->user);
            //     }

            //     if(in_array("IPR", $request->type_of_letter)){
            //         if ($cek_role->name == 'VP Solutions & Partnership Management') {
            //             $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','presales')->pluck('nik');
            //             $getData->orWhere(function ($query) use ($listGroup){
            //                 $query->whereIn('tb_pr_draft.issuance',$listGroup)
            //                 ->where('tb_pr_draft.type_of_letter', 'IPR');
            //             });
            //         } else if ($cek_role->name == 'VP Program & Project Management') {
            //             $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','pmo')->pluck('nik');
            //             $getData->orWhere(function ($query) use ($listGroup){
            //                 $query->whereIn('tb_pr_draft.issuance',$listGroup)
            //                 ->where('tb_pr_draft.type_of_letter', 'IPR');
            //             });
            //         }

            //     }
            // } else {
            //     if ($cek_role->name == 'VP Solutions & Partnership Management') {
            //         $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','presales')->pluck('nik');
            //         $getData->orWhere(function ($query) use ($listGroup){
            //             $query->whereIn('tb_pr_draft.issuance',$listGroup)
            //             ->where('tb_pr_draft.type_of_letter', 'IPR');
            //         });
            //         $getData->orWhere('tb_pr_draft.type_of_letter', "EPR");
            //     } else if ($cek_role->name == 'VP Program & Project Management') {
            //         $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','pmo')->pluck('nik');
            //         $getData->orWhere(function ($query) use ($listGroup){
            //             $query->whereIn('tb_pr_draft.issuance',$listGroup)
            //             ->where('tb_pr_draft.type_of_letter', 'IPR');
            //         });
            //         $getData->orWhere('tb_pr_draft.type_of_letter', "EPR");
            //     }

            //     if (!in_array(null, $request->user)) {
            //         $getData->whereIn('users.name', $request->user);
            //     }

            // }


            // if ($getData->pluck('tb_pr_draft.type_of_letter')->unique()->values() == 'IPR') {
            //     if ($cek_role->name == 'VP Solutions & Partnership Management') {
            //         $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','presales')->pluck('nik');
            //         $getData->orWhere(function ($query) use ($listGroup){
            //             $query->whereIn('tb_pr_draft.issuance',$listGroup)
            //             ->where('tb_pr_draft.type_of_letter', 'IPR');
            //         });
            //     } else if ($cek_role->name == 'VP Program & Project Management') {
            //         $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','pmo')->pluck('nik');
            //         $getData->orWhere(function ($query) use ($listGroup){
            //             $query->whereIn('tb_pr_draft.issuance',$listGroup)
            //             ->where('tb_pr_draft.type_of_letter', 'IPR');
            //         });
            //     }

            // } else if($getData->pluck('tb_pr_draft.type_of_letter')->unique()->values() == 'EPR'){
            //     if ($cek_role->name == 'VP Solutions & Partnership Management') {
            //         $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','presales')->pluck('nik');
            //         $getData->orWhere(function ($query) use ($listGroup){
            //             $query->whereIn('tb_pr_draft.issuance',$listGroup)
            //             ->where('tb_pr_draft.type_of_letter', 'EPR');
            //         });
            //         // $getData->orWhere('tb_pr_draft.type_of_letter', "EPR");
            //     } else if ($cek_role->name == 'VP Program & Project Management') {
            //         $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','pmo')->pluck('nik');
            //         $getData->orWhere(function ($query) use ($listGroup){
            //             $query->whereIn('tb_pr_draft.issuance',$listGroup)
            //             ->where('tb_pr_draft.type_of_letter', 'EPR');
            //         });
            //         // $getData->orWhere('tb_pr_draft.type_of_letter', "EPR");
            //     }


            // }



        } elseif($cek_role->name == 'VP Sales'){
            $listTerritoryName = User::select('name')->where('id_territory',$territory)->pluck('name');
            $listTerritoryNik = User::select('nik')->where('id_territory',$territory)->pluck('nik');

            $getData->whereIn('users.name',$listTerritoryName);


        } else if ($cek_role->name == 'VP Internal Chain Management'){
            $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Internal Chain Management')->pluck('nik');
            $listGroupName = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Internal Chain Management')->pluck('users.name');

            // if (!in_array(null, $request->user)) {
            //     $getData->whereIn('users.name', $request->user);
            // } else {
            //     $getData->whereIn('users.name', $listGroupName);
            // }

            $getData->whereIn('users.name',$listGroupName);


        } else if ($cek_role->name == 'VP Human Capital Management'){
            $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Human Capital Management')->pluck('nik');
            $listGroupName = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Human Capital Management')->pluck('users.name');

            // if (!in_array(null, $request->user)) {
            //     $getData->whereIn('users.name', $request->user);
            // } else {
            //     $getData->whereIn('users.name', $listGroupName);
            // }

            $getData->whereIn('users.name',$listGroupName);
        } else if ($cek_role->name == 'VP Synergy System Management'){
            $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Synergy System Management')->pluck('nik');
            $listGroupName = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Synergy System Management')->pluck('users.name');
            // if (!in_array(null, $request->type_of_letter)) {
            //     $getData->whereIn('tb_pr_draft.type_of_letter', $request->type_of_letter)->whereIn('tb_pr_draft.issuance',$listGroup);
            // }

            // if (!in_array(null, $request->user)) {
            //     $getData->whereIn('users.name', $request->user);
            // } else {
            //     $getData->whereIn('users.name', $listGroupName);
            // }

            $getData->whereIn('users.name',$listGroupName);
        } else {
            // if (!in_array(null, $request->type_of_letter)) {
            //     $getData->whereIn('tb_pr_draft.type_of_letter', $request->type_of_letter)->where('users.name', Auth::User()->name);
            // }

            $getData->where('users.name',Auth::User()->name);
        }


        return collect([
            "data_type_letter" => $getData->pluck('tb_pr_draft.type_of_letter')->unique()->values()->map(function ($item, $key){
                return array("id" => $item, "text" => $item);
            }),
            "dataStatus" => $getData->orderByRaw('FIELD(tb_pr_draft.status, "SAVED", "DRAFT", "VERIFIED","COMPARING", "CIRCULAR", "FINALIZED", "SENDED", "REJECT", "UNAPPROVED")')->pluck('tb_pr_draft.status')->unique()->values()->map(function ($item, $key){
                return array("id" => $item, "text" => $item);
            }),
            "dataUser" => $getData->pluck('users.name')->unique()->values()->map(function ($item, $key){
                return array("id" => $item, "text" => $item);
            }),
            "startDate" => current($date),
            "endDate" => end($date)
        ]);

    }

    public function getFilterCount(Request $request)
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first()->id_territory;
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('name', 'roles.group')->where('user_id', $nik)->first();

        $count = PRDraft::join('users','users.nik', '=', 'tb_pr_draft.issuance');
        // return $count;
        if ($cek_role->name == 'VP Internal Chain Management' || $cek_role->name == 'Procurement & Vendor Management' || $cek_role->name == 'Supply Chain & IT Support Manager' || $cek_role->name == 'Chief Operating Officer' || $cek_role->name == 'Chief Executive Officer') {
            $count_all = DB::table('tb_pr_draft')->join('users','users.nik', '=', 'tb_pr_draft.issuance');
            $count_need_attention = PRDraft::join('users','users.nik', '=', 'tb_pr_draft.issuance')->whereRaw("(`status` = 'REJECT' OR `status` = 'UNAPPROVED')");

            $count_ongoing = PRDraft::join('users','users.nik', '=', 'tb_pr_draft.issuance')->whereRaw("(`status` = 'VERIFIED' OR `status` = 'COMPARING' OR `status` = 'CIRCULAR' OR `status` = 'SAVED'  OR `status` = 'DRAFT')");

            $count_done = PRDraft::join('users','users.nik', '=', 'tb_pr_draft.issuance')->whereRaw("(`status` = 'FINALIZED' OR `status` = 'SENDED')");
        } else if ($cek_role->name == 'VP Sales'){
            $listTerritory = User::where('id_territory',$territory)->pluck('nik');

            $count_all = PRDraft::join('users','users.nik', '=', 'tb_pr_draft.issuance')->whereIn('issuance',$listTerritory);

            $count_need_attention = PRDraft::join('users','users.nik', '=', 'tb_pr_draft.issuance')->whereRaw("(`status` = 'REJECT' OR `status` = 'UNAPPROVED')")->whereIn('issuance',$listTerritory);

            $count_ongoing = PRDraft::join('users','users.nik', '=', 'tb_pr_draft.issuance')->whereRaw("(`status` = 'VERIFIED' OR `status` = 'COMPARING' OR `status` = 'CIRCULAR' OR `status` = 'SAVED'  OR `status` = 'DRAFT')")->whereIn('issuance',$listTerritory);

            $count_done = PRDraft::join('users','users.nik', '=', 'tb_pr_draft.issuance')->whereRaw("(`status` = 'FINALIZED' OR `status` = 'SENDED')")->whereIn('issuance',$listTerritory);

        } else if ($cek_role->name == 'VP Program & Project Management'){
            $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Program & Project Management')->pluck('nik');

            $count_all = PRDraft::join('users','users.nik', '=', 'tb_pr_draft.issuance')->whereIn('issuance',$listGroup);

            $count_need_attention = PRDraft::join('users','users.nik', '=', 'tb_pr_draft.issuance')->whereRaw("(`status` = 'REJECT' OR `status` = 'UNAPPROVED')")->whereIn('issuance',$listGroup);

            $count_ongoing = PRDraft::join('users','users.nik', '=', 'tb_pr_draft.issuance')->whereRaw("(`status` = 'VERIFIED' OR `status` = 'COMPARING' OR `status` = 'CIRCULAR' OR `status` = 'SAVED'  OR `status` = 'DRAFT')")->whereIn('issuance',$listGroup);

            $count_done = PRDraft::join('users','users.nik', '=', 'tb_pr_draft.issuance')->whereRaw("(`status` = 'FINALIZED' OR `status` = 'SENDED')")->whereIn('issuance',$listGroup);

        } else if ($cek_role->name == 'VP Internal Chain Management'){
            $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Internal Chain Management')->pluck('nik');

            $count_all = PRDraft::join('users','users.nik', '=', 'tb_pr_draft.issuance')->whereIn('issuance',$listGroup);

            $count_need_attention = PRDraft::join('users','users.nik', '=', 'tb_pr_draft.issuance')->whereRaw("(`status` = 'REJECT' OR `status` = 'UNAPPROVED')")->whereIn('issuance',$listGroup);

            $count_ongoing = PRDraft::join('users','users.nik', '=', 'tb_pr_draft.issuance')->whereRaw("(`status` = 'VERIFIED' OR `status` = 'COMPARING' OR `status` = 'CIRCULAR' OR `status` = 'SAVED'  OR `status` = 'DRAFT')")->whereIn('issuance',$listGroup);

            $count_done = PRDraft::join('users','users.nik', '=', 'tb_pr_draft.issuance')->whereRaw("(`status` = 'FINALIZED' OR `status` = 'SENDED')")->whereIn('issuance',$listGroup);

        } else if ($cek_role->name == 'VP Human Capital Management'){
            $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Human Capital Management')->pluck('nik');

            $count_all = PRDraft::join('users','users.nik', '=', 'tb_pr_draft.issuance')->whereIn('issuance',$listGroup);

            $count_need_attention = PRDraft::join('users','users.nik', '=', 'tb_pr_draft.issuance')->whereRaw("(`status` = 'REJECT' OR `status` = 'UNAPPROVED')")->whereIn('issuance',$listGroup);

            $count_ongoing = PRDraft::join('users','users.nik', '=', 'tb_pr_draft.issuance')->whereRaw("(`status` = 'VERIFIED' OR `status` = 'COMPARING' OR `status` = 'CIRCULAR' OR `status` = 'SAVED'  OR `status` = 'DRAFT')")->whereIn('issuance',$listGroup);

            $count_done = PRDraft::join('users','users.nik', '=', 'tb_pr_draft.issuance')->whereRaw("(`status` = 'FINALIZED' OR `status` = 'SENDED')")->whereIn('issuance',$listGroup);

        } else if ($cek_role->name == 'VP Solutions & Partnership Management'){
            $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Solutions & Partnership Management')->whereRaw("(`id_company` = '1')")->pluck('nik');

            $count_all = PRDraft::join('users','users.nik', '=', 'tb_pr_draft.issuance')->whereIn('issuance',$listGroup);

            $count_need_attention = PRDraft::join('users','users.nik', '=', 'tb_pr_draft.issuance')->whereRaw("(`status` = 'REJECT' OR `status` = 'UNAPPROVED')")->whereIn('issuance',$listGroup);

            $count_ongoing = PRDraft::join('users','users.nik', '=', 'tb_pr_draft.issuance')->whereRaw("(`status` = 'VERIFIED' OR `status` = 'COMPARING' OR `status` = 'CIRCULAR' OR `status` = 'SAVED'  OR `status` = 'DRAFT')")->whereIn('issuance',$listGroup);

            $count_done = PRDraft::join('users','users.nik', '=', 'tb_pr_draft.issuance')->whereRaw("(`status` = 'FINALIZED' OR `status` = 'SENDED')")->whereIn('issuance',$listGroup);

        } else if ($cek_role->name == 'VP Synergy System Management'){
            $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Synergy System Management')->pluck('nik');

            $count_all = PRDraft::join('users','users.nik', '=', 'tb_pr_draft.issuance')->whereIn('issuance',$listGroup);

            $count_need_attention = PRDraft::join('users','users.nik', '=', 'tb_pr_draft.issuance')->whereRaw("(`status` = 'REJECT' OR `status` = 'UNAPPROVED')")->whereIn('issuance',$listGroup);

            $count_ongoing = PRDraft::join('users','users.nik', '=', 'tb_pr_draft.issuance')->whereRaw("(`status` = 'VERIFIED' OR `status` = 'COMPARING' OR `status` = 'CIRCULAR' OR `status` = 'SAVED'  OR `status` = 'DRAFT')")->whereIn('issuance',$listGroup);

            $count_done = PRDraft::join('users','users.nik', '=', 'tb_pr_draft.issuance')->whereRaw("(`status` = 'FINALIZED' OR `status` = 'SENDED')")->whereIn('issuance',$listGroup);
        } else {
            $count_all = PRDraft::join('users','users.nik', '=', 'tb_pr_draft.issuance')->where('issuance',$nik);

            $count_need_attention = PRDraft::join('users','users.nik', '=', 'tb_pr_draft.issuance')->whereRaw("(`status` = 'REJECT' OR `status` = 'UNAPPROVED')")->where('issuance',$nik);

            $count_ongoing = PRDraft::join('users','users.nik', '=', 'tb_pr_draft.issuance')->whereRaw("(`status` = 'VERIFIED' OR `status` = 'COMPARING' OR `status` = 'CIRCULAR' OR `status` = 'SAVED'  OR `status` = 'DRAFT')")->where('issuance',$nik);

            $count_done = PRDraft::join('users','users.nik', '=', 'tb_pr_draft.issuance')->whereRaw("(`status` = 'FINALSIZED' OR `status` = 'SENDED')")->where('issuance',$nik);
        }

        if (isset($request->type_of_letter)) {
            if (!in_array(null, $request->type_of_letter)) {
                $count_all->whereIn('tb_pr_draft.type_of_letter', $request->type_of_letter);
                $count_need_attention->whereIn('tb_pr_draft.type_of_letter', $request->type_of_letter);
                $count_ongoing->whereIn('tb_pr_draft.type_of_letter', $request->type_of_letter);
                $count_done->whereIn('tb_pr_draft.type_of_letter', $request->type_of_letter);
            }
        }

        if (isset($request->user)) {
            if (!in_array(null, $request->user)) {
                $count_all->whereIn('users.name', $request->user);
                $count_need_attention->whereIn('users.name', $request->user);
                $count_ongoing->whereIn('users.name', $request->user);
                $count_done->whereIn('users.name', $request->user);
            }
        }

        if (isset($request->status)) {
            if (!in_array(null, $request->status)) {
                if (in_array("NA", $request->status)) {
                    $count_all->where(function($query){
                        $query->where('tb_pr_draft.status', 'REJECT')
                            ->orWhere('tb_pr_draft.status', 'UNAPPROVED');
                    });
                    $count_need_attention->where(function($query){
                        $query->where('tb_pr_draft.status', 'REJECT')
                            ->orWhere('tb_pr_draft.status', 'UNAPPROVED');
                    });
                    $count_ongoing->where(function($query){
                        $query->where('tb_pr_draft.status', 'REJECT')
                            ->orWhere('tb_pr_draft.status', 'UNAPPROVED');
                    });
                    $count_done->where(function($query){
                        $query->where('tb_pr_draft.status', 'REJECT')
                            ->orWhere('tb_pr_draft.status', 'UNAPPROVED');
                    });
                } elseif (in_array("OG", $request->status)) {
                    $count_all->where(function($query){
                        $query->where('tb_pr_draft.status', 'VERIFIED')
                            ->orWhere('tb_pr_draft.status', 'COMPARING')
                            ->orWhere('tb_pr_draft.status', 'CIRCULAR')
                            ->orWhere('tb_pr_draft.status', 'SAVED')
                            ->orWhere('tb_pr_draft.status', 'DRAFT');
                    });
                    $count_need_attention->where(function($query){
                        $query->where('tb_pr_draft.status', 'VERIFIED')
                            ->orWhere('tb_pr_draft.status', 'COMPARING')
                            ->orWhere('tb_pr_draft.status', 'CIRCULAR')
                            ->orWhere('tb_pr_draft.status', 'SAVED')
                            ->orWhere('tb_pr_draft.status', 'DRAFT');
                    });
                    $count_ongoing->where(function($query){
                        $query->where('tb_pr_draft.status', 'VERIFIED')
                            ->orWhere('tb_pr_draft.status', 'COMPARING')
                            ->orWhere('tb_pr_draft.status', 'CIRCULAR')
                            ->orWhere('tb_pr_draft.status', 'SAVED')
                            ->orWhere('tb_pr_draft.status', 'DRAFT');
                    });
                    $count_done->where(function($query){
                        $query->where('tb_pr_draft.status', 'VERIFIED')
                            ->orWhere('tb_pr_draft.status', 'COMPARING')
                            ->orWhere('tb_pr_draft.status', 'CIRCULAR')
                            ->orWhere('tb_pr_draft.status', 'SAVED')
                            ->orWhere('tb_pr_draft.status', 'DRAFT');
                    });
                } elseif (in_array("DO", $request->status)) {
                    $count_all->where(function($query){
                        $query->where('tb_pr_draft.status', 'FINALIZED')
                            ->orWhere('tb_pr_draft.status', 'SENDED');
                    });
                    $count_need_attention->where(function($query){
                        $query->where('tb_pr_draft.status', 'FINALIZED')
                            ->orWhere('tb_pr_draft.status', 'SENDED');
                    });
                    $count_ongoing->where(function($query){
                        $query->where('tb_pr_draft.status', 'FINALIZED')
                            ->orWhere('tb_pr_draft.status', 'SENDED');
                    });
                    $count_done->where(function($query){
                        $query->where('tb_pr_draft.status', 'FINALIZED')
                            ->orWhere('tb_pr_draft.status', 'SENDED');
                    });
                } else {
                    $count_all->whereIn('tb_pr_draft.status', $request->status);
                    $count_need_attention->whereIn('tb_pr_draft.status', $request->status);
                    $count_ongoing->whereIn('tb_pr_draft.status', $request->status);
                    $count_done->whereIn('tb_pr_draft.status', $request->status);

                }
            }
        }


        if($request->startDate != "" && $request->endDate != ""){
            $count_all->whereBetween('tb_pr_draft.created_at', [$request->startDate . " 00:00:00", $request->endDate . " 23:59:59"]);
            $count_need_attention->whereBetween('tb_pr_draft.created_at', [$request->startDate . " 00:00:00", $request->endDate . " 23:59:59"]);
            $count_ongoing->whereBetween('tb_pr_draft.created_at', [$request->startDate . " 00:00:00", $request->endDate . " 23:59:59"]);
            $count_done->whereBetween('tb_pr_draft.created_at', [$request->startDate . " 00:00:00", $request->endDate . " 23:59:59"]);
        }

        return collect([
            "count_all" => $count_all->count(),
            "count_need_attention" => $count_need_attention->count(),
            "count_ongoing" => $count_ongoing->count(),
            "count_done" => $count_done->count()
        ]);
    }

    public function getFilterStatus(Request $request)
    {
        $getData = DB::table('tb_pr_draft')->select('status')->where('type_of_letter', $request->type_of_letter)->groupBy('status')->get();
        return array("data"=>$getData);
    }

    public function getFilterUser(Request $request)
    {
        $getData = DB::table('tb_pr_draft')->join('users', 'users.nik', '=', 'tb_pr_draft.issuance')->select('name', 'nik')->where('type_of_letter', $request->type_of_letter)->groupBy('nik')->get();
        return array("data"=>$getData);
    }

    public function draftPR(){
        return view('/admin/createPR')->with(['initView'=> $this->initMenuBase(),'feature_item'=>$this->RoleDynamic('draft_pr'), 'sidebar_collapse' => 'true']);
    }

    public function detailDraftPR(){
        return view('/admin/detailDraftPR')->with(['initView'=> $this->initMenuBase(),'feature_item'=>$this->RoleDynamic('draft_pr'), 'sidebar_collapse' => 'true'  ]);
    }

    public function getClient()
    {
        $client = new Google_Client();
        $client->setApplicationName('Google Drive API PHP Quickstart');
        $client->setAuthConfig(env('AUTH_CONFIG'));
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');
        // $client->setScopes("https://www.googleapis.com/auth/drive");
        $client->setScopes(Google_Service_Drive::DRIVE_READONLY);

        $tokenPath = env('TOKEN_PATH');
        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            if($accessToken != null){
                $client->setAccessToken($accessToken);
            }
        }

        if ($client->isAccessTokenExpired()) {
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                $authUrl = $client->createAuthUrl();

                if(isset($_GET['code'])){
                    $authCode = trim($_GET['code']);
                    $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
                    $client->setAccessToken($accessToken);

                    echo "Access Token = " . json_encode($client->getAccessToken());

                    if (array_key_exists('error', $accessToken)) {
                        throw new Exception(join(', ', $accessToken));
                    }
                } else {
                    echo "Open the following link in your browser :<br>";
                    echo "<a href='" . $authUrl . "'>google drive create token</a>";
                }


            }
            // if (!file_exists(dirname($tokenPath))) {
            //     mkdir(dirname($tokenPath), 0700, true);
            // }
            file_put_contents($tokenPath, json_encode($client->getAccessToken()));
        }
        return $client;
    }

    public function getDraftPr(Request $request)
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first()->id_territory;
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('name', 'roles.group')->where('user_id', $nik)->first();

        // $cek_status = PR::join('tb_pr_draft', 'tb_pr_draft.id', 'tb_pr.id_draft_pr')->select('status_draft_pr')->get();
        if ($cek_role->name == 'VP Internal Chain Management' || $cek_role->name == 'Procurement & Vendor Management' || $cek_role->name == 'Supply Chain & IT Support Manager' || $cek_role->name == 'Chief Operating Officer' || $cek_role->name == 'Chief Executive Officer' || $cek_role->name == 'VP Program & Project Management' || $cek_role->name == 'VP Solutions & Partnership Management') {
            $getDataEPR = PRDraft::where('type_of_letter', 'EPR');
            // ->whereYear('tb_pr_draft.updated_at',date('Y'));
            if ($cek_role->name == 'Procurement & Vendor Management') {
                $getDataEPR = $getDataEPR->whereRaw("(`status` != 'CANCEL' && `status` != 'SENDED')")
                ;
            } else {
                $getDataEPR = $getDataEPR->whereRaw("(`status` != 'SAVED' AND `status` != 'CANCEL' AND `status` != 'SENDED')")
                ;
            }

        } else if ($cek_role->name == 'VP Sales'){
            $listTerritory = User::where('id_territory',$territory)->pluck('nik');
            $getDataEPR = PRDraft::where('type_of_letter', 'EPR')->whereIn('issuance',$listTerritory)->whereRaw("(`status` != 'CANCEL' && `status` != 'SENDED')");
        } else {
            $getDataEPR = PRDraft::where('type_of_letter', 'EPR')->where('issuance',$nik)->whereRaw("(`status` != 'CANCEL' && `status` != 'SENDED')");
        }

        if ($cek_role->name == 'VP Internal Chain Management' || $cek_role->name == 'Procurement & Vendor Management' || $cek_role->name == 'Supply Chain & IT Support Manager' || $cek_role->name == 'Chief Operating Officer' || $cek_role->name == 'Chief Executive Officer') {
            $getData = PRDraft::where('type_of_letter', 'IPR');
            // ->whereYear('tb_pr_draft.updated_at',date('Y'));
            if ($cek_role->name == 'Procurement & Vendor Management') {
                $getData = $getData->whereRaw("(`status` != 'CANCEL' AND `status` != 'SENDED')")
                ;
            } else {
                $getData = $getData->whereRaw("(`status` != 'CANCEL' AND `status` != 'SENDED' AND `status` != 'SAVED' AND `status`)")
                ;
            }

        } else if ($cek_role->name == 'VP Sales'){
            $listTerritory = User::where('id_territory',$territory)->pluck('nik');
            $getData = PRDraft::where('type_of_letter', 'IPR')->whereIn('issuance',$listTerritory)->whereRaw("(`status` != 'CANCEL' && `status` != 'SENDED')");
        } else if ($cek_role->name == 'VP Program & Project Management'){
            $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Program & Project Management')->pluck('nik');
            $getData = PRDraft::where('type_of_letter', 'IPR')->whereIn('issuance',$listGroup)->whereRaw("(`status` != 'CANCEL' && `status` != 'SENDED')");
        }
        // else if ($cek_role->name == 'BCD Manager'){
        //     $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','bcd')->pluck('nik');
        //     $getData = PRDraft::where('type_of_letter', 'IPR')->whereIn('issuance',$listGroup)->whereRaw("(`status` != 'CANCEL' && `status` != 'SENDED')");
        // }
        else if ($cek_role->name == 'VP Human Capital Management'){
            $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Human Capital Management')->pluck('nik');
            $getData = PRDraft::where('type_of_letter', 'IPR')->whereIn('issuance',$listGroup)->whereRaw("(`status` != 'CANCEL' && `status` != 'SENDED')");
        } else if ($cek_role->name == 'VP Solutions & Partnership Management'){
            $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Solutions & Partnership Management')->pluck('nik');
            $getData = PRDraft::where('type_of_letter', 'IPR')->whereIn('issuance',$listGroup)->whereRaw("(`status` != 'CANCEL' && `status` != 'SENDED')");
        } else if ($cek_role->name == 'VP Synergy System Management'){
            $listGroup = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','Synergy System Management')->pluck('nik');
            $getData = PRDraft::where('type_of_letter', 'IPR')->whereIn('issuance',$listGroup)->whereRaw("(`status` != 'CANCEL' && `status` != 'SENDED')");
        } else {
            $getData = PRDraft::where('type_of_letter', 'IPR')->where('issuance',$nik)->whereRaw("(`status` != 'CANCEL' && `status` != 'SENDED')");
        }

        // return $nik;

        // $getDataEPR = $getDataEPR->orderByRaw('FIELD(status, "CIRCULAR", "COMPARING", "VERIFIED", "FINALIZED", "DRAFT", "SAVED", "UNAPPROVED", "REJECT", "SENDED", "CANCEL")');
        // $getData = $getData->orderByRaw('FIELD(status, "CIRCULAR", "COMPARING", "VERIFIED", "FINALIZED", "DRAFT", "SAVED", "UNAPPROVED", "REJECT", "SENDED", "CANCEL")');
        // return $getDataEPR->get();

        // return $request->order;

        // $orderColumnIndex = $request->order[0]['column'] ?? '0';

        // $orderByName = 'no_pr';
        // switch($orderColumnIndex){
        //     case '0':
        //         $orderByName = 'no_pr';
        //         break;
        //     case '1':
        //         $orderByName = 'created_at';
        //         break;
        //     case '2':
        //         $orderByName = 'title';
        //         break;
        //     case '3':
        //         $orderByName = 'name';
        //         break;
        //     case '4':
        //         $orderByName = 'to';
        //         break;
        //     case '5':
        //         $orderByName = 'nominal';
        //         break;
        //     default:
        //         $orderByName = 'status';
        //         break;
        // }

        $getDataFinal = $getData->get();
        $getDataEPRFinal = $getDataEPR->get();

        $data = $getDataFinal->merge($getDataEPRFinal);

        $order = ["CIRCULAR","UNAPPROVED", "REJECT", "COMPARING", "VERIFIED", "FINALIZED", "DRAFT", "SAVED", "SENDED", "CANCEL"];

        $data = $data->sort(function ($a, $b) use ($order) {
            // return array_search($item->status, $order);
            $statusComparison = array_search($a->status, $order) <=> array_search($b->status, $order);

            if ($statusComparison === 0) {
                return strtotime($a->date) <=> strtotime($b->date); // Gantilah 'date' dengan nama kolom tanggal yang sesuai
            }

            return $statusComparison;
        });

        if ($request->order[0]['dir'] == 'asc') {
            // $data = $data->sortBy($orderByName)->values()->all();
            $data = $data->values()->all();
        }else{
            // $data = $data->sortByDesc($orderByName)->values()->all();
            $data = $data->values()->all();
        }

        $totalRecords = collect($data)->count();
        // Apply pagination
        $start = $request->input('start', 0);
        $length = $request->input('length', $request->input); // Number of records per page
        $draw = $request->input('draw');

        if ($draw > 1) {
            $datas = collect($data)->skip($start)->take($length);
            $data = [];
            $data = array_values($datas->toArray());
        }else{
            $data = collect($data)->skip($start)->take($length);
        }

        return response()->json([
            'draw' => $request->input('draw'),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $data,
            'length' => $length,
        ]);

        // $getData = $getData->get();
        // $getDataEPR = $getDataEPR->get();

        // return array("data"=>$getData->merge($getDataEPR));

    }

    public function getPid(Request $request)
    {
        $data = collect(SalesProject::join('sales_lead_register', 'sales_lead_register.lead_id', '=', 'tb_id_project.lead_id')->join('users', 'users.nik', '=', 'sales_lead_register.nik')->select(DB::raw('`tb_id_project`.`id_project` AS `id`,`tb_id_project`.`id_project` AS `text`'))->where('sales_lead_register.lead_id', $request->lead_id)->get());

        return array("data" => $data);
    }

    public function getLeadByPid(Request $request)
    {
        if (SalesProject::where('tb_id_project.id_project', $request->pid)->exists()) {
            $data = collect(SalesProject::join('sales_lead_register', 'sales_lead_register.lead_id', '=', 'tb_id_project.lead_id')->select(DB::raw('`sales_lead_register`.`lead_id` AS `id`,`sales_lead_register`.`lead_id` AS `text`'))->where('tb_id_project.id_project', $request->pid)->get());

            $getLinkSbe = DB::table('tb_sbe')->join('tb_sbe_document','tb_sbe_document.id_sbe','tb_sbe.id')->where('lead_id',$data[0]['id'])->select('link_drive','document_name','document_location')->orderby('tb_sbe_document.id','desc')->get();
        } else{
            $data = [];
            $getLinkSbe = [];
        }

        return collect([
            'data' => $data,
            'linkSbe' => $getLinkSbe
        ]);
    }

    public function getPidAll(Request $request)
    {
        $pid = collect(SalesProject::join('sales_lead_register', 'sales_lead_register.lead_id', '=', 'tb_id_project.lead_id')
            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
            ->select(DB::raw('`tb_id_project`.`id_project` AS `id`,`tb_id_project`.`id_project` AS `text`'))
            ->where('id_company', '1')
            ->orderBy('tb_id_project.created_at', 'desc')
            ->get());

        return array("data" => $pid);
    }

    public function getQuote(Request $request)
    {
        $data = collect(Quote::join('sales_tender_process', 'sales_tender_process.quote_number2', '=', 'tb_quote.quote_number')->select(DB::raw('`tb_quote`.`quote_number` AS `text`, `tb_quote`.`quote_number` AS `id`'))->where('sales_tender_process.lead_id', $request->lead_id)->get());

        return array("data" => $data);
    }

    public function getLeadRegister(Request $request)
    {
        $lead  =  collect(Sales::join('users', 'users.nik', '=', 'sales_lead_register.nik')
            ->join('tb_contact', 'sales_lead_register.id_customer', '=', 'tb_contact.id_customer')
            ->select(DB::raw('`sales_lead_register`.`lead_id` AS `id`,`sales_lead_register`.`lead_id` AS `text`'))
            ->where('id_company', '1')
            ->where('sales_lead_register.result','WIN')
            ->get());

        return array("data" => $lead);
    }

    public function getProductPr(Request $request)
    {
        $getProductPr = PrProduct::join('tb_pr_product_draft', 'tb_pr_product.id', '=', 'tb_pr_product_draft.id_product')
            ->join('tb_pr_draft', 'tb_pr_draft.id', '=', 'tb_pr_product_draft.id_draft_pr')
            ->select('tb_pr_product.name_product', 'tb_pr_product.description', 'tb_pr_product.qty', 'tb_pr_product.unit', 'tb_pr_product.nominal_product', 'tb_pr_product.grand_total', DB::raw("(CASE WHEN (serial_number is null) THEN '-' ELSE serial_number END) as serial_number"), DB::raw("(CASE WHEN (part_number is null) THEN '-' ELSE part_number END) as part_number"), 'tb_pr_product.id as id_product')
            ->where('id_draft_pr', $request->no_pr)->orderBy('tb_pr_product_draft.id_product', 'asc')->get();

        return array("data"=>$getProductPr);
    }

    public function getProductById(Request $request)
    {
        $getProduct = PrProduct::leftJoin('tb_pr_product_draft', 'tb_pr_product_draft.id_product', '=', 'tb_pr_product.id')->leftJoin('tb_pr_product_compare','tb_pr_product_compare.id_product','tb_pr_product.id')
            // ->join('tb_pr_draft', 'tb_pr_draft.id', '=', 'tb_pr_product_draft.id_draft_pr')
            ->select('name_product', 'qty', 'unit', 'tb_pr_product.description', 'nominal_product', 'grand_total', DB::raw("(CASE WHEN (serial_number is null) THEN '-' ELSE serial_number END) as serial_number"), DB::raw("(CASE WHEN (part_number is null) THEN '-' ELSE part_number END) as part_number"), 'tb_pr_product.id as id_product')->where('tb_pr_product.id', $request->id_product)->first();

        return array("data"=>$getProduct);
    }

    public function getProductCompareById(Request $request)
    {
        $getProduct = PrProduct::join('tb_pr_product_compare', 'tb_pr_product_compare.id_product', '=', 'tb_pr_product.id')
            ->join('tb_pr_compare', 'tb_pr_compare.id', '=', 'tb_pr_product_compare.id_compare_pr')
            ->select('name_product', 'qty', 'unit', 'tb_pr_product.description', 'nominal_product', 'grand_total', DB::raw("(CASE WHEN (serial_number is null) THEN '-' ELSE serial_number END) as serial_number"), DB::raw("(CASE WHEN (part_number is null) THEN '-' ELSE part_number END) as part_number"), 'isRupiah', 'tb_pr_product.id as id_product')->where('tb_pr_product.id', $request->id_product)->first();

        return array("data"=>$getProduct);
    }

    public function storeSupplierPr(Request $request)
    {
        $tambah = new PRDraft();
        $tambah->type_of_letter = $request['selectType'];
        $tambah->to = $request['inputTo'];
        $tambah->attention = $request['inputAttention'];
        $tambah->title = $request['inputSubject'];
        $tambah->email = $request['inputEmail'];
        $tambah->phone = $request['inputPhone'];
        $tambah->address = $request['inputAddress'];
        $tambah->fax = $request['inputFax'];
        $tambah->issuance = Auth::User()->nik;
        $tambah->category = $request['selectCategory'];
        if ($request['selectMethode'] == 'purchase_order') {
            $tambah->request_method = 'Purchase Order';
        } elseif ($request['selectMethode'] == 'payment') {
            $tambah->request_method = 'Payment';
        } elseif ($request['selectMethode'] = 'reimbursement') {
            $tambah->request_method = 'Reimbursement';
        }
        $tambah->status = 'SAVED';
        $tambah->status_used = 'Selected';
        $tambah->isCircular = 'False';
        $tambah->isCommit = $request['cbCommit'];
        $tambah->save();

        $no_akhir = $tambah->id;

        $activity = new PRActivity();
        $activity->id_draft_pr = $tambah->id;
        $activity->date_time = Carbon::now()->toDateTimeString();
        $activity->status = 'SAVED';
        $activity->operator = Auth::User()->name;
        $activity->activity = 'Create Supplier';
        $activity->save();

        if ($activity->status == 'SAVED') {
            $tambah = new PRDraftVerify();
            $tambah->id_draft_pr = $no_akhir;
            $tambah->verify_type_of_letter = 'False';
            $tambah->verify_category = 'False';
            $tambah->verify_to = 'False';
            $tambah->verify_email = 'False';
            $tambah->verify_phone = 'False';
            $tambah->verify_attention = 'False';
            $tambah->verify_title = 'False';
            $tambah->verify_address = 'False';
            $tambah->verify_request_method = 'False';
            $tambah->verify_pid = 'False';
            $tambah->verify_lead_id = 'False';
            $tambah->verify_quote_number = 'False';
            $tambah->verify_term_payment = 'False';
            $tambah->save();
        }

        return $no_akhir;
    }

    public function updateSupplierPr(Request $request)
    {
        $update = PRDraft::where('id', $request->no_pr)->first();
        $update->type_of_letter = $request['selectType'];
        $update->to = $request['inputTo'];
        $update->attention = $request['inputAttention'];
        $update->title = $request['inputSubject'];
        $update->email = $request['inputEmail'];
        $update->phone = $request['inputPhone'];
        $update->address = $request['inputAddress'];
        $update->fax = $request['inputFax'];
        // $update->issuance = Auth::User()->nik;
        $update->category = $request['selectCategory'];
        if ($request['selectMethode'] == 'purchase_order') {
            $update->request_method = 'Purchase Order';
        } elseif ($request['selectMethode'] == 'payment') {
            $update->request_method = 'Payment';
        } elseif ($request['selectMethode'] = 'reimbursement') {
            $update->request_method = 'Reimbursement';
        }
        $update->isCommit = $request['cbCommit'];
        $update->save();

        $no_akhir = $update->id;

        $update_pr = PR::where('id_draft_pr', $request->no_pr)->first();
        if (!empty($update_pr)) {
            $update_pr->type_of_letter = $request['selectType'];
            $update_pr->to = $request['inputTo'];
            $update_pr->attention = $request['inputAttention'];
            $update_pr->title = $request['inputSubject'];
            $update_pr->email = $request['inputEmail'];
            $update_pr->phone = $request['inputPhone'];
            $update_pr->address = $request['inputAddress'];
            $update_pr->fax = $request['inputFax'];
            // $update->issuance = Auth::User()->nik;
            $update_pr->category = $request['selectCategory'];
            if ($request['selectMethode'] == 'purchase_order') {
                $update_pr->request_method = 'Purchase Order';
            } elseif ($request['selectMethode'] == 'payment') {
                $update_pr->request_method = 'Payment';
            } elseif ($request['selectMethode'] = 'reimbursement') {
                $update_pr->request_method = 'Reimbursement';
            }
            $update_pr->save();
        }

        return $no_akhir;
    }

    public function storeProductPr(Request $request)
    {
        $tambah = new PrProduct();
        $tambah->name_product = $request['inputNameProduct'];
        $tambah->description = $request['inputDescProduct'];
        $tambah->nominal_product = str_replace(',', '', $request['inputPriceProduct']);
        $tambah->qty = $request['inputQtyProduct'];
        $tambah->serial_number = $request['inputSerialNumber'];
        $tambah->part_number = $request['inputPartNumber'];
        $tambah->unit = $request['selectTypeProduct'];
        $tambah->grand_total = str_replace(',', '', $request['inputTotalPrice']);
        $tambah->budget_type = $request['selectBudgetType'];
        if (isset($request['inputFromBudgetType'])) {
            $tambah->for = $request['inputFromBudgetType'];
        }
        $tambah->save();

        // $updateDraft = PRDraft::where('id',$request['no_pr'])->first();
        // if (isset($request['inputFromBudgetType'])) {
        //     $updateDraft->from = $request['inputFromBudgetType'];
        // }else{
        //     $updateDraft->from = $updateDraft->issuance;
        // }
        // $updateDraft->update();

        $tambah_product = new PRProductDraft();
        $tambah_product->id_draft_pr = $request['no_pr'];
        $tambah_product->id_product = $tambah->id;
        $tambah_product->added = Carbon::now()->toDateTimeString();
        $tambah_product->save();
    }

    public function updateProductPr(Request $request)
    {
        $update = PrProduct::where('id', $request->id_product)->first();
        $update->name_product = $request['inputNameProduct'];
        $update->description = $request['inputDescProduct'];
        $update->nominal_product = str_replace(',', '', $request['inputPriceProduct']);
        $update->qty = $request['inputQtyProduct'];
        $update->unit = $request['selectTypeProduct'];
        $update->serial_number = $request['inputSerialNumber'];
        $update->part_number = $request['inputPartNumber'];
        $update->budget_type = $request['selectBudgetType'];
        $update->grand_total = str_replace(',', '', $request['inputTotalPrice']);
        $update->save();
    }

    public function storeTax(Request $request)
    {
        $update = PRDraft::where('id', $request->no_pr)->first();
        $update->status_tax = $request->status_tax;
        $update->isRupiah = $request->isRupiah;
        $update->tax_pb = $request->tax_pb;
        $update->service_charge = $request->service_charge;
        $update->discount = $request->discount;
        $update->save();

        if (PR::where('id_draft_pr',$request->no_pr)->exists()) {
            $update = PR::where('id_draft_pr', $request->no_pr)->first();
            $update->status_tax = $request->status_tax;
            $update->isRupiah = $request->isRupiah;
            $update->tax_pb = $request->tax_pb;
            $update->service_charge = $request->service_charge;
            $update->discount = $request->discount;
            $update->save();
        }

        $update_pr = PR::where('id_draft_pr', $request->no_pr)->first();
        if (!empty($update_pr)) {
            $update_pr->status_tax = $request->status_tax;
            $update->isRupiah = $request->isRupiah;
            $update_pr->save();
        }
    }

    public function storeTaxComparing(Request $request)
    {
        $update = PRCompare::where('id', $request->no_pr)->first();
        $update->status_tax = $request->status_tax;
        $update->isRupiah = $request->isRupiah;
        $update->discount = $request->discount;
        $update->tax_pb = $request->tax_pb;
        $update->service_charge = $request->service_charge;
        $update->save();
    }

    public function storeDokumen(Request $request)
    {
        $get_pr = DB::table('tb_pr_draft')->select('type_of_letter', 'parent_id_drive')->where('id', $request['no_pr'])->first();
        $count = PrDokumen::join('tb_pr_document_draft', 'tb_pr_document_draft.id_document', '=', 'tb_pr_document.id')->where('id_draft_pr', $request['no_pr'])->count();
        $directory = "draft_pr/";

        // Eksternal Purchase Request
        if ($get_pr->type_of_letter == 'EPR') {

            if (SalesProject::where('tb_id_project.id_project', $request->selectPid)->exists()) {
                $data = collect(SalesProject::join('sales_lead_register', 'sales_lead_register.lead_id', '=', 'tb_id_project.lead_id')->select(DB::raw('`sales_lead_register`.`lead_id` AS `id`,`sales_lead_register`.`lead_id` AS `text`'))->where('tb_id_project.id_project', $request->selectPid)->get());

                $getLinkSbe = DB::table('tb_sbe')->join('tb_sbe_document','tb_sbe_document.id_sbe','tb_sbe.id')->where('lead_id',$data[0]['id'])->select('link_drive','document_name','document_location')->orderby('tb_sbe_document.id','desc')->get();
                $linkSbe = json_decode($getLinkSbe, true);
            } else{
                $data = [];
                $getLinkSbe = [];
            }

            //tambah quote supplier
            if ($request->inputQuoteSupplier != '-') {
                $allowedfileExtension   = ['jpg','png', 'jpeg', 'JPG', 'PNG', 'pdf', 'PDF'];
                $file                   = $request->file('inputQuoteSupplier');
                $fileName               = $file->getClientOriginalName();
                $strfileName            = explode('.', $fileName);
                $lastElement            = end($strfileName);
                $nameDoc                = $request['no_pr'] . '_quote_supplier.' . $lastElement;
                $extension              = $file->getClientOriginalExtension();
                $check                  = in_array($extension,$allowedfileExtension);

                if ($count == 0) {
                    $tambah_quote = new PrDokumen();
                    if ($check) {
                        // $request->file('inputQuoteSupplier')->move("draft_pr/", $nameDoc);
                        $this->uploadToLocal($request->file('inputQuoteSupplier'),$directory,$nameDoc);
                        $tambah_quote->dokumen_name             = "Quote Supplier";
                    } else {
                        return redirect()->back()->with('alert','Oops! Only pdf');
                    }

                    if(isset($fileName)){
                        $pdf_url = urldecode(url("draft_pr/" . $nameDoc));
                        $pdf_name = $nameDoc;
                    } else {
                        $pdf_url = 'http://test-drive.sinergy.co.id:8000/Lampiran.pdf';
                        $pdf_name = 'pdf_lampiran';
                    }

                    if ($get_pr->parent_id_drive == null) {
                        $parentID = $this->googleDriveMakeFolder($request->no_pr . ' Draft PR ' . date('Y'), $request->no_pr);
                    } else {
                        $parentID = [];
                        $parent_id = explode('"', $get_pr->parent_id_drive)[1];
                        array_push($parentID,$parent_id);

                        // $data = PR::where('id_draft_pr', $request->no_pr)->first();
                        // if (empty($data)) {
                        //     $data_dokumen = PRDocumentDraft::where('id_draft_pr', $request->no_pr)
                        //             ->join("tb_pr_document","tb_pr_document.id","tb_pr_document_draft.id_document")
                        //             ->where("tb_pr_document.dokumen_name","=","Quote Supplier")
                        //             ->orderBy('tb_pr_document_draft.id','desc')
                        //             ->first();

                        //     if (!empty($data_dokumen)) {
                        //         if (strpos($data_dokumen->dokumen_location, 'Revisi')) {
                        //             $pdf_name = explode("(",$data_dokumen->dokumen_location)[0] . "" . "(Revisi_" . ((int)substr($data_dokumen->dokumen_location,strpos($data_dokumen->dokumen_location,"Revisi")+ 7,1)+1) . ")." . explode(".",$data_dokumen->dokumen_location)[1];
                        //             $pdf_name = explode('/', $pdf_name)[1];
                        //         } else {
                        //             $pdf_name = explode(".",$pdf_name)[0] . "_" . "(Revisi_1)." . explode(".",$pdf_name)[1];
                        //         }
                        //     }
                        // } else{
                        //     if (strpos($data->title, 'Revisi')) {
                        //         $pdf_name = explode(".",$pdf_name)[0] . "" . "(Revisi" . ((int)substr($data->title,strpos($data->title,"Revisi ") + 7,1)+1) . ")." . explode(".",$pdf_name)[1];
                        //     } else {
                        //         $pdf_name = explode(".",$pdf_name)[0] . "_" . "(Revisi_1)." . explode(".",$pdf_name)[1];
                        //     }
                        // }
                    }

                    $tambah_quote->dokumen_location         = "draft_pr/" . $pdf_name;
                    $tambah_quote->link_drive = $this->googleDriveUploadCustom($pdf_name,$directory . $pdf_name,$parentID);
                    $tambah_quote->save();

                    $tambah_quote_draft = new PRDocumentDraft();
                    $tambah_quote_draft->id_draft_pr = $request['no_pr'];
                    $tambah_quote_draft->id_document = $tambah_quote->id;
                    $tambah_quote_draft->added = Carbon::now()->toDateTimeString();
                    $tambah_quote_draft->save();

                } else {
                    $getId = PrDokumen::join('tb_pr_document_draft', 'tb_pr_document_draft.id_document', '=', 'tb_pr_document.id')->select('tb_pr_document.id', 'tb_pr_document_draft.id as id_dokumen_draft')->where('id_draft_pr', $request['no_pr'])->where('dokumen_name', 'Quote Supplier')->first();
                    // return $getId;
                    $update_quote = PrDokumen::where('id', $getId->id)->first();
                    if ($check) {
                        // $request->file('inputQuoteSupplier')->move("draft_pr/", $nameDoc);
                        $this->uploadToLocal($request->file('inputQuoteSupplier'),$directory,$nameDoc);
                        $update_quote->dokumen_name             = "Quote Supplier";
                    } else {
                        return redirect()->back()->with('alert','Oops! Only pdf');
                    }

                    if(isset($fileName)){
                        $pdf_url = urldecode(url("draft_pr/" . $nameDoc));
                        $pdf_name = $nameDoc;
                    } else {
                        $pdf_url = 'http://test-drive.sinergy.co.id:8000/Lampiran.pdf';
                        $pdf_name = 'pdf_lampiran';
                    }

                    // if ($get_pr->parent_id_drive == null) {
                    //     $parentID = $this->googleDriveMakeFolder($request->no_pr . ' Draft PR ' . date('Y'), $request->no_pr);
                    // } else {
                    $parentID = [];
                    $parent_id = explode('"', $get_pr->parent_id_drive)[1];
                    array_push($parentID,$parent_id);
                    $pdf_name = explode(".",$pdf_name)[0] . "." . explode(".",$pdf_name)[1];
                    // }

                    $update_quote->dokumen_location         = "draft_pr/" . $pdf_name;
                    $update_quote->link_drive = $this->googleDriveUploadCustom($pdf_name,$directory . $pdf_name,$parentID);
                    $update_quote->save();

                    // $update_quote_draft = PRDocumentDraft::where('id', $getId->id_dokumen_draft)->first();
                    // $update_quote_draft->id_draft_pr = $request['no_pr'];
                    // $update_quote_draft->id_document = $update_quote->id;
                    // $update_quote_draft->added = Carbon::now()->toDateTimeString();
                    // $update_quote_draft->save();
                }


                $update_parent = PRDraft::where('id', $request['no_pr'])->first();
                $update_parent->parent_id_drive = $parentID;
                $update_parent->save();
            }

            $get_pr = PRDraft::select('type_of_letter', 'parent_id_drive')->where('id', $request['no_pr'])->first();

            if ($request->inputSPK != '-') {
                $allowedfileExtension   = ['jpg','png', 'jpeg', 'JPG', 'PNG', 'pdf', 'PDF'];
                $file                   = $request->file('inputSPK');
                $fileName               = $file->getClientOriginalName();
                $strfileName            = explode('.', $fileName);
                $lastElement            = end($strfileName);
                $nameDoc                = $request['no_pr'] . '_spk.' . $lastElement;
                $extension              = $file->getClientOriginalExtension();
                $check                  = in_array($extension,$allowedfileExtension);

                if ($count == 0) {
                    $tambah_spk = new PrDokumen();
                    if ($check) {
                        // $request->file('inputSPK')->move("draft_pr/", $nameDoc);
                        $this->uploadToLocal($request->file('inputSPK'),$directory,$nameDoc);
                        $tambah_spk->dokumen_name             = "SPK";
                    } else {
                        return redirect()->back()->with('alert','Oops! Only pdf');
                    }

                    if(isset($fileName)){
                        $pdf_url = urldecode(url("draft_pr/" . $nameDoc));
                        $pdf_name = $nameDoc;
                    } else {
                        $pdf_url = 'http://test-drive.sinergy.co.id:8000/Lampiran.pdf';
                        $pdf_name = 'pdf_lampiran';
                    }

                    $parentID = [];
                    $parent_id = explode('"', $get_pr->parent_id_drive)[1];
                    array_push($parentID,$parent_id);

                    // $data = PR::where('id_draft_pr', $request->no_pr)->first();
                    // if (empty($data)) {
                    //     $data_dokumen = PRDocumentDraft::where('id_draft_pr', $request->no_pr)
                    //             ->join("tb_pr_document","tb_pr_document.id","tb_pr_document_draft.id_document")
                    //             ->where("tb_pr_document.dokumen_name","=","SPK")
                    //             ->orderBy('tb_pr_document_draft.id','desc')
                    //             ->first();

                    //     if (!empty($data_dokumen)) {
                    //             if (strpos($data_dokumen->dokumen_location, 'Revisi')) {
                    //                 $pdf_name = explode("(",$data_dokumen->dokumen_location)[0] . "" . "(Revisi_" . ((int)substr($data_dokumen->dokumen_location,strpos($data_dokumen->dokumen_location,"Revisi")+ 7,1)+1) . ")." . explode(".",$data_dokumen->dokumen_location)[1];
                    //                 $pdf_name = explode('/', $pdf_name)[1];
                    //             } else {
                    //                 $pdf_name = explode(".",$pdf_name)[0] . "_" . "(Revisi_1)." . explode(".",$pdf_name)[1];
                    //             }
                    //         }
                    // } else{

                    //     if (strpos($data->title, 'Revisi')) {
                    //         $pdf_name = explode(".",$pdf_name)[0] . "" . "(Revisi" . ((int)substr($data->title,strpos($data->title,"Revisi ") + 7,1)+1) . ")." . explode(".",$pdf_name)[1];
                    //     } else {
                    //         $pdf_name = explode(".",$pdf_name)[0] . "_" . "(Revisi_1)." . explode(".",$pdf_name)[1];
                    //     }
                    // }

                    $tambah_spk->dokumen_location         = "draft_pr/" . $pdf_name;
                    $tambah_spk->link_drive = $this->googleDriveUploadCustom($pdf_name,$directory . $pdf_name,$parentID);
                    $tambah_spk->save();

                    $tambah_spk_draft = new PRDocumentDraft();
                    $tambah_spk_draft->id_draft_pr = $request['no_pr'];
                    $tambah_spk_draft->id_document = $tambah_spk->id;
                    $tambah_spk_draft->added = Carbon::now()->toDateTimeString();
                    $tambah_spk_draft->save();
                } else {
                    $getId = PrDokumen::join('tb_pr_document_draft', 'tb_pr_document_draft.id_document', '=', 'tb_pr_document.id')->select('tb_pr_document.id', 'tb_pr_document_draft.id as id_dokumen_draft')->where('id_draft_pr', $request['no_pr'])->where('dokumen_name', 'SPK')->first();
                    $update_spk = PrDokumen::where('id', $getId->id)->first();
                    if ($check) {
                        // $request->file('inputSPK')->move("draft_pr/", $nameDoc);
                        $this->uploadToLocal($request->file('inputSPK'),$directory,$nameDoc);
                        $update_spk->dokumen_name             = "SPK";
                    } else {
                        return redirect()->back()->with('alert','Oops! Only pdf');
                    }

                    if(isset($fileName)){
                        $pdf_url = urldecode(url("draft_pr/" . $nameDoc));
                        $pdf_name = $nameDoc;
                    } else {
                        $pdf_url = 'http://test-drive.sinergy.co.id:8000/Lampiran.pdf';
                        $pdf_name = 'pdf_lampiran';
                    }

                    $parentID = [];
                    $parent_id = explode('"', $get_pr->parent_id_drive)[1];
                    array_push($parentID,$parent_id);
                    $pdf_name = explode(".",$pdf_name)[0] . "." . explode(".",$pdf_name)[1];

                    $update_spk->dokumen_location         = "draft_pr/" . $pdf_name;
                    $update_spk->link_drive = $this->googleDriveUploadCustom($pdf_name,$directory . $pdf_name,$parentID);
                    $update_spk->save();
                }
            }

            // tambah SBE
            if ($request->inputSBE != '-') {
                $allowedfileExtension   = ['jpg','png', 'jpeg', 'JPG', 'PNG', 'pdf', 'PDF'];
                $file                   = $request->file('inputSBE');
                $fileName               = $file->getClientOriginalName();
                $strfileName            = explode('.', $fileName);
                $lastElement            = end($strfileName);
                $nameDoc                = $request['no_pr'] . '_sbe.' . $lastElement;
                $extension              = $file->getClientOriginalExtension();
                $check                  = in_array($extension,$allowedfileExtension);

                if ($count == 0) {
                    $tambah_sbe = new PrDokumen();
                    if ($check) {
                        if (count($getLinkSbe) > 0) {
                            $fileId = $this->extractFileIdFromDriveLink($linkSbe[0]['link_drive']);

                            $downloadedFilePath = $this->downloadFromDrive($fileId);
                            $uploadFilePath = $downloadedFilePath;
                        } else {
                            $this->uploadToLocal($request->file('inputSBE'),$directory,$nameDoc);
                        }
                        // $request->file('inputSBE')->move("draft_pr/", $nameDoc);
                        $tambah_sbe->dokumen_name             = "SBE";
                    } else {
                        return redirect()->back()->with('alert','Oops! Only pdf');
                    }

                    if(isset($fileName)){
                        $pdf_url = urldecode(url("draft_pr/" . $nameDoc));
                        $pdf_name = $nameDoc;
                    } else {
                        $pdf_url = 'http://test-drive.sinergy.co.id:8000/Lampiran.pdf';
                        $pdf_name = 'pdf_lampiran';
                    }

                    $parentID = [];
                    $parent_id = explode('"', $get_pr->parent_id_drive)[1];
                    array_push($parentID,$parent_id);

                    // $data = PR::where('id_draft_pr', $request->no_pr)->first();
                    // if (empty($data)) {
                    //     $data_dokumen = PRDocumentDraft::where('id_draft_pr', $request->no_pr)
                    //         ->join("tb_pr_document","tb_pr_document.id","tb_pr_document_draft.id_document")
                    //         ->where("tb_pr_document.dokumen_name","=","SBE")
                    //         ->orderBy('tb_pr_document_draft.id','desc')
                    //         ->first();

                    //     if (!empty($data_dokumen)) {
                    //         if (strpos($data_dokumen->dokumen_location, 'Revisi')) {
                    //             $pdf_name = explode("(",$data_dokumen->dokumen_location)[0] . "" . "(Revisi_" . ((int)substr($data_dokumen->dokumen_location,strpos($data_dokumen->dokumen_location,"Revisi")+ 7,1)+1) . ")." . explode(".",$data_dokumen->dokumen_location)[1];
                    //             $pdf_name = explode('/', $pdf_name)[1];
                    //         } else {
                    //             $pdf_name = explode(".",$pdf_name)[0] . "_" . "(Revisi_1)." . explode(".",$pdf_name)[1];
                    //         }
                    //     }
                    // } else{
                    //     if (strpos($data->title, 'Revisi')) {
                    //         $pdf_name = explode(".",$pdf_name)[0] . "" . "(Revisi" . ((int)substr($data->title,strpos($data->title,"Revisi ") + 7,1)+1) . ")." . explode(".",$pdf_name)[1];
                    //     } else {
                    //         $pdf_name = explode(".",$pdf_name)[0] . "_" . "(Revisi_1)." . explode(".",$pdf_name)[1];
                    //     }
                    // }

                    $tambah_sbe->dokumen_location         = "draft_pr/" . $pdf_name;
                    // $tambah_sbe->link_drive = $this->googleDriveUploadCustom($pdf_name,$directory . $pdf_name,$parentID);
                    if (count($getLinkSbe) > 0) {
                        $tambah_sbe->link_drive = $this->googleDriveUploadCustom($pdf_name,$uploadFilePath,$parentID);
                    } else {
                        $tambah_sbe->link_drive = $this->googleDriveUploadCustom($pdf_name,$directory . $pdf_name,$parentID);
                    }
                    $tambah_sbe->save();

                    $tambah_sbe_draft = new PRDocumentDraft();
                    $tambah_sbe_draft->id_draft_pr = $request['no_pr'];
                    $tambah_sbe_draft->id_document = $tambah_sbe->id;
                    $tambah_sbe_draft->added = Carbon::now()->toDateTimeString();
                    $tambah_sbe_draft->save();
                } else {
                    $getId = PrDokumen::join('tb_pr_document_draft', 'tb_pr_document_draft.id_document', '=', 'tb_pr_document.id')->select('tb_pr_document.id', 'tb_pr_document_draft.id as id_dokumen_draft')->where('id_draft_pr', $request['no_pr'])->where('dokumen_name', 'SBE')->first();
                    $update_sbe = PrDokumen::where('id', $getId->id)->first();
                    if ($check) {
                        if (count($getLinkSbe) > 0) {
                            $fileId = $this->extractFileIdFromDriveLink($linkSbe[0]['link_drive']);

                            $downloadedFilePath = $this->downloadFromDrive($fileId);
                            $uploadFilePath = $downloadedFilePath;
                        } else {
                            $this->uploadToLocal($request->file('inputSBE'),$directory,$nameDoc);
                        }
                        // $request->file('inputSBE')->move("draft_pr/", $nameDoc);
                        $update_sbe->dokumen_name             = "SBE";
                    } else {
                        return redirect()->back()->with('alert','Oops! Only pdf');
                    }

                    if(isset($fileName)){
                        $pdf_url = urldecode(url("draft_pr/" . $nameDoc));
                        $pdf_name = $nameDoc;
                    } else {
                        $pdf_url = 'http://test-drive.sinergy.co.id:8000/Lampiran.pdf';
                        $pdf_name = 'pdf_lampiran';
                    }

                    $parentID = [];
                    $parent_id = explode('"', $get_pr->parent_id_drive)[1];
                    array_push($parentID,$parent_id);

                    $pdf_name = explode(".",$pdf_name)[0] . "." . explode(".",$pdf_name)[1];

                    $update_sbe->dokumen_location         = "draft_pr/" . $pdf_name;
                    if (count($getLinkSbe) > 0) {
                        $update_sbe->link_drive = $this->googleDriveUploadCustom($pdf_name,$uploadFilePath,$parentID);
                    } else {
                        $update_sbe->link_drive = $this->googleDriveUploadCustom($pdf_name,$directory . $pdf_name,$parentID);
                    }
                    $update_sbe->save();
                }

            }

            // return gettype($request->arrInputDocPendukung);
            $dataAll = json_decode($request->arrInputDocPendukung,true);
            // return count($dataAll);
            foreach ($dataAll as $key => $data) {
                // if (in_array("", $request->inputDocPendukung)) {
                if($request->inputDocPendukung[0] != '-'){
                    // return $request->inputDocPendukung[0];
                    $allowedfileExtension   = ['jpg','png', 'jpeg', 'JPG', 'PNG', 'pdf', 'PDF'];
                    $file                   = $request->file('inputDocPendukung')[$key];
                    $fileName               = $file->getClientOriginalName();
                    $strfileName            = explode('.', $fileName);
                    $lastElement            = end($strfileName);
                    $nameDoc                = $data['no_pr'] . '_' . $data['nameDocPendukung'] . '.' . $lastElement;
                    $extension              = $file->getClientOriginalExtension();
                    $check                  = in_array($extension,$allowedfileExtension);
                    $tambah_dok = new PrDokumen();
                    if ($check) {
                        $this->uploadToLocal($request->file('inputDocPendukung')[$key],$directory,$nameDoc);
                        $tambah_dok->dokumen_name             = $data['nameDocPendukung'];

                    } else {
                        return redirect()->back()->with('alert','Oops! Only pdf');
                    }

                    if(isset($fileName)){
                        $pdf_url = urldecode(url("draft_pr/" . $nameDoc));
                        $pdf_name = $nameDoc;
                    } else {
                        $pdf_url = 'http://test-drive.sinergy.co.id:8000/Lampiran.pdf';
                        $pdf_name = 'pdf_lampiran';
                    }

                    $parentID = [];
                    $parent_id = explode('"', $get_pr->parent_id_drive)[1];
                    array_push($parentID,$parent_id);

                    $pdf_name = explode(".",$pdf_name)[0] . "." . explode(".",$pdf_name)[1];
                    // $data = PR::where('id_draft_pr', $request->no_pr)->first();
                    // if (empty($data)) {
                    //     $data_dokumen =  PRDocumentDraft::where('id_draft_pr', $request->no_pr)
                    //         ->join("tb_pr_document","tb_pr_document.id","tb_pr_document_draft.id_document")
                    //         ->where('dokumen_name', '=', $tambah_dok->dokumen_name )
                    //         ->orderBy('tb_pr_document_draft.id','desc')
                    //         ->first();

                    //     // return $data_dokumen;

                    //     if (!empty($data_dokumen)) {
                    //         if (strpos($data_dokumen->dokumen_location, 'Revisi')) {
                    //             // return 'disini1';
                    //             $pdf_name = explode("(",$data_dokumen->dokumen_location)[0] . "" . "(Revisi_" . ((int)substr($data_dokumen->dokumen_location,strpos($data_dokumen->dokumen_location,"Revisi")+ 7,1)+1) . ")." . explode(".",$data_dokumen->dokumen_location)[1];
                    //             $pdf_name = explode('/', $pdf_name)[1];
                    //         } else {
                    //             // return 'disini2';
                    //             $pdf_name = explode(".",$pdf_name)[0] . "_" . "(Revisi_1)." . explode(".",$pdf_name)[1];
                    //         }
                    //     }
                    // } else{
                    //     if (strpos($data->title, 'Revisi')) {
                    //         $pdf_name = explode(".",$pdf_name)[0] . "" . "(Revisi" . ((int)substr($data->title,strpos($data->title,"Revisi ") + 7,1)+1) . ")." . explode(".",$pdf_name)[1];
                    //     } else {
                    //         $pdf_name = explode(".",$pdf_name)[0] . "_" . "(Revisi_1)." . explode(".",$pdf_name)[1];
                    //     }
                    // }

                    $tambah_dok->dokumen_location         = "draft_pr/".$pdf_name;
                    $tambah_dok->save();

                    $update_link = PrDokumen::where('id', $tambah_dok->id)->first();
                    $update_link->link_drive = $this->googleDriveUploadCustom($pdf_name,$directory . $pdf_name,$parentID);
                    $update_link->save();

                    $tambah_dok_draft = new PRDocumentDraft();
                    $tambah_dok_draft->id_draft_pr = $request['no_pr'];
                    $tambah_dok_draft->id_document = $tambah_dok->id;
                    $tambah_dok_draft->added = Carbon::now()->toDateTimeString();
                    $tambah_dok_draft->save();
                }
            }

            $update = PRDraft::where('id', $request['no_pr'])->first();
            if ($request['selectLeadId'] == null) {
                $update->lead_id = '-';
            } else {
                $update->lead_id = $request['selectLeadId'];
            }
            if ($request['selectPid'] == null) {
                $update->pid = $request['inputPid'];
            }else{
                $update->pid = $request['selectPid'];
            }

            if ($request['selectQuoteNumber'] == null) {
                $update->quote_number = '-';
            } else {
                $update->quote_number = $request['selectQuoteNumber'];
            }
            $update->save();
        }

        //Internal Purchase Request
        if ($get_pr->type_of_letter == 'IPR') {
            // return $request->inputDocPendukung[0];

            // if ($get_pr->parent_id_drive == null) {
            //     $parentID = $this->googleDriveMakeFolder($request->no_pr . ' Draft PR', $request->no_pr);
            // } else {
            //     $parentID = [];
            //     $parent_id = explode('"', $get_pr->parent_id_drive)[1];
            //     array_push($parentID,$parent_id);
            // }

            // $update_parent = PRDraft::where('id', $request['no_pr'])->first();
            // $update_parent->parent_id_drive = $parentID;
            // $update_parent->save();

            if ($request->inputPenawaranHarga != '-') {
                $allowedfileExtension   = ['jpg','png', 'jpeg', 'JPG', 'PNG', 'pdf', 'PDF'];
                $file                   = $request->file('inputPenawaranHarga');
                $fileName               = $file->getClientOriginalName();
                $strfileName            = explode('.', $fileName);
                $lastElement            = end($strfileName);
                $nameDoc                = $request['no_pr'] . '_penawaran_harga.' . $lastElement;
                $extension              = $file->getClientOriginalExtension();
                $check                  = in_array($extension,$allowedfileExtension);

                if ($count == 0) {
                    $tambah = new PrDokumen();
                    if ($check) {
                        // $request->file('inputPenawaranHarga')->move("draft_pr/", $nameDoc);
                        $this->uploadToLocal($request->file('inputPenawaranHarga'),$directory,$nameDoc);
                        $tambah->dokumen_name             = "Penawaran Harga";

                    } else {
                        return redirect()->back()->with('alert','Oops! Only pdf');
                    }

                    if(isset($fileName)){
                        $pdf_url = urldecode(url("draft_pr/" . $nameDoc));
                        $pdf_name = $nameDoc;
                    } else {
                        $pdf_url = 'http://test-drive.sinergy.co.id:8000/Lampiran.pdf';
                        $pdf_name = 'pdf_lampiran';
                    }

                    if ($get_pr->parent_id_drive == null) {
                        $parentID = $this->googleDriveMakeFolder($request->no_pr . ' Draft PR ' . date('Y'), $request->no_pr);
                    } else {
                        $parentID = [];
                        $parent_id = explode('"', $get_pr->parent_id_drive)[1];
                        array_push($parentID,$parent_id);

                        $data = PR::where('id_draft_pr', $request->no_pr)->first();
                        if (empty($data)) {
                            $data_dokumen =  PRDocumentDraft::where('id_draft_pr', $request->no_pr)
                                ->join("tb_pr_document","tb_pr_document.id","tb_pr_document_draft.id_document")
                                ->where("tb_pr_document.dokumen_name","=","Penawaran Harga")
                                ->orderBy('tb_pr_document_draft.id','desc')
                                ->first();
                            if (!empty($data_dokumen)) {
                                if (strpos($data_dokumen->dokumen_location, 'Revisi')) {
                                    $pdf_name = explode("(",$data_dokumen->dokumen_location)[0] . "" . "(Revisi_" . ((int)substr($data_dokumen->dokumen_location,strpos($data_dokumen->dokumen_location,"Revisi")+ 7,1)+1) . ")." . explode(".",$data_dokumen->dokumen_location)[1];
                                    $pdf_name = explode('/', $pdf_name)[1];
                                } else {
                                    $pdf_name = explode(".",$pdf_name)[0] . "_" . "(Revisi_1)." . explode(".",$pdf_name)[1];
                                }
                            }
                        } else{

                            if (strpos($data->title, 'Revisi')) {
                                $pdf_name = explode(".",$pdf_name)[0] . "" . "(Revisi" . ((int)substr($data->title,strpos($data->title,"Revisi ") + 7,1)+1) . ")." . explode(".",$pdf_name)[1];
                            } else {
                                $pdf_name = explode(".",$pdf_name)[0] . "_" . "(Revisi_1)." . explode(".",$pdf_name)[1];
                            }
                        }
                    }

                    $tambah->dokumen_location         = "draft_pr/".$pdf_name;
                    $tambah->link_drive = $this->googleDriveUploadCustom($pdf_name,$directory . $pdf_name,$parentID);
                    $tambah->save();

                    $tambah_draft = new PRDocumentDraft();
                    $tambah_draft->id_draft_pr = $request['no_pr'];
                    $tambah_draft->id_document = $tambah->id;
                    $tambah_draft->added = Carbon::now()->toDateTimeString();
                    $tambah_draft->save();
                } else {
                    $getId = PrDokumen::join('tb_pr_document_draft', 'tb_pr_document_draft.id_document', '=', 'tb_pr_document.id')->select('tb_pr_document.id', 'tb_pr_document_draft.id as id_dokumen_draft')->where('id_draft_pr', $request['no_pr'])->where('dokumen_name', 'Penawaran Harga')->first();
                    $update = PrDokumen::where('id', $getId->id)->first();
                    if ($check) {
                        // $request->file('inputPenawaranHarga')->move("draft_pr/", $nameDoc);
                        $this->uploadToLocal($request->file('inputPenawaranHarga'),$directory,$nameDoc);
                        $update->dokumen_name             = "Penawaran Harga";

                    } else {
                        return redirect()->back()->with('alert','Oops! Only pdf');
                    }

                    if(isset($fileName)){
                        $pdf_url = urldecode(url("draft_pr/" . $nameDoc));
                        $pdf_name = $nameDoc;
                    } else {
                        $pdf_url = 'http://test-drive.sinergy.co.id:8000/Lampiran.pdf';
                        $pdf_name = 'pdf_lampiran';
                    }

                    // if ($get_pr->parent_id_drive == null) {
                    //     $parentID = $this->googleDriveMakeFolder($request->no_pr . ' Draft PR ' . date('Y'), $request->no_pr);
                    // } else {
                    $parentID = [];
                    $parent_id = explode('"', $get_pr->parent_id_drive)[1];
                    array_push($parentID,$parent_id);
                    // }

                    $pdf_name = explode(".",$pdf_name)[0] . "." . explode(".",$pdf_name)[1];

                    $update->dokumen_location         = "draft_pr/".$pdf_name;
                    $update->link_drive = $this->googleDriveUploadCustom($pdf_name,$directory . $pdf_name,$parentID);
                    $update->save();

                    $tambah_draft = new PRDocumentDraft();
                    $tambah_draft->id_draft_pr = $request['no_pr'];
                    $tambah_draft->id_document = $update->id;
                    $tambah_draft->added = Carbon::now()->toDateTimeString();
                    $tambah_draft->save();
                }

                $update_parent = PRDraft::where('id', $request['no_pr'])->first();
                $update_parent->parent_id_drive = $parentID;
                $update_parent->save();

            }

            $get_pr = PRDraft::select('type_of_letter', 'parent_id_drive')->where('id', $request['no_pr'])->first();

            $dataAll = json_decode($request->arrInputDocPendukung,true);
            foreach ($dataAll as $key => $data) {
                // if (in_array("", $request->inputDocPendukung)) {
                if($request->inputDocPendukung[0] != '-'){
                    // return 'aha';
                    // return $request->inputDocPendukung[0];
                    $allowedfileExtension   = ['jpg','png', 'jpeg', 'JPG', 'PNG', 'pdf', 'PDF'];
                    $file                   = $request->file('inputDocPendukung')[$key];
                    $fileName               = $file->getClientOriginalName();
                    $strfileName            = explode('.', $fileName);
                    $lastElement            = end($strfileName);
                    $nameDoc                = $data['no_pr'] . '_' . $data['nameDocPendukung'] . '.' . $lastElement;
                    $extension              = $file->getClientOriginalExtension();
                    $check                  = in_array($extension,$allowedfileExtension);
                    $tambah_dok = new PrDokumen();
                    if ($check) {
                        $this->uploadToLocal($request->file('inputDocPendukung')[$key],$directory,$nameDoc);
                        $tambah_dok->dokumen_name             = $data['nameDocPendukung'];

                    } else {
                        return redirect()->back()->with('alert','Oops! Only pdf');
                    }

                    if(isset($fileName)){
                        $pdf_url = urldecode(url("draft_pr/" . $nameDoc));
                        $pdf_name = $nameDoc;
                    } else {
                        $pdf_url = 'http://test-drive.sinergy.co.id:8000/Lampiran.pdf';
                        $pdf_name = 'pdf_lampiran';
                    }

                    $parentID = [];
                    $parent_id = explode('"', $get_pr->parent_id_drive)[1];
                    array_push($parentID,$parent_id);

                    $pdf_name = explode(".",$pdf_name)[0] . "." . explode(".",$pdf_name)[1];
                    // $data = PR::where('id_draft_pr', $request->no_pr)->first();
                    // if (empty($data)) {
                    //     $data_dokumen =  PRDocumentDraft::where('id_draft_pr', $request->no_pr)
                    //         ->join("tb_pr_document","tb_pr_document.id","tb_pr_document_draft.id_document")
                    //         ->where('dokumen_name', '=', $tambah_dok->dokumen_name )
                    //         ->orderBy('tb_pr_document_draft.id','desc')
                    //         ->first();

                    //     // return $data_dokumen;

                    //     if (!empty($data_dokumen)) {
                    //         if (strpos($data_dokumen->dokumen_location, 'Revisi')) {
                    //             // return 'disini1';
                    //             $pdf_name = explode("(",$data_dokumen->dokumen_location)[0] . "" . "(Revisi_" . ((int)substr($data_dokumen->dokumen_location,strpos($data_dokumen->dokumen_location,"Revisi")+ 7,1)+1) . ")." . explode(".",$data_dokumen->dokumen_location)[1];
                    //             $pdf_name = explode('/', $pdf_name)[1];
                    //         } else {
                    //             // return 'disini2';
                    //             $pdf_name = explode(".",$pdf_name)[0] . "_" . "(Revisi_1)." . explode(".",$pdf_name)[1];
                    //         }
                    //     }
                    // } else{
                    //     if (strpos($data->title, 'Revisi')) {
                    //         $pdf_name = explode(".",$pdf_name)[0] . "" . "(Revisi" . ((int)substr($data->title,strpos($data->title,"Revisi ") + 7,1)+1) . ")." . explode(".",$pdf_name)[1];
                    //     } else {
                    //         $pdf_name = explode(".",$pdf_name)[0] . "_" . "(Revisi_1)." . explode(".",$pdf_name)[1];
                    //     }
                    // }

                    $tambah_dok->dokumen_location         = "draft_pr/".$pdf_name;
                    $tambah_dok->save();

                    $update_link = PrDokumen::where('id', $tambah_dok->id)->first();
                    $update_link->link_drive = $this->googleDriveUploadCustom($pdf_name,$directory . $pdf_name,$parentID);
                    $update_link->save();

                    $tambah_dok_draft = new PRDocumentDraft();
                    $tambah_dok_draft->id_draft_pr = $request['no_pr'];
                    $tambah_dok_draft->id_document = $tambah_dok->id;
                    $tambah_dok_draft->added = Carbon::now()->toDateTimeString();
                    $tambah_dok_draft->save();
                }
            }
        }
    }

    private function extractFileIdFromDriveLink($googleDriveLink)
    {
        $matches = [];
        preg_match('/\/d\/(.*?)\//', $googleDriveLink, $matches);
        return $matches[1] ?? null;
    }

    public function downloadFromDrive($fileId) {
        $client = $this->getClient();
        $service = new Google_Service_Drive($client);

        $response = $service->files->get($fileId, [
            'alt' => 'media'
        ]);

        $content = $response->getBody()->getContents();

        $filePath = 'draft_pr/' . $fileId . '.pdf';
        file_put_contents($filePath, $content);

        return $filePath;
    }

    public function uploadToLocal($file,$directory,$nameFile){
        $file->move($directory,$nameFile);
    }

    public function downloadToLocal($file,$directory,$nameFile){
        $file->move($directory,$nameFile);
    }

    public function googleDriveMakeFolder($nameFolder,$no_pr){
        $client_folder = $this->getClient();
        $service_folder = new Google_Service_Drive($client_folder);

        $get_pr = DB::table('tb_pr_draft')->select('type_of_letter', 'parent_id_drive')->where('id', $no_pr)->first();

        $file = new Google_Service_Drive_DriveFile();
        $file->setName($nameFolder);
        $file->setMimeType('application/vnd.google-apps.folder');
        $file->setDriveId(env('GOOGLE_DRIVE_DRIVE_ID'));
        if ($get_pr->type_of_letter == 'EPR') {
            $file->setParents([env('GOOGLE_DRIVE_PARENT_ID_EKSTERNAL')]);
        } else {
            $file->setParents([env('GOOGLE_DRIVE_PARENT_ID_INTERNAL')]);
        }

        $result = $service_folder->files->create(
            $file,
            array(
                'mimeType' => 'application/octet-stream',
                'uploadType' => 'multipart',
                'supportsAllDrives' => true
            )
        );

        return array($result->id);
    }

    public function googleDriveUploadCustom($fileName,$locationFile,$parentID){
        $client = $this->getClient();
        $service = new Google_Service_Drive($client);

        $file = new Google_Service_Drive_DriveFile();
        $file->setName($fileName);
        $file->setParents($parentID);

        $result = $service->files->create(
            $file,
            [
                'data' => file_get_contents($locationFile),
                'mimeType' => mime_content_type($locationFile),
                'uploadType' => 'multipart',
                'supportsAllDrives' => true
            ]
        );

        $optParams = array(
            'fields' => 'files(webViewLink)',
            'q' => 'name="'.$fileName.'"',
            'supportsAllDrives' => true,
            'includeItemsFromAllDrives' => true
        );

        unlink($locationFile);
        $link = $service->files->listFiles($optParams)->getFiles()[0]->getWebViewLink();
        return $link;
    }

    public function deleteDokumen(Request $request)
    {
        PRDocumentDraft::where('id_document',$request->id)->delete();
        PrDokumen::where('id',$request->id)->delete();
    }

    public function deleteProduct(Request $request)
    {
        PrProduct::where('id',$request->id)->delete();
        PRProductDraft::where('id_product',$request->id)->delete();
    }

    public function storeLastStepDraftPr(Request $request)
    {
        if ($request->status_revision == 'revision') {
            $update = PRDraft::where('id', $request->no_pr)->first();
            $update->status = 'COMPARING';
            $update->nominal = str_replace('.', '', $request['inputGrandTotalProduct']);
            $update->save();

            $update_pr = PR::where('id_draft_pr', $request->no_pr)->first();
            if (strpos($update_pr->title, 'Revisi')) {
                $update_pr->title = substr_replace($update_pr->title,"(Revisi " . ((int)substr($update_pr->title,strpos($update_pr->title,"Revisi ") + 7,1)+1) . ")",strpos($update_pr->title,"Revisi") - 1);
            } else {
                $update_pr->title = $update_pr->title . ' (Revisi 1)';
            }
            $update_pr->amount = str_replace('.', '', $request['inputGrandTotalProduct']);
            $update_pr->status = 'On Progress';
            $update_pr->save();

            $activity = new PRActivity();
            $activity->id_draft_pr = $request['no_pr'];
            $activity->date_time = Carbon::now()->toDateTimeString();
            $activity->status = 'COMPARING';
            $activity->operator = Auth::User()->name;
            $activity->activity = substr($update_pr->title, -10) . ' - Updating PR with subject ' . $update_pr->title;
            $activity->save();

            $approver = '';

            // $this->uploadPdf($request->no_pr);
            $this->uploadPdfMerge($request->no_pr,$approver,'circular');

            $detail = PRDraft::join('users', 'users.nik', '=', 'tb_pr_draft.issuance')->select('users.name as name_issuance', 'tb_pr_draft.to', 'tb_pr_draft.attention', 'tb_pr_draft.title', 'tb_pr_draft.nominal', 'tb_pr_draft.id', 'tb_pr_draft.issuance', 'status')->where('tb_pr_draft.id', $request->no_pr)->first();

            $kirim_user = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('email', 'users.name as name_receiver')->where('nik', $detail->issuance)->first();

            //Disabled push notification
            /*$jsonInsertCreate = array(
                "heximal" => "#605ca8",
                "id_pr" => $detail->id,
                "title" => $detail->title,
                "result"=> 'COMPARING',
                "showed"=>"true",
                "status"=>"unread",
                "to"=> $kirim_user->email,
                "date_time"=>Carbon::now()->timestamp,
                "module"=>"draft"
            );

            $this->getNotifBadgeInsert($jsonInsertCreate);*/
        } else {
            $update = PRDraft::where('id', $request->no_pr)->first();
            $update->nominal = str_replace('.', '', $request['inputGrandTotalProduct']);
            if (strpos($update->title, 'Revisi')) {
                $update->title = substr_replace($update->title,"(Revisi " . ((int)substr($update->title,strpos($update->title,"Revisi ") + 7,1)+1) . ")",strpos($update->title,"Revisi") - 1);
            } else {
                if(PRActivity::where('status',"REJECT")->where('id_draft_pr', $request->no_pr)->exists()){
                    $update->title = $update->title . ' (Revisi 1)';
                }
            }
            $update->status = 'DRAFT';
            $update->save();

            $activity = new PRActivity();
            $activity->id_draft_pr = $request['no_pr'];
            $activity->date_time = Carbon::now()->toDateTimeString();
            $activity->status = 'DRAFT';
            $activity->operator = Auth::User()->name;
            $activity->activity = 'Add Draft PR with subject ' . $update->title;
            $activity->save();

            $detail = PRDraft::join('users', 'users.nik', '=', 'tb_pr_draft.issuance')->select('users.name as name_issuance', 'tb_pr_draft.to', 'tb_pr_draft.attention', 'tb_pr_draft.title', 'tb_pr_draft.nominal', 'tb_pr_draft.id', 'tb_pr_draft.issuance', 'status', 'id')->where('tb_pr_draft.id', $request->no_pr)->first();

            $kirim = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('email', 'users.name as name_receiver');

            $kirim_user = $kirim->where('roles.name', 'Procurement & Vendor Management')->where('status_karyawan', '!=', 'dummy')->first();

            // $nik = Auth::User()->nik;
            $territory = DB::table('users')->select('id_territory')->where('nik', $detail->issuance)->first()->id_territory;
            $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->select('name', 'roles.group')->where('user_id', $detail->issuance)->first();

            $listTerritory = User::where('id_territory',$territory)->where('id_position', 'MANAGER')->where('status_karyawan', '!=', 'dummy')->first();

            if ($cek_role->group == 'Sales') {
                $email_cc = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('email')
                    ->whereRaw("(`nik` = '".$detail->issuance."' OR `users`.`name` = '".$listTerritory->name."' OR `roles`.`name` = 'VP Internal Chain Management')")
                    ->get()->pluck('email');
            } else {
                $email_cc = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('email')
                    ->whereRaw("(`nik` = '".$detail->issuance."' OR `roles`.`name` = 'VP Internal Chain Management')")
                    ->get()->pluck('email');
            }


            Mail::to($kirim_user)->cc($email_cc)->send(new DraftPR($detail,$kirim_user,'[SIMS-App] Draft PR Submitted and Ready to Verify', 'detail_approver', 'next_approver'));

            //disabled push notification
            // $jsonInsert = array(
            //     "heximal" => "#3c8dbc",
            //     "id_pr" => $detail->id,
            //     "title" => $detail->title,
            //     "result"=> 'DRAFT',
            //     "showed"=>"true",
            //     "status"=>"unread",
            //     "to"=> $kirim_user->email,
            //     "date_time"=>Carbon::now()->timestamp,
            //     "module"=>"draft"
            // );

            // if ($cek_role->group == 'Sales') {
            //     $to_cc = User::select('email')
            //         ->whereRaw("(`name` = '".$listTerritory->name."' OR `id_position` = 'MANAGER' AND `id_division` = 'MSM')")
            //         ->get()->pluck('email');
            // } else {
            //     $to_cc = User::select('email')
            //         ->whereRaw("(`id_position` = 'MANAGER' AND `id_division` = 'MSM')")
            //         ->get()->pluck('email');
            // }

            // foreach ($to_cc as $data) {
            //     $jsonInsert2 = array(
            //         "heximal" => "#3c8dbc",
            //         "id_pr" => $detail->id,
            //         "title" => $detail->title,
            //         "result"=> 'DRAFT',
            //         "showed"=>"true",
            //         "status"=>"unread",
            //         "to"=> $data,
            //         "date_time"=>Carbon::now()->timestamp,
            //         "module"=>"draft"
            //     );
            //     $this->getNotifBadgeInsert($jsonInsert2);
            // }

            // $this->getNotifBadgeInsert($jsonInsert);

        }
    }

    public function cancelDraftPr(Request $request)
    {
        $update = PRDraft::where('id', $request->no_pr)->first();
        $update->status = 'CANCEL';
        $update->save();

        // return PR::where('id_draft_pr', $request->no_pr)->first();
        if (PR::where('id_draft_pr', $request->no_pr)->first() != "") {
            $update_pr = PR::where('id_draft_pr', $request->no_pr)->first();
            $update_pr->status = 'Cancel';
            $update_pr->save();
        }

        $activity = new PRActivity();
        $activity->id_draft_pr = $request['no_pr'];
        $activity->date_time = Carbon::now()->toDateTimeString();
        $activity->status = 'CANCEL';
        $activity->operator = Auth::User()->name;
        if (PR::where('id_draft_pr', $request->no_pr)->first() != "") {
            $activity->activity = 'Cancelled PR with subject ' . $request->notes;
        } else {
            $activity->activity = 'Cancelled PR with subject ' . $request->notes;
        }
        $activity->save();

        $activity = PRActivity::select('activity','operator','id_draft_pr')->where('tb_pr_activity.id_draft_pr', $request->no_pr)->where('status', 'CANCEL')->orderBy('date_time', 'desc')->take(1);

        $detail = PRDraft::join('users', 'users.nik', '=', 'tb_pr_draft.issuance')
            ->joinSub($activity,'temp_tb_pr_activity',function($join){
                $join->on("temp_tb_pr_activity.id_draft_pr","tb_pr_draft.id");
            })
            ->select('users.name as name_issuance', 'tb_pr_draft.to', 'tb_pr_draft.attention', 'tb_pr_draft.title', 'tb_pr_draft.nominal', 'tb_pr_draft.id', 'tb_pr_draft.issuance', 'status', 'id','activity')
            ->where('tb_pr_draft.id', $request->no_pr)->first();


        $kirim = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('email', 'users.name as name_receiver');

        $kirim_user = $kirim->where('roles.name', 'Procurement & Vendor Management')->where('status_karyawan', '!=', 'dummy')->first();
        $cek_role = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.name','roles.name as name_role','email')->where('user_id',Auth::User()->nik)->first();

        $territory = DB::table('users')->select('id_territory')->where('nik', $detail->issuance)->first()->id_territory;
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('name', 'roles.group')->where('user_id', $detail->issuance)->first();

        $listTerritory = User::where('id_territory',$territory)->where('id_position', 'MANAGER')->where('status_karyawan', '!=', 'dummy')->first();

        if ($cek_role->group == 'Sales') {
            $email_cc = User::select('email','roles.name as name_role')
                ->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')
                ->whereRaw("(`nik` = '".$detail->issuance."' OR `users`.`name` = '".$listTerritory->name."' OR `roles`.`name` = 'VP Internal Chain Management')")
                ->get()->pluck('email');
        } else {
            $email_cc = User::select('email','roles.name as name_role')
                ->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')
                ->whereRaw("(`nik` = '".$detail->issuance."' OR `roles`.`name` = 'VP Internal Chain Management')")
                ->get()->pluck('email');
        }


        Mail::to($kirim_user)->cc($email_cc)->send(new DraftPR($detail,$kirim_user,'[SIMS-App] Draft PR Cancelled', 'detail_approver', 'next_approver'));

        //Disabled push notification
        // $jsonInsertCreate = array(
        //     "heximal" => "#dd4b39", //diisi warna sesuai status
        //     "id_pr" => $detail->id,
        //     "title" => $detail->title, //diisi subject
        //     "result"=> 'CANCEL',
        //     "showed"=>"true",
        //     "status"=>"unread",
        //     "to"=> $kirim_user->email,
        //     "date_time"=>Carbon::now()->timestamp,
        //     "module"=>"draft"
        // );

        // $this->getNotifBadgeInsert($jsonInsertCreate);
    }

    public function sendMailDraft(Request $request)
    {
        $activity = PRActivity::select('activity','operator','id_draft_pr')->where('tb_pr_activity.id_draft_pr', $request->no_pr)->where('status', 'UNAPPROVED')->orderBy('date_time', 'desc')->take(1);

        $detail = PR::join('tb_pr_draft', 'tb_pr_draft.id', '=', 'tb_pr.id_draft_pr')->join('users', 'users.nik', '=', 'tb_pr_draft.issuance')
            ->joinSub($activity,'temp_tb_pr_activity',function($join){
                $join->on("temp_tb_pr_activity.id_draft_pr","tb_pr_draft.id");
            })
            ->select('users.name as name_issuance', 'tb_pr.to', 'tb_pr.attention', 'tb_pr.title', 'tb_pr.amount', 'tb_pr_draft.status', 'tb_pr.issuance', 'activity', 'no_pr')
            ->where('tb_pr.id_draft_pr', $request->no_pr)->first();

        return $next_approver = $this->getSignStatusPR($request->no_pr, 'detail');

        $kirim_user = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('email', 'users.name as name_receiver')->where('roles.name', 'Procurement & Vendor Management')->where('status_karyawan', '!=', 'dummy')->get()->pluck('email');

        $email_cc = User::join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('email','roles.name as name_role')
            ->whereRaw("(`nik` = '".$detail->issuance."' OR `roles`.`name` = 'VP Internal Chain Management')")
            ->get()->pluck('email');

        Mail::to($kirim_user)->cc($email_cc)->send(new DraftPR($detail,$kirim_user,'[SIMS-APP] PR ' .$detail->no_pr. ' Is Reject By ' . Auth::User()->name,'detail_approver', $next_approver));
    }

    public function storeTermPayment(Request $request)
    {
        $update = PRDraft::where('id', $request->no_pr)->first();
        $update->term_payment = $request['textAreaTOP'];
        $update->save();

        $update_pr = PR::where('id_draft_pr', $request->no_pr)->first();
        if (!empty($update_pr)) {
            $update_pr->term_payment =  $request['textAreaTOP'];
            $update_pr->save();
        }
    }

    public function verifyDraft(Request $request)
    {
        if (isset($request->valuesChecked)) {
            foreach ($request->valuesChecked as $key => $value) {
                if ($value == 'type_cek') {
                    $update = PRDraftVerify::where('id_draft_pr', $request->no_pr)->first();
                    $update->verify_type_of_letter = 'True';
                    $update->update();
                }

                if ($value == 'to_cek') {
                    $update = PRDraftVerify::where('id_draft_pr', $request->no_pr)->first();
                    $update->verify_to = 'True';
                    $update->update();
                }

                if ($value == 'email_cek') {
                    $update = PRDraftVerify::where('id_draft_pr', $request->no_pr)->first();
                    $update->verify_email = 'True';
                    $update->update();
                }

                if ($value == 'category_Cek') {
                    $update = PRDraftVerify::where('id_draft_pr', $request->no_pr)->first();
                    $update->verify_category = 'True';
                    $update->update();
                }

                if ($value == 'phone_cek') {
                    $update = PRDraftVerify::where('id_draft_pr', $request->no_pr)->first();
                    $update->verify_phone = 'True';
                    $update->update();
                }

                if ($value == 'attention_cek') {
                    $update = PRDraftVerify::where('id_draft_pr', $request->no_pr)->first();
                    $update->verify_attention = 'True';
                    $update->update();
                }

                if ($value == 'subject_cek') {
                    $update = PRDraftVerify::where('id_draft_pr', $request->no_pr)->first();
                    $update->verify_title = 'True';
                    $update->update();
                }

                if ($value == 'address_cek') {
                    $update = PRDraftVerify::where('id_draft_pr', $request->no_pr)->first();
                    $update->verify_address = 'True';
                    $update->update();
                }

                if ($value == 'methode_cek') {
                    $update = PRDraftVerify::where('id_draft_pr', $request->no_pr)->first();
                    $update->verify_request_method = 'True';
                    $update->update();
                }

                if ($value == 'lead_cek') {
                    $update = PRDraftVerify::where('id_draft_pr', $request->no_pr)->first();
                    $update->verify_lead_id = 'True';
                    $update->update();
                }

                if ($value == 'pid_cek') {
                    $update = PRDraftVerify::where('id_draft_pr', $request->no_pr)->first();
                    $update->verify_pid = 'True';
                    $update->update();
                }

                if ($value == 'quoNum_cek') {
                    $update = PRDraftVerify::where('id_draft_pr', $request->no_pr)->first();
                    $update->verify_quote_number = 'True';
                    $update->update();
                }

                if ($value == 'textarea_top_cek') {
                    $update = PRDraftVerify::where('id_draft_pr', $request->no_pr)->first();
                    $update->verify_term_payment = 'True';
                    $update->update();
                }
            }
        }

        $activity = new PRActivity();
        $activity->id_draft_pr = $request['no_pr'];
        $activity->date_time = Carbon::now()->toDateTimeString();
        if ($request['radioConfirm']  == 'reject') {
            $activity->status = 'REJECT';
            $activity->activity = $request['rejectReason'];
        } else {
            $activity->status = 'VERIFIED';
            $activity->activity = 'Verify Draft PR';
        }
        $activity->operator = Auth::User()->name;
        $activity->save();

        if ($request['radioConfirm']  == 'reject') {
            $update = PRDraft::where('id', $request['no_pr'])->first();
            $update->status = 'REJECT';
            $update->save();

            $activity = PRActivity::select('activity','operator','id_draft_pr')->where('tb_pr_activity.id_draft_pr', $request->no_pr)->where('status', 'REJECT')->orderBy('date_time', 'desc')->take(1);

            $detail = PRDraft::join('users', 'users.nik', '=', 'tb_pr_draft.issuance')
                ->joinSub($activity,'temp_tb_pr_activity',function($join){
                    $join->on("temp_tb_pr_activity.id_draft_pr","tb_pr_draft.id");
                })
                ->select('users.name as name_issuance', 'tb_pr_draft.to', 'tb_pr_draft.attention', 'tb_pr_draft.title', 'tb_pr_draft.nominal', 'tb_pr_draft.id', 'status', 'issuance', 'activity')
                ->where('tb_pr_draft.id', $request->no_pr)->first();

            $kirim_user = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('email', 'users.name as name_receiver')->where('nik', $detail->issuance)->first();

            $territory = DB::table('users')->select('id_territory')->where('nik', $detail->issuance)->first()->id_territory;
            $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->select('name', 'roles.group')->where('user_id', $detail->issuance)->first();

            $listTerritory = User::where('id_territory',$territory)->where('id_position', 'MANAGER')->where('status_karyawan', '!=', 'dummy')->first();

            if ($cek_role->group == 'Sales') {
                $email_cc = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('email')->whereRaw("(`roles`.`name` = 'Procurement & Vendor Management' OR `roles`.`name` = 'VP Internal Chain Management' OR `users`.`name` = '".$listTerritory->name."')")
                    ->where('status_karyawan', '!=', 'dummy')
                    ->get()->pluck('email');
            } else {
                $email_cc = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('email')->whereRaw("(`roles`.`name` = 'Procurement & Vendor Management' OR `roles`.`name` = 'VP Internal Chain Management')")
                    ->where('status_karyawan', '!=', 'dummy')
                    ->get()->pluck('email');
            }

            Mail::to($kirim_user)->cc($email_cc)->send(new DraftPR($detail,$kirim_user,'[SIMS-App] Draft PR '.$detail->id.' Rejected', 'detail_approver', 'next_approver'));

            //Disabled push notification
            // $jsonInsertCreate = array(
            //     "heximal" => "#dd4b39", //diisi warna sesuai status
            //     "id_pr" => $detail->id,
            //     "title" => $detail->title, //diisi subject
            //     "result"=> 'REJECT',
            //     "showed"=>"true",
            //     "status"=>"unread",
            //     "to"=> $kirim_user->email,
            //     "date_time"=>Carbon::now()->timestamp,
            //     "module"=>"draft"
            // );

            // $this->getNotifBadgeInsert($jsonInsertCreate);

        } else {
            // return 'verify';
            try {
                //store no_pr
                if (PR::where('id_draft_pr',$request['no_pr'])->exists()) {
                    PR::where('id_draft_pr',$request['no_pr'])->delete();
                }

                $get_draft_pr = PRDraft::where('id', $request['no_pr'])->first();
                $tahun = date("Y");
                $type = $get_draft_pr->type_of_letter;

                $cek_group = PRDraft::join('role_user', 'role_user.user_id', '=', 'tb_pr_draft.issuance')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('roles.name as name', 'roles.group','roles.slug')->where('tb_pr_draft.id', $request['no_pr'])->first();

                if ($cek_group->group == 'Program & Project Management') {
                    $posti = 'PPM';
                } elseif ($cek_group->group == 'Internal Chain Management') {
                    $posti = 'ICM';
                } elseif ($cek_group->group == 'Sales') {
                    $posti = 'SAL';
                } elseif ($cek_group->group == 'Solutions & Partnership Management') {
                    $posti = 'SPM';
                } elseif ($cek_group->group == 'Synergy System Management') {

                    $posti = 'SSM';
                } else {
                    $posti = 'HCM';
                }

                $edate = date("Y-m-d");

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
                $getLastNumPR = PR::orderBy('no', 'desc')->where('date','like',$tahun."%")->first();

                if($getnumber == NULL){
                    $getlastnumber = 1;
                    $lastnumber = $getlastnumber;
                } else{
                    $lastnumber = $getnumber+1;

                    if (isset($getLastNumPR)) {
                        $getLastNumPR = explode('/', $getLastNumPR->no_pr)[0];

                        $lastnumber = $getLastNumPR+1;

                        // if ($lastnumber ==  (int)$getLastNumPR) {
                        // }else{
                        //     $lastnumber = $lastnumber;
                        // }
                    }else{
                        $lastnumber = $lastnumber;
                    }
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

                $no = $akhirnomor.'/'.$posti .'/'. $type.'/' . $bln .'/'. $year_pr;
                $nom = PR::select('no')->orderBy('no','desc')->first();

                $tambah = new PR();
                $tambah->no = isset($nom->no) ? (int)$nom->no + 1 : 1;
                $tambah->no_pr = $no;
                $tambah->position = $posti;
                $tambah->type_of_letter = $type;
                $tambah->month = $bln;
                $tambah->date = $edate;
                $tambah->to = $get_draft_pr->to;
                $tambah->attention = $get_draft_pr->attention;
                $tambah->title = $get_draft_pr->title;
                $tambah->division = 'PPM';
                $tambah->issuance = $get_draft_pr->issuance;
                $tambah->from = $get_draft_pr->issuance;
                $tambah->amount = $get_draft_pr->nominal;
                $tambah->project_id = $get_draft_pr->pid;
                $tambah->category = $get_draft_pr->category;
                $tambah->result = 'T';
                $tambah->status = 'On Progress';
                $tambah->quote_number = $get_draft_pr->quote_number;
                $tambah->lead_id = $get_draft_pr->lead_id;
                $tambah->request_method = $get_draft_pr->request_method;
                $tambah->term_payment = $get_draft_pr->term_payment;
                $tambah->email = $get_draft_pr->email;
                $tambah->phone = $get_draft_pr->phone;
                $tambah->address = $get_draft_pr->address;
                $tambah->fax = $get_draft_pr->fax;
                $tambah->id_draft_pr = $request['no_pr'];
                $tambah->status_tax = $get_draft_pr->status_tax;
                $tambah->tax_pb = $get_draft_pr->tax_pb;
                $tambah->service_charge = $get_draft_pr->service_charge;
                $tambah->discount = $get_draft_pr->discount;
                $tambah->status_draft_pr = 'draft';
                $tambah->isRupiah = $get_draft_pr->isRupiah;
                $month_formatting = date('n');
                $tambah->month_formatting = (int)$month_formatting;
                $tambah->save();

                $approver = '';

                $this->uploadPdf($request->no_pr);

                $response = $this->uploadPdfMerge($request->no_pr,$approver,'verify');
                $update = PRDraft::where('id', $request['no_pr'])->first();
                $update->status = 'VERIFIED';
                $update->save();

                if (isset($response['status']) || $response['status'] === 'success') {
                    $update = PRDraft::where('id', $request['no_pr'])->first();
                    $update->status = 'VERIFIED';
                    $update->save();

                    $detail = PRDraft::join('users', 'users.nik', '=', 'tb_pr_draft.issuance')->select('users.name as name_issuance', 'tb_pr_draft.to', 'tb_pr_draft.attention', 'tb_pr_draft.title', 'tb_pr_draft.nominal', 'tb_pr_draft.id', 'status', 'issuance')->where('tb_pr_draft.id', $request->no_pr)->first();

                    $kirim_user = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('email', 'users.name as name_receiver')->where('nik', $detail->issuance)->first();

                    $territory = DB::table('users')->select('id_territory')->where('nik', $detail->issuance)->first()->id_territory;
                    $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')
                        ->select('name', 'roles.group')->where('user_id', $detail->issuance)->first();

                    $listTerritory = User::where('id_territory',$territory)->where('id_position', 'MANAGER')->where('status_karyawan', '!=', 'dummy')->first();

                    if ($cek_role->group == 'Sales') {
                        $email_cc = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('email')->whereRaw("(`roles`.`name` = 'Procurement & Vendor Management' OR `roles`.`name` = 'VP Internal Chain Management' OR `users`.`name` = '".$listTerritory->name."')")
                            ->where('status_karyawan', '!=', 'dummy')
                            ->get()->pluck('email');
                    } else {
                        $email_cc = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('email')->whereRaw("(`roles`.`name` = 'Procurement & Vendor Management' OR `roles`.`name` = 'VP Internal Chain Management')")
                            ->where('status_karyawan', '!=', 'dummy')
                            ->get()->pluck('email');
                    }

                    Mail::to($kirim_user)->cc($email_cc)->send(new DraftPR($detail,$kirim_user,'[SIMS-App] Draft PR ' .$detail->id. ' Verified and Ready to Get Compare and Circulate', 'detail_approver', 'next_approver'));
                }else{
                    throw new Exception($this->uploadPdfMerge($request->no_pr,$approver,'circular'));
                    PR::where('id',$tambah->id)->delete();
                }

            } catch (Exception $e) {
                DB::rollBack();
                echo json_encode([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ]);
            }
            // $this->mergePdf($request->no_pr);

            //Disabled push notification
            // $jsonInsertCreate = array(
            //     "heximal" => "#00a65a", //diisi warna sesuai status
            //     "id_pr" => $detail->id,
            //     "title" => $detail->title, //diisi subject
            //     "result"=> 'VERIFIED',
            //     "showed"=>"true",
            //     "status"=>"unread",
            //     "to"=> $kirim_user->email,
            //     "date_time"=>Carbon::now()->timestamp,
            //     "module"=>"draft"
            // );

            // $this->getNotifBadgeInsert($jsonInsertCreate);

        }
    }

    public function getPreviewPr(Request $request)
    {
        $data = DB::table('tb_pr_draft')->join('users', 'users.nik', '=', 'tb_pr_draft.issuance')
            // ->leftJoin('tb_pr_draft_verify', 'tb_pr_draft_verify.id_draft_pr', '=', 'tb_pr_draft.id')
            ->join('tb_pr_activity', 'tb_pr_activity.id_draft_pr', '=', 'tb_pr_draft.id')
            ->select('tb_pr_draft.to','tb_pr_draft.email', 'tb_pr_draft.phone', 'attention', 'title', 'tb_pr_draft.address', 'request_method', 'tb_pr_draft.created_at', DB::raw("(CASE WHEN (lead_id = 'null') THEN '-' ELSE lead_id END) as lead_id"), DB::raw("(CASE WHEN (quote_number = 'null') THEN '-' ELSE quote_number END) as quote_number"), 'term_payment','type_of_letter', 'users.name','tb_pr_draft.id', DB::raw("(CASE WHEN (fax is null) THEN '-' ELSE fax END) as fax"), 'pid', 'category','status_used', 'isCommit', 'isRupiah', DB::raw("(CASE WHEN (tax_pb is null) THEN 'false' WHEN (tax_pb = '0') THEN 'false' ELSE tax_pb END) as tax_pb"), DB::raw("(CASE WHEN (service_charge is null) THEN 'false' WHEN (service_charge = '0') THEN 'false' ELSE service_charge END) as service_charge"), DB::raw("(CASE WHEN (discount is null) THEN 'false' WHEN (discount = '0') THEN 'false' ELSE discount END) as discount"), DB::raw("(CASE WHEN (status_tax is null) THEN 'false' ELSE status_tax END) as status_tax"),'status_used')
            ->where('tb_pr_draft.id', $request->no_pr)
            ->first();

        if($data->status_used == 'Selected'){
            $sum_nominal = PrProduct::join('tb_pr_product_draft', 'tb_pr_product_draft.id_product', '=', 'tb_pr_product.id')
                ->join('tb_pr_draft', 'tb_pr_draft.id', '=', 'tb_pr_product_draft.id_draft_pr')
                ->select('grand_total')->where('tb_pr_product_draft.id_draft_pr', $request->no_pr)->sum('grand_total');
        } else {
            $sum_nominal = PrProduct::join('tb_pr_product_compare', 'tb_pr_product_compare.id_product', '=', 'tb_pr_product.id')
                ->join('tb_pr_compare', 'tb_pr_compare.id', '=', 'tb_pr_product_compare.id_compare_pr')
                ->select('grand_total')->where('tb_pr_compare.id_draft_pr', $request->no_pr)->sum('grand_total');
        }

        if ($data->discount != 'false') {
            $amount_discount = round($sum_nominal * ($data->discount))/100;

            $sum_nominal_subtracted = $sum_nominal - $amount_discount;
        } else {
            $sum_nominal_subtracted = $sum_nominal;
            $amount_discount = 0;
        }

        if ($data->status_tax == '1.1') {
            $amount_tax = round(($sum_nominal_subtracted * 11)/1000);
        } elseif ($data->status_tax == '1.2') {
            $amount_tax = round(($sum_nominal_subtracted * 12)/1000);
        } elseif ($data->status_tax == '11') {
            $amount_tax = round(($sum_nominal_subtracted * 11)/100);
        } elseif ($data->status_tax == '12') {
            $amount_tax = round(($sum_nominal_subtracted * 12)/100);
        } else {
            $amount_tax = 0;
        }

        if ($data->tax_pb != 'false') {
            $amount_pb = round($sum_nominal_subtracted * ($data->tax_pb))/100;
        } else {
            $amount_pb = 0;
        }

        if ($data->service_charge != 'false') {
            $amount_service_charge = round($sum_nominal_subtracted * ($data->service_charge))/100;
        } else {
            $amount_service_charge = 0;
        }

        $verify = PRDraftVerify::select('verify_type_of_letter', 'verify_category', 'verify_to', 'verify_email', 'verify_phone', 'verify_attention', 'verify_title', 'verify_address', 'verify_request_method', 'verify_pid', 'verify_lead_id', 'verify_quote_number', 'verify_term_payment')->where('id_draft_pr', $request->no_pr)->orderBy('id', 'desc')->first();

        $activity = DB::table('tb_pr_activity')
            ->select('activity as reason')
            ->where('tb_pr_activity.id_draft_pr', $request->no_pr)
            ->where(function($query){
                $query->where('tb_pr_activity.status', 'REJECT')
                    ->orWhere('tb_pr_activity.status', 'UNAPPROVED');
            })
            ->orderBy('date_time', 'desc')->take(1)->first();

        $activity_unapproved = DB::table('tb_pr_activity')->select('activity as reason')->where('tb_pr_activity.status', 'UNAPPROVED')->where('id_draft_pr', $request->no_pr)->orderBy('date_time', 'desc')->take(1)->first();

        // $activity_all = array_merge($activity,$activity_unapproved);

        $product = PrProduct::join('tb_pr_product_draft', 'tb_pr_product_draft.id_product', '=', 'tb_pr_product.id')
            ->join('tb_pr_draft', 'tb_pr_draft.id', '=', 'tb_pr_product_draft.id_draft_pr')
            ->select('name_product', 'qty', 'unit', 'tb_pr_product.description', 'nominal_product', 'grand_total', 'tb_pr_product.id as id_product', DB::raw("(CASE WHEN (serial_number is null) THEN '-' ELSE serial_number END) as serial_number"), DB::raw("(CASE WHEN (part_number is null) THEN '-' ELSE part_number END) as part_number"))->where('tb_pr_product_draft.id_draft_pr', $request->no_pr)->orderBy('tb_pr_product_draft.id_product', 'asc')->get();

        $dokumen = PrDokumen::join('tb_pr_document_draft', 'tb_pr_document_draft.id_document', '=', 'tb_pr_document.id')
            ->join('tb_pr_draft', 'tb_pr_draft.id', 'tb_pr_document_draft.id_draft_pr')
            ->select('dokumen_name', 'dokumen_location', 'tb_pr_document.id as id_dokumen', 'tb_pr_document_draft.id as id_dokumen_draft', 'link_drive')->where('tb_pr_document_draft.id_draft_pr', $request->no_pr);

        if ($data->type_of_letter == 'IPR') {
            $get_id_max = DB::table($dokumen, 'temp')->groupBy('dokumen_name')->selectRaw('MAX(`temp`.`id_dokumen`) as `id_dokumen`');
            $getAll = DB::table($get_id_max, 'temp2')->join('tb_pr_document', 'tb_pr_document.id', '=', 'temp2.id_dokumen')->select('dokumen_name', 'dokumen_location', 'temp2.id_dokumen', 'link_drive')->orderBy('created_at','asc')->get();
        } else {
            $get_id_max = DB::table($dokumen, 'temp')->groupBy('dokumen_name')->selectRaw('MAX(`temp`.`id_dokumen`) as `id_dokumen`');
            $getAll = DB::table($get_id_max, 'temp2')->join('tb_pr_document', 'tb_pr_document.id', '=', 'temp2.id_dokumen')->select('dokumen_name', 'dokumen_location', 'temp2.id_dokumen', 'link_drive')->orderBy('created_at', 'asc')->get();
        }

        return collect([
            'pr' => $data,
            'product' => $product,
            'dokumen' => $getAll,
            'activity' => $activity,
            'verify' => $verify,
            'grand_total' => $sum_nominal_subtracted+$amount_tax+$amount_pb+$amount_service_charge
        ]);
    }

    public function getDetailPr(Request $request)
    {
        $getActivityComparing = PRActivity::select('activity', 'id_draft_pr')->where('id_draft_pr', $request->no_pr)->where('activity', 'Updating')->orderBy('date_time', 'desc')->take(1);

        $data = PR::join('users', 'users.nik', '=', 'tb_pr.issuance')
            ->join('tb_pr_draft', 'tb_pr_draft.id', '=', 'tb_pr.id_draft_pr')
            ->leftJoinSub($getActivityComparing,'temp_tb_pr_activity',function($join){
                $join->on("temp_tb_pr_activity.id_draft_pr","tb_pr_draft.id");
            })
            ->select('tb_pr.to', 'tb_pr.email', 'tb_pr.phone', 'tb_pr.attention', 'tb_pr.title', 'tb_pr.address', 'tb_pr.request_method', 'tb_pr.created_at', DB::raw("(CASE WHEN (`tb_pr`.`lead_id` = 'null') THEN '-' ELSE `tb_pr`.`lead_id` END) as lead_id"), DB::raw("(CASE WHEN (`tb_pr`.`quote_number` = 'null') THEN '-' ELSE `tb_pr`.`quote_number` END) as quote_number"), 'tb_pr.term_payment','tb_pr.type_of_letter', 'users.name','tb_pr.id_draft_pr', DB::raw("(CASE WHEN (tb_pr.fax is null) THEN '-' ELSE tb_pr.fax END) as fax"), 'pid', 'tb_pr.category', 'status_draft_pr', 'tb_pr_draft.status', 'activity', 'tb_pr.no_pr', 'tb_pr_draft.isCommit', 'tb_pr.isRupiah', DB::raw("(CASE WHEN (`tb_pr`.`tax_pb` is null) THEN 'false' WHEN (`tb_pr`.`tax_pb` = '0') THEN 'false' ELSE `tb_pr`.`tax_pb` END) as tax_pb"), DB::raw("(CASE WHEN (`tb_pr`.`service_charge` is null) THEN 'false' WHEN (`tb_pr`.`service_charge` = '0') THEN 'false' ELSE `tb_pr`.`service_charge` END) as service_charge"), DB::raw("(CASE WHEN (`tb_pr`.`discount` is null) THEN 'false' WHEN (`tb_pr`.`discount` = '0') THEN 'false' ELSE `tb_pr`.`discount` END) as discount"), DB::raw("(CASE WHEN (`tb_pr`.`status_tax` is null) THEN 'false' ELSE `tb_pr`.`status_tax` END) as status_tax"))
            ->where('tb_pr.id_draft_pr', $request->no_pr)->first();

        if($data->status_draft_pr == 'draft'){
            $sum_nominal = PrProduct::join('tb_pr_product_draft', 'tb_pr_product_draft.id_product', '=', 'tb_pr_product.id')
                ->join('tb_pr_draft', 'tb_pr_draft.id', '=', 'tb_pr_product_draft.id_draft_pr')
                ->select('grand_total')->where('tb_pr_product_draft.id_draft_pr', $request->no_pr)->sum('grand_total');
        } else {
            $sum_nominal = PrProduct::join('tb_pr_product_compare', 'tb_pr_product_compare.id_product', '=', 'tb_pr_product.id')
                ->join('tb_pr_compare', 'tb_pr_compare.id', '=', 'tb_pr_product_compare.id_compare_pr')
                ->select('grand_total')->where('tb_pr_compare.id_draft_pr', $request->no_pr)->where('tb_pr_compare.status','Selected')->sum('grand_total');
        }

        if ($data->discount != 'false') {
            $amount_discount = round(($sum_nominal * ($data->discount))/100);

            $sum_nominal_subtracted = round($sum_nominal - $amount_discount);
        } else {
            $sum_nominal_subtracted = $sum_nominal;
            $amount_discount = 0;
        }

        if ($data->status_tax == '1.1') {
            $amount_tax = round(($sum_nominal_subtracted * 11)/1000);
        } elseif ($data->status_tax == '1.2') {
            $amount_tax = round(($sum_nominal_subtracted * 12)/1000);
        } elseif ($data->status_tax == '11') {
            $amount_tax = round(($sum_nominal_subtracted * 11)/100);
        } elseif ($data->status_tax == '12') {
            $amount_tax = round(($sum_nominal_subtracted * 12)/100);
        } else {
            $amount_tax = 0;
        }

        if ($data->tax_pb != 'false') {
            $amount_pb = round($sum_nominal_subtracted * ($data->tax_pb))/100;
        } else {
            $amount_pb = 0;
        }

        if ($data->service_charge != 'false') {
            $amount_service_charge = round($sum_nominal_subtracted * ($data->service_charge))/100;
        } else {
            $amount_service_charge = 0;
        }

        $activity = DB::table('tb_pr_activity')
            ->select('activity as reason')
            ->where('tb_pr_activity.id_draft_pr', $request->no_pr)
            ->where(function($query){
                $query->where('tb_pr_activity.status', 'REJECT')
                    ->orWhere('tb_pr_activity.status', 'UNAPPROVED');
            })
            ->orderBy('date_time', 'desc')->take(1)->first();

        if ($data->status_draft_pr == 'draft') {
            $id_compare_pr = '';
            $product = PrProduct::join('tb_pr_product_draft', 'tb_pr_product_draft.id_product', '=', 'tb_pr_product.id')
                ->join('tb_pr_draft', 'tb_pr_draft.id', '=', 'tb_pr_product_draft.id_draft_pr')
                ->select('name_product', 'qty', 'unit', 'tb_pr_product.description', 'nominal_product', 'grand_total', DB::raw("(CASE WHEN (serial_number is null) THEN '-' ELSE serial_number END) as serial_number"), DB::raw("(CASE WHEN (part_number is null) THEN '-' ELSE part_number END) as part_number"))->where('tb_pr_product_draft.id_draft_pr', $request->no_pr)->orderBy('tb_pr_product_draft.id_product', 'asc')->get();

            $dokumen = PrDokumen::join('tb_pr_document_draft', 'tb_pr_document_draft.id_document', '=', 'tb_pr_document.id')
                ->join('tb_pr_draft', 'tb_pr_draft.id', 'tb_pr_document_draft.id_draft_pr')
                ->select('dokumen_name', 'dokumen_location', 'link_drive', 'tb_pr_document.id as id_dokumen')
                ->where('tb_pr_document_draft.id_draft_pr', $request->no_pr);

            if ($data->type_of_letter == 'IPR') {
                $get_id_max = DB::table($dokumen, 'temp')->groupBy('dokumen_name')->selectRaw('MAX(`temp`.`id_dokumen`) as `id_dokumen`');
                $getAll = DB::table($get_id_max, 'temp2')->join('tb_pr_document', 'tb_pr_document.id', '=', 'temp2.id_dokumen')->select('dokumen_name', 'dokumen_location', 'temp2.id_dokumen', 'link_drive')->orderBy('created_at','asc')->get();
            } else {
                $get_id_max = DB::table($dokumen, 'temp')->groupBy('dokumen_name')->selectRaw('MAX(`temp`.`id_dokumen`) as `id_dokumen`');
                $getAll = DB::table($get_id_max, 'temp2')->join('tb_pr_document', 'tb_pr_document.id', '=', 'temp2.id_dokumen')->select('dokumen_name', 'dokumen_location', 'temp2.id_dokumen', 'link_drive')->orderBy('created_at', 'asc')->get();
            }
        } else if ($data->status_draft_pr == 'pembanding'){
            $id_compare_pr = PRCompare::select('id')->where('id_draft_pr', $request->no_pr)->where('tb_pr_compare.status', 'Selected')->first()->id;
            $product = PrProduct::join('tb_pr_product_compare', 'tb_pr_product_compare.id_product', '=', 'tb_pr_product.id')
                ->join('tb_pr_compare', 'tb_pr_compare.id', '=', 'tb_pr_product_compare.id_compare_pr')
                ->select('name_product', 'qty', 'unit', 'tb_pr_product.description', 'nominal_product', 'grand_total', DB::raw("(CASE WHEN (serial_number is null) THEN '-' ELSE serial_number END) as serial_number"), DB::raw("(CASE WHEN (part_number is null) THEN '-' ELSE part_number END) as part_number"))->where('tb_pr_compare.id_draft_pr', $request->no_pr)->where('status','Selected')->orderBy('tb_pr_product_compare.id_product', 'asc')->get();

            $dokumen = PrDokumen::join('tb_pr_document_compare', 'tb_pr_document_compare.id_document', '=', 'tb_pr_document.id')
                ->join('tb_pr_compare', 'tb_pr_compare.id', 'tb_pr_document_compare.id_compare_pr')
                ->select('dokumen_name', 'dokumen_location', 'link_drive', 'tb_pr_document.id as id_dokumen')
                ->where('tb_pr_compare.id_draft_pr', $request->no_pr)->where('status','Selected');

            if ($data->type_of_letter == 'IPR') {

                $getDokumen = PrDokumen::join('tb_pr_document_compare', 'tb_pr_document_compare.id_document', '=', 'tb_pr_document.id')
                    ->join('tb_pr_compare', 'tb_pr_compare.id', 'tb_pr_document_compare.id_compare_pr')
                    ->select('dokumen_name', 'dokumen_location', 'link_drive', 'tb_pr_document.id as id_dokumen')
                    ->where('tb_pr_compare.id_draft_pr', $request->no_pr)
                    ->where(function($query){
                        $query->where('dokumen_name', '!=', 'Penawaran Harga');
                    })
                    ->get();

                // $getDokumen = DB::table('tb_pr_document')->join('tb_pr_document_draft', 'tb_pr_document_draft.id_document', '=', 'tb_pr_document.id')
                // ->join('tb_pr_draft', 'tb_pr_draft.id', 'tb_pr_document_draft.id_draft_pr')
                // ->select('dokumen_name', 'dokumen_location', 'link_drive')
                // ->where('tb_pr_document_draft.id_draft_pr', $request->no_pr)
                // ->where(function($query){
                //     $query->where('dokumen_name', '!=', 'Penawaran Harga');
                // })
                // ->get();

                $get_id_max = DB::table($dokumen, 'temp')->groupBy('dokumen_name')->selectRaw('MAX(`temp`.`id_dokumen`) as `id_dokumen`');
                $getAll = DB::table($get_id_max, 'temp2')->join('tb_pr_document', 'tb_pr_document.id', '=', 'temp2.id_dokumen')->select('dokumen_name', 'dokumen_location', 'temp2.id_dokumen', 'link_drive')->orderBy('created_at','asc')->get();

                $getAll = $getAll;
            } else {

                $getDokumen = DB::table('tb_pr_document')->join('tb_pr_document_draft', 'tb_pr_document_draft.id_document', '=', 'tb_pr_document.id')
                    ->join('tb_pr_draft', 'tb_pr_draft.id', 'tb_pr_document_draft.id_draft_pr')
                    ->select('dokumen_name', 'dokumen_location', 'link_drive')
                    ->where('tb_pr_document_draft.id_draft_pr', $request->no_pr)
                    ->where(function($query){
                        $query->where('dokumen_name', '!=', 'Quote Supplier');
                    })
                    ->get();

                $get_id_max = DB::table($dokumen, 'temp')->groupBy('dokumen_name')->selectRaw('MAX(`temp`.`id_dokumen`) as `id_dokumen`');
                $getAll = DB::table($get_id_max, 'temp2')->join('tb_pr_document', 'tb_pr_document.id', '=', 'temp2.id_dokumen')->select('dokumen_name', 'dokumen_location', 'temp2.id_dokumen', 'link_drive')->orderBy('created_at', 'asc')->get();

                $getAll = array_merge($getAll->toArray(),$getDokumen->toArray());
            }

            // return $getAll;


        }
        $show_ttd = 'abc';

        $cek_type = PR::select('type_of_letter', 'issuance')->where('id_draft_pr', $request['no_pr'])->first();

        $territory = DB::table('users')->select('id_territory')->where('nik', $cek_type->issuance)->first()->id_territory;

        $cek_group = PR::join('role_user', 'role_user.user_id', '=', 'tb_pr.issuance')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('roles.name', 'roles.group')->where('tb_pr.id_draft_pr', $request['no_pr'])->first();

        $unapproved = DB::table('tb_pr_activity')
            ->where('tb_pr_activity.id_draft_pr', $request->no_pr)
            ->where('tb_pr_activity.status', "UNAPPROVED")
            ->orderBy('tb_pr_activity.id',"DESC")
            ->get();

        $tb_pr_activity = DB::table('tb_pr_activity')
            ->where('tb_pr_activity.id_draft_pr', $request->no_pr);

        if(count($unapproved) != 0){
            $tb_pr_activity->where('tb_pr_activity.id','>',$unapproved->first()->id);
        }

        $tb_pr_activity->where(function($query){
            $query->where('tb_pr_activity.status', 'CIRCULAR')
                ->orWhere('tb_pr_activity.status', 'FINALIZED');
        });

        $show_ttd = User::leftJoinSub($tb_pr_activity,'temp_tb_pr_activity',function($join){
            // $join->on("temp_tb_pr_activity.operator","=","users.name");
            $join->on("users.name","LIKE",DB::raw("CONCAT('%', temp_tb_pr_activity.operator, '%')"));
        })
            ->select('ttd', 'users.name')->where('activity', 'Approval')->orderBy('date_time','asc')->get();

        if ($data->status == 'CIRCULAR') {
            $get_ttd = $this->getSignStatusPR($request->no_pr, 'detail');
        } else {
            $get_ttd = $this->getSignStatusPR($request->no_pr, 'circular');
        }

        $notes = DB::table('tb_pr_notes')->select('id', 'resolve')->where('id_draft_pr', $request->no_pr)->get();

        $nik = Auth::User()->nik;
        $roles_manager = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('user_id')->orWhere('name', 'Chief Operating Officer')->where('user_id', $nik)->first();

        foreach ($notes as $key => $value) {
            if (isset($roles_manager)) {
                if ($roles_manager->user_id == $nik && $value->resolve == 'False') {
                    $notes = 'False';
                } else {
                    $notes = 'True';
                }
            }else{
                $notes = 'True';
            }
        }

        // return $get_ttd;

        return collect([
            'pr' => $data,
            'id_compare_pr' => $id_compare_pr,
            'product' => $product,
            'dokumen' => $getAll,
            'show_ttd' => $show_ttd,
            'getSign' => $get_ttd,
            'activity' => $activity,
            'isNotes' => $notes,
            'grand_total' => $sum_nominal_subtracted+$amount_tax+$amount_pb+$amount_service_charge
        ]);
    }

    public function storePembandingSupplier(Request $request)
    {
        $tambah = new PRCompare();
        $tambah->id_draft_pr = $request['no_pr'];
        $tambah->to = $request['inputTo'];
        $tambah->attention = $request['inputAttention'];
        $tambah->title = $request['inputSubject'];
        $tambah->email = $request['inputEmail'];
        $tambah->phone = $request['inputPhone'];
        $tambah->address = $request['inputAddress'];
        $tambah->fax = $request['inputFax'];
        $tambah->note_pembanding = $request['note_pembanding'];
        $tambah->status = 'Un-Select';
        $tambah->save();

        $update = PRDraft::where('id', $request['no_pr'])->first();
        $update->status = 'COMPARING';
        $update->save();

        $count_comparison = DB::table('tb_pr_activity')->where('activity', 'LIKE', 'Add Comparison%')->where('id_draft_pr', $request->no_pr)->count()+1;

        $activity = new PRActivity();
        $activity->id_draft_pr = $request['no_pr'];
        $activity->date_time = Carbon::now()->toDateTimeString();
        $activity->status = 'COMPARING';
        $activity->operator = Auth::User()->name;
        $activity->activity = 'Add Comparison ' . $count_comparison;
        $activity->save();

        $no_akhir = $tambah->id;
        return $no_akhir;
    }

    public function storePembandingProduct(Request $request)
    {
        // return $request['no_pr'];
        $tambah = new PrProduct();
        $tambah->name_product = $request['inputNameProduct'];
        $tambah->description = $request['inputDescProduct'];
        $tambah->nominal_product = str_replace(',', '', $request['inputPriceProduct']);
        $tambah->qty = $request['inputQtyProduct'];
        $tambah->unit = $request['selectTypeProduct'];
        $tambah->serial_number = $request['inputSerialNumber'];
        $tambah->part_number = $request['inputPartNumber'];
        $tambah->grand_total = str_replace(',', '', $request['inputTotalPrice']);
        $tambah->save();

        $tambah_product = new PRProductCompare();
        $tambah_product->id_compare_pr = $request['no_pr'];
        $tambah_product->id_product = $tambah->id;
        $tambah_product->added = Carbon::now()->toDateTimeString();
        $tambah_product->save();
    }

    public function storePembandingDokumen(Request $request)
    {
        $get_pr = DB::table('tb_pr_draft')->join('tb_pr_compare', 'tb_pr_compare.id_draft_pr', '=', 'tb_pr_draft.id')->select('type_of_letter', 'id_draft_pr', 'tb_pr_compare.to as to_compare', 'parent_id_drive')->where('tb_pr_compare.id', $request['no_pr'])->first();
        $parent_id = explode('"', $get_pr->parent_id_drive)[1];
        $directory = "draft_pr/";

        $count_comparison = DB::table('tb_pr_activity')->where('activity', 'LIKE', 'Add Comparison%')->where('id_draft_pr', $get_pr->id_draft_pr)->count();
        // Eksternal Purchase Request
        if ($get_pr->type_of_letter == 'EPR') {
            if ($request->inputQuoteSupplier != '-') {
                $allowedfileExtension   = ['jpg','png', 'jpeg', 'JPG', 'PNG', 'pdf', 'PDF'];
                $file                   = $request->file('inputQuoteSupplier');
                $fileName               = $file->getClientOriginalName();
                $strfileName            = explode('.', $fileName);
                $lastElement            = end($strfileName);
                $nameDoc                = $get_pr->id_draft_pr . '_Compare#' . $count_comparison . '_quote_supplier_pembanding_' . $get_pr->to_compare . '.' . $lastElement;
                $extension              = $file->getClientOriginalExtension();
                $check                  = in_array($extension,$allowedfileExtension);
                $tambah_quote = new PrDokumen();
                if ($check) {
                    $this->uploadToLocal($request->file('inputQuoteSupplier'),$directory,$nameDoc);
                    $tambah_quote->dokumen_name             = "Quote Supplier";
                } else {
                    return redirect()->back()->with('alert','Oops! Only pdf');
                }

                if(isset($fileName)){
                    $pdf_url = urldecode(url("draft_pr/" . $nameDoc));
                    $pdf_name = $nameDoc;
                } else {
                    $pdf_url = 'http://test-drive.sinergy.co.id:8000/Lampiran.pdf';
                    $pdf_name = 'pdf_lampiran';
                }

                // return $pdf_url;

                $parentID = [];
                array_push($parentID,$parent_id);

                $tambah_quote->dokumen_location     = "draft_pr/" . $nameDoc;
                $tambah_quote->link_drive           = $this->googleDriveUploadCustom($pdf_name,$directory . $pdf_name,$parentID);
                $tambah_quote->save();

                $tambah_quote_draft = new PRDocumentCompare();
                $tambah_quote_draft->id_compare_pr = $request['no_pr'];
                $tambah_quote_draft->id_document = $tambah_quote->id;
                $tambah_quote_draft->added = Carbon::now()->toDateTimeString();
                $tambah_quote_draft->save();
            }

            $dataAll = json_decode($request->arrInputDocPendukung,true);
            // return $dataAll;
            foreach ($dataAll as $key => $data) {
                // return $dataAll;
                if($request->inputDocPendukung[0] != '-'){
                    $allowedfileExtension   = ['jpg','png', 'jpeg', 'JPG', 'PNG', 'pdf', 'PDF'];
                    $file                   = $request->file('inputDocPendukung')[$key];
                    $fileName               = $file->getClientOriginalName();
                    $strfileName            = explode('.', $fileName);
                    $lastElement            = end($strfileName);
                    $nameDoc                = $get_pr->id_draft_pr . '_Compare#' . $count_comparison . '_' . $data['nameDocPendukung'] . '.' . $lastElement;
                    $extension              = $file->getClientOriginalExtension();
                    $check                  = in_array($extension,$allowedfileExtension);
                    $tambah_dok = new PrDokumen();
                    if ($check) {
                        $this->uploadToLocal($request->file('inputDocPendukung')[$key],$directory,$nameDoc);
                        $tambah_dok->dokumen_name             = $data['nameDocPendukung'];

                    } else {
                        return redirect()->back()->with('alert','Oops! Only pdf');
                    }

                    if(isset($fileName)){
                        $pdf_url = urldecode(url("draft_pr/" . $nameDoc));
                        $pdf_name = $nameDoc;
                    } else {
                        $pdf_url = 'http://test-drive.sinergy.co.id:8000/Lampiran.pdf';
                        $pdf_name = 'pdf_lampiran';
                    }

                    // return $pdf_name;
                    $parentID = [];
                    $parent_id = explode('"', $get_pr->parent_id_drive)[1];
                    array_push($parentID,$parent_id);

                    $pdf_name = explode(".",$pdf_name)[0] . "." . explode(".",$pdf_name)[1];

                    $tambah_dok->dokumen_location         = "draft_pr/".$pdf_name;
                    $tambah_dok->link_drive = $this->googleDriveUploadCustom($pdf_name,$directory . $pdf_name,$parentID);
                    $tambah_dok->save();

                    $tambah_dok_draft = new PRDocumentCompare();
                    $tambah_dok_draft->id_compare_pr = $request['no_pr'];
                    $tambah_dok_draft->id_document = $tambah_dok->id;
                    $tambah_dok_draft->added = Carbon::now()->toDateTimeString();
                    $tambah_dok_draft->save();
                }
            }
        }


        //Internal Purchase Request
        if ($get_pr->type_of_letter == 'IPR') {
            if ($request->inputPenawaranHarga != '-') {
                $tambah = new PrDokumen();

                $allowedfileExtension   = ['jpg','png', 'jpeg', 'JPG', 'PNG', 'pdf', 'PDF'];
                $file                   = $request->file('inputPenawaranHarga');
                $fileName               = $file->getClientOriginalName();
                $strfileName            = explode('.', $fileName);
                $lastElement            = end($strfileName);
                $nameDoc                = $get_pr->id_draft_pr . '_Compare#' . $count_comparison . '_penawaran_harga_pembanding_' . $get_pr->to_compare . '.' . $lastElement;
                $extension              = $file->getClientOriginalExtension();
                $check                  = in_array($extension,$allowedfileExtension);

                if ($check) {
                    $this->uploadToLocal($request->file('inputPenawaranHarga'),$directory,$nameDoc);
                    $tambah->dokumen_location         = "draft_pr/" . $nameDoc;
                    $tambah->dokumen_name             = "Penawaran Harga";
                } else {
                    return redirect()->back()->with('alert','Oops! Only pdf');
                }
                $tambah->save();

                $tambah_draft = new PRDocumentCompare();
                $tambah_draft->id_compare_pr = $request['no_pr'];
                $tambah_draft->id_document = $tambah->id;
                $tambah_draft->added = Carbon::now()->toDateTimeString();
                $tambah_draft->save();

                if(isset($fileName)){
                    $pdf_url = urldecode(url("draft_pr/" . $nameDoc));
                    $pdf_name = $nameDoc;
                } else {
                    $pdf_url = 'http://test-drive.sinergy.co.id:8000/Lampiran.pdf';
                    $pdf_name = 'pdf_lampiran';
                }

                $parentID = [];
                array_push($parentID,$parent_id);

                $update = PrDokumen::where('id', $tambah->id)->first();
                $update->link_drive = $this->googleDriveUploadCustom($pdf_name,$directory . $pdf_name,$parentID);
                $update->save();
            }


            $dataAll = json_decode($request->arrInputDocPendukung,true);
            foreach ($dataAll as $key => $data) {
                if ($request->inputDocPendukung != '-') {
                    $allowedfileExtension   = ['jpg','png', 'jpeg', 'JPG', 'PNG', 'pdf', 'PDF'];
                    $file                   = $request->file('inputDocPendukung')[$key];
                    $fileName               = $file->getClientOriginalName();
                    $strfileName            = explode('.', $fileName);
                    $lastElement            = end($strfileName);
                    $nameDoc                = $get_pr->id_draft_pr . '_Compare#' . $count_comparison . '_' . $data['nameDocPendukung'] . '_pembanding_' . $get_pr->to_compare . '.' . $lastElement;
                    $extension              = $file->getClientOriginalExtension();
                    $check                  = in_array($extension,$allowedfileExtension);
                    $tambah_dok = new PrDokumen();
                    if ($check) {
                        $this->uploadToLocal($request->file('inputDocPendukung')[$key],$directory,$nameDoc);
                        $tambah_dok->dokumen_name             = $data['nameDocPendukung'];
                    } else {
                        return redirect()->back()->with('alert','Oops! Only pdf');
                    }

                    if(isset($fileName)){
                        $pdf_url = urldecode('https://dev-app.sifoma.id/'. $tambah_dok->dokumen_location);
                        $pdf_name = $nameDoc;
                    } else {
                        $pdf_url = 'http://test-drive.sinergy.co.id:8000/Lampiran.pdf';
                        $pdf_name = 'pdf_lampiran';
                    }

                    $parentID = [];
                    array_push($parentID,$parent_id);

                    $tambah_dok->dokumen_location   = "draft_pr/" . $nameDoc;
                    $tambah_dok->link_drive         = $this->googleDriveUploadCustom($pdf_name,$directory . $pdf_name,$parentID);
                    $tambah_dok->save();

                    $tambah_dok_draft = new PRDocumentCompare();
                    $tambah_dok_draft->id_compare_pr = $data['no_pr'];
                    $tambah_dok_draft->id_document = $tambah_dok->id;
                    $tambah_dok_draft->added = Carbon::now()->toDateTimeString();
                    $tambah_dok_draft->save();
                }
            }
        }
    }

    public function storePembandingTermPayment(Request $request)
    {
        // return $request->status_tax;
        $update = PRCompare::where('id', $request->no_pr)->first();
        $update->term_payment = $request['textAreaTOP'];
        $update->save();
    }

    public function storeLastStepPembanding(Request $request)
    {
        // return $request['inputGrandTotalProduct'];
        $update = PRCompare::where('id', $request->no_pr)->first();
        $update->nominal = str_replace('.', '', $request['inputGrandTotalProduct']);
        $update->save();
    }

    public function getPembanding(Request $request)
    {
        $getPembanding = PRDraftComparison::where('id', $request->no_pr)->get();
        return $getPembanding;
    }

    public function getTypePr(Request $request)
    {
        // $data = DB::table('tb_pr_draft')->select('type_of_letter')->where('id', $request->no_pr)->first()->type_of_letter;
        $data = DB::table('tb_pr_draft')->select('type_of_letter')->where('id', $request->no_pr)->get();
        return $data;
    }

    public function getProductPembanding(Request $request)
    {
        $getProductPr = PrProduct::join('tb_pr_product_compare', 'tb_pr_product.id', '=', 'tb_pr_product_compare.id_product')
            ->join('tb_pr_compare', 'tb_pr_compare.id', '=', 'tb_pr_product_compare.id_compare_pr')
            ->select('tb_pr_product.name_product', 'tb_pr_product.description', 'tb_pr_product.qty', 'tb_pr_product.unit', 'tb_pr_product.nominal_product', 'tb_pr_product.grand_total', DB::raw("(CASE WHEN (serial_number is null) THEN '-' ELSE serial_number END) as serial_number"), DB::raw("(CASE WHEN (part_number is null) THEN '-' ELSE part_number END) as part_number"), 'id_product')
            ->where('tb_pr_product_compare.id_compare_pr', $request->no_pr)->orderBy('tb_pr_product_compare.id_product', 'asc')->get();

        return array("data"=>$getProductPr);
    }

    public function getPreviewPembanding(Request $request)
    {

        $data = DB::table('tb_pr_draft')->join('users', 'users.nik', '=', 'tb_pr_draft.issuance')->join('tb_pr_compare', 'tb_pr_compare.id_draft_pr', '=', 'tb_pr_draft.id')->select('tb_pr_compare.to', 'tb_pr_compare.email', 'tb_pr_compare.phone', 'tb_pr_compare.attention', 'tb_pr_compare.title', 'tb_pr_compare.address', 'request_method', 'tb_pr_compare.created_at', DB::raw("(CASE WHEN (lead_id = 'null') THEN '-' ELSE lead_id END) as lead_id"), DB::raw("(CASE WHEN (quote_number = 'null') THEN '-' ELSE quote_number END) as quote_number"), 'tb_pr_compare.term_payment','type_of_letter', 'users.name','tb_pr_compare.id', DB::raw("(CASE WHEN (tb_pr_compare.fax is null) THEN '-' ELSE tb_pr_compare.fax END) as fax"), 'pid', 'category', DB::raw("(CASE WHEN (`tb_pr_compare`.`status_tax` is null) THEN 'false' ELSE `tb_pr_compare`.`status_tax` END) as status_tax"), DB::raw("(CASE WHEN (`tb_pr_compare`.`tax_pb` is null) THEN 'false' WHEN (`tb_pr_compare`.`tax_pb` = '0') THEN 'false' ELSE `tb_pr_compare`.`tax_pb` END) as tax_pb"), DB::raw("(CASE WHEN (`tb_pr_compare`.`service_charge` is null) THEN 'false' WHEN (`tb_pr_compare`.`service_charge` = '0') THEN 'false' ELSE `tb_pr_compare`.`service_charge` END) as service_charge"), DB::raw("(CASE WHEN (`tb_pr_compare`.`discount` is null) THEN 'false' WHEN (`tb_pr_compare`.`discount` = '0') THEN 'false' ELSE `tb_pr_compare`.`discount` END) as discount"))->where('tb_pr_compare.id', $request->no_pr)->first();

        $sum_nominal = PrProduct::join('tb_pr_product_compare', 'tb_pr_product_compare.id_product', '=', 'tb_pr_product.id')
            ->join('tb_pr_compare', 'tb_pr_compare.id', '=', 'tb_pr_product_compare.id_compare_pr')
            ->select('grand_total')->where('tb_pr_product_compare.id_compare_pr', $request->no_pr)->sum('grand_total');

        if ($data->discount != 'false') {
            $amount_discount = ($sum_nominal * ($data->discount))/100;

            $sum_nominal = $sum_nominal - $amount_discount;
        } else {
            $amount_discount = 0;
        }

        if ($data->status_tax == '1.1') {
            $amount_tax = ($sum_nominal * 11)/1000;
        } elseif ($data->status_tax == '1.2') {
            $amount_tax = ($sum_nominal * 12)/1000;
        } elseif ($data->status_tax == '11') {
            $amount_tax = ($sum_nominal * 11)/100;
        } elseif ($data->status_tax == '12') {
            $amount_tax = ($sum_nominal * 12)/100;
        } else {
            $amount_tax = 0;
        }

        if ($data->tax_pb != 'false') {
            $amount_pb = ($sum_nominal * ($data->tax_pb))/100;
        } else {
            $amount_pb = 0;
        }

        if ($data->service_charge != 'false') {
            $amount_service_charge = ($sum_nominal * ($data->service_charge))/100;
        } else {
            $amount_service_charge = 0;
        }

        $product = PrProduct::join('tb_pr_product_compare', 'tb_pr_product_compare.id_product', '=', 'tb_pr_product.id')
            ->join('tb_pr_compare', 'tb_pr_compare.id', '=', 'tb_pr_product_compare.id_compare_pr')
            ->select('name_product', 'qty', 'unit', 'tb_pr_product.description', DB::raw("(CASE WHEN (serial_number is null) THEN '-' ELSE serial_number END) as serial_number"), DB::raw("(CASE WHEN (part_number is null) THEN '-' ELSE part_number END) as part_number"), 'nominal_product', 'grand_total')->where('tb_pr_product_compare.id_compare_pr', $request->no_pr)->get();

        $dokumen = PrDokumen::join('tb_pr_document_compare', 'tb_pr_document_compare.id_document', '=', 'tb_pr_document.id')
            ->join('tb_pr_compare', 'tb_pr_compare.id', 'tb_pr_document_compare.id_compare_pr')
            ->select('dokumen_name', 'dokumen_location', 'tb_pr_document.id as id_dokumen', 'tb_pr_document_compare.id as id_dokumen_compare', 'link_drive')->where('tb_pr_document_compare.id_compare_pr', $request->no_pr);

        if ($data->type_of_letter == 'IPR') {
            $get_id_max = DB::table($dokumen, 'temp')->groupBy('dokumen_name')->selectRaw('MAX(`temp`.`id_dokumen`) as `id_dokumen`');
            $getAll = DB::table($get_id_max, 'temp2')->join('tb_pr_document', 'tb_pr_document.id', '=', 'temp2.id_dokumen')->select('dokumen_name', 'dokumen_location', 'temp2.id_dokumen', 'link_drive')->orderBy('created_at','asc')->get();
        } else {
            $get_id_max = DB::table($dokumen, 'temp')->groupBy('dokumen_name')->selectRaw('MAX(`temp`.`id_dokumen`) as `id_dokumen`');
            $getAll = DB::table($get_id_max, 'temp2')->join('tb_pr_document', 'tb_pr_document.id', '=', 'temp2.id_dokumen')->select('dokumen_name', 'dokumen_location', 'temp2.id_dokumen', 'link_drive')->orderBy('created_at','asc')->get();
        }

        return collect([
            'pr' => $data,
            'product' => $product,
            'dokumen' => $getAll,
            'grand_total' => $sum_nominal+$amount_tax+$amount_pb+$amount_service_charge
        ]);
    }

    public function choosedComparison(Request $request)
    {
        if ($request->status == 'pembanding'){
            $select_nopr = PRCompare::join('tb_pr_draft', 'tb_pr_draft.id', '=', 'tb_pr_compare.id_draft_pr')
                // ->join('tb_pr', 'tb_pr.id_draft_pr', '=', 'tb_pr_draft.id')
                ->select('tb_pr_compare.id as id_compare', 'tb_pr_compare.to as to_compare', 'tb_pr_compare.email as email_compare', 'tb_pr_compare.phone as phone_compare', 'tb_pr_compare.fax as fax_compare', 'tb_pr_compare.attention as attention_compare', 'tb_pr_compare.title as title_compare', 'tb_pr_compare.address as address_compare', 'tb_pr_compare.term_payment as term_payment_compare', 'tb_pr_compare.nominal as nominal_compare', 'tb_pr_compare.note_pembanding', 'tb_pr_compare.status_tax', 'tb_pr_compare.tax_pb', 'tb_pr_compare.service_charge', 'tb_pr_compare.discount')
                ->where('tb_pr_compare.id', $request->no_pr)
                ->first();

            $get_id_compare = PRCompare::join('tb_pr_draft', 'tb_pr_draft.id', '=', 'tb_pr_compare.id_draft_pr')->select('tb_pr_compare.id')->where('tb_pr_compare.id_draft_pr', $request->id_draft_pr)->get();

            foreach ($get_id_compare as $data) {
                $update = PRCompare::where('id', $data->id)->first();
                $update->status = 'Un-Select';
                $update->save();
            }

            $update = PRCompare::where('id', $request->no_pr)->first();
            $update->status = 'Selected';
            $update->save();

            $update_pr = PR::where('id_draft_pr', $request->id_draft_pr)->first();
            $update_pr->to = $select_nopr->to_compare;
            $update_pr->attention = $select_nopr->attention_compare;
            if (strpos($update_pr->title, 'Revisi')) {
                $update_pr->title = substr_replace($update_pr->title,"(Revisi " . ((int)substr($update_pr->title,strpos($update_pr->title,"Revisi ") + 7,1)+1) . ")",strpos($update_pr->title,"Revisi") - 1);
            } else {
                $update_pr->title = $update_pr->title . ' (Revisi 1)';
            }
            $update_pr->phone = $select_nopr->phone_compare;
            $update_pr->email = $select_nopr->email_compare;
            $update_pr->address = $select_nopr->address_compare;
            $update_pr->fax = $select_nopr->fax_compare;
            $update_pr->term_payment = $select_nopr->term_payment_compare;
            $update_pr->amount = $select_nopr->nominal_compare;
            $update_pr->status_draft_pr = 'pembanding';
            $update_pr->status_tax = $select_nopr->status_tax;
            $update_pr->tax_pb = $select_nopr->tax_pb;
            $update_pr->service_charge = $select_nopr->service_charge;
            $update_pr->discount = $select_nopr->discount;
            $update_pr->save();

            $update_draft = PRDraft::where('id', $request->id_draft_pr)->first();
            $update_draft->status_used = 'Un-Select';
            $update_draft->save();

            $tambah = new PRActivity();
            $tambah->operator = Auth::User()->name;
            $tambah->id_draft_pr = $request->id_draft_pr;
            $tambah->status = 'COMPARING';
            $tambah->date_time = Carbon::now()->toDateTimeString();
            $tambah->activity = substr($update_pr->title, -10) . ' - Comparison choosed with ' . $select_nopr->note_pembanding;
            $tambah->save();

            $approver = '';

            $this->uploadPdf($request->no_pr);
            $this->uploadPdfMerge($request->id_draft_pr, $approver,'circular');

        } else if($request->status == 'draft'){
            $select_draft = DB::table('tb_pr_draft')->select('tb_pr_draft.to as to_draft', 'tb_pr_draft.email as email_draft', 'tb_pr_draft.phone as phone_draft', 'tb_pr_draft.fax as fax_draft', 'tb_pr_draft.attention as attention_draft', 'tb_pr_draft.title as title_draft', 'tb_pr_draft.address as address_draft', 'tb_pr_draft.term_payment as term_payment_draft', 'tb_pr_draft.id as id_draft_pr', 'tb_pr_draft.nominal as nominal_draft', 'status_used', 'status_tax', 'tax_pb', 'service_charge', 'discount')->where('id', $request->no_pr)->first();

            $get_id_compare = PRCompare::join('tb_pr_draft', 'tb_pr_draft.id', '=', 'tb_pr_compare.id_draft_pr')->select('tb_pr_compare.id')->where('tb_pr_compare.id_draft_pr', $request->no_pr)->get();

            foreach ($get_id_compare as $data) {
                $update = PRCompare::where('id', $data->id)->first();
                $update->status = 'Un-Select';
                $update->save();
            }

            $update_pr = PR::where('id_draft_pr', $request->no_pr)->first();
            $update_pr->to = $select_draft->to_draft;
            $update_pr->attention = $select_draft->attention_draft;
            if (strpos($update_pr->title, 'Revisi')) {
                $update_pr->title = substr_replace($update_pr->title,"(Revisi " . ((int)substr($update_pr->title,strpos($update_pr->title,"Revisi ") + 7,1)+1) . ")",strpos($update_pr->title,"Revisi") - 1);
            } else {
                $update_pr->title = $update_pr->title . ' (Revisi 1)';
            }
            // $update_pr->title = $select_draft->title_draft;
            $update_pr->phone = $select_draft->phone_draft;
            $update_pr->email = $select_draft->email_draft;
            $update_pr->address = $select_draft->address_draft;
            $update_pr->fax = $select_draft->fax_draft;
            $update_pr->term_payment = $select_draft->term_payment_draft;
            $update_pr->status_draft_pr = 'draft';
            $update_pr->amount = $select_draft->nominal_draft;
            $update_pr->status_tax = $select_draft->status_tax;
            $update_pr->save();

            $update_draft = PRDraft::where('id', $request->no_pr)->first();
            $update_draft->status_used = 'Selected';
            $update_draft->save();

            $tambah = new PRActivity();
            $tambah->operator = Auth::User()->name;
            $tambah->id_draft_pr = $request->no_pr;
            $tambah->status = 'COMPARING';
            $tambah->date_time = Carbon::now()->toDateTimeString();
            $tambah->activity = substr($update_pr->title, -10) . ' - Draft pr choosed';
            $tambah->save();

            $approver = '';

            // $this->uploadPdf($request->no_pr);
            $this->uploadPdfMerge($request->no_pr, $approver,'circular');
        }

    }

    public function getActivity(Request $request)
    {
        $getActivity = PRActivity::select('activity', 'operator', 'status', 'date_time')
            ->selectRaw("SUBSTR(`tb_pr_activity`.`date_time`,1,10) AS `date_format`")->where('id_draft_pr', $request->no_pr)->orderBy('date_time', 'desc')->get();

        $lastActivity = PRActivity::join('tb_pr', 'tb_pr.id_draft_pr', '=', 'tb_pr_activity.id_draft_pr')->select('tb_pr_activity.status')->where('tb_pr_activity.id_draft_pr', $request->no_pr)->orderBy('date_time', 'desc')->take(1)->get();

        $isCircular = DB::table('tb_pr_draft')->select('isCircular')->where('id', $request->no_pr)->first();

        $approver = $this->getSignStatusPR($request->no_pr, 'circular')->where('signed','false')->first();

        // return collect([
        //     'activity_group' => $getActivity->groupBy('date_format'),
        //     'last_activity' =>$lastActivity
        // ]);

        return [$getActivity->groupBy('date_format'), $lastActivity, $isCircular, $approver];

        // return $getActivity->groupBy('date_format');
    }

    public function getCountComparing(Request $request)
    {
        $cek = PRDraft::join('tb_pr_compare', 'tb_pr_compare.id_draft_pr', '=', 'tb_pr_draft.id')
            ->where('tb_pr_draft.id', $request->no_pr)
            ->count();

        return $cek;
    }

    public function cekTTD(Request $request)
    {
        return $cek = User::select('ttd')->where('nik', Auth::User()->nik)->first();
    }

    public function uploadTTD(Request $request)
    {
        $update                 = User::where('nik', Auth::User()->nik)->first();
        $allowedfileExtension   = ['jpg','png', 'jpeg', 'JPG', 'PNG'];
        $file                   = $request->file('inputTTD');
        $fileName               = $file->getClientOriginalName();
        $strfileName            = explode('.', $fileName);
        $lastElement            = end($strfileName);
        $nameDoc                = 'image/tanda_tangan/Tanda_tangan_' . Auth::User()->nik . '.' . $lastElement;
        $extension              = $file->getClientOriginalExtension();
        $check                  = in_array($extension,$allowedfileExtension);

        if ($check) {
            $request->file('inputTTD')->move("image/tanda_tangan/", $nameDoc);
            $update->ttd        = $nameDoc;
        } else {
            return redirect()->back()->with('alert','Oops! Only jpg, png');
        }
        $update->save();

        $nik = Auth::User()->nik;
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('name')->where('user_id', $nik)->first();

        $tambah = new PRActivity();
        $tambah->operator = Auth::User()->name;
        $tambah->id_draft_pr = $request['no_pr'];
        if ($cek_role->name == 'Chief Operating Officer') {
            $tambah->status = 'FINALIZED';
        } else {
            $tambah->status = 'CIRCULAR';
        }
        $tambah->date_time = Carbon::now()->toDateTimeString();
        $tambah->activity = 'Approval';
        $tambah->save();

        if ($tambah->status == 'FINALIZED') {
            $update = PRDraft::where('id', $request->no_pr)->first();
            $update->status = 'FINALIZED';
            $update->save();

            $detail = PR::join('tb_pr_draft', 'tb_pr_draft.id', '=', 'tb_pr.id_draft_pr')->join('users', 'users.nik', '=', 'tb_pr.issuance')->select('users.name as name_issuance', 'tb_pr.to', 'tb_pr.attention', 'tb_pr.title', 'tb_pr.amount as nominal', 'tb_pr.issuance', 'no_pr', 'tb_pr_draft.status', 'tb_pr_draft.id')->where('tb_pr.id_draft_pr', $request->no_pr)->first();

            // $kirim_user = User::select('email', 'name')->where('name', $this->getSignStatusPR($request->no_pr))->first();
            $kirim_user = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('email', 'users.name as name_receiver')->where('roles.name', 'Procurement & Vendor Management')->where('status_karyawan', '!=', 'dummy')->first();
            $next_approver = $this->getSignStatusPR($request->no_pr, 'circular');

            $email_cc = User::select('email')
                ->where('nik',$detail->issuance)
                ->get()->pluck('email');

            Mail::to($kirim_user)->cc($email_cc)->send(new DraftPR($detail,$kirim_user,'[SIMS-APP] PR ' .$detail->no_pr. ' Is Approved By ' . Auth::User()->name . ' And Ready To Finalized', 'detail_approver', $next_approver));

        } else {
            $update = PRDraft::where('id', $request->no_pr)->first();
            $update->status = 'CIRCULAR';
            $update->save();

            $detail = PR::join('tb_pr_draft', 'tb_pr_draft.id', '=', 'tb_pr.id_draft_pr')->join('users', 'users.nik', '=', 'tb_pr.issuance')->select('users.name as name_issuance', 'tb_pr.to', 'tb_pr.attention', 'tb_pr.title', 'tb_pr.amount as nominal', 'tb_pr.issuance', 'no_pr', 'tb_pr_draft.status', 'tb_pr_draft.id')->where('tb_pr.id_draft_pr', $request->no_pr)->first();

            $kirim_user = User::select('email', 'name')->where('name', $this->getSignStatusPR($request->no_pr, 'detail'))->first();

            $territory = DB::table('users')->select('id_territory')->where('nik', $detail->issuance)->first()->id_territory;
            $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->select('name', 'roles.group')->where('user_id', $detail->issuance)->first();

            $listTerritory = User::where('id_territory',$territory)->where('id_position', 'MANAGER')->where('status_karyawan', '!=', 'dummy')->first();

            $next_approver = $this->getSignStatusPR($request->no_pr, 'detail');
            $detail_approver = $this->getSignStatusPR($request->no_pr, 'circular');

            if ($cek_role->group == 'Sales') {
                $email_cc = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('email')
                    ->whereRaw("(`nik` = '".$detail->issuance."' OR `roles`.`name` = 'VP Internal Chain Management' OR `users`.`name` = '".$listTerritory->name."')")
                    ->get()->pluck('email');
            } else {
                $email_cc = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('email')
                    ->whereRaw("(`nik` = '".$detail->issuance."' OR `roles`.`name` = 'VP Internal Chain Management')")
                    ->get()->pluck('email');
            }



            Mail::to($kirim_user)->cc($email_cc)->send(new DraftPR($detail,$kirim_user,'[SIMS-APP] PR ' .$detail->no_pr. ' Is Approved By ' . Auth::User()->name, $detail_approver, $next_approver));
        }
        $approver = 'Signed by '.Auth::User()->name;

        $this->uploadPdfMerge($request->no_pr, $approver,'circular');
        // $this->mergePdf($request->no_pr);
    }

    public function showTTD(Request $request)
    {
        $cek_type = PR::select('type_of_letter', 'issuance')->where('id_draft_pr', $request['no_pr'])->first();

        $territory = DB::table('users')->select('id_territory')->where('nik', $cek_type->issuance)->first()->id_territory;
        // return $territory;

        $cek_group = PR::join('role_user', 'role_user.user_id', '=', 'tb_pr.issuance')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('roles.name', 'roles.group')->where('tb_pr.id_draft_pr', $request['no_pr'])->first();

        if ($cek_type->type_of_letter == 'EPR') {
            $show = User::select('ttd', 'name')->where('id_company', '1')->whereRaw("(`users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER' OR `users`.`id_division` = 'TECHNICAL PRESALES' AND `users`.`id_position` = 'MANAGER' OR `users`.`id_division` = 'PMO' AND `users`.`id_position` = 'MANAGER' OR `users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'MSM')")->get();

            return $show;

        } else {
            if ($cek_group->group == 'pmo') {

                $show = User::select('ttd')->where('id_company', '1')->whereRaw("(`users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER' OR `users`.`id_division` = 'PMO' AND `users`.`id_position` = 'MANAGER' OR `users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'MSM')")->first();

                return $show;

            } elseif ($cek_group->group == 'msm') {
                $show = User::select('ttd')->where('id_company', '1')->whereRaw("(`users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER' OR `users`.`id_division` = 'MSM' AND `users`.`id_position` = 'MANAGER')")->get();
                return $show;

            } elseif ($cek_group->group == 'bcd') {
                $show = User::select('ttd')->where('id_company', '1')->whereRaw("(`users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER' OR `users`.`id_division` = 'MSM' AND `users`.`id_position` = 'MANAGER' OR `users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'BCD')")->get();
                return $show;

            } elseif ($cek_group->group == 'DPG') {
                $show = User::select('ttd')->where('id_company', '1')->whereRaw("(`users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER' OR `users`.`id_position` = 'ENGINEER MANAGER' OR `users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'MSM')")->get();
                return $show;

            } elseif ($cek_group->group == 'presales') {
                $show = User::select('ttd')->where('id_company', '1')->whereRaw("(`users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER' OR `users`.`id_division` = 'TECHNICAL PRESALES' AND `users`.`id_position` = 'MANAGER' OR `users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'MSM')")->get();
                return $show;

            } elseif ($cek_group->group == 'hr') {
                $show = User::select('ttd')->where('id_company', '1')->whereRaw("(`users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER' OR `roles`.`name` = 'VP Human Capital Management' OR `users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'MSM')")->get();
                return $show;

            } elseif ($cek_group->group == 'Sales') {
                $show = User::select('ttd', 'name')->where('id_company', '1')->where('status_karyawan', '!=', 'dummy')->whereRaw("(`users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER' OR `users`.`id_position` = 'MANAGER' AND `users`.`id_territory` = '" . $territory . "' OR `users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'MSM')")->get();
                return $show;
            }
        }
    }

    public function circulerPrTanpaPembanding(Request $request)
    {
        $tambah = new PRActivity();
        $tambah->operator = Auth::User()->name;
        $tambah->id_draft_pr = $request['no_pr'];
        $tambah->status = 'CIRCULAR';
        $tambah->date_time = Carbon::now()->toDateTimeString();
        $tambah->activity = 'The comparison process is skipped for the following reasons: ' . $request['reason'];
        $tambah->save();

        $update = PRDraft::where('id', $request->no_pr)->first();
        $update->status = 'CIRCULAR';
        $update->isCircular = 'True';
        $update->save();

        $detail = PR::join('tb_pr_draft', 'tb_pr_draft.id', '=', 'tb_pr.id_draft_pr')->join('users', 'users.nik', '=', 'tb_pr.issuance')->select('users.name as name_issuance', 'tb_pr.to', 'tb_pr.attention', 'tb_pr.title', 'tb_pr.amount as nominal', 'tb_pr.issuance', 'no_pr', 'tb_pr_draft.status', 'tb_pr_draft.id')->where('tb_pr.id_draft_pr', $request->no_pr)->first();

        $next_approver = $this->getSignStatusPR($request->no_pr, 'detail');
        $detail_approver = $this->getSignStatusPR($request->no_pr, 'circular');
        // $detail_approver = $this->getSignStatusPR($request->no_pr, 'detail');
        $kirim_user = User::select('email', 'name')->where('name', $this->getSignStatusPR($request->no_pr, 'detail'))->first();
        // $kirim_user = $detail_approver->pluck('email');

        $territory = DB::table('users')->select('id_territory')->where('nik', $detail->issuance)->first()->id_territory;
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('name', 'roles.group')->where('user_id', $detail->issuance)->first();

        $listTerritory = User::where('id_territory',$territory)->where('id_position', 'MANAGER')->where('status_karyawan', '!=', 'dummy')->first();

        if ($cek_role->group == 'Sales') {
            $email_cc = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('email')
                ->whereRaw("(`nik` = '".$detail->issuance."' OR `roles`.`name` = 'Procurement & Vendor Management' OR `users`.`name` = '".$listTerritory->name."')")
                ->where('status_karyawan', '!=', 'dummy')
                ->get()->pluck('email');
        } else {
            $email_cc = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('email')
                ->whereRaw("(`nik` = '".$detail->issuance."' OR `roles`.`name` = 'Procurement & Vendor Management')")
                ->where('status_karyawan', '!=', 'dummy')
                ->get()->pluck('email');
        }

        Mail::to($kirim_user)->cc($email_cc)->send(new DraftPR($detail,$kirim_user,'[SIMS-APP] PR '.$detail->no_pr.' Ready to Approve', $detail_approver,$next_approver));

        //Disabled push notification
        // $jsonInsertCreate = array(
        //     "heximal" => "#f39c12", //diisi warna sesuai status
        //     "id_pr" => $detail->id,
        //     "title" => $detail->title, //diisi subject
        //     "result"=> 'CIRCULAR',
        //     "showed"=>"true",
        //     "status"=>"unread",
        //     "to"=> $kirim_user->email,
        //     "date_time"=>Carbon::now()->timestamp,
        //     "module"=>"draft"
        // );

        // $this->getNotifBadgeInsert($jsonInsertCreate);
    }

    public function circulerPr(Request $request)
    {
        $tambah = new PRActivity();
        $tambah->operator = Auth::User()->name;
        $tambah->id_draft_pr = $request['no_pr'];
        $tambah->status = 'CIRCULAR';
        $tambah->date_time = Carbon::now()->toDateTimeString();
        $tambah->activity = 'Start Circuler';
        $tambah->save();

        $update = PRDraft::where('id', $request->no_pr)->first();
        $update->isCircular = 'True';
        $update->status = 'CIRCULAR';
        $update->save();

        $detail = PR::join('tb_pr_draft', 'tb_pr_draft.id', '=', 'tb_pr.id_draft_pr')->join('users', 'users.nik', '=', 'tb_pr.issuance')->select('users.name as name_issuance', 'tb_pr.to', 'tb_pr.attention', 'tb_pr.title', 'tb_pr.amount as nominal', 'tb_pr.issuance', 'no_pr', 'tb_pr_draft.status', 'tb_pr_draft.id')->where('tb_pr.id_draft_pr', $request->no_pr)->first();

        $next_approver = $this->getSignStatusPR($request->no_pr, 'detail');
        $detail_approver = $this->getSignStatusPR($request->no_pr, 'circular');
        // $detail_approver = $this->getSignStatusPR($request->no_pr, 'detail');
        $kirim_user = User::select('email', 'name')->where('name', $this->getSignStatusPR($request->no_pr, 'detail'))->first();
        // $kirim_user = $detail_approver->pluck('email');

        $territory = DB::table('users')->select('id_territory')->where('nik', $detail->issuance)->first()->id_territory;
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('name', 'roles.group')->where('user_id', $detail->issuance)->first();

        $listTerritory = User::where('id_territory',$territory)->where('id_position', 'MANAGER')->where('status_karyawan', '!=', 'dummy')->first();

        if ($cek_role->group == 'Sales') {
            $email_cc = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('email')
                ->whereRaw("(`nik` = '".$detail->issuance."' OR `roles`.`name` = 'Procurement & Vendor Management' OR `users`.`name` = '".$listTerritory->name."')")
                ->where('status_karyawan', '!=', 'dummy')
                ->get()->pluck('email');
        } else {
            $email_cc = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('email')
                ->whereRaw("(`nik` = '".$detail->issuance."' OR `roles`.`name` = 'Procurement & Vendor Management')")
                ->where('status_karyawan', '!=', 'dummy')
                ->get()->pluck('email');
        }

        Mail::to($kirim_user)->cc($email_cc)->send(new DraftPR($detail,$kirim_user,'[SIMS-APP] PR '.$detail->no_pr.' Ready to Approve', $detail_approver,$next_approver));

        //Disabled Push notification
        /*$jsonInsertCreate = array(
            "heximal" => "#f39c12", //diisi warna sesuai status
            "id_pr" => $detail->id,
            "title" => $detail->title, //diisi subject
            "result"=> 'CIRCULAR',
            "showed"=>"true",
            "status"=>"unread",
            "to"=> $kirim_user->email,
            "date_time"=>Carbon::now()->timestamp,
            "module"=>"draft"
        );

        $this->getNotifBadgeInsert($jsonInsertCreate);*/
    }

    public function submitTtdApprovePR(Request $request)
    {
        $nik = Auth::User()->nik;
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('name')->where('user_id', $nik)->first();

        $cek_sign = PRActivity::where('id_draft_pr', $request->no_pr)->where('operator', Auth::User()->name)->whereRaw("(`status` =  'CIRCULAR' OR `status` = 'FINALIZED')")->first();

        if (PRActivity::where('id_draft_pr', $request->no_pr)->where('operator', Auth::User()->name)->whereRaw("(`status` =  'CIRCULAR' OR `status` = 'FINALIZED')")->exists()) {
            PRActivity::where('id', $cek_sign->id)->delete();
        }

        $tambah = new PRActivity();
        $tambah->operator = Auth::User()->name;
        $tambah->id_draft_pr = $request['no_pr'];
        if ($cek_role->name == 'Chief Operating Officer') {
            $tambah->status = 'FINALIZED';
        } else {
            $tambah->status = 'CIRCULAR';
        }
        $tambah->date_time = Carbon::now()->toDateTimeString();
        $tambah->activity = 'Approval';
        $tambah->save();

        $approver = 'Signed by '.Auth::User()->name;

        if ($tambah->status == 'FINALIZED') {
            $update = PRDraft::where('id', $request->no_pr)->first();
            $update->status = 'FINALIZED';
            $update->save();

            $detail = PR::join('tb_pr_draft', 'tb_pr_draft.id', '=', 'tb_pr.id_draft_pr')->join('users', 'users.nik', '=', 'tb_pr.issuance')->select('users.name as name_issuance', 'tb_pr.to', 'tb_pr.attention', 'tb_pr.title', 'tb_pr.amount as nominal', 'tb_pr.issuance', 'no_pr', 'tb_pr_draft.status', 'tb_pr_draft.id')->where('tb_pr.id_draft_pr', $request->no_pr)->first();

            $kirim_user = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('email', 'users.name as name_receiver')->where('roles.name', 'Procurement & Vendor Management')->where('status_karyawan', '!=', 'dummy')->first();

            $next_approver = $this->getSignStatusPR($request->no_pr, 'circular');

            $email_cc = User::select('email')
                ->where('nik',$detail->issuance)
                ->get()->pluck('email');

            Mail::to($kirim_user)->cc($email_cc)->send(new DraftPR($detail,$kirim_user,'[SIMS-APP] PR ' .$detail->no_pr. ' Is Approved By ' . Auth::User()->name . ' And Ready To Finalized', 'detail_approver', $next_approver));

            //Disabled push notificatio
            /*$jsonInsertCreate = array(
                "heximal" => "#00a65a", //diisi warna sesuai status
                "id_pr" => $detail->id,
                "title" => $detail->title, //diisi subject
                "result"=> 'FINALIZED',
                "showed"=>"true",
                "status"=>"unread",
                "to"=> $kirim_user->email,
                "date_time"=>Carbon::now()->timestamp,
                "module"=>"draft"
            );

            $this->getNotifBadgeInsert($jsonInsertCreate);*/
            // $this->uploadPdfMerge($request->no_pr, $approver);

        } else {
            $update = PRDraft::where('id', $request->no_pr)->first();
            $update->status = 'CIRCULAR';
            $update->isCircular = 'True';
            $update->save();

            $detail = PR::join('tb_pr_draft', 'tb_pr_draft.id', '=', 'tb_pr.id_draft_pr')->join('users', 'users.nik', '=', 'tb_pr.issuance')->select('users.name as name_issuance', 'tb_pr.to', 'tb_pr.attention', 'tb_pr.title', 'tb_pr.amount as nominal', 'tb_pr.issuance', 'no_pr', 'tb_pr_draft.status', 'tb_pr_draft.id','tb_pr.request_method','tb_pr.type_of_letter')->where('tb_pr.id_draft_pr', $request->no_pr)->first();

            $kirim_user = User::select('email', 'name')->where('name', $this->getSignStatusPR($request->no_pr, 'detail'))->first();
            $next_approver = $this->getSignStatusPR($request->no_pr, 'detail');
            $detail_approver = $this->getSignStatusPR($request->no_pr, 'circular');

            $territory = DB::table('users')->select('id_territory')->where('nik', $detail->issuance)->first()->id_territory;
            $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->select('name', 'roles.group')->where('user_id', $detail->issuance)->first();

            $listTerritory = User::where('id_territory',$territory)->where('id_position', 'MANAGER')->where('status_karyawan', '!=', 'dummy')->first();


            if ($next_approver == 'Muhammad Nabil' && $detail->request_method == 'Purchase Order') {
                if ($cek_role->group == 'Sales') {
                    $email_cc = User::join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')
                        ->select('email','roles.name as name_role')
                        ->whereRaw("(`nik` = '".$detail->issuance."' OR `roles`.`name` = 'VP Internal Chain Management' OR `users`.`name` = '".$listTerritory->name."')")
                        ->get()->pluck('email');
                } else {
                    $email_cc = User::join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('email','roles.name as name_role')
                        ->whereRaw("(`nik` = '".$detail->issuance."' OR `roles`.`name` = 'VP Internal Chain Management')")
                        ->get()->pluck('email');
                }
                $email_cc = $email_cc->put('4','elen@sinergy.co.id');
            } else {
                if ($cek_role->group == 'Sales') {
                    $email_cc = User::join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('email','roles.name as name_role')
                        ->whereRaw("(`nik` = '".$detail->issuance."' OR `roles`.`name` = 'VP Internal Chain Management' OR `users`.`name` = '".$listTerritory->name."')")
                        ->get()->pluck('email');
                } else {
                    $email_cc = User::join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('email','roles.name as name_role')
                        ->whereRaw("(`nik` = '".$detail->issuance."' OR `roles`.`name` = 'VP Internal Chain Management')")
                        ->get()->pluck('email');
                }
            }

            Mail::to($kirim_user)->cc($email_cc)->send(new DraftPR($detail,$kirim_user,'[SIMS-APP] PR ' .$detail->no_pr. ' Is Approved By ' . Auth::User()->name, $detail_approver, $next_approver));

            //Disabled push notification
            /*$jsonInsertCreate = array(
                "heximal" => "#f39c12", //diisi warna sesuai status
                "id_pr" => $detail->id,
                "title" => $detail->title, //diisi subject
                "result"=> 'CIRCULAR',
                "showed"=>"true",
                "status"=>"unread",
                "to"=> $kirim_user->email,
                "date_time"=>Carbon::now()->timestamp,
                "module"=>"draft"
            );

            $this->getNotifBadgeInsert($jsonInsertCreate);*/
            // if($cek_role->name == 'VP Internal Chain Management' || $detail->type_of_letter == 'IPR'){
            //     $this->uploadPdfMerge($request->no_pr, $approver,'circular');
            // }
        }

        // $this->mergePdf($request->no_pr);

    }

    public function rejectCirculerPR(Request $request)
    {
        $tambah = new PRActivity();
        $tambah->operator = Auth::User()->name;
        $tambah->id_draft_pr = $request['no_pr'];
        $tambah->status = 'UNAPPROVED';
        $tambah->date_time = Carbon::now()->toDateTimeString();
        $tambah->activity = 'The circulation process was stopped because it was rejected for the following reasons: ' . $request['reasonRejectSirkular'];
        $tambah->save();

        $update = PRDraft::where('id', $request->no_pr)->first();
        $update->isCircular = 'False';
        $update->status = 'UNAPPROVED';
        $update->save();

        $update_pr = PR::where('id_draft_pr',$request->no_pr)->first();
        $update_pr->status = 'Cancel';
        $update_pr->save();

        $activity = PRActivity::select('activity','operator','id_draft_pr')->where('tb_pr_activity.id_draft_pr', $request->no_pr)->where('status', 'UNAPPROVED')->orderBy('date_time', 'desc')->take(1);

        $get_status = PR::select('status_draft_pr')->where('id_draft_pr', $request->no_pr)->first();

        if ($get_status->status_draft_pr == 'pembanding') {
            $detail = PR::join('tb_pr_draft', 'tb_pr_draft.id', '=', 'tb_pr.id_draft_pr')->join('users', 'users.nik', '=', 'tb_pr_draft.issuance')->join('tb_pr_compare', 'tb_pr_compare.id_draft_pr', '=', 'tb_pr_draft.id')
                ->joinSub($activity,'temp_tb_pr_activity',function($join){
                    $join->on("temp_tb_pr_activity.id_draft_pr","tb_pr_draft.id");
                })
                ->select('users.name as name_issuance', 'tb_pr.to', 'tb_pr.attention', 'tb_pr.title', 'tb_pr.amount as nominal', 'tb_pr_draft.status', 'tb_pr.issuance', 'activity', 'no_pr', 'tb_pr_compare.id as id')
                ->where('tb_pr.id_draft_pr', $request->no_pr)->first();
        } else {
            $detail = PR::join('tb_pr_draft', 'tb_pr_draft.id', '=', 'tb_pr.id_draft_pr')->join('users', 'users.nik', '=', 'tb_pr_draft.issuance')
                ->joinSub($activity,'temp_tb_pr_activity',function($join){
                    $join->on("temp_tb_pr_activity.id_draft_pr","tb_pr_draft.id");
                })
                ->select('users.name as name_issuance', 'tb_pr.to', 'tb_pr.attention', 'tb_pr.title', 'tb_pr.amount as nominal', 'tb_pr_draft.status', 'tb_pr.issuance', 'activity', 'no_pr', 'tb_pr_draft.id as id')
                ->where('tb_pr.id_draft_pr', $request->no_pr)->first();
        }

        $next_approver = $this->getSignStatusPR($request->no_pr, 'detail');

        $kirim_user = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('email', 'users.name as name_receiver')->where('roles.name', 'Procurement & Vendor Management')->where('status_karyawan', '!=', 'dummy')->first();

        $territory = DB::table('users')->select('id_territory')->where('nik', $detail->issuance)->first()->id_territory;
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('name', 'roles.group')->where('user_id', $detail->issuance)->first();

        $listTerritory = User::where('id_territory',$territory)->where('id_position', 'MANAGER')->where('status_karyawan', '!=', 'dummy')->first();

        if ($cek_role->group == 'Sales') {
            $email_cc = User::join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('email','roles.name as name_role')
                ->whereRaw("(`nik` = '".$detail->issuance."' OR `roles`.`name` = 'VP Internal Chain Management' OR `users`.`name` = '".$listTerritory->name."')")
                ->get()->pluck('email');
        } else {
            $email_cc = User::join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('email','roles.name as name_role')
                ->whereRaw("(`nik` = '".$detail->issuance."' OR `roles`.`name` = 'VP Internal Chain Management')")
                ->get()->pluck('email');
        }

        Mail::to($kirim_user)->cc($email_cc)->send(new DraftPR($detail,$kirim_user,'[SIMS-APP] PR ' .$detail->no_pr. ' Is Reject By ' . Auth::User()->name,'detail_approver', $next_approver));


        //Disabled push notificati
        /*$jsonInsertCreate = array(
            "heximal" => "#dd4b39", //diisi warna sesuai status
            "id_pr" => $detail->id,
            "title" => $detail->title, //diisi subject
            "result"=> 'UNAPPROVED',
            "showed"=>"true",
            "status"=>"unread",
            "to"=> $kirim_user->email,
            "date_time"=>Carbon::now()->timestamp,
            "module"=>"draft"
        );

        $this->getNotifBadgeInsert($jsonInsertCreate);*/
    }

    public function storeNotes(Request $request)
    {
        $tambah = new PRNotes();
        $tambah->id_draft_pr = $request->no_pr;
        $tambah->operator = Auth::User()->name;
        $tambah->date_add = Carbon::now()->toDateTimeString();
        if(Auth::User()->name == 'Ganjar Pramudya Wijaya'){
            $tambah->resolve = 'True';
        } else {
            $tambah->resolve = 'False';
        }
        $tambah->notes = $request->inputNotes;
        $tambah->save();

        $tambah_activity = new PRActivity();
        $tambah_activity->id_draft_pr = $request->no_pr;
        $tambah_activity->date_time = Carbon::now()->toDateTimeString();
        $tambah_activity->status = 'ADD_NOTES';
        $tambah_activity->operator = Auth::User()->name;
        $tambah_activity->activity = 'Add Notes ';
        $tambah_activity->save();

        $notes = PRNotes::select('notes', 'id_draft_pr')->where('id_draft_pr', $request->no_pr)->orderBy('date_add', 'desc')->take(1);

        $detail = PR::join('tb_pr_draft', 'tb_pr_draft.id', '=', 'tb_pr.id_draft_pr')->join('users', 'users.nik', '=', 'tb_pr_draft.issuance')
            ->joinSub($notes,'temp_tb_pr_notes',function($join){
                $join->on("temp_tb_pr_notes.id_draft_pr","tb_pr_draft.id");
            })
            ->select('users.name as name_issuance', 'tb_pr.to', 'tb_pr.attention', 'tb_pr.title', 'tb_pr.amount as nominal', 'tb_pr_draft.status', 'tb_pr.issuance', 'no_pr', 'tb_pr_draft.id as id', 'notes')->where('tb_pr.id_draft_pr', $request->no_pr)->first();

        $kirim_user = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('email', 'users.name as name_receiver')->where('nik', $detail->issuance)->first();

        $kirim_user = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('email')
            ->where('status_karyawan', '!=', 'dummy')
            ->where('roles.name', 'Procurement & Vendor Management')
            ->orWhereIn('users.name',$request->emailMention)->get();

        $territory = DB::table('users')->select('id_territory')->where('nik', $detail->issuance)->first()->id_territory;
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('name', 'roles.group')->where('user_id', $detail->issuance)->first();

        $listTerritory = User::where('id_territory',$territory)->where('id_position', 'MANAGER')->where('status_karyawan', '!=', 'dummy')->first();

        // if ($cek_role->group == 'Sales') {
        //     $email_cc = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('email')->whereRaw("(`roles`.`name` = 'Procurement & Vendor Management' OR `roles`.`name` = 'VP Internal Chain Management' OR `users`.`name` = '".$listTerritory->name."')")
        //         ->where('status_karyawan', '!=', 'dummy')
        //         ->get()->pluck('email');
        // } else {
        //     $email_cc = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('email')->whereRaw("(`roles`.`name` = 'Procurement & Vendor Management' OR `roles`.`name` = 'VP Internal Chain Management')")

        //         ->where('status_karyawan', '!=', 'dummy')
        //         ->get()->pluck('email');
        // }

        foreach ($kirim_user as $data) {
            Mail::to($data->email)->send(new DraftPR($detail,$kirim_user,'[SIMS-APP] Add Notes by ' . Auth::User()->name . ' On PR ' . $detail->no_pr, 'detail_approver', 'addNotes'));
        }
    }

    public function storeReply(Request $request)
    {
        $tambah = new PRNotesDetail();
        $tambah->id_notes = $request->id_notes;
        $tambah->operator = Auth::User()->name;
        $tambah->date_add = Carbon::now()->toDateTimeString();
        $tambah->reply = $request->inputReply;
        $tambah->save();

        $data = PRNotes::join('tb_pr_notes_detail', 'tb_pr_notes.id', 'tb_pr_notes_detail.id_notes')->select('tb_pr_notes.operator')->where('tb_pr_notes.id', $request->id_notes)->first();

        $tambah_activity = new PRActivity();
        $tambah_activity->id_draft_pr = $request->no_pr;
        $tambah_activity->date_time = Carbon::now()->toDateTimeString();
        $tambah_activity->status = 'REPLY_NOTES';
        $tambah_activity->operator = Auth::User()->name;
        $tambah_activity->activity = "Reply " . $data->operator . "'s notes";
        $tambah_activity->save();

        $kirim_user = User::where('name', $data->operator)->first()->email;

        $notes = PRNotes::join('tb_pr_notes_detail', 'tb_pr_notes_detail.id_notes', '=', 'tb_pr_notes.id')->select('reply', 'id_draft_pr')->where('id_draft_pr', $request->no_pr)->orderBy('tb_pr_notes_detail.date_add', 'desc')->take(1);

        $detail = PR::join('tb_pr_draft', 'tb_pr_draft.id', '=', 'tb_pr.id_draft_pr')->join('users', 'users.nik', '=', 'tb_pr_draft.issuance')
            ->joinSub($notes,'temp_tb_pr_notes',function($join){
                $join->on("temp_tb_pr_notes.id_draft_pr","tb_pr_draft.id");
            })
            ->select('users.name as name_issuance', 'tb_pr.to', 'tb_pr.attention', 'tb_pr.title', 'tb_pr.amount as nominal', 'tb_pr_draft.status', 'tb_pr.issuance', 'no_pr', 'tb_pr_draft.id as id', 'reply')->where('tb_pr.id_draft_pr', $request->no_pr)->first();

        $territory = DB::table('users')->select('id_territory')->where('nik', $detail->issuance)->first()->id_territory;
        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('name', 'roles.group')->where('user_id', $detail->issuance)->first();

        $listTerritory = User::where('id_territory',$territory)->where('id_position', 'MANAGER')->where('status_karyawan', '!=', 'dummy')->first();

        $kirim_user = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('email')
            ->orWhereIn('users.name',$request->emailMention)->get();

        // if ($cek_role->group == 'Sales') {
        //     $email_cc = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('email')->whereRaw("(`roles`.`name` = 'Procurement & Vendor Management' OR `roles`.`name` = 'VP Internal Chain Management' OR `users`.`name` = '".$listTerritory->name."')")
        //         ->where('status_karyawan', '!=', 'dummy')
        //         ->get()->pluck('email');
        // } else {
        //     $email_cc = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('email')->whereRaw("(`roles`.`name` = 'Procurement & Vendor Management' OR `roles`.`name` = 'VP Internal Chain Management')")
        //         ->where('status_karyawan', '!=', 'dummy')
        //         ->get()->pluck('email');
        // }

        Mail::to($kirim_user)->send(new DraftPR($detail,$kirim_user,'[SIMS-APP] ' . Auth::User()->name . ' Reply Notes On PR ' . $detail->no_pr, 'detail_approver', 'replyNotes'));
    }

    public function storeResolveNotes(Request $request)
    {
        $update = PRNotes::where('id', $request->id)->first();
        $update->resolve = 'True';
        $update->save();

        $get_id = PRNotes::select('id_draft_pr')->where('id', $request->id)->first();

        $tambah_activity = new PRActivity();
        $tambah_activity->id_draft_pr = $get_id->id_draft_pr;
        $tambah_activity->date_time = Carbon::now()->toDateTimeString();
        $tambah_activity->status = 'RESOLVE_NOTES';
        $tambah_activity->operator = Auth::User()->name;
        $tambah_activity->activity = 'Resolve notes';
        $tambah_activity->save();
    }

    public function getNotes(Request $request)
    {
        $data = PRNotes::where('tb_pr_notes.id_draft_pr', $request->no_pr)->orderBy('id', 'asc')->get();

        // foreach ($data as $key => $value) {
        //     $value->id_show = $key+1;
        // }

        return $data;
    }

    public function getDataSendEmail(Request $request)
    {
        $get_type = PR::where('tb_pr.id_draft_pr', $request->no_pr)->first();

        $territory = DB::table('users')->select('id_territory')->where('nik', $get_type->issuance)->first()->id_territory;
        $nik = Auth::User()->nik;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;

        $cek_group = PR::join('role_user', 'role_user.user_id', '=', 'tb_pr.issuance')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('roles.name', 'group', 'role_user.user_id', 'mini_group')->where('tb_pr.id_draft_pr', $request['no_pr'])->first();

        $email_to = User::select('users.email')
            ->where('email', 'elen@sinergy.co.id')
            ->get();

        if ($get_type->type_of_letter == 'IPR') {
            if ($cek_group->group == 'Program & Project Management') {
                $email_cc = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('users.email')->where('id_company', '1')->where('status_karyawan', '!=', 'dummy')->whereRaw("(`roles`.`name` = 'VP Program & Project Management' OR `roles`.`name` = 'Chief Operating Officer' OR `users`.`nik` = '" .$cek_group->user_id. "')")->get();
            } elseif ($cek_group->group == 'Internal Chain Management') {
                $email_cc = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('users.email')->where('id_company', '1')->where('status_karyawan', '!=', 'dummy')->whereRaw("(`roles`.`name` = 'VP Internal Chain Management' OR `roles`.`name` = 'Chief Operating Officer' OR `users`.`nik` = '" .$cek_group->user_id. "')")->get();
            } elseif ($cek_group->group == 'Synergy System Management') {
                $email_cc = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('users.email')->where('id_company', '1')->where('status_karyawan', '!=', 'dummy')->whereRaw("(`roles`.`name` = 'VP Synergy System Management' OR `roles`.`name` = 'Chief Operating Officer' OR `users`.`nik` = '" .$cek_group->user_id. "')")->get();
            } elseif ($cek_group->group == 'Solutions & Partnership Management') {
                $email_cc = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('users.email')->where('id_company', '1')->where('status_karyawan', '!=', 'dummy')->whereRaw("(`roles`.`name` = 'VP Solutions & Partnership Management' OR `roles`.`name` = 'Chief Operating Officer' OR `users`.`nik` = '" .$cek_group->user_id. "')")->get();
            } elseif ($cek_group->group == 'Human Capital Management') {
                $email_cc = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('users.email')->where('id_company', '1')->where('status_karyawan', '!=', 'dummy')->whereRaw("(`roles`.`name` = 'Chief Operating Officer' OR `roles`.`name` = 'VP Human Capital Management' OR `users`.`nik` = '" .$cek_group->user_id. "')")->get();
            } elseif ($cek_group->group == 'Sales') {
                $email_cc = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('users.email')->where('id_company', '1')->where('status_karyawan', '!=', 'dummy')->whereRaw("(`roles`.`name` = 'Chief Operating Officer' OR `users`.`id_position` = 'MANAGER' AND `users`.`id_territory` = '" . $territory . "' OR `users`.`nik` = '" .$cek_group->user_id. "')")->get();
            }
        } else {
            if ($cek_group->name == 'Account Executive') {
                $email_cc = DB::table('users')->select('users.email')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')
                    ->whereRaw("(`roles`.`name` = 'VP Internal Chain Management' OR `users`.`id_position` = 'MANAGER' AND `users`.`id_territory` = '" . $territory . "' OR  `roles`.`name` = 'Chief Operating Officer' OR `users`.`nik` = '" .$cek_group->user_id. "')")
                    ->where('status_karyawan','!=','dummy')->where('id_company','1')->get();
            } elseif (Str::contains($cek_group->name, 'Manager')) {
                $email_cc = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.email')
                    ->whereRaw(
                        "(`roles`.`name` LIKE ? AND `roles`.`group` = ? OR `roles`.`name` = ? OR `roles`.`name` = ? OR `users`.`nik` = ?)",
                        ['%VP',  $cek_group->group, 'VP Internal Chain Management', 'Chief Operating Officer',$cek_group->user_id]
                    )
                    ->where('status_karyawan','!=','dummy')->where('id_company','1')->get();
                // return $cek_group->group;
            } else {
                if ($cek_group->mini_group == 'Human Capital Management') {
                    $email_cc = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.email')
                        ->whereRaw(
                            "(`roles`.`mini_group` = ? AND `roles`.`name` LIKE ? AND `roles`.`name` != ? OR `roles`.`name` = ? OR `roles`.`name` = ? OR `roles`.`name` = ? OR `users`.`nik` = ?)",
                            [$cek_group->mini_group, '%Manager', 'Delivery Project Manager', 'VP Program & Project Management', 'VP Internal Chain Management', 'Chief Operating Officer',$cek_group->user_id]
                        )
                        ->where('status_karyawan','!=','dummy')->where('id_company','1')->get();
                } else {
                    $email_cc = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')->select('users.email')
                        ->whereRaw(
                            "(`roles`.`mini_group` = ? AND `roles`.`name` LIKE ? AND `roles`.`name` != ? OR `roles`.`group` = ? AND `roles`.`name` LIKE ? OR `roles`.`name` = ? OR `roles`.`name` = ? OR `users`.`nik` = ?)",
                            [$cek_group->mini_group, '%Manager', 'Delivery Project Manager', $cek_group->group, 'VP%', 'VP Internal Chain Management', 'Chief Operating Officer',$cek_group->user_id]
                        )
                        ->where('status_karyawan','!=','dummy')->where('id_company','1')->get();
                }
            }


            // $email_cc = User::oin('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('users.email')->where('id_company', '1')->where('status_karyawan', '!=', 'dummy')->whereRaw("(`roles`.`name` = 'VP Internal Chain Management' OR `users`.`id_position` = 'MANAGER' AND `users`.`id_territory` = '" . $territory . "' OR  `roles`.`name` = 'Chief Operating Officer' OR `users`.`nik` = '" .$cek_group->user_id. "')")->get();
        }

        // $email_cc = $email_cc->push(new User(['email' => 'bcd@sinergy.co.id']));
        // $email_cc = $email_cc->push(new User(['email' => 'bcd@sinergy.co.id']));

        $subject = 'Permohonan Pembuatan PO - ' . $get_type->type_of_letter . ' ' . $get_type->title;

        return ['to' => $email_to, 'cc' => $email_cc, 'subject'=>$subject];
    }

    public function getPdfPr(Request $request)
    {
        // return view('pdf_pr_unheader');
        // $pdf = PDF::loadView('pdf_pr_unheader');
        // return $pdf;
        $data = DB::table('tb_pr')->join('users', 'users.nik', '=', 'tb_pr.issuance')->join('tb_pr_draft', 'tb_pr_draft.id', '=', 'tb_pr.id_draft_pr')
            ->join('tb_id_project', 'tb_id_project.id_project', '=', 'tb_pr.project_id', 'left')
            ->join('tb_contact', 'tb_contact.customer_legal_name', '=', 'tb_id_project.customer_name', 'left')
            ->select('tb_pr.to', 'tb_pr.email', 'tb_pr.phone as phone_pr', 'tb_pr.attention', 'tb_pr.title', 'tb_pr.address', 'tb_pr.request_method', 'tb_pr.created_at', DB::raw("(CASE WHEN (`tb_pr`.`lead_id` = 'null') THEN '-' ELSE `tb_pr`.`lead_id` END) as lead_id"), DB::raw("(CASE WHEN (`tb_pr`.`quote_number` = 'null') THEN '-' ELSE `tb_pr`.`quote_number` END) as quote_number"), 'tb_pr.term_payment','tb_pr.type_of_letter', 'users.name','tb_pr.id_draft_pr', 'tb_pr.no_pr', 'tb_pr.isRupiah',
                DB::raw("(CASE WHEN (tb_pr.fax is null) THEN '-' ELSE tb_pr.fax END) as fax"), 'project_id', 'tb_pr.category', 'customer_name as to_customer', 'amount_idr as grand_total', 'name_project as subject', 'tb_pr.issuance', 'parent_id_drive', 'status_draft_pr',
                DB::raw('IF(`tb_id_project`.`date` >= "2022-04-01", (`tb_id_project`.`amount_idr`*100)/111, (`tb_id_project`.`amount_idr`*10)/11) as `amount_idr_before_tax`'), 'street_address as address_customer', 'sales_name as from', 'tb_contact.phone', 'no_po_customer', 'city', 'province', 'postal', 'office_building', 'tb_id_project.created_at as tgl_pid', 'tb_id_project.date as date_pid', DB::raw("(CASE WHEN (`tb_pr`.`tax_pb` is null) THEN 'false' WHEN (`tb_pr`.`tax_pb` = '0') THEN 'false' ELSE `tb_pr`.`tax_pb` END) as tax_pb"), DB::raw("(CASE WHEN (`tb_pr`.`service_charge` is null) THEN 'false' WHEN (`tb_pr`.`service_charge` = '0') THEN 'false' ELSE `tb_pr`.`service_charge` END) as service_charge"), DB::raw("(CASE 
                        WHEN (`tb_pr`.`discount` IS NULL OR `tb_pr`.`discount` = '0') 
                        THEN 'false' 
                        ELSE ROUND(`tb_pr`.`discount`, 2) 
                    END) as discount"),'tb_pr.discount as discount_nominal',DB::raw("(CASE WHEN (`tb_pr`.`status_tax` is null) THEN 'false' ELSE `tb_pr`.`status_tax` END) as status_tax"))->where('tb_pr.id_draft_pr', $request->no_pr)->first();

        $data->tax_base_other_customer = round($data->amount_idr_before_tax * 11/12);

        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('name', 'group')->where('user_id', $data->issuance)->first();

        $parent_id = explode('"', $data->parent_id_drive)[1];

        if ($data->status_draft_pr == 'pembanding') {
            $product = PrProduct::join('tb_pr_product_compare', 'tb_pr_product_compare.id_product', '=', 'tb_pr_product.id', 'left')
                ->join('tb_pr_compare', 'tb_pr_compare.id', '=', 'tb_pr_product_compare.id_compare_pr', 'left')
                ->select('name_product', 'qty', 'unit', 'tb_pr_product.description', 'nominal_product', 'grand_total',
                    DB::raw("(CASE WHEN (serial_number is null) THEN '-' ELSE serial_number END) as serial_number"),
                    DB::raw("(CASE WHEN (part_number is null) THEN '-' ELSE part_number END) as part_number"))->where('tb_pr_compare.id_draft_pr', $request->no_pr)->where('status','Selected')->orderBy('tb_pr_product_compare.id_product', 'asc')->get();

            $sum_nominal = PrProduct::join('tb_pr_product_compare', 'tb_pr_product_compare.id_product', '=', 'tb_pr_product.id', 'left')
                ->join('tb_pr_compare', 'tb_pr_compare.id', '=', 'tb_pr_product_compare.id_compare_pr', 'left')
                ->select('grand_total')->where('tb_pr_compare.id_draft_pr', $request->no_pr)->where('status','Selected')->sum('grand_total');

        } else if($data->status_draft_pr == 'draft'){
            $product = PrProduct::join('tb_pr_product_draft', 'tb_pr_product_draft.id_product', '=', 'tb_pr_product.id')
                ->join('tb_pr_draft', 'tb_pr_draft.id', '=', 'tb_pr_product_draft.id_draft_pr')
                ->select('name_product', 'qty', 'unit', 'tb_pr_product.description', 'nominal_product', 'grand_total', DB::raw("(CASE WHEN (serial_number is null) THEN '-' ELSE serial_number END) as serial_number"), DB::raw("(CASE WHEN (part_number is null) THEN '-' ELSE part_number END) as part_number"))
                ->where('tb_pr_product_draft.id_draft_pr', $request->no_pr)
                ->orderBy('tb_pr_product_draft.id_product', 'asc')->get();

            $sum_nominal = PrProduct::join('tb_pr_product_draft', 'tb_pr_product_draft.id_product', '=', 'tb_pr_product.id')
                ->join('tb_pr_draft', 'tb_pr_draft.id', '=', 'tb_pr_product_draft.id_draft_pr')
                ->select('grand_total')->where('tb_pr_product_draft.id_draft_pr', $request->no_pr)->sum('grand_total');
        }


        if ($data->discount != 'false') {
            $amount_discount = round($sum_nominal * ($data->discount_nominal))/100;

            $sum_nominal_subtracted = $sum_nominal - $amount_discount;
        } else {
            $sum_nominal_subtracted = $sum_nominal;
            $amount_discount = 0;
        }

        if ($data->status_tax == '1.1') {
            $amount_tax = round(($sum_nominal_subtracted * 11)/1000);
        } elseif ($data->status_tax == '1.2') {
            $amount_tax = round(($sum_nominal_subtracted * 1.2)/1000);
        } elseif ($data->status_tax == '11') {
            $amount_tax = round(($sum_nominal_subtracted * 11)/100);
        } elseif ($data->status_tax == '12') {
            $amount_tax = round(($sum_nominal_subtracted * 12)/100);
        } else {
            $amount_tax = 0;
        }

        if ($data->tax_pb != 'false') {
            $amount_pb = round($sum_nominal_subtracted * ($data->tax_pb))/100;
        } else {
            $amount_pb = 0;
        }

        if ($data->service_charge != 'false') {
            $amount_service_charge = round($sum_nominal_subtracted * ($data->service_charge))/100;
        } else {
            $amount_service_charge = 0;
        }

        $tax_base_other = round($sum_nominal_subtracted * 11/12);

        // return $sum_nominal_subtracted;

        $grand_total = $sum_nominal_subtracted+$amount_tax+$amount_pb+$amount_service_charge;

        $territory = DB::table('users')
            ->select('id_territory')
            ->where('nik', $data->issuance)
            ->first()
            ->id_territory;

        $cek_group = PRDraft::join('role_user', 'role_user.user_id', '=', 'tb_pr_draft.issuance')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('roles.name', 'roles.group')
            ->where('tb_pr_draft.id', $request->no_pr)
            ->first();

        $unapproved = DB::table('tb_pr_activity')
            ->where('tb_pr_activity.id_draft_pr', $request->no_pr)
            ->where('tb_pr_activity.status', "UNAPPROVED")
            ->orderBy('tb_pr_activity.id',"DESC")
            ->get();

        $tb_pr_activity = DB::table('tb_pr_activity')
            ->where('tb_pr_activity.id_draft_pr', $request->no_pr);

        if(count($unapproved) != 0){
            $tb_pr_activity->where('tb_pr_activity.id','>',$unapproved->first()->id);
        }

        $tb_pr_activity->where(function($query){
            $query->where('tb_pr_activity.status', 'CIRCULAR')
                ->orWhere('tb_pr_activity.status', 'FINALIZED');
        });

        $sign = User::join('role_user', 'role_user.user_id', '=', 'users.nik')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select(
                'users.name',
                'roles.name as position',
                'ttd',
                DB::raw("IFNULL(SUBSTR(`temp_tb_pr_activity`.`date_time`,1,10),'-') AS `date_sign`"),
                DB::raw('IF(ISNULL(`temp_tb_pr_activity`.`date_time`),"false","true") AS `signed`')
            )
            ->leftJoinSub($tb_pr_activity,'temp_tb_pr_activity',function($join){
                // $join->on("temp_tb_pr_activity.operator","=","users.name");
                $join->on("users.name","LIKE",DB::raw("CONCAT('%', temp_tb_pr_activity.operator, '%')"));
            })
            ->where('id_company', '1')
            ->where('status_karyawan', '!=', 'dummy');

        $signData = $sign->get(); // Fetch once

        $hasElfiMaryanisSigned = $signData->contains(function ($value) {
            return $value->name == 'Elfi Maryanis' && $value->signed == 'true';
        });


        // if ($data->type_of_letter == 'EPR') {
        //     if ($data->category == 'Bank Garansi') {

        //         foreach ($sign->get() as $key => $value) {
        //             if ($value->name == 'Endraw Denny Hermanto' && $value->signed == 'true') {
        //                 $sign->whereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'BCD' OR `users`.`id_position` = 'MANAGER' AND `users`.`id_territory` = '" . $territory . "' OR  `users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER')")
        //                     ->orderByRaw('FIELD(position, "BCD Manager", "VP Sales", "Chief Operating Officer")');
        //             } else {
        //                 $sign->whereRaw("(`roles`.`name` = 'VP Internal Chain Management' OR `users`.`id_position` = 'MANAGER' AND `users`.`id_territory` = '" . $territory . "' OR  `roles`.`name` = 'Chief Operating Officer')")
        //                     ->orderByRaw('FIELD(position, "VP Internal Chain Management", "VP Sales", "Chief Operating Officer")');
        //             }
        //         }
        //         // $sign->whereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'BCD' OR `users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'MSM' OR `users`.`id_position` = 'MANAGER' AND `users`.`id_territory` = '" . $territory . "' OR  `users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER')")
        //         // ->orderByRaw('FIELD(position, "VP Internal Chain Management", "VP Sales", "Chief Operating Officer")');
        //     } else {
        //         foreach ($sign->get() as $key => $value) {
        //             if ($value->name == 'Endraw Denny Hermanto' && $value->signed == 'true') {
        //                 $sign->whereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'BCD' OR `users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER' OR `users`.`id_division` = 'TECHNICAL PRESALES' AND `users`.`id_position` = 'MANAGER' OR `users`.`id_division` = 'PMO' AND `users`.`id_position` = 'MANAGER')")
        //                     ->orderByRaw('FIELD(position, "BCD Manager", "VP Project Management", "VP Solutions & Partnership Management", "Chief Operating Officer")');
        //             } else {
        //                 $sign->whereRaw("(`roles`.`name` = 'VP Internal Chain Management' OR `roles`.`name` = 'Chief Operating Officer' OR `roles`.`name` = 'VP Solutions & Partnership Management' OR `roles`.`name` = 'VP Project Management')")
        //                     ->orderByRaw('FIELD(position, "VP Internal Chain Management", "VP Project Management", "VP Solutions & Partnership Management", "Chief Operating Officer")');
        //             }
        //         }

        //         // $sign->whereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'BCD' OR `users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER' OR `users`.`id_division` = 'TECHNICAL PRESALES' AND `users`.`id_position` = 'MANAGER' OR `users`.`id_division` = 'PMO' AND `users`.`id_position` = 'MANAGER')")
        //         // ->orderByRaw('FIELD(position, "BCD Manager", "VP Project Management", "VP Solutions & Partnership Management", "Chief Operating Officer")');
        //     }
        // } else {
        //     if ($cek_group->group == 'Project Management') {

        //         foreach ($sign->get() as $key => $value) {
        //             if ($value->name == 'Endraw Denny Hermanto' && $value->signed == 'true') {
        //                 $sign->whereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'BCD' OR `users`.`id_division` = 'PMO' AND `users`.`id_position` = 'MANAGER' OR `users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER')")
        //                     ->orderByRaw('FIELD(position, "BCD Manager", "VP Project Management", "Chief Operating Officer")');
        //             } else {
        //                 $sign->whereRaw("(`roles`.`name` = 'VP Internal Chain Management' OR `roles`.`name` = 'VP Project Management' OR `roles`.`name` = 'Chief Operating Officer')")
        //                     ->orderByRaw('FIELD(position, "VP Internal Chain Management", "VP Project Management", "Chief Operating Officer")');
        //             }
        //         }

        //         // $sign->whereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'BCD' OR `users`.`id_division` = 'PMO' AND `users`.`id_position` = 'MANAGER' OR `users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'MSM' OR `users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER')")
        //         // ->orderByRaw('FIELD(position, "VP Internal Chain Management", "VP Project Management", "Chief Operating Officer")');

        //     }
        //     elseif ($cek_group->group == 'Supply Chain, CPS & Asset Management') {

        //         foreach ($sign->get() as $key => $value) {
        //             if ($value->name == 'Endraw Denny Hermanto' && $value->signed == 'true') {
        //                 $sign->whereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'BCD' OR `users`.`id_division` = 'MSM' AND `users`.`id_position` = 'MANAGER' OR `users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER')")
        //                     ->orderByRaw('FIELD(position, "BCD Manager", "Chief Operating Officer")');
        //             } else {
        //                 $sign->whereRaw("(`roles`.`name` = 'VP Internal Chain Management' OR `roles`.`name` = 'Chief Operating Officer')")
        //                     ->orderByRaw('FIELD(position, "VP Internal Chain Management", "Chief Operating Officer")');
        //             }
        //         }

        //         // $sign->whereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'BCD' OR `users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'MSM' OR `users`.`id_division` = 'MSM' AND `users`.`id_position` = 'MANAGER' OR `users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER')")
        //         // ->orderByRaw('FIELD(position, "VP Internal Chain Management", "Chief Operating Officer")');

        //     }elseif ($cek_group->group == 'Product Management & Development') {

        //         foreach ($sign->get() as $key => $value) {
        //             if ($value->name == 'Endraw Denny Hermanto' && $value->signed == 'true') {
        //                 $sign->whereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'BCD' OR `users`.`id_division` = 'TECHNICAL PRESALES' AND `users`.`id_position` = 'MANAGER' OR `users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER')")
        //                     ->orderByRaw('FIELD(position, "BCD Manager", "VP Solutions & Partnership Management", "Chief Operating Officer")');
        //             } else {
        //                 $sign->whereRaw("(`roles`.`name` = 'VP Internal Chain Management' OR `roles`.`name` = 'VP Solutions & Partnership Management' OR `roles`.`name` = 'Chief Operating Officer')")
        //                     ->orderByRaw('FIELD(position, "VP Internal Chain Management", "VP Solutions & Partnership Management", "Chief Operating Officer")');
        //             }
        //         }

        //         // $sign->whereRaw("( `users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'MSM' OR `users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER')")
        //         // ->orderByRaw('FIELD(position, "VP Internal Chain Management", "BCD Manager","Chief Operating Officer")');

        //     } elseif ($cek_group->group == 'Solution Implementation & Managed Service') {
        //         foreach ($sign->get() as $key => $value) {
        //             if ($value->name == 'Endraw Denny Hermanto' && $value->signed == 'true') {
        //                 $sign->whereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'BCD' OR `users`.`id_position` = 'ENGINEER MANAGER' OR `users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER')")
        //                     ->orderByRaw('FIELD(position, "BCD Manager", "VP Solution Implementation & Managed Service","Chief Operating Officer")');
        //             } else {
        //                 $sign->whereRaw("(`roles`.`name` = 'VP Internal Chain Management' OR `roles`.`name` = 'VP Solution Implementation & Managed Service' OR `roles`.`name` = 'Chief Operating Officer')")
        //                     ->orderByRaw('FIELD(position, "VP Internal Chain Management", "VP Solution Implementation & Managed Service","Chief Operating Officer")');
        //             }
        //         }
        //         // $sign->whereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'BCD' OR `users`.`id_position` = 'ENGINEER MANAGER' OR `users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER')")
        //         // ->orderByRaw('FIELD(position, "VP Internal Chain Management", "VP Solution Implementation & Managed Service","Chief Operating Officer")');

        //     } elseif ($cek_group->group == 'Human Capital') {
        //         foreach ($sign->get() as $key => $value) {
        //             if ($value->name == 'Endraw Denny Hermanto' && $value->signed == 'true') {
        //                 $sign->whereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'BCD' OR `roles`.`name` = 'Renumeration, Personalia & GS Manager' OR  `users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER')")
        //                     ->orderByRaw('FIELD(position, "BCD Manager", "Renumeration, Personalia & GS Manager", "Chief Operating Officer")');
        //             } elseif ($hasElfiMaryanisSigned) {
        //                 $sign->whereRaw("
        //                     (`roles`.`name` = 'VP Internal Chain Management' 
        //                     OR `roles`.`name` = 'Renumeration, Personalia & GS Manager' 
        //                     OR `users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER')
        //                 ")
        //                     ->orderByRaw('FIELD(position, "VP Internal Chain Management", "Renumeration, Personalia & GS Manager", "Chief Operating Officer")');
        //             }  else {
        //                 $sign->whereRaw("(`roles`.`name` = 'VP Internal Chain Management' OR `roles`.`name` = 'VP Human Capital' OR `roles`.`name` = 'Chief Operating Officer')")
        //                     ->orderByRaw('FIELD(position, "VP Internal Chain Management", "VP Human Capital", "Chief Operating Officer")');
        //             }
        //         }
        //         // $sign->whereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'BCD' OR `roles`.`name` = 'Renumeration, Personalia & GS Manager' OR  `users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER')")
        //         // ->orderByRaw('FIELD(position, "VP Internal Chain Management", "Renumeration, Personalia & GS Manager", "Chief Operating Officer")');

        //     } elseif ($cek_group->group == 'Sales') {
        //         foreach ($sign->get() as $key => $value) {
        //             if ($value->name == 'Endraw Denny Hermanto' && $value->signed == 'true') {
        //                 $sign->whereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'BCD' OR `users`.`id_position` = 'MANAGER' AND `users`.`id_territory` = '" . $territory . "' OR  `users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER')")
        //                     ->orderByRaw('FIELD(position, "BCD Manager", "VP Sales", "Chief Operating Officer")');
        //             } else {
        //                 $sign->whereRaw("(`roles`.`name` = 'VP Internal Chain Management' OR `users`.`id_position` = 'MANAGER' AND `users`.`id_territory` = '" . $territory . "' OR  `roles`.`name` = 'Chief Operating Officer')")
        //                     ->orderByRaw('FIELD(position, "VP Internal Chain Management", "VP Sales", "Chief Operating Officer")');
        //             }
        //         }
        //         // $sign->whereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'BCD' OR `users`.`id_position` = 'MANAGER' AND `users`.`id_territory` = '" . $territory . "' OR  `users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER')")
        //         // ->orderByRaw('FIELD(position, "VP Internal Chain Management", "VP Sales", "Chief Operating Officer")');
        //     }
        // }

        $sign = $this->getSignStatusPR($request->no_pr,'circular');
        // return $sign;
        // $data->term_payment = "<b>Terms & Condition :</b> <br>" . $data->term_payment;
        $pdf = PDF::loadView('pdf_pr_unheader', compact('data', 'product','sign', 'sum_nominal', 'sum_nominal_subtracted', 'amount_tax', 'cek_role','grand_total','amount_pb','amount_service_charge','amount_discount','tax_base_other'));
        $fileName =   $data->no_pr  . ' ' . $data->title  . '.pdf';
        $nameFileFix = str_replace(' ', '_', $fileName);

        return $pdf->stream($nameFileFix);
        // return $pdf->output();
        // return view('pdf_pr_unheader', compact('data', 'product','sign', 'sum_nominal', 'amount_tax', 'cek_role','grand_total'));
    }

    public function getEmailTemplate(Request $request){
        $data = PR::join('users', 'users.nik', '=', 'tb_pr.issuance')->join('tb_pr_draft', 'tb_pr_draft.id', 'tb_pr.id_draft_pr')->join('tb_id_project', 'tb_id_project.id_project', '=', 'tb_pr.project_id', 'left')->join('tb_contact', 'tb_contact.customer_legal_name', '=', 'tb_id_project.customer_name', 'left')->select('tb_pr.to', 'tb_pr.email', 'tb_pr.phone', 'tb_pr.attention', 'tb_pr.title', 'tb_pr.address', 'tb_pr.request_method', 'tb_pr.created_at', DB::raw("(CASE WHEN (`tb_pr`.`lead_id` = 'null') THEN '-' ELSE `tb_pr`.`lead_id` END) as lead_id"), DB::raw("(CASE WHEN (`tb_pr`.`quote_number` = 'null') THEN '-' ELSE `tb_pr`.`quote_number` END) as quote_number"), 'tb_pr.term_payment','tb_pr.type_of_letter', 'users.name','tb_pr.id_draft_pr', 'amount', DB::raw('IF(`tb_pr`.`date` >= "2022-04-01", (`tb_pr`.`amount`*100)/111, (`tb_pr`.`amount`*10)/11) as `amount_pr_before_tax`'), DB::raw("(CASE WHEN (tb_pr.fax is null) THEN '-' ELSE tb_pr.fax END) as fax"), 'project_id', 'tb_pr.category', 'status_draft_pr', 'tb_pr.no_pr', 'customer_name as to_customer', 'amount_idr as grand_total', 'name_project as subject', DB::raw('IF(`tb_id_project`.`date` >= "2022-04-01", 
        (`tb_id_project`.`amount_idr`*100)/111, (`tb_id_project`.`amount_idr`*10)/11) as `amount_idr_before_tax`'), 'street_address as address_customer', 'sales_name as from', 'tb_contact.phone', 'no_po_customer', 'city', 'province', 'postal', 'office_building', 'tb_id_project.created_at as tgl_pid', 'tb_id_project.date as date_pid', 'tb_pr.status_tax', 'tb_pr.isRupiah', 'parent_id_drive', DB::raw("(CASE WHEN (`tb_pr`.`tax_pb` is null) THEN 'false' WHEN (`tb_pr`.`tax_pb` = '0') THEN 'false' ELSE `tb_pr`.`tax_pb` END) as tax_pb"), DB::raw("(CASE WHEN (`tb_pr`.`service_charge` is null) THEN 'false' WHEN (`tb_pr`.`service_charge` = '0') THEN 'false' ELSE `tb_pr`.`service_charge` END) as service_charge"),DB::raw("(CASE 
                WHEN (`tb_pr`.`discount` IS NULL OR `tb_pr`.`discount` = '0') 
                THEN 'false' 
                ELSE ROUND(`tb_pr`.`discount`, 2) 
            END) as discount"), DB::raw("(CASE WHEN (`tb_pr`.`status_tax` is null) THEN 'false' ELSE `tb_pr`.`status_tax` END) as status_tax"))->where('tb_pr.id_draft_pr', $request->no_pr)->first();

        $data->tax_base_other_customer = round($data->amount_idr_before_tax * 11/12);

        if ($data->status_draft_pr == 'draft') {
            $product = PrProduct::join('tb_pr_product_draft', 'tb_pr_product_draft.id_product', '=', 'tb_pr_product.id')
                ->join('tb_pr_draft', 'tb_pr_draft.id', '=', 'tb_pr_product_draft.id_draft_pr')
                ->select('name_product', 'qty', 'unit', 'tb_pr_product.description', 'nominal_product', 'grand_total', DB::raw("(CASE WHEN (serial_number is null) THEN '-' ELSE serial_number END) as serial_number"), DB::raw("(CASE WHEN (part_number is null) THEN '-' ELSE part_number END) as part_number"))
                ->where('tb_pr_product_draft.id_draft_pr', $request->no_pr)
                ->orderBy('tb_pr_product_draft.id_product', 'asc')->get();

            $sum_nominal = PrProduct::join('tb_pr_product_draft', 'tb_pr_product_draft.id_product', '=', 'tb_pr_product.id')
                ->join('tb_pr_draft', 'tb_pr_draft.id', '=', 'tb_pr_product_draft.id_draft_pr')
                ->select('grand_total')->where('tb_pr_product_draft.id_draft_pr', $request->no_pr)->sum('grand_total');

            $dokumen = PrDokumen::join('tb_pr_document_draft', 'tb_pr_document_draft.id_document', '=', 'tb_pr_document.id')
                ->join('tb_pr_draft', 'tb_pr_draft.id', 'tb_pr_document_draft.id_draft_pr')
                ->select('dokumen_name', 'dokumen_location', 'link_drive')
                ->where('tb_pr_document_draft.id_draft_pr', $request->no_pr)
                ->orderByRaw('FIELD(dokumen_name, "SBE", "Quote Supplier", "SPK")')->get();
        } else if ($data->status_draft_pr == 'pembanding'){
            $product = PrProduct::join('tb_pr_product_compare', 'tb_pr_product_compare.id_product', '=', 'tb_pr_product.id', 'left')
                ->join('tb_pr_compare', 'tb_pr_compare.id', '=', 'tb_pr_product_compare.id_compare_pr', 'left')
                ->select('name_product', 'qty', 'unit', 'tb_pr_product.description', 'nominal_product', 'grand_total', DB::raw("(CASE WHEN (serial_number is null) THEN '-' ELSE serial_number END) as serial_number"), DB::raw("(CASE WHEN (part_number is null) THEN '-' ELSE part_number END) as part_number"))->where('tb_pr_compare.id_draft_pr', $request->no_pr)->where('status','Selected')->orderBy('tb_pr_product_compare.id_product', 'asc')->get();

            $sum_nominal = PrProduct::join('tb_pr_product_compare', 'tb_pr_product_compare.id_product', '=', 'tb_pr_product.id', 'left')
                ->join('tb_pr_compare', 'tb_pr_compare.id', '=', 'tb_pr_product_compare.id_compare_pr', 'left')
                ->select('grand_total')->where('tb_pr_compare.id_draft_pr', $request->no_pr)->where('status','Selected')->sum('grand_total');

            $dokumen = PrDokumen::join('tb_pr_document_compare', 'tb_pr_document_compare.id_document', '=', 'tb_pr_document.id')
                ->join('tb_pr_compare', 'tb_pr_compare.id', 'tb_pr_document_compare.id_compare_pr')
                ->select('dokumen_name', 'dokumen_location', 'link_drive')
                ->where('tb_pr_compare.id_draft_pr', $request->no_pr)
                ->orderByRaw('FIELD(dokumen_name, "SBE", "Quote Supplier", "SPK")')->get();
        }

        if ($data->discount != 'false') {
            $amount_discount_hitung = round(($sum_nominal * $data->discount)/100);
            $amount_discount = round($amount_discount_hitung / 10000) * 10000;

            $sum_nominal_subtracted = $sum_nominal - $amount_discount;
        } else {
            $amount_discount = 0;
            $sum_nominal_subtracted = $sum_nominal;
        }

        if ($data->status_tax == '1.1') {
            $amount_tax = round(($sum_nominal_subtracted * 11)/1000);
        } elseif ($data->status_tax == '1.2') {
            $amount_tax = round(($sum_nominal_subtracted * 12)/1000);
        } elseif ($data->status_tax == '11') {
            $amount_tax = round(($sum_nominal_subtracted * 11)/100);
        } elseif ($data->status_tax == '12') {
            $amount_tax = round(($sum_nominal_subtracted * 12)/100);
        } else {
            $amount_tax = 0;
        }

        // if ($data->status_tax == '1.1') {
        //         $amount_tax = (($sum_nominal - $amount_discount) * 11)/1000;
        // } elseif ($data->status_tax == '11') {
        //     $amount_tax = (($sum_nominal - $amount_discount) * 11)/100;
        // } else {
        //     $amount_tax = 0;
        // }

        if ($data->tax_pb != 'false') {
            $amount_pb = round($sum_nominal_subtracted * ($data->tax_pb))/100;
        } else {
            $amount_pb = 0;
        }

        $tax_base_other = round($sum_nominal_subtracted * 11/12);

        if ($data->service_charge != 'false') {
            $amount_service_charge = round($sum_nominal_subtracted * ($data->service_charge))/100;
        } else {
            $amount_service_charge = 0;
        }

        $grand_total = $sum_nominal_subtracted+$amount_tax+$amount_pb+$amount_service_charge;

        // if ($data->date > '2024-12-31') {
        //     $amount_tax_project = $data->amount_idr_before_tax * 12/100;
        // } else {
        $amount_tax_project = $data->amount_idr_before_tax * 11/100;
        // }

        $client = $this->getClient();
        $service = new Google_Service_Drive($client);

        $optParams = array(
            'fields' => 'files(webViewLink)',
            // 'q' => 'name="'. $request->no_pr .' Draft PR"',
            'q' => 'mimeType="application/pdf" and "' . explode('"',$data->parent_id_drive)[1] . '" in parents',
            'supportsAllDrives' => true,
            'includeItemsFromAllDrives' => true
        );

        $linkDrive = collect([
            "folder" => "SIMS Apps > Purchase Request > " . ($data->type_of_letter == "EPR" ? "External" : "Internal"). " > " . $request->no_pr .' Draft PR',
            "link" => $service->files->listFiles($optParams)->getFiles()[0]->getWebViewLink()
        ]);

        // return $linkDrive;

        // return collect([
        //     'pr' => $data,
        //     'product' => $product,
        //     'dokumen' => $dokumen
        // ]);

        // return $sum_nominal;
        // echo "<pre>";
        // return $data;
        // return $product;
        // return $dokumen;
        // return $sum_nominal;
        // return $amount_tax;

        // return "</pre>endd---";

        return view('pr_pdf', compact('data','product','dokumen', 'sum_nominal', 'amount_tax','amount_tax_project','linkDrive','grand_total', 'amount_pb', 'amount_service_charge','amount_discount','tax_base_other'));
    }

    public function sendMailtoFinance(Request $request)
    {
        $tambah = new PRActivity();
        $tambah->operator = Auth::User()->name;
        $tambah->id_draft_pr = $request['no_pr'];
        $tambah->status = 'SENDED';
        $tambah->date_time = Carbon::now()->toDateTimeString();
        if ($request->status == 'sended') {
            $tambah->activity = 'PR has been processed';
        } else {
            $tambah->activity = 'PR has been sent to the Finance Division';
        }
        $tambah->save();

        $update = PRDraft::where('id', $request->no_pr)->first();
        $update->status = 'SENDED';
        $update->save();

        $update_pr = PR::where('id_draft_pr', $request->no_pr)->first();
        $update_pr->status = 'Done';
        $update_pr->save();

        if ($request->status != 'sended') {
            $this->sendEmail($request->to,$request->cc,$request->subject,$request->body);
        }

        $get_status = PR::select('status_draft_pr')->where('id_draft_pr', $request->no_pr)->first();
        $activity = PRActivity::select('activity','operator','id_draft_pr')->where('tb_pr_activity.id_draft_pr', $request->no_pr)->where('status', 'SENDED')->orderBy('date_time', 'desc')->take(1);

        if ($get_status->status_draft_pr == 'pembanding') {
            $detail = PR::join('tb_pr_draft', 'tb_pr_draft.id', '=', 'tb_pr.id_draft_pr')->join('users', 'users.nik', '=', 'tb_pr_draft.issuance')->join('tb_pr_compare', 'tb_pr_compare.id_draft_pr', '=', 'tb_pr_draft.id')
                ->joinSub($activity,'temp_tb_pr_activity',function($join){
                    $join->on("temp_tb_pr_activity.id_draft_pr","tb_pr_draft.id");
                })
                ->select('users.name as name_issuance', 'tb_pr.to', 'tb_pr.attention', 'tb_pr.title', 'tb_pr.amount as nominal', 'tb_pr_draft.status', 'tb_pr.issuance', 'activity', 'no_pr', 'tb_pr_draft.id as id')
                ->where('tb_pr.id_draft_pr', $request->no_pr)->first();
        } else {
            $detail = PR::join('tb_pr_draft', 'tb_pr_draft.id', '=', 'tb_pr.id_draft_pr')->join('users', 'users.nik', '=', 'tb_pr_draft.issuance')
                ->joinSub($activity,'temp_tb_pr_activity',function($join){
                    $join->on("temp_tb_pr_activity.id_draft_pr","tb_pr_draft.id");
                })
                ->select('users.name as name_issuance', 'tb_pr.to', 'tb_pr.attention', 'tb_pr.title', 'tb_pr.amount as nominal', 'tb_pr_draft.status', 'tb_pr.issuance', 'activity', 'no_pr', 'tb_pr_draft.id as id')
                ->where('tb_pr.id_draft_pr', $request->no_pr)->first();
        }

        $kirim_user = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('email', 'users.name as name_receiver')->where('nik', $detail->issuance)->first();

        //Disabled push notification
        /*$jsonInsertCreate = array(
            "heximal" => "#3c8dbc", //diisi warna sesuai status
            "id_pr" => $detail->id,
            "title" => $detail->title, //diisi subject
            "result"=> 'SENDED',
            "showed"=>"true",
            "status"=>"unread",
            "to"=> $kirim_user->email,
            "date_time"=>Carbon::now()->timestamp,
            "module"=>"draft"
        );

        $this->getNotifBadgeInsert($jsonInsertCreate);*/
    }

    public function sendEmail($to, $cc, $subject, $body){
        Mail::html($body, function ($message) use ($to, $cc, $subject) {
            $message
                ->to(explode(";", $to))
                ->subject($subject);

            if($cc != ""){
                $message->cc(explode(";", $cc));
            }
        });
    }

    public function getPdf($no_pr)
    {
        $data = DB::table('tb_pr')->join('users', 'users.nik', '=', 'tb_pr.issuance')->join('tb_pr_draft', 'tb_pr_draft.id', '=', 'tb_pr.id_draft_pr')
            ->join('tb_id_project', 'tb_id_project.id_project', '=', 'tb_pr.project_id', 'left')
            ->join('tb_contact', 'tb_contact.customer_legal_name', '=', 'tb_id_project.customer_name', 'left')
            ->select('tb_pr.to', 'tb_pr.email', 'tb_pr.phone as phone_pr', 'tb_pr.attention', 'tb_pr.title', 'tb_pr.address', 'tb_pr.request_method', 'tb_pr.created_at', DB::raw("(CASE WHEN (`tb_pr`.`lead_id` = 'null') THEN '-' ELSE `tb_pr`.`lead_id` END) as lead_id"), DB::raw("(CASE WHEN (`tb_pr`.`quote_number` = 'null') THEN '-' ELSE `tb_pr`.`quote_number` END) as quote_number"), 'tb_pr.term_payment','tb_pr.type_of_letter', 'users.name','tb_pr.id_draft_pr', 'tb_pr.no_pr', 'tb_pr.isRupiah',
                DB::raw("(CASE WHEN (tb_pr.fax is null) THEN '-' ELSE tb_pr.fax END) as fax"), 'project_id', 'tb_pr.category', 'customer_name as to_customer', 'amount_idr as grand_total', 'name_project as subject', 'tb_pr.issuance', 'parent_id_drive', 'status_draft_pr',
                DB::raw('IF(`tb_id_project`.`date` >= "2022-04-01", (`tb_id_project`.`amount_idr`*100)/111, (`tb_id_project`.`amount_idr`*10)/11) as `amount_idr_before_tax`'), 'street_address as address_customer', 'sales_name as from', 'tb_contact.phone', 'no_po_customer', 'city', 'province', 'postal', 'office_building', 'tb_id_project.created_at as tgl_pid', 'tb_id_project.date as date_pid', DB::raw("(CASE WHEN (`tb_pr`.`tax_pb` is null) THEN 'false' WHEN (`tb_pr`.`tax_pb` = '0') THEN 'false' ELSE `tb_pr`.`tax_pb` END) as tax_pb"), DB::raw("(CASE WHEN (`tb_pr`.`service_charge` is null) THEN 'false' WHEN (`tb_pr`.`service_charge` = '0') THEN 'false' ELSE `tb_pr`.`service_charge` END) as service_charge"), DB::raw("(CASE 
                        WHEN (`tb_pr`.`discount` IS NULL OR `tb_pr`.`discount` = '0') 
                        THEN 'false' 
                        ELSE ROUND(`tb_pr`.`discount`, 2) 
                    END) as discount"), 'tb_pr.discount as discount_nominal', DB::raw("(CASE WHEN (`tb_pr`.`status_tax` is null) THEN 'false' ELSE `tb_pr`.`status_tax` END) as status_tax"))->where('tb_pr.id_draft_pr', $no_pr)->first();

        $data->tax_base_other_customer = round($data->amount_idr_before_tax * 11/12);

        $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('name', 'group')->where('user_id', $data->issuance)->first();

        $parent_id = explode('"', $data->parent_id_drive)[1];

        if ($data->status_draft_pr == 'pembanding') {
            $product = PrProduct::join('tb_pr_product_compare', 'tb_pr_product_compare.id_product', '=', 'tb_pr_product.id', 'left')
                ->join('tb_pr_compare', 'tb_pr_compare.id', '=', 'tb_pr_product_compare.id_compare_pr', 'left')
                ->select('name_product', 'qty', 'unit', 'tb_pr_product.description', 'nominal_product', 'grand_total', DB::raw("(CASE WHEN (serial_number is null) THEN '-' ELSE serial_number END) as serial_number"), DB::raw("(CASE WHEN (part_number is null) THEN '-' ELSE part_number END) as part_number"))->where('tb_pr_compare.id_draft_pr', $no_pr)->where('status','Selected')->orderBy('tb_pr_product_compare.id_product', 'asc')->get();

            $sum_nominal = PrProduct::join('tb_pr_product_compare', 'tb_pr_product_compare.id_product', '=', 'tb_pr_product.id', 'left')
                ->join('tb_pr_compare', 'tb_pr_compare.id', '=', 'tb_pr_product_compare.id_compare_pr', 'left')
                ->select('grand_total')->where('tb_pr_compare.id_draft_pr', $no_pr)->where('status','Selected')->sum('grand_total');

        } else if($data->status_draft_pr == 'draft'){
            $product = PrProduct::join('tb_pr_product_draft', 'tb_pr_product_draft.id_product', '=', 'tb_pr_product.id')
                ->join('tb_pr_draft', 'tb_pr_draft.id', '=', 'tb_pr_product_draft.id_draft_pr')
                ->select('name_product', 'qty', 'unit', 'tb_pr_product.description', 'nominal_product', 'grand_total', DB::raw("(CASE WHEN (serial_number is null) THEN '-' ELSE serial_number END) as serial_number"), DB::raw("(CASE WHEN (part_number is null) THEN '-' ELSE part_number END) as part_number"))
                ->where('tb_pr_product_draft.id_draft_pr', $no_pr)
                ->orderBy('tb_pr_product_draft.id_product', 'asc')->get();

            $sum_nominal = PrProduct::join('tb_pr_product_draft', 'tb_pr_product_draft.id_product', '=', 'tb_pr_product.id')
                ->join('tb_pr_draft', 'tb_pr_draft.id', '=', 'tb_pr_product_draft.id_draft_pr')
                ->select('grand_total')->where('tb_pr_product_draft.id_draft_pr', $no_pr)->sum('grand_total');
        }

        if ($data->discount != 'false') {
            $amount_discount = round($sum_nominal * ($data->discount_nominal))/100;

            $sum_nominal_subtracted = $sum_nominal - $amount_discount;
        } else {
            $sum_nominal_subtracted = $sum_nominal;
            $amount_discount = 0;
        }

        if ($data->status_tax === '1.1') {
            $amount_tax = round(($sum_nominal_subtracted * 11) / 1000);
        } elseif ($data->status_tax === '1.2') {
            $amount_tax = round(($sum_nominal_subtracted * 12) / 1000);
        } elseif ($data->status_tax === '11') {
            $amount_tax = round(($sum_nominal_subtracted * 11) / 100);
        } elseif ($data->status_tax === '12') {
            $amount_tax = round(($sum_nominal_subtracted * 12) / 100);
        } else {
            $amount_tax = 0;
        }

        $tax_base_other = round($sum_nominal_subtracted * 11/12);

        $amount_pb = $data->tax_pb !== 'false' ? round($sum_nominal_subtracted * $data->tax_pb / 100) : 0;
        $amount_service_charge = $data->service_charge !== 'false' ? round($sum_nominal_subtracted * $data->service_charge / 100) : 0;

        $grand_total = $sum_nominal_subtracted + $amount_tax + $amount_pb + $amount_service_charge;

        $sign = $this->getSignStatusPR($no_pr,'circular');
        // return $sign;

        // $data->term_payment = "<b>Terms & Condition :</b> <br>" . $data->term_payment;

        $pdf = PDF::loadView('pdf_pr_unheader', compact('data', 'product','sign', 'sum_nominal', 'sum_nominal_subtracted', 'amount_tax', 'cek_role','grand_total','amount_pb','amount_service_charge','amount_discount','tax_base_other'));
        $fileName =   $data->no_pr  . ' ' . $data->title  . '.pdf';
        $nameFileFix = str_replace(' ', '_', $fileName);

        return $pdf->stream($nameFileFix);
    }

    public function uploadPdf($no_pr)
    {
        $client = $this->getClient();
        $service = new Google_Service_Drive($client);

        $data = PR::join('tb_pr_draft', 'tb_pr_draft.id', '=', 'tb_pr.id_draft_pr')->select('parent_id_drive', 'no_pr', 'tb_pr.title as title')->where('tb_pr.id_draft_pr', $no_pr)->first();

        $parent_id = explode('"', $data->parent_id_drive)[1];
        $fileName =   $data->no_pr . ' ' . $data->title . ' Only PDF PR.pdf';
        $nameFileFix = str_replace(' ', '_', $fileName);

        if(isset($fileName)){
            // $pdf_url = urldecode(url("/admin/getPdf?no_pr=" . $no_pr));
            $pdf_url = $this->getPdf($no_pr);
            $pdf_name = $nameFileFix;
        } else {
            $pdf_url = 'http://test-drive.sinergy.co.id:8000/Lampiran.pdf';
            $pdf_name = 'pdf_lampiran';
        }

        $parent = [];
        array_push($parent,$parent_id);

        $file = new Google_Service_Drive_DriveFile();
        $file->setName($pdf_name);
        $file->setParents($parent);

        $result = $service->files->create(
            $file,
            array(
                // 'data' => file_get_contents($pdf_url, false, stream_context_create(["ssl" => ["verify_peer"=>false, "verify_peer_name"=>false],"http" => ['protocol_version'=>'1.1']])),
                'data' => $pdf_url,
                'mimeType' => 'application/octet-stream',
                'uploadType' => 'multipart',
                'supportsAllDrives' => true
            )
        );
    }

    public function uploadPdfMerge($no_pr,$approver,$status)
    {
        try{
            $client = $this->getClient();
            $service = new Google_Service_Drive($client);

            $data = PR::join('tb_pr_draft', 'tb_pr_draft.id', '=', 'tb_pr.id_draft_pr')->select('parent_id_drive', 'no_pr', 'tb_pr.title as title')->where('tb_pr.id_draft_pr', $no_pr)->first();

            $parent_id = explode('"', $data->parent_id_drive)[1];
            $fileName =   $data->no_pr . ' ' . $data->title . ' ' . $approver . '.pdf';
            $nameFileFix = str_replace(' ', '_', $fileName);

            if(isset($fileName)){
                $pdf_url = urldecode(url("/admin/mergePdf?no_pr=" . $no_pr));
                $pdf_name = $nameFileFix;
            } else {
                $pdf_url = 'http://test-drive.sinergy.co.id:8000/Lampiran.pdf';
                $pdf_name = 'pdf_lampiran';
            }

            $parent = [];
            array_push($parent,$parent_id);

            $file = new Google_Service_Drive_DriveFile();
            $file->setName($pdf_name);
            $file->setParents($parent);

            $responseContent = @file_get_contents($pdf_url, false, stream_context_create(["ssl" => ["verify_peer"=>false, "verify_peer_name"=>false],"http" => ['protocol_version'=>'1.1']]));

            if ($responseContent === false) {
                $data = ['no_pr' => $no_pr];
                $request = Request::create('/mergePdf', 'GET', $data);
                $responsePdf = $this->mergePdf($request);

                throw new Exception($responsePdf);
            }else{
                $result = $service->files->create(
                    $file,
                    array(
                        'data' => file_get_contents($pdf_url, false, stream_context_create(["ssl" => ["verify_peer"=>false, "verify_peer_name"=>false],"http" => ['protocol_version'=>'1.1']])),
                        'mimeType' => 'application/octet-stream',
                        'uploadType' => 'multipart',
                        'supportsAllDrives' => true
                    )
                );

                http_response_code(200); // Set HTTP status code
                return json_encode([
                    'status' => 'success',
                    'message' => 'PDF successfully merged!'
                ]);
                exit;
            }
        }catch (Exception $e) {
            $parts = explode("\r\n\r\n", $e->getMessage(), 2); // Split at the first blank line
            $body = isset($parts[1]) ? $parts[1] : '';

            // Decode the JSON body to extract the message
            $data = json_decode($body, true);

            // Check if decoding was successful and retrieve the message
            if (isset($data['message'])) {
                $error = $data['message']; // Output the message
            } else {
                $error = "Message not found!";
            }
            // Catch the exception and return a 500 response
            http_response_code(500); // Set HTTP status code
            echo json_encode([
                'status' => 'error',
                'message' => $error
            ]);
            exit;
        }
    }

    private function generateFileName($data, $approver)
    {
        return str_replace(' ', '_', $data->no_pr . ' ' . $data->title . ' ' . $approver . '.pdf');
    }

    private function downloadPdfContent($pdf_url)
    {
        try {
            $client = new Client([
                'verify' => false,
            ]);

            $response = $client->get($pdf_url);

            if ($response->getStatusCode() === 200) {
                return $response->getBody()->getContents();
            }

            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    private function uploadToGoogleDrive($service, $file, $pdf_url)
    {
        $response = $service->files->create(
            $file,
            [
                'data' => file_get_contents($pdf_url),
                'mimeType' => 'application/octet-stream',
                'uploadType' => 'multipart',
                'supportsAllDrives' => true,
            ]
        );

        if (!$response) {
            throw new Exception('Failed to upload file to Google Drive.');
        }

        return $response;
    }

    public function getOnlyPdfPRFromLink($no_pr){
        $draft = PRDraft::where('id',$no_pr)->first();

        $client = $this->getClient();
        $service = new Google_Service_Drive($client);

        $optParams = array(
            'fields' => 'files(name,webViewLink, webContentLink)',
            'q' => 'mimeType="application/pdf" and "' . explode('"',$draft->parent_id_drive)[1] . '" in parents',
            // 'q' => 'name="'.explode("/", $dokumen->dokumen_location)[1].'" and "' . explode('"',$draft->parent_id_drive)[1] . '" in parents',
            // 'q' => 'name="'.$fileName.'"',
            'supportsAllDrives' => true,
            'includeItemsFromAllDrives' => true
        );

        // return $link = $service->files->listFiles($optParams)->getFiles()[1]->getWebViewLink();
        $link = "-";
        foreach($service->files->listFiles($optParams)->getFiles() as $key => $doc){
            if(preg_match("(Only_PDF_PR)", $doc->name) && $link == "-"){
                $link = $service->files->listFiles($optParams)->getFiles()[$key]->getWebContentLink();
            }
        }

        return $link;
        // return redirect($link);
    }

    public function getPdfPRFromLink(Request $req){

        $draft = PRDraft::where('id',$req->no_pr)->first();

        $client = $this->getClient();
        $service = new Google_Service_Drive($client);

        $optParams = array(
            'fields' => 'files(name,webViewLink)',
            'q' => 'mimeType="application/pdf" and "' . explode('"',$draft->parent_id_drive)[1] . '" in parents',
            'supportsAllDrives' => true,
            'includeItemsFromAllDrives' => true
        );

        // $link = $service->files->listFiles($optParams)->getFiles()[0]->getWebViewLink();
        $link = "-";
        foreach($service->files->listFiles($optParams)->getFiles() as $key => $doc){
            if(preg_match("(\d\d\d\d\/)", $doc->name) && $link == "-"){
                $link = $service->files->listFiles($optParams)->getFiles()[$key]->getWebViewLink();
            }
        }

        // return $link;
        return redirect($link);
    }

    public function getSignStatusPR($no_pr,$status){
        $data = PRDraft::where('id',$no_pr)->first();

        $territory = DB::table('users')
            ->select('id_territory')
            ->where('nik', $data->issuance)
            ->first()
            ->id_territory;

        $cek_group = PRDraft::join('role_user', 'role_user.user_id', '=', 'tb_pr_draft.issuance')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('roles.name', 'roles.group')->where('tb_pr_draft.id', $no_pr)
            ->first();

        $unapproved = DB::table('tb_pr_activity')
            ->where('tb_pr_activity.id_draft_pr', $no_pr)
            ->where('tb_pr_activity.status', "UNAPPROVED")
            ->orderBy('tb_pr_activity.id',"DESC")
            ->get();

        $tb_pr_activity = DB::table('tb_pr_activity')
            ->where('tb_pr_activity.id_draft_pr', $no_pr);

        if(count($unapproved) != 0){
            $tb_pr_activity->where('tb_pr_activity.id','>',$unapproved->first()->id);
        }

        $tb_pr_activity->where(function($query){
            $query->where('tb_pr_activity.status', 'CIRCULAR')
                ->orWhere('tb_pr_activity.status', 'FINALIZED');
        });

        $sign = User::join('role_user', 'role_user.user_id', '=', 'users.nik')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select(
                'users.name',
                'roles.name as position',
                'ttd',
                'email',
                'avatar',
                DB::raw("IFNULL(SUBSTR(`temp_tb_pr_activity`.`date_time`,1,10),'-') AS `date_sign`"),
                DB::raw('IF(ISNULL(`temp_tb_pr_activity`.`date_time`),"false","true") AS `signed`')
            )
            ->leftJoinSub($tb_pr_activity,'temp_tb_pr_activity',function($join){
                // $join->on("temp_tb_pr_activity.operator","=","users.name");
                $join->on("users.name","LIKE",DB::raw("CONCAT('%', temp_tb_pr_activity.operator, '%')"));
            })
            ->where('id_company', '1')
            ->where('status_karyawan','!=','dummy');

        // return $sign->get();

        if ($data->type_of_letter == 'EPR') {
            if ($data->category == 'Bank Garansi') {
                foreach ($sign->get() as $key => $value) {
                    if ($value->name == 'Endraw Denny Hermanto' && $value->signed == 'true') {
                        $sign->whereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'BCD' OR `users`.`id_position` = 'MANAGER' AND `users`.`id_territory` = '" . $territory . "' OR  `users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER')")
                            ->orderByRaw('FIELD(position, "BCD Manager", "VP Sales", "Chief Operating Officer")');
                    } else {
                        $sign->whereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'MSM' OR `users`.`id_position` = 'MANAGER' AND `users`.`id_territory` = '" . $territory . "' OR  `users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER')")
                            ->orderByRaw('FIELD(position, "VP Internal Chain Management", "VP Sales", "Chief Operating Officer")');
                    }
                }

                // $sign->whereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'BCD' OR `users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'MSM' OR `users`.`id_position` = 'MANAGER' AND `users`.`id_territory` = '" . $territory . "' OR  `users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER')")
                // ->orderByRaw('FIELD(position, "VP Internal Chain Management", "VP Sales", "Chief Operating Officer")');
            } else {
                foreach ($sign->get() as $key => $value) {
                    if ($value->name == 'Endraw Denny Hermanto' && $value->signed == 'true') {
                        $sign->whereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'BCD' OR `users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER' OR `users`.`id_division` = 'TECHNICAL PRESALES' AND `users`.`id_position` = 'MANAGER' OR `users`.`id_division` = 'PMO' AND `users`.`id_position` = 'MANAGER')")
                            ->orderByRaw('FIELD(position, "BCD Manager", "VP Program & Project Management", "VP Solutions & Partnership Management", "Chief Operating Officer")');
                    } else {
                        $sign->whereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'MSM' OR `users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER' OR `users`.`id_division` = 'TECHNICAL PRESALES' AND `users`.`id_position` = 'MANAGER' OR `users`.`id_division` = 'PMO' AND `users`.`id_position` = 'MANAGER')")
                            ->orderByRaw('FIELD(position, "VP Internal Chain Management", "VP Program & Project Management", "VP Solutions & Partnership Management", "Chief Operating Officer")');
                    }
                }

                // $sign->whereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'BCD' OR `users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER' OR `users`.`id_division` = 'TECHNICAL PRESALES' AND `users`.`id_position` = 'MANAGER' OR `users`.`id_division` = 'PMO' AND `users`.`id_position` = 'MANAGER')")
                // ->orderByRaw('FIELD(position, "BCD Manager", "VP Program & Project Management", "VP Solutions & Partnership Management", "Chief Operating Officer")');
            }
        } else {
            if ($cek_group->group == 'Program & Project Management') {

                foreach ($sign->get() as $key => $value) {
                    if ($value->name == 'Endraw Denny Hermanto' && $value->signed == 'true') {
                        $sign->whereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'BCD' OR `users`.`id_division` = 'PMO' AND `users`.`id_position` = 'MANAGER' OR `users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER')")
                            ->orderByRaw('FIELD(position, "BCD Manager", "VP Program & Project Management", "Chief Operating Officer")');
                    } else {
                        $sign->whereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'MSM' OR `users`.`id_division` = 'PMO' AND `users`.`id_position` = 'MANAGER' OR `users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER')")
                            ->orderByRaw('FIELD(position, "VP Internal Chain Management", "VP Program & Project Management", "Chief Operating Officer")');
                    }
                }

                // $sign->whereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'BCD' OR `users`.`id_division` = 'PMO' AND `users`.`id_position` = 'MANAGER' OR `users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'MSM' OR `users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER')")
                // ->orderByRaw('FIELD(position, "VP Internal Chain Management", "VP Program & Project Management", "Chief Operating Officer")');

            }
            elseif ($cek_group->group == 'Internal Chain Management') {

                foreach ($sign->get() as $key => $value) {
                    if ($value->name == 'Endraw Denny Hermanto' && $value->signed == 'true') {
                        $sign->whereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'BCD' OR `users`.`id_division` = 'MSM' AND `users`.`id_position` = 'MANAGER' OR `users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER')")
                            ->orderByRaw('FIELD(position, "BCD Manager", "Chief Operating Officer")');
                    } else {
                        $sign->whereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'MSM' OR `users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER')")
                            ->orderByRaw('FIELD(position, "VP Internal Chain Management", "Chief Operating Officer")');
                    }
                }

                // $sign->whereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'BCD' OR `users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'MSM' OR `users`.`id_division` = 'MSM' AND `users`.`id_position` = 'MANAGER' OR `users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER')")
                // ->orderByRaw('FIELD(position, "VP Internal Chain Management", "Chief Operating Officer")');

            }elseif ($cek_group->group == 'Solutions & Partnership Management') {

                foreach ($sign->get() as $key => $value) {
                    if ($value->name == 'Endraw Denny Hermanto' && $value->signed == 'true') {
                        $sign->whereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'BCD' OR `users`.`id_division` = 'TECHNICAL PRESALES' AND `users`.`id_position` = 'MANAGER' OR `users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER')")
                            ->orderByRaw('FIELD(position, "BCD Manager", "VP Solutions & Partnership Management", "Chief Operating Officer")');
                    } else {
                        $sign->whereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'MSM' OR `users`.`id_division` = 'TECHNICAL PRESALES' AND `users`.`id_position` = 'MANAGER' OR `users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER')")
                            ->orderByRaw('FIELD(position, "VP Internal Chain Management", "VP Solutions & Partnership Management", "Chief Operating Officer")');
                    }
                }

                // $sign->whereRaw("( `users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'MSM' OR `users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER')")
                // ->orderByRaw('FIELD(position, "VP Internal Chain Management", "BCD Manager","Chief Operating Officer")');

            } elseif ($cek_group->group == 'Synergy System Management') {
                foreach ($sign->get() as $key => $value) {
                    if ($value->name == 'Endraw Denny Hermanto' && $value->signed == 'true') {
                        $sign->whereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'BCD' OR `users`.`id_position` = 'ENGINEER MANAGER' OR `users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER')")
                            ->orderByRaw('FIELD(position, "BCD Manager", "VP Synergy System Management","Chief Operating Officer")');
                    } else {
                        $sign->whereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'MSM' OR `users`.`id_position` = 'ENGINEER MANAGER' OR `users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER')")
                            ->orderByRaw('FIELD(position, "VP Internal Chain Management", "VP Synergy System Management","Chief Operating Officer")');
                    }
                }
                // $sign->whereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'BCD' OR `users`.`id_position` = 'ENGINEER MANAGER' OR `users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER')")
                // ->orderByRaw('FIELD(position, "VP Internal Chain Management", "VP Synergy System Management","Chief Operating Officer")');

            } elseif ($cek_group->group == 'Human Capital Management') {
                foreach ($sign->get() as $key => $value) {
                    if ($value->name == 'Endraw Denny Hermanto' && $value->signed == 'true') {
                        $sign->whereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'BCD' OR `roles`.`name` = 'VP Human Capital Management' OR  `users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER')")
                            ->orderByRaw('FIELD(position, "BCD Manager", "VP Human Capital Management", "Chief Operating Officer")');
                    } else {
                        $sign->whereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'MSM' OR `roles`.`name` = 'VP Human Capital Management' OR  `users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER')")
                            ->orderByRaw('FIELD(position, "VP Internal Chain Management", "VP Human Capital Management", "Chief Operating Officer")');
                    }
                }
                // $sign->whereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'BCD' OR `roles`.`name` = 'VP Human Capital Management' OR  `users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER')")
                // ->orderByRaw('FIELD(position, "VP Internal Chain Management", "VP Human Capital Management", "Chief Operating Officer")');

            } elseif ($cek_group->group == 'Sales') {
                foreach ($sign->get() as $key => $value) {
                    if ($value->name == 'Endraw Denny Hermanto' && $value->signed == 'true') {
                        $sign->whereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'BCD' OR `users`.`id_position` = 'MANAGER' AND `users`.`id_territory` = '" . $territory . "' OR  `users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER')")
                            ->orderByRaw('FIELD(position, "BCD Manager", "VP Sales", "Chief Operating Officer")');
                    } else {
                        $sign->whereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'MSM' OR `users`.`id_position` = 'MANAGER' AND `users`.`id_territory` = '" . $territory . "' OR  `users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER')")
                            ->orderByRaw('FIELD(position, "VP Internal Chain Management", "VP Sales", "Chief Operating Officer")');
                    }
                }
                // $sign->whereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'BCD' OR `users`.`id_position` = 'MANAGER' AND `users`.`id_territory` = '" . $territory . "' OR  `users`.`id_division` = 'TECHNICAL' AND `users`.`id_position` = 'MANAGER')")
                // ->orderByRaw('FIELD(position, "VP Internal Chain Management", "VP Sales", "Chief Operating Officer")');
            }
        }

        if ($status == 'circular') {
            return $sign->get();
        } else{
            return $sign->get()->where('signed','false')->first()->name;
        }
    }

    public function mergePdf(Request $request)
    {
        $images = PRDocumentDraft::join('tb_pr_document', 'tb_pr_document.id', '=', 'tb_pr_document_draft.id_document')
            ->select('dokumen_location')
            ->where('id_draft_pr', $request->no_pr)
            ->where(function($query){
                $query->where('dokumen_location', 'like' , '%png')
                    ->orWhere('dokumen_location', 'like', '%PNG')
                    ->orWhere('dokumen_location', 'like', '%jpg')
                    ->orWhere('dokumen_location', 'like', '%JPG')
                    ->orWhere('dokumen_location', 'like', '%jpeg');
            })->get()->pluck('dokumen_location');

        $draft = PRDraft::where('id',$request->no_pr)->first();

        $client = $this->getClient();
        $service = new Google_Service_Drive($client);

        $pdf_url = urldecode(url("/admin/getPdf?no_pr=" . $request->no_pr));
        $pdf_file = $this->getPdf($request->no_pr);
        // $pdf_file = file_get_contents($pdf_url, 'rb', stream_context_create(["ssl" => ["verify_peer"=>false, "verify_peer_name"=>false],"http" => ['protocol_version'=>'1.1']]));
        // $pdf_file = $pdf_url;

        $data = PR::join('tb_pr_draft', 'tb_pr_draft.id', '=', 'tb_pr.id_draft_pr')
            ->select('status_draft_pr', 'no_pr', 'tb_pr.title', 'tb_pr_draft.type_of_letter')
            ->where('tb_pr.id_draft_pr', $request->no_pr)->first();

        if ($data->status_draft_pr == 'draft') {
            $dokumen = PrDokumen::join('tb_pr_document_draft', 'tb_pr_document_draft.id_document', '=', 'tb_pr_document.id')
                ->join('tb_pr_draft', 'tb_pr_draft.id', 'tb_pr_document_draft.id_draft_pr')
                ->select('dokumen_name', 'dokumen_location', 'link_drive', 'tb_pr_document.id as id_dokumen', 'id_draft_pr')
                ->where('tb_pr_document_draft.id_draft_pr', $request->no_pr);

            if ($data->type_of_letter == 'IPR') {
                $get_id_max = DB::table($dokumen, 'temp')->groupBy('dokumen_name')->selectRaw('MAX(`temp`.`id_dokumen`) as `id_dokumen`, MAX(`temp`.`id_draft_pr`) as `id_draft_pr`');
                $getAll = DB::table($get_id_max, 'temp2')->join('tb_pr_document', 'tb_pr_document.id', '=', 'temp2.id_dokumen')->select('dokumen_name', 'dokumen_location', 'temp2.id_dokumen', 'link_drive', 'id_draft_pr')->orderBy('id','desc')->get();
            } else {
                $get_id_max = DB::table($dokumen, 'temp')->groupBy('dokumen_name')->selectRaw('MAX(`temp`.`id_dokumen`) as `id_dokumen`, MAX(`temp`.`id_draft_pr`) as `id_draft_pr`');
                $getAll = DB::table($get_id_max, 'temp2')->join('tb_pr_document', 'tb_pr_document.id', '=', 'temp2.id_dokumen')->select('dokumen_name', 'dokumen_location', 'temp2.id_dokumen', 'link_drive', 'id_draft_pr')->orderBy('created_at', 'asc')->get();
            }
        } else if ($data->status_draft_pr == 'pembanding'){
            $dokumen = PrDokumen::join('tb_pr_document_compare', 'tb_pr_document_compare.id_document', '=', 'tb_pr_document.id')
                ->join('tb_pr_compare', 'tb_pr_compare.id', 'tb_pr_document_compare.id_compare_pr')
                ->select('dokumen_name', 'dokumen_location', 'link_drive', 'tb_pr_document.id as id_dokumen', 'id_draft_pr')
                ->where('tb_pr_compare.id_draft_pr', $request->no_pr)->where('status','Selected');

            if ($data->type_of_letter == 'IPR') {

                $getDokumen = PrDokumen::join('tb_pr_document_compare', 'tb_pr_document_compare.id_document', '=', 'tb_pr_document.id')
                    ->join('tb_pr_compare', 'tb_pr_compare.id', 'tb_pr_document_compare.id_compare_pr')
                    ->select('dokumen_name', 'dokumen_location', 'link_drive', 'tb_pr_document.id as id_dokumen')
                    ->where('tb_pr_compare.id_draft_pr', $request->no_pr)
                    ->where(function($query){
                        $query->where('dokumen_name', '!=', 'Penawaran Harga');
                    })
                    ->get();

                $get_id_max = DB::table($dokumen, 'temp')->groupBy('dokumen_name')->selectRaw('MAX(`temp`.`id_dokumen`) as `id_dokumen`, MAX(`temp`.`id_draft_pr`) as `id_draft_pr`');
                $getAll = DB::table($get_id_max, 'temp2')->join('tb_pr_document', 'tb_pr_document.id', '=', 'temp2.id_dokumen')->select('dokumen_name', 'dokumen_location', 'temp2.id_dokumen', 'link_drive', 'id_draft_pr')->orderBy('id','desc')->get();

                $getAll = $getAll;
            } else {

                $getDokumen = DB::table('tb_pr_document')->join('tb_pr_document_draft', 'tb_pr_document_draft.id_document', '=', 'tb_pr_document.id')
                    ->join('tb_pr_draft', 'tb_pr_draft.id', 'tb_pr_document_draft.id_draft_pr')
                    ->select('dokumen_name', 'dokumen_location', 'link_drive', 'id_draft_pr')
                    ->where('tb_pr_document_draft.id_draft_pr', $request->no_pr)
                    ->where(function($query){
                        $query->where('dokumen_name', '!=','Quote Supplier');
                    })
                    ->get();

                $get_id_max = DB::table($dokumen, 'temp')->groupBy('dokumen_name')->selectRaw('MAX(`temp`.`id_dokumen`) as `id_dokumen`, MAX(`temp`.`id_draft_pr`) as `id_draft_pr`');
                $getAll = DB::table($get_id_max, 'temp2')->join('tb_pr_document', 'tb_pr_document.id', '=', 'temp2.id_dokumen')->select('dokumen_name', 'dokumen_location', 'temp2.id_dokumen', 'link_drive', 'id_draft_pr')->orderBy('created_at', 'asc')->get();

                $getAll = array_merge($getAll->toArray(),$getDokumen->toArray());
            }


        }

        // return $getAll;
        $pdf = new Fpdi();
        // $pageCount =  $pdf->setSourceFile(StreamReader::createByString(file_get_contents($pdf_file,'rb')));
        $pageCount =  $pdf->setSourceFile(StreamReader::createByString($pdf_file));

        for ($i=0; $i < $pageCount; $i++) {
            //create a page
            $pdf->AddPage();
            // //import a page then get the id and will be used in the template
            $tplId = $pdf->importPage($i+1, '/MediaBox');
            // //use the template of the imporated page
            $pdf->useTemplate($tplId, 0, 0, 200);
        }

        if ($getAll != null) {
            foreach ($getAll as $dokumen) {
                $directory = "draft_pr/";
                // return explode("/", $dokumen->dokumen_location)[1];
                $optParams = array(
                    'fields' => 'files(name,webViewLink,webContentLink,mimeType,modifiedTime)',
                    'q' => 'name="'.explode("/", $dokumen->dokumen_location)[1].'" and "' . explode('"',$draft->parent_id_drive)[1] . '" in parents',
                    // 'q' => 'name="'.explode("/", 'draft_pr/1679_spk.pdf')[1].'" and "' . explode('"','["18jq_dMPY1PTBGgys1LsMs-okD9RWtsos"]')[1] . '" in parents',
                    'supportsAllDrives' => true,
                    'includeItemsFromAllDrives' => true
                );

                // return $optParams;
                // return $service->files->listFiles($optParams)->getFiles();
                // var_dump($service->files->listFiles($optParams)->getFiles());
                $dokumen_file = $service->files->listFiles($optParams)->getFiles()[0]->getWebContentLink();
                // $downloadPdf = $this->downloadToLocal($dokumen_file,$directory,explode("/", $dokumen->dokumen_location)[1]);

                if (explode(".", $dokumen->dokumen_location)[1] == 'pdf') {
                    $context = stream_context_create(
                        array(
                            "http" => array(
                                "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
                            )
                        )
                    );

                    // $page = $pdf->setSourceFile(StreamReader::createByString(file_get_contents($dokumen_file, false, $context)));
                    set_error_handler([$this, 'handleError'], E_WARNING);

                    // $responseDokumenFile = file_get_contents($dokumen_file, false, $context);
                    // $streamReader = StreamReader::createByString($responseDokumenFile);
                    // $page = $pdf->setSourceFile($streamReader);
                    try {
                        $page = $pdf->setSourceFile(StreamReader::createByString(file_get_contents($dokumen_file, false, $context)));
                        // $this->addPdfPages($pdf, $streamReader);
                    } catch (Exception $e) {
                        if (stripos($e->getMessage(),'This PDF document probably uses a compression technique which is not supported by') !== false) {
                            $error = 'Document '. $dokumen->dokumen_name .' probably uses a compression technique which is not supported, please reject PR and notify user to update document!';
                        }else{
                            $error = $e->getMessage();
                        }
                        // Catch the exception thrown by our custom error handler
                        return response()->json([
                            'error' => 'error',
                            'message' => $error
                        ], 500);
                    }

                    restore_error_handler();


                    for ($i=0; $i < $page; $i++) {
                        // import a page then get the id and will be used in the template
                        $tplId = $pdf->importPage($i+1, '/MediaBox');

                        $size = $pdf->getTemplateSize($tplId);

                        if ($size['width'] > $size['height']) {
                            $pdf->AddPage('L', array($size['width'], $size['height']));
                        } else {
                            $pdf->AddPage('P', array($size['width'], $size['height']));
                        }
                        // use the template of the imporated page
                        $pdf->useTemplate($tplId);
                    }
                } else{
                    // echo $dokumen_file . '<br>';

                    $context = stream_context_create(
                        array(
                            "http" => array(
                                "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
                            )
                        )
                    );

                    list($width,$height) = getimagesize($dokumen_file);
                    if ($width > $height) {
                        $pdf->AddPage('L');
                        $pdf->MemImage(file_get_contents($dokumen_file, false, $context),50, 50, 100, '', '', 'http://www.tcpdf.org', '', false, 300);
                    } else {
                        $pdf->AddPage('P');
                        $pdf->MemImage(file_get_contents($dokumen_file, false, $context),50, 50, 100, '', '', 'http://www.tcpdf.org', '', false, 300);
                    }
                }
            }

            $dokumen_revisi = $this->getOnlyPdfPRFromLink($dokumen->id_draft_pr,'revisi');
            // return $dokumen_revisi;
            if (strpos($data->title, 'Revisi') && $dokumen_revisi != '-') {
                // return 'true';

                $context = stream_context_create(
                    array(
                        "http" => array(
                            "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
                        )
                    )
                );

                $page_revisi =  $pdf->setSourceFile(StreamReader::createByString(file_get_contents($dokumen_revisi, false, $context)));
                // for ($i=0; $i < $page; $i++) {
                $tplId = $pdf->importPage(1, '/MediaBox');
                $size = $pdf->getTemplateSize($tplId);

                if ($size['width'] > $size['height']) {
                    $pdf->AddPage('L', array($size['width'], $size['height']));
                } else {
                    $pdf->AddPage('P', array($size['width'], $size['height']));
                }
                // use the template of the imporated page
                $pdf->useTemplate($tplId);
                $pdf->SetTitle('Revisi PR');
                // }
            }

        }

        //return the generated PDF
        // return $pdf->Output();
        $fileName =   $data->no_pr  . ' ' . $data->title  . '.pdf';
        $nameFileFix = str_replace(' ', '_', $fileName);
        $pdfContent = $pdf->Output('S');

        $driveFile = new Google_Service_Drive_DriveFile();
        $driveFile->setName($nameFileFix);
        $driveFile->setMimeType('application/pdf');

        // Upload the file
        $uploadedFile = $service->files->create($driveFile, [
            'data' => $pdfContent,
            'mimeType' => 'application/pdf',
            'uploadType' => 'multipart'
        ]);

        try {
            $permission = new Google_Service_Drive_Permission();
            $permission->setType('domain');
            $permission->setRole('reader');
            $permission->setDomain('sinergy.co.id');

            $response = $service->permissions->create($uploadedFile->getId(), $permission);

            Log::info('Response:', ['response' => $response]);

        } catch (Exception $e) {
            Log::error('Error response:', ['error' => $e->getMessage()]);
        }

        return $pdf->Output("D");
    }

    public function mergePdfPr(Request $request)
    {
        $noPr = $request->no_pr;

        $draft = PRDraft::find($noPr);
        $data = $this->getDraftData($noPr);

        if (!$data) {
            return response()->json(['error' => 'No data found'], 404);
        }

        $dokumenList = $this->getDocuments($data, $noPr, $draft);

        $dokumenArray = json_decode(json_encode($dokumenList), true);

        $fileIds = $this->extractFileIds($dokumenArray);

        $downloadedFiles = [];
        foreach ($fileIds as $fileId) {
            $savePath = storage_path("app/{$fileId}.pdf");
            $this->downloadFileFromGoogleDrive($fileId, $savePath);
            $downloadedFiles[] = $savePath;
        }

        $mergedFilePath = storage_path('app/merged.pdf');

        $fileName = str_replace(' ', '_', $data->no_pr . ' ' . $data->title . '.pdf');

        $pdf_file = $this->getPdf($request->no_pr);

        $tempPdfPath = storage_path("app/{$noPr}_additional.pdf");
        file_put_contents($tempPdfPath, $pdf_file);
        $allFilesToMerge = array_merge([$tempPdfPath],$downloadedFiles);

        $this->mergePdfs($allFilesToMerge, $mergedFilePath);
    }

    private function extractFileIds(array $data)
    {
        return array_map(function ($item) {
            preg_match('/\/d\/(.*?)\//', $item['link_drive'], $matches);
            return $matches[1] ?? null;
        }, $data);
    }

    private function downloadFileFromGoogleDrive($fileId, $savePath)
    {
        $client = $this->getClient();
        $service = new Google_Service_Drive($client);

        $response = $service->files->get($fileId, [
            'alt' => 'media'
        ]);

        $content = $response->getBody()->getContents();

        if ($response->getStatusCode() === 200) {
            file_put_contents($savePath, $response->getBody());
        }
    }

    public function getGoogleDriveFile($fileId)
    {
        $client = $this->getClient();
        $service = new Google_Service_Drive($client);

        try {
            $file = $service->files->get($fileId, [
                'alt' => 'media'
            ]);

            return $file->getBody()->getContents();
        } catch (Exception $e) {
            Log::error('Error fetching file from Google Drive:', ['error' => $e->getMessage()]);
            return null;
        }
    }

    private function mergePdfs(array $pdfPaths, string $outputPath)
    {
        $pdf = new FPDI();

        set_error_handler([$this, 'handleError'], E_WARNING);

        // try {
        //     $page = $pdf->setSourceFile(StreamReader::createByString(file_get_contents($dokumen_file, false, $context)));
        // } catch (Exception $e) {
        //     if (stripos($e->getMessage(),'This PDF document probably uses a compression technique which is not supported by') !== false) {
        //         $error = 'Document '. $dokumen->dokumen_name .' probably uses a compression technique which is not supported, please reject PR and notify user to update document!';
        //     }else{
        //         $error = $e->getMessage();
        //     }
        //     return response()->json([
        //         'error' => 'error',
        //         'message' => $error
        //     ], 500);
        // }

        restore_error_handler();

        foreach ($pdfPaths as $file) {
            $pageCount = $pdf->setSourceFile($file);

            for ($i = 1; $i <= $pageCount; $i++) {
                $pdf->AddPage();
                $tplIdx = $pdf->importPage($i);
                $pdf->useTemplate($tplIdx);
            }
        }

        $pdf->Output($outputPath, 'D');
    }


    private function getDraftData($noPr)
    {
        return PR::join('tb_pr_draft', 'tb_pr_draft.id', '=', 'tb_pr.id_draft_pr')
            ->select('status_draft_pr', 'no_pr', 'tb_pr.title', 'tb_pr_draft.type_of_letter')
            ->where('tb_pr.id_draft_pr', $noPr)
            ->first();
    }

    private function getDocuments($data, $noPr, $draft)
    {
        $query = $this->buildDocumentQuery($data, $noPr);
        $groupedDocuments = $this->getGroupedDocuments($query);

        if ($data->status_draft_pr === 'pembanding') {
            $extraDocs = $this->getExtraDocuments($data, $noPr);
            return array_merge($groupedDocuments->toArray(), $extraDocs->toArray());
        }

        return $groupedDocuments;
    }

    private function buildDocumentQuery($data, $noPr)
    {
        $tableJoin = $data->status_draft_pr === 'draft' ? 'tb_pr_document_draft' : 'tb_pr_document_compare';

        return PrDokumen::join($tableJoin, "{$tableJoin}.id_document", '=', 'tb_pr_document.id')
            ->join('tb_pr_draft', 'tb_pr_draft.id', "{$tableJoin}.id_draft_pr")
            ->select('dokumen_name', 'dokumen_location', 'link_drive', 'tb_pr_document.id as id_dokumen', 'id_draft_pr')
            ->where("{$tableJoin}.id_draft_pr", $noPr);
    }

    private function getGroupedDocuments($query)
    {
        return DB::table($query, 'temp')
            ->groupBy('dokumen_name','dokumen_location','link_drive')
            ->selectRaw('MAX(temp.id_dokumen) as id_dokumen, MAX(temp.id_draft_pr) as id_draft_pr')->selectRaw('dokumen_name')->selectRaw('dokumen_location')->selectRaw('link_drive')
            ->get();
    }

    private function getExtraDocuments($data, $noPr)
    {
        return PrDokumen::join('tb_pr_document_compare', 'tb_pr_document_compare.id_document', '=', 'tb_pr_document.id')
            ->join('tb_pr_compare', 'tb_pr_compare.id', '=', 'tb_pr_document_compare.id_compare_pr')
            ->select('dokumen_name', 'dokumen_location', 'link_drive', 'id_draft_pr')
            ->where('tb_pr_compare.id_draft_pr', $noPr)
            ->where('dokumen_name', '!=', 'Penawaran Harga')
            ->get();
    }

    // Custom error handler to convert warnings into exceptions
    public function handleError($errno, $errstr)
    {
        // Check if the error message is related to unsupported PDF compression
        if (strpos($errstr, 'This PDF document probably uses a compression technique') !== false) {
            // Log the error for debugging
            Log::error('FPDI Compression Error: ' . $errstr);

            // Throw an exception with a custom error message
            throw new Exception('The PDF contains an unsupported compression technique: ' . $errstr);
        }

        // Let PHP handle other warnings as usual
        return false;
    }

    public function uploadCSV(Request $request){
        $directory = "draft_pr/";
        $nameFile = "test_csv_upload.xlsx";
        $folderName = 'Test Draft PR 2';

        $this->uploadToLocal($request->file('csv_file'),$directory,$nameFile);

        $result = $this->readCSV($directory . "/" . $nameFile);

        if ($result == 'Format tidak sesuai' ) {
            return 'Format tidak sesuai';
            return collect([
                "text" => 'Format tidak sesuai',
                "status" => 'Error',
            ]);
        } else if ($result == 'Tidak ada produk') {
            return collect([
                "text" => 'Format tidak sesuai',
                "status" => 'Error',
            ]);
        } else {
            if(count($result) >= 1){
                foreach ($result as $key => $value) {
                    // return preg_replace("/[^0-9]/","",substr($value[7], 0, strpos($value[7], ",")));
                    // return $value[7];
                    if (is_numeric($value[5]) && is_numeric($value[7])) {
                        $insertProduct[] = ['name_product' => $value[1], 'description' => (string)$value[2], 'serial_number' => $value[3], 'part_number' => $value[4], 'qty' => $value[5], 'unit' => $value[6], 'nominal_product' => $value[7], 'grand_total' => $value[5]*$value[7]];
                    }
                    else {
                        $insertProduct[] = ['name_product' => $value[1], 'description' => (string)$value[2], 'serial_number' => $value[3], 'part_number' => $value[4], 'qty' => $value[5], 'unit' => $value[6], 'nominal_product' => preg_replace("/[^0-9]/", "", substr($value[7], 0, strpos($value[7], ","))),
                            // 'grand_total' => $value[5]*$value[7]
                            'grand_total' => intval($value[5]) * intval(preg_replace("/[^0-9]/", "", substr($value[7], 0, strpos($value[7], ","))))
                        ];
                    }

                }

                if(!empty($insertProduct)){
                    PrProduct::insert($insertProduct);
                }

                if ($request->status == 'pembanding') {
                    $id_product = PrProduct::select('id as id_product')->limit(count($result))->orderBy('id', 'desc')->get()->sortBy('id')->map(function($item) use ($request){
                        $item->id_compare_pr = $request->no_pr;
                        $item->added = Carbon::now()->toDateTimeString();
                        return $item;
                    })->values()->toArray();
                    PRProductCompare::insert($id_product);
                } else {
                    $id_product = PrProduct::select('id as id_product')->limit(count($result))->orderBy('id', 'desc')->get()->sortBy('id')->map(function($item) use ($request){
                        $item->id_draft_pr = $request->no_pr;
                        $item->added = Carbon::now()->toDateTimeString();
                        return $item;
                    })->values()->toArray();
                    PRProductDraft::insert($id_product);
                }
            } else {
                return 'Tidak ada produk';
            }
        }


        return $result;
    }

    public function readCSV($locationFile){

        $format = array(
            "product",
            "description",
            "serial_number",
            "part_number",
            "qty",
            "type(Pcs,Unit,Lot,Pack,Node)",
            "price(non-ppn)"
        );

        if (($open = fopen($locationFile, "r")) !== FALSE) {

            $i = 0;
            $array = [];
            while (($data = fgetcsv($open, 1000, ";")) !== FALSE) {
                if($i != 0){
                    $array[] = $data;
                } else {
                    array_shift($data);
                    if (empty(!array_diff($format, $data))) {
                        return 'Format tidak sesuai';
                    }


                }
                $i++;
            }
            if ($i == 1) {
                return 'Tidak ada produk';
            }
            fclose($open);
        }

        return $array;
        // return array_shift($array);
    }

    public function getPerson(Request $request)
    {
        $requestor = DB::table('tb_pr_draft')->join('users', 'users.nik', '=', 'tb_pr_draft.issuance')->where('tb_pr_draft.id', $request->no_pr)->first();
        $getAll = User::join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('users.name', 'users.email', 'users.avatar')
            ->whereRaw("(`nik` = '".$requestor->issuance."' OR `roles`.`name` = 'Procurement & Vendor Management')")
            ->where('status_karyawan', '!=', 'dummy')
            ->get();

        $next_approver = $this->getSignStatusPR($request->no_pr, 'circular');
        $array = array_merge($getAll->toArray(), $next_approver->toArray());
        return $array;
    }

    //Disabled push notification
    /*public function getNotifBadgeInsert($json){
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
    }*/

    public function getSupplier()
    {
        // return $data = DB::table('tb_pr')->leftjoin('tb_pr_draft','tb_pr_draft.to','tb_pr.to')
        // ->unionAll(DB::table('tb_pr')
        //     ->rightJoin('tb_pr_draft', 'tb_pr.to', '=', 'tb_pr_draft.to')
        // )->select(DB::raw('`tb_pr`.`to` AS `id`,`tb_pr`.`to` AS `text`'))->whereRaw("(`tb_pr`.`to` != '' OR `tb_pr`.`to` != NULL)")->groupBy('tb_pr.to')->distinct()->get();

        $data = DB::table('tb_pr')->select(DB::raw('`tb_pr`.`to` AS `id`,`tb_pr`.`to` AS `text`'))->whereRaw("(`tb_pr`.`to` != '' OR `tb_pr`.`to` != NULL)")->whereRaw("TRIM(`tb_pr`.`to`) NOT LIKE ?", ['%Comstor%'])->groupBy('tb_pr.to');

        return $data_draft_pr = DB::table('tb_pr_draft')->select(DB::raw('`tb_pr_draft`.`to` AS `id`,`tb_pr_draft`.`to` AS `text`'))->whereRaw("(`tb_pr_draft`.`to` != '' OR `tb_pr_draft`.`to` != NULL)")->whereRaw("TRIM(`tb_pr_draft`.`to`) NOT LIKE ?", ['%Comstor%'])->groupBy('tb_pr_draft.to')->union($data)->get();
    }

    public function getPidUnion(Request $request)
    {
        $pid = SalesProject::join('sales_lead_register', 'sales_lead_register.lead_id', '=', 'tb_id_project.lead_id')
            ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
            ->select(DB::raw('`tb_id_project`.`id_project` AS `id`,`tb_id_project`.`id_project` AS `text`'))
            ->where('id_company', '1')->orderBy('tb_id_project.id_project','desc')
            ->groupBy('id_project');

        $pid_pr = DB::table('tb_pr_draft')->select(DB::raw('`tb_pr_draft`.`pid` AS `id`,`tb_pr_draft`.`pid` AS `text`'))->where('pid','!=',null)->groupBy('tb_pr_draft.pid')->union($pid)->get();

        return array("data" => $pid_pr);
    }

    public function getSupplierDetail(Request $request)
    {
        $data = DB::table('tb_pr')->select('email','phone','address')
            ->where('to',$request->to)
            ->where('address','!=',NULL)
            ->where('phone','!=',NULL)
            ->where('email','!=',NULL);

        return $data_draft_pr = DB::table('tb_pr_draft')->select('email','phone','address')
            ->where('to',$request->to)
            ->where('address','!=',NULL)
            ->where('phone','!=',NULL)
            ->where('email','!=',NULL)
            ->union($data)->get();
    }
   
    public function getUserOperasional()
    {
        $data = User::select(DB::raw('`users`.`nik` AS `id`,`users`.`name` AS `text`'))->join('role_user','users.nik','=','role_user.user_id')
            ->join('roles','role_user.role_id','=','roles.id')
            ->where('roles.group','<>','sales')
            ->where('roles.group','<>','Human Capital Management')
            ->where('roles.group','<>','default')
            ->where('roles.group','<>','director')
            ->where('roles.group','<>','finance')
            ->where('users.name','like','%'.request('q').'%')->get();

        return $data;
    }

    // protected function setFileRestrictions(Google_Service_Drive $service, $fileId)
    // {
    //     // Define the domain you want to restrict access to
    //     $domain = 'sinergy.co.id';

    //     // Create a new restrictions object
    //     $restrictions = new Google_Service_Drive_DriveRestrictions();
    //     $restrictions->setDomain($domain);

    //     // Retrieve the file metadata
    //     $file = $service->files->get($fileId);
    //     $file->setRestrictions($restrictions);

    //     // Update the file with the new restrictions
    //     $service->files->update($fileId, $file);
    // }
}