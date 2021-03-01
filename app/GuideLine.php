<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GuideLine extends Model
{
    protected $table = 'tb_guide_line';
    protected $primaryKey = 'id';
    protected $fillable = ['description','link_url','regulation','policy','date_add'];

    public $timestamps = false;
}
