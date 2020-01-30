<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesChangeLog extends Model
{
	protected $table = 'sales_change_log';
    protected $primaryKey = 'id';
    protected $fillable = ['lead_id', 'nik', 'status', 'submit_price', 'deal_price'];
}
