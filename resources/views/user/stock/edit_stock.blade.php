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

                            @if (Session::get('success'))
                                <div class="alert bg-success text-white alert-dismissible fade show" role="alert">
                                    <strong>Success:</strong> {{ Session::get('success') }}
                                    <button type="button" class="close close-dark" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            @if (Session::get('error'))
                                <div class="alert bg-danger text-white alert-dismissible fade show" role="alert">
                                    <strong>Error:</strong> {{ Session::get('error') }}
                                    <button type="button" class="close close-dark" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                            <form class="needs-validation" novalidate method="POST" action="{{ route('update_stock') }}"
                                id="editStockForm">
                                @csrf
                                <input type="hidden" name="id" value="{{ $editData->id }}">

                                <div class="form-row">
                                    <div class="col-md-6 mb-3">
                                        <label for="">EDP Code</label>
                                        <input type="text" class="form-control @error('edp_code') is-invalid @enderror"
                                            name="edp_code_display" value="{{ $edpCodes->edp_code }}" readonly>
                                        <input type="hidden" name="edp_code" value="{{ $edpCodes->id }}">
                                        @error('edp_code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>


                                    <div class="col-md-6 mb-3">
                                        <label for="">Location ID</label>
                                        <input type="text" class="form-control @error('location_id') is-invalid @enderror" 
                                            name="location_id" value="{{ old('location_id', $editData->location_id) }}" required readonly>
                                        @error('location_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="">Location Name</label>
                                        <input type="text" class="form-control @error('location_name') is-invalid @enderror" 
                                            name="location_name" value="{{ old('location_name', $editData->location_name) }}" required readonly>
                                        @error('location_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>                                    

                                    <div class="col-md-6 mb-3">
                                        <label for="">Category</label>
                                        <select class="form-control @error('category') is-invalid @enderror" name="category"
                                            id="category_id" readonly required>
                                            <option selected disabled value="">Select Category...</option>
                                            <option value="Spares" {{ old('category', $editData->category) == 'Spares' ? 'selected' : '' }}>Spares</option>
                                            <option value="Stores" {{ old('category', $editData->category) == 'Stores' ? 'selected' : '' }}>Stores</option>
                                            <option value="Capital items" {{ old('category', $editData->category) == 'Capital items' ? 'selected' : '' }}>Capital items</option>
                                        </select>
                                        @error('category')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>


                                    <div class="col-md-6 mb-3">
                                        <label for="">Description</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror"
                                            name="description" id="description" required
                                            readonly>{{ old('description', $editData->description) }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>


                                    <div class="col-md-6 mb-3">
                                        <label for="">Section</label>
                                        <select class="form-control @error('section') is-invalid @enderror" name="section"
                                            id="section_id" readonly required>
                                            <option selected disabled value="">Select Section...</option>
                                            <option value="ENGG" {{ old('section', $editData->section) == 'ENGG' ? 'selected' : '' }}>ENGG</option>
                                            <option value="DRILL" {{ old('section', $editData->section) == 'DRILL' ? 'selected' : '' }}>DRILL</option>
                                        </select>
                                        @error('section')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>


                                    <div class="col-md-6 mb-3">
                                        <label for="">Unit of Measurement</label>
                                        <input type="text" class="form-control @error('measurement') is-invalid @enderror"
                                            name="measurement" id="measurement"
                                            value="{{ old('measurement', $editData->measurement) }}" required readonly>
                                        @error('measurement')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="">Available Quantity</label>
                                        <input type="number" class="form-control" name="qty" id="qty"
                                            value="{{ $editData->qty }}" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="">New Spareable</label>
                                        <input type="number"
                                            class="form-control @error('new_spareable') is-invalid @enderror"
                                            name="new_spareable" id="new_spareable"
                                            value="{{ old('new_spareable', $editData->new_spareable) }}" required>
                                        @error('new_spareable')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>


                                    <div class="col-md-6 mb-3">
                                        <label for="">Used Spareable</label>
                                        <input type="number"
                                            class="form-control @error('used_spareable') is-invalid @enderror"
                                            name="used_spareable" id="used_spareable"
                                            value="{{ old('used_spareable', $editData->used_spareable) }}" required>
                                        @error('used_spareable')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="">Remarks / Notes</label>
                                        <textarea class="form-control @error('remarks') is-invalid @enderror" name="remarks"
                                            id="remarks" required>{{ old('remarks', $editData->remarks) }}</textarea>
                                        @error('remarks')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
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

    <script>
        $(document).ready(function () {
            var edpCode = "{{ $editData->edp_code }}";

            if (edpCode) {
                $.ajaxSetup({
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
                });

                $.ajax({
                    type: "GET",
                    url: "{{ route('get_edp_details') }}",
                    data: { edp_code: edpCode },
                    success: function (response) {
                        console.log("EDP Data for Edit:", response);

                        if (response.success) {
                            $("#category_id").val(response.edp.category);
                            $("#category_hidden").val(response.edp.category);

                            $("#description").val(response.edp.description);
                            $("#measurement").val(response.edp.measurement);
                            $("#section_id").val(response.edp.section);
                            $("#section_hidden").val(response.edp.section);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("AJAX Error:", status, error);
                    }
                });
            }
        });

        $(document).ready(function () {
            // Automatically fade out alerts after 3 seconds
            setTimeout(function () {
                $(".alert").fadeOut("slow");
            }, 3000);
        });
    </script>

@endsection