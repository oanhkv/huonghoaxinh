<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        /** @var User|null $user */
        $user = Auth::user();

        if ($user?->is_locked) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'email' => 'Tài khoản này đã bị khóa. Vui lòng liên hệ hỗ trợ nếu cần mở khóa.',
            ])->onlyInput('email');
        }

        $this->mergeGuestCartToUserCart();

        if ($user?->hasRole('admin')) {
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->intended(route('home'));
    }

    private function mergeGuestCartToUserCart(): void
    {
        if (! Auth::check()) {
            return;
        }

        $guestCart = Session::get('guest_cart', []);
        if (empty($guestCart)) {
            return;
        }

        foreach ($guestCart as $item) {
            $productId = (int) ($item['product_id'] ?? 0);
            $quantity = (int) ($item['quantity'] ?? 0);
            $price = (float) ($item['price'] ?? 0);
            $variant = (string) ($item['variant'] ?? '');

            if ($productId <= 0 || $quantity <= 0) {
                continue;
            }

            $product = Product::find($productId);
            if (! $product) {
                continue;
            }

            $existingQuantity = Cart::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->sum('quantity');

            if ($existingQuantity >= $product->stock) {
                continue;
            }

            $allowedQuantity = min($quantity, max(0, $product->stock - $existingQuantity));
            if ($allowedQuantity <= 0) {
                continue;
            }

            $cartItem = Cart::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->where('price', $price)
                ->where('variant', $variant)
                ->first();

            if ($cartItem) {
                $cartItem->update([
                    'quantity' => min($product->stock, $cartItem->quantity + $allowedQuantity),
                ]);
            } else {
                Cart::create([
                    'user_id' => Auth::id(),
                    'product_id' => $productId,
                    'quantity' => $allowedQuantity,
                    'price' => $price > 0 ? $price : $product->price,
                    'variant' => $variant,
                ]);
            }
        }

        Session::forget('guest_cart');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
