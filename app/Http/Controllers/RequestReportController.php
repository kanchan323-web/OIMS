<?php

namespace App\Http\Controllers;

use App\Models\LogRequester;
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
        return view('reports.request.request_reports', compact('moduleName',));
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
        $query = Requester::query()
            ->where('requesters.requester_rig_id', auth()->user()->rig_id)
            ->orWhere('requesters.supplier_rig_id', auth()->user()->rig_id);

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
        }

        return [
            'total_requests' => $query->count(),
            'approved' => $query->where('status', 4)->count(),
            'declined' => $query->where('status', 5)->count(),
            'pending' => $query->where('status', 1)->count(),
        ];
    }


    private function approvalDeclineRates($request)
    {
        $query = Requester::query()
            ->where('requesters.requester_rig_id', auth()->user()->rig_id)
            ->orWhere('requesters.supplier_rig_id', auth()->user()->rig_id);

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
        }

        $total = $query->count();

        $approvedCount = (clone $query)->where('status', 4)->count();
        $declinedCount = (clone $query)->where('status', 5)->count();
        $pendingCount = (clone $query)->where('status', 1)->count();


        return [
            'approval_rate' => $total > 0 ? round(($approvedCount / $total) * 100, 2) : 0,
            'decline_rate' => $total > 0 ? round(($declinedCount / $total) * 100, 2) : 0,
            'pending_rate' => $total > 0 ? round(($pendingCount / $total) * 100, 2) : 0,
        ];
    }

    private function transactionHistory($request)
    {
        $query = Requester::query()
            ->leftJoin('request_status', 'requesters.id', '=', 'request_status.request_id')
            ->leftJoin('users', 'request_status.user_id', '=', 'users.id')
            ->leftJoin('rig_users', 'request_status.rig_id', '=', 'rig_users.id')
            ->where('requesters.requester_rig_id', auth()->user()->rig_id)
            ->orWhere('requesters.supplier_rig_id', auth()->user()->rig_id);

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('requesters.created_at', [$request->from_date, $request->to_date]);
        }

        return $query->select(
            'requesters.id as request_id',
            'requesters.available_qty',
            'requesters.status',
            'requesters.created_at',
            'request_status.status_id',
            'request_status.supplier_qty',
            'request_status.supplier_new_spareable',
            'request_status.supplier_used_spareable',
            'request_status.user_id as processed_by',
            'request_status.rig_id',
            'users.user_name as processed_by_name',
            'rig_users.name as rig_name'
        )->get();
    }


    private function requestFulfillmentStatus($request)
    {
        $query = Requester::with(['latestStatus', 'requestedStock', 'requesterStock'])
            ->where('requesters.requester_rig_id', auth()->user()->rig_id)
            ->orWhere('requesters.supplier_rig_id', auth()->user()->rig_id);

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
        }

        return $query->get()->map(function ($requester) {
            $status = $requester->latestStatus;

            return [
                'request_id' => $requester->RID,
                'requested_stock_item' => optional($requester->requestedStock)->description ?? 'N/A',
                'requester_stock_item' => optional($requester->requesterStock)->description ?? 'N/A',
                'status' => ($status && $status->status_id == 3) ? 'Delivered' : 'Not Delivered',
                'expected_delivery' => $requester->expected_date ?? 'N/A',
                'actual_delivery' => $status->updated_at ?? 'Not Delivered Yet',
            ];
        });
    }


    private function requestConsumptionDetails($request)
    {
        $query = LogRequester::query()
            ->with(['latestStatus', 'requestedStock', 'requesterStock'])
            ->leftJoin('request_status', 'logs_requesters.id', '=', 'request_status.request_id')
            ->where('logs_requesters.requester_rig_id', auth()->user()->rig_id)
            ->orWhere('logs_requesters.supplier_rig_id', auth()->user()->rig_id);

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('logs_requesters.created_at', [$request->from_date, $request->to_date]);
        }

        return $query->select(
            'logs_requesters.id as request_id',
            'logs_requesters.available_qty as initial_stock',
            'request_status.supplier_qty as received_stock',
            'request_status.supplier_used_spareable as used_stock',
            'request_status.supplier_new_spareable as remaining_stock'
        )->get()->map(function ($item) {
            return [
                'request_id' => $item->request_id,
                'requested_stock_item' => optional($item->requestedStock)->description ?? 'N/A',
                'requester_stock_item' => optional($item->requesterStock)->description ?? 'N/A',
                'initial_stock' => $item->initial_stock,
                'received_stock' => $item->received_stock ?? 0,
                'used_stock' => $item->used_stock ?? 0,
                'remaining_stock' => $item->remaining_stock ?? 0,
            ];
        });
    }
}
