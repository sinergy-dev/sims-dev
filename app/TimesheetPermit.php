<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TimesheetPermit extends Model
{
    protected $table = 'tb_timesheet_permit';
    protected $primaryKey = 'id';
    protected $fillable = ['status','start_date', 'end_date','activity','nik','date_add'];
    public $timestamps = false;
}
