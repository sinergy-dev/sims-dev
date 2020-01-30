<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PONumber extends Model
{
    protected $table = 'tb_po';
    protected $primaryKey = 'no';
    protected $fillable = ['no','no_po', 'position', 'type_of_letter', 'month', 'to', 'attention', 'title', 'project', 'description', 'from', 'division', 'issuance', 'project_id','note'];
}
