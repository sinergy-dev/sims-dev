<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PRProductDraft extends Model
{
    protected $table = 'tb_pr_product_draft';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;
	
	protected $fillable = [
		'id',
		'id_draft_pr',
		'id_product',
		'added'
	];
}
