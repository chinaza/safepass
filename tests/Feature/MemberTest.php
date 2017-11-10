<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;

class MemberTest extends TestCase
{
  use ConfigTrait;

    public function testMemberAdd()
    {
      $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $this->token,
      ])
      ->json('POST', '/members', [
        'email' => 'technical@andela.con',
        'role' => 'member',
        'companyId' => 2,
        'teamId' => 3,
        'secret' => 'Testing123!'
      ]);
      Log::info($response->headers);
      Log::info($response->getContent());
      $response->assertStatus(201);
    }

    public function testMembersList()
    {
      $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $this->token,
      ])
      ->json('GET', '/members', [
        'companyId' => 2
      ]);
      Log::info($response->headers);
      Log::info($response->getContent());
      $response->assertStatus(200);
    }

    public function testRoleUpdate()
    {
      $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $this->token,
      ])
      ->json('PUT', '/members/6', [
        'teamId' => 2,
        'role' => 'admin',
      ]);
      Log::info($response->headers);
      Log::info($response->getContent());
      $response->assertStatus(200);
    }

    public function testGetUser()
    {
      $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $this->token,
      ])
      ->json('GET', '/members/4', [
        'teamId' => 2
      ]);
      Log::info($response->headers);
      Log::info($response->getContent());
      $response->assertStatus(200);
    }

    public function testUserDel()
    {
      $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $this->token,
      ])
      ->json('DELETE', '/members/15', [
        'teamId' => 2
      ]);
      Log::info($response->headers);
      Log::info($response->getContent());
      $response->assertStatus(204);
    }
}
