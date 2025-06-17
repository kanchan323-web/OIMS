@extends('layouts.frontend.admin_layout')
@section('page-content')

<div class="content-page">
    <div class="container-fluid">

        {{-- Success & Error Messages --}}
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

        <div class="row">
            <div class="col-sm-12">
                {{Breadcrumbs::render('rig_user_list')}}
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Locations List</h4>
                        </div>
                        <a href="{{ route('admin.rig_users.create') }}" class="btn btn-primary">Add New</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive rounded mb-3">
                            <table class="data-tables table mb-0 tbl-server-info">
                                <thead class="bg-white text-uppercase">
                                    <tr class="ligth ligth-data">
                                        <th>No.</th>
                                        <th>Location Name</th>
                                        <th>Location ID</th>
                                        <th>Creation Date</th>
                                        <th>Operations</th>
                                    </tr>
                                </thead>
                                <tbody class="ligth-body">
                                    @foreach ($rigUsers as $user)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->location_id }}</td>
                                        <td>{{ $user->created_at->format('d-m-Y') }}</td>
                                        <td>
                                            <div class="d-flex align-items-center list-action">
                                                <a class="badge badge-info mr-2" title="View" data-toggle="modal" data-target="#exampleModal" onclick="viewrig({{ $user }})">
                                                    <i class="ri-eye-line mr-0"></i>
                                                </a>
                                                <a class="badge bg-success mr-2" data-toggle="tooltip" title="Edit" href="{{ route('admin.rig_users.edit', $user->id) }}">
                                                    <i class="ri-pencil-line mr-0"></i>
                                                </a>
                                                <a class="badge bg-warning mr-2 border-0 mr-2" title="View" data-toggle="modal" data-target="#DeleteModal" onclick="deleterig({{ $user }})" >
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
<div class="modal fade" id="DeleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this Location? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
              
                <form action="{{ route('admin.rig_users.destroy') }}" method="POST" class="d-inline-block">
                    @csrf
                        <input type="hidden" value="" id="deleteID"  name="rig_delete_id">
                        <input type="hidden" value="" id="deleteName"  name="rig_delete_name">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">View Location</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
         

                <div class="form-row">
                    <div class="col-md-6 mb-3">
                        <label for="name">Location Name</label>
                        <input type="text" id="rigName" class="form-control @error('name') is-invalid @enderror" name="name" value="" readonly required>
                        {{-- Validation Error Message Below the Field --}}
                        @error('name')
                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="location_id">Location ID</label>
                        <input 
                            type="text" 
                            id="location_id" 
                            name="location_id" 
                            class="form-control"
                            placeholder="e.g. RN05"
                            readonly
                        >
                        <small class="text-danger error-message" style="display: none;">
                            Must be 4 characters with at least 1 letter and 1 number
                        </small>
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
    function viewrig(rig){
            let riglocation_id = rig['location_id'];
            let rigname = rig['name'];

            $("#rigName").val(rigname);
            $("#location_id").val(riglocation_id);
    }
    function deleterig(rig){
            let riglocation_id = rig['location_id'];
            let rigname = rig['name'];

            $("#deleteName").val(rigname);
            $("#deleteID").val(riglocation_id);
    }
  </script>
  
@endsection
