<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuoteMSP extends Model
{
    protected $table = 'tb_quote_msp';
    protected $primaryKey = 'id_quote';
    protected $fillable = ['id_quote','no','quote_number','position', 'type_of_letter', 'month', 'date', 'to', 'attention', 'title', 'project', 'status', 'description', 'note', 'status_backdate','nik'];
}
