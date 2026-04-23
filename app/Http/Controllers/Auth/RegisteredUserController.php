<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class RegisteredUserController extends Controller
{
    public function createUser(): View
    {
        return view('auth.register', ['registerType' => 'user']);
    }

    public function createAdmin(): View
    {
        $hasAnyAdmin = Admin::query()->exists();
        if ($hasAnyAdmin && ! Auth::guard('admin')->check()) {
            abort(403);
        }

        return view('auth.register', [
            'registerType' => 'admin',
            'allowBootstrapAdmin' => ! $hasAnyAdmin,
        ]);
    }

    public function storeUser(Request $request): RedirectResponse
    {
        return $this->registerByType($request, 'user');
    }

    public function storeAdmin(Request $request): RedirectResponse
    {
        return $this->registerByType($request, 'admin');
    }

    private function registerByType(Request $request, string $type): RedirectResponse
    {
        $isBootstrapAdmin = false;
        if ($type === 'admin') {
            $hasAnyAdmin = Admin::query()->exists();
            $isBootstrapAdmin = ! $hasAnyAdmin;

            if ($hasAnyAdmin && ! Auth::guard('admin')->check()) {
                abort(403);
            }
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:'.($type === 'admin' ? Admin::class : User::class),
            ],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if ($type === 'admin' && $isBootstrapAdmin) {
            $request->validate([
                'bootstrap_code' => ['required', 'string'],
            ]);

            $expectedCode = trim((string) env('ADMIN_BOOTSTRAP_CODE', ''));
            if ($expectedCode === '' || ! hash_equals($expectedCode, (string) $request->bootstrap_code)) {
                throw ValidationException::withMessages([
                    'bootstrap_code' => 'Mã khởi tạo admin không chính xác.',
                ]);
            }
        }

        if ($type === 'admin') {
            $admin = Admin::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'is_active' => true,
            ]);

            if ($isBootstrapAdmin) {
                Auth::guard('admin')->login($admin);

                return redirect(route('admin.dashboard'))
                    ->with('success', 'Khởi tạo tài khoản admin đầu tiên thành công.');
            }

            return redirect(route('admin.users.index'))
                ->with('success', 'Đã tạo tài khoản quản trị viên mới thành công.');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => Hash::make($request->password),
        ]);

        $role = Role::firstOrCreate(['name' => 'user']);
        $user->assignRole($role);

        event(new Registered($user));
        Auth::login($user);

        return redirect(route('home'))
            ->with('success', 'Đăng ký tài khoản khách hàng thành công! Chào mừng bạn đến với Hương Hoa Xinh.');
    }
}
