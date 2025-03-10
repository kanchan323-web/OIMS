@extends('layouts.frontend.admin_layout')
@section('page-content')

<div class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Rigs</h4>
                        </div>
                        <a href="{{ route('admin.rig_users.create') }}" class="btn btn-primary">Add New</a>
                    </div>
                    <div class="card-body">
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
                                        <th>Name</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="ligth-body">
                                    @foreach ($rigUsers as $index => $user)
                                    <tr>
                                        <td>
                                            <div class="checkbox d-inline-block">
                                                <input type="checkbox" class="checkbox-input" id="checkbox{{ $user->id }}">
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
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->created_at }}</td>
                                        <td>
                                            <div class="d-flex align-items-center list-action">
                                                <a class="badge badge-info mr-2" data-toggle="tooltip" title="View" href="{{ route('admin.rig_users.show', $user->id)}}">
                                                    <i class="ri-eye-line mr-0"></i>
                                                </a>
                                                <a class="badge bg-success mr-2" data-toggle="tooltip" title="Edit" href="{{ route('admin.rig_users.edit', $user->id) }}">
                                                    <i class="ri-pencil-line mr-0"></i>
                                                </a>
                                                <form action="{{ route('admin.rig_users.destroy', $user->id) }}" method="POST" class="d-inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="badge bg-warning mr-2 border-0" onclick="return confirm('Are you sure?')" data-toggle="tooltip" title="Delete">
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

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
