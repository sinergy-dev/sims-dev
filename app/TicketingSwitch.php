<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketingSwitch extends Model
{
    //
    protected $table = 'ticketing__switch';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;
	
	protected $fillable = [
		'type',
		'port',
		'serial_number',
		'ip_management',
		'location',
		'cabang',
		'note',
		'date_add'
	];
}
