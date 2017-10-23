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

    if (!$company = Company::find($request->company_id))
    return response('No privilege for this operation ', 401);

    if ($company->email != Auth::User()->email)
    return response('No privilege for this operation ', 401);

    return $next($request);
  }
}
