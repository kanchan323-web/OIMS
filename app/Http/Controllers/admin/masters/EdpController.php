<?php

namespace App\Http\Controllers\admin\masters;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Edp;

class EdpController extends Controller
{
    public function index()
    {
      //  $edp = Edp::where('name', '!=', 'admin')->get();
        $moduleName = "EDP List";
        return view('admin.edp.index', compact('moduleName'));
    }

    public function create()
    {
        $moduleName = "Create EDP";
        return view('admin.edp.create', compact('moduleName'));
    }
}
