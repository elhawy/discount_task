<?php

namespace Modules\Infrastructure\Utilities\Interfaces;

interface Base64FileDecoderInterface
{
    public function decodeBase64Image(string $base64Image): array;
    public function decodeBase64File(string $base64Image): array;
    public function getBase64MimeType(string $base64Image): string;
    public function getBase64MimtypeExtension(string $base64Image): string;
}
