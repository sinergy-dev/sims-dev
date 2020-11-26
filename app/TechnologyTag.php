<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TechnologyTag extends Model
{
    //
    protected $table = 'tb_technology_tag';
    protected $primaryKey = 'id';
    protected $fillable = ['name_tech', 'description_tech', 'date_add'];
}
