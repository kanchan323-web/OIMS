@extends('layouts.frontend.admin_layout')
@section('page-content')
<div class="content-page">
    <div class="container-fluid add-form-list">

            {{-- Success Message --}}
            @if (Session::has('success'))
            <div class="alert bg-success text-white alert-dismissible fade show" role="alert">
                <strong>Success:</strong> {{ Session::get('success') }}
                <button type="button" class="close close-dark" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif

            {{-- Error Message --}}
            @if (Session::has('error'))
            <div class="alert bg-danger text-white alert-dismissible fade show" role="alert">
                <strong>Error:</strong> {{ Session::get('error') }}
                <button type="button" class="close close-dark" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif

            <div class="row">
                <div class="col-sm-12">
                    {{Breadcrumbs::render('section_list')}}

                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Section List</h4>
                            </div>
                            <a href="{{ route('admin.section.create') }}" class="btn btn-primary">Add Section</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive rounded mb-3">
                                <table class="data-tables table mb-0 tbl-server-info">
                                    <thead class="bg-white text-uppercase">
                                        <tr class="ligth ligth-data">
                                            <th>#</th>
                                            <th>Section Name</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="ligth-body">
                                        @foreach ($section_list as $section)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $section->section_name }}</td>
                                            <td>{{ $section->updated_at->format('d-m-Y')}}</td>
                                            <td>
                                                <div class="d-flex align-items-center list-action">
                                                    <a class="badge bg-success mr-2" data-toggle="tooltip" title="Section Edit" href="{{ route('admin.section.edit', $section->id) }}">
                                                        <i class="ri-pencil-line mr-0"></i>
                                                    </a>

                                                    <a class="badge bg-warning mr-2" data-toggle="modal" data-target="#DeleteModal"
                                                        onclick="deleteSection({{$section->id}})" data-placement="top" title="Section Delete"
                                                        data-original-title="Delete" href="#"><i
                                                        class="ri-delete-bin-line mr-0"></i>
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

<div class="modal fade" id="DeleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <form action="{{route('admin.section.destroy')}}"  method="post">
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
     $(document).ready(function() {
            $('[data-toggle="modal"]').tooltip(); // this enables tooltip on modal trigger
    });

    function deleteSection(id) {
        $("#delete_id").val(id);
    }

   /* setTimeout(function () {
                    $("#successMessage").fadeOut('slow');
                }, 3000); // 3 seconds
    */
</script>



@endsection
