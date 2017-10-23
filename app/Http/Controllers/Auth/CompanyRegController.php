<?php

namespace App\Http\Controllers\Auth;

use App\Company;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class CompanyRegController extends Controller
{

  /**
  * Create a new controller instance.
  *
  * @return void
  */
  public function __construct()
  {
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

    if (!$this->create($request->all())){
      return response('You can only register one company per account', 403);
    }

    // all good so return the token
    return response('Successful', 200);
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
      'name' => 'required|string|max:50'
    ]);
  }

  /**
  * Create a new user instance after a valid registration.
  *
  * @param  Request  $request
  * @return \App\User
  */
  protected function create(array $data)
  {
    if (count(Company::where('email', Auth::User()->email)->get()) > 0) return false;
    return Company::create([
      'name' => $data['name'],
      'email' => Auth::User()->email,
      'user_id' => Auth::User()->id,
    ]);
  }
}
