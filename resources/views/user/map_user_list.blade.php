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

                       


                        </div>
                    </div>


                   
                </div>
            </div>

            <div class="col-lg-12">
                <h4>Map User List</h4>
                <div class="table-responsive rounded mb-3">
                    <table class="data-tables table mb-0 tbl-server-info ">
                        <thead class="bg-white text-uppercase">

                      
                            <tr class="ligth ligth-data">

                              
                                <th>User Name</th>
                                <th>Email</th>
                                <th>Rig Id</th>
                               
                            </tr>
                        </thead>
                        <tbody class="ligth-body">
                            @foreach($data as $userData)

                            <tr class="user-row" 
                                data-id="{{$userData->id}}" 
                                data-email="{{$userData->email}}" 
                                data-user-type="{{$userData->user_type}}" 
                                data-rig-id="{{$userData->rig_id}}">

                                        <td>{{$userData->user_name}}</td>
                                        <td>{{$userData->email}}</td>
                                        <td>{{$userData->rig_id}}</td>

                           
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>


        </div>
    </div>
</div>


<div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Stock Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div class="col-lg-12">
                <div class="table-responsive rounded mb-3">
                    <table class="data-tables table mb-0 tbl-server-info">
                        <thead class="bg-white text-uppercase">
                            <tr class="ligth ligth-data">
                                <th>Sr.No</th>
                                <th>Location Name</th>
                                <th>EDP</th>
                                <th>Section</th>
                                <th>Description</th>
                                <th>Quantity</th>
                            </tr>
                        </thead>
                        <tbody class="light-body-userdata">
                        
                       
                     
                                          
                     
                        </tbody>
                    </table>
                </div>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="noDataFoundModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="noDataFoundLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="noDataFoundLabel">User Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p class="text-center text-danger"><strong>No Data Found for the Selected User.</strong></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
    $(document).ready(function() {
        $(".user-row").click(function() {
            let userId = $(this).data("id");

        
            $.ajaxSetup({
                headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') }
            });

        
            $.ajax({
                type: "POST",
                url: "{{ route('map_all_user_data.post') }}",
                data: { data: userId },
                success: function(response) {
                    console.log(response); 

                    if (response.data && response.data.length > 0) {
                        let id = response.data[0].id;

                        // window.location.href = "https://example.com";

                        // Show the modal
                        $("#staticBackdrop").modal("show");

                 
                        $.ajax({
                            type: "POST",
                            url: "{{ route('map_user_data_specific.post') }}",
                            data: { data: id },
                            success: function(response) {
                                console.log(response); 

                            
                                if (response.data && Array.isArray(response.data) && response.data.length > 0) {
                                    let tableBody = "";

                                 
                                    response.data.forEach((item, index) => {
                                        tableBody += `<tr>
                                            <td class='text-center'>${index + 1}</td>
                                            <td>${item.location_name || ''}</td>
                                            <td>${item.edp_code || ''}</td>
                                            <td>${item.section || ''}</td>
                                            <td>${item.description || ''}</td>
                                            <td class='text-center'>${item.qty || ''}</td>
                                            
                                        </tr>`;

                                     

                                    });
                                    $(".light-body-userdata").html(tableBody);
                                } else {
                                  
                                    $(".light-body-userdata").html("<tr><td colspan='8' class='text-center'>No data found</td></tr>");
                                }
                            },
                            error: function(xhr) {
                                console.error("Error fetching specific user data:", xhr);
                                alert("Something went wrong while fetching specific user data.");
                            }
                        });
                    } else {
                 
                        $("#noDataFoundModal").modal("show");
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 400) {
                        alert("Error: " + xhr.responseJSON.error);
                    } else if (xhr.status === 404) {
                        $("#noDataFoundModal").modal("show");
                    } else {
                        alert("Something went wrong! Please try again.");
                    }
                }
            });
        });
    });
</script>
@endsection

