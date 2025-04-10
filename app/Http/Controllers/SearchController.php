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
