<?php

namespace Sykez\Betterpay;

use GuzzleHttp\Client;
use Sykez\Betterpay\Exceptions\BetterpayException;
use GuzzleHttp\Exception\ClientException;
use Sykez\Betterpay\HashVerification;
use Sykez\Betterpay\Tokenization;

class Betterpay
{
    use HashVerification, Tokenization;

    /**
     * API endpoints
     * @var string
     */
    protected $production_url = 'https://www.betterpay.me/merchant/api/';    
    protected $sandbox_url = 'https://www.demo.betterpay.me/merchant/api/';

    protected $client;
    protected $api_url;
    protected $api_key;
    protected $merchant_id;
    protected $callback_url;
    protected $success_url;
    protected $fail_url;
    protected $sandbox;
    protected $is_3ds;
        
    /**
     * __construct
     *
     * @param  mixed $api_key
     * @param  mixed $merchant_id
     * @param  mixed $callback_url
     * @param  mixed $success_url
     * @param  mixed $fail_url
     * @param  mixed $sandbox
     * @param  mixed $is_3ds
     * @return void
     */
    public function __construct(?string $api_key, ?int $merchant_id, ?string $callback_url, ?string $success_url, ?string $fail_url, bool $sandbox = false, bool $is_3ds = true)
    {
        if (!$api_key || !$merchant_id || !$callback_url || !$success_url || !$fail_url) {
            throw BetterpayException::configException();
        }

        if ($sandbox && $is_3ds) {
            throw BetterpayException::sandbox3dsException();
        }

        $this->api_key = $api_key;
        $this->merchant_id = $merchant_id;
        $this->callback_url = $callback_url;
        $this->success_url = $success_url;
        $this->fail_url = $fail_url;
        $this->sandbox = $sandbox;
        $this->is_3ds = $is_3ds;
        $this->api_url = $this->sandbox ? $this->sandbox_url : $this->production_url;
    }
    
    /**
     * Set 3D Secure mode. Not supported in sandbox mode.
     *
     * @param  mixed $is_3ds
     * @return void
     */
    public function set3DS(bool $is_3ds)
    {
        $this->is_3ds = $is_3ds;
    }
    
    /**
     * Set API URL manually, in case you need to.
     *
     * @param  mixed $api_url
     * @return void
     */
    public function setApiUrl(string $api_url)
    {
        $this->api_url = $api_url;
    }
    
    /**
     * Set callback URL
     *
     * @param  mixed $callback_url
     * @return void
     */
    public function setCallbackUrl(string $callback_url)
    {
        $this->callback_url = $callback_url;
    }
    
    /**
     * Set success URL
     *
     * @param  mixed $success_url
     * @return void
     */
    public function setSuccessUrl(string $success_url)
    {
        $this->success_url = $success_url;
    }
    
    /**
     * Set fail URL
     *
     * @param  mixed $fail_url
     * @return void
     */
    public function setFailUrl(string $fail_url)
    {
        $this->fail_url = $fail_url;
    }
    
    /**
     * Hash (API Key + Merchant ID + ...)
     *
     * @param  mixed $args
     * @return string
     */
    public function makeHash(string ...$args): string
    {
        return md5(implode('|', [$this->api_key, $this->merchant_id, ...$args]));
    }
    
    /**
     * Hash2 - reverse of hash() (Merchant ID + API Key + ...)
     *
     * @param  mixed $args
     * @return string
     */
    public function makeHash2(string ...$args): string
    {
        return md5(implode('|', [$this->merchant_id, $this->api_key, ...$args]));
    }
    
    /**
     * Filter payload; remove null or '' array
     *
     * @param  mixed $payload
     * @return array
     */
    public function filterPayload(array $payload): array
    {
        return array_filter($payload, fn ($v) => $v !== null && $v !== '');
    }
    
    /**
     * httpRequest
     *
     * @param  mixed $url
     * @param  mixed $payload
     * @return string
     */
    public function httpRequest(string $url, array $payload): array
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
            throw BetterpayException::clientException($exception);
        }
    }
}
