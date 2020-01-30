<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PMO extends Model
{
	protected $table = 'tb_pmo';
    protected $primaryKey = 'id_pmo';
    protected $fillable = ['pmo_nik','lead_id'];
    public $timestamps = false;
}
