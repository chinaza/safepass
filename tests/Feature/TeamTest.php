<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;

class TeamTest extends TestCase
{
  private $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEsImlzcyI6Imh0dHA6Ly9sb2NhbGhvc3QvbG9naW4iLCJpYXQiOjE1MDkwNDk5NzEsImV4cCI6MTUwOTEzNjM3MSwibmJmIjoxNTA5MDQ5OTcxLCJqdGkiOiJod2NmdDNyNnJTZVdSQ3VFIn0.mOOU0O7qafIVDKmazqo4RwHl9fMarSUAIa9hW0EvBW4';

    // use RefreshDatabase;


    public function testCreateTeam()
    {
      $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $this->token,
      ])
      ->json('POST', '/teams', [
        'companyId' => 1,
        'name' => 'Design',
        'secret' => 'Testing123!'
      ]);
      Log::info($response->headers);
      Log::info($response->getContent());
      $response->assertStatus(200);
    }

    public function testUpdateTeam()
    {
      $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $this->token,
      ])
      ->json('PUT', '/teams/6', [
        'name' => 'Development',
        'companyId' => 1
      ]);
      Log::info($response->headers);
      Log::info($response->getContent());
      $response->assertStatus(200);
    }

    public function testDeleteTeam()
    {
      $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $this->token,
      ])
      ->json('DELETE', '/teams/6', [
        'companyId' => 1
      ]);
      Log::info($response->headers);
      Log::info($response->getContent());
      $response->assertStatus(202);
    }

    public function testListCompTeams()
    {
      $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $this->token,
      ])
      ->json('GET', '/teams', [
        'companyId' => 1
      ]);
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
        'companyId' => 1
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
      ->json('GET', '/teams/7', [
        'companyId' => 1
      ]);
      Log::info($response->headers);
      Log::info($response->getContent());
      $response->assertStatus(200);
    }
}
