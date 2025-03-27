<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Requester;

class DashboardController extends Controller
{
    public function index(){
        //return view('user.dashboard');
        if (Auth::check()) {
    

            $rig_id = Auth::user()->rig_id;

            $countIncommingRequest =   Requester::select(
                'rig_users.name as Location_Name',
                'rig_users.location_id',
                'requesters.*',
                'mst_status.status_name',
                'stocks.id as stock_id',
                'stocks.id as stock_id',
                'edps.edp_code',
            )->join('rig_users', 'requesters.requester_rig_id', '=', 'rig_users.id')
                ->join('stocks', 'requesters.stock_id', '=', 'stocks.id')
                ->join('edps', 'stocks.edp_code', '=', 'edps.id')
                ->leftJoin('mst_status', 'requesters.status', '=', 'mst_status.id')
                ->where('requesters.supplier_rig_id', $rig_id)
                ->with('requestStatuses')
                ->distinct()
                ->with('requestStatuses')
                ->distinct()
                ->orderBy('requesters.created_at', 'desc')
                ->count();

                $PendingIncommingRequest = Requester::join('rig_users', 'requesters.requester_rig_id', '=', 'rig_users.id')
                ->join('stocks', 'requesters.stock_id', '=', 'stocks.id')
                ->join('edps', 'stocks.edp_code', '=', 'edps.id')
                ->leftJoin('mst_status', 'requesters.status', '=', 'mst_status.id')
                ->where('requesters.supplier_rig_id', $rig_id)
                ->where('mst_status.status_name', 'Pending') // Count only "Pending" statuses
                ->count();

    
                $RaisedRequests = Requester::leftJoin('stocks', 'requesters.stock_id', '=', 'stocks.id')
                    ->leftJoin('mst_status', 'requesters.status', '=', 'mst_status.id')
                    ->join('rig_users', 'requesters.supplier_rig_id', '=', 'rig_users.id')
                    ->join('edps', 'stocks.edp_code', '=', 'edps.id')
                    //->where('requesters.supplier_id', $userId)
                    ->where('requesters.requester_rig_id', $rig_id)
                    ->select('requesters.*', 'stocks.location_name', 'stocks.location_id', 'mst_status.status_name', 'edps.edp_code')
                    ->orderBy('requesters.created_at', 'desc')
                    ->count();

                    $RaisedRequestsRequests= Requester::leftJoin('stocks', 'requesters.stock_id', '=', 'stocks.id')
                    ->leftJoin('mst_status', 'requesters.status', '=', 'mst_status.id')
                    ->join('rig_users', 'requesters.supplier_rig_id', '=', 'rig_users.id')
                    ->join('edps', 'stocks.edp_code', '=', 'edps.id')
                    //->where('requesters.supplier_id', $userId)
                    ->where('requesters.requester_rig_id', $rig_id)
                    ->select('requesters.*', 'stocks.location_name', 'stocks.location_id', 'mst_status.status_name', 'edps.edp_code')
                    ->orderBy('requesters.created_at', 'desc')
                    ->where('mst_status.status_name', 'Pending') // Count only "Pending" statuses
                    ->count();


            return view('user.dashboard',compact('countIncommingRequest','PendingIncommingRequest','RaisedRequests','RaisedRequestsRequests'));
        }else{
            return redirect()->route('user.login');
        }
    }
}
