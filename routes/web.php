<?php

use Illuminate\Support\Facades\Route;

// ==================== FRONTEND ====================
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ShopController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\FavoritesController;
use App\Http\Controllers\Frontend\ProductController as FrontendProductController;
use App\Http\Controllers\Frontend\BlogController;
use App\Http\Controllers\Frontend\ContactController;

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
Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
Route::patch('/cart/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{product}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/favorites', [FavoritesController::class, 'store'])->name('favorites.store');
Route::delete('/favorites/{product}', [FavoritesController::class, 'destroy'])->name('favorites.destroy');
Route::get('/favorites', [FavoritesController::class, 'index'])->name('favorites.index');
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/online', [CheckoutController::class, 'online'])->name('checkout.online');
Route::post('/checkout/online', [CheckoutController::class, 'processOnline'])->name('checkout.online.process');
Route::get('/checkout/sms-verify', [CheckoutController::class, 'smsSendForm'])->name('checkout.sms.form');
Route::post('/checkout/sms-verify', [CheckoutController::class, 'verifySms'])->name('checkout.sms.verify');
Route::post('/checkout/sms-resend', [CheckoutController::class, 'resendSms'])->name('checkout.sms.resend');
Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/product/{slug}', [FrontendProductController::class, 'show'])->name('product.show');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

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