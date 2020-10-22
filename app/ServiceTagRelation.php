<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ServiceTagRelation extends Model
{
    //
    protected $table = 'tb_service_tag_relation';
    protected $primaryKey = 'id';
    protected $fillable = [
    	'lead_id',
    	'id_service_tag',
    	'price'
    ];
}
