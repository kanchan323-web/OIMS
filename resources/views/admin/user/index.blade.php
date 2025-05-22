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
                            <table id="usersTable" class="data-tables table mb-0 tbl-server-info">
                                <thead class="bg-white text-uppercase">
                                    <tr class="ligth ligth-data">
                                        <th>
                                            <div class="checkbox d-inline-block">
                                                <input type="checkbox" class="checkbox-input" id="checkbox1">
                                                <label for="checkbox1" class="mb-0"></label>
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
                                <tbody class="ligth-body">
                                    @foreach($users as $key => $user)
                                    <tr>
                                        <td>
                                            <div class="checkbox d-inline-block">
                                                <input type="checkbox" class="checkbox-input" id="checkbox{{ $user->id }}">
                                                <label for="checkbox{{ $user->id }}" class="mb-0"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div>{{ $loop->iteration }}</div>
                                            </div>
                                        </td>
                                        <td>{{ $user->user_name }}</td>
                                        <td>{{ $user->rig_name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->cpf_no }}</td>
                                        <td>{{ ucfirst($user->user_type) }}</td>
                                        <td>{{ $user->user_status ? 'Active' : 'Inactive' }}</td>
                                        <td>
                                            <div class="d-flex align-items-center list-action">
                                                <a class="badge badge-info mr-2" data-toggle="modal" title="View"
                                                    data-target="#userViewModal" onclick="viewtoclick({{ json_encode($user) }})">
                                                    <i class="ri-eye-line mr-0"></i>
                                                </a>

                                                <a class="badge bg-success mr-2" data-toggle="tooltip" title="Edit"
                                                    href="{{ route('admin.edit', $user->id) }}">
                                                    <i class="ri-pencil-line mr-0"></i>
                                                </a>
                                                <a href="javascript:void(0);"
                                                    class="badge bg-warning mr-2 border-0 delete-btn"
                                                    data-toggle="tooltip" title="Delete" data-id="{{ $user->id }}"
                                                    data-action="{{ route('admin.destroy', $user->id) }}">
                                                    <i class="ri-delete-bin-line mr-0"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- View User Modal (Inline) -->
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
        // Initialize DataTables on your table with theme-compatible pagination
        $('#usersTable').DataTable({
            // You can configure options here, e.g., paging, lengthChange, etc.
            "lengthMenu": [5, 10, 25, 50],
            "pageLength": 10,
            "order": [], // no initial order
            "language": {
                "paginate": {
                    "previous": "<i class='ri-arrow-left-s-line'></i>",
                    "next": "<i class='ri-arrow-right-s-line'></i>"
                }
            },
            "dom": '<"top"f>rt<"bottom"lp><"clear">'
        });

        // Auto fade alert messages
        setTimeout(function () {
            $(".alert").fadeOut("slow");
        }, 3000);

        // Delete button handler
        $('.delete-btn').on('click', function () {
            const userId = $(this).data('id');
            const actionUrl = $(this).data('action');

            $('#deleteForm').attr('action', actionUrl);
            $('#deleteConfirmationModal').modal('show');
        });
    });

    // View modal fill function
    function viewtoclick(data) {
        $('#UserName').val(data.user_name);
        $('#Email').val(data.email);
        $('#CpfNo').val(data.cpf_no);
        $('#UserStatus').val(data.user_status ? 'Active' : 'Inactive');
        $('#UserType').val(data.user_type.charAt(0).toUpperCase() + data.user_type.slice(1));
        $('#RigName').val(data.rig_name);
    }
</script>

@endsection
