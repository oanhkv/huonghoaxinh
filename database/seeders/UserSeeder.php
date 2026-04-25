<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Nguyễn Thị Mai',
                'email' => 'mai.nguyen@example.com',
                'phone' => '0901234567',
                'address' => '12 Lê Lợi, Quận 1, TP.HCM',
            ],
            [
                'name' => 'Trần Minh Khôi',
                'email' => 'khoi.tran@example.com',
                'phone' => '0902345678',
                'address' => '45 Nguyễn Trãi, Quận 5, TP.HCM',
            ],
            [
                'name' => 'Lê Hoàng Phương',
                'email' => 'phuong.le@example.com',
                'phone' => '0903456789',
                'address' => '78 Cầu Giấy, Quận Cầu Giấy, Hà Nội',
            ],
            [
                'name' => 'Phạm Quỳnh Anh',
                'email' => 'anh.pham@example.com',
                'phone' => '0904567890',
                'address' => '102 Nguyễn Huệ, Quận 1, TP.HCM',
            ],
            [
                'name' => 'Đỗ Hữu Tài',
                'email' => 'tai.do@example.com',
                'phone' => '0905678901',
                'address' => '34 Hai Bà Trưng, Quận Hoàn Kiếm, Hà Nội',
            ],
            [
                'name' => 'Vũ Thanh Hà',
                'email' => 'ha.vu@example.com',
                'phone' => '0906789012',
                'address' => '56 Trần Phú, Hải Châu, Đà Nẵng',
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                array_merge($user, [
                    'password' => Hash::make('12345678'),
                    'email_verified_at' => now(),
                ])
            );
        }
    }
}
