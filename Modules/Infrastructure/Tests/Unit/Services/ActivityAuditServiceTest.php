<?php

namespace Modules\Infrastructure\Tests\Unit\Utilities;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Modules\Infrastructure\Services\Interfaces\ActivityAuditServiceInterface;
use Tests\TestCase;
use TiMacDonald\Log\LogFake;


class ActivityAuditServiceTest extends TestCase
{
    use WithFaker;

    public function test_create_will_call_awsActivityAuditS3_channel_with_info_level()
    {
        Log::swap(new LogFake);
        $this->logData = [
            "user" => $this->faker->email,
            "activity" => $this->faker->word
        ];
        $activityAuditService = $this->app->make(ActivityAuditServiceInterface::class);
        $activityAuditService->create($this->logData);
        Log::channel("awsActivityAuditS3")->assertLogged('info');
    }
}
