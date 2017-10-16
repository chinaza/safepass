<?php

namespace App\Traits;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;

trait JwtUser
{
  /**
  * Gets the currently authenticated user model
  *If not user is authenticated, returns null
  * @param  \Illuminate\Http\Request  $request
  * @param  string|null  $token
  * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
  */
  public function validateUser(Request $request)
  {
    try {

      if (! $user = JWTAuth::parseToken()->authenticate()) {
        return 'user_not_found';
      }

    } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

      return ['token' => 'token_expired'];

    } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

      return ['token' => 'token_invalid'];

    } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

      return 'token_absent';

    }

    // the token is valid and we have found the user via the sub claim
    return compact('user');
  }
}
