<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EngineerSpent extends Model
{
    protected $table = 'dvg_esm';
    protected $primaryKey = 'no';
    protected $fillable = ['no','date','personnel', 'type', 'description', 'amount', 'id_project', 'remarks','status','year'];
}
