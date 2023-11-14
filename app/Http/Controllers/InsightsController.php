<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Insights;
use App\TechnologyTag;
use App\NewsTag;
use App\NewsTagConnector;
use App\BlogTag;

class InsightsController extends Controller
{
    public function index()
    {
        $insights = Insights::all();
        return view('company_profile.insights.index', compact('insights'))->with(['initView'=> $this->initMenuBase()]);
    }

    public function create()
    {
        $tag = NewsTag::all();
        $product = TechnologyTag::all();
        return view('company_profile.insights.create', compact('tag', 'product'))->with(['initView'=> $this->initMenuBase()]);
    }

    public function edit($id)
    {
        $data = Insights::find($id);
        $product = TechnologyTag::all();
        $tag2 = BlogTag::where('id_insights', $id)->get()->all();
        $tag = NewsTag::all();
        $connector = NewsTagConnector::where('id_insights', $id)->get()->all();
        return view('company_profile.insights.edit', compact('data', 'product', 'tag', 'tag2', 'connector'))->with(['initView'=> $this->initMenuBase()]);
    }

    public function store(Request $request)
    {
        $data = $request->all();;
            $data_all = [
                'title' => $data['title'],
                'image' => $data['image'],
                'type' => $data['type'],
                'paragraph' => $data['paragraph'],
            ];

            $destination_path = 'public/images/insights'; 
            $image = $request -> file('image');
            $image_name = $image->getClientOriginalName(); 
            $path = $request->file('image')->storeAs($destination_path, $image_name); 
            $data_all['image'] = $image_name;

        $insights = Insights::create($data_all);

        if($data['type'] == 'blog') {
            foreach ($data['id_product'] as $item => $value) {
                $data_product = array(
                    'id_product' => $data['id_product'][$item],
                    'id_insights' => $insights->id,
                );
                BlogTag::create($data_product);
            };
        } else if($data['type'] == 'news') {
            foreach ($data['tag_name'] as $item => $value) {
                $data_tag = array(
                    'tag_name' => $data['tag_name'][$item],
                );
                $tag_insert = NewsTag::firstOrCreate($data_tag);

                $connector = array(
                    'id_tag' => $tag_insert->id,
                    'id_insights' => $insights->id,
                );

                NewsTagConnector::create($connector);
            };
        }

        return redirect('insights');
    }

    public function ckstore(Request $request)
    {

            $destination_path = 'public/images/ckeditor'; 
            $image = $request -> file('upload');
            $fileName = $image->getClientOriginalName(); 
            $path = $request->file('upload')->storeAs($destination_path, $fileName); 
            $data_all['upload'] = $fileName;

            $url = asset('storage/images/ckeditor/'. $fileName);

            return response()->json(['fileName' => $fileName, 'uploaded' => 1, 'url' => $url]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();;

        if($request->image){
            $data_all = [
                'title' => $data['title'],
                'image' => $data['image'],
                'type' => $data['type'],
                'paragraph' => $data['paragraph'],
            ];
            
            $destination_path = 'public/images/insights'; 
            $image = $request -> file('image');
            $image_name = $image->getClientOriginalName(); 
            $path = $request->file('image')->storeAs($destination_path, $image_name); 
            $data_all['image'] = $image_name;
            
        }else {
            $data_all = [
                'title' => $data['title'],
                'type' => $data['type'],
                'paragraph' => $data['paragraph'],
            ];
        }

        
        if($request->type == 'Blog'){
            NewsTagConnector::where('id_insights', $id)->delete();
            BlogTag::where('id_insights', $id)->delete();
            foreach ($data['id_product'] as $item => $value) {
                $data_product = array(
                    'id_product' => $data['id_product'][$item],
                    'id_insights' => $id,
                );
                BlogTag::create($data_product);
            };
        }else if($request->type == 'News') {
            BlogTag::where('id_insights', $id)->delete();
            NewsTagConnector::where('id_insights', $id)->delete();
            
            foreach ($data['tag_name'] as $item => $value) {
                $data_tag = array(
                    'tag_name' => $data['tag_name'][$item],
                );
                $tag_insert = NewsTag::firstOrCreate($data_tag);
                $connector = array(
                    'id_tag' => $tag_insert->id,
                    'id_insights' => $id,
                );
                NewsTagConnector::where('id_insights', $id)->create($connector);
            };           
           
        }

            Insights::find($id)->update($data_all);
            return redirect('insights');
    }

    public function destroy($id)
    {
        Insights::find($id)->delete();
        return back();
    }
}
