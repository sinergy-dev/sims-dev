<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WarehouseProjectMSP extends Model
{
    protected $table = 'inventory_delivery_msp';
    protected $primaryKey = 'id_transaction';
    protected $fillable = ['to_agen','address','telp','fax', 'attn', 'from', 'subj', 'date', 'ref', 'no_do', 'id_project','status_kirim','created_at', 'updated_at'];
}
