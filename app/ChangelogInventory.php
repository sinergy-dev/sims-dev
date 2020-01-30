<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChangelogInventory extends Model
{
    protected $table = 'inventory_change_log';
    protected $primaryKey = 'id_change';
    protected $fillable = ['id_detail_barang','note','created_at', 'updated_at'];
}
