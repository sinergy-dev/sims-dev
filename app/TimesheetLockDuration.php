<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TimesheetLockDuration extends Model
{
    protected $table = 'tb_timesheet_lock_duration';
    protected $primaryKey = 'id';
    protected $fillable = ['lock_duration','division','date_add'];
    public $timestamps = false;
}
