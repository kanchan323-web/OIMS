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

                              
                                <th>Sr. No.</th>
                                <th>User Name</th>
                                <th>Email</th>
                                <th>CPF No</th>
                                <!-- <th>Rig Id</th> -->
                               
                            </tr>
                        </thead>
                        <tbody class="ligth-body">
                            @foreach($data as $index=> $userData)

                            <tr class="user-row" 
                                data-id="{{$userData->id}}" 
                                data-email="{{$userData->email}}" 
                                data-user-type="{{$userData->user_type}}" 
                                data-rig-id="{{$userData->rig_id}}">

                                        <td>{{$index + 1}}</td>
                                        <td>{{$userData->user_name}}</td>
                                        <td>{{$userData->email}}</td>
                                        <td>{{$userData->cpf_no}}</td>

                           
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
                <div class="table-responsive rounded mb-3" id="thiswillget">

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
        let form = $('<form action="{{ url('/mapusergetdata') }}" method="POST"></form>');
        form.append('@csrf');
        form.append('<input type="hidden" name="id" value="' + userId + '">');
        $('body').append(form);
        form.submit();
    });
});
</script>
@endsection

