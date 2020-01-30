<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Letter extends Model
{
    protected $table = 'tb_letter';
    protected $primaryKey = 'no';
    protected $fillable = ['no','no_letter','position', 'type_of_letter', 'month', 'to', 'attention', 'title', 'project', 'description', 'from', 'nik', 'division', 'issuance', 'project_id','note'];
}
