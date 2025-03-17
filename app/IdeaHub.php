<?php


namespace App;

use Illuminate\Database\Eloquent\Model;

class IdeaHub extends Model
{
    protected $table = 'tb_idea_hub';
    protected $fillable = ['nik', 'ide', 'konsep_bisnis', 'referensi', 'posisi','divisi'];
    public $timestamps = true;

}