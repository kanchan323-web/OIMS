@extends('layouts.frontend.admin_layout')
@section('page-content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row justify-content-between">
                        <div class="col-sm-6 col-md-9">
                            <div id="user_list_datatable_info" class="dataTables_filter">
                                <form id="filterForm" class="w-100">
                                    <div class="row align-items-end">

                                        <!-- EDP Code -->
                                        <div class="col-md-2 mb-2">
                                            <label for="edp_code">EDP Code</label>
                                            <select class="form-control" name="edp_code" id="edp_code">
                                                <option disabled selected>Select EDP Code...</option>
                                                @foreach ($edpCodes as $edp)
                                                    <option value="{{ $edp->edp_id }}">{{ $edp->edp_code }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Receiver -->
                                        <div class="col-md-2 mb-2">
                                            <label for="receiver_id">Receiver</label>
                                            <select name="receiver_id" id="receiver_id" class="form-control">
                                                <option value="">Select Receiver</option>
                                                @foreach ($receivers as $receiver)
                                                    <option value="{{ $receiver->id }}">{{ $receiver->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Supplier -->
                                        <div class="col-md-2 mb-2">
                                            <label for="supplier_id">Supplier</label>
                                            <select name="supplier_id" id="supplier_id" class="form-control">
                                                <option value="">Select Supplier</option>
                                                @foreach ($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- From Date -->
                                        <div class="col-md-2 mb-2">
                                            <label for="from_date">From Date</label>
                                            <input type="date" class="form-control" name="from_date" id="from_date">
                                        </div>

                                        <!-- To Date -->
                                        <div class="col-md-2 mb-2">
                                            <label for="to_date">To Date</label>
                                            <input type="date" class="form-control" name="to_date" id="to_date">
                                        </div>

                                        <!-- Buttons -->
                                        <div class="col-md-2 mb-2 d-flex">
                                            <button type="button" class="btn btn-primary mr-2"
                                                id="filterButton">Search</button>
                                            <button type="button" class="btn btn-secondary ml-2"
                                                id="resetButton">Reset</button>
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

            $('#edp_code').select2({
                placeholder: "Select EDP Code...",
                allowClear: true,
                width: '100%'
            });


            function fetchReport() {
                let formData = $("#filterForm").serialize();
                let reportType = "adjustments";

                $.ajax({
                    type: "GET",
                    url: "{{ route('admin.report_stock_filter') }}",
                    data: formData + "&report_type=" + reportType,
                    dataType: "json",
                    success: function (response) {
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
                $("#edp_code").val('');
                $("#reciever_id").val('');
                $("#supplier_id").val('');
                fetchReport();
            });

            fetchReport(); // Initial load

            function handleExport(buttonId, baseUrl) {
                $(buttonId).click(function (e) {
                    e.preventDefault();
                    let formData = $("#filterForm").serializeArray();
                    formData.push({ name: "report_type", value: "adjustments" });
                    let filteredParams = formData
                        .filter(item => item.value.trim() !== "")
                        .map(item => `${encodeURIComponent(item.name)}=${encodeURIComponent(item.value)}`)
                        .join("&");
                    let finalUrl = filteredParams ? `${baseUrl}?${filteredParams}` : baseUrl;
                    window.open(finalUrl, '_blank');
                });
            }

            // Pass full route URLs directly using Blade
            handleExport("#downloadPdf", "{{ route('report_stockPdfDownload') }}");
            handleExport("#downloadexcel", "{{ route('report_stockExcelDownload') }}");
        });
    </script>
@endsection