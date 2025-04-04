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
                                        $totalRequests = $countIncomingRequest + $RaisedRequests;
                                        $incomingPercentage = $totalRequests > 0
                                            ? round(($countIncomingRequest / $totalRequests) * 100, 2)
                                            : 0;
                                    @endphp
                                    <div class="iq-progress-bar mt-2">
                                        <span class="bg-warning iq-progress progress-1"
                                            data-percent="{{ $incomingPercentage }}"
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
                                        $pendingPercentage = $countIncomingRequest > 0
                                            ? round(($PendingIncomingRequest / $countIncomingRequest) * 100, 2)
                                            : 0;
                                    @endphp
                                    <div class="iq-progress-bar mt-2">
                                        <span class="bg-info iq-progress progress-1" data-percent="{{ $pendingPercentage }}"
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
                                        $raisedPercentage = ($RaisedRequests + $countIncomingRequest) > 0
                                            ? round(($RaisedRequests / ($RaisedRequests + $countIncomingRequest)) * 100, 2)
                                            : 0;
                                    @endphp
                                    <div class="iq-progress-bar mt-2">
                                        <span class="bg-danger iq-progress progress-1"
                                            data-percent="{{ $raisedPercentage }}" style="width: {{ $raisedPercentage }}%;">
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
                        </div>
                    </div>
                </div>




              
              
            </div>

        </div>
    </div>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Data from Laravel
            const stockData = {
                weekly: @json($weeklyStockData), 
                monthly: @json($monthlyStockData),
                yearly: @json($yearlyStockData)
            };

            console.log("Weekly data:", stockData.weekly);
            console.log("Monthly data:", stockData.monthly);
            console.log("Yearly data:", stockData.yearly);
            
            // Initialize chart
            const chart = Highcharts.chart('stockMovementChart', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Stock Movement by Category'
                },
             
                xAxis: {
                    type: 'category',
                    title: {
                        text: 'Time Period'
                    },
                    crosshair: true
                },
                yAxis: {
                    title: {
                        text: 'Stock Quantity'
                    },
                    min: 0
                },
                plotOptions: {
                    column: {
                        stacking: 'normal',
                        pointPadding: 0.2,
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            formatter: function() {
                                return this.y > 0 ? this.y : '';
                            }
                        }
                    }
                },
                tooltip: {
                    headerFormat: '<b>{series.name}</b><br/>',
                    pointFormat: '{point.x}: <b>{point.y}</b> units'
                },
                series: [] // Will be populated dynamically
            });
        
            // Function to update chart based on selected period
            function updateChart(period) {
                // Remove existing series
                while (chart.series.length > 0) {
                    chart.series[0].remove(false);
                }
                
                // Add new series for each category
                Object.keys(stockData[period]).forEach(category => {
                    chart.addSeries({
                        name: category,
                        data: stockData[period][category],
                        color: getCategoryColor(category)
                    }, false);
                });
                
                // Update chart title and redraw
                chart.setTitle({ text: `Stock Movement (${period.charAt(0).toUpperCase() + period.slice(1)})` });
                chart.redraw();
            }
            
            // Color mapping function
            function getCategoryColor(category) {
                const colors = {
                    'Spares': '#7cb5ec',
                    'Stores': '#434348',
                    'Capital Item': '#90ed7d'
                };
                return colors[category] || Highcharts.getOptions().colors[0];
            }
            
            // Time period selector
            document.querySelectorAll('.time-period-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const period = this.dataset.period;
                    console.log(period);
                    
                    // Update button states
                    document.querySelectorAll('.time-period-btn').forEach(b => 
                        b.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Update chart
                    updateChart(period);
                });
            });
            
            // Initialize with weekly data
            updateChart('weekly');
        });
        </script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Get data from Laravel
        const totalStock = @json($totalStock);
        const pendingRaisedRequests = @json($RaisedRequestsRequests);

        // Calculate percentages
        const availableStock = totalStock - pendingRaisedRequests;
        const availablePercent = totalStock > 0 ? (availableStock / totalStock * 100).toFixed(1) : 0;
        const pendingPercent = totalStock > 0 ? (pendingRaisedRequests / totalStock * 100).toFixed(1) : 0;

        // Create the pie chart
        Highcharts.chart('stockPieChart', {
            chart: {
                type: 'pie',
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: 'Stock  Inventory Status',
                align: 'left'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b> ({point.y} items)'
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
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                        distance: -50,
                        filter: {
                            property: 'percentage',
                            operator: '>',
                            value: 4
                        }
                    },
                    showInLegend: true
                }
            },
            series: [{
                name: 'Stock',
                colorByPoint: true,
                data: [{
                    name: 'Own Inventory',
                    y: availableStock,
                    percentage: parseFloat(availablePercent),
                    color: '#7ee2ff' // Green
                }, {
                    name: 'Suppliers Inventory',
                    y: pendingRaisedRequests,
                    percentage: parseFloat(pendingPercent),
                    color: '#ff9770' // Orange
                }]
            }],
            credits: {
                enabled: true
            }
        });
    });
</script>









@endsection