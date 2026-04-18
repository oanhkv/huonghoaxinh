<?php

namespace App\Providers;

use App\Models\WebsiteSetting;
use App\Models\Category;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Throwable;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            if (! Schema::hasTable('website_settings')) {
                View::share('siteSettings', []);
                return;
            }

            $settings = WebsiteSetting::allAsArray();
            View::share('siteSettings', $settings);

            if (! empty($settings['site_name'])) {
                config(['app.name' => $settings['site_name']]);
            }
        } catch (Throwable $e) {
            View::share('siteSettings', []);
        }

        // Share mainCategories cho layout
        try {
            if (Schema::hasTable('categories')) {
                $mainCategories = Category::whereNull('parent_id')
                                         ->with('children')
                                         ->get();
                View::share('mainCategories', $mainCategories);
            }
        } catch (Throwable $e) {
            View::share('mainCategories', []);
        }
    }
}

