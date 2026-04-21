<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderInventoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user')->latest();

        // Tìm kiếm theo mã đơn hoặc tên khách
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_code', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('orderItems.product');

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,shipping,completed,cancelled,cod,paid,delivered',
        ]);

        $newStatus = $request->status;
        if ($order->status === 'cancelled' && $newStatus !== 'cancelled') {
            return redirect()->back()->with('error', 'Đơn đã hủy không thể chuyển lại trạng thái khác vì sẽ lệch tồn kho.');
        }

        DB::transaction(function () use ($order, $newStatus) {
            if ($newStatus === 'cancelled' && $order->status !== 'cancelled') {
                app(OrderInventoryService::class)->restoreForOrder($order);
            }
            $order->update(['status' => $newStatus]);
        });

        return redirect()->back()
            ->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
    }
}
