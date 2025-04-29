<?php

namespace App\Http\Controllers;

use App\Models\LogsStocks;
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
use App\Models\Requester;

class StockReportController extends Controller
{
    public function index(Request $request){
        $moduleName = "Stock Reports";
        return view('reports.stock.stock_reports', compact('moduleName',));
    }

    public function report_stock_filter(Request $request){
        $reportType = $request->input('report_type');
        if (!$reportType) {
            return response()->json(['error' => 'Missing report type'], 400);
        }
        switch ($reportType) {
            case 'overview':
                $data = $this->stockOverview($request);
                break;
            case 'stock_receiver':
                $data = $this->stockAdditions($request);
                break;
            case 'stock_issuer':
                $data = $this->stockRemovals($request);
                break;
            case 'transaction_history':
                $data = $this->transactionHistory($request);
                break;
            default:
                return response()->json(['error' => 'Invalid report type'], 400);
        }
        return response()->json(['data' => $data ?? []]);
    }

    private function stockOverview($request){
        $rig_id = Auth::user()->rig_id;
        $query = Stock::query();
    
        $from = $request->input('form_date');
        $to = $request->input('to_date');
    
        if ($from && $to) {
            $query->whereBetween('stocks.updated_at', [$from, $to]);
        } elseif ($from) {
            $query->whereDate('stocks.updated_at', '>=', $from);
        } elseif ($to) {
            $query->whereDate('stocks.updated_at', '<=', $to);
        }
    
        $stock_overview = $query->join('edps', 'stocks.edp_code', '=', 'edps.id')
            ->join('rig_users', 'stocks.rig_id', '=', 'rig_users.id')
            ->select(
                'stocks.*',
                'edps.edp_code AS EDP_Code',
                'rig_users.name',
                DB::raw("DATE_FORMAT(stocks.updated_at, '%d-%m-%Y') as date")
            )
            ->where('stocks.rig_id', $rig_id)
            ->orderBy('stocks.id', 'desc')
            ->get();
    
        return $stock_overview;
    }

    private function stockAdditions($request){
        $rig_id = Auth::user()->rig_id;
        $query = Stock::query();
        $from = $request->input('form_date');
        $to = $request->input('to_date');

        if ($from && $to) {
            $query->whereBetween('stocks.updated_at', [$from, $to]);
        } elseif ($from) {
            $query->whereDate('stocks.updated_at', '>=', $from);
        } elseif ($to) {
            $query->whereDate('stocks.updated_at', '<=', $to);
        }

        $stock_addition = $query ->join('requesters', 'stocks.id', '=', 'requesters.requester_stock_id')
        ->join('edps', 'stocks.edp_code', '=', 'edps.id')
        ->join('rig_users', 'requesters.supplier_rig_id', '=', 'rig_users.id')
        ->join('request_status', 'requesters.id', '=', 'request_status.request_id')
        ->select('edps.edp_code AS EDP_Code','edps.description','rig_users.name','stocks.qty','requesters.requested_qty','requesters.RID',
           DB::raw("DATE_FORMAT(request_status.updated_at, '%d-%m-%Y') as receipt_date"))
        ->where('requesters.requester_rig_id', $rig_id)
        ->where('request_status.status_id', 3)
        ->orderBy('requesters.updated_at', 'desc')
        ->get();
        return $stock_addition;
    }
    private function stockRemovals($request){
        $rig_id = Auth::user()->rig_id;
        $query = Stock::query();
        $from = $request->input('form_date');
        $to = $request->input('to_date');

        if ($from && $to) {
            $query->whereBetween('stocks.updated_at', [$from, $to]);
        } elseif ($from) {
            $query->whereDate('stocks.updated_at', '>=', $from);
        } elseif ($to) {
            $query->whereDate('stocks.updated_at', '<=', $to);
        }
        $stock_removal = $query ->join('requesters', 'stocks.id', '=', 'requesters.stock_id')
        ->join('edps', 'stocks.edp_code', '=', 'edps.id')
        ->join('rig_users', 'requesters.requester_rig_id', '=', 'rig_users.id')
        ->join('request_status', 'requesters.id', '=', 'request_status.request_id')
        ->select('edps.edp_code AS EDP_Code','edps.description','rig_users.name','stocks.qty','requesters.requested_qty','requesters.RID',
         DB::raw("DATE_FORMAT(request_status.updated_at, '%d-%m-%Y') as issued_date"),
         'request_status.supplier_new_spareable','request_status.supplier_used_spareable')
        ->where('requesters.supplier_rig_id', $rig_id)
        ->where('request_status.status_id', 3)
        ->orderBy('requesters.updated_at', 'desc')
        ->get();
        return $stock_removal;
    }

    public function stockPdfDownload(Request $request){
        $reportType = $request->input('report_type');
        $fileName='';
        if (!$reportType) {
            return response()->json(['error' => 'Missing report type'], 400);
        }

        switch ($reportType) {
            case 'overview':
                $data = $this->stockOverview($request);
                $fileName = 'Stock Overview Report.pdf';
                break;
            case 'stock_receiver':
                $data = $this->stockAdditions($request);
                $fileName = 'Stock Received Report.pdf';
                break;
            case 'stock_issuer':
                $data = $this->stockRemovals($request);
                $fileName = 'Stock Issued Report.pdf';
                break;
            default:
                $data = json(['error' => 'Invalid report type'], 400);
        }
      // return view('pdf.report.stock.stock_report', compact('data','request'));
        // Generate PDF with retrieved data
        $pdf = PDF::loadView('pdf.report.stock.stock_report', compact('data','request'));
        //return $pdf->download('Stock_Report.pdf');
        return $pdf->download($fileName);
    }

    public function stockExcelDownload(Request $request){
        $data = array(
            'report_type' => $request->report_type,
            'from_date' => $request->form_date,
            'to_date' => $request->to_date,
        );
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $filename='';

        $reportType = $data['report_type'];
        switch ($reportType) {
            case 'overview':
                    $sheet->setTitle('Stock Overview Report');
                    $sheet->setCellValue('A1', 'Sr.No');
                    $sheet->setCellValue('B1', 'EDP Code');
                    $sheet->setCellValue('C1', 'Section');
                    $sheet->setCellValue('D1', 'Description');
                    $sheet->setCellValue('F1', 'Total Qty');
                    $sheet->setCellValue('G1', 'Creation Date');
                    $stockDatas = $this->stockOverview($data);

                    $row = 2; // Start from the second row to leave space for headers
                    $i=1;
                    foreach ($stockDatas as $stockData) {
                        $sheet->setCellValue('A' . $row, $i);
                        $sheet->setCellValue('B' . $row, $stockData->EDP_Code);
                        $sheet->setCellValue('C' . $row, $stockData->section);
                        $sheet->setCellValue('D' . $row, $stockData->description);
                        $sheet->setCellValue('F' . $row, $stockData->qty);
                        $sheet->setCellValue('G' . $row, $stockData->creation_date);
                        $row++;
                        $i++;
                    }
                $filename='Stock Overview Report';
            break;
            case 'stock_receiver':
                    $sheet->setTitle('Stock Received Report');
                    $sheet->setCellValue('A1', 'Sr.No');
                    $sheet->setCellValue('B1', 'Request ID');
                    $sheet->setCellValue('C1', 'EDP Code');
                    $sheet->setCellValue('D1', 'Description');
                    $sheet->setCellValue('E1', 'Received QTY');
                    $sheet->setCellValue('F1', 'Supplier Rig');
                    $sheet->setCellValue('G1', 'Receipt Date');
                    $stockDatas = $this->stockAdditions($data);

                    $row = 2; // Start from the second row to leave space for headers
                    $i=1;
                    foreach ($stockDatas as $stockData) {
                        $sheet->setCellValue('A' . $row, $i);
                        $sheet->setCellValue('B' . $row, $stockData->RID);
                        $sheet->setCellValue('C' . $row, $stockData->EDP_Code);
                        $sheet->setCellValue('D' . $row, $stockData->description);
                        $sheet->setCellValue('E' . $row, $stockData->requested_qty);
                        $sheet->setCellValue('F' . $row, $stockData->name);
                        $sheet->setCellValue('G' . $row, $stockData->receipt_date);
                        $row++;
                        $i++;
                    }
                    $filename='Stock Received Report';
                break;
            case 'stock_issuer':
                    $sheet->setTitle('Stock Issued Report');
                    $sheet->setCellValue('A1', 'Sr.No');
                    $sheet->setCellValue('B1', 'Request ID');
                    $sheet->setCellValue('C1', 'EDP Code');
                    $sheet->setCellValue('D1', 'Description');
                    $sheet->setCellValue('E1', 'Issued QTY');
                    $sheet->setCellValue('F1', 'Receiver Rig');
                    $sheet->setCellValue('G1', 'Issued Date');
                    $stockDatas = $this->stockRemovals($data);

                    $row = 2; // Start from the second row to leave space for headers
                    $i=1;
                    foreach ($stockDatas as $stockData) {
                        $sheet->setCellValue('A' . $row, $i);
                        $sheet->setCellValue('B' . $row, $stockData->RID);
                        $sheet->setCellValue('C' . $row, $stockData->EDP_Code);
                        $sheet->setCellValue('D' . $row, $stockData->description);
                        $sheet->setCellValue('E' . $row, $stockData->requested_qty);
                        $sheet->setCellValue('F' . $row, $stockData->name);
                        $sheet->setCellValue('G' . $row, $stockData->issued_date);
                        $row++;
                        $i++;
                    }
                    $filename='Stock Issued Report';
                break;
            default:
                return response()->json(['error' => 'Invalid report type'], 400);
        }

        $writer = new Xlsx($spreadsheet);

        // Set the correct headers for downloading an Excel file
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
       // header('Content-Disposition: attachment;filename="Stock_Reportff.xlsx"');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }


    public function transactionHistory(Request $request)
    {
        $fromDate = $request->input('form_date');
        $toDate = $request->input('to_date');
    
        $query = LogsStocks::query()
            ->leftJoin('edps', 'logs_stocks.edp_code', '=', 'edps.id')
            ->when($fromDate, function ($q) use ($fromDate) {
                $q->whereDate('logs_stocks.updated_at', '>=', $fromDate);
            })
            ->when($toDate, function ($q) use ($toDate) {
                $q->whereDate('logs_stocks.updated_at', '<=', $toDate);
            })
            ->select([
                'logs_stocks.*',
                'logs_stocks.qty as Quantity',
                'edps.edp_code as EDP_Code',
                DB::raw("DATE_FORMAT(logs_stocks.updated_at, '%d-%m-%Y') as updated_at_formatted")
            ])
            ->get(); // Only call get() once here
    
        return $query;
    }
    

}
