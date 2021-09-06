<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'tb_invoice';
    protected $primaryKey = 'id';
    protected $fillable = ['id','no_invoice', 'date', 'from_eksternal', 'issuance', 'no_po', 'status'];
}
