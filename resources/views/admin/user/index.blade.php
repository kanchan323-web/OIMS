@extends('layouts.frontend.admin_layout')
@section('page-content')

    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
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
                                <table class="data-tables table mb-0 tbl-server-info">
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
                                                        <input type="checkbox" class="checkbox-input"
                                                            id="checkbox{{ $user->id }}">
                                                        <label for="checkbox{{ $user->id }}" class="mb-0"></label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div>
                                                            {{ $loop->iteration }}
                                                        </div>
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
                                                        data-target="#userViewModal" onclick="viewtoclick({{$user}})">
                                                        <i class="ri-eye-line mr-0"></i>
                                                     </a>

                                                        <a class="badge bg-success mr-2" data-toggle="tooltip" title="Edit"
                                                            href="{{ route('admin.edit', $user->id) }}">
                                                            <i class="ri-pencil-line mr-0"></i>
                                                        </a>
                                                        <form action="{{ route('admin.destroy', $user->id) }}" method="POST"
                                                            class="d-inline-block">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="badge bg-warning mr-2 border-0"
                                                                onclick="return confirm('Are you sure?')" data-toggle="tooltip"
                                                                title="Delete">
                                                                <i class="ri-delete-bin-line mr-0"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{ $users->links() }} <!-- Pagination -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Button trigger modal -->


  <!-- Modal -->
  <div class="modal fade" id="userViewModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">View User </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="card-body">
                    <div class="form-row">
                        <div class="col-md-6 mb-3">
                            <label for="user_name">User Name</label>
                            <input type="text" class="form-control" id="UserName" value="" readonly>

                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="Email" value="" readonly>

                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="cpf_no">CPF No</label>
                            <input type="text" class="form-control" id="CpfNo"  readonly>

                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="user_status">User Status</label>
                            <input type="text" class="form-control" id="UserStatus"  readonly>

                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="user_type">User Type</label>
                            <input type="text" class="form-control" id="UserType" value="" readonly>

                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="rig_id">Rigs Name</label>
                            <input type="text" class="form-control" id="RigName" value="" readonly>

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
    <script>

        $(document).ready(function () {
            // Automatically fade out alerts after 3 seconds
            setTimeout(function () {
                $(".alert").fadeOut("slow");
            }, 3000);
        });

        function viewtoclick(data) {
                // Get DOM elements
                const rigNameInput = document.getElementById("RigName");
                const rigNameLabel = document.querySelector('label[for="rig_id"]');
                const rigname = {!! json_encode($rigUsers) !!}; // Assuming this is a PHP-to-JS variable

                // Admin: Hide RigName | Non-admin: Show RigName (read-only)
                if (data.user_type === "admin") {
                    rigNameInput.style.display = "none";
                    rigNameLabel.style.display = "none";
                } else {
                    rigNameInput.style.display = "block"; // or "" to reset to default
                    rigNameLabel.style.display = "block"; // or "" to reset to default
                    rigNameInput.value = data.rig_name || ""; // Set value if not admin
                }

                // Set other field values
                document.getElementById("UserName").value = data.user_name;
                document.getElementById("Email").value = data.email;
                document.getElementById("CpfNo").value = data.cpf_no;
                document.getElementById("UserStatus").value = data.user_type;
                document.getElementById("UserType").value = (data.user_status == 1) ? "Active" : "Inactive";
            }
    </script>



@endsection
