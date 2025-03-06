<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User;
use App\Http\Controllers\StockController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\admin\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/user/login',[LoginController::class,'index'])->name('user.login');
Route::get('/user/register',[LoginController::class,'register'])->name('user.register');
Route::get('/user/logout',[LoginController::class,'logout'])->name('user.logout');

Route::post('/user/registration',[LoginController::class,'registerSubmit'])->name('user.registerSubmit');

Route::post('/user/authenticate',[LoginController::class,'authenticate'])->name('user.authenticate');
Route::get('/user/dashboard',[DashboardController::class,'index'])->name('user.dashboard');


Route::get('/admin/login',[AdminLoginController::class,'index'])->name('admin.login');
Route::get('/admin/dashboard',[AdminDashboardController::class,'index'])->name('admin.dashboard');
Route::post('/admin/authenticate',[AdminLoginController::class,'authenticate'])->name('admin.authenticate');
Route::get('/admin/logout',[AdminLoginController::class,'logout'])->name('admin.logout');


Route::get('/admin/index',[UserController::class,'index'])->name('admin.index');
Route::get('/admin/create',[UserController::class,'create'])->name('admin.create');
Route::post('/admin/store',[UserController::class,'store'])->name('admin.store');
Route::get('/admin/show/{id}',[UserController::class,'show'])->name('admin.show');
Route::get('/admin/edit/{id}',[UserController::class,'edit'])->name('admin.edit');	
Route::put('/admin/update/{id}',[UserController::class,'update'])->name('admin.update');	
Route::delete('/admin/destroy/{id}',[UserController::class,'destroy'])->name('admin.destroy');



Route::get('/forgotpassword', [LoginController::class, 'forgotpassword'])->name('forgotpassword');

Route::post('/submitpassword', [LoginController::class, 'submitpassword'])->name('submitpassword');

Route::any('/add_stock', [StockController::class, 'add_stock'])->name('add_stock');
Route::post('/stockSubmit', [StockController::class, 'stockSubmit'])->name('stockSubmit');
Route::any('/stock_list', [StockController::class, 'stock_list'])->name('stock_list');
Route::any('/all_stock_list', [StockController::class, 'all_stock_list'])->name('all_stock_list');
Route::get('/import', [StockController::class, 'showImportForm'])->name('import_stock');;
Route::post('/import_bulk', [StockController::class, 'import'])->name('stock.import');
