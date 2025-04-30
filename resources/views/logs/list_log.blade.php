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
                                            <label for="logs_type">Logs Type</label>
                                            <select class="form-control" name="logs_type" id="logs_type">
                                                <option disabled selected>Select Logs Type...</option>
                                                <option value="Rigs">Rigs Logs</option>
                                                <option value="Users">Users Logs</option>
                                                <option value="EDP">EDP Logs</option>
                                                <option value="Stock">Stock Logs</option>
                                                <option value="Request">Request Stock Logs</option>
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
                                            <button type="submit" class="btn btn-primary mr-2"
                                                id="filterButton">Search</button>
                                            <a href="{{ route('get.logs') }}"
                                                class="btn btn-secondary ml-2">Reset</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-3">
                  
                        </div>
                    </div>
                </div>

                <div class="col-lg-12 mt-5">
                    <div class="mb-2">
                        <h5 id="tableTitle" class="text-uppercase font-weight-bold text-dark"></h5>
                    </div>
                    <div id="loadingMessage" class="text-center text-info mb-2" style="display:none;">
                        Loading logs, please wait...
                    </div>
                    <div class="table-responsive rounded mb-3">
                        <div id="customFilters" class="row mb-3"></div>

                            <h4 id="tableTitle" class="mt-3"></h4>

                            <div id="loadingMessage" style="display: none;" class="text-info mb-2">Loading logs...</div>

                            <table id="logsTable" class="table ">
                                <thead><tr id="logsTableHead"></tr></thead>
                                <tbody id="logsTableBody"></tbody>
                            </table>
                    </div>
                </div>
                


            </div>
        </div>
</div>

<script>
    $(document).ready(function () {
        const tableHeaders = {
            'Rigs': ['ID', 'Location ID', 'Rig Name', 'Creator Type', 'Message', 'Date'],
            'Users': ['ID', 'User Name', 'Email', 'Creator Type', 'Message', 'Date'],
            'EDP': ['ID', 'EDP Code', 'Category', 'Description', 'Section', 'Creator Type', 'Message', 'Date'],
            'Stock': ['ID', 'EDP Code', 'Category', 'Section', 'QTY', 'Initial QTY', 'Measurement', 'New ', 'Used ', 'Message', 'Action', 'Date'],
            'Request': ['ID', 'RID', 'Available Qty', 'Requested Qty', 'Stock ID', 'Message', 'Date']
        };
    
        const fieldMappings = {
            'Rigs': ['id', 'location_id', 'name', 'creater_type', 'message', 'created_at'],
            'Users': ['id', 'user_name', 'email', 'creater_type', 'message', 'created_at'],
            'EDP': ['id', 'edp_code', 'category', 'description', 'section', 'creater_type', 'message', 'created_at'],
            'Stock': ['id', 'edp_code', 'category', 'section', 'qty', 'initial_qty', 'measurement', 'new_spareable', 'used_spareable', 'message', 'action', 'created_at'],
            'Request': ['id', 'RID', 'available_qty', 'requested_qty', 'stock_id', 'message', 'created_at']
        };
    
        const filterableColumns = {
            'Rigs': ['location_id', 'name'],
            'Users': ['user_name'],
            'EDP': ['category'],
            'Stock': ['edp_code','category','section'],
            'Request': ['RID']
        };
    
        $('#logs_type').on('change', function () {
            $('#filterForm').submit();
        });
    
        $("#filterForm").on("submit", function (e) {
            e.preventDefault();
    
            $("#loadingMessage").show();
    
            $.ajax({
                url: "{{ route('get.logs.filter') }}",
                type: 'GET',
                data: $(this).serialize(),
                dataType: 'json',
                success: function (response) {
                    const logType = response.type;
                    const data = response.data;
                    const fields = fieldMappings[logType];
                    $('#tableTitle').text(`${logType} Logs Table`);
    
                    const thead = $("#logsTableHead");
                    const tbody = $("#logsTableBody");
    
                    if ($.fn.DataTable.isDataTable('#logsTable')) {
                        $('#logsTable').DataTable().destroy();
                    }
    
                    thead.empty();
                    tbody.empty();
    
                    // Build header
                    tableHeaders[logType].forEach(header => {
                        thead.append(`<th>${header}</th>`);
                    });
    
                    // Build rows
                    data.forEach(log => {
                        const row = $("<tr></tr>");
                        fields.forEach(field => {
                            let value = log[field] || 'N/A';
                            if (field === 'created_at' && value !== 'N/A') {
                                const d = new Date(value);
                                value = `${("0" + d.getDate()).slice(-2)}-${("0" + (d.getMonth() + 1)).slice(-2)}-${d.getFullYear()}`;
                            }
                            row.append(`<td>${value}</td>`);
                        });
                        tbody.append(row);
                    });
    
                    // Build filter dropdowns above header
                    $("#customFilters").empty();
                    const filterCols = filterableColumns[logType] || [];
                    filterCols.forEach(field => {
                        const colIndex = fields.indexOf(field);
                        if (colIndex === -1) return;
    
                        const uniqueVals = [...new Set(data.map(item => item[field]))].filter(v => v !== null);
    
                        let filterHTML = `<div class="col-md-3">
                            <label class="small">${field.replace('_', ' ').toUpperCase()}</label>
                            <select class="form-control form-control-sm column-filter" data-col="${colIndex}">
                                <option value="">All</option>`;
                        uniqueVals.forEach(val => {
                            filterHTML += `<option value="${val}">${val}</option>`;
                        });
                        filterHTML += `</select></div>`;
    
                        $("#customFilters").append(filterHTML);
                    });
    
                    const datatable = $('#logsTable').DataTable({
                        paging: true,
                        searching: true,
                        ordering: true,
                        responsive: true,
                        destroy: true
                    });
    
                    // Apply filter
                    $('#customFilters').on('change', '.column-filter', function () {
                        const colIndex = $(this).data('col');
                        const val = $(this).val();
                        datatable.column(colIndex).search(val).draw();
                    });
    
                    $("#loadingMessage").hide();
                },
                error: function (xhr) {
                    $("#logsTableBody").html(`
                        <tr>
                            <td colspan="100%" class="text-center text-danger">
                                Error loading data.
                            </td>
                        </tr>
                    `);
                    $("#loadingMessage").hide();
                    console.error("AJAX Error:", xhr.responseText);
                }
            });
        });
    });
    </script>
    
    
    
    
    

  


@endsection
