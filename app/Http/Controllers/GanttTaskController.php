<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\GanttTask;
use App\Imp;

class GanttTaskController extends Controller
{
    public function store(Request $request, $id){

        $id = Imp::select('id')->where('id',$id)->first();
 
        $task = new GanttTask();
 
        $task->text = $request->text;
        $task->id_imp = $id->id;
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
 
    public function update($id, Request $request, $id_imp){
        $task = GanttTask::find($id_imp);
 
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
 
    public function destroy($id, $id_imp){
        $task = GanttTask::find($id_imp);
        $task->delete();
 
        return response()->json([
            "action"=> "deleted"
        ]);
    }
}
