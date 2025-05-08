@extends('layouts.frontend.admin_layout')
@section('page-content')

<div class="container">
    <h1>Request Status Chart</h1>
    
    <div class="filter-container">
        <div class="filter-group">
            <label for="preset-filter">Quick Filters</label>
            <select id="preset-filter" class="form-control">
                <option value="">Select a preset</option>
                <option value="today">Today</option>
                <option value="week">Last 7 Days</option>
                <option value="month">Last 30 Days</option>
                <option value="year">Last Year</option>
                <option value="custom">Custom Range</option>
            </select>
        </div>
        
        <div class="filter-group">
            <label for="start-date">Start Date</label>
            <input type="text" id="start-date" class="form-control datepicker" placeholder="Select start date">
        </div>
        
        <div class="filter-group">
            <label for="end-date">End Date</label>
            <input type="text" id="end-date" class="form-control datepicker" placeholder="Select end date">
        </div>
        
        <button id="apply-filter" class="btn btn-primary">Apply Filter</button>
        <button id="reset-filter" class="btn btn-danger">Reset</button>
    </div>
    
    <div id="chartContainer" style="height: 500px; min-width: 100%; margin-top: 20px;"></div>
</div>



<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<script>
$(document).ready(function() {
    // Initialize date pickers
    const startDatePicker = flatpickr("#start-date", {
        dateFormat: "Y-m-d",
        maxDate: new Date()
    });
    
    const endDatePicker = flatpickr("#end-date", {
        dateFormat: "Y-m-d",
        maxDate: new Date()
    });

    // Initialize chart
    const chart = Highcharts.chart('chartContainer', {
        chart: { type: 'column' },
        title: { text: 'Request Status by Section' },
        xAxis: {
            categories: [],
            title: { text: 'Sections' }
        },
        yAxis: {
            min: 0,
            title: { text: 'Count' },
            stackLabels: {
                enabled: true,
                style: {
                    fontWeight: 'bold'
                }
            }
        },
        legend: { 
            reversed: true,
            align: 'right',
            verticalAlign: 'top'
        },
        plotOptions: {
            column: { 
                stacking: 'normal',
                dataLabels: {
                    enabled: true,
                    formatter: function() {
                        return this.y > 0 ? this.series.name + ': ' + this.y : '';
                    },
                    style: {
                        fontWeight: 'bold',
                        textOutline: 'none'
                    }
                }
            }
        },
        series: [
            {
                name: 'MIT',
                data: [],
                color: '#51cf66' // Green
            },
            {
                name: 'Pending',
                data: [],
                color: '#ff6b6b' // Red
            }
        ],
        tooltip: {
            formatter: function() {
                return `<b>${this.x}</b><br/>
                        ${this.series.name}: ${this.y}<br/>
                        Total: ${this.point.stackTotal}`;
            }
        }
    });

    // Load initial data
    loadChartData();

    // Function to load chart data via AJAX
    function loadChartData(startDate = null, endDate = null) {
        $.ajax({
            url: '/chart-data',
            type: 'GET',
            data: {
                start_date: startDate,
                end_date: endDate
            },
            success: function(response) {
                updateChart(response);
            },
            error: function(xhr) {
                console.error('Error fetching chart data:', xhr.responseText);
            }
        });
    }

    // Function to update chart with new data
    function updateChart(data) {
        const sections = data.sections;
        const pendingData = [];
        const mitData = [];
        
        // Prepare data for chart
        sections.forEach(section => {
            pendingData.push(data.pendingData[section].reduce((a, b) => a + b, 0));
            mitData.push(data.mitData[section].reduce((a, b) => a + b, 0));
        });
        
        chart.update({
            xAxis: {
                categories: sections
            },
            series: [
                { data: mitData },
                { data: pendingData }
            ]
        });
    }

    // Event listeners
    $('#preset-filter').change(function() {
        const preset = $(this).val();
        const today = new Date();
        
        if (!preset) return;
        
        if (preset === 'custom') {
            startDatePicker.clear();
            endDatePicker.clear();
            return;
        }
        
        let startDate = new Date(today);
        
        switch(preset) {
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
        
        startDatePicker.setDate(startDate);
        endDatePicker.setDate(today);
    });

    $('#apply-filter').click(function() {
        const startDate = $('#start-date').val();
        const endDate = $('#end-date').val();
        
        if (startDate && endDate) {
            loadChartData(startDate, endDate);
        } else {
            alert('Please select both start and end dates');
        }
    });

    $('#reset-filter').click(function() {
        $('#preset-filter').val('');
        startDatePicker.clear();
        endDatePicker.clear();
        loadChartData(); // Load all data
    });
});
</script>

<style>
.filter-container { 
    display: flex; 
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 20px;
    align-items: flex-end;
}
.filter-group { 
    display: flex; 
    flex-direction: column;
    min-width: 200px;
}
.highcharts-data-label text {
    font-weight: bold;
}
</style>
@endpush
    
@endsection