<?php

namespace App;
use DB;
use Carbon\Carbon;


use Illuminate\Database\Eloquent\Model;

class GanttTaskPmo extends Model
{
    protected $table = "gantt_tasks_pmo";
    public $primaryKey = "id";

    protected $appends = ['open'];
 
    public function getOpenAttribute(){
        return true;
    }

    // public function getSubTaskOnGoingAttribute()
    // {
    //     $data = DB::table('gantt_tasks_pmo')->select('parent')->where('parent','!=',0);

    //     $subTaskMilestone = DB::table('gantt_tasks_pmo')->leftJoinSub($data, 'parent_text',function($join){
    //                 $join->on("gantt_tasks_pmo.id", '=', 'parent_text.parent');
    //             })->select('text')->where('id',$this->id);

    //     return $subTaskMilestone;
    // }

}
