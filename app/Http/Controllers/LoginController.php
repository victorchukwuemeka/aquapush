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
        dd($githubUser);
        
        
        //find or create user 
        $user = User::updateOrCreate(
            ['github_id' => $githubUser->id,],
            [
                'name' => $githubUser->name,
                'email' => $githubUser->email,
                'password' => $githubUser->password,
                'github_token'=> $githubUser->github_token,
                'github_refresh_token' => $githubUser->refreshToken,
            ]
        );
         
        dd($user);
        //Auth::login($user);
        return redirect('/');
    }
}
