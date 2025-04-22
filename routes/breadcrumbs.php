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
    $trail->push('Stock Assignment', route('map_user_stock_list'));
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

Breadcrumbs::for('edit_stock', function (BreadcrumbTrail $trail, $stock) {
    $trail->parent('stock_list');
    $trail->push('Edit Stock', route('edit_stock', $stock->id));
});

Breadcrumbs::for('import_stock', function (BreadcrumbTrail $trail) {
    $trail->parent('stock_list');
    $trail->push('Import Stock', route('import_stock'));
});

Breadcrumbs::for('stock.downloadSample', function (BreadcrumbTrail $trail) {
    $trail->parent('stock_list');
    $trail->push('Download Sample', route('stock.downloadSample'));
});

Breadcrumbs::for('stock_filter', function (BreadcrumbTrail $trail) {
    $trail->parent('stock_list');
    $trail->push('Stock Filter', route('stock_filter'));
});