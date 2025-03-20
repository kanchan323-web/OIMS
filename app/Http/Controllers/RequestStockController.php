<?php

namespace App\Http\Controllers;

use App\Mail\RequestAcceptedMail;
use App\Mail\RequestDeclinedMail;
use App\Mail\RequestQueryMail;
use App\Models\RequestStatus;
use Illuminate\Http\Request;
use App\Models\RequestStock;
use Illuminate\Support\Facades\DB;
use App\Models\Stock;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Requester;
use Illuminate\Support\Facades\Mail;
use App\Mail\requestor_stock_mail;
use App\Mail\supplier_stock_mail;


use Illuminate\Support\Facades\Session;


class RequestStockController extends Controller
{


    public function RequestStockList(Request $request)
    {

        $rig_id = Auth::user()->rig_id;
        $datarig = User::where('user_type', '!=', 'admin')
            ->where('rig_id', $rig_id)
            ->pluck('id')
            ->toArray();

        $data = RequestStock::get();
        $moduleName = "Request Stocks List";
        return view('request_stock.list_request_stock', compact('data', 'moduleName', 'datarig'));
    }

    public function GeneratedRequest(Request $request)
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

                // $stockData = Stock::select('edp_code')->distinct()->get();
            //   $stockData = Stock::join('edps', 'stocks.edp_code', '=', 'edps.id')
            //        ->select('stocks.*', 'edps.edp_code AS EDP_Code')
            //        ->distinct()
            //        ->get();

                $stockData = Stock::join('edps', 'stocks.edp_code', '=', 'edps.id')
                ->select('stocks.*', 'edps.edp_code AS EDP_Code')
                ->where('rig_id', '!=', $rig_id)
                ->where('req_status', 'inactive')
                ->get();

                // dd($stockData);


            $data = Stock::select('stocks.*','rig_users.name','edps.edp_code','edps.category','edps.description','edps.section')
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



        $request->validate([
            'available_qty' => 'required|numeric',
            'requested_qty' => 'required|numeric',
            'stock_id' => 'required',
            'requester_id' => 'required',
            'requester_rig_id' => 'required',
            'supplier_id' => 'required',
            'supplier_rig_id' => 'required',
        ]);
        try {

            $lastRequest = Requester::latest('id')->first();
            $nextId = $lastRequest ? $lastRequest->id + 1 : 1;
            $RID = 'RS' . str_pad($nextId, 8, '0', STR_PAD_LEFT);

            $SendRequest = Requester::insert([
                'available_qty' => $request->available_qty,
                'requested_qty' => $request->requested_qty,
                'stock_id' => $request->stock_id,
                'requester_id' => $request->requester_id,
                'requester_rig_id' => $request->requester_rig_id,
                'supplier_id' => $request->supplier_id,
                'supplier_rig_id' => $request->supplier_location_id,
                'RID' => $RID,
                'created_at' => now(),
                'updated_at' => now(),
            ]);


            $supplierData = User::where('id', $request->supplier_id)->first();

            $mailDataSupplier = [
                'title' => 'Stock Request from ONGC',
                'supplier_name' => $supplierData->user_name,
                'requester_name' => Auth::user()->user_name,
                'available_qty' => $request->available_qty,
                'requested_qty' => $request->requested_qty,
                'stock_id' => $request->stock_id,
                'requester_rig_id' => $request->requester_rig_id,
                'supplier_rig_id' => $request->supplier_rig_id,
                'created_at' => Carbon::now()->setTimezone('Asia/Kolkata')->format('d M Y, h:i A'),
            ];

            $mailDataRequester = [
                'title' => 'Stock Request Confirmation - ONGC',
                'supplier_name' => $supplierData->user_name,
                'requester_name' => Auth::user()->user_name,
                'available_qty' => $request->available_qty,
                'requested_qty' => $request->requested_qty,
                'stock_id' => $request->stock_id,
                'requester_rig_id' => $request->requester_rig_id,
                'supplier_rig_id' => $request->supplier_rig_id,
                'created_at' => Carbon::now()->setTimezone('Asia/Kolkata')->format('d M Y, h:i A'),
            ];



            try {
                if ($supplierData) {
                    $supplierEmail = $supplierData->email;

                    Mail::to(Auth::user()->email)->send(new requestor_stock_mail($mailDataRequester));
                    Mail::to($supplierEmail)->send(new supplier_stock_mail($mailDataSupplier));
                } else {
                    Session::flash('error', 'Supplier not found');
                    return redirect()->route('stock_list.request');
                }
            } catch (\Exception $e) {
                return redirect()->route('stock_list.request')
                    ->withErrors(['email_error' => 'Stock request sent, but email failed: ' . $e->getMessage()]);
            }



            if ($SendRequest) {
                Session::flash('success', 'Request of Stock Sent successfully!');
                return redirect()->route('stock_list.request');
            }
        } catch (\Exception $e) {
            return redirect()->route('stock_list.request')->withErrors('An error occurred: ' . $e->getMessage());
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
                'edps.edp_code as edp_code',
                'mst_status.status_name',
                DB::raw("DATE_FORMAT(requesters.created_at, '%d-%m-%Y') as formatted_created_at")
            )
            ->first();

        if (!$requestStock) {
            return response()->json(['success' => false, 'message' => 'Request not found']);
        }

        return response()->json(['success' => true, 'data' => $requestStock]);
    }




 




    public function IncomingRequestStockList(Request $request)
    {

        $rig_id = Auth::user()->rig_id;
        $datarig = User::where('user_type', '!=', 'admin')
            ->where('rig_id', $rig_id)
            ->pluck('id')
            ->toArray();

        // $data = RequestStock::get();
        $data = Requester::select('rig_users.name', 'rig_users.location_id', 'requesters.*')
            ->join('rig_users', 'requesters.supplier_rig_id', '=', 'rig_users.id')
            ->where('supplier_rig_id', $rig_id)
            ->orderBy('requesters.created_at', 'desc')->get();

        //print_r($data);
        //die;

        $moduleName = "Incoming Request List";
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
            ]);

            $requester_user = User::find($requester->requester_id);
            $supplier_user = User::find($requester->supplier_id);

            if (!$requester_user || !$supplier_user) {
                return response()->json(['success' => false, 'message' => 'User not found.'], 404);
            }

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

            Mail::to($receiverEmail)->send(new RequestAcceptedMail($mailData));

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

            $requester->update(['status' => 3]);

            RequestStatus::create([
                'request_id' => $request->request_id,
                'status_id' => 3,
                'decline_msg' => $request->decline_msg,
                'query_msg' => null,
                'supplier_qty' => null,
                'supplier_new_spareable' => null,
                'supplier_used_spareable' => null,
                'user_id' => Auth::id(),
                'rig_id' => Auth::user()->rig_id,
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

            Mail::to($receiverEmail)->send(new RequestDeclinedMail($mailData));

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

            Mail::to($receiverEmail)->send(new RequestQueryMail($mailData));

            session()->flash('success', 'Query raised successfully.');

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

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 400);
    }
}
