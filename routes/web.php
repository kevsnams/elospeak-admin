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
Route::resource('countries', 'CountriesController');
Route::resource('enrollments', 'EnrollmentController');
Route::resource('customer-messages', 'CustomerMessagesController');

Route::name('settings.')->group(function () {
    Route::get('/settings', 'WebsiteSettingsController@index')->name('index');
    Route::post('/settings', 'WebsiteSettingsController@save')->name('save');

    Route::get('/settings/register', 'WebsiteSettingsController@register')->name('register');
    Route::post('/settings/modify', 'WebsiteSettingsController@modify')->name('modify');
    Route::post('/settings/delete', 'WebsiteSettingsController@delete')->name('delete');
    Route::post('/settings/add', 'WebsiteSettingsController@add')->name('add');
});

Route::name('teacher.')->group(function () {
    Route::get('/teacher/educational-attainment', 'TeachersController@getEducationalAttainment')->name('educational-attainment');
});

Route::name('classroom.')->group(function () {
    Route::get('/classroom/status', 'ClassroomsController@getStatusAction')->name('status');
});

Route::name('enrollment.')->group(function () {
    Route::get('/enrollment/pdf/{id}', 'EnrollmentController@pdf')->name('pdf');
});