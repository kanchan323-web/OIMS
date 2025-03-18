@extends('layouts.frontend.layout')
@section('page-content')

<div class="content-page">
    <div class="container-fluid">
        <div class="row">



            <div class="col-lg-12">
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

                <div class="row justify-content-between">
                    <div class="col-sm-6 col-md-9">
                        <div id="user_list_datatable_info" class="dataTables_filter">

                            <form action="{{ route('request_stock_filter') }}" method="post"
                                class="mr-3 position-relative">
                                @csrf
                                <div class="row">
                                    <div class="col-md-2 mb-2">
                                        <label for="category">EDP Code</label>
                                        <select class="form-control" name="category">
                                            <option disabled {{ request('category') ? '' : 'selected' }}>Select
                                                EDP...</option>
                                            <option value="Spares"
                                                {{ request('category') == 'Spares' ? 'selected' : '' }}>Spares
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-md-2 mb-2">
                                        <label for="location_name">Location Name</label>
                                        <input type="text" class="form-control" placeholder="Location Name"
                                            name="location_name" value="{{ request('location_name') }}">
                                    </div>

                                    <div class="col-md-2 mb-2">
                                        <label for="form_date">From Date</label>
                                        <input type="date" class="form-control" name="form_date"
                                            value="{{ request('form_date') }}">
                                    </div>

                                    <div class="col-md-2 mb-2">
                                        <label for="to_date">To Date</label>
                                        <input type="date" class="form-control" name="to_date"
                                            value="{{ request('to_date') }}">
                                    </div>

                                    <div class="col-md-4 mb-2 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary mr-2">Search</button>
                                        <a href="{{ route('request_stock_list') }}" class="btn btn-secondary">Reset</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>


           <!--         <div class="col-sm-6 col-md-3">
                        <div class="user-list-files d-flex">
                            <a href="{{ route('request_stock_add') }}" class="btn btn-primary add-list"><i
                                    class="las la-plus mr-3"></i>Add Stock</a>
                            <a href="#" class="btn btn-primary add-list"><i class="las la-plus mr-3"></i>Bulk Stocks
                            </a>
                        </div>
                    </div>  -->
                </div>
            </div>

            <div class="col-lg-12">
                <div class="table-responsive rounded mb-3">
                    <table class="data-tables table mb-0 tbl-server-info">
                        <thead class="bg-white text-uppercase">
                            <tr class="ligth ligth-data">
                                <!-- <th>
                                    <div class="checkbox d-inline-block">
                                        <input type="checkbox" class="checkbox-input" id="checkbox1">
                                        <label for="checkbox1" class="mb-0"></label>
                                    </div>
                                </th> -->

                                <th>Sr.No</th>
                                <th>Location Name</th>
                               <!-- <th>EDP</th> -->
                                <th>Status</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="ligth-body">

                            @foreach($data as $index => $stockdata)
                            @if(!in_array($stockdata->user_id, $datarig))
                            <tr>
                                <td>{{ $index  +1 }}</td>
                                <td>{{$stockdata->name}}({{$stockdata->location_id}})</td>
                              <!--  <td>{{$stockdata->stock_code}}</td> -->
                                <td>{{$stockdata->status}}</td>
                                <td>{{$stockdata->created_at}}</td>
                                <td>
                                     <!-- Edit Button (Only for Your Members) -->
                                        <a class="badge badge-success mr-2" data-toggle="modal"
                                            onclick="RequestStockData({{$stockdata->id}})"
                                            data-target=".bd-example-modal-xl" data-placement="top" title=""
                                            data-original-title="Supplier Request" href="#"><i
                                                class="ri-arrow-right-circle-line"></i>
                                        </a>
                                </td>
                            </tr>
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>


        </div>
    </div>
</div>


<div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"> Requester Request</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card-body">
                    <!-- <form class="needs-validation" novalidate method="POST" action="" id="addStockForm"> -->

                    <div class="form-row">
                        <div class="col-md-6 mb-3">
                            <label for="">Requester Name</label>
                            <input type="text" class="form-control" name="" placeholder="Requester Name" id="location_id" readonly>
                            <div class="invalid-feedback">
                                Enter Requester Name
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Requester Rig Name</label>
                            <input type="text" class="form-control" placeholder="Requester Rig Name" name=""
                                id="requester_Id" readonly>
                            <div class="invalid-feedback">
                                Enter Requester Rig Name
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Supplier Name</label>
                            <input type="text" class="form-control" name="" placeholder="Supplier Name" id="Supplier_Location_Id"
                                readonly>
                            <div class="invalid-feedback">
                                Supplier Name
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Supplier Rig Name</label>
                            <input type="text" class="form-control" name="" placeholder="Supplier Rig Name"
                                id="Supplier_Location_Name" readonly>
                            <div class="invalid-feedback">
                                Supplier Rig Name
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">EDP Code</label>
                            <input type="text" class="form-control" name="" placeholder="EDP Code" id="EDP_Code" readonly>
                            <div class="invalid-feedback">
                                Enter EDP Code
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="category">Category</label>
                            <input type="text" class="form-control" placeholder="Category" id="category" name="category" readonly>
                            <div class="invalid-feedback">
                                Enter Category Name
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="section">Section</label>
                            <input type="text" class="form-control" placeholder="Section" name="section" id="section" readonly>
                            <div class="invalid-feedback">
                                Enter Section Name
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Unit of Measurement </label>
                            <input type="text" class="form-control" name="measurement" placeholder="Unit of Measurement" id="measurement"
                                readonly>
                            <div class="invalid-feedback">
                                Enter Unit of Measurement
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Description </label>
                            <textarea class="form-control" id="description" name="description"
                                placeholder="Description" readonly></textarea>
                            <div class="invalid-feedback">
                                Enter Description
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Status </label>
                            <input type="text" class="form-control" name="status" id="status">
                            <div class="invalid-feedback">
                                Enter Status
                            </div>
                     <!--       <select class="form-control" name="status">
                                <option disabled {{ request('status') ? '' : 'selected' }}>Select
                                    Status...</option>
                                <option value="Pendding"
                                    {{ request('status') == 'Pendding' ? 'selected' : '' }}>Pendding
                                </option>
                                <option value="Pendding"
                                    {{ request('status') == 'Pendding' ? 'selected' : '' }}>Pendding
                                </option>
                                <option value="Pendding"
                                    {{ request('status') == 'Pendding' ? 'selected' : '' }}>Pendding
                                </option>
                            </select> -->
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Supplier Total Quantity</label>
                            <input type="text" class="form-control" placeholder="Supplier Total Quantity" name="qty"
                                id="req_qty" readonly>
                            <div class="invalid-feedback">
                                Supplier Total Quantity
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">New Spearable </label>
                            <input type="text" class="form-control" placeholder="New Spearable " name="new_spareable"
                                id="new_spearable" readonly>
                            <div class="invalid-feedback">
                                Enter New Spareable
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Requester Requested Quantity</label>
                            <input type="text" class="form-control" placeholder="Requested Quantity" name="qty"
                                id="req_qty" readonly>
                            <div class="invalid-feedback">
                                Enter Requested Quantity
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Used Spareable </label>
                            <input type="text" class="form-control" placeholder="Used Spareable" name="used_spareable"
                                id="used_spareable" readonly>
                            <div class="invalid-feedback">
                                Enter Used Spareable
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Request Date</label>
                            <input type="date" class="form-control" placeholder="Request Date" name="used_spareable"
                                id="createdAt" readonly>
                            <div class="invalid-feedback">
                                Enter Request Date
                            </div>
                        </div>

                    </div>
                    <button class="btn btn-danger" type="submit">Decline Request</button>
                    <button class="btn btn-success" type="submit">Recieved Request</button>
                    <button class="btn btn-primary" type="submit">Raise Query</button>
            <!--     <button type="reset" class="btn btn-danger">Reset</button>
                <a href="" class="btn btn-light">Go Back</a> -->
                    <!-- </form> -->


       <!--             <ul class="nav nav-pills mb-3 nav-fill" id="pills-tab-1" role="tablist">
                        <li class="nav-item btn-danger">
                           <a class="nav-link active" id="pills-home-tab-fill" data-toggle="pill" href="#pills-home-fill" role="tab" aria-controls="pills-home" aria-selected="true">Home</a>
                        </li>
                        <li class="nav-item">
                           <a class="nav-link" id="pills-profile-tab-fill" data-toggle="pill" href="#pills-profile-fill" role="tab" aria-controls="pills-profile" aria-selected="false">Profile</a>
                        </li>
                        <li class="nav-item">
                           <a class="nav-link" id="pills-contact-tab-fill" data-toggle="pill" href="#pills-contact-fill" role="tab" aria-controls="pills-contact" aria-selected="false">Contact</a>
                        </li>
                     </ul>


                     <div class="tab-content" id="pills-tabContent-1">
                        <div class="tab-pane fade active show" id="pills-home-fill" role="tabpanel" aria-labelledby="pills-home-tab-fill">
                           <p>first</p>
                        </div>
                        <div class="tab-pane fade" id="pills-profile-fill" role="tabpanel" aria-labelledby="pills-profile-tab-fill">
                           <p>Second</p>
                        </div>
                        <div class="tab-pane fade" id="pills-contact-fill" role="tabpanel" aria-labelledby="pills-contact-tab-fill">
                            <p>third</p>
                        </div>
                     </div>
            -->


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
function RequestStockData(id) {
    var id = id;
    // console.log(id);
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: "GET",
        url: "{{route('request_stock_view.get')}}",
        data: {
            data: id
        },
        success: function(response) {
            $("#location_id").val(response.data[0].user_name);
            $("#requester_Id").val(response.data[0].req_id);
            $("#Supplier_Location_Name").val(response.data[0].supplier_location_name);
            $("#Supplier_Location_Id").val(response.data[0].user_name);
            $("#EDP_Code").val(response.data[0].user_name);
            $("#category").val(response.data[0].user_name);
            $("#section").val(response.data[0].user_name);
            $("#description").val(response.data[0].user_name);
            $("#req_qty").val(response.data[0].request_quantity);
            $("#measurement").val(response.data[0].measurement);
            $("#new_spearable").val(response.data[0].new_spareable);
            $("#used_spareable").val(response.data[0].used_spareable);
            $("#remarks").val(response.data[0].remarks);
            $("#status").val(response.data[0].user_name);
            $("#createdAt").val(response.data[0].user_name);
        }
    });
}

function deleteStockdata(id) {

    $("#delete_id").val(id);

}
</script>
<script>
$(document).ready(function() {
    $("#selectAll").on("change", function() {
        $(".row-checkbox").prop("checked", $(this).prop("checked"));
    });

    $(".row-checkbox").on("change", function() {
        if ($(".row-checkbox:checked").length === $(".row-checkbox").length) {
            $("#selectAll").prop("checked", true);
        } else {
            $("#selectAll").prop("checked", false);
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
