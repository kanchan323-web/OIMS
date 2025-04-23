@extends('layouts.frontend.layout')
@section('page-content')

    <div class="content-page">
        <div class="container-fluid add-form-list">
            <div class="row">
                <div class="col-sm-12">
                    {{ Breadcrumbs::render('add_stock') }}

                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Add or Edit Stock</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <form class="needs-validation" novalidate method="POST" id="addStockForm"
                                action="{{ route('stockSubmit') }}">
                                @csrf
                                <input type="hidden" name="id" id="id">

                                <div class="form-row">

                                    <div class="col-md-4 mb-4">
                                        <label for="edp_code_id">EDP Code</label>
                                        <select class="form-control select2" name="edp_code" id="edp_code_id" required>
                                            <option value="" disabled selected>Select EDP Code</option>
                                            @foreach ($edpCodes as $edp)
                                                <option value="{{ $edp->id }}">{{ $edp->edp_code }}</option>
                                            @endforeach
                                        </select>
                                        @error('edp_code')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <label for="location_ids">Location Id</label>
                                        <input type="text" class="form-control" name="location_id" placeholder="Location Id"
                                            value="{{ old('location_id', $LocationName?->location_id) }}" id="location_ids"
                                            required readonly>
                                        @error('location_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <label for="location_name">Location Name</label>
                                        <input type="text" class="form-control" placeholder="Location Name"
                                            name="location_name" id="location_name"
                                            value="{{ old('location_name', $LocationName?->name) }}" required readonly>
                                        @error('location_name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-4 edp_detail" style="display: none;">
                                        <label for="category_id">Category</label>
                                        <input type="text" class="form-control" name="category" id="category_id" required
                                            readonly>
                                        <input type="hidden" name="category" id="category_hidden">
                                        @error('category')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-4 edp_detail" style="display: none;">
                                        <label for="section_id">Section</label>
                                        <input type="text" class="form-control" name="section" id="section_id" required
                                            readonly>
                                        <input type="hidden" name="section" id="section_hidden">
                                        @error('section')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-4 edp_detail" style="display: none;">
                                        <label for="measurement">Unit of Measurement</label>
                                        <input type="text" class="form-control" name="measurement" id="measurement" required
                                            readonly>
                                        @error('measurement')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3 edp_detail" style="display: none;">
                                        <label for="description">Description</label>
                                        <textarea class="form-control" name="description" id="description" required
                                            readonly></textarea>
                                        @error('description')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="remarks">Remarks / Notes</label>
                                        <textarea class="form-control" name="remarks" id="remarks" required></textarea>
                                        @error('remarks')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <label for="qty">Total Quantity</label>
                                        <input type="number" class="form-control" name="qty" id="qty" required readonly>
                                        @error('qty')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <label for="new_spareable">New </label>
                                        <input type="text" class="form-control" name="new_spareable" id="new_spareable"
                                            required>
                                        @error('new_spareable')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <label for="used_spareable">Used </label>
                                        <input type="text" class="form-control" name="used_spareable" id="used_spareable"
                                            required>
                                        @error('used_spareable')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <button class="btn btn-success" type="submit">Submit Stock</button>
                                <a href="{{ route('add_stock') }}" class="btn btn-secondary">Reset</a>
                                <a href="{{ route('stock_list') }}" class="btn btn-light">Go Back</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // ✅ Define helper functions first
        const unitTypes = {
            'EA': 'integer', 'KIT': 'integer', 'PAA': 'integer', 'PAC': 'integer', 'ROL': 'integer', 'ST': 'integer',
            'FT': 'decimal', 'GAL': 'decimal', 'KG': 'decimal', 'KL': 'decimal', 'L': 'decimal', 'LB': 'decimal',
            'M': 'decimal', 'M3': 'decimal', 'MT': 'decimal', 'NO': 'integer'
        };

        function formatToIndianNumber(x) {
            x = x.toString();
            let parts = x.split(".");
            let intPart = parts[0].replace(/,/g, '');
            let decimalPart = parts[1] || "";

            let lastThree = intPart.slice(-3);
            let otherNumbers = intPart.slice(0, -3);

            if (otherNumbers !== '') {
                lastThree = ',' + lastThree;
            }

            let formattedInt = otherNumbers.replace(/\B(?=(\d{2})+(?!\d))/g, ",") + lastThree;
            return decimalPart ? formattedInt + "." + decimalPart : formattedInt;
        }

        function cleanNumber(numStr) {
            return numStr.replace(/,/g, '');
        }

        function validateInput(field) {
            let unit = $("#measurement").val()?.trim();
            let formattedVal = $(field).val().trim();
            let rawVal = formattedVal.replace(/,/g, '');

            let isValid = true;
            let errorMsg = "";

            let rawVal1 = parseFloat($("#new_spareable").val().replace(/,/g, '') || 0);
            let rawVal2 = parseFloat($("#used_spareable").val().replace(/,/g, '') || 0);
            let total = rawVal1 + rawVal2;

            if (unit && unitTypes[unit]) {
                if (unitTypes[unit] === 'integer') {
                    isValid = /^\d+$/.test(rawVal);
                    if (!isValid) {
                        errorMsg = "Only whole numbers allowed!";
                    } else if (total > 10) {
                        isValid = false;
                        errorMsg = "Total spareable qty cannot exceed 10!";
                    }
                } else if (unitTypes[unit] === 'decimal') {
                    if (/^\d+(\.\d+)?$/.test(rawVal)) {
                        let decimalPart = rawVal.includes(".") ? rawVal.split(".")[1] : "";
                        isValid = decimalPart.length <= 10;
                        if (!isValid) {
                            errorMsg = "Max 10 decimal places allowed!";
                        }
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

        function calculateSum() {
            var value1 = parseFloat(cleanNumber($('#new_spareable').val())) || 0;
            var value2 = parseFloat(cleanNumber($('#used_spareable').val())) || 0;
            var sum = value1 + value2;
            $('#qty').val(sum);
            $('#qty_display').text(formatToIndianNumber(sum));
            $('#qty').data('raw', sum); // Store raw value if needed
        }

        $(document).ready(function () {
            function toggleFields(show) {
                if (show) {
                    $(".edp_detail").fadeIn();
                } else {
                    $(".edp_detail").hide();
                }
            }

            $('#edp_code_id').on('change', function () {
                var edpCode = $(this).val();

                if (edpCode) {
                    toggleFields(true);
                    $.ajaxSetup({
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
                    });

                    $.ajax({
                        type: "GET",
                        url: "{{ route('get_edp_details') }}",
                        data: { edp_code: edpCode },
                        success: function (response) {
                            if (response.success) {
                                $("#category_id").val(response.edp.category).prop('disabled', true);
                                $("#category_hidden").val(response.edp.category);
                                $("#description").val(response.edp.description).prop('readonly', true);
                                $("#measurement").val(response.edp.measurement).prop('readonly', true);
                                $("#section_id").val(response.edp.section).prop('disabled', true);
                                $("#section_hidden").val(response.edp.section);

                                if (response.stock) {
                                    $("#addStockForm").attr("action", "{{ route('update_stock') }}");
                                    $("#id").val(response.stock.id);
                                    $("#qty").val(response.stock.qty);
                                    $("#new_spareable").val(response.stock.new_spareable);
                                    $("#used_spareable").val(response.stock.used_spareable);
                                    $("#remarks").val(response.stock.remarks);
                                } else {
                                    $("#addStockForm").attr("action", "{{ route('stockSubmit') }}");
                                    $("#id").val('');
                                    $("#qty").val('');
                                    $("#new_spareable").val('');
                                    $("#used_spareable").val('');
                                    $("#remarks").val('');
                                }
                            } else {
                                alert("EDP details not found!");
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error("AJAX Error:", status, error);
                        }
                    });
                } else {
                    toggleFields(false);
                }
            });

            toggleFields(false);

            $("#new_spareable, #used_spareable").on("input", function () {
                validateInput(this);
                calculateSum();
            });

            // Add this for formatting when focus is lost
            $("#new_spareable, #used_spareable").on("blur", function () {
                let input = $(this);
                let cleanVal = input.val().replace(/,/g, '');
                if (!isNaN(cleanVal) && cleanVal !== '') {
                    let formattedVal = formatToIndianNumber(cleanVal);
                    input.val(formattedVal);
                }
            });

            $("#measurement").on("change", function () {
                $("#new_spareable, #used_spareable").trigger("input");
            });

            $("#addStockForm").on("submit", function () {
                $('#new_spareable').val(cleanNumber($('#new_spareable').val()));
                $('#used_spareable').val(cleanNumber($('#used_spareable').val()));
                $('#qty').val(cleanNumber($('#qty').val()));
            });

            // Trigger initial input validation
            $("#new_spareable, #used_spareable").trigger("input");
        });
    </script>

    <script>
        $(document).ready(function () {
            $('#edp_code_id').select2({
                theme: 'bootstrap4', // Optional, for Bootstrap look
                placeholder: "Select EDP Code",
                allowClear: true,
                width: '100%' // Matches form-control width
            });
        });
    </script>




@endsection
