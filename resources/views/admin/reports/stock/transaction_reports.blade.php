@extends('layouts.frontend.admin_layout')
@section('page-content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row justify-content-between">

                        <div class="offset-3 col-sm-6 col-md-9">
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

                        <div class="col-12">
                            <div id="user_list_datatable_info" class="card p-3 shadow-sm border-0">

                                <form id="filterForm" class="w-100">
                                    <div class="row g-3">

                                        <!-- EDP Code -->
                                        <style>
                                            .select2-container--default .select2-selection--single {
                                                height: 41px;
                                                padding: 6px 12px;
                                                border: 1px solid #ced4da;
                                                border-radius: 0.375rem;
                                                /* Bootstrap 5 border-radius */
                                            }

                                            .select2-container--default .select2-selection--single .select2-selection__rendered {
                                                line-height: 28px;
                                                /* Adjust based on height */
                                            }

                                            .select2-container--default .select2-selection--single .select2-selection__arrow {
                                                height: 41px;
                                            }
                                        </style>
                                        <div class="col-md-2">
                                            <label for="edp_code" class="form-label fw-semibold">EDP Code</label>
                                            <select name="edp_code" id="edp_code" class="form-select custom-select2">
                                                <option value="">Select EDP Code</option>
                                                @foreach ($edpCodes as $code)
                                                    <option value="{{ $code->edp_id }}">{{ $code->edp_code }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- RID -->
                                        <div class="col-md-2">
                                            <label for="rid" class="form-label fw-semibold">RID</label>
                                            <select name="rid" id="rid" class="form-select">
                                                <option value="">Select RID</option>
                                                @foreach ($RIDList as $rid)
                                                    <option value="{{ $rid->RID }}">{{ $rid->RID }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Receiver -->
                                        <div class="col-md-2 ">
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
                                        <div class="col-md-2">
                                            <label for="from_date" class="form-label fw-semibold">From Date</label>
                                            <input type="date" class="form-control" name="from_date" id="from_date">
                                        </div>

                                        <!-- To Date -->
                                        <div class="col-md-2">
                                            <label for="to_date" class="form-label fw-semibold">To Date</label>
                                            <input type="date" class="form-control" name="to_date" id="to_date">
                                        </div>

                                        <!-- Buttons -->
                                        <div class="col-12 text-end pt-2">
                                            <button type="button" class="btn btn-primary me-2" id="filterButton">
                                                <i class="fas fa-search me-1"></i> Search
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary" id="resetButton">
                                                <i class="fas fa-undo me-1"></i> Reset
                                            </button>
                                        </div>

                                    </div>
                                </form>
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

        function formatIndianNumber(x) {
            if (x == null || x === '') return '0';

            let sign = '';
            if (typeof x === 'string' && (x.startsWith('+') || x.startsWith('-'))) {
                sign = x[0];
                x = x.substring(1); // Remove the sign from the number
            }

            let number = parseFloat(x);
            if (isNaN(number)) return '0';

            let parts = number.toFixed(2).split(".");
            let integerPart = parts[0];
            let decimalPart = "." + parts[1];

            let lastThree = integerPart.slice(-3);
            let otherNumbers = integerPart.slice(0, -3);

            if (otherNumbers !== '')
                lastThree = ',' + lastThree;

            let formatted = otherNumbers.replace(/\B(?=(\d{2})+(?!\d))/g, ",") + lastThree + decimalPart;

            return sign + formatted;
        }



        $(document).ready(function () {

            $('#edp_code').select2({
                placeholder: "Select EDP Code...",
                allowClear: true,
                width: '100%'
            });

            $('#rid').select2({
                placeholder: "Select RID...",
                allowClear: true,
                width: '100%'
            });

            function fetchReport() {
                let formData = $("#filterForm").serialize();
                let reportType = "transaction_history"; // Force report type

                $.ajax({
                    type: "GET",
                    url: "{{ route('admin.report_stock_filter') }}",
                    data: formData + "&report_type=" + reportType,
                    dataType: "json",
                    success: function (response) {
                        //console.log(response.data);
                        if ($.fn.DataTable.isDataTable('#dynamicAdminTable')) {
                            $('#dynamicAdminTable').DataTable().destroy();
                        }

                        let tableBody = $("#reportTable");
                        let tableHeaders = $("#tableHeaders");

                        tableBody.empty();
                        tableHeaders.empty();

                        if (!response.data || response.data.length === 0) {
                            tableBody.html('<tr><td colspan="11" class="text-center">No records found</td></tr>');
                            return;
                        }

                        let headers = "<th>Sr.No</th><th>EDP</th><th>Description</th><th>Change in New</th><th>Change in Used</th><th>Qty</th><th>Transaction</th><th>Reference ID</th><th>Transaction Date</th><th>Receiver</th><th>Supplier</th>";
                        let rows = "";

                        $.each(response.data, function (index, item) {
                            rows += `<tr>
                                            <td>${index + 1}</td>
                                            <td>${item.EDP_Code ?? '-'}</td>
                                            <td>${item.description ?? '-'}</td>
                                            <td>${formatIndianNumber(item.formatted_new_value ?? '0')}</td>
                                            <td>${formatIndianNumber(item.formatted_used_value ?? '0')}</td>
                                            <td>${formatIndianNumber(item.qty ?? 0)}</td>
                                            <td>${item.action ?? '-'}</td>
                                            <td>${item.reference_id ?? '-'}</td>
                                            <td>${item.updated_at_formatted ?? '-'}</td>
                                            <td>${item.receiver ?? '-'}</td>
                                            <td>${item.supplier ?? '-'}</td>
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


        });
    </script>
@endsection
