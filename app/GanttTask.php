<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GanttTask extends Model
{
    protected $appends = ["open"];
 
    public function getOpenAttribute(){
        return true;
    }
}
