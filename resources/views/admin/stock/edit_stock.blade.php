@extends('layouts.frontend.admin_layout')
@section('page-content')

    <div class="content-page">
        <div class="container-fluid add-form-list">
            <div class="row">
                <div class="col-sm-12">
                    {{Breadcrumbs::render('admin_edit_stock',$editData->id)}}
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
                            <form class="needs-validation" novalidate method="POST" action="{{ route('admin.update_stock') }}" id="editStockForm">
                                @csrf
                                <input type="hidden" name="id" value="{{ $editData->id }}">
                            
                                <div class="form-row">
                            
                                    <!-- EDP Code -->
                                    <div class="col-md-6 mb-3">
                                        <label for="">EDP Code</label>
                                        <input type="text" class="form-control @error('edp_code') is-invalid @enderror" name="edp_code_display" value="{{ $edpCodes->edp_code }}" readonly>
                                        <input type="hidden" name="edp_code" value="{{ $edpCodes->id }}">
                                        @error('edp_code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                            
                                    <!-- Rig -->
                                    <div class="col-md-6 mb-3">
                                        <label for="rig_id">Select Rig</label>
                                        <select class="form-control select2 @error('rig_id') is-invalid @enderror" disabled>
                                            <option value="" disabled>Select Rig</option>
                                            @foreach ($rigs as $rig)
                                                <option value="{{ $rig->id }}" {{ old('rig_id', $editData->rig_id) == $rig->id ? 'selected' : '' }}>
                                                    {{ $rig->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="rig_id" value="{{ old('rig_id', $editData->rig_id) }}">
                                        @error('rig_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                            
                                    <!-- Category -->
                                    <div class="col-md-6 mb-3">
                                        <label for="category_id">Category</label>
                                        <input type="text" class="form-control @error('category') is-invalid @enderror" name="category" id="category_id" value="{{ old('category', $editData->category) }}" readonly required>
                                        @error('category')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                            
                                    <!-- Description -->
                                    <div class="col-md-6 mb-3">
                                        <label for="description">Description</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="description" required readonly>{{ old('description', $editData->description) }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                            
                                    <!-- Section -->
                                    <div class="col-md-6 mb-3">
                                        <label for="section_id">Section</label>
                                        <input type="text" class="form-control @error('section') is-invalid @enderror" name="section" id="section_id" value="{{ old('section', $editData->section) }}" readonly required>
                                        @error('section')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                            
                                    <!-- Measurement -->
                                    <div class="col-md-6 mb-3">
                                        <label for="measurement">Unit of Measurement</label>
                                        <input type="text" class="form-control @error('measurement') is-invalid @enderror" name="measurement" id="measurement" value="{{ old('measurement', $editData->measurement) }}" readonly required>
                                        @error('measurement')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                            
                                    <!-- Total Quantity -->
                                    <div class="col-md-6 mb-3">
                                        <label for="qty">Total Quantity</label>
                                        <input type="text" class="form-control @error('qty') is-invalid @enderror" name="qty" id="qty" value="{{ IND_money_format($editData->qty) }}" readonly required>
                                        @error('qty')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                            
                                    <!-- New Spareable -->
                                    <div class="col-md-6 mb-3">
                                        <label for="new_spareable">New Spareable</label>
                                        <input type="text" class="form-control @error('new_spareable') is-invalid @enderror" name="new_spareable" id="new_spareable" value="{{ old('new_spareable', IND_money_format($editData->new_spareable)) }}" required>
                                        @error('new_spareable')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                            
                                    <!-- Used Spareable -->
                                    <div class="col-md-6 mb-3">
                                        <label for="used_spareable">Used Spareable</label>
                                        <input type="text" class="form-control @error('used_spareable') is-invalid @enderror" name="used_spareable" id="used_spareable" value="{{ old('used_spareable', IND_money_format($editData->used_spareable)) }}" required>
                                        @error('used_spareable')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                            
                                    <!-- Remarks -->
                                    <div class="col-md-6 mb-3">
                                        <label for="remarks">Remarks / Notes</label>
                                        <textarea class="form-control @error('remarks') is-invalid @enderror" name="remarks" id="remarks" required>{{ old('remarks', $editData->remarks) }}</textarea>
                                        @error('remarks')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
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

    <script>
        function formatToIndianNumber(input) {
            let parts = input.toString().split(".");
            let integerPart = parts[0].replace(/\D/g, '');
            let decimalPart = parts[1] || "";

            let lastThree = integerPart.slice(-3);
            let otherNumbers = integerPart.slice(0, -3);

            if (otherNumbers !== "") {
                lastThree = "," + lastThree;
            }

            let formattedInt = otherNumbers.replace(/\B(?=(\d{2})+(?!\d))/g, ",") + lastThree;
            return decimalPart ? formattedInt + "." + decimalPart : formattedInt;
        }


        $(document).ready(function () {
            var edpCode = "{{ $editData->edp_code }}";

            if (edpCode) {
                $.ajaxSetup({
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
                });

                $.ajax({
                    type: "GET",
                    url: "{{ route('admin.get_edp_details') }}",
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
                var value1 = parseFloat($('#new_spareable').val().replace(/,/g, '')) || 0;
                var value2 = parseFloat($('#used_spareable').val().replace(/,/g, '')) || 0;
                var sum = value1 + value2;
                $('#qty').val(formatToIndianNumber(sum));
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
                let value = $(field).val().replace(/,/g, '').trim();
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

            function applyIndianFormat(field) {
                let value = $(field).val().replace(/,/g, '');
                if (!isNaN(value) && value !== '') {
                    let formattedValue = formatToIndianNumber(value);
                    $(field).val(formattedValue);
                }
            }

            // Apply formatting on keyup and blur
            $("#new_spareable, #used_spareable").on("keyup blur", function () {
                applyIndianFormat(this);
            });

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

            calculateSum();
            applyIndianFormat("#new_spareable");
            applyIndianFormat("#used_spareable");
            applyIndianFormat("#qty");
        });


    </script>
    

@endsection