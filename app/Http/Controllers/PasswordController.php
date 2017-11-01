<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Team;
use App\TeamUser;
use App\Password;
use App\Pkey;

use App\Traits\EncLib;
use App\Traits\PasswordMgt;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class PasswordController extends Controller
{
  use PasswordMgt;

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

    $encryptedPassword = $this->encryptPassword($request->all());

    $password = Password::create([
      'title' => $request->title,
      'imgurl' => $request->imgURL,
      'username' => $request->username,
      'password' => $encryptedPassword['ciphertext'],
      'iv' => $encryptedPassword['iv'],
      'company_id' => $request->companyId,
      'team_id' => $request->teamId,
      'url' => $request->url
    ]);

    if (!$password) return response('Failed', 500);

    return response('Successfully added password', 201);
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

    return Password::select('id', 'title', 'imgurl', 'username', 'team_id', 'url')->where('team_id', $request->teamId)->get();
  }

  /**
  * Display the specified resource.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function show(Request $request, $id)
  {
    $password = Password::find($id);

    if (!$password) return response('Password not found', 404);

    $team = Team::find($password->team_id);

    if (!$team) return response('Team not found', 404);

    if (Gate::denies('get-password', $team))
    return response('You are not authorized to view this password', 401);

    //Decrypt Password
    $passwordText = $this->decryptPassword($password, $request->master, $password->teamId);

    $collection = collect($password);

    $filtered = $collection->only(['id', 'title', 'url', 'imgurl', 'username', 'company_id', 'team_id']);

    $filtered->put('password', $passwordText);

    return $filtered->all();
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
    $password = Password::find($id);

    $team = Team::find($password->team_id);

    if (!$team) return response('Team not found', 404);

    if (Gate::denies('edit-password', $team))
    return response('You are not authorized to modify passwords for this team', 401);

    //Validate Password
    $this->validator($request->all())->validate();

    $encryptedPassword = $this->encryptPassword($request->all());

    $password = [
      'title' => $request->title,
      'imgurl' => $request->imgurl,
      'username' => $request->username,
      'password' => $encryptedPassword['ciphertext'],
      'iv' => $encryptedPassword['iv'],
      'company_id' => $request->company_id,
      'team_id' => $request->teamId,
      'url' => $request->url
    ];

    if (!$password->save()) return response('Failed', 500);

    return response('Successfully added password', 200);
  }

  /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function destroy($id)
  {
    $password = Password::find($id);

    $team = Team::find($request->teamId);

    if (!$team || $password->team_id != $request->teamId) return response('Team not found', 404);

    if (Gate::denies('edit-password', $team))
    return response('You are not authorized to modify passwords for this team', 401);

    Password::destroy($id);

    return response('Password successfully deleted', 204);
  }
}
