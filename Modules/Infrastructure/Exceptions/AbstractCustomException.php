<?php

namespace Modules\Infrastructure\Exceptions;

use Exception;

abstract class AbstractCustomException extends Exception
{
    protected $statusCode;
    protected $errorcode;
    protected $message;

    abstract protected function setErrorCode();
    abstract protected function setStatusCode();
    abstract protected function setMessage();

    public function __construct()
    {
        $this->setStatusCode();
        $this->setErrorCode();
        $this->setMessage();

        parent::__construct();
    }

    /**
     * Report the exception.
     *
     * @return void
     */
    public function report()
    {
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        $data = array(
            'code' => $this->errorcode,
            'message' => $this->message,
        );

        return response()->json($data, $this->statusCode);
    }
}
