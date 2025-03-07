<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequestStock;
use Illuminate\Support\Facades\DB;
use App\Models\Stock;

class RequestStockController extends Controller
{
   

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
