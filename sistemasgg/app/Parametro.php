<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Parametro extends Model
{
    protected $fillable = ['codigo', 'nombre', 'valor1', 'valor2', 'condicion'];

        /*referecia para la llave foreanea a estancia */
        public function estancias()
        {
            return $this->hasMany('App\Estancia');
        }
}
