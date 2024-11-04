<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class TicketingDetail extends Model
{
    //
    protected $table = 'ticketing__detail';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;
	
	protected $fillable = [
		'id_ticket',
		'refrence',
		'pic',
		'contact_pic',
		'location',
		'problem',
		'serial_device',
		'id_atm',
		'note',
		'engineer',
		'ticket_number_3party',
		'reporting_time',
		'severity',
		'request_pending'
	];

    // protected $appends = ['concatenate_problem_ticket','concatenate_type_ticket'];

	public function id_detail(){
		return $this->hasOne('App\Ticketing','id_ticket','id_ticket');
	}

	public function first_activity_ticket(){
		return $this->hasOne('App\TicketingActivity','id_ticket','id_ticket')
			->orderBy('id','ASC');
	}

	public function lastest_activity_ticket(){
		return $this->hasOne('App\TicketingActivity','id_ticket','id_ticket')
			->orderBy('id','DESC');
	}

	public function all_activity_ticket(){
		return $this->hasMany('App\TicketingActivity','id_ticket','id_ticket')
			->orderBy('id','DESC')
			->with(['pending_remind:id,id_pending,remind_time']);
	}

	public function resolve(){
		return $this->hasOne('App\TicketingResolve','id_ticket','id_ticket');
	}

	public function severity_detail(){
		return $this->hasOne('App\TicketingSeverity','id','severity');
	}

	public function absen_machine(){
		return $this->hasOne('App\TicketingAbsen','id','id_atm');
	}

	// public function getConcatenateProblemTicketAttribute(){
	// 	$concat_problem_ticket = DB::table('ticketing__detail')->select(DB::raw("CONCAT(`location`,' - ',`problem`) AS problem"))->where('id_ticket',$this->id_ticket)->first()->problem;

	// 	return $concat_problem_ticket;
	// }

	// public function getConcatenateTypeTicketAttribute(){
	// 	$concat_problem_ticket = DB::table('ticketing__detail')->select(DB::raw("(CASE WHEN `type_ticket` = 'PL' THEN 'Permintaan Layanan Ticket' WHEN `type_ticket` = 'TT' THEN 'Trouble Ticket' WHEN `type_ticket` = 'PM' THEN 'Preventive Maintenance Ticket' ELSE '-' END) as type"))->where('id_ticket',$this->id_ticket)->first()->type;

	// 	return $concat_problem_ticket;
	// }
}
