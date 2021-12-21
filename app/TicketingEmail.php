<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketingEmail extends Model
{
    //
    protected $table = 'ticketing__email';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;
	
	protected $fillable = [
		'name',
		'body',
		'subject',
		'date_add'
	];

}
