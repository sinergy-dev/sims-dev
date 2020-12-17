<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PresenceSetting extends Model
{
    protected $table = 'presence__setting';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;

	protected $fillable = [
		'setting_on_time',
		'setting_injury_time',
		'setting_late',
		'setting_check_out',
		'date_add',
		'date_update'
	];
}
