<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewsTagConnector extends Model
{
    protected $table = 'tb_news_tag_connector';
    protected $guarded = [];

    public function tag()
    {
        return $this->belongsTo('App\NewsTag', 'id_tag');
    }

    public function insights()
    {
        return $this->belongsTo('App\Insights', 'id_insights');
    }
}
