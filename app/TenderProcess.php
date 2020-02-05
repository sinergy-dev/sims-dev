<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TenderProcess extends Model
{
    protected $table = 'sales_tender_process';
    protected $primaryKey = 'id_tp';
    protected $fillable = ['lead_id', 'nik', 'auction_number', 'submit_price', 'win_prob', 'nama_project', 'submit_date', 'quote_number','quote_number2', 'quote_number_final'];
}
