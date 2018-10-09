<?php

namespace App\Http\Controllers;
//________
use Illuminate\Database\Connection;
//________
use Illuminate\Http\Request;
use App\Ganadero;
use App\User;


class GanaderoController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $ganaderos = Ganadero::join('users','ganaderos.iduser','=','users.id')
            ->select('ganaderos.id','ganaderos.nombre', 'ganaderos.apellido', 'ganaderos.direccion', 
            'ganaderos.telefeno', 
            'ganaderos.celular','ganaderos.email', 'ganaderos.razonsocial', 'ganaderos.confactura', 
            'ganaderos.condicion','users.nombre as usuario')
            ->where('ganaderos.condicion','=','1') 
            ->orderBy('ganaderos.id', 'desc')->paginate(5);
        } 
        else{
            $ganaderos = Ganadero::join('users','ganaderos.iduser','=','users.id')
            ->select('ganaderos.id','ganaderos.nombre' , 'ganaderos.apellido' , 'ganaderos.direccion', 
            'ganaderos.telefeno', 
            'ganaderos.celular','ganaderos.email', 'ganaderos.razonsocial', 'ganaderos.confactura', 
            'ganaderos.condicion','users.nombre as usuario')
            ->where('ganaderos.condicion','=','1') 
            ->where('ganaderos.'.$criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(5);
        }
        
        return [
            'pagination' => [
                'total'        => $ganaderos->total(),
                'current_page' => $ganaderos->currentPage(),
                'per_page'     => $ganaderos->perPage(),
                'last_page'    => $ganaderos->lastPage(),
                'from'         => $ganaderos->firstItem(),
                'to'           => $ganaderos->lastItem(),
            ],
            'ganaderos' => $ganaderos
        ];
    }
    
    public function indexbaja(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $ganaderos = Ganadero::join('users','ganaderos.iduser','=','users.id')
            ->select('ganaderos.id','ganaderos.nombre', 'ganaderos.apellido', 'ganaderos.direccion', 
            'ganaderos.telefeno', 
            'ganaderos.celular','ganaderos.email', 'ganaderos.razonsocial', 'ganaderos.confactura', 
            'ganaderos.condicion','users.nombre as usuario')
            ->where('ganaderos.condicion','=','0')
            ->orderBy('ganaderos.id', 'desc')->paginate(5);
        } 
        else{
            $ganaderos = Ganadero::join('users','ganaderos.iduser','=','users.id')
            ->select('ganaderos.id','ganaderos.nombre' , 'ganaderos.apellido' , 'ganaderos.direccion', 
            'ganaderos.telefeno', 
            'ganaderos.celular','ganaderos.email', 'ganaderos.razonsocial', 'ganaderos.confactura', 
            'ganaderos.condicion','users.nombre as usuario')
            ->where('ganaderos.condicion','=','0') 
            ->where('ganaderos.'.$criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(5);
        }
        
        return [
            'pagination' => [
                'total'        => $ganaderos->total(),
                'current_page' => $ganaderos->currentPage(),
                'per_page'     => $ganaderos->perPage(),
                'last_page'    => $ganaderos->lastPage(),
                'from'         => $ganaderos->firstItem(),
                'to'           => $ganaderos->lastItem(),
            ],
            'ganaderos' => $ganaderos
        ];
    }

    public function listarGanaderoPdf()
    {
        $ganaderos = Ganadero::where('condicion', '=', '1') 
        ->select('ncompleto', 'telefeno', 'celular', 'direccion', 'condicion')
        ->orderBy('ncompleto', 'asc')->get();     
       
        $cont=Ganadero::count();

        $pdf = \PDF::loadView('pdf.ganaderospdf',['ganaderos'=>$ganaderos,'cont'=>$cont]);
        return $pdf->download('ganaderos.pdf');
    }

    public function listarGanaderoBajaPdf()
    {
        $ganaderos = Ganadero::where('condicion', '=', '0') 
        ->select('ncompleto', 'telefeno', 'celular', 'direccion', 'condicion')
        ->orderBy('ncompleto', 'asc')->get();     
       
        $cont=Ganadero::count();

        $pdf = \PDF::loadView('pdf.ganaderospdf',['ganaderos'=>$ganaderos,'cont'=>$cont]);
        return $pdf->download('ganaderos.pdf');
    }


    public function selectGanadero()
    {     //ncompleto
        //if (!$request->ajax()) return redirect('/');
        $ganaderos = Ganadero::where('condicion', '>=', '0')
        ->select('id',  'ncompleto as nombre')
        ->orderBy('nombre', 'asc')->get();
        return ['ganaderos' => $ganaderos];
    } 

    // codigo, nombre, apellido, direccion, telefeno,
    // celular, email, razonsocial, confactura, condicion, 
    // iduser
    public function store(Request $request)
    {
        if(!$request->ajax()) return redirect('/'); 
        $ganadero = new Ganadero();
        $ganadero->codigo ='0'; // $request->codigo campo reservado si alguna vez quieren agregar otro codigo.
        $ganadero->nombre = $request->nombre;
        $ganadero->apellido = $request->apellido;
        $ganadero->direccion = $request->direccion;
        $ganadero->telefeno = $request->telefeno;
        $ganadero->celular = $request->celular;
        $ganadero->email = $request->email;
        $ganadero->razonsocial = $request->razonsocial;
        $ganadero->confactura = $request->confactura;
        $ganadero->iduser = '1'; // $request->iduser; hay que cambiar con el usuario de sesion
        $ganadero->condicion = '1';
        $ganadero->ncompleto =$request->apellido.' '.$request->nombre;

        $ganadero->save();
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

            $ganadero = Ganadero::findOrFail($request->id);
            $ganadero->codigo ='0'; // $request->codigo campo reservado si alguna vez quieren agregar otro codigo.
            $ganadero->nombre = $request->nombre;
            $ganadero->apellido = $request->apellido;
            $ganadero->direccion = $request->direccion;
            $ganadero->telefeno = $request->telefeno;
            $ganadero->celular = $request->celular;
            $ganadero->email = $request->email;
            $ganadero->razonsocial = $request->razonsocial;
            $ganadero->confactura = $request->confactura;
            $ganadero->condicion = '1';
            $ganadero->iduser = '1'; // $request->iduser; hay que cambiar con el usuario de sesion
            $ganadero->ncompleto =$request->apellido.' '.$request->nombre;

            $ganadero->save();

    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $ganadero = Ganadero::findOrFail($request->id);
        $ganadero->condicion = '0';
        $ganadero->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $ganadero = Ganadero::findOrFail($request->id);
        $ganadero->condicion = '1';
        $ganadero->save();
    }

}
