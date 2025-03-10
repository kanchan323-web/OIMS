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

    public function store(Request $request)
    {

        $request->validate([
            'category_name' => 'required|string|unique:categories,category_name',
        ]);

        if (Category::where('category_name', $request->category_name)->exists()) {
            return redirect()->route('admin.category.create')
                ->with('error', 'Category already exists.');
        }

        $Category = new Category;
        $Category->category_name = $request->category_name;
        $Category->save();
    
        return redirect()->route('admin.category.index')
            ->with('success', 'Category added successfully.');

    }
    public function create()
    {
        $moduleName = "Create Category";
        return view('admin.category.create', compact('moduleName'));
    }
}
