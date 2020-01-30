<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;
use DB;

class Sales2 extends Model
{
	protected $table = 'sales_lead_register';
    protected $fillable = ['lead_id', 'nik', 'id_contact', 'opp_name', 'amount', 'result', 'status_sho'];
}
