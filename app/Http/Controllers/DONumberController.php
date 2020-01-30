<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use App\DONumber;
use Illuminate\Support\Facades\Route;
use Excel;
use Validator;

class DONumberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
            $notifc = count($notif);
        }elseif ($div == 'TECHNICAL PRESALES' && $pos == 'STAFF') {
            $notif = DB::table('sales_lead_register')
            ->join('sales_solution_design', 'sales_solution_design.lead_id', '=', 'sales_lead_register.lead_id')
            ->select('sales_lead_register.opp_name','sales_solution_design.nik')
            ->where('result','OPEN')
            ->orderBy('sales_lead_register.created_at','desc')
            ->get();
            $notifc = count($notif);
        }else{
             $notif = DB::table('sales_lead_register')
            ->select('opp_name','nik')
            ->where('result','OPEN')
            ->orderBy('created_at','desc')
            ->get();
            $notifc = count($notif);
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

        $datas = DB::table('tb_do')
                        ->select('no','no_do', 'type_of_letter', 'month', 'date', 'to', 'attention', 'title', 'project', 'description', 'project_id','note')
                        ->get();

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

        return view('admin/do', compact('lead', 'total_ter','notif','notifOpen','notifsd','notiftp','id_pro', 'datas', 'notifClaim', 'notifc'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $type = 'SJ';
        // $posti = $request['position'];
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

        $getnumber = DeliveryOrder::orderBy('no', 'desc')->first();

        if($getnumber == NULL){
            $getlastnumber = 1;
            $lastnumber = $getlastnumber;
        } else{
            $lastnumber = $getnumber->no+1;
        }// } else {
        //    if($getnumber->no > 7){
        //        $query = PR::where('no', 'like', '%8')->get();
        //        foreach ($query as $compare) {
        //             if($getnumber->no == $compare->no){
        //                $lastnumber = $getnumber->no+2;
        //             } else {
        //                $lastnumber = $getnumber->no+1;
        //             }            
        //         }
        //     }
        // }

        if($lastnumber < 10){
           $akhirnomor = '000' . $lastnumber;
        }elseif($lastnumber > 9 && $lastnumber < 100){
           $akhirnomor = '00' . $lastnumber;
        }elseif($lastnumber >= 100){
           $akhirnomor = '0' . $lastnumber;
        }

        // $getnum = count($getnumber);
        // $akhirnomor = $getnum+1;
        // if ($akhirnomor < 10) {
        //     $akhirnomor = '00' . $akhirnomor;
        // } elseif ($akhirnomor > 9 && $akhirnomor < 100) {
        //     $akhirnomor = '0' . $akhirnomor;
        // } elseif ($akhirnomor >= 100) {
        //     $akhirnomor = $akhirnomor;
        // }

        $no = $akhirnomor .'/'. $type .'/' . $bln .'/'. $year_pr;

        $tambah = new DeliveryOrder();
        $tambah->no = $lastnumber;
        $tambah->no_do = $no;
        // $tambah->position = $posti;
        $tambah->type_of_letter = $type;
        $tambah->month = $bln;
        $tambah->date = $request['date'];
        $tambah->to = $request['to'];
        $tambah->attention = $request['attention'];
        $tambah->title = $request['title'];
        $tambah->project = $request['project'];
        $tambah->description = $request['description'];
        /*$tambah->from = Auth::User()->nik;
        $tambah->division = $request['division'];
        $tambah->issuance = $request['issuance'];*/
        $tambah->project_id = $request['project_id'];
        // $tambah->result = 'T';
        $tambah->save();

        return redirect('do')->with('success', 'Created Delivery Order Successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $no = $request['edit_no_do'];

        $update = DeliveryOrder::where('no',$no)->first();
        // $update->date = $request['edit_date'];
        $update->to = $request['edit_to'];
        $update->attention = $request['edit_attention'];
        $update->title = $request['edit_title'];
        $update->project = $request['edit_project'];
        $update->description = $request['edit_description'];
        $update->project_id = $request['edit_project_id'];
        $update->note = $request['edit_note'];

        $update->update();

        return redirect('do')->with('update', 'Updated Delivery Order Data Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
