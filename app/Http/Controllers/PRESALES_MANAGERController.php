<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sales;

class PRESALES_MANAGERController extends Controller
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
        return view('presales/presales_manager');
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
        $this->validate($request, [
            'lead_id' => 'required',
            'contact' => 'required',
            'opp_name' => 'required',
            'closing_date' => 'required',
            'owner'   => 'required',
            'amount' => 'required'
        ]); 

        $tambah = new Sales();
        $tambah->lead_id = $request['lead_id'];
        $tambah->nik = $request['owner'];
        $tambah->id_contact = $request['contact'];
        $tambah->opp_name = $request['opp_name'];
        $tambah->closing_date = $request['closing_date'];
        $tambah->amount = $request['amount'];
        $tambah->save();

        return redirect('presales');

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
    public function update(Request $request, $id)
    {
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
}
