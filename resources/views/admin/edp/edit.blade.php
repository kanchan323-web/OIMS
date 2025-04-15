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
                            <h4 class="card-title">Edit EDP</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.edp.update') }}" method="POST" class="needs-validation"
                            novalidate>
                            @csrf
                            <div class="form-row">

                                <div class="col-md-6 mb-3">
                                    <label for="edp_code">EDP Code</label>
                                    <input type="text" class="form-control @error('edp_code') is-invalid @enderror"
                                        name="edp_code" value="{{$editData->edp_code}}" required>
                                        <input type="hidden" class="form-control @error('edp_code') is-invalid @enderror"
                                        name="edp_id" value="{{$editData->id}}" required>
                                    <div class="text-danger" id="edpError" style="display: none;"></div>
                                    @error('edp_code')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                  </div>

                                <div class="col-md-6 mb-3">
                                    <label for="category">Select Category</label>
                                    <select class="form-control" name="Category_Name" required>
                                        <option value="" {{ empty($editData->category) ? 'selected' : '' }}>Select
                                            Category...</option>
                                        <option value="spares" {{ $editData->category == 'spares' ? 'selected' : '' }}>
                                            Spares</option>
                                        <option value="store" {{ $editData->category == 'store' ? 'selected' : '' }}>
                                            Stores</option>
                                        <option value="capital"
                                            {{ $editData->category == 'capital' ? 'selected' : '' }}>Capital Item
                                        </option>
                                    </select>
                                </div>



                                <div class="col-md-6 mb-3">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" name="description" rows="3"
                                        required>{{$editData->description}}</textarea>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="section">Select Section</label>
                                    <select class="form-control" name="section" required>
                                        <option value="" {{ empty($editData->section) ? 'selected' : '' }}>Select
                                            Section...</option>
                                        <option value="CHEM" {{ $editData->section == 'CHEM' ? 'selected' : '' }}>CHEM
                                        </option>
                                        <option value="CMTG" {{ $editData->section == 'CMTG' ? 'selected' : '' }}>
                                            CMTG</option>
                                        <option value="DRILL" {{ $editData->section == 'DRILL' ? 'selected' : '' }}>
                                            DRILL</option>
                                        <option value="ENGG" {{ $editData->section == 'ENGG' ? 'selected' : '' }}>
                                            ENGG</option>
                                        <option value="HSD" {{ $editData->section == 'HSD' ? 'selected' : '' }}>
                                            HSD</option>
                                        <option value="WELL" {{ $editData->section == 'WELL' ? 'selected' : '' }}>
                                            WELL</option>
                                    </select>
                                </div>






                                <div class="col-md-6 mb-3">
                                    <label for="measurement">Measurement</label>
                                    <select class="form-control" name="measurement" required>
                                        <option value="">Select Measurement</option>

                                        @foreach ($UoM as $unit)
                                            <option value="{{ $unit->abbreviation }}"
                                                {{ $editData->measurement == $unit->abbreviation ? 'selected' : '' }}>
                                                {{ $unit->abbreviation }} - {{ $unit->unit_name }}
                                            </option>
                                        @endforeach
                                    </select>
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
            $("form").on("submit", function (e) {
                let edpCode = $("input[name='edp_code']").val();
                let regex = /^(?:[A-Za-z]{2,3}\d{6,7}|\d{9})$/; // 9 digits OR 2-3 letters + 6-7 digits

                if (!regex.test(edpCode)) {
                    e.preventDefault(); // Stop form submission
                    $("#edpError").text("EDP Code must be 9 digits OR start with 2-3 letters followed by 6-7 digits.").show();
                } else {
                    $("#edpError").hide();
                }
            });
        });
$(document).ready(function() {
    // Automatically fade out alerts after 3 seconds
    setTimeout(function() {
        $(".alert").fadeOut("slow");
    }, 3000);
});
</script>



@endsection
