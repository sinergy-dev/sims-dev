<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PMOEksternalStakeholder extends Model
{
    protected $table = 'tb_pmo_eksternal_stakeholder';
    protected $primaryKey = 'id';
    protected $fillable = ['id_project','name','date_time','email','no_hp'];
    public $timestamps = false;
}
