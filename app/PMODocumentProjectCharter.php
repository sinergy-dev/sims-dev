<?php

namespace App;
use DB;

use Illuminate\Database\Eloquent\Model;

class PMODocumentProjectCharter extends Model
{
    protected $table = 'tb_pmo_doc_project_charter';
    protected $primaryKey = 'id';
    protected $fillable = ['id_project_charter','id_document','date_time'];
    public $timestamps = false;
}
