@extends('layouts.frontend.layout')
@section('page-content')

    <div class="content-page">
        <div class="container-fluid add-form-list">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Add Stock</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <form class="needs-validation" novalidate method="POST" id="addStockForm" action="{{ route('stockSubmit') }}"
                                id="addStockForm">
                                @csrf
                                <div class="form-row">
                                    <div class="col-md-6 mb-3">
                                        <label for="">Location Id</label>
                                        <input type="text" class="form-control" name="location_id" placeholder="Location Id"
                                            value="{{ old('location_id', $LocationName->location_id) }}" id="location_ids" required>
                                        @error('location_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="">Location Name</label>
                                        <input type="text" class="form-control" placeholder="Location Name"
                                            name="location_name" id="location_name" value="{{ old('location_name', $LocationName->name) }}"
                                            required>
                                        @error('location_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="">EDP Code</label>
                                        <input type="text" class="form-control @error('edp_code') is-invalid @enderror"
                                            name="edp_code" id="edp_code_id" placeholder="Enter EDP Code" value="{{ old('edp_code') }}"
                                            required>
                                        @error('edp_code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3 edp_detail">
                                        <label for="">Category</label>
                                        <input type="text" class="form-control" name="category" id="category_id" required>
                                    <!--  <select class="form-control @error('category') is-invalid @enderror" name="category"
                                            required>
                                            <option selected disabled value="">Select Category...</option>
                                            <option value="Spares" {{ old('category') == 'Spares' ? 'selected' : '' }}>
                                                Spares</option>
                                            <option value="Stores" {{ old('category') == 'Stores' ? 'selected' : '' }}>
                                                Stores</option>
                                            <option value="Capital items"
                                                {{ old('category') == 'Capital items' ? 'selected' : '' }}>Capital items
                                            </option>
                                        </select>
                                    -->
                                        @error('category')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="">Description</label>
                                        <textarea class="form-control" name="description" placeholder="Enter Description"
                                        id="description" required>{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3 edp_detail">
                                        <label for="">Section</label>
                                        <input type="text" class="form-control" name="section" id="section" required>
                                        @error('section')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="">Unit of Measurement</label>
                                        <input type="text" class="form-control" name="measurement" id="measurement"
                                            value="{{ old('measurement') }}" required>
                                        @error('measurement')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="qty">Available Quantity</label>
                                        <input type="number" class="form-control @error('qty') is-invalid @enderror"
                                            placeholder="Available Quantity" name="qty" value="{{ old('qty') }}" id="qty" required>
                                        @error('qty')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="">New Spareable</label>
                                        <input type="number" class="form-control" placeholder="New Spareable"
                                            name="new_spareable" value="{{ old('new_spareable') }}" id="new_spareable" required>
                                        @error('new_spareable')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="">Used Spareable</label>
                                        <input type="number" class="form-control" placeholder="Used Spareable"
                                            name="used_spareable" value="{{ old('used_spareable') }}" id="used_spareable" required>
                                        @error('used_spareable')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="">Remarks / Notes</label>
                                        <textarea class="form-control @error('remarks') is-invalid @enderror" name="remarks"
                                            placeholder="Remarks / Notes" id="remarks" required>{{ old('remarks') }}</textarea>
                                        @error('remarks')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <button class="btn btn-primary" type="submit">Submit Form</button>
                                <a href="{{ route('add_stock') }}" class="btn btn-secondary">Reset</a>
                                <a href="{{ url()->previous() ?: route('stock_list') }}" class="btn btn-light">Go Back</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <script>
            $(document).ready(function() {
            $('#edp_code_id').on('input', function() {
                var edpCode = $(this).val().trim();
        
                console.log("Input detected: " + edpCode); 
        
                if (edpCode.length === 9) {
                    console.log("Triggering AJAX for EDP Code: " + edpCode);
        
                    $.ajaxSetup({
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
                    });
        
                    $.ajax({
                        type: "GET",
                        url: "{{ route('check_edp_stock') }}",
                        data: { edp_code: edpCode },
                        success: function(response) {
                            console.log("AJAX Response: ", response);
        
                            if (response.exists) {
                                $("#addStockForm").attr("action", "{{ route('update_stock') }}");
                            } else {
                                $("#addStockForm").attr("action", "{{ route('stockSubmit') }}");
                            }

                            $("#location_ids").val(response.data?.location_id || '');
                            $("#location_name").val(response.data?.location_name || '');
                            $("#category_id").val(response.data?.category || '');
                            $("#description").val(response.data?.description || '');
                            $("#measurement").val(response.data?.measurement || '');
                            $("#section").val(response.data?.section || '');
                            $("#qty").val(response.data?.qty || '');
                            $("#new_spareable").val(response.data?.new_spareable || '');
                            $("#used_spareable").val(response.data?.used_spareable || '');
                            $("#remarks").val(response.data?.remarks || '');
                        },
                        error: function(xhr, status, error) {
                            console.error("AJAX Error:", status, error);
                        }
                    });
                }
            });
        });
        
        </script>


@endsection