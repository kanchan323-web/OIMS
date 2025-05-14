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

        // MIT Status
        $Total_Incoming = Requester::select(
            'requesters.*',
        )->join('rig_users', 'requesters.requester_rig_id', '=', 'rig_users.id')
            ->join('stocks', 'requesters.stock_id', '=', 'stocks.id')
            ->join('edps', 'stocks.edp_code', '=', 'edps.id')
            ->leftJoin('mst_status', 'requesters.status', '=', 'mst_status.id')
            ->with('requestStatuses')
            ->count('status');
        //ok
        // MIT Status

        // MIT Status
        $mitstatus = Requester::select(
            'requesters.*',
        )->join('rig_users', 'requesters.requester_rig_id', '=', 'rig_users.id')
            ->join('stocks', 'requesters.stock_id', '=', 'stocks.id')
            ->join('edps', 'stocks.edp_code', '=', 'edps.id')
            ->leftJoin('mst_status', 'requesters.status', '=', 'mst_status.id')
            ->with('requestStatuses')
            ->where('requesters.status', 6)
            ->count();
        //ok
        // MIT Status

        // Received Status
        $Received_Status = Requester::select(
            'requesters.*',
        )->join('rig_users', 'requesters.requester_rig_id', '=', 'rig_users.id')
            ->join('stocks', 'requesters.stock_id', '=', 'stocks.id')
            ->join('edps', 'stocks.edp_code', '=', 'edps.id')
            ->leftJoin('mst_status', 'requesters.status', '=', 'mst_status.id')
            ->with('requestStatuses')
            ->where('requesters.status', 3)
            ->count();
        //ok
        // Received Status

        // Pending Status
        $Pending_Status = Requester::select(
            'requesters.*',
        )->join('rig_users', 'requesters.requester_rig_id', '=', 'rig_users.id')
            ->join('stocks', 'requesters.stock_id', '=', 'stocks.id')
            ->join('edps', 'stocks.edp_code', '=', 'edps.id')
            ->leftJoin('mst_status', 'requesters.status', '=', 'mst_status.id')
            ->with('requestStatuses')
            ->where('requesters.status', 1)
            ->count();
        //ok
        // Pending Status

        // Pending Status
        $Decline_Status = Requester::select(
            'requesters.*',
        )->join('rig_users', 'requesters.requester_rig_id', '=', 'rig_users.id')
            ->join('stocks', 'requesters.stock_id', '=', 'stocks.id')
            ->join('edps', 'stocks.edp_code', '=', 'edps.id')
            ->leftJoin('mst_status', 'requesters.status', '=', 'mst_status.id')
            ->with('requestStatuses')
            ->where('requesters.status', 5)
            ->count();
        //ok
        // Pending Status

        // Query Status
        $Query_Status = Requester::select(
            'requesters.*',
        )->join('rig_users', 'requesters.requester_rig_id', '=', 'rig_users.id')
            ->join('stocks', 'requesters.stock_id', '=', 'stocks.id')
            ->join('edps', 'stocks.edp_code', '=', 'edps.id')
            ->leftJoin('mst_status', 'requesters.status', '=', 'mst_status.id')
            ->with('requestStatuses')
            ->where('requesters.status', 2)
            ->count();
        //ok
        // Query Status

        // Approve Status
        $Approve_Status = Requester::select(
            'requesters.*',
        )->join('rig_users', 'requesters.requester_rig_id', '=', 'rig_users.id')
            ->join('stocks', 'requesters.stock_id', '=', 'stocks.id')
            ->join('edps', 'stocks.edp_code', '=', 'edps.id')
            ->leftJoin('mst_status', 'requesters.status', '=', 'mst_status.id')
            ->with('requestStatuses')
            ->where('requesters.status', 4)
            ->count();
        //ok
        // Approve Status

        // received/decline data 
        $results = Requester::select(
            'stocks.section',
            DB::raw('SUM(CASE WHEN requesters.status = 3 THEN 1 ELSE 0 END) as accept'),
            DB::raw('SUM(CASE WHEN requesters.status = 5 THEN 1 ELSE 0 END) as decline'),
            DB::raw('SUM(CASE WHEN requesters.status = 1 THEN 1 ELSE 0 END) as pending'),
            DB::raw('DATE(requesters.updated_at) as date')
        )
        ->join('stocks', 'requesters.stock_id', '=', 'stocks.id')
        ->whereIn('requesters.status', [1, 3, 5]) // Include Pending
        ->groupBy('stocks.section', DB::raw('DATE(requesters.updated_at)'))
        ->orderBy('date', 'desc')
        ->get();
        
        // Prepare chart data in required format
        $chartData = $results->map(function ($item) {
            return [
                'section' => $item->section,
                'accept' => $item->accept,
                'decline' => $item->decline,
                'pending' => $item->pending,
                'date' => $item->date
            ];
        })->toArray();
          
        //   dd($results  ); 


        $results = Stock::select()->get()->groupBy('section');

        $newdata = $results->map(function ($items, $section) {
            return [
                'section' => $section,
                'new_spareable' => $items->sum('new_spareable'),
                'used_spareable' => $items->sum('used_spareable'),
            ];
        })->values();
        
        // Define color palettes
        $newColors = ['#4285F4', '#34A853', '#F4AAAA', '#B39DDB', '#FBBC05', '#00ACC1', '#FF7043', '#9575CD', '#F06292'];
        $usedColors = ['#8AB4F8', '#81C995', '#FDE293', '#F4AAAA', '#B39DDB', '#80DEEA', '#FFAB91', '#D1C4E9', '#F8BBD0'];
        
        // Prepare chart data
        $newSections = [];
        $usedSections = [];
        
        foreach ($newdata as $index => $item) {
            $colorNew = $newColors[$index % count($newColors)];
            $colorUsed = $usedColors[$index % count($usedColors)];
        
            $newSections[] = [
                'name' => $item['section'] . ' (New)',
                'y' => $item['new_spareable'],
                'color' => $colorNew,
            ];
        
            $usedSections[] = [
                'name' => $item['section'] . ' (Used)',
                'y' => $item['used_spareable'],
                'color' => $colorUsed,
            ];
        }
        
      

        return view('admin.dashboard', compact(

            'Received_Status',
            'Pending_Status',
            'Approve_Status',
            'mitstatus',
            'Query_Status',
            'Decline_Status',
            'Total_Incoming',
        'chartData'

       
        ),[
            'newSections' => $newSections,
            'usedSections' => $usedSections
        ]);
    }

    public function getSectionData(Request $request)
    {
        $results = Requester::select(
            'stocks.section',
            DB::raw('SUM(CASE WHEN requesters.status = 3 THEN 1 ELSE 0 END) as accept'),
            DB::raw('SUM(CASE WHEN requesters.status = 5 THEN 1 ELSE 0 END) as decline')
        )
        ->join('stocks', 'requesters.stock_id', '=', 'stocks.id')
        ->whereIn('requesters.status', [3, 5])
        ->groupBy('stocks.section')
        ->orderBy('stocks.section')
        ->get();

        // Format for chart
        $chartData = $results->map(function ($item) {
            return [
                'section' => $item->section,
                'accept' => $item->accept,
                'decline' => $item->decline,
                'date' => date('Y-m-d') // Using current date for simplicity
            ];
        });

        return response()->json($chartData);
    }

}
