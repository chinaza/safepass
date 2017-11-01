<?php

namespace App\Traits;

use App\User;
use App\Team;
use App\Company;
use App\TeamUser;

use App\Traits\EncLib;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

trait PasswordMgt
{
  use EncLib;
  /**
  * Encrypts Password for storage
  * Returns Boolean
  * @param  array $data
  * @return array
  */
  public function encryptPassword(array $data)
  {
    //Generate AES Key from master
    $pkey = Auth::User()->pkey()->first();
    $aeskey = $this->generateAESKey($data['master'], $pkey['salt']);

    //Decrypt Private Pkey
    $privateKey = $this->aesDecrypt(Auth::User()->pkey()->first()->private, $aeskey['key'], $pkey['iv']);

    if (!$privateKey) abort(401, 'Invalid master password');

    //Get encrypted access token
    $team = Auth::User()->teams()
    ->where('team_id', $data['teamId'])
    ->first();

    //Decrypt encrypted token to get access token
    $accessToken = $this->privateDecrypt($team->token, $privateKey);

    $pw = $this->aesEncrypt($data['password'], $accessToken);

    //return Encrypted password
    return $pw;
  }

  /**
  * Decrypts password
  * Returns Boolean
  * @param  array $data
  * @param  string $master
  * @param  string $teamId
  * @param  string $iv
  * @return array
  */
  public function decryptPassword(string $data, string $master, string $teamId)
  {
    //Generate AES Key from master
    $salt = Auth::User()->pkey()->first()->salt;
    $aeskey = $this->generateAESKey($master, $salt);

    //Decrypt Private Pkey
    $privateKey = $this->aesDecrypt(Auth::User()->pkey()->first()->private, $aeskey['key']);

    if (!privateKey) abort(401, 'Invalid master password');

    //Get encrypted access token
    $team = Auth::User()->teams()
    ->where('team_id', $data['teamId'])
    ->first();

    //Decrypt encrypted token to get access token
    $accessToken = $this->privateDecrypt($team->token, $privateKey);

    //return Decrypted password
    return $this->aesDecrypt($data['password'], $accessToken, $data['iv']);
  }
}
