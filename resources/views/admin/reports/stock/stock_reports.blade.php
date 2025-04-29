@extends('layouts.frontend.admin_layout')
@section('page-content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row justify-content-between">
                        <div class="col-sm-6 col-md-9">
                            <div id="user_list_datatable_info" class="dataTables_filter">
                                <form id="filterForm" class="mr-3 position-relative">
                                    <div class="row">
                                        <div class="col-md-3 mb-2">
                                            <label for="edp_code">Report Type</label>
                                            <select class="form-control" name="report_type" id="report_type">
                                                <option disabled selected>Select Report Type...</option>
                                                <option value="summary">Stock Summary</option>
                                                <option value="additions">Stock Additions</option>
                                                <option value="removals">Stock Removals</option>
                                                <option value="adjustments">Stock Adjustments </option>
                                                <option value="consumptions">Stock Consumptions</option>
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
        <table id="dynamicAdminTable" class="table mb-0 tbl-server-info">
            <thead class="bg-white text-uppercase">
                <tr class="ligth ligth-data" id="tableHeaders"></tr>
            </thead>
            <tbody class="ligth-body" id="reportTable"></tbody>
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

        $.ajax({
            type: "GET",
            url: "{{ route('admin.report_stock_filter') }}",
            data: formData,
            dataType: "json",
            success: function (response) {
                console.log("AJAX Response:", response.data);

                // Destroy existing DataTable instance if exists
                if ($.fn.DataTable.isDataTable('#dynamicAdminTable')) {
                    $('#dynamicAdminTable').DataTable().destroy();
                }

                let tableBody = $("#reportTable");
                let tableHeaders = $("#tableHeaders");

                tableBody.empty();
                tableHeaders.empty();

                if (!response.data || response.data.length === 0) {
                    tableBody.html('<tr><td colspan="10" class="text-center">No records found</td></tr>');
                    return;
                }

                let reportType = $("#report_type").val();
                let headers = "";
                let rows = "";

                switch (reportType) {
                    case "summary":
                        headers = "<th>Sr.No</th><th>Rig Name</th><th>EDP Code</th><th>Category</th><th>Total QTY</th><th>Available QTY</th><th>Date</th>";
                        $.each(response.data, function (index, stockdata) {
                            var dateObj = new Date(stockdata.created_at);
                     var formattedDate = ("0" + dateObj.getDate()).slice(-2) + "-" +
                        ("0" + (dateObj.getMonth() + 1)).slice(-2) + "-" +
                        dateObj.getFullYear();
                            rows += `<tr>
                                <td>${index + 1}</td>
                                <td>${stockdata.location_name}</td>
                                <td>${stockdata.EDP_Code}</td>
                                <td>${stockdata.description}</td>
                                <td>${stockdata.initial_qty}</td>
                                <td>${stockdata.qty}</td>
                                <td>${formattedDate}</td>
                            </tr>`;
                        });
                        break;

                    case "additions":
                        headers = "<th>Sr.No</th><th>Edp Code</th><th>Description</th><th>Requester Rig</th><th>Add Stock</th><th>Supplier Rig</th><th>Date</th>";
                        $.each(response.data, function (index, stockdata) {
                            var dateObj = new Date(stockdata.created_at);
                     var formattedDate = ("0" + dateObj.getDate()).slice(-2) + "-" +
                        ("0" + (dateObj.getMonth() + 1)).slice(-2) + "-" +
                        dateObj.getFullYear();
                            rows += `<tr>
                                <td>${index + 1}</td>
                                <td>${stockdata.EDP_Code}</td>
                                <td>${stockdata.description}</td>
                                <td>${stockdata.location_name}</td>
                                <td>${stockdata.requested_qty}</td>
                                <td>${stockdata.name}</td>
                                 <td>${formattedDate}</td>
                            </tr>`;
                        });
                        break;

                    case "removals":
                        headers = "<th>Sr.No</th><th>Edp Code</th><th>Description</th><th>From Rig</th><th>Remove</th><th>Requestor Rig</th><th>Date</th>";
                        $.each(response.data, function (index, stockdata) {

                            var dateObj = new Date(stockdata.created_at);
    var formattedDate = ("0" + dateObj.getDate()).slice(-2) + "-" +
                        ("0" + (dateObj.getMonth() + 1)).slice(-2) + "-" +
                        dateObj.getFullYear();

                            rows += `<tr>
                                <td>${index + 1}</td>
                                <td>${stockdata.EDP_Code}</td>
                                <td>${stockdata.description}</td>
                                <td>${stockdata.location_name}</td>
                                <td>${stockdata.requested_qty}</td>
                                <td>${stockdata.name}</td>
                                    <td>${formattedDate}</td>
                            </tr>`;
                        });
                        break;

                    case "adjustments":
                        headers = "<th>Sr.No</th><th>Edp Code</th><th>Description</th><th>Req Rig</th><th>Req QTY</th><th>Supplier Rig</th><th>Supplier QTY</th><th>Date</th>";
                        $.each(response.data, function (index, stockdata) {
                            var dateObj = new Date(stockdata.updated_at);
    var formattedDate = ("0" + dateObj.getDate()).slice(-2) + "-" +
                        ("0" + (dateObj.getMonth() + 1)).slice(-2) + "-" +
                        dateObj.getFullYear();

                            rows += `<tr>
                                <td>${index + 1}</td>
                                <td>${stockdata.EDP_Code}</td>
                                <td>${stockdata.description}</td>
                                <td>${stockdata.req_name}</td>
                                <td>${stockdata.req_qty}</td>
                                <td>${stockdata.sup_name}</td>
                                <td>${stockdata.sup_qty}</td>
                                <td>${formattedDate}</td>
                            </tr>`;
                        });
                        break;

                    case "consumptions":
                        headers = "<th>Sr.No</th><th>EDP Code</th><th>Description</th><th>Total</th><th>Consumed</th><th>Consumed Type</th><th>Date</th>";
                        $.each(response.data, function (index, stockdata) {
                            let date = new Date(stockdata.created_at).toISOString().split('T')[0];
                            rows += `<tr>
                                <td>${index + 1}</td>
                                <td>${stockdata.EDP_Code}</td>
                                <td>${stockdata.description}</td>
                                <td>${stockdata.avl_qty}</td>
                                <td>${stockdata.consume}</td>
                                <td>${stockdata.name}</td>
                                <td>${date}</td>
                            </tr>`;
                        });
                        break;

                    default:
                        tableBody.html('<tr><td colspan="10" class="text-center">Invalid Report Type</td></tr>');
                        return;
                }

                tableHeaders.html(headers);
                tableBody.html(rows);

                // Reinitialize DataTable
                $('#dynamicAdminTable').DataTable({
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

    $("#filterButton").click(fetchReport);
    $("#report_type").change(fetchReport);
});


        $(document).ready(function() {
            $("#downloadPdf").click(function(e) {
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
        });

        $(document).ready(function() {
            $("#downloadexcel").click(function(e) {
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
