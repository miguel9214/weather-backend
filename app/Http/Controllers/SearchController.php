<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Services\WeatherService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    protected WeatherService $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    public function search(SearchRequest $request)
    {
        $weather = $this->weatherService->getWeather($request->city);

        $search = $request->user()->searches()->create([
            'city'     => $request->city,
            'data'     => $weather,
            'favorite' => false,
        ]);

        return response()->json($search);
    }

    public function index(Request $request)
    {
        return response()->json(
            $request->user()->searches()->latest()->get()
        );
    }

    public function show($id)
    {
        $search = auth()->user()->searches()->findOrFail($id);
        return response()->json($search);
    }
}
