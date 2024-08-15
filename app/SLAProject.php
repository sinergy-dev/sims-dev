<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SLAProject extends Model
{
    protected $table = 'tb_sla_project';
    
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'id',
        'pid',
        'sla_resolution_critical',
        'sla_resolution_minor',
        'sla_resolution_major',
        'sla_resolution_moderate',
        'sla_response',
        'date_add'
    ];

    public $timestamps = false;
}
