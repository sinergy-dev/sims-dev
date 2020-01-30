<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PMO_problem extends Model
{
	protected $table = 'tb_pmo_problem';
    protected $primaryKey = 'id';
    protected $fillable = ['id_pmo','problem','conture_measure','root_cause','start_date','end_date'];
    public $timestamps = false;
}
