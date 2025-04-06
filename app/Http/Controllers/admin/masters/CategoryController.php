<?php


namespace App\Http\Controllers\admin\masters;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\LogsCategory;


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

        LogsCategory::create([
            'category_name' => $request->category_name,
            'creater_id' => auth()->id(),
            'creater_type' => auth()->user()->user_type,
            'receiver_id' => null,
            'receiver_type' => null,
            'message' => 'Category has been created.',
        ]);
        
   
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

            $request->validate([
                'category_name' => 'required|string',
            ]);
            $category = Category::find($request->category_id);

            if ($category) {
                $oldName = $category->category_name;
            
            
                $category->update([
                    'category_name' => $request->category_name
                ]);
            
                LogsCategory::create([
                    'category_name'   => $request->category_name,
                    'creater_id'      => auth()->id(),
                    'creater_type'    => auth()->user()->user_type,
                    'receiver_id'     => null,
                    'receiver_type'   => null,
                    'message'         => "Category has been updated from '$oldName' to '{$request->category_name}'.",
                ]);
            }

            return redirect()->route('admin.category.index')
            ->with('success', 'Category Updated successfully.');  
    }
    public function destroy(Request $request){

        $category = Category::find($request->delete_id);

        if ($category) {
            $categoryName = $category->category_name;
            $category->delete();


            LogsCategory::create([
                'category_name' => $categoryName,
                'creater_id' => auth()->id(),
                'creater_type' => auth()->user()->user_type,
                'receiver_id' => null,
                'receiver_type' => null,
                'message' => 'Category has been deleted.',
            ]);
        }
        
        return redirect()->route('admin.category.index')
        ->with('success', 'Category Deleted successfully.');  
         
    }
}
