<?php

namespace App\Http\Controllers\Admin\Masters;

use App\Http\Controllers\Controller;
use App\Models\RigUser;
use Illuminate\Support\Facades\Auth;
use App\Models\LogsRigUsers;
use Illuminate\Http\Request;


class RigUserController extends Controller
{
    public function index()
    {
        $rigUsers = RigUser::where('name', '!=', 'admin')
        ->orderBy('id', 'desc')  // Sort by ID in descending order
        ->get();
        
        $moduleName = "Rigs";
        return view('admin.rig_users.index', compact('rigUsers', 'moduleName'));
    }

    public function create()
    {
        $moduleName = "Create Rigs"; 
        return view('admin.rig_users.create', compact('moduleName'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:60',
                'unique:rig_users,name'
            ],
            'location_id' => [
                'required',
                'string',
                'size:4',
                'regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{4}$/',
                'unique:rig_users,location_id'
            ],
        ], [
            'name.unique' => 'This rig name is already in use. Please choose a different name.',
            'location_id.unique' => 'This location ID is already assigned to another rig. Location IDs must be unique.',
          
        ]);

        RigUser::create([
            'name' => $request->name,
            'location_id' => $request->location_id,
            ]);
            
            LogsRigUsers::create([
                'location_id' => $request->location_id,
                'name' => $request->name,
                'created_at' => now(),
                'updated_at' => now(),
                'creater_id' => auth()->id(),
                'creater_type' => auth()->user()->user_type,
                'receiver_id' => null,
                'receiver_type' => null,
                'message' => "New Rig created: {$request->name} ({$request->location_id})",
            ]);


        return redirect()->route('admin.rig_users.index')->with('success', 'Rig User created successfully.');
    }

    public function show($id)
    {
        $rigUser = RigUser::findOrFail($id);
        $moduleName = "View Rig User";

        return view('admin.rig_users.show', compact('rigUser', 'moduleName'));
    }

    public function show2($id)
{
    $rig = RigUser::select('id', 'location_id', 'name')->find($id);
    return response()->json($rig ?: ['error' => 'Rig not found'], $rig ? 200 : 404);
}
    


    public function edit($id)
    {
        $rigUser = RigUser::findOrFail($id);
        $moduleName = "Edit Rigs";
        return view('admin.rig_users.edit', compact('rigUser', 'moduleName'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
                    'location_id' => [
                'required',
                'string',
                'size:4', 
                'regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{4}$/' 
            ],
        ]);

    
      

        $rigUser = RigUser::findOrFail($id);
       
        $rigUser->update(['name' => $request->name,
            'location_id' => $request->location_id,
            ]);


       
            LogsRigUsers::create([
                'location_id' => $request->location_id,
                'name' => $request->name,
                'created_at' => now(),
                'updated_at' => now(),
                'creater_id' => auth()->id(),
                'creater_type' => auth()->user()->user_type,
                'receiver_id' => null,
                'receiver_type' => null,
                'message'      => " Rig Updated: {$request->name} ({$request->location_id})",
            ]);

        return redirect()->route('admin.rig_users.index')->with('success', 'Rig User updated successfully.');
    }

    public function destroy(Request $request)
    {
        
        $rigUser = RigUser::where('location_id',$request->rig_delete_id)->delete();

        return redirect()->route('admin.rig_users.index')->with('success', 'Rig User deleted successfully.');
    }
}