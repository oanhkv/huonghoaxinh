<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function create()
    {
        return view('admin.users.form', [
            'user' => new User,
            'mode' => 'create',
            'isAdminForm' => true,
            'hasPhone' => Schema::hasColumn('users', 'phone'),
            'hasAddress' => Schema::hasColumn('users', 'address'),
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];

        if (Schema::hasColumn('users', 'phone')) {
            $rules['phone'] = ['nullable', 'string', 'max:20'];
        }

        if (Schema::hasColumn('users', 'address')) {
            $rules['address'] = ['nullable', 'string', 'max:255'];
        }

        $validated = $request->validate($rules);

        $payload = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ];

        if (array_key_exists('phone', $validated)) {
            $payload['phone'] = $validated['phone'];
        }

        if (array_key_exists('address', $validated)) {
            $payload['address'] = $validated['address'];
        }

        if (Schema::hasColumn('users', 'role')) {
            $payload['role'] = 'admin';
        }

        $user = User::create($payload);

        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        if (! $user->hasRole($adminRole->name)) {
            $user->assignRole($adminRole);
        }

        return redirect()->route('admin.users.admins')
            ->with('success', 'Thêm tài khoản admin thành công!');
    }

    public function index(Request $request)
    {
        $users = $this->buildUsersQuery($request, false)->latest()->paginate(10);

        return view('admin.users.index', [
            'users' => $users,
            'accountType' => 'customers',
        ]);
    }

    public function admins(Request $request)
    {
        $users = $this->buildUsersQuery($request, true)->latest()->paginate(10);

        return view('admin.users.index', [
            'users' => $users,
            'accountType' => 'admins',
        ]);
    }

    private function buildUsersQuery(Request $request, bool $admins): Builder
    {
        $query = User::query();

        if ($admins) {
            $query->where(function ($q) {
                $q->whereHas('roles', function ($roleQuery) {
                    $roleQuery->where('name', 'admin');
                });

                if (Schema::hasColumn('users', 'role')) {
                    $q->orWhere('role', 'admin');
                }
            });
        } else {
            $query->where(function ($q) {
                $q->whereDoesntHave('roles', function ($roleQuery) {
                    $roleQuery->where('name', 'admin');
                });

                if (Schema::hasColumn('users', 'role')) {
                    $q->where(function ($inner) {
                        $inner->whereNull('role')->orWhere('role', '!=', 'admin');
                    });
                }
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");

                if (Schema::hasColumn('users', 'phone')) {
                    $q->orWhere('phone', 'like', "%{$search}%");
                }
            });
        }

        return $query;
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.users.form', [
            'user' => $user,
            'mode' => 'edit',
            'isAdminForm' => $this->isAdmin($user),
            'hasPhone' => Schema::hasColumn('users', 'phone'),
            'hasAddress' => Schema::hasColumn('users', 'address'),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ];

        if (Schema::hasColumn('users', 'phone')) {
            $rules['phone'] = ['nullable', 'string', 'max:20'];
        }

        if (Schema::hasColumn('users', 'address')) {
            $rules['address'] = ['nullable', 'string', 'max:255'];
        }

        $validated = $request->validate($rules);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (array_key_exists('phone', $validated)) {
            $user->phone = $validated['phone'];
        }

        if (array_key_exists('address', $validated)) {
            $user->address = $validated['address'];
        }

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        if ($this->isAdmin($user) && Schema::hasColumn('users', 'role')) {
            $user->role = 'admin';
        }

        $user->save();

        if ($this->isAdmin($user) && ! $user->hasRole('admin')) {
            $adminRole = Role::firstOrCreate([
                'name' => 'admin',
                'guard_name' => 'web',
            ]);
            $user->assignRole($adminRole);
        }

        $redirectRoute = $this->isAdmin($user) ? 'admin.users.admins' : 'admin.users.index';

        return redirect()->route($redirectRoute)
            ->with('success', 'Cập nhật tài khoản thành công!');
    }

    public function destroy(User $user)
    {
        if ($user->hasRole('admin') || (Schema::hasColumn('users', 'role') && $user->role === 'admin')) {
            return redirect()->back()->with('error', 'Không thể xóa tài khoản Admin!');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Xóa khách hàng thành công!');
    }

    private function isAdmin(User $user): bool
    {
        return $user->hasRole('admin') || (Schema::hasColumn('users', 'role') && $user->role === 'admin');
    }
}
