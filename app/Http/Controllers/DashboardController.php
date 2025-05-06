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


            // Weekly/Monthly/Yearly Stock
            $weeklyStockData = [];
            $monthlyStockData = [];
            $yearlyStockData = [];

            $categories = ['Spares', 'Stores', 'Capital Item'];

            foreach ($categories as $category) {
                // same logic for weeklyStockData, monthlyStockData, yearlyStockData
            }

            $countUsedAndNewStock = Stock::where('rig_id', $rig_id)
                ->select('new_spareable', 'used_spareable')
                ->get();

            $newStock = $countUsedAndNewStock->sum(function ($item) {
                return is_numeric($item->new_spareable) ? floatval($item->new_spareable) : 0;
            });

            $usedStock = $countUsedAndNewStock->sum(function ($item) {
                return is_numeric($item->used_spareable) ? floatval($item->used_spareable) : 0;
            });

            $total = $newStock + $usedStock;

            if ($total > 0) {
                $newPercent = round(($newStock / $total) * 100, 1);
                $usedPercent = round(($usedStock / $total) * 100, 1);
            } else {
                $newPercent = 0;
                $usedPercent = 0;
            }


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