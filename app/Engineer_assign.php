<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Engineer_assign extends Model
{
    protected $table = 'tb_assign_lead_eng';
    protected $primaryKey = 'id_assign';
    protected $fillable = ['nik_assign'];
}
