<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequestStock;
use Illuminate\Support\Facades\DB;
use App\Models\Stock;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Requester;

use Illuminate\Support\Facades\Session;


class RequestStockController extends Controller
{


    public function RequestStockList(Request $request){

        $rig_id = Auth::user()->rig_id;
        $datarig = User::where('user_type', '!=', 'admin')
                    ->where('rig_id',$rig_id)
                    ->pluck('id')
                    ->toArray();


        $data = RequestStock::get();
        $moduleName = "Request Stocks List";
        return view('request_stock.list_request_stock',compact('data', 'moduleName','datarig'));
    }

    public function GeneratedRequest(Request $request){

        $rig_id = Auth::user()->rig_id;
        $datarig = User::where('user_type', '!=', 'admin')
                    ->where('rig_id',$rig_id)
                    ->pluck('id')
                    ->toArray();

        $data = RequestStock::get();
        $moduleName = "Request Stocks List";
        return view('request_stock.generated',compact('data', 'moduleName','datarig'));
    }

    public function request_stock_filter(Request $request){



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

        $moduleName = "Request Stocks filter";
        return view('request_stock.list_request_stock',compact('data', 'moduleName'));

    }

    public function RequestStockAdd(Request $request){
        $moduleName = "Add Stock";
        return view('request_stock.add_request_stock', compact('moduleName'));
    }


    public function RequestStockAddPost(Request $request){


        $request->validate([
            'available_qty' => 'required|numeric',
            'requested_qty' => 'required|numeric',
            'stock_id' => 'required',
            'requester_id' => 'required',
            'requester_rig_id' => 'required',
            'supplier_id' => 'required',
            'supplier_rig_id' => 'required',
        ]);

      $SendRequest =   DB::table('requesters')->insert([
            'available_qty' => $request->available_qty,
            'requested_qty' => $request->requested_qty,
            'stock_id' => $request->stock_id,
            'requester_id' => $request->requester_id,
            'requester_rig_id' => $request->requester_rig_id,
            'supplier_id' => $request->supplier_id,
            'supplier_rig_id' => $request->supplier_location_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if($SendRequest){
                Session::flash('success', 'Request of Stock Sent successfully!');
                return redirect()->route('stock_list.request');
        }

    }

    public function RequestStockViewPost(Request $request){
       $id =  $request->data;
       $data = RequestStock::where('id',$id)->get();
            return response()->json([
                'data' =>$data
            ]);
    }



    public function StockList(Request $request)
    {
        $rig_id = Auth::user()->rig_id;
        $datarig = User::where('user_type', '!=', 'admin')
            ->where('rig_id', $rig_id)
            ->pluck('id')
            ->toArray();

        $stockData = Stock::select('edp_code')->distinct()->get();
        $data = Stock::all();
        // $data = Stock::where('user_id', '!=', Auth::user()->id)
        // ->pluck('id')
        // ->toArray();
        // $data = Stock::where('user_id', '!=', Auth::user()->id)->get();

        // dd($data);
        $moduleName = "Stock";
        return view('request_stock.stock_list_request', compact('data', 'moduleName', 'stockData', 'datarig'));
    }

    public function get_stockrequest_data(Request $request){
        return hello;
    }

    public function IncomingRequestStockList(Request $request){

        $rig_id = Auth::user()->rig_id;
        $datarig = User::where('user_type', '!=', 'admin')
                    ->where('rig_id',$rig_id)
                    ->pluck('id')
                    ->toArray();

       // $data = RequestStock::get();
        $data = Requester::select('rig_users.name', 'rig_users.location_id','requesters.*')
        ->join('rig_users', 'requesters.supplier_rig_id', '=', 'rig_users.id')
        ->where('supplier_rig_id', $rig_id)
        ->orderBy('requesters.created_at', 'desc')->get();

        //print_r($data);
        //die;

        $moduleName = "Incoming Request List";
        return view('request_stock.list_request_stock',compact('data', 'moduleName','datarig'));
    }
}
