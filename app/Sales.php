<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Sales extends Model
{
    protected $table = 'sales_lead_register';
    protected $primaryKey = 'lead_id';
    protected $fillable = ['lead_id', 'nik', 'id_contact', 'opp_name', 'amount', 'result', 'status_handover', 'status_sho','closing_date','note', 'deal_price'];
}
