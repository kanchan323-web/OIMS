@extends('layouts.frontend.layout')
@section('page-content')

<div class="content-page">
    <div class="container-fluid add-form-list">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Edit Stock</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form class="needs-validation" novalidate method="POST" action="{{ route('admin.update_stock') }}" id="editStockForm">
                            @csrf
                            <input type="hidden" name="id" value="{{ $editData->id }}">
                            
                            <div class="form-row">
                                <div class="col-md-6 mb-3">
                                    <label for="">Location Id</label>
                                    <input type="text" class="form-control" name="location_id" value="{{ $editData->location_id }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">Location Name</label>
                                    <input type="text" class="form-control" name="location_name" value="{{ $editData->location_name }}" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="">EDP Code</label>
                                    <input type="text" class="form-control" name="edp_code_display" value="{{ $editData->edp_code }}" readonly>
                                    <input type="hidden" name="edp_code" value="{{ $editData->edp_code }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="">Category</label>
                                    <input type="text" class="form-control" name="category" value="{{ $editData->category }}" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="">Description</label>
                                    <textarea class="form-control" name="description" required>{{ $editData->description }}</textarea>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="">Section</label>
                                    <input type="text" class="form-control" name="section" value="{{ $editData->section }}" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="">Unit of Measurement</label>
                                    <input type="text" class="form-control" name="measurement" value="{{ $editData->measurement }}" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="">Available Quantity</label>
                                    <input type="number" class="form-control" name="qty" value="{{ $editData->qty }}" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="">New Spareable</label>
                                    <input type="number" class="form-control" name="new_spareable" value="{{ $editData->new_spareable }}" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="">Used Spareable</label>
                                    <input type="number" class="form-control" name="used_spareable" value="{{ $editData->used_spareable }}" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="">Remarks / Notes</label>
                                    <textarea class="form-control" name="remarks" required>{{ $editData->remarks }}</textarea>
                                </div>
                            </div>

                            <button class="btn btn-primary" type="submit">Update Stock</button>
                            <a href="{{ route('admin.stock_list') }}" class="btn btn-light">Go Back</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
