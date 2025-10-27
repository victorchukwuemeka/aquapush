<?php  
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\AdminUserController;


//admin routes 
Route::get('/dashboard', [AdminController::class, 'dashboard'])
->name('dashboard');

//monitoring users
Route::get('/users', [AdminUserController::class, 'index'])
->name('user-index');


