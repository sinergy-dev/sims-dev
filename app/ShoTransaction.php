<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class ShoTransaction extends Model
{
    protected $table = 'sales_sho_transaction';
    protected $primaryKey = 'id_transaction';
    protected $fillable = ['id_sho', 'nik', 'tanggal_hadir', 'keterangan', 'status'];
    //
    //
}
