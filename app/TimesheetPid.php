<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TimesheetPid extends Model
{
    protected $table = 'tb_timesheet_pid';
    protected $primaryKey = 'id';
    protected $fillable = ['nik','pid','date_add','role'];
    public $timestamps = false;
}
