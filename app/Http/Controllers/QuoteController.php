<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Quote;
use DB;
use Auth;
use Excel;
use App\TB_Contact;

class QuoteController extends Controller
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

        $pops = Quote::select('quote_number')->orderBy('created_at','desc')->first();

        $pops2 = Quote::select('quote_number')->where('status_backdate', 'F')->orderBy('updated_at', 'desc')->first();

        $tahun = date("Y");

		$datas = DB::table('tb_quote')
                        ->join('users', 'users.nik', '=', 'tb_quote.nik')
                        ->join('tb_contact', 'tb_contact.id_customer', '=', 'tb_quote.id_customer', 'left')
                        ->select('id_quote','quote_number','position','type_of_letter','date','to','attention','title','project','status', 'description', 'from', 'division', 'project_id','note', 'status_backdate', 'tb_quote.nik', 'name', 'month', 'project_type', 'tb_contact.id_customer', 'customer_legal_name')
                        ->orderBy('tb_quote.created_at', 'desc')
                        ->get();

        $backdate_num = Quote::select('quote_number','id_quote')->where('status_backdate', 'T')->get();

        $count = DB::table('tb_quote')
                    ->where('status_backdate', 'T')
                    ->get();

        $counts = count($count);

        $customer = TB_Contact::select('customer_legal_name', 'id_customer')->get();

        if ($ter != null) {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
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

        $sidebar_collapse = true;

        return view('quote/quote',compact('notif','datas','notifOpen','notifsd','notiftp', 'notifClaim', 'counts', 'count','pops', 'pops2', 'backdate_num', 'sidebar_collapse', 'customer'));
	}

	public function create()
	{

	}

    public function store(Request $request)
    {

        $tahun = date("Y");
        $cek = DB::table('tb_quote')
                // ->where('date','like',$tahun."%")
                ->whereYear('created_at', $tahun)
                ->count('id_quote');

        if ($cek > 0) {
            $getno = Quote::orderBy('id_quote', 'desc')->first();
            $getno_new = $getno->id_quote;

                if ($getno_new < 7) {
                    $angka = '7';
                }
                elseif ($getno_new > 6) {
                        $query = Quote::where('id_quote','like','%7')->get();
                        foreach ($query as $data) {
                             if ($getno_new == $data->id_quote) {
                                 $angka = $data->id_quote;
                             }else{
                                 $angka = $data->id_quote;
                             }
                        }
                }

                if ($getno_new == $angka) {
                     
                    $type = 'QO';
                    $posti = $request['position'];
                    $month_quote = substr($request['date'],5,2);
                    $year_quote = substr($request['date'],0,4);

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
                    $bln = $array_bln[$month_quote];


                    $getnumber = Quote::orderBy('no', 'desc')->whereYear('created_at', $tahun)->count();

                    $getnumbers = Quote::orderBy('id_quote', 'desc')->first();

                    if($getnumber == NULL){
                        $getlastnumber = 1;
                        $lastnumber = $getlastnumber;
                    } else{
                        $lastnumber = $getnumber+1;
                        $lastnumber9 = $getnumber+2;
                    }

                    if($lastnumber < 10){
                       $akhirnomor = '000' . $lastnumber;
                       $akhirnomor9 = '000' . $lastnumber9;
                    }elseif($lastnumber > 9 && $lastnumber < 100){
                       $akhirnomor = '00' . $lastnumber;
                       $akhirnomor9 = '00' . $lastnumber9;
                    }elseif($lastnumber >= 100){
                       $akhirnomor =  '0' . $lastnumber;
                       $akhirnomor9 = '0' . $lastnumber9;
                    }

                    $no = $akhirnomor.'/'.$posti .'/'. $type.'/' . $bln .'/'. $year_quote;
                    $nom = Quote::select('id_quote')->orderBy('created_at','desc')->first();
                    $no9  = $akhirnomor9;

                    for ($i=0; $i < 2 ; $i++) { 
                        $tambah = new Quote();
                        if ($i == 0) {
                            $tambah->id_quote = $nom->id_quote+1;
                            $tambah->quote_number = $no;
                            $tambah->status_backdate = NULL;
                        } else{
                            $tambah->id_quote = $nom->id_quote+2;
                            $tambah->quote_number = $no9;
                            $tambah->status_backdate = 'T';
                        }
                        
                        $tambah->position = $posti;
                        $tambah->type_of_letter = $type;
                        $tambah->month = $bln;
                        $tambah->date = $request['date'];
                        // $tambah->to = $request['to'];
                        $tambah->id_customer = $request['customer_quote'];
                        $tambah->attention = $request['attention'];
                        $tambah->title = $request['title'];
                        $tambah->project = $request['project'];
                        $tambah->description = $request['description'];
                        $tambah->nik = Auth::User()->nik;
                        $tambah->division = $request['division'];
                        $tambah->project_id = $request['project_id'];
                        $tambah->project_type = $request['project_type'];
                     
                        /*if ($i == 0) {
                           $tambah->status_backdate = NULL;
                        }else{
                            $tambah->status_backdate = 'T';
                        }*/

                        $tambah->save();
                    }

                    return redirect('quote')->with('success', 'Create Quote Number Successfully!');
                }else{
                    $type = 'QO';
                    $posti = $request['position'];
                    $month_quote = substr($request['date'],5,2);
                    $year_quote = substr($request['date'],0,4);

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
                    $bln = $array_bln[$month_quote];

                    $getnumber = Quote::orderBy('id_quote', 'desc')->whereYear('created_at', $tahun)->count();

                    $getnumbers = Quote::orderBy('id_quote', 'desc')->first();

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
                    }elseif($lastnumber >= 100){
                       $akhirnomor = '0' . $lastnumber;
                    }

                    $no = $akhirnomor.'/'.$posti .'/'. $type.'/' . $bln .'/'. $year_quote;

                    $tambah = new Quote();
                    $tambah->id_quote = $getnumbers->id_quote+1;
                    $tambah->quote_number = $no;
                    $tambah->position = $posti;
                    $tambah->type_of_letter = $type;
                    $tambah->month = $bln;
                    $tambah->date = $request['date'];
                    // $tambah->to = $request['to'];
                    $tambah->id_customer = $request['customer_quote'];
                    $tambah->attention = $request['attention'];
                    $tambah->title = $request['title'];
                    $tambah->project = $request['project'];
                    $tambah->description = $request['description'];
                    $tambah->nik = Auth::User()->nik;
                    $tambah->division = $request['division'];
                    $tambah->project_id = $request['project_id'];
                    $tambah->project_type = $request['project_type'];
                    $tambah->save();

                    return redirect('quote')->with('success', 'Create Quote Number Successfully!');
                        
                }
            
        } else{
            $type = 'QO';
            $posti = $request['position'];
            $month_quote = substr($request['date'],5,2);
            $year_quote = substr($request['date'],0,4);

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
            $bln = $array_bln[$month_quote];

            $getnumber = Quote::orderBy('id_quote', 'desc')->whereYear('created_at', $tahun)->count();

            $getnumbers = Quote::orderBy('id_quote', 'desc')->first();

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

            $no = $akhirnomor.'/'.$posti .'/'. $type.'/' . $bln .'/'. $year_quote;

            $tambah = new Quote();
            $tambah->id_quote = $getnumbers->id_quote+1;
            $tambah->quote_number = $no;
            $tambah->position = $posti;
            $tambah->type_of_letter = $type;
            $tambah->month = $bln;
            $tambah->date = $request['date'];
            // $tambah->to = $request['to'];
            $tambah->id_customer = $request['customer_quote'];
            $tambah->attention = $request['attention'];
            $tambah->title = $request['title'];
            $tambah->project = $request['project'];
            $tambah->description = $request['description'];
            $tambah->nik = Auth::User()->nik;
            $tambah->division = $request['division'];
            $tambah->project_id = $request['project_id'];
            $tambah->project_type = $request['project_type'];
            $tambah->save();

            return redirect('quote')->with('success', 'Create Quote Number Successfully!');
        }
    }

    public function store_backdate(Request $request)
    {
        $type = 'QO';
        $posti = $request['position'];
        $month_quote = substr($request['date'],5,2);
        $year_quote = substr($request['date'],0,4);

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
        $bln = $array_bln[$month_quote];

        /*$query = Quote::select('id_quote')
                        ->where('status_backdate','T')
                        ->orderBy('id_quote','asc')
                        ->first();
        
        $lastnumber = $query->id_quote;

        if($lastnumber < 10){
           $akhirnomor = '00' . $lastnumber;
        }elseif($lastnumber > 9 && $lastnumber < 100){
           $akhirnomor = '0' . $lastnumber;
        }elseif($lastnumber >= 100){
           $akhirnomor = $lastnumber;
        }*/

        $akhirnomor = $request['backdate_num'];

        $no = $akhirnomor.'/'.$posti .'/'. $type.'/' . $bln .'/'. $year_quote;

        $angka7 = Quote::select('id_quote')
                ->where('status_backdate','T')
                ->orderBy('id_quote','asc')
                ->first();
        $angka = $angka7->id_quote;

        $update = Quote::where('id_quote',$request['backdate_num'])->first();
        $update->quote_number = $no;
        $update->position = $posti;
        $update->type_of_letter = $type;
        $update->month = $bln;
        $update->date = $request['date'];
        // $update->to = $request['to'];
        $tambah->id_customer = $request['customer_quote_backdate'];
        $update->attention = $request['attention'];
        $update->title = $request['title'];
        $update->project = $request['project'];
        $update->description = $request['description'];
        $update->nik = Auth::User()->nik;
        $update->division = $request['division'];
        $update->project_id = $request['project_id'];
        $update->status_backdate = 'F';
        $update->project_type = $request['project_type'];
        $update->update();

        return redirect('quote')->with('sukses', 'Create Quote Number Successfully!');
    }

	public function update(Request $request)
	{
        $quote_number = $request['quote_number'];

        $update = Quote::where('quote_number', $quote_number)->first();
        $update->quote_number = $request['quote_number'];
        
        $update->to = $request['edit_to'];
        $update->attention = $request['edit_attention'];
        $update->title = $request['edit_title'];
        $update->project = $request['edit_project'];
        $update->description = $request['edit_description'];
        $update->project_id = $request['edit_project_id'];
        $update->note = $request['edit_note'];
        $update->update();

        return redirect('quote')->with('update', 'Update Quote Number Successfully!');
	}

    public function destroy_quote(Request $request)
    {
        $hapus = Quote::find($request->id_quote);
        $hapus->delete();

        return redirect()->back();
    }

    public function report_quote()
    {
        $nik = Auth::User()->nik;
        $territory = DB::table('users')->select('id_territory')->where('nik', $nik)->first();
        $ter = $territory->id_territory;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        $position = DB::table('users')->select('id_position')->where('nik', $nik)->first();
        $pos = $position->id_position;

        $datas = DB::table('tb_quote')
                        ->select('id_quote','quote_number','position','type_of_letter','date','to','attention','title','project')
                        ->get();

        if ($ter != null) {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
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
        
        return view('report/quote',compact('notif','datas','notifOpen','notifsd','notiftp', 'notifClaim'));
    }

    public function donwloadExcelQuote(Request $request)
    {
    	$nama = 'Daftar Buku Admin (Quo) '.date('Y');
        Excel::create($nama, function ($excel) use ($request) {
        $excel->sheet('Quote Number', function ($sheet) use ($request) {
        
        $sheet->mergeCells('A1:O1');

        $sheet->row(1, function ($row) {
            $row->setFontFamily('Calibri');
            $row->setFontSize(11);
            $row->setAlignment('center');
            $row->setFontWeight('bold');
        });

        $sheet->row(1, array('Quote Number'));

        $sheet->row(2, function ($row) {
            $row->setFontFamily('Calibri');
            $row->setFontSize(11);
            $row->setFontWeight('bold');
        });

        $datas = Quote::join('users', 'users.nik', '=', 'tb_quote.from')
                    ->select('quote_number','position','type_of_letter', 'month', 'date', 'to', 'attention', 'title','project','description','name','division','project_id')
                    ->get();

       // $sheet->appendRow(array_keys($datas[0]));
            $sheet->row($sheet->getHighestRow(), function ($row) {
                $row->setFontWeight('bold');
            });

             $datasheet = array();
             $datasheet[0]  =   array("No", "No Quote", "Position", "Type of Letter", "Month",  "Date", "To" , "Attention", "Title", "Project", "Description", "From", "Division","Id Project");
             $i=1;

            foreach ($datas as $data) {

               // $sheet->appendrow($data);
              $datasheet[$i] = array(
                            $i,
                            $data['quote_number'],
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
                            $data['project_id'],
                        );
              
              $i++;
            }

            $sheet->fromArray($datasheet);
        });

        })->export('xls');
    }
}
