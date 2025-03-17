<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class IdeaHubPoint extends Model
{
    protected $table = 'tb_idea_hub_point';
    protected $fillable = ['nik', 'point','nama'];
    public $timestamps = true;
}