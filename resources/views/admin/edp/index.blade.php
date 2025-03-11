@extends('layouts.frontend.admin_layout')
@section('page-content')

<div class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">EDP List</h4>
                        </div>
                        <a href="{{ route('admin.edp.create') }}" class="btn btn-primary">Add New</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>EDP Code</th>
                                        <th>Category Name</th>
                                        <th>Description</th>
                                        <th>Section</th>
                                        <th>Measurement</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($edp_list as $index => $list)

                                    <tr>
                                        <td >{{$index + 1}}</td >
                                        <td >{{$list->edp_code}}</td >
                                        <td >{{$list->category}}</td >
                                        <td >{{$list->description}}</td >
                                        <td >{{$list->section}}</td >
                                        <td >{{$list->measurement}}</td >

                                        <td >
                                        <a class="badge bg-success mr-2" data-toggle="tooltip"
                                                href="{{url('/admin/edp/'.$list->id.'/edit')}}"
                                                data-placement="top" title="" data-original-title="Edit"><i
                                                    class="ri-pencil-line mr-0"></i>
                                            </a>
                                            <a class="badge bg-warning mr-2" data-toggle="modal" data-target="#DeleteModal"  onclick="deleteCategory({{$list->id}})"  data-placement="top" title="" data-original-title="Delete"
                                            href="#"><i class="ri-delete-bin-line mr-0"></i></a>

                                        </td >
                                      
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
<div class="modal fade" id="DeleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <form action="{{route('admin.edp.destroy')}}"  method="post">
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
                            <input type="hidden" name="delete_id"  id="delete_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class=" btn btn-primary " data-dismiss="modal">Cancle</button>
                    <button type="submit" class=" btn btn-secondary " >Delete</button>
                </div>
      </form>
    </div>
  </div>
</div>

<script>
function deleteCategory(id) {

    $("#delete_id").val(id);    
}
</script>

@endsection