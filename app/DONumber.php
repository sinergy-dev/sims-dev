<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DONumber extends Model
{
    protected $table = 'tb_do';
    protected $primaryKey = 'no';
    protected $fillable = ['no','no_do', 'type_of_letter', 'month', 'date', 'to', 'attention', 'address', 'from', 'subject', 'project_id', 'note', 'no_telp', 'no_po'];
}
