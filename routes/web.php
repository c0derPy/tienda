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
  return view('index');
});

Route::resource('order', 'OrderController');

Route::get('order/webcheckout/{id}', 'OrderController@webCheckout');

Route::get('order/webcheckout/finish/{id}', 'OrderController@payment_finish');