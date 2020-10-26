<?php

namespace Modules\Infrastructure\Tests\Unit\Middleware;

use Tests\TestCase;
use \Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;
use Modules\User\Entities\User;
use Modules\User\Entities\RoleUser;
use Modules\Infrastructure\Http\Middleware\RoleMiddleware;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Modules\User\Entities\Lookups\UserRolesLookup;


class RoleMiddlewareTest extends TestCase
{

    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        factory(RoleUser::class)->create(["role" => "coral_admin"]);
        factory(RoleUser::class)->create(["role" => "super_admin"]);

    }

    /** @test */
    public function test_request_user_invalidRole_throws_unauthorizedException()
    {

        $user = factory(User::class)->create(["role_id" => UserRolesLookup::TPA_SUPER_ADMIN]);


        $request = Request::create('api/v1/tpas', 'GET');
        $request['Accept'] = 'application/json';
        $request['Content-Type'] = 'application/json';

        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $middleware = new RoleMiddleware;

        $this->expectException(AuthorizationException::class);
        $middleware->handle($request, function () {},"coral-admin");

    }

    public function test_request_user_valid_role_succeeds()
    {

        $user = factory(User::class)->create(["role_id" => UserRolesLookup::CORAL_ADMIN ]);

        $request = Request::create('api/v1/tpas', 'GET');
        $request['Accept'] = 'application/json';
        $request['Content-Type'] = 'application/json';

        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $middleware = new RoleMiddleware;

        $response = $middleware->handle($request, function () {},"coral_admin");
        $this->assertEquals($response, null);

    }

}
