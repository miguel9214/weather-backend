<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WeatherService;
use Illuminate\Support\Facades\Log;
use Exception;

class WeatherController extends Controller
{
    protected WeatherService $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }
/**
     * @OA\Get(
     *     path="/api/weather",
     *     summary="Obtener el clima de una ciudad",
     *     tags={"Weather"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="city",
     *         in="query",
     *         required=true,
     *         description="Nombre de la ciudad",
     *         @OA\Schema(type="string", example="Buenos Aires")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Datos climáticos recuperados",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Clima obtenido exitosamente"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Parámetro de ciudad faltante o inválido"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al obtener el clima"
     *     )
     * )
     */
    public function getWeather(Request $request)
    {
        $request->validate(['city' => 'required|string']);

        try {
            $data = $this->weatherService->getWeather($request->city);

            return response()->json([
                'message' => __('messages.weather_retrieved'),
                'data'    => $data,
            ]);
        } catch (Exception $e) {
            Log::error('Error fetching weather data: ' . $e->getMessage());

            return response()->json([
                'message' => __('messages.weather_fetch_error')
            ], 500);
        }
    }
}
