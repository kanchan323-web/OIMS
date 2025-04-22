<?php

namespace App\Http\Controllers\admin\masters;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Edp;
use App\Models\UnitOfMeasurement;
use App\Models\Category;
use App\Models\LogsEdps;
use App\Models\Section;
use Illuminate\Support\Facades\Response;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use DataTables;

class EdpController extends Controller{

    public function index(Request $request)
{
    $moduleName = "EDP List";
    
    if ($request->ajax()) {
        $data = Edp::orderBy('id', 'desc')->get();
        
        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true); 
    }
    
    return view('admin.edp.index', compact('moduleName'));
}

    public function create(){
        $category_list = Category::get();
        $moduleName = "Create EDP";
        $UoM = UnitOfMeasurement::get();
        $section_list = Section::get();
        return view('admin.edp.create', compact('moduleName', 'category_list', 'UoM','section_list'));
    }
    public function store(Request $request){
        $moduleName = "Create EDP";
        $validate = $request->validate([
            'edp_code' => ['required','unique:edps', 'regex:/^(?:[A-Za-z]{2,3}\d{6,7}|\d{9})$/'],
            'section'  => 'required|string',
            'measurement' => 'required',
            'description' => 'required',
        ]);

        if (Edp::where('edp_code', $request->edp_code)->exists()) {
            return redirect()->back()->with('error', 'EDP Code already exists!');
        }

        $materialGroup = strtoupper(substr($request->edp_code, 0, 2));
        // Determine category based on material group
        if ($materialGroup === 'OC') {
            $category = 'capital';
        } elseif (ctype_digit($materialGroup)) {
            $groupNum = intval($materialGroup);
            if ($groupNum >= 1 && $groupNum <= 20) {
                $category = 'store';
            } elseif ($groupNum >= 21 && $groupNum <= 42) {
                $category = 'spares';
            } else {
                $category = 'unknown';
            }
        } else {
            $category = 'unknown';
        }

        $edp = new Edp;
        $edp->edp_code = $request->edp_code;
        $edp->category = $category;
        $edp->material_group = $materialGroup;
        $edp->description = $request->description;
        $edp->section = $request->section;
        $edp->measurement = $request->measurement;
        $edp->save();

        LogsEdps::create([
            'edp_code' => $request->edp_code,
            'category' => $category,
            'material_group' => $materialGroup,
            'description' => $request->description,
            'section' => $request->section,
            'measurement' => $request->measurement,
            'creater_id' => auth()->id(),
            'creater_type' => auth()->user()->user_type,
            'receiver_id' => null,
            'receiver_type' => null,
            'message'       => "EDP '{$request->edp_code}' has been Created.",
        ]);

       // return redirect()->back()->with('success', 'EDP created successfully!');
        return redirect()->route('admin.edp.index')
            ->with('success', 'EDP created successfully.');
    }

    public function edit($id){
        $moduleName = "Edit EDP";
        $category_list = Category::all();
        $editData = Edp::findOrFail($id);
        $UoM = UnitOfMeasurement::get();
        $section_list = Section::get();
        return view('admin.edp.edit', compact('category_list', 'editData', 'moduleName', 'UoM','section_list'));
    }

    public function update(Request $request){
        $validate = $request->validate([
            'edp_code' => ['required','regex:/^(?:[A-Za-z]{2,3}\d{6,7}|\d{9})$/'],
            'section'  => 'required|string',
            'measurement' => 'required',
            'description' => 'required',
        ]);

        $edp = Edp::find($request->edp_id);

        if ($edp) {
            $oldData = $edp->toArray(); // If you want to log old values

                $materialGroup = strtoupper(substr($request->edp_code, 0, 2));
                // Determine category based on material group
                if ($materialGroup === 'OC') {
                    $category = 'capital';
                } elseif (ctype_digit($materialGroup)) {
                    $groupNum = intval($materialGroup);
                    if ($groupNum >= 1 && $groupNum <= 20) {
                        $category = 'store';
                    } elseif ($groupNum >= 21 && $groupNum <= 42) {
                        $category = 'spares';
                    } else {
                        $category = 'unknown';
                    }
                } else {
                    $category = 'unknown';
                }
            $edp->update([
                'edp_code'     => $request->edp_code,
                'category'     => $category,
                'material_group' => $materialGroup,
                'description'  => $request->description,
                'section'      => $request->section,
                'measurement'  => $request->measurement,
            ]);

            // Optional logging
            LogsEdps::create([
                'edp_code'      => $request->edp_code,
                'category'      => $category,
                'material_group' => $materialGroup,
                'description'   => $request->description,
                'section'       => $request->section,
                'measurement'   => $request->measurement,
                'creater_id'    => auth()->id(),
                'creater_type'  => auth()->user()->user_type,
                'receiver_id'   => null,
                'receiver_type' => null,
                'message'       => "EDP '{$oldData['edp_code']}' has been updated.",
            ]);
        } else {
            // Optional error handling
            return response()->json(['error' => 'EDP not found.'], 404);
        }
        return redirect()->route('admin.edp.index')
            ->with('success', 'EDP Updated successfully.');
    }

    public function destroy(Request $request){
        $edp = Edp::find($request->delete_id);

        if ($edp) {
            $edp->delete();

            // Optional: log the deletion
            LogsEdps::create([
                'edp_code'      => $edp->edp_code,
                'category'      => $edp->category,
                'description'   => $edp->description,
                'section'       => $edp->section,
                'measurement'   => $edp->measurement,
                'creater_id'    => auth()->id(),
                'creater_type'  => auth()->user()->user_type,
                'receiver_id'   => null,
                'receiver_type' => null,
                'message'       => "EDP '{$edp->edp_code}' has been deleted.",
            ]);
        } else {
            return response()->json(['error' => 'EDP not found'], 404);
        }

        return redirect()->route('admin.edp.index')
            ->with('success', 'EDP Deleted successfully.');
    }
    public function showImportForm(){
        $moduleName = "Import Bulk EDP";
        return view('admin.edp.import_bulk_edp', compact('moduleName'));
    }
    public function downloadSample(){
        $filePath = public_path('sample-files/sample_edp_admin.xlsx');
        return Response::download($filePath, 'Sample_Edp_File_Admin.xlsx');
    }

    public function import(Request $request){
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
            $expectedHeaders = ['Material', 'Material Description', 'UoM', 'Section'];
            $actualHeaders = array_map(fn($header) => trim((string) $header), array_slice($rows[0], 0, 4));

            // Check headers
            if ($actualHeaders !== $expectedHeaders) {
                Storage::delete($filePath);
                session()->flash('error', 'Invalid file format! Headers do not match the expected format.');
                return redirect()->back();
            }

            $errors = [];
            $seenEdpCodes = [];
            $allowedUoM = ['EA', 'FT', 'GAL', 'KG', 'KIT', 'KL', 'L', 'LB', 'M', 'M3', 'MT', 'NO', 'PAA', 'PAC', 'ROL', 'ST'];

            // Step 1: Validate all rows first
            foreach (array_slice($rows, 1) as $index => $row) {
                if (empty(array_filter($row, fn($value) => trim($value) !== ''))) continue;

                $edpCode = trim($row[0] ?? '');
                $uom = strtoupper(trim($row[2] ?? ''));

                // Validate EDP code (must be exactly 9 characters — digits or uppercase letters)
                if (!preg_match('/^[0-9A-Z]{9}$/', $edpCode)) {
                    $errors[] = "Row " . ($index + 2) . ": EDP code must be 9 characters.";
                }

                // Validate UoM
                if (!in_array($uom, $allowedUoM)) {
                    $errors[] = "Row " . ($index + 2) . ": Invalid UoM value '{$uom}'.";
                }

                // Check for duplicate EDP codes within the file
                if (isset($seenEdpCodes[$edpCode])) {
                    $errors[] = "Row " . ($index + 2) . ": Duplicate EDP code '{$edpCode}' found.";
                } else {
                    $seenEdpCodes[$edpCode] = true;
                }
            }

            // Step 2: If any row is invalid, terminate
            if (!empty($errors)) {
                Storage::delete($filePath);
                session()->flash('error', ['Uploaded file not validated.', ...$errors]);
                return redirect()->back();
            }

            // Step 3: Process the validated data
            foreach (array_slice($rows, 1) as $row) {
                if (empty(array_filter($row, fn($value) => trim($value) !== ''))) continue;

                $edpCode     = trim($row[0]);
                $description = trim($row[1]);
                $measurement = strtoupper(trim($row[2]));
                $section     = trim($row[3]);
                $materialGroup = strtoupper(substr($edpCode, 0, 2));

                // Determine category based on material group
                if ($materialGroup === 'OC') {
                    $category = 'capital';
                } elseif (ctype_digit($materialGroup)) {
                    $groupNum = intval($materialGroup);
                    if ($groupNum >= 1 && $groupNum <= 20) {
                        $category = 'store';
                    } elseif ($groupNum >= 21 && $groupNum <= 42) {
                        $category = 'spares';
                    } else {
                        $category = 'unknown';
                    }
                } else {
                    $category = 'unknown';
                }

                $edp = Edp::updateOrCreate(
                    ['edp_code' => $edpCode],
                    [
                        'description' => $description,
                        'measurement' => $measurement,
                        'section'     => $section,
                        'category'    => $category,
                        'material_group' => $materialGroup,
                    ]
                );

                LogsEdps::create([
                    'edp_code'      => $edp->edp_code,
                    'category'      => $edp->category,
                    'material_group' => $materialGroup,
                    'description'   => $edp->description,
                    'section'       => $edp->section,
                    'measurement'   => $edp->measurement,
                    'creater_id'    => auth()->id(),
                    'creater_type'  => auth()->user()->user_type,
                    'receiver_id'   => null,
                    'receiver_type' => null,
                    'message'       => $edp->wasRecentlyCreated
                        ? "EDP {$edp->edp_code} has been created via import."
                        : "EDP {$edp->edp_code} has been updated via import.",
                ]);
            }

            Storage::delete($filePath);
            session()->flash('success', 'Excel file imported successfully!');
            return redirect()->route('admin.edp.index');
        } catch (\Exception $e) {
            Storage::delete($filePath);
            session()->flash('error', 'Error importing file: ' . $e->getMessage());
            return redirect()->back();
        }
    }
}
