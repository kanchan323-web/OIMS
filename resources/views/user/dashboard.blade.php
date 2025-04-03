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
                {{-- <div class="col-lg-4 col-md-12 col-sm-12">
                    <div class="card card-block card-stretch card-height">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Overview Of Stoks Levels</h4>
                            </div>

                        </div>
                        <div id="StockLevels"></div>
                    </div>
                </div> --}}
               
                <div class="col-lg-4 col-md-12 col-sm-12">
                    <div class="card card-block card-stretch card-height">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Overview Of  Percentage Stock</h4>
                            </div>

                        </div>
                        <div id="PercentageOfStock"></div>

                    </div>
                </div>
              
                {{-- <div class="col-lg-4 col-md-12 col-sm-12">
                    <div class="card card-block card-stretch card-height">
                        <div class="card-header zzd-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title"> Overview Of Stock Request</h4>
                            </div>

                        </div>

                        <div id="topUsersChart" style="width:100%; height:500px;"></div>

                    </div>
                </div> --}}
                {{-- <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="card card-block card-stretch card-height">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Overview Of Stock Comparison</h4>
                            </div>

                        </div>
                        <div id="stockComparisonChart" style="width:100%; height:500px;"></div>

                    </div>
                </div> --}}
                <div class="col-lg-8 col-md-12 col-sm-12">
                    <div class="card card-block card-stretch card-height">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Overview Of Stock Comparison</h4>
                            </div>

                        </div>
                 
                    <div id="combinedStockChart" style="width: 100%; height: 400px;"></div>

                    </div>
                </div>
             
              

            </div>
            <!-- Page end  -->
        </div>
    </div>

{{-- OK --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Get all data from Laravel
        const stockCategories = @json(array_keys($categoryCounts->toArray()));
        const stockValues = @json(array_values($categoryCounts->toArray()));
        const incomingData = @json(array_values($incomingStockCounts->toArray()));
        const raisedData = @json(array_values($raisedStockCounts->toArray()));
    
        // Create the combined chart
        Highcharts.chart('combinedStockChart', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Stock Analysis by Category'
            },
            xAxis: {
                categories: stockCategories,
                crosshair: true,
                title: {
                    text: 'Stock Categories'
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Count'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y}</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0,
                    grouping: true,  // Group columns together
                    groupPadding: 0.1
                }
            },
            series: [{
                name: 'Stock Count',
                data: stockValues,
                color: '#7CB5EC'  // Blue
            }, {
                name: 'Incoming Requests',
                data: incomingData,
                color: '#90ED7D'  // Green
            }, {
                name: 'Raised Requests',
                data: raisedData,
                color: '#F7A35C'  // Orange
            }],
            legend: {
                layout: 'horizontal',
                align: 'center',
                verticalAlign: 'bottom'
            }
        });
    });
    </script>
{{-- OK --}}
{{-- OK --}}
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
{{-- OK --}}

{{-- OK --}}
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
{{-- OK --}}

{{-- OK --}}
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
{{-- OK --}}





{{-- OK --}}
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
{{-- OK --}}


@endsection