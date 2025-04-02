<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
        $PendingIncomingRequest = Requester::leftJoin('mst_status', 'requesters.status', '=', 'mst_status.id')
            ->where('mst_status.status_name', 'Pending')
            ->count();
        $totalUser = User::where('user_type', '!=', 'admin')->count();
        $totalRequester = Requester::count();
        $totalStock = Stock::count();
        $totalEDP = Edp::count();
        $allUsers = User::count();
        $rigOverview = RigUser::select('location_id', 'name', DB::raw('COUNT(id) as total_rigs'))
            ->groupBy('location_id', 'name')
            ->get();

        $usersOverView = User::select('users.id', 'users.user_name', 'rig_users.name as rig_name')
            ->leftJoin('rig_users', 'users.rig_id', '=', 'rig_users.id')
            ->get();
        $rigData = $usersOverView->groupBy('rig_name')->map(function ($group, $rigName) {
            return [
                'name' => $rigName ?? 'No Rig Assigned',
                'y' => $group->count(),
                'userNames' => $group->pluck('user_name')->toArray(),
            ];
        })->values();

        $edpsData = Edp::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $categoriesData = Category::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $dates = collect($edpsData->pluck('date'))->merge($categoriesData->pluck('date'))->unique()->sort();

        $edpsSeries = [];
        $categoriesSeries = [];

        foreach ($dates as $date) {
            $edpsSeries[] = [
                'date' => $date,
                'count' => optional($edpsData->firstWhere('date', $date))->count ?? 0,
            ];
            $categoriesSeries[] = [
                'date' => $date,
                'count' => optional($categoriesData->firstWhere('date', $date))->count ?? 0,
            ];
        }

        $inventoryLevels = Stock::select(
            'category',
            DB::raw('SUM(qty) as total_qty')
        )
            ->groupBy('category')
            ->orderBy('category')
            ->get();

        //  Get Total Stock Requested by Category
        $stockRequests = Requester::join('stocks', 'requesters.stock_id', '=', 'stocks.id')
            ->select(
                'stocks.category',
                DB::raw('SUM(requesters.requested_qty) as total_requested')
            )
            ->groupBy('stocks.category')
            ->orderBy('stocks.category')
            ->get();

        // Get Stock Movement Trends (Requests Over Time)
        $stockMovements = Requester::select(
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
            DB::raw('SUM(requested_qty) as total_requested')
        )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        //  Get Requests Sent Per Rig (requester_rig_id)
        $rigRequests = Requester::join('rig_users as r', 'requesters.requester_rig_id', '=', 'r.id')
            ->select('r.name as rig_name', DB::raw('COUNT(requesters.id) as total_requests_sent'))
            ->groupBy('r.name')
            ->orderBy('total_requests_sent', 'DESC')
            ->get();

        //  Get Incoming Requests Per Rig (supplier_rig_id) - **Fixed Query**
        $incomingRequests = Requester::join('rig_users as r', 'requesters.supplier_rig_id', '=', 'r.id')
            ->select('r.name as rig_name', DB::raw('COUNT(requesters.id) as total_requests_received'))
            ->where('requesters.status', '!=', 5) // 
            ->groupBy('r.name')
            ->orderBy('total_requests_received', 'DESC')
            ->get();

            $ownStockRequests = Requester::whereColumn('stock_id', 'requester_stock_id')->count();

            //  Get total requests that used supplier stock
            $supplierStockRequests = Requester::whereColumn('stock_id', '!=', 'requester_stock_id')->count();

        return view('admin.dashboard', compact(
            'rigOverview',
            'stockMovements',
            'inventoryLevels',
            'stockRequests',
            'rigRequests',
            'incomingRequests',
            'rigData',
            'usersOverView',
            'totalUser',
            'totalRequester',
            'totalStock',
            'totalEDP',
            'PendingIncomingRequest',
            'allUsers',
            'edpsSeries',
            'categoriesSeries',
             'ownStockRequests', 
        'supplierStockRequests'
        ));
    }

    public function get_stock()
    {
        return view('admin.dashboard');
    }

}
