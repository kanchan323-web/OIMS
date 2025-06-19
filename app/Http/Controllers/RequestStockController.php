<?php

namespace App\Http\Controllers;

use App\Mail\RequestAcceptedMail;
use App\Mail\RequestDeclinedMail;
use App\Mail\RequestQueryMail;
use App\Models\Edp;
use App\Models\LogsStocks;
use App\Models\RequestStatus;
use App\Notifications\NewRequestNotification;
use Illuminate\Http\Request;
use App\Models\RequestStock;
use App\Models\LogsRequesters;
use App\Models\LogsRequestStatus;
use Illuminate\Support\Facades\DB;
use App\Models\Stock;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\RigUser;
use App\Models\Requester;
use Illuminate\Support\Facades\Mail;
use App\Mail\requestor_stock_mail;
use App\Mail\supplier_stock_mail;
use App\Models\Notification;
use Illuminate\Support\Facades\Session;


class RequestStockController extends Controller
{

    public function RequestStockList(Request $request)
    {
        $rig_id = Auth::user()->rig_id;


        $EDP_Code_ID = Stock::join('edps', 'stocks.edp_code', '=', 'edps.id')
            ->select('stocks.*', 'edps.edp_code AS EDP_Code')
            ->where('rig_id', '!=', $rig_id)
            ->where('qty', '!=', 0)
            ->where('req_status', 'inactive')
            ->get();

        $Stock_Table_Data = Stock::select('stocks.id', 'stocks.measurement', 'stocks.qty', 'rig_users.name', 'users.user_name', 'edps.edp_code', 'edps.category', 'edps.description', 'edps.section')
            ->join('edps', 'stocks.edp_code', '=', 'edps.id')
            ->join('rig_users', 'stocks.rig_id', '=', 'rig_users.id')
            ->join('users', 'stocks.user_id', '=', 'users.id')
            ->where('stocks.rig_id', '!=', $rig_id)
            ->where('stocks.req_status', 'inactive')
            ->where('stocks.qty', '!=', 0)
            ->orderBy('stocks.updated_at', 'desc')
            ->get();




        $moduleName = "Request Stock List";


        return view('request_stock.stock_list_request', compact('Stock_Table_Data', 'moduleName', 'EDP_Code_ID'));
    }


    public function RequestStockFilter(Request $request)
    {

        $rig_id = Auth::user()->rig_id;
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
                ->join('rig_users', 'stocks.rig_id', '=', 'rig_users.id')
                ->where('rig_id', '!=', $rig_id)
                ->where('req_status', 'inactive')
                ->where('qty', '!=', 0)
                ->orderBy('stocks.updated_at', 'desc')
                ->get();

            $datarig = User::where('user_type', '!=', 'admin')
                ->where('rig_id', $rig_id)
                ->pluck('id')
                ->toArray();

            return response()->json(['data' => $data, 'datarig' => $datarig, 'stockData' => $stockData]);
        }
    }

    // public function RequestStockList(Request $request)
    // {

    //     $rig_id = Auth::user()->rig_id;
    //     $datarig = User::where('user_type', '!=', 'admin')
    //         ->where('rig_id', $rig_id)
    //         ->pluck('id')
    //         ->toArray();

    //     $data = RequestStock::get();
    //     $moduleName = "Request Stocks List";
    //     return view('request_stock.list_request_stock', compact('data', 'moduleName', 'datarig'));
    // }




    public function SupplierRequest(Request $request)
    {

        $rig_id = Auth::user()->rig_id;
        $datarig = User::where('user_type', '!=', 'admin')
            ->where('rig_id', $rig_id)
            ->pluck('id')
            ->toArray();

        $data = RequestStock::get();
        $moduleName = "Request Stocks List";
        return view('request_stock.supplier_request', compact('data', 'moduleName', 'datarig'));
    }




    public function StockList(Request $request)
    {
        $rig_id = Auth::user()->rig_id;
        $datarig = User::where('user_type', '!=', 'admin')
            ->where('rig_id', $rig_id)
            ->pluck('id')
            ->toArray();



        $stockData = Stock::join('edps', 'stocks.edp_code', '=', 'edps.id')
            ->select('stocks.*', 'edps.edp_code AS EDP_Code')
            ->where('rig_id', '!=', $rig_id)
            ->where('req_status', 'inactive')
            ->get();

        // dd($stockData);


        $data = Stock::select('stocks.*', 'rig_users.name', 'edps.edp_code', 'edps.category', 'edps.description', 'edps.section')
            ->join('edps', 'stocks.edp_code', '=', 'edps.id')
            ->join('rig_users', 'stocks.rig_id', '=', 'rig_users.id')
            ->where('rig_id', '!=', $rig_id)
            ->where('req_status', 'inactive')
            ->orderBy('stocks.id', 'desc')
            ->get();

        $moduleName = "Request Stock List";
        return view('request_stock.stock_list_request', compact('data', 'moduleName', 'stockData', 'datarig'));
    }




    public function request_stock_filter(Request $request)
    {
        $rig_id = Auth::user()->rig_id;
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

        $data = $data->leftJoin('edps', 'stocks.edp_code', '=', 'edps.id')
            ->select('stocks.*', 'edps.edp_code AS EDP_Code')
            ->where('rig_id', '!=', $rig_id)
            ->where('req_status', 'inactive')
            ->get();



        $moduleName = "Request Stocks filter";
        return view('request_stock.list_request_stock', compact('data', 'moduleName'));
    }




    public function RequestStockAdd(Request $request)
    {
        $moduleName = "Add Stock";
        return view('request_stock.add_request_stock', compact('moduleName'));
    }



    public function RequestStockAddPost(Request $request)
    {
       // return $request->dn_no;
        $request->merge([
            'available_qty' => str_replace(',', '', $request->available_qty),
            'requested_qty' => str_replace(',', '', $request->requested_qty),
        ]);

        $request->validate([
            'available_qty' => 'required|numeric',
            'requested_qty' => 'required|numeric',
            'stock_id' => 'required',
            'requester_id' => 'required',
            'requester_rig_id' => 'required',
            'supplier_id' => 'required',
            'supplier_rig_id' => 'required',
        ]);
        $user = Auth::user();
        $rigUser = RigUser::find($user->rig_id);
        $edp_data = Edp::find($request->req_edp_id);
        $requester_stockID = Stock::where([
            ['edp_code', $request->req_edp_id],
            // ['user_id', $user->id],    
            ['rig_id', $rigUser->id]
        ])->pluck('id')->first();
        $user_type = User::where('id', $request->supplier_id)->value('user_type');

        if (!$requester_stockID) {
            $stock_data = Stock::create([
                'edp_code' => $request->req_edp_id,
                'rig_id' => $rigUser->id,
                'location_id' => $rigUser->location_id,
                'location_name' => $rigUser->name,
                'description' => $edp_data->description,
                'section' => $edp_data->section,
                'category' => $edp_data->category,
                'qty' => 0,
                'initial_qty' => 0,
                'new_spareable' => 0,
                'used_spareable' => 0,
                'measurement' => $edp_data->measurement,
                'remarks' => '',
                'user_id' => $user->id,
            ]);
            $requester_stockID = $stock_data->id;
        }

        try {
            $lastRequest = Requester::latest('id')->first();
            $nextId = $lastRequest ? $lastRequest->id + 1 : 1;
            $RID = 'RS' . str_pad($nextId, 8, '0', STR_PAD_LEFT);

            if (empty($request->expected_date)) {
                $curr_date = Carbon::now();
                $expected_date = $curr_date->addDays(15);
            } else {
                $expected_date = $request->expected_date;
            }

            $SendRequest = Requester::insert([
                'available_qty' => $request->available_qty,
                'requested_qty' => $request->requested_qty,
                'stock_id' => $request->stock_id,
                'requester_stock_id' => $requester_stockID,
                'requester_id' => $request->requester_id,
                'requester_rig_id' => $request->requester_rig_id,
                'supplier_id' => $request->supplier_id,
                'supplier_rig_id' => $request->supplier_location_id,
                'RID' => $RID,
                'dn_no' -> $request->dn_no,
                'remarks' => $request->remark,
                'created_at' => now(),
                'updated_at' => now(),
                'expected_date' => $expected_date,
            ]);

            $insertedRequestID = Requester::latest('id')->value('id');

            RequestStatus::create([
                'request_id' => $insertedRequestID,
                'status_id' => 1,
                'decline_msg' => null,
                'query_msg' => null,
                'supplier_qty' => null,
                'supplier_new_spareable' => null,
                'supplier_used_spareable' => null,
                'user_id' => Auth::id(),
                'rig_id' => $rigUser->id,
                'is_read' => 0,
                'sent_to' => $request->supplier_id,
                'sent_from' => Auth::id(),
            ]);

            $requesterid = User::where('id', $request->requester_id)->value('user_name');
            $supplierid = User::where('id', $request->supplier_id)->value('user_name');
            $requesterRigid = RigUser::where('id', $request->requester_rig_id)->value('location_id');
            $supplierRigid = RigUser::where('id', $request->supplier_location_id)->value('location_id');
            $requesterName = User::where('id', $request->requester_id)->value('user_name');
            $requesterCPFName = User::where('id', $request->requester_id)->value('cpf_no');
            $supplierName = User::where('id', $request->supplier_id)->value('user_name');

            $requesterLocation = RigUser::where('id', $request->requester_rig_id)->value('location_id');
            $requesterLocationName = RigUser::where('id', $request->requester_rig_id)->value('name');
            $supplierLocation = RigUser::where('id', $request->supplier_location_id)->value('location_id');
            $supplierLocationName = RigUser::where('id', $request->supplier_location_id)->value('name');

            $stock = Stock::where('id', $request->stock_id)->first(); // Assumes Stock model exists

            $getedp = Edp::where('id', $stock->edp_code)->first();

            $message = sprintf(
                'The Request sent by User <strong>%s(%s)</strong> of Location <strong>%s</strong> (%s) to supplier Location <strong>%s</strong> (%s) for Material Edp (%s), with description (%s) for quantity <strong>%d</strong>',
                $requesterName,
                $requesterCPFName,
                $requesterLocationName,
                $requesterLocation,
                $supplierLocationName,
                $supplierLocation,
                $getedp->edp_code ?? 'N/A',
                $stock->description ?? 'N/A',
                $request->requested_qty

            );

            LogsRequesters::create([
                'request_id' => $request->requester_id,
                'status' => 1,
                'RID' => $RID,
                'dn_no' -> $request->dn_no,
                'available_qty' => $request->available_qty,
                'requested_qty' => $request->requested_qty,
                'stock_id' => $request->stock_id,
                'edp_code' => $getedp->edp_code,
                'requester_stock_id' => $requester_stockID,
                'requester_id' => $request->requester_id,
                'requester_rig_id' => $request->requester_rig_id,
                'supplier_id' => $request->supplier_id,
                'supplier_rig_id' => $request->supplier_location_id,
                'remarks' -> $request->remark,
                'created_at' => now(),
                'updated_at' => now(),
                'creater_id' => auth()->id(),
                'creater_type' => auth()->user()->user_type,
                'receiver_id' => $request->supplier_id,
                'receiver_type' => $user_type,
                'message' => $message,
                'action' => 'Request Sent',
            ]);

            LogsRequestStatus::create([
                'request_id' => $request->requester_id,
                'status_id' => 1,
                'available_qty' => $request->available_qty,
                'requested_qty' => $request->requested_qty,
                'RID' => $RID,
                'edp_code' => $getedp->edp_code,
                'decline_msg' => null,
                'query_msg' => null,
                'supplier_qty' => $request->available_qty,
                'supplier_new_spareable' => null,
                'supplier_used_spareable' => null,
                'user_id' => Auth::id(),
                'rig_id' => Auth::user()->rig_id,
                'sent_to' => $request->supplier_id,
                'sent_from' => Auth::id(),
                'created_at' => now(),
                'updated_at' => now(),
                'creater_id' => auth()->id(),
                'creater_type' => auth()->user()->user_type,
                'receiver_id' => $request->supplier_id,
                'receiver_type' => $user_type,
                'message' => $message
            ]);



            $updated_stock_status = Stock::where('id', $request->stock_id)->update(['req_status' => 'active']);

            $supplierData = User::where('id', $request->supplier_id)->first();

            $mailDataSupplier = [
                'title' => 'Stock Request from ONGC',
                'supplier_name' => $supplierData->user_name,
                'requester_name' => Auth::user()->user_name,
                'available_qty' => $request->available_qty,
                'requested_qty' => $request->requested_qty,
                'stock_id' => $request->edp_code,
                'requester_rig_id' => $rigUser->name,
                'supplier_rig_id' => $request->supplier_rig_id,
                'created_at' => Carbon::now()->setTimezone('Asia/Kolkata')->format('d M Y, h:i A'),
            ];
            $mailDataRequester = [
                'title' => 'Stock Request Confirmation - ONGC',
                'supplier_name' => $supplierData->user_name,
                'requester_name' => Auth::user()->user_name,
                'available_qty' => $request->available_qty,
                'requested_qty' => $request->requested_qty,
                'stock_id' => $request->edp_code,
                'requester_rig_id' => $rigUser->name,
                'supplier_rig_id' => $request->supplier_rig_id,
                'created_at' => Carbon::now()->setTimezone('Asia/Kolkata')->format('d M Y, h:i A'),
            ];

            $url = route('stock_list.get');
            $this->notifyAdmins("User '{$user->user_name}' has requested stock '{$stock_data->description}' for the rig '{$rigUser->name}'.", $url);

            try {
                if ($supplierData) {
                    $supplierEmail = $supplierData->email;

                    Mail::to(Auth::user()->email)->send(new requestor_stock_mail($mailDataRequester));
                    Mail::to($supplierEmail)->send(new supplier_stock_mail($mailDataSupplier));
                } else {
                    Session::flash('error', 'Supplier not found');
                    return redirect()->route('stock_list.get');
                }
            } catch (\Exception $e) {
                return redirect()->route('stock_list.get')
                    ->with(['email_error' => 'Stock request sent, but email failed: ' . $e->getMessage()]);
            }

            if ($SendRequest) {
                Session::flash('success', 'Request of Stock Sent successfully!');
                return redirect()->route('stock_list.get');
            }
        } catch (\Exception $e) {
            return redirect()->route('stock_list.get')->withErrors('An error occurred: ' . $e->getMessage());
        }
    }




    public function RequestStockViewPost(Request $request)
    {
        $requestStock = Requester::leftJoin('users as request', 'request.id', '=', 'requesters.requester_id')
            ->leftJoin('users as suppliers', 'suppliers.id', '=', 'requesters.supplier_id')
            ->leftJoin('rig_users as request_rig', 'request.rig_id', '=', 'request_rig.id')
            ->leftJoin('stocks', 'stocks.id', '=', 'requesters.stock_id')
            ->leftJoin('rig_users as supply_rig', 'suppliers.rig_id', '=', 'supply_rig.id')
            ->leftJoin('edps', 'edps.id', '=', 'stocks.edp_code')
            ->leftJoin('mst_status', 'requesters.status', '=', 'mst_status.id')
            ->where('requesters.id', $request->data)
            ->with('requestStatuses')
            ->select(
                'requesters.*',
                'request.id as requester_id',
                'request.user_name as requester_name',
                'suppliers.id as supplier_id',
                'suppliers.user_name as supplier_name',
                'request_rig.name as requesters_rig',
                'supply_rig.name as suppliers_rig',
                'stocks.edp_code as stock_edp_code',
                'stocks.category',
                'stocks.section',
                'stocks.description',
                'stocks.measurement',
                'stocks.new_spareable',
                'stocks.used_spareable',
                'stocks.qty',
                'edps.edp_code as edp_code',
                'mst_status.status_name',
                DB::raw("DATE_FORMAT(requesters.created_at, '%d-%m-%Y') as formatted_created_at")
            )
            ->first();

        if (!$requestStock) {
            return response()->json(['success' => false, 'message' => 'Request not found']);
        }

        // Fetch the request status for the viewer
        $request_status = RequestStatus::where('request_id', $request->data)
            ->orderBy('created_at', 'desc')
            ->select(['request_status.*'])
            ->first();

        // Fetch the request status for the viewer
        $supplier_qty = RequestStatus::select('request_status.supplier_qty')
            ->where('request_id', $request->data)
            ->orderBy('created_at', 'desc')
            ->first();

        // Extract supplier quantity (null if not found)
        // $supplier_qty = $request_status->suppliers_qty ?? null;

        return response()->json([
            'success' => true,
            'data' => $requestStock,
            'request_status' => $request_status,
            'supplier_qty' => $supplier_qty
        ]);
    }



    public function IncomingRequestStockList(Request $request)
    {
        $rig_id = Auth::user()->rig_id;
        $datarig = User::where('user_type', '!=', 'admin')
            ->where('rig_id', $rig_id)
            ->pluck('id')
            ->toArray();

        $data = Requester::select(
            'rig_users.name as Location_Name',
            'rig_users.location_id',
            'requesters.*',
            'mst_status.status_name',
            'stocks.id as stock_id',
            'stocks.id as stock_id',
            'stocks.description',
            'edps.edp_code',
        )->join('rig_users', 'requesters.requester_rig_id', '=', 'rig_users.id')
            ->join('stocks', 'requesters.stock_id', '=', 'stocks.id')
            ->join('edps', 'stocks.edp_code', '=', 'edps.id')
            ->leftJoin('mst_status', 'requesters.status', '=', 'mst_status.id')
            ->where('requesters.supplier_rig_id', $rig_id)
            ->with('requestStatuses')
            ->distinct()
            ->orderBy('requesters.updated_at', 'desc')
            ->get();

        $EDP_Code_ID = Requester::join('stocks', 'requesters.stock_id', '=', 'stocks.id')
            ->join('edps', 'stocks.edp_code', '=', 'edps.id')
            ->where('requesters.supplier_rig_id', $rig_id)
            ->select('edps.edp_code')
            ->get();

        //
        $moduleName = "Incoming Request List";
        return view('request_stock.list_request_stock', compact('data', 'moduleName', 'datarig', 'EDP_Code_ID'));
    }



    public function IncomingRequestStockFilter(Request $request)
    {
        $rig_id = Auth::user()->rig_id;

        if ($request->ajax()) {
            $data = Requester::select(
                'rig_users.name as Location_Name',
                'rig_users.location_id',
                'requesters.*',
                DB::raw("DATE_FORMAT(stocks.created_at, '%d-%m-%Y') as creation_date"),
                'mst_status.status_name',
                'stocks.description',
                'stocks.created_at as stock_created_at',
                'edps.edp_code'
            )
                ->join('rig_users', 'requesters.requester_rig_id', '=', 'rig_users.id')
                ->leftJoin('mst_status', 'requesters.status', '=', 'mst_status.id')
                ->join('stocks', 'requesters.stock_id', '=', 'stocks.id')
                ->join('edps', 'stocks.edp_code', '=', 'edps.id')
                ->when($request->edp_code, function ($query, $edp_code) {
                    return $query->where('edps.edp_code', $edp_code);
                })
                ->when($request->Description, function ($query, $description) {
                    return $query->where('stocks.description', 'LIKE', "%{$description}%");
                })
                ->when($request->form_date, function ($query) use ($request) {
                    return $query->whereDate('stocks.created_at', '>=', Carbon::parse($request->form_date)->startOfDay());
                })
                ->when($request->to_date, function ($query) use ($request) {
                    return $query->whereDate('stocks.created_at', '<=', Carbon::parse($request->to_date)->endOfDay());
                })
                ->where('requesters.supplier_rig_id', $rig_id)
                ->orderBy('requesters.updated_at', 'desc')
                ->get();

            return response()->json(['data' => $data]);
        }


        return view('request_stock.list_request_stock', compact('data', 'moduleName', 'datarig'));
    }



    public function accept(Request $request)
    {
        try {
            $requester = Requester::find($request->request_id);
            if (!$requester) {
                return response()->json(['success' => false, 'message' => 'Request not found.'], 404);
            }

            $requester->update(['status' => 6]);

            $supplier_total_qty = $request->supplier_new_spareable + $request->supplier_used_spareable;


            if (Auth::id() == $requester->supplier_id) {
                $sent_to = $requester->requester_id;
            } else {
                $sent_to = $requester->supplier_id;
            }

            RequestStatus::create([
                'request_id' => $request->request_id,
                'status_id' => 6,
                'decline_msg' => null,
                'query_msg' => null,
                'supplier_qty' => $supplier_total_qty,
                'supplier_new_spareable' => $request->supplier_new_spareable,
                'supplier_used_spareable' => $request->supplier_used_spareable,
                'user_id' => Auth::id(),
                'rig_id' => Auth::user()->rig_id,
                'sent_to' => $sent_to,
                'sent_from' => Auth::id()
            ]);

            $requesterTable = Requester::where('id', $request->request_id)->first();
            $RID = Requester::where('id', $request->request_id)->value('RID');
            $stock = Stock::where('id', $requesterTable->stock_id)->first();
            $getedp = Edp::where('id', $stock->edp_code)->first();
            $riglocation = RigUser::where('id', Auth::user()->rig_id)->value('location_id');
            $riglocationName = RigUser::where('id', Auth::user()->rig_id)->value('name');
            $supplierUser = User::select(DB::raw("CONCAT(user_name, ' (', cpf_no, ')') as supplierUser"))
                            ->where('id',  Auth::user()->id)
                            ->first();
            
            $RequesterRigLocation = RigUser::where('id', $requesterTable->requester_rig_id)->value('location_id');
            $RequesterRigLocationName = RigUser::where('id', $requesterTable->requester_rig_id)->value('name');
          /*  $requestorUser = User::select(DB::raw("CONCAT(user_name, ' (', cpf_no, ')') as requestorUser"))
                    ->where('rig_id', $requesterTable->requester_rig_id)
                    ->first();
            */

            $message = sprintf(
                'The material EDP (%s) has been dispatched by supplier user <strong>%s</strong> of Location <strong>%s</strong> (%s) to Location <strong>%s</strong> (%s). The process is now in the MIT stage with the description "%s", for a requested quantity of <strong>%d</strong>, and a quantity issued to the supplier of <strong>%s</strong>.',
                $getedp->edp_code ?? 'N/A',
                $supplierUser->supplierUser ?? '',
                $riglocationName,
                $riglocation,
              //  $requestorUser->requestorUser ?? '',
                $RequesterRigLocationName,
                $RequesterRigLocation,
                $stock->description ?? 'N/A',
                $requesterTable->requested_qty,
                $supplier_total_qty
            );


            LogsRequestStatus::create([
                'decline_msg' => null,
                'query_msg' => null,
                'supplier_qty' => $supplier_total_qty,
                'supplier_new_spareable' => $request->supplier_new_spareable,
                'supplier_used_spareable' => $request->supplier_used_spareable,
                'request_id' => $request->request_id,
                'status_id' => 6,
                'user_id' => Auth::id(),
                'rig_id' => Auth::user()->rig_id,
                'sent_to' => $sent_to,
                'sent_from' => Auth::id(),
                'created_at' => now(),
                'updated_at' => now(),
                'creater_id' => auth()->id(),
                'creater_type' => auth()->user()->user_type,
                'receiver_id' => null,
                'receiver_type' => null,
                'message' => $message,
                'RID' => $requesterTable->RID,
                'available_qty' => $requesterTable->available_qty,
                'requested_qty' => $requesterTable->requested_qty,
                'edp_code' => $getedp->edp_code,
            ]);



            $requester_user = User::find($requester->requester_id);
            $supplier_user = User::find($requester->supplier_id);

            if (!$requester_user || !$supplier_user) {
                return response()->json(['success' => false, 'message' => 'User not found.'], 404);
            }

            $user = Auth::user();
            $url = route('incoming_request_list');
            $this->notifyAdmins("User '{$user->user_name}' has accepted the request'{$requester->RID}'.", $url);

            $receiverEmail = $requester_user->email;

            $mailData = [
                'title' => 'Stock Request Accepted',
                'request_id' => $request->request_id,
                'stock_id' => $requester->RID,
                'requester_name' => $requester_user->user_name,
                'supplier_name' => $supplier_user->user_name,
                'requested_qty' => $requester->requested_qty,
                'supplier_qty' => $supplier_total_qty,
                'supplier_new_spareable' => $request->supplier_new_spareable,
                'supplier_used_spareable' => $request->supplier_used_spareable,
                'requester_rig_id' => $requester->rig_id,
                'supplier_rig_id' => Auth::user()->rig_id,
                'created_at' => $requester->created_at->format('d-m-Y'),
            ];

            // Mail::to($receiverEmail)->send(new RequestAcceptedMail($mailData));

            session()->flash('success', 'Request accepted successfully.');

            return response()->json(['success' => true, 'message' => 'Request accepted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred while processing the request.'], 500);
        }
    }



    public function decline(Request $request)
    {
        try {
            $requester = Requester::find($request->request_id);


            if (!$requester) {
                return response()->json(['success' => false, 'message' => 'Request not found.'], 404);
            }

            $requester->update(['status' => 5]);
            if (Auth::id() == $requester->supplier_id) {
                $sent_to = $requester->requester_id;
            } else {
                $sent_to = $requester->supplier_id;
            }

            RequestStatus::create([
                'request_id' => $request->request_id,
                'status_id' => 5,
                'decline_msg' => $request->decline_msg,
                'query_msg' => null,
                'supplier_qty' => null,
                'supplier_new_spareable' => null,
                'supplier_used_spareable' => null,
                'user_id' => Auth::id(),
                'rig_id' => Auth::user()->rig_id,
                'sent_to' => $sent_to,
                'sent_from' => Auth::id()
            ]);

            $requesterTable = Requester::where('id', $request->request_id)->first();

            $stock = Stock::where('id', $requesterTable->stock_id)->first();
            $getedp = Edp::where('id', $stock->edp_code)->first();
            $supplier_name = User::select(DB::raw("CONCAT(user_name, ' (', cpf_no, ')') as supplier_name"))
                    ->where('id', Auth::user()->id)
                    ->first();
            $riglocation = RigUser::where('id', Auth::user()->rig_id)->value('location_id');
            $riglocationName = RigUser::where('id', Auth::user()->rig_id)->value('name');
         /*   $requestor_name = User::select(DB::raw("CONCAT(user_name, ' (', cpf_no, ')') as requestor_name"))
                    ->where('rig_id', $requesterTable->requester_rig_id)
                    ->first();
         */
            $requesterriglocation = RigUser::where('id', $requesterTable->requester_rig_id)->value('location_id');
            $requesterriglocationName = RigUser::where('id', $requesterTable->requester_rig_id)->value('name');
            

            $message = sprintf(
                'The Request has been declined by supplier User <strong>%s</strong> of Location <strong>%s</strong> (%s) for material EDP (%s) of Location  <strong>%s</strong> (%s) , with description (%s) for a quantity of <strong>%d</strong>. Decline message: (%s).',
                $supplier_name->supplier_name ?? '',
                $riglocationName,
                $riglocation,
                $getedp->edp_code ?? 'N/A',
              //  $requestor_name->requestor_name ?? '',
                $requesterriglocationName ?? 'N/A',
                $requesterriglocation  ?? 'N/A',
                $stock->description ?? 'N/A',
                $requesterTable->requested_qty,
                $request->decline_msg
            );


            LogsRequestStatus::create([
                'decline_msg' => null,
                'query_msg' => null,
                'supplier_qty' => $requesterTable->requested_qty,
                'supplier_qty' => $requesterTable->requested_qty,
                'supplier_new_spareable' => null,
                'supplier_used_spareable' => null,
                'request_id' => $request->request_id,
                'status_id' => 5,
                'user_id' => Auth::id(),
                'rig_id' => Auth::user()->rig_id,
                'sent_to' => $sent_to,
                'sent_from' => Auth::id(),
                'created_at' => now(),
                'updated_at' => now(),
                'creater_id' => auth()->id(),
                'creater_type' => auth()->user()->user_type,
                'receiver_id' => null,
                'receiver_type' => null,
                'message' => $message,
                'RID' => $requesterTable->RID,
                'available_qty' => $requesterTable->available_qty,
                'requested_qty' => $requesterTable->requested_qty,
                'edp_code' => $getedp->edp_code,
            ]);




            $requester_user = User::find($requester->requester_id);
            $supplier_user = User::find($requester->supplier_id);

            if (!$requester_user || !$supplier_user) {
                return response()->json(['success' => false, 'message' => 'User not found.'], 404);
            }

            $receiverEmail = $requester_user->email;

            $mailData = [
                'title' => 'Stock Request Declined',
                'request_id' => $request->request_id,
                'stock_id' => $requester->RID,
                'requester_name' => $requester_user->user_name,
                'supplier_name' => $supplier_user->user_name,
                'requested_qty' => $requester->requested_qty,
                'decline_msg' => $request->decline_msg,
                'requester_rig_id' => $requester->rig_id,
                'supplier_rig_id' => Auth::user()->rig_id,
                'created_at' => $requester->created_at->format('d-m-Y'),
            ];

            Stock::where('id', $requester->stock_id)->update(['req_status' => 'inactive']);

            $user = Auth::user();
            $url = route('incoming_request_list');
            $this->notifyAdmins("User '{$user->user_name}' has declined the request'{$requester->RID}'.", $url);

            // Mail::to($receiverEmail)->send(new RequestDeclinedMail($mailData));

            session()->flash('success', 'Request declined successfully.');

            return response()->json(['success' => true, 'message' => 'Request declined successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred while processing the request.'], 500);
        }
    }



    public function query(Request $request)
    {
        try {
            $requester = Requester::find($request->request_id);
            if (!$requester) {
                return response()->json(['success' => false, 'message' => 'Request not found.'], 404);
            }

            $requester->update(['status' => 2]);

            if (Auth::id() == $requester->supplier_id) {
                $sent_to = $requester->requester_id;
            } else {
                $sent_to = $requester->supplier_id;
            }

            RequestStatus::create([
                'request_id' => $request->request_id,
                'status_id' => 2,
                'decline_msg' => null,
                'query_msg' => $request->query_msg,
                'supplier_qty' => null,
                'supplier_new_spareable' => null,
                'supplier_used_spareable' => null,
                'user_id' => Auth::id(),
                'rig_id' => Auth::user()->rig_id,
                'sent_to' => $sent_to,
                'sent_from' => Auth::id()
            ]);

            $requesterTable = Requester::where('id', $request->request_id)->first();
            $requesterTable = Requester::where('id', $request->request_id)->first();
            $stock = Stock::where('id', $requesterTable->stock_id)->first();
            $getedp = Edp::where('id', $stock->edp_code)->first();
            $riglocation = RigUser::where('id', Auth::user()->rig_id)->value('location_id');
            $riglocationName = RigUser::where('id', Auth::user()->rig_id)->value('name');



            $message = sprintf(
                'The Request is under query by Location <strong>%s</strong> (%s) for material EDP (%s), with the description "%s", for a quantity of <strong>%d</strong>. Query message: "%s".',
                $riglocationName,
                $riglocation,
                $getedp->edp_code ?? 'N/A',
                $stock->description ?? 'N/A',
                $requesterTable->requested_qty,
                $request->query_msg ?? 'N/A'
            );




            LogsRequestStatus::create([
                'decline_msg' => null,
                'query_msg' => $request->query_msg,
                'supplier_qty' => $requesterTable->requested_qty,
                'supplier_new_spareable' => null,
                'supplier_used_spareable' => null,
                'request_id' => $request->request_id,
                'status_id' => 2,
                'user_id' => Auth::id(),
                'rig_id' => Auth::user()->rig_id,
                'sent_to' => $sent_to,
                'sent_from' => Auth::id(),
                'created_at' => now(),
                'updated_at' => now(),
                'creater_id' => auth()->id(),
                'creater_type' => auth()->user()->user_type,
                'receiver_id' => null,
                'receiver_type' => null,
                'message' => $message,
                'RID' => $requesterTable->RID,
                'available_qty' => $requesterTable->available_qty,
                'requested_qty' => $requesterTable->requested_qty,
                'edp_code' => $getedp->edp_code,
            ]);


            $requester_user = User::find($requester->requester_id);
            $receiverEmail = $requester_user->email;
            $supplier_user = User::find($requester->supplier_id);

            $mailData = [
                'title' => 'Stock Request Query Raised',
                'request_id' => $request->request_id,
                'stock_id' => $requester->RID,
                'requester_name' => $requester_user->user_name,
                'supplier_name' => $supplier_user->user_name,
                'requested_qty' => $requester->requested_qty,
                'query_msg' => $request->query_msg,
                'requester_rig_id' => $requester->rig_id,
                'supplier_rig_id' => Auth::user()->rig_id,
                'created_at' => $requester->created_at->format('d-m-Y'),
            ];

            // Mail::to($receiverEmail)->send(new RequestQueryMail($mailData));

            session()->flash('success', 'Query raised successfully.');

            $user = Auth::user();
            $url = route('incoming_request_list');
            $this->notifyAdmins("User '{$user->user_name}' has raised a query for the request'{$requester->RID}'.", $url);

            return response()->json(['success' => true, 'message' => 'Query raised successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error occurred while processing the query.'], 500);
        }
    }



    public function getRequestStock($id)
    {
        $requestStock = RequestStock::find($id);

        if (!$requestStock) {
            return response()->json(['error' => 'Request not found'], 404);
        }

        return response()->json($requestStock);
    }



    public function updateStatus(Request $request)
    {
        $request->validate([
            'request_id' => 'required|exists:requesters,id',
            'status' => 'required|integer'
        ]);

        $requestData = Requester::find($request->request_id);
        if ($requestData) {
            $requestData->status = $request->status;
            $requestData->save();

            $requesterTable = Requester::where('id', $request->request_id)->first();
            $stock = Stock::where('id', $requesterTable->stock_id)->first();
            $requesterTable = Requester::where('id', $request->request_id)->first();
            $stock = Stock::where('id', $requesterTable->stock_id)->first();
            $getedp = Edp::where('id', $stock->edp_code)->first();
            $riglocation = RigUser::where('id', Auth::user()->rig_id)->value('location_id');
            $riglocationName = RigUser::where('id', Auth::user()->rig_id)->value('name');
           // $riglocationUser1 = User::where('rig_id', Auth::user()->rig_id)->value('user_name','cpf_no');

            $riglocationUser = User::select(DB::raw("CONCAT(user_name, ' (', cpf_no, ')') as user"))
                    ->where('id', Auth::user()->id)
                    ->first();

            $message = sprintf(
                'Request has been Received by User <strong>%s</strong> of Location <strong>%s</strong> (%s)  for Material Edp (%s), with description (%s) for quantity <strong>%s</strong> ',
                $riglocationUser->user,
                $riglocationName,
                $riglocation,
                $getedp->edp_code ?? 'N/A',
                $stock->description ?? 'N/A',
                $requesterTable->requested_qty ?? 'N/A',

            );

            // Check if the same message for the same request_id already exists
            $isDuplicate = LogsRequestStatus::where('request_id', $request->request_id)
                ->where('message', $message)
                ->exists();

            if (!$isDuplicate) {
                LogsRequestStatus::create([
                    'decline_msg' => null,
                    'query_msg' => null,
                    'supplier_qty' => $requesterTable->requested_qty,
                    'supplier_new_spareable' => $request->supplier_new_spareable,
                    'supplier_used_spareable' => $request->supplier_used_spareable,
                    'request_id' => $request->request_id,
                    'status_id' => 6,
                    'user_id' => Auth::id(),
                    'rig_id' => Auth::user()->rig_id,
                    'sent_to' => null,
                    'sent_from' => Auth::id(),
                    'created_at' => now(),
                    'updated_at' => now(),
                    'creater_id' => auth()->id(),
                    'creater_type' => auth()->user()->user_type,
                    'receiver_id' => null,
                    'receiver_type' => null,
                    'message' => $message,
                    'RID' => $requesterTable->RID,
                    'available_qty' => $requesterTable->available_qty,
                    'requested_qty' => $requesterTable->requested_qty,
                    'edp_code' => $getedp->edp_code,
                ]);
            }

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 400);
    }



    public function getRequestStatus(Request $request)
    {
        $statuses = RequestStatus::leftJoin('users as requestor', 'requestor.id', '=', 'request_status.user_id')
            ->leftJoin('mst_status as sm', 'sm.id', '=', 'request_status.status_id')
            ->where('request_status.request_id', $request->request_id)
            ->orderBy('request_status.created_at', 'desc')
            ->select([
                'request_status.*',
                'sm.status_name',
                'requestor.user_name as requestor_name',
            ])
            ->get();

        return response()->json($statuses);
    }


    public function RaisedRequestList(Request $request)
    {
        $moduleName = "Raised Request List";
        $userId = Auth::id();
        $rig_id = Auth::user()->rig_id;

        $data = Requester::leftJoin('stocks', 'requesters.stock_id', '=', 'stocks.id')
            ->leftJoin('mst_status', 'requesters.status', '=', 'mst_status.id')
            ->join('rig_users', 'requesters.supplier_rig_id', '=', 'rig_users.id')
            ->join('edps', 'stocks.edp_code', '=', 'edps.id')
            //->where('requesters.supplier_id', $userId)
            ->where('requesters.requester_rig_id', $rig_id)
            ->select('requesters.*', 'stocks.location_name', 'stocks.location_id', 'mst_status.status_name', 'edps.edp_code', 'edps.description')
            ->orderBy('requesters.updated_at', 'desc')
            ->get();

        $datarig = User::where('user_type', '!=', 'admin')
            ->where('rig_id', $rig_id)
            ->pluck('id')
            ->toArray();

        $stocks = Stock::select('id')->where('rig_id', $rig_id)->distinct()->get();

        $edps = Requester::join('stocks', 'requesters.stock_id', '=', 'stocks.id')
            ->join('edps', 'stocks.edp_code', '=', 'edps.id')
            ->where('requesters.requester_rig_id', $rig_id)
            ->select('edps.edp_code', 'edps.id')
            ->get();
        return view('request_stock.supplier_request', compact('data', 'moduleName', 'datarig', 'edps'));
    }


    public function filterRequestStock(Request $request)
    {
        $userId = Auth::id();
        $query = Requester::leftJoin('stocks', 'requesters.stock_id', '=', 'stocks.id')
            ->leftJoin('mst_status', 'requesters.status', '=', 'mst_status.id')
            ->leftJoin('edps', 'stocks.edp_code', '=', 'edps.id')
            ->where('requesters.requester_rig_id', Auth::user()->rig_id)
            ->select('requesters.*', DB::raw("DATE_FORMAT(requesters.updated_at, '%d-%m-%Y') as date"), 'stocks.location_name', 'stocks.location_id', 'mst_status.status_name', 'edps.edp_code', 'edps.description')
            ->orderBy('requesters.updated_at', 'desc')
            ->when($request->edp_code, function ($query, $edp_code) {
                return $query->where('stocks.edp_code', $edp_code);
            })
            ->when($request->location_name, function ($query, $location_name) {
                return $query->whereRaw('LOWER(stocks.location_name) LIKE LOWER(?)', ["%{$location_name}%"]);
            })
            ->when($request->form_date, function ($query) use ($request) {
                return $query->whereDate('requesters.created_at', '>=', Carbon::parse($request->form_date)->startOfDay());
            })
            ->when($request->to_date, function ($query) use ($request) {
                return $query->whereDate('requesters.created_at', '<=', Carbon::parse($request->to_date)->endOfDay());
            });
        $data = $query->get();

        $datarig = User::where('user_type', '!=', 'admin')
            ->where('rig_id', Auth::user()->rig_id)
            ->pluck('id')
            ->toArray();

        return response()->json(['data' => $data, 'datarig' => $datarig]);
    }


    public function updateIsReadStatus(Request $request)
    {
        $request->validate([
            'status_id' => 'required|exists:request_status,id',
        ]);
        $status = RequestStatus::find($request->status_id);
        if ($status && $status->is_read == 0) {
            $status->is_read = 1;
            $status->save();
            return response()->json(['success' => true, 'message' => 'Message marked as read']);
        }
        return response()->json(['success' => false, 'message' => 'Message status update failed'], 400);
    }


    public function updateStock(Request $request)
    {
        DB::beginTransaction();

        try {
            $requestStatus = RequestStatus::where('request_id', $request->request_id)
                ->where('status_id', 6)
                ->first();

            if (!$requestStatus) {
                return response()->json([
                    'success' => false,
                    'message' => 'Request status not found or already processed.'
                ]);
            }

            $requester = Requester::find($request->request_id);
            if (!$requester) {
                return response()->json([
                    'success' => false,
                    'message' => 'Requester not found.'
                ]);
            }

            $stock = Stock::find($requester->stock_id);
            $requesterStock = Stock::find($requester->requester_stock_id);

            Stock::where('id', $stock->id)->update(['req_status' => 'inactive']);

            if (!$stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stock data not found for stock ID.'
                ]);
            }

            if (!$requesterStock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stock data not found for requester stock ID.'
                ]);
            }

            $stock->new_spareable = max(0, $stock->new_spareable - $requestStatus->supplier_new_spareable);
            $stock->used_spareable = max(0, $stock->used_spareable - $requestStatus->supplier_used_spareable);
            $stock->qty = max(0, $stock->new_spareable + $stock->used_spareable);

            $requesterStock->new_spareable += $requestStatus->supplier_new_spareable;
            $requesterStock->used_spareable += $requestStatus->supplier_used_spareable;
            $requesterStock->qty = $requesterStock->new_spareable + $requesterStock->used_spareable;

            if (empty($requesterStock->initial_qty)) {
                $requesterStock->initial_qty = $requesterStock->qty;
            }

            // Save changes
            $stock->save();
            $requesterStock->save();

            // Update requester status
            $requester->status = 3;
            $requester->save();

            $sent_to = (Auth::id() == $requester->supplier_id) ? $requester->requester_id : $requester->supplier_id;

            // Create new request status entry
            RequestStatus::create([
                'request_id' => $request->request_id,
                'status_id' => 3,
                'decline_msg' => null,
                'query_msg' => null,
                'supplier_qty' => $requestStatus->supplier_qty,
                'supplier_new_spareable' => $requestStatus->supplier_new_spareable,
                'supplier_used_spareable' => $requestStatus->supplier_used_spareable,
                'user_id' => Auth::id(),
                'rig_id' => Auth::user()->rig_id,
                'sent_to' => $sent_to,
                'sent_from' => Auth::id()
            ]);


            $requesterTable = Requester::where('id', $request->request_id)->first();
            $stock = Stock::where('id', $requesterTable->stock_id)->first();
            $requesterTable = Requester::where('id', $request->request_id)->first();
            $stock = Stock::where('id', $requesterTable->stock_id)->first();
            $getedp = Edp::where('id', $stock->edp_code)->first();
            $riglocation = RigUser::where('id', Auth::user()->rig_id)->value('location_id');
            $riglocationName = RigUser::where('id', Auth::user()->rig_id)->value('name');
            $user_name = User::select(DB::raw("CONCAT(user_name, ' (', cpf_no, ')') as user_name"))
                    ->where('id', $requesterTable->requester_id)
                    ->first();

            $message = sprintf(
                ' The Request has been Acknowledged and Received by User <strong>%s</strong> of Location <strong>%s</strong>(%s) for material EDP (%s), with the description "%s", for a requested quantity of <strong>%d</strong>, and a quantity issued by  supplier of <strong>%s</strong>.',
                $user_name->user_name,
                $riglocationName,
                $riglocation,
                $getedp->edp_code ?? 'N/A',
                $stock->description ?? 'N/A',
                $requesterTable->requested_qty,
                $requestStatus->supplier_qty

            );






            LogsRequestStatus::create([
                'decline_msg' => null,
                'query_msg' => $request->query_msg,
                'supplier_qty' => $requesterTable->requested_qty,
                'supplier_new_spareable' => null,
                'supplier_used_spareable' => null,
                'request_id' => $request->request_id,
                'status_id' => 3,
                'user_id' => Auth::id(),
                'rig_id' => Auth::user()->rig_id,
                'sent_to' => $sent_to,
                'sent_from' => Auth::id(),
                'created_at' => now(),
                'updated_at' => now(),
                'creater_id' => auth()->id(),
                'creater_type' => auth()->user()->user_type,
                'receiver_id' => null,
                'receiver_type' => null,
                'message' => $message,
                'RID' => $requesterTable->RID,
                'available_qty' => $requesterTable->available_qty,
                'requested_qty' => $requesterTable->requested_qty,
                'edp_code' => $getedp->edp_code,
            ]);


            $edpCode = Edp::where('id', $stock->edp_code)->value('edp_code');
            $user = Auth::user();

            LogsStocks::create([
                'stock_id' => $stock->id,
                'location_id' => $stock->location_id,
                'location_name' => $stock->location_name,
                'edp_code' => $stock->edp_code,
                'category' => $stock->category,
                'description' => $stock->description,
                'section' => $stock->section,
                'qty' => $stock->qty,
                'initial_qty' => $stock->qty,
                'measurement' => $stock->measurement,
                'new_spareable' => $stock->new_spareable,
                'used_spareable' => $stock->used_spareable,
                'new_value' => $requestStatus->supplier_new_spareable,
                'used_value' => $requestStatus->supplier_used_spareable,
                'remarks' => $stock->remarks,
                'user_id' => $stock->user_id,
                'rig_id' => $stock->rig_id,
                'req_status' => "Inactive",
                'created_at' => now(),
                'updated_at' => now(),
                'creater_id' => $stock->rig_id,
                'creater_type' => null,
                'receiver_id' => $requesterStock->rig_id,
                'receiver_type' => null,
                'message' => "Stock Transfered from EDP Code: {$edpCode}.",
                'action' => "Transferred_from",
                'reference_id' => $requester->RID,
            ]);

            LogsStocks::create([
                'stock_id' => $requesterStock->id,
                'location_id' => $requesterStock->location_id,
                'location_name' => $requesterStock->location_name,
                'edp_code' => $requesterStock->edp_code,
                'category' => $requesterStock->category,
                'description' => $requesterStock->description,
                'section' => $requesterStock->section,
                'qty' => $requesterStock->qty,
                'initial_qty' => $requesterStock->qty,
                'measurement' => $requesterStock->measurement,
                'new_spareable' => $requesterStock->new_spareable,
                'used_spareable' => $requesterStock->used_spareable,
                'new_value' => $requestStatus->supplier_new_spareable,
                'used_value' => $requestStatus->supplier_used_spareable,
                'remarks' => $requesterStock->remarks,
                'user_id' => $requesterStock->user_id,
                'rig_id' => $requesterStock->rig_id,
                'req_status' => "Inactive",
                'created_at' => now(),
                'updated_at' => now(),
                'creater_id' => $stock->rig_id,
                'creater_type' => null,
                'receiver_id' => $requesterStock->rig_id,
                'receiver_type' => null,
                'message' => "Stock Transfered to EDP Code: {$edpCode}.",
                'action' => "Transferred_to",
                'reference_id' => $requester->RID,
            ]);


            $user = Auth::user();
            $url = route('raised_requests.index');
            $this->notifyAdmins("User '{$user->user_name}' has acknowledged the receival of the request '{$requester->RID}'.", $url);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'The Receipt Has Been Acknowledged.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ]);
        }
    }




    public function queryforRaisedRequest(Request $request)
    {
        try {
            $requester = Requester::find($request->request_id);
            if (!$requester) {
                return response()->json(['success' => false, 'message' => 'Request not found.'], 404);
            }

            $requester->update(['status' => 2]);

            $sent_to = (Auth::id() == $requester->supplier_id) ? $requester->requester_id : $requester->supplier_id;

            RequestStatus::create([
                'request_id' => $request->request_id,
                'status_id' => 2,
                'decline_msg' => null,
                'query_msg' => $request->query_msg,
                'supplier_qty' => null,
                'supplier_new_spareable' => null,
                'supplier_used_spareable' => null,
                'user_id' => Auth::id(),
                'rig_id' => Auth::user()->rig_id,
                'sent_to' => $sent_to,
                'sent_from' => Auth::id()
            ]);
            $requesterTable = Requester::where('id', $request->request_id)->first();
            $requesterTable = Requester::where('id', $request->request_id)->first();
            $stock = Stock::where('id', $requesterTable->stock_id)->first();
            $getedp = Edp::where('id', $stock->edp_code)->first();
            $riglocation = RigUser::where('id', Auth::user()->rig_id)->value('location_id');
            $riglocationName = RigUser::where('id', Auth::user()->rig_id)->value('name');



            $message = sprintf(
                'The Request is self query by Location <strong>%s</strong> (%s) for material EDP (%s), with the description "%s", for a quantity of <strong>%d</strong>. Query message: "%s".',
                $riglocationName,
                $riglocation,
                $getedp->edp_code ?? 'N/A',
                $stock->description ?? 'N/A',
                $requesterTable->requested_qty,
                $request->query_msg ?? 'N/A'
            );




            LogsRequestStatus::create([
                'decline_msg' => null,
                'query_msg' => $request->query_msg,
                'supplier_qty' => $requesterTable->requested_qty,
                'supplier_new_spareable' => null,
                'supplier_used_spareable' => null,
                'request_id' => $request->request_id,
                'status_id' => 2,
                'user_id' => Auth::id(),
                'rig_id' => Auth::user()->rig_id,
                'sent_to' => $sent_to,
                'sent_from' => Auth::id(),
                'created_at' => now(),
                'updated_at' => now(),
                'creater_id' => auth()->id(),
                'creater_type' => auth()->user()->user_type,
                'receiver_id' => null,
                'receiver_type' => null,
                'message' => $message,
                'RID' => $requesterTable->RID,
                'available_qty' => $requesterTable->available_qty,
                'requested_qty' => $requesterTable->requested_qty,
                'edp_code' => $getedp->edp_code,
            ]);

            // LogsRequestStatus::create([
            //     'request_id' => $request->request_id,
            //     'status_id' => 2,
            //     'decline_msg' => null,
            //     'query_msg' => $request->query_msg,
            //     'supplier_qty' => null,
            //     'supplier_new_spareable' => null,
            //     'supplier_used_spareable' => null,
            //     'user_id' => Auth::id(),
            //     'rig_id' => Auth::user()->rig_id,
            //     'sent_to' => $sent_to,
            //     'sent_from' => Auth::id(),
            //     'created_at' => now(),
            //     'updated_at' => now(),
            //     'creater_id' => auth()->id(),
            //     'creater_type' => auth()->user()->user_type,
            //     'receiver_id' => null,
            //     'receiver_type' => null,
            //     'message' => "Request id " . $request->request_id . " has been Decline  by user " . Auth::user()->user_name
            // ]);


            // Fetch requester and supplier details
            $requester_user = User::find($requester->requester_id);
            $supplier_user = User::find($requester->supplier_id);

            if (!$requester_user || !$supplier_user) {
                return response()->json(['success' => false, 'message' => 'User not found.'], 404);
            }

            // Prepare email data
            $mailData = [
                'title' => 'Stock Request Query Raised',
                'request_id' => $request->request_id,
                'stock_id' => $requester->RID,
                'requester_name' => $requester_user->user_name,
                'supplier_name' => $supplier_user->user_name,
                'requested_qty' => $requester->requested_qty,
                'query_msg' => $request->query_msg,
                'requester_rig_id' => $requester->rig_id,
                'supplier_rig_id' => Auth::user()->rig_id,
                'created_at' => $requester->created_at->format('d-m-Y'),
            ];

            // Mail::to($requester_user->email)->send(new RequestQueryMail($mailData));

            $user = Auth::user();
            $url = route('raised_requests.index');
            $this->notifyAdmins("User '{$user->user_name}' has raised the query for the request '{$requester->RID}'.", $url);

            return response()->json(['success' => true, 'message' => 'Query raised successfully.']);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error occurred while processing the query.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function acceptPendingRequest(Request $request)
    {

        try {
            $requester = Requester::find($request->request_id);
            if (!$requester) {
                return response()->json(['success' => false, 'message' => 'Request not found.'], 404);
            }

            $requester->update(['status' => 4]);
            if (Auth::id() == $requester->supplier_id) {
                $sent_to = $requester->requester_id;
            } else {
                $sent_to = $requester->supplier_id;
            }


            RequestStatus::create([
                'request_id' => $request->request_id,
                'status_id' => 4,
                'query_msg' => null,
                'supplier_qty' => null,
                'supplier_new_spareable' => null,
                'supplier_used_spareable' => null,
                'user_id' => Auth::id(),
                'rig_id' => Auth::user()->rig_id,
                'sent_to' => $sent_to,
                'sent_from' => Auth::id()
            ]);

            LogsRequestStatus::create([
                'request_id' => $request->request_id,
                'status_id' => 4,
                'query_msg' => null,
                'supplier_qty' => null,
                'supplier_new_spareable' => null,
                'supplier_used_spareable' => null,
                'user_id' => Auth::id(),
                'rig_id' => Auth::user()->rig_id,
                'sent_to' => $sent_to,
                'sent_from' => Auth::id(),
                'created_at' => now(),
                'updated_at' => now(),
                'creater_id' => auth()->id(),
                'creater_type' => auth()->user()->user_type,
                'receiver_id' => null,
                'receiver_type' => null,
                'message' => "Request id " . $request->request_id . " has been Decline  by user " . Auth::user()->user_name
            ]);

            $requester_user = User::find($requester->requester_id);
            $supplier_user = User::find($requester->supplier_id);

            if (!$requester_user || !$supplier_user) {
                return response()->json(['success' => false, 'message' => 'User not found.'], 404);
            }

            $receiverEmail = $requester_user->email;

            // $mailData = [
            //     'title' => 'Stock Request Declined',
            //     'request_id' => $request->request_id,
            //     'stock_id' => $requester->RID,
            //     'requester_name' => $requester_user->user_name,
            //     'supplier_name' => $supplier_user->user_name,
            //     'requested_qty' => $requester->requested_qty,
            //     'requester_rig_id' => $requester->rig_id,
            //     'supplier_rig_id' => Auth::user()->rig_id,
            //     'created_at' => $requester->created_at->format('d-m-Y'),
            // ];

            // Mail::to($receiverEmail)->send(new RequestDeclinedMail($mailData));

            session()->flash('success', 'Request Accept successfully.');

            $user = Auth::user();
            $url = route('raised_requests.index');
            $this->notifyAdmins("User '{$user->user_name}' has accepted the request '{$requester->RID}'.", $url);

            return response()->json(['success' => true, 'message' => 'Request Accept successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred while processing the request.'], 500);
        }
    }

    public function declineforRaisedRequest(Request $request)
    {
        try {
            $request->validate([
                'request_id' => 'required|exists:requesters,id',
                'decline_msg' => 'required|string'
            ]);

            $requester = Requester::findOrFail($request->request_id);

            $requester->update(['status' => 5]);

            $sent_to = (Auth::id() == $requester->supplier_id) ? $requester->requester_id : $requester->supplier_id;

            RequestStatus::create([
                'request_id' => $request->request_id,
                'status_id' => 5,
                'decline_msg' => $request->decline_msg,
                'query_msg' => null,
                'supplier_qty' => null,
                'supplier_new_spareable' => null,
                'supplier_used_spareable' => null,
                'user_id' => Auth::id(),
                'rig_id' => Auth::user()->rig_id,
                'sent_to' => $sent_to,
                'sent_from' => Auth::id()
            ]);

            $requesterTable = Requester::where('id', $request->request_id)->first();
            $requesterTable = Requester::where('id', $request->request_id)->first();
            $stock = Stock::where('id', $requesterTable->stock_id)->first();
            $getedp = Edp::where('id', $stock->edp_code)->first();
            $riglocation = RigUser::where('id', Auth::user()->rig_id)->value('location_id');
            $riglocationName = RigUser::where('id', Auth::user()->rig_id)->value('name');

            $message = sprintf(
                'The Request has been self declined by Location <strong>%s</strong> (%s) for material EDP (%s), with description (%s), for a quantity of <strong>%d</strong>. Decline message: (%s).',
                $riglocationName,
                $riglocation,
                $getedp->edp_code ?? 'N/A',
                $stock->description ?? 'N/A',
                $requesterTable->requested_qty,
                $request->decline_msg
            );


            LogsRequestStatus::create([
                'decline_msg' => null,
                'query_msg' => null,
                'supplier_qty' => $requesterTable->requested_qty,
                'supplier_qty' => $requesterTable->requested_qty,
                'supplier_new_spareable' => null,
                'supplier_used_spareable' => null,
                'request_id' => $request->request_id,
                'status_id' => 5,
                'user_id' => Auth::id(),
                'rig_id' => Auth::user()->rig_id,
                'sent_to' => $sent_to,
                'sent_from' => Auth::id(),
                'created_at' => now(),
                'updated_at' => now(),
                'creater_id' => auth()->id(),
                'creater_type' => auth()->user()->user_type,
                'receiver_id' => null,
                'receiver_type' => null,
                'message' => $message,
                'RID' => $requesterTable->RID,
                'available_qty' => $requesterTable->available_qty,
                'requested_qty' => $requesterTable->requested_qty,
                'edp_code' => $getedp->edp_code,
            ]);

            // LogsRequestStatus::create([
            //     'request_id' => $request->request_id,
            //     'status_id' => 5,
            //     'decline_msg' => $request->decline_msg,
            //     'query_msg' => null,
            //     'supplier_qty' => null,
            //     'supplier_new_spareable' => null,
            //     'supplier_used_spareable' => null,
            //     'user_id' => Auth::id(),
            //     'rig_id' => Auth::user()->rig_id,
            //     'sent_to' => $sent_to,
            //     'sent_from' => Auth::id(),
            //     'created_at' => now(),
            //     'updated_at' => now(),
            //     'creater_id' => auth()->id(),
            //     'creater_type' => auth()->user()->user_type,
            //     'receiver_id' => null,
            //     'receiver_type' => null,
            //     'message' => "Request id " . $request->request_id . " has been Decline  by user " . Auth::user()->user_name
            // ]);//

            $requester_user = User::find($requester->requester_id);
            $supplier_user = User::find($requester->supplier_id);

            if (!$requester_user || !$supplier_user) {
                return response()->json(['success' => false, 'message' => 'User not found.'], 404);
            }

            $mailData = [
                'title' => 'Stock Request Declined',
                'request_id' => $request->request_id,
                'stock_id' => $requester->RID,
                'requester_name' => $requester_user->user_name,
                'supplier_name' => $supplier_user->user_name,
                'requested_qty' => $requester->requested_qty,
                'decline_msg' => $request->decline_msg,
                'requester_rig_id' => $requester->rig_id,
                'supplier_rig_id' => Auth::user()->rig_id,
                'created_at' => $requester->created_at->format('d-m-Y'),
            ];

            Stock::where('id', $requester->stock_id)->update(['req_status' => 'inactive']);
            // Send email
            // Mail::to($requester_user->email)->send(new RequestDeclinedMail($mailData));

            $user = Auth::user();
            $url = route('raised_requests.index');
            $this->notifyAdmins("User '{$user->user_name}' has declined the request '{$requester->RID}'.", $url);

            return response()->json(['success' => true, 'message' => 'Request declined successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred while processing the request.'], 500);
        }
    }


    public function getRequestStatusforEdit($requestId)
    {
        $requestStatus = RequestStatus::where('request_id', $requestId)
            ->where('status_id', 6)
            ->latest()
            ->first();

        $requesters = Requester::where('id', $requestId)->first();

        if (!$requestStatus) {
            return response()->json([
                'success' => false,
                'message' => 'Request status not found.'
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'supplier_qty' => $requesters->available_qty,
                'requested_qty' => $requesters->requested_qty,
                'supplier_new_spareable' => $requestStatus->supplier_new_spareable,
                'supplier_used_spareable' => $requestStatus->supplier_used_spareable
            ]
        ]);
    }


    public function updateRequestStatus(Request $request, $requestId)
    {
        $requestStatus = RequestStatus::where('request_id', $requestId)
            ->where('status_id', 6)
            ->latest()
            ->first();

        if (!$requestStatus) {
            return response()->json([
                'success' => false,
                'message' => 'Request status not found.'
            ]);
        }

        $requestStatus->supplier_new_spareable = $request->new_spareable;
        $requestStatus->supplier_used_spareable = $request->used_spareable;
        $requestStatus->supplier_qty = $request->new_spareable + $request->used_spareable;
        $requestStatus->save();

        session()->flash('success', 'Request status updated successfully.');

        $user = Auth::user();
        $requester = Requester::find($requestStatus->request_id);
        $url = route('raised_requests.index');
        $this->notifyAdmins("User '{$user->user_name}' has changed the for the request '{$requester->RID}'.", $url);

        return response()->json(['success' => true]);
    }


    private function notifyAdmins($message, $url = null)
    {
        $user = Auth::user();

        $admins = User::where('user_type', 'admin')->get();

        foreach ($admins as $admin) {
            $notification = Notification::create([
                'type' => NewRequestNotification::class,
                'notifiable_type' => User::class,
                'notifiable_id' => $admin->id,
                'user_id' => $user->id,
                'data' => json_encode([
                    'message' => $message,
                    'url' => $url ?? route('dashboard'),
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Notify all rig users of the same rig
            $rigUsers = User::where('rig_id', $user->rig_id)
                ->where('user_type', 'user')
                ->get();

            foreach ($rigUsers as $rigUser) {
                DB::table('notification_user')->insert([
                    'user_id' => $rigUser->id,
                    'notification_id' => $notification->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Also notify the admin
            DB::table('notification_user')->insert([
                'user_id' => $admin->id,
                'notification_id' => $notification->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function incomingPenddingRequest(Request $request)
    {
        $moduleName = "Pendding Request Stock List";
        $rig_id = Auth::user()->rig_id;
        $datarig = User::where('user_type', '!=', 'admin')
            ->where('rig_id', $rig_id)
            ->pluck('id')
            ->toArray();

        $data = Requester::select(
            'rig_users.name as Location_Name',
            'rig_users.location_id',
            'requesters.*',
            'mst_status.status_name',
            'stocks.id as stock_id',
            'stocks.description',
            'edps.edp_code',
        )->join('rig_users', 'requesters.requester_rig_id', '=', 'rig_users.id')
            ->join('stocks', 'requesters.stock_id', '=', 'stocks.id')
            ->join('edps', 'stocks.edp_code', '=', 'edps.id')
            ->leftJoin('mst_status', 'requesters.status', '=', 'mst_status.id')
            ->where('requesters.supplier_rig_id', $rig_id)
            ->whereIn('requesters.status', [1, 2, 4, 6])
            ->orderBy('requesters.created_at', 'desc')
            ->get();

        $EDP_Code_ID = Requester::join('stocks', 'requesters.stock_id', '=', 'stocks.id')
            ->join('edps', 'stocks.edp_code', '=', 'edps.id')
            ->where('requesters.supplier_rig_id', $rig_id)
            ->where('requesters.status', 1)
            ->select('edps.edp_code')
            ->get();
        return view('request_stock.list_request_stock', compact('data', 'moduleName', 'datarig', 'EDP_Code_ID'));
    }

    public function raisedPenddingRequest(Request $request)
    {
        $moduleName = "Query Request Stock List";
        $rig_id = Auth::user()->rig_id;
        $datarig = User::where('user_type', '!=', 'admin')
            ->where('rig_id', $rig_id)
            ->pluck('id')
            ->toArray();

        /*   $data = Requester::select(
            'rig_users.name as Location_Name',
            'rig_users.location_id',
            'requesters.*',
            'mst_status.status_name',
            'stocks.id as stock_id',
            'stocks.description',
            'edps.edp_code',)->join('rig_users', 'requesters.requester_rig_id', '=', 'rig_users.id')
            ->join('stocks', 'requesters.stock_id', '=', 'stocks.id')
            ->join('edps', 'stocks.edp_code', '=', 'edps.id')
            ->leftJoin('mst_status', 'requesters.status', '=', 'mst_status.id')
            ->where('requesters.requester_rig_id', $rig_id)
            ->whereIn('requesters.status', [1,2,4,6])
            ->orderBy('requesters.created_at', 'desc')
            ->get();
*/

        $data = Requester::leftJoin('stocks', 'requesters.stock_id', '=', 'stocks.id')
            ->leftJoin('mst_status', 'requesters.status', '=', 'mst_status.id')
            ->join('rig_users', 'requesters.supplier_rig_id', '=', 'rig_users.id')
            ->join('edps', 'stocks.edp_code', '=', 'edps.id')
            ->where('requesters.requester_rig_id', $rig_id)
            ->whereIn('requesters.status', [1, 2, 4, 6])
            ->select('requesters.*', 'stocks.location_name', 'stocks.location_id', 'mst_status.status_name', 'edps.edp_code', 'edps.description')
            ->orderBy('requesters.created_at', 'desc')
            ->get();

        $edps = Requester::join('stocks', 'requesters.stock_id', '=', 'stocks.id')
            ->join('edps', 'stocks.edp_code', '=', 'edps.id')
            ->where('requesters.supplier_rig_id', $rig_id)
            ->where('requesters.status', 2)
            ->select('edps.edp_code')
            ->get();

        $status_type = 'raisedPenddingRequest.get';
        return view('request_stock.supplier_request', compact('data', 'moduleName', 'datarig', 'edps', 'status_type'));
    }

    public function CommanRequestStockFilter(Request $request)
    {
        $rig_id = Auth::user()->rig_id;
        if ($request->ajax()) {
            $data = Requester::query();
            $data->select(
                'rig_users.name as Location_Name',
                'rig_users.location_id',
                'requesters.*',
                'mst_status.status_name',
                'stocks.description',
                'stocks.created_at as stock_created_at',
                'edps.edp_code'
            )->join('rig_users', 'requesters.supplier_rig_id', '=', 'rig_users.id')
                ->leftJoin('mst_status', 'requesters.status', '=', 'mst_status.id')
                ->join('stocks', 'requesters.stock_id', '=', 'stocks.id')
                ->join('edps', 'stocks.edp_code', '=', 'edps.id')
                ->when($request->edp_code, function ($query, $edp_code) {
                    return $query->where('edps.edp_code', $edp_code);
                })
                ->when($request->Description, function ($query, $description) {
                    return $query->where('stocks.description', 'LIKE', "%{$description}%");
                })
                ->when($request->form_date, function ($query) use ($request) {
                    return $query->whereDate('stocks.created_at', '>=', Carbon::parse($request->form_date)->startOfDay());
                })
                ->when($request->to_date, function ($query) use ($request) {
                    return $query->whereDate('stocks.created_at', '<=', Carbon::parse($request->to_date)->endOfDay());
                })
                ->where('requesters.supplier_rig_id', $rig_id);

            if ($request->type == 'pendding_request.get') {
                $data->where('requesters.status', 1);
            }
            if ($request->type == 'query_request.get') {
                $data->where('requesters.status', 2);
            }

            $result = $data->orderBy('requesters.created_at', 'desc')->get();

            return response()->json(['data' => $result]);
        }
        return view('request_stock.comman_request_view', compact('data'));
    }

    public function fetchIncommingCount(Request $request)
    {
        $rig_id = Auth::user()->rig_id;
        $data = Requester::selectRaw('COUNT(requesters.id) as incoming_pending')
            ->where('requesters.supplier_rig_id', $rig_id)
            ->whereIn('requesters.status', [1, 2, 4, 6])->first();
        return response()->json(['data' => $data]);
    }
    public function fetchRaisedCount(Request $request)
    {
        $rig_id = Auth::user()->rig_id;
        $data = Requester::selectRaw('COUNT(requesters.id) as raised_pending')
            ->where('requesters.requester_rig_id', $rig_id)
            ->whereIn('requesters.status', [1, 2, 4, 6])->first();
        return response()->json(['data' => $data]);
    }
}
