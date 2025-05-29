@extends('layouts.frontend.layout')
@section('page-content')

<div class="content-page">
    <div class="container-fluid add-form-list">
        <div class="row">
            <div class="col-sm-12">
                {{Breadcrumbs::render('User_profile')}}
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">User Profile </h4>
                        </div>
                    </div>

                    <div class="card-body">

                        <div class="card-body">
                            <form class="needs-validation" novalidate method="" action=""
                                id="addStockForm">
                                @csrf
                                <div class="form-row">
                                    <div class="col-md-6 mb-3">
                                        <label for="">User Name</label>
                                        <input type="text" class="form-control" name="user_name"
                                            placeholder=" User Name" id="" value="{{Auth::user()->user_name}}" required readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="">Email</label>
                                        <input type="email" class="form-control" name="email"
                                            placeholder=" Email" id="" value="{{Auth::user()->email}}" required readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="">CPF No.</label>
                                        <input type="text" class="form-control" name="cpf_no"
                                            placeholder=" CPD No." id="" value="{{Auth::user()->cpf_no}}" required readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="">User Status</label>
                                        <input type="text" class="form-control" name="user_status"
                                            placeholder="User Status" id="" value="{{Auth::user()->user_status == 1 ? 'Active' : 'Inactive'}}" required readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="">User Type</label>
                                        <input type="text" class="form-control" name="user_type"
                                            placeholder="User Type" id="" value="{{Auth::user()->user_type}}" required readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="">RIG Id</label>
                                        <input type="text" class="form-control" name="rig_id_location"
                                            placeholder="User Type" id="" value=" ({{$RigUser->location_id}})&nbsp;&nbsp; {{$RigUser->name}}" required readonly>
                                        <input type="hidden" class="form-control" name="rig_id"
                                            placeholder="User Type" id="" value="{{Auth::user()->rig_id}}" required readonly>
                                    </div>
                                   
                                </div>
                                
                                <a href="{{ url()->previous() ?: route('user.dashboard') }}" class="btn btn-danger">Go
                                    Back</a>
                                <a href="" id="updatePassId" class="btn btn-success" data-toggle="modal" 
                                data-target=".changePassword-modal">Password Change
                                </a>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- add request modal -->
    <div class="modal fade changePassword-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Change Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <form class="needs-validation" novalidate id="changePasswordForm">
                            @csrf
                            <input type="hidden" class="form-control" name="user_id"
                                        value="{{Auth::user()->id}}">
                            <div class="form-row">
                                <div class="col-md-6 mb-3">
                                    <label for="password">New Password</label>
                                    <input type="password" class="form-control pass"
                                           name="password">
                                    <div class="error" id="password-error" style="color:red;"></div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="password">Confirm Password</label>
                                    <input type="password" class="form-control conPass"
                                           name="password_confirmation">
                                     <div class="error" id="password_confirmation-error" style="color:red;"></div>
                                </div>
                            </div>
                            <button class="btn btn-success" id="changePassword" type="submit">Submit</button>
                            <a href="{{route('user.profile')}}" class="btn btn-light">Go Back</a>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                </div>
            </div>
        </div>
    </div>

<script>
      $(document).ready(function () {
            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });

            $('#changePasswordForm').on('submit', function (e) {
                e.preventDefault();

                $('.error').text(''); 
               // $('.conPass').removeClass('is-invalid');
               //  $('.pass').removeClass('is-invalid');
                $.ajax({
                    url: "{{ route('changePassword.userProfile') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function (response) {
                         $('#changePassword-modal').modal('hide');
                        $('#changePasswordForm')[0].reset();
                        Swal.fire({
                        icon: 'success',
                        title: 'Password Updated!',
                        text: 'Your password has been successfully updated.',
                        confirmButtonText: 'OK'
                        }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = response.redirect;  // Change to your desired URL
                        }
                        });
                    },

                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                               // $('.pss').addClass('is-invalid');
                               //  $('.conPass').addClass('is-invalid');
                                $('#' + key + '-error').text(value[0]);
                            });
                        }
                    }
                });
            });
    });

    $(document).ready(function () {
        $('#updatePassId').click(function () {
            $('#changePasswordForm')[0].reset();
            $('#changePasswordForm').find('.form-control').removeClass('is-invalid');
            $('#changePasswordForm').find('.invalid-feedback').html('');
            $('#changePasswordForm').find('.error').text(''); 
            const modal = new bootstrap.Modal(document.getElementByClass('changePassword-modal'));
            modal.show();
        });
    });
</script>
@endsection