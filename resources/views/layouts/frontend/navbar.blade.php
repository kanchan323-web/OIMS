<div class="iq-top-navbar">
    <div class="iq-navbar-custom">
        <nav class="navbar navbar-expand-lg navbar-light p-0">
            <div class="iq-navbar-logo d-flex align-items-center justify-content-between">
                <i class="ri-menu-line wrapper-menu"></i>
                <a href="../backend/index.html" class="header-logo">
                    <img src="{{ asset('resources/images/logo.png') }}" class="img-fluid rounded-normal" alt="logo">
                    <h5 class="logo-title ml-3">POSDash</h5>

                </a>
            </div>

            @if (auth()->check())
                        @php
                            $user = Auth::user()->load('rig'); 
                        @endphp

                        <h5 class="mb-3">
                            Welcome {{ $user->email }}
                            @if($user->rig)
                                to {{ $user->rig->name }}
                            @else
                                (No Rig Assigned)
                            @endif
                        </h5>
            @endif


            <div class="d-flex align-items-center">
                <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-label="Toggle navigation">
                    <i class="ri-menu-3-line"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto navbar-list align-items-center">

                        <li class="nav-item nav-icon dropdown caption-content">
                            <a href="#" class="search-toggle dropdown-toggle" id="dropdownMenuButton4"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img src="{{ asset('resources/images/user/1.png') }}" class="img-fluid rounded"
                                    alt="user">
                            </a>
                            <div class="iq-sub-dropdown dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <div class="card shadow-none m-0">
                                    <div class="card-body p-0 text-center">
                                        <div class="media-body profile-detail text-center">
                                            <img src="{{ asset('resources/images/page-img/profile-bg.jpg') }}"
                                                alt="profile-bg" class="rounded-top img-fluid mb-4">
                                            <img src="{{ asset('resources/images/user/1.png') }}" alt="profile-img"
                                                class="rounded profile-img img-fluid avatar-70">
                                        </div>
                                        <div class="p-3">
                                            @if (auth()->check())
                                                <h5 class="mb-1">{{ Auth::user()->email }}</h5>
                                            @endif

                                            <div class="d-flex align-items-center justify-content-center mt-3">
                                                @if (auth()->guard('admin')->check())
                                                    <a href="../app/user-profile.html" class="btn border mr-2">Profile</a>
                                                    <a href="{{ route('admin.logout') }}" class="btn border">Sign
                                                        Out</a>
                                                @else
                                                    <a href="../app/user-profile.html" class="btn border mr-2">Profile</a>
                                                    <a href="{{ route('user.logout') }}" class="btn border">Sign
                                                        Out</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</div>