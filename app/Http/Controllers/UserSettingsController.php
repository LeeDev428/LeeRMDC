<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserSettingsController extends Controller
{
    public function index()
    {    
        return view('usersettings'); 
    }
}
