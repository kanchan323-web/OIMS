<?php

namespace App\Http\Controllers\admin\masters;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Edp;
use App\Models\Category;
use Illuminate\Support\Facades\Response;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

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
    public function showImportForm()
    {
        $moduleName = "Import Bulk EDP";
        return view('admin.edp.import_bulk_edp', compact('moduleName'));
    }
    public function downloadSample()
    {
        $filePath = public_path('sample-files/sample_edp_admin.xlsx');
        return Response::download($filePath, 'Sample_Edp_File.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);
        try {
            $file = $request->file('file');
            $filePath = $file->storeAs('temp', $file->getClientOriginalName());
            $spreadsheet = IOFactory::load(storage_path('app/' . $filePath));
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            // Expected headers
            $expectedHeaders = [
                'EDP',
                'Material Description',
                'Section',
                'Category',
                'UoM'  
            ];

            // Ensure the uploaded file matches the expected headers
            $actualHeaders = array_map(fn($header) => trim((string) $header), $rows[0]);
            if ($actualHeaders !== $expectedHeaders) {
                Storage::delete($filePath);
                session()->flash('error', 'Invalid file format! Headers do not match the expected format.');
                return redirect()->back();
            }

            $user = Auth::user();
           
            $errors = [];

            foreach (array_slice($rows, 1) as $index => $row) {
                if (empty(array_filter($row, fn($value) => trim($value) !== ''))) continue;

                // Validate EDP code (Column Index 0)
                if (!isset($row[0]) || !preg_match('/^\d{9}$/', $row[0])) {
                    $errors[] = "Row " . ($index + 2) . ": EDP code must be a 9-digit number.";
                    continue;
                }
    
                $edpCode = $row[0];
                $materialDesc = $row[1];
                $section = $row[2];
                $category = $row[3];
                $uom = $row[4];
                // Check for existing EDP
                $existingEdp = Edp::where('edp_code', $edpCode)->first();
    
                if ($existingEdp) {
                    // Update existing record
                    $existingEdp->update([
                        'description' => $materialDesc,
                        'section'     => $section,
                        'category'    => $category,
                        'measurement' => $uom,
                    ]);
                } else {
                    // Insert new EDP record
                    Edp::create([
                        'edp_code'    => $edpCode,
                        'description' => $materialDesc,
                        'section'     => $section,
                        'category'    => $category,
                        'measurement' => $uom,
                    ]);
                }
            }
            
            Storage::delete($filePath);
            if (!empty($errors)) {
                session()->flash('error', $errors);
                return redirect()->back();
            }
            session()->flash('success', 'Excel file imported successfully!');
            return redirect()->route('admin.edp.index');
        } catch (\Exception $e) {
            session()->flash('error', 'Error importing file: ' . $e->getMessage());
            return redirect()->back();
        }
    }
   
}
