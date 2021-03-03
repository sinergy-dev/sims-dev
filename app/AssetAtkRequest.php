<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssetAtkRequest extends Model
{
    protected $table = 'tb_asset_atk_request';
    protected $primaryKey = 'id_barang';
    protected $fillable = ['nik', 'nama', 'qty','status', 'link', 'keterangan', 'note_reject'];
}
