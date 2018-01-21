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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', 'HomeController@getIndex')->middleware('guest');
Route::get('/news', 'Controller@getNews');
Route::get('/highscore', 'Controller@getHighscore');
Route::get('/ajax/register', 'HomeController@registerAjaxCheck');

Route::prefix('/profile')->group(function() {
	Route::get('/index', 'ProfileController@getIndex');
});