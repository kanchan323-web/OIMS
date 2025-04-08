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
                                        <label for="edp_code">Report Type</label>
                                        <select class="form-control" name="report_type" id="report_type">
                                            <option disabled selected>Select Report Type...</option>
                                                <option value="1">Stock Overview</option>
                                                <option value="2">Stock Additions</option>
                                                <option value="3">Stock Removals</option>
                                                <option value="4">Stock Adjustments </option>
                                                <option value="5">Stock Consumptions</option>
                                                <option value="6">Stock Replenishment</option>
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

                           <!--         <div class="col-md-4 mb-2 d-flex align-items-end">
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
                                    </div>  -->

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
    $(document).ready(function () {
            // Filter Stock Data on Button Click
            $("#filterButton").click(function () {
                $.ajax({
                    type: "GET",
                    url: "{{ route('report_stock_filter') }}",
                    data: $("#filterForm").serialize(),
                    success: function (response) {
                         console.log("AJAX Response:", response.data.stock_addition);
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
                            case "1":
                            if (response.data && response.data.length > 0) {
                                headers = "<th>Sr.No</th><th>EDP Code</th><th>Section</th><th>Category</th><th>Total QTY</th><th>Available QTY</th><th>Date</th>";
                                $.each(response.data, function (index, stockdata) {
                                    var date = stockdata.created_at;
                                    var dateObj = new Date(date);
                                    var formattedDate = dateObj.toISOString().split('T')[0];
                                rows += `<tr>
                                                <td>${index + 1}</td>
                                                <td>${stockdata.EDP_Code}</td>
                                                <td>${stockdata.section}</td>
                                                <td>${stockdata.description}</td>
                                                <td>${stockdata.initial_qty}</td>
                                                <td>${stockdata.qty}</td>
                                                <td>${formattedDate}</td>
                                            </tr>`;
                                    });
                                }
                                break;

                            case "2":
                          //  if (response.data.stock_addition || response.data.stock_removal) {
                              //  console.log("AJAX Responsexcx:", response.data);
                                if (response.data && response.data.length > 0) {
                                headers = "<th>Sr.No</th><th>Edp Code</th><th>Description</th><th>Addition </th><th>Supplier Rig</th><th>Purchase Date</th>";
                                $.each(response.data, function (index, stockdata) {
                                rows += `<tr>
                                               <td>${index + 1}</td>
                                                <td>${stockdata.EDP_Code}</td>
                                                <td>${stockdata.description}</td>
                                                <td>${stockdata.requested_qty}</td>
                                                <td>${stockdata.name}</td>
                                                <td>${stockdata.created_at}</td>
                                            </tr>`;
                                        });
                                    }
                                //}
                                break;

                                case "3":
                                        if (response.data && response.data.length > 0) {
                                         headers = "<th>Sr.No</th><th>Edp Code</th><th>Description</th><th>Remove </th><th>Requestor Rig</th><th>Sell Date</th>";
                                        $.each(response.data, function (index, stockdata) {
                                         rows += `<tr>
                                                <td>${index + 1}</td>
                                                    <td>${stockdata.EDP_Code}</td>
                                                    <td>${stockdata.description}</td>
                                                    <td>${stockdata.requested_qty}</td>
                                                    <td>${stockdata.name}</td>
                                                    <td>${stockdata.created_at}</td>
                                                </tr>`;
                                            });
                                        }
                                break;

                            case "4":
                                 if (response.data && response.data.length > 0) {
                                        headers = "<th>Sr.No</th><th>Edp Code</th><th>Description</th><th>Adjustments</th><th>Supplier Rig</th><th>Date</th>";
                                    $.each(response.data, function (index, stockdata) {
                                        rows += `<tr>
                                            <td>${index + 1}</td>
                                                <td>${stockdata.EDP_Code}</td>
                                                <td>${stockdata.description}</td>
                                                <td>${stockdata.qty}</td>
                                                <td>${stockdata.name}</td>
                                                <td>${stockdata.created_at}</td>
                                            </tr>`;
                                        });
                                    }
                            break;

                            case "5":
                            if (response.data && response.data.length > 0) {
                                headers = "<th>Sr.No</th><th>EDP Code</th><th>Description</th><th>Total</th><th>Consumed</th><th>Consumed Type</th><th>Date</th>";
                                $.each(response.data, function (index, stockdata) {
                                    var date = stockdata.created_at;
                                    var dateObj = new Date(date);
                                    var formattedDate = dateObj.toISOString().split('T')[0];
                                rows += `<tr>
                                                <td>${index + 1}</td>
                                                <td>${stockdata.EDP_Code}</td>
                                                <td>${stockdata.description}</td>
                                                <td>${stockdata.avl_qty}</td>
                                                <td>${stockdata.consume}</td>
                                                <td>${stockdata.name}</td>
                                                <td>${formattedDate}</td>
                                            </tr>`;
                                    });
                                }
                            break;

                            case "6":
                            if (response.data && response.data.length > 0) {
                                headers = "<th>Sr.No</th><th>EDP Code</th><th>Description</th><th>Replenishment  </th><th>Status</th><th>Date</th>";
                                $.each(response.data, function (index, stockdata) {
                                    var date = stockdata.created_at;
                                    var dateObj = new Date(date);
                                    var formattedDate = dateObj.toISOString().split('T')[0];
                                rows += `<tr>
                                                <td>${index + 1}</td>
                                                <td>${stockdata.EDP_Code}</td>
                                                <td>${stockdata.description}</td>
                                                <td>${stockdata.replinish}</td>
                                                <td>${stockdata.name}</td>
                                                <td>${formattedDate}</td>
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
                    }
                });
            });
        });

     $(document).ready(function () {
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
    });

    $(document).ready(function () {
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
