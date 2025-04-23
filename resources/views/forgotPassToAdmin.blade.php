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
    <section class="mt-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
<div class="login-card">
            <div class="login-header">
                <div class="brand-logo back-link text-decoration-none d-flex align-items-center">
                    <img src="{{ asset('resources/images/login/key.svg') }}" alt="Admin Icon" class="brand-logo-img">
                </div>
                <h4 class="text-center">Contact Admin </h4>
            </div>
        </div>
        
        <div class="admin-table-container mt-4 p-4 rounded shadow-sm" style="background: #f8f9fa;">
            <div class="table-responsive">
                <table class="table table-hover admin-table">
                    <thead>
                        <tr class="bg-primary text-white">
                            <th class="py-3 ps-4 rounded-start">Username</th>
                            <th class="py-3">CPF Number</th>
                            <th class="py-3 pe-4 rounded-end">Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $admins = App\Models\User::where('user_type', 'admin')
                                        ->get(['user_name', 'cpf_no', 'email']);
                        @endphp
                        
                        @forelse($admins as $admin)
                            <tr class="border-bottom">
                                <td class="ps-4 fw-medium">{{ $admin->user_name }}</td>
                                <td>{{ $admin->cpf_no }}</td>
                                <td class="text-primary">{{ $admin->email }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted">
                                    <i class="fas fa-user-slash fa-2x mb-2"></i><br>
                                    No admin users found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
               
            </div>
            <div class="row">
                <div class="col-2 offset-10">
                    <a href="{{ route('user.login') }}" class="back-link text-decoration-none d-flex align-items-center">
                        <img src="{{ asset('resources/images/login/left-long.svg') }}" alt="Back" class="me-1" width="16" height="16">
                        Go Back
                    </a>
                </div>
            </div>
          
        </div>
            </div>
        </div>
        
    </div>
</section>

<style>
    .admin-table {
        border-collapse: separate;
        border-spacing: 0;
        min-width: 600px;
    }
    
    .admin-table th {
        font-weight: 500;
        letter-spacing: 0.5px;
        border: none;
    }
    
    .admin-table td {
        padding: 12px 16px;
        vertical-align: middle;
        background: white;
    }
    
    .admin-table tr:first-child th:first-child {
        border-top-left-radius: 8px;
    }
    
    .admin-table tr:first-child th:last-child {
        border-top-right-radius: 8px;
    }
    
    .admin-table tr:last-child td:first-child {
        border-bottom-left-radius: 8px;
    }
    
    .admin-table tr:last-child td:last-child {
        border-bottom-right-radius: 8px;
    }
    
    .admin-table tr:hover td {
        background-color: #f1f7ff;
        cursor: pointer;
        transition: all 0.2s;
    }
</style>
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
