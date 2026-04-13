<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('admin.profile.edit', [
            'user' => $request->user(),
            'hasPhone' => Schema::hasColumn('users', 'phone'),
            'hasAddress' => Schema::hasColumn('users', 'address'),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($request->user()->id),
            ],
        ];

        if (Schema::hasColumn('users', 'phone')) {
            $rules['phone'] = ['nullable', 'string', 'max:20'];
        }

        if (Schema::hasColumn('users', 'address')) {
            $rules['address'] = ['nullable', 'string', 'max:255'];
        }

        $validated = $request->validate($rules);

        $user = $request->user();
        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (array_key_exists('phone', $validated)) {
            $user->phone = $validated['phone'];
        }

        if (array_key_exists('address', $validated)) {
            $user->address = $validated['address'];
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return redirect()
            ->route('admin.profile.edit')
            ->with('success', 'Cập nhật thông tin cá nhân thành công.');
    }
}
