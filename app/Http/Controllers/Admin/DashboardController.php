<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $monthStart = Carbon::now()->startOfMonth();

        // Stats hôm nay
        $totalOrdersToday = Order::whereDate('created_at', $today)->count();
        $totalRevenueToday = Order::whereDate('created_at', $today)->sum('total_amount');
        $totalProductsSoldToday = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereDate('orders.created_at', $today)
            ->sum('order_items.quantity');
        $newCustomersToday = User::whereDate('created_at', $today)->count();

        // Stats hôm qua (so sánh % trend)
        $totalOrdersYesterday = Order::whereDate('created_at', $yesterday)->count();
        $totalRevenueYesterday = Order::whereDate('created_at', $yesterday)->sum('total_amount');

        $orderTrend = $this->trendPercent($totalOrdersToday, $totalOrdersYesterday);
        $revenueTrend = $this->trendPercent($totalRevenueToday, $totalRevenueYesterday);

        // Stats nhanh
        $pendingOrders = Order::where('status', 'pending')->count();
        $unreadMessages = Schema::hasTable('contact_messages')
            ? ContactMessage::where('status', 'new')->count()
            : 0;
        $lowStockProducts = Product::where('is_active', true)->where('stock', '<=', 5)->count();
        $totalProducts = Product::where('is_active', true)->count();

        // Tổng tháng
        $monthOrders = Order::where('created_at', '>=', $monthStart)->count();
        $monthRevenue = Order::where('created_at', '>=', $monthStart)->sum('total_amount');

        // Doanh thu 7 ngày
        $revenueLast7Days = [];
        $labels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $labels[] = $date->format('d/m');
            $revenueLast7Days[] = round(Order::whereDate('created_at', $date)->sum('total_amount') / 1000000, 2);
        }

        // Top sản phẩm bán chạy 30 ngày
        $topProducts = OrderItem::query()
            ->select('product_id', DB::raw('SUM(quantity) as sold'))
            ->whereHas('order', fn ($q) => $q->where('created_at', '>=', Carbon::now()->subDays(30)))
            ->groupBy('product_id')
            ->orderByDesc('sold')
            ->take(5)
            ->with('product:id,name,price,image,stock')
            ->get();

        // Đơn hàng gần đây
        $recentOrders = Order::with('user')->latest()->take(6)->get();

        return view('admin.dashboard', compact(
            'totalOrdersToday', 'totalRevenueToday', 'totalProductsSoldToday', 'newCustomersToday',
            'orderTrend', 'revenueTrend',
            'pendingOrders', 'unreadMessages', 'lowStockProducts', 'totalProducts',
            'monthOrders', 'monthRevenue',
            'revenueLast7Days', 'labels',
            'topProducts', 'recentOrders'
        ));
    }

    private function trendPercent(int|float $current, int|float $previous): array
    {
        if ($previous == 0) {
            return ['value' => $current > 0 ? 100 : 0, 'up' => $current >= 0];
        }
        $diff = (($current - $previous) / $previous) * 100;
        return ['value' => round(abs($diff), 1), 'up' => $diff >= 0];
    }
}
