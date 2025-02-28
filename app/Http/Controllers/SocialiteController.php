<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function authProviderRedirection($provider)  
    {
        return Socialite::driver($provider)->redirect();
    }

    public function socialAuthentication($provider) 
    {
        try {
            // Get user data from the social provider
            $socialUser = Socialite::driver($provider)->user();
    
            // Find user in the database by provider ID
            $user = User::where('auth_provider_id', $socialUser->id)->first();
    
            if ($user) {
                // Update avatar if not already set
                if (!$user->avatar) {
                    $user->avatar = $socialUser->avatar;
                    $user->save();
                }
                
                Auth::login($user);
            } else {
                // If user doesn't exist, create a new user
                $userData = User::create([
                    'name' => $socialUser->name,
                    'email' => $socialUser->email,
                    'password' => Hash::make('Password@1234'), // Consider using a random password generator
                    'avatar' => $socialUser->avatar, // Store Facebook profile picture
                    'auth_provider' => $provider,
                    'auth_provider_id' => $socialUser->id,
                ]);
    
                Auth::login($userData);
            }
    
            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            Log::error('Social authentication error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Something went wrong during authentication');
        }
    }
    
}
