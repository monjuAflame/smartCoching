<?php

use Illuminate\Http\Request;
use App\User;
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


Route::get('/admin', function () {
	$users = User::get();
    return view('auth.login',compact('users'));
});

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');


Route::get('/', 'FrontEnd\HomeController@index')->name('frontEnd.home');
Route::get('/about', 'FrontEnd\HomeController@about')->name('frontEnd.about');
Route::get('/contact', 'FrontEnd\HomeController@contact')->name('frontEnd.contact');
Route::post('/', 'FrontEnd\HomeController@searchStudent')->name('frontEnd.searchStudentId');
Route::get('/print/{s_fee_id}', 'FrontEnd\HomeController@printFullInvoice')->name('print');



Route::get('/{anypath}', 'HomeController@index')->where( 'path', '.*' );


