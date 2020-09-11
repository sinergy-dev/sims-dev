<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TechnologyTagRelation extends Model
{
    //
    protected $table = 'tb_technology_tag_relation';
    protected $primaryKey = 'id';
    protected $fillable = ['id_tech_tag', 'lead_id'];
}
