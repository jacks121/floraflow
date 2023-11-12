<?php

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

Route::get('/', function () {
    return view('welcome');
});


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Route::get('/bottle', 'App\Http\Controllers\BottleController@show')->name('bottle');

Route::get('/login', 'App\Http\Controllers\AuthController@showLoginForm')->name('login');
Route::post('/login', 'App\Http\Controllers\AuthController@login');
Route::post('/logout', 'App\Http\Controllers\AuthController@logout')->name('logout');

Route::post('/save-bottle-data', 'App\Http\Controllers\BottleController@saveData')->name('saveBottle');
Route::get('/bottles', 'App\Http\Controllers\BottleController@index')->name('bottles');
Route::post('/update-bottle-status', 'App\Http\Controllers\BottleStatusController@updateStatus')->name('changeStatus');
Route::get('/update-bottle-status', 'App\Http\Controllers\BottleStatusController@index');



