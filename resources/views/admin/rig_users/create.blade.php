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

        {{-- Validation Errors --}}
        @if ($errors->any())
        <div class="alert bg-warning text-white alert-dismissible fade show" role="alert">
            <strong>Warning:</strong> Please check the form for errors.
            <button type="button" class="close close-dark" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        <div class="row">
            <div class="col-sm-12">
                {{Breadcrumbs::render('add_rig')}}
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Add Location</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.rig_users.store') }}" method="POST" class="needs-validation" novalidate>
                            @csrf
                            <div class="form-row">
                                <div class="col-md-6 mb-3">
                                    <label for="name">Location Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
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
                                    >
                                    <small class="text-danger error-message" style="display: none;">
                                        Must be 4 characters with at least 1 letter and 1 number
                                    </small>
                                    @error('location_id')
                                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <button class="btn btn-primary" type="submit">Submit</button>
                            <a href="{{ route('admin.rig_users.index') }}" class="btn btn-light">Go Back</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
    // Regex pattern: 4 chars, at least 1 letter and 1 number
    const locationIdPattern = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{4}$/;

    // Validate on input change
    $('#location_id').on('input', function() {
        validateLocationId();
    });

    // Validate on form submission
    $('#logForm').on('submit', function(e) {
        if (!validateLocationId()) {
            e.preventDefault(); // Stop form submission if invalid
        }
    });

    function validateLocationId() {
        const value = $('#location_id').val().trim();
        const isValid = locationIdPattern.test(value);
        
        if (isValid) {
            $('#location_id').removeClass('is-invalid').addClass('is-valid');
            $('.error-message').hide();
        } else {
            $('#location_id').removeClass('is-valid').addClass('is-invalid');
            $('.error-message').show();
        }
        
        return isValid;
    }
});
</script>
@endsection
