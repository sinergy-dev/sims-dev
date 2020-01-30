<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use App\PONumberMSP;
use App\POAssetMSP;
use App\pam_msp;
use App\pam_produk_msp;
use App\pam_progress_msp;
use PDF;
use Excel;
use App\PR_MSP;

class POAssetMSPController extends Controller
{
    public function index(Request $request)
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        /*$pam = DB::table('tb_pam_msp')
            ->join('users','users.nik','=','tb_pam_msp.personel')
            ->join('tb_pr_msp','tb_pr_msp.no','=','tb_pam_msp.no_pr')
            ->select('tb_pam_msp.id_pam','tb_pam_msp.date_handover','tb_pr_msp.no_pr','tb_pam_msp.ket_pr','tb_pam_msp.note_pr','tb_pam_msp.to_agen','tb_pam_msp.status','users.name','tb_pam_msp.subject', 'tb_pr_msp.no', 'tb_pr_msp.date', 'tb_pam_msp.attention', 'tb_pam_msp.project', 'tb_pam_msp.project_id', 'ppn', 'terms')
            ->get();*/

        $pam = DB::table('tb_po_asset_msp')
                ->join('users', 'users.nik', '=', 'tb_po_asset_msp.nik_admin')
                ->join('tb_po_msp', 'tb_po_asset_msp.no_po', '=', 'tb_po_msp.no')
                ->join('tb_pr_msp', 'tb_pr_msp.no', '=', 'tb_po_asset_msp.no_pr')
                ->join('tb_pam_msp', 'tb_pam_msp.id_pam', '=', 'tb_po_asset_msp.id_pr_asset')
                ->select('tb_pam_msp.date_handover','tb_pr_msp.no_pr','tb_pam_msp.ket_pr','tb_pam_msp.note_pr','tb_pam_msp.to_agen','tb_pam_msp.status','tb_po_asset_msp.status_po','users.name','tb_pam_msp.subject', 'tb_pr_msp.no', 'tb_pr_msp.date', 'tb_pam_msp.attention', 'tb_pam_msp.project', 'tb_pam_msp.project_id', 'ppn', 'tb_po_asset_msp.term', 'tb_po_msp.no_po', 'tb_po_asset_msp.project_id', 'tb_po_asset_msp.id_po_asset')
                ->where('tb_po_asset_msp.status_po','!=', 'NEW')
                ->get();

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
            ->where('result','')
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

        // $sum = DB::table('tb_pam_msp')
        //     ->select('id_pam')
        //     ->sum('id_pam');

        // $count_product = DB::table('tb_pr_product_msp')
        //     ->select('id_product')
        //     ->sum('id_product');

        // $total_amount = DB::table('tb_pr_product_msp')
        //             ->select('nominal')
        //             ->sum('nominal');

        $from = DB::table('users')
                ->select('nik', 'name')
                ->where('id_company', '2')
                ->get();

        return view('admin_msp/po_asset',compact('notif','notifOpen','notifsd','notiftp','notifClaim','pam','produks','pams','sum','id_pam','count_product','total_amount','no_pr','$total_amount','from'));
    }

    public function update(Request $request)
    {
        $id_pam = $request['id_pam'];

        $update = POAssetMSP::where('id_po_asset', $id_pam)->first();
        $update->term          = nl2br($request['term_edit']);
        $update->update();

        return redirect('po_asset_msp')->with('update', 'Successfully!');
        //
    }

    public function downloadPDF2($id_po_asset)
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        $datas = DB::table('tb_po_asset_msp')
                ->join('users', 'users.nik', '=', 'tb_po_asset_msp.nik_admin')
                ->join('tb_po_msp', 'tb_po_asset_msp.no_po', '=', 'tb_po_msp.no')
                ->join('tb_pr_msp', 'tb_pr_msp.no', '=', 'tb_po_asset_msp.no_pr')
                ->join('tb_pam_msp', 'tb_pam_msp.id_pam', '=', 'tb_po_asset_msp.id_pr_asset')
                ->select('tb_pam_msp.date_handover','tb_pr_msp.no_pr','tb_pam_msp.ket_pr','tb_pam_msp.note_pr','tb_po_asset_msp.to_agen','tb_pam_msp.status','tb_po_asset_msp.status_po','users.name','tb_po_asset_msp.subject', 'tb_pr_msp.no', 'tb_pr_msp.date', 'tb_po_asset_msp.attention', 'tb_po_asset_msp.project', 'tb_po_asset_msp.project_id', 'ppn', 'tb_po_asset_msp.term', 'tb_po_msp.no_po', 'tb_po_asset_msp.project_id', 'tb_po_asset_msp.id_po_asset', 'ppn', 'tb_po_asset_msp.term', 'tb_po_asset_msp.address', 'tb_po_asset_msp.telp', 'tb_po_asset_msp.fax', 'tb_po_asset_msp.email', 'tb_po_asset_msp.id_po_asset')
                ->where('tb_po_asset_msp.id_po_asset', $id_po_asset)
                ->first();

        $produks = DB::table('tb_pam_msp')
            ->join('tb_pr_product_msp','tb_pr_product_msp.id_pam','=','tb_pam_msp.id_pam')
            ->join('tb_po_asset_msp', 'tb_po_asset_msp.id_pr_asset', '=', 'tb_pam_msp.id_pam')
            ->select('tb_pr_product_msp.name_product','tb_pr_product_msp.qty','tb_pr_product_msp.id_pam','tb_pr_product_msp.nominal','tb_pr_product_msp.total_nominal', 'tb_pr_product_msp.description', 'tb_pr_product_msp.unit', 'tb_pr_product_msp.msp_code')
            ->where('tb_po_asset_msp.id_po_asset',$id_po_asset)
            ->get();

    	$total_amounts = DB::table('tb_pam_msp')
            ->join('tb_pr_product_msp','tb_pr_product_msp.id_pam','=','tb_pam_msp.id_pam')
            ->join('tb_po_asset_msp', 'tb_po_asset_msp.id_pr_asset', '=', 'tb_pam_msp.id_pam')
            ->select('total_nominal')
            ->where('tb_po_asset_msp.id_po_asset', $id_po_asset)
            ->sum('total_nominal');

        $total_amount = "Rp " . number_format($total_amounts,0,'','.');

        $ppns = $total_amounts * (10/100);

        $ppn   = "Rp " . number_format($ppns,0,'','.');

        $grand_total = $total_amounts + $ppns;

        $grand_total2 =  "Rp " . number_format($grand_total,0,'','.');

        return view('admin_msp.po_pdf', compact('datas','produks','total_amount', 'nominal', 'ppn', 'grand_total2'));
        // return $pdf->download('Purchase Order '.$datas->no_po.' '.'.pdf');
    }
}
