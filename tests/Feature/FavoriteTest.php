<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Search;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_toggle_favorite()
    {
        $user = User::factory()->create();
        $search = Search::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->postJson("/api/favorites/{$search->id}/toggle");
        $response->assertStatus(200);

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'search_id' => $search->id
        ]);

        // Toggle de nuevo para quitar de favoritos
        $response = $this->actingAs($user)->postJson("/api/favorites/{$search->id}/toggle");
        $response->assertStatus(200);

        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id,
            'search_id' => $search->id
        ]);
    }
}
