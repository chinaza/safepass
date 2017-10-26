<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Team;
use App\TeamUser;

use App\Traits\EncLib;

class TeamController extends Controller
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
      'name' => 'required|string|max:20',
      'companyId' => 'required|numeric',
      'secret' => 'required|string|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&]{8,}/'
    ]);
  }

  /**
  * Display a listing of the resource.
  * @param Request $request
  * @return \Illuminate\Http\Response
  */
  public function index(Request $request)
  {
    return Team::select('id', 'name', 'company_id')
    ->where('company_id', $request->companyId)
    ->get();
  }

  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function retrieve(Request $request)
  {
    return Auth::User()->teams()
    ->select('teams.id', 'name', 'teams.company_id')
    ->join('teams', 'team_users.company_id', '=', 'teams.company_id')
    ->where('teams.company_id', $request->companyId)
    ->get();
  }


  /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
  public function store(Request $request)
  {
    $this->validator($request->all())->validate();

    $checkTeam = Team::where('name', $request->name)
    ->where('company_id', $request->companyId)->first();

    if (count($checkTeam) != 0) return response('Team already exists, kindly change the name', 403);

    $token = $this->generateToken($request->secret);

    $team = Team::create([
      'name' => $request->name,
      'company_id' => $request->companyId,
      'salt' => $token['salt'],
    ]);

    $teamUser = TeamUser::create([
      'user_id' => Auth::User()->id,
      'company_id' => $request->companyId,
      'team_id' => $team->id,
      'token' => $token['token'],
      'role' => 'admin'
    ]);

    if (!$teamUser){
      return response('Failed to add user to team', 403);
    }

    return response('Successful', 200);
  }

  /**
  * Display the specified resource.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function show(int $id)
  {
    return TeamUser::select('users.fullName', 'team_users.role')
    ->join('users', 'team_users.user_id', '=', 'users.id')
    ->where('team_users.team_id', $id)
    ->get();
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
    $request->validate([
      'name' => 'required|string|max:20'
    ]);

    $team = $this->checkTeam($request, $id);

    $team->name = $request->name;

    $team->save();

    return response('Successful', 200);
  }

  /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function destroy(Request $request, int $id)
  {
    $team = $this->checkTeam($request, $id);

    $team->delete();

    return response('Successful', 202);
  }

  private function generateToken(string $secret)
  {
    $aes = $this->generateAESKey($secret);
    $cipher = $this->publicEncrypt($aes['key'], Auth::User()->key()->first()->public);
    return [
      'salt' => $aes['salt'],
      'token' => $cipher['ciphertext']
    ];
  }

  private function checkTeam(Request $request, $id){
    if (!$team = Team::find($id))
    return response('Team not found', 404);

    if ($team->company_id != $request->companyId)
    return response('Unauthorized', 401);

    return $team;
  }

}
