<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Excel;
use Auth;
use App\Letter;
use App\SalesProject;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class LetterController extends Controller
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

        $pops = letter::select('no_letter')->where('status','A')->orderBy('no','desc')->first();

        $pops2 = Letter::select('no_letter')->where('status', 'F')->orderBy('updated_at', 'desc')->first();

        $tahun = date("Y");

        // $backdate_num = Quote::select('quote_number','id_quote')->where('status_backdate', 'T')->where('date','like',$tahun."%")->get();
        $backdate_num = Letter::select('no_letter','no')->where('status', 'T')->whereYear('created_at', $tahun)->orderBy('created_at','asc')->get();

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


        $datas = DB::table('tb_letter')
                        ->join('users', 'users.nik', '=', 'tb_letter.nik')
                        ->select('no_letter', 'position', 'type_of_letter', 'month', 'date', 'to', 'attention', 'title', 'project', 'description', 'division', 'project_id', 'status', 'note', 'name', 'tb_letter.nik')
                        ->where('status',NULL)
                        // ->orwhere('status', 'F')
                        ->where('date','like',$tahun."%")
                        ->get();

        $data_backdate = DB::table('tb_letter')
                        ->join('users', 'users.nik', '=', 'tb_letter.nik')
                        ->select('no_letter', 'position', 'type_of_letter', 'month', 'date', 'to', 'attention', 'title', 'project', 'description', 'division', 'project_id', 'status', 'note', 'name', 'tb_letter.nik')
                        // ->where('status',NULL)
                        ->where('status', 'F')
                        ->get();

        $status_letter = Letter::select('status')->where('status', '!=', 'T')->groupBy('status')->get();

        $count = DB::table('tb_letter')
                    ->where('status', 'T')
                    ->get();

        $counts = count($count);

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
        }  else{
            $notifClaim = DB::table('dvg_esm')
                            ->select('nik_admin', 'personnel', 'type')
                            ->where('status', 'FINANCE')
                            ->get();
        }

        $sidebar_collapse = true;

        $year_before = Letter::select(DB::raw('YEAR(created_at) year'))->orderBy('year','desc')->groupBy('year')->get();

        $pid = SalesProject::join('sales_lead_register', 'sales_lead_register.lead_id', '=', 'tb_id_project.lead_id')->join('users', 'users.nik', '=', 'sales_lead_register.nik')->select('id_project')->where('id_company', '1')->get();

        return view('admin/letter', compact('notif','notifOpen','notifsd','notiftp', 'datas', 'notifClaim','counts','pops', 'pops2','backdate_num', 'data_backdate', 'sidebar_collapse', 'status_letter','year_before','tahun', 'pid'))->with(['initView'=> $this->initMenuBase()]);
	}

    public function getdataletter(Request $request)
    {
        $tahun = date("Y"); 

        return array("data" => Letter::join('users', 'users.nik', '=', 'tb_letter.nik')
                        ->select('no_letter', 'position', 'type_of_letter', 'month', 'date', 'to', 'attention', 'title', 'project', 'description', 'division', 'project_id', 'status', 'note', 'name', 'tb_letter.nik', 'no')
                        ->where('status','A')
                        ->where('date','like',$tahun."%")
                        ->get());
    }

    public function getfilteryear(Request $request)
    {
        return array("data" => Letter::join('users', 'users.nik', '=', 'tb_letter.nik')
                        ->select('no_letter', 'position', 'type_of_letter', 'month', 'date', 'to', 'attention', 'title', 'project', 'description', 'division', 'project_id', 'status', 'note', 'name', 'tb_letter.nik', 'no')
                        ->where('status', $request->status)
                        ->where('date','like',$request->year."%")
                        ->get());
    }

	public function store(Request $request)
    {
        // $getno = Letter::orderBy('no', 'asc')->first();
        
        $tahun = date("Y");
        $cek = DB::table('tb_letter')
                ->whereYear('created_at', $tahun)
                ->count('no');

        $edate = strtotime($_POST['date']); 
        $edate = date("Y-m-d",$edate);

        $month_pr = substr($edate,5,2);
        $year_pr = substr($edate,0,4);

        // $getno_new = $getno->no;
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

        $type = $request['type'];
        $posti = $request['position'];        

        if ($cek > 0) {
            $letters = Letter::where('status','A')->orderBy('no','desc')->whereYear('created_at',$tahun)->first();

            if (isset($letters)) {
                $letters = $letters->no_letter;

                $getnumber =  explode("/",$letters)[0];
            }else{
                $getnumber =  0;
            }

            $nom = Letter::select('no')->orderBy('created_at','desc')->whereYear('created_at', $tahun)->first()->no;

            $skipNum = Letter::select('no_letter')->orderBy('created_at','desc')->first();

            $lastnumber = $getnumber+1;

            $lastnumber9 = $getnumber+2;

            if($lastnumber < 10){
               $akhirnomor  = '000' . $lastnumber;
               $akhirnomor9 = '00' . $lastnumber9;
            }elseif($lastnumber > 9 && $lastnumber < 100){
               $akhirnomor = '00' . $lastnumber;
               $akhirnomor9 = '00' . $lastnumber9;
            }elseif($lastnumber >= 100 && $lastnumber < 1000){
               $akhirnomor = '0' . $lastnumber;
               $akhirnomor9 = '0' . $lastnumber9;
            } elseif ($lastnumber >= 1000) {
                $akhirnomor = $lastnumber;
                $akhirnomor9 = $lastnumber9;
            }      

            if (substr($getnumber, -1) == '4') {
                $no   = $akhirnomor9.'/'.$posti .'/'. $type.'/' . $bln .'/'. $year_pr;

                $no9  = $akhirnomor;

                if (Letter::where('no_letter', '=', $no9)->exists()) {
                    $tambah = new Letter();
                        
                    $tambah->no_letter = $no;
                    $tambah->status = 'A';
                    $tambah->position = $posti;
                    $tambah->type_of_letter = $type;
                    $tambah->month = $bln;
                    $tambah->date = $edate;
                    $tambah->to = $request['to'];
                    $tambah->attention = $request['attention'];
                    $tambah->title = $request['title'];
                    $tambah->project = $request['project'];
                    $tambah->description = $request['description'];
                    $tambah->nik = Auth::User()->nik;
                    $tambah->division = $request['division'];
                    $tambah->project_id = $request['project_id'];

                    $tambah->save();
                }else{
                    for ($i=0; $i < 2 ; $i++) { 
                        $tambah = new Letter();
                        
                        if ($i == 0) {
                            // $tambah->no = $nom+1;
                            $tambah->no_letter = $no9;
                            $tambah->status = 'T';
                        }else{
                            // $tambah->no = $nom+2;
                            $tambah->no_letter = $no;
                            $tambah->status = 'A';
                        }
                        $tambah->position = $posti;
                        $tambah->type_of_letter = $type;
                        $tambah->month = $bln;
                        $tambah->date = $edate;
                        $tambah->to = $request['to'];
                        $tambah->attention = $request['attention'];
                        $tambah->title = $request['title'];
                        $tambah->project = $request['project'];
                        $tambah->description = $request['description'];
                        $tambah->nik = Auth::User()->nik;
                        $tambah->division = $request['division'];
                        $tambah->project_id = $request['project_id'];

                        $tambah->save();
                    }
                }

            }else {
                $no   = $akhirnomor.'/'.$posti .'/'. $type.'/' . $bln .'/'. $year_pr;

                $tambah = new Letter();
                $tambah->no_letter = $no;
                $tambah->position = $posti;
                $tambah->type_of_letter = $type;
                $tambah->month = $bln;
                $tambah->date = $edate;
                $tambah->to = $request['to'];
                $tambah->attention = $request['attention'];
                $tambah->title = $request['title'];
                $tambah->project = $request['project'];
                $tambah->description = $request['description'];
                $tambah->nik = Auth::User()->nik;
                $tambah->status = 'A';
                $tambah->division = $request['division'];
                $tambah->project_id = $request['project_id'];
                $tambah->save();  
            }

            // $getno = Letter::orderBy('no', 'desc')->first();
            // $getno_new = $getno->no;

                // if ($getno_new < 7) {
                //     $angka = '7';
                // }
                // elseif ($getno_new > 6) {
                //         $query = Letter::where('no','like','%7')->get();
                //         foreach ($query as $data) {
                //              if ($getno_new == $data->no) {
                //                  $angka = $data->no;
                //              }else{
                //                  $angka = $data->no;
                //              }
                //         }
                // }

                // if ($getno_new == $angka) {   
                //     $bln = $array_bln[$month_pr];

                //     if($getnumber == NULL){
                //         $getlastnumber = 1;
                //         $lastnumber  = $getlastnumber;
                //     } else{
                //         $lastnumber = $getnumber+1;
                //         $lastnumber9 = $getnumber+2;
                //     }
                    

                //     if($lastnumber < 10){
                //        $akhirnomor  = '000' . $lastnumber;
                //        $akhirnomor9 = '000' . $lastnumber9;
                //     }elseif($lastnumber > 9 && $lastnumber < 100){
                //        $akhirnomor = '00' . $lastnumber;
                //        $akhirnomor9 = '00' . $lastnumber9;
                //     }elseif($lastnumber >= 100){
                //        $akhirnomor = '0' . $lastnumber;
                //        $akhirnomor9 = '0' . $lastnumber9;
                //     }

                //     $no   = $akhirnomor.'/'.$posti .'/'. $type.'/' . $bln .'/'. $year_pr;
                //     $no9  = $akhirnomor9;

                //     //double check skip number
                //     $allNoLetter = Letter::where('status','T')->whereYear('created_at',$tahun)->get();
                //     foreach ($allNoLetter as $NewNoLetter) {
                //         $NewNoLetters = substr($NewNoLetter->no_letter, -1);
                //         if ($NewNoLetters == $no9) {
                //             // $skipNumber = 1;
                //             $status = 'sameNumber';
                //         }else{
                //             // $skipNumber = 2;
                //             $status = 'notSame';
                //         }

                //     }   

                //     if ($status == 'sameNumber') {
                //         for ($i=0; $i < 2 ; $i++) { 
                //             $tambah = new Letter();
                            
                //             if ($i == 0) {
                //                 // $tambah->no = $nom+1;
                //                 $tambah->no_letter = $no;
                //                 $tambah->status = 'A';
                //             }else{
                //                 // $tambah->no = $nom+2;
                //                 $tambah->no_letter = $no9;
                //                 $tambah->status = 'T';
                //             }
                //             $tambah->position = $posti;
                //             $tambah->type_of_letter = $type;
                //             $tambah->month = $bln;
                //             $tambah->date = $request['date'];
                //             $tambah->to = $request['to'];
                //             $tambah->attention = $request['attention'];
                //             $tambah->title = $request['title'];
                //             $tambah->project = $request['project'];
                //             $tambah->description = $request['description'];
                //             $tambah->nik = Auth::User()->nik;
                //             $tambah->division = $request['division'];
                //             $tambah->project_id = $request['project_id'];

                //             $tambah->save();
                //         }
                //     }else{
                //         $tambah = new Letter();
                            
                //         $tambah->no_letter = $no;
                //         $tambah->status = 'A';
                //         $tambah->position = $posti;
                //         $tambah->type_of_letter = $type;
                //         $tambah->month = $bln;
                //         $tambah->date = $request['date'];
                //         $tambah->to = $request['to'];
                //         $tambah->attention = $request['attention'];
                //         $tambah->title = $request['title'];
                //         $tambah->project = $request['project'];
                //         $tambah->description = $request['description'];
                //         $tambah->nik = Auth::User()->nik;
                //         $tambah->division = $request['division'];
                //         $tambah->project_id = $request['project_id'];

                //         $tambah->save();
                //     }

                // }else{
                //     $bln = $array_bln[$month_pr];

                //     if($getnumber == NULL){
                //         $getlastnumber = 1;
                //         $lastnumber = $getlastnumber;
                //     } else{
                //         $lastnumber = $getnumber+1;
                //     }

                //     if($lastnumber < 10){
                //        $akhirnomor = '000' . $lastnumber;
                //     }elseif($lastnumber > 9 && $lastnumber < 100){
                //        $akhirnomor = '00' . $lastnumber;
                //     }elseif($lastnumber >= 100){
                //        $akhirnomor = '0' . $lastnumber;
                //     }

                //     $no = $akhirnomor.'/'.$posti .'/'. $type.'/' . $bln .'/'. $year_pr;

                //     $tambah = new Letter();
                //     // $tambah->no = $nom+1;
                //     $tambah->no_letter = $no;
                //     $tambah->position = $posti;
                //     $tambah->type_of_letter = $type;
                //     $tambah->month = $bln;
                //     $tambah->date = $request['date'];
                //     $tambah->to = $request['to'];
                //     $tambah->attention = $request['attention'];
                //     $tambah->title = $request['title'];
                //     $tambah->project = $request['project'];
                //     $tambah->description = $request['description'];
                //     $tambah->nik = Auth::User()->nik;
                //     $tambah->status = 'A';
                //     // $tambah->from = $request['from'];
                //     $tambah->division = $request['division'];
                //     $tambah->project_id = $request['project_id'];
                //     $tambah->save();                        
                // }
            
        } else{
            $getlastnumber = 1;
            $lastnumber = $getlastnumber;

            if($lastnumber < 10){
               $akhirnomor = '000' . $lastnumber;
            }elseif($lastnumber > 9 && $lastnumber < 100){
               $akhirnomor = '00' . $lastnumber;
            }elseif($lastnumber >= 100 && $lastnumber < 1000){
               $akhirnomor = '0' . $lastnumber;
            } elseif ($lastnumber >= 1000) {
                $akhirnomor = $lastnumber;
            }

            $noReset = $akhirnomor.'/'.$posti .'/'. $type.'/' . $bln .'/'. $year_pr;

            $tambah = new Letter();
            $tambah->no_letter = $noReset;
            $tambah->position = $posti;
            $tambah->type_of_letter = $type;
            $tambah->month = $bln;
            $tambah->date = $edate;
            $tambah->to = $request['to'];
            $tambah->attention = $request['attention'];
            $tambah->title = $request['title'];
            $tambah->project = $request['project'];
            $tambah->description = $request['description'];
            $tambah->nik = Auth::User()->nik;
            $tambah->status = 'A';
            $tambah->division = $request['division'];
            $tambah->project_id = $request['project_id'];
            $tambah->save();            
        }

        return redirect('letter')->with('success', 'Create Letter Successfully!');
    }

	public function edit(Request $request)
	{
		$no = $request['edit_no_letter'];

        $type = $request['edit_type'];
        $posti = $request['edit_position'];

        $edate = strtotime($_POST['edit_date']); 
        $edate = date("Y-m-d",$edate);

        $month_letter = substr($edate,5,2);
        $year_letter = substr($edate,0,4);

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
        $bln = $array_bln[$month_letter];

        $getno = Letter::where('no_letter', $no)->first()->no_letter;
        $getnumberLetter =  explode("/",$getno)[0];

        $no_update = $getnumberLetter.'/'.$posti .'/'. $type.'/' . $bln .'/'. $year_letter;

        $update = Letter::where('no_letter',$no)->first();
        $update->to = $request['edit_to'];
        $update->no_letter = $no_update;
        $update->position = $posti;
        $update->type_of_letter = $type;
        $update->month = $bln;
        $update->date = $edate;
        $update->attention = $request['edit_attention'];
        $update->title = $request['edit_title'];
        $update->project = $request['edit_project'];
        $update->description = $request['edit_description'];
        // $update->from = $request['edit_from'];
        $update->project_id = $request['edit_project_id'];
        $update->note = $request['edit_note'];

        $update->update();

        return redirect('letter')->with('update', 'Updated Letter Data Successfully!');
	}

	public function destroy($no)
	{
		$hapus = Letter::find($no);
        $hapus->delete();

        return redirect('letter')->with('alert', 'Deleted!');
	}

    public function get_backdate_num(Request $request)
    {
        if (isset($request->tanggal)) {
            $backdate_num = Letter::selectRaw('`no_letter` as `text`')->selectRaw('`no` as `id`')->where('status', 'T')->whereYear('created_at',substr($request->tanggal, 6,4))->orderBy('created_at','asc')->get();
            return array('results'=>$backdate_num);
        } else {
            $backdate_num = Letter::selectRaw('`no_letter` as `text`')->selectRaw('`no` as `id`')->where('status', 'T')->orderBy('created_at','asc')->get();
            return array('results'=>$backdate_num);
        }
        
    }

    public function addBackdateNum(Request $request)
    {
        $lastnumber = Letter::whereYear('created_at',substr($request->date_backdate, 6,4))->where('status', 'F')->orderBy('no', 'desc')->first();

        if (isset($lastnumber)) {
            $lastnumber = $lastnumber->no_letter;
            $getletter =  explode("/",$lastnumber)[0];

            $getnumber = $getletter + 10;
        }else{
            $getnumber = 10; 
        }
        // return $lastnumber;

        if($getnumber < 10){
           $akhirnomor  = '000' . $getnumber;
        }elseif($getnumber > 9 && $getnumber < 100){
           $akhirnomor = '00' . $getnumber;
        }elseif($getnumber >= 100 && $getnumber < 1000){
           $akhirnomor = '0' . $getnumber;
        } elseif ($getnumber >= 1000) {
            $akhirnomor = $getnumber;
        }

        $edate = strtotime($_POST['date_backdate']); 
        $edate = date("Y-m-d",$edate);

        $tambah = new Letter();
        $tambah->no_letter = $akhirnomor;
        $tambah->status = 'T';
        $tambah->position = 'DIR';
        $tambah->type_of_letter = 'QO';
        $tambah->month = 'II';
        $tambah->date = $edate;
        $tambah->created_at = $edate . '00:00:00';
        $tambah->nik = Auth::User()->nik;
        $tambah->save();
    }

	public function downloadExcel(Request $request) {
        $spreadsheet = new Spreadsheet();

        $prSheet = new Worksheet($spreadsheet,'Nomor Letter');
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

        $sheet->getStyle('A1:N1')->applyFromArray($titleStyle);
        $sheet->setCellValue('A1','Nomor Letter');

        $headerStyle = $normalStyle;
        $headerStyle['font']['bold'] = true;
        $sheet->getStyle('A2:N2')->applyFromArray($headerStyle);;

        $headerContent = ["No", "No Letter", "Position", "Type of Letter", "Month",  "Date", "To", "Attention", "Title", "Project", "Description", "From", "Division", "Project ID"];
        $sheet->fromArray($headerContent,NULL,'A2');

        $dataLetter = Letter::select('no_letter','position','type_of_letter','month','date','to','attention','title','project','description','from','division','project_id')
            ->whereYear('tb_letter.date', $request->year)
            ->get();

        $dataLetter->map(function($item,$key) use ($sheet){
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

        $fileName = 'Daftar Buku Admin (Letter) ' . date('Y') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        
        $writer = new Xlsx($spreadsheet);
        ob_end_clean();
        return $writer->save("php://output");


		// $nama = 'Daftar Buku Admin (Letter) '.date('Y');
        Excel::create($nama, function ($excel) use ($request) {
        $excel->sheet('Nomor Letter', function ($sheet) use ($request) {
        
        $sheet->mergeCells('A1:O1');

       // $sheet->setAllBorders('thin');
        $sheet->row(1, function ($row) {
            $row->setFontFamily('Calibri');
            $row->setFontSize(11);
            $row->setAlignment('center');
            $row->setFontWeight('bold');
        });

        $sheet->row(1, array('Nomor Letter'));

        $sheet->row(2, function ($row) {
            $row->setFontFamily('Calibri');
            $row->setFontSize(11);
            $row->setFontWeight('bold');
        });

        $datas = Letter::select('no_letter','position','type_of_letter', 'month', 'date', 'to', 'attention', 'title','project','description','from','division','project_id')
                    ->get();

       // $sheet->appendRow(array_keys($datas[0]));
            $sheet->row($sheet->getHighestRow(), function ($row) {
                $row->setFontWeight('bold');
            });

             $datasheet = array();
             $datasheet[0]  =   array("No", "No Letter", "Position", "Type of Letter", "Month",  "Date", "To", "Attention", "Title", "Project", "Description", "From", "Division", "Project ID");
             $i=1;

            foreach ($datas as $data) {

               // $sheet->appendrow($data);
              $datasheet[$i] = array($i,
                            $data['no_letter'],
                            $data['position'],
                            $data['type_of_letter'],
                            $data['month'],
                            $data['date'],
                            $data['to'],
                            $data['attention'],
                            $data['title'],
                            $data['project'],
                            $data['description'],
                            $data['from'],
                            $data['division'],
                            $data['project_id'],
                        );
              
              $i++;
            }

            $sheet->fromArray($datasheet);
        });

        })->export('xls');
	}

    public function store_backdate(Request $request)
    {
        $type = $request['type'];
        $posti = $request['position'];

        $edate = strtotime($_POST['date']); 
        $edate = date("Y-m-d",$edate);
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

        // $query = Letter::select('no')
        //                 ->where('status','T')
        //                 ->orderBy('no','asc')
        //                 ->first();
        
        // $lastnumber = $query->no;

        // if($lastnumber < 10){
        //    $akhirnomor = '000' . $lastnumber;
        // }elseif($lastnumber > 9 && $lastnumber < 100){
        //    $akhirnomor = '00' . $lastnumber;
        // }elseif($lastnumber >= 100){
        //    $akhirnomor = '0' . $lastnumber;
        // }

        // $akhirnomor = $request['backdate_num'];

        $update = Letter::where('no',$request['backdate_num'])->first();
        $no = $update->no_letter . '/' . $posti . '/' . $type . '/' . $bln . '/' . $year_pr;

        // $angka7 = Letter::select('no')
        //         ->where('status','T')
        //         ->orderBy('no','asc')
        //         ->first();
        // $angka = $angka7->no;

        $update->no_letter = $no;
        $update->position = $posti;
        $update->type_of_letter = $type;
        $update->month = $bln;
        $update->date = $edate;
        $update->to = $request['to'];
        $update->attention = $request['attention'];
        $update->title = $request['title'];
        $update->project = $request['project'];
        $update->description = $request['description'];
        $update->nik = Auth::User()->nik;
        // $update->from = $request['from'];
        $update->division = $request['division'];
        $update->project_id = $request['project_id_backdate'];
        $update->status = 'F';
        $update->update();

        return redirect('letter')->with('sukses', 'Create Letter Successfully!');
    }
}
