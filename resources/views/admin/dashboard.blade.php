@extends('layouts.frontend.admin_layout')
@section('page-content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card card-block card-stretch card-height">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Overview</h4>
                            </div>
                            <div class="card-header-toolbar d-flex align-items-center">
                                <div class="dropdown">
                                    <span class="dropdown-toggle dropdown-bg btn" id="dropdownMenuButton001"
                                        data-toggle="dropdown">
                                        This Month<i class="ri-arrow-down-s-line ml-1"></i>
                                    </span>
                                    <div class="dropdown-menu dropdown-menu-right shadow-none"
                                        aria-labelledby="dropdownMenuButton001">
                                        <a class="dropdown-item" href="#">Year</a>
                                        <a class="dropdown-item" href="#">Month</a>
                                        <a class="dropdown-item" href="#">Week</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="layout1-chart1"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card card-block card-stretch card-height">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Revenue Vs Cost</h4>
                            </div>
                            <div class="card-header-toolbar d-flex align-items-center">
                                <div class="dropdown">
                                    <span class="dropdown-toggle dropdown-bg btn" id="dropdownMenuButton002"
                                        data-toggle="dropdown">
                                        This Month<i class="ri-arrow-down-s-line ml-1"></i>
                                    </span>
                                    <div class="dropdown-menu dropdown-menu-right shadow-none"
                                        aria-labelledby="dropdownMenuButton002">
                                        <a class="dropdown-item" href="#">Yearly</a>
                                        <a class="dropdown-item" href="#">Monthly</a>
                                        <a class="dropdown-item" href="#">Weekly</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="layout1-chart-2" style="min-height: 360px;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="card card-block card-stretch card-height">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Top Products</h4>
                            </div>
                            <div class="card-header-toolbar d-flex align-items-center">
                                <div class="dropdown">
                                    <span class="dropdown-toggle dropdown-bg btn" id="dropdownMenuButton006"
                                        data-toggle="dropdown">
                                        This Month<i class="ri-arrow-down-s-line ml-1"></i>
                                    </span>
                                    <div class="dropdown-menu dropdown-menu-right shadow-none"
                                        aria-labelledby="dropdownMenuButton006">
                                        <a class="dropdown-item" href="#">Year</a>
                                        <a class="dropdown-item" href="#">Month</a>
                                        <a class="dropdown-item" href="#">Week</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled row top-product mb-0">
                                <li class="col-lg-3">
                                    <div class="card card-block card-stretch card-height mb-0">
                                        <div class="card-body">
                                            <div class="bg-warning-light rounded">
                                                <img src="../assets/images/product/01.png"
                                                    class="style-img img-fluid m-auto p-3" alt="image">
                                            </div>
                                            <div class="style-text text-left mt-3">
                                                <h5 class="mb-1">Organic Cream</h5>
                                                <p class="mb-0">789 Item</p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="col-lg-3">
                                    <div class="card card-block card-stretch card-height mb-0">
                                        <div class="card-body">
                                            <div class="bg-danger-light rounded">
                                                <img src="../assets/images/product/02.png"
                                                    class="style-img img-fluid m-auto p-3" alt="image">
                                            </div>
                                            <div class="style-text text-left mt-3">
                                                <h5 class="mb-1">Rain Umbrella</h5>
                                                <p class="mb-0">657 Item</p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="col-lg-3">
                                    <div class="card card-block card-stretch card-height mb-0">
                                        <div class="card-body">
                                            <div class="bg-info-light rounded">
                                                <img src="../assets/images/product/03.png"
                                                    class="style-img img-fluid m-auto p-3" alt="image">
                                            </div>
                                            <div class="style-text text-left mt-3">
                                                <h5 class="mb-1">Serum Bottle</h5>
                                                <p class="mb-0">489 Item</p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="col-lg-3">
                                    <div class="card card-block card-stretch card-height mb-0">
                                        <div class="card-body">
                                            <div class="bg-success-light rounded">
                                                <img src="../assets/images/product/02.png"
                                                    class="style-img img-fluid m-auto p-3" alt="image">
                                            </div>
                                            <div class="style-text text-left mt-3">
                                                <h5 class="mb-1">Organic Cream</h5>
                                                <p class="mb-0">468 Item</p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>



                <div class="col-lg-4">
                    <div class="card card-transparent card-block card-stretch mb-4">
                        <div class="card-header d-flex align-items-center justify-content-between p-0">
                            <div class="header-title">
                                <h4 class="card-title mb-0">Best Item All Time</h4>
                            </div>
                            <div class="card-header-toolbar d-flex align-items-center">
                                <div><a href="#" class="btn btn-primary view-btn font-size-14">View All</a></div>
                            </div>
                        </div>
                    </div>
                    <div class="card card-block card-stretch card-height-helf">
                        <div class="card-body card-item-right">
                            <div class="d-flex align-items-top">
                                <div class="bg-warning-light rounded">
                                    <img src="../assets/images/product/04.png" class="style-img img-fluid m-auto"
                                        alt="image">
                                </div>
                                <div class="style-text text-left">
                                    <h5 class="mb-2">Coffee Beans Packet</h5>
                                    <p class="mb-2">Total Sell : 45897</p>
                                    <p class="mb-0">Total Earned : $45,89 M</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card card-block card-stretch card-height-helf">
                        <div class="card-body card-item-right">
                            <div class="d-flex align-items-top">
                                <div class="bg-danger-light rounded">
                                    <img src="../assets/images/product/05.png" class="style-img img-fluid m-auto"
                                        alt="image">
                                </div>
                                <div class="style-text text-left">
                                    <h5 class="mb-2">Bottle Cup Set</h5>
                                    <p class="mb-2">Total Sell : 44359</p>
                                    <p class="mb-0">Total Earned : $45,50 M</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Page end  -->
        </div>
    </div>
@endsection
