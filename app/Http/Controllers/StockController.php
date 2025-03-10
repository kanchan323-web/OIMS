<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Rules\ReCaptcha;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Stock;
use App\Models\RigUser;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use App\Models\RequestStock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;

class StockController extends Controller
{

    public function add_stock()
    {
        $moduleName = "Add Stock";
        $rigId =  Auth::user()->rig_id;
        $LocationName = RigUser::where('id',$rigId)->first();
        return view('user.stock.add_stock', compact('moduleName','LocationName'));
    }


    public function stock_list(Request $request)
    {
        $data = Stock::when($request->category, function ($query, $category) {
            return $query->where('category', $category);
        })
            ->when($request->location_name, function ($query, $location_name) {
                return $query->where('location_name', 'like', "%{$location_name}%");
            })
            ->when($request->form_date && $request->to_date, function ($query) use ($request) {
                return $query->whereBetween('created_at', [$request->form_date, $request->to_date]);
            })
            ->get();

        $moduleName = "Stock";
        return view('user.stock.list_stock', compact('data', 'moduleName'));
    }

    public function stock_filter(Request $request)
    {
        $moduleName = "Stock";
        $data = Stock::when($request->category, function ($query, $category) {
            return $query->where('category', $category);
        })
            ->when($request->location_name, function ($query, $location_name) {
                return $query->where('location_name', 'like', "%{$location_name}%");
            })
            ->when($request->form_date, function ($query) use ($request) {
                return $query->whereDate('created_at', '>=', Carbon::parse($request->form_date)->startOfDay());
            })
            ->when($request->to_date, function ($query) use ($request) {
                return $query->whereDate('created_at', '<=', Carbon::parse($request->to_date)->endOfDay());
            })->get();


        return view('user.stock.list_stock', compact('data', 'moduleName'));
    }

    public function stockSubmit(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'location_id' => 'required',
            'location_name' => 'required',
            'edp_code' => 'required',
            'category' => 'required',
            'description' => 'required',
            'section' => 'required',
            'qty' => 'required|numeric',
            'measurement' => 'required',
            'new_spareable' => 'required|numeric',
            'used_spareable' => 'required|numeric',
            'remarks' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }


        $stock = new Stock;
        $stock->location_id = $request->location_id;
        $stock->location_name = $request->location_name;
        $stock->edp_code = $request->edp_code;
        $stock->category = $request->category;
        $stock->description = $request->description;
        $stock->section = $request->section;
        $stock->qty = $request->qty;
        $stock->measurement = $request->measurement;
        $stock->new_spareable = $request->new_spareable;
        $stock->used_spareable = $request->used_spareable;
        $stock->remarks = $request->remarks;
        $stock->user_id = Auth::id();
        $stock->save();

        Session::flash('success', 'Stock submitted successfully!');

        return redirect()->route('stock_list');
    }

    // public function stock_list(){
    //     return view('user.stock.list_stock');
    // }


    public function downloadSample()
    {
        $filePath = public_path('sample-files/sample_stock.xlsx');
        return Response::download($filePath, 'Sample_Stock_File.xlsx');
    }


    public function showImportForm()
    {
        $moduleName = "Import Bulk Stock";
        return view('user.stock.import_bulk_stock', compact('moduleName'));
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

            $expectedHeaders = [
                'SLoc',
                'Location',
                'EDP',
                'Material Description',
                'Section',
                'Category',
                'Qty Total',
                'New Spareable',
                'Used Spareable',
                'UoM',
            ];

            $actualHeaders = array_map(fn($header) => trim((string) $header), $rows[0]);

            if ($actualHeaders !== $expectedHeaders) {
                Storage::delete($filePath);
                session()->flash('error', 'Invalid file format! Headers do not match the expected format.');
                return redirect()->back();
            }

            $errors = [];
            foreach (array_slice($rows, 1) as $index => $row) {
                if (array_filter($row, fn($value) => !is_null($value) && trim($value) !== '') === []) {
                    continue;
                }

                // Validate EDP code
                if (!isset($row[2]) || !preg_match('/^\d{9}$/', $row[2])) {
                    $errors[] = "Row " . ($index + 2) . ": EDP code must be a 9-digit number.";
                    continue;
                }

                // Validate required fields (excluding optional fields)
                $requiredFields = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]; // Required field indexes
                foreach ($requiredFields as $fieldIndex) {
                    if (!isset($row[$fieldIndex]) || trim($row[$fieldIndex]) === '') {
                        $errors[] = "Row " . ($index + 2) . ": Missing required field '" . $expectedHeaders[$fieldIndex] . "'.";
                        continue 2; // Skip this row
                    }
                }

                Stock::create([
                    'location_id'   => $row[0],
                    'location_name' => $row[1],
                    'edp_code'      => $row[2],
                    'description'   => $row[3],
                    'section'       => $row[4],
                    'category'      => $row[5],
                    'qty'           => (int) $row[6],
                    'new_spareable' => (int) $row[7],
                    'used_spareable' => (int) $row[8],
                    'measurement'   => $row[9],
                    'remarks'       => $row[10] ?? 'nill',
                    'user_id'       => Auth::id(),
                ]);
            }

            Storage::delete($filePath);

            if (!empty($errors)) {
                session()->flash('error', implode('<br>', $errors));
                return redirect()->back();
            }

            session()->flash('success', 'Excel file imported successfully!');
            return redirect()->route('stock_list');
        } catch (\Exception $e) {
            session()->flash('error', 'Error importing file: ' . $e->getMessage());
            return redirect()->back();
        }
    }



    public function stock_list_view(Request $request)
    {
        $id = $request->data;

        $viewdata =   Stock::where('id', $id)->get()->first();

        return response()->json(
            [
                'viewdata' => $viewdata
            ]
        );
    }
    public function EditStock(Request $request, $id)
    {

        $editData = Stock::where('id', $id)->get()->first();
        $moduleName = "Edit Stock";
        // dd($editData);
        return view('user.stock.edit_stock', ['editData' => $editData, 'moduleName' => $moduleName]);
    }

    public function UpdateStock(Request $request)
    {
        $dataid = $request->id;
        $update_data = $request->validate([
            'location_id' => 'required',
            'location_name' => 'required',
            'edp_code' => 'required',
            'category' => 'required',
            'description' => 'required',
            'section' => 'required',
            'qty' => 'required',
            'measurement' => 'required',
            'new_spareable' => 'required',
            'used_spareable' => 'required',
            'remarks' => 'required'
        ]);
        $UData = Stock::where('id', $dataid)->update($update_data);
        return redirect()->route('stock_list');
    }

    public function DeleteStock(Request $request)
    {
        $deleteId = $request->delete_id;
        $UData = Stock::where('id', $deleteId)->delete();
        return redirect()->route('stock_list');
    }
}
