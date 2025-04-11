<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Requester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Stock;
use App\Models\Edp;
use App\Models\User;
use App\Models\RigUser;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use App\Models\RequestStock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class RequestReportController extends Controller
{
    public function index(Request $request)
    {
        $moduleName = "Request Reports";
        $userId = Auth::id();
        $rig_id = Auth::user()->rig_id;

    /*    $data = Requester::leftJoin('stocks', 'requesters.stock_id', '=', 'stocks.id')
            ->leftJoin('mst_status', 'requesters.status', '=', 'mst_status.id')
            ->join('rig_users', 'requesters.supplier_rig_id', '=', 'rig_users.id')
            ->join('edps', 'stocks.edp_code', '=', 'edps.id')
            //->where('requesters.supplier_id', $userId)
            ->where('requesters.requester_rig_id', $rig_id)
            ->select('requesters.*', 'stocks.location_name', 'stocks.location_id', 'mst_status.status_name', 'edps.edp_code')
            ->orderBy('requesters.created_at', 'desc')
            ->get();

        $datarig = User::where('user_type', '!=', 'admin')
            ->where('rig_id', $rig_id)
            ->pluck('id')
            ->toArray();
        $stocks = Stock::select('id')->where('rig_id', $rig_id)->distinct()->get();
        $edps = Edp::select('edp_code', 'id as edp_id')->whereIn('id', $stocks)->distinct()->get();
*/
        //return view('reports.stock_reports', compact('data', 'moduleName', 'datarig', 'edps'));
        return view('admin.reports.request.request_reports', compact('moduleName',));
    }

    public function fetchReport(Request $request)
    {
        $reportType = $request->input('report_type');

        if (!$reportType) {
            return response()->json(['error' => 'Missing report type'], 400);
        }

        // try {
        switch ($reportType) {
            case 'summary':
                $data = $this->requestSummary($request);
                break;
            case 'approval_rates':
                $data = $this->approvalDeclineRates($request);
                break;
            case 'transaction_history':
                $data = $this->transactionHistory($request);
                break;
            case 'fulfillment_status':
                $data = $this->requestFulfillmentStatus($request);
                break;
            case 'consumption_details':
                $data = $this->requestConsumptionDetails($request);
                break;
            default:
                return response()->json(['error' => 'Invalid report type'], 400);
        }

        // Ensure response is structured correctly
        return response()->json(['data' => $data ?? []]);

        // } catch (\Exception $e) {
        //     return response()->json(['error' => 'Something went wrong!'], 500);
        // }
    }

    private function requestSummary($request)
    {
        $query = Requester::whereBetween('created_at', [$request->from_date, $request->to_date]);
        return [
            'total_requests' => $query->count(),
            'approved' => $query->where('status', 'Approved')->count(),
            'declined' => $query->where('status', 'Declined')->count(),
            'pending' => $query->where('status', 'Pending')->count(),
        ];
    }

    private function approvalDeclineRates($request)
    {
        $query = Requester::whereBetween('created_at', [$request->from_date, $request->to_date]);
        $total = $query->count();
        return [
            'approval_rate' => $total > 0 ? round(($query->where('status', 'Approved')->count() / $total) * 100, 2) : 0,
            'decline_rate' => $total > 0 ? round(($query->where('status', 'Declined')->count() / $total) * 100, 2) : 0,
            'pending_rate' => $total > 0 ? round(($query->where('status', 'Pending')->count() / $total) * 100, 2) : 0,
        ];
    }

    private function transactionHistory($request)
    {
        return Requester::whereBetween('created_at', [$request->from_date, $request->to_date])
            ->select('id as request_id', 'stock_items', 'quantity', 'status', 'created_at')
            ->get();
    }
}
