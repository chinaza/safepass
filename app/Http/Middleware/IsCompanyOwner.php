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
    if (!$request->companyId)
    return response('Company ID not set ', 401);

    if (!$company = Auth::User()->company()->first())
    return response("Company not found", 404);

    if ($company['id'] != $request->companyId)
    return response('No privilege for this operation ', 401);

    return $next($request);
  }
}
