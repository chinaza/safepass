<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\TeamUser;

class IsAdmin
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
    ->where('id', $request->teamId)->first();

    if (count($user) == 0) return response('User not registered with this team', 401);

    if ($user->role != 'admin') return response("You don't have admin rights", 403);

    return $next($request);
  }
}
