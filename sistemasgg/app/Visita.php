<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Visita extends Model
{  // idestancia
    // id, idestacia, idusuario, glosa, fecha_hora, condicion, total
    protected $fillable = ['idestancia', 'idusuario', 'glosa', 'fecha_hora', 
    'condicion', 'total', 'tipo_documento'];

   // public $timestamps = false;
    
    public function usuario(){
        return $this->belongsTo('App\User');
    }
    
    public function estancia(){
        return $this->belongsTo('App\Estancia');
    }
}
