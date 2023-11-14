<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProjectReference;
use App\PartnershipCategory;
use App\TechnologyTag;

class ProjectController extends Controller
{
    public function index()
    {
        $data = ProjectReference::all();
        return view('company_profile.project.index', compact('data'))->with(['initView'=> $this->initMenuBase()]);
    }

    public function create()
    {
        $product = TechnologyTag::all();
        return view('company_profile.project.create', compact('product'))->with(['initView'=> $this->initMenuBase()]);
    }

    public function store(Request $request)
    {
        $response = $request->all();

        $data = [
            'name' => $response['name'],
            'desc' => $response['desc'],
            'image' => $response['image'],
            'id_product' => $response['id_product'],
            'type' => $response['type'],
        ];
        
        $destination_path = 'public/images/project'; 
        $image = $request -> file('image');
        $image_name = $image->getClientOriginalName(); 
        $path = $request->file('image')->storeAs($destination_path, $image_name); 
        $data['image'] = $image_name;
        
        
        ProjectReference::create($data);
        return redirect('/project-references');
    }

    public function update(Request $request, $id)
    {
        $response = $request->all();

        $data = [
            'name' => $response['name'],
            'desc' => $response['desc'],
            'id_product' => $response['id_product'],
            'type' => $response['type'],
        ];

        if($request->hasFile('image')){
            $destination_path = 'public/images/project'; 
            $image = $request -> file('image');
            $image_name = $image->getClientOriginalName(); 
            $path = $request->file('image')->storeAs($destination_path, $image_name); 
            $data['image'] = $image_name;
        }

        ProjectReference::find($id)->update($data);
        return redirect('/project-references');
    }

    public function edit($id)
    {
        $data = ProjectReference::find($id);
        $product = TechnologyTag::all();
        return view('company_profile.project.edit', compact('data', 'product'))->with(['initView'=> $this->initMenuBase()]);
    }

    public function destroy($id)
    {
        ProjectReference::find($id)->delete();
        return back();
    }
}
