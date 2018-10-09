<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Estancia;
use App\User;
use App\Parametro;
use App\Zona;
class EstanciaController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if ($buscar==''){
            $estancias = Estancia::join('users','estancias.iduser','=','users.id')
            ->join('parametros','estancias.idtproveedor','=','parametros.id')
            ->join('zonas','estancias.idzona','=','zonas.id')
            ->join('ganaderos','estancias.idganadero','=','ganaderos.id')
            ->select('estancias.id','estancias.nombre', 'estancias.ubicacion', 'estancias.tamanio', 
            'estancias.distancia', 'estancias.razonsocial',  'estancias.nit', 'estancias.rua', 
            'estancias.observacion', 'estancias.condicion',
            'users.nombre as usuario',
            'estancias.idzona', 'estancias.idganadero', 'estancias.idtproveedor', 
            'zonas.nombre as zona', 'ganaderos.ncompleto as ganadero', 'parametros.nombre as tproveedor',
            'estancias.cantidadh as cantidadh',   'estancias.cantidadm as cantidadm')
            ->where('estancias.condicion','=','1')
            ->orderBy('estancias.id', 'desc')->paginate(5);
        } 
        else{
            $estancias = Estancia::join('users','estancias.iduser','=','users.id')
            ->join('parametros','estancias.idtproveedor','=','parametros.id')
            ->join('zonas','estancias.idzona','=','zonas.id')
            ->join('ganaderos','estancias.idganadero','=','ganaderos.id')
            ->select('estancias.id','estancias.nombre', 'estancias.ubicacion', 'estancias.tamanio', 
            'estancias.distancia', 'estancias.razonsocial',  'estancias.nit', 'estancias.rua', 
            'estancias.observacion', 'estancias.condicion',
            'users.nombre as usuario',
            'estancias.idzona','estancias.idganadero', 'estancias.idtproveedor',
            'zonas.nombre as zona', 'ganaderos.ncompleto as ganadero', 'parametros.nombre as tproveedor',
            'estancias.cantidadh as cantidadh', 'estancias.cantidadm as cantidadm')
            ->where('estancias.condicion','=','1')
            ->where('estancias.'.$criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(5);
        }
        
        return [
            'pagination' => [
                'total'        => $estancias->total(),
                'current_page' => $estancias->currentPage(),
                'per_page'     => $estancias->perPage(),
                'last_page'    => $estancias->lastPage(),
                'from'         => $estancias->firstItem(),
                'to'           => $estancias->lastItem(),
            ],
            'estancias' => $estancias
        ];
    }
    
    public function indexbaja(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if ($buscar==''){
            $estancias = Estancia::join('users','estancias.iduser','=','users.id')
            ->join('parametros','estancias.idtproveedor','=','parametros.id')
            ->join('zonas','estancias.idzona','=','zonas.id')
            ->join('ganaderos','estancias.idganadero','=','ganaderos.id')
            ->select('estancias.id','estancias.nombre', 'estancias.ubicacion', 'estancias.tamanio', 
            'estancias.distancia', 'estancias.razonsocial',  'estancias.nit', 'estancias.rua', 
            'estancias.observacion', 'estancias.condicion',
            'users.nombre as usuario',
            'estancias.idzona', 'estancias.idganadero', 'estancias.idtproveedor', 
            'zonas.nombre as zona', 'ganaderos.ncompleto as ganadero', 'parametros.nombre as tproveedor',
            'estancias.cantidadh as cantidadh',   'estancias.cantidadm as cantidadm')
            ->where('estancias.condicion','=','0')
            ->orderBy('estancias.id', 'desc')->paginate(5);
        } 
        else{
            $estancias = Estancia::join('users','estancias.iduser','=','users.id')
            ->join('parametros','estancias.idtproveedor','=','parametros.id')
            ->join('zonas','estancias.idzona','=','zonas.id')
            ->join('ganaderos','estancias.idganadero','=','ganaderos.id')
            ->select('estancias.id','estancias.nombre', 'estancias.ubicacion', 'estancias.tamanio', 
            'estancias.distancia', 'estancias.razonsocial',  'estancias.nit', 'estancias.rua', 
            'estancias.observacion', 'estancias.condicion',
            'users.nombre as usuario',
            'estancias.idzona','estancias.idganadero', 'estancias.idtproveedor',
            'zonas.nombre as zona', 'ganaderos.ncompleto as ganadero', 'parametros.nombre as tproveedor',
            'estancias.cantidadh as cantidadh', 'estancias.cantidadm as cantidadm')
            ->where('estancias.condicion','=','0')
            ->where('estancias.'.$criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(5);
        }
        
        return [
            'pagination' => [
                'total'        => $estancias->total(),
                'current_page' => $estancias->currentPage(),
                'per_page'     => $estancias->perPage(),
                'last_page'    => $estancias->lastPage(),
                'from'         => $estancias->firstItem(),
                'to'           => $estancias->lastItem(),
            ],
            'estancias' => $estancias
        ];
    }

    public function listarEstanciaPdf()
    {
        $estancias = Estancia::join('zonas','estancias.idzona','=','zonas.id')
        ->join('ganaderos','estancias.idganadero','=','ganaderos.id')
        ->select('estancias.nombre', 'ganaderos.ncompleto as ganadero', 'zonas.nombre as zona', 
        'estancias.tamanio as dimensiones', 'estancias.distancia')
        ->where('estancias.condicion','=','1')
        ->orderBy('estancias.nombre', 'asc')->get();     
       
        $cont=Estancia::count();

        $pdf = \PDF::loadView('pdf.estanciaspdf',['estancias'=>$estancias,'cont'=>$cont]);
        return $pdf->download('estancias.pdf');
    }
    
    public function listarEstanciaBajaPdf()
    {
        $estancias = Estancia::join('zonas','estancias.idzona','=','zonas.id')
        ->join('ganaderos','estancias.idganadero','=','ganaderos.id')
        ->select('estancias.nombre', 'ganaderos.ncompleto as ganadero', 'zonas.nombre as zona', 
        'estancias.tamanio as dimensiones', 'estancias.distancia')
        ->where('estancias.condicion','=','0')
        ->orderBy('estancias.nombre', 'asc')->get();     
       
        $cont=Estancia::count();

        $pdf = \PDF::loadView('pdf.estanciaspdf',['estancias'=>$estancias,'cont'=>$cont]);
        return $pdf->download('estancias.pdf');
    }

    public function selectEstancia(Request $request){
        if(!$request->ajax()) return redirect('/'); 
        $filtro = $request->filtro;
        $estancias = Estancia::join('ganaderos','estancias.idganadero','=','ganaderos.id')
        ->where('estancias.nombre','like', '%'. $filtro . '%')
        ->orWhere('ganaderos.ncompleto', 'like','%'. $filtro . '%')
        ->select('estancias.id', 'estancias.nombre', 'ganaderos.ncompleto as ganadero')
        ->orderBy('estancias.nombre', 'asc')->get();

        return ['estancias' => $estancias];
    }

    public function store(Request $request)
    {
        if(!$request->ajax()) return redirect('/'); 
        $estancia = new Estancia();
        $estancia->nombre = $request->nombre;
        $estancia->ubicacion = $request->ubicacion;
        $estancia->tamanio = $request->tamanio;
        $estancia->distancia = $request->distancia;
        $estancia->razonsocial = $request->razonsocial;
        $estancia->nit = $request->nit;
        $estancia->rua = $request->rua;
        $estancia->observacion = $request->observacion;
        $estancia->idtproveedor = '11';
        $estancia->idzona = $request->idzona;
        $estancia->iduser =\Auth::user()->id;  // '1'; //$request->iduser; hay que cambiar con el usuario de sesion
        $estancia->idganadero = $request->idganadero;
        $estancia->cantidadm = $request->cantidadm;
        $estancia->cantidadh = $request->cantidadh;
        $estancia->condicion = '1';

        $estancia->save();
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

            $estancia = Estancia::findOrFail($request->id);
            $estancia->nombre = $request->nombre;
            $estancia->ubicacion = $request->ubicacion;
            $estancia->tamanio = $request->tamanio;
            $estancia->distancia = $request->distancia;
            $estancia->razonsocial = $request->razonsocial;
            $estancia->nit = $request->nit;
            $estancia->rua = $request->rua;
            $estancia->observacion = $request->observacion;
            $estancia->idtproveedor = $request->idtproveedor;
            $estancia->idzona = $request->idzona;
            $estancia->iduser = '1'; // $request->iduser; hay que cambiar con el usuario de sesion
            $estancia->idganadero = $request->idganadero;
            $estancia->cantidadm = $request->cantidadm;
            $estancia->cantidadh = $request->cantidadh;
            $estancia->condicion = '1';
            $estancia->save();

    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $estancia = Estancia::findOrFail($request->id);
        $estancia->condicion = '0';
        $estancia->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $estancia = Estancia::findOrFail($request->id);
        $estancia->condicion = '1';
        $estancia->save();
    }

    public function informeEstancia(Request $request){
        if (!$request->ajax()) return redirect('/');
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if ($buscar<>''){
            $estancias = Estancia::join('users','estancias.iduser','=','users.id')
            ->join('ganaderos','estancias.idganadero','=','ganaderos.id')
            ->select('estancias.nombre', 'estancias.ubicacion', 'estancias.tamanio', 
            'estancias.distancia', 'ganaderos.ncompleto as ganadero', 
            'users.nombre as usuario')
            ->where('estancias.idzona','=', $buscar)
            ->orderBy('nombre', 'desc')->paginate(5);

            // ->where('estancias.condicion','=','1')
            // ->where('estancias.'.$criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(5);
        }
    }
}
