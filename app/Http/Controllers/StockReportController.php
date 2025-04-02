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
        return view('reports.stock_reports', compact('moduleName',));
    }

    public function report_stock_filter(Request $request)
    {
        $moduleName = "Report Stock";
        $rig_id = Auth::user()->rig_id;
        if ($request->ajax()) {

            $data = Stock::query()
                ->when($request->form_date, function ($query) use ($request) {
                    return $query->whereDate('stocks.created_at', '>=', Carbon::parse($request->form_date)->startOfDay());
                })
                ->when($request->to_date, function ($query) use ($request) {
                    return $query->whereDate('stocks.created_at', '<=', Carbon::parse($request->to_date)->endOfDay());
                });

            $data = $data->join('edps', 'stocks.edp_code', '=', 'edps.id')
            ->join('rig_users', 'stocks.rig_id', '=', 'rig_users.id')
            ->select('stocks.*', 'edps.edp_code AS EDP_Code','rig_users.name')
            ->where('rig_id', $rig_id)
            ->get();

            return response()->json(['data' => $data]);
        }

      //  $data = Stock::all();
     //   $stockData = Stock::select('edp_code')->distinct()->get();
       // return view('user.stock.list_stock', compact('data', 'moduleName', 'stockData'));
    }
}
