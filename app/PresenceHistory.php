<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PresenceHistory extends Model
{
    protected $table = 'presence__history';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;

	protected $fillable = [
		'nik',
		'presence_setting',
		'presence_schedule',
		'presence_actual',
		'presence_location',
		'presence_condition',
		'presence_type'
	];

	public function user(){
		return $this->hasOne(User::class,'nik','nik');
	}
}
