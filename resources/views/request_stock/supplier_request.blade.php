@extends('layouts.frontend.layout')
@section('page-content')

    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    {{Breadcrumbs::render('Raised_Requests_List')}}
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

                    <div class="row justify-content-between">
                        <div class="col-md-9">
                            <div id="user_list_datatable_info" class="dataTables_filter">
                                <form id="filterForm" action="{{ route('request_stock_filter') }}" method="post" class="mr-3 position-relative">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-2 mb-2">
                                            <label for="edp_code">EDP Code</label>
                                            <select class="form-control filter-input select2" name="edp_code" id="edp_code" required>
                                                <option disabled selected>Select EDP Code...</option>
                                                @foreach ($edps as $edp)
                                                    <option value="{{ $edp->id }}">{{ $edp->edp_code }}</option>
                                                @endforeach
                                            </select>
                                        </div>


                                        <div class="col-md-2 mb-2">
                                            <label for="location_name">Location Name</label>
                                            <input type="text" class="form-control filter-input"
                                                placeholder="Location Name" name="location_name" id="location_name"
                                                value="{{ request('location_name') }}">
                                        </div>

                                        <div class="col-md-2 mb-2">
                                            <label for="form_date">From Date</label>
                                            <input type="date" class="form-control filter-input" name="form_date"
                                                id="form_date" value="{{ request('form_date') }}">
                                        </div>

                                        <div class="col-md-2 mb-2">
                                            <label for="to_date">To Date</label>
                                            <input type="date" class="form-control filter-input" name="to_date"
                                                id="to_date" value="{{ request('to_date') }}">
                                        </div>

                                        <div class="col-md-4 mb-2 d-flex align-items-end">
                                            <button type="button" id="filterButton" class="btn btn-primary mr-2">Search</button>
                                            <button type="button" id="resetButton" class="btn btn-secondary">Reset</button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="table-responsive rounded mb-3">
                        <table class="data-tables table mb-0 tbl-server-info">
                            <thead class="bg-white text-uppercase">
                                <tr class="ligth ligth-data">
                                    <th>Sr.No</th>
                                    <th>Request Id</th>
                                    <th>Supplier Rig </th>
                                    <th>EDP Code</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="stockTable" class="ligth-body">
                                @foreach($data as $index => $stockdata)
                                                        @if(!in_array($stockdata->user_id, $datarig))
                                                                                <tr>
                                                                                    <td>{{ $index + 1 }}</td>
                                                                                    <td>{{ $stockdata->RID }}</td>
                                                                                    <td>{{ $stockdata->location_name }}</td>
                                                                                    <td>{{ $stockdata->edp_code }}</td>
                                                                                    <td>{{ $stockdata->description }}</td>
                                                                                    @php
                                                                                        $statusColors = [
                                                                                            'Pending' => 'badge-warning',
                                                                                            'Approve' => 'badge-success',
                                                                                            'Decline' => 'badge-danger',
                                                                                            'Query' => 'badge-info',
                                                                                            'Received' => 'badge-primary',
                                                                                            'MIT' => 'badge-purple'
                                                                                        ];
                                                                                        $badgeClass = $statusColors[$stockdata->status_name] ?? 'badge-secondary';
                                                                                    @endphp
                                                                                    <td><span class="badge {{ $badgeClass }}">{{ $stockdata->status_name }}</span></td>
                                                                                    <td>{{ $stockdata->created_at->format('d-m-Y') }}</td>
                                                                                    <td>
                                                                                        <a class="badge badge-success mr-2" data-toggle="modal"
                                                                                            onclick="RequestStockData({{ json_encode($stockdata->id) }})"
                                                                                            data-target=".bd-example-modal-xl" data-placement="top" title="Action by Supplier">
                                                                                            <i class="ri-arrow-right-circle-line"></i>
                                                                                        </a>
                                                                                        @php
                                                                                            $hasUnread = $stockdata->requestStatuses->where('is_read', 0)->count() > 0;
                                                                                        @endphp
                                                                                        <a class="badge badge-info position-relative"
                                                                                            onclick="ViewRequestStatus({{ $stockdata->id }})" data-toggle="modal"
                                                                                            data-placement="top" title="Display Request Status">
                                                                                            <i class="ri-eye-line"></i>
                                                                                            @if($hasUnread)
                                                                                                <span
                                                                                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                                                                                    ●
                                                                                                </span>
                                                                                            @endif
                                                                                        </a>
                                                                                    </td>
                                                                                </tr>
                                                        @endif
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> Action Performed by Supplier</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card-body">

                        <form id="mainModalForm">
                            <input type="hidden" id="request_id" name="request_id">
                            <div class="form-row">
                              {{-- <div class="col-md-6 mb-3">
                                    <label for="">Requester Name</label>
                                    <input type="text" class="form-control" name="" placeholder="Requester Name"
                                        id="location_id" readonly>
                                    <div class="invalid-feedback">
                                        Enter EDP Code
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">Requester Rig Name</label>
                                    <input type="text" class="form-control" placeholder="Requester Rig Name" name=""
                                        id="requester_Id" readonly>
                                    <div class="invalid-feedback">
                                        Enter Requester Rig Name
                                    </div>
                                </div> --}}
                                <div class="col-md-4 mb-4">
                                    <label for="">EDP Code</label>
                                    <input type="text" class="form-control" name="" placeholder="EDP Code" id="EDP_Code"
                                        readonly>
                                    <div class="invalid-feedback">
                                        Enter EDP Code
                                    </div>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <label for="">Supplier Name</label>
                                    <input type="text" class="form-control" name="" placeholder="Supplier Name"
                                        id="Supplier_Location_Id" readonly>
                                    <div class="invalid-feedback">
                                        Supplier Name
                                    </div>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <label for="">Supplier Rig Name</label>
                                    <input type="text" class="form-control" name="" placeholder="Supplier Rig Name"
                                        id="Supplier_Location_Name" readonly>
                                    <div class="invalid-feedback">
                                        Supplier Rig Name
                                    </div>
                                </div>

                                <div class="col-md-4 mb-4">
                                    <label for="category">Category</label>
                                    <input type="text" class="form-control" placeholder="Category" id="category_id"
                                        name="category" readonly>
                                    <div class="invalid-feedback">
                                        Enter Category Name
                                    </div>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <label for="section">Section</label>
                                    <input type="text" class="form-control" placeholder="Section" name="section"
                                        id="section" readonly>
                                    <div class="invalid-feedback">
                                        Enter Section Name
                                    </div>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <label for="">Unit of Measurement </label>
                                    <input type="text" class="form-control" name="measurement"
                                        placeholder="Unit of Measurement" id="measurement" readonly>
                                    <div class="invalid-feedback">
                                        Enter Unit of Measurement
                                    </div>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <label for="">Description </label>
                                    <textarea class="form-control" id="description" name="description"
                                        placeholder="Description" readonly></textarea>
                                    <div class="invalid-feedback">
                                        Enter Description
                                    </div>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <label for="">Supplier Total Quantity</label>
                                    <input type="text" class="form-control" placeholder="Supplier Total Quantity"
                                        name="total_qty" id="total_qty" readonly>
                                    <div class="invalid-feedback">
                                        Supplier Total Quantity
                                    </div>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <label for="">Requester Requested Quantity</label>
                                    <input type="text" class="form-control" placeholder="Requested Quantity" name="req_qty"
                                        id="req_qty" readonly>
                                    <div class="invalid-feedback">
                                        Enter Requested Quantity
                                    </div>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <label for="">New  </label>
                                    <input type="text" class="form-control" placeholder="New Spearable "
                                        name="new_spareable" id="new_spearable" readonly>
                                    <div class="invalid-feedback">
                                        Enter New
                                    </div>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <label for="">Used  </label>
                                    <input type="text" class="form-control" placeholder="Used Spareable"
                                        name="used_spareable" id="used_spareable" readonly>
                                    <div class="invalid-feedback">
                                        Enter Used
                                    </div>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <label for="">Status </label>
                                    <input type="text" class="form-control" name="status" id="status" readonly>
                                    <div class="invalid-feedback">
                                        Enter Status
                                    </div>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <label for="request_date">Request Date</label>
                                    <input type="text" class="form-control" placeholder="Request Date" name="request_date"
                                        id="request_date" readonly>
                                    <div class="invalid-feedback">
                                        Enter Request Date
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label id="status_label" style="display:none">Supplier Status Message</label>
                                    <span id="status_msg"></span>
                                </div>

                            </div>

                            <div class="d-flex justify-content-center mt-4">
                                <button class="btn btn-danger mx-2 decline_btn" type="button"
                                    data-target="#declineReasonModal">
                                    Decline
                                </button>

                                <button class="acceptanc_button btn btn-info mx-2" type="button" data-toggle="modal"
                                    data-target="#acba" style="display: none;">
                                    Accept
                                </button>


                                <button class="btn btn-success mx-2" type="button" id="openReceivedRequestModal">
                                    Acknowledge the Receipt
                                </button>
                                <button class="btn btn-primary mx-2" type="button" data-toggle="modal"
                                    data-target="#raiseQueryModal">
                                    Raise Query
                                </button>

                            </div>

                            <!-- Decline Request Modal -->
                            <div class="modal fade" id="declineReasonModal" tabindex="-1" role="dialog"
                                aria-labelledby="declineReasonLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="declineReasonLabel">Enter Decline Reason</h5>
                                            <button type="button" class="close sub-modal-close" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="declineForm">
                                                @csrf
                                                <div class="form-group">
                                                    <label for="decline_reason">Reason for Declining</label>
                                                    <textarea class="form-control" id="decline_reason" name="decline_reason"
                                                        rows="3" required></textarea>
                                                </div>
                                                <div class="d-flex justify-content-center mt-3">
                                                    <button type="button"
                                                        class="btn btn-secondary mx-2 sub-modal-close">Cancel</button>
                                                    <button type="button" class="btn btn-danger mx-2"
                                                        onclick="declineRequest()">Submit</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Raise Query Modal -->
                            <div class="modal fade" id="raiseQueryModal" tabindex="-1" role="dialog"
                                aria-labelledby="raiseQueryLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="raiseQueryLabel">Enter Query</h5>
                                            <button type="button" class="close sub-modal-close" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="raiseQueryForm" method="post">
                                                @csrf
                                                <div class="form-group">
                                                    <label for="query">Query</label>
                                                    <textarea class="form-control" id="query" name="query" rows="3"
                                                        required></textarea>
                                                </div>
                                                <div class="d-flex justify-content-center mt-3">
                                                    <button type="button"
                                                        class="btn btn-secondary mx-2 sub-modal-close">Cancel</button>
                                                    <button type="button" class="btn btn-primary mx-2"
                                                        onclick="submitQuery()">Submit</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Confirmation Modal -->
                            <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog"
                                aria-labelledby="confirmationModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="confirmationModalLabel">Confirm Action</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to acknowledge this request?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Cancel</button>
                                            <button type="button" class="btn btn-success"
                                                id="confirmAccept">Confirm</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                </div>
            </div>
        </div>
    </div>



    <!-- Request Status Modal -->
    <div class="modal fade" id="requestStatusModal" tabindex="-1" role="dialog" aria-labelledby="requestStatusModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header bg-white text-uppercase">
                    <h5 class="modal-title ligth ligth-data" id="requestStatusModalLabel">Request Status Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive rounded mb-3">
                        <table class="data-tables table mb-0 tbl-server-info">
                            <thead class="bg-white text-uppercase">
                                <tr class="ligth ligth-data">
                                    <th>Status</th>
                                    <th>Message</th>
                                    <th>Total Qty</th>
                                    <th>New</th>
                                    <th>Used</th>
                                    <th>Supplier</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody id="requestStatusData" class="ligth-body">
                                <!-- Data will be populated dynamically -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Submodal for Viewing Message -->
    <div class="modal fade" id="subModal" tabindex="-1" role="dialog" aria-labelledby="subModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-white">
                    <h5 class="modal-title" id="subModalLabel">Message Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="subModalMessageContent"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary d-none" id="subModalQueryButton">Query</button>
                </div>
            </div>
        </div>
    </div>

    {{-- 711 work here --}}
    <div class="modal fade" id="acba" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="">Proceed to Confirmation </h5>
                    <button type="button" class="close sub-modal-close" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="acceptPendingRequestForm">
                        <input type="hidden" value="" name="request_id" id="acceptPendingfield">
                        @csrf
                        <strong>
                            <div class="modal-body">
                                Are you sure you want to Accept this request &nbsp; ?
                            </div>
                            <hr>
                        </strong>
                        <div class="d-flex justify-content-center mt-3">
                            <button type="button" class="btn btn-secondary mx-2 sub-modal-close">Cancel</button>
                            <button type="button" class="btn mx-2 btn-info accept_confirmation"
                                onclick="acceptbtn()">Accept</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



    {{-- 711 work here --}}

    <script>

        $(document).ready(function() {
            $('[data-toggle="modal"]').tooltip(); // this enables tooltip on modal trigger
        });

        $(document).ready(function () {
            function fetchFilteredData() {
                $.ajax({
                    type: "GET",
                    url: "{{ route('raised_requests.filter') }}",
                    data: $("#filterForm").serialize(),
                    success: function (response) {
                        let tableBody = $("#stockTable");
                        tableBody.empty();

                        if (response.data && response.data.length > 0) {
                            $.each(response.data, function (index, stockdata) {
                                let badgeClass = {
                                    'Pending': 'badge-warning',
                                    'Approve': 'badge-success',
                                    'Decline': 'badge-danger',
                                    'Query': 'badge-info',
                                    'Received': 'badge-primary',
                                    'MIT': 'badge-purple'
                                }[stockdata.status_name] || 'badge-secondary';

                                tableBody.append(`
                                                                <tr>
                                                                    <td>${index + 1}</td>
                                                                    <td>${stockdata.RID}</td>
                                                                    <td>${stockdata.location_name}</td>
                                                                    <td>${stockdata.edp_code}</td>
                                                                     <td>${stockdata.description}</td>
                                                                    <td><span class="badge ${badgeClass}">${stockdata.status_name}</span></td>
                                                                    <td>${stockdata.created_at ? stockdata.creation_date : '-'}</td>
                                                                    <td>
                                                                        <a class="badge badge-success mr-2" data-toggle="modal"
                                                                                onclick="RequestStockData(${stockdata.id})"
                                                                                data-target=".bd-example-modal-xl" data-placement="top"
                                                                                title="Supplier Request" href="#">
                                                                                <i class="ri-arrow-right-circle-line"></i>
                                                                            </a>
                                                                        <a class="badge badge-info" onclick="ViewRequestStatus(${stockdata.id})"
                                                                                data-toggle="modal" data-placement="top" title="View Request Status" href="#">
                                                                                <i class="ri-eye-line"></i>
                                                                            </a>
                                                                    </td>
                                                                </tr>
                                                            `);
                            });
                        } else {
                            tableBody.append(`<tr><td colspan="5" class="text-center">No records found</td></tr>`);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("Error fetching data:", error);
                    }
                });
            }

            // Submit button triggers the AJAX call
            $("#filterButton").click(function (e) {
                e.preventDefault(); // Prevent form submission
                fetchFilteredData();
            });

            // Reset button to clear filters and reload data
            $("#resetButton").click(function () {
                window.location.href = "{{ route('raised_requests.index') }}";
            });

            // Auto-hide success/error messages
            setTimeout(function () {
                $(".alert").fadeOut("slow");
            }, 3000);
        });



        function RequestStockData(id) {
            $(".decline_btn").hide();
            $('#status_msg').text('').removeClass();
            $("#status_label").css('display', 'none');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "GET",
                url: "{{ route('request_stock_view.get') }}",
                data: { data: id },
                success: function (response) {
                    if (!response || !response.data) {
                        console.error("No valid data received.");
                        return;
                    }

                    var stockData = Array.isArray(response.data) ? response.data : [response.data];
                    if (stockData.length > 0 && stockData[0] !== null) {
                        var stock = stockData[0];
                        if (typeof stock === "object") {

                            //  711 working here
                            if (stock.status == 2) {
                                $(".acceptanc_button").show();
                                $("#acceptPendingfield").val(stock.id ?? '');
                            } else {
                                $(".acceptanc_button").hide();
                            }
                            //  711 working here

                            $("#request_id").val(stock.id ?? '');
                            $("#location_id").val(stock.requester_name ?? '');
                            $("#Supplier_Location_Id").val(stock.supplier_name ?? '');
                            $("#requester_Id").val(stock.requesters_rig ?? '');
                            $("#Supplier_Location_Name").val(stock.suppliers_rig ?? '');
                            $("#EDP_Code").val(stock.edp_code ?? '');
                            $("#category_id").val(stock.category ?? '');
                            $("#section").val(stock.section ?? '');
                            $("#description").val(stock.description ?? '');
                            $("#total_qty").val(stock.available_qty ?? '');
                            $("#req_qty").val(stock.requested_qty ?? '');
                            $("#measurement").val(stock.measurement ?? '');
                            $("#new_spearable").val(stock.new_spareable ?? '');
                            $("#used_spareable").val(stock.used_spareable ?? '');
                            $("#remarks").val(stock.remarks ?? '');
                            $("#status").val(stock.status_name ?? '');
                            if (response.request_status !== null) {
                                if (stock.status == 5) {
                                    $("#status_label").css('display', 'block');
                                    $("#status_msg").text(response.request_status['decline_msg']).addClass('text-danger');
                                }
                                if (stock.status == 2) {
                                    $("#status_label").css('display', 'block');
                                    $('#status_msg').text(response.request_status['query_msg']).addClass('text-primary');
                                }
                            }
                            console.log(stock.status);
                            $("#request_date").val(stock.formatted_created_at ?? '');
                            //debugger;
                            if (stock.status == 4) {
                                $(".btn-success, .btn-primary").hide();
                                $(".decline_btn").hide();
                            } else if (stock.status == 6) {
                                $(".btn-primary").hide();
                                $(".btn-success").show();
                                $(".decline_btn").hide();
                            } else if (stock.status == 5 || stock.status == 3) {
                                $(".btn-primary, .btn-success").hide();
                            } else if (stock.status == 2) {
                                $(".btn-primary").show();
                                $(".btn-success").hide();
                                $(".decline_btn").show();
                            } else if (stock.status == 1) {
                                $(".btn-primary, .btn-success").hide();
                                $(".decline_btn, .btn-primary").show();
                            }
                            else {
                                $(".btn-primary, .btn-success").show();
                            }

                        } else {
                            console.error("Stock data is not a valid object:", stock);
                        }
                    } else {
                        console.error("No stock data available.");
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error fetching stock data:", error);
                }
            });
        }


        //for Accept modal popup and changing status to Approve
        $(document).ready(function () {
            $("#openReceivedRequestModal").click(function (e) {
                e.preventDefault();
                let requestId = $("#request_id").val();
                let requestStatus = $("#request_status").val();

                if (!requestId) {
                    Swal.fire({
                        icon: "error",
                        title: "Error!",
                        text: "Request ID is missing!",
                        confirmButtonColor: "#d33"
                    });
                    return;
                }

                if (requestStatus == "4") {
                    $("#modal_total_qty").text($("#total_qty").val());
                    $("#modal_req_qty").text($("#req_qty").val());
                    $("#receivedRequestModal").modal("show");
                    return;
                }

                $("#confirmationModal").modal("show");
            });


            $("#confirmAccept").click(function () {
                let requestId = $("#request_id").val();

                $.ajax({
                    url: "{{ route('update.stock') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    data: {
                        request_id: requestId
                    },
                    success: function (response) {
                        // console.log(response);
                        if (response.success) {
                            Swal.fire({
                                icon: "success",
                                title: "Success!",
                                text: response.message,
                                confirmButtonColor: "#28a745"
                            }).then(() => {
                                window.location.href = "{{ route('raised_requests.index') }}"; // Redirect after confirmation
                            });
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Failed!",
                                text: response.message,
                                confirmButtonColor: "#d33"
                            });
                        }
                    },
                    error: function (xhr) {
                        console.log(xhr.responseText);
                        Swal.fire({
                            icon: "error",
                            title: "Error!",
                            text: "An error occurred while processing your request.",
                            confirmButtonColor: "#d33"
                        });
                    }
                });
            });

        });


        function ViewRequestStatus(request_id) {
            $.ajax({
                url: "{{ route('get.request.status') }}",
                type: "GET",
                data: { request_id: request_id },
                success: function (response) {
                    // console.log(request_id);
                    // console.log(response);
                    let html = "";
                    if (response.length > 0) {
                        response.forEach(status => {
                            let message = status.decline_msg ? status.decline_msg : (status.query_msg ? status.query_msg : 'N/A');

                            let unreadStyle = status.is_read == 0 ? 'style="font-weight: bold; text-decoration: underline; background-color: #e9ecef;"' : '';

                            html += `<tr ${unreadStyle} data-status-id="${status.id}">
                                                                    <td><span class="badge badge-${status.status_id == 2 ? 'success' :
                                    (status.status_id == 3 ? 'danger' :
                                        (status.status_id == 4 ? 'info' : 'secondary'))}">
                                                                        ${status.status_name}
                                                                    </span></td>
                                                                    <td>
                                                                        <button class="btn btn-link text-primary view-message" data-message="${message}" data-status-id="${status.id}">
                                                                            ${message.length > 20 ? message.substring(0, 20) + '...' : message}
                                                                        </button>
                                                                    </td>
                                                                    <td>${status.supplier_qty || 'N/A'}</td>
                                                                    <td>${status.supplier_new_spareable || 'N/A'}</td>
                                                                    <td>${status.supplier_used_spareable || 'N/A'}</td>
                                                                    <td>${status.requestor_name}</td>
                                                                    <td>${new Date(status.updated_at).toLocaleString()}</td>
                                                                </tr>`;
                        });
                    } else {
                        html = `<tr><td colspan="8" class="text-center">No status updates found.</td></tr>`;
                    }
                    $("#requestStatusData").html(html);
                    $("#requestStatusModal").modal('show');
                },
                error: function () {
                    alert("Failed to fetch request status.");
                }
            });
        }

        // Event listener for message click
        $(document).on("click", ".view-message", function () {
            let message = $(this).data("message");
            let statusId = $(this).data("status-id");
            let row = $(this).closest("tr");

            // Show message in a sub-modal
            $("#subModalMessageContent").text(message);
            $("#subModal").modal("show");

            // If row is unread, update the status in DB and remove styles dynamically
            if (row.attr("style")) {
                $.ajax({
                    url: "{{ route('update.is_read.status') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        status_id: statusId
                    },
                    success: function (response) {
                        if (response.success) {
                            // Remove inline styles dynamically
                            row.removeAttr("style");
                        }
                    },
                    error: function () {
                        alert("Failed to update read status.");
                    }
                });
            }
        });



        $(document).ready(function () {
            @if(isset($stockdata)) // Ensure $stockdata is set
                let stockStatus = {{ $stockdata->status }};
                let stockId = {{ json_encode($stockdata->id) }};
                console.log(stockId);

                if (stockStatus === 2) {
                    $('#subModalQueryButton').removeClass('d-none');
                } else {
                    $('#subModalQueryButton').addClass('d-none');
                }

                $('#subModalQueryButton').on('click', function () {
                    $('.modal').modal('hide');

                    setTimeout(function () {
                        $('.bd-example-modal-xl').modal('show');
                        RequestStockData(stockId);
                    }, 500);
                });
            @else
                console.error("Stock data is not available.");
            @endif
                                                                        });

        //For multiple modal seamless transitions
        $(document).ready(function () {
            // Open Decline Modal without closing Main Modal
            $('[data-target="#declineReasonModal"]').on("click", function () {
                $("#declineReasonModal").modal("show");
            });

            // Open Raise Query Modal without closing Main Modal
            $('[data-target="#raiseQueryModal"]').on("click", function () {
                $("#raiseQueryModal").modal("show");
            });


            // Close only the sub-modal (Decline or Raise Query), keep Main Modal open
            $(".sub-modal-close").on("click", function () {
                $(this).closest(".modal").modal("hide");
            });


            // Prevent clicking outside sub-modal from closing the main modal
            $(".modal").on("hidden.bs.modal", function (e) {
                if ($(".modal:visible").length) {
                    $("body").addClass("modal-open");
                }
            });
        });


        // JavaScript function (can be in a script tag or external JS file)
        function submitQuery() {
            // Get the request_id and query message
            var requestId = document.getElementById('request_id').value;
            var queryMessage = document.getElementById('query').value;

            // Validate the query
            if (!queryMessage.trim()) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Please enter a query message.',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
                return;
            }

            // Send AJAX request
            $.ajax({
                url: "{{ route('request.raisedrequestquery') }}",
                type: 'POST',
                data: {
                    _token: document.querySelector('input[name="_token"]').value,
                    request_id: requestId,
                    query_msg: queryMessage
                },
                success: function (response) {
                    // Close all open modals
                    $('.modal').modal('hide');

                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Your query has been submitted successfully.',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    });

                    // Reset the form
                    document.getElementById('raiseQueryForm').reset();
                },
                error: function (xhr) {
                    // Close all open modals
                    $('.modal').modal('hide');

                    // Show error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'An error occurred: ' +
                            (xhr.responseJSON?.message || 'Unknown error'),
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }

        // Close modal function (for the close buttons)
        function closeModal() {
            $('#raiseQueryModal').modal('hide');
        }


    </script>

    {{-- 711 work here --}}


    <script>
        $(document).ready(function () {

            $(".sub-modal-close").click(function () {
                $("#acba").modal("hide");
            });

        });




        function acceptbtn() {
            let acceptPendingValue = $("#acceptPendingfield").val();
            let requestUrl = "{{ route('request.pending.accept') }}";

            $.ajax({
                url: requestUrl,
                type: "POST",
                data: { request_id: acceptPendingValue },
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                beforeSend: function () {
                    $(".accept_confirmation").prop("disabled", true);
                },
                success: function (response) {
                    console.log("Server Response:", response); // Debugging

                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Your request has been accepted successfully.',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.reload();
                        }
                    });
                },
                error: function (xhr) { // Syntax error fixed (comma added)
                    console.log("AJAX Error:", xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong! Please try again.',
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'OK'
                    });
                },
                complete: function () {
                    $(".accept_confirmation").prop("disabled", false);
                },
            });
        }



        function declineRequest() {
            var requestId = document.getElementById('request_id').value;
            var declineMsg = document.getElementById('decline_reason').value;

            if (!declineMsg.trim()) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Please enter a reason for declining.',
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'OK'
                });
                return;
            }

            $.ajax({
                url: "{{ route('request.raisedrequestdecline') }}",
                type: 'POST',
                data: {
                    _token: document.querySelector('input[name="_token"]').value,
                    request_id: requestId,
                    decline_msg: declineMsg
                },
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Request has been declined successfully.',
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        // Close all modals
                        $('.modal').modal('hide');

                        // Clear input field
                        document.getElementById('decline_reason').value = '';

                        // Redirect to raised_requests.index
                        window.location.href = "{{ route('raised_requests.index') }}";
                    });
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'An error occurred: ' + (xhr.responseJSON?.message || 'Unknown error'),
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        // Close all modals
                        $('.modal').modal('hide');
                    });
                }
            });
        }

    </script>
    <script>
        $(document).ready(function () {
            $('#edp_code').select2({
                theme: 'bootstrap4',
                placeholder: 'Select EDP Code...',
                allowClear: true,
                width: '100%' // Matches Bootstrap input width
            });
        });
    </script>



@endsection
