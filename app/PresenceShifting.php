<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PresenceShifting extends Model
{
	protected $table = 'presence__shifting';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;

	protected $fillable = [
		'project_name',
		'project_location'
	];
}
