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
        $(document).ready(function() {
            // Filter Stock Data on Button Click

            function fetchReport() {
                let formData = $("#filterForm").serialize();
                $.ajax({
                    type: "GET",
                    url: "{{ route('report_stock_filter') }}",
                    data: $("#filterForm").serialize(),
                    success: function(response) {
                        console.log("AJAX Response:", response.data);
                        let tableBody = $("#reportTable");
                        let tableHeaders = $("#tableHeaders");
                        tableBody.empty();
                        tableHeaders.empty();
                        if (!response.data) {
                            console.warn("No data received.");
                            tableBody.html(
                                '<tr><td colspan="6" class="text-center">No records found</td></tr>'
                            );
                            return;
                        }
                        let reportType = $("#report_type").val();
                        let headers = "";
                        let rows = "";
                        switch (reportType) {
                            case "overview":
                                if (response.data && response.data.length > 0) {
                                    headers =
                                        "<th>Sr.No</th><th>EDP Code</th><th>Section</th><th>Category</th><th>Total Quantity</th><th>Date Updated</th>";
                                    $.each(response.data, function(index, stockdata) {
                                        var date = stockdata.updated_at;
                                        var dateObj = new Date(date);
                                        var formattedDate = dateObj.toISOString().split(
                                            'T')[0];
                                        rows += `<tr>
                                                <td>${index + 1}</td>
                                                <td>${stockdata.EDP_Code}</td>
                                                <td>${stockdata.section}</td>
                                                <td>${stockdata.description}</td>
                                                <td>${stockdata.qty}</td>
                                                <td>${stockdata.date}</td>
                                            </tr>`;
                                    });
                                }
                                break;
                            case "stock_receiver":
                                if (response.data && response.data.length > 0) {
                                    headers =
                                        "<th>Sr.No</th><th>Request ID</th><th>Edp Code</th><th>Description</th><th>Received QTY </th><th>Supplier Rig</th><th>Receipt Date</th>";
                                    $.each(response.data, function(index, stockdata) {
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
                                }
                                //}
                                break;

                            case "stock_issuer":
                                if (response.data && response.data.length > 0) {
                                    headers =
                                        "<th>Sr.No</th><th>Request ID</th><th>Edp Code</th><th>Description</th><th>Issued QTY</th><th>Receiver  Rig</th><th>Issued Date</th>";
                                    $.each(response.data, function(index, stockdata) {
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
                                }
                                break;
                            default:
                                tableBody.html(
                                    '<tr><td colspan="5" class="text-center">Invalid Report Type</td></tr>'
                                );
                                return;
                        }

                        tableHeaders.html(headers);
                        tableBody.html(rows ||
                            '<tr><td colspan="6" class="text-center">No records found</td></tr>'
                        );
                    },
                    error: function(xhr, status, error) {
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
