<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Rules\ReCaptcha;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Stock;
use App\Models\Edp;
use App\Models\User;
use App\Models\rig_users;
use App\Models\RigUser;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use App\Models\RequestStock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AdminStockController extends Controller
{
    public function add_stock()
    {
        $moduleName = "Add Stock";
        $rigId =  Auth::user()->rig_id;
        $edpCodes = Edp::all();
        $LocationName = RigUser::where('id', $rigId)->first();
        return view('admin.stock.add_stock', compact('moduleName', 'LocationName', 'edpCodes'));
    }


    public function stock_list(Request $request)
    {

        $rig_id = Auth::user()->rig_id;
        $datarig = User::where('rig_id', $rig_id)
            ->pluck('id')
            ->toArray();

        // $stockData = Stock::select('edp_code')->distinct()->get();
        $stockData = DB::table('stocks')
        ->join('edps', 'stocks.edp_code', '=', 'edps.id')
        ->select('stocks.*', 'edps.edp_code AS EDP_Code')  
        ->distinct()
        ->get();

        $data = Stock::join('edps', 'stocks.edp_code', '=', 'edps.id')
            ->select('stocks.*', 'edps.*')
            ->get();

       
        $moduleName = "Stock";
        return view('admin.stock.list_stock', compact('data', 'moduleName', 'stockData', 'datarig'));
    }


    public function stock_filter(Request $request)
    {
        $moduleName = "Stock";
        if ($request->ajax()) {
            $stockData = Stock::select('edp_code')->distinct()->get();

            $data = Stock::query()
                ->when($request->edp_code, function ($query, $edp_code) {
                    return $query->where('stocks.edp_code', $edp_code);
                })
                ->when($request->Description, function ($query, $description) {
                    return $query->where('stocks.description', 'LIKE', "%{$description}%");
                })
                ->when($request->form_date, function ($query) use ($request) {
                    return $query->whereDate('stocks.created_at', '>=', Carbon::parse($request->form_date)->startOfDay());
                })
                ->when($request->to_date, function ($query) use ($request) {
                    return $query->whereDate('stocks.created_at', '<=', Carbon::parse($request->to_date)->endOfDay());
                });

            
                $data = $data->join('edps', 'stocks.edp_code', '=', 'edps.id')
                ->select('stocks.*', 'edps.edp_code AS EDP_Code') 
                ->get();

            $rig_id = Auth::user()->rig_id;
            $datarig = User::where('user_type', '!=', 'admin')
                ->where('rig_id', $rig_id)
                ->pluck('id')
                ->toArray();

            return response()->json(['data' => $data, 'datarig' => $datarig, 'stockData' => $stockData]);
        }

        $data = Stock::all();
        $stockData = Stock::select('edp_code')->distinct()->get();
        return view('admin.stock.list_stock', compact('data', 'moduleName', 'stockData'));
    }



    public function stockSubmit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'location_id' => 'required',
            'location_name' => 'required',
            'edp_code' => 'required|integer|exists:edps,id|unique:stocks,edp_code',
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


        $user = Auth::user();
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
        $stock->user_id = $user->id;
        $stock->rig_id = $user->rig_id;
        $stock->save();
        Session::flash('success', 'Stock submitted successfully!');

        return redirect()->route('admin.stock_list');
    }


    // public function stock_list(){
    //     return view('user.stock.list_stock');
    // }


    public function downloadSample()
    {
        $filePath = public_path('sample-files/sample_stock_admin.xlsx');
        return Response::download($filePath, 'Sample_Stock_File.xlsx');
    }


    public function showImportForm()
    {
        $moduleName = "Import Bulk Stock";
        return view('admin.stock.import_bulk_stock', compact('moduleName'));
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
                'Location ID',
                'Location Name',
                'EDP',
                'Qty Total',
                'New Spareable',
                'Used Spareable',
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
                // Skip empty rows
                if (array_filter($row, fn($value) => !is_null($value) && trim($value) !== '') === []) {
                    continue;
                }

                // Validate EDP code (Column Index 2)
                if (!isset($row[2]) || !preg_match('/^\d{9}$/', $row[2])) {
                    $errors[] = "Row " . ($index + 2) . ": EDP code must be a 9-digit number.";
                    continue;
                }

                $edp = Edp::where('edp_code', $row[2])->first();
                if (!$edp) {
                    $errors[] = "Row " . ($index + 2) . ": EDP code {$row[2]} not found in the Edp table.";
                    continue;
                }

                // Validate required fields
                $requiredFields = range(0, 5);
                foreach ($requiredFields as $fieldIndex) {
                    if (!isset($row[$fieldIndex]) || trim($row[$fieldIndex]) === '') {
                        $errors[] = "Row " . ($index + 2) . ": Missing required field '" . $expectedHeaders[$fieldIndex] . "'.";
                        continue 2;
                    }
                }

                $locationId = $row[0];
                $locationName = $row[1];

                // Check if stock for the same EDP code already exists
                $existingStock = Stock::where('edp_code', $edp->id)
                    ->first();

                if ($existingStock) {
                    // Update existing stock
                    $existingStock->update([
                        'location_id'   => $locationId,
                        'location_name' => $locationName,
                        'qty'           => (int) $row[3],
                        'new_spareable' => (int) $row[4],
                        'used_spareable' => (int) $row[5],
                        'user_id'       => $user->id,
                    ]);
                } else {
                    // Insert new stock entry
                    Stock::create([
                        'edp_code'      => $edp->id,
                        'rig_id'        => $user->rig_id,
                        'location_id'   => $locationId,
                        'location_name' => $locationName,
                        'description'   => $edp->description,
                        'section'       => $edp->section,
                        'category'      => $edp->category,
                        'qty'           => (int) $row[3],
                        'new_spareable' => (int) $row[4],
                        'used_spareable' => (int) $row[5],
                        'measurement'   => $edp->measurement,
                        'remarks'       => 'nill',
                        'user_id'       => $user->id,
                    ]);
                }
            }

            Storage::delete($filePath);

            if (!empty($errors)) {
                session()->flash('error', $errors);
                return redirect()->back();
            }

            session()->flash('success', 'Excel file imported successfully!');
            return redirect()->route('admin.stock_list');
        } catch (\Exception $e) {
            session()->flash('error', 'Error importing file: ' . $e->getMessage());
            return redirect()->back();
        }
    }


    public function stock_list_view(Request $request)
    {
        Log::info('AJAX request received.', ['data' => $request->all()]);
        $id = $request->data;
        $viewdata = Stock::find($id);
        $rig_id = User::where('id', $viewdata->user_id)->value('rig_id');
        $rigdata = RigUser::where('id', $rig_id)->first();
        return response()->json(
            [
                'viewdata' => $viewdata,
                'rigdata' => $rigdata,
            ]
        );
    }


    public function EditStock(Request $request, $id)
    {
        $editData = Stock::where('id', $id)->get()->first();
        $edpCodes = Edp::where('id', $editData->edp_code)->first();
        $moduleName = "Edit Stock";
        return view('admin.stock.edit_stock', ['editData' => $editData, 'moduleName' => $moduleName, 'edpCodes' => $edpCodes]);
    }

    public function UpdateStock(Request $request)
    {
        $dataid = $request->id;
        $user = Auth::user(); 

        $update_data = $request->validate([
            'location_id' => 'required',
            'location_name' => 'required',
            'edp_code' => 'required|integer',
            'category' => 'required',
            'description' => 'required',
            'section' => 'required',
            'qty' => 'required|numeric',
            'measurement' => 'required',
            'new_spareable' => 'required|numeric',
            'used_spareable' => 'required|numeric',
            'remarks' => 'required'
        ]);

        // Add rig_id from authenticated user
        $update_data['rig_id'] = $user->rig_id;

        $stock = Stock::find($dataid);
        if (!$stock) {
            return redirect()->route('admin.stock_list')->with('error', 'Stock not found.');
        }

        $stock->update($update_data);

        return redirect()->route('admin.stock_list')->with('success', 'Stock updated successfully!');
    }


    public function DeleteStock(Request $request)
    {
        $deleteId = $request->delete_id;
        $UData = Stock::where('id', $deleteId)->delete();
        return redirect()->route('admin.stock_list');
    }

    public function get_edp_details(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'User not authenticated'], 401);
        }
        $edpCode = $request->edp_code;

        if (!$edpCode) {
            return response()->json(['success' => false, 'error' => 'EDP Code is missing'], 400);
        }

        $edp = Edp::where('id', $edpCode)->first();

        if (!$edp) {
            return response()->json(['success' => false, 'error' => 'EDP not found'], 404);
        }

        $stock = Stock::where('edp_code', $edpCode)->first();

        return response()->json([
            'success' => true,
            'edp' => $edp,
            'stock' => $stock
        ]);
    }


    // public function downloadPdf(Request $request)
    // {
    //     $query = Stock::query();

    //     $filtersApplied = false;

    //     if ($request->has('category') && $request->category) {
    //         $query->where('category', $request->category);
    //         $filtersApplied = true;
    //     }
    //     if ($request->has('location_name') && $request->location_name) {
    //         $query->where('location_name', 'LIKE', '%' . $request->location_name . '%');
    //         $filtersApplied = true;
    //     }
    //     if ($request->has('form_date') && $request->has('to_date')) {
    //         $query->whereBetween('created_at', [$request->form_date, $request->to_date]);
    //         $filtersApplied = true;
    //     }

    //     $stockData = $filtersApplied ? $query->get() : Stock::all();

    //     // Generate PDF with retrieved data
    //     $pdf = PDF::loadView('pdf.stock_report', compact('stockData'));

    //     return $pdf->download('Stock_Report.pdf');
    // }


    public function checkEdpStock(Request $request)
    {
        $stock = Stock::where('edp_code', $request->edp_code)->first();

        if ($stock) {
            return response()->json([
                'exists' => true,
                'data' => [
                    'location_id' => $stock->location_id,
                    'location_name' => $stock->location_name,
                    'category' => $stock->category,
                    'measurement' => $stock->measurement,
                    'section' => $stock->section,
                    'description' => $stock->description,
                    'qty' => $stock->qty,
                    'new_spareable' => $stock->new_spareable,
                    'used_spareable' => $stock->used_spareable,
                    'remarks' => $stock->remarks
                ]
            ]);
        } else {
            return response()->json(['exists' => false]);
        }
    }
}
