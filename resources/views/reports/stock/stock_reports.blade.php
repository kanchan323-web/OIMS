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
                                                <option value="2">Stock Movements</option>
                                                <option value="3">Stock Consumptions</option>
                                                <option value="4">Stock Replenishment</option>
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

                                    <div class="col-md-4 mb-2 d-flex align-items-end">
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
                            <a href="{{ route('stock_list_pdf') }}"
                                class="btn btn-primary ml-2 d-flex align-items-center justify-content-center"
                                id="downloadPdf" target="_blank">
                                <i class="fas fa-file-pdf mr-1"></i> Export PDF
                            </a>
                            <a href="{{ route('stock_list_pdf') }}"
                                class="btn btn-primary ml-2 d-flex align-items-center justify-content-center"
                                id="downloadPdf" target="_blank">
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
                            <tr class="ligth ligth-data">
                                <th>Sr.No</th>
                                <th>EDP Code</th>
                                <th>Section</th>
                                <th>Category</th>
                                <th>Total Qty </th>
                                <th>Net Qty</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody class="ligth-body" id="stockTable">
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
                        console.log(response.data);
                        let tableBody = $("#stockTable");
                        tableBody.empty();

                        if (response.data && response.data.length > 0) {
                            $.each(response.data, function (index, stockdata) {
                               // console.log("Stock data:", stockdata);\
                               var date = stockdata.created_at;
                               var dateObj = new Date(date);
                               var formattedDate = dateObj.toISOString().split('T')[0];

                                tableBody.append(`
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${stockdata.EDP_Code}</td>
                                    <td>${stockdata.section}</td>
                                    <td>${stockdata.description}</td>
                                    <td>${stockdata.initial_qty}</td>
                                    <td>${stockdata.qty}</td>
                                    <td>${formattedDate}</td>
                                </tr>
                            `);
                            });
                        } else {
                            tableBody.append(
                                `<tr><td colspan="7" class="text-center">No records found</td></tr>`
                            );
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("Error fetching data:", error);
                    }
                });
            });
        });

</script>

@endsection
