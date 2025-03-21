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

    <script>
        $(document).ready(function () {
            function fetchFilteredData() {
                $.ajax({
                    type: "GET",
                    url: "{{ route('request_stock_filter') }}", // Ensure this route exists
                    data: $("#filterForm").serialize(), // Serialize all form inputs
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
                                        <td>${stockdata.location_name}</td>
                                        <td><span class="badge ${badgeClass}">${stockdata.status_name}</span></td>
                                        <td>${stockdata.created_at ? stockdata.created_at : '-'}</td>
                                        <td>
                                            <a class="badge badge-info mr-2" data-toggle="modal"
                                                onclick="ViewRequestStatus(${stockdata.id})" data-placement="top"
                                                title="View Request Status" href="#">
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
    </script>
    
    
    
@endsection