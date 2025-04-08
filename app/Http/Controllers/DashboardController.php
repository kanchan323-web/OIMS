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
                  'usedPercent'
            ));
        } else {
            return redirect()->route('user.login');
        }
    }

    public function test()
    {

    }
}