@extends('layouts.frontend.admin_layout')
@section('page-content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <style>
                    td.wrap-message,
                    th.wrap-message {
                        white-space: normal !important;
                        word-break: break-word;
                        max-width: 300px;
                        /* or whatever width makes sense */
                    }
                </style>
                <div class="col-lg-12">
                    <div class="row mb-4">
                        <div class="col-12">

                            {{ Breadcrumbs::render('Logs_Table') }}
                        </div>
                    </div>

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
                                                {{-- <option value="Stock">Stock Logs</option> --}}
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
                                            <a href="{{ route('get.logs') }}" class="btn btn-secondary ml-2">Reset</a>
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
                    <style>
                        .wrap-message {
                            white-space: normal !important;
                            word-wrap: break-word;
                            max-width: 200px;
                            /* Adjust as needed */
                        }

                        .loading-spinner {
                            animation: spin 1s linear infinite;
                        }

                        @keyframes spin {
                            0% {
                                transform: rotate(0deg);
                            }

                            100% {
                                transform: rotate(360deg);
                            }
                        }
                    </style>

                    <div id="loadingMessage" class="text-center my-3" style="display: none;">
                        <img src="{{ asset('resources/images/login/gare.svg') }}" alt="Loading..."
                            class="loading-spinner mb-2" style="width: 40px;">
                        <div class="text-primary">Loading logs, please wait...</div>
                    </div>



                    <div class="table-responsive rounded mb-3">
                        <div id="customFilters" class="row mb-3"></div>

                        <h4 id="tableTitle" class="mt-3"></h4>

                        <div id="loadingMessage" style="display: none;" class="text-info mb-2">Loading logs...</div>

                        <table id="logsTable" class="table ">
                            <thead>
                                <tr id="logsTableHead" class="text-center"></tr>
                            </thead>
                            <tbody id="logsTableBody"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {

            function formatIndianNumber(x) {
                if (x == null) return 0;
                x = x.toString();
                var afterPoint = '';
                if (x.indexOf('.') > 0)
                    afterPoint = x.substring(x.indexOf('.'), x.length);
                x = Math.floor(x);
                x = x.toString();
                var lastThree = x.substring(x.length - 3);
                var otherNumbers = x.substring(0, x.length - 3);
                if (otherNumbers !== '')
                    lastThree = ',' + lastThree;
                return otherNumbers.replace(/\B(?=(\d{2})+(?!\d))/g, ",") + lastThree + afterPoint;
            }


            const tableHeaders = {
                'Rigs': ['ID', 'Location ID', 'Rig Name', 'Creator Type', 'Message', 'Date'],
                'Users': ['ID', 'User Name', 'Email', 'Creator Type', 'Message', 'Date'],
                'EDP': ['ID', 'EDP Code', 'Category', 'Description', 'Section', 'Creator Type', 'Message', 'Date'],
                'Request': ['ID', 'Request ID','EDP Code', 'Available Qty', 'Requested Qty',  'Message', 'Date']
            };

            const fieldMappings = {
                'Rigs': ['id', 'location_id', 'name', 'creater_type', 'message', 'created_at'],
                'Users': ['id', 'user_name', 'email', 'creater_type', 'message', 'created_at'],
                'EDP': ['id', 'edp_code', 'category', 'description', 'section', 'creater_type', 'message', 'created_at'],
                'Request': ['id', 'RID', 'edp_code','available_qty', 'requested_qty',  'message', 'created_at']
            };

            const filterableColumns = {
                'Rigs': ['location_combined'],
                'Users': ['user_name'],
                'EDP': ['edp_code', 'category'],
                'Request': ['RID']
            };

            const filterLabels = {
                'location_id': 'Location',
                'name': 'Rig Name',
                'user_name': 'User Name',
                'category': 'Category',
                'section': 'Section',
                'edp_code': 'EDP Code',
                'RID': 'Request ID'
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
                        console.log
                        const fields = fieldMappings[logType];
                        $('#tableTitle').text(`${logType} Logs `);

                        const thead = $("#logsTableHead");
                        const tbody = $("#logsTableBody");

                        if ($.fn.DataTable.isDataTable('#logsTable')) {
                            $('#logsTable').DataTable().destroy();
                        }

                        thead.empty();
                        tbody.empty();

                        tableHeaders[logType].forEach((header, index) => {
                            const wrapClass = (header === 'Message' || header === 'Description') ? 'wrap-message' : '';
                            const displayHeader = (index === 0) ? 'Sr No' : header;
                            thead.append(`<th class="${wrapClass}">${displayHeader}</th>`);
                        });

                        data.forEach((log, index) => {
                            const row = $("<tr></tr>");
                            fields.forEach((field, fieldIndex) => {
                                let value;
                                if (fieldIndex === 0) {
                                    value = index + 1; // Serial number starting from 1
                                } else {

                                    value = log[field] || 'N/A';

                                    if (field === 'created_at' && value !== 'N/A') {
                                        const d = new Date(value);
                                        // Convert to IST (Indian Standard Time)
                                        const istOffset = 5.5 * 60; // IST is UTC+5:30
                                        const localTime = new Date(d.getTime() + (istOffset * 60 * 1000));

                                        const day = ("0" + localTime.getUTCDate()).slice(-2);
                                        const month = ("0" + (localTime.getUTCMonth() + 1)).slice(-2);
                                        const year = localTime.getUTCFullYear();

                                        let hours = localTime.getUTCHours();
                                        const minutes = ("0" + localTime.getUTCMinutes()).slice(-2);
                                        const seconds = ("0" + localTime.getUTCSeconds()).slice(-2);
                                        const ampm = hours >= 12 ? 'PM' : 'AM';

                                        hours = hours % 12;
                                        hours = hours ? hours : 12; // Convert '0' to '12'
                                        hours = ("0" + hours).slice(-2);

                                        value = `${day}-${month}-${year} (${hours}:${minutes}:${seconds} ${ampm})`;
                                    }

                                }
                                const wrapClass = (field === 'message') ? 'wrap-message' : '';
                                row.append(`<td class="${wrapClass}">${value}</td>`);
                            });
                            tbody.append(row);
                        });


                        const edpCategoryMap = {};
                        data.forEach(item => {
                            if (!edpCategoryMap[item.category]) {
                                edpCategoryMap[item.category] = [];
                            }
                            edpCategoryMap[item.category].push(item.edp_code);
                        });

                        let edpCodeSelectIndex;
                        $("#customFilters").empty();
                        const filterCols = filterableColumns[logType] || [];

                        if (logType === 'Rigs') {
                            const combinedMap = {};

                            data.forEach(item => {
                                if (!combinedMap[item.location_id]) {
                                    combinedMap[item.location_id] = [];
                                }

                                if (item.name && !combinedMap[item.location_id].includes(item.name)) {
                                    combinedMap[item.location_id].unshift(item.name);
                                    if (combinedMap[item.location_id].length > 2) {
                                        combinedMap[item.location_id].pop();
                                    }
                                }
                            });

                            const colIndexLoc = fields.indexOf('location_id');
                            const colIndexRig = fields.indexOf('name');

                            let selectHTML = `<div class="col-md-3">
                                                                        <label class="small">Rig Name</label>
                                                                        <select class="form-control form-control-sm column-filter" data-col-location="${colIndexLoc}" data-col-rig="${colIndexRig}">
                                                                            <option value="">All</option>`;


                            Object.entries(combinedMap).forEach(([locId, rigNames]) => {
                                const label = `${rigNames.join(',')}&nbsp;&nbsp;(${locId})`;
                                selectHTML += `<option value="${locId}">${label}</option>`;
                                console.warn(label);
                            });


                            selectHTML += `</select></div>`;
                            $("#customFilters").append(selectHTML);
                        } else {
                            filterCols.forEach(field => {
                                const colIndex = fields.indexOf(field);
                                if (colIndex === -1) return;

                                const uniqueVals = [...new Set(data.map(item => item[field]))].filter(v => v !== null);
                                const label = filterLabels[field] || field;

                                let selectHTML = `<div class="col-md-3">
                                                                            <label class="small">${label}</label>
                                                                            <select class="form-control form-control-sm column-filter${(field === 'edp_code') ? ' select2-filter' : ''}" data-col="${colIndex}" data-field="${field}">
                                                                                <option value="">All</option>`;

                                uniqueVals.forEach(val => {
                                    selectHTML += `<option value="${val}">${val}</option>`;
                                });

                                selectHTML += `</select></div>`;
                                $("#customFilters").append(selectHTML);

                                if (field === 'edp_code') edpCodeSelectIndex = colIndex;
                            });
                        }

                        // Add select2 for Request ID filter
                        const requestIdSelect = $('select[data-field="RID"]');
                        if (requestIdSelect.length) {
                            const uniqueRequestIds = [...new Set(data.map(item => item.RID))];
                            requestIdSelect.empty().append('<option value="">All</option>');
                            uniqueRequestIds.forEach(id => {
                                requestIdSelect.append(`<option value="${id}">${id}</option>`);
                            });
                            requestIdSelect.select2({
                                width: '100%',
                                placeholder: "Select Request ID",
                                allowClear: true
                            });
                        }

                        $("#customFilters").append(`
                                                                    <div class="col-1 d-flex align-items-end">
                                                                        <button type="button" id="resetFilters" class="btn btn-secondary btn-sm" style="height: 33.22222px; width: 33.22222px;">
                                                                            <i class="fas fa-sync-alt"></i>
                                                                        </button>
                                                                    </div>
                                                                `);

                        const datatable = $('#logsTable').DataTable({
                            paging: true,
                            searching: true,
                            ordering: true,
                            responsive: true,
                            destroy: true,
                            order: [[fields.length - 1, 'desc']]
                        });

                        $('#customFilters').on('change', '.column-filter', function () {
                            const val = $(this).val();
                            const colIndex = $(this).data('col');
                            const colLoc = $(this).data('col-location');
                            const colRig = $(this).data('col-rig');

                            if (typeof colLoc !== 'undefined' && typeof colRig !== 'undefined') {
                                datatable.column(colLoc).search(val).draw();
                                datatable.column(colRig).search('').draw();
                            } else {
                                datatable.column(colIndex).search(val).draw();
                            }
                        });

                        $('#customFilters').off('click', '#resetFilters').on('click', '#resetFilters', function () {
                            $('.column-filter').each(function () {
                                $(this).val('').trigger('change');
                                const colIndex = $(this).data('col');
                                const colLoc = $(this).data('col-location');
                                const colRig = $(this).data('col-rig');

                                if (typeof colLoc !== 'undefined' && typeof colRig !== 'undefined') {
                                    datatable.column(colLoc).search('').draw();
                                    datatable.column(colRig).search('').draw();
                                } else {
                                    datatable.column(colIndex).search('').draw();
                                }
                            });
                        });

                        $('.select2-filter').select2({
                            width: '100%',
                            placeholder: "Select",
                            allowClear: true
                        });

                        $('#customFilters').on('change', 'select[data-field="edp_code"]', function () {
                            const edpVal = $(this).val();
                            const $catSelect = $('select[data-field="category"]');
                            if (edpVal) {
                                $catSelect.prop('disabled', true);
                            } else {
                                $catSelect.prop('disabled', false);
                            }
                        });

                        $('#customFilters').on('change', 'select[data-field="category"]', function () {
                            const selectedCategory = $(this).val();
                            const $edpSelect = $('select[data-field="edp_code"]');
                            let filteredEDPs = selectedCategory ? edpCategoryMap[selectedCategory] || [] : [...new Set(data.map(item => item.edp_code))];

                            let edpOptions = `<option value="">All</option>`;
                            [...new Set(filteredEDPs)].forEach(code => {
                                edpOptions += `<option value="${code}">${code}</option>`;
                            });

                            $edpSelect.html(edpOptions).val('').trigger('change.select2');
                            if (datatable) {
                                datatable.column(edpCodeSelectIndex).search('').draw();
                            }
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