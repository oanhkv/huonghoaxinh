<?php

use Illuminate\Support\Facades\Route;

// ==================== FRONTEND ====================
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ShopController;

// ==================== ADMIN ====================
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\RevenueController;
use App\Http\Controllers\Admin\WebsiteSettingController;

// ==================== FRONTEND ROUTES ====================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [ShopController::class, 'index'])->name('shop');
// Trang Giới thiệu (About Us)
Route::get('/about', [HomeController::class, 'about'])->name('about');

// ==================== ADMIN ROUTES ====================
Route::prefix('admin')
    ->middleware(['auth', 'admin'])
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Quản lý Sản phẩm
        Route::get('products/export', [ProductController::class, 'export'])->name('products.export');
        Route::post('products/import', [ProductController::class, 'import'])->name('products.import');
        Route::resource('products', ProductController::class);

        // Quản lý Danh mục
        Route::get('categories/export', [CategoryController::class, 'export'])->name('categories.export');
        Route::post('categories/import', [CategoryController::class, 'import'])->name('categories.import');
        Route::resource('categories', CategoryController::class);

        // Quản lý Đơn hàng
        Route::resource('orders', OrderController::class);
        Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');

        // Quản lý Đánh giá
        Route::resource('reviews', ReviewController::class)->only(['index', 'destroy']);

        // Quản lý Mã giảm giá
        Route::resource('vouchers', VoucherController::class)->except(['show']);

        // Thống kê doanh thu
        Route::get('revenue', [RevenueController::class, 'index'])->name('revenue.index');
        Route::get('revenue/export', [RevenueController::class, 'export'])->name('revenue.export');

        // Quản lý tài khoản
        Route::get('users/admins', [UserController::class, 'admins'])->name('users.admins');
        Route::resource('users', UserController::class);

        // Thông tin cá nhân admin
        Route::get('profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [AdminProfileController::class, 'update'])->name('profile.update');

        // Cài đặt website
        Route::get('settings', [WebsiteSettingController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [WebsiteSettingController::class, 'update'])->name('settings.update');
    });

require __DIR__.'/auth.php';