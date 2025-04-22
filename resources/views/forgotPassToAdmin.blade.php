<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password</title>

    <link rel="shortcut icon" href="{{ asset('resources/images/favicon.ico') }}" />
    <link rel="stylesheet" href="{{ asset('resources/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('resources/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('resources/css/loginstyle.css') }}">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
</head>

<body class="bg-light">
    <section class="p-3 p-md-4 p-xl-5">
        <div class="container">
            <div class="login-card">
                <div class="login-header">
                    <div class="brand-logo back-link text-decoration-none d-flex align-items-center">
                        <img src="{{ asset('resources/images/login/key.svg') }}" alt="Admin Icon" class="brand-logo-img">
                    </div>
                    <h4 class="text-center">Forgot Password </h4>
                </div>
            
            </div>
        </div>
        
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">^

                $(document).ready(function () {
                    // Automatically fade out alerts after 3 seconds
                    setTimeout(function () {
                        $(".alert").fadeOut("slow");
                    }, 3000);
                });
        </script>
</body>

</html>
