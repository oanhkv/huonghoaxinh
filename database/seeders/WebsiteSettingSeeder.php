<?php

namespace Database\Seeders;

use App\Models\WebsiteSetting;
use Illuminate\Database\Seeder;

class WebsiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'site_name' => 'Hương Hoa Xinh',
            'site_tagline' => 'Hoa tươi chất lượng cao - giao nhanh trong ngày',
            'support_email' => 'huonghoaxinh@gmail.com',
            'hotline' => '0925 939 255',
            'address' => 'Di Trạch, Huyện Hoài Đức, Hà Nội',
            'copyright_text' => 'Copyright © '.now()->year.' Hương Hoa Xinh. All rights reserved.',
            'hero_title' => 'HOA TƯƠI - ĐẸP - SANG TRỌNG',
            'hero_subtitle' => 'Giao hoa tận nơi • Tươi lâu • Thiết kế theo yêu cầu',
            'hero_button_text' => 'MUA HOA NGAY',
            'free_shipping_note' => 'Miễn phí giao hoa nội thành Hà Nội cho đơn từ 500K',
            'meta_title' => 'Hương Hoa Xinh - Shop Hoa Tươi Online',
            'meta_description' => 'Shop hoa tươi chất lượng cao, thiết kế sáng tạo, giao nhanh tại Hà Nội và TP.HCM.',
            'meta_keywords' => 'hoa tuoi, shop hoa, dat hoa online, huong hoa xinh, hoa sinh nhat, hoa khai truong',
            'facebook_url' => 'https://facebook.com/huonghoaxinh',
            'instagram_url' => 'https://instagram.com/huonghoaxinh',
            'youtube_url' => '',
            'zalo_url' => 'https://zalo.me/0925939255',
            'featured_products_limit' => '8',
            'enable_catalog_mode' => '0',
            'enable_reviews' => '1',
            'logo' => '',
            'hero_image' => '',
            'contact_inbox_email' => 'huonghoaxinh@gmail.com',
        ];

        WebsiteSetting::setMany($settings);
    }
}
