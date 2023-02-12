<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PMO_assign extends Model
{
	protected $table = 'tb_pmo_assign';
    protected $primaryKey = 'id';
    protected $fillable = ['id_project','role','nik'];
    public $timestamps = false;
}
