<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_on_protected_routes()
    {
        $response = $this->getJson('/api/favorites');
        $response->assertStatus(401);
    }
}
