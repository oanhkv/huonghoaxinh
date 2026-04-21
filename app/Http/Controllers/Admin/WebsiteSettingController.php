<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class WebsiteSettingController extends Controller
{
    public function edit()
    {
        $defaults = [
            'site_name' => 'Hương Hoa Xinh',
            'site_tagline' => 'Hoa tươi chất lượng cao - giao nhanh trong ngày',
            'support_email' => 'support@huonghoaxinh.vn',
            'hotline' => '0859 773 086',
            'address' => 'Quận Gò Vấp, TP.HCM',
            'copyright_text' => 'Copyright © '.now()->year.' Hương Hoa Xinh. All rights reserved.',
            'hero_title' => 'HOA TƯƠI - ĐẸP - SANG TRỌNG',
            'hero_subtitle' => 'Giao hoa tận nơi • Tươi lâu • Thiết kế theo yêu cầu',
            'hero_button_text' => 'MUA HOA NGAY',
            'free_shipping_note' => 'Giao hoa nhanh nội thành TP.HCM trong 2 giờ',
            'meta_title' => 'Hương Hoa Xinh - Shop Hoa Tươi',
            'meta_description' => 'Shop hoa tươi chất lượng cao, thiết kế sáng tạo, giao nhanh tại TP.HCM.',
            'meta_keywords' => 'hoa tuoi, shop hoa, dat hoa online, huong hoa xinh',
            'facebook_url' => '',
            'instagram_url' => '',
            'youtube_url' => '',
            'zalo_url' => '',
            'featured_products_limit' => '8',
            'enable_catalog_mode' => '0',
            'enable_reviews' => '1',
            'logo' => '',
            'hero_image' => '',
        ];

        $settings = array_merge($defaults, WebsiteSetting::allAsArray());

        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => ['required', 'string', 'max:100'],
            'site_tagline' => ['nullable', 'string', 'max:255'],
            'support_email' => ['nullable', 'email', 'max:100'],
            'hotline' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:255'],
            'copyright_text' => ['nullable', 'string', 'max:255'],
            'hero_title' => ['nullable', 'string', 'max:160'],
            'hero_subtitle' => ['nullable', 'string', 'max:255'],
            'hero_button_text' => ['nullable', 'string', 'max:60'],
            'free_shipping_note' => ['nullable', 'string', 'max:160'],
            'meta_title' => ['nullable', 'string', 'max:160'],
            'meta_description' => ['nullable', 'string', 'max:255'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'facebook_url' => ['nullable', 'url', 'max:255'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'youtube_url' => ['nullable', 'url', 'max:255'],
            'zalo_url' => ['nullable', 'url', 'max:255'],
            'featured_products_limit' => ['nullable', 'integer', 'min:4', 'max:24'],
            'enable_catalog_mode' => ['nullable', 'boolean'],
            'enable_reviews' => ['nullable', 'boolean'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'hero_image' => ['nullable', 'image', 'max:4096'],
        ]);

        $current = WebsiteSetting::allAsArray();

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('settings', 'public');
        } else {
            $validated['logo'] = $current['logo'] ?? '';
        }

        if ($request->hasFile('hero_image')) {
            $validated['hero_image'] = $request->file('hero_image')->store('settings', 'public');
        } else {
            $validated['hero_image'] = $current['hero_image'] ?? '';
        }

        $validated['enable_catalog_mode'] = $request->boolean('enable_catalog_mode') ? '1' : '0';
        $validated['enable_reviews'] = $request->boolean('enable_reviews') ? '1' : '0';

        WebsiteSetting::setMany($validated);
        Cache::forget('website_settings.all');

        return redirect()
            ->route('admin.settings.edit')
            ->with('success', 'Đã cập nhật cài đặt website thành công.');
    }
}
