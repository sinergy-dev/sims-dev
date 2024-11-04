<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketingActivity extends Model
{
    //
    protected $table = 'ticketing__activity';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;
	
	protected $fillable = [
		'id_ticket',
		'date',
		'activity',
		'operator',
		'note'
	];

	// public function detail_activity()	{
	// 	return $this->hasOne('App\TicketingDetail','id_ticket','id_ticket');
	// }

	public function id_activity()	{
		return $this->belongsTo('App\Ticketing','id_ticket','id_ticket');
	}

	public function pending_remind()
	{
	    return $this->belongsTo('App\TicketingPendingReminder', 'id', 'id_pending');
	}
}
