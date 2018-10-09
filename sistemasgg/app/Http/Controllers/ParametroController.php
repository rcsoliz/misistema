<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Parametro; // importo mi modelo "Parametro" 
class ParametroController extends Controller
{
    public function index(Request $request)
    {
        if(!$request->ajax()) return redirect('/'); 
     
        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar==''){
            $parametros = Parametro::orderBy('id', 'desc')->paginate(5);
        } 
        else {
            $parametros = Parametro::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id','desc')->paginate(5);
        }

        return [
            'pagination' => [
                'total'        => $parametros->total(),
                'current_page' => $parametros->currentPage(),
                'per_page'     => $parametros->perPage(),
                'last_page'    => $parametros->lastPage(),
                'from'         => $parametros->firstItem(),
                'to'           => $parametros->lastItem(),
            ],
            'parametros' => $parametros   
        ];
    }

    public function listarParametroPdf()
    {
        $parametros = Parametro::where('id', '>=', '1') 
        ->select('codigo', 'nombre', 'valor1', 'condicion')
        ->orderBy('codigo', 'asc')->get();     
       
        $cont=Parametro::count();

        $pdf = \PDF::loadView('pdf.parametrospdf',['parametros'=>$parametros,'cont'=>$cont]);
        return $pdf->download('parametros.pdf');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!$request->ajax()) return redirect('/'); 
        $parametro = new Parametro();
        $parametro->codigo = $request->codigo;
        $parametro->nombre = $request->nombre;
        $parametro->valor1 = $request->valor1;
        $parametro->valor2 = $request->valor2;
        $parametro->condicion = '1';
        $parametro->save();
    }

    public function selectParametro()
    { 
        if(!$request->ajax()) return redirect('/'); 
        $parametros = Parametro::whereBetween('codigo',  [201, 210])
        ->select('id','nombre')
        ->orderBy('nombre', 'desc')->get();

        return ['parametros' => $parametros];
    } 
    public function selectCondicionCorporal(){
        //if(!$request->ajax()) return redirect('/'); 
        $parametros = Parametro::whereBetween('codigo', [101, 110])
        ->where('condicion', '=', '1') 
        ->select('id','nombre')
        ->orderBy('nombre')->get();
        return ['parametros' => $parametros];
    } 
    public function selectTipoAlimentacion(){
        //if(!$request->ajax()) return redirect('/'); 
        $parametros = Parametro::whereBetween('codigo', [301, 399])
        ->where('condicion', '=', '1') 
        ->select('id','nombre')
        ->orderBy('nombre', 'desc')->get();
        return ['parametros' => $parametros];
    } 
    public function selectDias(){
        if(!$request->ajax()) return redirect('/'); 
        $parametros = Parametro::whereBetween('codigo', [401, 410])
        ->where('condicion', '=', '1') 
        ->select('id','nombre')
        ->orderBy('nombre', 'desc')->get();
        return ['parametros' => $parametros];
    }
    
    public function selectRangoFecha()
    {
        # SELECT id, nombre FROM `parametros` WHERE codigo BETWEEN '501' and '559' ORDER BY `parametros`.`id` ASC
       // if(!$request->ajax()) return redirect('/'); 
        $parametros = Parametro::whereBetween('codigo', [501, 559])
        ->where('condicion', '=', '1') 
        ->select('id','nombre')
        ->orderBy('id', 'ASC')->get();
        return ['parametros' => $parametros];
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if(!$request->ajax()) return redirect('/'); 
        $parametro = Parametro::findOrFail($request->id);
        $parametro->nombre = $request->nombre;
        $parametro->valor1 = $request->valor1;
        $parametro->valor2 = $request->valor2;
        $parametro->condicion = '1';
        $parametro->save();  
    }

    public function desactivar(Request $request)
    {
        if(!$request->ajax()) return redirect('/'); 
        $parametro = Parametro::findOrFail($request->id);
        $parametro->condicion = '0';
        $parametro->save();
    }

    public function activar(Request $request)
    {
        if(!$request->ajax()) return redirect('/'); 
        $parametro = Parametro::findOrFail($request->id);
        $parametro->condicion = '1';
        $parametro->save();
    }
}
