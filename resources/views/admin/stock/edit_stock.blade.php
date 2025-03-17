@extends('layouts.frontend.admin_layout')
@section('page-content')

<div class="content-page">
    <div class="container-fluid add-form-list">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Edit Stock</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form class="needs-validation" novalidate method="POST" action="{{ route('admin.update_stock') }}" id="editStockForm">
                            @csrf
                            <input type="hidden" name="id" value="{{ $editData->id }}">
                            
                            <div class="form-row">
                                <div class="col-md-6 mb-3">
                                    <label for="">EDP Code</label>
                                    <input type="text" class="form-control" name="edp_code_display" value="{{ $editData->edp_code }}" readonly>
                                    <input type="hidden" name="edp_code" value="{{ $editData->edp_code }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="">Location Id</label>
                                    <input type="text" class="form-control" name="location_id" value="{{ $editData->location_id }}" required readonly>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="">Location Name</label>
                                    <input type="text" class="form-control" name="location_name" value="{{ $editData->location_name }}" required readonly>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="">Category</label>
                                    <select class="form-control" name="category" id="category_id" readonly required>
                                        <option selected disabled value="">Select Category...</option>
                                        <option value="Spares" {{ $editData->category == 'Spares' ? 'selected' : '' }}>Spares</option>
                                        <option value="Stores" {{ $editData->category == 'Stores' ? 'selected' : '' }}>Stores</option>
                                        <option value="Capital items" {{ $editData->category == 'Capital items' ? 'selected' : '' }}>Capital items</option>
                                    </select>
                                    <input type="hidden" name="category" id="category_hidden" value="{{ $editData->category }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="">Description</label>
                                    <textarea class="form-control" name="description" id="description" required readonly>{{ $editData->description }}</textarea>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="">Section</label>
                                    <select class="form-control" name="section" id="section_id" readonly required>
                                        <option selected disabled value="">Select Section...</option>
                                        <option value="ENGG" {{ $editData->section == 'ENGG' ? 'selected' : '' }}>ENGG</option>
                                        <option value="DRILL" {{ $editData->section == 'DRILL' ? 'selected' : '' }}>DRILL</option>
                                    </select>
                                    <input type="hidden" name="section" id="section_hidden" value="{{ $editData->section }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="">Unit of Measurement</label>
                                    <input type="text" class="form-control" name="measurement" id="measurement" value="{{ $editData->measurement }}" required readonly>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="">Available Quantity</label>
                                    <input type="number" class="form-control" name="qty" id="qty" value="{{ $editData->qty }}" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="">New Spareable</label>
                                    <input type="number" class="form-control" name="new_spareable" id="new_spareable" value="{{ $editData->new_spareable }}" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="">Used Spareable</label>
                                    <input type="number" class="form-control" name="used_spareable" id="used_spareable" value="{{ $editData->used_spareable }}" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="">Remarks / Notes</label>
                                    <textarea class="form-control" name="remarks" id="remarks" required>{{ $editData->remarks }}</textarea>
                                </div>
                            </div>

                            <button class="btn btn-primary" type="submit">Update Stock</button>
                            <a href="{{ route('admin.stock_list') }}" class="btn btn-light">Go Back</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        var edpCode = "{{ $editData->edp_code }}";
        
        if (edpCode) {
            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });

            $.ajax({
                type: "GET",
                url: "{{ route('admin.get_edp_details') }}",
                data: { edp_code: edpCode },
                success: function (response) {
                    console.log("EDP Data for Edit:", response);

                    if (response.success) {
                        $("#category_id").val(response.edp.category);
                        $("#category_hidden").val(response.edp.category);

                        $("#description").val(response.edp.description);
                        $("#measurement").val(response.edp.measurement);
                        $("#section_id").val(response.edp.section);
                        $("#section_hidden").val(response.edp.section);
                    }
                },
                error: function (xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                }
            });
        }
    });
</script>

@endsection
