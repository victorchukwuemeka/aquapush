<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function redirectToGitHub()
    {   
        
        //dd('victor');
        //login or register with github.
        try {
            Log::info('Attempting GitHub login...');
            return Socialite::driver('github')->redirect();
        } catch (\Throwable $th) {
            Log::error('GitHub login failed: ' . $th->getMessage());
            return redirect()->route('login-error')->with('error', 'GitHub login failed: ' . $th->getMessage());
        }
        
        
    }

    public function gitLogout(Request $request):RedirectResponse{

        Auth::logout();

        $request->session()->invalidate();
        //$request->session()->regerateToken();

        return redirect()->route('home');
    }

    
    public function handleGitHubCallBack(Request $request)
    {     
         //dd('victorsssss');
        // $githubUser = Socialite::driver('github')->user();
        $githubUser = Socialite::driver('github')->stateless()->user();

        // Ensure email and name are always present
        $email = $githubUser->getEmail();
        $name = $githubUser->getName() ?? $githubUser->getNickname() ?? 'GitHub User';
        $avatar = $githubUser->getAvatar() ?? 'default-avatar.png';
        $token = encrypt($githubUser->token);
        $refreshToken = $githubUser->refreshToken ? encrypt($githubUser->refreshToken) : null;
        
      // try { 
            
            //check if user exist
            $user = User::where('github_id', $githubUser->id)->first();

            //login the user if he exist.
            if ($user) {
                Auth::login($user);
                $request->session()->regenerate();
                return redirect()->route('dashboard');
            }


            //check if the email exist
            $user_with_email = User::where('email', $githubUser->email)->first();

            if ($user_with_email) {
                 // If the email exists but GitHub ID doesn't, update the user with GitHub data
                 $user_with_email->update([
                    'github_id' => $githubUser->id,
                    'github_token' => encrypt($token),
                    'github_refresh_token' => encrypt($refreshToken),
                    'avatar' => $avatar,
                ]);
                Auth::login($user_with_email);
            }else {
                // create user 
                if ($githubUser->email) {
                    $user = User::create(
                        ['github_id' => $githubUser->id,
                         'name' => $name,
                         'email' => $email,
                         'github_token'=> encrypt($token),
                         'github_refresh_token' => encrypt($refreshToken),
                         'avatar' => $avatar,
                        ]
                    );
                    Auth::login($user);
                }else {
                    return dd($githubUser);
                }
            }
            return redirect()->route('dashboard');
       // } catch (\Throwable $e) {
            Log::error('GitHub login failed: ' . $e->getMessage());
            return redirect()->route("login-error")->with('error', 'Unable  futo login with GitHub.');
       // }
    }

    // --- Google ---
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request)
{
    try {
        // Get Google user
        $googleUser = Socialite::driver('google')->stateless()->user();

        // Ensure fields are present
        $email = $googleUser->getEmail();
        $name = $googleUser->getName() ?? $googleUser->getNickname() ?? 'Google User';
        $avatar = $googleUser->getAvatar() ?? 'default-avatar.png';
        $token = encrypt($googleUser->token);
        $refreshToken = $googleUser->refreshToken ? encrypt($googleUser->refreshToken) : null;

        // --- Check if user already exists by Google ID ---
        $user = User::where('google_id', $googleUser->id)->first();

        if ($user) {
            Auth::login($user);
            $request->session()->regenerate();
            return redirect()->route('dashboard');
        }

        // --- Check if user exists by email ---
        $user_with_email = User::where('email', $googleUser->email)->first();

        if ($user_with_email) {
            // Link Google account to existing user
            $user_with_email->update([
                'google_id' => $googleUser->id,
                'google_token' => $token,
                'google_refresh_token' => $refreshToken,
                'avatar' => $avatar,
            ]);

            Auth::login($user_with_email);
        } else {
            // Create new user
            if ($googleUser->email) {
                $user = User::create([
                    'google_id' => $googleUser->id,
                    'name' => $name,
                    'email' => $email,
                    'google_token' => $token,
                    'google_refresh_token' => $refreshToken,
                    'avatar' => $avatar,
                ]);
                Auth::login($user);
            } else {
                return dd($googleUser); // fallback if email missing
            }
        }

        return redirect()->route('dashboard');

    } catch (\Throwable $e) {
        Log::error('Google login failed: ' . $e->getMessage());
        return redirect()->route("login-error")->with('error', 'Unable to login with Google.');
    }
}

}
