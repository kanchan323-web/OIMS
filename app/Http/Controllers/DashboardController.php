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



            $Total_Incoming = Requester::join('rig_users', 'requesters.requester_rig_id', '=', 'rig_users.id')
                ->join('stocks', 'requesters.stock_id', '=', 'stocks.id')
                ->join('edps', 'stocks.edp_code', '=', 'edps.id')
                ->leftJoin('mst_status', 'requesters.status', '=', 'mst_status.id')
                ->where('requesters.supplier_rig_id', $rig_id)
                ->with('requestStatuses')
                ->count('status');

            $mitstatus = Requester::join('rig_users', 'requesters.requester_rig_id', '=', 'rig_users.id')
                ->join('stocks', 'requesters.stock_id', '=', 'stocks.id')
                ->join('edps', 'stocks.edp_code', '=', 'edps.id')
                ->leftJoin('mst_status', 'requesters.status', '=', 'mst_status.id')
                ->where('requesters.supplier_rig_id', $rig_id)
                ->with('requestStatuses')
                ->where('requesters.status', 6)
                ->count();

            $Received_Status = Requester::join('rig_users', 'requesters.requester_rig_id', '=', 'rig_users.id')
                ->join('stocks', 'requesters.stock_id', '=', 'stocks.id')
                ->join('edps', 'stocks.edp_code', '=', 'edps.id')
                ->leftJoin('mst_status', 'requesters.status', '=', 'mst_status.id')
                ->where('requesters.supplier_rig_id', $rig_id)
                ->with('requestStatuses')
                ->where('requesters.status', 3)
                ->count();

            $Pending_Status = Requester::join('rig_users', 'requesters.requester_rig_id', '=', 'rig_users.id')
                ->join('stocks', 'requesters.stock_id', '=', 'stocks.id')
                ->join('edps', 'stocks.edp_code', '=', 'edps.id')
                ->leftJoin('mst_status', 'requesters.status', '=', 'mst_status.id')
                ->where('requesters.supplier_rig_id', $rig_id)
                ->with('requestStatuses')
                ->where('requesters.status', 1)
                ->count();

            $Decline_Status = Requester::join('rig_users', 'requesters.requester_rig_id', '=', 'rig_users.id')
                ->join('stocks', 'requesters.stock_id', '=', 'stocks.id')
                ->join('edps', 'stocks.edp_code', '=', 'edps.id')
                ->leftJoin('mst_status', 'requesters.status', '=', 'mst_status.id')
                ->where('requesters.supplier_rig_id', $rig_id)
                ->with('requestStatuses')
                ->where('requesters.status', 5)
                ->count();

            $Query_Status = Requester::join('rig_users', 'requesters.requester_rig_id', '=', 'rig_users.id')
                ->join('stocks', 'requesters.stock_id', '=', 'stocks.id')
                ->join('edps', 'stocks.edp_code', '=', 'edps.id')
                ->leftJoin('mst_status', 'requesters.status', '=', 'mst_status.id')
                ->where('requesters.supplier_rig_id', $rig_id)
                ->with('requestStatuses')
                ->where('requesters.status', 2)
                ->count();

            $Approve_Status = Requester::join('rig_users', 'requesters.requester_rig_id', '=', 'rig_users.id')
                ->join('stocks', 'requesters.stock_id', '=', 'stocks.id')
                ->join('edps', 'stocks.edp_code', '=', 'edps.id')
                ->leftJoin('mst_status', 'requesters.status', '=', 'mst_status.id')
                ->where('requesters.supplier_rig_id', $rig_id)
                ->with('requestStatuses')
                ->where('requesters.status', 4)
                ->count();

            $mit_raised = Requester::leftJoin('stocks', 'requesters.stock_id', '=', 'stocks.id')
                ->leftJoin('mst_status', 'requesters.status', '=', 'mst_status.id')
                ->join('rig_users', 'requesters.supplier_rig_id', '=', 'rig_users.id')
                ->join('edps', 'stocks.edp_code', '=', 'edps.id')
                ->where('requesters.requester_rig_id', $rig_id)
                ->where('requesters.status', 6)
                ->count();

            $Received_raised = Requester::leftJoin('stocks', 'requesters.stock_id', '=', 'stocks.id')
                ->leftJoin('mst_status', 'requesters.status', '=', 'mst_status.id')
                ->join('rig_users', 'requesters.supplier_rig_id', '=', 'rig_users.id')
                ->join('edps', 'stocks.edp_code', '=', 'edps.id')
                ->where('requesters.requester_rig_id', $rig_id)
                ->where('requesters.status', 3)
                ->count();

            $Pending_raised = Requester::leftJoin('stocks', 'requesters.stock_id', '=', 'stocks.id')
                ->leftJoin('mst_status', 'requesters.status', '=', 'mst_status.id')
                ->join('rig_users', 'requesters.supplier_rig_id', '=', 'rig_users.id')
                ->join('edps', 'stocks.edp_code', '=', 'edps.id')
                ->where('requesters.requester_rig_id', $rig_id)
                ->where('requesters.status', 1)
                ->count();

            $Decline_raised = Requester::leftJoin('stocks', 'requesters.stock_id', '=', 'stocks.id')
                ->leftJoin('mst_status', 'requesters.status', '=', 'mst_status.id')
                ->join('rig_users', 'requesters.supplier_rig_id', '=', 'rig_users.id')
                ->join('edps', 'stocks.edp_code', '=', 'edps.id')
                ->where('requesters.requester_rig_id', $rig_id)
                ->where('requesters.status', 5)
                ->count();

            $Approve_raised = Requester::leftJoin('stocks', 'requesters.stock_id', '=', 'stocks.id')
                ->leftJoin('mst_status', 'requesters.status', '=', 'mst_status.id')
                ->join('rig_users', 'requesters.supplier_rig_id', '=', 'rig_users.id')
                ->join('edps', 'stocks.edp_code', '=', 'edps.id')
                ->where('requesters.requester_rig_id', $rig_id)
                ->where('requesters.status', 4)
                ->count();

            $Query_raised = Requester::leftJoin('stocks', 'requesters.stock_id', '=', 'stocks.id')
                ->leftJoin('mst_status', 'requesters.status', '=', 'mst_status.id')
                ->join('rig_users', 'requesters.supplier_rig_id', '=', 'rig_users.id')
                ->join('edps', 'stocks.edp_code', '=', 'edps.id')
                ->where('requesters.requester_rig_id', $rig_id)
                ->where('requesters.status', 2)
                ->count();

            $Total_Raised = Requester::leftJoin('stocks', 'requesters.stock_id', '=', 'stocks.id')
                ->leftJoin('mst_status', 'requesters.status', '=', 'mst_status.id')
                ->join('rig_users', 'requesters.supplier_rig_id', '=', 'rig_users.id')
                ->join('edps', 'stocks.edp_code', '=', 'edps.id')
                ->where('requesters.requester_rig_id', $rig_id)
                ->count('status');


                // Testing

                 // received/decline data 
        $results = Requester::select(
            'stocks.section',
            DB::raw('SUM(CASE WHEN requesters.status = 3 THEN 1 ELSE 0 END) as accept'),
            DB::raw('SUM(CASE WHEN requesters.status = 5 THEN 1 ELSE 0 END) as decline'),
            DB::raw('DATE(requesters.updated_at) as date')
        )
        ->join('stocks', 'requesters.stock_id', '=', 'stocks.id')
        ->whereIn('requesters.status', [3, 5])
        ->groupBy('stocks.section', DB::raw('DATE(requesters.updated_at)'))
        ->orderBy('date', 'desc')
        ->get();
    
        // Prepare chart data in required format
        $chartData = $results->map(function ($item) {
            return [
                'section' => $item->section,
                'accept' => $item->accept,
                'decline' => $item->decline,
                'date' => $item->date
            ];
        })->toArray();
          
        //   dd($results  ); 

        $newSections = [
            ['name' => 'SEC-1 (New)', 'y' => 52, 'color' => '#4285F4'],
            ['name' => 'SEC-2 (New)', 'y' => 30, 'color' => '#34A853'],
            ['name' => 'SEC-3 (New)', 'y' => 50, 'color' => '#F0BC05'],
            ['name' => 'SEC-4 (New)', 'y' => 50, 'color' => '#FBB805'],
            ['name' => 'SEC-5 (New)', 'y' => 50, 'color' => '#FBBC05'],
        ];

        $usedSections = [
            ['name' => 'SEC-1 (Used)', 'y' => 28, 'color' => '#8AB4F8'],
            ['name' => 'SEC-2 (Used)', 'y' => 15, 'color' => '#81C995'],
            ['name' => 'SEC-3 (Used)', 'y' => 22, 'color' => '#FDE293'],
        ];
                // Testing

            return view('user.dashboard', compact(
                'Total_Incoming',
                'mitstatus',
                'Received_Status',
                'Pending_Status',
                'Decline_Status',
                'Approve_Status',
                'Query_Status',
                'Total_Raised',
                'mit_raised',
                'Received_raised',
                'Pending_raised',
                'Decline_raised',
                'Approve_raised',
                'Query_raised',
                'chartData'

       
            ),[
                'newSections' => $newSections,
                'usedSections' => $usedSections
            ]);
        } else {
            return redirect()->route('user.login');
        }
    }


    public function test()
    {

    }
}