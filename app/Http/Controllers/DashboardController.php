<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Stock;
use App\Models\Requester;
use Illuminate\Support\Facades\DB;
class DashboardController extends Controller
{
    public function index(){
        if (Auth::check()) {
            $rig_id = Auth::user()->rig_id;
    
            // Basic counts
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

                
    
            // Stock category data
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
    
                $dailyAdditions = Stock::select(
                    DB::raw('DATE(created_at) as date'), 
                    DB::raw('COUNT(id) as quantity')
                )
                ->where('rig_id', $rig_id)
                ->groupBy('date')
                ->orderBy('date', 'ASC')
                ->get()
                ->pluck('quantity', 'date');  // Pluck as [date => quantity] pairs
            
           
                $weeklyAdditions = Stock::select(
                    DB::raw('YEARWEEK(created_at) as week'), 
                    DB::raw('COUNT(id) as quantity')
                )
                ->where('rig_id', $rig_id)
                ->groupBy('week')
                ->orderBy('week', 'ASC')
                ->get()
                ->pluck('quantity', 'week');
    
                $monthlyAdditions = Stock::select(
                    DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'), 
                    DB::raw('COUNT(id) as quantity')
                )
                ->where('rig_id', $rig_id)
                ->groupBy('month')
                ->orderBy('month', 'ASC')
                ->get()
                ->pluck('quantity', 'month'); 
    
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
    
            // Time-based requests data
            $dailyIncomingRequests = Requester::select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('COUNT(id) as total_requests')
                )
                ->where('supplier_rig_id', $rig_id)
                ->groupBy('date')
                ->orderBy('date', 'ASC')
                ->get();
    
            $weeklyIncomingRequests = Requester::select(
                    DB::raw('YEARWEEK(created_at) as week'),
                    DB::raw('COUNT(id) as total_requests')
                )
                ->where('supplier_rig_id', $rig_id)
                ->groupBy('week')
                ->orderBy('week', 'ASC')
                ->get();
    
            $monthlyIncomingRequests = Requester::select(
                    DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                    DB::raw('COUNT(id) as total_requests')
                )
                ->where('supplier_rig_id', $rig_id)
                ->groupBy('month')
                ->orderBy('month', 'ASC')
                ->get();
    
            $dailyRaisedRequests = Requester::select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('COUNT(id) as total_requests')
                )
                ->where('requester_rig_id', $rig_id)
                ->groupBy('date')
                ->orderBy('date', 'ASC')
                ->get();
                
    
            $weeklyRaisedRequests = Requester::select(
                    DB::raw('YEARWEEK(created_at) as week'),
                    DB::raw('COUNT(id) as total_requests')
                )
                ->where('requester_rig_id', $rig_id)
                ->groupBy('week')
                ->orderBy('week', 'ASC')
                ->get();
    
            $monthlyRaisedRequests = Requester::select(
                    DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                    DB::raw('COUNT(id) as total_requests')
                )
                ->where('requester_rig_id', $rig_id)
                ->groupBy('month')
                ->orderBy('month', 'ASC')
                ->get();
    
            // Top users
            $topUsers = Requester::select('users.id', 'users.user_name')
                ->join('users', 'requesters.requester_id', '=', 'users.id')
                ->where('requesters.requester_rig_id', $rig_id)
                ->groupBy('users.id', 'users.user_name')
                ->orderByDesc('users.id')
                ->limit(5)
                ->get();
    
            return view('user.dashboard', compact(
                'dailyAdditions', 'weeklyAdditions', 'monthlyAdditions',
                'categoryCounts', 'categoryPercentages',
                'countIncomingRequest', 'PendingIncomingRequest','totalStock',
                'RaisedRequests', 'RaisedRequestsRequests',
                'incomingStockCounts', 'raisedStockCounts', 'topUsers',
                'dailyIncomingRequests', 'weeklyIncomingRequests', 'monthlyIncomingRequests',
                'dailyRaisedRequests', 'weeklyRaisedRequests', 'monthlyRaisedRequests'
            ));
        } else {
            return redirect()->route('user.login');
        }
    }

    public function test(){

    }
}