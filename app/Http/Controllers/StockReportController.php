<?php

namespace App\Http\Controllers;

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

class StockReportController extends Controller
{
    public function index(Request $request)
    {
        $moduleName = "Stock Reports";
        $userId = Auth::id();
        $rig_id = Auth::user()->rig_id;
        return view('reports.stock.stock_reports', compact('moduleName',));
    }

    public function report_stock_filter(Request $request)
    {
        $moduleName = "Report Stock";

        $data = array(
            'report_type' => $request->report_type,
            'from_date' => $request->form_date,
            'to_date' => $request->to_date,
        );

        $response = $this->stock_common_filter($data);
        return response()->json(['data' => $response]);
    }

    private function stock_common_filter($data){
       // dd($data);

        $rig_id = Auth::user()->rig_id;
        $query = Stock::query();

        if($data['report_type']==2){

            if (!empty($data['from_date']) || !empty($data['to_date'])) {
                $query->whereBetween('stocks.created_at', [$data['from_date'], $data['to_date']]);
            }
            $get_data = $query->join('edps', 'stocks.edp_code', '=', 'edps.id')
            ->join('rig_users', 'stocks.rig_id', '=', 'rig_users.id')
            ->join('requesters', 'stocks.id', '=', 'requesters.requester_stock_id')
            ->join('request_status', 'requesters.id', '=', 'request_status.request_id')
            ->select('edps.edp_code AS EDP_Code','rig_users.name','requesters.requested_qty','request_status.created_at')
            ->where('rig_users.id', $rig_id)
            ->where('request_status.status_id', 3)
            ->get();

            return  $get_data;
        }elseif($data['report_type']==3){

        }elseif($data['report_type']==4){

        }else{

            if (!empty($data['from_date']) || !empty($data['to_date'])) {
                $query->whereBetween('stocks.created_at', [$data['from_date'], $data['to_date']]);
            }
            $get_data = $query->join('edps', 'stocks.edp_code', '=', 'edps.id')
            ->join('rig_users', 'stocks.rig_id', '=', 'rig_users.id')
            ->select('stocks.*', 'edps.edp_code AS EDP_Code','rig_users.name')
            ->where('rig_id', $rig_id)
            ->get();
        }
        return $get_data;

    }

    public function stockPdfDownload(Request $request){
        $moduleName = "Download Stock Report";

        $data = array(
            'report_type' => $request->report_type,
            'from_date' => $request->form_date,
            'to_date' => $request->to_date,
        );

       $stockData = $this->stock_common_filter($data);

        // Generate PDF with retrieved data
        $pdf = PDF::loadView('pdf.report.stock.stock_report', compact('stockData'));

        return $pdf->download('Stock_Report.pdf');
    }

    public function stockExcelDownload(Request $request){
        $data = array(
            'report_type' => $request->report_type,
            'from_date' => $request->form_date,
            'to_date' => $request->to_date,
        );
        $spreadsheet = new Spreadsheet();

        // Get the active sheet
        $sheet = $spreadsheet->getActiveSheet();

        // Set the title of the spreadsheet
        $sheet->setTitle('Stock Overview Report');

        // Set headers for the spreadsheet
        $sheet->setCellValue('A1', 'Sr.No');
        $sheet->setCellValue('B1', 'EDP Code');
        $sheet->setCellValue('C1', 'Section');
        $sheet->setCellValue('D1', 'Description');
        $sheet->setCellValue('E1', 'Total Qty');
        $sheet->setCellValue('F1', 'Available Qty');
        $sheet->setCellValue('G1', 'Date');
        $stockDatas = $this->stock_common_filter($data);

        $row = 2; // Start from the second row to leave space for headers
        $i=1;
        foreach ($stockDatas as $stockData) {
            $sheet->setCellValue('A' . $row, $i);
            $sheet->setCellValue('B' . $row, $stockData->EDP_Code);
            $sheet->setCellValue('C' . $row, $stockData->section);
            $sheet->setCellValue('D' . $row, $stockData->description);
            $sheet->setCellValue('E' . $row, $stockData->initial_qty);
            $sheet->setCellValue('F' . $row, $stockData->qty);
            $sheet->setCellValue('G' . $row, $stockData->created_at);
            $row++;
            $i++;
        }
        $writer = new Xlsx($spreadsheet);

        // Set the correct headers for downloading an Excel file
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Stock_Report.xlsx"');
        header('Cache-Control: max-age=0');

        // Write the Excel file to PHP output
        $writer->save('php://output');
       // dd($data);
    }

}
