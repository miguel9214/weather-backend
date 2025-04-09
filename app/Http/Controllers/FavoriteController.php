<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class FavoriteController extends Controller
{
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
            return response()->json(['message' => 'Error fetching favorites'], 500);
        }
    }

    public function toggleFavorite($id)
    {
        try {
            $search = auth()->user()->searches()->findOrFail($id);
            $search->favorite = ! $search->favorite;
            $search->save();

            return response()->json([
                'message' => $search->favorite ? 'Marked as favorite' : 'Removed from favorites',
                'search'  => $search,
            ]);
        } catch (Exception $e) {
            Log::error("Error toggling favorite: " . $e->getMessage());
            return response()->json(['message' => 'Error updating favorite status'], 500);
        }
    }
}
