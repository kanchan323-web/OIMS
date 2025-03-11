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

        $categorty_list = Category::get();
    
        return view('admin.category.index', compact('moduleName','categorty_list'));
    }


    public function store(Request $request)
    {


        $request->validate([
            'category_name' => 'required|string',
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
    public function edit($id)
    {
        $moduleName = "Edit Category";
        $editData = Category::where('id', $id)->get()->first();
        return view('admin.category.edit', ['editData' => $editData, 'moduleName' => $moduleName]);
    }

    public function update(Request $request){

            Category::where('id',$request->category_id)->update(
                [
                    'category_name' =>$request->category_name
                ]
            );

            return redirect()->route('admin.category.index')
            ->with('success', 'Category Updated successfully.');  
    }
    public function destroy(Request $request){


        Category::where('id',$request->delete_id)->delete();
        
        return redirect()->route('admin.category.index')
        ->with('success', 'Category Deleted successfully.');  
         
    }
}
