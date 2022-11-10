<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Moves extends Model
{
    protected $table = 'moves';

    public function winObj(){
        return $this->hasOne('App\Opportunity','id','opportunity');
    }
}
