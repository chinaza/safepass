<?php
use Illuminate\Support\Facades\Auth;
use Jrean\UserVerification\Traits\VerifiesUsers;
use Jrean\UserVerification\Facades\UserVerification;
use App\Http\Controllers\MemberController;

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

//Authentication routes
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => ['jwt.auth']], function () {//Authentication check middleware

  Route::group(['middleware' => ['jwt.refresh']], function () { //Regresh JWt Authentication middleware
    Route::post('/password/change', 'Auth\ChangePasswordController@changePassword');
  });

  Route::group(['middleware' => ['isVerified']], function () { //Check if user is verified

    //Create a company
    Route::post('/company/register', 'Auth\CompanyRegController@register');

    //Update Profile
    Route::post('/profile/update', 'ProfileController@update');

    //Teams Resource controller
    Route::resource('teams', 'TeamController');

    //Retrieve teams user belongs to
    Route::get('/my/teams', 'TeamController@retrieve');

    //Members Resource Controller
    Route::resource('members', 'MemberController');

    //Passwords Resource Controller
    Route::resource('passwords', 'PasswordController');

  });

  Route::post('/account/verify', function(){
    if (Auth::User()->verified) return response('User already verified', 403);
    UserVerification::generate(Auth::User());
    UserVerification::sendQueue(Auth::User(), 'SafePass Email Verification', 'no-reply@safepass.africa', 'SafePass Bot');
    return response('Successful', 200);
  });
});
