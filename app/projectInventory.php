<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class projectInventory extends Model
{
    protected $table = 'inventory_project';
    protected $primaryKey = 'id_inventory_project';
    protected $fillable = ['to','address','telp','fax','att','subj','from','date','ref','id_project'];
}
