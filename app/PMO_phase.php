<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PMO_phase extends Model
{
	protected $table = 'tb_pmo_phase';
    protected $primaryKey = 'id';
    protected $fillable = ['id_pmo','phase_status','start_date','end_date','finish_date'];
    public $timestamps = false;
}
