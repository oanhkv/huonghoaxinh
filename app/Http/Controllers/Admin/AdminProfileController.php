<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminProfileController extends Controller
{
    public function edit(Request $request): View
    {
        /** @var Admin $admin */
        $admin = Auth::guard('admin')->user();

        return view('admin.profile.edit', [
            'user' => $admin,
            'hasPhone' => Schema::hasColumn('admins', 'phone'),
            'hasAddress' => false,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        /** @var Admin $admin */
        $admin = Auth::guard('admin')->user();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('admins', 'email')->ignore($admin->id),
            ],
        ];

        if (Schema::hasColumn('admins', 'phone')) {
            $rules['phone'] = ['nullable', 'string', 'max:20'];
        }

        $validated = $request->validate($rules);

        $admin->name = $validated['name'];
        $admin->email = $validated['email'];

        if (array_key_exists('phone', $validated)) {
            $admin->phone = $validated['phone'];
        }

        $admin->save();

        return redirect()
            ->route('admin.profile.edit')
            ->with('success', 'Cập nhật thông tin cá nhân thành công.');
    }
}
