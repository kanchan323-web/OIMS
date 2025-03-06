      <div class="iq-sidebar  sidebar-default ">
          <div class="iq-sidebar-logo d-flex align-items-center justify-content-between">
              <a href="{{ route('user.dashboard') }}" class="header-logo">
                  <img src="{{ asset('resources/images/logo.png') }}" class="img-fluid rounded-normal light-logo"
                      alt="logo">
                  <h5 class="logo-title light-logo ml-3">OIMS</h5>
              </a>
              <div class="iq-menu-bt-sidebar ml-0">
                  <i class="las la-bars wrapper-menu"></i>
              </div>
          </div>
          <div class="data-scrollbar" data-scroll="1">
              <nav class="iq-sidebar-menu">
                  <ul id="iq-sidebar-toggle" class="iq-menu">
                      <li class="active">
                          <a href="{{ route('user.dashboard') }}" class="svg-icon">
                              <svg class="svg-icon" id="p-dash1" width="20" height="20"
                                  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                  <path
                                      d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z">
                                  </path>
                                  <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                  <line x1="12" y1="22.08" x2="12" y2="12"></line>
                              </svg>
                              <span class="ml-4">Dashboards</span>
                          </a>
                      </li>
                      <li class="">
                          <a href="{{ route('map_all_user_list') }}" class="svg-icon">
                              <svg class="svg-icon" id="p-dash1" width="20" height="20"
                                  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                  <circle cx="12" cy="8" r="4"></circle>
                                  <path d="M16 21v-2a4 4 0 0 0-8 0v2"></path>
                              </svg>
                              <span class="ml-4">Map User</span>
                          </a>
                      </li>
                      <li class=" ">
                          <a href="#product" class="collapsed" data-toggle="collapse" aria-expanded="false">
                              <svg class="svg-icon" id="p-dash2" width="20" height="20"
                                  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                  <circle cx="9" cy="21" r="1"></circle>
                                  <circle cx="20" cy="21" r="1"></circle>
                                  <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                              </svg>
                              <span class="ml-4">Stock</span>
                              <svg class="svg-icon iq-arrow-right arrow-active" width="20" height="20"
                                  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                  <polyline points="10 15 15 20 20 15"></polyline>
                                  <path d="M4 4h7a4 4 0 0 1 4 4v12"></path>
                              </svg>
                          </a>
                          <ul id="product" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                              <li class="">
                                  <a href="{{ route('stock_list') }}">
                                      <i class="las la-minus"></i><span>Stock List</span>
                                  </a>
                              </li>
                              <li class="">
                                  <a href="{{ route('add_stock') }}">
                                      <i class="las la-minus"></i><span>Add Stock</span>
                                  </a>
                              </li>
                          </ul>
                      </li>
                      <li class=" ">
                          <a href="#category" class="collapsed" data-toggle="collapse" aria-expanded="false">
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
                          <ul id="category" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                              <li class="">
                                  <a href="{{route('request_stock_list')}}">
                                      <i class="las la-minus"></i><span>All Stock Request List</span>
                                  </a>
                              </li>
                              <li class="">
                                  <a href="{{route('request_stock_add')}}">
                                      <i class="las la-minus"></i><span>Add Request</span>
                                  </a>
                              </li>
                              <li class="">
                                  <a href="{{route('request_stock_add')}}">
                                      <i class="las la-minus"></i><span>Incomming Request</span>
                                  </a>
                              </li>
                          </ul>
                      </li>
                      <li class=" ">
                          <a href="#sale" class="collapsed" data-toggle="collapse" aria-expanded="false">
                              <svg class="svg-icon" id="p-dash4" width="20" height="20"
                                  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                  stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                  stroke-linejoin="round">
                                  <path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path>
                                  <path d="M22 12A10 10 0 0 0 12 2v10z"></path>
                              </svg>
                              <span class="ml-4">Stock Analysis</span>
                              <svg class="svg-icon iq-arrow-right arrow-active" width="20" height="20"
                                  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                  stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                  stroke-linejoin="round">
                                  <polyline points="10 15 15 20 20 15"></polyline>
                                  <path d="M4 4h7a4 4 0 0 1 4 4v12"></path>
                              </svg>
                          </a>
                          <ul id="sale" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                              <li class="">
                                  <a href="../backend/page-list-sale.html">
                                      <i class="las la-minus"></i><span>List Sale</span>
                                  </a>
                              </li>
                              <li class="">
                                  <a href="../backend/page-add-sale.html">
                                      <i class="las la-minus"></i><span>Add Sale</span>
                                  </a>
                              </li>
                          </ul>
                      </li>
                      <li class=" ">
                          <a href="#people" class="collapsed" data-toggle="collapse" aria-expanded="false">
                              <svg class="svg-icon" id="p-dash8" width="20" height="20"
                                  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                  stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                  stroke-linejoin="round">
                                  <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                  <circle cx="9" cy="7" r="4"></circle>
                                  <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                  <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                              </svg>
                              <span class="ml-4">User Control</span>
                              <svg class="svg-icon iq-arrow-right arrow-active" width="20" height="20"
                                  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                  stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                  stroke-linejoin="round">
                                  <polyline points="10 15 15 20 20 15"></polyline>
                                  <path d="M4 4h7a4 4 0 0 1 4 4v12"></path>
                              </svg>
                          </a>
                          <ul id="people" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                              <li class="">
                                  <a href="../backend/page-list-users.html">
                                      <i class="las la-minus"></i><span>Users</span>
                                  </a>
                              </li>
                              <li class="">
                                  <a href="../backend/page-add-users.html">
                                      <i class="las la-minus"></i><span>Add Users</span>
                                  </a>
                              </li>
                              <li class="">
                                  <a href="../backend/page-list-suppliers.html">
                                      <i class="las la-minus"></i><span>Suppliers</span>
                                  </a>
                              </li>
                              <li class="">
                                  <a href="../backend/page-add-supplier.html">
                                      <i class="las la-minus"></i><span>Add Suppliers</span>
                                  </a>
                              </li>
                          </ul>
                      </li>
                      <li class="">
                          <a href="../backend/page-report.html" class="">
                              <svg class="svg-icon" id="p-dash7" width="20" height="20"
                                  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                  stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                  stroke-linejoin="round">
                                  <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                  <polyline points="14 2 14 8 20 8"></polyline>
                                  <line x1="16" y1="13" x2="8" y2="13"></line>
                                  <line x1="16" y1="17" x2="8" y2="17"></line>
                                  <polyline points="10 9 9 9 8 9"></polyline>
                              </svg>
                              <span class="ml-4">Reports</span>
                          </a>
                          <ul id="reports" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                          </ul>
                      </li>
                      <li class=" ">
                          <a href="#otherpage" class="collapsed" data-toggle="collapse" aria-expanded="false">
                              <svg class="svg-icon" id="p-dash9" width="20" height="20"
                                  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                  stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                  stroke-linejoin="round">
                                  <rect x="3" y="3" width="18" height="18" rx="2" ry="2">
                                  </rect>
                                  <rect x="7" y="7" width="3" height="9"></rect>
                                  <rect x="14" y="7" width="3" height="5"></rect>
                              </svg>
                              <span class="ml-4">other page</span>
                              <svg class="svg-icon iq-arrow-right arrow-active" width="20" height="20"
                                  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                  stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                  stroke-linejoin="round">
                                  <polyline points="10 15 15 20 20 15"></polyline>
                                  <path d="M4 4h7a4 4 0 0 1 4 4v12"></path>
                              </svg>
                          </a>
                          <ul id="otherpage" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                              <li class=" ">
                                  <a href="#user" class="collapsed" data-toggle="collapse" aria-expanded="false">
                                      <svg class="svg-icon" id="p-dash10" width="20" height="20"
                                          xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                          stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                          stroke-linejoin="round">
                                          <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                          <circle cx="8.5" cy="7" r="4"></circle>
                                          <polyline points="17 11 19 13 23 9"></polyline>
                                      </svg>
                                      <span class="ml-4">User Details</span>
                                      <svg class="svg-icon iq-arrow-right arrow-active" width="20" height="20"
                                          xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                          stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                          stroke-linejoin="round">
                                          <polyline points="10 15 15 20 20 15"></polyline>
                                          <path d="M4 4h7a4 4 0 0 1 4 4v12"></path>
                                      </svg>
                                  </a>
                                  <ul id="user" class="iq-submenu collapse" data-parent="#otherpage">
                                      <li class="">
                                          <a href="../app/user-profile.html">
                                              <i class="las la-minus"></i><span>User Profile</span>
                                          </a>
                                      </li>
                                      <li class="">
                                          <a href="../app/user-add.html">
                                              <i class="las la-minus"></i><span>User Add</span>
                                          </a>
                                      </li>
                                      <li class="">
                                          <a href="../app/user-list.html">
                                              <i class="las la-minus"></i><span>User List</span>
                                          </a>
                                      </li>
                                  </ul>
                              </li>

                              <li class=" ">
                                  <a href="#auth" class="collapsed" data-toggle="collapse" aria-expanded="false">
                                      <svg class="svg-icon" id="p-dash12" width="20" height="20"
                                          xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                          stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                          stroke-linejoin="round">
                                          <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                          <polyline points="14 2 14 8 20 8"></polyline>
                                          <line x1="16" y1="13" x2="8" y2="13"></line>
                                          <line x1="16" y1="17" x2="8" y2="17"></line>
                                          <polyline points="10 9 9 9 8 9"></polyline>
                                      </svg>
                                      <span class="ml-4">Authentication</span>
                                      <svg class="svg-icon iq-arrow-right arrow-active" width="20" height="20"
                                          xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                          stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                          stroke-linejoin="round">
                                          <polyline points="10 15 15 20 20 15"></polyline>
                                          <path d="M4 4h7a4 4 0 0 1 4 4v12"></path>
                                      </svg>
                                  </a>
                                  <ul id="auth" class="iq-submenu collapse" data-parent="#otherpage">
                                      <li class="">
                                          <a href="">
                                              <i class="las la-minus"></i><span>Login</span>
                                          </a>
                                      </li>
                                      <li class="">
                                          <a href="../backend/auth-sign-up.html">
                                              <i class="las la-minus"></i><span>Register</span>
                                          </a>
                                      </li>
                                  </ul>
                              </li>
                          </ul>
                      </li>
                  </ul>
              </nav>
          </div>
      </div>
