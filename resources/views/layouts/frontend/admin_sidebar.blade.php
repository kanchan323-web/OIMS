<div class="iq-sidebar  sidebar-default ">
    <div class="iq-sidebar-logo d-flex align-items-center justify-content-between">
        <a href="{{ route('admin.dashboard') }}" class="header-logo">
            <img src="{{ asset('resources/images/logo.png') }}" class="img-fluid rounded-normal light-logo" alt="logo">
            <h5 class="logo-title light-logo ml-3">OIMS</h5>
        </a>
        <div class="iq-menu-bt-sidebar ml-4">
            <i class="las la-bars wrapper-menu"></i>
        </div>
    </div>
    <div class="data-scrollbar" data-scroll="1">
        <nav class="iq-sidebar-menu">
            <ul id="iq-sidebar-toggle" class="iq-menu">
                <li class="{{ Route::is('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}" class="svg-icon">
                        <svg class="svg-icon" id="p-dash1" width="20" height="20"
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z">
                            </path>
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                            <line x1="12" y1="22.08" x2="12" y2="12"></line>
                        </svg>
                        <span class="ml-4">Dashboards</span>
                    </a>
                </li>

                <li class="{{ Route::is('admin.rig_users.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.rig_users.index') }}" class="svg-icon">
                        <svg class="svg-icon" id="p-dash8" width="20" height="20"
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 21c-4.97-4.97-8-8.92-8-13a8 8 0 1 1 16 0c0 4.08-3.03 8.03-8 13z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        <span class="ml-4">Rigs</span>
                    </a>
                </li>

                <!--       <li class="{{ Route::is('admin.rig_users.index') || Route::is('admin.rig_users.create') ? 'active' : '' }}">
                    <a href="#rig" class="collapsed" data-toggle="collapse" aria-expanded="false">
                        <svg class="svg-icon" id="p-dash8" width="20" height="20" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 21c-4.97-4.97-8-8.92-8-13a8 8 0 1 1 16 0c0 4.08-3.03 8.03-8 13z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        <span class="ml-4">Rigs</span>
                        <svg class="svg-icon iq-arrow-right arrow-active" width="20" height="20"
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="10 15 15 20 20 15"></polyline>
                            <path d="M4 4h7a4 4 0 0 1 4 4v12"></path>
                        </svg>
                    </a>
                    <ul id="rig" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                        <li class=" ">
                            <a href="{{ route('admin.rig_users.index') }}">
                                <i class="las la-minus"></i><span>All Rig</span>
                            </a>
                        </li>
                        <li class="">
                            <a href="{{ route('admin.rig_users.create') }}">
                                <i class="las la-minus"></i><span>Add Rig</span>
                            </a>
                        </li>
                    </ul>
                </li>  -->

                <li class="{{ Route::is('admin.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.index') }}" class="svg-icon">
                        <svg class="svg-icon" id="p-dash8" width="20" height="20"
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        <span class="ml-4">Users</span>
                    </a>
                </li>

                <!--        <li class=" {{ Route::is('admin.index') || Route::is('admin.create') ? 'active' : '' }}">
                    <a href="#users" class="collapsed" data-toggle="collapse" aria-expanded="false">
                        <svg class="svg-icon" id="p-dash8" width="20" height="20" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        <span class="ml-4">Users</span>
                        <svg class="svg-icon iq-arrow-right arrow-active" width="20" height="20"
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="10 15 15 20 20 15"></polyline>
                            <path d="M4 4h7a4 4 0 0 1 4 4v12"></path>
                        </svg>
                    </a>
                    <ul id="users" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                        <li class="">
                            <a href="{{ route('admin.index') }}">
                                <i class="las la-minus"></i><span>All User</span>
                            </a>
                        </li>
                        <li class="">
                            <a href="{{ route('admin.create') }}">
                                <i class="las la-minus"></i><span>Add User</span>
                            </a>
                        </li>
                    </ul>
                </li> -->

                <li class="{{ Route::is('admin.edp.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.edp.index') }}" class="svg-icon">
                        <svg class="svg-icon" id="p-dash9" width="20" height="20"
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2">
                            </rect>
                            <rect x="7" y="7" width="3" height="9"></rect>
                            <rect x="14" y="7" width="3" height="5"></rect>
                        </svg>
                        <span class="ml-4">EDP</span>
                    </a>
                </li>

                <li class="{{ Route::is('admin.stock_list') ? 'active' : '' }}">
                    <a href="{{ route('admin.stock_list') }}" class="svg-icon">
                        <svg class="svg-icon" id="p-dash2" width="20" height="20"
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="9" cy="21" r="1"></circle>
                            <circle cx="20" cy="21" r="1"></circle>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                        </svg>
                        <span class="ml-4">Stock</span>
                    </a>
                </li>

                <li
                    class=" {{ Route::is('request_stock_list') || Route::is('request_stock_add') || Route::is('request_stock_add') ? 'active' : '' }}">
                    <a href="#request" class="collapsed" data-toggle="collapse" aria-expanded="false">
                        <svg class="svg-icon" id="p-dash3" width="20" height="20"
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                        </svg>
                        <span class="ml-4">Request Stock</span>
                        <svg class="svg-icon iq-arrow-right arrow-active" width="20" height="20"
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="10 15 15 20 20 15"></polyline>
                            <path d="M4 4h7a4 4 0 0 1 4 4v12"></path>
                        </svg>
                    </a>
                    <ul id="request" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                        <li class="">
                            <a href="{{ route(name: 'admin.stock_list.get') }}">
                                <i class="las la-minus"></i><span>All Stock Request List</span>
                            </a>
                        </li>
                        <li class="">
                            <a href="{{ route('admin.incoming_request_list') }}">
                                <i class="las la-minus"></i><span>Incoming Requests</span>
                            </a>
                        </li>
                        <li class="">
                            <a href="{{ route('admin.raised_requests.index') }}">
                                <i class="las la-minus"></i><span>Raised Requests</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="{{ Route::is('stocks') || Route::is('requests') ? 'active' : '' }}">
                    <a href="#report" class="collapsed" data-toggle="collapse" aria-expanded="false">
                        <svg class="svg-icon" id="p-dash7" width="20" height="20"
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10 9 9 9 8 9"></polyline>
                        </svg>
                        <span class="ml-4">Report</span>
                        <svg class="svg-icon iq-arrow-right arrow-active" width="20" height="20"
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="10 15 15 20 20 15"></polyline>
                            <path d="M4 4h7a4 4 0 0 1 4 4v12"></path>
                        </svg>
                    </a>
                    <ul id="report" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                        <li class="">
                            <a href="{{ route('stock_reports.index') }}">
                                <i class="las la-minus"></i><span>Stocks Report </span>
                            </a>
                        </li>
                        <li class="">
                            <a href="{{ route('request_reports.index') }}">
                                <i class="las la-minus"></i><span>Request Report</span>
                            </a>
                        </li>
                        <li class="">
                            <a href="{{ route('stock_reports.index') }}">
                                <i class="las la-minus"></i><span>User Activity Report </span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="">
                    <a href="#logReport" class="collapsed" data-toggle="collapse" aria-expanded="false">
                        <svg class="svg-icon" id="log-folder" width="20" height="20"
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 2h9a2 2 0 0 1 2 2z">
                            </path>
                            <path d="M8 10h8v6H8z" fill="none"></path>
                            <line x1="10" y1="12" x2="14" y2="12"></line>
                            <line x1="10" y1="14" x2="14" y2="14"></line>
                            <line x1="10" y1="16" x2="12" y2="16"></line>
                        </svg>
                        <span class="ml-4">Log Report</span>
                        <svg class="svg-icon iq-arrow-right arrow-active" width="20" height="20"
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="10 15 15 20 20 15"></polyline>
                            <path d="M4 4h7a4 4 0 0 1 4 4v12"></path>
                        </svg>
                    </a>
                    <ul id="logReport" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                        <li class="">
                            <a href="{{ route('get.logs') }}">
                                <i class="las la-minus"></i><span>View Logs</span>
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>
        </nav>
    </div>
</div>
