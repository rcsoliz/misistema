<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ganadero extends Model
{
    protected $fillable = ['codigo','nombre', 'apellido', 'direccion', 'telefeno',
                           'celular', 'email', 'razonsocial', 'confactura', 'condicion', 
                           'iduser', 'ncompleto'];

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function getNombreCompleto()
    {
        return $this->attributes['nombre'] .' '. $this->attributes['apellido'];
    }
}
