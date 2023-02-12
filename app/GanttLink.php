<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GanttLink extends Model
{
    protected $table = "gantt_links";
    public $primaryKey = "id";
    public $timestamps = false;
}
