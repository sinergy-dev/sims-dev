<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PMOFinalReport extends Model
{
    protected $table = 'tb_pmo_final_report';
    protected $primaryKey = 'id';
    protected $fillable = ['id_project'];
    public $timestamps = false;
}
