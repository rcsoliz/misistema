<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
// Aqui son los Modelos
// Comando: php artisan make:model Clase
class Clase extends Model
{
    protected $table = 'clases';
    protected $fillable = ['nombre', 'descripcion', 'condicion'];

    public function items()
    {
        return $this->hasMany('App\Item');
    }
}
