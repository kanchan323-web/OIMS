<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>OIMS Login</title>
  <!--  <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
  -->
    <link rel="shortcut icon" href="{{ asset('resources/images/favicon.ico') }}" />
    <link rel="stylesheet" href="{{ asset('resources/css/backend-plugin.min.css') }}">
    <link rel="stylesheet" href="{{ asset('resources/vendor/@fortawesome/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('resources/vendor/line-awesome/dist/line-awesome/css/line-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('resources/css/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('resources/vendor/remixicon/fonts/remixicon.css') }}">  </head>
    <script src="{{ asset('resources/js/jquery-3.7.1.min.js') }}"></script>
</head>

<body class="bg-light">
    <section class=" p-3 p-md-4 p-xl-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-9 col-lg-7 col-xl-6 col-xxl-5">
                    <div class="card border border-light-subtle rounded-4">
                        <div class="card-body p-3 p-md-4 p-xl-5">
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-6">
                                        <h4 class="text-center">OIMS</h4>
                                        <div class="row" style="margin-top: 49px;">
                                        <div class="col-6">
                                                <a href="{{ route('admin.login') }}" class="svg-icon" style="font-size: larger;">
                                                    <svg class="svg-icon" id="p-dash10" width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><polyline points="17 11 19 13 23 9"></polyline>
                                                    </svg>
                                                    <span class="ml-1">Admin Login</span>
                                                </a>
                                            </div>
                                            <div class="col-6" style="text-align: right;">
                                                <a href="{{ route('user.login') }}" class="svg-icon" style="font-size: larger;">
                                                    <svg class="svg-icon" id="p-dash8" width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                                    </svg>
                                                    <span class="ml-1">User Login</span>
                                                </a>
                                            </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
