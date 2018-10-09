<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;    //:: filter ()
use Illuminate\Database\Query\Build;  //er::cont
use App\Item;
use App\Clase;
class ItemController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $items = Item::join('clases','items.idclase','=','clases.id')
            ->select('items.id','items.nombre', 'items.condicion', 'items.idclase', 'clases.nombre as clase')
            ->orderBy('items.id', 'desc')->paginate(10);
        }
        else{
            $items = Item::join('clases','items.idclase','=','clases.id')
            ->select('items.id','items.nombre', 'items.condicion', 'items.idclase', 'clases.nombre as clase')
            ->where('items.'.$criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(10);
        }
        
        return [
            'pagination' => [
                'total'        => $items->total(),
                'current_page' => $items->currentPage(),
                'per_page'     => $items->perPage(),
                'last_page'    => $items->lastPage(),
                'from'         => $items->firstItem(),
                'to'           => $items->lastItem(),
            ],
            'items' => $items
        ];
    }

    public function buscarItem(Request $request){
        if (!$request->ajax()) return redirect('/');

        $filtro = $request->filtro;
        $items = Item::where('id','=', $filtro)
        ->select('id', 'nombre')->orderBy('nombre','asc')->take(1)->get();
        
        return ['items' => $items];
        
    }

    public function listarPdf(){
        //listo los items
        $items = Item::join('clases','items.idclase','=','clases.id')
        ->select('items.nombre as nom', 'clases.nombre as clase', 'items.condicion as condicion')
        ->orderBy('items.nombre', 'desc')->get();
        //cuento la cantidad de items  
        $cont=Item::count();

        $pdf = \PDF::loadView('pdf.itemspdf',['items'=>$items,'cont'=>$cont]);
        return $pdf->download('items.pdf');
        // return $pdf->download('items.pdf');
    }

    public function listarItem(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $items = Item::join('clases','items.idclase','=','clases.id')
            ->select('items.id','items.nombre', 'items.condicion', 'items.idclase', 'clases.nombre as clase')
            ->orderBy('items.id', 'desc')->paginate(10);
        }
        else{
            $items = Item::join('clases','items.idclase','=','clases.id')
            ->select('items.id','items.nombre', 'items.condicion', 'items.idclase', 'clases.nombre as clase')
            ->where('items.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('id', 'desc')->paginate(10);
        }
        
        return ['items' => $items];
    }

    public function listarItemBusqueda(Request $request){
        if (!$request->ajax()) return redirect('/');
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if ($buscar==''){
            $items = Item::join('clases','items.idclase','=','clases.id')
            ->select('items.id','items.nombre', 'items.condicion', 'items.idclase', 'clases.nombre as clase')
            ->orderBy('items.id', 'desc')->paginate(10);
        }
    }

    public function selectItem()
    {
       // if (!$request->ajax()) return redirect('/');
            $items = Item::where('condicion', '=', '1')
            ->select('id','nombre')
            ->orderBy('nombre', 'asc')->get();

        return ['items' => $items];
      
    }

    public function store(Request $request)
    {
        if(!$request->ajax()) return redirect('/'); 
        $item = new Item();
        $item->nombre = $request->nombre;
        $item->idclase = $request->idclase;
        $item->condicion = '1';

        $item->save();
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

            $item = Item::findOrFail($request->id);
            $item->nombre = $request->nombre;
            $item->condicion = '1';
            $item->idclase = $request->idclase;
            $item->save();

    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $item = Item::findOrFail($request->id);
        $item->condicion = '0';
        $item->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $item = Item::findOrFail($request->id);
        $item->condicion = '1';
        $item->save();
    }
}
