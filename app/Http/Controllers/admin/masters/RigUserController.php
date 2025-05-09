<?php

namespace App\Http\Controllers\Admin\Masters;

use App\Http\Controllers\Controller;
use App\Models\RigUser;
use Illuminate\Support\Facades\Auth;
use App\Models\LogsRigUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


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
                'message' => 'New Rig created by ' . auth()->user()->user_name . ' (' . auth()->user()->user_type . '): ' . $request->name . ' (' . $request->location_id . ')',
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
            'name' => [
                'required',
                'string',
                'max:60',
                Rule::unique('rig_users', 'name')->ignore($id),
            ],
            'location_id' => [
                'required',
                'string',
                'size:4',
                'regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{4}$/',
                Rule::unique('rig_users', 'location_id')->ignore($id),
            ],
        ], [
            'name.unique' => 'This rig name is already in use. Please choose a different name.',
            'location_id.unique' => 'This location ID is already assigned to another rig. Location IDs must be unique.',
        ]);
    
        $rigUser = RigUser::findOrFail($id);
    
        // Build change message
        $changes = [];
        if ($rigUser->getOriginal('name') !== $request->name) {
            $changes[] = "Rig Name changed from '{$rigUser->getOriginal('name')}' to '{$request->name}'";
        }
        if ($rigUser->getOriginal('location_id') !== $request->location_id) {
            $changes[] = "Location ID changed from '{$rigUser->getOriginal('location_id')}' to '{$request->location_id}'";
        }
    
        $message = 'Rig Updated by ' . auth()->user()->user_name . ' (' . auth()->user()->user_type . ')';
        if (!empty($changes)) {
            $message .= ': ' . implode('; ', $changes);
        } else {
            $message .= ': No actual changes made.';
        }
    
        // Update the record
        $rigUser->update([
            'name' => $request->name,
            'location_id' => $request->location_id,
        ]);
    
        // Log the update
        LogsRigUsers::create([
            'location_id' => $request->location_id,
            'name' => $request->name,
            'created_at' => now(),
            'updated_at' => now(),
            'creater_id' => auth()->id(),
            'creater_type' => auth()->user()->user_type,
            'receiver_id' => null,
            'receiver_type' => null,
            'message' => $message,
        ]);
    
        return redirect()->route('admin.rig_users.index')->with('success', 'Rig User updated successfully.');
    }
    

    // public function destroy(Request $request)
    // {
        
    //     $rigUser = RigUser::where('location_id',$request->rig_delete_id)->delete();

    //     return redirect()->route('admin.rig_users.index')->with('success', 'Rig User deleted successfully.');
    // }

    public function destroy(Request $request)
{
    $rigUser = RigUser::where('location_id', $request->rig_delete_id)->first();

    if (!$rigUser) {
        return redirect()->route('admin.rig_users.index')->with('error', 'Rig User not found.');
    }

    // Capture info before deletion
    $deletedName = $rigUser->name;
    $deletedLocation = $rigUser->location_id;

    $rigUser->delete();

    $user = auth()->user();

    LogsRigUsers::create([
        'location_id' => $deletedLocation,
        'name' => $deletedName,
        'created_at' => now(),
        'updated_at' => now(),
        'creater_id' => $user->id,
        'creater_type' => $user->user_type,
        'receiver_id' => null,
        'receiver_type' => null,
        'message' => 'Rig Deleted by ' . $user->user_name . ' (' . $user->user_type . '): ' . $deletedName . ' (' . $deletedLocation . ')',
    ]);

    return redirect()->route('admin.rig_users.index')->with('success', 'Rig User deleted successfully.');
}
}