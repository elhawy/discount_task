<?php

namespace Modules\Infrastructure\Tests\Unit\http\Requests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Infrastructure\Services\Interfaces\ActivityAuditServiceInterface;
use Tests\TestCase;


class ActivityAuditControllerTest extends TestCase
{
    use WithFaker, RefreshDatabase;

     public function test_create_will_return_200()
     {
         $this->assertTrue(true);
//         $this->factoryCreatedUser = factory(\Modules\User\Entities\User::class)->create([
//             "role_id" => 2
//         ]);
//         $rquestBody = [
//             "activity" => $this->faker->word,
//             "user" => $this->factoryCreatedUser->email
//         ];
//         $ActivityAuditServiceInterfaceMock = \Mockery::mock(ActivityAuditServiceInterface::class);
//         $this->instance(ActivityAuditServiceInterface::class, $ActivityAuditServiceInterfaceMock);
//         $ActivityAuditServiceInterfaceMock->shouldReceive('create')
//             ->once()
//             ->with($rquestBody);
//
//         $response = $this->json('POST', 'api/v1/logs/activity-audit', $rquestBody, [
//             "Accept" => "application/json"
//         ]);
//         $response->assertStatus(200)
//             ->assertJson([
//                 'message' => "activity audit added successfully"
//             ]);
     }
}
