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
    protected $fillable = ['id_product_tag', 'lead_id'];
}
