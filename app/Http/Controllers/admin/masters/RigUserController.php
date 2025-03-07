<?php

namespace App\Http\Controllers\Admin\Masters;

use App\Http\Controllers\Controller;
use App\Models\RigUser;
use Illuminate\Http\Request;

class RigUserController extends Controller
{
    public function index()
    {
        $rigUsers = RigUser::where('name', '!=', 'admin')->get();
        return view('admin.rig_users.index', compact('rigUsers'));
    }

    public function create()
    {
        return view('admin.rig_users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        RigUser::create(['name' => $request->name]);

        return redirect()->route('admin.rig_users.index')->with('success', 'Rig User created successfully.');
    }

    public function edit($id)
    {
        $rigUser = RigUser::findOrFail($id);
        return view('admin.rig_users.edit', compact('rigUser'));
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

