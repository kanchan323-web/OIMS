@extends('layouts.frontend.admin_layout')
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
</style>
    <div class="content-page">
    {{-- {{Breadcrumbs::render('Admin.dashboard')}} --}}
            <div class="container-fluid ">
       
                <div class="row ">
    
                    <div class="col-lg-12 col-md-12">
                        <div class="card border-0 shadow-sm h-100 hover-effect">
                            <div class="card-body p-2">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h3 class="h3 card-title text-muted mb-0">
                                      <a href="{{route('admin.stock_list.get')}}">  Requests</a> 
                                    </h3>
                                    <div class="">
                                        
                                        <a href="{{route('admin.stock_list.get')}}">  <h5 class=" p-1 text-dark">{{$Total_Incoming}}</h5></a> 
                                    </div>
                                </div>
                                
                                <div class="row g-3 mt-1">

                                    <div class="col-4">
                                        <div class="border rounded p-1 text-center status-card">
                                            <h6 class="text-muted">Pending</h6>
                                            <h4 class="fw-bold text-info">{{$Pending_Status}}</h4>
                                        </div>
                                    </div>
                                    
                                    <div class="col-4">
                                        <div class="border rounded p-1 text-center status-card">
                                            <h6 class="text-muted">Approve</h6>
                                            <h4 class="fw-bold text-danger">{{$Approve_Status}}</h4>
                                        </div>
                                    </div>
                                   
                                    <div class="col-4 mb-1 ">
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
                                            <h6 class="text-muted">Query</h6>
                                            <h4 class="fw-bold text-danger">{{$Query_Status}}</h4>
                                        </div>
                                    </div>

                                    <div class="col-4">
                                        <div class="border rounded p-1 text-center status-card">
                                            <h6 class="text-muted">Decline</h6>
                                            <h4 class="fw-bold text-danger">{{$Decline_Status}}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <!-- Page end  -->


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
                
                            <h5 class="card-title mb-2">Section Received/Decline Report</h5>
                
                            <!-- Filter Section -->
                            <div class="compact-filter-container">
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
                                
                                <button id="compact-reset-filter" class="compact-btn reset" style="background-color: #E08DB4">Reset</button>
                            </div>
                
                            <div id="compact-chart-container"></div>
                
                            <script>
                                // Dynamic data from controller
                                const compactSampleData = @json($chartData);
                
                                // Initialize Highcharts chart
                                const compactChart = Highcharts.chart('compact-chart-container', {
                                    chart: { 
                                        type: 'bar',
                                        height: 280,
                                        spacing: [10, 10, 10, 10]
                                    },
                                    title: { text: '' },
                                    credits: { enabled: false },
                                    exporting: { enabled: false },
                                    xAxis: {
                                        categories: [],
                                        title: { 
                                            text: 'Sections',
                                            style: { fontSize: '12px', fontWeight: 'bold' }
                                        },
                                        labels: { style: { fontSize: '11px' } }
                                    },
                                    yAxis: {
                                        min: 0,
                                        title: { 
                                            text: 'Count',
                                            style: { fontSize: '12px', fontWeight: 'bold' }
                                        },
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
                                                formatter: function() { return this.y; },
                                                style: { fontSize: '10px', textOutline: 'none' }
                                            },
                                            pointWidth: 18,
                                            events: { contextmenu: function(e) { e.preventDefault(); } }
                                        },
                                        bar: { borderWidth: 0 }
                                    },
                                    series: [
                                        { name: 'Declined', data: [], color: '#FF9770' },
                                        { name: 'Received', data: [], color: '#32BDEA' }
                                    ],
                                    tooltip: {
                                        formatter: function() {
                                            return `<b>${this.x}</b><br/>
                                                    ${this.series.name}: ${this.y}<br/>
                                                    Total: ${this.point.stackTotal}`;
                                        }
                                    }
                                });
                
                                // Filter logic
                                function updateCompactChart(startDate = null, endDate = null) {
                                    let filteredData = compactSampleData;
                
                                    if (startDate && endDate) {
                                        filteredData = compactSampleData.filter(item => {
                                            const itemDate = new Date(item.date);
                                            return itemDate >= new Date(startDate) && itemDate <= new Date(endDate);
                                        });
                                    }
                
                                    const sectionTotals = {};
                
                                    filteredData.forEach(item => {
                                        if (!sectionTotals[item.section]) {
                                            sectionTotals[item.section] = { accept: 0, decline: 0 };
                                        }
                                        sectionTotals[item.section].accept += parseInt(item.accept);
                                        sectionTotals[item.section].decline += parseInt(item.decline);
                                    });
                
                                    const sections = Object.keys(sectionTotals);
                                    const acceptData = sections.map(section => sectionTotals[section].accept);
                                    const declineData = sections.map(section => sectionTotals[section].decline);
                
                                    compactChart.update({
                                        xAxis: { categories: sections },
                                        series: [
                                            { data: declineData },
                                            { data: acceptData }
                                        ]
                                    });
                                }
                
                                // Load initial chart with full data
                                updateCompactChart();
                
                                // Flatpickr setup
                                const compactStartDatePicker = flatpickr("#compact-start-date", {
                                    dateFormat: "Y-m-d",
                                    maxDate: new Date(),
                                    onChange: updateChartIfBothDatesSelected
                                });
                
                                const compactEndDatePicker = flatpickr("#compact-end-date", {
                                    dateFormat: "Y-m-d",
                                    maxDate: new Date(),
                                    onChange: updateChartIfBothDatesSelected
                                });
                
                                function updateChartIfBothDatesSelected() {
                                    const start = document.getElementById('compact-start-date').value;
                                    const end = document.getElementById('compact-end-date').value;
                                    if (start && end) {
                                        updateCompactChart(start, end);
                                    }
                                }
                
                                // Preset time range change
                                document.getElementById('compact-preset-filter').addEventListener('change', function() {
                                    const preset = this.value;
                                    const today = new Date();
                                    let startDate = new Date(today);
                
                                    if (!preset) return;
                
                                    if (preset === 'custom') {
                                        compactStartDatePicker.clear();
                                        compactEndDatePicker.clear();
                                        return;
                                    }
                
                                    switch (preset) {
                                        case 'today':
                                            break;
                                        case 'week':
                                            startDate.setDate(today.getDate() - 7);
                                            break;
                                        case 'month':
                                            startDate.setDate(today.getDate() - 30);
                                            break;
                                        case 'year':
                                            startDate.setFullYear(today.getFullYear() - 1);
                                            break;
                                    }
                
                                    const formattedStart = startDate.toISOString().split('T')[0];
                                    const formattedEnd = today.toISOString().split('T')[0];
                
                                    compactStartDatePicker.setDate(formattedStart);
                                    compactEndDatePicker.setDate(formattedEnd);
                                    updateCompactChart(formattedStart, formattedEnd);
                                });
                
                                // Reset filter
                                document.getElementById('compact-reset-filter').addEventListener('click', function() {
                                    document.getElementById('compact-preset-filter').value = '';
                                    compactStartDatePicker.clear();
                                    compactEndDatePicker.clear();
                                    updateCompactChart();
                                });
                            </script>
                        </div>
                    </div>
                </div>
        
                <div class="col-lg-6 col-md-6 col-sm-12">
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
                
                        </div>
                    </div>
                </div>
               
            </div>
        </div>

      

    </div>
   
    
  
@endsection