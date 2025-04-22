
@extends('layouts.frontend.admin_layout')
@section('page-content')



    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
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
                            <h4 class="card-title">EDP List</h4>
                        </div>

                        <div class="xl-offset-9 md-offset-4  sm-offset-4">
                            <a href="{{ route('admin.edp.create') }}" class="btn btn-primary">Add EDP</a>
                            <a href="{{route('admin.import_edp')}}" class="btn btn-primary "><i
                                    class="las la-plus mr-3"></i> EDP Bulk Upload </a>
                        </div>

                    </div>

                    <div class="col-lg-12">
                        <div class="table-responsive rounded mb-3">
                            <table class="table table-bordered tbl-server-info" id="edpTable">
                                <thead class="bg-white text-uppercase">
                                    <tr>
                                        <th>#</th>
                                        <th>EDP Code</th>
                                        <th>Category</th>
                                        <th>Material Group</th>
                                        <th>Description</th>
                                        <th>Section</th>
                                        <th>Measurement</th>
                                        <th>Action</th>
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
                    <form action="{{route('admin.edp.destroy')}}" method="post">
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

        <script>
            function deleteCategory(id) {
                $("#delete_id").val(id);
            }

            setTimeout(function () {
                $("#successMessage").fadeOut('slow');
            }, 3000); // 3 seconds
        </script>
        <script type="text/javascript">
            $(function () {
                $('#edpTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('admin.edp.index') }}",
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false},
                        {data: 'edp_code', name: 'edp_code'},
                        {data: 'category', name: 'category'},
                        {data: 'material_group', name: 'material_group'},
                        {data: 'description', name: 'description'},
                        {data: 'section', name: 'section'},
                        {data: 'measurement', name: 'measurement'},
                        {
                    data: 'id', 
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        // Base URL (adjust if needed)                           
                        var baseUrl = window.location.origin;
                        
                        // Edit button           
                        var editBtn = '<a class="badge bg-success mr-2" href="' + baseUrl + '/OIMS/admin/edp/' + data + '/edit" data-toggle="tooltip" title="Edit"><i class="ri-pencil-line"></i></a>';
                        
                        // Delete button
                        var deleteBtn = '<a class="badge bg-warning mr-2" data-toggle="modal" data-target="#DeleteModal" onclick="deleteCategory(' + data + ')" title="Delete"><i class="ri-delete-bin-line"></i></a>';
                        
                        return editBtn + '' + deleteBtn;
                    }
                }
                    ]
                });
            });
        </script>

@endsection
