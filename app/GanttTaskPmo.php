<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GanttTaskPmo extends Model
{
    protected $table = "gantt_tasks_pmo";
    public $primaryKey = "id";
    public $timestamps = false;
}
