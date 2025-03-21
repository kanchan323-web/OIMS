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
                                        <select class="form-control" name="edp_code" id="edp_code">
                                            <option disabled selected>Select EDP Code...</option>
                                            @foreach ($EDP_Code_ID as $edp_code_id)
                                                <option value="{{ $edp_code_id->edp_code }}">{{ $edp_code_id->edp_code }}</option>
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


           <!--         <div class="col-sm-6 col-md-3">
                        <div class="user-list-files d-flex">
                            <a href="{{ route('request_stock_add') }}" class="btn btn-primary add-list"><i
                                    class="las la-plus mr-3"></i>Add Stock</a>
                            <a href="#" class="btn btn-primary add-list"><i class="las la-plus mr-3"></i>Bulk Stocks
                            </a>
                        </div>
                    </div>  -->
                </div>
            </div>

            <div class="col-lg-12">
                <div class="table-responsive rounded mb-3">
                    <table class="data-tables table mb-0 tbl-server-info">
                        <thead class="bg-white text-uppercase">
                            <tr class="ligth ligth-data">
                                <!-- <th>
                                    <div class="checkbox d-inline-block">
                                        <input type="checkbox" class="checkbox-input" id="checkbox1">
                                        <label for="checkbox1" class="mb-0"></label>
                                    </div>
                                </th> -->

                                <th>Sr.No</th>
                                <th>Requester Rig Name</th>
                                <th>EDP Code</th>
                                <th>Request Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="ligth-body">
                            @foreach($data as $index => $stockdata)
                                @if(!in_array($stockdata->user_id, $datarig))
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $stockdata->Location_Name }}</td>
                                        <td>{{ $stockdata->edp_code }}</td>
                                        <td>{{ $stockdata->created_at->format('d-m-Y H:i:s') }}</td>

                                        <!-- Status with Dynamic Color -->
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

                                        <td>
                                            <span class="badge {{ $badgeClass }}">{{ $stockdata->status_name }}</span>
                                        </td>



                                        <td>
                                            <a class="badge badge-success mr-2" data-toggle="modal"
                                                onclick="RequestStockData({{ json_encode($stockdata->id) }})"
                                                data-target=".bd-example-modal-xl" data-placement="top"
                                                title="Supplier Request" href="#">
                                                <i class="ri-arrow-right-circle-line"></i>
                                            </a>
                                            <a class="badge badge-info" onclick="ViewRequestStatus({{ $stockdata->id }})"
                                                data-toggle="modal" data-placement="top"
                                                title="View Request Status" href="#">
                                                <i class="ri-eye-line"></i>
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
                <h5 class="modal-title" id="exampleModalLabel"> Requester Request</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card-body">
                    <!-- <form class="needs-validation" novalidate method="POST" action="" id="addStockForm"> -->
                    <form id="mainModalForm">
                    <input type="hidden" id="request_id" name="request_id">
                    <div class="form-row">
                        <div class="col-md-6 mb-3">
                            <label for="">Requester Name</label>
                            <input type="text" class="form-control" name="" placeholder="Requester Name" id="location_id" readonly>
                            <div class="invalid-feedback">
                                Enter Requester Name
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Requester Rig Name</label>
                            <input type="text" class="form-control" placeholder="Requester Rig Name" name=""
                                id="requester_Id" readonly>
                            <div class="invalid-feedback">
                                Enter Requester Rig Name
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Supplier Name</label>
                            <input type="text" class="form-control" name="" placeholder="Supplier Name" id="Supplier_Location_Id"
                                readonly>
                            <div class="invalid-feedback">
                                Supplier Name
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Supplier Rig Name</label>
                            <input type="text" class="form-control" name="" placeholder="Supplier Rig Name"
                                id="Supplier_Location_Name" readonly>
                            <div class="invalid-feedback">
                                Supplier Rig Name
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">EDP Code</label>
                            <input type="text" class="form-control" name="" placeholder="EDP Code" id="EDP_Code" readonly>
                            <div class="invalid-feedback">
                                Enter EDP Code
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="category">Category</label>
                            <input type="text" class="form-control" placeholder="Category" id="category_id" name="category" readonly>
                            <div class="invalid-feedback">
                                Enter Category Name
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="section">Section</label>
                            <input type="text" class="form-control" placeholder="Section" name="section" id="section" readonly>
                            <div class="invalid-feedback">
                                Enter Section Name
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Unit of Measurement </label>
                            <input type="text" class="form-control" name="measurement" placeholder="Unit of Measurement" id="measurement"
                                readonly>
                            <div class="invalid-feedback">
                                Enter Unit of Measurement
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Description </label>
                            <textarea class="form-control" id="description" name="description"
                                placeholder="Description" readonly></textarea>
                            <div class="invalid-feedback">
                                Enter Description
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Status </label>
                            <input type="text" class="form-control" name="status" id="status" readonly>
                            <div class="invalid-feedback">
                                Enter Status
                            </div>
                     <!--       <select class="form-control" name="status">
                                <option disabled {{ request('status') ? '' : 'selected' }}>Select
                                    Status...</option>
                                <option value="Pendding"
                                    {{ request('status') == 'Pendding' ? 'selected' : '' }}>Pendding
                                </option>
                                <option value="Pendding"
                                    {{ request('status') == 'Pendding' ? 'selected' : '' }}>Pendding
                                </option>
                                <option value="Pendding"
                                    {{ request('status') == 'Pendding' ? 'selected' : '' }}>Pendding
                                </option>
                            </select> -->
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Supplier Total Quantity</label>
                            <input type="text" class="form-control" placeholder="Supplier Total Quantity" name="total_qty"
                                id="total_qty" readonly>
                            <div class="invalid-feedback">
                                Supplier Total Quantity
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">New  </label>
                            <input type="text" class="form-control" placeholder="New Spearable " name="new_spareable"
                                id="new_spearable" readonly>
                            <div class="invalid-feedback">
                                Enter New
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Requester Requested Quantity</label>
                            <input type="text" class="form-control" placeholder="Requested Quantity" name="req_qty"
                                id="req_qty" readonly>
                            <div class="invalid-feedback">
                                Enter Requested Quantity
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Used  </label>
                            <input type="text" class="form-control" placeholder="Used Spareable" name="used_spareable"
                                id="used_spareable" readonly>
                            <div class="invalid-feedback">
                                Enter Used
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="request_date">Request Date</label>
                            <input type="text" class="form-control" placeholder="Request Date" name="request_date"
                                id="request_date" readonly>
                            <div class="invalid-feedback">
                                Enter Request Date
                            </div>
                        </div>

                    </div>
                    <div class="d-flex justify-content-center mt-4">
                        <button class="btn btn-danger mx-2" type="button" data-toggle="modal" data-target="#declineReasonModal">
                            Decline Request
                        </button>
                        <button class="btn btn-success mx-2" type="button" id="openReceivedRequestModal">
                            Received Request
                        </button>
                        <button class="btn btn-primary mx-2" type="button" data-toggle="modal" data-target="#raiseQueryModal">
                            Raise Query
                        </button>
                    </div>

                    <!-- Received Request Modal -->
                    <div class="modal fade" id="receivedRequestModal" tabindex="-1" role="dialog" aria-labelledby="receivedRequestLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="receivedRequestLabel">Received Request Details</h5>
                                    <button type="button" class="close sub-modal-close" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Supplier Total Quantity</th>
                                                <th>Requester Requested Quantity</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td id="modal_total_qty"></td>
                                                <td id="modal_req_qty"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th> New </th>
                                                <th> Used </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td id="modal_total_new"></td>
                                                <td id="modal_total_used"></td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <form id="receivedRequestForm">
                                        @csrf
                                        <div class="form-group">
                                            <label for="modal_new_spareable">New </label>
                                            <input type="number" class="form-control" id="modal_new_spareable" name="modal_new_spareable" min="0" value="0">
                                        </div>
                                        <div class="form-group">
                                            <label for="modal_used_spareable">Used </label>
                                            <input type="number" class="form-control" id="modal_used_spareable" name="modal_used_spareable" min="0" value="0">
                                        </div>
                                        <div class="form-group">
                                            <span id="error_message" class="text-danger"></span>
                                        </div>
                                        <div class="d-flex justify-content-center mt-3">
                                            <button type="button" class="btn btn-secondary mx-2 sub-modal-close">Cancel</button>
                                            <button type="button" class="btn btn-success mx-2" id="confirmReceivedRequest">Confirm</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Decline Request Modal -->
                    <div class="modal fade" id="declineReasonModal" tabindex="-1" role="dialog" aria-labelledby="declineReasonLabel" aria-hidden="true">
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
                                            <textarea class="form-control" id="decline_reason" name="decline_reason" rows="3" required></textarea>
                                        </div>
                                        <div class="d-flex justify-content-center mt-3">
                                            <button type="button" class="btn btn-secondary mx-2 sub-modal-close">Cancel</button>
                                            <button type="submit" class="btn btn-danger mx-2">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Raise Query Modal -->
                    <div class="modal fade" id="raiseQueryModal" tabindex="-1" role="dialog" aria-labelledby="raiseQueryLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="raiseQueryLabel">Enter Query</h5>
                                    <button type="button" class="close sub-modal-close" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form id="raiseQueryForm">
                                        @csrf
                                        <div class="form-group">
                                            <label for="query">Query</label>
                                            <textarea class="form-control" id="query" name="query" rows="3" required></textarea>
                                        </div>
                                        <div class="d-flex justify-content-center mt-3">
                                            <button type="button" class="btn btn-secondary mx-2 sub-modal-close">Cancel</button>
                                            <button type="submit" class="btn btn-primary mx-2">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Confirmation Modal -->
                    <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="confirmationModalLabel">Confirm Action</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    Are you sure you want to accept this request?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    <button type="button" class="btn btn-success" id="confirmAccept">Yes, Accept it!</button>
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
<div class="modal fade" id="requestStatusModal" tabindex="-1" role="dialog" aria-labelledby="requestStatusModalLabel" aria-hidden="true">
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
                                <th>Supplier Qty</th>
                                <th>New Spareable</th>
                                <th>Used Spareable</th>
                                <th>Requestor</th>
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




<script>

    //ajax filter for incomming request stock


    $(document).ready(function () {
            // Filter Stock Data on Button Click
            $("#filterButton").click(function () {

                $.ajax({
                    type: "GET",
                    url: "{{ route('incoming_request_filter.get') }}",
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
                                        <td>${stockdata.qty}</td>
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
    //ajax filter for incomming request stock


//to bring data into the main modal
function RequestStockData(id) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: "GET",
        url: "{{ route('request_stock_view.get') }}",
        data: { data: id },
        success: function(response) {
            if (!response || !response.data) {
                console.error("No valid data received.");
                return;
            }

            var stockData = Array.isArray(response.data) ? response.data : [response.data];

            if (stockData.length > 0 && stockData[0] !== null) {
                var stock = stockData[0];

                if (typeof stock === "object") {
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
                    $("#request_date").val(stock.formatted_created_at ?? '');

                    if (stock.status == 4) {
                        $(".btn-danger, .btn-primary").hide();
                    } else if (stock.status == 6) {
                        $(".btn-danger, .btn-primary").hide();
                        $(".btn-success, .btn-primary").hide();
                    } else if (stock.status == 3) {
                        $(".btn-danger, .btn-primary").hide();
                        $(".btn-success, .btn-primary").hide();
                    } else {
                        $(".btn-danger, .btn-primary").show();
                        $(".btn-success, .btn-primary").show();
                    }
                } else {
                    console.error("Stock data is not a valid object:", stock);
                }
            } else {
                console.error("No stock data available.");
            }
        },
        error: function(xhr, status, error) {
            console.error("Error fetching stock data:", error);
        }
    });
}


//For deleting
function deleteStockdata(id) {

    $("#delete_id").val(id);

}

//for checkbox
$(document).ready(function() {
    $("#selectAll").on("change", function() {
        $(".row-checkbox").prop("checked", $(this).prop("checked"));
    });

    $(".row-checkbox").on("change", function() {
        if ($(".row-checkbox:checked").length === $(".row-checkbox").length) {
            $("#selectAll").prop("checked", true);
        } else {
            $("#selectAll").prop("checked", false);
        }
    });
});

//Message for success and error time limit
$(document).ready(function() {
    setTimeout(function() {
        $(".alert").fadeOut("slow");
    }, 3000);
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


//For accept
$(document).ready(function () {
    console.log("Script Loaded!");

    function validateSpareableInputs() {
        let requestedQty = parseInt($("#modal_req_qty").text().trim()) || 0;
        let newSpareable = parseInt($("#modal_new_spareable").val()) || 0;
        let usedSpareable = parseInt($("#modal_used_spareable").val()) || 0;
        let totalSpareable = newSpareable + usedSpareable;

        if (totalSpareable > requestedQty) {
            $("#error_message").text("Total spareable quantity cannot exceed Requested Quantity.");
            return false;
        } else {
            $("#error_message").text("");
            return true;
        }
    }

    $("#modal_new_spareable, #modal_used_spareable").on("input", function () {
        validateSpareableInputs();
    });

    $(document).on("click", "#confirmReceivedRequest", function (e) {
        e.preventDefault();
        console.log("Submit Event Triggered!");

        let requestId = $("#mainModalForm").find("#request_id").val();
        let newSpareable = $("#modal_new_spareable").val();
        let usedSpareable = $("#modal_used_spareable").val();
        let supplierTotalQty = $("#modal_total_qty").text().trim();

        if (!requestId) {
            console.log("Request ID is missing!");
            return;
        }

        $.ajax({
            url: "{{ route('request.accept') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                request_id: requestId,
                supplier_total_qty: supplierTotalQty,
                supplier_new_spareable: newSpareable,
                supplier_used_spareable: usedSpareable
            },
            success: function (response) {
                if (response.success) {
                    window.location.href = "{{ route('incoming_request_list') }}";
                }
            },
            error: function (xhr) {
                console.error("❌ AJAX Error:", xhr.responseText);
            }
        });
    });
});


//for decline and query
$(document).ready(function () {
    // Decline Request
    $(document).on("submit", "#declineForm", function (e) {
        e.preventDefault();

        let requestId = $("#request_id").val();
        let declineMsg = $("#decline_reason").val();

        $.ajax({
            url: "{{ route('request.decline') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                request_id: requestId,
                decline_msg: declineMsg
            },
            success: function (response) {
                console.log("AJAX Success:", response);
                if (response.success) {
                    window.location.href = "{{ route('incoming_request_list') }}";
                }
            },
            error: function (xhr) {
                console.error("AJAX Error:", xhr.responseText);
            }
        });
    });

    // Raise Query
    $(document).on("submit", "#raiseQueryForm", function (e) {
        e.preventDefault();
        console.log("Query Event Triggered!");

        let requestId = $("#request_id").val();
        let queryMsg = $("#query").val();

        $.ajax({
            url: "{{ route('request.query') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                request_id: requestId,
                query_msg: queryMsg
            },
            success: function (response) {
                if (response.success) {
                    window.location.href = "{{ route('incoming_request_list') }}";
                }
            },
            error: function (xhr) {
                console.error("AJAX Error:", xhr.responseText);
            }
        });
    });
});

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
            url: "{{ route('request.updateStatus') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                request_id: requestId,
                status: 4
            },
            success: function (response) {
                //console.log(response);
                if (response.success) {
                    $("#modal_total_qty").text($("#total_qty").val());
                    $("#modal_req_qty").text($("#req_qty").val());
                    $("#modal_total_new").text($("#new_spearable").val());
                    $("#modal_total_used").text($("#used_spareable").val());

                    $("#confirmationModal").modal("hide");

                    $("#receivedRequestModal").modal("show");
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Failed!",
                        text: "Failed to update status!",
                        confirmButtonColor: "#d33"
                    });
                }
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                Swal.fire({
                    icon: "error",
                    title: "Error!",
                    text: "Error updating status. Please try again.",
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
        success: function(response) {
            console.log(response);
            let html = "";
            if (response.length > 0) {
                response.forEach(status => {
                    html += `<tr>
                        <td><span class="badge badge-${status.status_id == 2 ? 'success' :
                            (status.status_id == 3 ? 'danger' :
                            (status.status_id == 4 ? 'info' : 'secondary'))}">
                            ${status.status_name}
                        </span></td>
                        <td>${status.decline_msg ? status.decline_msg : (status.query_msg ? status.query_msg : 'N/A')}</td>
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
            $("#requestStatusModal").modal('show'); // Show modal after data is loaded
        },
        error: function() {
            alert("Failed to fetch request status.");
        }
    });
}


</script>

@endsection
