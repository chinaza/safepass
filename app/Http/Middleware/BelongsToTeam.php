<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BelongsToTeam
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

    if (!Auth::User()->teams()->select('id')->where('team_id', $teamId)->first())
    return response('You do not belong to this team', 403);

    return $next($request);
  }
}
