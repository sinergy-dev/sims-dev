<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detail_IdProject extends Model
{
    protected $table = 'tb_detail_id_project';
    protected $primaryKey = 'id_detail_pro';
    protected $fillable = ['id_pro','id_project', 'no_po_customer', 'amount_idr','date'];
}
