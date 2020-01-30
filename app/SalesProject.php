<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesProject extends Model
{
 	protected $table = 'tb_id_project';
    protected $primaryKey = 'id_pro';
    protected $fillable = ['customer_name','id_project','nik', 'no_po_customer', 'id_contact','name_project', 'amount_usd','amount_idr','date','note','sales_name'];
    //
}
