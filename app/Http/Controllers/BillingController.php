<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BillingController extends Controller
{
    public function show(){
        return view('billing.show',[
           "good"
        ]);
    }
}
