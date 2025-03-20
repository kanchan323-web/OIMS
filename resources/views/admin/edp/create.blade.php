@extends('layouts.frontend.admin_layout')
@section('page-content')

    <div class="content-page">
        <div class="container-fluid add-form-list">
            <div class="row">
                <div class="col-sm-12">
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
                    <div class="card">
                        <!-- @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif -->


                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Add EDP</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.edp.store') }}" method="POST" class="needs-validation" novalidate>
                                @csrf
                                <div class="form-row">
                                    <div class="col-md-6 mb-3">
                                        <label for="edp_code">EDP Code</label>
                                        <input type="text" class="form-control @error('edp_code') is-invalid @enderror"
                                            name="edp_code" value="{{ old('edp_code') }}" required>
                                        @error('edp_code')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="category">Select Category</label>
                                        <select class="form-control" name="Category_Name" required> 

                                            <option value="" {{ empty($editData->category) ? 'selected' : '' }}>
                                                Select Category...</option>
                                                <option value="Spares" >
                                                 Spares</option>

                                            <option value="Stores">
                                                Stores
                                            </option>
                                            <option value="Capital Item">
                                                Capital Item
                                            </option>

                                        </select>
                                    </div>

                                    <!-- <div class="col-md-6 mb-3">
                                            <label for="category">Select Category</label>
                                            <select class="form-control" name="Category_Name" required>
                                                <option disabled value="" {{ empty($editData->category) ? 'selected' : '' }}>
                                                    Select Category...</option>

                                                @foreach($category_list as $index => $list)
                                                <option value="{{ $list->category_name }}"
                                                    {{ isset($editData->category) && $editData->category == $list->category_name ? 'selected' : '' }}>
                                                    ({{ $loop->iteration }}) {{ $list->category_name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div> -->

                                    <div class="col-md-6 mb-3">
                                        <label for="description">Description</label>
                                        <textarea class="form-control" name="description" rows="3" required></textarea>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="category">Select Section</label>
                                        <select class="form-control" name="section" required>

                                            <option value="" {{ empty($editData->section) ? 'selected' : '' }}>
                                                Select Section...</option>


                                            <option value="ENGG">
                                                ENGG
                                            </option>
                                            <option value="DRILL">
                                                DRILL
                                            </option>

                                        </select>
                                    </div>

                                    <!-- <div class="col-md-6 mb-3">
                                            <label for="section">Section</label>
                                            <input type="text" class="form-control" name="section" required>
                                        </div> -->

                                    <div class="col-md-6 mb-3">
                                        <label for="measurement">Measurement</label>
                                        <input type="text" class="form-control" name="measurement" required>
                                    </div>


                                </div>
                                <button class="btn btn-primary" type="submit">Submit</button>
                                <a href="{{ route('admin.edp.index') }}" class="btn btn-light">Go Back</a>
                            </form>
                        </div>
                    </div>
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
    </script>
@endsection