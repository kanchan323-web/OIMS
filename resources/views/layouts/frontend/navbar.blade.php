<div class="iq-top-navbar">
    <div class="iq-navbar-custom">
        <nav class="navbar navbar-expand-lg navbar-light p-0">
            <div class="iq-navbar-logo d-flex align-items-center justify-content-between">
                <i class="ri-menu-line wrapper-menu"></i>

            </div>

            <div class="welcome-header ">
                @if(auth()->check())
                    @php
                        $user = Auth::user()->load('rig');
                    @endphp

                    <div class="d-flex align-items-baseline">
                        <span class="text-muted mr-2">
                            <i class="fas fa-user text-primary mr-1"></i>Welcome,
                        </span>
                        <span class="user-info">
                            <span class="role-badge text-uppercase small bg-light-primary text-primary px-2 py-1 rounded mr-2">USER</span>
                            <strong class="username">{{ $user->user_name }}</strong>
                        </span>
                        @if($user->rig)
                            <span class="rig-info ml-3 pl-3 border-left">
                                <i class="fas fa-hard-hat text-secondary mr-1"></i>
                                <span class="font-weight-medium">Rig Name: </span>
                                <span class="text-muted">({{ $user->rig->name }})</span>
                            </span>
                        @endif
                    </div>
                @endif
            </div>



            <div class="d-flex align-items-center">
                <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-label="Toggle navigation">
                    <i class="ri-menu-3-line"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto navbar-list align-items-center">
                        <li>
                           <a href="{{route('incomingPndding_request.get')}}" data-toggle="tooltip" data-placement="top" data-original-title="Incomming Pendding Request">
                             <svg class="svg-icon" id="p-dash2" width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="9" cy="21" r="1"></circle>
                                <circle cx="20" cy="21" r="1"></circle>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                             </svg>
                            <span id="notification-count" class="badge badge-primary notification-badge" style="">3</span></a>
                        </a>
                        </li>
                        <li>
                            <a href="{{route('raisedPenddingRequest.get')}}" data-toggle="tooltip" data-placement="top" data-original-title="Raised Pendding Request">
                              <svg class="svg-icon" id="p-dash2" width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                 <circle cx="9" cy="21" r="1"></circle>
                                 <circle cx="20" cy="21" r="1"></circle>
                                 <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                              </svg>
                             <span id="notification-count" class="badge badge-primary notification-badge" style="">3</span></a>
                         </a>
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
                                            @if (auth()->check())
                                                <h5 class="mb-1">{{ Auth::user()->email }}</h5>
                                            @endif
                                            <div class="d-flex align-items-center justify-content-center mt-3">
                                                <a href="{{route('user.profile')}}" class="btn border mr-2">Profile</a>
                                                <a href="{{ route('user.logout') }}" class="btn border">Sign
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
                url: "{{ route('notifications.markRead') }}",
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
                url: "{{ route('notifications.markAllRead') }}",
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
