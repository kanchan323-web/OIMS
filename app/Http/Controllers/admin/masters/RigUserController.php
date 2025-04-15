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
        $rigUsers = RigUser::where('name', '!=', 'admin')->get();
        
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
            'name' => 'required|string',
                    'location_id' => [
                'required',
                'string',
                'size:4', 
                'regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{4}$/' 
            ],
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


    public function edit($id)
    {
        $rigUser = RigUser::findOrFail($id);
        $moduleName = "Edit Rigs";
        return view('admin.rig_users.edit', compact('rigUser', 'moduleName'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $rigUser = RigUser::findOrFail($id);
        $rigUser->update(['name' => $request->name]);

        return redirect()->route('admin.rig_users.index')->with('success', 'Rig User updated successfully.');
    }

    public function destroy($id)
    {
        $rigUser = RigUser::findOrFail($id);
        $rigUser->delete();

        return redirect()->route('admin.rig_users.index')->with('success', 'Rig User deleted successfully.');
    }
}