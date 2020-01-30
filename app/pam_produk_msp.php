<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class pam_produk_msp extends Model
{
    protected $table = 'tb_pr_product_msp';
    protected $primaryKey = 'id_product';
    protected $fillable = ['qty','name_product','nominal', 'msp_code', 'unit', 'total_nominal', 'description'];
}
