<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// User Breadcrumbs start
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

//Report

Breadcrumbs::for('Stock_Report', function (BreadcrumbTrail $trail) {
    $trail->parent('user.dashboard'); 
    $trail->push('Stock Report', route('stock_report.index'));
});
Breadcrumbs::for('Request_Report', function (BreadcrumbTrail $trail) {
    $trail->parent('user.dashboard'); 
    $trail->push('Request Report', route('request_report'));
});
// User Breadcrumbs End

// Admin Breadcrumbs Start

Breadcrumbs::for('Admin.dashboard', function (BreadcrumbTrail $trail) {
    $trail->push('Dashboard', route('admin.dashboard'));
});

Breadcrumbs::for('Admin_Profile', function (BreadcrumbTrail $trail) {
    $trail->parent('Admin.dashboard'); 
    $trail->push('User Profile', route('user.admin.profile'));
});

// rig
Breadcrumbs::for('rig_user_list', function (BreadcrumbTrail $trail) {
    $trail->parent('Admin.dashboard');  // Proper parent relationship
    $trail->push('Rigs List', route('admin.rig_users.index'));
});

Breadcrumbs::for('add_rig', function (BreadcrumbTrail $trail) {
    $trail->parent('rig_user_list');  // Proper parent relationship
    $trail->push('Add Rig', route('admin.rig_users.create'));
});
Breadcrumbs::for('edit_rig', function (BreadcrumbTrail $trail,$id) {
    $trail->parent('rig_user_list');  // Proper parent relationship
    $trail->push('Edit Rig', route('admin.rig_users.edit',$id));
});

// user 
Breadcrumbs::for('user_list', function (BreadcrumbTrail $trail) {
    $trail->parent('Admin.dashboard');  // Proper parent relationship
    $trail->push('User List', route('admin.index'));
});
Breadcrumbs::for('add_User', function (BreadcrumbTrail $trail) {
    $trail->parent('user_list');  // Proper parent relationship
    $trail->push('Add User', route('admin.create'));
});
Breadcrumbs::for('edit_User', function (BreadcrumbTrail $trail,$id) {
    $trail->parent('user_list');  // Proper parent relationship
    $trail->push('Edit User', route('admin.edit',$id));
});

//section
Breadcrumbs::for('section_list', function (BreadcrumbTrail $trail) {
    $trail->parent('Admin.dashboard');  // Proper parent relationship
    $trail->push('Section List', route('admin.section.index'));
});
Breadcrumbs::for('section_create', function (BreadcrumbTrail $trail) {
    $trail->parent('section_list');  // Proper parent relationship
    $trail->push('Add Section', route('admin.section.create'));
});
Breadcrumbs::for('section_Edit', function (BreadcrumbTrail $trail,$id) {
    $trail->parent('section_list');  // Proper parent relationship
    $trail->push('Edit Section', route('admin.section.edit',$id)); 
});

//edp
Breadcrumbs::for('edit_list', function (BreadcrumbTrail $trail) {
    $trail->parent('Admin.dashboard');  // Proper parent relationship
    $trail->push('Edp List', route('admin.edp.index'));
});
Breadcrumbs::for('add_edp', function (BreadcrumbTrail $trail) {
    $trail->parent('edit_list');  // Proper parent relationship
    $trail->push('Add Edp', route('admin.edp.index'));
});

Breadcrumbs::for('edit_edp', function (BreadcrumbTrail $trail,$id) {
    $trail->parent('edit_list');  // Proper parent relationship
    $trail->push('Edit Edp', route('admin.edp.edit',$id));
});

Breadcrumbs::for('import_bulk_edp', function (BreadcrumbTrail $trail) {
    $trail->parent('edit_list');  // Proper parent relationship
    $trail->push('Import Bulk Edp', route('admin.import_edp'));
});

//stock
Breadcrumbs::for('admin_stock_list', function (BreadcrumbTrail $trail) {
    $trail->parent('Admin.dashboard');  // Proper parent relationship
    $trail->push('Stock List', route('admin.stock_list'));
});

Breadcrumbs::for('admin_add_stock', function (BreadcrumbTrail $trail) {
    $trail->parent('admin_stock_list');  // Proper parent relationship
    $trail->push('Add Stock', route('admin.add_stock'));
});

Breadcrumbs::for('admin_edit_stock', function (BreadcrumbTrail $trail,$id) {
    $trail->parent('admin_stock_list');  // Proper parent relationship
    $trail->push('Edit Stock', route('admin.edit_stock',$id));
});

Breadcrumbs::for('admin_import_bulk_stock', function (BreadcrumbTrail $trail) {
    $trail->parent('admin_stock_list');  // Proper parent relationship
    $trail->push('Import Bulk stock', route('admin.import_stock'));
});

//request stock
Breadcrumbs::for('request_admin_stock_list', function (BreadcrumbTrail $trail) {
    $trail->parent('Admin.dashboard');  // Proper parent relationship
    $trail->push('All Stock Request List', route('admin.stock_list.get'));
});
Breadcrumbs::for('request_admin_stock_list_incoming', function (BreadcrumbTrail $trail) {
    $trail->parent('Admin.dashboard');  // Proper parent relationship
    $trail->push('Incoming Request', route('admin.incoming_request_list'));
});
Breadcrumbs::for('Raised_Request_stock', function (BreadcrumbTrail $trail) {
    $trail->parent('Admin.dashboard');  // Proper parent relationship
    $trail->push('Raised Request', route('admin.raised_requests.index'));
});
//Report

Breadcrumbs::for('Logs_Table', function (BreadcrumbTrail $trail) {
    $trail->parent('Admin.dashboard');  // Proper parent relationship
    $trail->push('Logs Table', route('get.logs'));
});

// Breadcrumbs::for('request_admin_stock_list_incoming', function (BreadcrumbTrail $trail) {
//     $trail->parent('Admin.dashboard');  // Proper parent relationship
//     $trail->push('Request Report', route('request_report.index'));
// });
// Breadcrumbs::for('User_Activity_Report', function (BreadcrumbTrail $trail) {
//     $trail->parent('Admin.dashboard');  // Proper parent relationship
//     $trail->push('User Activity Report', route('stock_report.index'));
// });



//
// Admin Breadcrumbs End



