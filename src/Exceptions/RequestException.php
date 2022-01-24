<?php

namespace Sykez\BetterPay\Exceptions;

use Exception;

class RequestException extends Exception
{
    /**
     * @return static
     */
    public static function ClientException($exception)
    {
        $message = $exception->getMessage();

        return new static($message);
    }
}