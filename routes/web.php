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

/* Route::get('/', function () { */
/*     return view('welcome'); */
/* }); */

Route::get('/', 'StudentController@index')->name('index');
Route::post('import', 'StudentController@import')->name('import');

Route::get('login', 'AuthController@showLogin');
Route::post('login', 'AuthController@login');
Route::get('logout', 'AuthController@logout');
