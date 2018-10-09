<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleVisita extends Model
{
    protected $table = 'detalle_visitas';
    protected $fillable = ['idvisita', 'iditem', 'cantparcial', 
    'cantventa', 'categoria', 'pstandar', 
    'timpsalida', 'observacion', 'alimentacion'];
     
    public $timestamps = false;

}
