<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PresenceLocationUser extends Model
{
	protected $table = 'presence__location_user';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;

	protected $fillable = [
		'user_id',
		'location_id',
		'date_add'
	];

	public function location() {
		return $this->hasOne(PresenceLocation::class,'id','location_id');
	}
}
