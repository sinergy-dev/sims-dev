<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PIDRequest extends Model
{
    protected $table = 'tb_pid_request';
    protected $primaryKey = 'id_pid_request';
    protected $fillable = [
    	'no_quotation', 
    	'amount', 
    	'date_quotation',
    	'status',
    	'note',
    ];
}
