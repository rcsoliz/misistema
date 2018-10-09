<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Estancia extends Model
{


    //nombre, ubicacion, tamanio, distancia,razonsocial, nit, rua, observacion
    //idtproveedor, idzona, iduser ,condicion
    protected $fillable = ['nombre', 'ubicacion', 'tamanio', 'distancia', 'razonsocial',
    'nit', 'rua', 'observacion', 'idtproveedor' ,
    'idzona', 'iduser', 'idganadero', 'condicion',
    'cantidadm', 'cantidadh'];

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function parametro(){
        return $this->belongsTo('App\Parametro');
    }


}
