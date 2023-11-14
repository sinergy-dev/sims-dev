<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Career;
use App\JobRegist;


class CareerController extends Controller
{
    protected function index() 
    {
        $data = Career::all();
        return view('company_profile.career.index', compact('data'))->with(['initView'=> $this->initMenuBase()]);
    }

    protected function register()
    {
        $data = JobRegist::all();
        return view('company_profile.career.register', compact('data'))->with(['initView'=> $this->initMenuBase()]);
    }

    protected function register_destroy($id)
    {
        JobRegist::find($id)->delete();
        return back();
    }
    
    protected function store(Request $request) 
    {
        Career::create($request->all());
        return back();
    }

    protected function edit($id)
    {
        $data = Career::find($id);
        return view('company_profile.career.edit', compact('data'))->with(['initView'=> $this->initMenuBase()]);
    }

    protected function update(Request $request, $id)
    {
        Career::find($id)->update($request->all());
        return redirect('/career');
    }

    protected function destroy($id) 
    {
        Career::find($id)->delete();
        return back();
    }
}
