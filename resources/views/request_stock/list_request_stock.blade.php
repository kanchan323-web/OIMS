@extends('layouts.frontend.layout')
@section('page-content')

<style>
.badge-custom-blue {
    background-color: #70a9ff !important;
    color: white !important;
    border: none !important;
}

.badge-custom-blue:hover {
    background-color: #5598f0 !important; /* darker blue on hover */
    color: white !important;
}
</style>

    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    {{Breadcrumbs::render('incoming_request_stock_list')}}
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
                                            <select class="form-control select2" name="edp_code" id="edp_code" required>
                                                <option disabled selected>Select EDP Code...</option>
                                                @foreach ($data as $edp_code_data)
                                                    <option value="{{ $edp_code_data->edp_code }}">
                                                        {{ $edp_code_data->edp_code }}
                                                    </option>
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
                                    <th>Request ID</th>
                                    <th>DN No</th>
                                    <th>Requester Location</th>
                                    <th>EDP Code</th>
                                    <th>Description</th>
                                    <th>Request Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="ligth-body" id="stockTable">
                                @foreach($data as $index => $stockdata)
                                                        @if(!in_array($stockdata->user_id, $datarig))
                                                                                <tr>
                                                                                    <td>{{ $index + 1 }}</td>
                                                                                    <td>{{ $stockdata->RID }}</td>
                                                                                    <td>{{ $stockdata->dn_no }}</td>
                                                                                    <td>{{ $stockdata->Location_Name }}</td>
                                                                                    <td>{{ $stockdata->edp_code }}</td>
                                                                                    <td>{{ $stockdata->description }}</td>
                                                                                    <td>{{ $stockdata->updated_at->format('d-m-Y') }}</td>

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
                                                                                    <td class="text-center">
                                                                                        <div class="d-flex justify-content-center align-items-center" style="gap: 10px;">
                                                                                            <a href="{{ route('incomingInvoice', $stockdata->id) }}" class="badge badge-custom-blue mr-2" data-toggle="tooltip" 
                                                                                                data-placement="top" data-original-title="Download Invoice"
                                                                                                    target="_blank" id="downloadPdf" style="color: #70a9ff">
                                                                                                    <i class="fas fa-file-pdf mr-1"></i>
                                                                                            </a>

                                                                                            <a class="badge badge-success" data-toggle="modal"
                                                                                                onclick="RequestStockData({{ json_encode($stockdata->id) }})"
                                                                                                data-target=".bd-example-modal-xl" data-placement="top" title="Requestor's Request">
                                                                                                <i class="ri-arrow-right-circle-line"></i>
                                                                                            </a>

                                                                                            <input type="hidden" id="StockdataID" value="{{ $stockdata->id }}">
                                                                                            <input type="hidden" id="StockdataStatusName" value="{{ $stockdata->status_name }}">

                                                                                            @php
                                                                                                $hasUnread = $stockdata->requestStatuses->where('is_read', 0)->count() > 0;
                                                                                            @endphp

                                                                                            <a class="badge badge-info position-relative"
                                                                                                onclick="ViewRequestStatus({{ $stockdata->id }})" data-toggle="modal"
                                                                                                data-placement="top" title="Display Request Status">
                                                                                                <i class="ri-eye-line"></i>
                                                                                                @if($hasUnread)
                                                                                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                                                                                        ●
                                                                                                    </span>
                                                                                                @endif
                                                                                            </a>

                                                                                            @if($stockdata->status == 6)
                                                                                                <a class="badge badge-warning" data-toggle="modal"
                                                                                                    onclick="openEditReceivedRequestModal({{ json_encode($stockdata->id) }})"
                                                                                                    data-target="#receivedRequestModal" data-placement="top"
                                                                                                    title="Edit Received Request">
                                                                                                    <i class="ri-edit-line"></i>
                                                                                                </a>
                                                                                            @endif
                                                                                        </div>
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
                    <h5 class="modal-title" id="exampleModalLabel"> Requester's Request Detail</h5>
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
                                <div class="col-md-4 mb-4">
                                    <label for="">Request ID</label>
                                    <input type="text" class="form-control" name="" placeholder="Request ID" id="r_id"
                                        readonly>
                                    <div class="invalid-feedback">
                                        Enter Request ID
                                    </div>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <label for="">DN No</label>
                                    <input type="text" class="form-control" name="" placeholder="DN No" id="dn_id"
                                        readonly>
                                    <div class="invalid-feedback">
                                        Enter DN No
                                    </div>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <label for="">Requester Name</label>
                                    <input type="text" class="form-control" name="" placeholder="Requester Name"
                                        id="location_id" readonly>
                                    <div class="invalid-feedback">
                                        Enter Requester Name
                                    </div>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <label for="">EDP Code</label>
                                    <input type="text" class="form-control" name="" placeholder="EDP Code" id="EDP_Code"
                                        readonly>
                                    <div class="invalid-feedback">
                                        Enter EDP Code
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
                                    <label for="">Requester Location Name</label>
                                    <input type="text" class="form-control" placeholder="Requester Location Name" name=""
                                        id="requester_Id" readonly>
                                    <input type="hidden" class="form-control" name="" placeholder="Supplier Name" id="Supplier_Location_Id" readonly>
                                    <input type="hidden" class="form-control" name="" placeholder="Supplier Location Name" id="Supplier_Location_Name" readonly>
                                    <div class="invalid-feedback">
                                        Enter Requester Location Name
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
                                    <label for=""> Total Quantity</label>
                                    <input type="text" class="form-control" placeholder="Supplier Total Quantity"
                                        name="total_qty" id="total_qty" readonly>
                                    <div class="invalid-feedback">
                                         Total Quantity
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
                                    <label for="">Status </label>
                                    <input type="text" class="form-control" name="status" id="status" readonly>
                                    <div class="invalid-feedback">
                                        Enter Status
                                    </div>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <label for="">New </label>
                                    <input type="text" class="form-control" placeholder="New Spearable "
                                        name="new_spareable" id="new_spearable" readonly>
                                    <div class="invalid-feedback">
                                        Enter New
                                    </div>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <label for="">Used </label>
                                    <input type="text" class="form-control" placeholder="Used Spareable"
                                        name="used_spareable" id="used_spareable" readonly>
                                    <div class="invalid-feedback">
                                        Enter Used
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
                                <div class="col-md-4 mb-4">
                                    <label id="status_label" style="display:none">Request Status Message</label>
                                    <span id="status_msg"></span>
                                </div>
                            </div>

                            <!-- request decline staus -->
                            <div class="d-flex justify-content-center mt-4">
                                <button class="btn btn-danger mx-2" type="button" data-toggle="modal"
                                    data-target="#declineReasonModal">
                                    Decline Request
                                </button>
                                <button class="btn btn-success mx-2 receivedRequestBTN" type="button"
                                    id="openReceivedRequestModal">
                                    Received Request
                                </button>
                                <button class="btn btn-primary mx-2" type="button" data-toggle="modal"
                                    data-target="#raiseQueryModal">
                                    Raise Query
                                </button>
                            </div>

                            <!-- Received Request Modal -->
                            <div class="modal fade" id="receivedRequestModal" tabindex="-1" role="dialog"
                                aria-labelledby="receivedRequestLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="receivedRequestLabel">Received Request Details</h5>
                                            <button type="button" class="close sub-modal-close" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- Supplier vs Requester Table -->
                                            <table class="table table-bordered">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th scope="col">Supplier Total Quantity</th>
                                                        <th scope="col">Requester Requested Quantity</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td id="modal_total_qty"></td>
                                                        <td id="modal_req_qty"></td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                            <!-- New vs Used Stock Table -->
                                            <table class="table table-bordered">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th scope="col">New</th>
                                                        <th scope="col">Used</th>
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
                                                    <input type="number" class="form-control" id="modal_new_spareable"
                                                        name="modal_new_spareable" min="0" value="0">
                                                </div>
                                                <div class="form-group">
                                                    <label for="modal_used_spareable">Used </label>
                                                    <input type="number" class="form-control" id="modal_used_spareable"
                                                        name="modal_used_spareable" min="0" value="0">
                                                </div>
                                                <div class="form-group">
                                                    <span id="error_message" class="text-danger"></span>
                                                </div>
                                                <div class="d-flex justify-content-center mt-3">
                                                    <button type="button"
                                                        class="btn btn-secondary mx-2 sub-modal-close">Cancel</button>
                                                    <button type="button" class="btn btn-success mx-2"
                                                        id="confirmReceivedRequest">Confirm</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
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
                                                    <button type="submit" class="btn btn-danger mx-2">Submit</button>
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
                                            <form id="raiseQueryForm">
                                                @csrf
                                                <div class="form-group">
                                                    <label for="query">Query</label>
                                                    <textarea class="form-control" id="query" name="query" rows="3"
                                                        required></textarea>
                                                </div>
                                                <div class="d-flex justify-content-center mt-3">
                                                    <button type="button"
                                                        class="btn btn-secondary mx-2 sub-modal-close">Cancel</button>
                                                    <button type="submit" class="btn btn-primary mx-2">Submit</button>
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
                                            Are you sure you want to accept this request?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Cancel</button>
                                            <button type="button" class="btn btn-success" id="confirmAccept">Yes, Accept
                                                it!</button>
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
                        <table class="modal-table table mb-0 tbl-server-info">
                            <thead class="bg-white text-uppercase">
                                <tr class="ligth ligth-data">
                                    <th>Status</th>
                                    <th>Message</th>
                                    <th>Action by</th>
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



    <!-- Edit Received Request Modal -->
    <div class="modal fade" id="editReceivedRequestModal" tabindex="-1" role="dialog"
        aria-labelledby="editReceivedRequestLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editReceivedRequestLabel">Edit Received Request Details</h5>
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
                                <td id="edit_modal_total_qty"></td>
                                <td id="edit_modal_req_qty"></td>
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
                                <td id="edit_modal_total_new"></td>
                                <td id="edit_modal_total_used"></td>
                            </tr>
                        </tbody>
                    </table>

                    <form id="editReceivedRequestForm">
                        @csrf
                        <input type="hidden" id="edit_request_id" name="edit_request_id">
                        <div class="form-group">
                            <input type="hidden" id="original_new_spareable" value="0">
                            <label for="edit_modal_new_spareable">New </label>
                            <input type="number" class="form-control" id="edit_modal_new_spareable"
                                name="edit_modal_new_spareable" min="0" value="0">
                        </div>
                        <div class="form-group">
                            <input type="hidden" id="original_used_spareable" value="0">
                            <label for="edit_modal_used_spareable">Used </label>
                            <input type="number" class="form-control" id="edit_modal_used_spareable"
                                name="edit_modal_used_spareable" min="0" value="0">
                        </div>
                        <div class="form-group">
                            <span id="edit_error_message" class="text-danger"></span>
                        </div>
                        <div class="d-flex justify-content-center mt-3">
                            <button type="button" class="btn btn-secondary mx-2 sub-modal-close">Cancel</button>
                            <button type="button" class="btn btn-success mx-2"
                                id="confirmEditReceivedRequest">Confirm</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('[data-toggle="modal"]').tooltip(); // this enables tooltip on modal trigger
        });

        //ajax filter for incomming request stock

        $(document).ready(function () {
            // Filter Stock Data on Button Click
            $("#filterButton").click(function () {

                // console.log("hello");
                $.ajax({
                    type: "GET",
                    url: "{{ route('incoming_request_filter.get') }}",
                    data: $("#filterForm").serialize(),
                    success: function (response) {
                        let tableBody = $("#stockTable"); // Table inside modal
                        tableBody.empty(); // Clear old data

                        if (response.data && response.data.length > 0) {
                            $.each(response.data, function (index, stockdata) {
                                // Define status colors for different statuses
                                let statusColors = {
                                    'Pending': 'badge-warning',
                                    'Approve': 'badge-success',
                                    'Decline': 'badge-danger',
                                    'Query': 'badge-info',
                                    'Received': 'badge-primary',
                                    'MIT': 'badge-purple'
                                };

                                // Get the correct class for the badge
                                let badgeClass = statusColors[stockdata.status_name] || 'badge-secondary';

                                // Format created_at date (assuming it's returned as ISO format)
                            /*    let formattedDate = new Date(stockdata.created_at).toLocaleString('en-GB', {
                                    day: '2-digit', month: '2-digit', year: 'numeric',
                                    //hour: '2-digit', minute: '2-digit', second: '2-digit'
                                }); */

                                // Append row data to table
                                tableBody.append(`<tr><td>${index + 1}</td>
                                                        <td>${stockdata.RID}</td>
                                                        <td>${stockdata.Location_Name}</td>
                                                        <td>${stockdata.edp_code}</td>
                                                        <td>${stockdata.description}</td>
                                                        <td>${stockdata.creation_date}</td>
                                                        <td><span class="badge ${badgeClass}">${stockdata.status_name}</span></td>
                                                        <td><a class="badge badge-success mr-2" data-toggle="modal"
                                                                onclick="RequestStockData(${stockdata.id})"
                                                                data-target=".bd-example-modal-xl" data-placement="top"
                                                                title="Supplier Request" href="#"><i class="ri-arrow-right-circle-line"></i>
                                                                </a>
                                                                <a class="badge badge-info" onclick="ViewRequestStatus(${stockdata.id})"
                                                                    data-toggle="modal" data-placement="top"
                                                                    title="View Request Status" href="#">
                                                                    <i class="ri-eye-line"></i>
                                                                </a>
                                                        </td></tr> `);
                            });

                        } else {
                            tableBody.append(`<tr><td colspan="6" class="text-center">No records found</td></tr> `);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("Error fetching data:", error);
                    }
                });
            });
        });

        function formatIndianNumber(x) {
            if (x == null) return 0;
            x = x.toString();
            var afterPoint = '';
            if (x.indexOf('.') > 0)
                afterPoint = x.substring(x.indexOf('.'), x.length);
            x = Math.floor(x);
            x = x.toString();
            var lastThree = x.substring(x.length - 3);
            var otherNumbers = x.substring(0, x.length - 3);
            if (otherNumbers !== '')
                lastThree = ',' + lastThree;
            return otherNumbers.replace(/\B(?=(\d{2})+(?!\d))/g, ",") + lastThree + afterPoint;
        }

        //to bring data into the main modal
        function RequestStockData(id) {
            $(".btn-danger").hide();
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
                            $("#request_id").val(stock.id ?? '');
                            $("#r_id").val(stock.RID ?? '');
                            $("#dn_id").val(stock.dn_no ?? '');
                            $("#location_id").val(stock.requester_name ?? '');
                            $("#Supplier_Location_Id").val(stock.supplier_name ?? '');
                            $("#requester_Id").val(stock.requesters_rig ?? '');
                            $("#Supplier_Location_Name").val(stock.suppliers_rig ?? '');
                            $("#EDP_Code").val(stock.edp_code ?? '');
                            $("#category_id").val(stock.category ?? '');
                            $("#section").val(stock.section ?? '');
                            $("#description").val(stock.description ?? '');
                            $("#total_qty").val(formatIndianNumber(stock.qty ?? ''));
                            $("#req_qty").val(formatIndianNumber(stock.requested_qty ?? ''));
                            $("#measurement").val(stock.measurement ?? '');
                            $("#new_spearable").val(formatIndianNumber(stock.new_spareable ?? ''));
                            $("#used_spareable").val(formatIndianNumber(stock.used_spareable ?? ''));
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
                            $("#request_date").val(stock.formatted_created_at ?? '');
                            $("#modal_total_new").text(stock.supplier_new_spareable ?? 0);
                            $("#modal_total_used").text(stock.supplier_used_spareable ?? 0);
                            $("#original_new_spareable").val(stock.new_spareable ?? 0);
                            $("#original_used_spareable").val(stock.used_spareable ?? 0);

                            if (stock.status == 4) {
                                $(".btn-danger, .btn-primary").hide();
                                $(".btn-success").show();
                                $(".receivedRequestBTN").text("Accept");
                            } else if (stock.status == 6) {
                                $(".btn-danger, .btn-primary").hide();
                                $(".btn-success, .btn-primary").hide();
                            } else if (stock.status == 3) {
                                $(".btn-danger, .btn-primary").hide();
                                $(".btn-success, .btn-primary").hide();
                            } else if (stock.status == 5) {
                                $(".btn-danger, .btn-primary").hide();
                                $(".btn-success, .btn-primary").hide();
                            } else {
                                $(".btn-danger, .btn-primary").show();
                                $(".btn-success, .btn-primary").show();
                                $(".receivedRequestBTN").text("Received Request");
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


        //For deleting
        function deleteStockdata(id) {

            $("#delete_id").val(id);

        }


        //for checkbox
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


        //Message for success and error time limit
        $(document).ready(function () {
            setTimeout(function () {
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
            // console.log("Script Loaded!");

            // ✅ Function to validate new & used spareable inputs
            function validateSpareableInputs() {
            let requestedQty = parseInt($("#modal_req_qty").text().replace(/[^\d]/g, '')) || 0;
            let newSpareable = parseInt($("#modal_new_spareable").val()) || 0;
            let usedSpareable = parseInt($("#modal_used_spareable").val()) || 0;
            let totalSpareable = newSpareable + usedSpareable;

            let originalNew = parseInt($("#original_new_spareable").val()) || 0;
            let originalUsed = parseInt($("#original_used_spareable").val()) || 0;

            let errorMsg = "";

            if (totalSpareable > requestedQty) {
                errorMsg = "Total spareable quantity cannot exceed Requested Quantity.";
            } else if (newSpareable > originalNew) {
                errorMsg = "Entered new spareable exceeds available new spareable quantity.";
            } else if (usedSpareable > originalUsed) {
                errorMsg = "Entered used spareable exceeds available used spareable quantity.";
            }

            $("#error_message").text(errorMsg);

            return errorMsg === "";
        }

            $("#modal_new_spareable, #modal_used_spareable").on("input", function () {
                validateSpareableInputs();
            });

            $(document).on("click", "#confirmReceivedRequest", function (e) {
                e.preventDefault();

                let requestId = $("#mainModalForm").find("#request_id").val();
                let newSpareable = $("#modal_new_spareable").val();
                let usedSpareable = $("#modal_used_spareable").val();
                let supplierTotalQty = $("#modal_total_qty").text().trim();

                if (!requestId) {
                    // console.log("Request ID is missing!");
                    return;
                }

                if (!validateSpareableInputs()) {
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
                        // console.log("AJAX Success:", response);
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
                // console.log("Query Event Triggered!");

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
                success: function (response) {
                    let html = "";
                    // console.log(response);
                    response.sort((a, b) => b.id - a.id);

                    if (response.length > 0) {
                        response.forEach(status => {
                            let message = status.decline_msg ? status.decline_msg : (status.query_msg ? status.query_msg : 'N/A');
                            let unreadStyle = status.is_read == 0
                                ? 'style="font-weight: bold; text-decoration: underline; background-color: #e9ecef;"'
                                : '';

                            html += `<tr ${unreadStyle} data-status-id="${status.id}">
                                <td>
                                    <span class="badge badge-${status.status_id == 2 ? 'success' :
                                        (status.status_id == 3 ? 'danger' :
                                            (status.status_id == 4 ? 'info' : 'secondary'))}">
                                        ${status.status_name}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-link text-primary view-message" data-message="${message}" data-status-id="${status.id}">
                                        ${message.length > 20 ? message.substring(0, 20) + '...' : message}
                                    </button>
                                </td>
                                <td>${status.requestor_name}</td>
                                <td>${new Date(status.updated_at).toLocaleString()}</td>
                            </tr>`;
                        });
                    } else {
                        html = `<tr><td colspan="4" class="text-center">No status updates found.</td></tr>`;
                    }

                    // Destroy previous DataTable (if exists)
                    if ($.fn.DataTable.isDataTable('.modal-table')) {
                        $('.modal-table').DataTable().clear().destroy();
                    }

                    // Append new rows
                    $("#requestStatusData").html(html);

                    // Reinitialize DataTable
                    $('.modal-table').DataTable({
                        ordering: true,
                        paging: false,
                        searching: false,
                        info: false
                    });

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

            let stockId = $('#StockdataID').val();
            let stockStatus = $('#StockdataStatusName').val();

            // console.log(stockId);
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
        });



        // Function to open the Edit Received Request Modal
        function openEditReceivedRequestModal(requestId) {
            $.ajax({
                url: "{{ url('/user/request-stock/get-request-status') }}/" + requestId,
                type: 'GET',
                success: function (response) {
                    // console.log(response);
                    if (response.success) {
                        // Store requestId in the hidden input field
                        $('#edit_request_id').val(requestId);

                        // Populate modal fields
                        $('#edit_modal_total_qty').text(response.data.supplier_qty);
                        $('#edit_modal_req_qty').text(response.data.requested_qty);
                        $('#edit_modal_total_new').text(response.data.supplier_new_spareable);
                        $('#edit_modal_total_used').text(response.data.supplier_used_spareable);

                        $('#edit_modal_new_spareable').val(response.data.supplier_new_spareable);
                        $('#edit_modal_used_spareable').val(response.data.supplier_used_spareable);

                        // Remove previous modals and show new one
                        $('#editReceivedRequestModal').modal('hide');
                        $('.modal-backdrop').remove();
                        $('body').removeClass('modal-open').css('overflow', 'auto');

                        setTimeout(() => {
                            $('#editReceivedRequestModal').modal('show');
                        }, 300);
                    } else {
                        alert(response.message);
                    }
                },
                error: function () {
                    alert('Failed to fetch request details.');
                }
            });
        }

        $(document).on('click', '.sub-modal-close', function () {
            $('#editReceivedRequestModal').modal('hide');
        });

        $('#editReceivedRequestModal').on('hidden.bs.modal', function () {
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open').css('overflow', 'auto');
        });


        $('#confirmEditReceivedRequest').click(function () {
            let requestId = $('#edit_request_id').val();
            let newSpareable = parseInt($('#edit_modal_new_spareable').val()) || 0;
            let usedSpareable = parseInt($('#edit_modal_used_spareable').val()) || 0;
            let requestedQtyText = $('#edit_modal_req_qty').text().replace(/[^\d]/g, ''); // remove ALL non-digits
            let requestedQty = parseInt(requestedQtyText) || 0;

            if ((newSpareable + usedSpareable) > requestedQty) {
                $('#edit_error_message').text("Total of 'New' and 'Used' cannot exceed the Requested Quantity.");
                return;
            }
            $.ajax({
                url: "{{ url('/user/request-stock/update-request-status') }}/" + requestId,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    new_spareable: newSpareable,
                    used_spareable: usedSpareable
                },
                success: function (response) {
                    if (response.success) {
                        window.location.href = "{{ route('incoming_request_list') }}";
                    } else {
                        $('#edit_error_message').text(response.message);
                    }
                },
                error: function () {
                    alert('An error occurred while updating the request.');
                }
            });
        });



    </script>
    <script>
        $(document).ready(function () {
            $('#edp_code').select2({
                theme: 'bootstrap4',
                placeholder: "Select EDP Code...",
                allowClear: true,
                width: '100%'
            });
        });
    </script>


@endsection
