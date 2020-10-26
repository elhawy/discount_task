<?php
 

 namespace Modules\Infrastructure\Exceptions;

 Use Modules\Infrastructure\Exceptions\AbstractCoralException;
 use Symfony\Component\HttpFoundation\Response;
 use Modules\Infrastructure\Exceptions\ErrorCodes;

class CustomException extends AbstractCoralException
{
      
     function setErrorCode()
     {
         $this->_errorcode = ErrorCodes::MY_ERROR_CODE_EXAMPLE ;
     }

      function setStatusCode()
      {
        $this->_statusCode = Response::HTTP_UNPROCESSABLE_ENTITY;
      }

     function setMessage()
     {  
        $this->_message = "My Custom Exception Message";
     }       
    
  
}