<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProductTag extends Model
{
    //
    protected $table = 'tb_product_tag';
    protected $primaryKey = 'id';
    protected $fillable = ['name_product', 'description_product', 'date_add'];
}
