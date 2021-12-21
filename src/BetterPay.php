<?php

namespace Sykez\BetterPay;

use GuzzleHttp\Client;
use Sykez\BetterPay\Exceptions\BetterPayException;
use Exception;

class BetterPay
{
    protected $client, $api_key, $merchant_id, $callback_url, $success_url, $fail_url;

    public function __construct(string $api_key, string $merchant_id, string $api_url, string $callback_url, string $success_url, string $fail_url)
    {
        $this->client = new Client();
        $this->api_key = $api_key;
        $this->merchant_id = $merchant_id;
        $this->api_url = $api_url;
        $this->callback_url = $callback_url;
        $this->success_url = $success_url;
        $this->fail_url = $fail_url;
    }

    public function createTokenizationUrl(string $reference_id, int $skip_receipt = 0)
    {
        $hash = md5($this->api_key.'|'.urldecode($this->merchant_id).'|'.urldecode($reference_id));

        try {
            $response = $this->client->post(
                $this->api_url.'cards/token/v1/create',
                [
                    'json' => [
                        'merchant_id' => $this->merchant_id,
                        'reference_id' => $reference_id,
                        'callback_url_be' => $this->callback_url,
                        'callback_url_fe_succ' => $this->success_url,
                        'callback_url_fe_fail' => $this->fail_url,
                        'skip_receipt' => $skip_receipt,
                        'hash' => $hash,
                    ],
                ]
            );
            $data = json_decode($response->getBody());
        } catch (Exception $exception) {
            throw BetterPayException::ClientException($exception);
        }

        return $data;
    }

    public function charge(string $token, string $invoice, int $amount, int $sandbox_charge_status = 0)
    {
        $hash = md5($this->api_key.'|'.urldecode($this->merchant_id).'|'.urldecode($token).'|'.urldecode($invoice).'|'.urldecode($amount));

        try {
            $response = $this->client->post(
                $this->api_url.'cards/token/v1/pay/'.$token,
                [
                    'json' => [
                        'merchant_id' => $this->merchant_id,
                        'invoice' => $invoice,
                        'amount' => $amount,
                        'sandbox_charge_status' => $sandbox_charge_status,
                        'hash' => $hash,
                    ],
                ]
            );
            $data = json_decode($response->getBody());
        } catch (Exception $exception) {
            throw BetterPayException::ClientException($exception);
        }

        return $data;
    }
}
