<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProductTagRelation extends Model
{
    //
    protected $table = 'tb_product_tag_relation';
    protected $primaryKey = 'id';
    protected $fillable = [
    	'lead_id',
    	'id_product_tag',
    	'id_technology_tag',
    	'price'
    ];
}
