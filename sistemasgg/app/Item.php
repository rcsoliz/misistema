<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['nombre', 'condicion', 'idclase'];

    public function clase(){
        return $this->belongsTo('App\Clase');
    }
}
