<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class QuoteProduct extends Model
{
    protected $table = 'tb_quote_product';
    protected $primaryKey = 'id';
    protected $fillable = ['id_quote', 'name', 'qty', 'nominal', 'description', 'unit', 'grand_total','price_list', 'total_price_list'];
}