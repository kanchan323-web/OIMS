@extends('layouts.frontend.layout')
@section('page-content')


    <div class="content-page">
     <div class="container-fluid add-form-list">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Edit Stock </h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form class="needs-validation" novalidate method="POST" action="{{ route('update_stock') }}" id="addStockForm">
                            @csrf
                            
                         
                            <div class="form-row">

                            <div class="col-md-6 mb-3">
                                <label for="">Location Id</label>
                                <input type="text" class="form-control" name="location_id" value="{{$editData->location_id}}" placeholder=" Location Id" id="" required>
                                <input type="hidden" class="form-control" name="id" value="{{$editData->id}}" placeholder=" Location Id" id="" >
                                <div class="invalid-feedback">
                                    Enter location id
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="">Location Name</label>
                                <input type="text" class="form-control" placeholder="Location Name"  value="{{$editData->location_name}}" name="location_name" id="" required>
                                <div class="invalid-feedback">
                                    Enter Location Name
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="">EDP Code</label>
                                <input type="text" class="form-control" name="edp_code" value="{{$editData->edp_code}}" placeholder=" EDP Code" id="" required>
                                <div class="invalid-feedback">
                                    Enter EDP Code
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="category">Category</label>
                                <select class="form-control" id="category" name="category" required>
                                    <option disabled value="" {{ empty($editData->category) ? 'selected' : '' }}>Select Category...</option>
                                    <option value="Spares" {{ $editData->category == 'Spares' ? 'selected' : '' }}>Spares</option>
                                    <option value="Stores" {{ $editData->category == 'Stores' ? 'selected' : '' }}>Stores</option>
                                    <option value="Capital items" {{ $editData->category == 'Capital items' ? 'selected' : '' }}>Capital items</option>
                                </select>
                                <div class="invalid-feedback">
                                    Please select a category
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="">Description </label>
                                <textarea class="form-control" id=""  name="description" placeholder="Enter Description" required>{{$editData->description}}</textarea>
                                <div class="invalid-feedback">
                                    Enter Description
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="section">Section</label>
                                <select class="form-control" name="section" id="section" required>
                                    <option disabled value="" {{ empty($editData->section) ? 'selected' : '' }}>Select Section...</option>
                                    <option value="Section1" {{ $editData->section == 'Section1' ? 'selected' : '' }}>Section1</option>
                                    <option value="Section2" {{ $editData->section == 'Section2' ? 'selected' : '' }}>Section2</option>
                                    <option value="Section3" {{ $editData->section == 'Section3' ? 'selected' : '' }}>Section3</option>
                                </select>
                                <div class="invalid-feedback">
                                    Please select a Section
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="">Available Quantity</label>
                                <input type="text"  value="{{$editData->qty}}"  class="form-control" placeholder=" Available Quantity" name="qty" id="" required>
                                <div class="invalid-feedback">
                                    Enter Available Quantity
                                </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">Unit of Measurement </label>
                                <input type="text" value="{{$editData->measurement}}" class="form-control" name="measurement" placeholder="Unit of Measurement" id="" required>
                                <div class="invalid-feedback">
                                    Enter Unit of Measurement
                                </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                <label for="">New Spareable </label>
                                <input type="text" value="{{$editData->new_spareable}}" class="form-control" placeholder=" New Spareable" name="new_spareable" id="" required>
                                <div class="invalid-feedback">
                                    Enter New Spareable
                                </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">Used Spareable </label>
                                <input type="text" class="form-control" value="{{$editData->used_spareable}}"  placeholder=" Used Spareable" name="used_spareable" id="" required>
                                <div class="invalid-feedback">
                                    Enter Used Spareable
                                </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                <label for="">Remarks / Notes  </label>
                                <textarea class="form-control" name="remarks" placeholder="Remarks / Notes" required>{{ $editData->remarks }}</textarea>

                                <div class="invalid-feedback">
                                    Enter Remarks / Notes
                                </div>
                            </div>
                            </div>
                        
                            <button class="btn btn-primary" type="submit">Submit form</button>
                            <button type="reset" class="btn btn-danger">Reset</button>
                            <a href="{{ route('stock_list') }}" class="btn btn-light">Go Back</a>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>


