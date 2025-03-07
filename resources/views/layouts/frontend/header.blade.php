<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title>{{ $moduleName ?? 'Dashboard' }} | POS Dash</title>

      <!-- Favicon -->
      <link rel="shortcut icon" href="{{ asset('resources/images/favicon.ico') }}" />
      <link rel="stylesheet" href="{{ asset('resources/css/backend-plugin.min.css') }}">
      <link rel="stylesheet" href="{{ asset('resources/vendor/@fortawesome/fontawesome-free/css/all.min.css') }}">
      <link rel="stylesheet" href="{{ asset('resources/vendor/line-awesome/dist/line-awesome/css/line-awesome.min.css') }}">
      <link rel="stylesheet" href="{{ asset('resources/vendor/remixicon/fonts/remixicon.css') }}">  </head>
      <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
      <meta name="csrf-token" content="{{ csrf_token() }}">
  
      <body class="  ">
    <!-- loader Start -->
    <div id="loading">
          <div id="loading-center">
          </div>
    </div>
    <!-- loader END -->
    <!-- Wrapper Start -->
    <div class="wrapper">
