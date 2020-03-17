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

Auth::routes();
Route::get('/app', 'HomeController@app')->name('app');
Route::get('/', 'HomeController@index')->name('home');

Route::resource('teachers', 'TeachersController');
Route::resource('students', 'StudentsController');
Route::resource('classrooms', 'ClassroomsController');

Route::name('classroom.')->group(function () {
    Route::post('/classroom/timeslots', 'ClassroomsController@timeslots')->name('timeslots');
    Route::post('/classroom/teachers', 'ClassroomsController@teachers')->name('teachers');
});

Route::name('settings.')->group(function () {
    Route::get('/settings', 'WebsiteSettingsController@index')->name('index');
    Route::post('/settings', 'WebsiteSettingsController@save')->name('save');

    Route::get('/settings/register', 'WebsiteSettingsController@register')->name('register');
    Route::post('/settings/modify', 'WebsiteSettingsController@modify')->name('modify');
    Route::post('/settings/delete', 'WebsiteSettingsController@delete')->name('delete');
    Route::post('/settings/add', 'WebsiteSettingsController@add')->name('add');
});

Route::name('student.')->group(function () {
    Route::get('/student/classrooms/{id}/{view?}/{date?}', 'StudentsController@classrooms')->name('classrooms');
    Route::get('/student/classrooms/add/{id}/', 'StudentsController@addClassroom')->name('add-classroom');
});

Route::name('teacher.')->group(function () {
    Route::get('/teacher/educational-attainment', 'TeachersController@getEducationalAttainment')->name('educational-attainment');
});

Route::name('enroll.')->group(function() {
    Route::get('/enroll', 'EnrollController@index')->name('index');
    Route::post('/enroll', 'EnrollController@store')->name('store');
    Route::post('/enroll/check-availability', 'EnrollController@checkAvailability')->name('check-availability');
});

Route::name('invoice.')->group(function() {
    Route::get('/invoice/download', 'InvoiceController@download')->name('download');
});