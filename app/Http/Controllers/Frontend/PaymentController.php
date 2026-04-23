<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\Voucher;
use App\Services\OrderInventoryService;
use App\Services\PaymentQrService;
use App\Services\ShippingDistanceService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PaymentController extends Controller
{
    private const BUY_NOW_SESSION_KEY = 'checkout_buy_now_item';

    public function initBuyNow(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'nullable|numeric|min:0',
            'variant' => 'nullable|string|max:255',
        ]);

        $product = Product::findOrFail((int) $request->product_id);
        if (! $product->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm hiện đã ngừng bán.',
            ], 422);
        }
        if ((int) $product->stock < (int) $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Số lượng vượt quá tồn kho hiện tại.',
            ], 422);
        }

        Session::put(self::BUY_NOW_SESSION_KEY, [
            'product_id' => (int) $product->id,
            'quantity' => (int) $request->quantity,
            'price' => (float) ($request->unit_price ?? $product->price),
            'variant' => trim((string) $request->variant),
        ]);

        return response()->json([
            'success' => true,
            'redirect_url' => route('checkout.index', ['mode' => 'buy_now']),
        ]);
    }

    public function checkout()
    {
        $this->expirePendingOrders();

        $isBuyNow = request('mode') === 'buy_now';
        $cartItems = $isBuyNow
            ? $this->getBuyNowItemsFromSession()
            : Cart::where('user_id', Auth::id())->with('product')->get();
        $pendingOrder = null;

        if (! $isBuyNow && $cartItems->isEmpty() && Session::has('card_order_id')) {
            $pendingOrder = Order::with('orderItems.product')->find(Session::get('card_order_id'));
            if ($pendingOrder) {
                $cartItems = $pendingOrder->orderItems;
            }
        }

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống. Vui lòng thêm sản phẩm trước khi thanh toán.');
        }

        $stockIssue = app(OrderInventoryService::class)->findStockIssue($cartItems);
        if ($stockIssue) {
            return redirect()->route('cart.index')->with('error', $stockIssue);
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
            'isBuyNow',
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

    public function applyVoucherPreview(Request $request)
    {
        $request->validate([
            'voucher_code' => 'nullable|string|max:50',
            'shipping_address' => 'nullable|string|max:255',
            'checkout_source' => 'nullable|string|max:20',
        ]);

        $isBuyNow = $request->input('checkout_source') === 'buy_now';
        $cartItems = $isBuyNow
            ? $this->getBuyNowItemsFromSession()
            : Cart::where('user_id', Auth::id())->with('product')->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy sản phẩm để áp dụng mã giảm giá.',
            ], 422);
        }

        $distanceKm = 0.0;
        $shippingAddress = trim((string) $request->input('shipping_address', ''));
        if ($shippingAddress !== '') {
            $dist = app(ShippingDistanceService::class)->distanceFromShopKm($shippingAddress);
            $distanceKm = (float) $dist['distance_km'];
        }

        $voucherCode = trim((string) $request->input('voucher_code', ''));
        try {
            $pricing = $this->calculatePricing($cartItems, $voucherCode !== '' ? $voucherCode : null, $distanceKm);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first('voucher_code') ?: 'Mã giảm giá không hợp lệ.',
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => $voucherCode !== '' ? 'Áp dụng mã giảm giá thành công.' : 'Đã bỏ mã giảm giá.',
            'subtotal' => (float) $pricing['subtotal'],
            'shipping' => (float) $pricing['shipping'],
            'discount' => (float) $pricing['discount'],
            'total' => (float) $pricing['total'],
        ]);
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

        $isBuyNow = $request->input('checkout_source') === 'buy_now';
        $pendingOrder = null;
        if (! $isBuyNow && Session::has('card_order_id')) {
            $pendingOrder = Order::find(Session::get('card_order_id'));
        }

        $cartItems = $isBuyNow
            ? $this->getBuyNowItemsFromSession()
            : Cart::where('user_id', Auth::id())->with('product')->get();

        if (! $isBuyNow && $cartItems->isEmpty() && $pendingOrder && $pendingOrder->status === 'pending') {
            $cartItems = $pendingOrder->orderItems;
        }

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        $stockIssue = app(OrderInventoryService::class)->findStockIssue($cartItems);
        if ($stockIssue) {
            return redirect()->route('cart.index')->with('error', $stockIssue);
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

        $inventory = app(OrderInventoryService::class);
        $order = DB::transaction(function () use ($request, $total, $voucher, $distanceKm, $shipping, $discount, $geocodedAddress, $cartItems, $inventory, $isBuyNow) {
            $inventory->reserveForCartItems($cartItems);

            $order = new Order([
                'user_id' => Auth::id(),
                'order_code' => 'HD'.strtoupper(Str::random(6)),
                'total_amount' => $total,
                'status' => $request->payment_method === 'card' ? 'pending' : 'cod',
                'stock_deducted' => true,
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

            if (! $isBuyNow) {
                Cart::where('user_id', Auth::id())->delete();
            } else {
                Session::forget(self::BUY_NOW_SESSION_KEY);
            }

            return $order;
        });

        if ($request->payment_method === 'card') {
            Session::put('card_order_id', $order->id);

            return redirect()->route('checkout.card', $order)->with('success', 'Đơn hàng đã tạo. Vui lòng thanh toán bằng thẻ để hoàn tất.');
        }

        return redirect()->route('checkout.success', ['order' => $order->id])->with('success', 'Đơn hàng của bạn đã được tạo thành công.');
    }

    private function getBuyNowItemsFromSession()
    {
        $item = Session::get(self::BUY_NOW_SESSION_KEY);
        if (! is_array($item) || empty($item['product_id'])) {
            return collect();
        }

        $product = Product::find((int) $item['product_id']);
        if (! $product) {
            return collect();
        }

        return collect([
            (object) [
                'id' => 'buy-now-'.$product->id,
                'user_id' => Auth::id(),
                'product_id' => (int) $product->id,
                'quantity' => max(1, (int) ($item['quantity'] ?? 1)),
                'price' => (float) ($item['price'] ?? $product->price),
                'variant' => (string) ($item['variant'] ?? ''),
                'product' => $product,
            ],
        ]);
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
        $reviewedProductIds = Review::where('user_id', Auth::id())
            ->pluck('product_id')
            ->unique()
            ->map(fn ($id) => (int) $id)
            ->all();

        return view('frontend.orders.history', compact('orders', 'filter', 'reviewedProductIds'));
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

        DB::transaction(function () use ($order) {
            app(OrderInventoryService::class)->restoreForOrder($order);
            $order->update(['status' => 'cancelled']);
        });

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
        $expiredOrders = Order::where('status', 'pending')
            ->where('created_at', '<', Carbon::now()->subMinutes(10))
            ->with('orderItems')
            ->get();

        foreach ($expiredOrders as $order) {
            DB::transaction(function () use ($order) {
                app(OrderInventoryService::class)->restoreForOrder($order);
                $order->update(['status' => 'cancelled']);
            });
        }
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
