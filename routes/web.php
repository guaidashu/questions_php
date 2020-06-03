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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get("/", 'IndexController@index');

Route::prefix('admin')->group(function () {
    Route::get('getData', 'admin\QuestionController@getData');
    Route::post('login', 'admin\UserController@login');
    Route::get('get_info', 'admin\UserController@getInfo');
});

Route::prefix('api')->group(function () {
    Route::get('init_table', 'api\CreateTableController@createTable');
});

