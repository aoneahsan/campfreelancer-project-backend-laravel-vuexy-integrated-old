<?php

use App\Events\WebsocketDemoEvent;
use Illuminate\Support\Facades\Auth;
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
// A uth: :r outes(['verify' => true]);

Route::redirect('/', '/admin');
Route::get('/verify', 'Auth\AuthyVerifyController@index')->name('verify');
Route::post('/verify', 'Auth\AuthyVerifyController@verify')->name('verify');
Route::get('/home', 'HomeController@index')->name('home')->middleware('verified');


// Init Basic App Users With Respective Roles & Permissions
Route::get('/init_urp', 'Api\ApiSystemController@InitAppDefaultUsersSetup');

// Social Login
Route::get('login/{provider}', 'Auth\LoginController@redirectToProvider');
Route::get('login/{provider}/callback','Auth\LoginController@handleProviderCallback');

// Resend Verify Code
Route::post('resend-verify-code', 'Auth\AuthyVerifyController@resendCode')->middleware('throttle:10,1');

Route::get('user/analytics-report', 'Api\User\ApiUserAnalyticsController@index');


Route::get('/admin/{any?}', 'Admin\AdminController@index')->where('any', '.*');

// Au th: :r outes();

Route::get('/home', 'HomeController@index')->name('home');
