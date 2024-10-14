<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function redirectToGitHub()
    {   
        //dd('vic');
        return Socialite::driver('github')->redirect();
    }

    public function handleGitHubCallBack()
    {
        $githubUser = Socialite::driver('github')->stateless()->user();
        
        
        try {
             //find or create user 
             $user = User::updateOrCreate(
                ['github_id' => $githubUser->id,],
                [
                    'name' => $githubUser->name,
                    'email' => $githubUser->email,
                    'github_token'=> $githubUser->github_token,
                    'github_refresh_token' => $githubUser->refreshToken,
                ]
             );
             Auth::login($user);
             return redirect()->route('dashboard');
         
        } catch (\Throwable $e) {
            \Log::error('GitHub login failed: ' . $e->getMessage());
            return redirect('/login')->with('error', 'Unable to login with GitHub.');
        }
    }
}
