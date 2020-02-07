<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Excel;
use Auth;
use App\Letter;

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

        $pops = letter::select('no_letter')->where('status',NULL)->orderBy('created_at','desc')->first();

        $pops2 = Letter::select('no_letter')->where('status', 'F')->orderBy('updated_at', 'desc')->first();

        $tahun = date("Y");

        // $backdate_num = Quote::select('quote_number','id_quote')->where('status_backdate', 'T')->where('date','like',$tahun."%")->get();
        $backdate_num = Letter::select('no_letter','no')->where('status', 'T')->get();

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
                        ->select('no','no_letter', 'position', 'type_of_letter', 'month', 'date', 'to', 'attention', 'title', 'project', 'description', 'from', 'division', 'project_id', 'status', 'note', 'name', 'tb_letter.nik')
                        ->where('status',NULL)
                        // ->orwhere('status', 'F')
                        ->where('date','like',$tahun."%")
                        ->get();

        $data_backdate = DB::table('tb_letter')
                        ->join('users', 'users.nik', '=', 'tb_letter.nik')
                        ->select('no','no_letter', 'position', 'type_of_letter', 'month', 'date', 'to', 'attention', 'title', 'project', 'description', 'from', 'division', 'project_id', 'status', 'note', 'name', 'tb_letter.nik')
                        // ->where('status',NULL)
                        ->where('status', 'F')
                        ->get();

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
        }

        $sidebar_collapse = true;


        return view('admin/letter', compact('lead', 'total_ter','notif','notifOpen','notifsd','notiftp','id_pro', 'datas', 'notifClaim','counts','pops', 'pops2','backdate_num', 'data_backdate', 'sidebar_collapse'));
	}

	public function store(Request $request)
    {
        // $getno = Letter::orderBy('no', 'asc')->first();
        
        $tahun = date("Y");
        $cek = DB::table('tb_letter')
                ->whereYear('created_at', $tahun)
                ->count('no');

        // $getno_new = $getno->no;

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
                     
                    $type = $request['type'];
                    $posti = $request['position'];
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

                    $getnumber = Letter::orderBy('no', 'desc')->whereYear('created_at', $tahun)->count();

                    $getnumbers = Letter::orderBy('no', 'desc')->first();

                    if($getnumber == NULL){
                        $getlastnumber = 1;
                        $lastnumber  = $getlastnumber;
                    } else{
                        $lastnumber = $getnumber+1;
                        $lastnumber9 = $getnumber+2;
                    }

                    if($lastnumber < 10){
                       $akhirnomor  = '000' . $lastnumber;
                       $akhirnomor9 = '000' . $lastnumber9;
                    }elseif($lastnumber > 9 && $lastnumber < 100){
                       $akhirnomor = '00' . $lastnumber;
                       $akhirnomor9 = '00' . $lastnumber9;
                    }elseif($lastnumber >= 100){
                       $akhirnomor = '0' . $lastnumber;
                       $akhirnomor9 = '0' . $lastnumber9;
                    }

                    $no   = $akhirnomor.'/'.$posti .'/'. $type.'/' . $bln .'/'. $year_pr;
                    $no9  = $akhirnomor9;


                    $nom = Letter::select('no')->orderBy('created_at','desc')->first();

                    for ($i=0; $i < 2 ; $i++) { 
                        $tambah = new Letter();
                        
                        if ($i == 0) {
                            $tambah->no = $nom->no+1;
                            $tambah->no_letter = $no;
                            $tambah->status = NULL;
                        }else{
                            $tambah->no = $nom->no+2;
                            $tambah->no_letter = $no9;
                            $tambah->status = 'T';
                        }
                        $tambah->position = $posti;
                        $tambah->type_of_letter = $type;
                        $tambah->month = $bln;
                        $tambah->date = $request['date'];
                        $tambah->to = $request['to'];
                        $tambah->attention = $request['attention'];
                        $tambah->title = $request['title'];
                        $tambah->project = $request['project'];
                        $tambah->description = $request['description'];
                        // $tambah->from = $request['from'];
                        $tambah->nik = Auth::User()->nik;
                        $tambah->division = $request['division'];
                        $tambah->project_id = $request['project_id'];

                        $tambah->save();
                    }

                    return redirect('letter')->with('success', 'Create Letter Successfully!');
                }else{
                    $type = $request['type'];
                    $posti = $request['position'];
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

                    $getnumber = Letter::orderBy('no', 'desc')->whereYear('created_at', $tahun)->count();

                    $getnumbers = Letter::orderBy('no', 'desc')->first();

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

                    $no = $akhirnomor.'/'.$posti .'/'. $type.'/' . $bln .'/'. $year_pr;

                    $tambah = new Letter();
                    $tambah->no = $getnumbers->no+1;
                    $tambah->no_letter = $no;
                    $tambah->position = $posti;
                    $tambah->type_of_letter = $type;
                    $tambah->month = $bln;
                    $tambah->date = $request['date'];
                    $tambah->to = $request['to'];
                    $tambah->attention = $request['attention'];
                    $tambah->title = $request['title'];
                    $tambah->project = $request['project'];
                    $tambah->description = $request['description'];
                    $tambah->nik = Auth::User()->nik;
                    // $tambah->from = $request['from'];
                    $tambah->division = $request['division'];
                    $tambah->project_id = $request['project_id'];
                    $tambah->save();

                    return redirect('letter')->with('success', 'Create Letter Successfully!');
                        
                }
            
        } else{
            $type = $request['type'];
            $posti = $request['position'];
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

            $getnumber = Letter::orderBy('no', 'desc')->whereYear('created_at', $tahun)->count();

            $getnumbers = Letter::orderBy('no', 'desc')->first();

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
            $tambah->no = $getnumbers->no+1;
            $tambah->no_letter = $no;
            $tambah->position = $posti;
            $tambah->type_of_letter = $type;
            $tambah->month = $bln;
            $tambah->date = $request['date'];
            $tambah->to = $request['to'];
            $tambah->attention = $request['attention'];
            $tambah->title = $request['title'];
            $tambah->project = $request['project'];
            $tambah->description = $request['description'];
            // $tambah->from = $request['from'];
            $tambah->nik = Auth::User()->nik;
            $tambah->division = $request['division'];
            $tambah->project_id = $request['project_id'];
            $tambah->save();

            return redirect('letter')->with('success', 'Create Letter Successfully!');
        }
    }

	public function edit(Request $request)
	{
		$no = $request['edit_no_letter'];
        $position = $request['edit_position'];
        $type_of_letter = $request['edit_type'];
        $month_pr = substr($request['edit_date'],5,2);
        $year_pr = substr($request['edit_date'],0,4);

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

        if($no < 10){
           $akhirnomor = '000' . $no;
        }elseif($no > 9 && $no < 100){
           $akhirnomor = '00' . $no;
        }elseif($no >= 100){
           $akhirnomor = '0' . $no;
        }

        $no = $akhirnomor.'/'.$position .'/'. $type_of_letter.'/' . $bln .'/'. $year_pr;

        $update = Letter::where('no',$no)->first();
        $update->to = $request['edit_to'];
        $update->date = $request['edit_date'];
        $update->position = $position;
        $update->type_of_letter = $type_of_letter;
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

	public function downloadExcel(Request $request)
	{
		$nama = 'Daftar Buku Admin (Letter) '.date('Y');
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

        $akhirnomor = $request['backdate_num'];

        $no = $akhirnomor . '/' . $posti . '/' . $type . '/' . $bln . '/' . $year_pr;

        $angka7 = Letter::select('no')
                ->where('status','T')
                ->orderBy('no','asc')
                ->first();
        $angka = $angka7->no;

        $update = Letter::where('no_letter',$request['backdate_num'])->first();
        $update->no_letter = $no;
        $update->position = $posti;
        $update->type_of_letter = $type;
        $update->month = $bln;
        $update->date = $request['date'];
        $update->to = $request['to'];
        $update->attention = $request['attention'];
        $update->title = $request['title'];
        $update->project = $request['project'];
        $update->description = $request['description'];
        $update->nik = Auth::User()->nik;
        // $update->from = $request['from'];
        $update->division = $request['division'];
        $update->project_id = $request['project_id'];
        $update->status = 'F';
        $update->update();

        return redirect('letter')->with('sukses', 'Create Letter Successfully!');
    }
}
