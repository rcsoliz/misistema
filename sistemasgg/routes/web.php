<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



// guest invitados
Route::group(['middleware'=>['guest']],function(){
    Route::get('/','Auth\LoginController@showLoginForm');
    Route::post('/desch', 'Auth\LoginController@login')->name('login');
});

// auth usuario atuentificado
Route::group(['middleware'=>['auth']],function(){

    Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
    Route::get('/deshboard', 'DashboardController');
    //Notificaiones
    Route::post('/notification/get', 'NotificationController@get'); 

    Route::get('/main', function () {
        return view('contenido/contenido');
    })->name('main');

    Route::group(['middleware' =>['Administrador']], function(){
        
        Route::get('/clase', 'ClaseController@index');
        Route::get('/clase/selectClase', 'ClaseController@selectClase');
        Route::post('/clase/registrar', 'ClaseController@store');
        Route::put('/clase/actualizar', 'ClaseController@update');
        Route::put('/clase/desactivar', 'ClaseController@desactivar');
        Route::put('/clase/activar', 'ClaseController@activar');
        Route::get('/clase/listarClasePdf', 'ClaseController@listarClasePdf')->name('clase_pdf');
        
        Route::get('/zona', 'ZonaController@index');
        Route::get('/zona/selectZona', 'ZonaController@selectZona');
        Route::post('/zona/registrar', 'ZonaController@store');
        Route::put('/zona/actualizar', 'ZonaController@update');
        Route::put('/zona/desactivar', 'ZonaController@desactivar');
        Route::put('/zona/activar', 'ZonaController@activar');
        Route::get('/zona/listarZonaPdf', 'ZonaController@listarZonaPdf')->name('zonas_pdf');
        
        Route::get('/parametro', 'ParametroController@index');
        Route::get('/parametro/selectParametro', 'ParametroController@selectParametro');
        Route::post('/parametro/registrar', 'ParametroController@store');
        Route::put('/parametro/actualizar', 'ParametroController@update');
        Route::put('/parametro/desactivar', 'ParametroController@desactivar');
        Route::put('/parametro/activar', 'ParametroController@activar');
        Route::get('/parametro/selectTipoAlimentacion', 'ParametroController@selectTipoAlimentacion');
        Route::get('/parametro/selectCondicionCorporal', 'ParametroController@selectCondicionCorporal');
        Route::get('/parametro/selectDias', 'ParametroController@selectDias');
        Route::get('/parametro/listarParametroPdf', 'ParametroController@listarParametroPdf')->name('parametros_pdf');
        Route::get('/parametro/selectRangoFecha', 'ParametroController@selectRangoFecha');

        Route::get('/rol', 'RolController@index');
        Route::get('/rol/selectRol', 'RolController@selectRol');
        Route::post('/rol/registrar', 'RolController@store');
        Route::put('/rol/actualizar', 'RolController@update');
        Route::put('/rol/desactivar', 'RolController@desactivar');
        Route::put('/rol/activar', 'RolController@activar');
        
        Route::get('/user', 'UserController@index');
        Route::post('/user/registrar', 'UserController@store');
        Route::put('/user/actualizar', 'UserController@update');
        Route::put('/user/desactivar', 'UserController@desactivar');
        Route::put('/user/activar', 'UserController@activar');
        
        Route::get('/item', 'ItemController@index');
        Route::post('/item/registrar', 'ItemController@store');
        Route::put('/item/actualizar', 'ItemController@update');
        Route::put('/item/desactivar', 'ItemController@desactivar');
        Route::put('/item/activar', 'ItemController@activar');
        Route::get('/item/buscarItem', 'ItemController@buscarItem');
        Route::get('/item/listarItem', 'ItemController@listarItem');
        Route::get('/item/selectItem', 'ItemController@selectItem');
        Route::get('/item/listarPdf', 'ItemController@listarPdf')->name('items_pdf');

        Route::get('/ganadero', 'GanaderoController@index');
        Route::get('/ganadero/selectGanadero', 'GanaderoController@selectGanadero');
        Route::post('/ganadero/registrar', 'GanaderoController@store');
        Route::put('/ganadero/actualizar', 'GanaderoController@update');
        Route::put('/ganadero/desactivar', 'GanaderoController@desactivar');
        Route::put('/ganadero/activar', 'GanaderoController@activar');
        Route::get('/ganadero/listarGanaderoPdf', 'GanaderoController@listarGanaderoPdf')->name('ganadero_pdf');

        Route::get('/ganaderobaja/indexbaja', 'GanaderoController@indexbaja');
        Route::put('/ganaderobaja/activar', 'GanaderoController@activar'); 
        Route::get('/ganadero/listarGanaderoBajaPdf', 'GanaderoController@listarGanaderoBajaPdf')->name('ganaderobaja_pdf');

        Route::get('/estancia', 'EstanciaController@index');
        Route::post('/estancia/registrar', 'EstanciaController@store');
        Route::put('/estancia/actualizar', 'EstanciaController@update');
        Route::put('/estancia/desactivar', 'EstanciaController@desactivar');
        Route::put('/estancia/activar', 'EstanciaController@activar');
        Route::get('/estancia/selectEstancia', 'EstanciaController@selectEstancia');
        Route::get('/estancia/listarEstanciaPdf', 'EstanciaController@listarEstanciaPdf')->name('estancia_pdf');

        Route::get('/estancia/indexbaja', 'EstanciaController@indexbaja');
        Route::put('/estancia/activar', 'EstanciaController@activar'); 
        Route::get('/estancia/listarEstanciaBajaPdf', 'EstanciaController@listarEstanciaBajaPdf')->name('estanciabaja_pdf');

        Route::get('/visita', 'VisitaController@index');
        Route::post('/visita/registrar', 'VisitaController@store');
        Route::put('/visita/desactivar', 'VisitaController@desactivar');
        Route::get('/visita/obtenerCabecera', 'VisitaController@obtenerCabecera'); 
        Route::get('/visita/obtenerDetalles', 'VisitaController@obtenerDetalles'); 
        Route::get('/visita/pdf/{id}', 'VisitaController@pdf')->name('visita_pdf'); 

        Route::get('/visita/listarVisitasPorFechas', 'VisitaController@listarVisitasPorFechas'); 
     });

    Route::group(['middleware' => ['Captador']], function () {    
        
        Route::get('/zona', 'ZonaController@index');
        Route::get('/zona/selectZona', 'ZonaController@selectZona');
        Route::post('/zona/registrar', 'ZonaController@store');
        Route::put('/zona/actualizar', 'ZonaController@update');
        Route::put('/zona/desactivar', 'ZonaController@desactivar');
        Route::put('/zona/activar', 'ZonaController@activar');
        Route::get('/zona/listarZonaPdf', 'ZonaController@listarZonaPdf')->name('zonas_pdf');

        Route::get('/ganadero', 'GanaderoController@index');
        Route::get('/ganadero/selectGanadero', 'GanaderoController@selectGanadero');
        Route::post('/ganadero/registrar', 'GanaderoController@store');
        Route::put('/ganadero/actualizar', 'GanaderoController@update');
        Route::put('/ganadero/desactivar', 'GanaderoController@desactivar');
        Route::put('/ganadero/activar', 'GanaderoController@activar');
        Route::get('/ganadero/listarGanaderoPdf', 'GanaderoController@listarGanaderoPdf')->name('ganadero_pdf');

        Route::get('/estancia', 'EstanciaController@index');
        Route::post('/estancia/registrar', 'EstanciaController@store');
        Route::put('/estancia/actualizar', 'EstanciaController@update');
        Route::put('/estancia/desactivar', 'EstanciaController@desactivar');
        Route::put('/estancia/activar', 'EstanciaController@activar');
        Route::get('/estancia/selectEstancia', 'EstanciaController@selectEstancia');
        Route::get('/estancia/listarEstanciaPdf', 'EstanciaController@listarEstanciaPdf')->name('estancia_pdf');

        Route::get('/visita', 'VisitaController@index');
        Route::post('/visita/registrar', 'VisitaController@store');
        Route::put('/visita/desactivar', 'VisitaController@desactivar');
        Route::get('/visita/obtenerCabecera', 'VisitaController@obtenerCabecera'); 
        Route::get('/visita/obtenerDetalles', 'VisitaController@obtenerDetalles'); 
        Route::get('/visita/pdf/{id}', 'VisitaController@pdf')->name('visita_pdf'); 

        Route::get('/visita/listarVisitasPorFechas', 'VisitaController@listarVisitasPorFechas'); 
    });
    
    Route::group(['middleware' => ['Operador']], function () {    
        Route::get('/zona', 'ZonaController@index');
        Route::get('/zona/selectZona', 'ZonaController@selectZona');
        Route::post('/zona/registrar', 'ZonaController@store');
        Route::put('/zona/actualizar', 'ZonaController@update');
        Route::put('/zona/desactivar', 'ZonaController@desactivar');
        Route::put('/zona/activar', 'ZonaController@activar');
        Route::get('/zona/listarZonaPdf', 'ZonaController@listarZonaPdf')->name('zonas_pdf');

        Route::get('/parametro', 'ParametroController@index');
        Route::get('/parametro/selectParametro', 'ParametroController@selectParametro');
        Route::post('/parametro/registrar', 'ParametroController@store');
        Route::put('/parametro/actualizar', 'ParametroController@update');
        Route::put('/parametro/desactivar', 'ParametroController@desactivar');
        Route::put('/parametro/activar', 'ParametroController@activar');
        Route::get('/parametro/selectTipoAlimentacion', 'ParametroController@selectTipoAlimentacion');
        Route::get('/parametro/selectCondicionCorporal', 'ParametroController@selectCondicionCorporal');
        Route::get('/parametro/selectDias', 'ParametroController@selectDias');
        Route::get('/parametro/listarParametroPdf', 'ParametroController@listarParametroPdf')->name('parametros_pdf');
        Route::get('/parametro/selectRangoFecha', 'ParametroController@selectRangoFecha');
        
        Route::get('/item', 'ItemController@index');
        Route::post('/item/registrar', 'ItemController@store');
        Route::put('/item/actualizar', 'ItemController@update');
        Route::put('/item/desactivar', 'ItemController@desactivar');
        Route::put('/item/activar', 'ItemController@activar');
        Route::get('/item/buscarItem', 'ItemController@buscarItem');
        Route::get('/item/listarItem', 'ItemController@listarItem');
        Route::get('/item/selectItem', 'ItemController@selectItem');
        Route::get('/item/listarPdf', 'ItemController@listarPdf')->name('items_pdf');
        
        Route::get('/ganadero', 'GanaderoController@index');
        Route::get('/ganadero/selectGanadero', 'GanaderoController@selectGanadero');
        Route::post('/ganadero/registrar', 'GanaderoController@store');
        Route::put('/ganadero/actualizar', 'GanaderoController@update');
        Route::put('/ganadero/desactivar', 'GanaderoController@desactivar');
        Route::put('/ganadero/activar', 'GanaderoController@activar');
        Route::get('/ganadero/listarGanaderoPdf', 'GanaderoController@listarGanaderoPdf')->name('ganadero_pdf');
 
        Route::get('/estancia', 'EstanciaController@index');
        Route::post('/estancia/registrar', 'EstanciaController@store');
        Route::put('/estancia/actualizar', 'EstanciaController@update');
        Route::put('/estancia/desactivar', 'EstanciaController@desactivar');
        Route::put('/estancia/activar', 'EstanciaController@activar');
        Route::get('/estancia/selectEstancia', 'EstanciaController@selectEstancia');
        Route::get('/estancia/listarEstanciaPdf', 'EstanciaController@listarEstanciaPdf')->name('estancia_pdf');

        Route::get('/visita', 'VisitaController@index');
        Route::post('/visita/registrar', 'VisitaController@store');
        Route::put('/visita/desactivar', 'VisitaController@desactivar');
        Route::get('/visita/obtenerCabecera', 'VisitaController@obtenerCabecera'); 
        Route::get('/visita/obtenerDetalles', 'VisitaController@obtenerDetalles'); 
        Route::get('/visita/pdf/{id}', 'VisitaController@pdf')->name('visita_pdf'); 

        Route::get('/visita/listarVisitasPorFechas', 'VisitaController@listarVisitasPorFechas'); 
    });

});