<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketingATM extends Model
{
    //
    // protected $connection = 'second_db_connection';
    protected $table = 'ticketing__atm';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;
	
	protected $fillable = [
		'owner',
		'atm_id',
		'serial_number',
		'location',
		'address',
		'activation',
		'note',
		'machine_type',
		'os_atm',
		'versi_atm',
		'engineer_atm'
	];

	public function peripheral(){
		return $this->hasMany('App\TicketingATMPeripheral','id_atm','id')->orderBy('type');
	}
}
