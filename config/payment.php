<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Nhà cung cấp ảnh QR thanh toán
    |--------------------------------------------------------------------------
    | vietqr: https://img.vietqr.io (chuẩn VietQR / Napas, quét bằng app ngân hàng)
    | sepay: https://qr.sepay.vn/img (SePay — cần đúng mã ngân hàng SePay)
    */
    'provider' => env('PAYMENT_QR_PROVIDER', 'vietqr'),

    'vietqr' => [
        /* VPBank (NAPAS) — tra cứu: https://vietqr.io */
        'bank_id' => env('VIETQR_BANK_ID', '970432'),
        'account_no' => env('VIETQR_ACCOUNT_NO', '0925939255'),
        'account_name' => env('VIETQR_ACCOUNT_NAME', 'HUONG HOA XINH'),
        'template' => env('VIETQR_TEMPLATE', 'compact2'),
    ],

    'sepay' => [
        'bank' => env('SEPAY_BANK', ''),
        'account' => env('SEPAY_ACCOUNT', ''),
    ],

    'momo' => [
        'phone' => env('MOMO_PHONE', '0925939255'),
        'display_name' => env('MOMO_DISPLAY_NAME', 'Vi MoMo / Chuyen khoan'),
    ],

];
