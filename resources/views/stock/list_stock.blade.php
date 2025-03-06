@extends('layouts.frontend.layout')
@section('page-content')

    <div class="content-page">
     <div class="container-fluid">
        <div class="row">



            <div class="col-lg-12">
                @if (Session::get('success'))
                <div class="alert alert-success">{{ Session::get('success') }}</div>
                @endif
                @if (Session::get('error'))
                    <div class="alert alert-danger">{{ Session::get('error') }}</div>
                @endif
    
                <div class="row justify-content-between">
                <div class="col-sm-6 col-md-9">
                       <div id="user_list_datatable_info" class="dataTables_filter">

                        <form action="{{ route('stock_list') }}" method="get" class="mr-3 position-relative">
                            <div class="row">
                                <div class="col-md-2 mb-2">
                                    <label for="category">Category</label>
                                    <select class="form-control" name="category">
                                        <option disabled {{ request('category') ? '' : 'selected' }}>Select Category...</option>
                                        <option value="Spares" {{ request('category') == 'Spares' ? 'selected' : '' }}>Spares</option>
                                        <option value="Stores" {{ request('category') == 'Stores' ? 'selected' : '' }}>Stores</option>
                                        <option value="Capital items" {{ request('category') == 'Capital items' ? 'selected' : '' }}>Capital items</option>
                                    </select>
                                </div>
                        
                                <div class="col-md-2 mb-2">
                                    <label for="location_name">Location Name</label>
                                    <input type="text" class="form-control" placeholder="Location Name" name="location_name" value="{{ request('location_name') }}">
                                </div>
                        
                                <div class="col-md-2 mb-2">
                                    <label for="form_date">From Date</label>
                                    <input type="date" class="form-control" name="form_date" value="{{ request('form_date') }}">
                                </div>
                        
                                <div class="col-md-2 mb-2">
                                    <label for="to_date">To Date</label>
                                    <input type="date" class="form-control" name="to_date" value="{{ request('to_date') }}">
                                </div>
                        
                                <div class="col-md-4 mb-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary mr-2">Search</button>
                                    <a href="{{ route('stock_list') }}" class="btn btn-secondary">Reset</a>
                                </div>
                            </div>
                        </form>
                        
                        
                       </div>
                    </div>


                    <div class="col-sm-6 col-md-3">
                       <div class="user-list-files d-flex">
                         <a href="{{ route('add_stock') }}" class="btn btn-primary add-list"><i class="las la-plus mr-3"></i>Add Stock</a>
                         <a href="{{ route('add_stock') }}" class="btn btn-primary add-list"><i class="las la-plus mr-3"></i>Bulk Stocks </a>
                       </div>
                    </div>
                 </div>
            </div>

            <div class="col-lg-12">
                <div class="table-responsive rounded mb-3">
                <table class="data-tables table mb-0 tbl-server-info">
                    <thead class="bg-white text-uppercase">
                        <tr class="ligth ligth-data">
                            <th>
                                <div class="checkbox d-inline-block">
                                    <input type="checkbox" class="checkbox-input" id="checkbox1">
                                    <label for="checkbox1" class="mb-0"></label>
                                </div>
                            </th>
                            <th>Sr.No</th>
                            <th>Location Name</th>
                            <th>EDP</th>
                            <th>Section</th>
                            <th>Description</th>
                            <th>Quantity</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="ligth-body">
                    @foreach($data as $stockdata)
                        <tr>
                            
                            <td>
                                <div class="checkbox d-inline-block">
                                    <input type="checkbox" class="checkbox-input" id="checkbox2">
                                    <label for="checkbox2" class="mb-0"></label>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div>
                                    {{$stockdata->id}}         
                                    </div>
                                </div>
                            </td>
                            <td>{{$stockdata->location_name}}</td>
                            <td>{{$stockdata->edp_code}}</td>
                            <td>{{$stockdata->section}}</td>
                            <td>{{$stockdata->description}}</td>
                            <td>{{$stockdata->qty}}</td>
                            <td>
                                <div class="d-flex align-items-center list-action">
                                    <a class="badge badge-info mr-2" data-toggle="modal" onclick="viewstockdata({{$stockdata->id}})"  data-target=".bd-example-modal-xl" data-placement="top" title="" data-original-title="View"
                                        href="#"><i class="ri-eye-line mr-0"></i></a>
                                    <a class="badge bg-success mr-2" data-toggle="tooltip"   data-placement="top" title="" data-original-title="Edit"
                                        href="{{url('/edit_stock/'.$stockdata->id)}}"><i class="ri-pencil-line mr-0"></i></a>
                                    <a class="badge bg-warning mr-2" data-toggle="modal" data-target="#DeleteModal"  onclick="deleteStockdata({{$stockdata->id}})"  data-placement="top" title="" data-original-title="Delete"
                                        href="#"><i class="ri-delete-bin-line mr-0"></i></a>
                                </div>
                            </td>
                        </tr>
                                          
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>


        </div>
    </div>
</div>

<div class="modal fade" id="DeleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <form action="{{route('Delete_stock')}}"  method="post">
            @csrf
        <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Are you sure?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>You are about to delete <span class=""></span> and all of its contents.
                    </br>
                    <span style="font-weight:bold">This operation is permanent and cannot be undone.</span>
                    </p>
                            <input type="hidden" name="delete_id"  id="delete_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class=" btn btn-primary " data-dismiss="modal">Cancle</button>
                    <button type="submit" class=" btn btn-secondary " >Delete</button>
                </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
  <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">View Stock</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div class="card-body">
            <!-- <form class="needs-validation" novalidate method="POST" action="" id="addStockForm"> -->
                
                <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for="">Location Id</label>
                    <input type="text" class="form-control" name="location_id" placeholder=" Location Id" id="location_id" readonly>
                    <div class="invalid-feedback">
                        Enter location id
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="">Location Name</label>
                    <input type="text" class="form-control" placeholder=" Location Name" name="location_name" id="location_name" readonly>
                    <div class="invalid-feedback">
                        Enter Location Name
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="">EDP Code</label>
                    <input type="text" class="form-control" name="edp_code" placeholder=" EDP Code" id="edp_code" readonly>
                    <div class="invalid-feedback">
                        Enter EDP Code
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                        <label for="category">Category</label>
                        <select class="form-control" id="category" name="category" disabled>
                            <option selected disabled value="">Select Category...</option>
                            <option value="Spares">Spares</option>
                            <option value="Stores">Stores</option>
                            <option value="Capital items">Capital items</option>
                        </select>
                        <input type="hidden" name="category" id="hidden_category">
                        <div class="invalid-feedback">
                            Please select a category
                        </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="">Description </label>
                    <textarea class="form-control" id="description" name="description" placeholder="Enter Description" readonly></textarea>
                    <div class="invalid-feedback">
                        Enter Description
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="section">Section</label>
                    <select class="form-control" name="section" id="section" disabled>
                        <option selected disabled value="">Select Section...</option>
                        <option value="Section1">Section1</option>
                        <option value="Section2">Section2</option>
                        <option value="Section3">Section3</option>
                    </select>
                    <input type="hidden" name="section" id="hidden_section">
                    <div class="invalid-feedback">
                        Please select a Section
                    </div>
                </div>

              


                <div class="col-md-6 mb-3">
                    <label for="">Available Quantity</label>
                    <input type="text" class="form-control" placeholder=" Available Quantity" name="qty" id="qty" readonly>
                    <div class="invalid-feedback">
                        Enter Available Quantity
                    </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="">Unit of Measurement </label>
                    <input type="text" class="form-control" name="measurement" placeholder="Unit of Measurement" id="measurement" readonly>
                    <div class="invalid-feedback">
                        Enter Unit of Measurement
                    </div>
                    </div>
                    <div class="col-md-6 mb-3">
                    <label for="">New Spareable </label>
                    <input type="text" class="form-control" placeholder=" New Spareable" name="new_spareable" id="new_spareable" readonly>
                    <div class="invalid-feedback">
                        Enter New Spareable
                    </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="">Used Spareable </label>
                    <input type="text" class="form-control" placeholder=" Used Spareable" name="used_spareable" id="used_spareable" readonly>
                    <div class="invalid-feedback">
                        Enter Used Spareable
                    </div>
                    </div>
                    <div class="col-md-6 mb-3">
                    <label for="">Remarks / Notes  </label>
                    <textarea class="form-control" id="remarks" name="remarks" placeholder=" Remarks / Notes" readonly></textarea>
                    <div class="invalid-feedback">
                        Enter Remarks / Notes
                    </div>
                </div>
                </div>
                <!-- <button class="btn btn-primary" type="submit">Submit form</button>
                <button type="reset" class="btn btn-danger">Reset</button>
                <a href="" class="btn btn-light">Go Back</a> -->
            <!-- </form> -->
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
      </div>
    </div>
  </div>
</div>

<script>
    function viewstockdata(id){
       var id = id;
        // console.log(id);
        // return false;

        $.ajaxSetup({headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')}});
                       $.ajax({
                        type: "GET",
                        url: "{{route('stock_list_view')}}",
                        data:{ data: id },
                        success: function(response){
                                // console.log(response.viewdata['category']);
                                $("#location_id").val(response.viewdata['location_id']);
                                $("#location_name").val(response.viewdata['location_name']);
                                $("#edp_code").val(response.viewdata['edp_code']);
                            
                           
                                    var sectionValue = response.viewdata['section'];
                                    $("#section").val(sectionValue).trigger("change");
                                    $("#hidden_section").val(sectionValue);

                                    var categoryValue = response.viewdata['category'];
                                    $("#category").val(categoryValue).trigger("change");
                                    $("#hidden_category").val(categoryValue);


                                $("#qty").val(response.viewdata['qty']);
                                $("#measurement").val(response.viewdata['measurement']);
                                $("#new_spareable").val(response.viewdata['new_spareable']);
                                $("#used_spareable").val(response.viewdata['used_spareable']);
                                $("#remarks").val(response.viewdata['remarks']); 
                        }   
                       });
    }
    
    function deleteStockdata(id){
          
        $("#delete_id").val(id);

    }
</script>

