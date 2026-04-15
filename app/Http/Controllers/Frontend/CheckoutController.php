<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        $cartItems = session('cart', []);

        if (empty($cartItems)) {
            return redirect()->route('shop')->with('error', 'Giỏ hàng đang trống. Vui lòng thêm sản phẩm trước khi thanh toán.');
        }

        $cartTotal = collect($cartItems)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        return view('frontend.checkout.index', compact('cartItems', 'cartTotal'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'province' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'ward' => 'required|string|max:255',
            'payment_method' => 'required|in:cod,online',
            'note' => 'nullable|string|max:1000',
        ]);

        $cartItems = session('cart', []);
        if (empty($cartItems)) {
            return redirect()->route('shop')->with('error', 'Giỏ hàng đang trống.');
        }

        $checkoutData = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
            'province' => $request->input('province'),
            'district' => $request->input('district'),
            'ward' => $request->input('ward'),
            'payment_method' => $request->input('payment_method', 'cod'),
            'note' => $request->input('note'),
        ];

        $cartTotal = collect($cartItems)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        $orderSummary = [
            'name' => $checkoutData['name'],
            'email' => $checkoutData['email'],
            'phone' => $checkoutData['phone'],
            'address' => $checkoutData['address'],
            'province' => $checkoutData['province'],
            'district' => $checkoutData['district'],
            'ward' => $checkoutData['ward'],
            'note' => $checkoutData['note'],
            'payment_method' => $checkoutData['payment_method'],
            'cartItems' => $cartItems,
            'cartTotal' => $cartTotal,
        ];

        if ($checkoutData['payment_method'] === 'online') {
            session()->put('checkout_data', array_merge($checkoutData, [
                'cartItems' => $cartItems,
                'cartTotal' => $cartTotal,
            ]));

            return redirect()->route('checkout.online');
        }

        session()->forget('cart');

        return redirect()->route('checkout.success')->with([
            'success' => 'Đặt hàng thành công! Chúng tôi đã nhận thông tin của bạn và sẽ liên hệ sớm.',
            'payment_method' => 'cod',
            'order_summary' => $orderSummary,
        ]);
    }

    public function online()
    {
        $checkoutData = session('checkout_data');

        if (empty($checkoutData) || ($checkoutData['payment_method'] ?? '') !== 'online') {
            return redirect()->route('checkout.index')->with('error', 'Vui lòng hoàn tất thông tin thanh toán trước.');
        }

        return view('frontend.checkout.online', compact('checkoutData'));
    }

    public function processOnline(Request $request)
    {
        $request->validate([
            'card_holder' => 'required|string|max:255',
            'card_number' => 'required|digits_between:12,19',
            'expiry' => 'required|size:5',
            'cvc' => 'required|digits_between:3,4',
        ]);

        $checkoutData = session('checkout_data');
        if (empty($checkoutData) || ($checkoutData['payment_method'] ?? '') !== 'online') {
            return redirect()->route('checkout.index')->with('error', 'Yêu cầu thanh toán không hợp lệ.');
        }

        $smsCode = rand(100000, 999999);

        session()->put('payment_pending', [
            'checkout_data' => $checkoutData,
            'sms_code' => $smsCode,
            'attempts' => 0,
        ]);

        return redirect()->route('checkout.sms.form')->with('success', 'Mã xác nhận đã được gửi tới số điện thoại của bạn.');
    }

    public function smsSendForm()
    {
        if (empty(session('checkout_data'))) {
            return redirect()->route('checkout.index')->with('error', 'Vui lòng bắt đầu lại quá trình thanh toán.');
        }

        return view('frontend.checkout.sms-verification');
    }

    public function verifySms(Request $request)
    {
        $request->validate([
            'sms_code' => 'required|digits:6',
        ], [
            'sms_code.required' => 'Vui lòng nhập mã xác nhận.',
            'sms_code.digits' => 'Mã xác nhận phải là 6 chữ số.',
        ]);

        $paymentPending = session('payment_pending');
        if (empty($paymentPending)) {
            return redirect()->route('checkout.index')->with('error', 'Phiên thanh toán đã hết hạn. Vui lòng thử lại.');
        }

        if ($paymentPending['attempts'] >= 5) {
            session()->forget('payment_pending');
            return redirect()->route('checkout.index')->with('error', 'Bạn đã nhập sai mã xác nhận quá nhiều lần. Vui lòng bắt đầu lại.');
        }

        if ((string)$request->input('sms_code') !== (string)$paymentPending['sms_code']) {
            $paymentPending['attempts']++;
            session()->put('payment_pending', $paymentPending);

            return back()->with('error', 'Mã xác nhận không đúng. Vui lòng thử lại. (' . (5 - $paymentPending['attempts']) . ' lần còn lại)');
        }

        $checkoutData = $paymentPending['checkout_data'];

        $orderSummary = [
            'name' => $checkoutData['name'],
            'email' => $checkoutData['email'],
            'phone' => $checkoutData['phone'],
            'address' => $checkoutData['address'],
            'province' => $checkoutData['province'],
            'district' => $checkoutData['district'],
            'ward' => $checkoutData['ward'],
            'note' => $checkoutData['note'],
            'payment_method' => 'online',
            'cartItems' => $checkoutData['cartItems'],
            'cartTotal' => $checkoutData['cartTotal'],
        ];

        session()->forget(['cart', 'checkout_data', 'payment_pending']);

        return redirect()->route('checkout.success')->with([
            'success' => 'Thanh toán online thành công! Đơn hàng của bạn đã được ghi nhận.',
            'payment_method' => 'online',
            'order_summary' => $orderSummary,
        ]);
    }

    public function resendSms()
    {
        $paymentPending = session('payment_pending');
        if (empty($paymentPending)) {
            return redirect()->route('checkout.index')->with('error', 'Phiên thanh toán đã hết hạn. Vui lòng thử lại.');
        }

        $smsCode = rand(100000, 999999);
        $paymentPending['sms_code'] = $smsCode;
        $paymentPending['attempts'] = 0;

        session()->put('payment_pending', $paymentPending);

        return back()->with('success', 'Mã xác nhận mới đã được gửi tới số điện thoại của bạn.');
    }

    public function success()
    {
        return view('frontend.checkout.success');
    }
}

