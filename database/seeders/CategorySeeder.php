<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Hoa Sinh Nhật', 'slug' => 'hoa-sinh-nhat', 'description' => 'Hoa chúc mừng sinh nhật'],
            ['name' => 'Hoa Tình Yêu', 'slug' => 'hoa-tinh-yeu', 'description' => 'Hoa hồng, hoa tình nhân'],
            ['name' => 'Hoa Chúc Mừng', 'slug' => 'hoa-chuc-mung', 'description' => 'Hoa khai trương, chúc mừng'],
            ['name' => 'Hoa Tang Lễ', 'slug' => 'hoa-tang-le', 'description' => 'Hoa viếng, tang lễ'],
            ['name' => 'Hoa Khai Trương', 'slug' => 'hoa-khai-truong', 'description' => 'Hoa chúc mừng khai trương'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }
    }
}