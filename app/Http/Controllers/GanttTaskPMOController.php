<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\GanttTaskPmo;
use App\PMO;
use Carbon\Carbon;

class GanttTaskPMOController extends Controller
{


    public function store(Request $request){

        $id_pmo = GanttTaskPmo::select('id_pmo')->where('id',$request->parent)->first();
 
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
 
    public function update($id, Request $request){
        $task = GanttTaskPmo::find($id);
 
        $task->text = $request->text;
        $task->start_date = $request->start_date;
        $task->end_date = $request->end_date;
        $task->baseline_start = $request->baseline_start;
        $task->baseline_end = $request->baseline_end;
        $task->duration = $request->duration;
        $task->progress = $request->has("progress") ? $request->progress : 0;
        $task->parent = $request->parent;
 
        $task->save();
 
        return response()->json([
            "action"=> "updated"
        ]);
    }
 
    public function destroy($id){
        $task = GanttTaskPmo::find($id);
        $task->delete();
 
        return response()->json([
            "action"=> "deleted"
        ]);
    }

    public function createBaseline(Request $request){
        $getTask = GanttTaskPmo::where('id_pmo',$request->id_pmo)->get();

        foreach($getTask as $loop){
            GanttTaskPmo::where('id', $loop->id)->update(['baseline_start'=>$loop->start_date]);
        }

        foreach($getTask as $loop){
            // GanttTaskPmo::where('id', $loop->id)->update(['baseline_end'=> (new Carbon( $loop->start_date))->addDays((int) $loop->duration)]);
            GanttTaskPmo::where('id', $loop->id)->update(['baseline_end'=> $loop->end_date]);
        }

        return response()->json([
            "action"=> "updated"
        ]);
    }

    public function updateTask(Request $request)
    {
        $getTask = GanttTaskPmo::where('id_pmo',$request->id_pmo)->where('text',$request->text)->first();
        $getTask->start_date = $request->start_date;
        $getTask->end_date = $request->end_date;
        $getTask->text = $request->text;
        $getTask->save();

        return response()->json([
            "action"=> "updated"
        ]);
    }
}
