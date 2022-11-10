<?php

namespace Ordenes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Users extends Authenticatable
{
    protected $table = 'users';

    protected $hidden = [
        'password', 'remember_token'
    ];

    public function rol(){
        return $this->hasOne('Ordenes\Roles','id','rol');
    }

    public function referidos(){
        return $this->hasMany('Ordenes\Users','referido','id');
    }
}
