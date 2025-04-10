<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Search;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoriteServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_toggle_favorite_status()
    {
        $user = User::factory()->create();
        $search = Search::factory()->create(['user_id' => $user->id, 'favorite' => false]);

        $this->actingAs($user, 'sanctum');

        $response = $this->postJson("/api/favorites/{$search->id}/toggle");

        $response->assertOk();
        $this->assertTrue($search->fresh()->favorite);
    }

    public function test_user_can_unfavorite_a_city()
    {
        $user = User::factory()->create();
        $search = Search::factory()->create(['user_id' => $user->id, 'favorite' => true]);

        $this->actingAs($user, 'sanctum');

        $response = $this->postJson("/api/favorites/{$search->id}/toggle");

        $response->assertOk();
        $this->assertFalse($search->fresh()->favorite);
    }
}
