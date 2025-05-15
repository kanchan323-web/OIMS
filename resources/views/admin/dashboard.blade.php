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
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
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
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
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
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
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
                                    <a href="{{route('admin.stock_list.get')}}"> Requests</a>
                                </h3>
                                <div class="">

                                    <a href="{{route('admin.stock_list.get')}}">
                                        <h5 class=" p-1 text-dark">{{$Total_Incoming}}</h5>
                                    </a>
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
                                #compact-chart-container {
                                    height: 350px;
                                }

                                .compact-filter-container {
                                    display: flex;
                                    flex-wrap: wrap;
                                    gap: 10px;
                                    margin-bottom: 15px;
                                    align-items: flex-end;
                                }

                                .compact-filter-group {
                                    display: flex;
                                    flex-direction: column;
                                }

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

                                .compact-btn:hover {
                                    background-color: #3367d6;
                                }

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
                                    <input type="text" id="compact-start-date" class="form-control form-control-sm"
                                        placeholder="Start date">
                                </div>

                                <div class="compact-filter-group">
                                    <label for="compact-end-date">To</label>
                                    <input type="text" id="compact-end-date" class="form-control form-control-sm"
                                        placeholder="End date">
                                </div>

                                <button id="compact-reset-filter" class="compact-btn reset"
                                    style="background-color: #E08DB4">Reset</button>
                            </div>

                            <div id="compact-chart-container"></div>

                            <script>
                                const compactSampleData = @json($chartData);

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
                                                formatter: function () { return this.y; },
                                                style: { fontSize: '10px', textOutline: 'none' }
                                            },
                                            pointWidth: 18,
                                            events: { contextmenu: function (e) { e.preventDefault(); } }
                                        },
                                        bar: { borderWidth: 0 }
                                    },
                                    series: [
                                        { name: 'Declined', data: [], color: '#FF9770' },
                                        { name: 'Pending', data: [], color: '#FCD34D' },
                                        { name: 'Received', data: [], color: '#32BDEA' }
                                    ],
                                    tooltip: {
                                        formatter: function () {
                                            return `<br/>
                                                                        ${this.series.name}: ${this.y}<br/>
                                                                        Total: ${this.point.stackTotal}`;
                                        }
                                    }
                                });

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
                                            sectionTotals[item.section] = { accept: 0, decline: 0, pending: 0 };
                                        }
                                        sectionTotals[item.section].accept += parseInt(item.accept);
                                        sectionTotals[item.section].decline += parseInt(item.decline);
                                        sectionTotals[item.section].pending += parseInt(item.pending);
                                    });

                                    const sections = Object.keys(sectionTotals);
                                    const acceptData = sections.map(section => sectionTotals[section].accept);
                                    const declineData = sections.map(section => sectionTotals[section].decline);
                                    const pendingData = sections.map(section => sectionTotals[section].pending);

                                    compactChart.update({
                                        xAxis: { categories: sections },
                                        series: [
                                            { data: declineData },
                                            { data: pendingData },
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

                                document.getElementById('compact-preset-filter').addEventListener('change', function () {
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

                                document.getElementById('compact-reset-filter').addEventListener('click', function () {
                                    document.getElementById('compact-preset-filter').value = '';
                                    compactStartDatePicker.clear();
                                    compactEndDatePicker.clear();
                                    updateCompactChart();
                                });
                            </script>

                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 ">
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
                                    min-width: 100%;
                                }

                                .chart-title {
                                    text-align: center;
                                    font-weight: bold;
                                    margin-bottom: 10px;
                                    font-size: 16px;
                                }
                            </style>

                            <div class="dual-chart-container">
                                <div class="chart-box">

                                    <div id="stock-100-chart" style="height: 400px;"></div>
                                </div>
                            </div>

                            <script>
                                function formatIndianNumber(x) {
                                    var parts = x.toString().split(".");
                                    var lastThree = parts[0].slice(-3);
                                    var otherNumbers = parts[0].slice(0, -3);
                                    if (otherNumbers !== '') {
                                        lastThree = ',' + lastThree;
                                    }
                                    var res = otherNumbers.replace(/\B(?=(\d{2})+(?!\d))/g, ",") + lastThree;
                                    if (parts.length > 1) {
                                        res += "." + parts[1];
                                    }
                                    return res;
                                }

                                const sectionData = @json($combinedSections);

                                const categories = sectionData.map(item => item.section);

                                const usedStockData = sectionData.map(item => {
                                    const total = item.new + item.used;
                                    return {
                                        y: total === 0 ? 0.0001 : item.used,  // prevent zero-stack issue
                                        originalQty: item.used
                                    };
                                });

                                const newStockData = sectionData.map(item => {
                                    const total = item.new + item.used;
                                    return {
                                        y: total === 0 ? 0.0001 : item.new,  // prevent zero-stack issue
                                        originalQty: item.new
                                    };
                                });

                                Highcharts.chart('stock-100-chart', {
                                    chart: {
                                        type: 'column',
                                        height: 400
                                    },
                                    title: {
                                        text: 'Stock Distribution by Section (New vs Used)'
                                    },
                                    xAxis: {
                                        categories: categories,
                                        title: { text: 'Section' },
                                        labels: { style: { fontSize: '11px' } }
                                    },
                                    yAxis: {
                                        max: 100,
                                        title: {
                                            text: 'Percentage'
                                        },
                                        labels: {
                                            format: '{value}%'
                                        }
                                    },
                                    legend: {
                                        reversed: true,
                                        itemStyle: {
                                            fontSize: '12px'
                                        }
                                    },
                                    tooltip: {
                                        shared: true,
                                        useHTML: true,
                                        formatter: function () {
                                            let totalQty = 0;
                                            let tooltip = ``;

                                            this.points.forEach(point => {
                                                totalQty += point.point.originalQty;
                                            });

                                            if (totalQty === 0) {
                                                tooltip += `<div style="margin-left: 10px; color: #888;"><i>This section has no quantity.</i></div>`;

                                            } else {
                                                this.points.forEach(point => {
                                                    const percentage = ((point.point.originalQty / totalQty) * 100).toFixed(1);
                                                    tooltip += `<div style="margin-left: 10px">${point.series.name}: <b>${formatIndianNumber(point.point.originalQty)}</b> units (${percentage}%)</div>`;
                                                });
                                                tooltip += `<br/><b>Total: ${formatIndianNumber(totalQty)} units</b>`;
                                            }

                                            return tooltip;
                                        }
                                    },
                                    plotOptions: {
                                        column: {
                                            stacking: 'percent',
                                            dataLabels: {
                                                enabled: true,
                                                formatter: function () {
                                                    if (this.point.originalQty === 0) return null;
                                                    const percentage = this.percentage.toFixed(1);
                                                    return `${formatIndianNumber(this.point.originalQty)} (${percentage}%)`;
                                                },
                                                style: {
                                                    fontSize: '10px',
                                                    textOutline: 'none'
                                                }
                                            }
                                        }
                                    },
                                    series: [
                                        {
                                            name: 'Used',
                                            data: usedStockData,
                                            color: '#F4AAAA'
                                        },
                                        {
                                            name: 'New',
                                            data: newStockData,
                                            color: '#32BDEA'
                                        }
                                    ],
                                    credits: { enabled: false },
                                    exporting: { enabled: false }
                                });
                            </script>



                        </div>
                    </div>
                </div>

            </div>
        </div>



    </div>



@endsection