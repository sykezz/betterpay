<?php

namespace Sykez\Betterpay;

trait HashVerification
{  
    /**
     * tokenCardVerificationCallback
     * - https://www.betterpay.me/docs/?php#mpgs_tokenization6  
     *
     * @param  mixed $reference
     * @param  mixed $status_code
     * @param  mixed $status_message
     * @param  mixed $token
     * @param  mixed $hash
     * @return bool
     */
    public function tokenCardVerificationCallback(string $reference, string $status_code, string $status_message, string $token, string $hash): bool
    {
        $_hash = md5($this->api_key . $reference . $status_code . $status_message . $token);
        return strcmp($_hash, $hash) !== 0 ? false : true;
    }

    // https://www.betterpay.me/docs/?php#mpgs_tokenization5
    public function reference( string $reference_id)
    {
        return md5($this->api_key . '|' . urldecode($this->merchant_id) . '|' . urldecode($reference_id));
    }

    // https://www.betterpay.me/docs/?php#mpgs_tokenization9
    public function tokenizationCharge( string $token, string $invoice, int $amount)
    {
        return md5($this->api_key . '|' . urldecode($this->merchant_id) . '|' . urldecode($token) . '|' . urldecode($invoice) . '|' . urldecode($amount));
    }
}
