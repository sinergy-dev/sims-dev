<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventManagement extends Model
{
    protected $table = 'tb_event';
    protected $primaryKey = 'id';
    // protected $casts = [
    //     'attendee' => 'array',
    // ];
}
