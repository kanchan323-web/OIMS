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
                                            <a href="{{ route('user.get.logs') }}"
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

                <div class="col-lg-12">
                    <div class="table-responsive rounded mb-3">
                        <table class="data-tables table mb-0 tbl-server-info">
                            <thead class="bg-white text-uppercase" id="logsTableHead">
                                <tr class="ligth ligth-data">
                                 
                                </tr>
                            </thead>
                            <tbody class="ligth-body" id="logsTableBody">
                               
                            </tbody>
                        </table>
                    </div>
                </div>


            </div>
        </div>
</div>

<script>
    $(document).ready(function() {
        // Define table headers for each log type
        const tableHeaders = {
            'Rigs': ['ID', 'Location ID', 'Rig Name', 'Creator Type', 'Message', 'Date'],
            'Users': ['ID', 'User Name', 'Email', 'Creator Type', 'Message', 'Date'],
            'EDP': ['ID', 'EDP Code', 'Category', 'Description', 'Section','Creator Type','Message', 'Date'],
            'Stock': ['ID', 'EDP Code', 'Category', 'Section','QTY','Initial QTY','Measurement','New Spareable','Used Spareable', 'Message','Action', 'Date'],
            'Request': ['ID', 'Request Field 1', 'Request Field 2', 'Creator Type', 'Message', 'Date']

        };

        // Define field mappings for each log type
        const fieldMappings = {
            'Rigs': ['id', 'location_id', 'name', 'creater_type', 'message', 'created_at'],
            'Users': ['id', 'user_name', 'email', 'creater_type', 'message', 'created_at'],
            'EDP': ['id', 'edp_code', 'category', 'description', 'section','creater_type','message', 'created_at'],
            'Stock': ['id', 'edp_code', 'category','section','qty','initial_qty','measurement','new_spareable', 'used_spareable','message','action', 'created_at'],
            'Request': ['id', 'RID', 'available_qty', 'requested_qty','stock_id',,,,, 'message', 'created_at']
       
        };

        $("#filterForm").on("submit", function(e) {
            e.preventDefault();
            
            $.ajax({
                url: "{{ route('user.get.logs.filter') }}",
                type: 'GET',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
             
                    const thead = $("#logsTableHead");
                    const tbody = $("#logsTableBody");
                    
                    // Clear existing data
                    thead.empty();
                    tbody.empty();

                    if(response.data && response.data.length > 0) {
                        const logType = response.type;
                        
                        // Set table headers
                        thead.append('<tr></tr>');
                        tableHeaders[logType].forEach(header => {
                            thead.find('tr').append(`<th>${header}</th>`);
                        });

                        // Add table rows
                        response.data.forEach(log => {
                            const row = $('<tr></tr>');
                            fieldMappings[logType].forEach(field => {
                                const value = field === 'created_at' 
                                    ? (log[field] ? new Date(log[field]).toLocaleString() : 'N/A')
                                    : (log[field] || 'N/A');
                                row.append(`<td>${value}</td>`);
                            });
                            tbody.append(row);
                        });
                    } else {
                        tbody.html(`
                            <tr>
                                <td colspan="${Object.keys(tableHeaders).length}" class="text-center text-muted">
                                    No logs found matching your criteria
                                </td>
                            </tr>
                        `);
                    }
                },
                error: function(xhr) {
                    $("#logsTable tbody").html(`
                        <tr>
                            <td colspan="6" class="text-center text-danger">
                                Error loading data. Please try again.
                            </td>
                        </tr>
                    `);
                    console.error("AJAX Error:", xhr.responseText);
                }
            });
        });
    });
</script>
  


@endsection
