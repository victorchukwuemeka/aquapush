<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\DeploymentController;

Route::get('/', [PageController::class, 'landingPage'])->name('home');


Route::get('auth/github', [LoginController::class, 'redirectToGitHub'])->name('auth.redirect');
Route::get('auth/github/callback', [LoginController::class, 'handleGitHubCallback']);
Route::post('auth/github', [LoginController::class, 'gitLogout'])->name('logout');

Route::get('login/error', function () {
    return view('error.login');
})->name('login-error');


Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');



Route::get('/dashboard', [DeploymentController::class, 'showDashboard'])->name('ddashboard');
Route::post('/deploy', [DeploymentController::class, 'fetchRepositoryDetails'])->name('deploy');
