@extends('layouts.frontend.layout')
@section('page-content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    {{ Breadcrumbs::render('Stock_Report') }}
                    <div class="row justify-content-between">
                        <div class="col-sm-6 col-md-9">
                            <div id="user_list_datatable_info" class="dataTables_filter">
                                <form id="filterForm" class="mr-3 position-relative">
                                    <div class="row">
                                        <div class="col-md-3 mb-2">
                                            <label for="edp_code">Report Types</label>
                                            <select class="form-control" name="report_type" id="report_type">
                                                <option disabled selected>Select Report Type...</option>
                                                <option value="overview">Stock Overview</option>
                                                <option value="stock_receiver">Stock Received</option>
                                                <option value="stock_issuer">Stock Issued</option>
                                                <option value="transaction_history">Transaction History</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <label for="form_date">From Date</label>
                                            <input type="date" class="form-control" name="form_date" id="form_date">
                                        </div>

                                        <div class="col-md-2 mb-2">
                                            <label for="to_date">To Date</label>
                                            <input type="date" class="form-control" name="to_date" id="to_date">
                                        </div>

                                        <div class="col-md-2 mb-2 d-flex align-items-end">
                                            <button type="button" class="btn btn-primary mr-2"
                                                id="filterButton">Search</button>
                                            <a href="{{ route('admin.stock_list') }}"
                                                class="btn btn-secondary ml-2">Reset</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
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
                        <table id="dynamicTable" class="table mb-0 tbl-server-info">
                            <thead class="bg-white text-uppercase">
                                <tr class="ligth ligth-data" id="tableHeaders" role="row"></tr>
                            </thead>
                            <tbody class="ligth-body" id="reportTable"></tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function formatIndianNumber(x) {
            if (x == null || x === '') return '0';
            x = parseFloat(x); // Safely parse number
            if (isNaN(x)) return '0'; // If still NaN, return 0
            let parts = x.toFixed(2).split(".");
            let integerPart = parts[0];
            let decimalPart = parts.length > 1 ? "." + parts[1] : "";
            let lastThree = integerPart.substring(integerPart.length - 3);
            let otherNumbers = integerPart.substring(0, integerPart.length - 3);
            if (otherNumbers !== '')
                lastThree = ',' + lastThree;
            return otherNumbers.replace(/\B(?=(\d{2})+(?!\d))/g, ",") + lastThree + decimalPart;
        }


        $(document).ready(function () {
            function fetchReport() {
                let formData = $("#filterForm").serialize();
                console.log("Form Data:", formData);

                $.ajax({
                    type: "GET",
                    url: "{{ route('report_stock_filter') }}",
                    data: formData,
                    success: function (response) {
                        console.log("AJAX Response:", response.data);

                        if ($.fn.DataTable.isDataTable('#dynamicTable')) {
                            $('#dynamicTable').DataTable().destroy();
                        }

                        let tableBody = $("#reportTable");
                        let tableHeaders = $("#tableHeaders");
                        tableBody.empty();
                        tableHeaders.empty();

                        if (!response.data || response.data.length === 0) {
                            tableHeaders.html("");
                            tableBody.html('<tr><td colspan="12" class="text-center">No records found</td></tr>');
                            return;
                        }

                        let reportType = $("#report_type").val();
                        let headers = "";
                        let rows = "";

                        switch (reportType) {
                            case "overview":
                                headers = "<th>Sr.No</th><th>EDP Code</th><th>Section</th><th>Category</th><th>Total Quantity</th><th>Date Updated</th>";
                                $.each(response.data, function (index, stockdata) {
                                    rows += `<tr>
                                            <td>${index + 1}</td>
                                            <td>${stockdata.EDP_Code}</td>
                                            <td>${stockdata.section}</td>
                                            <td>${stockdata.description}</td>
                                            <td>${stockdata.qty}</td>
                                            <td>${stockdata.date}</td>
                                        </tr>`;
                                });
                                break;

                            case "stock_receiver":
                                headers = "<th>Sr.No</th><th>Request ID</th><th>Edp Code</th><th>Description</th><th>Received QTY</th><th>Supplier Rig</th><th>Receipt Date</th>";
                                $.each(response.data, function (index, stockdata) {
                                    rows += `<tr>
                                            <td>${index + 1}</td>
                                            <td>${stockdata.RID}</td>
                                            <td>${stockdata.EDP_Code}</td>
                                            <td>${stockdata.description}</td>
                                            <td>${stockdata.requested_qty}</td>
                                            <td>${stockdata.name}</td>
                                            <td>${stockdata.receipt_date}</td>
                                        </tr>`;
                                });
                                break;

                            case "stock_issuer":
                                headers = "<th>Sr.No</th><th>Request ID</th><th>Edp Code</th><th>Description</th><th>Issued QTY</th><th>Receiver Rig</th><th>Issued Date</th>";
                                $.each(response.data, function (index, stockdata) {
                                    rows += `<tr>
                                            <td>${index + 1}</td>
                                            <td>${stockdata.RID}</td>
                                            <td>${stockdata.EDP_Code}</td>
                                            <td>${stockdata.description}</td>
                                            <td>${stockdata.requested_qty}</td>
                                            <td>${stockdata.name}</td>
                                            <td>${stockdata.issued_date}</td>
                                        </tr>`;
                                });
                                break;

                            case "transaction_history":
                                headers = "<th>Sr.No</th><th>EDP</th><th>Description</th><th>Change in New</th><th>Change in Used</th><th>Qty</th><th>Transaction</th><th>Reference ID</th><th>Transaction Date</th><th>Receiver</th><th>Supplier</th>";

                                $.each(response.data, function (index, item) {
                                    rows += `<tr>
                                            <td>${index + 1}</td>
                                            <td>${item.EDP_Code ?? '-'}</td>
                                            <td>${item.description ?? '-'}</td>
                                            <td>${formatIndianNumber(item.new_spareable ?? 0)}</td>
                                            <td>${formatIndianNumber(item.used_spareable ?? 0)}</td>
                                            <td>${formatIndianNumber(item.qty ?? 0)}</td>
                                            <td>${item.transaction_type ?? '-'}</td>
                                            <td>${item.reference_id ?? '-'}</td>
                                            <td>${item.updated_at_formatted ?? '-'}</td>
                                            <td>${item.receiver ?? '-'}</td>
                                            <td>${item.supplier ?? '-'}</td>
                                        </tr>`;
                                });
                                break;

                            default:
                                tableBody.html('<tr><td colspan="12" class="text-center">Invalid Report Type</td></tr>');
                                return;
                        }

                        tableHeaders.html(headers);
                        tableBody.html(rows);

                        // Reinitialize DataTable
                        $('#dynamicTable').DataTable({
                            ordering: true,
                            paging: true,
                            searching: true
                        });
                    },
                    error: function (xhr, status, error) {
                        console.error("Error fetching data:", error);
                    }
                });
            }

            // Bind buttons
            $("#filterButton").click(fetchReport);
            $("#report_type").change(fetchReport);

            // Download PDF
            $("#downloadPdf").click(function (e) {
                e.preventDefault();
                let baseUrl = "{{ route('report_stockPdfDownload') }}";
                let formData = $("#filterForm").serializeArray();
                let filteredParams = formData
                    .filter(item => item.value.trim() !== "")
                    .map(item => `${encodeURIComponent(item.name)}=${encodeURIComponent(item.value)}`)
                    .join("&");
                let finalUrl = filteredParams ? `${baseUrl}?${filteredParams}` : baseUrl;
                window.open(finalUrl, '_blank');
            });

            // Download Excel
            $("#downloadexcel").click(function (e) {
                e.preventDefault();
                let baseUrl = "{{ route('report_stockExcelDownload') }}";
                let formData = $("#filterForm").serializeArray();
                let filteredParams = formData
                    .filter(item => item.value.trim() !== "")
                    .map(item => `${encodeURIComponent(item.name)}=${encodeURIComponent(item.value)}`)
                    .join("&");
                let finalUrl = filteredParams ? `${baseUrl}?${filteredParams}` : baseUrl;
                window.open(finalUrl, '_blank');
            });
        });
    </script>

@endsection