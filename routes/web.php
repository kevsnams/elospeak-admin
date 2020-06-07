<?php

use App\Http\Controllers\SignupApplicationConrtoller;
use Illuminate\Support\Facades\Route;

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

Route::name('pages.')->group(function () {
    Route::get('/', 'PageController@index')->name('index');
    Route::post('/auth', 'PageController@auth')->name('auth');
    Route::get('/unauth', 'PageController@unauth')->name('unauth');
});

Route::middleware('auth')->group(function () {
    Route::resources([
        'students' => 'StudentController',
        'teachers' => 'TeacherController',
        'classrooms' => 'ClassroomController',
        'messages' => 'MessageController',
        'settings' => 'SettingController',
        'applications' => 'SignupApplicationController'
    ]);
});

Route::post('/applications/accept/{application}', 'SignupApplicationController@accept')->name('applications.accept');
Route::post('/applications/deny/{application}', 'SignupApplicationController@deny')->name('applications.deny');
