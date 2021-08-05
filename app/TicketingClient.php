<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketingClient extends Model
{
    //
    protected $table = 'ticketing__client';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;
	
	protected $fillable = [
		'client_name',
		'client_acronym',
		'open_dear',
		'open_to',
		'open_cc',
		'close_dear',
		'close_to',
		'close_cc',
		'situation',
		'banking',
	];

	public function client_atm(){
		return $this->hasMany('App\TicketingATM','owner','id');
	}

}
