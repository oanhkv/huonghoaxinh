<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ShippingDistanceService
{
    /**
     * @return array{lat: float, lng: float}
     */
    public function shopCoordinates(): array
    {
        $lat = config('shop.lat');
        $lng = config('shop.lng');

        if ($lat !== null && $lat !== '' && $lng !== null && $lng !== '') {
            return ['lat' => (float) $lat, 'lng' => (float) $lng];
        }

        return Cache::remember('shop_geocode_from_query', 86400 * 30, function () {
            $coords = $this->geocode((string) config('shop.geocode_query'));
            if ($coords) {
                return $coords;
            }

            return ['lat' => 21.0285, 'lng' => 105.8542];
        });
    }

    /**
     * @return array{lat: float, lng: float}|null
     */
    public function geocodeAddress(string $address): ?array
    {
        $address = trim($address);
        if ($address === '') {
            return null;
        }

        $lower = Str::lower($address);
        $query = $address;
        if (! str_contains($lower, 'việt nam') && ! str_contains($lower, 'vietnam')) {
            $query .= ', Việt Nam';
        }

        return $this->geocode($query);
    }

    /**
     * @return array{distance_km: float, geocoded: bool}
     */
    public function distanceFromShopKm(string $customerAddress): array
    {
        if ($this->isSameAsShopAddress($customerAddress)) {
            return [
                'distance_km' => 0.0,
                'geocoded' => true,
            ];
        }

        $shop = $this->shopCoordinates();
        $dest = $this->geocodeAddress($customerAddress);

        if (! $dest) {
            return [
                'distance_km' => round((float) config('shop.shipping_fallback_km', 12), 2),
                'geocoded' => false,
            ];
        }

        $distance = $this->haversineKm($shop['lat'], $shop['lng'], $dest['lat'], $dest['lng']);

        return [
            'distance_km' => round($distance < 0.1 ? 0.0 : $distance, 2),
            'geocoded' => true,
        ];
    }

    public function haversineKm(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earth = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) ** 2;

        return $earth * (2 * atan2(sqrt($a), sqrt(1 - $a)));
    }

    /**
     * @return array{lat: float, lng: float}|null
     */
    private function geocode(string $query): ?array
    {
        try {
            $response = Http::timeout(12)
                ->withHeaders([
                    'User-Agent' => config('app.name', 'HuongHoaXinh').'/1.0 (contact@local)',
                ])
                ->get('https://nominatim.openstreetmap.org/search', [
                    'q' => $query,
                    'format' => 'json',
                    'limit' => 1,
                ]);

            if (! $response->successful()) {
                return null;
            }

            $rows = $response->json();
            if (! is_array($rows) || ! isset($rows[0]['lat'], $rows[0]['lon'])) {
                return null;
            }

            return [
                'lat' => (float) $rows[0]['lat'],
                'lng' => (float) $rows[0]['lon'],
            ];
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function isSameAsShopAddress(string $address): bool
    {
        $input = $this->normalizeAddress($address);
        if ($input === '') {
            return false;
        }

        $refs = [
            (string) config('shop.address_line', ''),
            (string) config('shop.geocode_query', ''),
        ];

        foreach ($refs as $ref) {
            $normalizedRef = $this->normalizeAddress($ref);
            if ($normalizedRef === '') {
                continue;
            }

            if ($input === $normalizedRef || str_contains($input, $normalizedRef) || str_contains($normalizedRef, $input)) {
                return true;
            }
        }

        return false;
    }

    private function normalizeAddress(string $address): string
    {
        $normalized = Str::lower(Str::ascii($address));
        $normalized = preg_replace('/\b(viet nam|vietnam)\b/', ' ', $normalized) ?? $normalized;
        $normalized = preg_replace('/[^a-z0-9]+/', ' ', $normalized) ?? $normalized;

        return trim((string) preg_replace('/\s+/', ' ', $normalized));
    }
}
