<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\ShippingDistanceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShippingEstimateController extends Controller
{
    public function estimate(Request $request, ShippingDistanceService $shipping): JsonResponse
    {
        $request->validate([
            'shipping_address' => 'required|string|max:500',
        ]);

        $result = $shipping->distanceFromShopKm($request->shipping_address);
        $km = $result['distance_km'];
        $ship = $km <= 10 ? 0 : 30000;

        return response()->json([
            'success' => true,
            'distance_km' => $km,
            'shipping' => $ship,
            'geocoded' => $result['geocoded'],
            'shop_address' => config('shop.address_line'),
        ]);
    }
}
