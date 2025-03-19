<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Quotation;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;

class QuotationController extends Controller
{
    public function index()
    {
        $data = Quotation::all();
        return view('company_profile.message.index', compact('data'))->with(['initView'=> $this->initMenuBase()]);
    }

    public function show($id) 
    {
        $data = Quotation::find($id);

        $pembaruan = [
            'record' => 'true'
        ];

        $data->update($pembaruan);
        return response()->json($data);
    }

    public function showData() {
        $data = Quotation::latest()->get()->all();

        return response()->json($data);
    }

    public function destroy($id)
    {
        Quotation::find($id)->delete();
        return back()->with('success', 'Success delete message');
    }
}
