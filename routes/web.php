<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// })->name('home');

Route::view('/', 'dashboard');
Route::resource('product', ProductController::class);
Route::resource('category', CategoryController::class);
Route::get('/category/{id}/count', [CategoryController::class, 'count'])->name('category.count');
Route::resource('customer', CustomerController::class);
Route::resource('invoice', InvoiceController::class);
Route::get('/report', [ReportController::class, "reports"]);