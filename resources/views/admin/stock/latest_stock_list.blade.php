
@extends('layouts.frontend.admin_layout')
@section('page-content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    {{Breadcrumbs::render('admin_stock_list')}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert" id="successMessage">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Stock List</h4>
                        </div>

                        <div class="xl-offset-9 md-offset-4  sm-offset-4">
                            <a href="{{ route('admin.add_stock') }}" class="btn btn-primary add-list"><i
                                        class="las la-plus mr-1"></i>Add or Edit Stock</a>
                            <a href="{{ route('admin.import_stock') }}" class="btn btn-primary add-list"><i
                                        class="las la-plus mr-1"></i>Import Bulk Stocks </a>
                        </div>

                    </div>

                    <div class="col-lg-12">
                        <div class="table-responsive rounded mb-3">
                            <table class="table  tbl-server-info" id="adminStockTable">
                                <thead class="bg-white text-uppercase">
                                    <tr>
                                        <th>Sr.No</th>
                                        <th>Location Name(RID)</th>
                                        <th>EDP</th>
                                        <th>Category</th>
                                        <th>Section</th>
                                        <th>Description</th>
                                        <th>New Qty</th>
                                        <th>Used Qty</th>
                                        <th>Quantity</th>
                                        <th>Action</th>
                                    </tr>
                                     <tr>
                                        <th></th> <!-- For index, no input -->
                                        <form id="filterForm">
                                            <th><input type="text" name="location_name" placeholder="Search Location" /></th>
                                            <th><input type="text" name="edp_code" placeholder="Search EDP" /></th>
                                            <th><input type="text" name="category" placeholder="Search Category" /></th>
                                            <th><input type="text" name="section" placeholder="Search Section" /></th>
                                            <th><input type="text" name="description" placeholder="Search Description" /></th>
                                        </form>
                                        <th>
                                            <button id="resetBtn" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top"
                                                        data-original-title="Reset"><i class="fas fa-sync-alt" aria-hidden="true"></i></button>
                                        </th>
                                        <th>
                                             <button class="btn btn-sm btn-warning"
                                                id="downloadPdf" target="_blank" data-toggle="tooltip" data-placement="top"
                                                        data-original-title="Export PDF">
                                                <i class="fas fa-file-pdf mr-1"></i>  
                                            </button>
                                             <a href="{{ route('admin.stock_list_excel') }}"
                                                class="btn btn-sm btn-primary"
                                                id="downloadExcel" target="_blank" data-toggle="tooltip" data-placement="top"
                                                        data-original-title="Export Excel">
                                                <i class="fas fa-file-excel mr-1"></i> 
                                            </a>
                                        </th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    <div class="modal fade" id="DeleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{route('admin.Delete_stock')}}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Are you sure?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>You are about to delete <span class=""></span> and all of its contents.
                            </br>
                            <span style="font-weight:bold">This operation is permanent and cannot be undone.</span>
                        </p>
                        <input type="hidden" name="delete_id" id="delete_id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class=" btn btn-primary " data-dismiss="modal">Cancle</button>
                        <button type="submit" class=" btn btn-secondary ">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">View Stock</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <!-- <form class="needs-validation" novalidate method="POST" action="" id="addStockForm"> -->

                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <label for="">Location Id</label>
                                <input type="text" class="form-control" name="location_id" placeholder=" Location Id"
                                    id="location_id" readonly>
                                <div class="invalid-feedback">
                                    Enter location id
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="">Location Name</label>
                                <input type="text" class="form-control" placeholder=" Location Name" name="location_name1"
                                    id="location_name1" readonly>
                                <div class="invalid-feedback">
                                    Enter Location Name
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="">EDP Code</label>
                                <input type="text" class="form-control" name="edp_code1" placeholder=" EDP Code"
                                    id="edp_code1" readonly>
                                <div class="invalid-feedback">
                                    Enter EDP Code
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="category">Category</label>
                                <input type="text" class="form-control" name="category" placeholder=" Category "
                                    id="category" readonly>
                                <input type="hidden" name="category" id="hidden_category">
                                <div class="invalid-feedback">
                                    Please select a category
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="">Description </label>
                                <textarea class="form-control" id="description" name="description"
                                    placeholder="Enter Description" readonly></textarea>
                                <div class="invalid-feedback">
                                    Enter Description
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="section">Section</label>
                                <input type="text" class="form-control" name="section" placeholder=" Section " id="section"
                                    readonly>
                                <input type="hidden" name="section" id="hidden_section">
                                <div class="invalid-feedback">
                                    Please select a Section
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="">Available Quantity</label>
                                <input type="text" class="form-control" placeholder=" Available Quantity" name="qty"
                                    id="qty" readonly>
                                <div class="invalid-feedback">
                                    Enter Available Quantity
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="">Unit of Measurement </label>
                                <input type="text" class="form-control" name="measurement" placeholder="Unit of Measurement"
                                    id="measurement" readonly>
                                <div class="invalid-feedback">
                                    Enter Unit of Measurement
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="">New  </label>
                                <input type="text" class="form-control" placeholder=" New " name="new_spareable"
                                    id="new_spareable" readonly>
                                <div class="invalid-feedback">
                                    Enter New 
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="">Used  </label>
                                <input type="text" class="form-control" placeholder=" Used " name="used_spareable"
                                    id="used_spareable" readonly>
                                <div class="invalid-feedback">
                                    Enter Used 
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="">Remarks / Notes </label>
                                <textarea class="form-control" id="remarks" name="remarks" placeholder=" Remarks / Notes"
                                    readonly></textarea>
                                <div class="invalid-feedback">
                                    Enter Remarks / Notes
                                </div>
                            </div>
                        </div>
                        <!-- <button class="btn btn-primary" type="submit">Submit form</button>
                                                                                  <button type="reset" class="btn btn-danger">Reset</button>
                                                                                  <a href="" class="btn btn-light">Go Back</a> -->
                        <!-- </form> -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                </div>
            </div>
        </div>
    </div>

<script type="text/javascript">
    $(function () {
        var table = $('#adminStockTable').DataTable({
            processing: true,
            serverSide: true,
            //ajax: "{{ route('admin.stock_list_latest') }}",
            ajax: {
                url: "{{ route('admin.stock_list_latest') }}",
                data: function (d) {
                    // Send filter values with AJAX request
                    d.location_name = $('#adminStockTable thead input:eq(0)').val();
                    d.edp_code = $('#adminStockTable thead input:eq(1)').val();
                    d.category = $('#adminStockTable thead input:eq(2)').val();
                    d.section = $('#adminStockTable thead input:eq(3)').val();
                    d.description = $('#adminStockTable thead input:eq(4)').val();
                }
            },
            columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false},
                    {data: 'location_name', name: 'location_name'},
                    {data: 'edp_code', name: 'edp_code'},
                    {data: 'category', name: 'category'},
                    {data: 'section', name: 'section'},
                    {data: 'description', name: 'description'},
                    {data: 'new_spareable', name: 'new_spareable'},
                    {data: 'used_spareable', name: 'used_spareable'},
                    {data: 'qty', name: 'qty'},
                    {
                        data: 'id', 
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            // Base URL (adjust if needed)                           
                            var baseUrl = window.location.origin;
                            
                            // view button           
                            var viewBtn = '<a class="badge badge-info mr-2" data-toggle="modal" onclick="viewstockdata(' + data + ')" data-target=".bd-example-modal-xl" data-placement="top" title="View stock" href="#"><i class="ri-eye-line mr-0"></i> </a>';
                            
                            // edit button
                            var editBtn = '<a class="badge bg-success mr-2" href="' + baseUrl + '/OIMS/admin/edit_stock/' + data + '" data-placement="top" title="Edit stock"><i class="ri-pencil-line"></i></a>';
                            
                            return viewBtn + '' + editBtn;
                        }
                    }
                ]
        });

        // Apply search on input change
        $('#adminStockTable thead input').on('keyup change', function () {
            table.draw();
        });

        //Reset button logic
        $('#resetBtn').click(function () {
            $('#adminStockTable thead input').val('');
            table.draw(); 
        });
    });
</script>

<script>
    function formatToIndianNumber(num) {
        num = parseFloat(num);
        if (isNaN(num)) return '';

        let parts = num.toString().split(".");
        let integerPart = parts[0];
        let decimalPart = parts[1] ? '.' + parts[1] : '';

        let lastThree = integerPart.slice(-3);
        let rest = integerPart.slice(0, -3);
        if (rest !== '') {
            lastThree = ',' + lastThree;
        }

        let formatted = rest.replace(/\B(?=(\d{2})+(?!\d))/g, ",") + lastThree;
        return formatted + decimalPart;
    }

    function viewstockdata(id){
        var id = id;
        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "GET",
            url: "{{route('admin.stock_list_view')}}",
            data: {
                data: id
            },
            success: function (response) {
                // console.log(response.viewdata['edp_code']);
                $("#location_id").val(response.viewdata['location_id']);
                $("#location_name1").val(response.viewdata['location_name']);
                $("#edp_code1").val(response.viewdata['edp_code']);
                var sectionValue = response.viewdata['section'];
                $("#section").val(sectionValue);
                $("#hidden_section").val(sectionValue);
                var categoryValue = response.viewdata['category'];
                $("#category").val(categoryValue);
                $("#hidden_category").val(categoryValue);
                $("#qty").val(formatToIndianNumber(response.viewdata['qty']));
                $("#measurement").val(response.viewdata['measurement']);
                $("#new_spareable").val(formatToIndianNumber(response.viewdata['new_spareable']));
                $("#used_spareable").val(formatToIndianNumber(response.viewdata['used_spareable']));
                $("#remarks").val(response.viewdata['remarks']);
                $("#description").val(response.viewdata['description']);
            }
        });
    }


    $(document).ready(function () {
        // Filter Stock Data on Button Click
        $("#filterButton").click(function () {
            $.ajax({
                type: "GET",
                url: "{{ route('admin.stock_filter') }}",
                data: $("#filterForm").serialize(),
                success: function (response) {
                    let tableBody = $("#stockTable");
                    tableBody.empty();

                    if (response.data && response.data.length > 0) {
                        $.each(response.data, function (index, stockdata) {
                            // Edit button is now available for all records
                            let editButton = `
                                <a class="badge bg-success mr-2" href="/OIMS/admin/edit_stock/${stockdata.id}">
                                    <i class="ri-pencil-line mr-0"></i>
                                </a>`;

                            tableBody.append(`
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${stockdata.location_name} (${stockdata.location_id})</td>
                                    <td>${stockdata.EDP_Code}</td>
                                    <td>${stockdata.category}</td>
                                    <td>${stockdata.section}</td>
                                    <td>${stockdata.description}</td>
                                    <td>${formatToIndianNumber(stockdata.new_spareable ?? 0)}
                                    <td>${formatToIndianNumber(stockdata.used_spareable ?? 0)}
                                    <td>${formatToIndianNumber(stockdata.qty ?? 0)}
                                    <span class="text-muted small">${stockdata.measurement}</span>
                                        </td>
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
                error: function (xhr, status, error) {
                    console.error("Error fetching data:", error);
                }
            });
        });
    });

    function deleteStockdata(id) {
        $("#delete_id").val(id);
    }

    $(document).ready(function () {
        $("#downloadPdf").click(function (e) {
            e.preventDefault();

            let baseUrl = "{{ route('admin.stock_list_pdf') }}";
            let formData = $("#filterForm").serializeArray();
           console.log('Form Data:', formData);

            let filteredParams = formData
                    .filter(item => item.value.trim() !== "")
                    .map(item => `${encodeURIComponent(item.name)}=${encodeURIComponent(item.value)}`)
                    .join("&");

            console.log('filteredParams'+filteredParams);

            let finalUrl = filteredParams ? `${baseUrl}?${filteredParams}` : baseUrl;
            window.open(finalUrl, '_blank');
        });
    });

    $(document).ready(function() {
        $("#downloadExcel").click(function(e) {
            e.preventDefault();

            let baseUrl = "{{ route('admin.stock_list_excel') }}";
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
        // Automatically fade out alerts after 3 seconds
        setTimeout(function () {
            $(".alert").fadeOut("slow");
        }, 3000);
    });

    $(document).ready(function () {
            $('#edp_code').select2({
                theme: 'bootstrap4', // match Bootstrap styling
                placeholder: "Select EDP Code...",
                allowClear: true,
                width: '100%' // ensures it matches .form-control width
            });
    });

</script>

@endsection
