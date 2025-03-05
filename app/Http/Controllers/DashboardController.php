<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(){
        //return view('user.dashboard');
        if (Auth::check()) {
            return view('user.dashboard');
        }else{
            return redirect()->route('user.login');
        }
    }
}
