<?php

namespace Modules\Orders\Exceptions;

use Modules\Infrastructure\Exceptions\AbstractCustomException;
use Modules\Infrastructure\Exceptions\ErrorCodes;
use Symfony\Component\HttpFoundation\Response;

class InvalidCurrencyException extends AbstractCustomException
{

    public function setErrorCode()
    {
        $this->errorcode = ErrorCodes::INVALID_CURRENCY;
    }

    public function setStatusCode()
    {
        $this->statusCode = Response::HTTP_UNPROCESSABLE_ENTITY;
    }

    public function setMessage()
    {
        $this->message = trans('orders::messages.invalid_currency');
    }
}
