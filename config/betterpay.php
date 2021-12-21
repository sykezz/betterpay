<?php

return [
    'api_key' => env('BETTERPAY_API_KEY'),
    'merchant_id' => env('BETTERPAY_MERCHANT_ID'),
    'api_url' => env('BETTERPAY_API_URL', 'https://www.demo.betterpay.me/merchant/api/'),
    'callback_url' => env('BETTERPAY_CALLBACK_URL'),
    'success_url' => env('BETTERPAY_SUCCESS_URL'),
    'fail_url' => env('BETTERPAY_FAIL_URL'),
];
