<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Rules\ReCaptcha;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class StockController extends Controller
{

    public function add_stock(){
         return view('user.stock.add_stock');
    }

    public function stock_list(){
        return view('user.stock.list_stock');
    }
}
