<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>OIMS Admin Login</title>
    <link rel="shortcut icon" href="{{ asset('resources/images/favicon.ico') }}" />
    <link rel="stylesheet" href="{{ asset('resources/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('resources/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('resources/css/loginstyle.css') }}">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
   
</head>

<body class="bg-light">
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="login-card">
                        <div class="login-header">
                            <div class="brand-logo back-link text-decoration-none d-flex align-items-center">
                                <img src="{{ asset('resources/images/login/user-shield.svg') }}" alt="Admin Icon" class="brand-logo-img">
                            </div> 
                            <h4>OIMS Admin Login</h4>
                        </div>
                        <div class="login-body">
                            @if (Session::get('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fas fa-check-circle me-2"></i>
                                    <strong>Success:</strong> {{ Session::get('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            @if (Session::get('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="fas fa-exclamation-circle me-2"></i>
                                    <strong>Error:</strong> {{ Session::get('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <form action="{{ route('admin.authenticate') }}" method='post' id="adminLoginForm">
                                @csrf()
                                <div class="row gy-3 overflow-hidden">
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <img src="{{ asset('resources/images/login/user-tie.svg') }}" alt="key" class="me-1" width="16" height="16">
                                                </span>
                                                <input type="text" class="form-control @error('login') is-invalid @enderror" 
                                                    name="login" value="{{ old('login') }}" 
                                                    placeholder="Enter Email or Username" id="login" required>
                                                @error('login')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    
                                                    <img src="{{ asset('resources/images/login/key.svg') }}" alt="key" class="me-1" width="16" height="16">
                                                </span>
                                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                                    name="password" id="password" placeholder="Enter Password" required>
                                                <span class="input-group-text password-toggle" id="togglePassword">
                                                    <img src="{{ asset('resources/images/login/eye.svg') }}" alt="eye" class="me-1" width="16" height="16">

                                                </span>
                                                @error('password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="d-grid mb-3">
                                            <button class="btn btn-primary" type="submit">
                                                <img src="{{ asset('resources/images/login/check.svg') }}" alt="Help" class="me-1" width="16" height="16">
                                                Log In
                                              
                                            </button>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <a href="{{ route('forgotpassword') }}" class="forgot-link text-decoration-none">
                                                <img src="{{ asset('resources/images/login/question.svg') }}" alt="Help" class="me-1" width="16" height="16">
                                                Forgot Password?
                                            </a>
                                            <a href="{{ route('home') }}" class="back-link text-decoration-none d-flex align-items-center">
                                                <img src="{{ asset('resources/images/login/left-long.svg') }}" alt="Back" class="me-1" width="16" height="16">
                                                Go Back
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- now we are change --}}

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
    </script>

    <script type="text/javascript">
        $(document).ready(function () {
            // Toggle password visibility
            $('#togglePassword').click(function(){
                const password = $('#password');
                const icon = $(this).find('img');
                
                if(password.attr('type') === 'password'){
                    password.attr('type', 'text');
                    icon.attr('src', "{{ asset('resources/images/login/eye-slash.svg') }}");
                } else {
                    password.attr('type', 'password');
                    icon.attr('src', "{{ asset('resources/images/login/eye.svg') }}");
                }
            });

            // Automatically fade out alerts after 5 seconds
            setTimeout(function () {
                $(".alert").fadeOut("slow");
            }, 5000);
        });

        function refreshCaptcha() {
            document.querySelector('img[alt="CAPTCHA"]').src = "{{ captcha_src() }}" + "?" + Math.random();
        }
    </script>
</body>
</html>