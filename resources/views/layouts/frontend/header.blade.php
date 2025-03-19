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
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
      <link rel="stylesheet" href="{{ asset('resources/vendor/remixicon/fonts/remixicon.css') }}">  </head>
      <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
      <meta name="csrf-token" content="{{ csrf_token() }}">

      <style>
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
        background-color: #007bff;
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
