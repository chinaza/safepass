<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{

  /**
  * Create a new controller instance.
  *
  * @return void
  */
  public function __construct()
  {
  }

  protected function validator(array $data)
  {
    return Validator::make($data, [
      'curPassword' => 'required|string',
      'newPassword' => 'required|string|confirmed|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&]{8,}/',
    ]);
  }

  /**
  * Send a reset link to the given user.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
  */
  public function changePassword(Request $request)
  {
    //Validate Inputs
    $this->validator($request->all())->validate();

    //Check the current password
    $current_password = Auth::User()->password;

    if(!Hash::check($request->curPassword, $current_password)) {
      return response()->json(
        ['curPassword' => 'Incorrect current password'],
        401
      );
    }

    //Change the password
    $obj_user = User::find(Auth::User()->id);
    $obj_user->password = Hash::make($request->newPassword);
    $obj_user->save();

    //Send response
    return response()->json(['status' => 'Password changed successfully'], 200);
  }
}
