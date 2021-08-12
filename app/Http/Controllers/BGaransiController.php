<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\BGaransi;
use Auth;
use PDF;
use Mail;
use App\Notifications\NewGuaranteeBank;
use Notification;
use App\User;
use Nasution\Terbilang;
use App\Letter;

class BGaransiController extends Controller
{    
   	public function index()
   	{
        $datas = "";
        $notiftp = "";
        $notifsd = "";
        $notif = "";
        $notifOpen = "";
        $notifClaim = "";

   		$nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position; 


        if ($div == 'SALES' ) {
        	$datas = DB::table('tb_bank_garansi')
   				->join('users', 'users.nik', '=', 'tb_bank_garansi.nik')
   				->select('kode_proyek', 'nama_proyek', 'no_proyek', 'perusahaan', 'division', 'alamat', 'kota', 'kode_pos', 'jenis', 'name', 'penerbit', 'tgl_mulai', 'tgl_selesai', 'dok_ref', 'valuta', 'nominal', 'note', 'no_dok', 'id_bank_garansi', 'status', 'tb_bank_garansi.updated_at','tb_bank_garansi.nik')
   				->where('tb_bank_garansi.nik', $nik)
                ->orderBy('tb_bank_garansi.created_at', 'desc')
   				->get();
        }elseif ($div == 'HR') {
        	$datas = DB::table('tb_bank_garansi')
   				->join('users', 'users.nik', '=', 'tb_bank_garansi.nik')
   				->select('kode_proyek', 'nama_proyek', 'no_proyek', 'perusahaan', 'division', 'alamat', 'kota', 'kode_pos', 'jenis', 'name', 'penerbit', 'tgl_mulai', 'tgl_selesai', 'dok_ref', 'valuta', 'nominal', 'note', 'no_dok', 'id_bank_garansi', 'status', 'tb_bank_garansi.updated_at','tb_bank_garansi.nik')
                ->orderBy('tb_bank_garansi.created_at', 'desc')
   				->get();
        }else{
            $datas = DB::table('tb_bank_garansi')
                ->join('users', 'users.nik', '=', 'tb_bank_garansi.nik')
                ->select('kode_proyek', 'nama_proyek', 'no_proyek', 'perusahaan', 'division', 'alamat', 'kota', 'kode_pos', 'jenis', 'name', 'penerbit', 'tgl_mulai', 'tgl_selesai', 'dok_ref', 'valuta', 'nominal', 'note', 'no_dok', 'id_bank_garansi', 'status', 'tb_bank_garansi.updated_at','tb_bank_garansi.nik')
                ->orderBy('tb_bank_garansi.created_at', 'desc')
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

   		return view('HR/bgaransi', compact('datas', 'notiftp', 'notifsd', 'notif', 'notifOpen', 'notifClaim'))->with(['initView'=> $this->initMenuBase(),'feature_item'=>$this->RoleDynamic('bgaransi')]);
   	}

   	public function add_bgaransi()
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


   		return view('HR/add_bgaransi', compact('notiftp', 'notifsd', 'notif', 'notifOpen', 'notifClaim'))->with(['initView'=> $this->initMenuBase()]);
   	}

   	public function edit_bg($id_bank_garansi)
   	{
   		$nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position; 


        if ($div == 'SALES' ) {
        	$datas = DB::table('tb_bank_garansi')
   				->join('users', 'users.nik', '=', 'tb_bank_garansi.nik')
   				->select('kode_proyek', 'nama_proyek', 'no_proyek', 'perusahaan', 'division', 'alamat', 'kota', 'kode_pos', 'jenis', 'name', 'penerbit', 'tgl_mulai', 'tgl_selesai', 'dok_ref', 'valuta', 'nominal', 'note', 'no_dok', 'id_bank_garansi', 'jangka_waktu', 'telp', 'fax')
   				->where('tb_bank_garansi.nik', $nik)
   				->where('id_bank_garansi', $id_bank_garansi)
   				->first();
        }elseif ($div == 'HR') {
        	$datas = DB::table('tb_bank_garansi')
   				->join('users', 'users.nik', '=', 'tb_bank_garansi.nik')
   				->select('kode_proyek', 'nama_proyek', 'no_proyek', 'perusahaan', 'division', 'alamat', 'kota', 'kode_pos', 'jenis', 'name', 'penerbit', 'tgl_mulai', 'tgl_selesai', 'dok_ref', 'valuta', 'nominal', 'note', 'no_dok', 'id_bank_garansi', 'jangka_waktu', 'telp', 'fax')
   				->where('id_bank_garansi', $id_bank_garansi)
   				->first();
        }else{
            $datas = DB::table('tb_bank_garansi')
                ->join('users', 'users.nik', '=', 'tb_bank_garansi.nik')
                ->select('kode_proyek', 'nama_proyek', 'no_proyek', 'perusahaan', 'division', 'alamat', 'kota', 'kode_pos', 'jenis', 'name', 'penerbit', 'tgl_mulai', 'tgl_selesai', 'dok_ref', 'valuta', 'nominal', 'note', 'no_dok', 'id_bank_garansi', 'jangka_waktu', 'telp', 'fax')
                ->where('id_bank_garansi', $id_bank_garansi)
                ->first();
        }

   		
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
        } else{
            $notifClaim = DB::table('dvg_esm')
                            ->select('nik_admin', 'personnel', 'type')
                            ->where('status', 'FINANCE')
                            ->get();
        }

   		return view('HR/edit_bgaransi', compact('datas', 'notiftp', 'notifsd', 'notif', 'notifOpen', 'notifClaim'))->with(['initView'=> $this->initMenuBase()]);
   	}

   	public function store(Request $request)
   	{
   		$tambah = new BGaransi();
        $tambah->kode_proyek 	= $request['kode_proyek'];
        $tambah->nama_proyek 	= $request['nama_proyek'];
        $tambah->nik 			= Auth::User()->nik;
        $tambah->no_proyek		= $request['no'];
        $tambah->perusahaan 	= $request['perusahaan'];
        $tambah->division 		= $request['div'];
        if ($request['nominal'] == NULL) {
            $tambah->nominal        = $request['nominal'];
        }else{
            $tambah->nominal        = str_replace(',', '', $request['nominal']);
        }
        $tambah->alamat		 	= $request['alamat'];
        $tambah->kota 			= $request['kota'];
        $tambah->kode_pos		= $request['kode'];
        $tambah->note 			= $request['note'];
        $tambah->jenis 			= $request['jenis'];
        $tambah->penerbit 		= $request['penerbit'];
        $tgl_mulai              = strtotime($_POST['tgl_mulai']); 
        $tgl_mulai              = date("Y-m-d",$tgl_mulai);
        $tambah->tgl_mulai 		= $tgl_mulai;
        $tgl_selesai            = strtotime($_POST['tgl_selesai']); 
        $tgl_selesai            = date("Y-m-d",$tgl_selesai);
        $tambah->tgl_selesai 	= $tgl_selesai;
        $tambah->jangka_waktu 	= $request['jangka'];
        $tambah->dok_ref 		= $request['dokumen'];
        $tambah->no_dok 		= $request['no_dok'];
        $tambah->valuta 		= $request['valuta'];
        $tambah->telp           = $request['no_telp'];
        $tambah->fax            = $request['no_fax'];
        $tambah->status         = 'new';
        $tambah->save();

        $kirim = User::select('email')->where('id_division', 'HR')->get();

        $users = User::select('email')->where('email', 'faiqoh@sinergy.co.id')->get();
        Notification::send($kirim, new NewGuaranteeBank());

        return redirect('/bank_garansi')->with('success', 'Successfully!');
   	}

   	public function update(Request $request)
   	{
   		$id_bank_garansi = $request['id'];

   		$update = BGaransi::where('id_bank_garansi', $id_bank_garansi)->first();
   		$update->kode_proyek 	= $request['kode_proyek'];
        $update->nama_proyek 	= $request['nama_proyek'];
        // $update->nik 			= Auth::User()->nik;
        $update->no_proyek		= $request['no'];
        $update->perusahaan 	= $request['perusahaan'];
        $update->division 		= $request['div'];
        $update->nominal 		= str_replace(',', '', $request['nominal']);
        $update->alamat		 	= $request['alamat'];
        $update->kota 			= $request['kota'];
        $update->kode_pos		= $request['kode'];
        $update->note 			= $request['note'];
        $update->jenis 			= $request['jenis'];
        $update->penerbit 		= $request['penerbit'];
        $update->tgl_mulai 		= $request['tgl_mulai'];
        $update->tgl_selesai 	= $request['tgl_selesai'];
        $update->jangka_waktu 	= $request['jangka'];
        $update->dok_ref 		= $request['dokumen'];
        $update->no_dok 		= $request['no_dok'];
        $update->valuta 		= $request['valuta'];
        $update->telp           = $request['no_telp'];
        $update->fax            = $request['no_fax'];
        $update->update();

        return redirect('/bank_garansi')->with('success', 'Successfully!');
   	}

   	public function pdf(Request $request,$id_bank_garansi)
   	{
   		$datas = DB::table('tb_bank_garansi')
   				->join('users', 'users.nik', '=', 'tb_bank_garansi.nik')
   				->select('kode_proyek', 'nama_proyek', 'no_proyek', 'perusahaan', 'division', 'alamat', 'kota', 'kode_pos', 'jenis', 'name', 'penerbit', 'tgl_mulai', 'tgl_selesai', 'dok_ref', 'valuta', 'nominal', 'note', 'no_dok', 'id_bank_garansi', 'jangka_waktu', 'tb_bank_garansi.created_at')
   				->where('id_bank_garansi', $id_bank_garansi)
   				->first();

        $nominals2 = DB::table('tb_bank_garansi')->select('nominal')->where('id_bank_garansi', $id_bank_garansi)->sum('nominal');

        $nominal = number_format($nominals2,0,'','.');

        $terbilang = new Terbilang();
        $kata = $terbilang->convert($nominals2); 

        $origdate = DB::table('tb_bank_garansi')
                    ->select('tgl_selesai', 'tgl_mulai', 'created_at', 'updated_at')
                    ->where('id_bank_garansi', $id_bank_garansi)
                    ->first();

        $tgl_mulai = date("d-M-y", strtotime($origdate->tgl_mulai));
        $tgl_selesai = date("d-M-y", strtotime($origdate->tgl_selesai));
        $created_at = date("d-M-y", strtotime($origdate->created_at));

   		return view('HR.bg_pdf', compact('datas', 'kata', 'tgl_mulai', 'tgl_selesai', 'created_at'))->with(['initView'=> $this->initMenuBase()]);
   	}

    public function update_status(Request $request)
    {
        $id = $request['id_bg_submit'];

        $update = BGaransi::where('id_bank_garansi', $id)->first();
        $update->status = 'done';
        $update->update();

        return redirect('/bank_garansi')->with('success', 'Successfully!');
    }

    public function downloadpdfsk($id_bank_garansi)
    {
    	$datas = DB::table('tb_bank_garansi')
   				->join('users', 'users.nik', '=', 'tb_bank_garansi.nik')
   				->join('tb_letter', 'tb_bank_garansi.id_bank_garansi', '=', 'tb_letter.id_bank_garansi')
   				->select('kode_proyek', 'nama_proyek', 'no_proyek', 'perusahaan', 'tb_bank_garansi.division', 'alamat', 'kota', 'kode_pos', 'jenis', 'name', 'penerbit', 'tgl_mulai', 'tgl_selesai', 'dok_ref', 'valuta', 'nominal', 'tb_bank_garansi.note', 'no_dok', 'tb_bank_garansi.id_bank_garansi', 'jangka_waktu', 'tb_bank_garansi.created_at', 'no_letter')
   				->where('tb_bank_garansi.id_bank_garansi', $id_bank_garansi)
   				->first();

   		$origdate = DB::table('tb_bank_garansi')
                    ->select('tgl_selesai', 'tgl_mulai', 'created_at', 'updated_at')
                    ->where('id_bank_garansi', $id_bank_garansi)
                    ->first();

        $tgl_mulai = date("d-M-y", strtotime($origdate->tgl_mulai));
        $tgl_selesai = date("d-M-y", strtotime($origdate->tgl_selesai));
        $created_at = date("d-M-y", strtotime($origdate->created_at));

        $nominals2 = DB::table('tb_bank_garansi')->select('nominal')->where('id_bank_garansi', $id_bank_garansi)->sum('nominal');

        $nominal = number_format($nominals2,0,'','.');

        $terbilang = new Terbilang();
        $kata = $terbilang->convert($nominals2); 

   		return view('HR.surat_kuasa_pdf', compact('datas', 'tgl_selesai', 'tgl_mulai', 'created_at', 'kata'))->with(['initView'=> $this->initMenuBase()]);
    }

    public function accept_status(Request $request)
    {
    	$id = $request['id_bg_accept'];

    	$update = BGaransi::where('id_bank_garansi', $id)->first();
        $update->status = 'proses';
        $update->update();

        $cek = DB::table('tb_letter')
                ->count('no');


        if ($cek > 0) {
            $getno = Letter::orderBy('no', 'desc')->first();
            $getno_new = $getno->no;

                if ($getno_new < 7) {
                    $angka = '7';
                }
                elseif ($getno_new > 6) {
                        $query = Letter::where('no','like','%7')->get();
                        foreach ($query as $data) {
                             if ($getno_new == $data->no) {
                                 $angka = $data->no;
                             }else{
                                 $angka = $data->no;
                             }
                        }
                }

                if ($getno_new == $angka) {
                     
                    $type = "SGB";
                    $posti = "HRD";
                    $month_pr = date('m');
                    $year_pr = date('Y');

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

                    $getnumber = Letter::orderBy('no', 'desc')->first();

                    if($getnumber == NULL){
                        $getlastnumber = 1;
                        $lastnumber = $getlastnumber;
                    } else{
                        $lastnumber = $getnumber->no+1;
                    }

                    if($lastnumber < 10){
                       $akhirnomor = '000' . $lastnumber;
                    }elseif($lastnumber > 9 && $lastnumber < 100){
                       $akhirnomor = '00' . $lastnumber;
                    }elseif($lastnumber >= 100){
                       $akhirnomor = '0' . $lastnumber;
                    }

                    $no = $akhirnomor.'/'.$posti .'/'. $type.'/' . $bln .'/'. $year_pr;

                    for ($i=0; $i < 2 ; $i++) { 
                        $tambah = new Letter();
                        $tambah->no_letter = $no;
                        $tambah->position = $posti;
                        $tambah->type_of_letter = $type;
                        $tambah->month = $bln;
                        $tambah->date = date('Y-m-d');
                        $tambah->to = 'Bank Mandiri';
                        $tambah->attention = 'Bank Garansi';
                        $tambah->title = 'Bank Garansi';
                        $tambah->project = $request['project'];
                        $tambah->description = 'Pembuatan Bank Garansi';
                        $tambah->nik = Auth::User()->nik;
                        $tambah->division = 'HRD';
                        $tambah->project_id = '';
                        $tambah->id_bank_garansi = $id;
                     
                        if ($i == 0) {
                           $tambah->status = NULL;
                        }else{
                            $tambah->status = 'T';
                        }

                        $tambah->save();
                    }

                    /*return redirect('letter')->with('success', 'Create Letter Successfully!');*/
                }else{
                    $type = "SGB";
                    $posti = "HRD";
                    $month_pr = date('m');
                    $year_pr = date('Y');

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

                    $getnumber = Letter::orderBy('no', 'desc')->first();

                    if($getnumber == NULL){
                        $getlastnumber = 1;
                        $lastnumber = $getlastnumber;
                    } else{
                        $lastnumber = $getnumber->no+1;
                    }

                    if($lastnumber < 10){
                       $akhirnomor = '000' . $lastnumber;
                    }elseif($lastnumber > 9 && $lastnumber < 100){
                       $akhirnomor = '00' . $lastnumber;
                    }elseif($lastnumber >= 100){
                       $akhirnomor = '0' . $lastnumber;
                    }

                    $no = $akhirnomor.'/'.$posti .'/'. $type.'/' . $bln .'/'. $year_pr;

                    $tambah = new Letter();
                    $tambah->no = $lastnumber;
                    $tambah->no_letter = $no;
                    $tambah->position = $posti;
                    $tambah->type_of_letter = $type;
                    $tambah->month = $bln;
                    $tambah->date = date('Y-m-d');
                    $tambah->to = 'Bank Mandiri';
                    $tambah->attention = 'Bank Garansi';
                    $tambah->title = 'Bank Garansi';
                    $tambah->project = $request['project'];
                    $tambah->description = 'Pembuatan Bank Garansi';
                    $tambah->nik = Auth::User()->nik;
                    $tambah->division = 'HRD';
                    $tambah->project_id = '';
                    $tambah->id_bank_garansi = $id;
                    $tambah->save();

                    // return redirect('letter')->with('success', 'Create Letter Successfully!');
                        
                }
            
        } else{
            $type = "SGB";
            $posti = "HRD";
            $month_pr = date('m');
            $year_pr = date('Y');

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

            $getnumber = Letter::orderBy('no', 'desc')->first();

            if($getnumber == NULL){
                $getlastnumber = 1;
                $lastnumber = $getlastnumber;
            } else{
                $lastnumber = $getnumber->no+1;
            }

            if($lastnumber < 10){
               $akhirnomor = '000' . $lastnumber;
            }elseif($lastnumber > 9 && $lastnumber < 100){
               $akhirnomor = '00' . $lastnumber;
            }elseif($lastnumber >= 100){
               $akhirnomor = '0' . $lastnumber;
            }

            $no = $akhirnomor.'/'.$posti .'/'. $type.'/' . $bln .'/'. $year_pr;

            $tambah = new Letter();
            $tambah->no = $lastnumber;
            $tambah->no_letter = $no;
            $tambah->position = $posti;
            $tambah->type_of_letter = $type;
            $tambah->month = $bln;
            $tambah->date = date('Y-m-d');
            $tambah->to = 'Bank Mandiri';
            $tambah->attention = 'Bank Garansi';
            $tambah->title = 'Bank Garansi';
            $tambah->project = $request['project'];
            $tambah->description = 'Pembuatan Bank Garansi';
            $tambah->nik = Auth::User()->nik;
            $tambah->division = 'HRD';
            $tambah->project_id = '';
            $tambah->id_bank_garansi = $id;
            $tambah->save();

            // return redirect('letter')->with('success', 'Create Letter Successfully!');
        }

        return redirect('/bank_garansi')->with('success', 'Successfully!');
    }
}
