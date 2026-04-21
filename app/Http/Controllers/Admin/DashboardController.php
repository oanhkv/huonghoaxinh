<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // Thống kê hôm nay
        $totalOrdersToday = Order::whereDate('created_at', $today)->count();
        $totalRevenueToday = Order::whereDate('created_at', $today)->sum('total_amount');
        $totalProductsSoldToday = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereDate('orders.created_at', $today)
            ->sum('order_items.quantity');

        $newCustomersToday = User::whereDate('created_at', $today)->count();

        // Doanh thu 7 ngày gần nhất
        $revenueLast7Days = [];
        $labels = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $labels[] = $date->format('d/m');

            $revenue = Order::whereDate('created_at', $date)->sum('total_amount');
            $revenueLast7Days[] = round($revenue / 1000000, 1); // Đơn vị triệu đồng
        }

        // Đơn hàng gần đây
        $recentOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalOrdersToday',
            'totalRevenueToday',
            'totalProductsSoldToday',
            'newCustomersToday',
            'revenueLast7Days',
            'labels',
            'recentOrders'
        ));
    }
}
