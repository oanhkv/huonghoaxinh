<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class WebsiteSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
    ];

    public static function allAsArray(): array
    {
        return Cache::rememberForever('website_settings.all', function () {
            return self::query()->pluck('value', 'key')->toArray();
        });
    }

    public static function getValue(string $key, mixed $default = null): mixed
    {
        return self::allAsArray()[$key] ?? $default;
    }

    public static function setMany(array $settings): void
    {
        $payload = [];

        foreach ($settings as $key => $value) {
            $payload[] = [
                'key' => $key,
                'value' => is_array($value) ? json_encode($value) : (string) $value,
                'updated_at' => now(),
                'created_at' => now(),
            ];
        }

        if (! empty($payload)) {
            self::query()->upsert($payload, ['key'], ['value', 'updated_at']);
            Cache::forget('website_settings.all');
        }
    }
}
