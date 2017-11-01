<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Team;
use App\TeamUser;
use App\Password;
use App\Traits\EncLib;

class PasswordController extends Controller
{
  use EncLib;

  /**
  * Get a validator for an incoming registration request.
  *
  * @param  array  $data
  * @return \Illuminate\Contracts\Validation\Validator
  */
  protected function validator(array $data)
  {
    return Validator::make($data, [
      'title' => 'required|string|max:20',
      'imgURL' => 'string',
      'username' => 'required|string',
      'password' => 'required|string',
      'companyId' => 'required|numeric',
      'teamId' => 'required|numeric',
      'url' => 'required|string',
      'master' => 'required|string'
    ]);
  }

  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function index(Request $request)
  {
    $team = Team::find($request->teamId);

    if (!$team) return response('Team not found', 404);

    if (Gate::denies('get-password', $team))
    return response('You are not authorized to view passwords belonging to this team', 401);

    return Password::select()->where('team_id', $request->teamId)->get();
  }

  /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
  public function store(Request $request)
  {
    $team = Team::find($request->teamId);

    if (!$team) return response('Team not found', 404);

    if (Gate::denies('edit-password', $team))
    return response('You are not authorized to create passwords for this team', 401);

    //Validate Password
    $this->validator($request->all())->validate();

    //Generate AES Key from master
    $salt = Auth::User()->pkey()->salt;

    //Encrypt password

    return Password::create([
      'title' => $request->title,
      'imgurl' => $request->imgurl,
      'username' => $request->username,
      'password' => $request->password,
      'company_id' => $request->company_id,
      'team_id' => $request->teamId,
      'url' => $request->url
    ]);
  }

  /**
  * Display the specified resource.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function show($id)
  {
    //
  }

  /**
  * Update the specified resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function update(Request $request, $id)
  {
    //
  }

  /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function destroy($id)
  {
    //
  }
}
