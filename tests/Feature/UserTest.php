<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserTest extends TestCase
{
  use ConfigTrait;
  // use RefreshDatabase;

  /**
  * A basic test example.
  *
  * @return void
  */
  public function testUserReg()
  {
    $response = $this->json('POST', '/register', [
      'fullName' =>'Chinaza Egbo',
      'email' => 'contactme@theonlyzhap.xyz',
      'password' => 'TestA942!',
      'password_confirmation' => 'TestA942!',
      'master' => 'TestA942!',
      'master_confirmation' => 'TestA942!'
    ]);
    Log::info($response->headers);
    Log::info($response->getContent());
    $response->assertStatus(200);
  }

  public function testCompanyReg()
  {
    $response = $this->json('POST', '/register', [
      'company' =>'Andela',
      'email' => 'technical@andela.con',
      'password' => 'TestA942!',
      'password_confirmation' => 'TestA942!',
      'master' => 'TestA942!',
      'master_confirmation' => 'TestA942!'
    ]);
    Log::info($response->headers);
    Log::info($response->getContent());
    $response->assertStatus(200);
  }

  public function testLogin()
  {
    $response = $this->json('POST', '/login', [
      'email' => 'technical@andela.con',
      'password' => 'TestA942!',
    ]);
    Log::info($response->headers);
    Log::info($response->getContent());
    $response->assertStatus(200);
  }

  public function testForgotPassword()
  {
    $response = $this->json('POST', '/password/email', [
      'email' => 'technical@andela.con'
    ]);
    Log::info($response->headers);
    Log::info($response->getContent());
    $response->assertStatus(200);
  }

  public function testResetPW(){
    $response = $this->json('POST', '/password/reset', [
      'token' => '4f613b7fe6f0d81805585beb11a7221c1028ed628a0723117df3d955e7430915',
      'email' => 'technical@andela.con',
      'password' => 'TestA942!',
      'password_confirmation' => 'TestA942!',
    ]);
    Log::info($response->headers);
    Log::info($response->getContent());
    $response->assertStatus(200);
  }

  public function testChangePW()
  {
    $response = $this->withHeaders([
      'Authorization' => 'Bearer ' . $this->token,
    ])
    ->json('POST', '/password/change', [
      'curPassword' => 'TestA942!',
      'newPassword' => 'TestA942!',
      'newPassword_confirmation' => 'TestA942!',
    ]);
    Log::info($response->headers);
    Log::info($response->getContent());
    $response->assertStatus(200);
  }

  public function testProfileUpdate()
  {
    $response = $this->withHeaders([
      'Authorization' => 'Bearer ' . $this->token,
    ])
    ->json('POST', '/profile/update', [
      'fullName' => 'Egbo Chinaza',
      'position' => 'Developer',
      'company' => 'Andela',
      'skills' => 'Programming',
    ]);
    Log::info($response->headers);
    Log::info($response->getContent());
    $response->assertStatus(200);
  }

  public function testVerify(){
    $response = $this->withHeaders([
      'Authorization' => 'Bearer ' . $this->token,
    ])
    ->json('POST', '/account/verify');
    Log::info($response->headers);
    Log::info($response->getContent());
    $response->assertStatus(403);
  }

  public function testCompanyCreate(){
    $response = $this->withHeaders([
      'Authorization' => 'Bearer ' . $this->token,
    ])
    ->json('POST', '/company/register', [
      'name' => 'Slack'
    ]);
    Log::info($response->headers);
    Log::info($response->getContent());
    $response->assertStatus(200);
  }
}
