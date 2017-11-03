<?php

namespace App\Http\Middleware;

use App\Company;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use Closure;

class IsCompanyOwner
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
    if (!$company = Auth::User()->company()->first())
    return response("User has no registered company", 403);
    
    return $next($request);
  }
}
