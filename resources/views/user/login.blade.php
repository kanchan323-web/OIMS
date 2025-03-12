<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>OIMS Login</title>
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>

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
                                    @if (Session::get('success'))
                                        <div class="alert bg-success text-white alert-dismissible fade show" role="alert">
                                            <strong>Success:</strong> {{ Session::get('success') }}
                                            {{-- <button type="button" class="close close-dark" data-dismiss="alert"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button> --}}
                                        </div>
                                    @endif

                                    @if (Session::get('error'))
                                        <div class="alert bg-danger text-white alert-dismissible fade show" role="alert">
                                            <strong>Error:</strong> {{ Session::get('error') }}
                                            {{-- <button type="button" class="close close-dark" data-dismiss="alert"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button> --}}
                                        </div>
                                    @endif

                                    <div class="mb-5">
                                        <h4 class="text-center"> Login Here</h4>
                                    </div>
                                </div>
                            </div>
                            <form action="{{ route('user.authenticate') }}" method='post' id="loginForm">
                                @csrf()
                                <div class="row gy-3 overflow-hidden">
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <input type="text"
                                                class="form-control @error('login') is-invalid @enderror " name="login"
                                                value="{{ old('login') }}" placeholder="Enter Email or Username"
                                                id="login">
                                            @error('login')
                                                <p class="invalid-feedback">{{ $message }}</p>
                                            @enderror
                                            <label for="login">Email or Username</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <input type="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                name="password" id="password" placeholder="Enter Password">
                                            @error('password')
                                                <p class="invalid-feedback">{{ $message }}</p>
                                            @enderror
                                            <label for="password">Password</label>
                                        </div>
                                    </div>
                                    <div class="form-group text-center">
                                        <label for="captcha">CAPTCHA</label>
                                        <div class="d-flex justify-content-center align-items-center">
                                            <input type="text" name="captcha" id="captcha"
                                                class="form-control @error('captcha') is-invalid @enderror"
                                                placeholder="Enter CAPTCHA" required>
                                            <img src="{{ captcha_src() }}" alt="CAPTCHA" class="ml-2 border rounded">
                                            <button type="button" class="btn btn-sm btn-light ml-2"
                                                onclick="refreshCaptcha()">
                                                🔄
                                            </button>
                                        </div>
                                        @error('captcha')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <div class="d-grid">
                                            <button class="btn bsb-btn-xl btn-primary py-3" type="submit">Log in
                                                now</button>
                                        </div>
                                        <a href="{{ route('forgotpassword') }}" class="btn btn-default">Forgot
                                            Password</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
        </script>
</body>

<script type="text/javascript">

    $("#password").keypress(function () {
        $('#password_error').css('display', 'none');
    });

    function refreshCaptcha() {
        document.querySelector('img[alt="CAPTCHA"]').src = "{{ captcha_src() }}" + "?" + Math.random();
    }

    $(document).ready(function () {
        // Automatically fade out alerts after 3 seconds
        setTimeout(function () {
            $(".alert").fadeOut("slow");
        }, 3000);
    });
</script>

</html>