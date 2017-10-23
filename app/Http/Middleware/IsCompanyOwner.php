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
    if (!$request->company_id)
    return response('No privilege for this operation ', 401);

    if (!$company = Auth::User()->company())
    return response('No privilege for this operation ', 401);

    if (!$company->id == $request->company_id)
    return response('No privilege for this operation ', 401);

    return $next($request);
  }
}
