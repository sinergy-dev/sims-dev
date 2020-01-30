<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PMO_detail extends Model
{
	protected $table = 'tb_pmo_detail';
    protected $primaryKey = 'id';
    protected $fillable = ['id_pmo','id_engineer_assign','progress','id_phase'];
    public $timestamps = false;
}
