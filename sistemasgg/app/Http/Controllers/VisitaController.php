<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;
use App\Visita;
use App\DetalleVisita;
use App\User; 
use App\Notifications\NotifyAdmin;
//use App\Parametro; 

class VisitaController extends Controller
{
      
    public function index(Request $request){
        if (!$request->ajax()) return redirect('/');
        $buscar = $request->buscar;
        $criterio = $request->criterio;
         
        $rolUsuario =\Auth::user()->id;
         
        if ($buscar==''){
            if ($rolUsuario == 1){
                $visitas = Visita::join('estancias','estancias.id','=','visitas.idestancia')
                ->join('users', 'users.id','=','visitas.idusuario')
                ->select('visitas.id','visitas.glosa','visitas.fecha_hora','visitas.condicion',
                'visitas.total', 
                'users.nombre as usuario', 
                'estancias.nombre as estancia')
                ->where('visitas.condicion', '=', 1)
                ->orderBy('visitas.id', 'desc')->paginate(5);
            }
            else{
                $visitas = Visita::join('estancias','estancias.id','=','visitas.idestancia')
                ->join('users', 'users.id','=','visitas.idusuario')
                ->select('visitas.id','visitas.glosa','visitas.fecha_hora','visitas.condicion',
                'visitas.total', 
                'users.nombre as usuario', 
                'estancias.nombre as estancia')
                ->where('visitas.condicion', '=', 1)
                ->where('visitas.idusuario', '=', $rolUsuario)
                ->orderBy('visitas.id', 'desc')->paginate(5);
            }
            //'users.id','users.nombre as usuario', 
            //'estancias.id', 'estancias.nombre as estancia')
        }
        else{
            if ($rolUsuario == 1){
                $visitas = Visita::join('estancias','estancias.id','=','visitas.idestancia')
                ->join('users', 'users.id','=','visitas.idusuario')
                ->select('visitas.id','visitas.glosa','visitas.fecha_hora','visitas.condicion',
                'visitas.total', 
                'users.nombre as usuario', 
                'estancias.nombre as estancia')
                ->where('visitas.'.$criterio, 'like', '%'. $buscar . '%')
                ->orderBy('visitas.id', 'desc')->paginate(5);
            }
            else {
                $visitas = Visita::join('estancias','estancias.id','=','visitas.idestancia')
                ->join('users', 'users.id','=','visitas.idusuario')
                ->select('visitas.id','visitas.glosa','visitas.fecha_hora','visitas.condicion',
                'visitas.total', 
                'users.nombre as usuario', 
                'estancias.nombre as estancia')
                ->where('visitas.'.$criterio, 'like', '%'. $buscar . '%')
                ->where('visitas.idusuario', '=', $rolUsuario)
                ->orderBy('visitas.id', 'desc')->paginate(5);
            }
            // 'users.id','users.nombre as usuario', 
            // 'estancias.id', 'estancias.nombre as estancia')
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

   public function obtenerCabecera(Request $request)
   {
    if (!$request->ajax()) return redirect('/');
    $id = $request->id;
    $visitaneo = Visita::join('estancias','estancias.id','=','visitas.idestancia')
    ->join('ganaderos', 'ganaderos.id','=','estancias.idganadero')
    ->select('estancias.nombre as estancia', 'visitas.id as num_documento',
     'visitas.tipo_documento', 'ganaderos.ncompleto as ganadero', 
     'visitas.total')
    ->where('visitas.id', '=', $id)
    ->orderBy('visitas.id', 'desc')->take(1)->get();
    return ['visitaneo' => $visitaneo];
   }

   public function obtenerDetalles(Request $request)
   {
    if (!$request->ajax()) return redirect('/');
    $id = $request->id;
    $detallesneo = DetalleVisita::join('items','detalle_visitas.iditem','=','items.id')
    ->join('parametros as par1', 'detalle_visitas.categoria','=','par1.id')
    ->join('parametros as par2', 'detalle_visitas.alimentacion','=','par2.id')
    ->join('parametros as par3', 'detalle_visitas.timpsalida','=','par3.id')
    ->select('items.nombre as item', 'detalle_visitas.cantparcial', 'detalle_visitas.cantventa',
     'par1.nombre as categoria', 'detalle_visitas.pstandar', 'par3.nombre as timpsalida', 
     'detalle_visitas.observacion', 'par2.nombre as alimentacion')
    ->where('detalle_visitas.idvisita', '=', $id)
    ->orderBy('detalle_visitas.id', 'desc')->get();
    
    return ['detallesneo' => $detallesneo];
   }

   public function pdf(Request $request, $id)
   {
       $visita =  Visita::join('estancias','estancias.id','=','visitas.idestancia')
       ->join('ganaderos', 'ganaderos.id','=','estancias.idganadero')
       ->join('users', 'users.id' ,'=', 'visitas.idusuario')
       ->select('estancias.nombre as estancia', 'visitas.id as num_documento',
        'visitas.tipo_documento', 'visitas.fecha_hora','ganaderos.ncompleto as ganadero', 
        'ganaderos.direccion', 'ganaderos.telefeno as telefono', 'ganaderos.celular', 'ganaderos.email',
        'visitas.total', 'users.nombre as nombre')
       ->where('visitas.id', '=', $id)
       ->orderBy('visitas.id', 'desc')->take(1)->get();

       $detalles = DetalleVisita::join('items','detalle_visitas.iditem','=','items.id')
       ->join('parametros as par1', 'detalle_visitas.categoria','=','par1.id')
       ->join('parametros as par2', 'detalle_visitas.alimentacion','=','par2.id')
       ->join('parametros as par3', 'detalle_visitas.timpsalida','=','par3.id')
       ->select('items.nombre as item', 'detalle_visitas.cantparcial', 'detalle_visitas.cantventa',
        'par1.nombre as categoria', 'detalle_visitas.pstandar', 'par3.nombre as timpsalida',  
        'detalle_visitas.observacion', 'par2.nombre as alimentacion')
       ->where('detalle_visitas.idvisita', '=', $id)
       ->orderBy('detalle_visitas.id', 'desc')->get();

       $pdf = \PDF::loadView('pdf.visita',['visita'=>$visita,'detalles'=>$detalles]);
       return $pdf->download('visita-'.$id.'.pdf');
   }

    public function store(Request $request)
    {
        if(!$request->ajax()) return redirect('/'); 
        try{
            DB::beginTransaction();

            Visita::where('condicion',1)
                  ->where('idestancia',$request->idestancia)  
                  ->update(['condicion' => 0]);

            $mytime= Carbon::now('America/La_Paz');
            $visita = new Visita();
            $visita->idestancia = $request->idestancia;
            $visita->idusuario = \Auth::user()->id;
            $visita->glosa = ''; //$request->glosa
            $visita->fecha_hora = $mytime->toDateString();
            $visita->condicion ='1';
            $visita->total = $request->total;
            $visita->tipo_documento =$request->tipo_documento;
            $visita->save();
          
            $detalles = $request->data;
            foreach($detalles as $ep=>$det)
            {
                $detallevisita = new DetalleVisita();
                $detallevisita->idvisita = $visita->id;
                $detallevisita->iditem = $det['iditem'];
                $detallevisita->cantparcial = $det['cantparcial'];
                $detallevisita->cantventa = $det['cantventa'];
                $detallevisita->categoria = $det['categoria'];
                $detallevisita->pstandar =  $det['pstandar'];
                $detallevisita->timpsalida =$det['timpsalida'];
                $detallevisita->observacion = $det['observacion'];
                $detallevisita->alimentacion = $det['alimentacion'];
                $detallevisita->save();
            }

            $fechaActual= date('Y-m-d');
            $numIngresos= DB::table('visitas')->whereDate('created_at', $fechaActual)->count();
            //$numVentas = DB::table('ventas')->whereDate('created_at', $fechaActual)->count(); 

           /* $arregloDatos = [
                'visitas' => [ 
                    'numero' => $numIngresos,
                    'msj' => 'Visitas'  
                ], 
                'ingresos' => [ 
                            'numero' => $numIngresos, 
                            'msj' => 'Visitas1' 
                ] 
            ]; */
            $arregloDatos = [
                'visitas' => [ 
                    'numero' => $numIngresos,
                    'msj' => 'Visitas'  
                ]
            ];
            
            $allUsers = User::all();
            foreach ($allUsers as $notificar){
                User::findOrFail($notificar->id)->notify(new NotifyAdmin($arregloDatos));  
            }

            DB::commit();
        }catch(Exception $e){
            DB::rollBack();
        }

    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $visitacab = Visita::findOrFail($request->id);
        $visitacab->condicion = '0';
        $visitacab->save();
    }

   /* Reportes 
   1.- Listado visitas por fechas*/
    public function listarVisitasPorFechas(Request $request){
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
