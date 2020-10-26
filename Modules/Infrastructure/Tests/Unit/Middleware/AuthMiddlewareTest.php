<?php

namespace Modules\Infrastructure\Tests\Unit\Middleware;

use Tests\TestCase;
use \Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;


class AuthMiddlewareTest extends TestCase
{
   
     /** @test */
     public function test_requests_missing_identityToken_throws_unauthenticated_exception()
     {
        
        $request = Request::create('api/v1/tpas', 'GET');
        $request['Accept'] = 'application/json';
        $request['Content-Type'] = 'application/json';       

        $middleware = app(Authenticate::class);      
 
        $this->expectException(AuthenticationException::class);
        $middleware->handle($request, function () {});       
        
     }
    
}
