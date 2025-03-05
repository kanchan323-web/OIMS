<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>OIMS Login</title>
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://www.google.com/recaptcha/api.js?render={{ env('GOOGLE_RECAPTCHA_KEY') }}"></script>
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
                                        <div class="alert alert-success">{{ Session::get('success') }}</div>
                                    @endif

                                    @if (Session::get('error'))
                                        <div class="alert alert-danger">{{ Session::get('error') }}</div>
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
                                                class="form-control @error('email') is-invalid @enderror "
                                                name="email" value="{{ old('email') }}" placeholder="Enter Email Id"
                                                id="email">
                                            @error('email')
                                                <p class="invalid-feedback">{{ $message }}</p>
                                            @enderror
                                            <label for="email">Email Id</label>
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

                                    <input type="hidden"
                                        class="form-control @error('g-recaptcha-response') is-invalid @enderror"
                                        name="g-recaptcha-response" id="g-recaptcha-response">
                                    @if ($errors->has('g-recaptcha-response'))
                                        <span class="text-danger">{{ $errors->first('g-recaptcha-response') }}</span>
                                    @endif

                                    <div class="col-12">
                                        <div class="d-grid">
                                            <button class="btn bsb-btn-xl btn-primary py-3" type="submit">Log in
                                                now</button>
                                        </div>
                                        <!--   <a href="{{ route('forgotpassword') }}" class="btn btn-default">Forgot
                                            Password</a> -->
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
    $('#loginForm').submit(function(event) {
        event.preventDefault();
        grecaptcha.ready(function() {
            grecaptcha.execute("{{ env('GOOGLE_RECAPTCHA_KEY') }}", {
                action: 'subscribe_newsletter'
            }).then(function(token) {
                $('#loginForm').prepend(
                    '<input type="hidden" name="g-recaptcha-response" value="' + token +
                    '">');
                $('#loginForm').unbind('submit').submit();
            });;
        });
    });

    $("#email").keypress(function() {
        $('#email_error').css('display', 'none');
        /* debugger
         if($('#email_error').val() == ''){
         $('#email_error').css('display','block');
         }else{
             $('#email_error').css('display','none');
         }
             */
    });

    $("#password").keypress(function() {
        $('#password_error').css('display', 'none');
    });
</script>

</html>
