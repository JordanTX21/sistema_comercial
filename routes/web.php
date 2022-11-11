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

Auth::routes(['register' => false]);

Route::get('/', function () {
    return redirect('home');
    //return view('welcome');
});

Route::get('/home', 'HomeController@index')->name('home');

/** FILES */
Route::get('file-view/{folder}/{filename}', 'FileController@viewFile');

Route::group(['middleware'=>'auth'], function () {
    /** RUTAS CLIENTES */
    Route::resource('/clientes', 'ClientesController');
    Route::post('/clientes_search', 'ClientesController@search');
    Route::get('/clientes_search', 'ClientesController@search');
    /** RUTAS EXPEDIENTES */
    Route::resource('/expedientes', 'ExpedientesController');
});
