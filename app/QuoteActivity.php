<?php


namespace App;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Model;

class QuoteActivity extends Model
{
    public $timestamps = false;
    protected $table = 'tb_quote_activity';

    protected $fillable = ['id_quote', 'operator', 'activity', 'date_add','status'];
}