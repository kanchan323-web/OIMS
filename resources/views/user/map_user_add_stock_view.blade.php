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
    
             
            </div>

            <div class="col-lg-12">
                <div class="table-responsive rounded mb-3">
                    <h4>Stock Add By {{$data->user_name}} </h4>
                <table class="data-tables table mb-0 tbl-server-info">
                    <thead class="bg-white text-uppercase">
                        <tr class="ligth ligth-data">
                            <th>
                            <div class="checkbox d-inline-block">
                             
                            </div>
                            </th>
                            <th>Sr.No</th>
                            <th>Location Id</th>
                            <th>Location Name</th>
                            <th>EDP</th>
                            <th>Section</th>
                            <th>Description</th>
                            <th>Quantity</th>
                           
                        </tr>
                    </thead>
                    <tbody class="ligth-body">
                    @foreach($tally as $index => $daa)
                        <tr>
                            
                           
                            <td>
                                <div class="d-flex align-items-center">
                                    <div>
                                      
                                    </div>
                                </div>
                            </td>
                            <td>{{$index +1}}</td>
                            <td>{{$daa->location_id}}</td>
                            <td>{{$daa->location_name}}</td>
                            <td>{{$daa->edp_code}}</td>
                            <td>{{$daa->section}}</td>
                            <td>{{$daa->description}}</td>
                            <td>{{$daa->qty}}</td>
                        </tr>
                         
                    @endforeach         
                     
                    </tbody>
                </table>
                </div>
            </div>


        </div>
    </div>
</div>

@endsection
