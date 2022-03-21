<?php

namespace Sykez\Betterpay;

trait Tokenization
{
    
    /**
     * Create token verification URL
     *
     * @param  mixed $reference_id
     * @param  mixed $amount
     * @param  mixed $invoice
     * @param  mixed $skip_receipt
     * @return mixed
     */
    public function createTokenVerificationUrl(string $reference_id, ?float $amount = null, ?string $invoice = null, int $skip_receipt = 0): mixed
    {
        // $amount & $invoice needs to be in hash if customized/!null
        $hash = $amount && $invoice ? $this->makeHash($reference_id, $amount, $invoice) : $this->makeHash($reference_id);

        $payload = [
            'merchant_id' => $this->merchant_id,
            'reference_id' => $reference_id,
            'hash' => $hash,

            // Optional
            'amount' => $amount,
            'invoice' => $invoice,
            'callback_url_be' => $this->callback_url,
            'callback_url_fe_succ' => $this->success_url,
            'callback_url_fe_fail' => $this->fail_url,
            'skip_receipt' => $skip_receipt,
        ];
        $payload =  $this->filterPayload($payload);
        $response = $this->httpRequest('cards/token/v1/' . ($this->is_3ds ? '3ds/' : '') . 'create', $payload);

        return $response;
    }
    
    /**
     * Charge token card
     *
     * @param  mixed $token
     * @param  mixed $invoice
     * @param  mixed $amount
     * @param  mixed $sandbox_charge_status
     * @return mixed
     */
    public function chargeTokenCard(string $token, string $invoice, float $amount, int $sandbox_charge_status = null): mixed
    {
        $hash = $this->makeHash($token, $invoice, $amount);
        $payload = [
            'merchant_id' => $this->merchant_id,
            'invoice' => $invoice,
            'amount' => $amount,
            'sandbox_charge_status' => $sandbox_charge_status,
            'hash' => $hash,
        ];
        $payload =  $this->filterPayload($payload);
        $response = $this->httpRequest('cards/token/v1/' . ($this->is_3ds ? '3ds/' : '') . 'pay/' . $token, $payload);

        return $response;
    }
        
    /**
     * Delete token card
     *
     * @param  mixed $token
     * @return mixed
     */
    public function deleteTokenCard(string $token): mixed
    {
        $hash = $this->makeHash($token);
        $payload = [
            'merchant_id' => $this->merchant_id,
            'hash' => $hash,
        ];
        $response = $this->httpRequest('cards/token/v1/' . ($this->is_3ds ? '3ds/' : '') . 'delete_token/' . $token, $payload);

        return $response;
    }
    
    /**
     * Get tokens
     *
     * @param  mixed $date_from
     * @param  mixed $date_to
     * @param  mixed $status
     * @param  mixed $page
     * @return mixed
     */
    public function getTokens(?string $date_from = null, ?string $date_to = null, ?int $status = null, ?int $page = null): mixed
    {
        $hash = $this->makeHash();
        $payload = [
            'merchant_id' => $this->merchant_id,
            'hash' => $hash,

            // Optional
            'date_from' => $date_from,
            'date_to' => $date_to,
            'status' => $status,
            'page' => $page,
        ];
        $payload =  $this->filterPayload($payload);
        $response = $this->httpRequest('cards/token/v1/' . ($this->is_3ds ? '3ds/' : '') . 'get_token/', $payload);

        return $response;
    }
    
    /**
     * Get token details
     *
     * @param  mixed $reference_id
     * @return mixed
     */
    public function getTokenDetails(string $reference_id): mixed
    {
        $hash = $this->makeHash($reference_id);
        $payload = [
            'merchant_id' => $this->merchant_id,
            'hash' => $hash,
        ];
        $response = $this->httpRequest('cards/token/v1/' . ($this->is_3ds ? '3ds/' : '') . 'get_token_details/' . $reference_id, $payload);

        return $response;
    }
    
    /**
     * Get token transactions
     *
     * @param  mixed $date_from
     * @param  mixed $date_to
     * @param  mixed $status
     * @param  mixed $page
     * @return mixed
     */
    public function getTokenTransactions(?string $date_from = null, ?string $date_to = null, ?int $status = null, ?int $page = null): mixed
    {
        $hash = $this->makeHash();
        $payload = [
            'merchant_id' => $this->merchant_id,
            'hash' => $hash,

            // Optional
            'date_from' => $date_from,
            'date_to' => $date_to,
            'status' => $status,
            'page' => $page,
        ];
        $payload =  $this->filterPayload($payload);
        $response = $this->httpRequest('cards/token/v1/' . ($this->is_3ds ? '3ds/' : '') . 'get_transaction/', $payload);

        return $response;
    }
    
    /**
     * Get token transaction details
     *
     * @param  mixed $invoice
     * @return mixed
     */
    public function getTokenTransactionDetails(string $invoice): mixed
    {
        $hash = $this->makeHash($invoice);
        $payload = [
            'merchant_id' => $this->merchant_id,
            'hash' => $hash,
        ];
        $response = $this->httpRequest('cards/token/v1/' . ($this->is_3ds ? '3ds/' : '') . 'get_transaction_details/' . $invoice, $payload);

        return $response;
    }
}
