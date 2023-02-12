<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PMOTechnologyUsed extends Model
{
    protected $table = 'tb_pmo_technology_project_charter';
    protected $primaryKey = 'id';
    protected $fillable = ['id_project_charter','technology_used','date_time'];
    public $timestamps = false;
}
