<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PresenceSetting extends Model
{
    protected $table = 'presence__setting';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;

	protected $fillable = [
		'setting_on-time',
		'setting_injury-time',
		'setting_late',
		'date_add',
		'date_update'
	];
}
