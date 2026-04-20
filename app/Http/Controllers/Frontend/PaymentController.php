<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function checkout()
    {
        $this->expirePendingOrders();

        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
        $pendingOrder = null;

        if ($cartItems->isEmpty() && Session::has('payment_order_id')) {
            $pendingOrder = Order::with('orderItems.product')->find(Session::get('payment_order_id'));
            if ($pendingOrder) {
                $cartItems = $pendingOrder->orderItems;
            }
        }

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống. Vui lòng thêm sản phẩm trước khi thanh toán.');
        }

        $subtotal = $cartItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        $shipping = 0;
        $total = $subtotal + $shipping;

        $paymentMethod = old('payment_method', $pendingOrder && $pendingOrder->status === 'pending' ? 'online' : 'cod');
        $shippingAddress = old('shipping_address', optional($pendingOrder)->shipping_address);
        $phone = old('phone', optional($pendingOrder)->phone);
        $note = old('note', optional($pendingOrder)->note);

        return view('frontend.checkout.index', compact('cartItems', 'subtotal', 'shipping', 'total', 'paymentMethod', 'shippingAddress', 'phone', 'note', 'pendingOrder'));
    }

    public function process(Request $request)
    {
        $this->expirePendingOrders();

        $request->validate([
            'shipping_address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'payment_method' => 'required|in:cod,online',
            'note' => 'nullable|string|max:500',
        ]);

        $pendingOrder = null;
        if (Session::has('payment_order_id')) {
            $pendingOrder = Order::find(Session::get('payment_order_id'));
        }

        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
        if ($cartItems->isEmpty() && $pendingOrder && $pendingOrder->status === 'pending') {
            $cartItems = $pendingOrder->orderItems;
        }

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        $subtotal = $cartItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        $shipping = 0;
        $total = $subtotal + $shipping;

        $pendingOrder = null;
        if (Session::has('payment_order_id')) {
            $pendingOrder = Order::find(Session::get('payment_order_id'));
        }

        if ($request->payment_method === 'online' && $pendingOrder && $pendingOrder->status === 'pending') {
            $pendingOrder->update([
                'shipping_address' => $request->shipping_address,
                'phone' => $request->phone,
                'note' => $request->note,
            ]);

            return redirect()->route('checkout.verify')->with('success', 'Một mã xác nhận đã được gửi đến số điện thoại của bạn.');
        }

        $order = new Order([
            'user_id' => Auth::id(),
            'order_code' => 'HD' . strtoupper(Str::random(6)),
            'total_amount' => $total,
            'status' => $request->payment_method === 'online' ? 'pending' : 'cod',
            'shipping_address' => $request->shipping_address,
            'phone' => $request->phone,
            'note' => $request->note,
        ]);
        $order->created_at = now();
        $order->save();

        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
            ]);
        }

        Cart::where('user_id', Auth::id())->delete();

        if ($request->payment_method === 'online') {
            $otpCode = rand(100000, 999999);
            Session::put('payment_order_id', $order->id);
            Session::put('payment_otp_code', $otpCode);
            Session::put('payment_phone', $request->phone);

            return redirect()->route('checkout.verify')->with('success', 'Một mã xác nhận đã được gửi đến số điện thoại của bạn.');
        }

        return redirect()->route('checkout.success', ['order' => $order->id])->with('success', 'Đơn hàng của bạn đã được tạo thành công.');
    }

    public function verify()
    {
        $this->expirePendingOrders();

        if (!Session::has('payment_order_id') || !Session::has('payment_otp_code')) {
            return redirect()->route('checkout.index')->with('error', 'Không có giao dịch cần xác thực.');
        }

        $order = Order::find(Session::get('payment_order_id'));
        if (!$order || $order->status !== 'pending') {
            return redirect()->route('orders.history')->with('error', 'Đơn hàng không còn ở trạng thái chờ xác thực.');
        }

        return view('frontend.checkout.verify');
    }

    public function submitVerify(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        if (!Session::has('payment_order_id') || !Session::has('payment_otp_code')) {
            return redirect()->route('checkout.index')->with('error', 'Không có giao dịch cần xác thực.');
        }

        $storedOtp = Session::get('payment_otp_code');
        $orderId = Session::get('payment_order_id');

        if ($request->otp !== (string)$storedOtp) {
            return back()->withErrors(['otp' => 'Mã xác nhận không đúng. Vui lòng thử lại.']);
        }

        $order = Order::find($orderId);

        if (!$order) {
            return redirect()->route('checkout.index')->with('error', 'Đơn hàng không tồn tại.');
        }

        $order->update(['status' => 'paid']);

        Session::forget(['payment_order_id', 'payment_otp_code', 'payment_phone']);

        return redirect()->route('checkout.success', ['order' => $order->id])->with('success', 'Thanh toán thành công. Đơn hàng của bạn đã được hoàn tất.');
    }

    public function success(Request $request)
    {
        $order = Order::find($request->order);

        return view('frontend.checkout.success', compact('order'));
    }

    public function history()
    {
        $this->expirePendingOrders();

        $filter = request('status', 'all');

        $query = Order::where('user_id', Auth::id())
            ->with('orderItems.product')
            ->orderBy('created_at', 'desc');

        if ($filter === 'pending') {
            $query->where('status', 'pending');
        } elseif ($filter === 'processing') {
            $query->whereIn('status', ['paid', 'cod']);
        } elseif ($filter === 'delivered') {
            $query->where('status', 'delivered');
        } elseif ($filter === 'cancelled') {
            $query->where('status', 'cancelled');
        }

        $orders = $query->get();

        return view('frontend.orders.history', compact('orders', 'filter'));
    }

    public function cancel(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->status !== 'pending') {
            return back()->with('error', 'Chỉ đơn hàng đang chờ xác nhận mới có thể hủy.');
        }

        if ($order->created_at->diffInMinutes(Carbon::now()) > 30) {
            return back()->with('error', 'Thời hạn hủy đơn đã quá 30 phút. Không thể hủy đơn này nữa.');
        }

        $order->update(['status' => 'cancelled']);

        return back()->with('success', 'Đơn hàng đã được hủy thành công.');
    }

    public function confirmReceived(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if (!in_array($order->status, ['paid', 'cod'])) {
            return back()->with('error', 'Chỉ đơn hàng đã thanh toán hoặc COD mới có thể xác nhận nhận hàng.');
        }

        $order->update(['status' => 'delivered']);

        return back()->with('success', 'Cảm ơn bạn, đơn hàng đã được đánh dấu là đã nhận.');
    }

    private function expirePendingOrders()
    {
        Order::where('status', 'pending')
            ->where('created_at', '<', Carbon::now()->subMinutes(10))
            ->update(['status' => 'cancelled']);
    }
}
