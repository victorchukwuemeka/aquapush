<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\DeploymentController;
use App\Http\Controllers\DigitalOceanController;
use App\Http\Controllers\DashboardController;


//home page 
Route::get('/', [PageController::class, 'landingPage'])->name('home');

//authentication route 
Route::get('auth/github', [LoginController::class, 'redirectToGitHub'])->name('auth.redirect');
Route::get('auth/github/callback', [LoginController::class, 'handleGitHubCallback']);

Route::post('auth/github', [LoginController::class, 'gitLogout'])->name('logout');
Route::get('login/error', function () {
    return view('error.login');
})->name('login-error');




//everything relating to deployment for the user to handle all needed to 
Route::get('/deployments', [DeploymentController::class, 'index'])->name('deployments.index');
Route::get('/deploy/new', [DeploymentController::class, 'create'])->name('deploy.new');
//Route::post('/deploy', [DeploymentController::class, 'fetchRepositoryDetails'])
//->name('deploy');
Route::post('/deploy/droplet', [DeploymentController::class, 'store'])->name('deploy.store');

// everything relating to digitalOcean 
// Show the DigitalOcean configuration form
Route::get('/digitalocean/config', [DigitalOceanController::class, 'showForm'])
 ->name('digitalocean.config');
// Handle the form submission
Route::post('/digitalocean/config', [DigitalOceanController::class, 'store'])
->name('digitalocean.store');

//dashborad 
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

//Route::get('/api/tokens', [ApiTokenController::class, 'index'])->name('api.tokens');
//Route::get('/account/settings', [AccountController::class, 'edit'])->name('account.settings');


