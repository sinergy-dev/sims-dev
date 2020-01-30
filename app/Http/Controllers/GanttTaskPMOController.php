<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\GanttTaskPmo;
use App\PMO;

class GanttTaskPMOController extends Controller
{


    public function store(Request $request, $id_pmo){

        $id_pmo = PMO::select('id_pmo')->where('id_pmo',$id_pmo)->first();
 
        $task = new GanttTaskPmo();
 
        $task->text = $request->text;
        $task->id_pmo = $id_pmo->id_pmo;
        $task->start_date = $request->start_date;
        $task->duration = $request->duration;
        $task->progress = $request->has("progress") ? $request->progress : 0;
        $task->parent = $request->parent;
 
        $task->save();
 
        return response()->json([
            "action"=> "inserted",
            "tid" => $task->id
        ]);
    }
 
    public function update($id, Request $request, $id_pmo){
        $task = GanttTaskPmo::find($id_pmo);
 
        $task->text = $request->text;
        $task->start_date = $request->start_date;
        $task->duration = $request->duration;
        $task->progress = $request->has("progress") ? $request->progress : 0;
        $task->parent = $request->parent;
 
        $task->save();
 
        return response()->json([
            "action"=> "updated"
        ]);
    }
 
    public function destroy($id, $id_pmo){
        $task = GanttTaskPmo::find($id_pmo);
        $task->delete();
 
        return response()->json([
            "action"=> "deleted"
        ]);
    }
}
