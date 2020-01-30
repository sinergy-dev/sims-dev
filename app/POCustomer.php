<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class POCustomer extends Model
{
    protected $table = 'tb_po_customer';
    protected $primaryKey = 'id_tb_po_cus';
    protected $fillable = ['lead_id','no_po','date','note','nominal'];
}
