<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Opportunity extends Model
{
    protected $table = 'opportunity';

    public function premio(){
        return $this->hasOne('App\Rewards','id','reward');
    }
}
