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
    public function index(Request $request){
        $moduleName = "Stock Reports";
        return view('admin.reports.stock.stock_reports', compact('moduleName',));
    }

    public function report_stock_filter(Request $request){
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
            default:
                return response()->json(['error' => 'Invalid report type'], 400);
        }
        return response()->json(['data' => $data ?? []]);
    }

    private function stockSummary($request){
        $query = Stock::query();
        if(!empty($request->from_date) || !empty($request->to_date)) {
            $query->whereBetween('stocks.created_at', [$request->from_date , $request->to_date]);
        }
        $stock_summary = $query->join('edps', 'stocks.edp_code', '=', 'edps.id')
            ->select('stocks.*', 'edps.edp_code AS EDP_Code')->orderBy('id', 'desc')->get();
        return $stock_summary;
    }

    private function stockAdditions($request){
        $query = Stock::query();
        if(!empty($request->from_date) || !empty($request->to_date)) {
            $query->whereBetween('stocks.created_at', [$request->from_date , $request->to_date]);
        }
        $stock_addition = $query ->join('requesters', 'stocks.id', '=', 'requesters.requester_stock_id')
        ->join('edps', 'stocks.edp_code', '=', 'edps.id')
        ->join('rig_users', 'requesters.supplier_rig_id', '=', 'rig_users.id')
        ->join('request_status', 'requesters.id', '=', 'request_status.request_id')
        ->select('edps.edp_code AS EDP_Code','edps.description','rig_users.name','stocks.qty','requesters.requested_qty','request_status.created_at','stocks.location_name')
        ->where('request_status.status_id', 3)
        ->get();
        return $stock_addition;
    }
    private function stockRemovals($request){
        $query = Stock::query();
        if(!empty($request->from_date) || !empty($request->to_date)) {
            $query->whereBetween('stocks.created_at', [$request->from_date , $request->to_date]);
        }
        $stock_removal = $query ->join('requesters', 'stocks.id', '=', 'requesters.stock_id')
            ->join('edps', 'stocks.edp_code', '=', 'edps.id')
            ->join('rig_users', 'requesters.requester_rig_id', '=', 'rig_users.id')
            ->join('request_status', 'requesters.id', '=', 'request_status.request_id')
            ->select('edps.edp_code AS EDP_Code','edps.description','rig_users.name','stocks.qty','requesters.requested_qty','request_status.created_at','request_status.supplier_new_spareable','request_status.supplier_used_spareable','stocks.location_name')
            ->where('request_status.status_id', 3)
            ->get();
        return $stock_removal;
    }
    private function stockAdjustments($request){
        $query = Requester::query();
        $stock_adjustments = $query ->join('stocks', 'requesters.stock_id', '=', 'stocks.id')
        ->join('stocks as req', 'requesters.requester_stock_id', '=', 'req.id')
        ->join('edps', 'stocks.edp_code', '=', 'edps.id')
        ->join('rig_users', 'requesters.requester_rig_id', '=', 'rig_users.id')
        ->join('request_status', 'requesters.id', '=', 'request_status.request_id')
        ->select('edps.edp_code AS EDP_Code','edps.description','rig_users.name as req_name','requesters.requested_qty','request_status.supplier_qty',
          DB::raw("DATE_FORMAT(request_status.updated_at, '%d-%m-%Y') as date"),'stocks.location_name as sup_name','requesters.RID')
        ->when($request->from_date, function ($query) use ($request) {
            return $query->whereDate('requesters.updated_at', '>=', Carbon::parse($request->from_date)->startOfDay());
        })
        ->when($request->to_date, function ($query) use ($request) {
            return $query->whereDate('requesters.updated_at', '<=', Carbon::parse($request->to_date)->endOfDay());
        })
        ->where('request_status.status_id', 3)
        ->orderBy('requesters.updated_at', 'desc')
        ->get();
        return $stock_adjustments;
    }


    private function stockConsumptions($request){
        $query = Stock::query();
        if(!empty($request->from_date) || !empty($request->to_date)) {
            $query->whereBetween('stocks.created_at', [$request->from_date , $request->to_date]);
        }
        $stock_consumption = $query ->join('requesters', 'stocks.id', '=', 'requesters.stock_id')
        ->join('edps', 'stocks.edp_code', '=', 'edps.id')
        ->join('rig_users', 'requesters.requester_rig_id', '=', 'rig_users.id')
        ->join('request_status', 'requesters.id', '=', 'request_status.request_id')
        ->select('edps.edp_code AS EDP_Code','edps.description','rig_users.name','requesters.requested_qty as consume','stocks.qty as avl_qty','request_status.created_at',
                'request_status.supplier_new_spareable','request_status.supplier_used_spareable')
        ->where('request_status.status_id', 3)
        ->get();
        return $stock_consumption;
    }
}
