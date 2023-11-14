<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerTechnology extends Model
{
    protected $table = 'tb_customer_connector';
    protected $guarded = [];

    public function tech()
    {
        return $this -> belongsTo('App\TechnologyTag', 'id_product');
    }
}
