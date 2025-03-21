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
                        <div class="col-md-9">
                            <div id="user_list_datatable_info" class="dataTables_filter">
                                <form action="{{ route('request_stock_filter') }}" method="post"
                                    class="mr-3 position-relative">
                                    <form id="filterForm" class="mr-3 position-relative">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-2 mb-2">
                                                <label for="edp_code">EDP Code</label>
                                                <select class="form-control filter-input" name="edp_code" id="edp_code">
                                                    <option disabled selected>Select EDP Code...</option>
                                                    @foreach ($edps as $edp)
                                                        <option value="{{ $edp->edp_id }}">{{ $edp->edp_code }}</option>
                                                    @endforeach
                                                </select>
                                            </div>                                            
                                    
                                            <div class="col-md-2 mb-2">
                                                <label for="location_name">Location Name</label>
                                                <input type="text" class="form-control filter-input" placeholder="Location Name"
                                                    name="location_name" id="location_name" value="{{ request('location_name') }}">
                                            </div>
                                    
                                            <div class="col-md-2 mb-2">
                                                <label for="form_date">From Date</label>
                                                <input type="date" class="form-control filter-input" name="form_date" id="form_date"
                                                    value="{{ request('form_date') }}">
                                            </div>
                                    
                                            <div class="col-md-2 mb-2">
                                                <label for="to_date">To Date</label>
                                                <input type="date" class="form-control filter-input" name="to_date" id="to_date"
                                                    value="{{ request('to_date') }}">
                                            </div>
                                    
                                            <div class="col-md-4 mb-2 d-flex align-items-end">
                                                <button type="button" id="filterButton" class="btn btn-primary mr-2">Search</button>
                                                <button type="button" id="resetButton" class="btn btn-secondary">Reset</button>
                                            </div>
                                        </div>
                                    </form>                                    
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
                                    <th>Location Name</th>
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
                                            <td>{{ $stockdata->location_name }} ({{ $stockdata->location_id }})</td>
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
                                            <td>{{ $stockdata->created_at->format('d-m-Y H:i:s') }}</td>
                                            <td>
                                                <a class="badge badge-success mr-2" data-toggle="modal"
                                                    onclick="RequestStockData({{ json_encode($stockdata->id) }})"
                                                    data-target=".bd-example-modal-xl" data-placement="top"
                                                    title="Supplier Request" href="#">
                                                    <i class="ri-arrow-right-circle-line"></i>
                                                </a>
                                                <a class="badge badge-info" onclick="ViewRequestStatus({{ $stockdata->id }})"
                                                    data-toggle="modal" data-placement="top" title="View Request Status" href="#">
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
                                <input type="text" class="form-control" placeholder="Category" id="category" name="category" readonly>
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
                                <input type="text" class="form-control" name="status" id="status">
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
                                <label for="">New Spearable </label>
                                <input type="text" class="form-control" placeholder="New Spearable " name="new_spareable"
                                    id="new_spearable" readonly>
                                <div class="invalid-feedback">
                                    Enter New Spareable
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
                                <label for="">Used Spareable </label>
                                <input type="text" class="form-control" placeholder="Used Spareable" name="used_spareable"
                                    id="used_spareable" readonly>
                                <div class="invalid-feedback">
                                    Enter Used Spareable
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
                            
                            <button class="btn btn-success mx-2" type="button" id="openReceivedRequestModal">
                                Acknowledge stock receival
                            </button>
                            <button class="btn btn-primary mx-2" type="button" data-toggle="modal" data-target="#raiseQueryModal">
                                Raise Query
                            </button>
                           
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
                                        Are you sure you want to acknowledge this request?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        <button type="button" class="btn btn-success" id="confirmAccept">Confirm</button>
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


    <script>
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
                                        <td>${stockdata.location_name} (${stockdata.location_id})</td>
                                        <td><span class="badge ${badgeClass}">${stockdata.status_name}</span></td>
                                        <td>${stockdata.created_at ? stockdata.created_at : '-'}</td>
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
                            $("#category").val(stock.category ?? '');
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
                                $(".btn-success, .btn-primary").hide(); 
                            } else if (stock.status == 6) {
                                $(".btn-primary").hide();  // Hide Raise Query button
                                $(".btn-success").show();  // Show Acknowledge Shipment button
                            } else if(stock.status == 5 || stock.status == 1 || stock.status == 3) {
                                $(".btn-primary, .btn-success").hide();
                            } else if(stock.status == 2) {
                                $(".btn-primary").show();  
                                $(".btn-success").hide();
                            } else {
                                $(".btn-primary, .btn-success").show();
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
                    url: "{{ route('request.updateStatusforRequest') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        request_id: requestId,
                        status: 3
                    },
                    success: function (response) {
                        if (response.success) {
                            $("#modal_total_qty").text($("#total_qty").val());  
                            $("#modal_req_qty").text($("#req_qty").val()); 

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

    </script>
    
    
    
@endsection