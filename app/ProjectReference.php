<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectReference extends Model
{
    protected $table = 'tb_project_reference';
    protected $guarded = [];

    public function partner_section()
    {
        return $this->belongsTo('App\TechnologyTag','id_product');
    }
}
