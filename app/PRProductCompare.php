<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PRProductCompare extends Model
{
    protected $table = 'tb_pr_product_compare';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;
	
	protected $fillable = [
		'id',
		'id_compare_pr',
		'id_product',
		'added'
	];
}
