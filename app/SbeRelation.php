<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SbeRelation extends Model
{
    protected $table = 'tb_sbe_relation';
    protected $primaryKey = 'id';
    protected $fillable = ['lead_id','tag_sbe','price_sbe'];
}
