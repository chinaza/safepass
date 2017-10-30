<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use App\Company;
use App\TeamUser;

use App\Traits\UserMgt;

class MemberController extends Controller
{
  use UserMgt;

  public function __construct(){
    $this->middleware('isCompanyOwner')->only('index');
    $this->middleware('isAdmin')->except(['index', 'show']);
    $this->middleware('belongsToTeam')->only('show');
  }

  /**
  * List all users in a company
  *
  * @return \Illuminate\Http\Response
  */
  public function index(Request $request)
  {
    return TeamUser::select('team_users.id', 'fullName', 'role', 'teams.name')
    ->join('users', 'users.id', '=', 'team_users.user_id')
    ->join('teams', 'teams.id', '=', 'team_users.team_id')
    ->where('team_users.company_id', $request->companyId)
    ->get();
  }


  /**
  * Add user to team
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
  public function store(Request $request)
  {
    $res = $this->addUser($request->all());
    return response($res['msg'], $res['code']);
  }

  /**
  * Get user's details
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
  *
  * @param  \Illuminate\Http\Request  $request
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function update(Request $request, $id)
  {
    if (!$teamUser = TeamUser::find($id))
    return response('User not found', 404);

    $teamUser->role = $request->role;
    $teamUser->save();
    return response('Successful', 200);
  }

  /**
  * Remove the specified user from team.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function destroy($id)
  {
    $companyExists = Company::where('email', Auth::User()->email)->first();
    Log::info($companyExists);

    if (!$teamUser = TeamUser::find($id))
    return response('User not found', 404);

    if ($companyExists && $teamUser->user_id == Auth::User()->id)
    return response('You cannot remove yourself', 403);

    $teamUser->delete();

    return response('Successful', 204);
  }
}
