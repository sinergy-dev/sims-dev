<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PresenceShiftingUser extends Model
{
	protected $table = 'presence__shifting_user';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;
}
