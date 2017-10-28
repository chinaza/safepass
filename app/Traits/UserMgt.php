<?php

namespace App\Traits;

use App\User;
use App\Team;
use App\TeamUser;
use App\Invitation;

use App\Traits\EncLib;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

trait UserMgt
{
  use EncLib;
  /**
  * Adds user to team
  * Returns Boolean
  * @param  string $email
  * @param  string $role
  * @param  int $companyId
  * @param  int $teamId
  * @param  string $secret
  * @return boolean
  */
  public function addUser(string $email, string $role, int $companyId, int $teamId, string $secret)
  {
    //Retrieve user ID and public key
    $user = User::select('users.id', 'pkeys.public')
    ->join('pkeys', 'users.id', '=', 'pkeys.user_id')
    ->where('users.email', $email)->first();

    if (!$user) {
      if ($invitation = $this->sendInvite()) {
        return response('user not registered, Invation sent', 200);
      }
    }

    //Check if user is already in team
    $res = TeamUser::select('id')
    ->where('user_id', $user->id)
    ->where('team_id', $teamId)
    ->get();

    if (count($res) != 0) return response('User already exists', 403);

    //Get salt
    $salt = Team::select('salt')->find($teamId)->salt;

    //Generate access token
    $token = $this->generateAccessToken($user->public, $secret, $salt);

    $teamUser = TeamUser::create([
      'user_id' => $user->id,
      'company_id' => $companyId,
      'team_id' => $teamId,
      'token' => $token['token'],
      'role' => $role
    ]);

    if (!$teamUser){
      return response('Failed to add user to team', 500);
    }

    return response('Successful', 200);
  }

  public function sendInvite($email, $teamId = null){
    return Invitation::create([
      'email' => $email,
      'team_id' => !$team_id?"":$teamId
    ]);
  }

  /**
  * Check if user exists
  * Returns array of user's details
  * @param  string $email
  * @return array|null
  */
  public function userExists($email)
  {
    return User::where('email', $email)->first();
  }
}
