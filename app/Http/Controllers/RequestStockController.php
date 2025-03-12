<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequestStock;
use Illuminate\Support\Facades\DB;
use App\Models\Stock;
use Carbon\Carbon;

class RequestStockController extends Controller
{
   

    public function RequestStockList(Request $request){
    
        $data = RequestStock::get();
        $moduleName = "Request Stocks";
        return view('request_stock.list_request_stock',compact('data', 'moduleName'));
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
            'user_name' => 'required',
            'user_id' => 'required',
            'section' => 'required',
            'stock_item' => 'required',
            'stock_code' => 'required',
            'request_quantity' => 'required',
            'qty' => 'required',
            'measurement' => 'required',
            'new_spareable' => 'required',
            'used_spareable' => 'required',
            'remarks' => 'required',
            'supplier_location_name' => 'required',
        ]);

        $requeststock = new RequestStock;
        $requeststock->user_name = $request->user_name;
        $requeststock->user_id = $request->user_id;
        $requeststock->section = $request->section;
        $requeststock->stock_item = $request->stock_item;
        $requeststock->stock_code = $request->stock_code;
        $requeststock->request_quantity = $request->request_quantity;
        $requeststock->qty = $request->qty;
        $requeststock->measurement = $request->measurement;
        $requeststock->new_spareable = $request->new_spareable;
        $requeststock->used_spareable = $request->used_spareable;
        $requeststock->remarks = $request->remarks;
        $requeststock->save();

        // $data = RequestStock::insert($insert_request_data);
        return redirect()->route('request_stock_list');
        
    }

    public function RequestStockViewPost(Request $request){
       $id =  $request->data;
       $data = RequestStock::where('req_id',$id)->get();
            return response()->json([
                'data' =>$data
            ]);
    }
}
