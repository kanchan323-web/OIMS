<?php

use App\Http\Controllers\Admin\Masters\RigUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User;
use App\Http\Controllers\StockController;
use App\Http\Controllers\RequestStockController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\masters\EdpController;
use App\Http\Controllers\admin\masters\CategoryController;


//Admin Portal Section

//Authentication
Route::get('/admin/login', [AdminLoginController::class, 'index'])->name('admin.login');
Route::post('/admin/authenticate', [AdminLoginController::class, 'authenticate'])->name('admin.authenticate');

Route::middleware(['admin.auth'])->group(function () {
    Route::get('/admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

    //Dashboard
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    //User Registration
    Route::get('/admin/index', [UserController::class, 'index'])->name('admin.index');
    Route::get('/admin/create', [UserController::class, 'create'])->name('admin.create');
    Route::post('/admin/store', [UserController::class, 'store'])->name('admin.store');
    Route::get('/admin/show/{id}', [UserController::class, 'show'])->name('admin.show');
    Route::get('/admin/edit/{id}', [UserController::class, 'edit'])->name('admin.edit');
    Route::put('/admin/update/{id}', [UserController::class, 'update'])->name('admin.update');
    Route::delete('/admin/destroy/{id}', [UserController::class, 'destroy'])->name('admin.destroy');

    //rig user master
    Route::get('/admin/rig_users', [RigUserController::class, 'index'])->name('admin.rig_users.index');
    Route::get('/admin/rig_users/create', [RigUserController::class, 'create'])->name('admin.rig_users.create');
    Route::post('/admin/rig_users', [RigUserController::class, 'store'])->name('admin.rig_users.store');
    Route::get('/admin/rig_users/{id}', [RigUserController::class, 'show'])->name('admin.rig_users.show');
    Route::get('/admin/rig_users/{id}/edit', [RigUserController::class, 'edit'])->name('admin.rig_users.edit');
    Route::post('/admin/rig_users/{id}', [RigUserController::class, 'update'])->name('admin.rig_users.update');
    Route::delete('/admin/rig_users/{id}', [RigUserController::class, 'destroy'])->name('admin.rig_users.destroy');

    //EDP master
    Route::get('/admin/edp', [EdpController::class, 'index'])->name('admin.edp.index');
    Route::get('/admin/edp/create', [EdpController::class, 'create'])->name('admin.edp.create');
    Route::post('/admin/edp', [EdpController::class, 'store'])->name('admin.edp.store');
    Route::get('/admin/edp/{id}/edit', [EdpController::class, 'edit'])->name('admin.edp.edit');
    Route::post('/admin/edp/update', [EdpController::class, 'update'])->name('admin.edp.update');
    Route::post('/admin/edp/delete', [EdpController::class, 'destroy'])->name('admin.edp.destroy');

    //Category master
    Route::get('/admin/category', [CategoryController::class, 'index'])->name('admin.category.index');
    Route::get('/admin/category/create', [CategoryController::class, 'create'])->name('admin.category.create');
    Route::post('/admin/category', [CategoryController::class, 'store'])->name('admin.category.store');
    Route::get('/admin/category/{id}/edit', [CategoryController::class, 'edit'])->name('admin.category.edit');
    Route::post('/admin/category/update', [CategoryController::class, 'update'])->name('admin.category.update');
    Route::post('/admin/category/delete', [CategoryController::class, 'destroy'])->name('admin.category.destroy');
});


//User Portal section

//Authentication
Route::get('/user/login', [LoginController::class, 'index'])->name('user.login');
Route::post('/user/authenticate', [LoginController::class, 'authenticate'])->name('user.authenticate');


Route::middleware(['auth'])->group(function () {
    Route::get('/user/logout', [LoginController::class, 'logout'])->name('user.logout');

    //Registration of users
    Route::get('/user/register', [LoginController::class, 'register'])->name('user.register');
    Route::post('/user/registration', [LoginController::class, 'registerSubmit'])->name('user.registerSubmit');

    //Dashboard
    Route::get('/user/dashboard', [DashboardController::class, 'index'])->name('user.dashboard');

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
    Route::get('/stock_filter',[StockController::class, 'stock_filter'])->name('stock_filter');



    //Request stock
    Route::prefix('request-stock')->group(function () {
        Route::get('/list', [RequestStockController::class, 'RequestStockList'])->name('request_stock_list');
        Route::get('/add', [RequestStockController::class, 'RequestStockAdd'])->name('request_stock_add');
        Route::post('/addSubmit', [RequestStockController::class, 'RequestStockAddPost'])->name('request_stock_add.post');
        Route::get('/view', [RequestStockController::class, 'RequestStockViewPost'])->name('request_stock_view.get');
        Route::post('/filter', [RequestStockController::class, 'request_stock_filter'])->name('request_stock_filter');
    });

    //User mapping
    Route::get('/mapuserlist', [LoginController::class, 'mapuserlist'])->name('map_all_user_list');
    Route::get('/mapuseraddstocklist', [LoginController::class, 'mapuserstockview'])->name('map_user_stock_list');
    Route::post('/mapusergetdata', [LoginController::class, 'mapuserdataget'])->name('map_all_user_data.post');
    Route::post('/mapspecificuserdata', [LoginController::class, 'mapspecificuserdata'])->name('map_user_data_specific.post');

});

//PDF Section
Route::get('/stock-list/pdf', [StockController::class, 'downloadPdf'])->name('stock_list_pdf');

//Reset Password (No Auth Required)
Route::get('/forgotpassword', [LoginController::class, 'forgotpassword'])->name('forgotpassword');
Route::post('/submitpassword', [LoginController::class, 'submitpassword'])->name('submitpassword');
Route::get('/reset-password/{user_id}/{token}', [LoginController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [LoginController::class, 'updatePassword'])->name('password.update');
