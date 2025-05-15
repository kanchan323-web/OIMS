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
                                            <label for="dual-preset-filter">Time Range</label>
                                            <select id="dual-preset-filter" class="form-control form-control-sm">
                                                <option value="">Select period</option>
                                                <option value="today">Today</option>
                                                <option value="week">Last 7 Days</option>
                                                <option value="month">Last 30 Days</option>
                                                <option value="year">Last Year</option>
                                                <option value="custom">Custom Range</option>
                                            </select>
                                        </div>

                                        <div class="compact-filter-group">
                                            <label for="dual-start-date">From</label>
                                            <input type="text" id="dual-start-date" class="form-control form-control-sm"
                                                placeholder="Start date">
                                        </div>

                                        <div class="compact-filter-group">
                                            <label for="dual-end-date">To</label>
                                            <input type="text" id="dual-end-date" class="form-control form-control-sm"
                                                placeholder="End date">
                                        </div>

                                        <button id="dual-reset-filter" class="compact-btn reset"
                                            style="background-color: #E08DB4">Reset</button>
                                    </div>

                                    <!-- Dual Chart Container -->
                                    <div id="incoming-chart-container" style="height: 250px; margin-bottom: 30px;"></div>
                                    <div id="raised-chart-container" style="height: 250px;"></div>

                                    <script>
                                        const incomingData = @json($incomingChartData);
                                        const raisedData = @json($raisedChartData);

                                        let dualStartPicker = flatpickr("#dual-start-date", { dateFormat: "Y-m-d", maxDate: new Date() });
                                        let dualEndPicker = flatpickr("#dual-end-date", { dateFormat: "Y-m-d", maxDate: new Date() });

                                        function parseDate(str) {
                                            return str ? new Date(str + "T00:00:00") : null;
                                        }

                                        function filterDataByDate(data, start, end) {
                                            if (!start || !end) return data;
                                            return data.filter(item => {
                                                if (!item.date) return false;
                                                const date = parseDate(item.date);
                                                return date >= parseDate(start) && date <= parseDate(end);
                                            });
                                        }

                                        function buildChart(containerId, data, title) {
                                            const sectionTotals = {};
                                            data.forEach(item => {
                                                const section = item.section || 'Unknown';
                                                if (!sectionTotals[section]) sectionTotals[section] = { accept: 0, decline: 0, pending: 0 };
                                                sectionTotals[section].accept += parseInt(item.accept) || 0;
                                                sectionTotals[section].decline += parseInt(item.decline) || 0;
                                                sectionTotals[section].pending += parseInt(item.pending) || 0;
                                            });

                                            const sections = Object.keys(sectionTotals);
                                            const acceptData = sections.map(s => sectionTotals[s].accept);
                                            const declineData = sections.map(s => sectionTotals[s].decline);
                                            const pendingData = sections.map(s => sectionTotals[s].pending);

                                            Highcharts.chart(containerId, {
                                                chart: { type: 'bar', height: 280 },
                                                title: { text: title },
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
                                                    { name: 'Declined', data: declineData, color: '#FF9770' },
                                                    { name: 'Pending', data: pendingData, color: '#FFD670' },
                                                    { name: 'Received', data: acceptData, color: '#32BDEA' }
                                                ],
                                                tooltip: {
                                                    formatter: function () {
                                                        return `<br/>
                                                                                                ${this.series.name}: ${this.y}<br/>
                                                                                                Total: ${this.point.stackTotal}`;
                                                    }
                                                }
                                            });
                                        }

                                        function updateDualCharts() {
                                            const start = document.getElementById('dual-start-date').value;
                                            const end = document.getElementById('dual-end-date').value;
                                            const filteredIncoming = filterDataByDate(incomingData, start, end);
                                            const filteredRaised = filterDataByDate(raisedData, start, end);
                                            buildChart('incoming-chart-container', filteredIncoming, 'Incoming Requests');
                                            buildChart('raised-chart-container', filteredRaised, 'Raised Requests');
                                        }

                                        document.getElementById('dual-preset-filter').addEventListener('change', function () {
                                            const preset = this.value;
                                            const today = new Date();
                                            let start = new Date(today);

                                            switch (preset) {
                                                case 'today': break;
                                                case 'week': start.setDate(today.getDate() - 7); break;
                                                case 'month': start.setDate(today.getDate() - 30); break;
                                                case 'year': start.setFullYear(today.getFullYear() - 1); break;
                                                case 'custom':
                                                    dualStartPicker.clear();
                                                    dualEndPicker.clear();
                                                    return;
                                            }

                                            if (preset !== 'custom') {
                                                dualStartPicker.setDate(start);
                                                dualEndPicker.setDate(today);
                                                updateDualCharts();
                                            }
                                        });

                                        document.getElementById('dual-start-date').addEventListener('change', updateDualCharts);
                                        document.getElementById('dual-end-date').addEventListener('change', updateDualCharts);

                                        document.getElementById('dual-reset-filter').addEventListener('click', function () {
                                            document.getElementById('dual-preset-filter').value = '';
                                            dualStartPicker.clear();
                                            dualEndPicker.clear();
                                            buildChart('incoming-chart-container', incomingData, 'Incoming Requests');
                                            buildChart('raised-chart-container', raisedData, 'Raised Requests');
                                        });

                                        // Initial render
                                        buildChart('incoming-chart-container', incomingData, 'Incoming Requests');
                                        buildChart('raised-chart-container', raisedData, 'Raised Requests');
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

        </div>
    </div>






@endsection