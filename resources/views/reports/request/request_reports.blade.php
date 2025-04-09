@extends('layouts.frontend.layout')

@section('page-content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row justify-content-between">
                        <div class="col-sm-6 col-md-9">
                            <form id="filterForm" class="mr-3 position-relative">
                                <div class="row">
                                    <div class="col-md-3 mb-2">
                                        <label for="report_type">Report Type</label>
                                        <select class="form-control" name="report_type" id="report_type">
                                            <option disabled selected>Select Report Type...</option>
                                            <option value="summary">Request Summary</option>
                                            <option value="approval_rates">Approval & Decline Rates</option>
                                            <option value="transaction_history">Transaction History</option>
                                            <option value="fulfillment_status">Request Fulfillment Status</option>
                                            <option value="consumption_details">Request Consumption Details</option>
                                        </select>
                                    </div>

                                    <div class="col-md-2 mb-2">
                                        <label for="from_date">From Date</label>
                                        <input type="date" class="form-control" name="from_date" id="from_date">
                                    </div>

                                    <div class="col-md-2 mb-2">
                                        <label for="to_date">To Date</label>
                                        <input type="date" class="form-control" name="to_date" id="to_date">
                                    </div>

                                    <div class="col-md-4 mb-2 d-flex align-items-end">
                                        <button type="button" class="btn btn-primary" id="filterButton">Search</button>
                                        <a href="{{ route('request_report') }}" class="btn btn-secondary ml-2">Reset</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="table-responsive rounded mb-3">
                        <table class="data-tables table mb-0 tbl-server-info">
                            <thead class="bg-white text-uppercase">
                                <tr class="ligth ligth-data" id="tableHeaders">
                                    <!-- Headers will be set dynamically -->
                                </tr>
                            </thead>
                            <tbody class="ligth-body" id="reportTable">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        $(document).ready(function () {
            function fetchReport() {
                let formData = $("#filterForm").serialize();
                // console.log("Form Data Sent:", formData); // Debugging

                $.ajax({
                    type: "GET",
                    url: "{{ route('report.fetch') }}",
                    data: formData,
                    dataType: "json",
                    beforeSend: function () {
                        // console.log("Sending AJAX request...");
                    },
                    success: function (response) {
                        // console.log("AJAX Response:", response);
                        let tableBody = $("#reportTable");
                        let tableHeaders = $("#tableHeaders");

                        tableBody.empty();
                        tableHeaders.empty();

                        if (!response.data) {
                            console.warn("No data received.");
                            tableBody.html('<tr><td colspan="6" class="text-center">No records found</td></tr>');
                            return;
                        }

                        let reportType = $("#report_type").val();
                        let headers = "";
                        let rows = "";

                        switch (reportType) {
                            case "summary":
                                headers = "<th>Sr.No</th><th>Total Requests</th><th>Approved</th><th>Declined</th><th>Pending</th>";
                                rows += `<tr>
                                                <td>1</td>
                                                <td class="text-purple"><strong>${response.data.total_requests ?? 0}</strong></td>
                                                <td class="text-success"><strong>${response.data.approved ?? 0}</strong></td>
                                                <td class="text-danger"><strong>${response.data.declined ?? 0}</strong></td>
                                                <td class="text-warning"><strong>${response.data.pending ?? 0}</strong></td>
                                            </tr>`;
                                break;

                            case "approval_rates":
                                headers = "<th>Sr.No</th><th>Approval Rate (%)</th><th>Decline Rate (%)</th><th>Pending (%)</th>";
                                rows += `<tr>
                                                <td>1</td>
                                                <td class="text-success"><strong>${response.data.approval_rate ?? 0}%</strong></td>
                                                <td class="text-danger"><strong>${response.data.decline_rate ?? 0}%</strong></td>
                                                <td class="text-warning"><strong>${response.data.pending_rate ?? 0}%</strong></td>
                                            </tr>`;
                                break;


                            case "transaction_history":
                                headers = "<th>Sr.No</th><th>Quantity</th><th>Status</th><th>Processed By</th><th>Rig Name</th><th>Created At</th>";

                                if (Array.isArray(response.data)) {
                                    response.data.forEach((item, index) => {
                                        // Determine status label and color
                                        let statusLabel, statusColor;
                                        switch (item.status_id) {
                                            case 1:
                                                statusLabel = "Pending";
                                                statusColor = "badge-warning"; // Yellow
                                                break;
                                            case 4:
                                                statusLabel = "Approved";
                                                statusColor = "badge-success"; // Green
                                                break;
                                            case 3:
                                                statusLabel = "Received";
                                                statusColor = "badge-primary"; // Blue
                                                break;
                                            case 6:
                                                statusLabel = "MIT";
                                                statusColor = "badge-purple"; // purple
                                                break;
                                            case 2:
                                                statusLabel = "Query";
                                                statusColor = "badge-info"; // Light blue
                                                break;
                                            case 5:
                                                statusLabel = "Declined";
                                                statusColor = "badge-danger"; // Red
                                                break;
                                            default:
                                                statusLabel = "Unknown";
                                                statusColor = "badge-secondary"; // Light Gray
                                        }

                                        rows += `<tr>
                                                            <td>${index + 1}</td>
                                                            <td>${item.supplier_qty ?? 0}</td>
                                                            <td><span class="badge ${statusColor}">${statusLabel}</span></td>
                                                            <td>${item.processed_by_name ?? '-'}</td>
                                                            <td>${item.rig_name ?? '-'}</td>
                                                            <td>${item.created_at ?? '-'}</td>
                                                        </tr>`;
                                    });
                                }


                                break;

                            case "fulfillment_status":
                                headers = "<th>Sr.No</th><th>Request ID</th><th>Requested Stock</th><th>Requesters Stock</th><th>Status</th><th>Expected Delivery</th><th>Actual Delivery</th>";

                                if (Array.isArray(response.data)) {
                                    response.data.forEach((item, index) => {
                                        // Determine status badge
                                        let statusBadge = item.status === "Delivered"
                                            ? `<span class="badge badge-success">Delivered</span>`
                                            : `<span class="badge badge-danger">Not Delivered</span>`;

                                        rows += `<tr>
                                            <td>${index + 1}</td>
                                            <td>${item.request_id ?? '-'}</td>
                                            <td>${item.requested_stock_item ?? 'Nill'}</td>
                                            <td>${item.requester_stock_item ?? 'Nill'}</td>
                                            <td>${statusBadge}</td>
                                            <td>${item.expected_delivery ?? '-'}</td>
                                            <td>${item.actual_delivery ?? '-'}</td>
                                        </tr>`;
                                    });
                                }
                                break;

                            case "consumption_details":
                                headers = "<th>Sr.No</th><th>Request ID</th><th>Requested Stock Item</th><th>Requester Stock Item</th><th>Initial Stock</th><th>Received Stock</th><th>Used Stock</th><th>Remaining Stock</th>";
                                if (Array.isArray(response.data)) {
                                    response.data.forEach((item, index) => {
                                        rows += `<tr>
                                                    <td>${index + 1}</td>
                                                    <td>${item.request_id ?? '-'}</td>    
                                                    <td>${item.requested_stock_item ?? '-'}</td>
                                                    <td>${item.requester_stock_item ?? '-'}</td>
                                                    <td>${item.initial_stock ?? '-'}</td>
                                                    <td>${item.received_stock ?? '-'}</td>
                                                    <td>${item.used_stock ?? 0}</td>
                                                    <td>${item.remaining_stock ?? 0}</td>
                                                </tr>`;
                                    });
                                }
                                break;

                            default:
                                tableBody.html('<tr><td colspan="5" class="text-center">Invalid Report Type</td></tr>');
                                return;
                        }

                        tableHeaders.html(headers);
                        tableBody.html(rows || '<tr><td colspan="6" class="text-center">No records found</td></tr>');
                    },
                    error: function (xhr, status, error) {
                        console.error("Error fetching data:", error);
                        console.log("Full Response:", xhr.responseText);
                        alert("An error occurred. Check console for details.");
                    }
                });
            }

            $("#filterButton").click(fetchReport);
            $("#report_type").change(fetchReport);
        });
    </script>

@endsection