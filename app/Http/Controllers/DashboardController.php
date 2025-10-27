<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DigitalOceanDroplet;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;

class DashboardController extends Controller
{
    public function index(){
        
        
        return view('dashboard.index');
    }

    public function accountSetting(){
        return view('dashboard.accountSetting.index-account-setting');
    }

    public function apiToken(){
        return view('dashboard.apiToken.index-api-token');
    }
   
    //listing all droplet 
    public function deployment(){
        
        try {
            $user_id = Auth::id();
            $droplets = DigitalOceanDroplet::where('user_id', $user_id)->get();
            return view('dashboard.deployment.index-deployment', ['droplets' => $droplets]);
        } catch (\Throwable $th) {
            return redirect()->back()
            ->with('error', 'Failed to fetch droplets: ' . $th->getMessage());
        }
    }


    
}


