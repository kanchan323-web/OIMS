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
        return view('reports.request_reports', compact('moduleName',));
    }
}
