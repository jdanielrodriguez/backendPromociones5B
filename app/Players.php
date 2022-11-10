<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Players extends Model
{
    protected $table = 'players';

    public function moves(){
        return $this->hasMany('App\Moves','move','id');
    }
}
