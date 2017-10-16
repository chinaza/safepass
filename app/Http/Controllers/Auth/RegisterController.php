<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Company;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class RegisterController extends Controller
{
  /**
  * Create a new controller instance.
  *
  * @return void
  */
  public function __construct()
  {
    $this->middleware('guest');
  }

  /**
  * Handle user/company registration
  *
  * @param  Request  $request
  * @return response
  */
  public function register(Request $request)
  {
    $this->validator($request->all())->validate();

    $credentials = $request->only('email', 'password');

    event(new Registered($user = $this->create($request->all())));

    try {
      // attempt to verify the credentials and create a token for the user
      if (! $token = JWTAuth::attempt($credentials)) {
        return response()->json(['error' => 'invalid_credentials'], 401);
      }
    } catch (JWTException $e) {
      // something went wrong whilst attempting to encode the token
      return response()->json(['error' => 'could_not_create_token'], 500);
    }

    // all good so return the token
    return response()->json(compact('token'));
  }

  /**
  * Get a validator for an incoming registration request.
  *
  * @param  array  $data
  * @return \Illuminate\Contracts\Validation\Validator
  */
  protected function validator(array $data)
  {
    return Validator::make($data, [
      'fullName' => 'required_without:company|nullable|string|max:255',
      'email' => 'required|string|email|max:255|unique:users',
      'company' => 'required_without:fullName|nullable|string|max:255',
      'password' => 'required|string|confirmed|min:8|regex:/^(?=.*[A-Za-z])(?=.*\d)(?=.*[$@$!%*#?&])[A-Za-z\d$@$!%*#?&]{8,}$/',
    ]);
  }

  /**
  * Create a new user instance after a valid registration.
  *
  * @param  array  $data
  * @return \App\User
  */
  protected function create(array $data)
  {
    $user = User::create([
      'fullName' => isset($data['fullName'])? $data['fullName'] :  '',
      'email' => $data['email'],
      'password' => bcrypt($data['password']),
    ]);

    if (!$user){
      return;
    }

    if (isset($data['company'])){
      Company::create([
        'name' => $data['company'],
        'email' => $data['email'],
        'user_id' => $user->id,
      ]);
    }

    return $user;
  }
}
