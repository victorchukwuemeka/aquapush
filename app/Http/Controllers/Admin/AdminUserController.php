<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Support\Facades\DB;


class AdminUserController extends Controller
{
    public function index(){
        $today = Visit::whereDate('created_at', today())->count();

        $total = Visit::count();

        $pages = Visit::select('path', DB::raw('count(*) as total'))
                      ->groupBy('path')
                      ->orderByDesc('total')
                      ->get();
        $users = User::all();

        return view('admin.users.admin-user-index', compact('users','today','total','pages'));
    }
}
