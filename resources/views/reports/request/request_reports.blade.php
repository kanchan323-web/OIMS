@extends('layouts.frontend.layout')

@section('page-content')
<div class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                {{ Breadcrumbs::render('Request_Report') }}
                <div class="row justify-content-between">
                    <div class="col-sm-6 col-md-9">
                        <form id="filterForm" class="mr-3 position-relative">
                            <div class="row">
                                <div class="col-md-3 mb-2">
                                    <label for="from_date">From Date</label>
                                    <input type="date" class="form-control" name="from_date" id="from_date">
                                </div>

                                <div class="col-md-3 mb-2">
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
                    <div class="col-sm-6 col-md-3">
                        <div class="user-list-files d-flex">
                            <a href="{{ route('report_stockPdfDownload') }}"
                                class="btn btn-primary ml-2 d-flex align-items-center justify-content-center"
                                id="downloadPdf" target="_blank">
                                <i class="fas fa-file-pdf mr-1"></i> Export PDF
                            </a>
                            <a href="{{ route('report_stockExcelDownload') }}"
                                class="btn btn-primary ml-2 d-flex align-items-center justify-content-center"
                                id="downloadexcel" target="_blank">
                                <i class="fas fa-file-excel mr-1"></i> Export Excel
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="table-responsive rounded mb-3">
                    <table class="data-tables table mb-0 tbl-server-info">
                        <thead class="bg-white text-uppercase">
                            <tr class="ligth ligth-data" id="tableHeaders">
                                <!-- Dynamic Headers -->
                            </tr>
                        </thead>
                        <tbody class="ligth-body" id="reportTable">
                            <!-- Dynamic Data -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Scripts --}}
<script>
    $(document).ready(function () {

        const reportType = 'transaction_history'; 

        function formatIndianNumber(x) {
            if (x == null) return '0';
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

        function fetchReport() {
            let fromDate = $("#from_date").val();
            let toDate = $("#to_date").val();

            $.ajax({
                url: "{{ route('report.fetch') }}",
                type: "GET",
                data: {
                    report_type: reportType,
                    from_date: fromDate,
                    to_date: toDate
                },
                dataType: "json",
                beforeSend: function () {
                    // You can add loader here
                },
                success: function (response) {
                    console.log(response);
                    let tableBody = $("#reportTable");
                    let tableHeaders = $("#tableHeaders");

                    tableBody.empty();
                    tableHeaders.empty();

                    // Set table headers
                    let headersHtml = '';

                    if (reportType === 'transaction_history') {
                        headersHtml = `
                            <th>EDP</th>
                            <th>Description</th>
                            <th>Change in New</th>
                            <th>Change in Used</th>
                            <th>Qty</th>
                            <th>Transaction</th>
                            <th>Reference ID</th>
                            <th>Transaction Date</th>
                            <th>Receiver</th>
                            <th>Supplier</th>
                        `;
                    } else if (reportType === 'summary') {
                        headersHtml = `
                            <th>Category</th>
                            <th>Total Requests</th>
                            <th>Approved</th>
                            <th>Declined</th>
                            <th>Pending</th>
                        `;
                    } 
                    // Add more else if for 'approval_rates', 'fulfillment_status', etc.

                    tableHeaders.html(headersHtml);

                    if (response.data && response.data.length > 0) {
                        response.data.forEach(function (item) {
                            let rowHtml = '';

                            if (reportType === 'transaction_history') {
                                rowHtml = `
                                    <tr>
                                        <td>${item.EDP_Code ?? '-'}</td>
                                        <td>${item.description ?? '-'}</td>
                                        <td>${formatIndianNumber(item.new_spareable ?? 0)}</td>
                                        <td>${formatIndianNumber(item.used_spareable ?? 0)}</td>
                                        <td>${formatIndianNumber(item.qty ?? 0)}</td>
                                        <td>${item.transaction_type ?? '-'}</td>
                                        <td>${item.reference_id ?? '-'}</td>
                                        <td>${item.updated_at ?? '-'}</td>
                                        <td>${item.receiver ?? '-'}</td>
                                        <td>${item.supplier ?? '-'}</td>
                                    </tr>
                                `;
                            } else if (reportType === 'summary') {
                                rowHtml = `
                                    <tr>
                                        <td>${item.category ?? '-'}</td>
                                        <td>${item.total_requests ?? 0}</td>
                                        <td>${item.approved ?? 0}</td>
                                        <td>${item.declined ?? 0}</td>
                                        <td>${item.pending ?? 0}</td>
                                    </tr>
                                `;
                            }

                            tableBody.append(rowHtml);
                        });
                    } else {
                        tableBody.html(`<tr><td colspan="10" class="text-center">No records found</td></tr>`);
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error fetching report:", error);
                    alert('Error fetching report. Check console.');
                }
            });
        }

        // Auto-fetch on page load
        fetchReport();

        // Fetch when Search button clicked
        $("#filterButton").click(function () {
            fetchReport();
        });

    });
</script>
@endsection
