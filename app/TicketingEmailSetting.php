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
		'dear',
		'to',
		'cc',
	];

}
