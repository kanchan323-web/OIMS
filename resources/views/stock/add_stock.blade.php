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
                    <div class="card-body">
                        <form class="needs-validation" novalidate method="POST" action="{{ route('stockSubmit') }}" id="addStockForm">
                            @csrf
                            <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <label for="">Location Id</label>
                                <input type="text" class="form-control" name="location_id" placeholder=" Location Id" id="" required>
                                <div class="invalid-feedback">
                                    Enter location id
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="">Location Name</label>
                                <input type="text" class="form-control" placeholder=" Location Name" name="location_name" id="" required>
                                <div class="invalid-feedback">
                                    Enter Location Name
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="">EDP Code</label>
                                <input type="text" class="form-control" name="edp_code" placeholder=" EDP Code" id="" required>
                                <div class="invalid-feedback">
                                    Enter EDP Code
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="">Category</label>
                                <select class="form-control" id="" name="category" required>
                                    <option selected disabled value="">Select Category...</option>
                                    <option value="Spares">Spares</option>
                                    <option value="Stores">Stores</option>
                                    <option value="Capital items">Capital items</option>
                                </select>
                                <div class="invalid-feedback">
                                    Please select a category
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="">Description </label>
                                <textarea class="form-control" id="" name="description" placeholder="Enter Description" required></textarea>
                                <div class="invalid-feedback">
                                    Enter Description
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="">Section</label>
                                <select class="form-control" name="section" id="" required>
                                    <option selected disabled value="">Select Section...</option>
                                    <option value="Section1">Section1</option>
                                    <option value="Section2">Section2</option>
                                    <option value="Section3">Section3</option>
                                </select>
                                <div class="invalid-feedback">
                                    Please select a Section
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="">Available Quantity</label>
                                <input type="text" class="form-control" placeholder=" Available Quantity" name="qty" id="" required>
                                <div class="invalid-feedback">
                                    Enter Available Quantity
                                </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">Unit of Measurement </label>
                                <input type="text" class="form-control" name="measurement" placeholder="Unit of Measurement" id="" required>
                                <div class="invalid-feedback">
                                    Enter Unit of Measurement
                                </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                <label for="">New Spareable </label>
                                <input type="text" class="form-control" placeholder=" New Spareable" name="new_spareable" id="" required>
                                <div class="invalid-feedback">
                                    Enter New Spareable
                                </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">Used Spareable </label>
                                <input type="text" class="form-control" placeholder=" Used Spareable" name="used_spareable" id="" required>
                                <div class="invalid-feedback">
                                    Enter Used Spareable
                                </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                <label for="">Remarks / Notes  </label>
                                <textarea class="form-control" id="" name="remarks" placeholder=" Remarks / Notes" required></textarea>
                                <div class="invalid-feedback">
                                    Enter Remarks / Notes
                                </div>
                            </div>
                            </div>
                            <button class="btn btn-primary" type="submit">Submit form</button>
                            <button type="reset" class="btn btn-danger">Reset</button>
                            <a href="{{ url()->previous() }}" class="btn btn-light">Go Back</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>


