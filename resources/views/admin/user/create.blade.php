@extends('layouts.frontend.admin_layout')
@section('page-content')

<div class="content-page">
    <div class="container-fluid add-form-list">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Add User</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.store') }}" method="POST" class="needs-validation" novalidate>
                            @csrf
                            <div class="form-row">
                                <div class="col-md-6 mb-3">
                                    <label for="user_name">User Name</label>
                                    <input type="text" class="form-control" name="user_name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" name="email" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="cpf_no">CPF No</label>
                                    <input type="text" class="form-control" name="cpf_no" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" name="password" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="user_status">User Status</label>
                                    <select class="form-control" name="user_status" required>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="user_type">User Type</label>
                                    <select class="form-control" name="user_type" required>
                                        <option value="select user type" selected>Select User type</option>
                                        {{-- <option value="admin">Admin</option> --}}
                                        <option value="user">User</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="rig_id">Select Rigs</label>
                                    <select class="form-control" name="rig_id">
                                        <option value="" disabled selected>Select Rig User...</option>
                                        @foreach($rigUsers as $rigUser)
                                            <option value="{{ $rigUser->id }}">{{ $rigUser->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <button class="btn btn-primary" type="submit">Submit</button>
                            <a href="{{ route('admin.index') }}" class="btn btn-light">Go Back</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
