<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category_in extends Model
{
    protected $table = 'inventory_category';
    protected $primaryKey = 'id_category';
    protected $fillable = ['category'];
}
