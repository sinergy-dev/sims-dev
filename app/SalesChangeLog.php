<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesChangeLog extends Model
{
	protected $table = 'sales_change_log';
    protected $primaryKey = 'id';
    protected $fillable = ['lead_id', 'nik', 'status', 'submit_price', 'deal_price','result'];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function getCreatedAtAttribute($value)
    {
        // Convert the stored value to the 'Asia/Jakarta' timezone and format it.
//        \Log::info('Accessor Called for Created At: ' . $value);
        return \Carbon\Carbon::parse($value)->timezone('Asia/Jakarta')->format('Y-m-d H:i:s');
    }
}
