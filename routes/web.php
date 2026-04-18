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
use App\Http\Controllers\Frontend\ProductController as FrontendProductController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\WishlistController;
use App\Http\Controllers\Frontend\BlogController;
use App\Http\Controllers\Frontend\PaymentController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/product/{slug}', [FrontendProductController::class, 'show'])->name('product.show');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{post:slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'submitContact'])->name('contact.submit');
// Trang Giới thiệu (About Us)
Route::get('/about', [HomeController::class, 'about'])->name('about');
// Trang Mã giảm giá (Vouchers)
Route::get('/vouchers', [HomeController::class, 'vouchers'])->name('vouchers');

// Debug Cart (chỉ khi auth)
Route::middleware('auth')->get('/debug-cart', function() {
    return view('frontend.debug-cart');
});

// Giỏ hàng routes (bắt buộc đăng nhập)
Route::middleware('auth')->prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('add', [CartController::class, 'add'])->name('add');
    Route::post('{cart}/update', [CartController::class, 'update'])->name('update');
    Route::delete('{cart}/remove', [CartController::class, 'remove'])->name('remove');
    Route::post('clear', [CartController::class, 'clear'])->name('clear');
    Route::get('get', [CartController::class, 'getCart'])->name('get');
    Route::post('apply-voucher', [CartController::class, 'applyVoucher'])->name('apply-voucher');
});

// Yêu thích routes (bắt buộc đăng nhập)
Route::middleware('auth')->prefix('wishlist')->name('wishlist.')->group(function () {
    Route::get('/', [WishlistController::class, 'index'])->name('index');
    Route::post('add', [WishlistController::class, 'add'])->name('add');
    Route::post('remove', [WishlistController::class, 'remove'])->name('remove');
    Route::post('toggle', [WishlistController::class, 'toggle'])->name('toggle');
    Route::get('check/{productId}', [WishlistController::class, 'isInWishlist'])->name('check');
});

Route::middleware('auth')->group(function () {
    Route::get('/checkout', [PaymentController::class, 'checkout'])->name('checkout.index');
    Route::post('/checkout', [PaymentController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/verify', [PaymentController::class, 'verify'])->name('checkout.verify');
    Route::post('/checkout/verify', [PaymentController::class, 'submitVerify'])->name('checkout.verify.submit');
    Route::get('/checkout/success', [PaymentController::class, 'success'])->name('checkout.success');
    Route::get('/orders/history', [PaymentController::class, 'history'])->name('orders.history');
    Route::post('/orders/{order}/cancel', [PaymentController::class, 'cancel'])->name('orders.cancel');
    Route::post('/orders/{order}/confirm-received', [PaymentController::class, 'confirmReceived'])->name('orders.confirmReceived');
});

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