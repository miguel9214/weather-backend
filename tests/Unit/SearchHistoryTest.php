<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Search;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_store_a_search()
    {
        $user = User::factory()->create();

        $payload = [
            'city' => 'Lima',
            'country' => 'Peru',
            'temperature' => 22,
            'condition' => 'Soleado',
        ];

        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('/api/searches', $payload);

        $response->assertCreated();
        $this->assertDatabaseHas('searches', [
            'user_id' => $user->id,
            'city' => 'Lima',
        ]);
    }

    public function test_user_can_retrieve_search_history()
    {
        $user = User::factory()->create();
        Search::factory()->count(3)->create(['user_id' => $user->id]);

        $this->actingAs($user, 'sanctum');

        $response = $this->getJson('/api/searches');

        $response->assertOk();
        $this->assertCount(3, $response->json());
    }
}
