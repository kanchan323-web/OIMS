@extends('layouts.frontend.admin_layout')
@section('page-content')

<div class="content-page">
    <div class="container-fluid add-form-list">
        <div class="row">
            <div class="col-sm-12">
                {{Breadcrumbs::render('add_User')}}
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
                                <!-- User Name -->
                                <div class="col-md-6 mb-3">
                                    <label for="user_name">User Name</label>
                                    <input type="text" class="form-control @error('user_name') is-invalid @enderror"
                                           name="user_name" value="{{ old('user_name') }}" required>
                                    @error('user_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="col-md-6 mb-3">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                           name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- CPF No -->
                                <div class="col-md-6 mb-3">
                                    <label for="cpf_no">CPF No</label>
                                    <input type="text" class="form-control @error('cpf_no') is-invalid @enderror"
                                           name="cpf_no" value="{{ old('cpf_no') }}" required>
                                    @error('cpf_no')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Password -->
                                <div class="col-md-6 mb-3">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                           name="password" required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- User Status -->
                                <div class="col-md-6 mb-3">
                                    <label for="user_status">User Status</label>
                                    <select class="form-control @error('user_status') is-invalid @enderror"
                                            name="user_status" required>
                                        <option value="1" {{ old('user_status') == '1' ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('user_status') == '0' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('user_status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- User Type -->
                                <div class="col-md-6 mb-3">
                                    <label for="user_type">User Type</label>
                                    <select class="form-control @error('user_type') is-invalid @enderror"
                                            name="user_type" id="user_type" required>
                                        <option value="" disabled {{ old('user_type') == '' ? 'selected' : '' }}>Select User type</option>
                                        <option value="admin" {{ old('user_type') == 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="user" {{ old('user_type') == 'user' ? 'selected' : '' }}>User</option>
                                    </select>
                                    @error('user_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Rig ID -->
                                <div class="col-md-6 mb-3" id="rig_section">
                                    <label for="rig_id">Select Rigs</label>
                                    <select class="form-control @error('rig_id') is-invalid @enderror"
                                            name="rig_id" id="rig_id">
                                        <option value="" disabled {{ old('rig_id') == '' ? 'selected' : '' }}>Select Rig User...</option>
                                        @foreach($rigUsers as $rigUser)
                                            <option value="{{ $rigUser->id }}" {{ old('rig_id') == $rigUser->id ? 'selected' : '' }}>
                                                {{ $rigUser->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('rig_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const userTypeSelect = document.getElementById('user_type');
        const rigSection = document.getElementById('rig_section');

        function toggleRigSection() {
            if (userTypeSelect.value === 'admin') {
                rigSection.style.display = 'none';
            } else {
                rigSection.style.display = 'block';
            }
        }

        toggleRigSection(); // On page load
        userTypeSelect.addEventListener('change', toggleRigSection); // On change
    });
</script>

@endsection
