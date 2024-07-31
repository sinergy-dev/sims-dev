<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketingEmailSetting extends Model
{
    //
    protected $table = 'ticketing__email_setting';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;
	
	protected $fillable = [
		'pid',
		'client',
		'open_dear',
		'open_to',
		'open_cc',
		'close_dear',
		'close_to',
		'close_cc',
	];

}
