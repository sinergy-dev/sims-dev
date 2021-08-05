<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketingAbsen extends Model
{
    //
    protected $table = 'ticketing__absen';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;
	
	protected $fillable = [
		'nama_cabang',
		'nama_kantor',
		'type_machine',
		'ip_machine',
		'ip_server',
	];
}
