@extends('layouts.frontend.layout')
@section('page-content')


    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    @if (Session::get('success'))
                        <div class="alert bg-success text-white alert-dismissible fade show" role="alert">
                            <strong>Success:</strong> {{ Session::get('success') }}
                            <button type="button" class="close close-dark" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    @if ($errors->has('email_error'))
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>Warning!</strong> {{ Session::get('email_error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
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
                    <div class="row justify-content-between">
                        <div class="col-sm-6 col-md-9">
                            <div id="user_list_datatable_info" class="dataTables_filter">
                                <form id="filterForm" class="mr-3 position-relative">
                                    <div class="row">
                                        <div class="col-md-2 mb-2">
                                            <label for="edp_code">EDP Code</label>
                                            <select class="form-control select2" name="edp_code" id="edp_code" required>
                                                <option disabled selected>Select EDP Code...</option>
                                                @foreach ($EDP_Code_ID as $edp_code_id)
                                                    <option value="{{ $edp_code_id->edp_code }}">{{ $edp_code_id->EDP_Code }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        


                                        <div class="col-md-2 mb-2">
                                            <label for="Description">Description</label>
                                            <input type="text" class="form-control" placeholder="Description"
                                                name="Description" id="Description">
                                        </div>


                                        <div class="col-md-2 mb-2">
                                            <label for="form_date">From Date</label>
                                            <input type="date" class="form-control" name="form_date" id="form_date">
                                        </div>


                                        <div class="col-md-2 mb-2">
                                            <label for="to_date">To Date</label>
                                            <input type="date" class="form-control" name="to_date" id="to_date">
                                        </div>


                                        <div class="col-md-4 mb-2 d-flex align-items-end">
                                            <button type="button" class="btn btn-primary mr-2"
                                                id="filterButton">Search</button>
                                            <a href="{{ route('stock_list.get') }}" class="btn btn-secondary ml-2">Reset</a>
                                            <!-- <a href="{{ route('stock_list_pdf') }}"
                                                class="btn btn-danger ml-2 d-flex align-items-center justify-content-center"
                                                id="downloadPdf" target="_blank">
                                                <i class="fas fa-file-pdf mr-1"></i> Export PDF
                                            </a> -->


                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>


                        <!-- <div class="col-sm-6 col-md-3">
                            <div class="user-list-files d-flex">
                                <a href="{{ route('add_stock') }}" class="btn btn-primary add-list"><i
                                        class="las la-plus mr-3"></i>Add Stock</a>
                                <a href="{{ route('import_stock') }}" class="btn btn-primary add-list"><i
                                        class="las la-plus mr-3"></i>Bulk Stocks </a>
                            </div>
                        </div> -->
                    </div>
                </div>


                <div class="col-lg-12">
                    <div class="table-responsive rounded mb-3">
                        <table class="data-tables table mb-0 tbl-server-info">
                            <thead class="bg-white text-uppercase">
                                <tr class="ligth ligth-data">
                                    <th>Sr.No</th>
                                    <th>Supplier Rig Name</th>
                                    <th>EDP</th>
                                    <th>Section</th>
                                    <th>Description</th>
                                    <th>Quantity</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="ligth-body" id="stockTable">
                                @if (isset($Stock_Table_Data) && $Stock_Table_Data != null)
                                    @foreach($Stock_Table_Data as $index => $stockdata)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $stockdata->name }}</td>
                                            <td>{{ $stockdata->edp_code }}</td>
                                            <td>{{ $stockdata->section }}</td>
                                            <td>{{ $stockdata->description }}</td>
                                            <td>
                                                {{ $stockdata->qty }}
                                                <span class="text-muted small">{{ $stockdata->measurement }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center list-action">
                                                    <!-- View Button (Always Visible) -->
                                                    <a class="badge badge-info mr-2" data-toggle="modal"
                                                        onclick="viewstockdata({{ $stockdata->id }})"
                                                        data-target=".bd-example-modal-xl" data-placement="top" title="View"
                                                        href="#">
                                                        <i class="ri-eye-line mr-0"></i>
                                                    </a>


                                                    <a class="badge badge-success mr-2" data-toggle="modal"
                                                        onclick="addRequest({{ $stockdata->id }})"
                                                        data-target=".bd-addRequest-modal-xl" data-placement="top" title="View"
                                                        href="#">
                                                        <i class="ri-arrow-right-circle-line"></i>
                                                    </a>


                                                    <!-- Edit Button (Only for Your Members) -->


                                                </div>


                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <td>No data exists</td>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>


            </div>
            
        </div>
    </div>




    <div class="modal fade" id="DeleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{route('Delete_stock')}}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Are you sure?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>You are about to delete <span class=""></span> and all of its contents.
                            </br>
                            <span style="font-weight:bold">This operation is permanent and cannot be undone.</span>
                        </p>
                        <input type="hidden" name="delete_id" id="delete_id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class=" btn btn-primary " data-dismiss="modal">Cancle</button>
                        <button type="submit" class=" btn btn-secondary ">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>




    <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">View Stock</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <!-- <form class="needs-validation" novalidate method="POST" action="" id="addStockForm"> -->


                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <label for="">Location Id</label>
                                <input type="text" class="form-control" name="location_id" placeholder=" Location Id"
                                    id="location_id" readonly>
                                <div class="invalid-feedback">
                                    Enter location id
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="">Location Name</label>
                                <input type="text" class="form-control" placeholder=" Location Name" name="location_name"
                                    id="location_name" readonly>
                                <div class="invalid-feedback">
                                    Enter Location Name
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="">EDP Code</label>
                                <input type="text" class="form-control" name="edp_code" placeholder=" EDP Code"
                                    id="edp_code_id" readonly>
                                <div class="invalid-feedback">
                                    Enter EDP Code
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="category">Category</label>
                                <input type="text" class="form-control" name="category" placeholder=" Category "
                                    id="category_id" readonly>
                                <input type="hidden" name="category" id="hidden_category">
                                <div class="invalid-feedback">
                                    Please select a category
                                </div>
                            </div>


                            <div class="col-md-6 mb-3">
                                <label for="">Description </label>
                                <textarea class="form-control" id="description_id" name="description"
                                    placeholder="Enter Description" readonly></textarea>
                                <div class="invalid-feedback">
                                    Enter Description
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="section">Section</label>
                                <input type="text" class="form-control" name="section" placeholder=" Section " id="section"
                                    readonly>
                                <input type="hidden" name="section" id="hidden_section">
                                <div class="invalid-feedback">
                                    Please select a Section
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="">Available Quantity</label>
                                <input type="text" class="form-control" placeholder=" Available Quantity" name="qty"
                                    id="qty" readonly>
                                <div class="invalid-feedback">
                                    Enter Available Quantity
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="">Unit of Measurement </label>
                                <input type="text" class="form-control" name="measurement" placeholder="Unit of Measurement"
                                    id="measurement" readonly>
                                <div class="invalid-feedback">
                                    Enter Unit of Measurement
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="">New Spareable </label>
                                <input type="text" class="form-control" placeholder=" New Spareable" name="new_spareable"
                                    id="new_spareable" readonly>
                                <div class="invalid-feedback">
                                    Enter New Spareable
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="">Used Spareable </label>
                                <input type="text" class="form-control" placeholder=" Used Spareable" name="used_spareable"
                                    id="used_spareable" readonly>
                                <div class="invalid-feedback">
                                    Enter Used Spareable
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="">Remarks / Notes </label>
                                <textarea class="form-control" id="remarks" name="remarks" placeholder=" Remarks / Notes"
                                    readonly></textarea>
                                <div class="invalid-feedback">
                                    Enter Remarks / Notes
                                </div>
                            </div>
                        </div>
                        <!-- <button class="btn btn-primary" type="submit">Submit form</button>
                                                                          <button type="reset" class="btn btn-danger">Reset</button>
                                                                          <a href="" class="btn btn-light">Go Back</a> -->
                        <!-- </form> -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                </div>
            </div>
        </div>
    </div>


    <!-- add request modal -->
    <div class="modal fade bd-addRequest-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Request for Stock </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <form class="needs-validation" novalidate method="POST" action="{{route('request_stock_add.post')}}"
                            id="AddRequestStock">
                            @csrf
                            <div class="form-row">
                                <div class="col-md-6 mb-3">
                                    <label for="">User Name</label>
                                    <input type="text" class="form-control" name="user_name"
                                        value="{{Auth::user()->user_name}}" placeholder="User Name" id="" required readonly>
                                    @error("user_name")
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                    <input type="hidden" class="form-control" name="requester_id"
                                        value="{{Auth::user()->id}}">
                                    @error("requester_id")
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                    <input type="hidden" class="form-control" name="requester_rig_id"
                                        value="{{Auth::user()->rig_id}}">
                                    @error("requester_rig_id")
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">EDP Code</label>
                                    <input type="text" class="form-control" name="edp_code" id="Redp_code" readonly>
                                    <input type="hidden" class="form-control" name="stock_id" id="Rstock_id" readonly>
                                    <input type="hidden" class="form-control" name="req_edp_id" id="req_edp_id" readonly>
                                    @error("stock_id")
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="category">Category</label>
                                    <input type="text" class="form-control" name="category" id="Rcategory" readonly>
                                    @error("category")
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="section">Section</label>
                                    <input type="text" class="form-control" name="section" id="Rsection" readonly>
                                    @error("section")
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">Unit of Measurement </label>
                                    <input type="text" class="form-control" name="measurement" id="Rmeasurement" value=""
                                        readonly>
                                    @error("measurement")
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">Description</label>
                                    <textarea class="form-control" id="Rdescription" name="description" readonly></textarea>
                                    @error("description")
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">Available Quantity</label>
                                    <input type="text" class="form-control" name="available_qty" id="Available_qty"
                                        readonly>
                                    @error("available_qty")
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>


                                <div class="col-md-6 mb-3">
                                    <label for="">Supplier Location Name</label>
                                    <input type="text" class="form-control" placeholder=" Supplier Location Name"
                                        id="Rsupplier_location_name" name="supplier_rig_id" required readonly>
                                    <input type="hidden" class="form-control" placeholder=" Supplier Location Name"
                                        id="Rsupplier_location_id" name="supplier_location_id" required readonly>
                                    @error("supplier_rig_id")
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                    <input type="hidden" class="form-control" name="supplier_id" placeholder=" "
                                        id="Rsupplier_id" required readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">Requested Quantity</label>
                                    <input type="number" class="form-control" name="requested_qty"
                                        placeholder="Requested Quantity" id="RequestQTY" required>


                                    <small id="qtyError" class="text-danger" style="display: none;"></small>
                                    @error("requested_qty")
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">Expected Delivery Date</label>
                                    <input type="date" class="form-control" name="expected_date" id="expected_date">
                                        <small class="text-primary">If date is not selected, system will automatically select 15 days from today as 'Expected Date'.</small>
                                </div>
                            </div>
                            <button class="btn btn-primary" id="AddRequestStock" type="submit">Submit Request</button>
                            <a href="{{route('stock_list.get')}}" class="btn btn-light">Go Back</a>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                </div>
            </div>
        </div>
    </div>





    <script>
        $(document).ready(function () {
            $("#AddRequestStock").on("submit", function () {
                // Show loader
                $("#loading").show();

                // Disable submit button to prevent multiple clicks
                $("#AddRequestStock button[type=submit]").prop("disabled", true);
            });
        });
    </script>


    <script>




        function addRequest(id) {
            var id = id;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "GET",
                url: "{{route('stock_list_view')}}",
                data: {
                    data: id
                },
                success: function (response) {
                   // console.log("stock id check:" + response.for_request_viewdata['EDPID']);
                  //  console.log("edp_code check:" + response.for_request_viewdata['edp_id']);
                    $("#Rlocation_id").val(response.for_request_viewdata['location_id']);
                    $("#Rlocation_name").val(response.for_request_viewdata['location_name']);
                    $("#Redp_code").val(response.viewdata['edp_code']);
                    $("#req_edp_id").val(response.for_request_viewdata['EDPID']);
                    $("#Rstock_id").val(id);
                    $("#Rstock_code").val(response.for_request_viewdata['id']);
                    var sectionValue = response.for_request_viewdata['section'];
                    $("#Rsection").val(sectionValue);
                    $("#Rhidden_section").val(sectionValue);
                    var categoryValue = response.for_request_viewdata['category'];
                    $("#Rcategory").val(categoryValue);
                    $("#Rhidden_category").val(categoryValue);
                    $("#Available_qty").val(response.for_request_viewdata['qty']);
                    $("#RequestQTY").on("input", function () {
                        let availableQty = parseFloat($("#Available_qty").val()) || 0;
                        let requestQty = parseFloat($(this).val()) || 0;
                        if (requestQty > availableQty) {
                            $("#RequestQTY").addClass("is-invalid");
                            $("#qtyError").text(
                                "Requested quantity cannot be greater than available quantity!").show();
                        } else {
                            $("#RequestQTY").removeClass("is-invalid");
                            $("#qtyError").hide();
                        }
                    });
                  //  console.log(response.viewdata);
                    $("#Rmeasurement").val(response.viewdata['measurement']);
                    $("#Rnew_spareable").val(response.viewdata['new_spareable']);
                    $("#Rused_spareable").val(response.viewdata['used_spareable']);
                    $("#Rremarks").val(response.viewdata['remarks']);
                    $("#Rdescription").val(response.viewdata['description']);
                    $("#Rsupplier_location_name").val(response.viewdata['location_name']);
                    $("#Rsupplier_location_id").val(response.viewdata['rig_id']);
                    $("#Rsupplier_id").val(response.viewdata['user_id']);
                  //  console.log(response.viewdata['location_id']);
                }
            });
        }


        function viewstockdata(id) {
            var id = id;
            // console.log(id);
            // return false;


            $.ajaxSetup({
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "GET",
                url: "{{route('stock_list_view')}}",
                data: {
                    data: id
                },
                success: function (response) {
                    console.log(response.viewdata);
                    $("#location_id").val(response.viewdata['location_id']);
                    $("#location_name").val(response.viewdata['location_name']);
                    $("#edp_code_id").val(response.viewdata['edp_code']);




                    var sectionValue = response.viewdata['section'];
                    $("#section").val(sectionValue);
                    $("#hidden_section").val(sectionValue);


                    var categoryValue = response.viewdata['category'];
                    $("#category_id").val(categoryValue);
                    $("#hidden_category").val(categoryValue);




                    $("#qty").val(response.viewdata['qty']);
                    $("#measurement").val(response.viewdata['measurement']);
                    $("#new_spareable").val(response.viewdata['new_spareable']);
                    $("#used_spareable").val(response.viewdata['used_spareable']);
                    $("#remarks").val(response.viewdata['remarks']);
                    $("#description_id").val(response.viewdata['description']);
                }
            });
        }








        $(document).ready(function () {
            // Filter Stock Data on Button Click
            $("#filterButton").click(function () {
                $.ajax({
                    type: "GET",
                    url: "{{ route('request_stock_filter.get') }}",
                    data: $("#filterForm").serialize(),
                    success: function (response) {
                        let tableBody = $("#stockTable");
                        tableBody.empty();
                        if (response.data && response.data.length > 0) {
                            $.each(response.data, function (index, stockdata) {
                                let editButton = '';
                                if (response.datarig.includes(stockdata.user_id)) {
                                    editButton = `
                                        <a class="badge badge-success mr-2" data-toggle="modal"
                                                onclick="makeRequest(${stockdata.id})"
                                                data-target=".bd-makerequest-modal-xl" data-placement="top" title="View"
                                                href="#">
                                                <i class="ri-arrow-right-circle-line"></i>
                                            </a>
                                        `;
                                }
                                tableBody.append(`
                                    <tr>
                                        <td>${index + 1}</td>
                                        <td>${stockdata.location_name}</td>
                                        <td>${stockdata.EDP_Code}</td>
                                        <td>${stockdata.section}</td>
                                        <td>${stockdata.description}</td>
                                        <td>${stockdata.qty}
                                            <span class="text-muted small">${stockdata.measurement}</span>
                                            </td>
                                        <td>


                                            <a class="badge badge-info mr-2" data-toggle="modal"
                                                onclick="viewstockdata(${stockdata.id})"
                                                data-target=".bd-example-modal-xl" data-placement="top" title="View"
                                                href="#">
                                                <i class="ri-eye-line mr-0"></i>
                                            </a>


                                            <a class="badge badge-success mr-2" data-toggle="modal"
                                                onclick="addRequest(${stockdata.id})"
                                                data-target=".bd-addRequest-modal-xl" data-placement="top" title="View"
                                                href="#">
                                                <i class="ri-arrow-right-circle-line"></i>
                                            </a>
                                            ${editButton}
                                        </td>
                                    </tr>
                                `);
                            });
                        } else {
                            tableBody.append(
                                `<tr><td colspan="7" class="text-center">No records found</td></tr>`
                            );
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("Error fetching data:", error);
                    }
                });
            });
        });






        function deleteStockdata(id) {


            $("#delete_id").val(id);


        }


        $(document).ready(function () {
            $("#selectAll").on("change", function () {
                $(".row-checkbox").prop("checked", $(this).prop("checked"));
            });


            $(".row-checkbox").on("change", function () {
                if ($(".row-checkbox:checked").length === $(".row-checkbox").length) {
                    $("#selectAll").prop("checked", true);
                } else {
                    $("#selectAll").prop("checked", false);
                }
            });
        });
        $(document).ready(function () {
            $("#downloadPdf").click(function (e) {
                e.preventDefault();


                let baseUrl = "{{ route('stock_list_pdf') }}";
                let formData = $("#filterForm").serializeArray();


                let filteredParams = formData
                    .filter(item => item.value.trim() !== "")
                    .map(item => `${encodeURIComponent(item.name)}=${encodeURIComponent(item.value)}`)
                    .join("&");


                let finalUrl = filteredParams ? `${baseUrl}?${filteredParams}` : baseUrl;
                window.open(finalUrl, '_blank');
            });
        });


        $(document).ready(function () {
            // Automatically fade out alerts after 3 seconds
            setTimeout(function () {
                $(".alert").fadeOut("slow");
            }, 3000);
        });
    </script>
    <script>
        $(document).ready(function () {
            $('#edp_code').select2({
                theme: 'bootstrap4', // for Bootstrap look
                placeholder: "Select EDP Code...",
                allowClear: true,
                width: '100%' // ensures it matches Bootstrap form-control
            });
        });
    </script>
    



@endsection
