<?php
use Illuminate\Support\Facades\Auth;
use Jrean\UserVerification\Traits\VerifiesUsers;
use Jrean\UserVerification\Facades\UserVerification;

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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => ['jwt.auth']], function () {
  Route::group(['middleware' => ['jwt.refresh']], function () {
    Route::post('/password/change', 'Auth\ChangePasswordController@changePassword');
  });

  Route::group(['middleware' => ['isVerified']], function () {
    Route::post('/company/register', 'Auth\CompanyRegController@register');
    Route::post('/profile/update', 'ProfileController@update');
  });

  Route::post('/account/verify', function(){
    UserVerification::generate(Auth::User());
    UserVerification::sendQueue(Auth::User(), 'SafePass Email Verification', 'no-reply@safepass.africa', 'SafePass Bot');
    return response('Successful', 200);
  });
});
