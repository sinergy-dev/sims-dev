<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PRDocumentCompare extends Model
{
    protected $table = 'tb_pr_document_compare';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;
	
	protected $fillable = [
		'id',
		'id_compare_pr',
		'id_document',
		'added'
	];
}
