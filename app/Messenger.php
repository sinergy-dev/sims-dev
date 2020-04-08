<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Messenger extends Model
{
    protected $table = 'tb_messenger';
    protected $primaryKey = 'id_messenger';
    protected $fillable = ['book_date', 'activity', 'status', 'pic_name', 'pic_contact', 'note', 'book_time', 'lokasi', 'item', 'nik'];
}
