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
Route::get('/', 'HomeController@index')->name('home');

Route::resource('teachers', 'TeachersController');
Route::resource('students', 'StudentsController');
Route::resource('classrooms', 'ClassroomsController');

Route::name('settings.')->group(function () {
    Route::get('/settings', 'WebsiteSettingsController@index')->name('index');
    Route::post('/settings', 'WebsiteSettingsController@save')->name('save');
});

Route::name('student.')->group(function () {
    Route::post('/student/add-balance/', 'StudentsController@addBalance')->name('add-balance');
});

Route::name('enroll.')->group(function() {
    Route::get('/enroll', 'EnrollController@index')->name('index');
    Route::post('/enroll', 'EnrollController@store')->name('store');
    Route::post('/enroll/check-availability', 'EnrollController@checkAvailability')->name('check-availability');
});