<?php

namespace Modules\Infrastructure\Http\Traits;

use Illuminate\Http\Response;

trait ApiErrorResponse
{
    public function errorResponse($errorCode,$message,$statusCode = Response::HTTP_INTERNAL_SERVER_ERROR)
    {  
        $error = array(
            'code'      => $errorCode,     
            'message'   => $message 
              
        );
        
        return response()->json(['error'=>$error],$statusCode);
      
    }
}