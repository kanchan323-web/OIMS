@extends('layouts.frontend.admin_layout')
@section('page-content')


<div class="content-page">
    <div class="container-fluid">
        <div class="row">

            @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">List Category</h4>
                        </div>
                        <a href="{{ route('admin.category.create') }}" class="btn btn-primary">Add New</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Category Name</th>
                                        <th>Date</th>
                                        <!-- <th>Created At</th> -->
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categorty_list as $index => $list)
                                    <tr>
                                        <td>{{$index +1 }}</td>
                                        <td>{{$list->category_name }}</td>
                                        <td>{{ $list->created_at->format('d-m-Y') }}</td>
                                        <td>

                                            <a class="badge bg-success mr-2" data-toggle="tooltip"
                                                href="{{url('/admin/category/'.$list->id.'/edit')}}"
                                                data-placement="top" title="" data-original-title="Edit"><i
                                                    class="ri-pencil-line mr-0"></i>
                                            </a>
                                           
                                            <a class="badge bg-warning mr-2" data-toggle="modal" data-target="#DeleteModal"  onclick="deleteCategory({{$list->id}})"  data-placement="top" title="" data-original-title="Delete"
                                        href="#"><i class="ri-delete-bin-line mr-0"></i></a>

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
<div class="modal fade" id="DeleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <form action="{{route('admin.category.destroy')}}"  method="post">
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