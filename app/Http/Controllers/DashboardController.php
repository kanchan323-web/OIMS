<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Stock;
use App\Models\Requester;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $rig_id = Auth::user()->rig_id;
            $ReceivedStock = Requester::where('supplier_rig_id', $rig_id)
                                        ->where('status', 3)
                                        ->sum('requested_qty');
                                            

            //   Top-Card-data
            $countIncomingRequest = Requester::where('supplier_rig_id', $rig_id)->count();
    
            $PendingIncomingRequest = Requester::leftJoin('mst_status', 'requesters.status', '=', 'mst_status.id')
                ->where('requesters.supplier_rig_id', $rig_id)
                ->where('mst_status.status_name', 'Pending')
                ->count();
            $RaisedRequests = Requester::where('requester_rig_id', $rig_id)->count();
            $RaisedRequestsRequests = Requester::leftJoin('mst_status', 'requesters.status', '=', 'mst_status.id')
                ->where('requesters.requester_rig_id', $rig_id)
                ->where('mst_status.status_name', 'Pending')
                ->count();
            //   Top-Card-data

    
            // Overview Of Stock Comparison   
            $totalStock = Stock::where('rig_id', $rig_id)->count();
            // Overview Of Stock Comparison 

            // MIT Status
            $Total_Incoming = Requester::select(
                'requesters.*',
                )->join('rig_users', 'requesters.requester_rig_id', '=', 'rig_users.id')
                ->join('stocks', 'requesters.stock_id', '=', 'stocks.id')
                ->join('edps', 'stocks.edp_code', '=', 'edps.id')
                ->leftJoin('mst_status', 'requesters.status', '=', 'mst_status.id')
                ->where('requesters.supplier_rig_id', $rig_id)
                ->with('requestStatuses')
                
                ->count('status');
            // MIT Status
           
            // MIT Status
            $mitstatus = Requester::select(
                'requesters.*',
                )->join('rig_users', 'requesters.requester_rig_id', '=', 'rig_users.id')
                ->join('stocks', 'requesters.stock_id', '=', 'stocks.id')
                ->join('edps', 'stocks.edp_code', '=', 'edps.id')
                ->leftJoin('mst_status', 'requesters.status', '=', 'mst_status.id')
                ->where('requesters.supplier_rig_id', $rig_id)
                ->with('requestStatuses')
                ->where('requesters.status',6)
                ->count();
            // MIT Status

            // Received Status
            $Received_Status = Requester::select(
                'requesters.*',
                )->join('rig_users', 'requesters.requester_rig_id', '=', 'rig_users.id')
                ->join('stocks', 'requesters.stock_id', '=', 'stocks.id')
                ->join('edps', 'stocks.edp_code', '=', 'edps.id')
                ->leftJoin('mst_status', 'requesters.status', '=', 'mst_status.id')
                ->where('requesters.supplier_rig_id', $rig_id)
                ->with('requestStatuses')
                ->where('requesters.status',3)
                ->count();
            // Received Status

            // Pending Status
            $Pending_Status = Requester::select(
                'requesters.*',
                )->join('rig_users', 'requesters.requester_rig_id', '=', 'rig_users.id')
                ->join('stocks', 'requesters.stock_id', '=', 'stocks.id')
                ->join('edps', 'stocks.edp_code', '=', 'edps.id')
                ->leftJoin('mst_status', 'requesters.status', '=', 'mst_status.id')
                ->where('requesters.supplier_rig_id', $rig_id)
                ->with('requestStatuses')
                ->where('requesters.status',1)
                ->count();
            // Pending Status

            // Pending Status
            $Decline_Status = Requester::select(
                'requesters.*',
                )->join('rig_users', 'requesters.requester_rig_id', '=', 'rig_users.id')
                ->join('stocks', 'requesters.stock_id', '=', 'stocks.id')
                ->join('edps', 'stocks.edp_code', '=', 'edps.id')
                ->leftJoin('mst_status', 'requesters.status', '=', 'mst_status.id')
                ->where('requesters.supplier_rig_id', $rig_id)
                ->with('requestStatuses')
                ->where('requesters.status',5)
                ->count();
            // Pending Status

            // Query Status
            $Query_Status = Requester::select(
                'requesters.*',
                )->join('rig_users', 'requesters.requester_rig_id', '=', 'rig_users.id')
                ->join('stocks', 'requesters.stock_id', '=', 'stocks.id')
                ->join('edps', 'stocks.edp_code', '=', 'edps.id')
                ->leftJoin('mst_status', 'requesters.status', '=', 'mst_status.id')
                ->where('requesters.supplier_rig_id', $rig_id)
                ->with('requestStatuses')
                ->where('requesters.status',2)
                ->count();
            // Query Status

            // Approve Status
            $Approve_Status = Requester::select(
                'requesters.*',
                )->join('rig_users', 'requesters.requester_rig_id', '=', 'rig_users.id')
                ->join('stocks', 'requesters.stock_id', '=', 'stocks.id')
                ->join('edps', 'stocks.edp_code', '=', 'edps.id')
                ->leftJoin('mst_status', 'requesters.status', '=', 'mst_status.id')
                ->where('requesters.supplier_rig_id', $rig_id)
                ->with('requestStatuses')
                ->where('requesters.status',4)
                ->count();
            // Approve Status


            // Raised MIT Status
            $mit_raised =  Requester::leftJoin('stocks', 'requesters.stock_id', '=', 'stocks.id')
            ->leftJoin('mst_status', 'requesters.status', '=', 'mst_status.id')
            ->join('rig_users', 'requesters.supplier_rig_id', '=', 'rig_users.id')
            ->join('edps', 'stocks.edp_code', '=', 'edps.id')
            ->where('requesters.requester_rig_id', $rig_id)
            ->where('requesters.status',6)
            ->count();
            // Raised MIT Status

            // Raised Received Status
            $Received_raised =  Requester::leftJoin('stocks', 'requesters.stock_id', '=', 'stocks.id')
            ->leftJoin('mst_status', 'requesters.status', '=', 'mst_status.id')
            ->join('rig_users', 'requesters.supplier_rig_id', '=', 'rig_users.id')
            ->join('edps', 'stocks.edp_code', '=', 'edps.id')
            ->where('requesters.requester_rig_id', $rig_id)
            ->where('requesters.status',3)
            ->count();
            // Raised Received Status

            // Raised Pending Status
            $Pending_raised =  Requester::leftJoin('stocks', 'requesters.stock_id', '=', 'stocks.id')
            ->leftJoin('mst_status', 'requesters.status', '=', 'mst_status.id')
            ->join('rig_users', 'requesters.supplier_rig_id', '=', 'rig_users.id')
            ->join('edps', 'stocks.edp_code', '=', 'edps.id')
            ->where('requesters.requester_rig_id', $rig_id)
            ->where('requesters.status',1)
            ->count();
            // Raised Pending Status


            // Raised Decline Status
            $Decline_raised =  Requester::leftJoin('stocks', 'requesters.stock_id', '=', 'stocks.id')
            ->leftJoin('mst_status', 'requesters.status', '=', 'mst_status.id')
            ->join('rig_users', 'requesters.supplier_rig_id', '=', 'rig_users.id')
            ->join('edps', 'stocks.edp_code', '=', 'edps.id')
            ->where('requesters.requester_rig_id', $rig_id)
            ->where('requesters.status',5)
            ->count();
            // Raised Decline Status


            // Raised Approve Status
            $Approve_raised =  Requester::leftJoin('stocks', 'requesters.stock_id', '=', 'stocks.id')
            ->leftJoin('mst_status', 'requesters.status', '=', 'mst_status.id')
            ->join('rig_users', 'requesters.supplier_rig_id', '=', 'rig_users.id')
            ->join('edps', 'stocks.edp_code', '=', 'edps.id')
            ->where('requesters.requester_rig_id', $rig_id)
            ->where('requesters.status',4)
            ->count();
            // Raised Approve Status


            // Raised Query Status
            $Query_raised =  Requester::leftJoin('stocks', 'requesters.stock_id', '=', 'stocks.id')
            ->leftJoin('mst_status', 'requesters.status', '=', 'mst_status.id')
            ->join('rig_users', 'requesters.supplier_rig_id', '=', 'rig_users.id')
            ->join('edps', 'stocks.edp_code', '=', 'edps.id')
            ->where('requesters.requester_rig_id', $rig_id)
            ->where('requesters.status',2)
            ->count();
            // Raised Query Status


            // Raised Total 
            $Total_Raised =  Requester::leftJoin('stocks', 'requesters.stock_id', '=', 'stocks.id')
            ->leftJoin('mst_status', 'requesters.status', '=', 'mst_status.id')
            ->join('rig_users', 'requesters.supplier_rig_id', '=', 'rig_users.id')
            ->join('edps', 'stocks.edp_code', '=', 'edps.id')
            ->where('requesters.requester_rig_id', $rig_id)
            ->count('status');
            // Raised Total
           
            
            // Stock category data
            $categoryCounts = Stock::where('rig_id', $rig_id)
                ->whereIn('category', ['Spares', 'Stores', 'Capital Item'])
                ->groupBy('category')
                ->select('category', DB::raw('COUNT(*) as Category_count'))
                ->pluck('Category_count', 'category');


            $currentStock = Stock::where('rig_id', $rig_id)
                ->whereIn('category', ['Spares', 'Stores', 'Capital Item'])
                ->selectRaw('category, SUM(qty) as total_quantity')
                ->groupBy('category')
                ->get()
                ->pluck('total_quantity', 'category');

            // Requests data by category
            $incomingStockCounts = Requester::join('stocks', 'requesters.stock_id', '=', 'stocks.id')
                ->where('requesters.supplier_rig_id', $rig_id)
                ->groupBy('stocks.category')
                ->select('stocks.category', DB::raw('COUNT(requesters.id) as total'))
                ->pluck('total', 'category');

            $raisedStockCounts = Requester::join('stocks', 'requesters.stock_id', '=', 'stocks.id')
                ->where('requesters.requester_rig_id', $rig_id)
                ->groupBy('stocks.category')
                ->select('stocks.category', DB::raw('COUNT(requesters.id) as total'))
                ->pluck('total', 'category');


            // In your controller:
            $weeklyStockData = [];
            $monthlyStockData = [];
            $yearlyStockData = [];

            $categories = ['Spares', 'Stores', 'Capital Item'];

            foreach ($categories as $category) {
                // Weekly data
                $weeklyStockData[$category] = Stock::where('rig_id', $rig_id)
                    ->where('category', $category)
                    ->selectRaw('YEAR(created_at) as year, 
                    WEEK(created_at) as week, 
                    MONTH(created_at) as month, 
                    SUM(qty) as quantity')
                    ->groupBy('year', 'week', 'month')
                    ->orderBy('year')
                    ->orderBy('week')
                    ->get()
                    ->map(function ($item) {
                        $monthName = \Carbon\Carbon::create()->month($item->month)->format('M');
                        return [
                            'name' => "Week {$item->week}, {$monthName} {$item->year}",  // Format: "Week 23, Jun 2023"
                            'y' => (int) $item->quantity,
                            'week' => $item->week,
                            'month' => $item->month,
                            'monthName' => $monthName,
                            'year' => $item->year
                        ];
                    })->toArray();

                // Monthly data
                $monthlyStockData[$category] = Stock::where('rig_id', $rig_id)
                    ->where('category', $category)
                    ->selectRaw("
                    YEAR(created_at) as year,
                    MONTH(created_at) as month,
                    DATE_FORMAT(created_at, '%Y-%m') as period,
                    SUM(qty) as quantity
                ")
                    ->groupBy('year', 'month', 'period')
                    ->orderBy('year', 'asc')
                    ->orderBy('month', 'asc')
                    ->get()
                    ->map(function ($item) {
                        $date = \Carbon\Carbon::createFromFormat('!m', $item->month)->month($item->month)->year($item->year);

                        return [
                            'period' => $item->period,  // '2023-01'
                            'name' => $date->format('F Y'),  // 'January 2023'
                            'shortName' => $date->format('M Y'),  // 'Jan 2023'
                            'y' => (int) $item->quantity,
                            'month' => $item->month,
                            'year' => $item->year,
                            'sortKey' => $item->year * 100 + $item->month  // Creates a sortable numeric key (202301)
                        ];
                    })
                    ->sortBy('sortKey')  // Extra sorting guarantee
                    ->values()  // Reset array keys after sorting
                    ->toArray();

                // Yearly data
                $yearlyStockData[$category] = Stock::where('rig_id', $rig_id)
                    ->where('category', $category)
                    ->selectRaw("YEAR(created_at) as period, SUM(qty) as quantity")
                    ->groupBy('period')
                    ->orderBy('period')
                    ->get()
                    ->map(function ($item) {
                        return [(string) $item->period, (int) $item->quantity];
                    })->toArray();
            }

            $countUsedAndNewStock = Stock::where('rig_id', $rig_id)->select('new_spareable', 'used_spareable')->get();

            $newStock = $countUsedAndNewStock->sum('new_spareable');
            $usedStock = $countUsedAndNewStock->sum('used_spareable');
            if($newStock ||$usedStock != 0){
                $newPercent = round(($newStock / ($newStock + $usedStock)) * 100, 1);
                $usedPercent = round(($usedStock / ($newStock + $usedStock)) * 100, 1);
            }else{
                $newPercent = 0;
                $usedPercent = 0; 
            }
          



            return view('user.dashboard', compact(
                'countIncomingRequest',
                'PendingIncomingRequest',
                'RaisedRequests',
                'RaisedRequestsRequests',
                'currentStock',
                'categoryCounts',
                'totalStock',
                'incomingStockCounts',
                'raisedStockCounts',
                'weeklyStockData',
                'monthlyStockData',
                'yearlyStockData',
                  'newStock',
                  'usedStock',
                  'newPercent', 
                  'usedPercent',
                  'ReceivedStock',
                  'mitstatus',
                  'Received_Status',
                  'Pending_Status',
                  'Decline_Status',
                  'Approve_Status',
                  'Query_Status',
                  'mit_raised',
                'Received_raised',
                'Pending_raised',
                'Decline_raised',
                'Approve_raised',
                'Query_raised',
                'Total_Incoming',
                'Total_Raised',
                  
            ));
        } else {
            return redirect()->route('user.login');
        }
    }

    public function test()
    {

    }
}