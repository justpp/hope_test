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
Route::post('/deploy','DeploymentController@deploy');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/user/list', 'HomeController@userList');

Route::group(['namespace'=>'File','prefix'=>'file'],function () {
    // 首页
    Route::get('/', 'IndexController@index');

    // 公司网盘
    Route::get('company', 'CompanyController@index');
    Route::get('company/list', 'CompanyController@listData');
});

