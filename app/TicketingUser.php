<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketingUser extends Model
{
    protected $table = 'ticketing__user';
    protected $primaryKey = 'id';
    protected $fillable = ['nik', 'pid', 'date_add'];
    public $timestamps = false;
}
