<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserTest extends TestCase
{
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
      'password' => 'TestA942',
      'password_confirmation' => 'TestA942'
    ]);
    Log::info($response->getContent());
    $response->assertStatus(200);
  }

  public function testCompanyReg()
  {
    $response = $this->json('POST', '/register', [
      'company' =>'Andela',
      'email' => 'technical@andela.con',
      'password' => 'TestA942',
      'password_confirmation' => 'TestA942'
    ]);
    Log::info($response->getContent());
    $response->assertStatus(200);
  }

  public function testLogin()
  {
    $response = $this->json('POST', '/login', [
      'email' => 'contactme@theonlyzhap.xyz',
      'password' => 'TestA942',
    ]);
    Log::info($response->getContent());
    $response->assertStatus(200);
  }

  public function testForgotPassword()
  {
    $response = $this->json('POST', '/password/email', [
      'email' => 'technical@andela.con'
    ]);
    Log::info($response->getContent());
    $response->assertStatus(200);
  }

  public function testResetPW(){
    $response = $this->json('POST', '/password/reset', [
      'token' => '5571e56eae879ec678f34e068ee5df289f3bfda996a9f221e6e28d48ec013d81',
      'email' => 'technical@andela.con',
      'password' => 'testing',
      'password_confirmation' => 'testing',
    ]);
    Log::info($response->getContent());
    $response->assertStatus(200);
  }

  public function testChangePW()
  {
    $response = $this->withHeaders([
      'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjI2LCJpc3MiOiJodHRwOi8vbG9jYWxob3N0L2xvZ2luIiwiaWF0IjoxNTA4MjUwNjcwLCJleHAiOjE1MDgyNTQyNzAsIm5iZiI6MTUwODI1MDY3MCwianRpIjoiaVB0cWV4NnZsVzhBOWpXNiJ9.0fRFufnOHEWZbhITU_ggGR3_dHk1Arjh1eynM3vQ-gI',
    ])
    ->json('POST', '/password/change', [
      'curPassword' => 'andela32!',
      'newPassword' => 'Andela32!',
      'newPassword_confirmation' => 'Andela32!',
    ]);
    Log::info($response->getContent());
    Log::info($response->headers);
    $response->assertStatus(200);
  }

  public function testProfileUpdate()
  {
    $response = $this->withHeaders([
      'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjI2LCJpc3MiOiJodHRwOi8vbG9jYWxob3N0L2xvZ2luIiwiaWF0IjoxNTA4MDIzMDE5LCJleHAiOjE1MDgwMjY2MTksIm5iZiI6MTUwODAyMzAxOSwianRpIjoiVkdVZG9CajhQT1U1Y0xtbCJ9.XB2H0JODp8qf5LDvO9_P75CuwQZq9n-HI6PFSR0GDiQ',
    ])
    ->json('POST', '/profile/update', [
      'fullName' => 'Egbo Chinaza',
      'position' => 'Developer',
      'company' => 'Andela',
      'skills' => 'Programming',
    ]);
    Log::info($response->getContent());
    Log::info($response->headers);
    $response->assertStatus(200);
  }

  public function testVerify(){
    $response = $this->withHeaders([
      'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjI1LCJpc3MiOiJodHRwOi8vbG9jYWxob3N0L2xvZ2luIiwiaWF0IjoxNTA4MjU0MzU3LCJleHAiOjE1MDgyNTc5NTcsIm5iZiI6MTUwODI1NDM1NywianRpIjoibDUxVDAzZGhRT1VuQXFJNiJ9.JVTUoADGuhZ5wcKaxbGFCAR2LkWswmVCM1zLOwFILDs',
    ])
    ->json('POST', '/account/verify');
    Log::info($response->getContent());
    Log::info($response->headers);
    $response->assertStatus(200);
  }

  public function testCompanyCreate(){
    $response = $this->withHeaders([
      'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjI1LCJpc3MiOiJodHRwOi8vbG9jYWxob3N0L2xvZ2luIiwiaWF0IjoxNTA4MjU0MzU3LCJleHAiOjE1MDgyNTc5NTcsIm5iZiI6MTUwODI1NDM1NywianRpIjoibDUxVDAzZGhRT1VuQXFJNiJ9.JVTUoADGuhZ5wcKaxbGFCAR2LkWswmVCM1zLOwFILDs',
    ])
    ->json('POST', '/company/register', [
      'name' => 'Slack'
    ]);
    Log::info($response->getContent());
    Log::info($response->headers);
    $response->assertStatus(200);
  }
}
