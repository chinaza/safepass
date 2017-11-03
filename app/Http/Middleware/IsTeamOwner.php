<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\TeamUser;

class IsTeamOwner
{
  /**
  * Handle an incoming request.
  *
  * @param  \Illuminate\Http\Request  $request
  * @param  \Closure  $next
  * @return mixed
  */
  public function handle($request, Closure $next)
  {
    if (!$teamId = $request->route('team')) $teamId = $request->teamId;

    $user = TeamUser::select('role')
    ->where('user_id', Auth::User()->id)
    ->where('team_id', $teamId)->first();


    if (!$user) return response('User not registered with this team', 401);

    if ($user->role != 'team_owner' && $user->role != 'company_owner') return response("You don't have rights", 403);

    return $next($request);
  }
}
