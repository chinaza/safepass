<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;

class MemberTest extends TestCase
{

    private $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEsImlzcyI6Imh0dHA6Ly9sb2NhbGhvc3QvbG9naW4iLCJpYXQiOjE1MDkxMzkwMTQsImV4cCI6MTUwOTIyNTQxNCwibmJmIjoxNTA5MTM5MDE0LCJqdGkiOiJYMkRpYUYxVEJ1cUhRZHhJIn0.NKvh9EtFSEsExeS31z2rQTLKPdTN9eQVNNSoQbbKfxY';

    public function testMemberAdd()
    {
      $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $this->token,
      ])
      ->json('POST', '/members', [
        'email' => 'technical@andela.con',
        'role' => 'member',
        'companyId' => 1,
        'teamId' => 7,
        'secret' => 'Testing123!'
      ]);
      Log::info($response->headers);
      Log::info($response->getContent());
      $response->assertStatus(200);
    }

    public function testMembersList()
    {
      $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $this->token,
      ])
      ->json('GET', '/members', [
        'companyId' => 1,
        'teamId' => 7,
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
      ->json('PUT', '/members/9', [
        'teamId' => 7,
        'role' => 'admin',
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
      ->json('DELETE', '/members/9', [
        'teamId' => 7
      ]);
      Log::info($response->headers);
      Log::info($response->getContent());
      $response->assertStatus(204);
    }

    public function testGetUser()
    {
      $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $this->token,
      ])
      ->json('GET', '/members/7', [
        'teamId' => 7
      ]);
      Log::info($response->headers);
      Log::info($response->getContent());
      $response->assertStatus(200);
    }
}
