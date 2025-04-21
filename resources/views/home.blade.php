<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>OIMS Login</title>
    <link rel="shortcut icon" href="{{ asset('resources/images/favicon.ico') }}" />
    <link rel="stylesheet" href="{{ asset('resources/css/backend-plugin.min.css') }}">
    <link rel="stylesheet" href="{{ asset('resources/vendor/@fortawesome/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('resources/vendor/line-awesome/dist/line-awesome/css/line-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('resources/css/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('resources/vendor/remixicon/fonts/remixicon.css') }}">
    <script src="{{ asset('resources/js/jquery-3.7.1.min.js') }}"></script>
    <style>
        .brand-logo {
            width: 70px;
            height: 70px;
            margin: 0 auto 1rem;
            border-radius: 50%;
            background-color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .brand-logo-img {
            width: 40px;
            height: 40px;
            color: var(--primary-color);
            fill: currentColor;
        }

        :root {
            --primary-color: #05b2e2;
            --secondary-color: #e8faff;
            /* light background to match */
            --accent-color: #63c7e2;
            /* main accent color */
            --text-color: #3a3d42;
            /* darker text for contrast */
        }

        body {
            background: linear-gradient(135deg, #f8f9fc 0%, #e2e8f0 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .login-card:hover {
            transform: translateY(-5px);
        }

        .login-header {
            background: var(--primary-color);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .login-header h4 {
            font-weight: 700;
            font-size: 2rem;
            margin: 0;
            letter-spacing: 1px;
        }

        .login-body {
            padding: 2.5rem;
            background-color: white;
        }

        .login-option {
            display: block;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-radius: 0.5rem;
            color: var(--text-color);
            text-decoration: none;
            transition: all 0.3s ease;
            border: 1px solid #e3e6f0;
            text-align: center;
        }

        .login-option:hover {
            background-color: var(--secondary-color);
            border-color: var(--primary-color);
            transform: translateY(-3px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        }

        .login-option i {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: var(--primary-color);
        }

        .login-option h5 {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .login-option p {
            color: #858796;
            font-size: 0.9rem;
            margin-bottom: 0;
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 1.5rem 0;
            color: #d1d3e2;
        }

        .divider::before,
        .divider::after {
            content: "";
            flex: 1;
            border-bottom: 1px solid #e3e6f0;
        }

        .divider-text {
            padding: 0 1rem;
            font-size: 0.8rem;
            text-transform: uppercase;
            color: #b7b9cc;
        }

        .brand-logo {
            width: 80px;
            height: 80px;
            margin-bottom: 1rem;
            border-radius: 50%;
            background-color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            margin: 0 auto 1.5rem;
        }

        .brand-logo i {
            font-size: 2.5rem;
            color: var(--primary-color);
        }

        .forgot-link:hover,
        .back-link:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        /* If you need to style the icons */
        .forgot-link img,
        .back-link img {
            filter: brightness(0) saturate(100%) invert(39%) sepia(57%) saturate(1559%) hue-rotate(202deg) brightness(93%) contrast(89%);
            /* Or if using currentColor in SVGs: */
            /* color: var(--primary-color); */
        }
    </style>
</head>

<body>
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="login-card">
                        <div class="login-header">
                            <div class="brand-logo back-link text-decoration-none d-flex align-items-center">

                                <img src="{{ asset('resources/images/login/lock.svg') }}" alt="Admin Icon"
                                    class="brand-logo-img">

                            </div>
                            <h4>OIMS Portal</h4>
                        </div>
                        <div class="login-body">
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <a href="{{ route('admin.login') }}" class="login-option">
                                        <div
                                            class="brand-logo back-link text-decoration-none d-flex align-items-center">
                                            <img src="{{ asset('resources/images/login/user-shield.svg') }}"
                                                alt="Admin Icon" class="brand-logo-img">
                                        </div>
                                        <h5>Admin Login</h5>
                                        <p>Access administrative controls and settings</p>
                                    </a>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <a href="{{ route('user.login') }}" class="login-option">
                                        <div
                                            class="brand-logo back-link text-decoration-none d-flex align-items-center">
                                            <img src="{{ asset('resources/images/login/user-tie.svg') }}"
                                                alt="Admin Icon" class="brand-logo-img">
                                        </div>
                                        <h5>User Login</h5>
                                        <p>Access your personal dashboard and features</p>
                                    </a>
                                </div>
                            </div>

                            {{-- <div class="divider">
                                <span class="divider-text">Or</span>
                            </div>

                            <div class="text-center mt-3">
                                <p class="small text-muted">Need help? <a href="#" class="text-primary">Contact
                                        support</a></p>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>