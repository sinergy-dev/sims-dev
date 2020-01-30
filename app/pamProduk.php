<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class pamProduk extends Model
{
    protected $table = 'dvg_pr_product';
    protected $primaryKey = 'id_product';
    protected $fillable = ['qty','name_product','nominal', 'name_product_customer', 'desc_customer', 'qty_customer', 'nominal_customer', 'total_nominal_customer'];
}
