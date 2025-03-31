<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Stock;
use App\Models\Requester;
use DB;
class DashboardController extends Controller
{
    public function index(){
        //return view('user.dashboard');
        if (Auth::check()) {
    

            $rig_id = Auth::user()->rig_id;

                $countIncomingRequest = Requester::where('supplier_rig_id', $rig_id)->count();

                // Count Pending Incoming Requests
                $PendingIncomingRequest = Requester::leftJoin('mst_status', 'requesters.status', '=', 'mst_status.id')
                ->where('requesters.supplier_rig_id', $rig_id)
                ->where('mst_status.status_name', 'Pending')
                ->count();

                // Count Raised Requests
                 $RaisedRequests = Requester::where('requester_rig_id', $rig_id)->count();

                // Count Pending Raised Requests
                $RaisedRequestsRequests = Requester::leftJoin('mst_status', 'requesters.status', '=', 'mst_status.id')
                ->where('requesters.requester_rig_id', $rig_id)
                ->where('mst_status.status_name', 'Pending')
                ->count();

                // Stock Category Counts
                $categoryCounts = Stock::where('rig_id', $rig_id)
                ->whereIn('category', ['Spares', 'Stores', 'Capital Item'])
                ->groupBy('category')
                ->select('category', DB::raw('COUNT(*) as Category_count'))
                ->pluck('Category_count', 'category');
                    
                    
                    $totalStock = Stock::where('rig_id', $rig_id)->count();
                    $categoryPercentages = Stock::select(
                            'category',
                            DB::raw('COUNT(*) as category_count'),
                            DB::raw('ROUND((COUNT(*) / ' . $totalStock . ') * 100, 2) as percentage')
                        )
                        ->whereIn('category', ['Spares', 'Stores', 'Capital Item'])
                        ->where('rig_id', $rig_id)
                        ->groupBy('category')
                        ->pluck('percentage', 'category');



                    // dd($categoryPercentages);
                    $dailyAdditions = Stock::select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(id) as total_additions'))
                    ->groupBy('date')
                    ->orderBy('date', 'ASC')
                    ->get();

                    $weeklyAdditions = Stock::select(DB::raw('YEARWEEK(created_at) as week'), DB::raw('COUNT(id) as total_additions'))
                        ->groupBy('week')
                        ->orderBy('week', 'ASC')
                        ->get();

                    $monthlyAdditions = Stock::select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'), DB::raw('COUNT(id) as total_additions'))
                        ->groupBy('month')
                        ->orderBy('month', 'ASC')
                        ->get();
                            
                        $incomingStockCounts = Requester::join('stocks', 'requesters.stock_id', '=', 'stocks.id')
                        ->where('requesters.supplier_rig_id', $rig_id)
                        ->groupBy('stocks.category')
                        ->select('stocks.category', DB::raw('COUNT(requesters.id) as total'))
                        ->pluck('total', 'category');
                
                    // Count of Raised Requests per Stock Category
                    $raisedStockCounts = Requester::join('stocks', 'requesters.stock_id', '=', 'stocks.id')
                        ->where('requesters.requester_rig_id', $rig_id)
                        ->groupBy('stocks.category')
                        ->select('stocks.category', DB::raw('COUNT(requesters.id) as total'))
                        ->pluck('total', 'category');

           
                            // Daily Stock Requests
                            $dailyRequests = Requester::select(
                                DB::raw('DATE(created_at) as date'),
                                DB::raw('COUNT(id) as total_requests')
                            )
                            ->where('requester_rig_id', $rig_id)
                            ->groupBy('date')
                            ->orderBy('date', 'ASC')
                            ->get();

                        // Weekly Stock Requests
                        $weeklyRequests = Requester::select(
                                DB::raw('YEARWEEK(created_at) as week'),
                                DB::raw('COUNT(id) as total_requests')
                            )
                            ->where('requester_rig_id', $rig_id)
                            ->groupBy('week')
                            ->orderBy('week', 'ASC')
                            ->get();

                        // Monthly Stock Requests
                        $monthlyRequests = Requester::select(
                                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                                DB::raw('COUNT(id) as total_requests')
                            )
                            ->where('requester_rig_id', $rig_id)
                            ->groupBy('month')
                            ->orderBy('month', 'ASC')
                            ->get();

                            $topUsers = Requester::select('users.id', 'users.user_name')
                            ->join('users', 'requesters.requester_id', '=', 'users.id') // Using requester_id to link with users
                            ->where('requesters.requester_rig_id', $rig_id) // Ensure requests belong to the same rig
                            ->groupBy('users.id', 'users.user_name') // Include ID and name
                            ->orderByDesc('users.id') // Order by user ID (or change this based on preference)
                            ->limit(5) // Get top 5 users
                            ->get();
                    

                    return view('user.dashboard', compact(
                        'dailyAdditions', 'weeklyAdditions', 'monthlyAdditions',
                        'categoryCounts', 'categoryPercentages',
                        'countIncomingRequest', 'PendingIncomingRequest',
                        'RaisedRequests', 'RaisedRequestsRequests',
                        'incomingStockCounts', 'raisedStockCounts',
                        'dailyRequests', 'weeklyRequests', 'monthlyRequests','topUsers','RaisedRequestsRequests'
                    ));
        }else{
            return redirect()->route('user.login');
        }
    }
}