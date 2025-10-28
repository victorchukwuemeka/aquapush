<?php

use App\Http\Controllers\BillingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\DeploymentController;
use App\Http\Controllers\Dashboard\DigitalOceanDropletController;
//DigitalOceanController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\GitHubAuthenticated;
use App\Http\Controllers\ContactController;

//home page 
Route::get('/', [PageController::class, 'landingPage'])->name('home');



Route::get('about/page', [PageController::class, 'about'])->name('about');
Route::get('contact/page', [PageController::class, 'contact'])->name('contact');


//everything about contacting us 
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');


//authentication route 
Route::get('auth/github', [LoginController::class, 'redirectToGitHub'])->name('auth.redirect');
Route::get('auth/github/callback', [LoginController::class, 'handleGitHubCallback']);
Route::post('auth/github', [LoginController::class, 'gitLogout'])->name('logout');

Route::get('auth/google', [LoginController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [LoginController::class, 'handleGoogleCallback']);


//error handling 
Route::get('login/error', function () {
    return view('error.login');
})->name('login-error');
Route::get('not/loggedin', function(){
    return view('error.not-in-session');
})->name('not-in-session');





//everything relating to deployment for the user to handle all needed to 
// Route::get('/deployments', [DeploymentController::class, 'index']);
//->name('deployments.index');
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
Route::get('/digitalocean/config/{droplet_id}', [DigitalOceanDropletController::class, 'configureDeployment'])
 ->name('deployments.configure');


 
// check anything relating to the digitalOceandroplet.
Route::post('/digitalocean/config', [DigitalOceanDropletController::class, 'store'])
->name('digitalocean.store');
Route::get('/digitalocean/show/droplet/{droplet_id}', [DigitalOceanDropletController::class, 'getDroplet'])
 ->name('droplet.show');
Route::post('/droplets/setup/{droplet_id}', [DigitalOceanDropletController::class, 'addRepoToDroplet'])
//->middleware('checkingBilling')
->name('droplet.setup');
Route::delete('/droplets/{droplet_id}', [DigitalOceanDropletController::class, 'deleteDroplet'])
  ->name('droplets.delete');
Route::get('droplets/index', [DigitalOceanDropletController::class, 'index'])
->name('droplets.index');





//dashborad related stuffs 
Route::get('/dashboard', [DashboardController::class, 'index'])
->name('dashboard');
Route::get('api/tokenllll', [DashboardController::class, 'apiToken'])
->name('api.tokens.index');
Route::get('account/setting', [DashboardController::class, 'accountSetting'])
->name('account.settings.index');



/**
 * billing related stuffs
 */
//Route::middleware(['auth'])->group(function () {
    Route::get('/billing', [BillingController::class, 'show'])->name('billing.show');
    Route::post('/billing/pay', [BillingController::class, 'redirectToGateway'])->name('billing.pay');
    Route::get('/billing/callback', [BillingController::class, 'handleGatewayCallback'])->name('billing.callback');
//});




//use App\Http\Controllers\DropletController;

// Single Droplet View
//Route::get('/droplets/{id}', [DropletController::class, 'show'])->name('droplet.show');


//Route::get('/api/tokens', [ApiTokenController::class, 'index'])->name('api.tokens');
//Route::get('/account/settings', [AccountController::class, 'edit'])->name('account.settings');




Route::get('/check-env', function () {
    return [
        'client_id' => env('GITHUB_CLIENT_ID'),
        'client_secret' => env('GITHUB_CLIENT_SECRET'),
        'redirect_url' => env('GITHUB_REDIRECT_URL'),
    ];
});


use Dotenv\Dotenv;
Route::get('/debug-env', function () {
    $dotenv = Dotenv::createImmutable(base_path());
    $dotenv->load();

    return [
        'client_id' => env('GITHUB_CLIENT_ID'),
        'client_secret' => env('GITHUB_CLIENT_SECRET'),
        'redirect_url' => env('GITHUB_REDIRECT_URL'),
    ];
});


