<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TimesheetWorkdays extends Model
{
    protected $table = 'tb_timesheet_workdays';
    protected $primaryKey = 'id';
    protected $fillable = ['workdays','month', 'year','date_add'];
    public $timestamps = false;
}
