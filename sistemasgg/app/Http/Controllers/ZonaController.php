<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;    //:: filter ()
use Illuminate\Database\Query\Build;  //er::cont
use Carbon\Carbon;

use Illuminate\Http\Request;
use App\Zona; // importo mi modelo "Zona" 

class ZonaController extends Controller
{
    public function index(Request $request)
    {
        if(!$request->ajax()) return redirect('/'); 
     
        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar==''){
            $zonas = Zona::orderBy('id', 'desc')->paginate(5);
        } 
        else {
            $zonas = Zona::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id','desc')->paginate(5);
        }

        return [
            'pagination' => [
                'total'        => $zonas->total(),
                'current_page' => $zonas->currentPage(),
                'per_page'     => $zonas->perPage(),
                'last_page'    => $zonas->lastPage(),
                'from'         => $zonas->firstItem(),
                'to'           => $zonas->lastItem(),
            ],
            'zonas' => $zonas   
        ];
    }

    //'SELECT id, nombre FROM zonas WHERE id>1 order by nombre, id'
    public function selectZona()
    {
        $zonas = Zona::where('id', '>', '1')
        ->select('id','nombre')
        ->orderBy('nombre', 'asc')->get();

        return ['zonas' => $zonas];
    }
    
    public function listarZonaPdf(){
       $zonas = Zona::where('id', '>', '1') 
       ->select('id', 'nombre', 'descripcion', 'condicion')
       ->orderBy('nombre', 'asc')->get();     
       
    
       //$fecha = getdate();
       //$actual= "";
       //$actual= "".$fecha["mday"]."".$fecha["year"]."".$fecha["year"]."".$fecha["hours"]."".$fecha["minutes"]."".$fecha["seconds"];

       //$nom ='zonas'."".$actual.".pdf";

       $cont=Zona::count();

       $pdf = \PDF::loadView('pdf.zonaspdf',['zonas'=>$zonas,'cont'=>$cont]);
        return $pdf->download('zonas.pdf');
       //return $pdf->download($nom);
    }

    public function store(Request $request)
    {
        if(!$request->ajax()) return redirect('/'); 
        $zona = new Zona();
        $zona->nombre = $request->nombre;
        $zona->descripcion = $request->descripcion;
        $zona->condicion = '1';
        $zona->save();
    }

    public function update(Request $request)
    {
        if(!$request->ajax()) return redirect('/'); 
        $zona = Zona::findOrFail($request->id);
        $zona->nombre = $request->nombre;
        $zona->descripcion = $request->descripcion;
        $zona->condicion = '1';
        $zona->save();  
    }

    public function desactivar(Request $request)
    {
        if(!$request->ajax()) return redirect('/'); 
        $zona = Zona::findOrFail($request->id);
        $zona->condicion = '0';
        $zona->save();
    }

    public function activar(Request $request)
    {
        if(!$request->ajax()) return redirect('/'); 
        $zona = Zona::findOrFail($request->id);
        $zona->condicion = '1';
        $zona->save();
    }
}
