<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Engineer_lead extends Model
{
    protected $table = 'lead_engineer';
    protected $primaryKey = 'lead_id';
    protected $fillable = ['lead_id','status','nik_lead'];
}
