<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TimesheetConfig extends Model
{
    protected $table = 'tb_timesheet_config';
    protected $primaryKey = 'id';
    protected $fillable = ['roles','phase', 'task','date_add','division','status_assign_pid'];
    public $timestamps = false;
}
