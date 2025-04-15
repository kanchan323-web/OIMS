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
        return view('admin.reports.request.request_reports', compact('moduleName',));
    }

    public function fetchReport(Request $request)
    {
        $reportType = $request->input('report_type');
        if (!$reportType) {
            return response()->json(['error' => 'Missing report type'], 400);
        }

        switch ($reportType) {
            case 'pending':
                $data = $this->pendingRequest($request);
                break;
            case 'approved':
                $data = $this->approvedRequest($request);
                break;
            case 'rejected':
                $data = $this->rejectedRequest($request);
                break;
            case 'escalated':
                $data = $this->escalatedRequest($request);
                break;
            default:
                return response()->json(['error' => 'Invalid report type'], 400);
        }
        // Ensure response is structured correctly
        return response()->json(['data' => $data ?? []]);
    }

    private function pendingRequest($request){
        $query = Requester::query();
        if(!empty($request->from_date) || !empty($request->to_date)) {
            $query = Requester::whereBetween('created_at', [$request->from_date, $request->to_date]);
        }
        $pendding = $query->join('stocks', 'requesters.requester_stock_id', '=', 'stocks.id')
            ->join('edps', 'stocks.edp_code', '=', 'edps.id')
            ->join('mst_status', 'requesters.status', '=', 'mst_status.id')
            ->join('rig_users', 'requesters.requester_rig_id', '=', 'rig_users.id')
            ->select('requesters.RID','requesters.requested_qty','requesters.created_at', 'edps.edp_code AS EDP_Code','edps.description','rig_users.name')
            ->whereBetween('requesters.status', [1,2,6])
            ->orderBy('requesters.id', 'desc')->get();
        return $pendding;
    }

    private function approvedRequest($request){
        $query = Requester::whereBetween('created_at', [$request->from_date, $request->to_date]);
        $total = $query->count();
        return [
            'approval_rate' => $total > 0 ? round(($query->where('status', 'Approved')->count() / $total) * 100, 2) : 0,
            'decline_rate' => $total > 0 ? round(($query->where('status', 'Declined')->count() / $total) * 100, 2) : 0,
            'pending_rate' => $total > 0 ? round(($query->where('status', 'Pending')->count() / $total) * 100, 2) : 0,
        ];
    }

    private function rejectedRequest($request){
        return Requester::whereBetween('created_at', [$request->from_date, $request->to_date])
            ->select('id as request_id', 'stock_items', 'quantity', 'status', 'created_at')
            ->get();
    }

    private function escalatedRequest($request){
        return Requester::whereBetween('created_at', [$request->from_date, $request->to_date])
            ->select('id as request_id', 'stock_items', 'quantity', 'status', 'created_at')
            ->get();
    }
}
