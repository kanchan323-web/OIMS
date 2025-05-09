

@extends('layouts.frontend.admin_layout')
@section('page-content')


<div class="content-page">
    <div class="container-fluid add-form-list">
        <div class="row">
            <div class="col-sm-12">
               
                @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
                @endif


                @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif


                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Add Category</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.category.update') }}" method="POST" class="needs-validation"
                            novalidate>
                            @csrf
                            <div class="form-row">
                                <div class="col-md-6 mb-3">
                                    <label for="name">Category Name</label>
                                    <input type="text" class="form-control" name="category_name" value="{{$editData->category_name}}" required>
                                    <input type="hidden" class="form-control" name="category_id" value="{{$editData->id}}" required>
                                </div>
                            </div>
                            <button class="btn btn-primary" type="submit">Submit</button>
                            <a href="{{ route('admin.category.index') }}" class="btn btn-light">Go Back</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
