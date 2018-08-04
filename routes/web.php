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

Route::get('/welcome', 'HomeController@welcome')->name('welcome');

// 首页
Route::get('/', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::post('/api/home', 'ApiController@home')->name('home');
Route::post('/api/change', 'ApiController@change_integral')->name('change_integral');
Route::post('/api/integral_list', 'ApiController@integral_list')->name('integral_list');
