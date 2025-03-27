<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Stock;
use App\Models\Requester;

class DashboardController extends Controller
{
    public function index(){
      $totalUser = User::count();
      $totalRequester = Requester::count();
      $totalStock = Stock::count();


      
      return view('admin.dashboard',compact('totalUser','totalRequester','totalStock'));
    }

    public function get_stock(){
        return view('admin.dashboard');
      }

}
