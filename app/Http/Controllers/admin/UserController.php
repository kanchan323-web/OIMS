<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\RigUser;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(10);
        return view('admin.user.index', compact('users'));
    }

    // Show form for creating a new user
    public function create()
    {
        $rigUsers = RigUser::where('name', '!=', 'Admin')
            ->where('name', '!=', 'admin')
            ->get(); 

        return view('admin.user.create', compact('rigUsers'));
    }

    // Store a new user
    public function store(Request $request)
    {
        $request->validate([
            'user_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:users',
            'cpf_no'     => 'required|string|max:255',
            'password'   => 'required|min:6',
            'user_status' => 'required|integer',
            'user_type'  => 'required|string|in:admin,user',
            'rig_id'     => 'nullable|integer',
        ]);

        User::create([
            'user_name'  => $request->user_name,
            'email'      => $request->email,
            'cpf_no'     => $request->cpf_no,
            'password'   => Hash::make($request->password),
            'user_status' => $request->user_status,
            'user_type'  => $request->user_type,
            'rig_id'     => $request->rig_id,
        ]);

        return redirect()->route('admin.index')->with('success', 'User created successfully.');
    }

    // Show a specific user
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('admin.user.show', compact('user'));
    }

    // Show form for editing a user
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $rigUsers = RigUser::where('name', '!=', 'Admin')
            ->where('name', '!=', 'admin')
            ->get();
        return view('admin.user.edit', compact('user', 'rigUsers'));
    }

    // Update user details
    public function update(Request $request, $id)
    {
        $user = User::findOrFail((int) $id);
        // dd($id, User::find($id));
        $request->validate([
            'user_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email,' . $id,
            'cpf_no'     => 'required|string|max:255',
            'user_status' => 'required|integer',
            'user_type'  => 'required|string|in:admin,user',
            'rig_id'     => 'required|integer',
        ]);

        $user->update([
            'user_name'  => $request->user_name,
            'email'      => $request->email,
            'cpf_no'     => $request->cpf_no,
            'user_status' => $request->user_status,
            'user_type'  => $request->user_type,
            'rig_id'     => $request->rig_id,
        ]);

        return redirect()->route('admin.index')->with('success', 'User updated successfully.');
    }

    // Delete a user
    public function destroy($id)
    {
        User::destroy($id);
        return redirect()->route('admin.index')->with('success', 'User deleted successfully.');
    }
}
