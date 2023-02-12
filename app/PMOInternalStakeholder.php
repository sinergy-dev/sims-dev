<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PMOInternalStakeholder extends Model
{
    protected $table = 'tb_pmo_internal_stakeholder';
    protected $primaryKey = 'id';
    protected $fillable = ['id_project_charter','nik','date_time','role'];
    public $timestamps = false;
}
