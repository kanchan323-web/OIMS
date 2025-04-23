<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('user.dashboard', function (BreadcrumbTrail $trail) {
    $trail->push('Dashboard', route('user.dashboard'));
});

// User Mapping List
Breadcrumbs::for('map_all_user_list', function (BreadcrumbTrail $trail) {
    $trail->parent('user.dashboard');  // Proper parent relationship
    $trail->push('User Mapping', route('map_all_user_list'));
});

// User Mapping Stock List
Breadcrumbs::for('map_user_stock_list', function (BreadcrumbTrail $trail) {
    $trail->parent('map_all_user_list');  // Clear hierarchy
    $trail->push('Stock Added By User', route('map_user_stock_list'));
});


// Stock Management Breadcrumbs
Breadcrumbs::for('stock_list', function (BreadcrumbTrail $trail) {
    $trail->parent('user.dashboard');
    $trail->push('Stock List', route('stock_list'));
});

Breadcrumbs::for('add_stock', function (BreadcrumbTrail $trail) {
    $trail->parent('stock_list');
    $trail->push('Add Stock', route('admin.add_stock'));
});

Breadcrumbs::for('edit_stock', function ($trail, $id) {
    $trail->parent('stock_list');
    $trail->push('Edit Stock', route('edit_stock', $id));
});

Breadcrumbs::for('import_stock', function (BreadcrumbTrail $trail) {
    $trail->parent('stock_list');
    $trail->push('Import Stock', route('import_stock'));
});
Breadcrumbs::for('request_stock_list', function (BreadcrumbTrail $trail) {
    $trail->parent('user.dashboard');
    $trail->push('Request Stock List', route('stock_list.get'));
});
Breadcrumbs::for('incoming_request_stock_list', function (BreadcrumbTrail $trail) {
    $trail->parent('user.dashboard');
    $trail->push('Incoming Request ', route('incoming_request_list'));
});

Breadcrumbs::for('Raised_Requests_List', function (BreadcrumbTrail $trail) {
    $trail->parent('user.dashboard');
    $trail->push('Raised Requests ', route('admin.raised_requests.index'));
});

Breadcrumbs::for('Report_Stock', function (BreadcrumbTrail $trail) {
    $trail->parent('user.dashboard');
    $trail->push('Report Stock', route('stock_report.index'));
});

Breadcrumbs::for('Report_Request', function (BreadcrumbTrail $trail) {
    $trail->parent('user.dashboard');
    $trail->push('Report Request', route('request_report.index'));
});

Breadcrumbs::for('User_profile', function (BreadcrumbTrail $trail) {
    $trail->parent('user.dashboard');
    $trail->push('User Profile', route('user.profile'));
});



