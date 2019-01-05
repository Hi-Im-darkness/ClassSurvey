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
Route::post('/import/student', 'ExcelImportController@studentImport');
Route::get('/import/teacher', 'ExcelImportController@index');
Route::post('/import/teacher', 'ExcelImportController@teacherImport');
Route::get('/import/course', 'ExcelImportController@index');
Route::post('/import/course', 'CourseController@import');

Route::get('/login', 'AuthController@showLogin');
Route::post('/login', 'AuthController@login');
Route::get('/logout', 'AuthController@logout');

Route::get('/forms', 'FormController@showForm');

Route::get('/courses', 'CourseController@showCourse');
Route::get('/courses/surveyform', 'SurveyController@showSurveyForm');
Route::post('/courses/surveyform', 'SurveyController@doSurvey');

Route::get('/surveys', 'SurveyController@showSurvey');
Route::post('/surveys', 'SurveyController@addSurvey');
Route::put('/surveys', 'SurveyController@editSurvey');
Route::delete('/surveys', 'SurveyController@deleteSurvey');
Route::get('/surveys/result', 'SurveyController@showResult');

Route::get('/students', 'StudentController@showStudent');
Route::post('/students', 'StudentController@addStudent');
Route::put('/students', 'StudentController@editStudent');
Route::delete('/students', 'StudentController@deleteStudent');

Route::get('/teachers', 'TeacherController@showTeacher');
Route::post('/teachers', 'TeacherController@addTeacher');
Route::put('/teachers', 'TeacherController@editTeacher');
Route::delete('/teachers', 'TeacherController@deleteTeacher');
