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
Route::post('/test1',function(){
	echo "test1";
});
Route::any('/test2',function(){
	echo "test2";
});
Route::group(['prefix'=>'home/index'],function(){
	Route::get('index', 'Home\IndexController@index');
	Route::get('getuser','Home\IndexController@getuser');
	Route::any('adduser','Home\IndexController@adduser');
	Route::get('deleteuser','Home\IndexController@deleteuser');
	Route::get('updateuser','Home\IndexController@updateuser');
});





