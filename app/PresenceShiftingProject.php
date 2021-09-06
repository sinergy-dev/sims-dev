<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PresenceShiftingProject extends Model
{
	protected $table = 'presence__shifting_project';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;

	protected $fillable = [
		'project_name',
		'project_location'
	];
}
