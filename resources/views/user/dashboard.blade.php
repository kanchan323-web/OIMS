@extends('layouts.frontend.layout')
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
                                            <p class="mb-2">Incoming Requests</p>
                                            <h4>{{$countIncomingRequest}}</h4>
                                        </div>
                                    </div>
                                    @php
                                        // Avoid division by zero
                                        $totalRequests = $countIncomingRequest + $RaisedRequests;
                                        $incomingPercentage = $totalRequests > 0 
                                            ? round(($countIncomingRequest / $totalRequests) * 100, 2) 
                                            : 0;
                                    @endphp
                                    <div class="iq-progress-bar mt-2">
                                        <span class="bg-warning iq-progress progress-1" data-percent="{{ $incomingPercentage }}" 
                                              style="width: {{ $incomingPercentage }}%;">
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
                                            <p class="mb-2">Pending Incoming Requests</p>
                                            <h4>{{$PendingIncomingRequest}}</h4>
                                        </div>
                                    </div>
                                    @php
                                        // Avoid division by zero
                                        $pendingPercentage = $countIncomingRequest > 0 
                                            ? round(($PendingIncomingRequest / $countIncomingRequest) * 100, 2) 
                                            : 0;
                                    @endphp
                                    <div class="iq-progress-bar mt-2">
                                        <span class="bg-info iq-progress progress-1" 
                                              data-percent="{{ $pendingPercentage }}" 
                                              style="width: {{ $pendingPercentage }}%;">
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
                                            <p class="mb-2">Raised Requests</p>
                                            <h4>{{$RaisedRequests}}</h4>
                                        </div>
                                    </div>
                                    @php
                                        // Avoid division by zero and calculate percentage dynamically
                                        $raisedPercentage = ($RaisedRequests + $countIncomingRequest) > 0 
                                            ? round(($RaisedRequests / ($RaisedRequests + $countIncomingRequest)) * 100, 2) 
                                            : 0;
                                    @endphp
                                    <div class="iq-progress-bar mt-2">
                                        <span class="bg-danger iq-progress progress-1" 
                                              data-percent="{{ $raisedPercentage }}" 
                                              style="width: {{ $raisedPercentage }}%;">
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
                                            <p class="mb-2">Pending Raised Requests</p>
                                            <h4>{{$RaisedRequestsRequests}}</h4>
                                        </div>
                                    </div>
                                    @php
                                        // Avoid division by zero and calculate percentage dynamically
                                        $pendingRaisedPercentage = ($RaisedRequests > 0) 
                                            ? round(($RaisedRequestsRequests / $RaisedRequests) * 100, 2) 
                                            : 0;
                                    @endphp
                                    <div class="iq-progress-bar mt-2">
                                        <span class="bg-success iq-progress progress-1" 
                                              data-percent="{{ $pendingRaisedPercentage }}" 
                                              style="width: {{ $pendingRaisedPercentage }}%;">
                                        </span>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card card-block card-stretch card-height">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Stock Level By Category</h4>
                            </div>

                        </div>
                        <div id="StockLevels"></div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="card card-block card-stretch card-height">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Percentage Of Total Stock</h4>
                            </div>

                        </div>
                        <div id="PercentageOfStock"></div>

                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="card card-block card-stretch card-height">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Percentage Of Total Stock</h4>
                            </div>

                        </div>
                        <div id="container" style="width:100%; height:500px;"></div>

                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card card-block card-stretch card-height">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title"> User with Most Stock Request</h4>
                            </div>

                        </div>

                        <div id="topUsersChart" style="width:100%; height:500px;"></div>

                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card card-block card-stretch card-height">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title"> Stock Requests</h4>
                            </div>

                        </div>

                        <div id="stockRequestsChart" style="width:100%; height:500px;"></div>

                    </div>
                </div>
               
                <div class="col-lg-8">
                    <div class="card card-block card-stretch card-height">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title"> Stock Comparison Imcoming and Raised Request</h4>
                            </div>

                        </div>
                        <div id="stockComparisonChart" style="width:100%; height:500px;"></div>

                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="card card-block card-stretch card-height">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title"> Stock Comparison Imcoming and Raised Request</h4>
                            </div>

                        </div>
                        <div id="combinedChartContainer" style="width:100%; height:600px;"></div>


                    </div>
                </div>
              

            </div>
            <!-- Page end  -->
        </div>
    </div>


    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Laravel data passed using Blade syntax
            const dailyData = @json($dailyAdditions).map(item => [new Date(item.date).getTime(), item.total_additions]);
            const weeklyData = @json($weeklyAdditions).map(item => [item.week, item.total_additions]);
            const monthlyData = @json($monthlyAdditions).map(item => [item.month, item.total_additions]);

            // Initialize Highcharts
            Highcharts.chart('container', {
                title: { text: 'Stock Additions Over Time', align: 'left' },
                subtitle: { text: 'Daily, Weekly, and Monthly Trade', align: 'left' },
                yAxis: { title: { text: 'Total Stock Additions' } },
                xAxis: { type: 'datetime', title: { text: 'Time Period' } },
                legend: { layout: 'vertical', align: 'right', verticalAlign: 'middle' },
                plotOptions: { series: { label: { connectorAllowed: false } } },
                series: [
                    { name: 'Daily Additions', data: dailyData },
                    { name: 'Weekly Additions', data: weeklyData },
                    { name: 'Monthly Additions', data: monthlyData }
                ],
                responsive: {
                    rules: [{
                        condition: { maxWidth: 500 },
                        chartOptions: { legend: { layout: 'horizontal', align: 'center', verticalAlign: 'bottom' } }
                    }]
                }
            });
        });
    </script>

    <script>
        (function (H) {
            H.seriesTypes.pie.prototype.animate = function (init) {
                const series = this,
                    chart = series.chart,
                    points = series.points,
                    { animation } = series.options,
                    { startAngleRad } = series;

                function fanAnimate(point, startAngleRad) {
                    const graphic = point.graphic,
                        args = point.shapeArgs;

                    if (graphic && args) {
                        graphic.attr({
                            start: startAngleRad,
                            end: startAngleRad,
                            opacity: 1
                        })
                            .animate({
                                start: args.start,
                                end: args.end
                            }, {
                                duration: animation.duration / points.length
                            }, function () {
                                if (points[point.index + 1]) {
                                    fanAnimate(points[point.index + 1], args.end);
                                }
                                if (point.index === series.points.length - 1) {
                                    series.dataLabelsGroup.animate({ opacity: 1 }, void 0, function () {
                                        points.forEach(point => { point.opacity = 1; });
                                        series.update({ enableMouseTracking: true }, false);
                                        chart.update({
                                            plotOptions: { pie: { innerSize: '40%', borderRadius: 8 } }
                                        });
                                    });
                                }
                            });
                    }
                }

                if (init) {
                    points.forEach(point => { point.opacity = 0; });
                } else {
                    fanAnimate(points[0], startAngleRad);
                }
            };
        }(Highcharts));

        var categoryData = @json($categoryPercentages);
        var categories_name = Object.keys(categoryData);
        var categories_values = Object.values(categoryData);


        var chartData = categories_name.map((name, index) => ({
            name: name,
            y: parseFloat(categories_values[index]) // Ensure values are numbers
        }));

        Highcharts.chart('PercentageOfStock', {
            chart: {
                type: 'pie'
            },
            title: {
                text: 'Stock Percentage by Category'
            },
            subtitle: {
                text: 'Custom animation of pie series'
            },
            tooltip: {
                headerFormat: '',
                pointFormat: '<span style="color:{point.color}">\u25cf</span> {point.name}: <b>{point.percentage:.1f}%</b>'
            },
            accessibility: {
                point: {
                    valueSuffix: '%'
                }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    borderWidth: 2,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b><br>{point.percentage:.2f}%',
                        distance: 20
                    }
                }
            },
            series: [{
                enableMouseTracking: false,
                animation: {
                    duration: 2000
                },
                colorByPoint: true,
                data: chartData
            }]
        });
    </script>


    <script>
        var categories_name = @json(array_keys($categoryCounts->toArray()));
        var categories_values = @json(array_values($categoryCounts->toArray()));

        console.log(categories_name);
        console.log(categories_values);

        Highcharts.chart('StockLevels', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Stock Category Count'
            },
            xAxis: {
                categories: categories_name,
                crosshair: true,
                accessibility: {
                    description: 'Stock Categories'
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Stock Count'
                }
            },
            tooltip: {
                valueSuffix: ' items'
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [
                {
                    name: 'Stock Count',
                    data: categories_values
                }
            ]
        });
    </script>

    {{-- Comparision --}}

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Laravel data passed using Blade syntax
            const categories = @json(array_keys($incomingStockCounts->toArray()));
            const incomingData = @json(array_values($incomingStockCounts->toArray()));
            const raisedData = @json(array_values($raisedStockCounts->toArray()));

            // Initialize Highcharts Bar Chart
            Highcharts.chart('stockComparisonChart', {
                chart: { type: 'bar' },
                title: { text: 'Comparison of Incoming vs Raised Requests' },
                xAxis: { categories: categories, title: { text: 'Stock Categories' } },
                yAxis: { title: { text: 'Number of Requests' } },
                legend: { layout: 'horizontal', align: 'center', verticalAlign: 'bottom' },
                series: [
                    { name: 'Incoming Requests', data: incomingData },
                    { name: 'Raised Requests', data: raisedData }
                ]
            });
        });
    </script>


<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Laravel data passed using Blade syntax
        const dailyDates = @json($dailyRequests->pluck('date')->toArray());
        const dailyCounts = @json($dailyRequests->pluck('total_requests')->toArray());

        const weeklyDates = @json($weeklyRequests->pluck('week')->toArray());
        const weeklyCounts = @json($weeklyRequests->pluck('total_requests')->toArray());

        const monthlyDates = @json($monthlyRequests->pluck('month')->toArray());
        const monthlyCounts = @json($monthlyRequests->pluck('total_requests')->toArray());

        // Function to create chart with selected type
        function createChart(type) {
            Highcharts.chart('stockRequestsChart', {
                chart: { type: type === 'step' ? 'line' : type },
                title: { text: 'Stock Requests Over Time' },
                xAxis: { categories: dailyDates, title: { text: 'Date' } },
                yAxis: { title: { text: 'Number of Requests' } },
                legend: { layout: 'horizontal', align: 'center', verticalAlign: 'bottom' },
                plotOptions: {
                    series: { step: type === 'step' } // Enable step mode only for Step Line
                },
                series: [
                    { name: 'Daily Requests', data: dailyCounts },
                    { name: 'Weekly Requests', data: weeklyCounts },
                    { name: 'Monthly Requests', data: monthlyCounts }
                ]
            });
        }

        // Initial Chart Load
        createChart('line');

        // Dropdown Change Event
        document.getElementById('chartType').addEventListener('change', function () {
            createChart(this.value);
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Laravel data passed using Blade syntax
        const userData = @json($topUsers);
        const chartData = userData.map(user => ({
            name: user.user_name, // Display user name
            y: 1 // Just a placeholder value to create pie slices
        }));

        // Initialize Highcharts Pie Chart
        Highcharts.chart('topUsersChart', {
            chart: { type: 'pie' },
            title: { text: 'Users with Stock Requests' },
            tooltip: { pointFormat: '<b>{point.name}</b>' },
            accessibility: { point: { valueSuffix: ' Users' } },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>'
                    }
                }
            },
            series: [{
                name: 'Users',
                colorByPoint: true,
                data: chartData
            }]
        });
    });
</script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
  
        // Process data for the charts
        var pieData = statusData.map(function(item) {
            return { name: item.status, y: item.count };
        });

        var lineData = dailyData.map(function(item) {
            return [new Date(item.date).getTime(), item.count];
        });

        var barCategories = weeklyData.map(function(item) {
            return 'Week ' + item.week;
        });

        var barData = weeklyData.map(function(item) {
            return item.count;
        });

        var topItemsCategories = topItemsData.map(function(item) {
            return item.item_name;
        });

        var topItemsDataSeries = topItemsData.map(function(item) {
            return item.total_requested;
        });

        // Combined Chart
        Highcharts.chart('combinedChartContainer', {
            title: {
                text: 'Stock Requests Overview'
            },
            xAxis: [{
                categories: barCategories,
                title: {
                    text: 'Weeks'
                },
                opposite: true
            }, {
                type: 'datetime',
                title: {
                    text: 'Date'
                }
            }],
            yAxis: [{
                title: {
                    text: 'Number of Requests'
                }
            }],
            series: [{
                type: 'pie',
                name: 'Request Status',
                data: pieData,
                center: [100, 80],
                size: 100,
                showInLegend: false,
                dataLabels: {
                    enabled: true
                }
            }, {
                type: 'line',
                name: 'Daily Requests',
                data: lineData,
                xAxis: 1
            }, {
                type: 'column',
                name: 'Weekly Requests',
                data: barData,
                xAxis: 0
            }, {
                type: 'bar',
                name: 'Top Requested Items',
                data: topItemsDataSeries,
                xAxis: 0,
                yAxis: 0,
                dataLabels: {
                    enabled: true,
                    format: '{point.y}'
                }
            }]
        });
    });
</script>

@endsection