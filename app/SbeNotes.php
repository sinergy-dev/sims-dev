<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SbeNotes extends Model
{
    protected $table = 'tb_sbe_notes';
    protected $primaryKey = 'id';
    protected $fillable = ['id_sbe','operator','notes','date_add'];
    public $timestamps = false;
}
