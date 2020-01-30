<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\GanttTask;
use App\GanttLink;

class GanttController extends Controller
{
    public function get(){
        $tasks = new GanttTask();
        $links = new GanttLink();
 
        return response()->json([
            "data" => $tasks->all(),
            "links" => $links->all()
        ]);
    }

}
