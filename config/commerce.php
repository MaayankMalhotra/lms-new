<?php

return [
    'currency' => env('SHOP_CURRENCY', 'INR'),
    'tax_rate' => (float) env('SHOP_TAX_RATE', 0.18),
    'shipping_flat_rate' => (float) env('SHOP_SHIPPING_FLAT', 0),
    'meta_pixel_id' => env('META_PIXEL_ID'),
];
