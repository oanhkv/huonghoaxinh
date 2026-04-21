<?php

namespace App\Services;

use App\Models\Order;

class PaymentQrService
{
    /**
     * Tạo URL ảnh QR thanh toán hợp lệ (VietQR hoặc SePay).
     * Nội dung chuyển khoản được rút gọn để tương thích app ngân hàng.
     */
    public static function forOrder(Order $order): array
    {
        $amount = max(0, (int) round($order->total_amount));
        $addInfo = self::sanitizeAddInfo((string) $order->order_code);
        $accountName = self::asciiName((string) config('payment.vietqr.account_name', ''));

        $provider = strtolower((string) config('payment.provider', 'vietqr'));

        if ($provider === 'sepay' && config('payment.sepay.bank') && config('payment.sepay.account')) {
            $url = sprintf(
                'https://qr.sepay.vn/img?bank=%s&acc=%s&amount=%d&des=%s',
                rawurlencode((string) config('payment.sepay.bank')),
                rawurlencode(preg_replace('/\s+/', '', (string) config('payment.sepay.account'))),
                $amount,
                rawurlencode($addInfo)
            );

            return [
                'image_url' => $url,
                'provider' => 'sepay',
                'add_info' => $addInfo,
                'amount' => $amount,
            ];
        }

        $bankId = preg_replace('/\s+/', '', (string) config('payment.vietqr.bank_id'));
        $accountNo = preg_replace('/\s+/', '', (string) config('payment.vietqr.account_no'));
        $template = preg_replace('/[^a-z0-9_-]/i', '', (string) config('payment.vietqr.template', 'compact2')) ?: 'compact2';

        $base = sprintf('https://img.vietqr.io/image/%s-%s-%s.jpg', $bankId, $accountNo, $template);
        $query = http_build_query(array_filter([
            'amount' => $amount > 0 ? $amount : null,
            'addInfo' => $addInfo,
            'accountName' => $accountName !== '' ? $accountName : null,
        ]), '', '&', PHP_QUERY_RFC3986);

        return [
            'image_url' => $base . ($query !== '' ? '?' . $query : ''),
            'provider' => 'vietqr',
            'add_info' => $addInfo,
            'amount' => $amount,
        ];
    }

    public static function sanitizeAddInfo(string $text): string
    {
        $t = preg_replace('/[^A-Za-z0-9]/', '', $text);

        return $t !== '' ? substr($t, 0, 25) : 'DONHANG';
    }

    public static function asciiName(string $name): string
    {
        $name = trim($name);
        if ($name === '') {
            return '';
        }

        $converted = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $name);
        if ($converted === false) {
            $converted = $name;
        }

        $converted = strtoupper(preg_replace('/[^A-Z0-9\s]/', '', $converted));

        return trim(preg_replace('/\s+/', ' ', $converted));
    }
}
