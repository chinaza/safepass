<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;

class TeamTest extends TestCase
{
  private $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEsImlzcyI6Imh0dHA6Ly9sb2NhbGhvc3QvbG9naW4iLCJpYXQiOjE1MDg3OTQxNDIsImV4cCI6MTUwODc5Nzc0MiwibmJmIjoxNTA4Nzk0MTQyLCJqdGkiOiJ3Qkl6VTFGR3oxU2RhRTJlIn0.l-K6F6MKuv-suAk61AT0sA1JuZt_kArMRFWNLLZ3UK4';
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCompMidware()
    {
      $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $this->token,
      ])
      ->json('POST', '/teams', [
        'company_id' => 1
      ]);
      Log::info($response->headers);
      Log::info($response->getContent());
      $response->assertStatus(200);
    }
}
