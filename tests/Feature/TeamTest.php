<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;

class TeamTest extends TestCase
{

  use ConfigTrait;
  // use RefreshDatabase;

  public function testCreateTeam()
  {
    $response = $this->withHeaders([
      'Authorization' => 'Bearer ' . $this->token,
    ])
    ->json('POST', '/teams', [
      'companyId' => 2,
      'name' => 'Development',
      'secret' => 'Testing123!'
    ]);
    Log::info($response->headers);
    Log::info($response->getContent());
    $response->assertStatus(201);
  }

  public function testUpdateTeam()
  {
    $response = $this->withHeaders([
      'Authorization' => 'Bearer ' . $this->token,
    ])
    ->json('PUT', '/teams/1', [
      'name' => 'Development',
      'companyId' => 2
    ]);
    Log::info($response->headers);
    Log::info($response->getContent());
    $response->assertStatus(200);
  }



  public function testListCompTeams()
  {
    $response = $this->withHeaders([
      'Authorization' => 'Bearer ' . $this->token,
    ])
    ->json('GET', '/teams');
    Log::info($response->headers);
    Log::info($response->getContent());
    $response->assertStatus(200);
  }

  public function testListTeams()
  {
    $response = $this->withHeaders([
      'Authorization' => 'Bearer ' . $this->token,
    ])
    ->json('GET', '/my/teams', [
      'companyId' => 2
    ]);
    Log::info($response->headers);
    Log::info($response->getContent());
    $response->assertStatus(200);
  }

  public function testListMembers()
  {
    $response = $this->withHeaders([
      'Authorization' => 'Bearer ' . $this->token,
    ])
    ->json('GET', '/teams/1');
    Log::info($response->headers);
    Log::info($response->getContent());
    $response->assertStatus(200);
  }

  public function testDeleteTeam()
  {
    $response = $this->withHeaders([
      'Authorization' => 'Bearer ' . $this->token,
    ])
    ->json('DELETE', '/teams/1', [
      'companyId' => 1
    ]);
    Log::info($response->headers);
    Log::info($response->getContent());
    $response->assertStatus(204);
  }
}
