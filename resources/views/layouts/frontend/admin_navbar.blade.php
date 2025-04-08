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


                        <li class="nav-item nav-icon dropdown">
                            <a href="#" class="search-toggle dropdown-toggle" id="dropdownMenuButton2"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-mail">
                                    <path
                                        d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                                    </path>
                                    <polyline points="22,6 12,13 2,6"></polyline>
                                </svg>
                                <span class="bg-primary"></span>
                            </a>
                            <div class="iq-sub-dropdown dropdown-menu" aria-labelledby="dropdownMenuButton2">
                                <div class="card shadow-none m-0">
                                    <div class="card-body p-0 ">
                                        <div class="cust-title p-3">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <h5 class="mb-0">All Messages</h5>
                                                <a class="badge badge-primary badge-card" href="#">3</a>
                                            </div>
                                        </div>
                                        <div class="px-3 pt-0 pb-0 sub-card">
                                            <a href="#" class="iq-sub-card">
                                                <div class="media align-items-center cust-card py-3 border-bottom">
                                                    <div class="">
                                                        <img class="avatar-50 rounded-small"
                                                            src="#" alt="01">
                                                    </div>
                                                    <div class="media-body ml-3">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <h6 class="mb-0">Emma Watson</h6>
                                                            <small class="text-dark"><b>12 : 47 pm</b></small>
                                                        </div>
                                                        <small class="mb-0">Lorem ipsum dolor sit amet</small>
                                                    </div>
                                                </div>
                                            </a>
                                            <a href="#" class="iq-sub-card">
                                                <div class="media align-items-center cust-card py-3 border-bottom">
                                                    <div class="">
                                                        <img class="avatar-50 rounded-small"
                                                            src="#" alt="02">
                                                    </div>
                                                    <div class="media-body ml-3">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <h6 class="mb-0">Ashlynn Franci</h6>
                                                            <small class="text-dark"><b>11 : 30 pm</b></small>
                                                        </div>
                                                        <small class="mb-0">Lorem ipsum dolor sit amet</small>
                                                    </div>
                                                </div>
                                            </a>
                                            <a href="#" class="iq-sub-card">
                                                <div class="media align-items-center cust-card py-3">
                                                    <div class="">
                                                        <img class="avatar-50 rounded-small"
                                                            src="#" alt="03">
                                                    </div>
                                                    <div class="media-body ml-3">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <h6 class="mb-0">Kianna Carder</h6>
                                                            <small class="text-dark"><b>11 : 21 pm</b></small>
                                                        </div>
                                                        <small class="mb-0">Lorem ipsum dolor sit amet</small>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <a class="right-ic btn btn-primary btn-block position-relative p-2" href="#"
                                            role="button">
                                            View All
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <!-- Notification Dropdown -->
                        <li class="nav-item nav-icon dropdown">
                            <a href="#" class="search-toggle dropdown-toggle" id="dropdownMenuButton"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell"></i>
                                <span id="notification-count" class="badge badge-primary notification-badge"
                                    style="display: none;">0</span>
                            </a>

                            <div class="iq-sub-dropdown dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <div class="card shadow-none m-0">
                                    <div class="card-body p-0">
                                        <div class="cust-title p-3">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <h5 class="mb-0">Notifications</h5>
                                                <a class="badge badge-primary badge-card"
                                                    id="notification-count-badge">0</a>
                                            </div>
                                        </div>
                                        <div class="px-3 pt-0 pb-0 sub-card" id="notification-list">
                                            <p class="text-center py-3">No new notifications</p>
                                        </div>
                                        <button class="btn btn-primary btn-block" data-toggle="modal"
                                            data-target="#notificationModal">
                                            View All
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </li>

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
                                            <h5 class="mb-1">{{ Auth::user()->email }}</h5>

                                            <div class="d-flex align-items-center justify-content-center mt-3">
                                                <a href="{{route('user.admin.profile')}}"
                                                    class="btn border mr-2">Profile</a>
                                                <a href="{{ route('admin.logout') }}" class="btn border">Sign
                                                    Out</a>
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

<!-- Notification Modal -->
<div class="modal fade" id="notificationModal" tabindex="-1" role="dialog" aria-labelledby="notificationModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">All Notifications</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="modal-notification-list">
                    <p class="text-center py-3">No new notifications</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button id="mark-all-read" class="btn btn-primary">Mark All as Read</button>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {
        function fetchNotifications() {
            $.ajax({
                url: "{{ route('notifications.fetch') }}",
                method: "GET",
                success: function (response) {
                    let notificationList = $("#notification-list");
                    let modalNotificationList = $("#modal-notification-list");
                    let notificationCount = $("#notification-count");
                    let notificationBadge = $("#notification-count-badge");

                    notificationList.empty();
                    modalNotificationList.empty();

                    // Populate Dropdown
                    if (response.dropdown_notifications.length > 0) {
                        response.dropdown_notifications.forEach(notification => {
                            let highlightClass = "text-primary font-weight-bold";

                            notificationList.append(`
                            <a href="javascript:void(0);" class="iq-sub-card mark-as-read ${highlightClass}"
                               data-id="${notification.id}" data-url="${notification.url}">
                                <div class="media align-items-center cust-card py-3 border-bottom">
                                    <div class="media-body ml-3">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <h6 class="mb-0">${notification.message}</h6>
                                            <small class="text-dark"><b>${notification.created_at}</b></small>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        `);
                        });
                    } else {
                        notificationList.append('<p class="text-center py-3">No new notifications</p>');
                    }

                    // Populate Modal
                    if (response.modal_notifications.length > 0) {
                        response.modal_notifications.forEach(notification => {
                            let highlightClass = "text-primary font-weight-bold";

                            modalNotificationList.append(`
                            <a href="javascript:void(0);" class="list-group-item list-group-item-action mark-as-read ${highlightClass}"
                               data-id="${notification.id}" data-url="${notification.url}">
                                <div class="d-flex justify-content-between">
                                    <span>${notification.message}</span>
                                    <small>${notification.created_at}</small>
                                </div>
                            </a>
                        `);
                        });
                    } else {
                        modalNotificationList.append('<p class="text-center py-3">No new notifications</p>');
                    }

                    // Update count
                    notificationBadge.text(response.unread_count).show();
                    notificationCount.text(response.unread_count).show();
                }
            });
        }

        fetchNotifications();
        setInterval(fetchNotifications, 5000);

        // Mark Individual Notification as Read
        $(document).on("click", ".mark-as-read", function (e) {
            e.preventDefault();
            let clickedElement = $(this);
            let notificationId = clickedElement.attr("data-id");
            let notificationUrl = clickedElement.attr("data-url");

            $.ajax({
                url: "{{ route('notifications.markReadAdmin') }}",
                method: "POST",
                data: { id: notificationId, _token: "{{ csrf_token() }}" },
                success: function () {
                    clickedElement.fadeOut(300, function () {
                        $(this).remove();
                        fetchNotifications();
                    });

                    if (notificationUrl && notificationUrl !== "null") {
                        window.location.href = notificationUrl;
                    }
                }
            });
        });

        // Mark All as Read
        $("#mark-all-read").click(function () {
            $.ajax({
                url: "{{ route('notifications.markAllReadAdmin') }}",
                method: "POST",
                data: { _token: "{{ csrf_token() }}" },
                success: function () {
                    $("#modal-notification-list").fadeOut(300, function () {
                        $(this).html('<p class="text-center py-3">No new notifications</p>').fadeIn();
                        fetchNotifications();
                    });
                }
            });
        });
    });

</script>