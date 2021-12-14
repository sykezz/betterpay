<?php

namespace Sykez\BetterPay\Exceptions;

use Exception;

class BetterPayException extends Exception
{
    static protected $response_codes = [
        //
    ];

    /**
     * @return static
     */
    public static function ClientException($exception)
    {
        $message = $exception->getMessage();

        // if (in_array($message, array_keys(self::$response_codes))) {
        //     return new static(self::$response_codes[$message] . " [{$message}]");
        // }

        return new static($message);
    }
}