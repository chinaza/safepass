<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Company;

class LoginController extends Controller
{
  /*
  |--------------------------------------------------------------------------
  | Login Controller
  |--------------------------------------------------------------------------
  |
  | This controller handles authenticating users for the application and
  | redirecting them to your home screen. The controller uses a trait
  | to conveniently provide its functionality to your applications.
  |
  */

  use AuthenticatesUsers;

  /**
  * Where to redirect users after login.
  *
  * @var string
  */
  protected $redirectTo = '/home';

  /**
  * Create a new controller instance.
  *
  * @return void
  */
  public function __construct()
  {
    $this->middleware('guest')->except('logout');
  }

  public function login(Request $request)
  {
    $this->validateLogin($request);

    // If the class is using the ThrottlesLogins trait, we can automatically throttle
    // the login attempts for this application. We'll key this by the username and
    // the IP address of the client making these requests into this application.
    if ($this->hasTooManyLoginAttempts($request)) {
      $this->fireLockoutEvent($request);

      return $this->sendLockoutResponse($request);
    }

    $loginData = $this->loginWithJWT($this->credentials($request));

    if (isset($loginData['error'])) {
      // If the login attempt was unsuccessful we will increment the number of attempts
      // to login and redirect the user back to the login form. Of course, when this
      // user surpasses their maximum number of attempts they will get locked out.
      $this->incrementLoginAttempts($request);
      return response($loginData, $loginData['error'] == 'invalid_credentials'?401:500);
    }

    return $this->sendLoginResponse($request, $loginData);

  }

  /**
  * Send the response after the user was authenticated.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
  protected function sendLoginResponse($request, $loginData)
  {
    $request->session()->regenerate();

    $this->clearLoginAttempts($request);

    return response($loginData, 200);
  }

  protected function loginWithJWT($credentials){
    try {
      // attempt to verify the credentials and create a token for the user
      if (! $token = JWTAuth::attempt($credentials)) {
        return ['error' => 'invalid_credentials'];
      }
    } catch (JWTException $e) {
      // something went wrong whilst attempting to encode the token
      return ['error' => 'could_not_create_token'];
    }

    if (!$companies = $this->getCompanies()) $companies = null;

    if ($companies) $companies = $this->getRoleInCompany($companies);

    $loginData = [
      'token' => $token,
      'companies' => $companies
    ];

    return $loginData;
  }

  private function getCompanies(){
    $companies = [];

    $memCompanies = Auth::User()->teams()
    ->join('companies', 'companies.id', '=', 'team_users.company_id')
    ->select('companies.id', 'companies.name', 'companies.email')
    ->get()
    ->unique();

    $regCompany = Auth::User()->company()->select('id', 'name', 'email')->first();

    if (count($memCompanies) > 0) $companies = array_merge($companies, $memCompanies->all());

    $companies = collect($companies);

    if ($regCompany)
    {
      if (count($memCompanies) > 0)
      {
        if (!$memCompanies->contains($regCompany->id))
        {
          $companies->push($regCompany);
        }
      }
      else
      {
        $companies->push($regCompany);
      }
    }
    return $companies;
  }

  private function getRoleInCompany($companies){
    $companies = $companies->each(function ($item, $key) {
      if (Auth::User()->email == $item['email']) {
        return $item['role'] = 'company_owner';
      }
      return $item['role'] = 'member';
    });

    return $companies->all();
  }
}
