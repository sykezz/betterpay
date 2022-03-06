<?php

namespace Sykez\BetterPay;

use GuzzleHttp\Client;
use Sykez\BetterPay\Exceptions\BetterPayException;
use GuzzleHttp\Exception\ClientException;
use Sykez\BetterPay\Hashing;

class BetterPay
{
    protected $client;
    protected $api_key;
    protected $merchant_id;
    protected $callback_url;
    protected $success_url;
    protected $fail_url;

    public function __construct(?string $api_key, ?string $merchant_id, string $api_url, ?string $callback_url, ?string $success_url, ?string $fail_url)
    {
        if (!$api_key || !$merchant_id || !$api_url || !$callback_url || !$success_url || !$fail_url) {
            throw BetterPayException::ConfigException();
        }

        $this->api_key = $api_key;
        $this->merchant_id = $merchant_id;
        $this->api_url = $api_url;
        $this->callback_url = $callback_url;
        $this->success_url = $success_url;
        $this->fail_url = $fail_url;
    }

    public function createTokenizationUrl(string $reference_id, int $skip_receipt = 0)
    {
        $hash = Hashing::reference($this->api_key, $this->merchant_id, $reference_id);
        $payload = [
            'merchant_id' => $this->merchant_id,
            'reference_id' => $reference_id,
            'callback_url_be' => $this->callback_url,
            'callback_url_fe_succ' => $this->success_url,
            'callback_url_fe_fail' => $this->fail_url,
            'skip_receipt' => $skip_receipt,
            'hash' => $hash,
        ];

        $response = $this->http_request('cards/token/v1/create', $payload);

        return $response;
    }

    public function charge(string $token, string $invoice, int $amount, int $sandbox_charge_status = 0)
    {
        $hash = Hashing::tokenizationCharge($this->api_key, $this->merchant_id, $token, $invoice, $amount);
        $payload = [
            'merchant_id' => $this->merchant_id,
            'invoice' => $invoice,
            'amount' => $amount,
            'sandbox_charge_status' => $sandbox_charge_status,
            'hash' => $hash,
        ];
        $response = $this->http_request('cards/token/v1/pay/' . $token, $payload);
        return $response;
    }

    public function http_request(string $url, array $payload)
    {
        $client = new Client();
        try {
            $response = $client->post(
                $this->api_url . $url,
                ['json' => $payload]
            );
            $data = json_decode($response->getBody(), true);

            return $data;
        } catch (ClientException $exception) {
            throw BetterPayException::ClientException($exception);
        }
    }
}
