<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Correos extends Model
{
    protected $table = 'correos';

    public function playerObj(){
        return $this->hasOne('App\Players','player','id');
    }
    public function moveObj(){
        return $this->hasMany('App\Users','user','id');
    }
}
