<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketingSeverity extends Model
{
    //
    protected $table = 'ticketing__severity';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;
	
	protected $fillable = [
		'name',
		'color',
		'description',
		'resolution_time'
	];
	
}
