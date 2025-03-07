<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequestStock;
use Illuminate\Support\Facades\DB;
use App\Models\Stock;

class RequestStockController extends Controller
{
    public function stock_list(Request $request){
        // $todayDate = Carbon::now()->format('Y-m-d');
        // return $todayDate;
    
        // $data =  DB::table('stocks')->get();
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
        
    // return $data;
    
        return view('user.stock.list_stock',compact('data'));
    }

    public function RequestStockList(Request $request){
    
        $data = RequestStock::get();
        return view('request_stock.list_request_stock',compact('data'));
    }

    public function RequestStockAdd(Request $request){
        return view('request_stock.add_request_stock');
    }
    public function RequestStockAddPost(Request $request){

        $insert_request_data = $request->validate([
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

        $data = RequestStock::insert($insert_request_data);
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
