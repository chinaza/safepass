<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;

class NotificationTest extends TestCase
{
  use ConfigTrait;
    /**
     * Test notifications
     *
     * @return void
     */
    public function testNotifications()
    {
      $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $this->token,
      ])
      ->json('GET', '/notifications');
      Log::info($response->headers);
      Log::info($response->getContent());
      $response->assertStatus(200);
    }
}
