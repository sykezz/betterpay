<?php

return [
    'api_key' => env('BETTERPAY_API_KEY'),
    'merchant_id' => env('BETTERPAY_MERCHANT_ID'),
    'callback_url' => env('BETTERPAY_CALLBACK_URL'),
    'success_url' => env('BETTERPAY_SUCCESS_URL'),
    'fail_url' => env('BETTERPAY_FAIL_URL'),
    'sandbox' => env('BETTERPAY_SANDBOX', false),
    '3ds' => env('BETTERPAY_3DS', true),
];
