<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TimesheetTask extends Model
{
    protected $table = 'tb_timesheet_task';
    protected $primaryKey = 'id';
    protected $fillable = ['task','description','date_add'];
    public $timestamps = false;

    protected $appends = ['title'];

    public function getTitleAttribute()
    {
        return 'Task';
    }
}
