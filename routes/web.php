<?php

use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// })->name('home');

Route::view('/', 'dashboard');
Route::get('/report', [ReportController::class, "reports"]);