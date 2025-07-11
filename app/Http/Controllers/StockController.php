<?php

namespace App\Http\Controllers;

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
use App\Models\RigUser;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use App\Models\RequestStock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class StockController extends Controller
{

    public function add_stock()
    {
        $moduleName = "Add Stock";
        $rigId =  Auth::user()->rig_id;
        $edpCodes = Edp::all();
        $LocationName = RigUser::where('id', $rigId)->first();
        return view('user.stock.add_stock', compact('moduleName', 'LocationName', 'edpCodes'));
    }


    public function stock_list(Request $request)
    {
        $rig_id = Auth::user()->rig_id;
        $datarig = User::where('user_type', '!=', 'admin')
            ->where('rig_id', $rig_id)
            ->pluck('id')
            ->toArray();

        $stockData = Stock::join('edps', 'stocks.edp_code', '=', 'edps.id')
            ->select('stocks.*', 'edps.edp_code AS EDP_Code')
            ->where('rig_id', $rig_id)
            ->distinct()
            ->get();


        $data = Stock::join('edps', 'stocks.edp_code', '=', 'edps.id')
            ->join('rig_users', 'stocks.rig_id', '=', 'rig_users.id')
            ->select('stocks.*', DB::raw("DATE_FORMAT(stocks.updated_at, '%d-%m-%Y') as date"), 'edps.edp_code', 'rig_users.name')
            ->where('rig_id', $rig_id)
            ->orderBy('stocks.updated_at', 'desc')
            ->get();
        $moduleName = "Stock List";

        return view('user.stock.list_stock', compact('data', 'moduleName', 'stockData', 'datarig'));
    }


    public function stock_filter(Request $request)
    {
        $moduleName = "Stock";
        $rig_id = Auth::user()->rig_id;
        if ($request->ajax()) {
            $stockData = Stock::select('edp_code')->distinct()->get();

            $data = Stock::query()
                ->when($request->edp_code, function ($query, $edp_code) {
                    return $query->where('stocks.edp_code', $edp_code);
                })
                ->when($request->Description, function ($query, $description) {
                    return $query->where('stocks.description', 'LIKE', "%{$description}%");
                });
            // ->when($request->form_date, function ($query) use ($request) {
            //     return $query->whereDate('stocks.created_at', '>=', Carbon::parse($request->form_date)->startOfDay());
            // })
            // ->when($request->to_date, function ($query) use ($request) {
            //     return $query->whereDate('stocks.created_at', '<=', Carbon::parse($request->to_date)->endOfDay());
            // });

            $data = $data->join('edps', 'stocks.edp_code', '=', 'edps.id')
                ->join('rig_users', 'stocks.rig_id', '=', 'rig_users.id')
                ->select('stocks.*', DB::raw("DATE_FORMAT(stocks.updated_at, '%d-%m-%Y') as date"), 'edps.edp_code AS EDP_Code', 'rig_users.name')
                ->where('rig_id', $rig_id)
                ->orderBy('stocks.updated_at', 'desc')
                ->get();

            $datarig = User::where('user_type', '!=', 'admin')
                ->where('rig_id', $rig_id)
                ->pluck('id')
                ->toArray();

            return response()->json(['data' => $data, 'datarig' => $datarig, 'stockData' => $stockData]);
        }

        $data = Stock::all();
        $stockData = Stock::select('edp_code')->distinct()->get();
        return view('user.stock.list_stock', compact('data', 'moduleName', 'stockData'));
    }



    public function stockSubmit(Request $request)
    {
        $unit = UnitOfMeasurement::where('abbreviation', $request->measurement)->first();

        $rules = [
            'location_id' => 'required',
            'location_name' => 'required',
            'edp_code' => 'required',
            'category' => 'required',
            'description' => 'required',
            'section' => 'required',
            'qty' => 'required|numeric',
            'measurement' => 'required',
            'remarks' => 'required'
        ];

        // Apply dynamic validation based on unit type
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

        // Validate request
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Store stock data
        $user = Auth::user();
        $stock = new Stock;
        $stock->location_id = $request->location_id;
        $stock->location_name = $request->location_name;
        $stock->edp_code = $request->edp_code;
        $stock->category = $request->category;
        $stock->description = $request->description;
        $stock->section = $request->section;
        $stock->qty = $request->qty;
        $stock->initial_qty = $request->qty;
        $stock->measurement = $request->measurement;
        $stock->new_spareable = $request->new_spareable;
        $stock->used_spareable = $request->used_spareable;
        $stock->remarks = $request->remarks;
        $stock->user_id = $user->id;
        $stock->rig_id = $user->rig_id;
        $stock->save();

        $user = Auth::user();
        $url = route('stock_list');
        $this->notifyAdmins("User '{$user->user_name}' has created stock '{$stock->description}'.", $url);
        $edpCode = Edp::where('id', $request->edp_code)->value('edp_code');


        LogsStocks::create([
            'stock_id'        => $stock->id,
            'location_id'     => $stock->location_id,
            'location_name'   => $stock->location_name,
            'edp_code'        => $stock->edp_code,
            'category'        => $stock->category,
            'description'     => $stock->description,
            'section'         => $stock->section,
            'qty'             => $stock->qty,
            'initial_qty'     => $stock->qty,
            'measurement'     => $stock->measurement,
            'new_spareable'   => $stock->new_spareable,
            'used_spareable'  => $stock->used_spareable,
            'new_value'       => $stock->new_spareable,
            'used_value'      => $stock->used_spareable,
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
            'message'         => "Stock created for EDP Code: {$edpCode}.",
            'action'          => "Added",
            'reference_id'    => $user->cpf_no,
        ]);

        Session::flash('success', 'Stock submitted successfully!');

        return redirect()->route('stock_list');
    }



    public function downloadSample()
    {
        $filePath = public_path('sample-files/sample_stock_user.xlsx');

        return Response::download($filePath, 'Sample_Stock_File_User.xlsx');
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
                'EDP',
                'Qty New',
                'Qty Used'
            ];
            $actualHeaders = array_map(fn($header) => trim((string) $header), $rows[0]);

            if ($actualHeaders !== $expectedHeaders) {
                Storage::delete($filePath);
                session()->flash('error', 'Invalid file format! Headers do not match the expected format.');
                return redirect()->back();
            }

            $user = Auth::user();
            $rigUser = RigUser::find($user->rig_id);
            if (!$rigUser) {
                session()->flash('error', 'Rig details not found for the logged-in user.');
                return redirect()->back();
            }

            $errors = [];
            foreach (array_slice($rows, 1) as $index => $row) {
                if (array_filter($row, fn($value) => !is_null($value) && trim($value) !== '') === []) {
                    continue;
                }

                // Validate EDP code (Column Index 0)
                if (!isset($row[0]) || !preg_match('/^(?:[A-Za-z]{2,3}\d{6,7}|\d[A-Za-z]\d{7}|\d{9})$/', $row[0])) {
                    $errors[] = "Row " . ($index + 2) . ": EDP code must be either exactly 9 digits or start with 1 digit, followed by 1 letter, and then 7 digits.";
                    continue;
                }


                $edp = Edp::where('edp_code', $row[0])->first();
                if (!$edp) {
                    $errors[] = "Row " . ($index + 2) . ": EDP code {$row[0]} not found in the Edp table.";
                    continue;
                }


                // Convert quantities to integers
                $qtyNew = (int)$row[1];
                $qtyUsed = (int)$row[2];
                $totalQty = $qtyNew + $qtyUsed;



                // Validate required fields
                $requiredFields = range(0, 2);
                foreach ($requiredFields as $fieldIndex) {
                    if (!isset($row[$fieldIndex]) || trim($row[$fieldIndex]) === '') {
                        $errors[] = "Row " . ($index + 2) . ": Missing required field '" . $expectedHeaders[$fieldIndex] . "'.";
                        continue 2;
                    }
                }

                // Check if stock already exists
                $stock = Stock::where('edp_code', $edp->id)->where('rig_id', $rigUser->id)
                    ->first();

                if ($stock) {
                    // Update existing stock
                    $stock->update([
                        'qty'           => $totalQty,
                        'new_spareable' => $qtyNew,
                        'used_spareable' => $qtyUsed,
                        'user_id'       => $user->id,
                    ]);
                } else {
                    // Insert new stock entry
                   $stock = Stock::create([
                        'edp_code'      => $edp->id,
                        'rig_id'        => $user->rig_id,
                        'location_id'   => $rigUser->location_id,
                        'location_name' => $rigUser->name,
                        'description'   => $edp->description,
                        'section'       => $edp->section,
                        'category'      => $edp->category,
                        'qty'           => $totalQty,
                        'initial_qty'   => $qtyNew,
                        'new_spareable' => $qtyNew,
                        'used_spareable' => $qtyUsed,
                        'measurement'   => $edp->measurement,
                        'remarks'       => 'nill',
                        'user_id'       => $user->id,
                    ]);
                }

                LogsStocks::create([
                    'stock_id'        => $stock->id,
                    'location_id'     => $rigUser->location_id,
                    'location_name'   => $rigUser->name,
                    'edp_code'        => $edp->id,
                    'category'        => $edp->category,
                    'description'     => $edp->description,
                    'section'         => $edp->section,
                    'qty'             => $totalQty,
                    'initial_qty'     => $totalQty,
                    'measurement'     => $edp->measurement,
                    'new_spareable'   => $qtyNew,
                    'used_spareable'  => $qtyUsed,
                    'new_value'       => $qtyNew,
                    'used_value'      => $qtyUsed,
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
            $this->notifyAdmins("User '{$user->user_name}' has imported bulk stock for rig '{$rigUser->name}'.", $url);

            session()->flash('success', 'Excel file imported successfully!');
            return redirect()->route('stock_list');
        } catch (\Exception $e) {
            session()->flash('error', 'Error importing file: ' . $e->getMessage());
            return redirect()->back();
        }
    }



    public function stock_list_view(Request $request)
    {
        Log::info('AJAX request received.', ['data' => $request->all()]);
        $id = $request->data;
        $viewdata =   Stock::select('stocks.*', 'rig_users.name', 'rig_users.location_id', 'edps.*')
            ->join('edps', 'stocks.edp_code', '=', 'edps.id')
            ->join('rig_users', 'stocks.rig_id', '=', 'rig_users.id')
            ->where('stocks.id', $id)
            ->get()->first();

        $viewdatarequest =   Stock::select('stocks.*', 'rig_users.name', 'rig_users.location_id', 'edps.id AS EDPID')
            ->join('edps', 'stocks.edp_code', '=', 'edps.id')
            ->join('rig_users', 'stocks.rig_id', '=', 'rig_users.id')
            ->where('stocks.id', $id)
            ->get()->first();

        return response()->json(
            [
                'viewdata' => $viewdata,
                'for_request_viewdata' => $viewdatarequest,
            ]
        );
    }


    public function EditStock(Request $request, $id)
    {
        $editData = Stock::where('id', $id)->get()->first();
        $edpCodes = Edp::where('id', $editData->edp_code)->first();
        $moduleName = "Edit Stock";
        return view('user.stock.edit_stock', ['editData' => $editData, 'moduleName' => $moduleName, 'edpCodes' => $edpCodes]);
    }

    public function UpdateStock(Request $request)
    {
        $dataid = $request->id;
        $user = Auth::user();
        $edpCode = Edp::where('id', $request->edp_code)->value('edp_code');
        $stock = Stock::find($dataid);
        if (!$stock) {
            return redirect()->route('stock_list')->with('error', 'Stock not found.');
        }

        $unit = UnitOfMeasurement::where('abbreviation', $request->measurement)->first();

        $request->merge([
            'qty' => str_replace(',', '', $request->qty),
            'new_spareable' => str_replace(',', '', $request->new_spareable),
            'used_spareable' => str_replace(',', '', $request->used_spareable),
        ]);

        $rules = [
            'edp_code' => 'required|integer',
            'category' => 'required',
            'description' => 'required',
            'section' => 'required',
            'qty' => 'required|numeric',
            'measurement' => 'required',
            'remarks' => 'required'
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

        $oldNewSpareable = $stock->new_spareable;
        $oldUsedSpareable = $stock->used_spareable;

        $newNewSpareable = $request->new_spareable;
        $newUsedSpareable = $request->used_spareable;

        $newValueDiff = $newNewSpareable - $oldNewSpareable;
        $usedValueDiff = $newUsedSpareable - $oldUsedSpareable;

        Stock::where('id', $dataid)->update([
            'new_spareable' => $request->new_spareable,
            'used_spareable' => $request->used_spareable,
            'remarks' => $request->remarks,
        ]);


        LogsStocks::create([
            'stock_id'        => $stock->id,
            'location_id'     => $request->location_id,
            'location_name'   => $request->location_name,
            'edp_code'        => $stock->edp_code,
            'category'        => $request->category,
            'description'     => $request->description,
            'section'         => $request->section,
            'qty'             => $request->qty,
            'initial_qty'     => $stock->qty,
            'measurement'     => $request->measurement,
            'new_spareable'   => $newNewSpareable,
            'used_spareable'  => $newUsedSpareable,
            'new_value'       => $newValueDiff,
            'used_value'      => $usedValueDiff,
            'remarks'         => $request->remarks,
            'user_id'         => $stock->user_id,
            'rig_id'          => $stock->rig_id,
            'req_status'      => "Inactive",
            'created_at'      => now(),
            'updated_at'      => now(),
            'creater_id'      => null,
            'creater_type'    => null,
            'receiver_id'     => null,
            'receiver_type'   => null,
            'message'         => "Stock Updated for EDP Code: {$edpCode}.",
            'action'          => "Modified",
            'reference_id'    => $user->cpf_no,
        ]);

        $validatedData = $request->validate($rules);

        $validatedData['rig_id'] = $user->rig_id;

        $stock->update($validatedData);

        $user = Auth::user();
        $url = route('stock_list');
        $this->notifyAdmins("User '{$user->user_name}' has edited bulk stock '{$stock->description}'.", $url);

        return redirect()->route('stock_list')->with('success', 'Stock updated successfully!');
    }

    public function DeleteStock(Request $request)
    {
        $deleteId = $request->delete_id;
        $UData = Stock::where('id', $deleteId)->delete();
        return redirect()->route('stock_list');
    }

    public function get_edp_details(Request $request)
    {
        $edpCode = $request->edp_code;

        if (!$edpCode) {
            return response()->json(['success' => false, 'error' => 'EDP Code is missing'], 400);
        }

        //$edp = Edp::where('id', $edpCode)->first();

           $edp = EDP::where('id', $edpCode)
                    ->orWhere('description', 'like', "%$edpCode%")
                    ->first();

        if (!$edp) {
            return response()->json(['success' => false, 'error' => 'EDP not found'], 404);
        }


        $rig_id = Auth::user()->rig_id;

        $stock = Stock::where('edp_code', $edpCode)
            ->where('rig_id', $rig_id)->first();

        return response()->json([
            'success' => true,
            'edp' => $edp,
            'stock' => $stock
        ]);
    }


    public function downloadPdf(Request $request)
    {
        $rig_id = Auth::user()->rig_id;
        $query = Stock::query()
            ->leftJoin('edps', 'stocks.edp_code', '=', 'edps.id')
            ->select('stocks.*', 'edps.edp_code');
            //->orderBy('stocks.updated_at', 'desc');

        $filtersApplied = false;

        if ($request->has('edp_code') && $request->edp_code) {
            $query->where('stocks.edp_code', $request->edp_code);
            $filtersApplied = true;
        }

        if ($request->has('Description') && $request->Description) {
            $query->where('stocks.description', 'LIKE', '%' . $request->Description . '%');
            $filtersApplied = true;
        }

        if ($request->has('form_date') && $request->has('to_date')) {
            $query->whereBetween('stocks.created_at', [$request->form_date, $request->to_date]);
            $filtersApplied = true;
        }

        $stockData = $query->where('rig_id', $rig_id)->orderBy('stocks.updated_at', 'desc')->get();

        // Generate PDF with retrieved data
        $pdf = PDF::loadView('pdf.stock_report', compact('stockData'));

        return $pdf->download('Stock_Report.pdf');
    }


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


    private function notifyAdmins($message, $url = null)
    {
        $user = Auth::user();

        $admins = User::where('user_type', 'admin')->get();

        foreach ($admins as $admin) {
            $notification = Notification::create([
                'type'            => NewRequestNotification::class,
                'notifiable_type' => User::class,
                'notifiable_id'   => $admin->id,
                'user_id'         => $user->id,
                'data'            => json_encode([
                    'message' => $message,
                    'url'     => $url ?? route('dashboard'),
                ]),
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            $rigUsers = User::where('rig_id', $user->rig_id)
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

    public function downloadExcel(Request $request)
    {
        $rig_id = Auth::user()->rig_id;
        $query = Stock::query()
            ->leftJoin('edps', 'stocks.edp_code', '=', 'edps.id')
            ->select('stocks.*', 'edps.edp_code');
           // ->orderBy('stocks.id', 'desc');

        $filtersApplied = false;

        if ($request->has('edp_code') && $request->edp_code) {
            $query->where('stocks.edp_code', $request->edp_code);
            $filtersApplied = true;
        }

        if ($request->has('Description') && $request->Description) {
            $query->where('stocks.description', 'LIKE', '%' . $request->Description . '%');
            $filtersApplied = true;
        }

    /*    if ($request->has('form_date') && $request->has('to_date')) {
            $query->whereBetween('stocks.created_at', [$request->form_date, $request->to_date]);
            $filtersApplied = true;
        }
    */
        $stockDatas = $query->where('rig_id', $rig_id)->orderBy('stocks.updated_at', 'desc')->get();


        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Stock List Report');
        $sheet->setCellValue('A1', 'Sr.No');
        $sheet->setCellValue('B1', 'Location Name(RID)');
        $sheet->setCellValue('C1', 'EDP Code');
        $sheet->setCellValue('D1', 'Section');
        $sheet->setCellValue('E1', 'Description');
        $sheet->setCellValue('F1', 'New Qty');
        $sheet->setCellValue('G1', 'Used Qty');
        $sheet->setCellValue('H1', 'Quantity');
        $sheet->getStyle('A1:H1')->getFont()->setBold(true);
        $row = 2; // Start from the second row to leave space for headers
        $i = 1;
        foreach ($stockDatas as $stockData) {
            $sheet->setCellValue('A' . $row, $i);
            $sheet->setCellValue('B' . $row, $stockData->location_name.' ('. $stockData->location_id.') ');
            $sheet->setCellValue('C' . $row, $stockData->edp_code);
            $sheet->setCellValue('D' . $row, $stockData->section);
            $sheet->setCellValue('E' . $row, $stockData->description);
            $sheet->setCellValue('F' . $row, IND_money_format($stockData->new_spareable));
            $sheet->setCellValue('G' . $row, IND_money_format($stockData->used_spareable));
            $sheet->setCellValue('H' . $row, IND_money_format($stockData->qty));
            $row++;
            $i++;
        }
        $filename = 'Stock List Report';

            $writer = new Xlsx($spreadsheet);

        // Set the correct headers for downloading an Excel file
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        // header('Content-Disposition: attachment;filename="Stock_Reportff.xlsx"');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }
}
