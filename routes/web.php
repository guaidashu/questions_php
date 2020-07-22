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
    Route::get('get_question_list', 'admin\QuestionController@getQuestionList');
    Route::post('add_question', 'admin\QuestionController@addQuestion');
    Route::put('update_question', 'admin\QuestionController@updateQuestion');
    Route::delete('delete_question', 'admin\QuestionController@deleteQuestion');
    Route::post('login', 'admin\UserController@login');
    Route::get('get_info', 'admin\UserController@getInfo');
    Route::post('add_physique', 'admin\PhysiqueController@addPhysique');
    Route::get('get_physique_list', 'admin\PhysiqueController@getPhysiqueList');
    Route::get('init_table', 'api\CreateTableController@createTable');
});

Route::prefix('api')->group(function () {
    Route::get('test_token', 'api\TestController@testToken')->middleware("check_token");
    Route::get('error', 'api\ErrorController@error')->name("api_error");
    Route::get('test', 'api\TestController@testMiniProgram');
    Route::get('get_question_list', 'api\QuestionController@getAllQuestion');
    Route::post('submit_result', 'api\HistoryController@submitResult');
    Route::get('get_history_result', 'api\HistoryController@getHistoryResult');
    Route::get('get_history_list_by_user_id', 'api\HistoryController@getHistoryListByUserId');
    Route::get('login', 'api\UserController@login');
});

