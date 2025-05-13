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

                $incomingChartData = Requester::select(
                    'stocks.section',
                    DB::raw('SUM(CASE WHEN requesters.status = 3 THEN 1 ELSE 0 END) as accept'),
                    DB::raw('SUM(CASE WHEN requesters.status = 5 THEN 1 ELSE 0 END) as decline'),
                    DB::raw('DATE(requesters.updated_at) as date')
                )
                ->join('stocks', 'requesters.stock_id', '=', 'stocks.id')
                ->whereIn('requesters.status', [3, 5])
                ->where('requesters.supplier_rig_id', $rig_id) // Incoming to this rig
                ->groupBy('stocks.section', DB::raw('DATE(requesters.updated_at)'))
                ->orderBy('date', 'desc')
                ->get();
                
                $raisedChartData = Requester::select(
                    'stocks.section',
                    DB::raw('SUM(CASE WHEN requesters.status = 3 THEN 1 ELSE 0 END) as accept'),
                    DB::raw('SUM(CASE WHEN requesters.status = 5 THEN 1 ELSE 0 END) as decline'),
                    DB::raw('DATE(requesters.updated_at) as date')
                )
                ->join('stocks', 'requesters.stock_id', '=', 'stocks.id')
                ->whereIn('requesters.status', [3, 5])
                ->where('requesters.requester_rig_id', $rig_id) // Raised by this rig
                ->groupBy('stocks.section', DB::raw('DATE(requesters.updated_at)'))
                ->orderBy('date', 'desc')
                ->get();
                
            
          
        //   dd($results  ); 


        $results = Stock::select()
        ->where('rig_id', $rig_id)
        ->get()
        ->groupBy('section');

        $newdata = $results->map(function ($items, $section) {
            return [
                'section' => $section,
                'new_spareable' => $items->sum('new_spareable'),
                'used_spareable' => $items->sum('used_spareable'),
            ];
        })->values();
        
        // Optional: Define color sets for new and used
        $newColors = ['#4285F4', '#34A853', '#F4AAAA', '#B39DDB', '#FBBC05'];
        $usedColors = ['#8AB4F8', '#81C995', '#FDE293', '#F4AAAA', '#B39DDB'];
        
        // Dynamically build $newSections and $usedSections
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
              
                'incomingChartData',
                'raisedChartData',

       
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