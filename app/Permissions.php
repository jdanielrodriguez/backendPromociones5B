<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permissions extends Model
{
    protected $table = 'permissions';

    public function users(){
        return $this->hasMany('App\Users','user','id');
    }
}
