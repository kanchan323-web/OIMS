@extends('layouts.frontend.layout')
@section('page-content')

<div class="content-page">
    <div class="container-fluid add-form-list">
        <div class="row">
            <div class="col-sm-12">
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
                                        <input type="text" class="form-control" name="rig_id"
                                            placeholder="User Type" id="" value="{{Auth::user()->rig_id}}" required readonly>
                                    </div>
                                   
                                </div>
                                
                                <a href="{{ url()->previous() ?: route('admin.dashboard') }}" class="btn btn-info">Go
                                    Back</a>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection