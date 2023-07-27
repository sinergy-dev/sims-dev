<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TimesheetPhase extends Model
{
    protected $table = 'tb_timesheet_phase';
    protected $primaryKey = 'id';
    protected $fillable = ['phase','description','date_add'];
    public $timestamps = false;

    protected $appends = ['title'];

    public function getTitleAttribute()
    {
        return 'Phase';
    }
}
