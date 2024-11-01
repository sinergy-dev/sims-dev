<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssetNotesTransaction extends Model
{
    protected $table = 'tb_asset_hr_notes_transaction';
    
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'id',
        'id_request',
        'nik',
        'notes',
        'created_at',
    ];

    public $timestamps = false;
}
