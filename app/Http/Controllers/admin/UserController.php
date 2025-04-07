<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\RigUser;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\LogsUser;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(10);
        $moduleName = "Users";
        $rigUsers = RigUser::pluck('name', 'id'); 

        return view('admin.user.index', compact('users', 'moduleName','rigUsers'));
    }

    // Show form for creating a new user
    public function create()
    {
        $rigUsers = RigUser::where('name', '!=', 'Admin')
            ->where('name', '!=', 'admin')
            ->get(); 
            $moduleName = "Create Users";
        return view('admin.user.create', compact('rigUsers', 'moduleName'));
    }

    // Store a new user
    public function store(Request $request)
    {
        $request->validate([
            'user_name'    => 'required|string|max:255',
            'email'        => 'required|email|unique:users',
            'cpf_no'       => 'required|string|max:255',
            'password'     => 'required|min:6',
            'user_status'  => 'required|integer',
            'user_type'    => 'required|string|in:admin,user',
            'rig_id'       => 'nullable|integer',
        ]);
    
        // Create the user
        $user = User::create([
            'user_name'   => $request->user_name,
            'email'       => $request->email,
            'cpf_no'      => $request->cpf_no,
            'password'    => Hash::make($request->password),
            'user_status' => $request->user_status,
            'user_type'   => $request->user_type,
            'rig_id'      => $request->rig_id,
        ]);

        // dd( $user );
    
        // Log the creation without storing the password
   
            $data = LogsUser::create([
                'user_name'     => $request->user_name,
                'email'         => $request->email,
                'cpf_no'        => $request->cpf_no,
                'user_status' => $request->user_status,
                'user_type'     => $request->user_type,
                'rig_id'      => $request->rig_id,
                'creater_id'    => auth()->id(),
                'creater_type'  => auth()->user()->user_type,
                'receiver_id'   => null,
                'receiver_type' => null,
                'message'       => "User {$request->user_name} has been created.",
            ]);
    
        return redirect()->route('admin.index')->with('success', 'User created successfully.');
    }

    // Show a specific user
    public function show($id)
    {
        $user = User::findOrFail($id);
        $moduleName = "View Users";
        return view('admin.user.show', compact('user','moduleName'));
    }

    // Show form for editing a user
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $rigUsers = RigUser::where('name', '!=', 'Admin')
            ->where('name', '!=', 'admin')
            ->get();
        $moduleName = "Edit Users";
        return view('admin.user.edit', compact('user', 'rigUsers', 'moduleName'));
    }

    // Update user details
    public function update(Request $request, $id)
    {

       
        $user = User::findOrFail((int) $id);

        
        $request->validate([
            'user_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email,' . $id,
            'cpf_no'     => 'required|string|max:255',
            'user_status' => 'required|integer',
            'user_type' => 'required|string|in:admin,user',
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

        LogsUser::create([
            'user_name'     => $request->user_name,
            'email'         => $request->email,
            'cpf_no'        => $request->cpf_no,
            'user_status' => $request->user_status,
            'user_type'     => $request->user_type,
            'rig_id'     => $request->rig_id,
            'creater_id'    => auth()->id(),
            'creater_type'  => auth()->user()->user_type,
            'receiver_id'   => null,
            'receiver_type' => null,
            'message'       => "User '{$request->user_name}' has been updated.",
        ]);

        return redirect()->route('admin.index')->with('success', 'User updated successfully.');
    }

    // Delete a user
    public function destroy($id)
    {
        $user = User::find($id);

        if ($user) {
            // Log before deletion
            LogsUser::create([
                'user_name'     => $user->user_name,
                'email'         => $user->email,
                'cpf_no'        => $user->cpf_no,
                'user_type'     => $user->user_type,
                'rig_id'      => $user->rig_id,
                'creater_id'    => auth()->id(),
                'creater_type'  => auth()->user()->user_type,
                'receiver_id'   => null,
                'receiver_type' => null,
                'message'       => "User '{$user->user_name}' has been deleted.",
            ]);
            // Delete the user
            $user->delete();
        }
        return redirect()->route('admin.index')->with('success', 'User deleted successfully.');
    }
}
