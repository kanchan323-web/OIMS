<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Stock;
use App\Models\Requester;
use App\Models\Edp;
use App\Models\RigUser;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $rig_id = Auth::user()->rig_id;


        $totalRequester = Requester::count();
        $totalStock = Stock::count();
        $PendingIncomingRequest = Requester::leftJoin('mst_status', 'requesters.status', '=', 'mst_status.id')
            ->where('mst_status.status_name', 'Pending')
            ->count();
        $totalUser = User::where('user_type', '!=', 'admin')->count();
        $totalEDP = Edp::count();
        $allUsers = User::count();

        $RaisedRequestsRequests = Requester::leftJoin('mst_status', 'requesters.status', '=', 'mst_status.id')
        ->where('requesters.requester_rig_id', $rig_id)
        ->where('mst_status.status_name', 'Pending')
        ->count();
        
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

        return view('admin.dashboard', compact(
            'totalRequester',
        'totalStock',
        'PendingIncomingRequest',
        'RaisedRequestsRequests',
        'totalUser',
        'totalEDP',
        'allUsers',
        'weeklyStockData',
        'monthlyStockData',
        'yearlyStockData'
        ));
    }

    public function get_stock()
    {
        return view('admin.dashboard');
    }

}
