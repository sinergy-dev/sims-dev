<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PRActivity extends Model
{
    protected $table = 'tb_pr_activity';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;
	
	protected $fillable = [
		'id',
		'id_draft_pr',
		'date_time',
		'status',
		'operator',
		'activity'
	];
}
