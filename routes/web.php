<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\DeploymentController;
use App\Http\Controllers\DigitalOceanController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\GitHubAuthenticated;


//home page 
Route::get('/', [PageController::class, 'landingPage'])->name('home');



//authentication route 
Route::get('auth/github', [LoginController::class, 'redirectToGitHub'])->name('auth.redirect');
Route::get('auth/github/callback', [LoginController::class, 'handleGitHubCallback']);
Route::post('auth/github', [LoginController::class, 'gitLogout'])->name('logout');


//error handling 
Route::get('login/error', function () {
    return view('error.login');
})->name('login-error');
Route::get('not/loggedin', function(){
    return view('error.not-in-session');
})->name('not-in-session');



//everything relating to deployment for the user to handle all needed to 
Route::get('/deployments', [DeploymentController::class, 'index'])->name('deployments.index');
Route::get('/deploy/new', [DeploymentController::class, 'create'])
->middleware(GitHubAuthenticated::class)
->name('deploy.new');
Route::get('/ssh/get', [DeploymentController::class, 'ssh'])->name('get-ssh');


//Route::post('/deploy', [DeploymentController::class, 'fetchRepositoryDetails'])
//->name('deploy');
Route::post('/deploy/droplet', [DeploymentController::class, 'deploy'])->name('deploy.store');
Route::get('/repo-error', function(){
    return view('dashboard.errors.repo-name');
})->name('error-not-laravel');



// everything relating to digitalOcean 
// Show the DigitalOcean configuration form
Route::get('/digitalocean/config/{droplet_id}', [DigitalOceanController::class, 'configureDeployment'])
 ->name('deployments.configure');
 // Handle the form submission
Route::post('/digitalocean/config', [DigitalOceanController::class, 'store'])
->name('digitalocean.store');
Route::get('/digitalocean/show/droplet/{droplet_id}', [DigitalOceanController::class, 'getDroplet'])
 ->name('droplet.show');
Route::post('/droplets/setup/{droplet_id}', [DigitalOceanController::class, 'setupProject'])
->name('droplet.setup');






//dashborad related stuffs 
Route::get('/dashboard', [DashboardController::class, 'index'])
->name('dashboard');
Route::get('deployments/index', [DashboardController::class, 'deployment'])
->name('deployments.index');
Route::get('api/tokenllll', [DashboardController::class, 'apiToken'])
->name('api.tokens.index');
Route::get('account/setting', [DashboardController::class, 'accountSetting'])
->name('account.settings.index');

//use App\Http\Controllers\DropletController;

// Single Droplet View
//Route::get('/droplets/{id}', [DropletController::class, 'show'])->name('droplet.show');


//Route::get('/api/tokens', [ApiTokenController::class, 'index'])->name('api.tokens');
//Route::get('/account/settings', [AccountController::class, 'edit'])->name('account.settings');


