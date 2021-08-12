<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketingDetail extends Model
{
    //
    protected $table = 'ticketing__detail';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;
	
	protected $fillable = [
		'id_ticket',
		'refrence',
		'pic',
		'contact_pic',
		'location',
		'problem',
		'serial_device',
		'id_atm',
		'note',
		'engineer',
		'ticket_number_3party',
		'reporting_time',
		'severity'
	];

	public function id_detail(){
		return $this->hasOne('App\Ticketing','id_ticket','id_ticket');
	}

	public function first_activity_ticket(){
		return $this->hasOne('App\TicketingActivity','id_ticket','id_ticket')
			->orderBy('id','ASC');
	}

	public function lastest_activity_ticket(){
		return $this->hasOne('App\TicketingActivity','id_ticket','id_ticket')
			->orderBy('id','DESC');
	}

	public function all_activity_ticket(){
		return $this->hasMany('App\TicketingActivity','id_ticket','id_ticket')
			->orderBy('id','DESC');
	}

	public function resolve(){
		return $this->hasOne('App\TicketingResolve','id_ticket','id_ticket');
	}

	public function severity_detail(){
		return $this->hasOne('App\TicketingSeverity','id','severity');
	}

	public function absen_machine(){
		return $this->hasOne('App\TicketingAbsen','id','id_atm');
	}
}
