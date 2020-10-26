<?php

namespace Modules\Infrastructure\Tests\Unit\Utilities;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Modules\Infrastructure\Logging\AWSS3Logger;
use Tests\TestCase;
use TiMacDonald\Log\LogFake;


class AWSS3LoggerTest extends TestCase
{
    use WithFaker;

    public function tearDown(): void
    {
        \Mockery::close();
    }

    public function generateFileNamePeriodicity_data_provider()
    {
        return [
            'generate File Name monthly'
            => ["monthly", "-" . date("y-m")],
            'generate File Name yearly'
            => ["yearly", "-" . date("y")],
            'generate File Name daily'
            => ["daily", "-" . date("y-m-d")],
            'generate File Name by default will return daily'
            => ["", "-" . date("y-m-d")],
        ];
    }

    /**
     * @dataProvider generateFileNamePeriodicity_data_provider
     */
    public function test_generateFileNamePeriodicity_return_periodicity($periodicity, $expectedResult)
    {
        $AWSS3Logger = $this->app->make(AWSS3Logger::class);
        $AWSS3LoggeRestult = $AWSS3Logger->generateFileNamePeriodicity($periodicity);
        $this->assertEquals($expectedResult, $AWSS3LoggeRestult);
    }

    public function test_S3ClientregisterStreamWrapper_return_S3Client_object()
    {
        $awsActivityAuditS3Attributes = $this->app['config']['logging']['channels']['awsActivityAuditS3'];
        $AWSS3Logger = $this->app->make(AWSS3Logger::class);
        $result = $AWSS3Logger->S3ClientregisterStreamWrapper($awsActivityAuditS3Attributes['configration']['aws']);
        $this->assertInstanceOf(\Aws\S3\S3Client::class, $result);
    }

    public function test_invoke_will_call_generateFileNamePeriodicity_and_S3ClientregisterStreamWrapper_methods_once()
    {
        $awsActivityAuditS3Attributes = $this->app['config']['logging']['channels']['awsActivityAuditS3'];

        $AWSS3LoggerMock = \Mockery::mock(AWSS3Logger::class)->makePartial();
        $this->instance(AWSS3Logger::class, $AWSS3LoggerMock);
        $AWSS3LoggerMock->shouldReceive('generateFileNamePeriodicity')
            ->once()
            ->with($awsActivityAuditS3Attributes['configration']['periodicity']);
        $AWSS3LoggerMock->shouldReceive('S3ClientregisterStreamWrapper')
            ->once()
            ->with($awsActivityAuditS3Attributes['configration']['aws']);
        $AWSS3Logger = $this->app->make(AWSS3Logger::class);
        $result = $AWSS3Logger->__invoke($awsActivityAuditS3Attributes);
        $this->assertInstanceOf(\Monolog\Logger::class, $result);
    }

    public function test_awsActivityAuditS3_channel_configration_attributes_not_have_null_value()
    {
        $awsActivityAuditS3Attributes = $this->app['config']['logging']['channels']['awsActivityAuditS3'];
        foreach ($awsActivityAuditS3Attributes['configration'] as $value) {
            $this->assertNotNull($value);
        }
    }

    public function test_awsActivityAuditS3_channel_configration_attributes_have_valid_aws_s3_attributes()
    {
        $awsActivityAuditS3Attributes = $this->app['config']['logging']['channels']['awsActivityAuditS3'];
        $S3Client = new \Aws\S3\S3Client($awsActivityAuditS3Attributes['configration']['aws']);
        $this->assertInstanceOf(\Aws\S3\S3Client::class, $S3Client);
    }


    public function test_awsActivityAuditS3_channel_Logged_info_level_with_json_structure_of_logged_message()
    {
        Log::swap(new LogFake);
        $this->logData = [
            "user" => $this->faker->email,
            "activity" => $this->faker->word,
            "time" => now("M d Y H:i:s")
        ];
        Log::channel("awsActivityAuditS3")->info(json_encode($this->logData));
        Log::channel("awsActivityAuditS3")->assertLogged('info', function ($message) {
            return ($message === json_encode($this->logData));
        });
    }
}
