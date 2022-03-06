<?php

namespace Sykez\BetterPay\Exceptions;

use Exception;

class BetterPayException extends Exception
{
    private static $http_code = null;
    private static $error_code = null;
    private static $error_message = null;

    /**
     * @return static
     */
    public static function ConfigException()
    {
        return new static('Missing BetterPay API key, merchant id, API URL, callback URL, success URL, or fail URL.');
    }

    /**
     * @return static
     */
    public static function ClientException($exception)
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

    public static function GetHttpCode()
    {
        return self::$http_code;
    }

    public static function GetErrorCode()
    {
        return self::$error_code;
    }

    public static function GetErrorMessage()
    {
        return self::$error_message;
    }
}
