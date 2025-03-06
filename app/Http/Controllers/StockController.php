<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Rules\ReCaptcha;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Stock;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use App\Models\RequestStock;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{

    public function add_stock(){
         return view('user.stock.add_stock');
    }

    public function stockSubmit(Request $request)
    {
        $insert_data = $request->validate([
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

        // $id = Auth::id();
        // print_r($id);
        // die;
        $stock = new Stock;
        $stock->location_id = $request->location_id;
        $stock->location_name = $request->location_id;
        $stock->edp_code = $request->location_id;
        $stock->category = $request->location_id;
        $stock->description = $request->location_id;
        $stock->section = $request->location_id;
        $stock->qty = $request->location_id;
        $stock->measurement = $request->location_id;
        $stock->new_spareable = $request->location_id;
        $stock->used_spareable = $request->location_id;
        $stock->remarks = $request->location_id;
        $stock->user_id = Auth::id();
        $stock->save();
        Session::flash('success', 'Stock submitted successfully!');
        return redirect()->route('stock_list');
    }

    // public function stock_list(){
    //     return view('user.stock.list_stock');
    // }

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


    public function showImportForm()
    {
        return view('user.stock.import_bulk_stock');
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
            
            foreach (array_slice($rows, 1) as $row) {

                Stock::create([
                    'location_id'   => $row[0] ?? null,
                    'location_name' => $row[1] ?? null,
                    'edp_code'      => $row[2] ?? null,
                    'description'   => $row[3] ?? null,
                    'section'       => $row[4] ?? null,
                    'category'      => $row[5] ?? null,
                    'qty'           => $row[6] ?? 0,
                    'new_spareable' => $row[7] ?? 0,
                    'used_spareable' => $row[8] ?? 0,
                    'measurement'   => $row[9] ?? null,
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
    
    
    public function stock_list_view(Request $request){
        $id = $request->data;

      $viewdata =   Stock::where('id',$id)->get()->first();

        return response()->json([
            'viewdata' => $viewdata 
        ]
        );
    }
    public function EditStock(Request $request ,$id){
      
        $editData = Stock::where('id',$id)->get()->first();

        // dd($editData);
        return view('user.stock.edit_stock',['editData'=>$editData]);
    }

    public function UpdateStock(Request $request){
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
        $UData = Stock::where('id',$dataid)->update($update_data);
       return redirect()->route('stock_list');
    }

    public function DeleteStock(Request $request){
        $deleteId = $request->delete_id;
        $UData = Stock::where('id',$deleteId)->delete();
        return redirect()->route('stock_list');
    }

    public function RequestStockList(Request $request){
    
        // $data = RequestStock::when($request->category, function ($query, $category) {
        //     return $query->where('category', $category);
        // })
        // ->when($request->location_name, function ($query, $location_name) {
        //     return $query->where('location_name', 'like', "%{$location_name}%");
        // })
        // ->when($request->form_date && $request->to_date, function ($query) use ($request) {
        //     return $query->whereBetween('created_at', [$request->form_date, $request->to_date]);
        // })
        // ->get();
        // Stock
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
