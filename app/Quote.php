<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    protected $table = 'tb_quote';
    protected $primaryKey = 'id_quote';
    protected $fillable = ['id_quote','no','quote_number','position', 'type_of_letter', 'month', 'date', 'to', 'attention',
        'title', 'project', 'status', 'description', 'project_id', 'from', 'division', 'note', 'status_backdate','nik',
        'project_type', 'id_customer', 'term_payment', 'email', 'no_telp','address','lead_id','sign','building', 'street','city',
        'quote_number_update','pdf_url','is_uploaded','parent_drive_id','is_sended'];
}
