<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Search;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SearchHistoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_retrieve_search_history()
    {
        // Crear un usuario autenticado con Sanctum
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        // Crear 3 búsquedas asociadas al usuario
        Search::factory()->count(3)->create([
            'user_id' => $user->id,
        ]);

        // Hacer solicitud GET a /api/searches
        $response = $this->getJson('/api/searches');

        // Verificar que la respuesta sea exitosa
        $response->assertOk();

        // Verificar estructura del JSON
        $response->assertJsonStructure([
            'message',
            'data' => [
                '*' => ['id', 'user_id', 'city', 'data', 'favorite', 'created_at', 'updated_at']
            ]
        ]);

        // Verificar que hay 3 resultados
        $this->assertCount(3, $response->json('data'));
    }
}
