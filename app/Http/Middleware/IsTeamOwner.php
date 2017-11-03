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
    $user = TeamUser::select('role')->where('user_id', Auth::User()->id)
    ->where('team_id', $request->teamId)->first();

    if (count($user) == 0) return response('User not registered with this team', 401);

    if ($user->role != 'team_owner' && $user->role != 'company_owner') return response("You don't have rights", 403);

    return $next($request);
  }
}
