<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Stock;
use App\Models\Requester;
use App\Models\Edp;
use App\Models\RigUser;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(){
      $PendingIncomingRequest = Requester::leftJoin('mst_status', 'requesters.status', '=', 'mst_status.id')
      ->where('mst_status.status_name', 'Pending')
      ->count();
      $totalUser = User::where('user_type','!=','admin')->count();
      $totalRequester = Requester::count();
      $totalStock = Stock::count();
      $totalEDP = Edp::count();
      $allUsers = User::count(); 
      $rigOverview = RigUser::select('location_id', 'name', DB::raw('COUNT(id) as total_rigs'))
                                  ->groupBy('location_id', 'name')
                                  ->get();

      $usersOverView = User::select('users.id', 'users.user_name', 'rig_users.name as rig_name')
                                  ->leftJoin('rig_users', 'users.rig_id', '=', 'rig_users.id')
                                  ->get();
      $rigData = $usersOverView->groupBy('rig_name')->map(function ($group, $rigName) {
                                    return [
                                        'name' => $rigName ?? 'No Rig Assigned',
                                        'y' => $group->count(),
                                        'userNames' => $group->pluck('user_name')->toArray(),
                                    ];
                                })->values();

                                $edpsData = Edp::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
                                ->groupBy('date')
                                ->orderBy('date')
                                ->get();
                        
                            $categoriesData = Category::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
                                ->groupBy('date')
                                ->orderBy('date')
                                ->get();
                        
                            $dates = collect($edpsData->pluck('date'))->merge($categoriesData->pluck('date'))->unique()->sort();
                        
                            $edpsSeries = [];
                            $categoriesSeries = [];
                        
                            foreach ($dates as $date) {
                                $edpsSeries[] = [
                                    'date' => $date,
                                    'count' => optional($edpsData->firstWhere('date', $date))->count ?? 0,
                                ];
                                $categoriesSeries[] = [
                                    'date' => $date,
                                    'count' => optional($categoriesData->firstWhere('date', $date))->count ?? 0,
                                ];
                            }
      

      
      return view('admin.dashboard',compact('rigOverview','rigData','usersOverView','totalUser','totalRequester','totalStock','totalEDP','PendingIncomingRequest','allUsers','edpsSeries', 'categoriesSeries'));
    }

    public function get_stock(){
        return view('admin.dashboard');
      }

}
