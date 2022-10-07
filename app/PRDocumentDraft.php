<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PRDocumentDraft extends Model
{
    protected $table = 'tb_pr_document_draft';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;
	
	protected $fillable = [
		'id',
		'id_draft_pr',
		'added'
	];
}
