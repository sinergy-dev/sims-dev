<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketingATMPeripheral extends Model
{
    //
    protected $table = 'ticketing__atm_peripheral';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;
	
	protected $fillable = [
		'id_atm',
		'id_peripheral',
		'type',
		'serial_number',
		'machine_type',

		'cctv_dvr_sn',
		'cctv_dvr_type',
		'cctv_besar_sn',
		'cctv_besar_type',
		'cctv_kecil_sn',
		'cctv_kecil_type'
	];

	public function atm(){
		return $this->hasOne('App\TicketingATM','id','id_atm');
	}
}
