<?php

namespace Modules\Infrastructure\Tests\Unit\Utilities;

use Modules\Infrastructure\Exceptions\FileUploadFailedException;
use Modules\Infrastructure\Utilities\Base64FileDecoder;
use Modules\Infrastructure\Utilities\FileUploader;
use Modules\Infrastructure\Utilities\Interfaces\FileUploaderInterface;
use Modules\Infrastructure\Utilities\Interfaces\Base64FileDecoderInterface;
use Tests\TestCase;

class FileUploaderTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
    }

    public function tearDown(): void
    {
        \Mockery::close();
    }

    public function test_upload_file_succefully()
    {
        $dummyImage = "data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD...";
        $base64ImageDecoder = \Mockery::mock(Base64FileDecoderInterface::class);
        $base64ImageDecoder->shouldReceive('decodeBase64File')
            ->once()
            ->andReturn([
                "extension" => "jpeg",
                "body" => "/9j/4AAQSkZJRgABAQAAAQABAAD..",
            ]);

        app()->bind(Base64FileDecoder::class, function () use ($base64ImageDecoder) {
            return $base64ImageDecoder;
        });

        $uploaderMock = \Mockery::mock(FileUploader::class)->makePartial();
        $uploaderMock->shouldReceive('upload')
            ->once()
            ->andReturn("user_profile_1587387129.jpeg");
        $result = $uploaderMock->uploadBase64File($dummyImage);
        $this->assertNotEmpty($result);
        $this->assertEquals("user_profile_1587387129.jpeg", $result);
    }

    public function test_decode_base64_image_failed_will_throw_exception()
    {
        $this->withoutExceptionHandling();
        $message = trans('messages.file_upload_failed');
        $this->expectExceptionMessage($message);
        $this->expectException(FileUploadFailedException::class);
        $dummyImage = "data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD...";

        $base64ImageDecoder = \Mockery::mock(Base64FileDecoderInterface::class);
        $base64ImageDecoder->shouldReceive('decodeBase64File')
            ->once()
            ->andReturn([
                "extension" => "jpeg",
                "body" => "/9j/4AAQSkZJRgABAQAAAQABAAD..",
            ]);

        app()->bind(Base64FileDecoder::class, function () use ($base64ImageDecoder) {
            return $base64ImageDecoder;
        });

        $uploaderMock = \Mockery::mock(FileUploader::class)->makePartial(['upload', 'uploadBase64File']);
        $uploaderMock->shouldReceive('upload')
            ->once()
            ->andThrow(new FileUploadFailedException());

        $uploaderMock->uploadBase64File($dummyImage);
    }
}
