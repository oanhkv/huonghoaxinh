<?php

return [

    /*
    | Địa chỉ cửa hàng hiển thị trên web & dùng làm điểm gốc tính khoảng cách giao hàng.
    | Tọa độ mặc định: khu vực Di Trạch, Hoài Đức, Hà Nội (có thể tinh chỉnh bằng SHOP_LAT / SHOP_LNG).
    */
    'address_line' => env('SHOP_ADDRESS', 'Số 5, ngõ 206, đường Di Trạch, Hoài Đức, Hà Nội'),

    'geocode_query' => env('SHOP_GEOCODE_QUERY', 'Số 5 ngõ 206 đường Di Trạch Hoài Đức Hà Nội Việt Nam'),

    'lat' => env('SHOP_LAT', 21.0273),

    'lng' => env('SHOP_LNG', 105.7158),

    /*
    | Khi không geocode được địa chỉ khách (mạng/OSM lỗi), dùng số km này để tính phí ship an toàn.
    */
    'shipping_fallback_km' => (float) env('SHOP_SHIPPING_FALLBACK_KM', 12),

    /*
    | Email nhận liên hệ/hỏi đáp từ form website.
    */
    'contact_inbox_email' => env('SHOP_CONTACT_INBOX_EMAIL', 'oanhvu1503@gmail.com'),

];
