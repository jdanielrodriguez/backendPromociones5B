<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rewards extends Model
{
    protected $table = 'reward';

    public function opportunities(){
        return $this->hasMany('App\Opportunity','reward','id');
    }
}