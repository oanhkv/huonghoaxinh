<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Voucher;
use App\Services\PaymentQrService;
use App\Services\ShippingDistanceService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PaymentController extends Controller
{
    public function checkout()
    {
        $this->expirePendingOrders();

        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
        $pendingOrder = null;

        if ($cartItems->isEmpty() && Session::has('card_order_id')) {
            $pendingOrder = Order::with('orderItems.product')->find(Session::get('card_order_id'));
            if ($pendingOrder) {
                $cartItems = $pendingOrder->orderItems;
            }
        }

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống. Vui lòng thêm sản phẩm trước khi thanh toán.');
        }

        $paymentMethod = old('payment_method', $pendingOrder && $pendingOrder->status === 'pending' ? 'card' : 'cod');
        $shippingAddress = old('shipping_address', optional($pendingOrder)->shipping_address);
        $phone = old('phone', optional($pendingOrder)->phone);
        $note = old('note', optional($pendingOrder)->note);
        $voucherCode = old('voucher_code', '');

        $distanceKm = 0.0;
        $distanceGeocoded = true;
        if (trim((string) $shippingAddress) !== '') {
            $dist = app(ShippingDistanceService::class)->distanceFromShopKm($shippingAddress);
            $distanceKm = $dist['distance_km'];
            $distanceGeocoded = $dist['geocoded'];
        }

        try {
            $pricing = $this->calculatePricing(
                $cartItems,
                $voucherCode !== '' ? trim($voucherCode) : null,
                $distanceKm
            );
        } catch (ValidationException $e) {
            $pricing = $this->calculatePricing($cartItems, null, $distanceKm);
        }

        $subtotal = $pricing['subtotal'];
        $shipping = $pricing['shipping'];
        $discount = $pricing['discount'];
        $total = $pricing['total'];

        return view('frontend.checkout.index', compact(
            'cartItems',
            'subtotal',
            'shipping',
            'discount',
            'total',
            'paymentMethod',
            'shippingAddress',
            'phone',
            'note',
            'pendingOrder',
            'distanceKm',
            'distanceGeocoded',
            'voucherCode'
        ));
    }

    public function process(Request $request)
    {
        $this->expirePendingOrders();

        $request->validate([
            'shipping_address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'payment_method' => 'required|in:cod,card',
            'note' => 'nullable|string|max:500',
            'voucher_code' => 'nullable|string|max:50',
        ]);

        $pendingOrder = null;
        if (Session::has('card_order_id')) {
            $pendingOrder = Order::find(Session::get('card_order_id'));
        }

        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
        if ($cartItems->isEmpty() && $pendingOrder && $pendingOrder->status === 'pending') {
            $cartItems = $pendingOrder->orderItems;
        }

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        $voucherCode = trim((string) $request->voucher_code);
        $distResult = app(ShippingDistanceService::class)->distanceFromShopKm($request->shipping_address);
        $distanceKm = $distResult['distance_km'];
        $geocodedAddress = $distResult['geocoded'];
        $pricing = $this->calculatePricing($cartItems, $voucherCode !== '' ? $voucherCode : null, $distanceKm);
        $subtotal = $pricing['subtotal'];
        $shipping = $pricing['shipping'];
        $discount = $pricing['discount'];
        $total = $pricing['total'];
        $voucher = $pricing['voucher'];

        if ($request->payment_method === 'card' && $pendingOrder && $pendingOrder->status === 'pending') {
            $pendingOrder->update([
                'shipping_address' => $request->shipping_address,
                'phone' => $request->phone,
                'note' => $request->note,
            ]);

            return redirect()->route('checkout.card', $pendingOrder)->with('success', 'Vui lòng hoàn tất thanh toán thẻ để xác nhận đơn hàng.');
        }

        $order = new Order([
            'user_id' => Auth::id(),
            'order_code' => 'HD'.strtoupper(Str::random(6)),
            'total_amount' => $total,
            'status' => $request->payment_method === 'card' ? 'pending' : 'cod',
            'shipping_address' => $request->shipping_address,
            'phone' => $request->phone,
            'note' => $this->buildOrderNote($request->note, $voucher?->code, $distanceKm, $shipping, $discount, $geocodedAddress),
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

        if ($voucher) {
            $voucher->increment('used_count');
        }

        Cart::where('user_id', Auth::id())->delete();

        if ($request->payment_method === 'card') {
            Session::put('card_order_id', $order->id);

            return redirect()->route('checkout.card', $order)->with('success', 'Đơn hàng đã tạo. Vui lòng thanh toán bằng thẻ để hoàn tất.');
        }

        return redirect()->route('checkout.success', ['order' => $order->id])->with('success', 'Đơn hàng của bạn đã được tạo thành công.');
    }

    public function cardPayment(Order $order)
    {
        $this->expirePendingOrders();

        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->status !== 'pending') {
            return redirect()->route('orders.history')->with('error', 'Đơn hàng này không còn chờ thanh toán thẻ.');
        }

        $order->load('orderItems.product');

        $paymentQr = PaymentQrService::forOrder($order);
        $vietqrBankId = config('payment.vietqr.bank_id');
        $vietqrAccountNo = config('payment.vietqr.account_no');
        $vietqrAccountName = config('payment.vietqr.account_name');
        $momoPhone = config('payment.momo.phone');
        $momoDisplayName = config('payment.momo.display_name');

        return view('frontend.checkout.card', compact(
            'order',
            'paymentQr',
            'vietqrBankId',
            'vietqrAccountNo',
            'vietqrAccountName',
            'momoPhone',
            'momoDisplayName'
        ));
    }

    public function confirmCardPayment(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->status !== 'pending') {
            return redirect()->route('orders.history')->with('error', 'Đơn hàng không ở trạng thái chờ thanh toán.');
        }

        $order->update(['status' => 'paid']);
        Session::forget('card_order_id');

        return redirect()->route('checkout.success', ['order' => $order->id])->with('success', 'Thanh toán thẻ thành công. Đơn hàng đã được xác nhận.');
    }

    public function success(Request $request)
    {
        $order = Order::where('id', $request->order)
            ->where('user_id', Auth::id())
            ->firstOrFail();

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

        if (! in_array($order->status, ['paid', 'cod'])) {
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

    private function calculatePricing($cartItems, ?string $voucherCode, float $distanceKm): array
    {
        $subtotal = (float) $cartItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        $shipping = $distanceKm <= 10 ? 0 : 30000;
        $voucher = null;
        $discount = 0;

        if ($voucherCode) {
            $voucher = Voucher::where('code', $voucherCode)
                ->where('is_active', true)
                ->first();

            if (! $voucher) {
                throw ValidationException::withMessages([
                    'voucher_code' => 'Mã giảm giá không hợp lệ.',
                ]);
            }

            if ($voucher->starts_at && now()->lt($voucher->starts_at)) {
                throw ValidationException::withMessages([
                    'voucher_code' => 'Mã giảm giá chưa đến thời gian áp dụng.',
                ]);
            }

            if ($voucher->ends_at && now()->gt($voucher->ends_at)) {
                throw ValidationException::withMessages([
                    'voucher_code' => 'Mã giảm giá đã hết hạn.',
                ]);
            }

            if ($voucher->usage_limit !== null && $voucher->used_count >= $voucher->usage_limit) {
                throw ValidationException::withMessages([
                    'voucher_code' => 'Mã giảm giá đã hết lượt sử dụng.',
                ]);
            }

            if ($voucher->min_order_amount !== null && $subtotal < $voucher->min_order_amount) {
                throw ValidationException::withMessages([
                    'voucher_code' => 'Đơn hàng chưa đạt giá trị tối thiểu để áp dụng mã.',
                ]);
            }

            if ($voucher->type === 'percentage') {
                $discount = $subtotal * ((float) $voucher->value / 100);
            } else {
                $discount = (float) $voucher->value;
            }

            if ($voucher->max_discount_amount !== null) {
                $discount = min($discount, (float) $voucher->max_discount_amount);
            }

            $discount = min($discount, $subtotal);
        }

        $total = max(0, $subtotal + $shipping - $discount);

        return [
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'discount' => $discount,
            'total' => $total,
            'voucher' => $voucher,
        ];
    }

    private function buildOrderNote(?string $note, ?string $voucherCode, float $distanceKm, float $shipping, float $discount, bool $geocoded = true): string
    {
        $pieces = [];
        if ($note) {
            $pieces[] = trim($note);
        }
        $pieces[] = 'Khoang cach tu cua hang: '.rtrim(rtrim(number_format($distanceKm, 2, '.', ''), '0'), '.').' km'
            .($geocoded ? '' : ' (uoc tinh, khong geocode duoc dia chi)');
        $pieces[] = 'Phi ship: '.number_format($shipping, 0, ',', '.').' VND';
        if ($voucherCode) {
            $pieces[] = 'Voucher: '.$voucherCode.' (-'.number_format($discount, 0, ',', '.').' VND)';
        }

        return implode(' | ', $pieces);
    }
}
