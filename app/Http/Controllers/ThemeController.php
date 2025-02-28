<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ThemeController extends Controller
{
    public function setTheme(Request $request)
    {
        // Validate the theme input (either 'light' or 'dark')
        $request->validate([
            'theme' => 'required|in:light,dark',
        ]);

        // Save the theme to the session
        session(['theme' => $request->theme]);

        // Return a response indicating success
        return response()->json(['success' => true]);
    }
}
