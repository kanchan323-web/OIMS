@extends('layouts.frontend.layout')
@section('page-content')

<div class="content-page">
    <div class="container-fluid add-form-list">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Add Stock </h4>
                        </div>
                    </div>
                    {{-- @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif --}}
                    <div class="card-body">
                        <form class="needs-validation" novalidate method="POST" action="{{ route('stockSubmit') }}"
                            id="addStockForm">
                            @csrf
                            <div class="form-row">
                                <div class="col-md-6 mb-3">
                                    <label for="">Location Id</label>
                                    <input type="text" class="form-control" name="location_id"
                                        placeholder=" Location Id" id="" value="{{$LocationName->location_id}}" required readonly>
                                    @error('location_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">Location Name</label>
                                    <input type="text" class="form-control" placeholder=" Location Name"
                                        name="location_name" id="" value="{{$LocationName->name}}" required readonly>
                                    @error('location_name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="">EDP Code</label>
                                    <select class="form-control @error('edp_code') is-invalid @enderror" name="edp_code" id="edp_code_id" required>
                                        <option selected disabled value="">Select EDP Code...</option>
                                        @foreach($edpCodes as $edp)
                                            <option value="{{ $edp->id }}">{{ $edp->edp_code }}</option>
                                        @endforeach
                                    </select>

                                    @error('edp_code')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                    <div class="col-md-6 mb-3 edp_detail">
                                        <label for="">Category</label>
                                        <input type="text" class="form-control" name="category" id="category_id" readonly required>
                                    <!--  <select class="form-control @error('category') is-invalid @enderror" name="category"
                                            required>
                                            <option selected disabled value="">Select Category...</option>
                                            <option value="Spares" {{ old('category') == 'Spares' ? 'selected' : '' }}>
                                                Spares</option>
                                            <option value="Stores" {{ old('category') == 'Stores' ? 'selected' : '' }}>
                                                Stores</option>
                                            <option value="Capital items"
                                                {{ old('category') == 'Capital items' ? 'selected' : '' }}>Capital items
                                            </option>
                                        </select>
                                    -->
                                        @error('category')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3 edp_detail">
                                        <label for="">Description</label>
                                        <textarea class="form-control "name="description" placeholder="Enter Description" id="description" readonly required>{{ old('description') }}</textarea>

                                        @error('description')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>



                                    <div class="col-md-6 mb-3 edp_detail">
                                        <label for="">Section</label>
                                        <input type="text" class="form-control" name="section" id="section" readonly required>
                                    <!--    <select class="form-control @error('section') is-invalid @enderror" name="section" required>
                                            <option selected disabled value="">Select Section...</option>
                                            <option value="Section1" {{ old('section') == 'Section1' ? 'selected' : '' }}>
                                                Section1</option>
                                            <option value="Section2" {{ old('section') == 'Section2' ? 'selected' : '' }}>
                                                Section2</option>
                                            <option value="Section3" {{ old('section') == 'Section3' ? 'selected' : '' }}>
                                                Section3</option>
                                        </select>
                                    -->
                                        @error('section')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3 edp_detail">
                                        <label for="">Unit of Measurement</label>
                                        <input type="text" class="form-control @error('measurement') is-invalid @enderror"
                                            name="measurement" readonly id="measurement" required>

                                        @error('measurement')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                <div class="col-md-6 mb-3">
                                    <label for="qty">Available Quantity</label>
                                    <input type="number" class="form-control @error('qty') is-invalid @enderror"
                                        placeholder="Available Quantity" name="qty" id="qty" value="{{ old('qty') }}"
                                        required>
                                    @error('qty')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="">New Spareable </label>
                                    <input type="number" class="form-control" placeholder=" New Spareable"
                                        name="new_spareable" id="" required>
                                    @error('new_spareable')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">Used Spareable </label>
                                    <input type="number" class="form-control" placeholder=" Used Spareable"
                                        name="used_spareable" id="" required>
                                    @error('used_spareable')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">Remarks / Notes</label>
                                    <textarea class="form-control @error('remarks') is-invalid @enderror" name="remarks"
                                        placeholder="Remarks / Notes" required>{{ old('remarks') }}</textarea>

                                    @error('remarks')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <button class="btn btn-primary" type="submit">Submit form</button>
                            <!-- <button type="reset" class="btn btn-danger">Reset</button> -->
                            <a href="{{ route('add_stock') }}" class="btn btn-secondary">Reset</a>
                            <a href="{{ url()->previous() ?: route('stock_list') }}" class="btn btn-light">Go Back</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
 $(document).ready(function() {
    $('.edp_detail').css('display', 'none');
    $('#edp_code_id').change(function() {

        var id = $(this).val();
        // console.log(id);
        $.ajaxSetup({headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')}});
        $.ajax({
        type: "GET",
        url: "{{route('get_edp_details')}}",
        data:{ data: id },
        success: function(response){
            $('.edp_detail').css('display', 'block');
                // console.log(response.viewdata['category']);
                $("#category_id").val(response.viewdata['category']);
                $("#measurement").val(response.viewdata['measurement']);
                $("#section").val(response.viewdata['section']);
                $("#description").val(response.viewdata['description']);
         }
        });

    });
});
</script>

@endsection
