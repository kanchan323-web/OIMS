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
                    DB::raw('SUM(CASE WHEN requesters.status = 3 THEN 1 ELSE 0 END) as received_count'),
                    DB::raw('SUM(CASE WHEN requesters.status = 5 THEN 1 ELSE 0 END) as decline_count')
                )
                ->join('stocks', 'requesters.stock_id', '=', 'stocks.id')
                ->whereIn('requesters.status', [3, 5])
                ->groupBy('stocks.section')
                ->orderBy('stocks.section')
                ->get();

                // Prepare data for chart
                $sections = $results->pluck('section');
                $receivedData = $results->pluck('received_count');
                $declineData = $results->pluck('decline_count');
    // received/decline data 
   
    
    
          
        //   dd($results  );


        return view('admin.dashboard', compact(

            'Received_Status',
            'Pending_Status',
            'Approve_Status',
            'mitstatus',
            'Query_Status',
            'Decline_Status',
            'Total_Incoming',
            'sections', 'receivedData', 'declineData'

       
        ));
    }

    public function get_stock()
    {
        return view('admin.dashboard');
    }

}
