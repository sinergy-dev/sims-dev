<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GuideLine extends Model
{
    protected $table = 'tb_guide_line';
    protected $primaryKey = 'id';
    protected $fillable = ['description','link_url','title','efective_date'];

    public $timestamps = false;
}
