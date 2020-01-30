<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use App\PONumber;
use App\POAsset;
use App\pam;
use App\pamProduk;
use App\pamProgress;
use PDF;
use Excel;
use App\PR;

class POAssetController extends Controller
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

        /*$pam = DB::table('dvg_pam')
            ->join('users','users.nik','=','dvg_pam.personel')
            ->join('tb_pr','tb_pr.no','=','dvg_pam.no_pr')
            ->select('dvg_pam.id_pam','dvg_pam.date_handover','tb_pr.no_pr','dvg_pam.ket_pr','dvg_pam.note_pr','dvg_pam.to_agen','dvg_pam.status','users.name','dvg_pam.subject', 'tb_pr.no', 'tb_pr.date', 'dvg_pam.attention', 'dvg_pam.project', 'dvg_pam.project_id', 'ppn', 'terms')
            ->get();*/

        $pam = DB::table('tb_po_asset')
                ->join('users', 'users.nik', '=', 'tb_po_asset.nik_admin')
                ->join('tb_po', 'tb_po_asset.no_po', '=', 'tb_po.no')
                ->join('tb_pr', 'tb_pr.no', '=', 'tb_po_asset.no_pr')
                ->join('dvg_pam', 'dvg_pam.id_pam', '=', 'tb_po_asset.id_pr_asset')
                ->select('dvg_pam.date','tb_pr.no_pr','dvg_pam.ket_pr','dvg_pam.note_pr','dvg_pam.to_agen','dvg_pam.status','tb_po_asset.status_po','users.name','dvg_pam.subject', 'tb_pr.no', 'tb_pr.date', 'dvg_pam.attention', 'dvg_pam.project', 'dvg_pam.project_id', 'ppn', 'tb_po_asset.term', 'tb_po.no_po', 'tb_po_asset.project_id', 'tb_po_asset.id_po_asset', 'dvg_pam.id_pam', 'tb_po_asset.id_po_asset')
                ->where('tb_po_asset.status_po','!=', 'NEW')
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

        // $sum = DB::table('dvg_pam')
        //     ->select('id_pam')
        //     ->sum('id_pam');

        // $count_product = DB::table('dvg_pr_product')
        //     ->select('id_product')
        //     ->sum('id_product');

        // $total_amount = DB::table('dvg_pr_product')
        //             ->select('nominal')
        //             ->sum('nominal');

        $from = DB::table('users')
                ->select('nik', 'name')
                ->where('id_company', '2')
                ->get();

        return view('DVG/po_asset/po_asset',compact('notif','notifOpen','notifsd','notiftp','notifClaim','pam','produks','pams','sum','id_pam','count_product','total_amount','no_pr','$total_amount','from'));
    }

    public function update(Request $request)
    {
        $id_po_asset = $request['id_po_asset'];
        $term = stripslashes($request['term']);

        $update_term = POAsset::where('id_po_asset', $id_po_asset)->first();
        // $update_term->term          = nl2br($request['term']);
        $update_term->term = nl2br($term);
        $update_term->update();

        return redirect('po_asset')->with('update', 'Successfully!');
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

        $datas = DB::table('tb_po_asset')
                ->join('users', 'users.nik', '=', 'tb_po_asset.nik_admin')
                ->join('tb_po', 'tb_po_asset.no_po', '=', 'tb_po.no')
                ->join('tb_pr', 'tb_pr.no', '=', 'tb_po_asset.no_pr')
                ->join('dvg_pam', 'dvg_pam.id_pam', '=', 'tb_po_asset.id_pr_asset')
                ->select('dvg_pam.date','tb_pr.no_pr','dvg_pam.ket_pr','dvg_pam.note_pr','tb_po_asset.to_agen','dvg_pam.status','tb_po_asset.status_po','users.name','tb_po_asset.subject', 'tb_pr.no', 'tb_pr.date', 'tb_po_asset.attention', 'tb_po_asset.project', 'tb_po_asset.project_id', 'ppn', 'tb_po_asset.term', 'tb_po.no_po', 'tb_po_asset.project_id', 'tb_po_asset.id_po_asset', 'ppn', 'tb_po_asset.term', 'tb_po_asset.address', 'tb_po_asset.telp', 'tb_po_asset.fax', 'tb_po_asset.email')
                ->where('tb_po_asset.id_po_asset', $id_po_asset)
                ->first();

        $produks = DB::table('dvg_pam')
            ->join('dvg_pr_product','dvg_pr_product.id_pam','=','dvg_pam.id_pam')
            ->join('tb_po_asset', 'tb_po_asset.id_pr_asset', '=', 'dvg_pam.id_pam')
            ->select('dvg_pr_product.name_product','dvg_pr_product.qty','dvg_pr_product.id_pam','dvg_pr_product.nominal','dvg_pr_product.total_nominal', 'dvg_pr_product.description')
            ->where('tb_po_asset.id_po_asset',$id_po_asset)
            ->where('dvg_pr_product.name_product', '!=', '')
            ->get();

    	$total_amounts = DB::table('dvg_pam')
            ->join('dvg_pr_product','dvg_pr_product.id_pam','=','dvg_pam.id_pam')
            ->join('tb_po_asset', 'tb_po_asset.id_pr_asset', '=', 'dvg_pam.id_pam')
            ->select('total_nominal')
            ->where('tb_po_asset.id_po_asset', $id_po_asset)
            ->sum('total_nominal');

        $total_amount = "Rp " . number_format($total_amounts,0,'','.');

        $ppns = $total_amounts * (10/100);

        $ppn   = "Rp " . number_format($ppns,0,'','.');

        $grand_total = $total_amounts + $ppns;

        $grand_total2 =  "Rp " . number_format($grand_total,0,'','.');

        return view('DVG.po_asset.po_pdf', compact('datas','produks','total_amount', 'nominal', 'ppn', 'grand_total2'));
        // return $pdf->download('Purchase Order '.$datas->no_po.' '.'.pdf');
    }
}
