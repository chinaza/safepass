<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\User;

class ProfileController extends Controller
{
  /**
   * Update the users Profile
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
   */
   protected function validator(array $data)
   {
     return Validator::make($data, [
       'fullName' => 'required|string|max:50',
       'position' => 'nullable|string|max:50',
       'company' => 'nullable|string|max:50',
       'skills' => 'nullable|string|max:100',
     ]);
   }

    public function update(Request $request)
    {
      //Validate Inputs
      $this->validator($request->all())->validate();

      //Update Profile
      $user = User::find(Auth::User()->id);
      $user->fullName = $request->fullName;
      $user->position = $request->position;
      $user->company = $request->company;
      $user->skills = $request->skills;
      $user->save();

      return response()->json(['profile' => 'Profile successfully updated'], 200);
    }    
}
