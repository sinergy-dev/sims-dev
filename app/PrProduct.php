<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrProduct extends Model
{
    protected $table = 'tb_pr_product';
    protected $primaryKey = 'id';
    protected $fillable = ['no_pr', 'name_product', 'qty', 'nominal', 'description', 'unit', 'serial_number', 'part_number', 'discount'];
}
