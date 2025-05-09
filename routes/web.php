<?php

use App\Http\Controllers\admin\AdminRequestStockController;
use App\Http\Controllers\Admin\Masters\RigUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User;
use App\Http\Controllers\LogsController as AdminLogsController;
use App\Http\Controllers\UserLogsController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\RequestStockController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\Admin\AdminStockController;
use App\Http\Controllers\admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\masters\EdpController;
use App\Http\Controllers\admin\masters\CategoryController;
use App\Http\Controllers\admin\masters\SectionController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\StockReportController;
use App\Http\Controllers\RequestReportController;
use App\Http\Controllers\admin\StockReportController as AdminStockReportController;
use App\Http\Controllers\admin\RequestReportController as AdminRequestReportController;



//call index page
Route::get('/', function () {
    return view('home');
})->name('home');

//Admin Portal Section
//Authentication
Route::get('/admin/login', [AdminLoginController::class, 'index'])->name('admin.login');
Route::post('/admin/authenticate', [AdminLoginController::class, 'authenticate'])->name('admin.authenticate');

Route::middleware(['admin.auth','admin.session'])->group(function () {

    Route::prefix('/admin')->group(function () {
        Route::get('/profile', [AdminLoginController::class, 'profile'])->name('user.admin.profile');
        Route::get('/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

        //admin Logs
        Route::get('/log',[AdminLogsController::class,'index'])->name('get.logs');
        Route::get('/logfilter',[AdminLogsController::class,'filterdata'])->name('get.logs.filter');


        //Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        //User Registration
        Route::get('/index', [UserController::class, 'index'])->name('admin.index');
        Route::get('/create', [UserController::class, 'create'])->name('admin.create');
        Route::post('/store', [UserController::class, 'store'])->name('admin.store');
        Route::get('/show/{id}', [UserController::class, 'show'])->name('admin.show');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('admin.edit');
        Route::put('/update/{id}', [UserController::class, 'update'])->name('admin.update');
        Route::delete('/destroy/{id}', [UserController::class, 'destroy'])->name('admin.destroy');

        //rig user master
        Route::get('/rig_users', [RigUserController::class, 'index'])->name('admin.rig_users.index');
        Route::get('/rig_users/create', [RigUserController::class, 'create'])->name('admin.rig_users.create');
        Route::post('/rig_users', [RigUserController::class, 'store'])->name('admin.rig_users.store');
        Route::get('/rig_users/{id}', [RigUserController::class, 'show'])->name('admin.rig_users.show');
        Route::get('/rig_users/{id}/edit', [RigUserController::class, 'edit'])->name('admin.rig_users.edit');
        Route::post('/rig_users/{id}', [RigUserController::class, 'update'])->name('admin.rig_users.update');
        Route::post('/rig_users_destroy', [RigUserController::class, 'destroy'])->name('admin.rig_users.destroy');

        //EDP master
        Route::get('/edp', [EdpController::class, 'index'])->name('admin.edp.index');
        Route::get('/edp/create', [EdpController::class, 'create'])->name('admin.edp.create');
        Route::post('/edp', [EdpController::class, 'store'])->name('admin.edp.store');
        Route::get('/edp/{id}/edit', [EdpController::class, 'edit'])->name('admin.edp.edit');
        Route::post('/edp/update', [EdpController::class, 'update'])->name('admin.edp.update');
        Route::post('/edp/delete', [EdpController::class, 'destroy'])->name('admin.edp.destroy');
        Route::get('/edp/import', [EdpController::class, 'showImportForm'])->name('admin.import_edp');
        Route::post('/edp/import_bulk', [EdpController::class, 'import'])->name('admin.edp.import');
        Route::get('/edp/sample-download', [EdpController::class, 'downloadSample'])->name('admin.edp.downloadSample');

        //Category master
        Route::get('/category', [CategoryController::class, 'index'])->name('admin.category.index');
        Route::get('/category/create', [CategoryController::class, 'create'])->name('admin.category.create');
        Route::post('/category', [CategoryController::class, 'store'])->name('admin.category.store');
        Route::get('/category/{id}/edit', [CategoryController::class, 'edit'])->name('admin.category.edit');
        Route::post('/category/update', [CategoryController::class, 'update'])->name('admin.category.update');
        Route::post('/category/delete', [CategoryController::class, 'destroy'])->name('admin.category.destroy');

         //Section master
         Route::get('/section', [SectionController::class, 'index'])->name('admin.section.index');
         Route::get('/section/create', [SectionController::class, 'create'])->name('admin.section.create');
         Route::post('/section', [SectionController::class, 'store'])->name('admin.section.store');
         Route::get('/section/{id}/edit', [SectionController::class, 'edit'])->name('admin.section.edit');
         Route::post('/section/update', [SectionController::class, 'update'])->name('admin.section.update');
         Route::post('/section/delete', [SectionController::class, 'destroy'])->name('admin.section.destroy');


        //Stocks
        Route::any('/add_stock', [AdminStockController::class, 'add_stock'])->name('admin.add_stock');
        Route::post('/stockSubmit', [AdminStockController::class, 'stockSubmit'])->name('admin.stockSubmit');
        Route::any('/all_stock_list', [AdminStockController::class, 'all_stock_list'])->name('admin.all_stock_list');
        Route::get('/import', [AdminStockController::class, 'showImportForm'])->name('admin.import_stock');;
        Route::post('/import_bulk', [AdminStockController::class, 'import'])->name('admin.stock.import');
        Route::get('/get_edp_details', [AdminStockController::class, 'get_edp_details'])->name('admin.get_edp_details');
        Route::get('/sample-download', [AdminStockController::class, 'downloadSample'])->name('admin.stock.downloadSample');
        Route::get('/get_data_forview', [AdminStockController::class, 'stock_list_view'])->name('admin.stock_list_view');
        Route::get('/edit_stock/{id}', [AdminStockController::class, 'EditStock'])->name('admin.edit_stock');
        Route::post('/update_stock', [AdminStockController::class, 'UpdateStock'])->name('admin.update_stock');
        Route::post('/delete_stock', [AdminStockController::class, 'DeleteStock'])->name('admin.Delete_stock');
        Route::get('/stock_list', [AdminStockController::class, 'stock_list'])->name('admin.stock_list');
        Route::get('/stock_filter', [AdminStockController::class, 'stock_filter'])->name('admin.stock_filter');
        Route::get('/check-edp-stock', [AdminStockController::class, 'checkEdpStock'])->name('admin.check_edp_stock');

        //Request Stock
        Route::prefix('request-stock')->group(function () {
            Route::get('/request_stockList', [AdminRequestStockController::class, 'RequestStockList'])->name('admin.stock_list.get');
            Route::get('/filter', [AdminRequestStockController::class, 'RequestStockFilter'])->name('admin.request_stock_filter.get');
            Route::get('/stockList', [AdminRequestStockController::class, 'StockList'])->name('admin.stock_list.request');
            Route::get('/list', [AdminRequestStockController::class, 'RequestStockList'])->name('admin.request_stock_list');
            Route::get('/supplier_request', [AdminRequestStockController::class, 'SupplierRequest'])->name('admin.supplier_request.get');
            Route::get('/add', [AdminRequestStockController::class, 'RequestStockAdd'])->name('admin.request_stock_add');
            Route::post('/addSubmit', [AdminRequestStockController::class, 'RequestStockAddPost'])->name('admin.request_stock_add.post');
            Route::get('/view', [AdminRequestStockController::class, 'RequestStockViewPost'])->name('admin.request_stock_view.get');
            Route::post('/filter', [AdminRequestStockController::class, 'request_stock_filter'])->name('admin.request_stock_filter');
            Route::get('/get_stockrequest_data', [AdminStockController::class, 'get_stockrequest_data'])->name('admin.get_stockrequest_data');
            Route::get('/incoming_request_list', [AdminRequestStockController::class, 'IncomingRequestStockList'])->name('admin.incoming_request_list');
            Route::get('/incoming_request_list_filter', [AdminRequestStockController::class, 'IncomingRequestStockFilter'])->name('admin.incoming_request_filter.get');
            Route::post('/request-status/accept', [AdminRequestStockController::class, 'accept'])->name('admin.request.accept');
            Route::post('/request-status/decline', [AdminRequestStockController::class, 'decline'])->name('admin.request.decline');
            Route::post('/request-status/query', [AdminRequestStockController::class, 'query'])->name('admin.request.query');
            Route::get('/request-status/get-request-stock/{id}', [AdminRequestStockController::class, 'getRequestStock']);
            Route::post('/request-status/update-request-status', [AdminRequestStockController::class, 'updateStatus'])->name('admin.request.updateStatus');
            Route::get('/status-list', [AdminRequestStockController::class, 'getRequestStatus'])->name('admin.get.request.status');
            Route::get('/raised-requests', [AdminRequestStockController::class, 'RaisedRequestList'])->name('admin.raised_requests.index');
            Route::get('/filtered-requests', [AdminRequestStockController::class, 'filterRequestStock'])->name('admin.raised_requests.filter');
            Route::post('/request-status/update-request-status-raised', [AdminRequestStockController::class, 'updateStatusforRequest'])->name('admin.request.updateStatusforRequest');
            Route::post('/request-status/update-is-read-status', [AdminRequestStockController::class, 'updateIsReadStatus'])->name('admin.update.is_read.status');
            Route::post('/update-stock', [AdminRequestStockController::class, 'updateStock'])->name('admin.update.stock');
            Route::post('/request-status/query-for-raised-request', [AdminRequestStockController::class, 'queryforRaisedRequest'])->name('admin.request.raisedrequestquery');
            Route::post('/accept-pending-request', [AdminRequestStockController::class, 'acceptPendingRequest'])->name('admin.request.pending.accept');
            Route::post('/decline-pending-request', [AdminRequestStockController::class, 'declineforRaisedRequest'])->name('admin.request.raisedrequestdecline');
            Route::get('/get-request-status/{id}', [AdminRequestStockController::class, 'getRequestStatusforEdit'])->name('admin.request-status.get');
            Route::post('/update-request-status/{id}', [AdminRequestStockController::class, 'updateRequestStatus'])->name('admin.request-status.update');
            Route::get('/requestList/{status}', [AdminRequestStockController::class, 'RequestStockListStatus'])->name('admin.requestList', ['status' => 'status']);
        });

        //Reports
        Route::prefix('reports')->group(function () {
            Route::get('/stocks', [AdminStockReportController::class, 'index'])->name('stock_reports.index');
            Route::get('/report_stock_filter', [AdminStockReportController::class, 'report_stock_filter'])->name('admin.report_stock_filter');

            Route::get('/transaction', [AdminStockReportController::class, 'transactions'])->name('admin.transaction');
        });

        //request stocks reports
        Route::get('/requests', [AdminRequestReportController::class, 'index'])->name('request_reports.index');
        Route::get('/request-report/fetch', [AdminRequestReportController::class, 'fetchReport'])->name('admin.report.fetch');

        //Notifications
        Route::post('/notifications/mark-read-admin', [NotificationController::class, 'markAsReadAdmin'])->name('notifications.markReadAdmin');
        Route::post('/notifications/mark-all-read-admin', [NotificationController::class, 'markAllReadAdmin'])->name('notifications.markAllReadAdmin');
    });

    Route::post('/extend-session', function() {
        request()->session()->put('last_activity', time());
        return response()->json(['success' => true]);
    })->name('admin.extend-session');
});


//User Portal section

//Authentication
Route::get('/user/login', [LoginController::class, 'index'])->name('user.login');
Route::post('/user/authenticate', [LoginController::class, 'authenticate'])->name('user.authenticate');


Route::middleware(['auth', 'user.session'])->group(function () {

    Route::prefix('/user')->group(function () {


        Route::get('/logout', [LoginController::class, 'logout'])->name('user.logout');
        Route::get('/profile', [LoginController::class, 'profile'])->name('user.profile');

         //user Logs
         Route::get('/user_log',[UserLogsController::class,'index'])->name('user.get.logs');
         Route::get('/user_logfilter',[UserLogsController::class,'filterdata'])->name('user.get.logs.filter');

        //Registration of users
        Route::get('/register', [LoginController::class, 'register'])->name('user.register');
        Route::post('/registration', [LoginController::class, 'registerSubmit'])->name('user.registerSubmit');
        //Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('user.dashboard');


        //Stocks
        Route::any('/add_stock', [StockController::class, 'add_stock'])->name('add_stock');
        Route::post('/stockSubmit', [StockController::class, 'stockSubmit'])->name('stockSubmit');
        Route::any('/all_stock_list', [StockController::class, 'all_stock_list'])->name('all_stock_list');
        Route::get('/import', [StockController::class, 'showImportForm'])->name('import_stock');;
        Route::post('/import_bulk', [StockController::class, 'import'])->name('stock.import');
        Route::get('/get_edp_details', [StockController::class, 'get_edp_details'])->name('get_edp_details');
        Route::get('/sample-download', [StockController::class, 'downloadSample'])->name('stock.downloadSample');
        Route::get('/get_data_forview', [StockController::class, 'stock_list_view'])->name('stock_list_view');
        Route::get('/edit_stock/{id}', [StockController::class, 'EditStock'])->name('edit_stock');
        Route::post('/update_stock', [StockController::class, 'UpdateStock'])->name('update_stock');
        Route::post('/delete_stock', [StockController::class, 'DeleteStock'])->name('Delete_stock');
        Route::get('/stock_list', [StockController::class, 'stock_list'])->name('stock_list');
        Route::get('/stock_filter', [StockController::class, 'stock_filter'])->name('stock_filter');
        Route::get('/check-edp-stock', [StockController::class, 'checkEdpStock'])->name('check_edp_stock');


        //Request stock
        Route::prefix('request-stock')->group(function () {
            Route::get('/request_stockList', [RequestStockController::class, 'RequestStockList'])->name('stock_list.get');
            Route::get('/filter', [RequestStockController::class, 'RequestStockFilter'])->name('request_stock_filter.get');
            Route::get('/stockList', [RequestStockController::class, 'StockList'])->name('stock_list.request');
            Route::get('/list', [RequestStockController::class, 'RequestStockList'])->name('request_stock_list');
            Route::get('/supplier_request', [RequestStockController::class, 'SupplierRequest'])->name('supplier_request.get');
            Route::get('/add', [RequestStockController::class, 'RequestStockAdd'])->name('request_stock_add');
            Route::post('/addSubmit', [RequestStockController::class, 'RequestStockAddPost'])->name('request_stock_add.post');
            Route::get('/view', [RequestStockController::class, 'RequestStockViewPost'])->name('request_stock_view.get');
            Route::post('/filter', [RequestStockController::class, 'request_stock_filter'])->name('request_stock_filter');
            Route::get('/get_stockrequest_data', [StockController::class, 'get_stockrequest_data'])->name('get_stockrequest_data');
            Route::get('/incoming_request_list', [RequestStockController::class, 'IncomingRequestStockList'])->name('incoming_request_list');
            Route::get('/incoming_request_list_filter', [RequestStockController::class, 'IncomingRequestStockFilter'])->name('incoming_request_filter.get');
            Route::post('/request-status/accept', [RequestStockController::class, 'accept'])->name('request.accept');
            Route::post('/request-status/decline', [RequestStockController::class, 'decline'])->name('request.decline');
            Route::post('/request-status/query', [RequestStockController::class, 'query'])->name('request.query');
            Route::get('/request-status/get-request-stock/{id}', [RequestStockController::class, 'getRequestStock']);
            Route::post('/request-status/update-request-status', [RequestStockController::class, 'updateStatus'])->name('request.updateStatus');
            Route::get('/status-list', [RequestStockController::class, 'getRequestStatus'])->name('get.request.status');
            Route::get('/raised-requests', [RequestStockController::class, 'RaisedRequestList'])->name('raised_requests.index');
            Route::get('/filtered-requests', [RequestStockController::class, 'filterRequestStock'])->name('raised_requests.filter');
            Route::post('/request-status/update-request-status-raised', [RequestStockController::class, 'updateStatusforRequest'])->name('request.updateStatusforRequest');
            Route::post('/request-status/update-is-read-status', [RequestStockController::class, 'updateIsReadStatus'])->name('update.is_read.status');
            Route::post('/update-stock', [RequestStockController::class, 'updateStock'])->name('update.stock');
            Route::post('/request-status/query-for-raised-request', [RequestStockController::class, 'queryforRaisedRequest'])->name('request.raisedrequestquery');
            Route::post('/accept-pending-request', [RequestStockController::class, 'acceptPendingRequest'])->name('request.pending.accept');
            Route::post('/decline-pending-request', [RequestStockController::class, 'declineforRaisedRequest'])->name('request.raisedrequestdecline');
            Route::get('/get-request-status/{id}', [RequestStockController::class, 'getRequestStatusforEdit'])->name('request-status.get');
            Route::post('/update-request-status/{id}', [RequestStockController::class, 'updateRequestStatus'])->name('request-status.update');
            Route::get('/incomingPenddingRequest', [RequestStockController::class, 'incomingPenddingRequest'])->name('incomingPndding_request.get');
            Route::get('/raisedPenddingRequest', [RequestStockController::class, 'raisedPenddingRequest'])->name('raisedPenddingRequest.get');
            Route::get('/RequestStockListFilter', [RequestStockController::class, 'CommanRequestStockFilter'])->name('CommanRequestStockFilter.get');
            Route::get('/fetchIncommingCount', [RequestStockController::class, 'fetchIncommingCount'])->name('fetchIncommingCount');
            Route::get('/fetchRaisedCount', [RequestStockController::class, 'fetchRaisedCount'])->name('fetchRaisedCount');
        });

        //User mapping
        Route::get('/mapuserlist', [LoginController::class, 'mapuserlist'])->name('map_all_user_list');
        Route::get('/mapuseraddstocklist', [LoginController::class, 'mapuserstockview'])->name('map_user_stock_list');
        Route::post('/mapusergetdata', [LoginController::class, 'mapuserdataget'])->name('map_all_user_data.post');
        Route::post('/mapspecificuserdata', [LoginController::class, 'mapspecificuserdata'])->name('map_user_data_specific.post');

        //Stocks Reports
        Route::prefix('reports')->group(function () {
            Route::get('/stock', [StockReportController::class, 'index'])->name('stock_report.index');
            Route::get('/report_stock_filter', [StockReportController::class, 'report_stock_filter'])->name('report_stock_filter');
          //  Route::get('/stock', [StockReportController::class, 'index'])->name('stock_report.index');
            Route::get('/stockPdfDownload', [StockReportController::class, 'stockPdfDownload'])->name('report_stockPdfDownload');
            Route::get('/stockExcelDownload', [StockReportController::class, 'stockExcelDownload'])->name('report_stockExcelDownload');

        });

        //Request stocks Reports
        Route::get('/request-report', [RequestReportController::class, 'index'])->name('request_report');
        Route::get('/request-report/fetch', [RequestReportController::class, 'fetchReport'])->name('report.fetch');
        Route::get('/request', [RequestReportController::class, 'index'])->name('request_report.index');
        Route::get('/requestPdfDownload', [RequestReportController::class, 'requestPdfDownload'])->name('report_requestPdfDownload');
        Route::get('/requestExcelDownload', [RequestReportController::class, 'requestExcelDownload'])->name('report_requestExcelDownload');

        //Notifications
        Route::post('/notifications/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.markRead');
        Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');

        Route::post('/extend-session', function() {
            request()->session()->put('last_activity', time());
            return response()->json(['success' => true]);
        })->name('user.extend-session');
    });
});

//PDF Section
Route::get('/stock-list/pdf', [StockController::class, 'downloadPdf'])->name('stock_list_pdf');

//Reset Password (No Auth Required)
Route::get('/forgotpassword', [LoginController::class, 'forgotpassword'])->name('forgotpassword');
Route::post('/submitpassword', [LoginController::class, 'submitpassword'])->name('submitpassword');
Route::get('/reset-password/{user_id}/{token}', [LoginController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [LoginController::class, 'updatePassword'])->name('password.update');

//Notifications
Route::get('/notifications/fetch', [NotificationController::class, 'fetchNotifications'])->name('notifications.fetch');
