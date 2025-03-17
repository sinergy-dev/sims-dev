<?php


namespace App;
use Illuminate\Database\Eloquent\Model;


class IdeaHubActivity extends Model
{
    protected $table = 'tb_idea_hub_activity';
    protected $fillable = ['id_idea_hub', 'operator', 'activity', 'date_add', 'status'];
    public $timestamps = false;
}