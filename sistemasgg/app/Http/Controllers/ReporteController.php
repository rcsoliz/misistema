<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Visita;
use App\DetalleVisita;
class ReporteController extends Controller
{
    
    public function index(Request $request){
        if (!$request->ajax()) return redirect('/');
        $finicio = $request->finicio;
        $ffinal = $request->ffinal;

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if ($buscar==''){
            $visitas = Visita::join('estancias','estancias.id','=','visitas.idestancia')
            ->join('users', 'users.id','=','visitas.idusuario')
            ->select('visitas.id','visitas.glosa','visitas.fecha_hora','visitas.condicion',
            'visitas.total', 
            'users.nombre as usuario', 
            'estancias.nombre as estancia')
            ->whereBetween('visitas.fecha_hora', [$finicio, $ffinal])
            ->orderBy('visitas.id', 'desc')->paginate(5);
            //$parametros = Parametro::whereBetween('codigo',  [201, 210])
        }
        else{
            $visitas = Visita::join('estancias','estancias.id','=','visitas.idestancia')
            ->join('users', 'users.id','=','visitas.idusuario')
            ->select('visitas.id','visitas.glosa','visitas.fecha_hora','visitas.condicion',
            'visitas.total', 
            'users.nombre as usuario', 
            'estancias.nombre as estancia')
            ->where('visitas.'.$criterio, 'like', '%'. $buscar . '%')
            ->whereBetween('visitas.fecha_hora', [$finicio, $ffinal])
            ->orderBy('visitas.id', 'desc')->paginate(5);
        }
        
        return [
            'pagination' => [
                'total'        => $visitas->total(),
                'current_page' => $visitas->currentPage(),
                'per_page'     => $visitas->perPage(),
                'last_page'    => $visitas->lastPage(),
                'from'         => $visitas->firstItem(),
                'to'           => $visitas->lastItem(),
            ],
            'visitas' => $visitas
        ];
    }

}
