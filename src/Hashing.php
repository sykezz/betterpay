<?php

namespace Sykez\Betterpay;

class Hashing
{
    // https://www.betterpay.me/docs/?php#mpgs_tokenization5
    public static function reference(string $api_key, string $merchant_id, string $reference_id)
    {
        return md5($api_key . '|' . urldecode($merchant_id) . '|' . urldecode($reference_id));
    }

    // https://www.betterpay.me/docs/?php#mpgs_tokenization6
    public static function tokenizationCallback(string $api_key, string $reference, string $status, string $status_message, string $token)
    {
        return md5($api_key . $reference . $status . $status_message . $token);
    }

    // https://www.betterpay.me/docs/?php#mpgs_tokenization9
    public static function tokenizationCharge(string $api_key, string $merchant_id, string $token, string $invoice, int $amount)
    {
        return md5($api_key . '|' . urldecode($merchant_id) . '|' . urldecode($token) . '|' . urldecode($invoice) . '|' . urldecode($amount));
    }
}
