<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Stock;
use App\Models\Requester;
use App\Models\Edp;
use App\Models\User;
use App\Models\RigUser;
use App\Models\LogsStocks;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use App\Models\RequestStock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class StockReportController extends Controller
{
    public function index(Request $request)
    {
        $moduleName = "Stock Reports";

        // Step 1: Get unique edp_code IDs from stocks
        $stockEdpIds = Stock::select('edp_code')
            ->distinct()
            ->pluck('edp_code');

        // Step 2: Use those IDs to fetch edp_code values from edps table
        $edpCodes = Edp::whereIn('id', $stockEdpIds)
            ->select('id as edp_id', 'edp_code')
            ->get();

        $receivers = RigUser::where('name', '!=', 'admin')->get();

        $suppliers =  RigUser::where('name', '!=', 'admin')->get();

        $RIDList = Requester::select('RID')->distinct()->get();

        return view('admin.reports.stock.stock_reports', compact('moduleName', 'edpCodes', 'receivers', 'suppliers', 'RIDList'));
    }




    public function report_stock_filter(Request $request)
    {
        $reportType = $request->input('report_type');
        if (!$reportType) {
            return response()->json(['error' => 'Missing report type'], 400);
        }
        switch ($reportType) {
            case 'summary':
                $data = $this->stockSummary($request);
                break;
            case 'additions':
                $data = $this->stockAdditions($request);
                break;
            case 'removals':
                $data = $this->stockRemovals($request);
                break;
            case 'adjustments':
                $data = $this->stockAdjustments($request);
                break;
            case 'consumptions':
                $data = $this->stockConsumptions($request);
                break;
            case 'transaction_history':
                $data = $this->transactionHistory($request);
                break;
            default:
                return response()->json(['error' => 'Invalid report type'], 400);
        }
        return response()->json(['data' => $data ?? []]);
    }

    private function stockSummary($request)
    {
        $query = Stock::query();
        if (!empty($request->from_date) || !empty($request->to_date)) {
            $query->whereBetween('stocks.created_at', [$request->from_date, $request->to_date]);
        }
        $stock_summary = $query->join('edps', 'stocks.edp_code', '=', 'edps.id')
            ->select('stocks.*', 'edps.edp_code AS EDP_Code')->orderBy('id', 'desc')->get();
        return $stock_summary;
    }

    private function stockAdditions($request)
    {
        $query = Stock::query();
        if (!empty($request->from_date) || !empty($request->to_date)) {
            $query->whereBetween('stocks.created_at', [$request->from_date, $request->to_date]);
        }
        $stock_addition = $query->join('requesters', 'stocks.id', '=', 'requesters.requester_stock_id')
            ->join('edps', 'stocks.edp_code', '=', 'edps.id')
            ->join('rig_users', 'requesters.supplier_rig_id', '=', 'rig_users.id')
            ->join('request_status', 'requesters.id', '=', 'request_status.request_id')
            ->select('edps.edp_code AS EDP_Code', 'edps.description', 'rig_users.name', 'stocks.qty', 'requesters.requested_qty', 'request_status.created_at', 'stocks.location_name')
            ->where('request_status.status_id', 3)
            ->get();
        return $stock_addition;
    }
    private function stockRemovals($request)
    {
        $query = Stock::query();
        if (!empty($request->from_date) || !empty($request->to_date)) {
            $query->whereBetween('stocks.created_at', [$request->from_date, $request->to_date]);
        }
        $stock_removal = $query->join('requesters', 'stocks.id', '=', 'requesters.stock_id')
            ->join('edps', 'stocks.edp_code', '=', 'edps.id')
            ->join('rig_users', 'requesters.requester_rig_id', '=', 'rig_users.id')
            ->join('request_status', 'requesters.id', '=', 'request_status.request_id')
            ->select('edps.edp_code AS EDP_Code', 'edps.description', 'rig_users.name', 'stocks.qty', 'requesters.requested_qty', 'request_status.created_at', 'request_status.supplier_new_spareable', 'request_status.supplier_used_spareable', 'stocks.location_name')
            ->where('request_status.status_id', 3)
            ->get();
        return $stock_removal;
    }


    private function stockAdjustments($request)
    {
        $query = Requester::query();

        $stock_adjustments = $query
            ->join('stocks', 'requesters.stock_id', '=', 'stocks.id')
            ->join('stocks as req', 'requesters.requester_stock_id', '=', 'req.id')
            ->join('edps', 'stocks.edp_code', '=', 'edps.id')
            ->join('rig_users', 'requesters.requester_rig_id', '=', 'rig_users.id')
            ->join('request_status', 'requesters.id', '=', 'request_status.request_id')
            ->select(
                'edps.edp_code AS EDP_Code',
                'edps.description',
                'rig_users.name as req_name',
                'requesters.requested_qty',
                'request_status.supplier_qty',
                DB::raw("DATE_FORMAT(request_status.updated_at, '%d-%m-%Y') as date"),
                'stocks.location_name as sup_name',
                'requesters.RID'
            )

            ->when($request->from_date, function ($query) use ($request) {
                return $query->whereDate('requesters.updated_at', '>=', Carbon::parse($request->from_date)->startOfDay());
            })
            ->when($request->to_date, function ($query) use ($request) {
                return $query->whereDate('requesters.updated_at', '<=', Carbon::parse($request->to_date)->endOfDay());
            })

            ->when($request->edp_code, function ($query) use ($request) {
                return $query->where('edps.id', $request->edp_code);
            })

            ->when($request->receiver_id, function ($query) use ($request) {
                return $query->where('requesters.requester_rig_id', $request->receiver_id);
            })

            ->when($request->supplier_id, function ($query) use ($request) {
                return $query->where('requesters.supplier_id', $request->supplier_id);
            })
            ->when($request->rid, function ($query) use ($request) {
                return $query->where('requesters.RID', $request->rid);
            })

            ->where('request_status.status_id', 3)
            ->orderBy('requesters.updated_at', 'desc')
            ->get();

        return $stock_adjustments;
    }



    private function stockConsumptions($request)
    {
        $query = Stock::query();
        if (!empty($request->from_date) || !empty($request->to_date)) {
            $query->whereBetween('stocks.created_at', [$request->from_date, $request->to_date]);
        }
        $stock_consumption = $query->join('requesters', 'stocks.id', '=', 'requesters.stock_id')
            ->join('edps', 'stocks.edp_code', '=', 'edps.id')
            ->join('rig_users', 'requesters.requester_rig_id', '=', 'rig_users.id')
            ->join('request_status', 'requesters.id', '=', 'request_status.request_id')
            ->select(
                'edps.edp_code AS EDP_Code',
                'edps.description',
                'rig_users.name',
                'requesters.requested_qty as consume',
                'stocks.qty as avl_qty',
                'request_status.created_at',
                'request_status.supplier_new_spareable',
                'request_status.supplier_used_spareable'
            )
            ->where('request_status.status_id', 3)
            ->get();
        return $stock_consumption;
    }


    public function transactions(Request $request)
    {
        $moduleName = "Transaction Reports";

        $stockEdpIds = Stock::select('edp_code')->distinct()->pluck('edp_code');

        $edpCodes = Edp::whereIn('id', $stockEdpIds)
            ->select('id as edp_id', 'edp_code')
            ->get();

        $receivers = RigUser::where('name', '!=', 'admin')->get();
        $suppliers = RigUser::where('name', '!=', 'admin')->get();
        $RIDList = Requester::select('RID')->distinct()->get();

        return view('admin.reports.stock.transaction_reports', compact('moduleName', 'edpCodes', 'receivers', 'suppliers', 'RIDList'));
    }


    private function transactionHistory(Request $request)
    {
        $fromDate = $request->input('form_date');
        $toDate = $request->input('to_date');
        $rigId = $request->input('rig_id'); // Optional: fallback to auth user's rig_id
        $edpCode = $request->input('edp_code');
        $category = $request->input('category');
        $section = $request->input('section');
        $receiverId = $request->input('receiver_id');
        $supplierId = $request->input('supplier_id');
        $action = $request->input('action');

        $logs = LogsStocks::query()
            ->leftJoin('edps', 'logs_stocks.edp_code', '=', 'edps.id')
            ->leftJoin('rig_users as sender', 'logs_stocks.creater_id', '=', 'sender.id')
            ->leftJoin('rig_users as receiver', 'logs_stocks.receiver_id', '=', 'receiver.id')

            ->when($fromDate, fn($q) => $q->whereDate('logs_stocks.updated_at', '>=', $fromDate))
            ->when($toDate, fn($q) => $q->whereDate('logs_stocks.updated_at', '<=', $toDate))

            ->when($rigId, fn($q) => $q->where('logs_stocks.rig_id', $rigId))
            ->when($edpCode, fn($q) => $q->where('logs_stocks.edp_code', $edpCode))
            ->when($category, fn($q) => $q->where('edps.category', $category))
            ->when($section, fn($q) => $q->where('edps.section', $section))
            ->when($receiverId, fn($q) => $q->where('logs_stocks.receiver_id', $receiverId))
            ->when($supplierId, fn($q) => $q->where('logs_stocks.creater_id', $supplierId))
            ->when($action, fn($q) => $q->where('logs_stocks.action', $action))

            ->select([
                'logs_stocks.*',
                DB::raw('(logs_stocks.new_value + logs_stocks.used_value) as qty'),
                'edps.edp_code as EDP_Code',
                'edps.description',
                DB::raw("DATE_FORMAT(logs_stocks.updated_at, '%d-%m-%Y') as updated_at_formatted"),
                DB::raw("receiver.name as receiver"),
                DB::raw("sender.name as supplier")
            ])
            ->orderBy('logs_stocks.updated_at', 'desc')
            ->get();

        $enhancedLogs = [];

        foreach ($logs as $log) {
            $symbolNew = '';
            $symbolUsed = '';

            switch (strtolower($log->action)) {
                case 'added':
                    $symbolNew = '+';
                    $symbolUsed = '+';
                    break;

                case 'transfer':
                case 'transferred_to':
                    $symbolNew = '+';
                    $symbolUsed = '+';
                    break;

                case 'transferred_from':
                    $symbolNew = '-';
                    $symbolUsed = '-';
                    break;

                case 'modified':
                    $previous = LogsStocks::where('stock_id', $log->stock_id)
                        ->where('id', '<', $log->id)
                        ->orderByDesc('id')
                        ->first();

                    if ($previous) {
                        $symbolNew = ($log->new_spareable > $previous->new_spareable) ? '+' : (($log->new_spareable < $previous->new_spareable) ? '-' : '');

                        $symbolUsed = ($log->used_spareable > $previous->used_spareable) ? '+' : (($log->used_spareable < $previous->used_spareable) ? '-' : '');
                    } else {
                        $symbolNew = '+';
                        $symbolUsed = '+';
                    }
                    break;
            }

            $log->formatted_new_value = $symbolNew . ($log->new_value ?? 0);
            $log->formatted_used_value = $symbolUsed . ($log->used_value ?? 0);
            $enhancedLogs[] = $log;
        }

        return $enhancedLogs;
    }

    public function transfer_reportPdf(Request $request)
    {
        $stockData = $this->stockAdjustments($request);
        // Generate PDF with retrieved data
        $pdf = PDF::loadView('pdf.admin_reports.stock.stock_transfer_report', compact('stockData'));
        return $pdf->download('Admin_Stock_Transfer_Report.pdf');
    }

    
    public function transfer_reportExcel(Request $request){
        $stockDatas = $this->stockAdjustments($request);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Stock Transfer Report');
        $sheet->setCellValue('A1', 'Sr.No');
        $sheet->setCellValue('B1', 'Request ID');
        $sheet->setCellValue('C1', 'EDP Code');
        $sheet->setCellValue('D1', 'Description');
        $sheet->setCellValue('E1', 'Receiver');
        $sheet->setCellValue('F1', 'Reciept QTY');
        $sheet->setCellValue('G1', 'Supplier');
        $sheet->setCellValue('H1', 'Issued QTY');
        $sheet->setCellValue('I1', 'Date');
        $sheet->getStyle('A1:I1')->getFont()->setBold(true);
        $row = 2; // Start from the second row to leave space for headers
        $i = 1;
        foreach ($stockDatas as $stockData) {
            $sheet->setCellValue('A' . $row, $i);
            $sheet->setCellValue('B' . $row, $stockData->RID);
            $sheet->setCellValue('C' . $row, $stockData->EDP_Code);
            $sheet->setCellValue('D' . $row, $stockData->description);
            $sheet->setCellValue('E' . $row, $stockData->req_name);
            $sheet->setCellValue('F' . $row, IND_money_format($stockData->requested_qty ?? '0'));
            $sheet->setCellValue('G' . $row, $stockData->sup_name);
            $sheet->setCellValue('H' . $row, IND_money_format($stockData->supplier_qty ?? '0'));
            $sheet->setCellValue('I' . $row, $stockData->date);
            $row++;
            $i++;
        }
        $filename = 'Stock Transfer Report';
        $writer = new Xlsx($spreadsheet);
        // Set the correct headers for downloading an Excel file
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        // header('Content-Disposition: attachment;filename="Stock_Reportff.xlsx"');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    public function transaction_reportPdf(Request $request){
       //  dd($request->edp_code);
        $stockData = $this->transactionHistory($request);
        $stockData = collect($stockData);
        // Generate PDF with retrieved data
        $pdf = PDF::loadView('pdf.admin_reports.stock.stock_transaction_report', compact('stockData'))->setPaper('A4', 'landscape');
        return $pdf->download('Admin_Stock_Transaction_Report.pdf');
    }

    
    public function transaction_reportExcel(Request $request){
        $stockDatas = $this->transactionHistory($request);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Stock Transaction Report');
        $sheet->setCellValue('A1', 'Sr.No');
        $sheet->setCellValue('B1', 'EDP');
        $sheet->setCellValue('C1', 'Description');
        $sheet->setCellValue('D1', 'Change in New');
        $sheet->setCellValue('E1', 'Change in Used');
        $sheet->setCellValue('F1', 'Qty');
        $sheet->setCellValue('G1', 'Transaction');
        $sheet->setCellValue('H1', 'Reference ID');
        $sheet->setCellValue('I1', 'Transaction Date');
        $sheet->setCellValue('J1', 'Receiver');
        $sheet->setCellValue('K1', 'Supplier');
        $sheet->getStyle('A1:K1')->getFont()->setBold(true);
        $row = 2; // Start from the second row to leave space for headers
        $i = 1;
        foreach ($stockDatas as $stockData) {
            $sheet->setCellValue('A' . $row, $i);
            $sheet->setCellValue('B' . $row, $stockData->EDP_Code);
            $sheet->setCellValue('C' . $row, $stockData->description);
            $sheet->setCellValue('D' . $row, IND_money_format($stockData->formatted_new_value ?? '0'));
            $sheet->setCellValue('E' . $row, IND_money_format($stockData->formatted_used_value ?? '0'));
            $sheet->setCellValue('F' . $row, IND_money_format($stockData->qty ?? '0'));
            $sheet->setCellValue('G' . $row, $stockData->action ?? '-');
            $sheet->setCellValue('H' . $row, $stockData->reference_id ?? '-');
            $sheet->setCellValue('I' . $row, $stockData->updated_at_formatted ?? '-');
            $sheet->setCellValue('J' . $row, $stockData->receiver ?? '-');
            $sheet->setCellValue('K' . $row, $stockData->supplier ?? '-');
            $row++;
            $i++;
        }
        $filename = 'Stock Transaction Report';
        $writer = new Xlsx($spreadsheet);
        // Set the correct headers for downloading an Excel file
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        // header('Content-Disposition: attachment;filename="Stock_Reportff.xlsx"');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

}
