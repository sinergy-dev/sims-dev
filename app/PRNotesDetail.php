<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PRNotesDetail extends Model
{
    protected $table = 'tb_pr_notes_detail';
	
	protected $primaryKey = 'id';

	public $timestamps = false;
	
	protected $fillable = [
		'id',
		'id_notes',
		'date_add',
		'operator',
		'reply'
	];
}
