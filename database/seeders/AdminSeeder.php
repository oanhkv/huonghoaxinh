<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin1@huonghoaxinh.com'],
            [
                'name' => 'Admin1',
                'password' => Hash::make('12345678'),
            ]
        );

        $role = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        if (! $admin->hasRole($role->name)) {
            $admin->assignRole($role);
        }

        echo "Admin account created successfully!\n";
    }
}