<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LogRequester;
use App\Models\LogsCategory;
use App\Models\LogsEdps;
use App\Models\LogsRequesters;
use App\Models\LogsRequestStatus;
use App\Models\LogsRigUsers;
use App\Models\LogsStocks;
use App\Models\LogsUser;

class LogsController extends Controller
{
        public function index() {
            return view('logs.list_log');
        }

        public function filterdata(Request $request){

            $type = $request->input('logs_type');
            $from = $request->input('form_date');
            $to = $request->input('to_date');
        
            // Get the right model based on log type
            $model = match($type) {
                'Rigs' => LogsRigUsers::class,
                'Users' => LogsUser::class,
                'EDP' => LogsEdps::class,
                'Stock' => LogsStocks::class,
                'Request' => LogsRequestStatus::class,
                default => null
            };
        
            if (!$model) {
                return response()->json(['error' => 'Invalid log type'], 400);
            }
        
            // Build query
            $query = $model::query();
            
            if ($from) $query->where('created_at', '>=', $from);
            if ($to) $query->where('created_at', '<=', $to);
        
              $data = $query->orderBy('created_at', 'desc')->get();
        
            return response()->json([
                'success' => true,
                'type' => $type,
                'data' => $data
            ]);
        }
}
