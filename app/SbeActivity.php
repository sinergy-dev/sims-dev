<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SbeActivity extends Model
{
    protected $table = 'tb_sbe_activity';
    protected $primaryKey = 'id';
    protected $fillable = ['id_sbe','operator','activity','date_add'];
    public $timestamps = false;
}
