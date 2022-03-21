<?php

namespace Sykez\Betterpay\Exceptions;

use Exception;

class BetterpayException extends Exception
{
    private static $http_code = null;
    private static $error_code = null;
    private static $error_message = null;

    /**
     * Configuration exception
     *
     * @return static
     */
    public static function configException(): static
    {
        return new static('Missing Betterpay API key, merchant id, API URL, callback URL, success URL, or fail URL.');
    }

    /**
     * Sandbox 3DS exception
     *
     * @return static
     */
    public static function sandbox3dsException(): static
    {
        return new static('Sandbox mode is not supported with 3DS.');
    }

    /**
     * Client exception
     *
     * @param  mixed $exception
     * @return static
     */
    public static function clientException($exception): static
    {
        self::$http_code = $exception->getCode();
        $response = json_decode($exception->getResponse()->getBody()->getContents(), true);

        if ($response && $response['response'] && $response['msg']) {
            self::$error_code = $response['response'];
            self::$error_message = $response['msg'];
        } else {
            self::$error_message = $exception->getMessage();
        }

        return new static(self::$error_message);
    }

    /**
     * Get http code
     *
     * @return static
     */
    public static function getHttpCode(): static
    {
        return self::$http_code;
    }

    /**
     * Get error code
     *
     * @return static
     */
    public static function getErrorCode(): static
    {
        return self::$error_code;
    }

    /**
     * Get error message
     *
     * @return static
     */
    public static function getErrorMessage(): static
    {
        return self::$error_message;
    }
}
