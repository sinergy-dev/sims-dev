<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\NewsTag;

class TagController extends Controller
{
    public function index()
    {
        $data = NewsTag::all();
        return view('company_profile.insights.tag', compact('data'))->with(['initView'=> $this->initMenuBase()]);
    }

    public function edit($id)
    {
        $data = NewsTag::find($id);
        return response()->json($data);
    }

    public function update(request $request, $id)
    {
        NewsTag::find($id)->update($request->all());
        return back();
    }

    public function destroy($id)
    {
        NewsTag::find($id)->delete();
        return back();
    }
}
