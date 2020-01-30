<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PONumberMSP extends Model
{
    protected $table = 'tb_po_msp';
    protected $primaryKey = 'no';
    protected $fillable = ['no','no_po', 'position', 'type_of_letter', 'month', 'to', 'attention', 'title', 'project', 'description', 'from', 'division', 'issuance', 'project_id'];
}
