<?php

namespace App\Traits;

use App\User;
use App\Team;
use App\Company;
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
  * @param  array $data
  * @return array
  */
  public function addUser(array $data)
  {
    //Retrieve user ID and public key
    $user = User::select('users.id', 'pkeys.public')
    ->join('pkeys', 'users.id', '=', 'pkeys.user_id')
    ->where('users.email', $data['email'])->first();

    if (!$user) {
      return [
        'msg' => 'user not registered',
        'code' => 403
      ];
    }

    //Check if user is already in team
    $res = TeamUser::select('id')
    ->where('user_id', $user->id)
    ->where('team_id', $data['teamId'])
    ->get();

    if (count($res) != 0)
    return [
      'msg' => 'User already exists',
      'code' => 403
    ];

    //Get salt
    $salt = Team::select('salt')->find($data['teamId'])->salt;

    //Generate access token
    $token = $this->generateAccessToken($user->public, $data['secret'], $salt);

    $teamUser = TeamUser::create([
      'user_id' => $user->id,
      'company_id' => $data['companyId'],
      'team_id' => $data['teamId'],
      'token' => $token['token'],
      'role' => $data['role']
    ]);

    if (!$teamUser){
      return [
        'msg' => 'Failed to add user to team',
        'code' => 500
      ];
    }

    return [
      'msg' => 'Successful',
      'code' => 200,
      'teamUser' => $teamUser
    ];
  }

  /**
  * Check if user exists
  * Returns array of user's details
  * @param  string $email
  * @return array|null
  */
  public function userExists(string $email)
  {
    return User::where('email', $email)->first();
  }

  /**
  * Checks if user owns the company
  * Returns true if user owns the company
  * @param int $companyId
  * @return Boolean
  */
  public function isCompanyOwner(int $companyId)
  {
    $userEmail = Auth::User()->email;
    $companyEmail = Company::find($companyId)->email;

    if ($userEmail != $companyEmail) return false;

    return true;
  }
}
