<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" 
          content="width=device-width, 
                   initial-scale=1,
                   shrink-to-fit=no" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" />
    <title>Forgot Password Form</title>
</head>

<body>
    <h4 class="text-success text-center">
        Forgot Password
    </h4>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('submitpassword') }}">
                            @csrf
                            
                            <!-- Email Input -->
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}"
                                       placeholder="Enter your email" 
                                       required>
                                @error('email') 
                                    <span class="text-danger">{{ $message }}</span> 
                                @enderror
                            </div>

                            

                            <!-- Submit Button -->
                            <button class="btn btn-primary btn-block">
                                Send Reset Password Link
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
