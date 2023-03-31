<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SbeDocument extends Model
{
    protected $table = 'tb_sbe_document';
    protected $primaryKey = 'id';
    protected $fillable = ['document_name','document_location','link_drive'];
}
