<?php

namespace App\Http\Controllers\admin\masters;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Edp;
use App\Models\Category;

class EdpController extends Controller
{
    public function index()
    {
      //  $edp = Edp::where('name', '!=', 'admin')->get();
        $moduleName = "EDP List";
        $edp_list = Edp::get();
        return view('admin.edp.index', compact('moduleName','edp_list'));
    }

    public function create()
    {
        $category_list = Category::get();
        $moduleName = "Create EDP";
        return view('admin.edp.create', compact('moduleName','category_list'));
    }
    public function store(Request $request)
    {

        $moduleName = "Create EDP";

        $validate = $request->validate([
            'edp_code' => 'required|string|size:9',
            'section'  => 'required|string',
            'measurement' => 'required',
            'description' => 'required',
        ]);

        $edp = new Edp;
        $edp->edp_code = $request->edp_code;
        $edp->category = $request->Category_Name;
        $edp->description = $request->description;
        $edp->section = $request->section;
        $edp->measurement = $request->measurement;
        $edp->save();

        return redirect()->back()->with('success', 'EDP created successfully!');
    }

    public function edit($id){
        $moduleName = "Edit EDP";
        $category_list = Category::all();
        $editData = Edp::findOrFail($id);
        return view('admin.edp.edit',compact('category_list','editData','moduleName'));
    }

    public function update(Request $request){
        $validate = $request->validate([
            'edp_code' => 'required|digits:9|numeric',
            'section'  => 'required|string',
            'measurement' => 'required',
            'description' => 'required',
        ]);

        Edp::where('id',$request->edp_id)->update(
            [
                'edp_code' =>  $request->edp_code,
                'category' =>$request->Category_Name,
                'description' =>$request->description,
                'section' =>$request->section,
                'measurement' =>$request->measurement,
            ]
        );
        return redirect()->route('admin.edp.index')
        ->with('success', 'EDP Updated successfully.');  
    }

    public function destroy(Request $request){


        Edp::where('id',$request->delete_id)->delete();
        
        return redirect()->route('admin.edp.index')
        ->with('success', 'EDP Deleted successfully.');  
         
    }
   
}
