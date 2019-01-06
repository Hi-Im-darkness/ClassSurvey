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

Route::post('/import/student', 'StudentController@import');
Route::post('/import/teacher', 'TeacherController@import');
Route::post('/import/course', 'CourseController@import');

Route::get('/login', 'AuthController@showLogin');
Route::post('/login', 'AuthController@login');
Route::get('/logout', 'AuthController@logout');

Route::get('/courses', 'CourseController@showCourse');
Route::get('/courses/liststudent', 'CourseController@listStudent');
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

Route::get('/forms', 'FormController@showForm');
Route::post('/forms', 'FormController@addForm');
Route::put('/forms', 'FormController@editForm');
Route::delete('/forms', 'FormController@deleteForm');
Route::get('/forms/questions', 'QuestionController@showQuestion');
Route::post('/forms/questions', 'QuestionController@addQuestion');
Route::put('/forms/questions', 'QuestionController@editQuestion');
Route::delete('/forms/questions', 'QuestionController@deleteQuestion');
Route::get('/forms/questions/categorys', 'QuestionController@showCategory');
