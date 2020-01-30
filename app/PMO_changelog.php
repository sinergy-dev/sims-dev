<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PMO_changelog extends Model
{
	protected $table = 'tb_pmo_changelog';
    protected $primaryKey = 'id';
    protected $fillable = ['id_pmo','id_engineer_assign','status','date'];
    public $timestamps = false;
}
