<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BlogTag extends Model
{
    protected $table = 'tb_blog_tag';
    protected $guarded = [];

    public function insights()
    {
        return $this->belongsTo('App\Insights','id_insights');
    }

    public function product()
    {
        return $this->belongsTo('App\TechnologyTag','id_product');
    }
}
