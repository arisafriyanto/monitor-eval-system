<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ReportAdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->name('login.process');
Route::delete('/logout', [LogoutController::class, 'logout'])->name('logout');

Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('/reports', [ReportAdminController::class, 'index'])->name('admin.reports.index');
    Route::get('/reports/{report}', [ReportAdminController::class, 'show'])->name('admin.reports.show');

    Route::post('reports/{report}/approve', [ReportAdminController::class, 'approve'])->name('admin.reports.approve');
    Route::post('reports/{report}/reject', [ReportAdminController::class, 'reject'])->name('admin.reports.reject');
});

Route::middleware(['auth', 'role:regional'])->group(function () {
    Route::redirect('/', '/reports');
    Route::resource('reports', ReportController::class);

    Route::get('/indonesia/cities/{provinceCode}', [RegionController::class, 'getCities'])->name('indonesia.cities');
    Route::get('/indonesia/districts/{cityCode}', [RegionController::class, 'getDistricts'])->name('indonesia.districts');
});
