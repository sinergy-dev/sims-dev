<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Type_in extends Model
{
    protected $table = 'inventory_type';
    protected $primaryKey = 'id_type';
    protected $fillable = ['type'];
}
