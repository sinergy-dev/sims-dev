<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class QuoteConfig extends Model
{
    public $timestamps = false;
    protected $table = 'tb_quote_config';
    protected $primaryKey = 'id';
    protected $fillable = ['id_quote', 'project_type', 'nominal', 'status', 'date_add', 'version', 'discount', 'tax_vat','reason','email','attention'];
}