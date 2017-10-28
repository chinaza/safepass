<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;

class MemberTest extends TestCase
{

    private $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEsImlzcyI6Imh0dHA6Ly9sb2NhbGhvc3QvbG9naW4iLCJpYXQiOjE1MDkxMzkwMTQsImV4cCI6MTUwOTIyNTQxNCwibmJmIjoxNTA5MTM5MDE0LCJqdGkiOiJYMkRpYUYxVEJ1cUhRZHhJIn0.NKvh9EtFSEsExeS31z2rQTLKPdTN9eQVNNSoQbbKfxY';

    public function testAdminMid()
    {
      $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $this->token,
      ])
      ->json('GET', '/members', [
        'teamId' => 7
      ]);
      Log::info($response->headers);
      Log::info($response->getContent());
      $response->assertStatus(200);
    }
}
