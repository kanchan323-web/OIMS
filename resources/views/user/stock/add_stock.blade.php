@extends('layouts.frontend.layout')
@section('page-content')

    <div class="content-page">
        <div class="container-fluid add-form-list">
            <div class="row">
                <div class="col-sm-12">
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

                                    <div class="col-md-6 mb-3">
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

                                    <div class="col-md-6 mb-3">
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
                                    </div>

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
                                        <label for="qty">Available Quantity</label>
                                        <input type="number" class="form-control" name="qty" id="qty" required readonly>
                                        @error('qty')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="new_spareable">New </label>
                                        <input type="text" class="form-control" name="new_spareable" id="new_spareable"
                                            required>
                                        @error('new_spareable')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="used_spareable">Used </label>
                                        <input type="text" class="form-control" name="used_spareable" id="used_spareable"
                                            required>
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
                           // console.log("EDP & Stock Data:", response);

                            if (response.success) {
                                $("#category_id").val(response.edp.category).prop('disabled', true);
                                $("#category_hidden").val(response.edp.category);
                                $("#description").val(response.edp.description).prop('readonly', true);
                                $("#measurement").val(response.edp.measurement).prop('readonly', true);
                                $("#section_id").val(response.edp.section).prop('disabled', true);
                                $("#section_hidden").val(response.edp.section);

                                if (response.stock) {
                                    //console.log(response.stock);
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

            // Ensure fields remain hidden initially
            toggleFields(false);
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
                var value1 = parseFloat($('#new_spareable').val()) || 0; // Default to 0 if empty or invalid
                var value2 = parseFloat($('#used_spareable').val()) || 0; // Default to 0 if empty or invalid
                var sum = value1 + value2; // Calculate the sum
                $('#qty').val(sum); // Display the sum in the 'sum' input field
            }

            // Attach the keyup event to both input fields
            $('#new_spareable, #used_spareable').on('keyup', function() {
                calculateSum(); // Call the calculateSum function when either input changes
            });

  /*      $("#addStockForm").on("submit", function (e) {
            if (!calculateSum()) {
                e.preventDefault();
            }
        });
        */
     });
    </script>



@endsection
