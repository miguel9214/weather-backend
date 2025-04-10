<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * @OA\Tag(
 *     name="Favorites",
 *     description="Gestión de favoritos del usuario"
 * )
 */

class FavoriteController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/favorites",
     *     summary="Obtener búsquedas favoritas del usuario autenticado",
     *     tags={"Favorites"},
     *     security={{ "sanctum": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de favoritos",
     *         @OA\JsonContent(type="array", @OA\Items(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="city", type="string", example="Paris"),
     *             @OA\Property(property="favorite", type="boolean", example=true),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2025-04-10T14:21:00Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2025-04-10T15:00:00Z")
     *         ))
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al obtener los favoritos"
     *     )
     * )
     */
    public function index(Request $request)
    {
        try {
            $favorites = $request->user()
                ->searches()
                ->where('favorite', true)
                ->get();

            return response()->json($favorites);
        } catch (Exception $e) {
            Log::error('Error fetching favorites: ' . $e->getMessage());
            return response()->json([
                'message' => __('messages.favorites_error')
            ], 500);
        }
    }
 /**
     * @OA\Put(
     *     path="/api/favorites/{id}",
     *     summary="Alternar estado de favorito para una búsqueda",
     *     tags={"Favorites"},
     *     security={{ "sanctum": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la búsqueda",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Favorito actualizado correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Marcado como favorito"),
     *             @OA\Property(property="search", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Búsqueda no encontrada"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al actualizar favorito"
     *     )
     * )
     */
    public function toggleFavorite($id)
    {
        try {
            $search = auth()->user()->searches()->findOrFail($id);
            $search->favorite = ! $search->favorite;
            $search->save();

            return response()->json([
                'message' => $search->favorite
                    ? __('messages.favorite_marked')
                    : __('messages.favorite_removed'),
                'search' => $search,
            ]);
        } catch (Exception $e) {
            Log::error("Error toggling favorite: " . $e->getMessage());
            return response()->json([
                'message' => __('messages.favorite_toggle_error')
            ], 500);
        }
    }
}
