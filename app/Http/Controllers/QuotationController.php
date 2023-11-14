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

    public function store(Request $request)
    {
        $data = $request->all();

        $mail = new SendMail($data); // Membuat instance objek pesan email
        $mail->subject('Message Sinergy Website'); 
        $mail->from($data['email'], $data['name']);

        if($data['for'] = 'Quotation') {
            Mail::to('sales@sinergy.com')->send($mail);
        } else if($data['for'] = 'Career'){
            Mail::to('admin@sinergy.com')->send($mail);
        } else if ($data['for'] = 'Company Profile') {
            Mail::to('sales@sinergy.com')->send($mail);
        } else if ($data['for'] = 'Help Desk') {
            Mail::to('helpdesk@sinergy.com')->send($mail);
        }

        Quotation::create($data);        
    
        return back()->with('message', 'success send request');;        
    }

    public function show($id) 
    {
        $data = Quotation::find($id);
        return response()->json($data);
    }

    public function destroy($id)
    {
        Quotation::find($id)->delete();
        return back();
    }
}
