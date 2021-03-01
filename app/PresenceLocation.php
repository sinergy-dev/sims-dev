<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PresenceLocation extends Model
{
	protected $table = 'presence__location';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;

	protected $fillable = [
		'location_name',
		'location_lat',
		'location_lng',
		'location_radius',
		'date_add',
		'date_update'
	];
}
