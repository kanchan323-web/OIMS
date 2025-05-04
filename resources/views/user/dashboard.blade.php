@extends('layouts.frontend.layout')
@section('page-content')
 
    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="container-fluid py-4">
                    <!-- Breadcrumbs -->
                    <div class="row mb-4">
                        <div class="col-12">
                          
                            {{ Breadcrumbs::render('user.dashboard') }}
                        </div>
                    </div>
                
                    <!-- Stats Cards -->
                    <div class="row g-4">
    
                        <div class="col-lg-6 col-md-6">
                            <div class="card border-0 shadow-sm h-100 hover-effect">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h3 class="h3 card-title text-muted mb-0">
                                          <a href="{{route('incoming_request_list')}}"> Incoming Requests</a> 
                                        </h3>
                                        <div class="bg-danger bg-opacity-10 p-2 rounded status-card">
                                            <h5 class="card-title text-dark">{{$Total_Incoming}}</h5>
                                        </div>
                                    </div>
                                    
                                    <div class="row g-3 mt-3">

                                        <div class="col-4 mb-2">
                                            <div class="border rounded p-3 text-center status-card">
                                                <h6 class="text-muted">MIT</h6>
                                                <h4 class="fw-bold text-primary">{{$mitstatus}}</h4>
                                            </div>
                                        </div>

                                        <div class="col-4 mb-2">
                                            <div class="border rounded p-3 text-center status-card">
                                                <h6 class="text-muted">Received</h6>
                                                <h4 class="fw-bold text-warning">{{$Received_Status}}</h4>
                                            </div>
                                        </div>

                                        <div class="col-4">
                                            <div class="border rounded p-3 text-center status-card">
                                                <h6 class="text-muted">Pending</h6>
                                                <h4 class="fw-bold text-info">{{$Pending_Status}}</h4>
                                            </div>
                                        </div>

                                        <div class="col-4">
                                            <div class="border rounded p-3 text-center status-card">
                                                <h6 class="text-muted">Decline</h6>
                                                <h4 class="fw-bold text-danger">{{$Decline_Status}}</h4>
                                            </div>
                                        </div>

                                        <div class="col-4">
                                            <div class="border rounded p-3 text-center status-card">
                                                <h6 class="text-muted">Approve</h6>
                                                <h4 class="fw-bold text-danger">{{$Approve_Status}}</h4>
                                            </div>
                                        </div>

                                        <div class="col-4">
                                            <div class="border rounded p-3 text-center status-card">
                                                <h6 class="text-muted">Query</h6>
                                                <h4 class="fw-bold text-danger">{{$Query_Status}}</h4>
                                            </div>
                                        </div>


                                    </div>

                                </div>
                             
                            </div>
                        </div>
                        
                        <!-- Outgoing Requests Card -->
                        <div class="col-lg-6 col-md-6">
                            <div class="card border-0 shadow-sm h-100 hover-effect">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="card-title text-muted mb-0 text-center">
                                            <a href="{{route('raised_requests.index')}}">Raised Requests</a>
                                        </h5>
                                        <div class="bg-info bg-opacity-10 p-2 rounded status-card">
                                            <h5 class="card-title text-dark">{{$Total_Raised}}</h5>
                                        </div>
                                    </div>
                                    
                                    <div class="row g-3 mt-3">

                                        <div class="col-4 mb-2">
                                            <div class="border rounded p-3 text-center status-card">
                                                <h6 class="text-muted">MIT</h6>
                                                <h4 class="fw-bold text-primary">{{$mit_raised}}</h4>
                                            </div>
                                        </div>

                                        <div class="col-4 mb-2">
                                            <div class="border rounded p-3 text-center status-card">
                                                <h6 class="text-muted">Received</h6>
                                                <h4 class="fw-bold text-warning">{{$Received_raised}}</h4>
                                            </div>
                                        </div>

                                        <div class="col-4">
                                            <div class="border rounded p-3 text-center status-card">
                                                <h6 class="text-muted">Pending</h6>
                                                <h4 class="fw-bold text-info">{{$Pending_raised}}</h4>
                                            </div>
                                        </div>

                                        <div class="col-4">
                                            <div class="border rounded p-3 text-center status-card">
                                                <h6 class="text-muted">Decline</h6>
                                                <h4 class="fw-bold text-danger">{{$Decline_raised}}</h4>
                                            </div>
                                        </div>

                                        <div class="col-4">
                                            <div class="border rounded p-3 text-center status-card">
                                                <h6 class="text-muted">Approve</h6>
                                                <h4 class="fw-bold text-danger">{{$Approve_raised}}</h4>
                                            </div>
                                        </div>

                                        <div class="col-4">
                                            <div class="border rounded p-3 text-center status-card">
                                                <h6 class="text-muted">Query</h6>
                                                <h4 class="fw-bold text-danger">{{$Query_raised}}</h4>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                
                <style>
                    .hover-effect {
                        transition: all 0.3s ease;
                        border-radius: 10px;
                        border: 1px solid var(--bs-border-color);
                    }
                    .hover-effect:hover {
                        transform: translateY(-5px);
                        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
                        border-color: var(--bs-border-color-translucent);
                    }
                    .card-title {
                        font-size: 1.1rem;
                        font-weight: 600;
                    }
                    .status-card {
                        transition: all 0.2s ease;
                        cursor: pointer;
                        background-color: var(--bs-body-bg);
                    }
                    .status-card:hover {
                        transform: translateY(-2px);
                        box-shadow: 0 4px 8px rgba(0,0,0,0.05);
                        background-color: var(--bs-light-bg-subtle);
                    }
                    .status-card h4 {
                        transition: all 0.2s ease;
                    }
                    .status-card:hover h4 {
                        transform: scale(1.05);
                    }
                </style>
                
                <div class="col-lg-8 col-md-12 col-sm-12">
                    <div class="card card-block card-stretch card-height">
                        <div class="card-header">
                            <h4 class="card-title">Stock Movement Trends </h4>
                        </div>
                        <div class="card-body">
                            <div class="btn-group mb-3">
                                <button class="btn btn-sm btn-outline-primary time-period-btn active" data-period="weekly">Weekly</button>
                                <button class="btn btn-sm btn-outline-primary time-period-btn" data-period="monthly">Monthly</button>
                                <button class="btn btn-sm btn-outline-primary time-period-btn" data-period="yearly">Yearly</button>
                            </div>
                            <div id="stockMovementChart" style="width:100%; height:500px;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-12 col-sm-12">
                    <div class="card card-block card-stretch card-height">
                        <div class="card-header">
                            <h4 class="card-title">Overview Of Stock Inventory</h4>
                        </div>
                        <div class="card-body">
                            <div id="stockPieChart" style="width: 100%; height: 400px;"></div>
                            
                            <!-- Stock Summary Table -->
                            {{-- <div class="mt-3">
                                <table class="table table-sm table-borderless">
                                    <tbody>
                                        <tr>
                                            <td><span class="badge" style="background-color: #ff9770">&nbsp;&nbsp;</span> Used Stock</td>
                                            <td class="text-end">@json($usedStock) items</td>
                                            <td class="text-end">@json($usedPercent)%</td>
                                        </tr>
                                        <tr>
                                            <td><span class="badge" style="background-color: #7ee2ff">&nbsp;&nbsp;</span> New Stock</td>
                                            <td class="text-end">@json($newStock) items</td>
                                            <td class="text-end">@json($newPercent)%</td>
                                        </tr>
                                        <tr class="table-light">
                                            <td><strong>Total Inventory</strong></td>
                                            <td class="text-end"><strong>@json($newStock + $usedStock) items</strong></td>
                                            <td class="text-end"><strong>100%</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div> --}}
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>












@endsection