

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
                {{Breadcrumbs::render('section_Edit',$editData->id)}}

                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Edit Section</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.section.update') }}" method="POST" class="needs-validation"
                            novalidate>
                            @csrf
                            <div class="form-row">
                                <div class="col-md-6 mb-3">
                                    <label for="name">Section Name</label>
                                    <input type="text" class="form-control" name="section_name" value="{{$editData->section_name}}" style="text-transform: uppercase;"
                                     required>
                                    <input type="hidden" class="form-control" name="section_id" value="{{$editData->id}}" required>
                                </div>
                            </div>
                            <button class="btn btn-primary" type="submit">Submit</button>
                            <a href="{{ route('admin.section.index') }}" class="btn btn-light">Go Back</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
