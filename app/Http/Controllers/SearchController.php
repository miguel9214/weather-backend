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
                'message' => 'Searches retrieved successfully',
                'data' => $searches
            ]);
        } catch (Exception $e) {
            Log::error('Error fetching searches: ' . $e->getMessage());

            return response()->json([
                'message' => 'An error occurred while retrieving searches'
            ], 500);
        }
    }

    public function store(SearchRequest $request)
    {
        try {
            $data = $this->weatherService->getWeather($request->city);

            $search = $request->user()->searches()->create([
                'city'     => $request->city,
                'data'     => $data,
                'favorite' => false,
            ]);

            return response()->json([
                'message' => 'Search created successfully',
                'data' => $search
            ], 201);
        } catch (Exception $e) {
            Log::error('Error storing search: ' . $e->getMessage());

            return response()->json([
                'message' => 'An error occurred while creating the search'
            ], 500);
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $search = $request->user()->searches()->findOrFail($id);

            return response()->json([
                'message' => 'Search retrieved successfully',
                'data' => $search
            ]);
        } catch (Exception $e) {
            Log::error("Error showing search (ID: $id): " . $e->getMessage());

            return response()->json([
                'message' => 'Search not found'
            ], 404);
        }
    }

    public function update(SearchRequest $request, $id)
    {
        try {
            $search = $request->user()->searches()->findOrFail($id);
            $search->update(['city' => $request->city]);

            return response()->json([
                'message' => 'Search updated successfully',
                'data' => $search
            ]);
        } catch (Exception $e) {
            Log::error("Error updating search (ID: $id): " . $e->getMessage());

            return response()->json([
                'message' => 'An error occurred while updating the search'
            ], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $search = $request->user()->searches()->findOrFail($id);
            $search->delete();

            return response()->json([
                'message' => 'Search deleted successfully'
            ]);
        } catch (Exception $e) {
            Log::error("Error deleting search (ID: $id): " . $e->getMessage());

            return response()->json([
                'message' => 'An error occurred while deleting the search'
            ], 500);
        }
    }
}
