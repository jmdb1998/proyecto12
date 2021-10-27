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

/*Route::get('usuarios/datalles', function (){
    return 'mostrando detalles del usuario: ' . $_GET['id'];
});*/

Route::get('usuarios', 'UserController@index')->name('users.index');

Route::get('usuarios/crear', 'UserController@create')->name('users.create');

Route::post('usuarios/crear', 'UserController@store')->name('users.store');

Route::get('usuarios/{user}', 'UserController@show')->where('id','[0-9]+')->name('users.show');

Route::get('saludo/{name}/{nickname?}', 'WellcomeUserController');