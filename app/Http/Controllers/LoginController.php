<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class LoginController extends Controller
{
    public function redirectToGitHub()
    {   
        //dd('vic');
        return Socialite::driver('github')->redirect();
    }

    public function gitLogout(Request $request):RedirectResponse{

        Auth::logout();

        $request->session()->invalidate();
        //$request->session()->regerateToken();

        return redirect()->route('home');
    }

    
    public function handleGitHubCallBack(Request $request)
    {
        $githubUser = Socialite::driver('github')->stateless()->user();
        //dd($githubUser->token);
        
       try { 
            
            //check if user exist
            $user = User::where('github_id', $githubUser->id)->first();

            //login the user if he exist.
            if ($user) {
                Auth::login($user);
                $request->session()->regenerate();
                return redirect()->route('home');
            }


            //check if the email exist
            $user_with_email = User::where('email', $githubUser->email)->first();

            if ($user_with_email) {
                 // If the email exists but GitHub ID doesn't, update the user with GitHub data
                 $user_with_email->update([
                    'github_id' => $githubUser->id,
                    'github_token' => encrypt($githubUser->token),
                    'github_refresh_token' => encrypt($githubUser->refreshToken),
                    'avatar' => $githubUser->avatar,
                ]);
                Auth::login($user_with_email);
            }else {
                // create user 
                $user = User::create(
                    ['github_id' => $githubUser->id,],
                    [   
                        'name' => $githubUser->name,
                        'email' => $githubUser->email,
                        'github_token'=> encrypt($githubUser->token),
                        'github_refresh_token' => encrypt($githubUser->refreshToken),
                        'avatar' => $githubUser->avatar,
                    ]
                );
                Auth::login($user);
            }
            return redirect()->route('home');
        } catch (\Throwable $e) {
            \Log::error('GitHub login failed: ' . $e->getMessage());
            return redirect()->route("login-error")->with('error', 'Unable to login with GitHub.');
        }
    }
}
