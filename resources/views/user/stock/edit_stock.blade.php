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
                        <form class="needs-validation" novalidate method="POST" action="{{ route('update_stock') }}" id="editStockForm">
                            @csrf
                            <input type="hidden" name="id" value="{{ $editData->id }}">
                            
                            <div class="form-row">
                                <div class="col-md-6 mb-3">
                                    <label for="">Location Id</label>
                                    <input type="text" class="form-control" name="location_id" value="{{ $editData->location_id }}" readonly required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">Location Name</label>
                                    <input type="text" class="form-control" name="location_name" value="{{ $editData->location_name }}" readonly required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="">EDP Code</label>
                                    <select class="form-control @error('edp_code') is-invalid @enderror" name="edp_code" id="edp_code_id" required>
                                        <option selected disabled value="">Select EDP Code...</option>
                                        @foreach($edpCodes as $edp)
                                            <option value="{{ $edp->id }}" {{ $editData->edp_code == $edp->id ? 'selected' : '' }}>
                                                {{ $edp->edp_code }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('edp_code')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3 edp_detail">
                                    <label for="">Category</label>
                                    <input type="text" class="form-control" name="category" id="category_id" value="{{ $editData->category }}" readonly required>
                                </div>

                                <div class="col-md-6 mb-3 edp_detail">
                                    <label for="">Description</label>
                                    <textarea class="form-control" name="description" id="description" readonly required>{{ $editData->description }}</textarea>
                                </div>

                                <div class="col-md-6 mb-3 edp_detail">
                                    <label for="">Section</label>
                                    <input type="text" class="form-control" name="section" id="section" value="{{ $editData->section }}" readonly required>
                                </div>

                                <div class="col-md-6 mb-3 edp_detail">
                                    <label for="">Unit of Measurement</label>
                                    <input type="text" class="form-control" name="measurement" id="measurement" value="{{ $editData->measurement }}" readonly required>
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
                            <a href="{{ route('stock_list') }}" class="btn btn-light">Go Back</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
 $(document).ready(function() {
    $('.edp_detail').css('display', '{{ $editData->edp_code ? "block" : "none" }}');

    $('#edp_code_id').change(function() {
        var id = $(this).val();
        console.log(id);

        $.ajaxSetup({headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')}});
        $.ajax({
            type: "GET",
            url: "{{ route('get_edp_details') }}",
            data: { data: id },
            success: function(response){
                $('.edp_detail').css('display', 'block');
                $("#category_id").val(response.viewdata['category']);
                $("#measurement").val(response.viewdata['measurement']);
                $("#section").val(response.viewdata['section']);
                $("#description").val(response.viewdata['description']);
            }
        });
    });
});
</script>

@endsection
