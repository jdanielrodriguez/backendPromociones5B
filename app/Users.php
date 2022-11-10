<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Users extends Authenticatable
{
    protected $table = 'users';

    protected $hidden = [
        'password', 'remember_token'
    ];

    public function rol(){
        return $this->hasOne('App\Roles','id','rol');
    }
}
