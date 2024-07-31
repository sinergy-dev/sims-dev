<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketingEmailSLM extends Model
{
    //
    protected $table = 'ticketing__email_slm';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;
	
	protected $fillable = [
		'second_level_support',
		'open_dear',
		'open_to',
		'open_cc',
		'close_dear',
		'close_to',
		'close_cc'
	];

}
