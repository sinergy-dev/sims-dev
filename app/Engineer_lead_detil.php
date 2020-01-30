<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Engineer_lead_detil extends Model
{
    protected $table = 'tb_detail_engineer';
    protected $primaryKey = 'id_detail';
    protected $fillable = ['lead_id','start_date','end_date','note','status'];
}
