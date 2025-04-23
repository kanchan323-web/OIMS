@extends('layouts.frontend.layout')
@section('page-content')

    <div class="content-page">
        <div class="container-fluid add-form-list">
            <div class="row">
                <div class="col-sm-12">

                    {{ Breadcrumbs::render('edit_stock', $editData->id) }}
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
                                    <div class="col-md-4 mb-4">
                                        <label for="">EDP Code</label>
                                        <input type="text" class="form-control @error('edp_code') is-invalid @enderror"
                                            name="edp_code_display" value="{{ $edpCodes->edp_code }}" readonly>
                                        <input type="hidden" name="edp_code" value="{{ $edpCodes->id }}">
                                        @error('edp_code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>


                                    <div class="col-md-4 mb-4">
                                        <label for="">Location ID</label>
                                        <input type="text" class="form-control @error('location_id') is-invalid @enderror"
                                            name="location_id" value="{{ old('location_id', $editData->location_id) }}"
                                            required readonly>
                                        @error('location_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-4">
                                        <label for="">Location Name</label>
                                        <input type="text" class="form-control @error('location_name') is-invalid @enderror"
                                            name="location_name"
                                            value="{{ old('location_name', $editData->location_name) }}" required readonly>
                                        @error('location_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-4">
                                        <label for="">Category</label>
                                        <input type="text" class="form-control" name="category" id="category_id" required
                                            readonly value="{{ old('category', $editData->category) }}">
                                        @error('category')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <label for="">Section</label>
                                        <input type="text" class="form-control" name="section" id="section_id" required
                                            readonly value="{{ old('section', $editData->section) }}">
                                        @error('section')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>


                                    <div class="col-md-4 mb-4">
                                        <label for="">Unit of Measurement</label>
                                        <input type="text" class="form-control @error('measurement') is-invalid @enderror"
                                            name="measurement" id="measurement"
                                            value="{{ old('measurement', $editData->measurement) }}" required readonly>
                                        @error('measurement')
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
                                        <label for="">Remarks / Notes</label>
                                        <textarea class="form-control @error('remarks') is-invalid @enderror" name="remarks"
                                            id="remarks" required>{{ old('remarks', $editData->remarks) }}</textarea>
                                        @error('remarks')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>



                                    <div class="col-md-4 mb-4">
                                        <label for="">Total Quantity</label>
                                        <input type="text" class="form-control" name="qty" id="qty"
                                            value="{{ $editData->qty }}" required readonly>
                                    </div>

                                    <div class="col-md-4 mb-4">
                                        <label for="">New </label>
                                        <input type="text" class="form-control @error('new_spareable') is-invalid @enderror"
                                            name="new_spareable" id="new_spareable"
                                            value="{{ old('new_spareable', $editData->new_spareable) }}" required>
                                        @error('new_spareable')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>


                                    <div class="col-md-4 mb-4">
                                        <label for="">Used </label>
                                        <input type="text"
                                            class="form-control @error('used_spareable') is-invalid @enderror"
                                            name="used_spareable" id="used_spareable"
                                            value="{{ old('used_spareable', $editData->used_spareable) }}" required>
                                        @error('used_spareable')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>



                                </div>

                                <button class="btn btn-success" type="submit">Update Stock</button>
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

        $(document).ready(function () {
            /*   function validateStock() {
                   let availableQty = $("#qty").val().trim() === "" ? 0 : parseInt($("#qty").val()) || 0;
                   let newSpareable = $("#new_spareable").val().trim() === "" ? 0 : parseInt($("#new_spareable").val()) || 0;
                   let usedSpareable = $("#used_spareable").val().trim() === "" ? 0 : parseInt($("#used_spareable").val()) || 0;
                   let totalSpareable = newSpareable + usedSpareable;

                   $("#new-error, #used-error").remove();

                   if ($("#qty").val().trim() === "" && $("#new_spareable").val().trim() === "" && $("#used_spareable").val().trim() === "") {
                       return true;
                   }

                   if (newSpareable > availableQty) {
                       $("#new_spareable").after('<div id="new-error" class="text-danger">New Spareable cannot exceed Available Quantity.</div>');
                       return false;
                   }


                   if (totalSpareable > availableQty) {
                       if (newSpareable > usedSpareable) {
                           $("#new_spareable").after('<div id="new-error" class="text-danger">Total of New & Used Spareable should not exceed Available Quantity.</div>');
                       } else {
                           $("#used_spareable").after('<div id="used-error" class="text-danger">Total of New & Used Spareable should not exceed Available Quantity.</div>');
                       }
                       return false;
                   }

                   return true;
               }

               $("#qty, #new_spareable, #used_spareable").on("input", function () {
                   validateStock();
               });

               $("#addStockForm").on("submit", function (e) {
                   if (!validateStock()) {
                       e.preventDefault();
                   }
               });
               */
            function calculateSum() {
                var value1 = parseFloat($('#new_spareable').val()) || 0; // Default to 0 if empty or invalid
                var value2 = parseFloat($('#used_spareable').val()) || 0; // Default to 0 if empty or invalid
                var sum = value1 + value2; // Calculate the sum
                $('#qty').val(sum); // Display the sum in the 'sum' input field
            }

            // Attach the keyup event to both input fields
            $('#new_spareable, #used_spareable').on('keyup', function () {
                calculateSum(); // Call the calculateSum function when either input changes
            });
        });


        $(document).ready(function () {
            let unitTypes = {
                'EA': 'integer', 'KIT': 'integer', 'PAA': 'integer', 'PAC': 'integer', 'ROL': 'integer', 'ST': 'integer',
                'FT': 'decimal', 'GAL': 'decimal', 'KG': 'decimal', 'KL': 'decimal', 'L': 'decimal', 'LB': 'decimal',
                'M': 'decimal', 'M3': 'decimal', 'MT': 'decimal', 'NO': 'integer'
            };

            function validateInput(field) {
                let unit = $("#measurement").val()?.trim();
                let value = $(field).val().trim();
                let isValid = true;
                let errorMsg = "";

                if (unit && unitTypes[unit]) {
                    if (unitTypes[unit] === 'integer') {
                        isValid = /^\d+$/.test(value);
                        errorMsg = isValid ? "" : "Only whole numbers allowed!";
                    } else if (unitTypes[unit] === 'decimal') {
                        if (/^\d+(\.\d+)?$/.test(value)) {
                            let decimalPart = value.includes(".") ? value.split(".")[1] : "";
                            isValid = decimalPart.length <= 10;
                            errorMsg = isValid ? "" : "Max 10 decimal places allowed!";
                            console.log("Current Value:", value);
                        } else {
                            isValid = false;
                            errorMsg = "Invalid decimal format!";
                        }
                    }
                }

                toggleError(field, isValid, errorMsg);
                toggleSubmit();
            }

            function toggleError(field, isValid, message) {
                $(field).toggleClass("is-invalid", !isValid).next(".invalid-feedback").remove();
                if (!isValid) $(field).after(`<div class="invalid-feedback">${message}</div>`);
            }

            function toggleSubmit() {
                $("button[type='submit']").prop("disabled", $(".is-invalid").length > 0);
            }

            $("#new_spareable, #used_spareable").on("input", function () {
                validateInput(this);
            });

            $("#measurement").on("change", function () {
                $("#new_spareable, #used_spareable").trigger("input");
            });

            $("#new_spareable, #used_spareable").trigger("input");
        });


    </script>

@endsection
