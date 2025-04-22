<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title>{{ $moduleName ?? 'Dashboard' }} </title>

      <!-- Favicon -->
      <link rel="shortcut icon" href="{{ asset('resources/images/favicon.ico') }}" />
      <link rel="stylesheet" href="{{ asset('resources/css/backend-plugin.min.css') }}">
      <link rel="stylesheet" href="{{ asset('resources/vendor/@fortawesome/fontawesome-free/css/all.min.css') }}">
      <link rel="stylesheet" href="{{ asset('resources/vendor/line-awesome/dist/line-awesome/css/line-awesome.min.css') }}">
      <link rel="stylesheet" href="{{ asset('resources/css/sweetalert2.min.css') }}">
      <link rel="stylesheet" href="{{ asset('resources/vendor/remixicon/fonts/remixicon.css') }}">  </head>
      <link rel="stylesheet" href="{{ asset('resources/css/select2-bootstrap4.min.css') }}">

      <script src="{{ asset('resources/js/jquery-3.7.1.min.js') }}"></script>
      <script src="{{ asset('resources/js/select2.min.js') }}"></script>
      <script src="{{ asset('resources/js/highcharts.js') }}"></script>
      <script src="{{ asset('resources/js/accessibility.js') }}"></script>
      <script src="{{ asset('resources/js/export-data.js') }}"></script>
      <script src="{{ asset('resources/js/exporting.js') }}"></script>
      <script src="{{ asset('resources/js/variable-pie.js') }}"></script>

        {{-- datatable --}}
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="{{ asset('resources/css/dataTables.bootstrap5.min.css') }}">
    <!-- DataTables JS -->
    <script src="{{ asset('resources/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('resources/js/dataTables.bootstrap5.min.js') }}"></script>
    {{-- datatable --}}
      
      <meta name="csrf-token" content="{{ csrf_token() }}">

      <style>
    .highcharts-figure,
    .highcharts-data-table table {
    min-width: 320px;
    max-width: 100%;
    margin: 1.5em auto;
}

/* Data Table Styling */
.highcharts-data-table table {
    font-family: 'Poppins', sans-serif;
    border-collapse: collapse;
    border: 1px solid #ddd;
    width: 100%;
    max-width: 800px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    overflow: hidden;
    font-size: 0.85rem; /* Small Font */
}

/* Table Caption */
.highcharts-data-table caption {
    padding: 1em;
    font-size: 1rem;
    font-weight: 700;
    color: #333;
    background: #f8f9fa;
    border-bottom: 2px solid #ddd;
    text-transform: uppercase;
}

/* Table Header */
.highcharts-data-table th {
    font-weight: 600;
    background: #0056b3;
    color: white;
    padding: 0.6em;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.9rem;
}

/* Table Data Cells */
.highcharts-data-table td {
    padding: 0.6em;
    border-bottom: 1px solid #ddd;
    font-size: 0.85rem;
}

/* Zebra Striping */
.highcharts-data-table tbody tr:nth-child(even) {
    background: #f3f6ff;
}

/* Hover Effect */
.highcharts-data-table tbody tr:hover {
    background: #e9f5ff;
    transition: 0.3s ease-in-out;
}

/* Description */
.highcharts-description {
    margin: 0.5rem 15px;
    font-size: 0.85rem; /* Smaller Font */
    color: #666;
}

/* Responsive Design */
@media (max-width: 768px) {
    .highcharts-data-table table {
        font-size: 0.8rem;
    }
}


    .custom-file-upload {
        position: relative;
        width: 100%;
        max-width: 300px;
    }

    .file-input {
        display: none; /* Hide default input */
    }

    .file-label {
        display: block;
        padding: 12px 20px;
        background-color: #0044ff;
        color: #fff;
        text-align: center;
        font-weight: bold;
        border-radius: 8px;
        cursor: pointer;
        transition: 0.3s ease-in-out;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    }

    .file-label:hover {
        background-color: #0056b3;
    }

    .file-label:active {
        transform: scale(0.98);
    }
</style>


      <body class="  ">
    <!-- loader Start -->
    <div id="loading">
          <div id="loading-center">
          </div>
    </div>
    <!-- loader END -->
    <!-- Wrapper Start -->
    <div class="wrapper">
