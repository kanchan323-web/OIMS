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

                                            <label for="edp_code">EDP Code</label>
                                            <select class="form-control" name="edp_code" id="edp_code">
                                                <option disabled selected>Select EDP Code...</option>
                                                
                                                    <option value=""></option>
                                               
                                            </select>
                                        </div>

                                    <div class="col-md-2 mb-2">
                                        <label for="Description">Description</label>
                                        <input type="text" class="form-control" placeholder="Description"
                                            name="Description" id="Description">
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
                                        <a href="{{ route('stock_list.request') }}"
                                            class="btn btn-secondary ml-2">Reset</a>
                                        <!-- <a href="{{ route('stock_list_pdf') }}"
                                            class="btn btn-danger ml-2 d-flex align-items-center justify-content-center"
                                            id="downloadPdf" target="_blank">
                                            <i class="fas fa-file-pdf mr-1"></i> Export PDF
                                        </a> -->

                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- <div class="col-sm-6 col-md-3">
                        <div class="user-list-files d-flex">
                            <a href="{{ route('add_stock') }}" class="btn btn-primary add-list"><i
                                    class="las la-plus mr-3"></i>Add Stock</a>
                            <a href="{{ route('import_stock') }}" class="btn btn-primary add-list"><i
                                    class="las la-plus mr-3"></i>Bulk Stocks </a>
                        </div>
                    </div> -->
                </div>
            </div>

            <div class="col-lg-12">
                <div class="table-responsive rounded mb-3">
                    <table class="data-tables table mb-0 tbl-server-info">
                        <thead class="bg-white text-uppercase">
                            <tr class="ligth ligth-data">
                                <th>Sr.No</th>
                                <th>Location Name</th>
                                <th>Requester ID</th>
                                <th>EDP</th>
                                <th>Quantity</th>
                                <th>Date</th>
                                <th>Action</th>
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
    $(document).ready(function() {
    // Filter Stock Data on Button Click
    $("#filterButton").click(function() {
        $.ajax({
            type: "GET",
            url: "{{ route('stock_filter') }}",
            data: $("#filterForm").serialize(),
            success: function(response) {
                let tableBody = $("#stockTable");
                tableBody.empty();

                if (response.data && response.data.length > 0) {
                    $.each(response.data, function(index, stockdata) {
                        let editButton = '';
                        if (response.datarig.includes(stockdata.user_id)) {
                            editButton = `
                                    <a class="badge badge-success mr-2" data-toggle="modal"
                                            onclick="makeRequest(${stockdata.id})"
                                            data-target=".bd-makerequest-modal-xl" data-placement="top" title="View"
                                            href="#">
                                            <i class="ri-arrow-right-circle-line"></i>
                                        </a>
                                    `;
                        }
                        tableBody.append(`
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${stockdata.location_name}</td>
                                    <td>${stockdata.EDP_Code}</td>
                                    <td>${stockdata.section}</td>
                                    <td>${stockdata.description}</td>
                                    <td>${stockdata.qty}</td>
                                    <td>
                                        <a class="badge badge-info mr-2" data-toggle="modal"
                                            onclick="viewstockdata(${stockdata.id})" data-target=".bd-example-modal-xl"
                                            data-placement="top" title="View" href="#"><i class="ri-eye-line mr-0"></i></a>
                                        ${editButton}
                                    </td>
                                </tr>
                            `);
                    });
                } else {
                    tableBody.append(
                        `<tr><td colspan="7" class="text-center">No records found</td></tr>`
                    );
                }
            },
            error: function(xhr, status, error) {
                console.error("Error fetching data:", error);
            }
        });
    });
});
</script>








@endsection
