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
                                            <label for="from_date">From Date</label>
                                            <input type="date" class="form-control" name="from_date" id="from_date">
                                        </div>

                                        <div class="col-md-3 mb-2">
                                            <label for="to_date">To Date</label>
                                            <input type="date" class="form-control" name="to_date" id="to_date">
                                        </div>

                                        <div class="col-md-3 mb-2 d-flex align-items-end">
                                            <button type="button" class="btn btn-primary mr-2" id="filterButton">Search</button>
                                            <button type="button" class="btn btn-secondary ml-2" id="resetButton">Reset</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-3">
                            <div class="user-list-files d-flex">
                                <a href="{{ route('report_stockPdfDownload') }}" class="btn btn-primary ml-2 d-flex align-items-center justify-content-center" id="downloadPdf" target="_blank">
                                    <i class="fas fa-file-pdf mr-1"></i> Export PDF
                                </a>
                                <a href="{{ route('report_stockExcelDownload') }}" class="btn btn-primary ml-2 d-flex align-items-center justify-content-center" id="downloadexcel" target="_blank">
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
                let reportType = "adjustments"; // fixed report type

                $.ajax({
                    type: "GET",
                    url: "{{ route('admin.report_stock_filter') }}",
                    data: formData + "&report_type=" + reportType,
                    dataType: "json",
                    success: function (response) {
                        console.log("AJAX Response:", response.data);

                        // Destroy DataTable if exists
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

                        let headers = "<th>Sr.No</th><th>Request ID</th><th>Edp Code</th><th>Description</th><th>Receiver</th><th>Reciept QTY</th><th>Supplier</th><th>Issued QTY</th><th>Date</th>";
                        let rows = "";

                        $.each(response.data, function (index, stockdata) {
                            rows += `<tr>
                                <td>${index + 1}</td>
                                <td>${stockdata.RID}</td>
                                <td>${stockdata.EDP_Code}</td>
                                <td>${stockdata.description}</td>
                                <td>${stockdata.req_name}</td>
                                <td>${stockdata.requested_qty}</td>
                                <td>${stockdata.sup_name}</td>
                                <td>${stockdata.supplier_qty}</td>
                                <td>${stockdata.date}</td>
                            </tr>`;
                        });

                        tableHeaders.html(headers);
                        tableBody.html(rows);

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

            $("#resetButton").click(function () {
                $("#from_date").val('');
                $("#to_date").val('');
                fetchReport(); // Reload table with cleared filters
            });

            // Load table on page load
            fetchReport();

            $("#downloadPdf").click(function (e) {
                e.preventDefault();
                let baseUrl = "{{ route('report_stockPdfDownload') }}";
                let formData = $("#filterForm").serializeArray();
                formData.push({ name: "report_type", value: "adjustments" });
                let filteredParams = formData
                    .filter(item => item.value.trim() !== "")
                    .map(item => `${encodeURIComponent(item.name)}=${encodeURIComponent(item.value)}`)
                    .join("&");
                let finalUrl = filteredParams ? `${baseUrl}?${filteredParams}` : baseUrl;
                window.open(finalUrl, '_blank');
            });

            $("#downloadexcel").click(function (e) {
                e.preventDefault();
                let baseUrl = "{{ route('report_stockExcelDownload') }}";
                let formData = $("#filterForm").serializeArray();
                formData.push({ name: "report_type", value: "adjustments" });
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
