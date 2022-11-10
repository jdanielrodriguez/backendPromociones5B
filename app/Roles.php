<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    protected $table = 'roles';

    public function referidos(){
        return $this->hasMany('App\Users','referido','id');
    }
}
