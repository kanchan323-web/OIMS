@extends('layouts.frontend.layout')
@section('page-content')

<div class="content-page">
    <div class="container-fluid add-form-list">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Request Stock</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form class="needs-validation" novalidate method="POST"
                            action="{{ route('request_stock_add.post') }}" id="addStockForm">
                            @csrf
                            <div class="form-row">
                                <div class="col-md-6 mb-3">
                                    <label for="">User Name</label>
                                    <input type="text" class="form-control" name="user_name" value="{{Auth::user()->user_name}}" placeholder="User Name"
                                        id="" required readonly>
                                    <div class="invalid-feedback">
                                        Enter User name
                                    </div>
                                    @error("user_name")
                                    <small class="text-danger">{{$message}}</small>
                                    @enderror

                                </div>
                                <div class="">
                                    <label for=""></label>
                                    <input type="hidden" class="form-control" name="user_id" placeholder=""
                                        value="{{Auth::user()->id}}" id="" required>
                                </div>


                                <div class="col-md-6 mb-3">
                                    <label for="">Section</label>
                                    <select class="form-control" name="section" id="" required>
                                        <option selected disabled value="">Select Section...</option>
                                        <option value="ENGG">ENGG</option>
                                        <option value="DRILL">DRILL</option>

                                    </select>
                                    <div class="invalid-feedback">
                                        Please select a Section
                                    </div>
                                    @error("section")
                                    <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="">Stock Item</label>
                                    <select class="form-control" name="stock_item" id="" required>
                                        <option selected disabled value="">Select Stock Item...</option>
                                        <option value="StockItem1">Stock Item 1</option>
                                        <option value="StockItem2">Stock Item 2</option>
                                        <option value="StockItem3">Stock Item 3</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please select a Stock Item
                                    </div>
                                    @error("stock_item")
                                    <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="">Stock Code</label>
                                    <input type="number" class="form-control" name="stock_code" placeholder="Stock Code"
                                        id="" required>
                                    <div class="invalid-feedback">
                                        Stock Code
                                    </div>
                                    @error("stock_code")
                                    <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="">Requested Quantity</label>
                                    <input type="number" class="form-control" name="request_quantity"
                                        placeholder="Requested Quantity" id="" required>
                                    <div class="invalid-feedback">
                                        Requested Quantity
                                    </div>
                                    @error("request_quantity")
                                    <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>



                                <div class="col-md-6 mb-3">
                                    <label for="">Available Quantity</label>
                                    <input type="number" class="form-control" placeholder=" Available Quantity" name="qty"
                                        id="" required>
                                    <div class="invalid-feedback">
                                        Enter Available Quantity
                                    </div>
                                    @error("qty")
                                    <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="">Unit of Measurement </label>
                                    <input type="text" class="form-control" name="measurement"
                                        placeholder="Unit of Measurement" id="" required>
                                    <div class="invalid-feedback">
                                        Enter Unit of Measurement
                                    </div>
                                    @error("measurement")
                                    <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>


                                <div class="col-md-6 mb-3">
                                    <label for="">New Spareable </label>
                                    <input type="number" class="form-control" placeholder=" New Spareable"
                                        name="new_spareable" id="" required>
                                    <div class="invalid-feedback">
                                        Enter New Spareable
                                    </div>
                                    @error("new_spareable")
                                    <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>



                                <div class="col-md-6 mb-3">
                                    <label for="">Used Spareable </label>
                                    <input type="number" class="form-control" placeholder=" Used Spareable"
                                        name="used_spareable" id="" required>
                                    <div class="invalid-feedback">
                                        Enter Used Spareable
                                    </div>
                                    @error("used_spareable")
                                    <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="">Remarks / Notes </label>
                                    <textarea class="form-control" id="" name="remarks" placeholder=" Remarks / Notes"
                                        required></textarea>
                                    <div class="invalid-feedback">
                                        Enter Remarks / Notes
                                    </div>
                                    @error("remarks")
                                    <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="">Supplier Location Name</label>
                                    <input type="text" class="form-control" name="supplier_location_name"
                                        placeholder=" Supplier Location Name" id="" required>
                                    <div class="invalid-feedback">
                                        Enter Supplier Location Name
                                    </div>
                                    @error("supplier_location_name")
                                    <small class="text-danger">{{$message}}</small>
                                    @enderror
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
@endsection