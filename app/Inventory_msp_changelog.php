<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventory_msp_changelog extends Model
{
    protected $table = 'inventory_changelog_msp';
    protected $primaryKey = 'id_changelog';
    protected $fillable = ['id_detail_do_msp','id_po_msp','qty','tgl_changelog', 'id_barang','created_at','updated_at'];
}
