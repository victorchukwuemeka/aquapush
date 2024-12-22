<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GitHubAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        
        //dd($request->user()->id);
        // Check if the user is authenticated and has a GitHub ID
        $user = $request->user();
        if (!$user || !$user->id ) {
            return redirect()->route('not-in-session')->with('error', 'You must log in with GitHub to access this page.');
        }

        return $next($request);
    }
}
