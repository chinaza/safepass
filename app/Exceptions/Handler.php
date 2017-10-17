<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Jrean\UserVerification\Exceptions\UserNotVerifiedException;
use Jrean\UserVerification\Exceptions\TokenMismatchException;
use Jrean\UserVerification\Exceptions\UserIsVerifiedException;

class Handler extends ExceptionHandler
{
  /**
  * A list of the exception types that are not reported.
  *
  * @var array
  */
  protected $dontReport = [
    //
  ];

  /**
  * A list of the inputs that are never flashed for validation exceptions.
  *
  * @var array
  */
  protected $dontFlash = [
    'password',
    'password_confirmation',
  ];

  /**
  * Report or log an exception.
  *
  * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
  *
  * @param  \Exception  $exception
  * @return void
  */
  public function report(Exception $exception)
  {
    parent::report($exception);
  }

  /**
  * Render an exception into an HTTP response.
  *
  * @param  \Illuminate\Http\Request  $request
  * @param  \Exception  $exception
  * @return \Illuminate\Http\Response
  */
  public function render($request, Exception $exception)
  {
    if ($exception instanceof Tymon\JWTAuth\Exceptions\TokenExpiredException) {
      return response()->json(['token_expired'], $exception->getStatusCode());
    }
    else if ($exception instanceof Tymon\JWTAuth\Exceptions\TokenInvalidException) {
      return response()->json(['token_invalid'], $exception->getStatusCode());
    }
    else if ($exception instanceof Tymon\JWTAuth\Exceptions\JWTException) {
      return response()->json(['token_absent'], $exception->getStatusCode());
    }

    if ($exception instanceof UserNotVerifiedException){
      return response('User not verified', 403);
    }
    else if ($exception instanceof TokenMismatchException){
      return response('Invalid verification token', 403);
    }
    else if ($exception instanceof UserIsVerifiedException){
      return response('User already verified', 403);
    }

    return parent::render($request, $exception);
  }
}
