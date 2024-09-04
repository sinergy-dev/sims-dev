<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Ticketing extends Model
{
    //
    protected $table = 'ticketing__id';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;
	
	protected $fillable = [
		'id_ticket',
		'id_client',
		'operator',
		'id_client_pid'
	];

	public function detail_ticket(){
		return $this->hasOne('App\TicketingDetail','id_ticket','id_ticket');
	}

	public function activity_ticket(){
		return $this->hasMany('App\TicketingActivity','id_ticket','id_ticket');
	}

	public function lastest_activity_ticket(){
		return $this->hasOne('App\TicketingActivity','id_ticket','id_ticket')
			->orderBy('id','DESC');
	}

	public function client_ticket(){
		return $this->hasOne('App\TicketingClient','id','id_client');
	}
}
