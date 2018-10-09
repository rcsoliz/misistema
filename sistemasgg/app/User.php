<?php

namespace App;

//use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable //Model
{
    use Notifiable;
    protected $fillable = ['nombre', 'tipo_documento', 'num_documento', 
        'direccion', 'telefono', 'email', 
        'usuario', 'password','condicion',
        'idrol'
    ];
    public $timestamps = false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /*referencia de llave foreanea a rol*/
    public function rol(){
        return $this->belongsTo('App\Rol');
    }

    /*referecia para la llave foreanea a ganadero */
    public function ganaderos()
    {
        return $this->hasMany('App\Ganadero');
    }

      /*referecia para la llave foreanea a estancia */
      public function estancias()
      {
          return $this->hasMany('App\Estancia');
      }
}
