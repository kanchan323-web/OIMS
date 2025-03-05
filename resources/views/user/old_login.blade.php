<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport"
        content="width=device-width,
                   initial-scale=1,
                   shrink-to-fit=no" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" />
    <title>Login Form</title>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://www.google.com/recaptcha/api.js?render={{ env('GOOGLE_RECAPTCHA_KEY') }}"></script>
</head>

<body>
    <h3 class="text-success text-center">
        Login
    </h3>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">

                        @if (Session::has('success'))
                            <div class="alert alert-success">

                                {{ Session::get('success') }}

                                @php

                                    Session::forget('success');

                                @endphp

                            </div>
                        @endif



                        <!-- Way 1: Display All Error Messages -->

                        @if ($errors->any())
                            <!--      <div class="alert alert-danger">

                                <strong>Whoops!</strong> There were some problems with your input.<br><br>

                            </div>
                    -->
                        @endif

                        <form method="POST" action="{{ route('login') }}" id="loginForm">
                            @csrf
                            <div class="form-group">
                                <label for="email">Email </label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="{{ old('email') }}" placeholder="Email"
                                    class="form-control @error('email') is-invalid @enderror" placeholder="Email" />
                                @error('email')
                                    <span class="text-danger" id="email_error">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Password"
                                    class="form-control @error('password') is-invalid @enderror" />
                                @error('password')
                                    <span class="text-danger " id="password_error">{{ $message }}</span>
                                @enderror
                            </div>

                            @if ($errors->has('g-recaptcha-response'))
                                <span class="text-danger">{{ $errors->first('g-recaptcha-response') }}</span>
                            @endif

                            <a href="{{ route('forgotpassword') }}" class="btn btn-default">Forgot Password</a>
                            <button class="btn btn-success btn-submit">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
