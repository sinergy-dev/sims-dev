<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketingResolve extends Model
{
    //
    protected $table = 'ticketing__resolve';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;
	
	protected $fillable = [
		'id_ticket',
		'root_couse ',
		'counter_measure',
		'finish ',
	];
}
