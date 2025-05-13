<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UnitOfMeasurement;
use App\Notifications\NewRequestNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Rules\ReCaptcha;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Stock;
use App\Models\LogsStocks;
use App\Models\Edp;
use App\Models\Notification;
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
        $rigId = Auth::user()->rig_id;

        $edpCodes = Edp::all();
        $rigs = RigUser::where(function ($query) {
            $query->where('location_id', '!=', 'admin')
                ->orWhere('name', '!=', 'admin');
        })->get();

        $LocationName = RigUser::where('id', $rigId)->first();

        return view('admin.stock.add_stock', compact('moduleName', 'LocationName', 'edpCodes', 'rigs'));
    }



    public function stock_list(Request $request)
    {
        $rig_id = Auth::user()->rig_id;
        $datarig = User::where('rig_id', $rig_id)
            ->pluck('id')
            ->toArray();

        $stockData = Stock::join('edps', 'stocks.edp_code', '=', 'edps.id')
            ->select('stocks.*', 'edps.edp_code AS EDP_Code')
            ->distinct()
            ->orderBy('stocks.id', 'desc')
            ->get();

        $data = Stock::join('edps', 'stocks.edp_code', '=', 'edps.id')
            ->select('stocks.*', 'edps.edp_code', 'edps.section', 'edps.description')
            ->orderBy('stocks.updated_at', 'desc')
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
                ->when($request->location_name, function ($query, $location_name) {
                    return $query->where('stocks.location_name', 'LIKE', "%{$location_name}%");
                });
            // ->when($request->form_date, function ($query) use ($request) {
            //     return $query->whereDate('stocks.created_at', '>=', Carbon::parse($request->form_date)->startOfDay());
            // })
            // ->when($request->to_date, function ($query) use ($request) {
            //     return $query->whereDate('stocks.created_at', '<=', Carbon::parse($request->to_date)->endOfDay());
            // });


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
        $unit = UnitOfMeasurement::where('abbreviation', $request->measurement)->first();
        $request->merge([
            'qty' => str_replace(',', '', $request->qty),
            'new_spareable' => str_replace(',', '', $request->new_spareable),
            'used_spareable' => str_replace(',', '', $request->used_spareable),
        ]);

        $rules = [
            // 'location_id' => 'required',
            // 'location_name' => 'required',
            'edp_code' => 'required',
            'category' => 'required',
            'description' => 'required',
            'section' => 'required',
            'qty' => 'required|numeric',
            'measurement' => 'required',
            'remarks' => 'required',
            'rig_id' => 'required'
        ];

        if ($unit) {
            if ($unit->type == 'integer') {
                $rules['new_spareable'] = 'required|integer';
                $rules['used_spareable'] = 'required|integer';
            } elseif ($unit->type == 'decimal') {
                $rules['new_spareable'] = 'required|numeric|regex:/^\d+(\.\d{1,10})?$/';
                $rules['used_spareable'] = 'required|numeric|regex:/^\d+(\.\d{1,10})?$/';
            }
        } else {
            return redirect()->back()
                ->withErrors(['measurement' => 'Invalid measurement unit selected.'])
                ->withInput();
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $rigUser = RigUser::find($request->rig_id);
        if (!$rigUser) {
            return redirect()->back()
                ->withErrors(['rig_id' => 'Invalid Rig selected.'])
                ->withInput();
        }

        $existingStock = Stock::where('edp_code', $request->edp_code)
            ->where('rig_id', $request->rig_id)
            ->first();

        if ($existingStock) {
            return redirect()->back()
                ->withErrors(['edp_code' => 'Stock with this EDP code already exists for the selected Rig.'])
                ->withInput();
        }

        $user = Auth::user();
        $stock = new Stock;
        $stock->location_id = $rigUser->location_id;
        $stock->location_name = $rigUser->name;
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
        $stock->rig_id = $request->rig_id;
        $stock->save();

        $user = Auth::user();
        $url = route('stock_list');
        $this->notifyAdmins("User '{$user->user_name}' has created stock '{$stock->description}'.", $url, ['rig_id' => $request->rig_id]);
        $edpCode = Edp::where('id', $request->edp_code)->value('edp_code');

        LogsStocks::create([
            'stock_id'        => $stock->id,
            'location_id'     => $rigUser->location_id,
            'location_name'   => $rigUser->name,
            'edp_code'        => $stock->edp_code,
            'category'        => $stock->category,
            'description'     => $stock->description,
            'section'         => $stock->section,
            'qty'             => $stock->qty,
            'initial_qty'     => $stock->qty,
            'measurement'     => $stock->measurement,
            'new_spareable'   => $stock->new_spareable,
            'used_spareable'  => $stock->used_spareable,
            'new_value'       => $request->new_spareable,
            'used_value'      => $request->used_spareable,
            'remarks'         => $stock->remarks,
            'user_id'         => $stock->user_id,
            'rig_id'          => $stock->rig_id,
            'req_status'      => "Inactive",
            'created_at'      => now(),
            'updated_at'      => now(),
            'creater_id'      => null,
            'creater_type'    => null,
            'receiver_id'     => null,
            'receiver_type'   => null,
            'message'         => "Stock created for EDP Code: {$stock->edp_code}.",
            'action'          => "Added",
            'reference_id'    => $user->cpf_no,
        ]);


        Session::flash('success', 'Stock submitted successfully!');

        return redirect()->route('admin.stock_list');
    }


    // public function stock_list(){
    //     return view('user.stock.list_stock');
    // }


    public function downloadSample()
    {
        $filePath = public_path('sample-files/sample_stock_admin.xlsx');
        return Response::download($filePath, 'Sample_Stock_File_Admin.xlsx');
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
                'EDP',
                'Qty New',
                'Qty Used'
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

                $locationId = trim($row[0]);
                $locationName = RigUser::where('location_id', $locationId)->value('name');

                $new_spareable = (int) $row[2];
                $used_spareable = (int) $row[3];
                $totalqty =  $new_spareable +   $used_spareable;

                // Validate EDP code (Column Index 2)
                if (!isset($row[1]) || !preg_match('/^\d{9}$/', $row[1])) {
                    $errors[] = "Row " . ($index + 2) . ": EDP code must be a 9-digit number.";
                    continue;
                }

                $edp = Edp::where('edp_code', $row[1])->first();
                if (!$edp) {
                    $errors[] = "Row " . ($index + 2) . ": EDP code {$row[1]} not found in the Edp table.";
                    continue;
                }

                $rig = RigUser::where('location_id', $locationId)->first();
                if (!$rig) {
                    $errors[] = "Row " . ($index + 2) . ": Rig {$locationName} not found in the Rig table.";
                    continue;
                }

                // Validate required fields
                $requiredFields = range(0, 3);
                foreach ($requiredFields as $fieldIndex) {
                    if (!isset($row[$fieldIndex]) || trim($row[$fieldIndex]) === '') {
                        $errors[] = "Row " . ($index + 2) . ": Missing required field '" . $expectedHeaders[$fieldIndex] . "'.";
                        continue 2;
                    }
                }

                // Check if stock for the same EDP code already exists
                $existingStock = Stock::where('edp_code', $edp->id)
                    ->where('rig_id', $rig->id)
                    ->first();

                if ($existingStock) {
                    // Update existing stock
                    $existingStock->update([
                        'location_id'   => $locationId,
                        'location_name' => $locationName,
                        'qty'           =>  $totalqty,
                        'new_spareable' => $new_spareable,
                        'used_spareable' => $used_spareable,
                        'user_id'       => $user->id,
                    ]);
                } else {
                    // Insert new stock entry
                    $existingStock = Stock::create([
                        'edp_code'      => $edp->id,
                        'rig_id'        => $rig->id,
                        'location_id'   => $locationId,
                        'location_name' => $locationName,
                        'description'   => $edp->description,
                        'section'       => $edp->section,
                        'category'      => $edp->category,
                        'qty'           => $totalqty,
                        'new_spareable' => $new_spareable,
                        'used_spareable' => $used_spareable,
                        'measurement'   => $edp->measurement,
                        'remarks'       => 'nill',
                        'user_id'       => $user->id,
                    ]);
                }

                LogsStocks::create([
                    'stock_id'        => $existingStock->id,
                    'location_id'     => $rig->location_id,
                    'location_name'   => $rig->name,
                    'edp_code'        => $edp->id,
                    'category'        => $edp->category,
                    'description'     => $edp->description,
                    'section'         => $edp->section,
                    'qty'             => $totalqty,
                    'initial_qty'     => $totalqty,
                    'measurement'     => $edp->measurement,
                    'new_spareable'   => $new_spareable,
                    'used_spareable'  => $used_spareable,
                    'new_value'       => $new_spareable,
                    'used_value'      => $used_spareable,
                    'remarks'         => 'nill',
                    'user_id'         => $user->id,
                    'rig_id'          => $user->rig_id,
                    'req_status'      => "Inactive",
                    'created_at'      => now(),
                    'updated_at'      => now(),
                    'creater_id'      => null,
                    'creater_type'    => null,
                    'receiver_id'     => null,
                    'receiver_type'   => null,
                    'message'         => "Stock created for EDP Code: {$edp->edp_code}.",
                    'action'          => "Added",
                    'reference_id'    => $user->cpf_no,
                ]);
            }

            Storage::delete($filePath);

            if (!empty($errors)) {
                session()->flash('error', $errors);
                return redirect()->back();
            }

            $user = Auth::user();
            $url = route('stock_list');
            $this->notifyAdmins("User '{$user->user_name}' has imported bulk stocks.", $url);

            session()->flash('success', 'Excel file imported successfully!');
            return redirect()->route('admin.stock_list');
        } catch (\Exception $e) {
            session()->flash('error', 'Error importing file: ' . $e->getMessage());
            return redirect()->back();
        }
    }


    public function stock_list_view(Request $request)
    {
        //Log::info('AJAX request received.', ['data' => $request->all()]);

        $id = $request->data;

        $viewdata = Stock::leftJoin('edps', 'stocks.edp_code', '=', 'edps.id')
            ->select('stocks.*', 'edps.edp_code')
            ->where('stocks.id', $id)
            ->first();

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
        $editData = Stock::where('id', $id)->first();
        $edpCodes = Edp::where('id', $editData->edp_code)->first();
        $moduleName = "Edit Stock";
        $rigs = RigUser::where(function ($query) {
            $query->where('location_id', '!=', 'admin')
                ->orWhere('name', '!=', 'admin');
        })->get();

        return view('admin.stock.edit_stock', compact('editData', 'edpCodes', 'moduleName', 'rigs'));
    }



    public function UpdateStock(Request $request)
    {
        $request->merge([
            'qty' => str_replace(',', '', $request->qty),
        ]);

        $dataid = $request->id;

        $user = Auth::user();

        $stock = Stock::find($dataid);

        if (!$stock) {
            return redirect()->route('admin.stock_list')->with('error', 'Stock not found.');
        }

        $unit = UnitOfMeasurement::where('abbreviation', $request->measurement)->first();

        $request->merge([
            'qty' => str_replace(',', '', $request->qty),
            'new_spareable' => str_replace(',', '', $request->new_spareable),
            'used_spareable' => str_replace(',', '', $request->used_spareable),
        ]);

        $rules = [
            // 'location_id' => 'required',
            // 'location_name' => 'required',
            'edp_code' => 'required|integer',
            'category' => 'required',
            'description' => 'required',
            'section' => 'required',
            'qty' => 'required|numeric',
            'measurement' => 'required',
            'remarks' => 'required',
            'rig_id' => 'required'
        ];

        if ($unit) {
            if ($unit->type == 'integer') {
                $rules['new_spareable'] = 'required|integer';
                $rules['used_spareable'] = 'required|integer';
            } elseif ($unit->type == 'decimal') {
                $rules['new_spareable'] = 'required|numeric|regex:/^\d+(\.\d{1,10})?$/';
                $rules['used_spareable'] = 'required|numeric|regex:/^\d+(\.\d{1,10})?$/';
            }
        } else {
            return redirect()->back()
                ->withErrors(['measurement' => 'Invalid measurement unit selected.'])
                ->withInput();
        }

        $rigUser = RigUser::find($request->rig_id);
        if (!$rigUser) {
            return redirect()->back()
                ->withErrors(['rig_id' => 'Invalid Rig selected.'])
                ->withInput();
        }

        $validatedData = $request->validate($rules);
        $validatedData['location_id'] = $rigUser->location_id;
        $validatedData['location_name'] = $rigUser->name;
        $validatedData['rig_id'] = $request->rig_id;
        $validatedData['new_spareable'] = $request->new_spareable;
        $validatedData['used_spareable'] = $request->used_spareable;

        $stock->update($validatedData);

        LogsStocks::create([
            'stock_id'        => $stock->id,
            'location_id'     => $rigUser->location_id,
            'location_name'   => $rigUser->name,
            'edp_code'        => $request->edp_code,
            'category'        => $request->category,
            'description'     => $request->description,
            'section'         => $request->section,
            'qty'             => $request->qty,
            'initial_qty'     => $stock->qty,
            'measurement'     => $request->measurement,
            'new_spareable'   => $stock->new_spareable,
            'used_spareable'  => $stock->used_spareable,
            'new_value'       => $stock->new_spareable,
            'used_value'      => $stock->used_spareable,
            'remarks'         => $request->remarks,
            'user_id'         => auth()->id(),
            'rig_id'          => $request->rig_id,
            'req_status'      => "Inactive",
            'created_at'      => now(),
            'updated_at'      => now(),
            'creater_id'      => null,
            'creater_type'    => auth()->user()->user_type,
            'receiver_id'     => null,
            'receiver_type'   => null,
            'message'         => "Stock Updated for EDP Code: {$request->edp_code}.",
            'action'          => "Modified",
            'reference_id'    => $user->cpf_no,
        ]);

        $user = Auth::user();
        $url = route('stock_list');
        $this->notifyAdmins(
            "User '{$user->user_name}' has edited bulk stock '{$stock->description}'.",
            $url,
            ['rig_id' => $request->rig_id]
        );

        return redirect()->route('admin.stock_list')->with('success', 'Stock updated successfully!');
    }


    public function DeleteStock(Request $request)
    {
        $deleteId = $request->delete_id;
        Stock::where('id', $deleteId)->delete();
        return redirect()->route('admin.stock_list');
    }

    public function get_edp_details(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'User not authenticated'], 401);
        }

        $edpCode = $request->edp_code;
        $rigId = $request->rig_id;

        if (!$edpCode) {
            return response()->json(['success' => false, 'error' => 'EDP Code is missing'], 400);
        }

        $edp = Edp::where('id', $edpCode)->first();

        if (!$edp) {
            return response()->json(['success' => false, 'error' => 'EDP not found'], 404);
        }

        // Fetch stock based on both EDP code and Rig ID
        $stockQuery = Stock::where('edp_code', $edpCode);
        if (!empty($rigId)) {
            $stockQuery->where('rig_id', $rigId);
        }
        $stock = $stockQuery->first();

        return response()->json([
            'success' => true,
            'edp' => [
                'category' => $edp->category,
                'description' => $edp->description,
                'measurement' => $edp->measurement,
                'section' => $edp->section,
            ],
            'stock' => $stock ? [
                'id' => $stock->id,
                'qty' => $stock->qty,
                'new_spareable' => $stock->new_spareable,
                'used_spareable' => $stock->used_spareable,
                'remarks' => $stock->remarks,
                'rig_id' => $stock->rig_id,
            ] : null
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


    private function notifyAdmins($message, $url = null, $rigId = null)
    {
        $admins = User::where('user_type', 'admin')->get();

        foreach ($admins as $admin) {
            $notification = Notification::create([
                'type'            => NewRequestNotification::class,
                'notifiable_type' => User::class,
                'notifiable_id'   => $admin->id,
                'user_id'         => $admin->id,
                'data'            => json_encode([
                    'message' => $message,
                    'url'     => $url ?? route('dashboard'),
                ]),
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            $rigUsers = User::where('rig_id', $rigId)
                ->where('user_type', 'user')
                ->get();

            foreach ($rigUsers as $rigUser) {
                DB::table('notification_user')->insert([
                    'user_id'         => $rigUser->id,
                    'notification_id' => $notification->id,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);
            }

            DB::table('notification_user')->insert([
                'user_id'         => $admin->id,
                'notification_id' => $notification->id,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
        }
    }
}
