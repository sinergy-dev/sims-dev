<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PMODocument extends Model
{
    protected $table = 'tb_pmo_document';
    protected $primaryKey = 'id';
    protected $fillable = ['document_name','document_location','link_drive'];
}
