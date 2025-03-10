<?php

namespace App\Http\Controllers\admin\masters;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
      //  $edp = Edp::where('name', '!=', 'admin')->get();
        $moduleName = "Category List";
        return view('admin.category.index', compact('moduleName'));
    }

    public function create()
    {
        $moduleName = "Create Category";
        return view('admin.category.create', compact('moduleName'));
    }
}
