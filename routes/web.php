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

Route::get('/young/{age}','UserController@young')->middleware('young');

// 首页
Route::get('/', 'HomeController@home')->name('home');

// home page
Route::get('/', 'StaticPagesController@home')->name('home');

// about page
Route::get('/about', 'StaticPagesController@about')->name('about');

//// 多条路由简写
//Route::resource('user', 'UserController');