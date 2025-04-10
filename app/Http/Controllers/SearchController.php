<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use Illuminate\Http\Request;
use App\Services\WeatherService;
use Illuminate\Support\Facades\Log;
use Exception;

class SearchController extends Controller
{
    protected WeatherService $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

        /**
     * @OA\Get(
     *     path="/api/searches",
     *     summary="Listar todas las búsquedas del usuario autenticado",
     *     tags={"Searches"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de búsquedas",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Búsquedas recuperadas exitosamente"),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     )
     * )
     */

    public function index(Request $request)
    {
        try {
            $searches = $request->user()->searches()->latest()->get();

            return response()->json([
                'message' => __('messages.searches_retrieved'),
                'data' => $searches
            ], 200);
        } catch (Exception $e) {
            Log::error('Error fetching searches: ' . $e->getMessage());

            return response()->json([
                'message' => __('messages.searches_fetch_error')
            ], 500);
        }
    }
/**
     * @OA\Post(
     *     path="/api/searches",
     *     summary="Crear una nueva búsqueda",
     *     tags={"Searches"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"city"},
     *             @OA\Property(property="city", type="string", example="Madrid")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Búsqueda creada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Búsqueda creada"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error al obtener el clima"
     *     )
     * )
     */
    public function store(SearchRequest $request)
    {
        try {
            $data = $this->weatherService->getWeather($request->city);

            if (!$data || !is_array($data)) {
                return response()->json([
                    'message' => __('messages.weather_not_retrieved')
                ], 422);
            }

            $search = $request->user()->searches()->create([
                'city'     => $request->city,
                'data'     => $data,
                'favorite' => false,
            ]);

            return response()->json([
                'message' => __('messages.search_created'),
                'data' => $search
            ], 201);
        } catch (Exception $e) {
            Log::error('Error storing search: ' . $e->getMessage());

            return response()->json([
                'message' => __('messages.search_create_error')
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/searches/{id}",
     *     summary="Mostrar una búsqueda específica",
     *     tags={"Searches"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la búsqueda",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Búsqueda encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Búsqueda encontrada"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Búsqueda no encontrada"
     *     )
     * )
     */

    public function show(Request $request, $id)
    {
        try {
            $search = $request->user()->searches()->findOrFail($id);

            return response()->json([
                'message' => __('messages.search_retrieved'),
                'data' => $search
            ], 200);
        } catch (Exception $e) {
            Log::error("Error showing search (ID: $id): " . $e->getMessage());

            return response()->json([
                'message' => __('messages.search_not_found')
            ], 404);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/searches/{id}",
     *     summary="Actualizar una búsqueda",
     *     tags={"Searches"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la búsqueda a actualizar",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"city"},
     *             @OA\Property(property="city", type="string", example="Lima")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Búsqueda actualizada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Búsqueda actualizada"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al actualizar"
     *     )
     * )
     */

    public function update(SearchRequest $request, $id)
    {
        try {
            $search = $request->user()->searches()->findOrFail($id);
            $search->update(['city' => $request->city]);

            return response()->json([
                'message' => __('messages.search_updated'),
                'data' => $search
            ], 200);
        } catch (Exception $e) {
            Log::error("Error updating search (ID: $id): " . $e->getMessage());

            return response()->json([
                'message' => __('messages.search_update_error')
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/searches/{id}",
     *     summary="Eliminar una búsqueda",
     *     tags={"Searches"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la búsqueda a eliminar",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Búsqueda eliminada",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Búsqueda eliminada")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al eliminar"
     *     )
     * )
     */

    public function destroy(Request $request, $id)
    {
        try {
            $search = $request->user()->searches()->findOrFail($id);
            $search->delete();

            return response()->json([
                'message' => __('messages.search_deleted')
            ], 200);
        } catch (Exception $e) {
            Log::error("Error deleting search (ID: $id): " . $e->getMessage());

            return response()->json([
                'message' => __('messages.search_delete_error')
            ], 500);
        }
    }
}
