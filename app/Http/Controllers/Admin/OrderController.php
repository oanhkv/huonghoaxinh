<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Voucher;
use App\Models\VoucherUserUsage;
use App\Services\OrderInventoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        // Xóa các đơn có ngày đặt trong tương lai (dữ liệu không hợp lệ)
        Order::where('created_at', '>', now())->delete();

        // Tìm ID của 3 đơn mới nhất để hiển thị badge NEW (nếu chưa xem)
        $newOrderIds = Order::where('created_at', '<=', now())
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->pluck('id')
            ->toArray();

        // Hiển thị theo ngày đặt (mới nhất lên đầu)
        $query = Order::with('user')->orderBy('created_at', 'desc');

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

        $orders = $query->paginate(10)->withQueryString();

        // Đánh dấu thuộc tính tạm thời is_new cho mỗi order (dùng trong view)
        $orders->getCollection()->transform(function ($o) use ($newOrderIds) {
            $o->is_new = (in_array($o->id, $newOrderIds) && is_null($o->viewed_at));
            return $o;
        });

        return view('admin.orders.index', compact('orders', 'newOrderIds'));
    }

    public function show(Order $order)
    {
        $order->load('orderItems.product');

        // Đánh dấu là đã xem (nếu cột viewed_at đã được thêm vào database)
        if (\Illuminate\Support\Facades\Schema::hasColumn('orders', 'viewed_at')) {
            if (! $order->viewed_at) {
                $order->viewed_at = now();
                $order->save();
            }
        }

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
                $usages = VoucherUserUsage::where('order_id', $order->id)->get();
                foreach ($usages as $usage) {
                    Voucher::where('id', $usage->voucher_id)->where('used_count', '>', 0)->decrement('used_count');
                    $usage->delete();
                }
            }
            $order->update(['status' => $newStatus]);
        });

        return redirect()->back()
            ->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
    }
}
