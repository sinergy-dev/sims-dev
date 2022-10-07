<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrDokumen extends Model
{
    protected $table = 'tb_pr_document';
    protected $primaryKey = 'id';
    protected $fillable = ['no_pr', 'dokumen'];
}
