<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\ConfigTrait;
use Illuminate\Support\Facades\Log;

class PasswordTest extends TestCase
{
  use ConfigTrait;

  public function testAddPw()
  {
    $response = $this->withHeaders([
      'Authorization' => 'Bearer ' . $this->token,
    ])
    ->json('POST', '/passwords', [
      'title' => 'Facebook',
      'imgURL' => '/just/testing',
      'username' => 'technical@andela.con',
      'password' => 'testinghaha',
      'companyId' => 1,
      'teamId' => 7,
      'url' => 'facebook.com',
      'master' => 'TestA942!'
    ]);
    Log::info($response->headers);
    Log::info($response->getContent());
    $response->assertStatus(201);
  }

  public function testUpdatePw()
  {
    $response = $this->withHeaders([
      'Authorization' => 'Bearer ' . $this->token,
    ])
    ->json('PUT', '/passwords/1', [
      'title' => 'Facebook',
      'imgURL' => '/just/testing',
      'username' => 'technical@andela.con',
      'password' => 'tuatuatua',
      'companyId' => 1,
      'teamId' => 7,
      'url' => 'facebook.com',
      'master' => 'TestA942!'
    ]);
    Log::info($response->headers);
    Log::info($response->getContent());
    $response->assertStatus(200);
  }

  public function testGetPw()
  {
    $response = $this->withHeaders([
      'Authorization' => 'Bearer ' . $this->token,
    ])
    ->json('GET', '/passwords', [
      'teamId' => 7,
    ]);
    Log::info($response->headers);
    Log::info($response->getContent());
    $response->assertStatus(200);
  }

  public function testShowPw()
  {
    $response = $this->withHeaders([
      'Authorization' => 'Bearer ' . $this->token,
    ])
    ->json('GET', '/passwords/1', [
      'master' => 'TestA942!',
    ]);
    Log::info($response->headers);
    Log::info($response->getContent());
    $response->assertStatus(200);
  }

  public function testDelPw()
  {
    $response = $this->withHeaders([
      'Authorization' => 'Bearer ' . $this->token,
    ])
    ->json('DELETE', '/passwords/1', [
      'master' => 'TestA942!',
    ]);
    Log::info($response->headers);
    Log::info($response->getContent());
    $response->assertStatus(204);
  }
}
