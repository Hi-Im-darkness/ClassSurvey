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

Route::get('/import/student', 'ExcelImportController@index');
Route::post('/import/student', 'ExcelImportController@studentImport')->name('import');
/* Route::get('/import/teacher', 'ExcelImportController@index'); */
/* Route::post('/import/teacher', 'ExcelImportController@teacherImport')->name('import'); */

Route::get('/login', 'AuthController@showLogin');
Route::post('/login', 'AuthController@login');
Route::get('/logout', 'AuthController@logout');

Route::get('/courses', 'SurveyController@showCourses');
Route::get('/surveyform', 'SurveyController@showSurveyForm');
Route::post('/surveyform', 'SurveyController@doSurvey');
