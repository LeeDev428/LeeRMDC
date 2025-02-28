<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Replace with your model

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query');

        // Search the database or other data sources
        $results = User::where('name', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%") // Add more fields as needed
            ->get();

        return view('search.results', compact('results', 'query'));
    }
}
