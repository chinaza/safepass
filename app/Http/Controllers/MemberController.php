<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;

use App\Company;
use App\TeamUser;
use App\Team;

use App\Traits\UserMgt;
use App\Events\MemberAdded;
use App\Events\RoleChanged;
use App\Events\MemberRemoved;

class MemberController extends Controller
{
  use UserMgt;

  public function __construct(){
    $this->middleware('isCompanyOwner')->only('index');
    $this->middleware('belongsToTeam')->except('index');
  }

  /**
  * List all users in a company
  * Company owner role only
  *
  * @return \Illuminate\Http\Response
  */
  public function index(Request $request)
  {
    return TeamUser::select('users.id', 'fullName', 'role', 'teams.name')
    ->join('users', 'users.id', '=', 'team_users.user_id')
    ->join('teams', 'teams.id', '=', 'team_users.team_id')
    ->where('team_users.company_id', $request->companyId)
    ->get();
  }


  /**
  * Add user to team
  *
  * For team_owner role; company owner
  * For admin role; Team owner, company owner
  * For member and contributor role; Team admin, Team owner, company owner
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
  public function store(Request $request)
  {
    $team = Team::find($request->teamId);

    if (!$team) return response('Team not found', 404);

    if (Gate::denies('manage-users', [$request->teamId, $request->role]))
    return response('You are not authorized to perform this action', 401);

    $res = $this->addUser($request->all());

    event(new MemberAdded($res['teamUser']));

    return response($res['msg'], $res['code']);
  }

  /**
  * Get user's details
  * You must belong to team to access this
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function show($id)
  {
    return TeamUser::select('users.fullName', 'users.email', 'users.company', 'users.position', 'users.skills')
    ->join('users', 'users.id', '=', 'team_users.user_id')
    ->find($id);
  }

  /**
  * Update User role
  * For team_owner role; company owner
  * For admin role; Team owner, company owner
  * For member and contributor role; Team admin, Team owner, company owner
  *
  * @param  \Illuminate\Http\Request  $request
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function update(Request $request, $id)
  {
    if (!$teamUser = TeamUser::find($id))
    return response('User not found', 404);

    if (Gate::denies('manage-users', [$teamUser->team_id, $teamUser->role]))
    return response('You are not authorized to perform this action', 401);

    if ($request->role == 'team_owner') {
      TeamUser::where('role', 'team_owner')
      ->update(['role' => 'member']);
    }

    $teamUser->role = $request->role;
    $teamUser->save();

    event(new RoleChanged($teamUser));

    return response('Successful', 200);
  }

  /**
  * Remove the specified user from team.
  * For team_owner role; company owner
  * For admin role; Team owner, company owner
  * For member and contributor role; Team admin, Team owner, company owner
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function destroy($id)
  {
    if (!$teamUser = TeamUser::find($id))
    return response('User not found', 404);

    if (Gate::denies('manage-users', [$teamUser->team_id, $teamUser->role]))
    return response('You are not authorized to perform this action', 401);

    if ($teamUser->user_id == Auth::User()->id)
    return response('You cannot remove yourself', 403);

    $teamData = TeamUser::select('team_users.name', 'company.name')
    ->where('team_users.id', $id)
    ->join('teams', 'teams.id', '=', 'team_users.team_id')
    ->join('companies', 'companies.id', '=', 'team_users.company_id')
    ->first();

    $teamUser->delete();

    event(new MemberRemoved($teamData));

    return response('Successful', 204);
  }
}
