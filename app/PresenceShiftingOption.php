<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PresenceShiftingOption extends Model
{
	protected $table = 'presence__shifting_option';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;
}
