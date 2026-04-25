<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Tài khoản admin dùng guard 'admin' (bảng admins) — login tại /admin/login
        $admins = [
            [
                'name' => 'Quản Trị Chính',
                'email' => 'admin@huonghoaxinh.com',
                'phone' => '0925939255',
                'is_active' => true,
            ],
            [
                'name' => 'Phụ Trách Đơn Hàng',
                'email' => 'order.admin@huonghoaxinh.com',
                'phone' => '0925939256',
                'is_active' => true,
            ],
            [
                'name' => 'Phụ Trách Marketing',
                'email' => 'marketing@huonghoaxinh.com',
                'phone' => '0925939257',
                'is_active' => true,
            ],
            [
                'name' => 'Phụ Trách Kho',
                'email' => 'warehouse@huonghoaxinh.com',
                'phone' => '0925939258',
                'is_active' => true,
            ],
            [
                'name' => 'Hỗ Trợ Khách Hàng',
                'email' => 'support@huonghoaxinh.com',
                'phone' => '0925939259',
                'is_active' => true,
            ],
        ];

        foreach ($admins as $admin) {
            Admin::updateOrCreate(
                ['email' => $admin['email']],
                array_merge($admin, ['password' => Hash::make('12345678')])
            );
        }

        // Tài khoản User legacy có role 'admin' (spatie) – để các tính năng frontend cần "User là admin" vẫn chạy.
        $userAdmin = User::updateOrCreate(
            ['email' => 'admin1@huonghoaxinh.com'],
            [
                'name' => 'Admin1',
                'password' => Hash::make('12345678'),
                'phone' => '0925939255',
                'address' => 'Di Trạch, Hoài Đức, Hà Nội',
            ]
        );

        $role = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        if (! $userAdmin->hasRole($role->name)) {
            $userAdmin->assignRole($role);
        }
    }
}
