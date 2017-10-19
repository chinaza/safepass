<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Company;
use App\Pkey;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

use Jrean\UserVerification\Traits\VerifiesUsers;
use Jrean\UserVerification\Facades\UserVerification;

use App\Traits\EncLib;

class RegisterController extends Controller
{

  use VerifiesUsers, EncLib;
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

    UserVerification::generate($user);
    UserVerification::sendQueue($user, 'SafePass Email Verification', 'no-reply@safepass.africa', 'SafePass Bot');

    if (!$this->generate($request->master)) {
      return response('server failure, please contact customer service hello@safepass.ng or 08133098502', 500);
    }

    // all good so return the token
    return response('Successful');
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
      'fullName' => 'required_without:company|nullable|string|max:50',
      'email' => 'required|string|email|max:255|unique:users',
      'company' => 'required_without:fullName|nullable|string|max:60',
      'password' => 'required|string|confirmed|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&]{8,}/',
      'master' => 'required|string|confirmed|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&]{8,}/',
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

  public function generate(string $master)
  {
    //Get RSA key pair
    $keyPair = $this->generateKeyPair();

    //Generate AES secret key from master password
    $key = $this->generateKey($master);

    //Encrypt private key with master password
    $encPrivKey = $this->aesEncrypt($keyPair['private'], $key['key']);

    return Pkey::create([
      'user_id' => Auth::User()->id,
      'private' => $encPrivKey['ciphertext'],
      'iv' => $encPrivKey['iv'],
      'salt' => $key['salt'],
      'public' => $keyPair['public']
    ]);
  }
}
