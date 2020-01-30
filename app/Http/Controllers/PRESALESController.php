<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\solution_design;
use App\Sales;
use DB;
use Auth;

class PRESALESController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $nik = Auth::User()->nik;
        $division = DB::table('users')->select('id_division')->where('nik', $nik)->first();
        $div = $division->id_division;
        if($div == 'TECHNICAL PRESALES'){
            $lead = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_contact', '=', 'tb_contact.id_contact')
                ->select('sales_lead_register.lead_id','tb_contact.name_contact', 'sales_lead_register.opp_name',
                'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name')
                ->where('id_division', $div)
                ->get();
        } else {
            $lead = DB::table('sales_lead_register')
                ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                ->join('tb_contact', 'sales_lead_register.id_contact', '=', 'tb_contact.id_contact')
                ->select('sales_lead_register.lead_id','tb_contact.name_contact', 'sales_lead_register.opp_name',
                'sales_lead_register.closing_date', 'sales_lead_register.amount', 'users.name')
                ->get();
        }
        return view('presales/presales')->with('lead', $lead);
    }

    public function detail_presales($lead_id)
    {
        $tampilkan = DB::table('sales_lead_register')
                    ->join('users', 'users.nik', '=', 'sales_lead_register.nik')
                    ->join('tb_contact', 'sales_lead_register.id_contact', '=', 'tb_contact.id_contact')
                    ->select('sales_lead_register.lead_id','sales_lead_register.nik','tb_contact.name_contact', 'sales_lead_register.opp_name',
                    'sales_lead_register.created_at', 'sales_lead_register.amount', 'users.name')
                    ->where('lead_id',$lead_id)
                    ->first();
        return view('presales/detail_presales')->with('tampilkan',$tampilkan);
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
    
        $tambah = new solution_design();
        $tambah->lead_id = $request['lead_id'];
        $tambah->nik = $request['nik'];
        $tambah->assessment = $request['assesment'];
        $tambah->pov = $request['pov'];
        $tambah->pb = $request['project_budget'];
        $tambah->priority = $request['priority'];
        $tambah->project_size = $request['proyek_size'];
        $tambah->save();

        return redirect()->to('/presales');
    }
    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($lead_id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id_sd)
    {
        $tampiledit = solution_design::where('id_sd', $id_sd)->first();
        return view('edit')->with('tampiledit', $tampiledit);  
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id_sd)
    {
        $update = solution_design::where('id_sd', $id_sd)->first();
        $update->assessment = $request['assesment'];
        $update->pov = $request['pov'];
        $update->pd = $request['propossed_design'];
        $update->pm = $request['project_management'];
        $update->ms = $request['maintenance'];
        $update->priority = $request['priority'];
        $update->project_size = $request['proyek_size'];
        $update->update();

        return redirect()->to('/presales/presales');

        //
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

    public function s_replace(){

        $s_r = DB::table('sales_lead_register')
                        ->select('lead_id')
                        ->get();

        return view('sales/sales')->with('s_r', $s_r);
    }

}
