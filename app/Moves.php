<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Moves extends Model
{
    protected $table = 'moves';

    public function winObj()
    {
        return $this->hasOne('App\Opportunity', 'id', 'opportunity')->with('premio');
    }
    public function players()
    {
        return $this->hasOne('App\Players', 'id', 'player');
    }
    public function departamento()
    {
        return $this->hasOne('App\Departaments', 'id', 'department');
    }
}
