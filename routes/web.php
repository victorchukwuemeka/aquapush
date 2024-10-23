<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\DeploymentController;
use App\Http\Controllers\DigitalOceanController;


Route::get('/', [PageController::class, 'landingPage'])->name('home');


Route::get('auth/github', [LoginController::class, 'redirectToGitHub'])->name('auth.redirect');
Route::get('auth/github/callback', [LoginController::class, 'handleGitHubCallback']);
Route::post('auth/github', [LoginController::class, 'gitLogout'])->name('logout');
Route::get('login', [LoginController::class, 'gitLogin'])->name('auth.login');


Route::get('login/error', function () {
    return view('error.login');
})->name('login-error');


Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');



Route::get('/dashboard', [DeploymentController::class, 'showDashboard'])->name('dashboard');
Route::post('/deploy', [DeploymentController::class, 'fetchRepositoryDetails'])->name('deploy');


// Show the DigitalOcean configuration form
Route::get('/digitalocean/config', [DigitalOceanController::class, 'showForm'])->name('digitalocean.config');

// Handle the form submission
Route::post('/digitalocean/config', [DigitalOceanController::class, 'store'])->name('digitalocean.store');

