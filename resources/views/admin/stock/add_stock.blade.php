@extends('layouts.frontend.admin_layout')
@section('page-content')

    <div class="content-page">
        <div class="container-fluid add-form-list">
            <div class="row">
                <div class="col-sm-12">

                    
                    {{ Breadcrumbs::render('admin_add_stock') }}
                    

                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Add or Edit Stock </h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <form class="needs-validation" novalidate method="POST" id="addStockForm"
                                action="{{ route('admin.stockSubmit') }}">
                                @csrf
                                <input type="hidden" name="id" id="id">

                                <div class="form-row">

                                    {{-- <div class="col-md-6 mb-3">
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
                                    </div> --}}

                                    <div class="col-md-6 mb-3">
                                        <label for="edp_code_id">EDP Code</label>
                                        <select class="form-control " name="edp_code" id="edp_code_id" required>
                                            <option value="" disabled selected>Select EDP Code</option>
                                            @foreach ($edpCodes as $edp)
                                                <option value="{{ $edp->id }}">{{ $edp->edp_code }}</option>
                                            @endforeach
                                        </select>
                                        @error('edp_code')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>


                                    <div class="col-md-6 mb-3">
                                        <label for="rig_id">Select Rig</label>

                                        @php
                                            $selectedRigId = old('rig_id', $LocationName->rig_id ?? $stock->rig_id ?? '');
                                            $isDisabled = !empty($selectedRigId);
                                        @endphp

                                        <select class="form-control select2" name="rig_id_display" id="rig_id" {{ $isDisabled ? 'disabled' : '' }} required>
                                            <option value="" disabled {{ $selectedRigId == '' ? 'selected' : '' }}>Select Rig
                                            </option>
                                            @foreach ($rigs as $rig)
                                                <option value="{{ $rig->id }}" {{ $selectedRigId == $rig->id ? 'selected' : '' }}>
                                                    {{ $rig->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                        {{-- Hidden field to submit actual value if select is disabled --}}
                                        @if ($isDisabled)
                                            <input type="hidden" name="rig_id" value="{{ $selectedRigId }}">
                                        @else
                                            {{-- Name must be rig_id if not disabled so it submits normally --}}
                                            <script>
                                                document.getElementById('rig_id').setAttribute('name', 'rig_id');
                                            </script>
                                        @endif

                                        @if ($errors->has('rig_id'))
                                            <div class="text-danger">{{ $errors->first('rig_id') }}</div>
                                        @endif
                                    </div>


                                    {{-- <div class="col-md-6 mb-3">
                                        <label for="location_ids">Location Id</label>
                                        <input type="text" class="form-control" name="location_id" placeholder="Location Id"
                                            value="{{ old('location_id', $LocationName?->location_id) }}" id="location_ids"
                                            required readonly>
                                        @error('location_id')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="location_name">Location Name</label>
                                        <input type="text" class="form-control" placeholder="Location Name"
                                            name="location_name" id="location_name"
                                            value="{{ old('location_name', $LocationName?->name) }}" required readonly>
                                        @error('location_name')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div> --}}

                                    <div class="col-md-6 mb-3 edp_detail" style="display: none;">
                                        <label for="category_id">Category</label>
                                        <input type="text" class="form-control" name="category" id="category_id" required
                                            readonly>
                                        <!--    <select class="form-control" name="category" id="category_id" required>
                                                                                                    <option selected disabled value="">Select Category...</option>
                                                                                                    <option value="Spares">Spares</option>
                                                                                                    <option value="Stores">Stores</option>
                                                                                                    <option value="Capital items">Capital items</option>
                                                                                                </select> -->
                                        <input type="hidden" name="category" id="category_hidden">
                                        @error('category')
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

                                    <div class="col-md-6 mb-3 edp_detail" style="display: none;">
                                        <label for="section_id">Section</label>
                                        <input type="text" class="form-control" name="section" id="section_id" required
                                            readonly>
                                        <!--    <select class="form-control" name="section" id="section_id" required>
                                                                                                    <option selected disabled value="">Select Section...</option>
                                                                                                    <option value="ENGG">ENGG</option>
                                                                                                    <option value="DRILL">DRILL</option>
                                                                                                </select> -->
                                        <input type="hidden" name="section" id="section_hidden">
                                        @error('section')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3 edp_detail" style="display: none;">
                                        <label for="measurement">Unit of Measurement</label>
                                        <input type="text" class="form-control" name="measurement" id="measurement" required
                                            readonly>
                                        @error('measurement')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="qty">Total Quantity</label>
                                        <input type="text" class="form-control" name="qty" id="qty" required readonly>
                                        @error('qty')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="new_spareable">New </label>
                                        <input type="text" class="form-control formatted-number" name="new_spareable"
                                            id="new_spareable" required>
                                        @error('new_spareable')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="used_spareable">Used </label>
                                        <input type="text" class="form-control formatted-number" name="used_spareable"
                                            id="used_spareable" required>
                                        @error('used_spareable')
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

                                </div>

                                <button class="btn btn-primary" type="submit">Submit Form</button>
                                <a href="{{ route('admin.add_stock') }}" class="btn btn-secondary">Reset</a>
                                <a href="{{ url()->previous() ?: route('admin.stock_list') }}" class="btn btn-light">Go
                                    Back</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function formatToIndianNumber(x) {
            if (!x) return '';
            x = x.toString().replace(/,/g, '');
            var afterPoint = '';
            if (x.indexOf('.') > 0)
                afterPoint = x.substring(x.indexOf('.'), x.length);
            x = Math.floor(x);
            x = x.toString();
            var lastThree = x.substring(x.length - 3);
            var otherNumbers = x.substring(0, x.length - 3);
            if (otherNumbers != '')
                lastThree = ',' + lastThree;
            return otherNumbers.replace(/\B(?=(\d{2})+(?!\d))/g, ",") + lastThree + afterPoint;
        }
    </script>

    <script>
        $(document).ready(function () {
            function toggleFields(show) {
                if (show) {
                    $(".edp_detail").fadeIn();
                } else {
                    $(".edp_detail").hide();
                }
            }

            function fetchEdpDetails() {
                const edpCode = $('#edp_code_id').val();
                const rigId = $('#rig_id').val();

                if (!edpCode || !rigId) {
                    toggleFields(false);
                    return;
                }

                $.ajaxSetup({
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
                });

                $.ajax({
                    type: "GET",
                    url: "{{ route('admin.get_edp_details') }}",
                    data: {
                        edp_code: edpCode,
                        rig_id: rigId
                    },
                    success: function (response) {
                        if (response.success) {
                            $("#category_id").val(response.edp.category).prop('disabled', true);
                            $("#category_hidden").val(response.edp.category);
                            $("#description").val(response.edp.description).prop('readonly', true);
                            $("#measurement").val(response.edp.measurement).prop('readonly', true);
                            $("#section_id").val(response.edp.section).prop('disabled', true);
                            $("#section_hidden").val(response.edp.section);

                            if (response.stock) {
                                $("#addStockForm").attr("action", "{{ route('admin.update_stock') }}");
                                $("#id").val(response.stock.id);
                                $("#qty").val(response.stock.qty);
                                $("#new_spareable").val(response.stock.new_spareable);
                                $("#used_spareable").val(response.stock.used_spareable);
                                $("#remarks").val(response.stock.remarks);

                                if (response.stock.rig_id) {
                                    $("#rig_id").val(response.stock.rig_id); // do not trigger change
                                }
                            } else {
                                $("#addStockForm").attr("action", "{{ route('admin.stockSubmit') }}");
                                $("#id").val('');
                                $("#qty").val('');
                                $("#new_spareable").val('');
                                $("#used_spareable").val('');
                                $("#remarks").val('');
                                // Keep current rig_id as-is
                            }

                            toggleFields(true);
                        } else {
                            alert("EDP details not found!");
                            toggleFields(false);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("AJAX Error:", status, error);
                        toggleFields(false);
                    }
                });
            }

            // Trigger fetch only when both inputs are selected
            $('#edp_code_id, #rig_id').on('change', function () {
                fetchEdpDetails();
            });

            toggleFields(false); // Hide fields initially
        });


        $(document).ready(function () {
            /*    function validateStock() {
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
                */
            function calculateSum() {
                var value1 = parseFloat(unformatIndianNumber($('#new_spareable').val())) || 0;
                var value2 = parseFloat(unformatIndianNumber($('#used_spareable').val())) || 0;
                var sum = value1 + value2;
                $('#qty').val(formatToIndianNumber(sum));
            }


            // Attach the keyup event to both input fields
            $('#new_spareable, #used_spareable').on('keyup', function () {
                calculateSum(); // Call the calculateSum function when either input changes
            });

            /*      $("#addStockForm").on("submit", function (e) {
                      if (!calculateSum()) {
                          e.preventDefault();
                      }
                  });
                  */

            function unformatIndianNumber(x) {
                return x.replace(/,/g, '');
            }

            function applyIndianFormat(id) {
                let value = $('#' + id).val();
                if (value) {
                    let plain = unformatIndianNumber(value);
                    if (!isNaN(plain)) {
                        $('#' + id).val(formatToIndianNumber(plain));
                    }
                }
            }

            $('#new_spareable, #used_spareable').on('blur', function () {
                applyIndianFormat(this.id);
            });

            $('#new_spareable, #used_spareable').on('focus', function () {
                // remove commas for editing
                let val = unformatIndianNumber($(this).val());
                $(this).val(val);
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
                let rawValue = $(field).val().trim();
                let value = rawValue.replace(/,/g, ''); // Remove commas for validation
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
    <script>
        $(document).ready(function () {
            $('#edp_code_id').select2({
                theme: 'bootstrap4', // Match Bootstrap theme
                placeholder: "Select EDP Code",
                allowClear: true,
                width: '100%' // Important for layout
            });
        });
    </script>




@endsection