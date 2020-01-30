<?php

namespace App;
use DB;

use Illuminate\Database\Eloquent\Model;

class SalesHandover extends Model
{
    protected $table = 'sales_sho';
    protected $primaryKey = 'id_sho';
    protected $fillable = ['lead_id', 'sow', 'timeline', 'top', 'service_budget', 'pid','meeting_date'];
    //
}
