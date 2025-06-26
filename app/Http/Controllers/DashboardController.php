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
                    DB::raw('SUM(CASE WHEN requesters.status IN (1, 2, 4, 6) THEN 1 ELSE 0 END) as pending'),
                    DB::raw('DATE(requesters.updated_at) as date')
                )
                ->join('stocks', 'requesters.stock_id', '=', 'stocks.id')
                ->whereIn('requesters.status', [1,2,3,4,5,6]) // Include Pending
                ->where('requesters.supplier_rig_id', $rig_id)
                ->groupBy('stocks.section', DB::raw('DATE(requesters.updated_at)'))
                ->orderBy('date', 'desc')
                ->get();

                // Raised by this rig
                $raisedChartData = Requester::select(
                    'stocks.section',
                    DB::raw('SUM(CASE WHEN requesters.status = 3 THEN 1 ELSE 0 END) as accept'),
                    DB::raw('SUM(CASE WHEN requesters.status = 5 THEN 1 ELSE 0 END) as decline'),
                    DB::raw('SUM(CASE WHEN requesters.status IN (1, 2, 4, 6) THEN 1 ELSE 0 END) as pending'),
                    DB::raw('DATE(requesters.updated_at) as date')
                )
                ->join('stocks', 'requesters.stock_id', '=', 'stocks.id')
                ->whereIn('requesters.status', [1,2,3,4,5,6]) // Include Pending
                ->where('requesters.requester_rig_id', $rig_id)
                ->groupBy('stocks.section', DB::raw('DATE(requesters.updated_at)'))
                ->orderBy('date', 'desc')
                ->get();



        //   dd($results  );

/*
        $results = Stock::select()
        ->where('rig_id', $rig_id)
        ->get()
        ->groupBy('section');

        $combinedSections = $results->map(function ($items, $section) {
            return [
                'section' => $section,
                'new' => $items->sum('new_spareable'),
                'used' => $items->sum('used_spareable'),
            ];
        })->values();
*/
        $combinedSections = DB::table('stocks')
            ->select(
                'section',
                DB::raw("COUNT(CASE WHEN new_spareable > 0 OR used_spareable > 0 THEN 1 END) AS edp_count"),
                DB::raw("COUNT(CASE WHEN new_spareable > 0 THEN 1 END) AS new"),
                DB::raw("COUNT(CASE WHEN used_spareable > 0 THEN 1 END) AS used")
            )
            ->where('rig_id', $rig_id)
            ->groupBy('section')
            ->get();

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
                'combinedSections',


            ));
        } else {
            return redirect()->route('user.login');
        }
    }


    public function test()
    {

    }
}
