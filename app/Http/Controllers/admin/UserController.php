<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\RigUser;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\LogsUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = User::where('users.user_name', '!=', 'admin')
                ->leftJoin('rig_users', 'users.rig_id', '=', 'rig_users.id')
                ->select([
                    'users.id',
                    'users.user_name',
                    'users.email',
                    'users.cpf_no',
                    'users.user_type',
                    'users.user_status',
                    'rig_users.name as rig_name'
                ]);

            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $viewData = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');

                    $viewBtn = '<a class="badge badge-info mr-2" data-toggle="modal" title="View"
                    data-target="#userViewModal" onclick="viewtoclick(' . $viewData . ')">
                    <i class="ri-eye-line mr-0"></i></a>';

                    $editBtn = '<a class="badge bg-success mr-2" data-toggle="tooltip" title="Edit"
                    href="' . url('OIMS/admin/user/' . $row->id . '/edit') . '">
                    <i class="ri-pencil-line mr-0"></i></a>';

                    $deleteBtn = '<a href="javascript:void(0);" class="badge bg-warning mr-2 border-0 delete-btn"
                    data-toggle="tooltip" title="Delete" data-id="' . $row->id . '"
                    data-action="' . url('OIMS/admin/user/' . $row->id) . '">
                    <i class="ri-delete-bin-line mr-0"></i></a>';

                    return $viewBtn . $editBtn . $deleteBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $moduleName = "Users List";
        $rigUsers = RigUser::pluck('name', 'id');
        return view('admin.user.index', compact('moduleName', 'rigUsers'));
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
            'user_name' => [
                'required',
                'string',
                'max:255',
                'unique:users,user_name',
                'regex:/^[A-Za-z\s]+$/',
            ],
            'email' => 'required|email|unique:users,email',
            'cpf_no' => [
                'required',
                'string',
                'min:5',
                'max:6',
                'regex:/^\d+$/',
                'unique:users,cpf_no',
            ],
            'password' => [
                'required',
                'min:8',
                'regex:/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]+$/',
            ],
            'user_status' => 'required|integer',
            'user_type' => 'required|string|in:admin,user',
            'rig_id' => 'nullable|integer',
        ], [
            'user_name.required' => 'The user name is required.',
            'user_name.string' => 'The user name must be a valid string.',
            'user_name.max' => 'The user name may not be greater than 255 characters.',
            'user_name.unique' => 'This user name is already taken.',
            'user_name.regex' => 'The user name may only contain letters and spaces.',

            'email.required' => 'The email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already registered.',

            'cpf_no.required' => 'The CPF number is required.',
            'cpf_no.unique' => 'This CPF number is already registered.',
            'cpf_no.string' => 'The CPF number must be a valid string.',
            'cpf_no.min' => 'The CPF number must be at least 5 digits.',
            'cpf_no.max' => 'The CPF number must not exceed 6 digits.',
            'cpf_no.regex' => 'The CPF number must contain only digits.',

            'password.required' => 'A password is required.',
            'password.min' => 'The password must be at least 8 characters.',
            'password.regex' => 'Password must include at least one letter, one number, and one special character.',

            'user_status.required' => 'Please select a user status.',
            'user_status.integer' => 'Invalid user status selected.',

            'user_type.required' => 'Please select a user type.',
            'user_type.in' => 'User type must be either Admin or User.',

            'rig_id.integer' => 'Invalid rig selection.',
        ]);

        $rigId = $request->user_type === 'admin' ? 0 : $request->rig_id;

        $user = User::create([
            'user_name' => $request->user_name,
            'email' => $request->email,
            'cpf_no' => $request->cpf_no,
            'password' => Hash::make($request->password),
            'user_status' => $request->user_status,
            'user_type' => $request->user_type,
            'rig_id' => $rigId,
        ]);

        LogsUser::create([
            'user_name' => $request->user_name,
            'email' => $request->email,
            'cpf_no' => $request->cpf_no,
            'user_status' => $request->user_status,
            'user_type' => $request->user_type,
            'rig_id' => $rigId,
            'creater_id' => auth()->id(),
            'creater_type' => auth()->user()->user_type,
            'receiver_id' => null,
            'receiver_type' => null,
            'message' => 'User created by ' . auth()->user()->user_name . ' (' . auth()->user()->user_type . '): '
                . 'Name: ' . $request->user_name . ', '
                . 'Email: ' . $request->email . ', '
                . 'CPF No: ' . $request->cpf_no . ', '
                . 'Type: ' . ucfirst($request->user_type) . ', '
                . 'Status: ' . ($request->user_status ? 'Active' : 'Inactive') . ', '
                . 'Rig ID: ' . ($rigId ?: 'N/A'),
        ]);

        return redirect()->route('admin.index')->with('success', 'User created successfully.');
    }


    // Show a specific user
    public function show($id)
    {
        $user = User::findOrFail($id);
        $moduleName = "View Users";
        return view('admin.user.show', compact('user', 'moduleName'));
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

        $rules = [
            'user_name'   => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-z\s]+$/',
            ],
            'email'       => [
                'required',
                'email',
                Rule::unique('users')->ignore($id),
            ],
            'cpf_no' => [
                'required',
                'string',
                'min:5',
                'max:6',
                'regex:/^\d+$/',
                Rule::unique('users')->ignore($id),
            ],
            'user_status' => 'required|integer',
            'user_type'   => 'required|string|in:admin,user',
            'rig_id'      => 'nullable|integer',
        ];

        if (!empty($request->password)) {
            $rules['password'] = [
                'min:8',
                'regex:/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]+$/',
            ];
        }

        $request->validate($rules, [
            'user_name.required' => 'The user name is required.',
            'user_name.regex'    => 'The user name must only contain letters and spaces.',

            'email.required'     => 'The email address is required.',
            'email.email'        => 'Please provide a valid email address.',
            'email.unique'       => 'This email is already taken.',

            'cpf_no.required' => 'The CPF number is required.',
            'cpf_no.unique'   => 'This CPF number is already in use.',
            'cpf_no.string'   => 'The CPF number must be a valid string.',
            'cpf_no.min'      => 'The CPF number must be at least 5 digits.',
            'cpf_no.max'      => 'The CPF number must not exceed 6 digits.',
            'cpf_no.regex'    => 'The CPF number must contain only digits.',

            'password.min'       => 'The password must be at least 8 characters.',
            'password.regex'     => 'Password must include at least one letter, one number, and one special character.',

            'user_status.required' => 'User status is required.',
            'user_type.required'   => 'User type is required.',
            'user_type.in'         => 'Invalid user type selected.',
            'rig_id.integer'       => 'Rig ID must be a number.',
        ]);

        $rigId = $request->user_type === 'admin' ? 0 : $request->rig_id;

        $original = $user->getOriginal(); // get old values

        // Update user fields
        $user->update([
            'user_name'   => $request->user_name,
            'email'       => $request->email,
            'cpf_no'      => $request->cpf_no,
            'user_status' => $request->user_status,
            'user_type'   => $request->user_type,
            'rig_id'      => $rigId,
        ]);

        if (!empty($request->password)) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        // Generate clear message of what changed
        $changes = [];

        if ($original['user_name'] !== $request->user_name) {
            $changes[] = "Name changed from '{$original['user_name']}' to '{$request->user_name}'";
        }

        if ($original['email'] !== $request->email) {
            $changes[] = "Email changed from '{$original['email']}' to '{$request->email}'";
        }

        if ($original['cpf_no'] !== $request->cpf_no) {
            $changes[] = "CPF No changed from '{$original['cpf_no']}' to '{$request->cpf_no}'";
        }

        if ((int)$original['user_status'] !== (int)$request->user_status) {
            $oldStatus = $original['user_status'] ? 'Active' : 'Inactive';
            $newStatus = $request->user_status ? 'Active' : 'Inactive';
            $changes[] = "Status changed from '{$oldStatus}' to '{$newStatus}'";
        }

        if ($original['user_type'] !== $request->user_type) {
            $changes[] = "Type changed from '{$original['user_type']}' to '{$request->user_type}'";
        }

        if ((int)$original['rig_id'] !== (int)$rigId) {
            $changes[] = "Rig ID changed from '{$original['rig_id']}' to '{$rigId}'";
        }

        if (!empty($request->password)) {
            $changes[] = "Password was updated";
        }

        $logMessage = "User updated by " . auth()->user()->user_name . " (" . auth()->user()->user_type . ")";
        $logMessage .= $changes ? ": " . implode(', ', $changes) : " but no changes detected.";

        // Save to log
        LogsUser::create([
            'user_name'     => $request->user_name,
            'email'         => $request->email,
            'cpf_no'        => $request->cpf_no,
            'user_status'   => $request->user_status,
            'user_type'     => $request->user_type,
            'rig_id'        => $rigId,
            'creater_id'    => auth()->id(),
            'creater_type'  => auth()->user()->user_type,
            'receiver_id'   => null,
            'receiver_type' => null,
            'message'       => $logMessage,
        ]);

        if (auth()->id() == $user->id) {
            auth()->logout();
            return redirect()->route('login')->with('message', 'Role updated. Please log in again.');
        }

        return redirect()->route('admin.index')->with('success', 'User updated successfully.');
    }



    // Delete a user
    public function destroy($id)
    {
        $user = User::find($id);

        if ($user) {
            // Log before deletion
            LogsUser::create([
                'user_name' => $user->user_name,
                'email' => $user->email,
                'cpf_no' => $user->cpf_no,
                'user_type' => $user->user_type,
                'rig_id' => $user->rig_id,
                'creater_id' => auth()->id(),
                'creater_type' => auth()->user()->user_type,
                'receiver_id' => null,
                'receiver_type' => null,
                'message' => "User '{$user->user_name}' has been deleted.",
            ]);
            // Delete the user
            $user->delete();
        }
        return redirect()->route('admin.index')->with('success', 'User deleted successfully.');
    }
}
