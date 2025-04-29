<?php

namespace App\Http\Controllers;

use App\Models\LogRequester;
use App\Models\LogsStocks;
use App\Models\Requester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Stock;
use App\Models\Edp;
use App\Models\User;
use App\Models\RigUser;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
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
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        // Directly fetch Transaction History
        $data = $this->transactionHistory($fromDate, $toDate);

        return response()->json(['data' => $data ?? []]);
    }



    // private function requestSummary($request)
    // {
    //     $query = Requester::query()
    //         ->where('requesters.requester_rig_id', auth()->user()->rig_id)
    //         ->orWhere('requesters.supplier_rig_id', auth()->user()->rig_id);

    //     if ($request->filled('from_date') && $request->filled('to_date')) {
    //         $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
    //     }

    //     return [
    //         'total_requests' => $query->count(),
    //         'approved' => $query->where('status', 4)->count(),
    //         'declined' => $query->where('status', 5)->count(),
    //         'pending' => $query->where('status', 1)->count(),
    //     ];
    // }


    // private function approvalDeclineRates($request)
    // {
    //     $query = Requester::query()
    //         ->where('requesters.requester_rig_id', auth()->user()->rig_id)
    //         ->orWhere('requesters.supplier_rig_id', auth()->user()->rig_id);

    //     if ($request->filled('from_date') && $request->filled('to_date')) {
    //         $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
    //     }

    //     $total = $query->count();

    //     $approvedCount = (clone $query)->where('status', 4)->count();
    //     $declinedCount = (clone $query)->where('status', 5)->count();
    //     $pendingCount = (clone $query)->where('status', 1)->count();


    //     return [
    //         'approval_rate' => $total > 0 ? round(($approvedCount / $total) * 100, 2) : 0,
    //         'decline_rate' => $total > 0 ? round(($declinedCount / $total) * 100, 2) : 0,
    //         'pending_rate' => $total > 0 ? round(($pendingCount / $total) * 100, 2) : 0,
    //     ];
    // }


    private function transactionHistory($fromDate, $toDate)
    {
        $query = LogsStocks::query()
            ->leftJoin('edps', 'logs_stocks.edp_code', '=', 'edps.id')
            ->when($fromDate, fn($q) => $q->whereDate('logs_stocks.updated_at', '>=', $fromDate))
            ->when($toDate, fn($q) => $q->whereDate('logs_stocks.updated_at', '<=', $toDate))
            ->select([
                'logs_stocks.*',
                'edps.edp_code as EDP_Code',
                DB::raw("DATE_FORMAT(logs_stocks.updated_at, '%d-%m-%Y') as updated_at")
            ])->get();
            dd($query);
        return $query->get();
    }





    // private function requestFulfillmentStatus($request)
    // {
    //     $query = Requester::with(['latestStatus', 'requestedStock', 'requesterStock'])
    //         ->where('requesters.requester_rig_id', auth()->user()->rig_id)
    //         ->orWhere('requesters.supplier_rig_id', auth()->user()->rig_id);

    //     if ($request->filled('from_date') && $request->filled('to_date')) {
    //         $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
    //     }

    //     return $query->get()->map(function ($requester) {
    //         $status = $requester->latestStatus;

    //         return [
    //             'request_id' => $requester->RID,
    //             'requested_stock_item' => optional($requester->requestedStock)->description ?? 'N/A',
    //             'requester_stock_item' => optional($requester->requesterStock)->description ?? 'N/A',
    //             'status' => ($status && $status->status_id == 3) ? 'Delivered' : 'Not Delivered',
    //             'expected_delivery' => $requester->expected_date ?? 'N/A',
    //             'actual_delivery' => $status->updated_at ?? 'Not Delivered Yet',
    //         ];
    //     });
    // }


    // private function requestConsumptionDetails($request)
    // {
    //     $query = LogRequester::query()
    //         ->with(['latestStatus', 'requestedStock', 'requesterStock'])
    //         ->leftJoin('request_status', 'logs_requesters.id', '=', 'request_status.request_id')
    //         ->where('logs_requesters.requester_rig_id', auth()->user()->rig_id)
    //         ->orWhere('logs_requesters.supplier_rig_id', auth()->user()->rig_id);

    //     if ($request->filled('from_date') && $request->filled('to_date')) {
    //         $query->whereBetween('logs_requesters.created_at', [$request->from_date, $request->to_date]);
    //     }

    //     return $query->select(
    //         'logs_requesters.RID as request_id',
    //         'logs_requesters.available_qty as initial_stock',
    //         'request_status.supplier_qty as received_stock',
    //         'request_status.supplier_used_spareable as used_stock',
    //         'request_status.supplier_new_spareable as remaining_stock'
    //     )->get()->map(function ($item) {
    //         return [
    //             'request_id' => $item->request_id,
    //             'requested_stock_item' => optional($item->requestedStock)->description ?? 'N/A',
    //             'requester_stock_item' => optional($item->requesterStock)->description ?? 'N/A',
    //             'initial_stock' => $item->initial_stock,
    //             'received_stock' => $item->received_stock ?? 0,
    //             'used_stock' => $item->used_stock ?? 0,
    //             'remaining_stock' => $item->remaining_stock ?? 0,
    //         ];
    //     });
    // }

    // public function requestPdfDownload(Request $request)
    // {
    //     $reportType = $request->input('report_type');
    //     if (!$reportType) {
    //         return response()->json(['error' => 'Missing report type'], 400);
    //     }

    //     switch ($reportType) {
    //         case 'summary':
    //             $data = $this->requestSummary($request);
    //             break;
    //         case 'approval_rates':
    //             $data = $this->approvalDeclineRates($request);
    //             break;
    //         case 'transaction_history':
    //             $data = $this->transactionHistory($request);
    //             break;
    //         case 'fulfillment_status':
    //             $data = $this->requestFulfillmentStatus($request);
    //             break;
    //         case 'consumption_details':
    //             $data = $this->requestConsumptionDetails($request);
    //             break;
    //         default:
    //             $data = json(['error' => 'Invalid report type'], 400);
    //     }
    //     // return $data;
    //     // return view('pdf.report.request.request_report', compact('data','request'));
    //     // Generate PDF with retrieved data
    //     $pdf = PDF::loadView('pdf.report.request.request_report', compact('data', 'request'));
    //     return $pdf->download('RequestStock_Report.pdf');
    // }

    // public function requestExcelDownload(Request $request)
    // {
    //     $reportType = $request->input('report_type');
    //     if (!$reportType) {
    //         return response()->json(['error' => 'Missing report type'], 400);
    //     }

    //     $spreadsheet = new Spreadsheet();
    //     $sheet = $spreadsheet->getActiveSheet();

    //     switch ($reportType) {
    //         case 'summary':
    //             $data = $this->requestSummary($request);
    //             $sheet->setTitle('Summary Report');
    //             $sheet->setCellValue('A1', 'Sr.No');
    //             $sheet->setCellValue('B1', 'Total Requests');
    //             $sheet->setCellValue('C1', 'Approved');
    //             $sheet->setCellValue('D1', 'Declined');
    //             $sheet->setCellValue('E1', 'Pending');
    //             $sheet->setCellValue('A2', 1);
    //             $sheet->setCellValue('B2', $data['total_requests']);
    //             $sheet->setCellValue('C2', $data['approved']);
    //             $sheet->setCellValue('D2', $data['declined']);
    //             $sheet->setCellValue('E2', $data['pending']);
    //             break;
    //         case 'approval_rates':
    //             $data = $this->approvalDeclineRates($request);
    //             $sheet->setTitle('Approval Rates');
    //             $sheet->setCellValue('A1', 'Sr.No');
    //             $sheet->setCellValue('B1', 'Approval Rate (%)');
    //             $sheet->setCellValue('C1', 'Decline Rate (%)');
    //             $sheet->setCellValue('D1', 'Pending (%)');
    //             $sheet->setCellValue('A2', 1);
    //             $sheet->setCellValue('B2', $data['approval_rate']);
    //             $sheet->setCellValue('C2', $data['decline_rate']);
    //             $sheet->setCellValue('D2', $data['pending_rate']);
    //             break;
    //         case 'transaction_history':
    //             $stockDatas = $this->transactionHistory($request);
    //             $sheet->setTitle('Transaction History');
    //             $sheet->setCellValue('A1', 'Sr.No');
    //             $sheet->setCellValue('B1', 'Quantity');
    //             $sheet->setCellValue('C1', 'Status');
    //             $sheet->setCellValue('D1', 'Processed By');
    //             $sheet->setCellValue('E1', 'Rig Name');
    //             $sheet->setCellValue('F1', 'Created At');
    //             $row = 2;
    //             $i = 1;
    //             foreach ($stockDatas as $stockData) {
    //                 $sheet->setCellValue('A' . $row, $i);
    //                 $sheet->setCellValue('B' . $row, $stockData->supplier_qty);
    //                 $sheet->setCellValue('C' . $row, $stockData->status_id);
    //                 $sheet->setCellValue('D' . $row, $stockData->processed_by_name);
    //                 $sheet->setCellValue('E' . $row, $stockData->rig_name);
    //                 $sheet->setCellValue('F' . $row, $stockData->created_at);
    //                 $row++;
    //                 $i++;
    //             }
    //             break;
    //         case 'fulfillment_status':
    //             $data = $this->requestFulfillmentStatus($request);
    //             $sheet->setTitle('Fulfillment Status');
    //             $sheet->setCellValue('A1', 'Sr.No');
    //             $sheet->setCellValue('B1', 'Request ID');
    //             $sheet->setCellValue('C1', 'Requested Stock');
    //             $sheet->setCellValue('D1', 'Requesters Stock');
    //             $sheet->setCellValue('E1', 'Status');
    //             $sheet->setCellValue('F1', 'Created At');
    //             $row = 2;
    //             $i = 1;
    //             foreach ($data as $stockData) {
    //                 $sheet->setCellValue('A' . $row, $i);
    //                 $sheet->setCellValue('B' . $row, $stockData['request_id']);
    //                 $sheet->setCellValue('C' . $row, $stockData['requested_stock_item']);
    //                 $sheet->setCellValue('D' . $row, $stockData['requester_stock_item']);
    //                 $sheet->setCellValue('E' . $row, $stockData['status']);
    //                 $sheet->setCellValue('F' . $row, $stockData['expected_delivery']);
    //                 $sheet->setCellValue('F' . $row, $stockData['actual_delivery']);
    //                 $row++;
    //                 $i++;
    //             }
    //             break;
    //         case 'consumption_details':
    //             $data = $this->requestConsumptionDetails($request);
    //             $sheet->setTitle('Consumption Details');
    //             $sheet->setCellValue('A1', 'Sr.No');
    //             $sheet->setCellValue('B1', 'Request ID');
    //             $sheet->setCellValue('C1', 'Requested Stock Item');
    //             $sheet->setCellValue('D1', 'Requester Stock Item');
    //             $sheet->setCellValue('E1', 'Initial Stock');
    //             $sheet->setCellValue('F1', 'Received Stock At');
    //             $sheet->setCellValue('G1', 'Used Stock');
    //             $sheet->setCellValue('H1', 'Remaining Stock');
    //             $row = 2;
    //             $i = 1;
    //             foreach ($data as $stockData) {
    //                 $sheet->setCellValue('A' . $row, $i);
    //                 $sheet->setCellValue('B' . $row, $stockData['request_id']);
    //                 $sheet->setCellValue('C' . $row, $stockData['requested_stock_item']);
    //                 $sheet->setCellValue('D' . $row, $stockData['requester_stock_item']);
    //                 $sheet->setCellValue('E' . $row, $stockData['initial_stock']);
    //                 $sheet->setCellValue('F' . $row, $stockData['received_stock']);
    //                 $sheet->setCellValue('G' . $row, $stockData['used_stock']);
    //                 $sheet->setCellValue('H' . $row, $stockData['used_stock']);
    //                 $row++;
    //                 $i++;
    //             }
    //             break;
    //         default:
    //             $data = json(['error' => 'Invalid report type'], 400);
    //     }

    //     $writer = new Xlsx($spreadsheet);

    //     // Set the correct headers for downloading an Excel file
    //     header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    //     header('Content-Disposition: attachment;filename="Request_Report.xlsx"');
    //     header('Cache-Control: max-age=0');

    //     $writer->save('php://output');
    // }
}
