<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Search;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_toggle_favorite()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $search = Search::factory()->create(['user_id' => $user->id, 'favorite' => false]);

        // Marcar como favorito
        $response = $this->postJson("/api/favorites/{$search->id}/toggle");
        $response->assertStatus(200);

        $this->assertDatabaseHas('searches', [
            'id'       => $search->id,
            'user_id'  => $user->id,
            'favorite' => true,
        ]);

        // Desmarcar como favorito
        $response = $this->postJson("/api/favorites/{$search->id}/toggle");
        $response->assertStatus(200);

        $this->assertDatabaseHas('searches', [
            'id'       => $search->id,
            'user_id'  => $user->id,
            'favorite' => false,
        ]);
    }
}
