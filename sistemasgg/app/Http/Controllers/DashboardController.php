<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
 
use Illuminate\Http\Request;
//use DB;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $anio=date('Y');

        /*$visitas=DB::table('visitas as i')
        ->select(DB::raw('MONTH(i.fecha_hora) as mes'),
        DB::raw('YEAR(i.fecha_hora) as anio'),
        DB::raw('COUNT(i.total) as total'))
        ->whereYear('i.fecha_hora',$anio)
        ->groupBy(DB::raw('MONTH(i.fecha_hora)'),DB::raw('YEAR(i.fecha_hora)'))
        ->get();*/ 
        //DB::select('exec Miprocedimiento "'.$parametro.'"');
        
        $visitas= DB::select('call getVistaPorMes('. $anio .')');

        /*$visitas= DB::table('visitas as i')
        ->select(DB::raw('CASE 
        WHEN MONTH(i.fecha_hora) =1 THEN Enero
        WHEN MONTH(i.fecha_hora) =2 THEN Febrero
        WHEN MONTH(i.fecha_hora) =3 THEN Marzo
        WHEN MONTH(i.fecha_hora) =4 THEN Abril
        WHEN MONTH(i.fecha_hora) =5 THEN Mayo
        WHEN MONTH(i.fecha_hora) =6 THEN Junio
        WHEN MONTH(i.fecha_hora) =7 THEN Julio
        WHEN MONTH(i.fecha_hora) =8 THEN Agosto
        WHEN MONTH(i.fecha_hora) =9 THEN Septiembre
        WHEN MONTH(i.fecha_hora) =10 THEN Octubre
        WHEN MONTH(i.fecha_hora) =11 THEN Noviembre
        WHEN MONTH(i.fecha_hora) =12 THEN Diciembre ELSE 1 END as mes'),
        DB::raw('YEAR(i.fecha_hora) as anio'),
        DB::raw('COUNT(i.total) as total'))
        ->whereYear('i.fecha_hora',$anio)
        ->groupBy(DB::raw('MONTH(i.fecha_hora)'),DB::raw('YEAR(i.fecha_hora)'))
        ->get();*/
        //->get();
 
        $visitauser=DB::table('visitas as i')
        ->join('users', 'users.id', '=', 'i.idusuario')
        ->select(DB::raw('users.nombre'),
        DB::raw('YEAR(i.fecha_hora) as anio'),
        DB::raw('SUM(i.total) as total'))
        ->whereYear('i.fecha_hora',$anio)
        ->groupBy(DB::raw('users.nombre'),DB::raw('YEAR(i.fecha_hora)'))
        ->get();

        /*$vistaganado=DB::table('visitas as i')
        ->join('users', 'users.id', '=', 'i.idusuario')
        ->select(DB::raw('users.nombre as nombre'),
        DB::raw('YEAR(i.fecha_hora) as anio'),
        DB::raw('SUM(i.total) as total'))
        ->whereYear('i.fecha_hora',$anio)
        ->groupBy (DB::raw('users.nombre'), DB::raw('YEAR(i.fecha_hora)'))
        ->get();*/

        $vistaganado=DB::select('call getCantidadPorMes('. $anio .')');

        return ['visitas'=>$visitas, 'visitauser'=>$visitauser, 'vistaganado'=>$vistaganado, 'anio'=>$anio];
       // return ['visitas'=>$visitas, 'anio'=>$anio];
    }

    /*$visitas=DB::table('visitas as i')
        ->select(DB::raw('(CASE 
                            WHEN MONTH(i.fecha_hora)=1 THEN enero
                            WHEN MONTH(i.fecha_hora)=2 THEN febrero
                            WHEN MONTH(i.fecha_hora)=3 THEN marzo
                            WHEN MONTH(i.fecha_hora)=4 THEN abril
                            WHEN MONTH(i.fecha_hora)=5 THEN marzo
                            WHEN MONTH(i.fecha_hora)=6 THEN junio
                            WHEN MONTH(i.fecha_hora)=7 THEN julio
                            WHEN MONTH(i.fecha_hora)=8 THEN agosto
                            WHEN MONTH(i.fecha_hora)=9 THEN septiembre
                            WHEN MONTH(i.fecha_hora)=10 THEN octubre
                            WHEN MONTH(i.fecha_hora)=11 THEN nomviembre
                            WHEN MONTH(i.fecha_hora)=12 THEN diciembre
                         ELSE END) as mes'),
        DB::raw('YEAR(i.fecha_hora) as anio'),
        DB::raw('COUNT(i.total) as total'))
       ->whereYear('i.fecha_hora',$anio)
       ->groupBy(DB::raw('mes'),DB::raw('YEAR(i.fecha_hora)'))
       ->get(); */ 
}
