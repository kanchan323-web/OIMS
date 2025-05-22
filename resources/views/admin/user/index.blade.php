@extends('layouts.frontend.admin_layout')
@section('page-content')

    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    {{ Breadcrumbs::render('user_list') }}
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Users List</h4>
                            </div>
                            <a href="{{ route('admin.create') }}" class="btn btn-primary">Add User</a>
                        </div>
                        <div class="card-body">
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

                            <div class="table-responsive rounded mb-3">
                                <table id="userTable" class="table mb-0 tbl-server-info data-tables">
                                    <thead class="bg-white text-uppercase">
                                        <tr class="ligth ligth-data">
                                            <th>
                                                <div class="checkbox d-inline-block">
                                                    <input type="checkbox" class="checkbox-input" id="checkboxAll">
                                                    <label for="checkboxAll" class="mb-0"></label>
                                                </div>
                                            </th>
                                            <th>#</th>
                                            <th>User Name</th>
                                            <th>Rig Name</th>
                                            <th>Email</th>
                                            <th>CPF No</th>
                                            <th>User Type</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: View User -->
    <div class="modal fade" id="userViewModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">View User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <label for="UserName">User Name</label>
                                <input type="text" class="form-control" id="UserName" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="Email">Email</label>
                                <input type="email" class="form-control" id="Email" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="CpfNo">CPF No</label>
                                <input type="text" class="form-control" id="CpfNo" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="UserStatus">User Status</label>
                                <input type="text" class="form-control" id="UserStatus" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="UserType">User Type</label>
                                <input type="text" class="form-control" id="UserType" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="RigName">Rigs Name</label>
                                <input type="text" class="form-control" id="RigName" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Deletion</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this user? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // Auto fade alerts
            setTimeout(function () {
                $(".alert").fadeOut("slow");
            }, 3000);

            // Check and destroy if DataTable is already initialized
            if ($.fn.DataTable.isDataTable('#userTable')) {
                $('#userTable').DataTable().clear().destroy();
            }

            // Initialize DataTable
            var userTable = $('#userTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.index') }}",
                columns: [
                    {
                        data: 'id',
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row) {
                            return `<div class="checkbox d-inline-block">
                                                <input type="checkbox" class="checkbox-input" id="checkbox${data}">
                                                <label for="checkbox${data}" class="mb-0"></label>
                                            </div>`;
                        }
                    },
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'user_name', name: 'users.user_name' }, // explicitly use table.column
                    { data: 'rig_name', name: 'rig_users.name' },   // FIXED: real DB column for search/sort
                    { data: 'email', name: 'users.email' },
                    { data: 'cpf_no', name: 'users.cpf_no' },
                    {
                        data: 'user_type', name: 'users.user_type',
                        render: function (data) {
                            return data.charAt(0).toUpperCase() + data.slice(1);
                        }
                    },
                    {
                        data: 'user_status', name: 'users.user_status',
                        render: function (data) {
                            return data == 1 ? 'Active' : 'Inactive';
                        }
                    },
                    {
                        data: 'id',
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row) {
                            var viewBtn = `<a class="badge badge-info mr-2" data-toggle="modal" title="View"
                                    href="javascript:void(0);"
                                    onclick="loadUserDetails(${data})"
                                    data-target="#userViewModal">
                                    <i class="ri-eye-line mr-0"></i></a>`;

                            // Use route() in Blade to generate URL with a placeholder, then replace placeholder with actual ID
                            var editUrlTemplate = @json(route('admin.edit', ['id' => '___ID___']));
                            var editUrl = editUrlTemplate.replace('___ID___', data);

                            var deleteUrlTemplate = @json(route('admin.destroy', ['id' => '___ID___']));
                            var deleteUrl = deleteUrlTemplate.replace('___ID___', data);

                            var editBtn = `<a class="badge bg-success mr-2" data-toggle="tooltip" title="Edit" href="${editUrl}">
                                    <i class="ri-pencil-line mr-0"></i></a>`;

                            var deleteBtn = `<a href="javascript:void(0);" class="badge bg-warning mr-2 border-0 delete-btn"
                                    data-toggle="tooltip" title="Delete" data-id="${data}" data-action="${deleteUrl}">
                                    <i class="ri-delete-bin-line mr-0"></i></a>`;

                            return viewBtn + editBtn + deleteBtn;
                        }

                    }
                ],
                order: [[1, 'asc']]
            });

            // Delete button click handler
            $('#userTable tbody').off('click', '.delete-btn').on('click', '.delete-btn', function () {
                const userId = $(this).data('id');
                const actionUrl = $(this).data('action');
                $('#deleteForm').attr('action', actionUrl);
                $('#deleteConfirmationModal').modal('show');
            });
        });

        function loadUserDetails(userId) {
            var url = "{{ route('admin.show', ':id') }}".replace(':id', userId);

            $.ajax({
                url: url,
                type: 'GET',
                success: function (response) {
                    // Assuming response contains user info, map accordingly:
                    $('#UserName').val(response.user_name || '');
                    $('#Email').val(response.email || '');
                    $('#CpfNo').val(response.cpf_no || '');
                    $('#UserStatus').val(response.user_status == 1 ? "Active" : "Inactive");
                    $('#UserType').val(response.user_type ? (response.user_type.charAt(0).toUpperCase() + response.user_type.slice(1)) : '');
                    $('#RigName').val(response.rig_name || '');

                    // Show/hide RigName based on user_type
                    if (response.user_type === "admin") {
                        $('#RigName').hide();
                        $('label[for="RigName"]').hide();
                    } else {
                        $('#RigName').show();
                        $('label[for="RigName"]').show();
                    }

                    $('#userViewModal').modal('show');
                },
                error: function () {
                    alert('Failed to fetch user details.');
                }
            });
        }

    </script>

@endsection