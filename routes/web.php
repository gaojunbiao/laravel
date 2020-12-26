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

Route::get('/', 'Admin\LoginController@login');
//后台路由
Route::group(['prefix'=>'admin','namespace'=>'Admin'],function(){
	//登录路由
	Route::any('login', 'LoginController@login');
	
});

Route::group(['prefix'=>'admin','namespace'=>'Admin','middleware'=>'IsLogin'],function(){
	//首页路由
	Route::get('index', 'IndexController@index');
	//欢迎页路由
	Route::get('welcome', 'IndexController@welcome');
	//退出登录路由
	Route::get('logout', 'IndexController@logout');
	//会员删除路由
	Route::post('member/delAll', 'MemberController@delAll');
	//会员上传头像路由
	Route::post('member/upload', 'MemberController@upload');
	//会员修改密码路由
	Route::get('member/editpass/{id}', 'MemberController@editpass');
	Route::post('member/editpass', 'MemberController@editpass');
	//会员资源路由
	Route::resource('member', 'MemberController');
	//分类删除路由
	Route::post('cate/delAll', 'CateController@delAll');
	//分类增加子栏目
	Route::get('cate/createson/{id}/{level}', 'CateController@createson');
	Route::post('cate/createson', 'CateController@createson');
	//分类排序
	Route::get('cate/sort/{id}/{sort}', 'CateController@sort');
	//分类资源路由
	Route::resource('cate', 'CateController');

});





