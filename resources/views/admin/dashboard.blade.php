@extends('layouts.frontend.admin_layout')
@section('page-content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-3 col-md-3">
                            <div class="card card-block card-stretch card-height">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-4 card-total-sale">
                                        <div>
                                            <p class="mb-2">Total Stock</p>
                                            <h4>{{ $totalStock }}</h4>
                                        </div>
                                    </div>
                                
                                    @php
                                        $StockDataLevel = max($totalEDP - $totalStock, 0);
                                        $GetStockLevelData = ($totalEDP > 0) ? round(($StockDataLevel / $totalEDP) * 100, 2) : 0;
                                    @endphp
                                        <div class="iq-progress-bar mt-2">
                                            <span class="bg-warning iq-progress progress-1" data-percent="{{ $GetStockLevelData }}" 
                                                >
                                            </span>
                                        </div>
                                    
                                </div>
                                
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3">
                            <div class="card card-block card-stretch card-height">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-4 card-total-sale">
                                        <div>
                                            <p class="mb-2">Total Request</p>
                                            <h4>{{$totalRequester}}</h4>
                                        </div>
                                    </div>
                                    <div class="iq-progress-bar mt-2">
                                        <span class="bg-info iq-progress progress-1" data-percent="85">
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3">
                            <div class="card card-block card-stretch card-height">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-4 card-total-sale">
                                        <div>
                                            <p class="mb-2">Escalation (Urgent Requests)</p>
                                            <h4>{{ $PendingIncomingRequest }}</h4>
                                        </div>
                                    </div>
                        
                                    @php
                                        $EscalationPercentage = ($totalRequester > 0) 
                                            ? round(($PendingIncomingRequest / $totalRequester) * 100, 2) 
                                            : 0;
                                    @endphp

                                    <div class="iq-progress-bar mt-2">
                                        <span class="bg-danger iq-progress progress-1" data-percent="{{ $EscalationPercentage }}" 
                                            >
                                        </span>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3">
                            <div class="card card-block card-stretch card-height">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-4 card-total-sale">
                                        <div>
                                            <p class="mb-2">Total Users</p>
                                            <h4>{{ $totalUser }}</h4>
                                        </div>
                                    </div>
                        
                                    @php
                                        // Total number of all users (admin + non-admin)
                                        
                                        
                                        // Ensure no division by zero
                                        $userPercentage = ($allUsers > 0) 
                                            ? round(($totalUser / $allUsers) * 100, 2) 
                                            : 0;
                                    @endphp
                        
                                    <div class="iq-progress-bar mt-2">
                                        <span class="bg-gray iq-progress progress-1" data-percent="{{ $userPercentage }}" 
                                            >
                                        </span>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>

                <div class="col-lg-4 col-md-12 col-sm-12">
                    <div class="card card-block card-stretch card-height">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Rig Overview</h4>
                            </div>

                        </div>
                        <div class="card-body">
                            <div id="container" style="width: 100%; height: 400px;"></div> 
                        </div>
                    </div>
                </div>  
                <div class="col-lg-4 col-md-12 col-sm-12">
                    <div class="card card-block card-stretch card-height">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">User Overview</h4>
                            </div>

                        </div>
                        <div class="card-body">
                            <div id="userChart" style="width:100%; height:400px;"></div>
                        </div>
                    </div>
                </div>  
                <div class="col-lg-4 col-md-12 col-sm-12">
                    <div class="card card-block card-stretch card-height">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Master Overview</h4>
                            </div>

                        </div>
                        
                        <div class="card-body">
                        <div id="edpsCategoriesChart" style="width:100%; height:400px;"></div>

                        </div>
                    </div>
                </div>  
                {{-- <div class="col-lg-6">
                    <div class="card card-block card-stretch card-height">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Revenue Vs Cost</h4>
                            </div>
                            <div class="card-header-toolbar d-flex align-items-center">
                                <div class="dropdown">
                                    <span class="dropdown-toggle dropdown-bg btn" id="dropdownMenuButton002"
                                        data-toggle="dropdown">
                                        This Month<i class="ri-arrow-down-s-line ml-1"></i>
                                    </span>
                                    <div class="dropdown-menu dropdown-menu-right shadow-none"
                                        aria-labelledby="dropdownMenuButton002">
                                        <a class="dropdown-item" href="#">Yearly</a>
                                        <a class="dropdown-item" href="#">Monthly</a>
                                        <a class="dropdown-item" href="#">Weekly</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="layout1-chart-2" style="min-height: 360px;"></div>
                        </div>
                    </div>
                </div> --}}
            </div>
            <!-- Page end  -->
        </div>
    </div>

    <script> 
        document.addEventListener("DOMContentLoaded", function () {  
            var rigData = @json($rigOverview);
        
            var chartData = rigData.map(item => ({
                name: `Location ${item.location_id} - ${item.name}`,
                y: item.total_rigs
            }));
        
            Highcharts.chart('container', {
                chart: {
                    type: 'pie'
                },
                title: {
                    text: 'Rigs Overview by Location'
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                accessibility: {
                    point: {
                        valueSuffix: '%'
                    }
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                        }
                    }
                },
                series: [{
                    name: 'Rigs',
                    data: chartData
                }]
            });
        });
        </script>
<style>
    
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var edpsData = @json($edpsSeries);
        var categoriesData = @json($categoriesSeries);

        var dates = edpsData.map(item => item.date);
        var edpsCounts = edpsData.map(item => item.count);
        var categoriesCounts = categoriesData.map(item => item.count);

        Highcharts.chart('edpsCategoriesChart', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Number of EDPs and Categories Added Over Time'
            },
            xAxis: {
                categories: dates,
                crosshair: true,
                title: {
                    text: 'Date'
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Count'
                }
            },
            tooltip: {
                shared: true,
                useHTML: true,
                headerFormat: '<b>{point.key}</b><br>',
                pointFormat: '{series.name}: {point.y}<br>'
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [{
                name: 'EDPs',
                data: edpsCounts
            }, {
                name: 'Categories',
                data: categoriesCounts
            }]
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var rigData = @json($rigData);

        Highcharts.chart('userChart', {
            chart: {
                type: 'variablepie'
            },
            title: {
                text: 'User Distribution by Rig'
            },
            tooltip: {
                useHTML: true,
                formatter: function() {
                    var userList = this.point.userNames.join('<br/>');
                    return '<b>' + this.point.name + '</b>: ' + this.y + ' users<br/><br/>' + userList;
                }
            },
            series: [{
                minPointSize: 10,
                innerSize: '20%',
                zMin: 0,
                name: 'Rigs',
                data: rigData
            }]
        });
    });
</script>
    
    
@endsection