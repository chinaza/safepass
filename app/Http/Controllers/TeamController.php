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

  public function __construct(){
    //You must be a company owner to create, list and delete teams
    $this->middleware('isCompanyOwner')->only(['index', 'store', 'destroy']);
    //Anyone part of the team can see members of the team
    $this->middleware('belongsToTeam')->only(['show', 'update']);
    //You must be a team owner to update team
    $this->middleware('isTeamOwner')->only('update');
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
      'name' => 'required|string|max:20',
      'companyId' => 'required|numeric',
      'secret' => 'required|string|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&]{8,}/'
    ]);
  }

  /**
  * List all teams
  * @param Request $request
  * @return \Illuminate\Http\Response
  */
  public function index(Request $request)
  {
    $userEmail = Auth::User()->email;

    return Team::select('teams.id', 'teams.name', 'teams.company_id')
    ->join('companies', 'teams.company_id', '=', 'companies.id')
    ->where('companies.email', $userEmail)
    ->get();
  }

  /**
  * List  all teams user belongs to
  *
  * @return \Illuminate\Http\Response
  */
  public function retrieve(Request $request)
  {
    return Auth::User()->teams()
    ->join('teams', 'team_users.team_id', '=', 'teams.id')
    ->select('teams.id', 'teams.name', 'teams.company_id')
    ->where('teams.company_id', $request->companyId)
    ->get();
  }


  /**
  * Add team
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

    $token = $this->generateAccessToken(Auth::User()->pkey()->first()->public, $request->secret);

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
      'role' => 'company_owner'
    ]);

    if (!$teamUser){
      return response('Failed to add user to team', 500);
    }

    return response('Successful', 201);
  }

  /**
  * Get's all users in a team
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function show(int $id)
  {
    return TeamUser::select('team_users.id', 'users.fullName', 'team_users.role')
    ->join('users', 'team_users.user_id', '=', 'users.id')
    ->where('team_users.team_id', $id)
    ->get();
  }


  /**
  * Update Team name
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

    if (!$team = Team::find($id))
    return response('Team not found', 404);

    if ($team->company_id != $request->companyId)
    return response('Unauthorized', 401);

    $team->name = $request->name;

    $team->save();

    return response('Successful', 200);
  }

  /**
  * Remove Team
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function destroy(Request $request, int $id)
  {
    if (!$team = Team::find($id))
    return response('Team not found', 404);

    $userTeam = Auth::User()->teams()
    ->select('id')
    ->where('company_id', Auth::User()->company()->first()->id)
    ->where('team_id', $id)
    ->first();

    if (!$userTeam)
    return response('Unauthorized', 401);

    $team->delete();

    return response('Successful', 204);
  }

}
