@extends('layouts.frontend.admin_layout')
@section('page-content')

<div class="content-page">
    <div class="container-fluid add-form-list">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Edit User</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.update', $user->id) }}" method="POST" class="needs-validation" novalidate>
                            @csrf
                            @method('PUT')
                            <div class="form-row">
                                <div class="col-md-6 mb-3">
                                    <label for="user_name">User Name</label>
                                    <input type="text" class="form-control" name="user_name" value="{{ $user->user_name }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" name="email" value="{{ $user->email }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="cpf_no">CPF No</label>
                                    <input type="text" class="form-control" name="cpf_no" value="{{ $user->cpf_no }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" name="password">
                                    <small class="text-muted">Leave blank to keep the existing password</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="user_status">User Status</label>
                                    <select class="form-control" name="user_status" required>
                                        <option value="1" {{ $user->user_status == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ $user->user_status == 0 ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="user_type">User Type</label>
                                    <select class="form-control" name="user_type" required>
                                        {{-- <option value="admin" {{ $user->user_type == 'admin' ? 'selected' : '' }}>Admin</option> --}}
                                        <option value="user" {{ $user->user_type == 'user' ? 'selected' : '' }}>User</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="rig_id">Select Rigs</label>
                                    <select class="form-control" name="rig_id">
                                        <option value="" disabled>Select Rig User...</option>
                                        @foreach($rigUsers as $rigUser)
                                            <option value="{{ $rigUser->id }}" {{ $user->rig_id == $rigUser->id ? 'selected' : '' }}>
                                                {{ $rigUser->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <button class="btn btn-primary" type="submit">Update</button>
                            <a href="{{ route('admin.index') }}" class="btn btn-light">Go Back</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
