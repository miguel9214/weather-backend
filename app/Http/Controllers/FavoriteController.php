<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        $favorites = $request->user()->searches()->where('favorite', true)->get();
        return response()->json($favorites);
    }

    public function toggleFavorite($id)
    {
        $search = auth()->user()->searches()->findOrFail($id);
        $search->favorite = ! $search->favorite;
        $search->save();

        return response()->json([
            'message' => $search->favorite ? 'Marked as favorite' : 'Removed from favorites',
            'search'  => $search,
        ]);
    }
}
