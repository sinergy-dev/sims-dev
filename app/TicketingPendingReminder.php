<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketingPendingReminder extends Model
{
    //
    protected $table = 'ticketing__pending_reminder';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;
	
	protected $fillable = [
		'id_ticket',
		'id_pending',
		'remind_create',
		'remind_time',
		'remind_success'
	];
}
