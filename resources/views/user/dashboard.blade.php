@extends('layouts.frontend.layout')
@section('page-content')
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
    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="container-fluid py-4">
                    <!-- Stats Cards -->
                    <div class="row g-4">
                        <div class="col-lg-6 col-md-6">
                            <div class="card border-0 shadow-sm h-100 hover-effect">
                                <div class="card-body p-2">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h3 class="h3 card-title text-muted mb-0">
                                          <a href="{{route('incoming_request_list')}}"> Incoming Requests</a> 
                                        </h3>
                                        <div class="  status-card">
                                            <h5 class="card-title text-dark">{{$Total_Incoming}}</h5>
                                        </div>
                                    </div>
                                    
                                    <div class="row g-3 mt-1">

                                        <div class="col-4 mb-1">
                                            <div class="border rounded p-1 text-center status-card">
                                                <h6 class="text-muted">MIT</h6>
                                                <h4 class="fw-bold text-primary">{{$mitstatus}}</h4>
                                            </div>
                                        </div>

                                        <div class="col-4 mb-1">
                                            <div class="border rounded p-1 text-center status-card">
                                                <h6 class="text-muted">Received</h6>
                                                <h4 class="fw-bold text-warning">{{$Received_Status}}</h4>
                                            </div>
                                        </div>

                                        <div class="col-4">
                                            <div class="border rounded p-1 text-center status-card">
                                                <h6 class="text-muted">Pending</h6>
                                                <h4 class="fw-bold text-info">{{$Pending_Status}}</h4>
                                            </div>
                                        </div>

                                        <div class="col-4">
                                            <div class="border rounded p-1 text-center status-card">
                                                <h6 class="text-muted">Decline</h6>
                                                <h4 class="fw-bold text-danger">{{$Decline_Status}}</h4>
                                            </div>
                                        </div>

                                        <div class="col-4">
                                            <div class="border rounded p-1 text-center status-card">
                                                <h6 class="text-muted">Approve</h6>
                                                <h4 class="fw-bold text-danger">{{$Approve_Status}}</h4>
                                            </div>
                                        </div>

                                        <div class="col-4">
                                            <div class="border rounded p-1 text-center status-card">
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
                                <div class="card-body p-1">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h5 class="card-title text-muted mb-0 text-center">
                                            <a href="{{route('raised_requests.index')}}">Raised Requests</a>
                                        </h5>
                                        <div class="status-card text-center">
                                            <h5 class="card-title text-dark">{{$Total_Raised}}</h5>
                                        </div>
                                    </div>
                                    
                                    <div class="row g-3 mt-1">

                                        <div class="col-4 mb-1">
                                            <div class="border rounded p-1 text-center status-card">
                                                <h6 class="text-muted">MIT</h6>
                                                <h4 class="fw-bold text-primary">{{$mit_raised}}</h4>
                                            </div>
                                        </div>

                                        <div class="col-4 mb-1">
                                            <div class="border rounded p-1 text-center status-card">
                                                <h6 class="text-muted">Received</h6>
                                                <h4 class="fw-bold text-warning">{{$Received_raised}}</h4>
                                            </div>
                                        </div>

                                        <div class="col-4">
                                            <div class="border rounded p-1 text-center status-card">
                                                <h6 class="text-muted">Pending</h6>
                                                <h4 class="fw-bold text-info">{{$Pending_raised}}</h4>
                                            </div>
                                        </div>

                                        <div class="col-4">
                                            <div class="border rounded p-1 text-center status-card">
                                                <h6 class="text-muted">Decline</h6>
                                                <h4 class="fw-bold text-danger">{{$Decline_raised}}</h4>
                                            </div>
                                        </div>

                                        <div class="col-4">
                                            <div class="border rounded p-1 text-center status-card">
                                                <h6 class="text-muted">Approve</h6>
                                                <h4 class="fw-bold text-danger">{{$Approve_raised}}</h4>
                                            </div>
                                        </div>

                                        <div class="col-4">
                                            <div class="border rounded p-1 text-center status-card">
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

                <div class="container-fluid mt-3">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <style>
                                        #compact-chart-container { height: 350px; }
                                        .compact-filter-container { 
                                            display: flex; 
                                            flex-wrap: wrap;
                                            gap: 10px;
                                            margin-bottom: 15px;
                                            align-items: flex-end;
                                        }
                                        .compact-filter-group { display: flex; flex-direction: column; }
                                        .compact-filter-group label { 
                                            margin-bottom: 3px; 
                                            font-weight: bold;
                                            font-size: 12px;
                                        }
                                        .compact-filter-group select, 
                                        .compact-filter-group input,
                                        .compact-btn {
                                            padding: 6px 10px;
                                            font-size: 12px;
                                            border-radius: 3px;
                                        }
                                        .compact-filter-group input {
                                            width: 120px;
                                        }
                                        .compact-btn {
                                            background-color: #4285f4;
                                            color: white;
                                            border: none;
                                            cursor: pointer;
                                            height: 32px;
                                        }
                                        .compact-btn:hover { background-color: #3367d6; }
                                        .compact-btn.reset {
                                            background-color: #f44336;
                                        }
                                        .compact-btn.reset:hover {
                                            background-color: #d32f2f;
                                        }
                                        .highcharts-data-label text {
                                            font-size: 10px;
                                            font-weight: bold;
                                        }
                                    </style>
                        
                                    <h5 class="card-title mb-2">Section Acceptance/Decline Report</h5>
                                    
                                    <!-- Filter Section -->
                                    {{-- <div class="compact-filter-container">
                                        <div class="compact-filter-group">
                                            <label for="compact-preset-filter">Time Range</label>
                                            <select id="compact-preset-filter" class="form-control form-control-sm">
                                                <option value="">Select period</option>
                                                <option value="today">Today</option>
                                                <option value="week">Last 7 Days</option>
                                                <option value="month">Last 30 Days</option>
                                                <option value="year">Last Year</option>
                                                <option value="custom">Custom Range</option>
                                            </select>
                                        </div>
                                        
                                        <div class="compact-filter-group">
                                            <label for="compact-start-date">From</label>
                                            <input type="text" id="compact-start-date" class="form-control form-control-sm" placeholder="Start date">
                                        </div>
                                        
                                        <div class="compact-filter-group">
                                            <label for="compact-end-date">To</label>
                                            <input type="text" id="compact-end-date" class="form-control form-control-sm" placeholder="End date">
                                        </div>
                                        
                                        <button id="compact-apply-filter" class="compact-btn">Apply</button>
                                        <button id="compact-reset-filter" class="compact-btn reset">Reset</button>
                                    </div>
                                     --}}
                                    {{-- <div id="compact-chart-container"></div> --}}
                        
                               
                                  <!-- Dual Chart Container -->
                                 

                                  <div id="incoming-chart-container" style="height: 250px; margin-bottom: 30px;"></div>
                                  <div id="raised-chart-container" style="height: 250px;"></div>

                                  <script>
                                    const incomingData = @json($incomingChartData);
                                    const raisedData = @json($raisedChartData);
                                
                                    function buildChart(containerId, data, chartTitle) {
                                        const sectionTotals = {};
                                
                                        data.forEach(item => {
                                            if (!sectionTotals[item.section]) {
                                                sectionTotals[item.section] = { accept: 0, decline: 0 };
                                            }
                                            sectionTotals[item.section].accept += parseInt(item.accept);
                                            sectionTotals[item.section].decline += parseInt(item.decline);
                                        });
                                
                                        const sections = Object.keys(sectionTotals);
                                        const acceptData = sections.map(section => sectionTotals[section].accept);
                                        const declineData = sections.map(section => sectionTotals[section].decline);
                                
                                        Highcharts.chart(containerId, {
                                            chart: {
                                                type: 'bar',
                                                height: 280
                                            },
                                            title: { text: chartTitle },
                                            credits: { enabled: false },
                                            exporting: { enabled: false },
                                            xAxis: {
                                                categories: sections,
                                                title: { text: 'Sections' },
                                                labels: { style: { fontSize: '11px' } }
                                            },
                                            yAxis: {
                                                min: 0,
                                                title: { text: 'Count' },
                                                labels: { style: { fontSize: '11px' } }
                                            },
                                            legend: {
                                                reversed: true,
                                                align: 'right',
                                                verticalAlign: 'top',
                                                itemStyle: { fontSize: '11px' }
                                            },
                                            plotOptions: {
                                                series: {
                                                    stacking: 'normal',
                                                    dataLabels: {
                                                        enabled: true,
                                                        formatter: function () {
                                                            return this.y;
                                                        },
                                                        style: { fontSize: '10px', textOutline: 'none' }
                                                    }
                                                }
                                            },
                                            series: [
                                                { name: 'Declined', data: declineData, color: '#ff6b6b' },
                                                { name: 'Accepted', data: acceptData, color: '#51cf66' }
                                            ],
                                            tooltip: {
                                                formatter: function () {
                                                    return `<b>${this.x}</b><br/>
                                                            ${this.series.name}: ${this.y}<br/>
                                                            Total: ${this.point.stackTotal}`;
                                                }
                                            }
                                        });
                                    }
                                
                                    // Initialize both charts
                                    buildChart('incoming-chart-container', incomingData, 'Incoming Requests');
                                    buildChart('raised-chart-container', raisedData, 'Raised Requests');
                                </script>
                                
                                </div>
                            </div>
                        </div>
                
                        <div class="col-lg-6"> 
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <style>
                                        .dual-chart-container { 
                                            display: flex;
                                            flex-wrap: wrap;
                                            gap: 20px;
                                            justify-content: space-between;
                                        }
                                        .chart-box {
                                            height: 350px;
                                            min-width: 48%;
                                            flex: 1 1 45%;
                                        }
                                        @media (max-width: 768px) {
                                            .chart-box {
                                                min-width: 100%;
                                            }
                                        }
                                        .chart-title {
                                            text-align: center;
                                            font-weight: bold;
                                            margin-bottom: 10px;
                                        }
                                    </style>
                        
                                        <div class="dual-chart-container">
                                            <!-- New Sections Chart -->
                                            <div class="chart-box">
                                                <div class="chart-title">New Sections Distribution</div>
                                                <div id="new-sections-chart"></div>
                                            </div>
                                            
                                            <!-- Used Sections Chart -->
                                            <div class="chart-box">
                                                <div class="chart-title">Used Sections Distribution</div>
                                                <div id="used-sections-chart"></div>
                                            </div>
                                        </div>
                        
                        
                        
                        
                                </div>
                            </div>
                        </div>
                       
                    </div>
                </div>

            </div>

        </div>
    </div>

    <script>
        const newSections = @json($newSections);
        const usedSections = @json($usedSections);
    
        const donutConfig = {
            chart: { type: 'pie' },
            title: { text: '' },
            tooltip: {
                pointFormat: '<b>{point.percentage:.1f}%</b> ({point.y} units)'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        // ✅ Show only section name inside chart
                        format: '{point.name}',
                        distance: -40,
                        style: {
                            fontWeight: 'bold',
                            fontSize: '11px',
                            textOutline: 'none'
                        }
                    },
                    showInLegend: true,
                    innerSize: '60%'
                }
            },
            legend: {
                labelFormatter: function () {
                    // ✅ Legend shows: section name + quantity
                    return `${this.name}: ${this.y} units`;
                },
                itemStyle: {
                    fontWeight: 'normal',
                    fontSize: '12px'
                }
            },
            credits: { enabled: false },
            exporting: { enabled: false }
        };
    
        Highcharts.chart('new-sections-chart', {
            ...donutConfig,
            series: [{
                name: 'New Sections',
                data: newSections.map(item => ({
                    ...item,
                    name: item.name.split(' - ')[0] // remove " - Qty: ..."
                }))
            }]
        });
    
        Highcharts.chart('used-sections-chart', {
            ...donutConfig,
            series: [{
                name: 'Used Sections',
                data: usedSections.map(item => ({
                    ...item,
                    name: item.name.split(' - ')[0]
                }))
            }]
        });
    </script>




@endsection