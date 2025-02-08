<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class QuoteConfigProduct extends Model
{
    protected $table = 'tb_quote_config_product';
    protected $primaryKey = 'id';
    protected $fillable = ['id_product', 'id_config'];
}