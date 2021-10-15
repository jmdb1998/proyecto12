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

Route::get('/', function () {
    return view('welcome');
});

Route::get('usuarios', function(){
    return '<h1>Usuarios</h1>';
});


/*Route::get('usuarios/datalles', function (){
    return 'mostrando detalles del usuario: ' . $_GET['id'];
});*/

Route::get('usuarios/nuevo', function (){
    return 'Creando un nuevo usuario';
});

Route::get('usuarios/{id}', function($id){
   return 'Mostrando detalles del usuario: '. $id;
})->where('id','[0-9]+');


Route::get('saludo/{name}/{nickname?}', function ($name, $nickname = null){
    return $nickname
        ? 'Bienvenido ' . $name . ' tu apodo es ' . $nickname
        : 'Hola ' . $name;
});