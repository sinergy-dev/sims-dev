<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cuti extends Model
{
    protected $table = 'tb_cuti';
    protected $primaryKey = 'id_cuti';
    protected $fillable = ['nik', 'date_req', 'date_start', 'date_end', 'reason_leave', 'acting_during_leave'];
    public $timestamps = false;

    protected $appends = ['remarks'];

    public function getRemarksAttribute()
    {
   		return 'Leaving Permit';
    }
}
